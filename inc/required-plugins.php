<?php
/**
 * J. Aller Require Plugins
 *
 * This makes installing the J. Aller theme easier by reminding of what
 * plugins need to be installed.
 *
 * @package j_aller
 */

add_action( 'admin_notices', 'allerj_theme_dependencies' );
function allerj_theme_dependencies() {
	$user_id = get_current_user_id();
	if ( !get_user_meta( $user_id, 'allerj_plugin_notice_dismissed' ) ) {

		if ( !defined( 'BODHI_SVGS_PLUGIN_PATH' ) ) {
			$allerj_plugins[] = array(
				'name'		=> 'SVG Support',
				'slug'		=> 'svg-support',
				'required'	=> true
			);
		}	
		
		if ( !defined( 'SLIM_SEO_VER' ) ) {
			$allerj_plugins[] = array(
				'name'		=> 'Slim SEO',
				'slug'		=> 'slim-seo',
				'required'	=> true
			);
		}	
		if ( !defined( 'WP_BOOTSTRAP_BLOCKS_PLUGIN_FILE' ) ) {
			$allerj_plugins[] = array(
				'name'		=> 'Bootstrap Blocks',
				'slug'		=> 'wp-bootstrap-blocks',
				'required'	=> true
			);
		}	
		if ( !defined( 'GETWID_PLUGIN_FILE' ) ) {
			$allerj_plugins[] = array(
				'name'		=> 'Getwid',
				'slug'		=> 'getwid',
				'required'	=> true
			);
		}	
		if ( !defined( 'WEBPEXPRESS_PLUGIN' ) ) {
			$allerj_plugins[] = array(
				'name'		=> 'WebP Express',
				'slug'		=> 'webp-express',
				'required'	=> true
			);
		}
		if ( !defined( 'Z_PLUGIN_URL' ) ) {
			$allerj_plugins[] = array(
				'name'		=> 'Categories Images',
				'slug'		=> 'categories-images',
				'required'	=> false
			);
		}	
		
		if(count($allerj_plugins) >= 1 ) {
			
			echo '
			<div class="notice notice-info is-dismissible">
				<p>The J. Aller theme works best with the following plugins 
			';
			
			foreach($allerj_plugins as $plugin) {
				$bare_url = "/wp-admin/plugin-install.php?tab=plugin-information&plugin=".$plugin['slug']."&TB_iframe=true&width=500&height=450";
				$complete_url = wp_nonce_url( $bare_url);
				
				$required_class = $plugin['required'] ? ' wp-ui-highlight ' : '';
				$required_button_class = $plugin['required'] ? ' button-primary ' : 'button-secondary';
				$required_text = $plugin['required'] ? ' - <span class="wp-ui-text-highlight">Required</span> ' : ' - Recomended ';
				
				echo '
				<p><a href="'.$complete_url.'" class="button thickbox open-plugin-details-modal '.$required_button_class.'">Install</a> &nbsp; &nbsp;'.$plugin['name'].$required_text.'</p>';
			}
			
			echo '
				</p>
				<a href="?allerj-plugin-dismissed" type="button" class="notice-dismiss">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</a>
			</div>';
		}
		
	} 

}

function allerj_plugin_notice_dismissed() {
	$user_id = get_current_user_id();
	if ( isset( $_GET['allerj-plugin-dismissed'] ) )
		add_user_meta( $user_id, 'allerj_plugin_notice_dismissed', 'true', true );
}
add_action( 'admin_init', 'allerj_plugin_notice_dismissed' );

?>