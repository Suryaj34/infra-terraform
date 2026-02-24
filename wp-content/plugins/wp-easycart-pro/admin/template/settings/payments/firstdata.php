<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_<?php if( get_option('ec_option_payment_process_method') == "firstdata" ){ ?>show<?php }else{?>hide<?php }?>" id="firstdata">
	<span><?php esc_attr( 'Setup First Data PayEezy (E4)', 'wp-easycart-pro' ); ?></span>
	<div>
		<?php esc_attr_e( 'Gateway ID', 'wp-easycart-pro' ); ?>
		<input name="ec_option_firstdatae4_exact_id"  id="ec_option_firstdatae4_exact_id" type="text" value="<?php echo get_option('ec_option_firstdatae4_exact_id'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Gateway Password', 'wp-easycart-pro' ); ?>
		<input name="ec_option_firstdatae4_password"  id="ec_option_firstdatae4_password" type="text" value="<?php echo get_option('ec_option_firstdatae4_password'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Key ID', 'wp-easycart-pro' ); ?>
		<input name="ec_option_firstdatae4_key_id"  id="ec_option_firstdatae4_key_id" type="text" value="<?php echo get_option('ec_option_firstdatae4_key_id'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Hmac Key', 'wp-easycart-pro' ); ?>
		<input name="ec_option_firstdatae4_key"  id="ec_option_firstdatae4_key" type="text" value="<?php echo get_option('ec_option_firstdatae4_key'); ?>" />
	</div>
	<div>
		<?php esc_attr_e( 'Language', 'wp-easycart-pro' ); ?>
		<select name="ec_option_firstdatae4_language" id="ec_option_firstdatae4_language">
			<option value="EN" <?php if (get_option('ec_option_firstdatae4_language') == "EN") echo ' selected'; ?>>EN</option>
			<option value="FR" <?php if (get_option('ec_option_firstdatae4_language') == "FR") echo ' selected'; ?>>FR</option>
			<option value="ES" <?php if (get_option('ec_option_firstdatae4_language') == "ES") echo ' selected'; ?>>ES</option>
		</select>
	</div>
	<div>
		<?php esc_attr_e( 'Currency', 'wp-easycart-pro' ); ?>
		<select name="ec_option_firstdatae4_currency" id="ec_option_firstdatae4_currency">
			<option value="USD" <?php if (get_option('ec_option_firstdatae4_currency') == "USD") echo ' selected'; ?>><?php esc_attr_e( 'U.S. Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="CAD" <?php if (get_option('ec_option_firstdatae4_currency') == "CAD") echo ' selected'; ?>><?php esc_attr_e( 'Canadian Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="DEM" <?php if (get_option('ec_option_firstdatae4_currency') == "DEM") echo ' selected'; ?>><?php esc_attr_e( 'German Mark', 'wp-easycart-pro' ); ?></option>
			<option value="CHF" <?php if (get_option('ec_option_firstdatae4_currency') == "CHF") echo ' selected'; ?>><?php esc_attr_e( 'Swiss Franc', 'wp-easycart-pro' ); ?></option>
			<option value="GBP" <?php if (get_option('ec_option_firstdatae4_currency') == "GBP") echo ' selected'; ?>><?php esc_attr_e( 'British Pound', 'wp-easycart-pro' ); ?></option>
			<option value="JPY" <?php if (get_option('ec_option_firstdatae4_currency') == "JPY") echo ' selected'; ?>><?php esc_attr_e( 'Japanese Yen', 'wp-easycart-pro' ); ?></option>
			<option value="AFA" <?php if (get_option('ec_option_firstdatae4_currency') == "AFA") echo ' selected'; ?>><?php esc_attr_e( 'Afghanistan Afghani', 'wp-easycart-pro' ); ?></option>
			<option value="ALL" <?php if (get_option('ec_option_firstdatae4_currency') == "ALL") echo ' selected'; ?>><?php esc_attr_e( 'Albanian Lek', 'wp-easycart-pro' ); ?></option>
			<option value="DZD" <?php if (get_option('ec_option_firstdatae4_currency') == "DZD") echo ' selected'; ?>><?php esc_attr_e( 'Algerian Dinar', 'wp-easycart-pro' ); ?></option>
			<option value="ADF" <?php if (get_option('ec_option_firstdatae4_currency') == "ADF") echo ' selected'; ?>><?php esc_attr_e( 'Andorran Franc', 'wp-easycart-pro' ); ?></option>
			<option value="ADP" <?php if (get_option('ec_option_firstdatae4_currency') == "ADP") echo ' selected'; ?>><?php esc_attr_e( 'Andorran Peseta', 'wp-easycart-pro' ); ?></option>
			<option value="AON" <?php if (get_option('ec_option_firstdatae4_currency') == "AON") echo ' selected'; ?>><?php esc_attr_e( 'Angolan New Kwanza', 'wp-easycart-pro' ); ?></option>
			<option value="ARS" <?php if (get_option('ec_option_firstdatae4_currency') == "ARS") echo ' selected'; ?>><?php esc_attr_e( 'Argentine Peso', 'wp-easycart-pro' ); ?></option>
			<option value="AWG" <?php if (get_option('ec_option_firstdatae4_currency') == "AWG") echo ' selected'; ?>><?php esc_attr_e( 'Aruban Florin', 'wp-easycart-pro' ); ?></option>
			<option value="AUD" <?php if (get_option('ec_option_firstdatae4_currency') == "AUD") echo ' selected'; ?>><?php esc_attr_e( 'Australian Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="ATS" <?php if (get_option('ec_option_firstdatae4_currency') == "ATS") echo ' selected'; ?>><?php esc_attr_e( 'Austrian Schilling', 'wp-easycart-pro' ); ?></option>
			<option value="BSD" <?php if (get_option('ec_option_firstdatae4_currency') == "BSD") echo ' selected'; ?>><?php esc_attr_e( 'Bahamanian Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="BHD" <?php if (get_option('ec_option_firstdatae4_currency') == "BHD") echo ' selected'; ?>><?php esc_attr_e( 'Bahraini Dinar', 'wp-easycart-pro' ); ?></option>
			<option value="BDT" <?php if (get_option('ec_option_firstdatae4_currency') == "BDT") echo ' selected'; ?>><?php esc_attr_e( 'Bangladeshi Taka', 'wp-easycart-pro' ); ?></option>
			<option value="BBD" <?php if (get_option('ec_option_firstdatae4_currency') == "BBD") echo ' selected'; ?>><?php esc_attr_e( 'Barbados Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="BEF" <?php if (get_option('ec_option_firstdatae4_currency') == "BEF") echo ' selected'; ?>><?php esc_attr_e( 'Belgian Franc', 'wp-easycart-pro' ); ?></option>
			<option value="BZD" <?php if (get_option('ec_option_firstdatae4_currency') == "BZD") echo ' selected'; ?>><?php esc_attr_e( 'Belize Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="BMD" <?php if (get_option('ec_option_firstdatae4_currency') == "BMD") echo ' selected'; ?>><?php esc_attr_e( 'Bermudian Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="BTN" <?php if (get_option('ec_option_firstdatae4_currency') == "BTN") echo ' selected'; ?>><?php esc_attr_e( 'Bhutan Ngultrum', 'wp-easycart-pro' ); ?></option>
			<option value="BOB" <?php if (get_option('ec_option_firstdatae4_currency') == "BOB") echo ' selected'; ?>><?php esc_attr_e( 'Bolivian Boliviano', 'wp-easycart-pro' ); ?></option>
			<option value="BWP" <?php if (get_option('ec_option_firstdatae4_currency') == "BWP") echo ' selected'; ?>><?php esc_attr_e( 'Botswana Pula', 'wp-easycart-pro' ); ?></option>
			<option value="BRL" <?php if (get_option('ec_option_firstdatae4_currency') == "BRL") echo ' selected'; ?>><?php esc_attr_e( 'Brazilian Real', 'wp-easycart-pro' ); ?></option>
			<option value="BND" <?php if (get_option('ec_option_firstdatae4_currency') == "BND") echo ' selected'; ?>><?php esc_attr_e( 'Brunei Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="BGL" <?php if (get_option('ec_option_firstdatae4_currency') == "BGL") echo ' selected'; ?>><?php esc_attr_e( 'Bulgarian Lev', 'wp-easycart-pro' ); ?></option>
			<option value="BIF" <?php if (get_option('ec_option_firstdatae4_currency') == "BIF") echo ' selected'; ?>><?php esc_attr_e( 'Burundi Franc', 'wp-easycart-pro' ); ?></option>
			<option value="XOF" <?php if (get_option('ec_option_firstdatae4_currency') == "XOF") echo ' selected'; ?>><?php esc_attr_e( 'CFA Franc BCEAO', 'wp-easycart-pro' ); ?></option>
			<option value="XAF" <?php if (get_option('ec_option_firstdatae4_currency') == "XAF") echo ' selected'; ?>><?php esc_attr_e( 'CFA Franc BEAC', 'wp-easycart-pro' ); ?></option>
			<option value="KHR" <?php if (get_option('ec_option_firstdatae4_currency') == "KHR") echo ' selected'; ?>><?php esc_attr_e( 'Cambodian Riel', 'wp-easycart-pro' ); ?></option>
			<option value="CVE" <?php if (get_option('ec_option_firstdatae4_currency') == "CVE") echo ' selected'; ?>><?php esc_attr_e( 'Cape Verde Escudo', 'wp-easycart-pro' ); ?></option>
			<option value="KYD" <?php if (get_option('ec_option_firstdatae4_currency') == "KYD") echo ' selected'; ?>><?php esc_attr_e( 'Cayman Islands Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="CLP" <?php if (get_option('ec_option_firstdatae4_currency') == "CLP") echo ' selected'; ?>><?php esc_attr_e( 'Chilean Peso', 'wp-easycart-pro' ); ?></option>
			<option value="CNY" <?php if (get_option('ec_option_firstdatae4_currency') == "CNY") echo ' selected'; ?>><?php esc_attr_e( 'Chinese Yuan Renminbi', 'wp-easycart-pro' ); ?></option>
			<option value="COP" <?php if (get_option('ec_option_firstdatae4_currency') == "COP") echo ' selected'; ?>><?php esc_attr_e( 'Colombian Peso', 'wp-easycart-pro' ); ?></option>
			<option value="KMF" <?php if (get_option('ec_option_firstdatae4_currency') == "KMF") echo ' selected'; ?>><?php esc_attr_e( 'Comoros Franc', 'wp-easycart-pro' ); ?></option>
			<option value="CRC" <?php if (get_option('ec_option_firstdatae4_currency') == "CRC") echo ' selected'; ?>><?php esc_attr_e( 'Costa Rican Colon', 'wp-easycart-pro' ); ?></option>
			<option value="HRK" <?php if (get_option('ec_option_firstdatae4_currency') == "HRK") echo ' selected'; ?>><?php esc_attr_e( 'Croatian Kuna', 'wp-easycart-pro' ); ?></option>
			<option value="CYP" <?php if (get_option('ec_option_firstdatae4_currency') == "CYP") echo ' selected'; ?>><?php esc_attr_e( 'Cyprus Pound', 'wp-easycart-pro' ); ?></option>
			<option value="CSK" <?php if (get_option('ec_option_firstdatae4_currency') == "CSK") echo ' selected'; ?>><?php esc_attr_e( 'Czech Koruna', 'wp-easycart-pro' ); ?></option>
			<option value="DKK" <?php if (get_option('ec_option_firstdatae4_currency') == "DKK") echo ' selected'; ?>><?php esc_attr_e( 'Danish Krone', 'wp-easycart-pro' ); ?></option>
			<option value="DJF" <?php if (get_option('ec_option_firstdatae4_currency') == "DJF") echo ' selected'; ?>><?php esc_attr_e( 'Djibouti Franc', 'wp-easycart-pro' ); ?></option>
			<option value="DOP" <?php if (get_option('ec_option_firstdatae4_currency') == "DOP") echo ' selected'; ?>><?php esc_attr_e( 'Dominican Peso', 'wp-easycart-pro' ); ?></option>
			<option value="NLG" <?php if (get_option('ec_option_firstdatae4_currency') == "NLG") echo ' selected'; ?>><?php esc_attr_e( 'Dutch Guilder', 'wp-easycart-pro' ); ?></option>
			<option value="XEU" <?php if (get_option('ec_option_firstdatae4_currency') == "XEU") echo ' selected'; ?>><?php esc_attr_e( 'ECU', 'wp-easycart-pro' ); ?></option>
			<option value="ECS" <?php if (get_option('ec_option_firstdatae4_currency') == "ECE") echo ' selected'; ?>><?php esc_attr_e( 'Ecuador Sucre', 'wp-easycart-pro' ); ?></option>
			<option value="EGP" <?php if (get_option('ec_option_firstdatae4_currency') == "EGP") echo ' selected'; ?>><?php esc_attr_e( 'Egyptian Pound', 'wp-easycart-pro' ); ?></option>
			<option value="SVC" <?php if (get_option('ec_option_firstdatae4_currency') == "SVC") echo ' selected'; ?>><?php esc_attr_e( 'El Salvador Colon', 'wp-easycart-pro' ); ?></option>
			<option value="EEK" <?php if (get_option('ec_option_firstdatae4_currency') == "EEK") echo ' selected'; ?>><?php esc_attr_e( 'Estonian Kroon', 'wp-easycart-pro' ); ?></option>
			<option value="ETB" <?php if (get_option('ec_option_firstdatae4_currency') == "ETB") echo ' selected'; ?>><?php esc_attr_e( 'Ethiopian Birr', 'wp-easycart-pro' ); ?></option>
			<option value="EUR" <?php if (get_option('ec_option_firstdatae4_currency') == "EUR") echo ' selected'; ?>><?php esc_attr_e( 'Euro', 'wp-easycart-pro' ); ?></option>
			<option value="FKP" <?php if (get_option('ec_option_firstdatae4_currency') == "FKP") echo ' selected'; ?>><?php esc_attr_e( 'Falkland Islands Pound', 'wp-easycart-pro' ); ?></option>
			<option value="FJD" <?php if (get_option('ec_option_firstdatae4_currency') == "FJD") echo ' selected'; ?>><?php esc_attr_e( 'Fiji Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="FIM" <?php if (get_option('ec_option_firstdatae4_currency') == "FTM") echo ' selected'; ?>><?php esc_attr_e( 'Finnish Markka', 'wp-easycart-pro' ); ?></option>
			<option value="FRF" <?php if (get_option('ec_option_firstdatae4_currency') == "FRF") echo ' selected'; ?>><?php esc_attr_e( 'French Franc', 'wp-easycart-pro' ); ?></option>
			<option value="GMD" <?php if (get_option('ec_option_firstdatae4_currency') == "GMD") echo ' selected'; ?>><?php esc_attr_e( 'Gambian Dalasi', 'wp-easycart-pro' ); ?></option>
			<option value="GHC" <?php if (get_option('ec_option_firstdatae4_currency') == "GHC") echo ' selected'; ?>><?php esc_attr_e( 'Ghanaian Cedi', 'wp-easycart-pro' ); ?></option>
			<option value="GIP" <?php if (get_option('ec_option_firstdatae4_currency') == "GIP") echo ' selected'; ?>><?php esc_attr_e( 'Gibraltar Pound', 'wp-easycart-pro' ); ?></option>
			<option value="XAU" <?php if (get_option('ec_option_firstdatae4_currency') == "XAU") echo ' selected'; ?>><?php esc_attr_e( 'Gold (oz.)', 'wp-easycart-pro' ); ?></option>
			<option value="GRD" <?php if (get_option('ec_option_firstdatae4_currency') == "GRD") echo ' selected'; ?>><?php esc_attr_e( 'Greek Drachma', 'wp-easycart-pro' ); ?></option>
			<option value="GTQ" <?php if (get_option('ec_option_firstdatae4_currency') == "GTQ") echo ' selected'; ?>><?php esc_attr_e( 'Guatemalan Quetzal', 'wp-easycart-pro' ); ?></option>
			<option value="GNF" <?php if (get_option('ec_option_firstdatae4_currency') == "GNF") echo ' selected'; ?>><?php esc_attr_e( 'Guinea Franc', 'wp-easycart-pro' ); ?></option>
			<option value="GYD" <?php if (get_option('ec_option_firstdatae4_currency') == "GYD") echo ' selected'; ?>><?php esc_attr_e( 'Guyanan Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="HTG" <?php if (get_option('ec_option_firstdatae4_currency') == "HTG") echo ' selected'; ?>><?php esc_attr_e( 'Haitian Gourde', 'wp-easycart-pro' ); ?></option>
			<option value="HNL" <?php if (get_option('ec_option_firstdatae4_currency') == "HNL") echo ' selected'; ?>><?php esc_attr_e( 'Honduran Lempira', 'wp-easycart-pro' ); ?></option>
			<option value="HKD" <?php if (get_option('ec_option_firstdatae4_currency') == "HKD") echo ' selected'; ?>><?php esc_attr_e( 'Hong Kong Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="HUF" <?php if (get_option('ec_option_firstdatae4_currency') == "HUF") echo ' selected'; ?>><?php esc_attr_e( 'Hungarian Forint', 'wp-easycart-pro' ); ?></option>
			<option value="ISK" <?php if (get_option('ec_option_firstdatae4_currency') == "ISK") echo ' selected'; ?>><?php esc_attr_e( 'Iceland Krona', 'wp-easycart-pro' ); ?></option>
			<option value="INR" <?php if (get_option('ec_option_firstdatae4_currency') == "INR") echo ' selected'; ?>><?php esc_attr_e( 'Indian Rupee', 'wp-easycart-pro' ); ?></option>
			<option value="IDR" <?php if (get_option('ec_option_firstdatae4_currency') == "IDR") echo ' selected'; ?>><?php esc_attr_e( 'Indonesian Rupiah', 'wp-easycart-pro' ); ?></option>
			<option value="IEP" <?php if (get_option('ec_option_firstdatae4_currency') == "IEP") echo ' selected'; ?>><?php esc_attr_e( 'Irish Punt', 'wp-easycart-pro' ); ?></option>
			<option value="ILS" <?php if (get_option('ec_option_firstdatae4_currency') == "ILS") echo ' selected'; ?>><?php esc_attr_e( 'Israeli New Shekel', 'wp-easycart-pro' ); ?></option>
			<option value="ITL" <?php if (get_option('ec_option_firstdatae4_currency') == "ITL") echo ' selected'; ?>><?php esc_attr_e( 'Italian Lira', 'wp-easycart-pro' ); ?></option>
			<option value="JMD" <?php if (get_option('ec_option_firstdatae4_currency') == "JMD") echo ' selected'; ?>><?php esc_attr_e( 'Jamaican Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="JOD" <?php if (get_option('ec_option_firstdatae4_currency') == "JOD") echo ' selected'; ?>><?php esc_attr_e( 'Jordanian Dinar', 'wp-easycart-pro' ); ?></option>
			<option value="KZT" <?php if (get_option('ec_option_firstdatae4_currency') == "KZT") echo ' selected'; ?>><?php esc_attr_e( 'Kazakhstan Tenge', 'wp-easycart-pro' ); ?></option>
			<option value="KES" <?php if (get_option('ec_option_firstdatae4_currency') == "KES") echo ' selected'; ?>><?php esc_attr_e( 'Kenyan Shilling', 'wp-easycart-pro' ); ?></option>
			<option value="KWD" <?php if (get_option('ec_option_firstdatae4_currency') == "KWD") echo ' selected'; ?>><?php esc_attr_e( 'Kuwaiti Dinar', 'wp-easycart-pro' ); ?></option>
			<option value="LAK" <?php if (get_option('ec_option_firstdatae4_currency') == "LAK") echo ' selected'; ?>><?php esc_attr_e( 'Lao Kip', 'wp-easycart-pro' ); ?></option>
			<option value="LVL" <?php if (get_option('ec_option_firstdatae4_currency') == "LVL") echo ' selected'; ?>><?php esc_attr_e( 'Latvian Lats', 'wp-easycart-pro' ); ?></option>
			<option value="LSL" <?php if (get_option('ec_option_firstdatae4_currency') == "LSL") echo ' selected'; ?>><?php esc_attr_e( 'Lesotho Loti', 'wp-easycart-pro' ); ?></option>
			<option value="LRD" <?php if (get_option('ec_option_firstdatae4_currency') == "LRD") echo ' selected'; ?>><?php esc_attr_e( 'Liberian Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="LTL" <?php if (get_option('ec_option_firstdatae4_currency') == "LTL") echo ' selected'; ?>><?php esc_attr_e( 'Lithuanian Litas', 'wp-easycart-pro' ); ?></option>
			<option value="LUF" <?php if (get_option('ec_option_firstdatae4_currency') == "LUF") echo ' selected'; ?>><?php esc_attr_e( 'Luxembourg Franc', 'wp-easycart-pro' ); ?></option>
			<option value="MOP" <?php if (get_option('ec_option_firstdatae4_currency') == "MOP") echo ' selected'; ?>><?php esc_attr_e( 'Macau Pataca', 'wp-easycart-pro' ); ?></option>
			<option value="MGF" <?php if (get_option('ec_option_firstdatae4_currency') == "MGF") echo ' selected'; ?>><?php esc_attr_e( 'Malagasy Franc', 'wp-easycart-pro' ); ?></option>
			<option value="MWK" <?php if (get_option('ec_option_firstdatae4_currency') == "MWK") echo ' selected'; ?>><?php esc_attr_e( 'Malawi Kwacha', 'wp-easycart-pro' ); ?></option>
			<option value="MYR" <?php if (get_option('ec_option_firstdatae4_currency') == "MYR") echo ' selected'; ?>><?php esc_attr_e( 'Malaysian Ringgit', 'wp-easycart-pro' ); ?></option>
			<option value="MVR" <?php if (get_option('ec_option_firstdatae4_currency') == "MVR") echo ' selected'; ?>><?php esc_attr_e( 'Maldive Rufiyaa', 'wp-easycart-pro' ); ?></option>
			<option value="MTL" <?php if (get_option('ec_option_firstdatae4_currency') == "MRL") echo ' selected'; ?>><?php esc_attr_e( 'Maltese Lira', 'wp-easycart-pro' ); ?></option>
			<option value="MRO" <?php if (get_option('ec_option_firstdatae4_currency') == "MRO") echo ' selected'; ?>><?php esc_attr_e( 'Mauritanian Ouguiya', 'wp-easycart-pro' ); ?></option>
			<option value="MUR" <?php if (get_option('ec_option_firstdatae4_currency') == "MUR") echo ' selected'; ?>><?php esc_attr_e( 'Mauritius Rupee', 'wp-easycart-pro' ); ?></option>
			<option value="MXN" <?php if (get_option('ec_option_firstdatae4_currency') == "MXN") echo ' selected'; ?>><?php esc_attr_e( 'Mexican Peso', 'wp-easycart-pro' ); ?></option>
			<option value="MNT" <?php if (get_option('ec_option_firstdatae4_currency') == "MNT") echo ' selected'; ?>><?php esc_attr_e( 'Mongolian Tugrik', 'wp-easycart-pro' ); ?></option>
			<option value="MAD" <?php if (get_option('ec_option_firstdatae4_currency') == "MAD") echo ' selected'; ?>><?php esc_attr_e( 'Moroccan Dirham', 'wp-easycart-pro' ); ?></option>
			<option value="MZM" <?php if (get_option('ec_option_firstdatae4_currency') == "MZM") echo ' selected'; ?>><?php esc_attr_e( 'Mozambique Metical', 'wp-easycart-pro' ); ?></option>
			<option value="MMK" <?php if (get_option('ec_option_firstdatae4_currency') == "MMK") echo ' selected'; ?>><?php esc_attr_e( 'Myanmar Kyat', 'wp-easycart-pro' ); ?></option>
			<option value="ANG" <?php if (get_option('ec_option_firstdatae4_currency') == "ANG") echo ' selected'; ?>><?php esc_attr_e( 'NL Antillian Guilder', 'wp-easycart-pro' ); ?></option>
			<option value="NAD" <?php if (get_option('ec_option_firstdatae4_currency') == "NAD") echo ' selected'; ?>><?php esc_attr_e( 'Namibia Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="NPR" <?php if (get_option('ec_option_firstdatae4_currency') == "NPR") echo ' selected'; ?>><?php esc_attr_e( 'Nepalese Rupee', 'wp-easycart-pro' ); ?></option>
			<option value="NZD" <?php if (get_option('ec_option_firstdatae4_currency') == "NZD") echo ' selected'; ?>><?php esc_attr_e( 'New Zealand Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="NIO" <?php if (get_option('ec_option_firstdatae4_currency') == "NIO") echo ' selected'; ?>><?php esc_attr_e( 'Nicaraguan Cordoba Oro', 'wp-easycart-pro' ); ?></option>
			<option value="NGN" <?php if (get_option('ec_option_firstdatae4_currency') == "NGN") echo ' selected'; ?>><?php esc_attr_e( 'Nigerian Naira', 'wp-easycart-pro' ); ?></option>
			<option value="NOK" <?php if (get_option('ec_option_firstdatae4_currency') == "NOK") echo ' selected'; ?>><?php esc_attr_e( 'Norwegian Kroner', 'wp-easycart-pro' ); ?></option>
			<option value="OMR" <?php if (get_option('ec_option_firstdatae4_currency') == "OMR") echo ' selected'; ?>><?php esc_attr_e( 'Omani Rial', 'wp-easycart-pro' ); ?></option>
			<option value="PKR" <?php if (get_option('ec_option_firstdatae4_currency') == "PKR") echo ' selected'; ?>><?php esc_attr_e( 'Pakistan Rupee', 'wp-easycart-pro' ); ?></option>
			<option value="XPD" <?php if (get_option('ec_option_firstdatae4_currency') == "XPD") echo ' selected'; ?>><?php esc_attr_e( 'Palladium (oz.)', 'wp-easycart-pro' ); ?></option>
			<option value="PAB" <?php if (get_option('ec_option_firstdatae4_currency') == "PAB") echo ' selected'; ?>><?php esc_attr_e( 'Panamanian Balboa', 'wp-easycart-pro' ); ?></option>
			<option value="PGK" <?php if (get_option('ec_option_firstdatae4_currency') == "PGK") echo ' selected'; ?>><?php esc_attr_e( 'Papua New Guinea Kina', 'wp-easycart-pro' ); ?></option>
			<option value="PYG" <?php if (get_option('ec_option_firstdatae4_currency') == "PYG") echo ' selected'; ?>><?php esc_attr_e( 'Paraguay Guarani', 'wp-easycart-pro' ); ?></option>
			<option value="PEN" <?php if (get_option('ec_option_firstdatae4_currency') == "PEN") echo ' selected'; ?>><?php esc_attr_e( 'Peruvian Nuevo Sol', 'wp-easycart-pro' ); ?></option>
			<option value="PHP" <?php if (get_option('ec_option_firstdatae4_currency') == "PHP") echo ' selected'; ?>><?php esc_attr_e( 'Philippine Peso', 'wp-easycart-pro' ); ?></option>
			<option value="XPT" <?php if (get_option('ec_option_firstdatae4_currency') == "XPT") echo ' selected'; ?>><?php esc_attr_e( 'Platinum (oz.)', 'wp-easycart-pro' ); ?></option>
			<option value="PLN" <?php if (get_option('ec_option_firstdatae4_currency') == "PLN") echo ' selected'; ?>><?php esc_attr_e( 'Polish Zloty', 'wp-easycart-pro' ); ?></option>
			<option value="PTE" <?php if (get_option('ec_option_firstdatae4_currency') == "PTE") echo ' selected'; ?>><?php esc_attr_e( 'Portuguese Escudo', 'wp-easycart-pro' ); ?></option>
			<option value="QAR" <?php if (get_option('ec_option_firstdatae4_currency') == "QAR") echo ' selected'; ?>><?php esc_attr_e( 'Qatari Rial', 'wp-easycart-pro' ); ?></option>
			<option value="ROL" <?php if (get_option('ec_option_firstdatae4_currency') == "ROL") echo ' selected'; ?>><?php esc_attr_e( 'Romanian Leu', 'wp-easycart-pro' ); ?></option>
			<option value="RUB" <?php if (get_option('ec_option_firstdatae4_currency') == "RUB") echo ' selected'; ?>><?php esc_attr_e( 'Russian Rouble', 'wp-easycart-pro' ); ?></option>
			<option value="WST" <?php if (get_option('ec_option_firstdatae4_currency') == "WST") echo ' selected'; ?>><?php esc_attr_e( 'Samoan Tala', 'wp-easycart-pro' ); ?></option>
			<option value="STD" <?php if (get_option('ec_option_firstdatae4_currency') == "STD") echo ' selected'; ?>><?php esc_attr_e( 'Sao Tome/Principe Dobra', 'wp-easycart-pro' ); ?></option>
			<option value="SAR" <?php if (get_option('ec_option_firstdatae4_currency') == "SAR") echo ' selected'; ?>><?php esc_attr_e( 'Saudi Riyal', 'wp-easycart-pro' ); ?></option>
			<option value="SCR" <?php if (get_option('ec_option_firstdatae4_currency') == "SCR") echo ' selected'; ?>><?php esc_attr_e( 'Seychelles Rupee', 'wp-easycart-pro' ); ?></option>
			<option value="SLL" <?php if (get_option('ec_option_firstdatae4_currency') == "SLL") echo ' selected'; ?>><?php esc_attr_e( 'Sierra Leone Leone', 'wp-easycart-pro' ); ?></option>
			<option value="XAG" <?php if (get_option('ec_option_firstdatae4_currency') == "XAG") echo ' selected'; ?>><?php esc_attr_e( 'Silver (oz.)', 'wp-easycart-pro' ); ?></option>
			<option value="SGD" <?php if (get_option('ec_option_firstdatae4_currency') == "SGD") echo ' selected'; ?>><?php esc_attr_e( 'Singapore Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="SKK" <?php if (get_option('ec_option_firstdatae4_currency') == "SKK") echo ' selected'; ?>><?php esc_attr_e( 'Slovak Koruna', 'wp-easycart-pro' ); ?></option>
			<option value="SIT" <?php if (get_option('ec_option_firstdatae4_currency') == "SIT") echo ' selected'; ?>><?php esc_attr_e( 'Slovenian Tolar', 'wp-easycart-pro' ); ?></option>
			<option value="SBD" <?php if (get_option('ec_option_firstdatae4_currency') == "SBD") echo ' selected'; ?>><?php esc_attr_e( 'Solomon Islands Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="ZAR" <?php if (get_option('ec_option_firstdatae4_currency') == "ZAR") echo ' selected'; ?>><?php esc_attr_e( 'South African Rand', 'wp-easycart-pro' ); ?></option>
			<option value="KRW" <?php if (get_option('ec_option_firstdatae4_currency') == "KRW") echo ' selected'; ?>><?php esc_attr_e( 'South-Korean Won', 'wp-easycart-pro' ); ?></option>
			<option value="ESP" <?php if (get_option('ec_option_firstdatae4_currency') == "ESP") echo ' selected'; ?>><?php esc_attr_e( 'Spanish Peseta', 'wp-easycart-pro' ); ?></option>
			<option value="LKR" <?php if (get_option('ec_option_firstdatae4_currency') == "LKR") echo ' selected'; ?>><?php esc_attr_e( 'Sri Lanka Rupee', 'wp-easycart-pro' ); ?></option>
			<option value="SHP" <?php if (get_option('ec_option_firstdatae4_currency') == "SHP") echo ' selected'; ?>><?php esc_attr_e( 'St. Helena Pound', 'wp-easycart-pro' ); ?></option>
			<option value="SRG" <?php if (get_option('ec_option_firstdatae4_currency') == "SRG") echo ' selected'; ?>><?php esc_attr_e( 'Suriname Guilder', 'wp-easycart-pro' ); ?></option>
			<option value="SZL" <?php if (get_option('ec_option_firstdatae4_currency') == "SZL") echo ' selected'; ?>><?php esc_attr_e( 'Swaziland Lilangeni', 'wp-easycart-pro' ); ?></option>
			<option value="SEK" <?php if (get_option('ec_option_firstdatae4_currency') == "SEK") echo ' selected'; ?>><?php esc_attr_e( 'Swedish Krona', 'wp-easycart-pro' ); ?></option>
			<option value="TWD" <?php if (get_option('ec_option_firstdatae4_currency') == "TWS") echo ' selected'; ?>><?php esc_attr_e( 'Taiwan Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="TZS" <?php if (get_option('ec_option_firstdatae4_currency') == "TZS") echo ' selected'; ?>><?php esc_attr_e( 'Tanzanian Shilling', 'wp-easycart-pro' ); ?></option>
			<option value="THB" <?php if (get_option('ec_option_firstdatae4_currency') == "THB") echo ' selected'; ?>><?php esc_attr_e( 'Thai Baht', 'wp-easycart-pro' ); ?></option>
			<option value="TOP" <?php if (get_option('ec_option_firstdatae4_currency') == "TOP") echo ' selected'; ?>><?php esc_attr_e( 'Tonga Pa\'anga', 'wp-easycart-pro' ); ?></option>
			<option value="TTD" <?php if (get_option('ec_option_firstdatae4_currency') == "TTD") echo ' selected'; ?>><?php esc_attr_e( 'Trinidad/Tobago Dollar', 'wp-easycart-pro' ); ?></option>
			<option value="TND" <?php if (get_option('ec_option_firstdatae4_currency') == "TND") echo ' selected'; ?>><?php esc_attr_e( 'Tunisian Dinar', 'wp-easycart-pro' ); ?></option>
			<option value="TRL" <?php if (get_option('ec_option_firstdatae4_currency') == "TRL") echo ' selected'; ?>><?php esc_attr_e( 'Turkish Lira', 'wp-easycart-pro' ); ?></option>
			<option value="UGS" <?php if (get_option('ec_option_firstdatae4_currency') == "UGS") echo ' selected'; ?>><?php esc_attr_e( 'Uganda Shilling', 'wp-easycart-pro' ); ?></option>
			<option value="UAH" <?php if (get_option('ec_option_firstdatae4_currency') == "UAH") echo ' selected'; ?>><?php esc_attr_e( 'Ukraine Hryvnia', 'wp-easycart-pro' ); ?></option>
			<option value="UYP" <?php if (get_option('ec_option_firstdatae4_currency') == "UYP") echo ' selected'; ?>><?php esc_attr_e( 'Uruguayan Peso', 'wp-easycart-pro' ); ?></option>
			<option value="AED" <?php if (get_option('ec_option_firstdatae4_currency') == "AED") echo ' selected'; ?>><?php esc_attr_e( 'Utd. Arab Emir. Dirham', 'wp-easycart-pro' ); ?></option>
			<option value="VUV" <?php if (get_option('ec_option_firstdatae4_currency') == "VUV") echo ' selected'; ?>><?php esc_attr_e( 'Vanuatu Vatu', 'wp-easycart-pro' ); ?></option>
			<option value="VEB" <?php if (get_option('ec_option_firstdatae4_currency') == "VEB") echo ' selected'; ?>><?php esc_attr_e( 'Venezuelan Bolivar', 'wp-easycart-pro' ); ?></option>
			<option value="VND" <?php if (get_option('ec_option_firstdatae4_currency') == "VND") echo ' selected'; ?>><?php esc_attr_e( 'Vietnamese Dong', 'wp-easycart-pro' ); ?></option>
			<option value="YUN" <?php if (get_option('ec_option_firstdatae4_currency') == "YUN") echo ' selected'; ?>><?php esc_attr_e( 'Yugoslav Dinar', 'wp-easycart-pro' ); ?></option>
			<option value="ZMK" <?php if (get_option('ec_option_firstdatae4_currency') == "ZMK") echo ' selected'; ?>><?php esc_attr_e( 'Zambian Kwacha', 'wp-easycart-pro' ); ?></option>
		  </select>
	</div>
	<div>
		<?php esc_attr_e( 'Test Mode', 'wp-easycart-pro' ); ?>
		<select name="ec_option_firstdatae4_test_mode" id="ec_option_firstdatae4_test_mode">
			<option value="1" <?php if (get_option('ec_option_firstdatae4_test_mode') == 1) echo ' selected'; ?>><?php esc_attr_e( 'Yes', 'wp-easycart-pro' ); ?></option>
			<option value="0" <?php if (get_option('ec_option_firstdatae4_test_mode') == 0) echo ' selected'; ?>><?php esc_attr_e( 'No', 'wp-easycart-pro' ); ?></option>
		</select>
	</div>
	<div class="ec_admin_settings_input" style="padding-right:0px;">
		<input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_firstdata_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart-pro' ); ?>" />
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'payment', 'firstdata' );?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart-pro' ); ?>" style="margin-top:0px; margin-right:0px;">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart-pro' ); ?>
		</a>
	</div>
</div>