<?php
if( !defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_downloads extends wp_easycart_admin_details{
	
	public $download;
	public $item;

	public function __construct( ){
		parent::__construct( );
		add_action( 'wp_easycart_admin_downloads_details_basic_fields', array( $this, 'basic_fields' ) );
	}
	
	protected function init( ){
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=manage-downloads';
		$this->id = 1;
		$this->page = 'wp-easycart-orders';
		$this->subpage = 'downloads';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-download';
		$this->download = $this->item = (object) array(
			"download_id"					=> uniqid( md5( rand( ) ) ),
			"date_created"					=> date( 'F d, Y' ),
			"download_count"				=> 0,
			"order_id" 						=> "",
			"product_id" 					=> "",
			"download_file_name"			=> "",
			"is_amazon_download"			=> 0,
			"amazon_key"					=> ""
		);
	}
	
	protected function init_data( ){
		$this->form_action = 'update-download';
		$this->download = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_download.* FROM ec_download WHERE download_id = %s", $_GET['download_id'] ) );
		$this->id = $this->download->download_id;
	}

	public function output( $type = 'edit' ){
		$this->init( );
		if( $type == 'edit' )
			$this->init_data( );
		
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/orders/downloads/downloads-details.php' );
	}
	
	public function basic_fields( ){
		
		//server based file list
		$listDir = array( ); 
		$dir = WP_PLUGIN_DIR . "/wp-easycart-data/products/downloads";
		if( $handler = opendir( $dir ) ){ 
			while( ( $sub = readdir( $handler ) ) !== FALSE ){ 
				if( $sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "_notes" && $sub != ".htaccess" ){ 
					if( is_file( $dir . "/" . $sub ) ){ 
						$listDir[] = (object) array( 
							"id" => $sub, 
							"value" => $sub
						);
					}
				} 
			}
			closedir( $handler ); 
		} 
		
		//amazon file list
		$amazon_file_list = $this->getawsfiles( );
		$amazonDir = array( );
		if( $amazon_file_list ){
			foreach( $amazon_file_list as $amazon_file ){
				$amazonDir[] = (object) array(
					"id" => $amazon_file, 
					"value" => $amazon_file
				);
			}
		}
		
		
		global $wpdb;
		$all_download_products = $wpdb->get_results( "SELECT ec_product.product_id AS id, ec_product.title AS value FROM ec_product WHERE ec_product.is_download = '1' ORDER BY title ASC" );
		
		$download_file_name = $this->download->download_file_name;
		if( $download_file_name != "" && substr( $download_file_name, 0, 7 ) != "http://" && substr( $download_file_name, 0, 8 ) != "https://" ){
			$download_file_name = plugins_url( '/wp-easycart-data/products/downloads/' . $download_file_name );
		}
		
		$fields = apply_filters( 'wp_easycart_admin_downloads_details_basic_fields_list', array(
			array(
				"name"				=> "download_id",
				"type"				=> "text",
				"label"				=> __( "Unique Download ID", 'wp-easycart-pro' ),
				"required" 			=> true,
				"read-only"			=> true,
				"message" 			=> __( "Please enter a unique download id.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->download->download_id
			),
			array(
				"name"				=> "order_id",
				"type"				=> "number",
				"label"				=> __( "Order ID", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter an order id.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'number',
				"value"				=> $this->download->order_id
			),
			array(
				"name"				=> "date_created",
				"type"				=> "date",
				"label"				=> __( "Date Created", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter a starting date.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'date',
				"value"				=> date('M j Y g:i A', strtotime($this->download->date_created))
			),
			array(
				"name"				=> "download_count",
				"type"				=> "number",
				"label"				=> __( "Download Count", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter how many downloads have been made.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'number',
				"step"				=> 1,
				"min"				=> 0,
				"value"				=> $this->download->download_count
			),
			array(
				"name"				=> "product_id",
				"type"				=> "select",
				"label"				=> __( "Attached Product", 'wp-easycart-pro' ),
				"data_label" 		=> __( "Choose a downloadable product", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please select a product this download is attached to.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'select',
				"data"				=> $all_download_products,
				"value"				=> $this->download->product_id
			),
			array(
				"name"				=> "is_amazon_download",
				"type"				=> "checkbox",
				"label"				=> __( "Is an Amazon Download", 'wp-easycart-pro' ),
				"required" 			=> false,
				"message" 			=> __( "Please select if is an Amazon Download.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'checkbox',
				"onclick" 			=> "ec_admin_download_is_amazon",
				"selected" 			=> false,
				"value"				=> $this->download->is_amazon_download
			),
			array(
				"name"				=> "download_file_name",
				"type"				=> "image_upload",
				"label"				=> __( "Download File Name", 'wp-easycart-pro' ),
				"data_label" 		=> __( "Choose a download file", 'wp-easycart-pro' ),
				"required" 			=> false,
				"message" 			=> __( "Please select a file for this download", 'wp-easycart-pro' ),
				"validation_type" 	=> 'image',
				"button_label"		=> __( 'Upload File', 'wp-easycart-pro' ),
				"show_delete"		=> false,
				"image_action"		=> 'ec_admin_download_upload',
				"value"				=> $download_file_name
			),
			array(
				"name"				=> "amazon_key",
				"type"				=> "select",
				"label"				=> __( "Amazon S3 File Name", 'wp-easycart-pro' ),
				"data_label" 		=> __( "Choose a download file", 'wp-easycart-pro' ),
				"required" 			=> false,
				"message" 			=> __( "Please select an Amazon S3 file name.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'select',
				/*"requires"=>array(
					"name"=>"is_amazon_download",
					"value"=>"1",
					"default_show"=> false
				),*/
				"data"				=> $amazonDir,
				"value"				=> $this->download->amazon_key
			),
			

			
		) );
		$this->print_fields( $fields );
	}
	
	public function getawsfiles( ){
		
		$returnArray = array( );
		
		if( ( get_option( 'ec_option_amazon_key' ) != '' && get_option( 'ec_option_amazon_key' ) != '0' ) && 
			( get_option( 'ec_option_amazon_secret' ) != '' && get_option( 'ec_option_amazon_secret' ) != '0' ) &&
			( get_option( 'ec_option_amazon_bucket' ) != '' && get_option( 'ec_option_amazon_bucket' ) != '0' ) ){
				
			//if( phpversion( ) >= 5.3 ){
				require_once( WP_PLUGIN_DIR . "/wp-easycart/inc/classes/account/ec_amazons3.php" );
				$amazons3 = new ec_amazons3( );
				$returnArray = $amazons3->get_aws_files( );
			//}else{
			//	$returnArray[] = "PHP 5.3+ Required";
			//}
			 
			//if( count( $returnArray ) > 0 ){
				return $returnArray;
			//}else{
			//	return array( "noresults" );
			//}
			
		//}else{
		//	return array( "noresults" );
		}
		
	}

	
}