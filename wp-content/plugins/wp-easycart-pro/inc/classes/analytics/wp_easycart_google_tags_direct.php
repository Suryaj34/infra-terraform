<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_google_tags_direct' ) ) :

	final class wp_easycart_google_tags_direct {

		protected static $_instance = null;
		private $enable_preview = false;
		private $preview_header = '';

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp_easycart_google_tags_direct' ), '1.1' );
		}

		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp_easycart_google_tags_direct' ), '1.1' );
		}

		public function __construct() {
			if ( $this->is_enabled() ) {
				add_action ( 'wp_easycart_product_details_before', array( $this, 'start_track_view_product' ) );
				add_action ( 'wp_easycart_view_product_list', array( $this, 'start_track_view_category' ), 10, 8 );
				add_action ( 'wpeasycart_item_added_to_cart', array( $this, 'start_track_add_to_cart' ), 10, 2 );
				add_action ( 'wp_easycart_display_cart_before', array( $this, 'start_track_initiate_checkout' ), 10, 2 );
				add_action ( 'wp_easycart_stripe_payment_method_complete', array( $this, 'start_track_add_payment_info' ), 10, 2 );
				add_action ( 'wpeasycart_save_checkout_info_complete', array( $this, 'start_track_add_shipping_info' ), 10, 3 );
				add_action ( 'wpeasycart_order_paid', array( $this, 'start_track_purchase' ) );
			}
		}

		public function is_enabled() {
			if ( get_option( 'ec_option_google_ga4_tag_manager_direct' ) && '' != get_option( 'ec_option_google_ga4_tag_manager_measurement_id' ) && '' != get_option( 'ec_option_google_ga4_tag_manager_api_secret' ) && '' != get_option( 'ec_option_google_ga4_tag_manager_server_url' ) ) {
				return true;
			} else {
				return false;
			}
		}

		public function start_track_view_product( $product ) {
			$product_data = array(
				'item_id' => $product->model_number,
				'item_name' => $product->title,
				'price' => (float) number_format( $product->price, 2, '.', '' ),
				'item_brand' => $product->manufacturer_name,
			);

			$event = array(
				'name' => 'view_item',
				'params' => array(
					'currency' => get_option( 'ec_option_base_currency' ),
					'value' => (float) number_format( $product->price, 2, '.', '' ),
					'items' => array(
						$product_data
					),
				),
			);
			$this->send_gtm_event( $event );
		}

		public function start_track_view_category( $product_list, $category_list, $menu_id, $submenu_id, $subsubmenu_id, $manufacturer_id, $group_id, $atts ) {
			if ( $group_id ) {
				global $wpdb;
				$category = $GLOBALS['ec_categories']->get_category( $group_id );
				if ( $category ) {
					$this->track_view_category( $category->category_name, $category->category_id, $product_list );
				}
			}
		}

		private function track_view_category( $category_name, $category_id, $product_list ) {
			$products = array();
			$total_value = 0.0;
			$index = 1;
			if ( is_array( $product_list ) ) {
				foreach ( $product_list as $product_list_item ) {
					$products[] = array(
						'item_id' => $product_list_item->model_number,
						'item_name' => $product_list_item->title,
						'price' => (float) number_format( $product_list_item->price, 2, '.', '' ),
						'item_category' => $category_name,
						'index' => $index,
					);
					$index++;
				}
			}
			$event = array(
				'name' => 'view_item_list',
				'params' => array(
					'item_list_name' => $category_name,
					'item_list_id' => $category_id,
					'items' => $products,
				)
			);
			$this->send_gtm_event( $event );
		}

		public function start_track_add_to_cart( $tempcart_id, $cart_id ) {
			global $wpdb;
			$cartitem = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_tempcart.*, ec_product.model_number, ec_product.price, ec_product.title FROM ec_tempcart LEFT JOIN ec_product ON ec_product.product_id = ec_tempcart.product_id WHERE ec_tempcart.tempcart_id = %d', $tempcart_id ) );
			if ( is_object( $cartitem ) ) {
				$this->track_add_to_cart( $cartitem );
			}
		}

		function track_add_to_cart( $cartitem ) {
			$product = array(
				'item_id' => $cartitem->model_number,
				'item_name' => $cartitem->title,
				'price' => (float) number_format( $cartitem->price, 2, '.', '' ),
				'quantity' => (int) $cartitem->quantity,
			);
			$event = array(
				'name' => 'add_to_cart',
				'params' => array(
					'currency' => get_option( 'ec_option_base_currency' ),
					'value' => (float) number_format( $cartitem->price * $cartitem->quantity, 2, '.', '' ),
					'items' => array( $product ),
				)
			);
			$this->send_gtm_event( $event );
		}

		public function start_track_initiate_checkout( $cart, $order_totals ) {
			if ( is_object( $cart ) && is_array( $cart->cart ) && count( $cart->cart ) > 0 ) {
				$this->track_initiate_checkout( $order_totals->grand_total, $cart->cart, count( $cart->cart ) );
			}
		}

		private function track_initiate_checkout( $cart_total, $cart_items, $total_items ) {
			$products = array();
			if ( is_array( $cart_items ) ) {
				foreach ( $cart_items as $cart_item ) {
					$products[] = array(
						'item_id' => $cart_item->model_number,
						'item_name' => $cart_item->title,
						'price' => (float) number_format( $cart_item->unit_price, 2, '.', '' ),
						'quantity' => (int) $cart_item->quantity,
					);
				}
			}
			$event = array(
				'name' => 'begin_checkout',
				'params' => array(
					'currency' => get_option( 'ec_option_base_currency' ),
					'value' => (float) $cart_total,
					'items' => $products,
				),
			);
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->coupon_code ) && '' != $GLOBALS['ec_cart_data']->cart_data->coupon_code ) {
				$event['params']['coupon'] = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
			}
			$this->send_gtm_event( $event );
		}

		function track_complete_registration() {
			$event = array(
				'name' => '',
				'params' => array(
					'registration_method' => 'email',
					'status' => 'completed',
				),
			);
			$this->send_gtm_event( $event );
		}

		public function start_track_add_payment_info( $cart, $order_totals ) {
			if ( is_object( $cart ) && is_array( $cart->cart ) && count( $cart->cart ) > 0 ) {
				$this->track_add_payment_info( $order_totals->grand_total, $cart->cart );
			}
		}

		private function track_add_payment_info( $cart_total, $cart_items ) {
			$products = array();
			if ( is_array( $cart_items ) ) {
				foreach ( $cart_items as $cart_item ) {
					$products[] = array(
						'item_id' => $cart_item->model_number,
						'item_name' => $cart_item->title,
						'price' => (float) number_format( $cart_item->unit_price, 2, '.', '' ),
						'quantity' => (int) $cart_item->quantity,
					);
				}
			}
			$event = array(
				'name' => 'add_payment_info',
				'params' => array(
					'currency' => get_option( 'ec_option_base_currency' ),
					'value' => (float) $cart_total,
					'payment_type' => 'Credit Card',
					'items' => $products,
				),
			);
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->coupon_code ) && '' != $GLOBALS['ec_cart_data']->cart_data->coupon_code ) {
				$event['params']['coupon'] = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
			}
			$this->send_gtm_event( $event );
		}

		public function start_track_add_shipping_info( $cart, $order_totals, $shipping_method_label ) {
			if ( is_object( $cart ) && is_array( $cart->cart ) && count( $cart->cart ) > 0 ) {
				$this->track_add_shipping_info( $order_totals->grand_total, $cart->cart, $this->simplify_shipping_method_label( $shipping_method_label ) );
			}
		}
		
		private function simplify_shipping_method_label( $shipping_method_label ) {
			$label = wp_strip_all_tags( $shipping_method_label, true );
			$pattern = '/\s*[\$€£][\d.,]+$/';
			return trim( preg_replace( $pattern, '', $label ) );
		}

		private function track_add_shipping_info( $cart_total, $cart_items, $shipping_method_label ) {
			$products = array();
			if ( is_array( $cart_items ) ) {
				foreach ( $cart_items as $cart_item ) {
					$products[] = array(
						'item_id' => $cart_item->model_number,
						'item_name' => $cart_item->title,
						'price' => (float) number_format( $cart_item->unit_price, 2, '.', '' ),
						'quantity' => (int) $cart_item->quantity,
					);
				}
			}
			$user_email = null;
			$user_phone = null;
			$user_first_name = null;
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->email ) {
				$user_email = $GLOBALS['ec_cart_data']->cart_data->email;
			} else if ( '' != $GLOBALS['ec_user']->email ) {
				$user_email = $GLOBALS['ec_user']->email;
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_phone ) {
				$user_phone = preg_replace( '/[^0-9]/', '', $GLOBALS['ec_cart_data']->cart_data->billing_phone );
			} else if ( '' != $GLOBALS['ec_user']->billing->phone ) {
				$user_phone = preg_replace( '/[^0-9]/', '', $GLOBALS['ec_user']->billing->phone );
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) {
				$user_first_name = $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
			} else if ( '' != $GLOBALS['ec_user']->billing->first_name ) {
				$user_first_name = $GLOBALS['ec_user']->billing->first_name;
			}
			$user_data_payload = array(
				'em' => hash( 'sha256', strtolower( $user_email ) ),
				'ph' => hash( 'sha256', $user_phone ),
				'fn' => hash( 'sha256', strtolower( $user_first_name ) )
			);
			$event = array(
				'name' => 'add_shipping_info',
				'params' => array(
					'currency' => get_option( 'ec_option_base_currency' ),
					'value' => (float) $cart_total,
					'shipping_tier' => $shipping_method_label,
					'items' => $products,
					'user_data' => $user_data_payload,
					'event_id' => 'shipping-' . uniqid(),
				),
			);
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->coupon_code ) && '' != $GLOBALS['ec_cart_data']->cart_data->coupon_code ) {
				$event['params']['coupon'] = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
			}
			$this->send_gtm_event( $event );
		}

		public function start_track_purchase( $order_id ) {
			global $wpdb;
			$order = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_order WHERE order_id = %d', $order_id ) );
			if ( is_object( $order ) ) {
				$order_details = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_orderdetail WHERE order_id = %d', $order_id ) );
				if ( is_array( $order_details ) ) {
					$this->track_purchase( $order_id, $order->grand_total, $order->tax_total, $order->shipping_total, $order_details, $order->promo_code );
				}
			}
		}

		private function track_purchase( $order_id, $grand_total, $tax_total, $shipping_total, $order_details, $coupon_code ) {
			$products = array();
			if ( is_array( $order_details ) ) {
				foreach ( $order_details as $order_detail ) {
					$products[] = array(
						'item_id' => $order_detail->model_number,
						'item_name' => $order_detail->title,
						'currency' => get_option( 'ec_option_base_currency' ),
						'price' => (float) number_format( $order_detail->unit_price, 2, '.', '' ),
						'quantity' => (int) $order_detail->quantity,
					);
				}
			}
			$event = array(
				'name' => 'purchase',
				'params' => array(
					'transaction_id' => $order_id,
					'value' => (float) $grand_total,
					'tax' => (float) $tax_total,
					'shipping' => (float) $shipping_total,
					'currency' => get_option( 'ec_option_base_currency' ),
					'items' => $products,
				),
			);
			if ( '' != $coupon_code ) {
				$event['params']['coupon'] = $coupon_code;
			}
			$this->send_gtm_event( $event );
		}

		function send_gtm_event( $event_data ) {
			if ( ! $this->is_bot() ) {
				wpeasycart_session()->handle_session();
				$client_id = $GLOBALS['ec_cart_data']->ec_cart_id;

				$server_container_url = get_option( 'ec_option_google_ga4_tag_manager_server_url' );
				$measurement_id = get_option( 'ec_option_google_ga4_tag_manager_measurement_id' );
				$api_secret = get_option( 'ec_option_google_ga4_tag_manager_api_secret' );

				$url = rtrim( $server_container_url, '/') . '/mp/collect?measurement_id=' . $measurement_id . '&api_secret=' . $api_secret;

				$payload = array(
					'client_id' => $client_id,
					'events' => array( $event_data ),
				);

				if ( $GLOBALS['ec_user']->user_id ) {
					$payload['user_id'] = 'WPEASYCART-' . (int) $GLOBALS['ec_user']->user_id;
				}

				$headers = array(
					'Content-Type: application/json'
				);

				if ( $this->enable_preview ) {
					$headers[] = 'x-gtm-server-preview: ' . $this->preview_header;
				}

				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $url);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt( $ch, CURLOPT_POST, true);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $payload ) );
				curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

				$response = curl_exec( $ch );
				$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
				$error = curl_error( $ch );
				curl_close( $ch );

				if ( $http_code >= 200 && $http_code < 300 ) {
					return true;
				} else {
					$db = new ec_db();
					$db->insert_response( 0, 1, "Google Tags Direct (error)", $url . ' --- ' . $http_code . ' --- ' . $response . ' --- ' . print_r( $payload, true ) . ' --- ' . json_encode( $payload ) );
					return false;
				}
			}
		}
		function is_bot() {
			$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
			$bot_signatures = array(
				'googlebot',
				'bingbot',
				'slurp',
				'duckduckbot',
				'baiduspider',
				'yandexbot',
				'sogou',
				'exabot',
				'facebot',
				'ia_archiver',
				'semrushbot',
				'ahrefsbot',
				'mj12bot'
			);
			$user_agent_lower = strtolower( $user_agent );
			foreach ( $bot_signatures as $bot ) {
				if ( strpos( $user_agent_lower, $bot ) !== false ) {
					return true;
				}
			}
			return false;
		}
	}
endif;

function wp_easycart_google_tags_direct() {
	return wp_easycart_google_tags_direct::instance();
}
wp_easycart_google_tags_direct();
