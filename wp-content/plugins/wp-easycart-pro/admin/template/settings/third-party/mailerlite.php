<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_mailerlite_settings_loader" ); ?>
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Mailer Lite Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'mailerlite' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'third-party', 'mailerlite' );?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_mailerlite', 'ec_admin_save_mailerlite_settings', get_option( 'ec_option_enable_mailerlite' ), __( 'Enable Mailer Lite', 'wp-easycart-pro' ), __( 'This will allow you to connect your store to a Mailer Lite.', 'wp-easycart-pro' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_mailerlite_api_key', 'ec_admin_save_mailerlite_settings', get_option( 'ec_option_mailerlite_api_key' ), __( 'Mailer Lite API Token', 'wp-easycart-pro' ), __( 'This is the API Token required to send data to your Mailer Lite account from WP EasyCart.', 'wp-easycart-pro' ), '', 'ec_admin_save_mailerlite_settings_key_row', get_option( 'ec_option_enable_mailerlite' ), false ); ?>
	</div>
</div>