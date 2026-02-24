<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-feedback"></div>
		<span><?php _e( 'Order Text Notifications', 'wp-easycart-pro' ); ?></span>
		<?php wp_easycart_admin( )->preloader->print_saved_icon( "ec_admin_checkout_form_settings_saved" ); ?>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'checkout', 'text-notifications');?>" target="_blank" class="ec_help_icon_link" title="<?php _e( 'View Help?', 'wp-easycart-pro' ); ?>">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'checkout', 'text-notifications' ); ?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
		<img src="<?php echo plugins_url( 'wp-easycart-pro/admin/images/checkout-settings-texting-ad.jpg' ); ?>" class="wpeasycart-add-text-notification-image" />
		
		<div>
			<a href="https://www.wpeasycart.com/cloud-services-customer-text-alert-messaging/" target="_blank" class="wpeasycart-add-text-notification-button"><?php esc_attr_e( 'Add Text Notifications', 'wp-easycart-pro' ); ?></a>

			<hr />

			<h4 class="wpeasycart-add-text-notification-header"><?php esc_attr_e( 'Already Purchased?', 'wp-easycart-pro' ); ?></h4>
			<div><?php esc_attr_e( 'If you\'ve already purchased the text notification service, please visit your account here and connect your service to your license. Once you do this, this area will automatically update to show the text notification setup options.', 'wp-easycart-pro' ); ?></div>
			
			<a href="https://www.wpeasycart.com/my-account/?ec_page=dashboard" target="_blank" class="wpeasycart-add-text-notification-account-button"><?php esc_attr_e( 'Visit Your Account', 'wp-easycart-pro' ); ?></a>
		</div>
		
	</div>
</div>