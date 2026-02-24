<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_subscription_plans extends wp_easycart_admin_details{

	public $subscription_plan;
	public $item;

	public function __construct( ){
		parent::__construct( );
		add_action( 'wp_easycart_admin_subscription_plans_details_basic_fields', array( $this, 'basic_fields' ) );

	}
	
	protected function init( ){
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=subscription-plans';
		$this->id = 0;
		$this->page = 'wp-easycart-products';
		$this->subpage = 'subscriptionplans';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-subscription-plan';
		$this->subscription_plan = (object) array(
			"subscription_plan_id"		=> "",
			"plan_title"				=> "",
			"can_downgrade"				=> ""
		);

	}
	
	protected function init_data( ){
		$this->form_action = 'update-subscription-plan';
		$this->subscription_plan = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_subscription_plan.* FROM ec_subscription_plan WHERE subscription_plan_id = %d", $_GET['subscription_plan_id'] ) );
		$this->id = $this->subscription_plan->subscription_plan_id;

		
	}

	public function output( $type = 'edit' ){
		$this->init( );
		if( $type == 'edit' )
			$this->init_data( );
		
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/products/subscriptions/subscription-plans-details.php' );
	}
	
	public function basic_fields( ){

		$fields = apply_filters( 'wp_easycart_admin_subscription_plans_details_basic_fields_list', array(
			array(
				"name"				=> "subscription_plan_id",
				"alt_name"			=> "subscription_plan_id",
				"type"				=> "hidden",
				"value"				=> $this->subscription_plan->subscription_plan_id
			),
			array(
				"name"				=> "plan_title",
				"type"				=> "text",
				"label"				=> __( "Subscription Plan Title", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter a title for this subscription plan.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->subscription_plan->plan_title
			),
			array(
				"name"				=> "can_downgrade",
				"type"				=> "checkbox",
				"label"				=> __( "Can Upgrade/Downgrade Plan", 'wp-easycart-pro' ),
				"required" 			=> false,
				"message" 			=> __( "Please select whether user can upgrade/downgrade within this plan.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'checkbox',
				"value"				=> $this->subscription_plan->can_downgrade
			)

			
		) );
		$this->print_fields( $fields );
	}

	
}