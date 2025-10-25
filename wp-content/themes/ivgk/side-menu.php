<?php $userId = wp_get_current_user(); ?>
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
global $menuCheck;
global $pageId;
$pageId = get_the_ID();
$array_menu = wp_get_nav_menu_items('menu');
$menu = array();
foreach ($array_menu as $m) 
{
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
foreach ( $menu as $key => $value )
{

$isActive = false;
$activeClass = $isActive ? " active" : ""; 
$openClass = $isActive ? " menu-open" : "";	?>
<li class="nav-item bg-warning <?=$openClass; ?>"><a tabindex="-1" href="<?=$value['url']; ?>" class="nav-link <?=$activeClass; ?>"><?php if($value['icon'] != '') ?><i class="nav-icon mr-2 fas <?=$value['icon'];?>"></i> 
<p><?=$value['title'];?>
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
