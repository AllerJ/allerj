<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package j_aller
 */

?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('container-fluid'); ?>>
				<div class="row">
					<div class="col px-0">
						<div class="entry-content my-0">
							
<?php the_content();
							wp_link_pages(
								array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'allerj' ),
									'after'  => '</div>',
								)
							);?>
							
						</div><!-- .entry-content -->
					</div>
				</div>
			</article><!-- #post-<?php the_ID(); ?> -->