<?php
	
class ec_ups{
	private $ups_access_license_number;
	private $ups_ship_from_state;
	private $ups_ship_from_zip;
	private $ups_shipper_number;
	private $ups_country_code;
	private $ups_weight_type;
	private $ups_conversion_rate;
	private $ups_negotiated_rates;
	private $token_buffer = 300;
	private $shipper_url;
	
	function __construct( $ec_setting ){
		$this->ups_access_license_number = $ec_setting->get_ups_access_license_number( );
		$this->ups_ship_from_state = $ec_setting->get_ups_ship_from_state( );
		$this->ups_ship_from_zip = $ec_setting->get_ups_ship_from_zip( );
		$this->ups_shipper_number = $ec_setting->get_ups_shipper_number( );
		$this->ups_country_code = $ec_setting->get_ups_country_code( );
		$this->ups_weight_type = $ec_setting->get_ups_weight_type( );
		$this->ups_conversion_rate = $ec_setting->get_ups_conversion_rate( );
		$this->ups_negotiated_rates = $ec_setting->get_ups_negotiated_rates( );
		$this->shipper_url = "https://onlinetools.ups.com/webservices/Rate";
	}
	
	/* Token Auth Functions*/
	private function get_oauth_url( $ups_api_version = 'v1', $ups_api_request_type = 'Shop' ) {
		return 'https://onlinetools.ups.com/api/rating/' . $ups_api_version . '/' . $ups_api_request_type;
	}

	private function get_oauth_headers() {
		$token_info = get_option( 'ec_option_ups_token_info' );
		$headr = array();
		$headr[] = 'Authorization: Bearer ' . ( ( isset( $token_info['access_token'] ) ) ? $token_info['access_token'] : '' );
		$headr[] = 'Content-Type: application/json';
		return $headr;
	}

	private function get_oauth_response( $request_url, $headr, $is_post, $data ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $request_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );
		curl_setopt( $ch, CURLOPT_POST, $is_post );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
		curl_setopt($ch, CURLOPT_TIMEOUT, (int) 10);
		$response = curl_exec($ch);
		if ( curl_errno( $ch ) ) {
			$error_msg = curl_error( $ch );
			$db = new ec_db();
			$db->insert_response( 0, 0, "UPS oAuth ERROR", $error_msg );
		}
		curl_close( $ch );
		return $response;
	}

	function maybe_refresh_token() {
		if ( ! get_option( 'ec_option_ups_use_oauth' ) ) {
			return;
		}

		if ( ! get_option( 'ec_option_ups_token_info' ) ) {
			return;
		}

		$token_info = get_option( 'ec_option_ups_token_info' );

		if ( ! isset( $token_info['access_token'] ) ) {
			return;
		}

		if ( ! isset( $token_info['refresh_token'] ) ) {
			return;
		}

		if ( ! isset( $token_info['refresh_expiration'] ) ) {
			return;
		}
		
		if ( isset( $token_info['access_expiration'] ) && (int) $token_info['access_expiration'] > time() ) {
			return;
			
		} else if ( (int) $token_info['refresh_expiration'] < time() ) {
			$db->insert_response( 0, 0, "UPS Refresh ERROR", "Error in ups refresh token, token has expired." );
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), esc_attr__( 'UPS Disconnected', 'wp-easycart-pro' ), esc_attr__( 'Your UPS refresh token may have expired or ran into problems. This may not mean your live rates stopped working yet, but eventually they will! To fix this problem, navigate to your WP EasyCart, Settings, Shipping Settings and click the Connect to UPS button under the UPS setup.', 'wp-easycart-pro' ) );
			return;
		}

		$this->refresh_token();
	}
	
	function refresh_token() {
		$token_info = get_option( 'ec_option_ups_token_info' );
		if ( ! $token_info || ! is_array( $token_info ) || ! isset( $token_info['refresh_token'] ) ) {
			return (object) array(
				'errors' => esc_attr__( 'No token found', 'wp-easycart-pro' ),
			);
		}
		$request = new WP_Http;
		$response = $request->request(
			'https://connect.wpeasycart.com/ups/?step=refresh&refresh_token=' . urlencode( $token_info['refresh_token'] ),
			array(
				'method' => 'GET',
				'sslverify' => false
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$db = new ec_db();
			$db->insert_response( 0, 0, "UPS Refresh ERROR", "error in ups refresh token, " . $error_message );
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), esc_attr__( 'UPS Disconnected', 'wp-easycart-pro' ), esc_attr__( 'Your UPS refresh token may have expired or ran into problems. This may not mean your live rates stopped working yet, but eventually they will! To fix this problem, navigate to your WP EasyCart, Settings, Shipping Settings and click the Connect to UPS button under the UPS setup.', 'wp-easycart-pro' ) );
			return (object) array(
				'errors' => $error_message,
			);

		} else {
			$response_json = json_decode( $response['body'] );
			$db = new ec_db( );
			if ( isset( $response['body'] ) && isset( $response_json->refresh_token ) ) {
				$db->insert_response( 0, 0, "UPS Refresh", print_r( $response_json, true ) );
				$access_token = $response_json->access_token;
				$access_expiration = $response_json->expires_in;
				$refresh_token = $response_json->refresh_token;
				$refresh_expires = (int) $response_json->refresh_expires;
				$refresh_issued = (int) $response_json->refresh_issued;

				update_option( 'ec_option_ups_token_info', array(
					'access_token' => $access_token,
					'access_expiration' => time() + $access_expiration - 300,
					'refresh_token' => $refresh_token,
					'refresh_expiration' => time() + $refresh_expires - 300,
				) );
			} else {
				$db->insert_response( 0, 0, "UPS Refresh ERROR", print_r( $response, true ) );
				wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), esc_attr__( 'UPS Disconnected', 'wp-easycart-pro' ), esc_attr__( 'Your UPS refresh token may have expired or ran into problems. This may not mean your live rates stopped working yet, but eventually they will! To fix this problem, navigate to your WP EasyCart, Settings, Shipping Settings and click the Connect to UPS button under the UPS setup.', 'wp-easycart-pro' ) );
			}
			return $response_json;
		}
	}
	/* End Token Auth Functions*/

	public function get_rate( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){
		if( $weight == 0 ) {
			$weight = 1/16;// min 1 ounce
		}

		if ( ! $destination_country ) {
			$destination_country = $this->ups_country_code;
		}
		
		if ( ! $destination_zip ) {
			$destination_zip = $this->ups_ship_from_zip;
		}

		$this->maybe_refresh_token();
		$request_url = $this->get_oauth_url( 'v2205', 'Shop' );
		$headr = $this->get_oauth_headers();
		$shipper_data = $this->get_all_rates_shipper_data_oauth( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		$response = $this->get_oauth_response( $request_url, $headr, true, $shipper_data );
		$db = new ec_db( );
		$db->insert_response( 0, 0, "UPS oAuth Get Rate", print_r( $shipper_data, true ) . ' ---- ' . print_r( $response, true ) );
		$response_json = json_decode( $response );
		if ( $response_json && isset( $response_json->response ) && isset( $response_json->response->errors ) && is_array( $response_json->response->errors ) && count( $response_json->response->errors ) > 0 ) {
			$headr = $this->get_oauth_headers();
			$response = $this->get_oauth_response( $request_url, $headr, true, $shipper_data );
			$db->insert_response( 0, 0, "UPS oAuth Get Rate (error)", print_r( $shipper_data, true ) . ' ---- ' . print_r( $response, true ) );
			$response_json = json_decode( $response );
		}
		return $response_json;
	}

	public function get_all_rates( $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){
		if ( $weight == 0 ) {
			$weight = 1/16;// min 1 ounce
		}

		if ( ! $destination_country ) {
			$destination_country = $this->ups_country_code;
		}

		if ( ! $destination_zip ) {
			$destination_zip = $this->ups_ship_from_zip;
		}

		$this->maybe_refresh_token();
		$request_url = $this->get_oauth_url( 'v2205', 'Shop' );
		$headr = $this->get_oauth_headers();
		$shipper_data = $this->get_all_rates_shipper_data_oauth( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		$response = $this->get_oauth_response( $request_url, $headr, true, $shipper_data );
		$db = new ec_db( );
		$db->insert_response( 0, 0, "UPS oAuth Get Rates", print_r( $shipper_data, true ) . ' ---- ' . print_r( $response, true ) );
		$response_json = json_decode( $response );
		if ( $response_json && isset( $response_json->response ) && isset( $response_json->response->errors ) && is_array( $response_json->response->errors ) && count( $response_json->response->errors ) > 0 ) {
			$headr = $this->get_oauth_headers();
			$response = $this->get_oauth_response( $request_url, $headr, true, $shipper_data );
			$db->insert_response( 0, 0, "UPS oAuth Get Rates (error)", print_r( $shipper_data, true ) . ' ---- ' . print_r( $response, true ) );
			$response_json = json_decode( $response );
		}
		$return_rates = array();
		if ( $response_json && isset( $response_json->RateResponse ) && isset( $response_json->RateResponse->RatedShipment ) ) {
			for ( $i = 0; $i < count( $response_json->RateResponse->RatedShipment ); $i++ ) {
				if ( $this->ups_negotiated_rates && isset( $response_json->RateResponse->RatedShipment[ $i ]->NegotiatedRateCharges ) ) {
					$return_rates[] = array(
						'rate_code' => (string) $response_json->RateResponse->RatedShipment[ $i ]->Service->Code,
						'rate' => number_format( floatval( $response_json->RateResponse->RatedShipment[ $i ]->NegotiatedRateCharges->TotalCharge->MonetaryValue ) * $this->ups_conversion_rate, 2, ".", "" ),
						'delivery_days' => (string) ( ( isset( $response_json->RateResponse->RatedShipment[ $i ]->GuaranteedDelivery ) ) ? $response_json->RateResponse->RatedShipment[ $i ]->GuaranteedDelivery->BusinessDaysInTransit : '' ),
					);
				} else {
					$return_rates[] = array(
						'rate_code' => (string) $response_json->RateResponse->RatedShipment[ $i ]->Service->Code,
						'rate' => number_format( floatval( $response_json->RateResponse->RatedShipment[ $i ]->TotalCharges->MonetaryValue ) * $this->ups_conversion_rate, 2, ".", "" ),
						'delivery_days' => (string) ( ( isset( $response_json->RateResponse->RatedShipment[ $i ]->GuaranteedDelivery ) ) ? $response_json->RateResponse->RatedShipment[ $i ]->GuaranteedDelivery->BusinessDaysInTransit : '' ),
					);
				}
			}
		}
		return $return_rates;
	}

	public function get_rate_test( $ship_code, $destination_zip, $destination_country, $weight, $length = 10, $width = 10, $height = 10, $declared_value = 0, $cart = array( ) ){
		$this->maybe_refresh_token();
		$request_url = $this->get_oauth_url( 'v2205', 'Shop' );
		$headr = $this->get_oauth_headers();
		$shipper_data = $this->get_all_rates_shipper_data_oauth( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart, true );
		$response = $this->get_oauth_response( $request_url, $headr, true, $shipper_data );
		$db = new ec_db( );
		$db->insert_response( 0, 0, "UPS oAuth TEST", print_r( $shipper_data, true ) . ' ---- ' . print_r( $response, true ) );
		$response_json = json_decode( $response );
		if ( $response_json && isset( $response_json->response ) && isset( $response_json->response->errors ) && is_array( $response_json->response->errors ) && count( $response_json->response->errors ) > 0 ) {
			$headr = $this->get_oauth_headers();
			$response = $this->get_oauth_response( $request_url, $headr, true, $shipper_data );
			$db->insert_response( 0, 0, "UPS oAuth TEST (error)", print_r( $shipper_data, true ) . ' ---- ' . print_r( $response, true ) );
			$response_json = json_decode( $response );
		}
		return $response_json;
	}

	public function validate_address( $destination_city, $destination_state, $destination_zip, $destination_country ) {
		return true; /* Not Currently Validating Addresses to Prevent User Errors */
	}

	/* oAuth Data Methods */
	private function get_shipper_block_oauth() {
		$ups_settings = ( get_option( 'ec_option_ups_settings' ) ) ? get_option( 'ec_option_ups_settings' ) : array( 'address1' => '', 'city' => '', 'state' => '', 'zip' => '', 'country' => '' );
		$address_lines = array();
		$address_block = array(
			'ShipperNumber'		=> $this->ups_shipper_number,
			'Address'			=> array(
				'AddressLine'	=> array( ),
				'CountryCode'	=> 'US',
			)
		);
		if ( isset( $ups_settings['address1'] ) && '' != $ups_settings['address1'] ) {
			$address_block['Address']['AddressLine'][] = $ups_settings['address1'];
		}
		if ( isset( $ups_settings['address2'] ) && '' != $ups_settings['address2'] ) {
			$address_block['Address']['AddressLine'][] = $ups_settings['address2'];
		}
		if ( isset( $ups_settings['address3'] ) && '' != $ups_settings['address3'] ) {
			$address_block['Address']['AddressLine'][] = $ups_settings['address3'];
		}
		if ( isset( $ups_settings['city'] ) && '' != $ups_settings['city'] ) {
			$address_block['Address']['City'] = $ups_settings['city'];
		}
		if ( isset( $ups_settings['state'] ) && '' != $ups_settings['state'] ) {
			$address_block['Address']['StateProvinceCode'] = $ups_settings['state'];
		}
		$address_block['Address']['PostalCode'] = $this->ups_ship_from_zip;
		$address_block['Address']['CountryCode'] = $this->ups_country_code;
		return $address_block;
	}
	
	private function get_package_block_oauth( $length, $width, $height, $weight, $price ) {
		return (object) array(
			'PackagingType'	=> (object) array(
				'Code'	=> '02',
			),
			'Dimensions'			=> (object) array(
				'UnitOfMeasurement'		=> (object) array(
					'Code'					=> ( $this->ups_weight_type == 'LBS' ) ? 'IN' : 'CM',
					'Description'			=> ( $this->ups_weight_type == 'LBS' ) ? 'Inches' : 'Centermeters',
				),
				'Length'				=> (string) ceil( $length ),
				'Width'					=> (string) ceil( $width ),
				'Height'				=> (string) ceil( $height ),
			),
			'PackageWeight'			=> (object) array(
				'UnitOfMeasurement'		=> (object) array(
					'Code'					=> ( $this->ups_weight_type == 'LBS' ) ? 'LBS' : 'KGS',
					'Description'			=> ( $this->ups_weight_type == 'LBS' ) ? 'Inches' : 'Centemeters',
				),
				'Weight'				=> (string) $weight,
			),
			'PackageServiceOptions'	=> (object) array(
				'DeclaredValue'			=> (object) array(
					'CurrencyCode'			=> get_option( 'ec_option_base_currency' ),
					'MonetaryValue'			=> (string) number_format( $price, 2, '.', '' ),
				),
			),
		);
	}
	
	private function get_all_rates_shipper_data_oauth( $destination_zip, $destination_country, $weight = 1, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ), $is_test = false ){
		$package = array();
		if ( get_option( 'ec_option_ship_items_seperately' ) && count( $cart ) > 0 ) {
			foreach ( $cart as $cart_item ) {
				if ( $cart_item->is_shippable && !$cart_item->exclude_shippable_calculation ) {
					$quantity = ( isset( $cart_item->quantity ) ) ? $cart_item->quantity : 1;
					for ( $i = 0; $i < $quantity; $i++ ) {
						$package[] = $this->get_package_block_oauth( $cart_item->length, $cart_item->width, $cart_item->height, ( ( $cart_item->weight > 0 ) ? $cart_item->weight : (1/16) ), ( ( isset( $cart_item->unit_price ) ) ? $cart_item->unit_price : $cart_item->price ) );
					}
				}
			}
		} else {
			$package_total = 0;
			$last_package_i = 0;

			$current_weight = 0;
			$current_value = 0;
			$products = array();
			foreach ( $cart as $cartitem ) {
				if ( ! isset( $cartitem->exclude_shippable_calculation ) ) {
					$cartitem->exclude_shippable_calculation = 0;
				}
				if ( $cartitem->is_shippable && ! $cartitem->exclude_shippable_calculation ) {
					$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
					for ( $i = 0; $i < $quantity; $i++ ) {
						$products[] = array( 
							'width' => $cartitem->width,
							'height' => $cartitem->height,
							'length' => $cartitem->length,
							'weight' => $cartitem->weight,
						);
						$current_weight += $cartitem->weight;
						$current_value += ( ( isset( $cartitem->unit_price ) ) ? $cartitem->unit_price : $cartitem->price );
						$parcel = $this->calculate_parcel( $products );
						if ( $current_weight > 150 ) {
							$package[] = $this->get_package_block_oauth( $parcel['length'], $parcel['width'], $parcel['height'], $current_weight, $current_value );
							$products = array();
							$current_weight = 0;
							$current_value = 0;
						}
					}
				}
			}

			if ( count( $products ) > 0 ) {
				$parcel = $this->calculate_parcel( $products );
				$package[] = $this->get_package_block_oauth( $parcel['length'], $parcel['width'], $parcel['height'], ( ( $current_weight > 0 ) ? $current_weight : (1/16) ), $current_value );
			}
		}

		$ship_to_address = array(
			'Address' => array(
				'AddressLine' => array(),
				'PostalCode' => $destination_zip,
				'CountryCode' => $destination_country,
			),
		);
		if ( $is_test ) {
			$ups_settings = ( get_option( 'ec_option_ups_settings' ) ) ? get_option( 'ec_option_ups_settings' ) : array( 'address1' => '', 'city' => '', 'state' => '', 'zip' => '', 'country' => '' );
			if ( isset( $ups_settings['city'] ) && '' != $ups_settings['city'] ) {
				$ship_to_address['Address']['City'] = $ups_settings['city'];
			}
			if ( isset( $ups_settings['state'] ) && '' != $ups_settings['state'] ) {
				$ship_to_address['Address']['StateProvinceCode'] = $ups_settings['state'];
			}
			if ( isset( $ups_settings['address1'] ) && '' != $ups_settings['address1'] ) {
				$ship_to_address['Address']['AddressLine'][] = $ups_settings['address1'];
			}
			if ( isset( $ups_settings['address2'] ) && '' != $ups_settings['address2'] ) {
				$ship_to_address['Address']['AddressLine'][] = $ups_settings['address2'];
			}
		} else {
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) ) {
				$ship_to_address['Address']['AddressLine'][] = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1;
			}
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) ) {
				$ship_to_address['Address']['AddressLine'][] = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2;
			}
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) && '' != isset( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) ) {
				$ship_to_address['Address']['StateProvinceCode'] = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
			}
		}

		$rate_request = array(
			'RateRequest' => array(
				'Request' => array(
					'RequestOption' => 'Shop',
					'CustomerClassification' => (object) array(
						'Code' => ( $this->ups_negotiated_rates ) ? '00' : '05',
					),
				),
				'Shipment' => array(
					'Shipper' => $this->get_shipper_block_oauth(),
					'ShipTo' => $ship_to_address,
					'Package' => $package,
					'ShipmentRatingOptions' => array(
						'NegotiatedRatesIndicator' => ( ( $this->ups_negotiated_rates ) ? 'true' : 'false' ),
					),
				),
			),
		);
		if ( $this->is_military_zip( $destination_zip ) ) {
			$rate_request['RateRequest']['Shipment']['Service'] = array(
				'Code' => '93',
			);
		}
		return $rate_request;
	}
	/* End oAuth Data Methods */

	/* Calculation Methods */
	private function calculate_parcel( $products ) {
		$package_dimensions = array( 0, 0, 0 );
		$package_weight = 0;
		$package_volume = 0;
		$package_volume_empty = 0;
		$package_volume_used = 0;
		
		// Step through each product
		foreach ( $products as $product ) {
			$product_dimensions = array( $product['width'], $product['height'], $product['length'] );
			// Twist and turn the item, longest side first ([0]=length, [1]=width, [2]=height)
			rsort( $product_dimensions, SORT_NUMERIC); // Sort $product_dimensions by highest to lowest
			if ( $product_dimensions[0] <= $package_dimensions[0] && $product_dimensions[1] <= $package_dimensions[1] && $product_dimensions[2] <= $package_dimensions[2] && ( $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2] ) <= $package_volume_empty ) {
				$package_volume_empty -= $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
				$package_volume_used += $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
			} else {
				// Package height + item height
				$package_dimensions[2] += $product_dimensions[2];

				// If this is the widest item so far, set item width as package width
				if ( $product_dimensions[1] > $package_dimensions[1] ) {
					$package_dimensions[1] = $product_dimensions[1];
				}

				// If this is the longest item so far, set item length as package length
				if ( $product_dimensions[0] > $package_dimensions[0] ) {
					$package_dimensions[0] = $product_dimensions[0];
				}

				// Twist and turn the package, longest side first ([0]=length, [1]=width, [2]=height)
				rsort( $package_dimensions, SORT_NUMERIC );
				$package_volume = $package_dimensions[0] * $package_dimensions[1] * $package_dimensions[2];
				$package_volume_used += $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
				$package_volume_empty = $package_volume - $package_volume_used;
				
			}
			$package_weight = $package_weight + $product['weight'];
		}

		$parcel = array(
			'weight' => $package_weight,
			'width' => $package_dimensions[0],
			'height' => $package_dimensions[1],
			'length' => $package_dimensions[2],
		);

		return $parcel;
	}
	/* End Calculation Methods */
	private function is_military_zip( $zip_code ) {
		$zip_5 = substr( trim( $zip_code ), 0, 5 );
		if ( ! is_numeric( $zip_5 ) || strlen( $zip_5 ) != 5 ) {
			return false;
		}
		$prefix_2_digit = substr( $zip_5, 0, 2 );
		$prefix_3_digit = substr( $zip_5, 0, 3 );
		if ( $prefix_2_digit === '09' ) {
			return true;
		}
		if ( $prefix_3_digit === '340' ) {
			return true;
		}
		if ( $prefix_3_digit >= '962' && $prefix_3_digit <= '966' ) {
			return true;
		}
		return false;
	}
}
