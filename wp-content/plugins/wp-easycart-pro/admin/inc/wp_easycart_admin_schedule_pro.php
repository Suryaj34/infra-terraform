<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_schedule_pro' ) ) :

	final class wp_easycart_admin_schedule_pro {

		protected static $_instance = null;

		public $schedule_list_file;
		public $schedule_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->schedule_list_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/schedule/schedule-list.php';
			$this->schedule_details_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/schedule/schedule-details.php';
			if ( wp_easycart_admin_license()->is_licensed() ) {
				remove_action( 'wp_easycart_admin_schedule_list', array( wp_easycart_admin(), 'show_schedule_list_example', 1 ) );
				remove_action( 'wp_easycart_admin_schedule_list', array( wp_easycart_admin(), 'show_upgrade', 1 ) );
				remove_action( 'wp_easycart_admin_schedule_details', array( wp_easycart_admin(), 'show_upgrade', 1 ) );
				add_action( 'wp_easycart_admin_schedule_list', array( $this, 'show_list' ), 1 );
				add_action( 'wp_easycart_admin_schedule_details', array( $this, 'show_details' ), 1 );
				
				/* Process Admin Messages */
				add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );

				/* Process Form Actions */
				add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_schedule' ) );
				add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_schedule' ) );

				add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_schedule' ) );
				add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_schedule' ) );
			}
		}

		public function process_add_new_schedule() {
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-schedule' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_schedule();
				wp_cache_delete( 'wpeasycart-schedules' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'schedule', $result );
			}
		}

		public function process_update_schedule() {
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-schedule' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_schedule();
				wp_cache_delete( 'wpeasycart-schedules' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'schedule', $result );
			}
		}

		public function process_delete_schedule() {
			if ( isset( $_GET['subpage'] ) && 'schedule' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-schedule' == $_GET['ec_admin_form_action'] && isset( $_GET['schedule_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_schedule();
				wp_cache_delete( 'wpeasycart-schedules' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'schedule', $result );
			}
		}
		public function process_bulk_delete_schedule() {
			if ( isset( $_GET['subpage'] ) && 'schedule' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-schedule' == $_GET['ec_admin_form_action'] && ! isset( $_GET['schedule_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_schedule();
				wp_cache_delete( 'wpeasycart-schedules' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'schedule', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'schedule-inserted' == $_GET['success'] ) {
				$messages[] = __( 'schedule was successfully created', 'wp-easycart-pro' );
			} else if ( isset( $_GET['success'] ) && 'schedule-updated' == $_GET['success'] ) {
				$messages[] = __( 'schedule was successfully updated', 'wp-easycart-pro' );
			} else if ( isset( $_GET['success'] ) && 'schedule-deleted' == $_GET['success'] ) {
				$messages[] = __( 'schedule was successfully deleted', 'wp-easycart-pro' );
			}
			return $messages;
		}

		public function show_details() {
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_details_schedule.php' );
			$details = new wp_easycart_admin_details_schedule();
			$details->output( esc_attr( $_GET['ec_admin_form_action'] ) );
		}

		public function show_list() {
			include( $this->schedule_list_file );
		}

		public function insert_schedule() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-schedule-details' ) ) {
				return false;
			}

			global $wpdb;
			$schedule_label = ( isset( $_POST['schedule_label'] ) ) ? sanitize_text_field( wp_unslash( $_POST['schedule_label'] ) ) : '';
			$day_of_week = ( isset( $_POST['day_of_week'] ) ) ? sanitize_text_field( wp_unslash( $_POST['day_of_week'] ) ) : '';
			$holiday_date = ( isset( $_POST['holiday_date'] ) ) ? date( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_POST['holiday_date'] ) ) ) ) : '';
			$is_holiday = ( isset( $_POST['is_holiday'] ) && '1' == $_POST['is_holiday'] ) ? 1 : 0;
			$apply_to_retail = ( isset( $_POST['apply_to_retail'] ) && '1' == $_POST['apply_to_retail'] ) ? 1 : 0;
			$apply_to_preorder = ( isset( $_POST['apply_to_preorder'] ) && '1' == $_POST['apply_to_preorder'] ) ? 1 : 0;
			$apply_to_restaurant = ( isset( $_POST['apply_to_restaurant'] ) && '1' == $_POST['apply_to_restaurant'] ) ? 1 : 0;
			$retail_start = ( isset( $_POST['retail_start'] ) ) ? sanitize_text_field( wp_unslash( $_POST['retail_start'] ) ) : '';
			$retail_end = ( isset( $_POST['retail_end'] ) ) ? sanitize_text_field( wp_unslash( $_POST['retail_end'] ) ) : '';
			$preorder_start = ( isset( $_POST['preorder_start_month'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_month'] ) ) : '00';
			$preorder_start .= ':' . ( ( isset( $_POST['preorder_start_day'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_day'] ) ) : '00' );
			$preorder_start .= ':' . ( ( isset( $_POST['preorder_start_hour'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_hour'] ) ) : '00' );
			$preorder_start .= ':' . ( ( isset( $_POST['preorder_start_minute'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_minute'] ) ) : '00' );
			$preorder_end = ( isset( $_POST['preorder_end_month'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_month'] ) ) : '00';
			$preorder_end .= ':' . ( ( isset( $_POST['preorder_end_day'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_day'] ) ) : '00' );
			$preorder_end .= ':' . ( ( isset( $_POST['preorder_end_hour'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_hour'] ) ) : '00' );
			$preorder_end .= ':' . ( ( isset( $_POST['preorder_end_minute'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_minute'] ) ) : '00' );
			$preorder_open_time = ( isset( $_POST['preorder_open_time'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_open_time'] ) ) : '';
			$preorder_close_time = ( isset( $_POST['preorder_close_time'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_close_time'] ) ) : '';
			$restaurant_start = ( isset( $_POST['restaurant_start'] ) ) ? sanitize_text_field( wp_unslash( $_POST['restaurant_start'] ) ) : '';
			$restaurant_end = ( isset( $_POST['restaurant_end'] ) ) ? sanitize_text_field( wp_unslash( $_POST['restaurant_end'] ) ) : '';
			$retail_closed = ( isset( $_POST['retail_closed'] ) && '1' == $_POST['retail_closed'] ) ? 1 : 0;
			$preorder_closed = ( isset( $_POST['preorder_closed'] ) && '1' == $_POST['preorder_closed'] ) ? 1 : 0;
			$restaurant_closed = ( isset( $_POST['restaurant_closed'] ) && '1' == $_POST['restaurant_closed'] ) ? 1 : 0;

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_schedule( schedule_label, day_of_week, is_holiday, holiday_date, apply_to_retail, apply_to_preorder, apply_to_restaurant, retail_start, retail_end, preorder_start, preorder_end, preorder_open_time, preorder_close_time, restaurant_start, restaurant_end, retail_closed, preorder_closed, restaurant_closed ) VALUES( %s, %s, %d, %s, %d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d)', $schedule_label, $day_of_week, $is_holiday, $holiday_date, $apply_to_retail, $apply_to_preorder, $apply_to_restaurant, $retail_start, $retail_end, $preorder_start, $preorder_end, $preorder_open_time, $preorder_close_time, $restaurant_start, $restaurant_end, $retail_closed, $preorder_closed, $restaurant_closed ) );
			$schedule_id = $wpdb->insert_id;

			if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
				$location_ids = array();
				foreach ( $_POST['location_ids'] as $location_id ) {
					$location_ids[] = (int) $location_id;
				}
				$sql = 'INSERT INTO ec_location_to_schedule( location_id, schedule_id ) VALUES';
				for ( $i = 0; $i < count( $location_ids ); $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= $wpdb->prepare( '(%d,%d)', $location_ids[ $i ], $schedule_id );
				}
				$wpdb->query( $sql );
			}

			do_action( 'wpeasycart_schedule_added', $schedule_id );

			return array( 'success' => 'schedule-inserted' );
		}

		public function update_schedule() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-schedule-details' ) ) {
				return false;
			}

			global $wpdb;
			$schedule_id = ( isset( $_POST['schedule_id'] ) ) ? (int) $_POST['schedule_id'] : '0';
			$schedule_label = ( isset( $_POST['schedule_label'] ) ) ? sanitize_text_field( wp_unslash( $_POST['schedule_label'] ) ) : '';
			if ( $schedule_id <= 7 ) {
				$schedule_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_schedule WHERE schedule_id = %d', $schedule_id ) );
				$day_of_week = $schedule_row->day_of_week;
				$holiday_date = $schedule_row->holiday_date;
				$is_holiday = $schedule_row->is_holiday;
			} else {
				$day_of_week = ( isset( $_POST['day_of_week'] ) ) ? sanitize_text_field( wp_unslash( $_POST['day_of_week'] ) ) : '';
				$holiday_date = ( isset( $_POST['holiday_date'] ) ) ? date( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_POST['holiday_date'] ) ) ) ) : '';
				$is_holiday = ( isset( $_POST['is_holiday'] ) && '1' == $_POST['is_holiday'] ) ? 1 : 0;
			}
			$apply_to_retail = ( isset( $_POST['apply_to_retail'] ) && '1' == $_POST['apply_to_retail'] ) ? 1 : 0;
			$apply_to_preorder = ( isset( $_POST['apply_to_preorder'] ) && '1' == $_POST['apply_to_preorder'] ) ? 1 : 0;
			$apply_to_restaurant = ( isset( $_POST['apply_to_restaurant'] ) && '1' == $_POST['apply_to_restaurant'] ) ? 1 : 0;
			$retail_start = ( isset( $_POST['retail_start'] ) ) ? sanitize_text_field( wp_unslash( $_POST['retail_start'] ) ) : '';
			$retail_end = ( isset( $_POST['retail_end'] ) ) ? sanitize_text_field( wp_unslash( $_POST['retail_end'] ) ) : '';
			$preorder_start = ( isset( $_POST['preorder_start_month'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_month'] ) ) : '00';
			$preorder_start .= ':' . ( ( isset( $_POST['preorder_start_day'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_day'] ) ) : '00' );
			$preorder_start .= ':' . ( ( isset( $_POST['preorder_start_hour'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_hour'] ) ) : '00' );
			$preorder_start .= ':' . ( ( isset( $_POST['preorder_start_minute'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_start_minute'] ) ) : '00' );
			$preorder_end = ( isset( $_POST['preorder_end_month'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_month'] ) ) : '00';
			$preorder_end .= ':' . ( ( isset( $_POST['preorder_end_day'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_day'] ) ) : '00' );
			$preorder_end .= ':' . ( ( isset( $_POST['preorder_end_hour'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_hour'] ) ) : '00' );
			$preorder_end .= ':' . ( ( isset( $_POST['preorder_end_minute'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_end_minute'] ) ) : '00' );
			$preorder_open_time = ( isset( $_POST['preorder_open_time'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_open_time'] ) ) : '';
			$preorder_close_time = ( isset( $_POST['preorder_close_time'] ) ) ? sanitize_text_field( wp_unslash( $_POST['preorder_close_time'] ) ) : '';
			$restaurant_start = ( isset( $_POST['restaurant_start'] ) ) ? sanitize_text_field( wp_unslash( $_POST['restaurant_start'] ) ) : '';
			$restaurant_end = ( isset( $_POST['restaurant_end'] ) ) ? sanitize_text_field( wp_unslash( $_POST['restaurant_end'] ) ) : '';
			$retail_closed = ( isset( $_POST['retail_closed'] ) && '1' == $_POST['retail_closed'] ) ? 1 : 0;
			$preorder_closed = ( isset( $_POST['preorder_closed'] ) && '1' == $_POST['preorder_closed'] ) ? 1 : 0;
			$restaurant_closed = ( isset( $_POST['restaurant_closed'] ) && '1' == $_POST['restaurant_closed'] ) ? 1 : 0;

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_schedule SET schedule_label = %s, day_of_week = %s, is_holiday = %d, holiday_date = %s, apply_to_retail = %d, apply_to_preorder = %d, apply_to_restaurant = %d, retail_start = %s, retail_end = %s, preorder_start = %s, preorder_end = %s, preorder_open_time = %s, preorder_close_time = %s, restaurant_start = %s, restaurant_end = %s, retail_closed = %d, preorder_closed = %d, restaurant_closed = %d WHERE schedule_id = %d', $schedule_label, $day_of_week, $is_holiday, $holiday_date, $apply_to_retail, $apply_to_preorder, $apply_to_restaurant, $retail_start, $retail_end, $preorder_start, $preorder_end, $preorder_open_time, $preorder_close_time, $restaurant_start, $restaurant_end, $retail_closed, $preorder_closed, $restaurant_closed, $schedule_id ) );

			if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
				$location_ids = array();
				foreach ( $_POST['location_ids'] as $location_id ) {
					$location_ids[] = (int) $location_id;
				}
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_location_to_schedule WHERE schedule_id = %d', $schedule_id ) );
				$sql = 'INSERT INTO ec_location_to_schedule( location_id, schedule_id ) VALUES';
				for ( $i = 0; $i < count( $location_ids ); $i++ ) {
					if ( $i > 0 ) {
						$sql .= ',';
					}
					$sql .= $wpdb->prepare( '(%d,%d)', $location_ids[ $i ], $schedule_id );
				}
				$wpdb->query( $sql );
			}

			return array( 'success' => 'schedule-updated' );
		}

		public function delete_schedule() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-schedule' ) ) {
				return false;
			}

			global $wpdb;
			$schedule_id = ( isset( $_GET['schedule_id'] ) ) ? (int) $_GET['schedule_id'] : 0;
			if ( $schedule_id <= 7 ) {
				return array( 'error' => 'schedule-deleted' );
			} else {
				do_action( 'wpeasycart_schedule_deleting', $schedule_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_schedule WHERE schedule_id = %d', $schedule_id ) );
				do_action( 'wpeasycart_schedule_deleted', $schedule_id );
				return array( 'success' => 'schedule-deleted' );
			}
		}

		public function bulk_delete_schedule() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-schedule' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			foreach ( $bulk_ids as $bulk_id ) {
				if ( (int) $bulk_id > 7 ) {
					do_action( 'wpeasycart_schedule_deleting', (int) $bulk_id );
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_schedule WHERE schedule_id = %d', (int) $bulk_id ) );
					do_action( 'wpeasycart_schedule_deleted', (int) $bulk_id );
				}
			}
			return array( 'success' => 'schedule-deleted' );
		}
	}
endif;

function wp_easycart_admin_schedule_pro() {
	return wp_easycart_admin_schedule_pro::instance();
}
wp_easycart_admin_schedule_pro();
