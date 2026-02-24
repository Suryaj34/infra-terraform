<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "beanstream" ){ ?>show<?php }else{?>hide<?php }?>" id="beanstream">
    <span><?php _e( 'Setup Bambora', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_beanstream_merchant_id" id="ec_option_beanstream_merchant_id" type="text" value="<?php echo get_option('ec_option_beanstream_merchant_id'); ?>" />
    </div>
    <div>
        <?php _e( 'API Passcode', 'wp-easycart-pro' ); ?>
        <input name="ec_option_beanstream_api_passcode" id="ec_option_beanstream_api_passcode" type="text" value="<?php echo get_option('ec_option_beanstream_api_passcode'); ?>" />
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_beanstream_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>