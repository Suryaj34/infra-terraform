<form action="<?php echo $this->action; ?>"  method="POST">
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
	<input type="hidden" name="download_id" value="<?php echo $this->download->download_id; ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-download"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){
							esc_attr_e( 'ADD NEW DOWNLOAD', 'wp-easycart-pro' );
						}else{
							esc_attr_e( 'EDIT DOWNLOAD', 'wp-easycart-pro' );
						} ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo $this->docs_link; ?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'orders', 'manage-downloads', 'details' );?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<?php do_action( 'wp_easycart_admin_downloads_details_basic_fields' ); ?>
					</div>
				</div>
			</div>
			<div class="ec_admin_details_footer">
				<div class="ec_page_title_button_wrap">
					<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart-pro' ); ?></a>
					<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
				</div>
			</div>  
		</div>
	</div>
</form>