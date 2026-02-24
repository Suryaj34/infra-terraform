<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "cashfree" ){ ?>show<?php }else{?>hide<?php }?>" id="cashfree">
    <span><?php _e( 'Setup Cashfree', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'App ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cashfree_app_id"  id="ec_option_cashfree_app_id" type="text" value="<?php echo get_option( 'ec_option_cashfree_app_id' ); ?>" />
    </div>
    <div>
        <?php _e( 'Secret Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cashfree_secret"  id="ec_option_cashfree_secret" type="text" value="<?php echo get_option('ec_option_cashfree_secret'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_cashfree_currency" id="ec_option_cashfree_currency">
            <option value="INR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "INR") echo ' selected'; ?>><?php _e( 'Indian Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="USD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "USD") echo ' selected'; ?>><?php _e( 'US Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="CNY" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "CNY") echo ' selected'; ?>><?php _e( 'Chinese Yuan Renminbi', 'wp-easycart-pro' ); ?></option>
            <option value="GBP" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "GBP") echo ' selected'; ?>><?php _e( 'Pound Sterling', 'wp-easycart-pro' ); ?></option>
            <option value="GBP" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "AED") echo ' selected'; ?>><?php _e( 'UAE Dirham', 'wp-easycart-pro' ); ?></option>
            <option value="AUD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "AUD") echo ' selected'; ?>><?php _e( 'Australian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="AZN" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "AZN") echo ' selected'; ?>><?php _e( 'Azerbaijanian Manat', 'wp-easycart-pro' ); ?></option>
            <option value="BHD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "BHD") echo ' selected'; ?>><?php _e( 'Bahraini Dinar', 'wp-easycart-pro' ); ?></option>
            <option value="CAD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "CAD") echo ' selected'; ?>><?php _e( 'Canadian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="CHF" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "CHF") echo ' selected'; ?>><?php _e( 'Swiss Franc', 'wp-easycart-pro' ); ?></option>
            <option value="DKK" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "DKK") echo ' selected'; ?>><?php _e( 'Danish Krone', 'wp-easycart-pro' ); ?></option>
            <option value="EGP" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "EGP") echo ' selected'; ?>><?php _e( 'Egyptian Pound', 'wp-easycart-pro' ); ?></option>
            <option value="EUR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "EUR") echo ' selected'; ?>><?php _e( 'Euro', 'wp-easycart-pro' ); ?></option>
            <option value="HKD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "HKD") echo ' selected'; ?>><?php _e( 'Hong Kong Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="ILS" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "ILS") echo ' selected'; ?>><?php _e( 'New Israeli Sheqel', 'wp-easycart-pro' ); ?></option>
            <option value="INR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "INR") echo ' selected'; ?>><?php _e( 'Indian Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="JOD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "JOD") echo ' selected'; ?>><?php _e( 'Jordanian Dinar', 'wp-easycart-pro' ); ?></option>
            <option value="JPY" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "JPY") echo ' selected'; ?>><?php _e( 'Japanese Yen', 'wp-easycart-pro' ); ?></option>
            <option value="KRW" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "KRW") echo ' selected'; ?>><?php _e( 'Korean Won', 'wp-easycart-pro' ); ?></option>
            <option value="KWD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "KWD") echo ' selected'; ?>><?php _e( 'Kuwaiti Dinar', 'wp-easycart-pro' ); ?></option>
            <option value="MYR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "MYR") echo ' selected'; ?>><?php _e( 'Malaysian Ringgit', 'wp-easycart-pro' ); ?></option>
            <option value="NOK" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "NOK") echo ' selected'; ?>><?php _e( 'Norwegian Krone', 'wp-easycart-pro' ); ?></option>
            <option value="NZD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "NZD") echo ' selected'; ?>><?php _e( 'New Zealand Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="OMR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "OMR") echo ' selected'; ?>><?php _e( 'Rial Omani', 'wp-easycart-pro' ); ?></option>
            <option value="QAR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "QAR") echo ' selected'; ?>><?php _e( 'Qatari Rial', 'wp-easycart-pro' ); ?></option>
            <option value="RUB" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "RUB") echo ' selected'; ?>><?php _e( 'Russian Ruble', 'wp-easycart-pro' ); ?></option>
            <option value="SAR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "SAR") echo ' selected'; ?>><?php _e( 'Saudi Riyal', 'wp-easycart-pro' ); ?></option>
            <option value="SEK" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "SEK") echo ' selected'; ?>><?php _e( 'Swedish Krona', 'wp-easycart-pro' ); ?></option>
            <option value="SGD" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "SGD") echo ' selected'; ?>><?php _e( 'Singapore Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="THB" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "THB") echo ' selected'; ?>><?php _e( 'Thai Baht', 'wp-easycart-pro' ); ?></option>
            <option value="ZAR" <?php if ( get_option( 'ec_option_cashfree_currency' ) == "ZAR") echo ' selected'; ?>><?php _e( 'South African Rand', 'wp-easycart-pro' ); ?></option>
	   </select>
    </div>
	<div>
        <?php _e( 'Test or Live Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_cashfree_testmode" id="ec_option_cashfree_testmode">
		    <option value="1" <?php if ( get_option( 'ec_option_cashfree_testmode' ) == "1") echo ' selected'; ?>><?php _e( 'Test/Sandbox Mode', 'wp-easycart-pro' ); ?></option>
		    <option value="0" <?php if ( get_option( 'ec_option_cashfree_testmode' ) == "0") echo ' selected'; ?>><?php _e( 'Live Mode', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
	<div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_cashfree_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>