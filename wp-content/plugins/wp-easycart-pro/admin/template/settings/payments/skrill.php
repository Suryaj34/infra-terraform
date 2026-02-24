<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "skrill" ){ ?>show<?php }else{?>hide<?php }?>" id="skrill">
    <span><?php _e( 'Setup Skrill', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID(Customer ID)', 'wp-easycart-pro' ); ?>
        <input name="ec_option_skrill_merchant_id"  id="ec_option_skrill_merchant_id" type="text" value="<?php echo get_option('ec_option_skrill_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Company Name', 'wp-easycart-pro' ); ?>
        <input name="ec_option_skrill_company_name"  id="ec_option_skrill_company_name" type="text" value="<?php echo get_option('ec_option_skrill_company_name'); ?>" />
    </div>
    <div>
        <?php _e( 'Email', 'wp-easycart-pro' ); ?>
        <input name="ec_option_skrill_email" id="ec_option_skrill_email"  type="text" value="<?php echo get_option('ec_option_skrill_email'); ?>" />
    </div>
    <div>
        <?php _e( 'Language', 'wp-easycart-pro' ); ?>
        <select name="ec_option_skrill_language" id="ec_option_skrill_language">
            <option value="EN" <?php if (get_option('ec_option_skrill_language') == "EN") echo ' selected'; ?>>EN</option>
            <option value="DE" <?php if (get_option('ec_option_skrill_language') == "DE") echo ' selected'; ?>>DE</option>
            <option value="ES" <?php if (get_option('ec_option_skrill_language') == "ES") echo ' selected'; ?>>ES</option>
            <option value="FR" <?php if (get_option('ec_option_skrill_language') == "FR") echo ' selected'; ?>>FR</option>
            <option value="IT" <?php if (get_option('ec_option_skrill_language') == "IT") echo ' selected'; ?>>IT</option>
            <option value="PL" <?php if (get_option('ec_option_skrill_language') == "PL") echo ' selected'; ?>>PL</option>
            <option value="GR" <?php if (get_option('ec_option_skrill_language') == "GR") echo ' selected'; ?>>GR</option>
            <option value="RO" <?php if (get_option('ec_option_skrill_language') == "RO") echo ' selected'; ?>>RO</option>
            <option value="RU" <?php if (get_option('ec_option_skrill_language') == "RU") echo ' selected'; ?>>RU</option>
            <option value="TR" <?php if (get_option('ec_option_skrill_language') == "TR") echo ' selected'; ?>>TR</option>
            <option value="CN" <?php if (get_option('ec_option_skrill_language') == "CN") echo ' selected'; ?>>CN</option>
            <option value="CZ" <?php if (get_option('ec_option_skrill_language') == "CZ") echo ' selected'; ?>>CZ</option>
            <option value="NL" <?php if (get_option('ec_option_skrill_language') == "NL") echo ' selected'; ?>>NL</option>
            <option value="DA" <?php if (get_option('ec_option_skrill_language') == "DA") echo ' selected'; ?>>DA</option>
            <option value="SV" <?php if (get_option('ec_option_skrill_language') == "SV") echo ' selected'; ?>>SV</option>
            <option value="FI" <?php if (get_option('ec_option_skrill_language') == "FI") echo ' selected'; ?>>FI</option>
            <option value="BG" <?php if (get_option('ec_option_skrill_language') == "BG") echo ' selected'; ?>>BG</option>
        </select>
    </div>
    <div>
        <?php _e( 'Currency Code', 'wp-easycart-pro' ); ?>
        <select name="ec_option_skrill_currency_code" id="ec_option_skrill_currency_code">
            <option value="USD" <?php if (get_option('ec_option_skrill_currency_code') == "USD") echo ' selected'; ?>>U.S. Dollar</option>
            <option value="EUR" <?php if (get_option('ec_option_skrill_currency_code') == "EUR") echo ' selected'; ?>>Euro</option>
            <option value="GBP" <?php if (get_option('ec_option_skrill_currency_code') == "GBP") echo ' selected'; ?>>British Pound</option>
            <option value="HKD" <?php if (get_option('ec_option_skrill_currency_code') == "HKD") echo ' selected'; ?>>Hong Kong Dollar</option>
            <option value="SGD" <?php if (get_option('ec_option_skrill_currency_code') == "SGD") echo ' selected'; ?>>Singapore Dollar</option>
            <option value="JPY" <?php if (get_option('ec_option_skrill_currency_code') == "JPY") echo ' selected'; ?>>Japanese Yen</option>
            <option value="CAD" <?php if (get_option('ec_option_skrill_currency_code') == "CAD") echo ' selected'; ?>>Canadian Dollar</option>
            <option value="AUD" <?php if (get_option('ec_option_skrill_currency_code') == "AUD") echo ' selected'; ?>>Australian Dollar</option>
            <option value="CHF" <?php if (get_option('ec_option_skrill_currency_code') == "CHF") echo ' selected'; ?>>Swiss Franc</option>
            <option value="DKK" <?php if (get_option('ec_option_skrill_currency_code') == "DKK") echo ' selected'; ?>>Danish Krone</option>
            <option value="SEK" <?php if (get_option('ec_option_skrill_currency_code') == "SEK") echo ' selected'; ?>>Swedish Krona</option>
            <option value="NOK" <?php if (get_option('ec_option_skrill_currency_code') == "NOK") echo ' selected'; ?>>Norwegian Krone</option>
            <option value="ILS" <?php if (get_option('ec_option_skrill_currency_code') == "ILS") echo ' selected'; ?>>Israeli Shekel</option>
            <option value="MYR" <?php if (get_option('ec_option_skrill_currency_code') == "MYR") echo ' selected'; ?>>Malaysian Ringgit</option>
            <option value="NZD" <?php if (get_option('ec_option_skrill_currency_code') == "NZD") echo ' selected'; ?>>New Zealand Dollar</option>
            <option value="TRY" <?php if (get_option('ec_option_skrill_currency_code') == "TRY") echo ' selected'; ?>>New Turkish Lira</option>
            <option value="AED" <?php if (get_option('ec_option_skrill_currency_code') == "AED") echo ' selected'; ?>>Utd. Arab Emir. Dirham</option>
            <option value="MAD" <?php if (get_option('ec_option_skrill_currency_code') == "MAD") echo ' selected'; ?>>Moroccan Dirham</option>
            <option value="QAR" <?php if (get_option('ec_option_skrill_currency_code') == "QAR") echo ' selected'; ?>>Qatari Rial</option>
            <option value="SAR" <?php if (get_option('ec_option_skrill_currency_code') == "SAR") echo ' selected'; ?>>Saudi Riyal</option>
            <option value="TWD" <?php if (get_option('ec_option_skrill_currency_code') == "TWD") echo ' selected'; ?>>Taiwan Dollar</option>
            <option value="THB" <?php if (get_option('ec_option_skrill_currency_code') == "THB") echo ' selected'; ?>>Thailand Baht</option>
            <option value="CZK" <?php if (get_option('ec_option_skrill_currency_code') == "CZK") echo ' selected'; ?>>Czech Koruna</option>
            <option value="HUF" <?php if (get_option('ec_option_skrill_currency_code') == "HUF") echo ' selected'; ?>>Hungarian Forint</option>
            <option value="SKK" <?php if (get_option('ec_option_skrill_currency_code') == "SKK") echo ' selected'; ?>>Slovakian Koruna</option>
            <option value="EEK" <?php if (get_option('ec_option_skrill_currency_code') == "EEK") echo ' selected'; ?>>Estonian Kroon</option>
            <option value="BGN" <?php if (get_option('ec_option_skrill_currency_code') == "BGN") echo ' selected'; ?>>Bulgarian Leva</option>
            <option value="PLN" <?php if (get_option('ec_option_skrill_currency_code') == "PLN") echo ' selected'; ?>>Polish Zloty</option>
            <option value="ISK" <?php if (get_option('ec_option_skrill_currency_code') == "ISK") echo ' selected'; ?>>Iceland Krona</option>
            <option value="INR" <?php if (get_option('ec_option_skrill_currency_code') == "INR") echo ' selected'; ?>>Indian Rupee</option>
            <option value="LVL" <?php if (get_option('ec_option_skrill_currency_code') == "LVL") echo ' selected'; ?>>Latvian Lat</option>
            <option value="KRW" <?php if (get_option('ec_option_skrill_currency_code') == "KRW") echo ' selected'; ?>>South-Korean Won</option>
            <option value="ZAR" <?php if (get_option('ec_option_skrill_currency_code') == "ZAR") echo ' selected'; ?>>South-African Rand</option>
            <option value="RON" <?php if (get_option('ec_option_skrill_currency_code') == "RON") echo ' selected'; ?>>Romanian Leu New</option>
            <option value="HRK" <?php if (get_option('ec_option_skrill_currency_code') == "HRK") echo ' selected'; ?>>Croatian Kuna</option>
            <option value="LTL" <?php if (get_option('ec_option_skrill_currency_code') == "LTL") echo ' selected'; ?>>Lithuanian Litas</option>
            <option value="JOD" <?php if (get_option('ec_option_skrill_currency_code') == "JOD") echo ' selected'; ?>>Jordanian Dinar</option>
            <option value="OMR" <?php if (get_option('ec_option_skrill_currency_code') == "OMR") echo ' selected'; ?>>Omani Rial</option>
            <option value="RSD" <?php if (get_option('ec_option_skrill_currency_code') == "RSD") echo ' selected'; ?>>Serbian dinar</option>
            <option value="TND" <?php if (get_option('ec_option_skrill_currency_code') == "TND") echo ' selected'; ?>>Tunisian Dinar</option>
        </select>
    </div>
	
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_skrill_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
    
</div>