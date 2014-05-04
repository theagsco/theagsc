<?php
/**
 * My Account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce;

wc_print_notices(); ?>

<div id="woocommerce-content">

	<div id="my-account-gravatar"><?php global $user_email; get_currentuserinfo(); ?><?php echo get_avatar( $user_email, $size = '128' ); ?></div>
		
		<?php
		printf(
			__( '<h1>Hello %1$s.</h1> <h2 class="wrong-guy">(not %1$s? <a href="%2$s">Sign out</a>).</h2>', 'woocommerce' ) . ' ',
			$current_user->display_name,
			wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )
		);
	
		printf( __( '<p class="myaccount_user">From your account dashboard you can view your recent orders, manage your shipping and billing addresses and <a href="%s">edit your password and account details</a>.', 'woocommerce' ),
			wc_customer_edit_account_url()
		);
		?>
		<div style="clear:both;"></div>
	</p>
	
	<section class="woo-account-section"><?php do_action( 'woocommerce_before_my_account' ); ?></section>
	
	<section class="woo-account-section"><?php wc_get_template( 'myaccount/my-downloads.php' ); ?></section>
	
	<section class="woo-account-section"><?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) ); ?></section>
	
	<section class="woo-account-section"><?php wc_get_template( 'myaccount/my-address.php' ); ?></section>
	
	<section class="woo-account-section"><?php do_action( 'woocommerce_after_my_account' ); ?></section>
	
</div><!--woocommerce-content-->
