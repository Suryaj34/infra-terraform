<span class="ec_admin_order_details_order_date ec_admin_initial_hide" id="ec_admin_order_details_order_date_edit">
	<input type="text" class="wp-ec-datepicker" placeholder="<?php esc_attr_e( 'Order Date', 'wp-easycart-pro' ); ?>" id="order_date" name="order_date" value="<?php echo date( 'F d, Y', strtotime( wp_easycart_admin_orders( )->order_details->order->order_date ) ); ?>" />
	<div class="dashicons-before dashicons-yes ec_admin_order_details_totals_edit" id="ec_admin_order_date_save" style="top:8px; right:-35px;"></div>
</span>