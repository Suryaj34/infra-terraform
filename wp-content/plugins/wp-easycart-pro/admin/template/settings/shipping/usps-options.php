<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php _e( 'USPS Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'shipping-settings', 'usps');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-settings', 'usps'); ?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_<?php echo ( get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group' ) ){ ?>

			<?php if ( ! get_option( 'ec_option_usps_v3_enable' ) && '' != wp_easycart_admin()->settings->usps_user_name ) { ?>
			
				<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_usps_v3_enable', 'ec_admin_toggle_usps_pro', get_option( 'ec_option_usps_v3_enable' ), __( 'Upgrade to USPS V3', 'wp-easycart-pro' ), __( 'You may upgrade your USPS integration by toggling this on.', 'wp-easycart-pro' ) ); ?>
			
			<?php } else { ?>
				
				<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_usps_v3_enable', 'ec_admin_toggle_usps_pro', get_option( 'ec_option_usps_v3_enable' ), __( 'Enable USPS', 'wp-easycart-pro' ), __( 'Enable to add USPS live shipping rates.', 'wp-easycart-pro' ) ); ?>
			
			<?php } ?>
		
			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_usps_v3_custom', 'ec_admin_toggle_usps_pro', get_option( 'ec_option_usps_v3_custom' ), __( 'Enter Your Own Credentials', 'wp-easycart-pro' ), __( 'You may choose to create your own USPS app and enter credentials to connect, instead of relying on the WP EasyCart USPS app.', 'wp-easycart-pro' ), 'ec_option_usps_v3_custom_row', get_option( 'ec_option_usps_v3_enable' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_usps_v3_custom_old', 'ec_admin_toggle_usps_pro', get_option( 'ec_option_usps_v3_custom_old' ), __( 'Revert to Web Tools', 'wp-easycart-pro' ), __( 'Only choose this option if you have to, it will no longer work after January 2026.', 'wp-easycart-pro' ), 'ec_option_usps_v3_custom_old_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom' ) ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'usps_user_name', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin()->settings->usps_user_name, __( 'API User Name', 'wp-easycart-pro' ), __( 'This is your API User Name from your USPS Web Account. Usually in the format: 123ABCD1234.', 'wp-easycart-pro' ), '', 'ec_admin_usps_user_name_row', ( ! get_option( 'ec_option_usps_v3_enable' ) && '' != wp_easycart_admin()->settings->usps_user_name ) || ( get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom' ) && get_option( 'ec_option_usps_v3_custom_old' ) ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_usps_v3_client_id', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_usps_v3_client_id' ), __( 'USPS V3 Client ID', 'wp-easycart-pro' ), __( 'This is your App Client ID from your USPS Developer Account.', 'wp-easycart-pro' ), '', 'ec_option_usps_v3_client_id_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom' ) && ! get_option( 'ec_option_usps_v3_custom_old' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_usps_v3_client_secret', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_usps_v3_client_secret' ), __( 'USPS V3 Client Secret', 'wp-easycart-pro' ), __( 'This is your App Client Secret from your USPS Developer Account.', 'wp-easycart-pro' ), '', 'ec_option_usps_v3_client_secret_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom' ) && ! get_option( 'ec_option_usps_v3_custom_old' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'usps_ship_from_zip', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->usps_ship_from_zip, __( 'Origin Zip', 'wp-easycart-pro' ), __( 'This is the zip code you are shipping packages from.', 'wp-easycart-pro' ), '', 'ec_admin_usps_ship_from_zip_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'usps_conversion_rate', 'ec_admin_save_shipping_text_setting_pro', get_option( 'usps_conversion_rate' ), __( 'Conversion Rate', 'wp-easycart-pro' ), __( 'This will convert rates from the response currency into your base currency, also used to pad costs higher or lower.', 'wp-easycart-pro' ), '', 'ec_admin_usps_conversion_rate_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_usps_v3_custom_rates', 'ec_admin_toggle_usps_pro', get_option( 'ec_option_usps_v3_custom_rates' ), __( 'Enable Custom Account Rates', 'wp-easycart-pro' ), __( 'You may enter your credentials to get account specific rates.', 'wp-easycart-pro' ), 'ec_option_usps_v3_custom_rates_row', get_option( 'ec_option_usps_v3_enable' ) && ! get_option( 'ec_option_usps_v3_custom_old' ) ); ?>

			<?php $price_type_options = array(
				(object) array(
					'value'	=> 'RETAIL',
					'label'	=> 'Retail',
				),
				(object) array(
					'value'	=> 'CONTRACT',
					'label'	=> 'Contract',
				),
				(object) array(
					'value'	=> 'COMMERCIAL',
					'label'	=> 'Commercial',
				),
			); ?>
			<?php $account_type_options = array(
				(object) array(
					'value'	=> 'EPS',
					'label'	=> 'EPS',
				),
				(object) array(
					'value'	=> 'PERMIT',
					'label'	=> 'PERMIT',
				),
				(object) array(
					'value'	=> 'METER',
					'label'	=> 'METER',
				),
				(object) array(
					'value'	=> 'MID',
					'label'	=> 'MID',
				),
			); ?>
			<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_usps_v3_price_type', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_usps_v3_price_type' ), __( 'Price Type', 'wp-easycart-pro' ), __( 'The price type for your account.', 'wp-easycart-pro' ), $price_type_options, 'ec_option_usps_v3_price_type_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom_rates' ) && ! get_option( 'ec_option_usps_v3_custom_old' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_usps_v3_account_type', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_usps_v3_account_type' ), __( 'Payment Account Type', 'wp-easycart-pro' ), __( 'The type of payment account you are using.', 'wp-easycart-pro' ), $account_type_options, 'ec_option_usps_v3_account_type_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom_rates' ) && ! get_option( 'ec_option_usps_v3_custom_old' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_usps_v3_account_number', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_usps_v3_account_number' ), __( 'Your Account Number', 'wp-easycart-pro' ), __( 'The Enterprise Payment Account, Permit number, Mailer ID (MID), or PC Postage meter number associated with a contract.', 'wp-easycart-pro' ), '', 'ec_option_usps_v3_account_number_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom_rates' ) && ! get_option( 'ec_option_usps_v3_custom_old' ), false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_usps_v3_crid', 'ec_admin_save_shipping_text_setting_pro', get_option( 'ec_option_usps_v3_crid' ), __( 'Your CRID', 'wp-easycart-pro' ), __( 'Customer Registration ID (CRID) associated with the business or mailer.', 'wp-easycart-pro' ), '', 'ec_option_usps_v3_crid_row', get_option( 'ec_option_usps_v3_enable' ) && get_option( 'ec_option_usps_v3_custom_rates' ) && ! get_option( 'ec_option_usps_v3_custom_old' ), false ); ?>

		<?php } else { ?>

			<?php echo __( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

		<?php $usps_status = wp_easycart_admin_live_shipping_rates_pro( )->get_usps_status( ); ?>
		<div class="ec_admin_live_shipping_status_connected"<?php echo ( $usps_status != 'connected' ) ? ' style="display:none"' : ''; ?> id="ec_admin_usps_status_connected"><?php _e( 'Connected', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_error"<?php echo ( $usps_status != 'error' ) ? ' style="display:none"' : ''; ?> id="ec_admin_usps_status_error"><?php _e( 'Error', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_disabled"<?php echo ( $usps_status != 'disabled' ) ? ' style="display:none"' : ''; ?> id="ec_admin_usps_status_disabled"><?php _e( 'Disabled', 'wp-easycart-pro' ); ?></div>

	</div>
	
	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">
		<?php esc_attr_e( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings.', 'wp-easycart-pro' ); ?>
	</div>

</div>
