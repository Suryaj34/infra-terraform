<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "paypoint" ){ ?>show<?php }else{?>hide<?php }?>" id="paypoint">
    <span><?php _e( 'Setup Paypoint', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypoint_merchant_id"  id="ec_option_paypoint_merchant_id" type="text" value="<?php echo get_option('ec_option_paypoint_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'VPN Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypoint_vpn_password"  id="ec_option_paypoint_vpn_password" type="password" value="<?php echo get_option('ec_option_paypoint_vpn_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_paypoint_test_mode" id="ec_option_paypoint_test_mode">
            <option value="1" <?php if (get_option('ec_option_paypoint_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes, Use Test Mode', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_paypoint_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No, Process Live Transactions', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
	<div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_paypoint_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>