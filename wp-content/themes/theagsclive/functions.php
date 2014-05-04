<?php

function MyBreadcrumb() {
    if (!is_home()) {
        echo '<a class="homecrumb" href="';
        echo get_option('home');
        echo '">';
        echo 'Home';;
        echo "</a> >  ";
        if (is_category() || is_single()) {
            the_category(' / ');
            if (is_single()) {
                echo " > ";
                the_title();
            }
        } elseif (is_page()) {
            echo the_title();
        }
    }
}


/*-----------------------------------------------------------------------------------*/  
/*   Disable admin bar for all 
/*-----------------------------------------------------------------------------------*/  

/*
function my_init() {
    if (!is_admin()) {
        // comment out the next two lines to load the local copy of jQuery
        wp_deregister_script('jquery'); 
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, '1.3.2', true); 
        wp_enqueue_script('jquery');
        wp_register_script('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js', false, '1.3.2', true); 
        wp_enqueue_script('jquery-ui');
    }
}
add_action('init', 'my_init');
*/

// removes related products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

//removes woocommerce breadcrumbs
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);

//Removes billing address for virtual products
add_filter( 'woocommerce_checkout_fields' , 'woo_remove_billing_checkout_fields' );
 
// declares theme support for woocommerce
add_theme_support( 'woocommerce' ); 

/**
 * Remove unwanted checkout fields
 *
 * @return $fields array
*/
function woo_remove_billing_checkout_fields( $fields ) {
    
    if( woo_cart_has_virtual_product() == true ) {
	    unset($fields['billing']['billing_company']);
	    unset($fields['billing']['billing_address_1']);
	    unset($fields['billing']['billing_address_2']);
	    unset($fields['billing']['billing_city']);
	    unset($fields['billing']['billing_postcode']);
	    unset($fields['billing']['billing_country']);
	    unset($fields['billing']['billing_state']);
	    unset($fields['billing']['billing_phone']);
	    unset($fields['order']['order_comments']);
	    unset($fields['billing']['billing_address_2']);
	    unset($fields['billing']['billing_postcode']);
	    unset($fields['billing']['billing_company']);
	    unset($fields['billing']['billing_city']);
    }
    
    return $fields;
}
 
/**
 * Check if the cart contains virtual product
 *
 * @return bool
*/
function woo_cart_has_virtual_product() {
  
  global $woocommerce;
  
  // By default, no virtual product
  $has_virtual_products = false;
  
  // Default virtual products number
  $virtual_products = 0;
  
  // Get all products in cart
  $products = $woocommerce->cart->get_cart();
  
  // Loop through cart products
  foreach( $products as $product ) {
	  
	  // Get product ID and '_virtual' post meta
	  $product_id = $product['product_id'];
	  $is_virtual = get_post_meta( $product_id, '_virtual', true );
	  
	  // Update $has_virtual_product if product is virtual
	  if( $is_virtual == 'yes' )
  		$virtual_products += 1;
  }
  
  if( count($products) == $virtual_products )
  	$has_virtual_products = true;
  
  return $has_virtual_products;
 
}




show_admin_bar(false);  

// Post Thumbs
add_theme_support('post-thumbnails');
//

// Excerpt Length
add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
return 76; 
}
//

//remove_filter('the_content', 'wpautop');

// Register Menu
function register_my_menus() {
  register_nav_menus(
    array('header-menu' => __( 'Header Menu' ) )
  );
}
add_action( 'init', 'register_my_menus' );

//


// Strip p tags from img
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');
//


// something about tags
add_filter('the_tags', 'wp32234_add_span_get_the_tag_list');

function wp32234_add_span_get_the_tag_list($list) {
    $list = str_replace('rel="tag">', 'class="details_tag" rel="tag"> #', $list);
    return $list;
}

// add royal slider skin
add_filter('new_royalslider_skins', 'new_royalslider_add_custom_skin', 10, 2);
function new_royalslider_add_custom_skin($skins) {
      $skins['agscslider'] = array(
           'label' => 'The AGSC',
           'path' => 'http://theagsc.com/wp-content/themes/theagsclive/agsc-slider/agsc-slider.css'
      );
      return $skins;
}

/*
if ( ! is_single()) { 

// define hooks for WooCommerce
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<div class="content">';
}

function my_theme_wrapper_end() {
  echo '</div>';
}
add_theme_support( 'woocommerce' );

}
*/



// Ensure cart contents update when products are added to the cart via AJAX 
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
 
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start();
	
	?>
	<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="cart-contents" title="Go to Cart">
                      <span class="account-name icon-cart">(<?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?>)<span class="icon-down"></span></span></a>
	<?php
	
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
	
}


// empty woocommerce cart
add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
  global $woocommerce;
	
	if ( isset( $_GET['empty-cart'] ) ) {
		$woocommerce->cart->empty_cart(); 
	}
}



//make slugs/permalinks etc work correctly with custom post types and taxonomies
function filter_post_type_link($link, $post)
{
    if ($post->post_type != 'work')
        return $link;

    if ($cats = get_the_terms($post->ID, 'type'))
        $link = str_replace('%type%', array_pop($cats)->slug, $link);
    return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2);



// adds thumbnail to admin area, so you can see which posts have a thumbnail more easily
add_filter('manage_posts_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);
function posts_columns($defaults){
    $defaults['riv_post_thumbs'] = __('Thumbs');
    return $defaults;
}
function posts_custom_columns($column_name, $id){
        if($column_name === 'riv_post_thumbs'){
        echo the_post_thumbnail( 'thumbnail' );
    }
}


// removes agsc slider styles from royal slider - it was a bit annoying
add_action('wp_print_styles', 'deregister_style');
    function deregister_style(){
wp_deregister_style("agscslider-css");
}








// This tip solves the issue of WooCommerce stylesheets being loaded after your custom ones.
    
// Which means that if you need to override any WooCommerce styling you have to add '!important'
// after each CSS declaration.

// But changing the order of how the sytlesheets are loaded means your custom stylesheet cascades properly 
// and overrides WooCommerce styles.

function my_custom_scripts() {
    
    // place wooCommerce styles before our main stlesheet
	if ( class_exists('woocommerce') ) {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_enqueue_style('woocommerce_frontend_styles', plugins_url() .'/woocommerce/assets/css/woocommerce.css');
	}
    
		   wp_enqueue_style('reset', 'http://dvclmn.com/css_reset.css');
		   wp_enqueue_style('style', get_stylesheet_directory_uri().'/style.less');

}
add_action('wp_enqueue_scripts', 'my_custom_scripts');














/**
 * Optimize WooCommerce Scripts
 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
 */
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
 
function child_manage_woocommerce_styles() {
    //remove generator meta tag
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
 
    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {
        //dequeue scripts and styles
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            //wp_dequeue_script( 'wc-add-to-cart' );
            //wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            //wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            //wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
 
}


/**
 * Optimize WooCommerce Scripts
 * Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
 */
add_action( 'wp_enqueue_scripts', 'child_remove_woocommerce_styles', 99 );
 
function child_remove_woocommerce_styles() {
    //remove generator meta tag
    remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
 
    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {
        //dequeue scripts and styles
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            wp_dequeue_script( 'wc_price_slider' );
/*
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-checkout' );
*/
/*             wp_dequeue_script( 'wc-single-product' ); */
/*
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
*/
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
/*             wp_dequeue_script( 'jquery-placeholder' ); */
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
    }
 
}



@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );



// posts per page based on CPT
function iti_custom_posts_per_page($query)
{
    switch ( $query->query_vars['post_type'] )
    {
        case 'work':  // Post Type named 'iti_cpt_1'
            $query->query_vars['posts_per_page'] = 100;
            break;

        default:
            break;
    }
    return $query;
}

if( !is_admin() )
{
    add_filter( 'pre_get_posts', 'iti_custom_posts_per_page' );
}



/*


//get royal slider to load files when needed
register_new_royalslider_files(2);

*/





add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
  if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $post_type = get_query_var('post_type');
    if($post_type)
        $post_type = $post_type;
    else
        $post_type = array('post','work'); // replace cpt to your custom post type
    $query->set('post_type',$post_type);
    return $query;
    }
}


// Prevent access to dev areas
function password_protected(){
    if(!is_user_logged_in() && (ENVIRONMENT == 'development' || ENVIRONMENT == 'staging')){
        wp_redirect(get_option('siteurl') .'/wp-login.php');
    }
}
add_action('template_redirect', 'password_protected');



?>