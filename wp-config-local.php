<?php 
// Local server settings
 
// Local Database
define('DB_NAME', 		'theagsc_local');
define('DB_USER', 		'root');
define('DB_PASSWORD', 	'root');
define('DB_HOST', 		'localhost');
 
// Overwrites the database to save keep edeting the DB
$lcl = 'http://localhost:8888/theagsc/';
define('WP_HOME',	 $lcl );
define('WP_SITEURL', $lcl );

define('WP_LOCAL_DEV', true );  // Used by disable plugin for local dev plugin in /mu-plugins