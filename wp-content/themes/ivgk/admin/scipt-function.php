<?php
add_action( 'wp_enqueue_scripts', 'saplingTechEnqueueScripts' ,999 );
function saplingTechEnqueueScripts()
{	
	global $wp;
	$enqueueCss = new classCSS();
	$cssLink = $enqueueCss->getEnqueueCss();
	if( is_array( $cssLink) )
	 {
		wp_register_style( 'googleFonts' ,   'https://fonts.googleapis.com/css?family='.urlencode( implode("|", array_values($cssLink) ) ) );
 	wp_enqueue_style( 'googleFonts' );
	 }
    //echo plugin_dir( __DIR__ );
    /*
    $classCSS = new classCSS();
	$cssLink = $classCSS->getEnqueueCss();
	if( is_array( $cssLink) )
	{   //foreach( $cssLink as $value)
        {
            //implode("|", array_values($cssLink) )
            wp_register_style( "googleFonts-CSS" ,   'https://fonts.googleapis.com/css?family='.implode("|", array_values($cssLink) ) );
		    wp_enqueue_style( "googleFonts-CSS" );
        }
		
    }
    
    
    wp_register_style('fontCSS',  'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
    wp_enqueue_style('fontCSS');
    */

	wp_register_style('allCSS',  get_template_directory_uri() . '/css/all.min.css');
    wp_enqueue_style('allCSS');
	
//	wp_register_style('fontAwesome',  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
  //  wp_enqueue_style('fontAwesome');
	
	
    
  //  wp_register_style('overlayScrollbarsCSS',  get_template_directory_uri() . '/css/OverlayScrollbars.min.css');
   // wp_enqueue_style('overlayScrollbarsCSS');
    
  //  wp_register_style('adminLteCSS',  get_template_directory_uri() . '/css/adminlte.min.css');
    wp_register_style('adminLteCSS',  get_template_directory_uri() . '/css/new.css');
    wp_enqueue_style('adminLteCSS');
    //wp_register_style('cropperCSS',  get_template_directory_uri() . '/css/cropper.css');
    //wp_enqueue_style('cropperCSS');
	
	//wp_register_style('themifyCSS',  get_template_directory_uri() . '/css/themify-icons.min.css');
   // wp_enqueue_style('themifyCSS');
	wp_register_style('dflipCSS',  get_template_directory_uri() . '/css/dflip.min.css');
    wp_enqueue_style('dflipCSS');
	
   // wp_register_style('bootstrapDataTableCSS',  get_template_directory_uri() . '/css/dataTables.bootstrap4.min.css');
   // wp_enqueue_style('bootstrapDataTableCSS');
    
   // wp_register_style('bootstrapResponsiveCSS',  get_template_directory_uri() . '/css/responsive.bootstrap4.min.css');
   // wp_enqueue_style('bootstrapResponsiveCSS');

   // wp_register_style('bootstrapButtonCSS',  get_template_directory_uri() . '/css/buttons.bootstrap4.min.css');
   // wp_enqueue_style('bootstrapButtonCSS');
    
    //wp_register_style('toastrCSS',  get_template_directory_uri() . '/css/toastr.min.css');
    //wp_enqueue_style('toastrCSS');

	//wp_register_style('selectizeCSS',  get_template_directory_uri() . '/css/selectize.bootstrap3.min.css');
   // wp_enqueue_style('selectizeCSS');
  //  wp_register_style('select2CSS',  get_template_directory_uri() . '/css/select2.min.css');
  // wp_enqueue_style('select2CSS');

  // wp_register_style('select2BootstrapCSS',  get_template_directory_uri() . '/css/select2-bootstrap4.min.css');
  //  wp_enqueue_style('select2BootstrapCSS');

   // wp_register_style('jqueryDataTableCSS',  get_template_directory_uri()  . '/css/jquery.dataTables.min.css');
   // wp_enqueue_style('jqueryDataTableCSS');

///wp_register_style('dateRangePickerCSS',  get_template_directory_uri()  . '/css/daterangepicker.css');
   // wp_enqueue_style('dateRangePickerCSS');

    //wp_register_style('jqueryUICSS',  get_template_directory_uri()  . '/css/jquery-ui.css');
   // wp_enqueue_style('jqueryUICSS');

	wp_register_style('mainStyle',  get_template_directory_uri()  . '/style.css');
    wp_enqueue_style('mainStyle');
	
	wp_register_script('jqueryJS',  get_template_directory_uri()  . '/js/jquery.min.js',array(), false, false);
    wp_enqueue_script('jqueryJS');
    //wp_register_script('jqueryContentJS',  get_template_directory_uri()  . '/js/jquery.contextMenu.min.js',array(), false, false);
   // wp_enqueue_script('jqueryContentJS');
    
    wp_register_script('jqueryUI',  get_template_directory_uri()  . '/js/jquery-ui.min.js',array(), false, false);
    wp_enqueue_script('jqueryUI');

   wp_register_script('jqueryValidatorJS',  get_template_directory_uri()  . '/js/jquery.validate.min.js',array(), false, true);
    wp_enqueue_script('jqueryValidatorJS');
	
	// MODAL
    wp_register_script('bootstrapBundleJS',  get_template_directory_uri()  . '/js/bootstrap.bundle.min.js',array(), false, true);
    wp_enqueue_script('bootstrapBundleJS');

    wp_register_script('adminLteJS',  get_template_directory_uri()  . '/js/adminlte.js',array(), false, true);
    wp_enqueue_script('adminLteJS'); 

    wp_register_script('jqueryDataTableJS',  get_template_directory_uri()  . '/js/jquery.dataTables.min.js',array(), false, true);
    wp_enqueue_script('jqueryDataTableJS');
    /*
    wp_register_script('bootstrapDataTableJS',  get_template_directory_uri()  . '/js/dataTables.bootstrap4.min.js',array(), false, true);
    wp_enqueue_script('bootstrapDataTableJS');

    wp_register_script('responsiveDataTableJS',  get_template_directory_uri()  . '/js/dataTables.responsive.min.js',array(), false, true);
    wp_enqueue_script('responsiveDataTableJS');

    wp_register_script('responsiveBootStrapeJS',  get_template_directory_uri()  . '/js/responsive.bootstrap4.min.js',array(), false, true);
    wp_enqueue_script('responsiveBootStrapeJS');

    wp_register_script('dataTableButtonJS',  get_template_directory_uri()  . '/js/dataTables.buttons.min.js',array(), false, true);
    wp_enqueue_script('dataTableButtonJS');
    
    wp_register_script('buttonBootStrapJS',  get_template_directory_uri()  . '/js/buttons.bootstrap4.min.js',array(), false, true);
    wp_enqueue_script('buttonBootStrapJS');
    */
   // wp_register_script('jsZipJS',  get_template_directory_uri()  . '/js/jszip.min.js',array(), false, true);
    //wp_enqueue_script('jsZipJS');

  //  wp_register_script('pdfMakeJS',  get_template_directory_uri()  . '/js/pdfmake.min.js',array(), false, true);
   // wp_enqueue_script('pdfMakeJS');
    
   // wp_register_script('vfsFontJS',  get_template_directory_uri()  . '/js/vfs_fonts.js',array(), false, true);
  //  wp_enqueue_script('vfsFontJS');
  //  
    /*
	 wp_register_script('htmlCanvasJS',  get_template_directory_uri()  . '/js/html2canvas.min.js',array(), false, true);
    wp_enqueue_script('htmlCanvasJS');
   wp_register_script('threeJS',  get_template_directory_uri()  . '/js/three.min.js',array(), false, true);
    wp_enqueue_script('threeJS');
    
    wp_register_script('pdfJS',  get_template_directory_uri()  . '/js/pdf.min.js',array(), false, true);
    wp_enqueue_script('pdfJS');
   wp_register_script('flipJS',  get_template_directory_uri()  . '/js/3dflipbook.min.js',array(), false, true);
    wp_enqueue_script('flipJS');
     */
		
		 wp_register_script('googleMap', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDof7SucDawoYij84ke1GUAj3H7dkHwdGY&callback=initMap',array(), false, true);
   if( preg_match('/connects/' ,home_url( $wp->request ) ) )
		wp_enqueue_script('googleMap');
	
	 wp_register_script('flipJS',  get_template_directory_uri()  . '/js/dflip.min.js',array(), false, true);
    wp_enqueue_script('flipJS');
    wp_register_script('buttonHtml5JS',  get_template_directory_uri()  . '/js/buttons.html5.min.js',array(), false, true);
    wp_enqueue_script('buttonHtml5JS');

   wp_register_script('buttonPrintJS',  get_template_directory_uri()  . '/js/buttons.print.min.js',array(), false, true);
    wp_enqueue_script('buttonPrintJS');

    wp_register_script('buttonColVisJS',  get_template_directory_uri()  . '/js/buttons.colVis.min.js',array(), false, true);
    wp_enqueue_script('buttonColVisJS');

   // wp_register_script('toastrJS',  get_template_directory_uri()  . '/js/toastr.min.js',array(), false, true);
  //  wp_enqueue_script('toastrJS');

   wp_register_script('select2JS',  get_template_directory_uri()  . '/js/select2.full.min.js',array(), false, true);
    wp_enqueue_script('select2JS');

	//wp_register_script('selectizeJS',  get_template_directory_uri()  . '/js/selectize.min.js',array(), false, true);
   // wp_enqueue_script('selectizeJS');
   
    wp_register_script('blockUiJS',  get_template_directory_uri()  . '/js/blockUI.js',array(), false, true);
    wp_enqueue_script('blockUiJS');
    
    wp_register_script('momentJS', get_template_directory_uri()  . '/js/moment.min.js',array(), false, true);
    wp_enqueue_script('momentJS');
	
	//wp_register_script('cropperJS', get_template_directory_uri()  . '/js/cropper.js',array(), false, true);
   // wp_enqueue_script('cropperJS');

	//wp_register_script('dateRangePickerJS', get_template_directory_uri()  . '/plugins/inputmask/jquery.inputmask.min.js',array(), false, true);
    //wp_enqueue_script('dateRangePickerJS');

    wp_register_script('dateRangePickerJS', get_template_directory_uri()  . '/js/daterangepicker.js',array(), false, true);
   wp_enqueue_script('dateRangePickerJS');

   //wp_register_script('loaderJS', get_template_directory_uri()  . '/js/loader.js',array(), false, true);
   //wp_enqueue_script('loaderJS');
	 wp_register_script('chartJS', get_template_directory_uri()  . '/js/chart.min.js',array(), false, true);
   wp_enqueue_script('chartJS');
 wp_register_script('chartPluginJS', get_template_directory_uri()  . '/js/chartjs-plugin.js',array(), false, true);
   wp_enqueue_script('chartPluginJS');
    wp_register_script('validatorJS',  get_template_directory_uri()  . '/js/validator.js',array(), false, true);
    wp_enqueue_script('validatorJS');

    //wp_register_script('overlayScrollbarsJS',  get_template_directory_uri()  . '/js/jquery.overlayScrollbars.min.js',array(), false, true);
    //wp_enqueue_script('overlayScrollbarsJS');

	//wp_register_script('hotKeyJS',  get_template_directory_uri()  . '/js/jquery.hotkeys-0.7.9.min.js',array(), false, true);
   // wp_enqueue_script('hotKeyJS');
	
    // wp_register_script('jqueryMouseWheelJS',  get_template_directory_uri()  . '/plugins/jquery-mousewheel/jquery.mousewheel.js',array(), false, true);
    // wp_enqueue_script('jqueryMouseWheelJS');

    //wp_register_script('raphaelJS',  get_template_directory_uri()  . '/js/raphael.min.js',array(), false, true);
   // wp_enqueue_script('raphaelJS');

	//wp_register_script('flowJS',  get_template_directory_uri()  . '/js/flowchart-latest.js',array(), false, true);
   // wp_enqueue_script('flowJS'); 
	
    // wp_register_script('mapaelJS',  get_template_directory_uri()  . '/plugins/jquery-mapael/jquery.mapael.min.js',array(), false, true);
    // wp_enqueue_script('mapaelJS');

   // wp_register_script('usaStateJS',  get_template_directory_uri()  . '/plugins/jquery-mapael/maps/usa_states.min.js',array(), false, true);
   // wp_enqueue_script('usaStateJS');

   // wp_register_script('chartJS',  get_template_directory_uri()  . '/plugins/chart.js/Chart.min.js',array(), false, true);
   // wp_enqueue_script('chartJS');

    //wp_register_script('demoJS',  get_template_directory_uri()  . '/dist/js/demo.js',array(), false, true);
   // wp_enqueue_script('demoJS');

    //wp_register_script('dashboard2JS',  get_template_directory_uri()  . '/dist/js/pages/dashboard2.js',array(), false, true);
    //wp_enqueue_script('dashboard2JS');


    
	wp_dequeue_style('wp-block-library');
	wp_deregister_style('wp-block-library');
	
	wp_dequeue_script( 'wp-embed' );
	wp_deregister_script('wp-embed');
	wp_dequeue_style( 'global-styles' );
	wp_deregister_script('global-styles');
	

	
global $wp_styles;
//print_r( $wp_styles );
	
}
 