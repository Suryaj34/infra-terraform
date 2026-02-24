<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_subscriptions_pro' ) ) :

final class wp_easycart_admin_subscriptions_pro{
	
	protected static $_instance = null;
	
	public $subscriptions_list_file;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}

	public function __construct( ){ 
		$this->subscriptions_list_file 	= WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/orders/subscriptions/subscriptions-list.php';	
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			remove_action( 'wp_easycart_admin_subscriptions_list', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			remove_action( 'wp_easycart_admin_subscriptions_details', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			add_action( 'wp_easycart_admin_subscriptions_list', array( $this, 'show_list' ), 1 );
			add_action( 'wp_easycart_admin_subscriptions_details', array( $this, 'show_details' ), 1 );
			
			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			
			/* Process Admin Actions */
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_subscription' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_subscription' ) );
		}
	}
	
	public function process_delete_subscription( ){
		if( $_GET['ec_admin_form_action'] == 'delete-subscription' && isset( $_GET['subscription_id'] ) && !isset( $_GET['bulk'] ) ){
			$result = $this->delete_subscription( );
			wp_cache_flush();
			wp_easycart_admin( )->redirect( 'wp-easycart-orders', 'subscriptions', $result );
		}
	}
	
	public function process_bulk_delete_subscription( ){
		if( $_GET['ec_admin_form_action'] == 'delete-subscription' && !isset( $_GET['subscription_id'] ) && isset( $_GET['bulk'] ) ){
			$result = $this->bulk_delete_subscription( );
			wp_cache_flush();
			wp_easycart_admin( )->redirect( 'wp-easycart-orders', 'subscriptions', $result );
		}
	}
	
	public function add_success_messages( $messages ){
		if( isset( $_GET['success'] ) && $_GET['success'] == 'subscription-cancelled' ){
			$messages[] = __( 'Subscription successfully cancelled', 'wp-easycart-pro' );
		}else if( isset( $_GET['success'] ) && $_GET['success'] == 'subscription-deleted' ){
			$messages[] = __( 'Subscription successfully deleted', 'wp-easycart-pro' );
		}
		return $messages;
	}
	
	public function show_list( ){
		include( $this->subscriptions_list_file );
	}
	
	public function show_details( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/inc/wp_easycart_admin_details_subscriptions.php' );
		$details = new wp_easycart_admin_details_subscriptions( );
		$details->output( esc_attr( $_GET['ec_admin_form_action'] ) );
	}
	
	public function update_subscription( ){
		global $wpdb;
		$product_id = $_POST['update_product_id'];
		$product_id_previous = $_POST['product_id'];
		if( $product_id == $product_id_previous )
			return;
		
		$subscription_id = $_POST['subscription_id'];
		$stripe_subscription_id = $_POST['stripe_subscription_id'];
		$stripe_customer_id = $_POST['stripe_customer_id'];
		
		$product = $wpdb->get_row( $wpdb->prepare( "SELECT product_id, title, model_number, price, subscription_bill_length, subscription_bill_period, subscription_prorate, subscription_unique_id FROM ec_product WHERE product_id = %d", $product_id ) );
		$stripe_user = (object) array( "stripe_customer_id" => $stripe_customer_id );
		$stripe_product = (object) array( "product_id" => $product_id, "subscription_unique_id" => $product->subscription_unique_id );
		
		if( get_option( 'ec_option_payment_process_method' ) == 'stripe' ){
			$stripe = new ec_stripe( );
		}else{
			$stripe = new ec_stripe_connect( );
		}
		$response = $stripe->update_subscription( $stripe_product, $stripe_user, NULL, $stripe_subscription_id, NULL, $product->subscription_prorate );
		if ( $response ) {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_subscription SET title = %s, product_id = %d, model_number = %s, price = %s, payment_length = %s, payment_period = %s WHERE subscription_id = %d", $product->title, $product_id, $product->model_number, $product->price, $product->subscription_bill_length, $product->subscription_bill_period, $subscription_id ) );
			wp_cache_flush();
			do_action( 'wpeasycart_subscription_updated', $subscription_id );
			return $wpdb->get_row( $wpdb->prepare( "SELECT title, product_id, model_number, price, payment_length, payment_period FROM ec_subscription WHERE subscription_id = %d", $subscription_id ) );
		} else {
			return array( "error" => "subscription-update-failed", "product_id" => $product_id_previous );
		}
	}
	
	public function cancel_subscription( ){
		global $wpdb;
		
		$subscription_id = $_POST['stripe_subscription_id'];
		$user = $_POST['stripe_customer_id'];
		$stripe_user = (object) array( "stripe_customer_id" => $user );

		if( get_option( 'ec_option_payment_process_method' ) == 'stripe' ){
			$stripe = new ec_stripe( );
		}else{
			$stripe = new ec_stripe_connect( );
		}
		$response = $stripe->cancel_subscription( $stripe_user, $subscription_id );

		$wpdb->query( $wpdb->prepare( "UPDATE ec_subscription SET subscription_status = 'Canceled' WHERE stripe_subscription_id = %s", $subscription_id ) );
		$subscription = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_subscription WHERE stripe_subscription_id = %s', $subscription_id ) );
		do_action( 'wpeasycart_subscription_canceled', $subscription->subscription_id );
		return array( 'success' => 'subscription-cancelled' );  
	}
	
	public function delete_subscription( ){
		global $wpdb;
		$subscription_id = $_GET['subscription_id'];
		do_action( 'wpeasycart_subscription_deleting', $subscription_id );
		$wpdb->query( $wpdb->prepare( "DELETE FROM ec_subscription WHERE subscription_id = %s", $subscription_id ) );
		return array( 'success' => 'subscription-deleted' );
	}
	
	public function bulk_delete_subscription( ){
		global $wpdb;
		
		$bulk_ids = $_GET['bulk'];
		foreach ( $bulk_ids as $bulk_id ) {
			do_action( 'wpeasycart_subscription_deleting', $bulk_id );
			$wpdb->query( $wpdb->prepare( "DELETE FROM ec_subscription WHERE subscription_id = %s", $bulk_id ) );
		}
		
		return array( 'success' => 'subscription-deleted' );
	}
}
endif; // End if class_exists check

function wp_easycart_admin_subscriptions_pro( ){
	return wp_easycart_admin_subscriptions_pro::instance( );
}
wp_easycart_admin_subscriptions_pro( );

add_action( 'wp_ajax_ec_admin_ajax_cancel_subscription', 'ec_admin_ajax_cancel_subscription' );
function ec_admin_ajax_cancel_subscription( ){
	wp_easycart_admin_subscriptions_pro( )->cancel_subscription( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_subscription_details', 'ec_admin_ajax_save_subscription_details' );
function ec_admin_ajax_save_subscription_details( ){
	$response = wp_easycart_admin_subscriptions_pro( )->update_subscription( );
	echo json_encode( $response );
	die( );
}