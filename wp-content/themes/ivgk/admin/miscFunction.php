<?php
function memberPlan()
{
	global $wpdb;
	$returnArr = array();
	$payment = $wpdb->get_results("select * from {$wpdb->prefix}membership_plan limit 1","ARRAY_A");
			//print_r($payment);
	if( count ( $payment  ) == 1)
	{
		$returnArr = $payment[0]; 
		/*
		echo '<div class="row">
						<div class="col-12">
							<div class="alert alert-info text-center">
								Your membership is  '.$datediff.'. Please renew your membership
								<a class="btn btn-outline-primary">'.$paymentInfo['Charge'].'</a>
								for '.$paymentInfo['Validity_Days'].' Days
							</div>
						</div>
					</div>';
					*/
	}
	else{
		echo '<div class="row">
						<div class="col-12">
							<div class="alert alert-danger text-center">
								No Membership plan found to renew
							</div>
						</div>
					</div>';
	}
	return $returnArr;
}
function renew_membership()
{
	global $wpdb;
	$return = true;
	$date = date('Y-m-d');
	$user = wp_get_current_user();
    $roles = (array) $user->roles;
	$roleKey = key($roles);
	$role = $roles[$roleKey];
	$datediff = 0;
	$membershipQry = $wpdb->get_results("SELECT Paid_Role FROM {$wpdb->prefix}user_role 
									where User_Role='{$role}'","ARRAY_A");
	if( (int)$membershipQry == 1 )
	{
		$membership = $membershipQry[0]['Paid_Role'];
		if( $membership == 'YES') 
		{
			$member = memberPlan();
			if( count ($member) > 0)
			{
				$expiryQry = $wpdb->get_results("SELECT Expiry_Date, DATE_FORMAT(Expiry_Date,'%d %M , %Y') as expiryDate 
												FROM {$wpdb->prefix}membership 
												where userId='{$user->ID}' 
												order by Expiry_Date DESC limit 1 ","ARRAY_A");
				if( count( $expiryQry ) > 0 )
				{
					$expiryData = $expiryQry[0];
					$now = time(); 
					$your_date = strtotime($expiryData['Expiry_Date']);
					$datediff = $now - $your_date;
					$datediff =  round($datediff / (60 * 60 * 24)); 
					if( (int)$datediff < 0 )
					{
						$datediff *= -1;
						$paymentMsg = " Your membership is  expiring on {$expiryData['expiryDate']} ( in {$datediff} days)";
					}
					else if( (int)$datediff == 0 )
					{
						$paymentMsg = " Your membership is  expiring on {$expiryData['expiryDate']} ( TODAY )";
					} elseif(  (int)$datediff > 0 )
					{
					
						$paymentMsg = "Your membership has expired on {$expiryData['expiryDate']}";
						$return = false;
					}
					$member = memberPlan();
					if( count ($member) > 0)
					{
						echo '<div class="row">
								<div class="col-12">
									<div class="alert alert-info text-center">
										'.$paymentMsg.'
										<a class="btn btn-outline-primary">Rs. '.(float)$member['Charge'].'</a>
										for '.$member['Validity_Days'].' Days
									</div>
								</div>
							</div>';
					}
					else
					{
						$return = false;	
					}
				}
				else
				{
					
					echo '<div class="row">
							<div class="col-12">
								<div class="alert alert-info text-center">
									You dont have any membership . Please buy a new membership
									<a class="btn btn-outline-primary">Rs. '.(float)$member['Charge'].'</a>
									for '.$member['Validity_Days'].' Days
								</div>
							</div>
						</div>';
					
					$return = false;	


				}
			}else
			{
				$return = false;
			}
		}
	}
	else
	{
		$return = false;	
		echo '<div class="row">
				<div class="col-12">
					<div class="alert alert-danger text-center">
						Undefined User Role.
					</div>
				</div>
			</div>';
	 	
	}
	
	
	return $return;
	
}
function showLogoutMsg($fromMenu,$isLogin)
{	
	if(!$fromMenu)
	{
		echo '<div class="row">
				<div class="col-12">
					<div class="alert alert-danger text-center">You have no access to this page...</div>
				</div>
			</div>';
	}
	else if(!$isLogin) echo do_shortcode('[LoginPage]');
}
function alert_display($error_msg)
{		
	$returnAddButtonValue = '';
	$errorColor = 'alert-success';
	$error_text = '';
	$error_show = 'none';
	$alertText = '';
	if( count ( $error_msg ) > 0)	
	{	
		
		$alertI = 0; 
		foreach( $error_msg as $key => $val )
		{
			$error_color = $val[1];
			$error_text = $val[0];
			if( !$error_color )
			{
				$errorColor = 'alert-danger';
				$returnAddButtonValue = "danger";
			}
			
			
			if( strlen( $error_text) > 2)
			{
				$alertText .= ($alertI == 0) ? $error_text : "<br>{$error_text}";
				$alertI++;
				
			}
			
		}
		echo "<div id='errorAlert' class='row alert {$errorColor}'>
					<div class='col-12 text-center' id='wp-admin-bar-Error'>
						{$alertText}
					</div>
				</div>";
	}else
	{
		echo "<div id='errorAlert' class='row alert alert-danger' style='display:none'>
				<div class='col-sm-20 text-center' id='wp-admin-bar-Error' style='margin:auto;'>
				</div>
			 </div>";
		
	}
	return $returnAddButtonValue;
 
}

	
function trnData()
{
	global $systemAC;
	global $field;
	global $wpdb;
	$pagename = strtoupper( $field['fieldData']['pageName'] );
	//echo $pagename;
	$ac_trn = array(); 
	$srI = 0;
	if(  $pagename == 'PURCHASE' || $pagename == 'SALE' || $pagename == 'PHARMACYSALE' )  
	{
		$debtorAccount = $_POST['Account_Name'];
		$billDate = $_POST['Bill_Date'];
		$registerAC = isset( $_POST["Register"] ) ? $_POST["Register"] : $systemAC['PHARMACY CASH'] ;
		$register = $pagename == 'PURCHASE' || $pagename == 'SALE' ?  $systemAC[$pagename] :  $systemAC['PHARMACY SALE'];
		$gstId = ( $pagename == 'PURCHASE') ? $systemAC["GST PAID"] : $systemAC["GST COLLECTED"];
		$stockId = $systemAC["STOCK AMOUNT"];
		$ac_trn['trnFile']['debtor_amount'] = array( "Register" => $register, 
										   "Account_Name" =>  $debtorAccount ,
										   "Bill_Date" => $billDate,
										   "Order_No" => $_POST['Order_No'],
										   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
										   "Amount" =>  $pagename == 'SALE' ? (float)$_POST['Bill_Amount'] : (float)$_POST['Bill_Amount'] * -1  ,
										   "Description" => '' ,
										   "Cheque_No"      => '',
										   "Page_Id" => $_POST['ID'],
										   "Page_Name" => $pagename,
										   "Sr" => $srI, 
										   );
		$srI++;
		$ac_trn['trnFile']['gst_amount'] = array( "Register" => $register, 
										   "Account_Name" => $gstId,
										   "Bill_Date" => $billDate,
										   "Order_No" => $_POST['Order_No'],
										   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
										   "Amount" => $pagename == 'SALE' ?  (float)$_POST['Total_GST_Amt'] * -1 :  (float)$_POST['Total_GST_Amt'] ,
										   "Description" => '' ,
										   "Cheque_No"      => '',
										   "Page_Id" => $_POST['ID'],
										   "Page_Name" => $pagename,
										    "Sr" => $srI,
										   );
		$srI++;
		$ac_trn['trnFile']['Taxable_Amt'] = array( "Register" => $register, 
										   "Account_Name" => $stockId ,
										   "Bill_Date" => $billDate,
										   "Order_No" => $_POST['Order_No'],
										   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
										   "Amount" => $pagename == 'SALE' ? (float)$_POST['Total_Taxable_Amt'] * -1 : (float)$_POST['Total_Taxable_Amt'] ,
										   "Description" => '' ,
										   "Cheque_No"      => '',
										   "Page_Id" => $_POST['ID'],
										   "Page_Name" => $pagename,
										    "Sr" => $srI,
										   );
		$descriptionData = $pagename.' Entry - Cash Payment from '.getDataName('users',array('Account_Name'),$_POST['Account_Name']);								   
		$ac_trn['trnFile']['debtor_payment'] = array(  "Register" => $registerAC, 
											 "Account_Name" => $debtorAccount ,
											 "Bill_Date" => $billDate,
											 "Order_No" => $_POST['Order_No'],
											 "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0,
											 "Amount" => $pagename == 'SALE' ? (float)$_POST['Cash_Receive'] * -1 : (float)$_POST['Cash_Paid'] ,
											 "Description" => $descriptionData ,
											 "Cheque_No"      => '',
											 "Page_Id" => $_POST['ID'],
										     "Page_Name" => $pagename,
											 "Sr" => 1,
										   );
		$ac_trn['trnFile']['register_payment'] = array( 
											   "Register" => $registerAC, 
											   "Account_Name" => $registerAC ,
											   "Bill_Date" => $billDate,
											   "Order_No" => $_POST['Order_No'],
											   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
											   "Amount" => $pagename == 'SALE' ? (float)$_POST['Cash_Receive'] : (float)$_POST['Cash_Paid'] * -1 ,
											   "Description" => $descriptionData ,
											   "Cheque_No"      => '',
											   "Page_Id" => $_POST['ID'],
										   		"Page_Name" => $pagename,
												"Sr" => 1,
										   );
		if ( $pagename == 'PURCHASE' || $pagename == 'SALE' )
		{
			$srI++;
			$pro = array();
			$chq_no = array(); // for sale rate & purchase rate
			foreach( $_POST as $k => $v)
			{
				if( preg_match("/Product_Name_/",$k) && strlen($v) > 0 )
				{	$pro_id = explode("Product_Name_",$k)[1];
					$pro_qty = (float)$_POST["QTY_{$pro_id}"] ;
					$pro_fqty = (float)$_POST["FQ_{$pro_id}"] ;
					
					$ac_trn['stkFile'][$k] = array( "Register" => $register, 
											   "Account_Name" => $_POST['Account_Name'] ,
											   "Bill_Date" => $_POST['Bill_Date'],
											   "Order_No" => $_POST['Order_No'],
											   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
											   "Qty" => $pagename == 'SALE' || $pagename == 'PHARMACYSALE' ? (float)$pro_qty * -1 : (float)$pro_qty ,
											   "Fqty" => $pagename == 'SALE' || $pagename == 'PHARMACYSALE' ? (float)$pro_fqty * -1 : (float)$pro_fqty ,
											   "Product" => $v,
											   "Batch_No" => $_POST["Batch_No_{$pro_id}"],
											   "Rate"  => $_POST["Rate_{$pro_id}"],
											   "Total"  => $_POST["Total_{$pro_id}"],
											   "Page_Id" => $_POST['ID'],
										   		"Page_Name" => $pagename,
											   "Sr" => $srI,
											   );
					$srI++;
					
				}
			}
		
		}

		return $ac_trn;
		
	}
	else if( $pagename == 'REFER OUT' )
	{
		
		
		$referAmount = 0;
		$billDate = $_POST['Bill_Date'];
		$debtorAccount = $_POST['Patient_Name'];
		$descriptionBill = "Bill Ganerated from REFER OUT";
		$descriptionData = "cash from REFER OUT";
		//$registerAC = $systemAC["RECEPTION CASH"];
		$registerAC = $_POST['Register'];
		$ProfitId = $systemAC["REFER-OUT PROFIT"];
		$register = $systemAC['REFER-OUT'];
		$totalRefer = 0 ;
		foreach( $_POST as $k => $v)
		{
			if( preg_match("/Report_Name_/",$k) && strlen($v) > 0 )
			{	
				$referAmount = 0;
				$pro_id = explode("Report_Name_",$k)[1];
				$reportName = $_POST["Report_Name_{$pro_id}"];
				$referOut = $_POST["Refer_Out_{$pro_id}"];;
				$referQry = $wpdb->get_results("SELECT ID,Breakup 
												From {$wpdb->prefix}reportgroup_breakup 
												WHERE Page_Id='{$reportName}' AND
													  Refer_Out='{$referOut}' limit 1","ARRAY_A");
				if( count($referQry) > 0 )	
				{
					foreach ( $referQry as $key => $value )
					{
						//print_r($value);
						$referAmount = (float)$value['Breakup'];
						$totalRefer += $referAmount;
					}
				}
				$ac_trn['trnFile'][] = array( "Register" => $register, 
									   "Account_Name" => $_POST["Refer_Out_{$pro_id}"] ,
									   "Bill_Date" => $billDate,
									   "Order_No" => $_POST['Order_No'],
									   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 , 
									   "Amount" => (float)$referAmount * -1 ,
									   "Description" => $descriptionBill ,
									   "Cheque_No"      => '',
									   "Page_Id" => $_POST['ID'],
									   "Page_Name" => $pagename,
										"Sr" => 1, 
									   );
															   
			
			}
		}				
				
		$_POST['Bill_Amount'] = isset(  $_POST['Bill_Amount'] ) ? $_POST['Bill_Amount'] : 0 ;
		$profit_amount = (float)$_POST['Bill_Amount'] - $totalRefer;
		$ac_trn['trnFile'][] = array( "Register" => $register, 
									   "Account_Name" => $debtorAccount , 
									   "Bill_Date" => $billDate,
									   "Order_No" => $_POST['Order_No'],
									   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
									   "Amount" => (float)$_POST['Bill_Amount'] ,
									   "Description" => $descriptionBill ,
									   "Cheque_No"      => '',
									   "Page_Id" => $_POST['ID'],
										"Page_Name" => $pagename,
									   "Sr" => 2,
									   );
										
		$ac_trn['trnFile'][] = array( "Register" => $register, 
										   "Account_Name" => $ProfitId ,
										   "Bill_Date" => $billDate,
										   "Order_No" => $_POST['Order_No'],
										   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 , 
										   "Amount" => (float)$profit_amount * -1 ,
										   "Description" => $descriptionBill ,
										   "Cheque_No"      => '',
										   "Page_Id" => $_POST['ID'],
										   "Page_Name" => $pagename,
											"Sr" => 3,
										   );
		$ac_trn['trnFile'][] = array(  "Register" => $registerAC, 
										 "Account_Name" => $debtorAccount ,
										 "Bill_Date" => $billDate,
										 "Order_No" => $_POST['Order_No'],
										 "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
										 "Amount" => (float)$_POST['Cash_Receive'] * -1 ,
										 "Description" => $descriptionData ,
										 "Cheque_No"      => '',
										 "Page_Id" => $_POST['ID'],
										 "Page_Name" => $pagename,
										 "Sr" =>4,
									   );
		$ac_trn['trnFile'][] = array( 
								   "Register" => $registerAC, 
								   "Account_Name" => $registerAC ,
								   "Bill_Date" => $billDate,
								   "Order_No" => $_POST['Order_No'],
								   "Bill_No" =>isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
								   "Amount" => (float)$_POST['Cash_Receive'] ,
								   "Description" => $descriptionData ,
								   "Cheque_No"      => '',
								   "Page_Id" => $_POST['ID'],
								   "Page_Name" => $pagename,
									"Sr" => 5,
							   );
					
			//if( $_POST['Bill_No'] == 0 ) { unset($ac_trn['debtor_amount']); unset($ac_trn['profit_amount']);}
			//print_r($ac_trn);
			return $ac_trn;
			
	}
	else if(  $pagename == 'TEST' ||  $pagename == 'ADMIT' || $pagename == 'REFER' || $pagename == 'APPOINTMENT' ||  $pagename == 'RECEPTION' || $pagename == 'DISCHARGE'  )
	{
		
		if( $pagename == 'ADMIT' ) 
		{
			
			$joinAppoint = "{$wpdb->prefix}appointment";
            $joinUsers = "{$wpdb->prefix}users";
			
		  $debtorQry = $wpdb->get_results("select  {$joinUsers}.ID
							FROM  {$joinAppoint}
							LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$joinAppoint}.Patient_Name)
							WHERE {$joinAppoint}.isTrash = 0 AND {$joinAppoint}.ID ='{$_POST['Patient_Name']}' AND {$joinUsers}.ID IS NOT NULL ", 'ARRAY_A');;
			if( count ($debtorQry)  == 1)
			{
				$debtorAccount = $debtorQry[0]['ID'];
			}
			$billDate = $_POST['Admit_Date'];
			$descriptionBill = "Bill Ganerated from ADMIT";
			$descriptionData = "cash from ADMIT";
			// $registerAC = $systemAC["RECEPTION CASH"];
			$registerAC = $_POST['Register'];
			 $ProfitId = $systemAC["PATIENT PROFIT"];
			 $register = $systemAC['ADMIT'];
		}
		else if( $pagename == 'APPOINTMENT'   ) 
		{
			
			$billDate = $_POST['Appoint_Date'];
			$debtorAccount = $_POST['Patient_Name'];
			$descriptionBill = "Bill Ganerated from APPOINTMENT";
			$descriptionData = "cash from APPOINTMENT";
			//$registerAC = $systemAC["RECEPTION CASH"];
			$registerAC = $_POST['Register'];
			$ProfitId = $systemAC["PATIENT PROFIT"];
			$register = $systemAC['APPOINTMENT'];
		}
			else if( $pagename == 'REFER' ) 
			{
				

				$billDate = $_POST['Payment_Date'];
				$debtorAccount = $_POST['Refer_To'];
				$_POST['Bill_Amount'] = $_POST['Cash_Receive'];
				$descriptionBill = "Bill Ganerated from REFER";
				$descriptionData = "cash from REFER";
			 	//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $_POST['Register'];
			 	$ProfitId = $systemAC["REFER PROFIT"];
			 	$register = $systemAC['REFER'];
			}

			
			
			else if( $pagename == 'DISCHARGE' ) 
			{
				$joinAdmit = "{$wpdb->prefix}admit";
				$joinAppoint = "{$wpdb->prefix}appointment";
				$joinUsers = "{$wpdb->prefix}users";
			
				$debtorQry = $wpdb->get_results("select  {$joinUsers}.ID
							FROM  {$joinAdmit}
							LEFT JOIN {$joinAppoint} ON ( {$joinAppoint}.ID = {$joinAdmit}.Patient_Name)
							LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$joinAppoint}.Patient_Name)
							WHERE {$joinAdmit}.isTrash = 0 AND {$joinAdmit}.ID ='{$_POST['Patient_Name']}' AND {$joinUsers}.ID IS NOT NULL ", 'ARRAY_A');;
			if( count ($debtorQry)  == 1)
			{
				$debtorAccount = $debtorQry[0]['ID'];
			}
			
				$billDate = $_POST['Discharge_Date'];
				//$debtorAccount = $_POST['Patient_Name'];
				$descriptionBill = "Bill Ganerated from DISCHARGE";
				$descriptionData = "cash from DISCHARGE";
				//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $_POST['Register'];
				$ProfitId = $systemAC["PATIENT PROFIT"];
				$register = $systemAC['DISCHARGE'];
			}
			else if( $pagename == 'RECEPTION' ) 
			{
				
				$billDate = $_POST['Bill_Date'];
				$debtorAccount = $_POST['Patient_Name'];
				$descriptionBill = "Bill Ganerated from RECEPTION";
				$descriptionData = "cash from RECEPTION";
				//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $_POST['Register'];
				$ProfitId = $systemAC["PATIENT PROFIT"];
				$register = $systemAC['RECEPTION'];
				//$registerAC = $systemAC[$pagename];
			}else if( $pagename == 'TEST' ) 
			{
				
				$billDate = $_POST['Bill_Date'];
				$debtorAccount = $_POST['Patient_Name'];
				$descriptionBill = "Bill Ganerated from TEST";
				$descriptionData = "cash from TEST";
				//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $_POST['Register'];
				$ProfitId = $systemAC["TEST PROFIT"];
				$register = $systemAC['TEST'];
				//$registerAC = $systemAC[$pagename];
			}		
			
			

			
			$_POST['Bill_Amount'] = isset(  $_POST['Bill_Amount'] ) ? $_POST['Bill_Amount'] : 0 ;
			$ac_trn['trnFile'] = array( 'debtor_amount' => array( "Register" => $register, 
													   "Account_Name" => $debtorAccount ,
													   "Bill_Date" => $billDate,
													   "Order_No" => $_POST['Order_No'],
													   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
													   "Amount" => (float)$_POST['Bill_Amount'] ,
													   "Description" => $descriptionBill ,
													   "Cheque_No"      => '',
													   "Page_Id" => $_POST['ID'],
														"Page_Name" => $pagename,
													   "Sr" => 2,
													   ),
									'profit_amount' => array( "Register" => $register, 
													   "Account_Name" => $ProfitId ,
													   "Bill_Date" => $billDate,
													   "Order_No" => $_POST['Order_No'],
													   "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 , 
													   "Amount" => (float)$_POST['Bill_Amount'] * -1 ,
													   "Description" => $descriptionBill ,
													   "Cheque_No"      => '',
													   "Page_Id" => $_POST['ID'],
													   "Page_Name" => $pagename,
													    "Sr" => 3,
													   ),
									
									'debtor_payment' => array(  "Register" => $registerAC, 
														 "Account_Name" => $debtorAccount ,
														 "Bill_Date" => $billDate,
														 "Order_No" => $_POST['Order_No'],
														 "Bill_No" => isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
														 "Amount" => (float)$_POST['Cash_Receive'] * -1 ,
														 "Description" => $descriptionData ,
														 "Cheque_No"      => '',
														 "Page_Id" => $_POST['ID'],
														 "Page_Name" => $pagename,
														 "Sr" =>4,
													   ),
									'register_payment' => array( 
														   "Register" => $registerAC, 
														   "Account_Name" => $registerAC ,
														   "Bill_Date" => $billDate,
														   "Order_No" => $_POST['Order_No'],
														   "Bill_No" =>isset(  $_POST['Bill_No'] ) ? $_POST['Bill_No'] : 0 ,
														   "Amount" => (float)$_POST['Cash_Receive'] ,
														   "Description" => $descriptionData ,
														   "Cheque_No"      => '',
														   "Page_Id" => $_POST['ID'],
														   "Page_Name" => $pagename,
															"Sr" => 1,
													   ),
							);
			//if( $_POST['Bill_No'] == 0 ) { unset($ac_trn['debtor_amount']); unset($ac_trn['profit_amount']);}
			//print_r($ac_trn);
			return $ac_trn;
							
	}
	
	else if(  $pagename == 'BANKCASH' || $pagename == 'BANK-CASH' || $pagename == 'PHARMACYCASH' || $pagename == 'RECEPTIONCASH'  ) 
	{
		$regAmount = 0;
		if( $_POST['Register'] == $systemAC['CONTRA'] )
		{
			if( strlen( $_POST["Account_Name_1"] ) > 0 )
			{
				$amount = (float)$_POST["CR_Amt_1"] > 0 && strlen($_POST["CR_Amt_1"] ) > 0 ?  (float)$_POST["CR_Amt_1"] * -1 : (float)$_POST["DR_Amt_1"];
				$regAmount += (float)$amount;
				$ac_trn['trnFile'][] = array( 
										   "Register" 		=> $_POST['Account_Name_1'], 
										   "Account_Name"   => $_POST['Account_Name_2'] ,
										   "Bill_Date"      => $_POST['Bill_Date'],
										   "Order_No"       => 0,
										   "Bill_No"        => 0,
										   "Cheque_No"      => $_POST["Cheque_No_1"],
										   "Amount"         => $amount ,
										   "Description"    => $_POST["Description_1"],
										   "Page_Id" => $_POST['ID'],
										   "Page_Name" => $pagename,
										   "Sr"             => 999,
										   );
				
				
			}
			if( strlen( $_POST["Account_Name_2"] ) > 0 )
			{
				$amount = (float)$_POST["CR_Amt_1"] > 0 && strlen($_POST["CR_Amt_1"] ) > 0 ?  (float)$_POST["CR_Amt_1"] * -1 : (float)$_POST["DR_Amt_1"];
				$regAmount += (float)$amount;
				$ac_trn['trnFile'][] = array( 
										   "Register" 		=> $_POST['Account_Name_2'], 
										   "Account_Name"   => $_POST['Account_Name_1'] ,
										   "Bill_Date"      => $_POST['Bill_Date'],
										   "Order_No"       => 0,
										   "Bill_No"        => 0,
										   "Cheque_No"      => $_POST["Cheque_No_1"],
										   "Amount"         => $amount  * -1,
										   "Description"    => $_POST["Description_1"],
										   "Page_Id" => $_POST['ID'],
										   "Page_Name" => $pagename,
										   "Sr"             => 999,
										   );
				
				
			}
		}
		else
		{
			foreach( $_POST as $key => $value)
			{
				if( preg_match("/Account_Name_/" ,$key ) && strlen( $_POST[$key] ) > 0 )
				{
					$thisId = explode("Account_Name_" , $key );
					$thisId = (int)$thisId[1];
					$amount = (float)$_POST["CR_Amt_{$thisId}"] > 0 && strlen($_POST["CR_Amt_{$thisId}"] ) > 0 ?  (float)$_POST["CR_Amt_{$thisId}"] * -1 : (float)$_POST["DR_Amt_{$thisId}"];
					$regAmount -= (float)$amount;
					$ac_trn['trnFile'][] = array( 
											   "Register" 		=> $_POST['Register'], 
											   "Account_Name"   => $_POST[$key] ,
											   "Bill_Date"      => $_POST['Bill_Date'],
											   "Order_No"       => $_POST["Order_No_{$thisId}"],
											   "Bill_No"        => 0,
											   "Cheque_No"      => $_POST["Cheque_No_{$thisId}"],
											   "Amount"         => $amount ,
											   "Description"    => $_POST["Description_{$thisId}"],
											   "Page_Id" => $_POST['ID'],
											   "Page_Name" => $pagename,
											   "Sr"             => $srI,
											   );
					$srI++;
					
				}
			}
			if( $regAmount != 0 )
			{
				$regId =  $_POST['Register'] ;
				$ac_trn['trnFile'][] = array( 
												   "Register" 		=> $_POST['Register'], 
												   "Account_Name"   => $regId ,
												   "Bill_Date"      => $_POST['Bill_Date'],
												   "Order_No"       => 0,
												   "Bill_No"        => 0,
												   "Cheque_No"      => '',
												   "Amount"         => $regAmount ,
												   "Description"    => '',
												   "Page_Id" => $_POST['ID'],
													"Page_Name" => $pagename,
													"Sr"            => $srI,
												   );
						$srI++;
			}
		}
		return $ac_trn;
		
	} 
}

	
function referTrn()
{
	global $wpdb;
	global $field;
	global $errorMsg;
	$atts = $_POST['ADD'];
	//print_r($_POST);
	//$createField = new saplingMiscFunction();
	
	
	$pagename = strtoupper($field['fieldData']['pageName']);
			
	
	$trashD = array();
	$trashD['Page_Id'] = $_POST['ID'];
	$trashD['Page_Name'] = $pagename;
	$result = $wpdb->delete($wpdb->prefix."trntbl"  , $trashD  );
	if ( $pagename == 'PURCHASE' || $pagename == 'SALE' )
	{ 
		$result = $wpdb->delete($wpdb->prefix."stktbl" , $trashD  );
	}
	



	if( preg_match("/UPDATE/",$atts ) || preg_match("/ADD/",$atts ) || preg_match("/RESTORE/",$atts ) )
	{	
		if ( preg_match("/UPDATE/",$atts ) )  $errorName = 'Updated';
		else if ( preg_match("/RESTORE/",$atts ) )  $errorName = 'Restored';
		else  $errorName = 'Added';
		
		$ac_trn = trnData();
	
		$is_full_succ = $is_full_suc = true;
		if( count ( $ac_trn['trnFile'] ) >  0 )
		{
			foreach( $ac_trn['trnFile'] as $k => $v)
			{
				if($v['Amount'] != 0)
				{	
					$result = $wpdb->insert($wpdb->prefix."trntbl", $v  );
					if(!$result) $is_full_succ = false;
				}
			}
		}
		else
		{
			$errorMsg[] = array( "No Transcations found for {$atts}",  false);
			return false;
		}
		if( isset($ac_trn['stkFile']) )
		{
			if( count($ac_trn['stkFile']) > 0 )	
			{
				foreach( $ac_trn['stkFile'] as $k => $v)
				{
					if($v['Qty'] != 0)
					{	
						$result = $wpdb->insert($wpdb->prefix."stktbl", $v  );
						if(!$result) $is_full_suc = false;
					}
				}
			}
			else
			{
				$errorMsg[] = array( "No Stock Transcations found for {$atts}",  false);
				return false;
			}
		}
		if($is_full_succ && $is_full_suc)
		{
			$errorMsg[] = array( "All Transcations successfully {$errorName}" ,  true);
			return true;
		}
			
		else 
		{	
			$errorMsg[] = array( "All Transcations failed {$atts}",  false);
			return false;
		}
			
	}		
}
	
	
function getDataName($tbl,$name,$id)
{
	global $wpdb;
	$getData = $wpdb->get_results("select * from {$wpdb->prefix}{$tbl} where  isTrash = 0 AND  id='{$id}'",'ARRAY_A' );
	if( count(  $getData ) == 1 )
	{
		$str = '';
		foreach( $getData as $key => $value )
		{
			$i = 0 ;
			foreach( $name as $k => $v )
			{
				$str .= ($i==0) ? $value[$v] :  "--" . $value[$v];
				$i++;
			}
		}
		return $str;
	}
	else{
		return "NOT-FOUND";
	}
}

function availibityTrn($atts, $pagename)
{
	global $wpdb;
	$deleteId = array();
	$deleteId['Doctor_Id'] = $_POST['Doctor_Id'];
	$deleteId['Available_Date'] = $_POST['Available_Date'];
	$result = $wpdb->delete("{$wpdb->prefix}{$pagename}data", $deleteId);

	$insertData = array();
	$insertData['Available_Date'] = $_POST['Available_Date'];
	$totalEntry = 0;
	$insertEntry = 0;
	$insertData['Doctor_Id'] = $_POST['Doctor_Id'];
	$insertData['Available_Date'] = $_POST['Available_Date'];
	foreach( $_POST as $key => $val)
	{
		if( preg_match( "/checkbox_/", $key ) )
		{
			$insertData['Available_Time'] = $val;
			$result = $wpdb->insert( "{$wpdb->prefix}{$pagename}data", $insertData);
			if( $result) $insertEntry++;
			$totalEntry++;
		}
	}
}





function getDischarge()
{
  
global $wpdb;
global $systemAC;
global $field ;
global $fieldValue ;
global $jsData;
$classUI = new classUI();
$classMysql = new classMysql();	

$registername = array('BANK', 'CASH', 'JV');
$jsData['registerList'] = $classMysql->getAccountList($registername);


$currChargeAmt = $totalRoomAmt  = $totalNurseAmt  = $totalDoctorAmt  = $totalRoomIPDAmt  = $totalNurseIPDAmt = $totalAdvanceAmt = 0;
$jsData['chargeData'] = $classMysql->chargeData();

$atbl = $wpdb->prefix."admit";
$aptbl = $wpdb->prefix."appointment";
$dtbl = $wpdb->prefix."discharge";
$ttbl = $wpdb->prefix."transferroom";
$ditbl = $wpdb->prefix."doctoripd";
$nitbl = $wpdb->prefix."nurseipd";
$trntbl = $wpdb->prefix."trntbl";
$returnStr = "";	

 $field['fieldData']['downWard'] = "down";     
$field[5]['multi']['Charge_Type']['Charge_Type'] = array(
	"fieldType" => "select",
	"colClass" => "col-sm-8",
    "formGroup" => "vertical",
    "spanWidth" => "120",
    "optionList" => $jsData['chargeData'] ,
    "firstOption" => "Select Charge Type",
	"fieldValue" => array (
		"name" => "Charge_Type",
		"id" => "Charge_Type",
		"required" => true,
		"showmessage" => "hide",
		"class" => "form-control select2",
		
	)
);

$field[5]['multi']['Charge_Type']['Amount'] = array(
	"fieldType" => "input",
	"colClass" => "col-sm-4",
    "formGroup" => "vertical",
    "spanWidth" => "120",
	"fieldValue" => array (
		"name" => "Charge_Amt",
		"id" => "Charge_Amt",
		"type" => "number",
		"class" => "form-control",
		"min" => 0,
		"step" => 1,
		"showmessage" => "hide",
		"required" => true,
		"max" => 999999,
		"maxlength" => 9,
	)
);

//


 
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldType"] = "input";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["colClass"] = "col-sm-12";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["rowOpen"] = "YES";
    $field[6]['field']['Bill_Amount']['Bill_Amount']["rowClose"] = "YES";
    $field[6]['field']['Bill_Amount']['Bill_Amount']["formGroup"] = "horizontal";
    $field[6]['field']['Bill_Amount']['Bill_Amount']["spanWidth"] = "140";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["required"] = true;
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["name"] = "Bill_Amount";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["id"] = "Bill_Amount";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["type"] = "number";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["class"] = "form-control";
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["min"] = 0;
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["step"] = 1;
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["max"] = 9999999;
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["maxlength"] = 10;
	$field[6]['field']['Bill_Amount']['Bill_Amount']["fieldValue"]["readonly"] = true;
	

	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldType"] = "input";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["colClass"] = "col-sm-12";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["rowOpen"] = "YES";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["rowClose"] = "YES";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["formGroup"] = "horizontal";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["spanWidth"] = "140";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["required"] = true;
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["name"] = "Pending_Amount";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["id"] = "Pending_Amount";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["type"] = "number";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["class"] = "form-control";
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["min"] = 0;
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["step"] = 1;
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["max"] = 9999999;
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["maxlength"] = 10;
	$field[6]['field']['Pending_Amount']['Pending_Amount']["fieldValue"]["readonly"] = true;
	
	$field[6]['field']['Discount']['Discount']["fieldType"] = "input";
	$field[6]['field']['Discount']['Discount']["colClass"] = "col-sm-12";
	$field[6]['field']['Discount']['Discount']["rowOpen"] = "YES";
	$field[6]['field']['Discount']['Discount']["rowClose"] = "YES";
	$field[6]['field']['Discount']['Discount']["formGroup"] = "horizontal";
	$field[6]['field']['Discount']['Discount']["spanWidth"] = "140";
	$field[6]['field']['Discount']['Discount']["fieldValue"]["name"] = "Discount";
	$field[6]['field']['Discount']['Discount']["fieldValue"]["id"] = "Discount";
	$field[6]['field']['Discount']['Discount']["fieldValue"]["type"] = "number";
	$field[6]['field']['Discount']['Discount']["fieldValue"]["class"] = "form-control";
	$field[6]['field']['Discount']['Discount']["fieldValue"]["min"] = 0;
	$field[6]['field']['Discount']['Discount']["fieldValue"]["step"] = 1;
	$field[6]['field']['Discount']['Discount']["fieldValue"]["max"] = 9999999;
	$field[6]['field']['Discount']['Discount']["fieldValue"]["maxlength"] = 10;
	
 
	
	
	$field[6]['field']['Register']['Register']["fieldType"] = "select";
	$field[6]['field']['Register']['Register']["colClass"] = "col-sm-12 select2";
	$field[6]['field']['Register']['Register']["rowOpen"] = "YES";
	$field[6]['field']['Register']['Register']["rowClose"] = "YES";
	$field[6]['field']['Register']['Register']["formGroup"] = "horizontal";
	$field[6]['field']['Register']['Register']["spanWidth"] = "140";
	$field[6]['field']['Register']['Register']["fieldValue"]["name"] = "Register";
	$field[6]['field']['Register']['Register']["fieldValue"]["id"] = "Register";
	$field[6]['field']['Register']['Register']["fieldValue"]["class"] = "form-control select2";
	$field[6]['field']['Register']['Register']['firstOption'] = "Select Register";
	$field[6]['field']['Register']['Register']['optionList'] = $jsData['registerList'];
	
	
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldType"] = "input";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["colClass"] = "col-sm-12";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["rowOpen"] = "YES";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["rowClose"] = "YES";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["formGroup"] = "horizontal";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["spanWidth"] = "140";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["name"] = "Cash_Receive";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["id"] = "Cash_Receive";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["type"] = "number";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["class"] = "form-control";
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["min"] = 0;
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["step"] = 1;
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["max"] = 9999999;
	$field[6]['field']['Cash_Receive']['Cash_Receive']["fieldValue"]["maxlength"] = 10;

	

	$field[2]['field']['Patient_Name']['Patient_Name']['fieldValue']['readonly'] = 'YES';
	$field[2]['field']['Discharge_Date']['Discharge_Date']['fieldValue']['readonly'] = 'YES';
	$field[2]['field']['Discharge_Time']['Discharge_Time']['fieldValue']['readonly'] = 'YES';
	$field[2]['field']['Charge_Per_Hours']['Charge_Per_Hours']['fieldValue']['readonly'] = 'YES';

	$field[5]['Group_Prefix'] = '<div class="row"><div class="col-sm-12 col-md-7">';
	$field[5]['Group_Suffix'] = $value['Group_Suffix'] = '</div>';
	$field[6]['Group_Prefix'] = '<div class="col-sm-12 col-md-5">';
	$field[6]['Group_Suffix'] = $value['Group_Suffix'] = '</div></div>';


ksort($field, 1);


//Print_r($roomChargeList);
	
$admit_Query 	= $wpdb->get_results("select {$atbl}.*,{$aptbl}.ID AS appointPatientID from {$atbl} 
									JOIN {$aptbl} ON ({$aptbl}.ID = {$atbl}.Patient_Name)
									  where {$atbl}.ID='{$_POST['Patient_Name']}'",'ARRAY_A' );
$discharge_Query 	= $wpdb->get_results("select * from {$dtbl} where ID='{$_POST['ID']}' and isTrash=0 ",'ARRAY_A' );
	
if( count( $discharge_Query ) == 1 )
{

	$getData = $discharge_Query[0];
	if( isset(  $getData['jsonData'] ) )
	{	
		$allData = json_decode( $getData['jsonData'] , true);
		foreach( $allData as $k => $v )
		{	if( preg_match("/Amount_/",$k) ) 
				$currChargeAmt += (float)$v;
			if( !isset($_POST[$k]) )
				$_POST[$k] = $v;
		}

		//unset ( $getData['jsonData'] ); 
	}
			
	foreach( $getData as $k => $v )
	{	
		if( !isset($_POST[$k]) )
		$_POST[$k] = $v;
	}
    $_POST['ADD'] = 'UPDATE DISCHARGE';
	
}
else{
	

    $_POST['ADD']  = 'ADD DISCHARGE';
} 


	


if( count ($admit_Query) == 1 )
{	
	
	//echo $_POST['patient_name'];
	$admit_data = $admit_Query[0];
	$roomChargeList = $classMysql->getRoomPriceList($admit_data['Admit_Date']);
	//print_r($roomChargeList);
	$transfer_Query  = $wpdb->get_results("select * from {$ttbl} where Patient_Name='{$_POST['Patient_Name']}' and isTrash=0 order by Transfer_Date, Transfer_Time",'ARRAY_A' );
	//$doctoripd_Query = $wpdb->get_results("select jsonData from {$ditbl} where Patient_Name='{$_POST['Patient_Name']}' and isTrash=0 and jsonData != '' order by IPD_Date, id",'ARRAY_A' );
	//$nurseipd_Query  = $wpdb->get_results("select jsonData from {$nitbl} where Patient_Name='{$_POST['Patient_Name']}'  and isTrash=0 and jsonData != ''  order by IPD_Date, id",'ARRAY_A' );
 	$advance_Query	= $wpdb->get_results("select DATE_FORMAT(Bill_Date ,'%d-%m-%Y') as Bill_Date,
												 Amount,
												 Register,
												 Description 
										  from {$trntbl} 
										  where account_name='{$admit_data['appointPatientID']}' and   
												bill_date >= '{$admit_data['Admit_Date']}'  and  
												bill_date <= '{$_POST['Discharge_Date']}' AND 
												Page_Name != 'DISCHARGE'  AND 
												Page_Name != 'APPOINTMENT' 
										  order by bill_date, id",'ARRAY_A' );

 
	$totalRoomIPDAmt = $totalRoomAmt = $totalDoctorAmt = $totalNurseIPDAmt = $totalNurseAmt = 0;

	
	$roomArray = array();
	$roomArray[] = array($admit_data['Admit_Date'],$admit_data['Admit_Time'],$admit_data['Room_No']);
	
	foreach($transfer_Query as $k => $v )
	{	//$va = json_decode($v['des'],true);
		$roomArray[] = array($v['Transfer_Date'],$v['Transfer_Time'],$v['Room_No']);		
	}
	$roomArray[] = array($_POST['Discharge_Date'],$_POST['Discharge_Time'],$_POST['Discharge_Room']);	
	$returnStr.= '<div class="row">
				        <div class="col-sm-12">
				        	<table class="table tablesorter table-striped table-bordered table-hover table-condensed" style="width:100%">';	
		
                	$returnStr.= '<tr><th>Sr</th><th>Admin Period</th><th>Room NO</th><th>Days, Hrs</th><th>Room Chrg/Day</th><th>Room Chrg</th><th>Dr Chrg/Day</th><th>Dr Chrg</th><th>Nurse Chrg/Day</th><th>Nurse Chrg</th></tr>';
	$isSuccess = true;
	for( $i=0; $i < count($roomArray) - 1; $i++ )
	{
		
		$roomA = $roomArray[$i];
		$j = (int)$i + 1;
		$roomN = $roomArray[$j];
		$t1 = strtotime( "{$roomA[0]} {$roomA[1]}" );
		$t2 = strtotime( "{$roomN[0]} {$roomN[1]}" );
		$diff = $t2 - $t1;
		$hours = $diff / ( 60 * 60 ); 
		$days = floor( (int)($diff / ( 60 * 60 * 24 )));
		$hr =  floor( (int)( ( $diff - ( $days *  60 * 60 * 24) )/ (60 * 60 ) ));
		$mi =  floor( (int)( ( $diff - ( $days * 60 * 60 * 24) -  ( $hr *  60 * 60 ) )/ (60 ) ) );
		$hours =  $hours / (int)$_POST['Charge_Per_Hours'] ;
		
		if($days < 0)
		{
			$isSuccess = false;
		}
		
		$roomChargeAmt = $roomChargeList[$roomA[2]];
		

		$roomHrs = (int)$_POST['Charge_Per_Hours'] / 24;
		$roomName = $roomChargeAmt['Ward_Name'] ." - ". $roomChargeAmt['Room_Name'] ;
		$roomCharge = (int)$roomChargeAmt['Ward_Charge'] * $roomHrs;
		$doctorCharge = (int)$roomChargeAmt['Doctor_Charge']  * $roomHrs;
		$NurseCharge = (int)$roomChargeAmt['Nursing_Charge']  * $roomHrs;
		
		$roomChargehrs = (int)($roomCharge * $hours);
		$totalRoomAmt += $roomChargehrs;
		
		$doctorChargehrs = (int)($doctorCharge * $hours);
		$totalDoctorAmt += $doctorChargehrs;
		
		$nurseChargehrs = (int)($NurseCharge * $hours);
		$totalNurseAmt += $nurseChargehrs;
		
		$returnStr.= '<tr><td>'.($i + 1).'</td>
		         <td>'. date_format( date_create( $roomA[0] ), 'd/m/y') .' '.$roomA[1].' TO '. date_format( date_create( $roomN[0] ), 'd/m/y').' '.$roomN[1] .'</td>
		         <td>'. $roomName .'</td>
		         <td>'. $days .'-Days, '. $hr .'-Hrs</td>
		         <td>'. $roomChargeAmt['Ward_Charge'] .'</td>
		         <td>'. $roomChargehrs .'</td>
		         <td>'. $roomChargeAmt['Doctor_Charge'] .'</td>
		         <td>'. $doctorChargehrs .'</td>
		         <td>'. $roomChargeAmt['Nursing_Charge'] .'</td>
		         <td>'. $nurseChargehrs .'</td>
		         </tr>';
	}
	$returnStr.= '<tr><td colspan="5" class="text-right">Total</b></td><td>'.$totalRoomAmt.'</td><td></td><td class="text-right">'.$totalDoctorAmt.'</td><td></td><td>'.$totalNurseAmt.'</td></tr>';
		 
	        $returnStr.= '</table>
	                </div>
	            </div>';
	$returnStr.= '<div class="row">
				    <div class="col-sm-12">
				    	<table class="table tablesorter table-striped table-bordered table-hover table-condensed" style="width:100%">';	
	
	
	
	if( count ( $advance_Query ) > 0 )
	{
	    $totalAdvanceAmt = 0;
		$returnStr.= "<tr><th>Sr</th><th>Date & Time</th><th>Description</th><th>Charge</th></tr>";
		$i=1;
		foreach( $advance_Query as $k => $v )
		{
						$vAmount = (float)$v['Amount'] * -1;
						$vDate =   date_format( date_create( $v['Bill_Date'] ), 'd/m/Y');
						$returnStr .= "<tr><td>{$i}</td><td>{$v['Bill_Date']}</td><td>{$v['Description']}</td><td>{$vAmount}</td></tr>";
						$totalAdvanceAmt += $vAmount;
						$i++;
				
		}
		$returnStr.= "<tr><td></td><td></td><td>Total IPD-CASH Receive</td><td>{$totalAdvanceAmt}</td></tr>";
		
	}
	
	
}

$fieldValue['Bill_Amount'] = $totalNurseAmt + $totalRoomAmt + $totalDoctorAmt + $totalRoomIPDAmt + $totalNurseIPDAmt  ;
$fieldValue['Pending_Amount'] = $totalNurseAmt + $totalRoomAmt + $totalDoctorAmt + $totalRoomIPDAmt + $totalNurseIPDAmt - $totalAdvanceAmt;

$returnStr.= '<input type="hidden" id="debtorvalue" value="'.$fieldValue['Bill_Amount'].'" />';
$returnStr.= '<input type="hidden" id="debtorvalueadvance" value="'.$totalAdvanceAmt.'" />';


$fieldValue['Bill_Amount'] += $currChargeAmt ;

			
		$returnStr.= '</table>
			    	</div>
					</div>';
		if( !$isSuccess)
		{	
			echo $classUI->noDataFound("Discharge Date cant be less than Admit Date / Transfer Date");
			unset($_POST['ADD']);
			
		}	echo $returnStr;
			$classUI->echoForm();

	
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		

		$(function () {
				$('body').on('keyup', 'input[name^=Amount_]',function () {
					//var id_no = parseInt(jQuery(this).attr('name').split('_')[2]);
					discharge_total('Amount_');
				});
				$('body').on('keyup', '#Discount',function () {
					//var id_no = parseInt(jQuery(this).attr('name').split('_')[2]);
					discharge_total('Amount_');
				});
			});
		</script>	
		<?php

}


function getTest()
{
	
	global $wpdb;
	global $systemAC;
	global $field ;
	global $fieldValue ;
	global $jsData;
	$classUI = new classUI();
	$classMysql = new classMysql();	

	$returnStr = "";	

	

	$field['1']['field']['Bill_Date']['Bill_Date']['fieldValue']['readonly'] = 'readonly';
	$field['1']['field']['Patient_Name']['Patient_Name']['fieldValue']['readonly'] = 'readonly';
	$field['1']['field']['Report_Group']['Report_Group']['fieldValue']['readonly'] = 'readonly';
	$field['1']['field']['Bill_Amount']['Bill_Amount']['fieldValue']['readonly'] = 'readonly';
	$field['1']['field']['Cash_Receive']['Cash_Receive']['fieldValue']['readonly'] = 'readonly';
	$field['1']['field']['Register']['Register']['fieldValue']['readonly'] = 'readonly';
	$field['5']['field']['ADD']['ADD']['colClass'] = "col-sm-12";
	unset($field['5']['field']['REFRESH']);
	ksort($field, 1);
	if( $_POST['ADD'] == 'UPDATE' )
	{
		$testQuery = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}test WHERE ID='{$_POST['ID']}'","ARRAY_A");
		if( count( $testQuery ) == 1 )
		{
				
			$allData = json_decode( $testQuery[0]['jsonData'] , true);
			foreach($allData as $key => $value )  if( !isset($_POST[$key]) ) $_POST[$key] = $value;
			foreach( $testQuery[0] as $k => $v )
			{	
				if( !isset($_POST[$k]) )
				$_POST[$k] = $v;
			}
	
			$_POST['ADD'] = 'UPDATE TEST';
			$fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'UPDATE TEST';
			
		}else
		{
			$_POST['ADD'] = 'ADD TEST';
			$fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD TEST';
		}
	}
	else{
		$_POST['ADD']  = 'ADD TEST';
		$fielValue['Refresh_Type']  = $_POST['Refresh_Type'] = 'ADD TEST';
	} 	

	$reportQuery = $wpdb->get_results("SELECT Report_Name FROM {$wpdb->prefix}reportgroup WHERE ID='{$_POST['Report_Group']}'","ARRAY_A");
	
	if( count( $reportQuery ) > 0 )	
	{
		$returnStr.=  '<div class="row">';
		$reportList = explode("---",$reportQuery[0]['Report_Name']);
		$autoF = 0;
		foreach( $reportList as $k => $v )
		{
			if(trim($v) != '')
			{
				$reportFieldQry = $wpdb->get_results("SELECT 
														{$wpdb->prefix}reportfield.* ,
														{$wpdb->prefix}reportname.Report_Name as reportName
													  FROM {$wpdb->prefix}reportfield 
													  LEFT JOIN {$wpdb->prefix}reportname ON ({$wpdb->prefix}reportname.ID = {$wpdb->prefix}reportfield.Report_Name)
													  WHERE {$wpdb->prefix}reportfield.Report_Name='{$v}' ORDER BY {$wpdb->prefix}reportfield.Field_Order","ARRAY_A");
				if( count( $reportFieldQry ) > 0 )
				{
					$returnStr.=  '<div class="col-12">
							<div class="alert alert-info text-center">
							'.$reportFieldQry[0]['reportName'].'
							</div>
						  </div>';
					foreach( $reportFieldQry as $key => $value )
					{
						$autofocus = $autoF == 0 ? "autofocus":"";
						$_POST[$value['Report_Field']] = isset( $_POST[$value['Report_Field']] ) ? $_POST[$value['Report_Field']] : '';
						$required = $value['Required'] == 'YES' ? 'required': '';
						$astriString = $value['Required'] == 'YES' ? ' *': '';
						if ( $value['Field_Type'] == 'select' )
						{
							$optionArr = preg_split('/\r\n|[\r\n]/',$value['Field_Option']);
							$optionList = '';
							$iField = 0;
							foreach($optionArr as $kField => $vField)
							{
								if($vField != '')
								{	$selected = '';
									if( $_POST[$value['Report_Field']]  == $vField ) $selected = ' selected';
									$optionList .= $iField == 0 ? "<option value=''>{$vField}</option>" : "<option value='{$vField}'{$selected}>{$vField}</option>";
									$iField++;
								}
							}
							$fieldType = "<select {$required} {$autofocus} class='form-control' name='{$value['Report_Field']}' id='{$value['Report_Field']}'>{$optionList}</select>";
						} else 
						{
							$inputType = '';
							if( $value['Field_Type'] = 'number') 
							{
								$inputType .=  strlen($value['Field_Min']) > 0 ? " min='{$value['Field_Min']}'" : "";
								$inputType .=  strlen($value['Field_Max']) > 0 ? " max='{$value['Field_Max']}'" : "";
								$inputType .=  strlen($value['Field_Step']) > 0 ? " step='{$value['Field_Step']}'" : "";
							}
							$inputType .=  strlen($value['Field_Maxlength']) > 0 ? " maxlength='{$value['Field_Maxlength']}'" : "";
							$fieldType = "<input {$required} {$autofocus} {$inputType} class='form-control' value='{$_POST[$value['Report_Field']]}' name='{$value['Report_Field']}' id='{$value['Report_Field']}' type='{$value['Field_Type']}' />";
						}
						
						$returnStr.=  '<div class="col-12 col-sm-6 col-md-4 my-2 form-outline">
											<label class="control-label" >'.str_replace("_"," " , $value['Report_Field']).$astriString.'</label>
											<div class="form-group">'.$fieldType.'</div>
											<label>'.stripslashes ( htmlspecialchars_decode( nl2br(  $value['Reference_Range'] ) ) ) .'</label>
										</div>';
						$autoF++;	
					}
				}
				
			}
			
		}
		
		$returnStr.= '</div>';
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
	//$classUI->echoForm();
	$classUI->startSubmitForm("submitPageForm", $field['fieldData']['formheadername']);
	wp_nonce_field(-1, $field['fieldData']['nonceField'], true, true);
	echo $returnStr;
	$classUI->showAllField('not-search');
	
	$classUI->endSubmitForm();

}


function reportGroupBreakup()
{
	global $wpdb;
	global $errorMsg;
	global $field;
	$atts = $_POST['ADD'];
	
	

	$trashD = array();
	$trashD['Page_Id'] = $_POST['ID'];
	$result = $wpdb->delete($wpdb->prefix."reportgroup_breakup"  , $trashD  );
	
	if( preg_match("/UPDATE/",$atts ) || preg_match("/ADD/",$atts ) || preg_match("/RESTORE/",$atts ) )
	{	
		if ( preg_match("/UPDATE/",$atts ) )  $errorName = 'Updated';
		else if ( preg_match("/RESTORE/",$atts ) )  $errorName = 'Restored';
		else  $errorName = 'Added';
		
		$report_trn = reportgroupData();
	
		$is_full_succ = count( $report_trn ) ;
		if( $is_full_succ == 0 )
		{
			 $errorMsg[] = array( "No Report Group to add in history",  false);
			 return false;
		}
		else
		{
			foreach( $report_trn as $k => $v)
			{
				if((float)$v['Breakup'] > 0)
				{	
					$result = $wpdb->insert($wpdb->prefix."reportgroup_breakup", $v  );
					if($result) $is_full_succ--;
				}
			}
			
			if($is_full_succ == 0)
			{
				$errorMsg[] =  array( "All Report Group successfully {$errorName}" ,  true);
				return true;
			}
			else 
			{
				$errorMsg[] =  array( "{$is_full_succ} Report Group failed {$errorName}",  false);
				return false;
			}
		}
		
			
	}		
}
	
	
		

function reportgroupData()
{
	global $field;
	$pagename = strtoupper( $field['fieldData']['pageName'] );
	$ac_trn = array(); 
	
	if(  $pagename == 'REPORTGROUP')  
	{
			foreach( $_POST as $k => $v)
			{
				if( preg_match("/Refer_Out_/",$k) )
				{	$pro_id = explode("Refer_Out_",$k)[1];
					
					
					$ac_trn[$pro_id] = array(  "Refer_Out" => $v ,
										   "Breakup" => (float)$_POST["Breakup_{$pro_id}"],
										   "Page_Id" => $_POST['ID']
										   );
					
				}
			}
		
			return $ac_trn;
	}
	
}



function salarystructure()
{
	global $wpdb;
	global $field;
	global $errorMsg;
	$atts = $_POST['ADD'];
	
	
	
	$trashD = array();
	$trashD['Page_Id'] = $_POST['ID'];
	$result = $wpdb->delete($wpdb->prefix."salarystructure_history"  , $trashD  );
		
	
	
	if( preg_match("/UPDATE/",$atts ) || preg_match("/ADD/",$atts ) || preg_match("/RESTORE/",$atts ) )
	{	
		if ( preg_match("/UPDATE/",$atts ) )  $errorName = 'Updated';
		else if ( preg_match("/RESTORE/",$atts ) )  $errorName = 'Restored';
		else  $errorName = 'Added';
		
		$packet_trn = salarystructureData();
	
		
		$is_full_succ = count( $packet_trn ) ;
		if( $is_full_succ == 0 )
		{
			$errorMsg[] =  array( "No salary structure to add in salary history",  false);
			return false;
		}
		else
		{
			foreach( $packet_trn as $k => $v)
			{
					
					$result = $wpdb->insert($wpdb->prefix."salarystructure_history", $v  );
					if($result) $is_full_succ--;
				
			}
			
			if($is_full_succ == 0)
			{
				$errorMsg[] =  array( "All salary structure successfully {$errorName}" ,  true);
				return true;
			}
				
			else 
			{	
				$errorMsg[] =  array( "{$is_full_succ} salary structure failed {$errorName}",  false);
				return false;
			}
		}
			
	}		
}
	
	
		

function salarystructureData()
{
	global $field;
	$pagename = strtoupper( $field['fieldData']['pageNm'] );
	$ac_trn = array(); 
	
	if( $pagename == 'SALARY STRUCTURE')
	{		
		foreach( $_POST as $k => $v)
		{
			
			if( preg_match("/Criteria_/",$k) )
			{	
			
				
				$pro_id = explode("Criteria_",$k)[1];
				
				
				$ac_trn[] = array(  "Criteria" => $v ,
									"Action_Date" => $_POST['Action_Date'],
									"Breakup" => $_POST['Breakup'],
									"Deduct" => $_POST['Deduct'],
									"Salary_Slab" => $_POST['Salary_Slab'],
									"Breakup_Value" => $_POST["Breakup_{$pro_id}"],
									"Type" => $_POST["Type_{$pro_id}"],
									"Basic_Breakup" => $_POST["Basic_Breakup_{$pro_id}"], 
									"Page_Id" => $_POST['ID']
									   );
				
			}
		}
	}
		
	return $ac_trn;	
}



	
	
function commissionstructure()
{
	global $wpdb;
	global $field;
	global $errorMsg;
	$atts = $_POST['ADD'];
	
	$trashD = array();
	$trashD['Page_Id'] = $_POST['ID'];
	$result = $wpdb->delete($wpdb->prefix."commissionstructure_history"  , $trashD  );
	
	if( preg_match("/UPDATE/",$atts ) || preg_match("/ADD/",$atts ) || preg_match("/RESTORE/",$atts ) )
	{	
		if ( preg_match("/UPDATE/",$atts ) )  $errorName = 'Updated';
		else if ( preg_match("/RESTORE/",$atts ) )  $errorName = 'Restored';
		else  $errorName = 'Added';
		$packet_trn = commissionstructureData();
	
		$is_full_succ = count( $packet_trn ) ;
		if( $is_full_succ == 0 )
		{
			$errorMsg[] =  array( "No Commission structure to add in history",  false);
			return false;
		}
		else
		{
			foreach( $packet_trn as $k => $v)
			{
				if((float)$v['Commission'] != 0)
				{	
					$result = $wpdb->insert($wpdb->prefix."commissionstructure_history", $v  );
					if($result) $is_full_succ--;
				}
			}
			
			if($is_full_succ == 0)
			{
				$errorMsg[] =  array( "All Commission structure successfully {$errorName}" ,  true);
				return true;
			}
				
			else 
			{	
				$errorMsg[] =  array( "{$is_full_succ} Commission structure failed {$errorName}",  false);
				return false;
			}
		}
			
	}		
}
	
	
		

function commissionstructureData()
{
	global $field;
	$pagename = strtoupper( $field['fieldData']['pageNm'] );
	$ac_trn = array(); 
	
	if( $pagename == 'COMMISSION STRUCTURE')
	{	
	
		foreach( $_POST as $k => $v)
		{
			if( preg_match("/From_Weight_/",$k) && (float)$v >= 0 )
			{	$pro_id = explode("From_Weight_",$k)[1];
				
				
				$ac_trn[] = array(  "Weight" => $v ,
									   "Action_Date" => $_POST['Action_Date'],
										"Commission" =>  (float)$_POST["Commission_{$pro_id}"],
										"Commission_Slab" => $_POST['Commission_Slab'],
									   "Page_Id" => $_POST['ID']
									   );
				
			}
		}
	}	
	return $ac_trn;
	
	
}


?>