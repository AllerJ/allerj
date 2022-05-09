<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package j_aller
 */
function makeCSS(){
	$base_file = __DIR__.'/aller-block/base.css';
	
	
	$brandColor = esc_html( get_theme_mod( 'brandColor', '#424a84' ) );
	$brandDark =  esc_html( get_theme_mod( 'brandDark', '#060644' ) );
	$brandAlt = esc_html( get_theme_mod( 'brandAlt', '#800080' ) );	
	$textColor = esc_html( get_theme_mod( 'textColor', '#282828' ) );	

	$acc_brandColor = getContrastColor( esc_html( get_theme_mod( 'brandColor', '#424a84' ) ) );
	$acc_brandDark =  getContrastColor( esc_html( get_theme_mod( 'brandDark', '#060644' ) ) );
	$acc_brandAlt = getContrastColor( esc_html( get_theme_mod( 'brandAlt', '#800080' ) ) );	
	$acc_textColor = getContrastColor( esc_html( get_theme_mod( 'textColor', '#282828' ) ) );

	$root_css = "
:root {
	--brand-color: $brandColor;
	--brand-dark: $brandDark;
	--brand-alt: $brandAlt;
	--body-text: $textColor;
	
	--acc_brand-color: $acc_brandColor;
	--acc_brand-dark: $acc_brandDark;
	--acc_brand-alt: $acc_brandAlt;
	--acc_body-text: $acc_textColor;
}";

	$base_css = file_get_contents($base_file);
	$get_array = proper_parse_str(get_theme_mod( 'google_fonts_link', 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400&family=Montserrat:wght@300;400&display=swap'));
	
	$file = __DIR__.'/aller-block/style.css'; 
	$open = fopen( $file, 'w' ); 
	$write = fputs( $open, $root_css ); 
	$write = fputs( $open, $base_css );
	
	foreach($get_array['family'] as $oneFamily) {
		$font_name = get_font_name($oneFamily);
		$font_class = str_replace(' ', '-', strtolower($font_name));
		$class = ".$font_class { font-family: '$font_name' !important;}
";
		$write = fputs( $open, $class ); 
	}

	$write = fputs( $open, $class ); 
 
	fclose( $open );
}
add_action( 'customize_save_after', 'makeCSS' );


function replace_color_palette() {
	// Disable Custom Colors
	add_theme_support( 'disable-custom-colors' );
  
  
	  $brandColor = esc_html( get_theme_mod( 'brandColor', '#424a84' ) );
	  $brandDark =  esc_html( get_theme_mod( 'brandDark', '#060644' ) );
	  $brandAlt = esc_html( get_theme_mod( 'brandAlt', '#800080' ) );	
	  $textColor = esc_html( get_theme_mod( 'textColor', '#282828' ) );	
	  
	// Editor Color Palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Brand Color', 'allerj' ),
			'slug'  => 'brandColor',
			'color'	=> $brandColor,
		),
		array(
			'name'  => __( 'Brand Dark', 'allerj' ),
			'slug'  => 'brandDark',
			'color'	=> $brandDark,
		),
		array(
			'name'  => __( 'Brand Alt', 'allerj' ),
			'slug'  => 'brandAlt',
			'color'	=> $brandAlt,
		),
		array(
			'name'  => __( 'Body Text', 'allerj' ),
			'slug'  => 'textColor',
			'color'	=> $textColor,
		),
		array(
			'name'  => __( 'White', 'allerj' ),
			'slug'  => 'white',
			'color'	=> '#fff',
		),
		array(
			'name'  => __( 'Black', 'allerj' ),
			'slug'  => 'black',
			'color'	=> '#000',
		),
	) );
}
add_action( 'after_setup_theme', 'replace_color_palette' );




function aller_block_block_init() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$dir = get_template_directory() . '/blocks';

	$index_js = 'aller-block/index.js';
	wp_register_script(
		'aller-block-block-editor',
		get_template_directory_uri() . "/blocks/$index_js",
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
		),
		filemtime( "$dir/$index_js" )
	);


	$brandColor[] = [
		'name'  => __( 'Brand Color', 'allerj'),
		'slug'  => 'brandColor',
		'color' => esc_html( get_theme_mod( 'brandColor', '#424a84' ) )
	];
	$brandColor[] = [
	
		'name'  => __('Brand Dark Color', 'allerj'),
		'slug'  => 'brandDark',
		'color' => esc_html( get_theme_mod( 'brandDark', '#060644' ) )
	];
	$brandColor[] = [
		'name'  => __('Brand Alt Color', 'allerj'),
		'slug'  => 'brandAlt',
		'color' => esc_html( get_theme_mod( 'brandAlt', '#800080' ) )
	];
	$brandColor[] = [
		'name'  => __('Text Color', 'allerj'),
		'slug'  => 'textColor',
		'color' => esc_html( get_theme_mod( 'textColor', '#282828' ) )
	];
	$brandColor[] = [
		'name'  => __('White', 'allerj'),
		'slug'  => 'white',
		'color' => '#fff' 
	];
	$brandColor[] = [
		'name'  => __('Black', 'allerj'),
		'slug'  => 'black',
		'color' => '#000' ,
	];


	$get_array = proper_parse_str(get_theme_mod( 'google_fonts_link', 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400&family=Montserrat:wght@300;400&display=swap'));
		$googleFonts[] = [ 'label' => 'Default', 'value' => '' ];
	foreach($get_array['family'] as $oneFamily) {
		$fontName = get_font_name($oneFamily);
		$font_class = str_replace(' ', '-', strtolower($fontName));
		$googleFonts[] = [ 'label' => $fontName, 'value' => $font_class ];
	}
	

	wp_localize_script(
		'aller-block-block-editor',
		"CUSTOMIZER",
		[
			'googleFonts' => $googleFonts,
			'brandColors' => $brandColor
		]
	);

	$editor_css = 'aller-block/style.css';
	wp_register_style(
		'aller-block-block-editor',
		get_template_directory_uri() . "/blocks/$editor_css",
		array(),
		filemtime( "$dir/$editor_css" )
	);

	register_block_type( 'allerj/aller-block', array(
		'editor_script' => 'aller-block-block-editor',
		'editor_style'  => 'aller-block-block-editor',
		'style'         => 'aller-block-block'
	) );
}
add_action( 'init', 'aller_block_block_init' );


function proper_parse_str($string)
{
	$array = array();
	$delimiter = '?';
	if (strpos($string, '?') === false) {
		$delimiter = '/';
	}
	$string = urldecode(substr($string, strrpos($string, $delimiter)+1));
	if (strpos($string, '=') === false) {
		$array[$string] = $string;
		return $array;
	}
	$pairs = explode('&', $string);
	foreach ($pairs as $pair) {
		list($key, $value) = explode('=', $pair, 2);
		if (isset($array[$key])) {
			if (is_array($array[$key])) {
				$array[$key][] = $value;
			} else {
				$array[$key] = array($array[$key], $value);
			}
		} else {
			$array[$key] = $value;
		}
	}
	return $array;
}

function get_font_name($string)
{   
	$string = explode(':', $string);
	return $string[0];   
}

function getContrastColor($hexColor) 
{

		// hexColor RGB
		$R1 = hexdec(substr($hexColor, 1, 2));
		$G1 = hexdec(substr($hexColor, 3, 2));
		$B1 = hexdec(substr($hexColor, 5, 2));

		// Black RGB
		$blackColor = "#000000";
		$R2BlackColor = hexdec(substr($blackColor, 1, 2));
		$G2BlackColor = hexdec(substr($blackColor, 3, 2));
		$B2BlackColor = hexdec(substr($blackColor, 5, 2));

		 // Calc contrast ratio
		 $L1 = 0.2126 * pow($R1 / 255, 2.2) +
			   0.7152 * pow($G1 / 255, 2.2) +
			   0.0722 * pow($B1 / 255, 2.2);

		$L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
			  0.7152 * pow($G2BlackColor / 255, 2.2) +
			  0.0722 * pow($B2BlackColor / 255, 2.2);

		$contrastRatio = 0;
		if ($L1 > $L2) {
			$contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
		} else {
			$contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
		}

		// If contrast is more than 5, return black color
		if ($contrastRatio > 5) {
			return '#000000';
		} else { 
			// if not, return white color.
			return '#FFFFFF';
		}
}


