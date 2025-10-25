<nav class="main-header navbar navbar-expand navbar-white navbar-light">
<ul class="navbar-nav" id="topmenu">
<li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" tabindex="-1" role="button"><i class="fas fa-bars"></i></a></li>
<?php $menuList = wp_get_nav_menu_items('top menu');
foreach( $menuList as $key=> $value)
{ ?>
<li class="nav-item d-none d-sm-inline-block">
<a href="<?=$value->url;?>" class="nav-link" tabindex="-1"><?=$value->title;?></a>
</li>
<?php } ?>
</ul>
</nav>