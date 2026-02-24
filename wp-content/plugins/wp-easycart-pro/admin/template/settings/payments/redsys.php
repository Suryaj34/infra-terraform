<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "redsys" ){ ?>show<?php }else{?>hide<?php }?>" id="redsys">
    <span><?php _e( 'Setup Realex', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant Code', 'wp-easycart-pro' ); ?>
        <input name="ec_option_redsys_merchant_code"  id="ec_option_redsys_merchant_code" type="text" value="<?php echo get_option('ec_option_redsys_merchant_code'); ?>" />
    </div>
    <div>
        <?php _e( 'Terminal', 'wp-easycart-pro' ); ?>
        <input name="ec_option_redsys_terminal"  id="ec_option_redsys_terminal" type="text" value="<?php echo get_option('ec_option_redsys_terminal'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_redsys_currency" id="ec_option_redsys_currency">
            <option value="978" <?php if (get_option('ec_option_redsys_currency') == "978") echo ' selected'; ?>>Euros</option>
            <option value="840" <?php if (get_option('ec_option_redsys_currency') == "840") echo ' selected'; ?>>Dollars</option>
            <option value="826" <?php if (get_option('ec_option_redsys_currency') == "826") echo ' selected'; ?>>Pounds</option>
            <option value="392" <?php if (get_option('ec_option_redsys_currency') == "392") echo ' selected'; ?>>Yen</option>
        </select>
    </div>
    <div>
        <?php _e( 'Secret Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_redsys_key"  id="ec_option_redsys_key" type="text" value="<?php echo get_option('ec_option_redsys_key'); ?>" />
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_redsys_test_mode" id="ec_option_redsys_test_mode">
            <option value="1" <?php if (get_option('ec_option_redsys_test_mode') == "1") echo ' selected'; ?>><?php _e( 'Test Mode', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_redsys_test_mode') == "0") echo ' selected'; ?>><?php _e( 'Production Mode', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_redsys_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
    
</div>