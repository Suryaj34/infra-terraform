<div class="ec_admin_other_third_party_row">

	<div class="ec_admin_slider_row">

		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_third_party_display_loader" ); ?>

		<h3>
			<?php _e( 'Other', 'wp-easycart-pro' ); ?>
			<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'thirdparty' );?>" target="_blank" class="ec_help_icon_link" title="<?php _e( 'View Help?', 'wp-easycart-pro' ); ?>" style="float:left; margin-left:0px;">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
			</a>
		</h3>

		<div class="ec_admin_slider_row_description">

			<div><?php _e( 'There are 10 other third party gateways to accept credit cards on an external site. These types of payments jump your customers out to the payment provider to complete their payment and can be used in place of PayPal.', 'wp-easycart-pro' ); ?></div>

			<div style="text-align:left; margin-top:20px;">
				<select name="ec_option_payment_third_party" id="ec_option_payment_third_party" onchange="toggle_third_party( );">
					<option value="0" <?php if( get_option( 'ec_option_payment_third_party' ) == 0 ){ echo ' selected'; } ?>><?php _e( 'No Third Party Processor', 'wp-easycart-pro' ); ?></option>
					<?php do_action( 'wpeasycart_admin_load_third_party_select_options' ); ?>
				</select>
			</div>

			<?php do_action( 'wpeasycart_admin_load_third_party_settings' ); ?>

			<div class="ec_admin_settings_input<?php if( get_option( 'ec_option_payment_third_party' ) != '0' && get_option( 'ec_option_payment_third_party' ) != 'custom_thirdparty' ){ ?> ec_admin_initial_hide<?php }?>" id="ec_admin_third_party_none" style="padding:10px 0;">
				<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_third_party_selection( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
			</div>
		</div>

	</div>

</div>