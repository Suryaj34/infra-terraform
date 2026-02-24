<table class="wp-list-table widefat fixed striped">
	<thead>
		<tr>
			<th width="80%" style="text-align:left; padding-left:15px; width:80%;"><?php esc_attr_e( 'Notification Details', 'wp-easycart-pro' ); ?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $message_list as $message ) { ?>
		<tr>
			<td style="width:80%;" width="80%">
				<div style="float:left; width:100%;" id="message_trigger_type_row_<?php echo esc_attr( $message->message_id ); ?>">
					<select id="message_trigger_type_<?php echo $message->message_id; ?>" onchange="wpeasycart_text_trigger_update_display(<?php echo esc_attr( $message->message_id ); ?>)">
						<option value=""><?php esc_attr_e( 'Choose a Trigger', 'wp-easycart-pro' ); ?></option>
						<option value="new-subscriber"<?php echo ( $message->trigger_type == 'new-subscriber' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On New User Notification Signup', 'wp-easycart-pro' ); ?></option>
						<option value="removed-subscriber"<?php echo ( $message->trigger_type == 'removed-subscriber' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Subscriber Removed', 'wp-easycart-pro' ); ?></option>
						<option value="order-status-update"<?php echo ( $message->trigger_type == 'order-status-update' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Order Status Update', 'wp-easycart-pro' ); ?></option>
						<option value="shipping-tracking-update"<?php echo ( $message->trigger_type == 'shipping-tracking-update' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Shipping Tracking Update', 'wp-easycart-pro' ); ?></option>
						<option value="order-note"<?php echo ( $message->trigger_type == 'order-note' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Order Notes Update', 'wp-easycart-pro' ); ?></option>
						<option value="shipping-address"<?php echo ( $message->trigger_type == 'shipping-address' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Shipping Address Update', 'wp-easycart-pro' ); ?></option>
						<option value="billing-address"<?php echo ( $message->trigger_type == 'billing-address' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Billing Address Update', 'wp-easycart-pro' ); ?></option>
						<option value="line-items-updated"<?php echo ( $message->trigger_type == 'line-items-updated' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Line Items Update', 'wp-easycart-pro' ); ?></option>
						<option value="line-items-added"<?php echo ( $message->trigger_type == 'line-items-added' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Order Line Item Added', 'wp-easycart-pro' ); ?></option>
						<option value="line-items-deleted"<?php echo ( $message->trigger_type == 'line-items-deleted' ) ? ' selected="selected"' : ''; ?>><?php esc_attr_e( 'On Order Line Item Deleted', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>

				<div style="float:left; width:100%;<?php echo ( $message->trigger_type != 'order-status-update' ) ? ' display:none;' : ''; ?>" id="message_order_status_id_row_<?php echo esc_attr( $message->message_id ); ?>">
					<?php global $wpdb; $order_status_list = $wpdb->get_results( "SELECT ec_orderstatus.* FROM ec_orderstatus ORDER BY status_id" ); ?>
					<select id="message_order_status_id_<?php echo esc_attr( $message->message_id ); ?>">
						<option value=""><?php esc_attr_e( 'Trigger on All Order Status', 'wp-easycart-pro' ); ?></option>
						<?php
						foreach( $order_status_list as $order_status ){
							echo '<option value="' . $order_status->status_id . '"' . ( ( $message->order_status_id == $order_status->status_id ) ? ' selected="selected"' : '' ) . '>' . $order_status->order_status . '</option>';
						}
						?>
					</select>
				</div>

				<div style="float:left; width:100%;">
					<input type="text" value="<?php echo esc_attr( $message->message ); ?>" placeholder="<?php esc_attr_e( 'Enter Your Message', 'wp-easycart-pro' ); ?>" id="cloud_message_<?php echo $message->message_id; ?>" />
				</div>

				<div style="float:left; width:100%; padding:0;" class="ec_admin_settings_input">
					<input type="button" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Update Message', 'wp-easycart-pro' ); ?>" onclick="wpeasycart_update_cloud_message( <?php echo $message->message_id; ?> );" />
				</div>
			</td>
			<td style="text-align:center; vertical-align:top; width:20%;" valign="top" width="20%">
				<a href="#" onclick="wpeasycart_delete_cloud_message( <?php echo esc_attr( $message->message_id ); ?> ); return false;" title="<?php esc_attr_e( 'Delete', 'wp-easycart-pro' ); ?>" style="display:inline-block; text-align:center; background:#d63638; color:#ffff; border-radius:100%; padding:7px; margin-top:15px;"><span class="dashicons dashicons-trash"></span></a>
			</td>
		</tr>
		<?php }?>
	</tbody>
</table>