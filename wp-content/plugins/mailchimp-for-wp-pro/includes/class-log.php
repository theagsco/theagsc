<?php

class MC4WP_Log {

	private $table_name;
	private $db_version = '1.0.3';

	private static $instance = null;

	public static function init() {
		if(!self::$instance) {
			self::$instance = new self;
		}
	}

	public static function instance() {
		return self::$instance;
	}


	private function __construct() {
		if(is_admin()) {
			 if(! version_compare(get_option('mc4wp_log_db_version', 0), $this->db_version, '>=')) {
				add_action('init', array($this, 'setup') );
			}
		}

		add_action( 'mc4wp_subscribe_form', array( $this, 'log_form_subscriber' ), 10, 5 );
		add_action( 'mc4wp_subscribe_checkbox', array( $this, 'log_checkbox_subscriber' ), 10, 6 );
	}

	public function log_form_subscriber( $email, array $list_ids, $form_id, array $merge_vars = array(), $url = '' ) {
		return mc4wp_log( $email, $list_ids, 'form', 'sign-up-form', $merge_vars, $form_id, null, $url );
	}

	public function log_checkbox_subscriber( $email, array $list_ids, $signup_type, $merge_vars = array(), $comment_id = null, $url = '') {
		return mc4wp_log( $email, $list_ids, 'checkbox', $signup_type, $merge_vars, null, $comment_id, $url );
	}

	public function setup() {
		global $wpdb, $charset_collate;
		$table_name = $wpdb->prefix . 'mc4wp_log';

		$wpdb->hide_errors();

		// change column name, 
		// @v1.01
		$wpdb->query("ALTER TABLE {$table_name} CHANGE list_ID list_ids VARCHAR(100)");

		// create database table
		$sql = "CREATE TABLE {$table_name} (
			ID bigint(20) NOT NULL AUTO_INCREMENT,
			email VARCHAR(100) NOT NULL,
			list_ids VARCHAR(100) NOT NULL,
			signup_method VARCHAR(50) NOT NULL,
			signup_type VARCHAR(55) NOT NULL,
			form_ID bigint(20) NULL,
			comment_ID bigint(20) NULL,
			merge_vars text NULL,
			url VARCHAR(255) DEFAULT '',
			datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (ID)
			) $charset_collate; ";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		// change 'sign-up_form' in 'sign-up-form';
		$wpdb->query("UPDATE {$table_name} SET signup_type = 'sign-up-form' WHERE signup_type = 'sign-up_form'");

		$wpdb->show_errors();
		update_option('mc4wp_log_db_version', $this->db_version);
	}

}
