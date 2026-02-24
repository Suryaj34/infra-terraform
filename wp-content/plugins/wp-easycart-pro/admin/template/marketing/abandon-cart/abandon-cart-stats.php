<div class="ec_admin_list_line_item_fullwidth ec_admin_demo_data_line">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_abandon_cart_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-dashboard"></div>
		<span><?php esc_attr_e( 'Abandoned Product Stats (Last 7 Days)', 'wp-easycart-pro' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('marketing', 'abandoned-carts', 'settings');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url('marketing', 'abandoned-carts', 'settings');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		<?php
		global $wpdb;
		$products = $wpdb->get_results( "SELECT ec_product.product_id, ec_product.title FROM ec_product" );
		?>
		<table class="wp-list-table widefat fixed striped pages" style="margin-top:10px; margin-bottom: 10px;">
			<thead>
				<tr>
					<td class="manage-column column-primary"><strong><?php esc_attr_e( 'Product', 'wp-easycart-pro' ); ?></strong></td>
					<td class="manage-column column-primary"><strong><?php esc_attr_e( 'Quantity', 'wp-easycart-pro' ); ?></strong></td>
				</tr>
			</thead>
			<tbody>
				<?php 
				if( count( $products ) > 0 ){
					foreach( $products as $cart ){
						$product_rows = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_tempcart WHERE product_id = %d AND last_changed_date >= DATE(NOW()) - INTERVAL 7 DAY GROUP BY session_id", $cart->product_id ) );
						$count = count( $product_rows );
						if( $count ){
					?>
				<tr>
					<td><?php echo $cart->title; ?></td>
					<td><?php echo $count; ?></td>
				</tr>
				<?php 
						}
					}
				}else{ ?>
				<tr>
					<td colspan="4"><?php esc_attr_e( 'No Abandoned Products Found', 'wp-easycart-pro' ); ?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
</div>