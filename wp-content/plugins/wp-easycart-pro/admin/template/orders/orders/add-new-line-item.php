<div class="ec_admin_order_details_line_item ec_admin_initial_hide" id="ec_admin_add_new_order_item">
	<div class="ec_admin_order_details_item_actions">
		<div class="dashicons-before dashicons-trash" onclick="ec_order_add_new_line_cancel( ); return false;"></div>
		<div class="dashicons-before dashicons-yes" onclick="ec_order_add_new_line_submit( ); return false;"></div>
	</div>
	<div class="ec_admin_order_details_item_details">
		<?php 
			global $wpdb;
			$products = $wpdb->get_results( "SELECT product_id, title, model_number, price FROM ec_product ORDER BY title ASC" ); 
		?>
		<select name="order_line_add_product_id" id="order_line_add_product_id" class="select2-basic">
			<option value="0"><?php esc_attr_e( 'Select a Product', 'wp-easycart-pro' ); ?></option>
			<?php foreach( $products as $product ){ ?>
			<option value="<?php echo $product->product_id; ?>"><?php echo $product->title; ?></option>
			<?php }?>
		</select>
	</div>
	<div class="ec_admin_order_details_item_price"><?php esc_attr_e( 'QTY', 'wp-easycart-pro' ); ?>: <input type="number" min="1" step="1" name="order_line_add_quantity" id="order_line_add_quantity" /></div>
	<div class="ec_admin_order_details_item_total"></div>
</div>