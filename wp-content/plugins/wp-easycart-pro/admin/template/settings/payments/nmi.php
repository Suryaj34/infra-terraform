<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "nmi" ){ ?>show<?php }else{?>hide<?php }?>" id="nmi">
    <span><?php _e( 'Network Merchants (NMI)', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Processing Method', 'wp-easycart-pro' ); ?>
        <select name="ec_option_nmi_3ds" id="ec_option_nmi_3ds" onchange="ec_admin_update_nmi_cardinal_view( );">
            <option value="0" <?php if ( get_option( 'ec_option_nmi_3ds') == "0" ){ echo " selected=\"selected\""; } ?>><?php _e( 'Use Standard Direct Post', 'wp-easycart-pro' ); ?></option>
            <option value="2" <?php if ( get_option( 'ec_option_nmi_3ds') == "2" ){ echo " selected=\"selected\""; } ?>><?php _e( 'Use Cardinal Centinel with NMI', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_nmi_cardinal_setting <?php if( get_option( 'ec_option_nmi_3ds') == "0" ){ ?> ec_admin_initial_hide<?php }?>">
        <?php _e( 'NMI API Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_nmi_api_key" id="ec_option_nmi_api_key" type="text" value="<?php echo get_option('ec_option_nmi_api_key'); ?>" />
    </div>
    <div>
        <?php _e( 'NMI Username', 'wp-easycart-pro' ); ?>
        <input name="ec_option_nmi_username"  id="ec_option_nmi_username" type="text" value="<?php echo get_option('ec_option_nmi_username'); ?>" />
    </div>
    <div>
        <?php _e( 'NMI Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_nmi_password"  id="ec_option_nmi_password" type="password" value="<?php echo get_option('ec_option_nmi_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Your Shipping Postal', 'wp-easycart-pro' ); ?>
        <input name="ec_option_nmi_ship_from_zip"  id="ec_option_nmi_ship_from_zip" type="text" value="<?php echo get_option('ec_option_nmi_ship_from_zip'); ?>" />
    </div>
    <div>
        <?php _e( 'NMI Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_nmi_currency" id="ec_option_nmi_currency">
            <option value="USD" <?php if ( get_option( 'ec_option_nmi_currency') == "USD" ){ echo " selected=\"selected\""; } ?>>USD</option>
            <option value="CAD" <?php if ( get_option( 'ec_option_nmi_currency') == "CAD" ){ echo " selected=\"selected\""; } ?>>CAD</option>
            <option value="EUR" <?php if ( get_option( 'ec_option_nmi_currency') == "EUR" ){ echo " selected=\"selected\""; } ?>>EUR</option>
            <option value="GBP" <?php if ( get_option( 'ec_option_nmi_currency') == "GBP" ){ echo " selected=\"selected\""; } ?>>GBP</option>
        </select>
    </div>
    <div>
        <?php _e( 'NMI Processor ID (Optional)', 'wp-easycart-pro' ); ?>
        <input name="ec_option_nmi_processor_id"  id="ec_option_nmi_processor_id" type="text" value="<?php echo get_option('ec_option_nmi_processor_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Summary Commodity Code', 'wp-easycart-pro' ); ?>
        <input name="ec_option_nmi_commodity_code"  id="ec_option_nmi_commodity_code" type="text" value="<?php echo get_option('ec_option_nmi_commodity_code'); ?>" />
    </div>
    <div class="ec_admin_nmi_cardinal_setting <?php if( get_option( 'ec_option_nmi_3ds') == "0" ){ ?> ec_admin_initial_hide<?php }?>">
        <?php _e( 'Cardinal Processor ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardinal_processor_id"  id="ec_option_cardinal_processor_id" type="text" value="<?php echo get_option('ec_option_cardinal_processor_id'); ?>" />
    </div>
    <div class="ec_admin_nmi_cardinal_setting <?php if( get_option( 'ec_option_nmi_3ds') == "0" ){ ?> ec_admin_initial_hide<?php }?>">
        <?php _e( 'Cardinal Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardinal_merchant_id"  id="ec_option_cardinal_merchant_id" type="text" value="<?php echo get_option('ec_option_cardinal_merchant_id'); ?>" />
    </div>
    <div class="ec_admin_nmi_cardinal_setting <?php if( get_option( 'ec_option_nmi_3ds') == "0" ){ ?> ec_admin_initial_hide<?php }?>">
        <?php _e( 'Cardinal Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardinal_password"  id="ec_option_cardinal_password" type="password" value="<?php echo get_option('ec_option_cardinal_password'); ?>" />
    </div>
    <div class="ec_admin_nmi_cardinal_setting <?php if( get_option( 'ec_option_nmi_3ds') == "0" ){ ?> ec_admin_initial_hide<?php }?>">
        <?php _e( 'Cardinal Currency', 'wp-easycart-pro' ); ?>
        <select name="ec_option_cardinal_currency" id="ec_option_cardinal_currency">
            <option value="840" <?php if ( get_option( 'ec_option_cardinal_currency') == "840" ){ echo " selected=\"selected\""; } ?>>USD</option>
            <option value="124" <?php if ( get_option( 'ec_option_cardinal_currency') == "124" ){ echo " selected=\"selected\""; } ?>>CAD</option>
            <option value="978" <?php if ( get_option( 'ec_option_cardinal_currency') == "978" ){ echo " selected=\"selected\""; } ?>>EUR</option>
            <option value="826" <?php if ( get_option( 'ec_option_cardinal_currency') == "826" ){ echo " selected=\"selected\""; } ?>>GBP</option>
        </select>
    </div>
    <div class="ec_admin_nmi_cardinal_setting <?php if( get_option( 'ec_option_nmi_3ds') == "0" ){ ?> ec_admin_initial_hide<?php }?>">
        <?php _e( 'Cardinal Test Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_cardinal_test_mode" id="ec_option_cardinal_test_mode">
            <option value="0" <?php if ( get_option( 'ec_option_cardinal_test_mode') == "0" ){ echo " selected=\"selected\""; } ?>><?php _e( 'No, Live Mode', 'wp-easycart-pro' ); ?></option>
            <option value="1" <?php if ( get_option( 'ec_option_cardinal_test_mode') == "1" ){ echo " selected=\"selected\""; } ?>><?php _e( 'Yes, Test Mode', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_nmi_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>