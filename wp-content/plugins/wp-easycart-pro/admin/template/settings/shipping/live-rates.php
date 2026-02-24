<?php
include( WP_EASYCART_ADMIN_PRO_PLUGIN_DIR . 'admin/inc/wp_easycart_live_rate_codes.php' );
$live_triggers = $this->wpdb->get_results( "SELECT * FROM ec_shippingrate WHERE is_ups_based = 1 OR is_usps_based = 1 OR is_fedex_based = 1 OR is_auspost_based = 1 OR is_dhl_based = 1 OR is_canadapost_based = 1 ORDER BY shipping_order ASC" );
$currency = new ec_currency( );
?>
<div style="width:100% !important;" class="ec_admin_settings_input ec_admin_settings_shipping_section ec_admin_settings_<?php if( wp_easycart_admin( )->settings->shipping_method == "live" ){ ?>show<?php }else{?>hide<?php }?>" id="live">

	<div class="ec_admin_live_rates_status_row">
		<?php
		$status = new wp_easycart_admin_store_status( );
		////////////////////////////
		// UPS Shipping Check
		////////////////////////////
		if( $status->ec_ups_shipping_setup( ) ){ ?>
		<div class="ec_live_shipping_status_good"><div class="dashicons-before dashicons-yes"></div><span class="ec_live_shipping_status_label"><?php _e( 'UPS CONNECTED', 'wp-easycart-pro' ); ?></span></div>
		<?php }else{ ?>
		<a class="ec_live_shipping_status_bad" href="admin.php?page=wp-easycart-settings&subpage=shipping-settings"><div class="dashicons-before dashicons-no"></div><span class="ec_live_shipping_status_label"><?php _e( 'UPS NOT SETUP', 'wp-easycart-pro' ); ?></span></a>
		<?php }

		////////////////////////////
		// USPS Shipping Check
		////////////////////////////
		if( $status->ec_usps_shipping_setup( ) ){ ?>
		<div class="ec_live_shipping_status_good"><div class="dashicons-before dashicons-yes"></div><span class="ec_live_shipping_status_label"><?php _e( 'USPS CONNECTED', 'wp-easycart-pro' ); ?></span></div>
		<?php }else{ ?>
		<a class="ec_live_shipping_status_bad" href="admin.php?page=wp-easycart-settings&subpage=shipping-settings"><div class="dashicons-before dashicons-no"></div><span class="ec_live_shipping_status_label"><?php _e( 'USPS NOT SETUP', 'wp-easycart-pro' ); ?></span></a>
		<?php }

		////////////////////////////
		// FEDEX Shipping Check
		////////////////////////////
		$fedex_status = wp_easycart_admin_live_shipping_rates_pro( )->get_fedex_status( ); 
		if ( 'connected' == $fedex_status ) { ?>
		<div class="ec_live_shipping_status_good"><div class="dashicons-before dashicons-yes"></div><span class="ec_live_shipping_status_label"><?php _e( 'FEDEX CONNECTED', 'wp-easycart-pro' ); ?></span></div>
		<?php }else{ ?>
		<a class="ec_live_shipping_status_bad" href="admin.php?page=wp-easycart-settings&subpage=shipping-settings"><div class="dashicons-before dashicons-no"></div><span class="ec_live_shipping_status_label"><?php _e( 'FEDEX NOT SETUP', 'wp-easycart-pro' ); ?></span></a>
		<?php }

		////////////////////////////
		// DHL Shipping Check
		////////////////////////////
		if( $status->ec_dhl_shipping_setup( ) ){ ?>
		<div class="ec_live_shipping_status_good"><div class="dashicons-before dashicons-yes"></div><span class="ec_live_shipping_status_label"><?php _e( 'DHL CONNECTED', 'wp-easycart-pro' ); ?></span></div>
		<?php }else{ ?>
		<a class="ec_live_shipping_status_bad" href="admin.php?page=wp-easycart-settings&subpage=shipping-settings"><div class="dashicons-before dashicons-no"></div><span class="ec_live_shipping_status_label"><?php _e( 'DHL NOT SETUP', 'wp-easycart-pro' ); ?></span></a>
		<?php }

		////////////////////////////
		// AUSPOST Shipping Check
		////////////////////////////
		if( $status->ec_auspost_shipping_setup( ) ){ ?>
		<div class="ec_live_shipping_status_good"><div class="dashicons-before dashicons-yes"></div><span class="ec_live_shipping_status_label"><?php _e( 'AUS POST CONNECTED', 'wp-easycart-pro' ); ?></span></div>
		<?php }else{ ?>
		<a class="ec_live_shipping_status_bad" href="admin.php?page=wp-easycart-settings&subpage=shipping-settings"><div class="dashicons-before dashicons-no"></div><span class="ec_live_shipping_status_label"><?php _e( 'AUS POST NOT SETUP', 'wp-easycart-pro' ); ?></span></a>
		<?php } 

		////////////////////////////
		// Canada Post Shipping Check
		////////////////////////////
		if( $status->ec_canadapost_shipping_setup( ) ){ ?>
		<div class="ec_live_shipping_status_good"><div class="dashicons-before dashicons-yes"></div><span class="ec_live_shipping_status_label"><?php _e( 'CA POST CONNECTED', 'wp-easycart-pro' ); ?></span></div>
		<?php }else{ ?>
		<a class="ec_live_shipping_status_bad" href="admin.php?page=wp-easycart-settings&subpage=shipping-settings"><div class="dashicons-before dashicons-no"></div><span class="ec_live_shipping_status_label"><?php _e( 'CA POST NOT SETUP', 'wp-easycart-pro' ); ?></span></a>
		<?php } ?>

	</div>

	<span style="float:left; width:100%;"><?php _e( 'Add Live Shipping Rate', 'wp-easycart-pro' ); ?></span>

	<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
		<span><?php _e( 'Shipping Type', 'wp-easycart-pro' ); ?>: </span>
		<select class="ec_admin_live_label_input" name="ec_admin_new_live_code_type" id="ec_admin_new_live_code_type" onchange="ec_admin_update_new_live_rate_method_display( );">
			<option value="0"><?php _e( 'Select a Shipping Type', 'wp-easycart-pro' ); ?></option>
			<?php foreach( $live_rate_codes as $live_rate_type ){ ?>
			<option value="<?php echo $live_rate_type['type']; ?>"><?php echo $live_rate_type['label']; ?></option>
			<?php }?>
		</select>
	</div>

	<?php foreach( $live_rate_codes as $live_rate_type ){ ?>
	<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new ec_admin_shipping_live_rate_type ec_admin_initial_hide" id="<?php echo $live_rate_type['type']; ?>">
		<span><?php echo $live_rate_type['label'] ?>: </span>
		<select class="ec_admin_live_label_input" name="ec_admin_new_live_code_<?php echo $live_rate_type['type']; ?>" id="ec_admin_new_live_code_<?php echo $live_rate_type['type']; ?>">
			<option value="0"><?php _e( 'Select a Shipping Method', 'wp-easycart-pro' ); ?></option>
			<?php foreach( $live_rate_type['rates'] as $live_rate_code ){ ?>
			<option value="<?php echo $live_rate_code['code']; ?>"><?php echo $live_rate_code['label']; ?></option>
			<?php }?>
		</select>
	</div>
	<?php }?>

	<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
		<span><?php _e( 'Shipping Label', 'wp-easycart-pro' ); ?>: </span><input type="text" class="ec_admin_live_label_input ec_admin_input_no_upper" value="" name="ec_admin_new_live_label" id="ec_admin_new_live_label" />
	</div>

	<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
		<span><?php _e( 'Override Price', 'wp-easycart-pro' ); ?>: </span><input type="number" step=".01" class="ec_admin_live_trigger_rate_input" value="" name="ec_admin_new_live_override_rate" id="ec_admin_new_live_override_rate" />
	</div>

	<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
		<span><?php _e( 'Free Shipping Threshold', 'wp-easycart-pro' ); ?>: </span><input type="number" step=".01" class="ec_admin_live_trigger_rate_input" value="" name="ec_admin_new_live_free_shipping_threshold" id="ec_admin_new_live_free_shipping_threshold" />
	</div>

	<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
		<span><?php _e( 'Shipping Zone', 'wp-easycart-pro' ); ?>:</span>
		<select name="ec_admin_new_live_shipping_zone" id="ec_admin_new_live_shipping_zone">
			<option value="0"><?php _e( 'No Zone', 'wp-easycart-pro' ); ?></option>
			<?php foreach( wp_easycart_admin( )->shipping_zones as $shipping_zone ){ ?>
			<option value="<?php echo $shipping_zone->zone_id; ?>"><?php echo $shipping_zone->zone_name; ?></option>
			<?php }?>
		</select>
	</div>


	<div class="ec_admin_live_shipping_spacer"></div>
	<div class="ec_admin_settings_shipping_input">
		<input type="submit" class="ec_admin_settings_simple_button" value="<?php _e( 'Add New', 'wp-easycart-pro' ); ?>" onclick="return ec_admin_add_new_shipping_live_trigger( );" />
	</div>

	<div class="ec_admin_settings_shipping_divider"></div>

	<div class="ec_admin_tax_spacer"></div>

	<div class="ec_admin_settings_live_rate_heading"><?php _e( 'Your Live Rates', 'wp-easycart-pro' ); ?></div>
	<div class="ec_admin_live_rate_table" id="ec_admin_live_shipping_rates_table" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-live-rate-list' ) ); ?>">
		<?php foreach( $live_triggers as $trigger ){ ?>
		<div class="ec_admin_live_rate_display" id="ec_admin_live_rate_<?php echo $trigger->shippingrate_id; ?>" data-id="<?php echo esc_attr( $trigger->shippingrate_id ); ?>">

			<div class="ec_admin_settings_live_rate_toggle">
				<a href="#" onclick="ec_admin_settings_open_live_rate( '<?php echo $trigger->shippingrate_id; ?>' ); return false;" id="ec_admin_live_rate_toggle_<?php echo $trigger->shippingrate_id; ?>"><div class="dashicons-before dashicons-plus"></div></a>
			</div>

			<div class="ec_admin_settings_shipping_input">
				<input type="submit" class="ec_admin_settings_simple_delete_button" style="text-align:center;" value="Delete" onclick="return ec_admin_delete_live_trigger( '<?php echo $trigger->shippingrate_id; ?>' );" />
			</div>

			<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
				<input style="max-width:100%;" type="text" class="ec_admin_live_label_input ec_admin_live_rate_label_bold ec_admin_input_no_upper" value="<?php echo $trigger->shipping_label; ?>" name="ec_admin_new_live_label_<?php echo $trigger->shippingrate_id; ?>" id="ec_admin_new_live_label_<?php echo $trigger->shippingrate_id; ?>" placeholder="<?php _e( 'Shipping Method Label', 'wp-easycart-pro' ); ?>" />
			</div>

			<div id="ec_admin_live_rate_content_<?php echo $trigger->shippingrate_id; ?>" class="ec_admin_live_rate_content">
				<select style="max-width:100%;" class="ec_admin_live_label_input" name="ec_admin_new_live_code_type_<?php echo $trigger->shippingrate_id; ?>" id="ec_admin_new_live_code_type_<?php echo $trigger->shippingrate_id; ?>" onchange="ec_admin_update_live_rate_method_display( '<?php echo $trigger->shippingrate_id; ?>' );">
					<option value="0"><?php _e( 'Select a Shipping Type', 'wp-easycart-pro' ); ?></option>
					<?php foreach( $live_rate_codes as $live_rate_type ){ ?>
					<option value="<?php echo $live_rate_type['type']; ?>"<?php if( $trigger->{$live_rate_type['type']} ){ ?> selected="selected"<?php }?>><?php echo $live_rate_type['label']; ?></option>
					<?php }?>
				</select>

				<?php foreach( $live_rate_codes as $live_rate_type ){ ?>
				<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new ec_admin_shipping_live_rate_type<?php if( !$trigger->{$live_rate_type['type']} ){ ?>  ec_admin_initial_hide<?php }?>" id="<?php echo $live_rate_type['type']; ?>_<?php echo $trigger->shippingrate_id; ?>">
					<select style="max-width:100%;" class="ec_admin_live_label_input" name="ec_admin_new_live_code_<?php echo $live_rate_type['type']; ?>_<?php echo $trigger->shippingrate_id; ?>" id="ec_admin_new_live_code_<?php echo $live_rate_type['type']; ?>_<?php echo $trigger->shippingrate_id; ?>">
						<option value="0"><?php _e( 'Select a Shipping Method', 'wp-easycart-pro' ); ?></option>
						<?php foreach( $live_rate_type['rates'] as $live_rate_code ){ ?>
						<option value="<?php echo $live_rate_code['code']; ?>"<?php if( $trigger->shipping_code == $live_rate_code['code'] ){?> selected="selected"<?php }?>><?php echo $live_rate_code['label']; ?></option>
						<?php }?>
					</select>
				</div>
				<?php }?>

				<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
					<span><?php _e( 'Override Price', 'wp-easycart-pro' ); ?>:</span> <input style="max-width:100%;" type="number" step=".01" class="ec_admin_live_trigger_rate_input" value="<?php if( $trigger->shipping_override_rate ){ echo ( method_exists( $currency, 'get_number_safe' ) ) ? $currency->get_number_safe( $trigger->shipping_override_rate ) : $trigger->shipping_override_rate; } ?>" name="ec_admin_new_live_override_rate_<?php echo $trigger->shippingrate_id; ?>" id="ec_admin_new_live_override_rate_<?php echo $trigger->shippingrate_id; ?>" />
				</div>

				<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
					<span><?php _e( 'Free Shipping Threshold', 'wp-easycart-pro' ); ?>:</span> <input style="max-width:100%;" type="number" step=".01" class="ec_admin_live_trigger_rate_input" value="<?php if( $trigger->free_shipping_at != -1 ){ echo ( method_exists( $currency, 'get_number_safe' ) ) ? $currency->get_number_safe( $trigger->free_shipping_at ) : $trigger->free_shipping_at; } ?>" name="ec_admin_new_live_free_shipping_threshold_<?php echo $trigger->shippingrate_id; ?>" id="ec_admin_new_live_free_shipping_threshold_<?php echo $trigger->shippingrate_id; ?>" />
				</div>

				<div class="ec_admin_live_shipping_row ec_admin_shipping_live_trigger_row_new">
					<select style="max-width:100%;" class="ec_admin_live_label_input" name="ec_admin_new_live_shipping_zone_<?php echo $trigger->shippingrate_id; ?>" id="ec_admin_new_live_shipping_zone_<?php echo $trigger->shippingrate_id; ?>">
						<option value="0"><?php _e( 'No Zone', 'wp-easycart-pro' ); ?></option>
						<?php foreach( wp_easycart_admin( )->shipping_zones as $shipping_zone ){ ?>
						<option value="<?php echo $shipping_zone->zone_id; ?>"<?php if( $shipping_zone->zone_id == $trigger->zone_id ){ ?> selected="selected"<?php }?>><?php echo $shipping_zone->zone_name; ?></option>
						<?php }?>
					</select>
				</div>
				<input type="submit" class="ec_admin_settings_simple_button" value="<?php _e( 'Save Changes', 'wp-easycart-pro' ); ?>" onclick="return ec_admin_save_shipping_live_triggers( );" />
			</div>
		</div>
		<?php } ?>
	</div>
	<div id="ec_admin_no_live_triggers"<?php if( count( $live_triggers ) > 0 ){ ?> style="display:none;"<?php }?>><?php _e( 'No Live Rates Entered', 'wp-easycart-pro' ); ?></div>

	<div id="insert_new_live_trigger_here"></div>

	<div class="ec_admin_settings_shipping_input">
		<input type="submit" class="ec_admin_settings_simple_button" value="<?php _e( 'Save Triggers', 'wp-easycart-pro' ); ?>" onclick="return ec_admin_save_shipping_live_triggers( );" />
	</div>

</div>