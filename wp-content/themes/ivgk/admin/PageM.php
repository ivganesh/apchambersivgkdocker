<?php

add_shortcode("MemberPage", "MemberPage");
function MemberPage($atts)
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
			// print_r($_POST);
			
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
			$jsData['membershipPlanList'] = array();
			$currentYear = Date('Y');
			$currentYear = (int)$currentYear + 15;
			
			for($initalYear = 2020; $initalYear < $currentYear;  $initalYear++ )
			{
				$year = $initalYear . " - " . ( $initalYear + 1);
				$jsData['membershipPlanList'][$year] = $year;
			}
				
			
			
			
			$jsData['chamberDesignationList'] = $wpdb->get_results("select ID,Chamber_Designation from {$wpdb->prefix}chamber_designation 
			 												where  isTrash=0 order by Chamber_Designation", 'ARRAY_A');
       		$jsData['chamberDesignationList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['chamberDesignationList'],
					"option_text" => array("ID"),
					"option_value" => array("Chamber_Designation"),
        		)
			);
			
			$jsData['userRoleList'] = $wpdb->get_results("select User_Role from {$wpdb->prefix}user_role 
			 												where  isTrash=0 order by User_Role", 'ARRAY_A');
			
       		$jsData['userRoleList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['userRoleList'],
					"option_text" => array("User_Role"),
					"option_value" => array("User_Role"),
        		)
			);
			//print_r( $jsData['userRoleList']  );
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
				$designationTbl = "{$wpdb->prefix}chamber_designation";
				$countryTbl = "{$wpdb->prefix}country";
				$stateTbl = "{$wpdb->prefix}state";
				$cityTbl = "{$wpdb->prefix}city";
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.ID,
											{$thisTbl}.isTrash,
											{$thisTbl}.Organization_Name,
											{$thisTbl}.Applicant_Name,
											{$thisTbl}.User_Role,
											{$thisTbl}.Mobile_No,
											{$thisTbl}.Membership_Category,
											{$thisTbl}.Membership_Period,
											{$thisTbl}.Email_ID,
											{$thisTbl}.Latitude,
											{$thisTbl}.Longitude,
											{$designationTbl}.Chamber_Designation AS chamberDesignation
								FROM  {$thisTbl}
								LEFT JOIN {$designationTbl} ON ( {$designationTbl}.ID = {$thisTbl}.Chamber_Designation )
								WHERE {$thisTbl}.isSys = 0 AND {$thisTbl}.ID != 1 AND {$thisTbl}.User_Role != '' {$getExtraQry}
								ORDER BY {$thisTbl}.Organization_Name";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Organization_Name" => array("value" => "Organization_Name", "type" => "text"),
					"Applicant_Name" => array("value" => "Applicant_Name", "type" => "text"),
					"User_Role" => array("value" => "User_Role", "type" => "text"),
					"Designation" => array("value" => "chamberDesignation", "type" => "text"),
					"Category" => array("value" => "Membership_Category", "type" => "text"),
					"Period" => array("value" => "Membership_Period", "type" => "text"),
					"Mobile_No" => array("value" => "Mobile_No", "type" => "text"),
					"Email_ID" => array("value" => "Email_ID", "type" => "text"),
					
					//"Latitude" => array("value" => "Latitude", "type" => "text"),
					//"Longitude" => array("value" => "Longitude", "type" => "text"),
					
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
				if( isset ( $_POST['Page_Name'] ) )
				{
					if( count ( $_POST['Page_Name'] ) > 0)
						$_POST['Page_Name'] = implode("--", $_POST['Page_Name']);
					else $_POST['Page_Name'] = '';
				}else $_POST['Page_Name'] = '';
				
				
               $isSuccess =  $classAction->createAccountUser();
				if( $isSuccess)
				{
					 $isSuccess =  $classAction->createOtherFile();
					 //print_r($_POST); exit;
					 if( $isSuccess)
					 {
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
			$_POST['Page_Name'] = explode("--", $_POST['Page_Name']);
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
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_1.src = URL.createObjectURL(file);
				} 
		  });
			input_2.addEventListener('change', function (e) {
				var files = e.target.files;
				var reader;
				var file;
				var url;
				if (files && files.length > 0) {
			  		file = files[0];
				    avatar_2.src = URL.createObjectURL(file);
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


add_shortcode("UserRolePage", "UserRolePage");
function UserRolePage($atts)
{
   global $menuCheck; global $menuAction;
	global $pageId;
	
	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{
		//remove_role("subscriber");
		//remove_role("contributor");
		//remove_role("author");
		//remove_role("editor");
        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
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
        //$getExtraQry = $classUI->formSearchQuery($thisTbl);
        
        if (isset($_POST['SearchData'])) {
            $thisTbl = "{$wpdb->prefix}{$pagename}";
            $getExtraQry = $classUI->formSearchQuery($thisTbl);
            $field['getQry'] = "select {$thisTbl}.User_Role,{$thisTbl}.isTrash
                            FROM  {$thisTbl}
                            WHERE {$thisTbl}.isSys = 0
                            {$getExtraQry}
                            ORDER BY  {$thisTbl}.User_Role";

            $field['tableCol'] = array(
                "User_Role" => array("value" => "User_Role", "type" => "text"),
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

                if ($_POST['ADD'] == 'ADD') {

                    if ($GLOBALS['wp_roles']->is_role($_POST['User_Role'])) {
                        $errorMsg[] = array("Role Exists", $_POST['ADD'], 'alert-danger');
                    } else {
                        add_role($_POST['User_Role'], $_POST['User_Role']);
                        $errorMsg[] = array("New Role Created", $_POST['ADD'], 'alert-success');
                    }
                } 

                if ($isSuccess) {
                    $isSuccess = $classAction->tableToMaster();
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

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode("DesignationPage", "DesignationPage");
function DesignationPage($atts)
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
								ORDER BY {$thisTbl}.Designation";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Designation" => array("value" => "Designation", "type" => "text"),
					
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


add_shortcode("BusinessConstitutionPage", "BusinessConstitutionPage");
function BusinessConstitutionPage($atts)
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
								ORDER BY {$thisTbl}.Business_Constitution";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Business_Constitution" => array("value" => "Business_Constitution", "type" => "text"),
					
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



add_shortcode("BusinessTypePage", "BusinessTypePage");
function BusinessTypePage($atts)
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
								ORDER BY {$thisTbl}.Business_Type";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Business_Type" => array("value" => "Business_Type", "type" => "text"),
					
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


add_shortcode("CountryPage", "CountryPage");
function CountryPage($atts)
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
								ORDER BY {$thisTbl}.Country";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Country" => array("value" => "Country", "type" => "text"),
					"Country_Code" => array("value" => "Country_Code", "type" => "text"),
					
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


add_shortcode("StatePage", "StatePage");
function StatePage($atts)
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
			
			$jsData['countryList'] = $wpdb->get_results("select ID,Country from {$wpdb->prefix}country 
			 												where  isTrash=0 order by Country", 'ARRAY_A');
       		$jsData['countryList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['countryList'],
					"option_text" => array("ID"),
					"option_value" => array("Country"),
        		)
			);
			

			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				$countryTable = "{$wpdb->prefix}country";
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
								$countryTable.Country as Country_Name
								FROM  {$thisTbl}
								LEFT JOIN {$countryTable} ON ({$countryTable}.ID = {$thisTbl}.Country)
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.State";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Country" => array("value" => "Country_Name", "type" => "text"),
					"State" => array("value" => "State", "type" => "text"),
					
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



add_shortcode("CityPage", "CityPage");
function CityPage($atts)
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
			$countryTable = "{$wpdb->prefix}country";
			$stateTable = "{$wpdb->prefix}state";
			$jsData['stateList'] = $wpdb->get_results("select {$stateTable}.ID,
															  {$stateTable}.State,
															  {$countryTable}.Country
															  from {$stateTable} 
															  LEFT JOIN {$countryTable} ON 
															  		( {$countryTable}.ID = {$stateTable}.Country)
			 												where  {$stateTable}.isTrash=0 
															order by {$stateTable}.State", 'ARRAY_A');
       		$jsData['stateList'] = $classUI->setAsSelectOption(array("table_data" => $jsData['stateList'],
					"option_text" => array("ID"),
					"option_value" => array("State","Country"),
        		)
			);
			

			$classUI->getTableField();
       		
			if (isset($_POST['SearchData'])) 
			{
				$thisTbl = "{$wpdb->prefix}{$pagename}";
				
				$getExtraQry = $classUI->formSearchQuery($thisTbl);

				$field['getQry'] = "select {$thisTbl}.*,
								$stateTable.State as State_Name,
								$countryTable.Country as Country_Name
								FROM  {$thisTbl}
								LEFT JOIN {$stateTable} ON ({$stateTable}.ID = {$thisTbl}.State)
								LEFT JOIN {$countryTable} ON ({$countryTable}.ID = {$stateTable}.Country)
								WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
								ORDER BY {$thisTbl}.City";

				$field['tableCol'] = array(
					"Action" => array("value" => "ID", "type" => "editdelete"),
					"Country" => array("value" => "Country_Name", "type" => "text"),
					"State" => array("value" => "State_Name", "type" => "text"),
					"City" => array("value" => "City", "type" => "text"),
					
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
