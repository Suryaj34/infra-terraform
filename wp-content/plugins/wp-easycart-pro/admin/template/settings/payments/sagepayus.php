<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "sagepayus" ){ ?>show<?php }else{?>hide<?php }?>" id="sagepayus">
    <span><?php _e( 'Setup Paya (Previously Sagepay U.S.)', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'User ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_sagepayus_mid"  id="ec_option_sagepayus_mid" type="text" value="<?php echo get_option('ec_option_sagepayus_mid'); ?>" />
    </div>
    <div>
        <?php _e( 'User Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_sagepayus_mkey"  id="ec_option_sagepayus_mkey" type="text" value="<?php echo get_option('ec_option_sagepayus_mkey'); ?>" />
    </div>
    <div>
        <?php _e( 'Application ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_sagepayus_application_id"  id="ec_option_sagepayus_application_id" type="text" value="<?php echo get_option('ec_option_sagepayus_application_id'); ?>" />
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_sagepayus_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>