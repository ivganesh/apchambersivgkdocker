<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="profile" href="<?php echo esc_url( __( 'http://gmpg.org/xfn/11', 'vw-travel' ) ); ?>">

<!-- AP Chambers Meta Tags -->
<meta name="description" content="Andhra Pradesh Chambers of Commerce and Industry Federation (AP Chambers) - The largest industry federation in Andhra Pradesh with 1,400+ corporate members and 78 affiliated associations.">
<meta name="keywords" content="AP Chambers, Andhra Pradesh, Commerce, Industry, Federation, Business, Trade, Chamber of Commerce">
<meta name="author" content="AP Chambers">
<meta name="robots" content="index, follow">

<!-- Open Graph Meta Tags for Social Media -->
<meta property="og:title" content="AP Chambers - Andhra Pradesh Chambers of Commerce and Industry Federation">
<meta property="og:description" content="Andhra Pradesh Chambers of Commerce and Industry Federation (AP Chambers) - The largest industry federation in Andhra Pradesh with 1,400+ corporate members and 78 affiliated associations.">
<meta property="og:image" content="https://apchambers.in/wp-content/uploads/2025/06/apchamberslogopng.png">
<meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="AP Chambers">
<meta property="og:locale" content="en_US">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="AP Chambers - Andhra Pradesh Chambers of Commerce and Industry Federation">
<meta name="twitter:description" content="Andhra Pradesh Chambers of Commerce and Industry Federation (AP Chambers) - The largest industry federation in Andhra Pradesh with 1,400+ corporate members and 78 affiliated associations.">
<meta name="twitter:image" content="https://apchambers.in/wp-content/uploads/2025/06/apchamberslogopng.png">
<meta name="twitter:site" content="@apchambers">

<!-- Additional Meta Tags -->
<meta name="theme-color" content="#dc3545">
<meta name="msapplication-TileColor" content="#dc3545">

<!-- Force Search Engine Re-indexing -->
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<link rel="canonical" href="<?php echo esc_url(home_url('/')); ?>">

<!-- Cache Busting for Social Media -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<?php wp_head(); ?>
</head>
<?PHP if( !session_id() ) session_start();
		$_SESSION['toggleSidebar'] = isset( $_SESSION['toggleSidebar'] ) ? $_SESSION['toggleSidebar'] : 'non-collapse';
		$bodyClass = $_SESSION['toggleSidebar'] == 'collapse' ? ' sidebar-collapse' : '';
		global $wpAjaxpath;
	if(is_user_logged_in())$bodyClass = "sidebar-mini layout-navbar-fixed layout-fixed {$bodyClass}";
	else $bodyClass = '';
?>
<body  style="height: auto;">
<script> var thisAjax = '<?=$wpAjaxpath;?>';</script>

<div class="wrapper">