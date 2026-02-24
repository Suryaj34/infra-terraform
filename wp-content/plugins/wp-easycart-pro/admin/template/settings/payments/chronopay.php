<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "chronopay" ){ ?>show<?php }else{?>hide<?php }?>" id="chronopay">
    <span><?php _e( 'Setup Chronopay', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Currency', 'wp-easycart-pro' ); ?>
        <input name="ec_option_chronopay_currency" id="ec_option_chronopay_currency" type="text" value="<?php echo get_option('ec_option_chronopay_currency'); ?>" />
    </div>
    <div>
        <?php _e( 'Product ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_chronopay_product_id" id="ec_option_chronopay_product_id" type="text" value="<?php echo get_option('ec_option_chronopay_product_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Shared Secret', 'wp-easycart-pro' ); ?>
        <input name="ec_option_chronopay_shared_secret" id="ec_option_chronopay_shared_secret" type="text" value="<?php echo get_option('ec_option_chronopay_shared_secret'); ?>" />
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_chronopay_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>