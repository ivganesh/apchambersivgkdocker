<?PHP
class classCSS
{
	// this function will return current user capability
	
	public function getEnqueueCss()
	{	global $wpdb;
		global $fontStyle;
		$googleFontsArray = array();
		$qryValue = array();
		$travelCAUser = wp_get_current_user();
		$qry = "select * from {$wpdb->prefix}ui where userId = '{$travelCAUser->ID}' ";
		$qryResult = $wpdb->get_results($qry,'ARRAY_A' );
		//print_r($qryResult);
		if( count( $qryResult ) > 0  )
		{	
			foreach( $qryResult as $key => $value ) 
				$qryValue = $value;
		}
		else{
			$qry = "select * from {$wpdb->prefix}ui where userId = '1' ";
			$qryResult = $wpdb->get_results($qry,'ARRAY_A' );	
			if( count( $qryResult ) > 0  )
			{	
				foreach( $qryResult as $key => $value ) 
					$qryValue = $value;
			}
		}
		
		foreach( $qryValue as $key => $value)
		{	
			
			if( strlen( $value ) > 1 )
			{			
				if ( preg_match("/Font_Family/", $key ) )
				{
					if( !in_array( $value ,  $googleFontsArray )) $googleFontsArray[] = $value;	
				}
			}
		}
		$fontStyle = implode(", ", $googleFontsArray );
		$returnArr = array();
		$returnArr['fontStyle'] = $googleFontsArray;
		$returnArr['fieldValue'] = $qryValue;
		return $googleFontsArray;
	}
	
	private function hexToRgb($hex, $alpha = false) {
	   $hex      = str_replace('#', '', $hex);
	   $length   = strlen($hex);
	   $rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
	   $rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
	   $rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
	   if ( $alpha ) {
		  $rgb['a'] = $alpha;
	   }
	   return $rgb;
	}
	public function getCssData()
	{	global $wpdb;
		global $fontStyle;
		
		$travelCAUser = wp_get_current_user();
		$qry = "select * from {$wpdb->prefix}ui where userId = '{$travelCAUser->ID}' ";
		$qryResult = $wpdb->get_results($qry,'ARRAY_A' );								
		
			$qry = "select * from {$wpdb->prefix}ui where userId = '1' ";
			$qryResult = $wpdb->get_results($qry,'ARRAY_A' );	
			if( count( $qryResult ) > 0  )
			{	
				foreach( $qryResult as $key => $value ) 
					extract($value);
			}
		
		
		////.btn-light,.input-group input[type=text],.input-group input[type=date],.input-group input[type=time],  .input-group select , .input-group textarea , .row.alert.alert-field-example
		$homeUrl = home_url( $path = '', $scheme = null )."/";
		$returnCss = '<script>var thisHost = "'. $homeUrl .'";</script>';			

		
		$returnCss .=  " <style type='text/css'>
		
        .carousel-control-next .carousel-control-next-icon, .carousel-control-prev .carousel-control-prev-icon {
    width: 30px;
    height: 30px;
}

		@media (min-width: 576px)
		{
			#submitPageFormModal .modal-dialog {
				 max-width: 100%; 
				margin: .5rem
			}
			
			
		}
		#submitPageFormModal .modal-dialog {
				 max-width: 100%; 
				margin: .5rem 
			}
		
							.content-wrapper, .wrapper, section.content,  html body
						   {";
								if ( $Body_BackGround_Color  != '' ) $returnCss .=   "background-color: {$Body_BackGround_Color};"  ;
								if ( $Body_Font_Color != '' ) $returnCss .=   "color: {$Body_Font_Color};"  ;
								if ( $Body_Font_Family != '' ) $returnCss .=   "font-family: {$Body_Font_Family};"  ;
								if ( $Body_Font_Size != '' ) $returnCss .=   "font-size: {$Body_Font_Size};"  ;
			$returnCss .= "}
							html body  a ,.navbar-light .navbar-brand , #footer-2 a
						   {";
								if ( $Body_Font_Color != '' ) $returnCss .=   "color: {$Body_Font_Color};"  ;
								if ( $Body_Font_Family != '' ) $returnCss .=   "font-family: {$Body_Font_Family};"  ;
								if ( $Body_Font_Size != '' ) $returnCss .=   "font-size: {$Body_Font_Size};"  ;
			$returnCss .= "}	



						   html body .submitPageFormCard.card 
						   {";
								if ( $Form_BackGround_Color != '' ) $returnCss .=   "background-color: {$Form_BackGround_Color};"  ;
								if ( $Form_Font_Color != '' ) $returnCss .=   "color: {$Form_Font_Color};"  ;
								if ( $Form_Font_Family != '' ) $returnCss .=   "font-family: {$Form_Font_Family};"  ;
								if ( $Form_Font_Size != '' ) $returnCss .=   "font-size: {$Form_Font_Size};"  ;
								if ( $Form_Border_Size != '' )      $returnCss .=  "border: {$Form_Border_Size} solid;" ;
								if ( $Form_Border_Color != '' )     $returnCss .=  "border-color: {$Form_Border_Color};"  ;
								if ( $Form_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Form_Corner_Radius}px;";
			$returnCss .= "}
						   .submitPageFormCard.card .card-header
						   {";
								if ( $Form_Header_BackGround_Color != '' ) $returnCss .=   "background-color: {$Form_Header_BackGround_Color};"  ;
								if ( $Form_Header_Font_Color != '' ) $returnCss .=   "color: {$Form_Header_Font_Color};"  ;
								if ( $Form_Header_Font_Family != '' ) $returnCss .=   "font-family: {$Form_Header_Font_Family};"  ;
								if ( $Form_Header_Font_Size != '' ) $returnCss .=   "font-size: {$Form_Header_Font_Size};"  ;
								if ( $Form_Border_Size  != '' && $Form_Border_Color != '' )      $returnCss .=  "border-bottom: {$Form_Border_Size} {$Form_Border_Color} solid;" ;
								
			$returnCss .= "}



						html body .reportPageFormCard.card 
						{";
								if ( $Search_BackGround_Color != '' ) $returnCss .=   "background-color: {$Search_BackGround_Color};"  ;
								if ( $Search_Font_Color != '' ) $returnCss .=   "color: {$Search_Font_Color};"  ;
								if ( $Search_Font_Family != '' ) $returnCss .=   "font-family: {$Search_Font_Family};"  ;
								if ( $Search_Font_Size != '' ) $returnCss .=   "font-size: {$Search_Font_Size};"  ;
								if ( $Search_Border_Size != '' )      $returnCss .=  "border: {$Search_Border_Size} solid;" ;
								if ( $Search_Border_Color != '' )     $returnCss .=  "border-color: {$Search_Border_Color};"  ;
								if ( $Search_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Search_Corner_Radius}px;";
			$returnCss .= "}
						.reportPageFormCard.card .card-header
						{";
								if ( $Search_Header_BackGround_Color != '' ) $returnCss .=   "background-color: {$Search_Header_BackGround_Color};"  ;
								if ( $Search_Header_Font_Color != '' ) $returnCss .=   "color: {$Search_Header_Font_Color};"  ;
								if ( $Search_Header_Font_Family != '' ) $returnCss .=   "font-family: {$Search_Header_Font_Family};"  ;
								if ( $Search_Header_Font_Size != '' ) $returnCss .=   "font-size: {$Search_Header_Font_Size};"  ;
								if ( $Search_Border_Size  != '' && $Search_Border_Color != '' )      $returnCss .=  "border-bottom: {$Search_Border_Size} {$Form_Border_Color} solid;" ;
							
			$returnCss .= "}
									
										
			
			
							nav.navbar
						   {";
								if (  $Top_Menu_BackGround_Color != '' ) $returnCss .=   "background-color: {$Top_Menu_BackGround_Color};" ;
								if (  $Top_Menu_Box_Shadow_Color   != '' && ($Top_Menu_Box_Shadow_Size)  ) $returnCss .=   "box-shadow: {$Top_Menu_Box_Shadow_Size} {$Top_Menu_Box_Shadow_Color};" ;
			$returnCss .= "}
						   nav#navbar_top .navbar-nav > li > a.nav-link
						   {
							    ";
								if (  $Top_Menu_Font_Color != '' ) $returnCss .=   "color: {$Top_Menu_Font_Color};" ;
								if (  $Top_Menu_Font_Family != '' ) $returnCss .=   "font-family: {$Top_Menu_Font_Family};" ;
								if (  $Top_Menu_Font_Size != '' ) $returnCss .=   "font-size: {$Top_Menu_Font_Size};" ;
								if (  $Top_Menu_Font_Weight != '' ) $returnCss .=   "font-weight: {$Top_Menu_Font_Weight};" ;
			$returnCss .= "}
							nav.navbar  .fas
							{";
									if (  $Top_Menu_Font_Color != '' ) $returnCss .=   "color: {$Top_Menu_Font_Color};" ;
			$returnCss .= "} 
							nav#topmenu > li > a:hover,nav  #topmenuright .fas:hover,nav  #topmenuright .fas:focus
						    {";
								if (  $Top_Menu_Hover_Font_Color != '' ) $returnCss .=   "color: {$Top_Menu_Hover_Font_Color};" ;
			$returnCss .= "} 
							



						
							#sidebar
							{";
								if (  $Sidebar_Menu_Box_Shadow_Color  != '' && ($Sidebar_Menu_Box_Shadow_Size)  ) $returnCss .=   "box-shadow: {$Sidebar_Menu_Box_Shadow_Size} {$Sidebar_Menu_Box_Shadow_Color};" ;
							
			$returnCss .= "} 
							aside.main-sidebar , .layout-navbar-fixed .wrapper .sidebar-dark-primary .brand-link:not([class*=navbar]) ,.layout-navbar-fixed .wrapper .sidebar-dark-primary .brand-link:not([class*=navbar]) 
							{";
								if (  $Sidebar_Menu_BackGround_Color != '' ) $returnCss .=   "background-color: {$Sidebar_Menu_BackGround_Color};" ;
			$returnCss .= "}
							aside.main-sidebar .sidebar ul > li.nav-item > a.nav-link, 
							[class*=sidebar-dark-] .sidebar a, 
							aside .brand-text
						   {";
								if (  $Sidebar_Menu_Font_Color != '' ) $returnCss .=   "color: {$Sidebar_Menu_Font_Color};" ;
								if (  $Sidebar_Menu_Font_Family != '' ) $returnCss .=   "font-family: {$Sidebar_Menu_Font_Family};" ;
								if (  $Sidebar_Menu_Font_Size != '' ) $returnCss .=   "font-size: {$Sidebar_Menu_Font_Size};" ;
								if (  $Sidebar_Menu_Font_Weight != '' ) $returnCss .=   "font-weight: {$Sidebar_Menu_Font_Weight};" ;
			$returnCss .= "}
							aside.main-sidebar .sidebar ul.nav-sidebar  > li.menu-open > a.active:hover, 
							aside.main-sidebar .sidebar ul  > li.nav-item > a.nav-link:hover,
							[class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:hover,
							aside.main-sidebar .sidebar ul.nav-sidebar  > li.menu-open > a.active:focus, 
							aside.main-sidebar .sidebar ul  > li.nav-item > a.nav-link:focus,
							[class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:focus
						   {";
								if (  $Sidebar_Menu_Hover_Font_Color != '' ) $returnCss .=   "color: {$Sidebar_Menu_Hover_Font_Color};" ;
								if (  $Sidebar_Menu_Hover_BG_Color != '' ) $returnCss .=   "background-color: {$Sidebar_Menu_Hover_BG_Color};" ;
			$returnCss .= "}
							aside.main-sidebar .sidebar ul.nav-sidebar  > li.menu-open > a.active
						   {";
								if (  $Sidebar_Menu_Active_Font_Color != '' ) $returnCss .=   "color: {$Sidebar_Menu_Active_Font_Color};" ;
								if (  $Sidebar_Menu_Active_BG_Color != '' ) $returnCss .=   "background-color: {$Sidebar_Menu_Active_BG_Color};" ;
			$returnCss .= "}
							[class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active
						   {";
								if (  $Sidebar_SubMenu_Active_Font_Color != '' ) $returnCss .=   "color: {$Sidebar_SubMenu_Active_Font_Color};" ;
								if (  $Sidebar_SubMenu_Active_BG_Color != '' ) $returnCss .=   "background-color: {$Sidebar_SubMenu_Active_BG_Color};" ;
			$returnCss .= "}	
							[class*=sidebar-dark] .brand-link,[class*=sidebar-dark] .user-panel
						   {";
								if (  $Sidebar_Menu_Font_Color != '' ) $returnCss .=   "border-bottom: 1px solid  {$Sidebar_Menu_Font_Color};" ;
			$returnCss .= "}
							[class*=sidebar-dark] .btn-sidebar:focus, [class*=sidebar-dark] .form-control-sidebar:focus,
							[class*=sidebar-dark] .btn-sidebar:hover, [class*=sidebar-dark] .form-control-sidebar:hover ,
							[class*=sidebar-dark] .btn-sidebar, [class*=sidebar-dark] .form-control-sidebar
						{";
								if (  $Sidebar_Menu_Font_Color != '' ) $returnCss .=   "background-color: {$Sidebar_Menu_Font_Color};" ;
								if (  $Sidebar_Menu_Font_Color != '' ) $returnCss .=   "border: 1px solid  {$Sidebar_Menu_Font_Color};" ;
								if (  $Sidebar_Menu_BackGround_Color != '' ) $returnCss .=   "color: {$Sidebar_Menu_BackGround_Color};" ; 
								
			$returnCss .= "} 

						
						.vertical>label.error,
						 .horizontal>label.error,
						 .material>label.error,
						 .vertical>label.error,
							{font-size: 12px;}
							
						   .vertical>label , 
						   .horizontal label, 
						   .form-outline>label, 
						   .material>label,
						   .horizontal .input-group-text
							{";
								 if ( $Label_BackGround_Color != '' ) $returnCss .=  "background-color: {$Label_BackGround_Color};"  ;
								 if ( $Label_Font_Color != '' )       $returnCss .=  "color: {$Label_Font_Color};"  ;
								 if ( $Label_Font_Family != '' )      $returnCss .=  "font-family: {$Label_Font_Family};"  ;
								 if ( $Label_Font_Size != '' )        $returnCss .=  "font-size: {$Label_Font_Size};" ;
								 if ( $Label_Font_Weight != '' )      $returnCss .=  "font-weight: {$Label_Font_Weight};"  ;
			$returnCss .= "}
			
							input.form-control:focus, 	
							textarea.form-control:focus,							
							select.form-control:focus, 
							.input-group > .form-control:not(:first-child):focus, 
							.input-group .form-control select:focus,
							.select2-search__field:focus,
							.bootstrap-select > .dropdown-toggle:focus,
							input.form-control:focus-visible, 	
							textarea.form-control:focus-visible,							
							select.form-control:focus-visible, 
							.input-group > .form-control:not(:first-child):focus-visible, 
							.input-group .form-control select:focus-visible,
							.select2-search__field:focus-visible,
							.bootstrap-select > .dropdown-toggle:focus-visible,
							.select2-container--bootstrap4.select2-container--focus .selection>span.select2-selection,
							.select2-container--bootstrap4.select2-container--open .selection>span.select2-selection,
							.select2-container--bootstrap4 .select2-dropdown,
							.select2-container--bootstrap4 .select2-dropdown.select2-dropdown--above,
							.select2-container--bootstrap4:focus-within .selection>span.select2-selection,
							.horizontal>.input-group:focus-within .input-group-prepend>.input-group-text,
							
							.horizontal>.input-group>.input-group-prepend>.validate-focus
							{ 
								outline:none;";
							  
									  if (  $Field_Hover_Border_Color  != '' )      $returnCss .=  "border-color: {$Field_Hover_Border_Color};" ;
								 
			$returnCss .= "}
						.vertical:focus-within label:first-child,
					.horizontal:focus-within label:first-child,
					.vertical:focus-within label:first-child, 
					.form-outline:focus-within label:first-child, 
					.material:focus-within label:first-child ,
					 .horizontal>.input-group:focus-within .input-group-prepend>.input-group-text,
					 .horizontal>.input-group>.input-group-prepend>.validate-focus,
					 .form-outline>label.validate-focus{"; 
								if ( $Field_Hover_Border_Color != '' )       $returnCss .=  "color: {$Field_Hover_Border_Color};"  ;
			$returnCss .= "}
			
			
			
						label.validate-error-border,
						input.form-control.validate-error-border,
						select.form-control.validate-error-border,
						textarea.form-control.validate-error-border,
						.horizontal .input-group-prepend>.validate-error-border,
						.validate-error-border .selection .select2-selection
						
						{";
								if ( $Fail_Font_Color != '' )       $returnCss .=  "border-color: {$Fail_Font_Color};"  ;
			$returnCss .= "}
						input[type='submit'][value='DELETE'].form-control,
						input[type='submit'][value='TRASH'].form-control
						{";
								if ( $Fail_Font_Color != '' )       $returnCss .=  "background-color: {$Fail_Font_Color};color:#FFFFFF"  ;
			$returnCss .= "}
						.horizontal .input-group .validate-error,
						label.validate-error,
						 .vertical>label.validate-error,
						 .horizontal>label.validate-error,
						 .material>label.validate-error,
						 .form-outline>label.validate-error
						
						 {";
								if ( $Fail_Font_Color != '' )       $returnCss .=  "color: {$Fail_Font_Color};"  ;
			$returnCss .= "} 
						 .vertical>label.error,
						 .horizontal>label.error,
						 .material>label.error,
						 .form-outline>label.error
							{";
								if ( $Fail_Font_Color != '' )       $returnCss .=  "color: {$Fail_Font_Color};"  ;
			$returnCss .= "font-size: 12px;}
			
					
							
							.form-outline label.outline-label {
							  top: 8px;
							  left: 8px;
							  position: relative;";
								if ( $Field_BackGround_Color != '' ) $returnCss .=  "background-color: {$Field_BackGround_Color};"  ;
			 $returnCss .= " padding: 0px 5px 0px 5px;
							  z-index: 99;
							} 
							
							select.form-control, 
							textarea.form-control, 
							input.form-control,
							.select2-container
							{";
								  if ( $Field_Border_Size  != '' && $Field_Border_Color != '' )      $returnCss .=  "border: {$Field_Border_Size} solid {$Field_Border_Color};" ;
								 if ( $Field_BackGround_Color != '' ) $returnCss .=  "background-color: {$Field_BackGround_Color};"  ;
								 if ( $Field_Font_Color != '' )       $returnCss .=  "color: {$Field_Font_Color};"  ;
								 if ( $Field_Font_Family != '' )      $returnCss .=  "font-family: {$Field_Font_Family};"  ;
								 if ( $Field_Font_Size != '' )        $returnCss .=  "font-size: {$Field_Font_Size};" ;
								 if ( $Field_Font_Weight != '' )      $returnCss .=  "font-weight: {$Field_Font_Weight};"  ;
								 if ( $Field_Border_Size  != '' && $Field_Border_Color != '' )      $returnCss .=  "border: {$Field_Border_Size} solid {$Field_Border_Color};" ;
								 if ( $Field_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Field_Corner_Radius}px;";
			$returnCss .= "}
			.material input,
			.material textarea,
			.material select, 
			.material .select2-container--bootstrap4 .selection .select2-selection {
					background-color: transparent;
					border: none;";
					 if ( $Field_Border_Size  != '' && $Field_Border_Color != '' )      $returnCss .=  "border-bottom: {$Field_Border_Size} solid {$Field_Border_Color};" ;
					 if ( $Field_BackGround_Color != '' ) $returnCss .=  "background-color: {$Field_BackGround_Color};"  ;
					 if ( $Field_Font_Color != '' )       $returnCss .=  "color: {$Field_Font_Color};"  ;
					 if ( $Field_Font_Family != '' )      $returnCss .=  "font-family: {$Field_Font_Family};"  ;
					 if ( $Field_Font_Size != '' )        $returnCss .=  "font-size: {$Field_Font_Size};" ;
					 if ( $Field_Font_Weight != '' )      $returnCss .=  "font-weight: {$Field_Font_Weight};"  ;
			 $returnCss .= "border-radius: 0;
					outline: none;
					width: 100%;
					margin: 0;
					padding: 0;
					-webkit-box-shadow: none;
					box-shadow: none;
					-webkit-box-sizing: content-box;
					box-sizing: content-box;
					-webkit-transition: border .3s, -webkit-box-shadow .3s;
					transition: border .3s, -webkit-box-shadow .3s;
					transition: box-shadow .3s, border .3s;
					transition: box-shadow .3s, border .3s, -webkit-box-shadow .3s;
				}

							.form-control:disabled, 
							.form-control[readonly],
							.ui-select,
							.input-group-prepend select.form-control, 
							.input-group-prepend textarea.form-control, 
							.input-group-prepend input.form-control,
							.bootstrap-select > .dropdown-toggle
							{"; 
								
								 if ( $Field_BackGround_Color != '' ) $returnCss .=  "background-color: {$Field_BackGround_Color};"  ;
								 if ( $Field_Font_Color != '' )       $returnCss .=  "color: {$Field_Font_Color};"  ;
								 if ( $Field_Font_Family != '' )      $returnCss .=  "font-family: {$Field_Font_Family};"  ;
								 if ( $Field_Font_Size != '' )        $returnCss .=  "font-size: {$Field_Font_Size};" ;
								 if ( $Field_Font_Weight != '' )      $returnCss .=  "font-weight: {$Field_Font_Weight};"  ;
								 if ( $Field_Border_Size  != '' && $Field_Border_Color  != '' )      $returnCss .=  "border: {$Field_Border_Size} solid {$Field_Border_Color};" ;
								 if ( $Field_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Field_Corner_Radius}px;";
			$returnCss .= "}
						
							.form-outline .input-group .error{ top:0px; left:2px;";
			$returnCss .= "}
							



			
							table.table th
							{";
							 if ( $Report_Header_BackGround_Color != '' ) $returnCss .=  "background-color: {$Report_Header_BackGround_Color};"  ;
							 if ( $Report_Header_Font_Color != '' )       $returnCss .=  "color: {$Report_Header_Font_Color};"  ;
							 if ( $Report_Header_Font_Family != '' )      $returnCss .=  "font-family: {$Report_Header_Font_Family};"  ;
							 if ( $Report_Header_Font_Size != '' )        $returnCss .=  "font-size: {$Report_Header_Font_Size};" ;
							 if ( $Report_Header_Font_Weight != '' )      $returnCss .=  "font-weight: {$Report_Header_Font_Weight};"  ;
			$returnCss .= "}
							table.table th{";				
							 if (  $Report_Header_Border_Color != '' )     
							 {
								$returnCss .=  "border-top:  {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
								$returnCss .=  "border-bottom:  {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
								$returnCss .=  "border-right:  {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
								$returnCss .=  "border-left:  0;" ;
							 } 
			 $returnCss .= "}
							 table.table th:first-child{";				
								if (  $Report_Header_Border_Color != '' )     
								{
								    $returnCss .=  "border-left:  {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
								} 
			$returnCss .= "}				 
							.table-striped tbody tr:nth-of-type(2n+1) td, .table-striped tbody tr:nth-of-type(2n+1) td:focus, .table-striped tbody tr:nth-of-type(2n+1) a
							{";
							 if ( $Report_Even_Row_BackGround_Color != '' ) $returnCss .=  "background-color: {$Report_Even_Row_BackGround_Color};"  ;
							 if ( $Report_Even_Row_Font_Color != '' )       $returnCss .=  "color: {$Report_Even_Row_Font_Color};"  ;
							 if ( $Report_Even_Row_Font_Family != '' )      $returnCss .=  "font-family: {$Report_Even_Row_Font_Family};"  ;
							 if ( $Report_Even_Row_Font_Size != '' )        $returnCss .=  "font-size: {$Report_Even_Row_Font_Size};" ;
							 if ( $Report_Even_Row_Font_Weight != '' )      $returnCss .=  "font-weight: {$Report_Even_Row_Font_Weight};"  ;
							 
			$returnCss .= " }
							.table-striped tbody tr:not(:last-child) td
							{";
							 if ( $Report_Header_Border_Size  != '' && $Report_Header_Border_Color  )      
							 {
								$returnCss .=  "border-bottom: {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
								//$returnCss .=  "border-top: 0;" ;
							 }  
							  
			$returnCss .= " }
							.table-striped tbody tr td
							{";
							if ( $Report_Header_Border_Size  != '' && $Report_Header_Border_Color  )      
							{
								$returnCss .=  "border-right: {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
							}  
			  
			$returnCss .= " }
							.table-striped tbody tr td:first-child
							{";
							if ( $Report_Header_Border_Size  != '' && $Report_Header_Border_Color  )      
							{
								$returnCss .=  "border-left: {$Report_Header_Border_Size} solid {$Report_Header_Border_Color};" ;
							}  

			$returnCss .= " }
							.table-striped tbody tr.totalTr td
							{";

							//if ( $Report_Header_Border_Size ! ='' && $Total_Row_Border_Color != '' )        $returnCss .=  "border: {$Report_Header_Border_Size} solid {$Total_Row_Border_Color};" ;
			  
			$returnCss .= " }
							.table-striped tbody tr:nth-of-type(2n) td, .table-striped tbody tr:nth-of-type(2n) td:focus , .table-striped tbody tr:nth-of-type(2n) a
							{";
							 if ( $Report_Odd_Row_BackGround_Color != '' ) $returnCss .=  "background-color: {$Report_Odd_Row_BackGround_Color};"  ;
							 if ( $Report_Odd_Row_Font_Color != '' )       $returnCss .=  "color: {$Report_Odd_Row_Font_Color};"  ;
							 if ( $Report_Odd_Row_Font_Family != '' )      $returnCss .=  "font-family: {$Report_Odd_Row_Font_Family};"  ;
							 if ( $Report_Odd_Row_Font_Size != '' )        $returnCss .=  "font-size: {$Report_Odd_Row_Font_Size};" ;
							 if ( $Report_Odd_Row_Font_Weight != '' )      $returnCss .=  "font-weight: {$Report_Odd_Row_Font_Weight};"  ;
							  
			$returnCss .= " }
							.table-striped tbody tr.totalTr
							{";
							 if ( $Total_Row_BackGround_Color != '' ) $returnCss .=  "background-color: {$Total_Row_BackGround_Color};"  ;
							 if ( $Total_Row_Font_Color != '' )       $returnCss .=  "color: {$Total_Row_Font_Color};"  ;
							 if ( $Total_Row_Font_Family != '' )      $returnCss .=  "font-family: {$Total_Row_Font_Family};"  ;
							 if ( $Total_Row_Font_Size != '' )        $returnCss .=  "font-size: {$Total_Row_Font_Size};" ;
							 if ( $Total_Row_Font_Weight != '' )      $returnCss .=  "font-weight: {$Total_Row_Font_Weight};"  ;
			$returnCss .= " }";
			
			
			$returnCss .= "body .row .input-group .dangerField, 
							body .row .input-group .dangerField:focus, 
							body .row .input-group .dangerField:hover
							{";
							
							 if ( $Fail_BackGround_Color != '' ) $returnCss .=  "background-color: {$Fail_BackGround_Color};"  ;
							 if ( $Fail_Font_Color != '' )       $returnCss .=  "color: {$Fail_Font_Color};"  ;
							 if ( $Fail_Border_Color != '' )     $returnCss .=  "border-color: {$Fail_Border_Color};"  ;
			
								
			$returnCss .= "}
							
							.dropdown-item.active {height: 100%; }
							/*
							input[type='button'].form-control,.dropdown-menu,.dropdown-item 
							{";
							 $returnCss .= "cursor: pointer;";	
							$returnCss .= "min-height: calc(2.25rem + 2px);";
							 if ( $Button_BackGround_Color != '' ) $returnCss .=  "background-color: {$Button_BackGround_Color};"  ;
							 if ( $Button_Font_Color != '' )       $returnCss .=  "color: {$Button_Font_Color};"  ;
							 if ( $Button_Font_Family != '' )      $returnCss .=  "font-family: {$Button_Font_Family};"  ;
							 if ( $Button_Font_Size != '' )        $returnCss .=  "font-size: {$Button_Font_Size};" ;
							 if ( $Button_Font_Weight != '' )      $returnCss .=  "font-weight: {$Button_Font_Weight};"  ;
							 if ( $Button_Border_Size  != '' && $Button_Border_Color != '' )      $returnCss .=  "border: {$Button_Border_Size} solid {$Button_Border_Color};" ;
							 if ( $Button_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Button_Corner_Radius}px;";
			$returnCss .= "}*/
							input[type='button'].form-control:focus
							{";
							 $returnCss .= "cursor: pointer;";		
							 if ( $Button_Hover_BackGround_Color != '' ) $returnCss .=  "background-color: {$Button_Hover_BackGround_Color};"  ;
							 if ( $Button_Hover_Font_Color != '' )       $returnCss .=  "color: {$Button_Hover_Font_Color};"  ;
 
			$returnCss .= "}
							input[type='submit'].form-control
							{";
							 $returnCss .= "cursor: pointer;";
							 $returnCss .= "height: 100%;";	
							$returnCss .= "min-height: calc(2.25rem + 2px);";								 
							 if ( $Submit_Button_BackGround_Color != '' ) $returnCss .=  "background-color: {$Submit_Button_BackGround_Color};"  ;
							 if ( $Submit_Button_Font_Color != '' )       $returnCss .=  "color: {$Submit_Button_Font_Color};"  ;
							 if ( $Submit_Button_Font_Family != '' )      $returnCss .=  "font-family: {$Submit_Button_Font_Family};"  ;
							 if ( $Submit_Button_Font_Size != '' )        $returnCss .=  "font-size: {$Submit_Button_Font_Size};" ;
							 if ( $Submit_Button_Font_Weight != '' )      $returnCss .=  "font-weight: {$Submit_Button_Font_Weight};"  ;
							 if ( $Submit_Button_Border_Size  != '' && $Submit_Button_Border_Color != '' )      $returnCss .=  "border: {$Submit_Button_Border_Size} solid {$Submit_Button_Border_Color};" ;
							 if ( $Submit_Button_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Submit_Button_Corner_Radius}px;";
			$returnCss .= "}
							input[type='submit'].form-control:focus
							{";
								$returnCss .= "cursor: pointer;";	
								$returnCss .= "height: 100%;";	
$returnCss .= "min-height: calc(2.25rem + 2px);";										
							 if ( $Submit_Button_Hover_BackGround_Color != '' ) $returnCss .=  "background-color: {$Submit_Button_Hover_BackGround_Color};"  ;
							 if ( $Submit_Button_Hover_Font_Color != '' )       $returnCss .=  "color: {$Submit_Button_Hover_Font_Color};"  ;
							 if ( $Submit_Button_Border_Color != '' ) 
							 {	 $rgbColor = $this->hexToRgb($Submit_Button_Border_Color);
								 $returnCss .=  "box-shadow: 0 0 0 0.2rem rgba({$rgbColor['r']}, {$rgbColor['g']}, {$rgbColor['b']}, 0.50);";
							 }
			$returnCss .= "}
							.alert.alert-danger
							{";
								
							 if ( $Fail_BackGround_Color != '' ) $returnCss .=  "background-color: {$Fail_BackGround_Color};"  ;
							 if ( $Fail_Font_Color != '' )       $returnCss .=  "color: {$Fail_Font_Color};"  ;
							 if ( $Fail_Font_Family != '' )      $returnCss .=  "font-family: {$Fail_Font_Family};"  ;
							 if ( $Fail_Font_Size != '' )        $returnCss .=  "font-size: {$Fail_Font_Size};" ;
							 if ( $Fail_Font_Weight != '' )      $returnCss .=  "font-weight: {$Fail_Font_Weight};"  ;
							 if ( $Fail_Border_Size  != '' && $Fail_Border_Color != '' )      $returnCss .=  "border: {$Fail_Border_Size} solid {$Fail_Border_Color};" ;
							 if ( $Fail_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Fail_Corner_Radius}px;";
			$returnCss .= "}
							.alert.alert-danger a
							{";
						    if ( $Fail_Font_Color != '' )       $returnCss .=  "color: {$Fail_Font_Color};"  ;
			$returnCss .= "}
			.control-label
							{";
								
							 if ( $Label_Font_Color != '' )       $returnCss .=  "color: {$Label_Font_Color};"  ;
							 if ( $Label_Font_Family != '' )      $returnCss .=  "font-family: {$Label_Font_Family};"  ;
							 if ( $Label_Font_Size != '' )        $returnCss .=  "font-size: {$Label_Font_Size};" ;
							 if ( $Label_Font_Weight != '' )      $returnCss .=  "font-weight: {$Label_Font_Weight};"  ;
			$returnCss .= "}
							.alert.alert-success
							{";
								
							 if ( $Success_BackGround_Color != '' ) $returnCss .=  "background-color: {$Success_BackGround_Color};"  ;
							 if ( $Success_Font_Color != '' )       $returnCss .=  "color: {$Success_Font_Color};"  ;
							 if ( $Success_Font_Family != '' )      $returnCss .=  "font-family: {$Success_Font_Family};"  ;
							 if ( $Success_Font_Size != '' )        $returnCss .=  "font-size: {$Success_Font_Size};" ;
							 if ( $Success_Font_Weight != '' )      $returnCss .=  "font-weight: {$Success_Font_Weight};"  ;
							 if ( $Success_Border_Size  != '' &&  $Success_Border_Color != '' )      $returnCss .=  "border: {$Success_Border_Size} solid {$Success_Border_Color};" ;
							 if ( $Success_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Success_Corner_Radius}px;";
			$returnCss .= "}
							.alert.alert-success a
							{";
						    if ( $Success_Font_Color != '' )       $returnCss .=  "color: {$Success_Font_Color};"  ;
			$returnCss .= "}
							.alert.alert-info
							{";
								
							 if ( $Info_BackGround_Color != '' ) $returnCss .=  "background-color: {$Info_BackGround_Color};"  ;
							 if ( $Info_Font_Color != '' )       $returnCss .=  "color: {$Info_Font_Color};"  ;
							 if ( $Info_Font_Family != '' )      $returnCss .=  "font-family: {$Info_Font_Family};"  ;
							 if ( $Info_Font_Size != '' )        $returnCss .=  "font-size: {$Info_Font_Size};" ;
							 if ( $Info_Font_Weight != '' )      $returnCss .=  "font-weight: {$Info_Font_Weight};"  ;
							 if ( $Info_Border_Size  != '' && $Info_Border_Color != '' )      $returnCss .=  "border: {$Info_Border_Size} solid {$Info_Border_Color};" ;
							 if ( $Info_Corner_Radius != '' )    $returnCss .=  "border-radius: {$Info_Corner_Radius}px;";
			$returnCss .= "}
							.alert.alert-info a
							{";
						    if ( $Info_Font_Color != '' )       $returnCss .=  "color: {$Info_Font_Color};"  ;
			$returnCss .= "}
							.form-control::-ms-input-placeholder,.form-control::-moz-placeholder,.form-control::-webkit-input-placeholder
							{";
							
								if ( $Placeholder_Font_Color != '' )       $returnCss .=  "color: {$Placeholder_Font_Color};"  ;
			$returnCss .= "}
		
							
							
						section.invoice	 
						{ ";
						
							if ( isset($Print_Font_Size) )       $returnCss .=  "font-size: {$Print_Font_Size};"  ;
							if ( isset($Print_Font_Family) )       $returnCss .=  "font-family: {$Print_Font_Family};"  ;
							if ( isset($Print_Font_Weight) )       $returnCss .=  "font-weight: {$Print_Font_Weight};"  ;
			$returnCss .= " }
						section.invoice	.firm label.header 
						{";
							if ( isset($Firm_Header_Font_Size) )       $returnCss .=  "font-size: {$Firm_Header_Font_Size};"  ;
							if ( isset($Firm_Header_Font_Family) )       $returnCss .=  "font-family: {$Firm_Header_Font_Family};"  ;
							if ( isset($Firm_Header_Font_Weight) )       $returnCss .=  "font-weight: {$Firm_Header_Font_Weight};"  ;
							
			$returnCss .= " }
						section.invoice	.firm label.text 
						{";
							if ( isset($Firm_Text_Font_Size) )       $returnCss .=  "font-size: {$Firm_Text_Font_Size};"  ;
							if ( isset($Firm_Text_Font_Family) )       $returnCss .=  "font-family: {$Firm_Text_Font_Family};"  ;
							if ( isset($Firm_Text_Font_Weight) )       $returnCss .=  "font-weight: {$Firm_Text_Font_Weight};"  ;
							
			$returnCss .= " }
					section.invoice	.firm label.label 
						{";
							if ( isset($Firm_Label_Font_Size) )       $returnCss .=  "font-size: {$Firm_Label_Font_Size};"  ;
							if ( isset($Firm_Label_Font_Family) )       $returnCss .=  "font-family: {$Firm_Label_Font_Family};"  ;
							if ( isset($Firm_Label_Font_Weight) )       $returnCss .=  "font-weight: {$Firm_Label_Font_Weight};"  ;
							
			$returnCss .= " }
			section.invoice	.account label.header 
						{";
							if ( isset($Account_Header_Font_Size) )       $returnCss .=  "font-size: {$Account_Header_Font_Size};"  ;
							if ( isset($Account_Header_Font_Family) )       $returnCss .=  "font-family: {$Account_Header_Font_Family};"  ;
							if ( isset($Account_Header_Font_Weight) )       $returnCss .=  "font-weight: {$Account_Header_Font_Weight};"  ;
							
			$returnCss .= " }
						section.invoice	.account label.text 
						{";
							if ( isset($Account_Text_Font_Size) )       $returnCss .=  "font-size: {$Account_Text_Font_Size};"  ;
							if ( isset($Account_Text_Font_Family) )       $returnCss .=  "font-family: {$Account_Text_Font_Family};"  ;
							if ( isset($Account_Text_Font_Weight) )       $returnCss .=  "font-weight: {$Account_Text_Font_Weight};"  ;
							
			$returnCss .= " }
					section.invoice	.account label.label 
						{";
							if ( isset($Account_Label_Font_Size) )       $returnCss .=  "font-size: {$Account_Label_Font_Size};"  ;
							if ( isset($Account_Label_Font_Family) )       $returnCss .=  "font-family: {$Account_Label_Font_Family};"  ;
							if ( isset($Account_Label_Font_Weight) )       $returnCss .=  "font-weight: {$Account_Label_Font_Weight};"  ;
							
			$returnCss .= " }	
			section.invoice	.invoice2 label.text
						{";
							if ( isset($Invoice_Text_Font_Size) )       $returnCss .=  "font-size: {$Invoice_Text_Font_Size};"  ;
							if ( isset($Invoice_Text_Font_Family) )       $returnCss .=  "font-family: {$Invoice_Text_Font_Family};"  ;
							if ( isset($Invoice_Text_Font_Weight) )       $returnCss .=  "font-weight: {$Invoice_Text_Font_Weight};"  ;
							
			$returnCss .= " }
					section.invoice2 .invoice2 label.label 
						{";
							if ( isset($Invoice_Label_Font_Size) )       $returnCss .=  "font-size: {$Invoice_Label_Font_Size};"  ;
							if ( isset($Invoice_Label_Font_Family) )       $returnCss .=  "font-family: {$Invoice_Label_Font_Family};"  ;
							if ( isset($Invoice_Label_Font_Weight) )       $returnCss .=  "font-weight: {$Invoice_Label_Font_Weight};"  ;
							
			$returnCss .= " }
			section.invoice	.table-responsive table.table-striped thead th
						{";
							if ( isset($Table_Header_Font_Size) )       $returnCss .=  "font-size: {$Table_Header_Font_Size};"  ;
							if ( isset($Table_Header_Font_Family) )       $returnCss .=  "font-family: {$Table_Header_Font_Family};"  ;
							if ( isset($Table_Header_Font_Weight) )       $returnCss .=  "font-weight: {$Table_Header_Font_Weight};"  ;
							
			$returnCss .= " }
					section.invoice	.table-responsive table.table-striped  tbody td
						{";
							if ( isset($Table_row_Font_Size) )       $returnCss .=  "font-size: {$Invoice_Label_Font_Size};"  ;
							if ( isset($Table_row_Font_Family) )       $returnCss .=  "font-family: {$Table_row_Font_Family};"  ;
							if ( isset($Table_row_Font_Weight) )       $returnCss .=  "font-weight: {$Table_row_Font_Weight};"  ;
							
			$returnCss .= " }			
					</style>";
		return $returnCss;
			
			
	}
	
}

