<?PHP
class classDate 
{
	public function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		// The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
	public function setSalaryDate( $thisEmp, $thisDate, $thisVal )
	{
		
		global $employeeSalaryData;
		$nextMonth = date("Y-m-d",strtotime( date("Y-m-d", strtotime($thisDate))." ,+1 month"));
		$maxTime = strtotime( $_POST['End_Date'] );
		$thisTime = strtotime( $thisDate );
		if( $thisTime < $maxTime )
		{
			if( !isset( $employeeSalaryData[$thisEmp][$nextMonth] ) )
			{
				$employeeSalaryData[$thisEmp][$nextMonth] = $thisVal;
				$this->setSalaryDate( $thisEmp, $nextMonth, $thisVal );
			}
		}
							
	}
	public function firstLastDateOfMonth($Date)
	{
			$f_dt = strtotime( date("Y-m-d", strtotime($Date))." ,first day of this month");
			$fincialDate['firstDate'] = date("Y-m-d",$f_dt);
			$t_dt = strtotime( date("Y-m-d", strtotime($Date)).", last day of this month");
			$fincialDate['lastDate'] = date("Y-m-d",$t_dt);
			return $fincialDate;
	}
	public function getAllDateRange($fDate,$lDate)
	{
		$returnArray = array();
		$start    = (new DateTime($fDate))->modify('first day of this month');
		$end      = (new DateTime($lDate))->modify('first day of next month');
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($start, $interval, $end);

		foreach ($period as $dt) {
			//echo $dt;
			 array_push( $returnArray, strtotime($dt->format("Y-m-d")) ); //$dt->format("Y-m-d") );
		}

		return $returnArray;
	}
	
	
	public function getMonths($fDate,$lDate)
    {
        $date = strtotime ( $lDate );
        $day =  date("d", $date );
        $dmonth =  date("m", $date );
        $month =  date("n", $date );
        $firstYear =  $lastYear = $year  = date("Y", $date);
        
        if( $month < 4 )
        {   
            $firstYear =  $firstYear - 1;
            $lastYear = $lastYear;
        }else
        {
            $firstYear =  $firstYear;
            $lastYear = $lastYear + 1;
        }
        $fincialDate['firstDate'] = "{$firstYear}-04-01";
        $fincialDate['lastDate'] = "{$lastYear}-03-31";
        return $fincialDate;
    }
	
	
	public function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
		  
		// Declare an empty array 
		$array = array(); 
		  
		// Variable that store the date interval 
		// of period 1 day 
		$interval = new DateInterval('P1D'); 
	  
		$realEnd = new DateTime($end); 
		$realEnd->add($interval); 
	  
		$period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
	  
		// Use loop to store date into array 
		foreach($period as $date) {                  
			$array[] = $date->format($format);  
		} 
	  
		// Return the array elements 
		return $array; 
	} 

	public function getFincialDate($lDate)
    {
        

        $date = strtotime ( $lDate );
        $day =  date("d", $date );
        $dmonth =  date("m", $date );
        $month =  date("n", $date );
        $firstYear =  $lastYear = $year  = date("Y", $date);
        
        if( $month < 4 )
        {   
            $firstYear =  $firstYear - 1;
            $lastYear = $lastYear;
        }else
        {
            $firstYear =  $firstYear;
            $lastYear = $lastYear + 1;
        }
        $fincialDate['firstDate'] = "{$firstYear}-04-01";
        $fincialDate['lastDate'] = "{$lastYear}-03-31";
        return $fincialDate;
    }
    
    public function getFincialMonth($lDate)
    {
        

        $date = strtotime ( $lDate );
        $year  = date("Y", $date);
        $fincialMonths = array();
        for($i = 4 ; $i < 16; $i++ )
        {
            if( $i > 12) $j = $i - 12;
            else $j = $i;
            
            if( $i == 13 ) $year++;
            $d = cal_days_in_month(CAL_GREGORIAN,$j,$year);
            $monthName = date("M Y", strtotime( "{$year}-{$j}-01" ) );
            $fincialMonths[$monthName] = $d;
        }
       // print_r($fincialMonths);
        return $fincialMonths;
    }
    
    public function getallFincialMonth($lDate)
    {
        $date = strtotime ( $lDate );
        $year  = date("Y", $date);
        $fincialMonths = array();
        for($i = 4 ; $i < 16; $i++ )
        {
            if( $i > 12) $j = $i - 12;
            else $j = $i;
            
            if( $i == 13 ) $year++;
            
            $first_date_find = strtotime(date("Y-m-d", strtotime( "{$year}-{$j}-01" ) ) . ", first day of this month");
            $firstDate = date("Y-m-d",$first_date_find);
            
            $last_date_find = strtotime(date("Y-m-d", strtotime( "{$year}-{$j}-01" ) ) . ", last day of this month");
            $lastDate = date("Y-m-d",$last_date_find);
            
            $monthName = date("M Y", strtotime( "{$year}-{$j}-01" ) );
            $fincialMonths[$monthName] = array("firstDate" => $firstDate, "lastDate" => $lastDate );
        }
       // print_r($fincialMonths);
        return $fincialMonths;
    }
	public function dateConvert($lDate)
    {
        $date = implode("-",array_reverse(explode("-",$lDate)));
       // print_r($fincialMonths);
        return $date;
    }
 
}

?>