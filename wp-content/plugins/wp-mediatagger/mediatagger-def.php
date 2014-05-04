<?php	// WP Mediatagger messages definition file

	$t = new stdClass;
	
	///////////////////////////////////////////////////////////////////////////////////////
	//
	// Messages and text constants
	//
	//
	
	$t-> activation = "Activation";
	$t-> table_detected_not_created = "Taxonomy table detected in database - not created";
	$t-> table_detected_converted = "Taxonomy table detected in database - converted.";
	$t-> table_not_detected_created = "Taxonomy table not detected in database - created";
	$t-> loading = "Loading";
	$t-> plugin_options_detected_serial = "Plugin options detected in database in <b>serialized</b> format - RELOADED successfully.";
	$t-> plugin_options_detected_itemized = "Plugin options detected in database in <b>itemized</b> format - RELOADED and SERIALIZED successfully.";
	$t-> plugin_options_not_detected =  "No plugin options detected in database from former versions - INITIALIZED successfully.";
	$t-> no_tag_detected = "<b>No tag detected in your blog - Start by adding tags to your blog before tagging media.</b>";
	$t-> select_media_before_tagging = 'Select at least one media in the list for tagging.';
	$t-> displaying = 'Displaying';
	$t-> view = 'View';

	$t-> editor = 'Editor';
	$t-> file = 'File';
	$t-> tag = 'Tag';
	$t-> tag_and_previous = '< Tag';
	$t-> tag_and_next = 'Tag >';
	$t-> clear = 'Clear';
	$t-> media_associated_tag__1 = 'media associated to tag';
	$t-> media_associated_tag__2 = 'medias associated to tag';
	
	$t-> explorer = 'Explorer';
	$t-> all_media = 'All media';
	$t-> list_all_media = 'List all media';
	$t-> tagged_media = 'Tagged media';
	$t-> list_tagged_media = 'List tagged media';
	$t-> untagged_media = 'Untagged media';
	$t-> list_untagged_media = 'List untagged media';
	$t-> list_media = 'List';
	$t-> list_media_custom = 'List media added to the custom list';
	
	$t-> search_all_media = 'Search all media';
	$t-> display = 'Display';
	$t-> display_depth = 'Display depth';
	$t-> media_starting_from = 'media starting from';
	$t-> start_display_index = 'Start display index';
	$t-> no_media_detected = 'No media detected in your blog - Start by adding media before tagging.';

	$t-> add_to_list = 'Add to list';
	$t-> remove_from_list = 'Remove from list';
	$t-> reset_list = 'Reset list';
	$t-> no_media_range = 'No media to display in this range - Start / stop indexes reset to default.';
	
	$t-> no_tag_match = 'None of the selected tag(s) match existing tags. The media search URL should be composed as http://www.mysite.com/library?tags=tag1+tag2+...+tagN, where http://www.mysite.com/library is the search result page. Check the spelling of the tag slugs';
	$t-> search_cloud  = 'You can search a media by theme with the tag cloud above.';
	$t-> search_form = 'You can search a media by theme by selecting appropriate keywords below and clicking OK.';
	$t-> search_cloud_form = 'You can search a media by theme either with the tag cloud above or selecting appropriate keywords below and clicking OK.';
	$t-> search_keyword = 'You can search a media entering a keyword above that will be searched as part of the medias name.';
	$t-> search_cloud_keyword = 'You can search a media by theme with the tag cloud above or entering a keyword above that will be searched as part of the medias name.';
	$t-> search_form_keyword = 'You can search a media by theme by selecting appropriate keywords below and clicking OK or entering a keyword above that will be searched as part of the medias name.';
	$t-> search_cloud_form_keyword = 'You can search a media by theme either with the tag cloud above or selecting appropriate keywords below and clicking OK or entering a keyword above that will be searched as part of the medias name.';
	
	$t-> no_media_matching = 'no media found matching this criteria list';
	$t-> n_media_found__1 = 'media found';
	$t-> n_media_found__2 = 'medias found';
	
	$t-> result_display = 'Results display';
	$t-> result_gallery = 'Display the results as an image gallery';
	$t-> gallery = 'Gallery';
	$t-> result_image_list = 'Display the results as an itemized image list';
	$t-> itemized = 'Itemized';
	$t-> title_list = 'Display the results as an image title list';
	$t-> titles = 'Titles';
	
	$t-> not_attached_post = 'not attached to any post';
	$t-> go_to_page = 'Go to page';
	$t-> access_to_media = 'Access to media';
	$t-> click_previous_page = 'Click to display previous page';
	$t-> previous = 'previous';
	$t-> click_next_page = 'Click to display next page';
	$t-> next = 'next';
	$t-> image_x_to_y__1 = 'image %d to %d';
	$t-> image_x_to_y__2 = 'images %d to %d';
	$t-> offer_media_engine = 'Offer a media search engine to your blog with';
	
	$t-> invalid_option_value = 'Invalid values restored to the last correct settings :';
	$t-> options_saved = 'Parameters saved.';
	$t->not_available = 'not available';
	
	$t-> tags_from_taxonomy = 'Tags detected from the image taxonomy';
	$t-> post = 'post';
	$t-> original_tags__1 = 'Original tag';
	$t-> original_tags__2 = 'Original tags';
	$t-> new_tags__1 = 'New tag';
	$t-> new_tags__2 = 'New tags';
	
	$t-> search_attachment_like = 'Search attachment like...';
	$t-> toggle_tag_cloud = 'Toggle tag cloud';
	$t-> tag_cloud = 'Tag cloud';
	$t-> toggle_form = 'Toggle form';
	$t-> form = 'Form';
	$t-> toggle_search_field = 'Toggle search field';
	$t-> search_field = 'Search field';
	$t-> type_here_keyword = 'Type here the keyword that will be searched in the media names database, rather than filtering on the tags attached to the medias.';
	$t-> occurence__1 = 'occurence';
	$t-> occurence__2 = 'occurences';
	
	$t-> orphean_media = 'Orphean media, not linked to any post';
	$t-> no_tag_associated = 'No tag associated to this media';
	
	$t-> search_display = "Search display";
	$t-> php_version_outdated = "PHP Version %.2f is the minimum required on your server to run the %s plugin.";
	$t-> php_version_current = "Version %s was detected on the server.";
	$t-> update_options = "Update options";
	$t-> others = "Others";
	
	$t-> option_pane_donation = 'If you experience this plugin brings value to your site, you are free to make a donation for supporting the development and maintenance.';
	$t-> option_pane_donation2 = 'Even small donations matter and are encouraging !';
	
	$t-> plugin_description = 'Display your MediaTagger tag cloud in the sidebar. Before that, you need to have properly tagged your medias in the MediaTagger plugin Admin Panel and have as well setup a result page that you will use as your tag cloud target page';
	
?>