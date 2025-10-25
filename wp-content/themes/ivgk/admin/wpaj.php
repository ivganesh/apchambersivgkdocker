<?php

function toggleSidebar() {
	if( ! session_id() ) session_start();
	$_SESSION['toggleSidebar'] =  $_POST['class'] == 'collapse' ?  "non-collapse" : 'collapse' ; 
	echo $_SESSION['toggleSidebar'];
	die();
}
add_action( 'wp_ajax_nopriv_toggleSidebar', 'toggleSidebar' );
add_action( 'wp_ajax_toggleSidebar', 'toggleSidebar' );


function registerMobile() 
{
	
	if ( isset($_REQUEST) ) 
	{
		global $wpdb;
		//PATIENT  User_Role
		$getRegister = $wpdb->get_results("select Register_Mobile from {$wpdb->prefix}users where Register_Mobile='{$_POST['Register_Mobile']}' and User_Role='PATIENT'","ARRAY_A");
		if( count($getRegister) == 1 )
		{
			echo 'YES';
			
		}else{
			echo 'NO';
		}
	}
	die();
}
add_action( 'wp_ajax_registerMobile', 'registerMobile' );

function unlinkFile() 
{
	
	if ( isset($_REQUEST) ) 
	{
		global $scanDirPath; 
 		if( unlink($scanDirPath.$_POST['fileName']) )
		{
			echo 'YES';
		}else echo 'N';
	}
	die();
}
add_action( 'wp_ajax_unlinkFile', 'unlinkFile' );

function unlinkImage() 
{
	
	if ( isset($_REQUEST) ) 
	{
		global $wpdb;
		$tableName = '';
		if( $_POST['imageType'] == 'user') $tableName = "{$wpdb->prefix}users";
		else if( $_POST['imageType'] == 'listing') $tableName = "{$wpdb->prefix}listing";
		$upload_dir = wp_upload_dir();
		//echo $upload_dir['basedir'] ."/". $_POST['imageName'];
		
 		
			$updateArr["userProfile{$_POST['imageNo']}"] = '';
			$updateWhere['ID'] = $_POST['imageId'];
			$result = $wpdb->update($tableName,$updateArr,$updateWhere);
			if( $result )
			{
				unlink($upload_dir['basedir'] ."/". $_POST['imageName']);
				echo get_site_url().'/default-user.png';
			}else echo 'ERROR';
			//echo $wpdb->last_error; 
			//echo $wpdb->print_error();
			
			
		
	}else echo 'ERROR';
	die();
}
add_action( 'wp_ajax_unlinkImage', 'unlinkImage' );



function activateUser() 
{
	
	if ( isset($_REQUEST) ) 
	{
		global $wpdb;
		$updateArr["is_Activated"] = 1;
		$updateWhere['ID'] = $_POST['Id'];
		$result = $wpdb->update("{$wpdb->prefix}users",$updateArr,$updateWhere);
		if( $result )
		{
			echo "SUCCESS";
		}else echo 'ERROR';
	}else echo 'ERROR';
	die();
}
add_action( 'wp_ajax_activateUser', 'activateUser' );


function deActivateUser() 
{
	
	if ( isset($_REQUEST) ) 
	{
		global $wpdb;
		$updateArr["is_Activated"] = 0;
		$updateWhere['ID'] = $_POST['Id'];
		$result = $wpdb->update("{$wpdb->prefix}users",$updateArr,$updateWhere);
		if( $result )
		{
			echo "SUCCESS";
		}else echo 'ERROR';
	}else echo 'ERROR';
	die();
}
add_action( 'wp_ajax_deActivateUser', 'deActivateUser' );


