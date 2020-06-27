<?php
/**
 * Add WooCommerce support
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'understrap_woocommerce_support' );
if ( ! function_exists( 'understrap_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce theme support.
	 */
	function understrap_woocommerce_support() {
		add_theme_support( 'woocommerce' );

		// Add New Woocommerce 3.0.0 Product Gallery support.
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );

		// hook in and customizer form fields.
		add_filter( 'woocommerce_form_field_args', 'understrap_wc_form_field_args', 10, 3 );
	}
}

/**
 * First unhook the WooCommerce wrappers
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Then hook in your own functions to display the wrappers your theme requires
 */
add_action( 'woocommerce_before_main_content', 'understrap_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'understrap_woocommerce_wrapper_end', 10 );
if ( ! function_exists( 'understrap_woocommerce_wrapper_start' ) ) {
	function understrap_woocommerce_wrapper_start() {
		$container = get_theme_mod( 'understrap_container_type' );
		echo '<div class="wrapper" id="woocommerce-wrapper">';
		echo '<div class="' . esc_attr( $container ) . '" id="content" tabindex="-1">';
		echo '<div class="row">';
		get_template_part( 'global-templates/left-sidebar-check' );
		echo '<main class="site-main" id="main">';
	}
}
if ( ! function_exists( 'understrap_woocommerce_wrapper_end' ) ) {
	function understrap_woocommerce_wrapper_end() {
		echo '</main><!-- #main -->';
		get_template_part( 'global-templates/right-sidebar-check' );
		echo '</div><!-- .row -->';
		echo '</div><!-- Container end -->';
		echo '</div><!-- Wrapper end -->';
	}
}


/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null   $value Not in use.
 *
 * @return mixed
 */
if ( ! function_exists( 'understrap_wc_form_field_args' ) ) {
	function understrap_wc_form_field_args( $args, $key, $value = null ) {
		// Start field type switch case.
		switch ( $args['type'] ) {
			/* Targets all select input type elements, except the country and state select input types */
			case 'select':
				// Add a class to the field's html element wrapper - woocommerce
				// input types (fields) are often wrapped within a <p></p> tag.
				$args['class'][] = 'form-group';
				// Add a class to the form input itself.
				$args['input_class']       = array( 'form-control', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
					// Add custom data attributes to the form input itself.
				);
				break;
			// By default WooCommerce will populate a select with the country names - $args
			// defined for this specific input type targets only the country select element.
			case 'country':
				$args['class'][]     = 'form-group single-country';
				$args['label_class'] = array( 'control-label' );
				break;
			// By default WooCommerce will populate a select with state names - $args defined
			// for this specific input type targets only the country select element.
			case 'state':
				// Add class to the field's html element wrapper.
				$args['class'][] = 'form-group';
				// add class to the form input itself.
				$args['input_class']       = array( '', 'input-lg' );
				$args['label_class']       = array( 'control-label' );
				$args['custom_attributes'] = array(
					'data-plugin'      => 'select2',
					'data-allow-clear' => 'true',
					'aria-hidden'      => 'true',
				);
				break;
			case 'password':
			case 'text':
			case 'email':
			case 'tel':
			case 'number':
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'textarea':
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
			case 'checkbox':
				$args['label_class'] = array( 'custom-control custom-checkbox' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			case 'radio':
				$args['label_class'] = array( 'custom-control custom-radio' );
				$args['input_class'] = array( 'custom-control-input', 'input-lg' );
				break;
			default:
				$args['class'][]     = 'form-group';
				$args['input_class'] = array( 'form-control', 'input-lg' );
				$args['label_class'] = array( 'control-label' );
				break;
		} // end switch ($args).
		return $args;
	}
}

if ( ! is_admin() && ! function_exists( 'wc_review_ratings_enabled' ) ) {
	/**
	 * Check if reviews are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_reviews_enabled() {
		return 'yes' === get_option( 'woocommerce_enable_reviews' );
	}

	/**
	 * Check if reviews ratings are enabled.
	 *
	 * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
	 *
	 * @return bool
	 */
	function wc_review_ratings_enabled() {
		return wc_reviews_enabled() && 'yes' === get_option( 'woocommerce_enable_review_rating' );
	}
}

if ( ! function_exists( 'barbeque_woocommerce_cart_link_fragment' ) ) {
	function barbeque_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		$count = WC()->cart->cart_contents_count;
		if ( $count >= 0 ) {
			?>
			<a class="minicart-trigger" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><i class="zmdi zmdi-shopping-basket"></i></a>
			<div class="shop__qun">
				<span><?php echo esc_html( $count ); ?></span>
			</div>
		<?php }

		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'barbeque_woocommerce_cart_link_fragment' );

function bbq_validate_custom_field( $passed, $product_id, $quantity ) {
	if( empty( $_POST['sauce'] ) ) {
	// Fails validation
	$passed = false;
	wc_add_notice( __( 'Please pick the sauce', 'cfwc' ), 'error' );
	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'bbq_validate_custom_field', 10, 3 );

add_action( 'woocommerce_before_add_to_cart_button', 'add_sauce_option_field' );
function add_sauce_option_field() {
	$args = array(
		'numberposts'		=> -1, // -1 is for all
		'post_type'		=> 'sauce', // or 'post', 'page'
		'orderby' 		=> 'ID', // or 'date', 'rand'
		'order' 		=> 'ASC', // or 'DESC'
	);
	// Get the posts
	$myposts = get_posts($args);

	// If there are posts
	if($myposts):
		echo "<div class=container><div class=row><h4 class='purchase'>Purchase</h4>";
		// Loop the posts
		foreach ($myposts as $mypost):?>
			<div class="col-12">
				<input type="radio" name="sauce" value="<?php echo $mypost->ID ?>"> <?php echo get_the_title($mypost->ID) ?>
			</div>
		<?php endforeach; 
		echo "</div></div>";
		wp_reset_postdata();
	endif;
}

// Adding the custom field to as custom data for this cart item in the cart object
add_filter( 'woocommerce_add_cart_item_data', 'save_custom_fields_data_to_cart', 10, 2 );
function save_custom_fields_data_to_cart( $cart_item_data, $product_id ) {
	$bool = false;
	$data = array();
	if( isset( $_REQUEST['sauce'] ) ) {
		$cart_item_data['custom_data']['sauce'] = $_REQUEST['sauce'];
		// below statement make sure every add to cart action as unique line item
		$cart_item_data['custom_data']['unique_key'] = md5( microtime().rand() );
		WC()->session->set( 'custom_data', $data );
	}
	return $cart_item_data;
}
/* Display custom data on cart and checkout page.
add_filter( 'woocommerce_get_item_data', 'get_item_data' , 25, 2 );
function get_item_data ( $cart_data, $cart_item ) {

    if( ! empty( $cart_item['custom_data'] ) ){
        $values =  array();
        foreach( $cart_item['custom_data'] as $key => $value )
            if( $key != 'unique_key' ){
                $values[] = $value;
            }
		$values = implode( ', ', $values );
		$sauce_name = get_post_meta( $values, 'sauce_name', true );
		$additional_price = get_post_meta( $values, 'additional_price', true );
		$text = $sauce_name."<i> + IDR".number_format($additional_price,0,",",".")."</i>";
        $cart_data[] = array(
            'name'    => "Sauce",
            'display' => $text
        );
    }

    return $cart_data;
}*/

// Add order item meta.
add_action( 'woocommerce_add_order_item_meta', 'add_order_item_meta' , 10, 3 );
function add_order_item_meta ( $item_id, $cart_item, $cart_item_key ) {
    if ( isset( $cart_item[ 'custom_data' ] ) ) {
        $values =  array();
        foreach( $cart_item[ 'custom_data' ] as $key => $value )
            if( $key != 'unique_key' ){
                $values[] = $value;
            }
        $values = implode( ', ', $values );
        wc_add_order_item_meta( $item_id, 'sauces', $values );
    }
}

// Changing the cart item price based on custom field calculation
add_action( 'woocommerce_before_calculate_totals', 'change_product_name_and_price', 10, 1 );
function change_product_name_and_price( $cart ) {

	if ( is_admin() && ! defined( 'DOING_AJAX' ) )
		return;

	if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
		return;

	// Iterating though cart items
	foreach ( $cart->get_cart() as $cart_item ) {
		// Continue if we get the custom data for the current cart item
		if( ! empty( $cart_item['custom_data'] ) ){
			// Get the custom field "added price" value
			$sauceID =$cart_item['custom_data']['sauce'];
			$added_price = get_post_meta($sauceID, 'additional_price', true );
			$sauce_name = get_the_title($sauceID);
			$sauce_meta_name = get_post_meta( $sauceID, 'sauce_name', true );
			$text = '<b>Sauce : </b>'.$sauce_meta_name."<i> + IDR".number_format($added_price,0,",",".")."</i>";
			// The WC_Product object
			$product = $cart_item['data'];
			//get original product name
			$original_name = method_exists( $product, 'get_name' ) ? $product->get_name() : $product->post->post_title;
			//set new product name
			$newProductName = $original_name."<br />".$text;
			if( method_exists( $product, 'set_name' ) )
				$product->set_name( $newProductName );
			else
				$product->post->post_title = $newProductName;
			// Get the price (WooCommerce versions 2.5.x to 3+)
			$product_price = method_exists( $product, 'get_price' ) ? floatval($product->get_price()) : floatval($product->price);
			// New price calculation
			$new_price = $product_price + $added_price;
			// Set the calculeted price (WooCommerce versions 2.5.x to 3+)
			method_exists( $product, 'set_price' ) ? $product->set_price( $new_price ) : $product->price = $new_price;
		}
	}
}

/** start woocommerce hook for front page */

/** Archieve Page */
remove_action('woocommerce_sidebar','woocommerce_get_sidebar');
remove_action('woocommerce_before_main_content','woocommerce_breadcrumb',20);
remove_action('woocommerce_before_shop_loop','woocommerce_result_count',20);
remove_action('woocommerce_before_shop_loop','woocommerce_catalog_ordering',30);

add_action ('woocommerce_before_shop_loop_item','wrap_thumbnails_with_div',5);
add_action('loop_start','open_product_loop_container_row',1);
function open_product_loop_container_row(){
	echo "<div class='container px-0'><div class='row'>";
}
function wrap_thumbnails_with_div(){
	echo "<div class='col-md-6 col-sm-12 px-0'>";
}

add_action('woocommerce_shop_loop_item_title', 'close_div_thumb_and_wrap_title',4);
function close_div_thumb_and_wrap_title(){
	echo "</div><!-- close thumnails with div --><div class ='col-sm-12 col-md-6 pl-sm-0 pl-md-3'>";
}
add_action('woocommerce_after_shop_loop_item_title','the_excerpt',11);

remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart');


add_action('loop_end','close_product_loop_container_row',5);
function close_product_loop_container_row(){
	echo "</div><!-- close container --></div><!-- close row -->";
}

/** single Product */
add_action('woocommerce_before_add_to_cart_form','add_pack_list');
function add_pack_list(){
	global $product;
  	$product_id=$product->id;
	echo "<h3>Content</h3><ul>";
	$args = array(
		'numberposts'		=> -1, // -1 is for all
		'post_type'		=> 'ingredient', // or 'post', 'page'
		'orderby' 		=> 'title', // or 'date', 'rand'
		'order' 		=> 'ASC', // or 'DESC'
	);
	// Get the posts
	$myposts = get_posts($args);

	// If there are posts
	if($myposts):
		// Loop the posts
		foreach ($myposts as $mypost):
			$idIngredient = $mypost->ID;
			$title = get_the_title($idIngredient);
			$weight = get_post_meta($product_id,$idIngredient,true);
			if(!empty($weight) && $weight>0){
				echo '<li>'.$title.'</li>';
			}
		endforeach;
	endif;
	echo "</ul>";
}


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
function woocommerce_template_product_description() {
	woocommerce_get_template( 'single-product/tabs/description.php' );
}
add_action( 'woocommerce_after_single_product', 'woocommerce_template_product_description', 20 );
remove_action('woocommerce_after_single_product_summary','woocommerce_upsell_display',15);
remove_action('woocommerce_after_single_product_summary','woocommerce_output_related_products',20);