<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Config;

/**
 * Add <body> classes
 */
function body_class($classes) {
  // Add page slug if it doesn't exist
  if (is_single() || is_page() && !is_front_page()) {
    if (!in_array(basename(get_permalink()), $classes)) {
      $classes[] = basename(get_permalink());
    }
  }

  // Add class if sidebar is active
/*
  if (Config\display_sidebar()) {
    $classes[] = 'sidebar-primary';
  }
*/

  return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

// Clean up the excerpt
function excerpt_more() {
  return '&hellip; ' . __('', 'roots');
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

// Customise xcerpt length
function excerpt_length( $length ) {
	return 25;
}
add_filter( 'excerpt_length', __NAMESPACE__ . '\\excerpt_length', 999 );

/*
// Add search icon to Primary Navigation
function add_custom($items, $args) {
  if ($args->theme_location == 'primary_navigation') {
    $items .= '<li class="custom">Hey</li>';
  }
  return $items;
}
add_filter('wp_nav_menu_items', __NAMESPACE__ . '\\add_custom', 10, 2);
*/

// Convert absolute URLs in content to site relative ones
// Inspired by http://thisismyurl.com/6166/replace-wordpress-static-urls-dynamic-urls/
function sp_clean_static_url($content) {
   $thisURL = get_bloginfo('url');
   $stuff = str_replace(' src=\"'.$thisURL, ' src=\"', $content );
   $stuff = str_replace(' href=\"'.$thisURL, ' href=\"', $stuff );
	return $stuff;
}
add_filter('content_save_pre', __NAMESPACE__ . '\\sp_clean_static_url','99');


// Fix child category template issue

function is_subcategory($category = null) {
    if (is_category()) {
        if (null != $category){
            $cat = get_category($category);
        }else{
            $cat = get_category(get_query_var('cat'),false);
        }
        if ($cat->parent == 0 ){
            return false;
        }else{
            return true;
        }
    }
    return false;
}