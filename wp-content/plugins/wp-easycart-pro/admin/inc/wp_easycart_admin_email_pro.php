<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_email_pro' ) ) :

final class wp_easycart_admin_email_pro{
	
	protected static $_instance = null;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
		
	public function __construct( ){
		add_action( 'wp_easycart_admin_email_global_settings', array( $this, 'add_options' ) );
	}
	
	public function add_options( ){
		wp_easycart_admin( )->load_toggle_group_textarea( 'ec_option_email_signature_text', 'ec_admin_save_email_text_setting', get_option( 'ec_option_email_signature_text' ), __( 'Email Signature: Text', 'wp-easycart-pro' ), __( 'This will show at the footer of all automatic emails from the WP EasyCart.', 'wp-easycart-pro' ), __( 'Enter a customer message', 'wp-easycart-pro' ), 'ec_option_email_signature_text_row' );
		
		wp_easycart_admin( )->load_toggle_group_image( 'ec_option_email_signature_image', 'ec_admin_save_email_text_setting', get_option( 'ec_option_email_signature_image' ), __( 'Email Signature: Image', 'wp-easycart-pro' ), __( 'This will show at the footer of all automatic emails from the WP EasyCart.', 'wp-easycart-pro' ), '', 'ec_option_email_signature_image_row', true, false );
	}
}
endif; // End if class_exists check

function wp_easycart_admin_email_pro( ){
	return wp_easycart_admin_email_pro::instance( );
}
wp_easycart_admin_email_pro( );
