<?php
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'wp_easycart_admin_downloads_pro' ) )  :

final class wp_easycart_admin_downloads_pro{
	
	protected static $_instance = null;
	
	public $downloads_list_file;
	public $downloads_details_file;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
	
	public function __construct( ){ 
		$this->downloads_list_file 			= WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/downloads/downloads-list.php';
		$this->downloads_details_file 		= WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/downloads/downloads-details.php';
		if( wp_easycart_admin_license( )->is_licensed( ) ){
			remove_action( 'wp_easycart_admin_downloads_list', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			remove_action( 'wp_easycart_admin_downloads_details', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			add_action( 'wp_easycart_admin_downloads_list', array( $this, 'show_list' ), 1 );
			add_action( 'wp_easycart_admin_downloads_details', array( $this, 'show_details' ), 1 );
			
			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			
			/* Process Admin Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_download' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_download' ) );
			
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_download' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_download' ) );
		}
	}
	
	public function process_add_new_download( ){
		if( $_POST['ec_admin_form_action'] == "add-new-download" ){
			$result = $this->insert_download( );
			wp_easycart_admin( )->redirect( 'wp-easycart-orders', 'downloads', $result );
		}
	}
	
	public function process_update_download( ){
		if( $_POST['ec_admin_form_action'] == "update-download" ){
			$result = $this->update_download( );
			wp_easycart_admin( )->redirect( 'wp-easycart-orders', 'downloads', $result );
		}
	}
	
	public function process_delete_download( ){
		if( $_GET['ec_admin_form_action'] == 'delete-download' && isset( $_GET['download_id'] ) && !isset( $_GET['bulk'] ) ){
			$result = $this->delete_download( );
			wp_easycart_admin( )->redirect( 'wp-easycart-orders', 'downloads', $result );
		}
	}
	
	public function process_bulk_delete_download( ){
		if( $_GET['ec_admin_form_action'] == 'delete-download' && !isset( $_GET['download_id'] ) && isset( $_GET['bulk'] ) ){
			$result = $this->bulk_delete_download( );
			wp_easycart_admin( )->redirect( 'wp-easycart-orders', 'downloads', $result );
		}
	}
	
	public function add_success_messages( $messages ){
		if( isset( $_GET['success'] ) && $_GET['success'] == 'download-inserted' ){
			$messages[] = __( 'Download successfully created', 'wp-easycart-pro' );
		}else if( isset( $_GET['success'] ) && $_GET['success'] == 'download-updated' ){
			$messages[] = __( 'Download successfully updated', 'wp-easycart-pro' );
		}else if( isset( $_GET['success'] ) && $_GET['success'] == 'download-deleted' ){
			$messages[] = __( 'Download successfully deleted', 'wp-easycart-pro' );
		}
		return $messages;
	}
	
	public function show_list( ){
		include( $this->downloads_list_file );
	}
	
	public function show_details( ){
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_details_downloads.php' );
		$details = new wp_easycart_admin_details_downloads( );
		$details->output( esc_attr( $_GET['ec_admin_form_action'] ) );
	}

	public function insert_download() {
		global $wpdb;
		$download_id = $_POST['download_id'];
		$order_id = $_POST['order_id'];
		$product_id = $_POST['product_id'];
		$download_count = $_POST['download_count'];
		$download_file_name = stripslashes_deep( $_POST['download_file_name'] );
		$amazon_key = stripslashes_deep( $_POST['amazon_key'] );
		$is_amazon_download = 0;
		if ( isset( $_POST['is_amazon_download'] ) ) {
			$is_amazon_download = 1;
		}
		$wpdb->query( $wpdb->prepare( "INSERT INTO ec_download( download_id, order_id, product_id, download_count, download_file_name, is_amazon_download, amazon_key ) VALUES( %s, %d, %d, %s, %s, %d, %s )", $download_id, $order_id, $product_id, $download_count, $download_file_name, $is_amazon_download, $amazon_key ) );
		do_action( 'wpeasycart_download_added', $download_id );
		return array( 'success' => 'download-inserted' );
	}

	public function update_download() {
		global $wpdb;
		$download_id = $_POST['download_id'];
		$order_id = $_POST['order_id'];
		$date_created = date( 'Y-m-d', strtotime( $_POST['date_created'] ) );
		$download_count = $_POST['download_count'];
		$product_id = $_POST['product_id'];
		$download_file_name = stripslashes_deep( $_POST['download_file_name'] );
		$amazon_key = stripslashes_deep( $_POST['amazon_key'] );
		$is_amazon_download = 0;
		if ( isset( $_POST['is_amazon_download'] ) ) {
			$is_amazon_download = 1;
		}
		$wpdb->query( $wpdb->prepare( "UPDATE ec_download SET date_created = %s, download_count = %s, product_id = %s, download_file_name = %s, is_amazon_download = %s, amazon_key = %s WHERE download_id = %s", $date_created, $download_count, $product_id, $download_file_name, $is_amazon_download, $amazon_key, $download_id ) );
		do_action( 'wpeasycart_download_updated', $download_id );
		return array( 'success' => 'download-updated' );	
	}

	public function delete_download() {
		global $wpdb;
		$download_id = $_GET['download_id'];
		do_action( 'wpeasycart_download_deleting', $download_id );
		$wpdb->query( $wpdb->prepare( "DELETE FROM ec_download WHERE ec_download.download_id = %s", $download_id ) );
		return array( 'success' => 'download-deleted' );
	}

	public function bulk_delete_download( ){
		global $wpdb;
		
		$bulk_ids = $_GET['bulk'];
		foreach( $bulk_ids as $bulk_id ){
			$wpdb->query( $wpdb->prepare( "DELETE FROM ec_download WHERE ec_download.download_id = %s", $bulk_id ) );
		}
		
		return array( 'success' => 'download-deleted' );
	}
}
endif; // End if class_exists check

function wp_easycart_admin_downloads_pro( ){
	return wp_easycart_admin_downloads_pro::instance( );
}
wp_easycart_admin_downloads_pro( );