<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_fee extends wp_easycart_admin_details {
	public $fee;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_fee_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=fees';
		$this->id = 0;
		$this->page = 'wp-easycart-settings';
		$this->subpage = 'fee';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-fee';
		$this->fee = (object) array(
			'fee_id' => '',
			'fee_label' => '',
			'fee_admin_description' => '',
			'fee_country' => '',
			'fee_state' => '',
			'fee_zip' => '',
			'fee_city' => '',
			'fee_category' => '',
			'fee_role' => '',
			'fee_zone' => '',
			'fee_payment_type' => '',
			'fee_type' => '1',
			'fee_rate' => '',
			'fee_price' => '',
			'fee_min' => '',
			'fee_max' => '',
		);
	}

	protected function init_data() {
		$this->form_action = 'update-fee';
		$this->fee = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_fee.* FROM ec_fee WHERE fee_id = %d", $_GET['fee_id'] ) );
		$this->id = $this->fee->fee_id;
	}

	public function output( $type = 'edit' ){
		$this->init();
		if ( 'edit' == $type ) {
			$this->init_data();
		}
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/fee/fee-details.php' );
	}

	public function basic_fields() {
		$countries = $this->wpdb->get_results( 'SELECT ec_country.iso2_cnt AS id, ec_country.name_cnt AS value FROM ec_country ORDER BY sort_order ASC, name_cnt ASC' );
		$states = $this->wpdb->get_results( 'SELECT ec_state.id_sta AS id, ec_state.name_sta AS value FROM ec_state LEFT JOIN ec_country ON ec_state.idcnt_sta = ec_country.id_cnt ORDER BY ec_country.name_cnt ASC, ec_state.name_sta ASC' );
		$categories = $this->wpdb->get_results( 'SELECT ec_category.category_id AS id, ec_category.category_name AS value FROM ec_category ORDER BY ec_category.priority DESC' );
		$user_roles = $this->wpdb->get_results( 'SELECT ec_role.role_label AS id, ec_role.role_label AS value FROM ec_role ORDER BY ec_role.role_label ASC' );
		$shipping_zones = $this->wpdb->get_results( 'SELECT ec_zone.zone_id AS id, ec_zone.zone_name AS value FROM ec_zone ORDER BY ec_zone.zone_name ASC' );
		$payment_methods = array(
			(object) array(
				'id' => 'card',
				'value' => esc_attr__( 'Card Payment', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'affirm',
				'value' => esc_attr__( 'Affirm (Stripe)', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'klarna',
				'value' => esc_attr__( 'Klarna (Stripe)', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'afterpay_clearpay',
				'value' => esc_attr__( 'Afterpay (Stripe)', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'third_party',
				'value' => esc_attr__( 'PayPal / Third Party', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'amazonpay',
				'value' => esc_attr__( 'AmazonPay', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'manual_bill',
				'value' => esc_attr__( 'Manual Payment', 'wp-easycart-pro' ),
			),
		);
		$fields = apply_filters( 'wp_easycart_admin_fee_details_basic_fields_list', array(
			array(
				'name' => 'fee_id',
				'alt_name' => 'fee_id',
				'type' => 'hidden',
				'value' => $this->fee->fee_id,
			),
			array(
				"name"				=> "fee_label",
				"type"				=> "text",
				"label"				=> __( "Flex-Fee Label", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "You must enter a label. This label is shown in the line item for this fee in the cart.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->fee->fee_label
			),
			array(
				"name"				=> "fee_country",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $countries,
				"multiple"			=> true,
				"label" 			=> __( "Apply Fee by Country (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> explode( ',', $this->fee->fee_country ),
			),
			array(
				"name"				=> "fee_state",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $states,
				"multiple"			=> true,
				"label" 		=> __( "Apply Fee by State (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> explode( ',', $this->fee->fee_state ),
			),
			array(
				"name"				=> "fee_city",
				"type"				=> "text",
				"label"				=> __( "Apply Fee by City (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->fee->fee_city
			),
			array(
				"name"				=> "fee_zip",
				"type"				=> "text",
				"label"				=> __( "Apply Fee by Zip (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->fee->fee_zip
			),
			array(
				"name"				=> "fee_category",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $categories,
				"multiple"			=> true,
				"label" 		=> __( "Apply Fee by Category (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> explode( ',', $this->fee->fee_category ),
			),
			array(
				"name"				=> "fee_role",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $user_roles,
				"multiple"			=> true,
				"label" 		=> __( "Apply Fee by User Role (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> explode( ',', $this->fee->fee_role ),
			),
			array(
				"name"				=> "fee_zone",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $shipping_zones,
				"multiple"			=> true,
				"label" 		=> __( "Apply Fee by Shipping Zone (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> explode( ',', $this->fee->fee_zone ),
			),
			array(
				"name"				=> "fee_payment_type",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $payment_methods,
				"multiple"			=> true,
				"label" 		=> __( "Apply Fee by Payment Type (optional)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> explode( ',', $this->fee->fee_payment_type ),
			),
			array(
				"name"				=> "fee_type",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"data"				=> array(
					(object) array(
						'id' => '1',
						'value' => 'This is a Rate Based Fee',
					),
					(object) array(
						'id' => '2',
						'value' => 'This is a Price Based Fee',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Choose Your Fee Type", 'wp-easycart-pro' ),
				"message"			=> __( "You must select a fee type.", 'wp-easycart-pro' ),
				"required" 			=> true,
				"value" 			=> $this->fee->fee_type
			),
			array(
				"name"				=> "fee_rate",
				"type"				=> "number",
				"label"				=> __( "Fee Percentage", 'wp-easycart-pro' ),
				"min"				=> "-100",
				"max"				=> "100",
				"step"				=> ".01",
				"required" 			=> false,
				"validation_type" 	=> 'number',
				"value"				=> $this->fee->fee_rate,
				"requires"			=> array(
					"name"			=> "fee_type",
					"value"			=> "1",
					"default_show"	=> false
				),
			),
			array(
				"name"				=> "fee_price",
				"type"				=> "number",
				"label"				=> __( "Fee Price", 'wp-easycart-pro' ),
				"step"				=> ".01",
				"required" 			=> false,
				"validation_type" 	=> 'number',
				"value"				=> $this->fee->fee_price,
				"requires"			=> array(
					"name"			=> "fee_type",
					"value"			=> "2",
					"default_show"	=> false
				),
			),
			array(
				"name"				=> "fee_min",
				"type"				=> "number",
				"label"				=> __( "Fee Minimum Charge", 'wp-easycart-pro' ),
				"step"				=> ".01",
				"required" 			=> false,
				"validation_type" 	=> 'number',
				"value"				=> $this->fee->fee_min,
				"requires"			=> array(
					"name"			=> "fee_type",
					"value"			=> "2",
					"default_show"	=> false
				),
			),
			array(
				"name"				=> "fee_max",
				"type"				=> "number",
				"label"				=> __( "Fee Maximum Charge", 'wp-easycart-pro' ),
				"step"				=> ".01",
				"required" 			=> false,
				"validation_type" 	=> 'number',
				"value"				=> $this->fee->fee_max,
				"requires"			=> array(
					"name"			=> "fee_type",
					"value"			=> "2",
					"default_show"	=> false
				),
			)
		) );
		$this->print_fields( $fields );
	}
}
