<div class="ec_admin_other_live_gateway_row">

	<div class="ec_admin_slider_row">

		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_live_gateway_display_loader" ); ?>

		<?php do_action( 'wp_easycart_admin_live_gateway_post_stripe' ); ?>

		<h3>
			<?php _e( 'Other', 'wp-easycart-pro' ); ?>
			<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'live' );?>" target="_blank" class="ec_help_icon_link" title="<?php _e( 'View Help?', 'wp-easycart-pro' ); ?>" style="float:left; margin-left:0px;">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
			</a>
		</h3>

		<div class="ec_admin_slider_row_description">
			<div><?php _e( 'There are 25 other options to choose from to accept credit cards on your site. Use this box to select another gateway, keep in mind you can only use this in place of Stripe or Square and can choose PayPal or another third party gateway with this feature.', 'wp-easycart-pro' ); ?></div>

			<div class="ec_admin_live_gateway_select" style="text-align:left; margin-top:20px;">
				<select id="ec_option_payment_process_method" name="ec_option_payment_process_method" onchange="toggle_live_gateways( );<?php do_action( 'wp_easycart_pro_add_live_save' ); ?>" style="width:250px;">
					<option value="0" <?php if( get_option( 'ec_option_payment_process_method') == "0" ){ echo " selected"; } ?>><?php _e( 'No Live Payment Processor', 'wp-easycart-pro' ); ?></option>
					<?php do_action( 'wpeasycart_admin_load_live_gateway_select_options' ); ?>
				</select>
			</div>

			<?php do_action( 'wpeasycart_admin_load_live_gateway_settings' ); ?>

			<div class="ec_admin_settings_input<?php if( get_option( 'ec_option_payment_process_method' ) != '0' && get_option( 'ec_option_payment_process_method' ) != 'custom' ){ ?> ec_admin_initial_hide<?php }?>" id="ec_admin_live_gateway_none">
				<?php /*<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_live_gateway_selection( );" value="<?php _e( 'Save Options" />*/ ?>
			</div>

		</div>

	</div>

</div>