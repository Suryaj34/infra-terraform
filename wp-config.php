<?php
//Begin Really Simple Security Server variable fix
   $_SERVER["HTTPS"] = "on";
//END Really Simple Security
//Begin Really Simple Security session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple Security cookie settings
//Begin Really Simple Security key
define('RSSSL_KEY', 'nGFgdGPPskAKT1lZ1Suzkm9cR4wjbAIYol2mOXVwPGtJCiKp8G2vIuxDuFhGIkAI');
//END Really Simple Security key

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpressdbteamc' );

/** Database username */
define( 'DB_USER', 'jash' );

/** Database password */
define( 'DB_PASSWORD', 'teamC1234567' );

/** Database hostname */
define( 'DB_HOST', 'terraform-20260222150857800000000001.c5cqc42wmvhy.ap-south-1.rds.amazonaws.com' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'f3h0_}/8h?sX3|5&sjvcQm/Sg.`;cRg2FgZ3f8^@%7z)[ite4im+{ymol59jlR0?' );
define( 'SECURE_AUTH_KEY',  '.<[vj|uuV<Q:i%N?1c=Fa4y#x-pz{T1O5KGIRBQ$0XCGDAkOz~+/cwWXXCL)n=O-' );
define( 'LOGGED_IN_KEY',    'L:)B~kel:~t&B;AqWvaj(w*}gRC/x!(gnrFD90TE@-iN}GQzZ_/>]3EHH2V)b:J]' );
define( 'NONCE_KEY',        'aDE!w=DyVb4p4`LSg06f_GX$=c]u!$Yr+O4{`lM;M}Ne^rF#1!}cryEF8.1~U4m,' );
define( 'AUTH_SALT',        'J$K0(WUJ(M $i+a$p&aenKdFsB;/oAXIcwX45rP>_&JY2oSW,GkdQDC_g(Zu^Q4u' );
define( 'SECURE_AUTH_SALT', '1?Ra(ONd&mV2qzUi Gv&Dc+ls67UJN-6a~cj>!*R:$MKc]KrEQ8N=F!hV$H$;XxK' );
define( 'LOGGED_IN_SALT',   '(SWBQ~MVOUlJow]8c$T}9!~KD4;-6*DHVAi@dwWVv>07OxC?|%[!^`Ls:]kvZ|$g' );
define( 'NONCE_SALT',       '8C3upH`~f?$bl:g7?<%)+C`6.!op5jJ?{![NxQSi=eJ@QG#a(|^l@~1e?p%;glK%' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
 define( 'WP_DEBUG', true );
 define( 'WP_DEBUG_LOG', true);

// define('DISALLOW_FILE_EDIT', false);

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
