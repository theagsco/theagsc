<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
 

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
 
if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
	include( dirname( __FILE__ ) . '/local-config.php' );
} else {
    define('DB_NAME', 		'db173785_theagsc_dev');
    define('DB_USER', 		'1clk_wp_MESHP33');
    define('DB_PASSWORD', 	'starwars');
	define('DB_HOST', 		'localhost');
}
 
/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'KTNbkf]G!=*rt~w*>W+@N*{q)H|@{<ap.tB2Fjy;=!%q%VXtn%NY;_4/>ur[RFr0');
define('SECURE_AUTH_KEY',  'RT`gA)2H8I]Q|NEQ3a@^Y/%wv3BsAh%:Nbcc@>3!}=lJt+(1h<.o3`*A-Nom|Q0m');
define('LOGGED_IN_KEY',    '=JP*B+W%3!0tays+b,VQQd`>]B#3|cI;F+Da?0(zi3Bo9wxI7a*;@tTA0O-x.Ve7');
define('NONCE_KEY',        '1(o8$*nL2-ZCVCiySiQEsMy;vL;g|2)wn6fv2|Xjs3L}`)S)3ER~{+pdp+K3GVBk');
define('AUTH_SALT',        '|az;l^,6kZ|?f-7$13Q|$Mm,7ZOM(tOMB*pA;2Q`/2z4T/5UT-BjNzAkN+NO+!cT');
define('SECURE_AUTH_SALT', '-zr!Brdz 82>,8-Zb8{0ID>}R(6;Eg9B W{<XJ,zVHLs3d&MmR/-6GL3i8XSP*{1');
define('LOGGED_IN_SALT',   'r9*jXt-^j<Qe!,9,Mbr`{^LU-t-,j0`:IoO2&j<1U8G+Sy3+O$`cS5WMg>3!y.b ');
define('NONCE_SALT',       '+Yh])31ip2N;QUkQuYdejK%K&,1GJ>STmb|vHnX*e7a#22KsG4Xr%L&GvXehu;tF');

/**#@-*/

$table_prefix  = 'wp_';


/**

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if ( !defined('WP_DEBUG') )
	define('WP_DEBUG', false);
	
/** Disable WP File Editor */
define('DISALLOW_FILE_EDIT', true);



/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
