<?php
/**
 * Template Name: WooCommerce
 * @package j_aller
 */

get_header();
?>
<div class="container-fluid mt-5">
	<div class="row justify-content-md-center">
		<div id="primary" class="content-area col-md-10">
			<main id="main" class="site-main justify-content-md-center" role="main">
		<?php
		if(woocommerce_content()){
			woocommerce_content();
		} else {
			
			while ( have_posts() ) :
				the_post();
				?>
				
				<?php the_content(); ?>

			<?php 
			endwhile; // End of the loop.
		}
			?>
			</main><!-- #main -->
		</div>
	</div>
</div>

<?php
get_sidebar();
get_footer();
