<?php
/*
Plugin Name: MailChimp for WordPress Pro
Plugin URI: http://dannyvankooten.com/mailchimp-for-wordpress/
Description: Pro version of MailChimp for WordPress. Adds various sign-up methods to your website.
Version: 1.98.5
Author: Danny van Kooten
Author URI: http://dannyvanKooten.com
License: GPL v3

MailChimp for WordPress
Copyright (C) 2012-2013, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


if( !defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	header( 'X-Robots-Tag: noindex' );
	exit;
}

// define some constant we need. probably already defined.
if (!defined('WP_CONTENT_DIR')) { define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' ); }
if (!defined('WP_CONTENT_URL') ) { define( 'WP_CONTENT_URL', site_url( 'wp-content') ); }

define('MC4WP_VERSION_NUMBER', "1.98.5");
define('MC4WP_PLUGIN_FILE', __FILE__);
define("MC4WP_PLUGIN_DIR", plugin_dir_path(__FILE__));

 // Global Functions
require_once MC4WP_PLUGIN_DIR . 'includes/functions.php';
require_once MC4WP_PLUGIN_DIR . 'includes/log-functions.php';
require_once MC4WP_PLUGIN_DIR . 'includes/template-functions.php';

// Initialize Plugin Class
require_once MC4WP_PLUGIN_DIR . 'includes/class-plugin.php';
MC4WP::init();

// Only load the Admin class on admin requests, excluding AJAX.
if(is_admin() && (!defined("DOING_AJAX") || !DOING_AJAX)) {
	// Initialize Admin Class
	require_once MC4WP_PLUGIN_DIR . 'includes/class-admin.php';
	MC4WP_Admin::init();
}