<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "migs" ){ ?>show<?php }else{?>hide<?php }?>" id="migs">
    <span><?php _e( 'Setup MasterCard Internet Gateway Service (MIGS)', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Secure Secret(Signature)', 'wp-easycart-pro' ); ?>
        <input name="ec_option_migs_signature"  id="ec_option_migs_signature" type="text" value="<?php echo get_option('ec_option_migs_signature'); ?>" />
    </div>
    <div>
        <?php _e( 'Access Code', 'wp-easycart-pro' ); ?>
        <input name="ec_option_migs_access_code"  id="ec_option_migs_access_code" type="text" value="<?php echo get_option('ec_option_migs_access_code'); ?>" />
    </div>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_migs_merchant_id"  id="ec_option_migs_merchant_id" type="text" value="<?php echo get_option('ec_option_migs_merchant_id'); ?>" />
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_migs_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>