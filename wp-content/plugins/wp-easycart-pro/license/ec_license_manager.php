<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'ec_license_manager' ) ) :

final class ec_license_manager{
	
	protected static $_instance = null;
	
	private $license_data;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
	
	public function is_pro( ){
		$license_data = $this->ec_get_license( );
		print_r( $license_data );
	}
	
	public function is_expired( ){
		$license_data = $this->ec_get_license( );
		print_r( $license_data );
	}
	
	public function ec_get_license( ){
		///////////////////////////////////////////////////////////////////////////////////////
		//if you want new registration immediately, uncomment and delete transient every time
		//delete_transient( 'ec_license_data');
		///////////////////////////////////////////////////////////////////////////////////////
		if( isset( $_GET['page'] ) && ( $_GET['page'] == 'wp-easycart-registration' || $_GET['page'] == 'ec_adminv2' ) ){
			delete_transient( 'ec_license_data');
		}
		$transient = get_transient( 'ec_license_data' );
		if( !empty( $transient ) && ( ( isset( $transient->key_version ) && $transient->key_version != 'v4' ) || ( isset( $transient->support_end_date ) && time( ) <= strtotime( $transient->support_end_date ) ) ) ){
			return $transient;
			
		}else{
			$this->license_data = new stdClass();
			$action_url = 'https://licensing.wpeasycart.com/licensing/checkregistration.php';
			
			$url = site_url();
			$url = str_replace('http://', '', $url);
			$url = str_replace('https://', '', $url);
			$url = str_replace('www.', '', $url);
			
			$api_params = array(
				'ec_action' => 'get_license',
				'site_url' => $url
			);
			
			$response = wp_remote_get( $action_url, array( 'body' => $api_params, 'timeout' => 10, 'sslverify' => false ) );
			if( is_wp_error( $response ) ){
				$this->license_data->siteurl = $url;
				$this->license_data->customername = 'Not Available';
				$this->license_data->date = 'Not Available';
				$this->license_data->response_code = 200;
				$this->license_data->response_error = true;
				$this->license_data->key_version = 'v3';
				$this->license_data->support_end_date = '';
				$this->license_data->transaction_key = '';
				$this->license_data->is_trial = false;
				set_transient( 'ec_license_data', $this->license_data, HOUR_IN_SECONDS);  //set how long we want a license check to be occuring
				//delete_transient( 'ec_license_data');
				return $this->license_data;
			}
			$response_code = wp_remote_retrieve_response_code( $response ) ;
			$body = wp_remote_retrieve_body( $response );
		  
			if( $response_code != 200 ){
				$this->license_data->siteurl = $url;
				$this->license_data->customername = 'Not Available';
				$this->license_data->date = 'Not Available';
				$this->license_data->response_code = 200;
				$this->license_data->key_version = 'v3';
				$this->license_data->support_end_date = '';
				$this->license_data->transaction_key = '';
				$this->license_data->is_trial = false;
				delete_transient( 'ec_license_data');
				return $this->license_data;
			
			}else{
				$this->license_data = json_decode( $body );
				if( is_object( $this->license_data ) ){
					$this->license_data->response_code = $response_code;
				}else{
					$this->license_data = (object) array( 'response_code', $response_code ); 
				}
				set_transient( 'ec_license_data', $this->license_data, HOUR_IN_SECONDS);  //set how long we want a license check to be occuring
				return $this->license_data;
			 }
		}
	}
	
	public function ec_update_license( $email ){
		$license_info = get_option( 'wp_easycart_license_info' );
		$license_info['customer_email'] = $email;
		update_option( 'wp_easycart_license_info', $license_info );
		
		$action_url = 'https://licensing.wpeasycart.com/licensing/updateregistration.php';
		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);
		
		$api_params = array(
			'ec_action' 		=> 'update_license',
			'site_url' 			=> $url,
			'customeremail' 	=> esc_attr( $email ),
			'transactionkey'	=> esc_attr( $license_info['transaction_key'] )
		);
		
		$response = wp_remote_get( $action_url, array( 'body' => $api_params, 'timeout' => 30, 'sslverify' => false ) );
		delete_transient( 'ec_license_data' );
		
	}
	
	public function ec_activate_license($customername, $customeremail, $transactionkey) {
		
		update_option( 'wp_easycart_license_info', array( 
			'customer_name' => $customername, 
			'customer_email' => $customeremail, 
			'transaction_key' => $transactionkey 
		) );
		delete_transient( 'ec_license_data');
		$action_url = 'https://licensing.wpeasycart.com/licensing/activateregistration.php';

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);
		
		$api_params = array(
			'ec_action' => 'activate_license',
			'site_url' => $url,
			'customername' => esc_attr( $customername ),
			'customeremail' => esc_attr( $customeremail ),
			'transactionkey' => esc_attr( $transactionkey )
		);
		
		$response = wp_remote_get( $action_url, array( 'body' => $api_params, 'timeout' => 30, 'sslverify' => false ) );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		
		$body = wp_remote_retrieve_body( $response );
		
		$activation_status = $body;
		
		return $activation_status;  //no_key_found, success, error

	}
	
	public function ec_deactivate_license($transactionkey) {
		
		delete_option( 'wp_easycart_license_info' );
		delete_transient( 'ec_license_data');
		$action_url = 'https://licensing.wpeasycart.com/licensing/deactivateregistration.php';

		$url = site_url();
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('www.', '', $url);
		
		$api_params = array(
			'ec_action' => 'deactivate_license',
			'site_url' => $url,
			'transactionkey' => esc_attr( $transactionkey )
		);
		
		$response = wp_remote_get( $action_url, array( 'body' => $api_params, 'timeout' => 30, 'sslverify' => false ) );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		
		$body = wp_remote_retrieve_body( $response );
		
		
		$deactivation_status = $body;
		
		return $deactivation_status;

	}

}
endif; // End if class_exists check

function ec_license_manager( ){
	return ec_license_manager::instance( );
}
ec_license_manager( );