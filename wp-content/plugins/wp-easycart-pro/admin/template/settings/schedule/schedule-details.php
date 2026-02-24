<form action="<?php echo $this->action; ?>"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form" novalidate="novalidate" enctype="multipart/form-data">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-schedule-details' ); ?>
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
	<input type="hidden" name="schedule_id" value="<?php echo $this->schedule->schedule_id; ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-products"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){ _e( 'ADD NEW', 'wp-easycart-pro' ); }else{ _e( 'EDIT', 'wp-easycart-pro' ); } ?> <?php _e( 'Schedule', 'wp-easycart-pro' ); ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'schedules', 'details' );?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'manage-schedules', 'details' );?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php _e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<input type="submit" value="<?php _e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php _e( 'Schedule Setup', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php _e( 'Schedules allow you to enable and disable orders for preorder and/or restaurant style products. Setup a typical Sunday to Saturday schedule for your store, plus add holiday schedules to prevent orders on special days. Your schedule is in your WordPress timezone settings.', 'wp-easycart-pro' ); ?> <strong>(<?php echo esc_attr( wp_timezone_string() ); ?>)</strong></p></div>
						<?php do_action( 'wp_easycart_admin_schedule_details_basic_fields' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>