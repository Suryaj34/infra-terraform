<form action="<?php echo $this->action; ?>"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form" novalidate="novalidate" enctype="multipart/form-data">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-fee-details' ); ?>
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
	<input type="hidden" name="fee_id" value="<?php echo $this->fee->fee_id; ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-products"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){ _e( 'ADD NEW', 'wp-easycart-pro' ); }else{ _e( 'EDIT', 'wp-easycart-pro' ); } ?> <?php _e( 'FLEX-FEE', 'wp-easycart-pro' ); ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'fees', 'details' );?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'manage-fees', 'details' );?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php _e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<input type="submit" value="<?php _e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php _e( 'Flex-Fee Setup', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php _e( 'Flex-fees were built to do just about anything at the cart total level. You may add a price amount, a rate amount, min and max amounts, and apply to all sorts of criteria. This system can even be used to provide discounts by product category and more!', 'wp-easycart-pro' ); ?></p></div>
						<?php do_action( 'wp_easycart_admin_fee_details_basic_fields' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>