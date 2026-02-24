<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class wp_easycart_admin_details_schedule extends wp_easycart_admin_details {
	public $schedule;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_schedule_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=schedules';
		$this->id = 0;
		$this->page = 'wp-easycart-settings';
		$this->subpage = 'schedule';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-schedule';
		$this->item = $this->schedule = (object) array(
			'schedule_id' => '',
			'schedule_label' => '',
			'day_of_week' => 'SUN',
			'is_holiday' => '0',
			'holiday_date' => '',
			'apply_to_retail' => '0',
			'apply_to_preorder' => '0',
			'apply_to_restaurant' => '0',
			'retail_start' => '',
			'retail_end' => '',
			'preorder_start' => '',
			'preorder_end' => '',
			'preorder_open_time' => '',
			'preorder_close_time' => '',
			'restaurant_start' => '',
			'restaurant_end' => '',
			'retail_closed' => '',
			'preorder_closed' => '',
			'restaurant_closed' => '',
		);
	}

	protected function init_data() {
		$this->form_action = 'update-schedule';
		$this->item = $this->schedule = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_schedule.* FROM ec_schedule WHERE schedule_id = %d", $_GET['schedule_id'] ) );
		$this->id = $this->schedule->schedule_id;
	}

	public function output( $type = 'edit' ){
		$this->init();
		if ( 'edit' == $type ) {
			$this->init_data();
		}
		include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . '/admin/template/settings/schedule/schedule-details.php' );
	}

	public function basic_fields() {
		$day_of_weeks = array(
			(object) array(
				'id' => 'SUN',
				'value' => esc_attr__( 'Sunday', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'MON',
				'value' => esc_attr__( 'Monday', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'TUE',
				'value' => esc_attr__( 'Tuesday', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'WED',
				'value' => esc_attr__( 'Wednesday', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'THU',
				'value' => esc_attr__( 'Thursday', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'FRI',
				'value' => esc_attr__( 'Friday', 'wp-easycart-pro' ),
			),
			(object) array(
				'id' => 'SAT',
				'value' => esc_attr__( 'Saturday', 'wp-easycart-pro' ),
			),
		);
		$times = array();
		for( $hour = 0; $hour < 24; $hour++ ) {
			$times[] = (object) array(
				'id' => esc_attr( date( 'H:i', strtotime( date( 'Y-m-d ' . $hour . ':00' ) ) ) ),
				'value' => esc_attr( date( get_option('time_format'), strtotime( date( 'Y-m-d ' . $hour . ':00' ) ) ) ),
			);
		}
		$fields = array(
			array(
				'name' => 'schedule_id',
				'alt_name' => 'schedule_id',
				'type' => 'hidden',
				'value' => $this->schedule->schedule_id,
			),
			array(
				"name"				=> "schedule_label",
				"type"				=> "text",
				"label"				=> __( "Schedule Label", 'wp-easycart-pro' ),
				"required" 			=> true,
				"message" 			=> __( "You must enter a label. This label is only shown in the admin.", 'wp-easycart-pro' ),
				"validation_type" 	=> 'text',
				"value"				=> $this->schedule->schedule_label
			),
			array(
				"name"				=> "is_holiday",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => 'General Weekday',
					),
					(object) array(
						'id' => '1',
						'value' => 'Holiday',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Type of Schedule (is this a holiday?)", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->is_holiday,
				"show"  	=> array(
					"name" 	=> "holiday_date",
					"value"	=> "1"
				),
				"disabled_for_ids" => array( 1, 2, 3, 4, 5, 6, 7 ),
			),
			array(
				"name"	=> "holiday_date",
				"type"	=> "date",
				"label"	=> __( "Holiday Date", 'wp-easycart-pro' ),
				"required" => false,
				"message" => __( "Enter the date that this holiday applies.", 'wp-easycart-pro' ),
				"validation_type" => 'date',
				"value" => $this->schedule->holiday_date,
				"requires"	=> array(
					"name"			=> "is_holiday",
					"value"			=> 1,
					"default_show"	=> false
				),
				"disabled_for_ids" => array( 1, 2, 3, 4, 5, 6, 7 ),
			),
			array(
				"name"				=> "day_of_week",
				"type"				=> "select",
				"select2"			=> "basic",
				"data"				=> $day_of_weeks,
				"label" 			=> __( "The day of the week that this applies", 'wp-easycart-pro' ),
				"required" 			=> false,
				"value" 			=> $this->schedule->day_of_week,
				"requires"	=> array(
					array(
						"name"			=> "is_holiday",
						"value"			=> "0",
						"default_show"	=> false
					)
				),
				"disabled_for_ids" => array( 1, 2, 3, 4, 5, 6, 7 ),
			),
			array(
				"name"				=> "apply_to_retail",
				"alt_name"			=> "apply_to_retail",
				"type"				=> "hidden",/*
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => __( 'Disabled', 'wp-easycart-pro' ),
					),
					(object) array(
						'id' => '1',
						'value' => __( 'Enabled', 'wp-easycart-pro' ),
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Enable for Retail Orders", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),*/
				"value" 			=> $this->schedule->apply_to_retail,
				"show"  	=> array(
					"name" 	=> "retail_closed",
					"value"	=> 1
				),
			),
			array(
				"name"				=> "retail_closed",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => 'Open for Retail Orders',
					),
					(object) array(
						'id' => '1',
						'value' => 'Closed for Retail Orders',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Open/Close Retail Orders", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->retail_closed,
				"requires"	=> array(
					"name"			=> "apply_to_retail",
					"value"			=> 1,
					"default_show"	=> false
				),
			),
			array(
				"name"	=> "retail_start",
				"type"	=> "time",
				"label"	=> __( "Retail Start Time", 'wp-easycart-pro' ),
				"required" => false,
				"validation_type" => 'time',
				"value" => $this->schedule->retail_start,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_retail",
						"value"			=> true,
						"default_show"	=> false
					),
					array(
						"name"			=> "retail_closed",
						"value"			=> false,
						"default_show"	=> false
					),
				),
			),
			array(
				"name"	=> "retail_end",
				"type"	=> "time",
				"label"	=> __( "Retail End Time", 'wp-easycart-pro' ),
				"required" => false,
				"validation_type" => 'time',
				"value" => $this->schedule->retail_end,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_retail",
						"value"			=> true,
						"default_show"	=> false
					),
					array(
						"name"			=> "retail_closed",
						"value"			=> false,
						"default_show"	=> false
					),
				),
			),
			array(
				"name"				=> "apply_to_preorder",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => 'Disabled',
					),
					(object) array(
						'id' => '1',
						'value' => 'Enabled',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Enable for Preorders", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->apply_to_preorder,
				"show"  	=> array(
					"name" 	=> "preorder_closed",
					"value"	=> "1"
				),
			),
			array(
				"name"				=> "preorder_closed",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => 'Open for Presale Orders',
					),
					(object) array(
						'id' => '1',
						'value' => 'Closed for Presale Orders',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Open/Close Preorders", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->preorder_closed,
				"requires"	=> array(
					"name"			=> "apply_to_preorder",
					"value"			=> "1",
					"default_show"	=> false
				),
			),
			array(
				"name"	=> "preorder_start",
				"type"	=> "timer",
				"label"	=> __( "Pre-Order Start", 'wp-easycart-pro' ),
				"required" => false,
				"validation_type" => 'time',
				"value" => $this->schedule->preorder_start,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_preorder",
						"value"			=> '1',
						"default_show"	=> false
					),
					array(
						"name"			=> "preorder_closed",
						"value"			=> '0',
						"default_show"	=> false
					),
				),
			),
			array(
				"name"	=> "preorder_end",
				"type"	=> "timer",
				"label"	=> __( "Pre-Order End", 'wp-easycart-pro' ),
				"required" => false,
				"validation_type" => 'time',
				"value" => $this->schedule->preorder_end,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_preorder",
						"value"			=> '1',
						"default_show"	=> false
					),
					array(
						"name"			=> "preorder_closed",
						"value"			=> '0',
						"default_show"	=> false
					),
				),
			),
			array(
				"name"				=> "preorder_open_time",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data" => $times,
				"multiple"			=> false,
				"label" 			=> __( "Preorder Pick Up Open Time", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->preorder_open_time,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_preorder",
						"value"			=> '1',
						"default_show"	=> false
					),
					array(
						"name"			=> "preorder_closed",
						"value"			=> '0',
						"default_show"	=> false
					),
				),
			),
			array(
				"name"				=> "preorder_close_time",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data" => $times,
				"multiple"			=> false,
				"label" 			=> __( "Preorder Pick Up Close Time", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->preorder_close_time,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_preorder",
						"value"			=> '1',
						"default_show"	=> false
					),
					array(
						"name"			=> "preorder_closed",
						"value"			=> '0',
						"default_show"	=> false
					),
				),
			),
			array(
				"name"				=> "apply_to_restaurant",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => 'Disabled',
					),
					(object) array(
						'id' => '1',
						'value' => 'Enabled',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Enable for Restaurant Orders", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->apply_to_restaurant,
				"show"  	=> array(
					"name" 	=> "restaurant_closed",
					"value"	=> "1"
				),
			),
			array(
				"name"				=> "restaurant_closed",
				"type"				=> "select",
				"select2"			=> "basic",
				"validation_type"	=> "select2",
				"required" => false,
				"data"				=> array(
					(object) array(
						'id' => '0',
						'value' => 'Open for Restaurant Orders',
					),
					(object) array(
						'id' => '1',
						'value' => 'Closed for Restaurant Orders',
					),
				),
				"multiple"			=> false,
				"label" 			=> __( "Open/Close Restaurant Orders", 'wp-easycart-pro' ),
				"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
				"value" 			=> $this->schedule->restaurant_closed,
				"requires"	=> array(
					"name"			=> "apply_to_restaurant",
					"value"			=> "1",
					"default_show"	=> false
				),
			),
			array(
				"name"	=> "restaurant_start",
				"type"	=> "time",
				"label"	=> __( "Restaurant Start Time", 'wp-easycart-pro' ),
				"required" => false,
				"validation_type" => 'time',
				"value" => $this->schedule->restaurant_start,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_restaurant",
						"value"			=> "1",
						"default_show"	=> false
					),
					array(
						"name"			=> "restaurant_closed",
						"value"			=> "0",
						"default_show"	=> false
					),
				),
			),
			array(
				"name"	=> "restaurant_end",
				"type"	=> "time",
				"label"	=> __( "Restaurant End Time", 'wp-easycart-pro' ),
				"required" => false,
				"validation_type" => 'time',
				"value" => $this->schedule->restaurant_end,
				"requires"	=> array(
					array(
						"name"			=> "apply_to_restaurant",
						"value"			=> "1",
						"default_show"	=> false
					),
					array(
						"name"			=> "restaurant_closed",
						"value"			=> "0",
						"default_show"	=> false
					),
				),
			),
		);
		if ( get_option( 'ec_option_pickup_enable_locations' ) ) {
			global $wpdb;
			$locations = $wpdb->get_results( 'SELECT location_id AS id, location_label AS value FROM ec_location ORDER BY location_label ASC' );
			$selected_locations = $wpdb->get_results( $wpdb->prepare( 'SELECT location_id FROM ec_location_to_schedule WHERE schedule_id = %d', $this->id ) );
			$schedule_locations = array();
			if ( is_array( $selected_locations ) ) {
				foreach( $selected_locations as $selected_location ){
					$schedule_locations[] = (int) $selected_location->location_id;
				}
			}
			if ( is_array( $locations ) && count( $locations ) > 0 ) {
				$fields[] = array(
					"name"				=> "location_ids",
					"type"				=> "select",
					"select2"			=> "basic",
					"validation_type"	=> "select2",
					"required"			=> false,
					"data"				=> $locations,
					"multiple"			=> true,
					"label" 			=> __( "Locations", 'wp-easycart-pro' ) . ( ( ! get_option( 'ec_option_multiple_location_schedules_enabled' ) ) ? ' (' . __( 'Feature disabled, enable this in the checkout settings.', 'wp-easycart-pro' ) . ')' : '' ),
					"data_label"		=> __( "Select One", 'wp-easycart-pro' ),
					"value" 			=> $schedule_locations,
					"requires"	=> array(
						"name"			=> "apply_to_preorder",
						"value"			=> "1",
						"default_show"	=> false
					),
				);
			}
		}
		$fields = apply_filters( 'wp_easycart_admin_schedule_details_basic_fields_list', $fields );
		$this->print_fields( $fields );
	}
	
	public function print_time_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			for ( $i = 0; $i < count( $column['requires'] ); $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		$time = ( isset( $column['value'] ) && is_string( $column['value'] ) ) ? explode( ':', $column['value'] ) : array( '00', '00', '00' );
		$hour = ( isset( $time[0] ) ) ? $time[0] : '00';
		$min = ( isset( $time[1] ) ) ? $time[1] : '00';
		$second = ( isset( $time[2] ) ) ? $time[2] : '00';
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>' . esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		echo '<select autocomplete="off" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '"';
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-ec-datepicker" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo '>';
		$time_format = get_option('time_format');
		for ( $i = 0; $i < 24; $i++ ) {
			for ( $j = 0; $j < 12; $j++ ) {
				echo '<option value="' . ( ( $i < 10 ) ? '0' . $i : $i ) . ':' . ( ( $j*5 < 10 ) ? '0' . $j*5 : $j*5 ) . '"' . ( ( $column['value'] == ( ( $i < 10 ) ? '0' . $i : $i ) . ':' . ( ( $j*5 < 10 ) ? '0' . $j*5 : $j*5 ) ) ? 'selected="selected"' : '' ) . '>' . date( $time_format, strtotime( $i . ':' . ($j*5) . ':00' ) ) . '</option>';
			}
		}
		echo '</select>';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset></div></div></div>';
	}

	public function print_timer_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			for ( $i = 0; $i < count( $column['requires'] ); $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}

		$timer = ( isset( $column['value'] ) && is_string( $column['value'] ) ) ? explode( ':', $column['value'] ) : array( '00', '00', '00', '00' );
		$month = ( isset( $timer[0] ) ) ? $timer[0] : '00';
		$day = ( isset( $timer[1] ) ) ? $timer[1] : '00';
		$hour = ( isset( $timer[2] ) ) ? $timer[2] : '00';
		$minute = ( isset( $timer[3] ) ) ? $timer[3] : '00';

		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>' . esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		echo '<table><thead><tr><th>' . esc_attr__( 'Months', 'wp-easycart-pro' ) . '</th><th>' . esc_attr__( 'Days', 'wp-easycart-pro' ) . '</th><th>' . esc_attr__( 'Hours', 'wp-easycart-pro' ) . '</th><th>' . esc_attr__( 'Minutes', 'wp-easycart-pro' ) . '</th><th></th></tr></thead><tbody><tr>';
		echo '<td><select autocomplete="off" name="' . esc_attr( $column['name'] ) . '_month" id="' . esc_attr( $column['name'] ) . '_month"';
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-ec-datepicker" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo '>';
		for ( $i = 0; $i < 24; $i++ ) {
			echo '<option value="' . ( ( $i < 10 ) ? '0' . $i : $i ) . '"' . ( ( $month == ( ( $i < 10 ) ? '0' . $i : $i ) ) ? 'selected="selected"' : '' ) . '>' . $i . ' ' . esc_attr__( 'Months', 'wp-easycart-pro' ) . '</option>';
		}
		echo '</select></td>';
		echo '<td><select autocomplete="off" name="' . esc_attr( $column['name'] ) . '_day" id="' . esc_attr( $column['name'] ) . '_day"';
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-ec-datepicker" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo '>';
		for ( $i = 0; $i < 31; $i++ ) {
			echo '<option value="' . ( ( $i < 10 ) ? '0' . $i : $i ) . '"' . ( ( $day == ( ( $i < 10 ) ? '0' . $i : $i ) ) ? 'selected="selected"' : '' ) . '>' . $i . ' ' . esc_attr__( 'Days', 'wp-easycart-pro' ) . '</option>';
		}
		echo '</select></td>';
		echo '<td><select autocomplete="off" name="' . esc_attr( $column['name'] ) . '_hour" id="' . esc_attr( $column['name'] ) . '_hour"';
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-ec-datepicker" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo '>';
		for ( $i = 0; $i < 24; $i++ ) {
			echo '<option value="' . ( ( $i < 10 ) ? '0' . $i : $i ) . '"' . ( ( $hour == ( ( $i < 10 ) ? '0' . $i : $i ) ) ? 'selected="selected"' : '' ) . '>' . $i . ' ' . esc_attr__( 'Hours', 'wp-easycart-pro' ) . '</option>';
		}
		echo '</select></td>';
		echo '<td><select autocomplete="off" name="' . esc_attr( $column['name'] ) . '_minute" id="' . esc_attr( $column['name'] ) . '_minute"';
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-ec-datepicker" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo '>';
		for ( $i = 0; $i < 60; $i++ ) {
			echo '<option value="' . ( ( $i < 10 ) ? '0' . $i : $i ) . '"' . ( ( $minute == ( ( $i < 10 ) ? '0' . $i : $i ) ) ? 'selected="selected"' : '' ) . '>' . $i . ' ' . esc_attr__( 'Minutes', 'wp-easycart-pro' ) . '</option>';
		}
		echo '</select></td>';
		echo '<td>' . esc_attr__( 'Before', 'wp-easycart-pro' ) . '</td></tr></tbody></table>';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset>';
		$before_display = '';
		$min_max = ( (int) $month * 31 * 24 * 60 * 60 ) + ( (int) $day * 24 * 60 * 60 ) + ( (int) $hour * 60 * 60 ) + ( (int) $minute * 60 );
		$first_display_item = true;
		if ( $month > 0 && $month < 2 ) {
			$before_display .= (int) $month . ' ' . esc_attr__( 'Month', 'wp-easycart-pro' );
			$first_display_item = false;
		} else if ( $month > 0 && $month >= 2 ) {
			$before_display .= (int) $month . ' ' . esc_attr__( 'Months', 'wp-easycart-pro' );
			$first_display_item = false;
		}
		if ( $day > 0 && $day < 2 ) {
			if ( ! $first_display_item ) {
				$before_display .= ', ';
			}
			$before_display .= (int) $day . ' ' . esc_attr__( 'Day', 'wp-easycart-pro' );
			$first_display_item = false;
		} else if ( $day > 0 && $day >= 2 ) {
			if ( ! $first_display_item ) {
				$before_display .= ', ';
			}
			$before_display .= (int) $day . ' ' . esc_attr__( 'Days', 'wp-easycart-pro' );
			$first_display_item = false;
		}
		if ( $hour > 0 && $hour < 2 ) {
			if ( ! $first_display_item ) {
				$before_display .= ', ';
			}
			$before_display .= (int) $hour . ' ' . esc_attr__( 'Hour', 'wp-easycart-pro' );
			$first_display_item = false;
		} else if ( $hour > 0 && $hour >= 2 ) {
			if ( ! $first_display_item ) {
				$before_display .= ', ';
			}
			$before_display .= (int) $hour . ' ' . esc_attr__( 'Hours', 'wp-easycart-pro' );
			$first_display_item = false;
		}
		if ( $minute > 0 && $minute < 2 ) {
			if ( ! $first_display_item ) {
				$before_display .= ', ';
			}
			$before_display .= (int) $minute . ' ' . esc_attr__( 'Minute', 'wp-easycart-pro' );
			$first_display_item = false;
		} else if ( $minute > 0 && $minute >= 2 ) {
			if ( ! $first_display_item ) {
				$before_display .= ', ';
			}
			$before_display .= (int) $minute . ' ' . esc_attr__( 'Minutes', 'wp-easycart-pro' );
			$first_display_item = false;
		}
		if ( $this->schedule->is_holiday ) {
			$date_time = $this->schedule->holiday_date . ' 00:00:00';
		} else {
			$date_time = 'next ' . $this->schedule->day_of_week;
		}
		$wp_timezone = wp_timezone();
		if ( ! $wp_timezone ) {
			$wp_timezone = 'America/Los_Angeles';
		}
		$rel_string = '-' . $month . ' month -' . $day . ' day -' . $hour . ' hour -' . $minute . ' minute';
		$future_date_obj = new DateTime( $date_time, $wp_timezone );
		$adjusted_date_obj = clone $future_date_obj;
		$adjusted_date_obj->modify( $rel_string );
		$wp_date_format = 'M d, Y';
		$wp_time_format = 'g:i a';
		$wp_datetime_format_with_tz = $wp_date_format . ' ' . $wp_time_format;
		$formatted_original_date = $future_date_obj->format( $wp_datetime_format_with_tz );
		$formatted_adjusted_date = $adjusted_date_obj->format( $wp_datetime_format_with_tz );
		$formatted_original_date_attr = $future_date_obj->format( 'Y-m-d\TH:i:00' );
		echo '<div class="wpec-preview-date" id="' . esc_attr( $column['name'] ) . '_preview" data-future-pickup-date="' . esc_attr( $formatted_original_date_attr ) . '" data-month="' . esc_attr__( 'Month', 'wp-easycart-pro' ) . '" data-months="' . esc_attr__( 'Months', 'wp-easycart-pro' ) . '" data-day="' . esc_attr__( 'Day', 'wp-easycart-pro' ) . '" data-days="' . esc_attr__( 'Days', 'wp-easycart-pro' ) . '" data-hour="' . esc_attr__( 'Hour', 'wp-easycart-pro' ) . '" data-hours="' . esc_attr__( 'Hours', 'wp-easycart-pro' ) . '" data-minute="' . esc_attr__( 'Minute', 'wp-easycart-pro' ) . '" data-minutes="' . esc_attr__( 'Minutes', 'wp-easycart-pro' ) . '">';
		echo '<span class="wpec-preview-before-info">' . esc_attr( $before_display ) . '</span> ';
		echo sprintf( esc_attr__( 'before %s is', 'wp-easycart-pro' ), '<span class="wpec-preview-start-date">' . esc_attr( $formatted_original_date ) . '</span>' );
		echo ' <span class="wpec-preview-adjusted-date">' . esc_attr( $formatted_adjusted_date ) . '</span>';
		echo '</div>';
		echo '</div></div></div>';
	}
}
