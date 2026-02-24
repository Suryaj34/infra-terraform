<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_location_pro' ) ) :

	final class wp_easycart_admin_location_pro {

		protected static $_instance = null;

		public $location_list_file;
		public $location_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->location_list_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/locations/location-list.php';
			$this->location_details_file = WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/locations/location-details.php';
			if ( wp_easycart_admin_license()->is_licensed() ) {
				remove_action( 'wp_easycart_admin_location_list', array( wp_easycart_admin(), 'show_location_list_example', 1 ) );
				remove_action( 'wp_easycart_admin_location_list', array( wp_easycart_admin(), 'show_upgrade', 1 ) );
				remove_action( 'wp_easycart_admin_location_details', array( wp_easycart_admin(), 'show_upgrade', 1 ) );
				add_action( 'wp_easycart_admin_location_list', array( $this, 'show_list' ), 1 );
				add_action( 'wp_easycart_admin_location_details', array( $this, 'show_details' ), 1 );
				
				/* Process Admin Messages */
				add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );

				/* Process Form Actions */
				add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_location' ) );
				add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_location' ) );

				add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_location' ) );
				add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_location' ) );
			}
		}

		public function process_add_new_location() {
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-location' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_location();
				wp_cache_delete( 'wpeasycart-locations' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'location', $result );
			}
		}

		public function process_update_location() {
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-location' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_location();
				wp_cache_delete( 'wpeasycart-locations' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'location', $result );
			}
		}

		public function process_delete_location() {
			if ( isset( $_GET['subpage'] ) && 'location' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-location' == $_GET['ec_admin_form_action'] && isset( $_GET['location_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_location();
				wp_cache_delete( 'wpeasycart-locations' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'location', $result );
			}
		}
		public function process_bulk_delete_location() {
			if ( isset( $_GET['subpage'] ) && 'location' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-location' == $_GET['ec_admin_form_action'] && ! isset( $_GET['location_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_location();
				wp_cache_delete( 'wpeasycart-locations' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'location', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'location-inserted' == $_GET['success'] ) {
				$messages[] = __( 'location was successfully created', 'wp-easycart-pro' );
			} else if ( isset( $_GET['success'] ) && 'location-updated' == $_GET['success'] ) {
				$messages[] = __( 'location was successfully updated', 'wp-easycart-pro' );
			} else if ( isset( $_GET['success'] ) && 'location-deleted' == $_GET['success'] ) {
				$messages[] = __( 'location was successfully deleted', 'wp-easycart-pro' );
			}
			return $messages;
		}

		public function show_details() {
			include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_admin_details_location.php' );
			$details = new wp_easycart_admin_details_location();
			$details->output( esc_attr( $_GET['ec_admin_form_action'] ) );
		}

		public function show_list() {
			include( $this->location_list_file );
		}

		public function insert_location() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-location-details' ) ) {
				return false;
			}

			global $wpdb;
			$location_label = ( isset( $_POST['location_label'] ) ) ? sanitize_text_field( wp_unslash( $_POST['location_label'] ) ) : '';
			$address_line_1 = ( isset( $_POST['address_line_1'] ) ) ? sanitize_text_field( wp_unslash( $_POST['address_line_1'] ) ) : '';
			$address_line_2 = ( isset( $_POST['address_line_2'] ) ) ? sanitize_text_field( wp_unslash( $_POST['address_line_2'] ) ) : '';
			$city = ( isset( $_POST['city'] ) ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
			$state = ( isset( $_POST['state'] ) ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
			$zip = ( isset( $_POST['zip'] ) ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
			$country = ( isset( $_POST['country'] ) ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
			$phone = ( isset( $_POST['phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
			$email = ( isset( $_POST['email'] ) ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
			$hours_note = ( isset( $_POST['hours_note'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['hours_note'] ) ) : '';
			$latitude = $longitude = false;
			if ( get_option( 'ec_option_pickup_location_google_site_key' ) && '' != get_option( 'ec_option_pickup_location_google_site_key' ) ) {
				$lat_long = wp_easycart_get_location_geocode( $address_line_1 . ' ' . $address_line_2 . ' ' . $city . ' ' . $state . ' ' . $zip . ' ' . $country );
				if ( is_array( $lat_long ) && isset( $lat_long['lat'] ) && isset( $lat_long['long'] ) ) {
					$latitude = $lat_long['lat'];
					$longitude = $lat_long['long'];
				}
			} else {
				$latitude = ( isset( $_POST['latitude'] ) ) ? sanitize_text_field( wp_unslash( $_POST['latitude'] ) ) : '';
				$longitude = ( isset( $_POST['longitude'] ) ) ? sanitize_text_field( wp_unslash( $_POST['longitude'] ) ) : '';
			}
			if ( $latitude && $longitude ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_location( location_label, address_line_1, address_line_2, city, state, zip, country, phone, email, hours_note, latitude, longitude ) VALUES( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )', $location_label, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $email, $hours_note, $latitude, $longitude ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_location( location_label, address_line_1, address_line_2, city, state, zip, country, phone, email, hours_note ) VALUES( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )', $location_label, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $email, $hours_note ) );
			}
			$location_id = $wpdb->insert_id;
			do_action( 'wpeasycart_location_added', $location_id );
			return array( 'success' => 'location-inserted' );
		}

		public function update_location() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-location-details' ) ) {
				return false;
			}

			global $wpdb;
			$location_id = ( isset( $_POST['location_id'] ) ) ? (int) $_POST['location_id'] : '0';
			$location_label = ( isset( $_POST['location_label'] ) ) ? sanitize_text_field( wp_unslash( $_POST['location_label'] ) ) : '';
			$address_line_1 = ( isset( $_POST['address_line_1'] ) ) ? sanitize_text_field( wp_unslash( $_POST['address_line_1'] ) ) : '';
			$address_line_2 = ( isset( $_POST['address_line_2'] ) ) ? sanitize_text_field( wp_unslash( $_POST['address_line_2'] ) ) : '';
			$city = ( isset( $_POST['city'] ) ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
			$state = ( isset( $_POST['state'] ) ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
			$zip = ( isset( $_POST['zip'] ) ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
			$country = ( isset( $_POST['country'] ) ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
			$phone = ( isset( $_POST['phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
			$email = ( isset( $_POST['email'] ) ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
			$hours_note = ( isset( $_POST['hours_note'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['hours_note'] ) ) : '';
			$latitude = $longitude = false;
			if ( get_option( 'ec_option_pickup_location_google_site_key' ) && '' != get_option( 'ec_option_pickup_location_google_site_key' ) ) {
				$lat_long = wp_easycart_get_location_geocode( $address_line_1 . ' ' . $address_line_2 . ' ' . $city . ' ' . $state . ' ' . $zip . ' ' . $country );
				if ( is_array( $lat_long ) && isset( $lat_long['lat'] ) && isset( $lat_long['long'] ) ) {
					$latitude = $lat_long['lat'];
					$longitude = $lat_long['long'];
				}
			} else {
				$latitude = ( isset( $_POST['latitude'] ) ) ? sanitize_text_field( wp_unslash( $_POST['latitude'] ) ) : '';
				$longitude = ( isset( $_POST['longitude'] ) ) ? sanitize_text_field( wp_unslash( $_POST['longitude'] ) ) : '';
			}
			if ( $latitude && $longitude ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_location SET location_label = %s, address_line_1 = %s, address_line_2 = %s, city = %s, state = %s, zip = %s, country = %s, phone = %s, email = %s, hours_note = %s, latitude = %s, longitude = %s WHERE location_id = %d', $location_label, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $email, $hours_note, $latitude, $longitude, $location_id ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_location SET location_label = %s, address_line_1 = %s, address_line_2 = %s, city = %s, state = %s, zip = %s, country = %s, phone = %s, email = %s, hours_note = %s WHERE location_id = %d', $location_label, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $email, $hours_note, $location_id ) );
			}
			do_action( 'wpeasycart_location_updated', $location_id );
			return array( 'success' => 'location-updated' );
		}

		public function delete_location() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-location' ) ) {
				return false;
			}

			global $wpdb;
			$location_id = ( isset( $_GET['location_id'] ) ) ? (int) $_GET['location_id'] : 0;
			if ( $location_id <= 7 ) {
				return array( 'error' => 'location-deleted' );
			} else {
				do_action( 'wpeasycart_location_deleting', $location_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_location WHERE location_id = %d', $location_id ) );
				do_action( 'wpeasycart_location_deleted', $location_id );
				return array( 'success' => 'location-deleted' );
			}
		}

		public function bulk_delete_location() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-location' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			foreach ( $bulk_ids as $bulk_id ) {
				if ( (int) $bulk_id > 7 ) {
					do_action( 'wpeasycart_location_deleting', (int) $bulk_id );
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_location WHERE location_id = %d', (int) $bulk_id ) );
					do_action( 'wpeasycart_location_deleted', (int) $bulk_id );
				}
			}
			return array( 'success' => 'location-deleted' );
		}
	}
endif;

function wp_easycart_admin_location_pro() {
	return wp_easycart_admin_location_pro::instance();
}
wp_easycart_admin_location_pro();
