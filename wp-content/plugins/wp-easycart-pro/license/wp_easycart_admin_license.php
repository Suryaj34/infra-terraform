<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_license' ) ) :

final class wp_easycart_admin_license{
	
	protected static $_instance = null;
	
	public $license_data;
	public $register_file;
	public $register_half_file;
	public $registration_expired_file;
	public $registration_expired_half_file;
	public $license_expired = false;
	public $valid_license = false;
	public $active_license = false;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
	
	public function __construct( ){
		add_action( 'admin_init', array( $this, 'run_actions' ) );
	}
	
	public function run_actions( ){
		//deactivate store license
		if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "deactivateregistration" ){
			$deactivate_status = ec_license_manager( )->ec_deactivate_license( $_POST['transactionkey'] );
			if( $deactivate_status == 'success' ){
				$query_extra = "&success=deactivate-complete";
			}else if( $deactivate_status == 'no_key_found' ){
				$query_extra = "&error=deactivate-no-key-found";
			}else{
				$query_extra = "&error=deactivate-registration-failed";
			}
			wp_redirect( 'admin.php?page=wp-easycart-registration&subpage=registration' . $query_extra );
			exit( );
		}
		//activate store license
		if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "activateregistration" ){
			$activate_status = ec_license_manager( )->ec_activate_license( $_POST['customername'], $_POST['customeremail'], $_POST['transactionkey'] );
			if( $activate_status == 'success' ){
				$query_extra = "&success=activate-complete";
			}else if( $activate_status == 'no_key_found' ){
				$query_extra = "&error=activate-no-key-found";
			}else{
				$query_extra = "&error=activate-failed";
			}
			
			$license_data = ec_license_manager( )->ec_get_license( );
			if( $license_data->is_trial ){
				wp_redirect( 'admin.php?page=wp-easycart-settings' . $query_extra );
			}else{
				wp_redirect( 'admin.php?page=wp-easycart-registration&subpage=registration' . $query_extra );
			}
			
			exit( );
		}
		
		// Update Email Address
		if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "updateregistrationemail" ){
			$activate_status = ec_license_manager( )->ec_update_license( $_POST['customeremail'] );			
			wp_redirect( 'admin.php?page=wp-easycart-registration&subpage=registration&success=update-complete' );
			exit( );
		}
	}
	
	public function is_licensed( ){
		return $this->valid_license && !$this->license_expired;
	}
	
	public function license_check( ){
		$this->license_data = ec_license_manager( )->ec_get_license( );
		
		if( !isset( $this->license_data->siteurl ) ){
			$this->license_data->siteurl == NULL;
		}
		
		$local_server_name = site_url();
		$local_server_name = str_replace('http://', '', $local_server_name);
		$local_server_name = str_replace('https://', '', $local_server_name);
		$local_server_name = str_replace('www.', '', $local_server_name);
		
		//////ACTIVE PLUGIN WITH ACTIVE SUPPORT
		//Plugin Installed
		//Good License Response
		//License is not empty
		//site url matches license site url
		//active support
		if( $this->license_data != NULL && $this->license_data->response_code == 200 && $this->license_data->siteurl == $local_server_name && $this->license_data->is_trial ){
			if( isset( $this->license_data->support_end_date ) && time( ) <= strtotime( $this->license_data->support_end_date ) ){
				$this->license_expired = false;
				$this->valid_license = true;
				$this->active_license = true;
			}else{
				$this->license_expired = true;
				$this->valid_license = true;
				$this->active_license = false;
			}
			return 'trial';
		
		}else if( $this->license_data != NULL && $this->license_data->response_code == 200 && $this->license_data->siteurl == $local_server_name && $this->license_data->key_version == 'v3'){
			$this->valid_license = true;
			$this->active_license = true;
			return 'activated';
		
		}else if( $this->license_data != NULL && $this->license_data->response_code == 200 && $this->license_data->siteurl == $local_server_name && $this->license_data->key_version == 'v4'){
			if( time( ) <= strtotime( $this->license_data->support_end_date ) ){
				$this->license_expired = false;
				$this->valid_license = true;
				$this->active_license = true;
			}else{
				$this->license_expired = true;
				$this->valid_license = true;
				$this->active_license = false;
			}
			return 'activated';
		
		///////DEACTIVATED LICENSE
		//Plugin Installed
		//Good License Response
		//site url is empty
		}else if( $this->license_data != NULL && $this->license_data->response_code == 200 && $this->license_data->siteurl == NULL ){
			$this->active_license = false;
			return 'deactivated';
		 
		///////ACTIVE PLUGIN WITH COMMUNICATIONS ERROR
		//Plugin Installed
		//bad License Response
		}else if( $this->license_data != NULL && $this->license_data->response_code != 200 ){
			$this->active_license = false;
			return 'communications_error';
		 
		///////NO PLUGIN INSTALLED
		//Plugin not installed
		}else{
			$this->active_license = false;
			return 'not_installed';	
		}
	}
}
endif; // End if class_exists check

function wp_easycart_admin_license( ){
	return wp_easycart_admin_license::instance( );
}
wp_easycart_admin_license( );