<?PHP



add_shortcode("EventGalleryPage", "EventGalleryPage");
function EventGalleryPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			$jsData['eventList'] = $wpdb->get_results("select ID, Event_Name from {$wpdb->prefix}chamber_events 
			 												where  isTrash=0  order by Event_Name", 'ARRAY_A');
       		$jsData['eventList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['eventList'],
					"option_text" => array("ID"),
					"option_value" => array("Event_Name"),
        		)
			);
 		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
               $joinEvents = "{$wpdb->prefix}chamber_events";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
                              {$joinEvents}.Event_name,
                              {$joinEvents}.Start_Date,
                              {$joinEvents}.End_Date
                              FROM  {$thisTbl}
                				JOIN {$joinEvents} ON {$joinEvents}.id = {$thisTbl}.Event
								
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Event,{$thisTbl}.View_Order";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Event_name" => array("value" => "Event_name", "type" => "text"),
                    "Start_Date" => array("value" => "Start_Date", "type" => "text"),
                    "End_Date" => array("value" => "End_Date", "type" => "text"),
					"View_Order" => array("value" => "View_Order", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				$isSuccess =  $classAction->createOtherFile();
				if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'event_gallery';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("UpdateProfilePage", "UpdateProfilePage");
function UpdateProfilePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
  	$screen = "first";

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			$jsData['designationList'] = $wpdb->get_results("select ID,Designation from {$wpdb->prefix}designation 
			 												where  isTrash=0 order by Designation", 'ARRAY_A');
       		$jsData['designationList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['designationList'],
					"option_text" => array("ID"),
					"option_value" => array("Designation"),
        		)
			);
			
			$jsData['userRoleList'] = $wpdb->get_results("select User_Role from {$wpdb->prefix}user_role 
			 												where  isTrash=0 order by User_Role", 'ARRAY_A');
       		$jsData['userRoleList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['userRoleList'],
					"option_text" => array("User_Role"),
					"option_value" => array("User_Role"),
        		)
			);
			
			$jsData['countryList'] = $wpdb->get_results("select ID,Country from {$wpdb->prefix}country 
			 												where  isTrash=0 order by Country", 'ARRAY_A');
       		$jsData['countryList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['countryList'],
					"option_text" => array("ID"),
					"option_value" => array("Country"),
        		)
			);
			
			$jsData['stateList'] = $wpdb->get_results("select ID,State from {$wpdb->prefix}state 
			 												where  isTrash=0 order by State", 'ARRAY_A');
       		$jsData['stateList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['stateList'],
					"option_text" => array("ID"),
					"option_value" => array("State"),
        		)
			);
			
			$jsData['cityList'] = $wpdb->get_results("select ID,City from {$wpdb->prefix}city 
			 												where  isTrash=0 order by City", 'ARRAY_A');
       		$jsData['cityList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['cityList'],
					"option_text" => array("ID"),
					"option_value" => array("City"),
        		)
			);
			
			$jsData['constitutionList'] = $wpdb->get_results("select ID,Business_Constitution 
															from {$wpdb->prefix}business_constitution 
			 												where  isTrash=0 order by Business_Constitution", 'ARRAY_A');
       		$jsData['constitutionList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['constitutionList'],
					"option_text" => array("ID"),
					"option_value" => array("Business_Constitution"),
        		)
			);
			
			$jsData['businessLineList'] = $wpdb->get_results("select ID,Business_Type 
															from {$wpdb->prefix}business_type 
			 												where  isTrash=0 order by Business_Type", 'ARRAY_A');
       		$jsData['businessLineList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['businessLineList'],
					"option_text" => array("ID"),
					"option_value" => array("Business_Type"),
        		)
			);
 		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Release_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Release_Title" => array("value" => "Release_Title", "type" => "text"),
					"Release_Date" => array("value" => "Release_Date", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				//print_r($_POST); exit;
             
                  $isSuccess =  $classAction->createOtherFile();
                  if ($isSuccess) {
                      $isSuccess =  $classAction->tableToMaster();
                  }
              
				
            }

        }
          else  if( isset( $_POST['Deactivate_Your_Account']))
          {
            $insertData['is_Activated'] = 0;
            $updateQuery['ID'] = $user->ID;
            $result = $wpdb->update($wpdb->prefix."users", $insertData , $updateQuery );
            if ($result) 
            {
              $errorMsg[] = array("Your account has been successfully deactivate.", true);
            }
            else
            {
              $errorMsg[] = array("Failed to deactivate your account.. Retry", false);
            }

          }
          else  if( isset( $_POST['Delete_Your_Account']))
          {
              $screen = "second";
              
           }
          else if( isset($_POST['Confirm']))
           {
              $insertData['is_Activated'] = 0;
              $updateQuery['ID'] = $user->ID;
              $result = $wpdb->update($wpdb->prefix."users", $insertData , $updateQuery );
              if ($result) 
              {
                $errorMsg[] = array("Your account has been successfully deleted.", true);
              }
              else
              {
                $errorMsg[] = array("Failed to delete your account.. Retry", false);
              }
			 
              $sessions = WP_Session_Tokens::get_instance( get_current_user_id());
              $sessions->destroy_all();
              wp_logout();
              wp_redirect( home_url()."/login" );
          }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
		$_POST['editForm'] = $user->ID;	
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];
		if( $screen == 'first')
        	$classUI->echoForm('no');
        else
         {
          echo '<form method="post" action="">';
          echo '<div class="row">';
          echo '<div class="col-sm-12">';
          echo '<div class=""><p>Are you sure to delete the account as account once deleted can not be retrieved , if you want to temporarily deactivate use deactivate button  by pressing no , to continue to delete your account press yes</p></div>';
          echo '</div>';
          echo '<div class="col-6 text-center">';
          echo '<input type="submit" name="Confirm" value="Yes" class="btn btn-danger" >';
          echo '</div>';
          echo '<div class="col-sm-6 text-center">';
          echo '<input type="submit" name="Cancel" value="No" class="btn btn-success" >';
          echo '</div>';
          echo '</div>';
           echo '</form>';
          
        }
       // $classUI->searchFormAndData();

        ?>
		<script>
			if (navigator.geolocation) 
			{
				navigator.geolocation.getCurrentPosition(function(position)
				{ 
					//alert(position.coords.latitude);
					$("input[name=Latitude]").val(position.coords.latitude);
					$("input[name=Longitude]").val(position.coords.longitude);	
				});
			} 
			else 
			{
				alert( "Geolocation is not supported by this browser.");
			}
         var _rulesString = {};
		var _messagesString = {};
			
			var imageType = 'users';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
		});
		
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}
	
	

add_shortcode("PoliciesPage", "PoliciesPage");
function PoliciesPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
			
// 			$atts = array();
// 			$atts['app_id'] = '8bce8e99-bd0f-4156-b9f2-4b247447cdb8';
// 			$atts['appKey'] = 'NDFiOWI2NTMtNjE0NS00Nzk2LWIzNTctMWE0NjNmNGU2NTA3';
// 			$atts['msg'] = 'Testing';
// 			$atts['large_icon'] = 'https://apchambers.in/app/wp-content/uploads/2022/04/apccif.jpg';
// 			$isSuccess = $classAction->oneSignalNotification($atts);
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.ID,
									{$thisTbl}.Policy_Description,
									{$thisTbl}.isTrash,
									DATE_FORMAT({$thisTbl}.Policy_Date ,'%d-%m-%Y') as Policy_Date 
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Policy_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Policy Date" => array("value" => "Policy_Date", "type" => "text"),
					"Policy Description" => array("value" => "Policy_Description", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
					$isSuccess =  $classAction->createOtherFile();
				if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
					if( $isSuccess && strtolower( $_POST['ADD'] ) == 'add')
					{
						$atts = array();
						$atts['appID'] = '8bce8e99-bd0f-4156-b9f2-4b247447cdb8';
						$atts['appKey'] = 'NDFiOWI2NTMtNjE0NS00Nzk2LWIzNTctMWE0NjNmNGU2NTA3';
						$atts['msg'] = 'Testing';
						$atts['large_icon'] = 'https://apchambers.in/app/wp-content/uploads/2022/04/apccif.jpg';
						//$isSuccess = $classAction->oneSignalNotification($atts);
						
					}
					
					
				}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		var imageType = 'user';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
			var files = e.target.files;
			var done = function (url) {
			  input_1.value = '';
			  image_1.src = url;
			  $modal_1.modal('show');
			};
			var reader;
			var file;
			var url;

			if (files && files.length > 0) {
			  file = files[0];

			  if (URL) {
				done(URL.createObjectURL(file));
			  } else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
				  done(reader.result);
				};
				reader.readAsDataURL(file);
			  }
			} 
		  });

      $modal_1.on('shown.bs.modal', function () {
        cropper = new Cropper(image_1, {
			  aspectRatio: "free",
			  viewMode: 2,
			});
		  }).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		  });  

		  crop_1.addEventListener('click', function () {
			var canvas;
			$modal_1.modal('hide');
			if (cropper) {
			  canvas = cropper.getCroppedCanvas();
			  avatar_1.src = canvas.toDataURL();
			  inputData_1.value =  avatar_1.src ;
			}
		  });
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("RenewalPage", "RenewalPage");
function RenewalPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			$jsData['memberNameList'] = $wpdb->get_results("select ID,Applicant_Name from {$wpdb->prefix}users 
			 												where  isTrash=0 and User_role='member'  order by Applicant_Name", 'ARRAY_A');
       		$jsData['memberNameList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['memberNameList'],
					"option_text" => array("ID"),
					"option_value" => array("Applicant_Name"),
        		)
			);
			
			$jsData['memberPlanList'] = $wpdb->get_results("select ID,Membership_Name from {$wpdb->prefix}membership_plan 
			 												where  isTrash=0  order by Membership_Name", 'ARRAY_A');
       		$jsData['memberPlanList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['memberPlanList'],
					"option_text" => array("ID"),
					"option_value" => array("Membership_Name"),
        		)
			);
			
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);
				$joinUser = "{$wpdb->prefix}users";
				$joinPlan = "{$wpdb->prefix}membership_plan";
				$field['getQry'] = "select DATE_FORMAT( {$thisTbl}.Start_Date , '%d-%m-%Y' ) as startDate,
								{$thisTbl}.ID,
								{$thisTbl}.isTrash,
								{$joinUser}.Applicant_Name,
								{$joinUser}.Mobile_No,
								{$joinPlan}.Membership_Name,
								{$joinPlan}.Charge,
								{$joinPlan}.Validity_Days,
								DATE_FORMAT( DATE_ADD(  {$thisTbl}.Start_Date , INTERVAL {$joinPlan}.Validity_Days DAY), '%d-%m-%Y' ) as expiryDate
								FROM  {$thisTbl}
								LEFT JOIN {$joinUser} ON ( {$joinUser}.ID = {$thisTbl}.	Member_Name)
								LEFT JOIN {$joinPlan} ON ( {$joinPlan}.ID = {$thisTbl}.Member_Plan)
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Start_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "edit"),
					"Start_Date" => array("value" => "startDate", "type" => "text"),
					"Expiry_Date" => array("value" => "expiryDate", "type" => "text"),
					"Applicant_Name" => array("value" => "Applicant_Name", "type" => "text"),
					"Mobile_No" => array("value" => "Mobile_No", "type" => "text"),
					"Membership_Name" => array("value" => "Membership_Name", "type" => "text"),
					"Charge" => array("value" => "Charge", "type" => "text"),
					"Validity_Days" => array("value" => "Validity_Days", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				//print_r($_POST); exit;
				//$isSuccess =  $classAction->createUploadFile();
				//if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
					if( $isSuccess)
					{
						$insertArr = $updateArr =  array();
						$insertArr['User_Role'] = 'Paid Member';
						$updateArr['ID'] = $_POST['Member_Name'];
						$result = $wpdb->update("{$wpdb->prefix}users", $insertArr, $updateArr);
						if($result)
						{
							$sessions = WP_Session_Tokens::get_instance( $_POST['Member_Name'] );
           					$sessions->destroy_all();
							$errorMsg[] = array("User converted to PAID MEMBER.", true);
							$errorMsg[] = array("You have to refresh the page.", true);
							$errorMsg[] = array("Converted member is logged out now.", true);
						}else $errorMsg[] = array("Failed to convert role.", false);
					}
				//}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
		
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

       $classUI->echoForm('yes');
        $classUI->searchFormAndData('no');

        ?>
		<script>
		
         var _rulesString = {};
		var _messagesString = {};
			
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}
	



add_shortcode("MembershipPlanPage", "MembershipPlanPage");
function MembershipPlanPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Membership_Name";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "edit"),
					"Membership_Name" => array("value" => "Membership_Name", "type" => "text"),
					"Charge" => array("value" => "Charge", "type" => "text"),
					"Validity_Days" => array("value" => "Validity_Days", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				//print_r($_POST); exit;
				//$isSuccess =  $classAction->createUploadFile();
				//if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				//}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
		
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

       $classUI->echoForm('yes');
        $classUI->searchFormAndData('no');

        ?>
		<script>
		
         var _rulesString = {};
		var _messagesString = {};
			
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}
	



add_shortcode("ChamberDesignationPage", "ChamberDesignationPage");
function ChamberDesignationPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Chamber_Designation";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Chamber_Designation" => array("value" => "Chamber_Designation", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				//print_r($_POST); exit;
				//$isSuccess =  $classAction->createUploadFile();
				//if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				//}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm('yes');
        $classUI->searchFormAndData('no');

        ?>
		<script>
	
		
         var _rulesString = {};
		var _messagesString = {};
			
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}
	

	
add_shortcode("PollsResultPage", "PollsResultPage");
function PollsResultPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($jsData);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			

			$field['fieldData']['currentId'] = $user->ID;
			
			
		

		$answerArr = array();	
       $pollAns = $wpdb->get_results("SELECT Poll,Answer,count(*) as pollAnswer FROM {$wpdb->prefix}poll_answer 
	   								where isTrash=0 GROUP  BY Poll, Answer ","ARRAY_A");
		foreach( $pollAns as $ans )
		{
			$answerArr[$ans['Poll']][$ans['Answer']] = $ans['pollAnswer'];
		}
       $polls = $wpdb->get_results("SELECT Question,ID FROM {$wpdb->prefix}opinion_poll 
	   								where isTrash=0 ORDER BY added","ARRAY_A");
		if( count ( $polls ) > 0)
		{
			?>
<script>
			var donutOptions     =   {
           tooltips: {
         enabled: true
    },
             plugins: {
            datalabels: {
                formatter: (value, ctx) => {
					
						if( parseInt(value) == 0) return '';
						var keys =  Object.keys( ctx.dataset._meta ) ;
						var datakey = keys[0];
                        let sum = ctx.dataset._meta[datakey].total;
						let percentage = (value * 100 / sum).toFixed(2);
						return value +" \n&\n "+ percentage + "%";
							
					
                   

                   
                },
                color: "#fff",
                     }
        }
    };
			</script>
<?
			echo '<div class="row mt-2">';
			$i = 1;
			foreach ( $polls as $poll )
	 	 	{
				$values = '[';
				if( isset( $answerArr[$poll['ID']] ) )
				{
					$values .=  isset( $answerArr[$poll['ID']]['YES'] ) ? $answerArr[$poll['ID']]['YES'] : '0' ;
					$values .=  isset( $answerArr[$poll['ID']]['NO'] ) ? ','.$answerArr[$poll['ID']]['NO'] : ',0' ;
					$values .=  isset( $answerArr[$poll['ID']]['Cant Say'] ) ? 
									','.$answerArr[$poll['ID']]['Cant Say'] : ',0' ;
				}
				$values .= ']';
				
				echo '<div class="col-sm-12 col-md-6">
						<div class="card card-danger">
							<div class="card-header">
								<h3 style="float: none;" class="card-title text-center">'.$poll['Question'].'</h3>
							</div>
							<div class="card-body">
								<canvas id="donutChart_'.$poll['ID'].'" 
										height="250"
										class="chartjs-render-monitor">
								</canvas>
							</div>
						</div>
					</div>
					<script>
					
					 $(function () {
					   var donutChartCanvas_'.$poll['ID'].' = $("#donutChart_'.$poll['ID'].'").get(0).getContext("2d")
						var donutData_'.$poll['ID'].'        = {
						  labels: [
							  "YES ",
							  "NO",
							  "Cant Say"
						  ],
						  datasets: [
							{
							  data: '.$values.',
							  backgroundColor : ["#00a65a","#f56954","#00c0ef"],
							}
						  ]
						};
						

						new Chart(donutChartCanvas_'.$poll['ID'].', {
						  type: "doughnut",
						  data: donutData_'.$poll['ID'].',
						  options: donutOptions,
						});
					 })</script>';
	  		}	
			echo '</div>';
		}else{
			$classUI->noDataFound("No Poll found to answer");
		}
	 
			
        
        
        

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}




add_shortcode("PollAnswerPage", "PollAnswerPage");
function PollAnswerPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			if( isset( $_POST['Update'] ) )
			{
				$isExists = $wpdb->get_results("select ID from {$wpdb->prefix}poll_answer 
												WHERE userId='".$user->ID."' 
												AND  Poll='".$_POST['Poll']."'","ARRAY_A");
				if( count ( $isExists ) ==  0 )
				{
					$insertArr = array();
					$insertArr['Answer'] = $_POST['Answer'];
					$insertArr['Poll'] = $_POST['Poll'];
					$insertArr['userId'] = $user->ID;
					$insertArr['added'] = date('Y-m-d H:i:s');
					$result = $wpdb->insert("{$wpdb->prefix}poll_answer",$insertArr);
				}else{
					$insertArr = $updateArr = array();
					$insertArr['Answer'] = $_POST['Answer'];
					$updateArr['Poll'] = $_POST['Poll'];
					$updateArr['userId'] = $user->ID;
					$result = $wpdb->update("{$wpdb->prefix}poll_answer",$insertArr,$updateArr);
				}
				if( $result )
				{
					$errorMsg[] = array("{$_POST['Update']} successfully", true);
				}else{
					$errorMsg[] = array(" Failed to {$_POST['Update']}", true);
				}
			}
		if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

		$answerArr = array();	
       $pollAns = $wpdb->get_results("SELECT Poll, Answer FROM {$wpdb->prefix}poll_answer 
	   								where isTrash=0 AND userId={$user->ID}","ARRAY_A");
		foreach( $pollAns as $ans )
		{
			$answerArr[$ans['Poll']] = $ans['Answer'];
		}
       $polls = $wpdb->get_results("SELECT Question,ID FROM {$wpdb->prefix}opinion_poll 
	   								where isTrash=0 ORDER BY added","ARRAY_A");
		if( count ( $polls ) > 0)
		{
			echo '<table class="table table-bordered table-hover dataTable dtr-inline" >
					<thead>
						<tr>
							<th>#</th>
							<th>Opinion Poll</th>
							<th>Answer</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					';
			$i = 1;
			foreach ( $polls as $poll )
	 	 	{
				$options = '';
				foreach ( $jsData['answerList'] as $key => $value )
				{
					$selected = isset( $answerArr[$poll['ID']] ) ? ( 
										$answerArr[$poll['ID']] == $key ? 'selected' : '' ) : '';
					$options .= '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
				}
				$button = isset( $answerArr[$poll['ID']] ) ?  'Update' : 'Add';
		  		echo '<tr>
						<form method="post">
							<th>'.$i.'</th>
							<th>'.$poll['Question'].'</th>
							<th><select class="form-control" required 
										name="Answer">'.$options.'</select></th>
							<th>
								<input type="hidden" name="Poll" value="'.$poll['ID'].'" >
								<input type="submit" class="btn btn-outline-primary" 
											name="Update" value="'.$button.'" />
								
							</th>
							</form>
						</tr>';
				$i++;
	  		}	
			echo '</tbody>
					<tfoot>
						<tr>
							<th>#</th>
							<th>Opinion Poll</th>
							<th>Answer</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>';
		}else{
			$classUI->noDataFound("No Poll found to answer");
		}
	 
			
        
        
        

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("ExecutiveProfilePage", "ExecutiveProfilePage");
function ExecutiveProfilePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
			$jsData['executiveMemberList'] = $wpdb->get_results("select ID, Applicant_Name from {$wpdb->prefix}users 
			 												where  isTrash=0  AND Page_Name != ''
															order by Applicant_Name", 'ARRAY_A');
       		$jsData['executiveMemberList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['executiveMemberList'],
					"option_text" => array("ID"),
					"option_value" => array("Applicant_Name"),
        		)
			);
		
			$classUI->getTableField();
			
			
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				$userTbl = "{$wpdb->prefix}users";
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.ID,
											{$thisTbl}.isTrash,
										   {$userTbl}.Applicant_Name
								FROM  {$thisTbl}
								LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$thisTbl}.Executive_Member )
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.added";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Member_Name" => array("value" => "Applicant_Name", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				$isSuccess =  $classAction->createOtherFile();
				if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}



		
add_shortcode("TVReleasePage", "TVReleasePage");
function TVReleasePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Release_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Release_Title" => array("value" => "Release_Title", "type" => "text"),
					"Release_Date" => array("value" => "Release_Date", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				//$isSuccess =  $classAction->createUploadFile();
				//if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				//}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}

	
	
add_shortcode("PressReleasePage", "PressReleasePage");
function PressReleasePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Release_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Release_Title" => array("value" => "Release_Title", "type" => "text"),
					"Release_Date" => array("value" => "Release_Date", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				$isSuccess =  $classAction->createOtherFile();
				if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'press_release';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		 input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


	
add_shortcode("ChemberSeminarPage", "ChemberSeminarPage");
function ChemberSeminarPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
									DATE_FORMAT({$thisTbl}.Seminar_Date,'%d-%m-%Y') as seminarDate
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Seminar_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Seminar | Workshop Name" => array("value" => "seminarDate", "type" => "text"),
					"Seminar_Name" => array("value" => "Seminar_Name", "type" => "text"),
					"Description" => array("value" => "Seminar_Description", "type" => "text"),
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

           
				if ($isSuccess) {
					$isSuccess =  $classAction->createOtherFile();
					if ($isSuccess) {
           	 			$isSuccess =  $classAction->tableToMaster();
					}
				}
            

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'seminar';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 
		
			$('[data-toggle="tooltip"]').tooltip();

			input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
		
		  
		  
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}

add_shortcode("MagazinePage", "MagazinePage");
function MagazinePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	//phpinfo();
	//echo '<pre>';
	//print_r($_SERVER);
	//echo '</pre>';
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.View_Order";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"View_Order" => array("value" => "View_Order", "type" => "text"),
					"Magazine_Name" => array("value" => "Magazine_Name", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

           
				if ($isSuccess) {
					$isSuccess =  $classAction->createUploadFile();
					if ($isSuccess) {
						$isSuccess =  $classAction->createOtherFile();
						if ($isSuccess) {
           	 				$isSuccess =  $classAction->tableToMaster();
						}
					}
				}
            

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'magazine';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();
			input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  file = files[0];
				    avatar_1.src = URL.createObjectURL(file);

		
			} 
		  });
		});
			/*
		 window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			  var avatar_2 = document.getElementById('uploaded_image_userProfile_2');
			  var image_2 = document.getElementById('sample_userProfile_2');
			  var input_2 = document.getElementById('userProfile_2');
			  var inputData_2 = document.getElementById('DatauserProfile_2');
			  var crop_2 = document.getElementById('crop_userProfile_2');
			  var $modal_2 = $('#modal_userProfile_2');
			  var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
			var files = e.target.files;
			var done = function (url) {
			  input_1.value = '';
			  image_1.src = url;
			  $modal_1.modal('show');
			};
			var reader;
			var file;
			var url;

			if (files && files.length > 0) {
			  file = files[0];

			  if (URL) {
				done(URL.createObjectURL(file));
			  } else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
				  done(reader.result);
				};
				reader.readAsDataURL(file);
			  }
			} 
		  });

      $modal_1.on('shown.bs.modal', function () {
        cropper = new Cropper(image_1, {
			  aspectRatio: "free",
			  viewMode: 2,
			});
		  }).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		  });  

		  crop_1.addEventListener('click', function () {
			var canvas;
			$modal_1.modal('hide');
			if (cropper) {
			  canvas = cropper.getCroppedCanvas();
			  avatar_1.src = canvas.toDataURL();
			  inputData_1.value =  avatar_1.src ;
			}
		  });
		  
		  
		  input_2.addEventListener('change', function (e) {
			var files = e.target.files;
			var done = function (url) {
			  input_2.value = '';
			  image_2.src = url;
			  $modal_2.modal('show');
			};
			var reader;
			var file;
			var url;

			if (files && files.length > 0) {
			  file = files[0];

			  if (URL) {
				done(URL.createObjectURL(file));
			  } else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
				  done(reader.result);
				};
				reader.readAsDataURL(file);
			  }
			} 
		  });

      $modal_2.on('shown.bs.modal', function () {
        cropper = new Cropper(image_2, {
			  aspectRatio: "free",
			  viewMode: 2,
			});
		  }).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		  });  

		  crop_2.addEventListener('click', function () {
			var canvas;
			$modal_2.modal('hide');
			if (cropper) {
			  canvas = cropper.getCroppedCanvas();
			  avatar_2.src = canvas.toDataURL();
			  inputData_2.value =  avatar_2.src ;
			}
		  });
		  
		  
		  
		  
		});*/
	
						
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}

add_shortcode("CarouselPage", "CarouselPage");
function CarouselPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.View_Order";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"View_Order" => array("value" => "View_Order", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				$isSuccess =  $classAction->createOtherFile();
				if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
				}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'carousel';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}



add_shortcode("JobPostsPage", "JobPostsPage");
function JobPostsPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
									DATE_FORMAT({$thisTbl}.Start_Date,'%d-%m-%Y') as startDate,
									DATE_FORMAT({$thisTbl}.End_Date,'%d-%m-%Y') as endDate
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Job_Title";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Job_Title" => array("value" => "Job_Title", "type" => "text"),
					"Start_Date" => array("value" => "startDate", "type" => "text"),
					"End_Date" => array("value" => "endDate", "type" => "text"),
					"Company_Name" => array("value" => "Company_Name", "type" => "text"),
					"Opening" => array("value" => "Opening", "type" => "text"),
					"Job_Description" => array("value" => "Job_Description", "type" => "text"),
					"Contact_Details" => array("value" => "Contact_Details", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}



add_shortcode("ChamberEventsPage", "ChamberEventsPage");
function ChamberEventsPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
									DATE_FORMAT({$thisTbl}.Start_Date,'%d-%m-%Y') as startDate,
									DATE_FORMAT({$thisTbl}.End_Date,'%d-%m-%Y') as endDate
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Event_Name";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Event_Name" => array("value" => "Event_Name", "type" => "text"),
					"Start_Date" => array("value" => "startDate", "type" => "text"),
					"Start_Time" => array("value" => "Start_Time", "type" => "text"),
					"End_Date" => array("value" => "endDate", "type" => "text"),
					"End_Time" => array("value" => "End_Time", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
           	 		 $isSuccess =  $classAction->createOtherFile();
					 //print_r($_POST); exit;
					 if( $isSuccess)
					 {
						 $isSuccess =  $classAction->tableToMaster();
					 }
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'chamber_events';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			
			$('[data-toggle="tooltip"]').tooltip();

			input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
		
		  
		  
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("GOsPage", "GOsPage");
function GOsPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
			
// 			$atts = array();
// 			$atts['app_id'] = '8bce8e99-bd0f-4156-b9f2-4b247447cdb8';
// 			$atts['appKey'] = 'NDFiOWI2NTMtNjE0NS00Nzk2LWIzNTctMWE0NjNmNGU2NTA3';
// 			$atts['msg'] = 'Testing';
// 			$atts['large_icon'] = 'https://apchambers.in/app/wp-content/uploads/2022/04/apccif.jpg';
// 			$isSuccess = $classAction->oneSignalNotification($atts);
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.ID,
									{$thisTbl}.GO_Description,
									{$thisTbl}.isTrash,
									DATE_FORMAT({$thisTbl}.Gos_Date ,'%d-%m-%Y') as Gos_Date 
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.GO_Description";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Gos_Date" => array("value" => "Gos_Date", "type" => "text"),
					"GO_Description" => array("value" => "GO_Description", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
					$isSuccess =  $classAction->createOtherFile();
				if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
					if( $isSuccess && strtolower( $_POST['ADD'] ) == 'add')
					{
						$atts = array();
						$atts['appID'] = '8bce8e99-bd0f-4156-b9f2-4b247447cdb8';
						$atts['appKey'] = 'NDFiOWI2NTMtNjE0NS00Nzk2LWIzNTctMWE0NjNmNGU2NTA3';
						$atts['msg'] = 'Testing';
						$atts['large_icon'] = 'https://apchambers.in/app/wp-content/uploads/2022/04/apccif.jpg';
						//$isSuccess = $classAction->oneSignalNotification($atts);
						
					}
					
					
				}
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		var imageType = 'user';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
			var files = e.target.files;
			var done = function (url) {
			  input_1.value = '';
			  image_1.src = url;
			  $modal_1.modal('show');
			};
			var reader;
			var file;
			var url;

			if (files && files.length > 0) {
			  file = files[0];

			  if (URL) {
				done(URL.createObjectURL(file));
			  } else if (FileReader) {
				reader = new FileReader();
				reader.onload = function (e) {
				  done(reader.result);
				};
				reader.readAsDataURL(file);
			  }
			} 
		  });

      $modal_1.on('shown.bs.modal', function () {
        cropper = new Cropper(image_1, {
			  aspectRatio: "free",
			  viewMode: 2,
			});
		  }).on('hidden.bs.modal', function () {
			cropper.destroy();
			cropper = null;
		  });  

		  crop_1.addEventListener('click', function () {
			var canvas;
			$modal_1.modal('hide');
			if (cropper) {
			  canvas = cropper.getCroppedCanvas();
			  avatar_1.src = canvas.toDataURL();
			  inputData_1.value =  avatar_1.src ;
			}
		  });
		});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("OpinionPollPage", "OpinionPollPage");
function OpinionPollPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
									DATE_FORMAT({$thisTbl}.Start_Date,'%d-%m-%Y') as startDate,
									DATE_FORMAT({$thisTbl}.End_Date,'%d-%m-%Y') as endDate
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Start_Date";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Question" => array("value" => "Question", "type" => "text"),
					"Start_Date" => array("value" => "startDate", "type" => "text"),
					"End_Date" => array("value" => "endDate", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("BusinessExchangePage", "BusinessExchangePage");
function BusinessExchangePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
									DATE_FORMAT({$thisTbl}.Exchange_Date,'%d-%m-%Y') as exchangeDate
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Product";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Exchange_Date" => array("value" => "exchangeDate", "type" => "text"),
					"Product | Service - Title" => array("value" => "Product", "type" => "text"),
					"Product | Service - Details" => array("value" => "Product_Details", "type" => "text"),
					"Contact_Details" => array("value" => "Contact_Details", "type" => "text"),
					"Company_Profile" => array("value" => "Company_Profile", "type" => "text"),
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("ForeignTradePage", "ForeignTradePage");
function ForeignTradePage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Consulate_Name";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Consulate | Trade Office Name" => array("value" => "Consulate_Name", "type" => "text"),
					"Website" => array("value" => "Website", "type" => "text"),
					"Address" => array("value" => "Address", "type" => "text"),
					"Contact_Details" => array("value" => "Contact_Details", "type" => "text"),
					"Country" => array("value" => "Country", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
           	 		$isSuccess =  $classAction->tableToMaster();
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode("TendersPage", "TendersPage");
function TendersPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
												DATE_FORMAT( {$thisTbl}.Start_Date , '%d-%m-%Y') as startDate,
												DATE_FORMAT( {$thisTbl}.End_Date , '%d-%m-%Y') as endDate
								FROM  {$thisTbl}
								
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Tender_Name";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Tender_Name" => array("value" => "Tender_Name", "type" => "text"),
					"Website" => array("value" => "Website", "type" => "text"),
					"Start_Date" => array("value" => "startDate", "type" => "text"),
					"End_Date" => array("value" => "endDate", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
				$isSuccess = $classAction->createOtherFile();
				if( $isSuccess )
           	 		$isSuccess =  $classAction->tableToMaster();
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
			var imageType = 'user';
			window.addEventListener('DOMContentLoaded', function () {
			  var avatar_1 = document.getElementById('uploaded_image_userProfile_1');
			  var image_1 = document.getElementById('sample_userProfile_1');
			  var input_1 = document.getElementById('userProfile_1');
			  var inputData_1 = document.getElementById('DatauserProfile_1');
			  var crop_1 = document.getElementById('crop_userProfile_1');
			  var $modal_1 = $('#modal_userProfile_1');
			  
			 var cropper;
		
			$('[data-toggle="tooltip"]').tooltip();

		  input_1.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
				});
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}
add_shortcode("BusinessOpportunitiesPage", "BusinessOpportunitiesPage");
function BusinessOpportunitiesPage($atts)
{
	global $menuCheck;
	global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{

			global $wp;
			global $wpdb; 
			global $jsData;
			global $field;
			global $fieldValue;
			global $errorMsg;
			$errorMsg = array();

			extract($atts);
			extract($jsData);
			$pagename = strtolower($pagename);
			
			$returnSubmitValue = '';
			$isSuccess = true;
			$classUI = new classUI();
			$classMysql = new classMysql();
			$classAction = new classAction();
			$classValidator = new classValidator();
			$classDate = new classDate();
			
			
			$field['fieldData']['pageName'] = $pagename;
			$field['fieldData']['pageNm'] = $pagenm;

			$field['fieldData']['currentId'] = $user->ID;
			$field['fieldData']['currentRole'] = $role[$roleKey];
			if( isset ( $menuAction[$pageId] )) 
				$field['fieldData']['currentAction'] = $menuAction[$pageId];
			$field['fieldData']['nonceField'] = $pagename . "_action";
			
		
			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*
								FROM  {$thisTbl}
								
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.Company_Name";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Company_Name" => array("value" => "Company_Name", "type" => "text"),
					"Contact" => array("value" => "Contact_Details", "type" => "text"),
					
					
				);
			}

        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {
           	  $isSuccess =  $classAction->tableToMaster();
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}
        if ( isset( $_GET['logID'] )  ||  isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            extract($_POST);
        }
        
        
        if( isset( $_POST['Refresh_Type'] ) )
        {
            if(  $_POST['Refresh_Type'] == ''  )
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }else if( !isset( $_POST['Refresh_Type'] ) )
        {
                $fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD';
        }
        if( isset( $_POST['REFRESH'] ) ) $_POST['ADD'] = $_POST['Refresh_Type'];

        $classUI->echoForm();
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php
		
		}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}

?>