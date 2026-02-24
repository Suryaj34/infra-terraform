<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php esc_attr_e( 'Australia Post Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'shipping-settings', 'australia-post');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-settings', 'australia-post');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_<?php echo ( get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">

		<?php if( method_exists( wp_easycart_admin( ), 'load_toggle_group' ) ){ ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'auspost_api_key', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->auspost_api_key, __( 'API Key', 'wp-easycart-pro' ), __( 'This is from your Australia Post Account.', 'wp-easycart-pro' ), '', 'ec_admin_auspost_api_key_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'auspost_ship_from_zip', 'ec_admin_save_shipping_text_setting_pro', wp_easycart_admin( )->settings->auspost_ship_from_zip, __( 'Ship From Postal Code', 'wp-easycart-pro' ), __( 'This is the postal code you are shipping from, used for rate calculation.', 'wp-easycart-pro' ), '', 'ec_admin_auspost_ship_from_zip_row', true, false ); ?>

		<?php }else{ ?>

			<?php esc_attr_e( 'Pro feature missing. Please update your WP EasyCart Plugin to fix this issue.', 'wp-easycart-pro' ); ?>

		<?php } ?>

		<?php $auspost_status = wp_easycart_admin_live_shipping_rates_pro( )->get_auspost_status( ); ?>
		<div class="ec_admin_live_shipping_status_connected"<?php echo ( $auspost_status != 'connected' ) ? ' style="display:none"' : ''; ?> id="ec_admin_auspost_status_connected"><?php esc_attr_e( 'Connected', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_error"<?php echo ( $auspost_status != 'error' ) ? ' style="display:none"' : ''; ?> id="ec_admin_auspost_status_error"><?php esc_attr_e( 'Error', 'wp-easycart-pro' ); ?></div>
		<div class="ec_admin_live_shipping_status_disabled"<?php echo ( $auspost_status != 'disabled' ) ? ' style="display:none"' : ''; ?> id="ec_admin_auspost_status_disabled"><?php esc_attr_e( 'Disabled', 'wp-easycart-pro' ); ?></div>

	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">
		<?php esc_attr_e( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings.', 'wp-easycart-pro' ); ?>
	</div>

</div>