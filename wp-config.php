<?php
/**
 * The base configurations of the WordPress.
 *
 * This file is a custom version of the wp-config file to help
 * with setting it up for multiple environments. Inspired by
 * Leevi Grahams ExpressionEngine Config Bootstrap
 * (http://ee-garage.com/nsm-config-bootstrap)
 *
 * @package WordPress
 * @author Abban Dunne @abbandunne
 * @link http://abandon.ie/wordpress-configuration-for-multiple-environments
 */


// Define Environments - may be a string or array of options for an environment
$environments = array(
	'local'       => 'localhost',
	'development' => 'dev.',
);

// Get Server name
$server_name = $_SERVER['SERVER_NAME'];

foreach($environments AS $key => $env){

	if(is_array($env)){

		foreach ($env as $option){

			if(stristr($server_name, $option)){

				define('ENVIRONMENT', $key);
				
				break 2;
			}

		}

	} else {

		if(stristr($server_name, $env)){

			define('ENVIRONMENT', $key);

			break;

		}
		
	}

}

// If no environment is set default to live
if(!defined('ENVIRONMENT')) define('ENVIRONMENT', 'live');

// Define different DB connection details depending on environment
switch(ENVIRONMENT){

	case 'local':

		define('DB_NAME', 'theagsc_dev');
		define('DB_USER', 'root');
		define('DB_PASSWORD', 'root');
		define('DB_HOST', 'localhost');
		define('WP_DEBUG', true);

		define('WP_SITEURL', 'http://localhost:8888/theagsc');
		define('WP_HOME', 'http://localhost:8888/theagsc');
		
		define('WP_LOCAL_DEV', true );  // Used by disable plugin for local dev plugin in /mu-plugins

		break;

	case 'development':

		define('DB_NAME', 'db173785_theagsc_dev');
		define('DB_USER', 'db173785');
		define('DB_PASSWORD', 'bumfluff88');
		define('DB_HOST', $_ENV{DATABASE_SERVER});
		define('WP_DEBUG', true);

		break;

}

// If database isn't defined then it will be defined here.
// Put the details for your live environment in here.
if(!defined('DB_NAME'))
	define('DB_NAME', 'db173785_theagsc_live');

if(!defined('DB_USER'))
	define('DB_USER', '1clk_wp_kG8mohJ');

if(!defined('DB_PASSWORD'))
	define('DB_PASSWORD', 'bumfluff88');

if(!defined('DB_HOST'))
	define('DB_HOST', $_ENV{DATABASE_SERVER});

if(!defined('DB_CHARSET'))
	define('DB_CHARSET', 'utf8');

if(!defined('DB_COLLATE'))
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

if(!defined('AUTH_KEY'))
define('AUTH_KEY',         'KTNbkf]G!=*rt~w*>W+@N*{q)H|@{<ap.tB2Fjy;=!%q%VXtn%NY;_4/>ur[RFr0');

if(!defined('SECURE_AUTH_KEY'))
define('SECURE_AUTH_KEY',  'RT`gA)2H8I]Q|NEQ3a@^Y/%wv3BsAh%:Nbcc@>3!}=lJt+(1h<.o3`*A-Nom|Q0m');

if(!defined('LOGGED_IN_KEY'))
define('LOGGED_IN_KEY',    '=JP*B+W%3!0tays+b,VQQd`>]B#3|cI;F+Da?0(zi3Bo9wxI7a*;@tTA0O-x.Ve7');

if(!defined('NONCE_KEY'))
define('NONCE_KEY',        '1(o8$*nL2-ZCVCiySiQEsMy;vL;g|2)wn6fv2|Xjs3L}`)S)3ER~{+pdp+K3GVBk');

if(!defined('AUTH_SALT'))
define('AUTH_SALT',        '|az;l^,6kZ|?f-7$13Q|$Mm,7ZOM(tOMB*pA;2Q`/2z4T/5UT-BjNzAkN+NO+!cT');

if(!defined('SECURE_AUTH_SALT'))
define('SECURE_AUTH_SALT', '-zr!Brdz 82>,8-Zb8{0ID>}R(6;Eg9B W{<XJ,zVHLs3d&MmR/-6GL3i8XSP*{1');

if(!defined('LOGGED_IN_SALT'))
define('LOGGED_IN_SALT',   'r9*jXt-^j<Qe!,9,Mbr`{^LU-t-,j0`:IoO2&j<1U8G+Sy3+O$`cS5WMg>3!y.b ');

if(!defined('NONCE_SALT'))
define('NONCE_SALT',       '+Yh])31ip2N;QUkQuYdejK%K&,1GJ>STmb|vHnX*e7a#22KsG4Xr%L&GvXehu;tF');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
if(!isset($table_prefix)) $table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
if(!defined('WPLANG'))
	define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
if(!defined('WP_DEBUG'))
	define('WP_DEBUG', false);
	
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');