<?php
if( !defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_coupons extends wp_easycart_admin_details{

	public $coupon;
	public $item;

	public function __construct( ){
		parent::__construct( );
		add_action( 'wp_easycart_admin_coupon_details_stepone_fields', array( $this, 'stepone_fields' ) );
		add_action( 'wp_easycart_admin_coupon_details_steptwo_fields', array( $this, 'steptwo_fields' ) );
		add_action( 'wp_easycart_admin_coupon_details_stepthree_fields', array( $this, 'stepthree_fields' ) );
		add_action( 'wp_easycart_admin_coupon_details_stepfour_fields', array( $this, 'stepfour_fields' ) );
	}
	
	protected function init( ){
		$this->id = 0;
		$this->page = 'wp-easycart-rates';
		$this->subpage = 'coupons';
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=coupons';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-coupon';
		$this->coupon = $this->item = (object) array(
			'promocode_id' => '',
			'message' => '',
			'times_redeemed' => '',
			'max_redemptions' => '',
			'expiration_date' => '',

			'is_dollar_based' => '',
			'is_percentage_based' => '',
			'is_shipping_based' => '',
			'is_free_item_based' => '',
			'is_for_me_based' => '',
			'is_bogo_based' => '',

			'by_manufacturer_id' => '',
			'by_product_id' => '',
			'by_all_products' => '',
			'by_category_id' => '',

			'promo_dollar' => '',
			'promo_percentage' => '',
			'promo_shipping' => '',
			'promo_free_item' => '0.00',
			'promo_for_me' => '',
			'promo_bogo_dollar' => '',
			'promo_bogo_percentage' => '',

			'manufacturer_id' => '',
			'product_id' => '',
			'category_id' => '',

			'first_order_only' => 0,
			'apply_to_shipping' => false,
			'minimum_required' => '',

			'duration' => 'forever',
			'duration_in_months' => '1',
		);
	}
	
	protected function init_data( ){
		$this->form_action = 'update-coupon';
		$this->item = $this->coupon = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_promocode.* FROM ec_promocode WHERE promocode_id = %s", $_GET['promocode_id'] ) );
		$this->id = $this->coupon->promocode_id;
	}

	public function output( $type = 'edit' ){
		$this->init( );
		if( $type == 'edit' )
			$this->init_data( );
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/marketing/coupons/coupon-details.php' );
	}
	
	public function stepone_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_coupon_details_stepone_fields_list', array(
			array(
				"panel"	=> "basic",
				"name"	=> "promocode_id",
				"type"	=> "text",
				"label"	=> __( "Coupon Code", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a unique Coupon code", 'wp-easycart-pro' ),
				"validation_type" => 'text',
				"value" => $this->coupon->promocode_id
			),
			array(
				"panel"	=> "basic",
				"name"	=> "message",
				"type"	=> "text",
				"label"	=> __( "Customer Message", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a unique message to show user upon entering coupon code", 'wp-easycart-pro' ),
				"validation_type" => 'text',
				"value" => $this->coupon->message
			),
			array(
				"panel"	=> "basic",
				"name"	=> "max_redemptions",
				"type"	=> "number",
				"label"	=> __( "Maximum Redemptions", 'wp-easycart-pro' ),
				"decimals" => 0,
				"step"  => 1,
				"required" => true,
				"message" => __( "Please enter the maximum times this coupon may be used.  999 is unlimited", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"value" => $this->coupon->max_redemptions
			),
			array(
				"panel"	=> "basic",
				"name"	=> "times_redeemed",
				"type"	=> "number",
				"label"	=> __( "Times Redeemed", 'wp-easycart-pro' ),
				"decimals" => 0,
				"step"  => 1,
				"read-only" => true,
				"required" => false,
				"message" => "",
				"value" => $this->coupon->times_redeemed
			),
			array(
				"panel"	=> "basic",
				"name"	=> "expiration_date",
				"type"	=> "date",
				"label"	=> __( "Expiration Date", 'wp-easycart-pro' ),
				"max"	=> date( 'Y-m-d', strtotime( "+5 year" ) ),
				"required" => true,
				"message" => __( "Expiration date required, max 5 years in the future", 'wp-easycart-pro' ),
				"validation_type" => 'date',
				"value" => $this->coupon->expiration_date
			),
			array(
				"panel"	=> "basic",
				"name"	=> "duration",
				"type"	=> "select",
				"data"	=> array(
					(object) array(
						"id"	=> "forever",
						"value"	=> __( "Forever (Applies to all recurring payments)", 'wp-easycart-pro' )
					),
					(object) array(
						"id"	=> "once",
						"value"	=> __( "Once (Applies only to first payment)", 'wp-easycart-pro' )
					),
					(object) array(
						"id"	=> "repeating",
						"value"	=> __( "Apply to Some Limited Number of Months", 'wp-easycart-pro' )
					)
				),
				"data_label" => __( "Please Select a Duration", 'wp-easycart-pro' ),
				"label"		=> __( "Duration (Applies to Subscriptions Only)", 'wp-easycart-pro' ),
				"required" 	=> true,
				"message" => __( "Choose a coupon duration even if this does not apply to a subscription", 'wp-easycart-pro' ),
				"validation_type" => 'select',
				"show"  	=> array(
					"name" 	=> "duration_in_months",
					"value"	=> "repeating"
				),
				"value" => $this->coupon->duration
			),
			array(
				"panel"		=> "basic",
				"name"		=> "duration_in_months",
				"type"		=> "number",
				"label"		=> __( "Duration in Months", 'wp-easycart-pro' ),
				"required" 	=> false,
				"requires"	=> array(
					array(
						"name"			=> "duration",
						"value"			=> "repeating",
						"default_show"	=> false
					)
				),
				"value" 	=> $this->coupon->duration_in_months
			)
		) );
		$this->print_fields( $fields );
	}
	
	public function steptwo_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_coupon_details_steptwo_fields_list', array(
		
			array(
				"panel"	=> "basic",
				"name"	=> "is_dollar_based",
				"type"	=> "checkbox",
				"label"	=> __( "Dollar Based Coupon?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"onclick" => "ec_admin_coupon_type_change",
				"selected" => false,
				"show"  => array(
					"name" =>"promo_dollar",
					"value"=>"1"
				),
				"value" => $this->coupon->is_dollar_based
			),
			array(
				"panel"	=> "basic",
				"name"	=> "promo_dollar",
				"type"	=> "currency",
				"label"	=> __( "Dollar Amount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter a dollar amount this discount will apply", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"is_dollar_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_dollar
			),
			array(
				"panel"	=> "basic",
				"name"	=> "is_percentage_based",
				"type"	=> "checkbox",
				"label"	=> __( "Percentage Based Coupon?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"onclick" => "ec_admin_coupon_type_change",
				"selected" => false,
				"show"  => array(
					"name" =>"promo_percentage",
					"value"=>"1"
				),
				"value" => $this->coupon->is_percentage_based
			),
			array(
				"panel"	=> "basic",
				"name"	=> "promo_percentage",
				"type"	=> "currency",
				"label"	=> __( "Percentage Discount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter a percentage this discount will apply", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"is_percentage_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_percentage,
                "min"   => 0,
                "max"   => 100
			),
			array(
				"panel"	=> "basic",
				"name"	=> "is_shipping_based",
				"type"	=> "checkbox",
				"label"	=> __( "Shipping Based Coupon?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"onclick" => "ec_admin_coupon_type_change",
				"selected" => false,
				"show"  => array(
					"name" =>"promo_shipping",
					"value"=>"1"
				),
				"value" => $this->coupon->is_shipping_based
			),
			array(
				"panel"	=> "basic",
				"name"	=> "promo_shipping",
				"type"	=> "currency",
				"label"	=> __( "Shipping Discount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter a dollar amount this discount will apply toward shipping", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"is_shipping_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_shipping
			),
			array(
				"panel"	=> "basic",
				"name"	=> "is_free_item_based",
				"type"	=> "checkbox",
				"label"	=> __( "Free Item Based Coupon?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"onclick" => "ec_admin_coupon_type_change",
				"selected" => false,
				//"show"  => array(
					//"name" =>"promo_free_item",
					//"value"=>"1"
				//),
				"value" => $this->coupon->is_free_item_based
			),
			array(
				"panel"	=> "basic",
				"name"	=> "is_bogo_based",
				"type"	=> "checkbox",
				"label"	=> __( "Bogo Based Coupon?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"onclick" => "ec_admin_coupon_type_change",
				"selected" => false,
				//"show"  => array(
					//"name" =>"promo_free_item",
					//"value"=>"1"
				//),
				"value" => $this->coupon->is_bogo_based
			),
			array(
				"panel"	=> "basic",
				"name"	=> "promo_bogo_dollar",
				"type"	=> "currency",
				"label"	=> __( "Dollar Amount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter a dollar amount this discount will apply", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"is_bogo_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_bogo_dollar
			),
			array(
				"panel"	=> "basic",
				"name"	=> "promo_bogo_percentage",
				"type"	=> "currency",
				"label"	=> __( "Percentage Discount", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Please enter a percentage this discount will apply", 'wp-easycart-pro' ),
				"requires"=>array(
					"name"=>"is_bogo_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_bogo_percentage,
                "min"   => 0,
                "max"   => 100
			),
			/*array(
				"panel"	=> "basic",
				"name"	=> "promo_free_item",
				"type"	=> "currency",
				"label"	=> "Free Item Discount",
				"required" => false,
				"message" => "Please enter an amount",
				"requires"=>array(
					"name"=>"is_free_item_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_free_item
			),
			array(
				"panel"	=> "basic",
				"name"	=> "is_for_me_based",
				"type"	=> "checkbox",
				"label"	=> "For Me Based Coupon?",
				"required" => false,
				"message" => "",
				"onclick" => "ec_admin_coupon_type_change",
				"selected" => false,
				"show"  => array(
					"name" =>"promo_for_me",
					"value"=>"1"
				),
				"value" => $this->coupon->is_for_me_based
			),
			array(
				"panel"	=> "basic",
				"name"	=> "promo_for_me",
				"type"	=> "currency",
				"label"	=> "For Me Discount",
				"required" => false,
				"message" => "Please enter an amount",
				"requires"=>array(
					"name"=>"is_for_me_based",
					"value"=>"1",
					"default_show"=> false
				),
				"value" => $this->coupon->promo_for_me
			)*/
			
			
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
			"name"	=> "by_all_products",
			"type"	=> "checkbox",
			"label"	=> __( "Apply to All Products?", 'wp-easycart-pro' ),
			"onclick" => "ec_admin_coupon_apply_to",
			"required" => false,
			"message" => "",
			"selected" => false,
			"value" => $this->coupon->by_all_products
		),
		array(
			"panel"	=> "basic",
			"name"	=> "by_product_id",
			"type"	=> "checkbox",
			"label"	=> __( "Apply to only one product?", 'wp-easycart-pro' ),
			"required" => false,
			"message" => "",
			"onclick" => "ec_admin_coupon_apply_to",
			"selected" => false,
			"show"  => array(
				"name" =>"product_id",
				"value"=>"1"
			),
			"value" => $this->coupon->by_product_id
		),
		array(
			"panel" => "basic",
			"name"	=> "product_id",
			"type"	=> "select",
			"data"	=> $all_products,
			"data_label" => __( "Select a Product", 'wp-easycart-pro' ),
			"label" => __( "Select Product", 'wp-easycart-pro' ),
			"required" => false,
			"message" => __( "Please select a product", 'wp-easycart-pro' ),
			"requires"=>array(
				"name"=>"by_product_id",
				"value"=>"1",
				"default_show"=> false
			),
			"value" => $this->coupon->product_id
			
		),
		array(
			"panel"	=> "basic",
			"name"	=> "by_manufacturer_id",
			"type"	=> "checkbox",
			"label"	=> __( "Apply to a specific manufacturer?", 'wp-easycart-pro' ),
			"required" => false,
			"message" => "",
			"onclick" => "ec_admin_coupon_apply_to",
			"selected" => false,
			"show"  => array(
				"name" =>"manufacturer_id",
				"value"=>"1"
			),
			"value" => $this->coupon->by_manufacturer_id
		),
		array(
			"panel" => "basic",
			"name"	=> "manufacturer_id",
			"type"	=> "select",
			"data"	=> $manufacturers,
			"data_label" => __( "Please Select a Manufacturer", 'wp-easycart-pro' ),
			"label" => __( "Select Manufacturer", 'wp-easycart-pro' ),
			"required" => false,
			"message" => __( "Please select a manufacturer", 'wp-easycart-pro' ),
			"requires"=>array(
				"name"=>"by_manufacturer_id",
				"value"=>"1",
				"default_show"=> false
			),
			"value" => $this->coupon->manufacturer_id
		),
		array(
			"panel"	=> "basic",
			"name"	=> "by_category_id",
			"type"	=> "checkbox",
			"label"	=> __( "Apply to a specific Category?", 'wp-easycart-pro' ),
			"required" => false,
			"message" => "",
			"onclick" => "ec_admin_coupon_apply_to",
			"selected" => false,
			"show"  => array(
				"name" =>"category_id",
				"value"=>"1"
			),
			"value" => $this->coupon->by_category_id
		),
		array(
			"panel" => "basic",
			"name"	=> "category_id",
			"type"	=> "select",
			"data"	=> $categories,
			"data_label" => __( "Please select a Category", 'wp-easycart-pro' ),
			"label" => __( "Select Category", 'wp-easycart-pro' ),
			"required" => false,
			"message" => __( "Please select a category", 'wp-easycart-pro' ),
			"requires"=>array(
				"name"=>"by_category_id",
				"value"=>"1",
				"default_show"=> false
			),
			"value" => $this->coupon->category_id
		)
			
			
			
		) );
		$this->print_fields( $fields );
	}
	
	public function stepfour_fields( ){
		global $wpdb;
		$styles = array(
			array( 'float', 'none'	)
		);
		$hide_styles = array(
			array( 'display', ( ( $this->coupon->is_dollar_based || $this->coupon->is_percentage_based ) ? 'block' : 'none' ) )
		);
		$fields = apply_filters( 'wp_easycart_admin_coupon_details_stepthree_fields_list', array(
			array(
				"panel"	=> "basic",
				"name"	=> "first_order_only",
				"type"	=> "checkbox",
				"label"	=> __( "Apply to First Order Only?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"selected" => false,
				"value" => $this->coupon->first_order_only,
				"styles" => $hide_styles,
			),
			array(
				"panel"	=> "basic",
				"name"	=> "apply_to_shipping",
				"type"	=> "checkbox",
				"label"	=> __( "Apply Discount to Shipping?", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"selected" => false,
				"value" => $this->coupon->apply_to_shipping,
				"styles" => $hide_styles,
			),
			array(
				"panel"	=> "basic",
				"name"	=> "minimum_required",
				"type"	=> "number",
				"label"	=> __( "Minimum Products Required for Coupon to Apply", 'wp-easycart-pro' ),
				"required" => false,
				"message" => "",
				"selected" => false,
				"value" => $this->coupon->minimum_required,
				"styles" => $styles
			)
		) );
		$this->print_fields( $fields );
	}
	
	
}