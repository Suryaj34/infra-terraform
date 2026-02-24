<?php
global $wpdb;
$country_none = array( 
	(object) array(
		'value' => '',
		'label' => __( 'Select One', 'wp-easycart-pro' )
	)
);
$country_list = $wpdb->get_results( "SELECT iso2_cnt AS value, name_cnt AS label FROM ec_country ORDER BY sort_order ASC" );
$countries = array_merge( $country_none, $country_list );
$weight_units = array(
	(object) array(
		'value' => '',
		'label' => __( 'Select One', 'wp-easycart-pro' )
	),
	(object) array(
		'value' => 'LBS',
		'label' => __( 'LBS', 'wp-easycart-pro' )
	),
	(object) array(
		'value' => 'KGS',
		'label' => __( 'KGS', 'wp-easycart-pro' )
	)
);
?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php _e( 'UPS Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'shipping-settings', 'ups');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'shipping-settings', 'ups' );?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_<?php echo ( get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group' ) ){ ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_ups_use_oauth', 'ec_admin_toggle_ups_pro', get_option( 'ec_option_ups_use_oauth' ), __( 'Enable UPS', 'wp-easycart-pro' ), __( 'Enable this to connect with UPS. You must also click to connect and fill out required settings below.', 'wp-easycart-pro' ) ); ?>

			<?php $redirect_uri = urlencode( admin_url( ) . '?ec_admin_form_action=ups-onboard&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-ups' ) ); ?>

			<?php $ec_option_ups_settings = ( get_option( 'ec_option_ups_settings' ) ) ? get_option( 'ec_option_ups_settings' ) : array( 'address1' => '', 'address2' => '', 'address3' => '', 'city' => '', 'state' => '' ); ?>

			<div id="ups_oauth_buttons"<?php if ( ! get_option( 'ec_option_ups_use_oauth' ) ) { ?> style="display:none;"<?php }?>>
			<?php if ( get_option( 'ec_option_ups_token_info' ) ) { ?>
				<a href="https://connect.wpeasycart.com/ups/?step=start&redirect=<?php echo urlencode( $redirect_uri ); ?>" id="ups_oauth_connect" style="margin:0 0 35px;" class="wpeasycart-add-text-notification-account-button"><?php esc_attr_e( 'Reconnect or Switch UPS Account', 'wp-easycart-pro' ); ?></a>
				<div style="float:left; width:100%; margin:-25px 0 30px;">
					<?php $refresh_url = admin_url( ) . '?ec_admin_form_action=ups-refresh&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-ups' ); ?>
					<a href="<?php echo esc_url_raw( $refresh_url ); ?>"><?php esc_attr_e( 'Refresh or Fix Connection', 'wp-easycart-pro' ); ?></a> | 
					<?php $disconnect_url = admin_url( ) . '?ec_admin_form_action=ups-disconnect&wp_easycart_nonce=' . wp_create_nonce( 'wp-easycart-ups' ); ?>
					<a href="<?php echo esc_url_raw( $disconnect_url ); ?>"><?php esc_attr_e( 'Disconnect', 'wp-easycart-pro' ); ?></a>
				</div>
			<?php } else { ?>
				<div style="float:left; width:100%; margin:15px 0 5px; font-size:1.2em;"><strong><?php esc_attr_e( 'Your UPS Account is NOT Connected, Click the button below to get started!', 'wp-easycart-pro' ); ?></strong></div>
				<a href="https://connect.wpeasycart.com/ups/?step=start&redirect=<?php echo urlencode( $redirect_uri ); ?>" id="ups_oauth_connect" style="margin:0 0 35px;" class="wpeasycart-add-text-notification-account-button"<?php echo ( ! get_option( 'ec_option_ups_use_oauth' ) ) ? ' style="display:none;"' : ''; ?>><?php esc_attr_e( 'Link Your UPS Account', 'wp-easycart-pro' ); ?></a>
			<?php } ?>
			</div>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_shipper_number', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->ups_shipper_number, __( 'Shipper Number', 'wp-easycart-pro' ), __( 'This is from your UPS Account.', 'wp-easycart-pro' ), '', 'ec_admin_ups_shipper_number_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'ups_weight_type', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->ups_weight_type, __( 'Weight Unit', 'wp-easycart-pro' ), __( 'The standard weight unit of shipments and your website product weight.', 'wp-easycart-pro' ), $weight_units, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_ship_from_address1', 'ec_admin_save_shipping_text_setting_pro', $ec_option_ups_settings['address1'], __( 'Origin Address (Line 1)', 'wp-easycart-pro' ), __( 'This is the address (line 1) that you ship from.', 'wp-easycart-pro' ), '', 'ec_admin_ups_ship_from_address1_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_ship_from_address2', 'ec_admin_save_shipping_text_setting_pro', $ec_option_ups_settings['address2'], __( 'Origin Address (Line 2)', 'wp-easycart-pro' ), __( 'This is the address (line 2) that you ship from.', 'wp-easycart-pro' ), '', 'ec_admin_ups_ship_from_address2_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_ship_from_address3', 'ec_admin_save_shipping_text_setting_pro', $ec_option_ups_settings['address3'], __( 'Origin Address (Line 3)', 'wp-easycart-pro' ), __( 'This is the address (line 3) that you ship from.', 'wp-easycart-pro' ), '', 'ec_admin_ups_ship_from_address3_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_ship_from_city', 'ec_admin_save_shipping_text_setting_pro', $ec_option_ups_settings['city'], __( 'Origin City', 'wp-easycart-pro' ), __( 'This is the city you ship from.', 'wp-easycart-pro' ), '', 'ec_admin_ups_ship_from_city_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_ship_from_state', 'ec_admin_save_shipping_text_setting_pro', $ec_option_ups_settings['state'], __( 'Origin State/Province (2 Character Code)', 'wp-easycart-pro' ), __( 'This is the 2 digit state/province you ship from.', 'wp-easycart-pro' ), '', 'ec_admin_ups_ship_from_state_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_ship_from_zip', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->ups_ship_from_zip, __( 'Origin Postal Code', 'wp-easycart-pro' ), __( 'This is the postal code you ship from.', 'wp-easycart-pro' ), '', 'ec_admin_ups_ship_from_zip_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'ups_country_code', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->ups_country_code, __( 'Origin Country', 'wp-easycart-pro' ), __( 'This is the country you will be shipping from.', 'wp-easycart-pro' ), $countries, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ups_conversion_rate', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->ups_conversion_rate, __( 'Conversion Rate', 'wp-easycart-pro' ), __( 'This will convert rates from the response currency into your base currency, also used to pad costs higher or lower.', 'wp-easycart-pro' ), '', 'ec_admin_ups_conversion_rate_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ups_negotiated_rates', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->ups_negotiated_rates, __( 'Negotiated Rates', 'wp-easycart-pro' ), __( 'Enabling this will get negotiated rates, if available.', 'wp-easycart-pro' ) ); ?>

		<?php }else{ ?>

			<?php echo __( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

		<?php $ups_status = wp_easycart_admin_live_shipping_rates_pro( )->get_ups_status( ); ?>
		<div class="ec_admin_live_shipping_status_connected"<?php echo ( $ups_status != 'connected' ) ? ' style="display:none"' : ''; ?> id="ec_admin_ups_status_connected"><?php _e( 'Connected', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_error"<?php echo ( $ups_status != 'error' ) ? ' style="display:none"' : ''; ?> id="ec_admin_ups_status_error"><?php _e( 'Error', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_disabled"<?php echo ( $ups_status != 'disabled' ) ? ' style="display:none"' : ''; ?> id="ec_admin_ups_status_disabled"><?php _e( 'Disabled', 'wp-easycart-pro' ); ?></div>

	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">
		<?php esc_attr_e( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings.', 'wp-easycart-pro' ); ?>
	</div>

</div>