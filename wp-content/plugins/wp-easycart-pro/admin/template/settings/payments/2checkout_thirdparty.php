<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "2checkout_thirdparty" ){ ?>show<?php }else{?>hide<?php }?>" id="2checkout_thirdparty">
    <span>
        <?php _e( 'Setup 2Checkout', 'wp-easycart-pro' ); ?>
    </span>
    <div>
        <?php _e( 'Account Number', 'wp-easycart-pro' ); ?>
        <input name="ec_option_2checkout_thirdparty_sid"  id="ec_option_2checkout_thirdparty_sid" type="text" value="<?php echo get_option('ec_option_2checkout_thirdparty_sid'); ?>" />
    </div>
    <div>
        <?php _e( 'Secret Word', 'wp-easycart-pro' ); ?>
        <input name="ec_option_2checkout_thirdparty_secret_word"  id="ec_option_2checkout_thirdparty_secret_word" type="text" value="<?php echo get_option('ec_option_2checkout_thirdparty_secret_word'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency Code', 'wp-easycart-pro' ); ?>
        <select name="ec_option_2checkout_thirdparty_currency_code" id="ec_option_2checkout_thirdparty_currency_code">
            <option value="USD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'USD') echo ' selected'; ?>><?php _e( 'U.S. Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="AFN" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'AFN') echo ' selected'; ?>><?php _e( 'Afghani', 'wp-easycart-pro' ); ?></option>
            <option value="ALL" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'ALL') echo ' selected'; ?>><?php _e( 'Lek', 'wp-easycart-pro' ); ?></option>
            <option value="DZD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'DZD') echo ' selected'; ?>><?php _e( 'Algerian Dinar', 'wp-easycart-pro' ); ?></option>
            <option value="ARS" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'ARS') echo ' selected'; ?>><?php _e( 'Argentine Peso', 'wp-easycart-pro' ); ?></option>
            <option value="AUD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'AUD') echo ' selected'; ?>><?php _e( 'Australian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="AZN" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'AZN') echo ' selected'; ?>><?php _e( 'Azerbaijan Manat', 'wp-easycart-pro' ); ?></option>
            <option value="BSD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BSD') echo ' selected'; ?>><?php _e( 'Bahamian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="BDT" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BDT') echo ' selected'; ?>><?php _e( 'Taka', 'wp-easycart-pro' ); ?></option>
            <option value="BBD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BBD') echo ' selected'; ?>><?php _e( 'Barbados Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="BZD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BZD') echo ' selected'; ?>><?php _e( 'Belize Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="BMD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BMD') echo ' selected'; ?>><?php _e( 'Bermudian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="BOB" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BOB') echo ' selected'; ?>><?php _e( 'Boliviano', 'wp-easycart-pro' ); ?></option>
            <option value="BWP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BWP') echo ' selected'; ?>><?php _e( 'Pula', 'wp-easycart-pro' ); ?></option>
            <option value="BRL" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BRL') echo ' selected'; ?>><?php _e( 'Brazilian Real', 'wp-easycart-pro' ); ?></option>
            <option value="GBP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'GBP') echo ' selected'; ?>><?php _e( 'Pound Sterling', 'wp-easycart-pro' ); ?></option>
            <option value="BND" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BND') echo ' selected'; ?>><?php _e( 'Brunei Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="BGN" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'BGN') echo ' selected'; ?>><?php _e( 'Bulgarian Lev', 'wp-easycart-pro' ); ?></option>
            <option value="CAD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'CAD') echo ' selected'; ?>><?php _e( 'Canadian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="CLP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'CLP') echo ' selected'; ?>><?php _e( 'Chilean Peso', 'wp-easycart-pro' ); ?></option>
            <option value="CNY" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'CNY') echo ' selected'; ?>><?php _e( 'Renminbi (Yuan)', 'wp-easycart-pro' ); ?></option>
            <option value="COP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'COP') echo ' selected'; ?>><?php _e( 'Colombian Peso', 'wp-easycart-pro' ); ?></option>
            <option value="CRC" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'CRC') echo ' selected'; ?>><?php _e( 'Costa Rican Colón', 'wp-easycart-pro' ); ?></option>
            <option value="HRK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'HRK') echo ' selected'; ?>><?php _e( 'Croatian Kuna', 'wp-easycart-pro' ); ?></option>
            <option value="CZK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'CZK') echo ' selected'; ?>><?php _e( 'Czech Koruna', 'wp-easycart-pro' ); ?></option>
            <option value="DKK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'DKK') echo ' selected'; ?>><?php _e( 'Danish Krone', 'wp-easycart-pro' ); ?></option>
            <option value="DOP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'DOP') echo ' selected'; ?>><?php _e( 'Dominican Peso', 'wp-easycart-pro' ); ?></option>
            <option value="XCD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'XCD') echo ' selected'; ?>><?php _e( 'East Caribbean Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="EGP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'EGP') echo ' selected'; ?>><?php _e( 'Egyptian Pound', 'wp-easycart-pro' ); ?></option>
            <option value="EUR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'EUR') echo ' selected'; ?>><?php _e( 'Euro', 'wp-easycart-pro' ); ?></option>
            <option value="FJD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'FJD') echo ' selected'; ?>><?php _e( 'Fiji Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="GTQ" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'GTQ') echo ' selected'; ?>><?php _e( 'Quetzal', 'wp-easycart-pro' ); ?></option>
            <option value="HKD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'HKD') echo ' selected'; ?>><?php _e( 'Hong Kong Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="HNL" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'HNL') echo ' selected'; ?>><?php _e( 'Lempira', 'wp-easycart-pro' ); ?></option>
            <option value="HUF" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'HUF') echo ' selected'; ?>><?php _e( 'Hungarian Forint', 'wp-easycart-pro' ); ?></option>
            <option value="INR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'INR') echo ' selected'; ?>><?php _e( 'Indian Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="IDR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'IDR') echo ' selected'; ?>><?php _e( 'Rupiah', 'wp-easycart-pro' ); ?></option>
            <option value="ILS" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'ILS') echo ' selected'; ?>><?php _e( 'Israeli New Sheqel', 'wp-easycart-pro' ); ?></option>
            <option value="JMD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'JMD') echo ' selected'; ?>><?php _e( 'Jamaican Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="JPY" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'JPY') echo ' selected'; ?>><?php _e( 'Japanese Yen', 'wp-easycart-pro' ); ?></option>
            <option value="KZT" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'KZT') echo ' selected'; ?>><?php _e( 'Tenge', 'wp-easycart-pro' ); ?></option>
            <option value="KES" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'KES') echo ' selected'; ?>><?php _e( 'Kenyan Shilling', 'wp-easycart-pro' ); ?></option>
            <option value="LAK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'LAK') echo ' selected'; ?>><?php _e( 'Kip', 'wp-easycart-pro' ); ?></option>
            <option value="MMK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MMK') echo ' selected'; ?>><?php _e( 'Kyat', 'wp-easycart-pro' ); ?></option>
            <option value="LBP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'LBP') echo ' selected'; ?>><?php _e( 'Lebanese Pound', 'wp-easycart-pro' ); ?></option>
            <option value="LRD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'LRD') echo ' selected'; ?>><?php _e( 'Liberian Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="MOP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MOP') echo ' selected'; ?>><?php _e( 'Macanese Pataca', 'wp-easycart-pro' ); ?></option>
            <option value="MYR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MYR') echo ' selected'; ?>><?php _e( 'Malaysian Ringgit', 'wp-easycart-pro' ); ?></option>
            <option value="MVR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MVR') echo ' selected'; ?>><?php _e( 'Rufiyaa', 'wp-easycart-pro' ); ?></option>
            <option value="MRO" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MRO') echo ' selected'; ?>><?php _e( 'Ouguiya', 'wp-easycart-pro' ); ?></option>
            <option value="MUR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MUR') echo ' selected'; ?>><?php _e( 'Mauritius Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="MXN" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MXN') echo ' selected'; ?>><?php _e( 'Mexican Peso', 'wp-easycart-pro' ); ?></option>
            <option value="MAD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'MAD') echo ' selected'; ?>><?php _e( 'Moroccan Dirham', 'wp-easycart-pro' ); ?></option>
            <option value="NPR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'NPR') echo ' selected'; ?>><?php _e( 'Nepalese Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="TWD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'TWD') echo ' selected'; ?>><?php _e( 'New Taiwan Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="NZD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'NZD') echo ' selected'; ?>><?php _e( 'New Zealand Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="NIO" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'NIO') echo ' selected'; ?>><?php _e( 'Cordoba Oro', 'wp-easycart-pro' ); ?></option>
            <option value="NOK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'NOK') echo ' selected'; ?>><?php _e( 'Norwegian Krone', 'wp-easycart-pro' ); ?></option>
            <option value="PKR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'PKR') echo ' selected'; ?>><?php _e( 'Pakistan Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="PGK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'PGK') echo ' selected'; ?>><?php _e( 'Kina', 'wp-easycart-pro' ); ?></option>
            <option value="PEN" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'PEN') echo ' selected'; ?>><?php _e( 'Nuevo Sol', 'wp-easycart-pro' ); ?></option>
            <option value="PHP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'PHP') echo ' selected'; ?>><?php _e( 'Philippine Peso', 'wp-easycart-pro' ); ?></option>
            <option value="PLN" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'PLN') echo ' selected'; ?>><?php _e( 'Polish Zloty', 'wp-easycart-pro' ); ?></option>
            <option value="QAR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'QAR') echo ' selected'; ?>><?php _e( 'Qatari Rial', 'wp-easycart-pro' ); ?></option>
            <option value="RON" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'RON') echo ' selected'; ?>><?php _e( 'RON', 'wp-easycart-pro' ); ?></option>
            <option value="RUB" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'RUB') echo ' selected'; ?>><?php _e( 'Russian Ruble', 'wp-easycart-pro' ); ?></option>
            <option value="WST" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'WST') echo ' selected'; ?>><?php _e( 'Tala', 'wp-easycart-pro' ); ?></option>
            <option value="SAR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'SAR') echo ' selected'; ?>><?php _e( 'Saudi Riyal', 'wp-easycart-pro' ); ?></option>
            <option value="SCR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'SCR') echo ' selected'; ?>><?php _e( 'Seychelles Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="SGD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'SGD') echo ' selected'; ?>><?php _e( 'Singapore Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="SBD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'SBD') echo ' selected'; ?>><?php _e( 'Solomon Islands Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="ZAR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'ZAR') echo ' selected'; ?>><?php _e( 'Rand', 'wp-easycart-pro' ); ?></option>
            <option value="KRW" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'KRW') echo ' selected'; ?>><?php _e( 'Won', 'wp-easycart-pro' ); ?></option>
            <option value="LKR" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'LKR') echo ' selected'; ?>><?php _e( 'Sri Lanka Rupee', 'wp-easycart-pro' ); ?></option>
            <option value="SEK" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'SEK') echo ' selected'; ?>><?php _e( 'Swedish Krona', 'wp-easycart-pro' ); ?></option>
            <option value="CHF" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'CHF') echo ' selected'; ?>><?php _e( 'Swiss Franc', 'wp-easycart-pro' ); ?></option>
            <option value="SYP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'SYP') echo ' selected'; ?>><?php _e( 'Syrian Pound', 'wp-easycart-pro' ); ?></option>
            <option value="THB" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'THB') echo ' selected'; ?>><?php _e( 'Thai Baht', 'wp-easycart-pro' ); ?></option>
            <option value="TOP" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'TOP') echo ' selected'; ?>><?php _e( 'Paʻanga', 'wp-easycart-pro' ); ?></option>
            <option value="TTD" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'TTD') echo ' selected'; ?>><?php _e( 'Trinidad and Tobago Dollar', 'wp-easycart-pro' ); ?></option>
            <option value="TRY" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'TRY') echo ' selected'; ?>><?php _e( 'Turkish Lira', 'wp-easycart-pro' ); ?></option>
        	<option value="UAH" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'UAH') echo ' selected'; ?>><?php _e( 'Hryvnia', 'wp-easycart-pro' ); ?></option>
            <option value="AED" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'AED') echo ' selected'; ?>><?php _e( 'UAE Dirham', 'wp-easycart-pro' ); ?></option>
            <option value="VUV" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'VUV') echo ' selected'; ?>><?php _e( 'Vatu', 'wp-easycart-pro' ); ?></option>
            <option value="VND" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'VND') echo ' selected'; ?>><?php _e( 'Vietnamese Dong', 'wp-easycart-pro' ); ?></option>
            <option value="XOF" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'XOF') echo ' selected'; ?>><?php _e( 'CFA Franc BCEAO', 'wp-easycart-pro' ); ?></option>
            <option value="YER" <?php if( get_option( 'ec_option_2checkout_thirdparty_currency_code') == 'YER') echo ' selected'; ?>><?php _e( 'Yemeni Rial', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div>
        <?php _e( 'Language', 'wp-easycart-pro' ); ?>
        <select name="ec_option_2checkout_thirdparty_lang" id="ec_option_2checkout_thirdparty_lang">
            <option value="zh" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'zh') echo ' selected'; ?>><?php _e( 'Chinese', 'wp-easycart-pro' ); ?></option>
            <option value="da" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'da') echo ' selected'; ?>><?php _e( 'Danish', 'wp-easycart-pro' ); ?></option>
            <option value="nl" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'nl') echo ' selected'; ?>><?php _e( 'Dutch', 'wp-easycart-pro' ); ?></option>
            <option value="en" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'en') echo ' selected'; ?>><?php _e( 'Englsh', 'wp-easycart-pro' ); ?></option>
            <option value="fr" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'fr') echo ' selected'; ?>><?php _e( 'French', 'wp-easycart-pro' ); ?></option>
            <option value="gr" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'gr') echo ' selected'; ?>><?php _e( 'German', 'wp-easycart-pro' ); ?></option>
            <option value="el" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'el') echo ' selected'; ?>><?php _e( 'Greek', 'wp-easycart-pro' ); ?></option>
            <option value="it" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'it') echo ' selected'; ?>><?php _e( 'Italian', 'wp-easycart-pro' ); ?></option>
            <option value="jp" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'jp') echo ' selected'; ?>><?php _e( 'Japanese', 'wp-easycart-pro' ); ?></option>
            <option value="no" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'no') echo ' selected'; ?>><?php _e( 'Norwegian', 'wp-easycart-pro' ); ?></option>
            <option value="pt" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'pt') echo ' selected'; ?>><?php _e( 'Portugese', 'wp-easycart-pro' ); ?></option>
            <option value="sl" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'sl') echo ' selected'; ?>><?php _e( 'Slovenian', 'wp-easycart-pro' ); ?></option>
            <option value="es" <?php if( get_option( 'ec_option_2checkout_thirdparty_lang') == 'es') echo ' selected'; ?>><?php _e( 'Spanish', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div>
        <?php _e( 'Checkout Purchase Step', 'wp-easycart-pro' ); ?>
        <select name="ec_option_2checkout_thirdparty_purchase_step" id="ec_option_2checkout_thirdparty_purchase_step">
        	<option value="review-cart" <?php if( get_option('ec_option_2checkout_thirdparty_purchase_step' ) == 'review-cart' ) echo ' selected'; ?>><?php _e( 'Review Cart', 'wp-easycart-pro' ); ?></option>
        	<option value="shipping-information" <?php if( get_option('ec_option_2checkout_thirdparty_purchase_step' ) == 'shipping-information' ) echo ' selected'; ?>><?php _e( 'Shipping Information', 'wp-easycart-pro' ); ?></option>
        	<option value="billing-information" <?php if( get_option('ec_option_2checkout_thirdparty_purchase_step' ) == 'billing-information' ) echo ' selected'; ?>><?php _e( 'Billing Information', 'wp-easycart-pro' ); ?></option>
        	<option value="payment-method" <?php if( get_option('ec_option_2checkout_thirdparty_purchase_step' ) == 'payment-method' ) echo ' selected'; ?>><?php _e( 'Payment Method', 'wp-easycart-pro' ); ?></option>
       	</select>
    </div>
    <div>
        <?php _e( 'Sandbox Account', 'wp-easycart-pro' ); ?>
        <select name="ec_option_2checkout_thirdparty_sandbox_mode" id="ec_option_2checkout_thirdparty_sandbox_mode">
        	<option value="1" <?php if( get_option('ec_option_2checkout_thirdparty_sandbox_mode' ) == 1 ) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
        	<option value="0" <?php if( get_option('ec_option_2checkout_thirdparty_sandbox_mode' ) == 0 ) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
       	</select>
    </div>
    <div>
        <?php _e( 'Demo Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_2checkout_thirdparty_demo_mode" id="ec_option_2checkout_thirdparty_demo_mode">
        	<option value="1" <?php if( get_option('ec_option_2checkout_thirdparty_demo_mode' ) == 1 ) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
        	<option value="0" <?php if( get_option('ec_option_2checkout_thirdparty_demo_mode' ) == 0 ) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
       	</select>
    </div>
    <div class="ec_admin_settings_notice">
        <strong><?php _e( 'To complete setup you must complete the following steps:', 'wp-easycart-pro' ); ?></strong>
        <ol>
        	<li><?php _e( 'Go to your Account Settings -> Site Management and setup the Direct Return option shown in the image below.', 'wp-easycart-pro' ); ?></li>
            <li><?php _e( 'Go to your Account Settings -> Site Management and enter the Approved URL with', 'wp-easycart-pro' ); ?>: <?php echo site_url( ); ?>.</li>
            <li><?php _e( 'Click the Notifications icon in the top right corner of your account, just left of the help and account icons. Then enter your site URL in all of the URL boxes and check the enable box for all of the notification options. The URL to enter is', 'wp-easycart-pro' ); ?>: <?php echo site_url( ); ?>.</li>
        </ol>
        <br /><br />
    </div>
	<div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_2checkout_thirdparty_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>