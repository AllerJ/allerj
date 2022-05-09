<?php 

add_action( 'widgets_init', 'my_register_sidebars' );
function my_register_sidebars() {
	register_sidebar(array( 
		'name'          => __( 'Banner', 'allerj' ),
		'description'	=> 'This area is above the navigation, typically used to add a site wide banner announcing a sale or other promotion.',
		'id'            => 'banner',
		'before_widget' => '', 
		'after_widget' => '', 
		'before_title' => '', 
		'after_title' => '', 
	));
	
	register_sidebar(array( 
		'name'          => __( 'Popup', 'allerj' ),
		'description'	=> 'Adding a block into this area will activate a sitewide popup to display when a visitor arrives at this site.',
		'id'            => 'popup',
		'before_widget' => '', 
		'after_widget' => '', 
		'before_title' => '', 
		'after_title' => '', 
	));

	register_sidebar(array( 
		'name'          => __( 'Primary Footer', 'allerj' ),
		'description'	=> 'This is the primary footer of the site that is on every every page.',
		'id'            => 'footer_primary',
		'before_widget' => '', 
		'after_widget' => '', 
		'before_title' => '', 
		'after_title' => '', 
	));
	
	register_sidebar(array( 
		'name'          => __( 'Sidebar', 'allerj' ),
		'description'	=> 'This is the sidebar site that is on every every page.',
		'id'            => 'sidebar',
		'before_widget' => '', 
		'after_widget' => '', 
		'before_title' => '', 
		'after_title' => '', 
	));
};
	
?>