<form action="<?php echo $this->action; ?>"  method="POST">
	<input type="hidden" name="ec_admin_form_action" value="<?php echo $this->form_action; ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-tag"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){ _e( 'ADD NEW PROMOTION', 'wp-easycart-pro' ); }else{ _e( 'EDIT PROMOTION', 'wp-easycart-pro' ); } ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo $this->docs_link; ?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
							</a>
							<?php echo wp_easycart_admin( )->helpsystem->print_vids_url('marketing', 'promotions', 'details');?>
							<a href="<?php echo $this->action; ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart-pro' ); ?></a>
							<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart-pro' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'STEP ONE', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'A promotion is a discount that applies across products or categories of products and automatically applies without any user coupon or interaction needed.  All customers will receive this promotion while it is active and running on your website.  Simply give this promotion a descriptive name and starting/ending dates.  Customers will see the promotion headline above each product details when shopping.  You may also design and create your own banners or other advertising material to promote the event.', 'wp-easycart-pro' ); ?></p></div>
						<?php do_action( 'wp_easycart_admin_promotion_details_stepone_fields' ); ?><br /><hr />
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'STEP TWO', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'Select a promotion type, such as a price or percentage off a group of products OR when a dollar value is reached.  You can also offer a shipping discount when a certain dollar value is reached.', 'wp-easycart-pro' ); ?></p></div>
						<?php do_action( 'wp_easycart_admin_promotion_details_steptwo_fields' ); ?><br /><hr />
						<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'STEP THREE', 'wp-easycart-pro' ); ?><br></div>
						<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'Depending on your selection on step two, you can choose which group of products to apply the discount to, or the set price threshold when the promotion will become active.', 'wp-easycart-pro' ); ?></p></div>
						<?php do_action( 'wp_easycart_admin_promotion_details_stepthree_fields' ); ?><br /><hr />
						<div id="wpeasycart_promotions_step_4">
							<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'STEP FOUR', 'wp-easycart-pro' ); ?><br></div>
							<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'The final step is to create a price discount or percentage discount that the customer will receive.  These promotion discounts are on top of any listed price cuts you have at the product level. For example, if you display a $100 dollar product that is regularly $120, then run a 20&percnt; discount, it will show a slashed out price of $120 with a promotion price of $80. The discount will come off the actual listed price of the item, even if you have a previous price discount at the product level.', 'wp-easycart-pro' ); ?></p></div>
							<?php do_action( 'wp_easycart_admin_promotion_details_stepfour_fields' ); ?><br />
						</div>
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