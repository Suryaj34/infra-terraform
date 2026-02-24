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
		'value' => 'LB',
		'label' => __( 'LBS', 'wp-easycart-pro' )
	),
	(object) array(
		'value' => 'KG',
		'label' => __( 'KGS', 'wp-easycart-pro' )
	)
);
?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php _e( 'FedEx Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'shipping-settings', 'fedex');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-settings', 'fedex');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_<?php echo ( get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group' ) ){ ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_fedex_use_oauth', 'ec_admin_toggle_fedex_pro', get_option( 'ec_option_fedex_use_oauth' ), __( 'Enable FedEx', 'wp-easycart-pro' ), __( 'You will need to follow the on screen instructions to create and connect your FedEx account.', 'wp-easycart-pro' ) ); ?>

			<div class="ec_fedex_help" id="ec_fedex_help_v2"<?php if ( ! get_option( 'ec_option_fedex_use_oauth' ) ) { ?> style="display:none;"<?php }?>><a href="https://docs.wpeasycart.com/docs/administrative-console-guide/live-fedex-shipping-rates/" target="_blank"><?php esc_attr_e( 'Click here for help connecting to FedEx.', 'wp-easycart-pro' ); ?></a> | <a href="https://developer.fedex.com/" target="_blank"><?php esc_attr_e( 'Log into Your FedEx Developer Account.', 'wp-easycart-pro' ); ?></a></div>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_fedex_api_key', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_fedex_api_key' ), __( 'API Key', 'wp-easycart-pro' ), __( 'This is from your FedEx Account.', 'wp-easycart-pro' ), '', 'ec_admin_fedex_api_key', get_option( 'ec_option_fedex_use_oauth' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_fedex_api_secret_key', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_fedex_api_secret_key' ), __( 'API Secret Key', 'wp-easycart-pro' ), __( 'This is from your FedEx Account.', 'wp-easycart-pro' ), '', 'ec_admin_fedex_api_secret_key_row', get_option( 'ec_option_fedex_use_oauth' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fedex_account_number', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->fedex_account_number, __( 'Account Number', 'wp-easycart-pro' ), __( 'This is from your FedEx Account.', 'wp-easycart-pro' ), '', 'ec_admin_fedex_account_number_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fedex_ship_from_zip', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->fedex_ship_from_zip, __( 'Origin Postal Code', 'wp-easycart-pro' ), __( 'This is the postal code you will be shipping from.', 'wp-easycart-pro' ), '', 'ec_admin_fedex_ship_from_zip_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'fedex_country_code', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->fedex_country_code, __( 'Origin Country', 'wp-easycart-pro' ), __( 'This is the country you will be shipping from.', 'wp-easycart-pro' ), $countries, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'fedex_weight_units', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->fedex_weight_units, __( 'Weight Unit', 'wp-easycart-pro' ), __( 'The standard weight unit of shipments and your website product weight.', 'wp-easycart-pro' ), $weight_units, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fedex_conversion_rate', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->fedex_conversion_rate, __( 'Conversion Rate', 'wp-easycart-pro' ), __( 'This is the conversion rate to use from returning FedEx rates to your base currency (or use to add a percentage to every rate).', 'wp-easycart-pro' ), '1.000', 'ec_admin_fedex_conversion_rate_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_fedex_use_check_address_type', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_fedex_use_check_address_type' ), __( 'Check Address Type', 'wp-easycart-pro' ), __( 'Enabling this will check the address type prior to getting the shipping rate. If found to be business, rates will be returned for a business address. You must request an additional permission from FedEx to use the Address Validation Service (AVS).', 'wp-easycart-pro' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'fedex_test_account', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->fedex_test_account, __( 'Test Mode', 'wp-easycart-pro' ), __( 'Enabling this will get requests in test mode through FedEx.', 'wp-easycart-pro' ) ); ?>

		<?php }else{ ?>

			<?php echo __( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

		<?php $fedex_status = wp_easycart_admin_live_shipping_rates_pro( )->get_fedex_status( ); ?>
		<div class="ec_admin_live_shipping_status_connected"<?php echo ( $fedex_status != 'connected' ) ? ' style="display:none"' : ''; ?> id="ec_admin_fedex_status_connected"><?php _e( 'Connected', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_error"<?php echo ( $fedex_status != 'error' ) ? ' style="display:none"' : ''; ?> id="ec_admin_fedex_status_error"><?php _e( 'Error', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_disabled"<?php echo ( $fedex_status != 'disabled' ) ? ' style="display:none"' : ''; ?> id="ec_admin_fedex_status_disabled"><?php _e( 'Disabled', 'wp-easycart-pro' ); ?></div>

	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">
		<?php esc_attr_e( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings.', 'wp-easycart-pro' ); ?>
	</div>

</div>
