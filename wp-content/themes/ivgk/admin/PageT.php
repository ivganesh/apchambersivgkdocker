<?php


add_shortcode("TestPage", "TestPage");

function TestPage($atts)
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
        $showForm = true;  

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
        
        $user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
        $field['fieldData']['pageName'] = $pagename;
        $field['fieldData']['pageNm'] = $pagenm;

        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];
		if( isset ( $menuAction[$pageId] )) 
			$field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";
        
		 $field['fieldData']["callBack"] = "referTrn";
		  $field['fieldData']["downWard"] = "yes";
		 
        $fieldValue['Bill_Date'] = date('Y-m-d');

        //
        //$getExtraQry = $classUI->formSearchQuery($thisTbl);
        $jsData['patientNameList'] = $classMysql->getBalanceSheetList('PATIENT');
        $jsData['reportGroupList'] = $classMysql->labTestGroup();
		$registername = array('BANK', 'CASH'); 
        $jsData['registerList'] = $classMysql->getAccountList($registername);
		//$jsData['referOutList'] = $classMysql->getAccountList(array('REFER OUT'));
		//$jsData['referByList'] = $classMysql->getUserRoleList('INHOUSE DOCTOR');

        $classUI->getTableField();


        if (isset($_POST['SearchData'])) {
            $thisTbl = "{$wpdb->prefix}{$pagename}";
            $joinUsers = "{$wpdb->prefix}users";
            $joinReportGroup = "{$wpdb->prefix}reportgroup";
            $getExtraQry = $classUI->formSearchQuery($thisTbl);
           
			$field['getQry'] = "select {$thisTbl}.ID ,
										{$thisTbl}.isTrash ,
                                        {$thisTbl}.Bill_Amount ,
										 {$thisTbl}.Order_No ,
											 {$thisTbl}.Cash_Receive,
                                        {$joinReportGroup}.Group_Name ,
										T2.Account_Name  as acRegister,
                                DATE_FORMAT({$thisTbl}.Bill_Date,'%d-%m-%Y') as billDate,
                                {$joinUsers}.Account_Name as patientName
                            FROM  {$thisTbl}
                            LEFT OUTER JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$thisTbl}.Patient_Name)
                            LEFT OUTER JOIN {$joinReportGroup} ON ( {$joinReportGroup}.ID = {$thisTbl}.Report_Group)
							LEFT OUTER JOIN {$joinUsers} as T2 ON ( T2.ID = {$thisTbl}.Register)
                            WHERE {$thisTbl}.isSys = 0  {$getExtraQry}
                            ORDER BY {$thisTbl}.Bill_Date , {$joinUsers}.Account_Name ";

            $field['tableCol'] = array(
                "Action" => array("value" => "ID", "type" => "editdelete"),
                "Bill_Date" => array("value" => "billDate", "type" => "text"),
				"Order_No" => array("value" => "Order_No", "type" => "text"),
                "Patient_name" => array("value" => "patientName", "type" => "text"),
                "Report_Name" => array("value" => "Group_Name", "type" => "text"),
                "Bill_Amount" => array("value" => "Bill_Amount", "type" => "float"),
				"Register" => array("value" => "acRegister", "type" => "text"), 
				"Cash_Receive" => array("value" => "Cash_Receive", "type" => "float"),
            );
        }

        
        if (isset($_POST['ADD'])) {

            
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  false);
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();

            }

            if ($isSuccess) {

                if (isset($_POST['ADD'])) {

                    if ($_POST['ADD'] == 'ADD' || $_POST['ADD'] == 'UPDATE') {

                        $getReturnValue = getTest();
                        $showForm = false;

                    } else {

                        $showForm = true;
                        $classAction = new classAction();
						 $isSuccess = $classAction->tableToMaster();
						if($isSuccess)
						{
							$isSuccess = referTrn(); 
							if(!$isSuccess && $_POST['ADD'] == 'ADD')
							{
								$_POST['ADD'] = 'UPDATE';
							}
						}
						
						
                        $secondPost = true;
                    }
                }

            }
        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }
		if (isset($secondPost)) { 
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
        
        
       

        


        if($showForm) 
        {
			$classUI->echoForm();
		}else
		{
			echo $getReturnValue;
		}
           
        $classUI->searchFormAndData();

        ?>
		<script>
         var _rulesString = {
			Register: {
				registerCashReceive: true
			},
			Cash_Receive : {  registerCashReceive : true },
        };
        var _messagesString = {
			Register:"Register cant be blank if cash is not blank.",
			Cash_Receive:"Cash Receive cant be blank if register is selected.",
        };
		
		$(function (){
			jQuery("body").on("change","select[id=Report_Group]",function(){
				if( $(this).val() != '')
				{
					var id = $(this).attr("id");
					var thisval = $("#"+id +" option:selected").text().split(" -- ")[1];
					$("#Bill_Amount").val($.trim(thisval));
				}else
				{
					$("#Bill_Amount").val('');
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

add_shortcode("MultiTaskingPage", "MultiTaskingPage");

function MultiTaskingPage($atts)
{
    // if (($locations = get_nav_menu_locations()) && isset($locations["primary"])) {
    //     $menu = wp_get_nav_menu_object($locations["primary"]);
    //     $menu_items = wp_get_nav_menu_items($menu->term_id);
    // }
    // $this_item = current(wp_filter_object_list($menu_items, array("object_id" => get_queried_object_id())));
    //print_r($this_item);
    if (is_user_logged_in()) {

        global $wp; 
        global $wpdb;
        global $jsData;
        global $errorMsg;
        $errorMsg = array();

        extract($jsData);
        
        $classUI = new classUI();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        
        $user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];
		$field['fieldData']['nonceField'] =  "MultiTasking_action";
		/*
		$tables = $wpdb->get_results("SHOW TABLES FROM saplingtech","ARRAY_A");
		foreach( $tables as $key => $value )
		{
			
				$checkColumn = $wpdb->get_results("DESC `{$value['Tables_in_saplingtech']}`", "ARRAY_A");
				$sysExists = false;
				$updatedExists = false;
				$addedExists = false;
				foreach( $checkColumn as $k => $v )
				{
					if( strtolower($v['Field']) == 'issys' ) { $sysExists = true;  }
					if( strtolower($v['Field']) == 'updated' ) { $updatedExists = true;  }
					if( strtolower($v['Field']) == 'added' ) { $addedExists = true;  }
				}
				if( $sysExists )
				{
					$val = "ALTER TABLE `{$value['Tables_in_saplingtech']}` CHANGE `isSys` `isSys` TINYINT(1) NOT NULL DEFAULT '0';";
					$result = $wpdb->query($val);
				}
				else
				{
					$val = "ALTER TABLE `{$value['Tables_in_saplingtech']}` ADD `isSys` TINYINT(1) NOT NULL DEFAULT '0';";
					$result = $wpdb->query($val);
				}
				echo $result.'<br>';
				if( $updatedExists )
				{
					$val = "ALTER TABLE `{$value['Tables_in_saplingtech']}` CHANGE `updated` `updated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';";
					$result = $wpdb->query($val);
				}
				else
				{
					$val = "ALTER TABLE `{$value['Tables_in_saplingtech']}` ADD `updated` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';";
					$result = $wpdb->query($val);
				}
				echo $result.'<br>';
				if( $addedExists )
				{
					$val = "ALTER TABLE `{$value['Tables_in_saplingtech']}` CHANGE `added` `added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';";
					$result = $wpdb->query($val);
				}
				else
				{
					$val = "ALTER TABLE `{$value['Tables_in_saplingtech']}` ADD `added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';";
					$result = $wpdb->query($val);
				}
				echo $result.'<br>';
			
			
		}
		*/
        



        /*
        $patientNameList = $classUI->setOptionData(array('firstOption' => "Select Patient", 'option' => $classMysql->getBalanceSheetList('PATIENT')));
        $appointDRList = $classUI->setOptionData(array('firstOption' => "Select Appoint DR", 'option' => $classMysql->getUserRoleList('INHOUSE DOCTOR'), "firstOption" => "Select Doctor for this appointment"));
        $roomList = $classUI->setOptionData(array('firstOption' => "Select Room No", 'option' => $classMysql->roomList()));
        $chargeList = $classUI->setOptionData(array('firstOption' => "Select Charge Type", 'option' => $classMysql->chargeData()));
        $referToList = $classUI->setOptionData(array('firstOption' => "Select Refer Out", 'option' => $classMysql->getBalanceSheetList('REFER OUT')));
		$registername = array('BANK', 'CASH', 'JV') ;
		$registerList = $classUI->setOptionData(array('firstOption' => "Select Register", 'option' => $classMysql->getAccountList($registername)));
		*/
		$userTbl = "{$wpdb->prefix}users";
		$appointTbl = "{$wpdb->prefix}appointment";
		$opdTbl = "{$wpdb->prefix}doctoropd";
		$admitTbl = "{$wpdb->prefix}admit";
		$dischargeTbl = "{$wpdb->prefix}discharge";
        $qry = $wpdb->get_results("SELECT
									{$userTbl}.ID,
									{$dischargeTbl}.ID as dischargeID,
									DATE_FORMAT({$dischargeTbl}.Discharge_Date , '%d-%m-%y')as dischargeDate,
									DATE_FORMAT({$opdTbl}.OPD_Date , '%d-%m-%y')as opdDate,
									{$opdTbl}.ID as opdID,
									DATE_FORMAT({$admitTbl}.Admit_Date , '%d-%m-%y')as admitDate,
									{$admitTbl}.ID as admitID,
									DATE_FORMAT({$appointTbl}.Appoint_Date , '%d-%m-%y')as appointDate,
									{$appointTbl}.ID as appointID,
									CONCAT({$userTbl}.Account_Name,' - ',{$userTbl}.Patient_Age,' ',{$userTbl}.Gender,' - ',{$userTbl}.Register_Mobile) as patient
									FROM {$userTbl}
									LEFT JOIN {$appointTbl} ON ( {$appointTbl}.Patient_Name = {$userTbl}.ID )
									LEFT JOIN {$admitTbl} ON ( {$admitTbl}.Patient_Name = {$appointTbl}.ID )
									LEFT JOIN {$dischargeTbl} ON ( {$dischargeTbl}.Patient_Name = {$admitTbl}.ID )
									LEFT JOIN {$opdTbl} ON ( {$opdTbl}.Patient_Name = {$appointTbl}.ID )
									WHERE {$userTbl}.Balance_Sheet='PATIENT'
									ORDER BY {$userTbl}.Account_Name
									", "ARRAY_A");
        $i = 1;
        foreach ($qry as $key => $val) {
			$dischargeID = (int)$val['dischargeID'];
			$admitID = (int)$val['admitID'];
			$appointID = (int)$val['appointID'];
			$opdID = (int)$val['opdID'];
			if( $admitID > 0)
			{
				$appointText = 'Admitted';
				$ipdText = 'IPD';
				$nurseText = 'Nurse';
				if($dischargeID > 0)
				{
					$dischargeText = 'Discharged';
				}else
				{
					$dischargeText = $val['admitDate'];
				}
				
			}else
			{
				$appointText = $val['appointDate'];
				$ipdText = '';
				$nurseText = '';
				$dischargeText ='';
			}
			if($opdID > 0)
			{
				$opdText = 'Done';
			}else
			{
				$opdText = 'OPD';
			}
			
			//echo strlen($val['appointDate']);
            $tableArr[$i]['Patient'] = array("value" => $val['patient'], "type" => "text");
            $tableArr[$i]['Appoint'] = array("value" => "Appoint", "type" => "text", "id" => "AppointMultiTasking__{$val['ID']}");
			$tableArr[$i]['OPD'] = array("value" => $opdText , "type" => "text");
			$tableArr[$i]['Admit'] = array("value" => $appointText  , "type" => "text");
			
			$tableArr[$i]['IPD'] = array("value" => $ipdText , "type" => "text");
			$tableArr[$i]['Nurse'] = array("value" => $nurseText , "type" => "text");
			
			$tableArr[$i]['DISCH'] = array("value" => $dischargeText , "type" => "text");
			
           
			if( $admitID > 0)
			{
				$appointText = 'Admitted';
				$tableArr[$i]['IPD']['id'] = "IPDMultiTasking__{$admitID}";
				$tableArr[$i]['Nurse']['id'] = "NurseMultiTasking__{$admitID}";
				if($dischargeID > 0)
				{
					$dischargeText = 'Discharged';
				}else
				{
					$dischargeText = 'DISCH';
					if( $appointID > 0 )$tableArr[$i]['DISCH']['id'] = "AdmitMultiTasking__{$appointID}";
				}
				
			}else
			{
				if( $appointID > 0 )$tableArr[$i]['Admit']['id'] = "AdmitMultiTasking__{$appointID}";
				
			}
			if($opdID > 0)
			{
				$opdText = 'Done';
			}else
			{
				if( $appointID > 0 )$tableArr[$i]['OPD']['id'] = "OPDMultiTasking__{$appointID}";
			}
            $i++;
			
			$tableArr[$i]['Patient'] = array("value" => $val['patient'], "type" => "text");
            $tableArr[$i]['Appoint'] = array("value" => "", "type" => "text", );
			$tableArr[$i]['OPD'] = array("value" => "" , "type" => "text");
			
			$tableArr[$i]['Admit'] = array("value" => "Cash", "type" => "text", "id" => "CashMultiTasking__{$val['ID']}", "toggle" => "AddCash");
			$tableArr[$i]['IPD'] = array("value" => "Reception", "type" => "text", "id" => "ReceptionMultiTasking__{$val['ID']}", "toggle" => "AddReception");
			$tableArr[$i]['Nurse'] = array("value" => "Refer", "type" => "text", "id" => "ReferMultiTasking__{$val['ID']}", "toggle" => "AddRefer");
			$tableArr[$i]['DISCH'] = array("value" => "Refer-Out", "type" => "text", "id" => "ReferOutMultiTasking__{$val['ID']}", "toggle" => "AddReferOut");
			
	
			$i++;
        } 

        echo '<div class="row  mt-3" >';
        echo '<div class="col-12">';
        echo $classUI->showReports($tableArr, "reportTable10", array());
        echo '</div>';
        echo '</div>';

        ?>

    <script>
   <?PHP

        ?>
        var _rulesString = {};
		var _messagesString = {};
	

    jQuery(document).ready( function ()
    {
		
		
		$('body').on('click', 'a[id^=ReferOutMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("ReferOutMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('refer-out/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		$('body').on('click', 'a[id^=ReferMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("ReferMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('refer/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		$('body').on('click', 'a[id^=AppointMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("AppointMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('appointment/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		$('body').on('click', 'a[id^=CashMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("CashMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('bank-cash/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		$('body').on('click', 'a[id^=ReceptionMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("ReceptionMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('reception/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		
		$('body').on('click', 'a[id^=AdmitMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("AdmitMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('admit/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		
		$('body').on('click', 'a[id^=OPDMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("OPDMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('doctor-opd/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		
		$('body').on('click', 'a[id^=IPDMultiTasking__]',function () {
			var thisID = $(this).attr('id').split("IPDMultiTasking__");
			var width = screen.width * 0.90;
			width = width.toFixed(0);
			var height = screen.height;
			 window.open('doctor-ipd/?multiTasking=yes&Patient_Name='+thisID[1], "_blank", "toolbar=no,scrollbars=yes,resizable=no,top=0,left=0,width="+width+",height="+height);
			return false;
		});
		
		
		
		
		


	 });
</script>

    <?PHP
	}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    

}



?>