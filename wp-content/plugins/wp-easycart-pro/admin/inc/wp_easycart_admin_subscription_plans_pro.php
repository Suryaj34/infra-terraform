<?php
class wp_easycart_admin_subscription_plans_pro {

	public $subscription_plans_list_file;
	public $subscription_plans_details_file;

	public function __construct() {
		$this->subscription_plans_list_file 	= WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/template/products/subscriptions/subscription-plans-list.php';
		if ( wp_easycart_admin_license( )->is_licensed( ) ) {
			remove_action( 'wp_easycart_admin_subscription_plans_list', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			remove_action( 'wp_easycart_admin_subscription_plans_details', array( wp_easycart_admin( ), 'show_upgrade', 1 ) );
			add_action( 'wp_easycart_admin_subscription_plans_list', array( $this, 'show_list' ), 1 );
			add_action( 'wp_easycart_admin_subscription_plans_details', array( $this, 'show_details' ), 1 );

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_subscription_plan' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_subscription_plan' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_subscription_plan' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_subscription_plan' ) );
		}
	}

	public function process_add_new_subscription_plan() {
		if( $_POST['ec_admin_form_action'] == "add-new-subscription-plan" ){
			$result = $this->insert_subscription_plan( );
			wp_easycart_admin( )->redirect( 'wp-easycart-products', 'subscriptionplans', $result );
		}
	}

	public function process_update_subscription_plan() {
		if( $_POST['ec_admin_form_action'] == "update-subscription-plan" ){
			$result = $this->update_subscription_plan( );
			wp_easycart_admin( )->redirect( 'wp-easycart-products', 'subscriptionplans', $result );
		}
	}

	public function process_delete_subscription_plan() {
		if( isset($_GET['subpage']) == 'subscriptionplans' && $_GET['ec_admin_form_action'] == 'delete-subscription-plan' && isset( $_GET['subscription_plan_id'] ) && !isset( $_GET['bulk'] ) ){
			$result = $this->delete_subscription_plan( );
			wp_easycart_admin( )->redirect( 'wp-easycart-products', 'subscriptionplans', $result );
		}
	}

	public function process_bulk_delete_subscription_plan() {
		if( isset($_GET['subpage']) == 'subscriptionplans' && $_GET['ec_admin_form_action'] == 'delete-subscription-plan' && !isset( $_GET['subscription_plan_id'] ) && isset( $_GET['bulk'] ) ){
			$result = $this->bulk_delete_subscription_plan( );
			wp_easycart_admin( )->redirect( 'wp-easycart-products', 'subscriptionplans', $result );
		}
	}

	public function add_success_messages( $messages ) {
		if ( isset( $_GET['success'] ) && $_GET['success'] == 'subscription-plan-inserted' ) {
			$messages[] = __( 'Subscription Plan successfully created', 'wp-easycart-pro' );
		} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'subscription-plan-updated' ) {
			$messages[] = __( 'Subscription Plan successfully updated', 'wp-easycart-pro' );
		} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'subscription-plan-deleted' ) {
			$messages[] = __( 'Subscription Plan successfully deleted', 'wp-easycart-pro' );
		}
		return $messages;
	}

	public function add_failure_messages( $messages ) {
		if ( isset( $_GET['error'] ) && $_GET['error'] == 'subscription-plan-inserted-error' ) {
			$messages[] = __( 'Subscription Plan failed to create', 'wp-easycart-pro' );
		} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'subscription-plan-updated-error' ) {
			$messages[] = __( 'Subscription Plan failed to update', 'wp-easycart-pro' );
		} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'subscription-plan-deleted-error' ) {
			$messages[] = __( 'Subscription Plan failed to delete', 'wp-easycart-pro' );
		} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'subscription-plan-duplicate' ) {
			$messages[] = __( 'Subscription Plan failed to create due to duplicate', 'wp-easycart-pro' );
		}
		return $messages;
	}

	public function show_list() {
		include( $this->subscription_plans_list_file );
	}

	public function show_details() {
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/inc/wp_easycart_admin_details_subscription_plans.php' );
		$details = new wp_easycart_admin_details_subscription_plans( );
		$details->output( esc_attr( $_GET['ec_admin_form_action'] ) );
	}

	public function insert_subscription_plan() {
		global $wpdb;
		$plan_title = stripslashes_deep( $_POST['plan_title'] );
		$can_downgrade = 0;
		if ( isset( $_POST['can_downgrade'] ) ) {
			$can_downgrade = 1;
		}
		$wpdb->query( $wpdb->prepare( "INSERT INTO ec_subscription_plan( plan_title, can_downgrade ) VALUES( %s, %d )", $plan_title, $can_downgrade ) );
		$subscription_plan_id = $wpdb->insert_id;
		do_action( 'wpeasycart_subscription_plan_added', $subscription_plan_id );
		return array( 'success' => 'subscription-plan-inserted' );
	}

	public function update_subscription_plan() {
		global $wpdb;
		$subscription_plan_id = (int) $_POST['subscription_plan_id'];
		$plan_title = stripslashes_deep( $_POST['plan_title'] );
		$can_downgrade = 0;
		if( isset( $_POST['can_downgrade'] ) ) {
			$can_downgrade = 1;
		}
		$wpdb->query( $wpdb->prepare( "UPDATE ec_subscription_plan SET plan_title = %s, can_downgrade = %d WHERE subscription_plan_id = %d", $plan_title, $can_downgrade, $subscription_plan_id ) );
		do_action( 'wpeasycart_subscription_plan_updated', $subscription_plan_id );
		return array( 'success' => 'subscription-plan-updated' );
	}

	public function delete_subscription_plan() {
		global $wpdb;
		$subscription_plan_id = (int) $_GET['subscription_plan_id'];
		do_action( 'wpeasycart_subscription_plan_deleting', $subscription_plan_id );
		$wpdb->query( $wpdb->prepare( "DELETE FROM ec_subscription_plan WHERE subscription_plan_id = %d", $subscription_plan_id ) );
		do_action( 'wpeasycart_subscription_plan_deleted', $subscription_plan_id );
		return array( 'success' => 'subscription-plan-deleted' );
	}

	public function bulk_delete_subscription_plan() {
		global $wpdb;
		$bulk_ids = $_GET['bulk'];
		foreach( $bulk_ids as $bulk_id ){
			$wpdb->query( $wpdb->prepare( "DELETE FROM ec_subscription_plan WHERE subscription_plan_id = %d", $bulk_id ) );
		}
		return array( 'success' => 'subscription-plan-deleted' );
	}
}
new wp_easycart_admin_subscription_plans_pro();
