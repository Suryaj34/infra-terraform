<?php
if ( ! class_exists( 'ec_taxjar' ) ) :

final class ec_taxjar {

	protected static $_instance = null;
	public $api_version = '2022-01-24';
	public $address_verified;

	public $tax_amount;

	public $subscription_product;
	public $subscription_product_quantity;
	public $subscription_product_discount = 0;
	public $subscription_product_option_price = 0;
	public $subscription_product_option_onetime = 0;

	public static function instance( ) {

		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;

	}

	public function __construct( ){
		add_action( 'init', array( $this, 'initiate_taxjar' ), 10 );
		add_action( 'wpeasycart_cart_updated', array( $this, 'update_tax_amount' ), 10 );
		add_action( 'wpeasycart_order_paid', array( $this, 'add_tax_jar_order' ), 10, 1 );
		add_action( 'wpeasycart_full_order_refund', array( $this, 'refund_tax_jar_order' ), 10, 1 );
	}

	public function setup_subscription_for_tax( $product, $quantity, $discount_total = 0, $option_total = 0, $option_total_onetime = 0 ){
		$this->subscription_product = $product;
		$this->subscription_product_option_price = $option_total;
		$this->subscription_product_quantity = $quantity;
		$this->subscription_product_discount = $discount_total;
		$this->subscription_product_option_onetime = $option_total_onetime;
		$this->update_tax_amount();
	}

	public function initiate_taxjar() {
		if ( isset( $GLOBALS['ec_cart_data']->cart_data->taxjar_tax_amount ) && '' != $GLOBALS['ec_cart_data']->cart_data->taxjar_tax_amount ) {
			$this->tax_amount = $GLOBALS['ec_cart_data']->cart_data->taxjar_tax_amount;
			$this->address_verified = $GLOBALS['ec_cart_data']->cart_data->taxjar_address_verified;
		}else{
			$this->tax_amount = 0;
			$this->address_verified = 0;
		}
	}

	public function get_categories() {
		$api_token = $this->get_api_token();
		$curl = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $this->get_tax_jar_url() . 'categories',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					'Authorization: Bearer ' . $api_token,
					'Content-Type: application/json',
					'x-api-version: ' . $this->api_version,
				),
			)
		);
		$response = curl_exec( $curl );
		curl_close($curl);

		if ( $response !== false ) {
			$response = json_decode( $response );
		}
		$db = new ec_db();
		$db->insert_response( 0, 0, "TaxJar Categories", print_r( $response, true ) );

		if ( isset( $response->error ) || ! isset( $response->categories ) ) {
			return array();
		} else {
			return $response->categories;
		}
	}

	public function update_tax_amount() {
		if ( $this->is_enabled() ) {
			$db = new ec_db();
			$cartpage = new ec_cartpage();

			$api_token = $this->get_api_token();
			$cart_id = $GLOBALS['ec_cart_data']->ec_cart_id;
			$customer_id = $GLOBALS['ec_cart_data']->cart_data->user_id;
			if ( $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
				$customer_id = $GLOBALS['ec_cart_data']->cart_data->guest_key;
			}
			$cart_items = $this->get_tax_jar_cartitems( $cartpage->order_totals->shipping_total, $cartpage->order_totals->discount_total );
			$destination = $this->get_tax_jar_destination();

			$parameters = array(
				'from_country' => get_option( 'ec_option_tax_jar_country' ),
				'from_zip' => get_option( 'ec_option_tax_jar_zip' ),
				'from_state' => get_option( 'ec_option_tax_jar_state' ),
				'from_city' => get_option( 'ec_option_tax_jar_city' ),
				'from_street' => get_option( 'ec_option_tax_jar_address' ),
				'to_country' => $destination->country,
				'to_zip' => ( 'US' == $destination->country ) ? $destination->zip : NULL,
				'to_state' => ( 'US' == $destination->country || 'CA' == $destination->country ) ? $destination->state : NULL,
				'to_city' => ( 'US' == $destination->country ) ? $destination->city : NULL,
				'to_street' => ( 'US' == $destination->country ) ? $destination->address_line_1 . ' ' . $destination->address_line_2 : NULL,
				'shipping' => (string) number_format( $cartpage->order_totals->shipping_total, 2, '.', '' ),
				'customer_id' => $customer_id,
				'line_items' => $cart_items,
			);

			if ( $destination && $this->tax_jar_address_verification( $destination ) ) {
				$body_content = json_encode( $parameters );
				$curl = curl_init();
				curl_setopt_array(
					$curl,
					array(
						CURLOPT_URL => $this->get_tax_jar_url() . 'taxes',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => $body_content,
						CURLOPT_HTTPHEADER => array(
							'Content-Length: ' . strlen( $body_content ),
							'Authorization: Bearer ' . $api_token,
							'Content-Type: application/json',
							'x-api-version: ' . $this->api_version,
						),
					)
				);
				$response = curl_exec( $curl );
				curl_close($curl);

				$db->insert_response( 0, 0, "Tax Jar Lookup", $this->get_tax_jar_url() . 'taxes' . ' -----' . $body_content . ' ---- ' . print_r( $response, true ) );
				$response = json_decode( $response );

				if ( isset( $response->error ) || ! isset( $response->tax ) ) {
					$this->tax_amount = 0;
				} else {
					$this->tax_amount = $response->tax->amount_to_collect;
				}
			}else{
				$this->tax_amount = 0;
			}

			$GLOBALS['ec_cart_data']->cart_data->taxjar_tax_amount = $this->tax_amount;
			$GLOBALS['ec_cart_data']->cart_data->taxjar_address_verified = $this->address_verified;
			$GLOBALS['ec_cart_data']->save_session_to_db( );
		}
	}

	public function add_tax_jar_order ( $order_id ) {
		if ( $this->is_enabled() ) {
			global $wpdb;
			$api_token = $this->get_api_token();
			$order = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_order WHERE order_id = %d', $order_id ) );
			$order_details = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_orderdetail.*, ec_product.TIC FROM ec_orderdetail LEFT JOIN ec_product ON ec_product.product_id = ec_orderdetail.product_id WHERE order_id = %d', $order_id ) );
			$line_items = array();
			foreach ( $order_details as $order_detail ) {
				$line_items[] = array(
					'id' => $order_detail->orderdetail_id,
					'quantity' => $order_detail->quantity,
					'product_identifier' => $order_detail->model_number,
					'description' => $order_detail->title,
					'product_tax_code' => $order_detail->TIC,
					'unit_price' => $order_detail->unit_price,
				);
			}
			$customer_id = $order->user_id;
			if ( ! $order->user_id ) {
				$customer_id = $order->guest_key;
			}
			$parameters = array(
				'transaction_id' => $order_id,
				'transaction_date' => date( 'Y/m/d', strtotime( $order->order_date ) ),
				'from_country' => get_option( 'ec_option_tax_jar_country' ),
				'from_zip' => get_option( 'ec_option_tax_jar_zip' ),
				'from_state' => get_option( 'ec_option_tax_jar_state' ),
				'from_city' => get_option( 'ec_option_tax_jar_city' ),
				'from_street' => get_option( 'ec_option_tax_jar_address' ),
				'to_country' => $order->shipping_country,
				'to_zip' => $order->shipping_zip,
				'to_state' => $order->shipping_state,
				'to_city' => $order->shipping_city,
				'to_street' => $order->shipping_address_line_1 . ' ' . $order->shipping_address_line_2,
				'customer_id' => $customer_id,
				'amount' => number_format( $order->sub_total + $order->shipping_total, 2, '.', '' ),
				'shipping' => number_format( $order->shipping_total, 2, '.', '' ),
				'sales_tax' => number_format( $order->tax_total, 2, '.', '' ),
				'line_items' => $line_items,
			);

			$body_content = json_encode( $parameters );
			$curl = curl_init();
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL => $this->get_tax_jar_url() . 'transactions/orders',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $body_content,
					CURLOPT_HTTPHEADER => array(
						'Content-Length: ' . strlen( $body_content ),
						'Authorization: Bearer ' . $api_token,
						'Content-Type: application/json',
						'x-api-version: ' . $this->api_version,
					),
				)
			);
			$response = curl_exec( $curl );
			curl_close($curl);

			$db = new ec_db( );
			if( $response === false ){
				$db->insert_response( 0, 1, "TaxJar Insert Order ERROR", print_r( $parameters, true ) );
				return;
			}
			$db->insert_response( 0, 0, "TaxJar Insert Order", print_r( $parameters, true ) . ' ----- ' . print_r( $response, true ) );

			$GLOBALS['ec_cart_data']->cart_data->taxjar_tax_amount = "";
			$GLOBALS['ec_cart_data']->cart_data->taxjar_address_verified = 0;
			$GLOBALS['ec_cart_data']->save_session_to_db( );

		}

	}

	public function refund_tax_jar_order( $order_id ){
		if ( $this->is_enabled() ) {
			global $wpdb;
			$api_token = $this->get_api_token();
			$order = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_order WHERE order_id = %d', $order_id ) );
			$order_details = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_orderdetail WHERE order_id = %d', $order_id ) );
			$line_items = array();
			foreach ( $order_details as $order_detail ) {
				$line_items[] = array(
					'id' => $order_detail->orderdetail_id,
					'quantity' => $order_detail->quantity,
					'product_identifier' => $order_detail->model_number,
					'description' => $order_detail->title,
					'product_tax_code' => $order_detail->TIC,
					'unit_price' => number_format( (-1) * $order_detail->price, 2, '.', '' ),
				);
			}
			$customer_id = $GLOBALS['ec_cart_data']->cart_data->user_id;
			if ( $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
				$customer_id = $GLOBALS['ec_cart_data']->cart_data->guest_key;
			}

			$parameters = array( 
				'transaction_id' => $order_id,
				'transaction_reference_id' => $order_id,
				'transaction_date' => date( 'Y/m/d', strtotime( $order->order_date ) ),
				'from_country' => get_option( 'ec_option_tax_jar_country' ),
				'from_zip' => get_option( 'ec_option_tax_jar_zip' ),
				'from_state' => get_option( 'ec_option_tax_jar_state' ),
				'from_city' => get_option( 'ec_option_tax_jar_city' ),
				'from_street' => get_option( 'ec_option_tax_jar_address' ),
				'to_country' => $order->shipping_country,
				'to_zip' => $order->shipping_zip,
				'to_state' => $order->shipping_state,
				'to_city' => $order->shipping_city,
				'to_street' => $order->shipping_address_line_1 . ' ' . $order->shipping_address_line_2,
				'customer_id' => $customer_id,
				'amount' => number_format( (-1) * ( $order->grand_total - $order->tax_total ), 2, '.', '' ),
				'shipping' => number_format( (-1) * $order->shipping_total, 2, '.', '' ),
				'sales_tax' => number_format( (-1) * $order->tax_total, 2, '.', '' ),
				'line_items' => $line_items,
			);

			$body_content = json_encode( $parameters );
			$curl = curl_init();
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL => $this->get_tax_jar_url() . 'transactions/refunds',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $body_content,
					CURLOPT_HTTPHEADER => array(
						'Content-Length: ' . strlen( $body_content ),
						'Authorization: Bearer ' . $api_token,
						'Content-Type: application/json',
						'x-api-version: ' . $this->api_version,
					),
				)
			);
			$response = curl_exec( $curl );
			curl_close($curl);

			$db = new ec_db( );
			if ( false === $response ) {
				$db->insert_response( 0, 1, "TaxJar Refund Order ERROR", print_r( $parameters, true ) );
				return;
			}
			$db->insert_response( 0, 0, "TaxJar Refund Order", print_r( $response, true ) );
		}
	}

	private function get_tax_jar_url() {
		return ( ! get_option( 'ec_option_tax_jar_sandbox' ) ) ? 'https://api.taxjar.com/v2/' : 'https://api.sandbox.taxjar.com/v2/';
	}

	public function tax_jar_address_verification( $destination ) {
		if ( ! get_option( 'ec_option_tax_jar_enable_address_verification' ) ) {
			return true;
		}
		if ( 'US' != $destination->country ) {
			return true;
		}
		$db = new ec_db();
		$parameters = array(
			'street' => $destination->address_line_1 . ' ' . $destination->address_line_2,
			'city' => $destination->city,
			'state' => $destination->state,
			'zip' => $destination->zip,
			"country" => 'US'
		);
		$api_token = $this->get_api_token();

		$body_content = json_encode( $parameters );
		$curl = curl_init();
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $this->get_tax_jar_url() . 'addresses/validate',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $body_content,
				CURLOPT_HTTPHEADER => array(
					'Content-Length: ' . strlen( $body_content ),
					'Authorization: Bearer ' . $api_token,
					'Content-Type: application/json',
					'x-api-version: ' . $this->api_version,
				),
			)
		);
		$response = curl_exec( $curl );
		curl_close($curl);
		
		if ( false === $response ) {
			$db->insert_response( 0, 1, "TaxJar Verify Address ERROR", print_r( $parameters, true ) );
			return;
		}
		$db->insert_response( 0, 0, "TaxJar Verify Address", print_r( $response, true ) );
		$response = json_decode( $response );

		if ( isset( $response->addresses ) && count( $response->addresses ) ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_city = $response->addresses[0]->city;
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = $response->addresses[0]->state;
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $response->addresses[0]->street;
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $response->addresses[0]->zip;
			$GLOBALS['ec_cart_data']->cart_data->shipping_country = $response->addresses[0]->country;
		}

		return true;
	}

	public function is_enabled() {
		if ( ! get_option( 'ec_option_tax_jar_enable' ) ) {
			return false;
		}

		if ( ! get_option( 'ec_option_tax_jar_sandbox' ) && '' == get_option( 'ec_option_tax_jar_live_token' ) ) {
			return false;
		}

		if ( get_option( 'ec_option_tax_jar_sandbox' ) && '' == get_option( 'ec_option_tax_jar_sandbox_token' ) ) {
			return false;
		}

		return true;
	}

	private function get_api_token() {
		return ( ! get_option( 'ec_option_tax_jar_sandbox' ) ) ? get_option( 'ec_option_tax_jar_live_token' ) : get_option( 'ec_option_tax_jar_sandbox_token' );
	}

	private function get_tax_jar_cartitems( $shipping_total, $discount_total ) {
		global $wpdb;
		$cartitems = array();

		if ( isset( $this->subscription_product ) ) {
			$unit_price = $this->subscription_product->price + $this->subscription_product_option_price - ( $this->subscription_product_discount / $this->subscription_product_quantity ) + ( $this->subscription_product_option_onetime / $this->subscription_product_quantity );
			$cartitems[] = array(
				'id' => 0,
				'quantity' => $this->subscription_product_quantity,
				'product_tax_code' => $this->subscription_product->TIC,
				'unit_price' => number_format( $unit_price, 2, '.', '' ),
			 );

		} else {
			$cart = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_tempcart.quantity, ec_product.price, ec_product.model_number, ec_product.TIC FROM ec_tempcart LEFT JOIN ec_product ON ec_product.product_id = ec_tempcart.product_id WHERE ec_tempcart.session_id = %s AND ec_product.is_taxable', $GLOBALS['ec_cart_data']->ec_cart_id ) );
			$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
			if ( count( $cart->cart ) == 0 ) {
				return $cartitems;
			}

			$discount_remaining = $discount_total;
			$onetime_added = 0;

			for ( $i = 0; $i < count( $cart->cart ); $i++ ) {

				if ( $discount_remaining <= 0 ) {
					$unit_price = $cart->cart[$i]->unit_price;

				} else if ( $discount_remaining > ( $cart->cart[$i]->unit_price * $cart->cart[$i]->quantity ) ) {
					$discount_remaining = $discount_remaining - ( $cart->cart[$i]->quantity * ( $cart->cart[$i]->unit_price - .01 ) );
					$unit_price = .01;

				} else {
					$unit_price = ( $cart->cart[$i]->unit_price - ( $discount_remaining / $cart->cart[$i]->quantity ) );
					$discount_remaining = 0;
				}

				$cartitems[] = array(
					'id' => $i + $onetime_added,
					'quantity' => $cart->cart[$i]->quantity,
					'product_tax_code' => $cart->cart[$i]->TIC,
					'unit_price' => number_format( $unit_price, 2, '.', '' ),
				 );

				if ( $cart->cart[$i]->options_price_onetime > 0 ) {
					$onetime_added++;
					$cartitems[] = array(
						'id' => $i + $onetime_added,
						'quantity' => $cart->cart[$i]->quantity,
						'product_tax_code' => $cart->cart[$i]->TIC,
						'unit_price' => number_format( $cart->cart[$i]->options_price_onetime, 2, '.', '' ),
					);
				}
			}
		}

		$ec_db = new ec_db();
		$ec_db->insert_response( 0, 0, "Taxjar Cart Items", print_r( $cartitems, true ) );

		return $cartitems;
	}

	private function get_tax_jar_destination() {
		$parameters = (object) array(
			'address_line_1' => $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1,
			'address_line_2' => $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2,
			'city' => $GLOBALS['ec_cart_data']->cart_data->shipping_city,
			'state' => $GLOBALS['ec_cart_data']->cart_data->shipping_state,
			'zip' => $GLOBALS['ec_cart_data']->cart_data->shipping_zip,
			'country' => $GLOBALS['ec_cart_data']->cart_data->shipping_country,
		);
		return $parameters;
	}
}

endif; // End if class_exists check

function wpeasycart_taxjar() {
	return ec_taxjar::instance( );
}

$GLOBALS['wpeasycart_taxjar'] = wpeasycart_taxjar();
