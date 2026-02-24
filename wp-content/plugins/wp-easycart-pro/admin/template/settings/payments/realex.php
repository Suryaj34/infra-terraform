<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "realex" ){ ?>show<?php }else{?>hide<?php }?>" id="realex">
    <span><?php _e( 'Setup RealEx', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_realex_merchant_id"  id="ec_option_realex_merchant_id" type="text" value="<?php echo get_option('ec_option_realex_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Secret', 'wp-easycart-pro' ); ?>
        <input name="ec_option_realex_secret"  id="ec_option_realex_secret" type="password" value="<?php echo get_option('ec_option_realex_secret'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_realex_currency" id="ec_option_realex_currency">
            <option value="GBP" <?php if (get_option('ec_option_realex_currency') == "GBP") echo ' selected'; ?>>GBP</option>
            <option value="EUR" <?php if (get_option('ec_option_realex_currency') == "EUR") echo ' selected'; ?>>EUR</option>
            <option value="USD" <?php if (get_option('ec_option_realex_currency') == "USD") echo ' selected'; ?>>USD</option>
            <option value="DKK" <?php if (get_option('ec_option_realex_currency') == "DKK") echo ' selected'; ?>>DKK</option>
            <option value="NOK" <?php if (get_option('ec_option_realex_currency') == "NOK") echo ' selected'; ?>>NOK</option>
            <option value="CHF" <?php if (get_option('ec_option_realex_currency') == "CHF") echo ' selected'; ?>>CHF</option>
            <option value="AUD" <?php if (get_option('ec_option_realex_currency') == "AUD") echo ' selected'; ?>>AUD</option>
            <option value="CAD" <?php if (get_option('ec_option_realex_currency') == "CAD") echo ' selected'; ?>>CAD</option>
            <option value="CZK" <?php if (get_option('ec_option_realex_currency') == "CZK") echo ' selected'; ?>>CZK</option>
            <option value="JPY" <?php if (get_option('ec_option_realex_currency') == "JPY") echo ' selected'; ?>>JPY</option>
            <option value="NZD" <?php if (get_option('ec_option_realex_currency') == "NZD") echo ' selected'; ?>>NZD</option>
            <option value="HKD" <?php if (get_option('ec_option_realex_currency') == "HKD") echo ' selected'; ?>>HKD</option>
            <option value="ZAR" <?php if (get_option('ec_option_realex_currency') == "ZAR") echo ' selected'; ?>>ZAR</option>
            <option value="SEK" <?php if (get_option('ec_option_realex_currency') == "SEK") echo ' selected'; ?>>SEK</option>
        </select>
    </div>
    <div>
        <?php _e( '3D Secure', 'wp-easycart-pro' ); ?>
        <select name="ec_option_realex_3dsecure" id="ec_option_realex_3dsecure">
            <option value="1" <?php if (get_option('ec_option_realex_3dsecure') == 1) echo ' selected'; ?>><?php _e( 'Yes, All 3D Secure', 'wp-easycart-pro' ); ?></option>
            <option value="2" <?php if (get_option('ec_option_realex_3dsecure') == 2) echo ' selected'; ?>><?php _e( 'Yes, But Only When Liability Shifts to Merchant', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_realex_3dsecure') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_realex_test_mode" id="ec_option_realex_test_mode">
            <option value="0" <?php if (get_option('ec_option_realex_test_mode') == '0') echo ' selected'; ?>><?php _e( 'Live Mode', 'wp-easycart-pro' ); ?></option>
            <option value="1" <?php if (get_option('ec_option_realex_test_mode') == '1') echo ' selected'; ?>><?php _e( 'Test Mode', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_realex_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>