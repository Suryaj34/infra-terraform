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
		<span><?php _e( 'DHL Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'shipping-settings', 'dhl');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'shipping-settings', 'dhl' );?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_<?php echo ( get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group' ) ){ ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'dhl_site_id', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->dhl_site_id, __( 'DHL Site ID', 'wp-easycart-pro' ), __( 'This is from your DHL Account.', 'wp-easycart-pro' ), '', 'ec_admin_dhl_site_id_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'dhl_password', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->dhl_password, __( 'DHL Site Password', 'wp-easycart-pro' ), __( 'This is from your DHL Account.', 'wp-easycart-pro' ), '', 'ec_admin_dhl_password_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_dhl_account_number', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_dhl_account_number' ), __( 'DHL Account Number', 'wp-easycart-pro' ), __( 'This is the account number for your DHL account.', 'wp-easycart-pro' ), '', 'ec_admin_ec_option_dhl_account_number_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'dhl_ship_from_zip', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->dhl_ship_from_zip, __( 'Origin Postal Code', 'wp-easycart-pro' ), __( 'This is the postal code you will be shipping from.', 'wp-easycart-pro' ), '', 'ec_admin_dhl_ship_from_zip_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'dhl_ship_from_country', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->dhl_ship_from_country, __( 'Origin Country', 'wp-easycart-pro' ), __( 'This is the country you will be shipping from.', 'wp-easycart-pro' ), $countries, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'dhl_weight_unit', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->dhl_weight_unit, __( 'Weight Unit', 'wp-easycart-pro' ), __( 'The standard weight unit of shipments and your website product weight.', 'wp-easycart-pro' ), $weight_units, '', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'dhl_test_mode', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->dhl_test_mode, __( 'Test Mode', 'wp-easycart-pro' ), __( 'Enabling this will get requests in test mode through DHL.', 'wp-easycart-pro' ) ); ?>

		<?php }else{ ?>

			<?php echo __( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

		<?php $dhl_status = wp_easycart_admin_live_shipping_rates_pro( )->get_dhl_status( ); ?>
		<div class="ec_admin_live_shipping_status_connected"<?php echo ( $dhl_status != 'connected' ) ? ' style="display:none"' : ''; ?> id="ec_admin_dhl_status_connected"><?php _e( 'Connected', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_error"<?php echo ( $dhl_status != 'error' ) ? ' style="display:none"' : ''; ?> id="ec_admin_dhl_status_error"><?php _e( 'Error', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_disabled"<?php echo ( $dhl_status != 'disabled' ) ? ' style="display:none"' : ''; ?> id="ec_admin_dhl_status_disabled"><?php _e( 'Disabled', 'wp-easycart-pro' ); ?></div>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">
		<?php esc_attr_e( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings.', 'wp-easycart-pro' ); ?>
	</div>

</div>