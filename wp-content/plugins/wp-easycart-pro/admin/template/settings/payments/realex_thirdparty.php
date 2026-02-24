<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "realex_thirdparty" ){ ?>show<?php }else{?>hide<?php }?>" id="realex_thirdparty">
    <span><?php _e( 'Setup Realex', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_realex_thirdparty_merchant_id"  id="ec_option_realex_thirdparty_merchant_id" type="text" value="<?php echo get_option('ec_option_realex_thirdparty_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Secret', 'wp-easycart-pro' ); ?>
        <input name="ec_option_realex_thirdparty_secret"  id="ec_option_realex_thirdparty_secret" type="text" value="<?php echo get_option('ec_option_realex_thirdparty_secret'); ?>" />
    </div>
    <div>
        <?php _e( 'Account', 'wp-easycart-pro' ); ?>
        <input name="ec_option_realex_thirdparty_account"  id="ec_option_realex_thirdparty_account" type="text" value="<?php echo get_option('ec_option_realex_thirdparty_account'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_realex_thirdparty_currency" id="ec_option_realex_thirdparty_currency">
            <option value="GBP" <?php if (get_option('ec_option_realex_thirdparty_currency') == "GBP") echo ' selected'; ?>>GBP</option>
            <option value="EUR" <?php if (get_option('ec_option_realex_thirdparty_currency') == "EUR") echo ' selected'; ?>>EUR</option>
            <option value="USD" <?php if (get_option('ec_option_realex_thirdparty_currency') == "USD") echo ' selected'; ?>>USD</option>
            <option value="DKK" <?php if (get_option('ec_option_realex_thirdparty_currency') == "DKK") echo ' selected'; ?>>DKK</option>
            <option value="NOK" <?php if (get_option('ec_option_realex_thirdparty_currency') == "NOK") echo ' selected'; ?>>NOK</option>
            <option value="CHF" <?php if (get_option('ec_option_realex_thirdparty_currency') == "CHF") echo ' selected'; ?>>CHF</option>
            <option value="AUD" <?php if (get_option('ec_option_realex_thirdparty_currency') == "AUD") echo ' selected'; ?>>AUD</option>
            <option value="CAD" <?php if (get_option('ec_option_realex_thirdparty_currency') == "CAD") echo ' selected'; ?>>CAD</option>
            <option value="CZK" <?php if (get_option('ec_option_realex_thirdparty_currency') == "CZK") echo ' selected'; ?>>CZK</option>
            <option value="JPY" <?php if (get_option('ec_option_realex_thirdparty_currency') == "JPY") echo ' selected'; ?>>JPY</option>
            <option value="NZD" <?php if (get_option('ec_option_realex_thirdparty_currency') == "NZD") echo ' selected'; ?>>NZD</option>
            <option value="HKD" <?php if (get_option('ec_option_realex_thirdparty_currency') == "HKD") echo ' selected'; ?>>HKD</option>
            <option value="ZAR" <?php if (get_option('ec_option_realex_thirdparty_currency') == "ZAR") echo ' selected'; ?>>ZAR</option>
            <option value="SEK" <?php if (get_option('ec_option_realex_thirdparty_currency') == "SEK") echo ' selected'; ?>>SEK</option>
        </select>
    </div>
          
    <div class="ec_admin_settings_notice"><strong><?php _e( 'To Do', 'wp-easycart-pro' ); ?>:</strong> <?php _e( 'You must submit the following URLs to Realex before the redirect method can work completely. You should also add information to your account for your customers to see on the payment page, successful payment page, and the failed payment page.', 'wp-easycart-pro' ); ?></div>

    <div class="ec_admin_settings_notice"><strong><?php _e( 'Realex Referring URL', 'wp-easycart-pro' ); ?>:</strong> <?php echo $this->cart_page . $this->permalink_divider . "ec_page=realex_redirect"; ?></div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_realex_thirdparty_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>