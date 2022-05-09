<?php

// Updates the Gutenberg Editor to be fullwidth
add_action('admin_head', 'editor_full_width_gutenberg');

// Includes stylesheet so Gutenberg Editor has theme style
add_action('after_setup_theme', 'misha_gutenberg_css');

// Removes bloated Emoji support used in comments
add_filter('emoji_svg_url', '__return_false');
add_filter('jpeg_quality', function ($arg) {
    return 100;
});
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'print_emoji_detection_script', 7);

// Add tabs and deal with extra lines in the_content()
add_filter('the_content', 'filter_the_content_in_the_main_loop', 20);

// Removes JSON and api support
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('template_redirect', 'rest_output_link_header', 11);

// Removes query strings "?v=123&junk" from static resources
add_action('init', 'remove_query_strings');

// Preload Theme Style Sheet
add_action('wp_head', 'preload_theme_style', 5);

// Disable Application Password https://perishablepress.com/wordpress-disable-application-passwords/
add_filter('wp_is_application_passwords_available', '__return_false');

// Removes RSS Feed
add_action('init', 'my_head_cleanup');
add_action('after_theme_support', 'remove_feed');

// Removes the ability to comment cutting back on spam and extra traffic to site
add_action('init', 'remove_comment_support', 100);
add_action('admin_menu', 'my_remove_admin_menus');
add_action('wp_before_admin_bar_render', 'mytheme_admin_bar_render');
add_action('admin_bar_menu', 'remove_from_admin_bar', 999);

// Remove the javascript for embeding videos. Comment out to
// "It’s super easy to embed videos, images, tweets, audio, and other content into your WordPress site."
add_action('wp_footer', 'my_deregister_scripts');



// Remove Plugin Scripts and styles
add_action('wp_enqueue_scripts', 'custom_dequeue', 9999);
add_action('wp_head', 'custom_dequeue', 9999);

function remove_from_admin_bar($wp_admin_bar)
{
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('updates');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('new-content');
    //$wp_admin_bar->remove_node('site-name');
    //$wp_admin_bar->remove_node('my-account');
    //$wp_admin_bar->remove_node('search');
    //$wp_admin_bar->remove_node('customize');
}
function remove_feed()
{
    remove_theme_support('automatic-feed-links');
}
function my_head_cleanup()
{
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}
function my_remove_admin_menus()
{
    remove_menu_page('edit-comments.php');
    remove_menu_page('edit.php');
}
function remove_comment_support()
{
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
}
function mytheme_admin_bar_render()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('post');
}
function remove_query_strings()
{
    add_filter('script_loader_src', 'remove_query_strings_split', 15);
    add_filter('style_loader_src', 'remove_query_strings_split', 15);
}
function remove_query_strings_split($src)
{
    $output = preg_split("/(&ver|\?ver)/", $src);
    return $output[0];
}
function my_deregister_scripts()
{
    wp_deregister_script('wp-embed');
}
function custom_dequeue()
{
    wp_dequeue_style('wp-bootstrap-blocks-styles');
    wp_deregister_style('wp-bootstrap-blocks-styles');

    wp_dequeue_style('wp-block-library');
    wp_deregister_style('wp-block-library');

    wp_dequeue_style('slick');
    wp_deregister_style('slick');

    wp_dequeue_style('slick-theme');
    wp_deregister_style('slick-theme');

    // wp_dequeue_script( 'getwid-blocks-frontend-js' );
    // wp_deregister_script( 'getwid-blocks-frontend-js' );
}
function preload_theme_style()
{
    $allerj_js = $_ENV['THEME_MODE'] == 'prod' ? 'vendors.js' : 'vendors.php';
    $allerj_css = $_ENV['THEME_MODE'] == 'prod' ? 'theme.css' : 'theme.php';

    echo '<link rel="preload" href="'.get_stylesheet_directory_uri().'/css/'.$allerj_css.'" as="style">
        <link rel="preload" href="'.get_stylesheet_directory_uri().'/js/'.$allerj_js.'" as="script">
        <link rel="preload" href="/wp-includes/js/jquery/jquery.min.js" as="script">
        <link rel="preload" href="/wp-includes/js/jquery/jquery-migrate.min.js" as="script">
';
    echo '<link rel="preload" href="'.
get_theme_mod('google_fonts_link', 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400&family=Montserrat:wght@300;400&display=swap').
'" as="style">
';
    // <link rel="preload" href="/wp-content/plugins/getwid/assets/js/frontend.blocks.js" as="script">
// <link rel="preload" href="/wp-content/plugins/getwid/vendors/fontawesome-free/css/all.min.css" as="style">
// <link rel="preload" href="/wp-content/plugins/getwid/assets/css/blocks.style.css" as="style">
}

add_filter('show_recent_comments_widget_style', '__return_false', 99);

function filter_the_content_in_the_main_loop($content)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (is_singular() && in_the_loop() && is_main_query()) {
        $arr = explode("\n", $content);
        foreach ($arr as $key => $value) {            
            if ($value == "") {
                unset($arr[$key]);
            }
            $arr[$key] = "\t\t\t\t\t\t\t" . $value;
        }
        $x = implode("\n", $arr);
        $html = $x."\n";
        
//         $config = array(
//                    'indent'         => true,
//                    'output-xhtml'   => true,
//                    'wrap'           => 200
//                );
//         
// 
//         $tidy = new tidy();
//         $tidy->parseString($html, $config, 'utf8');
//         $tidy->cleanRepair();
        
        
        $tidy_config = array(
//                             'clean' => true,
                             'output-xhtml' => true,
                             'show-body-only' => true,
                             'indent' => true,
                             'break-before-br' => true,
                             'wrap' => 0,
                            
                             );
        
        $tidy = tidy_parse_string($html, $tidy_config, 'UTF8');
        $tidy->cleanRepair();

        return $tidy;        
    }
    return $content;
}

function editor_full_width_gutenberg()
{
    echo '<style>
            body.gutenberg-editor-page .editor-post-title__block, body.gutenberg-editor-page .editor-default-block-appender, body.gutenberg-editor-page .editor-block-list__block {
                max-width: 100vw;
            }
            .block-editor__container .wp-block {
                max-width: calc(100vw - 300px);
            }
    </style>';
}
function misha_gutenberg_css()
{
    add_theme_support('editor-styles');
    add_editor_style('/css/allerj.css');

}


function update_htaccess()
{
    $doc_root = $_SERVER["DOCUMENT_ROOT"];
    $htaccess = file_get_contents($doc_root.'/.htaccess');
    $theme_mode = get_theme_mod('allerj_devmode', 'dev');
    $rules = "\nSetEnv THEME_MODE ".$theme_mode."\n";

    $new_htaccess = replace_between($htaccess, "###CUSTOM RULES###", "###END CUSTOM RULES###\n", $rules);

    file_put_contents($doc_root.'/.htaccess', $new_htaccess);
}
add_action('customize_save_after', 'update_htaccess');

function replace_between($str, $needle_start, $needle_end, $replacement)
{
    $pos_start = strpos($str, $needle_start);
    $start = $pos_start === false ? 0 : $pos_start + strlen($needle_start);

    $pos_end = strpos($str, $needle_end, $start);
    $end = $start === false ? strlen($str) : $pos_end;

    if ($pos_end == 0 && $pos_start == 0) {
        $replacement = $needle_start.$replacement.$needle_end;
    }
    return substr_replace($str, $replacement, $start, $end - $start);

    return substr_replace($str, $replacement, $start, $end - $start);
}


//REMOVE GUTENBERG BLOCK LIBRARY CSS FROM LOADING ON FRONTEND
function remove_wp_block_library_css(){
wp_dequeue_style( 'wp-block-library' );
wp_dequeue_style( 'wp-block-library-theme' );
wp_dequeue_style( 'global-styles' ); // REMOVE THEME.JSON
}
add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );

remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );

    add_filter( 'render_block', function( $block_content, $block ) {
        if ( $block['blockName'] === 'core/group' ) {
            return $block_content;
        }

        return wp_render_layout_support_flag( $block_content, $block );
    }, 10, 2 );



function allerj_social_buttons($content)
{
    global $post;

    $sb_url = urlencode(get_permalink());
    $sb_title = urlencode(get_the_title());
    $sb_thumb = urlencode(get_the_post_thumbnail_url());

    $twitterURL = 'https://twitter.com/intent/tweet?text='.$sb_title.'&url='.$sb_url.'&via=jdiannedotson';
    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$sb_url;

    if (!empty($sb_thumb)) {
        $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$sb_url.'&media='.$sb_thumb.'&amp;description='.$sb_title;
    } else {
        $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$sb_url.'&description='.$sb_title;
    }
    $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$sb_url.'&media='.$sb_thumb.'&description='.$sb_title;
    $gplusURL ='https://plus.google.com/share?url='.$sb_title.'';

    $content .= '<div class="social-box">Share this post:<div class="social-btn">';
    $content .= '<a class="col-2 sbtn s-twitter" href="'. $twitterURL .'" target="_blank" rel="nofollow"><span>Twitter</span></a>';
    $content .= '<a class="col-2 sbtn s-facebook" href="'.$facebookURL.'" target="_blank" rel="nofollow"><span>Facebook</span></a>';
    $content .= '<a class="col-2 sbtn s-pinterest" href="'.$pinterestURL.'" data-pin-custom="true" target="_blank" rel="nofollow"><span>Pin it on Pinterest</span></a>';
    $content .= '<a class="col-2 sbtn s-linkedin" href="'.$linkedInURL.'" target="_blank" rel="nofollow"><span>LinkedIn</span></a>';
    $content .= '</div></div>';

    return $content;
};
add_shortcode('allerj_social_share', 'allerj_social_buttons');
