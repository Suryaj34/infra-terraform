<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_checkout_pro' ) ) :

class wp_easycart_admin_checkout_pro {

	private $text_notifications_file;
	private $text_notifications_list_file;
	private $text_notifications_file_upgrade;

	protected static $_instance = null;

	public static function instance( ) {

		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;

	}

	public function __construct( ){ 
		$this->text_notifications_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/checkout/text-notifications.php';
		$this->text_notifications_list_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/checkout/text-notifications-list.php';
		$this->text_notifications_file_upgrade = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/checkout/text-notifications-upgrade.php';
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			add_action( 'wpeasycart_admin_checkout_settings', array( $this, 'add_text_notifications' ) );
			if ( function_exists( 'wp_easycart_admin_checkout' ) ) {
				remove_action( 'wpeasycart_admin_checkout_form_fields_end', array( wp_easycart_admin_checkout(), 'add_additional_email_option' ) );
			}
			add_action( 'wpeasycart_admin_checkout_form_fields_end', array( $this, 'add_additional_email_option' ) );
			//add_action( 'wpeasycart_admin_settings_cart_top', array( $this, 'add_one_page_checkout' ) );
		}
	}

	public function add_text_notifications( ){
		if( $this->check_for_license( ) ){ // Add logic for has texting
			$message_list = $this->get_messages( );
			include $this->text_notifications_file;
		} else {
			include $this->text_notifications_file_upgrade;
		}
	}
	
	public function add_one_page_checkout() {
		wp_easycart_admin( )->load_toggle_group( 'ec_option_onepage_checkout', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_onepage_checkout' ), __( 'One Page Checkout (Beta)', 'wp-easycart-pro' ), __( 'This is currently a beta feature and may have bugs when combined with some features. Enable at your own risk. this to create a one page checkout (no page reload).', 'wp-easycart-pro' ), 'ec_option_onepage_checkout_row', true );

		wp_easycart_admin( )->load_toggle_group( 'ec_option_onepage_checkout_tabbed', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_onepage_checkout_tabbed' ), __( 'One Page Checkout Format: Enable Breadcrumbs Display', 'wp-easycart-pro' ), __( 'Enable this to create a one page checkout that shows in a breadcrumbs format when shipping is required.', 'wp-easycart-pro' ), 'ec_option_onepage_checkout_tabbed_row', get_option( 'ec_option_onepage_checkout' ) );

		wp_easycart_admin( )->load_toggle_group( 'ec_option_onepage_checkout_cart_first', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_onepage_checkout_cart_first' ), __( 'One Page Checkout Entry: Cart First', 'wp-easycart-pro' ), __( 'Enable this to show the cart first, disable to go straight to checkout first.', 'wp-easycart-pro' ), 'ec_option_onepage_checkout_cart_first_row', get_option( 'ec_option_onepage_checkout' ) );

		//wp_easycart_admin( )->load_toggle_group( 'ec_option_onepage_checkout_quantity_adjust_on', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_onepage_checkout_quantity_adjust_on' ), __( 'One Page Checkout: Quantity Adjust on Checkout', 'wp-easycart-pro' ), __( 'Enable this to allow a customer to adjust the quantity on the checkout screen.', 'wp-easycart-pro' ), 'ec_option_onepage_checkout_quantity_adjust_on_row', get_option( 'ec_option_onepage_checkout' ) );
	}

	public function add_additional_email_option() {
		wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_extra_email', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_enable_extra_email' ), __( 'Additional Email', 'wp-easycart-pro' ), __( 'Enable this to allow customers to enter an optional secondary email that will receive order email communications.', 'wp-easycart-pro' ) );
	}

	public function print_message_list( $message_list ){
		include $this->text_notifications_list_file;
	}

	public function add_message( ) {
		$license_info = get_option( 'wp_easycart_license_info' );

		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return array( );
		}
		$license_key = $license_info['transaction_key'];

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);

		$request_params = array(
			'body' => array(
				'license_key' => preg_replace( '/[^A-Z0-9]/', '', strtoupper( $license_key ) ),
				'site_url' => esc_attr( $url ),
				'trigger_type' => preg_replace( '/[^a-z0-9\-\_]/', '', strtolower( $_POST['trigger_type'] ) ),
				'order_status_id' => (int) $_POST['order_status_id'],
				'message' => $_POST['message']
			)
		);

		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/new/',
			$request_params
		);

		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}

		$response = json_decode( $response['body'] );

		return $response;
	}

	public function update_message( ) {
		$license_info = get_option( 'wp_easycart_license_info' );

		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return array( );
		}
		$license_key = $license_info['transaction_key'];

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);

		$request_params = array(
			'body' => array(
				'license_key' => preg_replace( '/[^A-Z0-9]/', '', strtoupper( $license_key ) ),
				'site_url' => esc_attr( $url ),
				'message_id' => (int) $_POST['message_id'],
				'trigger_type' => preg_replace( '/[^a-z0-9\-\_]/', '', strtolower( $_POST['trigger_type'] ) ),
				'order_status_id' => (int) $_POST['order_status_id'],
				'message' => $_POST['message']
			)
		);

		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/update/',
			$request_params
		);

		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}

		$response = json_decode( $response['body'] );

		return $response;
	}

	public function delete_message( ) {
		$license_info = get_option( 'wp_easycart_license_info' );

		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return array( );
		}
		$license_key = $license_info['transaction_key'];

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);

		$request_params = array(
			'body' => array(
				'license_key' => preg_replace( '/[^A-Z0-9]/', '', strtoupper( $license_key ) ),
				'site_url' => esc_attr( $url ),
				'message_id' => (int) $_POST['message_id']
			)
		);

		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/delete/',
			$request_params
		);

		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}

		$response = json_decode( $response['body'] );

		return $response;
	}

	public function get_messages( ) {
		$license_info = get_option( 'wp_easycart_license_info' );

		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return array( );
		}
		$license_key = $license_info['transaction_key'];

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);

		$request_params = array(
			'body' => array(
				'license_key' => preg_replace( '/[^A-Z0-9]/', '', strtoupper( $license_key ) ),
				'site_url' => esc_attr( $url )
			)
		);

		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/',
			$request_params
		);

		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}

		$response = json_decode( $response['body'] );

		return $response;
	}

	public function check_for_license( ) {
		$license_info = get_option( 'wp_easycart_license_info' );

		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return false;
		}
		$license_key = $license_info['transaction_key'];

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);

		$request_params = array(
			'body' => array(
				'license_key' => preg_replace( '/[^A-Z0-9]/', '', strtoupper( $license_key ) ),
				'site_url' => esc_attr( $url )
			)
		);

		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/verify/',
			$request_params
		);

		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}

		$response = json_decode( $response['body'] );

		if( isset( $response->error ) ) {
			return false;
		}

		return true;
	}
}
endif; // End if class_exists check

function wp_easycart_admin_checkout_pro( ){
	return wp_easycart_admin_checkout_pro::instance( );
}
wp_easycart_admin_checkout_pro( );

add_action( 'wp_ajax_ec_admin_ajax_update_enable_cloud_messages', 'ec_admin_ajax_update_enable_cloud_messages' );
function ec_admin_ajax_update_enable_cloud_messages( ){
	update_option( 'ec_option_enable_cloud_messages', (int) $_POST['ec_option_enable_cloud_messages'] );
	update_option( 'ec_option_cloud_messages_default_country', sanitize_text_field( $_POST['ec_option_cloud_messages_default_country'] ) );
	$preferred_countries = array();
	foreach( $_POST['ec_option_cloud_messages_preferred_countries'] as $pref_country ) {
		$preferred_countries[] = sanitize_text_field( $pref_country );
	}
	update_option( 'ec_option_cloud_messages_preferred_countries', $preferred_countries );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_add_cloud_message', 'ec_admin_ajax_add_cloud_message' );
function ec_admin_ajax_add_cloud_message( ){
	wp_easycart_admin_checkout_pro( )->add_message( );
	$message_list = wp_easycart_admin_checkout_pro( )->get_messages( );
	if ( count( $message_list ) > 0 ) {
		wp_easycart_admin_checkout_pro( )->print_message_list( $message_list );
	}
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_update_cloud_message', 'ec_admin_ajax_update_cloud_message' );
function ec_admin_ajax_update_cloud_message( ){
	wp_easycart_admin_checkout_pro( )->update_message( );
	$message_list = wp_easycart_admin_checkout_pro( )->get_messages( );
	if ( count( $message_list ) > 0 ) {
		wp_easycart_admin_checkout_pro( )->print_message_list( $message_list );
	}
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_delete_cloud_message', 'ec_admin_ajax_delete_cloud_message' );
function ec_admin_ajax_delete_cloud_message( ){
	wp_easycart_admin_checkout_pro( )->delete_message( );
	$message_list = wp_easycart_admin_checkout_pro( )->get_messages( );
	if ( count( $message_list ) > 0 ) {
		wp_easycart_admin_checkout_pro( )->print_message_list( $message_list );
	}
	die( );
}
