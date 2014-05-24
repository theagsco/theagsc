<?php
/*
Plugin Name: Image Tags
Description: Adds the tag taxonomy to Wordpress media uploads
Version: 0.1
Author: Dave Coleman
Author URI: http://thagsc.com/
*/


function wptp_add_tags_to_attachments() {
    register_taxonomy_for_object_type( 'post_tag', 'attachment' );
}
add_action( 'init' , 'wptp_add_tags_to_attachments' );

?>