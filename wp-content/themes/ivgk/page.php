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
			
			$menuCheck[$m->object_id] = $m->roles;
		} 
	}
	$topsubmenu = array();
	foreach ($top_menu as $m) 
	{
		if (!empty($m->menu_item_parent))
		{
				$menuCheck[$m->object_id] = $m->roles;
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
	global $sideMenu;
  	$array_menu = wp_get_nav_menu_items('menu');
  	$sideMenu = array();
	foreach ($array_menu as $m) 
	{
		//print_r($m);
		if (empty($m->menu_item_parent))
		{
			if($m->shown == 'shown' )
			{
				$menuAction[$m->object_id] = $m->actions;
				$sideMenu[$m->ID] = array();
				$sideMenu[$m->ID]['icon'] = $m->icon;
				$sideMenu[$m->ID]['title'] = $m->title;
				$sideMenu[$m->ID]['url'] = $m->url;
				$sideMenu[$m->ID]['active']= $m->object_id;
				$sideMenu[$m->ID]['children']= array();
			}
			$menuCheck[$m->object_id] = $m->roles;
		} 
	}
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

				if( isset ( $sideMenu[$m->menu_item_parent] ) )
				{
					$sideMenu[$m->menu_item_parent]['active']=$m->object_id ;
					$sideMenu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
				}
			}
		}
	}
	$mainHeader = '';
	
	
?>

<nav id="navbar_top" class="<?=$mainHeader ; ?> text-center navbar navbar-expand-lg navbar-white navbar-light">

		
      <a href="https://apchambers.in">
        <img src="https://apchambers.in/wp-content/uploads/2022/09/1024by500.png" alt="" style="max-width:120px" class="logo">
      </a>
	  <button style="font-size:36px" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    	<span class="navbar-toggler-icon"></span>
  	  </button>
	  <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">

<?php 
	//$menuList = wp_get_nav_menu_items('top menu');

foreach( $topMenu as $key=> $value)
{ 
		if( count( $value['children'] ) > 0 ){ 
			?>
			<li class="nav-item my-2 dropdown">
        		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          			<?=$value['title'];?>
        		</a>
        		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<?
			foreach(  $value['children'] as $k => $v) {
			?>
          			<a class="dropdown-item my-2" href="<?=$v['url']; ?>"><?=$v['title']; ?></a>
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
        	<a class="nav-link my-2" href="<?=$value['url'];?>"> <?=$value['title'];?></a>
      	</li>
		<?
	}
		
		
		
		

} ?>
	  </ul>
      </div>
	
</nav>

<div class=""><section class="content">
	
			




<div class="row mx-3">
<div class="col-md-12">
<?php //while ( have_posts() ) : the_post();
?>
<div id="content-vw">
<?php 
$membershipCheck = true;	
	if(is_user_logged_in()  )
	{
		if ( isset ( $menuCheck[$pageId] ) )
				if($menuCheck[$pageId] == 'in' )
					$membershipCheck = renew_membership();
	
	}
	//if( $membershipCheck  )
	//{
  		if(get_the_title() != 'Home')
        {
          echo '<div class="mt-2 border-bottom"><h3> <b>';
    
 			 echo get_the_title();
  		echo '</b> </h3></div>';
        }
  		
 		
		the_content();
	//}
		
//if ( comments_open() || get_comments_number() ) :
//comments_template();
//endif;
?>
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