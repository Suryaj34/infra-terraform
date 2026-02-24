<div id="ec_admin_order_details_shipping_form" class="ec_admin_order_details_row ec_admin_customer_info_top ec_admin_initial_hide">
    <div class="dashicons-before dashicons-yes ec_admin_order_details_totals_edit" id="ec_admin_order_details_shipping_info_save"></div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_12 ec_admin_order_details_input_padding">
        	<input type="text" placeholder="<?php _e( 'First Name', 'wp-easycart-pro' ); ?>" id="shipping_first_name" name="shipping_first_name" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_first_name; ?>" />
        </div>
        <div class="ec_admin_order_details_column_12 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'Last Name', 'wp-easycart-pro' ); ?>" id="shipping_last_name" name="shipping_last_name" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_last_name; ?>" />
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'Company Name', 'wp-easycart-pro' ); ?>" id="shipping_company_name" name="shipping_company_name" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_company_name; ?>" />
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'Address', 'wp-easycart-pro' ); ?>" id="shipping_address_line_1" name="shipping_address_line_1" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_address_line_1; ?>" />
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'Suite/Apartment #', 'wp-easycart-pro' ); ?>" id="shipping_address_line_2" name="shipping_address_line_2" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_address_line_2; ?>" />
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'City', 'wp-easycart-pro' ); ?>" id="shipping_city" name="shipping_city" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_city; ?>" />
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_23 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'State', 'wp-easycart-pro' ); ?>" id="shipping_state" name="shipping_state" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_state; ?>" />
        </div>
        <div class="ec_admin_order_details_column_13 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'Zip/Postal', 'wp-easycart-pro' ); ?>" id="shipping_zip" name="shipping_zip" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_zip; ?>" />
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
            <select name="shipping_country" id="shipping_country" class="select2-basic wpep-required">
            	<option value="0"><?php _e( 'Select a Country', 'wp-easycart-pro' ); ?></option>
                <?php 
				global $wpdb;
				$countries = $wpdb->get_results( "SELECT * FROM ec_country ORDER BY name_cnt ASC" );
				foreach( $countries as $country ){
				?>
                <option value="<?php echo $country->iso2_cnt; ?>"<?php if( $country->iso2_cnt == wp_easycart_admin_orders( )->order_details->order->shipping_country ){ ?> selected="selected"<?php }?>><?php echo $country->name_cnt; ?></option>
                <?php } ?>
           </select>
        </div>
    </div>
    <div class="ec_admin_order_details_row">
        <div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
            <input type="text" placeholder="<?php _e( 'Phone', 'wp-easycart-pro' ); ?>" id="shipping_phone" name="shipping_phone" value="<?php echo wp_easycart_admin_orders( )->order_details->order->shipping_phone; ?>" />
        </div>
    </div>
</div>