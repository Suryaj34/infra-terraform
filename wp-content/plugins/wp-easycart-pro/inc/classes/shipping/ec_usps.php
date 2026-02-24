<?php

class ec_usps{

	private $usps_user_name;									// Your USPS user name
	private $usps_ship_from_zip;								// Your USPS ship from zip code
	private $shipper_url;										// String
	private $use_international;									// BOOL

	function __construct( $ec_setting ){
		$this->usps_user_name = $ec_setting->get_usps_user_name();
		$this->usps_ship_from_zip = $ec_setting->get_usps_ship_from_zip();	

		$this->shipper_url = "https://production.shippingapis.com/ShippingAPI.dll";
	}

	public function get_rate( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){

		if( $weight == 0 )
			$weight = 1/16;

		if( !$destination_country )
			$destination_country = "US";

		if( !$destination_zip || $destination_zip == "" )
			$destination_zip = $this->usps_ship_from_zip;

		if( $destination_country != "US" ){

			$rate_codes = array(	"ALL" => "All",
									"FIRST CLASS" => "PARCEL",
									"FIRST CLASS COMMERCIAL" => "PARCEL",
									"FIRST CLASS HFP COMMERCIAL" => "PARCEL",
									"PACKAGE" => "Package",
									"POSTCARDS" => "Postcards",
									"ENVELOPE" => "Envelope",
									"LARGEENVELOPE" => "LargeEnvelope",
									"FLATRATE" => "FlatRate",
									"PRIORITY" => 1, 
									"PRIORITY COMMERCIAL" => 1, 
									"PRIORITY CPP" => 1, 
									"PRIORITY HFP COMMERCIAL" => 33, 
									"PRIORITY HFP CPP" => 33, 
									"EXPRESS" => 3, 
									"EXPRESS COMMERCIAL" => 3, 
									"EXPRESS CPP" => 3,
									"EXPRESS SH" => 23,  
									"EXPRESS SH COMMERCIAL" => 23, 
									"EXPRESS HFP CPP" => 2, 
									"STANDARD POST" => 4, 
									"MEDIA" =>6, 
									"LIBRARY" => 7,  
									"ONLINE" => 7, 
									"PLUS" => 7,
									"USPS GXG" => 12,
									"USPS GROUND ADVANTAGE" => 1058,
									"USPS GROUND ADVANTAGE CUBIC" => 1096
								);
		}else{

			$rate_codes = array( 	"PRIORITY" => 1, 
									"FIRST CLASS" => "PARCEL",
									"FIRST CLASS COMMERCIAL" => "PARCEL",
									"FIRST CLASS HFP COMMERCIAL" => "PARCEL",
									"PRIORITY COMMERCIAL" => 1, 
									"PRIORITY CPP" => 1, 
									"PRIORITY HFP COMMERCIAL" => 33, 
									"PRIORITY HFP CPP" => 33, 
									"EXPRESS" => 3, 
									"EXPRESS COMMERCIAL" => 3, 
									"EXPRESS CPP" => 3,
									"EXPRESS SH" => 23,  
									"EXPRESS SH COMMERCIAL" => 23, 
									"EXPRESS HFP CPP" => 2, 
									"STANDARD POST" => 4, 
									"MEDIA" =>6, 
									"LIBRARY" => 7, 
									"ALL" => 7, 
									"ONLINE" => 7, 
									"PLUS" => 7,
									"PACKAGE" => "Package",
									"POSTCARDS" => "Postcards",
									"ENVELOPE" => "Envelope",
									"LARGEENVELOPE" => "LargeEnvelope",
									"FLATRATE" => "FlatRate",
									"USPS GXG" => 12,
									"USPS GROUND ADVANTAGE" => 1058,
									"USPS GROUND ADVANTAGE CUBIC" => 1096

								);
		}

		$rate_type = strtoupper( $ship_code );
		$rate_code = $rate_codes[$rate_type];
		$ship_data = $this->get_shipper_data( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );


		$ch = curl_init(); //initiate the curl session 
		curl_setopt( $ch, CURLOPT_URL, $this->shipper_url ); //set to url to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // tell curl to return data in a variable 
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		if ( $this->use_international ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=IntlRateV2&XML=' . urlencode( $ship_data ) ); // post the xml
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=RateV4&XML=' . urlencode( $ship_data ) ); // post the xml
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)90); // set timeout in seconds 
		$response = curl_exec($ch);
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		if ( $response === false || $httpCode < 200 || $httpCode >= 300) {
			$response_error = curl_error( $ch );
			$response = '<?xml version="1.0" encoding="UTF-8"?>
			<Error>
				<Error>USPS Server Failure</Error>
				<Description>' . $response_error . '</Description>
				<Source>CURL</Source>
			</Error>';
		}
		curl_close ($ch);

		$db = new ec_db( );
		$db->insert_response( 0, 0, "USPS", print_r( $ship_data, true ) . ' ------- ' . print_r( $response, true ) );

		return $this->process_response( $response, $rate_code );

	}

	public function get_all_rates( $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( )){

		if( strlen( $destination_zip ) <= 0 )
			$destination_zip = $this->usps_ship_from_zip;

		$length = ceil( $length );
		if( $length <= 0 )
			$length = 1;
		$width = ceil( $width );
		if( $width <= 0 )
			$width = 1;
		$height = ceil( $height );
		if( $height <= 0 )
			$height = 1;

		if( !$destination_country )
			$destination_country = "US";

		if( !$destination_zip || $destination_zip == "" )
			$destination_zip = $this->usps_ship_from_zip;

		$ship_data = $this->get_all_rates_shipper_data( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );


		$ch = curl_init(); //initiate the curl session 
		curl_setopt( $ch, CURLOPT_URL, $this->shipper_url ); //set to url to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // tell curl to return data in a variable 
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		if ( $this->use_international ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=IntlRateV2&XML=' . urlencode( $ship_data ) ); // post the xml
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=RateV4&XML=' . urlencode( $ship_data ) ); // post the xml
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)90); // set timeout in seconds 
		$response = curl_exec($ch);
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		if ( $httpCode < 200 || $httpCode >= 300) {
			$response_error = curl_error( $ch );
			$response = '<?xml version="1.0" encoding="UTF-8"?>
			<Error>
				<Error>USPS Server Failure</Error>
				<Description>' . $response_error . '</Description>
				<Source>CURL</Source>
			</Error>';
		}
		curl_close ($ch);

		$db = new ec_db( );
		$db->insert_response( 0, 0, "USPS", print_r( $ship_data, true ) . ' ------- ' . print_r( $response, true ) );

		return $this->process_all_rates_response( $response );

	}

	public function get_rate_test( $ship_code, $destination_zip, $destination_country, $weight, $length = 1, $width = 1, $height = 1, $declared_value = 0, $cart = array( ) ){

		if( !$destination_country )
			$destination_country = "US";

		if( $destination_country != "US" ){

			$rate_codes = array(	"ALL" => "All",
									"FIRST CLASS" => "PARCEL",
									"FIRST CLASS COMMERCIAL" => "PARCEL",
									"FIRST CLASS HFP COMMERCIAL" => "PARCEL",
									"PACKAGE" => "Package",
									"POSTCARDS" => "Postcards",
									"ENVELOPE" => "Envelope",
									"LARGEENVELOPE" => "LargeEnvelope",
									"FLATRATE" => "FlatRate",
									"PRIORITY" => 1, 
									"PRIORITY COMMERCIAL" => 1, 
									"PRIORITY CPP" => 1, 
									"PRIORITY HFP COMMERCIAL" => 33, 
									"PRIORITY HFP CPP" => 33, 
									"EXPRESS" => 3, 
									"EXPRESS COMMERCIAL" => 3, 
									"EXPRESS CPP" => 3,
									"EXPRESS SH" => 23,  
									"EXPRESS SH COMMERCIAL" => 23, 
									"EXPRESS HFP CPP" => 2, 
									"STANDARD POST" => 4, 
									"MEDIA" =>6, 
									"LIBRARY" => 7,  
									"ONLINE" => 7, 
									"PLUS" => 7,
									"USPS GXG" => 12,
									"USPS GROUND ADVANTAGE" => 1058,
									"USPS GROUND ADVANTAGE CUBIC" => 1096
								);
		}else{

			$rate_codes = array( 	"PRIORITY" => 1, 
									"FIRST CLASS" => "PARCEL",
									"FIRST CLASS COMMERCIAL" => "PARCEL",
									"FIRST CLASS HFP COMMERCIAL" => "PARCEL",
									"PRIORITY COMMERCIAL" => 1, 
									"PRIORITY CPP" => 1, 
									"PRIORITY HFP COMMERCIAL" => 33, 
									"PRIORITY HFP CPP" => 33, 
									"EXPRESS" => 3, 
									"EXPRESS COMMERCIAL" => 3, 
									"EXPRESS CPP" => 3,
									"EXPRESS SH" => 23,  
									"EXPRESS SH COMMERCIAL" => 23, 
									"EXPRESS HFP CPP" => 2, 
									"STANDARD POST" => 4, 
									"MEDIA" =>6, 
									"LIBRARY" => 7, 
									"ALL" => 7, 
									"ONLINE" => 7, 
									"PLUS" => 7,
									"PACKAGE" => "Package",
									"POSTCARDS" => "Postcards",
									"ENVELOPE" => "Envelope",
									"LARGEENVELOPE" => "LargeEnvelope",
									"FLATRATE" => "FlatRate",
									"USPS GXG" => 12,
									"USPS GROUND ADVANTAGE" => 1058,
									"USPS GROUND ADVANTAGE CUBIC" => 1096

								);
		}

		$rate_type = strtoupper( $ship_code );
		$rate_code = $rate_codes[$rate_type];

		$ship_data = $this->get_shipper_data( $ship_code, $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value, $cart );

		$ch = curl_init(); //initiate the curl session 
		curl_setopt( $ch, CURLOPT_URL, $this->shipper_url ); //set to url to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // tell curl to return data in a variable 
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		if ( $this->use_international ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=IntlRateV2&XML=' . urlencode( $ship_data ) ); // post the xml
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=RateV4&XML=' . urlencode( $ship_data ) ); // post the xml
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)90); // set timeout in seconds 
		$response = curl_exec($ch);
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		if ( $response === false || $httpCode < 200 || $httpCode >= 300) {
			$response_error = curl_error( $ch );
			$response = '<?xml version="1.0" encoding="UTF-8"?>
			<Error>
				<Error>USPS Server Failure</Error>
				<Description>' . $response_error . '</Description>
				<Source>CURL</Source>
			</Error>';
		}
		curl_close ($ch); 
		$db = new ec_db( );
		$db->insert_response( 0, 0, "USPS TEST", print_r( $ship_data, true ) . ' ----- ' . print_r( $response, true ) );

		return $response;

	}

	private function get_shipper_data( $ship_code, $destination_zip, $destination_country, $weight, $height = 1, $width = 1, $length = 1, $declared_value = 0, $cart = array( ) ){

		$item_ids = array( '1ST', '2ND', '3RD' );

		$lbs = floor( $weight );
		$ounces =  16 * ( $weight - $lbs  ) ;

		if( $lbs == 0 && $ounces == 0 )
			$ounces = 1;

		$length = ceil( $length );
		if( $length <= 0 )
			$length = 1;
		$width = ceil( $width );
		if( $width <= 0 )
			$width = 1;
		$height = ceil( $height );
		if( $height <= 0 )
			$height = 1;

		if( $destination_country != "US" ){
			$db = new ec_db( );
			if( $destination_country == "KR" )
				$country_name = "KOREA";
			else
				$country_name = $GLOBALS['ec_countries']->get_country_name( $destination_country );
			$this->use_international = true;

			$length = ( $length < 6 ) ? 6 : $length;
			$width = ( $width < 1 ) ? 1 : $width;
			$height = ( $height < 4 ) ? 4 : $height;

			$shipper_data = "<IntlRateV2Request USERID='" . $this->usps_user_name . "' >
							<Revision>2</Revision>
							<Package ID='1ST' >
								<Pounds>" . $lbs . "</Pounds>
								<Ounces>" . $ounces . "</Ounces>
								<Machinable>true</Machinable>
								<MailType>" . $ship_code . "</MailType>
								<GXG>
									<POBoxFlag>N</POBoxFlag>
									<GiftFlag>N</GiftFlag>
								</GXG>
								<ValueOfContents>10.00</ValueOfContents>
								<Country>" . $country_name . "</Country>
								<Container>RECTANGULAR</Container>
								<Size>REGULAR</Size>
								<Width>" . $width . "</Width>
								<Length>" . $length . "</Length>
								<Height>" . $height . "</Height>
								<Girth>1</Girth>
								<OriginZip>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</OriginZip>
							</Package>
						</IntlRateV2Request>";

		}else{

			$this->use_international = false;
			$shipper_data = "<RateV4Request USERID='" . $this->usps_user_name . "' >
							<Revision/>";

			if( get_option( 'ec_option_ship_items_seperately' ) && count( $cart ) > 0 ){

				$i=0;

				foreach( $cart as $cartitem ){

					if( $cartitem->is_shippable && !$cartitem->exclude_shippable_calculation ){

						$lbs = ( isset( $cartitem->total_weight ) ) ? floor( $cartitem->total_weight ) : floor( $cartitem->weight );
						$ounces = 16 * ( isset( $cartitem->total_weight ) ) ? ( $cartitem->total_weight - $lbs  ) : ( $cartitem->weight - $lbs  );

						if ( $lbs == 0 && $ounces == 0 ) {
							$ounces = 1;
						}

						$length = ceil( $cartitem->length );
						if ( $length <= 0 ) {
							$length = 1;
						}
						$width = ceil( $cartitem->width );
						if ( $width <= 0 ) {
							$width = 1;
						}
						$height = ceil( $cartitem->height );
						if ( $height <= 0 ) {
							$height = 1;
						}

						$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
						for ( $j = 0; $j < $quantity; $j++ ) {		
							$shipper_data .= "
											<Package ID='";
											if( $i<3 ){
												$shipper_data .= $item_ids[$i];
											}else{
												$shipper_data .= ($i+1) . "TH";
											}
							$shipper_data .= "' >
												<Service>" . $ship_code . "</Service>
												<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
												<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
												<Pounds>" . $lbs . "</Pounds>
												<Ounces>" . $ounces . "</Ounces>
												<Container>RECTANGULAR</Container>
												<Size>LARGE</Size>
												<Width>" . $width . "</Width>
												<Length>" . $length . "</Length>
												<Height>" . $height . "</Height>
												<Machinable>true</Machinable>
											</Package>";			

							$i++;

						} // close quantity loop
					} // close is shippable check
				} // close cart items loop

			}else{
				$package_total = 0;
				$last_package_i = 0;
				$current_weight = 0;
				$current_package = 1;
				$products = array( );
				foreach ( $cart as $cartitem ) {
					$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
					for ( $i = 0; $i < $quantity; $i++ ) {
						if ( ! isset( $cartitem->exclude_shippable_calculation ) ) {
							$cartitem->exclude_shippable_calculation = 0;
						}
						if ( $cartitem->is_shippable && !$cartitem->exclude_shippable_calculation ) {
							$products[] = array( 
								'width' 	=> $cartitem->width,
								'height'	=> $cartitem->height,
								'length'	=> $cartitem->length,
								'weight'	=> ( isset( $cartitem->total_weight ) ) ? $cartitem->total_weight : $cartitem->weight,
							);
							$current_weight += ( ( isset( $cartitem->total_weight ) ) ? $cartitem->total_weight : $cartitem->weight );
						}
						$parcel = $this->calculate_parcel( $products );

						if ( $current_weight > 70 || $parcel['length'] > 17 || $parcel['height'] > 17 || $parcel['width'] > 27 ) {
							$lbs = floor( $parcel['weight'] );
							$ounces =  16 * ( $parcel['weight'] - $lbs  );

							if ( $lbs == 0 && $ounces == 0 ) {
								$ounces = 1;
							}

							// Get package name
							if ( $current_package == 1 ) {
								$package_name = "1ST";
							} else if ( $current_package == 2 ) {
								$package_name = "2ND";
							} else if ( $current_package == 3 ) {
								$package_name = "3RD";
							} else {
								$package_name = $current_package . "TH";
							}

							if ( $parcel['length'] <= 0 ) {
								$parcel['length'] = 1;
							}
							if ( $parcel['width'] <= 0 ) {
								$parcel['width'] = 1;
							}
							if ( $parcel['height'] <= 0 ) {
								$parcel['height'] = 1;
							}

							$shipper_data .= "
								<Package ID='" . $package_name . "' >
									<Service>ALL</Service>
									<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
									<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
									<Pounds>" . $lbs . "</Pounds>
									<Ounces>" . $ounces . "</Ounces>
									<Container>RECTANGULAR</Container>
									<Size>LARGE</Size>
									<Width>" . $parcel['width'] . "</Width>
									<Length>" . $parcel['length'] . "</Length>
									<Height>" . $parcel['height'] . "</Height>
									<Machinable>true</Machinable>
								</Package>";

							$products = array( );
							$current_weight = 0;
							$current_package++;
						}
					}
				}

				// Maybe add last package
				if ( count( $products ) > 0 ) {
					$parcel = $this->calculate_parcel( $products );

					$lbs = floor( $parcel['weight'] );
					$ounces =  16 * ( $parcel['weight'] - $lbs  );

					if ( $lbs == 0 && $ounces == 0 ) {
						$ounces = 1;
					}

					// Get package name
					if ( $current_package == 1 ) {
						$package_name = "1ST";
					} else if ( $current_package == 2 ) {
						$package_name = "2ND";
					} else if ( $current_package == 3 ) {
						$package_name = "3RD";
					} else {
						$package_name = $current_package . "TH";
					}

					// In case of old users with no dimensions
					if ( $parcel['length'] <= 0 ) {
						$parcel['length'] = 1;
					}
					if ( $parcel['width'] <= 0 ) {
						$parcel['width'] = 1;
					}
					if ( $parcel['height'] <= 0 ) {
						$parcel['height'] = 1;
					}

					$shipper_data .= "
						<Package ID='" . $package_name . "' >
							<Service>ALL</Service>
							<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
							<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
							<Pounds>" . $lbs . "</Pounds>
							<Ounces>" . $ounces . "</Ounces>
							<Container>RECTANGULAR</Container>
							<Size>LARGE</Size>
							<Width>" . $parcel['width'] . "</Width>
							<Length>" . $parcel['length'] . "</Length>
							<Height>" . $parcel['height'] . "</Height>
							<Machinable>true</Machinable>
						</Package>";
				}
			}
			$shipper_data .= "
						</RateV4Request>";

		}

		return $shipper_data;
	}

	private function get_all_rates_shipper_data( $destination_zip, $destination_country, $weight, $length, $width, $height, $declared_value = 0, $cart = array( ) ){

		$item_ids = array( '1ST', '2ND', '3RD' );

		$lbs = floor( $weight );
		$ounces = 16 * ( $weight - $lbs  ) ;

		if ( $lbs == 0 && $ounces == 0 ) {
			$ounces = 1;
		}

		$length = ceil( $length );
		if ( $length <= 0 ) {
			$length = 1;
		}
		$width = ceil( $width );
		if ( $width <= 0 ) {
			$width = 1;
		}
		$height = ceil( $height );
		if ( $height <= 0 ) {
			$height = 1;
		}

		if ( $destination_country != "US" ) {
			$db = new ec_db( );
			if ( $destination_country == "KR" ) {
				$country_name = "KOREA";
			} else {
				$country_name = $GLOBALS['ec_countries']->get_country_name( $destination_country );
			}
			$this->use_international = true;

			$length = ( $length < 6 ) ? 6 : $length;
			$width = ( $width < 1 ) ? 1 : $width;
			$height = ( $height < 4 ) ? 4 : $height;

			$shipper_data = "<IntlRateV2Request USERID='" . $this->usps_user_name . "' >
							<Revision>2</Revision>
							<Package ID='1ST' >
								<Pounds>" . $lbs . "</Pounds>
								<Ounces>" . $ounces . "</Ounces>
								<Machinable>true</Machinable>
								<MailType>ALL</MailType>
								<GXG>
									<POBoxFlag>N</POBoxFlag>
									<GiftFlag>N</GiftFlag>
								</GXG>
								<ValueOfContents>10.00</ValueOfContents>
								<Country>" . $country_name . "</Country>
								<Container>RECTANGULAR</Container>
								<Size>REGULAR</Size>
								<Width>" . $width . "</Width>
								<Length>" . $length . "</Length>
								<Height>" . $height . "</Height>
								<Girth>1</Girth>
								<OriginZip>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</OriginZip>
							</Package>
						</IntlRateV2Request>";

		} else {
			$this->use_international = false;
			$shipper_data = "<RateV4Request USERID='" . $this->usps_user_name . "' >
							<Revision/>";

			if ( get_option( 'ec_option_ship_items_seperately' ) && count( $cart ) > 0 ) {
				$i=0;
				foreach ( $cart as $cartitem ) {
					if ( $cartitem->is_shippable && !$cartitem->exclude_shippable_calculation ) {
						$lbs = ( isset( $cartitem->total_weight ) ) ? floor( $cartitem->total_weight ) : floor( $cartitem->weight );
						$ounces =  16 * ( isset( $cartitem->total_weight ) ) ? ( $cartitem->total_weight - $lbs ) : ( $cartitem->weight - $lbs );

						if ( $lbs == 0 && $ounces == 0 ) {
							$ounces = 1;
						}

						$length = ceil( $cartitem->length );
						if ( $length <= 0 ) {
							$length = 1;
						}
						$width = ceil( $cartitem->width );
						if ( $width <= 0 ) {
							$width = 1;
						}
						$height = ceil( $cartitem->height );
						if ( $height <= 0 ) {
							$height = 1;
						}
						$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
						for ( $j = 0; $j < $quantity; $j++ ) {
							$shipper_data .= "
											<Package ID='";
											if ( $i < 3 ) {
												$shipper_data .= $item_ids[$i];
											} else {
												$shipper_data .= ($i+1) . "TH";
											}
							$shipper_data .= "' >
												<Service>ALL</Service>
												<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
												<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
												<Pounds>" . $lbs . "</Pounds>
												<Ounces>" . $ounces . "</Ounces>
												<Container>RECTANGULAR</Container>
												<Size>LARGE</Size>
												<Width>" . $width . "</Width>
												<Length>" . $length . "</Length>
												<Height>" . $height . "</Height>
												<Machinable>true</Machinable>
											</Package>";	
							$i++;

						}// close quantity loop
					}// close is shippable check
				}// close cart items loop

			} else {
				$package_total = 0;
				$last_package_i = 0;

				// Generate Product List
				$current_weight = 0;
				$current_package = 1;
				$products = array( );
				foreach ( $cart as $cartitem ) {
					$quantity = ( isset( $cartitem->quantity ) ) ? $cartitem->quantity : 1;
					for ( $i = 0; $i < $quantity; $i++ ) {
						if ( $cartitem->is_shippable && ! $cartitem->exclude_shippable_calculation ) {
							$products[] = array( 
								'width' => $cartitem->width,
								'height' => $cartitem->height,
								'length' => $cartitem->length,
								'weight' => ( isset( $cartitem->total_weight ) ) ? $cartitem->total_weight / $quantity : $cartitem->weight,
							);
							$current_weight += ( isset( $cartitem->total_weight ) ) ? $cartitem->total_weight / $quantity : $cartitem->weight;
						}
						$parcel = $this->calculate_parcel( $products );

						if ( $current_weight > 70 || $parcel['length'] > 17 || $parcel['height'] > 17 || $parcel['width'] > 27 ) {
							$lbs = floor( $parcel['weight'] );
							$ounces =  16 * ( $parcel['weight'] - $lbs  );

							if ( $lbs == 0 && $ounces == 0 ) {
								$ounces = 1;
							}

							if ( $current_package == 1 ) {
								$package_name = "1ST";
							} else if ( $current_package == 2 ) {
								$package_name = "2ND";
							} else if ( $current_package == 3 ) {
								$package_name = "3RD";
							} else {
								$package_name = $current_package . "TH";
							}

							if ( $parcel['length'] <= 0 ) {
								$parcel['length'] = 1;
							}
							if ( $parcel['width'] <= 0 ) {
								$parcel['width'] = 1;
							}
							if ( $parcel['height'] <= 0 ) {
								$parcel['height'] = 1;
							}

							$shipper_data .= "
	<Package ID='" . $package_name . "' >
		<Service>ALL</Service>
		<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
		<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
		<Pounds>" . $lbs . "</Pounds>
		<Ounces>" . $ounces . "</Ounces>
		<Container>RECTANGULAR</Container>
		<Size>LARGE</Size>
		<Width>" . $parcel['width'] . "</Width>
		<Length>" . $parcel['length'] . "</Length>
		<Height>" . $parcel['height'] . "</Height>
		<Machinable>true</Machinable>
	</Package>";

							$products = array( );
							$current_weight = 0;
							$current_package++;
						}
					}
				}

				// Maybe add last package
				if( count( $products ) > 0 ){
					$parcel = $this->calculate_parcel( $products );

					$lbs = floor( $parcel['weight'] );
					$ounces =  16 * ( $parcel['weight'] - $lbs  );

					if( $lbs == 0 && $ounces == 0 )
						$ounces = 1;

					// Get package name
					if( $current_package == 1 ){		$package_name = "1ST";
					}else if( $current_package == 2 ){	$package_name = "2ND";
					}else if( $current_package == 3 ){	$package_name = "3RD";
					}else{								$package_name = $current_package . "TH";
					}

					// In case of old users with no dimensions
					if( $parcel['length'] <= 0 )		$parcel['length'] = 1;
					if( $parcel['width'] <= 0 )			$parcel['width'] = 1;
					if( $parcel['height'] <= 0 )		$parcel['height'] = 1;

					$shipper_data .= "
	<Package ID='" . $package_name . "' >
		<Service>ALL</Service>
		<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
		<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
		<Pounds>" . $lbs . "</Pounds>
		<Ounces>" . $ounces . "</Ounces>
		<Container>RECTANGULAR</Container>
		<Size>LARGE</Size>
		<Width>" . $parcel['width'] . "</Width>
		<Length>" . $parcel['length'] . "</Length>
		<Height>" . $parcel['height'] . "</Height>
		<Machinable>true</Machinable>
	</Package>";
				} else if ( $current_package == 1 ) {
					$shipper_data .= "
	<Package ID='1ST' >
		<Service>ALL</Service>
		<ZipOrigination>" . substr( $this->usps_ship_from_zip, 0, 5 ) . "</ZipOrigination>
		<ZipDestination>" . substr( $destination_zip, 0, 5 ) . "</ZipDestination>
		<Pounds>0</Pounds>
		<Ounces>1</Ounces>
		<Container>RECTANGULAR</Container>
		<Size>LARGE</Size>
		<Width>1</Width>
		<Length>1</Length>
		<Height>1</Height>
		<Machinable>true</Machinable>
	</Package>";
				}

			}

			$shipper_data .= "
						</RateV4Request>";

		}

		return $shipper_data;
	}

	private function process_response( $result, $rate_code ){

		if( strlen( $result ) == 0 )
			return "ERROR";

		$conversion_rate = ( get_option( 'usps_conversion_rate' ) ) ? (float) get_option( 'usps_conversion_rate' ) : 1;

		$rate = false;
		try {
			$xml = new SimpleXMLElement( $result );
			if ( $this->use_international ) {
				if ( $xml && $xml->Package && $xml->Package[0] && $xml->Package[0]->Service && $xml->Package[0]->Service[0] ) {
					for( $i=0; $i<count( $xml->Package[0]->Service ); $i++ ){
						$rate = (float) $xml->Package[0]->Service[$i]->Postage * $conversion_rate;
					}
				} else {
					$rate = "ERROR";
				}
			} else {
				if ( $rate_code != 7 && $xml && $xml->Package && $xml->Package[0] && $xml->Package[0]->Postage && $xml->Package[0]->Postage[0] && $xml->Package[0]->Postage[0]->Rate ) {
					$rate = $xml->Package[0]->Postage[0]->Rate * $conversion_rate;
				} else {
					$rate = "ERROR";
				}
			}
		} catch ( Exception $e ) {
			// Ignore errors
		}

		if ( $rate ) {
			return $rate;
		} else{
			return 'ERROR';
		}
	}

	private function process_all_rates_response( $result ){
		$rates = array();
		if ( strlen( $result ) == 0 ) {
			return $rates;
		}
		$conversion_rate = ( get_option( 'usps_conversion_rate' ) ) ? (float) get_option( 'usps_conversion_rate' ) : 1;
		try {
			$xml = new SimpleXMLElement( $result );
			if ( $this->use_international ) {
				$min_rate = (float) 99999.99;
				foreach ( $xml->Package->Service as $service ) {
					if ( (float) $service->Postage < $min_rate ) {
						$min_rate = (float) $service->Postage * $conversion_rate;
					}
					if ( (string) $service->attributes()->ID == "1" ) {
						$rates["EXPRESS"] = array( 'rate_code' => "EXPRESS", 'rate' => (float) $service->Postage * $conversion_rate );
					} else if ( (string) $service->attributes()->ID == "2" ) {
						$rates["PRIORITY"] = array( 'rate_code' => "PRIORITY", 'rate' => (float) $service->Postage * $conversion_rate );
					} else if ( (string) $service->attributes()->ID == "15" ) {
						$rates["FIRST CLASS"] = array( 'rate_code' => "FIRST CLASS", 'rate' => (float) $service->Postage * $conversion_rate );
						$rates["FIRST CLASS RETAIL"] = array( 'rate_code' => "FIRST CLASS RETAIL", 'rate' => (float) $service->Postage * $conversion_rate );
					} else if ( (string) $service->attributes()->ID == "12" ) {
						$rates["USPS GXG"] = array( 'rate_code' => "USPS GXG", 'rate' => (float) $service->Postage * $conversion_rate );
					}
				}
				if ( $min_rate == 99999.99 ) {
					$rates[] = array( 'rate_code' => 'ALL', 'rate' => 0.00 );
				} else {
					$rates[] = array( 'rate_code' => 'ALL', 'rate' => $min_rate );
				}
			} else {
				$rate_codes = array(
					"1" => "PRIORITY",
					"33" => "PRIORITY HFP COMMERCIAL", 
					"3" => "EXPRESS",
					"23" => "EXPRESS SH",   
					"2" => "EXPRESS HFP CPP", 
					"4" => "STANDARD POST", 
					"6" => "MEDIA", 
					"7" => "LIBRARY",
					"0" => "FIRST CLASS", // Old Version, picks last item
					"0-2" => "FIRST CLASS STAMPED LETTER",
					"0-3" => "FIRST CLASS RETAIL",
					"0-4" => "FIRST CLASS LARGE ENVELOPE",
					"12"  => "USPS GXG",
					"1058" => "USPS GROUND ADVANTAGE",
					"1096" => "USPS GROUND ADVANTAGE CUBIC",
				);
				$package_count = 0;
				foreach ( $xml->Package as $package ) {
					$rate_types_found = array();
					for ( $i = 0; $i < count( $package->Postage ); $i++ ) {
						$rate_id = (string) $package->Postage[ $i ]['CLASSID'];
						$rate_title = (string) $package->Postage[ $i ]->MailService;
						if ( strval( $rate_id ) == '0' && strstr( $rate_title, 'Stamped Letter' ) ) {
							$rate_id .= '-2';
						} else if ( strval( $rate_id ) == '0' && strstr( $rate_title, 'Retail' ) ) {
							$rate_id .= '-3';
						} else if ( strval( $rate_id ) == '0' && strstr( $rate_title, 'Large Envelope' ) ) {
							$rate_id .= '-4';
						}
						if ( isset( $rate_codes[ strval( $rate_id ) ] ) ) {
							if ( isset( $rates[$rate_codes[ strval( $rate_id )]] ) && isset( $rates[$rate_codes[ strval( $rate_id )]]['rate'] ) && !in_array( strval( $rate_id ), $rate_types_found ) ) {
								if ( (float) $package->Postage[$i]->Rate > 0 ) {
									$rates[$rate_codes[ strval( $rate_id )]]['rate'] = $rates[$rate_codes[ strval( $rate_id )]]['rate'] + (float) $package->Postage[$i]->Rate * $conversion_rate;
									$rates[$rate_codes[ strval( $rate_id )]]['count']++;
									$rate_types_found[] = strval( $rate_id );
								} else if ( isset( $package->Postage[$i]->CommercialRate ) ) {
									$rates[$rate_codes[ strval( $rate_id )]]['rate'] = $rates[$rate_codes[ strval( $rate_id )]]['rate'] + (float) $package->Postage[$i]->CommercialRate * $conversion_rate;
									$rates[$rate_codes[ strval( $rate_id )]]['count']++;
									$rate_types_found[] = strval( $rate_id );
								}
							} else {
								if ( (float) $package->Postage[$i]->Rate > 0 ) {
									$rates[$rate_codes[ strval( $rate_id )]] = array( 'rate_code' => (string) $rate_codes[ strval( $rate_id ) ], 'rate' => (float) $package->Postage[$i]->Rate * $conversion_rate, 'count' => 1 );
									$rate_types_found[] = strval( $rate_id );
								} else if ( isset( $package->Postage[$i]->CommercialRate ) ) {
									$rates[$rate_codes[ strval( $rate_id )]] = array( 'rate_code' => (string) $rate_codes[ strval( $rate_id ) ], 'rate' => (float) $package->Postage[$i]->CommercialRate * $conversion_rate, 'count' => 1 );
									$rate_types_found[] = strval( $rate_id );
								}
							}
						}
					}
					$package_count++;
				}
				foreach ( $rates as $key => $arr ) {
					if ( $arr['count'] < $package_count ) {
						unset( $rates[$key] );
					}
				}
			}
		} catch ( Exception $e ) {
			// Ignore errors
		}
		return $rates;
	}

	public function validate_address( $destination_address, $destination_city, $destination_state, $destination_zip, $destination_country ){
		return true;
		$ship_code = 'ALL';
		$ship_data = $this->get_shipper_data( $ship_code, $destination_zip, $destination_country, 5 );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $this->shipper_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		if ( $destination_country != 'US' ) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=IntlRateV2&XML=' . urlencode( $ship_data ) );
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, 'API=RateV4&XML=' . urlencode( $ship_data ) );
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)90); 
		$response = curl_exec($ch);
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		if ( $response === false || $httpCode < 200 || $httpCode >= 300) {
			$response = '';
		}
		curl_close ($ch); 

		if( strlen( $response ) == 0 ) {
			return false;
		}

		try {
			$xml = new SimpleXMLElement( $response );
			if ( isset( $xml->Package ) && isset( $xml->Package->Error ) ) {
				return false;
			} else {
				return true;
			}
		} catch ( Exception $e ) {
			// Ignore errors
		}
		return false;
	}

	private function calculate_parcel( $products ){

		// Create an empty package
		$package_dimensions = array( 0, 0, 0 );
		$package_weight = 0;
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
		}

		$parcel = array( 	'weight' 	=> $package_weight,
							'width'		=> $package_dimensions[0],
							'height'	=> $package_dimensions[1],
							'length'	=> $package_dimensions[2] );

		return $parcel;
	}

}	
?>