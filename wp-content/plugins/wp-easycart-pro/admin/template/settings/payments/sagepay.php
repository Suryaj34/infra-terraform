<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "sagepay" ){ ?>show<?php }else{?>hide<?php }?>" id="sagepay">
	<span><?php _e( 'Setup Opayo by Elavon (Formerly Sagepay)', 'wp-easycart-pro' ); ?></span>
	<div class="ec_admin_settings_notice"><strong><?php _e( '3D Secure can be enabled in your Opayo account. Once enabled, if the customer uses Visa or MasterCard AND their bank uses the Verified by Visa or MasterCard SecureCode systems, the customer will be redirected to their bank to complete the verification process. If this part fails, order will still be visible in their account and in your EasyCart Admin Console, but will say "Card Denied". If this happens you will need to follow up with your customer to process their payment again. This integration is protocol 4.00 compliant and meets the EU PSD2 RTS on SCA, as long as you have all security settings enabled in your account.', 'wp-easycart-pro' ); ?></strong><br /><br /></div>
	<div>
		<?php _e( 'Vendor', 'wp-easycart-pro' ); ?>
		<input name="ec_option_sagepay_vendor"  id="ec_option_sagepay_vendor" type="text" value="<?php echo get_option('ec_option_sagepay_vendor'); ?>" />
	</div>
	<div>
		<?php _e( 'Currency', 'wp-easycart-pro' ); ?>
		<select name="ec_option_sagepay_currency" id="ec_option_sagepay_currency">
			<option value="AUD" <?php if (get_option('ec_option_sagepay_currency') == "AUD") echo ' selected'; ?>>Australian Dollar</option>
			<option value="CAD" <?php if (get_option('ec_option_sagepay_currency') == "CAD") echo ' selected'; ?>>Canadian Dollar</option>
			<option value="CHF" <?php if (get_option('ec_option_sagepay_currency') == "CHF") echo ' selected'; ?>>Swiss Franc</option>
			<option value="DKK" <?php if (get_option('ec_option_sagepay_currency') == "DKK") echo ' selected'; ?>>Danish Krone</option>
			<option value="EUR" <?php if (get_option('ec_option_sagepay_currency') == "EUR") echo ' selected'; ?>>Euro</option>
			<option value="GBP" <?php if (get_option('ec_option_sagepay_currency') == "GBP") echo ' selected'; ?>>Pound Sterling</option>
			<option value="HKD" <?php if (get_option('ec_option_sagepay_currency') == "HKD") echo ' selected'; ?>>Hong Kong Dollar</option>
			<option value="IDR" <?php if (get_option('ec_option_sagepay_currency') == "IDR") echo ' selected'; ?>>Rupiah</option>
			<option value="JPY" <?php if (get_option('ec_option_sagepay_currency') == "JPY") echo ' selected'; ?>>Yen</option>
			<option value="LUF" <?php if (get_option('ec_option_sagepay_currency') == "LUF") echo ' selected'; ?>>Luxembourg Franc</option>
			<option value="NOK" <?php if (get_option('ec_option_sagepay_currency') == "NOK") echo ' selected'; ?>>Norwegian Krone</option>
			<option value="NZD" <?php if (get_option('ec_option_sagepay_currency') == "NZD") echo ' selected'; ?>>New Zealand Dollar</option>
			<option value="SEK" <?php if (get_option('ec_option_sagepay_currency') == "SEK") echo ' selected'; ?>>Swedish Krona</option>
			<option value="SGD" <?php if (get_option('ec_option_sagepay_currency') == "SGD") echo ' selected'; ?>>Singapore Dollar</option>
			<option value="TRL" <?php if (get_option('ec_option_sagepay_currency') == "TRL") echo ' selected'; ?>>Turkish Lira</option>
			<option value="USD" <?php if (get_option('ec_option_sagepay_currency') == "USD") echo ' selected'; ?>>US Dollar</option>
		</select>
	</div>
	<div>
		<?php _e( 'Simulator Only', 'wp-easycart-pro' ); ?>
		<select name="ec_option_sagepay_simulator" id="ec_option_sagepay_simulator">
			<option value="1" <?php if (get_option('ec_option_sagepay_simulator') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
			<option value="0" <?php if (get_option('ec_option_sagepay_simulator') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
		</select>
	</div>
	<div>
		<?php _e( 'Test Mode', 'wp-easycart-pro' ); ?>
		<select name="ec_option_sagepay_testmode" id="ec_option_sagepay_testmode">
			<option value="1" <?php if (get_option('ec_option_sagepay_testmode') == 1) echo ' selected'; ?>><?php _e( 'Yes', 'wp-easycart-pro' ); ?></option>
			<option value="0" <?php if (get_option('ec_option_sagepay_testmode') == 0) echo ' selected'; ?>><?php _e( 'No', 'wp-easycart-pro' ); ?></option>
		</select>
	</div>
	<div class="ec_admin_settings_input">
		<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_sagepay_options( );" value="<?php _e( 'Save Options', 'wp-easycart-pro' ); ?>" />
	</div>
</div>