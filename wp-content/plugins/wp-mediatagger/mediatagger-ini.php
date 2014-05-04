<?php

$opt_init = array(
	// Admin panel options
		'admin_media_formats' => array(1, 2, 3),			// refer to -form.php : 'jpeg','gif','png'
		'admin_tags_source' => 1,							// 1: tags	2: tags and categories	3: categories 
		'admin_tags_groups' => '',
		'admin_override_post_taxonomy' => '2',				// 2: do not modify post taxonomy, 
															// 1: when tagging images, overrides post tags with image tags logically ORd
		'admin_background_color' => 'eef',
		'admin_credit' => array(1),
//		'admin_image_border_w' => 20,
//		'admin_image_border_color' => 'eee',
		'admin_num_tags_per_col' => 30,
		'admin_tags_excluded' => '',
		'admin_max_img_page' => 20,
		'admin_img_height' => 400,
		'admin_img_group_height' => 70,
	// Search options	
		'search_num_tags_per_col' => 30,
		'search_form_font' => 10,
		'search_tags_excluded' => '',
		'search_default_display_mode' => array(1, 2, 3),	// refer to -form.php : 'Tag cloud', 'Tag form', 'Search field'
		'search_display_switchable' => 1,					// 2 : non switchable
	// Result options	
		'result_default_display_mode' => 1,					// 1: gallery    2: itemized image list   3: image title list
		'result_display_switchable' => 1,					// 1: is switchable, 2: is not
		'result_display_optimize_xfer' => 1,				// 1: optimize, 2: dont optimize
		'result_img_list_w_h' => 250,
		'result_img_gallery_w_h' => 100,
	// Tagcloud
		'tagcloud_num_tags' => 0,							// 0: display all tags
		'tagcloud_order' => 0,								// 0: alphabetical ascent ; 1: descending hit order ; 2: random
		'tagcloud_font_min' => 8,
		'tagcloud_font_max' => 25,
		'tagcloud_color_min' => 'bbbbbb',
		'tagcloud_color_max' => '000000',
		'tagcloud_highlight_color' => '000000',
	// Image gallery
		'gallery_image_border_w' => 0,
		'gallery_image_border_color' => 'fff',
		'gallery_image_link_ctrl' => 1,						// 1: link to image		2: link to post
		'gallery_image_num_per_page' => 50,
	// List
		'list_image_num_per_page' => 10,
		'list_title_num_per_page' => 30	
	);

/*
$d = new stdClass;

	$d-> admin_media_formats = array('jpeg','gif','png');
	$d-> admin_tags_source = 1;								// 1: tags	2: tags and categories	3: categories
	$d-> admin_tags_groups = '';
	$d-> admin_override_post_taxonomy = '2';				// 2: do not modify post taxonomy, 1: when tagging images, overrides post tags with image tags logically ORd
	
	$d-> admin_background_color = 'eef';
	$d-> admin_credit = array(' ');
	$d-> admin_num_tags_per_col = 30;
	$d-> admin_tags_excluded = '';
	$d-> admin_max_img_page = 20;
	$d-> admin_img_height = 400;
	$d-> admin_img_group_height = 70;
	
	// Search options	
	$d-> search_num_tags_per_col = 30;
	$d-> search_form_font = 10;
	$d-> search_tags_excluded = '';
	$d-> search_default_display_mode = array('Tag cloud', 'Tag form', 'Search field');	// legacy : bitwise - 0: tagcloud    1: form   2: form
	$d-> search_display_switchable = 1;			// 2 : non switchable
	
	// Result options		
	$d-> result_default_display_mode = 1;		// 1: gallery    2: itemized image list   3: image title list
	$d-> result_display_switchable = 1;			// 1: is switchable, 2: is not
	$d-> result_display_optimize_xfer = 1;		// 1: optimize, 2: dont optimize
	$d-> result_img_list_w_h = 250;
	$d-> result_img_gallery_w_h = 100;
	
	// Tagcloud
	$d-> tagcloud_num_tags = 0;					// 0: display all tags
	$d-> tagcloud_order = 0;					// 1: alphabetical ascent ; 2: descending hit order ; 3: random
	$d-> tagcloud_font_min = 8;
	$d-> tagcloud_font_max = 25;
	$d-> tagcloud_color_min = 'bbbbbb';
	$d-> tagcloud_color_max = '000000';
	$d-> tagcloud_highlight_color = '000000';
	
	// Image gallery
	$d-> gallery_image_border_w = 0;
	$d-> gallery_image_border_color = 'fff';
	$d-> gallery_image_link_ctrl = 1;			// 1: link to image		2: link to post
	$d-> gallery_image_num_per_page = 50;
	
	// List
	$d-> list_image_num_per_page = 10;
	$d-> list_title_num_per_page = 30;
*/

?>