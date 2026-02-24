<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_payments_pro' ) ) :

final class wp_easycart_admin_payments_pro{
	
	protected static $_instance = null;
	
	private $wpdb;
	
	public $cart_page;
	public $permalink_divider;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
		
	public function __construct( ){
		
		// Keep reference to wpdb
		global $wpdb;
		$this->wpdb =& $wpdb;
		
		// Link Information
		$cart_page_id = get_option('ec_option_cartpage');
		if( function_exists( 'icl_object_id' ) ){
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$this->cart_page = get_permalink( $cart_page_id );
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
		}
		if( substr_count( $this->cart_page, '?' ) )					$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";
	
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_actions' ) );
			add_filter( 'wp_easycart_admin_payment_file', array( $this, 'load_payment_form' ), 10, 2 );
			add_action( 'wp_easycart_pro_add_live_save', array( $this, 'add_live_save' ) );
			remove_action( 'wp_easycart_admin_payment_options_top', array( wp_easycart_admin_payments( ), 'load_free_header' ) );
			remove_action( 'wp_easycart_admin_payment_options_top', array( wp_easycart_admin_payments( ), 'load_free_amazonpay' ) );
			remove_action( 'wp_easycart_admin_payment_options_top', array( wp_easycart_admin_payments( ), 'load_free_paypal' ) );
			remove_action( 'wp_easycart_admin_payment_options_top', array( wp_easycart_admin_payments( ), 'load_free_square' ) );
			remove_action( 'wp_easycart_admin_payment_options_top', array( wp_easycart_admin_payments( ), 'load_free_stripe' ) );
			remove_action( 'wp_easycart_admin_payment_options_top', array( wp_easycart_admin_payments( ), 'load_free_footer' ) );
			add_action( 'wpeasycart_admin_payment_settings', array( $this, 'add_third_party' ) );
			add_action( 'wpeasycart_admin_payment_settings', array( $this, 'add_live_gateway' ) );
			add_action( 'wpeasycart_admin_payment_settings', array( $this, 'add_amazon_pay' ) );

			/* Square Overlays */
			add_action( 'wp_easycart_admin_product_details_basic_start', array( $this, 'maybe_add_square_overlays' ), 10, 1 );
			add_action( 'wp_easycart_admin_product_details_quantity_fields', array( $this, 'maybe_add_square_inventory_overlays' ), 10, 1 );
			add_action( 'wp_easycart_admin_product_details_pricing_fields', array( $this, 'maybe_add_square_overlays' ), 10, 1 );
			add_action( 'wp_easycart_admin_product_details_images_pro_start', array( $this, 'maybe_add_square_overlays' ), 10, 1 );
			add_action( 'wp_easycart_admin_product_details_options_pro_start', array( $this, 'maybe_add_square_inventory_overlays' ), 10, 1 );
			add_action( 'wp_easycart_admin_product_details_modifiers_pro_start', array( $this, 'maybe_add_square_overlays' ), 10, 1 );
		}
		
		$this->maybe_process_intuit_oauth2( );
		$this->process_intuit_app( );
	}

	public function add_amazon_pay() {
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/amazonpay.php' );
	}

	public function add_live_save( ){
		echo 'ec_admin_save_live_gateway_selection( );';
	}
	
	public function add_third_party( ){
		echo '<h1 class="easycart-wp-heading-inline">Third Party Gateway</h1>';
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/paypal.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/third-party.php' );
	}
	
	public function add_live_gateway( ){
		echo '<h1 class="easycart-wp-heading-inline">Live Gateway</h1>';
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/square.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/stripe_connect.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/live-gateway.php' );
	}
	
	public function maybe_add_square_overlays( $product ) {
		if ( get_option( 'ec_option_payment_process_method' ) == 'square' && get_option( 'ec_option_square_auto_product_sync' ) && '' != $product->square_id ) {
			echo '<div class="wp-easycart-square-cover"><div><h3>' . esc_attr__( 'Square Product Sync Enabled', 'wp-easycart-pro' ) . '</h3><p>' . esc_attr__( 'You currently have square product sync enabled, which means your product data is regularly synced from Square into WP EasyCart. To manage your Square products, log into your Square account and make updates to the products there.', 'wp-easycart-pro' ) . '</p></div></div>';
		}
	}
	
	public function maybe_add_square_inventory_overlays( $product ) {
		if ( get_option( 'ec_option_payment_process_method' ) == 'square' && get_option( 'ec_option_square_auto_sync' ) && '' != $product->square_id ) {
			echo '<div class="wp-easycart-square-cover"><div><h3>' . esc_attr__( 'Square Inventory Sync Enabled', 'wp-easycart-pro' ) . '</h3><p>' . esc_attr__( 'You currently have square inventory sync enabled, which means your inventory data is regularly synced from Square into WP EasyCart. To manage your Square inventory, log into your Square account and make updates to the inventory there.', 'wp-easycart-pro' ) . '</p></div></div>';
		}
	}
	
	public function process_actions( ){
		if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && get_option( 'ec_option_intuit_oauth_version' ) == '1' && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'ec_wpeasycart_intuit_authroize' ){
			$this->intuit_oauth_init( );
		}else if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'ec_wpeasycart_intuit_reauthroize' ){
			$this->intuit_oauth_reauthorize( );
		}else if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'ec_wpeasycart_intuit_disconnect' ){
			$this->intuit_oauth_disconnect( );
		}
		
	}
	
	private function maybe_process_intuit_oauth2( ){
		if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && get_option( 'ec_option_intuit_oauth_version' ) == '2' && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'ec_wpeasycart_intuit_authroize' ){
			$this->intuit_oauth2_init( );
		
		}else if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && get_option( 'ec_option_intuit_oauth_version' ) == '2' && isset( $_GET['state'] ) && $_GET['state'] == 'wpeasycart_security_token_' . get_option( 'ec_option_intuit_security_token' ) ){
			$this->intuit_oauth2_handle_response( );
			
		}
	}
	
	private function process_intuit_app( ){
		
		// Handle a Failed Connect Attempt
		if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && isset( $_GET['wpeasycart_intuit_failed'] ) ){
			wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment&error=failed-to-connect' );
			die( );
		
		// Handle a Successful Connect Attempt
		}else if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && isset( $_GET['wpeasycart_intuit_state'] ) ){
			$refresh_token = preg_replace( "/[^A-Za-z0-9 \-\._\~\+\/]/", '', $_GET['refresh_token'] );
			$access_token = preg_replace( "/[^A-Za-z0-9 \-\._\~\+\/]/", '', $_GET['access_token'] );
			
			update_option( 'ec_option_intuit_access_token', $access_token );
			update_option( 'ec_option_intuit_refresh_token', $refresh_token );
			update_option( 'ec_option_intuit_last_authorized', time( ) );
			
			wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment&success=connected' );
			die( );
		
		}
		
	}
	
	public function load_payment_form( $current_file, $payment_type ){
		if( $payment_type == 'square' )
			return WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/' . $payment_type . '.php';
		else if( file_exists( $current_file ) )
			return $current_file;
		else
			return WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/settings/payments/' . $payment_type . '.php';
	}
	
	public function update_2checkout_thirdparty( ){
		update_option( 'ec_option_2checkout_thirdparty_sid', stripslashes_deep( $_POST['ec_option_2checkout_thirdparty_sid'] ) );
		update_option( 'ec_option_2checkout_thirdparty_secret_word', stripslashes_deep( $_POST['ec_option_2checkout_thirdparty_secret_word'] ) );
		update_option( 'ec_option_2checkout_thirdparty_currency_code', $_POST['ec_option_2checkout_thirdparty_currency_code'] );
		update_option( 'ec_option_2checkout_thirdparty_lang', $_POST['ec_option_2checkout_thirdparty_lang'] );
		update_option( 'ec_option_2checkout_thirdparty_purchase_step', $_POST['ec_option_2checkout_thirdparty_purchase_step'] );
		update_option( 'ec_option_2checkout_thirdparty_sandbox_mode', $_POST['ec_option_2checkout_thirdparty_sandbox_mode'] );
		update_option( 'ec_option_2checkout_thirdparty_demo_mode', $_POST['ec_option_2checkout_thirdparty_demo_mode'] );
	}
	
	public function update_cashfree( ){
		update_option( 'ec_option_cashfree_app_id', stripslashes_deep( $_POST['ec_option_cashfree_app_id'] ) );
		update_option( 'ec_option_cashfree_secret', stripslashes_deep( $_POST['ec_option_cashfree_secret'] ) );
		update_option( 'ec_option_cashfree_currency', $_POST['ec_option_cashfree_currency'] );
		update_option( 'ec_option_cashfree_testmode', $_POST['ec_option_cashfree_testmode'] );
	}
	
	public function update_dwolla( ){
		update_option( 'ec_option_dwolla_thirdparty_account_id', stripslashes_deep( $_POST['ec_option_dwolla_thirdparty_account_id'] ) );
		update_option( 'ec_option_dwolla_thirdparty_key', stripslashes_deep( $_POST['ec_option_dwolla_thirdparty_key'] ) );
		update_option( 'ec_option_dwolla_thirdparty_secret', stripslashes_deep( $_POST['ec_option_dwolla_thirdparty_secret'] ) );
		update_option( 'ec_option_dwolla_thirdparty_test_mode', $_POST['ec_option_dwolla_thirdparty_test_mode'] );
	}
	
	public function update_nets( ){
		update_option( 'ec_option_nets_merchant_id', $_POST['ec_option_nets_merchant_id'] );
		update_option( 'ec_option_nets_token', $_POST['ec_option_nets_token'] );
		update_option( 'ec_option_nets_currency', $_POST['ec_option_nets_currency'] );
		update_option( 'ec_option_nets_test_mode', $_POST['ec_option_nets_test_mode'] );
	}
	
	public function update_payfast( ){
		update_option( 'ec_option_payfast_merchant_id', stripslashes_deep( $_POST['ec_option_payfast_merchant_id'] ) );
		update_option( 'ec_option_payfast_merchant_key', stripslashes_deep( $_POST['ec_option_payfast_merchant_key'] ) );
		update_option( 'ec_option_payfast_passphrase', stripslashes_deep( $_POST['ec_option_payfast_passphrase'] ) );
		update_option( 'ec_option_payfast_sandbox', stripslashes_deep( $_POST['ec_option_payfast_sandbox'] ) );
	}
	
	public function update_payfort( ){
		update_option( 'ec_option_payfort_merchant_id', stripslashes_deep( $_POST['ec_option_payfort_merchant_id'] ) );
		update_option( 'ec_option_payfort_access_code', stripslashes_deep( $_POST['ec_option_payfort_access_code'] ) );
		update_option( 'ec_option_payfort_sha_type', $_POST['ec_option_payfort_sha_type'] );
		update_option( 'ec_option_payfort_request_phrase', stripslashes_deep( $_POST['ec_option_payfort_request_phrase'] ) );
		update_option( 'ec_option_payfort_response_phrase', stripslashes_deep( $_POST['ec_option_payfort_response_phrase'] ) );
		update_option( 'ec_option_payfort_language', $_POST['ec_option_payfort_language'] );
		update_option( 'ec_option_payfort_currency_code', $_POST['ec_option_payfort_currency_code'] );
		update_option( 'ec_option_payfort_test_mode', $_POST['ec_option_payfort_test_mode'] );
	}
	
	public function update_paymentexpress_thirdparty( ){
		update_option( 'ec_option_payment_express_thirdparty_username', stripslashes_deep( $_POST['ec_option_payment_express_thirdparty_username'] ) );
		update_option( 'ec_option_payment_express_thirdparty_key', stripslashes_deep( $_POST['ec_option_payment_express_thirdparty_key'] ) );
		update_option( 'ec_option_payment_express_thirdparty_currency', $_POST['ec_option_payment_express_thirdparty_currency'] );
	}
	
	public function update_realex_thirdparty( ){
		update_option( 'ec_option_realex_thirdparty_merchant_id', stripslashes_deep( $_POST['ec_option_realex_thirdparty_merchant_id'] ) );
		update_option( 'ec_option_realex_thirdparty_secret', stripslashes_deep( $_POST['ec_option_realex_thirdparty_secret'] ) );
		update_option( 'ec_option_realex_thirdparty_currency', $_POST['ec_option_realex_thirdparty_currency'] );
		update_option( 'ec_option_realex_thirdparty_account', $_POST['ec_option_realex_thirdparty_account'] );
	}
	
	public function update_redsys( ){
		update_option( 'ec_option_redsys_merchant_code', stripslashes_deep( $_POST['ec_option_redsys_merchant_code'] ) );
		update_option( 'ec_option_redsys_terminal', stripslashes_deep( $_POST['ec_option_redsys_terminal'] ) );
		update_option( 'ec_option_redsys_currency', $_POST['ec_option_redsys_currency'] );
		update_option( 'ec_option_redsys_key', stripslashes_deep( $_POST['ec_option_redsys_key'] ) );
		update_option( 'ec_option_redsys_test_mode', $_POST['ec_option_redsys_test_mode'] );
	}
	
	public function update_sagepay_paynow_za( ){
		update_option( 'ec_option_sagepay_paynow_za_service_key', stripslashes_deep( $_POST['ec_option_sagepay_paynow_za_service_key'] ) );
	}
	
	public function update_skrill( ){
		update_option( 'ec_option_skrill_merchant_id', stripslashes_deep( $_POST['ec_option_skrill_merchant_id'] ) );
		update_option( 'ec_option_skrill_company_name', stripslashes_deep( $_POST['ec_option_skrill_company_name'] ) );
		update_option( 'ec_option_skrill_email', stripslashes_deep( $_POST['ec_option_skrill_email'] ) );
		update_option( 'ec_option_skrill_language', $_POST['ec_option_skrill_language'] );
		update_option( 'ec_option_skrill_currency_code', $_POST['ec_option_skrill_currency_code'] );
	}
	
	public function update_live_gateway_selection( ){
		update_option( 'ec_option_payment_process_method', $_POST['ec_option_payment_process_method'] );
		do_action( 'wpeasycart_live_gateway_updated', esc_attr( $_POST['ec_option_payment_process_method'] ) );
	}
	
	public function update_amazonpay( ){
		update_option( 'ec_option_amazonpay_enable', (int) stripslashes_deep( $_POST['ec_option_amazonpay_enable'] ) );
		update_option( 'ec_option_amazonpay_store_id', stripslashes_deep( $_POST['ec_option_amazonpay_store_id'] ) );
		update_option( 'ec_option_amazonpay_merchant_id', stripslashes_deep( $_POST['ec_option_amazonpay_merchant_id'] ) );
		update_option( 'ec_option_amazonpay_public_key', stripslashes_deep( $_POST['ec_option_amazonpay_public_key'] ) );
		update_option( 'ec_option_amazonpay_private_key', stripslashes_deep( $_POST['ec_option_amazonpay_private_key'] ) );
		update_option( 'ec_option_amazonpay_currency', stripslashes_deep( $_POST['ec_option_amazonpay_currency'] ) );
		update_option( 'ec_option_amazonpay_region', stripslashes_deep( $_POST['ec_option_amazonpay_region'] ) );
		update_option( 'ec_option_amazonpay_language', stripslashes_deep( $_POST['ec_option_amazonpay_language'] ) );
		update_option( 'ec_option_amazonpay_is_sandbox', (int) $_POST['ec_option_amazonpay_is_sandbox'] );
		update_option( 'ec_option_amazonpay_pay_button_color', $_POST['ec_option_amazonpay_pay_button_color'] );
		update_option( 'ec_option_amazonpay_hide_early_buttons', $_POST['ec_option_amazonpay_hide_early_buttons'] );
	}
	
	public function update_authorize( ){
		update_option( 'ec_option_authorize_login_id', stripslashes_deep( $_POST['ec_option_authorize_login_id'] ) );
		update_option( 'ec_option_authorize_trans_key', stripslashes_deep( $_POST['ec_option_authorize_trans_key'] ) );
		update_option( 'ec_option_authorize_currency_code', $_POST['ec_option_authorize_currency_code'] );
		update_option( 'ec_option_authorize_test_mode', $_POST['ec_option_authorize_test_mode'] );
		update_option( 'ec_option_authorize_developer_account', $_POST['ec_option_authorize_developer_account'] );
	}
	
	public function update_beanstream( ){
		update_option( 'ec_option_beanstream_merchant_id', stripslashes_deep( $_POST['ec_option_beanstream_merchant_id'] ) );
		update_option( 'ec_option_beanstream_api_passcode', stripslashes_deep( $_POST['ec_option_beanstream_api_passcode'] ) );
	}
	
	public function update_braintree( ){
		update_option( 'ec_option_braintree_merchant_id', stripslashes_deep( $_POST['ec_option_braintree_merchant_id'] ) );
		update_option( 'ec_option_braintree_merchant_account_id', stripslashes_deep( $_POST['ec_option_braintree_merchant_account_id'] ) );
		update_option( 'ec_option_braintree_public_key', stripslashes_deep( $_POST['ec_option_braintree_public_key'] ) );
		update_option( 'ec_option_braintree_private_key', stripslashes_deep( $_POST['ec_option_braintree_private_key'] ) );
		update_option( 'ec_option_braintree_environment', $_POST['ec_option_braintree_environment'] );
	}
		
	public function update_chronopay( ){
		update_option( 'ec_option_chronopay_currency', stripslashes_deep( $_POST['ec_option_chronopay_currency'] ) );
		update_option( 'ec_option_chronopay_product_id', stripslashes_deep( $_POST['ec_option_chronopay_product_id'] ) );
		update_option( 'ec_option_chronopay_shared_secret', stripslashes_deep( $_POST['ec_option_chronopay_shared_secret'] ) ); 
	}
		
	public function update_virtualmerchant( ){
		update_option( 'ec_option_virtualmerchant_ssl_merchant_id', stripslashes_deep( $_POST['ec_option_virtualmerchant_ssl_merchant_id'] ) );
		update_option( 'ec_option_virtualmerchant_ssl_user_id', stripslashes_deep( $_POST['ec_option_virtualmerchant_ssl_user_id'] ) );
		update_option( 'ec_option_virtualmerchant_ssl_pin', stripslashes_deep( $_POST['ec_option_virtualmerchant_ssl_pin'] ) );
		update_option( 'ec_option_virtualmerchant_demo_account', $_POST['ec_option_virtualmerchant_demo_account'] );
	}
	
	public function update_eway( ){
		update_option( 'ec_option_eway_use_rapid_pay', $_POST['ec_option_eway_use_rapid_pay'] );
		update_option( 'ec_option_eway_customer_id', stripslashes_deep( $_POST['ec_option_eway_customer_id'] ) );
		update_option( 'ec_option_eway_api_key', $_POST['ec_option_eway_api_key'] );
		update_option( 'ec_option_eway_api_password', $_POST['ec_option_eway_api_password'] );
		update_option( 'ec_option_eway_client_key', $_POST['ec_option_eway_client_key'] );
		update_option( 'ec_option_eway_test_mode', $_POST['ec_option_eway_test_mode'] );  
		update_option( 'ec_option_eway_test_mode_success', $_POST['ec_option_eway_test_mode_success'] ); 
	}
	
	public function update_firstdata( ){
		update_option( 'ec_option_firstdatae4_exact_id', stripslashes_deep( $_POST['ec_option_firstdatae4_exact_id'] ) );
		update_option( 'ec_option_firstdatae4_password', stripslashes_deep( $_POST['ec_option_firstdatae4_password'] ) );
		update_option( 'ec_option_firstdatae4_key_id', $_POST['ec_option_firstdatae4_key_id'] );
		update_option( 'ec_option_firstdatae4_key', $_POST['ec_option_firstdatae4_key'] );
		update_option( 'ec_option_firstdatae4_language', $_POST['ec_option_firstdatae4_language'] );
		update_option( 'ec_option_firstdatae4_currency', $_POST['ec_option_firstdatae4_currency'] ); 
		update_option( 'ec_option_firstdatae4_test_mode', $_POST['ec_option_firstdatae4_test_mode'] );
	}
	
	public function update_goemerchant( ){
		update_option( 'ec_option_goemerchant_gateway_id', stripslashes_deep( $_POST['ec_option_goemerchant_gateway_id'] ) ); 
		update_option( 'ec_option_goemerchant_processor_id', stripslashes_deep( $_POST['ec_option_goemerchant_processor_id'] ) ); 
		update_option( 'ec_option_goemerchant_trans_center_id', stripslashes_deep( $_POST['ec_option_goemerchant_trans_center_id'] ) );          
	}
	
	public function update_intuit( ){
		update_option( 'ec_option_intuit_oauth_version', $_POST['ec_option_intuit_oauth_version'] );
		update_option( 'ec_option_intuit_app_token', stripslashes_deep( $_POST['ec_option_intuit_app_token'] ) );
		if( $_POST['ec_option_intuit_oauth_version'] == '1' ){
			update_option( 'ec_option_intuit_consumer_key', stripslashes_deep( $_POST['ec_option_intuit_consumer_key'] ) );
			update_option( 'ec_option_intuit_consumer_secret', stripslashes_deep( $_POST['ec_option_intuit_consumer_secret'] ) );
		}else{
			update_option( 'ec_option_intuit_consumer_key', stripslashes_deep( $_POST['ec_option_intuit_client_id'] ) );
			update_option( 'ec_option_intuit_consumer_secret', stripslashes_deep( $_POST['ec_option_intuit_client_secret'] ) );
		}
		update_option( 'ec_option_intuit_currency', $_POST['ec_option_intuit_currency'] );
		update_option( 'ec_option_intuit_test_mode', $_POST['ec_option_intuit_test_mode'] );
	}
	
	public function intuit_oauth_init( ){
		require_once( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/qbsdk/config.php' );
		define('OAUTH_REQUEST_URL', 'https://oauth.intuit.com/oauth/v1/get_request_token');
		define('OAUTH_ACCESS_URL', 'https://oauth.intuit.com/oauth/v1/get_access_token');
		define('OAUTH_AUTHORISE_URL', 'https://appcenter.intuit.com/Connect/Begin');
		define('OAUTH_CONSUMER_KEY', get_option( 'ec_option_intuit_consumer_key' ) );
		define('OAUTH_CONSUMER_SECRET', get_option( 'ec_option_intuit_consumer_secret' ) );
		
		// The url to this page. it needs to be dynamic to handle runnable's dynamic urls
		define( 'CALLBACK_URL', get_admin_url( ) . 'admin.php?page=wp-easycart-settings&subpage=payment&ec_admin_form_action=ec_wpeasycart_intuit_authroize' );
		
		// cleans out the token variable if comming from
		// connect to QuickBooks button
		if( isset( $_GET['start'] ) ){
			update_option( 'ec_option_intuit_access_token', '' );
		}
		 
		try{
			if( !class_exists( 'OAuth' ) ){
				echo __( "OAuth is not available for PHP on your server, which is a requirement for using Intuit. Please contact your hosting provider and ask them to install the PHP extension 'OAuth'.", 'wp-easycart-pro' );
				die( );
			}
			$oauth = new OAuth( OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
			$oauth->enableDebug();
			$oauth->disableSSLChecks(); //To avoid the error: (Peer certificate cannot be authenticated with given CA certificates)
			if( !isset( $_GET['oauth_token'] ) && get_option( 'ec_option_intuit_access_token' ) == '' ){
				
				// step 1: get request token from Intuit
				$request_token = $oauth->getRequestToken( OAUTH_REQUEST_URL, CALLBACK_URL );
				update_option( 'ec_option_intuit_access_token_secret', $request_token['oauth_token_secret'] );
				
				// step 2: send user to intuit to authorize 
				header( 'Location: '. OAUTH_AUTHORISE_URL . '?oauth_token=' . $request_token['oauth_token'] );
		
			}
			
			if( isset( $_GET['oauth_token'] ) && isset( $_GET['oauth_verifier'] ) ){
			
				// step 3: request a access token from Intuit
				$oauth->setToken( $_GET['oauth_token'], get_option( 'ec_option_intuit_access_token_secret' ) );
				$access_token = $oauth->getAccessToken( OAUTH_ACCESS_URL );
				
				$token = $access_token['oauth_token'];
				$token_secret = $access_token['oauth_token_secret'];
				$realmId = $_REQUEST['realmId'];
				$dataSource = $_REQUEST['dataSource'];
				
				update_option( 'ec_option_intuit_realm_id', $realmId );
				update_option( 'ec_option_intuit_access_token', $token );
				update_option( 'ec_option_intuit_access_token_secret', $token_secret );
				update_option( 'ec_option_intuit_last_authorized', time( ) );
				
				// write JS to pup up to refresh parent and close popup
				echo '<script type="text/javascript">
					window.opener.location.href = window.opener.location.href;
					window.close();
				  </script>';
			}
		 
		}catch( OAuthException $e ){
			echo __( "Got auth exception", 'wp-easycart-pro' );
			echo '<pre>';
			print_r($e);
		}
	}
	
	public function intuit_oauth2_init( ){
		$client_id = get_option( 'ec_option_intuit_consumer_key' );
		$response_type = 'code';
		$scope = 'com.intuit.quickbooks.payment';
		$redirect_uri = get_admin_url( );
		$security_token = rand( 1000000, 9999999 );
		update_option( 'ec_option_intuit_security_token', $security_token );
		$state = 'wpeasycart_security_token_' . $security_token;
		
		$url = "https://appcenter.intuit.com/connect/oauth2?client_id=" . $client_id . "&response_type=" . $response_type . "&scope=" . $scope . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&";
		wp_redirect( $url );
	}
	
	public function intuit_oauth2_handle_response( ){
		
		if( !isset( $_GET['state'] ) )
			return false;
		
		if( !isset( $_GET['code'] ) )
			return false;
		
		if( !isset( $_GET['realmId'] ) )
			return false;
		
		update_option( 'ec_option_intuit_realm_id', esc_attr( $_GET['realmId'] ) );
		
		$client_id = get_option( 'ec_option_intuit_consumer_key' );
		$client_secret = get_option( 'ec_option_intuit_consumer_secret' );
		$redirect_uri = get_admin_url( );
		
		$gateway_url = "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer";
		$gateway_data = array(
			"code"			=> $_GET['code'],
			"redirect_uri"	=> $redirect_uri,
			"grant_type"	=> "authorization_code"
		);
		
		$headr = array( );
		$headr[] = 'Accept: application/json';
		$headr[] = 'Authorization: Basic ' . base64_encode( $client_id . ":" . $client_secret );
		$headr[] = 'Content-Type: application/x-www-form-urlencoded';
		$headr[] = 'Host: oauth.platform.intuit.com';
		$headr[] = 'Cache-Control: no-cache';
		
		$ch = curl_init( );
		curl_setopt($ch, CURLOPT_URL, $gateway_url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headr );
		curl_setopt($ch, CURLOPT_POST, true ); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $gateway_data ) );
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)30);
		$response = curl_exec($ch);
		
		$ec_db = new ec_db( );
		if( $response === false ){
			$ec_db->insert_response( 0, 1, __( "Intuit Connection Error", 'wp-easycart-pro' ), curl_error( $ch ) );
			$response = (object) array( "error" => curl_error( $ch ) );
		}else
			$ec_db->insert_response( 0, 0, __( "Intuit Connection Response", 'wp-easycart-pro' ), print_r( $response, true ) );
		curl_close ($ch);
		
		$response_decoded = json_decode( $response );
		
		$refresh_token = $response_decoded->refresh_token;
		$access_token = $response_decoded->access_token;
		$token_type = $response_decoded->token_type;
		$expires_in = $response_decoded->expires_in;
		$x_refresh_token_expires_in = $response_decoded->x_refresh_token_expires_in;
		
		update_option( 'ec_option_intuit_access_token', $access_token );
		update_option( 'ec_option_intuit_refresh_token', $refresh_token );
		update_option( 'ec_option_intuit_last_authorized', time( ) );
		
		wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment' );
		die( );
	}
	
	public function intuit_oauth_reauthorize( ){
		
		$gateway = new ec_intuit( );
		$disconnect_result = $gateway->reauthorize( );
		
		if( $disconnect_result == "success" ){
			header( "location:admin.php?page=wp-easycart-settings&subpage=payment&intuit_reauthorize=true" );
				
		}else{ 
			header( "location:admin.php?page=wp-easycart-settings&subpage=payment&intuit_reauthorize=" . $disconnect_result );
			
		}
		
	}
	
	public function intuit_oauth_disconnect( ){
		
		$gateway = new ec_intuit( );
		$disconnect_result = $gateway->disconnect( );
			
		update_option( 'ec_option_intuit_realm_id', "" );
		update_option( 'ec_option_intuit_refresh_token', "" );
		update_option( 'ec_option_intuit_access_token', "" );
		update_option( 'ec_option_intuit_access_token_secret', "" );
		update_option( 'ec_option_intuit_last_authorized', 0 );
		
		if( $disconnect_result == "success" ){
			header( "location:admin.php?page=wp-easycart-settings&subpage=payment&intuit_disconnected=true" );
				
		}else{ 
			header( "location:admin.php?page=wp-easycart-settings&subpage=payment&intuit_disconnected=" . $disconnect_result );
			
		}
		
	}
	
	public function update_migs( ){
		update_option( 'ec_option_migs_signature', stripslashes_deep( $_POST['ec_option_migs_signature'] ) );
		update_option( 'ec_option_migs_access_code', stripslashes_deep( $_POST['ec_option_migs_access_code'] ) );
		update_option( 'ec_option_migs_merchant_id', stripslashes_deep( $_POST['ec_option_migs_merchant_id'] ) );
	}
	
	public function update_moneris_ca( ){
		update_option( 'ec_option_moneris_ca_store_id', stripslashes_deep( $_POST['ec_option_moneris_ca_store_id'] ) );
		update_option( 'ec_option_moneris_ca_api_token', stripslashes_deep( $_POST['ec_option_moneris_ca_api_token'] ) );
		update_option( 'ec_option_moneris_ca_test_mode', $_POST['ec_option_moneris_ca_test_mode'] );
	}
	
	public function update_moneris_us( ){
		update_option( 'ec_option_moneris_us_store_id', stripslashes_deep( $_POST['ec_option_moneris_us_store_id'] ) );
		update_option( 'ec_option_moneris_us_api_token', stripslashes_deep( $_POST['ec_option_moneris_us_api_token'] ) );
		update_option( 'ec_option_moneris_us_test_mode', $_POST['ec_option_moneris_us_test_mode'] );
	}
	
	public function update_nmi( ){
		update_option( 'ec_option_nmi_3ds', $_POST['ec_option_nmi_3ds'] );
		update_option( 'ec_option_nmi_api_key', stripslashes_deep( $_POST['ec_option_nmi_api_key'] ) );
		update_option( 'ec_option_nmi_username', stripslashes_deep( $_POST['ec_option_nmi_username'] ) );
		update_option( 'ec_option_nmi_password', stripslashes_deep( $_POST['ec_option_nmi_password'] ) );
		update_option( 'ec_option_nmi_currency', $_POST['ec_option_nmi_currency'] );
		update_option( 'ec_option_nmi_processor_id', stripslashes_deep( $_POST['ec_option_nmi_processor_id'] ) );
		update_option( 'ec_option_nmi_ship_from_zip', stripslashes_deep( $_POST['ec_option_nmi_ship_from_zip'] ) );
		update_option( 'ec_option_nmi_commodity_code', stripslashes_deep( $_POST['ec_option_nmi_commodity_code'] ) );
		update_option( 'ec_option_cardinal_processor_id', stripslashes_deep( $_POST['ec_option_cardinal_processor_id'] ) );
		update_option( 'ec_option_cardinal_merchant_id', stripslashes_deep( $_POST['ec_option_cardinal_merchant_id'] ) );
		update_option( 'ec_option_cardinal_password', stripslashes_deep( $_POST['ec_option_cardinal_password'] ) );
		update_option( 'ec_option_cardinal_currency', $_POST['ec_option_cardinal_currency'] );
		update_option( 'ec_option_cardinal_test_mode', $_POST['ec_option_cardinal_test_mode'] );
	}
	
	public function update_payline( ){
		update_option( 'ec_option_payline_username', stripslashes_deep( $_POST['ec_option_payline_username'] ) );
		update_option( 'ec_option_payline_password', stripslashes_deep( $_POST['ec_option_payline_password'] ) );
		update_option( 'ec_option_payline_currency', $_POST['ec_option_payline_currency'] );
	}
	
	public function update_paymentexpress( ){
		update_option( 'ec_option_payment_express_username', stripslashes_deep( $_POST['ec_option_payment_express_username'] ) );
		update_option( 'ec_option_payment_express_password', stripslashes_deep( $_POST['ec_option_payment_express_password'] ) );
		update_option( 'ec_option_payment_express_currency', $_POST['ec_option_payment_express_currency'] );
		update_option( 'ec_option_payment_express_developer_account', $_POST['ec_option_payment_express_developer_account'] );
	}
	
	public function update_paypal_pro( ){
		update_option( 'ec_option_paypal_pro_test_mode', $_POST['ec_option_paypal_pro_test_mode'] );
		update_option( 'ec_option_paypal_pro_vendor', stripslashes_deep( $_POST['ec_option_paypal_pro_vendor'] ) );
		update_option( 'ec_option_paypal_pro_partner', stripslashes_deep( $_POST['ec_option_paypal_pro_partner'] ) );
		update_option( 'ec_option_paypal_pro_user', stripslashes_deep( $_POST['ec_option_paypal_pro_user'] ) );
		update_option( 'ec_option_paypal_pro_password', stripslashes_deep( $_POST['ec_option_paypal_pro_password'] ) );
		update_option( 'ec_option_paypal_pro_currency', $_POST['ec_option_paypal_pro_currency'] );
	}
	
	public function update_paypal_payments_pro( ){
		update_option( 'ec_option_paypal_payments_pro_test_mode', $_POST['ec_option_paypal_payments_pro_test_mode'] );
		update_option( 'ec_option_paypal_payments_pro_user', stripslashes_deep( $_POST['ec_option_paypal_payments_pro_user'] ) );
		update_option( 'ec_option_paypal_payments_pro_password', stripslashes_deep( $_POST['ec_option_paypal_payments_pro_password'] ) );
		update_option( 'ec_option_paypal_payments_pro_signature', stripslashes_deep( $_POST['ec_option_paypal_payments_pro_signature'] ) );
		update_option( 'ec_option_paypal_payments_pro_currency', $_POST['ec_option_paypal_payments_pro_currency'] );
	}
	
	public function update_paypoint( ){
		update_option( 'ec_option_paypoint_merchant_id', stripslashes_deep( $_POST['ec_option_paypoint_merchant_id'] ) );
		update_option( 'ec_option_paypoint_vpn_password', stripslashes_deep( $_POST['ec_option_paypoint_vpn_password'] ) );
		update_option( 'ec_option_paypoint_test_mode', $_POST['ec_option_paypoint_test_mode'] );
	}
	
	public function update_realex( ){
		update_option( 'ec_option_realex_merchant_id', stripslashes_deep( $_POST['ec_option_realex_merchant_id'] ) );
		update_option( 'ec_option_realex_secret', stripslashes_deep( $_POST['ec_option_realex_secret'] ) );
		update_option( 'ec_option_realex_currency', $_POST['ec_option_realex_currency'] );
		update_option( 'ec_option_realex_3dsecure', $_POST['ec_option_realex_3dsecure'] );
		update_option( 'ec_option_realex_test_mode', $_POST['ec_option_realex_test_mode'] );
	}
	
	public function update_sagepay( ){
		update_option( 'ec_option_sagepay_vendor', stripslashes_deep( $_POST['ec_option_sagepay_vendor'] ) );
		update_option( 'ec_option_sagepay_currency', $_POST['ec_option_sagepay_currency'] );
		update_option( 'ec_option_sagepay_simulator', $_POST['ec_option_sagepay_simulator'] );
		update_option( 'ec_option_sagepay_testmode', $_POST['ec_option_sagepay_testmode'] );
	}
	
	public function update_sagepayus( ){
		update_option( 'ec_option_sagepayus_mid', stripslashes_deep( $_POST['ec_option_sagepayus_mid'] ) );
		update_option( 'ec_option_sagepayus_mkey', stripslashes_deep( $_POST['ec_option_sagepayus_mkey'] ) );
		update_option( 'ec_option_sagepayus_application_id', stripslashes_deep( $_POST['ec_option_sagepayus_application_id'] ) );
	}
	
	public function update_securenet( ){
		update_option( 'ec_option_securenet_id', stripslashes_deep( $_POST['ec_option_securenet_id'] ) );
		update_option( 'ec_option_securenet_secure_key', stripslashes_deep( $_POST['ec_option_securenet_secure_key'] ) );
		update_option( 'ec_option_securenet_use_sandbox', $_POST['ec_option_securenet_use_sandbox'] );
	}
	
	public function update_securepay( ){
		update_option( 'ec_option_securepay_merchant_id', stripslashes_deep( $_POST['ec_option_securepay_merchant_id'] ) );
		update_option( 'ec_option_securepay_password', stripslashes_deep( $_POST['ec_option_securepay_password'] ) );
		update_option( 'ec_option_securepay_currency', $_POST['ec_option_securepay_currency'] );
		update_option( 'ec_option_securepay_test_mode', $_POST['ec_option_securepay_test_mode'] );
	}
	
	public function update_stripe( ){
		update_option( 'ec_option_stripe_public_api_key', $_POST['ec_option_stripe_public_api_key'] );
		update_option( 'ec_option_stripe_api_key', $_POST['ec_option_stripe_api_key'] );
		update_option( 'ec_option_stripe_currency', $_POST['ec_option_stripe_currency'] );
		update_option( 'ec_option_stripe_company_country', sanitize_text_field( wp_unslash( $_POST['ec_option_stripe_company_country'] ) ) );
		update_option( 'ec_option_stripe_enable_apple_pay', (int) $_POST['ec_option_stripe_enable_apple_pay'] );
		update_option( 'ec_option_stripe_disable_wallet_first', (int) $_POST['ec_option_stripe_disable_wallet_first'] );
		update_option( 'ec_option_stripe_enable_ideal', $_POST['ec_option_stripe_enable_ideal'] );
		update_option( 'ec_option_stripe_order_create_customer', $_POST['ec_option_stripe_order_create_customer'] );
	}
	
	public function update_square( ){
        if( get_option( 'ec_option_square_is_sandbox' ) ){
            update_option( 'ec_option_square_sandbox_location_id', $_POST['ec_option_square_location_id'] );
            
        }else{
            update_option( 'ec_option_square_application_id', stripslashes_deep( $_POST['ec_option_square_application_id'] ) );
            update_option( 'ec_option_square_access_token', stripslashes_deep( $_POST['ec_option_square_access_token'] ) );
            update_option( 'ec_option_square_location_id', $_POST['ec_option_square_location_id'] );
        
        }
        update_option( 'ec_option_square_location_country', stripslashes_deep( $_POST['ec_option_square_location_country'] ) );
        update_option( 'ec_option_square_digital_wallet', $_POST['ec_option_square_digital_wallet'] );
        update_option( 'ec_option_square_sync_block_active_change', (int) $_POST['ec_option_square_sync_block_active_change'] );
        update_option( 'ec_option_square_merchant_name', stripslashes_deep( $_POST['ec_option_square_merchant_name'] ) );
		$square = new ec_square( );
		$square->set_currency( );
        
        if( !get_option( 'ec_option_square_is_sandbox' )  && $_POST['ec_option_square_digital_wallet'] ){
            $square->register_domain( );
        }
	}

	public function update_square_sync() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
			return;
		}

		if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
			return false;
		}

		$enable_sync = (int) $_POST['ec_option_square_auto_sync'];
		update_option( 'ec_option_square_auto_sync', $enable_sync );
		if ( $enable_sync ) {
			if( ! wp_next_scheduled( 'wpeasycart_square_sync_inventory' ) ){
				wp_schedule_event( time(), 'hourly', 'wpeasycart_square_sync_inventory' ); 
			}
		} else {
			$event = wp_get_scheduled_event( 'wpeasycart_square_sync_inventory' );
			if ( $event ) {
				wp_unschedule_event( $event->timestamp, 'wpeasycart_square_sync_inventory' );
			}
			wp_clear_scheduled_hook( 'wpeasycart_square_sync_inventory' ); 
		}
	}

	public function update_square_product_sync() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
			return;
		}

		if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
			return false;
		}

		$enable_sync = (int) $_POST['ec_option_square_auto_product_sync'];
		update_option( 'ec_option_square_auto_product_sync', $enable_sync );
		if ( $enable_sync ) {
			if( ! wp_next_scheduled( 'wpeasycart_square_product_sync' ) ){
				wp_schedule_event( time(), 'hourly', 'wpeasycart_square_product_sync' ); 
			}
		} else {
			$event = wp_get_scheduled_event( 'wpeasycart_square_product_sync' );
			if ( $event ) {
				wp_unschedule_event( $event->timestamp, 'wpeasycart_square_product_sync' );
			}
			wp_clear_scheduled_hook( 'wpeasycart_square_product_sync' ); 
		}
	}

	public function update_square_webhooks() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
			return;
		}

		if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
			return false;
		}

		$db = new ec_db();
		$enable_webhooks = (int) $_POST['ec_option_square_webhooks'];
		update_option( 'ec_option_square_webhooks', $enable_webhooks );
		if ( $enable_webhooks ) {
			if ( get_option( 'ec_option_square_is_sandbox' ) ) {
				$merchant_id = get_option( 'ec_option_square_sandbox_merchant_id' );
			} else {
				$merchant_id = get_option( 'ec_option_square_merchant_id' );
			}
			if ( get_option( 'ec_option_square_is_sandbox' ) ) {
				$access_token = get_option( 'ec_option_square_sandbox_access_token' );
			} else {
				$access_token = get_option( 'ec_option_square_access_token' );
			}
			$webhook_url = rtrim( get_site_url(), '/' ) . '/?wpeasycarthook=square-webhook';
			if ( get_option( 'ec_option_square_is_sandbox' ) ) {
				$url = "https://connect.wpeasycart.com/square-sandbox/webhook-create.php?merchantID=".$merchant_id."&accessToken=".$access_token."&webhookURL=".$webhook_url;
			} else {
				$url = "https://connect.wpeasycart.com/square-v2/webhook-create.php?merchantID=".$merchant_id."&accessToken=".$access_token."&webhookURL=".$webhook_url;
			}
			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'GET',
					'timeout' => 8
				)
			);
			if ( is_wp_error( $response ) ) {
				$db->insert_response( 0, 1, "Square Webhook Register CURL ERROR", $response->get_error_message() );
				$response = json_encode( (object) array(
					'error' => $response->get_error_message(),
				) );
			}else{
				$db->insert_response( 0, 0, "Square Webhook Register Response", print_r( $response, true ) );
			}
		}
	}

	public function update_cardpointe( ){
		update_option( 'ec_option_cardpointe_site', stripslashes_deep( $_POST['ec_option_cardpointe_site'] ) );
		update_option( 'ec_option_cardpointe_merch', stripslashes_deep( $_POST['ec_option_cardpointe_merch'] ) );
		update_option( 'ec_option_cardpointe_username', stripslashes_deep( $_POST['ec_option_cardpointe_username'] ) ); 
		update_option( 'ec_option_cardpointe_password', stripslashes_deep( $_POST['ec_option_cardpointe_password'] ) ); 
		update_option( 'ec_option_cardpointe_currency', stripslashes_deep( $_POST['ec_option_cardpointe_currency'] ) ); 
		update_option( 'ec_option_cardpointe_shipfromzip', stripslashes_deep( $_POST['ec_option_cardpointe_shipfromzip'] ) );
	}
}
endif; // End if class_exists check

function wp_easycart_admin_payments_pro( ){
	return wp_easycart_admin_payments_pro::instance( );
}
wp_easycart_admin_payments_pro( );

add_action( 'wp_ajax_ec_admin_ajax_save_2checkout_thirdparty', 'ec_admin_ajax_save_2checkout_thirdparty' );
function ec_admin_ajax_save_2checkout_thirdparty( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_2checkout_thirdparty( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_cashfree', 'ec_admin_ajax_save_cashfree' );
function ec_admin_ajax_save_cashfree( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_cashfree( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_dwolla', 'ec_admin_ajax_save_dwolla' );
function ec_admin_ajax_save_dwolla( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_dwolla( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_nets', 'ec_admin_ajax_save_nets' );
function ec_admin_ajax_save_nets( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_nets( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_payfast', 'ec_admin_ajax_save_payfast' );
function ec_admin_ajax_save_payfast( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_payfast( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_payfort', 'ec_admin_ajax_save_payfort' );
function ec_admin_ajax_save_payfort( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_payfort( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_paymentexpress_thirdparty', 'ec_admin_ajax_save_paymentexpress_thirdparty' );
function ec_admin_ajax_save_paymentexpress_thirdparty( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_paymentexpress_thirdparty( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_realex_thirdparty', 'ec_admin_ajax_save_realex_thirdparty' );
function ec_admin_ajax_save_realex_thirdparty( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_realex_thirdparty( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_redsys', 'ec_admin_ajax_save_redsys' );
function ec_admin_ajax_save_redsys( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_redsys( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_sagepay_paynow_za', 'ec_admin_ajax_save_sagepay_paynow_za' );
function ec_admin_ajax_save_sagepay_paynow_za( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_sagepay_paynow_za( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_skrill', 'ec_admin_ajax_save_skrill' );
function ec_admin_ajax_save_skrill( ){
	wp_easycart_admin_payments( )->update_third_party_selection( );
	wp_easycart_admin_payments_pro( )->update_skrill( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_live_gateway_selection', 'ec_admin_ajax_save_live_gateway_selection' );
function ec_admin_ajax_save_live_gateway_selection( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_amazonpay', 'ec_admin_ajax_save_amazonpay' );
function ec_admin_ajax_save_amazonpay( ){
	wp_easycart_admin_payments_pro( )->update_amazonpay( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_authorize', 'ec_admin_ajax_save_authorize' );
function ec_admin_ajax_save_authorize( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_authorize( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_beanstream', 'ec_admin_ajax_save_beanstream' );
function ec_admin_ajax_save_beanstream( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_beanstream( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_braintree', 'ec_admin_ajax_save_braintree' );
function ec_admin_ajax_save_braintree( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_braintree( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_chronopay', 'ec_admin_ajax_save_chronopay' );
function ec_admin_ajax_save_chronopay( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_chronopay( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_virtualmerchant', 'ec_admin_ajax_save_virtualmerchant' );
function ec_admin_ajax_save_virtualmerchant( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_virtualmerchant( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_eway', 'ec_admin_ajax_save_eway' );
function ec_admin_ajax_save_eway( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_eway( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_firstdata', 'ec_admin_ajax_save_firstdata' );
function ec_admin_ajax_save_firstdata( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_firstdata( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_goemerchant', 'ec_admin_ajax_save_goemerchant' );
function ec_admin_ajax_save_goemerchant( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_goemerchant( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_intuit', 'ec_admin_ajax_save_intuit' );
function ec_admin_ajax_save_intuit( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_intuit( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_migs', 'ec_admin_ajax_save_migs' );
function ec_admin_ajax_save_migs( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_migs( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_moneris_ca', 'ec_admin_ajax_save_moneris_ca' );
function ec_admin_ajax_save_moneris_ca( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_moneris_ca( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_moneris_us', 'ec_admin_ajax_save_moneris_us' );
function ec_admin_ajax_save_moneris_us( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_moneris_us( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_nmi', 'ec_admin_ajax_save_nmi' );
function ec_admin_ajax_save_nmi( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_nmi( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_payline', 'ec_admin_ajax_save_payline' );
function ec_admin_ajax_save_payline( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_payline( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_paymentexpress', 'ec_admin_ajax_save_paymentexpress' );
function ec_admin_ajax_save_paymentexpress( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_paymentexpress( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_paypal_pro', 'ec_admin_ajax_save_paypal_pro' );
function ec_admin_ajax_save_paypal_pro( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_paypal_pro( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_paypal_payments_pro', 'ec_admin_ajax_save_paypal_payments_pro' );
function ec_admin_ajax_save_paypal_payments_pro( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_paypal_payments_pro( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_paypoint', 'ec_admin_ajax_save_paypoint' );
function ec_admin_ajax_save_paypoint( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_paypoint( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_realex', 'ec_admin_ajax_save_realex' );
function ec_admin_ajax_save_realex( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_realex( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_sagepay', 'ec_admin_ajax_save_sagepay' );
function ec_admin_ajax_save_sagepay( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_sagepay( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_sagepayus', 'ec_admin_ajax_save_sagepayus' );
function ec_admin_ajax_save_sagepayus( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_sagepayus( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_securenet', 'ec_admin_ajax_save_securenet' );
function ec_admin_ajax_save_securenet( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_securenet( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_securepay', 'ec_admin_ajax_save_securepay' );
function ec_admin_ajax_save_securepay( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_securepay( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_stripe', 'ec_admin_ajax_save_stripe' );
function ec_admin_ajax_save_stripe( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_stripe( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_square_pro', 'ec_admin_ajax_save_square_pro' );
function ec_admin_ajax_save_square_pro( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_square( );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_square_sync', 'ec_admin_ajax_save_square_sync' );
function ec_admin_ajax_save_square_sync( ){
	wp_easycart_admin_payments_pro( )->update_square_sync();
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_square_product_sync', 'ec_admin_ajax_save_square_product_sync' );
function ec_admin_ajax_save_square_product_sync( ){
	wp_easycart_admin_payments_pro( )->update_square_product_sync();
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_save_square_webhooks', 'ec_admin_ajax_save_square_webhooks' );
function ec_admin_ajax_save_square_webhooks( ){
	wp_easycart_admin_payments_pro( )->update_square_webhooks();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_cardpointe', 'ec_admin_ajax_save_cardpointe' );
function ec_admin_ajax_save_cardpointe( ){
	wp_easycart_admin_payments_pro( )->update_live_gateway_selection( );
	wp_easycart_admin_payments_pro( )->update_cardpointe( );
	die( );
}
