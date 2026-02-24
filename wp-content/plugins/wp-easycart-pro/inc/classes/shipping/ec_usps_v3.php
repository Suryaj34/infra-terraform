<?php

class ec_usps_v3{
	private $base_url = 'https://apis.usps.com';
	private $access_token = null;
	private $token_expires_at = 0;
	private $ship_from_zip;
	private $rate_code_map;
	private $reverse_map;

	public function __construct( $ec_setting ) {
		$this->ship_from_zip = $ec_setting->get_usps_ship_from_zip();
		$this->rate_code_map = array(
			'FIRST CLASS' => 'USPS_GROUND_ADVANTAGE',
			'FIRST CLASS COMMERCIAL' => 'USPS_GROUND_ADVANTAGE',
			'FIRST CLASS HFP COMMERCIAL' => 'USPS_GROUND_ADVANTAGE',
			'PACKAGE' => 'USPS_GROUND_ADVANTAGE',
			'STANDARD POST' => 'USPS_GROUND_ADVANTAGE',
			'USPS GROUND ADVANTAGE' => 'USPS_GROUND_ADVANTAGE',
			'USPS GROUND ADVANTAGE CUBIC' => 'USPS_GROUND_ADVANTAGE',
			'PARCEL' => 'USPS_GROUND_ADVANTAGE', 
			'PRIORITY' => 'PRIORITY_MAIL',
			'PRIORITY COMMERCIAL' => 'PRIORITY_MAIL',
			'PRIORITY CPP' => 'PRIORITY_MAIL',
			'PRIORITY HFP COMMERCIAL' => 'PRIORITY_MAIL',
			'PRIORITY HFP CPP' => 'PRIORITY_MAIL',
			'EXPRESS' => 'PRIORITY_MAIL_EXPRESS',
			'EXPRESS COMMERCIAL' => 'PRIORITY_MAIL_EXPRESS',
			'EXPRESS CPP' => 'PRIORITY_MAIL_EXPRESS',
			'EXPRESS SH' => 'PRIORITY_MAIL_EXPRESS',
			'EXPRESS SH COMMERCIAL' => 'PRIORITY_MAIL_EXPRESS',
			'EXPRESS HFP CPP' => 'PRIORITY_MAIL_EXPRESS',
			'MEDIA' => 'MEDIA_MAIL',
			'LIBRARY' => 'LIBRARY_MAIL',
			'USPS GXG' => 'GLOBAL_EXPRESS_GUARANTEED',
			'ALL' => 'USPS_GROUND_ADVANTAGE',
			'ONLINE' => 'USPS_GROUND_ADVANTAGE',
			'PLUS' => 'USPS_GROUND_ADVANTAGE',
		);
		$this->reverse_map = array(
			'PRIORITY_MAIL' => 'PRIORITY',
			'PRIORITY_MAIL_EXPRESS' => 'EXPRESS',
			'PRIORITY_MAIL_EXPRESS_SP' => 'EXPRESS',
			'PRIORITY_MAIL_EXPRESS_PA' => 'EXPRESS',
			'PRIORITY_MAIL_EXPRESS_CP' => 'EXPRESS COMMERCIAL',
			'MEDIA_MAIL' => 'MEDIA',
			'LIBRARY_MAIL' => 'LIBRARY',
			'BOUND_PRINTED_MATTER' => 'BOUND PRINTED MATTER',
			'PARCEL_SELECT' => 'PARCEL SELECT',
			'USPS_CONNECT_LOCAL' => 'CONNECT LOCAL',
			'GLOBAL_EXPRESS_GUARANTEED' => 'USPS GXG',
			'PRIORITY_MAIL_INTERNATIONAL' => 'PRIORITY',
			'PRIORITY_MAIL_EXPRESS_INTERNATIONAL' => 'EXPRESS',
			'FIRST-CLASS_PACKAGE_INTERNATIONAL_SERVICE' => 'USPS_GROUND_ADVANTAGE',
			'USPS_GROUND_ADVANTAGE' => 'USPS GROUND ADVANTAGE'
		);
	}

	public function get_rate ( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ) {
		$this->connect();
		if ( '' == $destination_country ) {
			$destination_country = 'US';
		}
		if ( 'US' == $destination_country ) {
			return $this->get_domestic_rate( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		} else {
			return $this->get_international_rate( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}
	}

	public function get_domestic_rate( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart ) {
		$this->connect();
		$rates = $this->get_domestic_rates( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		if ( is_array( $rates ) && count( $rates ) > 0 ) {
			foreach( $rates as $rate ) {
				if ( $ship_code == $rate['rate_code'] ) {
					return $rate['rate'];
				}
			}
		}
		return 'ERROR';
	}

	public function get_international_rate( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart ) {
		$this->connect();
		$rates = $this->get_international_rates( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		if ( is_array( $rates ) && count( $rates ) > 0 ) {
			foreach( $rates as $rate ) {
				if ( $ship_code == $rate['rate_code'] ) {
					return $rate['rate'];
				}
			}
		}
		return 'ERROR';
	}

	public function get_all_rates( $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array() ) {
		$this->connect();
		if ( '' == $destination_country ) {
			$destination_country = 'US';
		}
		if ( 'US' == $destination_country ) {
			return $this->get_domestic_rates( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		} else {
			return $this->get_international_rates( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}
	}

	public function get_rate_test( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){
		$this->connect();
		if ( '' == $destination_country ) {
			$destination_country = 'US';
		}
		if ( 'US' == $destination_country ) {
			$rates = $this->get_domestic_rates( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		} else {
			$rates = $this->get_international_rates( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}
		return ( is_array( $rates ) && count( $rates ) > 0 );
	}

	public function validate_address( $destination_address, $destination_city, $destination_state, $destination_zip, $destination_country ){
		return true;
	}

	private function get_domestic_rates( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart ) {
		if ( '' == $destination_country ) {
			$destination_country = 'US';
		}
		$boxes = $this->create_boxes( $weight, $length, $width, $height, $declared_value, $cart );
		$price_type = 'RETAIL';
		$payment_account = null;
		if ( get_option( 'ec_option_usps_v3_custom_rates' ) ) {
			$price_type = ( get_option( 'ec_option_usps_v3_price_type' ) ) ? get_option( 'ec_option_usps_v3_price_type' ) : 'CONTRACT';
			$payment_account = array(
				'accountType' => ( get_option( 'ec_option_usps_v3_account_type' ) ) ? get_option( 'ec_option_usps_v3_account_type' ) : 'EPS',
				'accountNumber' => ( get_option( 'ec_option_usps_v3_account_number' ) ) ? get_option( 'ec_option_usps_v3_account_number' ) : '', // 10-digit EPS number
				'CRID' => ( get_option( 'ec_option_usps_v3_crid' ) ) ? get_option( 'ec_option_usps_v3_crid' ) : '', // Customer Registration ID
			);
		}
		$pricing_option = array(
			'priceType' => $price_type
		);
		if ( $payment_account ) {
			$pricing_option['paymentAccount'] = $payment_account;
		}
		$responses = array();
		$endpoint = $this->base_url . '/shipments/v3/options/search';
		foreach ( $boxes as $box ) {
			$body = array(
				'originZIPCode' => $this->ship_from_zip,
				'destinationZIPCode' => $destination_zip,
				'pricingOptions' => array(
					$pricing_option,
				),
				'packageDescription' => array(
					'weight' => (float) $box['weight'],
					'length' => (float) $this->process_dimension( $box['length'] ),
					'width' => (float) $this->process_dimension( $box['width'] ),
					'height' => (float) $this->process_dimension( $box['height'] ),
					'mailClass' => 'ALL_OUTBOUND',
				),
			);
			if ( $response = $this->make_request( $endpoint, $body ) ) {
				$responses[] = $response;
			}
		}
		return $this->process_responses( $responses );
	}

	private function get_international_rates( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart ) {
		if ( '' == $destination_country ) {
			$destination_country = 'US';
		}
		$payment_account = null;
		if ( get_option( 'ec_option_usps_v3_custom_rates' ) ) {
			$price_type = ( get_option( 'ec_option_usps_v3_price_type' ) ) ? get_option( 'ec_option_usps_v3_price_type' ) : 'CONTRACT';
			$payment_account = array(
				'accountType' => ( get_option( 'ec_option_usps_v3_account_type' ) ) ? get_option( 'ec_option_usps_v3_account_type' ) : 'EPS',
				'accountNumber' => ( get_option( 'ec_option_usps_v3_account_number' ) ) ? get_option( 'ec_option_usps_v3_account_number' ) : '', // 10-digit EPS number
				'CRID' => ( get_option( 'ec_option_usps_v3_crid' ) ) ? get_option( 'ec_option_usps_v3_crid' ) : '', // Customer Registration ID
			);
		}
		$boxes = $this->create_boxes( $weight, $length, $width, $height, $declared_value, $cart );
		$responses = array();
		$endpoint = $this->base_url . '/international-prices/v3/total-rates/search';
		foreach ( $boxes as $box ) {
			$body = array(
				'originZIPCode' => $this->ship_from_zip,
				'foreignPostalCode' => $destination_zip,
				'destinationCountryCode' => $destination_country,
				'weight' => (float) $box['weight'],
				'length' => (float) $this->process_dimension( $box['length'] ),
				'width' => (float) $this->process_dimension( $box['width'] ),
				'height' => (float) $this->process_dimension( $box['height'] ),
				'mailClass' => 'ALL',
				'priceType' => 'RETAIL',
				//'mailingDate' => date('Y-m-d'),
				//'itemValue' => (float) $box['value'],
			);
			if ( $payment_account ) {
				$body['accountType'] = $payment_account['accountType'];
				$body['accountNumber'] = $payment_account['accountNumber'];
			}
			if ( $response = $this->make_request( $endpoint, $body ) ) {
				$responses[] = $response;
			}
		}
		return $this->process_responses( $responses );
	}

	private function make_request( $endpoint, $body ) {
		$db = new ec_db();
		try {
			$this->connect();
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $endpoint );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $body ) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer ' . $this->access_token,
				'Content-Type: application/json',
				'Accept: application/json'
			) );
			$response = curl_exec( $ch );
			$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			if ( curl_errno( $ch ) ) {
				$db->insert_response( 0, 1, "USPS V3 (cURL Error)", print_r( $body, true ) . ' --- ' . curl_error( $ch ) );
				return false;
			} else if ( $http_code !== 200 ) {
				$db->insert_response( 0, 1, "USPS V3 (http error)", print_r( $body, true ) . ' --- ' . print_r( $response, true ) );
				return false;
			} else {
				$db->insert_response( 0, 0, "USPS V3", print_r( $body, true ) . ' --- ' . print_r( $response, true ) );
			}
			curl_close( $ch );
			return json_decode( $response, true );
		} catch ( Exception $e ) {
			$db->insert_response( 0, 1, "USPS V3 (exception)", print_r( $body, true ) . ' --- ' . $e->getMessage() );
			return false;
		}
	}

	private function connect() {
		if ( get_option( 'ec_option_usps_v3_access_token' ) ) {
			$this->access_token = get_option( 'ec_option_usps_v3_access_token' );
			if ( '' == $this->access_token ) {
				$this->access_token = null;
			}
		}
		if ( get_option( 'ec_option_usps_v3_token_expires_at' ) ) {
			$this->token_expires_at = get_option( 'ec_option_usps_v3_token_expires_at' );
		}
		if ( $this->access_token && time() < $this->token_expires_at ) {
			return true;
		}
		if ( get_option( 'ec_option_usps_v3_custom' ) && '' != get_option( 'ec_option_usps_v3_client_id' ) && '' != get_option( 'ec_option_usps_v3_client_secret' ) ) {
			return $this->connect_custom();
		} else {
			return $this->connect_wpeasycart();
		}
	}

	private function connect_custom() {
		$db = new ec_db();
		$client_id = get_option( 'ec_option_usps_v3_client_id' );
		$client_secret = get_option( 'ec_option_usps_v3_client_secret' );

		$endpoint = 'https://apis.usps.com/oauth2/v3/token';
		$payload = array(
			'grant_type' => 'client_credentials',
			'client_id' => $client_id,
			'client_secret' => $client_secret,
		);
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $endpoint );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $payload ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/json'
		) );
		$response = curl_exec( $ch );
		$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		$data = json_decode( $response, true );

		if ( $http_code !== 200 || !isset( $data['access_token'] ) ) {
			$db->insert_response( 0, 1, "USPS V3 (oAuth CUSTOM error)", $response );
			return false;
		}

		if ( ! isset( $data['access_token'] ) ) {
			$db->insert_response( 0, 1, "USPS V3 (oAuth CUSTOM error)", $response );
			return false;
		}

		$this->access_token = $data['access_token'];
		$this->token_expires_at = time() + (int) $data['expires_in'] - 60;
		update_option( 'ec_option_usps_v3_access_token', $this->access_token );
		update_option( 'ec_option_usps_v3_token_expires_at', $this->token_expires_at );
	}

	private function connect_wpeasycart() {
		$db = new ec_db();
		$endpoint = 'https://connect.wpeasycart.com/usps-v3/?usps=WPEASYCARTPROUSPS';
		$ch = curl_init( $endpoint );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/json'
		) );
		$response = curl_exec( $ch );
		$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		$data = json_decode( $response, true );
		$db->insert_response( 0, 0, "USPS oAuth Response", $response . ' ---- ' . print_r( $data, true ) );

		if ( $http_code !== 200 || !isset( $data['access_token'] ) ) {
			$db->insert_response( 0, 1, "USPS V3 (oAuth error)", ( ( isset( $data['error_description'] ) ) ? $data['error_description'] : 'Unknown error' ) );
			return false;
		}
		$this->access_token = $data['access_token'];
		$this->token_expires_at = time() + (int) $data['expires_in'] - 60;
		update_option( 'ec_option_usps_v3_access_token', $this->access_token );
		update_option( 'ec_option_usps_v3_token_expires_at', $this->token_expires_at );
		return true;
	}

	private function process_dimension( $dimension ) {
		$dimension = ceil( $dimension );
		if ( $dimension <= 0 ) {
			$dimension = 1;
		}
		return $dimension;
	}

	private function create_boxes( $weight, $length, $width, $height, $declared_value, $cart ) {
		$MAX_WEIGHT = 70;
		$boxes = array();
		$items = array();
		if ( is_array( $cart ) && count( $cart ) > 0 ) {
			foreach ( $cart as $cartitem ) {
				$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
				for ( $i = 0; $i < $quantity; $i++ ) {
					if ( $cartitem->is_shippable && ! $cartitem->exclude_shippable_calculation ) {
						$product_dimensions = array( $cartitem->width, $cartitem->height, $cartitem->length );
						rsort( $product_dimensions, SORT_NUMERIC); // Sort $product_dimensions by highest to lowest
						$items[] = array( 
							'width' => $product_dimensions[1],
							'height' => $product_dimensions[2],
							'length' => $product_dimensions[0],
							'weight' => ( isset( $cartitem->total_weight ) ) ? $cartitem->total_weight / $quantity : $cartitem->weight,
							'value' => $cartitem->unit_price,
						);
					}
				}
			}

			usort( $items, function( $a, $b ) {
				return $b['weight'] > $a['weight'];
			} );

			foreach ( $items as $item ) {
				$packed = false;
				foreach ( $boxes as &$box ) {
					if ( ( $box['weight'] + $item['weight'] ) <= $MAX_WEIGHT ) {
						$box['value'] += $item['value'];
						$box['weight'] += $item['weight'];
						$box['height'] += $item['height'];
						if ( $box['width'] < $item['width'] ) {
							$box['width'] = $item['width'];
						}
						if ( $box['length'] < $item['length'] ) {
							$box['length'] = $item['length'];
						}
						$packed = true;
						break;
					}
				}

				if ( ! $packed ) {
					$boxes[] = array(
						'value' => $item['value'],
						'weight' => $item['weight'],
						'length' => max( $item['length'], 10 ),
						'width'  => max( $item['width'], 10 ),
						'height' => max( $item['height'], 5 ),
					);
				}
			}
		} else {
			$boxes[] = array(
				'value' => $declared_value,
				'weight' => $weight,
				'length' => max( $length, 10 ),
				'width'  => max( $width, 10 ),
				'height' => max( $height, 5 ),
			);
		}
		return $boxes;
	}

	private function process_responses( $responses ) {
		$conversion_rate = ( get_option( 'usps_conversion_rate' ) ) ? (float) get_option( 'usps_conversion_rate' ) : 1;
		$rates = array();
		foreach ( $responses as $response ) {
			if ( isset( $response['pricingOptions'] ) ) {
				foreach ( $response['pricingOptions'] as $pricing_option ) {
					if ( isset( $pricing_option['shippingOptions'] ) ) {
						foreach ( $pricing_option['shippingOptions'] as $shipping_option ) {
							$mail_class = $shipping_option['mailClass'];
							$best_rate_for_class = null;
							if ( isset( $shipping_option['rateOptions'] ) ) {
								foreach ( $shipping_option['rateOptions'] as $rate_option ) {
									if ( isset( $rate_option['rates'] ) ) {
										foreach ( $rate_option['rates'] as $rate ) {
											if ( strpos( $rate['description'], 'APO/FPO' ) !== false ) {
												continue;
											}
											$indicator = ( isset( $rate['rateIndicator'] ) ) ? $rate['rateIndicator'] : '';
											$is_standard_rate = empty( $indicator );
											$is_allowed_special = ( 'DN' == $indicator || 'DR' == $indicator || 'SP' == $indicator || 'CP' == $indicator || 'PA' == $indicator || 'NONSTANDARD' == $rate['processingCategory'] );

											if ( ! $is_standard_rate && ! $is_allowed_special ) {
												continue;
											}
											if ( is_null( $best_rate_for_class ) || $rate['price'] < $best_rate_for_class['price'] ) {
												$best_rate_for_class = array(
													'id' => $mail_class,
													'price' => $rate['price'],
												);
											}
										}
									}
								}
							}
							if ( $best_rate_for_class ) {
								if ( isset( $this->reverse_map[ $mail_class ] ) ) {
									$rates[ $this->reverse_map[ $mail_class ] ] = array(
										'rate_code' => $this->reverse_map[ $mail_class ],
										'rate' => (float) $best_rate_for_class['price'] * $conversion_rate,
									);
								}
							}
						}
					}
				}
			} else {
				$rates_list = ( isset( $response['rateOptions'] ) ) ? $response['rateOptions'] : array();
				if ( is_array( $rates_list ) && count( $rates_list ) > 0 ) {
					foreach ( $rates_list as $rate_option ) {
						$mail_class = '';
						if ( isset( $rate_option['rates'] ) && is_array( $rate_option['rates'] ) ) {
							foreach ( $rate_option['rates'] as $rate ) {
								if ( isset( $rate['mailClass'] ) ) {
									$mail_class = $rate['mailClass'];
								}
							}
						}
						if ( '' != $mail_class ) {
							if ( isset( $this->reverse_map[ $mail_class ] ) ) {
								$rates[ $this->reverse_map[ $mail_class ] ] = array(
									'rate_code' => $this->reverse_map[ $mail_class ],
									'rate' => (float) $rate_option['totalBasePrice'] * $conversion_rate,
								);
							}
						}
					}
				}
			}
		}
		return $rates;
	}

}
