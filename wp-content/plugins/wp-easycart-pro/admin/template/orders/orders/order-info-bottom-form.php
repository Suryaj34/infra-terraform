<div class="ec_admin_order_details_row ec_admin_customer_info_top ec_admin_initial_hide" style="margin-top:20px;" id="ec_admin_edit_order_information_bottom">
	<div class="dashicons-before dashicons-yes ec_admin_order_details_totals_edit" id="ec_admin_order_details_save_bottom"></div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'IP Address', 'wp-easycart-pro' ); ?>" id="order_ip_address" name="order_ip_address" value="<?php echo wp_easycart_admin_orders( )->order_details->order->order_ip_address; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<select id="agreed_to_terms" name="agreed_to_terms" style="min-width:100% !important; max-width:100% !important; margin-right:0px !important;">
				<option value="1"<?php if( wp_easycart_admin_orders( )->order_details->order->agreed_to_terms ){ ?> selected="selected"<?php }?>><?php esc_attr_e( 'AGREED to Terms', 'wp-easycart-pro' ); ?></option>
				<option value="0"<?php if( !wp_easycart_admin_orders( )->order_details->order->agreed_to_terms ){ ?> selected="selected"<?php }?>><?php esc_attr_e( 'Did NOT Agree to Terms', 'wp-easycart-pro' ); ?></option>
			</select>
		</div>
	</div>
</div>