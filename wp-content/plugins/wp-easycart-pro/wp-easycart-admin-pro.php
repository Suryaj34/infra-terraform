<?php
/**
 * Plugin Name: WP EasyCart PRO
 * Plugin URI: http://www.wpeasycart.com
 * Description: This extension to the EasyCart adds all of the PRO features to your WP EasyCart shopping cart system.

 * Version: 5.8.14
 * Author: WP EasyCart
 * Author URI: http://www.wpeasycart.com
 * Text Domain: wp-easycart-pro
 * Domain Path: /languages

 * @package wp-easycart-pro
 * @version 5.8.14
 * @author WP EasyCart <sales@wpeasycart.com>
 * @copyright Copyright (c) 2012, WP EasyCart
 * @link http://www.wpeasycart.com
 */

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_pro' ) ) :

final class wp_easycart_admin_pro{

	protected static $_instance = null;

	public static function instance( ) {
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	}

	public function __construct( ){ 
		if ( ! defined( 'WP_EASYCART_ADMIN_PRO_PLUGIN_DIR' ) ) {
			define( 'WP_EASYCART_ADMIN_PRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'WP_EASYCART_ADMIN_PRO_PLUGIN_URL' ) ) {
			define( 'WP_EASYCART_ADMIN_PRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'WP_EASYCART_ADMIN_PRO_PLUGIN_FILE' ) ) {
			define( 'WP_EASYCART_ADMIN_PRO_PLUGIN_FILE', __FILE__ );
		}
		if ( ! defined( 'WP_EASYCART_ADMIN_PRO_VERSION' ) ) {
			define( 'WP_EASYCART_ADMIN_PRO_VERSION', '5.8.14' );
		}

		/* WP Hooks */
		add_action( 'plugins_loaded', array( $this, 'load_translation' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_notices', array( $this, 'v3_wp_easycart_check' ) );
		add_action( 'admin_init', array( $this, 'create_user_role' ) );
		add_action( "in_plugin_update_message-wp-easycart-pro/wp-easycart-admin-pro.php", array( $this, 'show_upgrade_message' ), 10, 2 );
		add_action( 'wp_easycart_admin_messages', array( $this, 'maybe_show_square_upgrade' ) );
		add_action( 'wp_easycart_admin_messages', array( $this, 'trial_expired_check' ), 1 );
		add_action( 'wpeasycart_order_paid', array( $this, 'maybe_send_push_notification' ), 10, 1 );
		add_action( 'wpeasycart_order_paid', array( $this, 'maybe_emailer_order_update' ), 10, 1 );
		add_action( 'wpeasycart_success_page_content_top', array( $this, 'maybe_add_shareasale_pixel' ), 10, 2 );
		add_action( 'wpeasycart_success_page_content_middle', array( $this, 'maybe_show_order_text_subscribe_box' ), 10, 1 );
		add_action( 'wpeasycart_order_status_update', array( $this, 'maybe_trigger_order_status_updated' ), 10, 2 );
		add_action( 'wpeasycart_tracking_info_update', array( $this, 'maybe_trigger_tracking_updated' ), 10, 5 );
		add_action( 'wpeasycart_admin_order_customer_notes_update', array( $this, 'maybe_trigger_order_notes_updated' ), 10, 2 ); 
		add_action( 'wpeasycart_admin_order_billing_update', array( $this, 'maybe_trigger_order_billing_updated' ), 10, 11 );
		add_action( 'wpeasycart_admin_order_shipping_update', array( $this, 'maybe_trigger_order_shipping_updated' ), 10, 11 );
		add_action( 'wpeasycart_order_detail_line_update', array( $this, 'maybe_trigger_order_line_updated' ), 10, 17 );
		add_action( 'wpeasycart_order_detail_line_added', array( $this, 'maybe_trigger_order_line_added' ), 10, 2 );
		add_action( 'wpeasycart_order_detail_line_deleted', array( $this, 'maybe_trigger_order_line_deleted' ), 10, 2 );
		add_action( 'wpeasycart_store_status_bubble_list_start', array( $this, 'maybe_add_store_status_bubble' ) );
		add_action( 'wpeasycart_store_status_bubble_list_start', array( $this, 'maybe_add_store_status_advert_bubble' ) );
		add_action( 'wp_easycart_main_nav_left_end', array( $this, 'maybe_add_advert_menu_item' ) );

		add_filter( 'wp_easycart_stripe_connect_fee_rate', array( $this, 'remove_stripe_fee' ) );
		add_filter( 'wp_easycart_allow_paypal_express', array( $this, 'allow_express' ) );
		add_filter( 'wp_easycart_stripe_payment_methods', array( $this, 'allow_stripe_payment_methods' ) );
		add_filter( 'wp_easycart_onepage_checkout', array( $this, 'allow_onepage_checkout' ) );
		add_filter( 'wp_easycart_admin_bulk_order_options', array( $this, 'allow_stamps_export' ) );
		add_filter( 'wp_easycart_stripe_connect_api_key', array( $this, 'maybe_customize_stripe_api_key' ) );
		add_filter( 'wp_easycart_stripe_connect_publishable_key', array( $this, 'maybe_customize_stripe_publishable_key' ) );
		
		add_action( 'wpeasycart_subscriber_added', array( $this, 'subscriber_added' ), 10, 2 );
		add_action( 'wpeasycart_insert_subscriber', array( $this, 'subscriber_inserted' ), 10, 3 );
		add_action( 'wpeasycart_remove_subscriber', array( $this, 'subscriber_removed' ), 10, 1 );

		/* WP EC Hooks */
		add_action( 'wp_easycart_admin_pro_ready', array( $this, 'load_admin_pro' ) );

		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'plugin-updates/plugin-update-checker.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'license/ec_license_manager.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'license/wp_easycart_admin_license.php' );

		$license_type = wp_easycart_admin_license( )->license_check( );
		$registration_info = get_option( 'wp_easycart_license_info' );
		$license_data = ec_license_manager( )->ec_get_license( );

		if( $license_type == "trial" ){
			add_filter( 'wp_easycart_trial_start_content', array( $this, 'remove_trial_start_content' ) );
			add_filter( 'wp_easycart_upgrade_pro_url', array( $this, 'update_pro_upgrade_url' ) );
			add_filter( 'wp_easycart_upgrade_premium_url', array( $this, 'update_premium_upgrade_url' ) );
		}

		if( $license_type == "trial" && wp_easycart_admin_license( )->license_expired ){
			add_action( 'wp_easycart_email_receipt_top', array( $this, 'show_admin_email_trial_notice' ), 10, 2 );

		}else if( wp_easycart_admin_license( )->license_expired ){
			add_action( 'wp_easycart_email_receipt_top', array( $this, 'show_admin_email_renew_notice' ), 10, 2 );
		}

		if( wp_easycart_admin_license( )->license_expired ){
			add_filter( 'wp_easycart_admin_upgrade_file', array( $this, 'replace_with_renew' ) );
			add_filter( 'admin_notices', array( $this, 'license_expired_notice' ) );
		}

		if( is_admin( ) ){
			$url = "https://connect.wpeasycart.com";
			if ( $url ) {
				if( $registration_info && isset( $registration_info['transaction_key'] ) ){
					$MyUpdateChecker = new WPECPluginUpdateChecker_2_0(
						$url . '/downloads/wp-easycart-pro/wp-easycart-admin-pro.php?transaction_key='.$registration_info['transaction_key'],
						__FILE__,
						'wp-easycart-pro'
					);

				}else if( $license_data && isset( $license_data->siteurl ) ){
					$MyUpdateChecker = new WPECPluginUpdateChecker_2_0(
						$url . '/downloads/wp-easycart-pro/wp-easycart-admin-pro.php?siteurl='.$license_data->siteurl,
						__FILE__,
						'wp-easycart-pro'
					);

				}else{
					$MyUpdateChecker = new WPECPluginUpdateChecker_2_0(
						$url . '/downloads/wp-easycart-pro/wp-easycart-admin-pro.php',
						__FILE__,
						'wp-easycart-pro'
					);
				}

			}
		}

		if ( get_option( 'ec_option_google_ga4_tag_manager_direct' ) && '' != get_option( 'ec_option_google_ga4_tag_manager_measurement_id' ) && '' != get_option( 'ec_option_google_ga4_tag_manager_api_secret' ) && '' != get_option( 'ec_option_google_ga4_tag_manager_server_url' ) ) {
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'inc/classes/analytics/wp_easycart_google_tags_direct.php' );
		}
	}

	public function subscriber_added( $email, $name ) {
		$first_name = $name;
		$last_name = '';
		$names = explode( ' ', $name );
		if ( is_array( $names ) && count( $names ) > 0 ) {
			$first_name = $names[0];
			for ( $i = 1; $i < count( $names ); $i++ ) {
				if ( $i > 1 ) {
					$last_name .= ' ';
				}
				$last_name .= $names[ $i ];
			}
		}
		if ( get_option( 'ec_option_enable_mailerlite' ) ) {
			$data = (object) array(
				'email' => $email,
				'name' => $first_name,
				'fields' => array(
					'last_name' => $last_name,
				),
			);
			$this->call_mailerlite( $data, 'https://api.mailerlite.com/api/v2/subscribers', 'POST' );
		}
		if ( get_option( 'ec_option_enable_convertkit' ) ) {
			$data = (object) array(
				'email' => $email,
				'first_name' => $first_name,
				'fields' => (object) array(
					'last_name' => $last_name,
				),
			);
			$this->call_convertkit( $data, 'https://api.convertkit.com/v3/forms/3125220/subscribe', 'POST' );
		}
		if ( get_option( 'ec_option_enable_activecampaign' ) ) {
			$data = (object) array(
				'contact' => (object) array(
					'email' => $email,
					'firstName' => $first_name,
					'lastName' => $last_name,
				),
			);
			$contact_response = $this->call_activecampaign( $data, 'contact/sync', 'POST' );
			if ( $contact_response && isset( $contact_response->contact ) && isset( $contact_response->contact->id ) ) {
				$data = (object) array(
					'contactList' => (object) array(
						'sourceid'=> 0,
						'list' => get_option( 'ec_option_activecampaign_list' ),
						'contact' => $contact_response->contact->id,
						'status' => '1'
					),
				);
				$this->call_activecampaign( $data, 'contactLists', 'POST' );
			}
		}
	}

	public function subscriber_inserted( $email, $first = '', $last = '' ) {
		if ( get_option( 'ec_option_enable_mailerlite' ) ) {
			$data = (object) array(
				'email' => $email,
				'name' => $first,
				'fields' => array(
					'last_name' => $last,
				),
			);
			$this->call_mailerlite( $data, 'https://api.mailerlite.com/api/v2/subscribers', 'POST' );
		}
		if ( get_option( 'ec_option_enable_convertkit' ) ) {
			$data = (object) array(
				'email' => $email,
				'first_name' => $first,
				'fields' => (object) array(
					'last_name' => $last,
				),
			);
			$this->call_convertkit( $data, 'https://api.convertkit.com/v3/forms/' . get_option( 'ec_option_convertkit_form' ) . '/subscribe', 'POST' );
		}
		if ( get_option( 'ec_option_enable_activecampaign' ) ) {
			$data = (object) array(
				'contact' => (object) array(
					'email' => $email,
					'firstName' => $first,
					'lastName' => $last,
				)
			);
			$contact_response = $this->call_activecampaign( $data, 'contact/sync', 'POST' );
			if ( $contact_response && isset( $contact_response->contact ) && isset( $contact_response->contact->id ) ) {
				$data = (object) array(
					'contactList' => (object) array(
						'sourceid'=> 0,
						'list' => get_option( 'ec_option_activecampaign_list' ),
						'contact' => $contact_response->contact->id,
						'status' => '1'
					)
				);
				$this->call_activecampaign( $data, 'contactLists', 'POST' );
			}
		}
	}

	public function subscriber_removed( $email ) {
		if ( get_option( 'ec_option_enable_mailerlite' ) ) {
			$data = (object) array(
				'email' => $email,
				'type' => 'unsubscribed'
			);
			$this->call_mailerlite( $data, 'https://api.mailerlite.com/api/v2/subscribers', 'POST' );
		}
		if ( get_option( 'ec_option_enable_convertkit' ) ) {
			$data = (object) array(
				'email' => $email
			);
			$this->call_convertkit( $data, 'https://api.convertkit.com/v3/unsubscribe', 'PUT' );
		}
		if ( get_option( 'ec_option_enable_activecampaign' ) ) {
			$data = (object) array(
				'email' => $email,
			);
			$contact_response = $this->call_activecampaign( $data, 'contacts', 'GET' );
			if ( $contact_response && isset( $contact_response->contacts ) && isset( $contact_response->contacts[0] ) ) {
				$data = (object) array(
					'contactList' => (object) array(
						'sourceid'=> 0,
						'list' => get_option( 'ec_option_activecampaign_list' ),
						'contact' => $contact_response->contacts[0]->id,
						'status' => '2'
					)
				);
				$this->call_activecampaign( $data, 'contactLists', 'POST' );
			}
		}
	}

	public function call_mailerlite( $data, $url, $method ) {
		$ecdb = new ec_db_admin( );
		
		$headr = array();
		$headr[] = 'X-MailerLite-ApiKey: ' . get_option( 'ec_option_mailerlite_api_key' );
		if ( 'POST' == $method ) {
			$headr[] = 'content-type: application/json';
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );
		if ( 'POST' == $method ) {
			curl_setopt( $ch, CURLOPT_POST, 1 );
		}
		if ( 'POST' == $method ) {
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
		}
		curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
		curl_setopt( $ch, CURLOPT_TIMEOUT, (int) 8);
		$response = curl_exec( $ch );
		if ( $response === false ){
			$ecdb->insert_response( 0, 1, "MailerLite", print_r( $data, true ) . ' ---- ' . $url . ' ---- ' . curl_error( $ch ) );
			$this->is_setup = false;
		} else {
			$ecdb->insert_response( 0, 0, "MailerLite", print_r( $data, true ) . ' ----' . $url . ' ---- ' .print_r( $response, true ) );
		}
		curl_close( $ch );
		return json_decode( $response );
	}

	public function call_convertkit( $data, $url, $method ) {
		$ecdb = new ec_db_admin( );
		
		if ( 'GET' == $method ) {
			$url .= '?api_key=' . get_option( 'ec_option_convertkit_api_key' );
		} else {
			$data->api_secret = get_option( 'ec_option_convertkit_api_secret' );
		}
		
		$headr = array();
		$headr[] = 'content-type: application/json';
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		if ( 'GET' != $method ) {
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );
			curl_setopt( $ch, CURLOPT_POST, ( 'POST' == $method ) );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
			curl_setopt( $ch, CURLOPT_TIMEOUT, (int) 8);
			if ( 'PUT' == $method ) {
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
			}
		}
		$response = curl_exec( $ch );
		if ( $response === false ){
			$ecdb->insert_response( 0, 1, "ConvertKit", print_r( $data, true ) . ' ---- ' . $url . ' ---- ' . curl_error( $ch ) );
			$this->is_setup = false;
		} else {
			$ecdb->insert_response( 0, 0, "ConvertKit", print_r( $data, true ) . ' ----' . $url . ' ---- ' . print_r( $response, true ) );
		}
		curl_close( $ch );
		return json_decode( $response );
	}

	public function call_activecampaign( $data, $url, $method ) {
		$ecdb = new ec_db_admin( );
		$url = get_option( 'ec_option_activecampaign_api_url' ) . '/api/3/' . $url;
		
		if ( 'GET' == $method ) {
			
		}
		
		$headr = array();
		$headr[] = 'Api-Token: ' . get_option( 'ec_option_activecampaign_api_key' );
		$headr[] = 'Accept: application/json';
		$headr[] = 'content-type: application/json';
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );
		if ( 'GET' != $method ) {
			curl_setopt( $ch, CURLOPT_POST, ( 'POST' == $method ) );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
			curl_setopt( $ch, CURLOPT_TIMEOUT, (int) 8);
			if ( 'PUT' == $method ) {
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
			}
		}
		$response = curl_exec( $ch );
		if ( $response === false ){
			$ecdb->insert_response( 0, 1, "ActiveCampaign", print_r( $data, true ) . ' ---- ' . $url . ' ---- ' . curl_error( $ch ) );
			$this->is_setup = false;
		} else {
			$ecdb->insert_response( 0, 0, "ActiveCampaign", print_r( $data, true ) . ' ----' . $url . ' ---- ' . print_r( $response, true ) );
		}
		curl_close( $ch );
		return json_decode( $response );
	}

	public function show_admin_email_trial_notice( $order_id, $is_admin ){
		if( $is_admin ){
			echo '<tr height="10"><td colspan="4" style="background-color:#a01818;"></td></tr>';
			echo '<tr><td colspan="4" align="center" style="background-color:#a01818; color:#FFF; text-align:center; font-size:26px;">';
			echo __( 'YOUR WP EASYCART TRIAL IS OVER!', 'wp-easycart-pro' ) . '<br />';
			echo '<a class="button" href="';
			echo $this->update_pro_upgrade_url( $url );
			echo '" style="color:white;" target="_blank">' . __( 'CLICK TO UPGRADE', 'wp-easycart-pro' ) . '</a>';
			echo '</td></tr>';
			echo '<tr height="10"><td colspan="4" style="background-color:#a01818;"></td></tr>';
			echo '<tr height="25"><td colspan="4" style="background-color:#ffffff;"></td></tr>';
		}
	}

	public function show_admin_email_renew_notice( $order_id, $is_admin ){
		if( $is_admin ){
			echo '<tr height="10"><td colspan="4" style="background-color:#a01818;"></td></tr>';
			echo '<tr><td colspan="4" align="center" style="background-color:#a01818; color:#FFF; text-align:center; font-size:20px;">';
			$license_data = ec_license_manager( )->ec_get_license( );
			$license_info = get_option( 'wp_easycart_license_info' );
			echo sprintf( __( 'YOUR WP EASYCART LICENSE IS EXPIRED! Please renew today to continue using your %s license.', 'wp-easycart-pro' ), ( ( $license_data->model_number == 'ec400' ) ? 'Professional' : 'Premium' ) );
			echo '<br /><a class="button" href="';
			if( $license_data->model_number == 'ec400' ){
				echo 'https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=' . $license_info['transaction_key'];
			}else{
				echo 'https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=' . $license_info['transaction_key'];
			}
			echo '" style="color:white;" target="_blank">' . __( 'CLICK TO RENEW NOW', 'wp-easycart-pro' ) . '</a>';
			echo '</td></tr>';
			echo '<tr height="10"><td colspan="4" style="background-color:#a01818;"></td></tr>';
			echo '<tr height="25"><td colspan="4" style="background-color:#ffffff;"></td></tr>';
		}
	}

	public function update_pro_upgrade_url( $url ){
		$license_info = get_option( 'wp_easycart_license_info' );
		if( is_array( $license_info ) && isset( $license_info['transaction_key'] ) ){
			return 'https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=' . $license_info['transaction_key'] . '&license_type=Professional';
		}
		return $url;
	}

	public function update_premium_upgrade_url( $url ){
		$license_info = get_option( 'wp_easycart_license_info' );
		if( is_array( $license_info ) && isset( $license_info['transaction_key'] ) ){
			return 'https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=' . $license_info['transaction_key'] . '&license_type=Premium';
		}
		return $url;
	}

	public function remove_trial_start_content( $content ){
		return "";
	}

	public function replace_with_renew( $file ){
		return WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'license/renew.php';
	}

	public function show_upgrade_message( $plugin_data, $response ){
		if( wp_easycart_admin_license( )->license_data->key_version == 'v3' ){
			echo '</p></div><div class="update-message notice inline notice-error notice-alt"><p>' . sprintf( __( 'Updates are disabled because your license no longer supports updates. Please %sclick here%s to go to your account and upgrade your license.', 'wp-easycart-pro' ), '<a href="http://www.wpeasycart.com/my-account" target="_blank">', '</a>' ) . '<script>jQuery( "#wp-easycart-pro-update > td > .notice-warning" ).remove( );</script>';
		}else if( !wp_easycart_admin_license( )->valid_license ){
			echo '</p></div><div class="update-message notice inline notice-error notice-alt"><p>' . sprintf( __( 'No license was found. If you have already purchased a license register it by %s clicking here %s. If you are in need of a license, %s purchase one here %s.', 'wp-easycart-pro' ), '<a href="admin.php?page=wp-easycart-registration&subpage=registration">', '</a>', '<a href="' . apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/' ) . '" target="_blank">', '</a>' ) . '<script>jQuery( "#wp-easycart-pro-update > td > .notice-warning" ).remove( );</script>';
		}else if( !wp_easycart_admin_license( )->active_license ){
			$registration_info = get_option( 'wp_easycart_license_info' );
			if( $registration_info ){
				echo '</p></div><div class="update-message notice inline notice-error notice-alt"><p>' . sprintf( __( 'Updates are disabled because your license has expired. Please %sclick here to renew your license%s and continue receiving updates.', 'wp-easycart-pro' ), '<a href="http://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=' . $registration_info['transaction_key'] . '" target="_blank">', '</a>' ) . '<script>jQuery( "#wp-easycart-pro-update > td > .notice-warning" ).remove( );</script>';
			}else{
				echo '</p></div><div class="update-message notice inline notice-error notice-alt"><p>' . sprintf( __( 'Updates are disabled because your license has expired. Please %sclick here%s to go to your account and renew your license.', 'wp-easycart-pro' ), '<a href="http://www.wpeasycart.com/my-account" target="_blank">', '</a>' ) . '<script>jQuery( "#wp-easycart-pro-update > td > .notice-warning" ).remove( );</script>';
			}
		}
	}

	public function load_admin_pro( ){
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			remove_action( 'wp_easycart_admin_messages', array( wp_easycart_admin( ), 'load_upsell_image' ) );
			remove_action( 'wp_easycart_admin_upsell_popup', array( wp_easycart_admin( ), 'load_upsell_popup' ) );
			add_action( 'wp_easycart_admin_product_slideout_option_types', array( $this, 'add_product_slideout_option' ) );
			add_filter( 'wp_easycart_admin_lock_icon', array( $this, 'remove_lock_icon' ) );
			add_action( 'wp_easycart_admin_reports_filters_post', array( $this, 'maybe_add_location_filter' ) );
		}
		if( isset( $_GET['page'] ) && $_GET['page'] == 'wp-easycart-rates' ){
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_abandon_cart_pro.php' );
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_coupons_pro.php' );
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_giftcards_pro.php' );
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_promotions_pro.php' );
		}
		if( isset( $_GET['page'] ) && $_GET['page'] == 'wp-easycart-products' && isset( $_GET['product_id'] ) ){
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_google_merchant_pro.php' );
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_details_products_pro.php' );
		}
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_fee_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_schedule_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_location_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_cart_importer_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_checkout_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_products_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_subscription_plans_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_downloads_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_email_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_live_shipping_rates_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_miscellaneous_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_orders_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_payments_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_subscriptions_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_taxes_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_third_party_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_user_pro.php' );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_user_role_pro.php' );
	}

	public function remove_lock_icon( $content ){
		return "";
	}

	public function remove_stripe_fee( $fee ){
		if ( wp_easycart_admin_license()->valid_license && wp_easycart_admin_license()->active_license ) {
			return 0;
		}
		return $fee;
	}

	public function allow_express( $allow ){
		if ( wp_easycart_admin_license()->valid_license && wp_easycart_admin_license()->active_license ) {
			$allow = true;
		}
		return $allow;
	}

	public function maybe_add_location_filter() {
		if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
			global $wpdb;
			$locations = $wpdb->get_results( 'SELECT ec_location.location_id, ec_location.location_label FROM ec_location ORDER BY ec_location.location_label ASC' );
			if ( is_array( $locations ) && count( $locations ) > 0 ) {
				echo '<select id="location_filter" style="max-width:300px; float:right;" onchange="wpeasycart_admin_update_chart_data( );">';
					echo '<option value="0" selected="selected">' . esc_attr__( 'No Location Filter', 'wp-easycart-pro' ) . '</option>';
					foreach ( $locations as $location ) {
						echo '<option value="' . esc_attr( $location->location_id ) . '">' . esc_attr( $location->location_label ) . '</option>';
					}
				echo '</select>';
			}
		}
	}

	public function allow_stripe_payment_methods( $payment_method_types ) {
		if ( wp_easycart_admin_license()->valid_license && wp_easycart_admin_license()->active_license ) {
			if ( get_option( 'ec_option_stripe_klarna' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) ) {
				$klarna_rules = array(
					'AT' => array( 'EUR' ),
					'BE' => array( 'EUR' ),
					'DK' => array( 'DKK' ),
					'FI' => array( 'EUR' ),
					'FR' => array( 'EUR' ),
					'DE' => array( 'EUR' ),
					'IE' => array( 'EUR' ),
					'IT' => array( 'EUR' ),
					'NL' => array( 'EUR' ),
					'NO' => array( 'NOK' ),
					'ES' => array( 'EUR' ),
					'SE' => array( 'SEK' ),
					'GB' => array( 'GBP' ),
					'US' => array( 'USD' ),
				);
				if ( isset( $klarna_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $klarna_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) {
					if ( in_array( get_option( 'ec_option_stripe_company_country' ), array( 'US' ) ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'US' ) ) ) {
						$payment_method_types[] = 'klarna';
					} else if ( in_array( get_option( 'ec_option_stripe_company_country' ), array(  'AT', 'BE', 'FI', 'FR', 'DE', 'IE', 'IT', 'NL', 'ES') ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'AT', 'BE', 'FI', 'FR', 'DE', 'IE', 'IT', 'NL', 'ES' ) ) ) {
						$payment_method_types[] = 'klarna';
					} else if ( in_array( get_option( 'ec_option_stripe_company_country' ), array( 'DK' ) ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'DK' ) ) ) {
						$payment_method_types[] = 'klarna';
					} else if ( in_array( get_option( 'ec_option_stripe_company_country' ), array( 'NO' ) ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'NO' ) ) ) {
						$payment_method_types[] = 'klarna';
					} else if ( in_array( get_option( 'ec_option_stripe_company_country' ), array( 'GB' ) ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'GB' ) ) ) {
						$payment_method_types[] = 'klarna';
					}
				}
			}
			if ( get_option( 'ec_option_stripe_klarna' ) && get_option( 'ec_option_onepage_checkout' ) && ! in_array( 'klarna', $payment_method_types ) ) {
				$payment_method_types[] = 'klarna';
			}
			if ( get_option( 'ec_option_stripe_afterpay' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'US', 'CA', 'GB', 'FR', 'AU', 'NZ', 'ES' ) ) && $GLOBALS['ec_cart_data']->cart_data->billing_country == get_option( 'ec_option_stripe_company_country' ) ) {
				$afterpay_rules = array(
					'AU' => array( 'AUD' ),
					'CA' => array( 'CAD' ),
					'NZ' => array( 'NZD' ),
					'GB' => array( 'GBP' ),
					'US' => array( 'USD' ),
					'FR' => array( 'EUR' ),
					'ES' => array( 'EUR' ),
				);
				if ( isset( $afterpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $afterpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) {
					$payment_method_types[] = 'afterpay_clearpay';
				}
			}
			if ( get_option( 'ec_option_stripe_afterpay' ) && get_option( 'ec_option_onepage_checkout' ) && ! in_array( 'afterpay_clearpay', $payment_method_types ) ) {
				$payment_method_types[] = 'afterpay_clearpay';
			}
			if ( get_option( 'ec_option_stripe_affirm' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'US' ) ) ) {
				$payment_method_types[] = 'affirm';
			} else if ( get_option( 'ec_option_stripe_affirm' ) && get_option( 'ec_option_onepage_checkout' ) ) {
				$payment_method_types[] = 'affirm';
			}
			if ( get_option( 'ec_option_stripe_link' ) ) {
				$payment_method_types[] = 'link';
			}
			if ( get_option( 'ec_option_stripe_alipay' ) ) {
				$alipay_rules = array(
					'AU' => array( 'AUD', 'CYN' ),
					'CA' => array( 'CAD', 'CYN' ),
					'AT' => array( 'EUR', 'CYN' ),
					'BE' => array( 'EUR', 'CYN' ),
					'BG' => array( 'EUR', 'CYN' ),
					'CY' => array( 'EUR', 'CYN' ),
					'CZ' => array( 'EUR', 'CYN' ),
					'DK' => array( 'EUR', 'CYN' ),
					'EE' => array( 'EUR', 'CYN' ),
					'FI' => array( 'EUR', 'CYN' ),
					'FR' => array( 'EUR', 'CYN' ),
					'DE' => array( 'EUR', 'CYN' ),
					'GR' => array( 'EUR', 'CYN' ),
					'IE' => array( 'EUR', 'CYN' ),
					'IT' => array( 'EUR', 'CYN' ),
					'LV' => array( 'EUR', 'CYN' ),
					'LT' => array( 'EUR', 'CYN' ),
					'LU' => array( 'EUR', 'CYN' ),
					'MT' => array( 'EUR', 'CYN' ),
					'NL' => array( 'EUR', 'CYN' ),
					'NO' => array( 'EUR', 'CYN' ),
					'PT' => array( 'EUR', 'CYN' ),
					'RO' => array( 'EUR', 'CYN' ),
					'SK' => array( 'EUR', 'CYN' ),
					'SI' => array( 'EUR', 'CYN' ),
					'ES' => array( 'EUR', 'CYN' ),
					'SE' => array( 'EUR', 'CYN' ),
					'CH' => array( 'EUR', 'CYN' ),
					'GB' => array( 'GBP', 'CYN' ),
					'HK' => array( 'HKD', 'CYN' ),
					'JP' => array( 'JPY', 'CYN' ),
					'MY' => array( 'MYR', 'CYN' ),
					'NZ' => array( 'NZD', 'CYN' ),
					'SG' => array( 'SGD', 'CYN' ),
					'US' => array( 'USD', 'CYN' ),
				);
				if ( isset( $alipay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $alipay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) {
					$payment_method_types[] = 'alipay';
				}
			}
			if ( get_option( 'ec_option_stripe_wechat' ) ) {
				$wechatpay_rules = array(
					'AU' => array( 'AUD', 'CYN' ),
					'CA' => array( 'CAD', 'CYN' ),
					'AT' => array( 'EUR', 'CYN' ),
					'BE' => array( 'EUR', 'CYN' ),
					'DK' => array( 'EUR', 'DKK', 'CYN' ),
					'FI' => array( 'EUR', 'CYN' ),
					'FR' => array( 'EUR', 'CYN' ),
					'DE' => array( 'EUR', 'CYN' ),
					'IE' => array( 'EUR', 'CYN' ),
					'IT' => array( 'EUR', 'CYN' ),
					'LU' => array( 'EUR', 'CYN' ),
					'NL' => array( 'EUR', 'CYN' ),
					'NO' => array( 'EUR', 'NOK', 'CYN' ),
					'PT' => array( 'EUR', 'CYN' ),
					'ES' => array( 'EUR', 'CYN' ),
					'SE' => array( 'EUR', 'SEK', 'CYN' ),
					'CH' => array( 'EUR', 'CHF', 'CYN' ),
					'GB' => array( 'GBP', 'CYN' ),
					'HK' => array( 'HKD', 'CYN' ),
					'JP' => array( 'JPY', 'CYN' ),
					'SG' => array( 'SGD', 'CYN' ),
					'US' => array( 'USD', 'CYN' ),
				);
				if ( isset( $wechatpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $wechatpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) {
					$payment_method_types[] = 'wechat_pay';
				}
			}
			if ( get_option( 'ec_option_stripe_sepa' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'GB' ) ) ) {
				$payment_method_types[] = 'sepa_debit';
			}
			if ( get_option( 'ec_option_stripe_becs' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'AU' ) ) ) {
				$payment_method_types[] = 'au_becs_debit';
			}
			if ( get_option( 'ec_option_stripe_bancontact' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'BE' ) ) ) {
				$payment_method_types[] = 'bancontact';
			}
			if ( get_option( 'ec_option_stripe_blik' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'PL' ) ) ) {
				$payment_method_types[] = 'blik';
			}
			if ( get_option( 'ec_option_stripe_boleto' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'BR' ) ) ) {
				$payment_method_types[] = 'boleto';
			}
			if ( get_option( 'ec_option_stripe_eps' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'AT' ) ) ) {
				$payment_method_types[] = 'eps';
			}
			if ( get_option( 'ec_option_stripe_fpx' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'MY' ) ) ) {
				$payment_method_types[] = 'fpx';
			}
			if ( get_option( 'ec_option_stripe_giropay' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'DE' ) ) ) {
				$payment_method_types[] = 'giropay';
			}
			if ( get_option( 'ec_option_stripe_grabpay' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'MY', 'SG' ) ) ) {
				$grabpay_rules = array(
					'MY' => array( 'MYR' ),
					'SG' => array( 'SGD' ),
				);
				if ( isset( $grabpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) && in_array( get_option('ec_option_stripe_currency' ), $grabpay_rules[ get_option( 'ec_option_stripe_company_country' ) ] ) ) {
					$payment_method_types[] = 'grabpay';
				}
			}
			if ( get_option( 'ec_option_stripe_enable_ideal' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'NL' ) ) ) {
				$payment_method_types[] = 'ideal';
			}
			if ( get_option( 'ec_option_stripe_konbini' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'JP' ) ) ) {
				$payment_method_types[] = 'konbini';
			}
			if ( get_option( 'ec_option_stripe_oxxo' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'MX' ) ) ) {
				$payment_method_types[] = 'oxxo';
			}
			if ( get_option( 'ec_option_stripe_p24' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'PL' ) ) ) {
				$payment_method_types[] = 'p24';
			}
			if ( get_option( 'ec_option_stripe_paynow' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'SG' ) ) ) {
				$payment_method_types[] = 'paynow';
			}
			if ( get_option( 'ec_option_stripe_pix' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'BR' ) ) ) {
				$payment_method_types[] = 'pix';
			}
			if ( get_option( 'ec_option_stripe_promptpay' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'TH' ) ) ) {
				$payment_method_types[] = 'promptpay';
			}
			if ( get_option( 'ec_option_stripe_sofort' ) && isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) && in_array( $GLOBALS['ec_cart_data']->cart_data->billing_country, array( 'AT', 'BE', 'DE', 'IT', 'NL', 'ES' ) ) ) {
				$payment_method_types[] = 'sofort';
			}
		}
		return $payment_method_types;
	}
	
	public function allow_onepage_checkout( $enabled ) {
		if ( wp_easycart_admin_license()->valid_license && wp_easycart_admin_license()->active_license && get_option( 'ec_option_onepage_checkout' ) ) {
			$enabled = true;
		}
		return $enabled;
	}
	
	public function allow_stamps_export( $options ) {
		if ( wp_easycart_admin_license()->valid_license && wp_easycart_admin_license()->active_license ) {
			$options[] = array(
				'name'	=> 'export-orders-stamps-csv',
				'label'	=> __( 'Export Selected CSV (Stamps.com)', 'wp-easycart-pro' )
			);
			$options[] = array(
				'name'	=> 'export-orders-stamps-csv-all',
				'label'	=> __( 'Export All CSV (Stamps.com)', 'wp-easycart-pro' )
			);
		}
		return $options;
	}

	public function maybe_customize_stripe_api_key( $api_key ) {
		if ( '' == get_option( 'ec_option_stripe_connect_sandbox_publishable_key' ) || '' == get_option( 'ec_option_stripe_connect_sandbox_access_token' ) ) {
			return $api_key;
		}
		if ( $GLOBALS['ec_user']->user_id && isset( $GLOBALS['ec_user']->is_stripe_test_user ) && $GLOBALS['ec_user']->is_stripe_test_user ) {
			return get_option( 'ec_option_stripe_connect_sandbox_access_token' );
		}
		return $api_key;
	}

	public function maybe_customize_stripe_publishable_key( $api_key ) {
		if ( '' == get_option( 'ec_option_stripe_connect_sandbox_publishable_key' ) || '' == get_option( 'ec_option_stripe_connect_sandbox_access_token' ) ) {
			return $api_key;
		}
		if ( $GLOBALS['ec_user']->user_id && isset( $GLOBALS['ec_user']->is_stripe_test_user ) && $GLOBALS['ec_user']->is_stripe_test_user ) {
			return get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
		}
		return $api_key;
	}

	public function add_product_slideout_option( ){
		echo '<option value="2">' . esc_attr__( 'Modifiers (Advanced Options)', 'wp-easycart-pro' ) . '</option>';
	}

	public function maybe_show_square_upgrade( ){
		if( get_option( 'ec_option_payment_process_method' ) == 'square' && get_option( 'ec_option_square_application_id' ) != '' ){
			$app_redirect_state = rand( 1000000, 9999999 );
			echo '<div style="width:100%; text-align:center; max-width:100%;"><a href="https://support.wpeasycart.com/square/?url=' . admin_url( ) . '&state=' . $app_redirect_state . '"><img src="' . plugins_url( 'wp-easycart-pro/admin/images/Square-Upgrade-Banner.png' ) . '" style="max-width:100%; height:auto;" alt="' . sprintf( __( 'Update Your %s Setup', 'wp-easycart-pro' ), 'Square' ) . '" /></a></div>';
		}
	}

	public function trial_expired_check( ){
		if( isset( wp_easycart_admin_license( )->license_data->response_error ) ){
			echo '<h3 style="background:#00bcd4; color:#FFF; padding:20px; margin:0 20px 20px 19px; text-align:center;">' . __( 'The WP EasyCart Registration system is currently being worked on and is temporarily unavailable. In the meantime, you should not see any interruption in your services or licensing. You may close this message.', 'wp-easycart-pro' ) . '</h3>';

		}else if( isset( wp_easycart_admin_license( )->license_data->is_trial ) && wp_easycart_admin_license( )->license_data->is_trial && strtotime( date( 'Y-m-d', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ) ) < strtotime( date( 'Y-m-d' ) ) ){
			echo '<h3 style="background:#a01818; color:#FFF; padding:20px; margin:0 10px 20px; text-align:center;">' . __( 'Your PRO Trial Has EXPIRED.', 'wp-easycart-pro' ) . ' <a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/';
			$license_info = get_option( 'wp_easycart_license_info' );
			if( is_array( $license_info ) && isset( $license_info['transaction_key'] ) ){ 
				echo '?transaction_key=' . $license_info['transaction_key'];
			}
			echo '" target="_blank" style="margin-left:10px; border-radius:5px; background:#FFF; padding:5px 10px; font-size:14px; text-decoration:none; text-transform:uppercase; color:#a01818; border:2px solid #f1f1f1;">' . __( 'Upgrade Now', 'wp-easycart-pro' ) . '</a></h3>';
			remove_all_actions( 'wp_easycart_admin_shell_content' );
			$registration = new wp_easycart_admin_registration( );
			$registration->load_registration_status( );

		}else if( isset( wp_easycart_admin_license( )->license_data->is_trial ) && wp_easycart_admin_license( )->license_data->is_trial ){
			echo '<h3 style="background:#00bcd4; color:#FFF; padding:20px; margin:0 20px 20px 19px; text-align:center;">' . sprintf( __( 'Your PRO Trial is Active. Trial Expires on %s', 'wp-easycart-pro' ), date( 'F d, Y', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ) ) . ' <a href="' . apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/' ) . '" target="_blank" style="margin-left:10px; border-radius:5px; background:#FFF; padding:5px 10px; font-size:14px; text-decoration:none; text-transform:uppercase; color:#02bcd4; border:2px solid #f1f1f1;">' . __( 'Upgrade Now', 'wp-easycart-pro' ) . '</a></h3>';

		}
	}

	public function __clone( ) {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-easycart-pro' ), '1.0' );
	}

	public function __wakeup( ){
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-easycart-pro' ), '1.0' );
	}

	public function load_translation( ){
		load_plugin_textdomain( 'wp-easycart-pro', FALSE, '/wp-easycart-pro/languages' );
	}

	public function load_frontend_scripts() {
		if( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == 'checkout_success' && get_option( 'ec_option_enable_cloud_messages' ) ) {
			wp_register_style( 'wpeasycart_intl_phone_css', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'vendor/intl-tel-input/css/intlTelInput.min.css', array(), WP_EASYCART_ADMIN_PRO_VERSION );
			wp_enqueue_style( 'wpeasycart_intl_phone_css' );
			wp_register_script( 'wpeasycart_intl_phone_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'vendor/intl-tel-input/js/intlTelInput.min.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
			wp_enqueue_script( 'wpeasycart_intl_phone_js' );
			wp_register_script( 'wpeasycart_intl_phone_util_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'vendor/intl-tel-input/js/utils.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
			wp_enqueue_script( 'wpeasycart_intl_phone_util_js' );
		}
		if ( get_option( 'ec_option_enable_shareasale' ) && '' != get_option( 'ec_option_shareasale_merchant_id' ) && ( ! isset( $_GET['ec_page'] ) || 'checkout_success' != $_GET['ec_page'] ) ) {
			wp_enqueue_script( 'wpeasycart_shareasale_js', 'https://www.dwin1.com/19038.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION, false );
		}
	}

	public function load_scripts() {
		if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_GET['page'] ) && substr( $_GET['page'], 0, 11 ) == "wp-easycart" ) {
			if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-rates" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "coupons" ) {
				wp_register_script( 'wp_easycart_admin_coupons_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . '/admin/js/coupons.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_coupons_js' );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-rates" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "promotions" ) {
				wp_register_script( 'wp_easycart_admin_promotions_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . '/admin/js/promotions.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_promotions_js' );	

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-orders" && ( !isset( $_GET['subpage'] ) || $_GET['subpage'] == "orders" ) ) {
				wp_register_script( 'wp_easycart_admin_orders_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/orders.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_orders_pro_js' );
				wp_localize_script( 'wp_easycart_admin_orders_pro_js', 'wp_easycart_pro_admin_orders_language', array(
					'agree-terms-yes' => __( 'Agreed to Terms: Yes', 'wp-easycart-pro' ),
					'agree-terms-no' => __( 'Agreed to Terms: No', 'wp-easycart-pro' )
				) );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-orders" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "subscriptions" ) {
				wp_register_script( 'wp_easycart_admin_subscriptions_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/subscriptions.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_subscriptions_pro_js' );
				wp_localize_script( 'wp_easycart_admin_subscriptions_pro_js', 'wp_easycart_pro_admin_subscriptions_language', array(
					'canceled' => __( 'Canceled', 'wp-easycart-pro' )
				) );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-products" && ( !isset( $_GET['subpage'] ) || $_GET['subpage'] == "products" ) ) {
				wp_register_script( 'wp_easycart_admin_products_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/products.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_products_pro_js' );
				wp_localize_script( 'wp_easycart_admin_products_pro_js', 'wp_easycart_pro_admin_products_language', array(
					'confirm-option-change' => __( 'Changing your option sets will reset your variant information, please confirm to continue.', 'wp-easycart-pro' ),
					'max-5-options' => __( 'There is a maximum of 5 option sets allowed per product. Try adding modifiers, which are unlimited.', 'wp-easycart-pro' ),
				) );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-orders" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "downloads" ) {
				wp_register_script( 'wp_easycart_admin_downloads_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/downloads.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_downloads_js' );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-settings" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "shipping-settings" ) {
				wp_register_script( 'wp_easycart_admin_shipping_settings_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/shipping-settings.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_shipping_settings_pro_js' );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-settings" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "checkout" ) {
				wp_register_script( 'wp_easycart_admin_checkout_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/checkout.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_checkout_pro_js' );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-settings" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "third-party" ) {
				wp_register_script( 'wp_easycart_admin_third_party_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/third-party.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_third_party_pro_js' );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-settings" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "fee" ) {
				wp_register_script( 'wp_easycart_admin_fees_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/fees.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_fees_pro_js' );

			} else if ( isset( $_GET['page'] ) && $_GET['page'] == "wp-easycart-settings" && isset( $_GET['subpage'] ) && $_GET['subpage'] == "schedule" ) {
				wp_register_script( 'wp_easycart_admin_schedules_pro_js', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . 'admin/js/schedules.js', array( 'jquery' ), WP_EASYCART_ADMIN_PRO_VERSION );
				wp_enqueue_script( 'wp_easycart_admin_schedules_pro_js' );
			}
		}
		if ( wp_easycart_admin_license( )->is_licensed( ) && ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_GET['page'] ) && substr( $_GET['page'], 0, 11 ) == "wp-easycart" ) {
			wp_register_style( 'wp_easycart_admin_pro_css', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . '/admin/css/wp-easycart-admin-pro.css', array( ), WP_EASYCART_ADMIN_PRO_VERSION );
			wp_enqueue_style( 'wp_easycart_admin_pro_css' );

		} else if( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_GET['page'] ) && substr( $_GET['page'], 0, 11 ) == "wp-easycart" ) {
			wp_register_style( 'wp_easycart_admin_pro_css', WP_EASYCART_ADMIN_PRO_PLUGIN_URL . '/admin/css/wp-easycart-admin-expired-pro.css', array( ), WP_EASYCART_ADMIN_PRO_VERSION );
			wp_enqueue_style( 'wp_easycart_admin_pro_css' );
		}
	}

	public function v3_wp_easycart_check( ){
		if( !$this->is_wp_easycart_installed( ) ){
			echo '<div class="updated">';
			echo '<p>' . sprintf( __( 'WP EasyCart PRO requires WP EasyCart Version 4.0.0 or greater. Please %sclick here to install WP EasyCart%s', 'wp-easycart-pro' ), '<a href="' . admin_url( 'plugin-install.php?s=wp-easycart&tab=search&type=term' ) . '">', '</a>' ) . '</p>';
			echo '</div>';

		}else if( $this->is_wp_easycart_v3( ) ){
			echo '<div class="updated">';
			echo '<p>' . sprintf( __( 'WP EasyCart PRO requires WP EasyCart Version 4.0.0 or greater. Please %sclick here to update your WP EasyCart plugin%s', 'wp-easycart-pro' ), '<a href="' . admin_url( 'update-core.php' ) . '">', '</a>' ) . '</p>';
			echo '</div>';
			echo '<style>#setting-error-wpec_tgmpa{ display:none !important; }</style>';
		}
	}

	public function create_user_role() {
		global $wp_roles;

		if ( ! class_exists( 'WP_Roles' ) ) {
			return;
		}

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( wp_roles()->is_role( 'wpec_store_manager' ) ) {
			return;
		}

		$wpec_capabilities = array( 'wpec_manager', 'wpec_reports', 'wpec_store_status', 'wpec_products', 'wpec_orders', 'wpec_users', 'wpec_marketing', 'wpec_settings', 'wpec_diagnostics', 'wpec_registration' );

		// remove_role( 'wpec_store_manager' );
		add_role(
			'wpec_store_manager',
			__( 'WP EasyCart Store Manager', 'wp-easycart-pro' ),
			array(
				'read' => true,
				'wpec_manager' => true,
				'wpec_reports' => true,
				'wpec_store_status' => true,
				'wpec_products' => true,
				'wpec_orders' => true,
				'wpec_users' => true,
				'wpec_marketing' => true,
				'wpec_settings' => true,
				'wpec_diagnostics' => true,
				'wpec_registration' => true,
			)
		);

		foreach ( $wpec_capabilities as $wpec_capability ) {
			$wp_roles->add_cap( 'wpec_store_manager', $wpec_capability );
			$wp_roles->add_cap( 'administrator', $wpec_capability );
		}
	}

	public function license_expired_notice( ){
		echo '<div class="notice notice-error">';
		echo '<p>';
		$license_data = ec_license_manager( )->ec_get_license( );
		$license_info = get_option( 'wp_easycart_license_info' );
		if( wp_easycart_admin_license( )->license_data->is_trial ){
			echo sprintf( __( 'Your WP EasyCart trial expired on %s. Please upgrade today to continue using the WP EasyCart.', 'wp-easycart-pro' ), date( 'F j, Y', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ) );
			echo '<a class="button" href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=' . $license_info['transaction_key'];
			echo '" style="margin-left:10px; color:white; background-color:#0085ba;" target="_blank">' . __( 'UPGRADE NOW', 'wp-easycart-pro' ) . '</a>';
		}else{
			echo sprintf( __( 'Your WP EasyCart license expired on %s. You have been reverted to the FREE edition and 2%% fees may apply. Please renew today to continue using your %s license.', 'wp-easycart-pro' ), date( 'F j, Y', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ), ( ( $license_data->model_number == 'ec400' ) ? 'Professional' : 'Premium' ) );
			echo '<a class="button" href="';
			if( $license_data->model_number == 'ec400' ){
				echo 'https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=' . $license_info['transaction_key'];
			}else{
				echo 'https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=' . $license_info['transaction_key'];
			}
			echo '" style="margin-left:10px; color:white; background-color:#0085ba;" target="_blank">' . __( 'RENEW NOW', 'wp-easycart-pro' ) . '</a>';
		}
		echo '</p>';
		echo '</div>';
	}

	public function is_wp_easycart_installed( ){
		if( file_exists( WP_PLUGIN_DIR . '/wp-easycart/wpeasycart.php' ) ){
			return true;
		}else{
			return false;
		}
	}

	public function is_wp_easycart_v3( ){
		$plugin_file = WP_PLUGIN_DIR . '/wp-easycart/wpeasycart.php';
		if( file_exists( $plugin_file ) ){
			$plugin_info = get_plugin_data( $plugin_file );
			if( version_compare( $plugin_info['Version'], '4.0.0' ) < 0 ){
				return true;
			}
		}
		return false;
	}

	public function maybe_send_push_notification( $order_id ){
		if( get_option( 'ec_option_enable_push_notifications' ) ){
			global $wpdb;
			$order = $wpdb->get_row( $wpdb->prepare( "SELECT order_id, grand_total FROM ec_order WHERE order_id = %d", $order_id ) );
			$app_url = str_replace( "www.", "", str_replace( "http://", "", str_replace( "https://", "", get_site_url( ) ) ) );
			$license = get_option( 'wp_easycart_license_info' );
			$transaction_key = $license['transaction_key'];
			$url = "https://connect.wpeasycart.com/notifications/create.php?order_id=".$order->order_id."&grand_total=".urlencode( $GLOBALS['currency']->get_currency_display( $order->grand_total ) )."&app_url=".urlencode( $app_url )."&transaction_key=".urlencode( $transaction_key );

			$ch = curl_init( );
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POST, false ); 
			curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_TIMEOUT, (int) 30 );
			curl_exec(   $ch );
			curl_close(  $ch );
		}
	}
	
	public function maybe_emailer_order_update( $order_id ) {
		if ( get_option( 'ec_option_enable_mailerlite' ) ) {
			global $wpdb;
			$ecdb = new ec_db_admin( );
			$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE order_id = %d", $order_id ) );
			$order_details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_orderdetail WHERE order_id = %d", $order_id ) );
			
			// Get Subscriber ID
			$data = (object) array();
			$subscriber_id_response = $this->call_mailerlite( $data, 'https://api.mailerlite.com/api/v2/subscribers/' . urlencode( $order->user_email ), 'GET' );
			$subscriber_id = ( $subscriber_id_response && isset( $subscriber_id_response->id ) ) ? $subscriber_id_response->id : 0;
			
			// Process products for groups
			$mailerlite_groups = get_option( 'ec_option_mailerlite_groups' );
			foreach ( $order_details as $order_detail ) {
				if ( $mailerlite_groups && isset( $mailerlite_groups->{ strval( $order_detail->product_id ) } ) ) {
					$data = (object) array(
						'group_name' => $mailerlite_groups->{ strval( $order_detail->product_id ) }
					);
					$this->call_mailerlite( $data, 'https://api.mailerlite.com/api/v2/groups/group_name/subscribers/' . $subscriber_id . '/assign', 'POST' );
				}
			}
		}
		
		if ( get_option( 'ec_option_enable_convertkit' ) ) {
			global $wpdb;
			$ecdb = new ec_db_admin( );
			$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE order_id = %d", $order_id ) );
			$order_details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_orderdetail WHERE order_id = %d", $order_id ) );
			
			// Get Subscriber ID
			$data = (object) array(
				'purchase' => (object) array(
					'transaction_id' => 'WPEC-' . $order->order_id,
					'email_address' => $order->user_email,
					'first_name' => $order->billing_first_name,
					'currency' => esc_attr( $GLOBALS['currency']->get_currency_code( ) ),
					'transaction_time' => $order->order_date,
					'subtotal' => number_format( $order->sub_total, 2, '.', '' ),
					'tax' => number_format( $order->tax_total + $order->vat_total + $order->hst_total + $order->pst_total + $order->gst_total + $order->duty_total, 2, '.', '' ),
					'shipping' => number_format( $order->shipping_total, 2, '.', '' ),
					'discount' => number_format( $order->discount_total, 2, '.', '' ),
					'total' => number_format( $order->grand_total, 2, '.', '' ),
					'status' => 'paid',
					'products' => array(),
				)
			);
			foreach( $order_details as $order_detail ) {
				$data->purchase->products[] = (object) array(
					'pid' => $order_detail->product_id,
					'lid' => $order_detail->orderdetail_id,
					'name' => $order_detail->title,
					'sku' => $order_detail->model_number,
					'unit_price' => number_format( $order_detail->unit_price, 2, '.', '' ),
					'quantity' => $order_detail->quantity,
				);
			}
			$this->call_convertkit( $data, 'https://api.convertkit.com/v3/purchases', 'POST' );
		}

		if ( get_option( 'ec_option_enable_activecampaign' ) ) {
			global $wpdb;
			$ecdb = new ec_db_admin( );
			$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE order_id = %d", $order_id ) );
			$order_details = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_orderdetail WHERE order_id = %d", $order_id ) );
			
			$data = (object) array(
				'contact' => (object) array(
					'email' => $order->user_email,
					'firstName' => $order->billing_first_name . ' ' . $order->billing_last_name,
				)
			);
			$contact_response = $this->call_activecampaign( $data, 'contact/sync', 'POST' );
			$activecampaign_groups = get_option( 'ec_option_activecampaign_groups' );
			if ( $contact_response && isset( $contact_response->contact ) && isset( $contact_response->contact->id ) ) {
				foreach ( $order_details as $order_detail ) {
					if ( $activecampaign_groups && isset( $activecampaign_groups->{ strval( $order_detail->product_id ) } ) ) {
						$data = (object) array(
							'contactList' => (object) array(
								'sourceid'=> 0,
								'list' => $activecampaign_groups->{ strval( $order_detail->product_id ) },
								'contact' => $contact_response->contact->id,
								'status' => '1'
							)
						);
						$this->call_activecampaign( $data, 'contactLists', 'POST' );
					}
				}
			}
		}
	}
	
	public function maybe_add_shareasale_pixel( $order_id, $order ) {
		if ( get_option( 'ec_option_enable_shareasale' ) && '' != get_option( 'ec_option_shareasale_merchant_id' ) && ! $order->success_page_shown ) {
			$valid_currency_codes = array( 'USD', 'CAD', 'EUR', 'MXN', 'IDR', 'GBP', 'NZD', 'AUD', 'SGD', 'HKD', 'CHF', 'RUB', 'JPY', 'CNY', 'BRL', 'INR', 'NOK', 'AED', 'THB' );
			$convert_currency = ( get_option( 'ec_option_shareasale_currency_conversion' ) && in_array( $GLOBALS['currency']->get_currency_code(), $valid_currency_codes ) ) ? true : false;
			
			$sku_list = array();
			$price_list = array();
			$quantity_list = array();
			$couponcode = $order->promo_code;
			
			foreach( $order->orderdetails as $order_line_item ) {
				$sku_list[] = $order_line_item->model_number;
				$price_list[] = ( $convert_currency ) ? $GLOBALS['currency']->convert_price( $order_line_item->unit_price ) : $order_line_item->unit_price;
				$quantity_list[] = $order_line_item->quantity;
			}

			$optional_string = '';
			if ( $couponcode != '' ) {
				$optional_string .= '&couponcode=' . esc_attr( $couponcode );
			}

			if ( get_option( 'ec_option_shareasale_send_details' ) ) {
				$optional_string .= '&skulist=' . urlencode( implode( ',', $sku_list ) );
				$optional_string .= '&pricelist=' . urlencode( implode( ',', $price_list ) );
				$optional_string .= '&quantitylist=' . urlencode( implode( ',', $quantity_list ) );
			}
			
			if( $convert_currency ) {
				$optional_string .= '&currency=' . urlencode( $GLOBALS['currency']->get_currency_code() );
			}

			$subtotal = ( $convert_currency ) ? $GLOBALS['currency']->convert_price( $order->sub_total ) : $order->sub_total;
			$discount_total = ( $convert_currency ) ? $GLOBALS['currency']->convert_price( $order->discount_total ) : $order->discount_total;
			$subtotal = ( $subtotal - $discount_total < 1 ) ? 1 : ( $subtotal - $discount_total );
			
			echo '<img src="https://www.shareasale.com/sale.cfm?tracking=' . esc_attr( $order_id ) . '&amount=' . esc_attr( number_format( $subtotal, 2, '.', '' ) ) . '&merchantID=' . esc_attr( get_option( 'ec_option_shareasale_merchant_id' ) ) .'&transtype=sale' . $optional_string . '" width="1" height="1">';
			echo '<script src="https://www.dwin1.com/19038.js" type="text/javascript" defer="defer"></script>';
		}
	}
	
	public function maybe_show_order_text_subscribe_box( $order_id ){
		if( get_option( 'ec_option_enable_cloud_messages' ) ){
			echo '<div style="display:flex; flex-direction:row; align-items:center; column-count:2; border:1px solid #CCC; background:#fefefe; padding:15px; justify-content:flex-start; align-content:center; margin-top:25px; position:relative;">
				<div id="text_notification_loader" style="position:absolute; width:100%; height:100%; top:0; left:0; display:none;">
					<div class="ec_store_loader" style="display:block; top:50%; left:50%; margin-top:-16px; margin-left:-16px;">Loading...</div>
					<div class="ec_store_loader_bg" style="display:block; position:absolute;"></div>
				</div>
				<div class="ec_order_success_loader" style="width:150px; display:flex; flex-direction:column;">
					<div class="ec_order_success_loader_loaded">
						<span class="dashicons dashicons-smartphone" style="font-size:86px; color:#222;"></span>
					</div>
				</div>
				<div style="display:flex; flex-direction:column; text-align:left;">
					<h2 class="ec_cart_success_title" style="margin:0; text-align:left;">' . wp_easycart_language( )->get_text( 'cart_success', 'cart_text_notification_title' ) . '</h2>
					<p class="ec_cart_success_subtitle" style="text-align:left; margin:0 0 5px;">' . wp_easycart_language( )->get_text( 'cart_success', 'cart_text_notification_description' ) . '</p>
					<input id="text_phone_number" value="" type="tel" style="margin:0 0 5px;" placeholder="' . wp_easycart_language( )->get_text( 'cart_success', 'cart_text_notification_placeholder' ) . '" />
					<input type="hidden" id="text_order_id" value="' . (int) $order_id . '" />
					<div class="ec_cart_error_text_subscribe_error" id="text_notification_error">' . wp_easycart_language( )->get_text( 'cart_success', 'cart_text_notification_error' ) . '</div>
					<div class="ec_cart_success_text_subscribe_success" id="text_notification_success">' . wp_easycart_language( )->get_text( 'cart_success', 'cart_text_notification_success' ) . '</div>
					<p class="ec_cart_success_continue_shopping_button" style="text-align:left; margin:0;">
						<a href="#" style="position:relative;" id="wp_easycart_text_notification_subscribe_button">' . wp_easycart_language( )->get_text( 'cart_success', 'cart_text_notification_button' ) . '</a>
					</p>
				</div>
			</div>';
			echo '<script>
			jQuery( document ).ready( function( ){
				var input = document.getElementById( "text_phone_number" );
				var wpeasycart_text_iti = window.intlTelInput( input, {
					initialCountry: "auto",
					geoIpLookup: function( success, failure ) {
						jQuery.get( "https://ipinfo.io", function() {}, "jsonp" ).always( function( resp ) {
							var countryCode = (resp && resp.country) ? resp.country : "' . esc_attr( strtolower( get_option( 'ec_option_cloud_messages_default_country' ) ) ). '";
							success(countryCode);
						});
					},
					preferredCountries: [';
			$is_first = true;
			if ( is_array( get_option( 'ec_option_cloud_messages_preferred_countries' ) ) ) {
				foreach( get_option( 'ec_option_cloud_messages_preferred_countries' ) as $pref_country ) {
					if ( ! $is_first ) {
						echo ',';
					}
					echo '"' . esc_attr( strtolower( $pref_country ) ) . '"';
					$is_first = false;
				}
			} else if ( is_string( get_option( 'ec_option_cloud_messages_preferred_countries' ) ) ) {
				echo '"' . esc_attr( strtolower( get_option( 'ec_option_cloud_messages_preferred_countries' ) ) ) . '"';
			}
			echo '],
					separateDialCode: true
				});
				jQuery( document.getElementById( "wp_easycart_text_notification_subscribe_button" ) ).on( "click", function() {
					wp_easycart_text_notification_subscribe( wpeasycart_text_iti );
					return false;
				} );
			} );
			</script>';
		}
	}
	
	public function subscribe_text_order_notifications() {
		if( !isset( $_POST['text_number'] ) ) {
			return false;
		}

		if( !isset( $_POST['order_id'] ) ) {
			return false;
		}

		global $wpdb;

		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest == '' ) {
			$order_row = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id FROM ec_order WHERE user_id = %d AND order_id = %d', (int) $GLOBALS['ec_user']->user_id, (int) $_POST['order_id'] ) );
		} else if ( $GLOBALS['ec_cart_data']->cart_data->guest_key != '' ) {
			$order_row = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id FROM ec_order WHERE guest_key = %s', $GLOBALS['ec_cart_data']->cart_data->guest_key ) );
		} else {
			return false;
		}

		if ( ! $order_row ) {
			return false;
		}
		
		$this->subscribe_text_notification( (int) $_POST['order_id'], $_POST['text_number'] );
		
	}
	
	public function subscribe_text_notification( $order_id, $phone_number ) {
		
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
				'site_url' => esc_attr( $url ),
				'order_id' => (int) $order_id,
				'phone' => preg_replace( '/[^0-9]/', '', $phone_number )
			)
		);
		
		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/subscribe/',
			$request_params
		);
		
		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}
		
		$response = json_decode( $response['body'] );
		
		$this->maybe_trigger_new_subscriber_added( (int) $order_id, preg_replace( '/[^0-9]/', '', $phone_number ) );
		
		return true;
	}
	
	public function unsubscribe_text_notification( $order_id, $phone_number ) {
		
		$this->maybe_trigger_subscriber_removed( (int) $order_id, preg_replace( '/[^0-9]/', '', $phone_number ) );
		
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
				'site_url' => esc_attr( $url ),
				'order_id' => (int) $order_id,
				'phone' => preg_replace( '/[^0-9]/', '', $phone_number )
			)
		);
		
		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/unsubscribe/',
			$request_params
		);
		
		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}
		
		$response = json_decode( $response['body'] );
		
		return true;
	}
	
	public function maybe_trigger_new_subscriber_added( $order_id, $phone_number ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'new-subscriber', $order_id, array(
			'phone_number' => $phone_number,
		) );
	}
	
	public function maybe_trigger_subscriber_removed( $order_id, $phone_number ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'removed-subscriber', $order_id, array(
			'phone_number' => $phone_number,
		) );
	}
	
	public function maybe_trigger_order_status_updated( $order_id, $orderstatus_id ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		global $wpdb;
		$order = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_order WHERE order_id = %d', $order_id ) );
		$order_date = date( get_option( 'date_format' ), strtotime( $order->order_date ) );
		$pickup_date = '';
		$date_timestamp = strtotime( $order->pickup_date );
		$selected_pickup_date_time = '';
		$restaurant_date = '';
		$restaurant_pickup_time = '';
		if ( $date_timestamp > 0 ) {
			$pickup_date = date( apply_filters( 'wp_easycart_pickup_date_placeholder_format', 'F d, Y' ), $date_timestamp );
			$end_time_timestamp = strtotime( $order->pickup_date . ' +1 hour' );
			$selected_pickup_date_time = date( get_option( 'time_format' ), $date_timestamp ) . ' - ' . date( get_option( 'time_format' ), $end_time_timestamp );
		}
		if ( $order->includes_restaurant_type && isset( $order->pickup_time ) && '' != $order->pickup_time ) {
			$restaurant_time = $order->pickup_time;
			$restaurant_time_timestamp = strtotime( $restaurant_time );
			if ( $restaurant_time_timestamp > 0 ) {
				$restaurant_date = date( apply_filters( 'wp_easycart_pickup_date_placeholder_format', 'F d, Y' ), $restaurant_time_timestamp );
				$pickup_time_minutes = (int) date( 'i', $pickup_time_timestamp );
				$pickup_time_rounded_minutes = round( $pickup_time_minutes / 5 ) * 5;
				$pickup_time_updated_timestamp = strtotime( date( 'Y-m-d H:', $pickup_time_timestamp ) . sprintf( '%02d:00', $pickup_time_rounded_minutes ) );
				$restaurant_pickup_time = date( 'H:i', $pickup_time_updated_timestamp );
			}
		}
		$this->trigger_cloud_message( 'order-status-update', $order_id, array(
			'order_date' => $order_date,
			'restaurant_date' => $restaurant_date,
			'restaurant_time' => $restaurant_pickup_time,
			'pickup_date' => $pickup_date,
			'pickup_time' => $selected_pickup_date_time,
			'grand_total' => $GLOBALS['currency']->get_currency_display( $order->grand_total ),
		) );
	}
	
	public function maybe_trigger_tracking_updated( $order_id, $use_expedited_shipping, $shipping_method, $shipping_carrier, $tracking_number ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'shipping-tracking-update', $order_id, array(
			'use_expedited_shipping' => $use_expedited_shipping,
			'shipping_method' => $shipping_method,
			'shipping_carrier' => $shipping_carrier,
			'tracking_number' => $tracking_number,
		) );
	}
	
	public function maybe_trigger_order_notes_updated( $order_id, $order_customer_notes ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'order-note', $order_id, array(
			'order_customer_notes' => $order_customer_notes,
		) );
	}
	
	public function maybe_trigger_order_billing_updated( $order_id, $billing_first_name, $billing_last_name, $billing_company_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_country, $billing_zip, $billing_phone ){
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'billing-address', $order_id, array(
			'billing_first_name' => $billing_first_name,
			'billing_last_name' => $billing_last_name,
			'billing_company_name' => $billing_company_name,
			'billing_address_line_1' => $billing_address_line_1,
			'billing_address_line_2' => $billing_address_line_2,
			'billing_city' => $billing_city,
			'billing_state' => $billing_state,
			'billing_country' => $billing_country,
			'billing_zip' => $billing_zip,
			'billing_phone' => $billing_phone,
		) );
	}
	
	public function maybe_trigger_order_shipping_updated( $order_id, $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_country, $shipping_zip, $shipping_phone ){
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'shipping-address', $order_id, array(
			'shipping_first_name' => $shipping_first_name,
			'shipping_last_name' => $shipping_last_name,
			'shipping_company_name' => $shipping_company_name,
			'shipping_address_line_1' => $shipping_address_line_1,
			'shipping_address_line_2' => $shipping_address_line_2,
			'shipping_city' => $shipping_city,
			'shipping_state' => $shipping_state,
			
			'shipping_country' => $shipping_country,
			'shipping_zip' => $shipping_zip,
			'shipping_phone' => $shipping_phone,
		) );
	}
	
	public function maybe_trigger_order_line_updated( $order_id, $orderdetail_id, $title, $model_number, $quantity, $unit_price, $total_price, $giftcard_id, $gift_card_email, $gift_card_from_name, $gift_card_to_name, $gift_card_message, $optionitem_name_1, $optionitem_name_2, $optionitem_name_3, $optionitem_name_4, $optionitem_name_5 ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		$this->trigger_cloud_message( 'line-items-updated', $order_id, array(
			'title' => $title,
			'model_number' => $model_number,
			'quantity' => $quantity,
			'unit_price' => $unit_price,
			'total_price' => $total_price,
			'giftcard_id' => $giftcard_id,
			'gift_card_email' => $gift_card_email,
			'gift_card_from_name' => $gift_card_from_name,
			'gift_card_to_name' => $gift_card_to_name,
			'gift_card_message' => $gift_card_message,
			'optionitem_name_1' => $optionitem_name_1,
			'optionitem_name_2' => $optionitem_name_2,
			'optionitem_name_3' => $optionitem_name_3,
			'optionitem_name_4' => $optionitem_name_4,
			'optionitem_name_5' => $optionitem_name_5
		) );
	}
	
	public function maybe_trigger_order_line_added( $order_id, $orderdetail_id ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		global $wpdb;
		$orderdetail_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_orderdetail WHERE orderdetail_id = %d', $orderdetail_id ) );
		
		$this->trigger_cloud_message( 'line-items-added', $order_id, array(
			'title' => $orderdetail_row->title,
			'model_number' => $orderdetail_row->model_number,
			'quantity' => $orderdetail_row->quantity,
			'unit_price' => $orderdetail_row->unit_price,
			'total_price' => $orderdetail_row->total_price,
			'giftcard_id' => $orderdetail_row->giftcard_id,
			'gift_card_email' => $orderdetail_row->gift_card_email,
			'gift_card_from_name' => $orderdetail_row->gift_card_from_name,
			'gift_card_to_name' => $orderdetail_row->gift_card_to_name,
			'gift_card_message' => $orderdetail_row->gift_card_message,
			'optionitem_name_1' => $orderdetail_row->optionitem_name_1,
			'optionitem_name_2' => $orderdetail_row->optionitem_name_2,
			'optionitem_name_3' => $orderdetail_row->optionitem_name_3,
			'optionitem_name_4' => $orderdetail_row->optionitem_name_4,
			'optionitem_name_5' => $orderdetail_row->optionitem_name_5
		) );
	}
	
	public function maybe_trigger_order_line_deleted( $order_id, $orderdetail_id ) {
		
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		global $wpdb;
		$orderdetail_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_orderdetail WHERE orderdetail_id = %d', $orderdetail_id ) );
		
		$this->trigger_cloud_message( 'line-items-deleted', $order_id, array(
			'title' => $title,
			'model_number' => $orderdetail_row->model_number,
			'quantity' => $orderdetail_row->quantity,
			'unit_price' => $orderdetail_row->unit_price,
			'total_price' => $orderdetail_row->total_price,
			'giftcard_id' => $orderdetail_row->giftcard_id,
			'gift_card_email' => $orderdetail_row->gift_card_email,
			'gift_card_from_name' => $orderdetail_row->gift_card_from_name,
			'gift_card_to_name' => $orderdetail_row->gift_card_to_name,
			'gift_card_message' => $orderdetail_row->gift_card_message,
			'optionitem_name_1' => $orderdetail_row->optionitem_name_1,
			'optionitem_name_2' => $orderdetail_row->optionitem_name_2,
			'optionitem_name_3' => $orderdetail_row->optionitem_name_3,
			'optionitem_name_4' => $orderdetail_row->optionitem_name_4,
			'optionitem_name_5' => $orderdetail_row->optionitem_name_5
		) );
	}
	
	public function maybe_add_advert_menu_item( ) {
		if( get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		if ( wp_easycart_admin_orders_pro()->check_for_cloud_license( ) ) {
			return;
		}
		
		if ( get_option( 'ec_option_disable_easycart_ad' ) ) {
			return;
		}
		
		echo '<h4 style="margin:20px auto 0; float:left; width:100%; text-align:center; font-size:18px; color:#FFF; font-weight:bold;">' . esc_attr__( 'Text Notifications', 'wp-easycart-pro' ) . '</h4>';
		echo '<a href="https://www.wpeasycart.com/cloud-services-customer-text-alert-messaging/" target="_blank" style="margin:10px auto; float:left; width:100%; height:autuo;"><img src="' . plugins_url( 'wp-easycart-pro/admin/images/order-shipped-text.jpg' ) . '" style="max-width:100%; height:auto;" /></a>';
		echo '<a href="https://www.wpeasycart.com/cloud-services-customer-text-alert-messaging/" target="_blank" style="margin:0px 5% 20px; float:left; width:90%; text-align:center; background:#ffffff; color:#577d2e; padding:12px 5px; display:block; border-radius:15px; font-size:18px; text-decoration:none;">' . esc_attr__( 'Learn More', 'wp-easycart-pro' ) . '</a>';
	}
	
	public function maybe_add_store_status_advert_bubble( ) {
		if( get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		if ( wp_easycart_admin_orders_pro()->check_for_cloud_license( ) ) {
			return;
		}
		
		if ( get_option( 'ec_option_disable_easycart_ad' ) ) {
			return;
		}
		
		echo '<div class="ec_admin_status_circle_container">
			<div style="float:left; width:120px; position:relative;">
				<img src="' . plugins_url( 'wp-easycart-pro/admin/images/order-shipped-text.jpg' ) . '" style="max-width:100%; height:auto;" />
			</div>
			<div class="ec_admin_status_circle_content">
				<h4>' . esc_attr__( 'Text Notifications', 'wp-easycart-pro' ) . '</h4>
				<div>' . esc_attr__( 'Send order notifications to customers using SMS text messages.', 'wp-easycart-pro' ) . '</div>
				<a href="https://www.wpeasycart.com/cloud-services-customer-text-alert-messaging/" target="_blank">' . esc_attr__( 'Learn More', 'wp-easycart-pro' ) . '</a>
			</div>
		</div>';
	}
	
	public function maybe_add_store_status_bubble( ){
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		if ( ! wp_easycart_admin_orders_pro()->check_for_cloud_license( ) ) {
			return;
		}
		
		$license_info = get_option( 'wp_easycart_license_info' );
		
		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return;
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
			)
		);
		
		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/stats/',
			$request_params
		);
		
		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return;
		}
		
		$response = json_decode( $response['body'] );
		
		if( isset( $response->error ) ) {
			echo '<div class="ec_admin_status_circle_container">';
			wp_easycart_admin( )->display_stat_circle( esc_attr__( 'EXPIRED', 'wp-easycart-pro' ), -1, __( 'Text Notifications', 'wp-easycart-pro' ), esc_attr__( 'Your cloud service has expired or was cancelled.', 'wp-easycart-pro' ), 'https://www.wpeasycart.com/my-shopping-cart/?ec_page=subscription_info&subscription=text-notification-service-150', esc_attr__( 'Renew Subscription', 'wp-easycart-pro' ) );
			echo '</div>';
			
		} else {
			$sent_percentage = $response->monthly_count / $response->max_per_month;
			$sent_percentage_display = ceil( $sent_percentage * 100 ) . '%';
			$reset_date_formatted = date( 'F d, Y', strtotime( '+1 month', strtotime( $response->month_start ) ) );

			echo '<div class="ec_admin_status_circle_container">';
			wp_easycart_admin( )->display_stat_circle( $sent_percentage_display, ( ( ( 1 - $sent_percentage ) > 0 ) ? ( 1 - $sent_percentage ) : -1 ), __( 'Text Notifications', 'wp-easycart-pro' ), sprintf( esc_attr__( 'You have sent %1$d/%2$d texts this month. Your limit is reset on %3$s', 'wp-easycart-pro' ), (int) $response->monthly_count, (int) $response->max_per_month, $reset_date_formatted ), 'https://www.wpeasycart.com/my-account/?ec_page=subscription_details&subscription_id=' . (int) $response->subscription_id, esc_attr__( 'Increase Your Quota', 'wp-easycart-pro' ) );
			echo '</div>';
		}
	}
	
	private function trigger_cloud_message( $trigger_type, $order_id, $order_meta = array() ) {
		global $wpdb;

		$order_row = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id, orderstatus_id FROM ec_order WHERE order_id = %d', $order_id ) );
		
		if ( ! $order_row ) {
			return false;
		}
		
		$license_info = get_option( 'wp_easycart_license_info' );
		
		if( ! is_array( $license_info ) || ! isset( $license_info['transaction_key'] ) ){ 
			return false;
		}
		$license_key = $license_info['transaction_key'];
		
		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);
		
		/* If Match - Trigger */
		$request_params = array(
			'body' => array(
				'license_key' => preg_replace( '/[^A-Z0-9]/', '', strtoupper( $license_key ) ),
				'site_url' => esc_attr( $url ),
				'order_id' => (int) $order_id,
				'order_status_id' => (int) $order_row->orderstatus_id,
				'message_meta' => json_encode( $order_meta ),
				'trigger_type' => $trigger_type
			)
		);
		
		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/trigger/',
			$request_params
		);
	}
	
	public function start_square_sync() {
		
		if ( ! wp_easycart_admin_license()->valid_license || ! wp_easycart_admin_license( )->active_license ) {
			return;
		}
		
		if ( ! class_exists( 'ec_square' ) ) {
			return;
		}
		
		$square = new ec_square();
		$has_more = true;
		$cursor = false;
		$update_after = false;
		if ( get_option( 'ec_option_square_last_sync' ) ) {
			$update_after = date( 'Y-m-d', get_option( 'ec_option_square_last_sync' ) ) . 'T' . date( 'H:i:s', get_option( 'ec_option_square_last_sync' ) ) . 'Z';
		}
		while ( $has_more ) {
			$response = $square->get_inventory_results( $cursor, $update_after );
			if ( isset( $response->counts ) ) {
				foreach( $response->counts as $object ){
					$this->update_square_inventory( $object );
				}
			}
			if ( isset( $response->cursor ) && $response->cursor ) {
				$cursor = $response->cursor;
				$has_more = true;
			} else {
				$cursor = false;
				$has_more = false;
			}
		}
		update_option( 'ec_option_square_last_sync', time() );
		wp_cache_flush( );
	}

	public function start_square_product_sync() {
		
		if ( ! wp_easycart_admin_license()->valid_license || ! wp_easycart_admin_license( )->active_license ) {
			return;
		}
		
		if ( ! class_exists( 'ec_square' ) ) {
			return;
		}
		
		$square = new ec_square( );
		if ( get_option( 'ec_option_square_last_cursor' ) ) {
			$response = $square->get_catalog( get_option( 'ec_option_square_last_cursor' ) );
		} else {
			$response = $square->get_catalog( );
		}

		$total_interations = 0;
		while( $response && $total_interations < 50 ){
			foreach( $response->objects as $object ){
				if( $object->type == "CATEGORY" ){
					$square->insert_category( $object );
				}else if( $object->type == "ITEM" ){
					$square->insert_product( $object, true, get_option( 'ec_option_square_auto_sync' ) );
				}
			}
			if( $response->cursor ){
				update_option( 'ec_option_square_last_cursor', $response->cursor );
				$response = $square->get_catalog( $response->cursor );
			}else{
				update_option( 'ec_option_square_last_cursor', 0 );
				$response = false;
			}
			$total_interations++;
		}
		wp_cache_flush( );
	}

	private function update_square_inventory( $object ) {
		global $wpdb;
		if ( 'ITEM_VARIATION' == $object->catalog_object_type && 'IN_STOCK' == $object->state ) {
			$found_optionitem = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE ec_optionitem.square_id = %s', $object->catalog_object_id ) );
			if ( $found_optionitem ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity, ec_optionitem SET ec_optionitemquantity.quantity = %d WHERE ec_optionitemquantity.optionitem_id_1 = ec_optionitem.optionitem_id AND ec_optionitem.square_id = %s', $object->quantity, $object->catalog_object_id ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.stock_quantity = ( SELECT SUM( ec_optionitemquantity.quantity ) FROM ec_optionitemquantity WHERE ec_optionitemquantity.product_id = ec_product.product_id ) WHERE ec_product.option_id_1 = %d', $found_optionitem->option_id ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE square_variation_id = %s', $object->quantity, $object->catalog_object_id ) );
			}
		} else if ( 'IN_STOCK' == $object->state ) {
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE square_id = %s', $object->quantity, $object->catalog_object_id ) );
		}
	}
	
	private function update_square_inventory_adjustments( $object ) {
		global $wpdb;
		if( 'PHYSICAL_COUNT' == $object->type ) {
			if ( 'ITEM_VARIATION' == $object->physical_count->catalog_object_type ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity, ec_optionitem SET ec_optionitemquantity.quantity = %d WHERE ec_optionitemquantity.optionitem_id_1 = ec_optionitem.optionitem_id AND ec_optionitem.square_id = %s', $object->physical_count->quantity, $object->physical_count->catalog_object_id ) );
				$found_optionitem = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE ec_optionitem.square_id = %s', $object->physical_count->catalog_object_id ) );
				if ( $found_optionitem ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.stock_quantity = ( SELECT SUM( ec_optionitemquantity.quantity ) FROM ec_optionitemquantity WHERE ec_optionitemquantity.product_id = ec_product.product_id ) WHERE ec_product.option_id_1 = %d', $found_optionitem->option_id ) );
				} else {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE square_variation_id = %s', $object->adjustment->quantity, $object->physical_count->catalog_object_id ) );
				}
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE square_id = %s', $object->physical_count->quantity, $object->physical_count->catalog_object_id ) );
			}
		} else {
			if ( 'SOLD' != $object->adjustment->to_state && 'IN_STOCK' != $object->adjustment->to_state ) {
				return;
			}
			if ( 'ITEM_VARIATION' == $object->adjustment->catalog_object_type ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_optionitemquantity, ec_optionitem SET ec_optionitemquantity.quantity = ec_optionitemquantity.quantity ' . ( ( 'SOLD' == $object->adjustment->to_state ) ? '-' : '+' ) . ' %d WHERE ec_optionitemquantity.optionitem_id_1 = ec_optionitem.optionitem_id AND ec_optionitem.square_id = %s', $object->adjustment->quantity, $object->adjustment->catalog_object_id ) );
				$found_optionitem = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE ec_optionitem.square_id = %s', $object->adjustment->catalog_object_id ) );
				if ( $found_optionitem ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.stock_quantity = ( SELECT SUM( ec_optionitemquantity.quantity ) FROM ec_optionitemquantity WHERE ec_optionitemquantity.product_id = ec_product.product_id ) WHERE ec_product.option_id_1 = %d', $found_optionitem->option_id ) );
				} else {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = %d WHERE square_variation_id = %s', $object->adjustment->quantity, $object->adjustment->catalog_object_id ) );
				}
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET stock_quantity = stock_quantity ' . ( ( 'SOLD' == $object->adjustment->to_state ) ? '-' : '+' ) . ' %d WHERE square_id = %s', $object->adjustment->quantity, $object->adjustment->catalog_object_id ) );
			}
		}
	}

}
endif; // End if class_exists check

function wp_easycart_admin_pro( ){
	return wp_easycart_admin_pro::instance( );
}
wp_easycart_admin_pro( );

add_action( 'wp_ajax_ec_ajax_subscribe_text_notification', 'ec_ajax_subscribe_text_notification' );
add_action( 'wp_ajax_nopriv_ec_ajax_subscribe_text_notification', 'ec_ajax_subscribe_text_notification' );
function ec_ajax_subscribe_text_notification( ){
	$response = wp_easycart_admin_pro()->subscribe_text_order_notifications();
	echo json_encode( array( 'response_code' => ( ( $response ) ? '1' : '0' ) ) );
	die( );
}

register_uninstall_hook( __FILE__, 'wp_easycart_admin_pro_uninstall' );
function wp_easycart_admin_pro_uninstall( ){
	delete_transient( 'ec_license_data' );
}

add_action( 'activated_plugin', 'wp_easycart_pro_activation_redirect' );
function wp_easycart_pro_activation_redirect( $plugin ) {
	do_action( 'wpeasycart_pro_activated' );
	if( $plugin == plugin_basename( __FILE__ ) && wp_easycart_admin_pro( )->is_wp_easycart_installed( ) && !wp_easycart_admin_pro( )->is_wp_easycart_v3( ) ) {
		exit( wp_redirect( admin_url( 'admin.php?page=wp-easycart-registration' ) ) );
	}
}
add_action( 'plugins_loaded', 'wp_easycart_pro_load_textdomain' );
function wp_easycart_pro_load_textdomain( ){
	load_plugin_textdomain( 'wp-easycart-pro', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}

add_action( 'wpeasycart_square_sync_inventory', 'wpeasycart_square_sync_inventory_start' );
function wpeasycart_square_sync_inventory_start() {
	wp_easycart_admin_pro()->start_square_sync();
}

add_action( 'wpeasycart_square_product_sync', 'wpeasycart_square_product_sync' );
function wpeasycart_square_product_sync() {
	wp_easycart_admin_pro()->start_square_product_sync();
}

if ( ! get_option( 'ec_option_enable_debugging_mode' ) ) {
	error_reporting( 0 );
}
