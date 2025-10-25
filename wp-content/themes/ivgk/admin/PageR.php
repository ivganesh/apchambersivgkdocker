<?php 

add_shortcode("ContactPage", "ContactPage");
function ContactPage()
{
	global $wp;
	$url = home_url( $wp->request );
  	global $errorMsg;
  	
    if( ! session_id() ) session_start();
  
    
    
  		
  
	if($_POST)
	{
      if( $_POST['fromInquiry'] == 'submit')
        {
        
          if( isset($_SESSION['answer']) )
          {

            if( $_SESSION['answer'] == $_POST['answer'])
            {
				$message= '';
                if(function_exists('stripslashes')) {
                    $message = stripslashes(trim($_POST['message']));
                   $subject = stripslashes(trim($_POST['subject']));
                } else {
                    $message = trim($_POST['message']);
                    $subject = trim($_POST['subject']);
                }
                $emailTo = get_option('admin_email');
                $subject = 'Inquiry From '.$_POST['your_name'];
                $body = "Name: {$_POST['your_name']} \n\n Subject: {$subject} \n\n Phone: {$_POST['mobile']} \n\n Email: {$_POST['email']} \n\n Message: {$message}";
                $headers = 'From: '.$_POST['your_name'].' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $_POST['email'];

                wp_mail($emailTo, $subject, $body, $headers);
                 $errorMsg[] = array("Inquiry submitted succesfully",true);
            }
            else
              {
              $errorMsg[] = array("Incorrect Answer...",false);
            }
          }else
              {
              $errorMsg[] = array("Incorrect Answer...",false);
            }
      
      	
      }
	}
  if (isset($errorMsg)) {
    		echo '<div class="mt-3">';
            $returnSubmitValue = alert_display($errorMsg);
    		echo '</div>';
        }
  $digit1 = mt_rand(1, 10);
  $digit2 = mt_rand(1, 10);

  $math = "$digit1 + $digit2 = ?";
  $_SESSION['answer'] = $digit1 + $digit2;
  
	echo '<section class="wrap__contact-form mt-4">
        <div class="container">
            <div class="row">';
		
              echo '<div class="col-md-8">
                    <h3><b>Contact us</b></h3>
					<form name="contactForm" enctype="multipart/form-data"  method="post" action="'.$url.'">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group form-group-name">
									<label>Your name <span class="required"></span></label>
									<input type="text" class="form-control" name="your_name" required="">

								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group form-group-name">
									<label>Subject <span class="required"></span></label>
									<input type="text" class="form-control" name="subject" required="" >

								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group form-group-name">
									<label>Your email <span class="required"></span></label>
									<input type="email" class="form-control" name="email" required="" >

								</div>
							</div>

							<div class="col-md-6">
								<div class="form-group form-group-name">
									<label>Your mobile number <span class="required"></span></label>
									<input type="text" class="form-control" name="mobile" required="" >

								</div>
							</div>  
                            
                            
							<div class="col-md-12">
								<div class="form-group">
									<label>Your message </label>
									<textarea class="form-control" rows="15" name="message"></textarea>
								</div>
								
							</div>
                            
                            <div class="col-12">
								<div class="form-group form-group-nam">
									<label for="user_password">'.$math.'<font color="red">*</font></label>
									<input required type="number" min="-20" max="20" step="1" maxlength="3" name="answer" placeholder="Enter Your Answer" id="answer" class="form-control" value="" >
								</div>
							</div>
                            
                            <div class="col-12">
								<div class="form-group float-right mt-4">
									<input type="submit" class="btn btn-primary" value="submit" name="fromInquiry">
								</div>
							</div>
                            
                            
						</div>
					</form>
                </div>
                <div class="col-md-4">
                <h3><b>Secretariat</b></h3>
                
                
                    <div class="wrap__contact-form-office">
                        <ul class="list-unstyled">
                            <li><span></span>Andhra Pradesh Chambers of Commerce and Industry Federation</li>
							<li><span><i class="fa fa-home"></i></span>#40-1-144, 3rd Floor, Corporate Centre,Beside Chandana Grand, M.G. Road, Vijayawada, Andhra Pradesh 520 010 </li>
                            <li><span><i class="fa fa-phone"></i></span><a href="tel:08662482888">+91 866-2482888 </a></span></li>
							<li><span><i class="fa fa-phone"></i></span><a href="tel:9912092222"> +91 99120 92222</a></span></li>
							<li><span><i class="fa fa-phone"></i></span><a href="tel:9121221473">+91 91212 21473</a></span></li>
							<li><span><i class="fa fa-phone"></i></span><a href="tel:9121221474">+91 91212 21474</a></span></li>
                            <li><span><i class="fa fa-envelope"></i></span><a href="mailto:federation@apchamber.in"> federation@apchamber.in</a></li>
                        </ul>
                        
                        
               
               
               
               
               
               <h3><b>Vizag Regional Office</b></h3>
                
                
                    <div class="wrap__contact-form-office">
                        <ul class="list-unstyled">
                            <li><span><i class="fa fa-home"></i></span>#14-1-15/4, Sri Dasapalla Towers, 1st Floor, 1/5, Opp: Central Revenue Quarters, Nowroji Road, Maharanipeta , VISAKHAPATNAM-530002, Andhra Pradesh, India. </li>
                            <li><span><i class="fa fa-phone"></i></span><a href="tel:9121221476">+91-91212 21476</a></span></li>
							<li><span><i class="fa fa-phone"></i></span><a href="tel:9121221477">+91-91212 21477</a></span></li>
                            <li><span><i class="fa fa-envelope"></i></span><a href="mailto:federationvisakha@apchamber.in"> federationvisakha@apchamber.in</a></li>
                        </ul>
               
               
               
               
               
                        
                        
                        
                        
                        
                        
                        
                        

                        <div class="social__media">
                            <h5>find us</h5>
							
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
                </div>
            </div>
        </div>
    </section>';
	echo '<script>
    var _rulesString = {};
	var _messagesString = {};
	
</script>';
}


add_shortcode("HomePage", "HomePage");
function HomePage()
{
	$site_url = get_site_url();
	global $wpdb;
	
	$carouselData = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}carousel WHERE isTrash = 0 ORDER BY View_Order", "ARRAY_A");
	$magazineData = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}magazine WHERE isTrash = 0 ORDER BY View_Order LIMIT 2", "ARRAY_A");
	$gosData = $wpdb->get_results("SELECT GO_Description, PDF_1, DATE_FORMAT(Gos_Date,'%d %M, %Y') as gosDate FROM {$wpdb->prefix}gos WHERE isTrash = 0 ORDER BY Gos_Date LIMIT 10", "ARRAY_A");
	$curDate = date('Y-m-d');
	$upcomingEvents = $wpdb->get_results("SELECT ID, Event_Name, TIME_FORMAT(Start_Time,'%h:%i %p') as startTime, DATE_FORMAT(Start_Date, '%W %d,%b-%Y') as startDate FROM {$wpdb->prefix}chamber_events WHERE isTrash = 0 AND Start_Date >= '{$curDate}' ORDER BY Start_Date DESC", "ARRAY_A");
?>
<div class="row mt-3">
	<div class="col-sm-12">
		<div class="slider-container">
			<div class="container-slider-image-full nopadd">
				<div id="sliderindi" class="carousel slide carousel-fade" data-ride="carousel">
					<ol class="carousel-indicators">
						<?php
							foreach ($carouselData as $index => $data) {
								$class = $index === 0 ? 'active' : '';
								echo '<li data-target="#sliderindi" data-slide-to="' . $index . '" class="' . $class . '"></li>';
							}
						?>
					</ol>
					<div class="carousel-inner">
						<?php
							foreach ($carouselData as $index => $data) {
								$class = $index === 0 ? 'active' : '';
								echo '<div class="carousel-item ' . $class . '">
									<div class="row">
										<div class="col-12 col-sm-0">
											<div class="carousel-caption text-capitalize">
												<p class="text-white animated zoomInLeft" style="animation-delay:2s">' . $data['Description'] . '</p>
												<div class="animated fadeInLeft d-none d-sm-block text-left" style="animation-delay:2.6s"></div>
											</div>
										</div>
										<div class="col-12 col-sm-12">
											<img class="d-block w-100" src="' . $data['userProfile_1'] . '" alt="Carousel slide">
										</div>
									</div>
								</div>';
							}
						?>
					</div>
					<!-- Navigation arrows -->
					<a class="carousel-control-prev" href="#sliderindi" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#sliderindi" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	/* Custom carousel navigation styling */
	#sliderindi .carousel-indicators li {
		background-color: #dc3545 !important; /* Red color for inactive dots */
		opacity: 0.5;
	}
	#sliderindi .carousel-indicators li.active {
		background-color: #dc3545 !important; /* Red color for active dot */
		opacity: 1;
	}
	#sliderindi .carousel-control-prev-icon,
	#sliderindi .carousel-control-next-icon {
		background-color: #dc3545 !important; /* Red color for arrows */
		border-radius: 50%;
		width: 40px;
		height: 40px;
		background-size: 60% 60%;
	}
	#sliderindi .carousel-control-prev,
	#sliderindi .carousel-control-next {
		width: 5%;
		opacity: 0.8;
	}
	#sliderindi .carousel-control-prev:hover,
	#sliderindi .carousel-control-next:hover {
		opacity: 1;
	}
	.upcoming-events-container {
		overflow-x: hidden;
		padding: 20px 0;
		position: relative;
		margin: 0 auto;
		max-width: 1200px;
		background: transparent;
	}

	.upcoming-events-row {
		display: flex;
		flex-wrap: nowrap;
		gap: 20px;
		padding: 10px 0;
		will-change: transform;
		justify-content: flex-start;
		align-items: flex-start;
		margin: 0;
		/* No min-width! */
	}

	.upcoming-event-card {
		flex: 0 0 300px;
		width: 300px;
		box-sizing: border-box;
		transition: all 0.3s ease;
		cursor: pointer;
		background: white;
		border-radius: 8px;
		box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		overflow: hidden;
		display: flex;
		flex-direction: column;
	}
    .upcoming-event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .upcoming-event-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    .upcoming-event-date {
        color: #666;
        font-size: 0.9em;
        margin-top: 10px;
    }
</style>
    <div class="row mt-4 text-center mb-0">
        <div class="col-12">
            <p class="h2 w-100 text-center">Upcoming Events</p>
        </div>
    </div>
    <div class="upcoming-events-container">
        <div class="upcoming-events-row" id="marquee-row">
            <?php if (count($upcomingEvents) > 0) : ?>
                <?php foreach ($upcomingEvents as $event) : 
                    $galleryImage = '';
                    $imgQry = $wpdb->get_results("SELECT userProfile_1 FROM {$wpdb->prefix}event_gallery WHERE isTrash = 0 AND Event='{$event['ID']}' ORDER BY View_Order LIMIT 1", "ARRAY_A");
                    if (count($imgQry) > 0) {
                        $galleryImage = $imgQry[0]['userProfile_1'];
                    }
                ?>
                    <div class="card shadow upcoming-event-card" onclick="window.location.href='<?php echo $site_url; ?>/view-gallery/?event_gallery=<?php echo $event['ID']; ?>'">
                        <?php if ($galleryImage) : ?>
                            <img src="<?php echo $galleryImage; ?>" class="upcoming-event-image" alt="<?php echo $event['Event_Name']; ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><b><?php echo $event['Event_Name']; ?></b></h5>
                            <p class="upcoming-event-date">on <?php echo $event['startDate']; ?> at <?php echo $event['startTime']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
   <script>
// (function() {
//     const row = document.getElementById('marquee-row');
//     if (!row) return;

//     // Only duplicate if there are at least 2 events
//     let isLooping = false;
//     if (row.children.length > 1) {
//         row.innerHTML += row.innerHTML;
//         isLooping = true;
//     }

//     let scrollAmount = 0;
//     const speed = 1; // pixels per frame
//     let running = true;

//     // Wait for images to load to get correct width
//     function getRowWidth() {
//         return row.scrollWidth / (isLooping ? 2 : 1);
//     }

//     function setInitialPosition() {
//         scrollAmount = getRowWidth();
//         row.style.transform = `translateX(${scrollAmount}px)`;
//     }

//     function animate() {
//         if (running) {
//             scrollAmount -= speed;
//             if (Math.abs(scrollAmount) >= getRowWidth()) {
//                 scrollAmount = 0;
//             }
//             row.style.transform = `translateX(${scrollAmount}px)`;
//         }
//         requestAnimationFrame(animate);
//     }

//     setInitialPosition();
//     animate();

//     // Pause on hover
//     const container = document.querySelector('.upcoming-events-container');
//     container.addEventListener('mouseenter', function() {
//         running = false;
//     });
//     container.addEventListener('mouseleave', function() {
//         running = true;
//     });

//     // Recalculate on window resize
//     window.addEventListener('resize', setInitialPosition);
// })();
// (function() {
//     const row = document.getElementById('marquee-row');
//     if (!row) return;

//     let isLooping = false;
//     if (row.children.length > 1) {
//         row.innerHTML += row.innerHTML;
//         isLooping = true;
//     }

//     let scrollAmount = 0;
//     const speed = 1; // pixels per frame
//     let running = true;

//     function getRowWidth() {
//         return row.scrollWidth / (isLooping ? 2 : 1);
//     }

//     function setInitialPosition() {
//         scrollAmount = getRowWidth();
//         row.style.transform = `translateX(${scrollAmount}px)`;
//     }

//     function animate() {
//         if (running) {
//             scrollAmount -= speed;
//             if (isLooping) {
//                 if (Math.abs(scrollAmount) >= getRowWidth()) {
//                     scrollAmount = 0;
//                 }
//                 row.style.transform = `translateX(${scrollAmount}px)`;
//             } else {
//                 // For single event: hide when off left, reset to right
//                 if (Math.abs(scrollAmount) > getRowWidth()) {
//                     scrollAmount = getRowWidth();
//                 }
//                 row.style.transform = `translateX(${scrollAmount}px)`;
//                 // Hide when off left, show when in view
//                 if (scrollAmount < 0) {
//                     row.style.visibility = 'hidden';
//                 } else {
//                     row.style.visibility = 'visible';
//                 }
//             }
//         }
//         requestAnimationFrame(animate);
//     }

//     setInitialPosition();
//     animate();

//     // Pause on hover
//     const container = document.querySelector('.upcoming-events-container');
//     container.addEventListener('mouseenter', function() {
//         running = false;
//     });
//     container.addEventListener('mouseleave', function() {
//         running = true;
//     });

//     // Recalculate on window resize
//     window.addEventListener('resize', setInitialPosition);
// })();
(function() {
    const row = document.getElementById('marquee-row');
    if (!row) return;

    let isLooping = false;
    if (row.children.length > 3) {
        row.innerHTML += row.innerHTML;
        isLooping = true;
    }

    let scrollAmount = 0;
    const speed = 1; // pixels per frame
    let running = true;

    function getRowWidth() {
        return row.scrollWidth / (isLooping ? 2 : 1);
    }

    function getCardWidth() {
        // For single event, get the width of the card
        if (row.children.length > 0) {
            return row.children[0].offsetWidth;
        }
        return 0;
    }

    function setInitialPosition() {
        scrollAmount = getRowWidth();
        row.style.transform = `translateX(${scrollAmount}px)`;
        row.style.visibility = 'visible';
    }

    function animate() {
        if (running) {
            scrollAmount -= speed;
            if (isLooping) {
                if (Math.abs(scrollAmount) >= getRowWidth()) {
                    scrollAmount = 0;
                }
                row.style.transform = `translateX(${scrollAmount}px)`;
            } else {
                const cardWidth = getCardWidth();
                // Reset as soon as the card is fully out of view
                if (scrollAmount < -cardWidth) {
                    scrollAmount = getRowWidth();
                }
                row.style.transform = `translateX(${scrollAmount}px)`;
                // Hide when off left, show when in view
                if (scrollAmount < 0 && Math.abs(scrollAmount) > cardWidth) {
                    row.style.visibility = 'hidden';
                } else {
                    row.style.visibility = 'visible';
                }
            }
        }
        requestAnimationFrame(animate);
    }

    setInitialPosition();
    animate();

    // Pause on hover
    const container = document.querySelector('.upcoming-events-container');
    container.addEventListener('mouseenter', function() {
        running = false;
    });
    container.addEventListener('mouseleave', function() {
        running = true;
    });

    // Recalculate on window resize
    window.addEventListener('resize', setInitialPosition);
})();
</script>
<div class="row mt-4 d-flex" style="display: flex;">
    <!-- Magazines Column -->
    <div class="col-md-12 col-lg-8 d-flex flex-column" style="display: flex; flex-direction: column;">
        <div class="row flex-grow-1" style="flex: 1 1 auto;">
            <?php foreach ($magazineData as $magazine) : ?>
                <div class="col-sm-12 col-md-6">
                    <div class="card shadow" style="position:relative;">
                        <a href="<?php echo $site_url; ?>/magazine-view/?magazine=<?php echo $magazine['ID']; ?>" target="_blank">
                            <img class="img-fluid" src="<?php echo $magazine['userProfile_1']; ?>">
                        </a>
                        <div style="position:absolute;bottom:10px;left:10px;" class="card_category">
                            <?php echo $magazine['Magazine_Name']; ?>
                        </div>
                    </div>
                </div>    
            <?php endforeach; ?>
        </div>
      	<a class="btn btn-outline-info mt-auto align-self-center" href="<?php echo $site_url; ?>/magazine-view/">View All</a>
    </div>    

    <!-- GOs/Policies Column -->
    <div class="col-md-12 col-lg-4 d-flex flex-column" style="display: flex; flex-direction: column;">
        <aside class="wrapper__list__article flex-grow-1" style="flex: 1 1 auto;">
            <h4 class="border_section">GOs/Policies</h4>
            <div class="wrapper__list-number">
                <?php 
                $gosData = $wpdb->get_results("SELECT GO_Description, PDF_1, DATE_FORMAT(Gos_Date,'%d %M, %Y') as gosDate FROM {$wpdb->prefix}gos WHERE isTrash = 0 ORDER BY Gos_Date DESC LIMIT 2", "ARRAY_A");
                foreach ($gosData as $go) : 
                    $pdf = $go['PDF_1'] != '' ? ' href="' . $go['PDF_1'] . '"' : ''; 
                ?>
                    <div class="card__post__list">
                        <div class="list-number"><span>→</span></div>
                        <a <?php echo $pdf; ?> target="_blank" class="category"><?php echo $go['gosDate']; ?></a>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <h5><a <?php echo $pdf; ?> target="_blank"><?php echo $go['GO_Description']; ?></a></h5>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </aside>
        <a class="btn btn-outline-info mt-auto align-self-center" href="https://apchambers.in/gos-view/">View All</a>
    </div>
</div>
<style>
.full-width-card {
	width: 100%;
}
.carousel-item img {
        height: 500px; /* Set the desired height */
        object-fit: cover;
        width: 100%;
    }
</style>
<script>
    var _rulesString = {};
	var _messagesString = {};
	$(function () {
		$("#myCarousel").carousel();
	});
</script>
<?php
}


add_shortcode("PrintMediaPage", "PrintMediaPage");
function PrintMediaPage()
{
	global $wpdb;

	$upcomingEvents = $wpdb->get_results( "SELECT Release_Title,
												  Description,
												  userProfile_1,
											   DATE_FORMAT(Release_Date, '%W %d,%b-%Y') as releaseDate 
										FROM {$wpdb->prefix}press_release 
										WHERE isTrash = 0   ORDER BY Release_Date DESC", "ARRAY_A");
	if( count ( $upcomingEvents ) > 0 )
	{
		echo '<div class="row mb-2">';
		foreach( $upcomingEvents as $upcomingEvent )
		{
			?>
				<div class="col-sm-6 col-md-4 col-lg-4 p-2">
					<div class="card shadow">
						<div class="card-body">
							<div style="position: absolute;top: 20px;" class="btn btn-primary card-article text-center">
								<?=$upcomingEvent['releaseDate']; ?>
							</div>
							<div class="image-box">
                                    <img class="img-fluid zoom-img" src="<?=$upcomingEvent['userProfile_1'] ;?>" />
                             </div> 
							<h5 class="card-title text-center mt-2"><b><?=$upcomingEvent['Release_Title'] ;?></b></h5>
							<p class="card-text"><?=$upcomingEvent['Description'] ;?></p>
						  </div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}

// business excellence awards page this

add_shortcode("BusinessExcellenceAwardsPage", "BusinessExcellenceAwardsPage");
function BusinessExcellenceAwardsPage() {
    ob_start();
    ?>
    <style>
    .bea-bg {
        background: #14233a;
        color: #fff;
        min-height: 100vh;
        padding: 0;
        font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    }
    .bea-gold-border {
        border: 4px solid #c9a13b;
        border-radius: 18px;
        padding: 32px 18px 18px 18px;
        margin: 32px auto 0 auto;
        background: #14233a;
        max-width: 900px;
        box-shadow: 0 0 24px 0 rgba(201,161,59,0.15);
    }
    .bea-header-logo {
        width: 120px;
        margin: 0 auto 16px auto;
        display: block;
    }
    .bea-trophy {
        width: 220px;
        margin: 0 auto 16px auto;
        display: block;
    }
    .bea-title {
        color: #c9a13b;
        font-size: 2.2rem;
        font-weight: bold;
        text-align: center;
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
    }
    .bea-subtitle {
        color: #fff;
        font-size: 1.2rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .bea-section-title {
        color: #c9a13b;
        font-size: 1.5rem;
        font-weight: bold;
        margin: 2rem 0 1rem 0;
        text-align: center;
    }
    .bea-gold-line {
        border-top: 2px solid #c9a13b;
        margin: 1.5rem 0 1.5rem 0;
    }
    .bea-content {
        color: #fff;
        font-size: 1.08rem;
        margin-bottom: 1.5rem;
        text-align: justify;
    }
    .bea-award-list {
        background: #14233a;
        border: 2px solid #c9a13b;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        margin: 2rem 0;
        color: #fff;
    }
    .bea-award-list li {
        border-bottom: 1px solid #c9a13b;
        padding: 0.5rem 0;
        margin: 0 0 0.2rem 0;
        font-size: 1.08rem;
        list-style: decimal inside;
    }
    .bea-award-list li:last-child {
        border-bottom: none;
    }
    .bea-footer {
        text-align: center;
        color: #fff;
        font-size: 1rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .bea-footer-logo {
        width: 90px;
        margin: 0 auto 0.5rem auto;
        display: block;
    }
    .bea-gold-text {
        color: #c9a13b;
        font-weight: bold;
    }
    .bea-nomination-link {
        color: #fff;
        font-size: 1.2rem;
        font-weight: bold;
        text-align: center;
        margin: 2rem 0 1rem 0;
        display: block;
        text-decoration: underline;
    }
	.bea-sponsor-section {
			display: flex;
			flex-direction: column;
			align-items: center;
			margin-bottom: 1.5rem;
			width: 100%;
		}
		.bea-sponsor-image-wrapper {
			width: 100%;
			max-width: 600px;
			display: flex;
			justify-content: center;
		}
		.bea-sponsor-image {
			max-width: 100%;
			height: auto;
			display: block;
			border-radius: 10px;
			box-shadow: 0 2px 12px rgba(0,0,0,0.08);
		}
		@media (max-width: 600px) {
			.bea-sponsor-image-wrapper {
				max-width: 98vw;
			}
		}
    @media (max-width: 600px) {
        .bea-gold-border {
            padding: 12px 2px 2px 2px;
        }
        .bea-award-list {
            padding: 1rem 0.2rem;
        }
        .bea-sponsor-logo {
            width: 60px;
            height: 40px;
        }
		
    }
    </style>
    <div class="bea-bg">
		<div class="bea-nomination-link">
                Please Click <a href="https://docs.google.com/forms/d/e/1FAIpQLSf4TjzSEEr31oh9MU4Lf6-TUMEIxFNwr9YEgcJPeARsDArMKQ/viewform" target="_blank" class="btn btn-warning btn-lg fw-bold px-4 py-2 mb-2">Nominate Now</a> to submit your nomination
            </div>
			<div class="bea-nomination-link">
                Last date for submission: 07th August 2025
            </div>
        <div class="bea-gold-border">
            <!-- Header Logo Placeholder -->
            <img src="https://apchambers.in/wp-content/uploads/2025/06/apchamberslogopng.png" alt="AP Chambers Logo" class="bea-header-logo" />
            <div class="bea-title">CELEBRATING BUSINESS BRILLIANCE</div>
            <!-- Trophy Placeholder -->
            <img src="https://apchambers.in/wp-content/uploads/2025/06/newimageabctwo.png" alt="Trophy" class="bea-trophy" />
            <div class="bea-title" style="font-size:1.5rem; margin-top:0.5rem;">AP CHAMBERS BUSINESS EXCELLENCE AWARDS 2025</div>
            <div class="bea-subtitle">Recognise. Reward. Revitalise</div>
            <div class="bea-content">
                Andhra Pradesh Chambers of Commerce and Industry Federation (AP Chambers) is a proactive, industry-led, non-governmental, and not-for-profit organisation committed to advancing the interests of trade, commerce, and industry in Andhra Pradesh. Through continuous policy advocacy and constructive engagement with both State and Central governments, AP Chambers plays a vital role in shaping economic reforms, promoting entrepreneurship, and supporting sustainable growth across sectors. It is the largest industry federation in the State with a diverse membership of approximately 1,400 corporate members, 78 affiliated state and district-level associations, and overall reach of around 40,000 members.
            </div>
            <div class="bea-section-title">AP Chambers presents Business Excellence Awards</div>
            <div class="bea-content">
                AP Chambers has now instituted the prestigious <span class="bea-gold-text">‘AP Chambers Business Excellence Awards’</span> to honour outstanding enterprises and entrepreneurs for their significant contribution to the economic and social growth of Andhra Pradesh. These awards celebrate innovation, resilience, and excellence across key sectors that drive our State's economic growth.<br><br>
                The annual awards will honour outstanding contributions in diverse sectors such as MSME, Start-ups, Food Processing, Textiles, Automobiles, Tourism & Hospitality, Women Entrepreneurship, Exports, etc. The awards aim to celebrate success stories while motivating others to pursue excellence. They will be exclusively conferred upon companies located or registered in Andhra Pradesh.<br><br>
                By recognising exemplary performance, the award aims to create role models, promote a culture of competitiveness, and inspire the next generation of businesses and leaders. This initiative also aligns with State Government's and AP Chambers' vision of strengthening industry-government partnership for inclusive and sustainable growth.
            </div>
            <div class="bea-section-title">Business Excellence Award Categories for 2025</div>
            <div class="bea-award-list">
                <ol>
                    <li>Best MSME Company of the Year (Micro & Small)</li>
                    <li>Best MSME Company of the Year (Medium)</li>
                    <li>Best Company of the Year in Large Category</li>
                    <li>Best Start-up of the Year</li>
                    <li>Best Company of the Year in Exports (MSME)</li>
                    <li>Best Company of the Year in Food Processing (Including Aqua)</li>
                    <li>Best Company of the Year in Tourism & Hospitality</li>
                    <li>Best Company of the Year in Textiles</li>
                    <li>Best Company of the Year in Automobiles</li>
                    <li>Best Company of the Year in Logistics</li>
                    <li>Best Company of the Year in Infrastructure & Real Estate</li>
                    <li>Best Company of the Year in Circular Economy (Waste Management & Recycling)</li>
                    <li>Best CSR Initiative of the Year</li>
                    <li>Best Women Entrepreneur of the Year</li>
                    <li>Life-time Achievement Award</li>
                </ol>
            </div>
            <div class="bea-section-title" style="text-align:left;">Selection Process</div>
            <div class="bea-content" style="margin-bottom:0.5rem;">
                <ul style="list-style:square inside; color:#fff;">
                    <li>Applications are invited from enterprises present across Andhra Pradesh.</li>
                    <li>Entries will be evaluated based on criteria such as financial performance, growth, innovation, quality standards, exports, CSR initiatives, and employee welfare.</li>
                    <li>A distinguished jury panel will independently assess and finalise the winners.</li>
                </ul>
            </div>
            <div class="bea-nomination-link">
                Please Click <a href="https://docs.google.com/forms/d/e/1FAIpQLSf4TjzSEEr31oh9MU4Lf6-TUMEIxFNwr9YEgcJPeARsDArMKQ/viewform" target="_blank" class="btn btn-warning btn-lg fw-bold px-4 py-2 mb-2">Nominate Now</a> to submit your nomination
            </div>
			<div class="bea-sponsor-section">
				
					<img src="https://apchambers.in/wp-content/uploads/2025/06/Screenshot-2025-06-28-at-6.43.15 PM.png" alt="Sponsors" class="bea-sponsor-image" />
				
			</div>
            <div class="bea-footer">
                <img src="https://apchambers.in/wp-content/uploads/2025/06/apchamberslogopng.png" alt="AP Chambers Logo" class="bea-footer-logo" />
                Andhra Pradesh Chambers of Commerce and Industry Federation<br>
				#40-1-144, 3rd Floor, Corporate Centre,<br>
				Beside Chandana Grand, M.G. Road, Vijayawada, Andhra Pradesh 520 010<br>
				Ph: +91 866 2482888, +91 99120 92222, +91 91212 21473, +91 91212 21474<br>
				federation@apchamber.in

            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
} 

add_shortcode("AllGOsPage", "AllGOsPage");
function AllGOsPage() {
    global $wpdb;
    $site_url = get_site_url();
    $gosData = $wpdb->get_results("SELECT GO_Description, PDF_1, DATE_FORMAT(Gos_Date,'%d %M, %Y') as gosDate FROM {$wpdb->prefix}gos WHERE isTrash = 0 ORDER BY Gos_Date DESC", "ARRAY_A");
    ?>
    <div class="container mt-4">
        <h2 class="mb-4 text-center">All GOs / Policies</h2>
        <div class="row justify-content-center mb-3">
            <div class="col-md-6">
                <input type="text" id="goSearchInput" class="form-control" placeholder="Search GOs...">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="list-group" id="goList">
                    <?php foreach ($gosData as $go) : 
                        $pdf = $go['PDF_1'] != '' ? ' href="' . $go['PDF_1'] . '"' : '';
                    ?>
                        <div class="list-group-item mb-3 shadow-sm go-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <a<?php echo $pdf; ?> target="_blank" class="h5 mb-1 go-title"><?php echo $go['GO_Description']; ?></a>
                                <span class="badge bg-secondary go-date"><?php echo $go['gosDate']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('goSearchInput');
        const items = document.querySelectorAll('.go-item');
        input.addEventListener('input', function() {
            const filter = input.value.toLowerCase();
            items.forEach(function(item) {
                const title = item.querySelector('.go-title').textContent.toLowerCase();
                const date = item.querySelector('.go-date').textContent.toLowerCase();
                if (title.includes(filter) || date.includes(filter)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    </script>
    <?php
} 

add_shortcode("PastEventsPage", "PastEventsPage");
function PastEventsPage()
{
    global $wpdb;
    $site_url = get_site_url();
    $curDate = date('Y-m-d');
    $upcomingEvents = $wpdb->get_results( "SELECT ID,Event_Name,
                                               TIME_FORMAT( Start_Time,'%h:%i %p' ) as startTime,
                                               DATE_FORMAT(Start_Date, '%W %d,%b-%Y') as startDate,
                                               userProfile_1
                                        FROM {$wpdb->prefix}chamber_events 
                                        WHERE isTrash = 0 AND Start_Date <= '{$curDate}'  ORDER BY Start_Date DESC", "ARRAY_A");
    if( count ( $upcomingEvents ) > 0 )
    {
        // Add CSS for uniform card and image sizes
        echo '<style>
        .past-event-card {
            height: 420px; /* Adjust as needed */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .past-event-card .card-body {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
		.past-event-image {
			width: 100%;
			height: 320px;
			object-fit: contain;
			border-radius: 4px 4px 0 0;
			margin-bottom: 10px;
			background: #f4f4f4;
		}
        </style>';

        echo '<div class="row mb-2 d-flex justify-content-center">';
        foreach( $upcomingEvents as $upcomingEvent )
        {
            $galleryImage = '';
            $imgQry = $wpdb->get_results( "SELECT userProfile_1
                                        FROM {$wpdb->prefix}event_gallery 
                                        WHERE isTrash = 0 AND Event='{$upcomingEvent['ID']}'  
                                        ORDER BY View_Order Limit 1", "ARRAY_A");
            if( count ( $imgQry ) > 0 && !empty($imgQry[0]['userProfile_1']) )
            {
                $galleryImage = '<img class="past-event-image" src="'.$imgQry[0]['userProfile_1'].'" />';
            }
            // Fallback to chamber_events.userProfile_1 if no image in event_gallery
            else if (!empty($upcomingEvent['userProfile_1'])) {
                $galleryImage = '<img class="past-event-image" src="'.$upcomingEvent['userProfile_1'].'" />';
            }
            ?>
                <div class="col-sm-6 col-md-4 p-2">
                    <div class="card shadow past-event-card">
                        <div class="card-header">
                          <h5 class="card-title"><b>
                            <a href="<?=$site_url;?>/view-gallery/?event_gallery=<?=$upcomingEvent['ID'];?>" >
                                <?=$upcomingEvent['Event_Name'] ;?>
                            </a></b></h5>
                        </div>
                        <div class="card-body">
                            <?=$galleryImage; ?>
                            <p class="card-text text-right">on <?=$upcomingEvent['startDate'] ;?> at <?=$upcomingEvent['startTime'] ;?></p>
                            <?
                            if( $galleryImage != ''){
                            ?>
                            <!--<a href="<?=$site_url;?>/view-gallery/?event_gallery=<?=$upcomingEvent['ID'];?>" class="btn btn-outline-info card-text text-right">View Gallery</a> -->
                            <?
                            }
                            ?>
                          </div>
                    </div>    
                </div>
            <?
        }
        echo '</div>';
    }
}


add_shortcode("AllEventsPage", "AllEventsPage");
function AllEventsPage()
{
    global $wpdb;
    $site_url = get_site_url();
    $curDate = date('Y-m-d');

    // Start output buffering (so we can return all HTML as a string at the end)
    ob_start();

    // --- 1. Custom CSS for our plain JS carousel ---
    ?>
    <style>
    /* Container for each event's carousel */
    .custom-carousel {
        position: relative;
        width: 100%;
        overflow: hidden;
        margin-bottom: 1rem; /* spacing under the carousel */
    }

    /* Wrapper that holds all slides in a row */
    .custom-carousel-inner {
        display: flex;
        transition: transform 0.5s ease;
        width: 100%;
    }

    /* Each slide takes up 100% of the width */
    .custom-carousel-item {
        min-width: 100%;
        box-sizing: border-box;
    }

    .custom-carousel-item img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Navigation Buttons */
    .custom-carousel-control-prev,
    .custom-carousel-control-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0,0,0,0.3);
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        cursor: pointer;
        z-index: 10;
    }
    .custom-carousel-control-prev:hover,
    .custom-carousel-control-next:hover {
        background-color: rgba(0,0,0,0.5);
    }
    .custom-carousel-control-prev {
        left: 10px;
    }
    .custom-carousel-control-next {
        right: 10px;
    }
    </style>

    <!-- 2. Plain JavaScript for the custom carousel behavior -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Find all custom carousel containers
      const carousels = document.querySelectorAll('.custom-carousel');

      carousels.forEach(function(carousel) {
        const inner = carousel.querySelector('.custom-carousel-inner');
        const items = carousel.querySelectorAll('.custom-carousel-item');
        let currentIndex = 0;

        // Get Prev and Next buttons
        const prevButton = carousel.querySelector('.custom-carousel-control-prev');
        const nextButton = carousel.querySelector('.custom-carousel-control-next');

        // Function to update slide position
        function updateCarousel() {
          // Shift the carousel so that the current slide is in view
          inner.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
        }

        // Move to previous slide
        prevButton.addEventListener('click', function() {
          // If at the first slide, wrap around to the last
          currentIndex = (currentIndex === 0) ? items.length - 1 : currentIndex - 1;
          updateCarousel();
        });

        // Move to next slide
        nextButton.addEventListener('click', function() {
          // If at the last slide, wrap around to the first
          currentIndex = (currentIndex === items.length - 1) ? 0 : currentIndex + 1;
          updateCarousel();
        });
      });
    });
    </script>
    <?php

    // --- 3. Query the database for events ---
    $events = $wpdb->get_results("
        SELECT ID, Event_Name, 
               TIME_FORMAT(Start_Time, '%h:%i %p') AS startTime,
               DATE_FORMAT(Start_Date, '%W %d, %b-%Y') AS startDate,
               Start_Date
        FROM {$wpdb->prefix}chamber_events 
        WHERE isTrash = 0 
        ORDER BY Start_Date DESC",
    "ARRAY_A");

    // --- 4. Render the events in a Bootstrap row (cards) ---
    if (count($events) > 0) {
        echo '<div class="row mb-2 d-flex justify-content-center">';

        foreach ($events as $event) {
            $eventID   = $event['ID'];
            $eventName = $event['Event_Name'];
            $startDate = $event['startDate'];
            $startTime = $event['startTime'];

            // Fetch all images for the event
            $imageResults = $wpdb->get_results("
                SELECT userProfile_1 
                FROM {$wpdb->prefix}event_gallery 
                WHERE isTrash = 0 
                  AND Event = '{$eventID}'
                ORDER BY View_Order",
            "ARRAY_A");
            ?>
            
            <div class="col-sm-6 col-md-6 col-lg-4 p-2">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="card-title">
                            <b>
                                <a href="<?php echo esc_url($site_url); ?>/view-gallery/?event_gallery=<?php echo esc_attr($eventID); ?>">
                                    <?php echo esc_html($eventName); ?>
                                </a>
                            </b>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($imageResults)) : ?>
                            <!-- 5. Our custom carousel container -->
                            <div class="custom-carousel">
                                <div class="custom-carousel-inner">
                                    <?php
                                    // Generate a slide for each image
                                    foreach ($imageResults as $image) {
                                        $imageURL = $image['userProfile_1'];
                                        ?>
                                        <div class="custom-carousel-item">
                                            <img src="<?php echo esc_url($imageURL); ?>" alt="Event Image">
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <!-- Prev/Next Buttons -->
                                <button class="custom-carousel-control-prev" type="button">
                                    Prev
                                </button>
                                <button class="custom-carousel-control-next" type="button">
                                    Next
                                </button>
                            </div>
                        <?php else : ?>
                            <p class="text-muted">No images available</p>
                        <?php endif; ?>

                        <p class="card-text text-right">
                            On <?php echo esc_html($startDate); ?> at <?php echo esc_html($startTime); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }

    // Return all output
    return ob_get_clean();
}



add_shortcode("UpcomingEventsPage", "UpcomingEventsPage");
function UpcomingEventsPage()
{
	global $wpdb;
	$site_url = get_site_url();
	$curDate = date('Y-m-d');
	$upcomingEvents = $wpdb->get_results( "SELECT ID,Event_Name,
											   TIME_FORMAT( Start_Time,'%h:%i %p' ) as startTime,
											   DATE_FORMAT(Start_Date, '%W %d,%b-%Y') as startDate 
										FROM {$wpdb->prefix}chamber_events 
										WHERE isTrash = 0 AND Start_Date >= '{$curDate}'  ORDER BY Start_Date DESC", "ARRAY_A");
	if( count ( $upcomingEvents ) > 0 )
	{
		echo '<div class="row mb-2 d-flex justify-content-center">';
		foreach( $upcomingEvents as $upcomingEvent )
		{
			$galleryImage = '';
			$imgQry = $wpdb->get_results( "SELECT userProfile_1
										FROM {$wpdb->prefix}event_gallery 
										WHERE isTrash = 0 AND Event='{$upcomingEvent['ID']}'  
										ORDER BY View_Order Limit 1", "ARRAY_A");
			if( count ( $imgQry ) > 0 )
			{
				$galleryImage = '<img style="width:100%" src="'.$imgQry[0]['userProfile_1'].'" />';
			}
			?>
				<div class="col-sm-6 col-md-4 p-2">
					<div class="card shadow">
						<div class="card-header">
                          <h5 class="card-title"><b><a href="<?=$site_url;?>/view-gallery/?event_gallery=<?=$upcomingEvent['ID'];?>" ><?=$upcomingEvent['Event_Name'] ;?></a></b></h5>
						</div>
						<div class="card-body">
							<?=$galleryImage; ?>
							<p class="card-text text-center">on <?=$upcomingEvent['startDate'] ;?> at <?=$upcomingEvent['startTime'] ;?></p>
							<?
							if( $galleryImage != ''){
							?>
							<!-- <a href="<?=$site_url;?>/view-gallery/?event_gallery=<?=$upcomingEvent['ID'];?>" class="btn btn-outline-info card-text text-right">View Gallery</a> -->
							<?
							}
							?>
						  </div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}




add_shortcode("SeminarsPage", "SeminarsPage");
function SeminarsPage()
{
	global $wpdb;
	$seminarData = $wpdb->get_results( "SELECT Seminar_Name,
											   Seminar_Description,
											   DATE_FORMAT(Seminar_Date, '%W %d %M, %Y') as seminarDate 
										FROM {$wpdb->prefix}seminar 
										WHERE isTrash = 0 ORDER BY Seminar_Date DESC", "ARRAY_A");
	if( count ( $seminarData ) > 0 )
	{
		echo '<div class="row mb-2">';
		foreach( $seminarData as $seminar )
		{
			?>
				<div class="col-sm-12 p-2">
					<div class="card shadow">
						<div class="card-body">
							<h5 class="card-title"><b><?=$seminar['Seminar_Name'] ;?></b></h5>
							<p class="card-text"><?=$seminar['Seminar_Description'] ;?></p>
							<span class="text-right"><?=$seminar['seminarDate'] ;?></span>
						  </div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}





add_shortcode( "ActivationUserPage" , "ActivationUserPage" );
function ActivationUserPage($atts)
{	
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{
		
    	global $wpdb;
	
		$returnSubmitValue = '';
		$classUI = new classUI();
		$classMysql = new classMysql();
		$currentId = get_current_user_id();
		$isGrid = true;
	
	
		
		
		$joinUsers = "{$wpdb->prefix}users";
			$exQry = "";
		
		$memberQry = $wpdb->get_results("SELECT 
												{$joinUsers}.ID,
												{$joinUsers}.user_login,
												{$joinUsers}.user_email,
												{$joinUsers}.is_Activated
												FROM 
												{$joinUsers} 
												WHERE ID != 1
												order by {$joinUsers}.user_login " , 'ARRAY_A' );
		$i = 0;
		$tableArr = array();	
		foreach( $memberQry as $key => $val ) 
		{
			if( (int)$val['is_Activated'] == 0)  
				$tableArr[$i]['Actiavate'] = array("value" => "Activate", "type" => "text","id"=>"ActivateUser_{$val['ID']}");
			else $tableArr[$i]['Actiavate'] = array("value" => "DEACTIVATE", "type" => "text-right", "id"=>"DE-ActivateUser_{$val['ID']}");
			$tableArr[$i]['user_login'] = array("value" => $val['user_login'], "type" => "text");
			$tableArr[$i]['user_email'] = array("value" => $val['user_email'], "type" => "text");
					
					$i++;
		}
			if( count ( $tableArr ) > 0 )
			{
				echo '<div class="row  mt-3" >';
				echo '<div class="col-12">';
				echo $classUI->showReports($tableArr, "reportTable01", array());
				echo '</div>';
				echo '</div>';
				 ?>
		<script>
		
			var _rulesString = {};
			var _messagesString = {};
			$(function(){
				$('body').on('click', '[id^=ActivateUser_]', function () {
					
					$('html').block();
					var tId = $(this).attr("id");
					var thisId = tId.split("ActivateUser_")[1];
					 var dataa = {};
					dataa['action'] = 'activateUser';
					dataa['Id'] = thisId  ;
					
					$.ajax({
						type: 'POST',
						url: thisAjax,
						data:dataa,
						success: function(data) {
							$('html').unblock(); 
							data = data.trim();
							if(data == 'ERROR')
							{
								alert_danger("Failed to activate user");
							}
							else{
								alert_sucuss("User activated successfully");
								$("#"+tId).html("DEACTIVATE");
								$("#"+tId).attr("id","DE-"+tId);
								
							}
						},
						error: function (errorThrown) {
							$('html').unblock();
							alert_danger(errorThrown.responseText);
						}
					}); 
				});
				$('body').on('click', '[id^=DE-ActivateUser_]', function () {
					
					$('html').block();
					var tId = $(this).attr("id");
					var newId = tId.split("DE-")[1];
					var thisId = tId.split("DE-ActivateUser_")[1];
					 var dataa = {};
					dataa['action'] = 'deActivateUser';
					dataa['Id'] = thisId  ;
					
					$.ajax({
						type: 'POST',
						url: thisAjax,
						data:dataa,
						success: function(data) {
							$('html').unblock(); 
							data = data.trim();
							if(data == 'ERROR')
							{
								alert_danger("Failed to de-activate user");
							}
							else{
								alert_sucuss("User de-activated successfully");
								$("#"+tId).html("Activate");
								$("#"+tId).attr("id",newId);
								
							}
						},
						error: function (errorThrown) {
							$('html').unblock();
							alert_danger(errorThrown.responseText);
						}
					}); 
				});
			});

		</script>
		<?php
			}
			else echo $classUI->noDataFound("User Data Not Found");
		
		
		
	
	}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}

	
add_shortcode("ViewGalleryPage", "ViewGalleryPage");
function ViewGalleryPage()
{
	global $wpdb;
	$images = array();
	$galleryImage = '';
	if( isset( $_GET['event_gallery'] ) )
	{
		$images = $wpdb->get_results("SELECT userProfile_1 from {$wpdb->prefix}event_gallery WHERE isTrash=0 AND Event={$_GET['event_gallery']} order by View_Order","ARRAY_A");
     	$upcomingEvents = $wpdb->get_results( "SELECT ID,Event_Name,Event_Details,userProfile_1,
											   TIME_FORMAT( Start_Time,'%h:%i %p' ) as startTime,
											   DATE_FORMAT(Start_Date, '%W %d,%b-%Y') as startDate 
										FROM {$wpdb->prefix}chamber_events 
										WHERE  ID = {$_GET['event_gallery']} limit 1", "ARRAY_A");
		if( count ( $upcomingEvents ) > 0 )
        {
          foreach( $upcomingEvents as $upcomingEvent )
		{
          ?>
          		<div class="card shadow mt-2">
						<div class="card-header" style="align-self: center;">
							<h5 class="card-title "><b><?=$upcomingEvent['Event_Name'] ;?></b></h5>
						</div>
						<div class="card-body">
                         	 <div class="row">
                            <?
            					if( $upcomingEvent['userProfile_1'] != '' )
                                {
                                  ?>
                                	<div class="col-sm-6 p-2 col-md-4 col-lg-3 shadow">
                                      	<img style="width:100%;height:100%" src="<?=$upcomingEvent['userProfile_1'] ;?>">
                            		</div>
                            		<div class="col-sm-6 p-2 col-md-8 col-lg-9 shadow">
                            	<?
                                }
                                else
                                {
                                  ?>
                            		<div class="col-sm-12 p-2 shadow">
                            	  <?
									
                                }
                            ?>
       						
                            
										<p class="card-text text-left"><?=$upcomingEvent['Event_Details'] ;?></p>
                            		</div>
                           
                            		<div class="col-sm-12 p-2 shadow">
										<p class="card-text text-center">on <?=$upcomingEvent['startDate'] ;?> at <?=$upcomingEvent['startTime'] ;?></p>
                             		</div> 
							<?
							if( $galleryImage != '')
                            {
							?>
							<!-- <a href="<?=$site_url;?>/view-gallery/?event_gallery=<?=$upcomingEvent['ID'];?>" class="btn btn-outline-info card-text text-right">View Gallery</a> -->
							<?
							}
							?>
						  	</div>
						</div>
<?php
          }
        }
	}
	?>
    <div class="row" data-container>
		<?
			foreach( $images as $image )
			{
		?>
       <div class="col-sm-6 p-2 col-md-4 col-lg-3 shadow"><img class="zoom-img" style="width:100%;height:100%" src="<?=$image['userProfile_1'] ;?>">
       </div>
		<? }
		?>
            
    </div>
<?
		
}

add_shortcode("InitiativeChamberPage", "InitiativeChamberPage");
function InitiativeChamberPage()
{
  
  
		echo '<div class="wrap__about-us mt-4"> 
        		<div class="card card-success">
				
                	<h3><b>Next Gen</b></h3>
				
				
					<p>Next Gen is an integral part of " The Andhra Pradesh Chambers of Commerce and Industry Federation " and will be a platform to bring all the young entrepreneurs of Andhra Pradesh into one great body with the purpose of inspiring learning, sharing of knowledge and ideas, and thus strengthening of the business communities in this state.</p>


					<p>AP State re- organization has brought to the forefront many challenges than opportunities for the residue State of Andhra Pradesh. Next Gen group is a platform for all round development of all those who believe in society and societal progress. A new era of thinking is on the horizon. NextGen invites all the ptogressive minds to join us to collaborate and work towards creating enterprises and opportunities in the state of AP and make AP the desired state for better living and better opportunities.</p>
				
			</div>';

		
		echo '<div class="row mt-2">';
		
		echo '</div>';
       echo '<h3><b>Natural Organic Farmers Association (NOFA)</b></h3>
                <p>Andhra Pradesh Chambers has pramoted Natural Organic Farmers Association (NOFA) in 2014, is a statewide organization leading a growing movement of farmers, consumers, gardeners, and businesses committed to promoting sustainable, local, organic food and farming.</p>
                <p>NOFA provides education in organic farming, assistance and support to regionally-based, sustainable farmers to help them thrive, educate consumers about the value in buying local, organic products, help consumers connect with regionally-based farmers, work to make local, organic food available to all people, and advocate policies that support a sustainable food and farm system.</p>
                <h3><b>NOFA Mission Statement</b></h3>
                <p>NOFA is an organization of farmers, gardeners and consumers creating a sustainable regional food system which is ecologically sound and economically viable. Through demonstration and education, NOFA promote organic farming, organic food production, and local marketing. NOFA brings organic sector consumer and farmer closer together to make high quality food available to all people with nominal cost.</p>
                <p>NOFA is a member-based, non-profit educational organization supported in part by membership donations and individual contributions. Charitable donations are tax deductible to fullest extend allowable by the IRS.</p>
                </div>';
	
              
}


add_shortcode("NextGenPage", "NextGenPage");
function NextGenPage()
{
	global $wpdb;
	$designationTable = "{$wpdb->prefix}designation";
	$exeTable = "{$wpdb->prefix}executive_profile";
	$userTable = "{$wpdb->prefix}users";
	$siteTitle = get_site_url();
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$exeTable}.PDF_1,
													{$exeTable}.Description,
													{$designationTable}.Designation as designationName
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Designation  )
										JOIN {$exeTable} ON 
												( {$exeTable}.Executive_Member = {$userTable}.ID  )
										WHERE {$userTable}.isTrash = 0  AND 
											  {$userTable}.Page_Name  LIKE '%Next Gen%'  
										ORDER BY {$userTable}.Applicant_Name", "ARRAY_A");
	//print_r($executiveMembers);
	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="card card-success">
				<div class="card-header">
                
				</div>
				<div class="card-body">
					Next Gen is an integral part of " The Andhra Pradesh Chambers of Commerce and Industry Federation " and will be a platform to bring all the young entrepreneurs of Andhra Pradesh into one great body with the purpose of inspiring learning, sharing of knowledge and ideas, and thus strengthening of the business communities in this state.
				</div>
			</div>';
	
		
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-12 p-2">
					<div class="card shadow">
						<div class="card-body  text-center">
							<div class="row">
								<div class="col-sm-5 col-md-4 p-2">
									<img style="width:60%" src="<?=$member['userProfile_1'] ;?>" />
									<div class="border">
										<h5 style="float:none" class="mt-2 card-title text-center">
											<b><?=$member['Applicant_Name'] ;?></b></h5>
										<p class="card-text mt-2 text-center"><?=$member['designationName'] ;?></p>
									</div>
								</div>
								<div class="col-sm-7 col-md-8 p-2 text-left">
									<?=$member['Description'] ;?>
								</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
	
}


add_shortcode("BoardOfDirectorsPage", "BoardOfDirectorsPage");
function BoardOfDirectorsPage()
{
	global $wpdb;
	
	// Add CSS for consistent image sizing
	echo '<style>
		.board-directors-img {
			width: 100%;
			height: 220px;
			object-fit: contain;
			object-position: center;
			background-color: #f8f9fa;
		}
		.board-directors-card {
			height: 100%;
		}
		.board-directors-card-body {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.board-directors-img-container {
			flex-shrink: 0;
		}
		.board-directors-info {
			flex-grow: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
	</style>';
	
	$designationTable = "{$wpdb->prefix}chamber_designation";
	$userTable = "{$wpdb->prefix}users";
	$defaultImage = get_site_url().'/default-user.png';
	
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Chamber_Designation as chamberDesi
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Chamber_Designation  )
										WHERE {$userTable}.isTrash = 0  AND 
										      {$userTable}.Page_Name LIKE '%Board Of Directors - Zonal Heads%'  
										ORDER BY {$userTable}.Member_Order", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="alert mt-2 pl-2 alert-light" role="alert">Zonal Heads</div>';
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			$profileImage = (!empty($member['userProfile_1'])) ? $member['userProfile_1'] : $defaultImage;
			?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-2">
					<div class="card shadow board-directors-card">
						<div class="card-body text-center board-directors-card-body">
							<div class="board-directors-img-container">
								<img class="board-directors-img" src="<?=$profileImage;?>" alt="<?=$member['Applicant_Name'];?>" />
							</div>
							<div class="border board-directors-info">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['chamberDesi'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
	
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Chamber_Designation as chamberDesi
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Chamber_Designation  )
										WHERE {$userTable}.isTrash = 0  AND 
										      {$userTable}.Page_Name LIKE '%Board Of Directors - Board Members%'  
										ORDER BY {$userTable}.Member_Order", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="alert mt-2 pl-2 alert-light" role="alert">Board Members</div>';
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			$profileImage = (!empty($member['userProfile_1'])) ? $member['userProfile_1'] : $defaultImage;
			?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-2">
					<div class="card shadow board-directors-card">
						<div class="card-body text-center board-directors-card-body">
							<div class="board-directors-img-container">
								<img class="board-directors-img" src="<?=$profileImage;?>" alt="<?=$member['Applicant_Name'];?>" />
							</div>
							<div class="border board-directors-info">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['chamberDesi'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}

add_shortcode("ManagementCommitteePage", "ManagementCommitteePage");
function ManagementCommitteePage()
{
	global $wpdb;
	$designationTable = "{$wpdb->prefix}chamber_designation";
	$executiveTable = "{$wpdb->prefix}executive_profile";
	$userTable = "{$wpdb->prefix}users";
	
	// Add CSS for consistent image sizing (only for grid layout)
	echo '<style>
		.management-committee-grid-img {
			width: 100%;
			height: 200px;
			object-fit: contain;
			object-position: center;
		}
		.management-committee-grid-card {
			height: 100%;
		}
		.management-committee-grid-card-body {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.management-committee-grid-img-container {
			flex-shrink: 0;
		}
		.management-committee-grid-info {
			flex-grow: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
	</style>';
	
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Chamber_Designation as chamberDesi,
													{$executiveTable}.PDF_1,
													{$executiveTable}.Description
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Chamber_Designation  )
										LEFT JOIN {$executiveTable} ON 
												( {$executiveTable}.Executive_Member = {$userTable}.ID  )		
										WHERE {$userTable}.isTrash = 0  AND 
										     {$userTable}.Page_Name LIKE '%Executive Committiee Profile%'  
										ORDER BY {$userTable}.Member_Order", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			$pdfLink = '';
			//echo $member['PDF_1'];
			if( $member['PDF_1'] != '')
			{
				$handle = @fopen($member['PDF_1'], 'r');
				if($handle){
					$pdfLink = '<a class="btn btn-outline-info" href="'.$member['PDF_1'].'" >Detailed Profile</a>';
				}
			}
				
			?>
				<div class="col-sm-12  shadow border-top border-primary p-2">
					<div class="row">
						<div class="col-sm-4 col-md-3">
							<div class="card">
							<div class="card-body  text-center">
									<img style="width:90%" src="<?=$member['userProfile_1'] ;?>" />
								<div class="border">
									<h5 style="float:none" class="mt-2 card-title text-center"><b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['chamberDesi'] ;?></p>
								</div>
								
							  </div>
						</div>	
						</div>
						<div class="col-sm-8 p-2 col-md-9">
							<h5 class="card-title"><? echo stripslashes ( htmlspecialchars_decode( nl2br(  $member['Description'] ) ) );?></h5>
							<p class="card-text"><?=$pdfLink;?></p>
						</div>
					</div>
								
							
				</div>
			<?
		}
		echo '</div>';
	}
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Chamber_Designation as chamberDesi
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Chamber_Designation  )
										WHERE {$userTable}.isTrash = 0  AND 
										      {$userTable}.Page_Name LIKE '%Executive Committiee Without Profile%'  
										ORDER BY {$userTable}.Member_Order", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="row mb-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-2">
					<div class="card shadow management-committee-grid-card">
						<div class="card-body management-committee-grid-card-body text-center">
							<div class="management-committee-grid-img-container">
								<img class="management-committee-grid-img" src="<?=$member['userProfile_1'] ;?>" alt="<?=$member['Applicant_Name'] ;?>" />
							</div>
							<div class="management-committee-grid-info border">
								<h5 style="float:none" class="mt-2 card-title text-center"><b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['chamberDesi'] ;?></p>
							</div>
							
						  </div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}



add_shortcode("PastLeadersPage", "PastLeadersPage");
function PastLeadersPage()
{
	global $wpdb;
	$userTable = "{$wpdb->prefix}users";
	$executiveMembers = $wpdb->get_results("SELECT {$userTable}.*	
    									FROM {$userTable}
										WHERE {$userTable}.isTrash = 0  AND 
											  {$userTable}.Page_Name  LIKE '%Past Leaders - Past Presidents%'  
										ORDER BY {$userTable}.Member_Order", "ARRAY_A");

	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="alert mt-2 pl-2 alert-light" role="alert">Past Presidents</div>';
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-2">
					<div class="card shadow">
						<div class="card-body  text-center">
							<img style="width:100%" src="<?=$member['userProfile_1'] ;?>" />
							<div class="border">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['Past_President_Year'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
	
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*
										FROM {$userTable}
										
										WHERE {$userTable}.isTrash = 0  AND 
											  {$userTable}.Page_Name LIKE '%Past Leaders - Past General Secretaries%'  
										ORDER BY {$userTable}.Member_Order", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		echo '<div class="alert mt-2 pl-2 alert-light" role="alert">Past General Secretaries</div>';
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-6 col-md-4 col-lg-3 p-2">
					<div class="card shadow">
						<div class="card-body  text-center">
							<img style="width:100%" src="<?=$member['userProfile_1'] ;?>" />
							<div class="border">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['Past_Secretary_Year'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}



add_shortcode("GovernmentOrderPage", "GovernmentOrderPage");
function GovernmentOrderPage()
{
	global $wpdb;
	$classUI = new classUI();
	$gosTable = "{$wpdb->prefix}gos";
	$siteTitle = get_site_url();
	$orders = $wpdb->get_results( "SELECT {$gosTable}.*,
										DATE_FORMAT( {$gosTable}.Gos_Date  , '%d-%m-%Y' ) as gosDate
										FROM {$gosTable}
										WHERE {$gosTable}.isTrash = 0  
										ORDER BY {$gosTable}.Gos_Date ", "ARRAY_A");
	if( count ( $orders ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $orders as $order )
		{
          	$pdfLink = '';
			//echo $member['PDF_1'];
			if( $order['PDF_1'] != '')
			{
				$handle = @fopen($order['PDF_1'], 'r');
				if($handle){
					$pdfLink = '<a class="btn btn-outline-info" href="'.$order['PDF_1'].'" >View PDF</a>';
				}
			}
			
			
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$order['GO_Description']; ?>
							</b>
    					</div>
						
						<div class="card-body  text-right">
							<!-- <img style="width:100%" src="<?=$order['userProfile_1'] ;?>" /> -->
							<p class="card-text  p-0 text-right"><?=$order['gosDate'] ; ?></p>
                           <p class="card-text "><?=$pdfLink ; ?></p>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Government Order found");
	}
}



add_shortcode("GovernmentPoliciesPage", "GovernmentPoliciesPage");
function GovernmentPoliciesPage()
{
	global $wpdb;
	$classUI = new classUI();
	$gosTable = "{$wpdb->prefix}policies";
	$siteTitle = get_site_url();
	$orders = $wpdb->get_results( "SELECT {$gosTable}.*,
										DATE_FORMAT( {$gosTable}.Policy_Date , '%d-%m-%Y' ) as policyDate
										FROM {$gosTable}
										WHERE {$gosTable}.isTrash = 0  
										ORDER BY {$gosTable}.Policy_Date", "ARRAY_A");
	if( count ( $orders ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $orders as $order )
		{
			
          	$pdfLink = '';
			//echo $member['PDF_1'];
			if( $order['PDF_1'] != '')
			{
				$handle = @fopen($order['PDF_1'], 'r');
				if($handle){
					$pdfLink = '<a class="btn btn-outline-info" href="'.$order['PDF_1'].'" >View PDF</a>';
				}
			}
			
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$order['Policy_Description']; ?>
							</b>
    					</div>
						
						<div class="card-body  text-right">
							<!-- <img style="width:100%" src="<?=$order['userProfile_1'] ;?>" /> -->
							<p class="card-text  p-0 text-right"><?=$order['policyDate'] ; ?></p>
                          <p class="card-text "><?=$pdfLink ; ?></p>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Government Policy found");
	}
}


add_shortcode( "SearchDirectoryPage" , "SearchDirectoryPage" );
function SearchDirectoryPage($atts)
{	
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{
		
    	global $wpdb;
	
		$returnSubmitValue = '';
		$classUI = new classUI();
		$classMysql = new classMysql();
		$currentId = get_current_user_id();
		$isGrid = true;
	
	
		
		
		$joinUsers = "{$wpdb->prefix}users";
			$exQry = "";
		if( isset( $_POST['search']))
		{
			if (  $_POST['search'] == "List View" ) $isGrid = false;
			$exQry = " AND ( {$joinUsers}.Applicant_Name LIKE '%{$_POST['search_text']}%'
			OR {$joinUsers}.User_Role LIKE '%{$_POST['search_text']}%' 
			OR {$joinUsers}.Mobile_No LIKE '%{$_POST['search_text']}%' 
			OR {$joinUsers}.Email_ID LIKE '%{$_POST['search_text']}%' 
			OR {$joinUsers}.Organization_Name LIKE '%{$_POST['search_text']}%' ) ";
		}
		$memberQry = $wpdb->get_results("SELECT 
												{$joinUsers}.ID,
												{$joinUsers}.Chamber_Designation,
												{$joinUsers}.Constitution,
												{$joinUsers}.Applicant_Name,
												{$joinUsers}.User_Role,
												{$joinUsers}.userProfile_1,
												{$joinUsers}.Mobile_No,
												{$joinUsers}.Email_ID,
												{$joinUsers}.Organization_Name
												FROM 
												{$joinUsers} 
												WHERE {$joinUsers}.Applicant_Name != ''
												{$exQry}
												order by {$joinUsers}.Applicant_Name " , 'ARRAY_A' );
		foreach( $memberQry as $member ) 
			$memberArr[$member['ID']] = $member;
		
		$i = $j = 0;
		echo "<form autocomplete='off' method='post'>";
		echo '<div class="row">';
			echo '<div class="col-4 mb-2">';
				echo '<input type="text" class="form-control" placeholder="enter search text" name="search_text" value="" />';
			echo '</div>';
			echo '<div class="col-4 mb-2">';
				echo '<input type="submit" class="form-control" name="search" value="Grid View" />';
			echo '</div>';
			echo '<div class="col-4 mb-2">';
				echo '<input type="submit" class="form-control" name="search" value="List View" />';
			echo '</div>';
		echo '</div>';
		echo '</form>';
		
			?>
		 <div class="card">
				  <div class="card-body">
					  <div class="row">
					  <?
						foreach( $memberArr as $member )
						{
							//print_r( $member ); exit;
							if( $isGrid )
							{
							$imgString = strlen( $member['userProfile_1'] ) > 5 ? '<div class="col-md-4 my-auto">
									<img class="rounded  img-fluid" src="'.$member['userProfile_1'].'">
								</div>
								<div class="col-md-8">' : '<div class="col-md-12">';
						?>
					  <div class="col-12 col-sm-6 col-md-6 mb-3">
							<div class="info-box p-2 h-100">
								<?=$imgString; ?>
								<div class="info-box-content">
									<span class="info-box-text">
										<strong>Name : <?=$member['Applicant_Name'];?></strong>
									</span>
									<span class="info-box-text">
										<strong>Organization : <?=$member['Organization_Name'];?></strong>
									</span>
									<span class="info-box-text"><?=$member['User_Role'];?></span>
                                    <span class="info-box-text">
										<i class="fa fa-phone"></i> &nbsp; <?=$member['Mobile_No'];?>
									</span>
									<span class="info-box-text">
										<i class="fa fa-envelope"> &nbsp;</i><?=$member['Email_ID'];?>
									</span>
								</div>
							</div>	
						</div>
					  	<?
						}
							else{
								?>
						  	<div class="col-12">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12 col-md-6">
												<span class="text-left">
														<strong>Name : <?=$member['Applicant_Name'];?></strong>
												</span>
											</div>
											<div class="col-sm-12 col-md-6">
												<span class="text-right">
														<strong>Organization : <?=$member['Organization_Name'];?></strong>
												</span>
											</div>
											<div class="col-sm-12 col-md-4">
												<span class="text-left">
														<?=$member['User_Role'];?>
												</span>
											</div>
											<div class="col-sm-12 col-md-4">
												<span class="text-center">
														<i class="fa fa-phone"></i>
														<a href="tel:<?=$member['Mobile_No'];?>" >
															<?=$member['Mobile_No'];?>
														</a>
												</span>
											</div>
											<div class="col-sm-12 col-md-4">
												<span class="text-right">
														<i class="fa fa-envelope"> &nbsp;</i>
														<?=$member['Email_ID'];?>
												</span>
											</div>
										</div>
										
									</div>	
								</div>
									</div>
<?
							}
							if( $isGrid )
							{
				echo '</div>';
			}
						}
			
					  ?>
						 </div>
				  </div>
			  </div>
			  
			 
			
				<?
		
	
	}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}


add_shortcode( "DirectoryPage" , "DirectoryPage" );
function DirectoryPage ($atts)
{	
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{
		
    	global $wpdb;
	
		$returnSubmitValue = '';
		$classUI = new classUI();
		$classMysql = new classMysql();
		$currentId = get_current_user_id();
	
	
		$chamberDesignation = $wpdb->get_results("select ID,Business_Type 
													from {$wpdb->prefix}business_type 
													WHERE isTrash=0 order by Business_Type " , 'ARRAY_A' );
		$chamberDesignationArr = $memberArr = array();
		foreach($chamberDesignation as $key => $value)
			$chamberDesignationArr[$value['ID']] = $value['Business_Type'];
		
		
		$joinUsers = "{$wpdb->prefix}users";
		
		$memberQry = $wpdb->get_results("SELECT 
												{$joinUsers}.ID,
												{$joinUsers}.Chamber_Designation,
												{$joinUsers}.Constitution,
												{$joinUsers}.Applicant_Name,
												{$joinUsers}.User_Role,
												{$joinUsers}.userProfile_1,
												{$joinUsers}.Mobile_No,
												{$joinUsers}.Email_ID,
												{$joinUsers}.Organization_Name
												FROM 
												{$joinUsers} 
												order by {$joinUsers}.Applicant_Name " , 'ARRAY_A' );
		foreach( $memberQry as $member ) 
			$memberArr[$member['Constitution']][$member['ID']] = $member;
		/*
		$i = $j = 0;
		echo "<form autocomplete='off' method='post'>";
		echo '<div class="row">';
			echo '<div class="col-6 mb-2">';
				echo '<input type="text" class="form-control" placeholder="enter search text" name="search_text" value="" />';
			echo '</div>';
			echo '<div class="col-6 mb-2">';
				echo '<input type="submit" class="form-control" name="search" value="Search" />';
			echo '</div>';
		echo '</div>';
		echo '</form>';
		*/
		echo '<div id="accordion">';
		foreach( $chamberDesignationArr as $key => $value )
		{
			if( isset($memberArr[$key] )  )
			{
				
			?>
			<div class="card border shadow">
				<div class="card-header" id="heading_<?=$key ;?>">
				  <h5 class="mb-0">
					<button class="btn btn-link w-100 text-left collapsed" data-toggle="collapse" 
							data-target="#collapse_<?=$key ;?>" aria-expanded="false" 
							aria-controls="collapse_<?=$key ;?>">
					  <?=$value ;?>
					</button>
				  </h5>
				</div>

				<div id="collapse_<?=$key ;?>" class="collapse" aria-labelledby="heading_<?=$key ;?>" 
					 data-parent="#accordion">
				  <div class="card-body">
					  <div class="row">
					  <?
						foreach( $memberArr[$key] as $member )
						{
							//print_r( $member ); exit;
							$imgString = strlen( $member['userProfile_1'] ) > 5 ? '<div class="col-md-4 my-auto">
									<img class="rounded  img-fluid" src="'.$member['userProfile_1'].'">
								</div>
								<div class="col-md-8">' : '<div class="col-md-12">';
						?>
					  <div class="col-12 col-sm-6 col-md-6 mb-3">
							<div class="info-box p-2 h-100">
								<?=$imgString; ?>
								<div class="info-box-content">
									<span class="info-box-text"><strong><?=$member['Applicant_Name'];?></strong></span>
									<span class="info-box-text"><strong><?=$member['Organization_Name'];?></strong></span>
									<span class="info-box-text"><?=$member['User_Role'];?></span>
                                    <span class="info-box-text">
										<i class="fa fa-phone"></i> &nbsp; <?=$member['Mobile_No'];?>
									</span>
									<span class="info-box-text">
										<i class="fa fa-envelope"> &nbsp;</i><?=$member['Email_ID'];?>
									</span>
								</div>
								</div>
							</div>	
						</div>
					  	<?
						}
					  ?>
						 </div>
				  </div>
				</div>
			  
			  
			 
			
				<?
			}
		}
		echo '</div>';
		
	
	}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}




add_shortcode( "CommitteeConnectPage" , "CommitteeConnectPage" );
function CommitteeConnectPage ($atts)
{	
	global $menuCheck;
	global $menuAction;
	global $pageId;

	if (  isset ( $menuCheck[$pageId] )  && is_user_logged_in()) {
		
		$user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
		
		if( ( is_array( $menuCheck[$pageId] ) && in_array( $role[$roleKey], $menuCheck[$pageId] )  ) || 
			( !is_array( $menuCheck[$pageId] ) && ( $menuCheck[$pageId] == 'in'  ) )
		)
		{
		
    	global $wpdb;
	
		$returnSubmitValue = '';
		$classUI = new classUI();
		$classMysql = new classMysql();
		$currentId = get_current_user_id();
	
	
		$chamberDesignation = $wpdb->get_results("select ID,Chamber_Designation 
													from {$wpdb->prefix}chamber_designation 
													WHERE isTrash=0 order by Chamber_Designation " , 'ARRAY_A' );
		$chamberDesignationArr = $memberArr = array();
		foreach($chamberDesignation as $key => $value)
			$chamberDesignationArr[$value['ID']] = $value['Chamber_Designation'];
		
		
		$joinUsers = "{$wpdb->prefix}users";
		
		$memberQry = $wpdb->get_results("SELECT 
												{$joinUsers}.ID,
												{$joinUsers}.Chamber_Designation,
												{$joinUsers}.Applicant_Name,
												{$joinUsers}.User_Role,
												{$joinUsers}.userProfile_1,
												{$joinUsers}.Mobile_No,
												{$joinUsers}.Email_ID,
												{$joinUsers}.Organization_Name
												FROM 
												{$joinUsers} 
												order by {$joinUsers}.Applicant_Name " , 'ARRAY_A' );
		foreach( $memberQry as $member ) 
			$memberArr[$member['Chamber_Designation']][$member['ID']] = $member;
		/*
		$i = $j = 0;
		echo "<form autocomplete='off' method='post'>";
		echo '<div class="row">';
			echo '<div class="col-6 mb-2">';
				echo '<input type="text" class="form-control" placeholder="enter search text" name="search_text" value="" />';
			echo '</div>';
			echo '<div class="col-6 mb-2">';
				echo '<input type="submit" class="form-control" name="search" value="Search" />';
			echo '</div>';
		echo '</div>';
		echo '</form>';
		*/
		echo '<div id="accordion">';
		foreach( $chamberDesignationArr as $key => $value )
		{
			if( isset($memberArr[$key] )  )
			{
				
			?>
			<div class="card border shadow">
				<div class="card-header" id="heading_<?=$key ;?>">
				  <h5 class="mb-0">
					<button class="btn btn-link w-100 text-left collapsed" data-toggle="collapse" 
							data-target="#collapse_<?=$key ;?>" aria-expanded="false" 
							aria-controls="collapse_<?=$key ;?>">
					  <?=$value ;?>
					</button>
				  </h5>
				</div>

				<div id="collapse_<?=$key ;?>" class="collapse" aria-labelledby="heading_<?=$key ;?>" 
					 data-parent="#accordion">
				  <div class="card-body">
					  <div class="row">
					  <?
						foreach( $memberArr[$key] as $member )
						{
							//print_r( $member ); exit;
							$imgString = strlen( $member['userProfile_1'] ) > 5 ? '<div class="col-md-4 my-auto">
									<img class="rounded  img-fluid" src="'.$member['userProfile_1'].'">
								</div>
								<div class="col-md-8">' : '<div class="col-md-12">';
						?>
					  <div class="col-12 col-sm-6 col-md-6 mb-3">
							<div class="info-box p-2 h-100">
								<?=$imgString; ?>
								<div class="info-box-content">
									<span class="info-box-text"><strong><?=$member['Applicant_Name'];?></strong></span>
									<span class="info-box-text"><strong><?=$member['Organization_Name'];?></strong></span>
									<span class="info-box-text"><?=$member['User_Role'];?></span>
                                    <span class="info-box-text">
										<i class="fa fa-phone"></i> &nbsp; <?=$member['Mobile_No'];?>
									</span>
									<span class="info-box-text">
										<i class="fa fa-envelope"> &nbsp;</i><?=$member['Email_ID'];?>
									</span>
								</div>
								</div>
							</div>	
						</div>
					  	<?
						}
					  ?>
						 </div>
				  </div>
				</div>
			  </div>
			  
			  
			 
			
				<?
			}
		}
		echo '</div>';
		
	
	}
		else {
			showLogoutMsg( false , is_user_logged_in());
		}
    } else {
        showLogoutMsg( isset ( $menuCheck[$pageId] ), is_user_logged_in());
    }
}










add_shortcode( "ConnectsPage" , "ConnectsPage" );
function ConnectsPage($atts)
{	 
	$siteTitle = get_site_url();
	$user = wp_get_current_user();
    $role = (array) $user->roles;
		
    if (  is_user_logged_in() )
    { 

		global $wpdb;
		$latLongStr = '';
		$latLongArr = array();
		$latLong = $wpdb->get_results("select ID,Email_ID, Mobile_No, Latitude, Longitude,Applicant_Name from {$wpdb->prefix}users where Latitude != '' AND Longitude != '' AND User_Role != 'administrator' ","ARRAY_A");
		$i = 0;
		foreach( $latLong as $key => $value )
		{
			
			$latLongArr[$value['ID']]['Latitude'] = $value['Latitude'];
			$latLongArr[$value['ID']]['Longitude'] = $value['Longitude'];
			$latLongArr[$value['ID']]['Applicant_Name'] = $value['Applicant_Name'];
			$latLongArr[$value['ID']]['Email_ID'] = $value['Email_ID'];
			$latLongArr[$value['ID']]['Mobile_No'] = $value['Mobile_No'];
			
			if ( $i != 0 )  $latLongStr .= "," ;
			$latLongStr .= "['{$value['Latitude']}','{$value['Longitude']}','{$value['Applicant_Name']}']" ;
			$i++;			
		}
		echo '<a class="btn mb-2 btn-default" href="'.$siteTitle.'/connect-list/" > + View List Connect</a>';
		
		echo "<script>let latLongStr = [{$latLongStr}];</script>";
		?>

		
		<div id="map" style="width:100%;height:400px" >
		<input id="latitude" type="hidden" value="" >
		<input id="longitude" type="hidden" value="">
		
		</div>
		<script>
		var _rulesString = {};
		var _messagesString = {};
		window.latitude='';
		window.longitude='';
		function initMap() 
		{
			
		
			if (navigator.geolocation) 
			{
				navigator.geolocation.getCurrentPosition(function(position)
				{ 
					window.latitude = position.coords.latitude;
					window.longitude = position.coords.longitude;	
					alert(window.longitude);
					var currentLat = window.latitude;
					var currentLong = window.longitude;
					var locations = [];
					for(var i=0; i < latLongStr.length; i++)
					{	
						var thisCord = latLongStr[i];
						
						var distCoord = getDistance(currentLat,currentLong,thisCord[0],thisCord[1]);
						var valueToPush = new Array();
						valueToPush[0] = thisCord[2];
						valueToPush[1] = thisCord[0];
						valueToPush[2] = thisCord[1];

						locations.push(valueToPush);
					}
				

					var map = new google.maps.Map(document.getElementById('map'), {
					  zoom: 9,
					  center: new google.maps.LatLng(currentLat,currentLong ),
					  mapTypeId: google.maps.MapTypeId.ROADMAP
					});
			
					var infowindow = new google.maps.InfoWindow();

					var marker, i;
					var i = 1000000;
					 marker = new google.maps.Marker({
						position: new google.maps.LatLng( currentLat , currentLong),
						map: map,
						 icon: {
							path: google.maps.SymbolPath.CIRCLE,
							scale: 10,
							fillColor: "#33BBFF",
							fillOpacity: 0.5,
							strokeWeight: 2,
							strokeColor: '#3399FF',
						},
					  });

					  google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
						  infowindow.setContent(locations[i][0]);
						  infowindow.open(map, marker);
						}
					  })(marker, i));
					  
					var i;  
					for (i = 0; i < locations.length; i++) {  
					  marker = new google.maps.Marker({
						position: new google.maps.LatLng( locations[i][1] , locations[i][2]),
						map: map
					  });

					  google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
						  infowindow.setContent(locations[i][0]);
						  infowindow.open(map, marker);
						}
					  })(marker, i));
					}
				});
			} 
			else 
			{
				alert( "Geolocation is not supported by this browser.");
			}
		
		
			
			
			
		}
		
		function Deg2Rad( deg ) {
       return deg * Math.PI / 180;
    }

    function getDistance(lat_from, long_from, lat_to, long_to)
    {       
        //Toronto Latitude  43.74 and longitude  -79.37
        //Vancouver Latitude  49.25 and longitude  -123.12
        lat1 = Deg2Rad(lat_from); 
        lat2 = Deg2Rad(lat_to); 
        lon1 = Deg2Rad(long_from); 
        lon2 = Deg2Rad(long_to);
        latDiff = lat2-lat1;
        lonDiff = lon2-lon1;
        var R = 6371000; // metres
        var φ1 = lat1;
        var φ2 = lat2;
        var Δφ = latDiff;
        var Δλ = lonDiff;

        var a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ/2) * Math.sin(Δλ/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

        var d = R * c;

        var dist = Math.acos( Math.sin(φ1)*Math.sin(φ2) + Math.cos(φ1)*Math.cos(φ2) * Math.cos(Δλ) ) * R;
		return dist;
    }  
		
		</script>
		<?php
	}
}
add_shortcode( "ConnectListPage" , "ConnectListPage" );
function ConnectListPage($atts)
{	 
	$user = wp_get_current_user();
    $role = (array) $user->roles;
		
    if (  is_user_logged_in() )
    { 

		if( count($_POST) > 1 )
		{
			//print_r($_POST);
			global $wpdb;
			$latLongArr = array();
			$thisTbl = "{$wpdb->prefix}users";
			$chapterJoin = "{$wpdb->prefix}chapter";
			
			$catExt = '';
			if( isset( $_POST['search'] ) && isset( $_POST['search_text'] )   )
			{
				$catExt .= " AND ( {$thisTbl}.Organization_Name LIKE '%{$_POST['search_text']}%' OR 
								   {$thisTbl}.Applicant_Name LIKE '%{$_POST['search_text']}%'  OR 
								   {$thisTbl}.User_Role LIKE '%{$_POST['search_text']}%'  OR 
								   {$thisTbl}.Mobile_No LIKE '%{$_POST['search_text']}%'  OR
								   {$thisTbl}.Email_ID LIKE '%{$_POST['search_text']}%' 
								   
								   )";
			}
			
		
			$latLong = $wpdb->get_results("select 
												{$thisTbl}.ID,
												{$thisTbl}.userProfile_1,
												{$thisTbl}.Designation,
												{$thisTbl}.User_Role,
												{$thisTbl}.Email_ID, 
												{$thisTbl}.Mobile_No, 
												{$thisTbl}.Latitude, 
												{$thisTbl}.Longitude,
												{$thisTbl}.Organization_Name,
												{$thisTbl}.Applicant_Name
											from 
												{$thisTbl} 
											where 
												Latitude != '' AND 
												Longitude != '' AND isTrash =0 AND User_role != 'administrator' {$catExt} ","ARRAY_A");

			foreach( $latLong as $key => $value )
			{
				$distance = haversineGreatCircleDistance($_POST['latitude'], $_POST['longitude'],$value['Latitude'], $value['Longitude'] );
				$distance = round( $distance / 1000, 3);
				$latLongArr[$value['ID']]['userProfile_1'] = $value['userProfile_1'];
				$latLongArr[$value['ID']]['Email_ID'] = $value['Email_ID'];
				$latLongArr[$value['ID']]['Mobile_No'] = $value['Mobile_No'];
				$latLongArr[$value['ID']]['User_Role'] = $value['User_Role'];
				$latLongArr[$value['ID']]['Applicant_Name'] = $value['Applicant_Name'];
				$latLongArr[$value['ID']]['Organization_Name'] = $value['Organization_Name'];
				$latLongArr[$value['ID']]['Designation'] = $value['Designation'];
				$latLongArr[$value['ID']]['Distance'] = $distance;
				
			}
			usort($latLongArr, function($a, $b) {
				return $a['Distance'] - $b['Distance'];
			});
			
			echo "<form autocomplete='off' method='post'>";
			echo '<input id="latitude" name="latitude" type="hidden" value="" >
			 <input id="longitude" type="hidden"  name="longitude" value="">';
		echo '<div class="row">';
			echo '<div class="col-6 mb-2">';
				echo '<input type="text" class="form-control" placeholder="enter search text" name="search_text" value="" />';
			echo '</div>';
			echo '<div class="col-6 mb-2">';
				echo '<input type="submit" class="form-control" name="search" value="Search" />';
			echo '</div>';
		echo '</div>';
		echo '</form>';
		echo '<script>if (navigator.geolocation) 
			{
				navigator.geolocation.getCurrentPosition(function(position)
				{ 
					$("#latitude").val(position.coords.latitude);
					$("#longitude").val(position.coords.longitude);	
					$("#listMap").submit();
				});
			} 
			else 
			{
				alert( "Geolocation is not supported by this browser.");
			}</script>';
			echo '<div class="row">';
			if( count( $latLongArr) > 0 )
			{
				foreach($latLongArr as $key => $value)
				{
					$imgString = strlen( $value['userProfile_1'] ) > 5 ? '<div class="col-md-4 my-auto">
									<img class="rounded  img-fluid" src="'.$value['userProfile_1'].'">
								</div>
								<div class="col-md-8">' : '<div class="col-md-12">';
								
					echo '<div class="col-12 col-sm-6 col-md-6 mb-3">
							<div class="info-box p-2 h-100">
									'.$imgString.'
										<div class="info-box-content">
									<span class="info-box-text"><strong>'.$value['Applicant_Name'].'</strong></span>
									<span class="info-box-text"><strong>'.$value['Organization_Name'].'</strong></span>
									<span class="info-box-text">'.$value['User_Role'].'</span>
                                    <span class="info-box-text"><i class="fa fa-phone"></i> &nbsp; '.$value['Mobile_No'].'</span>
									<span class="info-box-text"><i class="fa fa-envelope"> &nbsp;</i>'.$value['Email_ID'].'</span>
									
									<span class="info-box-text text-right">'.$value['Distance'].' Kms Away</span>
										</div>
									</div>	
							</div>
						</div>';
				}			
			}
			else{
				$classUI = new classUI();
				echo '<div class="col-12">';
				echo $classUI->noDataFound("No Data Found...");
				echo '</div>';
			}
			echo '</div>';
		}
		else{
		echo '<form id="listMap" method="post" ><input id="latitude" name="latitude" type="hidden" value="" >
			 <input id="longitude" type="hidden"  name="longitude" value=""></form>';
		echo '<script>if (navigator.geolocation) 
			{
				navigator.geolocation.getCurrentPosition(function(position)
				{ 
					$("#latitude").val(position.coords.latitude);
					$("#longitude").val(position.coords.longitude);	
					$("#listMap").submit();
				});
			} 
			else 
			{
				alert( "Geolocation is not supported by this browser.");
			}</script>';	
		}			
	
	}
	
}

function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}

add_shortcode("TenderPage", "TenderPage");
function TenderPage()
{
	global $wpdb;
	$classUI = new classUI();
	$tendersTable = "{$wpdb->prefix}tenders";
	$siteTitle = get_site_url();
	$tenders = $wpdb->get_results( "SELECT {$tendersTable}.*,
										DATE_FORMAT( {$tendersTable}.Start_Date , '%d-%m-%Y') as startDate,
										DATE_FORMAT( {$tendersTable}.End_Date , '%d-%m-%Y') as endDate
										FROM {$tendersTable}
										WHERE {$tendersTable}.isTrash = 0  
										ORDER BY {$tendersTable}.Start_Date", "ARRAY_A");
	if( count ( $tenders ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $tenders as $tender )
		{
			$website = $tender['Website'] != '' ? '<h5 style="float:none" class="mt-2 card-title text-center">
									<i class="fa fa-globe"></i> '.$tender['Website'].'</h5>' : '';
			$endDate = $tender['endDate'] != '' &&  $tender['endDate'] != '00-00-0000'  ? 
					' & End Date - '.$tender['endDate']  : '';
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$tender['Tender_Name']; ?>
							</b>
    					</div>
						<div class="card-body  text-center">
							<!-- <img style="width:100%" src="<?=$tender['userProfile_1'] ;?>" /> -->
								<?=$website ;?>
								<p class="card-text mt-2 text-center">Start Date - <?=$tender['startDate'] ;?> <?=$endDate ;?></p>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Tenders found");
	}
	
}

add_shortcode("ForeignTrPage", "ForeignTrPage");
function ForeignTrPage()
{
	global $wpdb;
	$classUI = new classUI();
	$tradeTable = "{$wpdb->prefix}foreign_trade";
	$siteTitle = get_site_url();
	$trades = $wpdb->get_results( "SELECT {$tradeTable}.*
										FROM {$tradeTable}
										WHERE {$tradeTable}.isTrash = 0  
										ORDER BY {$tradeTable}.Consulate_Name", "ARRAY_A");
	if( count ( $trades ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $trades as $trade )
		{
			$website = $trade['Website'] != '' ? '<h5 style="float:none" class="mt-2 card-title text-center">
									<i class="fa fa-globe"></i> '.$trade['Website'].'</h5>' : '';
			$address = $trade['Address'] != ''   ? 
					'<p class="card-text p-0 mt-2 text-center"><i class="fa fa-home"></i> '.$trade['Address'] .'</p>' : '';
			$contact = $trade['Contact_Details'] != ''   ? 
					'<p class="card-text  p-0 text-center"><i class="fa fa-phone"></i> '.$trade['Contact_Details'] .'</p>' : '';
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$trade['Consulate_Name']; ?>
							</b>
    					</div>
						<div class="card-body  text-center">
							<!-- <img style="width:100%" src="<?=$trade['userProfile_1'] ;?>" /> -->
								<?=$website ;?>
								<?=$address ;?>
								<?=$contact ;?>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Foreign Tradess found");
	}
}

add_shortcode("BusinessExPage", "BusinessExPage");
function BusinessExPage()
{
	global $wpdb;
	$classUI = new classUI();
	$exchangeTable = "{$wpdb->prefix}business_exchange";
	$siteTitle = get_site_url();
	$exchanges = $wpdb->get_results( "SELECT {$exchangeTable}.*,
										DATE_FORMAT( {$exchangeTable}.Exchange_Date , '%d-%m-%Y' ) as exchangeDate
										FROM {$exchangeTable}
										WHERE {$exchangeTable}.isTrash = 0  
										ORDER BY {$exchangeTable}.Exchange_Date", "ARRAY_A");
	if( count ( $exchanges ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $exchanges as $exchang )
		{
			
			$product = $exchang['Product_Details'] != ''   ? 
					'<p class="card-text p-0 mt-2 text-center">'.$exchang['Product_Details'] .'</p>' : '';
			$contact = $exchang['Contact_Details'] != ''   ? 
					'<p class="card-text  p-0 text-center"><i class="fa fa-phone"></i> '.$exchang['Contact_Details'] .'</p>' : '';
			$profile = $exchang['Company_Profile'] != ''   ? 
					'<p class="card-text  p-0 text-center">'.$exchang['Company_Profile'] .'</p>' : '';
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$exchang['Product']; ?>
							</b>
    					</div>
						<div class="card-header text-center">
								<?=$exchang['Product_Details']; ?>
    					</div>
						<div class="card-body  text-center">
							<!-- <img style="width:100%" src="<?=$exchang['userProfile_1'] ;?>" /> -->
								<?=$profile ;?>
								<?=$contact ;?>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Business Exchanges found");
	}
}


add_shortcode("BusinessOpportunityPage", "BusinessOpportunityPage");
function BusinessOpportunityPage()
{
	global $wpdb;
	$classUI = new classUI();
	$opportunityTable = "{$wpdb->prefix}business_opportunities";
	$siteTitle = get_site_url();
	$opportunities = $wpdb->get_results( "SELECT {$opportunityTable}.*
										FROM {$opportunityTable}
										WHERE {$opportunityTable}.isTrash = 0  
										ORDER BY {$opportunityTable}.Company_Name", "ARRAY_A");
	if( count ( $opportunities ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $opportunities as $opportunity )
		{
			
			
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$opportunity['Company_Name']; ?>
							</b>
    					</div>
						<div class="card-header text-center">
							<b>
								<?=$opportunity['Opportunity_Details']; ?>
							</b>
    					</div>
						<div class="card-body  text-center">
							<!-- <img style="width:100%" src="<?=$opportunity['userProfile_1'] ;?>" /> -->
							<p class="card-text  p-0 text-center"><i class="fa fas-home"></i><?=$opportunity['Contact_Details'] ; ?></p>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Business opportunities found");
	}
}


add_shortcode("JobPage", "JobPage");
function JobPage()
{
	global $wpdb;
	$classUI = new classUI();
	$jobTable = "{$wpdb->prefix}job_posts";
	$siteTitle = get_site_url();
	$jobs = $wpdb->get_results( "SELECT {$jobTable}.*,
										DATE_FORMAT( {$jobTable}.Start_Date , '%d-%m-%Y' ) as startDate,
										DATE_FORMAT( {$jobTable}.End_Date , '%d-%m-%Y' ) as endDate
										FROM {$jobTable}
										WHERE {$jobTable}.isTrash = 0  
										ORDER BY {$jobTable}.Start_Date", "ARRAY_A");
	if( count ( $jobs ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $jobs as $job )
		{
			
			
			
			$endDate = $job['endDate'] != '' &&  $job['endDate'] != '00-00-0000'  ? 
					' & End Date - '.$job['endDate']  : '';
			
		?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card  border border-primary shadow">
						<div class="card-header text-center">
							<b>
								<?=$job['Job_Title']; ?>
							</b>
    					</div>
						<div class="card-header text-center">
								<?=$job['Company_Name']; ?>
    					</div>
						<div class="card-header text-center">
								No Of Opening :- <?=$job['Opening']; ?>
    					</div>
						
						<div class="card-body  text-center">
							<!-- <img style="width:100%" src="<?=$job['userProfile_1'] ;?>" /> -->
								
							<p class="card-text mt-2 text-center"><?=$job['Job_Description'] ;?></p>
							<p class="card-text  p-0 text-center"><i class="fa fa-phone"></i>
								<?=$job['Contact_Details'];?></p>
							<p class="card-text mt-2 text-right">Start Date - <?=$job['startDate'] ;?><?=$endDate ;?></p>
						</div>
					</div>	
				</div>
		<?
		}
		echo '</div>';
	}else 
	{
		$classUI->noDataFound("No Job postings found");
	}
}


add_shortcode("VizagRegionPage", "VizagRegionPage");
function VizagRegionPage()
{
	global $wpdb;
	$designationTable = "{$wpdb->prefix}designation";
	$userTable = "{$wpdb->prefix}users";
	$siteTitle = get_site_url();
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Designation as designationName
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Designation  )
										WHERE {$userTable}.isTrash = 0  AND 
											  {$userTable}.Page_Name  like '%Vizag Region%'  
										ORDER BY {$userTable}.Applicant_Name", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card shadow">
						<div class="card-body  text-center">
							<img style="width:60%" src="<?=$member['userProfile_1'] ;?>" />
							<div class="border">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['designationName'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
	
}



add_shortcode("RaayalaseemaRegionPage", "RaayalaseemaRegionPage");
function RaayalaseemaRegionPage()
{
	global $wpdb;
	$designationTable = "{$wpdb->prefix}designation";
	$userTable = "{$wpdb->prefix}users";
	$siteTitle = get_site_url();
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Designation as designationName
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Designation  )
										WHERE {$userTable}.isTrash = 0  AND 
											  {$userTable}.Page_Name  LIKE '%Raayalaseema Region%'  
										ORDER BY {$userTable}.Applicant_Name", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card shadow">
						<div class="card-body  text-center">
							<img style="width:60%" src="<?=$member['userProfile_1'] ;?>" />
							<div class="border">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['designationName'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
	
}


add_shortcode("CentralAndhraRegionPage", "CentralAndhraRegionPage");
function CentralAndhraRegionPage()
{
	global $wpdb;
	$designationTable = "{$wpdb->prefix}designation";
	$userTable = "{$wpdb->prefix}users";
	$siteTitle = get_site_url();
	$executiveMembers = $wpdb->get_results( "SELECT {$userTable}.*,
													{$designationTable}.Designation as designationName
										FROM {$userTable}
										LEFT JOIN {$designationTable} ON 
												( {$designationTable}.ID = {$userTable}.Designation  )
										WHERE {$userTable}.isTrash = 0  AND 
											  {$userTable}.Page_Name  LIKE '%Central Andhra Region%'  
										ORDER BY {$userTable}.Applicant_Name", "ARRAY_A");
	if( count ( $executiveMembers ) > 0 )
	{
		
		
		echo '<div class="row mt-2">';
		foreach( $executiveMembers as $member )
		{
			?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card shadow">
						<div class="card-body  text-center">
							<img style="width:60%" src="<?=$member['userProfile_1'] ;?>" />
							<div class="border">
								<h5 style="float:none" class="mt-2 card-title text-center">
									<b><?=$member['Applicant_Name'] ;?></b></h5>
								<p class="card-text mt-2 text-center"><?=$member['designationName'] ;?></p>
							</div>
						</div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
	
}





add_shortcode("InternetPage", "InternetPage");
function InternetPage()
{
	global $wpdb;
	$upcomingEvents = $wpdb->get_results( "SELECT Release_Title,
												  Description,
												  userProfile_1,
											   DATE_FORMAT(Release_Date, '%W %d,%b-%Y') as releaseDate 
										FROM {$wpdb->prefix}tv_release 
										WHERE isTrash = 0   ORDER BY Release_Date DESC", "ARRAY_A");
	if( count ( $upcomingEvents ) > 0 )
	{
		echo '<div class="row mb-2">';
		foreach( $upcomingEvents as $upcomingEvent )
		{
			?>
				<div class="col-sm-12 col-md-6 col-lg-4 p-2">
					<div class="card shadow">
						<div class="card-body">
7							<div style="position: absolute;top: 20px;" class="btn btn-primary card-article text-center">
								<?=$upcomingEvent['releaseDate']; ?>
							</div>
							<div class="image-box">
								<iframe class="img-fluid" src="<?=$upcomingEvent['userProfile_1'] ;?>">
								</iframe>
								
                                </div>
							<h5 class="card-title"><b><?=$upcomingEvent['Release_Title'] ;?></b></h5>
							<p class="card-text"><?=$upcomingEvent['Description'] ;?></p>
						  </div>
					</div>	
				</div>
			<?
		}
		echo '</div>';
	}
}



add_shortcode("MagazineViewPage", "MagazineViewPage");
function MagazineViewPage()
{
	global $wpdb;
	$site_url = get_site_url();
	
		//echo do_shortcode('[dflip id="3824" ][/dflip]');
		//echo do_shortcode('[dflip id="3828" ][/dflip]');
	if( isset( $_GET['magazine'] ) )
	{
		$magazineData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}magazine 
										WHERE isTrash = 0 AND ID='{$_GET['magazine']}'", "ARRAY_A");
		 if( count ( $magazineData ) > 0 )
		 {
			 $magazine = $magazineData[0];
			 ?>
<div class="_df_book df-lite" id="df_<?=$_GET['magazine'] ; ?>"  data-title="" _slug="<?=$_GET['magazine'] ; ?>" wpoptions="true" thumbtype="bg" >
   <p class="df-raw-loading">Please wait while flipbook is loading.</p>
</div>
  <!-- <div class="sample-container" style="height:100vh"></div> -->

<script class="df-shortcode-script" type="application/javascript">window.option_df_<?=$_GET['magazine'] ; ?> = {"outline":[],"forceFit":"true","autoEnableOutline":"false","autoEnableThumbnail":"false","overwritePDFOutline":"false","direction":"1","pageSize":"0","source":"<?=$magazine['PDF_1'] ; ?>","wpOptions":"true"}; if(window.DFLIP && window.DFLIP.parseBooks){window.DFLIP.parseBooks();}</script>
			<?
		 }
		?>
<script type="text/javascript">
	var _rulesString = {};
		var _messagesString = {};

</script>
<?
		
	}else{
	
		
		$magazineData = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}magazine 
										WHERE isTrash = 0 ORDER BY View_Order", "ARRAY_A");
		?>
		<div class="row">
	
			<?
				foreach( $magazineData as $magazine )
				{
					//print_r($magazine);
			?>
        	<div class="col-sm-12 col-md-6 p-4">
			    <div class="card shadow" style="position:relative;">
					<a href="<?=$site_url; ?>/magazine-view/?magazine=<?=$magazine['ID']; ?>" target="_blank">
						<img class="img-fluid" src="<?=$magazine['userProfile_1']; ?>">
					</a>
					<div style="position:absolute;bottom:10px;left:10px;" 
						 class="card_category">
						<?=$magazine['Magazine_Name']; ?>
					</div>
				</div>
             </div>	
			<? } 
			?>
              
		</div>
		<script type="text/javascript">
		var _rulesString = {};
		var _messagesString = {};
	
</script>

	<?
	}
	
}

add_shortcode("BecomeaMemberPage", "BecomeaMemberPage");
function BecomeaMemberPage()
{
	echo '<div class="wrap__about-us mt-4">
                <p>Please click on the following links to view and download the membership application form.</p>
                <ol>
                    <li><a href="https://apchambers.in/Generalll.pdf" target="_blank">"GENERAL APPLICATION FORM DOWNLOAD"</a></li>
                    <li><a href="https://apchambers.in/affiliateee.pdf" target="_blank">"AFFILIATE APPLICATION FORM DOWNLOAD"</a></li>
                </ol>
				<h3><b>Or</b></h3>
 				<p><a href="'.site_url() .'/user-registration/" class="btn btn-primary text-white">Click here to register</a></p>

          </div>';
}
	
add_shortcode("SpecialBenfitsPage", "SpecialBenfitsPage");
function SpecialBenfitsPage()
{
	echo '<div class="wrap__about-us mt-4">
                <p>1. To have first hand information on the business opportunities in AP.</p>
                <p>2. Latest developments in the state of AP.</p>
                <p>3. Business delegations to AP.</p>
                <p>4. Platform to enhance business and to develop newer markets in AP</p>
                <p>5. Latest update on Govt Notifications, various policies and amendments etc.</p>
                <p>6. Information exchange.</p>
                <p>7. Latest information about direct and indirect taxes.</p>
                <p>8. Excellent network with all the leading industrialists / traders of the state.</p>
                <p>9. Awards and Recognitions.</p>
                <p>10. Meeting venue with suitable facility to hold meetings/seminars for 20 to 50 members at concessional rate for members in India when you come here.</p>
                </div>';
}
add_shortcode("CeratificateofOriginPage", "CeratificateofOriginPage");
function CeratificateofOriginPage()
{
	echo '<div class="wrap__about-us mt-4">
                <p style="text-align:justify;">A Certificate of Origin (often a abbreviated to C/O or CoO) is a document used in international trade. It is a printed form completed by the exporter or an agent and certified by an issuing body, attesting that the goods in a particular shipment have been wholly produced manufactured or processed in a particular country. It is generally an integrally part of export documents. Determining the origin of product is important because it is key basis for applying tariff and other important criteria. The instrument establishes evidence on the origin of goods imported into any country.</p>
                <p>There are two types of Certificate of Origin.</p>
                <p><b>1) Preferential</b></p>
                <p><b>2) Non-preferential</b></p>

                <p style="text-align:justify;">AP Chambers is authorized by the Directorate General of Foreign Trade (DGFT) to issue Non-preferential “Certificate of Origin” to exporters. Chambers has been efficiently providing this service to all its members at a nominal cost. The exporter seeking Certificates of Origin has to become a member of the Chambers (if not a member) and execute an indemnity bond in favor of the Chambers before they can avail of this service for the first time. The exporter can get the Certificate of Origin signed by one of the authorized signatories from the Chambers for a nominal service charge. </p>
				<p>AP Chambers also issues CoOs online through DGFT’s Common Digital Platform. Members can apply CoOs online through the DGFT platform and Chambers will process and approve the CoOs completely online. Please download the following document to know how to register and apply for CoOs on Common Digital Platform.
				</p>
				<p><b><a href="https://apchambers.in/Exporter-Manual.pdf" target="_blank"><font color = "red">"CLICK HERE TO DOWNLOAD THE DOCUMENT ON HOW TO REGISTER AND APPLY  COOS  ON COMMON DIGITAL PLATFORM"</font></a></b></p>
                </div>';
}

add_shortcode("StateLevelCommittiePage", "StateLevelCommittiePage");
function StateLevelCommittiePage()
{
	// Add CSS for consistent image sizing
	echo '<style>
		.committee-chair-img {
			width: 100%;
			height: 220px;
			object-fit: contain;
			object-position: center;
			background-color: #f8f9fa;
		}
		.committee-chair-card {
			height: 100%;
		}
		.committee-chair-card-body {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.committee-chair-img-container {
			flex-shrink: 0;
		}
		.committee-chair-info {
			flex-grow: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
	</style>';
	
	echo '<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
				<strong><u><span style="font-family:Tahoma; ">STATE LEVEL COMMITTEES\' OFFICE BEARERS FOR 2025-27</span></u></strong>
			</p>';
	
	$defaultImage = get_site_url().'/default-user.png';
	
	// Committee data for 2025-27
	$committees = array(
		array(
			'name' => 'Affiliates Council',
			'chair_name' => 'Dr. S Panduranga Prasad',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/drspandurangaprasad.jpg'
		),
		array(
			'name' => 'Energy',
			'chair_name' => 'Sri P Koti Rao',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/kotarao.jpg'
		),
		array(
			'name' => 'Tourism',
			'chair_name' => 'Sri Ramisetty Veeraswamy',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/ramisetty_veeraswamy.jpg'
		),
		array(
			'name' => 'Retail',
			'chair_name' => 'Sri Chukkapalli Sanketh',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/chukkapalli_sanketh.jpg'
		),
		array(
			'name' => 'International Trade',
			'chair_name' => 'Dr Murty Indrakanti',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/murtyindrakanti.jpg'
		),
		array(
			'name' => 'Taxation (GST)',
			'chair_name' => 'CA Ramakrishna Sangu',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/ramakrishnasangu.jpg'
		),
		array(
			'name' => 'Food processing',
			'chair_name' => 'Sri Krishna Prasad',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/krishna_prasad.jpg'
		),
		array(
			'name' => 'Aqua & Marine',
			'chair_name' => 'Sri V Sambasiva Rao',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/sambasivarao.jpg'
		),
		array(
			'name' => 'Programmes (Events)',
			'chair_name' => 'Smt. D Aparna',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/daparna.jpg'
		),
		array(
			'name' => 'Banking & Finance',
			'chair_name' => 'Sri A Satyanarayana',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/satyanarayana.jpg'
		),
		array(
			'name' => 'Education',
			'chair_name' => 'Smt. Suma Bindu Atluri',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/sumabindu.jpg'
		),
		array(
			'name' => 'Skill Development',
			'chair_name' => 'Smt. Radhika',
			'chair_designation' => 'Chairman',
			'chair_image' => 'https://apchambers.in/wp-content/uploads/2025/10/radhika.jpg'
		)
	);
	
	// Display all committees in card layout
	echo '<div class="row mt-2">';
	foreach($committees as $committee) {
		$profileImage = (!empty($committee['chair_image'])) ? $committee['chair_image'] : $defaultImage;
		?>
		<div class="col-sm-6 col-md-4 col-lg-3 p-2">
			<div class="card shadow committee-chair-card">
				<div class="card-body text-center committee-chair-card-body">
					<div class="committee-chair-img-container">
						<img class="committee-chair-img" src="<?=$profileImage;?>" alt="<?=$committee['chair_name'];?>" />
					</div>
					<div class="border committee-chair-info">
						<h6 class="mt-2 mb-1"><strong><?=$committee['name'];?></strong></h6>
						<h5 class="mt-2 card-title text-center">
							<b><?=$committee['chair_name'];?></b>
						</h5>
						<p class="card-text mt-1 text-center text-muted"><?=$committee['chair_designation'];?></p>
					</div>
				</div>
			</div>	
		</div>
		<?php
	}
	echo '</div>';
	
	echo '<div class="team-member row mt-4">                   
                    <div class="col-md-12">
                        <h3>Roles of State-level Committees</h3>
                        <ol>
                            <li>To identify the problems in the industry.</li>
                            <li>Advice the Board on issues pertaining to the Industrial / Business segment of the reference.</li>
                            <li>To develop expert opinion in Andhra Pradesh on various issues of the Particular industry to achieve growth in the current Scenario.</li>
                            <li>The committees will give suggestions to the board for conducting different programmes in all sectors.</li>
                            <li>Help industry to empower them with Best Practises& innovative technologies focusing on the overall development of Andhra Pradesh.</li>
                            <li>Arranging meetings with members for any controversial problems related to Government orders, GO\'s, and any other issues of Trade, Business, and Industrial sectors.</li>
                            <li>Informative meetings for the members who are interested to set up the new Industry.</li>
                            <li>Organizing conferences/ Seminars / Workshops related to the particular Industry.</li>
                            <li>Interactive Sessions with respective Government and other Bodies / Agencies Departments.</li>
                        </ol>
                    </div>
                </div>             
                </div>';
}


add_shortcode("BenfitToMembersPage", "BenfitToMembersPage");
function BenfitToMembersPage()
{
	echo '<div class="wrap__about-us mt-4">
                <p style="text-align:justify;">1. Platform to enhance business and to develop newer markets.</p>                
                <p>2. Government notifications, policies, amendments etc.</p>
                <p>3. Meeting with overseas/Domestic business delegations.</p>
                <p>4. Meeting with ministers and senior bureaucrats.</p>
                <p>5. Seminars/Workshops/conferences/Trainings.</p>
                <p>6. Networking across and with in industry.</p>
                <p>7. The opportunity of being part of any of the expert committee subject to the suitability of the member.</p>
                <p>8. Information exchange.</p>
                <p>9. Authorized to issuing of Certificate of Origin(C/O, COO) to the exporters.</p>
                <p>10. VISA Recommendation letters to the Business groups for promotion.</p>
                <p>11. Chambers can Issue the authorization letters to promote their activities in overseas countries and recommend to enrolment as member in various trade and industrial association in abroad.</p>
                <p>12. House Magazine name as “Andhra Chamber Bulletin” Publish in every month, which contains the Chambers activates across the state with latest topics, GO’s, Circulars related to trade and industry to keep abreast of members.</p>
                <p>13. Availment of concession free in participation seminars, events, trade fairs, exhibitions and business delegation to go abroad.</p>
                <p>14. Create sub –Committee as sector wise and put forward various hurdles faced by the industry before Govt to resolve the issue and keep update the latest information to the members.</p>
                <p>15. On day to day basis AP Chambers impart the latest updates relating trade, service and industry through social media to the members.</p>
                </div>';
	
}
add_shortcode("MemberShipPage", "MemberShipPage");
function MemberShipPage()
{
	echo '<div class="wrap__about-us mt-4">
                    <p><b> Membership Categories:</b> There are two broad categories of members</p>                
                    <p class="pl-2"><b>1. General Members</b></p>
                    <p class="pl-2">The category of “General Members” shall be eligible to be invited as also participate and vote at any General or other meeting of Andhra Pradesh Chambers. There are sub-categories in General Members as under:</p>
                    <p class="pl-4" ><b>i)	Affiliates</b></p>
                    <p class="pl-4"><b>ii)	Corporate</b></p>
                    <p class="pl-4"><b>iii)	Active</b></p>
                    <p class="pl-2"><b>2. Formal Members</b></p>
                    <p class="pl-2" style="text-align:justify;">The category of “Formal Members” shall neither be eligible to be invited nor can they participate in General body meeting of Andhra Pradesh Chambers. They can be invited for specific meetings as decided by Board. There are sub-categories in Formal Members as under:</p>
                    <p class="pl-4"><b>i)	Associate</b></p>
                    <p class="pl-4"><b>ii)	Student</b></p>
                    <p class="pl-4"><b>iii)	Special</b> </p>
                    <p class="pl-4"><b>iv)	Honorary</b></p>
                    <p><b>Eligibility:</b></p>
                    <p class="pl-2">(i) Any registered association or other legal entity in Andhra Pradesh truly representative of any primary economic activity of its members and having an abiding interest in the objectives of Andhra Pradesh Chambers shall be eligible to apply for membership under “Affiliate” category.</p>
                    <p class="pl-2">(ii) Any person, Company, Corporation, Firm, Concern or other legal business entity in India actively engaged in any economic activity and having an abiding interest in the objectives of Andhra Pradesh Chambers shall be eligible to apply for membership under “Corporate” or “Active” or “Associate” category.</p>
                    <p class="pl-2">(iii) Any person, studying graduation or post-graduation in India or elsewhere and having an interest in the objectives of Andhra Pradesh shall be eligible to apply for membership under “Student” category.</p>
                    <p class="pl-2">(iv) The Board of Directors shall confer (or revoke) “Honorary” membership by a majority vote to individuals for distinction in public affairs who shall have all the privileges of members except the right to vote and shall be exempt from payment of any fees to Andhra Pradesh Chambers.</p>
                    <p><b>Subscriptions:</b></p>
                    <p>Membership Admission Fee and Annual Subscriptions shall be at such rate or rates, schedule or formula, terms and conditions as may, from time to time, be prescribed by the Board and shall be payable in advance subject to the provisions of “Manual of Procedure on Membership.”</p>
                    <p><b>Voting:</b></p>
                    <p>In any proceeding in which voting by members is called for, each member in good standing from “General Members” category, i.e., Affiliate, Corporate and Active sub-categories shall be entitled to cast number of votes as per the “Manual Procedure” laid out from time to time and the same is applicable only for general Body Meetings as representative of the member. The “Formal Members” category, i.e., Associate, Student, Special and Honorary sub-categories shall not have any voting rights.</p>
                </div>';
}
	
add_shortcode("SecretariatPage", "SecretariatPage");
function SecretariatPage()
{
	echo '<div class="wrap__about-us mt-4">
                <div class="row">
                    <div class="col-12">
                    <h3><b>Secretariat</b></h3>                
                    <h5><b>Andhra Pradesh Chambers Of Commerce and Industry Federation</b></h5>
                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> #40-1-144, 3rd Floor, Corporate Buliding, Side of Chandana Grand, Old khandari Junction, M. G. Road, Vijayawada-520010, Andhra Pradesh.</p>
                    <p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:federation@apchamber.in">federation@apchamber.in</a></p>
                    </div>
                   
                </div>
                <div class="clearfix"></div>
                <h4><b>Vizag Regional Office</b></h4>
                <h4><b>Andhra Pradesh Chambers Of Commerce and Industry Federation</b></h4>
                <p><i class="fa fa-map-marker" aria-hidden="true"></i> #14-1-15/4, Sri Dasapalla Towers, 1st Floor, 1/5, Opp: Central Revenue Quarters, Nowroji Road, Maharanipeta , VISAKHAPATNAM-530002, Andhra Pradesh</p>
                <p><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:federationvisakha@apchamber.in">federationvisakha@apchamber.in</a></p>
                </div>';
}

add_shortcode("AffliateCousilPage", "AffliateCousilPage");
function AffliateCousilPage()
{
	echo '<div class="wrap__about-us mt-4">
                <p>The Affiliates Council (hereinafter referred to as the Council) is an advisory body for advocating, converging and mobilising the views of the business community in the State and the country to help the Board evolve and enforce the strategy, policy and programme of action. It is vested for these purposes with all the necessary powers and responsibilities.</p>
                
                <br>
                <br>
        

<table style="margin: auto; overflow-x: auto; width: 386px; text-align: center; border-collapse: collapse;">
            <tr>
                <th style="border: 1px solid black;"><b>SNo</b></th>
                <th style="border: 1px solid black;"><b>ASSOCIATION</b></th>
            </tr>
            <tr>
                <td style="border: 1px solid black;">1</td>
                <td style="border: 1px solid black;">FEDERATION OF ASSET FINANCE ASSOCIATIONS OF ANDHRA PRADESH</td>
            </tr>
    <tr>
        <td style="border: 1px solid black;">2</td>
        <td style="border: 1px solid black;">ANDHRA PRADESH FOOD PROCESSING INDUSTRIES FEDERATION  </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">3</td>
        <td style="border: 1px solid black;">ANDHRA MOTOR MERCHANTS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">4</td>
        <td style="border: 1px solid black;">CONFEDERATION OF WOMAN ENTREPRENEURS OF INDIA  (COWE)</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">5</td>
        <td style="border: 1px solid black;">ANDHRA PRADESH TEXTILE MILLS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">6</td>
        <td style="border: 1px solid black;">ASSOCIATION OF LADY ENTREPRENEURS OF ANDHRA PRADESH</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">7</td>
        <td style="border: 1px solid black;">ANDHRA PRADESH MSME INDUSTRIES ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">8</td>
        <td style="border: 1px solid black;">ANDHRA PRADESH FEDERATION OF PETROLEUM TRADERS </td>
    </tr>
   <tr>
        <td style="border: 1px solid black;">9</td>
        <td style="border: 1px solid black;">AP ADVENTURE ASSOCIATION (SCULLING AND ROWING ASSOCIATION OF AP)</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">10</td>
        <td style="border: 1px solid black;">ANDHRA STATE GUNNY TRADERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">11</td>
        <td style="border: 1px solid black;">ASSOCIATION OF FOOTWEAR MANUFACTURERS AND TRADERS OF ANDHRA PRADESH (AP FOOTWEAR ASSOCIATION)</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">12</td>
        <td style="border: 1px solid black;">ANDHRA PRADESH PETROLEUM TANK TRUCK OPERATORS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">13</td>
        <td style="border: 1px solid black;">NATIONAL REAL ESTATE DEVELOPMENT COUNCIL ANDHRA PRADESH </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">14</td>
        <td style="border: 1px solid black;">AP FERRO ALLOYS PRODUCERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">15</td>
        <td style="border: 1px solid black;">NAVYA ANDHRA PRADESH PLASTIC MANUFACTURURS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">16</td>
        <td style="border: 1px solid black;">AP COTTON ASSOCIATION TMC DIVISION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">17</td>
        <td style="border: 1px solid black;">AP HOTELS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">18</td>
        <td style="border: 1px solid black;">TOUR & TRAVELLERS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">19</td>
        <td style="border: 1px solid black;">ANDHRA PRADESH COTTON ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">20</td>
        <td style="border: 1px solid black;">HOTELS & RESTAURANTS ASSOCIATION OF ANDHRA PRADESH</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">21</td>
        <td style="border: 1px solid black;">AP LORRY OWNERS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">22</td>
        <td style="border: 1px solid black;">CHILLIES EXPORTERS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">23</td>
        <td style="border: 1px solid black;">THE IRON HARDWARE & PAINTS MERCHANT ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">24</td>
        <td style="border: 1px solid black;">THE INDIAN TOBACCO ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">25</td>
        <td style="border: 1px solid black;">VTPS FLYASH BRICKS MANUFACTURERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">26</td>
        <td style="border: 1px solid black;">HOTELS AND ALLIED INSTITUATION WELFARE ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">27</td>
        <td style="border: 1px solid black;">THE TIMBER MERCHANTS CHAMBER  ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">28</td>
        <td style="border: 1px solid black;">CREDAI VISAKHAPATNAM CHAPTER </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">29</td>
        <td style="border: 1px solid black;">THE GUDIVADA DIVISION RICEMILLERS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">30</td>
        <td style="border: 1px solid black;">ANANTAPUR DISTRICT CHAMBER OF COMMERCE & INDUSTRY </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">31</td>
        <td style="border: 1px solid black;">THE VIZAGAPATAM CHAMBER OF COMMERCE & INDUSTRY </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">32</td>
        <td style="border: 1px solid black;">VISAKHAPATNAM CUSTOMS BROKERS’ ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">33</td>
        <td style="border: 1px solid black;">THE VIZAGAPATAM CLOTH MERCHANTS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">34</td>
        <td style="border: 1px solid black;">VIZAG AREA FLYASH BUILDING MATERIAL MANUFACTURING ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">35</td>
        <td style="border: 1px solid black;">NELLORE DISTRICT CHAMBER OF COMMERCE & INDUSTRY </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">36</td>
        <td style="border: 1px solid black;">THE KAKINADA CUSTOMS BROKERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">37</td>
        <td style="border: 1px solid black;">KONDAPALLI INDUSTRIES ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">38</td>
        <td style="border: 1px solid black;">THE KAKINADA STEAMER AGENTS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">39</td>
        <td style="border: 1px solid black;">THE CHAMBER OF COMMERCE VIZIANAGARAM </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">40</td>
        <td style="border: 1px solid black;">VIZAG COOKING GAS DEALERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">41</td>
        <td style="border: 1px solid black;">VISAKHA TILES & SANITARY MERCHANTS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">42</td>
        <td style="border: 1px solid black;">TIRUPATI CHAMBER OF COMMERCE </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">43</td>
        <td style="border: 1px solid black;">THE GUNTUR DISTRICT COLD STORAGE OWNERS WELFARE ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">44</td>
        <td style="border: 1px solid black;">VISAKHA AUTO NAGAR SMALL SCALE INDUSTRIALIST WELFARE ASSOCIATION (VASSIWA)</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">45</td>
        <td style="border: 1px solid black;">THE KURNOOL DISTRICT CHAMBER OF COMMERCE & INDUSTRY </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">46</td>
        <td style="border: 1px solid black;">THE CHITTOOR DISTRICT PHARMACEUTICAL FRANCHISES ASSOCIATION  </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">47</td>
        <td style="border: 1px solid black;">VISHAKHA HOTEL MERCHANTS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">48</td>
        <td style="border: 1px solid black;">THE GUNTUR DALL MILLLERS WELFARE ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">49</td>
        <td style="border: 1px solid black;">THE KAKINADA PORT WORKERS POOL</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">50</td>
        <td style="border: 1px solid black;">THE KURNOOL HOTELS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">51</td>
        <td style="border: 1px solid black;">SRI POTTI SRIRAMULU NELLORE DIST RICE CANVASING AGENTS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">52</td>
        <td style="border: 1px solid black;">THE COCANADA CHAMBER OF COMMERCE </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">53</td>
        <td style="border: 1px solid black;">PLASTIC SCRAP DEALERS MFG. WELFARE ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">54</td>
        <td style="border: 1px solid black;">DISPOSAL MOTOR MERCHANTS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">55</td>
        <td style="border: 1px solid black;">CREDAI VIJAYAWADA CHAPTER</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">56</td>
        <td style="border: 1px solid black;">VIJAYAWADA TEXTILES READYMADE RETAILERS WELFARE ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">57</td>
        <td style="border: 1px solid black;">THE VIJAYAWADA TAXI OWNER’S WELFARE ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">58</td>
        <td style="border: 1px solid black;">VIJAYAWADA GLASS & PLYWOOD DEALERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">59</td>
        <td style="border: 1px solid black;">VIJAYAWADA BRANCH OF SIRC OF ICAI</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">60</td>
        <td style="border: 1px solid black;">VIJAYAWADA HOTEL OWNERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">61</td>
        <td style="border: 1px solid black;">NAREDCO VIJ CITY</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">62</td>
        <td style="border: 1px solid black;">THE JAWAHAR AUTONAGAR MUTUALLY AIDED CONSUMER CO-OPERATIVE STORES LTD. </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">63</td>
        <td style="border: 1px solid black;">THE KRISHNA DIST. LORRY OWNERS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">64</td>
        <td style="border: 1px solid black;">KRISHNA DISTRICT CHITFUND WELFARE ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">65</td>
        <td style="border: 1px solid black;">THE KRISHNA DISTRICT LORRY OWNERS MUTUALLY AIDED CO-OPERATIVE STORES LTD</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">66</td>
        <td style="border: 1px solid black;">THE AUTO MOBILE TECHNICIANS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">67</td>
        <td style="border: 1px solid black;">THE KRISHNA DISTRICT AUTO  FINANCE ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">68</td>
        <td style="border: 1px solid black;">KRISHNA DIST. RICE AND OIL MILLERS ASSOCIATION</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">69</td>
        <td style="border: 1px solid black;"> THE KRISHNA DIST. AGRO CHEMICAL MARKETERS WELFARE ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">70</td>
        <td style="border: 1px solid black;">POORNA MARKET DHALL’S & OIL DEALERS ASSOCIATION </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">71</td>
        <td style="border: 1px solid black;">NIMMAKAYALA VARTHAKA SANGHAM</td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">72</td>
        <td style="border: 1px solid black;">SRI KANAKAMAHALAKSHMI TRUST</td>
    </tr>




</table>
                
                </div>';
}



add_shortcode("HistoryPage", "HistoryPage");
function HistoryPage()
{
	echo '<div class="wrap__about-us mt-4">
                <h3><b> Introduction </b></h3>
                <p style="text-align:justify;">Andhra Pradesh Chambers of Commerce and Industry Federation (AP Chambers), a State-level industry body, represents the trade and industry of Andhra Pradesh with a membership of around 1,000 Corporate members and 60 affiliated associations totalling a membership of approximately 25,000. It is the largest industry body in the State of AP. The Federation is at the forefront to eagerly support and collaborate with the State and Central governments for the economic development of the State.</p>
                <p style="text-align:justify;">AP Chambers, established in 1999 as a chamber of commerce, presently represents the whole state of Andhra Pradesh. It is strategically located at Vijayawada, the hub of the State’s economic and political spectrum. AP Chambers has a three-tier membership, representing industry, trade and service. The Chambers has been relentlessly pursuing the agenda of identifying business opportunities and challenges, addressing critical issues with the single-minded focus of sustainable growth in the State. The Chambers has also been instrumental in influencing policy frameworks and changes.</p>
				<p style="text-align:justify;">AP Chambers provides a platform for interaction to address issues affecting the growth of businesses in the State. Various state-level and district-level associations/bodies/chambers are affiliated with AP Chambers.</p>
                <h3><b>Main activities of the Chambers:</b></h3>
                <p>1. Submitting representations to the State and Central governments on issues affecting commerce and industry.</p>
                <p>2. Educating/bringing awareness to members on various State and Central laws and amendments.</p>
                <p>3. Organising seminars, workshops and meetings for members with government officials and industry experts on vital issues.</p>
                <p>4. Acting as a trade facilitation centre</p>                
                <p style="text-align:justify;">The administration of the Chambers is handled by the Board of Directors and backed by a professional team headed by the President and other office-bearers. The Chambers constituted three zones, namely, Central, Visakhapatnam and Rayalaseema to better address the issues of each region. Each zone is headed by Vice President, Chairman and Vice Chairman for administrative convenience. The Board of Directors is also assisted by a string of state-level Committees on different subjects. All the members are encouraged to actively participate in the state-level committees of their interest to enable them to master the subjects and at the same time ensure that the Chambers always has able and experienced leaders to take care of the interests of the members. Andhra Pradesh Chambers of Commerce and Industry Federation will always truly be of the members, by the members, and for the members.</p>               
                </div>';
   
}




add_shortcode("PresidentsCouncil", "PresidentsCouncil");
function PresidentsCouncil()
{
	echo '<p style="text-align: center;"><strong><span style="text-decoration: underline;">Presidents Council</span></strong></p>
	<table style="width: 395px; height: 276px; margin-left: auto; margin-right: auto;" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
	<td valign="bottom" nowrap="nowrap" width="64">
	<table style="width: 370px; height: 271px;" border="1" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>S.No.</strong></p>
	</td>
	<td valign="top" width="186">
	<p style="text-align: center;"><strong>Members </strong></p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>1</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. Gokaraju Gangaraju</p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>2</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. M. Murali Krishna</p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>3</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. M. Prabhakar Rao</p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>4</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. G. Sambasiva Rao</p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>5</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. K.V.S. Prakash Rao<strong>&nbsp;</strong></p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>6</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. Pydah Krishna Prasad<strong>&nbsp;</strong></p>
	</td>
	</tr>
	<tr>
	<td valign="top" width="47">
	<p style="text-align: center;"><strong>7</strong></p>
	</td>
	<td valign="top" width="186">
	<p>Mr. Potluri Bhaskara Rao<strong>&nbsp;</strong></p>
	</td>
	</tr>
	</tbody>
	</table>
	<p>&nbsp;</p>
	</td>
	<td valign="bottom" nowrap="nowrap" width="77">
	<p style="text-align: center;"><strong>&nbsp;</strong></p>
	</td>
	<td valign="bottom" nowrap="nowrap" width="77">
	<p><strong>&nbsp;</strong></p>
	</td>
	</tr>
	</tbody>
	</table>';
   
}



add_shortcode("EthicsCommittee", "EthicsCommittee");
function EthicsCommittee()
{
	echo '<table style="height: 765px; margin-left: auto; margin-right: auto;" border="5" width="651">
	<tbody>
	<tr>
	<td style="width: 633px;" colspan="5">
	<p style="text-align: center;"><strong>Ethics Committee (Founder Members)</strong></p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p><strong>S.NO</strong></p>
	</td>
	<td style="width: 156.047px;">
	<p><strong>NAME</strong></p>
	</td>
	<td style="width: 187.344px;">
	<p><strong>ORGANIZATION</strong></p>
	</td>
	<td style="width: 122.484px;">
	<p><strong>DESIGNATION</strong></p>
	</td>
	<td style="width: 103.641px;">
	<p><strong>LOCATION</strong></p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>1</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. L.V.S. Rajasekhar</p>
	</td>
	<td style="width: 187.344px;">
	<p>LEPL Projects Limited (AirCosta)</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>2</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Ch. R. K. Prasad</p>
	</td>
	<td style="width: 187.344px;">
	<p>Kusalava International Limited</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>3</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. G. Gangaraju</p>
	</td>
	<td style="width: 187.344px;">
	<p>Laila Group of Companies</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>4</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Paladugu Laxmana Rao</p>
	</td>
	<td style="width: 187.344px;">
	<p>Chartered Accountant</p>
	</td>
	<td style="width: 122.484px;">
	<p>&nbsp;</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>5</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Chalasani Subba Rao</p>
	</td>
	<td style="width: 187.344px;">
	<p>Timber Marchents Association</p>
	</td>
	<td style="width: 122.484px;">
	<p>President</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>6</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Valluru Venkata Basavaiah</p>
	</td>
	<td style="width: 187.344px;">
	<p>Seshasai Kalyanamandapam</p>
	</td>
	<td style="width: 122.484px;">
	<p>&nbsp;</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>7</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. P. V. Koteswara Rao</p>
	</td>
	<td style="width: 187.344px;">
	<p>Navata Road Transport</p>
	</td>
	<td style="width: 122.484px;">
	<p>Managing Partner</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>8</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. M. Murali Krishna</p>
	</td>
	<td style="width: 187.344px;">
	<p>Hotel Fortune Murali Park</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>9</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Ch. Arun Kumar</p>
	</td>
	<td style="width: 187.344px;">
	<p>Popular Shoe Mart</p>
	</td>
	<td style="width: 122.484px;">
	<p>Managing Partner</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>10</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. D. Rama Krishna</p>
	</td>
	<td style="width: 187.344px;">
	<p>Efftronics Systems Pvt. Ltd.</p>
	</td>
	<td style="width: 122.484px;">
	<p>&nbsp;MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>11</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. K. V. S. Prakash Rao</p>
	</td>
	<td style="width: 187.344px;">
	<p>Ramcor</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>12</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Jeji Prasad</p>
	</td>
	<td style="width: 187.344px;">
	<p>Prakasa Engineering Works</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 633px;" colspan="5">
	<p style="text-align: center;"><strong>Ethics Committee (Elected Members)</strong></p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>1</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. C. Bhagavantha Rao</p>
	</td>
	<td style="width: 187.344px;">
	<p>INCAP Limited</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>2</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. Y. V. Eswara Rao</p>
	</td>
	<td style="width: 187.344px;">
	<p>The AP Lorry Owners Association</p>
	</td>
	<td style="width: 122.484px;">
	<p>Secretary</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	<tr>
	<td style="width: 39.4844px;">
	<p>3</p>
	</td>
	<td style="width: 156.047px;">
	<p>Mr. S. Ganesh</p>
	</td>
	<td style="width: 187.344px;">
	<p>Liners India Limited</p>
	</td>
	<td style="width: 122.484px;">
	<p>MD</p>
	</td>
	<td style="width: 103.641px;">
	<p>Vijayawada</p>
	</td>
	</tr>
	</tbody>
	</table>';
   
}



add_shortcode("WomenEntrepreneursWing", "WomenEntrepreneursWing");
function WomenEntrepreneursWing()
{
	// Add CSS for consistent image sizing
	echo '<style>
		.women-wing-img {
			width: 100%;
			height: 220px;
			object-fit: contain;
			object-position: center;
			background-color: #f8f9fa;
		}
		.women-wing-card {
			height: 100%;
		}
		.women-wing-card-body {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.women-wing-img-container {
			flex-shrink: 0;
		}
		.women-wing-info {
			flex-grow: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
	</style>';
	
	echo '<div>
			<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
				<strong><u><span style="font-family:Georgia; ">WOMEN ENTREPRENEURS\' WING FOR 2025-27</span></u></strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
				<strong><span style="font-family:Georgia; ">&#xa0;</span></strong>
			</p>';
	
	$defaultImage = get_site_url().'/default-user.png';
	
	// Central Zone
	$centralZone = array(
		array(
			'name' => 'Dr. Mamatha Rayapati',
			'designation' => 'Chairperson',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/mamatha_rayapati.jpg'
		),
		array(
			'name' => 'Smt. G. Jayasree',
			'designation' => 'Vice Chairperson',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/g_jayasree.jpg'
		),
		array(
			'name' => 'Smt. Sravanti Kancharla',
			'designation' => 'Vice Chairperson',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/sravanti_kancharla.jpg'
		)
	);
	
	echo '<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
			<strong><u><span style="font-family:Georgia; ">CENTRAL ZONE</span></u></strong>
		</p>';
	echo '<div class="row mt-2 mb-4">';
	foreach($centralZone as $member) {
		$profileImage = (!empty($member['image'])) ? $member['image'] : $defaultImage;
		?>
		<div class="col-sm-6 col-md-4 col-lg-3 p-2">
			<div class="card shadow women-wing-card">
				<div class="card-body text-center women-wing-card-body">
					<div class="women-wing-img-container">
						<img class="women-wing-img" src="<?=$profileImage;?>" alt="<?=$member['name'];?>" />
					</div>
					<div class="border women-wing-info">
						<h5 class="mt-2 card-title text-center">
							<b><?=$member['name'];?></b>
						</h5>
						<p class="card-text mt-1 text-center text-muted"><?=$member['designation'];?></p>
					</div>
				</div>
			</div>	
		</div>
		<?php
	}
	echo '</div>';
	
	// Visakhapatnam Zone
	$visakhapatnamZone = array(
		array(
			'name' => 'Dr. Srivalli Korrapati',
			'designation' => 'Chairperson',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/srivalli_korrapati.jpg'
		)
	);
	
	echo '<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
			<strong><u><span style="font-family:Georgia; ">VISAKHAPATNAM ZONE</span></u></strong>
		</p>';
	echo '<div class="row mt-2 mb-4">';
	foreach($visakhapatnamZone as $member) {
		$profileImage = (!empty($member['image'])) ? $member['image'] : $defaultImage;
		?>
		<div class="col-sm-6 col-md-4 col-lg-3 p-2">
			<div class="card shadow women-wing-card">
				<div class="card-body text-center women-wing-card-body">
					<div class="women-wing-img-container">
						<img class="women-wing-img" src="<?=$profileImage;?>" alt="<?=$member['name'];?>" />
					</div>
					<div class="border women-wing-info">
						<h5 class="mt-2 card-title text-center">
							<b><?=$member['name'];?></b>
						</h5>
						<p class="card-text mt-1 text-center text-muted"><?=$member['designation'];?></p>
					</div>
				</div>
			</div>	
		</div>
		<?php
	}
	echo '</div>';
	
	echo '</div>';
   
}



add_shortcode("Zones", "Zones");
function Zones()
{
	// Add CSS for consistent image sizing
	echo '<style>
		.zonal-committee-img {
			width: 100%;
			height: 220px;
			object-fit: contain;
			object-position: center;
			background-color: #f8f9fa;
		}
		.zonal-committee-card {
			height: 100%;
		}
		.zonal-committee-card-body {
			display: flex;
			flex-direction: column;
			height: 100%;
		}
		.zonal-committee-img-container {
			flex-shrink: 0;
		}
		.zonal-committee-info {
			flex-grow: 1;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
	</style>';
	
	echo '<div>
			<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
				<strong><u><span style="font-family:Georgia; ">ZONAL COMMITTEES OFFICE-BEARERS FOR 2025-27</span></u></strong>
			</p>
			<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
				<strong><span style="font-family:Georgia; ">&#xa0;</span></strong>
			</p>';
	
	$defaultImage = get_site_url().'/default-user.png';
	
	// Central Zone
	$centralZone = array(
		array(
			'name' => 'Sri Sidda Sudheer Kumar',
			'designation' => 'Vice President',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/sidda_sudheer.jpg'
		),
		array(
			'name' => 'Sri V Sathish Babu',
			'designation' => 'Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/sathis_babu.jpg'
		),
		array(
			'name' => 'Smt. Sakku Madhavi',
			'designation' => 'Vice Chairperson',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/sakku_madhavi.jpg'
		),
		array(
			'name' => 'Sri Gadde Vijaya Kumar',
			'designation' => 'Vice Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/gadde_vijaykumar.jpg'
		)
	);
	
	echo '<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
			<strong><u><span style="font-family:Georgia; ">CENTRAL ZONE</span></u></strong>
		</p>';
	echo '<div class="row mt-2 mb-4">';
	foreach($centralZone as $member) {
		$profileImage = (!empty($member['image'])) ? $member['image'] : $defaultImage;
		?>
		<div class="col-sm-6 col-md-4 col-lg-3 p-2">
			<div class="card shadow zonal-committee-card">
				<div class="card-body text-center zonal-committee-card-body">
					<div class="zonal-committee-img-container">
						<img class="zonal-committee-img" src="<?=$profileImage;?>" alt="<?=$member['name'];?>" />
					</div>
					<div class="border zonal-committee-info">
						<h5 class="mt-2 card-title text-center">
							<b><?=$member['name'];?></b>
						</h5>
						<p class="card-text mt-1 text-center text-muted"><?=$member['designation'];?></p>
					</div>
				</div>
			</div>	
		</div>
		<?php
	}
	echo '</div>';
	
	// Rayalaseema Zone
	$rayalaseemaZone = array(
		array(
			'name' => 'Sri K V Chowdary',
			'designation' => 'Vice President',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/v_chowdary.jpg'
		),
		array(
			'name' => 'Sri K Ramalinga Reddy',
			'designation' => 'Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/ramalinga_reddy.jpg'
		),
		array(
			'name' => 'Sri R Suresh Babu',
			'designation' => 'Vice Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/suresh_babu.jpg'
		),
		array(
			'name' => 'Sri K M Siva Murthy',
			'designation' => 'Vice Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/siva_murthy.jpg'
		)
	);
	
	echo '<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
			<strong><u><span style="font-family:Georgia; ">RAYALASEEMA ZONE</span></u></strong>
		</p>';
	echo '<div class="row mt-2 mb-4">';
	foreach($rayalaseemaZone as $member) {
		$profileImage = (!empty($member['image'])) ? $member['image'] : $defaultImage;
		?>
		<div class="col-sm-6 col-md-4 col-lg-3 p-2">
			<div class="card shadow zonal-committee-card">
				<div class="card-body text-center zonal-committee-card-body">
					<div class="zonal-committee-img-container">
						<img class="zonal-committee-img" src="<?=$profileImage;?>" alt="<?=$member['name'];?>" />
					</div>
					<div class="border zonal-committee-info">
						<h5 class="mt-2 card-title text-center">
							<b><?=$member['name'];?></b>
						</h5>
						<p class="card-text mt-1 text-center text-muted"><?=$member['designation'];?></p>
					</div>
				</div>
			</div>	
		</div>
		<?php
	}
	echo '</div>';
	
	// Visakhapatnam Zone
	$visakhapatnamZone = array(
		array(
			'name' => 'Smt. A Leela Rani',
			'designation' => 'Vice President',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/leela_rani.jpg'
		),
		array(
			'name' => 'Sri P Sobhan Prakash',
			'designation' => 'Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/sobhan_prakash.jpg'
		),
		array(
			'name' => 'Sri G Shiva Kumar',
			'designation' => 'Vice Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/shiva_kumar.jpg'
		),
		array(
			'name' => 'Sri Pavan Kartheek',
			'designation' => 'Vice Chairman',
			'image' => 'https://apchambers.in/wp-content/uploads/2025/10/pavan_kartheek.jpg'
		)
	);
	
	echo '<p style="margin-top:0pt; margin-bottom:8pt; text-align:center; line-height:108%; font-size:12pt">
			<strong><u><span style="font-family:Georgia; ">VISAKHAPATNAM ZONE</span></u></strong>
		</p>';
	echo '<div class="row mt-2 mb-4">';
	foreach($visakhapatnamZone as $member) {
		$profileImage = (!empty($member['image'])) ? $member['image'] : $defaultImage;
		?>
		<div class="col-sm-6 col-md-4 col-lg-3 p-2">
			<div class="card shadow zonal-committee-card">
				<div class="card-body text-center zonal-committee-card-body">
					<div class="zonal-committee-img-container">
						<img class="zonal-committee-img" src="<?=$profileImage;?>" alt="<?=$member['name'];?>" />
					</div>
					<div class="border zonal-committee-info">
						<h5 class="mt-2 card-title text-center">
							<b><?=$member['name'];?></b>
						</h5>
						<p class="card-text mt-1 text-center text-muted"><?=$member['designation'];?></p>
					</div>
				</div>
			</div>	
		</div>
		<?php
	}
	echo '</div>';
	
	echo '</div>';
   
}


add_shortcode("SubCommittees", "SubCommittees");
function SubCommittees()
{
	echo '
    
    	<div style=" border: 0px solid;
	display: flex;
	justify-content: center;">
    <p>&nbsp;</p>
<table style="border: none;border-collapse: collapse;width:354pt;">
    <tbody>
        <tr>
            <td colspan="3" style="color:black;font-size:15px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.0pt;width:354pt;">VISAKHAPATNAM ZONE</td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">HOTELS &amp; TOURISM</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">&nbsp;S.No.</td>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">NAME</td>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">DESIGNATION</td>
        </tr>
        <tr>
            <td rowspan="2" style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid black;border-left:1.0pt solid windowtext;height:30.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:none;border-left:none;width:248pt;">Mr. G. VENKAT KRISHNA</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:none;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;height:15.75pt;width:248pt;">DIRECTOR, DASPALLA HOTELS</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;"><br></td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Ms. USHA KODALI (TRAVEL IQ)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">CO-CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. G &nbsp;SHIVA KUMAR (CMD, SVN GROUP)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. BISWAS (PARK HOTEL)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. P.SRINIVAS KUMAR (VTZ TRAVEL LINKS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. KAMAL (SUNRAY)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">7</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. G EASWAR KAUSHIK (BLUE EARTH HOTELS PVT LTD)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">8</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. DHEERAJ (DC HOLIDAYS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">9</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. I T RAMA KRISHNAN (DOLPHIN)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">10</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SANDEEP REDDY (PALM BEACH)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">11</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SURESH GM (RIVER BAY, RAJAHMUNDRY)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">12</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. MURTHY CHITTOORY (VENKY RESIDENCY , KAKINADA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:1.0pt solid windowtext;height:15.75pt;border-top:none;">13</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. K .SRIKANTH (BEST WESTERN)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">14</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SUSHANT (SPICY VENUE, SIRIPURAM)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;border-left:none;"><br></td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;width:248pt;"><br></td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;width:354pt;">EDUCATION</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">&nbsp;S.No.</td>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">NAME</td>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">DESIGNATION</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. V RAMA RAO (SECRETARY &amp; CORRESPONDENT, MVR COLLEGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td rowspan="2" style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid black;border-left:1.0pt solid windowtext;height:30.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:none;border-left:none;width:248pt;">Mr. D. SATYANARAYANA REDDY</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:none;border-left:1.0pt solid windowtext;">CO-CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;height:15.75pt;width:248pt;">SECRETARY AND CORRESPONDENT</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;"><br></td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. T BALARAMA KRISHNA (TSR &amp; TBK COLLEGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. DADI RATNAKAR (DIET, ANAKAPALLE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. JASTI SRIKANTH (SUN COLLEGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. PRANEET (BALAJI PUBLIC SCHOOL)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">7</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. D.GOVINDA RAO (SAMYUKTHA DEGREE COLLEGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">8</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. ANSG DORA BABU (VIVEKANANDA DEGREE COLLEGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">9</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. M.RAAMJEE (SVP ENGG COLLEGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">10</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SANDEEP CHITHRA (POLLOCK SCHOOL)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">&nbsp;MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">11</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. ANAND RAO (NIST)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:1.0pt solid windowtext;border-top:none;">&nbsp;MEMBER</td>
        </tr>
        <tr>
            <td rowspan="2" style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid black;border-left:1.0pt solid windowtext;height:30.75pt;">12</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:none;border-left:none;width:248pt;">Mr. G L N RAJU</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:none;border-left:1.0pt solid windowtext;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:none;border-bottom:1.0pt solid windowtext;border-left:none;height:15.75pt;width:248pt;">(MAHATHI EDUCATIONAL INSTITUTIONS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;"><br></td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.0pt;width:29pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;width:248pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;width:77pt;"><br></td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:700;font-style:italic;text-decoration:none;font-family:"Century Schoolbook", serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;width:354pt;">PORTS &amp; LOGISTICS</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. RAJESH GRANDHI (INTEGRAL TRADING &amp; LOGISTICS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. A VENKATA CHALAM (AKV LOGISTICS PVT LTD)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CO-CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. A V SUBBA RAO</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. KOLLI RAMESH (JSN MARINE PVT LTD)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. MAHENDRA TATED&nbsp;</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. A RAVI SHANKAR (ELITELOGIX EXIM AGENCY)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">7</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. M SATYA NARAYANA (ASHOK INTERNATIONAL)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">8</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. D S ANAND</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">9</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. ARG UNNITHAN (MARINE CARE &apos;N&apos; ASSOCIATES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">10</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. PBL RAJESH (PBL TRANSPORTS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">&nbsp;MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:700;font-style:italic;text-decoration:none;font-family:"Century Schoolbook", serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">INSURANCE, FIRE &amp; SAFETY</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;"><br></td>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">NAME&nbsp;</td>
            <td style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">DESIGNATION</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. B MADHU</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SRI RAM PRASAD</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CO CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. RAMA KRISHNA RAO (SAHANA ASSOCIATES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SPB RANGACHARYULU</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:700;font-style:italic;text-decoration:none;font-family:"Century Schoolbook", serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; INFORMATION TECHNOLOGY&nbsp;</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. O NARESH KUMAR</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. G KRISHNA MOHAN (INSPIREEDGE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">VICE CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. PETER SCHNEEBERGER</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SRIDHAR KOSARAJU</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. WINSTON ADAMS (FLUENTGRID)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SRIDHAR PANUGANTI (XINTHE TECHNOLOGIES PVT LTD)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:700;font-style:italic;text-decoration:none;font-family:"Century Schoolbook", serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;width:354pt;">MEDIA &amp; ENTERTAINMENT</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. P L K MURTHY</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. UDAY PATTA (PIXEL RUN)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. RAJESH GUNTU</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. SANTOSH PATNAIK (EX HINDU)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">MR. VEERUMAMA (V TEAM)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. K SESHU BABU (SREEKANYA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">7</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. K VAMSI (MOHINI THEATRES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">8</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. JASTI MADAN (VENKATESWARA THEATRES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">9</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. D.RAVI KUMAR (RAYS ENTERTAINMENT)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">10</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. RAHUL (RAZZMATAZZ)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">&nbsp;MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.0pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;width:354pt;">TAXATION&nbsp;</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. VAYETLA SRINIVASA RAO (MCA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. S.MURALI KRISHNA (ICAI)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CO CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. N. RAMU NAIDU (NEMMADI &amp; ASSOCIATES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. M V SURENDRA KALYAN (MCA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">&nbsp;Mr. JAGADISH KUMAR (CPE ACADEMY)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:1.0pt solid windowtext;height:15.75pt;border-top:none;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. ANIL KUMAR BEZAWADA (HIRAGANGE ASSOCIATES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;width:354pt;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;width:354pt;">PHARMA &amp; VSEZ</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. P.VENKATARAMA REDDY (ACTIS PHARMA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mrs. VEENA JANA (WORLD WIDE DIAMONDS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CO CHAIRPERSON</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. MVSNV PRASADA RAJU (VASUDHA PHARMA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. KVS VARMA (HOBEL BELLOWS CO)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. J SUBBA RAO (SIONC PHARMA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mrs. ALLA LEELA RANI (LEE PHARMA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">7</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. BSSV NARAYANA (SYNERGIES CASTINGS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">8</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. DORA SWAMY (BRANDIX)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">9</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. GANESH KUMAR (GLAND PHARMA)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:15px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;height:15.75pt;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
            <td style="color:black;font-size:15px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;"><br></td>
        </tr>
        <tr>
            <td colspan="3" style="color:black;font-size:13px;font-weight:700;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:1.0pt solid windowtext;border-right:1.0pt solid black;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;width:354pt;">MSME &amp; START-UPS</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">1</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. V.M. KISHORE KUMAR (SEERA METALS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">CHAIRMAN</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">2</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. M. SRIKANTH (ACADEMY OF ROBOTICS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">3</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. AKULA SREEDHAR (BHAVANA FABTECH)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">4</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. CH. RAJASEKHAR (MARGNARC ELECTRODES)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">5</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. R. JAGAPATHI RAJU (COMMERCIAL PLASTICS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:15.75pt;">6</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. HAKIM MEHDI (MOHSIN BROTHERS)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
        <tr>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:center;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:1.0pt solid windowtext;height:27.0pt;">7</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;width:248pt;">Mr. RAJINIKANTH (LOHMAN CASTINGS PVT LTD, ANAKAPALLE)</td>
            <td style="color:black;font-size:13px;font-weight:400;font-style:normal;text-decoration:none;font-family:Calibri, sans-serif;text-align:general;vertical-align:bottom;border:none;border-top:none;border-right:1.0pt solid windowtext;border-bottom:1.0pt solid windowtext;border-left:none;">MEMBER</td>
        </tr>
    </tbody>
</table>
</div>';
   
}