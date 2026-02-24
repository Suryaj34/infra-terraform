<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "paypal_pro" ){ ?>show<?php }else{?>hide<?php }?>" id="paypal_pro">
    <span><?php _e( 'Setup PayPal Payflow Pro', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Partner', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_pro_partner"  id="ec_option_paypal_pro_partner" type="text" value="<?php echo get_option('ec_option_paypal_pro_partner'); ?>" />
    </div>
    <div>
        <?php _e( 'User Name', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_pro_user"  id="ec_option_paypal_pro_user" type="text" value="<?php echo get_option('ec_option_paypal_pro_user'); ?>" />
    </div>
    <div>
        <?php _e( 'Vendor', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_pro_vendor"  id="ec_option_paypal_pro_vendor" type="text" value="<?php echo get_option('ec_option_paypal_pro_vendor'); ?>" />
    </div>
    <div>
        <?php _e( 'Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_paypal_pro_password"  id="ec_option_paypal_pro_password" type="password" value="<?php echo get_option('ec_option_paypal_pro_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_paypal_pro_currency" id="ec_option_paypal_pro_currency">
            <option value="USD" <?php if (get_option('ec_option_paypal_pro_currency') == 'USD') echo ' selected'; ?>>U.S. Dollar</option>
            <option value="AUD" <?php if (get_option('ec_option_paypal_pro_currency') == 'AUD') echo ' selected'; ?>>Australian Dollar</option>
            <option value="BRL" <?php if (get_option('ec_option_paypal_pro_currency') == 'BRL') echo ' selected'; ?>>Brazilian Real</option>
            <option value="CAD" <?php if (get_option('ec_option_paypal_pro_currency') == 'CAD') echo ' selected'; ?>>Canadian Dollar</option>
            <option value="CZK" <?php if (get_option('ec_option_paypal_pro_currency') == 'CZK') echo ' selected'; ?>>Czech Koruna</option>
            <option value="CZK" <?php if (get_option('ec_option_paypal_pro_currency') == 'CZK') echo ' selected'; ?>>Danish Krone</option>
            <option value="EUR" <?php if (get_option('ec_option_paypal_pro_currency') == 'EUR') echo ' selected'; ?>>Euro</option>
            <option value="HKD" <?php if (get_option('ec_option_paypal_pro_currency') == 'HKD') echo ' selected'; ?>>Hong Kong Dollar</option>
            <option value="HUF" <?php if (get_option('ec_option_paypal_pro_currency') == 'HUF') echo ' selected'; ?>>Hungarian Forint</option>
            <option value="ILS" <?php if (get_option('ec_option_paypal_pro_currency') == 'ILS') echo ' selected'; ?>>Israeli New Sheqel</option>
            <option value="JPY" <?php if (get_option('ec_option_paypal_pro_currency') == 'JPY') echo ' selected'; ?>>Japanese Yen</option>
            <option value="MYR" <?php if (get_option('ec_option_paypal_pro_currency') == 'MYR') echo ' selected'; ?>>Malaysian Ringgit</option>
            <option value="MXN" <?php if (get_option('ec_option_paypal_pro_currency') == 'MXN') echo ' selected'; ?>>Mexican Peso</option>
            <option value="NOK" <?php if (get_option('ec_option_paypal_pro_currency') == 'NOK') echo ' selected'; ?>>Norwegian Krone</option>
            <option value="NZD" <?php if (get_option('ec_option_paypal_pro_currency') == 'NZD') echo ' selected'; ?>>New Zealand Dollar</option>
            <option value="PHP" <?php if (get_option('ec_option_paypal_pro_currency') == 'PHP') echo ' selected'; ?>>Philippine Peso</option>
            <option value="PLN" <?php if (get_option('ec_option_paypal_pro_currency') == 'PLN') echo ' selected'; ?>>Polish Zloty</option>
            <option value="GBP" <?php if (get_option('ec_option_paypal_pro_currency') == 'GBP') echo ' selected'; ?>>Pound Sterling</option>
            <option value="SGD" <?php if (get_option('ec_option_paypal_pro_currency') == 'SGD') echo ' selected'; ?>>Singapore Dollar</option>
            <option value="SEK" <?php if (get_option('ec_option_paypal_pro_currency') == 'SEK') echo ' selected'; ?>>Swedish Krona</option>
            <option value="CHF" <?php if (get_option('ec_option_paypal_pro_currency') == 'CHF') echo ' selected'; ?>>Swiss Franc</option>
            <option value="TWD" <?php if (get_option('ec_option_paypal_pro_currency') == 'TWD') echo ' selected'; ?>>Taiwan New Dollar</option>
            <option value="THB" <?php if (get_option('ec_option_paypal_pro_currency') == 'THB') echo ' selected'; ?>>Thai Baht</option>
            <option value="TRY" <?php if (get_option('ec_option_paypal_pro_currency') == 'TRY') echo ' selected'; ?>>Turkish Lira</option>
        </select>
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_paypal_pro_test_mode" id="ec_option_paypal_pro_test_mode">
            <option value="1" <?php if (get_option('ec_option_paypal_pro_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes, Use Sandbox Mode', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_paypal_pro_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No, Process Live Transactions', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_paypal_pro_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>