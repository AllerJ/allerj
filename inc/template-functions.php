<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package j_aller
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function allerj_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'allerj_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function allerj_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

function custom_logo_url() {
	$logo = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $logo , 'full' );
	$image_url = $image[0] ?? '';
	return $image_url;
}

// Commented out to remove pingback link
//add_action( 'wp_head', 'allerj_pingback_header' );

function allerj_wp_head(){
	ob_start();
	wp_head();
	$header = ob_get_contents();
	ob_end_clean();
	echo preg_replace("/\n</", "\n\t<", substr($header, 0, -1));
	echo "\n";
}