<?PHP
class classValidator
{
	private function _checkMinLength($dataValidationValue, $dataToBeChecked)
	{
		if( strlen( $dataToBeChecked ) <  $dataValidationValue  )
			return "This field value must have atleast {$dataValidationValue} Characters and/or Digits and/or any Special Keys";
		else return true;
	}
	
	private function _checkMaxLength($dataValidationValue, $dataToBeChecked)
	{	
		if( strlen( $dataToBeChecked ) > $dataValidationValue  )
			return "This field value must be less than {$dataValidationValue} Characters and/or Digits and/or any Special Keys";
		else return true;
	}
	private function _checkLength($dataValidationValue, $dataToBeChecked)
	{	
		if( strlen( $dataToBeChecked ) != $dataValidationValue )
			return "This field value must have {$dataValidationValue} Characters and/or Digits and/or any Special Keys";
		else return true;
	}
	
	private function _checkInt( $dataToBeChecked)
	{	
		//$dataToBeChecked  = $dataToBeChecked + 0; 
		if( !is_int( $dataToBeChecked ) )
			return "This field must have interger value [Exa : 0 , 59 , 898 ]";
		else return true;
	}
	 
	private function _checkPassStrength ( $dataToBeChecked )
	{
		$uppercase = preg_match('@[A-Z]@', $dataToBeChecked);
		$lowercase = preg_match('@[a-z]@', $dataToBeChecked);
		$number    = preg_match('@[0-9]@', $dataToBeChecked);
		//$specialChars = preg_match('@[^\w]@', $dataToBeChecked);
		if(!$uppercase || !$lowercase || !$number )
			return "Must contain at least one number and one uppercase and lowercase letter, and  8-12 characters";
		else return true;
	}
	private function _checkFloat( $dataToBeChecked)
	{	
		//$dataToBeChecked  = $dataToBeChecked + 0; 
		if( !is_float( $dataToBeChecked ) )
			return "This field  must have float value  [Exa : 0.01 , 59 , 898.98 ].";
		else return true;
	}
	private function _checkMaxValue($dataValidationValue, $dataToBeChecked)
	{	//echo  "{$dataToBeChecked} >=  {$dataValidationValue}";
		if( (float)$dataToBeChecked >  (float)$dataValidationValue)
			return "This field value must be less than {$dataValidationValue}.";
		else return true;
	}
	private function _checkMinValue( $dataValidationValue, $dataToBeChecked)
	{	
		if( (float)$dataToBeChecked <  (float)$dataValidationValue )
			return "This field value must be greater than {$dataValidationValue}.";
		else return true;
	}
	
	private function _checkMultiArray($dataValidationValue, $dataToBeChecked)
	{	
		if( is_array( $dataToBeChecked ) )
		{
			$thisReturn = true;
			foreach( $dataToBeChecked as $key => $value )
			{
				if( !array_key_exists(  $value, $dataValidationValue ) )
					$thisReturn =  false;
				
			}
			if($thisReturn) return true;
			else return "Data you have selected is not from the system... Cheating Huh..."; 

		}
		else if( array_key_exists(  $dataToBeChecked, $dataValidationValue ) )
				return true;
			else return "Data you have selected is not from the system... Cheating Huh..."; 
	}
	

	private function _checkFincialDate( $dataToBeChecked )
	{
		if (date('Y-m-d', strtotime($dataToBeChecked ) ) !=  $dataToBeChecked) 
		{
			return 'Date you have selected is not in date formate.';
		}
		else
		{
			$timeCurrent = strtotime($dataToBeChecked);;
			$minYear = substr($_SESSION['fincial_year'],0,2);
			$maxYear = substr($_SESSION['fincial_year'],2,2);
			$minDate = strtotime("20{$minYear}-04-01");
			$maxDate = strtotime("20{$maxYear}-03-31");
			
			if ( $minDate < $timeCurrent && $timeCurrent > $maxDate )
			{
				return 'Date you have selected is out of fincial year.';
				
			}else return true;
		}
	}
	private function _getQueryArray($arr)
	{
		$array = array();
		foreach($arr['table_data'] as $value )
		{  
			$arrayt = json_decode( $value['des'] , true); 
			if( isset($value['ID']) ) $arrayt['ID'] =  (trim($value['ID']));
			$i=0;
			$val = '';
			foreach( $arr['option_value'] as $d)
			{	$val .= ($i == 0) ?  (trim($arrayt[$d])) : " - ". (trim($arrayt[$d])) ;
				$i++;
			}
			$array[] = $val;
		}
		//print_r($array); exit;
		return $array;
	}
	
	private function _checkQuery($dataValidationValue, $dataToBeChecked)
	{	
		if( in_array( $dataToBeChecked ,
					   $this->_getQueryArray( array( 'table_data' =>   $dataValidationValue[0] , 
												     'option_value' => $dataValidationValue[1]
													)
											) 
					 )
		  )
		  {
			  return true;
		  }
		  else return "Data you have selected is not from the select box... Cheating Huh...";
		
	}
	
	public function checkValidation()
	{	
		$postData = $_POST;
		global $errorMsg;
		global $field;

		$errorMessage = true;
		foreach( $field as $key => $value )
		{
			if( isset ( $field[$key]['field'] ) )
			{
				foreach ( $field[$key]['field'] as $fieldNamee => $fieldArrayy )
				{
					
				//$hasMinLength = isset ( $fieldArray['hasMinLength'] ) ? true : false;
				//echo $fieldName;
					foreach( $fieldArrayy as $fieldName  => $fieldArray )
					{
						if( isset ( $postData[$fieldName] ) )
						{
							$thisCount = is_array( $postData[$fieldName] ) ? count( $postData[$fieldName] ) : strlen( $postData[$fieldName] );
							if( $thisCount	> 0 || isset( $fieldArray['fieldValue']['required'] ) )
							{
								if( isset(  $fieldArray['fieldValue']  ) )
								{

									foreach( $fieldArray['fieldValue'] as $fieldValidator => $validatorValue )
									{	
										
											if ( $fieldValidator == 'minlength' )
												$result = $this->_checkMinLength( $validatorValue , $postData[$fieldName] ); 
											else if ( $fieldValidator== 'maxlength' )	
												$result = $this->_checkMaxLength( $validatorValue, $postData[$fieldName] ); 
											else if ( $fieldValidator == 'min' )	
												$result = $this->_checkMinValue( $validatorValue ,$postData[$fieldName] ); 
											else if ( $fieldValidator == 'max' )		
												$result = $this->_checkMaxValue( $validatorValue, $postData[$fieldName] ); 
											// else if ( $fieldValidator == 'optionList' )							
											// 	$result = $this->_checkMultiArray( $validatorValue , $postData[$fieldName] );
												

											if( isset ( $result )  )
											{
												if( is_string( $result ) )
												{
													$fldName = isset( $fieldArray['displayName'] ) ? $fieldArray['displayName'] : ucwords( str_replace("_", " ",$fieldName) );
													$errorMsg[] = array(  "<strong>" . $fldName  . "!</strong>  " . $result ,false ) ;
													$errorMessage = false;
												}		
											}
											unset($result);
										
										
									}
									if( isset( $fieldArray['optionList']  ) && $fieldArray['fieldType'] == 'select' )
									{
										//print_r($fieldArray['optionList']);
										$result = $this->_checkMultiArray( $fieldArray['optionList'] , $postData[$fieldName] );
										if( isset ( $result )  )
										{
											if( is_string( $result ) )
											{
												$fldName = isset( $fieldArray['displayName'] ) ? $fieldArray['displayName'] : ucwords( str_replace("_", " ",$fieldName) );
												$errorMsg[] = array(  "<strong>" . $fldName  . "!</strong>  " . $result ,false ) ;
												$errorMessage = false;
											}		
										}
										unset($result);
									}
									
								}
								
								
							}
								
						}
					}
				}
			}
		}
		
		return $errorMessage;
		//return false;
	}
	public function checkPageName()
	{
		global $wpdb;
		global $errorMsg;
		$isSuccess = true; 
		if( $_POST['ADD'] == 'ADD' )
		{
			$myposts = $wpdb->get_results( "SELECT post_type FROM $wpdb->posts WHERE post_type='page' AND post_title = '".wp_strip_all_tags( $_POST['Page_Name'] )."'", "ARRAY_A" );
			if( count ( $myposts ) > 0)
			{
				$isSuccess = false;
				$errorMsg[] = array("Post Page already exists..", false );
				$isSuccess = true;
			}
		}else
		{
			$myposts = $wpdb->get_results( "SELECT post_type FROM $wpdb->posts WHERE ID != '{$_POST['Page_Id']}' AND  post_type='page' AND post_title = '".wp_strip_all_tags( $_POST['Page_Name'] )."'",  "ARRAY_A" );
			if( count ( $myposts ) > 0)
			{
				$isSuccess = false;
				$errorMsg[] = array("Post Page already exists..", false );
			}
		}
		if( $_POST['ADD'] == 'ADD' )
		{
			$table = $wpdb->query("SHOW TABLES LIKE '{$wpdb->prefix}{$_POST['Page_Name']}'");
			if( $table > 0)
			{
				$isSuccess = false;
				$errorMsg[] = array("Table already exists..", false );
			}
		}
		if( $_POST['ADD'] == 'ADD' )
			$checkPage = $wpdb->get_results("SELECT Page_Name FROM {$wpdb->prefix}pagename where Page_Name='{$_POST['Page_Name']}'","ARRAY_A");
		else 
			$checkPage = $wpdb->get_results("SELECT Page_Name FROM {$wpdb->prefix}pagename where ID != '{$_POST['ID']}' AND Page_Name='{$_POST['Page_Name']}'","ARRAY_A");
		if( count ($checkPage) > 0)
		{
			$isSuccess = false;
			$errorMsg[] = array("Page name already exists", false );
		}
		
		return $isSuccess;
	}
	
	
}
