<?php
$curr_page = "";
if( isset( $_GET['subpage'] ) ) {
	$curr_page = esc_attr( $_GET['subpage'] );
} else {
	$curr_page = esc_attr( $_GET['page'] );
}
?>
<div class="ec_admin_settings_panel ec_admin_details_panel">

	<div class="ec_admin_important_numbered_list">

		<div class="ec_admin_flex_row">

			<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

				<div class="ec_admin_settings_label">
					<div class="dashicons-before dashicons-lock"></div>
					<span><?php esc_attr_e( 'Your License Has Expired!', 'wp-easycart-pro' ); ?></span>
					<a href="https://www.wpeasycart.com/wordpress-shopping-cart-pricing/" target="_blank" class="ec_help_icon_link">
						<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
					</a>
				</div>

				<div class="ec_admin_upgrade_wrap">

					<div>
						<?php
						$pro_plugin_base = 'wp-easycart-pro/wp-easycart-admin-pro.php';
						$pro_plugin_file = WP_PLUGIN_DIR . '/' . $pro_plugin_base;
						if( file_exists( $pro_plugin_file ) && !is_plugin_active( $pro_plugin_base ) ) {
							echo '<div class="ec_admin_message_error">';
							echo '<p>';
							echo esc_attr( sprintf( __( 'WP EasyCart PRO is installed but NOT ACTIVATED. Please %1$sclick here to activate your WP EasyCart PRO plugin%2$s.', 'wp-easycart-pro' ), '<a href="' . wp_easycart_admin( )->get_pro_activation_link( ) . '">', '</a>' ) );
							echo '</p>';
							echo '</div>';
						} ?>
						<?php $license_data = ec_license_manager( )->ec_get_license( ); ?>
						<?php $license_info = get_option( 'wp_easycart_license_info' ); ?>
						<div class="ec_admin_upgrade_header"><?php if ( $license_data->is_trial ) {
							echo esc_attr( 'You must upgrade your license to use this feature', 'wp-easycart-pro' );
						} else {
							echo esc_attr( 'You must renew your license to use this feature', 'wp-easycart-pro' );
						} ?></div>
						<div class="ec_admin_upgrade_subheader"><?php if( $license_data->is_trial ) {
							echo esc_attr( sprintf( __( 'Your trial ended on %s', 'wp-easycart-pro' ),  date( 'F j, Y', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ) ) );
						} else {
							echo esc_attr( sprintf( __( 'Your license expired on %s', 'wp-easycart-pro' ),  date( 'F j, Y', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ) ) );
						}?>
						<br />
						<?php if( $license_data->is_trial ) {
							esc_attr_e( 'Please upgrade today to continue using the WP EasyCart.', 'wp-easycart-pro' );
						} else {
							esc_attr_e( 'Please renew today to continue using the WP EasyCart.', 'wp-easycart-pro' );
						}?>
						</div>
						<?php if( $license_data->is_trial ){ ?>
						<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'UPGRADE TODAY', 'wp-easycart-pro' ); ?></a></div>
						<?php }else if( $license_data->model_number == 'ec400' ){ ?>
						<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'RENEW LICENSE', 'wp-easycart-pro' ); ?></a></div>
						<?php }else{ ?>
						<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'RENEW LICENSE', 'wp-easycart-pro' ); ?></a></div>
						<?php }?>
						<div class="ec_admin_upgrade_box_line_item"><a href="https://www.wpeasycart.com/wordpress-shopping-cart-pricing/" target="_blank"><?php esc_attr_e( 'You are currently reverted to the FREE edition, learn more about license types here.', 'wp-easycart-pro' ); ?></a></div>
					</div>

					<div class="ec_admin_upgrade_divider" style="margin-bottom:25px;"><div></div></div>

					<div class="ec_admin_upgrade_box_container"<?php if( $license_data->model_number == 'ec410' ){ ?> style="display:block;"<?php }?>>

						<?php if( $license_data->model_number == 'ec400' ){ ?>
						<div class="ec_admin_upgrade_box ec_admin_upgrade_box_most_popular">

							<div class="ec_admin_upgrade_box_line_item">
								<div class="ec_admin_upgrade_box_title"><?php esc_attr_e( 'Professional', 'wp-easycart-pro' ); ?></div>
							</div>

							<div class="ec_admin_upgrade_box_line_item"><img src="<?php echo plugins_url( 'wp-easycart/admin/images/v4-professional-edition.jpg' ); ?>" alt="<?php esc_attr_e( 'Premium Edition', 'wp-easycart-pro' ); ?>" /></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_box_signup_row">
								<?php if( $license_data->is_trial ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=<?php echo $license_info['transaction_key']; ?>&license_type=professional" target="_blank"><?php esc_attr_e( 'UPGRADE TRIAL', 'wp-easycart-pro' ); ?></a>
								<?php }else if( $license_data->model_number == 'ec400' ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'RENEW LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }else{ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'DOWNGRADE LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }?>
							</div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( '30+ Payment Methods', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Sell with PayPal, Square, Intuit, Stripe & More', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'USPS, UPS, FedEx, DHL, Australia Post, & Canada Post', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Unlimited Support Tickets', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Coupons, Promotions, & Gift Cards', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'B2B, Volume & Option Product Pricing', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Categories & Product Groupings', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Sell Downloads, Subscriptions, & Gift Cards', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( '12 Advanced Product Variant Types', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( '8+ Tax Options', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Unlimited Products', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item" style="color:#666;"><?php esc_attr_e( 'No Premium Extensions', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item" style="color:#666;"><?php esc_attr_e( 'No Premium Apps', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item" style="color:#666;"><?php esc_attr_e( 'No QuickBooks', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item" style="color:#666;"><?php esc_attr_e( 'No MailChimp', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item" style="color:#666;"><?php esc_attr_e( 'No ShipStation', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item" style="color:#666;"><?php esc_attr_e( 'No Groupon Importer', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_box_signup_row">
								<?php if( $license_data->is_trial ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=<?php echo $license_info['transaction_key']; ?>&license_type=professional" target="_blank"><?php esc_attr_e( 'UPGRADE TRIAL', 'wp-easycart-pro' ); ?></a>
								<?php }else if( $license_data->model_number == 'ec400' ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'RENEW LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }else{ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'DOWNGRADE LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }?>
							</div>

						</div>
						<?php }?>

						<div class="ec_admin_upgrade_box"<?php if( $license_data->model_number == 'ec410' ){ ?> style="width:100%; margin:0;"<?php }?>>

							<div class="ec_admin_upgrade_box_line_item">
								<div class="ec_admin_upgrade_box_title"><?php esc_attr_e( 'Premium', 'wp-easycart-pro' ); ?></div>
							</div>

							<div class="ec_admin_upgrade_box_line_item"><img src="<?php echo plugins_url( 'wp-easycart/admin/images/v4-premium-edition.jpg' ); ?>" alt="<?php esc_attr_e( 'Premium Edition', 'wp-easycart-pro' ); ?>" /></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_box_signup_row">
								<?php if( $license_data->is_trial ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=<?php echo $license_info['transaction_key']; ?>&license_type=premium" target="_blank"><?php esc_attr_e( 'UPGRADE TRIAL', 'wp-easycart-pro' ); ?></a>
								<?php }else if( $license_data->model_number == 'ec400' ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'UPGRADE LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }else{ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'RENEW LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }?>
							</div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( '30+ Payment Methods', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Sell with PayPal, Square, Intuit, Stripe & More', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'USPS, UPS, FedEx, DHL, Australia Post, & Canada Post', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Unlimited Support Tickets', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Coupons, Promotions, & Gift Cards', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'B2B, Volume & Option Product Pricing', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Categories & Product Groupings', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Sell Downloads, Subscriptions, & Gift Cards', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( '12 Advanced Product Variant Types', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( '8+ Tax Options', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item"><?php esc_attr_e( 'Unlimited Products', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_special_line_item"><?php esc_attr_e( '10 Premium Extensions', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_special_line_item"><?php esc_attr_e( '3 Premium Apps', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_special_line_item"><?php esc_attr_e( 'QuickBooks for Desktop', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_special_line_item"><?php esc_attr_e( 'MailChimp e-commerce API 3.0', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_special_line_item"><?php esc_attr_e( 'Full ShipStation Integration', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_special_line_item"><?php esc_attr_e( 'Groupon Importer', 'wp-easycart-pro' ); ?></div>

							<div class="ec_admin_upgrade_box_line_item ec_admin_upgrade_box_signup_row">
								<?php if( $license_data->is_trial ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=<?php echo $license_info['transaction_key']; ?>&license_type=premium" target="_blank"><?php esc_attr_e( 'UPGRADE TRIAL', 'wp-easycart-pro' ); ?></a>
								<?php }else if( $license_data->model_number == 'ec400' ){ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'UPGRADE LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }else{ ?>
								<a href="https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=<?php echo $license_info['transaction_key']; ?>" target="_blank"><?php esc_attr_e( 'RENEW LICENSE', 'wp-easycart-pro' ); ?></a>
								<?php }?>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>