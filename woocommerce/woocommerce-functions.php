<?php
/**
 * Woocommerce compatibility
 *
 * @package allerj
 */

/**
 * Declare support
 */

function allerj_woocommerce_scripts() {
	wp_enqueue_style( 'woo-icons', get_stylesheet_directory_uri().'/woocommerce/css/ionicons.min.css', array(), null );
	wp_enqueue_style( 'woo-css', get_stylesheet_directory_uri().'/woocommerce/css/woocommerce.css', array(), null );
	wp_enqueue_script('woo-js', get_stylesheet_directory_uri().'/woocommerce/js/woocommerce.js', array(), null, true);

}
add_action( 'wp_enqueue_scripts', 'allerj_woocommerce_scripts' );

function allerj_wc_support() {

	add_theme_support( 'woocommerce', array(
		'gallery_thumbnail_image_width' => 600,
	) );
	add_theme_support( 'wc-product-gallery-lightbox' );
}
add_action( 'after_setup_theme', 'allerj_wc_support' );

/**
 * Add and remove actions
 */
function allerj_woocommerce_actions() {

	$fullwidth_archives = get_theme_mod( 'allerj_fullwidth_shop_archives', 0 );

	//Theme wrappers
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

	add_action('woocommerce_before_main_content', 'allerj_wc_wrapper_start', 10);
	add_action('woocommerce_after_main_content', 'allerj_wc_wrapper_end', 10);
	
	
	//Custom pricing and modal opener
	add_action( 'woocommerce_after_shop_loop_item_title', 'allerj_loop_pricing_button' );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 11 );
	//Inner loop product wrapper
	add_action( 'woocommerce_before_shop_loop_item', 'allerj_wrap_loop_product_content_before', 9 );
	add_action( 'woocommerce_after_shop_loop_item', 'allerj_wrap_loop_product_content_after', 6 );
	//Remove all WC styling
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	//Add classes to loop products
	add_filter( 'post_class', 'allerj_wc_loop_classes' );
	//Move ratings before title
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_rating', 9 );
	//Remove sidebar on single products
	if ( is_single() || ( is_archive() && $fullwidth_archives ) ) {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
	}	
	//Single product wrappers
	add_action( 'woocommerce_before_single_product_summary', 'allerj_wrap_single_product_before', 1 );
	add_action( 'woocommerce_after_single_product_summary', 'allerj_wrap_single_product_after', 1 );
	//Single product gallery and details wrappers
	add_action( 'woocommerce_before_single_product_summary', 'allerj_wrap_single_product_gallery_before', 1 );
	add_action( 'woocommerce_before_single_product_summary', 'allerj_wrap_single_product_gallery_after', 999 );
	add_action( 'woocommerce_after_single_product_summary', 'allerj_wrap_single_product_details_after', 0 );
	//Single gallery thumbs navigation
	add_action( 'woocommerce_before_single_product_summary', 'allerj_single_gallery_nav', 21 );
	//Quantity buttons
	add_action( 'woocommerce_single_product_summary', 'allerj_qty_buttons', 31 );
	//Remove page title from archives
	add_filter( 'woocommerce_show_page_title', '__return_false' );
	//Remove breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	//Shop results and ordering wrappers
	add_action( 'woocommerce_before_shop_loop', 'allerj_before_shop_results', 19 );
	add_action( 'woocommerce_before_shop_loop', 'allerj_after_shop_results', 21 );
	add_action( 'woocommerce_before_shop_loop', 'allerj_before_shop_sorting', 29 );
	add_action( 'woocommerce_before_shop_loop', 'allerj_after_shop_sorting', 31 );
	//Cart
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
	//Relatd products
	if ( get_theme_mod( 'allerj_wcs_single_hide_related' ) ) {
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

}
add_action( 'wp', 'allerj_woocommerce_actions' );

/**
 * Theme wrappers
 */
function allerj_wc_wrapper_start() {
	$cols = 'col-md-9';
	echo '<div class="container-fluid mt-5">';
		echo '<div class="row justify-content-md-center">';
			echo '<div id="primary" class="content-area ' . $cols . '">';
				echo '<main id="main" class="site-main" role="main">';
}

function allerj_wc_wrapper_end() {
				echo '</main>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}

/**
 * Helper function to check if current page is WC archive
 */
function allerj_wc_archive_check() {
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Adds classes to Woocommerce products in the loop
 */
function allerj_wc_loop_classes( $classes ) {

	$loop_check = allerj_wc_archive_check();
	$columns_no = get_theme_mod( 'allerj_columns_number', '4' );

	if ( $loop_check ) {
		if ( $columns_no == '4' ) {
			$classes[] = 'col-xs-6 col-sm-6 col-md-3 shop-4-cols';
		} elseif ( $columns_no == '3' ) {
			$classes[] = 'col-xs-6 col-sm-6 col-md-4 shop-3-cols';
		} elseif ( $columns_no == '2' ) {
			$classes[] = 'col-xs-6 col-sm-6 col-md-6 shop-2-cols';
		}
	}
	return $classes;
}

/**
 * Add inner wrapper for loop products
 */

function allerj_wrap_loop_product_content_before() {
	echo '<div class="product-inner">';
}
function allerj_wrap_loop_product_content_after() {
	echo '</div>';
}

/**
 * Wrapper for single products
 */
function allerj_wrap_single_product_before() {
	echo '<div class="product-detail content">';
	echo 	'<div class="row">';

}
function allerj_wrap_single_product_after() {
	echo 	'</div>';	
	echo '</div>';
}

/**
 * Wrapper for single product galleries and product details
 */
function allerj_wrap_single_product_gallery_before() {

	$product_layout = get_theme_mod( 'allerj_product_layout', 'product-layout-1');

	global $product, $post;
	$gallery_ids = $product->get_gallery_image_ids();

	$gallery = empty( $gallery_ids ) ? 'no-gallery' : 'has-gallery';	

	if ( $product_layout == 'product-layout-2' ) {
		echo '<div class="col-xs-12 col-sm-12 col-md-5 product-images-wrapper">';
	} else {
		echo '<div class="col-xs-12 col-sm-12 col-md-6 product-images-wrapper ' . $gallery . '">';
	}


}
function allerj_wrap_single_product_gallery_after() {
	echo '</div>';
	//Spacer
	echo '<div class="col-xs-12 col-sm-12 col-md-1"></div>';
	//Open product details wrapper
	$product_layout = get_theme_mod( 'allerj_product_layout', 'product-layout-1');

	if ( $product_layout == 'product-layout-2' ) {
		echo '<div class="col-xs-12 col-sm-12 col-md-6 product-detail-summary sticky-element">';	
	} else {
		echo '<div class="col-xs-12 col-sm-12 col-md-5 product-detail-summary">';		
	}


}
function allerj_wrap_single_product_details_after() {
	echo '</div>';
}

/**
 * Pricing and modal opener on loop products
 */
function allerj_loop_pricing_button() {
	echo '<div class="product-price-button">';
	echo 	'<span class="product-price">';
			woocommerce_template_loop_price();
	echo 	'</span>';
	echo 	'<div class="product-button">';
	echo 		'<a href="#modal-quickview" class="product-quickview">' . esc_html__( 'Show more', 'allerj' ) . '</a>';
	echo 	'</div>';
	echo '</div>';
	
	$hide_modal = get_theme_mod( 'allerj_disable_quickview' );
	if ( !$hide_modal ) {
		get_template_part( 'template-parts/woocommerce', 'modal' );
	}
}

/**
 * Single gallery thumbs navigation
 */
function allerj_single_gallery_nav() {

	$product_layout = get_theme_mod( 'allerj_product_layout', 'product-layout-1' );

	global $product, $post;
	$gallery_ids = $product->get_gallery_image_ids();


	if ( ( $product_layout == 'product-layout-2' ) || empty( $gallery_ids ) ) {
		return;
	}

	if ( ( $product_layout == 'product-layout-1' ) || ( $product_layout == 'product-layout-4' ) ) {
		$vertical 	= 'true';
	} else {
		$vertical = 'false';
	}


	?>
	<div class="product-thumbnails row" data-vertical="<?php echo esc_attr( $vertical ); ?>">
	<?php
		$post_id = $post -> ID;
		$post_thumb = get_post_thumbnail_id( $post_id );
		echo '<div class="slick-slide">' . wp_get_attachment_image( $post_thumb, 'medium' ) . '</div>';
		foreach( $gallery_ids as $gallery_id ) {
			echo '<div class="slick-slide">' . wp_get_attachment_image( $gallery_id, 'medium' ) . '</div>';
		} 							
	?>
	</div>	
	<?php
}

/**
 * Increase/decrease quantity buttons
 */
function allerj_qty_buttons() {
	echo '<a href="#" class="q-plus add"><i class="ion-plus"></i></a>
		 <a href="#" class="q-min min"><i class="ion-minus"></i></a>';
}

/**
 * Update cart
 */
function allerj_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<?php $cart_content = WC()->cart->cart_contents_count; ?>

	<span class="cart-count">(<?php echo intval($cart_content); ?>)</span>

	<?php
	$fragments['.cart-count'] = ob_get_clean();


	ob_start();
	?>

	<div class="cart-mini-wrapper__inner"><?php woocommerce_mini_cart(); ?></div>

	<?php
	$fragments['.cart-mini-wrapper__inner'] = ob_get_clean();	
	
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'allerj_header_add_to_cart_fragment' );

/**
 * Shop results and ordering wrappers
 */
function allerj_before_shop_results() {
	echo '<div class="shop-filter-toolbar"><div class="row"><div class="hidden-xs col-sm-6 col-md-9"><span class="product-result-count">';
}
function allerj_after_shop_results() {
	echo '</span></div>';
}
function allerj_before_shop_sorting() {
	echo '<div class="col-xs-12 col-sm-6 col-md-3 text-right"><div class="product-ordering"><div class="select-wrapper">';
}
function allerj_after_shop_sorting() {
	echo '</div></div></div></div></div><!-- /.shop-filter-toolbar -->';
}



function allerj_before_checkout_details() {
	echo '<div class="col-xs-12 col-sm-12 col-md-7 col-customer-detail">';
}
add_action( 'woocommerce_checkout_before_customer_details', 'allerj_before_checkout_details' );

function allerj_after_checkout_details() {
	echo '</div><div class="col-xs-12 col-sm-12 col-md-5 col-review-order">';
}
add_action( 'woocommerce_checkout_after_customer_details', 'allerj_after_checkout_details' );



function allerj_before_checkout_order() {
	echo '';
}
add_action( 'woocommerce_checkout_before_order_review', 'allerj_before_checkout_order' );

function allerj_after_checkout_order() {
	echo '</div>';
}
add_action( 'woocommerce_checkout_after_order_review', 'allerj_after_checkout_order' );



function allerj_wc_category_description ($category) {
	$id 			= $category->term_id;
	$term 			= get_term( $id, 'product_cat' );
	$description 	= $term->description;
	echo '<div class="product-category-desc"><p>' . $description . '</p></div>';
}
add_action( 'woocommerce_after_subcategory_title', 'allerj_wc_category_description', 11 );

/**
 * Number of products
 */
function allerj_woocommerce_products_number() {

	$number  = get_theme_mod( 'allerj_archives_products_no', '10' );

	return $number;
}
add_filter( 'loop_shop_per_page', 'allerj_woocommerce_products_number', 20 );



/**
 * Breadcrumbs
 */
function allerj_breadcrumb_wrapper() {
	if ( is_product() ) { ?>
	<div class="page-header">
		<div class="container">
			<div class="page-breadcrumbs clearfix">
			<?php woocommerce_breadcrumb(); ?>
			</div>
		</div>
	</div>
	<?php
	}
}
add_action( 'allerj_before_container', 'allerj_breadcrumb_wrapper' );

if ( ! function_exists( 'attribute_slug_to_title' ) ) {
	function attribute_slug_to_title( $attribute ,$slug ) {
		global $woocommerce;
		if ( taxonomy_exists( esc_attr( str_replace( 'attribute_', '', $attribute ) ) ) ) {
			$term = get_term_by( 'slug', $slug, esc_attr( str_replace( 'attribute_', '', $attribute ) ) );
			if ( ! is_wp_error( $term ) && $term->name )
				$value = $term->name;
		} else {
			$value = apply_filters( 'woocommerce_variation_option_name', $value );
		}
		return $value;
	}
}

add_action('woocommerce_before_add_to_cart_button', 'custom_product_attributes');

function custom_product_attributes() {
?>
<style>
	.thumbnail {
		width: 6rem;
		height: auto;
		border: solid 1px #c0c0c0;
		padding: .15rem;
		margin: 1rem 1rem 1rem 0;
	}
	.color_option {
		width: 3rem !important;
	}
	.active {
		background-color: red;
	}
</style>
<script type="text/javascript"> 

function updateSelect(input, option, imageClass){	 	
	jQuery('input[name="'+input+'"]').val(option);
	jQuery('.'+imageClass).removeClass('active');
	jQuery('#option_'+option).addClass('active');
}

</script>
<?php
	global $product;
	$attributes = $product->get_attributes();
	if ( ! $attributes ) {
		return;
	}
	if ($product->is_type( 'variable' )) {
		return;
	}
	echo '<div class="wc-prod-attributes"><h4>Options</h4>';
	foreach ( $attributes as $attribute ) {
		$attribute_data = $attribute->get_data();
		$attribute_terms = $attribute_data['options'];
		$name = $attribute_data['name'];
		$label = wc_attribute_label($name);
?>
<div class="wc-prod-single-attribute">
	<strong><?= $label; ?></strong>
	<br>
<?php 

$options_type = explode('-', $name);

if( end($options_type) == 'colors' ) {
	$thumbnail_class = 'color_option';
} else {
	$thumbnail_class = '';
}

foreach ( $attribute_terms as $pa ): ?>
	<img 
		id="option_<?= $pa; ?>" 
		src="<?= z_taxonomy_image_url($pa); ?>" 
		class="<?= $thumbnail_class; ?> thumbnail <?= $name; ?>"
		onclick="updateSelect('attribute[<?= $attribute_data['id']; ?>]', '<?= $pa; ?>', '<?= $name; ?>')">
<?php endforeach; ?>
	<br>
	<input type="hidden" class="hidden" name="attribute[<?= $attribute_data['id']; ?>]" id="attribute[<?= $attribute_data['id']; ?>]" value="">	
</div>
<hr>
<?php
	}
	echo '</div>';
}
// add_action( 'woocommerce_add_cart_item_data', 'save_in_cart_my_custom_product_field', 10, 2 );
// 
// function save_in_cart_my_custom_product_field( $cart_item_data, $product_id ) {
// 	if( isset( $_POST['attribute'] ) ) {
// 		$cart_item_data[ 'attribute' ] = $_POST['attribute'];
// 		$cart_item_data['unique_key'] = md5( microtime().rand() );
// 		WC()->session->set( 'custom_data', $_POST['attribute'] );
// 	}
// 	return $cart_item_data;
// }
// 
// add_filter( 'woocommerce_get_item_data', 'render_custom_field_meta_on_cart_and_checkout', 10, 2 );
// 
// function render_custom_field_meta_on_cart_and_checkout( $cart_data, $cart_item ) {
// 
// 	$custom_items = array();
// 	if( !empty( $cart_data ) )	$custom_items = $cart_data;
// 
// 	$custom_items[] = array(
// 		'name'      => __( 'Item', 'woocommerce' ),
// 		'value'     => $cart_item['product_id'],
// 		'display'   => $cart_item['data']->name . '<br> SKU: ' . $cart_item['data']->sku,
// 	);
// 	
// 	foreach ($cart_item['attribute'] as $key => $value) {
// 
// 		$key_label = wc_attribute_label(wc_attribute_taxonomy_name_by_id($key));
// 		$term_name = get_term( $value )->name;
// 
// 		$custom_items[] = array(
// 			'name'      => __( $key_label, 'woocommerce' ),
// 			'value'     => $value,
// 			'display'   => $term_name,
// 		);	
// 	}
// 	return $custom_items;
// }

// add_action( 'woocommerce_add_order_item_meta', 'tshirt_order_meta_handler', 10, 3 );
// function tshirt_order_meta_handler( $item_id, $cart_item, $cart_item_key ) {
// 	$custom_field_value = $cart_item['attribute'];
// 
// 	if( ! empty($custom_field_value) )
// 
// 		foreach ($cart_item['attribute'] as $key => $value) {
// 			$key_name = wc_attribute_taxonomy_name_by_id($key);
// 			$term_name = get_term( $value )->name;
// 			wc_update_order_item_meta( $item_id, $key_name, $term_name );
// 		}
// 	;
// }



add_filter( 'woocommerce_add_cart_item_data', function( $cart_item_data, $product_id, $variation_id )
{
	if ( $variation_id ){
		$product_id = $variation_id;
	}
	
	if( isset( $_REQUEST[ 'attribute' ] ) )
	{
		$meta_list_arr = $_REQUEST[ 'attribute' ];
		foreach($meta_list_arr as $key => $value)
		{
				$key_name = wc_attribute_label(wc_attribute_taxonomy_name_by_id($key));
				$term_name = get_term( $value )->name;		
				$cart_item_data['meta'][$key_name] = $term_name;
		}
	}
	
	return $cart_item_data;
	
}, 5, 3 );



add_filter( 'woocommerce_get_cart_item_from_session', function( $cart_item, $values )
{
	if ( isset( $values['meta'] ) ) {
		$cart_item['meta'] = $values['meta'];
	}

	return $cart_item;
}, 11, 2 );
	
// Adding to the cart information about additional fields
add_filter( 'woocommerce_get_item_data', function( $item_data, $cart_item )
{
	if(isset($cart_item['meta'])) 
	{
		foreach( $cart_item['meta'] as $label => $value ) 
		{
			$item_data[] = array(
				'key'   => $label,
				'value' => $value,
			);    
		}
	}
	
	return $item_data;
}, 11, 2 );

// Adding additional product information to the order
add_action( 'woocommerce_checkout_create_order_line_item', function( $item, $cart_item_key, $values, $order )
{
	if ( isset( $values['meta'] ) )
	{
		foreach( $values['meta'] as $label => $value )
		{
			$item->add_meta_data( $label, $value, true );
		}
	}
}, 10, 4 );