<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_shareasale_settings_loader" ); ?>
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'ShareASale Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'shareasale' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'third-party', 'shareasale' );?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_shareasale', 'ec_admin_save_shareasale_settings', get_option( 'ec_option_enable_shareasale' ), __( 'Enable ShareASale', 'wp-easycart-pro' ), __( 'This will allow you to connect your store to a ShareASale merchant account.', 'wp-easycart-pro' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_shareasale_merchant_id', 'ec_admin_save_shareasale_settings', get_option( 'ec_option_shareasale_merchant_id' ), __( 'ShareASale Merchant ID', 'wp-easycart-pro' ), __( 'This is the merchant ID that connects your site to ShareASale', 'wp-easycart-pro' ), '', 'ec_admin_ec_option_shareasale_merchant_id_row', get_option( 'ec_option_enable_shareasale' ), false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_shareasale_send_details', 'ec_admin_save_shareasale_settings', get_option( 'ec_option_shareasale_send_details' ), __( 'Send Cart Details to ShareASale', 'wp-easycart-pro' ), __( 'Enable this to send sku, price, and quantity to ShareASale.', 'wp-easycart-pro' ), 'ec_option_shareasale_send_details_row', get_option( 'ec_option_enable_shareasale' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_shareasale_currency_conversion', 'ec_admin_save_shareasale_settings', get_option( 'ec_option_shareasale_currency_conversion' ), __( 'Use Currency Conversion', 'wp-easycart-pro' ), __( 'This feature is for advanced users only. It will convert line items based on the checkout currency display and your settings and send that info to ShareASale.', 'wp-easycart-pro' ), 'ec_option_shareasale_currency_conversion_row', get_option( 'ec_option_enable_shareasale' ) ); ?>
	</div>
</div>