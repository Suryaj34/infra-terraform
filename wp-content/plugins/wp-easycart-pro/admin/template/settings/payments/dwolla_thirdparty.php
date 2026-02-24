<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "dwolla_thirdparty" ){ ?>show<?php }else{?>hide<?php }?>" id="dwolla_thirdparty">
    <span><?php _e( 'Setup Dwolla', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Account ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_dwolla_thirdparty_account_id"  id="ec_option_dwolla_thirdparty_account_id" type="text" value="<?php echo get_option('ec_option_dwolla_thirdparty_account_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_dwolla_thirdparty_key"  id="ec_option_dwolla_thirdparty_key" type="text" value="<?php echo get_option('ec_option_dwolla_thirdparty_key'); ?>" />
    </div>
    <div>
        <?php _e( 'Secret', 'wp-easycart-pro' ); ?>
        <input name="ec_option_dwolla_thirdparty_secret"  id="ec_option_dwolla_thirdparty_secret" type="text" value="<?php echo get_option('ec_option_dwolla_thirdparty_secret'); ?>" />
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_dwolla_thirdparty_test_mode" id="ec_option_dwolla_thirdparty_test_mode">
            <option value="1" <?php if (get_option('ec_option_dwolla_thirdparty_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_dwolla_thirdparty_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
          
    <div class="ec_admin_settings_notice"><strong><?php _e( 'When registering your application to get the key and secret needed to process the checkout, you should enter an application name, add the call back URL listed below, and give the application the permission to request money.', 'wp-easycart-pro' ); ?></strong><br /><br /></div>

    <div class="ec_admin_settings_notice"><strong><?php _e( 'Dwolla Payment Callback URL', 'wp-easycart-pro' ); ?>:</strong> <?php echo $this->cart_page . $this->permalink_divider . "ec_page=checkout_success"; ?></div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_dwolla_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>