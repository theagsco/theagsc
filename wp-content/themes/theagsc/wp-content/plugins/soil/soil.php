<?php
/*
Plugin Name:        Soil
Plugin URI:         http://roots.io/plugins/soil/
Description:        Clean up WordPress markup, relative URLs, nice search
Version:            3.0.0
Author:             Roots
Author URI:         http://roots.io/

License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define('SOIL_PATH', plugin_dir_path(__FILE__));

function soil_load_modules() {
  if (current_theme_supports('soil-clean-up')) {
    require_once(SOIL_PATH . 'modules/clean-up.php');
  }

  if (current_theme_supports('soil-relative-urls')) {
    require_once(SOIL_PATH . 'modules/relative-urls.php');
  }

  if (current_theme_supports('soil-nice-search')) {
    require_once(SOIL_PATH . 'modules/nice-search.php');
  }
}
add_action('after_setup_theme', 'soil_load_modules');
