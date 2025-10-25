<div class="content-wrapper">
<section class="content">
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
</section>
</div>