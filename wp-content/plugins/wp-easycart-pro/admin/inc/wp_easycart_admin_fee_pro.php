<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_fee_pro' ) ) :

	final class wp_easycart_admin_fee_pro {

		protected static $_instance = null;

		public $fee_list_file;
		public $fee_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->fee_list_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/fee/fee-list.php';
			$this->fee_details_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/fee/fee-details.php';
			if ( wp_easycart_admin_license()->is_licensed() ) {
				remove_action( 'wp_easycart_admin_fee_list', array( wp_easycart_admin(), 'show_fee_list_example', 1 ) );
				remove_action( 'wp_easycart_admin_fee_list', array( wp_easycart_admin(), 'show_upgrade', 1 ) );
				remove_action( 'wp_easycart_admin_fee_details', array( wp_easycart_admin(), 'show_upgrade', 1 ) );
				add_action( 'wp_easycart_admin_fee_list', array( $this, 'show_list' ), 1 );
				add_action( 'wp_easycart_admin_fee_details', array( $this, 'show_details' ), 1 );
				
				/* Process Admin Messages */
				add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );

				/* Process Form Actions */
				add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_fee' ) );
				add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_fee' ) );

				add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_fee' ) );
				add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_fee' ) );
			}
		}

		public function process_add_new_fee() {
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-fee' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_fee();
				wp_cache_delete( 'wpeasycart-fees' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'fee', $result );
			}
		}

		public function process_update_fee() {
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-fee' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_fee();
				wp_cache_delete( 'wpeasycart-fees' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'fee', $result );
			}
		}

		public function process_delete_fee() {
			if ( isset( $_GET['subpage'] ) && 'fee' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-fee' == $_GET['ec_admin_form_action'] && isset( $_GET['fee_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_fee();
				wp_cache_delete( 'wpeasycart-fees' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'fee', $result );
			}
		}
		public function process_bulk_delete_fee() {
			if ( isset( $_GET['subpage'] ) && 'fee' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-fee' == $_GET['ec_admin_form_action'] && ! isset( $_GET['fee_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_fee();
				wp_cache_delete( 'wpeasycart-fees' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'fee', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'fee-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Fee was successfully created', 'wp-easycart-pro' );
			} else if ( isset( $_GET['success'] ) && 'fee-updated' == $_GET['success'] ) {
				$messages[] = __( 'Fee was successfully updated', 'wp-easycart-pro' );
			} else if ( isset( $_GET['success'] ) && 'fee-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Fee was successfully deleted', 'wp-easycart-pro' );
			}
			return $messages;
		}

		public function show_details() {
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_details_fee.php' );
			$details = new wp_easycart_admin_details_fee();
			$details->output( esc_attr( $_GET['ec_admin_form_action'] ) );
		}

		public function show_list() {
			include( $this->fee_list_file );
		}

		public function insert_fee() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-fee-details' ) ) {
				return false;
			}

			global $wpdb;

			$fee_label = $_POST['fee_label'];
			$fee_country = ( isset( $_POST['fee_country'] ) ) ? $_POST['fee_country'] : array();
			$fee_state = ( isset( $_POST['fee_state'] ) ) ? $_POST['fee_state'] : array();
			$fee_zip = $_POST['fee_zip'];
			$fee_city = $_POST['fee_city'];
			$fee_category = ( isset( $_POST['fee_category'] ) ) ? $_POST['fee_category'] : array();
			$fee_role = ( isset( $_POST['fee_role'] ) ) ? $_POST['fee_role'] : array();
			$fee_zone = ( isset( $_POST['fee_zone'] ) ) ? $_POST['fee_zone'] : array();
			$fee_payment_type = ( isset( $_POST['fee_payment_type'] ) ) ? $_POST['fee_payment_type'] : array();
			$fee_type = ( isset( $_POST['fee_type'] ) ) ? (int) $_POST['fee_type'] : 1;
			$fee_rate = ( 1 == $fee_type ) ? $_POST['fee_rate'] : 0;
			$fee_price = ( 2 == $fee_type ) ? $_POST['fee_price'] : 0;
			$fee_min = ( 1 == $fee_type ) ? $_POST['fee_min'] : 0;
			$fee_max = ( 1 == $fee_type ) ? $_POST['fee_max'] : 0;

			$payment_methods = array(
				'card' => esc_attr__( 'Card Payment', 'wp-easycart-pro' ),
				'affirm' => esc_attr__( 'Affirm (Stripe)', 'wp-easycart-pro' ),
				'klarna' => esc_attr__( 'Klarna (Stripe)', 'wp-easycart-pro' ),
				'afterpay_clearpay' => esc_attr__( 'Afterpay (Stripe)', 'wp-easycart-pro' ),
				'third_party' => esc_attr__( 'PayPal / Third Party', 'wp-easycart-pro' ),
				'amazonpay' => esc_attr__( 'Amazon Pay', 'wp-easycart-pro' ),
				'manual_bill' => esc_attr__( 'Manual Payment', 'wp-easycart-pro' ),
			);

			$fee_admin_description = '';
			$is_fee_admin_description_first = true;
			foreach ( $fee_country as $country ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $GLOBALS['ec_countries']->get_country_name( $country );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_state as $state ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $wpdb->get_var( $wpdb->prepare( 'SELECT ec_state.name_sta FROM ec_state WHERE id_sta = %d', $state ) );
				$is_fee_admin_description_first = false;
			}
			if ( strlen( trim( $fee_city ) ) > 0 ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= trim( $fee_city );
				$is_fee_admin_description_first = false;
			}
			if ( strlen( trim( $fee_zip ) ) > 0 ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= trim( $fee_zip );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_category as $category ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $wpdb->get_var( $wpdb->prepare( 'SELECT ec_category.category_name FROM ec_category WHERE ec_category.category_id = %d', $category ) );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_role as $role ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $role;
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_zone as $zone ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $wpdb->get_var( $wpdb->prepare( 'SELECT ec_zone.zone_name FROM ec_zone WHERE ec_zone.zone_id = %d', $zone ) );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_payment_type as $payment_method ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= ( in_array( $payment_method, $payment_methods ) ) ? $payment_methods[ $payment_method ] : $payment_method;
				$is_fee_admin_description_first = false;
			}

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_fee( fee_label, fee_admin_description, fee_country, fee_state, fee_zip, fee_city, fee_category,fee_role, fee_zone, fee_payment_type, fee_type, fee_rate, fee_price, fee_min, fee_max ) VALUES( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %s, %s, %s )', $fee_label, $fee_admin_description, implode( ',', $fee_country ), implode( ',', $fee_state ), $fee_zip, $fee_city, implode( ',', $fee_category ), implode( ',', $fee_role ), implode( ',', $fee_zone ), implode( ',', $fee_payment_type ), $fee_type, $fee_rate, $fee_price, $fee_min, $fee_max ) );
			$fee_id = $wpdb->insert_id;

			do_action( 'wpeasycart_fee_added', $fee_id );

			return array( 'success' => 'fee-inserted' );
		}

		public function update_fee() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-fee-details' ) ) {
				return false;
			}

			global $wpdb;

			$fee_id = (int) $_POST['fee_id'];
			$fee_label = $_POST['fee_label'];
			$fee_country = ( isset( $_POST['fee_country'] ) ) ? $_POST['fee_country'] : array();
			$fee_state = ( isset( $_POST['fee_state'] ) ) ? $_POST['fee_state'] : array();
			$fee_zip = $_POST['fee_zip'];
			$fee_city = $_POST['fee_city'];
			$fee_category = ( isset( $_POST['fee_category'] ) ) ? $_POST['fee_category'] : array();
			$fee_role = ( isset( $_POST['fee_role'] ) ) ? $_POST['fee_role'] : array();
			$fee_zone = ( isset( $_POST['fee_zone'] ) ) ? $_POST['fee_zone'] : array();
			$fee_payment_type = ( isset( $_POST['fee_payment_type'] ) ) ? $_POST['fee_payment_type'] : array();
			$fee_type = ( isset( $_POST['fee_type'] ) ) ? (int) $_POST['fee_type'] : 1;
			$fee_rate = ( 1 == $fee_type ) ? $_POST['fee_rate'] : 0;
			$fee_price = ( 2 == $fee_type ) ? $_POST['fee_price'] : 0;
			$fee_min = ( 1 == $fee_type ) ? $_POST['fee_min'] : 0;
			$fee_max = ( 1 == $fee_type ) ? $_POST['fee_max'] : 0;

			$payment_methods = array(
				'card' => esc_attr__( 'Card Payment', 'wp-easycart-pro' ),
				'affirm' => esc_attr__( 'Affirm (Stripe)', 'wp-easycart-pro' ),
				'klarna' => esc_attr__( 'Klarna (Stripe)', 'wp-easycart-pro' ),
				'afterpay_clearpay' => esc_attr__( 'Afterpay (Stripe)', 'wp-easycart-pro' ),
				'third_party' => esc_attr__( 'PayPal / Third Party', 'wp-easycart-pro' ),
				'amazonpay' => esc_attr__( 'Amazon Pay', 'wp-easycart-pro' ),
				'manual_bill' => esc_attr__( 'Manual Payment', 'wp-easycart-pro' ),
			);

			$fee_admin_description = '';
			$is_fee_admin_description_first = true;
			foreach ( $fee_country as $country ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $GLOBALS['ec_countries']->get_country_name( $country );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_state as $state ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $wpdb->get_var( $wpdb->prepare( 'SELECT ec_state.name_sta FROM ec_state WHERE id_sta = %d', $state ) );
				$is_fee_admin_description_first = false;
			}
			if ( strlen( trim( $fee_city ) ) > 0 ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= trim( $fee_city );
				$is_fee_admin_description_first = false;
			}
			if ( strlen( trim( $fee_zip ) ) > 0 ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= trim( $fee_zip );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_category as $category ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $wpdb->get_var( $wpdb->prepare( 'SELECT ec_category.category_name FROM ec_category WHERE ec_category.category_id = %d', $category ) );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_role as $role ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $role;
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_zone as $zone ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= $wpdb->get_var( $wpdb->prepare( 'SELECT ec_zone.zone_name FROM ec_zone WHERE ec_zone.zone_id = %d', $zone ) );
				$is_fee_admin_description_first = false;
			}
			foreach ( $fee_payment_type as $payment_type ) {
				$fee_admin_description .= ( ! $is_fee_admin_description_first ) ? ', ' : '';
				$fee_admin_description .= ( ( in_array( $payment_type, $payment_methods ) ) ? $payment_methods[ $payment_type ] : $payment_type );
				$is_fee_admin_description_first = false;
			}

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_fee SET fee_label = %s, fee_admin_description = %s, fee_country = %s, fee_state = %s, fee_zip = %s, fee_city = %s, fee_category = %s, fee_role = %s, fee_zone = %s, fee_payment_type = %s, fee_type = %d, fee_rate = %s, fee_price = %s, fee_min = %s, fee_max = %s WHERE fee_id = %d', $fee_label, $fee_admin_description, implode( ',', $fee_country ), implode( ',', $fee_state ), $fee_zip, $fee_city, implode( ',', $fee_category ), implode( ',', $fee_role ), implode( ',', $fee_zone ), implode( ',', $fee_payment_type ), $fee_type, $fee_rate, $fee_price, $fee_min, $fee_max, $fee_id ) );
			do_action( 'wpeasycart_fee_updated', $fee_id );

			return array( 'success' => 'fee-updated' );
		}

		public function delete_fee() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-fee' ) ) {
				return false;
			}

			global $wpdb;
			$fee_id = ( isset( $_GET['fee_id'] ) ) ? (int) $_GET['fee_id'] : 0;
			do_action( 'wpeasycart_fee_deleting', $fee_id );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_fee WHERE fee_id = %d', $fee_id ) );
			do_action( 'wpeasycart_fee_deleted', $fee_id );
			return array( 'success' => 'fee-deleted' );
		}

		public function bulk_delete_fee() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-fee' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wpeasycart_fee_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_fee WHERE fee_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_fee_deleted', (int) $bulk_id );
			}
			return array( 'success' => 'fee-deleted' );
		}
	}
endif;

function wp_easycart_admin_fee_pro() {
	return wp_easycart_admin_fee_pro::instance();
}
wp_easycart_admin_fee_pro();
