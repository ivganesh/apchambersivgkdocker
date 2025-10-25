<?php


function wp_get_restAPI_nav_menu_items( $menu, $args = array() ) {
	$menu = wp_get_nav_menu_object( $menu );
	if ( ! $menu ) {
		return false;
	}

	static $fetched = array();

	$items = get_objects_in_term( $menu->term_id, 'nav_menu' );
	if ( is_wp_error( $items ) ) {
		return false;
	}

	$defaults        = array(
		'order'       => 'ASC',
		'orderby'     => 'menu_order',
		'post_type'   => 'nav_menu_item',
		'post_status' => 'publish',
		'output'      => ARRAY_A,
		'output_key'  => 'menu_order',
		'nopaging'    => true,
	);
	$args            = wp_parse_args( $args, $defaults );
	$args['include'] = $items;

	if ( ! empty( $items ) ) {
		$items = get_posts( $args );
	} else {
		$items = array();
	}

	// Get all posts and terms at once to prime the caches.
	if ( empty( $fetched[ $menu->term_id ] ) && ! wp_using_ext_object_cache() ) {
		$fetched[ $menu->term_id ] = true;
		$posts                     = array();
		$terms                     = array();
		foreach ( $items as $item ) {
			$object_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
			$object    = get_post_meta( $item->ID, '_menu_item_object', true );
			$type      = get_post_meta( $item->ID, '_menu_item_type', true );

			if ( 'post_type' === $type ) {
				$posts[ $object ][] = $object_id;
			} elseif ( 'taxonomy' === $type ) {
				$terms[ $object ][] = $object_id;
			}
		}

		if ( ! empty( $posts ) ) {
			foreach ( array_keys( $posts ) as $post_type ) {
				get_posts(
					array(
						'post__in'               => $posts[ $post_type ],
						'post_type'              => $post_type,
						'nopaging'               => true,
						'update_post_term_cache' => false,
					)
				);
			}
		}
		unset( $posts );

		if ( ! empty( $terms ) ) {
			foreach ( array_keys( $terms ) as $taxonomy ) {
				get_terms(
					array(
						'taxonomy'     => $taxonomy,
						'include'      => $terms[ $taxonomy ],
						'hierarchical' => false,
					)
				);
			}
		}
		unset( $terms );
	}

	$items = array_map( 'wp_setup_nav_menu_item', $items );

	if ( ! is_admin() ) { // Remove invalid items only on front end.
		$items = array_filter( $items, '_is_valid_nav_menu_item' );
	}

	if ( ARRAY_A === $args['output'] ) {
		$items = wp_list_sort(
			$items,
			array(
				$args['output_key'] => 'ASC',
			)
		);

		$i = 1;

		foreach ( $items as $k => $item ) {
			$items[ $k ]->{$args['output_key']} = $i++;
		}
	}
	return $items;
	/**
	 * Filters the navigation menu items being returned.
	 *
	 * @since 3.0.0
	 *
	 * @param array  $items An array of menu item post objects.
	 * @param object $menu  The menu object.
	 * @param array  $args  An array of arguments used to retrieve menu item objects.
	 */
	//return apply_filters( 'wp_get_nav_menu_items', $items, $menu, $args );
}


function getMenuList($user_id)
{
	$array_menu = wp_get_restAPI_nav_menu_items('menu');
	$menu = $menuCheck = array();
	$user_meta = get_userdata($user_id);
     $role = $user_meta->roles;
	$roleKey = key($role);
	$menu = array();
	foreach ($array_menu as $m) 
	{
		if( (int)$m->menu_item_parent == 0 )
		{
			$menu[$m->title] = array();
		
			foreach ($array_menu as $me) 
			{
				if( $m->ID == $me->menu_item_parent &&  $me->shown == 'shown' )
			
				{
					$menuCheck = $m->roles;
					if( ( is_array( $menuCheck ) && in_array( $role[$roleKey], $menuCheck )  ) || 
					( !is_array( $menuCheck ) && ( $menuCheck == 'in'  ) )
					)
					{
						array_push( $menu[$m->title],$me->title ); 
					}
				}
			}
		}
		//if (empty($m->menu_item_parent))  
			/*
		{
			
			if(  $m->shown == 'shown' )
			{
				$menuCheck = $m->roles;
				if( ( is_array( $menuCheck ) && in_array( $role[$roleKey], $menuCheck )  ) || 
				( !is_array( $menuCheck ) && ( $menuCheck == 'in'  ) )
				)
				{
					$menu[$m->title] = array();
					//$menu[$m->ID]['title']       =  $m->title;
					//$menu[$m->title]['children']    =   array();
				}
			}
		} 
		*/
	}
	/*
	$submenu = array();
	foreach ($array_menu as $m) 
	{ 
		 if (!empty($m->menu_item_parent))  
		 {
			$menuCheck = $m->roles;
			if(  $m->shown == 'shown' )
			{
				$menuCheck = $m->roles;
				if( ( is_array( $menuCheck ) && in_array( $role[$roleKey], $menuCheck )  ) || 
				( !is_array( $menuCheck ) && ( $menuCheck == 'in'  ) )
				) 
				{
					$submenu[$m->ID] = array();
					$submenu[$m->ID]['title']    =    $m->title;
					
					if( isset ( $menu[$m->menu_item_parent] ) )
					{
						$menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
						
					}
				}
			}
		}
	}*/
	return $menu;
}
function wpRestTrnData($pagename)
{
	global $systemAC;
	global $dataArray;
	global $wpdb;
	$pagename = strtoupper($pagename); 
	$ac_trn = array(); 
	$srI = 0;
	if(  $pagename == 'PURCHASE' || $pagename == 'SALE' || $pagename == 'PHARMACYSALE' )  
	{
		$debtorAccount = $dataArray['Account_Name'];
		$billDate = $dataArray['Bill_Date'];
		$registerAC = isset( $dataArray["Register"] ) ? $dataArray["Register"] : $systemAC['PHARMACY CASH'] ;
		$register = $pagename == 'PURCHASE' || $pagename == 'SALE' ?  $systemAC[$pagename] :  $systemAC['PHARMACY SALE'];
		$gstId = ( $pagename == 'PURCHASE') ? $systemAC["GST PAID"] : $systemAC["GST COLLECTED"];
		$stockId = $systemAC["STOCK AMOUNT"];
		$ac_trn['trnFile']['debtor_amount'] = array( "Register" => $register, 
										   "Account_Name" =>  $debtorAccount ,
										   "Bill_Date" => $billDate,
										   "Order_No" => $dataArray['Order_No'],
										   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
										   "Amount" =>  $pagename == 'SALE' ? (float)$dataArray['Bill_Amount'] : (float)$dataArray['Bill_Amount'] * -1  ,
										   "Description" => '' ,
										   "Cheque_No"      => '',
										   "Page_Id" => $dataArray['ID'],
										   "Page_Name" => $pagename,
										   "Sr" => $srI, 
										   );
		$srI++;
		$ac_trn['trnFile']['gst_amount'] = array( "Register" => $register, 
										   "Account_Name" => $gstId,
										   "Bill_Date" => $billDate,
										   "Order_No" => $dataArray['Order_No'],
										   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
										   "Amount" => $pagename == 'SALE' ?  (float)$dataArray['Total_GST_Amt'] * -1 :  (float)$dataArray['Total_GST_Amt'] ,
										   "Description" => '' ,
										   "Cheque_No"      => '',
										   "Page_Id" => $dataArray['ID'],
										   "Page_Name" => $pagename,
										    "Sr" => $srI,
										   );
		$srI++;
		$ac_trn['trnFile']['Taxable_Amt'] = array( "Register" => $register, 
										   "Account_Name" => $stockId ,
										   "Bill_Date" => $billDate,
										   "Order_No" => $dataArray['Order_No'],
										   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
										   "Amount" => $pagename == 'SALE' ? (float)$dataArray['Total_Taxable_Amt'] * -1 : (float)$dataArray['Total_Taxable_Amt'] ,
										   "Description" => '' ,
										   "Cheque_No"      => '',
										   "Page_Id" => $dataArray['ID'],
										   "Page_Name" => $pagename,
										    "Sr" => $srI,
										   );
		$descriptionData = $pagename.' Entry - Cash Payment from '.getDataName('users',array('Account_Name'),$dataArray['Account_Name']);								   
		$ac_trn['trnFile']['debtor_payment'] = array(  "Register" => $registerAC, 
											 "Account_Name" => $debtorAccount ,
											 "Bill_Date" => $billDate,
											 "Order_No" => $dataArray['Order_No'],
											 "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0,
											 "Amount" => $pagename == 'SALE' ? (float)$dataArray['Cash_Receive'] * -1 : (float)$dataArray['Cash_Paid'] ,
											 "Description" => $descriptionData ,
											 "Cheque_No"      => '',
											 "Page_Id" => $dataArray['ID'],
										     "Page_Name" => $pagename,
											 "Sr" => 1,
										   );
		$ac_trn['trnFile']['register_payment'] = array( 
											   "Register" => $registerAC, 
											   "Account_Name" => $registerAC ,
											   "Bill_Date" => $billDate,
											   "Order_No" => $dataArray['Order_No'],
											   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
											   "Amount" => $pagename == 'SALE' ? (float)$dataArray['Cash_Receive'] : (float)$dataArray['Cash_Paid'] * -1 ,
											   "Description" => $descriptionData ,
											   "Cheque_No"      => '',
											   "Page_Id" => $dataArray['ID'],
										   		"Page_Name" => $pagename,
												"Sr" => 1,
										   );
		if ( $pagename == 'PURCHASE' || $pagename == 'SALE' )
		{
			$srI++;
			$pro = array();
			$chq_no = array(); // for sale rate & purchase rate
			foreach( $dataArray as $k => $v)
			{
				if( preg_match("/Product_Name_/",$k) && strlen($v) > 0 )
				{	$pro_id = explode("Product_Name_",$k)[1];
					$pro_qty = (float)$dataArray["QTY_{$pro_id}"] ;
					$pro_fqty = (float)$dataArray["FQ_{$pro_id}"] ;
					
					$ac_trn['stkFile'][$k] = array( "Register" => $register, 
											   "Account_Name" => $dataArray['Account_Name'] ,
											   "Bill_Date" => $dataArray['Bill_Date'],
											   "Order_No" => $dataArray['Order_No'],
											   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
											   "Qty" => $pagename == 'SALE' || $pagename == 'PHARMACYSALE' ? (float)$pro_qty * -1 : (float)$pro_qty ,
											   "Fqty" => $pagename == 'SALE' || $pagename == 'PHARMACYSALE' ? (float)$pro_fqty * -1 : (float)$pro_fqty ,
											   "Product" => $v,
											   "Batch_No" => $dataArray["Batch_No_{$pro_id}"],
											   "Rate"  => $dataArray["Rate_{$pro_id}"],
											   "Total"  => $dataArray["Total_{$pro_id}"],
											   "Page_Id" => $dataArray['ID'],
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
		$billDate = $dataArray['Bill_Date'];
		$debtorAccount = $dataArray['Patient_Name'];
		$descriptionBill = "Bill Ganerated from REFER OUT";
		$descriptionData = "cash from REFER OUT";
		//$registerAC = $systemAC["RECEPTION CASH"];
		$registerAC = $dataArray['Register'];
		$ProfitId = $systemAC["REFER-OUT PROFIT"];
		$register = $systemAC['REFER-OUT'];
		$totalRefer = 0 ;
		foreach( $dataArray as $k => $v)
		{
			if( preg_match("/Report_Name_/",$k) && strlen($v) > 0 )
			{	
				$referAmount = 0;
				$pro_id = explode("Report_Name_",$k)[1];
				$reportName = $dataArray["Report_Name_{$pro_id}"];
				$referOut = $dataArray["Refer_Out_{$pro_id}"];;
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
									   "Account_Name" => $dataArray["Refer_Out_{$pro_id}"] ,
									   "Bill_Date" => $billDate,
									   "Order_No" => $dataArray['Order_No'],
									   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 , 
									   "Amount" => (float)$referAmount * -1 ,
									   "Description" => $descriptionBill ,
									   "Cheque_No"      => '',
									   "Page_Id" => $dataArray['ID'],
									   "Page_Name" => $pagename,
										"Sr" => 1, 
									   );
															   
			
			}
		}				
				
		$dataArray['Bill_Amount'] = isset(  $dataArray['Bill_Amount'] ) ? $dataArray['Bill_Amount'] : 0 ;
		$profit_amount = (float)$dataArray['Bill_Amount'] - $totalRefer;
		$ac_trn['trnFile'][] = array( "Register" => $register, 
									   "Account_Name" => $debtorAccount , 
									   "Bill_Date" => $billDate,
									   "Order_No" => $dataArray['Order_No'],
									   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
									   "Amount" => (float)$dataArray['Bill_Amount'] ,
									   "Description" => $descriptionBill ,
									   "Cheque_No"      => '',
									   "Page_Id" => $dataArray['ID'],
										"Page_Name" => $pagename,
									   "Sr" => 2,
									   );
										
		$ac_trn['trnFile'][] = array( "Register" => $register, 
										   "Account_Name" => $ProfitId ,
										   "Bill_Date" => $billDate,
										   "Order_No" => $dataArray['Order_No'],
										   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 , 
										   "Amount" => (float)$profit_amount * -1 ,
										   "Description" => $descriptionBill ,
										   "Cheque_No"      => '',
										   "Page_Id" => $dataArray['ID'],
										   "Page_Name" => $pagename,
											"Sr" => 3,
										   );
		$ac_trn['trnFile'][] = array(  "Register" => $registerAC, 
										 "Account_Name" => $debtorAccount ,
										 "Bill_Date" => $billDate,
										 "Order_No" => $dataArray['Order_No'],
										 "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
										 "Amount" => (float)$dataArray['Cash_Receive'] * -1 ,
										 "Description" => $descriptionData ,
										 "Cheque_No"      => '',
										 "Page_Id" => $dataArray['ID'],
										 "Page_Name" => $pagename,
										 "Sr" =>4,
									   );
		$ac_trn['trnFile'][] = array( 
								   "Register" => $registerAC, 
								   "Account_Name" => $registerAC ,
								   "Bill_Date" => $billDate,
								   "Order_No" => $dataArray['Order_No'],
								   "Bill_No" =>isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
								   "Amount" => (float)$dataArray['Cash_Receive'] ,
								   "Description" => $descriptionData ,
								   "Cheque_No"      => '',
								   "Page_Id" => $dataArray['ID'],
								   "Page_Name" => $pagename,
									"Sr" => 5,
							   );
					
			//if( $dataArray['Bill_No'] == 0 ) { unset($ac_trn['debtor_amount']); unset($ac_trn['profit_amount']);}
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
							WHERE {$joinAppoint}.isTrash = 0 AND {$joinAppoint}.ID ='{$dataArray['Patient_Name']}' AND {$joinUsers}.ID IS NOT NULL ", 'ARRAY_A');;
			if( count ($debtorQry)  == 1)
			{
				$debtorAccount = $debtorQry[0]['ID'];
			}
			$billDate = $dataArray['Admit_Date'];
			$descriptionBill = "Bill Ganerated from ADMIT";
			$descriptionData = "cash from ADMIT";
			// $registerAC = $systemAC["RECEPTION CASH"];
			$registerAC = $dataArray['Register'];
			 $ProfitId = $systemAC["PATIENT PROFIT"];
			 $register = $systemAC['ADMIT'];
		}
		else if( $pagename == 'APPOINTMENT'   ) 
		{
			
			$billDate = $dataArray['Appoint_Date'];
			$debtorAccount = $dataArray['Patient_Name'];
			$descriptionBill = "Bill Ganerated from APPOINTMENT";
			$descriptionData = "cash from APPOINTMENT";
			//$registerAC = $systemAC["RECEPTION CASH"];
			$registerAC = $dataArray['Register'];
			$ProfitId = $systemAC["PATIENT PROFIT"];
			$register = $systemAC['APPOINTMENT'];
		}
			else if( $pagename == 'REFER' ) 
			{
				

				$billDate = $dataArray['Payment_Date'];
				$debtorAccount = $dataArray['Refer_To'];
				$dataArray['Bill_Amount'] = $dataArray['Cash_Receive'];
				$descriptionBill = "Bill Ganerated from REFER";
				$descriptionData = "cash from REFER";
			 	//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $dataArray['Register'];
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
							WHERE {$joinAdmit}.isTrash = 0 AND {$joinAdmit}.ID ='{$dataArray['Patient_Name']}' AND {$joinUsers}.ID IS NOT NULL ", 'ARRAY_A');;
			if( count ($debtorQry)  == 1)
			{
				$debtorAccount = $debtorQry[0]['ID'];
			}
			
				$billDate = $dataArray['Discharge_Date'];
				//$debtorAccount = $dataArray['Patient_Name'];
				$descriptionBill = "Bill Ganerated from DISCHARGE";
				$descriptionData = "cash from DISCHARGE";
				//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $dataArray['Register'];
				$ProfitId = $systemAC["PATIENT PROFIT"];
				$register = $systemAC['DISCHARGE'];
			}
			else if( $pagename == 'RECEPTION' ) 
			{
				
				$billDate = $dataArray['Bill_Date'];
				$debtorAccount = $dataArray['Patient_Name'];
				$descriptionBill = "Bill Ganerated from RECEPTION";
				$descriptionData = "cash from RECEPTION";
				//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $dataArray['Register'];
				$ProfitId = $systemAC["PATIENT PROFIT"];
				$register = $systemAC['RECEPTION'];
				//$registerAC = $systemAC[$pagename];
			}else if( $pagename == 'TEST' ) 
			{
				
				$billDate = $dataArray['Bill_Date'];
				$debtorAccount = $dataArray['Patient_Name'];
				$descriptionBill = "Bill Ganerated from TEST";
				$descriptionData = "cash from TEST";
				//$registerAC = $systemAC["RECEPTION CASH"];
				$registerAC = $dataArray['Register'];
				$ProfitId = $systemAC["TEST PROFIT"];
				$register = $systemAC['TEST'];
				//$registerAC = $systemAC[$pagename];
			}		
			
			

			
			$dataArray['Bill_Amount'] = isset(  $dataArray['Bill_Amount'] ) ? $dataArray['Bill_Amount'] : 0 ;
			$ac_trn['trnFile'] = array( 'debtor_amount' => array( "Register" => $register, 
													   "Account_Name" => $debtorAccount ,
													   "Bill_Date" => $billDate,
													   "Order_No" => $dataArray['Order_No'],
													   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
													   "Amount" => (float)$dataArray['Bill_Amount'] ,
													   "Description" => $descriptionBill ,
													   "Cheque_No"      => '',
													   "Page_Id" => $dataArray['ID'],
														"Page_Name" => $pagename,
													   "Sr" => 2,
													   ),
									'profit_amount' => array( "Register" => $register, 
													   "Account_Name" => $ProfitId ,
													   "Bill_Date" => $billDate,
													   "Order_No" => $dataArray['Order_No'],
													   "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 , 
													   "Amount" => (float)$dataArray['Bill_Amount'] * -1 ,
													   "Description" => $descriptionBill ,
													   "Cheque_No"      => '',
													   "Page_Id" => $dataArray['ID'],
													   "Page_Name" => $pagename,
													    "Sr" => 3,
													   ),
									
									'debtor_payment' => array(  "Register" => $registerAC, 
														 "Account_Name" => $debtorAccount ,
														 "Bill_Date" => $billDate,
														 "Order_No" => $dataArray['Order_No'],
														 "Bill_No" => isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
														 "Amount" => (float)$dataArray['Cash_Receive'] * -1 ,
														 "Description" => $descriptionData ,
														 "Cheque_No"      => '',
														 "Page_Id" => $dataArray['ID'],
														 "Page_Name" => $pagename,
														 "Sr" =>4,
													   ),
									'register_payment' => array( 
														   "Register" => $registerAC, 
														   "Account_Name" => $registerAC ,
														   "Bill_Date" => $billDate,
														   "Order_No" => $dataArray['Order_No'],
														   "Bill_No" =>isset(  $dataArray['Bill_No'] ) ? $dataArray['Bill_No'] : 0 ,
														   "Amount" => (float)$dataArray['Cash_Receive'] ,
														   "Description" => $descriptionData ,
														   "Cheque_No"      => '',
														   "Page_Id" => $dataArray['ID'],
														   "Page_Name" => $pagename,
															"Sr" => 1,
													   ),
							);
			//if( $dataArray['Bill_No'] == 0 ) { unset($ac_trn['debtor_amount']); unset($ac_trn['profit_amount']);}
			//print_r($ac_trn);
			return $ac_trn;
							
	}
	
	else if(  $pagename == 'BANKCASH' || $pagename == 'BANK-CASH' || $pagename == 'PHARMACYCASH' || $pagename == 'RECEPTIONCASH'  ) 
	{
		$regAmount = 0;
		if( $dataArray['Register'] == $systemAC['CONTRA'] )
		{
			if( strlen( $dataArray["Account_Name_1"] ) > 0 )
			{
				$amount = (float)$dataArray["CR_Amt_1"] > 0 && strlen($dataArray["CR_Amt_1"] ) > 0 ?  (float)$dataArray["CR_Amt_1"] * -1 : (float)$dataArray["DR_Amt_1"];
				$regAmount += (float)$amount;
				$ac_trn['trnFile'][] = array( 
										   "Register" 		=> $dataArray['Account_Name_1'], 
										   "Account_Name"   => $dataArray['Account_Name_2'] ,
										   "Bill_Date"      => $dataArray['Bill_Date'],
										   "Order_No"       => 0,
										   "Bill_No"        => 0,
										   "Cheque_No"      => $dataArray["Cheque_No_1"],
										   "Amount"         => $amount ,
										   "Description"    => $dataArray["Description_1"],
										   "Page_Id" => $dataArray['ID'],
										   "Page_Name" => $pagename,
										   "Sr"             => 999,
										   );
				
				
			}
			if( strlen( $dataArray["Account_Name_2"] ) > 0 )
			{
				$amount = (float)$dataArray["CR_Amt_1"] > 0 && strlen($dataArray["CR_Amt_1"] ) > 0 ?  (float)$dataArray["CR_Amt_1"] * -1 : (float)$dataArray["DR_Amt_1"];
				$regAmount += (float)$amount;
				$ac_trn['trnFile'][] = array( 
										   "Register" 		=> $dataArray['Account_Name_2'], 
										   "Account_Name"   => $dataArray['Account_Name_1'] ,
										   "Bill_Date"      => $dataArray['Bill_Date'],
										   "Order_No"       => 0,
										   "Bill_No"        => 0,
										   "Cheque_No"      => $dataArray["Cheque_No_1"],
										   "Amount"         => $amount  * -1,
										   "Description"    => $dataArray["Description_1"],
										   "Page_Id" => $dataArray['ID'],
										   "Page_Name" => $pagename,
										   "Sr"             => 999,
										   );
				
				
			}
		}
		else
		{
			foreach( $dataArray as $key => $value)
			{
				if( preg_match("/Account_Name_/" ,$key ) && strlen( $dataArray[$key] ) > 0 )
				{
					$thisId = explode("Account_Name_" , $key );
					$thisId = (int)$thisId[1];
					$amount = (float)$dataArray["CR_Amt_{$thisId}"] > 0 && strlen($dataArray["CR_Amt_{$thisId}"] ) > 0 ?  (float)$dataArray["CR_Amt_{$thisId}"] * -1 : (float)$dataArray["DR_Amt_{$thisId}"];
					$regAmount -= (float)$amount;
					$ac_trn['trnFile'][] = array( 
											   "Register" 		=> $dataArray['Register'], 
											   "Account_Name"   => $dataArray[$key] ,
											   "Bill_Date"      => $dataArray['Bill_Date'],
											   "Order_No"       => $dataArray["Order_No_{$thisId}"],
											   "Bill_No"        => 0,
											   "Cheque_No"      => $dataArray["Cheque_No_{$thisId}"],
											   "Amount"         => $amount ,
											   "Description"    => $dataArray["Description_{$thisId}"],
											   "Page_Id" => $dataArray['ID'],
											   "Page_Name" => $pagename,
											   "Sr"             => $srI,
											   );
					$srI++;
					
				}
			}
			if( $regAmount != 0 )
			{
				$regId =  $dataArray['Register'] ;
				$ac_trn['trnFile'][] = array( 
												   "Register" 		=> $dataArray['Register'], 
												   "Account_Name"   => $regId ,
												   "Bill_Date"      => $dataArray['Bill_Date'],
												   "Order_No"       => 0,
												   "Bill_No"        => 0,
												   "Cheque_No"      => '',
												   "Amount"         => $regAmount ,
												   "Description"    => '',
												   "Page_Id" => $dataArray['ID'],
													"Page_Name" => $pagename,
													"Sr"            => $srI,
												   );
						$srI++;
			}
		}
		return $ac_trn;
		
	
		
	}
}
function wpRestReferTrn( $pageName,$atts)
{
	global $wpdb;
	global $dataArray;
	 $atts = strtoupper( $atts );
	$isContinue = true;
	
	$pageName = strtoupper($pageName);
	if( $atts == 'TRASH' || $atts == 'RESTORE'  )
	{	
		$trashD = array();
		$trashD['Page_Id'] = $dataArray['ID'];
		$trashD['Page_Name'] = $pageName;
		$trashUpdate = array();
		$trashUpdate['isTrash'] =  $atts == 'TRASH'  ? 1 : 0;
		$wpdb->update($wpdb->prefix."trntbl" , $trashUpdate , $trashD  );
		if ( $pageName == 'PURCHASE' || $pageName == 'SALE' )
		{ 
			 $wpdb->update($wpdb->prefix."stktbl" , $trashUpdate , $trashD  );
			
		}
		
		
	}
	
	
	if( $atts == 'UPDATE' )
	{
		$trashD = array();
		$trashD['Page_Id'] = $dataArray['ID'];
		$trashD['Page_Name'] = $pageName;
		 $wpdb->delete($wpdb->prefix."trntbl"  , $trashD  );
		
		if ( $pageName == 'PURCHASE' || $pageName == 'SALE' )
		{ 
			 $wpdb->delete($wpdb->prefix."stktbl" , $trashD  );
			
		}
		
	}
	

	

	if( preg_match("/UPDATE/",$atts ) || preg_match("/ADD/",$atts ) )
	{	
		if ( $atts == 'UPDATE' )  $errorName = 'Updated';
		else  $errorName = 'Added';
		$ac_trn = wpRestTrnData( $pageName );
	
		$is_full_succ = $is_full_suc = true;
		foreach( $ac_trn['trnFile'] as $k => $v)
		{
			if((float)$v['Amount'] != 0)
			{	
				$result = $wpdb->insert($wpdb->prefix."trntbl", $v  );
				if(!$result) $is_full_succ = false;
			}
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
		}
		if($is_full_succ && $is_full_suc)
		{
			return array(true, "All Transcations successfully {$errorName}" );
		}
		else 
		{	
			return array( true, "All Transcations failed {$atts}");  
		}
			
	}	

	
}

function class_endpoints(){

  $clsAPI = new classRest();
	$clsAPI->register_routes();
  
}
add_action('rest_api_init','class_endpoints');



function validate_jwt($data)
{
	global $wpdb;
		
	$result = $wpdb->get_results("SELECT * FROM
									{$wpdb->prefix}users 
								 WHERE 
									ID = {$data['ID']} AND jwt_key='{$data['jwt_key']}'",ARRAY_A);
								
	
	if( count( $result) == 1 )return true;
	
	return false;
}

function check_user($userName)
{
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM
										{$wpdb->prefix}users
									 WHERE 
										 user_email = '{$userName}'    OR   
										 user_mobile = '{$userName}'   
										" , ARRAY_A);
										
	if( count( $result) == 1 )
	{
		return $result[0];
	}
	return array();									
}


function check_user_id($userId)
{
	global $wpdb;
	$result = $wpdb->get_results("SELECT * FROM
										{$wpdb->prefix}users
									 WHERE 
										 ID = '{$userId}' " , ARRAY_A);
										
	if( count( $result) == 1 )
	{
		return $result[0];
	}
	return array();									
}

function set_jwt($jwtKey,$ID)
{
	global $wpdb;
	$result = $wpdb->update("{$wpdb->prefix}users", array("jwt_key" =>  $jwtKey) ,  array("ID" =>  $ID) );
	
	if( $result ) 	return true;
	return false;
}
 
 
function getOrderNo($data,$type)
{

	if( isset( $data['Bill_Date'] ) ) $getBillDate = $data['Bill_Date'];
	else if( isset( $data['Discharge_Date'] ) ) $getBillDate = $data['Discharge_Date'];
	else if( isset( $data['Appoint_Date'] ) ) $getBillDate = $data['Appoint_Date'];
	else if( isset( $data['Admit_Date'] ) ) $getBillDate = $data['Admit_Date'];
	else if( isset( $data['IPD_Date'] ) ) $getBillDate = $data['IPD_Date'];
	else if( isset( $data['OPD_Date'] ) ) $getBillDate = $data['OPD_Date'];
	//else if( isset( $data['Refer_Date'] ) ) $getBillDate = $data['Refer_Date'];
	else if( isset( $data['Payment_Date'] ) ) $getBillDate = $data['Payment_Date'];
	else return false;
	
	if( isset( $getBillDate ) )
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
				$getOrderNo = $value[$type];
				$getOrderNo++;
				if( (int)$getOrderNo > 1)
				{
					$insertData = $updateQuery = array();
					$insertData[$type] = $getOrderNo;
					$updateQuery['ID']  = $value['ID'];
					$result = $wpdb->update("{$wpdb->prefix}orderbillno" , $insertData , $updateQuery );
					
					if($result)
					{
						return $getOrderNo;
					} 
					else { 
						return false;
					}
				}else return false;
				
			}
			
		}
		else{
			return false;	
		}
	}

}

function setInsertUniqueQuery($tblName,$fieldData)
{
	global $wpRestUnique;
	$queryReturn = '';

		
		foreach( $wpRestUnique[$tblName] as $key => $value )
			if( $value == 'AND' )$queryReturn .=  " AND ";
			else if ( $value == "OR") $queryReturn .=  " OR " ;
				else if( $value == "openBracket") $queryReturn .= " ( " ;
					else if ( $value == "closeBracket" )  $queryReturn .=  " ) " ;
						else $queryReturn .=  " {$key} = '" . trim($fieldData[$key])."' " ;
		
	
	return $queryReturn;
}

function requiredField($reqArray,$fieldData)
{
	$returnStr = '';
	foreach( $reqArray as $key => $value)
	{
		if( isset( $fieldData[$key] ) )
		{
			if( trim($fieldData[$key]) == '')
			{
				$returnStr .= str_replace("_", " ", $key)." cant be blank...";
			}
		}
	}
	if(strlen($returnStr) > 1) return array(  false, $returnStr);
	return array( true, '');
	
}

function addNewUserrole()
{
	global $dataArray;
	 if ($GLOBALS['wp_roles']->is_role($dataArray['User_Role'])) {
		return  array(false, "Role Exists");
	} else {
		add_role($dataArray['User_Role'], $dataArray['User_Role']);
		return  array(true, "New Role Created");
	}
}

function addNewUsers()
{
	global $dataArray;
	if($dataArray['action'] == 'ADD')
	{
		// Anti-spam validation
		if (isset($dataArray['Register_Email'])) {
			$email = $dataArray['Register_Email'];
			
			// Block suspicious email patterns
			$spam_patterns = [
				'/.*@.*\.(tk|ml|ga|cf|ru|cn)$/i',  // Free/suspicious domains
				'/\d{10,}@/i',                      // Long numbers
				'/test\d*@/i',                      // Test emails
				'/.*@(gmail|yahoo|hotmail)\.com$/i' // Common disposable domains
			];
			
			foreach ($spam_patterns as $pattern) {
				if (preg_match($pattern, $email)) {
					error_log('AP Chambers: Spam user creation blocked - suspicious email: ' . $email);
					return array(false, "Invalid email address");
				}
			}
			
			// Check for suspicious account names
			if (isset($dataArray['Account_Name'])) {
				$account_name = $dataArray['Account_Name'];
				if (strlen($account_name) < 3 || preg_match('/^\d+$/', $account_name)) {
					error_log('AP Chambers: Spam user creation blocked - suspicious account name: ' . $account_name);
					return array(false, "Invalid account name");
				}
			}
		}
		
		$checkUserName =  $dataArray['Register_Email'] != '' ? $dataArray['Register_Email'] :
							(
								$dataArray['Register_Mobile'] != '' ? $dataArray['Register_Mobile'] :
								str_replace(" ", "", str_replace(" ", "", $dataArray['Account_Name']))
							);
		$user_id = username_exists($checkUserName);
		if (!$user_id) {
			$userdata = array(
				'user_login' => $checkUserName,
				'user_email' => $dataArray['Register_Email'],
				'user_pass' => '12345678',
			);
			$userId = wp_insert_user($userdata);
			if ($userId) {
				$dataArray['ID'] = $userId;
				$dataArray['action'] = 'UPDATE'; 
				$u = new WP_User( $userId );
				$u->remove_role( 'subscriber' );
				$u->add_role( $dataArray['User_Role'] );
				return array(true,"User created");
				
			} else {
				return array(false,"User not created");
			}
		} else {
			return array(false,"User Name  OR E-Mail already exists...");
		}
	} 
	else{
		return array(true,"It is updated entry");
	}
	
}



function addNewUsersPatient($thisAction)
{
	global $dataArray;
	$isContinue = true;
	$returnMessage = '';
	$dataArray['User_Role'] =  $dataArray['Balance_Sheet'] = 'PATIENT';
	if ($thisAction == "ADD") 
	{
		$checkUserName =  $dataArray['Register_Email'] != '' ? $dataArray['Register_Email'] :
							(
								$dataArray['Register_Mobile'] != '' ? $dataArray['Register_Mobile'] :
								str_replace(" ", "", str_replace(" ", "", $dataArray['Account_Name']))
							);  
		$user_id = username_exists($checkUserName);
		if (!$user_id) {
			$userdata = array(
				'user_login' => $checkUserName,
				'user_email' => $dataArray['Register_Email'],
				'user_pass' => '12345678',
			);
			$userId = wp_insert_user($userdata);
			if ($userId) {
				$dataArray['ID'] = $userId;
				$dataArray['action'] = 'UPDATE';
				$u = new WP_User( $userId );
				$u->remove_role( 'subscriber' );
				$u->add_role( $dataArray['User_Role'] );
				$isContinue = false;
				$returnMessage = 'User created..';
				
			} else {
				$isContinue = false;
				$returnMessage = 'User not created..';
				//return array(false , "User not created");
				
			}
		} else {
			$isContinue = false; 
			$returnMessage = 'User Name  OR E-Mail already exists..';
		}
	}else{
		$isContinue = true;
		//$returnMessage = 'It is updated entry';
		//return array(true,"It is updated entry");
	}
	if( $isContinue && ( $thisAction == "ADD" || $thisAction == "UPDATE" ) )
	{
		if ( $_FILES ) 
		{ 
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			foreach( $_FILES as $key => $value )
			{
				$files = $_FILES[$key];
				//print_r($files);
				if( $files['name'] != '' &&  $files['size'] > 0 )
				{
					$file = array( 
								'name' => $files['name'].'.jpeg',
								'type' => $files['type'], 
								'tmp_name' => $files['tmp_name'], 
								'error' => $files['error'],
								'size' => $files['size']
						 );
						 
						// $target_file = ABSPATH."/wp-content/uploads/";
						// if (move_uploaded_file($file["tmp_name"], $target_file)) 
//Array ( [name] => Koala.jpg [type] => image/jpeg [tmp_name] => /tmp/phpioWsPx [error] => 0 [size] => 143469 )

					$upload_overrides = array( 'test_form' => false );
					$movefile = wp_handle_upload($file,$upload_overrides); 
					if ( $movefile && ! isset( $movefile['error'] ) ) {
						$dataArray["Avatar"] = $movefile["url"];
						$isContinue = true; 
						$returnMessage .= 'Avatar Image uploaded successfully';
					} else {
						$isContinue = false;  
						$returnMessage .= $movefile['error'];
						//$returnMessage .= 'Failed to upload Avatar Image';
					}
					
				}

			}
							
		}
	}
	return  array($isContinue,$returnMessage);
	
}

function addNewUsersEmployee()
{
	global $dataArray;
	$dataArray['Balance_Sheet'] = 'EMPLOYEE';
	if ($dataArray['action'] == "ADD") 
	{
		$checkUserName =  $dataArray['Register_Email'] != '' ? $dataArray['Register_Email'] :
							(
								$dataArray['Register_Mobile'] != '' ? $dataArray['Register_Mobile'] :
								str_replace(" ", "", str_replace(" ", "", $dataArray['Account_Name']))
							);
		$user_id = username_exists($checkUserName);
		if (!$user_id) {
			$userdata = array(
				'user_login' => $checkUserName,
				'user_email' => $dataArray['Register_Email'],
				'user_pass' => '12345678',
			);
			$userId = wp_insert_user($userdata);
			if ($userId) { 
				$dataArray['ID'] = $userId;
				$dataArray['action'] = 'UPDATE';
				$errorMsg[] = array("User created", 'alert-success');
				$u = new WP_User( $userId );
				$u->remove_role( 'subscriber' );
				$u->add_role( $dataArray['User_Role'] );
				return array(true,"User created");
			} else {
				return  array(false, "User not created");
			}
		} else {
			return array(false, "User Name  OR E-Mail already exists...");
		}
	}
	else{
		return array(true,"It is updated entry");
	}		
		
		
		
}

function addAdmissionNo()
{
	global $wpdb;
	global $dataArray;
	$isContinue = true;
	$classDate = new classDate();
	if ($dataArray['action'] == 'ADD') {
		$dates = $classDate->firstLastDateOfMonth($dataArray['Admit_Date']);
		if (count($dates) == 2) {
			$sortDate = strtotime($dataArray['Admit_Date']);
			$admissionNo = $wpdb->get_results("select Admission_No from {$wpdb->prefix}admit WHERE Admit_Date >= '" . $dates['firstDate'] . "'  and Admit_Date <= '" . $dates['lastDate'] . "' and  Admission_No > 9999 order by Admission_No DESC limit 1", 'ARRAY_A');
			//echo count($admissionNo); exit;
			if (count($admissionNo) == 0) {
				$newAdmissionNo =  date('y', $sortDate).date('m', $sortDate)."0001"  ;
				$dataArray['Admission_No'] = (int) $newAdmissionNo;
			} else {
				$newAdmissionNo = (int) $admissionNo[0]['Admission_No'];
				$newAdmissionNo++;
				$dataArray['Admission_No'] = $newAdmissionNo;
			}

			if ($dataArray['Admission_No'] > 0) {
				 return array(true,"Admission No created");
			} else {
				 return array(false,"Failed to create Admission No");
			}
		}
	}
	else{
		return array(true,"It is updated entry");
	}
}