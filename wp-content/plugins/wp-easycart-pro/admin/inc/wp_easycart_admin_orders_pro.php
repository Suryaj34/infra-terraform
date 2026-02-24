<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_orders_pro' ) ) :

final class wp_easycart_admin_orders_pro{

	protected static $_instance = null;

	public static function instance( ) {

		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;

	}

	public function __construct( ){ 
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			add_filter( 'wp_easycart_admin_order_details_order_date_edit_action', array( $this, 'allow_order_date_edit' ), 1 );

			add_filter( 'wp_easycart_admin_order_details_view_as_customer_action', array( $this, 'allow_view_as_customer' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_add_new_line_action', array( $this, 'allow_add_line_item' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_edit_line_action', array( $this, 'allow_edit_line_item' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_delete_line_action', array( $this, 'allow_delete_line_item' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_totals_edit_action', array( $this, 'allow_totals_edit' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_refund_action', array( $this, 'allow_refund' ), 1 );
			add_filter( 'wp_easycart_admin_enable_refund_email_button', array( $this, 'allow_refund_email' ), 10, 1 );

			add_filter( 'wp_easycart_admin_order_details_shipping_edit_action', array( $this, 'allow_shipping_edit' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_order_edit_action', array( $this, 'allow_order_edit' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_billing_edit_action', array( $this, 'allow_billing_edit' ), 1 );
			add_filter( 'wp_easycart_admin_order_details_order_bottom_edit_action', array( $this, 'allow_order_bottom_edit' ), 1 );

			add_action( 'wp_easycart_order_details_order_date', array( $this, 'print_order_date_form' ) );
			add_action( 'wp_easycart_admin_order_details_line_item_end', array( $this, 'print_line_item_edit_fields' ), 10, 1 );
			add_action( 'wp_easycart_admin_order_details_items_end', array( $this, 'print_add_new_line_item' ) );
			add_action( 'wp_easycart_admin_order_details_totals_content_end', array( $this, 'print_totals_form' ) );
			add_action( 'wp_easycart_order_details_refund_panel', array( $this, 'print_refund_form' ) );

			remove_action( 'wp_easycart_admin_order_details_left_content_end', array( wp_easycart_admin_orders(), 'print_order_history' ) );
			add_action( 'wp_easycart_admin_order_details_left_content_end', array( $this, 'print_order_history' ) );
			add_action( 'wp_easycart_admin_order_details_shipping_content_end', array( $this, 'print_shipping_form' ) );
			add_action( 'wp_easycart_order_details_order_information', array( $this, 'print_order_info_form' ) );
			add_action( 'wp_easycart_admin_order_details_billing_content_end', array( $this, 'print_billing_form' ) );
			add_action( 'wp_easycart_order_details_order_bottom_information', array( $this, 'print_order_info_bottom_form' ) );

			add_action( 'wpeasycart_order_details_shipping_address_pre', array( $this, 'maybe_add_order_details_subscribe_box' ), 10, 1 );
			add_action( 'wp_easycart_order_details_order_bottom_information', array( $this, 'maybe_add_order_details_text_advert' ), 10, 1 );
			
			add_action( 'wp_easycart_admin_order_details_shipping_after_phone', array( $this, 'add_shipping_address_copy' ), 10, 1 );
			add_action( 'wp_easycart_admin_order_details_billing_after_phone', array( $this, 'add_billing_address_copy' ), 10, 1 );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_view_as_customer' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_send_refund_email' ) );

			add_filter( 'wp_easycart_admin_order_list_filters', array( $this, 'maybe_add_location_filter' ) );
			add_action( 'wp_easycart_additional_admin_options', array( $this, 'enable_refund_auto_email_option' ) );
		}
	}

	public function allow_order_date_edit( $action ){
		return 'ec_order_show_hide_edit_order_date';
	}

	public function allow_view_as_customer( $action ){
		return 'ec_order_view_as_customer';
	}

	public function allow_add_line_item( $action ){
		return 'ec_order_add_new_line';
	}

	public function allow_edit_line_item( $action ){
		return 'ec_order_edit_line_item';
	}

	public function allow_delete_line_item( $action ){
		return 'ec_order_delete_line_item';
	}

	public function allow_totals_edit( $action ){
		return 'ec_order_show_hide_edit_totals';
	}

	public function allow_refund( $action ){
		return 'ec_order_show_hide_edit_refund';
	}
	
	public function allow_refund_email( $enabled ) {
		return true;
	}

	public function allow_shipping_edit( $action ){
		return 'ec_order_show_hide_edit_shipping';
	}

	public function allow_order_edit( $action ){
		return 'ec_order_show_hide_edit_order_information';
	}

	public function allow_billing_edit( $action ){
		return 'ec_order_show_hide_edit_billing';
	}

	public function allow_order_bottom_edit( $action ){
		return 'ec_order_show_hide_edit_order_information_bottom';
	}

	public function print_order_date_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/order-date-form.php' );
	}

	public function print_line_item_edit_fields( $line_item ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/order-item.php' );
	}

	public function print_add_new_line_item( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/add-new-line-item.php' );
	}

	public function print_totals_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/order-totals-form.php' );
	}

	public function print_refund_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/refund-form.php' );
	}
	
	public function print_order_history( $order_id = false ){
		if( ! $order_id ) {
			$order_id = ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : 0;
		}
		global $wpdb;
		$order_text_log = ( get_option( 'ec_option_enable_cloud_messages' ) && $this->check_for_cloud_license( ) ) ? $this->get_text_log( $order_id ) : array();
		$order_history_list = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_order_log.*, NOW() AS now_datetime FROM ec_order_log WHERE order_id = %d ORDER BY order_log_timestamp DESC', $order_id ) );
		$order_history_list = array_merge( $order_text_log, $order_history_list );
		usort( $order_history_list, array( $this, 'compare_log_date' ) );
		$order_history = array();
		foreach ( $order_history_list as $order_history_item ) {
			$meta = array();
			if(  $order_history_item->order_log_key != 'order-text' ) {
				$meta_items = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_order_log_meta WHERE order_log_id = %d AND order_id = %d', $order_history_item->order_log_id, $order_id ) );
				foreach ( $meta_items as $meta_item ) {
					$meta[$meta_item->order_log_meta_key] = $meta_item->order_log_meta_value;
					if( $meta_item->order_log_meta_key == 'orderstatus_id' ) {
						$meta['order_status'] = $wpdb->get_var( $wpdb->prepare( 'SELECT order_status FROM ec_orderstatus WHERE status_id = %d', $meta_item->order_log_meta_value ) );
					}
				}
				$order_data = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_orderstatus.order_status, ec_order.billing_first_name, ec_order.billing_last_name, ec_order.order_gateway FROM ec_order, ec_orderstatus WHERE ec_order.order_id = %d AND ec_order.orderstatus_id = ec_orderstatus.status_id', $order_id ) );
				if( ! isset( $meta['order_status'] ) ) {
					$meta['order_status'] = $order_data->order_status;
				}
				if( ! isset( $meta['first_name'] ) ) {
					$meta['first_name'] = $order_data->billing_first_name;
				}
				if( ! isset( $meta['last_name'] ) ) {
					$meta['last_name'] = $order_data->billing_last_name;
				}
				if( ! isset( $meta['order_gateway'] ) ) {
					$meta['order_gateway'] = $order_data->order_gateway;
				}
			}
			$order_history[] = (object) array(
				'item' => $order_history_item,
				'meta' => $meta,
			);
		}
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/order-history.php' );
	}
	
	private function compare_log_date( $a, $b ) {
		$a_time = strtotime( $a->now_datetime ) - strtotime( $a->order_log_timestamp );
		$b_time = strtotime( $b->now_datetime ) - strtotime( $b->order_log_timestamp );
		if( $a_time == $b_time && isset( $a->order_log_id ) && isset( $b->order_log_id ) ) {
			return ( $a->order_log_id < $b->order_log_id ) ? 1 : -1;
			
		} else {
			return ( $a_time - $b_time );
		}
	}
	
	public function get_text_log( $order_id ) {
		global $wpdb;

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
				'order_id' => (int) $order_id
			)
		);
		
		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/log/',
			$request_params
		);
		
		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}
		
		$response = json_decode( $response['body'] );
		
		return $response;
	}

	public function print_order_history_dashicon( $order_history_item ) {
		if( $order_history_item->item->order_log_key == 'order-new' ) {
			echo 'dashicons-plus-alt';

		} else if( $order_history_item->item->order_log_key == 'order-date-update' ) { 
			echo 'dashicons-calendar-alt';

		} else if( $order_history_item->item->order_log_key == 'order-user-update' ) { 
			echo 'dashicons-admin-users';

		} else if( $order_history_item->item->order_log_key == 'order-info-update' ) {
			echo 'dashicons-edit-page';

		} else if( $order_history_item->item->order_log_key == 'order-shipping-update' ) {
			echo 'dashicons-edit-page';

		} else if( $order_history_item->item->order_log_key == 'order-shipping-method-update' ) {
			echo 'dashicons-edit-page';

		} else if( $order_history_item->item->order_log_key == 'order-billing-update' ) {
			echo 'dashicons-edit-page';

		} else if( $order_history_item->item->order_log_key == 'order-credit-card-update' ) {
			echo 'dashicons-embed-photo';

		} else if( $order_history_item->item->order_log_key == 'order-customer-notes-update' ) {
			echo 'dashicons-edit-page';

		} else if( $order_history_item->item->order_log_key == 'order-terms-update' ) {
			echo 'dashicons-edit-page';

		} else if( $order_history_item->item->order_log_key == 'order-status-update' ) {
			echo 'dashicons-bell';

		} else if( $order_history_item->item->order_log_key == 'order-totals-update' ) {
			echo 'dashicons-list-view';

		} else if( $order_history_item->item->order_log_key == 'order-refund-full' ) {
			echo 'dashicons-undo';

		} else if( $order_history_item->item->order_log_key == 'order-refund-partial' ) {
			echo 'dashicons-undo';

		} else if( $order_history_item->item->order_log_key == 'order-log-entry' ) {
			echo 'dashicons-money-alt';

		} else if( $order_history_item->item->order_log_key == 'order-shipping-email' ) {
			echo 'dashicons-email-alt';

		} else if( $order_history_item->item->order_log_key == 'order-quick-edit' ) {
			echo 'dashicons-edit-large';

		} else if( $order_history_item->item->order_log_key == 'order-text' ) {
			echo 'dashicons-smartphone';

		} else if( $order_history_item->item->order_log_key == 'order-line-added' ) {
			echo 'dashicons-plus-alt';

		} else if( $order_history_item->item->order_log_key == 'order-line-updated' ) {
			echo 'dashicons-saved';

		} else if( $order_history_item->item->order_log_key == 'order-line-deleted' ) {
			echo 'dashicons-dismiss';

		} else if( $order_history_item->item->order_log_key == 'order-stock-update' ) {
			echo 'dashicons-arrow-down-alt';

		}
	}
	
	public function get_gateway_label( $order_gateway ) {
		$gateways = array(
			'2checkout_thirdparty' => '2Checkout',
			'amazonpay' => 'AmazonPay',
			'cashfree' => 'CashFree',
			'dwolla_thirdparty' => 'Dwolla',
			'nets' => 'Nets Nexaxept',
			'payfast_thirdparty' => 'PayFast',
			'payfort' => 'Payfort',
			'paymentexpress_thirdparty' => 'Payment Express PxPay 2.0',
			'realex_thirdparty' => 'Realex',
			'redsys' => 'Redsys',
			'sagepay_paynow_za' => 'SagePay Pay Now South Africa',
			'skrill' => 'Skrill',
			'custom_thirdparty' => __( 'Custom Gateway', 'wp-easycart-pro' ),
			'authorize' => 'Authorize.net',
			'beanstream' => 'Bambora',
			'braintree' => 'Braintree S2S',
			'cardpointe' => 'Cardpointe',
			'chronopay' => 'Chronopay',
			'virtualmerchant' => 'Converge (Virtual Merchant)',
			'eway' => 'Eway',
			'firstdata' => 'First Data Payeezy (e4)',
			'goemerchant' => 'GoeMerchant',
			'intuit' => 'Intuit Payments',
			'migs' => 'MIGS', 
			'moneris_ca' => 'Moneris Canada',
			'moneris_us' => 'Moneris USA',
			'nmi' => 'Network Merchants (NMI)',
			'sagepay' => 'Opayo by Elavon (Formerly Sagepay)',
			'sagepayus' => 'Paya (Previously Sagepay US)',
			'payline' => 'Payline',
			'paymentexpress' => 'Payment Express PxPost',
			'paypal_pro' => 'PayPal PayFlow Pro',
			'paypal_payments_pro' => 'PayPal Payments Pro',
			'paypoint' => 'PayPoint', 
			'realex' => 'Realex',
			'securepay' => 'SecurePay',
			'stripe' => 'Stripe (v1)',
			'stripe_connect' => 'Stripe',
			'securenet' => 'WorldPay',
			'custom' => __( 'Custom Payment Gateway', 'wp-easycart-pro' ),
		);
		
		return ( isset( $gateways[$order_gateway] ) ) ? $gateways[$order_gateway] : false;
	}

	public function print_order_history_subtitle( $order_history_item ) {
		global $wpdb;
		if( $order_history_item->item->order_log_key == 'order-new' ) {
			echo sprintf( esc_attr__( 'Order placed by %1$s with the status %2$s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['first_name'] ) ) ? $order_history_item->meta['first_name'] : '' ) . ' ' . ( ( isset( $order_history_item->meta['last_name'] ) ) ? $order_history_item->meta['last_name'] : '' ), ( ( isset( $order_history_item->meta['order_status'] ) ) ? $order_history_item->meta['order_status'] : '' ) );
			if( isset( $order_history_item->meta['order_gateway'] ) && '' != $order_history_item->meta['order_gateway'] && $this->get_gateway_label( $order_history_item->meta['order_gateway'] ) ) {
				echo '<br />';
				echo sprintf( esc_attr__( 'Payment processed by %s', 'wp-easycart-pro' ), $this->get_gateway_label( $order_history_item->meta['order_gateway'] ) );
			}

		} else if( $order_history_item->item->order_log_key == 'order-date-update' ) {
			echo sprintf( esc_attr__( 'The order date was updated to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_date'] ) ) ? $order_history_item->meta['order_date'] : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-user-update' ) { 
			$user_row = $wpdb->get_row( $wpdb->prepare( 'SELECT first_name, last_name FROM ec_user WHERE user_id = %d', ( ( isset( $order_history_item->meta['user_id'] ) ) ? $order_history_item->meta['user_id'] : '' ) ) );
			echo sprintf( esc_attr__( 'The user was updated to %s', 'wp-easycart-pro' ), ( ( $user_row ) ? $user_row->first_name . ' ' . $user_row->last_name : esc_attr__( 'unknown user', 'wp-easycart-pro' ) ) );

		} else if( $order_history_item->item->order_log_key == 'order-info-update' ) {
			echo sprintf( esc_attr__( 'Order weight set to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_weight'] ) ) ? $order_history_item->meta['order_weight'] : '' ) );
			echo '<br />' . sprintf( esc_attr__( 'Gift card used set to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['giftcard_id'] ) ) ? $order_history_item->meta['giftcard_id'] : '' ) );
			echo '<br />' . sprintf( esc_attr__( 'Coupon code used set to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['promo_code'] ) ) ? $order_history_item->meta['promo_code'] : '' ) );
			echo '<br />' . sprintf( esc_attr__( 'Private order notes updated to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_notes'] ) ) ? $order_history_item->meta['order_notes'] : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-shipping-update' ) {
			echo ( ( isset( $order_history_item->meta['shipping_first_name'] ) && $order_history_item->meta['shipping_first_name'] != '' ) ? $order_history_item->meta['shipping_first_name'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_last_name'] ) && $order_history_item->meta['shipping_last_name'] != '' ) ? ' ' . $order_history_item->meta['shipping_last_name'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_company_name'] ) && $order_history_item->meta['shipping_company_name'] != '' ) ? '<br />' . $order_history_item->meta['shipping_company_name'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_address_line_1'] ) && $order_history_item->meta['shipping_address_line_1'] != '' ) ? '<br />' . $order_history_item->meta['shipping_address_line_1'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_address_line_2'] ) && $order_history_item->meta['shipping_address_line_2'] != '' ) ? '<br />' . $order_history_item->meta['shipping_address_line_2'] : '' );
			echo '<br />' . ( ( isset( $order_history_item->meta['shipping_city'] ) && $order_history_item->meta['shipping_city'] != '' ) ? $order_history_item->meta['shipping_city'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_state'] ) && $order_history_item->meta['shipping_state'] != '' ) ? ' ' . $order_history_item->meta['shipping_state'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_zip'] ) && $order_history_item->meta['shipping_zip'] != '' ) ? ' ' . $order_history_item->meta['shipping_zip'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_country'] ) && $order_history_item->meta['shipping_country'] != '' ) ? '<br />' . $order_history_item->meta['shipping_country'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_phone'] ) && $order_history_item->meta['shipping_phone'] != '' ) ? '<br />' . $order_history_item->meta['shipping_phone'] : '' );

		} else if( $order_history_item->item->order_log_key == 'order-shipping-method-update' ) {
			echo ( ( isset( $order_history_item->meta['use_expedited_shipping'] ) && $order_history_item->meta['use_expedited_shipping'] ) ? __( 'Expedited shipping was selected', 'wp-easycart-pro' ) : __( 'Standard shipping was selected', 'wp-easycart-pro' ) );
			echo ( ( isset( $order_history_item->meta['shipping_method'] ) && $order_history_item->meta['shipping_method'] != '' ) ? '<br />' . esc_attr__( 'Shipping Method', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['shipping_method'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_carrier'] ) && $order_history_item->meta['shipping_carrier'] != '' ) ? '<br />' . esc_attr__( 'Shipping Carrier', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['shipping_carrier'] : '' );
			echo ( ( isset( $order_history_item->meta['tracking_number'] ) && $order_history_item->meta['tracking_number'] != '' ) ? '<br />' . esc_attr__( 'Tracking Number', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['tracking_number'] : '' );

		} else if( $order_history_item->item->order_log_key == 'order-billing-update' ) {
			echo ( ( isset( $order_history_item->meta['billing_first_name'] ) && $order_history_item->meta['billing_first_name'] != '' ) ? $order_history_item->meta['billing_first_name'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_last_name'] ) && $order_history_item->meta['billing_last_name'] != '' ) ? ' ' . $order_history_item->meta['billing_last_name'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_company_name'] ) && $order_history_item->meta['billing_company_name'] != '' ) ? '<br />' . $order_history_item->meta['billing_company_name'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_address_line_1'] ) && $order_history_item->meta['billing_address_line_1'] != '' ) ? '<br />' . $order_history_item->meta['billing_address_line_1'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_address_line_2'] ) && $order_history_item->meta['billing_address_line_2'] != '' ) ? '<br />' . $order_history_item->meta['billing_address_line_2'] : '' );
			echo '<br />' . ( ( isset( $order_history_item->meta['billing_city'] ) && $order_history_item->meta['billing_city'] != '' ) ? $order_history_item->meta['billing_city'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_state'] ) && $order_history_item->meta['billing_state'] != '' ) ? ' ' . $order_history_item->meta['billing_state'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_zip'] ) && $order_history_item->meta['billing_zip'] != '' ) ? ' ' . $order_history_item->meta['billing_zip'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_country'] ) && $order_history_item->meta['billing_country'] != '' ) ? '<br />' . $order_history_item->meta['billing_country'] : '' );
			echo ( ( isset( $order_history_item->meta['billing_phone'] ) && $order_history_item->meta['billing_phone'] != '' ) ? '<br />' . $order_history_item->meta['billing_phone'] : '' );

		} else if( $order_history_item->item->order_log_key == 'order-credit-card-update' ) {
			echo ( ( isset( $order_history_item->meta['user_email'] ) && $order_history_item->meta['user_email'] != '' ) ? sprintf( esc_attr__( 'User email updated to %s', 'wp-easycart-pro' ), $order_history_item->meta['user_email'] ) : '' );
			echo ( ( isset( $order_history_item->meta['email_other'] ) && $order_history_item->meta['email_other'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Additional email updated to %s', 'wp-easycart-pro' ), $order_history_item->meta['email_other'] ) : '' );
			echo ( ( isset( $order_history_item->meta['card_holder_name'] ) && $order_history_item->meta['card_holder_name'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Card holder name updated to %s', 'wp-easycart-pro' ), $order_history_item->meta['card_holder_name'] ) : '' );
			echo '<br />' . sprintf( esc_attr__( 'Credit card updated to **** **** **** %s %s/%s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['creditcard_digits'] ) && $order_history_item->meta['creditcard_digits'] != '' ) ? $order_history_item->meta['creditcard_digits'] : '' ), ( ( isset( $order_history_item->meta['cc_exp_month'] ) ) ? $order_history_item->meta['cc_exp_month'] : '' ), ( ( isset( $order_history_item->meta['cc_exp_year'] ) ) ? $order_history_item->meta['cc_exp_year'] : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-customer-notes-update' ) {
			echo sprintf( esc_attr__( 'The customer notes updated: %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_customer_notes'] ) ) ? $order_history_item->meta['order_customer_notes'] : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-terms-update' ) {
			echo sprintf( esc_attr__( 'The IP address was updated to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_ip_address'] ) ) ? $order_history_item->meta['order_ip_address'] : '' ) );
			echo '<br />' . ( ( isset( $order_history_item->meta['agreed_to_terms'] ) && $order_history_item->meta['agreed_to_terms'] ) ? __( 'The agreed to terms value is now YES', 'wp-easycart-pro' ) : __( 'The agreed to terms value is now NO', 'wp-easycart-pro' ) );

		} else if( $order_history_item->item->order_log_key == 'order-status-update' ) {
			echo sprintf( esc_attr__( 'The order status changed to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_status'] ) ) ? $order_history_item->meta['order_status'] : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-totals-update' ) {
			echo ( ( isset( $order_history_item->meta['sub_total'] ) && $order_history_item->meta['sub_total'] != '' && ( (float) $order_history_item->meta['sub_total'] > 0 || (float) $order_history_item->meta['sub_total'] < 0 ) ) ? esc_attr__( 'Sub Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['sub_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['vat_total'] ) && $order_history_item->meta['vat_total'] != '' && ( (float) $order_history_item->meta['vat_total'] > 0 || (float) $order_history_item->meta['vat_total'] < 0 ) ) ? '<br />' . esc_attr__( 'VAT Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['vat_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['vat_rate'] ) && $order_history_item->meta['vat_rate'] != '' && ( (float) $order_history_item->meta['vat_rate'] > 0 || (float) $order_history_item->meta['vat_rate'] < 0 ) ) ? '<br />' . esc_attr__( 'VAT Rate', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['vat_rate'] . '%' : '' );
			echo ( ( isset( $order_history_item->meta['vat_registration_number'] ) && $order_history_item->meta['vat_registration_number'] != '' ) ? '<br />' . esc_attr__( 'VAT Registration #', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['vat_registration_number'] : '' );
			echo ( ( isset( $order_history_item->meta['tax_total'] ) && $order_history_item->meta['tax_total'] != '' && ( (float) $order_history_item->meta['tax_total'] > 0 || (float) $order_history_item->meta['tax_total'] < 0 ) ) ? '<br />' . esc_attr__( 'TAX Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['tax_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['gst_total'] ) && $order_history_item->meta['gst_total'] != '' && ( (float) $order_history_item->meta['gst_total'] > 0 || (float) $order_history_item->meta['gst_total'] < 0 ) ) ? '<br />' . esc_attr__( 'GST Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['gst_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['gst_rate'] ) && $order_history_item->meta['gst_rate'] != '' && ( (float) $order_history_item->meta['gst_rate'] > 0 || (float) $order_history_item->meta['gst_rate'] < 0 ) ) ? '<br />' . esc_attr__( 'GST Rate', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['gst_rate'] . '%' : '' );
			echo ( ( isset( $order_history_item->meta['hst_total'] ) && $order_history_item->meta['hst_total'] != '' && ( (float) $order_history_item->meta['hst_total'] > 0 || (float) $order_history_item->meta['hst_total'] < 0 ) ) ? '<br />' . esc_attr__( 'HST Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['hst_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['hst_rate'] ) && $order_history_item->meta['hst_rate'] != '' && ( (float) $order_history_item->meta['hst_rate'] > 0 || (float) $order_history_item->meta['hst_rate'] < 0 ) ) ? '<br />' . esc_attr__( 'HST Rate', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['hst_rate'] . '%' : '' );
			echo ( ( isset( $order_history_item->meta['pst_total'] ) && $order_history_item->meta['pst_total'] != '' && ( (float) $order_history_item->meta['pst_total'] > 0 || (float) $order_history_item->meta['pst_total'] < 0 ) ) ? '<br />' . esc_attr__( 'PST Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['pst_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['pst_rate'] ) && $order_history_item->meta['pst_rate'] != '' && ( (float) $order_history_item->meta['pst_rate'] > 0 || (float) $order_history_item->meta['pst_rate'] < 0 ) ) ? '<br />' . esc_attr__( 'PST Rate', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['pst_rate'] . '%' : '' );
			echo ( ( isset( $order_history_item->meta['shipping_total'] ) && $order_history_item->meta['shipping_total'] != '' && ( (float) $order_history_item->meta['shipping_total'] > 0 || (float) $order_history_item->meta['shipping_total'] < 0 ) ) ? '<br />' . esc_attr__( 'Shipping Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['shipping_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['discount_total'] ) && $order_history_item->meta['discount_total'] != '' && ( (float) $order_history_item->meta['discount_total'] > 0 || (float) $order_history_item->meta['discount_total'] < 0 ) ) ? '<br />' . esc_attr__( 'Discount Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['discount_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['grand_total'] ) && $order_history_item->meta['grand_total'] != '' && ( (float) $order_history_item->meta['grand_total'] > 0 || (float) $order_history_item->meta['grand_total'] < 0 ) ) ? '<br />' . esc_attr__( 'Grand Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['grand_total'] ) : '' );
			echo ( ( isset( $order_history_item->meta['refund_total'] ) && $order_history_item->meta['refund_total'] != '' && ( (float) $order_history_item->meta['refund_total'] > 0 || (float) $order_history_item->meta['refund_total'] < 0 ) ) ? '<br />' . esc_attr__( 'Refund Total', 'wp-easycart-pro' ) . ': ' . $GLOBALS['currency']->get_currency_display( $order_history_item->meta['refund_total'] ) : '' );

		} else if( $order_history_item->item->order_log_key == 'order-refund-full' ) {
			echo sprintf( esc_attr__( 'The order was fully refunded (-%s)', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['refunded_amount'] ) ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['refunded_amount'] ) : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-refund-partial' ) {
			echo sprintf( esc_attr__( 'The order was partially refunded (-%s)', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['refunded_amount'] ) ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['refunded_amount'] ) : '' ) );

		} else if( $order_history_item->item->order_log_key == 'order-log-entry' ) {
			echo sprintf( esc_attr__( 'There is a log entry available, %1$sClick here to view%2$s.', 'wp-easycart-pro' ), '<a href="admin.php?page=wp-easycart-settings&subpage=logs&response_id=' . $order_history_item->meta['response_id'] . '&ec_admin_form_action=edit" target="_blank">', '</a>' );

		} else if( $order_history_item->item->order_log_key == 'order-shipping-email' ) {
			echo sprintf( esc_attr__( 'The order shipped email was sent to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['email'] ) ) ? $order_history_item->meta['email'] : '' ) );
			if ( isset( $order_history_item->meta['email_other'] ) && '' != $order_history_item->meta['email_other'] ) {
				echo '<br />' . sprintf( esc_attr__( 'The order shipped email was sent to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['email_other'] ) ) ? $order_history_item->meta['email_other'] : '' ) );
			}

		} else if( $order_history_item->item->order_log_key == 'order-quick-edit' ) {
			echo sprintf( esc_attr__( 'The order status changed to %s', 'wp-easycart-pro' ), ( ( isset( $order_history_item->meta['order_status'] ) ) ? $order_history_item->meta['order_status'] : '' ) );
			echo '<br />' . ( ( isset( $order_history_item->meta['use_expedited_shipping'] ) && $order_history_item->meta['use_expedited_shipping'] ) ? __( 'Expedited shipping was selected', 'wp-easycart-pro' ) : __( 'Standard shipping was selected', 'wp-easycart-pro' ) );
			echo ( ( isset( $order_history_item->meta['shipping_method'] ) && $order_history_item->meta['shipping_method'] != '' ) ? '<br />' . esc_attr__( 'Shipping Method', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['shipping_method'] : '' );
			echo ( ( isset( $order_history_item->meta['shipping_carrier'] ) && $order_history_item->meta['shipping_carrier'] != '' ) ? '<br />' . esc_attr__( 'Shipping Carrier', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['shipping_carrier'] : '' );
			echo ( ( isset( $order_history_item->meta['tracking_number'] ) && $order_history_item->meta['tracking_number'] != '' ) ? '<br />' . esc_attr__( 'Tracking Number', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['tracking_number'] : '' );

		} else if( $order_history_item->item->order_log_key == 'order-text' ) {

			if ( isset( $order_history_item->item->send_result ) && $order_history_item->item->send_result == 'accepted' ) {
				esc_attr_e( 'The message was accepted by the carrier and should be delivered when possible.', 'wp-easycart-pro' );

			} else if ( isset( $order_history_item->item->send_result ) && $order_history_item->item->send_result == 'delivered' ) {
				echo sprintf( esc_attr__( 'The message was delivered successfully to %s.', 'wp-easycart-pro' ), $order_history_item->item->phone_number );

			} else if ( isset( $order_history_item->item->send_result ) && 'failed-' == substr( $order_history_item->item->send_result, 0, 7 ) ) {
				$status_explode = explode( '-', $order_history_item->item->send_result );
				$error_code = false;
				if ( count( $status_explode ) == 2 ) {
					$error_code = $status_explode[1];
				}
				if ( '2' == $error_code || '7' == $error_code || '8' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the device was temorarily unavilable.', 'wp-easycart-pro' );
				} else if ( '3' == $error_code || '11' == $error_code || '12' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the number is no longer active.', 'wp-easycart-pro' );
				} else if ( '4' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the destination number has blocked the sending number.', 'wp-easycart-pro' );
				} else if ( '6' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the network carrier blocked due to suspected spam.', 'wp-easycart-pro' );
				} else if ( '9' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the user has blocked all messages from these types of services.', 'wp-easycart-pro' );
				} else if ( '10' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the message had content that is considered not allowed.', 'wp-easycart-pro' );
				} else if ( '13' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the device has an age restriction.', 'wp-easycart-pro' );
				} else if ( '14' == $error_code ) {
					esc_attr_e( 'Message was not delivered because SMS is not available on this device.', 'wp-easycart-pro' );
				} else if ( '15' == $error_code ) {
					esc_attr_e( 'Message was not delivered because the device has a prepaid plan with insufficient funds.', 'wp-easycart-pro' );
				} else {
					esc_attr_e( 'There was an unknown error in delivering the message.', 'wp-easycart-pro' );
				}

			} else {
				echo ( ( isset( $order_history_item->item->message ) ) ? $order_history_item->item->message : esc_attr__( 'No message found for this log item', 'wp-easycart-pro' ) );
				echo '<br />' . sprintf( esc_attr__( 'was sent by text to %s.', 'wp-easycart-pro' ), ( ( isset( $order_history_item->item->phone_number ) ) ? $order_history_item->item->phone_number : esc_attr__( 'no phone number found', 'wp-easycart-pro' ) ) );
				echo ( ( isset( $order_history_item->item->error_message ) && $order_history_item->item->error_message == 'MAX SEND EXCEEDED' ) ? '<br /><span style="color:red; font-weight:bold;">Message not sent! You have exceeded your maximum sends for the month. You may visit your store status page to increase your monthly quota.</span>' : '' );
			}

		} else if( $order_history_item->item->order_log_key == 'order-line-added' ) {
			echo ( ( isset( $order_history_item->meta['title'] ) && $order_history_item->meta['title'] != '' ) ? '<strong>' . $order_history_item->meta['title'] . '</strong>' : '' );
			echo ( ( isset( $order_history_item->meta['model_number'] ) && $order_history_item->meta['model_number'] != '' ) ? '<br />' . $order_history_item->meta['model_number'] : '' );
			echo '<br />' . ( ( isset( $order_history_item->meta['unit_price'] ) && $order_history_item->meta['unit_price'] != '' ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['unit_price'] ) : '' ) . ' x ' . ( ( isset( $order_history_item->meta['quantity'] ) && $order_history_item->meta['quantity'] != '' ) ? $order_history_item->meta['quantity'] : '' ) . ' = ' . ( ( isset( $order_history_item->meta['total_price'] ) && $order_history_item->meta['total_price'] != '' ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['total_price'] ) : '' );
			
		} else if( $order_history_item->item->order_log_key == 'order-line-updated' ) {
			echo ( ( isset( $order_history_item->meta['title'] ) && $order_history_item->meta['title'] != '' ) ? '<strong>' . $order_history_item->meta['title'] . '</strong>' : '' );
			echo ( ( isset( $order_history_item->meta['model_number'] ) && $order_history_item->meta['model_number'] != '' ) ? '<br />' . $order_history_item->meta['model_number'] : '' );
			echo '<br />' . ( ( isset( $order_history_item->meta['unit_price'] ) && $order_history_item->meta['unit_price'] != '' ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['unit_price'] ) : '' ) . ' x ' . ( ( isset( $order_history_item->meta['quantity'] ) && $order_history_item->meta['quantity'] != '' ) ? $order_history_item->meta['quantity'] : '' ) . ' = ' . ( ( isset( $order_history_item->meta['total_price'] ) && $order_history_item->meta['total_price'] != '' ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['total_price'] ) : '' );
			echo ( ( isset( $order_history_item->meta['giftcard_id'] ) && $order_history_item->meta['giftcard_id'] != '' ) ? '<br />' . esc_attr__( 'Gift Card ID', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['giftcard_id'] : '' );
			echo ( ( isset( $order_history_item->meta['gift_card_email'] ) && $order_history_item->meta['gift_card_email'] != '' ) ? '<br />' . esc_attr__( 'Gift Card Email', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['gift_card_email'] : '' );
			echo ( ( isset( $order_history_item->meta['gift_card_from_name'] ) && $order_history_item->meta['gift_card_from_name'] != '' ) ? '<br />' . esc_attr__( 'Gift Card From', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['gift_card_from_name'] : '' );
			echo ( ( isset( $order_history_item->meta['gift_card_to_name'] ) && $order_history_item->meta['gift_card_to_name'] != '' ) ? '<br />' . esc_attr__( 'Gift Card To', 'wp-easycart-pro' ) . ': ' . $order_history_item->meta['gift_card_to_name'] : '' );
			echo ( ( isset( $order_history_item->meta['optionitem_name_1'] ) && $order_history_item->meta['optionitem_name_1'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Option Item %d', 'wp-easycart-pro' ), 1 ) . ': ' . $order_history_item->meta['optionitem_name_1'] : '' );
			echo ( ( isset( $order_history_item->meta['optionitem_name_2'] ) && $order_history_item->meta['optionitem_name_2'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Option Item %d', 'wp-easycart-pro' ), 2 ) . ': ' . $order_history_item->meta['optionitem_name_2'] : '' );
			echo ( ( isset( $order_history_item->meta['optionitem_name_3'] ) && $order_history_item->meta['optionitem_name_3'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Option Item %d', 'wp-easycart-pro' ), 3 ) . ': ' . $order_history_item->meta['optionitem_name_3'] : '' );
			echo ( ( isset( $order_history_item->meta['optionitem_name_4'] ) && $order_history_item->meta['optionitem_name_4'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Option Item %d', 'wp-easycart-pro' ), 4 ) . ': ' . $order_history_item->meta['optionitem_name_4'] : '' );
			echo ( ( isset( $order_history_item->meta['optionitem_name_5'] ) && $order_history_item->meta['optionitem_name_5'] != '' ) ? '<br />' . sprintf( esc_attr__( 'Option Item %d', 'wp-easycart-pro' ), 5 ) . ': ' . $order_history_item->meta['optionitem_name_5'] : '' );

		} else if( $order_history_item->item->order_log_key == 'order-line-deleted' ) {
			echo ( ( isset( $order_history_item->meta['title'] ) && $order_history_item->meta['title'] != '' ) ? '<strong>' . $order_history_item->meta['title'] . '</strong>' : '' );
			echo ( ( isset( $order_history_item->meta['model_number'] ) && $order_history_item->meta['model_number'] != '' ) ? '<br />' . $order_history_item->meta['model_number'] : '' );
			echo '<br />' . ( ( isset( $order_history_item->meta['unit_price'] ) && $order_history_item->meta['unit_price'] != '' ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['unit_price'] ) : '' ) . ' x ' . ( ( isset( $order_history_item->meta['quantity'] ) && $order_history_item->meta['quantity'] != '' ) ? $order_history_item->meta['quantity'] : '' ) . ' = ' . ( ( isset( $order_history_item->meta['total_price'] ) && $order_history_item->meta['total_price'] != '' ) ? $GLOBALS['currency']->get_currency_display( $order_history_item->meta['total_price'] ) : '' );

		} else if( $order_history_item->item->order_log_key == 'order-stock-update' ) {
			if ( isset( $order_history_item->meta['product_id'] ) && '' != $order_history_item->meta['product_id'] ) {
				$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $order_history_item->meta['product_id'] ) );
				$quantity = ( ( isset( $order_history_item->meta['quantity'] ) && $order_history_item->meta['quantity'] != '' ) ? $order_history_item->meta['quantity'] : '-1' );
				if ( $product && is_object( $product ) ) {
					echo esc_attr( $product->title . ' (' . $quantity . ')' );
				}
			}
		}
	}

	public function print_order_history_title( $order_history_item ) {
		if( $order_history_item->item->order_log_key == 'order-new' ) {
			echo sprintf( esc_attr__( 'Order %d was created!', 'wp-easycart-pro' ), (int) $order_history_item->item->order_id );

		} else if( $order_history_item->item->order_log_key == 'order-date-update' ) { 
			esc_attr_e( 'Order date was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-user-update' ) { 
			esc_attr_e( 'Order connected to a user', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-info-update' ) {
			esc_attr_e( 'Some order info was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-shipping-update' ) {
			esc_attr_e( 'Order shipping address was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-shipping-method-update' ) {
			esc_attr_e( 'Order shiping method and tracking info was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-billing-update' ) {
			esc_attr_e( 'Order billing address was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-credit-card-update' ) {
			esc_attr_e( 'Order credit card info was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-customer-notes-update' ) {
			esc_attr_e( 'Order customer notes was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-terms-update' ) {
			esc_attr_e( 'Order terms and IP address info was updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-status-update' ) {
			$order_status = ( isset( $order_history_item->meta['order_status'] ) ) ? $order_history_item->meta['order_status'] : __( 'Unknown Status', 'wp-easycart-pro' );
			echo sprintf( esc_attr__( 'Order status was updated to %s', 'wp-easycart-pro' ), $order_status );

		} else if( $order_history_item->item->order_log_key == 'order-totals-update' ) {
			esc_attr_e( 'Order totals were updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-refund-full' ) {
			esc_attr_e( 'Order was fully refunded', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-refund-partial' ) {
			$refunded_amount = ( isset( $order_history_item->meta['refunded_amount'] ) ) ? $order_history_item->meta['refunded_amount'] : false;
			if ( $refunded_amount ) {
				echo sprintf( esc_attr__( 'Partial refund of %s was issued', 'wp-easycart-pro' ), $refunded_amount );
			} else {
				esc_attr_e( 'There was a partial refund issued', 'wp-easycart-pro' );
			}

		} else if( $order_history_item->item->order_log_key == 'order-log-entry' ) {
			$log_response_type = ( isset( $order_history_item->meta['log_type'] ) ) ? $order_history_item->meta['log_type'] : false;
			if ( $log_response_type ) {
				echo sprintf( esc_attr__( 'Payment or other info logged for %s', 'wp-easycart-pro' ), ucwords( $log_response_type ) );
			} else {
				esc_attr_e( 'Order log item added', 'wp-easycart-pro' );
			}

		} else if( $order_history_item->item->order_log_key == 'order-shipping-email' ) {
			esc_attr_e( 'The order shipped email was sent', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-quick-edit' ) {
			esc_attr_e( 'Order edited via the quick edit method', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-text' ) {

			if ( isset( $order_history_item->item->send_result ) && $order_history_item->item->send_result == 'accepted' ) {
				esc_attr_e( 'Text notification accepted', 'wp-easycart-pro' );

			} else if ( isset( $order_history_item->item->send_result ) && $order_history_item->item->send_result == 'delivered' ) {
				esc_attr_e( 'Text notification delivered', 'wp-easycart-pro' );

			} else if ( isset( $order_history_item->item->send_result ) && 'failed-' == substr( $order_history_item->item->send_result, 0, 7 ) ) {
				esc_attr_e( 'Text notification failed', 'wp-easycart-pro' );

			} else {
				esc_attr_e( 'Text notification sent', 'wp-easycart-pro' );

			}

		} else if( $order_history_item->item->order_log_key == 'order-line-added' ) {
			esc_attr_e( 'Line item added to order', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-line-updated' ) {
			esc_attr_e( 'Line item updated', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-line-deleted' ) {
			esc_attr_e( 'Line item removed from order', 'wp-easycart-pro' );

		} else if( $order_history_item->item->order_log_key == 'order-stock-update' ) {
			esc_attr_e( 'Stock removed from inventory', 'wp-easycart-pro' );

		}
	}

	public function get_order_history_date_diff( $order_history_item ) {
		$current_time = strtotime( $order_history_item->item->now_datetime );
		$log_time = strtotime( $order_history_item->item->order_log_timestamp );

		$time_diff = $current_time - $log_time;

		if( $time_diff < 60 ) {
			esc_attr_e( 'Less Than a Minute Ago', 'wp-easycart-pro' );
		} else if( $time_diff < 60*2 ) {
			esc_attr_e( 'A Minute Ago', 'wp-easycart-pro' );
		} else if( $time_diff < 60*60 ) {
			echo sprintf( esc_attr__( '%d Minutes Ago', 'wp-easycart-pro' ), ceil( $time_diff / 60 ) );
		} else if( $time_diff < 60*60*24 ) {
			echo sprintf( esc_attr__( '%d Hours Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 ) ) );
		} else if( $time_diff < 60*60*24*31 ) {
			echo sprintf( esc_attr__( '%d Days Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 * 24 ) ) );
		} else if( $time_diff < 60*60*24*7*26 ) {
			echo sprintf( esc_attr__( '%d Weeks Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 * 24 * 7 ) ) );
		} else if( $time_diff < 60*60*24*7*52 ) {
			echo sprintf( esc_attr__( '%d Months Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 * 24 * 31 ) ) );
		}else {
			esc_attr_e( 'Over a Year Ago', 'wp-easycart-pro' );
		}
	}

	public function print_shipping_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/shipping-details-form.php' );
	}

	public function print_order_info_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/order-info-form.php' );
	}

	public function print_billing_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/billing-details-form.php' );
	}

	public function print_order_info_bottom_form( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/orders/order-info-bottom-form.php' );
	}

	public function process_view_as_customer( ){
		if( $_GET['ec_admin_form_action'] == 'view-as-customer' ){
			global $wpdb;
			$order_id = (int) $_GET['order_id'];
			$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE order_id = %d", $order_id ) );

			/* Get the account page */
			$account_page_id = get_option('ec_option_accountpage');
			if( function_exists( 'icl_object_id' ) ){
				$account_page_id = icl_object_id( $account_page_id, 'page', true, ICL_LANGUAGE_CODE );
			}
			$account_page = get_permalink( $account_page_id );
			if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
				$https_class = new WordPressHTTPS( );
				$account_page = $https_class->makeUrlHttps( $account_page );
			}else if( get_option( 'ec_option_load_ssl' ) ){
				$account_page = str_replace( 'http://', 'https://', $account_page );
			}

			/* If user id attached, login */
			if( $order->user_id ){

				/* Login the user */
				wpeasycart_session( )->handle_session( );

				$user_id = $order->user_id;
				$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_user WHERE user_id = %d", $user_id ) );
				$GLOBALS['ec_cart_data']->cart_data->user_id = $user->user_id;
				$GLOBALS['ec_cart_data']->cart_data->email = $user->email;
				$GLOBALS['ec_cart_data']->cart_data->username = $user->first_name . " " . $user->last_name;
				$GLOBALS['ec_cart_data']->cart_data->first_name = $user->first_name;
				$GLOBALS['ec_cart_data']->cart_data->last_name = $user->last_name;
				$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
				$GLOBALS['ec_cart_data']->cart_data->guest_key = "";
				$GLOBALS['ec_cart_data']->save_session_to_db( );

				wp_cache_flush( );
				do_action( 'wpeasycart_login_success', $user->email );

				wp_redirect( $account_page . '?ec_page=order_details&order_id=' . $order_id );

			}else{
				wp_redirect( $account_page . '?ec_page=order_details&order_id=' . $order_id . '&ec_guest_key=' . $order->guest_key );

			}
		}
	}

	public function process_send_refund_email() {
		if( 'send-refund-email' == $_GET['ec_admin_form_action'] ) {
			$order_id = (int) $_GET['order_id'];
			$this->send_refund_email( $order_id );
			wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', array( 'success' => 'refund-email-sent', 'order_id' => (int) $_GET['order_id'], 'ec_admin_form_action' => 'edit' ) );
		}
	}

	public function maybe_add_location_filter( $filters ) {
		if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
			global $wpdb;
			$locations = $wpdb->get_results( 'SELECT ec_location.location_id AS value, ec_location.location_label AS label FROM ec_location ORDER BY ec_location.location_label ASC' );
			if ( is_array( $locations ) && count( $locations ) > 0 ) {
				$filters[] = array(
					'data'		=> $locations,
					'label'		=> __( 'All Locations', 'wp-easycart-pro' ),
					'where'		=> 'ec_order.location_id = %d'
				);
			}
		}
		return $filters;
	}

	public function enable_refund_auto_email_option( $enabled ) {
		wp_easycart_admin( )->load_toggle_group( 'ec_option_auto_send_refund_email', 'ec_admin_save_additional_options', get_option( 'ec_option_auto_send_refund_email' ), __( 'Order Refunds: Automatically send refund email to customer', 'wp-easycart-pro' ), __( 'Enabling this feature will automatically email the customer when a refund is completed. This will happen for both full and partial refunds.', 'wp-easycart-pro' ) );
	}

	public function update_order_date( ){
		global $wpdb;
		$order_id = $_POST['order_id'];
		$order_date = date( "Y-m-d h:i:s", strtotime( $_POST['order_date'] ) );
		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET order_date = %s, last_updated = NOW( ) WHERE order_id = %d", $order_date, $order_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-date-update" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "order_date", %s )', $order_log_id, $order_id, $order_date ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function add_line_item( ){
		global $wpdb;
		$order_id = $_POST['order_id'];
		$product_id = $_POST['order_line_add_product_id'];
		$quantity = $_POST['order_line_add_quantity'];
		$product = $wpdb->get_row( $wpdb->prepare( "SELECT product_id, title, model_number, price, image1, is_download, is_giftcard, is_taxable, is_shippable FROM ec_product WHERE product_id = %d", $product_id ) );
		$total_price = $product->price * $quantity;
		$wpdb->query( $wpdb->prepare( "INSERT INTO ec_orderdetail( order_id, product_id, title, model_number, unit_price, total_price, quantity, image1, is_download, is_giftcard, is_taxable, is_shippable ) VALUES( %d, %d, %s, %s, %s, %s, %d, %s, %d, %d, %d, %d )", $order_id, $product->product_id, $product->title, $product->model_number, $product->price, $total_price, $quantity, $product->image1, $product->is_download, $product->is_giftcard, $product->is_taxable, $product->is_shippable ) );
		$insert_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-line-added" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderdetail_id", %s )', $order_log_id, $order_id, $insert_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "product_id", %s )', $order_log_id, $order_id, (int) $product->product_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "title", %s )', $order_log_id, $order_id, $product->title ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "model_number", %s )', $order_log_id, $order_id, $product->model_number ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "unit_price", %s )', $order_log_id, $order_id, $product->price ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "total_price", %s )', $order_log_id, $order_id, $total_price ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "quantity", %s )', $order_log_id, $order_id, $quantity ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "image1", %s )', $order_log_id, $order_id, $product->image1 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "is_download", %s )', $order_log_id, $order_id, $product->is_download ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "is_giftcard", %s )', $order_log_id, $order_id, $product->is_giftcard ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "is_taxable", %s )', $order_log_id, $order_id, $product->is_taxable ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "is_shippable", %s )', $order_log_id, $order_id, $product->is_shippable ) );
		do_action( 'wpeasycart_order_detail_line_added', $order_id, $insert_id );
		do_action( 'wpeasycart_order_updated', $order_id );
		return $insert_id;
	}

	public function edit_line_item( ){
		global $wpdb;
		$order_id = $_POST['order_id'];
		$orderdetail_id = $_POST['orderdetail_id'];
		$quantity = $_POST['line_item_quantity_'.$orderdetail_id];
		$unit_price = $_POST['line_item_unit_price_'.$orderdetail_id];
		$total_price = $_POST['line_item_total_price_'.$orderdetail_id];
		$title = stripslashes_deep( $_POST['line_item_title_'.$orderdetail_id] );
		$model_number = stripslashes_deep( $_POST['line_item_model_number_'.$orderdetail_id] );
		$giftcard_id = ( isset( $_POST['line_item_giftcard_id_'.$orderdetail_id] ) ) ? $_POST['line_item_giftcard_id_'.$orderdetail_id] : '';
		$gift_card_email = ( isset( $_POST['line_item_gift_card_email_'.$orderdetail_id] ) ) ? $_POST['line_item_gift_card_email_'.$orderdetail_id] : '';
		$gift_card_from_name = ( isset( $_POST['line_item_gift_card_from_name_'.$orderdetail_id] ) ) ? $_POST['line_item_gift_card_from_name_'.$orderdetail_id] : '';
		$gift_card_to_name = ( isset( $_POST['line_item_gift_card_to_name_'.$orderdetail_id] ) ) ? $_POST['line_item_gift_card_to_name_'.$orderdetail_id] : '';
		$gift_card_message = ( isset( $_POST['line_item_gift_card_message_'.$orderdetail_id] ) ) ? $_POST['line_item_gift_card_message_'.$orderdetail_id] : '';
		$optionitem_name_1 = ( isset( $_POST['line_item_optionitem_name_1_'.$orderdetail_id] ) ) ? $_POST['line_item_optionitem_name_1_'.$orderdetail_id] : '';
		$optionitem_name_2 = ( isset( $_POST['line_item_optionitem_name_2_'.$orderdetail_id] ) ) ? $_POST['line_item_optionitem_name_2_'.$orderdetail_id] : '';
		$optionitem_name_3 = ( isset( $_POST['line_item_optionitem_name_3_'.$orderdetail_id] ) ) ? $_POST['line_item_optionitem_name_3_'.$orderdetail_id] : '';
		$optionitem_name_4 = ( isset( $_POST['line_item_optionitem_name_4_'.$orderdetail_id] ) ) ? $_POST['line_item_optionitem_name_4_'.$orderdetail_id] : '';
		$optionitem_name_5 = ( isset( $_POST['line_item_optionitem_name_5_'.$orderdetail_id] ) ) ? $_POST['line_item_optionitem_name_5_'.$orderdetail_id] : '';
		if( isset( $_POST['adv_items'] ) ){
			foreach( $_POST['adv_items'] as $adv_item ){
				$wpdb->query( $wpdb->prepare( "UPDATE ec_order_option SET option_value = %s WHERE order_option_id = %d", $adv_item['value'], $adv_item['id'] ) );
			}
		}
		do_action( 'wpeasycart_order_detail_line_update', $order_id, $orderdetail_id, $title, $model_number, $quantity, $unit_price, $total_price, $giftcard_id, $gift_card_email, $gift_card_from_name, $gift_card_to_name, $gift_card_message, $optionitem_name_1, $optionitem_name_2, $optionitem_name_3, $optionitem_name_4, $optionitem_name_5 );
		$wpdb->query( $wpdb->prepare( "UPDATE ec_orderdetail SET title = %s, model_number = %s, quantity = %s, unit_price = %s, total_price = %s, giftcard_id = %s, gift_card_email = %s, gift_card_from_name = %s, gift_card_to_name = %s, gift_card_message = %s, optionitem_name_1 = %s, optionitem_name_2 = %s, optionitem_name_3 = %s, optionitem_name_4 = %s, optionitem_name_5 = %s WHERE order_id = %d AND orderdetail_id = %d", $title, $model_number, $quantity, $unit_price, $total_price, $giftcard_id, $gift_card_email, $gift_card_from_name, $gift_card_to_name, $gift_card_message, $optionitem_name_1, $optionitem_name_2, $optionitem_name_3, $optionitem_name_4, $optionitem_name_5, $order_id, $orderdetail_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-line-updated" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderdetail_id", %s )', $order_log_id, $order_id, $orderdetail_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "title", %s )', $order_log_id, $order_id, $title ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "model_number", %s )', $order_log_id, $order_id, $model_number ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "unit_price", %s )', $order_log_id, $order_id, $unit_price ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "total_price", %s )', $order_log_id, $order_id, $total_price ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "quantity", %s )', $order_log_id, $order_id, $quantity ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "giftcard_id", %s )', $order_log_id, $order_id, $giftcard_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_email", %s )', $order_log_id, $order_id, $gift_card_email ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_from_name", %s )', $order_log_id, $order_id, $gift_card_from_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_to_name", %s )', $order_log_id, $order_id, $gift_card_to_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_message", %s )', $order_log_id, $order_id, $gift_card_message ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_1", %s )', $order_log_id, $order_id, $optionitem_name_1 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_2", %s )', $order_log_id, $order_id, $optionitem_name_2 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_3", %s )', $order_log_id, $order_id, $optionitem_name_3 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_4", %s )', $order_log_id, $order_id, $optionitem_name_4 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_5", %s )', $order_log_id, $order_id, $optionitem_name_5 ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function delete_line_item( ){
		global $wpdb;
		$order_id = $_POST['order_id'];
		$orderdetail_id = $_POST['orderdetail_id'];
		$line_item = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_orderdetail WHERE orderdetail_id = %d', $orderdetail_id ) );
		do_action( 'wpeasycart_order_detail_line_delete', $order_id, $orderdetail_id );
		$wpdb->query( $wpdb->prepare( "DELETE FROM ec_orderdetail WHERE order_id = %d AND orderdetail_id = %d", $order_id, $orderdetail_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-line-deleted" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderdetail_id", %s )', $order_log_id, $order_id, $orderdetail_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "title", %s )', $order_log_id, $order_id, $line_item->title ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "model_number", %s )', $order_log_id, $order_id, $line_item->model_number ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "unit_price", %s )', $order_log_id, $order_id, $line_item->unit_price ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "total_price", %s )', $order_log_id, $order_id, $line_item->total_price ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "quantity", %s )', $order_log_id, $order_id, $line_item->quantity ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "giftcard_id", %s )', $order_log_id, $order_id, $line_item->giftcard_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_email", %s )', $order_log_id, $order_id, $line_item->gift_card_email ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_from_name", %s )', $order_log_id, $order_id, $line_item->gift_card_from_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_to_name", %s )', $order_log_id, $order_id, $line_item->gift_card_to_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gift_card_message", %s )', $order_log_id, $order_id, $line_item->gift_card_message ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_1", %s )', $order_log_id, $order_id, $line_item->optionitem_name_1 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_2", %s )', $order_log_id, $order_id, $line_item->optionitem_name_2 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_3", %s )', $order_log_id, $order_id, $line_item->optionitem_name_3 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_4", %s )', $order_log_id, $order_id, $line_item->optionitem_name_4 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "optionitem_name_5", %s )', $order_log_id, $order_id, $line_item->optionitem_name_5 ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function update_order_management_info( ){
		global $wpdb;

		$order_id = $_POST['order_id'];
		$user_email = stripslashes_deep( $_POST['user_email'] );
		$email_other = stripslashes_deep( $_POST['email_other'] );
		$card_holder_name = stripslashes_deep( $_POST['card_holder_name'] );
		$creditcard_digits = $_POST['creditcard_digits'];
		$cc_exp_month = $_POST['cc_exp_month'];
		$cc_exp_year = $_POST['cc_exp_year'];

		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET user_email = %s, email_other = %s, card_holder_name = %s, creditcard_digits = %s, cc_exp_month = %s, cc_exp_year = %s, last_updated = NOW( ) WHERE order_id = %d", $user_email, $email_other, $card_holder_name, $creditcard_digits, $cc_exp_month, $cc_exp_year, $order_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-credit-card-update" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "user_email", %s )', $order_log_id, $order_id, $user_email ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "email_other", %s )', $order_log_id, $order_id, $email_other ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "card_holder_name", %s )', $order_log_id, $order_id, $card_holder_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "creditcard_digits", %s )', $order_log_id, $order_id, $creditcard_digits ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "cc_exp_month", %s )', $order_log_id, $order_id, $cc_exp_month ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "cc_exp_year", %s )', $order_log_id, $order_id, $cc_exp_year ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function update_order_management_info_bottom( ){
		global $wpdb;

		$order_id = $_POST['order_id'];
		$order_ip_address = $_POST['order_ip_address'];
		$agreed_to_terms = $_POST['agreed_to_terms'];

		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET order_ip_address = %s, agreed_to_terms = %d, last_updated = NOW( ) WHERE order_id = %d", $order_ip_address, $agreed_to_terms, $order_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-terms-update" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "order_ip_address", %s )', $order_log_id, $order_id, $order_ip_address ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "agreed_to_terms", %s )', $order_log_id, $order_id, $agreed_to_terms ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function update_billing_address( ){
		global $wpdb;

		$order_id = $_POST['order_id'];
		$billing_first_name = stripslashes_deep( $_POST['billing_first_name'] );
		$billing_last_name = stripslashes_deep( $_POST['billing_last_name'] );
		$billing_company_name = stripslashes_deep( $_POST['billing_company_name'] );
		$billing_address_line_1 = stripslashes_deep( $_POST['billing_address_line_1'] );
		$billing_address_line_2 = stripslashes_deep( $_POST['billing_address_line_2'] );
		$billing_city = stripslashes_deep( $_POST['billing_city'] );
		$billing_state = stripslashes_deep( $_POST['billing_state'] );
		$billing_country = stripslashes_deep( $_POST['billing_country'] );
		$billing_zip = stripslashes_deep( $_POST['billing_zip'] );
		$billing_phone = stripslashes_deep( $_POST['billing_phone'] );

		do_action( 'wpeasycart_admin_order_billing_update', $order_id, $billing_first_name, $billing_last_name, $billing_company_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_country, $billing_zip, $billing_phone );

		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET billing_first_name = %s, billing_last_name = %s, billing_company_name = %s, billing_address_line_1 = %s, billing_address_line_2 = %s, billing_city = %s, billing_state = %s, billing_country = %s, billing_zip = %s, billing_phone = %s, last_updated = NOW( ) WHERE order_id = %d", $billing_first_name, $billing_last_name, $billing_company_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_country, $billing_zip, $billing_phone, $order_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-billing-update" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_first_name", %s )', $order_log_id, $order_id, $billing_first_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_last_name", %s )', $order_log_id, $order_id, $billing_last_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_company_name", %s )', $order_log_id, $order_id, $billing_company_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_address_line_1", %s )', $order_log_id, $order_id, $billing_address_line_1 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_address_line_2", %s )', $order_log_id, $order_id, $billing_address_line_2 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_city", %s )', $order_log_id, $order_id, $billing_city ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_state", %s )', $order_log_id, $order_id, $billing_state ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_zip", %s )', $order_log_id, $order_id, $billing_zip ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_country", %s )', $order_log_id, $order_id, $billing_country ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "billing_phone", %s )', $order_log_id, $order_id, $billing_phone ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function update_shipping_address( ){
		global $wpdb;

		$order_id = $_POST['order_id'];
		$shipping_first_name = stripslashes_deep( $_POST['shipping_first_name'] );
		$shipping_last_name = stripslashes_deep( $_POST['shipping_last_name'] );
		$shipping_company_name = stripslashes_deep( $_POST['shipping_company_name'] );
		$shipping_address_line_1 = stripslashes_deep( $_POST['shipping_address_line_1'] );
		$shipping_address_line_2 = stripslashes_deep( $_POST['shipping_address_line_2'] );
		$shipping_city = stripslashes_deep( $_POST['shipping_city'] );
		$shipping_state = stripslashes_deep( $_POST['shipping_state'] );
		$shipping_country = stripslashes_deep( $_POST['shipping_country'] );
		$shipping_zip = stripslashes_deep( $_POST['shipping_zip'] );
		$shipping_phone = stripslashes_deep( $_POST['shipping_phone'] );

		do_action( 'wpeasycart_admin_order_shipping_update', $order_id, $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_country, $shipping_zip, $shipping_phone );

		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET shipping_first_name = %s, shipping_last_name = %s, shipping_company_name = %s, shipping_address_line_1 = %s, shipping_address_line_2 = %s, shipping_city = %s, shipping_state = %s, shipping_country = %s, shipping_zip = %s, shipping_phone = %s, last_updated = NOW( ) WHERE order_id = %d", $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_country, $shipping_zip, $shipping_phone, $order_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-shipping-update" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_first_name", %s )', $order_log_id, $order_id, $shipping_first_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_last_name", %s )', $order_log_id, $order_id, $shipping_last_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_company_name", %s )', $order_log_id, $order_id, $shipping_company_name ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_address_line_1", %s )', $order_log_id, $order_id, $shipping_address_line_1 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_address_line_2", %s )', $order_log_id, $order_id, $shipping_address_line_2 ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_city", %s )', $order_log_id, $order_id, $shipping_city ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_state", %s )', $order_log_id, $order_id, $shipping_state ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_zip", %s )', $order_log_id, $order_id, $shipping_zip ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_country", %s )', $order_log_id, $order_id, $shipping_country ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_phone", %s )', $order_log_id, $order_id, $shipping_phone ) );
		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function update_order_totals( ){
		global $wpdb;

		$order_id = $_POST['order_id'];
		$sub_total = $_POST['sub_total'];
		$tax_total = $_POST['tax_total'];
		$shipping_total = $_POST['shipping_total'];
		$discount_total = $_POST['discount_total'];
		$vat_total = ( isset( $_POST['vat_total'] ) ) ? $_POST['vat_total'] : '';
		$duty_total = ( isset( $_POST['duty_total'] ) ) ? $_POST['duty_total'] : '';
		$grand_total = $_POST['grand_total'];
		$refund_total = $_POST['refund_total'];
		$gst_total = ( isset( $_POST['gst_total'] ) ) ? $_POST['gst_total'] : '';
		$gst_rate = ( isset( $_POST['gst_rate'] ) ) ? $_POST['gst_rate'] : '';
		$pst_total = ( isset( $_POST['pst_total'] ) ) ? $_POST['pst_total'] : '';
		$pst_rate = ( isset( $_POST['pst_rate'] ) ) ? $_POST['pst_rate'] : '';
		$hst_total = ( isset( $_POST['hst_total'] ) ) ? $_POST['hst_total'] : '';
		$hst_rate = ( isset( $_POST['hst_rate'] ) ) ? $_POST['hst_rate'] : '';
		$vat_rate = ( isset( $_POST['vat_rate'] ) ) ? $_POST['vat_rate'] : '';
		$vat_registration_number = ( isset( $_POST['vat_registration_number'] ) ) ? $_POST['vat_registration_number'] : '';

		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET sub_total = %s, tax_total = %s, shipping_total = %s, discount_total = %s, vat_total = %s, duty_total = %s, grand_total = %s, refund_total = %s, gst_total = %s, gst_rate = %s, pst_total = %s, pst_rate = %s, hst_total = %s, hst_rate = %s, vat_rate = %s, vat_registration_number = %s, last_updated = NOW( ) WHERE order_id = %d", $sub_total, $tax_total, $shipping_total, $discount_total, $vat_total, $duty_total, $grand_total, $refund_total, $gst_total, $gst_rate, $pst_total, $pst_rate, $hst_total, $hst_rate, $vat_rate, $vat_registration_number, $order_id ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-totals-update" )', $order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "sub_total", %s )', $order_log_id, $order_id, $sub_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "tax_total", %s )', $order_log_id, $order_id, $tax_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_total", %s )', $order_log_id, $order_id, $shipping_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "discount_total", %s )', $order_log_id, $order_id, $discount_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "vat_total", %s )', $order_log_id, $order_id, $vat_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "duty_total", %s )', $order_log_id, $order_id, $duty_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "grand_total", %s )', $order_log_id, $order_id, $grand_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "refund_total", %s )', $order_log_id, $order_id, $refund_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gst_total", %s )', $order_log_id, $order_id, $gst_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "gst_rate", %s )', $order_log_id, $order_id, $gst_rate ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "pst_total", %s )', $order_log_id, $order_id, $pst_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "pst_rate", %s )', $order_log_id, $order_id, $pst_rate ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "hst_total", %s )', $order_log_id, $order_id, $hst_total ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "hst_rate", %s )', $order_log_id, $order_id, $hst_rate ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "vat_rate", %s )', $order_log_id, $order_id, $vat_rate ) );
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "vat_registration_number", %s )', $order_log_id, $order_id, $vat_registration_number ) );

		$order_fees = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_order_fee WHERE order_id = %d ORDER BY order_fee_id ASC', $order_id ) );
		foreach ( $order_fees as $order_fee ) {
			if ( isset( $_POST[ 'flex_fee_' . $order_fee->order_fee_id ] ) ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_order_fee SET fee_total = %s WHERE order_fee_id = %d', $_POST[ 'flex_fee_' . $order_fee->order_fee_id ], $order_fee->order_fee_id ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, %s, %s )', $order_log_id, $order_id, $order_fee->fee_label, $_POST[ 'flex_fee_' . $order_fee->order_fee_id ] ) );
			}
		}

		do_action( 'wpeasycart_order_updated', $order_id );
	}

	public function refund_order() {
		global $wpdb;

		$order_id = $_POST['order_id'];
		$refund_amount  = $_POST['refund_amount'];
		$is_partial_refund = $is_full_refund = false;
		$query_vars = array( );

		$order = $wpdb->get_row( $wpdb->prepare( "SELECT affirm_charge_id, stripe_charge_id, order_notes, refund_total, grand_total, gateway_transaction_id, order_gateway FROM ec_order WHERE order_id = %d", $order_id ) );

		if ( $refund_amount + $order->refund_total < $order->grand_total ) {
			$is_partial_refund = true;
		} else {
			$is_full_refund = true;
		}

		if( $refund_amount + $order->refund_total > $order->grand_total ) {
			$refund_amount = $order->grand_total - $order->refund_total;
		}

		$gateway_class = "ec_" . $order->order_gateway;
		if ( $order->order_gateway == "stripe_connect" ) {
			$gateway_key = 'stripe_charge_id';
		} else if ( $order->order_gateway == "affirm" || $order->order_gateway == "stripe" ) {
			$gateway_key = $order->order_gateway . '_charge_id';
		} else {
			$gateway_key = 'gateway_transaction_id';
		}

		if ( $order->order_gateway == 'paypal-express' ) {
			$gateway = new ec_paypal( );
			$refund_result = $gateway->refund_express_charge( $order_id, $order->{$gateway_key}, $refund_amount );

		} else if( class_exists( $gateway_class ) ) {
			$gateway = new $gateway_class( );
			$refund_result = $gateway->refund_charge( $order->{$gateway_key}, $refund_amount );

		} else {
			$refund_result = false;
		}

		if ( $order->order_gateway == 'square' && $is_full_refund ) {
			$gateway->cancel_order( $order->{$gateway_key} );
		}

		if ( $refund_result ) {
			$date = date('l jS \of F Y h:i:s A');
			if ( isset( $order->order_notes ) && strlen( $order->order_notes ) > 0 ) {
				$new_order_notes = $order->order_notes . PHP_EOL .  PHP_EOL . sprintf( __( "Refund of %s made on %s", 'wp-easycart-pro' ), $GLOBALS['currency']->get_currency_display( $refund_amount ), $date );
			} else {
				$new_order_notes = sprintf( __( "Refund of %s made on %s", 'wp-easycart-pro' ), $GLOBALS['currency']->get_currency_display( $refund_amount ), $date );
			}
			if ( $is_full_refund ) {
				$orderstatus_id = 16;
			} else {
				$orderstatus_id = 17;
			}
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET ec_order.refund_total = ( ec_order.refund_total + %s ), ec_order.order_notes = %s, ec_order.orderstatus_id = %d, last_updated = NOW( ) WHERE ec_order.order_id = %d", $refund_amount, $new_order_notes, $orderstatus_id, $order_id ) );

			if ( $orderstatus_id == 16 ) { // Check for gift card to refund
				$order_details = $wpdb->get_results( $wpdb->prepare( "SELECT is_giftcard, giftcard_id FROM ec_orderdetail WHERE order_id = %d", $order_id ) );
				foreach ( $order_details as $order_detail ) {
					if ( $order_detail->is_giftcard ) {
						$wpdb->query( $wpdb->prepare( "DELETE FROM ec_giftcard WHERE ec_giftcard.giftcard_id = %s", $order_detail->giftcard_id ) );
					}
				}
			}

			if ( $orderstatus_id == 16 ) { // Is Full Refund
				do_action( 'wpeasycart_full_order_refund', $order_id );
			} else {
				do_action( 'wpeasycart_partial_order_refund', $order_id, $refund_amount, ( $refund_amount + $order->refund_total ) );
			}
			$query_vars['success'] = 'refund-success';
			$query_vars['orderstatus_id'] = $orderstatus_id;
			$query_vars['is_full_refund'] = $is_full_refund;
			$query_vars['refund_remaining'] = $GLOBALS['currency']->get_number_only( $order->grand_total - $order->refund_total - $refund_amount );
			$query_vars['refund_total'] = $GLOBALS['currency']->get_number_only( $refund_amount + $order->refund_total );
			$query_vars['order_notes'] = $new_order_notes;
			
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, %s )', $order_id, ( ( $is_full_refund ) ? 'order-refund-full' : 'order-refund-partial' ) ) );
			$order_log_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "refunded_amount", %s )', $order_log_id, $order_id, $refund_amount ) );
			if ( get_option( 'ec_option_auto_send_refund_email' ) ) {
				$this->send_refund_email( $order_id );
			}
		} else {
			$query_vars['error'] = 'refund-failed';
		}
		return $query_vars;
	}
	
	public function send_refund_email( $order_id ) {
		$mysqli = new ec_db_admin();
		$order_row = $mysqli->get_order_row_admin( $order_id );
		if ( $order_row ) {
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_refund_email();
			return true;
		} else {
			return false;
		}
	}

	public function maybe_add_order_details_subscribe_box( $order ) {
		if( ! get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		if ( ! $this->check_for_cloud_license( ) ) {
			return;
		}
		
		$subscribers = $this->get_cloud_message_subscribers( $order->order_id );
		echo '<div class="ec_admin_order_details_row ec_admin_customer_info_top ec_admin_customer_info_details">';
		wp_easycart_admin( )->preloader->print_preloader( "ec_admin_text_notifications" );
		echo '
			<div class="ec_admin_row_heading_title ec_admin_order_details_special_title">' . esc_attr__( 'Text Notifications', 'wp-easycart-pro' ) . '</div>
			<hr />
			<div class="ec_admin_row_heading_title ec_admin_order_details_special_title">' . esc_attr__( 'Subscribers', 'wp-easycart-pro' ) . '</div>';
		if ( count( $subscribers ) > 0 ) {
		echo '
			<ul style="list-style:list; padding:0 0 0 15px;" id="wpeasycart_admin_text_subscriber_list">';
		foreach( $subscribers as $subscriber ) {
			echo '<li>' . esc_attr( $subscriber->phone_number ) . ' <span style="font-size:11px; color:#999; padding-left:10px;">' . $this->get_date_diff_subscriber( $subscriber->date_created, $subscriber->current_datetime ) . '</span><br /><a href="#" onclick="wp_easycart_admin_unsubscribe_text_notification( ' . $order->order_id . ', \'' . $subscriber->phone_number. '\' ); return false;">unsubscribe</a></li>';
		}
		echo '
			</ul>';
		} else {
		echo '
			<strong id="wpeasycart_admin_text_subscriber_list">' . esc_attr__( 'No subscribed mobile numbers.', 'wp-easycart-pro' ) . '</strong>';
		}
		echo '
			<hr />
			<div class="ec_admin_row_heading_title ec_admin_order_details_special_title">' . esc_attr__( 'Add New Subscriber', 'wp-easycart-pro' ) . '</div>
			<div class="ec_admin_order_details_row"><div style="padding:5px 0;"><input type="text" placeholder="' . esc_attr__( 'Enter Phone Number', 'wp-easycart-pro' ) . '" id="text_phone_new" style="float:none;" /></div></div>
			<div class="ec_admin_order_details_row"><a class="ec_admin_order_edit_button" onclick="wp_easycart_admin_subscribe_text_notification( ' . $order->order_id . ' ); return false;">' . esc_attr__( 'Subscribe Number', 'wp-easycart-pro' ) . '</a></div>
		</div>';
	}
	
	public function maybe_add_order_details_text_advert( ) {
		if( get_option( 'ec_option_enable_cloud_messages' ) ) {
			return;
		}
		
		if ( $this->check_for_cloud_license( ) ) {
			return;
		}
		
		if ( get_option( 'ec_option_disable_easycart_ad' ) ) {
			return;
		}
		
		echo '<div style="font-size:1.3em; text-align:center; float:left; width:100%; margin:25px 0 0;">' . esc_attr__( 'Add Text Notifications', 'wp-easycart-pro' ) . '</div>
		<div style="font-size:1em; text-align:center; float:left; width:100%; margin:0 0;">' . esc_attr__( 'Plans start with 150 sends/month', 'wp-easycart-pro' ) . '</div>
		<a href="https://www.wpeasycart.com/cloud-services-customer-text-alert-messaging/" target="_blank" style="display:block; text-align:center; margin:0 auto; color:#FFF; padding:4px 20px; text-decoration:none; border-radius:5px; clear:both; width:60%; background:' . esc_attr( get_option( 'ec_option_admin_color' ) ) . ';">' . esc_attr__( 'View Plan', 'wp-easycart-pro' ) . '</a>
		<a href="https://www.wpeasycart.com/cloud-services-customer-text-alert-messaging/" target="_blank" style="display:block; text-align:center; float:left; width:100%;">
			<img style="max-width:100%;width:307px; height:auto; margin:0 auto;" src="' . plugins_url( 'wp-easycart-pro/admin/images/text-advertisement.jpg' ) . '" />
		</a>';
	}
	
	public function add_shipping_address_copy( $order ) {
		echo '<a href="#" class="wp-easycart-copy-address" data-type="shipping"><span class="dashicons dashicons-clipboard"></span> ' . esc_attr__( 'Copy Shipping Address', 'wp-easycart-pro' ) . '<span class="wp-easycart-admin-copied">' . esc_attr__( 'COPIED!', 'wp-easycart-pro' ) . '</span></a>';
	}
	
	public function add_billing_address_copy( $order ) {
		echo '<a href="#" class="wp-easycart-copy-address" data-type="billing"><span class="dashicons dashicons-clipboard"></span> ' . esc_attr__( 'Copy Billing Address', 'wp-easycart-pro' ) . '<span class="wp-easycart-admin-copied">' . esc_attr__( 'COPIED!', 'wp-easycart-pro' ) . '</span></a>';
	}
	
	public function check_for_cloud_license( ) {
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
			update_option( 'ec_option_enable_cloud_messages', 0 );
			return false;
		}
		
		return true;
	}
	
	public function get_date_diff_subscriber( $date_created, $current_datetime ) {
		$current_time = strtotime( $current_datetime );
		$log_time = strtotime( $date_created );

		$time_diff = $current_time - $log_time;

		if( $time_diff < 60 ) {
			return esc_attr__( 'Less Than a Minute Ago', 'wp-easycart-pro' );
		} else if( $time_diff < 60*2 ) {
			return esc_attr__( 'A Minute Ago', 'wp-easycart-pro' );
		} else if( $time_diff < 60*60 ) {
			return sprintf( esc_attr__( '%d Minutes Ago', 'wp-easycart-pro' ), ceil( $time_diff / 60 ) );
		} else if( $time_diff < 60*60*24 ) {
			return sprintf( esc_attr__( '%d Hours Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 ) ) );
		} else if( $time_diff < 60*60*24*31 ) {
			return sprintf( esc_attr__( '%d Days Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 * 24 ) ) );
		} else if( $time_diff < 60*60*24*7*26 ) {
			return sprintf( esc_attr__( '%d Weeks Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 * 24 * 7 ) ) );
		} else if( $time_diff < 60*60*24*7*52 ) {
			return sprintf( esc_attr__( '%d Months Ago', 'wp-easycart-pro' ), ceil( $time_diff / ( 60 * 60 * 24 * 31 ) ) );
		}else {
			return esc_attr__( 'Over a Year Ago', 'wp-easycart-pro' );
		}
	}
	
	public function get_cloud_message_subscribers( $order_id ) {
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
			)
		);
		
		$request = new WP_Http();
		$response = wp_remote_post(
			'https://cloud.wpeasycart.com/api/text/messages/subscribers/',
			$request_params
		);
		
		if( is_wp_error( $response ) || ! $response || ! isset( $response['body'] ) || $response['body'] == '' ) {
			return array( );
		}
		
		$response = json_decode( $response['body'] );
		
		if( ! isset( $response->subscribers ) ) {
			return array();
		}
		
		return $response->subscribers;
	}

	public function save_pickup_date() {
		if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
			return false;
		}

		global $wpdb;
		$order_id = (int) $_POST['order_id'];
		$pickup_date = $_POST['pickup_date'];
		$pickup_date_time = $_POST['pickup_date_time'];
		$pickup_date_split = ( is_string( $pickup_date ) ) ? explode( ' 00:00:00 GMT', $pickup_date ) : array( $pickup_date, '' );
		$pickup_date_only = ( is_array( $pickup_date_split ) && count( $pickup_date_split ) > 0 ) ? $pickup_date_split[0] : $pickup_date;
		$timestamp = strtotime( $pickup_date_only . ' ' . $pickup_date_time );
		$formatted_pickup_date = date( 'Y-m-d H:i:00', $timestamp );
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET pickup_date = %s WHERE order_id = %d', $formatted_pickup_date, $order_id ) );
		wp_cache_flush( );
		die();
	}

	public function save_pickup_time() {
		if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
			return false;
		}

		global $wpdb;
		$order_id = (int) $_POST['order_id'];
		$pickup_date = $_POST['pickup_time_date'];
		$pickup_date_time = $_POST['pickup_time_time'];
		$pickup_date_split = ( is_string( $pickup_date ) ) ? explode( ' 00:00:00 GMT', $pickup_date ) : array( $pickup_date, '' );
		$pickup_date_only = ( is_array( $pickup_date_split ) && count( $pickup_date_split ) > 0 ) ? $pickup_date_split[0] : $pickup_date;
		$timestamp = strtotime( $pickup_date_only . ' ' . $pickup_date_time );
		$formatted_pickup_date = date( 'Y-m-d H:i:00', $timestamp );
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET pickup_time = %s WHERE order_id = %d', $formatted_pickup_date, $order_id ) );
		wp_cache_flush( );
		die();
	}

	public function save_pickup_location() {
		if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
			return false;
		}

		global $wpdb;
		$order_id = (int) $_POST['order_id'];
		$location_id = (int) $_POST['location_id'];
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET location_id = %d WHERE order_id = %d', $location_id, $order_id ) );
		wp_cache_flush( );
		die();
	}
}
endif; // End if class_exists check

function wp_easycart_admin_orders_pro( ){
	return wp_easycart_admin_orders_pro::instance( );
}
wp_easycart_admin_orders_pro( );
add_action( 'wp_ajax_ec_admin_ajax_save_order_date', 'ec_admin_ajax_save_order_date' );
function ec_admin_ajax_save_order_date( ){
	wp_easycart_admin_orders_pro( )->update_order_date( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_add_new_order_detail_line_item', 'ec_admin_ajax_add_new_order_detail_line_item' );
function ec_admin_ajax_add_new_order_detail_line_item( ){
	global $wpdb;
	$orderdetail_id = wp_easycart_admin_orders_pro( )->add_line_item( );
	$line_item = $wpdb->get_row( $wpdb->prepare( "SELECT ec_orderdetail.*, ec_order.subscription_id FROM ec_orderdetail LEFT JOIN ec_order ON (ec_order.order_id = ec_orderdetail.order_id) WHERE ec_orderdetail.orderdetail_id = %s ORDER BY orderdetail_id", $orderdetail_id ) );
	include(  WP_PLUGIN_DIR . '/wp-easycart/admin/template/orders/orders/order-item.php' );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_edit_order_detail_line_item', 'ec_admin_ajax_edit_order_detail_line_item' );
function ec_admin_ajax_edit_order_detail_line_item( ){
	wp_easycart_admin_orders_pro( )->edit_line_item( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_delete_order_detail_line_item', 'ec_admin_ajax_delete_order_detail_line_item' );
function ec_admin_ajax_delete_order_detail_line_item( ){
	wp_easycart_admin_orders_pro( )->delete_line_item( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_order_management_details', 'ec_admin_ajax_save_order_management_details' );
function ec_admin_ajax_save_order_management_details( ){
	wp_easycart_admin_orders_pro( )->update_order_management_info( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_order_management_details_bottom', 'ec_admin_ajax_save_order_management_details_bottom' );
function ec_admin_ajax_save_order_management_details_bottom( ){
	wp_easycart_admin_orders_pro( )->update_order_management_info_bottom( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_order_billing_address', 'ec_admin_ajax_save_order_billing_address' );
function ec_admin_ajax_save_order_billing_address( ){
	wp_easycart_admin_orders_pro( )->update_billing_address( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_save_order_shipping_address', 'ec_admin_ajax_save_order_shipping_address' );
function ec_admin_ajax_save_order_shipping_address( ){
	wp_easycart_admin_orders_pro( )->update_shipping_address( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_edit_order_totals', 'ec_admin_ajax_edit_order_totals' );
function ec_admin_ajax_edit_order_totals( ){
	wp_easycart_admin_orders_pro( )->update_order_totals( );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_process_refund', 'ec_admin_ajax_process_refund' );
function ec_admin_ajax_process_refund( ){
	$refund_result = wp_easycart_admin_orders_pro( )->refund_order( );
	echo json_encode( $refund_result );
	die( );
}
add_action( 'wp_ajax_ec_admin_ajax_refresh_order_history', 'ec_admin_ajax_refresh_order_history' );
function ec_admin_ajax_refresh_order_history( ){
	wp_easycart_admin_orders_pro( )->print_order_history( (int) $_POST['order_id'] );
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_subscribe_text_notification', 'ec_admin_ajax_subscribe_text_notification' );
function ec_admin_ajax_subscribe_text_notification( ){
	wp_easycart_admin_pro()->subscribe_text_notification( $_POST['order_id'], $_POST['phone_number'] );
	$subscribers = wp_easycart_admin_orders_pro()->get_cloud_message_subscribers( (int) $_POST['order_id'] );
	if ( count( $subscribers ) > 0 ) {
		echo '<ul style="list-style:list; padding:0 0 0 15px;" id="wpeasycart_admin_text_subscriber_list">';
		foreach( $subscribers as $subscriber ) {
			echo '<li>' . esc_attr( $subscriber->phone_number ) . ' <span style="font-size:11px; color:#999; padding-left:10px;">' . wp_easycart_admin_orders_pro()->get_date_diff_subscriber( $subscriber->date_created, $subscriber->current_datetime ) . '</span><br /><a href="#" onclick="wp_easycart_admin_unsubscribe_text_notification( ' . (int) $_POST['order_id'] . ', \'' . $subscriber->phone_number. '\' ); return false;">unsubscribe</a></li>';
		}
		echo '</ul>';
	} else {
		echo '<strong id="wpeasycart_admin_text_subscriber_list">' . esc_attr__( 'No subscribed mobile numbers.', 'wp-easycart-pro' ) . '</strong>';
	}
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_unsubscribe_text_notification', 'ec_admin_ajax_unsubscribe_text_notification' );
function ec_admin_ajax_unsubscribe_text_notification( ){
	wp_easycart_admin_pro()->unsubscribe_text_notification( $_POST['order_id'], $_POST['phone_number'] );
	$subscribers = wp_easycart_admin_orders_pro()->get_cloud_message_subscribers( (int) $_POST['order_id'] );
	if ( count( $subscribers ) > 0 ) {
		echo '<ul style="list-style:list; padding:0 0 0 15px;" id="wpeasycart_admin_text_subscriber_list">';
		foreach( $subscribers as $subscriber ) {
			echo '<li>' . esc_attr( $subscriber->phone_number ) . ' <span style="font-size:11px; color:#999; padding-left:10px;">' . wp_easycart_admin_orders_pro()->get_date_diff_subscriber( $subscriber->date_created, $subscriber->current_datetime ) . '</span><br /><a href="#" onclick="wp_easycart_admin_unsubscribe_text_notification( ' . (int) $_POST['order_id'] . ', \'' . $subscriber->phone_number. '\' ); return false;">unsubscribe</a></li>';
		}
		echo '</ul>';
	} else {
		echo '<strong id="wpeasycart_admin_text_subscriber_list">' . esc_attr__( 'No subscribed mobile numbers.', 'wp-easycart-pro' ) . '</strong>';
	}
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_update_order_pickup_date', 'ec_admin_ajax_update_order_pickup_date' );
function ec_admin_ajax_update_order_pickup_date( ){
	wp_easycart_admin_orders_pro()->save_pickup_date();
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_update_order_pickup_time', 'ec_admin_ajax_update_order_pickup_time' );
function ec_admin_ajax_update_order_pickup_time( ){
	wp_easycart_admin_orders_pro()->save_pickup_time();
	die( );
}

add_action( 'wp_ajax_ec_admin_ajax_update_order_pickup_location', 'ec_admin_ajax_update_order_pickup_location' );
function ec_admin_ajax_update_order_pickup_location( ){
	wp_easycart_admin_orders_pro()->save_pickup_location();
	die( );
}
