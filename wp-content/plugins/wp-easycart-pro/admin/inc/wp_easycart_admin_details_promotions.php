<?php
if( !defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_promotions extends wp_easycart_admin_details{
	
	public $promotion;
	public $item;
	
	public function __construct( ){
		parent::__construct( );
		add_action( 'wp_easycart_admin_promotion_details_stepone_fields', array( $this, 'stepone_fields' ) );
		add_action( 'wp_easycart_admin_promotion_details_steptwo_fields', array( $this, 'steptwo_fields' ) );
		add_action( 'wp_easycart_admin_promotion_details_stepthree_fields', array( $this, 'stepthree_fields' ) );
		add_action( 'wp_easycart_admin_promotion_details_stepfour_fields', array( $this, 'stepfour_fields' ) );
	}
	
	protected function init( ){
		$this->id = 0;
		$this->page = 'wp-easycart-rates';
		$this->subpage = 'promotions';
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=promotions';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-promotion';
		$this->item = $this->promotion = (object) array(
			"promotion_id"					=> "",
			"name"							=> "",
			"type"							=> "",
			"start_date"					=> "",
			"end_date"						=> "",
			"product_id_1"					=> "",
			"product_id_2"					=> "",
			"product_id_3"					=> "",
			"manufacturer_id_1"				=> "",
			"manufacturer_id_2"				=> "",
			"manufacturer_id_3"				=> "",
			"category_id_1"					=> "",
			"category_id_2"					=> "",
			"category_id_3"					=> "",
			"price1"						=> "",
			"price2"						=> "",
			"price3"						=> "",
			"percentage1"					=> "",
			"percentage2"					=> "",
			"percentage3"					=> "",
			"number1"						=> "",
			"number2"						=> "",	
			"number3"						=> ""	
		);
	}
	
	protected function init_data( ){
		$this->form_action = 'update-promotion';
		$this->item = $this->promotion = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_promotion.* FROM ec_promotion WHERE promotion_id = %s", $_GET['promotion_id'] ) );
		$this->id = $this->promotion->promotion_id;
	}

	public function output( $type = 'edit' ){
		$this->init( );
		if( $type == 'edit' )
			$this->init_data( );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/marketing/promotions/promotion-details.php');
	}
	
	public function stepone_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_coupon_details_stepone_fields_list', array(
			
			array(
				"panel"	=> "basic",
				"name"	=> "promotion_id",
				"alt_name"	=> "promotion_id",
				"type"	=> "hidden",
				"value" => $this->promotion->promotion_id
			),
			array(
				"panel"	=> "basic",
				"name"	=> "name",
				"type"	=> "text",
				"label"	=> __( "Promotion Name", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a unique name for this promotion.", 'wp-easycart-pro' ),
				"validation_type" => 'text',
				"value" => $this->promotion->name
			),
			array(
				"panel"	=> "basic",
				"name"	=> "start_date",
				"type"	=> "date",
				"label"	=> __( "Promotion Start Date", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a date this promotion will begin.", 'wp-easycart-pro' ),
				"validation_type" => 'date',
				"value" => $this->promotion->start_date
			),
			array(
				"panel"	=> "basic",
				"name"	=> "end_date",
				"type"	=> "date",
				"label"	=> __( "Promotion End Date", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a date this promotion will end.", 'wp-easycart-pro' ),
				"validation_type" => 'date',
				"value" => $this->promotion->end_date
			)
			
		) );
		$this->print_fields( $fields );
	}
	
	public function steptwo_fields( ){
		$promotion_type = array( 
			(object) array( 
				"id" => 1, 
				"value" => __( "Price/Percentage off a product or group of products", 'wp-easycart-pro' )
			),
			(object) array( 
				"id" => 6, 
				"value" => __( "Price/Percentage off when certain dollar value reached", 'wp-easycart-pro' )
			),
			(object) array( 
				"id" => 8, 
				"value" => __( "Price/Percentage off when certain number items in cart", 'wp-easycart-pro' )
			),
			(object) array( 
				"id" => 9, 
				"value" => __( "BOGO Promotion (Buy 1 Get 1 Price/Percentage Off)", 'wp-easycart-pro' )
			),
			(object) array( 
				"id" => 4, 
				"value" => __( "Shipping discount when certain dollar value reached", 'wp-easycart-pro' )
			),
			(object) array( 
				"id" => 7, 
				"value" => __( "Free Shipping on Selected Products", 'wp-easycart-pro' )
			)
			
		);
		
		$fields = apply_filters( 'wp_easycart_admin_coupon_details_steptwo_fields_list', array(
		
			array(
                "panel" => "basic",
                "name"	=> "type",
                "type"	=> "select",
                "data"	=> $promotion_type,
                "data_label" => __( "Choose a Promotion Type", 'wp-easycart-pro' ),
                "label" => __( "Promotion Type", 'wp-easycart-pro' ),
                "required" => true,
                "message" => __( "Please select a promotion type", 'wp-easycart-pro' ),
                "validation_type" => 'select',
                "onchange" => "ec_admin_promotion_type_change",
                "value" => $this->promotion->type

			),
            array(
				"panel"	=> "basic",
				"name"	=> "number2",
				"type"	=> "number",
				"step"	=> 1,
				"label"	=> __( "Max Redeemable per Order", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter the maximimum times this BOGO can apply.", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"requires"=>array(
					"name"=>"type",
					"value"=>"9",
					"default_show"=> false
				),
				"value" => $this->promotion->number2
		
			)
			
		) );
		$this->print_fields( $fields );
	}
	
	
	public function stepthree_fields( ){
		global $wpdb;
		$all_products = $wpdb->get_results( "SELECT ec_product.product_id AS id, ec_product.title AS value FROM ec_product ORDER BY title ASC" );
		$manufacturers = $wpdb->get_results( "SELECT ec_manufacturer.manufacturer_id AS id, ec_manufacturer.name AS value FROM ec_manufacturer ORDER BY name ASC" );
		$categories = $wpdb->get_results( "SELECT ec_category.category_id AS id, ec_category.category_name AS value FROM ec_category ORDER BY category_name ASC" );

		$fields = apply_filters( 'wp_easycart_admin_coupon_details_stepthree_fields_list', array(
		
			array(
				"panel"	=> "basic",
				"name"	=> "number1",
				"type"	=> "number",
				"step"	=> 1,
				"label"	=> __( "Quantity Required", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter how many products are needed to receive discount.", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"requires"=>array(
					"name"=>"type",
					"value"=>"8",
					"default_show"=> false
				),
				"value" => $this->promotion->number1
		
			),
			array(
				"panel"	=> "basic",
				"name"	=> "price2",
				"type"	=> "currency",
				"label"	=> __( "Price Threshold", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter a price that will trigger this promotion.", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"requires"=>array(
					"name"=>"type",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->promotion->price2
		
			),
			array(
				"panel" => "basic",
				"name"	=> "product_id_1",
				"type"	=> "select",
				"data"	=> $all_products,
				"data_label" => __( "No Product Selected", 'wp-easycart-pro' ),
				"label" => __( "Select Product", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please select a product", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"type",
					"value"=>array(1,9),
					"default_show"=> false
				),
				"onchange" => "ec_admin_promotion_reset_selections",
				"value" => $this->promotion->product_id_1
				
			),
			array(
				"panel" => "basic",
				"name"	=> "manufacturer_id_1",
				"type"	=> "select",
				"data"	=> $manufacturers,
				"data_label" => __( "No Manufacturer Selected", 'wp-easycart-pro' ),
				"label" => __( "Select Manufacturer", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please select a manufacturer", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"type",
					"value"=>array(1,9),
					"default_show"=> false
				),
				"onchange" => "ec_admin_promotion_reset_selections",
				"value" => $this->promotion->manufacturer_id_1
			),
			array(
				"panel" => "basic",
				"name"	=> "category_id_1",
				"type"	=> "select",
				"data"	=> $categories,
				"data_label" => __( "No Category Selected", 'wp-easycart-pro' ),
				"label" => __( "Select Category", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please select a category", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"type",
					"value"=>array(1,9),
					"default_show"=> false
				),
				"onchange" => "ec_admin_promotion_reset_selections",
				"value" => $this->promotion->category_id_1
			)
			
		) );
		$this->print_fields( $fields );
	}
	
	public function stepfour_fields( ){

		$fields = apply_filters( 'wp_easycart_admin_coupon_details_stepfour_fields_list', array(
		
			array(
				"panel"	=> "basic",
				"name"	=> "price1",
				"type"	=> "currency",
				"label"	=> __( "Price Discount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter how much customers will receive off.", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"onchange" => "ec_admin_promotion_reset_discount",
				"value" => $this->promotion->price1
		
			),
			array(
				"panel"	=> "basic",
				"name"	=> "percentage1",
				"type"	=> "text",
				"label"	=> __( "Percentage Discount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter how much customers will receive off.", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"onchange" => "ec_admin_promotion_reset_discount",
				"value" => $this->promotion->percentage1
		
			)
			
		) );
		$this->print_fields( $fields );
	}
	
	
}