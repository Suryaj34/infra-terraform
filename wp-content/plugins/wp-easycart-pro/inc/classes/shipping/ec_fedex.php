<?php
class ec_fedex{
	private $fedex_key;											// Your FedEx Account Key
	private $fedex_account_number;								// Your FedEx Account Number
	private $fedex_meter_number;								// Your FedEx Meter Number
	private $fedex_password;									// Your FedEx Password
	private $fedex_ship_from_zip;								// Your FedEx ship from zip code
	private $fedex_weight_units;								// The weight units to use for the FedEx api
	private $fedex_country_code;								// The country code for the FedEx api
	private $fedex_conversion_rate;								// A conversion rate option
	private $fedex_test_account;								// Is this is a FedEx test account
	
	private $shipper_url;										// String

	function __construct( $ec_setting ){
		$this->fedex_key = $ec_setting->get_fedex_key( );
		$this->fedex_account_number = $ec_setting->get_fedex_account_number();
		$this->fedex_meter_number = $ec_setting->get_fedex_meter_number();
		$this->fedex_password = $ec_setting->get_fedex_password();
		$this->fedex_ship_from_zip = $ec_setting->get_fedex_ship_from_zip();
		$this->fedex_weight_units = $ec_setting->get_fedex_weight_units();
		$this->fedex_country_code = $ec_setting->get_fedex_country_code();	
		$this->fedex_conversion_rate = $ec_setting->get_fedex_conversion_rate();
		$this->fedex_test_account = $ec_setting->get_fedex_test_account();
	}
	
	public function handle_token_auth() {
		if ( ! $this->fedex_test_account && get_option( 'ec_option_fedex_token' ) && '' != get_option( 'ec_option_fedex_token' ) && time() + 300 <  get_option( 'ec_option_fedex_token_expires' ) ) {
			return;
		}
		if ( $this->fedex_test_account && get_option( 'ec_option_fedex_token_sandbox' ) && '' != get_option( 'ec_option_fedex_token_sandbox' ) && time() + 300 <  get_option( 'ec_option_fedex_token_sandbox_expires' ) ) {
			return;
		}
		$this->create_token();
	}
	
	public function create_token() {
		$ch = curl_init();
		curl_setopt_array( $ch, array(
			CURLOPT_URL => $this->get_endpoint_url() . 'oauth/token',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=' . esc_attr( get_option( 'ec_option_fedex_api_key' ) ) . '&client_secret=' . esc_attr( get_option( 'ec_option_fedex_api_secret_key' ) ),
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/x-www-form-urlencoded'
			),
		) );
		$response = curl_exec( $ch );
		curl_close( $ch );
		
		$db = new ec_db( );
		$db->insert_response( 0, 0, "FedEx Authorization", print_r( $response, true ) );
		
		if ( $response ) {
			$response_json = json_decode( $response );
			if ( $response_json && isset( $response_json->access_token ) ) {
				( $this->fedex_test_account ) ? update_option( 'ec_option_fedex_token_sandbox', $response_json->access_token ) : update_option( 'ec_option_fedex_token', $response_json->access_token );
			}
			if ( $response_json && isset( $response_json->expires_in ) ) {
				( $this->fedex_test_account ) ? update_option( 'ec_option_fedex_token_sandbox_expires', time() + $response_json->expires_in ) : update_option( 'ec_option_fedex_token_expires', time() + $response_json->expires_in );
			}
		}
	}
	
	private function get_endpoint_url() {
		return ( $this->fedex_test_account ) ? 'https://apis-sandbox.fedex.com/' : 'https://apis.fedex.com/';
	}

	private function get_oauth_headers() {
		$access_token = ( ! $this->fedex_test_account ) ? get_option( 'ec_option_fedex_token' ) : get_option( 'ec_option_fedex_token_sandbox' );
		$headr = array();
		$headr[] = 'Authorization: Bearer ' . esc_attr( $access_token );
		$headr[] = 'Content-Type: application/json';
		return $headr;
	}

	private function get_oauth_response( $request_url, $headr, $is_post, $data, $is_json = true ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $request_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_ENCODING, '' );
		curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, (int) 10);
		curl_setopt( $ch, CURLOPT_POST, $is_post );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
		if ( $data ) {
			if ( $is_json ) {
				curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $data ) );
			} else {
				curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data, '', '&amp;' ) );
			}
		}
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );
		$response = curl_exec( $ch );
		curl_close( $ch );
		return $response;
	}

	private function get_rate_data( $cart, $ship_code, $destination_zip = false, $destination_country = false ) {
		$address_type = 1;
		if ( get_option( 'ec_option_fedex_use_check_address_type' ) ) {
			if ( $destination_zip && $destination_country ) {
				$address_type = $this->get_address_type( '', '', '', $destination_zip, $destination_country );
			} else {
				$address_type = $this->get_address_type( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, $GLOBALS['ec_cart_data']->cart_data->shipping_city, $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_zip, $GLOBALS['ec_cart_data']->cart_data->shipping_country );
			}
		}
		if ( $destination_zip && $destination_country ) {
			$recipient_address = (object) array(
				'address' => (object) array(
					'postalCode' => $destination_zip,
					'countryCode' => $destination_country,
				),
			);
		} else {
			$destination_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
			if ( 'GB' == $GLOBALS['ec_cart_data']->cart_data->shipping_country ) {
				$destination_state = $this->convert_uk_county( $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_city );
			}
			if ( '' != $destination_state ) {
				$recipient_address = (object) array(
					'address' => (object) array(
						'city' => $GLOBALS['ec_cart_data']->cart_data->shipping_city,
						'stateOrProvinceCode' => $destination_state,
						'postalCode' => $GLOBALS['ec_cart_data']->cart_data->shipping_zip,
						'countryCode' => $GLOBALS['ec_cart_data']->cart_data->shipping_country,
						'residential' => $address_type
					),
				);
			} else {
				$recipient_address = (object) array(
					'address' => (object) array(
						'city' => $GLOBALS['ec_cart_data']->cart_data->shipping_city,
						'postalCode' => $GLOBALS['ec_cart_data']->cart_data->shipping_zip,
						'countryCode' => $GLOBALS['ec_cart_data']->cart_data->shipping_country,
						'residential' => $address_type
					),
				);
			}
		}
		$dimensions_units = 'IN';
		if ( get_option( 'ec_option_enable_metric_unit_display' ) ) {
			$dimensions_units = 'CM';
		}
		$data = (object) array(
			'accountNumber' => (object) array(
				'value' => $this->fedex_account_number,
			),
			'rateRequestControlParameters' => (object) array(
				'returnTransitTimes' => false,
				'servicesNeededOnRateFailure' => true,
				'variableOptions' => 'FREIGHT_GUARANTEE',
				'rateSortOrder' => 'SERVICENAMETRADITIONAL',
			),
			'requestedShipment' => (object) array(
				'shipper' => (object) array(
					'address' => (object) array(
						'postalCode' => $this->fedex_ship_from_zip,
						'countryCode' => $this->fedex_country_code,
					),
				),
				'recipient' => $recipient_address,
				'preferredCurrency'	=> str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
				'rateRequestType' => array( 'ACCOUNT' ),
				'pickupType' => 'DROPOFF_AT_FEDEX_LOCATION',
				'requestedPackageLineItems' => array(),
				'totalPackageCount' => 1,
				'totalWeight' => 0,
			),
		);
		if ( $ship_code ) {
			$data->requestedShipment->serviceType = $ship_code;
		}
		$cart = apply_filters( 'wpeasycart_fedex_cart_item_list', $cart );
		if ( get_option( 'ec_option_ship_items_seperately' ) && count( $cart ) > 0 ) { // Each Item Separate
			for ( $i = 0; $i < count( $cart ); $i++ ) {
				if ( $cart[$i]->is_shippable && ! $cart[$i]->exclude_shippable_calculation ) {
					$quantity = ( isset( $cart[$i]->quantity ) ) ? $cart[$i]->quantity : 1;
					for ( $j = 0; $j < $quantity; $j++ ) {
						$packageLineItem = (object) array(
							'declaredValue' => (object) array(
								'amount' => number_format( ( ( isset( $cart[$i]->unit_price ) ) ? $cart[$i]->unit_price : $cart[$i]->price ), 2, '.', '' ),
								'currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
							),
							'weight' => (object) array(
								'units' => $this->fedex_weight_units,
								'value' => ( $cart[$i]->weight > 0 ) ? $cart[$i]->weight : (1/16), // min 1 ounce
							),
							'dimensions' => (object) array(
								'length' => ( (int) $cart[$i]->length < 1 ) ? 1 : (int) $cart[$i]->length,
								'width' => ( (int) $cart[$i]->width < 1 ) ? 1 : (int) $cart[$i]->width,
								'height' => ( (int) $cart[$i]->height < 1 ) ? 1 : (int) $cart[$i]->height,
								'units' => $dimensions_units,
							),
						);
						$data->requestedShipment->requestedPackageLineItems[] = $packageLineItem;
						$data->requestedShipment->totalPackageCount++;
						$data->requestedShipment->totalWeight += ( ( $cart[$i]->weight > 0 ) ? $cart[$i]->weight : (1/16) );
					}// close quantity loop
				}// close is shippable check
			}// close cart item loop
		} else {
			$current_weight = 0;
			$products = array( );
			foreach ( $cart as $cartitem ) {
				if ( isset( $cartitem->is_shippable ) && $cartitem->is_shippable && ( ! isset( $cartitem->exclude_shippable_calculation ) || ! $cartitem->exclude_shippable_calculation ) ) {
					// Each quantity item is a new product in the shipping world
					$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
					for ( $i = 0; $i < $quantity; $i++ ) {
						$products[] = array(
							'width' => ( $cartitem->width < 0 ) ? .01 : $cartitem->width,
							'height' => ( $cartitem->height < 0 ) ? .01 : $cartitem->height,
							'length' => ( $cartitem->length < 0 ) ? .01 : $cartitem->length,
							'weight' => $cartitem->weight,
							'price' => ( ( isset( $cartitem->unit_price ) ) ? $cartitem->unit_price : $cartitem->price ),
						);
						$current_weight += $cartitem->weight;
						$parcel = $this->calculate_parcel( $products );
						if ( $current_weight > 150 || $parcel['width'] > 108 || ( $parcel['width']*2 + $parcel['height']*2 + $parcel['length'] ) > 165 ) {
							$packageLineItem = (object) array(
								'declaredValue' => (object) array(
									'amount' => number_format( $parcel['price'], 2, '.', '' ),
									'currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
								),
								'weight' => (object) array(
									'units' => $this->fedex_weight_units,
									'value' => $parcel['weight'],
								),
								'dimensions' => (object) array(
									'length' => ( (int) $parcel['length'] < 1 ) ? 1 : (int) $parcel['length'],
									'width' => ( (int) $parcel['width'] < 1 ) ? 1 : (int) $parcel['width'],
									'height' => ( (int) $parcel['height'] < 1 ) ? 1 : (int) $parcel['height'],
									'units' => $dimensions_units,
								),
							);
							$data->requestedShipment->requestedPackageLineItems[] = $packageLineItem;
							$data->requestedShipment->totalPackageCount++;
							$data->requestedShipment->totalWeight += $parcel['weight'];
							$current_weight = 0;
							$products = array();
						}
					}// close quantity loop
				}// close shippable check
			}// close cart item loop

			// Maybe add last package
			if( count( $products ) > 0 ){
				$parcel = $this->calculate_parcel( $products );
				$packageLineItem = (object) array(
					'declaredValue' => (object) array(
						'amount' => number_format( $parcel['price'], 2, '.', '' ),
						'currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
					),
					'weight' => (object) array(
						'units' => $this->fedex_weight_units,
						'value' => ( $parcel['weight'] > 0 ) ? $parcel['weight'] : (1/16), // min 1 ounce, 
					),
					'dimensions' => (object) array(
						'length' => ( (int) $parcel['length'] < 1 ) ? 1 : (int) $parcel['length'],
						'width' => ( (int) $parcel['width'] < 1 ) ? 1 : (int) $parcel['width'],
						'height' => ( (int) $parcel['height'] < 1 ) ? 1 : (int) $parcel['height'],
						'units' => $dimensions_units,
					),
				);
				$data->requestedShipment->requestedPackageLineItems[] = $packageLineItem;
				$data->requestedShipment->totalPackageCount++;
				$data->requestedShipment->totalWeight += ( ( $parcel['weight'] > 0 ) ? $parcel['weight'] : (1/16) );
			}
		}
		return $data;
	}

	public function get_rate_v2( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){
		$service_type = strtoupper( $ship_code );
		$service_types = array(
			"EUROPE_FIRST_INTERNATIONAL_PRIORITY", 
			"FEDEX_1_DAY_FREIGHT", 
			"FEDEX_2_DAY", 
			"FEDEX_2_DAY_AM", 
			"FEDEX_2_DAY_FREIGHT", 
			"FEDEX_3_DAY_FREIGHT",
			"FEDEX_EXPRESS_SAVER",
			"FEDEX_FIRST_FREIGHT",
			"FEDEX_FREIGHT_ECONOMY",
			"FEDEX_FREIGHT_PRIORITY",
			"FEDEX_GROUND",
			"FIRST_OVERNIGHT",
			"GROUND_HOME_DELIVERY",
			"INTERNATIONAL_ECONOMY",
			"INTERNATIONAL_ECONOMY_FREIGHT",
			"INTERNATIONAL_FIRST",
			"INTERNATIONAL_PRIORITY",
			"INTERNATIONAL_PRIORITY_FREIGHT",
			"PRIORITY_OVERNIGHT",
			"SMART_POST",
			"STANDARD_OVERNIGHT",
		);
		
		if( in_array( $service_type, $service_types ) ){
			$this->handle_token_auth();
			$request_url = $this->get_endpoint_url() . 'rate/v1/rates/quotes';
			$request_headr = $this->get_oauth_headers();
			$request_data = $this->get_rate_data( $cart, $service_type, $destination_zip, $destination_country );
			$response = $this->get_oauth_response( $request_url, $request_headr, 1, $request_data );
			
			$db = new ec_db( );
			$db->insert_response( 0, 0, "FedEx Get Rate V2", print_r( $response, true ) );
			
			$rates = $this->get_processed_rates( $response );
			if ( is_array( $rates ) && count( $rates ) > 0 ) {
				return $rates[0]['rate'];
			}
		}
		return 'ERROR';
	}

	public function get_rate( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){
		if ( get_option( 'ec_option_fedex_use_oauth' ) ) {
			return $this->get_rate_v2( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}
		$address_type = 1;
		if( get_option( 'ec_option_fedex_use_check_address_type' ) ) {
			$address_type = $this->get_address_type( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, $GLOBALS['ec_cart_data']->cart_data->shipping_city, $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_zip, $GLOBALS['ec_cart_data']->cart_data->shipping_country );
		}
		
		if( $weight == 0 )
			$weight = 1/16; // Min 1 ounce
		
		$dimensions_units = 'IN';
		if( get_option( 'ec_option_enable_metric_unit_display' ) )
			$dimensions_units = 'CM';
			
		if( !$destination_country )
			$destination_country = $this->fedex_country_code;
			
		if( !$destination_zip || $destination_zip == "" )
			$destination_zip = $this->fedex_ship_from_zip;
		
		$service_type = strtoupper( $ship_code );
		
		$service_types = array( 	"EUROPE_FIRST_INTERNATIONAL_PRIORITY", 
									"FEDEX_1_DAY_FREIGHT", 
									"FEDEX_2_DAY", 
									"FEDEX_2_DAY_AM", 
									"FEDEX_2_DAY_FREIGHT", 
									"FEDEX_3_DAY_FREIGHT",
									"FEDEX_EXPRESS_SAVER",
									"FEDEX_FIRST_FREIGHT",
									"FEDEX_FREIGHT_ECONOMY",
									"FEDEX_FREIGHT_PRIORITY",
									"FEDEX_GROUND",
									"FIRST_OVERNIGHT",
									"GROUND_HOME_DELIVERY",
									"INTERNATIONAL_ECONOMY",
									"INTERNATIONAL_ECONOMY_FREIGHT",
									"INTERNATIONAL_FIRST",
									"INTERNATIONAL_PRIORITY",
									"INTERNATIONAL_PRIORITY_FREIGHT",
									"PRIORITY_OVERNIGHT",
									"SMART_POST",
									"STANDARD_OVERNIGHT" );
		
		if( in_array( $service_type, $service_types ) ){
			
			if( $this->fedex_test_account ){
				$path_to_wsdl = dirname(__FILE__) . "/fedex_rate_service_v16_test_account.wsdl";
			}else{
				$path_to_wsdl = dirname(__FILE__) . "/fedex_rate_service_v16.wsdl";
			}
	
			ini_set("soap.wsdl_cache_enabled", "0");
			 
			$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
			
			$request['WebAuthenticationDetail'] = array(
				'UserCredential' =>array(
					'Key' => $this->fedex_key, 
					'Password' => $this->fedex_password
				)
			); 
			$request['ClientDetail'] = array(
				'AccountNumber' => $this->fedex_account_number, 
				'MeterNumber' => $this->fedex_meter_number
			);
			$request['TransactionDetail'] = array( 'CustomerTransactionId' => ' *** Rate Request v16 using PHP ***' );
			$request['Version'] = array(
				'ServiceId' => 'crs', 
				'Major' => '16', 
				'Intermediate' => '0', 
				'Minor' => '0'
			);
			$request['ReturnTransitAndCommit'] = true;
			$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
			$request['RequestedShipment']['ShipTimestamp'] = date( 'c' );
			$request['RequestedShipment']['ServiceType'] = $service_type; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
			$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
			
			$shipper = array( 'Address' => array( 'PostalCode' => $this->fedex_ship_from_zip, 'CountryCode' => $this->fedex_country_code ) );
			$request['RequestedShipment']['Shipper'] = $shipper;
			
			$recipient = array( 
				'Address' => array( 
					'PostalCode' => $destination_zip,
					'CountryCode' => $destination_country,
					'Residential' 	=> $address_type,
				)
			);
			$request['RequestedShipment']['Recipient'] = $recipient;
			
			$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
			$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
			$cart = apply_filters( 'wpeasycart_fedex_cart_item_list', $cart );
			if( get_option( 'ec_option_ship_items_seperately' ) && count( $cart ) > 0 ){ // Each Item Separate
				$total_items = 0;
				$request['RequestedShipment']['RequestedPackageLineItems'] = array( );
				for ( $i = 0; $i < count( $cart ); $i++ ) {
					if ( $cart[$i]->is_shippable && ! $cart[$i]->exclude_shippable_calculation ) {
						$quantity = ( isset( $cart[$i]->quantity ) ) ? $cart[$i]->quantity : 1;
						for ( $j = 0; $j < $quantity; $j++ ) {
							$total_items++;
							$packageLineItem = array(
								'SequenceNumber' 	=> $total_items,
								'GroupPackageCount'	=> 1,
								'Dimensions' => array(
									'Length' => ( $cart[$i]->length < 1 ) ? 1 : $cart[$i]->length,
									'Width' => ( $cart[$i]->width < 1 ) ? 1 : $cart[$i]->width,
									'Height' => ( $cart[$i]->height < 1 ) ? 1 : $cart[$i]->height,
									'Units' => $dimensions_units,
								),
								'Weight' => array( 
									'Value' => ( $cart[$i]->weight > 0 ) ? $cart[$i]->weight : (1/16), // min 1 ounce 
									'Units' => $this->fedex_weight_units,
								),
								'InsuredValue' => array(
									'Currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
									'Amount' => number_format( ( ( isset( $cart[$i]->unit_price ) ) ? $cart[$i]->unit_price : $cart[$i]->price ), 2, '.', '' ),
								),
							);
							$request['RequestedShipment']['RequestedPackageLineItems'][] = $packageLineItem;
						}// close quantity loop
					}// close is shippable check
				}// close cart item loop
				$request['RequestedShipment']['PackageCount'] = $total_items;
			
			}else{
				$total_items = 0;
				
				// Generate Product List
				$current_weight = 0;
				$products = array( );
				foreach( $cart as $cartitem ){
					if( $cartitem->is_shippable && !$cartitem->exclude_shippable_calculation ){
						// Each quantity item is a new product in the shipping world
						$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
						for ( $i = 0; $i < $quantity; $i++ ) {
							// Add the new product
							$products[] = array(
								'width' => ( $cartitem->width < 0 ) ? .01 : $cartitem->width,
								'height' => ( $cartitem->height < 0 ) ? .01 : $cartitem->height,
								'length' => ( $cartitem->length < 0 ) ? .01 : $cartitem->length,
								'weight' => $cartitem->weight,
								'price' => ( ( isset( $cartitem->unit_price ) ) ? $cartitem->unit_price : $cartitem->price ),
							);
							$current_weight += $cartitem->weight;
							$parcel = $this->calculate_parcel( $products );

							if ( $current_weight > 150 || $parcel['width'] > 108 || ( $parcel['width']*2 + $parcel['height']*2 + $parcel['length'] ) > 165 ) {
								$total_items++;
								$packageLineItem = array(
									'SequenceNumber' => $total_items,
									'GroupPackageCount' => 1,
									'Dimensions' => array(
										'Length' => ( $parcel['length'] < 1 ) ? 1 : $parcel['length'],
										'Width' => ( $parcel['width'] < 1 ) ? 1 : $parcel['width'],
										'Height' => ( $parcel['height'] < 1 ) ? 1 : $parcel['height'],
										'Units' => $dimensions_units,
									),
									'Weight' => array( 
										'Value' => $parcel['weight'],
										'Units' => $this->fedex_weight_units,
									),
									'InsuredValue' => array(
										'Currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
										'Amount' => number_format( $parcel['price'], 2, '.', '' ),
									),
								);
								$request['RequestedShipment']['RequestedPackageLineItems'][] = $packageLineItem;
								$current_weight = 0;
								$products = array( );
							}
						}// close quantity loop
					}// close shippable check
				}// close cart item loop
				
				// Maybe add last package
				if ( count( $products ) > 0 ) {
					$total_items++;
					$parcel = $this->calculate_parcel( $products );
					$packageLineItem 	= array(
						'SequenceNumber' => $total_items, 
						'GroupPackageCount' => 1, 
						'Dimensions' => array(
							'Length' => ( $parcel['length'] < 1 ) ? 1 : $parcel['length'],
							'Width' => ( $parcel['width'] < 1 ) ? 1 : $parcel['width'],
							'Height' => ( $parcel['height'] < 1 ) ? 1 : $parcel['height'],
							'Units' => $dimensions_units,
						),
						'Weight' => array( 
							'Value' => ( $parcel['weight'] > 0 ) ? $parcel['weight'] : (1/16), // min 1 ounce, 
							'Units' => $this->fedex_weight_units,
						),
						'InsuredValue' => array(
							'Currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
							'Amount' => number_format( $parcel['price'], 2, '.', '' ),
						),
					);
					$request['RequestedShipment']['RequestedPackageLineItems'][] = $packageLineItem;
				}
				$request['RequestedShipment']['PackageCount'] = $total_items;
			}

			try {
				$response = $client->getRates( $request );
				$db = new ec_db();
				$db->insert_response( 0, 0, "FedEx Get Rate V1", print_r( $request, true ) . '------' . print_r( $response, true ) );
					
				if ( $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' ) {
					$rateReply = $response->RateReplyDetails;
					$serviceType = $rateReply->ServiceType;
					$payor_account_package = 0.000;
					$rated_account_package = 0.000;
					$payor_list_package = 0.000;
					$rated_list_package = 0.000;
					$rate_other = 0.000;

					if ( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_ACCOUNT_PACKAGE" ) {
						$payor_account_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
					} else if ( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_ACCOUNT_PACKAGE" ) {
						$rated_account_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
					} else if ( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_LIST_PACKAGE" ) {
						$payor_list_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
					} else if ( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_LIST_PACKAGE" ) {
						$rated_list_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
					} else {
						$rate_other = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
					}

					if ( $payor_account_package > 0 ) {
						$rate = $payor_account_package;
					} else if ( $rated_account_package > 0 ) {
						$rate = $rated_account_package;
					} else if ( $payor_list_package > 0 ) {
						$rate = $payor_list_package;
					} else if ( $rated_list_package > 0 ) {
						$rate = $rated_list_package;
					} else {
						$rate = $rate_other;
					}

					$rate_discount = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalFreightDiscounts->Amount * $this->fedex_conversion_rate, 2, ".", "" );
					
					if ( ! get_option( 'ec_option_fedex_use_net_charge' ) ) {
						$rate = $rate + $rate_discount;
					}
					return $rate;
				} else {
					$db = new ec_db();
					$db->insert_response( 0, 0, "FedEx Get Rate V1", "error in fedex get rate, " . $this->printFault($exception, $client) );
					return "ERROR";
				}
			} catch ( SoapFault $exception ) {
				$db = new ec_db( );
				$db->insert_response( 0, 0, "FedEx Get Rate V1", "error in fedex get rate, " . $this->printFault($exception, $client) );
				return "ERROR";
			}
		}
	}

	public function get_all_rates_v2( $destination_zip, $destination_country, $weight, $length = 10, $width = 10, $height = 10, $declared_value = 0, $cart = array() ) {
		$this->handle_token_auth();
		$request_url = $this->get_endpoint_url() . 'rate/v1/rates/quotes';
		$request_headr = $this->get_oauth_headers();
		$request_data = $this->get_rate_data( $cart, false );
		$response_data = $this->get_oauth_response( $request_url, $request_headr, 1, $request_data );
		$response = json_decode( $response_data );
		$db = new ec_db( );
		if ( ! $response || ! isset( $response->output ) || ! isset( $response->output->rateReplyDetails ) || ! is_array( $response->output->rateReplyDetails ) ) {
			$db->insert_response( 0, 0, "FedEx Get All Rates V2 Error", print_r( $response, true ) );
			return "ERROR";
		}
		$rates = $this->get_processed_rates( $response );
		$db->insert_response( 0, 0, "FedEx Get All Rates V2", print_r( $rates, true ) . ' ----- ' . print_r( $response, true ) );
		return $rates;
	}

	public function get_all_rates( $destination_zip, $destination_country, $weight, $length = 10, $width = 10, $height = 10, $declared_value = 0, $cart = array() ) {
		if ( get_option( 'ec_option_fedex_use_oauth' ) ) {
			return $this->get_all_rates_v2( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}
		$address_type = 1;
		if ( get_option( 'ec_option_fedex_use_check_address_type' ) ) {
			$address_type = $this->get_address_type( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, $GLOBALS['ec_cart_data']->cart_data->shipping_city, $GLOBALS['ec_cart_data']->cart_data->shipping_state, $GLOBALS['ec_cart_data']->cart_data->shipping_zip, $GLOBALS['ec_cart_data']->cart_data->shipping_country );
		}

		if ( $weight == 0 ) {
			$weight = 1/16;
		}

		$dimensions_units = 'IN';
		if ( get_option( 'ec_option_enable_metric_unit_display' ) ) {
			$dimensions_units = 'CM';
		}

		if ( ! $destination_country ) {
			$destination_country = $this->fedex_country_code;
		}

		if ( ! $destination_zip || $destination_zip == "" ) {
			$destination_zip = $this->fedex_ship_from_zip;
		}

		if ( $this->fedex_test_account ) {
			$path_to_wsdl = dirname(__FILE__) . "/fedex_rate_service_v16_test_account.wsdl";
		} else {
			$path_to_wsdl = dirname(__FILE__) . "/fedex_rate_service_v16.wsdl";
		}

		ini_set("soap.wsdl_cache_enabled", "0");
		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' =>array(
				'Key' => $this->fedex_key,
				'Password' => $this->fedex_password,
			)
		); 
		$request['ClientDetail'] = array(
			'AccountNumber' => $this->fedex_account_number,
			'MeterNumber' => $this->fedex_meter_number,
		);
		$request['TransactionDetail'] = array( 'CustomerTransactionId' => ' *** Rate Request v16 using PHP ***' );
		$request['Version'] = array(
			'ServiceId' => 'crs',
			'Major' => '16',
			'Intermediate' => '0',
			'Minor' => '0',
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date( 'c' );
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...

		$shipper = array( 'Address' => array( 'PostalCode' => $this->fedex_ship_from_zip, 'CountryCode' => $this->fedex_country_code ) );
		$request['RequestedShipment']['Shipper'] = $shipper;

		$recipient = array( 
			'Address' => array( 
				'PostalCode' 	=> $destination_zip,
				'CountryCode' 	=> $destination_country,
				'Residential' 	=> $address_type,
			)
		);
		$request['RequestedShipment']['Recipient'] = $recipient;
		
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
		
		$cart = apply_filters( 'wpeasycart_fedex_cart_item_list', $cart );
		if ( get_option( 'ec_option_ship_items_seperately' ) && count( $cart ) > 0 ) {
			$total_items = 0;
			$request['RequestedShipment']['RequestedPackageLineItems'] = array( );
			for ( $i = 0; $i < count( $cart ); $i++ ) {
				if ( $cart[$i]->is_shippable && !$cart[$i]->exclude_shippable_calculation ) {
					$quantity = ( isset( $cart[$i]->quantity ) ) ? $cart[$i]->quantity : 1;
					for ( $j = 0; $j < $quantity; $j++ ) {
						$total_items++;
						$packageLineItem = array(
							'SequenceNumber' => $total_items,
							'GroupPackageCount' => 1,
							'Dimensions' => array(
								'Length' => ( $cart[$i]->length < 1 ) ? 1 : $cart[$i]->length,
								'Width' => ( $cart[$i]->width < 1 ) ? 1 : $cart[$i]->width,
								'Height' => ( $cart[$i]->height < 1 ) ? 1 : $cart[$i]->height,
								'Units' => $dimensions_units,
							),
							'Weight' => array(
								'Value' => ( $cart[$i]->weight > 0 ) ? $cart[$i]->weight : (1/16), // min 1 ounce
								'Units' => $this->fedex_weight_units,
							),
							'InsuredValue' => array(
								'Currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
								'Amount' => number_format( ( ( isset( $cart[$i]->unit_price ) ) ? $cart[$i]->unit_price : $cart[$i]->price ), 2, '.', '' ),
							),
						);
						$request['RequestedShipment']['RequestedPackageLineItems'][] = $packageLineItem;
					}// close quantity loop
				}// close is shippable check
			}// close cart items loop
			$request['RequestedShipment']['PackageCount'] = $total_items;
		} else {
			$total_items = 0;
			// Generate Product List
			$current_weight = 0;
			$products = array( );
			foreach ( $cart as $cartitem ) {
				if ( $cartitem->is_shippable && !$cartitem->exclude_shippable_calculation ) {
					// Each quantity item is a new product in the shipping world
					$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
					for ( $i = 0; $i < $quantity; $i++ ) {
						// Add the new product
						$products[] = array(
							'width' => ( $cartitem->width < 0 ) ? .01 : $cartitem->width,
							'height' => ( $cartitem->height < 0 ) ? .01 : $cartitem->height,
							'length' => ( $cartitem->length < 0 ) ? .01 : $cartitem->length,
							'weight' => $cartitem->weight,
							'price' => ( ( isset( $cartitem->unit_price ) ) ? $cartitem->unit_price : $cartitem->price ),
						);
						$current_weight += $cartitem->weight;
						$parcel = $this->calculate_parcel( $products );

						if ( $current_weight > 150 || $parcel['width'] > 108 || ( $parcel['width']*2 + $parcel['height']*2 + $parcel['length'] ) > 165 ) {
							$total_items++;
							$packageLineItem = array(
								'SequenceNumber' => $total_items, 
								'GroupPackageCount' => 1, 
								'Dimensions' => array(
									'Length' => ( $parcel['length'] < 1 ) ? 1 : $parcel['length'],
									'Width' => ( $parcel['width'] < 1 ) ? 1 : $parcel['width'],
									'Height' => ( $parcel['height'] < 1 ) ? 1 : $parcel['height'],
									'Units' => $dimensions_units,
								),
								'Weight' => array( 
									'Value' => $parcel['weight'],
									'Units' => $this->fedex_weight_units,
								),
								'InsuredValue' => array(
									'Currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
									'Amount' => number_format( $parcel['price'], 2, '.', '' ),
								),
							);
							$request['RequestedShipment']['RequestedPackageLineItems'][] = $packageLineItem;
							$current_weight = 0;
							$products = array();
						}
					}// close quantity loop
				}// close is shippable check
			}// close cart item loop

			// Maybe add last package
			if ( count( $products ) > 0 ) {
				$parcel = $this->calculate_parcel( $products );
				$total_items++;
				$packageLineItem = array(
					'SequenceNumber' => $total_items,
					'GroupPackageCount' => 1,
					'Dimensions' => array(
						'Length' => ( $parcel['length'] < 1 ) ? 1 : $parcel['length'],
						'Width' => ( $parcel['width'] < 1 ) ? 1 : $parcel['width'],
						'Height' => ( $parcel['height'] < 1 ) ? 1 : $parcel['height'],
						'Units' => $dimensions_units,
					),
					'Weight' => array(
						'Value' => ( $parcel['weight'] > 0 ) ? $parcel['weight'] : (1/16), // min 1 ounce
						'Units' => $this->fedex_weight_units,
					),
					'InsuredValue' => array(
						'Currency' => str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
						'Amount' => number_format( $parcel['price'], 2, '.', '' ),
					),
				);
				$request['RequestedShipment']['RequestedPackageLineItems'][] = $packageLineItem;
			}
			$request['RequestedShipment']['PackageCount'] = $total_items;
		}

		try {
			$response = $client->getRates( $request );
			$db = new ec_db();
			$db->insert_response( 0, 0, "FedEx Get All Rates V1", print_r( $request, true ) . '------' . print_r( $response, true ) );
			if ( $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' ) {
				if ( $response->HighestSeverity == 'WARNING' && $response->Notifications->Code == 556 ) {
					return "ERROR";
				}
				$rates = array();
				// If only 1 result, NOT returned as array...
				if ( is_array( $response->RateReplyDetails ) ) {
					for ( $i=0; $i<count( $response->RateReplyDetails ); $i++ ) {
						$code = $response->RateReplyDetails[$i]->ServiceType;
						$rate = 0.000;
						$payor_account_package = 0.000;
						$rated_account_package = 0.000;
						$payor_list_package = 0.000;
						$rated_list_package = 0.000;
						$rate_other = 0.000;

						if ( isset( $response->RateReplyDetails[$i]->RatedShipmentDetails ) && is_array( $response->RateReplyDetails[$i]->RatedShipmentDetails ) ) {
							if( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_ACCOUNT_PACKAGE" ){
								$payor_account_package = number_format( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
							}else if( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_ACCOUNT_PACKAGE" ){
								$rated_account_package = number_format( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
							}else if( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_LIST_PACKAGE" ){
								$payor_list_package = number_format( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
							}else if( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_LIST_PACKAGE" ){
								$rated_list_package = number_format( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
							}else{
								$rate_other = number_format( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
							}
						}

						if ( $payor_account_package > 0 ) {
							$rate = $payor_account_package;
						} else if ( $rated_account_package > 0 ) {
							$rate = $rated_account_package;
						} else if ( $payor_list_package > 0 ) {
							$rate = $payor_list_package;
						} else if ( $rated_list_package > 0 ) {
							$rate = $rated_list_package;
						} else {
							$rate = $rate_other;
						}

						$rate_discount = number_format( $response->RateReplyDetails[$i]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalFreightDiscounts->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						if ( ! get_option( 'ec_option_fedex_use_net_charge' ) ) {
							$rate = $rate + $rate_discount;
						}
						$rates[] = array( 'rate_code' => $code, 'rate' => $rate );
					}
				} else {
					$code = $response->RateReplyDetails->ServiceType;
					$rate = 0.000;
					$payor_account_package = 0.000;
					$rated_account_package = 0.000;
					$payor_list_package = 0.000;
					$rated_list_package = 0.000;
					$rate_other = 0.000;

					if(
						isset( $response ) && 
						isset( $response->RateReplyDetails ) && 
						is_array( $response->RateReplyDetails ) &&
						isset( $response->RateReplyDetails[0] ) && 
						isset( $response->RateReplyDetails[0]->RatedShipmentDetails ) &&  
						isset( $response->RateReplyDetails[0]->RatedShipmentDetails[0] ) && 
						isset( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail ) && 
						isset( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType )
					) {
						if( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_ACCOUNT_PACKAGE" ){
							$payor_account_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						}else if( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_ACCOUNT_PACKAGE" ){
							$rated_account_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						}else if( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_LIST_PACKAGE" ){
							$payor_list_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						}else if( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_LIST_PACKAGE" ){
							$rated_list_package = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						}else{
							$rate_other = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						}
						
						if( $payor_account_package > 0 ){
							$rate = $payor_account_package;
						}else if( $rated_account_package > 0 ){
							$rate = $rated_account_package;
						}else if( $payor_list_package > 0 ){
							$rate = $payor_list_package;
						}else if( $rated_list_package > 0 ){
							$rate = $rated_list_package;
						}else {
							$rate = $rate_other;
						}
						
						$rate_discount = number_format( $response->RateReplyDetails[0]->RatedShipmentDetails[0]->ShipmentRateDetail->TotalFreightDiscounts->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						
						if( !get_option( 'ec_option_fedex_use_net_charge' ) ){
							$rate = $rate + $rate_discount;
						}
						
						$rates[] = array( 'rate_code' => $code, 'rate' => $rate );
					} else if (
						isset( $response ) && 
						isset( $response->RateReplyDetails ) && 
						isset( $response->RateReplyDetails->RatedShipmentDetails ) &&  
						isset( $response->RateReplyDetails->RatedShipmentDetails[0] ) && 
						isset( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail ) && 
						isset( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->RateType )
					) {
						if ( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_ACCOUNT_PACKAGE" ) {
							$payor_account_package = number_format( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						} else if ( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_ACCOUNT_PACKAGE" ) {
							$rated_account_package = number_format( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						} else if ( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "PAYOR_LIST_PACKAGE" ) {
							$payor_list_package = number_format( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						} else if ( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->RateType == "RATED_LIST_PACKAGE" ) {
							$rated_list_package = number_format( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						} else {
							$rate_other = number_format( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						}

						if ( $payor_account_package > 0 ) {
							$rate = $payor_account_package;
						} else if ( $rated_account_package > 0 ) {
							$rate = $rated_account_package;
						} else if ( $payor_list_package > 0 ) {
							$rate = $payor_list_package;
						} else if ( $rated_list_package > 0 ) {
							$rate = $rated_list_package;
						} else {
							$rate = $rate_other;
						}

						$rate_discount = number_format( $response->RateReplyDetails->RatedShipmentDetails[0]->ShipmentRateDetail->TotalFreightDiscounts->Amount * $this->fedex_conversion_rate, 2, ".", "" );
						
						if( !get_option( 'ec_option_fedex_use_net_charge' ) ){
							$rate = $rate + $rate_discount;
						}
						
						$rates[] = array( 'rate_code' => $code, 'rate' => $rate );
					}
				}
				
				return $rates;
			} else {
				$db = new ec_db( );
				$db->insert_response( 0, 0, "FedEx Get All Rates V1 Error", print_r( $response, true ) );
				return "ERROR";
			}
		} catch ( SoapFault $exception ) {
			$db = new ec_db( );
			$db->insert_response( 0, 0, "FedEx Get All Rates V1 Error", "error in fedex get rate, " . $this->printFault($exception, $client) );
			return "ERROR";
		}
	}

	private function get_processed_rates( $response ) {
		$rates = array( );
		for ( $i = 0; $i < count( $response->output->rateReplyDetails ); $i++ ) {
			$code = $response->output->rateReplyDetails[$i]->serviceType;
			$rate = 0.000;
			$payor_account_package = 0.000;
			$rated_account_package = 0.000;
			$payor_list_package = 0.000;
			$rated_list_package = 0.000;
			$rate_other = 0.000;
			$rate_discount = 0;

			if ( isset( $response->output->rateReplyDetails[$i]->ratedShipmentDetails ) && is_array( $response->output->rateReplyDetails[$i]->ratedShipmentDetails ) ) {
				if( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->rateType == "PAYOR_ACCOUNT_PACKAGE" ){
					$payor_account_package = number_format( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->totalNetCharge * $this->fedex_conversion_rate, 2, ".", "" );
				}else if( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->rateType == "RATED_ACCOUNT_PACKAGE" ){
					$rated_account_package = number_format( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->totalNetCharge * $this->fedex_conversion_rate, 2, ".", "" );
				}else if( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->rateType == "PAYOR_LIST_PACKAGE" ){
					$payor_list_package = number_format( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->totalNetCharge * $this->fedex_conversion_rate, 2, ".", "" );
				}else if( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->rateType == "RATED_LIST_PACKAGE" ){
					$rated_list_package = number_format( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->totalNetCharge * $this->fedex_conversion_rate, 2, ".", "" );
				}else{
					$rate_other = number_format( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->totalNetCharge * $this->fedex_conversion_rate, 2, ".", "" );
				}
				if ( isset( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->ratedPackages ) && is_array( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->ratedPackages ) && isset( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->ratedPackages[0] ) && isset( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->ratedPackages[0]->packageRateDetail ) && isset( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->ratedPackages[0]->packageRateDetail->totalFreightDiscounts ) ) {
					$rate_discount = number_format( $response->output->rateReplyDetails[$i]->ratedShipmentDetails[0]->ratedPackages[0]->packageRateDetail->totalFreightDiscounts * $this->fedex_conversion_rate, 2, ".", "" );
				}
			}

			if( $payor_account_package > 0 ){
				$rate = $payor_account_package;
			}else if( $rated_account_package > 0 ){
				$rate = $rated_account_package;
			}else if( $payor_list_package > 0 ){
				$rate = $payor_list_package;
			}else if( $rated_list_package > 0 ){
				$rate = $rated_list_package;
			}else {
				$rate = $rate_other;
			}

			if ( ! get_option( 'ec_option_fedex_use_net_charge' ) ) {
				$rate = $rate + $rate_discount;
			}

			$rates[] = array( 'rate_code' => $code, 'rate' => $rate );
		}
		return $rates;
	}

	public function get_rate_test_v2( $ship_code, $destination_zip, $destination_country, $weight, $length = 10, $width = 10, $height = 10, $declared_value = 0, $cart = array() ) {
		$this->handle_token_auth();
		$request_url = $this->get_endpoint_url() . 'rate/v1/rates/quotes';
		$request_headr = $this->get_oauth_headers();
		$request_data = $this->get_rate_data( $cart, $ship_code, $destination_zip, $destination_country );
		$response_data = $this->get_oauth_response( $request_url, $request_headr, 1, $request_data );
		$response = json_decode( $response_data );
		$db = new ec_db( );
		$db->insert_response( 0, 0, "FedEx Test V2", print_r( $response, true ) );
		if ( ! $response || ! isset( $response->output ) || ! isset( $response->output->rateReplyDetails ) || ! is_array( $response->output->rateReplyDetails ) ) {
			$db->insert_response( 0, 0, "FedEx Test V2 Error", print_r( $response, true ) );
			return "ERROR";
		}
		return $response;
	}

	public function get_rate_test( $ship_code, $destination_zip, $destination_country, $weight, $length = 10, $width = 10, $height = 10, $declared_value = 0, $cart = array() ) {
		if ( get_option( 'ec_option_fedex_use_oauth' ) ) {
			return $this->get_rate_test_v2( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}

		if( $weight == 0 )
			return "0.00";
		
		$dimensions_units = 'IN';
		if( get_option( 'ec_option_enable_metric_unit_display' ) )
			$dimensions_units = 'CM';
			
		if( !$destination_country )
			$destination_country = $this->fedex_country_code;
		
		if( $this->fedex_test_account ){
			$path_to_wsdl = dirname(__FILE__) . "/fedex_rate_service_v16_test_account.wsdl";
		}else{
			$path_to_wsdl = dirname(__FILE__) . "/fedex_rate_service_v16.wsdl";
		}

		ini_set("soap.wsdl_cache_enabled", "0");
		 
		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
		
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' =>array(
				'Key' => $this->fedex_key, 
				'Password' => $this->fedex_password
			)
		); 
		$request['ClientDetail'] = array(
			'AccountNumber' => $this->fedex_account_number, 
			'MeterNumber' => $this->fedex_meter_number
		);
		$request['TransactionDetail'] = array( 'CustomerTransactionId' => ' *** Rate Request v16 using PHP ***' );
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '16', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['ReturnTransitAndCommit'] = true;
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		$request['RequestedShipment']['ShipTimestamp'] = date( 'c' );
		$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
		
		$shipper = array( 'Address' => array( 'PostalCode' => $this->fedex_ship_from_zip, 'CountryCode' => $this->fedex_country_code ) );
		$request['RequestedShipment']['Shipper'] = $shipper;
		
		$recipient = array( 'Address' => array( 'PostalCode' => $destination_zip, 'CountryCode' => $destination_country ) );
		$request['RequestedShipment']['Recipient'] = $recipient;
		
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
		$request['RequestedShipment']['PackageCount'] = '1';
		
		$packageLineItem = array( 
			'SequenceNumber' 	=> 1, 
			'GroupPackageCount'	=> 1, 
			'Dimensions'	=> array(
				'Length'		=> ( $length < 1 ) ? 1 : $length,
				'Width'			=> ( $width < 1 ) ? 1 : $width,
				'Height'		=> ( $height < 1 ) ? 1 : $height,
				'Units'			=> $dimensions_units
			),
			'Weight' => array( 
				'Value' 			=> $weight, 
				'Units' 			=> $this->fedex_weight_units
			),
			'InsuredValue' => array(
				'Currency'	=> str_replace( 'SGD', 'SID', get_option( 'ec_option_base_currency' ) ),
				'Amount'	=> number_format( $declared_value, 2, '.', '' ),
			),
		);
		$request['RequestedShipment']['RequestedPackageLineItems'] = $packageLineItem;
		
		try{
			$response = $client->getRates($request);
			
		}catch (SoapFault $exception){
			$reponse = array( "ERROR" => "Error in fedex get rate, " . $this->printFault( $exception, $client ) );     
		}
		
		$db = new ec_db( );
		$db->insert_response( 0, 0, "FedEx Test V1",  print_r( $request, true ) . ' ----- ' . print_r( $response, true ) );
		
		return $response;
	}
	
	private function printError( $client, $response ){
		$string = 'Error returned in processing transaction: ';
		$string .= $this->printNotifications( $response -> Notifications );
		$string .= $this->printRequestResponse( $client, $response );
		return $string;
	}
	
	private function printNotifications( $notes ){
		$string = "";
		foreach( $notes as $noteKey => $note ){
			if(is_string($note)){    
				$string .= $noteKey . ': ' . $note . "\r\n";
			}
			else{
				$string .= $this->printNotifications( $note );
			}
		}
		return $string;
	}
	
	private function printRequestResponse($client){
		return 'Request: ' .  htmlspecialchars($client->__getLastRequest()) . ", Response " . htmlspecialchars($client->__getLastResponse());
	}
	
	private function printFault($exception, $client) {
		$string = '<h2>Fault</h2>' . "<br>\n";                        
		$string .= "<b>Code:</b>{$exception->faultcode}<br>\n";
		$string .="<b>String:</b>{$exception->faultstring}<br>\n";
		$string .= sprintf( "\r%s:- %s", date("D M j G:i:s T Y"), $client->__getLastRequest( ). "\n\n" . $client->__getLastResponse( ) );
		return $string;
	}

	public function get_address_type_v2( $desination_address, $destination_city, $destination_state, $destination_zip, $destination_country ) {
		$this->handle_token_auth();
		$request_url = $this->get_endpoint_url() . 'address/v1/addresses/resolve';
		$request_headr = $this->get_oauth_headers();
		if ( 'GB' == $destination_country ) {
			$destination_state = $this->convert_uk_county( $destination_state, $destination_city );
		}
		if ( '' != $destination_state ) {
			$request_data = (object) array(
				'addressToValidate' => array(
					(object) array(
						'address' => (object) array(
							'streetLines' => array(
								$destination_address,
							),
							'city' => $destination_city,
							'stateOrProvinceCode' => $destination_state,
							'postalCode' => $destination_zip,
							'countryCode' => $destination_country,
						),
					),
				),
			);
		} else {
			$request_data = (object) array(
				'addressToValidate' => array(
					(object) array(
						'address' => (object) array(
							'streetLines' => array(
								$destination_address,
							),
							'city' => $destination_city,
							'postalCode' => $destination_zip,
							'countryCode' => $destination_country,
						),
					),
				),
			);
		}
		$response_data = $this->get_oauth_response( $request_url, $request_headr, 1, $request_data );
		$response = json_decode( $response_data );
		$db = new ec_db( );
		$db->insert_response( 0, 0, "FedEx Address Test V2", print_r( $response, true ) );
		if ( ! $response || ! isset( $response->output ) || ! isset( $response->output->resolvedAddresses ) || ! is_array( $response->output->resolvedAddresses ) ) {
			$db->insert_response( 0, 0, "FedEx Address Test V2 Error", print_r( $response, true ) );
			return "ERROR";
		}
		if( isset( $response->output->resolvedAddresses[0]->classification ) && 'BUSINESS' == $response->output->resolvedAddresses[0]->classification ) {
			return 0;
		}
		return 1;
	}

	public function get_address_type( $desination_address, $destination_city, $destination_state, $destination_zip, $destination_country ) {
		if ( get_option( 'ec_option_fedex_use_oauth' ) ) {
			return $this->get_address_type_v2( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );
		}

		$db = new ec_db( );
		if( $this->fedex_test_account ){
			$path_to_wsdl = dirname(__FILE__) . "/fedex_address_validation_service_v2_test.wsdl";
		}else {
			$path_to_wsdl = dirname(__FILE__) . "/fedex_address_validation_service_v2.wsdl";
		}
		ini_set("soap.wsdl_cache_enabled", "0");

		$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

		$request['WebAuthenticationDetail'] = array(
			'UserCredential' =>array(
				'Key' => $this->fedex_key, 
				'Password' => $this->fedex_password
			)
		); 
		$request['ClientDetail'] = array(
			'AccountNumber' => $this->fedex_account_number, 
			'MeterNumber' => $this->fedex_meter_number
		);
		$request['Version'] = array(
			'ServiceId' => 'aval', 
			'Major' => '4', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		if ( 'GB' == $destination_country ) {
			$destination_state = $this->convert_uk_county( $destination_state, $destination_city );
		}
		$request['RequestTimestamp'] = date( 'Y-m-d' ) . 'T' . date( 'H:i:sP' );
		if ( '' != $destination_state ) {
			$request['AddressesToValidate'] = (object) array(
				'Address' => (object) array(
					'StreetLines' => array( $desination_address ),
					'City' => $destination_city,
					'StateorProvinceCode' => $destination_state,
					'PostalCode' => $destination_zip,
					'CountryCode' => $destination_country
				)
			);
		} else {
			$request['AddressesToValidate'] = (object) array(
				'Address' => (object) array(
					'StreetLines' => array( $desination_address ),
					'City' => $destination_city,
					'PostalCode' => $destination_zip,
					'CountryCode' => $destination_country
				)
			);
		}
		$request['Options'] = array(
			'CheckResidentialStatus' => 1,
			'MaximumNumberOfMatches' => 5,
			'StreetAccuracy' => 'LOOSE',
			'DirectionalAccuracy' => 'LOOSE',
			'CompanyNameAccuracy' => 'LOOSE',
			'ConvertToUpperCase' => 1,
			'RecognizeAlternateCityNames' => 1,
			'ReturnParsedElements' => 1
		);

		try{
			$response = $client->addressValidation( $request );
			$db->insert_response( 0, 0, "FedEx AVS V1", print_r( $request, true ) . '------' . print_r( $response, true ) );
			if( $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' ){
				if( isset( $response->AddressResults ) && isset( $response->AddressResults->Classification ) && 'BUSINESS' == $response->AddressResults->Classification ) {
					return 0;
				}
			}

			return 1;

		} catch ( SoapFault $exception ) {
			$db->insert_response( 0, 0, "FedEx AVS V1 Err", print_r( $request, true ) . ' ------ ' . $this->printFault( $exception, $client ) );
			return 1;
		}
	}
	
	public function validate_address( $desination_address, $destination_city, $destination_state, $destination_zip, $destination_country ){
		return true;
		/*
		if( $this->fedex_test_account ){
			return true; //Cannot test address in test mode environment!! STUPID FEDEX.
		}else{
			$path_to_wsdl = dirname(__FILE__) . "/fedex_address_validation_service_v2.wsdl";

			ini_set("soap.wsdl_cache_enabled", "0");
			 
			$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
			
			$request['WebAuthenticationDetail'] = array(
				'UserCredential' =>array(
					'Key' => $this->fedex_key, 
					'Password' => $this->fedex_password
				)
			); 
			$request['ClientDetail'] = array(
				'AccountNumber' => $this->fedex_account_number, 
				'MeterNumber' => $this->fedex_meter_number
			);
			$request['Version'] = array(
				'ServiceId' => 'aval', 
				'Major' => '2', 
				'Intermediate' => '0', 
				'Minor' => '0'
			);
			
			$request['RequestTimestamp'] = date( 'Y-m-d' ) . 'T' . date( 'H:i:sP' );
			$request['AddressesToValidate'] = array(
				0 => array( 
					'Address' => $desination_address,
					'City' => $destination_city,
					'StateorProvinceCode' => $destination_state,
					'PostalCode' => $destination_zip,
					'CountryCode' => $destination_country
				)
			);
			$request['Options'] = array(
				'CheckResidentialStatus' => 1,
				'MaximumNumberOfMatches' => 5,
				'StreetAccuracy' => 'LOOSE',
				'DirectionalAccuracy' => 'LOOSE',
				'CompanyNameAccuracy' => 'LOOSE',
				'ConvertToUpperCase' => 1,
				'RecognizeAlternateCityNames' => 1,
				'ReturnParsedElements' => 1
			);
			
			try{
				$response = $client->addressValidation( $request );
			
				print_r( $response );
				die( );
				
				if( $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' ){  	
				
					if( $response->ProposedAddressDetails->Score > 0 )
						return true;
						
					else
						return false;
					
				}else{
					
					return true;
					
				}
					
				return $response;
			
			}catch (SoapFault $exception){
				
				return true; // Do not let an error stop the checkout!
			
			}
			
		}
		*/
	}
	
	private function check_package( $curr_package, $next_item ){
		$test_package = $this->add_to_package( $curr_package, $next_item );
		if ( $test_package['weight'] > 150 ) {
			return false;
		} else if( $test_package['length'] > 108 ) {
			return false;
		} else if( $test_package['width']*2 + $test_package['height']*2 + $test_package['length'] > 165 ) {
			return false;
		} else {
			return true;
		}
	}
	
	private function check_item( $next_item ){
		// Rotate the package
		$dimensions = array( $next_item->length, $next_item->width, $next_item->height );
		sort( $dimensions );
		
		if( $next_item->weight > 150 ) {
			return false;
		} else if( min( $next_item->length, $next_item->width, $next_item->height ) > 119 ) {
			return false;
		} else if( ( $dimensions[0] * 2 ) + ( $dimensions[1] * 2 ) > 165 ) {
			return false;
		} else {
			return true;
		}
	}
	
	private function get_empty_package( ){
		return array(
			'length' => 0,
			'width'  => 0,
			'height' => 0,
			'weight' => 0,
			'price' => 0,
		);
	}
	
	private function add_to_package( $curr_package, $item ){
		// Rotate item to find length
		$dimensions = array( $item->length, $item->width, $item->height );
		sort( $dimensions );
		
		// Should we put in box w+w, h+h, or l+l?
		$new_width = $curr_package['width'] + $dimensions[0];
		$new_height = $curr_package['height'] + $dimensions[0];
		$new_length = $curr_package['length'] + $dimensions[0];
		
		$volume_width = $new_width * max( $curr_package['height'], $dimensions[1] ) * max( $curr_package['length'], $dimensions[2] );
		$volume_height = $new_height * max( $curr_package['width'], $dimensions[1] ) * max( $curr_package['length'], $dimensions[2] );
		$volume_length = $new_length * max( $curr_package['width'], $dimensions[1] ) * max( $curr_package['height'], $dimensions[2] );
		
		if( $curr_package['weight'] == 0 || ( $volume_width < $volume_height && $volume_width < $volume_length ) ){
			$curr_package['length'] = max( $curr_package['length'], $dimensions[2] );
			$curr_package['width'] += $dimensions[0];
			$curr_package['height'] = max( $curr_package['height'], $dimensions[1] );
		
		}else if( $volume_height < $volume_width && $volume_height < $volume_length ){
			$curr_package['length'] = max( $curr_package['length'], $dimensions[2] );
			$curr_package['width'] = max( $dimensions[1], $curr_package['width'] );
			$curr_package['height'] += $dimensions[0];
			
		}else{
			$curr_package['length'] += $dimensions[0];
			$curr_package['width'] = max( $curr_package['width'], $dimensions[1] );
			$curr_package['height'] = max( $curr_package['height'], $dimensions[2] );
			
		}
		$curr_package['weight'] += $item->weight;
		$curr_package['price'] += $item->price;
		return $curr_package;
	}
	
	private function calculate_parcel( $products ){
 
		// Create an empty package
		$package_dimensions = array( 0, 0, 0 );
		$package_weight = 0;
		$package_price = 0;
		$package_volume = 0;
		$package_volume_empty = 0;
		$package_volume_used = 0;
		
		// Step through each product
		foreach( $products as $product ){
		
			// Create an array of product dimensions
			$product_dimensions = array( $product['width'], $product['height'], $product['length'] );
			
			// Twist and turn the item, longest side first ([0]=length, [1]=width, [2]=height)
			rsort( $product_dimensions, SORT_NUMERIC); // Sort $product_dimensions by highest to lowest
			
			if( $product_dimensions[0] <= $package_dimensions[0] && $product_dimensions[1] <= $package_dimensions[1] && $product_dimensions[2] <= $package_dimensions[2] && ( $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2] ) <= $package_volume_empty ){
				$package_volume_empty -= $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
				$package_volume_used += $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
			
			}else{
				
				// Package height + item height
				$package_dimensions[2] += $product_dimensions[2];
				
				// If this is the widest item so far, set item width as package width
				if($product_dimensions[1] > $package_dimensions[1]) 
					
					$package_dimensions[1] = $product_dimensions[1];
				
				// If this is the longest item so far, set item length as package length
				if($product_dimensions[0] > $package_dimensions[0]) 
					$package_dimensions[0] = $product_dimensions[0];
				
				// Twist and turn the package, longest side first ([0]=length, [1]=width, [2]=height)
				rsort( $package_dimensions, SORT_NUMERIC );
				
				$package_volume = $package_dimensions[0] * $package_dimensions[1] * $package_dimensions[2];
				$package_volume_used += $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
				$package_volume_empty = $package_volume - $package_volume_used;
				
			}
			
			// Add to total weight
			$package_weight = $package_weight + $product['weight'];
			$package_price = $package_price + $product['price'];
		}

		$parcel = array(
			'price' 	=> $package_price,
			'weight' 	=> $package_weight,
			'width'		=> $package_dimensions[0],
			'height'	=> $package_dimensions[1],
			'length'	=> $package_dimensions[2]
		);

		return $parcel;
	}

	private function convert_uk_county( $county, $city = '' ) {
		$counties_to_code = array(
			strtolower( 'Aberconwy and Colwyn' ) => 'I0',
			strtolower( 'Aberdeen City' ) => 'I1',
			strtolower( 'Aberdeenshire' ) => 'I2',
			strtolower( 'Anglesey' ) => 'I3',
			strtolower( 'Angus' ) => 'I4',
			strtolower( 'Antrim' ) => 'I5',
			strtolower( 'Argyll and Bute' ) => 'I6',
			strtolower( 'Armagh' ) => 'I7',
			strtolower( 'Avon' ) => 'I8',
			strtolower( 'Ayrshire' ) => 'I9',
			strtolower( 'Bath and NE Somerset' ) => 'IB',
			strtolower( 'Bedfordshire' ) => 'IC',
			strtolower( 'Belfast' ) => 'IE',
			strtolower( 'Berkshire' ) => 'IF',
			strtolower( 'Berwickshire' ) => 'IG',
			strtolower( 'BFPO' ) => 'IH',
			strtolower( 'Blaenau Gwent' ) => 'II',
			strtolower( 'Buckinghamshire' ) => 'IJ',
			strtolower( 'Caernarfonshire' ) => 'IK',
			strtolower( 'Caerphilly' ) => 'IM',
			strtolower( 'Caithness' ) => 'IO',
			strtolower( 'Cambridgeshire' ) => 'IP',
			strtolower( 'Cardiff' ) => 'IQ',
			strtolower( 'Cardiganshire' ) => 'IR',
			strtolower( 'Carmarthenshire' ) => 'IS',
			strtolower( 'Ceredigion' ) => 'IT',
			strtolower( 'Channel Islands' ) => 'IU',
			strtolower( 'Cheshire' ) => 'IV',
			strtolower( 'City of Bristol' ) => 'IW',
			strtolower( 'Clackmannanshire' ) => 'IX',
			strtolower( 'Clwyd' ) => 'IY',
			strtolower( 'Conwy' ) => 'IZ',
			strtolower( 'CornwallScilly' ) => 'J0',
			strtolower( 'Cornwall' ) => 'J0',
			strtolower( 'Scilly' ) => 'J0',
			strtolower( 'Cumbria' ) => 'J1',
			strtolower( 'Denbighshire' ) => 'J2',
			strtolower( 'Derbyshire' ) => 'J3',
			strtolower( 'Derry/Londonderry' ) => 'J4',
			strtolower( 'Derry' ) => 'J4',
			strtolower( 'Londonderry' ) => 'J4',
			strtolower( 'Devon' ) => 'J5',
			strtolower( 'Dorset' ) => 'J6',
			strtolower( 'Down' ) => 'J7',
			strtolower( 'Dumfries and Galloway' ) => 'J8',
			strtolower( 'Dunbartonshire' ) => 'J9',
			strtolower( 'Dundee' ) => 'JA',
			strtolower( 'Durham' ) => 'JB',
			strtolower( 'Dyfed' ) => 'JC',
			strtolower( 'East Ayrshire' ) => 'JD',
			strtolower( 'East Dunbartonshire' ) => 'JE',
			strtolower( 'East Lothian' ) => 'JF',
			strtolower( 'East Renfrewshire' ) => 'JG',
			strtolower( 'East Riding Yorkshire' ) => 'JH',
			strtolower( 'East Sussex' ) => 'JI',
			strtolower( 'Edinburgh' ) => 'JJ',
			strtolower( 'England' ) => 'JK',
			strtolower( 'Essex' ) => 'JL',
			strtolower( 'Falkirk' ) => 'JM',
			strtolower( 'Fermanagh' ) => 'JN',
			strtolower( 'Fife' ) => 'JO',
			strtolower( 'Flintshire' ) => 'JP',
			strtolower( 'Glasgow' ) => 'JQ',
			strtolower( 'Gloucestershire' ) => 'JR',
			strtolower( 'Greater London' ) => 'JS',
			strtolower( 'Greater Manchester' ) => 'JT',
			strtolower( 'Gwent' ) => 'JU',
			strtolower( 'Gwynedd' ) => 'JV',
			strtolower( 'Hampshire' ) => 'JW',
			strtolower( 'Hartlepool' ) => 'JX',
			strtolower( 'Hereford and Worcester' ) => 'HAW',
			strtolower( 'Hertfordshire' ) => 'JY',
			strtolower( 'Highlands' ) => 'JZ',
			strtolower( 'Inverclyde' ) => 'K0',
			strtolower( 'Inverness-Shire' ) => 'K1',
			strtolower( 'Isle of Man' ) => 'K2',
			strtolower( 'Isle of Wight' ) => 'K3',
			strtolower( 'Kent' ) => 'K4',
			strtolower( 'Kincardinshire' ) => 'K5',
			strtolower( 'Kingston Upon Hull' ) => 'K6',
			strtolower( 'Kinross-Shire' ) => 'K7',
			strtolower( 'Kirklees' ) => 'K8',
			strtolower( 'Lanarkshire' ) => 'K9',
			strtolower( 'Lancashire' ) => 'KA',
			strtolower( 'Leicestershire' ) => 'KB',
			strtolower( 'Lincolnshire' ) => 'KC',
			strtolower( 'Londonderry' ) => 'KD',
			strtolower( 'Merseyside' ) => 'KE',
			strtolower( 'Merthyr Tydfil' ) => 'KF',
			strtolower( 'Mid Glamorgan' ) => 'KG',
			strtolower( 'Mid Lothian' ) => 'KH',
			strtolower( 'Middlesex' ) => 'KI',
			strtolower( 'Monmouthshire' ) => 'KJ',
			strtolower( 'Moray' ) => 'KK',
			strtolower( 'Neath Port Talbot' ) => 'KL',
			strtolower( 'Newport' ) => 'KM',
			strtolower( 'Norfolk' ) => 'KN',
			strtolower( 'North Ayrshire' ) => 'KP',
			strtolower( 'North East Lincolnshire' ) => 'KQ',
			strtolower( 'North Lanarkshire' ) => 'KR',
			strtolower( 'North Lincolnshire' ) => 'KT',
			strtolower( 'North Somerset' ) => 'KU',
			strtolower( 'North Yorkshire' ) => 'KV',
			strtolower( 'Northamptonshire' ) => 'KO',
			strtolower( 'Northern Ireland' ) => 'KW',
			strtolower( 'Northumberland' ) => 'KX',
			strtolower( 'Nottinghamshire' ) => 'KZ',
			strtolower( 'Orkney and Shetland Isles' ) => 'L0',
			strtolower( 'Oxfordshire' ) => 'L1',
			strtolower( 'Pembrokeshire' ) => 'L2',
			strtolower( 'Perth and Kinross' ) => 'L3',
			strtolower( 'Powys' ) => 'L4',
			strtolower( 'Redcar and Cleveland' ) => 'L5',
			strtolower( 'Renfrewshire' ) => 'L6',
			strtolower( 'Rhonda Cynon Taff' ) => 'L7',
			strtolower( 'Rutland' ) => 'L8',
			strtolower( 'Scottish Borders' ) => 'L9',
			strtolower( 'Shetland' ) => 'LB',
			strtolower( 'Shropshire' ) => 'LC',
			strtolower( 'Somerset' ) => 'LD',
			strtolower( 'South Ayrshire' ) => 'LE',
			strtolower( 'South Glamorgan' ) => 'LF',
			strtolower( 'South Gloucesteshire' ) => 'LG',
			strtolower( 'South Lanarkshire' ) => 'LH',
			strtolower( 'South Yorkshire' ) => 'LI',
			strtolower( 'Staffordshire' ) => 'LJ',
			strtolower( 'Stirling' ) => 'LK',
			strtolower( 'Stockton On Tees' ) => 'LL',
			strtolower( 'Suffolk' ) => 'LM',
			strtolower( 'Surrey' ) => 'LN',
			strtolower( 'Swansea' ) => 'LO',
			strtolower( 'Torfaen' ) => 'LP',
			strtolower( 'Tyne and Wear' ) => 'LQ',
			strtolower( 'Tyrone' ) => 'LR',
			strtolower( 'Vale Of Glamorgan' ) => 'LS',
			strtolower( 'Wales' ) => 'LT',
			strtolower( 'Warwickshire' ) => 'LU',
			strtolower( 'West Berkshire' ) => 'LV',
			strtolower( 'West Dunbartonshire' ) => 'LW',
			strtolower( 'West Glamorgan' ) => 'LX',
			strtolower( 'West Lothian' ) => 'LY',
			strtolower( 'West Midlands' ) => 'LZ',
			strtolower( 'West Sussex' ) => 'M0',
			strtolower( 'West Yorkshire' ) => 'M1',
			strtolower( 'Western Isles' ) => 'M2',
			strtolower( 'Wiltshire' ) => 'M3',
			strtolower( 'Wirral' ) => 'M4',
			strtolower( 'Worcestershire' ) => 'M5',
			strtolower( 'Wrexham' ) => 'M6',
			strtolower( 'York' ) => 'M7',
		);
		if ( isset( $counties_to_code[ strtolower( str_replace( '&', '', str_replace( '/', '', $county ) ) ) ] ) ) {
			return $counties_to_code[ strtolower( str_replace( '&', '', str_replace( '/', '', $county ) ) ) ];
		} else if ( isset( $counties_to_code[ strtolower( str_replace( '&', '', str_replace( '/', '', $city ) ) ) ] ) ) {
			$counties_to_code[ strtolower( str_replace( '&', '', str_replace( '/', '', $city ) ) ) ];
		} else {
			return '';
		}
	}

}
