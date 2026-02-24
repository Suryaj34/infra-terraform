<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "payfort" ){ ?>show<?php }else{?>hide<?php }?>" id="payfort">
    <span><?php _e( 'Setup Payfort', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant Identifier', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payfort_merchant_id"  id="ec_option_payfort_merchant_id" type="text" value="<?php echo get_option('ec_option_payfort_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Access Code', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payfort_access_code"  id="ec_option_payfort_access_code" type="text" value="<?php echo get_option('ec_option_payfort_access_code'); ?>" />
    </div>
    <div>
        <?php _e( 'Encryption Type', 'wp-easycart-pro' ); ?>
    	<select name="ec_option_payfort_sha_type" id="ec_option_payfort_sha_type">
            <option value="sha1" <?php if (get_option('ec_option_payfort_sha_type') == 'sha1') echo ' selected'; ?>>SHA-128</option>
            <option value="sha256" <?php if (get_option('ec_option_payfort_sha_type') == 'sha256') echo ' selected'; ?>>SHA-256</option>
            <option value="sha512" <?php if (get_option('ec_option_payfort_sha_type') == 'sha512') echo ' selected'; ?>>SHA-512</option>
        </select>
    </div>
    <div>
        <?php _e( 'SHA Request Phrase', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payfort_request_phrase" id="ec_option_payfort_request_phrase" type="text" value="<?php echo get_option('ec_option_payfort_request_phrase'); ?>" />
    </div>
    <div>
        <?php _e( 'SHA Response Phrase', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payfort_response_phrase" id="ec_option_payfort_response_phrase" type="text" value="<?php echo get_option('ec_option_payfort_response_phrase'); ?>" />
    </div>
    <div>
        <?php _e( 'Language', 'wp-easycart-pro' ); ?>
    	<select name="ec_option_payfort_language" id="ec_option_payfort_language">
            <option value="en" <?php if (get_option('ec_option_payfort_language') == 'en') echo ' selected'; ?>><?php _e( 'English', 'wp-easycart-pro' ); ?></option>
            <option value="ar" <?php if (get_option('ec_option_payfort_language') == 'ar') echo ' selected'; ?>><?php _e( 'Arabic', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div>
        <?php _e( 'Currency Code(e.g. USD)', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payfort_currency_code" id="ec_option_payfort_currency_code" type="text" value="<?php echo get_option('ec_option_payfort_currency_code'); ?>" />
    </div>
    <div>
        <?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
    	<select name="ec_option_payfort_test_mode" id="ec_option_payfort_test_mode">
            <option value="1" <?php if (get_option('ec_option_payfort_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Test Mode', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_payfort_test_mode') == 0) echo ' selected'; ?>><?php _e( 'Production Mode', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_payfort_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
    
</div>