<h1 class="easycart-wp-heading-inline"><?php esc_attr_e( 'Amazon Pay', 'wp-easycart-pro' ); ?></h1>

<div class="ec_admin_amazonpay_row">
	<div class="ec_admin_slider_row">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_amazonpay_display_loader" ); ?>
		<h3>
			<span style="float:left; width:100%;"><?php esc_attr_e( 'Amazon Pay', 'wp-easycart-pro' ); ?></span>
			<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'amazonpay' );?>" target="_blank" class="ec_help_icon_link" title="<?php _e( 'View Help?', 'wp-easycart-pro' ); ?>" style="float:left; margin-left:0px;">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart-pro' ); ?>
			</a>
		</h3>
		<div class="ec_admin_slider_row_description">
			<div><?php esc_attr_e( 'Enable and setup Amazon Pay to allow customers to checkout on your site using their Amazon account, addresses, and payment methods.', 'wp-easycart-pro' ); ?></div>
			<div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section" style="display:<?php echo ( get_option( 'ec_option_amazonpay_enable' ) ) ? 'block' : 'none'; ?>;" id="wpeasycart_amazonpay_settings">
				<div>
					<?php esc_attr_e( 'Store ID', 'wp-easycart-pro' ); ?>
					<input name="ec_option_amazonpay_store_id" id="ec_option_amazonpay_store_id" type="text" value="<?php echo get_option( 'ec_option_amazonpay_store_id' ); ?>" />
				</div>
				<div>
					<?php esc_attr_e( 'Merchant ID', 'wp-easycart-pro' ); ?>
					<input name="ec_option_amazonpay_merchant_id" id="ec_option_amazonpay_merchant_id" type="text" value="<?php echo get_option( 'ec_option_amazonpay_merchant_id' ); ?>" />
				</div>
				<div>
					<?php esc_attr_e( 'Public Key', 'wp-easycart-pro' ); ?>
					<input name="ec_option_amazonpay_public_key" id="ec_option_amazonpay_public_key" type="text" value="<?php echo get_option( 'ec_option_amazonpay_public_key' ); ?>" />
				</div>
				<div>
					<?php esc_attr_e( 'Private Key (PEM File Downloaded on Setup)', 'wp-easycart-pro' ); ?>
					<textarea name="ec_option_amazonpay_private_key" id="ec_option_amazonpay_private_key" style="float:left; width:100%; min-height:150px;"><?php echo get_option( 'ec_option_amazonpay_private_key' ); ?></textarea>
				</div>
				<div>
					<?php esc_attr_e( 'Region', 'wp-easycart-pro' ); ?>
					<select name="ec_option_amazonpay_region" id="ec_option_amazonpay_region">
						<option value="US" <?php if ( get_option( 'ec_option_amazonpay_region') == "US" ){ echo " selected=\"selected\""; } ?>><?php _e( 'US', 'wp-easycart-pro' ); ?></option>
						<option value="EU" <?php if ( get_option( 'ec_option_amazonpay_region') == "EU" ){ echo " selected=\"selected\""; } ?>><?php _e( 'EU/UK', 'wp-easycart-pro' ); ?></option>
						<option value="JP" <?php if ( get_option( 'ec_option_amazonpay_region') == "JP" ){ echo " selected=\"selected\""; } ?>><?php _e( 'JP', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>
				<div>
					<?php esc_attr_e( 'Currency Code', 'wp-easycart-pro' ); ?>
					<select name="ec_option_amazonpay_currency" id="ec_option_amazonpay_currency">
						<option value="USD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "USD" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'USD', 'wp-easycart-pro' ); ?></option>
						<option value="EUR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "EUR" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'EUR', 'wp-easycart-pro' ); ?></option>
						<option value="GBP" <?php if ( get_option( 'ec_option_amazonpay_currency') == "GBP" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'GBP', 'wp-easycart-pro' ); ?></option>
						<option value="JPY" <?php if ( get_option( 'ec_option_amazonpay_currency') == "JPY" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'JPY', 'wp-easycart-pro' ); ?></option>
						<?php /*
						<option value="AED" <?php if ( get_option( 'ec_option_amazonpay_currency') == "AED" ){ echo " selected=\"selected\""; } ?>><?php _e( 'AED', 'wp-easycart-pro' ); ?></option>
						<option value="ALL" <?php if ( get_option( 'ec_option_amazonpay_currency') == "ALL" ){ echo " selected=\"selected\""; } ?>><?php _e( 'ALL', 'wp-easycart-pro' ); ?></option>
						<option value="ARS" <?php if ( get_option( 'ec_option_amazonpay_currency') == "ARS" ){ echo " selected=\"selected\""; } ?>><?php _e( 'ARS', 'wp-easycart-pro' ); ?></option>
						<option value="AUD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "AUD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'AUD', 'wp-easycart-pro' ); ?></option>
						<option value="BDT" <?php if ( get_option( 'ec_option_amazonpay_currency') == "BDT" ){ echo " selected=\"selected\""; } ?>><?php _e( 'BDT', 'wp-easycart-pro' ); ?></option>
						<option value="BRL" <?php if ( get_option( 'ec_option_amazonpay_currency') == "BRL" ){ echo " selected=\"selected\""; } ?>><?php _e( 'BRL', 'wp-easycart-pro' ); ?></option>
						<option value="BGN" <?php if ( get_option( 'ec_option_amazonpay_currency') == "BGN" ){ echo " selected=\"selected\""; } ?>><?php _e( 'BGN', 'wp-easycart-pro' ); ?></option>
						<option value="CAD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "CAD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'CAD', 'wp-easycart-pro' ); ?></option>
						<option value="CHF" <?php if ( get_option( 'ec_option_amazonpay_currency') == "CHF" ){ echo " selected=\"selected\""; } ?>><?php _e( 'CHF', 'wp-easycart-pro' ); ?></option>
						<option value="CNY" <?php if ( get_option( 'ec_option_amazonpay_currency') == "CNY" ){ echo " selected=\"selected\""; } ?>><?php _e( 'CNY', 'wp-easycart-pro' ); ?></option>
						<option value="COP" <?php if ( get_option( 'ec_option_amazonpay_currency') == "COP" ){ echo " selected=\"selected\""; } ?>><?php _e( 'COP', 'wp-easycart-pro' ); ?></option>
						<option value="CZK" <?php if ( get_option( 'ec_option_amazonpay_currency') == "CZK" ){ echo " selected=\"selected\""; } ?>><?php _e( 'CZK', 'wp-easycart-pro' ); ?></option>
						<option value="DKK" <?php if ( get_option( 'ec_option_amazonpay_currency') == "DKK" ){ echo " selected=\"selected\""; } ?>><?php _e( 'DKK', 'wp-easycart-pro' ); ?></option>
						<option value="HKD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "HKD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'HKD', 'wp-easycart-pro' ); ?></option>
						<option value="HRK" <?php if ( get_option( 'ec_option_amazonpay_currency') == "HRK" ){ echo " selected=\"selected\""; } ?>><?php _e( 'HRK', 'wp-easycart-pro' ); ?></option>
						<option value="HUF" <?php if ( get_option( 'ec_option_amazonpay_currency') == "HUF" ){ echo " selected=\"selected\""; } ?>><?php _e( 'HUF', 'wp-easycart-pro' ); ?></option>
						<option value="IDR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "IDR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'IDR', 'wp-easycart-pro' ); ?></option>
						<option value="ILS" <?php if ( get_option( 'ec_option_amazonpay_currency') == "ILS" ){ echo " selected=\"selected\""; } ?>><?php _e( 'ILS', 'wp-easycart-pro' ); ?></option>
						<option value="INR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "INR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'INR', 'wp-easycart-pro' ); ?></option>
						<option value="JOD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "JOD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'JOD', 'wp-easycart-pro' ); ?></option>
						<option value="KHR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "KHR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'KHR', 'wp-easycart-pro' ); ?></option>
						<option value="KRW" <?php if ( get_option( 'ec_option_amazonpay_currency') == "KRW" ){ echo " selected=\"selected\""; } ?>><?php _e( 'KRW', 'wp-easycart-pro' ); ?></option>
						<option value="LAK" <?php if ( get_option( 'ec_option_amazonpay_currency') == "LAK" ){ echo " selected=\"selected\""; } ?>><?php _e( 'LAK', 'wp-easycart-pro' ); ?></option>
						<option value="LKR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "LKR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'LKR', 'wp-easycart-pro' ); ?></option>
						<option value="MAD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "MAD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'MAD', 'wp-easycart-pro' ); ?></option>
						<option value="MXN" <?php if ( get_option( 'ec_option_amazonpay_currency') == "MXN" ){ echo " selected=\"selected\""; } ?>><?php _e( 'MXN', 'wp-easycart-pro' ); ?></option>
						<option value="MYR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "MYR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'MYR', 'wp-easycart-pro' ); ?></option>
						<option value="NGN" <?php if ( get_option( 'ec_option_amazonpay_currency') == "NGN" ){ echo " selected=\"selected\""; } ?>><?php _e( 'NGN', 'wp-easycart-pro' ); ?></option>
						<option value="NOK" <?php if ( get_option( 'ec_option_amazonpay_currency') == "NOK" ){ echo " selected=\"selected\""; } ?>><?php _e( 'NOK', 'wp-easycart-pro' ); ?></option>
						<option value="NPR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "NPR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'NPR', 'wp-easycart-pro' ); ?></option>
						<option value="NZD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "NZD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'NZD', 'wp-easycart-pro' ); ?></option>
						<option value="PHP" <?php if ( get_option( 'ec_option_amazonpay_currency') == "PHP" ){ echo " selected=\"selected\""; } ?>><?php _e( 'PHP', 'wp-easycart-pro' ); ?></option>
						<option value="PKR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "PKR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'PKR', 'wp-easycart-pro' ); ?></option>
						<option value="PLN" <?php if ( get_option( 'ec_option_amazonpay_currency') == "PLN" ){ echo " selected=\"selected\""; } ?>><?php _e( 'PLN', 'wp-easycart-pro' ); ?></option>
						<option value="RON" <?php if ( get_option( 'ec_option_amazonpay_currency') == "RON" ){ echo " selected=\"selected\""; } ?>><?php _e( 'RON', 'wp-easycart-pro' ); ?></option>
						<option value="RUB" <?php if ( get_option( 'ec_option_amazonpay_currency') == "RUB" ){ echo " selected=\"selected\""; } ?>><?php _e( 'RUB', 'wp-easycart-pro' ); ?></option>
						<option value="SEK" <?php if ( get_option( 'ec_option_amazonpay_currency') == "SEK" ){ echo " selected=\"selected\""; } ?>><?php _e( 'SEK', 'wp-easycart-pro' ); ?></option>
						<option value="SGD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "SGD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'SGD', 'wp-easycart-pro' ); ?></option>
						<option value="THB" <?php if ( get_option( 'ec_option_amazonpay_currency') == "THB" ){ echo " selected=\"selected\""; } ?>><?php _e( 'THB', 'wp-easycart-pro' ); ?></option>
						<option value="TWD" <?php if ( get_option( 'ec_option_amazonpay_currency') == "TWD" ){ echo " selected=\"selected\""; } ?>><?php _e( 'TWD', 'wp-easycart-pro' ); ?></option>
						<option value="VND" <?php if ( get_option( 'ec_option_amazonpay_currency') == "VND" ){ echo " selected=\"selected\""; } ?>><?php _e( 'VND', 'wp-easycart-pro' ); ?></option>
						<option value="ZAR" <?php if ( get_option( 'ec_option_amazonpay_currency') == "ZAR" ){ echo " selected=\"selected\""; } ?>><?php _e( 'ZAR', 'wp-easycart-pro' ); ?></option>
						*/ ?>
					</select>
				</div>
				<div>
					<?php esc_attr_e( 'Amazon Checkout Language', 'wp-easycart-pro' ); ?>
					<select name="ec_option_amazonpay_language" id="ec_option_amazonpay_language">
						<option value="en_US" <?php if ( get_option( 'ec_option_amazonpay_language') == "en_US" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'US English', 'wp-easycart-pro' ); ?></option>
						<option value="en_GB" <?php if ( get_option( 'ec_option_amazonpay_language') == "en_GB" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'UK English', 'wp-easycart-pro' ); ?></option>
						<option value="de_DE" <?php if ( get_option( 'ec_option_amazonpay_language') == "de_DE" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'German', 'wp-easycart-pro' ); ?></option>
						<option value="fr_FR" <?php if ( get_option( 'ec_option_amazonpay_language') == "fr_FR" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'French', 'wp-easycart-pro' ); ?></option>
						<option value="it_IT" <?php if ( get_option( 'ec_option_amazonpay_language') == "it_IT" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'Italian', 'wp-easycart-pro' ); ?></option>
						<option value="es_ES" <?php if ( get_option( 'ec_option_amazonpay_language') == "es_ES" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'Spanish', 'wp-easycart-pro' ); ?></option>
						<option value="ja_JP" <?php if ( get_option( 'ec_option_amazonpay_language') == "ja_JP" ){ echo " selected=\"selected\""; } ?>><?php esc_attr_e( 'Japanese', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>
				<div>
					<?php esc_attr_e( 'Live / Sandbox Mode', 'wp-easycart-pro' ); ?>
					<select name="ec_option_amazonpay_is_sandbox" id="ec_option_amazonpay_is_sandbox">
						<option value="1"<?php echo ( get_option('ec_option_amazonpay_is_sandbox' ) == 1 ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Sandbox Mode', 'wp-easycart-pro' ); ?></option>
						<option value="0"<?php echo ( get_option('ec_option_amazonpay_is_sandbox' ) == 0 ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Production Mode', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>
				<div>
					<?php esc_attr_e( 'Pay Button Color Scheme', 'wp-easycart-pro' ); ?>
					<select name="ec_option_amazonpay_pay_button_color" id="ec_option_amazonpay_pay_button_color">
						<option value="Gold"<?php echo ( get_option('ec_option_amazonpay_pay_button_color' ) == 'Gold' ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Gold', 'wp-easycart-pro' ); ?></option>
						<option value="LightGray"<?php echo ( get_option('ec_option_amazonpay_pay_button_color' ) == 'LightGray' ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Light Gray', 'wp-easycart-pro' ); ?></option>
						<option value="DarkGray"<?php echo ( get_option('ec_option_amazonpay_pay_button_color' ) == 'DarkGray' ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Dark Gray', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>
				<div>
					<?php esc_attr_e( 'Pay Button Locations', 'wp-easycart-pro' ); ?>
					<select name="ec_option_amazonpay_hide_early_buttons" id="ec_option_amazonpay_hide_early_buttons">
						<option value="0"<?php echo ( ! get_option('ec_option_amazonpay_hide_early_buttons' ) ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Offer AmazonPay Before Payment', 'wp-easycart-pro' ); ?></option>
						<option value="1"<?php echo ( get_option('ec_option_amazonpay_hide_early_buttons' ) ) ? ' selected' : ''; ?>><?php esc_attr_e( 'Hide AmazonPay Until Payment Page', 'wp-easycart-pro' ); ?></option>
					</select>
				</div>
				<div class="ec_admin_settings_input">
					<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_amazonpay_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart-pro' ); ?>" />
				</div>
			</div>
		</div>
		<div class="ec_admin_toggles_wrap">
			<div class="ec_admin_toggle">
				<span><?php esc_attr_e( 'Enable', 'wp-easycart-pro' ); ?>:</span>
				<label class="ec_admin_switch">
					<input type="checkbox" onclick="ec_admin_save_amazonpay_options();" class="ec_admin_slider_checkbox" value="1" id="ec_option_amazonpay_enable"<?php echo ( get_option( 'ec_option_amazonpay_enable' ) ) ? ' checked="checked"' : ''; ?>>
					<span class="ec_admin_slider round"></span>
				</label>
			</div>
		</div>
	</div>
</div>