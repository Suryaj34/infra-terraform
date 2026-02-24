<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "payline" ){ ?>show<?php }else{?>hide<?php }?>" id="payline">
    <span><?php _e( 'Payline', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Username', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payline_username"  id="ec_option_payline_username" type="text" value="<?php echo get_option('ec_option_payline_username'); ?>" />
    </div>
    <div>
        <?php _e( 'Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payline_password"  id="ec_option_payline_password" type="password" value="<?php echo get_option('ec_option_payline_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Currency Code', 'wp-easycart-pro' ); ?>
        <input name="ec_option_payline_currency"  id="ec_option_payline_currency" type="text" value="<?php echo get_option('ec_option_payline_currency'); ?>" />
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_payline_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>