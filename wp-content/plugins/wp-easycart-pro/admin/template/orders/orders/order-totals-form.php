<?php
global $wpdb;
$duty_tax = $wpdb->get_row( "SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_duty = 1" );
$vat_tax = $wpdb->get_row( "SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_vat = 1 OR tax_by_single_vat = 1" );
$global_tax = $wpdb->get_row( "SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_all = 1" );
$easy_canada_tax = get_option( 'ec_option_enable_easy_canada_tax' );
?>
<div id="ec_admin_order_details_totals_form" class="ec_admin_initial_hide">
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'Sub Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="sub_total" name="sub_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->sub_total ); ?>" /></div>
	</div>
	<?php if ( false !== $vat_tax || false !== $global_tax ) { ?>
			<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
				<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'VAT Total', 'wp-easycart-pro' ); ?>:</div>
				<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="vat_total" name="vat_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->vat_total ); ?>" /></div>
			</div>
			<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
				<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'VAT Rate', 'wp-easycart-pro' ); ?>:</div>
				<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step=".001" id="vat_rate" name="vat_rate" value="<?php echo wp_easycart_admin_orders( )->order_details->order->vat_rate; ?>" /></div>
			</div>
			<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
				<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'VAT Registration #', 'wp-easycart-pro' ); ?>:</div>
				<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="text" name="vat_registration_number" id="vat_registration_number" value="<?php echo wp_easycart_admin_orders( )->order_details->order->vat_registration_number;?>" /></div>
			</div>
	<?php } ?>
	<?php if ( $easy_canada_tax || false !== $global_tax ) { ?>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'GST Total', 'wp-easycart-pro' ); ?> (<?php echo wp_easycart_admin_orders( )->order_details->order->gst_rate;?>%) :</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="gst_total" name="gst_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->gst_total ); ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'GST Rate', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step=".001" id="gst_rate" name="gst_rate" value="<?php echo wp_easycart_admin_orders( )->order_details->order->gst_rate; ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'HST Total', 'wp-easycart-pro' ); ?> (<?php echo wp_easycart_admin_orders( )->order_details->order->hst_rate;?>%) :</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="hst_total" name="hst_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->hst_total ); ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'HST Rate', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step=".001" id="hst_rate" name="hst_rate" value="<?php echo wp_easycart_admin_orders( )->order_details->order->hst_rate; ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'PST Total', 'wp-easycart-pro' ); ?> (<?php echo wp_easycart_admin_orders( )->order_details->order->pst_rate;?>%) :</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="pst_total" name="pst_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->pst_total ); ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'PST Rate', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step=".001" id="pst_rate" name="pst_rate" value="<?php echo wp_easycart_admin_orders( )->order_details->order->pst_rate; ?>" /></div>
	</div>
	<?php } ?>
	<?php if ( false !== $duty_tax || false !== $global_tax ) { ?>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'Duty Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="duty_total" name="duty_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->duty_total );?>" /></div>
	</div>
	<?php } ?>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'Tax Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="tax_total" name="tax_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->tax_total ); ?>" /></div>
	</div>
	<?php if ( count( wp_easycart_admin_orders( )->order_details->order->order_fees ) > 0 ) { ?>
	<?php foreach ( wp_easycart_admin_orders( )->order_details->order->order_fees as $order_fee ) { ?>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row ec_admin_order_details_flex_fee_row" data-flex-fee-id="<?php echo esc_attr( $order_fee->order_fee_id ); ?>">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php echo esc_attr( $order_fee->fee_label ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="flex_fee_<?php echo esc_attr( $order_fee->order_fee_id ); ?>" name="flex_fee[]" value="<?php echo $GLOBALS['currency']->get_number_only( $order_fee->fee_total ); ?>" /></div>
	</div>
	<?php }?>
	<?php }?>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'Shipping Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="shipping_total" name="shipping_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->shipping_total ); ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label"><?php _e( 'Discount Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" min="0" id="discount_total" name="discount_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->discount_total ); ?>" /></div>
	</div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label ec_admin_order_details_currency_grand_total_label"><?php _e( 'Grand Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total ec_admin_order_details_currency_grand_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="grand_total" name="grand_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->grand_total ); ?>" /></div>
	</div>
	<div class="dashicons-before dashicons-yes ec_admin_order_details_totals_edit" id="ec_admin_order_total_save"></div>
	<div class="ec_admin_order_details_row ec_admin_order_details_currency_row">
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_label ec_admin_order_details_currency_refund_label"><?php _e( 'Refund Total', 'wp-easycart-pro' ); ?>:</div>
		<div class="ec_admin_order_details_column_12 ec_admin_order_details_currency_total ec_admin_order_details_currency_refund_total"><input type="number" step="<?php echo pow( 10, -1 * $GLOBALS['currency']->get_decimal_length( ) ); ?>" id="refund_total" name="refund_total" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->refund_total ); ?>" /></div>
	</div>
</div>