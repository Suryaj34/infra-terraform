<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_text_notifications_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-feedback"></div>
		<span><?php _e( 'Order Text Notifications', 'wp-easycart-pro' ); ?></span>
		<?php wp_easycart_admin( )->preloader->print_saved_icon( "ec_admin_checkout_form_settings_saved" ); ?>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'checkout', 'text-notifications');?>" target="_blank" class="ec_help_icon_link" title="<?php _e( 'View Help?', 'wp-easycart-pro' ); ?>">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'checkout', 'text-notifications' ); ?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_cloud_messages', 'ec_admin_save_text_notification_settings_options', get_option( 'ec_option_enable_cloud_messages' ), __( 'Enable Cloud Messaging', 'wp-easycart-pro' ), __( 'This will enable the triggers and messages you have setup below. Leave off during setup.', 'wp-easycart-pro' ) ); ?>
		
		<?php $country_options = array( ); ?>
		<?php foreach( wp_easycart_admin( )->countries as $country ){
			$country_options[] = (object) array(
				'value'	=> $country->iso2_cnt,
				'label'	=> $country->name_cnt
			);
		} ?>
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cloud_messages_default_country', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_cloud_messages_default_country' ), __( 'Country: Default Country', 'wp-easycart-pro' ), __( 'If no country is found using the default ip address to country system, it will default to this selection.', 'wp-easycart-pro' ), $country_options, 'ec_option_cloud_messages_default_country_row', get_option( 'ec_option_enable_cloud_messages' ), false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cloud_messages_preferred_countries', 'ec_admin_save_text_notification_settings_options', get_option( 'ec_option_cloud_messages_preferred_countries' ), __( 'Cloud Messaging Preferred Countries', 'wp-easycart-pro' ), __( 'Select the preferred countries to show in the phone country code list.', 'wp-easycart-pro' ), $country_options, 'ec_option_cloud_messages_preferred_countries_row', get_option( 'ec_option_enable_cloud_messages' ), false, true ); ?>

		<div style="float:left; border:1px solid #CCC; padding:15px; margin-bottom:25px; ">

			<h3 style="margin-top:0px; border-bottom:1px solid #CCC; padding-bottom:5px;"><?php esc_attr_e( 'Add New Message', 'wp-easycart-pro' ); ?></h3>
			<h5 style="margin-top:-10px;">** <?php esc_attr_e( 'Dynamic values are available for use, click the Help button for this section to learn more!', 'wp-easycart-pro' ); ?></h5>

			<div style="float:left; width:100%;" id="message_trigger_type_row_new" onchange="wpeasycart_text_trigger_update_display( 'new' )">
				<select id="new_message_trigger_type">
					<option value=""><?php esc_attr_e( 'Choose a Trigger', 'wp-easycart-pro' ); ?></option>
					<option value="new-subscriber"><?php esc_attr_e( 'On New User Notification Signup', 'wp-easycart-pro' ); ?></option>
					<option value="removed-subscriber"><?php esc_attr_e( 'On Subscriber Removed', 'wp-easycart-pro' ); ?></option>
					<option value="order-status-update"><?php esc_attr_e( 'On Order Status Update', 'wp-easycart-pro' ); ?></option>
					<option value="shipping-tracking-update"><?php esc_attr_e( 'On Shipping Tracking Update', 'wp-easycart-pro' ); ?></option>
					<option value="order-note"><?php esc_attr_e( 'On Order Notes Update', 'wp-easycart-pro' ); ?></option>
					<option value="shipping-address"><?php esc_attr_e( 'On Shipping Address Update', 'wp-easycart-pro' ); ?></option>
					<option value="billing-address"><?php esc_attr_e( 'On Billing Address Update', 'wp-easycart-pro' ); ?></option>
					<option value="line-items-updated"><?php esc_attr_e( 'On Order Line Item Updated', 'wp-easycart-pro' ); ?></option>
					<option value="line-items-added"><?php esc_attr_e( 'On Order Line Item Added', 'wp-easycart-pro' ); ?></option>
					<option value="line-items-deleted"><?php esc_attr_e( 'On Order Line Item Deleted', 'wp-easycart-pro' ); ?></option>
				</select>
			</div>

			<div style="float:left; width:100%; display:none;" id="message_order_status_id_row_new">
				<?php global $wpdb; $order_status_list = $wpdb->get_results( "SELECT ec_orderstatus.* FROM ec_orderstatus ORDER BY status_id" ); ?>
				<select id="new_message_order_status_id">
					<option value=""><?php esc_attr_e( 'Trigger on All Order Status', 'wp-easycart-pro' ); ?></option>
					<?php
					foreach( $order_status_list as $order_status ){
						echo '<option value="' . $order_status->status_id . '">' . $order_status->order_status . '</option>';
					}
					?>
				</select>
			</div>

			<div style="float:left; width:100%;">
				<input type="text" value="" placeholder="<?php esc_attr_e( 'Enter Your Message', 'wp-easycart-pro' ); ?>" name="new_cloud_message" id="new_cloud_message" />
			</div>

			<div style="float:left; width:100%; padding:0;" class="ec_admin_settings_input">
				<input type="button" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Add New Message', 'wp-easycart-pro' ); ?>" onclick="wpeasycart_add_cloud_message();" />
			</div>
		</div>

		<h3 style="margin-top:0px; border-bottom:1px solid #CCC; padding-bottom:5px;"><?php esc_attr_e( 'Manage Messages', 'wp-easycart-pro' ); ?></h3>
		<h5 style="margin-top:-10px;">** <?php esc_attr_e( 'Dynamic values are available for use, click the Help button for this section to learn more!', 'wp-easycart-pro' ); ?></h5>

		<div id="cloud_message_list_none" style="float:left; width:100%; font-weight:bold; background:#d63638; padding:12px; text-align:center; color:#ffffff; margin:-15px 0 0;<?php echo ( count( $message_list ) > 0 ) ? ' display:none;' : ''; ?>" id=""><?php esc_attr_e( 'You have not setup any messages for this cloud account.', 'wp-easycart-pro' ); ?></div>

		<div id="cloud_message_list" style="float:left; width:100%;<?php echo ( count( $message_list ) > 0 ) ? '' : ' display:none;'; ?>">
			<?php $this->print_message_list( $message_list ); ?>
		</div>

	</div>
</div>