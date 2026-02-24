<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_products_pro extends wp_easycart_admin_details {

	public $product;
	public $item;

	public function __construct() {
		parent::__construct();
		
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=products';
		$this->id = '0';
		$this->page = 'wp-easycart-products';
		$this->subpage = 'products';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;

		if ( isset( $_GET['pagenum'] ) ) {
			$this->action .= '&pagenum=' . (int) $_GET['pagenum'];
		}
		if ( isset( $_GET['orderby'] ) ){
			$this->action .= '&orderby=' . sanitize_key( $_GET['orderby'] );
		}
		if ( isset( $_GET['order'] ) ){
			$this->action .= '&order=' . sanitize_key( $_GET['order'] );
		}
		$this->form_action = 'update-product';
		$this->product = $this->item = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT 
				ec_product.*,
				" . $this->wpdb->prefix . "posts.guid,
				" . $this->wpdb->prefix . "posts.post_excerpt
			FROM 
				ec_product 
				LEFT JOIN " . $this->wpdb->prefix . "posts ON " .$this->wpdb->prefix . "posts.ID = ec_product.post_id 
			WHERE product_id = %d", (int) $_GET['product_id']
		) );
		$this->id = $this->product->product_id;
	}
	
	public function print_google_merchant_fields() {
		global $wpdb;
		$fields = array(
			array(
				"name"				=> 'gm_enabled',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'yes',
						'value' => 'Yes'
					),
					(object) array(
						'id' => 'no',
						'value' => 'No'
					),
				),
				"data_label" 		=> __( "Please Select a Yes or No", 'wp-easycart-pro' ),
				"label"				=> __( "Enabled", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'yes',
				"default_value"		=> 'yes',
			),
			array(
				"name"				=> 'gm_title',
				"type"				=> "text",
				"label"				=> __( "Product Title", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> $this->product->title,
			),
			array(
				"name"				=> 'gm_google_product_category',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> wp_easycart_admin_google_merchant_pro()->google_merchant_categories,
				"data_label" 		=> __( "Please Select a Product Category", 'wp-easycart-pro' ),
				"label"				=> __( "Google Product Category", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"message" 			=> __( "Please select a Product Category.", 'wp-easycart-pro' ),
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_product_type',
				"type"				=> "text",
				"label"				=> __( "Product Type", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_identifier_exists',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'yes',
						'value' => 'Yes'
					),
					(object) array(
						'id' => 'no',
						'value' => 'No'
					),
				),
				"data_label" 		=> __( "Please Select a Yes or No", 'wp-easycart-pro' ),
				"label"				=> __( "Identifier Exists", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'no',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_gtin',
				"type"				=> "text",
				"label"				=> __( "GTIN", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_mpn',
				"type"				=> "text",
				"label"				=> __( "MPN", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_availability',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'in_stock',
						'value' => 'In stock',
					),
					(object) array(
						'id' => 'out_of_stock',
						'value' => 'Out of Stock',
					),
					(object) array(
						'id' => 'preorder',
						'value' => 'Preorder',
					),
					(object) array(
						'id' => 'backorder',
						'value' => 'Backorder',
					),
				),
				"data_label" 		=> __( "Use current stock settings", 'wp-easycart-pro' ),
				"label"				=> __( "Availability", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_condition',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'new',
						'value' => 'New',
					),
					(object) array(
						'id' => 'refurbished',
						'value' => 'Refurbished',
					),
					(object) array(
						'id' => 'used',
						'value' => 'Used',
					),
				),
				"data_label" 		=> __( "Select a Condition", 'wp-easycart-pro' ),
				"label"				=> __( "Product Condition", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'new',
				"default_value"		=> '',
			),
			array(
				"name"	=> "gm_availability_date",
				"type"	=> "date",
				"label"	=> __( "Availability Date (preorders and backorders)", 'wp-easycart-pro' ),
				"max"	=> date( 'Y-m-d', strtotime( "+1 year" ) ),
				"min"	=> date( 'Y-m-d', strtotime( "today" ) ),
				"required" => false,
				"message" => __( "Availability date must be within 1 year of today.", 'wp-easycart-pro' ),
				"validation_type" => 'date',
				"value" => '',
			),
			array(
				"name"	=> "gm_expiration_date",
				"type"	=> "date",
				"label"	=> __( "Expiration Date (date to remove)", 'wp-easycart-pro' ),
				"max"	=> date( 'Y-m-d', strtotime( "+30 days" ) ),
				"min"	=> date( 'Y-m-d', strtotime( "today" ) ),
				"required" => false,
				"message" => __( "Expiration date must be within 30 days.", 'wp-easycart-pro' ),
				"validation_type" => 'date',
				"value" => '',
			),
			array(
				"name"				=> 'gm_gender',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'male',
						'value' => 'Male',
					),
					(object) array(
						'id' => 'female',
						'value' => 'Female',
					),
					(object) array(
						'id' => 'unisex',
						'value' => 'Unisex',
					),
				),
				"data_label" 		=> __( "No Selection Required", 'wp-easycart-pro' ),
				"label"				=> __( "Gender", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_age_group',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'newborn',
						'value' => 'Newborn',
					),
					(object) array(
						'id' => 'infant',
						'value' => 'Infant',
					),
					(object) array(
						'id' => 'toddler',
						'value' => 'Toddler',
					),
					(object) array(
						'id' => 'kids',
						'value' => 'Kids',
					),
					(object) array(
						'id' => 'adult',
						'value' => 'Adult',
					),
				),
				"data_label" 		=> __( "No Selection Required", 'wp-easycart-pro' ),
				"label"				=> __( "Age Group", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_size_type',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'regular',
						'value' => 'Regular',
					),
					(object) array(
						'id' => 'petite',
						'value' => 'Petite',
					),
					(object) array(
						'id' => 'plus',
						'value' => 'Plus',
					),
					(object) array(
						'id' => 'tall',
						'value' => 'Tall',
					),
					(object) array(
						'id' => 'big',
						'value' => 'Big',
					),
					(object) array(
						'id' => 'maternity',
						'value' => 'Maternity',
					),
				),
				"data_label" 		=> __( "No Selection Required", 'wp-easycart-pro' ),
				"label"				=> __( "Size Type", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_size_system',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'AU',
						'value' => 'AU',
					),
					(object) array(
						'id' => 'BR',
						'value' => 'BR',
					),
					(object) array(
						'id' => 'CN',
						'value' => 'CN',
					),
					(object) array(
						'id' => 'DE',
						'value' => 'DE',
					),
					(object) array(
						'id' => 'EU',
						'value' => 'EU',
					),
					(object) array(
						'id' => 'FR',
						'value' => 'FR',
					),
					(object) array(
						'id' => 'IT',
						'value' => 'IT',
					),
					(object) array(
						'id' => 'JP',
						'value' => 'JP',
					),
					(object) array(
						'id' => 'MEX',
						'value' => 'MEX',
					),
					(object) array(
						'id' => 'UK',
						'value' => 'UK',
					),
					(object) array(
						'id' => 'US',
						'value' => 'US',
					),
				),
				"data_label" 		=> __( "No Selection Required", 'wp-easycart-pro' ),
				"label"				=> __( "Size System", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_item_group_id',
				"type"				=> "text",
				"label"				=> __( "Item Group ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> $this->product->model_number,
			),
			array(
				"name"				=> 'gm_color',
				"type"				=> "text",
				"label"				=> __( "Color", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_material',
				"type"				=> "text",
				"label"				=> __( "Material", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_pattern',
				"type"				=> "text",
				"label"				=> __( "Pattern", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_size',
				"type"				=> "text",
				"label"				=> __( "Size", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_weight_type',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'lb',
						'value' => 'Lbs',
					),
					(object) array(
						'id' => 'oz',
						'value' => 'Ozs',
					),
					(object) array(
						'id' => 'g',
						'value' => 'Grams',
					),
					(object) array(
						'id' => 'kg',
						'value' => 'KGs',
					),
				),
				"data_label" 		=> __( "Select a weight type", 'wp-easycart-pro' ),
				"label"				=> __( "Weight Type", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'lb',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_shipping_weight',
				"type"				=> "number",
				"step"				=> 1,
				"min"				=> 1,
				"label"				=> __( "Shipping Weight", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_unit_pricing_base_measure',
				"type"				=> "text",
				"label"				=> __( "Unit Pricing Base Measure", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_unit_pricing_measure',
				"type"				=> "text",
				"label"				=> __( "Unit Pricing Measure", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_shipping_label',
				"type"				=> "text",
				"label"				=> __( "Shipping Label", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_shipping_unit',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'in',
						'value' => 'Inch',
					),
					(object) array(
						'id' => 'cm',
						'value' => 'CM',
					),
				),
				"data_label" 		=> __( "Select a shipping unit", 'wp-easycart-pro' ),
				"label"				=> __( "Shipping Unit", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'in',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_shipping_length',
				"type"				=> "number",
				"step"				=> 1,
				"min"				=> 1,
				"label"				=> __( "Shipping Length", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_shipping_width',
				"type"				=> "number",
				"step"				=> 1,
				"min"				=> 1,
				"label"				=> __( "Shipping Width", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_shipping_height',
				"type"				=> "number",
				"step"				=> 1,
				"min"				=> 1,
				"label"				=> __( "Shipping Height", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_min_handling_time',
				"type"				=> "number",
				"step"				=> 1,
				"min"				=> 1,
				"label"				=> __( "Min Handling Time (in days)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_max_handling_time',
				"type"				=> "number",
				"step"				=> 1,
				"min"				=> 1,
				"label"				=> __( "Max Handling Time (in days)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_adult',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'yes',
						'value' => 'Yes',
					),
					(object) array(
						'id' => 'no',
						'value' => 'No',
					),
				),
				"data_label" 		=> __( "Select yes or no", 'wp-easycart-pro' ),
				"label"				=> __( "Is Adult Content?", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'no',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_multipack',
				"type"				=> "text",
				"label"				=> __( "Multipack (review use cases before adding!)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_is_bundle',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'yes',
						'value' => 'Yes',
					),
					(object) array(
						'id' => 'no',
						'value' => 'No',
					),
				),
				"data_label" 		=> __( "Select yes or no", 'wp-easycart-pro' ),
				"label"				=> __( "Is a Bundle?", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> 'no',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_certification',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'yes',
						'value' => 'Yes',
					),
					(object) array(
						'id' => '',
						'value' => 'No',
					),
				),
				"data_label" 		=> __( "Select yes or no", 'wp-easycart-pro' ),
				"label"				=> __( "Add Certification Info (EC/EPREL)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_certification_code',
				"type"				=> "text",
				"label"				=> __( "Certification Code (EC/EPREL)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> ''
			),
			array(
				"name"				=> 'gm_energy_efficiency_class',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'A+++',
						'value' => 'A+++',
					),
					(object) array(
						'id' => 'A++',
						'value' => 'A++',
					),
					(object) array(
						'id' => 'A+',
						'value' => 'A+',
					),
					(object) array(
						'id' => 'A',
						'value' => 'A',
					),
					(object) array(
						'id' => 'B',
						'value' => 'B',
					),
					(object) array(
						'id' => 'C',
						'value' => 'C',
					),
					(object) array(
						'id' => 'D',
						'value' => 'D',
					),
					(object) array(
						'id' => 'E',
						'value' => 'E',
					),
					(object) array(
						'id' => 'F',
						'value' => 'F',
					),
					(object) array(
						'id' => 'G',
						'value' => 'G',
					),
				),
				"data_label" 		=> __( "No Energy Effeciency Class", 'wp-easycart-pro' ),
				"label"				=> __( "Energy Effeciency Class", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_min_energy_efficiency_class',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'A+++',
						'value' => 'A+++',
					),
					(object) array(
						'id' => 'A++',
						'value' => 'A++',
					),
					(object) array(
						'id' => 'A+',
						'value' => 'A+',
					),
					(object) array(
						'id' => 'A',
						'value' => 'A',
					),
					(object) array(
						'id' => 'B',
						'value' => 'B',
					),
					(object) array(
						'id' => 'C',
						'value' => 'C',
					),
					(object) array(
						'id' => 'D',
						'value' => 'D',
					),
					(object) array(
						'id' => 'E',
						'value' => 'E',
					),
					(object) array(
						'id' => 'F',
						'value' => 'F',
					),
					(object) array(
						'id' => 'G',
						'value' => 'G',
					),
				),
				"data_label" 		=> __( "No Minimum Energy Effeciency Class", 'wp-easycart-pro' ),
				"label"				=> __( "Minimum Energy Effeciency Class", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
			array(
				"name"				=> 'gm_max_energy_efficiency_class',
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> array(
					(object) array(
						'id' => 'A+++',
						'value' => 'A+++',
					),
					(object) array(
						'id' => 'A++',
						'value' => 'A++',
					),
					(object) array(
						'id' => 'A+',
						'value' => 'A+',
					),
					(object) array(
						'id' => 'A',
						'value' => 'A',
					),
					(object) array(
						'id' => 'B',
						'value' => 'B',
					),
					(object) array(
						'id' => 'C',
						'value' => 'C',
					),
					(object) array(
						'id' => 'D',
						'value' => 'D',
					),
					(object) array(
						'id' => 'E',
						'value' => 'E',
					),
					(object) array(
						'id' => 'F',
						'value' => 'F',
					),
					(object) array(
						'id' => 'G',
						'value' => 'G',
					),
				),
				"data_label" 		=> __( "No Max Energy Effeciency Class", 'wp-easycart-pro' ),
				"label"				=> __( "Max Energy Effeciency Class", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"visible"			=> true,
				"value"				=> '',
				"default_value"		=> '',
			),
		);
		$merchant_data = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_value FROM ec_product_google_attributes WHERE product_id = %d", $this->product->product_id ) );
		if ( $merchant_data ) {
			$merchant_rows = json_decode( $merchant_data );
			foreach ( $merchant_rows as $name => $value ) {
				$fields_count = count( $fields );
				for ( $i = 0; $i < $fields_count; $i++ ) {
					if ( $fields[$i]['name'] == 'gm_' . $name ) {
						$fields[$i]['value'] = $value;
					}
				}
			}

		}
		$fields = apply_filters( 'wp_easycart_admin_product_details_google_merchant_fields_list', $fields );
		$this->print_fields( $fields );
	}
}
