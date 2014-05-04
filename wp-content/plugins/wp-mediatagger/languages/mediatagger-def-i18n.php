<?php

_("Activation");
_("Taxonomy table detected in database - not created");
_("Taxonomy table detected in database - converted.");
_("Taxonomy table not detected in database - created");
_("Loading");
_("Plugin options detected in database in <b>serialized</b> format - RELOADED successfully.");
_("Plugin options detected in database in <b>itemized</b> format - RELOADED and SERIALIZED successfully.");
_("No plugin options detected in database from former versions - INITIALIZED successfully.");
_("<b>No tag detected in your blog - Start by adding tags to your blog before tagging media.</b>");
_("Select at least one media in the list for tagging.");
_("Displaying");
_("View");
_("Editor");
_("File");
_("Tag");
_("< Tag");
_("Tag >");
_("Clear");
_("Explorer");
_("All media");
_("List all media");
_("Tagged media");
_("List tagged media");
_("Untagged media");
_("List untagged media");
_("List");
_("List media added to the custom list");
_("Search all media");
_("Display");
_("Display depth");
_("media starting from");
_("Start display index");
_("No media detected in your blog - Start by adding media before tagging.");
_("Add to list");
_("Remove from list");
_("Reset list");
_("No media to display in this range - Start / stop indexes reset to default.");
_("None of the selected tag(s) match existing tags. The media search URL should be composed as http://www.mysite.com/library?tags=tag1+tag2+...+tagN, where http://www.mysite.com/library is the search result page. Check the spelling of the tag slugs");
_("You can search a media by theme with the tag cloud above.");
_("You can search a media by theme by selecting appropriate keywords below and clicking OK.");
_("You can search a media by theme either with the tag cloud above or selecting appropriate keywords below and clicking OK.");
_("You can search a media entering a keyword above that will be searched as part of the medias name.");
_("You can search a media by theme with the tag cloud above or entering a keyword above that will be searched as part of the medias name.");
_("You can search a media by theme by selecting appropriate keywords below and clicking OK or entering a keyword above that will be searched as part of the medias name.");
_("You can search a media by theme either with the tag cloud above or selecting appropriate keywords below and clicking OK or entering a keyword above that will be searched as part of the medias name.");
_("no media found matching this criteria list");
_("Results display");
_("Display the results as an image gallery");
_("Gallery");
_("Display the results as an itemized image list");
_("Itemized");
_("Display the results as an image title list");
_("Titles");
_("not attached to any post");
_("Go to page");
_("Access to media");
_("Click to display previous page");
_("previous");
_("Click to display next page");
_("next");
_("Offer a media search engine to your blog with");
_("Invalid values restored to the last correct settings :");
_("Parameters saved.");
_("not available");
_("Tags detected from the image taxonomy");
_("post");
_("Search attachment like...");
_("Toggle tag cloud");
_("Tag cloud");
_("Toggle form");
_("Form");
_("Toggle search field");
_("Search field");
_("Type here the keyword that will be searched in the media names database, rather than filtering on the tags attached to the medias.");
_("Orphean media, not linked to any post");
_("No tag associated to this media");
_("Search display");
_("PHP Version %.2f is the minimum required on your server to run the %s plugin.");
_("Version %s was detected on the server.");
_("Update options");
_("Others");
_("If you experience this plugin brings value to your site, you are free to make a donation for supporting the development and maintenance.");
_("Even small donations matter and are encouraging !");
_("Display your MediaTagger tag cloud in the sidebar. Before that, you need to have properly tagged your medias in the MediaTagger plugin Admin Panel and have as well setup a result page that you will use as your tag cloud target page");
_n("media associated to tag", "medias associated to tag");
_n("media found", "medias found");
_n("image %d to %d", "images %d to %d");
_n("Original tag", "Original tags");
_n("New tag", "New tags");
_n("occurence", "occurences");

?>

<?php	// WP Mediatagger admin panel options form parametrization

$form = array(

	// header
	//
	'header_general' => array(
		'type' => "HEADER",
		'desc' => self::__("General")
	),
	
	// admin_media_formats
	//
	'admin_media_formats' => array(
		'type' => "SELECT_OR",
		'desc' => self::__("File formats for tagging"),
		'tooltip' => self::__("This defines the set of files available for tagging (admin) and searching (visitors)"),
		'list' => array("jpeg","gif","png","txt","rtf","pdf","mp3"),
		'checker' => "count(@VAL) > 0",
		'errmsg' => self::__("At least one file format has to be selected (General).")
	),
	
	// admin_tags_source
	//
	'admin_tags_source' => array(
		'type' => "SELECT_XOR",
		'desc' => self::__("Tagging source"),
		'tooltip' => self::__("You can tag medias either with your blog tags, with your categories, or with your tags and categories merged together"),
		'list' => array(self::__("Tags"),
						self::__("Tags and categories"),
						self::__("Categories")
					),
		'order' => array(1,3,2)
		// ! Choice restricted by list ==> No checker & error message
	),
					
	
	// admin_tags_groups
	//
	'admin_tags_groups' => array(
		'type' => "TEXTAREA",
		'desc' => 	self::__("Tags groups"),
		'tooltip' => self::__("Optional : you can regroup tags by groups so that those tags are displayed together. This applies to the tagging panel form as well as to the search form. If you do not need to categorize your tags, keep this field empty. Otherwise, use the following CSV syntax, one group definition per line :

   my_group1_name=tag1,tag2,tag5,tag7, ... ,tag n
   my_group2_name=tag3,tag4,tag6, ... , tag m
   ...

Spaces do not matter - The tags not listed in these groups will be listed at the end in the default category. Optionnally, you can name this default category by adding as last line - do not add anything after '=' :

   my_default_group_name=

By default the tags are disposed by column automatically ; you can override this rule by leaving a blank line before the group you want to start to the next column."),
		'checker' => "self::check_valid_tags_groups('@VAL')",
		'errmsg' => self::__("Tag group definition : incorrect syntax (General).")
	),
	
	// admin_background_color
	//	
	'admin_background_color' => array(
		'type' => "TEXT",
		'size' => 8,
		'desc' => 	self::__("Fields background color"),
		'tooltip' => self::__("This color is used for the fields background in the tagging panel and search form"),
		'checker' => "self::check_valid_colorhex('@VAL')",
		'errmsg' => self::__("The fields background color must be a valid hexadecimal color code as 3ff or a1b160 (General).")
	),
	
	// header
	//
	'header_media_taxonomy' => array(
		'type' => "HEADER",
		'desc' => self::__("Media taxonomy")
	),
	
	// admin_override_post_taxonomy
	//
	'admin_override_post_taxonomy' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Override original post taxonomy with media taxonomy"),
		'tooltip' => self::__("Carefully read this note :

This option allows to let the media taxonomy defined with MediaTagger supersede the post taxonomy provided as standard by WordPress.
If you activate this feature : every time you tag a media from the MediaTagger editor, the post containing this media will be associated to the tag list formed from a logical OR between all the tags set on the medias contained in the post. 

Any former tag manually set on the post will be lost and supersed by this logical media taxonomy combination. For that reason it is recommended to backup your WordPress database before the first activation of this feature."),
		'list' => array(self::__("Yes"),
						self::__("No")
					)
		// ! Choice restricted by list ==> No checker & error message
	),
	
	// taxonomy_previsualize_link
	//
	'taxonomy_previsualize_link' => array(
		'type' => "LINK",
		'desc'	=> self::__("Preview the Media Taxonomy scheme"),
		'tooltip'=> self::__("This preview function lets you evaluate what would the media taxonomy be compared to your current posts tagging.

After clicking this link you will be directed to the preview page. From this page you will then have the choice to run an automatic retagging batch."),
		'text'	=> self::__("Preview"),
		'url'	=> "mdtg_submit('form_display','preview_tax')"
		// ! Link ==> No checker & error message
	),

	// header
	//
	'header_tag_editor' => array(
		'type' => "HEADER",
		'desc' => self::__("Tag editor")
	),

	// admin_tags_excluded
	//
	'admin_tags_excluded' => array(
		'type' => "TEXT",
		'size' => 60,
		'desc' => self::__("Tagging excluded tags"),
		'tooltip' => self::__("List of comma separated tag names defined in your blog and that will not appear in the Tag Editor"),
		'checker' => "self::check_string_is_taglist('@VAL')",
		'errmsg' => self::__("The tag exclusion list must contain available tags separated by commas (Tag Editor).")
	),
	
	// admin_num_tags_per_col
	//
	'admin_num_tags_per_col' => array(
		'type' => "TEXT",
		'desc' => self::__("Number of tags displayed per column"),
		'tooltip' => self::__("Set this number to have a convenient columnar display of your tags in the Tag Editor, properly spread over the above available horizontal space"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("The number of tags displayed by column must be an integer greater than 0 (Tag Editor).")
	),
	
	// admin_img_height
	//
	'admin_img_height' => array(
		'type' => "TEXT",
		'desc' => self::__("Maximum image pixel height"),
		'tooltip' => self::__("Used to scale the images displayed in the Tag Editor"),
		'checker' => "is_int(@VAL) && @VAL >= 100",
		'errmsg' => self::__("The maximum image pixel height must be an integer greater than 100 (Tag Editor).")
	),
	
	// admin_img_height
	//
	'admin_img_group_height' => array(
		'type' => "TEXT",
		'desc' => self::__("Maximum image pixel height for group display"),
		'tooltip' => self::__("Used to scale the images displayed in the Tag Editor for group tagging"),
		'checker' => "is_int(@VAL) && @VAL >= 10",
		'errmsg' => self::__("The maximum image pixel height must be an integer greater than 10 (Tag Editor).")
	),
	
	// admin_max_img_list
	//
	'admin_max_img_page' => array(
		'type' => "TEXT",
		'desc' => self::__("Maximum number of media displayed per page"),
		'tooltip' => self::__("Maximum number of media that can be displayed on one page"),
		'checker' => "is_int(@VAL) && @VAL >= 10 && @VAL <= 500",
		'errmsg' => self::__("The maximum number of media per page must be greater than 10 and less than 500 (Tag Editor).")
	),

	
/*	// admin_image_border_w
	//
	'admin_image_border_w' => array(
		'type' => "TEXT",
		'desc' => self::__("Image frame pixel width"),
		'tooltip' => self::__("This parameter sets the image border width"),
		'checker' => "is_int(@VAL) && @VAL >= 0",
		'errmsg' => self::__("The image frame pixel width must be an integer greater than 0 (Tag Editor).")
	),
	
	// admin_image_border_color
	//
	'admin_image_border_color' => array(
		'type' => "TEXT",
		'size' => 8,
		'desc' => self::__("Image frame color in hex format"),
		'tooltip' => self::__("This parameter sets the image border color"),
		'checker' => "self::check_valid_colorhex('@VAL')",
		'errmsg' => self::__("The image frame color must be a valid hexadecimal color code as 3ff or a1b160 (Tag Editor).")
	),*/

	// header
	//
	'header_search_format' => array(
		'type' => "HEADER",
		'desc' => self::__("Search format")
	),
		
	// search_tags_excluded
	//
	'search_tags_excluded' => array(
		'type' => "TEXT",
		'size' => 60,
		'desc' => self::__("Search excluded tags"),
		'tooltip' => self::__("List of comma separated tag names defined in your blog and that will not appear in the list of searchable tags"),
		'checker' => "self::check_string_is_taglist('@VAL')",
		'errmsg' => self::__("The tag exclusion list must contain available tags separated by commas (Search format).")
	),
		
	// search_default_display_mode
	//
	'search_default_display_mode' => array(
		'type' => "SELECT_OR",
		'desc' => self::__("Default search display mode"),
		'tooltip' => self::__("Tag cloud is more compact but does not allow the multi-criteria search provided by the check boxes form.
Tag form offers multiple key search.
Search field manages search based on media name."),
		'list' => array(self::__("Tag cloud"),
						self::__("Tag form"),
						self::__("Search field")
					),
		'checker' => "count(@VAL) > 0",
		'errmsg' => self::__("At least one default display mode needs to be selected (Search format).")
	),

	// search_display_switchable
	//
	'search_display_switchable' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Visitors can switch between available search formats"),
		'tooltip' => self::__("If 'yes' is selected, the result page allows the user to dynamically select the most appropriate search format.
Otherwise, format is fixed to default display style set above.
Javascript must be enabled in the navigator to use this capability"),
		'list' => array(self::__("Yes"),
						self::__("No")
						)
		// ! Choice restricted by list ==> No checker & error message
	),

	// search_num_tags_per_col
	//
	'search_num_tags_per_col' => array(
		'type' => "TEXT",
		'desc' => self::__("Number of tags displayed per column in form mode"),
		'tooltip' => self::__("Set this number to have a convenient columnar display of your tags in the search section, properly spread over the available horizontal space"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("The number of tags displayed by column must be an integer greater than 0 (Search format)."),
		'readonly' => "self::detect_form_column_breaks()"		// readonly status function
	),

	// search_form_font
	//
	'search_form_font' => array(
		'type' => "TEXT",
		'desc' => self::__("Search form font size (pt)"),
		'tooltip' => self::__("Font size to be used for the search form made of check boxes and tags"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("The search form font must be a positive integer expressed in pt (Search format).")
	),
		
	// tagcloud_num_tags
	//
	'tagcloud_num_tags' => array(
		'type' => "TEXT",
		'desc' => self::__("Number of tags displayed in the tag cloud"),
		'tooltip' => self::__("Number of tags selected among the highest ranked displayed in the tag cloud ; put 0 to get all the tags"),
		'checker' => "is_int(@VAL) && @VAL >= 0",
		'errmsg' => self::__("The number of tags displayed in the tag cloud must be an integer greater than 0 ; if 0 all the tags are displayed (Search format)")
	),
	
	// tagcloud_order
	//
	'tagcloud_order' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Tag cloud order"),
		'tooltip' => self::__("Select the appropriate option to have the tags ordered aphabetically, by occurence or randomly, depending on your application.
Note that this ordering is applied after having selected the highest ranking tags according to the parameter just above"),
		'list' => array(self::__("Alphabetical"),
						self::__("Rank"),
						self::__("Random")
						)
		// ! Choice restricted by list ==> No checker & error message
	),
	
	// tagcloud_font_min
	//
	'tagcloud_font_min' => array(
		'type' => "TEXT",
		'desc' => self::__("Tag cloud minimum font size (pt)"),
		'tooltip' => self::__("This parameter sets the font size for the least used tag"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("Tag cloud minimum font size must be a positive integer (Search format)")
	),
	
	// tagcloud_font_max
	//
	'tagcloud_font_max' => array(
		'type' => "TEXT",
		'desc' => self::__("Tag cloud maximum font size (pt)"),
		'tooltip' => self::__("This parameter sets the font size for the most used tag"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("Tag cloud minimum font size must be a positive integer (Search format)")
	),

	// tagcloud_color_min
	//
	'tagcloud_color_min' => array(
		'type' => "TEXT",
		'size' => 8,
		'desc' => self::__("Minimum tag cloud color in hex format"),
		'tooltip' => self::__("Tag cloud dynamic colors : this font color will be used for the tags with the lowest use. Set to -1 to not use dynamic colors"),
		'checker' => "self::check_valid_colorhex('@VAL', true)",
		'errmsg' => self::__("The minimum tagcloud color must be a valid hexadecimal color code as 3ff or a1b160, or -1 for not using dynamic colors (Search format)")
	),

	// tagcloud_color_max
	//
	'tagcloud_color_max' => array(
		'type' => "TEXT",
		'size' => 8,
		'desc' => self::__("Maximum tag cloud color in hex format"),
		'tooltip' => self::__("Tag cloud dynamic colors : this font color will be used for the tags with the highest use. Set to -1 to not use dynamic colors"),
		'checker' => "self::check_valid_colorhex('@VAL', true)",
		'errmsg' => self::__("The maximum tagcloud color must be a valid hexadecimal color code as 3ff or a1b160, or -1 for not using dynamic colors (Search format)")
	),

	// tagcloud_highlight_color
	//
	'tagcloud_highlight_color' => array(
		'type' => "TEXT",
		'size' => 8,
		'desc' => self::__("Tag cloud highlighting font color in hex format"),
		'tooltip' => self::__("This font color is used to highlight the tags selected for a search ; used also for the tag cloud hover effect when dynamic colors are used for the tag cloud.
Set to -1 if you don't want hover effect in your tag cloud with dynamic colors and if you don't want to highlight the selected tag"),
		'checker' => "self::check_valid_colorhex('@VAL', true)",
		'errmsg' => self::__("The tag cloud highlighting font color must be a valid hexadecimal color code as 3ff or a1b160, or -1 for no higlighting effect (Search format)")
	),

	// header
	//
	'header_output_format' => array(
		'type' => "HEADER",
		'desc' => self::__("Output format")
	),
		
	// result_default_display_mode
	//
	'result_default_display_mode' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Default result display mode"),
		'tooltip' => self::__("Select gallery style for a condensed graphical output, title list for pure text.
Images will be scaled in both cases using maximum image pixel width or height specified below"),
		'list' => array(self::__("Image gallery"),
						self::__("Image list"),
						self::__("Title list")
						)
		// ! Choice restricted by list ==> No checker & error message
	),

	// result_display_switchable
	//
	'result_display_switchable' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Visitors can switch between available output formats"),
		'tooltip' => self::__("If 'yes' is selected, the result page allows the user to dynamically select the most appropriate output format.
Otherwise, format is fixed to default output style set above.
Javascript must be enabled in the navigator to use this capability."),
		'list' => array(self::__("Yes"),
						self::__("No")
						)
		// ! Choice restricted by list ==> No checker & error message
	),

	// result_display_optimize_xfer
	//
	'result_display_optimize_xfer' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Optimize thumbnail transfer"),
		'tooltip' => self::__("If 'Yes' is selected, the GD graphic library will be used server side to resize the images to the exact dimension used for displaying in the client browser.
Otherwise, the images will be transferred in full format and directly resized in the browser.
For given server configurations, this optimization does not produce the expected result.
When activating this option, check on your search page that the result gallery or image list is displayed correctly.
If not, disable this option as proposed by default.
This option is selectable only if the GD library is detected on the server (check in the footnote below)."),
		'list' => array(self::__("Yes"),
						self::__("No")
						),
		'readonly' => "!self::\$GD_VERSION"		// readonly status function
		// ! Choice restricted by list ==> No checker & error message
	),

	// header
	//
	'header_gallery_output_format' => array(
		'type' => "HEADER",
		'desc' => self::__("Gallery output format")
	),
		
	// gallery_image_num_per_page
	//
	'gallery_image_num_per_page' => array(
		'type' => "TEXT",
		'desc' => self::__("Number of images per gallery page"),
		'tooltip' => self::__("Number of images to be listed on the search result page in case the display format is set to 'Gallery'."),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("The number of images per page must be positive (Gallery output format)")
	),

	// gallery_image_link_ctrl
	//
	'gallery_image_link_ctrl' => array(
		'type' => "SELECT_XOR",
		'desc' => 	self::__("Link on the gallery thumbnails points to"),
		'tooltip' => self::__("Select 'Plain size image' to link the gallery thumbnails to the full size image.
Otherwise the thumbnails will be linked to the post where the image was posted."),
		'list' => array(self::__("Plain size image"),
						self::__("Post containing the image")
						)
		// ! Choice restricted by list ==> No checker & error message
	),

	// result_img_gallery_w_h
	//
	'result_img_gallery_w_h' => array(			// TBD : change to gallery_img_w_h for naming coherence
		'type' => "TEXT",
		'desc' => self::__("Maximum image pixel width or height"),
		'tooltip' => self::__("Used to scale the images displayed in gallery display mode on the Image Tag Search Result Page. The largest of the (width, height) will be scaled to this number"),
		'checker' => "is_int(@VAL) && @VAL >= 10",
		'errmsg' => self::__("The maximum image pixel width or height must be an integer greater than 10 (Gallery output format)")
	),

	// gallery_image_border_w
	//
	'gallery_image_border_w' => array(			
		'type' => "TEXT",
		'desc' => self::__("Image frame pixel width"),
		'tooltip' => self::__("Image border width used for the gallery display style. If border is set to 0, images are displayed border to border.
In case an image framing plugin or theme is activated, this setting will be generally superseded by the specific framing theme or plugin."),
		'checker' => "is_int(@VAL) && @VAL >= 0",
		'errmsg' => self::__("The image frame pixel width must be an integer greater than 0 (Gallery output format)")
	),

	// gallery_image_border_color
	//
	'gallery_image_border_color' => array(
		'type' => "TEXT",
		'size' => 8,
		'desc' => self::__("Image border color in hex format"),
		'tooltip' => self::__("Image border color used to frame each gallery image. This parameter is significant only if the image border set above is greater than 0.
In case an image framing plugin or theme is activated, this setting will be generally superseded by the specific framing theme or plugin."),
		'checker' => "self::check_valid_colorhex('@VAL')",
		'errmsg' => self::__("The image frame color must be a valid hexadecimal color code as 3ff or a1b160 (Gallery output format)")
	),

	// header
	//
	'header_image_list_output_format' => array(
		'type' => "HEADER",
		'desc' => self::__("Image list output format")
	),
		
	// list_image_num_per_page
	//
	'list_image_num_per_page' => array(			
		'type' => "TEXT",
		'desc' => self::__("Number of images per list page"),
		'tooltip' => self::__("Number of images to be listed on the search result page in case the display format is set to 'Image list'"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("The number of images per page must be positive (Image list output format)")
	),

	// result_img_list_w_h
	//
	'result_img_list_w_h' => array(			// TBD : change to list_img_w_h for naming coherence
		'type' => "TEXT",
		'desc' => self::__("Maximum image pixel width or height"),
		'tooltip' => self::__("Number of image titles to be listed on the search result page in case the display format is set to 'Title list'"),
		'checker' => "is_int(@VAL) && @VAL >= 10",
		'errmsg' => self::__("The maximum image pixel width or height must be an integer greater than 10 (Image list output format)")
	),

	// header
	//
	'header_title_list_output_format' => array(
		'type' => "HEADER",
		'desc' => self::__("Title list output format")
	),
		
	// list_title_num_per_page
	//
	'list_title_num_per_page' => array(			
		'type' => "TEXT",
		'desc' => self::__("Number of image titles per list page"),
		'tooltip' => self::__("Number of images to be listed on the search result page in case the display format is set to 'Image list'"),
		'checker' => "is_int(@VAL) && @VAL > 0",
		'errmsg' => self::__("The number of image titles per page must be positive (Title list output format)")
	),
	
	// header
	//
	'header_misceallenous' => array(
		'type' => "HEADER",
		'desc' => self::__("Misceallenous")
	),
		
	// Audit database integrity
	//
	'database_audit_link' => array(
		'type' => "LINK",
		'desc'	=> self::__("Audit database integrity"),
		'tooltip'=> self::__("This preview function lets you evaluate the inconsistencies detected in your WordPress database.

After clicking this link you will be directed to the preview page. From this page you will then have the choice to run automatic cleanup batches.
This function involves heavy SQL computation. Depending on your hosting plan, this checker can be aborted if you exceed the maximum number of SQL queries allowed in your plan."),
		'text'	=> self::__("Audit"),
		'url'	=> "mdtg_submit('form_display','audit_database')"
		// ! Link ==> No checker & error message
	),
	
	// admin_credit
	//
	'admin_credit' => array(
		'type' => "SELECT_OR",
		'desc' => self::__("Display credit line"),
		'tooltip' => self::__("A possibility is offered to not display the very tiny MediaTagger credit line shown below the search form in plain format (not in widget).
Anyhow, be aware of the very significative work that was required to develop this plugin to push it to what you can enjoy today.
If you decide not to show this line and assuming you like this plugin, I let to your good willingness the possibility to include anywhere else on your site a credit line linking back to my home site (http://www.photos-dauphine.com)"),
		'list' => array(' ')
	)

);

?>