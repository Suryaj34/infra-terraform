<?php
if( !defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_giftcard extends wp_easycart_admin_details{

	public $giftcard;
	public $order_id;
	public $item;

	public function __construct( ){
		parent::__construct( );
		add_action( 'wp_easycart_admin_giftcard_details_basic_fields', array( $this, 'basic_fields' ) );
	}
	
	protected function init( ){
		$this->id = 0;
        $this->order_id = 0;
		$this->page = 'wp-easycart-rates';
		$this->subpage = 'gift-cards';
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=gift-cards';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-gift-card';
		$this->giftcard = (object) array(
			"giftcard_id"					=> "",
			"amount"						=> "",
			"message"						=> ""
		);
	}
	
	protected function init_data( ){
		$this->form_action = 'update-gift-card';
		$this->giftcard = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_giftcard.* FROM ec_giftcard WHERE giftcard_id = %s", $_GET['giftcard_id'] ) );
		$this->id = $this->giftcard->giftcard_id;
        $this->order_id = $this->wpdb->get_var( $this->wpdb->prepare("SELECT ec_orderdetail.order_id FROM ec_orderdetail WHERE giftcard_id = %s", $this->giftcard->giftcard_id ) );
	}

	public function output( $type = 'edit' ){
		$this->init( );
		if( $type == 'edit' )
			$this->init_data( );
		
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/marketing/giftcards/giftcard-details.php' );
	}
	
	public function basic_fields( ){
		global $wpdb;
		$order_id = $wpdb->get_var( $wpdb->prepare("SELECT ec_orderdetail.order_id FROM ec_orderdetail WHERE giftcard_id = %s",$this->giftcard->giftcard_id ) );
		$fields = apply_filters( 'wp_easycart_admin_giftcard_details_basic_fields_list', array(
			array(
				"name"	=> "giftcard_id",
				"type"	=> "text",
				"label"	=> __( "Gift Card ID", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a 12 digit/character Gift Card ID", 'wp-easycart-pro' ),
				"validation_type" => 'gift-card',
				"value" => $this->giftcard->giftcard_id
			),
			array(
				"name"	=> "amount",
				"type"	=> "currency",
				"label"	=> __( "Amount on Gift Card", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a Gift Card amount", 'wp-easycart-pro' ),
				"validation_type" => 'number',
				"value" => $this->giftcard->amount
			),
			array(
				"name"	=> "message",
				"type"	=> "textarea",
				"label"	=> __( "Custom Message from User", 'wp-easycart-pro' ),
				"required" => true,
				"message" => __( "Please enter a message for the customer", 'wp-easycart-pro' ),
				"validation_type" => 'text',
				"value" => $this->giftcard->message
			)
		) );
		$this->print_fields( $fields );
	}
	
}