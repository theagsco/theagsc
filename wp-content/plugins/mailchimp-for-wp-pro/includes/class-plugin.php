<?php

class MC4WP {
	private static $instance = null;

	public static function instance() {
		return self::$instance;
	}

	public static function init() {
		if(!self::$instance) {
			self::$instance = new self;
		}
	}

	private function __construct() {

		// init checkboxes
		require_once MC4WP_PLUGIN_DIR . 'includes/class-checkboxes.php';
		MC4WP_Checkboxes::init();

		// init forms
		require_once MC4WP_PLUGIN_DIR . 'includes/class-forms.php';
		MC4WP_Forms::init();

		// init logging class
		require_once MC4WP_PLUGIN_DIR . 'includes/class-log.php';
		MC4WP_Log::init();

		// init widget
		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		// load css
		add_action( 'wp_enqueue_scripts', array($this, 'load_stylesheets'), 90);
		add_action( 'login_enqueue_scripts',  array($this, 'load_stylesheets') );
		
	}

	public function register_widget() {
		include_once MC4WP_PLUGIN_DIR . 'includes/class-widget.php';
		register_widget( 'MC4WP_Widget' );
	}

	public function load_stylesheets() 
	{
		$stylesheets = apply_filters('mc4wp_stylesheets', array());

		if(!empty($stylesheets)) {
			$stylesheet_url = add_query_arg($stylesheets, plugins_url('mailchimp-for-wp-pro/assets/css/css.php'));
			wp_enqueue_style( 'mailchimp-for-wp', $stylesheet_url, array(), MC4WP_VERSION_NUMBER);
		}
	}

}
