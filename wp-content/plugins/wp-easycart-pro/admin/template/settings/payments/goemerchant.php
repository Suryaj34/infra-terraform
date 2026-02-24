<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "goemerchant" ){ ?>show<?php }else{?>hide<?php }?>" id="goemerchant">
    <span><?php _e( 'Setup GoeMerchant', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Center ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_goemerchant_trans_center_id"  id="ec_option_goemerchant_trans_center_id" type="text" value="<?php echo get_option('ec_option_goemerchant_trans_center_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Gateway ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_goemerchant_gateway_id"  id="ec_option_goemerchant_gateway_id" type="text" value="<?php echo get_option('ec_option_goemerchant_gateway_id'); ?>" />
    </div>
    <div>
        <?php _e( 'Processor ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_goemerchant_processor_id"  id="ec_option_goemerchant_processor_id" type="text" value="<?php echo get_option('ec_option_goemerchant_processor_id'); ?>" />
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_goemerchant_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>