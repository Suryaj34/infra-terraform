<form action="<?php echo $this->action; ?>"  method="POST">
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />
	<input type="hidden" name="original_id" value="<?php echo $this->coupon->promocode_id; ?>" />
	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-feedback"></div>
						<span><?php echo ( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ) ? __( 'ADD NEW COUPON', 'wp-easycart-pro' ) : __( 'EDIT COUPON', 'wp-easycart-pro' ); ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo $this->docs_link; ?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url( 'marketing', 'coupons', 'details' ); ?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>
					<?php 
					$plugin_file = WP_PLUGIN_DIR . '/wp-easycart/wpeasycart.php';
					if( file_exists( $plugin_file ) ){
						$plugin_info = get_plugin_data( $plugin_file );
						if( version_compare( $plugin_info['Version'], '4.2.9' ) < 0 ){
					?>
						<div class="error" style="clear:both; padding:20px;"><?php esc_attr_e( 'You must upgrade the WP EasyCart main plugin to be able to add or update coupons.', 'wp-easycart-pro' ); ?></div>
					<?php 
						}
					}?>
					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title" style="margin-top:25px;"><?php esc_attr_e( 'STEP ONE', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><?php esc_attr_e( 'A coupon is redeemed when the unique coupon code is entered by the customer during checkout.  They are not applied automatically.  You may attach a coupon to all products, or selected products as well as have the coupon do various activities such as adjusting price or percentage of the shopping cart.  You may view how many times the coupon is redeemed within this section.', 'wp-easycart-pro' ); ?></div>
						<?php do_action( 'wp_easycart_admin_coupon_details_stepone_fields' ); ?><br /><hr />
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title" style="margin-top:25px;"><?php esc_attr_e( 'STEP TWO', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><?php esc_attr_e( 'Select which type of coupon you would like to create.  Dollar, Percentage or Shipping based coupons will discount the shopping cart based on your selection.  To offer free shipping, leave it at 0.00.  Free Item coupons will tag the order with the coupon code so you can decide how to handle anyone using that code, but does not adjust any pricing.', 'wp-easycart-pro' ); ?></div>
						<?php do_action( 'wp_easycart_admin_coupon_details_steptwo_fields' ); ?><br /><hr />
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title" style="margin-top:25px;"><?php esc_attr_e( 'STEP THREE', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><?php esc_attr_e( 'Select which items to apply this coupon code to.  If there is a match in the shopping cart, then the coupon discount will be applied.  You may choose to create a custom category of products OR you can select by manufacturer or single products.', 'wp-easycart-pro' ); ?></div>
						<?php do_action( 'wp_easycart_admin_coupon_details_stepthree_fields' ); ?><br /><hr />
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title" style="margin-top:25px;"><?php esc_attr_e( 'STEP FOUR', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><?php esc_attr_e( 'Choose optional restrictions for the coupon, including minimum purchased to trigger the discount.', 'wp-easycart-pro' ); ?></div>
						<?php do_action( 'wp_easycart_admin_coupon_details_stepfour_fields' ); ?><br />
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
