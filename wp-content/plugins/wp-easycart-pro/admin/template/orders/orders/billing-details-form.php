<div id="ec_admin_order_details_billing_form" class="ec_admin_order_details_row ec_admin_customer_info_top ec_admin_initial_hide">
	<div class="dashicons-before dashicons-yes ec_admin_order_details_totals_edit" id="ec_admin_order_details_billing_info_save"></div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'First Name', 'wp-easycart-pro' ); ?>" id="billing_first_name" name="billing_first_name" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_first_name; ?>" />
		</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Last Name', 'wp-easycart-pro' ); ?>" id="billing_last_name" name="billing_last_name" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_last_name; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Company Name', 'wp-easycart-pro' ); ?>" id="billing_company_name" name="billing_company_name" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_company_name; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Address', 'wp-easycart-pro' ); ?>" id="billing_address_line_1" name="billing_address_line_1" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_address_line_1; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Suite/Apartment #', 'wp-easycart-pro' ); ?>" id="billing_address_line_2" name="billing_address_line_2" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_address_line_2; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'City', 'wp-easycart-pro' ); ?>" id="billing_city" name="billing_city" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_city; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_23 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'State', 'wp-easycart-pro' ); ?>" id="billing_state" name="billing_state" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_state; ?>" />
		</div>
		<div class="ec_admin_order_details_column_13 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Zip/Postal', 'wp-easycart-pro' ); ?>" id="billing_zip" name="billing_zip" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_zip; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<select name="billing_country" id="billing_country" class="select2-basic wpep-required">
				<option value="0"><?php esc_attr_e( 'Select a Country', 'wp-easycart-pro' ); ?></option>
				<?php 
				global $wpdb;
				$countries = $wpdb->get_results( "SELECT * FROM ec_country ORDER BY name_cnt ASC" );
				foreach( $countries as $country ){
				?>
				<option value="<?php echo $country->iso2_cnt; ?>"<?php if( $country->iso2_cnt == wp_easycart_admin_orders( )->order_details->order->billing_country ){ ?> selected="selected"<?php }?>><?php echo $country->name_cnt; ?></option>
				<?php } ?>
		   </select>
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Phone', 'wp-easycart-pro' ); ?>" id="billing_phone" name="billing_phone" value="<?php echo wp_easycart_admin_orders( )->order_details->order->billing_phone; ?>" />
		</div>
	</div>
</div>