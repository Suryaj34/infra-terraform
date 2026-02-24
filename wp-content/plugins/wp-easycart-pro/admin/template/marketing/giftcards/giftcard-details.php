<form action="<?php echo $this->action; ?>"  method="POST">
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
	<input type="hidden" name="original_id" value="<?php echo $this->giftcard->giftcard_id; ?>" />
	<input type="hidden" name="giftcard_id" value="<?php echo $this->giftcard->giftcard_id; ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-products"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){  _e( 'ADD NEW GIFT CARD', 'wp-easycart-pro' ); }else{ _e( 'EDIT GIFT CARD', 'wp-easycart-pro' ); } ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo $this->docs_link; ?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'marketing', 'gift-cards', 'details' );?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<?php if( $this->order_id ){ ?>
							<a href="admin.php?page=wp-easycart-orders&subpage=orders&order_id=<?php echo $this->order_id; ?>&ec_admin_form_action=edit" class="ec_page_title_button"><?php esc_attr_e( 'View Order', 'wp-easycart-pro' ); ?></a>
							<?php }?>
							<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'Gift Card Setup', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'A Gift Card is a unique 12 digit code that your customer may use at checkout and redeem. They are essentially a cash card, and you may send this Gift Card ID to the customer either by mail or by email.', 'wp-easycart-pro' ); ?></p></div>
						<?php do_action( 'wp_easycart_admin_giftcard_details_basic_fields' ); ?>
					</div>
				</div>
			</div>
			<div class="ec_admin_details_footer">
				<div class="ec_page_title_button_wrap">
					<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart-pro' ); ?></a>
					<?php if( $this->order_id ){ ?>
					<a href="admin.php?page=wp-easycart-orders&subpage=orders&order_id=<?php echo $this->order_id; ?>&ec_admin_form_action=edit" class="ec_page_title_button"><?php esc_attr_e( 'View Order', 'wp-easycart-pro' ); ?></a>
					<?php }?>
					<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
				</div>
			</div>
		</div>
	</div>
</form>