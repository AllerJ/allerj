<?php
/**
 * J. Aller functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package j_aller
 */
 
require get_template_directory() . '/inc/diet.php';
require get_template_directory() . '/inc/widget-areas.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/duplicate-page.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/required-plugins.php';
require get_template_directory() . '/blocks/aller-block.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/plugin-log.php';
require get_template_directory() . '/inc/class-rename-wp-login.php';

// if woocommerce is installed, add theme support
if ( defined( 'WC_VERSION ' ) ) {
	require get_template_directory() . '/woocommerce/woocommerce-functions.php';
}

// if Jetpack is installed, add support
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Adds PopUp to site if the Sidebar named "popup" exists.
if( is_active_sidebar( 'popup' ) ):
	require get_template_directory() . '/inc/dash-popup.php';
endif;

/**
* Function to add Accessibility Page and footer block.
* DIRECTIONS: Uncomment, refresh page, re-comment out. 
*/
// require get_template_directory() . '/inc/make-pages.php';

$defaultTimeZone='UTC';
if(date_default_timezone_get() != $defaultTimeZone) date_default_timezone_set($defaultTimeZone);

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

// Protect wordpress theme files from being edited by the built in file editor.
define('DISALLOW_FILE_EDIT', true);

if ( ! function_exists( 'allerj_setup' ) ) :
	function allerj_setup() {
//		Uncomment to add RSS Feeds		
//		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'search-form',
				'caption',
				'gallery',
				'script',
				'style'
			)
		);
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
		add_editor_style( 'css/allerj.css' );
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'allerj' ),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'allerj_setup' );

// Sets the default width for the GetWid reqiured plugin.
function allerj_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'allerj_content_width', 938 );
}
add_action( 'after_setup_theme', 'allerj_content_width', 0 );

// Set the default font set from Google Fonts. Will be overridden in customizer.
function allerj_scripts() {
	wp_enqueue_style( 'fonts', get_theme_mod( 'google_fonts_link', 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400&family=Montserrat:wght@300;400&display=swap'), array(), null );


// Decides if the theme should use the combined minified css and js flat files or the dynamic versions. This is set in Customizer 
	$allerj_css = $_ENV['THEME_MODE'] == 'prod' ? 'theme.css' : 'theme.php';
	$allerj_js = $_ENV['THEME_MODE'] == 'prod' ? 'vendors.js' : 'vendors.php';
	
	wp_enqueue_style( 'allerj-style', get_stylesheet_directory_uri().'/css/'.$allerj_css, array(), _S_VERSION );
	wp_enqueue_script('vendors_js', get_stylesheet_directory_uri().'/js/'.$allerj_js, array(), null, true);

// Uncomment to include a loading line at the top of each page.	
//	wp_enqueue_script('line_js', get_stylesheet_directory_uri().'/js/line.js', array(), null, true);
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'allerj_scripts' );

// Formats the head section
add_action( 'wp_head', function(){echo "\n";}, 6 );

// Adds fonts and scripts for the Wordpress Dashboard
function add_google_font() {
	wp_enqueue_style( 'admin-google-font', get_theme_mod( 'google_fonts_link', 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400&family=Montserrat:wght@300;400&display=swap'), array(), null );
}
add_action( 'admin_enqueue_scripts', 'add_google_font' );

// Adds bootstrap menu layout	  
function register_navwalker(){
	require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';
}
add_action( 'after_setup_theme', 'register_navwalker' );

function magnium_tinymce_fix( $init )
{
	$init['extended_valid_elements'] = 'div[*], article[*]';
	$init['remove_linebreaks'] = false;
	$init['convert_newlines_to_brs'] = true;
	$init['remove_redundant_brs'] = false;
	return $init;
}
add_filter('tiny_mce_before_init', 'magnium_tinymce_fix');

function _date($format="r", $timestamp=false, $timezone=false)
{
	$userTimezone = new DateTimeZone(!empty($timezone) ? $timezone : 'GMT');
	$gmtTimezone = new DateTimeZone('GMT');
	$myDateTime = new DateTime(($timestamp!=false?date("r",(int)$timestamp):date("r")), $gmtTimezone);
	$offset = $userTimezone->getOffset($myDateTime);
	return date($format, ($timestamp!=false?(int)$timestamp:$myDateTime->format('U')) + $offset);
}
