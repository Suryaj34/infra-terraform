<?php
// This is a payment gateway basic structure,
// child classes will be based on this class.

class ec_intuit extends ec_gateway{

	/****************************************
	* GATEWAY SPECIFIC HELPER FUNCTIONS
	*****************************************/

	function get_gateway_data( ){

		// Prior to checkout, attempt to reconnect if needed
		if( get_option( 'ec_option_intuit_last_authorized' ) + ( 3600 ) < time( ) ){
			$this->reauthorize( );
		}

		$tax_total = number_format( $this->order_totals->tax_total + $this->order_totals->gst_total + $this->order_totals->pst_total + $this->order_totals->hst_total, 2, '.', '' );
		if ( !$this->tax->vat_included ) {
			$tax_total = number_format( $tax_total + $this->order_totals->vat_total, 2, '.', '' );
		}

		$data = array( 
			'amount' => number_format( $this->order_totals->grand_total, 2, ".", "" ),
			'currency' => get_option( 'ec_option_intuit_currency' ),
			'token' => $_POST['intuitToken'],
			'context' => array(
				'tax' => $tax_total,
				'mobile' => false,
				'isEcommerce' => true
			),
			'capture' => 'true',
			'description' => $this->order_id,
		);

		return json_encode( $data );
	}

	private function get_guid( ){
		$chars = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9" );
		$length = 50;
		$guid = "";
		for ( $i = 0; $i < $length; $i++ ) {
			$guid .= $chars[ rand( 0, count( $chars ) - 1 ) ];
		}
		return $guid;
	}

	function get_gateway_url( ){
		if( get_option( 'ec_option_intuit_test_mode' ) ) {
			return "https://sandbox.api.intuit.com/quickbooks/v4/payments/charges";
		} else {
			return "https://api.intuit.com/quickbooks/v4/payments/charges" ;
		}
	}

	function get_host( ){
		if ( get_option( 'ec_option_intuit_test_mode' ) && get_option( 'ec_option_intuit_oauth_version' ) != 3 ) {
			return 'sandbox.api.intuit.com';
		} else {
			return 'api.intuit.com';
		}
	}

	function get_gateway_refund_url( $transaction_id ){
		if ( get_option( 'ec_option_intuit_test_mode' ) ) {
			return 'https://sandbox.api.intuit.com/quickbooks/v4/payments/charges/' . $transaction_id . '/refunds';
		} else {
			return 'https://api.intuit.com/quickbooks/v4/payments/charges/' . $transaction_id . '/refunds';
		}
	}

	function get_gateway_headers( ){
		if ( get_option( 'ec_option_intuit_oauth_version' ) != '1' ) {
			$headers =  array(
				'Accept' => 'application/json',
				'Authorization' => 'Bearer ' . get_option( 'ec_option_intuit_access_token' ),
				'Content-Type' => 'application/json;charset=UTF-8',
				'Request-Id' => substr( md5( time( ) ), 0, 32 )
			);
		}else{
			$signed = $this->get_signed( $this->get_gateway_url( ) );
			$headers =  array(
				'Content-Type' => 'application/json',
				'Host' => $this->get_host(),
				'Request-Id' => $this->get_guid(),
				'Authorization' => $signed['header']
			);
		}
		return $headers;
	}

	function get_gateway_response( $gateway_url, $gateway_data, $gateway_headers ){
		$headr = array();
		foreach ( $gateway_headers as $key => $header_info ){
			$headr[] = $key . ': ' . $header_info;
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $gateway_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headr );
		curl_setopt( $ch, CURLOPT_POST, true ); 
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $gateway_data );
		curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
		curl_setopt( $ch, CURLOPT_TIMEOUT, (int) 10 );
		$response = curl_exec( $ch );
		if( $response === false ){
			$this->mysqli->insert_response( 0, 1, "INTUIT CURL ERROR", curl_error( $ch ) );
			$response = (object) array( "error" => curl_error( $ch ) );
		}else{
			$this->mysqli->insert_response( 0, 0, "Intuit Response", print_r( $response, true ) );
		}
		curl_close ($ch);
		return $response;
	}

	private function get_special_gateway_headers( $url, $host ){
		if( get_option( 'ec_option_intuit_refresh_token' ) || get_option( 'ec_option_intuit_oauth_version' ) != '1' ){
			$headers =  array(
				'Authorization' => 'Bearer ' . get_option( 'ec_option_intuit_access_token' ),
				'Accept' => '*/*',
				'Content-Type' => 'application/json;charset=UTF-8',
				'Request-Id' => md5( time( ) )
			);
		}else{
			$signed = $this->get_signed( $url );
			$headers =  array(
				'Content-Type' => "application/json",
				'Host' => $host,
				'Request-Id' => $this->get_guid( ),
				'Authorization' => $signed['header']
			);
		}
		return $headers;
	}

	private function get_signed( $url ){
		$oauthObject = new OAuthSimple( get_option( 'ec_option_intuit_consumer_key' ), get_option( 'ec_option_intuit_consumer_secret' ) );
		$oauthObject->setAction( "POST" );
		$signatures = array(
			'api_key' => get_option( 'ec_option_intuit_consumer_key' ),
			'access_secret' => get_option( 'ec_option_intuit_consumer_secret' ), 
			'access_token' => get_option( 'ec_option_intuit_access_token' ),
			'oauth_token_secret' => get_option( 'ec_option_intuit_access_token_secret' )
		);

		$signed = $oauthObject->sign( 
			array(
				'path' => $url,
				"parameters"	=> array(
					'oauth_signature_method' => 'HMAC-SHA1'
				),
				'signatures' => $signatures
			)
		);
		return $signed;
	}

	function handle_gateway_response( $response ){
		if ( is_object( $response ) ) {
			$this->is_success = false;
			$this->mysqli->insert_response( $this->order_id, ! $this->is_success, 'Intuit', print_r( $response, true ) );
			$this->error_message = 'error';
		} else {
			$response_obj = json_decode( $response );
			if ( strtoupper( $response_obj->status ) == 'CAPTURED' ){
				$this->mysqli->update_order_transaction_id( $this->order_id, $response_obj->id );
				$this->is_success = true;
			}else {
				$this->is_success = false;
			}
			$this->mysqli->insert_response( $this->order_id, !$this->is_success, 'Intuit', print_r( $response, true ) );
			if( ! $this->is_success ) {
				$this->error_message = $response_obj->status;
			}
		}
	}

	public function refund_charge( $gateway_transaction_id, $refund_amount ){
		if ( get_option( 'ec_option_intuit_last_authorized' ) + ( 150 * 24 * 60 * 60 ) <= time( ) ) {
			$this->reauthorize();
		}

		$gateway_url = $this->get_gateway_refund_url( $gateway_transaction_id );
		$data = array( 
			'amount' => number_format( $refund_amount, 2, ".", "" )
		);

		$request = new WP_Http;
		$response = $request->request( $gateway_url, array( 'method' => 'POST', 'body' => json_encode( $data ), 'headers' => $this->get_special_gateway_headers( $gateway_url, $this->get_host( ) ) ) );

		if( is_wp_error( $response ) ){
			$this->error_message = $response->get_error_message( );
			return false;
		}else{
			$response_body = $response['body'];
			$response_obj = json_decode( $response_body );
			$this->mysqli->insert_response( 0, 1, "Intuit Refund", print_r( $response, true ) );
			if( strtoupper( $response_obj->status == "ISSUED" ) || strtoupper( $response_obj->status ) == "SETTLED" ){
				return true;
			}else{
				return false;
			}
		}
	}

	public function disconnect( ){

		if( get_option( 'ec_option_intuit_refresh_token' ) || get_option( 'ec_option_intuit_oauth_version' ) == '3' ){
			$ch = curl_init( );
			curl_setopt( $ch, CURLOPT_URL, "https://connect.wpeasycart.com/intuit/disconnect.php?refresh_token=" . get_option( 'ec_option_intuit_refresh_token' ) . "&test_mode=" . get_option( 'ec_option_intuit_test_mode' ) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$response = curl_exec( $ch );
			if( $response === false) {
				$response = file_get_contents( "https://connect.wpeasycart.com/intuit/disconnect.php?refresh_token=" . get_option( 'ec_option_intuit_refresh_token' ) . "&test_mode=" . get_option( 'ec_option_intuit_test_mode' ) );
			}
			curl_close( $ch );
			$this->mysqli->insert_response( 0, 0, "Intuit Disconnect", $response );
			return "success";

		}else{
			$gateway_url = "https://appcenter.intuit.com/api/v1/connection/disconnect";
			$gateway_headers = $this->get_special_gateway_headers( $gateway_url, "appcenter.intuit.com" );
			$data = "";

			$request = new WP_Http;
			$response = $request->request( $gateway_url, array( 'method' => 'POST', 'body' => json_encode( $data ), 'headers' => $gateway_headers ) );

			if( is_wp_error( $response ) ){
				return $response->get_error_message( );
			}else{
				$response_body = $response['body'];
				$response_obj = json_decode( $response_body );

				$this->mysqli->insert_response( 0, 1, "Intuit Disconnect", print_r( $response, true ) );

				if( $response_obj->ErrorCode == "0" ) {
					return "success";
				} else {
					return $response_obj->ErrorCode;
				}
			}
		}

	}

	public function reauthorize( ){

		if( get_option( 'ec_option_intuit_oauth_version' ) == '3' ){
			$ch = curl_init( );
			curl_setopt( $ch, CURLOPT_URL, "https://connect.wpeasycart.com/intuit/refresh.php?refresh_token=" . get_option( 'ec_option_intuit_refresh_token' ) . "&test_mode=" . get_option( 'ec_option_intuit_test_mode' ) );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$response = curl_exec( $ch );
			if( $response === false) {
				$response = file_get_contents( "https://connect.wpeasycart.com/intuit/refresh.php?refresh_token=" . get_option( 'ec_option_intuit_refresh_token' ) . "&test_mode=" . get_option( 'ec_option_intuit_test_mode' ) );
			}
			curl_close( $ch );
			$this->mysqli->insert_response( 0, 0, "Intuit Reconnect", $response );
			$response_decoded = json_decode( $response );

			if( isset( $response_decoded->refresh_token ) && isset( $response_decoded->access_token ) && '' != $response_decoded->refresh_token && '' != $response_decoded->access_token ){

				$refresh_token = $response_decoded->refresh_token;
				$access_token = $response_decoded->access_token;

				update_option( 'ec_option_intuit_access_token', $access_token );
				update_option( 'ec_option_intuit_refresh_token', $refresh_token );
				update_option( 'ec_option_intuit_last_authorized', time( ) );

				return "success";

			}else{
				return "error";
			}

		}else if( get_option( 'ec_option_intuit_oauth_version' ) == '2' ){

			$this->reauthorize_oauth2( );

		}else{

			$gateway_url = "https://appcenter.intuit.com/api/v1/connection/reconnect";

			$gateway_headers = $this->get_special_gateway_headers( $gateway_url, "appcenter.intuit.com" );

			$data = "";

			$request = new WP_Http;
			$response = $request->request( $gateway_url, array( 'method' => 'POST', 'body' => json_encode( $data ), 'headers' => $gateway_headers ) );

			if( is_wp_error( $response ) ){
				return $response->get_error_message( );

			}else{

				$response_body = $response['body'];
				$response_obj = json_decode( $response_body );

				$this->mysqli->insert_response( 0, 1, "Intuit Reconnect", print_r( $response, true ) );

				if( $response_obj->ErrorCode == "0" ){
					update_option( 'ec_option_intuit_access_token', $response_obj->OAuthToken );
					update_option( 'ec_option_intuit_access_token_secret', $response_obj->OAuthTokenSecret );
					update_option( 'ec_option_intuit_last_authorized', time( ) );

					return "success";

				}else
					return $response_obj->ErrorCode;

			}

		}

	}

	public function reauthorize_oauth2( ){

		$client_id = get_option( 'ec_option_intuit_consumer_key' );
		$client_secret = get_option( 'ec_option_intuit_consumer_secret' );

		$gateway_url = "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer";
		$gateway_data = array(
			"grant_type"	=> "refresh_token",
			"refresh_token"	=> get_option( 'ec_option_intuit_refresh_token' )
		);

		$headr = array( );
		$headr[] = 'Accept: application/json';
		$headr[] = 'Authorization: Basic ' . base64_encode( $client_id . ":" . $client_secret );
		$headr[] = 'Content-Type: application/x-www-form-urlencoded';
		$headr[] = 'Host: oauth.platform.intuit.com';
		$headr[] = 'Cache-Control: no-cache';

		$ch = curl_init( );
		curl_setopt($ch, CURLOPT_URL, $gateway_url );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headr );
		curl_setopt($ch, CURLOPT_POST, true ); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $gateway_data ) );
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_DEFAULT );
		curl_setopt($ch, CURLOPT_TIMEOUT, (int)30);
		$response = curl_exec($ch);
		$ec_db = new ec_db( );
		if( $response === false ){
			$ec_db->insert_response( 0, 1, "Intuit Connection Error", curl_error( $ch ) );
			$response = (object) array( "error" => curl_error( $ch ) );
		}else
			$ec_db->insert_response( 0, 0, "Intuit Connection Response", print_r( $response, true ) );
		curl_close ($ch);

		$response_decoded = json_decode( $response );

		$refresh_token = $response_decoded->refresh_token;
		$access_token = $response_decoded->access_token;
		$token_type = $response_decoded->token_type;
		$expires_in = $response_decoded->expires_in;
		$x_refresh_token_expires_in = $response_decoded->x_refresh_token_expires_in;

		update_option( 'ec_option_intuit_access_token', $access_token );
		update_option( 'ec_option_intuit_refresh_token', $refresh_token );
		update_option( 'ec_option_intuit_last_authorized', time( ) );

	}

}

add_action( 'wp_easycart_end_live_payment_box_inner', 'wp_easycart_intuit_add_javascript', 10, 1 );
function wp_easycart_intuit_add_javascript( $cartpage ) {
	echo '<script>
	jQuery( document ).ready( function() {
		jQuery( "#ec_cart_submit_order" ).on( "click", function( e ) {
			if( ! jQuery( document.getElementById( "ec_payment_credit_card" ) ).is( ":checked" ) ) {
				return true;
			}
			if( jQuery( ".ec_cart_error_row" ).is( ":visible" ) ) {
				return false;
			}
			var intuit_card = {};
			var card_name = "' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) ) . ' ' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ) . '";
			if( jQuery( "#ec_card_holder_name" ).length ) {
				card_name = jQuery( "#ec_card_holder_name" ).val();
			}
			var exp = jQuery( "#ec_cc_expiration" ).val().split( "/" );
			var expYear = exp[1].trim();
			if( expYear.length == 2 ) {
				expYear = "20" + expYear;
			}
			intuit_card.expYear = expYear;
			intuit_card.expMonth = exp[0].trim();
			intuit_card.address = {};
			intuit_card.address.region = "' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ) . '";
			intuit_card.address.postalCode = "' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ) . '";
			intuit_card.address.streetAddress = "' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ) . '";
			intuit_card.address.country = "' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) ) . '";
			intuit_card.address.city = "' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ) . '";
			intuit_card.name = card_name;
			intuit_card.cvc = jQuery( "#ec_security_code" ).val();
			intuit_card.number = jQuery( "#ec_card_number" ).val();
			var cardObj = {"card":intuit_card};
			e.preventDefault();
			var baseUrl = "' . ( ( get_option( 'ec_option_intuit_test_mode' ) ) ? 'https://sandbox.api.intuit.com/quickbooks/v4/payments/tokens' : 'https://api.intuit.com/quickbooks/v4/payments/tokens' ) . '";
			jQuery.ajax({
				url: baseUrl,
				data: JSON.stringify( cardObj ),
				type: "POST",
				dataType: "text",
				headers: {"Content-Type": "application/json"},
				success: function ( resp ) {
					var response = JSON.parse( resp );
					jQuery( "#ec_submit_order_form" ).append( \'<input type="hidden" name="intuitToken" value="\' + response.value + \'" \>\' ).submit();
					return true;
				},
				error: function(e) {
					console.log( intuit_card );
					console.log( e );
					return false;
				}
			});
		});
	} );
	</script>';
}