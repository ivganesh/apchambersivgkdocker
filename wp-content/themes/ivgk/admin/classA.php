<?PHP
class classAction {

	public function oneSignalNotification($atts){
        $content      = array(
        	"en" => $atts['msg']
    	);
      
    $hashes_array = array();
    array_push($hashes_array, array(
        "id" => "like-button",
        "text" => "Like",
        "icon" => "https://apchambers.in/app/wp-content/uploads/2022/04/apccif.jpg",
        "url" => "https://apchambers.in"
    ));
   
    $fields = array(
        'app_id' => $atts['app_id'],
        'included_segments' => array(
            'All'
        ),
        'data' => array(
            "foo" => "bar"
        ),
        'contents' => $content
    );
    
    echo $fields = json_encode($fields);
    
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic '.$atts['appKey']
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    curl_close($ch);
    print($response);
    return $response;
      
}
  
  public function createAccountUser()
	{
		global $errorMsg;
    	global $wpdb;
		$isContinue = true;
		if ($_POST['ADD'] == "ADD") {
			$checkUserName =  $_POST['Mobile_No'];
              $mobileid = username_exists($checkUserName);
			
				$emailId = email_exists($_POST['Email_ID']);
			
            if ($mobileid == false && $emailId == false) {
                $userdata = array(
                    'user_login' => $checkUserName,
					'user_email' => $_POST['Email_ID'],
                    'user_pass' => '12345678',
                );
                $userId = wp_insert_user($userdata);
                if ($userId) {
                    $_POST['ID'] = $userId;
                    $_POST['ADD'] = 'UPDATE';
                    $errorMsg[] = array("User created", true);
                  
                  
					$u = new WP_User( $userId );
					$u->remove_role( 'subscriber' );
					$u->add_role( $_POST['User_Role'] );
                  
                  	update_user_meta($userId, 'is_activated', true);
 					$role = array ('Mobile_No' =>  $_POST['Mobile_No'], 'Email_ID' => $_POST['Email_ID'] , 'User_Role' => $_POST['User_Role'],'is_Activated'=>1);
                  	$roleUpdate = array("ID" => $userId);
                  	$result = $wpdb->update("{$wpdb->prefix}users",$role,$roleUpdate);
                  
                   if( $result )
                    {
                    $errorMsg[] = array("User Role Assign User",  true);
                    
                  }else
                    {
                    $errorMsg[] = array("Failed to assign user role",  false);
                    
                  }
					
                } else {
                    $isContinue = false;
                    $errorMsg[] = array("User not created",  false);
                }
            } else {
                $isContinue = false;
                $errorMsg[] = array("User Name  OR E-Mail already exists...", false);
            }
        }
		else{
			global $wp_roles;
			$all_roles = $wp_roles->roles;
			$u = new WP_User( $_POST['ID'] );
			$u->remove_role( 'subscriber' );
			foreach( $all_roles as $key => $value )
			{ 
				$u->remove_role( $key );
			}
			$u->add_role( $_POST['User_Role'] );
		}			
		return $isContinue;
	}
	
	
	
	public function checkPurchaseSaleProduct()
	{
		global $wpdb;
		global $errorMsg;
		$isContinue = true;


		$batchTbl = "{$wpdb->prefix}batchno";
        $productTbl = "{$wpdb->prefix}product";
		$productArr = array();
        $productList = $wpdb->get_results("SELECT
												{$batchTbl}.Batch_No,
												{$productTbl}.Product_Name as productName,
												{$productTbl}.ID,

											FROM {$batchTbl}
											LEFT JOIN {$productTbl} ON ( {$productTbl}.ID= {$batchTbl}.Product_Name)
											GROUP BY {$batchTbl}.Batch_No", 'ARRAY_A');	
		foreach($productList as $key => $value)
		{
			$productArr[$value['productName']][$value['Batch_No']] = $value['ID'];
		}
		foreach($_POST as $key => $value)
		{
			if( preg_match( "/Product_Name_/", $key ) && $value != '' )
			{
				$value = trim($value);
				$thisId = explode("Product_Name_",$key);
				$thisId = (int)$thisId[1];
				$batchNo = trim( $_POST["Batch_No_{$thisId}"] );
				if ( !isset( $productArr[$value][$batchNo] ) )
				{
					$errorMsg[] = array("{$value} + {$batchNo} is not found...",  false);
					$isContinue = false;
				}
			}
		}									
		return $isContinue;
	}

	public function addManufacturer()
	{
		global $wpdb;
		global $errorMsg;
		global $field;
		$isContinue = true;

		$getManu = $wpdb->get_results("SELECT Manufacturer from {$wpdb->prefix}manufacturer where Manufacturer='{$_POST['Manufacturer']}'", "ARRAY_A");
		if( count( $getManu ) == 0 )
		{

			$result = $wpdb->insert("{$wpdb->prefix}manufacturer",
						  array('userId'=> $field['fieldData']['currentId'],
						  "Manufacturer" => $_POST['Manufacturer'] )
						 );
			if ($result) {
				$errorMsg[] = array("Manufacturer has been added", true);
			} else {
				$isContinue = false;
				$errorMsg[] = array("Failed to add Manufacturer",  false);
			}			 
		}
		return $isContinue;
	}
	
	public function userRegistrationCreate()
	{
		global $errorMsg;
      	global $wpdb;
		$isContinue = true;
		$checkUserName =  $_POST['Mobile_No'];
		$mobileid = username_exists($checkUserName);
			
		$emailId = email_exists($_POST['Email_ID']);
			
       if ($mobileid == false && $emailId == false) 
	   {
                $userdata = array(
                    'user_login' => $checkUserName,
					'user_email' => $_POST['Email_ID'],
                    'user_pass' => $_POST['password'],
                );
                $userId = wp_insert_user($userdata);
                if ($userId) {
                    $_POST['ID'] = $userId;
					update_user_meta($userId, 'is_activated', true);
 					$role = array ('Mobile_No' => $checkUserName, 'Email_ID' => $_POST['Email_ID'] , 'User_Role' => 'User','is_Activated'=>0);
                  $roleUpdate = array("ID" => $userId);
                  $result = $wpdb->update("{$wpdb->prefix}users",$role,$roleUpdate);
                    $errorMsg[] = array("User created", true);
                  if( $result )
                    {
                    $errorMsg[] = array("Please Contact APChambers to activate your account",  true);
                    
                  }else
                    {
                    $errorMsg[] = array("Failed to assign user role",  false);
                    
                  }
 					$u = new WP_User( $userId );
					$u->remove_role( 'subscriber' );
 					$u->add_role( 'User' );
					
                } else {
                    $isContinue = false;
                    $errorMsg[] = array("User not created",  false);
                }
            } else {
                $isContinue = false;
                $errorMsg[] = array("User Name  OR E-Mail already exists...", false);
            }
        		
		return $isContinue;
	}
	
	
	
	public function createOtherFile()
	{
		global $errorMsg;
		$isContinue = true;
		//DatauserProfile_
		if ( $_FILES ) 
		{ 
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			$i = 0;
			$t = 0 ;
			foreach( $_FILES as $key => $value )
			{ 	
				
				$files = $_FILES[$key];
				if( $files['name'] != '' )
				{ 	$t++;
					$thisId = explode("_", $key);
					
					$thisId = (int)$thisId[1]; 
					if($thisId > 0)
					{
						
							$file = array( 
								'name' => $files['name'],
								'type' => $files['type'], 
								'tmp_name' => $files['tmp_name'], 
								'error' => $files['error'],
								'size' => $files['size']
							 ); 

							$upload_overrides = array( 'test_form' => false );
							$movefile = wp_handle_upload($file,$upload_overrides); 
							
							if ( $movefile && ! isset( $movefile['error'] ) ) {
								$_POST[$key] = $movefile["url"];
								$i++;
								
							} else {
								$isContinue = false; 
								
							}
						
						
					}
					
				}
							
			}
			if( $i == $t )
			{
				$errorMsg[] = array("All files uploaded successfully" , true );
			}
			else{
				$errorMsg[] = array("Only {$i} / {$t} files uploaded" , false ); 	
			}
			
		}
		
		
		
		return $isContinue;
	}
	
	public function createUploadFile()
	{
		global $errorMsg;
		$isContinue = true;
		
		if ( $_FILES ) 
		{
			foreach($_POST as $key => $value )
			{
				if( preg_match('/DatauserProfile_/',$key) && strlen($value) > 30 )
				{
					$thisId = explode('DatauserProfile_',$key); 
					$thisId = $thisId[1];
					$imageData = $_POST[$key];
					if( $imageData != '' )
					{
						$decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$imageData));

						$upload_dir = wp_upload_dir(); 
						
						$fileType = explode( 'data:image/',$imageData);
						$fileType = explode( ';',$fileType[1]);
						$fileType =  $fileType[0]; 
						 
						$filename = $_POST['ID'].'.'.$fileType;
						$hashed_filename = md5($filename. microtime() )  . '_' . $filename;
						$image_upload = file_put_contents( $upload_dir['basedir'] ."/". $hashed_filename, $decoded );
						
					
						if ( $image_upload  ) {
							$_POST["userProfile_{$thisId}"] =  $upload_dir['baseurl'] ."/". $hashed_filename;
							$errorMsg[] = array("Avatar uploaded successfully" , true );
						} else {
							$isContinue = false; 
							$errorMsg[] = array("Failed to upload Avatar" , false ); 	
						}
					}
				}
			}

		}
		
		return $isContinue;
	}

	public function addToEmployeeAttend()
	{
		global $errorMsg;
		global $field;
		global $wpdb;
		$insertedEntry = $allEntry = $isSuccess = 0;

		$insertData = array();
		$insertData['Attend_Date'] = $_POST['Attend_Date'];
		$insertData['userId'] = $field['fieldData']['currentId'];
		$insertData['added'] = current_time('mysql');

		foreach($_POST['Employee_Name'] as $key => $value )
		{
			$insertData['Employee_Name'] = $value;
			$insertData['Attendance'] = $_POST['Attendance'][$key];
			$result = $wpdb->insert("{$wpdb->prefix}employeeattend",$insertData);
			if(!$result) $isSuccess++;
			else $insertedEntry++;
			$allEntry++;
		}
		if( $isSuccess > 0)
		{
			$errorMsg[] = array("Only {$insertedEntry}/{$allEntry} successfully added...",false);
		}
		else{
			$errorMsg[] = array("All Attendance successfully added...",true);
		}
	}

	public function tableToMaster()
	{
		global $field; 
		global $fieldValue; 
		global $errorMsg;
		$isContinue = true;
		if( isset( $_POST['ADD']) )
		{
			if( preg_match("/ADD/", $_POST['ADD']   ) )
			{	
				if( in_array("ADD", $field['fieldData']['currentAction'] ) )
				{
					if( isset($_POST['Order_No'] ) ) {  $isContinue =  $this->getOrderNo(); }
					if( $isContinue  )if( isset($_POST['Bill_No'] ) ) $isContinue = $this->getBillNo();
					if( $isContinue  )
					{					
						$isContinue = $this->addToMaster();
						
					}
				}else
				{
					$isContinue = false;
					$errorMsg[] = array( "ADD action is not allowed" ,  false  );
				}
			}
			else if( preg_match("/UPDATE/", $_POST['ADD']   ) ) 
			{
				if( in_array("UPDATE", $field['fieldData']['currentAction'] ) )
				{
					$isContinue = $this->updateToMaster();
				}else
				{
					$isContinue = false;
					$errorMsg[] = array( "UPDATE action is not allowed" ,  false  );
				}

			}
			else if( preg_match("/DELETE/", $_POST['ADD']   ) ) 
			{
				if( in_array("DELETE", $field['fieldData']['currentAction'] ) )
				{
					$isContinue = $this->deleteToMaster();
				}else
				{
					$isContinue = false;
					$errorMsg[] = array( "DELETE action is not allowed" ,  false  );
				}
				
			}
			else if( preg_match("/TRASH/", $_POST['ADD']   ) ) 
			{
				if( in_array("TRASH", $field['fieldData']['currentAction'] ) )
				{
					$isContinue = $this->trashToMaster();
				}else
				{
					$isContinue = false;
					$errorMsg[] = array( "TRASH action is not allowed" ,  false  );
				}
				
			}
		  	else if( preg_match("/RESTORE/", $_POST['ADD']   ) ) 
			{
				if( in_array("RESTORE", $field['fieldData']['currentAction'] ) )
				{
					$isContinue = $this->restoreToMaster();
				}else
				{
					$isContinue = false;
					$errorMsg[] = array( "RESTORE action is not allowed" ,  false  );
				}
			}
			else { 
					$errorMsg[] = array( "Your submit action is not allowed" ,  false  );
					$isContinue = false;
			}
		}
		else 
		{
			$errorMsg[] = array( "Your submit action is not defined" ,  false  );
			$isContinue = false;
		}
		
		if( isset( $field['fieldData']['setAfterAction']  ) )
		{
			foreach( $field['fieldData']['setAfterAction']  as $key => $value )
			{
				$fieldValue[$key] = $_POST[$key];
			}
		}
		if($isContinue)
			$this->insertUserLogs($_POST['ID'], $_POST['ADD']);
		return $isContinue;
	}
	public function insertUserLogs($post_id ,$post_action, $post_slug = '' )
	{
		
		global $wpdb;
		global $field;
		
		if( $post_slug == '')
		{
			global $post;
			$post_slug = isset( $post->post_name ) ?  $post->post_name  : '';
		}
		if( $post_id == '')
		{
			$post_id = isset( $_POST['ID'] ) ? $_POST['ID'] : 0;  
		}
		if( $post_action == '')
		{
			$post_action = isset( $_POST['ADD'] ) ? $_POST['ADD'] : 'undefined'; 
		}
		
		if(  isset($field['fieldData']['currentId']) && 
			 isset($_POST['ID']) &&   isset($_POST['ADD']) )
		{
			$thisIP = $this->getUserIP();
			if( $post_slug != '' && $post_slug != 'custom-page' && $post_slug != 'custom-field' && 
				$field['fieldData']['currentId'] != ''  &&
				(int)$_POST['ID'] != 0 &&  $_POST['ADD'] != '' && $thisIP != '' )
			 
			{ 
				$insertLog = array();
				$insertLog['Action_Table'] = $post_slug;
				$insertLog['Action_ID'] = $post_id;
				$insertLog['Action_Type'] = $post_action;
				$insertLog['Action_User'] =  $field['fieldData']['currentId'] ;
				$insertLog['Action_IP'] = $thisIP;
				$insertLog['Action_Date'] = date('Y-m-d');
				$insertLog['Action_Time'] = date('H:i:s');
				$wpdb->insert("{$wpdb->prefix}userlogs", $insertLog);
			}
		}
	}
	public function getUserIP() 
	{
		$returnIP = '';
		if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
				$addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
				$returnIP =  trim($addr[0]);
			} else {
				$returnIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
		}
		else {
			$returnIP = $_SERVER['REMOTE_ADDR'];
		}
		return $returnIP;
	}

	public function addToMaster()
	{
		global $wpdb;
		global $field;
		global $errorMsg;
		$uniqueQuery= '';
		$error = array();
		$tableName = "{$wpdb->prefix}{$field['fieldData']['pageName']}";
		if( isset ( $field['fieldData']['uniqueField'] ) )
		{
			if( count ( $field['fieldData']['uniqueField'] ) > 0 )
			{
				$uniqueQuery = $this->_setInsertUniqueQuery();
			}
		}
		$shownData = $this-> _getShownData();
		$noofrows = strlen( $uniqueQuery ) > 0 ? $wpdb->get_var("select COUNT(*) from {$tableName} where  {$uniqueQuery} ") : 0 ; 
		if( $noofrows > 0) 
		{
			$errorMsg[] =  array( $field['fieldData']['errorPrefix'] . " already exits. {$shownData}" ,  false  );
			return false;
		}
		else 
		{	
			$insertData = $this->_setInsertData();
			//print_r($insertData);
			$result = $wpdb->insert($tableName , $insertData );
			//echo $wpdb->last_error; 
			//echo $wpdb->print_error();
		
			if($result)
			{  	
				$_POST['ID'] = $wpdb->insert_id;
				$_POST['insertedID'] = $result;
				$errorMsg[] =  array( $field['fieldData']['errorPrefix'] . " Successfully Added. {$shownData}" ,  true  );  
				return true;
			}
			else 
			{				
				$errorMsg[] =  array( $field['fieldData']['errorPrefix'] . " Failed to Add. {$shownData}" ,   false  );
				return false;
			}
		}
	}
	
	private function _getShownData()
	{
		global $field;
		$returnStr = '';
		$i = 0;
		if( isset( $field['fieldData']['shownAfterAction']  ) )
		{
			foreach( $field['fieldData']['shownAfterAction'] as $key => $value)
			{
				$returnStr .= $i == 0 ? str_replace("_"," ", $key). " : {$_POST[$key]}" : " | ".str_replace("_"," ", $key). " : {$_POST[$key]}";
				$i++;
			}
		}
		return $returnStr;
	}
	
	public function updateToMaster()
	{
		global $wpdb;
		global $field;
		global $errorMsg;
		$uniqueQuery = '';
		$tableName = "{$wpdb->prefix}{$field['fieldData']['pageName']}";
		if( isset ( $field['fieldData']['uniqueField'] ) )
		{
			if( count ( $field['fieldData']['uniqueField'] ) > 0 )
			{
				$uniqueQuery = $this->_setInsertUniqueQuery();
			}
		}
		$shownData = $this-> _getShownData();
		$noofrows = strlen( $uniqueQuery ) > 0 ? $wpdb->get_var("select COUNT(*) from {$tableName} where   ID!='{$_POST['ID']}'  AND	 {$uniqueQuery} ") : 0;
		if( $noofrows > 0 ) {
			$errorMsg[] =  array( $field['fieldData']['errorPrefix'] . " already exists. {$shownData}" , false  );
			return false;
		}
		else
		{
			$insertData = $this->_setInsertData();
			$updateQuery = array('ID' => $_POST['ID'] );
			//print_r($insertData);
			$result = $wpdb->update($tableName, $insertData , $updateQuery );
			//echo $wpdb->last_error; 
			//echo $wpdb->print_error();
			if($result)
			{  
				$_POST['insertedID'] = $result;
				$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Successfully Updated. {$shownData}" , true  );  
				return true;
			}
			else  {
				$errorMsg[] = array( $field['fieldData']['errorPrefix']  . " Failed to Update. {$shownData}" ,  false  );	
				return false;
			}
		}
	}
	public function trashToMaster()
	{
		global $wpdb;
		global $field;
		global $errorMsg;
		$tableName = "{$wpdb->prefix}{$field['fieldData']['pageName']}";
	
		$insertData = array("isTrash" => 1 );
		$updateQuery = array('ID' => $_POST['ID'] );
		$shownData = $this-> _getShownData();
		$result = $wpdb->update($tableName, $insertData , $updateQuery );
		if($result)
		{  
			$_POST['insertedID'] = $result;
			$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Successfully Trashed. {$shownData}" , true  );  
			return true;
		}
		else  {
			$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Failed to Trash. {$shownData}" ,  false  );	
			return false;
		}
		
	}
	public function deleteToMaster()
	{
		global $wpdb;
		global $field;
		global $errorMsg;
		$tableName = "{$wpdb->prefix}{$field['fieldData']['pageName']}";
	
		$insertData = array("isTrash" => 1 );
		$updateQuery = array('ID' => $_POST['ID'] );
		$shownData = $this-> _getShownData();
		$result = $wpdb->delete($tableName,  $updateQuery );
		if($result)
		{  
			$_POST['insertedID'] = $result;
			$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Successfully Deleted. {$shownData}" , true  );  
			return true;
		}
		else  {
			$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Failed to Delete. {$shownData}" ,  false  );	
			return false;
		}
		
	}
	public function restoreToMaster()
	{
		global $wpdb;
		global $field;
		global $errorMsg;
		$tableName = "{$wpdb->prefix}{$field['fieldData']['pageName']}";
	
		$insertData = array("isTrash" => 0 );
		$updateQuery = array('ID' => $_POST['ID'] );
		$shownData = $this-> _getShownData();
		$result = $wpdb->update($tableName, $insertData , $updateQuery );
		if($result)
		{  
			$_POST['insertedID'] = $result;
			$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Successfully Trashed. {$shownData}" , true  );  
			return true;
		}
		else {
			$errorMsg[] =  array( $field['fieldData']['errorPrefix']  . " Failed to Trash. {$shownData}" ,  false  );	
			return false;
		}
		
	}




	public function _setInsertData()
	{
		global $field;
		
		$insertData = array();
		$desData = array();
		
		foreach( $field as $key => $value )
		{
			foreach( $value as $kee => $vaa )
			{
				if($kee == 'field')
				{
					
					foreach( $vaa as $ke => $val)
					{
						foreach( $val as $k => $v)
						{
								//echo $k."</br>";
								/*
								if( $v['fieldType'] == 'checkbox' )
								{
									foreach( $v['optionList'] as $check_k => $check_v)
									{
										if( isset($_POST[$check_k]) ) $insertData[$check_k] = $check_v;
										else $insertData[$check_k] = '';
									}
								}
								*/	
								if( isset ( $v['fieldValue']['type'] ) &&  isset( $_POST[$k] )  )  
								{
									
									if( $v['fieldValue']['type'] != 'submit' &&  $v['fieldValue']['type'] != 'button' ) $insertData[$k] = $_POST[$k];	
									
								}
								else if( isset( $_POST[$k] ) ) $insertData[$k] = htmlentities( trim($_POST[$k]));
							
						}
					}
					
				}
			}
		}
		 
		
		
		$insertData['userId'] = $field['fieldData']['currentId'];
		$currentDate = date('Y-m-d H:i:s');
		if( preg_match("/ADD/", $_POST['ADD'] ) ) $insertData['added'] = $currentDate; 	 
		if( preg_match("/UPDATE/", $_POST['ADD'] ) ) $insertData['updated'] = $currentDate; 	
		
		if( isset( $field['fieldData']['insertJsonData'] ) )		
		if( $field['fieldData']['insertJsonData'] == 'YES')
		{
			foreach ( $_POST as $key => $value  )
			$desData[$key] = htmlentities(trim($_POST[$key]));
				
			if( isset( $desData['Refresh_Type'] ) )  { unset($desData['Refresh_Type']);   }
			if( isset( $desData['_wpnonce'] ) )  { unset($desData['_wpnonce']);   }
			if( isset( $desData['_wp_http_referer'] ) ) {  unset($desData['_wp_http_referer']);   }
			if( isset( $desData['action'] ) ) {  unset($desData['action']);   }
			if( isset( $desData['Action'] ) ) {  unset($desData['Action']);   }
			if( isset( $desData['ADD'] ) ) {  unset($desData['ADD']);   }
			if( count( $desData ) > 0 ) $insertData['jsonData'] = json_encode($desData);
		}
		
		
		if( isset( $insertData['Refresh_Type'] ) )  { unset($insertData['Refresh_Type']);   }
		if( isset( $insertData['ID'] ) )  { unset($insertData['ID']);   }
		if( isset( $insertData['_wpnonce'] ) )  { unset($insertData['_wpnonce']);   }
		if( isset( $insertData['_wp_http_referer'] ) ) {  unset($insertData['_wp_http_referer']);   }
		if( isset ( $field['fieldData']['pageNm'] ) )
		{
			if( $field['fieldData']['pageNm'] == "Contra")
			{
				if( isset( $insertData['Account_Name_2'] ) ) {  unset($insertData['Account_Name_2']);   }
				if( isset( $insertData['Account_Name_1'] ) ) {  unset($insertData['Account_Name_1']);   }
				if( isset( $insertData['DR_Amt_1'] ) ) {  unset($insertData['DR_Amt_1']);   }
				if( isset( $insertData['CR_Amt_1'] ) ) {  unset($insertData['CR_Amt_1']);   }
				if( isset( $insertData['Cheque_No_1'] ) ) {  unset($insertData['Cheque_No_1']);   }
				if( isset( $insertData['Order_No_1'] ) ) {  unset($insertData['Order_No_1']);   }
				if( isset( $insertData['Description_1'] ) ) {  unset($insertData['Description_1']);   }
			}
		}
		return $insertData;	
	}
	private function _setInsertUniqueQuery()
	{
		global $field;
		$queryReturn = '';
		foreach( $field['fieldData']['uniqueField'] as $key => $value )
			if( $value == 'AND' )$queryReturn .=  " AND ";
			else if ( $value == "OR") $queryReturn .=  " OR " ;
				else if( $value == "openBracket") $queryReturn .= " ( " ;
					else if ( $value == "closeBracket" )  $queryReturn .=  " ) " ;
						else $queryReturn .=  " {$key} = '" . trim($_POST[$key])."' " ;
		return $queryReturn;
	}

	
	public function getOrderNo()
	{
		global $errorMsg;
		$isContinue = false;
		if( isset( $_POST['Bill_Date'] ) ) $getBillDate = $_POST['Bill_Date'];
		else if( isset( $_POST['Discharge_Date'] ) ) $getBillDate = $_POST['Discharge_Date'];
		else if( isset( $_POST['Appoint_Date'] ) ) $getBillDate = $_POST['Appoint_Date'];
		else if( isset( $_POST['Admit_Date'] ) ) $getBillDate = $_POST['Admit_Date'];
		else if( isset( $_POST['IPD_Date'] ) ) $getBillDate = $_POST['IPD_Date'];
		else if( isset( $_POST['OPD_Date'] ) ) $getBillDate = $_POST['OPD_Date'];
		//else if( isset( $_POST['Refer_Date'] ) ) $getBillDate = $_POST['Refer_Date'];
		else if( isset( $_POST['Payment_Date'] ) ) $getBillDate = $_POST['Payment_Date'];
		if( isset( $getBillDate ) && $getBillDate > '2020-01-01')
		{
			$classDate = new classDate();
			$thisDate = $classDate->getFincialDate($getBillDate);
			$getBillDate = $thisDate['firstDate'];
		
			
			global $wpdb;
			$getData = $wpdb->get_results("select ID,BillNo,OrderNo from {$wpdb->prefix}orderbillno WHERE Bill_Date='{$getBillDate}'" ,'ARRAY_A' ) ;	
			if ( count($getData) > 0 )
			{
				foreach( $getData as $key => $value )
				{
					$getOrderNo = $value["OrderNo"];
					$getOrderNo++;
					$insertData = $updateQuery = array();
					$insertData["OrderNo"] = $getOrderNo;
					$updateQuery['ID']  = $value['ID'];
					$result = $wpdb->update("{$wpdb->prefix}orderbillno" , $insertData , $updateQuery );
					
					if($result)
					{
						$_POST['Order_No'] = $getOrderNo;
						$isContinue = true;
					} 
					else { 
						$errorMsg[] = array("Cant get ".str_replace("_"," ", $type).".. Retry Again",false);
					}
				}
				
			}
			else{
				$errorMsg[] = array("Order No not set for fincial date ".$getBillDate.'. Set Order No from Company -> Order BillNo',false);
			}
		}
		else $errorMsg[] = array("Cant get date for Order No",false);
		return $isContinue;

	}
	
	public function getBillNo()
	{
		global $errorMsg;
		$isContinue = false;
		if( isset( $_POST['Bill_Date'] ) ) $getBillDate = $_POST['Bill_Date'];
		else if( isset( $_POST['Discharge_Date'] ) ) $getBillDate = $_POST['Discharge_Date'];
		else if( isset( $_POST['Appoint_Date'] ) ) $getBillDate = $_POST['Appoint_Date'];
		else if( isset( $_POST['Admit_Date'] ) ) $getBillDate = $_POST['Admit_Date'];
		else if( isset( $_POST['IPD_Date'] ) ) $getBillDate = $_POST['IPD_Date'];
		else if( isset( $_POST['OPD_Date'] ) ) $getBillDate = $_POST['OPD_Date'];
		//else if( isset( $_POST['Refer_Date'] ) ) $getBillDate = $_POST['Refer_Date'];
		else if( isset( $_POST['Payment_Date'] ) ) $getBillDate = $_POST['Payment_Date'];
		
		if( isset( $getBillDate ) && $getBillDate > '2020-01-01')
		{
			$classDate = new classDate();
			$thisDate = $classDate->getFincialDate($getBillDate);
			$getBillDate = $thisDate['firstDate'];
		
			
			global $wpdb;
			$getData = $wpdb->get_results("select ID,BillNo,OrderNo from {$wpdb->prefix}orderbillno WHERE Bill_Date='{$getBillDate}'" ,'ARRAY_A' ) ;	
			if ( count($getData) > 0 )
			{
				foreach( $getData as $key => $value )
				{
					$getOrderNo = $value["BillNo"];
					$getOrderNo++;
					$insertData = $updateQuery = array();
					$insertData["BillNo"] = $getOrderNo;
					$updateQuery['ID']  = $value['ID'];
					$result = $wpdb->update("{$wpdb->prefix}orderbillno" , $insertData , $updateQuery );
					
					if($result)
					{
						$_POST['Bill_No'] = $getOrderNo;
						$isContinue = true;
					} 
					else { 
						$errorMsg[] = array("Cant get ".str_replace("_"," ", $type).".. Retry Again",false);
					}
				}
				
			}
			else{
				$errorMsg[] = array("Bill No not set for fincial date ".$getBillDate.'. Set Bill No from Company -> Order BillNo',false);
			}
		}
		else $errorMsg[] = array("Cant get date for Bill No",false);
		return $isContinue;

	}

	
}
?>