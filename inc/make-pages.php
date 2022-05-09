<?php
/**
 * J. Aller Make Pages and Blocks
 *
 * This will make a customized Accessibility Page as well as setting up
 * a footer block.
 *
 * @package j_aller
 */

add_action('init','check_pages_live');

function check_pages_live(){    
    if( get_page_by_title( 'Accessibility Statement' ) == NULL ) {
        $domain = $_SERVER['SERVER_NAME'];
        $content = <<<EOF
        <!-- wp:paragraph -->
        <p>This commitment to accessibility for all begins with this site and our efforts to ensure all functionality and all content is accessible to all visitors.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:paragraph -->
        <p>Our ongoing accessibility effort works towards conforming to the  Web Content Accessibility Guidelines (WCAG) version 2.1, level AA criteria. These guidelines not only help make web content accessible to users with sensory, cognitive and mobility disabilities, but ultimately to all users, regardless of ability.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:paragraph -->
        <p>Our ongoing accessibility efforts work toward making <strong>$domain</strong> as accessible as possible. We welcome comments on how to improve the site’s accessibility for users with disabilities.</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:paragraph {"className":"text-center"} -->
        <p class="text-center"><a class="btn btn-brand" href="/contact">Contact Us With Ideas</a></p>
        <!-- /wp:paragraph -->
EOF;
        create_pages_fly( 'Accessibility Statement', $content , 'page');
    }
    
    if( get_page_by_title( 'Home' ) == NULL ) {
        $domain = $_SERVER['SERVER_NAME'];
        $content = "";
        create_pages_fly( 'Home', $content , 'page');
    }

    if( get_page_by_title( 'footer', OBJECT, 'block_area' ) == NULL ) {
        $year = date("Y"); 
        $content = '<p class="center">©  $year</p>';
        create_pages_fly( 'footer', $content , 'block_area');
    }

    $page = get_page_by_title( 'Sample Page', OBJECT, 'page' );
    wp_delete_post( $page->ID );
    
    $post = get_page_by_title( 'Hello World!', OBJECT, 'post' );
    wp_delete_post( $post->ID );
    
    $homepage = get_page_by_title( 'Home' );
    
    if ( $homepage )
    {
        update_option( 'page_on_front', $homepage->ID );
        update_option( 'show_on_front', 'page' );
    }
}



function create_pages_fly($pageName, $content, $post_type) {
    $createPage = array(
      'post_title'    => $pageName,
      'post_content'  => $content,
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type'     => $post_type,
      'post_name'     => (str_replace(' ', '-', strtolower($pageName)))
    );
    wp_insert_post( $createPage );
}