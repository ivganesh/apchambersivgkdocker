
<?php 
	while ( have_posts() ) : the_post();
?>
	<section class="invoice">
		<?php 
			the_content();
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>
	</section>
<?PHP
	endwhile; 
?>
</div>
</body>
</html>
