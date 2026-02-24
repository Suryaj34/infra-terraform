<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "eway" ){ ?>show<?php }else{?>hide<?php }?>" id="eway">
    <span><?php _e( 'Setup eWay', 'wp-easycart-pro' ); ?></span>
    <span id="eway_api_info"><p><strong><?php _e( 'eWay Rapid API Setup: Copy your API key and API password from your eWay account, My Account Tab and sub category API Key. Generate a password here and copy and paste it into the box provided.', 'wp-easycart-pro' ); ?></strong></p></span>
    <div>
        <?php _e( 'Payment Method', 'wp-easycart-pro' ); ?>
        <select name="ec_option_eway_use_rapid_pay" id="ec_option_eway_use_rapid_pay" onchange="ec_eway_payment_type_change( );">
            <option value="1" <?php if (get_option('ec_option_eway_use_rapid_pay') == 1) echo ' selected'; ?>><?php _e( 'Rapid Pay', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_eway_use_rapid_pay') == 0) echo ' selected'; ?>><?php _e( 'Direct Payment (outdated)', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div id="eway_customer_id">
        <?php _e( 'Customer ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_eway_customer_id"  id="ec_option_eway_customer_id" type="text" value="<?php echo get_option('ec_option_eway_customer_id'); ?>" />
    </div>
    <div id="eway_api_key">
        <?php _e( 'API Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_eway_api_key"  id="ec_option_eway_api_key" type="text" value="<?php echo get_option('ec_option_eway_api_key'); ?>" />
    </div>
    <div id="eway_api_password">
        <?php _e( 'API Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_eway_api_password"  id="ec_option_eway_api_password" type="password" value="<?php echo get_option('ec_option_eway_api_password'); ?>" />
    </div>
    <div id="eway_client_key">
        <?php _e( 'Client Key', 'wp-easycart-pro' ); ?>
        <input name="ec_option_eway_client_key"  id="ec_option_eway_client_key" type="text" value="<?php echo get_option('ec_option_eway_client_key'); ?>" />
    </div>
    <div>
        <?php _e( 'Sandbox Mode', 'wp-easycart-pro' ); ?>
        <select name="ec_option_eway_test_mode" id="ec_option_eway_test_mode">
            <option value="1" <?php if (get_option('ec_option_eway_test_mode') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_eway_test_mode') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    <div id="eway_process_transaction">
        <?php _e( 'Eway Test Mode Process Successful Transaction', 'wp-easycart-pro' ); ?>
        <select name="ec_option_eway_test_mode_success" id="ec_option_eway_test_mode_success">
            <option value="1" <?php if (get_option('ec_option_eway_test_mode_success') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
            <option value="0" <?php if (get_option('ec_option_eway_test_mode_success') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
        </select>
    </div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_eway_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>
<script>
function ec_eway_payment_type_change( ){
	if( jQuery( document.getElementById( 'ec_option_eway_use_rapid_pay' ) ).val( ) == "1" ){
		jQuery( document.getElementById( 'eway_api_info' ) ).show( );
		jQuery( document.getElementById( 'eway_customer_id' ) ).hide( );
		jQuery( document.getElementById( 'eway_process_transaction' ) ).hide( );
		jQuery( document.getElementById( 'eway_api_key' ) ).show( );
		jQuery( document.getElementById( 'eway_api_password' ) ).show( );
		jQuery( document.getElementById( 'eway_client_key' ) ).show( );
	}else{
		jQuery( document.getElementById( 'eway_api_info' ) ).hide( );
		jQuery( document.getElementById( 'eway_customer_id' ) ).show( );
		jQuery( document.getElementById( 'eway_process_transaction' ) ).show( );
		jQuery( document.getElementById( 'eway_api_key' ) ).hide( );
		jQuery( document.getElementById( 'eway_api_password' ) ).hide( );
		jQuery( document.getElementById( 'eway_client_key' ) ).hide( );
	}
}
ec_eway_payment_type_change( );
</script>