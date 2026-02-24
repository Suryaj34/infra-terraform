<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "moneris_ca" ){ ?>show<?php }else{?>hide<?php }?>" id="moneris_ca">
    <span><?php _e( 'Setup Moneris Canada', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Store ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_moneris_ca_store_id"  id="ec_option_moneris_ca_store_id" type="text" value="<?php echo get_option('ec_option_moneris_ca_store_id'); ?>" />
    </div>
    <div>
        <?php _e( 'API Token', 'wp-easycart-pro' ); ?>
        <input name="ec_option_moneris_ca_api_token"  id="ec_option_moneris_ca_api_token" type="text" value="<?php echo get_option('ec_option_moneris_ca_api_token'); ?>" />
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_moneris_ca_test_mode" id="ec_option_moneris_ca_test_mode">
            <option value="1" <?php if (get_option('ec_option_moneris_ca_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes, Put into Test Mode', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_moneris_ca_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No, Process Live Transactions', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_moneris_ca_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>