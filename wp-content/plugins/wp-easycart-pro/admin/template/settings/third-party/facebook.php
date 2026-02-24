<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_facebook_settings_loader" ); ?>
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Facebook Pixel Setup', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'facebook' );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'third-party', 'amazon' );?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		<div>
			<?php esc_attr_e( 'Facebook Pixel ID', 'wp-easycart-pro' ); ?>
			<input type="text" name="ec_option_fb_pixel" id="ec_option_fb_pixel" value="<?php echo get_option('ec_option_fb_pixel'); ?>" />
		</div>
		<div class="ec_admin_settings_input">
			<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_facebook_settings_pro( );" value="<?php esc_attr_e( 'Save Setup', 'wp-easycart-pro' ); ?>" />
		</div>
	</div>
</div>