<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_subscriptions extends wp_easycart_admin_details {

	public $subscription;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_subscription_details_basic_fields', array( $this, 'basic_fields' ) );
		add_action( 'wp_easycart_admin_subscription_details_subscription_terms_fields', array( $this, 'subscription_terms_fields' ) );
		add_action( 'wp_easycart_admin_subscription_details_product_info_fields', array( $this, 'product_info_fields' ) );
		add_action( 'wp_easycart_admin_subscription_details_customer_info_fields', array( $this, 'customer_info_fields' ) );

		add_action( 'wp_easycart_admin_subscription_details_stripe_info_fields', array( $this, 'stripe_info_fields' ) );
		add_action( 'wp_easycart_admin_subscription_details_paypal_info_fields', array( $this, 'paypal_info_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=subscriptions';
		$this->id = 0;
		$this->page = 'wp-easycart-orders';
		$this->subpage = 'subscriptions';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-subscription';
		$this->item = $this->subscription = (object) array(
			"subscription_id"					=> "",
			"subscription_type"					=> "",
			"subscription_status"				=> "",
			"number_payments_completed"			=> "",
			"num_failed_payment"				=> "",
			
			"payment_length"					=> "",
			"payment_period"					=> "",
			"start_date"						=> "",
			"last_payment_date" 				=> "",
			"next_payment_date"					=> "",
			
			"title"								=> "",
			"product_id"						=> "",
			"model_number"						=> "",
			"price"								=> "",
			
			"user_id"							=> "",
			"email"								=> "",
			"first_name"						=> "",
			"last_name"							=> "",
			"user_country"						=> "",

			"paypal_txn_id"						=> "",
			"paypal_txn_type"					=> "",
			"paypal_subscr_id"					=> "",
			"paypal_username"					=> "",
			"paypal_password"					=> "",
			
			"stripe_subscription_id"			=> "",
			"payment_duration"					=> "",
			"quantity"							=> "",
			
		);
	}

	protected function init_data( ){
		$this->form_action = 'update-subscription';
		$this->subscription = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_subscription.*, ec_user.stripe_customer_id FROM ec_subscription LEFT JOIN ec_user ON ec_user.user_id = ec_subscription.user_id WHERE subscription_id = %d", $_GET['subscription_id'] ) );
		$this->id = $this->subscription->subscription_id;
	}

	public function output( $type = 'edit' ){
		$this->init();
		if ( $type == 'edit' ) {
			$this->init_data();
		}
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR. 'admin/template/orders/subscriptions/subscriptions-details.php' );
	}

	public function basic_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_subscriptions_details_basic_fields_list', array(
			array(
				"name"				=> "subscription_id",
				"type"				=> "text",
				"label"				=> __( "Unique subscription ID", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter a unique subscription id.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->subscription_id
			),
			array(
				"name"				=> "subscription_type",
				"type"				=> "text",
				"label"				=> __( "Subscription Type", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->subscription_type
			),
			array(
				"name"				=> "subscription_status",
				"type"				=> "text",
				"label"				=> __( "Subscription Status", 'wp-easycart-pro' ),
				"required" 			=> false,
				"read-only"			=> true,
				"validation_type" 	=> 'text',
				"value"				=> $this->subscription->subscription_status
			),
			array(
				"name"				=> "number_payments_completed",
				"type"				=> "text",
				"label"				=> __( "Payments Completed", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->number_payments_completed
			),
			array(
				"name"				=> "num_failed_payment",
				"type"				=> "text",
				"label"				=> __( "Failed Payments", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->num_failed_payment
			),
		) );
		$this->print_fields( $fields );
	}

	public function isValidTimeStamp( $timestamp ) {
		return ( (string) (int) $timestamp === $timestamp ) && ( $timestamp <= PHP_INT_MAX ) && ( $timestamp >= ~PHP_INT_MAX );
	}

	public function subscription_terms_fields( ){
		$period = '';
		if ( $this->subscription->payment_period == 'W' ) {
			$period = 'Weekly';
		} else if ( $this->subscription->payment_period == 'M' ) {
			$period = 'Monthly';
		} else if ( $this->subscription->payment_period == 'Y' ){
			$period = 'Yearly';
		} else if ( $this->subscription->payment_period == 'D' ) {
			$period = 'Daily';
		} else if ( $this->subscription->payment_period == 'H' ) {
			$period = 'Hourly';
		}
		$fields = apply_filters( 'wp_easycart_admin_subscription_terms_fields_list', array(
			array(
				"name"				=> "payment_length",
				"type"				=> "text",
				"label"				=> __( "Payment Interval", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->payment_length
			),
			array(
				"name"				=> "payment_period",
				"type"				=> "text",
				"label"				=> __( "Payment Period", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $period
			),
			array(
				"name"				=> "start_date",
				"type"				=> "date",
				"label"				=> __( "Start Date", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'date',
				"read-only"			=> true,
				"value"				=> $this->subscription->start_date
			),
			array(
				"name"				=> "last_payment_date",
				"type"				=> "date",
				"label"				=> __( "Last Payment", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'date',
				"read-only"			=> true,
				"value"				=> $this->subscription->last_payment_date
			),
			array(
				"name"				=> "next_payment_date",
				"type"				=> "date",
				"label"				=> __( "Next Payment", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'date',
				"read-only"			=> true,
				"value"				=> $this->subscription->next_payment_date
			),
		) );
		$this->print_fields( $fields );
	}

	public function product_info_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_product_info_fields_list', array(
			array(
				"name"				=> "title",
				"type"				=> "text",
				"label"				=> __( "Product Title", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->title
			),
			array(
				"name"				=> "product_id",
				"type"				=> "text",
				"label"				=> __( "Product ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->product_id
			),
			array(
				"name"				=> "price",
				"type"				=> "currency",
				"label"				=> __( "Purchase Price", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'currency',
				"read-only"			=> true,
				"value"				=> $this->subscription->price
			),
		) );
		$this->print_fields( $fields );
	}

	public function customer_info_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_customer_info_fields_list', array(
			array(
				"name"				=> "user_id",
				"type"				=> "text",
				"label"				=> __( "User ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->user_id
			),
			array(
				"name"				=> "email",
				"type"				=> "text",
				"label"				=> __( "Email Address", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->email
			),
			array(
				"name"				=> "first_name",
				"type"				=> "text",
				"label"				=> __( "First Name", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->first_name
			),
			array(
				"name"				=> "last_name",
				"type"				=> "text",
				"label"				=> __( "Last Name", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->last_name
			),
			array(
				"name"				=> "user_country",
				"type"				=> "text",
				"label"				=> __( "Country", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->user_country
			),
		) );
		$this->print_fields( $fields );
	}

	public function stripe_info_fields( ){
		$fields = apply_filters( 'wp_easycart_admin_stripe_info_fields_list', array(
			array(
				"name"				=> "stripe_subscription_id",
				"type"				=> "text",
				"label"				=> __( "Subscription ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->stripe_subscription_id
			),
			array(
				"name"				=> "stripe_customer_id",
				"type"				=> "text",
				"label"				=> __( "Customer ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->stripe_customer_id
			),
			array(
				"name"				=> "payment_duration",
				"type"				=> "text",
				"label"				=> __( "Payment Duration", 'wp-easycart-pro' ),
				"required" 			=> false,
				"read-only"			=> true,
				"validation_type" 	=> 'text',
				"value"				=> $this->subscription->payment_duration
			),
			array(
				"name"				=> "quantity",
				"type"				=> "text",
				"label"				=> __( "Quantity", 'wp-easycart-pro' ),
				"required" 			=> false,
				"read-only"			=> true,
				"validation_type" 	=> 'text',
				"value"				=> $this->subscription->quantity
			),
		) );
		$this->print_fields( $fields );
	}
	
	public function paypal_info_fields( ){

		$fields = apply_filters( 'wp_easycart_admin_paypal_info_fields_list', array(
			array(
				"name"				=> "paypal_txn_id",
				"type"				=> "text",
				"label"				=> __( "PayPal Txn ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->paypal_txn_id
			),
			array(
				"name"				=> "paypal_txn_type",
				"type"				=> "text",
				"label"				=> __( "PayPal Txn Type", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->paypal_txn_type
			),
			array(
				"name"				=> "paypal_subscr_id",
				"type"				=> "text",
				"label"				=> __( "Subscriber ID", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"read-only"			=> true,
				"value"				=> $this->subscription->paypal_subscr_id
			),
		) );
		$this->print_fields( $fields );
	}
}
