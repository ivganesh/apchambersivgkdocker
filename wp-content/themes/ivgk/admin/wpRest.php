<?php 
define( 'DOMAIN_NAME', 'http://hospital-management.saplingtech.com' ); 
define( 'JWT_TIME', 60 * 60 * 24 * 7 ); 
//define( 'JWT_TIME', 60 );
use \Firebase\JWT\JWT; 

class classRest extends WP_REST_Controller {

    public function register_routes() 
    {
		$namespace = 'classrest';
	
		register_rest_route( $namespace, '/login' , array(
			'methods'         => 'POST',
			'callback'        => array( $this, 'actionLogin' ),
			 'permission_callback' => '__return_true'
		) );
		
		register_rest_route( $namespace, '/actionType' , array(
			'methods'         => 'POST',
			'callback'        => array( $this, 'actionData' ),
			 'permission_callback' => array( $this, 'check_api_permissions' )
		) );

		register_rest_route( $namespace, '/actionreport' , array(
			'methods'         => 'POST',
			'callback'        => array( $this, 'actionReport' ),
			 'permission_callback' => '__return_true'
		) );
		
    }
	
	/**
	 * Check API permissions with anti-spam measures
	 */
	public function check_api_permissions(WP_REST_Request $request)
	{
		// Block registration attempts from unauthenticated users
		$jwt = $request->get_header('Authorization');
		if (empty($jwt)) {
			// Log the attempt for monitoring
			error_log('AP Chambers: Unauthorized registration attempt from IP: ' . $_SERVER['REMOTE_ADDR']);
			return false;
		}
		
		// Additional anti-spam checks
		$data = $request->get_json_params() ?: $request->get_body_params();
		
		// Block suspicious user creation attempts
		if (isset($data['actionName']) && in_array($data['actionName'], ['account', 'patient', 'employee'])) {
			if (isset($data['action']) && $data['action'] === 'ADD') {
				// Check for spam patterns
				if (isset($data['Register_Email'])) {
					$email = $data['Register_Email'];
					
					// Block suspicious email patterns
					$spam_patterns = [
						'/.*@.*\.(tk|ml|ga|cf)$/i',  // Free domains
						'/.*@.*\.(ru|cn|in)$/i',     // Common spam countries
						'/\d{10,}@/i',               // Long numbers
						'/test\d*@/i',               // Test emails
						'/.*@(gmail|yahoo|hotmail)\.com$/i'  // Common disposable domains
					];
					
					foreach ($spam_patterns as $pattern) {
						if (preg_match($pattern, $email)) {
							error_log('AP Chambers: Spam registration blocked - suspicious email: ' . $email . ' from IP: ' . $_SERVER['REMOTE_ADDR']);
							return false;
						}
					}
				}
				
				// Rate limiting - check if too many requests from same IP
				$ip = $_SERVER['REMOTE_ADDR'];
				$rate_limit_key = 'apchambers_rate_limit_' . $ip;
				$rate_limit_count = get_transient($rate_limit_key);
				
				if ($rate_limit_count && $rate_limit_count > 5) {
					error_log('AP Chambers: Rate limit exceeded for IP: ' . $ip);
					return false;
				}
				
				// Increment rate limit counter
				$rate_limit_count = $rate_limit_count ? $rate_limit_count + 1 : 1;
				set_transient($rate_limit_key, $rate_limit_count, 3600); // 1 hour
			}
		}
		
		return true;
	}
	
	
	public function actionReport(WP_REST_Request $request)
	{
		$returnData = array();
		global $wpdb;
		if( $_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$jwt =  $request->get_header('Authorization'); 
			$isContinue = true; 

			if($jwt != '')
			{
				try{
					$decoded_data = JWT::decode($jwt,"owt125",array("HS256"));
					$userCheck =  array();
					$user_check['ID'] = $decoded_data->data->user_id;
					/*
					$expTime = (int)$decoded_data->exp - (int)time();
					$expT = 60 * 60 * 24;
					if( $expTime < $expT ){
						
					}
					$user_check['jwt_key'] = $jwt;
					if( !validate_jwt($user_check) )
					{ 
						$returnData['status'] = 0;
						$returnData['message'] = "Chating huh";
						$isContinue = false;
					}*/
				}
				catch(Exception $ex)
				{ 
					$returnData['status'] = 0;
					$returnData['message'] = $ex->getMessage();
					$isContinue = false;
				}  
			}
			else{
				$returnData['status'] = 0;
				$returnData['message'] = "Authorization failed";
				$isContinue = false;
			}
			 
			if($isContinue)
			{   
				$saleTbl = "{$wpdb->prefix}sale";
				$purchTbl = "{$wpdb->prefix}purchase";
				$bankTbl = "{$wpdb->prefix}bankcash";
				$userTbl = "{$wpdb->prefix}users";
				$roomTbl = "{$wpdb->prefix}roomno";
				$wardTbl = "{$wpdb->prefix}wardtype";
				$pharmacyDraftTbl = "{$wpdb->prefix}pharmacy_draft";
				$draftTbl = "{$wpdb->prefix}draft";
				$updateTable = "{$wpdb->prefix}employeeattend";
				$salaryTable = "{$wpdb->prefix}salary";
				$appointmentTable = "{$wpdb->prefix}appointment";
				$admitTable = "{$wpdb->prefix}admit";
				$batchTable = "{$wpdb->prefix}batchno";
				$productTable = "{$wpdb->prefix}product";
				$referTable = "{$wpdb->prefix}refer";
				$referOnlyTable = "{$wpdb->prefix}refer_only";
				$productTypeTable = "{$wpdb->prefix}producttype";
				$operationTable = "{$wpdb->prefix}operation";
				$opdTable = "{$wpdb->prefix}doctoropd";
				$ipdTable = "{$wpdb->prefix}doctoripd";
				$nurseTable = "{$wpdb->prefix}nurseipd";
				$dischargeTable = "{$wpdb->prefix}discharge";
				$disummTable = "{$wpdb->prefix}dischargesummary";
				$generalTable = "{$wpdb->prefix}generalbill";
				//$dataArray = json_decode($request->get_body(), true);
				$dataArray = $_POST;
				$result = array();
				if($dataArray['action'] == 'bankCashView' || $dataArray['action'] == 'contraView'  )
				{ 
					global $systemAC;
					$qry = $dataArray['action'] == 'bankCashView' ? " AND {$bankTbl}.Register != '{$systemAC['CONTRA']}'" : " AND {$bankTbl}.Register = '{$systemAC['CONTRA']}'";
					$thisResult = $wpdb->get_results("SELECT {$bankTbl}.*,
														{$userTbl}.Account_Name as Reg_Name
												  FROM 
												  {$bankTbl} 
												  LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$bankTbl}.Register) 
												  WHERE
												  {$bankTbl}.isSys = 0 {$qry}
												  ORDER BY {$bankTbl}.Bill_Date,{$userTbl}.Account_Name","ARRAY_A");
					
					foreach( $thisResult as $key => $val) 
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData'], true);
							$thisR['DR_Amt'] = $thisR['jsonData']['DR_Amt_1'];
							$thisR['CR_Amt'] = $thisR['jsonData']['CR_Amt_1'];
							$thisR['Order_No'] = $thisR['jsonData']['Order_No_1'];
							$thisR['Cheque_No'] = $thisR['jsonData']['Cheque_No_1'];
							$thisR['Description'] = $thisR['jsonData']['Description_1'];
						}
						$result[] = $thisR;
					}
					
				}
				else if ( $dataArray['action'] == 'updateAttendVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$updateTable}.*,
														{$userTbl}.Account_Name as Ac_Name
												  FROM 
												  {$updateTable} 
												  LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$updateTable}.Employee_Name) 
												  ORDER BY {$updateTable}.Attend_Date,{$userTbl}.Employee_Name","ARRAY_A");
					
				}
				else if($dataArray['action']== 'allBalanceSpecialityList' ) 
				{ 
					$result['balance'] = $wpdb->get_results("SELECT Balance_Sheet AS ID, CONCAT( Balance_Sheet , ' - ', Balance_Head ) AS  Account_Name FROM {$wpdb->prefix}balancesheet where isTrash=0  ORDER BY Balance_Sheet","ARRAY_A");;
					$result['speciality'] = $wpdb->get_results("SELECT ID, Speciality AS  Account_Name FROM {$wpdb->prefix}speciality where isTrash=0  ORDER BY Speciality","ARRAY_A");;
				}
				else if($dataArray['action']== 'employeeAttendList' )
				{
					$result = $wpdb->get_results("SELECT ID , Account_Name  FROM {$wpdb->prefix}users where
									isTrash = 0 AND isSys = 0 AND
									Balance_Sheet = 'EMPLOYEE'
									ORDER BY Account_Name","ARRAY_A");
				}
				else if($dataArray['action']== 'employeeView' )
				{
					$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users where
									isTrash = 0 AND isSys = 0 AND
                                    
                                    Balance_Sheet = 'EMPLOYEE'
									ORDER BY Account_Name","ARRAY_A");
				}
				else if($dataArray['action']== 'alluserRoleList' )
				{
					$result = $wpdb->get_results("SELECT User_Role as ID , User_Role AS Account_Name FROM {$wpdb->prefix}userrole 
									ORDER BY User_Role","ARRAY_A");
				}
				else if($dataArray['action']== 'salaryVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$salaryTable}.*,
														{$userTbl}.Account_Name as Emp_Name
												  FROM
												  {$salaryTable} 
												  LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$salaryTable}.Employee) 
												  ORDER BY {$userTbl}.Account_Name,{$salaryTable}.Start_Date","ARRAY_A");
					
				}
				else if($dataArray['action']== 'referVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$referTable}.ID,
														{$referTable}.Refer_Date,
														{$referTable}.Patient_Name,
														{$referTable}.Refer_By, 
														{$referTable}.Refer_To,
														{$referTable}.Register,
														{$referTable}.Payment_Date,
														{$referTable}.Refer_For,
														{$referTable}.Remarks,
														{$referTable}.isSys,
														{$referTable}.Order_No,
														{$referTable}.Cash_Receive,
														{$userTbl}.Account_Name as Patient,
														T1.Account_Name as referTo,
														T2.Account_Name as referBy,
														T3.Account_Name as Register_Name
												  FROM
												  {$referTable} 
												  LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$referTable}.Patient_Name)
												 LEFT JOIN {$userTbl} AS T1 ON ( T1.ID = {$referTable}.Refer_To)
												 LEFT JOIN {$userTbl} AS T2 ON ( T2.ID = {$referTable}.Refer_By)
												 LEFT JOIN {$userTbl} AS T3 ON ( T3.ID = {$referTable}.Register) 
												  ORDER BY {$referTable}.Refer_Date,{$userTbl}.Account_Name","ARRAY_A");
					
				}
				else if($dataArray['action']== 'referOnlyVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$referOnlyTable}.ID,
														{$referOnlyTable}.Refer_Date,
														{$referOnlyTable}.Refer_For,
														{$referOnlyTable}.Patient_Name,
														{$referOnlyTable}.Refer_By, 
														{$referOnlyTable}.Refer_To,
														{$referOnlyTable}.Remarks,
														{$referOnlyTable}.isSys,
														{$userTbl}.Account_Name as Patient, 
														T1.Account_Name as referTo,
														T2.Account_Name as referBy
												  FROM
												  {$referOnlyTable} 
												  LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$referOnlyTable}.Patient_Name)
												 LEFT JOIN {$userTbl} AS T1 ON ( T1.ID = {$referOnlyTable}.Refer_To)
												 LEFT JOIN {$userTbl} AS T2 ON ( T2.ID = {$referOnlyTable}.Refer_By)
												  ORDER BY {$referOnlyTable}.Refer_Date,{$userTbl}.Account_Name","ARRAY_A");
					
				}
				else if($dataArray['action']== 'accountVIEW' )
				{
					$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}users where
									isTrash = 0 AND isSys = 0 AND
                                    Balance_Sheet != '' AND
                                    Balance_Sheet != 'PATIENT' AND
                                    Balance_Sheet != 'EMPLOYEE'
									ORDER BY Account_Name","ARRAY_A");
				}
				else if($dataArray['action']== 'operationVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$operationTable}.*,
														{$userTbl}.Account_Name as Patient, 
														T1.Account_Name as Operating_Doctor,
														T2.Account_Name as Assistant_Doctor,
														T3.Account_Name as Other_DR_1,
														T4.Account_Name as Other_DR_2
												FROM
												  {$operationTable} 
												 LEFT JOIN {$admitTable} ON ( {$admitTable}.ID = {$operationTable}.Patient_Name)
												 LEFT JOIN {$appointmentTable} ON ( {$appointmentTable}.ID = {$admitTable}.Patient_Name)
												 LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name)
												 LEFT JOIN {$userTbl} AS T1 ON ( T1.ID = {$operationTable}.Operating_DR)
												 LEFT JOIN {$userTbl} AS T2 ON ( T2.ID = {$operationTable}.OT_Assistant)
												 LEFT JOIN {$userTbl} AS T3 ON ( T3.ID = {$operationTable}.Other_Doctor_1)
												 LEFT JOIN {$userTbl} AS T4 ON ( T4.ID = {$operationTable}.Other_Doctor_2)
												  ORDER BY {$userTbl}.Account_Name,{$operationTable}.Operation_Date","ARRAY_A");
					
				
				}
				else if($dataArray['action']== 'operationList' )
				{
					

					$result['patient']  =  $wpdb->get_results("SELECT
														{$admitTable}.ID,
														CONCAT( {$userTbl}.Account_Name , ' - ' ,
														{$userTbl}.Patient_Age , ' - ' ,
														{$userTbl}.Gender , ' - ' ,
														DATE_FORMAT( {$admitTable}.Admit_Date , '%d-%m-%Y'),'[Admit]' ) AS Account_Name
												  FROM {$admitTable}
													LEFT JOIN {$appointmentTable} ON ({$appointmentTable}.ID = {$admitTable}.Patient_Name)
													LEFT JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
												  WhERE
												  {$admitTable}.isTrash=0
												   ORDER BY
                                                  {$userTbl}.Account_Name, {$admitTable}.Admit_Date", 'ARRAY_A');
													
					$result['appointDR'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where User_Role='INHOUSE DOCTOR' ORDER BY Account_Name","ARRAY_A");
					$result['assistant'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where User_Role='ASSISTANT DOCTOR' ORDER BY Account_Name","ARRAY_A");
					$result['otherDR'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='OUTSIDE DOCTOR' ORDER BY Account_Name","ARRAY_A");
				
				
				}
				else if($dataArray['action']== 'rmpList' )
				{
					$result = $wpdb->get_results("SELECT ID,Account_Name FROM {$wpdb->prefix}users where
									isTrash = 0 AND 
                                    Balance_Sheet = 'RMP'
									ORDER BY Account_Name","ARRAY_A");
				}
				else if($dataArray['action']== 'admitVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$admitTable}.*,
												{$userTbl}.Account_Name as Patient,
												T1.Account_Name as Register_Name,
												CONCAT( {$roomTbl}.Room_Name , ' - ',{$roomTbl}.Room_Code , ' - ',{$roomTbl}.Ward_Type ) AS Room 
												FROM {$admitTable}  
												LEFT JOIN {$appointmentTable} ON ( {$appointmentTable}.ID = {$admitTable}.Patient_Name) 
												LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name) 
												LEFT JOIN {$roomTbl}  ON ( {$roomTbl}.Room_Code = {$admitTable}.Room_No) 
												LEFT JOIN {$userTbl} AS T1 ON ( T1.ID = {$admitTable}.Register) 
												where 
												{$admitTable}.isSys = 0 
												ORDER BY {$admitTable}.Admit_Date, {$admitTable}.Admit_Time ","ARRAY_A");
				}
				else if($dataArray['action']== 'appointmentVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$appointmentTable}.*,
												CASE WHEN  {$appointmentTable}.isClosed = 1 THEN 'DONE'
												ELSE 'Pending' END AS Consultation,
												{$userTbl}.Account_Name as Patient,
												T1.Account_Name as Appointed_Doctor,
												T2.Account_Name as Register_Name
												FROM {$appointmentTable}  
												LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name) 
												LEFT JOIN {$userTbl} AS T1 ON ( T1.ID = {$appointmentTable}.Appoint_DR) 
												LEFT JOIN {$userTbl} AS T2 ON ( T2.ID = {$appointmentTable}.Register) 
												where
												{$appointmentTable}.isSys = 0 
												ORDER BY {$appointmentTable}.Appoint_Date, {$appointmentTable}.Appoint_Time ","ARRAY_A");
				}
				else if($dataArray['action']== 'patientVIEW' )
				{
					$result = $wpdb->get_results("SELECT {$userTbl}.*,
												{$userTbl}.Account_Name as Patient_Name,
												T1.Account_Name as RMP
												FROM {$userTbl}  
												LEFT  JOIN {$userTbl} AS T1 ON ( T1.ID = {$userTbl}.RMP_Name) 
												where
												{$userTbl}.isSys = 0 AND {$userTbl}.isSys = 0 AND
												{$userTbl}.Balance_Sheet = 'PATIENT' 
												ORDER BY {$userTbl}.Account_Name","ARRAY_A");
				}
				else if($dataArray['action']== 'doctorOpdVIEW' )
				{
					$thisResult = $wpdb->get_results("SELECT {$opdTable}.*,
												{$userTbl}.Account_Name as Patient,
												{$appointmentTable}. Appoint_Date
												FROM {$opdTable}  
												LEFT  JOIN {$appointmentTable}  ON ( {$appointmentTable}.ID = {$opdTable}.Patient_Name) 
												LEFT  JOIN {$userTbl}  ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name) 
												where
												{$opdTable}.isSys = 0  
												ORDER BY {$opdTable}.OPD_Date, {$userTbl}.Account_Name","ARRAY_A");
					foreach( $thisResult as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
				}
				else if($dataArray['action']== 'doctorIpdVIEW' )
				{
					$thisResult = $wpdb->get_results("SELECT {$ipdTable}.*,
												{$userTbl}.Account_Name as Patient,
												{$admitTable}.Admit_Date
												FROM {$ipdTable}  
												LEFT  JOIN {$admitTable}  ON ( {$admitTable}.ID = {$ipdTable}.Patient_Name) 
												LEFT  JOIN {$appointmentTable}  ON ( {$appointmentTable}.ID = {$admitTable}.Patient_Name) 
												LEFT  JOIN {$userTbl}  ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name) 
												where
												{$ipdTable}.isSys = 0  
												ORDER BY {$ipdTable}.IPD_Date, {$userTbl}.Account_Name","ARRAY_A");
					foreach( $thisResult as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
				}
				else if($dataArray['action']== 'nurseIpdVIEW' )
				{
					$thisResult = $wpdb->get_results("SELECT {$nurseTable}.*,
												{$userTbl}.Account_Name as Patient,
												{$admitTable}.Admit_Date
												FROM {$nurseTable}  
												LEFT  JOIN {$admitTable}  ON ( {$admitTable}.ID = {$nurseTable}.Patient_Name) 
												LEFT  JOIN {$appointmentTable}  ON ( {$appointmentTable}.ID = {$admitTable}.Patient_Name) 
												LEFT  JOIN {$userTbl}  ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name) 
												where
												{$nurseTable}.isSys = 0  
												ORDER BY {$nurseTable}.IPD_Date, {$userTbl}.Account_Name","ARRAY_A");
					foreach( $thisResult as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
				}
				else if($dataArray['action'] == 'draftView' ) 
				{ 
					$thisResult = $wpdb->get_results("SELECT {$draftTbl}.*
												  FROM 
												  {$draftTbl}  
												  ORDER BY {$draftTbl}.Draft_Name","ARRAY_A");
					
					foreach( $thisResult as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
					
				}
				else if($dataArray['action'] == 'dischargeSummaryView' ) 
				{ 
					$thisResult = $wpdb->get_results("SELECT {$disummTable}.*,
															{$admitTable}.Admit_Date,
															{$userTbl}.Account_Name AS Patient
												  FROM {$disummTable}  
												  LEFT  JOIN {$admitTable}  ON ( {$admitTable}.ID = {$disummTable}.Patient_Name) 
												LEFT  JOIN {$appointmentTable}  ON ( {$appointmentTable}.ID = {$admitTable}.Patient_Name) 
												LEFT  JOIN {$userTbl}  ON ( {$userTbl}.ID = {$appointmentTable}.Patient_Name) 
												ORDER BY {$userTbl}.Account_Name","ARRAY_A");
					
					foreach( $thisResult as $key => $val) 
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
					
				}
				else if($dataArray['action'] == 'pharmacyDraftView' ) 
				{ 
					$thisResult = $wpdb->get_results("SELECT {$pharmacyDraftTbl}.*
												  FROM 
												  {$pharmacyDraftTbl} 
												  ORDER BY {$pharmacyDraftTbl}.Draft_Name","ARRAY_A");
					
					foreach( $thisResult as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
					
				}
				else if($dataArray['action'] == 'saleView' ||  $dataArray['action'] == 'purchaseView' ) 
				{ 
					$thisTable = $dataArray['action'] == 'saleView' ? $saleTbl : $purchTbl;
					$thisResult = $wpdb->get_results("SELECT {$thisTable}.*,
														{$userTbl}.Account_Name as Ac_Name,
														T1.Account_Name as Register_AC
												  FROM 
												  {$thisTable} 
												  LEFT JOIN {$userTbl} ON ( {$userTbl}.ID = {$thisTable}.Account_Name) 
												  LEFT JOIN {$userTbl} as T1 ON ( T1.ID = {$thisTable}.Register) 
												  ORDER BY {$thisTable}.Bill_Date,{$userTbl}.Account_Name","ARRAY_A");
					
					foreach( $thisResult as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
					
				}
				else if($dataArray['action'] == 'roomList' )
				{ 
					$result = $wpdb->get_results("SELECT 
													{$roomTbl}.*,
													{$wardTbl}.Ward_Code,
													{$wardTbl}.Ward_Name,
													{$wardTbl}.Ward_Charge,
													{$wardTbl}.Doctor_Charge,
													{$wardTbl}.Nursing_Charge
												FROM {$roomTbl}
												 LEFT JOIN {$wardTbl} ON ({$wardTbl}.Ward_Code = {$roomTbl}.Ward_type)
												 ORDER BY {$roomTbl}.Room_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] == 'wardList' )
				{ 
					$result = $wpdb->get_results("SELECT Ward_Code,Ward_Name FROM {$wardTbl} ORDER BY Ward_Name","ARRAY_A");
					 
				}
				else if($dataArray['action'] == 'patientAppointDrreferToList' )
				{
					$result['patient'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='PATIENT' ORDER BY Account_Name","ARRAY_A");
					$result['referBy'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where User_Role='INHOUSE DOCTOR' ORDER BY Account_Name","ARRAY_A");
					$result['referTo'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='REFER OUT' ORDER BY Account_Name","ARRAY_A");
				} 
				else if($dataArray['action'] == 'patientRegisterAppointDrreferToList' )
				{
					$result['patient'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='PATIENT' ORDER BY Account_Name","ARRAY_A");
					$result['register'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
					
					$result['referBy'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where User_Role='INHOUSE DOCTOR' ORDER BY Account_Name","ARRAY_A");
					$result['referTo'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='REFER OUT' ORDER BY Account_Name","ARRAY_A");
				} 
				else if($dataArray['action'] == 'patientRegisterRoomList' )
				{
					$classMysql = new classMysql();
					
					$result['register'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
					$result['patient'] = $wpdb->get_results("SELECT
														{$appointmentTable}.ID,
														CONCAT( {$userTbl}.Account_Name,' - ',
														{$userTbl}.Patient_Age,' - ',
														{$userTbl}.Gender,' - ',
														{$userTbl}.Register_Mobile,' - ',
														DATE_FORMAT( {$appointmentTable}.Appoint_Date, '%d-%m-%Y') ,'[Appoint]' ) as Account_Name
												  FROM {$appointmentTable}
													LEFT  JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
												  WhERE
												  {$userTbl}.Balance_Sheet='PATIENT' AND
												  {$appointmentTable}.isTrash=0
												   ORDER BY
													{$userTbl}.Account_Name", 'ARRAY_A');
					$result['room'] = $wpdb->get_results("select Room_Code AS ID , CONCAT( Room_Code, ' - ',Room_Name,' - ',Ward_Type ) AS  Account_Name  from {$wpdb->prefix}roomno WHERE isTrash=0 order by Room_Name ", 'ARRAY_A');
				}
				else if($dataArray['action'] == 'patientRegisterAppointDrList' )
				{
					$result['register'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
					$result['patient'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='PATIENT' ORDER BY Account_Name","ARRAY_A");
					$result['appointDR'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where User_Role='INHOUSE DOCTOR' ORDER BY Account_Name","ARRAY_A");
				}
				else if($dataArray['action'] == 'allOnlyRegisterList' )
				{ 
					$result = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] == 'allAccountList' )
				{ 
					$result['account'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet!='CASH' AND  Balance_Sheet!='BANK' ORDER BY Account_Name","ARRAY_A");
					$result['register'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] == 'allRegisterList' )
				{ 
					$result = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] == 'getBillAmount' ) 
				{ 
			
					$result = $wpdb->get_results("SELECT Appoint_Charge, Appoint_Validity, Next_Charge 
									 from {$wpdb->prefix}hospitalcharge 
									 WHERE Action_Date <= '{$dataArray['appointDate']}' 
									 ORDER BY Action_Date DESC limit 1","ARRAY_A");
		
					if( count ( $result ) == 1) 
					{
						$appointCharge = $result[0]['Appoint_Charge'];
						$appointValidity = $result[0]['Appoint_Validity'];
						$nextCharge = $result[0]['Next_Charge'];
						$result = $wpdb->get_results("SELECT 
														Appoint_Date,
														Cash_Receive,
														DATE_FORMAT( Appoint_Date, '%d-%m-%Y' ) as appointmentDate
													  FROM {$wpdb->prefix}appointment 
													  WHERE 
														Patient_Name='{$dataArray['patientId']}'
														AND Appoint_Date <= '{$dataArray['appointDate']}'
														AND Appoint_DR <= '{$dataArray['appointDR']}'														
														AND isTrash = 0 ORDER BY Appoint_Date LIMIT 1","ARRAY_A");
						if( count (  $result  ) > 0 )
						{
							$thisAppoint = $result[0]['Appoint_Date'];
							$appointmentDate = $result[0]['appointmentDate'];
							$Cash_Receive = (float)$result[0]['Cash_Receive'];
							
							$dateDiff =  round( ( strtotime($dataArray['appointDate']) - strtotime($thisAppoint) )  / (60 * 60 * 24));
							if( $dateDiff > $appointValidity )
							{
								$result = "Last appoint was on {$appointmentDate} AND paid {$Cash_Receive}----".$appointCharge;
							}else{
								$result = "Last appoint was on {$appointmentDate} AND paid {$Cash_Receive}----".$nextCharge;
							}

						}else{
							
							$result = "First appointment----".$appointCharge;
						}
					}else $result = "Hospital charge not found for ".implode("-",array_reverse(explode("-",$dataArray['appointDate'])));	
					
					 
							
				}
				else if($dataArray['action'] == 'allDebtorList' ) 
				{ 
					$result = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet!='CASH' AND  Balance_Sheet!='BANK' ORDER BY Account_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] == 'allBatchList' )
				{ 
					$result = $wpdb->get_results("SELECT 
														ROUND ( {$batchTable}.Product_MRP /  {$batchTable}.Unit_Per_Sheet , 2 ) AS Product_MRP ,
														DATE_FORMAT( {$batchTable}.Expiry_Date ,'%m-%Y' ) as Expiry_Date,
														{$batchTable}.Batch_No AS Account_Name,
														{$batchTable}.ID,
														if( {$productTable}.GST_Inclusive = 'YES', 
															ROUND ( ( {$batchTable}.Sale_Rate / (  1 + ( {$batchTable}.GST_Charge / 100) ) ) / {$batchTable}.Unit_Per_Sheet , 2 ),
															ROUND ( {$batchTable}.Sale_Rate /  {$batchTable}.Unit_Per_Sheet , 2 ) 
														) AS Sale_Rate  ,
														ROUND (  {$batchTable}.Purchase_Rate /  {$batchTable}.Unit_Per_Sheet , 2 ) AS Purchase_Rate,
														{$batchTable}.Opening_Stock,
														{$batchTable}.GST_Charge,
														{$productTable}.GST_Inclusive
												  FROM {$productTable} 
												  LEFT JOIN {$batchTable} ON ( {$batchTable}.Product_Name = {$productTable}.ID  )
												  WHERE {$batchTable}.isSys=0    AND Product_Name='{$dataArray['Product']}'
												  ORDER BY {$batchTable}.Batch_No","ARRAY_A");
					
				}
				else if($dataArray['action'] ==  'admitPatientProductNurseIpdList' )
				{
					 
					
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
					$result['dose'] = $wpdb->get_results("SELECT  Dose AS  acName FROM {$wpdb->prefix}dose where isTrash=0 ORDER BY Dose","ARRAY_A");
					$result['doseTime'] = $wpdb->get_results("SELECT  Dose_Time AS  acName FROM {$wpdb->prefix}dosetime where isTrash=0 ORDER BY Dose_Time","ARRAY_A");
					$result['Narration'] = $wpdb->get_results("SELECT  Narration AS  acName FROM {$wpdb->prefix}narration where isTrash=0 ORDER BY Narration","ARRAY_A");
					$result['admitPatient'] = $wpdb->get_results("SELECT {$admitTable}.ID, 
																		CONCAT( {$userTbl}.Account_Name , ' - ',
																				{$userTbl}.Patient_Age,' - ',
																				{$userTbl}.Gender,' - ',
																				DATE_FORMAT( {$admitTable}.Admit_Date , '%d-%m-%Y') , '[Admit]' 
																		) AS Account_Name
																	FROM {$admitTable} 
																	LEFT JOIN {$appointmentTable} ON ({$appointmentTable}.ID = {$admitTable}.Patient_Name)
																	LEFT JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
																	where {$admitTable}.isSys=0 ORDER BY {$userTbl}.Account_Name","ARRAY_A");
					
					
				}
				else if($dataArray['action'] ==  'admitPatientProductList' )
				{
					$result['diagnosis'] = $result['mh'] = $result['sr']  = array();
					$resultSymptom = $resultAllergy = array();
					$result['allergy'] = $wpdb->get_results("SELECT Allergy as acName  FROM {$wpdb->prefix}allergy where isTrash=0 ORDER BY Allergy","ARRAY_A");
					
					
					$result['symptoms'] = $wpdb->get_results("SELECT Symptom as acName  FROM {$wpdb->prefix}symptom where isTrash=0 ORDER BY Symptom","ARRAY_A");
					$result['diagnosis'] = $wpdb->get_results("SELECT Illness as acName  FROM {$wpdb->prefix}illness where isTrash=0 ORDER BY Illness","ARRAY_A");
					$result['mh'] = $wpdb->get_results("SELECT Medical_History as acName  FROM {$wpdb->prefix}medicalhistory where isTrash=0 ORDER BY Medical_History","ARRAY_A");
					
					$chrgTbl = "{$wpdb->prefix}chargetype";
					$testTbl = "{$wpdb->prefix}reportgroup";
					$chargeD = $wpdb->get_results("SELECT {$chrgTbl}.Charge_Type
													   FROM
														{$chrgTbl}
													  WHERE {$chrgTbl}.isSys=0
													  ORDER BY {$chrgTbl}.Charge_Type ", 'ARRAY_A');

					$testG = $wpdb->get_results("SELECT {$testTbl}.Group_Name as Charge_Type
													   FROM
														{$testTbl}
													  WHERE {$testTbl}.isSys=0
													  ORDER BY {$testTbl}.Group_Name ", 'ARRAY_A');
				   
					$chargeDataArr = array();
					foreach($chargeD as $key => $val)
					{
						$chargeDataArr[]['acName'] =  html_entity_decode ( stripslashes( $val['Charge_Type'] ) );
					}
					foreach($testG as $key => $val)
					{
						$chargeDataArr[]['acName'] = html_entity_decode ( stripslashes( $val['Charge_Type'] ) ) ; 
					}
					$result['sr'] = $chargeDataArr; 
								
 
					
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
					$result['dose'] = $wpdb->get_results("SELECT  Dose AS  acName FROM {$wpdb->prefix}dose where isTrash=0 ORDER BY Dose","ARRAY_A");
					$result['doseTime'] = $wpdb->get_results("SELECT  Dose_Time AS  acName FROM {$wpdb->prefix}dosetime where isTrash=0 ORDER BY Dose_Time","ARRAY_A");
					$result['Narration'] = $wpdb->get_results("SELECT  Narration AS  acName FROM {$wpdb->prefix}narration where isTrash=0 ORDER BY Narration","ARRAY_A");
					$result['admitPatient'] = $wpdb->get_results("SELECT {$admitTable}.ID, 
																		CONCAT( {$userTbl}.Account_Name , ' - ',
																				{$userTbl}.Patient_Age,' - ',
																				{$userTbl}.Gender,' - ',
																				DATE_FORMAT( {$admitTable}.Admit_Date , '%d-%m-%Y') , '[Admit]' 
																		) AS Account_Name
																	FROM {$admitTable} 
																	LEFT JOIN {$appointmentTable} ON ({$appointmentTable}.ID = {$admitTable}.Patient_Name)
																	LEFT JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
																	where {$admitTable}.isSys=0 ORDER BY {$userTbl}.Account_Name","ARRAY_A");
					
				}
				else if($dataArray['action'] ==  'appointPatientProductList' )
				{
					$result['diagnosis'] = $result['mh'] = $result['sr']  = array();
					$resultSymptom = $resultAllergy = array();
					$result['allergy'] = $wpdb->get_results("SELECT Allergy as acName  FROM {$wpdb->prefix}allergy where isTrash=0 ORDER BY Allergy","ARRAY_A");
					
					
					$result['symptoms'] = $wpdb->get_results("SELECT Symptom as acName  FROM {$wpdb->prefix}symptom where isTrash=0 ORDER BY Symptom","ARRAY_A");
					$result['diagnosis'] = $wpdb->get_results("SELECT Illness as acName  FROM {$wpdb->prefix}illness where isTrash=0 ORDER BY Illness","ARRAY_A");
					$result['mh'] = $wpdb->get_results("SELECT Medical_History as acName  FROM {$wpdb->prefix}medicalhistory where isTrash=0 ORDER BY Medical_History","ARRAY_A");
					
					$chrgTbl = "{$wpdb->prefix}chargetype";
					$testTbl = "{$wpdb->prefix}reportgroup";
					$chargeD = $wpdb->get_results("SELECT {$chrgTbl}.Charge_Type
													   FROM
														{$chrgTbl}
													  WHERE {$chrgTbl}.isSys=0
													  ORDER BY {$chrgTbl}.Charge_Type ", 'ARRAY_A');

					$testG = $wpdb->get_results("SELECT {$testTbl}.Group_Name as Charge_Type
													   FROM
														{$testTbl}
													  WHERE {$testTbl}.isSys=0
													  ORDER BY {$testTbl}.Group_Name ", 'ARRAY_A');
				   
					$chargeDataArr = array();
					foreach($chargeD as $key => $val)
					{
						$chargeDataArr[]['acName'] =  html_entity_decode ( stripslashes( $val['Charge_Type'] ) );
					}
					foreach($testG as $key => $val)
					{
						$chargeDataArr[]['acName'] = html_entity_decode ( stripslashes( $val['Charge_Type'] ) ) ; 
					}
					$result['sr'] = $chargeDataArr; 
								
 
					
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
					$result['dose'] = $wpdb->get_results("SELECT  Dose AS  acName FROM {$wpdb->prefix}dose where isTrash=0 ORDER BY Dose","ARRAY_A");
					$result['doseTime'] = $wpdb->get_results("SELECT  Dose_Time AS  acName FROM {$wpdb->prefix}dosetime where isTrash=0 ORDER BY Dose_Time","ARRAY_A");
					$result['Narration'] = $wpdb->get_results("SELECT  Narration AS  acName FROM {$wpdb->prefix}narration where isTrash=0 ORDER BY Narration","ARRAY_A");
					$result['appointPatient'] = $wpdb->get_results("SELECT {$appointmentTable}.ID, 
																			CONCAT( {$userTbl}.Account_Name,' - ',
																			{$userTbl}.Patient_Age,' - ',
																			{$userTbl}.Gender,' - ',
																			DATE_FORMAT( {$appointmentTable}.Appoint_Date , '%d-%m-%Y') , '[Appoint]' ) as Account_Name
																	FROM {$appointmentTable} 
																	JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
																	where {$appointmentTable}.isSys=0 ORDER BY {$userTbl}.Account_Name","ARRAY_A");
					
				}
				else if($dataArray['action'] ==  'allProductDraftList' )
				{ 
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
					$result['dose'] = $wpdb->get_results("SELECT  Dose AS  acName FROM {$wpdb->prefix}dose where isTrash=0 ORDER BY Dose","ARRAY_A");
					$result['doseTime'] = $wpdb->get_results("SELECT  Dose_Time AS  acName FROM {$wpdb->prefix}dosetime where isTrash=0 ORDER BY Dose_Time","ARRAY_A");
					$result['Narration'] = $wpdb->get_results("SELECT  Narration AS  acName FROM {$wpdb->prefix}narration where isTrash=0 ORDER BY Narration","ARRAY_A");
					$result['allergy'] = $wpdb->get_results("SELECT Allergy as acName  FROM {$wpdb->prefix}allergy where isTrash=0 ORDER BY Allergy","ARRAY_A");
					$result['symptoms'] = $wpdb->get_results("SELECT Symptom as acName  FROM {$wpdb->prefix}symptom where isTrash=0 ORDER BY Symptom","ARRAY_A");
					$result['diagnosis'] = $wpdb->get_results("SELECT Illness as acName  FROM {$wpdb->prefix}illness where isTrash=0 ORDER BY Illness","ARRAY_A");
					$result['mh'] = $wpdb->get_results("SELECT Medical_History as acName  FROM {$wpdb->prefix}medicalhistory where isTrash=0 ORDER BY Medical_History","ARRAY_A");
					
				}
				else if($dataArray['action'] ==  'dischargeSummaryList' )
				{ 
					$result['admitPatient'] =    $wpdb->get_results("SELECT
														{$admitTable}.ID,
														CONCAT ( {$userTbl}.Account_Name ,' - ',
														{$userTbl}.Patient_Age,' - ',
														{$userTbl}.Gender,' - ',
														DATE_FORMAT( {$admitTable}.Admit_Date , '%d-%m-%Y') , '[Admit]' ,' - ',
														 DATE_FORMAT( {$dischargeTable}.Discharge_Date , '%d-%m-%Y') , '[Discharge]' ) as Account_Name
												  FROM {$dischargeTable}
												    LEFT JOIN {$admitTable} ON ({$admitTable}.ID = {$dischargeTable}.Patient_Name)
													LEFT JOIN {$appointmentTable} ON ({$appointmentTable}.ID = {$admitTable}.Patient_Name)
													LEFT JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
												  WhERE
												  {$dischargeTable}.isTrash=0  
												   ORDER BY
													{$userTbl}.Account_Name", 'ARRAY_A');
					
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
					$result['dose'] = $wpdb->get_results("SELECT  Dose AS  acName FROM {$wpdb->prefix}dose where isTrash=0 ORDER BY Dose","ARRAY_A");
					$result['doseTime'] = $wpdb->get_results("SELECT  Dose_Time AS  acName FROM {$wpdb->prefix}dosetime where isTrash=0 ORDER BY Dose_Time","ARRAY_A");
					$result['Narration'] = $wpdb->get_results("SELECT  Narration AS  acName FROM {$wpdb->prefix}narration where isTrash=0 ORDER BY Narration","ARRAY_A");
					$result['draft'] = $wpdb->get_results("SELECT  ID,Draft_Name AS  Account_Name FROM {$wpdb->prefix}draft where isTrash=0 ORDER BY Draft_Name","ARRAY_A");
					
				}
				else if($dataArray['action'] ==  'dischargeList' )
				{ 
					$result =  $wpdb->get_results("SELECT
														{$admitTable}.ID,
														CONCAT ( {$userTbl}.Account_Name ,' - ',
														{$userTbl}.Patient_Age,' - ',
														{$userTbl}.Gender,' - ',
														DATE_FORMAT( {$admitTable}.Admit_Date , '%d-%m-%Y') , '[Admit]' ,' - ',
														 DATE_FORMAT( {$dischargeTable}.Discharge_Date , '%d-%m-%Y') , '[Discharge]' ) as Account_Name
												  FROM {$dischargeTable}
												    LEFT JOIN {$admitTable} ON ({$admitTable}.ID = {$dischargeTable}.Patient_Name)
													LEFT JOIN {$appointmentTable} ON ({$appointmentTable}.ID = {$admitTable}.Patient_Name)
													LEFT JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name)
												  WhERE
												  {$dischargeTable}.isTrash=0  
												   ORDER BY
													{$userTbl}.Account_Name", 'ARRAY_A');
					
					
				}
				else if($dataArray['action'] == 'allBatchDataList' )
				{ 
					$result = $wpdb->get_results("SELECT 
														ROUND ( {$batchTable}.Product_MRP /  {$batchTable}.Unit_Per_Sheet , 2 ) AS Product_MRP ,
														DATE_FORMAT( {$batchTable}.Expiry_Date ,'%m-%Y' ) as Expiry_Date,
														{$batchTable}.Batch_No AS Account_Name,
														{$batchTable}.ID,
														if( {$productTable}.GST_Inclusive = 'YES', 
															ROUND ( ( {$batchTable}.Sale_Rate / (  1 + ( {$batchTable}.GST_Charge / 100) ) ) / {$batchTable}.Unit_Per_Sheet , 2 ),
															ROUND ( {$batchTable}.Sale_Rate /  {$batchTable}.Unit_Per_Sheet , 2 ) 
														) AS Sale_Rate  ,
														ROUND (  {$batchTable}.Purchase_Rate /  {$batchTable}.Unit_Per_Sheet , 2 ) AS Purchase_Rate,
														{$batchTable}.Opening_Stock,
														{$batchTable}.GST_Charge,
														{$productTable}.GST_Inclusive
												  FROM {$productTable} 
												  LEFT JOIN {$batchTable} ON ( {$batchTable}.Product_Name = {$productTable}.ID  )
												  WHERE {$batchTable}.isSys=0    AND Product_Name='{$dataArray['Product']}'
												  ORDER BY {$batchTable}.Batch_No","ARRAY_A");
						
					
				 
				}
				else if($dataArray['action'] == 'allAccountProductBatchList' )
				{
					$batchTbl = "{$wpdb->prefix}batchno";
					$productTbl = "{$wpdb->prefix}product";
					$productTypeTbl = "{$wpdb->prefix}producttype";
					$result['account'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet!='CASH' AND  Balance_Sheet!='BANK' ORDER BY Account_Name","ARRAY_A");
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
												
					$result['batch'] = $wpdb->get_results("SELECT
														{$batchTbl}.ID,
														{$batchTbl}.Batch_No AS Account_Name,
														ROUND ( {$batchTbl}.Product_MRP /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Product_MRP ,
														DATE_FORMAT( {$batchTbl}.Expiry_Date ,'%m-%Y' ) as Expiry_Date,
														{$batchTbl}.Batch_No,
                                                        {$batchTbl}.ID,
														if( B1.GST_Inclusive = 'YES', 
															ROUND ( ( {$batchTbl}.Sale_Rate / (  1 + ( B1.GST_Charge / 100) ) ) / {$batchTbl}.Unit_Per_Sheet , 2 ),
															ROUND ( {$batchTbl}.Sale_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) 
															) 
															AS Sale_Rate  ,
														ROUND (  {$batchTbl}.Purchase_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Purchase_Rate,
														{$batchTbl}.Opening_Stock, 
                                                        B1.GST_Charge,
                                                        B1.GST_Inclusive
												 FROM {$batchTbl}
												 JOIN {$productTbl} ON ( {$productTbl}.ID = {$batchTbl}.Product_Name) 
												 LEFT JOIN   ( 
															SELECT *  
															FROM (
																SELECT {$productTypeTbl}.HSN, {$productTypeTbl}.GST_Charge, {$productTypeTbl}.GST_Inclusive FROM {$productTypeTbl}
																ORDER BY {$productTypeTbl}.Action_Date DESC 
															) AS BP1
															GROUP BY BP1.HSN 
															
														) B1  ON ( B1.HSN = {$productTbl}.Product_Type)
												", 'ARRAY_A');
					
					$batchGroup = $wpdb->get_results("SELECT {$batchTbl}.Product_Name
													 FROM {$batchTbl} 
													 WHERE {$batchTbl}.isSys=0 GROUP BY {$batchTbl}.Product_Name","ARRAY_A");
					foreach( $batchGroup as $key => $value )
					{
						$batchData = $wpdb->get_results("SELECT
														{$batchTbl}.ID,
														{$batchTbl}.Batch_No AS Account_Name,
														ROUND ( {$batchTbl}.Product_MRP /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Product_MRP ,
														DATE_FORMAT( {$batchTbl}.Expiry_Date ,'%m-%y' ) as Expiry_Date,
														{$batchTbl}.Batch_No,
                                                        {$batchTbl}.ID,
														if( B1.GST_Inclusive = 'YES', 
															ROUND ( ( {$batchTbl}.Sale_Rate / (  1 + ( B1.GST_Charge / 100) ) ) / {$batchTbl}.Unit_Per_Sheet , 2 ),
															ROUND ( {$batchTbl}.Sale_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) 
															) 
															AS Sale_Rate  ,
														ROUND (  {$batchTbl}.Purchase_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Purchase_Rate,
														{$batchTbl}.Opening_Stock,
                                                        B1.GST_Charge,
                                                        B1.GST_Inclusive
										  FROM {$batchTbl}  
										  LEFT  JOIN {$productTbl} ON ( {$productTbl}.ID = {$batchTbl}.Product_Name  )
										  LEFT  JOIN ( 
															SELECT *  
															FROM (
																SELECT {$productTypeTbl}.HSN, {$productTypeTbl}.GST_Charge, {$productTypeTbl}.GST_Inclusive FROM {$productTypeTbl}
																 WHERE {$productTypeTbl}.Action_Date <= '{$_POST['actionDate']}'  
																ORDER BY {$productTypeTbl}.Action_Date DESC 
															) AS BP1
															GROUP BY BP1.HSN 
															
														) B1 ON ( B1.HSN = {$productTbl}.Product_Type  )
												 WHERE
												 {$batchTbl}.isSys=0 AND {$batchTbl}.Product_Name='{$value['Product_Name']}' ORDER BY Batch_No","ARRAY_A");
						$result['batchData'][$value['Product_Name']] = $batchData;
					} 
					
					$result['register'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] == 'allAccountProductBatchDraftList' )
				{ 
			
					$batchTbl = "{$wpdb->prefix}batchno";
					$productTbl = "{$wpdb->prefix}product";
					$productTypeTbl = "{$wpdb->prefix}producttype";
					$result['account'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet!='CASH' AND  Balance_Sheet!='BANK' ORDER BY Account_Name","ARRAY_A");
					$result['product'] = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0 ORDER BY Product_Name","ARRAY_A");
					
												
					$result['batch'] = $wpdb->get_results("SELECT
														{$batchTbl}.ID,
														{$batchTbl}.Batch_No AS Account_Name,
														ROUND ( {$batchTbl}.Product_MRP /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Product_MRP ,
														DATE_FORMAT( {$batchTbl}.Expiry_Date ,'%m-%Y' ) as Expiry_Date,
														{$batchTbl}.Batch_No,
                                                        {$batchTbl}.ID,
														if( B1.GST_Inclusive = 'YES', 
															ROUND ( ( {$batchTbl}.Sale_Rate / (  1 + ( B1.GST_Charge / 100) ) ) / {$batchTbl}.Unit_Per_Sheet , 2 ),
															ROUND ( {$batchTbl}.Sale_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) 
															) 
															AS Sale_Rate  ,
														ROUND (  {$batchTbl}.Purchase_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Purchase_Rate,
														{$batchTbl}.Opening_Stock, 
                                                        B1.GST_Charge,
                                                        B1.GST_Inclusive
												 FROM {$batchTbl}
												 JOIN {$productTbl} ON ( {$productTbl}.ID = {$batchTbl}.Product_Name) 
												 LEFT JOIN   ( 
															SELECT *  
															FROM (
																SELECT {$productTypeTbl}.HSN, {$productTypeTbl}.GST_Charge, {$productTypeTbl}.GST_Inclusive FROM {$productTypeTbl}
																ORDER BY {$productTypeTbl}.Action_Date DESC 
															) AS BP1
															GROUP BY BP1.HSN 
															
														) B1  ON ( B1.HSN = {$productTbl}.Product_Type)
												", 'ARRAY_A');
					
					$batchGroup = $wpdb->get_results("SELECT {$batchTbl}.Product_Name
													 FROM {$batchTbl} 
													 WHERE {$batchTbl}.isSys=0 GROUP BY {$batchTbl}.Product_Name","ARRAY_A");
					foreach( $batchGroup as $key => $value )
					{
						$batchData = $wpdb->get_results("SELECT
														{$batchTbl}.ID,
														{$batchTbl}.Batch_No AS Account_Name,
														ROUND ( {$batchTbl}.Product_MRP /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Product_MRP ,
														DATE_FORMAT( {$batchTbl}.Expiry_Date ,'%m-%y' ) as Expiry_Date,
														{$batchTbl}.Batch_No,
                                                        {$batchTbl}.ID,
														if( B1.GST_Inclusive = 'YES', 
															ROUND ( ( {$batchTbl}.Sale_Rate / (  1 + ( B1.GST_Charge / 100) ) ) / {$batchTbl}.Unit_Per_Sheet , 2 ),
															ROUND ( {$batchTbl}.Sale_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) 
															) 
															AS Sale_Rate  ,
														ROUND (  {$batchTbl}.Purchase_Rate /  {$batchTbl}.Unit_Per_Sheet , 2 ) AS Purchase_Rate,
														{$batchTbl}.Opening_Stock,
                                                        B1.GST_Charge,
                                                        B1.GST_Inclusive
										  FROM {$batchTbl}  
										  LEFT  JOIN {$productTbl} ON ( {$productTbl}.ID = {$batchTbl}.Product_Name  )
										  LEFT  JOIN ( 
															SELECT *  
															FROM (
																SELECT {$productTypeTbl}.HSN, {$productTypeTbl}.GST_Charge, {$productTypeTbl}.GST_Inclusive FROM {$productTypeTbl}
																 WHERE {$productTypeTbl}.Action_Date <= '{$_POST['actionDate']}'  
																ORDER BY {$productTypeTbl}.Action_Date DESC 
															) AS BP1
															GROUP BY BP1.HSN 
															
														) B1 ON ( B1.HSN = {$productTbl}.Product_Type  )
												 WHERE
												 {$batchTbl}.isSys=0 AND {$batchTbl}.Product_Name='{$value['Product_Name']}' ORDER BY Batch_No","ARRAY_A");
						$result['batchData'][$value['Product_Name']] = $batchData;
					}
					
					
					$result['register'] = $wpdb->get_results("SELECT ID, CONCAT( Account_Name , ' - ', Register_Mobile ) AS  Account_Name FROM {$wpdb->prefix}users where Balance_Sheet='CASH' OR  Balance_Sheet='BANK' ORDER BY Account_Name","ARRAY_A");
				
					$draft = $wpdb->get_results("SELECT ID, Draft_Name AS  Account_Name, jsonData FROM {$wpdb->prefix}pharmacy_draft where isTrash=0 ORDER BY Draft_Name","ARRAY_A");
					$thisResult = array();
					foreach( $draft as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$thisResult[] = $thisR;
					}
					$result['draft'] = $thisResult;
				}
				else if($dataArray['action'] == 'allBalanceSheetList' )
				{
					$result = $wpdb->get_results("SELECT Balance_Sheet, CONCAT( Balance_Sheet , ' - ', Balance_Head ) AS  Account_Name FROM {$wpdb->prefix}balancesheet where isTrash=0  ORDER BY Balance_Sheet","ARRAY_A");
				}
				else if($dataArray['action'] == 'allProductMaster' ) 
				{
					$result['manufacturer'] = $wpdb->get_results("SELECT ID, CONCAT( Manufacturer , ' - ', Manufacturer_Code ) AS  Account_Name FROM {$wpdb->prefix}manufacturer where isTrash=0 ORDER BY Manufacturer","ARRAY_A");
					$result['productType'] = $wpdb->get_results("SELECT ID, CONCAT( Product_Type , ' - ', HSN ) AS  Account_Name FROM {$wpdb->prefix}producttype where isTrash=0  ORDER BY Product_Type","ARRAY_A");
				
				}
				else if ( $dataArray['action'] == 'allGstList' ) 
				{
					$result = $wpdb->get_results("SELECT GST_Charge AS ID, GST_Charge AS  Account_Name FROM {$wpdb->prefix}gstcharge where isTrash=0 ORDER BY GST_Charge","ARRAY_A");
				}
				else if($dataArray['action'] == 'allBatchNoMaster' ) 
				{ 
					//$result = $wpdb->get_results("SELECT GST_Charge AS ID, GST_Charge AS  Account_Name FROM {$wpdb->prefix}gstcharge where isTrash=0 ORDER BY GST_Charge","ARRAY_A");
					$result = $wpdb->get_results("SELECT ID, Product_Name AS  Account_Name FROM {$wpdb->prefix}product where isTrash=0  ORDER BY product_Name","ARRAY_A");
				
				}
				else if($dataArray['action'] =='generalBillView' )
				{
					$resultArray = $wpdb->get_results("SELECT {$generalTable}.*,
														  DATE_FORMAT( {$generalTable}.Discharged_Date , '%d-%m-%Y' ) as Discharge_On,
														   DATE_FORMAT({$admitTable}.Admit_Date , '%d-%m-%Y' )  as Admit_On,
														 {$userTbl}.Account_Name as Patient
												 FROM {$generalTable} 
												
												 LEFT JOIN {$admitTable} ON ({$admitTable}.ID = {$generalTable}.Patient_Name )
												 LEFT JOIN {$appointmentTable} ON ({$appointmentTable}.ID = {$admitTable}.Patient_Name )
												 LEFT JOIN {$userTbl} ON ({$userTbl}.ID = {$appointmentTable}.Patient_Name )
												  where {$generalTable}.isTrash=0  
												 ORDER BY {$generalTable}.Discharged_Date,{$userTbl}.Account_Name","ARRAY_A");
					foreach( $resultArray as $key => $val)
					{
						$thisR = $val;
						if($val['jsonData'])
						{
							$thisR['jsonData'] = json_decode($val['jsonData']);
						}
						$result[] = $thisR;
					}
				}
				
				else if($dataArray['action'] =='attendanceVIEW' )
				{
					$tableArr = $tableArray = array();
					$tbl = "{$wpdb->prefix}users";
					$satbl = "{$wpdb->prefix}employeeattend";
					
					$f_dt = strtotime(date("Y-m-d", strtotime($dataArray['fromDate'])) . " ,first day of this month");
                    $dataArray['From_Date'] = date("Y-m-d", $f_dt);
                    $t_dt = strtotime(date("Y-m-d", strtotime($dataArray['fromDate'])) . ", last day of this month");
                    $dataArray['To_Date'] = date("Y-m-d", $t_dt);
					
					
					$qry = strlen($dataArray['Employee']) > 0 ? " AND {$satbl}.Employee_Name ='{$dataArray['Employee']}' " : "";

					$employeeAttendList = $wpdb->get_results("SELECT
														{$satbl}.Attend_Date,
														{$tbl}.Account_Name,
														{$tbl}.Register_Email,
														{$tbl}.ID as ID,
														{$satbl}.Attendance
													 FROM
														{$tbl}
													LEFT JOIN {$satbl}  ON ({$satbl}.Employee_Name = {$tbl}.ID)
													WHERE
														 {$satbl}.isSys=0
														 AND {$satbl}.Attend_Date >= '{$dataArray['From_Date']}'
														 AND {$satbl}.Attend_Date <= '{$dataArray['To_Date']}'
														{$qry}
													ORDER BY {$tbl}.Account_Name", 'ARRAY_A');

					$totalCount = count($employeeAttendList);
					$i = 1;
					$dateRangeArray = array();
					$classDate = new classDate();
					$dateRangeArray = $classDate->getDatesFromRange($dataArray['From_Date'], $dataArray['To_Date']);
					if ($totalCount > 0) {
						$employeeById = $employeeByDate = $employeeId = array();
						foreach ($employeeAttendList as $key => $val) {
							$CAEmail = $val['Register_Email'];
							$timestamp = strtotime($val['Attend_Date']);
							$day = date('l', $timestamp);
							$employeeById[$val['ID']][$val['Attend_Date']] = array("ID" => $val['ID'],
								"Attend_Date" => $val['Attend_Date'],
								"Employee_Name" => $val['Account_Name'],
								"Attendance" => $val['Attendance'],
							);

							if (!isset($employeeId[$val['ID']])) {
								$employeeId[$val['ID']] = $val['Account_Name'];
							}

						}
						foreach ($employeeById as $key => $val) {
							$tableArr = array();
							$tableArr['Employee_Name'] = $employeeId[$key];
							$total = $totalDay = 0;
							foreach ($dateRangeArray as $k => $v) {
								$date = new DateTime($v);
								$vDate = $date->format('d-m-y');
								if (isset($employeeById[$key][$v])) {
									$thisData = $employeeById[$key][$v]; 
								}

								if (isset($employeeById[$key][$v])) {

									//echo $v; exit;
									$tableArr[$vDate] = $thisData['Attendance'];
									if ($thisData['Attendance'] == 'Present' || $thisData['Attendance'] == 'Holiday') {
										$total++;
									}
									else if (preg_match('/-Half/', $thisData['Attendance'])) {
										$total += 0.5;
									}

								  

								} else {
									$tableArr[$vDate] = "X";
								}
								$totalDay++;  
							}
							$tableArr["Total {$totalDay}-Days"] = strval($total); 
							$tableArray[] = $tableArr;
						}
					}
					$result = $tableArray;
				}
				else if($dataArray['action'] =='TrialBalanceData' )
				{
						$tableArr = $tableArray = array();
						$tbl = "{$wpdb->prefix}trntbl";
						$atbl = "{$wpdb->prefix}users";
						$tCR = $tDR = 0;
						$balanceSheetStr = $dataArray['Balance'] != '' ? " {$atbl}.Balance_Sheet = '{$dataArray['Balance']}' AND " : "";
						$accountData = $wpdb->get_results("SELECT {$atbl}.Opening_Balance ,
														  {$atbl}.Account_Name ,
														  {$atbl}.ID,
														  {$atbl}.Balance_Sheet,
														  sum({$tbl}.Amount) as Amount
													FROM {$tbl}
													LEFT JOIN {$atbl}  ON ({$atbl}.ID = {$tbl}.Account_Name)
												   WHERE
													   {$balanceSheetStr}
													   {$tbl}.isSys = 0
													Group BY
													{$tbl}.Account_Name", 'ARRAY_A');

						$total_opbal = $ac_opening_bal = 0;

						if (count($accountData) > 0) {

							foreach ($accountData as $key => $value) {
								$tableArr = array();
								$total_opbal += (float) $value['Opening_Balance'];
								$tAmount = (float) $value['Opening_Balance'] + (float) $value['Amount'];
								$value['Account_Name'] = isset($value['Account_Name']) ? $value['Account_Name'] : "";

								$tableArr['Account_Name'] = $value['Account_Name'];

								if ($value['Amount'] > 0) {
									$tCR += (float)$tAmount;
									$tableArr['isTrash'] = '0';
									$tableArr['DR_Amt'] =number_format(round( (float)$tAmount ,2),2);  
									$tableArr['CR_Amt'] = "";
								} else {
									$tDR += (float)$tAmount;
									$tableArr['isTrash'] = '1';
									$tableArr['DR_Amt'] = "";
									$tableArr['CR_Amt'] = number_format(round( (float)$tAmount * -1 ,2),2);  
								}
								$tableArray[] = $tableArr;
								$ac_opening_bal += $tAmount;

							}
							$tableArr = array();
							$ac_opening_bal -= $total_opbal;
							$tableArr['Account_Name'] = "SUSPENCE AC";
							if ($total_opbal < 0) {
								$tDR += (float)$total_opbal;
								$tableArr['isTrash'] = '1';
								$tableArr['DR_Amt'] = number_format(round( (float)$total_opbal * -1 ,2),2);   
								$tableArr['CR_Amt'] = "";
							} else {
								$tCR += (float)$total_opbal;
								$tableArr['isTrash'] = '1';
								$tableArr['DR_Amt'] = "";
								$tableArr['CR_Amt'] = number_format(round( (float)$total_opbal  ,2),2);  
							}
							$tableArray[] = $tableArr;
							$tableArr = array();
							$tableArr['Account_Name'] = "BAL DIFF";
							if ($ac_opening_bal < 0) {
								$tDR += (float)$ac_opening_bal;
								$tableArr['isTrash'] = '1';
								$tableArr['DR_Amt'] = number_format(round( (float)$ac_opening_bal * -1  ,2),2);  
								$tableArr['CR_Amt'] = "";
							} else {
								$tCR += (float)$ac_opening_bal;
								$tableArr['isTrash'] = '1';
								$tableArr['DR_Amt'] = "";
								$tableArr['CR_Amt'] = number_format(round( (float)$ac_opening_bal  ,2),2);  
							}
							$tableArray[] = $tableArr;
							$tableArr['Account_Name'] = "TOTAL";
							
								$tableArr['isTrash'] = '1';
								$tableArr['DR_Amt'] =  number_format(round( (float)$tDR * -1  ,2),2);  
								$tableArr['CR_Amt'] = number_format(round( (float)$tCR  ,2),2);  
							
							$tableArray[] = $tableArr;
						}
						$result = $tableArray;
				}	
				else if($dataArray['action'] == 'RegisterReportData' )
				{
						$tbl = "{$wpdb->prefix}trntbl";
						$atbl = "{$wpdb->prefix}users";

						$ac_opening_bal = 0;

						$regId = $dataArray['Account'];

						$get_trn = $wpdb->get_results("select opening_balance,ID from {$wpdb->prefix}users where isTrash =0 AND Account_Name = '{$dataArray['Account']}'  ", 'ARRAY_A');
						if (count($get_trn) == 1) {
							foreach ($get_trn as $key => $value) {
								$ac_opening_bal += (float) $value['opening_balance'];
							}

						}

						$get_trn = $wpdb->get_results("select sum(amount) as sumbal from {$tbl} 
														where isTrash = 0 AND  
														bill_date < '{$dataArray['fromDate']}' 
														and  ( Register = '{$dataArray['Account']}' AND Account_Name != '{$dataArray['Account']}' )", 'ARRAY_A');
						if (count($get_trn) == 1) {
							$ac_opening_bal += (float) $get_trn[0]['sumbal'];
						}
						$tableArr['Bill_Date'] =  "";
						$tableArr['Order_No'] =  "";
						$tableArr['Account_Name'] =  "";
						$tableArr['Cheque_No'] =  "";
						$tableArr['Description'] =  "OPN BAL";
						if ($ac_opening_bal < 0) {
							$tableArr['isTrash'] =  "0";
							$tableArr['DR_Amt'] =  number_format(round( (float)$ac_opening_bal * -1 ,2),2); 
							$tableArr['CR_Amt'] =  "";
						} else {
							$tableArr['isTrash'] =  "1";
							$tableArr['DR_Amt'] =  "";
							$tableArr['CR_Amt'] =  number_format(round( (float)$ac_opening_bal ,2),2); 
						}
						$tableArray[] = $tableArr;
						$getData = $wpdb->get_results("SELECT {$tbl}.Bill_Date,
													  {$tbl}.Bill_No,
													  {$tbl}.Cheque_No,
													  {$tbl}.Description,
													  {$tbl}.Register,
													  {$atbl}.account_name as Register_name,
													  {$tbl}.Amount,
													  {$atbl}.Account_Name
												From {$tbl}
													LEFT JOIN {$atbl}  ON ({$atbl}.ID = {$tbl}.Account_Name)
											 WHERE
												   {$tbl}.isSys = 0 AND

												   {$tbl}.Bill_Date >= '{$dataArray['fromDate']}' AND
												   {$tbl}.Bill_Date <= '{$dataArray['toDate']}' AND
													 (  {$tbl}.Register = '{$dataArray['Account']}'  AND
														{$tbl}.Account_Name != '{$dataArray['Account']}' ) 
												ORDER BY
													{$tbl}.Bill_Date,{$tbl}.ID", 'ARRAY_A');

						$totalCount = count($getData);
						if ($totalCount > 0) {

							foreach ($getData as $key => $value) {
								$thisRegister = $value['Register'];
							   

								$tableArr['Bill_Date'] =  $value['Bill_Date'];
								$tableArr['Order_No'] =  $value['Bill_No'];
								$tableArr['Account_Name'] =  $value['Account_Name'];
								$tableArr['Cheque_No'] =  $value['Cheque_No'];
								$tableArr['Description'] =  $value['Description'];
								if ($value['Amount'] < 0) {
									$tableArr['DR_Amt'] =  number_format(round( (float)$value['Amount'] * -1 ,2),2); 
									$tableArr['CR_Amt'] =  "";
									$tableArr['isTrash'] =  "0";
								} else {
									$tableArr['isTrash'] =  "1";
									$tableArr['DR_Amt'] =  "";
									$tableArr['CR_Amt'] =  number_format(round( (float)$value['Amount'] ,2),2); 
								}
								$ac_opening_bal += $value['Amount'];

								$tableArray[] = $tableArr;
							}
							
						}
						
							$tableArr['Bill_Date'] =  "";
							$tableArr['Order_No'] =  "";
							$tableArr['Account_Name'] =  "";
							$tableArr['Cheque_No'] =  "";
							$tableArr['Description'] =  "CL BAL";
							if ($ac_opening_bal > 0) {$tableArr['DR_Amt'] =  number_format(round( (float)$ac_opening_bal ,2),2); 
								$tableArr['CR_Amt'] =  "";
								$tableArr['isTrash'] =  "1";
							} else {
								$tableArr['isTrash'] =  "0";
								$tableArr['DR_Amt'] =  "";
								$tableArr['CR_Amt'] =  number_format(round( (float)$ac_opening_bal * -1 ,2),2); 
							}
							$tableArray[] = $tableArr;
						$result = $tableArray;
				}
				else if($dataArray['action'] == 'LedgerData' ) 
				{ 
						$tableArr =  $tableArray = array(); 
						$i = 1; 
						$tbl = "{$wpdb->prefix}trntbl";
						$ac_opening_bal = 0;
						if ((int) $dataArray['Account'] > 0) {
							$get_trn = $wpdb->get_results("select opening_balance,ID from {$wpdb->prefix}users where isTrash =0 AND ID ='{$dataArray['Account']}' ", 'ARRAY_A');
							if (count($get_trn) == 1) {
								foreach ($get_trn as $key => $value) {
									$ac_opening_bal += (float) $value['opening_balance'];
								}

							}
						}

						$get_trn = $wpdb->get_results("select sum(amount) as sumbal from {$tbl} where isTrash = 0 AND bill_date < '{$dataArray['fromDate']}' and  Account_Name = '{$dataArray['Account']}'", 'ARRAY_A');
						if (count($get_trn) == 1) {
							$ac_opening_bal += (float) $get_trn[0]['sumbal'];
						}
						
							//$tableArr['Sr'] =  $i ;
							$tableArr['isTrash'] =  0;
							$tableArr['Bill_Date'] =  "";
							$tableArr['Order_No'] =  "";
							//$tableArr['Bill_No'] =  "";
							$tableArr['Cheque_No'] =  "";
							$tableArr['Description'] =  "";
							$tableArr['Register'] =  "OPN BAL";
							if ($ac_opening_bal >= 0) {
								$tableArr['DR_Amt'] =  number_format(round( (float)$ac_opening_bal ,2),2);  
								$tableArr['CR_Amt'] =  "";
							} else {
								$tableArr['DR_Amt'] =  "";
								$tableArr['CR_Amt'] =  number_format(round( (float)$ac_opening_bal * -1 ,2),2);  
							}
							$tableArray[] = $tableArr;
							$i++;
						
						$tbl = "{$wpdb->prefix}trntbl";
						$atbl = "{$wpdb->prefix}users";
						$getData = $wpdb->get_results(" SELECT
													{$tbl}.ID,
													{$tbl}.Bill_No,
													 {$tbl}.Bill_Date, 
													{$tbl}.Cheque_No,
													{$tbl}.Order_No,
													{$tbl}.Description,
													{$tbl}.Amount,
													{$tbl}.Register,
													{$atbl}.Account_Name as Register_name
												FROM
													{$tbl}
												LEFT JOIN {$atbl}  ON ({$atbl}.ID = {$tbl}.Register)
												WHERE
													{$tbl}.isSys = 0 AND
													{$tbl}.Bill_Date >= '{$dataArray['fromDate']}' AND
													{$tbl}.Bill_Date <= '{$dataArray['toDate']}' AND
													{$tbl}.Account_Name='{$dataArray['Account']}'
												ORDER BY {$tbl}.Bill_Date,{$tbl}.ID", 'ARRAY_A');

						$totalCount = count($getData);
						if ($totalCount > 0) 
						{

							foreach ($getData as $key => $value) 
							{

							   $tableArr = array();
								//    $tableArr['Sr'] = $i ;
								
								$tableArr['Bill_Date'] =  $value['Bill_Date'];
								$tableArr['Order_No'] =  $value['Order_No'];
							   // $tableArr['Bill_No'] = $value['Bill_No'];
								$tableArr['Cheque_No'] =  $value['Cheque_No'];
								$tableArr['Description'] =  $value['Description'];
								$tableArr['Register'] =  $value['Register_name'];
								if ($value['Amount'] >= 0) {
									$tableArr['DR_Amt'] =  number_format(round( (float)$value['Amount'] ,2),2);    
									$tableArr['CR_Amt'] =  "";
									$tableArr['isTrash'] =  1;
								} else {
									$tableArr['DR_Amt'] =  "";
									$tableArr['CR_Amt'] = number_format(round( (float)$value['Amount'] * -1 ,2),2);    
									$tableArr['isTrash'] =  0;
								}
								$ac_opening_bal += $value['Amount'];
								$tableArray[] = $tableArr;
								$i++;
							}
							$tableArr = array();
							$tableArr['isTrash'] =  0;
							//    $tableArr['Sr'] =  $i ;
							$tableArr['Bill_Date'] =  "";
							//$tableArr['Order_No'] =  "";
							$tableArr['Order_No'] =  "";
							$tableArr['Cheque_No'] =  "";

							$tableArr['Description'] =  "";
							$tableArr['Register'] =  "CL BAL";
							if ($ac_opening_bal < 0) {
								$tableArr['DR_Amt'] =  number_format(round( (float)$ac_opening_bal * -1 ,2),2);  
								$tableArr['CR_Amt'] =  "";
							} else {
								$tableArr['DR_Amt'] =  "";
								$tableArr['CR_Amt'] =  number_format(round( (float)$ac_opening_bal ,2),2);   
							}
							$tableArray[] = $tableArr;
							$i++;
						}
						
						$result = $tableArray;
					
				}
				else if($dataArray['action'] == 'ExpiryReportData' ) 
				{ 
						global $systemAC;
						$tableArr =  $tableArray = array(); 
						$tbl = "{$wpdb->prefix}stktbl";
						$batchtbl = "{$wpdb->prefix}batchno";
						$ptbl = "{$wpdb->prefix}product";

						$qry = strlen($dataArray['Product']) > 0 ? " AND {$batchtbl}.Product_Name  = '{$dataArray['Product']}' " : "";

						$getData = $wpdb->get_results("SELECT
													{$ptbl}.Product_Name,
													{$batchtbl}.Batch_No,
													{$batchtbl}.Expiry_Date,
													{$batchtbl}.Product_MRP,
													{$batchtbl}.Opening_Stock,

													sum( if( {$tbl}.Register = '{$systemAC['SALE']}'  , Qty , 0 ) )  as Sale_QTY,
													sum( if( {$tbl}.Register = '{$systemAC['PURCHASE']}' , Qty ,0 )  )  as Purchase_QTY

										
												From {$batchtbl}
													LEFT JOIN {$ptbl} ON ( {$ptbl}.ID = {$batchtbl}.Product_Name )
													LEFT JOIN {$tbl} ON ( {$tbl}.Product = {$batchtbl}.Product_Name )
											WHERE
												{$batchtbl}.Expiry_Date <= '{$dataArray['fromDate']}'
												{$qry}
												GROUP BY
												{$ptbl}.Product_Name", 'ARRAY_A');

						$totalCount = count($getData);
						if ($totalCount > 0) {

							foreach ($getData as $key => $value) {
								$tableArr = array();
								$total = 0;
								$value['Expiry_Date'] = isset($value['Expiry_Date']) ? $value['Expiry_Date'] : '0000-00-00';
								$value['Opening_Stock'] = isset($value['Opening_Stock']) ? $value['Opening_Stock'] : '0';
								$value['Sale_QTY'] = isset($value['Sale_QTY']) ? $value['Sale_QTY'] : '0';
								$value['Purchase_QTY'] = isset($value['Purchase_QTY']) ? $value['Purchase_QTY'] : '0';
								$total = $value['Opening_Stock'] + $value['Sale_QTY'] + $value['Purchase_QTY'];
								$tableArr['isTrash'] =  0;
								$tableArr['Exp_Date'] =  $value['Expiry_Date'];
								$tableArr['Product_Name'] =  $value['Product_Name'];
								//$tableArr['Company'] = $value['Product_Company'] ;
								$tableArr['Batch_No'] =  $value['Batch_No'];
								$tableArr['MRP'] = number_format(round( (float)$value['Product_MRP'] ,2),2);   
								$tableArr['Opn_Stock'] =  $value['Opening_Stock'];
								$tableArr['Sale_QTY'] =  $value['Sale_QTY'];
								$tableArr['Purc_QTY'] = $value['Purchase_QTY'];
								$tableArr['CL_Stock'] = $total;
							
								$tableArray[] = $tableArr;
							}
							$result = $tableArray;
						}
						
						
					
				}
				else if($dataArray['action'] == 'GSTReportData' ) 
				{ 
					$tableArr =  $tableArray = array(); 
					$f_dt = strtotime(date("Y-m-d", strtotime($dataArray['fromDate'])) . " ,first day of this month");
                    $dataArray['From_Date'] = date("Y-m-d", $f_dt);
                    $t_dt = strtotime(date("Y-m-d", strtotime($dataArray['fromDate'])) . ", last day of this month");
                    $dataArray['To_Date'] = date("Y-m-d", $t_dt);
					
						if($dataArray['GST'] == 'GST-1')
						{
							$tbl = "{$wpdb->prefix}sale";
							$atbl = "{$wpdb->prefix}users";
							
							$taxableAmount = $sGSTAmt = $cGSTAmt = $iGSTAmt = $billAmount = 0;
							
							$getData = $wpdb->get_results("SELECT
																{$tbl}.Bill_Date,
																{$tbl}.Bill_No ,
																{$tbl}.jsonData ,
																{$tbl}.Total_Taxable_Amt ,
																{$tbl}.Total_GST_Amt ,
																{$tbl}.Bill_Amount ,
																{$atbl}.Account_Name,
																{$atbl}.State_Name ,
																{$atbl}.GST_No

															From {$tbl}

																INNER JOIN {$atbl}  ON ({$atbl}.ID = {$tbl}.Account_Name)

															WHERE
																{$tbl}.isSys = 0 AND
																{$tbl}.Bill_Date >= '{$dataArray['From_Date']}' AND
																{$tbl}.Bill_Date <= '{$dataArray['To_Date']}'
															ORDER BY {$tbl}.Bill_Date, {$tbl}.ID", 'ARRAY_A');

							if (count($getData) > 0) 
							{

								foreach ($getData as $key => $value) 
								{


									$desData = json_decode($value['jsonData'], true);
									$desArray = array();
									foreach ($desData as $k => $v) {
										if (preg_match("/Product_Name_/", $k) && strlen($v) > 0) {
											$thisId = explode("Product_Name_", $k);
											$thisId = $thisId[1];
											$igst = $sgst = 0;
											if ($companyState == $value['State_Name']) {
												$sgst = $desData["GST_{$thisId}"] / 2;
											} else {
												$igst = $desData["GST_{$thisId}"];
											}
											$gstPer = $desData["GST_Rate_{$thisId}"] * 100;
											if (isset($desArray[$gstPer])) {
												$desArray[$gstPer]['Taxable_Amt'] += $desData["Taxable_{$thisId}"];
												$desArray[$gstPer]['IGST_Amt'] += $igst;
												$desArray[$gstPer]['SGST_Amt'] += $sgst;
												$desArray[$gstPer]['Total'] += $desData["Total_{$thisId}"];

											} else {
												$desArray[$gstPer] = array("Taxable_Amt" => $desData["Taxable_{$thisId}"],
													"IGST_Amt" => $igst,
													"SGST_Amt" => $sgst,
													"Total" => $desData["Total_{$thisId}"],
												);
											}

										}
									}
									$ii = 0;
									if (count($desArray) > 0) {
										foreach ($desArray as $dk => $dv) {
											$tableArr = array();
											if ($ii == 0) {
												$tableArr['Service_Type'] = "G"; 
												$tableArr['isTrash'] = "0";
												$tableArr['Bill_Date'] = $value['Bill_Date'];
												$tableArr['Bill_No'] = $value['Bill_No'];
												$tableArr['Account_Name'] = $value['Account_Name'];
												$tableArr['GST_No'] = $value['GST_No'];
												$tableArr['State_Name'] = $stateNameList[$value['State_Name']];
												//$tableArr['Account_Name'] = $value['Account_Name'];
												$tableArr['GST(%)'] = ($dk / 100) . " %"; 
												$tableArr['Taxable_Amt'] = number_format(round( (float)$dv["Taxable_Amt"] ,2),2);  
												$tableArr['S-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2);  ;
												$tableArr['C-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2); 
												$tableArr['I-GST'] = number_format(round( (float)$dv["IGST_Amt"] ,2),2);  
												$tableArr['Bill_Amt'] = number_format(round( (float)$dv["Total"] ,2),2);  
												$ii++;
											} else {
												$tableArr['Service_Type'] = "";
												$tableArr['Bill_Date'] = "";
												$tableArr['isTrash'] = "0";
												$tableArr['Bill_No'] = "";
												$tableArr['Account_Name'] = "";
												$tableArr['GST_No'] = "";
												$tableArr['State_Name'] = "";
												$tableArr['Account_Name'] = "";
												$tableArr['GST(%)'] = ($dk / 100) . " %";
												$tableArr['Taxable_Amt'] = $dv["Taxable_Amt"];
												$tableArr['S-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2);  
												$tableArr['C-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2);  
												$tableArr['I-GST'] = number_format(round( (float)$dv["IGST_Amt"] ,2),2);  
												$tableArr['Bill_Amt'] = number_format(round( (float)$dv["Total"] ,2),2);  
												$i++;
												$ii++;
											}
											$taxableAmount += $dv["Taxable_Amt"];
											$sGSTAmt += $dv["SGST_Amt"];
											$cGSTAmt = $dv["SGST_Amt"];
											$iGSTAmt = $dv["IGST_Amt"];
											$billAmount += $dv["Total"];
											$tableArray[] = $tableArr;
										}
									}
								}
								$tableArr = array();
								$tableArr['Service_Type'] = "";
								$tableArr['isTrash'] = "0";
								$tableArr['Bill_Date'] = "";
								$tableArr['Bill_No'] = "";
								$tableArr['Account_Name'] = "";
								$tableArr['GST_No'] = "";
								$tableArr['State_Name'] = "";
								$tableArr['Account_Name'] = "";
								$tableArr['GST(%)'] = " ";
								$tableArr['Taxable_Amt'] = number_format(round( (float)$taxableAmount ,2),2); 
								$tableArr['S-GST'] = number_format(round( (float)$sGSTAmt ,2),2); 
								$tableArr['C-GST'] = number_format(round( (float)$cGSTAmt ,2),2); 
								$tableArr['I-GST'] = number_format(round( (float)$iGSTAmt ,2),2); 
								$tableArr['Bill_Amt'] = number_format(round( (float)$billAmount ,2),2);  
								$tableArray[] = $tableArr;
								$result = $tableArray;

								
							} 
						}else if($dataArray['GST'] == 'GST-2')
						{
							$tbl = "{$wpdb->prefix}purchase";
							$atbl = "{$wpdb->prefix}users";
							
							$taxableAmount = $sGSTAmt = $cGSTAmt = $iGSTAmt = $billAmount = 0;
							
							$getData = $wpdb->get_results("SELECT
                                                        {$tbl}.Bill_Date ,
                                                        {$tbl}.Order_No ,
                                                        {$tbl}.jsonData ,
                                                        {$tbl}.Invoice_No ,
                                                        {$tbl}.Total_Taxable_Amt ,
                                                        {$tbl}.Total_GST_Amt ,
                                                        {$tbl}.Bill_Amount ,
                                                        {$atbl}.Account_Name,
                                                        {$atbl}.State_Name,
                                                        {$atbl}.GST_No
                                                From {$tbl}

                                                        INNER JOIN {$atbl}  ON ({$atbl}.ID = {$tbl}.Account_Name)

                                                    WHERE
                                                        {$tbl}.isSys = 0 AND
                                                        {$tbl}.Bill_Date >= '{$dataArray['From_Date']}' AND
                                                        {$tbl}.Bill_Date <= '{$dataArray['To_Date']}'
                                                    ORDER BY {$tbl}.Bill_Date, {$tbl}.ID", 'ARRAY_A');

							if (count($getData) > 0) 
							{

								foreach ($getData as $key => $value) 
								{


									$desData = json_decode($value['jsonData'], true);
									$desArray = array();
									foreach ($desData as $k => $v) {
										if (preg_match("/Product_Name_/", $k) && strlen($v) > 0) {
											$thisId = explode("Product_Name_", $k);
											$thisId = $thisId[1];
											$igst = $sgst = 0;
											if ($companyState == $value['State_Name']) {
												$sgst = $desData["GST_{$thisId}"] / 2;
											} else {
												$igst = $desData["GST_{$thisId}"];
											}
											$gstPer = $desData["GST_Rate_{$thisId}"] * 100;
											if (isset($desArray[$gstPer])) {
												$desArray[$gstPer]['Taxable_Amt'] += $desData["Taxable_{$thisId}"];
												$desArray[$gstPer]['IGST_Amt'] += $igst;
												$desArray[$gstPer]['SGST_Amt'] += $sgst;
												$desArray[$gstPer]['Total'] += $desData["Total_{$thisId}"];

											} else {
												$desArray[$gstPer] = array("Taxable_Amt" => $desData["Taxable_{$thisId}"],
													"IGST_Amt" => $igst,
													"SGST_Amt" => $sgst,
													"Total" => $desData["Total_{$thisId}"],
												);
											}

										}
									}
									$ii = 0;
									if (count($desArray) > 0) {
										foreach ($desArray as $dk => $dv) {
											$tableArr = array();
											if ($ii == 0) {
												$tableArr['Service_Type'] = "G";
												$tableArr['isTrash'] = "0";
												$tableArr['Bill_Date'] = $value['Bill_Date'];
												$tableArr['Bill_No'] = $value['Invoice_No'];
												$tableArr['Account_Name'] = $value['Account_Name'];
												$tableArr['GST_No'] = $value['GST_No'];
												$tableArr['State_Name'] = $stateNameList[$value['State_Name']];
												//$tableArr['Account_Name'] = $value['Account_Name'];
												$tableArr['GST(%)'] = ($dk / 100) . " %"; 
												$tableArr['Taxable_Amt'] = number_format(round( (float)$dv["Taxable_Amt"] ,2),2);  
												$tableArr['S-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2);  ;
												$tableArr['C-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2); 
												$tableArr['I-GST'] = number_format(round( (float)$dv["IGST_Amt"] ,2),2);  
												$tableArr['Bill_Amt'] = number_format(round( (float)$dv["Total"] ,2),2);  
												$ii++;
											} else {
												$tableArr['Service_Type'] = "";
												$tableArr['isTrash'] = "0";
												$tableArr['Bill_Date'] = "";
												$tableArr['Bill_No'] = "";
												$tableArr['Account_Name'] = "";
												$tableArr['GST_No'] = "";
												$tableArr['State_Name'] = "";
												$tableArr['GST(%)'] = ($dk / 100) . " %";
												$tableArr['Taxable_Amt'] = $dv["Taxable_Amt"];
												$tableArr['S-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2);  
												$tableArr['C-GST'] = number_format(round( (float)$dv["SGST_Amt"] ,2),2);  
												$tableArr['I-GST'] = number_format(round( (float)$dv["IGST_Amt"] ,2),2);  
												$tableArr['Bill_Amt'] = number_format(round( (float)$dv["Total"] ,2),2);  
												$i++;
												$ii++;
											}
											$taxableAmount += $dv["Taxable_Amt"];
											$sGSTAmt += $dv["SGST_Amt"];
											$cGSTAmt = $dv["SGST_Amt"];
											$iGSTAmt = $dv["IGST_Amt"];
											$billAmount += $dv["Total"];
											$tableArray[] = $tableArr;
										}
									}
								}
								$tableArr = array();
								$tableArr['Service_Type'] = "";
								$tableArr['isTrash'] = "0";
								$tableArr['Bill_Date'] = "";
								$tableArr['Account_Name'] = "";
								$tableArr['Bill_No'] = "";
								$tableArr['GST_No'] = "";
								$tableArr['State_Name'] = "";
								$tableArr['GST(%)'] = " ";
								$tableArr['Taxable_Amt'] = number_format(round( (float)$taxableAmount ,2),2); 
								$tableArr['S-GST'] = number_format(round( (float)$sGSTAmt ,2),2); 
								$tableArr['C-GST'] = number_format(round( (float)$cGSTAmt ,2),2); 
								$tableArr['I-GST'] = number_format(round( (float)$iGSTAmt ,2),2); 
								$tableArr['Bill_Amt'] = number_format(round( (float)$billAmount ,2),2);  
								$tableArray[] = $tableArr;
								$result = $tableArray;

								
							} 
						}
						else if($dataArray['GST'] == 'GST-3')
						{
								$tableArray =  $tableArr = array();
								$taxableAmount = $gstAmount = $billAmount = 0;
								$tbl = "{$wpdb->prefix}sale";
								$getData = $wpdb->get_results("SELECT
																	Bill_Date,
																	sum( Total_Taxable_Amt ) as Total_Taxable_Amt ,
																	sum( Total_GST_Amt ) as Total_GST_Amt ,
																	sum( Bill_Amount ) as  Bill_Amount
																From {$tbl}
																WHERE
																	isTrash = 0 AND
																	Bill_Date >= '{$dataArray['From_Date']}' AND
																	Bill_Date <= '{$dataArray['To_Date']}' limit 1 ", 'ARRAY_A');
								//print_r($getData);
								foreach ($getData as $key => $val) {
									$tableArr['Month'] =  date_format(date_create($val['Bill_Date']), 'M Y');
									$tableArr['Service_Type'] =  "SALE";
									$tableArr['isTrash'] = "0";
									$tableArr['Taxable_Amt'] =   number_format(round( (float)$val["Total_Taxable_Amt"] ,2),2); 
									$tableArr['GST'] =   number_format(round( (float)$val["Total_GST_Amt"]  ,2),2);  
									$tableArr['Bill_Amt'] =   number_format(round( (float)$val["Bill_Amount"]  ,2),2);  
									$tableArray[] = $tableArr;
									$taxableAmount += (float)$val["Total_Taxable_Amt"] ;
									$gstAmount += (float)$val["Total_GST_Amt"] ;
									$billAmount += (float)$val["Bill_Amount"] ;
								}
								$tbl = "{$wpdb->prefix}purchase";
								$getData = $wpdb->get_results("SELECT
																	Bill_Date,
																	sum( Total_Taxable_Amt ) as Total_Taxable_Amt ,
																	sum( Total_GST_Amt ) as Total_GST_Amt ,
																	sum( Bill_Amount ) as  Bill_Amount
																From {$tbl}
																WHERE
																	isTrash = 0 AND
																	Bill_Date >= '{$dataArray['From_Date']}' AND
																	Bill_Date <= '{$dataArray['To_Date']}' limit 1 ", 'ARRAY_A');
								foreach ($getData as $key => $val) {
									$tableArr['Month'] =  date_format(date_create($val['Bill_Date']), 'M Y');
									$tableArr['isTrash'] = "0";
									$tableArr['Service_Type'] =  "PURCHASE";
									$tableArr['Taxable_Amt'] =  number_format(round( (float)$val["Total_Taxable_Amt"] * -1 ,2),2); 
									$tableArr['GST'] =  number_format(round( (float)$val["Total_GST_Amt"] * -1 ,2),2);  
									$tableArr['Bill_Amt'] =  number_format(round( (float)$val["Bill_Amount"] * -1 ,2),2); 
									$tableArray[] = $tableArr;
									$taxableAmount += (float)$val["Total_Taxable_Amt"] * -1 ;
									$gstAmount += (float)$val["Total_GST_Amt"] * -1;
									$billAmount += (float)$val["Bill_Amount"] * -1 ;
								}
								$tableArr['Month'] =  "";
								$tableArr['isTrash'] = "0";
								$tableArr['Service_Type'] =  "";
								$tableArr['Taxable_Amt'] =  number_format(round( (float)$taxableAmount ,2),2); 
								$tableArr['GST'] =  number_format(round( (float)$gstAmount,2),2);  
								$tableArr['Bill_Amt'] =  number_format(round( (float)$billAmount ,2),2); 
								$tableArray[] = $tableArr;
								$result = $tableArray;
						}
				}
				else if($dataArray['action'] == 'StockReportData' ) 
				{ 
					$tbl = "{$wpdb->prefix}stktbl";
					$atbl = "{$wpdb->prefix}users";
					$prtbl = "{$wpdb->prefix}product";
					$typetbl = "{$wpdb->prefix}producttype";
					$bntbl = "{$wpdb->prefix}batchno";	
					$qry = strlen($dataArray['Product']) > 0 ? " AND {$tbl}.Product = '{$dataArray['Product']}' " : "";
					$tableArr = $tableArray = array();
					if( $dataArray['Detail'] == 'No Detail' )
					{
						
						$getData = $wpdb->get_results("SELECT 
                                                    {$tbl}.Bill_Date ,
                                                    {$tbl}.Order_No,
                                                    {$tbl}.Register,
                                                    {$tbl}.Qty,
                                                    {$tbl}.Rate,
                                                    {$prtbl}.Product_Name,
                                                    {$prtbl}.Manufacturer,
													{$prtbl}.Ganeric_Name,
													{$prtbl}.Drug_Name,
													{$prtbl}.Remarks,
                                                    {$atbl}.Account_Name,
                                                    {$bntbl}.Batch_No,
                                                    {$bntbl}.Product_MRP,
                                                    {$bntbl}.Expiry_Date
                                                From {$tbl}
                                                    LEFT JOIN {$atbl}  ON ({$atbl}.ID = {$tbl}.Account_Name)
                                                    LEFT JOIN {$bntbl} ON ({$bntbl}.ID = {$tbl}.Batch_No)
                                                    LEFT JOIN {$prtbl} ON ({$prtbl}.ID = {$bntbl}.Product_Name)
                                            WHERE
                                                {$tbl}.isSys = 0 AND
                                                {$tbl}.Bill_Date >= '{$dataArray['fromDate']}' AND
                                                {$tbl}.Bill_Date <= '{$dataArray['toDate']}'
                                                {$qry}
                                                ORDER BY
                                                    {$prtbl}.Product_Name, {$tbl}.ID", 'ARRAY_A');
						if (count($getData) > 0) 
						{

							foreach ($getData as $key => $value) {

								$registerKey = array_search($value['Register'], $systemAC);

								
								$tableArr['Product_Name'] = $value['Product_Name'] . ' - ' . $value['Manufacturer'];
								$tableArr['Batch_No'] = $value['Batch_No'];
								$tableArr['Ganeric_Name'] = $value['Ganeric_Name'];
								$tableArr['Drug_Name'] = $value['Drug_Name'];
								$tableArr['Remarks'] = $value['Remarks'];
								$tableArr['Exp_Date'] = $value['Expiry_Date'];
								$tableArr['MRP'] = number_format(round( (float)$value['Product_MRP'] ,2),2);  
								$tableArr['Rate'] = number_format(round( (float)$value['Rate'] ,2),2); 
								$tableArr['QTY'] = $value['Qty'];
								$tableArr['Register'] = $registerKey;
								$tableArr['Bill_Date'] = $value['Bill_Date'];
								$tableArr['Order_No'] = $value['Order_No'];
								$tableArr['Account_Name'] = $value['Account_Name'];
								$tableArray[] = $tableArr;
							}
							$result = $tableArray;
						}
					}else{
						//return $dataArray;
							global $systemAC;
							$tableArray = array();
							$qry1 = strlen($dataArray['Product']) > 0 ? " AND {$bntbl}.Product_Name = '{$dataArray['Product']}' " : "";	
							$tblData = $wpdb->get_results("select 
                                                {$bntbl}.Product_Name AS productID,
                                                {$bntbl}.Batch_No,
                                                {$bntbl}.Opening_Stock,
                                                {$bntbl}.Product_MRP,
												{$bntbl}.Purchase_Rate,
												{$bntbl}.Unit_Per_Sheet,
                                                DATE_FORMAT( {$bntbl}.Expiry_Date ,'%d-%m-%Y' ) as Expiry_Date,
												{$prtbl}.Ganeric_Name,
												{$prtbl}.Drug_Name,
												{$prtbl}.Remarks,
												{$prtbl}.Product_Name,
                                                {$prtbl}.Manufacturer,
												{$typetbl}.GST_Charge
                                            from {$bntbl} 
											 LEFT JOIN {$prtbl} ON ({$prtbl}.ID = {$bntbl}.Product_Name)
											LEFT JOIN {$typetbl} ON ({$typetbl}.ID = {$prtbl}.Product_Type)											 
                                            WHERE {$bntbl}.isSys=0 {$qry1} order by {$bntbl}.Product_Name", 'ARRAY_A');
            $batchData = array();
            foreach ($tblData as $k => $v) {
                if (isset($batchData[$v['productID']]['OpenStock'])) {
                    $batchData[$v['productID']]['OpenStock'] += (float)$v['Opening_Stock'];
					$batchData[$v['productID']]['OpenVal'] += ((float)$v['Opening_Stock'] * (float) $v['Purchase_Rate'] / (float)$v['Unit_Per_Sheet'] ) + ( (float)$v['Opening_Stock'] * (float) $v['Purchase_Rate'] * (float) $v['GST_Charge'] / ( (float)$v['Unit_Per_Sheet'] * 100 ) );
                } else {
                    $batchData[$v['productID']]['OpenStock'] = (float)$v['Opening_Stock'];
					$batchData[$v['productID']]['OpenVal'] = ((float)$v['Opening_Stock'] * (float) $v['Purchase_Rate'] / (float)$v['Unit_Per_Sheet'] ) + ( (float)$v['Opening_Stock'] * (float) $v['Purchase_Rate'] * (float) $v['GST_Charge'] / ( (float)$v['Unit_Per_Sheet'] * 100 ) );
					
                }
				
				$batchData[$v['productID']]['Ganeric_Name'] = $v['Ganeric_Name'];
				$batchData[$v['productID']]['Drug_Name'] = $v['Drug_Name'];
				$batchData[$v['productID']]['Remarks'] = $v['Remarks'];
				$batchData[$v['productID']]['Product_Name'] = $v['Product_Name'];
				$batchData[$v['productID']]['Manufacturer'] = $v['Manufacturer'];
            }

			  $purchaseData = array();
               $purchaseQry = $wpdb->get_results("SELECT Product,QTY,Rate,Total From {$tbl} WHERE isTrash = 0 AND Register = '{$systemAC["PURCHASE"]}'  {$qry} order by Bill_Date DESC","ARRAY_A");
				foreach( $purchaseQry as $key => $val )
				{
					$purchaseData[$val['Product']][] = $val;
				}
		
			   $getData = $wpdb->get_results("SELECT
                                                    {$tbl}.Product,
                                                    
                                                    sum( if( {$tbl}.Register = '{$systemAC["SALE"]}' && {$tbl}.Bill_Date < '{$dataArray['From_Date']}' , {$tbl}.Qty , 0 ) )  as Opening_Sale_QTY,
                                                    sum( if( {$tbl}.Register = '{$systemAC["PURCHASE"]}' && {$tbl}.Bill_Date < '{$dataArray['From_Date']}' , {$tbl}.Qty ,0 )  )  as Opening_Purchase_QTY,
                                                    sum( if( {$tbl}.Register = '{$systemAC["SALE"]}' && {$tbl}.Bill_Date >= '{$dataArray['From_Date']}' && {$tbl}.Bill_Date <= '{$dataArray['To_Date']}' , {$tbl}.Qty , 0 ) )  as Sale_QTY,
                                                    sum( if( {$tbl}.Register = '{$systemAC["PURCHASE"]}' && {$tbl}.Bill_Date >= '{$dataArray['From_Date']}' && {$tbl}.Bill_Date <= '{$dataArray['To_Date']}' , {$tbl}.Qty ,0 )  )  as Purchase_QTY

                                                From {$tbl}

                                            WHERE
                                                {$tbl}.isSys =0
                                                {$qry}
                                                Group BY
                                                    {$tbl}.Product", 'ARRAY_A');
                $totalCount = count($getData);
                if ($totalCount > 0) {

                    foreach ($getData as $key => $value) {
                       
						$stockData[$value['Product']]['Opening_Sale_QTY'] = $value['Opening_Sale_QTY'];
						$stockData[$value['Product']]['Opening_Purchase_QTY'] = $value['Opening_Purchase_QTY'];
						$stockData[$value['Product']]['Sale_QTY'] = $value['Sale_QTY'];
						$stockData[$value['Product']]['Purchase_QTY'] = $value['Purchase_QTY'];
					}
					
				}
				//return $stockData;
				foreach( $batchData as $key => $value ) 
				{
					$purchaseData[$key][] = array('QTY' =>$value['OpenStock'], 'Total' => $value['OpenVal']  );
					$stockData[$key]['Opening_Sale_QTY'] = isset($stockData[$key]['Opening_Sale_QTY']) ? (float)$stockData[$key]['Opening_Sale_QTY'] * -1 : '0';
					$stockData[$key]['Opening_Purchase_QTY'] = isset($stockData[$key]['Opening_Purchase_QTY']) ? (float)$stockData[$key]['Opening_Purchase_QTY'] : '0';
					$stockData[$key]['Sale_QTY'] = isset($stockData[$key]['Sale_QTY']) ? (float)$stockData[$key]['Sale_QTY'] * -1 : '0';
					$stockData[$key]['Purchase_QTY'] = isset($stockData[$key]['Purchase_QTY']) ? (float)$stockData[$key]['Purchase_QTY'] : '0';
					
					
					$tableArr['Product_Name'] = $value['Product_Name'] . ' - ' . $value['Manufacturer'];
					//$tableArr[$i]['Ganeric_Name'] = array( "value" => $value['Ganeric_Name'] , "type" => "text"  );
					//$tableArr[$i]['Drug_Name'] = array( "value" => $value['Drug_Name'] , "type" => "date"  );
					//$tableArr[$i]['Remarks'] = array( "value" => $value['Remarks'] , "type" => "text"  );
				   
					$tableArr['Opn_Stock'] = $value['OpenStock'];
					$tableArr['Opn_Val'] =  $value['OpenVal'];
					$tableArr['Opn_Sale_QTY'] = $stockData[$key]['Opening_Sale_QTY'];
					$tableArr['Opn_Purc_QTY'] =$stockData[$key]['Opening_Purchase_QTY'];
					$tableArr['Sale_QTY'] =  $stockData[$key]['Sale_QTY'];
					$tableArr['Purc_QTY'] = $stockData[$key]['Purchase_QTY'];
					
					$total = (float)$value['OpenStock'] - 
							 (float)$stockData[$key]['Opening_Sale_QTY'] + 
							 (float)$stockData[$key]['Opening_Purchase_QTY'] - 
							 (float)$stockData[$key]['Sale_QTY'] + 
							 (float)$stockData[$key]['Purchase_QTY'];
					
					$tableArr['CL_Stock'] = $total;
					
					$thisQty = $total ;
					$thisVal = 0; 
					$openingSale = $stockData[$key]['Opening_Sale_QTY']   + $stockData[$key]['Sale_QTY'] ;
					
					if($thisQty > 0 && isset($purchaseData[$key]))
					{
						foreach ( $purchaseData[$key] as $kk => $vv )
						{
							if( $thisQty > 0 )
							{	//echo $thisQty."<br>";
								if( $vv['QTY'] <= $thisQty )
								{
									$thisQty -= $vv['QTY'];
									$thisVal += $vv['Total'];

								}
								else{
									
									$thisVal += $thisQty * $vv['Total']/$vv['QTY'];
									$thisQty = 0;
								}
							}
							
						}
					}
					$tableArr['CL_Val'] = $thisVal;
					$tableArray[] = $tableArr;
					$i++;
                 }
				 $result = $tableArray;
							/*
							 $tblData = $wpdb->get_results("select 
													{$bntbl}.Product_Name AS productID,
													{$bntbl}.Batch_No,
													{$bntbl}.Opening_Stock,
													{$bntbl}.Product_MRP,
													DATE_FORMAT( {$bntbl}.Expiry_Date ,'%d-%m-%Y' ) as Expiry_Date,
													{$prtbl}.Ganeric_Name,
													{$prtbl}.Drug_Name,
													{$prtbl}.Remarks,
													{$prtbl}.Product_Name,
													 {$prtbl}.Manufacturer
												from {$bntbl} 
												 LEFT JOIN {$prtbl} ON ({$prtbl}.ID = {$bntbl}.Product_Name) 
												WHERE {$bntbl}.isSys=0 order by {$bntbl}.Product_Name", 'ARRAY_A');
							$batchData = array();
							foreach ($tblData as $k => $v) {
								if (isset($batchData[$v['productID']]['OpenStock'])) {
									$batchData[$v['productID']]['OpenStock'] += $v['Opening_Stock'];
								} else {
									$batchData[$v['productID']]['OpenStock'] = $v['Opening_Stock'];
								}
								
								$batchData[$v['productID']]['Ganeric_Name'] = $v['Ganeric_Name'];
								$batchData[$v['productID']]['Drug_Name'] = $v['Drug_Name'];
								$batchData[$v['productID']]['Remarks'] = $v['Remarks'];
								$batchData[$v['productID']]['Product_Name'] = $v['Product_Name'];
								$batchData[$v['productID']]['Manufacturer'] = $v['Manufacturer'];
							}
							//print_r($batchData);
							$stockData = array();

						   
							$getData = $wpdb->get_results("SELECT
                                                    {$tbl}.Product,
                                                    
                                                    sum( if( {$tbl}.Register = '{$systemAC["SALE"]}' && {$tbl}.Bill_Date < '{$dataArray['From_Date']}' , {$tbl}.Qty , 0 ) )  as Opening_Sale_QTY,
                                                    sum( if( {$tbl}.Register = '{$systemAC["PURCHASE"]}' && {$tbl}.Bill_Date < '{$dataArray['From_Date']}' , {$tbl}.Qty ,0 )  )  as Opening_Purchase_QTY,
                                                    sum( if( {$tbl}.Register = '{$systemAC["SALE"]}' && {$tbl}.Bill_Date >= '{$dataArray['From_Date']}' && {$tbl}.Bill_Date <= '{$dataArray['To_Date']}' , {$tbl}.Qty , 0 ) )  as Sale_QTY,
                                                    sum( if( {$tbl}.Register = '{$systemAC["PURCHASE"]}' && {$tbl}.Bill_Date >= '{$dataArray['From_Date']}' && {$tbl}.Bill_Date <= '{$dataArray['To_Date']}' , {$tbl}.Qty ,0 )  )  as Purchase_QTY

                                                From {$tbl}

                                            WHERE
                                                {$tbl}.isSys =0
                                                {$qry}
                                                Group BY
                                                    {$tbl}.Product", 'ARRAY_A');

							$totalCount = count($getData);
							if ($totalCount > 0) {

								foreach ($getData as $key => $value) {
								   
									$stockData[$value['Product']]['Opening_Sale_QTY'] = $value['Purchase_QTY'];
									$stockData[$value['Product']]['Opening_Purchase_QTY'] = $value['Purchase_QTY'];
									$stockData[$value['Product']]['Sale_QTY'] = $value['Purchase_QTY'];
									$stockData[$value['Product']]['Purchase_QTY'] = $value['Purchase_QTY'];
								}
								
							}
							
							foreach( $batchData as $key => $value )
							{
								$tableArr = array();
								$stockData[$key]['Opening_Sale_QTY'] = isset($stockData[$key]['Opening_Sale_QTY']) ? (float)$stockData[$key]['Opening_Sale_QTY'] * -1 : '0';
								$stockData[$key]['Opening_Purchase_QTY'] = isset($stockData[$key]['Opening_Purchase_QTY']) ? (float)$stockData[$key]['Opening_Purchase_QTY'] : '0';
								$stockData[$key]['Sale_QTY'] = isset($stockData[$key]['Sale_QTY']) ? (float)$stockData[$key]['Sale_QTY'] * -1 : '0';
								$stockData[$key]['Purchase_QTY'] = isset($stockData[$key]['Purchase_QTY']) ? (float)$stockData[$key]['Purchase_QTY'] : '0';
									
									
								$tableArr['Product_Name'] = $value['Product_Name'] . ' - ' . $value['Manufacturer'];
								$tableArr['Ganeric_Name'] =  $value['Ganeric_Name'] ;
								$tableArr['Drug_Name'] =  $value['Drug_Name'] ;
								$tableArr['Remarks'] =  $value['Remarks'] ;
							   
								$tableArr['Opn_Stock'] = $value['OpenStock'];
								$tableArr['Opn_Sale_QTY'] = $stockData[$key]['Opening_Sale_QTY'];
								$tableArr['Opn_Purc_QTY'] = $stockData[$key]['Opening_Purchase_QTY'];
								$tableArr['Sale_QTY'] = $stockData[$key]['Sale_QTY'];
								$tableArr['Purc_QTY'] = $stockData[$key]['Purchase_QTY'];
								
								$total = (float)$value['OpenStock'] + 
										 (float)$stockData[$key]['Opening_Sale_QTY'] + 
										 (float)$stockData[$key]['Opening_Purchase_QTY'] + 
										 (float)$stockData[$key]['Sale_QTY']+ 
										 (float)$stockData[$key]['Purchase_QTY'];
								
								$tableArr['CL_Stock'] = $total;

								$tableArray[] = $tableArr;
							 }
							 $result = $tableArray;
						*/
					}
				}
				
				if( is_array( $result ) )
				{
					if( count ($result) > 0 )
					{
						$returnData['status'] = 1;
						$returnData['message'] = $result;
					}
					else
					{
						$returnData['status'] = 0;
						$returnData['message'] = 'No Data Found';
					}
				}
				else{
					if( strlen( $result ) > 2 )
					{
						$returnData['status'] = 1;
						$returnData['message'] = $result;
					}
					else
					{
					$returnData['status'] = 0;
					$returnData['message'] = "No Data Found";
					}
				}
						
						
			}
		}
		else
		{
			$returnData['status'] = 0;
			$returnData['message'] = "Access denied";
		}
		
		$res = new WP_REST_Response($returnData);
        $res->set_status(200); 
		return $res;
		
	}
	public function actionData(WP_REST_Request $request)
	{
		global $wpdb;
		global $wpRestReq;
		global $wpRestAfter;
		global $wpRestBefore;
		global $wpRestUnique;
		global $dataArray;
		
		$classDate = new classDate();
		$returnData = array();		
		if( $_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$jwt =  $request->get_header('Authorization'); 
			$isContinue = true;

			if($jwt != '')
			{
				try{
					$decoded_data = JWT::decode($jwt,"owt125",array("HS256"));
					$userCheck =  array();
					$user_check['ID'] = $decoded_data->data->user_id;
					/*
					$user_check['jwt_key'] = $jwt;
					if( !validate_jwt($user_check) )
					{
						$returnData['status'] = 0;
						$returnData['message'] = "Chating huh";
						$isContinue = false;
					}*/
				}
				catch(Exception $ex)
				{ 
					$returnData['status'] = 0;
					$returnData['message'] = $ex->getMessage();
					$isContinue = false;
				}  
			}
			else{
				$returnData['status'] = 0;
				$returnData['message'] = "Authorization failed";
				$isContinue = false;
			} 
			
			if( $isContinue ) 
			{
				$dataArray = $_POST;
				$dataArray['userId'] = $user_check['ID'];
				if( isset( $dataArray['actionName'] ) )
				{
					
					$tblName = $dataArray['actionName'];
					
					$tableName = $wpdb->prefix.$dataArray['actionName'];
					if($tblName == 'account' || $tblName == 'employee' || $tblName == 'patient')
					{
						$tableName = "{$wpdb->prefix}users";
					}
					if( $tblName == 'orderbillno' )
					{
						if( isset( $dataArray['Bill_Date'] ) )
						{
							$thisDate = $classDate->getFincialDate($dataArray['Bill_Date']);
							$dataArray['Bill_Date'] = $thisDate['firstDate'];
						}
					}
					unset( $dataArray['actionName'] );
				}
				
				
				
				if( isset($dataArray['jsonData']) ) 
					$dataArray['jsonData'] = json_encode( $dataArray['jsonData'] );
				
				//return $dataArray;
				
				if( $dataArray['action'] == 'UPDATE' || $dataArray['action'] == 'ADD' )
				{
					if( isset( $wpRestReq[$tblName] ) )
					{
						$reqData = requiredField($wpRestReq[$tblName],$dataArray);
						$isContinue = $reqData[0];
						if(!$isContinue)
						{
							$returnData['status'] = 1;
							$returnData['message'] = $reqData[1];
						}
						
					}
					
				}
				
				$thisAction = $dataArray['action'];
				unset( $dataArray['action'] );
				
				if( $isContinue )
				{
					if( $thisAction == 'ADD' )
					{
						
						if( isset ( $dataArray['Order_No'] ))
						{
							//return $dataArray; 
							$dataArray['Order_No'] = getOrderNo($dataArray,"OrderNo"); 
						
							if( (int)$dataArray['Order_No'] < 1 )
							{
								$returnData['status'] = 0;
								$returnData['message'] = "Failed to get order no";
								$isContinue = false;
							} 
						}
						if($isContinue)
						{						
							if( isset ( $dataArray['Bill_No'] ))
							{
								$dataArray['Bill_No'] = getOrderNo($dataArray,"BillNo");
								if( (int)$dataArray['Bill_No'] < 1 )
								{
									$returnData['status'] = 0;
									$returnData['message'] = "Failed to get bill no";
									$isContinue = false;
								}
							}
							if($isContinue)
							{
								
								$dataArray['added'] = date('Y-m-d h:i:s');
								$uniqueQuery = '';
								if( isset( $wpRestUnique[$tblName] ) )
								{
								 $uniqueQuery = setInsertUniqueQuery($tblName,$dataArray);
								}
								$noofrows = strlen( $uniqueQuery ) > 0 ? $wpdb->get_var("select COUNT(*) from {$tableName} where  {$uniqueQuery} ") : 0 ; 
								if( $noofrows > 0)  
								{
									$returnData['status'] = 0;
									$returnData['message'] = "adding duplicate entry";
								} 
								else 
								{
									
									if( isset( $wpRestBefore[$tblName] ) )
									{
										$reqData = $wpRestBefore[$tblName][0]($thisAction);
										
										if( $wpRestBefore[$tblName][1]) 
										{
											$isContinue = $reqData[0];
											if(!$isContinue )
											{
												$returnData['status'] = 0;
												$returnData['message'] = $reqData[1];
											}
										}
										
									}
									
									if( $isContinue ) 
									{
										$result = $wpdb->insert(
																$tableName,
																$dataArray
															   );
										if($result)
										{	
											$dataArray['ID'] = $wpdb->insert_id;
											//$dataArray['insertedID'] = $result; 
											
											if( isset( $wpRestAfter[$tblName] ) )
											{
												
												$thisRest = $wpRestAfter[$tblName][0]($tblName,$thisAction);
												if($thisRest[0]) 
												{
													$returnData['status'] = 1;
													$returnData['message'] = "has been successfully added.. all Transcation Added";
												}else{
													$returnData['status'] = 1;
													$returnData['message'] = "has been successfully added.. {$thisRest[1]}";
												}
											
											}
											else
												{
													$returnData['status'] = 1;
													$returnData['message'] = "has been successfully added";
														
												}
										}else{
											$returnData['status'] = 0;
											$returnData['message'] = "Failed to add";
										}
									}
								}
										
							}
						}
						
					}

					if( $thisAction == 'UPDATE' )
					{
						if( !isset ( $dataArray['ID'] ))
						{
							
							if( (int)$dataArray['ID'] < 1  )
							{
								$returnData['status'] = 0;
								$returnData['message'] = "Invalid ID";
								$isContinue = false;
							}
						}
						if($isContinue)
						{ 
							if( isset ( $dataArray['Order_No'] ))
							{
								if( (int)$dataArray['Order_No'] < 1  )
								{
									$returnData['status'] = 0;
									$returnData['message'] = "Invalid Order No";
									$isContinue = false;
									
								}
							}
							if($isContinue)
							{
								if( isset ( $dataArray['Bill_No'] ))
								{
									if( (int)$dataArray['Bill_No'] < 1  ) 
									{
										$returnData['status'] = 0;
										$returnData['message'] = "Invalid Bill No";
										$isContinue = false;
									}
								}
								if($isContinue)
								{
									
									
									$dataArray['updated'] = date('Y-m-d h:i:s');
									$tableID = $dataArray['ID'];
									//unset($dataArray['ID']);
									$uniqueQuery = '';
									
									
									if( isset( $wpRestUnique[$tblName] ) )
									{
										$uniqueQuery = setInsertUniqueQuery($tblName,$dataArray);
									}
									
									$noofrows = strlen( $uniqueQuery ) > 0 ? $wpdb->get_var("select COUNT(*) from {$tableName} where ID!='{$tableID}'  AND {$uniqueQuery} ") : 0 ; 
									if( $noofrows > 0)  
									{
										$returnData['status'] = 0;
										$returnData['message'] = "updating duplicate entry";
									}
									else 
									{
										if( isset( $wpRestBefore[$tblName] ) )
										{
											$reqData = $wpRestBefore[$tblName][0]($thisAction);
											
											if( $wpRestBefore[$tblName][1]) 
											{
												$isContinue = $reqData[0];
												if(!$isContinue )
												{
													$returnData['status'] = 0;
													$returnData['message'] = $reqData[1];
												}
											}
											
										}
										if( $isContinue )
										{
									
											$result = $wpdb->update(
																	$tableName,
																	$dataArray,
																	array("ID"=>$tableID)
																	);
											
											
											if($result)
											{
												if( isset( $wpRestAfter[$tblName] ) )
												{
													
													$thisRest = $wpRestAfter[$tblName][0]($tblName,$thisAction);
													
													if($thisRest[0])  
													{
														$returnData['status'] = 1;
														$returnData['message'] = "has been successfully updated.. all Transcation Added";
													}else{
														$returnData['status'] = 1;
														$returnData['message'] = "has been successfully updated.. {$thisRest[1]}";
													}
													
												}
												else
												{
													$returnData['status'] = 1;
													$returnData['message'] = "has been successfully updated";
														
												} 
											}else{
												$returnData['status'] = 0; 
												$returnData['message'] = "Failed to update";
											}
										}
											
										
									}
									
								}
							}
						}
						
					}
					if( $thisAction == 'TRASH'   )
					{
						//$dataArray['trashed'] = date('Y-m-d h:i:s');
						if( isset ( $dataArray['ID'] ))
						{
							if( (int)$dataArray['ID'] < 1  ) 
							{
								$returnData['status'] = 0;
								$returnData['message'] = "Invalid ID";
							}else{
								$result = $wpdb->update( 
												 $tableName,
												 array("isTrash" => 1, ),
												 array("ID" => $dataArray['ID'])
											   );
								if($result)
								{
									if( isset( $wpRestAfter[$tblName] ) )
									{
										
										$thisRest = $wpRestAfter[$tblName][0]($tblName,$thisAction);
										if($thisRest[0])
										{
											$returnData['status'] = 0;
											$returnData['message'] = "has been successfully trashed.. all Transcation Added";
										}else{
											$returnData['status'] = 0;
											$returnData['message'] = "has been successfully trashed.. {$thisRest[1]}";
										}
										
									}
									else
									{
										$returnData['status'] = 1;
										$returnData['message'] = "has been successfully trashed";
											
									} 
								}else{
									$returnData['status'] = 0;
									$returnData['message'] = "Failed to trash";
								}
							}
						}else{
									$returnData['status'] = 0;
									$returnData['message'] = "ID not found";
								}
						
						
					}
					if(  $thisAction == 'RESTORE'  )
					{
						//$dataArray['trashed'] = date('Y-m-d h:i:s');
						if( isset ( $dataArray['ID'] ))
						{
							if( (int)$dataArray['ID'] < 1  ) 
							{
								$returnData['status'] = 0;
								$returnData['message'] = "Invalid ID";
							}else{
								$result = $wpdb->update( 
												 $tableName,
												 array("isTrash" => 0, ),
												 array("ID" => $dataArray['ID'])
											   );
								if($result)
								{
									if( isset( $wpRestAfter[$tblName] ) )
									{
										
										$thisRest = $wpRestAfter[$tblName][0]($tblName,$thisAction);
										if($thisRest[0])
										{
											$returnData['status'] = 0;
											$returnData['message'] = "has been successfully restored.. all Transcation Added";
										}else{
											$returnData['status'] = 0;
											$returnData['message'] = "has been successfully restored.. {$thisRest[1]}";
										}
										
									}
									else
									{
										$returnData['status'] = 1;
										$returnData['message'] = "has been successfully restored";
											
									} 
								}else{
									$returnData['status'] = 0;
									$returnData['message'] = "Failed to restore";
								}
							}
						}else{
									$returnData['status'] = 0;
									$returnData['message'] = "ID not found";
								}
						
						
					}
					if( $thisAction == 'VIEW' )
					{
						$result = $wpdb->get_results("SELECT * FROM {$tableName} Order BY {$dataArray['orderBy']}","ARRAY_A");
						if( count ( $result ) > 0 )
						{
							$returnData['status'] = 1;
							$returnData['message'] = $result;
						}
						else{
							$returnData['status'] = 0;
							$returnData['message'] = "No data found";
						}
					} 
					
					 if( $thisAction == 'BACKUP' )
					{
						$DBUSER=DB_USER;
						$DBPASSWD=DB_PASSWORD;
						$DATABASE=DB_NAME;
						$HOSTNAME=DB_HOST; 
						
						$backup_file = $DATABASE ."__". date("Y-m-d-H-i-s") . '.sql.gz';
						$command = "mysqldump --host=$HOSTNAME --user=$DBUSER --password=$DBPASSWD $DATABASE  | gzip > $backup_file";
						system($command);
						$returnData['status'] = 1;
						$returnData['message'] = "Backup has been successfully added";
					}
					 if( $thisAction == 'GET_BACKUP' )
					{
						global $scanDirPath;
						$DATABASE=DB_NAME;
						$scanDir = scandir($scanDirPath); 
						$returnArray = array();
						foreach($scanDir as $key => $val)
						{
							if( preg_match('/'.$DATABASE.'/',$val) )
							{	
								$thisFile = explode("__",$val);
								
								$returnArray[] = $val;
						
							}
						}
						if( count ( $returnArray ) > 0 )
						{
							$returnData['status'] = 1;
							$returnData['message'] = $returnArray;
						}
						else
						{
							$returnData['status'] = 0;
							$returnData['message'] = "No Back up found";
						}
					}
					 if( $thisAction == 'UNLINK' )
					{
						global $scanDirPath;
						$scanDir = $scanDirPath.'/'.$dataArray['fileName'];
						if(file_exists($scanDir)){
							$result = unlink($scanDir);
							if($result)
							{			
								$returnData['status'] = 1;
								$returnData['message'] = "File has been deleted";
							}
							else {
								$returnData['status'] = 0;
								$returnData['message'] = "Failed to delete";
							}
						}else{
							$returnData['status'] = 0;
							$returnData['message'] = "No file found";
						}
						
					}
					if( $thisAction == 'CHANGEPASSWORD' ) 
					{
							$user = get_userdata($user_check['ID']); //trace($user);
							$x = wp_check_password($dataArray['oldPassword'], $user->user_pass, $user->ID);
							if ($x) {
								if (!empty($dataArray['newPassword']) && !empty($dataArray['confirmPassword'])) {
									if ($dataArray['newPassword'] == $dataArray['confirmPassword']) 
									{
										$udata['ID'] = $user->ID;
										$udata['user_pass'] = $dataArray['newPassword'];
										$uid = wp_update_user($udata);
										if ($uid) {
											
											$returnData['status'] = 1;
											$returnData['message'] = "has been successfully reset";
							

										} else {
											$returnData['status'] = 0;
											$returnData['message'] = "Failed to update";
										}
									} else {
										$returnData['status'] = 0;
										$returnData['message'] = "Confirm password doesn't match with new password";
									}
								} else {
									$returnData['status'] = 0;
									$returnData['message'] = "Invalid new password and confirm password";
								}
							} else {
								$returnData['status'] = 0;
								$returnData['message'] = "Old password doesn't match";
							}					 
					}
				}
				
			}

		}else
		{
			$returnData['status'] = 0;
			$returnData['message'] = "Access denied";
		}
		
		$res = new WP_REST_Response($returnData);
        $res->set_status(200);
		return $res;
	}
	
	
	
	public function actionLogin($req)
	{
		
		$returnData = array();		
		if( $_SERVER['REQUEST_METHOD'] === 'POST')
		{
			if( $_POST['action'] == 'login')
			{
				
				if( !empty($_POST['user_name']) && !empty($_POST['user_password'])  )
				{
					
					$login_data = array();
					$login_data['user_login'] = $_POST['user_name'];
					$login_data['user_password'] = $_POST['user_password'];
					$login_data['remember'] = isset ( $_POST['remember'] ) ? true : false ;
					//$user_verify = wp_signon( $login_data, true);
					$user = null;
					$user = wp_authenticate($_POST['user_name'], $_POST['user_password']);
					//return $user_verify;
					 if ($user instanceof WP_User) {
						 $userData = array(
										"user_id"=> $user->ID,
										"user_name"=>$_POST['user_name'],
										"user_role"=>$user->User_Role
									); 
							//return $userData;		
							$iat = time(); 
							$secret_key = 'owt125';
							$playload_info = array(
							"iss"=> DOMAIN_NAME,
							"iat"=> $iat,
							"nbf"=> $iat + 1,
							"exp"=> $iat + (int)JWT_TIME,
							"aud"=> $_POST['user_name'],
							"data"=> $userData,
							);
							$jwt = JWT::encode($playload_info, $secret_key,'HS256');
							$returnData['status'] = 1;
							$returnData['jwt'] = $jwt;
							$returnData['data'] = $userData;
							$returnData['message'] = "Logged in successfully";
							$menuList = getMenuList($user->ID);
							$returnData['menu'] = $menuList;
					 }
					 else
					 {
						 $loginError = 'Invalid credentials';
						$returnData['status'] = 0;
						$returnData['message'] = $loginError; 
					 }
					 /*
					if ( is_wp_error( $user_verify ) ) 
					{
					
						//$loginError = $user_verify->get_error_message();
						$loginError = 'Invalid credentials';
						$returnData['status'] = 0;
						$returnData['message'] = $loginError; 
					}
					else
					{
					
							$userData = array(
										"user_id"=> $user_verify->ID,
										"user_name"=>$_POST['user_name'],
										"user_role"=>$user_verify->User_Role
									); 
							//return $userData;		
							$iat = time(); 
							$secret_key = 'owt125';
							$playload_info = array(
							"iss"=> DOMAIN_NAME,
							"iat"=> $iat,
							"nbf"=> $iat + 1,
							"exp"=> $iat + (int)JWT_TIME,
							"aud"=> $_POST['user_name'],
							"data"=> $userData,
							);
							$jwt = JWT::encode($playload_info, $secret_key,'HS256');
							$returnData['status'] = 1;
							$returnData['jwt'] = $jwt;
							$returnData['data'] = $userData;
							$returnData['message'] = "Logged in successfully";
							$menuList = getMenuList($user_verify->ID);
							$returnData['menu'] = $menuList;
							
						
					}
					*/
				
					
				}else{
						$returnData['status'] = 0;
						$returnData['message'] = "Some fiels are required";
						
					}
			}
			else
			{
				$returnData['status'] = 0;
				$returnData['message'] = "POST data are not valid";
			}
			
		}else
		{
			$returnData['status'] = 0;
			$returnData['message'] = "Access denied";
		}
		$res = new WP_REST_Response($returnData);
        $res->set_status(200);
		return $res;
	}

 }