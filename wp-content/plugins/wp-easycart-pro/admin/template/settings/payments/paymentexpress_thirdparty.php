<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "paymentexpress_thirdparty" ){ ?>show<?php }else{?>hide<?php }?>" id="paymentexpress_thirdparty">
    <span><?php _e( 'Setup Payment Express PxPay 2.0', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'User Name', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payment_express_thirdparty_username"  id="ec_option_payment_express_thirdparty_username" type="text" value="<?php echo get_option('ec_option_payment_express_thirdparty_username'); ?>" />
    </div>
    <div>
        <?php _e( 'Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payment_express_thirdparty_key"  id="ec_option_payment_express_thirdparty_key" type="text" value="<?php echo get_option('ec_option_payment_express_thirdparty_key'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_payment_express_thirdparty_currency" id="ec_option_payment_express_thirdparty_currency">
            <option value="USD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "USD") echo ' selected'; ?>>U.S. Dollar</option>
            <option value="CAD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "CAD") echo ' selected'; ?>>Canadian Dollar</option>
            <option value="CHF" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "CHF") echo ' selected'; ?>>Swiss Franc</option>
            <option value="DKK" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "DKK") echo ' selected'; ?>>Danish Krone</option>
            <option value="EUR" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "EUR") echo ' selected'; ?>>Euro</option>
            <option value="FRF" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "FRF") echo ' selected'; ?>>French Franc</option>
            <option value="GBP" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "GBP") echo ' selected'; ?>>United Kingdom Pound</option>
            <option value="HKD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "HKD") echo ' selected'; ?>>Hong Kong Dollar</option>
            <option value="JPY" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "JPY") echo ' selected'; ?>>Japanese Yen</option>
            <option value="NZD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "NZD") echo ' selected'; ?>>New Zealand Dollar</option>
            <option value="SGD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "SGD") echo ' selected'; ?>>Singapore Dollar</option>
            <option value="THB" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "THB") echo ' selected'; ?>>Thai Baht</option>
            <option value="ZAR" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "ZAR") echo ' selected'; ?>>Rand</option>
            <option value="AUD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "AUD") echo ' selected'; ?>>Australian Dollar</option>
            <option value="WST" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "WST") echo ' selected'; ?>>Samoan Tala</option>
            <option value="VUV" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "VUV") echo ' selected'; ?>>Vanuatu Vatu</option>
            <option value="TOP" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "TOP") echo ' selected'; ?>>Tongan Pa'anga</option>
            <option value="SBD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "SBD") echo ' selected'; ?>>Solomon Islands Dollar</option>
            <option value="PGK" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "PGK") echo ' selected'; ?>>Papua New Guinea Kina</option>
            <option value="MYR" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "MYR") echo ' selected'; ?>>Malaysian Ringgit</option>
            <option value="KWD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "KWD") echo ' selected'; ?>>Kuwaiti Dinar</option>
            <option value="FJD" <?php if (get_option('ec_option_payment_express_thirdparty_currency') == "FJD") echo ' selected'; ?>>Fiji Dollar</option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_paymentexpress_thirdparty_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>