<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_<?php if( get_option('ec_option_payment_third_party') == "sagepay_paynow_za" ){ ?>show<?php }else{?>hide<?php }?>" id="sagepay_paynow_za">
    <span><?php _e( 'Setup SagePay Pay Now South Africa', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Service Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_sagepay_paynow_za_service_key" id="ec_option_sagepay_paynow_za_service_key" type="text" value="<?php echo get_option('ec_option_sagepay_paynow_za_service_key'); ?>" />
    </div>
	
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_sagepay_paynow_za_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>