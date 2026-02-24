<div class="ec_admin_order_details_row ec_admin_customer_info_top ec_admin_initial_hide" id="ec_admin_edit_order_information">
	<div class="dashicons-before dashicons-yes ec_admin_order_details_totals_edit" id="ec_admin_order_details_save"></div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Cardholder Name', 'wp-easycart-pro' ); ?>" id="card_holder_name" name="card_holder_name" value="<?php if( wp_easycart_admin_orders( )->order_details->order->card_holder_name != "" ){ ?><?php echo wp_easycart_admin_orders( )->order_details->order->card_holder_name; ?><?php }else{ ?><?php echo wp_easycart_admin_orders( )->order_details->order->billing_first_name; ?> <?php echo wp_easycart_admin_orders( )->order_details->order->billing_last_name; ?><?php }?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Email', 'wp-easycart-pro' ); ?>" id="user_email" name="user_email" value="<?php echo wp_easycart_admin_orders( )->order_details->order->user_email; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Additional Email', 'wp-easycart-pro' ); ?>" id="email_other" name="email_other" value="<?php echo wp_easycart_admin_orders( )->order_details->order->email_other; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_1 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'CC Digits', 'wp-easycart-pro' ); ?>" id="creditcard_digits" name="creditcard_digits" value="<?php echo wp_easycart_admin_orders( )->order_details->order->creditcard_digits; ?>" />
		</div>
	</div>
	<div class="ec_admin_order_details_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Exp Month', 'wp-easycart-pro' ); ?>" id="cc_exp_month" name="cc_exp_month" value="<?php echo wp_easycart_admin_orders( )->order_details->order->cc_exp_month; ?>" />
		</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_input_padding">
			<input type="text" placeholder="<?php esc_attr_e( 'Exp Year', 'wp-easycart-pro' ); ?>" id="cc_exp_year" name="cc_exp_year" value="<?php echo wp_easycart_admin_orders( )->order_details->order->cc_exp_year; ?>" />
		</div>
	</div>
</div>