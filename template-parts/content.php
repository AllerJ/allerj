<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package j_aller
 */

?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php
if ( is_singular() ) :
						the_title( '<span class="h1 entry-title">', '</span>' );
					else :
						the_title( '<span class="h2 entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></span>' );
						echo "<!-- .entry-title -->";
					endif;
			
					if ( 'post' === get_post_type() ) :
						?>
						
					<div class="entry-meta">
						<?php
						allerj_posted_on();
						allerj_posted_by();
						?>
						
					</div><!-- .entry-meta -->
<?php endif; ?>
				</header><!-- .entry-header -->
					<?php if ( is_front_page() == false) : ?>
					<?php if(allerj_post_thumbnail()) { ?>
									<div class="row">
										<div class="col">
					<?php allerj_post_thumbnail(); ?>
										</div>
									</div>
					<?php } ?>
					<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'allerj' ),
								array(
									'span' => array(
										'class' => array(),
									),
								)
							),
							wp_kses_post( get_the_title() )
						)
					);
					
					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'allerj' ),
							'after'  => '</div>',
						)
					);
					?>
				
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<?php allerj_entry_footer(); ?>
				
				</footer><!-- .entry-footer -->
			</article><!-- #post-<?php the_ID(); ?> -->
