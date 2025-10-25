<?php
get_header(); 
global $wp;
if ( $wp->request == 'printinvoice')
    get_template_part( 'print-content'); 
else 
{
	$userId = wp_get_current_user();
	global $menuCheck;
	global $menuAction;
	global $pageId;
	$pageId = get_the_ID();
	
	$top_menu = wp_get_nav_menu_items('top menu');
	$topMenu = array();
	foreach ($top_menu as $m) 
	{
		//print_r($m);
		if (empty($m->menu_item_parent))
		{
			
				$topMenu[$m->ID] = array();
				$topMenu[$m->ID]['icon'] = $m->icon;
				$topMenu[$m->ID]['title'] = $m->title;
				$topMenu[$m->ID]['url'] = $m->url;
				$topMenu[$m->ID]['children']= array();
			
			//$menuCheck[$m->object_id] = $m->roles;
		} 
	}
	$topsubmenu = array();
	foreach ($top_menu as $m) 
	{
		if (!empty($m->menu_item_parent))
		{
			
				$topsubmenu[$m->ID] = array();
				$topsubmenu[$m->ID]['title']= $m->title;
				$topsubmenu[$m->ID]['url']= $m->url;

				if( isset ( $topMenu[$m->menu_item_parent] ) )
				{
					$topMenu[$m->menu_item_parent]['active']=$m->object_id ;
					$topMenu[$m->menu_item_parent]['children'][$m->ID] = $topsubmenu[$m->ID];
				}
			
		}
	}
	//echo '<pre>';
	//print_r($topMenu); exit;
	$array_menu = wp_get_nav_menu_items('menu');
	$menu = array();
	foreach ($array_menu as $m) 
	{
		//print_r($m);
		if (empty($m->menu_item_parent))
		{
			if($m->shown == 'shown' )
			{
				$menu[$m->ID] = array();
				$menu[$m->ID]['icon'] = $m->icon;
				$menu[$m->ID]['title'] = $m->title;
				$menu[$m->ID]['url'] = $m->url;
				$menu[$m->ID]['active']= $m->object_id;
				$menu[$m->ID]['children']= array();
			}
			$menuCheck[$m->object_id] = $m->roles;
		} 
	}
	$submenu = array();
	foreach ($array_menu as $m) 
	{
		if (!empty($m->menu_item_parent))
		{
			$menuAction[$m->object_id] = $m->actions;
			$menuCheck[$m->object_id] = $m->roles;
			if($m->shown == 'shown' )
			{
				$submenu[$m->ID] = array();
				$submenu[$m->ID]['title']= $m->title;
				$submenu[$m->ID]['url']= $m->url;
				$submenu[$m->ID]['active']= $m->object_id ;

				if( isset ( $menu[$m->menu_item_parent] ) )
				{
					$menu[$m->menu_item_parent]['active']=$m->object_id ;
					$menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
				}
			}
		}
	}
	$mainHeader = '';
	if(is_user_logged_in())
	{
		$mainHeader = 'main-header';
		
	}
	
?>
<nav id="navbar_top" class="<?=$mainHeader ; ?> navbar navbar-expand-lg navbar-white navbar-light">
	<?
if(!is_user_logged_in())
	{
		echo '<div class="container">';
		
	}
	?>
		
						<a href="https://apchambers.in/Index.html">
							<img src="https://apchambers.in/app/wp-content/uploads/2022/04/apccif.jpg" style="width:80%;" alt="" class="img-fluid logo">
					    </a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">

<?php 
	//$menuList = wp_get_nav_menu_items('top menu');
foreach( $topMenu as $key=> $value)
{ 
		if( count( $value['children'] ) > 0 ){ 
			?>
				<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=$value['title'];?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?
			foreach(  $value['children'] as $k => $v) {
			?>
          <a class="dropdown-item" href="<?=$v['url']; ?>"><?=$v['title']; ?></a>
			  <?
			}
			?>
          
        </div>
      </li>
				<?
	}
	else
	{ ?>
		<li class="nav-item">
        <a class="nav-link" href="<?=$value['url'];?>"> <?=$value['title'];?></a>
      </li>
		<?
	}
		
		
		
		

} ?>
</ul>
	</div>
	<?
	if(!is_user_logged_in()){
		echo '</div">';
	}
	?>
</nav>
<?
	
	if(is_user_logged_in())
	{
	?>
<aside class="main-sidebar sidebar-dark-primary">
	<a class="brand-link">
		<img src="<?php echo get_site_icon_url(); ?>" class="brand-image img-circle elevation-3" style="opacity: .8" />
		<span class="brand-text font-weight-light">
			<div class="row">
				<div class="col-12"><?php echo get_option( 'blogname' ); ?></div>
				<?php if($userId->user_login) { ?><div class="col-12">Welcome, <?php echo $userId->user_login; ?></div> <?php } ?>
			</div>
		</span>
	</a>
	<div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition">
		<div class="os-resize-observer-host observed">
			<div class="os-resize-observer" style="left: 0px; right: auto;">
			</div>
		</div>
		<div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
			<div class="os-resize-observer">
			</div>
		</div>
		<div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 291px;">
		</div>
		<div class="os-padding">
			<div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
				<div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
<nav class="mt-4">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
<?php

foreach ( $menu as $key => $value )
{

$isActive = false;
$activeClass = $isActive ? " active" : ""; 
$openClass = $isActive ? " menu-open" : "";	?>
<li class="nav-item <?=$openClass; ?>"><a tabindex="-1" href="<?=$value['url']; ?>" class="nav-link <?=$activeClass; ?>"><?php if($value['icon'] != '') ?><i class="nav-icon mr-2 fas <?=$value['icon'];?>"></i> 
<p><?=$value['title'];
if( count( $value['children'] ) > 0 ){ ?> <i class="right fas fa-angle-left"></i> <?php } ?>
</p></a>
<?php
if( count( $value['children'] ) > 0 )
{
?>
<ul class="nav nav-treeview" style="display: none;">
<?php
foreach( $value['children'] as $k => $v)
{
$activeClassI = $pageId == $v['active']? " active" : ""; 
?>
<li class="nav-item"><a tabindex="-1" href="<?=$v['url']; ?>" class="nav-link <?=$activeClassI; ?>"><p><?=$v['title']; ?></p></a></li>
<?php
}
?>
</ul>
<?php
}
?>
</li>
<?php
}
?>
</ul>
</nav>
</div>
</div>
</div>
</div>
</aside>
<?
	}
if(is_user_logged_in()) 
{
	echo '<div class="content-wrapper"><section class="content">';
}
else echo '<div class="container">';
		?>
			




<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<?php while ( have_posts() ) : the_post();?>
<div id="content-vw">
<?php 
the_content();
if ( comments_open() || get_comments_number() ) :
comments_template();
endif;
?>
</div>
<?PHP endwhile; ?>
</div>
</div>
</div>

<?php
	if(is_user_logged_in()) 
{
	echo '</section></div>';
}
else echo '</div>';
	//get_template_part( 'page-menu'); 
	//get_template_part( 'side-menu'); 
	
    //get_template_part( 'page-content'); 
    get_footer(); 
}

?>