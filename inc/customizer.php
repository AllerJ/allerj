<?php
/**
 * J. Aller Theme Customizer
 *
 * @package j_aller
 */

function allerj_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'allerj_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'allerj_customize_partial_blogdescription',
			)
		);
	}
}
add_action( 'customize_register', 'allerj_customize_register' );

function allerj_customize_partial_blogname() {
	bloginfo( 'name' );
}

function allerj_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

function allerj_customize_preview_js() {
	wp_enqueue_script( 'allerj-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'allerj_customize_preview_js' );


function brand_colors_customize_register( $wp_customize ) {
	$wp_customize->add_setting(
		'textColor', array(
			'default'           => '#282828',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'textColor', array(
				'label'       => esc_html__( 'Body Text Color', 'allerj' ),
				'description' => esc_html__( 'Select the primary body text color.', 'allerj' ),
				'section'     => 'allerj_color_scheme',
			)
		)
	);
	
	$wp_customize->add_setting(
		'brandColor', array(
			'default'           => '#424a84',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'brandColor', array(
				'label'       => esc_html__( 'Brand Color', 'allerj' ),
				'description' => esc_html__( 'Select the primary Brand Color.', 'allerj' ),
				'section'     => 'allerj_color_scheme',
			)
		)
	);
	
	$wp_customize->add_setting(
		'brandDark', array(
			'default'           => '#060644',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'brandDark', array(
				'label'       => esc_html__( 'Brand Dark Color', 'allerj' ),
				'description' => esc_html__( 'Typically this is used for the hover color.', 'allerj' ),
				'section'     => 'allerj_color_scheme',
			)
		)
	);
	
	$wp_customize->add_setting(
		'brandAlt', array(
			'default'           => '#800080',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, 'brandAlt', array(
				'label'       => esc_html__( 'Brand Alt Color', 'allerj' ),
				'description' => esc_html__( 'Select a secondary color for this brand.', 'allerj' ),
				'section'     => 'allerj_color_scheme',
			)
		)
	);

}
add_action( 'customize_register', 'brand_colors_customize_register', 11 );

function allerj_customizer_register($wp_customize)
{
	$wp_customize->add_section('allerj_color_scheme', array(
		'title'    => __('AllerJ Customizations', 'allerj'),
		'priority' => 120,
	));

	$wp_customize->add_setting(
		'google_fonts_link', 
			array(
				'default'        => 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400&family=Montserrat:wght@300;400&display=swap',
	));
	
	$wp_customize->add_control(
		'google_fonts_link', 
			array(
				'label'      => __('Google Fonts Embed Link', 'allerj'),
				'section'    => 'allerj_color_scheme',
	));
	
	$wp_customize->add_setting('allerj_devmode', array(
		'default'        => 'dev',
	));
	
	$wp_customize->add_control('allerj_color_scheme', array(
		'label'      => __('Activate Development Mode', 'allerj'),
		'section'    => 'allerj_color_scheme',
		'settings'   => 'allerj_devmode',
		'type'       => 'radio',
		'choices'    => array(
			'dev' => 'Dev Mode',
			'prod' => 'Production Mode',
		),
	));
	
		
	$wp_customize->add_setting('allerj_navigation', array(
		'default'        => 'show',
	));
	
	$wp_customize->add_control('allerj_navigation', array(
		'label'      => 'Sitewide Default Navigation Bar',
		'section'    => 'allerj_color_scheme',
		'settings'   => 'allerj_navigation',
		'type'       => 'radio',
		'choices'    => array(
			'show' => 'Show Navigation',
			'hide' => 'Hide Navigation',
		),
	));
	
}
add_action('customize_register', 'allerj_customizer_register');


