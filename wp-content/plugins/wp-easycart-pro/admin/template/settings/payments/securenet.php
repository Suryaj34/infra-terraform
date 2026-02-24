<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "securenet" ){ ?>show<?php }else{?>hide<?php }?>" id="securenet">
	<span><?php esc_attr_e( 'Setup WorldPay', 'wp-easycart-pro' ); ?></span>
	<div>
		<?php esc_attr_e( 'SecureNet ID', 'wp-easycart-pro' ); ?>
		<input name="ec_option_securenet_id"  id="ec_option_securenet_id" type="text" value="<?php echo get_option('ec_option_securenet_id'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Secure Key', 'wp-easycart-pro' ); ?>
		<input name="ec_option_securenet_secure_key"  id="ec_option_securenet_secure_key" type="password" value="<?php echo get_option('ec_option_securenet_secure_key'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Sandbox Mode', 'wp-easycart-pro' ); ?>
		<select name="ec_option_securenet_use_sandbox" id="ec_option_securenet_use_sandbox">
			<option value="0" <?php if (get_option('ec_option_securenet_use_sandbox') == 0) echo ' selected'; ?>><?php esc_attr_e( 'No', 'wp-easycart-pro' ); ?></option>
			<option value="1" <?php if (get_option('ec_option_securenet_use_sandbox') == 1) echo ' selected'; ?>><?php esc_attr_e( 'Yes', 'wp-easycart-pro' ); ?></option>
		</select>
	</div>
	<div class="ec_admin_settings_input" style="padding-right:0px;">
		<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_securenet_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart-pro' ); ?>" />
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'worldpay' );?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart-pro' ); ?>" style="margin-top:0px; margin-right:0px;">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
	</div>
</div>