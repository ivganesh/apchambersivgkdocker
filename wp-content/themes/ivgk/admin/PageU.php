<?php


add_shortcode( "MobileLoginPage" , "MobileLoginPage" );
function MobileLoginPage($atts)
{	 
	global $loginError;
	global $wpdb;
	
		
    if ( !is_user_logged_in() )
	{    
				
		global $wp;	
		
		$ApiKey = 'g2rKQXHCMm33iPL7Nk+j1P9aMoVRgrB/nPAnGULa+rs=';
		$ClientId = 'd9746c7b-61a6-47bc-ab98-398e778af22f'; 
		$SenderId = 'IVGKOT';
		$screen = 'first';
		
						
							
		if( isset( $_POST['ganerate_otp']) )
		{
			
				
					
			if( !empty( $_POST['user_login'] ) )
			{
				//echo "select ID,Mobile from {$wpdb->prefix}users where  Mobile='{$_POST['user_login']}' OR user_login='{$_POST['user_login']}' OR user_email='{$_POST['user_login']}' ";
				$userQry = $wpdb->get_results("select ID,Mobile_No from {$wpdb->prefix}users where  Mobile_No='{$_POST['user_login']}'","ARRAY_A");				
				if( count($userQry) == 1)
				{
					$thisData = $userQry[0];
					$MobileNumbers = $thisData['Mobile_No'];
					$_POST['userId'] = $thisData['ID'];
					$otp = mt_rand(1000,9999);
					$result = $wpdb->update("{$wpdb->prefix}users",array("otp"=> $otp ), array("ID"=> $thisData['ID']));
					if( $result )
					{
						$memberName = 'member';
						$userName = 'dummy';
                      $Message = urlencode("{$otp} IS YOUR OTP TO LOGIN INTO APCHAMBERS , THANK YOU FOR USING IVGK SERVICES");
						
	
						$curl = curl_init();
						curl_setopt_array($curl, array(
						  CURLOPT_URL => "https://api.mylogin.co.in/api/v2/SendSMS?ApiKey={$ApiKey}&ClientId={$ClientId}&SenderId={$SenderId}&Message={$Message}&MobileNumbers=91{$MobileNumbers}",
						 CURLOPT_RETURNTRANSFER => true, 
						  CURLOPT_ENCODING => "",
						  CURLOPT_MAXREDIRS => 10,
						  CURLOPT_TIMEOUT => 30,
						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						  CURLOPT_CUSTOMREQUEST => "GET",
						  CURLOPT_POSTFIELDS => "",
						  CURLOPT_HTTPHEADER => array(
							"content-type: application/json"
						  ),
						));

						$response = curl_exec($curl);
						$err = curl_error($curl);
						curl_close($curl);
						
						if ($err) {
						 $loginError =  "Failed to send OTP... Try again..." ;
						 $screen = 'first';
						} else {
							
						 $loginError =  "OTP sent to {$MobileNumbers}";
						 $screen = 'second';
						}
					}else{
						$loginError =  "Failed to send OTP... Try again..." ;
						 $screen = 'first';
					}
				
				}else {
					$loginError = "User not found for {$_POST['user_login']}";
					$screen = 'first';
				}
			}else {
				$loginError = 'User Name | Email | Mobile cant be empty';
				$screen = 'first';
			}	
				
			
			
			
		}else if( isset( $_POST['validate_otp']) )
		{
			if( !empty( $_POST['OTP'] ) )
			{	//echo "select otp,ID from {$wpdb->prefix}users where otp = '{$_POST['OTP']}' and ID='{$_POST['userId']}' ";
				$userQry = $wpdb->get_results("select otp,ID from {$wpdb->prefix}users where otp = '{$_POST['OTP']}' and ID='{$_POST['userId']}' ","ARRAY_A");				
				if( count ( $userQry ) == 1 )
				{
					$user_id = $userQry[0]['ID'];
					$user = get_userdata($user_id);
					wp_set_current_user($user_id, $user_id);
					wp_set_auth_cookie($user_id);
					do_action( 'wp_login',$user->user_login);
					$loginError = 'OTP validated';
					$screen = 'third';					
					header('Location: ' . home_url() );
		
					
					
					
				}
				else{
					$loginError = 'Invalid or Expired OTP';
					$screen = 'second';
				}
			}else{
				$loginError = 'OTP cant be blank';
				$screen = 'second';
			}
		} else 
		{
			$screen = 'first';
			
		}


		if($screen == 'first')
		{
  ?> 
	<div class="row">	 
	
	
		<div class="col-sm-12 col-md-6 mx-auto">
			<div class="card">
				<div class="card-header">Login</div>
				<div class="card-body">
					<form name="loginform"  autocomplete="off"  id="submitPageForm" action="<?PHP home_url( $wp->request ); ?>" method="post">
						<div class="row">
						<?PHP
							
							if( !empty( $loginError  ) ) 
							{
								echo '<div class="col-12">
										<div class="alert alert-danger  text-center">'.
										$loginError
										.'</div>
									</div>';
							}
						?>
							<div class="col-12 mb-2">
								<div class="form-outline">
									<label for="user_login">Enter Your Register Mobile No<font color="red">*</font></label>
									<input required type="text" autofocus name="user_login" placeholder="Enter Your Register Mobile No" maxlength="100" minlength="3" id="user_login" class="form-control" >
								</div>
							</div>
							
							<div class="col-6">
							</div>
							<div class="col-6">
									<input type="submit" id="-LastFocus-" class="form-control" value="GET OTP" name="ganerate_otp">
							</div>
							<div class="col-12">
								<a tabindex="-1" href="<?PHP echo home_url() .'/lost-password/' ; ?>" title="Sapling Tech"> Lost | Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
			<div>	
		</div>
		
		
	</div>

		  
<?PHP
		}
		else if( $screen == 'second')
		{
			?> 
	<div class="row">	 
		<div class="col-sm-12 col-md-12 mx-auto">
		<div class='alert alert-info text-center'> Dont refresh this page..
		</div>
		</div>
	
		<div class="col-sm-12 col-md-6 mx-auto">
			<div class="card">
				<div class="card-header">OPT Validate</div>
				<div class="card-body">
					<form name="loginform"  autocomplete="off"  id="submitPageForm" action="<?PHP home_url( $wp->request ); ?>" method="post">
						<input type='hidden' name='userId' value='<?=$_POST['userId']; ?>'>
						<div class="row">
						<?PHP
							
							if( !empty( $loginError  ) ) 
							{
                              $class =  preg_match('/OTP sent to/',$loginError )  ? 'alert-success' : 'alert-danger'; 
								echo '<div class="col-12">
										<div class="alert '.$class.'  text-center">'.
										$loginError
										.'</div>
									</div>';
							}
						?>
							<div class="col-12">
								<div class="form-group">
									<label for="user_login">OTP<font color="red">*</font></label>
									<input required type="text" autofocus required name="OTP"  maxlength="6" minlength="4" id="OTP" class="form-control" >
								</div>
							</div>
							
							<div class="col-6">
							</div>
							<div class="col-6">
									<input type="submit" id="-LastFocus-" class="form-control" value="Validate" name="validate_otp">
							</div>

						</div>
					</form>
				</div>
			<div>	
		</div>
		
		
	</div>

		  
<?PHP
		}else if( $screen == 'third')
		{
			?> 
	
						<?PHP
							
							if( !empty( $loginError  ) )  
							{
								echo '<div class="row"><div class="col-12">
										<div class="alert alert-danger  text-center">'.
										$loginError
										.'</div>
									</div></div>';
							}
						?>
							

						
					

		  
<?PHP
		}
		
    }
	else
	{
		

						
		echo '<div class="row">
				<div class="col-12">
					<div class="alert alert-danger text-center">Your are already logged-in</div>
				</div>
			</div>';
	}
}




add_shortcode( "UserRegistrationPage" , "UserRegistrationPage" );
function UserRegistrationPage($atts)
{	 
	global $errorMsg;
	global $wpdb;
	if( ! session_id() ) session_start();
		
    if ( !is_user_logged_in() )
	{    
				
		global $wp;	
		
		if( isset( $_POST['REGISTER']) )
		{
			
				
			if( isset($_SESSION['answer']) )
          	{

            	if( $_SESSION['answer'] == $_POST['answer'])
            	{		
                    if( !empty( $_POST['Mobile_No'] ) && !empty( $_POST['Email_ID'] ) && !empty( $_POST['password'] )  )
                    {
                        if( $_POST['password']  == $_POST['confirm_password']  )
                        {

                          $classAction = new classAction();
                          $isSuccess = $classAction->userRegistrationCreate();


                        }else {
                             $errorMsg[] =  array( "User not found for {$_POST['user_login']}",false);
                            $screen = 'first';
                        }
                    }else {
                         $errorMsg[] =  array( 'Email | Mobile | password  cant be empty',false);
                        $screen = 'first';
                    }	
                }else {
                         $errorMsg[] =  array( 'Invalid Answer',false);
                        $screen = 'first';
                    }
            }else {
                         $errorMsg[] =  array( 'Invalid Answer',false);
                        $screen = 'first';
                    }
				
			
			
			
		}
		
		if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }
      
      	// if ($_POST) {
        // if (isset($_POST['Mobile_No'])) {
        //         if ($_SESSION['answer'] == $_POST['answer']) {
        //             $user_login = sanitize_text_field($_POST['user_login']);
        //             $loginError = retrieve_password_sapling($user_login);
        //         } else {
        //             $loginError = "ERROR : Your answer is incoreect.";
        //         }
        //     }
        // }

        if ($_POST) {
            if (isset($_POST['Mobile_No']) && isset($_POST['user_login']) && isset($_POST['answer'])) {
                if ($_SESSION['answer'] == $_POST['answer']) {
                    $user_login = sanitize_text_field($_POST['user_login']);
                    $loginError = retrieve_password_sapling($user_login);
                } else {
                    $loginError = "ERROR : Your answer is incorrect.";
                }
            }
        }
      
      	$digit1 = mt_rand(1, 10);
        $digit2 = mt_rand(1, 10);
        $math = "$digit1 + $digit2 = ?";
        $_SESSION['answer'] = $digit1 + $digit2;

		
			 
  ?> 
   
	<div class="row mt-4">	 
	
	
		<div class="col-sm-12 col-md-6 mx-auto">
			<div class="submitPageFormCard card mb-2 d-print-none">
				<div class="card-header">User Registration</div>
				<div class="card-body">
					<form name="loginform"  autocomplete="off"  id="submitPageForm" action="<?PHP home_url( $wp->request ); ?>" method="post">
						<div class="row">
						<?PHP
							

						?>
							<div id="C_Mobile_No" class="col-sm-6 form-outline"><label class="control-label">Mobile No *</label><div class="input-group"><input class="form-control " required="" name="Mobile_No" id="Mobile_No" type="number" maxlength="10" minlength="10" max="9999999999" min="4999999999" step="1" value=""> </div></div>
							<div id="C_Email_ID" class="col-sm-6 form-outline"><label class="control-label">Email ID *</label><div class="input-group"><input class="form-control " required="" name="Email_ID" id="Email_ID" type="email" maxlength="100" minlength="10" value=""> </div></div>
							<div id="C_Email_ID mt-2" class="col-sm-6 form-outline"><label class="control-label">Password *</label><div class="input-group"><input class="form-control " required="" name="password" id="password" type="password" maxlength="20" minlength="5" value=""> </div></div>
							<div id="C_Email_ID mt-2" class="col-sm-6 form-outline"><label class="control-label">Confirm Password *</label><div class="input-group"><input class="form-control " required="" name="confirm_password" id="confirm_password" type="password" maxlength="20" minlength="5" value=""> </div></div>
							<div class="col-12">
								&nbsp;
							</div>
                          	<div class="col-12 mb-2 form-outline">
								<label class="control-label"><?PHP echo $math; ?> *</label>
									<input required type="number" min="-20" max="20" step="1" maxlength="3" name="answer" placeholder="Enter Your Answer" id="answer" class="form-control" value="" >
							</div>
							
							<div class="col-6">
                              <div class="col-12">
								<a tabindex="-1" href="<?PHP echo home_url() . '/login-2/'; ?>"> Already have an account?</a>
							</div>
							</div>
							<div class="col-6">
									<input type="submit" id="-LastFocus-" class="form-control" value="REGISTER" name="REGISTER">
							</div>
						
						</div>
					</form>
				</div>
			<div>	
		</div>
		
		
	</div>

		  
    <?PHP
            
            
    ?>
		<script>
        var _rulesString = {};
		var _messagesString = {};
    </script>
		<?
    }
	else
	{
		

						
		echo '<div class="row">
				<div class="col-12">
					<div class="alert alert-danger text-center">Your are already logged-in</div>
				</div>
			</div>';
	}
}


add_shortcode("AlertPage", "AlertPage");
function AlertPage($atts)
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

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        

        $field['fieldData']['pageName'] = $pagename;
         $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";

        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
       // // $field['fieldData']["callBack"] = "referTrn";
        //'' => ' - Select Font Style - ',

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 100,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );
        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                "maxlength" => 30,
            ),

        );
        $page_arr = array(
            'ID',
            'Success_BackGround_Color',
            'Success_Font_Color',
            'Success_Font_Family',
            'Success_Font_Size',
            'Success_Font_Weight',
            'Success_Border_Size',
            'Success_Border_Color',
            'Success_Corner_Radius',

            'Fail_BackGround_Color',
            'Fail_Font_Color',
            'Fail_Font_Family',
            'Fail_Font_Size',
            'Fail_Font_Weight',
            'Fail_Border_Size',
            'Fail_Border_Color',
            'Fail_Corner_Radius',

            'Info_BackGround_Color',
            'Info_Font_Color',
            'Info_Font_Family',
            'Info_Font_Size',
            'Info_Font_Weight',
            'Info_Border_Size',
            'Info_Border_Color',
            'Info_Corner_Radius',

        );

        foreach ($page_arr as $key => $value) 
        {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } else if (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Success_BackGround_Color']['Success_BackGround_Color']['rowOpen'] = true;
        $field[1]['field']['Info_Corner_Radius']['Info_Corner_Radius']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        );
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check","alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $isSuccess = $classAction->tableToMaster();
				if($isSuccess && isset($_GET['multiTasking']))
				{
					echo '<script>window.close();</script>';
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

        echo $classCSS->getCssData('applying');
        $classMysql->extractColorData();
        $classUI->echoForm('no');
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php
        //$classUI->searchFormAndData();

        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId = '1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
            //$adminColor[$key] = $value;

        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode("ButtonPage", "ButtonPage");

function ButtonPage($atts)
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

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();   
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();

        $field['fieldData']['pageName'] = $pagename;
         $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
        // $field['fieldData']["callBack"] = "referTrn";
        //$isUpdated = true;

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );

        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                'class' => 'form-control',
            ),
        );

        $page_arr = array(
            'ID',
            'Submit_Button_BackGround_Color',
            'Submit_Button_Font_Color',
            'Submit_Button_Font_Family',
            'Submit_Button_Font_Size',
            'Submit_Button_Font_Weight',
            'Submit_Button_Border_Size',
            'Submit_Button_Border_Color',
            'Submit_Button_Corner_Radius',
            'Submit_Button_Hover_BackGround_Color',
            'Submit_Button_Hover_Font_Color',
            'Submit_Button_Hover_Font_Family',
            'Submit_Button_Hover_Font_Size',
            'Submit_Button_Hover_Font_Weight',

            'Button_BackGround_Color',
            'Button_Font_Color',
            'Button_Font_Family',
            'Button_Font_Size',
            'Button_Font_Weight',
            'Button_Border_Size',
            'Button_Border_Color',
            'Button_Corner_Radius',
            'Button_Hover_BackGround_Color',
            'Button_Hover_Font_Color',
           

        );

        foreach ($page_arr as $key => $value) {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } else if (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Submit_Button_BackGround_Color']['Submit_Button_BackGround_Color']['rowOpen'] = true;
        $field[1]['field']['Button_Hover_Font_Color']['Button_Hover_Font_Color']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        );
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $classAction->tableToMaster();
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

        echo $classCSS->getCssData('applying');
        $classMysql->extractColorData();


        $classUI->echoForm('no');
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId='1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
            //$adminColor[$key] = $value;

        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode("fieldLabelPage", "fieldLabelPage");

function fieldLabelPage($atts)
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

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();

        $field['fieldData']['pageName'] = $pagename;
         $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
        // $field['fieldData']["callBack"] = "referTrn";

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );

        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                'class' => 'form-control',
            ),
        );

        $page_arr = array(
            'ID',

            'Label_BackGround_Color',
            'Label_Font_Color',
            'Label_Font_Family',
            'Label_Font_Size',
            'Label_Font_Weight',

            'Field_BackGround_Color',
            'Field_Font_Color',
            'Field_Font_Family',
            'Field_Font_Size',
            'Field_Font_Weight',
            'Field_Border_Size',
            'Field_Border_Color',
            'Field_Corner_Radius',

            'Field_Hover_Border_Color',
			'Placeholder_Font_Color',
			'Placeholder_Opacity',
            
        );

        foreach ($page_arr as $key => $value) {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } else if (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Label_BackGround_Color']['Label_BackGround_Color']['rowOpen'] = true;
		$field[1]['field']['Placeholder_Opacity']['Placeholder_Opacity']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        ); 
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $classAction->tableToMaster();
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

        echo $classCSS->getCssData('applying');
        $classMysql->extractColorData();

        $classUI->echoForm('no');
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php
        
        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId = '1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode("GeneralPage", "GeneralPage");

function GeneralPage($atts)
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

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        

        $field['fieldData']['pageName'] = $pagename;
         $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
        // $field['fieldData']["callBack"] = "referTrn";

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );
        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                'class' => 'form-control',
            ),
        );

        $page_arr = array(
            'ID',

            'Body_BackGround_Color',
            'Body_Font_Color',
            'Body_Font_Size',
            'Body_Font_Family',

            'Form_BackGround_Color',
            'Form_Font_Color',
            'Form_Font_Size',
            'Form_Font_Family',
            'Form_Border_Size',
            'Form_Border_Color',
            'Form_Corner_Radius',

            'Form_Header_BackGround_Color',
            'Form_Header_Font_Color',
            'Form_Header_Font_Size',
            'Form_Header_Font_Family',

            'Search_BackGround_Color',
            'Search_Font_Color',
            'Search_Font_Size',
            'Search_Font_Family',
            'Search_Border_Size',
            'Search_Border_Color',
            'Search_Corner_Radius',

            'Search_Header_BackGround_Color',
            'Search_Header_Font_Color',
            'Search_Header_Font_Size',
            'Search_Header_Font_Family',

            

            
        );

        foreach ($page_arr as $key => $value) {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } else if (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Body_BackGround_Color']['Body_BackGround_Color']['rowOpen'] = true;
        $field[1]['field']['Search_Header_Font_Family']['Search_Header_Font_Family']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        );
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $classAction->tableToMaster();
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

        echo $classCSS->getCssData('applying');
        $classMysql->extractColorData();


        $classUI->echoForm('no');
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId='1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
            //$adminColor[$key] = $value;

        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode("InvoicePrintPage", "InvoicePrintPage");

function InvoicePrintPage($atts)
{
    global $menuCheck; global $menuAction;
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
        global $errorMsg;
        global $fontStyleList;
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        

        $field['fieldData']['pageName'] = $pagename;
         $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
		if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];

        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
        // $field['fieldData']["callBack"] = "referTrn";

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );
        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                'class' => 'form-control',
            ),
        );

        $page_arr = array(
            'ID',

            'Print_Font_Size',
            'Print_Font_Family',
            'Print_Font_Weight',

            'Firm_Header_Font_Size',
			'Firm_Header_Font_Family',
            'Firm_Header_Font_Weight',

            'Firm_Label_Font_Size',
			'Firm_Label_Font_Family',
            'Firm_Label_Font_Weight',
			
			'Firm_Text_Font_Size',
			'Firm_Text_Font_Family',
            'Firm_Text_Font_Weight',

        
            'Account_Header_Font_Size',
			'Account_Header_Font_Family',
            'Account_Header_Font_Weight',

            'Account_Label_Font_Size',
			'Account_Label_Font_Family',
            'Account_Label_Font_Weight',
			
			'Account_Text_Font_Size',
			'Account_Text_Font_Family',
            'Account_Text_Font_Weight', 

            'Invoice_Label_Font_Size',
			'Invoice_Label_Font_Family',
            'Invoice_Label_Font_Weight',
			
			 'Invoice_Text_Font_Size',
			'Invoice_Text_Font_Family',
            'Invoice_Text_Font_Weight',
			
			'Table_Header_Font_Size',
			'Table_Header_Font_Family',
            'Table_Header_Font_Weight',
			
			'Table_Row_Font_Size',
			'Table_Row_Font_Family',
            'Table_Row_Font_Weight',

            'Terms_Label_Font_Size',
			'Terms_Label_Font_Family',
            'Terms_Label_Font_Weight',
			
			'Terms_Text_Font_Size',
			'Terms_Text_Font_Family',
            'Terms_Text_Font_Weight',

        ); 

        foreach ($page_arr as $key => $value) {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } elseif (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else if (preg_match("/Disable_Select_Box_Down_Arrow/", $value)) {
                $field[1]['field'][$value][$value] = $fldDownArrow;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Print_Font_Size']['Print_Font_Size']['rowOpen'] = true;
        $field[1]['field']['Terms_Text_Font_Weight']['Terms_Text_Font_Weight']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => $md6lg6,
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        );
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => $md6lg6,
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $classAction->tableToMaster();
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

        echo $classCSS->getCssData('applying');
        //$classMysql->extractColorDagetCssDatata();
		$classMysql->extractColorData();

        $classUI->echoForm('no');
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId='1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
            //$adminColor[$key] = $value;

        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}
add_shortcode("NavbarPage", "NavbarPage");

function NavbarPage($atts)
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

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        
        $field['fieldData']['pageName'] = $pagename;
         $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
        // $field['fieldData']["callBack"] = "referTrn";

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );
        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                'class' => 'form-control',
            ),
        );

        $page_arr = array(
            'ID',
            'Top_Menu_BackGround_Color',
            'Top_Menu_Font_Color',
            'Top_Menu_Font_Family',
            'Top_Menu_Font_Size',
            'Top_Menu_Font_Weight',
            'Top_Menu_Hover_Font_Color',
            'Top_Menu_Box_Shadow_Color',
            'Top_Menu_Box_Shadow_Size',

            'Sidebar_Menu_BackGround_Color',
            'Sidebar_Menu_Font_Color',
            'Sidebar_Menu_Font_Family',
            'Sidebar_Menu_Font_Size',
            'Sidebar_Menu_Font_Weight',
            'Sidebar_Menu_Hover_Font_Color',
            'Sidebar_Menu_Hover_BG_Color',

            'Sidebar_Menu_Active_Font_Color',
            'Sidebar_Menu_Active_BG_Color',

            'Sidebar_SubMenu_Active_Font_Color',
            'Sidebar_SubMenu_Active_BG_Color',

            'Sidebar_Menu_Box_Shadow_Color',
            'Sidebar_Menu_Box_Shadow_Size',

        );

        foreach ($page_arr as $key => $value) {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } else if (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Top_Menu_BackGround_Color']['Top_Menu_BackGround_Color']['rowOpen'] = true;
        $field[1]['field']['Sidebar_Menu_Box_Shadow_Size']['Sidebar_Menu_Box_Shadow_Size']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        );
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $classAction->tableToMaster();
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

        echo $classCSS->getCssData('applying');
        $classMysql->extractColorData();


        $classUI->echoForm('no');
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId='1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
            //$adminColor[$key] = $value;

        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode("ReportTablePage", "ReportTablePage");

function ReportTablePage($atts)
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

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        $errorMsg = array();

        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classCSS = new classCSS();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        

        $field['fieldData']['pageName'] = $pagename;
        $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] = $role[$roleKey];  if( isset ( $menuAction[$pageId] )) $field['fieldData']['currentAction'] = $menuAction[$pageId];
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
        
        // $field['fieldData']["callBack"] = "referTrn";

        $fontWeightList = array('' => ' - Select Font Weight - ', 'bold' => 'bold', 'bolder' => 'bolder', 'normal' => 'normal', 'lighter' => 'lighter');
        
        $fldColor = array(
            "fieldType" => "input",
           'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'color',
                'class' => 'form-control',
            ),
        );
        $fontStyle = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontStyleList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fontWeight = array(
            "fieldType" => "select",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "optionList" => $fontWeightList,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
            ),
        );
        $fldRadius = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'class' => 'form-control',
                'type' => 'number',
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'class' => 'form-control',
            ),
        );
        $fldOpacity = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'max' => 90,
                'min' => 0,
                'step' => 1,
                'type' => 'number',
                'class' => 'form-control',
            ),
        );
        $fldOther = array(
            "fieldType" => "input",
            'colClass' => 'col-12 col-sm-6 col-md-4 col-lg-3',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'text',
                "maxlength" => 30,
                'class' => 'form-control',
            ),
        );
        $fldUser = array(
            "fieldType" => "input",
            "fieldValue" => array(
                'type' => 'hidden',
                'class' => 'form-control',
            ),
        );
        $page_arr = array(
            'ID',
            'Report_Header_BackGround_Color',
            'Report_Header_Font_Color',
            'Report_Header_Font_Family',
            'Report_Header_Font_Size',
            'Report_Header_Font_Weight',
            'Report_Header_Border_Size',
            'Report_Header_Border_Color',

            'Report_Even_Row_BackGround_Color',
            'Report_Even_Row_Font_Color',
            'Report_Even_Row_Font_Family',
            'Report_Even_Row_Font_Size',
            'Report_Even_Row_Font_Weight',

            'Report_Odd_Row_BackGround_Color',
            'Report_Odd_Row_Font_Color',
            'Report_Odd_Row_Font_Family',
            'Report_Odd_Row_Font_Size',
            'Report_Odd_Row_Font_Weight',

            'Total_Row_BackGround_Color',
            'Total_Row_Font_Color',
            'Total_Row_Font_Family',
            'Total_Row_Font_Size',
            'Total_Row_Font_Weight',

        );

        foreach ($page_arr as $key => $value) {
            if (preg_match("/ID/", $value)) {
                $field[1]['field'][$value][$value] = $fldUser;
            } else if (preg_match("/_Color/", $value)) {
                $field[1]['field'][$value][$value] = $fldColor;
            } else if (preg_match("/_Font_Family/", $value)) {
                $field[1]['field'][$value][$value] = $fontStyle;
            } else if (preg_match("/_Font_Weight/", $value)) {
                $field[1]['field'][$value][$value] = $fontWeight;
            } else if (preg_match("/_Corner_Radius/", $value)) {
                $field[1]['field'][$value][$value] = $fldRadius;
            } else if (preg_match("/_Opacity/", $value)) {
                $field[1]['field'][$value][$value] = $fldOpacity;
            } else {
                $field[1]['field'][$value][$value] = $fldOther;
            }
            $field[1]['field'][$value][$value]['fieldValue']['name'] = $value;
            $field[1]['field'][$value][$value]['fieldValue']['id'] = $value;
         

        }
        
        $field[1]['field']['Report_Header_BackGround_Color']['Report_Header_BackGround_Color']['rowOpen'] = true;
        $field[1]['field']['Total_Row_Font_Weight']['Total_Row_Font_Weight']['rowClose'] = true;

        $field[1]['field']['ADD']['ADD'] = array(
            "fieldType" => "input",
            'rowOpen' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'ADD',
                'id' => 'ADD',
            ),
        );
        $field[1]['field']['RESET']['RESET'] = array(
            "fieldType" => "input",
            'rowClose' => true,
            'colClass' => 'col-6',
            'spanWidth' => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                'type' => 'submit',
                'class' => 'form-control',
                'name' =>'RESET',
                'id' => 'RESET',
            ),
        );

        

        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

            if ($isSuccess) {
                $classAction->tableToMaster();
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

        echo $classCSS->getCssData('applying');
        $classMysql->extractColorData();


        $classUI->echoForm('no');
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

        $colorStyle = $wpdb->get_results("select * from {$wpdb->prefix}ui where userId='1'", 'ARRAY_A');
        $adminColor = "var adminColor = {}; ";
        if (count($colorStyle) > 0) {
            foreach ($colorStyle as $key => $value) {
                foreach ($value as $k => $v) {
                    $adminColor .= " adminColor['{$k}'] = '{$v}'; ";
                }

            }
            //$adminColor[$key] = $value;

        }

    } else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }

}

add_shortcode('LoginPage', 'LoginPage');

function LoginPage()
{
    if (!is_user_logged_in()) {
        
       

        global $wp;
        $digit1 = mt_rand(1, 10);
        $digit2 = mt_rand(1, 10);
        
        $math = "$digit1 + $digit2 = ?";
        $_SESSION['answer'] = $digit1 + $digit2;
        
        ?>
	<div class="row mt-4">


		<div class="col-sm-12 col-md-6 mx-auto">
			<div class="submitPageFormCard card">
				<div class="card-header">Login</div>
				<div class="card-body">
					<form name="loginform"  autocomplete="off"  id="submitPageForm" action="<?PHP home_url($wp->request);?>" method="post">
						<div class="row">
						<?PHP
		global $loginError;
        if (isset($loginError)) {
            echo '<div class="col-12">
										<div class="alert alert-danger  text-center">' .
                $loginError
                . '</div>
									</div>';
        }
        ?>
							<div class="col-12 mb-2 form-outline">
								<label class="control-label">User Name | E-Mail ID * </label>
									
									<input required type="text" autofocus name="user_login" placeholder="Enter Your User Name | E-Mail Id" maxlength="100" minlength="3" id="user_login" class="form-control" >
							</div>
 
							<div class="col-12 mb-2 form-outline">
								<label class="control-label">Password * </label>
									<input required type="password" autofocus name="user_password" placeholder="Enter Your Passwprd" minlength="5" maxlength="20" id="user_password" class="form-control" >
							</div>

							<div class="col-12 mb-2 form-outline">
								<label class="control-label"><?PHP echo $math; ?> *</label>
									<input required type="number" min="-20" max="20" step="1" maxlength="3" name="answer" placeholder="Enter Your Answer" id="answer" class="form-control" value="" >
							</div>


							<div class="col-8 pt-2">
									<input tabindex="-1" name="remember" type="checkbox" id="rememberme" value="forever"><label style="font-size:16px"> &nbsp; Remember Me</label>
							</div>
							<div class="col-4 pt-2">
									<input type="submit" id="Action" class="form-control" value="LOGIN" name="Action">
									<input type="hidden" name="redirect_to" value="<?php echo home_url( $wp->request ); ?>">
							</div>
							<div class="col-12">
								<a tabindex="-1" href="<?PHP echo home_url() . '/user-registration/'; ?>"> Dont have account. Sign Up?</a>
							</div>
							<div class="col-12 mt-2">
								<a tabindex="-1" href="<?PHP echo home_url() . '/lost-password/'; ?>" > Lost | Forgot Your Password?</a>
							</div>
						</div>
					</form>
				</div>
			<div>
		</div>


	</div>
	<script>
        var _rulesString = {};
		var _messagesString = {};
    </script>

<?PHP

    } else {

        echo '<div class="row">
				<div class="col-12">
					<div class="alert alert-danger text-center">Your are already logged-in</div>
				</div>
			</div>';
    }

}

add_shortcode('LogoutPage', 'LogoutPage');

function LogoutPage($atts)
{

    /*    session_destroy();
    wp_logout();
    wp_redirect( home_url().'/wp-login.php' );

    $sessions = WP_Session_Tokens::get_instance( get_current_user_id());
    $sessions->destroy_all();
    wp_redirect( home_url()."/wp-login.php" );

     */
    global $wp;
    global $wpdb;
    global $jsData;
    global $field;
    extract($atts);
    extract($jsData);
    $pagename = strtolower($pagename);
    $nonceField = $pagename . "_action";
    $returnSubmitValue = '';

    $field['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
    $field['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
    $field['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";

    $classUI = new classUI();
    $classMysql = new classMysql();
    $classAction = new classAction();
    $classValidator = new classValidator();

    if( isset ( $_POST['Action'] ) )
    {
        if( $_POST['Action']  == "LOGOUT")
        {
            wp_logout();
			wp_redirect( home_url()."/login" );
        }
        
    }
    if( isset ( $_POST['Refersh'] ) )
    {
        if( $_POST['Refersh']  == "LOGOUT FROM EVERYWHERE")
        {
            $sessions = WP_Session_Tokens::get_instance( get_current_user_id());
            $sessions->destroy_all();
            wp_logout();
            wp_redirect( home_url()."/login" );
        }
    }
   

    echo $classUI->startSubmitForm('submitPageForm' , $formheadername,'' ,'No');
        wp_nonce_field($nonceField);

        if (isset($searchBoxHeight)) {
            echo $classUI->startForm();
        }
        echo '<div class="row">
                    <div class="col-6">
                        <input type="submit" class="form-control" name="Action" id="Action" value="LOGOUT" />
                    </div>
                    <div class="col-6">
                        <input type="submit" class="form-control" name="Refersh" id="Refersh" value="LOGOUT FROM EVERYWHERE" />
                     </div>
                </div>';
        if (isset($searchBoxHeight)) {
            echo $classUI->endForm();
        }

        echo $classUI->endSubmitForm();
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

    
    

    
}


add_shortcode('BackUpPage', 'BackUpPage');

function BackUpPage($atts)
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
    
        global $wp;
        extract($atts);
        $nonceField = $pagename . "_action";
        $form_url = home_url($wp->request);
        $classUI = new classUI();
        $field['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";
		
		$DBUSER=DB_USER;
		$DBPASSWD=DB_PASSWORD;
		$DATABASE=DB_NAME;
		$HOSTNAME=DB_HOST; 
			
        if( isset ( $_POST['ADD'] ) )
        {
            if( $_POST['ADD']  == "Back Up")
            {
                
                
				$backup_file = $DATABASE ."__". date("Y-m-d-H-i-s") . '.sql.gz';
				$command = "mysqldump --host=$HOSTNAME --user=$DBUSER --password=$DBPASSWD $DATABASE  | gzip > $backup_file";
				system($command);
            }
        }
        

      
		echo '<div id="errorAlert" class="row alert alert-danger" style="display:none">
				<div class="col-sm-12 text-center" id="wp-admin-bar-Error" style="margin:auto;">
				</div>
			 </div>'; 
			  
        echo $classUI->startSubmitForm('submitPageForm' , $formheadername);
        wp_nonce_field($nonceField);

        if (isset($searchBoxHeight)) {
            echo $classUI->startForm();
        }
        echo '<div class="row"><div class="col-6"><input type="submit" class="form-control" name="ADD" id="ADD" value="Back Up" /></a></div>
							   <div class="col-6"><input type="submit" class="form-control" name="REFRESH" id="REFRESH" value="REFRESH" /></div>
			  </div>';
		global $scanDirPath;
		$scanDir = scandir($scanDirPath); 
		$i = 1; 
		$path = home_url()."/";
		foreach($scanDir as $key => $val)
		{
			if( preg_match('/'.$DATABASE.'/',$val) )
			{	
				$thisFile = explode("__",$val);
				
				echo '<div class="row" id="fileList_'.$i.'"> <input type="hidden" id="file_'.$i.'" value="'.$val.'" />
										<div class="col-4">'.$thisFile[1].'</div>
										<div class="col-4"><a href="'.$path.$val.'"><input type="button" class="form-control" id="DOWNLOADFILE_'.$i.'" value="DOWNLOAD" /></a></div>
										<div class="col-4"><input type="button" class="form-control" id="DELETEFILE_'.$i.'" value="DELETE" /></div>
				</div>';
				$i++;
		
			}
		}
        if (isset($searchBoxHeight)) {
            echo $classUI->endForm();
        }
		
        echo $classUI->endSubmitForm();
		
		?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		$(function () {
			$('body').on('click', '[id*=DELETEFILE_]', function () {

				var thisId = $(this).attr("id").split("DELETEFILE_");
				
				var thisVal = $("#file_"+thisId[1]).val();
				if (thisVal.length > 0) {
					$('html').block();
					var dataa = {};
					dataa['action'] = 'unlinkFile';
					dataa['fileName'] = thisVal;
					jQuery.ajax({
						type: 'POST',
						url: thisAjax,
						data: dataa,
						success: function (data) { 
							$('html').unblock();
							if (data == 'N') {
								alert_danger('Failed to delete');
							}
							else {
								alert_sucuss('File has been deleted successfully');
								$("#fileList_"+thisId[1]).hide();
								
							}
							return false;
						},
						error: function (errorThrown) {
							$('html').unblock();
							alert_danger(errorThrown.responseText);
						}
					});
					return false;
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

function retrieve_password_sapling($user_login)
    {
        global $wpdb, $current_site;

        if (empty($user_login)) {
            return "ERROR : Invalid User Name | Mail Id";
        } else if (strpos($user_login, '@')) {
            $user_data = get_user_by('email', trim($user_login));
            if (empty($user_data)) {
                return "ERROR : Invalid User Name | Mail Id";
            }

        } else {
            $login = trim($user_login);
            $user_data = get_user_by('login', $login);
        }

        do_action('lostpassword_post');

        if (!$user_data) {
            return "ERROR : Invalid User Name | Mail Id";
        }

        // redefining user_login ensures we return the right case in the email
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
		 $user_id = $user_data->ID;

        do_action('retreive_password', $user_login); // Misspelled and deprecated
        // do_action('retrieve_password', $user_login);

        $allow = apply_filters('allow_password_reset', true, $user_data->ID);

        if (!$allow) {
            return "ERROR : You are not allowd to reset password";
        } else if (is_wp_error($allow)) {
            return "ERROR : You are not allowd to reset password";
        }

        $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
        if (empty($key)) {
            // Generate something random for a key...
            $key = wp_generate_password(20, false);
            //do_action('retrieve_password_key', $user_login, $key);
            // Now insert the new md5 key into the db
            $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        }
        $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
        $message .= network_home_url('/') . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
        $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
        $message .= '<' . network_site_url("resetlink?user={$user_id}&key={$key}>")."\r\n";

        if (is_multisite()) {
            $blogname = $GLOBALS['current_site']->site_name;
        } else
        // The blogname option is escaped with esc_html on the way into the database in sanitize_option
        // we want to reverse this for the plain text arena of emails.
        {
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        }

        $title = sprintf(__('[%s] Password Reset'), $blogname);

        $title = apply_filters('retrieve_password_title', $title);
        $message = apply_filters('retrieve_password_message', $message, $key);

        if ($message && !wp_mail($user_email, $title, $message)) {
            return 'The e-mail could not be sent.<br />Possible reason: your host may have disabled the mail() function...';
        }

        return "Check your email for the confirmation link.";
    }

add_shortcode('LostPasswordPage', 'LostPasswordPage');

function LostPasswordPage()
{
    //if ( !is_user_logged_in() )
    //{
    global $wp;
    global $loginError;
    

    if ($_POST) {
        if (isset($_POST['user_login'])) {
            if ($_SESSION['answer'] == $_POST['answer']) {
                $user_login = sanitize_text_field($_POST['user_login']);
                $loginError = retrieve_password_sapling($user_login);
            } else {
                $loginError = "ERROR : Your answer is incoreect.";
            }
        }
    }

    $digit1 = mt_rand(1, 10);
    $digit2 = mt_rand(1, 10);
    $math = "$digit1 + $digit2 = ?";
    $_SESSION['answer'] = $digit1 + $digit2;
    

    ?>
	<div class="row">


		<div class="col-sm-20 col-md-6 mx-auto">
			<div class="submitPageFormCard card">
				<div class="card-header">Lost Password</div>
				<div class="card-body">
					<form name="submitPageForm" id="submitPageForm" autocomplete="off"  action="<?PHP home_url($wp->request);?>" method="post">
						<div class="row">
						<?PHP

    if (isset($loginError)) {$alertStyle = preg_match("/ERROR : /", $loginError) ? "danger" : "success";
        echo '<div class="col-12">
										<div class="alert alert-' . $alertStyle . '  text-center">' .
            $loginError
            . '</div>
									</div>';
    }
    ?>

							<div class="col-12 col-sm-6 mb-2 form-outline">
								<label class="control-label">User Name | E-Mail ID *</label>
									<input required type="text" autofocus name="user_login" placeholder="Enter Your User Name | E-Mail Id" maxlength="100" minlength="3" id="user_login" class="form-control" >
							</div> 
							<div class="col-12  col-sm-6 mb-2 form-outline">
								<label class="control-label"><?PHP echo $math; ?> *</label>
									<input type="number" required min="-20" max="20" step="1"  name="answer" maxlength="3" minlength="1" placeholder="Enter Your Answer" id="answer" class="form-control" >
							</div>
							<div class="col-12" style="height:6px">&nbsp;</div>
							<div class="col-12">
									<input type="submit" id="submit_login" class="form-control" value="GET NEW PASSWORD" name="submit_login">
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>


	</div>
	<script>
        var _rulesString = {};
		var _messagesString = {};
    </script>

    <?PHP
}

add_shortcode("ChangePasswordPage", "ChangePasswordPage");

function ChangePasswordPage($atts)
{
    
    if (is_user_logged_in()) {

        global $wp;
        global $wpdb;
        global $jsData;
        global $field;
        global $errorMsg;
        global $fontStyleList;
        
        extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        $nonceField = $pagename . "_action";
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['pageName'] = $pagename;
        $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";


        $field['1']['field']['Old_Password']['Old_Password'] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "spanWidth" => $spanWidth,
            "rowOpen" => true,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "autofocus" => "autofocus",
                "placeholder" => "Enter Old Password",
                "required" => true,
                "type" => "text",
                "maxlength" => 20,
                "minlength" => 5,
                "class" => "form-control",
                "name" => "Old_Password",
                "id" => "Old_Password",
            ),
        );
        $field['1']['field']['New_Password']['New_Password'] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "spanWidth" => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "placeholder" => "Enter New Password",
                "required" => true,
                "type" => "text",
                "maxlength" => 20,
                "minlength" => 5,
                "class" => "form-control",
                "name" => "New_Password",
                "id" => "New_Password",
            ),
        );
        $field['1']['field']['Confirm_Password']['Confirm_Password'] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "spanWidth" => $spanWidth,
            "formGroup" => "vertical",
			 "rowClose" => true,
            "fieldValue" => array(
                "placeholder" => "Enter Confirm Password",
                "required" => true,
                "type" => "text",
                "maxlength" => 20,
                "minlength" => 5,
                "class" => "form-control",
                "name" => "Confirm_Password",
                "id" => "Confirm_Password",
            ),
        );
        $field['1']['action']["Change Password"]["Change_Password"] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "rowClose" => true,
			"rowOpen" => true,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "type" => "submit",
                "class" => "form-control",
                "name" =>"Change_Password",
            ),
        );

       if (isset($_POST['Change_Password']) ) {

         
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

         
            
            if ($isSuccess) {
                $isContinue = true;
                if (isset($_POST['Change_Password'])) {
                    if ($_POST['Change_Password'] == 'Change Password') {
                        $user = wp_get_current_user(); //trace($user);
                        $x = wp_check_password($_POST['Old_Password'], $user->user_pass, $user->ID);
                        if ($x) {
                            if (!empty($_POST['New_Password']) && !empty($_POST['Confirm_Password'])) {
                                if ($_POST['New_Password'] == $_POST['Confirm_Password']) {
                                    $udata['ID'] = $user->ID;
                                    $udata['user_pass'] = $_POST['New_Password'];
                                    $uid = wp_update_user($udata);
									
                                    if ($uid) {
										delete_user_meta( $user->ID, 'force-password-change' );
                                        wp_set_auth_cookie($user->ID);
                                        wp_set_current_user($user->ID);
                                        do_action('wp_login', $user->user_login, $user);

                                        $errorMsg[] = array("The password has been updated successfully", "alert-success");

                                    } else {
                                        $errorMsg[] = array("Failed to update password",  "alert-danger");
                                    }
                                } else {
                                    $errorMsg[] = array("Confirm password doesn't match with new password", "alert-danger");
                                }
                            } else {
                                $errorMsg[] = array("Please enter new password and confirm password",  "alert-danger");
                            }
                        } else {
                            $errorMsg[] = array("Old password doesn't match",  "alert-danger");
                        }
                    } else {
                        $errorMsg[] = array("Action method doesn't match... Cheating huh...",  "alert-danger");
                    }
                }

            }
        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (!isset($_POST['Refresh']) && !isset($_POST['SearchData']) && !isset($_POST['editThisForm']) && !isset($_POST['deleteThisForm'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}


        echo $classUI->echoForm();
        ?>
        <script>
        var _rulesString = {
			Confirm_Password : {
				equalTo : "#New_Password"
			},
		};
		var _messagesString = {};
        </script>
        <?php

    } else {
        showLogoutMsg(false , is_user_logged_in());
    }

}

add_shortcode('WelcomePage', 'WelcomePage');

function WelcomePage()
{

  	global $wpdb;
    if (is_user_logged_in()) 
	{
      $dis = array();
      $dis['Total'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}users WHERE User_Role != ''");
      $dis['Paid'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}users WHERE User_Role = 'Paid Member'");
      $dis['Members'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}users WHERE User_Role = 'Member'");
      
      $dis['Government'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}gos");
      $dis['Tenders'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}tenders");
      $dis['Job'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}job_posts");
      
      $dis['Exchange'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}business_exchange");
      $dis['Trade'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}foreign_trade");
      $dis['Opportunity'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}business_opportunities");
        
      	//echo  "<h1>WELCOME</h1>";
      ?>
<div class="row p-3 mt-3" style="background-color: palegoldenrod;">
  
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
         <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Total Members</span>
            <span class="info-box-number">
            <?=$dis['Total']; ?>
            <small>%</small>
            </span>
         </div>
      </div>
   </div>
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
         <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Paid Members</span>
            <span class="info-box-number"><?=$dis['Paid']; ?></span>
         </div>
      </div>
   </div>
   <div class="clearfix hidden-md-up"></div>
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
         <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Members</span>
            <span class="info-box-number"><?=$dis['Members']; ?></span>
         </div>
      </div>
   </div>
  
  
  <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
         <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Government Orders</span>
            <span class="info-box-number">
            <?=$dis['Government']; ?>
            <small>%</small>
            </span>
         </div>
      </div>
   </div>
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
         <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Posted Job</span>
            <span class="info-box-number"><?=$dis['Tenders']; ?></span>
         </div>
      </div>
   </div>
   <div class="clearfix hidden-md-up"></div>
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
         <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Members</span>
            <span class="info-box-number"><?=$dis['Job']; ?></span>
         </div>
      </div>
   </div>
  
  
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
         <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Business Exchange</span>
            <span class="info-box-number">
            <?=$dis['Exchange']; ?>
            <small>%</small>
            </span>
         </div>
      </div>
   </div>
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
         <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Foreign Trades</span>
            <span class="info-box-number"><?=$dis['Trade']; ?></span>
         </div>
      </div>
   </div>
   <div class="clearfix hidden-md-up"></div>
   <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
         <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
         <div class="info-box-content">
            <span class="info-box-text">Business Opportunity</span>
            <span class="info-box-number"><?=$dis['Opportunity']; ?></span>
         </div>
      </div>
   </div>
  
  
   
</div>
            
       <?
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
				
				echo '<div class="col-sm-6 col-md-6 col-lg-4">
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
							  "YES",
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
			?>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		</script>
		<?php

		} 
	}
}


add_shortcode('ResetLinkPage', 'ResetLinkPage');

function ResetLinkPage()
{
	
	 global $wp;
	global $wpdb;
	global $jsData;
	global $field;
	global $errorMsg;
	global $fontStyleList;
	global $fieldValue;
        
        //extract($atts);
        extract($jsData);
        $pagename = strtolower('resetpassword');
        $nonceField = $pagename . "_action";
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['pageName'] = $pagename;
        $field['fieldData']['pageNm'] = $pagename;
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] =  isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] =  isset( $formheadername )  ? $formheadername : "Default Form Name";

		$fieldValue['user'] = isset( $_GET['user'] ) ? $_GET['user'] : '' ;
		$field['1']['field']['user']['user'] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "spanWidth" => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "type" => "hidden",
                "name" => "user",
                "id" => "user",
            ),
        );
        $field['1']['field']['New_Password']['New_Password'] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "spanWidth" => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "placeholder" => "Enter New Password",
                "required" => true,
                "type" => "text",
                "maxlength" => 20,
                "minlength" => 5,
                "class" => "form-control",
                "name" => "New_Password",
                "id" => "New_Password",
            ),
        );
        $field['1']['field']['Confirm_Password']['Confirm_Password'] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "spanWidth" => $spanWidth,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "placeholder" => "Enter Confirm Password",
                "required" => true,
                "type" => "text",
                "maxlength" => 20,
                "minlength" => 5,
                "class" => "form-control",
                "name" => "Confirm_Password",
                "id" => "Confirm_Password",
            ),
        );
        $field['1']['action']["Reset_Password"]["Reset_Password"] = array(
            "fieldType" => "input",
            "colClass" => $md12lg12,
            "rowClose" => true,
            "formGroup" => "vertical",
            "fieldValue" => array(
                "type" => "submit",
                "class" => "form-control",
                "name" =>"Reset_Password",
            ),
        );

       if (isset($_POST['Reset_Password']) ) {

         
            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check",  "alert-danger");
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }

         
            
            if ($isSuccess) {
                $isContinue = true;
                if (isset($_POST['Reset_Password'])) {
                    if ($_POST['Reset_Password'] == 'Reset Password') {
                        $user = get_user_by( 'ID', $_POST['user'] );
                        
                            if (!empty($_POST['New_Password']) && !empty($_POST['Confirm_Password'])) {
                                if ($_POST['New_Password'] == $_POST['Confirm_Password']) 
								{
                                   reset_password( $user, $_POST['New_Password'] );
								   $errorMsg[] = array("The password has been updated successfully",  "alert-success");
								} else {
                                    $errorMsg[] = array("Confirm password doesn't match with new password",  "alert-danger");
                                }
                            } else {
                                $errorMsg[] = array("Please enter new password and confirm password", "alert-danger");
                            }
                      
                    } else {
                        $errorMsg[] = array("Action method doesn't match... Cheating huh...",  "alert-danger");
                    }
                }

            }
        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (!isset($_POST['Refresh']) && !isset($_POST['SearchData']) && !isset($_POST['editThisForm']) && !isset($_POST['deleteThisForm'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}


        
        ?>
        <script>
        var _rulesString = {
			Confirm_Password : {
				equalTo : "#New_Password"
			},
		};
		var _messagesString = {};
        </script>
		<?php
	
	if( isset( $_GET['key'])  &&    isset( $_GET['user']) ) 
	{
		$thisForm = '';
		$dbKey = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE ID = %s", $_GET['user']));
		if( !empty($dbKey) )
		{
			if( $dbKey ==  $_GET['key'] )
			{
				$thisForm =  $classUI->echoForm();
				
			}else{
				$errorMsg[] = array("Invalid link...", "alert-danger");
			}
		}
		else{
			 $errorMsg[] = array("Invalid link...", "alert-danger");
		}
		
		 if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }
		echo $thisForm;
		
		
	}
}



add_shortcode( "UserActivationPage" , "UserActivationPage" );
function UserActivationPage($atts)
{	 
	global $errorMsg;
	global $wpdb;
	
		
    if ( !is_user_logged_in() )
	{    
				
		global $wp;	
		
		global $SmsApiKey;
		global $SmsClientId;
		global $SmsSenderId;;
		$screen = 'first';
		if( isset( $_POST['ganerate_otp']) )
		{
			
				
					
			if( !empty( $_POST['user_login'] ) )
			{
				$userQry = $wpdb->get_results("select ID,Mobile_No,user_email from {$wpdb->prefix}users where Mobile_No='{$_POST['user_login']}' OR  user_login='{$_POST['user_login']}' OR user_email='{$_POST['user_login']}' ","ARRAY_A");				
				//$classAction = new classAction();
				//$isSuccess = $classAction->userRegistrationCreate();
				if( count ( $userQry ) == 1 )
				{
					$thisData = $userQry[0];
					$MobileNumbers = $thisData['Mobile_No'];
					$EmailID = $thisData['user_email'];
					$user_id = $thisData['ID'];
					$email_otp = mt_rand(1000,9999);
					$result = update_user_meta($user_id, 'activation_code_email', $email_otp);
					$mobile_otp = mt_rand(1000,9999);
					$result1 = update_user_meta($user_id, 'activation_code_mobile', $mobile_otp);
					if( $result && $result1)
					{
						$memberName = 'member';
						$userName = 'dummy';
						//echo "Dear {$memberName} Your account created successfully and the User name is {$userName} and Password is {$otp} , Thank you for using IVGK Services";
						$Message = "Dear {$memberName} Your account created successfully and  Password is {$email_otp} , Thank you for using IVGK Services";
						$mobilMessage = "Dear {$memberName} Your account created successfully and  Password is {$mobile_otp} , Thank you for using IVGK Services";
						
						$mobileMessage = urlencode($mobilMessage);
						$err = ''; 
						$err1 = true;
						//echo $MobileNumbers;
						
							$curl = curl_init();
							curl_setopt_array($curl, array(
							  CURLOPT_URL => "http://43.252.88.230/index.php/smsapi/httpapi/?secret={$SmsApiKey}&sender=3861&receiver={$MobileNumbers}&route=TA&msgtype=1&sms={$mobileMessage}",
							//  CURLOPT_URL => "https://api.mylogin.co.in/api/v2/SendSMS?ApiKey={$SmsApiKey}&ClientId={$SmsClientId}&SenderId={$SmsSenderId}&Message={$mobileMessage}&MobileNumbers=91{$MobileNumbers}",
							 CURLOPT_RETURNTRANSFER => true, 
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 30,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "GET",
							  CURLOPT_POSTFIELDS => "",
							  CURLOPT_HTTPHEADER => array(
								"content-type: application/json"
							  ),
							)); 

							$response = curl_exec($curl);
							$err = curl_error($curl);

							curl_close($curl);
					
							if (strlen( $err ) > 0 ) {
								$errorMsg[] =  array( "Failed to send OTP on {$MobileNumbers}... " , false) ;
							} else {
								$errorMsg[] =  array( $loginError =  "OTP sent to {$MobileNumbers}...", true);
							}
						
						 
						
							$err1 = wp_mail( $EmailID, __('Email Subject','text-domain') , $Message);
							if (!$err1) {
								$errorMsg[] =  array("Failed to mail OTP on {$EmailID} ...", false) ;
							} else {
								$errorMsg[] =  array( "OTP sent to mail: {$EmailID}",true) ;
							}
					
						
						if( $err != '' || $err1)
						{
							$screen = 'second';
						}
						else
						{
							$screen = 'first';
						}
	
					}else{
						 $errorMsg[] =  array("Failed to send OTP... Try again..." ,false);
						 $screen = 'first';
					}
				
				}else {
					 $errorMsg[] =  array( "User not found for {$_POST['user_login']}",false);
					$screen = 'first';
				}
			}else {
				 $errorMsg[] =  array( 'Email | Mobile | password  cant be empty',false);
				$screen = 'first';
			}	
				
			
			
			
		}else if( isset( $_POST['validate_otp']) )
		{
			if( !empty( $_POST['Email_OTP'] ) && !empty( $_POST['Mobile_OTP'] ) )
			{	//echo "select otp,ID from {$wpdb->prefix}users where otp = '{$_POST['OTP']}' and ID='{$_POST['userId']}' ";
				//$userQry = $wpdb->get_results("select otp,ID from {$wpdb->prefix}users where otp = '{$_POST['OTP']}' and ID='{$_POST['userId']}' ","ARRAY_A");				
				$email_code = get_user_meta($_POST['userId'], 'activation_code_email', true);
				$mobile_code = get_user_meta($_POST['userId'], 'activation_code_mobile', true);
				if( (int)$email_code == (int)$_POST['Email_OTP'] && (int)$mobile_code == (int)$_POST['Mobile_OTP'] )
				{
					update_user_meta($_POST['userId'], 'is_activated', 1);
					$user = get_userdata($_POST['userId']);
					wp_set_current_user($_POST['userId'], $_POST['userId']);
					wp_set_auth_cookie($_POST['userId']);
					do_action( 'wp_login',$user->user_login);
					 $errorMsg[] =  array('OTP validated',true);
					$screen = 'third';					
					header('Location: ' . home_url() );
				}
				else{
					 $errorMsg[] =  array('Invalid or Expired OTP',false);
					$screen = 'second';
				}
			}else{
				 $errorMsg[] =  array('Email | Mobile OTP cant be blank',false);
				$screen = 'second';
			}
		} else 
		{
			$screen = 'first';
			
		}
		
		
		if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

		if($screen == 'first')
		{
			 
  ?> 
   
	<div class="row mt-4">	 
	
	
		<div class="col-sm-12 col-md-6 mx-auto">
			<div class="submitPageFormCard card mb-2 d-print-none">
				<div class="card-header">User Registration</div>
				<div class="card-body">
					<form name="loginform"  autocomplete="off"  id="submitPageForm" action="<?PHP home_url( $wp->request ); ?>" method="post">
						<div class="row">
						<?PHP
							

						?>
							<div id="C_Mobile_No" class="col-sm-12 form-outline"><label class="control-label">Email | Mobile No *</label><div class="input-group"><input class="form-control " required="" name="user_login" id="user_login" type="number" maxlength="10"  value=""> </div></div>
							<div class="col-12">
								&nbsp; 
							</div>
							
							<div class="col-6">
							</div>
							<div class="col-6">
									<input type="submit" id="-LastFocus-" class="form-control" value="GET OTP" name="ganerate_otp">
							</div>
						
						</div>
					</form>
				</div>
			<div>	
		</div>
		
		
	</div>

		  
<?PHP
		}
		else if( $screen == 'second')
		{
			?> 
	<div class="row mt-4">	 
		<div class="col-sm-12 col-md-12 mx-auto">
		<div class='alert alert-info text-center'> Dont refresh this page..
		</div>
		</div>
	
		<div class="col-sm-12 col-md-6 mx-auto">
			<div class="submitPageFormCard card mb-2 d-print-none">
				<div class="card-header">OPT Validate</div>
				<div class="card-body">
					<form name="loginform"  autocomplete="off"  id="submitPageForm" action="<?PHP home_url( $wp->request ); ?>" method="post">
						<input type='hidden' name='userId' value='<?=$user_id; ?>'>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label for="user_login">Email OTP<font color="red">*</font></label>
									<input required type="text" autofocus required name="Email_OTP"  maxlength="6" minlength="4" id="Emial_OTP" class="form-control" >
								</div>
							</div>
							<div class="col-12 mt-2"> 
								<div class="form-group">
									<label for="user_login">Mobile OTP<font color="red">*</font></label>
									<input required type="text" autofocus required name="Mobile_OTP"  maxlength="6" minlength="4" id="Mobile_OTP" class="form-control" >
								</div>
							</div>
							<div class="col-12">
								&nbsp;
							</div>
							<div class="col-6">
							</div>
							<div class="col-6">
									<input type="submit" id="-LastFocus-" class="form-control" value="Validate" name="validate_otp">
							</div>

						</div>
					</form>
				</div>
			<div>	
		</div>
		
		
	</div>

		  
<?PHP
		}
		?>
		<script>
        var _rulesString = {};
		var _messagesString = {};
    </script>
		<?
	}
	else
	{
		

						
		echo '<div class="row">
				<div class="col-12">
					<div class="alert alert-danger text-center">Your are already logged-in</div>
				</div>
			</div>';
	}
	
}
