<?php
/**
 * J. Aller Track Plugin Activity
 *
 * As of now, this will write to a log file every time a 
 * plugin is added and deleted. Future features will show a place to 
 * write a note about why a plugin was installed.
 *
 * @package j_aller
 */



// TODO: Add Row after plugin row for notes about what the plugin does, why it's needed.
// function add_new_plugin_row( $plugin_file, $plugin_data, $status) {
// 	echo "<tr><th></th><td colspan='3'>View Activate/Deactivate Log for ".$plugin_data['Name']." </tr>";
// }
// add_action('after_plugin_row', 'add_new_plugin_row', 10, 3);
// 

// TODO: Add Link
// add_filter( 'plugin_row_meta', 'custom_plugin_row_meta', 10, 4 );
// function custom_plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) { 
// 	
// 		$new_links = array(
// 				'donate' => '<a href="donation_url" target="_blank">View Activate/Deactivate Log</a>'
// 				);
// 		 
// 		$plugin_meta = array_merge( $plugin_meta, $new_links );
// 	 
// 	return $plugin_meta;
// }




function detect_plugin_activation( $plugin, $network_activation ) {
	$cached = fopen(__DIR__.'/plugin-log.txt', 'a');
	$plugin_log = 'ACTIVATE,' . $plugin . ',' . wp_get_current_user()->display_name . ',' . _date("m/d/Y,h:i:s a", false, 'America/New_York'). '
';
	fwrite($cached, $plugin_log);
	fclose($cached);
}
add_action( 'activated_plugin', 'detect_plugin_activation', 10, 2 );

function detect_plugin_deactivation( $plugin, $network_activation ) {
	$cached = fopen(__DIR__.'/plugin-log.txt', 'a');
	$plugin_log = 'DEACTIVATE,' . $plugin . ',' . wp_get_current_user()->display_name . ',' . _date("m/d/Y,h:i:s a", false, 'America/New_York'). '
';
	fwrite($cached, $plugin_log);
	fclose($cached);
}
add_action( 'deactivated_plugin', 'detect_plugin_deactivation', 10, 2 );

function detect_plugin_delete( $plugin, $deleted ) {
	$cached = fopen(__DIR__.'/plugin-log.txt', 'a');
	$plugin_log = 'DELETED,' . $plugin . ',' . wp_get_current_user()->display_name . ',' . _date("m/d/Y,h:i:s a", false, 'America/New_York'). '
';
	fwrite($cached, $plugin_log);
	fclose($cached);
}
add_action( 'deleted_plugin', 'detect_plugin_delete', 10, 2 );

// TODO TRACK UPDATES
// function action_upgrader_process_complete( $array, $int ) { 
// 	$cached = fopen(__DIR__.'/plugin-log.txt', 'a');
// 	$plugin_log = 'UPGRADE,' . $plugin . ',' . wp_get_current_user()->display_name . ',' . _date("m/d/Y,h:i:s a", false, 'America/New_York'). '
// 	';
// 	fwrite($cached, $plugin_log);
// 	fclose($cached);
// }; 
// https://developer.wordpress.org/reference/hooks/upgrader_process_complete/
// add_action( 'upgrader_process_complete', 'action_upgrader_process_complete', 10, 2 ); 
