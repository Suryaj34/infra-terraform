<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_user_pro' ) ) :

final class wp_easycart_admin_user_pro{
	
	protected static $_instance = null;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
		
	public function __construct( ){
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			add_filter( 'wp_easycart_admin_user_details_basic_fields_list', array( $this, 'maybe_add_email_other_field' ), 10, 1 );
			add_filter( 'wp_easycart_admin_user_details_optional_fields_list', array( $this, 'maybe_add_bypass_billing_match_shipping' ), 10, 1 );
			add_filter( 'wp_easycart_admin_user_details_optional_fields_list', array( $this, 'maybe_add_stripe_test_user_field' ), 10, 1 );
		}
	}
	
	public function maybe_add_email_other_field( $fields ) {
		if ( get_option( 'ec_option_enable_extra_email' ) && isset( $_GET['user_id'] ) ) {
			global $wpdb;
			$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_user WHERE user_id = %d', (int) $_GET['user_id'] ) );
			if( $user && isset( $user->email_other ) ) {
				$fields[] = array(
					'name' => 'email_other',
					'type' => 'text',
					'label' => __( 'Additional Email Address', 'wp-easycart-pro' ),
					'required' => false,
					'validation_type' 	=> 'email',
					'value' => $user->email_other,
				);
			}
		}
		return $fields;
	}

	public function maybe_add_bypass_billing_match_shipping( $fields ) {
		if ( isset( $_GET['user_id'] ) ) {
			global $wpdb;
			$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_user WHERE user_id = %d', (int) $_GET['user_id'] ) );
			if( $user && isset( $user->allow_shipping_bypass ) ) {
				$fields[] = array(
					'name' => 'allow_shipping_bypass',
					'type' => 'checkbox',
					'label' => __( 'Bypass Disabled Shipping Address Option (both global and product level)', 'wp-easycart-pro' ),
					'required' => false,
					'message' => '',
					'selected' => false,
					'value' => $user->allow_shipping_bypass,
				);
			}
		}
		return $fields;
	}

	public function maybe_add_stripe_test_user_field( $fields ) {
		if ( isset( $_GET['user_id'] ) && '' != get_option( 'ec_option_stripe_connect_sandbox_publishable_key' ) && '' != get_option( 'ec_option_stripe_connect_sandbox_access_token' ) ) {
			global $wpdb;
			$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_user WHERE user_id = %d', (int) $_GET['user_id'] ) );
			if( $user && isset( $user->is_stripe_test_user ) ) {
				$fields[] = array(
					'name' => 'is_stripe_test_user',
					'type' => 'checkbox',
					'label' => __( 'Make Stripe Test User (PAYMENTS WILL ONLY PROCESS ON YOUR SANDBOX!)', 'wp-easycart-pro' ),
					'required' => false,
					'message' => '',
					'selected' => false,
					'value' => $user->is_stripe_test_user,
				);
			}
		}
		return $fields;
	}

}
endif; // End if class_exists check

function wp_easycart_admin_user_pro( ){
	return wp_easycart_admin_user_pro::instance( );
}
wp_easycart_admin_user_pro();
