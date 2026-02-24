<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "paymentexpress" ){ ?>show<?php }else{?>hide<?php }?>" id="paymentexpress">
    <span><?php _e( 'Setup Payment Express PxPost', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'User Name', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payment_express_username"  id="ec_option_payment_express_username" type="text" value="<?php echo get_option('ec_option_payment_express_username'); ?>" />
    </div>
    <div>
        <?php _e( 'Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payment_express_password"  id="ec_option_payment_express_password" type="password" value="<?php echo get_option('ec_option_payment_express_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_payment_express_currency" id="ec_option_payment_express_currency">
            <option value="USD" <?php if (get_option('ec_option_payment_express_currency') == "USD") echo ' selected'; ?>>U.S. Dollar</option>
            <option value="CAD" <?php if (get_option('ec_option_payment_express_currency') == "CAD") echo ' selected'; ?>>Canadian Dollar</option>
            <option value="CHF" <?php if (get_option('ec_option_payment_express_currency') == "CHF") echo ' selected'; ?>>Swiss Franc</option>
            <option value="DKK" <?php if (get_option('ec_option_payment_express_currency') == "DKK") echo ' selected'; ?>>Danish Krone</option>
            <option value="EUR" <?php if (get_option('ec_option_payment_express_currency') == "EUR") echo ' selected'; ?>>Euro</option>
            <option value="FRF" <?php if (get_option('ec_option_payment_express_currency') == "FRF") echo ' selected'; ?>>French Franc</option>
            <option value="GBP" <?php if (get_option('ec_option_payment_express_currency') == "GBP") echo ' selected'; ?>>United Kingdom Pound</option>
            <option value="HKD" <?php if (get_option('ec_option_payment_express_currency') == "HKD") echo ' selected'; ?>>Hong Kong Dollar</option>
            <option value="JPY" <?php if (get_option('ec_option_payment_express_currency') == "JPY") echo ' selected'; ?>>Japanese Yen</option>
            <option value="NZD" <?php if (get_option('ec_option_payment_express_currency') == "NZD") echo ' selected'; ?>>New Zealand Dollar</option>
            <option value="SGD" <?php if (get_option('ec_option_payment_express_currency') == "SGD") echo ' selected'; ?>>Singapore Dollar</option>
            <option value="THB" <?php if (get_option('ec_option_payment_express_currency') == "THB") echo ' selected'; ?>>Thai Baht</option>
            <option value="ZAR" <?php if (get_option('ec_option_payment_express_currency') == "ZAR") echo ' selected'; ?>>Rand</option>
            <option value="AUD" <?php if (get_option('ec_option_payment_express_currency') == "AUD") echo ' selected'; ?>>Australian Dollar</option>
            <option value="WST" <?php if (get_option('ec_option_payment_express_currency') == "WST") echo ' selected'; ?>>Samoan Tala</option>
            <option value="VUV" <?php if (get_option('ec_option_payment_express_currency') == "VUV") echo ' selected'; ?>>Vanuatu Vatu</option>
            <option value="TOP" <?php if (get_option('ec_option_payment_express_currency') == "TOP") echo ' selected'; ?>>Tongan Pa'anga</option>
            <option value="SBD" <?php if (get_option('ec_option_payment_express_currency') == "SBD") echo ' selected'; ?>>Solomon Islands Dollar</option>
            <option value="PGK" <?php if (get_option('ec_option_payment_express_currency') == "PGK") echo ' selected'; ?>>Papua New Guinea Kina</option>
            <option value="MYR" <?php if (get_option('ec_option_payment_express_currency') == "MYR") echo ' selected'; ?>>Malaysian Ringgit</option>
            <option value="KWD" <?php if (get_option('ec_option_payment_express_currency') == "KWD") echo ' selected'; ?>>Kuwaiti Dinar</option>
            <option value="FJD" <?php if (get_option('ec_option_payment_express_currency') == "FJD") echo ' selected'; ?>>Fiji Dollar</option>
        </select>
    </div>
    <div>
        <?php _e( 'Developer Account', 'wp-easycart-pro' ); ?>
        <select name="ec_option_payment_express_developer_account" id="ec_option_payment_express_developer_account">
            <option value="1" <?php if (get_option('ec_option_payment_express_developer_account') == "1") echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_payment_express_developer_account') == "0") echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_paymentexpress_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>