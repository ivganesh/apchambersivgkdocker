<?PHP
class classMysql
{
	function getSlabDesc($date,$presentDays,$thisSalary,$slabStructure)
   {
	    $returnArr = array();
		$pass_date = $date;
		$total_days = cal_days_in_month(CAL_GREGORIAN, date('m', $pass_date), date('Y', $pass_date));
		$salaryNet = (float)$thisSalary['Net_Salary'];
		$salaryAmount = $presentDays *  (float)$thisSalary['Net_Salary'] / $total_days;
		$returnArr['salaryAmount'] = number_format((float)$salaryAmount, 2, '.', '');
		$returnArr['total_days'] = $total_days;
		$total_ctc = $basicAmt = 0;
		$salarySlab = '';
		$basic = $slabStructure[1];
		
		
		foreach( $basic as $cKey => $cVal )
		{
			$salarySlab = $cVal['salarySlab'];
			if( (float)$salaryAmount >= $cKey)
			{
				 
				if( $cVal['Type'] == 'Percent')
				{
					$desc = (float)$cVal['Breakup_Value'] ." % OF ".(float)$salaryAmount." [SALARY]";
					$thisVal  =  (float)$salaryAmount * (float)$cVal['Breakup_Value'] / 100;
				}
				else
				{				
					$desc = (float)$cVal['Breakup_Value'] ." FLAT";
					$thisVal =   (float)$cVal['Breakup_Value'];
			
				}
				
				$returnArr[$cVal['salaryBreakup']] = $thisVal;
				$returnArr['earningArr'][$cVal['salaryBreakup']] = array( "Amount" => $thisVal,'Desc'=> $desc) ;
						
				$basicAmt += $thisVal;
				break;
			}
		}
		$total_ctc += $basicAmt;
		unset($slabStructure[1]);
		foreach( $slabStructure as $key => $value )
		{
			foreach( $value as $cKey => $cVal )
			{
				
				if( (float)$salaryAmount >= $cKey)
				{
					$desc  = '';
					if( $cVal['Type'] == 'Percent')
					{
						if( $cVal['Basic_Breakup'] == 'YES')
						{	
							$desc = (float)$cVal['Breakup_Value'] ." % OF ".(float)$basicAmt." [BASIC]";
							$thisVal  =  (float)$basicAmt * (float)$cVal['Breakup_Value'] / 100;
						}
						else 
						{
							$desc = (float)$cVal['Breakup_Value'] ." % OF ".(float)$salaryAmount." [SALARY]";
							$thisVal  =  (float)$salaryAmount * (float)$cVal['Breakup_Value'] / 100;
						}
					}
					else
					{		
						$desc = (float)$cVal['Breakup_Value'] ." FLAT";
						$thisVal =   (float)$cVal['Breakup_Value'];
					}
					
					if( $cVal['Deduct'] == 'YES')
					{
						$returnArr['earningArr'][$cVal['salaryBreakup']] = array( "Amount" => $thisVal,'Desc'=> $desc) ;
						$total_ctc -= $thisVal;
					}
					else 
					{
						$returnArr['deductArr'][$cVal['salaryBreakup']] = array( "Amount" => $thisVal,'Desc'=> $desc) ;
						$total_ctc += $thisVal;
					}
					break;
				}
				
			}
		}
		$returnArr['total_ctc'] =  number_format((float)$total_ctc, 2, '.', ''); 
		$returnArr['salarySlab'] =  $salarySlab; 
		return $returnArr;
   }
   
	public function employeeWeight($startDate,$endDate,$employee)
	{
		global $wpdb;
		$returnData = array();
		$packethistory =  "{$wpdb->prefix}packet_history";
		$qry = strlen($employee) > 0 ? " AND Issue_To ='{$employee}' " : "";
		$commissionQry = $wpdb->get_results("SELECT * FROM ( SELECT Return_Date,Packet_No,Weight,Issue_To FROM {$packethistory}
													 WHERE Return_Date >= '{$startDate}'  AND 
														   Return_Date <= '{$endDate}' 
														   {$qry}
													ORDER BY Weight DESC ) AS W1 GROUP BY W1.Issue_To,W1.Packet_No,W1.Issue_To", 'ARRAY_A');
		foreach ( $commissionQry as $key => $value)
		{
			//echo $value['Return_Date']."<br>";
			$time = strtotime( $value['Return_Date']." ,first day of this month"); 
			$returnData[$time][$value['Issue_To']][] =  (float)$value['Weight'];
			
		}
		return $returnData;
				
	}
	
	public function getEmployeeAttend($startDate,$endDate,$employee)
	{
		global $wpdb; 
		$employeeAttend = array();
		$attend = "{$wpdb->prefix}employeeattend";
		$qry = '';
		 $qry .= strlen($employee) > 0 ? " AND {$attend}.Employee_Name ='{$_POST['Employee']}' " : "";
		 $qry .= strlen($startDate) > 0 ? "  AND {$attend}.Attend_Date >= '{$startDate}' " : "";
		 $qry .= strlen($endDate) > 0 ? "  AND {$attend}.Attend_Date <= '{$endDate}' " : "";
		
                $employeeAttendQry = $wpdb->get_results("SELECT
                                                        {$attend}.Attendance,
                                                        {$attend}.Employee_Name,
														CONCAT( DATE_FORMAT( {$attend}.Attend_Date , '%Y-%m-') ,'01' ) as Attend_Date
                                                     FROM
                                                        {$attend}
                                                    WHERE
                                                         {$attend}.isTrash=0 {$qry} 
                                                         ", 'ARRAY_A');
                foreach ($employeeAttendQry as $key => $val) {

                    $time = strtotime( $val['Attend_Date'] ); 
					if( isset ( $employeeAttend[$time][$val['Employee_Name']]  ) ) 
					{
						if( $val['Attendance'] == 'Present' || $val['Attendance'] == 'Sick-Leave' || 
							$val['Attendance'] == 'Casual-Leave'|| $val['Attendance'] == 'Paid-Leave' )
						{
							$employeeAttend[$time][$val['Employee_Name']] += 1;
						}else if( $val['Attendance'] == 'First-Half-Paid' || $val['Attendance'] == 'Last-Half-Paid' )
						{
							$employeeAttend[$time][$val['Employee_Name']] += 0.5;
						}
					}
					else{
						if( $val['Attendance'] == 'Present' || $val['Attendance'] == 'Sick-Leave' || 
							$val['Attendance'] == 'Casual-Leave'|| $val['Attendance'] == 'Paid-Leave' )
						{
							$employeeAttend[$time][$val['Employee_Name']] = 1;
						}else if( $val['Attendance'] == 'First-Half-Paid' || $val['Attendance'] == 'Last-Half-Paid' )
						{
							$employeeAttend[$time][$val['Employee_Name']] = 0.5;
						}
					}
                }
				
				return $employeeAttend;
				
	}
	
	public function commissionStructureData($startDate, $endDate, $dateRange)
	{
		global $wpdb;
		global $jsData;
		$returnData = array();
		$commissionhistory =  "{$wpdb->prefix}commissionstructure_history";
		$commissionInnerQry = $wpdb->get_results("
								SELECT Action_Date,Weight,Commission,Commission_Slab
								FROM {$commissionhistory}
								 WHERE Action_Date <= '{$endDate}'
								ORDER BY Action_Date DESC,Weight DESC", 'ARRAY_A');
		foreach( $commissionInnerQry as $key => $value ) 
		{
			$time = strtotime($value['Action_Date']);
			$returnData[$time][$value['Commission_Slab']][$value['Weight']] =  (float)$value['Commission'];
		}
	
		krsort($returnData);
		$lastVar = array();
		
		foreach( $dateRange as $val )
		{
			if( !isset ( $returnData[$val] ) )
			{
				$thisData = $this->returnData($val, $returnData);
				if( count ( $thisData ) > 0 )$returnData[$val] = $thisData;
			}				
		}
		
		
		return $returnData; 
	}
	
	private function returnData( $val, $returnData )
	{
		$checkValue = (int)$val;
		$returnArr = array();
		foreach( $returnData as $key => $value )
		{
			$thisValue = (int)$key;
			
			if( $thisValue < $val)
			{
				$returnArr = $value;
				break;
			}
		}
		return $returnArr;
	}
	public function getSalary($startDate, $endDate,$employee,$dateRange)
	{
		global $wpdb;
		$returnData = array();
		$salary = "{$wpdb->prefix}salary_head";
		$salarySlab = "{$wpdb->prefix}salaryslab";
		$users = "{$wpdb->prefix}users";
		$breakup = "{$wpdb->prefix}salarybreakup";
		$cSlab = "{$wpdb->prefix}commissionslab";
		$qry = strlen($employee) > 0 ? " AND {$salary}.Employee ='{$employee}' " : ""; 

		$salaryInnerQry = $wpdb->get_results("SELECT  {$salary}.Employee,
													  {$salary}.Net_Salary,
													  {$salary}.Salary_Head,
													  {$salary}.Action_Date,
													   {$salary}.Salary_Slab, 
													   {$salarySlab}.Salary_Slab AS salarySlab, 
													    {$cSlab}.Commission_Slab AS salaryHead, 
											 CONCAT ( DATE_FORMAT({$users}.Joining_Date,'%Y-%m-') , '01' ) AS Joining_Date
											 FROM {$salary}
											 JOIN {$users} ON ( {$users}.ID = {$salary}.Employee )
											 LEFT JOIN {$cSlab} ON ({$cSlab}.ID = {$salary}.Salary_Head )
											 LEFT JOIN {$salarySlab} ON ({$salarySlab}.ID = {$salary}.Salary_Slab )
											 WHERE {$salary}.isTrash = 0 AND {$salary}.Action_Date <= '{$endDate}' 
											 {$qry} 
											ORDER BY {$salary}.Action_Date DESC", 'ARRAY_A');
		foreach ( $salaryInnerQry as $key => $value )
		{ 
			$time = strtotime($value['Action_Date']);
			$value['Joining_Date'] = strtotime($value['Joining_Date']);
			if( (int)$value['Joining_Date']  < 0 ) $value['Joining_Date']  = 0;
			$returnData[$value['Employee']][$time] = $value;
		}
		krsort($returnData);
		$lastVar = array();
		foreach( $returnData as $key => $value  )
		{
			
				foreach( $dateRange as $val )
				{
					if( !isset ( $returnData[$key][$val] ) )
					{
						$thisData = $this->returnData($val, $value);
						if( count ( $thisData ) > 0 )$returnData[$key][$val] = $thisData;
					}				
				}
			
		}
		return $returnData;
}
	public function getSalaryStructure($startDate, $endDate,$dateRange)
	{
		global $wpdb;
		$returnData = array();
		$salarystructure = "{$wpdb->prefix}salarystructure_history";
		$breakup = "{$wpdb->prefix}salarybreakup";
		$slab = "{$wpdb->prefix}salaryslab";
		$salaryInnQry = $wpdb->get_results(" SELECT  {$salarystructure}.*,
												{$breakup}.Salary_Breakup as salaryBreakup,
												{$slab}.Salary_Slab as salarySlab		
												FROM {$salarystructure}
												
												LEFT JOIN {$breakup} ON ({$breakup}.ID = {$salarystructure}.Breakup )
												LEFT JOIN {$slab} ON ({$slab}.ID = {$salarystructure}.Salary_Slab )
												WHERE  {$salarystructure}.Action_Date <= '{$endDate}' 
												ORDER BY  {$salarystructure}.Action_Date DESC,  {$salarystructure}.Criteria DESC", 'ARRAY_A');
		foreach( $salaryInnQry as $key => $value )
		{
			$time = strtotime($value['Action_Date']);
			$returnData[$time][$value['Salary_Slab']][$value['Breakup']][$value['Criteria']] = $value;		
		}
		krsort($returnData);	
		$lastVar = array();
		foreach( $dateRange as $val )
		{
			if( !isset ( $returnData[$val] ) )
			{
				$thisData = $this->returnData($val, $returnData);
				if( count ( $thisData ) > 0 )$returnData[$val] = $thisData;
			}				
		}		
		return $returnData;
	}
	
	public function getAllEmployee($employee='')
	{
		global $wpdb;
		$returnData = array();
		$users = "{$wpdb->prefix}users";	
		$salaryHead = "{$wpdb->prefix}salary_head";
	    $qry = strlen($employee) > 0 ? " AND {$users}.Employee ='{$employee}' " : "";

		$returnData = $wpdb->get_results("select 
														{$salaryHead}.Employee AS ID,
														{$users}.Account_Name,{$users}.User_Role,
													{$users}.Register_Mobile
													from {$salaryHead} 
													JOIN {$users} ON ({$users}.ID = {$salaryHead}.Employee) 
													Group by {$salaryHead}.Employee", 'ARRAY_A');
		return $returnData;		
		
	}
	
	public function checkEmployeeAttend()
	{
		global $wpdb;
		$attend = "{$wpdb->prefix}employeeattend";
		$getQry = $wpdb->get_results("SELECT Attend_Date from {$attend} 
									  WHERE Attend_Date='{$_POST['Attend_Date']}' LIMIT 1","ARRAY_A");
		if( count ( $getQry) > 0 ) return false;
		else return true;
	}
	
    public function getMasterData($valueId,$masteType)
    {
        global $wpdb;
        $classUI = new classUI();
        $masterQry  = $wpdb->get_results("SELECT Master_Description 
                                        FROM {$wpdb->prefix}master 
                                        WHERE Master_Type='{$valueId}' ORDER BY Master_Description","ARRAY_A");
         return $classUI->setAsSelectOption(array("table_data" => $masterQry,
                                                    "option_text" => array($valueId),
                                                    "option_value" => array("Master_Description"),
                                                )
                                            );                                
    }
    public function firmDetail()
    {
        global $wpdb;
		global $billData;
		$actionQry = '';
		if( isset ( $billData['billData']['Bill_Date'] ) )
			$actionDate = $billData['billData']['Bill_Date'];
		
		//if( isset( $actionDate ) ) $actionQry = " WHERE Action_Date <= '{$actionDate}' ORDER BY Action_Date DESC ";
        if( $_POST['printName'] == 'pharmacysale' || $_POST['printName'] == 'sale')
		{
             $firmQry = $wpdb->get_results("SELECT RX_Company_Name as Company_Name,
                                                   RX_Notes as Notes,
                                                   RX_Contact_No as Contact_No,
                                                   RX_PAN_No as PAN_No,
                                                   RX_GST_No as GST_NO,
												   Company_State,
                                                   RX_Licence_No as Licence_No,
                                                   RX_address as Address,
                                                   RX_Contact_Email as Contact_Email
                                            FROM {$wpdb->prefix}firm {$actionQry}  limit 1","ARRAY_A");
		}
        else 
		{ 
			$firmQry = $wpdb->get_results("SELECT Company_Name,
                                                    Notes,
                                                    Contact_No,
                                                    PAN_No,
													Company_State,
                                                    GST_No as GST_NO,
                                                    Licence_No,
                                                    Address,
                                                    Contact_Email 
                                             FROM {$wpdb->prefix}firm {$actionQry}  limit 1","ARRAY_A");
		}
        
        return ( count ( $firmQry) == 1 ) ? $firmQry[0] : array();
    }
    public function billData()
    {
        global $wpdb;
        $billData = array();
        $joinUsers = "{$wpdb->prefix}users";
        $joinAdmit = "{$wpdb->prefix}admit";
        $joinAppoint = "{$wpdb->prefix}appointment";
        $thisTable = "{$wpdb->prefix}{$_POST['printName']}";
		$joinOperation = "{$wpdb->prefix}operation";
		$joinDischarge = "{$wpdb->prefix}discharge";
		$joinCertificate = "{$wpdb->prefix}discharge";
		if( $_POST['printName'] == 'patient_certificate')
        {
			$billQry = $wpdb->get_results("SELECT 
            {$thisTable}.*

          FROM {$thisTable}
          WHERE {$thisTable}.isTrash=0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");
		}
        else if( $_POST['printName'] == 'test')
        {
            $billQry = $wpdb->get_results("SELECT 
            {$thisTable}.*,
            {$joinUsers}.Account_Name as accountName,
            {$joinUsers}.Patient_Age,
            {$joinUsers}.Gender,
            {$joinUsers}.Guardian_Mobile,
            {$joinUsers}.Register_Mobile,
            {$joinUsers}.Register_Email,
            {$joinUsers}.Licence_No,
            {$joinUsers}.GST_No,
            {$joinUsers}.Address,
            {$joinUsers}.City_Name,
            {$joinUsers}.Pincode_No,
            {$joinUsers}.State_Name,
            {$joinUsers}.Balance_Sheet,
            {$joinUsers}.User_Role

          FROM {$thisTable}
          LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$thisTable}.Patient_Name )
          WHERE {$thisTable}.isTrash=0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");

        }else if( $_POST['printName'] == 'pharmacysale' || $_POST['printName'] == 'sale')
        {
            $billQry = $wpdb->get_results("SELECT 
                                             {$thisTable}.*,
                                             {$joinUsers}.Account_Name as accountName,
                                             {$joinUsers}.Patient_Age,
                                             {$joinUsers}.Gender,
                                             {$joinUsers}.Guardian_Mobile,
                                             {$joinUsers}.Register_Mobile,
                                             {$joinUsers}.Register_Email,
                                             {$joinUsers}.Licence_No,
                                             {$joinUsers}.GST_No,
                                             {$joinUsers}.Address,
                                             {$joinUsers}.City_Name,
                                             {$joinUsers}.Pincode_No,
                                             {$joinUsers}.State_Name,
                                             {$joinUsers}.Balance_Sheet,
                                             {$joinUsers}.User_Role

                                           FROM {$thisTable}
                                           LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$thisTable}.Account_Name )
                                           WHERE {$thisTable}.isTrash=0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");
        }
		else if(  $_POST['printName'] == 'discharge'  )
        {
            $billQry = $wpdb->get_results("SELECT   
                                            {$thisTable}.* ,
                                            {$joinAdmit}.Admit_Date ,
                                            {$joinAdmit}.Admission_No ,
                                            {$joinAdmit}.Admit_Time ,
                                            {$joinAdmit}.Room_No ,
                                            {$joinUsers}.Account_Name as accountName,
                                             {$joinUsers}.Patient_Age,
                                             {$joinUsers}.Gender,
                                             {$joinUsers}.Guardian_Mobile,
                                             {$joinUsers}.Register_Mobile,
                                             {$joinUsers}.Register_Email,
                                             {$joinUsers}.Licence_No,
                                             {$joinUsers}.GST_No,
                                             {$joinUsers}.Address,
                                             {$joinUsers}.City_Name,
                                             {$joinUsers}.Pincode_No,
                                             {$joinUsers}.State_Name,
                                             {$joinUsers}.Balance_Sheet,
                                             {$joinUsers}.User_Role,
											 {$joinOperation}.Operation_Date
                                        FROM  {$thisTable}
										LEFT JOIN {$joinOperation} ON ( {$joinOperation}.ID = {$thisTable}.Patient_Name)
										LEFT JOIN {$joinAdmit} ON ( {$joinAdmit}.ID = {$thisTable}.Patient_Name)
                                        LEFT JOIN {$joinAppoint} ON ( {$joinAppoint}.ID = {$joinAdmit}.Patient_Name)
                                        LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$joinAppoint}.Patient_Name)
                                        WHERE {$thisTable}.isTrash = 0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");
        }
		else if(   $_POST['printName'] == 'generalbill' || $_POST['printName'] == 'dischargesummary' )
        {
            $billQry = $wpdb->get_results("SELECT   
                                            {$thisTable}.* ,
                                            {$joinAdmit}.Admit_Date ,
                                            {$joinAdmit}.Admission_No ,
                                            {$joinAdmit}.Admit_Time ,
                                            {$joinAdmit}.Room_No ,
                                            {$joinUsers}.Account_Name as accountName,
                                             {$joinUsers}.Patient_Age,
                                             {$joinUsers}.Gender,
                                             {$joinUsers}.Guardian_Mobile,
                                             {$joinUsers}.Register_Mobile,
                                             {$joinUsers}.Register_Email,
                                             {$joinUsers}.Licence_No,
                                             {$joinUsers}.GST_No,
                                             {$joinUsers}.Address,
                                             {$joinUsers}.City_Name,
                                             {$joinUsers}.Pincode_No,
                                             {$joinUsers}.State_Name,
                                             {$joinUsers}.Balance_Sheet,
                                             {$joinUsers}.User_Role,
											 {$joinOperation}.Operation_Date as operationDate,
											 {$joinDischarge}.Discharge_Date as dischargeDate
                                        FROM  {$thisTable}
										LEFT JOIN {$joinDischarge} ON ( {$joinDischarge}.ID = {$thisTable}.Patient_Name)
										LEFT JOIN {$joinOperation} ON ( {$joinOperation}.ID = {$thisTable}.Patient_Name)
										LEFT JOIN {$joinAdmit} ON ( {$joinAdmit}.ID = {$thisTable}.Patient_Name)
                                        LEFT JOIN {$joinAppoint} ON ( {$joinAppoint}.ID = {$joinAdmit}.Patient_Name)
                                        LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$joinAppoint}.Patient_Name)
                                        WHERE {$thisTable}.isTrash = 0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");
        }
        else if(  $_POST['printName'] == 'doctoripd' || $_POST['printName'] == 'nurseipd')
        {
            $billQry = $wpdb->get_results("SELECT   
                                            {$thisTable}.* ,
                                            {$joinAdmit}.Admit_Date ,
                                            {$joinAdmit}.Admission_No ,
                                            {$joinAdmit}.Admit_Time ,
                                            {$joinAdmit}.Room_No ,
                                            {$joinUsers}.Account_Name as accountName,
                                             {$joinUsers}.Patient_Age,
                                             {$joinUsers}.Gender,
                                             {$joinUsers}.Guardian_Mobile,
                                             {$joinUsers}.Register_Mobile,
                                             {$joinUsers}.Register_Email,
                                             {$joinUsers}.Licence_No,
                                             {$joinUsers}.GST_No,
                                             {$joinUsers}.Address,
                                             {$joinUsers}.City_Name,
                                             {$joinUsers}.Pincode_No,
                                             {$joinUsers}.State_Name,
                                             {$joinUsers}.Balance_Sheet,
                                             {$joinUsers}.User_Role
                                        FROM  {$thisTable}
										LEFT JOIN {$joinAdmit} ON ( {$joinAdmit}.ID = {$thisTable}.Patient_Name)
                                        LEFT JOIN {$joinAppoint} ON ( {$joinAppoint}.ID = {$joinAdmit}.Patient_Name)
                                        LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$joinAppoint}.Patient_Name)
                                        WHERE {$thisTable}.isTrash = 0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");
        } else if(  $_POST['printName'] == 'doctoropd' )
        {
            $billQry = $wpdb->get_results("SELECT   
                                            {$thisTable}.* ,
                                            {$joinAppoint}.Appoint_Date ,
                                            {$joinUsers}.Account_Name as accountName,
                                             {$joinUsers}.Patient_Age,
                                             {$joinUsers}.Gender,
                                             {$joinUsers}.Guardian_Mobile,
                                             {$joinUsers}.Register_Mobile,
                                             {$joinUsers}.Register_Email,
                                             {$joinUsers}.Licence_No,
                                             {$joinUsers}.GST_No,
                                             {$joinUsers}.Address,
                                             {$joinUsers}.City_Name,
                                             {$joinUsers}.Pincode_No,
                                             {$joinUsers}.State_Name,
                                             {$joinUsers}.Balance_Sheet,
                                             {$joinUsers}.User_Role
                                        FROM  {$thisTable}
                                        LEFT JOIN {$joinAppoint} ON ( {$joinAppoint}.ID = {$thisTable}.Patient_Name)
                                        LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$joinAppoint}.Patient_Name)
                                        WHERE {$thisTable}.isTrash = 0 AND {$thisTable}.ID={$_POST['printStep']} limit 1","ARRAY_A");
        }
        else if( 'salary' == $_POST['printName'] ) 
        {
        
            $thisData = explode("__",$_POST['printStep'] );
            $_POST['SalaryID'] = $thisData[0];
            $_POST['Salary_Date'] = $thisData[1];
            
           $thisTable = "{$wpdb->prefix}salary_head";
            $billQry = $wpdb->get_results("SELECT   
                                            {$thisTable}.* ,
                                            {$joinUsers}.Account_Name as accountName,
                                             {$joinUsers}.Patient_Age,
                                             {$joinUsers}.Gender,
                                             {$joinUsers}.Guardian_Mobile,
                                             {$joinUsers}.Register_Mobile,
                                             {$joinUsers}.Register_Email,
                                             {$joinUsers}.Licence_No,
                                             {$joinUsers}.GST_No,
                                             {$joinUsers}.Address,
                                             {$joinUsers}.City_Name,
                                             {$joinUsers}.Pincode_No,
                                             {$joinUsers}.State_Name,
                                             {$joinUsers}.Balance_Sheet,
                                             {$joinUsers}.User_Role
                                        FROM  {$thisTable}
                                        LEFT JOIN {$joinUsers} ON ( {$joinUsers}.ID = {$thisTable}.Employee)
                                        WHERE {$thisTable}.isTrash = 0 AND {$thisTable}.Employee={$_POST['SalaryID']} AND {$thisTable}.Action_Date  <= '{$_POST['Salary_Date']}' 
                                        ORDER BY {$thisTable}.Action_Date DESC limit 1","ARRAY_A");
        }
        
        $billData['billData'] = $billQry[0];
		$thisData = array();
		if( isset(  $billData['billData']['jsonData'] ) )
		{
			$dischargeDesData = json_decode( $billData['billData']['jsonData'], true );
			foreach( $dischargeDesData as $key => $val )
			{
				
					$thisData['billData'][$key] = $val;
				
			}
		}
		foreach( $billData['billData'] as $key => $val )
			{
					$thisData['billData'][$key] = $val;
				
			}
        return $thisData;
    }
    public function array_sort($array, $on, $order = SORT_ASC)
    {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function getChargeFromOpdIpd($atts)
    {
        global $wpdb;
        $opdTbl = "{$wpdb->prefix}doctoropd";
        $ipdTbl = "{$wpdb->prefix}doctoripd";
        $chargeD = $wpdb->get_results("SELECT Today_Date, Bill_Amount, Des
										   FROM
											{$opdTbl}
										  WHERE isTrash=0
										  ORDER BY Today_Date", 'ARRAY_A');

        $testG = $wpdb->get_results("SELECT Today_Date, Bill_Amount, Des
										   FROM
											{$ipdTbl}
										  WHERE isTrash=0
										  ORDER BY Today_Date", 'ARRAY_A');
        $chargeData = array_merge($chargeD, $testG);
        if (count($chargeD) == 0 && count($testG) == 0) {
            return 'N';
        } else {
            $returnStr = '';
            foreach ($chargeData as $key => $val) {
                $DesVal = json_decode($val['jsonData'], true);
                $thisArr = array();
                foreach ($DesVal as $k => $v) {
                    if (preg_match('/Charge_Type_/', $k) || preg_match('/Amount_/', $k)) {
                        $thisArr[$k] = $v;
                    }
                }
                $thisArr['Bill_Amount'] = $val['Bill_Amount'];
                $thisArr['Today_Date'] = $val['Today_Date'];
                $thisArr['submitpost_add'] = 'ADD';
                $returnStr .= "<option value='" . json_encode($thisArr) . "'>" . date_format(date_create($thisArr['Today_Date']), 'd/m/Y') . "</option>'";
            }
            return $returnStr;
        }

    }

    public function extractColorData()
    {
        global $wpdb;
        global $field;
		$qryData = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId = '{$field['fieldData']['currentId']}' ",'ARRAY_A' );
		if( count( $qryData )  == 0  )
		{	
			$qryData = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId = '1' ",'ARRAY_A' );	
			
		}
        foreach( $qryData as $key => $value ) foreach( $value as $k => $v) $_POST[$k] = $v;
        $_POST['ADD'] =  'UPDATE' ;
        
        return array();
    }

     public function extractData($idName = '') 
    {
        global $wpdb;
        global $field;
        global $fielValue;
		$classUI = new classUI();
        $postData = $_POST;
        $desData = $thisData = $qryData = array();
        if( $idName == '')$idName = 'ID';
		  
		$thisID = 0;
		$thisAction = '';
		if ( isset( $_GET['logID'] ) )
		{			
			$thisID = (int)$_GET['logID'];
			if( isset( $_GET['actionType'] ) )
				$thisAction = $_GET['actionType'];
			else $thisAction = 'UPDATE';
		}
		else  if ( isset( $_POST['copyForm'] ) && in_array("ADD", $field['fieldData']['currentAction'] ) )
		{			
			$thisID = (int)$_POST['copyForm'];
			$thisAction = 'ADD';
		}
		else if ( isset( $_POST['editForm'] )  && in_array("UPDATE", $field['fieldData']['currentAction'] ) ) 
		{
			$thisID = (int)$_POST['editForm'];
			$thisAction = 'UPDATE';
		}
		else if ( isset( $_POST['deleteForm'] )  && in_array("DELETE", $field['fieldData']['currentAction'] ) ) 
		{
			$thisID = (int)$_POST['deleteForm'];
			$thisAction = 'DELETE';
		}
		else if ( isset( $_POST['restoreForm'] )  && in_array("RESTORE", $field['fieldData']['currentAction'] ) ) 
		{
			$thisID = (int)$_POST['restoreForm'];
			$thisAction = 'RESTORE';
		}
		else if ( isset( $_POST['trashForm'] )  && in_array("TRASH", $field['fieldData']['currentAction'] ) ) 
		{
			$thisID = (int)$_POST['trashForm'];
			$thisAction = 'TRASH';
		}

       if( $thisID > 0 )
	   {
			$showQuery = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$field['fieldData']['pageName']} WHERE {$idName}='{$thisID}' ", "ARRAY_A");
			if (count($showQuery) == 1) {
				$qryData = $showQuery[0];
				
				if( isset( $qryData['jsonData'] ) ) 
				{
					$desData = json_decode( $qryData['jsonData'],true );
					//print_r($desData);
					if( is_array($desData) )foreach( $desData as $key => $value )  $_POST[$key] = $value;
					unset( $qryData['jsonData'] );
				}
				foreach( $qryData as $key => $value )  $_POST[$key] = $value;
				
				 $_POST['ADD'] = $thisAction;
			
				$fielValue['Refresh_Type']  = $_POST['Refresh_Type'] =  $_POST['ADD'];
				echo "<script>$('document').ready(function() {
						$('#submitPageFormModal').modal('show');
						$('body').on('click', '#addNew', function () {
							$('form#submitPageForm select').val('');
							$('form#submitPageForm textarea').val('');
							$('form#submitPageForm input[type!=submit]').val('');
							$('form#submitPageForm input[id=ADD]').val('ADD');

						});
  					});</script>";
			
			}
       }
	   else $classUI->noDataFound("{$thisAction} is not allowed for you...");
        
    }

    public function chargeData()
    {
        global $wpdb;
        $chrgTbl = "{$wpdb->prefix}chargetype";
        $testTbl = "{$wpdb->prefix}reportgroup";
        $chargeD = $wpdb->get_results("SELECT {$chrgTbl}.Charge_Type,
												    {$chrgTbl}.Charge_Amount
										   FROM
											{$chrgTbl}
										  WHERE {$chrgTbl}.isTrash=0
										  ORDER BY {$chrgTbl}.Charge_Type ", 'ARRAY_A');

        $testG = $wpdb->get_results("SELECT {$testTbl}.Group_Name as Charge_Type,
												    {$testTbl}.Report_Charge as Charge_Amount
										   FROM
											{$testTbl}
										  WHERE {$testTbl}.isTrash=0
										  ORDER BY {$testTbl}.Group_Name ", 'ARRAY_A');
        $chargeData = array_merge($chargeD, $testG);
        $classUI = new classUI();
        return $chargeData = $classUI->setAsSelectOption(array("table_data" => $chargeData,
            "option_text" => array("Charge_Type"),
            "option_value" => array("Charge_Type", "Charge_Amount"),
        )
        );
    }
    public function admitList()
    {
        global $wpdb;
        $atbl = "{$wpdb->prefix}admit";
        $ptbl = "{$wpdb->prefix}patient";
        $appointList = $wpdb->get_results("select {$ptbl}.ID,
												  {$ptbl}.Patient_Name,
												  {$ptbl}.Register_Mobile,
												  {$ptbl}.Patient_Age,
												  {$ptbl}.Gender
										  FROM {$atbl}
													LEFT OUTER JOIN {$ptbl} on ({$atbl}.Patient_Name = {$ptbl}.ID)
										  WHERE
												  {$atbl}.isTrash=0
										  ORDER BY
												  {$ptbl}.Patient_Name ", 'ARRAY_A');
        $classUI = new classUI();
        $appointList = $this->setAsSelectOption(array('table_data' => $appointList,
            'option_text' => array('ID'),
            'option_value' => array('Patient_Name', 'Register_Mobile', "Patient_Age", "Gender"),
        )
        );
        return $appointList;
    }
    public function roomList()
    {
        global $wpdb;
        $roomList = $wpdb->get_results("select Room_Code,Room_Name,Ward_Type from {$wpdb->prefix}roomno WHERE isTrash=0 order by Room_Name ", 'ARRAY_A');
        $classUI = new classUI();
        return $roomList = $classUI->setAsSelectOption(array("table_data" => $roomList,
            "option_text" => array("Room_Code"),
            "option_value" => array("Room_Name", "Room_Code", "Ward_Type"),
        )
        );
    }

    public function patientList()
    {
        global $wpdb;
        $patientNameList = $wpdb->get_results("select ID,Account_Name,Register_Mobile,Patient_Age,Register_Mobile,Gender from {$wpdb->prefix}users WHERE isTrash=0 AND Balance_Sheet = 'PATIENT' order by Account_Name ", 'ARRAY_A');
        $classUI = new classUI();
        return $patientNameList = $classUI->setAsSelectOption(array("table_data" => $patientNameList,
            "option_text" => array("ID"),
            "option_value" => array("Account_Name", "Patient_Age", "Gender", "Register_Mobile"),
        )
        );
    }

    public function getPatientData($patientName)
    {
        global $wpdb;
        $patientList = $wpdb->get_results("select ID,Account_Name,Register_Mobile,Patient_Age,Gender from {$wpdb->prefix}users WHERE isTrash=0 AND Balance_Sheet='PATIENT' AND ID='{$patientName}' order by Account_Name ", 'ARRAY_A');
        $classUI = new classUI();
        $patientList = $classUI->setAsSelectOption(array('table_data' => $patientList,
            'option_text' => array('ID'),
            'option_value' => array('Account_Name', 'Register_Mobile', "Patient_Age", "Gender"),
        )
        );
        return $patientList;
    }
    public function productList()
    {
        global $wpdb;
        $productList = $wpdb->get_results("select ID,Product_Name, Manufacturer from {$wpdb->prefix}product WHERE isTrash=0 order by Product_Name", 'ARRAY_A');
        $classUI = new classUI();
        return $productList = $classUI->setAsSelectOption(array("table_data" => $productList,
            "option_text" => array("ID"),
            "option_value" => array("Product_Name", "Manufacturer"),
        )
        );
    }
    public function labTestGroup()
    {
        global $wpdb;
        $reportNameList = $wpdb->get_results("select ID, CONCAT( Group_Name,' -- ',Report_Charge ) as Group_Name from {$wpdb->prefix}reportgroup WHERE isTrash=0 order by Group_Name ", 'ARRAY_A');
        $classUI = new classUI();
        return $reportNameList = $classUI->setAsSelectOption(array('table_data' => $reportNameList,
            'option_text' => array('ID'),
            'option_value' => array('Group_Name'),
        )
        );
    }
    public function getAccountList($atts)
    {
        global $wpdb;
        $tbl = "{$wpdb->prefix}users";

        if( is_array( $atts))
            $balanceSheetArr = "( Balance_Sheet='" . implode("' OR Balance_Sheet='", $atts) . "') AND";
        else 
        $balanceSheetArr = " Account_Name='{$atts}' AND ";
        $accountList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													{$tbl}.Account_Name as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$balanceSheetArr}
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
        $classUI = new classUI();
        return $accountList = $classUI->setAsSelectOption(array('table_data' => $accountList,
            'option_text' => array('ID'),
            'option_value' => array('aName'),
        )
        );
    }
    public function getUserRoleOnlyList($atts)
    {

        global $wpdb;
        $tbl = "{$wpdb->prefix}users";
        if (is_array($atts)) {
            $userRoleArr = "( User_role='" . implode("' OR User_role='", $atts) . "') AND";
            $userRoleList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													{$tbl}.Account_Name  as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$userRoleArr}
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
        } else {

            if ($atts == '') {
                $userRoleList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													{$tbl}.Account_Name as  aName
											  FROM
												  {$tbl}
											  WHERE
											  		User_role != '' AND
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            } else {
                $userRoleList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													{$tbl}.Account_Name  aName
											  FROM
												  {$tbl}
											  WHERE
													{$tbl}.User_Role = '{$atts}'	AND
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            }
        }
        $classUI = new classUI();
        return $accountList = $classUI->setAsSelectOption(array('table_data' => $userRoleList,
            'option_text' => array('ID'),
            'option_value' => array('aName'),
        )
        );
    }

    public function getUserRoleList($atts)
    {

        global $wpdb;
        $tbl = "{$wpdb->prefix}users";
        if (is_array($atts)) {
            $userRoleArr = "( User_role='" . implode("' OR User_role='", $atts) . "') AND";
            $userRoleList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Register_Mobile ) as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$userRoleArr}
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
        } else {

            if ($atts == '') {
                $userRoleList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Register_Mobile ) as  aName
											  FROM
												  {$tbl}
											  WHERE
											  		User_role != '' AND
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            } else {
                $userRoleList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													{$tbl}.Account_Name as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$tbl}.User_Role = '{$atts}'	AND
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            }
        }
        $classUI = new classUI();
        return $accountList = $classUI->setAsSelectOption(array('table_data' => $userRoleList,
            'option_text' => array('ID'),
            'option_value' => array('aName'),
        )
        );
    }
    public function getBalanceSheetList($atts)
    {
        global $wpdb;
        $tbl = "{$wpdb->prefix}users";
        if (is_array($atts)) {
            $balanceSheetArr = "( Balance_Sheet='" . implode("' OR Balance_Sheet='", $atts) . "') AND";
            $accountList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Register_Mobile ) as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$balanceSheetArr}
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
        } else {

            if ($atts == '') {
                $accountList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Register_Mobile ) as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            } else if ($atts == 'PATIENT') {
                $accountList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Patient_Age, ' -- ',{$tbl}.Gender, ' -- ',{$tbl}.Register_Mobile ) as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$tbl}.Balance_Sheet = '{$atts}'	AND
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            } else {
                $accountList = $wpdb->get_results("SELECT
													{$tbl}.ID,
													CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Register_Mobile ) as  aName
											  FROM
												  {$tbl}
											  WHERE
													{$tbl}.Balance_Sheet = '{$atts}'	AND
													{$tbl}.isTrash=0
											  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');
            }
        }

        $classUI = new classUI();
        return $accountList = $classUI->setAsSelectOption(array('table_data' => $accountList,
            'option_text' => array('ID'),
            'option_value' => array('aName'),
        )
        );
    }

    public function accountPatientEmployeeList()
    {
        global $wpdb;
        $acNameList = $newAccountList = array();
        $employeeList = $wpdb->get_results("select ID,CONCAT( Employee_Name,' -- ', Register_Mobile) as aName from {$wpdb->prefix}employee WHERE isTrash=0 order by Employee_Name ", 'ARRAY_A');
        foreach ($employeeList as $key => $val) {
            $acNameList[] = array('ID' => $val['ID'], 'aName' => $val['aName']);
        }

        $patientList = $wpdb->get_results("select ID, CONCAT(Patient_Name,' -- ',Register_Mobile,' -- ',Patient_Age,' -- ',Gender) as aName from {$wpdb->prefix}patient WHERE isTrash=0 order by Patient_Name ", 'ARRAY_A');
        foreach ($patientList as $key => $val) {
            $acNameList[] = array('ID' => $val['ID'], 'aName' => $val['aName']);
        }

        $bstbl = "{$wpdb->prefix}balancesheet";
        $tbl = "{$wpdb->prefix}users";
        $accountList = $wpdb->get_results("SELECT
												{$tbl}.ID,
												CONCAT({$tbl}.Account_Name, ' -- ',{$tbl}.Register_Mobile ) as  aName
										  FROM
											  {$tbl}
										  LEFT OUTER JOIN {$bstbl} ON ({$bstbl}.ID = {$tbl}.Balance_Sheet)
										  WHERE
												{$bstbl}.Balance_Sheet != 'BANK' AND
												{$bstbl}.Balance_Sheet != 'JV' AND
												{$bstbl}.Balance_Sheet != 'CASH' AND
												{$bstbl}.Balance_Sheet != 'CONTRA' AND
												{$tbl}.isTrash=0 AND
												{$bstbl}.isTrash=0
										  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');

        foreach ($accountList as $key => $val) {
            $acNameList[] = array('ID' => $val['ID'], 'aName' => $val['aName']);
        }

        $acNameList = $this->array_sort($acNameList, 'aName', SORT_ASC);
        foreach ($acNameList as $key => $val) {
            $newAccountList[$val['ID']] = $val['aName'];
        }
        return $newAccountList;
    }

    public function inhouseDrList()
    {
        global $wpdb;
        $acNameList = $newAccountList = array();

        $bstbl = "{$wpdb->prefix}balancesheet";
        $tbl = "{$wpdb->prefix}users";
        $accountList = $wpdb->get_results("SELECT
												{$tbl}.ID,
												CONCAT({$tbl}.Account_Name,' -- ', {$tbl}.Register_Mobile ) as  aName
										  FROM
											  {$tbl}
										  LEFT OUTER JOIN {$bstbl} ON ({$bstbl}.ID = {$tbl}.Balance_Sheet)
										  WHERE
												{$tbl}.isTrash=0 AND
												{$bstbl}.Balance_Sheet = 'INHOUSE DOCTOR' AND
												{$bstbl}.isTrash=0
										  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');

        foreach ($accountList as $key => $val) {
            $newAccountList[$val['ID']] = $val['aName'];
        }
        return $newAccountList;
    }

    public function debtorPatientemployeeList()
    {
        global $wpdb;
        $acNameList = $newAccountList = array();
        $employeeList = $wpdb->get_results("select ID,CONCAT( Employee_Name,' -- ',Register_Mobile) as aName from {$wpdb->prefix}employee WHERE isTrash=0 order by Employee_Name ", 'ARRAY_A');
        foreach ($employeeList as $key => $val) {
            $acNameList[] = array('ID' => $val['ID'], 'aName' => $val['aName']);
        }

        $patientList = $wpdb->get_results("select ID, CONCAT(Patient_Name,' -- ',Register_Mobile,Patient_Age,Gender) as aName from {$wpdb->prefix}patient WHERE isTrash=0 order by Patient_Name ", 'ARRAY_A');
        foreach ($patientList as $key => $val) {
            $acNameList[] = array('ID' => $val['ID'], 'aName' => $val['aName']);
        }

        $bstbl = "{$wpdb->prefix}balancesheet";
        $tbl = "{$wpdb->prefix}users";
        $accountList = $wpdb->get_results("SELECT
												{$tbl}.ID,
												CONCAT({$tbl}.Account_Name,' -- ', {$tbl}.Register_Mobile ) as  aName
										  FROM
											  {$tbl}
										  LEFT OUTER JOIN {$bstbl} ON ({$bstbl}.ID = {$tbl}.Balance_Sheet)
										  WHERE
												{$tbl}.isTrash=0 AND
												( {$bstbl}.Balance_Sheet = 'DEBTOR' OR {$bstbl}.Balance_Sheet = 'CREDITOR' ) AND
												{$bstbl}.isTrash=0
										  ORDER BY {$tbl}.Account_Name ", 'ARRAY_A');

        foreach ($accountList as $key => $val) {
            $acNameList[] = array('ID' => $val['ID'], 'aName' => $val['aName']);
        }

        $acNameList = $this->array_sort($acNameList, 'aName', SORT_ASC);
        foreach ($acNameList as $key => $val) {
            $newAccountList[$val['ID']] = $val['aName'];
        }
        return $newAccountList;
    }

    public function getDischargePatientList()
    {global $wpdb;
        $tbl = "{$wpdb->prefix}admit";
		$atbl = "{$wpdb->prefix}appointment";
		$dtbl = "{$wpdb->prefix}discharge";
        $ptbl = "{$wpdb->prefix}users";

        $admitPatientList = $wpdb->get_results("SELECT
														{$tbl}.ID AS ID,
														{$ptbl}.Account_Name AS Account_Name,
														{$ptbl}.Patient_Age,
														{$ptbl}.Gender,
														CONCAT ( DATE_FORMAT( {$tbl}.Admit_Date , '%d-%m-%Y') , '[Admit]' ) as Admit_Date,
														CONCAT ( DATE_FORMAT( {$dtbl}.Discharge_Date , '%d-%m-%Y') , '[Discharge]' ) as Discharge_Date
												  FROM {$dtbl}
												    LEFT OUTER JOIN {$tbl} ON ({$tbl}.ID = {$dtbl}.Patient_Name)
													LEFT OUTER JOIN {$atbl} ON ({$atbl}.ID = {$tbl}.Patient_Name)
													LEFT OUTER JOIN {$ptbl} ON ({$ptbl}.ID = {$atbl}.Patient_Name)
												  WhERE
												  {$dtbl}.isTrash=0 
												   ORDER BY
													{$ptbl}.Account_Name", 'ARRAY_A');

        $classUI = new classUI();
        $admitPatientList = $classUI->setAsSelectOption(array("table_data" => $admitPatientList,
            "option_text" => array("ID"),
            "option_value" => array("Account_Name", "Patient_Age", "Admit_Date", "Discharge_Date"),
        )
        );
        return $admitPatientList;
    }

    
    public function getAdmitPatient()
    {global $wpdb;

        $admitTbl = "{$wpdb->prefix}admit";
		$appointTbl = "{$wpdb->prefix}appointment";
        $userTbl = "{$wpdb->prefix}users";
       // $dtbl = "{$wpdb->prefix}discharge";

        $appointPatientList = $wpdb->get_results("SELECT
														{$admitTbl}.ID,
														{$userTbl}.Account_Name,
														{$userTbl}.Patient_Age,
														{$userTbl}.Gender,
														{$userTbl}.Register_Mobile,
														CONCAT ( DATE_FORMAT( {$admitTbl}.Admit_Date , '%d-%m-%Y') , '[Admit]' ) as Admit_Date
												  FROM {$admitTbl}
												  JOIN {$appointTbl} ON ({$appointTbl}.ID = {$admitTbl}.Patient_Name)
												    JOIN {$userTbl} ON ({$userTbl}.ID = {$appointTbl}.Patient_Name)
												  WhERE
												  {$userTbl}.Balance_Sheet='PATIENT' AND
												  {$admitTbl}.isTrash=0
												   ORDER BY {$admitTbl}.Admit_Date, {$userTbl}.Account_Name", 'ARRAY_A');
        $classUI = new classUI();
        $appointPatientList = $classUI->setAsSelectOption(array("table_data" => $appointPatientList,
            "option_text" => array("ID"),
            "option_value" => array("Account_Name", "Patient_Age", "Register_Mobile","Admit_Date"),
        )
        );
        return $appointPatientList;
    }

    public function  getAppointPatient()
    {
        global $wpdb;

        $tbl = "{$wpdb->prefix}appointment";
        $ptbl = "{$wpdb->prefix}users";
       // $dtbl = "{$wpdb->prefix}discharge";

        $appointPatientList = $wpdb->get_results("SELECT
														{$tbl}.ID,
														{$ptbl}.Account_Name,
														{$ptbl}.Patient_Age,
														{$ptbl}.Gender,
														{$ptbl}.Register_Mobile,
														CONCAT( DATE_FORMAT( {$tbl}.Appoint_Date, '%d-%m-%Y') ,'[Appoint]' ) as Appoint_Date
												  FROM {$tbl}
													LEFT OUTER JOIN {$ptbl} ON ({$ptbl}.ID = {$tbl}.Patient_Name)
												  WhERE
												  {$ptbl}.Balance_Sheet='PATIENT' AND
												  {$tbl}.isTrash=0
												   ORDER BY
													{$ptbl}.Account_Name", 'ARRAY_A');
        $classUI = new classUI();
        $appointPatientList = $classUI->setAsSelectOption(array("table_data" => $appointPatientList,
            "option_text" => array("ID"),
            "option_value" => array("Account_Name", "Patient_Age", "Register_Mobile","Appoint_Date"),
        )
        );
        return $appointPatientList;
    } 
    public function getPatientList()
    {global $wpdb;
        $admitPatientList = $wpdb->get_results("select 
                                {$wpdb->prefix}users.ID,
                                {$wpdb->prefix}users.Account_Name,
                                {$wpdb->prefix}users.Register_Mobile,
                                {$wpdb->prefix}users.Patient_Age, 
                                {$wpdb->prefix}users.Register_Mobile,
                                {$wpdb->prefix}users.Gender 
                                from 
                                {$wpdb->prefix}users 
                                WHERE {$wpdb->prefix}users.isTrash=0 AND  {$wpdb->prefix}users.Balance_Sheet='PATIENT' 
                                order by {$wpdb->prefix}users.Account_Name ", 'ARRAY_A');
        $classUI = new classUI();
        $admitPatientList = $classUI->setAsSelectOption(array("table_data" => $admitPatientList,
            "option_text" => array("ID"),
            "option_value" => array("Account_Name", "Patient_Age", "Gender", "Register_Mobile"),
        )
        );
        return $admitPatientList;
    }

    public function getRoomPriceList($date)
    {
        global $wpdb;
        $rntbl = "{$wpdb->prefix}roomno";
        $rttbl = "{$wpdb->prefix}wardtype";
        $roomQry = $wpdb->get_results("SELECT
												{$rntbl}.Room_Code,
												{$rntbl}.Room_Name,
												B1.Ward_Name,
												B1.Ward_Charge,
												B1.Doctor_Charge,
												B1.Nursing_Charge
										  FROM
												{$rntbl}
										LEFT  JOIN ( 
															SELECT *  
															FROM (
																SELECT {$rttbl}.Ward_Code,{$rttbl}.Ward_Name, {$rttbl}.Ward_Charge, {$rttbl}.Doctor_Charge, {$rttbl}.Nursing_Charge FROM {$rttbl}
																 WHERE {$rttbl}.Action_Date <= '{$date}'  
																ORDER BY {$rttbl}.Action_Date DESC
															) AS BP1
															GROUP BY BP1.Ward_Code 
															
														) B1 ON ( B1.Ward_Code = {$rntbl}.Ward_Type  )

										  Where
												{$rntbl}.isTrash = 0 
										  ORDER BY {$rntbl}.Room_Name", "ARRAY_A");
        $roomChargeList = array();
        foreach ($roomQry as $key => $val) {
            $roomChargeList[$val['Room_Code']] = array("Room_Name" => $val['Room_Name'],
                "Ward_Name" => $val['Ward_Name'],
                "Ward_Charge" => $val['Ward_Charge'],
                "Doctor_Charge" => $val['Doctor_Charge'],
                "Nursing_Charge" => $val['Nursing_Charge'],
            );
        }
        return $roomChargeList;

    }

   

}
