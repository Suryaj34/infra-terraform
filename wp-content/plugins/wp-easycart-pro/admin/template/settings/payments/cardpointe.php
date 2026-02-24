<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "cardpointe" ){ ?>show<?php }else{?>hide<?php }?>" id="cardpointe">
    <span><?php _e( 'Card Pointe', 'wp-easycart-pro' ); ?></span>
    <div>
        <?php _e( 'Site', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardpointe_site" id="ec_option_cardpointe_site" type="text" value="<?php echo get_option( 'ec_option_cardpointe_site' ); ?>" />
    </div>
    <div>
        <?php _e( 'Merchant ID', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardpointe_merch" id="ec_option_cardpointe_merch" type="text" value="<?php echo get_option( 'ec_option_cardpointe_merch' ); ?>" />
    </div>
    <div>
        <?php _e( 'API Username', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardpointe_username" id="ec_option_cardpointe_username" type="text" value="<?php echo get_option( 'ec_option_cardpointe_username' ); ?>" />
    </div>
    <div>
        <?php _e( 'API Password', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardpointe_password" id="ec_option_cardpointe_password" type="password" value="<?php echo get_option('ec_option_cardpointe_password'); ?>" />
    </div>
    <div>
        <?php _e( 'Ship From Zip', 'wp-easycart-pro' ); ?>
        <input name="ec_option_cardpointe_shipfromzip" id="ec_option_cardpointe_shipfromzip" type="text" value="<?php echo get_option( 'ec_option_cardpointe_shipfromzip' ); ?>" />
    </div>
    <div>
        <?php _e( 'Currency Code', 'wp-easycart-pro' ); ?>
        <select name="ec_option_cardpointe_currency" id="ec_option_cardpointe_currency">
		    <option value="USD"<?php echo ( get_option('ec_option_cardpointe_currency') == 'USD' ) ? ' selected="selected"' : ''; ?>><?php _e( 'USD', 'wp-easycart-pro' ); ?></option>
		    <option value="CAD"<?php echo ( get_option('ec_option_cardpointe_currency') == 'CAD' ) ? ' selected="selected"' : ''; ?>><?php _e( 'CAD', 'wp-easycart-pro' ); ?></option>
	    </select>
    </div>
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_cardpointe_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
    </div>
</div>