<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_google_adwords_loader" ); ?>
    
    <div class="ec_admin_settings_label">
        <div class="dashicons-before dashicons-admin-generic"></div>
        <span><?php esc_attr_e( 'Google Adwords Setup', 'wp-easycart-pro' ); ?></span>
        <a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'third-party', 'google adwords' ) );?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
        </a>
        <?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'third-party', 'google adwords');?>
    </div>
    
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        <div><?php esc_attr_e( 'Google Conversion ID (Conversion Tracking)', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_conversion_id" id="ec_option_google_adwords_conversion_id" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_conversion_id' ) ); ?>" /></div>
        <div><?php esc_attr_e( 'Google Tag ID (AW-XXXXXXXXX)', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_tag_id" id="ec_option_google_adwords_tag_id" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_tag_id' ) ); ?>" /></div>
       	<div><?php esc_attr_e( 'Google Conversion Language', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_language" id="ec_option_google_adwords_language" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_language' ) ); ?>" /> </div>
        <div><?php esc_attr_e( 'Google Conversion Format', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_format" id="ec_option_google_adwords_format" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_format' ) ); ?>" /> </div>
        <div><?php esc_attr_e( 'Google Conversion Color', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_color" id="ec_option_google_adwords_color" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_color' ) ); ?>" /> </div>
        <div><?php esc_attr_e( 'Google Conversion Currency', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_currency" id="ec_option_google_adwords_currency" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_currency' ) ); ?>" /> </div>
        <div><?php esc_attr_e( 'Google Conversion Label', 'wp-easycart-pro' ); ?><input type="text" name="ec_option_google_adwords_label" id="ec_option_google_adwords_label" value="<?php echo esc_attr( get_option( 'ec_option_google_adwords_label' ) ); ?>" /> </div>
        <div><input type="checkbox" name="ec_option_google_adwords_remarketing_only" id="ec_option_google_adwords_remarketing_only" value="0"<?php if( get_option('ec_option_google_adwords_remarketing_only') == "true" ){ echo " checked=\"checked\""; }?> /> <?php esc_attr_e( 'Google Remarketing Only', 'wp-easycart-pro' ); ?></div>
        
        <div class="ec_admin_settings_input">
            <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_google_adwords_pro( );" value="<?php esc_attr_e( 'Save Setup', 'wp-easycart-pro' ); ?>" />
        </div>
    </div>
</div>