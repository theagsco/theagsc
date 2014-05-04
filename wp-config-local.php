define('DB_NAME', 'theagsc_dev');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost:/Applications/MAMP/tmp/mysql/mysql.sock' );
$table_prefix  = 'wp_';
/**
 * Site URLs
 */
$live = 'http://theagsc.com/';
$lcl = 'http://localhost:8888/theagsc/';
define('WP_HOME',	 $lcl );
define('WP_SITEURL', $lcl );
/*
 * Debug on/off for Development
 */
define('WP_LOCAL_DEV', true );  // Used by disable plugin for local dev plugin in /mu-plugins
define('WP_DEBUG', true);
define('SAVEQUERIES', false);
define('WP_CACHE', false);
