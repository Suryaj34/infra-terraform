<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "paypal_payments_pro" ){ ?>show<?php }else{?>hide<?php }?>" id="paypal_payments_pro">
    <span><?php _e( 'Setup Paypal Payments Pro', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'User', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_payments_pro_user"  id="ec_option_paypal_payments_pro_user" type="text" value="<?php echo get_option('ec_option_paypal_payments_pro_user'); ?>" />
    </div>
    <div>
        <?php _e( 'Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_payments_pro_password"  id="ec_option_paypal_payments_pro_password" type="password" value="<?php echo get_option('ec_option_paypal_payments_pro_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Signature', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_payments_pro_signature"  id="ec_option_paypal_payments_pro_signature" type="text" value="<?php echo get_option('ec_option_paypal_payments_pro_signature'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_paypal_payments_pro_currency" id="ec_option_paypal_payments_pro_currency">
            <option value="USD" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'USD') echo ' selected'; ?>>U.S. Dollar</option>
            <option value="AUD" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'AUD') echo ' selected'; ?>>Australian Dollar</option>
            <option value="CAD" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'CAD') echo ' selected'; ?>>Canadian Dollar</option>
            <option value="CZK" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'CZK') echo ' selected'; ?>>Czech Koruna</option>
            <option value="DKK" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'DKK') echo ' selected'; ?>>Danish Krone</option>
            <option value="EUR" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'EUR') echo ' selected'; ?>>Euro</option>
            <option value="HKD" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'HKD') echo ' selected'; ?>>Hong Kong Dollar</option>
            <option value="HUF" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'HUF') echo ' selected'; ?>>Hungarian Forint</option>
            <option value="JPY" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'JPY') echo ' selected'; ?>>Japanese Yen</option>
            <option value="NOK" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'NOK') echo ' selected'; ?>>Norwegian Krone</option>
            <option value="NZD" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'NZD') echo ' selected'; ?>>New Zealand Dollar</option>
            <option value="PLN" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'PLN') echo ' selected'; ?>>Polish Zloty</option>
            <option value="GBP" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'GBP') echo ' selected'; ?>>Pound Sterling</option>
            <option value="SGD" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'SGD') echo ' selected'; ?>>Singapore Dollar</option>
            <option value="SEK" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'SEK') echo ' selected'; ?>>Swedish Krona</option>
            <option value="CHF" <?php if (get_option('ec_option_paypal_payments_pro_currency') == 'CHF') echo ' selected'; ?>>Swiss Franc</option>
        </select>
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_paypal_payments_pro_test_mode" id="ec_option_paypal_payments_pro_test_mode">
            <option value="1" <?php if (get_option('ec_option_paypal_payments_pro_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes, Use Sandbox Mode', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_paypal_payments_pro_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No, Process Live Transactions', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_paypal_payments_pro_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>