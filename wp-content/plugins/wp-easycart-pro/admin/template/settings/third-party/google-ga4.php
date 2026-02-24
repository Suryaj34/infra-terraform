<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_google_ga4_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Google Analytics GA4 Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'google-ga4' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'third-party', 'google-ga4');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_google_ga4_property_id', 'ec_admin_save_google_ga4_pro_text', get_option( 'ec_option_google_ga4_property_id' ), __( 'Google GA4 Property ID', 'wp-easycart-pro' ), __( 'This is the property ID for your Google GA4 account.', 'wp-easycart-pro' ), '', 'ec_option_google_ga4_property_id_row', true, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_google_ga4_tag_manager', 'ec_admin_save_google_ga4_pro', get_option( 'ec_option_google_ga4_tag_manager' ), __( 'Enable Tag Manager Type', 'wp-easycart-pro' ), __( 'Default is Google Tags, but switching this on will switch to the tag manager.', 'wp-easycart-pro' ), '', 'ec_option_google_ga4_tag_manager_row', true, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_google_ga4_tag_manager_direct', 'ec_admin_save_google_ga4_pro', get_option( 'ec_option_google_ga4_tag_manager_direct' ), __( 'Enable Tag Manager Direct Integration', 'wp-easycart-pro' ), __( 'This allows you to add server to server direct integration for Google Tags and your eCommerce data.', 'wp-easycart-pro' ), '', 'ec_option_google_ga4_tag_manager_direct_row', true, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_google_ga4_tag_manager_measurement_id', 'ec_admin_save_google_ga4_pro_text', get_option( 'ec_option_google_ga4_tag_manager_measurement_id' ), __( 'Tags Mesurement ID', 'wp-easycart-pro' ), __( 'This is the measurement ID from your GA4 stream. Even if not connecting data in the tag manager container, you still must enter a valid measurement ID.', 'wp-easycart-pro' ), '', 'ec_option_google_ga4_tag_manager_measurement_id_row', get_option( 'ec_option_google_ga4_tag_manager_direct' ), false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_google_ga4_tag_manager_api_secret', 'ec_admin_save_google_ga4_pro_text', get_option( 'ec_option_google_ga4_tag_manager_api_secret' ), __( 'Tags API Secret', 'wp-easycart-pro' ), __( 'This is the API Secret for your Google Tags Direct Integration.', 'wp-easycart-pro' ), '', 'ec_option_google_ga4_tag_manager_api_secret_row', get_option( 'ec_option_google_ga4_tag_manager_direct' ), false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_google_ga4_tag_manager_server_url', 'ec_admin_save_google_ga4_pro_text', get_option( 'ec_option_google_ga4_tag_manager_server_url' ), __( 'Tags Server Container URL', 'wp-easycart-pro' ), __( 'This is the Server Container URL for your Google Tags Direct Integration.', 'wp-easycart-pro' ), '', 'ec_option_google_ga4_tag_manager_server_url_row', get_option( 'ec_option_google_ga4_tag_manager_direct' ), false ); ?>
	</div>
</div>
