<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "payfast_thirdparty" ){ ?>show<?php }else{?>hide<?php }?>" id="payfast_thirdparty">
	<span><?php esc_attr_e( 'Setup PayFast', 'wp-easycart-pro' ); ?></span>
	<div>
		<?php esc_attr_e( 'Merchant ID', 'wp-easycart-pro' ); ?>
		<input name="ec_option_payfast_merchant_id"  id="ec_option_payfast_merchant_id" type="text" value="<?php echo get_option('ec_option_payfast_merchant_id'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Merchant Key', 'wp-easycart-pro' ); ?>
		<input name="ec_option_payfast_merchant_key"  id="ec_option_payfast_merchant_key" type="text" value="<?php echo get_option('ec_option_payfast_merchant_key'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Passphrase (optional)', 'wp-easycart-pro' ); ?>
		<input name="ec_option_payfast_passphrase"  id="ec_option_payfast_passphrase" type="text" value="<?php echo get_option('ec_option_payfast_passphrase'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Sandbox Mode', 'wp-easycart-pro' ); ?>
		<select name="ec_option_payfast_sandbox" id="ec_option_payfast_sandbox">
			<option value="1" <?php if (get_option('ec_option_payfast_sandbox') == 1) echo ' selected'; ?>><?php esc_attr_e( 'Yes (Sandbox Mode)', 'wp-easycart-pro' ); ?></option>
			<option value="0" <?php if (get_option('ec_option_payfast_sandbox') == 0) echo ' selected'; ?>><?php esc_attr_e( 'No (Live Mode)', 'wp-easycart-pro' ); ?></option>
		</select>
	</div>
	<div class="ec_admin_settings_input" style="padding-right:0px;">
		<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_payfast_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart-pro' ); ?>" />
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'payfast' );?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart-pro' ); ?>" style="margin-top:0px; margin-right:0px;">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
	</div>
</div>