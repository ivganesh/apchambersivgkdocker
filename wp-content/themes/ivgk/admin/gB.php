<?php
global $dataArray;
global $errorPrefix;
global $systemAC;
global $menuCheck;
global $menuAction; 
global $pageId;
global $scanDirPath;
global $wpAjaxpath;
global $wpRestUnique;
global $wpRestBefore;
global $wpRestAfter;
global $wpRestTrn;
global $wpRestReq;
global $fontStyle;	
$dataArray = array();
$scanDirPath = dirname(  dirname(  dirname(  dirname( dirname(__FILE__) ) ) ) );
$wpAjaxpath = get_site_url().'/wp-admin/admin-ajax.php';
// CASH-BANK REGISTER

$registerAccount = array( "CASH", "CONTRA", "JV", "PHARMACY CASH", "RECEPTION CASH", 
						"APPOINTMENT CASH","IPD CASH", "OPD CASH", "REFER", "REFER CASH", 
						"REFER PROFIT","REFER-IN",  "REFER-IN CASH", "REFER-IN PROFIT","REFER-OUT", "REFER-OUT CASH", "REFER-OUT PROFIT", 
						"STOCK AMOUNT", "GST PAID", "GST COLLECTED", "TDS PAID", "TDS COLLECTED", 
						"CESS PAID", "CESS COLLECTED", "CONVEYACE", "DEBTOR ROUNDOFF", "CREDITOR ROUNDOFF", 
						"PATIENT PROFIT", "WALKING CLIENT",	"LAB CASH", 
						
						"SALE", "PURCHASE", "ADMIT", 
						"DISCHARGE", "PATIENT", "APPOINTMENT", "DOCTOR OPD", "DOCTOR IPD", 
						"NURSE IPD",  "LAB",  "RECEPTION", "PHARMACY SALE",
						"PHARMACY PURCHASE", "BILLING" ,"TEST","TEST PROFIT"  );
$userId = 3;
foreach( $registerAccount as $key => $val)
{
	$systemAC[$val] = $userId; 
	$userId++; 
}
unset($userId);

global $js_data;
			
$jsData['membershipCategoryList'] = array("C1" => "C1", 
										 "C2" => "C2", 
										 "C3" => "C3", 
										 "C4" => "C4", 
										 "Active" => "Active", 
										 "AFF1" => "AFF1", 
										 "AFF2" => "AFF2", 
										 "AFF3" => "AFF3");
$jsData['pageNameList'] = array("Executive Committiee Profile" => "Executive Committiee Profile",
								"Executive Committiee Without Profile" => "Executive Committiee Without Profile",
								"Board Of Directors - Zonal Heads" => "Board Of Directors - Zonal Heads" , 
								"Board Of Directors - Board Members" => "Board Of Directors - Board Members" , 
								
								"Past Leaders - Past Presidents" => "Past Leaders - Past Presidents" , 
								"Past Leaders - Past General Secretaries" => "Past Leaders - Past General Secretaries" , 
								"Next Gen" => "Next Gen", 
								"Central Andhra Region" => "Central Andhra Region", 
								"Raayalaseema Region" => "Raayalaseema Region",
								"Vizag Region"=> "Vizag Region");
$jsData['stateNameList'] = array ( "" => "Select State", "35" => "ANDAMAN AND NICOBAR ISLANDS - 35", "28" => "ANDHRA PRADESH - 28", "37" => "ANDHRA PRADESH (NEW) - 37", "12" => "ARUNACHAL PRADESH - 12", "18" => "ASSAM - 18", "10" => "BIHAR - 10", "4" => "CHANDIGARH - 4", "22" => "CHATTISGARH - 22", "26" => "DADRA AND NAGAR HAVELI - 26", "25" => "DAMAN AND DIU - 25", "7" => "DELHI - 7", "30" => "GOA - 30", "24" => "GUJARAT - 24", "6" => "HARYANA - 6", "2" => "HIMACHAL PRADESH - 2", "1" => "JAMMU & KASHMIR - 1", "20" => "JHARKHAND - 20", "29" => "KARNATAKA - 29", "32" => "KERALA - 32", "31" => "LAKSHWADEEP - 31", "23" => "MADHYA PRADESH - 23", "27" => "MAHARASHTRA - 27", "14" => "MANIPUR - 14", "17" => "MEGHLAYA - 17", "15" => "MIZORAM - 15", "13" => "NAGALAND - 13", "21" => "ODISHA - 21", "34" => "PUDUCHERRY - 34", "3" => "PUNJAB - 3", "8" => "RAJASTHAN - 8", "11" => "SIKKIM - 11", "33" => "TAMIL NADU - 33", "36" => "TELANGANA - 36", "16" => "TRIPURA - 16", "9" => "UTTAR  PRADESH - 9", "5" => "UTTARAKHAND - 5", "19" => "WEST BENGAL - 19" );
$jsData['accountTypeList'] = array( '' => 'Select Account Type',"CA." => "CA." , "Dr." => "Dr." , "Er." => "Er." , "M/S" => "M/S" ,"Miss" => "Miss" , "Mr." => "Mr." , "Mrs." => "Mrs." , "Ms." => "Ms." , "Mx." => "Mx." );
$jsData['answerList'] = [""=>"Select Answer","YES"=>"YES","NO"=>"NO","Cant Say"=>"Can't Say"];
$jsData['bloodGroupList'] = ['' => 'Select Blood Group','A+' => 'A+' , "A-" => 'A-' , "B+" => 'B+' , "B-" => 'B-' , "AB+" => 'AB+' , "AB-" => 'AB-' , "O+" => 'O+' , "O-" => 'O-' ];
$jsData['genderList'] = ['' => 'Select Gender', 'MALE' => 'Male' , "FEMALE" => 'Female', "TRANSGENDER" => 'TRANSGENDER'  ];
$jsData['guardianTypeList'] = ['' => 'Select Guardian', 'S/O' => 'S/O' , "D/O" => 'D/O' , "Brother " => "Brother", "Sister" => "Sister" , "Cousin brother" => "Cousin brother", "Cousin Sister" => "Cousin Sister" , 'Uncle' => 'Uncle' , "Aunt" => 'Aunt' ,'W/O' => 'W/O' ];
$jsData['referralList'] = ['' => 'Select Referral', 'RMP' => 'RMP' , "Doctor" => 'Doctor' , "Self" => "Self", "Relatives" => "Relatives" , "Friends" => "Friends", "Professor" => "Professor" , 'Other' => 'Other'   ];
$jsData['balanceHeadList'] = array("" => "Select Balance Head", "CAPITAL AC" => "CAPITAL AC" , "CAPITAL LIABILITY" => "CAPITAL LIABILITY" , "CURRENT ASSET" => "CURRENT ASSET", "CURRENT LIABILITY" => "CURRENT LIABILITY" , "EXPENCE" => "EXPENCE" , "FIXED ASSET" => "FIXED ASSET", "INCOME" => "INCOME" , "NON-CURRENT ASSET" => "NON-CURRENT ASSET" , "NON-CURRENT LIABILITY" => "NON-CURRENT LIABILITY" );
$jsData['yesNoList'] = array("" => "Select Option", "YES" => "YES" , "NO" => "NO");
$jsData['allowedDomain'] = array("in", "org", "com", "info");
$jsData['sundayAppointList'] = array("" => "Select Option", "Yes" => "Yes" , "No" => "No", "Morning" => "Morning", "Evening" => "Evening");
$jsData['yesList'] = array("" => "Select Option", "YES" => "YES");
$jsData['perFlatList'] = array( "Flat" => "Flat", "Percent" => "Per ( % )");
$jsData['salaryTypeList'] = array( "Salary" => "Salary", "Commission" => "Commission");
 
$jsData['gstTypeList'] = array("" => "Select GST Type", "GST-1" => "GST-1" , "GST-2" => "GST-2", "GST-3" => "GST-3");
$jsData['allowedFile'] = array("php", "css","js","html");
$jsData['deliveryList'] = array('' => 'Select If Delivery', 'Normal Delivery' => 'Normal Delivery', 'Operation Delivery' => 'Operation Delivery');
$jsData['infantTypeList'] = ['' => 'Select Infant Type',  "Baby Boy" => "Baby Boy" , 'Baby Girl' => 'Baby Girl' , "Baby Trans" => 'Baby Trans'  ];							
$jsData['attendList'] = array( "Absent" => "Absent" , "Present" => "Present", "Holiday" => "Holiday", "Sick-Leave" => "Sick-Leave", "Casual-Leave" => "Casual-Leave", "First-Half" => "First-Half", "Last-Half" => "Last-Half");
$jsData['deductOption'] = array( '' => 'Select YES for deduction of absent days',"YES" => "YES" , "NO" => "NO");
$jsData['hivList'] = array ( 'NR' => "NR" , 'R' => 'R' ); 
$jsData['treatmentTypeList'] = array ( 'Medical Managed' => "Medical Managed" , 'Surgery Done' => 'Surgery Done' ); 


$jsData['returnSubmitValue'] = '';
$jsData['Action'] = 'ADD'; 
$jsData['Refresh'] = 'Refresh';
$jsData['countShowDataRow'] = 0;
$jsData['mailArr'] = array(); 
$jsData['tableArr'] = array();
$jsData['md12lg12'] = "col-sm-12 col-md-12 col-lg-12";
$jsData['md11lg11'] = "col-sm-12 col-md-11 col-lg-11";
$jsData['md10lg10'] = "col-sm-12 col-md-10 col-lg-10";
$jsData['md9lg9'] = "col-sm-12 col-md-9 col-lg-9";
$jsData['md8lg8'] = "col-sm-12 col-md-8 col-lg-8";
$jsData['md7lg7'] = "col-sm-12 col-md-7 col-lg-7";
$jsData['md6lg6'] = "col-12 col-sm-6 col-md-6 col-lg-6";
$jsData['md5lg5'] = "col-6 col-sm-5 col-md-5 col-lg-5";
$jsData['md4lg4'] = "col-sm-12 col-md-4 col-lg-4";
$jsData['md3lg3'] = "col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3";
$jsData['md2lg2'] = "col-12 col-sm-2 col-md-2 col-lg-2 mt-auto";
$jsData['md1lg1'] = "col-sm-12 col-md-1 col-lg-1";
$jsData['spanWidth'] = '120';
$jsData['hiddenType'] = array(
			"fieldType" => "input",
			"type" => "hidden",
		);


															
global $fontStyleList;		
$fontStyleList = array( '' => 'Select Font' ,'ABeeZee' => 'ABeeZee' ,'Abel' => 'Abel' ,'Acme' => 'Acme' ,'Alegreya Sans' => 'Alegreya Sans' ,
						'Aleo' => 'Aleo' ,'Antic' => 'Antic' ,'Arimo' => 'Arimo' ,'Arizonia' => 'Arizonia' ,'Arsenal' => 'Arsenal' ,
						'Arvo' => 'Arvo' , 'Arya' => 'Arya' ,'Assistant' => 'Assistant' ,'Basic' => 'Basic' ,'Bitter' => 'Bitter'  , 
						'Cousine' => 'Cousine' ,'Coustard' => 'Coustard' ,'Lemon' => 'Lemon' ,'Nova Square' => 'Nova Square' , 
						'Oleo Script' => 'Oleo Script' ,'Open Sans' => 'Open Sans' ,'PT Serif' => 'PT Serif' ,'Rajdhani' => 'Rajdhani' , 
						'Roboto' => 'Roboto' ,'Roboto Mono' => 'Roboto Mono' ,'Roboto Slab' => 'Roboto Slab' ,'Stylish' => 'Stylish' 
						);  

$wpRestUnique['balancesheet'] = array("Balance_Sheet" => "Balance_Sheet" );
$wpRestUnique['gstcharge'] = array("GST_Charge" => "GST_Charge" );
$wpRestUnique['orderbillno'] = array("Bill_Date" => "Bill_Date");
$wpRestUnique['chargetype'] = array("Charge_Type" => "Charge_Type");
$wpRestUnique['roomno'] = array("Room_Code" => "Room_Code"); 
$wpRestUnique['wardtype'] = array("Ward_Code" => "Ward_Code" ,"1" => "AND", "Action_Date" => "Action_Date");
$wpRestUnique['userrole'] = array("User_Role" => "User_Role");
$wpRestUnique['timeslot'] = array("Slot_Start" => "Slot_Start"); 
$wpRestUnique['admit'] = array("Patient_Name" => "Patient_Name" ,"1" => "AND", "Admit_Date" => "Admit_Date");
$wpRestUnique['appointment'] = array("Patient_Name" => "Patient_Name" ,"1" => "AND", "Appoint_Date" => "Appoint_Date");
$wpRestUnique['allergy'] = array("Allergy" => "Allergy");
$wpRestUnique['description'] = array("Description" => "Description");
$wpRestUnique['dose'] = array("Dose" => "Dose");
$wpRestUnique['dosetime'] = array("Dose_Time" => "Dose_Time");
$wpRestUnique['narration'] = array("Narration" => "Narration");
$wpRestUnique['medicalhistory'] = array("Medical_History" => "Medical_History");
$wpRestUnique['symptom'] = array("Symptom" => "Symptom");
$wpRestUnique['illness'] = array("Illness" => "Illness");
$wpRestUnique['speciality'] = array("Speciality" => "Speciality");
$wpRestUnique['firm'] = array( "Action_Date" => "Action_Date");
$wpRestUnique['producttype'] = array( "HSN" => "HSN","1" => "AND", "Action_Date" => "Action_Date");
$wpRestUnique['doctoropd'] = array( "Patient_Name" => "Patient_Name","1" => "AND", "OPD_Date" => "OPD_Date");
//$wpRestUnique['doctoripd'] = array( "Patient_Name" => "Patient_Name","1" => "AND", "IPD_Date" => "IPD_Date");
//$wpRestUnique['nurseipd'] = array( "Patient_Name" => "Patient_Name","1" => "AND", "IPD_Date" => "IPD_Date");

$wpRestAfter['sale'] = array( 'wpRestTrn', true);	
$wpRestAfter['purchase'] = array( 'wpRestTrn',true);
$wpRestAfter['appointment'] = array( 'wpRestTrn',true);	
$wpRestAfter['admit'] = array( 'wpRestTrn',true);
$wpRestAfter['refer'] = array( 'wpRestTrn', true);	
	 
$wpRestBefore['admit'] = array('addAdmissionNo',true); 
$wpRestBefore['userrole'] = array('addNewUserrole',true);  
$wpRestBefore['account'] = array('addNewUsers',true);
$wpRestBefore['patient'] = array('addNewUsersPatient',true); 
$wpRestBefore['employee'] = array('addNewUsersEmployee',true);  

$wpRestReq['admit'] = array('Patient_Name'=>array(),'Admit_Date'=>array(),'Admit_Time'=>array(),'Room_No'=>array());
$wpRestReq['appointment'] = array('Patient_Name'=>array(),'Appoint_Date'=>array(),'Appoint_Time'=>array(),'Appoint_DR'=>array(),'Bill_Amount'=>array());
$wpRestReq['refer'] = array('Patient_Name'=>array(),'Refer_Date'=>array(),'Refer_By'=>array(),'Refer_To'=>array(),'Refer_For'=>array());
$wpRestReq['refer_only'] = array('Patient_Name'=>array(),'Refer_Date'=>array(),'Refer_By'=>array(),'Refer_To'=>array(),'Refer_For'=>array());
$wpRestReq['bankcash'] = array('Bill_Date'=>array(),'Register'=>array());
$wpRestReq['balancesheet'] = array('Balance_Sheet'=>array(),'Balance_Head'=>array());
$wpRestReq['orderbillno'] = array('Bill_Date'=>array(),'BillNo'=>array(),'OrderNo'=>array());
$wpRestReq['gstcharge'] = array('GST_Charge'=>array());
$wpRestReq['chargetype'] = array('Charge_Type'=>array(),'Charge_Amount'=>array()); 
$wpRestReq['roomno'] = array("Room_Code" =>array(), "Room_Name"=>array(), "Ward_Type"=>array());
$wpRestReq['wardtype'] = array("Ward_Code" =>array(), "Ward_Name"=>array(), "Ward_Charge"=>array(), "Doctor_Charge"=>array(), "Nursing_Charge"=>array()  );
$wpRestReq['userrole'] = array("User_Role" =>array()); 
$wpRestReq['timeslot'] = array("Slot_Start" => array(),"Slot_End" => array() );
$wpRestReq['firm'] = array("Company_Name" => array(),"Company_State" => array(),"Action_Date" => array(),"Contact_No" => array()
							,"Contact_Email" => array(),"Address" => array(),"Slot_Appoint" => array(),"Sunday_Appoint" => array()
							,"Appoint_Charge" => array(),"Appoint_Validity" => array(),"Next_Charge" => array() );