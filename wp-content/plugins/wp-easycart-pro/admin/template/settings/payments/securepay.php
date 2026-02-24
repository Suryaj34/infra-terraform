<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "securepay" ){ ?>show<?php }else{?>hide<?php }?>" id="securepay">
    <span><?php _e( 'Setup SecurePay', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_securepay_merchant_id"  id="ec_option_securepay_merchant_id" type="text" value="<?php echo get_option('ec_option_securepay_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_securepay_password"  id="ec_option_securepay_password" type="password" value="<?php echo get_option('ec_option_securepay_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_securepay_currency" id="ec_option_securepay_currency">
            <option value="AUD" <?php if (get_option('ec_option_securepay_currency') == "AUD") echo ' selected'; ?>>AUD</option>
            <option value="CAD" <?php if (get_option('ec_option_securepay_currency') == "CAD") echo ' selected'; ?>>CAD</option>
            <option value="CHF" <?php if (get_option('ec_option_securepay_currency') == "CHF") echo ' selected'; ?>>CHF</option>
            <option value="EUR" <?php if (get_option('ec_option_securepay_currency') == "EUR") echo ' selected'; ?>>EUR</option>
            <option value="GBP" <?php if (get_option('ec_option_securepay_currency') == "GBP") echo ' selected'; ?>>GBP</option>
            <option value="HKD" <?php if (get_option('ec_option_securepay_currency') == "HKD") echo ' selected'; ?>>HKD</option>
            <option value="JPY" <?php if (get_option('ec_option_securepay_currency') == "CHF") echo ' selected'; ?>>JPY</option>
            <option value="NZD" <?php if (get_option('ec_option_securepay_currency') == "NZD") echo ' selected'; ?>>NZD</option>
            <option value="SGD" <?php if (get_option('ec_option_securepay_currency') == "SGD") echo ' selected'; ?>>SGD</option>
            <option value="USD" <?php if (get_option('ec_option_securepay_currency') == "USD") echo ' selected'; ?>>USD</option>
        </select>
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_securepay_test_mode" id="ec_option_securepay_test_mode">
            <option value="1" <?php if (get_option('ec_option_securepay_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_securepay_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_securepay_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>