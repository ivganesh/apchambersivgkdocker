<?php 
if(is_user_logged_in()) { $mainFooter = 'main-footer'; } else { $mainFooter = ''; } ; 
$siteUrl = get_site_url();
$footer_menu = wp_get_nav_menu_items('footer menu');
$fmenu = array();

foreach ($footer_menu as $m) 
{
	if (empty($m->menu_item_parent))
	{

		$fmenu[$m->ID] = array();
		$fmenu[$m->ID]['title'] = $m->title;
		$fmenu[$m->ID]['children']= array();

	} 
}


$fsubmenu = array();
foreach ($footer_menu as $m) 
{
	if (!empty($m->menu_item_parent))
	{
		
			$fsubmenu[$m->ID] = array();
			$fsubmenu[$m->ID]['title']= $m->title;
			$fsubmenu[$m->ID]['url']= $m->url;

			if( isset ( $fmenu[$m->menu_item_parent] ) )
			{
				$fmenu[$m->menu_item_parent]['children'][$m->ID] = $fsubmenu[$m->ID];

			}
		
	}
}
global $sideMenu;



// echo  do_shortcode('[wise-chat theme="colddark"]');
?>

<?
	
	if(is_user_logged_in())
	{
	
	 echo '<footer style="background-color:#4c4a4a" class="row border-bottom border-dark p-2 text-white">';
      foreach ( $sideMenu as $key => $value )
      {
          echo '<div class="col-sm-4 col-md-4 col-lg-2" style="padding : 20px">
                  <h4 class="font-weight-bold">';
        			if( count( $value['children'] ) == 0 ) echo '<a href="'.$value['url'] .'" style="font-family:inherit;font-size:inherit" class="font-weight-bold text-white">'.$value['title'].'</a>';
        			else echo $value['title'];
                      echo '</h4>
                  <ul class="nav flex-column">';
          if( count( $value['children'] ) > 0 )
          {
              foreach( $value['children'] as $k => $v)
              {
                  echo '<li class="nav-item mb-2"><a href="'.$v['url'] .'" class="nav-link p-0 text-white">'.$v['title'].'</a></li>';
              }
          }
          echo '</ul></div>';

      }
      echo '</footer>';

	}
?>
<footer style="background-color:#4c4a4a" class="row p-2  text-white">

		
      <?
		
foreach ( $fmenu as $key => $value )
{
	//3909   3910
	echo '<div class="col-sm-4 col-md-4 col-lg-2" style="padding : 20px">
        	<h4 class="font-weight-bold">'.$value['title'].'</h4>
        	<ul class="nav flex-column">';
	if( count( $value['children'] ) > 0 )
	{
		foreach( $value['children'] as $k => $v)
		{
			echo '<li class="nav-item mb-2"><a href="'.$v['url'] .'" class="nav-link p-0 text-white">'.$v['title'].'</a></li>';
		}
	}
	echo '</ul></div>';
	
}
echo '<div class="col-sm-4 col-md-4 col-lg-2" style="padding : 20px">
        	<ul class="nav flex-column">';
if(is_user_logged_in())
{
   echo '<li class="nav-item my-2"><button type="button" class="btn btn-outline-primary"><a href="'.site_url() .'/logout/" class="nav-link p-0 text-white"><h6 style="color:yellow;">Logout</h6></a></button></li>';
}else
  {
  echo '<li class="nav-item my-2"><button type="button" class="btn btn-outline-primary"><a href="'.site_url() .'/login-2/" class="nav-link p-0 text-white"><h6 style="color:yellow;">Login</h6></a></button></li>';
	echo '<li class="nav-item my-2"><button type="button" class="btn btn-outline-primary"><a href="'.site_url() .'/mobile-login/" class="nav-link p-0 text-white"><h6 style="color:yellow;">Mobile OTP Login</h6></a></button></li>';
	echo '<li class="nav-item"><button type="button" class="btn btn-outline-primary"><a href="'.site_url() .'/user-registration/" class="nav-link p-0 text-white"><h6 style="color:yellow;">User Registration</h6></a></button></li>';
}
	
	echo '</ul></div>';
		?>
  <div class="col-sm-6 mt-2"><a href="https://apchambers.in/Index.html">
							<img src="https://apchambers.in/wp-content/uploads/2022/09/1024by500.png" style="max-width:150px" alt="" class="img-fluid logo">
					    </a></div>
<div class="col-sm-6 mt-2 text-right" style="padding : 5px">
   
										<div class="social__media mt-4">
											<ul class="list-inline">
												<li class="list-inline-item">
													<a href="https://www.facebook.com/apchambers.in" target="_blank" class="btn btn-social rounded text-white facebook">
													<i class="fab fa-facebook"></i>
													</a>
												</li>
												<li class="list-inline-item">
													<a href="https://twitter.com/APCHAMBERSAPEX" target="_blank" class="btn btn-social rounded text-white twitter">
													<i class="fab fa-twitter"></i>
													</a>
												</li>
												<li class="list-inline-item">
													<a href="https://wa.me/+919912092222/?text=Hi!%20I%20need%20more%20information" target="_blank" class="btn btn-social rounded text-white whatsapp">
													<i class="fab fa-whatsapp"></i>
													</a>
												</li>
												<li class="list-inline-item">
													<a href="https://www.linkedin.com/company/andhra-pradesh-chambers-of-commerce-and-industry-federation/?viewAsMember=true" target="_blank" class="btn btn-social rounded text-white linkedin">
													<i class="fab fa-linkedin"></i>
													</a>
												</li>
												<li class="list-inline-item">
													<a href="https://www.youtube.com/channel/UCHnoZO0D0x7Ao1YRpxQcaaw" target="_blank" class="btn btn-social rounded text-white youtube">
													<i class="fab fa-youtube"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
   

    <div class="col-12 p-2 mt-2 border-top">
      <p>© 2021 APCHAMBERS, Inc. All rights reserved. | <a href="https://apchambers.in/privacypolicy/" target="_blank" style="color:inherit;text-decoration:underline;">Privacy Policy</a></p>
  </div>
</footer>
<?php wp_footer(); 
$siteUrl = get_site_url();
//echo do_shortcode('[wise-chat  show_users_counter="1" channel="Ap Chamber"]');
?>
<script>
   $.widget.bridge('uibutton', $.ui.button);
  document.addEventListener("DOMContentLoaded", function(){
		
		window.addEventListener('scroll', function() {
	       
			if (window.scrollY > 50){
				document.getElementById('navbar_top').classList.add('fixed-top');
				navbar_height = document.querySelector('.navbar').offsetHeight;
				document.body.style.paddingTop = navbar_height + 'px';
			} else {
			 	document.getElementById('navbar_top').classList.remove('fixed-top');
				document.body.style.paddingTop = '0';
			} 
		});
	}); 
setTimeout(function(){
/*    window._oneSignalInitOptions.promptOptions = {
      slidedown: {
        prompts: [
          {
            type: "push",
            autoPrompt: true,
            text: {
              actionMessage: "Your Custom Action Message",
              acceptButton: "Yes",
              cancelButton: "No",
            },
            delay: {
              timeDelay: 1,
              pageViews: 1,
            }
          }
        ]
      }
    };*/
    window.OneSignal = window.OneSignal || [];
    window.OneSignal.push(function() {
      window.OneSignal.init(window._oneSignalInitOptions);
    });
}, 3000);
</script>

</body>
</html>