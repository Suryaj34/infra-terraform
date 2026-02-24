<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "virtualmerchant" ){ ?>show<?php }else{?>hide<?php }?>" id="virtualmerchant">
    <span><?php _e( 'Setup Virtual Merchant', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Account ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_virtualmerchant_ssl_merchant_id"  id="ec_option_virtualmerchant_ssl_merchant_id" type="text" value="<?php echo get_option('ec_option_virtualmerchant_ssl_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'User ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_virtualmerchant_ssl_user_id"  id="ec_option_virtualmerchant_ssl_user_id" type="text" value="<?php echo get_option('ec_option_virtualmerchant_ssl_user_id'); ?>" />
    </div>
    <div>
        <?php _e( 'PIN', 'wp-easycart-pro' ); ?>
        <input name="ec_option_virtualmerchant_ssl_pin"  id="ec_option_virtualmerchant_ssl_pin" type="text" value="<?php echo get_option('ec_option_virtualmerchant_ssl_pin'); ?>" />
    </div>
    <div>
        <?php _e( 'Demo Account', 'wp-easycart-pro' ); ?>
        <select name="ec_option_virtualmerchant_demo_account" id="ec_option_virtualmerchant_demo_account">
            <option value="1" <?php if (get_option('ec_option_virtualmerchant_demo_account') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_virtualmerchant_demo_account') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_virtualmerchant_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>