<form action="<?php echo $this->action; ?>"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form" novalidate="novalidate" enctype="multipart/form-data">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-location-details' ); ?>
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
	<input type="hidden" name="location_id" value="<?php echo $this->location->location_id; ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-products"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){ _e( 'ADD NEW', 'wp-easycart-pro' ); }else{ _e( 'EDIT', 'wp-easycart-pro' ); } ?> <?php _e( 'location', 'wp-easycart-pro' ); ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'locations', 'details' );?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'manage-locations', 'details' );?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php _e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<input type="submit" value="<?php _e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php _e( 'Location Setup', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php _e( 'Locations allow you to create multiple pickup options for your customers on checkout.', 'wp-easycart-pro' ); ?></p></div>
						<?php do_action( 'wp_easycart_admin_location_details_basic_fields' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>