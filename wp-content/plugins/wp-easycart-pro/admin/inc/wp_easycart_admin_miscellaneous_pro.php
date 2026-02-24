<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_miscellaneous_pro' ) ) :

final class wp_easycart_admin_miscellaneous_pro{
	
	protected static $_instance = null;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
		
	public function __construct( ){
		add_action( 'wp_easycart_additional_settings_more_options', array( $this, 'add_options' ) );
	}
	
	public function add_options( ){
		wp_easycart_admin( )->load_toggle_group( 'ec_option_disable_easycart_ad', 'ec_admin_save_additional_options', get_option( 'ec_option_disable_easycart_ad' ), __( 'Disable EasyCart Ad Area', 'wp-easycart-pro' ), __( 'Enable this to remove the text notification ad area from the plugin.', 'wp-easycart-pro' ) );

		wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_debugging_mode', 'ec_admin_save_additional_options', get_option( 'ec_option_enable_debugging_mode' ), __( 'Enable Debugging Mode', 'wp-easycart-pro' ), __( 'Enable this to disable the error blocking feature used for maximum cart compatibility.', 'wp-easycart-pro' ) );
	}
}
endif; // End if class_exists check

function wp_easycart_admin_miscellaneous_pro( ){
	return wp_easycart_admin_miscellaneous_pro::instance( );
}
wp_easycart_admin_miscellaneous_pro( );
