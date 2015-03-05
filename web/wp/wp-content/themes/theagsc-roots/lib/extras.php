<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more() {
  return '&hellip; ' . __('', 'roots');
}
add_filter('excerpt_more', 'roots_excerpt_more');

function custom_excerpt_length( $length ) {
	return 26;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );