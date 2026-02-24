<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "braintree" ){ ?>show<?php }else{?>hide<?php }?>" id="braintree">
    <span><?php _e( 'Setup Braintree S2S', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_braintree_merchant_id" id="ec_option_braintree_merchant_id" type="text" value="<?php echo get_option('ec_option_braintree_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Merchant Account ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_braintree_merchant_account_id" id="ec_option_braintree_merchant_account_id" type="text" value="<?php echo get_option('ec_option_braintree_merchant_account_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Public Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_braintree_public_key" id="ec_option_braintree_public_key" type="text" value="<?php echo get_option('ec_option_braintree_public_key'); ?>" />
    </div>
    <div>
        <?php _e( 'Private Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_braintree_private_key" id="ec_option_braintree_private_key" type="text" value="<?php echo get_option('ec_option_braintree_private_key'); ?>" />
    </div>
    <div>
        <?php _e( 'Environment', 'wp-easycart-pro' ); ?>
        <select name="ec_option_braintree_environment" id="ec_option_braintree_environment">
            <option value="sandbox" <?php if (get_option('ec_option_braintree_environment') == 'sandbox') echo ' selected'; ?>><?php _e( 'Sandbox', 'wp-easycart-pro' ); ?></option>
            <option value="production" <?php if (get_option('ec_option_braintree_environment') == 'production') echo ' selected'; ?>><?php _e( 'Production', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_braintree_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>