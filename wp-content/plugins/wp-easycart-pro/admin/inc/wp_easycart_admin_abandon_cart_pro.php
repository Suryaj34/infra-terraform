<?php
class wp_easycart_admin_abandon_cart_pro {

	public $abandon_cart_file;
	public $abandon_cart_stats_file;
	public $settings;

	public function __construct() { 
		$this->abandon_cart_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/marketing/abandon-cart/abandon-cart.php';
		$this->abandon_cart_stats_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/marketing/abandon-cart/abandon-cart-stats.php';
		$this->settings = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/marketing/abandon-cart/settings.php';
		if ( wp_easycart_admin_license()->is_licensed() ) {
			remove_action( 'wp_easycart_admin_abandon_cart_load', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			add_action( 'wp_easycart_admin_abandon_cart_load', array( $this, 'load_abandon_cart' ) );
			add_action( 'wpeasycart_admin_abandon_cart', array( $this, 'load_abandon_cart_design' ) );	
			add_action( 'wpeasycart_admin_abandon_cart', array( $this, 'load_abandon_cart_stats' ) );

			// Add Form Actions
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_send_abandoned_email' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_turn_on_abandoned_automation' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_turn_off_abandoned_automation' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_remove_from_list' ) );
		}
	}

	public function process_send_abandoned_email() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_marketing' ) ) {
			return;
		}

		if ( isset( $_GET['ec_admin_form_action'] ) && 'send-abandoned-email' == $_GET['ec_admin_form_action'] && isset( $_GET['tempcart_id'] ) ) {
			$this->wpeasycart_send_email_reminder( $_GET['tempcart_id'] );
			wp_easycart_admin()->redirect( 'wp-easycart-rates', 'abandon-cart', array() );
		}
	}

	public function process_turn_on_abandoned_automation() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_marketing' ) ) {
			return;
		}

		if ( isset( $_GET['ec_admin_form_action'] ) && 'turn-on-abandoned-automation' == $_GET['ec_admin_form_action'] ) {
			if ( ! wp_next_scheduled( 'wpeasycart_abandoned_cart_automation' ) ) {
				wp_schedule_event( time() + 10, 'daily', 'wpeasycart_abandoned_cart_automation' ); 
			}
			update_option( 'ec_option_abandoned_cart_automation', 1 );
			wp_easycart_admin()->redirect( 'wp-easycart-rates', 'abandon-cart', array() );
		}
	}

	public function process_turn_off_abandoned_automation() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_marketing' ) ) {
			return;
		}

		if ( isset( $_GET['ec_admin_form_action'] ) && 'turn-off-abandoned-automation' == $_GET['ec_admin_form_action'] ) {
			wp_clear_scheduled_hook( 'wpeasycart_abandoned_cart_automation' ); 
			update_option( 'ec_option_abandoned_cart_automation', 0 );
			wp_easycart_admin()->redirect( 'wp-easycart-rates', 'abandon-cart', array() );
		}
	}

	public function process_remove_from_list() {
		if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_marketing' ) ) {
			return;
		}

		global $wpdb;
		if( isset( $_GET['ec_admin_form_action'] ) && 'remove-from-list' == $_GET['ec_admin_form_action'] && isset( $_GET['tempcart_session_id'] ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_tempcart SET hide_from_admin = 1 WHERE ec_tempcart.session_id = %s", $_GET['tempcart_session_id'] ) );
			wp_easycart_admin()->redirect( 'wp-easycart-rates', 'abandon-cart', array() );
		}
	}

	public function load_abandon_cart() {
		include( $this->settings );
	}

	public function load_abandon_cart_design() {
		include( $this->abandon_cart_file );
	}

	public function load_abandon_cart_stats() {
		include( $this->abandon_cart_stats_file );
	}

	public function wpeasycart_send_email_reminder( $tempcart_id ) {
		global $wpdb;
		$email_logo_url = get_option( 'ec_option_email_logo' );
		$cart_page_id = get_option('ec_option_cartpage');
		if ( function_exists( 'icl_object_id' ) ) {
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cart_page = get_permalink( $cart_page_id );
		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$cart_page = $https_class->makeUrlHttps( $cart_page );
		}
		if ( substr_count( $cart_page, '?' ) ) {
			$permalink_divider = "&";
		} else {
			$permalink_divider = "?";
		}
		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "X-Mailer: PHP/" . phpversion();

		$tempcart_item = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart.session_id, ec_tempcart.tempcart_id, ec_tempcart.product_id, ec_tempcart.quantity, ec_tempcart_data.translate_to, ec_tempcart_data.billing_first_name, ec_tempcart_data.billing_last_name, ec_tempcart_data.email, ec_product.title FROM ec_tempcart LEFT JOIN ec_tempcart_data ON ec_tempcart_data.session_id = ec_tempcart.session_id LEFT JOIN ec_product ON ec_product.product_id = ec_tempcart.product_id WHERE ec_tempcart.tempcart_id = %d ORDER BY ec_tempcart.session_id, last_changed_date DESC", $tempcart_id ) );
		if ( $tempcart_item->translate_to != '' ) {
			$GLOBALS['language']->set_language( $tempcart_item->translate_to );
		}
		$tempcart_rows = $wpdb->get_results( $wpdb->prepare( "SELECT ec_product.*, ec_tempcart.quantity AS tempcart_quantity, ec_tempcart.optionitem_id_1, ec_tempcart.optionitem_id_2, ec_tempcart.optionitem_id_3, ec_tempcart.optionitem_id_4, ec_tempcart.optionitem_id_5 FROM ec_tempcart, ec_product WHERE ec_product.product_id = ec_tempcart.product_id AND ec_tempcart.session_id = %s", $tempcart_item->session_id ) );
		$to = $tempcart_item->email;
		$subject = $GLOBALS['language']->get_text( 'ec_abandoned_cart_email', 'email_title' );

		ob_start();
		if ( file_exists( WP_PLUGIN_DIR . '/wp-easycart-data/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_abandoned_cart_email.php' ) ) {
			include WP_PLUGIN_DIR . '/wp-easycart-data/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_abandoned_cart_email.php';
		} else {
			include WP_PLUGIN_DIR . '/wp-easycart/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_abandoned_cart_email.php';
		}
		$message = ob_get_clean();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if ( $email_send_method == "1" ) {
			wp_mail( $to, $subject, $message, implode("\r\n", $headers), $attachments );
		} else if( $email_send_method == "0" ) {
			$mailer = new wpeasycart_mailer();
			$mailer->send_order_email( $to, $subject, $message );
		} else {
			do_action( 'wpeasycart_custom_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $to, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $subject, $message );
		}

		$wpdb->query( $wpdb->prepare( "UPDATE ec_tempcart SET ec_tempcart.abandoned_cart_email_sent = ec_tempcart.abandoned_cart_email_sent + 1 WHERE ec_tempcart.session_id = %s", $tempcart_item->session_id ) );
	}
}
new wp_easycart_admin_abandon_cart_pro();
