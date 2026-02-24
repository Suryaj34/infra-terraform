<div class="ec_admin_refund_row ec_admin_initial_hide" id="ec_admin_edit_order_refund">
	<span><?php _e( 'Refund Amount', 'wp-easycart-pro' ); ?>: </span>
    <input type="number" name="refund_amount" id="refund_amount" step="0.01" min=".01" max="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->grand_total - wp_easycart_admin_orders( )->order_details->order->refund_total ); ?>" value="<?php echo $GLOBALS['currency']->get_number_only( wp_easycart_admin_orders( )->order_details->order->grand_total - wp_easycart_admin_orders( )->order_details->order->refund_total ); ?>">
    <input type="submit" value="<?php _e( 'Process Refund', 'wp-easycart-pro' ); ?>" onclick="ec_admin_process_refund( ); return false;" class="ec_admin_order_totals_edit_button">
</div>

<?php if ( '' != get_option( 'ec_option_google_adwords_tag_id' ) || '' != get_option( 'ec_option_google_ga4_property_id' ) ) { ?>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) ? get_option( 'ec_option_google_ga4_property_id' ) : get_option( 'ec_option_google_adwords_tag_id' ) ); ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){ dataLayer.push(arguments); }
		gtag( 'js', new Date() );
		<?php if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) { ?>
		gtag('config', '<?php echo esc_attr( get_option( 'ec_option_google_ga4_property_id' ) ); ?>');
		<?php } ?>
		<?php if ( '' != get_option( 'ec_option_google_adwords_tag_id' ) ) { ?>
		gtag('config', '<?php echo esc_attr( get_option( 'ec_option_google_adwords_tag_id' ) ); ?>');
		<?php } ?>
	</script>
<?php } ?>
