<?php

@ini_set( 'max_upload_filesize' , '256M' );
@ini_set( 'post_max_size', '256M');

if ( ! function_exists( 'saplingtech_setup' ) ) :
function saplingtech_setup() {
    register_nav_menus( array(
		'primary' => __( 'Primary Menu','vw-travel' ),
	) );
}
endif;
add_action( 'after_setup_theme', 'saplingtech_setup' );
global $pagenow;

if( ! session_id() ) session_start();
ob_start(); 
set_time_limit(0);
date_default_timezone_set('Asia/Kolkata');

require get_template_directory() . '/vendor/autoload.php';

register_nav_menus( array(
    'menu' => esc_html__( 'Primary', 'text-domain' ),
));

require get_template_directory() . '/admin/gB.php';
require get_template_directory() . '/admin/wpRestFunc.php';
require get_template_directory() . '/admin/wpRest.php';
require get_template_directory() . '/admin/scipt-function.php';
require get_template_directory() . '/admin/wp-bootstrap-navwalker.php';
require get_template_directory() . '/admin/wpaj.php';
require get_template_directory() . '/admin/navMenuRoles.php';
require get_template_directory() . '/admin/miscFunction.php';
require get_template_directory() . '/admin/classA.php';
require get_template_directory() . '/admin/classC.php';
require get_template_directory() . '/admin/classD.php';
require get_template_directory() . '/admin/classM.php';
require get_template_directory() . '/admin/classU.php';
require get_template_directory() . '/admin/classV.php';
require get_template_directory() . '/admin/PageU.php';
require get_template_directory() . '/admin/PageM.php';
require get_template_directory() . '/admin/PageR.php';
require get_template_directory() . '/admin/PageT.php';
require get_template_directory() . '/admin/PageP.php';
require get_template_directory() . '/admin/PageD.php';
require get_template_directory() . '/admin/PageE.php';
remove_action( 'wp_head', 'wp_generator' );
 remove_action( 'wp_head', 'wc_page_noindex' );
remove_filter( 'wp_robots', 'wp_robots_max_image_preview_large' );

/*
$author = get_role( 'administrator' );
print_r($author); 
*/

add_action('init', 'remheadlink');
function remheadlink() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action( 'wp_head', 'wp_resource_hints', 2, 99 ); 
	remove_action( 'wp_head', 'rest_output_link_wp_head'  ,10 );
	remove_action('wp_head', 'wp_shortlink_wp_head', 10);
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'feed_links',2);

}
add_action('wp_head', 'hook_css');
function hook_css() 
{
	
	  $getCssData = new classCSS();
	  echo $getCssData->getCssData(); 
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	//if (!current_user_can('administrator') && !is_admin()) {
	  show_admin_bar(false);
	//}
}

 

function wpLoginSapling()
{
	
	global $loginError;
	if( isset( $_POST['answer']) &&  isset( $_SESSION['answer'] ))
	{
		if ($_SESSION['answer'] == $_POST['answer'] ) 
		{
			
			global $wpdb;
			$checkTrashUser = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}users WHERE
												isTrash = 0 and is_Activated = 1 AND ( user_login='{$_POST['user_login']}' OR user_email='{$_POST['user_login']}' )
												","ARRAY_A");
			if( count ($checkTrashUser) > 0 )
			{		
				$userId = $checkTrashUser[0]['ID'];
				//$isActivated  = get_user_meta($userId, 'is_activated', true);
				//if( (int)$isActivated  == 1 )
				{
				
					$login_data = array();
					$login_data['user_login'] = $_POST['user_login'];
					$login_data['user_password'] = $_POST['user_password'];
					$login_data['remember'] = isset ( $_POST['remember'] ) ? true : false ;
					$redirect_to = $_POST['redirect_to'];
					$user_verify = wp_signon( $login_data, true);
					if ( is_wp_error( $user_verify ) ) 
					{

						$loginError = $user_verify->get_error_message();
						$loginError = 'Invalid credentials';
					}
					else
					{
						$user = get_user_by('ID', $user_verify->ID);
						$user_id = $user_verify->data->ID;
						if($user) 
						{
							wp_set_current_user($user_id, $user->user_login);
							wp_set_auth_cookie($user_id);
							do_action( 'wp_login', $user->user_login );
							unset($_POST);

							if( strlen($redirect_to) > 0 )
							 if( preg_match("/login/",$redirect_to)) $redirect_to = home_url('welcome');
							 else $redirect_to;
							 wp_redirect( $redirect_to );
							 exit;
						}
					}
				}
				
			}else{
					$loginError = 'User not found or not activated';
				}
		}
		else{
			$loginError = "Your answer is incoreect.";
		}
	}else{
		$loginError = "invalid SESSION.";
	}
	return $loginError;
}
add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
  wp_redirect( home_url()."/login" );
  exit();
}
 
add_filter( 'init', 'saplingTechAjaxSubmit' );

function remove_core_updates(){
    global $wp_version;
	return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');  
add_filter('pre_site_transient_update_themes','remove_core_updates');



function saplingTechAjaxSubmit()
{

	global $pagenow;

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	/*
	if( 'wp-login.php' == $pagenow ) {
		global $wp;
		wp_redirect( home_url('login') );
	 	exit();
	}
	*/
	

	if( isset( $_POST['Action']) )
	{	
		if( $_POST['Action'] == 'LOGIN')
		{	
			$loginError = wpLoginSapling();
		}
	}
	
}




add_action( 'rest_api_init', 'register_upcoming_events_endpoint' );

function register_upcoming_events_endpoint() {
    register_rest_route( 'mytheme/v1', '/upcoming-events', array(
        'methods' => 'GET',
        'callback' => 'get_upcoming_events',
    ) );
}


function get_upcoming_events( $request ) {
    global $wpdb;
    $curDate = date('Y-m-d');
    $upcomingEvents = $wpdb->get_results( "SELECT ID,Event_Name,
                                               TIME_FORMAT( Start_Time,'%h:%i %p' ) as startTime,
                                               DATE_FORMAT(Start_Date, '%W %d,%b-%Y') as startDate 
                                        FROM {$wpdb->prefix}chamber_events 
                                        WHERE isTrash = 0 AND Start_Date >= '{$curDate}'  ORDER BY Start_Date DESC", "ARRAY_A");

    $data = array();

    foreach ( $upcomingEvents as $upcomingEvent ) {
        $galleryImage = '';
        $imgQry = $wpdb->get_results( "SELECT userProfile_1
                                    FROM {$wpdb->prefix}event_gallery 
                                    WHERE isTrash = 0 AND Event='{$upcomingEvent['ID']}'  
                                    ORDER BY View_Order Limit 1", "ARRAY_A");
        if( count ( $imgQry ) > 0 )
        {
            $galleryImage = $imgQry[0]['userProfile_1'];
        }

        $data[] = array(
            'event_name' => $upcomingEvent['Event_Name'],
            'start_date' => $upcomingEvent['startDate'],
            'start_time' => $upcomingEvent['startTime'],
            'gallery_image' => $galleryImage
        );
    }

    return $data;
}













@ini_set( 'max_upload_filesize' , '256M' );
@ini_set( 'post_max_size', '256M');
@ini_set('memory_limit','256M');
@ini_set('post_max_size' , '256M');

// Include only approved pages in WordPress sitemap
add_filter( 'wp_sitemaps_posts_query_args', 'apchambers_include_only_approved_pages', 10, 2 );
function apchambers_include_only_approved_pages( $args, $post_type ) {
    if ( 'page' === $post_type ) {
        // List of approved page slugs (marked as "yes")
        $approved_slugs = array(
            'about-chambers',
            'affiliates-counsil',
            'offices',
            'initiatives-of-chambers',
            'contact',
            'membership-details',
            'benefits-to-members',
            'state-level-committie',
            'special-nri-benefits',
            'become-a-member',
            'certificate-of-origin',
            'magazine-view',
            'past-events',
            'seminars-workshops',
            'print-media',
            'tv-internet',
            'management-committee',
            'board-of-directors',
            'state-level-committee-2',
            'past-leaders',
            'nextgen',
            'central-andhra-region',
            'rayalaseema-region',
            'visakhapatnam-region',
            'tender',
            'foreign-trade-2',
            'business-exchange-2',
            'jobs',
            'business-opportunities-2',
            'government-policies',
            'privacypolicy',
            'presidents-council',
            'ethics-committee',
            'sub-committees',
            'zones',
            'women-entrepreneurs-wing',
            'application-form',
            'all-events-gallery',
            'business-awards-2025',
            'gos-view'
        );
        
        // Get page IDs for approved pages
        $approved_ids = array();
        
        foreach ( $approved_slugs as $slug ) {
            $page = get_page_by_path( $slug );
            if ( $page ) {
                $approved_ids[] = $page->ID;
            }
        }
        
        // Also include the homepage (ID = 0 or get_option('page_on_front'))
        $homepage_id = get_option('page_on_front');
        if ( $homepage_id ) {
            $approved_ids[] = $homepage_id;
        }
        
        // Set only approved pages to be included
        if ( !empty( $approved_ids ) ) {
            $args['post__in'] = $approved_ids;
        } else {
            // If no approved pages found, exclude everything
            $args['post__in'] = array(0);
        }
    }
    
    return $args;
}

// Clear sitemap cache when pages are updated
add_action( 'save_post', 'apchambers_clear_sitemap_cache' );
function apchambers_clear_sitemap_cache( $post_id ) {
    // Clear sitemap cache
    if ( function_exists( 'wp_sitemaps_get_server' ) ) {
        wp_sitemaps_get_server()->renderer->cache->clear();
    }
}

// Ensure proper XML output for sitemaps
add_action( 'init', 'apchambers_fix_sitemap_output' );
function apchambers_fix_sitemap_output() {
    // Only run on sitemap requests
    if ( ! is_admin() && isset( $_GET['sitemap'] ) ) {
        // Remove any output buffering that might interfere
        if ( ob_get_level() ) {
            ob_clean();
        }
        
        // Ensure proper headers
        if ( ! headers_sent() ) {
            header( 'Content-Type: application/xml; charset=utf-8' );
        }
    }
}

