<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_location extends wp_easycart_admin_details {
	public $location;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_location_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=locations';
		$this->id = 0;
		$this->page = 'wp-easycart-settings';
		$this->subpage = 'location';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-location';
		$this->item = $this->location = (object) array(
			'location_id' => '',
			'location_label' => '',
			'address_line_1' => '',
			'address_line_2' => '',
			'city' => '',
			'state' => '',
			'zip' => '',
			'country' => '',
			'phone' => '',
			'email' => '',
			'latitude' => '',
			'longitude' => '',
			'hours_note' => '',
		);
	}

	protected function init_data() {
		$this->form_action = 'update-location';
		$this->item = $this->location = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_location.* FROM ec_location WHERE location_id = %d", $_GET['location_id'] ) );
		$this->id = $this->location->location_id;
	}

	public function output( $type = 'edit' ){
		$this->init();
		if ( 'edit' == $type ) {
			$this->init_data();
		}
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/locations/location-details.php' );
	}

	public function basic_fields() {
		$fields = array(
			array(
				'name' => 'location_id',
				'alt_name' => 'location_id',
				'type' => 'hidden',
				'value' => $this->location->location_id,
			),
			array(
				"name"				=> "location_label",
				"type"				=> "text",
				"label"				=> __( "Location Label", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "You must enter a label.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->location->location_label
			),
			array(
				"name"				=> "address_line_1",
				"type"				=> "text",
				"label"				=> __( "Address Line 1", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter an address.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->location->address_line_1
			),
			array(
				"name"				=> "address_line_2",
				"type"				=> "text",
				"label"				=> __( "Address Line 2", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->address_line_2
			),
			array(
				"name"				=> "city",
				"type"				=> "text",
				"label"				=> __( "City", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter a city.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->location->city
			),
			array(
				"name"				=> "state",
				"type"				=> "text",
				"label"				=> __( "State/Province/County", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->state
			),
			array(
				"name"				=> "zip",
				"type"				=> "text",
				"label"				=> __( "Zip or Postal Code", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->zip
			),
			array(
				"name"				=> "country",
				"type"				=> "text",
				"label"				=> __( "Country", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "Please enter a country.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->location->country
			),
			array(
				"name"				=> "phone",
				"type"				=> "text",
				"label"				=> __( "Location Phone", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->phone
			),
			array(
				"name"				=> "email",
				"type"				=> "text",
				"label"				=> __( "Location Email Address", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->email
			),
			array(
				"name"				=> "hours_note",
				"type"				=> "textarea",
				"label"				=> __( "Location Notes (shown in location selection list)", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->hours_note
			),
		);
		if ( ! get_option( 'ec_option_pickup_location_google_site_key' ) || '' == get_option( 'ec_option_pickup_location_google_site_key' ) ) {
			$fields[] = array(
				"name"				=> "latitude",
				"type"				=> "text",
				"label"				=> __( "Location Latitude", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->latitude
			);
			$fields[] = array(
				"name"				=> "longitude",
				"type"				=> "text",
				"label"				=> __( "Location Longitude", 'wp-easycart-pro' ),
				"required" 			=> false,
				"validation_type" 	=> 'text',
				"value"				=> $this->location->longitude
			);
		}
		$fields = apply_filters( 'wp_easycart_admin_location_details_basic_fields_list', $fields );
		$this->print_fields( $fields );
	}
}
