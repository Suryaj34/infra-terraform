<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_taxes_pro' ) ) :

final class wp_easycart_admin_taxes_pro{

	protected static $_instance = null;

	public $tax_cloud_setup_file;
	public $tax_jar_setup_file;

	public static function instance( ) {

		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;

	}

	public function __construct( ){
		$this->tax_cloud_setup_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/taxes/tax-cloud-setup.php';
		$this->tax_jar_setup_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/taxes/tax-jar-setup.php';

		if( wp_easycart_admin_license( )->is_licensed( ) ){
			// Actions
			remove_action( 'wpeasycart_admin_tax_setup', array( wp_easycart_admin_taxes( ), 'load_tax_cloud_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_tax_cloud_setup' ) );
			remove_action( 'wpeasycart_admin_tax_setup', array( wp_easycart_admin_taxes( ), 'load_tax_jar_setup' ) );
			add_action( 'wpeasycart_admin_tax_setup', array( $this, 'load_tax_jar_setup' ) );
			add_filter( 'wp_easycart_admin_product_details_tax_fields_list', array( $this, 'add_tax_jar_tic_setup' ), 10, 1 );
			add_action( 'init', array( $this, 'save_settings' ) );
		}
	}

	public function load_tax_cloud_setup( ){
		include( $this->tax_cloud_setup_file );
	}

	public function load_tax_jar_setup( ){
		include( $this->tax_jar_setup_file );
	}

	public function add_tax_jar_tic_setup( $fields ) {
		if ( function_exists( 'wpeasycart_taxjar' ) && wpeasycart_taxjar()->is_enabled() ) {
			$tic_list = wpeasycart_taxjar()->get_categories();
			$tic_categories = array();
			foreach ( $tic_list as $tic_item ) {
				$tic_categories[] = (object) array(
					'id' => $tic_item->product_tax_code,
					'value' => $tic_item->name . ' (' . $tic_item->product_tax_code . ')'
				);
			}

			$fields_return = array();
			foreach ( $fields as $field ) {
				if ( 'TIC' == $field['name'] ) {
					$fields_return[] = array(
						'name' => 'TIC',
						'type' => 'select',
						'data' => $tic_categories,
						'data_label' => __( 'None Selected', 'wp-easycart-pro' ),
						'label' => __( 'TaxJar Category', 'wp-easycart-pro' ),
						'required' => false,
						'validation_type' => 'select',
						'visible' => true,
						'value' => $field['value'],
						'select2' => 'basic'
					);
				} else {
					$fields_return[] = $field;
				}
			}
			return $fields_return;
		} else {
			return $fields;
		}
	}

	/* Tax Cloud */
	public function save_tax_cloud( ){
		$options_text = array( 'ec_option_tax_cloud_api_id', 'ec_option_tax_cloud_api_key', 'ec_option_tax_cloud_address', 'ec_option_tax_cloud_city', 'ec_option_tax_cloud_state', 'ec_option_tax_cloud_zip' );

		if( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ){
			update_option( $_POST['update_var'], stripslashes_deep( $_POST['val'] ) );

		}
	}

	/* Tax Jar */
	public function save_tax_jar() {
		$options = array( 'ec_option_tax_jar_enable', 'ec_option_tax_jar_sandbox', 'ec_option_tax_jar_enable_address_verification' );
		$options_text = array( 'ec_option_tax_jar_live_token', 'ec_option_tax_jar_sandbox_token', 'ec_option_tax_jar_address', 'ec_option_tax_jar_city', 'ec_option_tax_jar_state', 'ec_option_tax_jar_zip', 'ec_option_tax_jar_country' );

		if( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ){
			update_option( $_POST['update_var'], (int) $_POST['val'] );

		} else if( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ){
			update_option( $_POST['update_var'], stripslashes_deep( $_POST['val'] ) );

		}
	}

}
endif; // End if class_exists check

function wp_easycart_admin_taxes_pro( ){
	return wp_easycart_admin_taxes_pro::instance( );
}
wp_easycart_admin_taxes_pro( );

/* Tax Rate Hooks - Tax Cloud */
add_action( 'wp_ajax_ec_admin_ajax_save_tax_cloud_settings', 'ec_admin_ajax_save_tax_cloud_settings' );
function ec_admin_ajax_save_tax_cloud_settings( ){
	wp_easycart_admin_taxes_pro( )->save_tax_cloud( );
	die( );

}

/* Tax Rate Hooks - TaxJar */
add_action( 'wp_ajax_ec_admin_ajax_save_tax_jar_settings', 'ec_admin_ajax_save_tax_jar_settings' );
function ec_admin_ajax_save_tax_jar_settings( ){
	wp_easycart_admin_taxes_pro( )->save_tax_jar( );
	die( );

}
