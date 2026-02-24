<?php
class ec_amazonpay{

	function process_order( $cart_page, $permalink_divider, $grand_total, $order_id ) {
		$payload_update = $this->get_payload( $cart_page, $permalink_divider, $grand_total, $order_id, false );
		$checkout = $this->update_checkout_session( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id, $payload_update );
		
		if( $checkout && isset( $checkout->webCheckoutDetails ) && isset( $checkout->webCheckoutDetails->amazonPayRedirectUrl ) ) {
			$ec_db_admin = new ec_db_admin();
			$ec_db_admin->update_order_status( $order_id, '10' );
			do_action( 'wpeasycart_order_paid', $order_id );
			wp_redirect( $checkout->webCheckoutDetails->amazonPayRedirectUrl );
			die();
		} else {
			wp_redirect( $cart_page . $permalink_divider . 'ec_page=checkout_payment&ec_cart_error=payment_failed' );
			die();
		}
		
		return false;
	}
	
	public function complete_checkout( $session_id ) {
		if( $session_id != $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) {
			return false;
		}
		global $wpdb;
		$order_id = (int) $_GET['order_id'];
		$order = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_order WHERE order_id = %d', $order_id ) );
		if( ! $order ) {
			return false;
		}
		$payload_complete = $this->get_payment_payload( $order->grand_total );
		$checkout = $this->complete_checkout_session( $session_id, $payload_complete );
		return $checkout;
	}
	
	public function handle_checkout_complete( $checkout ) {
		global $wpdb;
		$order_id = (int) $_GET['order_id'];
		$order = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_order WHERE order_id = %d', $order_id ) );
		if( ! $order ) {
			return false;
		}
		$ec_cartpage = new ec_cartpage();

		if( $checkout && isset( $checkout->statusDetails ) && isset( $checkout->statusDetails->state ) && 'Completed' == $checkout->statusDetails->state ) {
			$ec_db_admin = new ec_db_admin();
			$order_row = $ec_db_admin->get_order_row_admin( $order_id );
			$orderdetails = $ec_db_admin->get_order_details_admin( $order_id );

			/* Update Stock Quantity */
			foreach( $orderdetails as $orderdetail ) {
				$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
				if ( $product ) {
					if ( $product->use_optionitem_quantity_tracking ) {
						$ec_db_admin->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
					}
					$ec_db_admin->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
					$ec_db_admin->update_details_stock_adjusted( $orderdetail->orderdetail_id );
				}
			}

			$charge_id = $checkout->chargeId;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET gateway_transaction_id = %s WHERE order_id = %d', $charge_id, $order_id ) );

			// send email
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_email_receipt();
			$order_display->send_gift_cards();

			$ec_db_admin->update_order_status( $order_id, '3' );
			do_action( 'wpeasycart_order_paid', $order_id );

			$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
			$ec_cartpage->order->clear_session();
			$GLOBALS['ec_cart_data']->save_session_to_db();
			
			wp_redirect( $ec_cartpage->cart_page . $ec_cartpage->permalink_divider . 'ec_page=checkout_success&order_id=' . $order_id );
			die();

		} else {
			$ec_db_admin = new ec_db_admin();
			$ec_db_admin->remove_order( $order_id );
			$GLOBALS['ec_cart_data']->save_session_to_db();
			wp_redirect( $ec_cartpage->cart_page . $ec_cartpage->permalink_divider . 'ec_cart_error=payment_failed' );
			die();
		}
		
	}
	
	function process_credit_card( ){
		
		$gateway_response = $this->get_gateway_response( '', array(), array() );
		
		if ( ! $gateway_response ) {
			return false;

		} else {
			if ( $this->is_success ) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function refund_charge( $gateway_transaction_id, $refund_amount ){
		$payload_refund = $this->get_refund_payload( $gateway_transaction_id, $refund_amount );
		$checkout = $this->refund_payment( $payload_refund );
		if( $checkout && isset( $checkout->statusDetails ) && isset( $checkout->statusDetails->state ) && ( 'RefundInitiated' == $checkout->statusDetails->state ||  'Refunded' == $checkout->statusDetails->state ) ) {
			return true;
		} else {
			return false;
		}
	}
	
	/* Button Info */
	private function get_amazon_pay_config() {
		return array(
			'public_key_id'=> get_option( 'ec_option_amazonpay_public_key' ),
			'private_key' => get_option( 'ec_option_amazonpay_private_key' ),
			'region' => get_option( 'ec_option_amazonpay_region' ),
			'sandbox' => (bool) get_option( 'ec_option_amazonpay_is_sandbox' )
		);
	}
	
	private function load_amazon_api() {
		require_once( EC_PLUGIN_DIRECTORY . '-pro/inc/classes/gateway/AmazonPay/phpseclib/Math/BigInteger.php' );
		require_once( EC_PLUGIN_DIRECTORY . '-pro/inc/classes/gateway/AmazonPay/phpseclib/Crypt/RSA.php' );
		require_once( EC_PLUGIN_DIRECTORY . '-pro/inc/classes/gateway/AmazonPay/Client.php' );
		require_once( EC_PLUGIN_DIRECTORY . '-pro/inc/classes/gateway/AmazonPay/ClientInterface.php' );
		require_once( EC_PLUGIN_DIRECTORY . '-pro/inc/classes/gateway/AmazonPay/HttpCurl.php' );
	}
	
	public function get_payload_signature( $payload ) {
		$this->load_amazon_api();
		$amazonpay_config = $this->get_amazon_pay_config();
		try{
			$client = new Amazon\Pay\API\Client( $amazonpay_config );
			$payload_json = json_encode( $payload );
			$signature = $client->generateButtonSignature( $payload_json );
			return $signature;
		}catch (exception $e) {
			return false;
		}
	}
	
	public function get_refund_payload( $charge_id, $amount ) {
		$payload = (object) array(
			'chargeId' => $charge_id,
			'refundAmount' => (object) array(
				'amount' => (string) number_format( $amount, 2, '.', '' ),
				'currencyCode' => esc_attr( get_option( 'ec_option_amazonpay_currency' ) )
			)
		);
		return $payload;
	}
	
	public function get_payment_payload( $order_total ) {
		$payload = (object) array(
			'chargeAmount' => (object) array(
				'amount' => (string) number_format( $order_total, 2, '.', '' ),
				'currencyCode' => esc_attr( get_option( 'ec_option_amazonpay_currency' ) )
			)
		);
		return $payload;
	}

	public function get_payload( $cart_page, $permalink_divider, $order_total, $order_id = '', $is_new = true ) {
		global $wpdb;

		$scopes = array( 'name', 'email', 'billingAddress', 'shippingAddress' );
		if ( get_option( 'ec_option_collect_user_phone' ) ) {
			$scopes[] = 'phoneNumber';
		}

		$allowed_count = 0;
		$allowed_countries = (object) array();
		$countries = $wpdb->get_results( 'SELECT * FROM ec_country WHERE ship_to_active = 1 ORDER BY iso2_cnt ASC' );
		foreach ( $countries as $country ) {
			if ( 'US' == $country->iso2_cnt ) {
				$states = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_state WHERE idcnt_sta = %d AND ship_to_active = 1', $country->id_cnt ) );
				$allowed_states = array();
				foreach ( $states as $state ) {
					$allowed_states[] = $state->code_sta;
				}
				if ( count( $allowed_states ) > 0 ) {
					$allowed_countries->{strtoupper( $country->iso2_cnt )} = (object) array(
						'statesOrRegions' => $allowed_states
					);
					$allowed_count++;
				}
			} else if ( $country->ship_to_active ) { 
				$allowed_countries->{strtoupper( $country->iso2_cnt )} = (object) array();
				$allowed_count++;
			}
		}

		$payload = (object) array(
			'webCheckoutDetails' => (object) array(
				'checkoutReviewReturnUrl' => $cart_page . $permalink_divider . "ec_page=amazonpay_success",
				'checkoutResultReturnUrl' => $cart_page . $permalink_divider . "ec_page=checkout_success&order_id=" . $order_id,
			),
			'paymentDetails' => (object) array(
				'paymentIntent' => 'AuthorizeWithCapture',
				'chargeAmount' => (object) array(
					'amount' => (string) number_format( $order_total, 2, '.', '' ),
					'currencyCode' => esc_attr( get_option( 'ec_option_amazonpay_currency' ) )
				)
			)
		);
		
		if ( $is_new ) {
			$payload->storeId = get_option( 'ec_option_amazonpay_store_id' );
			$payload->scopes = $scopes;
			$payload->paymentDetails->presentmentCurrency = esc_attr( get_option( 'ec_option_amazonpay_currency' ) );
		}

		if ( $allowed_count > 0 && $is_new ) {
			$payload->deliverySpecifications = (object) array(
				'addressRestrictions' => (object) array(
					'type' => 'Allowed',
					'restrictions' => $allowed_countries
				)
			);
		}

		return $payload;
	}

	public function handle_checkout_response( $checkout ) {
		// Set Amazon Specific Vars
		$buyer_id = ( isset( $checkout->buyer->buyerId ) ) ? sanitize_text_field( $checkout->buyer->buyerId ) : '';
		
		$GLOBALS['ec_cart_data']->cart_data->amazon_session_id = $checkout->checkoutSessionId;
		$GLOBALS['ec_cart_data']->cart_data->amazon_buyer_id = $buyer_id;
		$GLOBALS['ec_cart_data']->cart_data->amazon_payment_selection = ( isset( $checkout->paymentPreferences ) && isset( $checkout->paymentPreferences[0] ) && isset( $checkout->paymentPreferences[0]->paymentDescriptor ) ) ? sanitize_text_field( $checkout->paymentPreferences[0]->paymentDescriptor ) : '';
		
		// Set Basic Info
		$name = trim( sanitize_text_field( $checkout->buyer->name ) );
		$name_arr = explode( ' ', $name );
		$first_name = ( isset( $name_arr[0] ) ) ? $name_arr[0] : '';
		$last_name = ( isset( $name_arr[1] ) ) ? $name_arr[ count( $name_arr ) - 1] : '';
		$email = ( isset( $checkout->buyer->email ) ) ? $checkout->buyer->email : '';
		
		$GLOBALS['ec_cart_data']->cart_data->email = sanitize_text_field( $email );
		$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $first_name );
		$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $last_name );

		// Set Billing Address
		$billing_name = ( isset( $checkout->billingAddress->name ) ) ? $checkout->billingAddress->name : '';
		$billing_name_arr = explode( ' ', $billing_name );
		$billing_first_name = ( isset( $billing_name_arr[0] ) ) ? $billing_name_arr[0] : '';
		$billing_last_name = ( isset( $billing_name_arr[1] ) ) ? $billing_name_arr[ count( $billing_name_arr ) - 1] : '';
		$billing_address_line_1 = ( isset( $checkout->billingAddress->addressLine1 ) ) ? $checkout->billingAddress->addressLine1 : '';
		$billing_address_line_2 = ( isset( $checkout->billingAddress->addressLine2 ) ) ? $checkout->billingAddress->addressLine2 : '';
		$billing_address_line_2 .= ( isset( $checkout->billingAddress->addressLine3 ) && $checkout->billingAddress->addressLine3 != '' ) ? ' ' . $checkout->billingAddress->addressLine3 : '';
		$billing_city = ( isset( $checkout->billingAddress->city ) ) ? $checkout->billingAddress->city : '';
		$billing_state = ( isset( $checkout->billingAddress->stateOrRegion ) && $checkout->billingAddress->stateOrRegion != '' ) ? $checkout->billingAddress->stateOrRegion . ' ' : '';
		$billing_state .= ( isset( $checkout->billingAddress->county ) && $checkout->billingAddress->county != '' ) ? $checkout->billingAddress->county . ' ' : '';
		$billing_state .= ( isset( $checkout->billingAddress->district ) && $checkout->billingAddress->district != '' ) ? $checkout->billingAddress->district . ' ' : '';
		$billing_state = trim( $billing_state );
		$billing_zip = ( isset( $checkout->billingAddress->postalCode ) ) ? $checkout->billingAddress->postalCode : '';
		$billing_country = ( isset( $checkout->billingAddress->countryCode ) ) ? $checkout->billingAddress->countryCode : '';
		$billing_phone = ( isset( $checkout->billingAddress->phoneNumber ) ) ? $checkout->billingAddress->phoneNumber : '';
		
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $billing_first_name );
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $billing_last_name );
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = '';
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $billing_address_line_1 );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $billing_address_line_2 );
		$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $billing_city );
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $billing_state );
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $billing_zip );
		$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $billing_country );
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $billing_phone );

		// Set Shipping Address
		$GLOBALS['ec_cart_data']->cart_data->shipping_selector = ( isset( $checkout->shippingAddress ) ) ? "true" : "false";
		if( isset( $checkout->shippingAddress ) ) {
			$shipping_name = ( isset( $checkout->shippingAddress->name ) ) ? $checkout->shippingAddress->name : '';
			$shipping_name_arr = explode( ' ', $shipping_name );
			$shipping_first_name = ( isset( $shipping_name_arr[0] ) ) ? $shipping_name_arr[0] : '';
			$shipping_last_name = ( isset( $shipping_name_arr[1] ) ) ? $shipping_name_arr[ count( $shipping_name_arr ) - 1] : '';
			$shipping_address_line_1 = ( isset( $checkout->shippingAddress->addressLine1 ) ) ? $checkout->shippingAddress->addressLine1 : '';
			$shipping_address_line_2 = ( isset( $checkout->shippingAddress->addressLine2 ) ) ? $checkout->shippingAddress->addressLine2 : '';
			$shipping_address_line_2 .= ( isset( $checkout->shippingAddress->addressLine3 ) && $checkout->shippingAddress->addressLine3 != '' ) ? ' ' . $checkout->shippingAddress->addressLine3 : '';
			$shipping_city = ( isset( $checkout->shippingAddress->city ) ) ? $checkout->shippingAddress->city : '';
			$shipping_state = ( isset( $checkout->shippingAddress->stateOrRegion ) && $checkout->shippingAddress->stateOrRegion != '' ) ? $checkout->shippingAddress->stateOrRegion . ' ' : '';
			$shipping_state .= ( isset( $checkout->shippingAddress->county ) && $checkout->shippingAddress->county != '' ) ? $checkout->shippingAddress->county . ' ' : '';
			$shipping_state .= ( isset( $checkout->shippingAddress->district ) && $checkout->shippingAddress->district != '' ) ? $checkout->shippingAddress->district . ' ' : '';
			$shipping_state = trim( $shipping_state );
			$shipping_zip = ( isset( $checkout->shippingAddress->postalCode ) ) ? $checkout->shippingAddress->postalCode : '';
			$shipping_country = ( isset( $checkout->shippingAddress->countryCode ) ) ? $checkout->shippingAddress->countryCode : '';
			$shipping_phone = ( isset( $checkout->shippingAddress->phoneNumber ) ) ? $checkout->shippingAddress->phoneNumber : '';

			$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $shipping_first_name );
			$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $shipping_last_name );
			$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = '';
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $shipping_address_line_1 );
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $shipping_address_line_2 );
			$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $shipping_city );
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $shipping_state );
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $shipping_zip );
			$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $shipping_country );
			$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $shipping_phone );

		} else {
			$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $billing_first_name );
			$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $billing_last_name );
			$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = '';
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $billing_address_line_1 );
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $billing_address_line_2 );
			$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $billing_city );
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $billing_state );
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $billing_zip );
			$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $billing_country );
			$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $billing_phone );

		}

		$GLOBALS['ec_cart_data']->cart_data->order_notes = '';
		$GLOBALS['ec_cart_data']->cart_data->payment_method = 'amazonpay';
		$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
		$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
		$GLOBALS['ec_cart_data']->save_session_to_db();
		
		do_action( 'wpeasycart_cart_updated' );
	}
	
	public function get_checkout_session( $object_id ) {
		$ec_db = new ec_db();
		$this->load_amazon_api();
		$amazonpay_config = $this->get_amazon_pay_config();
		try{
			$client = new Amazon\Pay\API\Client( $amazonpay_config );
			$checkout = $client->getCheckoutSession( $object_id );
			$order_id = ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : 0;
			$ec_db->insert_response( $order_id, 0, "AmazonPay (Get)", print_r( $checkout, true ) );
			$checkout_response = ( isset( $checkout['response'] ) ) ? json_decode( $checkout['response'] ) : false;
			return ( $checkout_response && isset( $checkout_response->checkoutSessionId ) ) ? $checkout_response : false;
		}catch (exception $e) {
			return false;
		}
	}
	
	public function update_checkout_session( $object_id, $payload ) {
		$ec_db = new ec_db();
		$this->load_amazon_api();
		$amazonpay_config = $this->get_amazon_pay_config();
		try{
			$client = new Amazon\Pay\API\Client( $amazonpay_config );
			$checkout = $client->updateCheckoutSession( $object_id, json_encode( $payload ) );
			$order_id = ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : 0;
			$ec_db->insert_response( $order_id, 0, "AmazonPay (Update)", print_r( $checkout, true ) );
			$checkout_response = ( isset( $checkout['response'] ) ) ? json_decode( $checkout['response'] ) : false;
			return ( $checkout_response && isset( $checkout_response->checkoutSessionId ) ) ? $checkout_response : false;
		}catch (exception $e) {
			return false;
		}
	}
	
	public function complete_checkout_session( $object_id, $payload ) {
		$ec_db = new ec_db();
		$this->load_amazon_api();
		$amazonpay_config = $this->get_amazon_pay_config();
		try{
			$client = new Amazon\Pay\API\Client( $amazonpay_config );
			$checkout = $client->completeCheckoutSession( $object_id, json_encode( $payload ) );
			$order_id = ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : 0;
			$ec_db->insert_response( $order_id, 0, "AmazonPay (Complete)", print_r( $checkout, true ) );
			$checkout_response = ( isset( $checkout['response'] ) ) ? json_decode( $checkout['response'] ) : false;
			return ( $checkout_response && isset( $checkout_response->checkoutSessionId ) ) ? $checkout_response : false;
		}catch (exception $e) {
			return false;
		}
	}
	
	public function refund_payment( $payload ) {
		$ec_db = new ec_db();
		$this->load_amazon_api();
		$amazonpay_config = $this->get_amazon_pay_config();
		try{
			$client = new Amazon\Pay\API\Client( $amazonpay_config );
			$headers = array(
				'x-amz-pay-idempotency-key' => $this->get_idempotency_key()
			);
			$checkout = $client->createRefund( json_encode( $payload ), $headers );
			$order_id = ( isset( $_GET['order_id'] ) ) ? (int) $_GET['order_id'] : 0;
			$ec_db->insert_response( $order_id, 0, "AmazonPay (Refund)", print_r( $checkout, true ) );
			$checkout_response = ( isset( $checkout['response'] ) ) ? json_decode( $checkout['response'] ) : false;
			return ( $checkout_response && isset( $checkout_response->refundId ) ) ? $checkout_response : false;
		}catch (exception $e) {
			return false;
		}
	}
	
	public function get_idempotency_key() {
		$chars = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
		$length = 27;
		$key = '';
		for ( $i=0; $i<$length; $i++ ) {
			$key .= $chars[ rand( 0, count( $chars) - 1 ) ];
		}
		$key .= '-WPEC';
		return $key;
	}
	
}

// On cart totals change, update checkout session.

add_action( 'wp', 'wpeasycart_amazon_pay_handle_session' );
function wpeasycart_amazon_pay_handle_session() {
	if( isset( $_GET['ec_page'] ) && isset( $_GET['amazonCheckoutSessionId'] ) && 'amazonpay_success' == $_GET['ec_page'] ) {
		$ec_cartpage = new ec_cartpage();
		$amazonpay = new ec_amazonpay();
		$checkout = $amazonpay->get_checkout_session( preg_replace( '/[^a-zA-Z0-9\-]/', '', $_GET['amazonCheckoutSessionId'] ) );
		if ( $checkout ) {
			$amazonpay->handle_checkout_response( $checkout );
			$next_page = "checkout_shipping";
			if ( get_option( 'ec_option_skip_shipping_page' ) || $GLOBALS['ec_user']->freeshipping || $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' ) {
				$next_page = "checkout_payment";

			} else if ( ! get_option( 'ec_option_use_shipping' ) || $ec_cartpage->cart->shippable_total_items == 0 ) {
				$next_page = "checkout_payment";

			}
			wp_redirect( $ec_cartpage->cart_page . $ec_cartpage->permalink_divider . 'ec_page=' . $next_page );
			die();

		} else {
			wp_redirect( $ec_cartpage->cart_page . $ec_cartpage->permalink_divider . 'ec_page=checkout_info&ec_cart_error=amazon_error' );
			die();

		}

	} else if( isset( $_GET['ec_page'] ) && isset( $_GET['amazonCheckoutSessionId'] ) && 'checkout_success' == $_GET['ec_page'] ) {
		$amazonpay = new ec_amazonpay();
		$result = $amazonpay->complete_checkout( preg_replace( '/[^a-zA-Z0-9\-]/', '', $_GET['amazonCheckoutSessionId'] ) );
		$amazonpay->handle_checkout_complete( $result );
	}
}

add_action( 'wp_easycart_cart_after_checkout_button', 'wpeasycart_add_amazon_pay_checkout_button', 10, 1 );
add_action( 'wp_easycart_checkout_details_right_end', 'wpeasycart_add_amazon_pay_checkout_button', 10, 1 );
function wpeasycart_add_amazon_pay_checkout_button( $cartpage ) {
	if( get_option( 'ec_option_amazonpay_enable' ) && ! get_option( 'ec_option_amazonpay_hide_early_buttons' ) && '' != get_option( 'ec_option_amazonpay_store_id' ) && '' != get_option( 'ec_option_amazonpay_merchant_id' ) && '' != get_option( 'ec_option_amazonpay_public_key' ) && '' != get_option( 'ec_option_amazonpay_private_key' ) && $cartpage->order_totals->grand_total > 0 && ( '' != $GLOBALS['ec_cart_data']->cart_data->user_id || ( get_option( 'ec_option_allow_guest' ) && ! $cartpage->has_downloads ) ) ) {
		wpeasycart_add_amazon_pay_checkout_button_print( $cartpage );
	}
}
function wpeasycart_add_amazon_pay_checkout_button_print( $cart ) {
	echo '<div class="ec_details_amazon_button"></div>';
}

add_action( 'wp_easycart_cart_after_checkout_button', 'wpeasycart_add_amazon_pay_checkout_button_script', 10, 1 );
add_action( 'wp_easycart_checkout_details_right_end', 'wpeasycart_add_amazon_pay_checkout_button_script', 10, 1 );
function wpeasycart_add_amazon_pay_checkout_button_script( $cartpage ) {
	if( get_option( 'ec_option_amazonpay_enable' ) && ! get_option( 'ec_option_amazonpay_hide_early_buttons' ) && '' != get_option( 'ec_option_amazonpay_store_id' ) && '' != get_option( 'ec_option_amazonpay_merchant_id' ) && '' != get_option( 'ec_option_amazonpay_public_key' ) && '' != get_option( 'ec_option_amazonpay_private_key' ) && $cartpage->order_totals->grand_total > 0 && ( '' != $GLOBALS['ec_cart_data']->cart_data->user_id || ( get_option( 'ec_option_allow_guest' ) && ! $cartpage->has_downloads ) ) ) {
		wpeasycart_add_amazon_pay_checkout_button_script_print( $cartpage );
	}
}
function wpeasycart_add_amazon_pay_checkout_button_script_print( $cartpage ) {
	$amazonpay = new ec_amazonpay();
	$payload = $amazonpay->get_payload( $cartpage->cart_page, $cartpage->permalink_divider, $cartpage->order_totals->converted_grand_total );

	$is_shippable = true;

	echo '<script>
		amazon.Pay.renderButton( \'.ec_details_amazon_button\', {
			merchantId: \'' . esc_attr( get_option( 'ec_option_amazonpay_merchant_id' ) ) . '\',
			publicKeyId: \'' . esc_attr( get_option( 'ec_option_amazonpay_public_key' ) ) . '\',
			ledgerCurrency: \'' . esc_attr( get_option( 'ec_option_amazonpay_currency' ) ) . '\',
			checkoutLanguage: \'' . esc_attr( get_option( 'ec_option_amazonpay_language' ) ) . '\',
			productType: \'' . ( ( $is_shippable ) ? 'PayAndShip' : 'PayOnly' ) . '\',
			placement: \'Checkout\',
			buttonColor: \'' . esc_attr( get_option( 'ec_option_amazonpay_pay_button_color' ) ) . '\',
			createCheckoutSessionConfig: {                     
				payloadJSON: \'' . json_encode( $payload ) . '\',
				signature: \'' . $amazonpay->get_payload_signature( $payload ) . '\'
			}
		});
	</script>';
}

add_action( 'wp_easycart_cart_payment_payment_methods_end', 'wpeasycart_add_amazon_pay_change_payment_box', 10, 1 );
function wpeasycart_add_amazon_pay_change_payment_box( $cartpage ) {
	if( get_option( 'ec_option_amazonpay_enable' ) && '' != get_option( 'ec_option_amazonpay_store_id' ) && '' != get_option( 'ec_option_amazonpay_merchant_id' ) && '' != get_option( 'ec_option_amazonpay_public_key' ) && '' != get_option( 'ec_option_amazonpay_private_key' ) && isset( $GLOBALS['ec_cart_data'] ) && isset( $GLOBALS['ec_cart_data']->cart_data ) && isset( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) ) {
		if( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id == '' ) {
			echo '<div style="float:left; width:100%; margin:10px 0;" class="wpeasycart-amazon-pay-checkout-container"><div style="float:right; width:150px;" class="wpeasycart-amazon-pay-checkout-button">';
			wpeasycart_add_amazon_pay_checkout_button_print( false );
			wpeasycart_add_amazon_pay_checkout_button_script_print( $cartpage );
			echo '</div></div>';

		} else { 
			echo '<div class="ec_cart_option_row">
				<input type="radio" class="no_wrap" name="ec_cart_payment_selection" id="ec_amazonpay" value="amazonpay"' . ( ( 'amazonpay' == $GLOBALS['ec_cart_data']->cart_data->payment_method ) ? ' checked="checked"' : '' ) . ' onchange="ec_update_payment_display( \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );"> ' . wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_amazon' ) . '
			</div>
			<div id="ec_amazonpay_form" style="display:' . ( ( 'amazonpay' == $GLOBALS['ec_cart_data']->cart_data->payment_method ) ? 'block;"' : 'none' ) . '">
				<div class="ec_cart_box_section">
					<span>' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_payment_selection ) . '<span>
					<div class="ec_cart_input_row">
						<a href="' . $cartpage->cart_page . $cartpage->permalink_divider . 'ec_page=checkout_payment" class="wpeasycart_edit_amazon_payment_link">' . wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_change_payment_method' ) . '</a>
					</div>
				</div>
			</div>
			<script type="text/javascript" charset="utf-8">
				amazon.Pay.bindChangeAction( \'.wpeasycart_edit_amazon_payment_link\', {
					amazonCheckoutSessionId: \'' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) . '\',
					changeAction: \'changePayment\'
				});
			</script>';
		}
	}
	
}

add_action( 'wp_easycart_cart_payment_end', 'wpeasycart_add_amazon_pay_change_billing_script', 10, 1 );
function wpeasycart_add_amazon_pay_change_billing_script( $cartpage ) { 
	if( get_option( 'ec_option_amazonpay_enable' ) && ! get_option( 'ec_option_amazonpay_hide_early_buttons' ) && '' != get_option( 'ec_option_amazonpay_store_id' ) && '' != get_option( 'ec_option_amazonpay_merchant_id' ) && '' != get_option( 'ec_option_amazonpay_public_key' ) && '' != get_option( 'ec_option_amazonpay_private_key' ) && isset( $GLOBALS['ec_cart_data'] ) && isset( $GLOBALS['ec_cart_data']->cart_data ) && isset( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) && '' != $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) {
		echo '<script type="text/javascript" charset="utf-8">
			amazon.Pay.bindChangeAction( \'.wpeasycart_edit_billing_address_link_mobile\', {
				amazonCheckoutSessionId: \'' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) . '\',
				changeAction: \'changeAddress\'
			});
			amazon.Pay.bindChangeAction( \'.wpeasycart_edit_billing_address_link\', {
				amazonCheckoutSessionId: \'' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) . '\',
				changeAction: \'changeAddress\'
			});
			amazon.Pay.bindChangeAction( \'.wpeasycart_edit_shipping_address_link\', {
				amazonCheckoutSessionId: \'' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) . '\',
				changeAction: \'changeAddress\'
			});
			amazon.Pay.bindChangeAction( \'.wpeasycart_edit_shipping_address_link_mobile\', {
				amazonCheckoutSessionId: \'' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_session_id ) . '\',
				changeAction: \'changeAddress\'
			});
		</script>';
	}
}
