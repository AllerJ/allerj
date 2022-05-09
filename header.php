<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package j_aller
 */

// Add security headers
Header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
Header("X-Frame-Options: SAMEORIGIN");
Header("X-Content-Type-Options: nosniff");
Header("Content-Security-Policy: block-all-mixed-content");
Header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
Header("Referrer-Policy: no-referrer-when-downgrade");

// Accessibility features
$contrast_cookie = null;
$font_cookie = null;
if( isset( $_COOKIE['contrast-cookie'] ) ){ $contrast_cookie = $_COOKIE['contrast-cookie'];	}
if( isset( $_COOKIE['font-size-cookie'] ) ){ $font_cookie = $_COOKIE['font-size-cookie']; }

$accessibility_class = "";
if($contrast_cookie == 'true') {
	$accessibility_class .= ' accessibility__contrast '.$contrast_cookie;
} else {
	$accessibility_class .= ' no-con';
}
if($font_cookie == 'true') {
	$accessibility_class .= ' accessibility__fontsize';
} else {
	$accessibility_class .= ' no-font';
}
?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?= $accessibility_class; ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php allerj_wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<a class="skip-link screen-reader-text" href="#primary" title="<?php esc_html_e( 'Skip to content', 'allerj' ); ?>"><?php esc_html_e( 'Skip to content', 'allerj' ); ?></a>
	<div class="acctoggle" role="toolbar" aria-label="High Contrast and Font Size Options">
		<noscript>
			<span class="sr-only">You have JavaScript disabled. Please enable JavaScript to use this feature.</span>
		</noscript>
		<div class="acctoggle__ctrl">
			<div class="tool">
				<span>Toggle High Contrast</span>
			</div>
			<button class="acctoggle__contrast">	
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28.7 28.7" role="img" aria-labelledby="high-contrast-svg-title">
					<title id="high-contrast-svg-title">
						Toggle High Contrast	</title>
					<path d="M14.3 0C6.5 0 0 6.5 0 14.3c0 7.9 6.5 14.3 14.3 14.3 7.9 0 14.3-6.5 14.3-14.3C28.7 6.5 22.2 0 14.3 0zm0 3.6c6 0 10.8 4.8 10.8 10.8 0 6-4.8 10.8-10.8 10.8V3.6z"/>
				</svg>
			</button>
		</div>
		<div class="acctoggle__ctrl">
			<div class="tool">
				<span>Toggle Large Font Size</span>
			</div>
			<button class="acctoggle__fontsize">
				<svg viewBox="0 0 24 19" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="large-font-svg-title">
				<title id="large-font-svg-title">
					Toggle Large Font Size</title>
				<path d="M10.667 2.637v15.825H8V2.637H0V0h18.667v2.637h-8zM24 9.231h-9.333v2.637H18v6.594h2.667v-6.594H24V9.231z" fill="currentColor"/>
				</svg>
			</button>
		</div>
	</div>
	<div id="page" class="site">
		<header id="masthead" class="site-header">
<?php dynamic_sidebar( 'banner' ); ?>
<?php
$header_display = get_theme_mod( 'allerj_navigation', 'show');
if($header_display == 'show') {
?>
			<nav class="navbar navbar-expand-md navbar-light bg-light " role="navigation">
				<div class="container">
				
					<div class="site-description">
						<div class="one-third">
							<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#allerj-navbar-collapse-1" aria-controls="allerj-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
						</div>
					</div>

					<?php
					wp_nav_menu( array(
						'menu_id'			=> 'primary-menu',
						'theme_location'    => 'menu-1',
						'depth'             => 2,
						'container'         => 'div',
						'container_class'   => 'collapse navbar-collapse justify-content-center',
						'container_id'      => 'allerj-navbar-collapse-1',
						'menu_class'        => 'nav navbar-nav montserrat ml-0',
						'item_spacing'		=> 'preserve',
						'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
						'walker'            => new WP_Bootstrap_Navwalker(),
					) );
					?>
					
				</div>
			</nav>
<?php
}
?>
		</header>
