<?php
/*
Plugin Name: MediaTagger
Plugin URI: http://www.photos-dauphine.com/wp-mediatagger-plugin
Description: Extensively configurable plugin packed with a bunch of features enabling media tagging, search and media taxonomy.
Author: www.photos-dauphine.com
Author URI: http://www.photos-dauphine.com/
Version: 4.0.4
Stable Tag: 4.0.4
*/


class wp_mediatagger{
		
	const 	MIN_PHP_VERSION = 50000;
	//const 	DEBUG = 1;
	//const 	DEBUG_SQL_WRITE = 1;

	private static $PHP_VERSION;
	private static $MYSQL_VERSION;
	private static $GD_VERSION;
	private static $SQL_MDTG_TABLE;
	
	private static $PLUGIN_NAME;			// "MediaTagger"
	private static $PLUGIN_NAME_UCF;		// "Mediatagger"
	private static $PLUGIN_NAME_LC;			// "mediatagger"
	private static $PLUGIN_DIR_PATH;		// "/homez.424/photosdab/www/wp-content/plugins/wp-mediatagger/"
	private static $PLUGIN_DIR_URL;			// "http://www.photos-dauphine.com/wp-content/plugins/wp-mediatagger/"
	private static $PLUGIN_DIR_NAME;		// "wp-mediatagger/"
	private static $PLUGIN_VERSION;			// "2.1.1
	private static $PLUGIN_VERSION_STABLE;	// "2.1.0
	
	private static $opt_init;
	private static $opt;
	
	private static $tax;

	private static $form;					// will contain the -form definition file
	private static $t;						// will contain the -def definition file
		
	private $dummy = "0";					// only non static to be able to dump "$this"
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Class constructor 
	//
	// Wordpress events sequence : 
	//		Activation : construct / activation / construct / loading / init / admin notice
	//		Load : construct / loading / init / admin notice
	//
    function __construct() {
		
		// Initialize basic plugin information - mainly naming, version, path
		//
		self::set_plugin_information();	
						
        // Create database structure if plugin activated for the first time
		//
		register_activation_hook(__FILE__, array($this, 'plugin_activation'));
		
		// For potentially erasing database structure
		//
		register_deactivation_hook(__FILE__, array($this, 'plugin_deactivation')); 
		
		// Do all plugin init
		//
		add_action('plugins_loaded', array($this, 'plugin_load'));
		add_action('init', array($this, 'plugin_init'));
		add_action('admin_notices' , array($this, 'admin_message_display'));
		
		if (is_admin()) {
			add_action('admin_menu', array($this, 'add_admin_menu'));
		}
		
		add_action("plugins_loaded", array($this, 'mdtg_widget_init'));
		
		// Load java script
		//
		$javascript_filename = self::$PLUGIN_NAME_LC . '.js';
		wp_register_script($javascript_filename, self::$PLUGIN_DIR_URL . $javascript_filename, false, self::$PLUGIN_VERSION);
		wp_enqueue_script($javascript_filename);

		wp_enqueue_script('jquery');		
		
		//
		// Load CSS
		$css_filename = self::$PLUGIN_NAME_LC . '.css';
		wp_register_style($css_filename, self::$PLUGIN_DIR_URL . $css_filename, false, self::$PLUGIN_VERSION);
		wp_enqueue_style($css_filename);
		
		// Plugin filters
		add_filter('plugin_action_links', array($this, 'action_links'), 10, 2);
		add_filter('the_content', array($this, 'run_shortcode'), 7);		
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Set various plugin information obtained from plugin header
	//
    private function set_plugin_information() {
		global $wpdb;

		// Primary : plugin names, paths
		foreach(array_slice(file(__FILE__), 0, 10) as $line) {
			$expl = explode(':', $line);
			switch (trim(current($expl))) {
				case 'Plugin Name': self::$PLUGIN_NAME = trim(next($expl)); break;
				case 'Version': self::$PLUGIN_VERSION = trim(next($expl)); break;
				case 'Stable Tag': self::$PLUGIN_VERSION_STABLE = trim(next($expl)); break;
			}
		}
		
		self::$PLUGIN_NAME_LC = strtolower(self::$PLUGIN_NAME);
		self::$PLUGIN_NAME_UCF = ucfirst(self::$PLUGIN_NAME_LC);
		self::$PLUGIN_DIR_PATH = plugin_dir_path(__FILE__);
		self::$PLUGIN_DIR_URL = plugin_dir_url(__FILE__);
		self::$PLUGIN_DIR_NAME = basename(self::$PLUGIN_DIR_PATH) . '/';
		
		//	Second : versions, messages, init values
		//
		
		//	Init constants
		//
		self::$PHP_VERSION = phpversion();
		self::$MYSQL_VERSION = mysql_get_server_info();
		self::$GD_VERSION = self::get_gd_version();
		
		self::$SQL_MDTG_TABLE = $wpdb->prefix . self::$PLUGIN_NAME_LC;
													
		//	Init messages and default values
		//
		load_plugin_textdomain(self::$PLUGIN_NAME_LC, false, self::$PLUGIN_DIR_NAME . 'languages/' );		

		$filename_prefix = self::$PLUGIN_DIR_PATH . self::$PLUGIN_NAME_LC;
 
		
 		include_once($filename_prefix . '-form.php');
		self::$form = $form;
																						
		include_once($filename_prefix . '-ini.php');
		self::$opt_init = $opt_init;

		include_once($filename_prefix . '-def.php');
		self::$t = self::translate_i18_strings($t, $filename_prefix . '-form.php');
		//self::print_ro($t);
	}
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Create image taxonomy database structure if not existing
	//
    function plugin_activation(){
		
		self::admin_message_log(self::$t->activation . " :<br/>", true);
		
		// Create table if not existing
		//
		self::check_table_exists(1);
		
		self::load_options($admin_msg);	
		self::admin_message_log($admin_msg);			
	}
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Message for admin panel
	//
	function admin_message_display() {
		$option_name = self::$PLUGIN_NAME_LC . '_admin_message';

		if ($msg = get_option($option_name)) {
			update_option($option_name, '');	// reset message to avoid re-display
			echo '<div class="updated"><p>' . $msg . '</p></div>';
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Image taxonomy database structure should be deleted in this function
	//
    function plugin_deactivation(){
		// Inform that DB is not deleted, should be done manually in case needed
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Called before plugin_init
	//
   	function plugin_load(){

		//	Load plugin options
		//
		self::admin_message_debug(self::$t->loading, true);
		self::load_options($admin_msg);
		self::admin_message_debug($admin_msg);

		//	Load taxonomy
		//		
		self::taxonomy_update();

		//d($this);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Logger for messages
	//
	private function admin_message_log($msg, $init=false, $debug=false){
		$option_name = self::$PLUGIN_NAME_LC . '_admin_message';
		$buffer = get_option($option_name);
		if (is_admin() && ($debug ? defined('self::DEBUG') : true) ) {
			if ($init || buffer == '') {
				$buffer = self::$PLUGIN_NAME . ' v' . self::$PLUGIN_VERSION . ' - ' .$msg;
			} else { // cumulate
				$buffer .= ' - ' . $msg;
			}
			update_option($option_name, $buffer);
		}
	}

   	private function admin_message_debug($msg, $init=false){
		self::admin_message_log($msg, $init, true);
	}
			 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Init plugin options - Create entry in database if needed ; perform database upgrade if required due to former version
	//
    function plugin_init(){
		//d("plugin init");
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin menu function 
	//
	function add_admin_menu(){
				
		add_utility_page(self::$PLUGIN_NAME, self::$PLUGIN_NAME, "manage_options", self::$PLUGIN_NAME_LC, array($this, 'manager_page'),
			self::$PLUGIN_DIR_URL . 'images/menu.png');
		add_submenu_page(self::$PLUGIN_NAME_LC, self::$t->explorer, self::$t->explorer, "manage_options", self::$PLUGIN_NAME_LC, array($this, 'manager_page'));
		add_submenu_page(self::$PLUGIN_NAME_LC, "Options", "Options", "manage_options", self::$PLUGIN_NAME_LC .'_options', array($this, 'options_page'));
		add_submenu_page(self::$PLUGIN_NAME_LC, "Player", "Player", "manage_options", self::$PLUGIN_NAME_LC .'_database', array($this, 'player_page'));
		if (defined('self::DEBUG')) {
			add_submenu_page(self::$PLUGIN_NAME_LC, "______ self::\$opt", "______ self::\$opt", "manage_options", self::$PLUGIN_NAME_LC .'_dump_opt', array($this, 'dump_opt_page'));
			add_submenu_page(self::$PLUGIN_NAME_LC, "______ self::\$t", "______ self::\$t", "manage_options", self::$PLUGIN_NAME_LC .'_dump_def', array($this, 'dump_def_page'));
			add_submenu_page(self::$PLUGIN_NAME_LC, "______ self::\$form", "______ self::\$form", "manage_options", self::$PLUGIN_NAME_LC .'_dump_form', array($this, 'dump_form_page'));
			add_submenu_page(self::$PLUGIN_NAME_LC, "______ self::\$tax", "______ self::\$tax", "manage_options", self::$PLUGIN_NAME_LC .'_dump_tax', array($this, 'dump_tax_page'));
		}
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Init plugin options
	//
	private function load_options(&$admin_msg){
		
		// Plugin options :								
		// Try first to read options from single entry to detect if plugin upgraded to inline options
		$options = get_option(self::$PLUGIN_NAME_LC);
		
		//$force_serialize = 1;	// set to 1 for testing
		if ($options != '' && !$force_serialize) {	// options available 'inline' - read it
			$admin_msg .= self::$t->plugin_options_detected_serial;			
			
		} else {	// no single entry detected - try first to detect old fashion options
			if (get_option('wpit_admin_num_tags_per_col') != '') {	// old fashion detected : read and convert
				$admin_msg .= self::$t->plugin_options_detected_itemized;
				
				foreach(self::$opt_init as $opt_name => $opt_default){
					$options[$opt_name] = self::get_option_safe($opt_name);
				}
			} else {	// no old fashion detected : keep default value initialized by constructor
				$admin_msg .= self::$t->plugin_options_not_detected;				
				$options = self::$opt_init;
			}
		}
		
		// Check the options queried from DB match those required by this plug version, provided by the opt_init table
		if (count(array_diff_key(self::$opt_init, $options))) {	// if not, scan initialize the missing options with default value
			foreach(self::$opt_init as $opt_name => $opt_default)
				self::$opt[$opt_name] = (array_key_exists($opt_name, $options) ? $options[$opt_name] : $opt_default);		
		} else {	// Otherwise integral copy
			self::$opt = $options;	
		}
		
		//	Check options coherence 
		//
		self::check_option_coherence();
		
		// Save to database in case of fix at loading
		update_option(self::$PLUGIN_NAME_LC, self::$opt);
		//print_r(self::$opt);
		
	}	

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Retrieve options from itemized format - convert if needed (see 'switch' cases)
	//	
	private function get_option_safe($option_name) {
		if (($option_val = get_option('wpit_' . $option_name, "option_not_found")) != "option_not_found") {
			// convert from legacy itemized to serial
			switch ($option_name) {
				case 'admin_media_formats':		// map to index
					$format_list = explode(',', $option_val);
					$list = array();
					for ($i=0; $i < count(self::$form[$option_name]['list']) ; $i++) {
						if (in_array(self::$form[$option_name]['list'][$i], $format_list)) 
							$list[] = $i+1;	//self::$opt_init[$option_name][$i];
					}
					$option_val = $list;					
					break;
				case 'admin_credit':		// convert from string to array
					$option_val = array(1);
					break;
				case 'search_default_display_mode':	// map to index
					$list = array();
					for ($i=0; $i < count(self::$opt_init[$option_name]) ; $i++) {
						if ($option_val & (1<<$i)) 
							$list[$i] = $i+1;	//self::$opt_init[$option_name][$i];
					}
					$option_val = $list;
					break;
				case 'admin_override_post_taxonomy':		// convert 0->2 and 1->1
				case 'search_display_switchable':		
				case 'result_display_optimize_xfer':		
				case 'result_display_switchable':		
				case 'gallery_image_link_ctrl':		
					$option_val = ($option_val ? 1 : 2);
					break;
				case 'tagcloud_order':
					$option_val++;
					break;
			}
			return $option_val;
		}
		return self::$opt_init[$option_name];	// if not found
	}
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Update taxonomy table 
	//
	private function taxonomy_update(){
		global $wpdb;
		$tax_tags = array();
		$tax_cats = array();	
		$tag_source = self::$opt['admin_tags_source'];
		$tag_groups = self::$opt['admin_tags_groups'];
		
		if ($tag_source <= 2) {	// select only tags, or tags and categories	
			$sql_query = 'SELECT term_taxonomy_id, tax.term_id, slug, name '.
						 'FROM ' . $wpdb->term_taxonomy . ' AS tax INNER JOIN ' . $wpdb->terms . ' AS ter ON tax.term_id = ter.term_id ' .
						 'WHERE tax.taxonomy = "post_tag" ';
						 
			$tax_tags = self::run_mysql_query($sql_query);
			array_walk($tax_tags, array($this, 'walk_add_category'), self::__('Tags'));
		}

		if ($tag_source >= 2) {	// only select categories, or tags and categories
			$sql_query = 'SELECT term_taxonomy_id, tax.term_id, slug, name '.
						 'FROM ' . $wpdb->term_taxonomy . ' AS tax INNER JOIN ' . $wpdb->terms . ' AS ter ON tax.term_id = ter.term_id ' .
						 'WHERE tax.taxonomy = "category" ';
		
			$tax_cats = self::run_mysql_query($sql_query);
			array_walk($tax_cats, array($this, 'walk_add_category'), self::__('Categories'));
		}
	
		self::$tax = array_merge($tax_tags, $tax_cats);
		// self::$tax = array();	// uncomment to simulate no tag in the blog
		if (!self::$tax)
			return 0;	

		// Sort tags alphabetically - this sort will be the one used for the tag form if no groups are defined for the tags
		uasort(self::$tax, array($this, 'cmp_objects_lexicography'));
		
		// Build tag groups as defined in the admin interface
		self::build_tag_groups($tag_groups);
		self::taxonomy_stats_update();
		return 1;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Make stats on tags ; add it as a 'count' field to the tag structure
	//
	private function taxonomy_stats_update(){
		global $wpdb;
		
		foreach(self::$tax as $key=>$tax){
			$sql_query = 'SELECT * '.
				'FROM ' . self::$SQL_MDTG_TABLE . ' ' .
				'WHERE term_taxonomy_id = ' . $tax->term_taxonomy_id;
			$sql_query_result = self::run_mysql_query($sql_query);
			$tax->count = sizeof($sql_query_result);
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Check minimum PHP version
	//
	private function check_php_version(){	
		if (self::get_php_version() < self::MIN_PHP_VERSION) { 
			self::user_message(self::$t->php_version_outdated, self::MIN_PHP_VERSION/10000, self::$PLUGIN_NAME);
			self::user_message(self::$t->php_version_current, phpversion());
			return true;
		}
		return false;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	Retrieve PHP_VERSION_ID ; if does not exist (<5.2.7), emulate
	//
	private function get_php_version() {
		if (!defined('PHP_VERSION_ID')) {	// defined starting 5.2.7
			$version = explode('.', PHP_VERSION);
			define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
		}
		// PHP_VERSION_ID is defined as $major_version * 10000 + $minor_version * 100 + $release_version;
		if (PHP_VERSION_ID < 50207) {
			define('PHP_MAJOR_VERSION',   $version[0]);
			define('PHP_MINOR_VERSION',   $version[1]);
			define('PHP_RELEASE_VERSION', $version[2]);
		}
		
		return PHP_VERSION_ID;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	Get gd_version
	//
	private function get_gd_version() {
		if (function_exists("gd_info")){
			$gd_info = gd_info();
			$gd_version = $gd_info['GD Version'];
		} else {
			$gd_version = 0;
		}
		return $gd_version;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : browser
	//
	//	TBD : thumbnail, tag
	//
	function manager_page(){	
		if (self::check_php_version()) return;
		if (!self::$tax) {  
			self::user_message(self::$t->no_tag_detected);
			return;
		}
		//self::print_ro($_POST);
		
		$submit_type = $_POST['mdtg_submit_list'];
		$view = ($_POST['mdtg_view'] ? $_POST['mdtg_view'] : 'Explorer');
		
		if (($view == 'Explorer' && $submit_type != self::$t->tag_) || $submit_type == 'Explorer') {
			self::Explorer_page();
		} else {
			self::editor_page($submit_type);
		}
				
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Manage media tag setting for single and bulk tagging
	//
	private function manage_media_tags($media_list, $submit_type){
	
		$new_tag_list = (strstr($submit_type, self::$t->clear_) ? array() : $_POST['mdtg_tags']);
		
		foreach ($media_list as $media_id) {
			self::set_media_tags($media_id, $new_tag_list);
			
			$media_info = self::get_media_info($media_id);
			$treat_post_tags = (self::$opt['admin_override_post_taxonomy'] == 1) && (get_post_type($media_info->post_ID) == 'post');
			if ($treat_post_tags) {	// Update post taxonomy from image taxonomy
				$auto_post_tags = self::update_post_tags($media_info->post_ID, 1, $updt_required);
			}
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : editor
	//
	private function editor_page($submit_type){
		
		echo "<h1>" . self::$PLUGIN_NAME . " - " . self::$t->editor . "</h1>";

		$submit_type = $_POST['mdtg_submit_list'];
		$view = $_POST['mdtg_view'];
		$custom_list = ($_POST['mdtg_custom_list'] ? explode(',', $_POST['mdtg_custom_list']) : array());
		$list_select = ($_POST['mdtg_select'] ? $_POST['mdtg_select'] : array());
		
		if (count($list_select) > $_POST['mdtg_display_depth'])
			$list_select = array_slice($list_select, 0, $_POST['mdtg_display_depth']);
		
		if ($_POST['mdtg_media_list'])
			$list = explode(',', $_POST['mdtg_media_list']);
		else {		// coming from explorer view
			$list = $list_select;							// selectable medias = those selected in the explorer view
			$list_select = array(current($list_select));	// select first element when coming from explorer
			$_POST['mdtg_display_start'] = 0;
		}
			
		//self::print_ro($list);	
		//self::print_ro($list_select);	
		
		self::manage_explorer_window($list, $list_select, $media_displayed_list, $display_start, $display_depth, 0);
		if (!$list_select)
			$list_select = ($media_displayed_list ? array(current($media_displayed_list)) : array());
		
		//self::print_ro($media_displayed_list);	
		//self::print_ro($list_select);	
		
		$num_selected_media = count($list_select);
		if ($num_selected_media > 1 && ($submit_type == self::$t->tag_ || $submit_type == self::$t->clear_) ) 
			$submit_type = 'Group ' . $submit_type;
		?>
        
     	<form name="mdtg_form" method="post" action="">
            <input type="hidden" name="mdtg_view" value="Editor">
			<input type="hidden" name="mdtg_media_list" value="<?php echo implode(',', $list) ?>" />
            <input type="hidden" name="mdtg_custom_list" value="<?php echo implode(',', $custom_list) ?>">

		<?php
		$button_list = array('<', self::$t->tag_and_previous_, self::$t->tag_, self::$t->tag_and_next_, '>', 'spacer', self::$t->clear_, 'spacer', self::$t->explorer_);
		
		if (!$list_select) {
			self::user_message(self::$t->select_media_before_tagging);
			$button_list_disable = array('<', self::$t->tag_and_previous_, self::$t->tag_, self::$t->tag_and_next_, '>', self::$t->clear_);
		} else {	
			$media_id = current($list_select);
			//self::print_ro($media_id);	
						
			switch($submit_type) {
				case self::$t->clear_ :
				case self::$t->tag_ :
				case self::$t->tag_and_previous_ :
				case self::$t->tag_and_next_ :
				case "<" :
				case ">" :
					if ((strstr($submit_type, self::$t->tag_) && $view == self::$t->editor_) || ($submit_type == self::$t->clear_)){	// tag media_id	
						self::manage_media_tags(array($media_id), $submit_type);
					}
					if (strstr($submit_type, ">") || strstr($submit_type, "<"))  {	
						$key = array_search($media_id, $list);
						if (strstr($submit_type, ">"))
							$media_id = $list[$key+1];
						if (strstr($submit_type, "<"))
							$media_id = $list[$key-1];
						$list_select = array($media_id);
					}
					break;
					
				case "Group " . self::$t->tag_ :										// tag list_select
				case "Group " . self::$t->clear_ :										// tag list_select
					self::manage_media_tags($list_select, $submit_type);
					break;
			}
			
			//self::print_ro($media_id);	
				
			$button_list_disable = array();
			switch ($num_selected_media) {
				case 1 :	// 1 media selected
					// display media info & tags	
					//self::print_ro($media_id);
					$media_info = self::get_media_info($media_id);
					$media_tags = self::get_media_tags($media_id);
					//self::print_ro($media_info);
					//self::print_ro($media_tags);
					echo '<div style="margin:20px;float:left"><img src="' . $media_info->image . '" height="' . self::$opt['admin_img_height'] . '" ></div>';
					//echo '<div style="margin:20px;float:left"><img src="' . $media_info->image . '" ' . ($media_info->w < $media_info->h ? 'height="' : 'width="') . self::$opt['admin_img_height'] . '" ></div>';
					echo '<div style="padding:20px;">';
					echo '<i>' . self::$t->file . ' : </i>' . basename($media_info->url) . '<br/>';
					echo '<i>Description : </i>' . $media_info->title  . '<br/>';
					echo '<i>Type : </i>' . $media_info->mime  . '<br/>';
					echo '<i>Post : </i>' . $media_info->post_title  . '<br/>';
					//self::print_ro($media_info);
					echo '</div>';
					
					// Display tags
					echo '<div style="clear:both">' . self::print_tag_form($media_tags, 1) . '</div>';
					
					// configure buttons

					//$button_list = array('<', '< Tag', 'Tag', 'Tag >', '>', 'spacer', 'Clear', 'spacer', 'Explorer');
					$button_list_disable = array();
					//if ($key >= count($list) - 1 ) 	$button_list_disable = array('Tag >', '>');
					if ($media_id >= end($media_displayed_list)) 
						$button_list_disable = array(self::$t->tag_and_next_, '>');
					if ($media_id <= $media_displayed_list[0]) 
						$button_list_disable = array_merge($button_list_disable, array('<', self::$t->tag_and_previous_));					
					
					break;
					
				default :	//  processing lot
					$media_tags = self::get_media_tags_group($list_select);
					//self::print_ro($media_tags);
	
					echo $num_selected_media . " media selected for group tagging";
	//				self::print_ro($list_select);
					
					echo '<div style="margin:20px">';
					
					foreach($list_select as $media_id) {
						$media_info = self::get_media_info($media_id);
						echo '<img style="padding-right:4px" ' . self::get_optimized_thumbnail($media_info, self::$opt['admin_img_group_height']) . '>';
					}
					echo '</div>';
	
					// Display tags
					echo '<div style="clear:both">' . self::print_tag_form($media_tags, 1) . '</div>';
				
					// configure buttons
					$button_list_disable = array('<', self::$t->tag_and_previous_, self::$t->tag_and_next_, '>');
			}
		}

		//self::print_media_list($list, $list_select, $button_list, false, $button_list_disable, 1, 1);
		self::print_media_list(count($list), $media_displayed_list, $list_select, $display_start, $display_depth, $button_list, $button_list_disable, 1, 1);
		?>
        </form>
        <?php

	}

	////////////////////////////////////////////////////////////////
	// Print tag form
	//
	private function print_tag_form($checked_tags, $admin_page = 0) {
		//global $g_imgt_tag_taxonomy; // self::$tax
		$strout = '';
		
		if ($admin_page) {
			$num_tags_per_col = self::$opt['admin_num_tags_per_col'];	//admin_get_option_safe('wpit_admin_num_tags_per_col', WPIT_ADMIN_INIT_NUM_TAGS_PER_COL);
			$tags_excluded = self::$opt['admin_tags_excluded'];	//admin_get_option_safe('wpit_admin_tags_excluded', WPIT_ADMIN_INIT_TAGS_EXCLUDED);
		} else {	// search page
			$num_tags_per_col = self::$opt['search_num_tags_per_col'];	//admin_get_option_safe('wpit_search_num_tags_per_col', WPIT_SEARCH_INIT_NUM_TAGS_PER_COL);
			$tags_excluded = self::$opt['search_tags_excluded'];	//admin_get_option_safe('wpit_search_tags_excluded', WPIT_SEARCH_INIT_TAGS_EXCLUDED);
		}
		
		if (!count($checked_tags))
			$checked_tags = array();
	
		$multiple_groups = 0;
		$admin_tags_source = self::$opt['admin_tags_source'];	//admin_get_option_safe('wpit_admin_tags_source', WPIT_ADMIN_INIT_TAGS_SOURCE);
		$admin_tags_groups = self::$opt['admin_tags_groups'];	//admin_get_option_safe('wpit_admin_tags_groups', WPIT_ADMIN_INIT_TAGS_GROUPS);
		$admin_background_color = self::$opt['admin_background_color'];	//admin_get_option_safe('wpit_admin_background_color', WPIT_ADMIN_INIT_BACKGROUND_COLOR);
		if ($admin_tags_source == 2 || strlen($admin_tags_groups)>0)
			$multiple_groups = 1;
		$manual_col_brk = self::detect_form_column_breaks();
			
	// phdbg($admin_tags_groups);
	// phdbg(self::$tax);
	
		$group = '';
		$new_group = 0;
		$tag_displayed_count = 0;
		foreach (self::$tax as $key=>$tax_item) {
			if ($tax_item->category != $group) {	// detect group transition
				$group = $tax_item->category;
				$new_group = 1;
			} else {
				$new_group = 0;	
			}
			
			if ($multiple_groups && $new_group) {
				if (!(($tag_displayed_count+1) % $num_tags_per_col)) {	// avoid to have group name at column bottom with first element on next col
					$tag_displayed_count++;
				} else if (!(($tag_displayed_count+2) % $num_tags_per_col)) { 	// avoid to have group name at column bottom with second element on next col
					$tag_displayed_count+=2;
				}
				if (($manual_col_brk && (isset($tax_item->group_break) || !$tag_displayed_count)) || 
						(!$manual_col_brk && !($tag_displayed_count % $num_tags_per_col))){	// start new col on modulo
					if ($tag_displayed_count) $strout .=  '</div >';
					$strout .= '<div style="float:left">';
				}
				if ($admin_page)
					$strout .= '<span style="background-color:#' . $admin_background_color . ';font-weight:bold">' . $group . "</span><br/>";
				else 
					$strout .= '<p style="padding:1px;margin:1px;background-color:#' . $admin_background_color . ';font-style:italic">&nbsp;' . $group . '</p><p style="margin:0">';				
				$tag_displayed_count++;
			}
			
			if (!$manual_col_brk && !($tag_displayed_count % $num_tags_per_col)){	// start new col on modulo
				$not_last_group_tag = 0;
				$is_last_tag = (end(self::$tax) == $tax_item ? 1 : 0);	// last tag of the taxonomy
				if (!$is_last_tag) {
					if (self::$tax[(int)($key+1)]->category  == $group)
						$not_last_group_tag = 1;
				}
				if (!$multiple_groups || $not_last_group_tag){	// tag is not the last of its group
					if ($tag_displayed_count) $strout .= '</p></div >';
					$strout .= '<div style="float:left">';
				} else
					$tag_displayed_count--;		// avoid to have a tag belonging to a group alone at the top of the next column
			}
					
			if (self::is_tag_name_excluded($tags_excluded, $tax_item->name)) continue;
			
			if  ($admin_page) {
				$checked = in_array($tax_item, $checked_tags);	// $checked = in_array_fast($tax_item, $checked_tags);
			} else {	// search page
				//print_ro($checked_tags);
				$checked = in_array($tax_item->term_taxonomy_id, $checked_tags);		
			}
			$strout .= '<input type="checkbox" value=' . $tax_item->term_taxonomy_id . " name=mdtg_tags[]" . ($checked? " checked" : "") . '> ' . 
				($checked ? '<span style="color:#00F">' : "") .	$tax_item->name . ($checked ? "</span>" : "") . 
				'<span style="font-size:0.7em;color:#999" title="' . $tax_item->count . ' ' . 
				self::n('media_associated_tag', $tax_item->count) . ' : ' . $tax_item->name . '"> ' . $tax_item->count . "&nbsp;</span><br />";
			$tag_displayed_count++;
		}
	//phdbg($strout);
		return $strout;
	}

	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : explorer
	//
	private function explorer_page(){
			
		echo "<h1>" . self::$PLUGIN_NAME . " - " . self::$t->explorer . "</h1>";
		
		$list_type_desc = array('media_all' => self::$t->all_media, 'media_tagged' => self::$t->tagged_media, 'media_untagged' => self::$t->untagged_media, 
			'custom_list' => self::$t->list_media, 'post' => 'post', 'search' => 'search result');

		$search_keyword = $_POST['mdtg_search'];
		$list_select = ($_POST['mdtg_select'] ? $_POST['mdtg_select'] : array());
		$custom_list = ($_POST['mdtg_custom_list'] ? explode(',', $_POST['mdtg_custom_list']) : array());
		$list_type = $_POST['mdtg_list_type'];
		$list_type = ($list_type && !($list_type == 'search' && !$search_keyword)  ? $list_type : 'media_all');	// media_all || media_tagged || media_untagged || list || post
		if ($list_type != 'search') $search_keyword = '';

		// Manage custom list
		switch($_POST['mdtg_submit_list']) {
			case self::$t->add_to_list_ :
				$custom_list = array_unique(array_merge($custom_list, $list_select));
				$list_type = 'custom_list';
				break;
			case self::$t->remove_from_list_ :
				$custom_list = array_unique(array_diff($custom_list, $list_select));
				$list_type = 'custom_list';
				break;
			case self::$t->reset_list_ :
				$custom_list = array();
				$list_select = array();
				break;
		}
		sort($custom_list);
		//if ($custom_list) self::print_ro($custom_list);
		
		// Select the list to be displayed
		$display_all = 0;
		switch($list_type) {
			case 'custom_list' :
				$media_list = $custom_list;
				self::get_media_list('media_count', $count, $reason);
				break;
			case 'post' :
				$post_ID = $_POST['mdtg_post_ID'];
				$media_list = array_keys(get_attached_media('', $post_ID));
				sort($media_list);
				self::get_media_list('media_count', $count, $reason);
				break;
			case 'search' :
				$display_all = 1;
				self::get_media_list('media_count', $count, $reason);
				$media_list = self::search_keyword($search_keyword);
				break;
			default :	// media_all || media_tagged || media_untagged 
				$media_list = self::get_media_list($list_type, $count, $reason);
				if ($list_type == 'media_all' && !$count->total) {
					self::user_message(self::$t->no_media_detected);
					return;
				}
				break;
			// already sorted
		}
		//if ($media_list) self::print_ro($media_list);
		?>
        
        <form name="mdtg_form" method="post" action="">
        	<input type="hidden" name="mdtg_view" value="Explorer">
        	<input type="hidden" name="mdtg_list_type" value="<?php echo $list_type ?>">
        	<input type="hidden" name="mdtg_custom_list" value="<?php echo implode(',', $custom_list) ?>">
        	<input type="hidden" name="mdtg_post_ID" value="<?php echo $post_ID ?>">
            <?php echo self::$t->displaying ?> : <b><?php echo $list_type_desc[$list_type] . ' (' . count($media_list) . ')'; ?></b><br/><?php echo self::$t->view ?> : &nbsp;
			<a href="" onClick="mdtg_submit('mdtg_list_type','media_all');return false;" title="<?php echo self::$t->list_all_media ?>"><?php echo self::$t->all_media ?></a> (<?php echo $count->total ?>) &nbsp;
			<a href="" onClick="mdtg_submit('mdtg_list_type','media_tagged');return false;" title="<?php echo self::$t->list_tagged_media ?>"><?php echo self::$t->tagged_media ?></a> (<?php echo $count->tagged ?>) &nbsp;
			<a href="" onClick="mdtg_submit('mdtg_list_type','media_untagged');return false;" title="<?php echo self::$t->list_untagged_media ?>"><?php echo self::$t->untagged_media ?></a> (<?php echo $count->untagged ?>) &nbsp;
			<a href="" onClick="mdtg_submit('mdtg_list_type','custom_list');return false;" title="<?php echo self::$t->list_media_custom ?>"><?php echo self::$t->list_media ?></a> (<?php echo count($custom_list) ?>) &nbsp;
            <input type="text" name="mdtg_search" title="<?php echo self::$t->search_all_media ?>" value="<?php echo $search_keyword ?>" onkeydown="if (event.keyCode == 13) {mdtg_submit('mdtg_list_type','search');return false;}" />
            
		<?php
		$button_list_disable = array();
		
		self::manage_explorer_window($media_list, $list_select, $media_displayed_list, $display_start, $display_depth, $display_all);
		
		if (!$list_select || !array_intersect($media_displayed_list, $list_select)) $button_list_disable = array_merge($button_list_disable, array(self::$t->tag_, self::$t->add_to_list_, self::$t->remove_from_list_));
		if (!$custom_list) $button_list_disable = array_merge($button_list_disable, array(self::$t->reset_list_));

		self::print_media_list(count($media_list), $media_displayed_list, $list_select, $display_start, $display_depth, 
			array(self::$t->tag_, "spacer", self::$t->add_to_list_, self::$t->remove_from_list_, self::$t->reset_list_), $button_list_disable);
		?>
        </form>
        <?php
		
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	// Display media list for explorer
	//
	private function manage_explorer_window($media_list, &$list_select, &$media_displayed_list, &$display_start, &$display_depth, $display_all) {
	
		$display_start = ($_POST['mdtg_display_start'] ? $_POST['mdtg_display_start'] : 0);
		$display_depth = ($_POST['mdtg_display_depth'] ? $_POST['mdtg_display_depth'] : 20);
		if ($display_start < 0) $display_start = 0;
		if ($display_depth < 1) $display_depth = 1;
		if ($display_all) $display_depth = count($media_list);
		if ($display_depth > self::$opt['admin_max_img_page']) $display_depth = self::$opt['admin_max_img_page'];
		$media_displayed_list = array_slice($media_list, $display_start, $display_depth);
		if (!$media_displayed_list) {
			$display_start = 0;
			$display_depth = 20;
			$media_displayed_list = array_slice($media_list, $display_start, $display_depth);			
			self::user_message(self::$t->no_media_range);
		}
		$list_select = array_intersect($media_displayed_list, $list_select);
	
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	// Display media list for explorer
	//
	//private function print_media_list($media_list, $list_select, $button_group = array(), $display_all = 0, $button_list_disable = array(), $submit_checkboxes = 0, $is_editor = 0) {
	private function print_media_list($num_media, $media_displayed_list, $list_select, $display_start, $display_depth, $button_group = array(), $button_list_disable = array(), $submit_checkboxes = 0, $is_editor = 0) {
			
		$select_counter = count($list_select);

		$custom_list = ($_POST['mdtg_custom_list'] ? explode(',', $_POST['mdtg_custom_list']) : array());
		$var_arg = ($submit_checkboxes ? -1 : count($custom_list));

		//self::print_ro($media_list);
		//self::print_ro($media_displayed_list);
		//self::print_ro($list_select);
		
		//$toggle = ($_POST['mdtg_select0'] ? $_POST['mdtg_select0'] : 0);
		$toggle = (count($list_select) > 0 && !array_diff($media_displayed_list, $list_select) ? 1 : 0);
		?>
        
		<input type="submit" name="mdtg_return" style="position:absolute;left:-9999px;width:1px;height:1px;"/><br/>
        
		<div style="padding-top:10px;clear:both">
			<div style="float:left;width:584px">
				<div style="width:80px; float:left"><input type="checkbox" name="mdtg_select_master" onClick="mdtg_toggle(this, <?php echo $var_arg?>)" <?php echo ($toggle ? 'checked' : '') ?> />&nbsp;
					<label id="mdtg_list_count"><?php echo $select_counter; /*echo str_repeat("&nbsp;", 17)*/ ?></label></div> 
    	<?php 
			foreach ($button_group as $button) {
				if ($button == "spacer") echo " &nbsp; &nbsp; ";
				else echo '<button type="submit" name="mdtg_submit_list" value="' . $button . '" ' . 
					(in_array($button, $button_list_disable) && $button_list_disable ? 'disabled' : '') . ' >' . self::__($button) . '</button>';
//				else echo '<input type="submit" name="mdtg_submit_list" value="' . $button . '" ' . 
//					(in_array($button, $button_list_disable) && $button_list_disable ? 'disabled' : '') . ' />';
			}
			echo '</div><div>';
		?>
				<?php echo self::$t->display ?> <input type="text" name="mdtg_display_depth" value="<?php echo $display_depth ?>" size="4" title="<?php echo self::$t->display_depth ?>"/> <?php echo self::$t->media_starting_from ?> <input type="text" name="mdtg_display_start" value="<?php echo $display_start ?>" size="4" title="<?php echo self::$t->start_display_index ?>"/>
			</div>
		</div>
		<?php
						
		//if (!$media_list) echo "<br/>Media list empty.<br/>";
		//$display_stop = min($display_start + $display_depth, count($media_list));
		$display_depth = min($display_depth, $num_media - $display_start);
		
		$bckcol = "f0f0f0";
		//for($i = $display_start; $i < $display_stop; $i++) {
		for($i = 0; $i < $display_depth; $i++) {
			//$media_id = $media_list[$i];
			$media_id = $media_displayed_list[$i];
			$media_info = self::get_media_info($media_id);
			$media_tags = self::get_media_tags($media_id);
						
			$media_title = $media_info->title;
			if (strlen($media_title) > 50) $media_title = substr($media_title,0, 50) . '...'; // shorten name if too long
			$post_url = '<a href="" . onClick="mdtg_submit(\'mdtg_list_type\',\'post\', 1);mdtg_submit(\'mdtg_post_ID\',' . $media_info->post_ID . ');return false;" ' . 
				'title="View media attached to post" style="color:#889;">' . $media_info->post_title . '</a>';
			$post_title = ($media_info->post_ID < 0 ? '<em>(' . self::$t->orphean_media . ')</em>' : ($is_editor ? $media_info->post_title : $post_url));
			
			$bckcol = ($bckcol == "ffffff" ? "f0f0f0" : "ffffff");
			echo '<div class="media_list" style="background-color:#' . $bckcol . ';">' . 
				'<p style="width:25px;background-color:#f0f0f0"><input type="checkbox" style="vertical-align:sub" name="mdtg_select[]" value="' . 
				$media_id . '" ' . (in_array($media_id, $list_select) ? 'checked ' : ' ') . 'onclick="mdtg_manage_checkboxes(' . $var_arg . ');"' . '></p>' .
				'<p style="width:60px;"><img ' . self::get_optimized_thumbnail($media_info, 31) . '></p>' .
				'<p style="width:500px;">' . $post_title . " : " . $media_title . '</p>' .
				'<p>';
			foreach($media_tags as $key=>$tag) {
				if ($key) echo ", ";
					echo $tag->name; 
				}
			if (!count($media_tags)) echo "<i>" . self::$t->no_tag_associated . "</i>";
			echo '</p></div>';
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Return thumbnail, optimized to the requested size if the image magic library is available
	//
	private function get_optimized_thumbnail($media_info, $img_h) {
		
		$img_w = round($media_info->w * $img_h / $media_info->h);
		
		if (self::$opt['result_display_optimize_xfer'] == 1)
			$img_html = 'src="' . self::$PLUGIN_DIR_URL . 'thumbnail.php?s=' . $media_info->image . '&w=' . $img_w . '&h=' . $img_h . '" ';
		else
			$img_html = 'src="' . $media_info->image . '" ';
		$img_html .= 'width="' . $img_w . '" height="' . $img_h . '" ';
		return $img_html; 
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Return array of tag objects corresponding to img_id
	// If no tag assigned to the media, return empty array
	//
	private function get_media_tags($media_id){
		global $wpdb;
		$tags = array();
			
		$sql_query = 'SELECT term_taxonomy_id '.
					 'FROM ' . self::$SQL_MDTG_TABLE . ' AS img ' .
					 'WHERE img.object_id = ' . $media_id . ' ' .
					 'ORDER by term_taxonomy_id';
		$tax = self::run_mysql_query($sql_query);
		
		foreach($tax as $media_tax){
			$tags[] = self::get_tag_descriptors('term_taxonomy_id=' . $media_tax->term_taxonomy_id);
		}
		
		return $tags;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Get tags for a list of media
	//
	private function get_media_tags_group($list) {
		$tags = array();
		foreach($list as $media_id) $tags = array_merge($tags, self::get_media_tags($media_id));
		$tags = self::array_unique_obj($tags);
		return $tags;
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	// Set tags based on taxonomy array for media media_id
	//
	private function set_media_tags($media_id, $taxonomy_term_id) {
		global $wpdb;
		
		// Clear previously set tags (if already existing)
		$sql_query = 'DELETE FROM ' . self::$SQL_MDTG_TABLE . ' ' .
					 'WHERE ' . self::$SQL_MDTG_TABLE . '.object_id = ' . $media_id;
		self::run_mysql_query($sql_query, 1);
		
		// If tag list not empty : set new tags (otherwise : it is a reset
		if (!empty($taxonomy_term_id)){
			// Build SQL new values list
			$sql_string_values = '(' . $media_id. ',' . implode('),('.$media_id.',', $taxonomy_term_id) . ')';
			
			// set new tags on $img_id
			$sql_query = 'INSERT INTO ' . self::$SQL_MDTG_TABLE . ' ' .
						 '(`object_id`, `term_taxonomy_id`) VALUES ' . $sql_string_values;
			self::run_mysql_query($sql_query, 1);
			}
		// update taxonomy stats
		self::taxonomy_stats_update();
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Retrieve information relative to media : title, relative path to media, 
	// W and H, post title, post URI
	//
	function get_media_info($obj_id) {
		global $wpdb;
		$media_info = new StdClass;
		$icon_path = self::$PLUGIN_DIR_URL . 'images/';
			
		$media_info->title = get_the_title($obj_id);
		$media_info->mime = get_post_mime_type($obj_id);
		$media_info->url = wp_get_attachment_url($obj_id);
	
		switch($media_info->mime) {
			case "image/jpeg":
			case "image/gif":
			case "image/png":
				$media_info->image = 	$media_info->url;
				break;
			case "application/pdf":
				$media_info->image = self::get_pdf_thumbnail(get_attached_file($obj_id));
				break;
			case "audio/mpeg":
				$media_info->image = $icon_path . "icon_mp3.jpg";
				break;			
			case "text/plain":
				$media_info->image = $icon_path . "icon_txt.jpg";
				break;			
			case "application/rtf":
				$media_info->image = $icon_path . "icon_rtf.jpg";
				break;			
		}
		
		list($media_info->w, $media_info->h) = @getimagesize($media_info->image);
		
		$post_obj = get_post($obj_id);	// retrieve image object
		
		// look for parent and parent address
		if ($post_obj->post_parent) {	// normal case
			$post_obj = get_post($post_obj->post_parent);
			$media_info->post_ID = $post_obj->ID;
			$media_info->post_URI = get_permalink($post_obj->ID);	//$post_obj->guid;
			$media_info->post_title = $post_obj->post_title;
		} else {	// treat the rich-text-tag plugin case to link to the tag page
			//echo "found case";
			$sql_query = 'SELECT term_id ' .
							'FROM ' . $wpdb->term_taxonomy . ' ' .
							'WHERE `description` LIKE "%' . $media_info->url . '%"';
			$sql_result = self::run_mysql_query($sql_query);
			//print_ro($sql_result);
				
			$media_info->post_ID = -1;
			if (!empty($sql_result)) {
				$media_info->post_URI = get_tag_link($sql_result[0]->term_id);	//get_bloginfo('url') . '/?tag=' . $sql_result[0]->slug;
				$media_info->post_title = "Tag : " . $sql_result[0]->slug;
			}
		}
		//print "image parent post ID : " . $img_info->post_ID . "<br/>";
		//print "image parent post URI : " . $img_info->post_URI . "<br/>";
		//print "image parent post title : " . $media_info->post_title . "<br/>";
		
		return $media_info;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Extract list of media from database
	//		what : media_all, media_tagged, media_untagged, media_count
	//
	private function get_media_list($what, &$count, &$reason) {
		global $wpdb;
		$mime_types = array("1"=>"image/jpeg", 
							"2"=>"image/gif",
							"3"=>"image/png",
							"4"=>"text/plain",
							"5"=>"application/rtf",
							"6"=>"application/pdf",
							"7"=>"audio/mpeg");
		$count = new StdClass;
		$count->total = 0;
		$count->tagged = 0;
		$count->untagged = 0;
		$reason = 0;
		
		//$reason = 1; return array();		// UNCOMMENT THIS LINE TO SIMULATE BLOG WITHOUT MEDIA
		//$reason = 2; return array();		// UNCOMMENT THIS LINE TO SIMULATE BLOG WITH ALL MEDIA TAGGED
		
		$admin_media_formats = self::$opt['admin_media_formats'];
		$mime_string = "";
		foreach ($admin_media_formats as $i) {
			$mime_string .= ',"' . $mime_types[$i] . '"';
		}
		$mime_string = substr($mime_string, 1);		// remove first comma
		//echo "MIME types : " . $mime_string . "<br/>";
		
		// Get all images and supported attachments
		$sql_query = 'SELECT ID '.
					 'FROM ' . $wpdb->posts . ' AS pos ' .
					 'WHERE pos.post_mime_type IN (' . $mime_string . ')';
		$media_all = self::run_mysql_query($sql_query);
	
		foreach($media_all as $obj)
			$media[] = $obj->ID;
		if (empty($media)) {		// no media detected
			$reason = 1;
			return array();
		}
		sort($media);		// TBD : array_values ?
		//self::print_ro($media);
		$count->total = sizeof($media);
		//self::print_ro($count->total);
		
		// Get tagged medias matching selected media formats
		$sql_query = 'SELECT DISTINCT object_id '.
					 'FROM ' . self::$SQL_MDTG_TABLE . ' ' .
					 'WHERE 1';
		$media_tagged_all = self::run_mysql_query($sql_query);
		
		$media_tagged = array();
		foreach($media_tagged_all as $obj)
			$media_tagged[] = $obj->object_id;
		//self::print_ro($media_tagged);
		// select only the media with the selected format jpg, pdf, etc)
		$media_tagged = array_intersect($media_tagged, $media);
		sort($media_tagged); // TBD : array_values ?
		//self::print_ro($media_tagged);
		$count->tagged = sizeof($media_tagged);
		//self::print_ro($count->tagged);
		
		
		// Get untagged by difference
		$media_untagged = array_diff($media, $media_tagged);
		sort($media_untagged); // TBD : array_values ?
		//self::print_ro($media_untagged);
		if (empty($media_untagged)) $reason = 2;		// all media tagged
		$count->untagged = sizeof($media_untagged);
		//self::print_ro($count->untagged);
			
		switch ($what) {
			case 'media_all' : return $media; break;
			case 'media_tagged' : return $media_tagged; break;
			case 'media_untagged' :  return $media_untagged; break;
			case 'media_count' : return array(); break;
		}
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Retrieve all tags associated to media for a given post (remove any image
	//	tag that is a category to avoid messing wordpress post categorizing)
	//
	private function get_media_tags_from_post_id($post_id){
		global $wpdb;
		$tax_id_list = array();
		//$debug = 1;
					
		// Retrieve tags associated to post images
		$media_list =& get_children('post_type=attachment&post_parent=' . $post_id);
		//self::print_ro($media_list);
		if (empty($media_list))
			return array();
		
		foreach($media_list as $media_id=>$media){
			$media_list[$media_id] = self::get_media_tags($media_id);	
		}
		//self::print_ro($media_list);
			
		foreach($media_list as $media_id){
			foreach ($media_id as $tag){
				if (!in_array($tag->term_taxonomy_id, $tax_id_list) && get_cat_name($tag->term_id) == "")
					$tax_id_list[] = $tag->term_taxonomy_id;
			}
		}
		sort($tax_id_list);
		//self::print_ro($tax_id_list);
		
		if ($debug) {
			echo self::$t->tags_from_taxonomy . "<br/>";
			print_ro(self::taxonomy_to_name($tax_id_list));
		}
		
		return $tax_id_list;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Set tags for a post
	//
	private function update_post_tags($post_id, $update, &$update_required){
		global $wpdb;
		$update_required = 1;
		
		// Retrieve image taxonomy
		$tag_list = self::get_media_tags_from_post_id($post_id);
		
		//
		// 1 - retrieve current category(ies) and tags
		//
		$current_tax = array();
		$cats = array();
	
		$category = get_the_category($post_id);
		foreach($category as $cat)
			$cats[] = $cat->term_taxonomy_id;
		
		$query_result = get_the_tags($post_id);
		if (!empty($query_result)) {
			foreach ($query_result as $tax)
				$current_tax[] = $tax->term_taxonomy_id;
			sort($current_tax);
		}
		
		if (count($current_tax) == count($tag_list) && !count(array_diff($current_tax, $tag_list))) {
			$update_required = 0;
			return $tag_list;	// tags are already properly set
		}
		
		//$original_cats = implode(', ', imgt_taxonomy_to_name($cats));
		if (!$update) {
			$original_tags = implode(', ', self::taxonomy_to_name($current_tax));
			$new_tags = implode(', ', self::taxonomy_to_name($tag_list));
			echo '=== <em>' . self::$t->post . '</em> : <strong>' . get_the_title($post_id) . "</strong> ===<br/>";
			//echo '<em>' . _n('Original category', 'Original categories', count($cats), 'mediatagger') . ":</em> " . $original_cats . "<br/>";
			echo '<em>' . self::n('original_tags', count($current_tax)) . ":</em> " . $original_tags . "<br/>";
			echo '<em>' . self::n('new_tags', count($new_tags)) . ":</em> " . $new_tags . "<br/>";
			//print_ro($current_tax);
			//print_ro($tag_list);
			return $tag_list;
		}
				
		//
		// 2 - delete current tags and cat(s)
		//
		$sql_query = 'DELETE FROM ' .  $wpdb->term_relationships . ' ' .
					 'WHERE object_id = ' . $post_id;
		self::run_mysql_query($sql_query, 1);
			
		//
		// 3 - reset original cat(s) and set new tags
		//
		$cat_tag_list = array_merge($cats, $tag_list);
		
		$sql_string_values = '(' . $post_id. ',' . implode(',0),('.$post_id.',', $cat_tag_list) . ')';
		$sql_string_values = substr($sql_string_values, 0, strlen($sql_string_values)-1) . ',0)';
			
		$sql_query = 'INSERT INTO ' . $wpdb->term_relationships . ' ' .
					 '(`object_id`, `term_taxonomy_id`, `term_order`) VALUES ' . $sql_string_values;
		self::run_mysql_query($sql_query, 1);
		
		//
		// 4 - update tag count for tags previously set (and potentially erased) and new tags
		//
		$tags_count_updt_list = array_unique(array_merge($current_tax, $tag_list));
		foreach	($tags_count_updt_list as $tag) {
			$tag_desc = self::get_tag_descriptors('term_taxonomy_id=' . $tag);
			//print_ro($tag_desc);
			$new_count = count(query_posts('tag=' . $tag_desc->slug . '&posts_per_page=-1'));
			//echo $new_count . "<br/>";
			$sql_query = 'UPDATE ' . $wpdb->term_taxonomy . ' '.
						'SET count = "' . $new_count . '" ' .
						'WHERE term_taxonomy_id = "' . $tag . '"';
			self::run_mysql_query($sql_query, 1);
		}
			
		return $tag_list;	// return real tag list associated to the post, excluding any category
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Return array of tag names from array of taxonomy
	//
	private function taxonomy_to_name($tax, $use_keys=0){
		$tax_name = array();
		if (empty($tax))
			return $tax_name;
			
		foreach($tax as $tax_key=>$tax_id){
			$id = ($use_keys? $tax_key : $tax_id);
			$tag_desc = self::get_tag_descriptors('term_taxonomy_id=' . $id);
			$tax_name[] = (empty($tag_desc) ? get_cat_name($id) : $tag_desc->name);
		}
		natcasesort($tax_name);
		
		return $tax_name;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	//	Define MediaTagger shortcode
	//
	// [mediatagger attributes]
	//
	function multisearch_shortcode_func($atts) {
		
		extract(shortcode_atts(array(
			'result_page_url' => '',
			'num_tags_displayed' => '',
			'font_size_min' => '',
			'font_size_max' => '',
			'font_color_min' => '',
			'font_color_max' => '',
		), $atts));
			
		$strout = self::multisort_insert($result_page_url, $num_tags_displayed, $font_size_min, $font_size_max, $font_color_min, $font_color_max);
		return $strout;
	}
	
	//
	//	function called 1/ by the shortcode below 2/ can be called directly (public) from anywhere in the site
	//
	public function get_media_count() {
		self::get_media_list('media_count', $count, $reason);
		return $count->total;	
	}
	
	//
	// Return plugin version
	//
	public function get_plugin_version_stable() {
		return self::$PLUGIN_VERSION_STABLE;	
	}
	public function get_plugin_version() {
		return self::$PLUGIN_VERSION;	
	}

	//
	// Function below is a trick to run the short code with priority 7, ie before wpautop, and filters with default (10) priority
	// Otherwise those formatting functions would not be applied onto this shortcode
	//
	function run_shortcode($content) {
		global $shortcode_tags;
	
		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		$shortcode_tags = array();
	
		add_shortcode(self::$PLUGIN_NAME_LC, array($this, 'multisearch_shortcode_func'));
		add_shortcode(self::$PLUGIN_NAME_LC . '_count', array($this, 'get_media_count'));
		add_shortcode(self::$PLUGIN_NAME_LC . '_version_stable', array($this, 'get_plugin_version_stable'));
		add_shortcode(self::$PLUGIN_NAME_LC . '_version', array($this, 'get_plugin_version'));
	
		// Do the shortcode (only the one above is registered)
		$content = do_shortcode($content);
	
		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;
	
		return $content;
	}
	

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : viewer
	//
	function player_page(){
		if (self::check_php_version()) return;

		echo "<h1>" . self::$PLUGIN_NAME . " - Player</h1>";
		
		echo '<div style="clear:both;padding:20px;width:800px;background-color:#fff;font-size:11pt">';
		
		$strout = self::multisort_insert();
		
		echo $strout;
		echo '</div>';
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//
	// Insert image search form on a page and provide search result when tags selected and 
	// search submitted
	//
	//
	private function multisort_insert($result_page_url='', $num_tags_displayed = '', $font_size_min = '', $font_size_max = '', 
								   $font_color_min = '', $font_color_max = '', $called_from_widget = 0){
		$search_field_default = self::$t->search_attachment_like;
		$run_free_search = 0;
		$debug = 0;
		$strout = '';
		
		//self::print_ro($_POST);
		
		if (isset($_GET['tags'])){		// a GET url was formed : http://www.photos-dauphine.com/phototheque?tags=lumiere+arbre+foret
			$tag_list_get = $_GET['tags'];
			$tag_list_get = explode(' ', $tag_list_get);
			//self::print_ro($tag_list_get);
			$tax_id_list = self::slug_to_taxonomy($tag_list_get);
			//self::print_ro($tax_id_list);
			$search_mode = 0;
		}
		
		if (isset($_GET['display'])){		// a GET url was formed : http://www.photos-dauphine.com/phototheque?tag=lumiere+arbre+foret&display=cloud 
			// display argument can be :  cloud+form+field at the max ; by default the setup defined in the admin panel applies
			$search_mode=0;
			$search_display_get = $_GET['display'];
			if (strstr($search_display_get, "cloud")) $search_mode += 1;
			if (strstr($search_display_get, "form")) $search_mode += 2;
			if (strstr($search_display_get, "field")) $search_mode += 4;
			
			//self::print_ro(self::is_search_mode("cloud", $search_mode) . " " . self::is_search_mode("form", $search_mode). " " . self::is_search_mode("field", $search_mode));			
		}
		
		$tax_id_list = (isset($tax_id_list) ? $tax_id_list : ($_POST['search'] == "Clear" ? array() : $_POST['mdtg_tags']));
		
		// Define form prefix to avoid 2 same form names when widget displayed on result page
		$search_form_prefix = ($called_from_widget ? 'mdtg_widget_' : 'mdtg_'); 
		$search_form_name = $search_form_prefix . 'seachform';
		$search_form_submit = $search_form_prefix . 'post_submit';
		
		// Free field search
		$free_search = (($_POST['search'] == "Clear" || ($_POST['free_search'] == $_POST['last_free_search']) && ($_POST['link_triggered']<21)) ? "" : $_POST['free_search']);
		if ($free_search == "") {
			$free_search = $search_field_default;	
		}
		if ($free_search != $search_field_default) {
			$run_free_search = 1;
			$tax_id_list = array();
		}
		//self::print_ro("free_search : " . $free_search . " (run : $run_free_search)");

		// Get result page
		if ($result_page_url == '')
			$result_page_url = get_permalink();
			
		// Get preset modes 
		$preset_search_mode_tab = self::$opt['search_default_display_mode'];	
		//self::print_ro($preset_search_mode_tab);
		foreach($preset_search_mode_tab as $i => $mode)	// bit : 0: cloud only; 1: cloud & form; 2: form only
			$preset_search_mode += 1<<($mode-1);
		//self::print_ro("Preset search mode : " . $preset_search_mode);
		$search_mode = ($called_from_widget ? 1 : (isset($search_mode) ? $search_mode : ( $_POST['coming_from_widget'] ? $preset_search_mode : (isset($_POST['search_mode']) ? $_POST['search_mode'] : 
			$preset_search_mode))));	
		//self::print_ro('Search mode : ' . $search_mode);
		
		$preset_result_mode = self::$opt['result_default_display_mode']; 	// 1: gallery; 2: itemized image list; 3: title list
		//self::print_ro('Preset result mode : ' . $preset_result_mode);
		
		$result_mode = (isset($_POST['result_mode']) ? $_POST['result_mode'] : $preset_result_mode);
		//self::print_ro('Result mode : ' . $result_mode);

		//self::print_ro('search_display_switchable mode : ' . self::$opt['search_display_switchable']);
		//self::print_ro('result_display_switchable mode : ' . self::$opt['result_display_switchable']);
		$is_search_mode_switchable = ($called_from_widget ? 0 : self::$opt['search_display_switchable']%2);
		$is_result_mode_switchable = self::$opt['result_display_switchable']%2;
		$search_tags_excluded = self::$opt['search_tags_excluded'];
		$admin_background_color = self::$opt['admin_background_color'];
	
		switch ($_POST['link_triggered']){
			// 0: nothing ; 1: toggle cloud ; 2: toggle form ; toggle field
			case 11: self::toggle_search_mode("cloud", $search_mode); break;
			case 12: self::toggle_search_mode("form", $search_mode); break;
			case 13: self::toggle_search_mode("field", $search_mode); break; 
			// 1: gallery ; 2: itemized image list; 3: title list
			case 21:
			case 22:
			case 23: $result_mode = $_POST['link_triggered'] - 20; break; 
			// 30:prev page, 31:next page
			case 30: $change_page_previous = 1; break;	
			case 31: $change_page_next = 1; break;
		}
		
		switch ($result_mode) {
			case 1:				// gallery
				$num_img_per_page = self::$opt['gallery_image_num_per_page'];
				$img_norm_size = self::$opt['result_img_gallery_w_h'];
				$img_border_width = self::$opt['gallery_image_border_w'];
				$img_border_color = self::$opt['gallery_image_border_color'];
				$link_to_post = self::$opt['gallery_image_link_ctrl'] % 2;
				break;
			case 2:				//  itemized image list
				$num_img_per_page = self::$opt['list_image_num_per_page'];
				$img_norm_size = self::$opt['result_img_list_w_h'];
				break;
			case 3:				//  title list
				$num_img_per_page = self::$opt['list_title_num_per_page'];
				break;
		}
		
		$result_display_optimize_xfer = self::$opt['result_display_optimize_xfer'] % 2 ;	// 1==>1 2==>0

	
		//////////////////////////////// BEGIN : prepare cloud search mode /////////////////////////////////////////////////////////////
		if (self::is_search_mode("cloud", $search_mode)) {			
			$tagcloud_order = self::$opt['tagcloud_order'] - 1;	// to change from base 1 to base 0
	
			if ($num_tags_displayed == '')
				$num_tags_displayed = self::$opt['tagcloud_num_tags'];	// 0 = all tags
			if ($font_size_min == '')
				$font_size_min = self::$opt['tagcloud_font_min'];
			if ($font_size_max == '')
				$font_size_max = self::$opt['tagcloud_font_max'];
				
			if ($font_color_min == '')
				$font_color_min = self::$opt['tagcloud_color_min'];
			if ($font_color_max == '')
				$font_color_max = self::$opt['tagcloud_color_max'];
			$highlight_text_color = self::$opt['tagcloud_highlight_color'];
			
			$use_dynamic_colors = ( $font_color_min == -1 ? 0 : 1 );
			$use_hover_and_search_highlight = ( $highlight_text_color == -1 ? 0 : 1 );
			
			// filter tags to remove excluded ones
			foreach(self::$tax as $tax)
				if (!self::is_tag_name_excluded($search_tags_excluded, $tax->name))
					$tax_tab[] = $tax;
					
			// Select highest ranking tags and shuffle
			uasort($tax_tab, array($this, cmp_objects_count));
			$tax_tab = array_reverse($tax_tab);
			if ($num_tags_displayed)
				$tax_tab = array_slice($tax_tab, 0, $num_tags_displayed);
			
			// Tags are already sorted by descending ranking
			if ($tagcloud_order == 0)
				uasort($tax_tab, array($this, cmp_objects_lexicography));
			if ($tagcloud_order == 2)
				shuffle($tax_tab);
		
			// Define font scale factor
			foreach ($tax_tab as $tax)
				$count[] = $tax->count;
			$count_diff = max($count) - min($count);
			if ( ($font_size_max == $font_size_min) || !$count_diff)
				$font_scale = 0;
			else
				$font_scale = ($font_size_max - $font_size_min) / $count_diff;
			
			// Define font scale factor
			$color_min_rgb = self::html2rgb($font_color_min);
			$color_max_rgb = self::html2rgb($font_color_max);
			if ( ($font_color_min == $font_color_max) || !$count_diff)
				$color_scale = array_fill(0, 3, 0);
			else {
				$color_scale[0] = ($color_max_rgb[0] - $color_min_rgb[0]) / $count_diff;
				$color_scale[1] = ($color_max_rgb[1] - $color_min_rgb[1]) / $count_diff;
				$color_scale[2] = ($color_max_rgb[2] - $color_min_rgb[2]) / $count_diff;
			}
		} ////////////////////////////// END : prepare cloud search mode ///////////////////////////////////////////////////////////////

		//////////////////////////////// BEGIN : prepare form search mode //////////////////////////////////////////////////////////////
		if (self::is_search_mode("form", $search_mode)) {  
			$num_tags_per_col = self::$opt['search_num_tags_per_col'];
			$search_form_font = self::$opt['search_form_font'];
		} ////////////////////////////// END : prepare form search mode ////////////////////////////////////////////////////////////////
		
		$num_img_start = 0;
		//	Manage PREV / NEXT page _POST here
		if ($change_page_previous) {
			$num_img_start = $_POST['num_img_start'] - $num_img_per_page;		
		} else if ($change_page_next) {
			$num_img_start = $_POST['num_img_start'] + $num_img_per_page;
		}
		$num_img_stop = $num_img_start + $num_img_per_page;	// excluded
		
		if ($_POST['tagcloud'] > 0) {
			unset($tax_id_list);
			$tax_id_list[0] = $_POST['tagcloud'];
		}

		$strout .= '<script language="JavaScript" type="text/javascript">
';
		$strout .= '<!--
';
		$strout .= 'function ' . $search_form_submit . '(post_var_name, post_var_value) {
';
		$strout .= 'document.' . $search_form_name . '.elements[post_var_name].value = post_var_value ;
';
		$strout .= 'document.' . $search_form_name . '.submit();
';
		$strout .= '}
';
		$strout .= 'function tagsearchblur(element) {
';
		$strout .= 'if(element.value == \'\') {element.value = \'' . $search_field_default . '\';}
';
		$strout .= '}
';
		$strout .= 'function tagsearchfocus(element) {
';
		$strout .= 'if(element.value == \'' . $search_field_default . '\') {element.value = \'' . '' . '\';}
';
		$strout .= '}
';
		$strout .= '-->
';
		$strout .= '</script>
';

		$strout .= '<form name="' . $search_form_name . '" method="post" action="' . $result_page_url . '" style="padding:0;margin:0">';
		$strout .= '<input type="hidden" name="search_mode" value="' . $search_mode . '">';
		$strout .= '<input type="hidden" name="result_mode" value="' . $result_mode . '">';
		$strout .= '<input type="hidden" name="tagcloud" value="">';
		$strout .= '<input type="hidden" name="num_img_start" value="' . $num_img_start . '">';
		$strout .= '<input type="hidden" name="link_triggered" value="">';
		$strout .= '<input type="hidden" name="last_free_search" value="' . $free_search . '">';
		if ($called_from_widget) $strout .= '<input type="hidden" name="coming_from_widget" value="1">';
	
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////// Display search mode selector  //////////////////////////////////////////////////////// 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
			
		if ($is_search_mode_switchable) { 
			$strout .= '<div style="clear:both;font-size:0.9em;height:1.2em;padding:4px;margin:0;background-color:#' . $admin_background_color . '"><div style="float:left;color:#AAA;padding-left:5px;letter-spacing:1pt;font-variant:small-caps"><em>' . self::$t->search_display . '</em></div><div style="float:right;padding-right:5px">';
			$strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'11\');return false" title="' . self::$t->toggle_tag_cloud . '">'. self::$t->tag_cloud . '</a>';
			$strout .= ' &nbsp;';
			$strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'12\');return false" title="' . self::$t->toggle_form . '">'. self::$t->form . '</a>';
			$strout .= ' &nbsp;';
			$strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'13\');return false" title="' . self::$t->toggle_search_field . '">'. self::$t->search_field . '</a>';
			$strout .= '</div></div>';
		}

		// Free search field
		if (!$called_from_widget && self::is_search_mode("field", $search_mode))
			$strout .=  '<p style="clear:both;margin:0;padding:' . ($is_search_mode_switchable ? '15' : '0') . 'px 0 0 10px"><input type="text" style="font-style: italic;" name="free_search" size="26" onblur="tagsearchblur(this);" onfocus="tagsearchfocus(this);" value="' . $free_search . '" title="' . self::$t->type_here_keyword .'"></p>';
	
		$strout .= '<p style="clear:both;padding:' . ($called_from_widget ? '0' : '15') . 'px 0 0 0;margin:0">';	
	
		if (self::is_search_mode("cloud", $search_mode)) { // Display tag cloud
			$checked_tags = (isset($tax_id_list) ? $tax_id_list : array());
			foreach ($tax_tab as $tax){ 
				$color_rgb[0] = round($color_scale[0]*$tax->count + $color_min_rgb[0], 0);
				$color_rgb[1] = round($color_scale[1]*$tax->count + $color_min_rgb[1], 0);
				$color_rgb[2] = round($color_scale[2]*$tax->count + $color_min_rgb[2], 0);
				$strout .= '<a href="' . $result_page_url . '" style="font-size:' . round($font_scale*$tax->count + $font_size_min, 1) . 'pt;line-height:110%;text-decoration:none;' .
					($use_dynamic_colors ? 'color:' . self::rgb2html($color_rgb) . ';' : '') . 
					(in_array($tax->term_taxonomy_id, $checked_tags) && $use_hover_and_search_highlight && !$called_from_widget ? 'color:#' . $highlight_text_color : '') . 
					'" onClick="' . $search_form_submit . '(' . "'tagcloud','" . $tax->term_taxonomy_id . "');return false" . '"' . 
					($called_from_widget ? '' : ($use_hover_and_search_highlight && $use_dynamic_colors && !in_array($tax->term_taxonomy_id, $checked_tags) ? 
					' onmouseover="this.style.color=' . "'#" . $highlight_text_color . "'" . '" onmouseout="this.style.color=' . "'" . self::rgb2html($color_rgb) . 
					"'" . '"' : '')) . ' title="' . $tax->count . ' ' . self::n(occurence, $tax->count) . '">' . $tax->name . '</a> ';
			}	// if ($search_mode <= 2)
			$strout .= '</p>';
		} // end tag cloud
		
		if ($called_from_widget) {	// Leave now - nothing more to print, the tag cloud is completed.
			$strout .= '</form>';
			return $strout;
		}

		if (empty($tax_id_list) && !$run_free_search) {	// case no tag selected
			switch ($search_mode) {
				case 0:	// available search method : none (means search was done from URL)
					$strout .= '<em>'. self::$t->no_tag_match . '</em> : <strong>' . $_GET['tags'] . '</strong>'; break;
				case 1:	// available search method : cloud
					$strout .= '<em>'. self::$t->search_cloud . '</em>'; break;
				case 2:	// available search method : 		form
					$strout .= '<em>'. self::$t->search_form . '</em>'; break;	
				case 3: // available search method : cloud 	form
					$strout .= '<em>'. self::$t->search_cloud_form . '</em>'; break;
				case 4: // available search method : 				field
					$strout .= '<em>'. self::$t->search_keyword . '</em>'; break;
				case 5: // available search method : cloud 			field
					$strout .= '<em>'. self::$t->search_cloud_keyword . '</em>'; break;
				case 6: // available search method : 		form	field
					$strout .= '<em>'. self::$t->search_form_keyword . '</em>'; break;	
				case 7: // available search method : cloud	form	field
					$strout .= '<em>'. self::$t->search_cloud_form_keyword . '</em>'; break;
			}
		} else {	// free search || tags selected
			$strout .= '&raquo;<strong> ';
			if ($run_free_search) { // free search
				$multisort_img_list = self::get_media_ID($free_search);	// search images matching keyword
				$strout .= "*" . $free_search . "*";
			} else {
				// Tags selected - search must be started
				//$tagsSelected = 1;
				//printf(_n('Theme matched', 'Themes matched', sizeof($_POST['tags']), 'mediatagger')) ; 
				foreach($tax_id_list as $n=>$img_tax_id) {
					if ($n) $strout .= ', ';
					$tax = self::get_tag_descriptors('term_taxonomy_id=' . $img_tax_id);
					$strout .= $tax->name;
				}
				$multisort_img_list = self::get_media_ID($tax_id_list);	// search images matching tag list
			}
		
			$strout .= '</strong> : ';
	
		//self::print_ro($multisort_img_list);

		$num_img_found = sizeof($multisort_img_list);
		if (!$num_img_found) {
			$strout .= '<i>' . self::$t->no_media_matching . '</i><br/>';
		} else {
			if ($num_img_stop > $num_img_found)
				$num_img_stop = $num_img_found;
				
			$strout .= '<i>' . $num_img_found . ' ';
			$strout .= self::n('n_media_found', $num_img_found); 
			$strout .= '</i><br/>&nbsp;<br/>';
			
			// Get image display size
			$img_min_w_h = round($img_norm_size*3/4, 0);
			 
			//$plugin_url =  WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "", plugin_basename(__FILE__));
			$thumb_url = self::$PLUGIN_DIR_URL . 'thumbnail.php';

			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//////////////////////////////////////////////////////// Display result mode selector  ////////////////////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ($is_result_mode_switchable) {
				$strout .= '<div style="clear:both;font-size:0.9em;height:1.2em;padding:4px;margin:0;background-color:#' . $admin_background_color . '"><div style="float:left;color:#AAA;padding-left:5px;letter-spacing:1pt;font-variant:small-caps"><em>' . self::$t->result_display . '</em></div><div style="float:right;padding-right:5px">';
				if ($result_mode != 1) $strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'21\');return false" title="' . self::$t->result_gallery . '">';
				$strout .= self::$t->gallery;
				if ($result_mode != 1) $strout .= '</a>';
				$strout .= ' &nbsp;';
				if ($result_mode != 2) $strout .= '<a href="' . $result_page_url  .'" onClick="' . $search_form_submit . '(\'link_triggered\',\'22\');return false" title="' . self::$t->result_image_list . '">';
				$strout .= self::$t->itemized;
				if ($result_mode != 2) $strout .= '</a>';
				$strout .= ' &nbsp;';
				if ($result_mode != 3) $strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'23\');return false" title="' .  self::$t->title_list . '">';
				$strout .= self::$t->titles;
				if ($result_mode != 3) $strout .='</a>';
				$strout .= '</div></div>';
			}	// if ($is_result_mode_switchable) 
				
			if ($result_mode >= 2)		// image list or title list
				$strout .= '<p style="margin:0;padding:0;font-size:0.8em">&nbsp;</p>';
		
			// Display results : gallery, image list or title list
			for ($n = $num_img_start; $n < $num_img_stop; $n++) {
				$img_obj = $multisort_img_list[$n];
				$img_info = self::get_media_info($img_obj->ID);
				$img_ratio = $img_info->h/$img_info->w;
				
				/*if ($img_info->h > $img_info->w) {*/
					$img_h = $img_norm_size;
					$img_w = round($img_info->w * $img_h / $img_info->h, 0);
				/*} else {
					$img_w = $img_norm_size;
					$img_h = round($img_info->h * $img_w / $img_info->w, 0);					
				}
				if (($img_ratio < 0.6 || $img_ratio > 1.6) && ($img_h < $img_min_w_h || $img_w < $img_min_w_h)) { // likely panorama format case
					if ($img_h > $img_w) {
						$img_h = round($img_h * $img_min_w_h/$img_w, 0);
						$img_w = $img_min_w_h;
					} else {
						$img_w = round($img_w * $img_min_w_h/$img_h, 0);
						$img_h = $img_min_w_h;
					}
				}*/
				
				if ($img_w > $img_info->w || $img_h > $img_info->h) {
					$img_w = $img_info->w;
					$img_h = $img_info->h;
				}
		
				$is_media_attached_to_post = strlen($img_info->post_URI) > 0;	// check media is attached to post
				$link_media_to_post = $link_to_post && $is_media_attached_to_post;	// link to post only if 1/required by setup 2/media is attached
				$is_image = false;
				if (strpos($img_info->mime, 'image') !== false)	// JPEG, GIF or PNG
					$is_image = true;
				
				$unattached_msg = self::$t->not_attached_post;	
				$img_tooltip = $img_obj->post_title . ' ('. ($is_media_attached_to_post ? $img_info->post_title : $unattached_msg) . ')';
				
				switch ($result_mode) {
					case 1:	// gallery
						if ($is_image) {
							$strout .= '<a href="' . ($link_media_to_post ? $img_info->post_URI : $img_info->url) . '" title="' . $img_tooltip . 
								'"><img src="' . ($result_display_optimize_xfer ? 
									$thumb_url . '?s=' . $img_info->image . '&w=' . $img_w . '&h=' . $img_h :
									$img_info->image) . 
								'" width="' . $img_w . '" height="' . $img_h . '" alt="' . $img_tooltip . '" style="border:' . $img_border_width . 
								'px solid #' . $img_border_color . '"></a>';
						} else {	// attachment is not an image : TXT, PDF, MP3, etc.
							$strout .= '<span style="float:left"><a href="'. ($link_media_to_post ? $img_info->post_URI : $img_info->url) . '" title="' . $img_tooltip .
								 '">' . '<img src="' . $img_info->image . '" width="' . $img_w*.8 .'" >' . '<br/><span style="font-size:0.7em;padding:0 5px">' . 
								 basename($img_info->url) . '</span></a></span>';	
						}
						break;
					case 2:	// image list
						$strout .= '<p style="padding: 10px 0 0 0;margin:0">' . $img_obj->post_title . 
							' (' . ($is_media_attached_to_post ? '<a href="'. $img_info->post_URI . '" title="'  . self::$t->go_to_page . '">' .
							 $img_info->post_title. '</a>' : $unattached_msg) . ')<br/>' .
							'<a href="' . $img_info->url . '" title="' . $img_tooltip . '"><img src="' . ($result_display_optimize_xfer ?
								$thumb_url . '?s=' . $img_info->image . '&w=' . $img_w . '&h=' . $img_h :
								$img_info->image).
							'" width="' . $img_w . '" height="' . $img_h . '" alt="' . $img_tooltip . '"></a></p>'; 
						break;
					case 3:	// title list
						$strout .= '<p style="padding: 2px 0 0 0;margin:0">' . ($is_media_attached_to_post ? '<a href="'. $img_info->post_URI . '" title="'  .
						__('Go to page', 'mediatagger') . '">' : ucfirst($unattached_msg)) .
						$img_info->post_title. '</a> : ' . '<a href="' . $img_info->url . '" title="' . self::$t->access_to_media . '">' . 
						$img_obj->post_title . '</a></p>';
						break;
				}	// end switch
			}	// end for
		}
	}
			
	if ($num_img_start > 0 || $num_img_stop < $num_img_found) 
		$display_pagination = 1;
			
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////// Display pagination selector  ////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($display_pagination) { 
		$strout .= '<p style="clear:both;font-size:0.9em;padding:4px;margin:15px 0 0 0;background-color:#' . $admin_background_color . '">&nbsp;<em>Page ' . (int)(1+$num_img_start/$num_img_per_page) . ' (';
		$strout .= sprintf(self::n('image_x_to_y', $num_img_found), (int)($num_img_start+1), $num_img_stop) . ') &nbsp;&nbsp;</em>';
		if ($num_img_start > 0) $strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'30\');return false" title="' . 
			self::$t->click_previous_page . '">';
		$strout .= '&laquo; ' . self::$t->previous . ($num_img_start > 0 ? '</a>' : '') . '&nbsp;';
		if ($num_img_stop < $num_img_found) $strout .= '<a href="' . $result_page_url . '" onClick="' . $search_form_submit . '(\'link_triggered\',\'31\');return false" title="' . self::$t->click_next_page . '">';
		$strout .= self::$t->next . ' &raquo;' . ($num_img_stop < $num_img_found ? '</a>' : '') . '</p>';
	}	// if ($display_pagination)
	if (self::is_search_mode("form", $search_mode)) {	// form
		if ($display_pagination || !$num_img_found || ($num_img_found && !$display_pagination) )
			$strout .= '<p style="margin:0;padding:5px 0 0 0;clear:both">&nbsp;</p>';
		
		$strout .= '<div style="font-size:' . $search_form_font . 'pt">';
		$strout .= self::print_tag_form($tax_id_list);
		$strout .= '</div>';
		
		$strout .= '</div><div class="submit" style="clear:both;padding-top:15px;text-align:center"><input type="submit" value="OK" name="search" style="width:75px"><input type="submit" value="Clear" name="search" style="width:75px"></div>';
	} else if (isset($tax_id_list)){	// cloud only, in case tags are set
		foreach ($tax_id_list as $tax) 
			$strout .= '<input type="hidden" name="mdtg_tags[]" value="' . $tax . '"> ';
	}
	
	if (self::$opt['admin_credit'])
		$strout .= '<div style="clear:both;float:right;font-size:0.7em;padding:5px 10px 0 0"><a href="http://www.photos-dauphine.com/wp-mediatagger-plugin" title="' . 
			self::$t->offer_media_engine . ' WP MediaTagger ' . self::$PLUGIN_VERSION_STABLE . '"><em>MediaTagger</em></a></div>';
		
	$strout .= '</form>';
	
	return $strout;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Return array of img IDs corresponding to either :
	//		- list of taxonomy_term_id like array(2,4,13)
	//		- attachment name matching a search string
	//
	private function get_media_ID($search_pattern){
		global $wpdb;
		$a_name_str = "";
		
		if (empty($search_pattern))
			return array();
		
		if (is_array($search_pattern)) {	// search_pattern is an array of tag IDs
			foreach($search_pattern as $n=>$tax_id) {
				$sql_query = 'SELECT DISTINCT object_id '.
							 'FROM ' . self::$SQL_MDTG_TABLE . ' AS img ' .
							 'WHERE img.term_taxonomy_id=' . $tax_id . ' ' .
							 'ORDER by object_id';			
				$imgt_img_id_sqls = self::run_mysql_query($sql_query);
				
				//phdbg($imgt_img_id_sqls);
				
				if (empty($imgt_img_id_sqls))	// no chance to find any intersection in case of multisearch ; and for simple search, we know there are no matches ...
					return array();
						
				$imgt_img_id = array();
				foreach($imgt_img_id_sqls as $imgt_img_id_sql){
					$imgt_img_id[] = $imgt_img_id_sql->object_id;
				}
				
				// Search images ID common to all selected tags
				if (empty($multisort_imgt_img_id)) {
					$multisort_imgt_img_id = $imgt_img_id;
				} else {
					$multisort_imgt_img_id = array_intersect($multisort_imgt_img_id, $imgt_img_id);
				}
				if (empty($multisort_imgt_img_id))	// at first empty intersection, return empty ...
					return array();
			}
		} else {	// search pattern is a string, to be searched as part of attachment names
			//phdbg("searching keyword <br/>");
			$sql_query = 'SELECT ID '.
						 'FROM ' . $wpdb->posts . ' AS pos ' .
						 'WHERE pos.post_title LIKE "%' . self::strip_accents($search_pattern, 1) . '%" AND pos.post_type="attachment"';
			$imgt_img_id_sqls = self::run_mysql_query($sql_query);
			$multisort_imgt_img_id = array();
			foreach($imgt_img_id_sqls as $imgt_img_id_sql){
				$multisort_imgt_img_id[] = $imgt_img_id_sql->ID;
			}
		}
		
		// Get all informations related to found medias
		foreach($multisort_imgt_img_id as $img_id) {
			$multisort_img_list[] = get_post($img_id);
		}
		
		return $multisort_img_list;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Return array of taxonomy IDs from array of slugs
	//
	private function slug_to_taxonomy($slug_list){
	
		$tax_list = array();
		if (empty($slug_list))
			return $tax_list;
			
		foreach($slug_list as $slug){
			$tax = self::get_tag_descriptors('slug=' . $slug);
			if (!empty($tax))
				$tax_list[] = $tax->term_taxonomy_id;
		}
	
		return $tax_list;
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	//	Return specific mode activation status given overall display mode :
	//		cloud : 1
	//		form : 2
	//		field : 4
	//
	private function is_search_mode($check_mode, $mode_value) {	
		$n = ($check_mode == "cloud" ? 0 : ($check_mode == "form" ? 1 : 2));	// 2 : expected to be "field"
		return $mode_value & (1 << $n);	
	}

	private function toggle_search_mode($toggle_mode, &$mode_value) {	
		$n = ($toggle_mode == "cloud" ? 0 : ($toggle_mode == "form" ? 1 : 2));	
		//phdbg($mode_value);
		$mode_value ^= (1 << $n);
		//phdbg($mode_value);	
	}
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : dump_opt_page
	//
	function dump_opt_page(){
				
		if (self::check_php_version()) return;

		echo "<h1>" . self::$PLUGIN_NAME . " - self::\$opt</h1>";

		self::print_ro(self::$opt);
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : dump_def_page
	//
	function dump_def_page(){
				
		if (self::check_php_version()) return;

		echo "<h1>" . self::$PLUGIN_NAME . " - self::\$t</h1>";

		self::print_ro(self::$t);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : dump_form_page
	//
	function dump_form_page(){
				
		if (self::check_php_version()) return;

		echo "<h1>" . self::$PLUGIN_NAME . " - self::\$form</h1>";

		self::print_ro(self::$form);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : dump_def_page
	//
	function dump_tax_page(){
				
		if (self::check_php_version()) return;

		echo "<h1>" . self::$PLUGIN_NAME . " - self::\$tax</h1>";

		self::print_ro(self::$tax);
	}

	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Admin panel : options        
	//
	function options_page(){
		$error_list = array();

		if (self::check_php_version()) return; ?>

		<h1><?php echo self::$PLUGIN_NAME ?> - Options</h1>
                         
        <?php 
		//self::print_ro($_POST);
		//self::print_ro(self::$form);

		//	Determine the display mode : options (default) || check_pdf || preview_tax || audit_database
		$form_display = $_POST['form_display'];
		if (!$form_display)
			$form_display = 'options';
		
		// Start form
		//
		?>	
        <form name="mdtg_form" method="post" action="">
            <input type="hidden" name="form_display" value="">
		
        <?php
        if ($form_display == 'options') { 	// display option form
			if (strlen($_POST['submit_options']) > 0) {	// form was submitted - option page not displayed for the first time
				// Check input data validity - revert to last good one if invalid data
				self::check_submit_data($_POST, $error_list);
				// Save to database
				update_option(self::$PLUGIN_NAME_LC, self::$opt);
				
				// display errors
				// self::print_ro($error_list);
				if (count($error_list)) {
					self::user_message(self::$t->invalid_option_value);
					foreach($error_list as $varname) {
						self::user_message(self::$form[$varname]['desc'] . ' - ' . self::$form[$varname]['errmsg']);				
					}
				} else {
					self::user_message(self::$t->options_saved);	
				}
			}	// end "if (strlen($_POST['submit_options']) > 0)"
			?>
		  
            <p><i><?php echo self::$t->options_mouse_over ?></i></p>
    
			<?php self::print_option_form($error_list); ?>
                
            <div class="submit"><input type="submit" name="submit_options" value="<?php echo self::$t->update_options ?> &raquo;" /></div>
            <br/>

		<?php
		} // end "($form_display == 'options')"
		else {
			switch ($form_display) {
				case 'check_pdf' : 
					self::check_pdf_converter(1, 1);	// args : force check, verbose
					break;
				case 'preview_tax' : 
					self::preview_taxonomy();			// previsualize image taxonomy applied to posts
					break;
				case 'audit_database' : 
					self::audit_database();			// audit database inconsistencies
					break;
			}
			?>
			</br>
			<div class="submit"><input type="submit" name="return" value="<?php self::_e('Return') ?> "></div>
			<?php
		}		
		?>
        
        </form>
        
    <hr />
    <p>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="padding-right:10px; float:left">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="WY6KNNHATBS5Q">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
    </form>
    
    </form>
    
    <?php echo self::$t->option_pane_donation . '<br/>'. self::$t->option_pane_donation2; ?>
    </p>
    
    <hr />
    <p style="padding:0;margin-top:-5px;font-size:0.8em"><em><?php echo ' <a href="http://www.photos-dauphine.com/wp-mediatagger-plugin" title="WordPress MediaTagger Plugin Home">WP MediaTagger</a> ' . self::$PLUGIN_VERSION . ' | ' ; echo 'PHP ' . phpversion() .  ' | MySQL ' . mysql_get_server_info() . ' | GD Lib ' . ( self::$GD_VERSION ? self::$GD_VERSION : self::$t->not_available) ;?></em></p>
        
        <?php
//		self::print_ro(self::$opt);
//		self::print_ro(self::$tax);
		
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Display all available options with highlight in case of error 
	//
	private function print_option_form($error_list) {

		foreach(self::$form as $option_name => $option_descriptor) {
		
			$option_val = (array_key_exists($option_name, self::$opt) ? self::$opt[$option_name] : '');
			//echo self::print_ro($option_val);
			
			$input_type = $option_descriptor['type'];
			$desc = $option_descriptor['desc'];
			
			if ($input_type == 'HEADER') {
				echo '<h3>' . $desc . '</h3>';
				continue;
			}
	
			$tooltip = $option_descriptor['tooltip'];
			$script_varname = 'mdtg_' . $option_name;
			
			//	Parse item specific options depending on def file
			//
			if (array_key_exists('list', $option_descriptor)) {		// a list is declared in the -form.php file
				$varlist = $option_descriptor['list'];
			}
			if (array_key_exists('text', $option_descriptor)) {		// a text is declared in the -form.php file
				$link_text = $option_descriptor['text'];
			}
			if (array_key_exists('url', $option_descriptor)) {			// an url is declared in the -form.php file
				$link_url = $option_descriptor['url'];
			}
	
			$readonly = self::run_eval($option_name, $option_val, 'readonly');	// 0->1, 1->0, 2->0			target : 0->0, 1->1, 2->0
			
			$size = 4;	// default input field size
			if (array_key_exists('size', $option_descriptor)) {	// a size is declared in the -def.php file
				$size = $option_descriptor['size'];
			}
					
			if (array_key_exists('order', $option_descriptor)) {	// a list order is declared in the -def.php file
				$order = $option_descriptor['order'];	// list of indexes - ex : array(1,3,2),
			} else {	// Natural order 1,2,3...
				$order = range(1, sizeof($varlist));
			}
			
			//	Lets go... print item
			//
			$html = $desc;	
			if (in_array($option_name, $error_list)) {
				$html = "<span class='option_highlight'>" . $html . "</span>";	// highlight parameter if error detected
			}
							
			echo "<div style='clear:both;'><p class='label'>" . $html ."</p>";
			
			switch($input_type){
					
				case 'TEXT':
					echo '<input type="text" name="' . $script_varname . '" value="' . $option_val . '" size="' . $size . '" title="'  . $tooltip . 
						'" ' . ($readonly ? 'readonly ' : '') . '>';
					break;
	
				case 'TEXTAREA':
					echo '<textarea name="' . $script_varname . '" title="' . $tooltip . '" cols="70" rows="11">' . stripslashes($option_val) . '</textarea>';
					break;
					
				case 'SELECT_XOR':
					echo '<select name="' . $script_varname . '" title="' . $tooltip . '" ' . ($readonly ? 'disabled' : '') .  '>';
					foreach($order as $n) {
						echo "<option value='" . $n . "' " . ($option_val == $n ? "selected" : "") . ">" . $varlist[$n-1] . "&nbsp;</option>";	
					}
					echo "</select>";
					break;
					
				case 'SELECT_OR':
					//self::print_ro($varlist);
					echo '<input type="hidden" name="' . $script_varname . '[]" value="_hidden_checklist_init_">';	// to have script variable reported even if no selection made
					foreach($varlist as $key => $item){	
					//self::print_ro( $item);
						echo '<label><input type="checkbox" value="' . intval($key+1) . '" name="' . $script_varname . '[]"' .
						(in_array($key+1, $option_val) ? ' checked' : '') . ' title="' . $tooltip . '">' . self::item_format($item) . '</label> &nbsp;';
					}
					break;
					
				case 'LINK':
					echo '<a href="" onClick="' . $link_url . ';return false;" title="'. $tooltip . '">' . $link_text . '</a>';
					break;
	
			}
			echo "</div>";
		}
	}
	
	////////////////////////////////////////////////////////////////
	// Format item - used for PDF generation check
	//
	private function item_format($item) {
		
		switch ($item){
			case 'pdf':
				$item = '<a href="" onClick="mdtg_submit(\'form_display\',\'check_pdf\');return false;" title="' . (self::check_pdf_converter() ? 
					'PDF thumbnail creation enabled on this server' : 'PDF thumbnail creation NOT enabled on this server' ) . 
					' - Click to force a re-evaluation">' . $item . '</a>';
				break;
		}
		return $item;
	}
	
	
	////////////////////////////////////////////////////////////////
	// Check data submitted by POST variable is valid
	//	
	private function check_submit_data($collect, &$error_list) {
		
		//self::print_ro(self::$opt);
		//self::print_ro($collect);
		
		//	check each option complies with the checker routine, if implemented
		//	
		foreach($collect as $script_varname => $varval) {
			$tab = explode("mdtg_", $script_varname);
			if (count($tab) < 2) continue;	// not the right form variable, should start with mdtg_
			
			if (is_array($varval))			// remove the always present element used to track empty selections
				if ($varval[0] == '_hidden_checklist_init_') array_shift($varval);
					
			$varname = $tab[1];
			//echo "=== " . $varname . "<br/>";
			
			if (self::run_eval($varname, $varval)){
				self::$opt[$varname] = $varval;
			} else {
				$error_list[] = $varname;
			}			
		}
		
		//	check options coherence
		//	
		self::check_option_coherence($error_list);
		
		//self::print_ro(self::$opt);		
	}

	////////////////////////////////////////////////////////////////
	// 	Evaluate expression
	//	func-type : 'checker', 'readonly'
	//
	function run_eval($varname, $varval, $func_type='checker') {	
	
		$vardef = self::$form[$varname];
	
		if (array_key_exists($func_type, $vardef)) {	// a checker function is declared in the -def.php file
			$expression = $vardef[$func_type];
			//if($func_type =='checker') echo $expression . '<br/>';
			if (is_array($varval))
				$expression = str_replace('@VAL', var_export($varval, true), $expression);
			else
				$expression = str_replace('@VAL', $varval, $expression);
			//if($func_type =='checker') echo $expression . '<br/>';
			
			$eval_str = "\$is_invalid = ! (" . $expression . ") ; return 1 ;";
			//if($func_type =='checker') echo $eval_str . '<br/>';
			
			$eval_exec_ok = eval($eval_str);	// eval("\$is_invalid = ! ($expression) ; return 1 ;");
			if ($eval_exec_ok != 1 || $is_invalid) {
				//if($func_type =='checker') echo "!!!!!!! NOK !!!!!!!<br/>";
				return 0;
			} else {
				//if($func_type =='checker') echo "OK <br/>";
				return 1;
			}
		} else {	// no checker declared : OK by default
			//if($func_type =='checker') echo "Default OK <br/>";
			return ($func_type =='readonly' ? 0 : 2);
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Retrieve options from itemized format - convert if needed (see 'switch' cases)
	//	
	private function check_option_coherence(&$error_list=array()) {
		
		// Force display optimize xfer to 2 if library GD not installed
		if (!self::$GD_VERSION) {
			self::$opt['result_display_optimize_xfer'] = 2;
			
		}
			
		if (self::$opt['tagcloud_color_min'] == -1 && self::$opt['tagcloud_color_max'] != -1){
			self::$opt['tagcloud_color_max'] = -1;
			$error_list[] = 'tagcloud_color_max';
		}
		if (self::$opt['tagcloud_color_min'] != -1 && self::$opt['tagcloud_color_max'] == -1){
			self::$opt['tagcloud_color_min'] = -1;
			$error_list[] = 'tagcloud_color_min';
		}
	}
	
	////////////////////////////////////////////////////////////////
	// 	Check valid hex color code
	//
	private function check_valid_colorhex($colorCode, $accept_minus_1=false) {
		// If user accidentally passed along the # sign, strip it off
		if ($accept_minus_1 && $colorCode == -1) return true;
		$colorCode = ltrim($colorCode, '#');
		if (ctype_xdigit($colorCode) && (strlen($colorCode) == 6 || strlen($colorCode) == 3)) return true;
		else return false;
	}
		
	////////////////////////////////////////////////////////////////
	// 	Check valid tags group definition
	//
	private function check_valid_tags_groups($text) {
		if (self::build_tag_groups($text) > 0) return false;
		return true;
	}
	
	////////////////////////////////////////////////////////////////
	// 	Build tag groups based on admin panel grouping definition
	//
	private function build_tag_groups($admin_tags_groups){
		$used_tags = array();
		$tags_groups = array();
		
		$groups_str = trim(stripslashes($admin_tags_groups));
		if (!strlen($groups_str)) {
			return -1;
		}
		
		$groups = explode(chr(13), $groups_str);	// split by lines
		
		//$column_break = 0;
		foreach($groups as $key=>$line){
			// If line is empty, interpret it as a column break
			if (!strlen(trim($line))) {
				$column_break = 1;
				continue;
			}
			// For each line, split group name from group tags for each group definition
			$grpdef = explode('=', $line);
			
			if (count($grpdef) != 2) {	// group definition line does not respect the GROUPNAME = TAGLIST format
				return 10;
			} else if (!strlen(trim($grpdef[0])) || !strlen(trim($grpdef[1]))) {
				if ($key < count($groups)-1)	// this is a syntax error
					return 20;
				else {							// this is the default group name declaration
					$default_group_name = trim($grpdef[0]);
					if ($column_break) {
						$default_column_break = 1;
						$column_break = 0;
					}
					break;
				}
			} else if (!self::check_string_is_taglist($grpdef[1])) {	// list holds at least on item which is not a tag
				return 30;
			}
			// Split CSV tag list
			$grpitems = explode(',', $grpdef[1]);
			array_walk($grpitems, array($this, 'walk_trim'));
			
			$group_item = new StdClass;
			$group_item->group_name = trim($grpdef[0]);
			$group_item->group_tags = $grpitems;
			if ($column_break) {
				$group_item->group_break = 1;
				$column_break = 0;
			}
			$used_tags = array_merge($used_tags, $grpitems);
			$tags_groups[] = $group_item;
		}
				
		// add tags not listed in any group
		$used_tags = array_unique($used_tags);
		$grpitems = array();
		foreach(self::$tax as $tax_item) {
			if (!in_array($tax_item->name, $used_tags))
				$grpitems[] = $tax_item->name;
		}
		if (count($grpitems)) {
			$group_item = new StdClass;
			$group_item->group_name = (isset($default_group_name) ? $default_group_name : self::$t->default_tag_group_name);
			$group_item->group_tags = $grpitems;
			if ($default_column_break)
				$group_item->group_break = 1;
			$tags_groups[] = $group_item;
		}
		
		// reorder taxonomy by tags, and associate appropriate category
		foreach($tags_groups as $tags_group) {
			foreach($tags_group->group_tags as $key=>$tag_name) {
				$tag_desc = self::get_tag_descriptors('name=' . $tag_name);
				$tag_desc->category = $tags_group->group_name;
				if (!$key & $tags_group->group_break)
					$tag_desc->group_break = 1; 
				$tax[] = $tag_desc;
			}
		}
		
		// Copy the sorted taxonomy local copy to the global
		self::$tax = $tax;	
		return 0;
	}
	
	////////////////////////////////////////////////////////////////
	// 	Check list string is a list of CSV existing tag names 
	//
	private function check_string_is_taglist($list_str) {
		
		if (!strlen(trim($list_str))) return true;
		$tag_table = explode(',', $list_str);
		foreach($tag_table as $tag_name) {
			$tag_name = trim($tag_name);
			$tag_desc = self::get_tag_descriptors('name=' . $tag_name);
			if (!ctype_digit($tag_desc->term_id)) return false;
		}
		return true;
	}
	
	////////////////////////////////////////////////////////////////
	// 	Check if a tag name is part of the exclusion list 
	//
	private function is_tag_name_excluded($tag_list, $check_tag_name) {
		$tag_table = explode(',', $tag_list);
		foreach($tag_table as $tag_name) {
			$tag_name = trim($tag_name);
			if ($check_tag_name == $tag_name) return true;
		}
		return false;
	}


	////////////////////////////////////////////////////////////////////////////////////
	// Return object composed of term_taxonomy_id, term_id, slug and name
	// based on 1 of those 4 elements passed as argument
	//
	private function get_tag_descriptors($search_str) {
		
		$search_obj = new StdClass;
		$search_obj->term_taxonomy_id = '';
		$search_obj->term_id = '';
		$search_obj->slug = '';
		$search_obj->name = '';
	
		$a_search_str = explode('=', $search_str);
		switch ($a_search_str[0]) {
			case 'term_taxonomy_id' : $search_obj->term_taxonomy_id = $a_search_str[1]; $y = array_uintersect(self::$tax, array($search_obj),
										array($this, "cmp_objects_term_taxonomy_id")); break;
			case 'term_id' :          $search_obj->term_id = $a_search_str[1]; $y = array_uintersect(self::$tax, array($search_obj), 
										array($this, "cmp_objects_term_id")); break;	
			case 'slug' :             $search_obj->slug = $a_search_str[1]; $y = array_uintersect(self::$tax, array($search_obj), 
										array($this, "cmp_objects_slug")); break;	
			case 'name' :             $search_obj->name = $a_search_str[1]; $y = array_uintersect(self::$tax, array($search_obj), 
										array($this, "cmp_objects_name")); break;			
		}
		//return current($y);
		return array_shift($y);
	}
	
	////////////////////////////////////////////////////////////////
	// 	Detect if tag form columns are displayed by column break or
	//	by modulo
	//
	private function detect_form_column_breaks(){
		$is_break_set = false;
		
		foreach(self::$tax as $key=>$item){
			if (isset($item->group_break))
				$is_break_set = true;	
		}
		return $is_break_set;
	}

	////////////////////////////////////////////////////////////////////////////////////
	// Implement array_unique for an array of objects
	//
	private function array_unique_obj($array) {
		$buffer = array();
		foreach($array as $obj) $buffer[serialize($obj)] = $obj;
		return array_values($buffer);	
	}
	
	////////////////////////////////////////////////////////////////
	//	Callback functions
	//
	private function cmp_objects_slug($ref, $item){
		if ($ref->slug == $item->slug)
			return 0;
		return -1;
	}
	
	private function cmp_objects_name($ref, $item){
		if ($ref->name == $item->name)
			return 0;
		return -1;
	}
	
	private function cmp_objects_term_id($ref, $item){
		if ($ref->term_id == $item->term_id)
			return 0;
		return -1;
	}
	
	private function cmp_objects_term_taxonomy_id($ref, $item){
		if ($ref->term_taxonomy_id == $item->term_taxonomy_id)
			return 0;
		return -1;
	}
	
	private function cmp_objects_lexicography($a, $b){
		$cmp = strcasecmp(self::strip_accents($a->name, 1), self::strip_accents($b->name, 1));
		return ($cmp == 0 ? 0 : ($cmp > 0 ? 1 : -1));
	}

	private function cmp_objects_count($a, $b){
		if ($a->count == $b->count)
			return 0;
		return ($a->count < $b->count) ? -1 : 1;
	}
	
	private function walk_trim(&$a){
		$a = trim($a);
	}
	
	private function walk_add_category(&$a, $key, $category){
		$a->category = $category;
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	// Return list of image ids matching a keyword
	//
	private function search_keyword($keyword){

		$media_list = self::get_media_list('media_all', $count, $reason); 
		
		if (strlen($keyword) > 0)
			//$media_list_filtered = array_filter($media_list, imgt_filter_keyword);
			$media_list_filtered = array_filter($media_list, array(new Ckeyword_filter($this, $keyword), 'keyword_filter'));
		else 
			$media_list_filtered = $media_list;
		
		return array_values($media_list_filtered);
	}

	////////////////////////////////////////////////////////////////
	// Get URL path for a file with absolute system path :
	//	
	//	/homez.424/photosdab/www/wp-content/plugins/wp-mediatagger/mediatagger.php
	//		will give
	//	http://www.photos-dauphine.com/wp-content/plugins/wp-mediatagger/mediatagger.php
	//
	private function get_url_from_path($path) {	
		// Next lines typically returns '/wp_content'
		//$wp_upload_dir = wp_upload_dir();
		//$upload_base_dir = array_pop(explode(get_option('siteurl'),$wp_upload_dir['baseurl']));
		//$upload_base_dir = $wp_upload_dir['baseurl'];
		
		$content_base = end(explode(home_url(), content_url()));
		$path_suffix = end(explode($content_base, $path));
		$file_url =  home_url() . $content_base . $path_suffix;
		
		if (0) {
			echo ">> path = " . $path . "<br/>";
			echo ">> home_url() = " . home_url() . "<br/>";
			echo ">> content_url() = " . content_url() . "<br/>";
			echo ">> content_base = " . $content_base . "<br/>";	
			echo ">> path_suffix = " . $path_suffix . "<br/>";	
			echo ">> file_url = " . $file_url . "<br/>";			
		}
		
		return $file_url;
	}
	
	////////////////////////////////////////////////////////////////////////////////////
	// Return thumbnail from pdf file
	//
	private function get_pdf_thumbnail($pdf_file) {
		$default_thumbnail_url = self::$PLUGIN_DIR_URL . 'images/icon_pdf.jpg';
		//echo "default_thumbnail_url : $default_thumbnail_url <br/>";
		
		$thumbnail_filename = dirname($pdf_file) . '/'. current(explode('.', basename($pdf_file))) . '.png';
		$thumbnail_url = self::get_url_from_path($thumbnail_filename);
		//echo "&nbsp;&nbsp; thumbnail target file : '$thumbnail_filename' <br/>";
		//echo "&nbsp;&nbsp; thumbnail target url : '$thumbnail_url' <br/>";
		
		if (file_exists($thumbnail_filename)) {
			//echo "&nbsp;&nbsp; thumbnail file detected <br/>";
		} else if (self::check_pdf_converter(0, 0)){	// convert to JPG
			//echo "&nbsp;&nbsp; trying to create thumbnail file... <br/>";
			$exec_cmd = self::build_pdf_convert_command($pdf_file, $thumbnail_filename);
			//echo "&nbsp;&nbsp; system command : $exec_cmd <br/>";
			exec($exec_cmd, $reval);
			if (file_exists($thumbnail_filename)) {
				//echo "&nbsp;&nbsp; file creation success <br/>";
			} else {
				//echo "&nbsp;&nbsp; file creation FAILED ; using default icon <br/>";
				//$thumbnail_url = get_bloginfo('url') .'/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/icon_pdf.jpg';
				$thumbnail_url = $default_thumbnail_url;
			}
		} else {	// take default thumbnail
			//echo "&nbsp;&nbsp; default thumbnail <br/>";
			//$thumbnail_url = get_bloginfo('url') .'/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/icon_pdf.jpg';
			$thumbnail_url = $default_thumbnail_url;
		}
		
		//echo "&nbsp;&nbsp; thumbnail final url : '$thumbnail_url' <br/>&nbsp;<br/>";
		return $thumbnail_url;
		
	}

	////////////////////////////////////////////////////////////////
	// Retrieve canonical path of a system util
	//
	private function build_pdf_convert_command($fin='', $fout='') {
	
		$exec_result = exec("type convert");
		//Expected : $convert_util = "/usr/bin/convert";
		$convert_util = end(explode(' ',$exec_result));
		//echo "Convert path : $convert_util <br/>";
		if (!strlen(trim($convert_util)))
			return false;
			
		if ($fin != "")
			$exec_cmd = "exec 2>&1; " . $convert_util . " -debug exception " . $fin . "[0] -density 216 -resample 72 " . $fout;
		else
			$exec_cmd = $convert_util;
		
		return $exec_cmd;
	}
	
	////////////////////////////////////////////////////////////////
	// Check if the pdf to jpg conversion is enabled on the server
	//
	private function check_pdf_converter($force_check = 0, $verbose = 0) {
		//	pdf.png exist => OK
		//  pdf.png.fail exist => NOK
		// 	none of the 2 || force_check => check conversion 
		
		/*echo "<pre>";
		//echo "TEST<br/>";
		system("ls -l /usr/lib64");
		system("rpm -qa");
		system("find / -name *jasper*");
		echo "</pre>";*/	
			
		$pdf_source = self::$PLUGIN_DIR_PATH . 'images/test/test.pdf';
		$png_out = dirname($pdf_source) . '/pdf.png';
		$png_fail_out = dirname($pdf_source) . '/pdf.png.fail';
		$png_url = self::get_url_from_path($png_out);
	
		if ($verbose) {
			echo "Testing if server is installed with PDF converter module...<br/>&nbsp;<br/>";
			echo "pdf_source : " . $pdf_source . "<br/>";
			echo "png_out : " . $png_out . "<br/>";
			echo "png_fail_out : " . $png_fail_out . "<br/>";
			echo "png_url : " . $png_url . "<br/>";
		}
		
		if (file_exists($png_out)){
			if ($force_check) {
				if ($verbose) echo "Output file already exists ; deleting before re-evaluating :  ";
				unlink($png_out);
				if (!file_exists($png_out)) 
					if ($verbose) echo "file delete succeeded <br/>";
				else
					if ($verbose) echo "WARNING : file could not be deleted <br/>";
			}
			else {
				if ($verbose) {
					echo "Output file already exists : $png_url <br/>";
					echo '<img src="' . $png_url . '"><br/>';
					echo "Interpreted as capability of the server to convert from pdf to png  <br/>";
				}
				return 1;
			}
		}
		
		if (file_exists($png_fail_out)){
			if ($force_check) {
				if ($verbose) echo "Failure conversion indicator file exists ; deleting before re-evaluating :  ";
				unlink($png_fail_out);
				if (!file_exists($png_fail_out)) 
					if ($verbose) echo "file delete succeeded <br/>";
				else
					if ($verbose) echo "WARNING : file could not be deleted <br/>";
			}
			else {
				if ($verbose) {
					echo "Output file already exists : $png_fail_out <br/>";
					echo "Interpreted as INABILITY of the server to convert from pdf to jpg  <br/>";
				}
				return 0;
			}
		}
	
		if (!($convert_util = self::build_pdf_convert_command())){
			if ($verbose) echo "Convert utility not found <br/>";
			return 0;
		};
			
		//Expected : $convert_util = "/usr/bin/convert";
		if ($verbose) echo "Convert path : $convert_util <br/>";
		
		//echo "&nbsp;&nbsp; exec(pwd) : " . exec('pwd') . "<br/>";
		exec("exec 2>&1; $convert_util -version", $tab); 
		if ($verbose) self::print_ro($tab);
		unset($tab);
		exec("exec 2>&1; $convert_util -list format", $tab);
		if ($verbose) self::print_ro($tab);
		unset($tab);
	
		$exec_cmd = self::build_pdf_convert_command($pdf_source, $png_out);
		if ($verbose) echo "<br/>Executing command : <b>'$exec_cmd'</b> <br/>&nbsp;<br/>";
		
		exec($exec_cmd, $tab);		
		if ($verbose && count($tab)) print_ro($tab);
		
		if (file_exists($png_out)) {
			if ($verbose) {
				echo "<b>Conversion succeeded - PNG file created : $png_url </b><br/>&nbsp;<br/>";
				echo '<img src="' . $png_url . '"><br/>';
				echo ">>> PDF conversion enabled";
			}
			return 1;	
		} else {
			// Create failure indication file
			touch($png_fail_out);
			if ($verbose) {
				echo "<b>conversion FAILED - PNG not created</b><br/>&nbsp;<br/>";
				echo ">>> pdf conversion NOT enabled ; conversion inability file created : $png_fail_out ";
			}
			return 0;	
		}
	}
	
	////////////////////////////////////////////////////////////////
	// Preview image taxonomy applied to posts
	//
	private function preview_taxonomy() {
	
		echo "<h3>TBD - <i>preview_taxonomy()</i> function under development...</h3>";
			
	}

	////////////////////////////////////////////////////////////////
	// Preview image taxonomy applied to posts
	//
	private function audit_database() {
	
		echo "<h3>TBD - <i>audit_database()</i> function under development...</h3>";
			
	}
	
	////////////////////////////////////////////////////////////////
	// Run mysql query and do minimum error checking
	//
	private function run_mysql_query($sql_query, $debug = 0) {
		global $wpdb;
		
		$debug_sql = (defined('self::DEBUG_SQL_WRITE') ?  $debug : 0);

		if ($debug_sql) {
			self::print_ro($sql_query);
			return;
		}
		
		self::check_table_exists();
		
		$sql_result = $wpdb->get_results($sql_query);
		if (mysql_error()) {
			echo 'MYSQL query returned error executing query <strong>"'. $sql_query . '"</strong> : <br/>=> <span style="color:red">' . 
				htmlentities(mysql_error()) . '</span><br/>';
			$sql_result = "SQL_EXEC_ERROR";
		}
		return $sql_result;
	}

	////////////////////////////////////////////////////////////////
	// Check table exists - initialize of copy according to case
	//
	private function check_table_exists($verbose=0){
		
		global $wpdb;
		$db_table_legacy = 	$wpdb->term_relationships . '_img';	// up to release 3.2.1
		$db_table = self::$SQL_MDTG_TABLE;
		// For testing, uncomment line below
		// $db_table = self::$SQL_MDTG_TABLE . '_test';
		
		// If table already there, return with no action
		if ($wpdb->get_var('SHOW TABLES LIKE "' . $db_table . '"') == $db_table){
			if ($verbose)
				self::admin_message_log(self::$t->table_detected_not_created . ".<br/>");
			return;
		} else {	// create or create AND import
			$sql = 'CREATE TABLE ' . $db_table . '(object_id BIGINT(20) NOT NULL DEFAULT 0,term_taxonomy_id BIGINT(20) NOT NULL DEFAULT 0,PRIMARY KEY (object_id,term_taxonomy_id),KEY term_taxonomy_id (term_taxonomy_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    		$wpdb->query($sql);
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $db_table_legacy . '"') == $db_table_legacy){	// copy legacy to new
				self::admin_message_log(self::$t->table_detected_converted . ".<br/>");			
				$sql = 'INSERT INTO ' . $db_table . ' SELECT * FROM ' . $db_table_legacy . ';';
	    		$wpdb->query($sql);		
			} else {
				self::admin_message_log(self::$t->table_not_detected_created . ".<br/>");			
			}
		}
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Translate i18 strings
	//
    private function translate_i18_strings($t, $form_file) {

		if ($_SERVER['REMOTE_ADDR'] == PHD_CLIENT_IP) {
			//echo PHD_CLIENT_IP . " detected<br/>";

			$def_filename = self::$PLUGIN_DIR_PATH . self::$PLUGIN_NAME_LC . '-def.php';
			//echo $def_filename . '<br/>';
			$file_basename = self::$PLUGIN_DIR_PATH . 'languages/' . self::$PLUGIN_NAME_LC . '-def-i18n';
			$i18n_filename = $file_basename . '.php';
			$md5_filename = $file_basename . '.md5';
			
			$md5_ref = @file_get_contents($md5_filename);
			$md5 = md5_file($def_filename);
			//echo $md5_ref . '<br/>';
			//echo $md5 . '<br/>';
			
			if ($md5 != $md5_ref) {
				echo "MD5 differs <br/>";
		
				//
				//	Create i18 parsing file that will be then processed by poedit locally
				//
				$t_tab = (array)$t;
				$key_plural = '';
				foreach ($t_tab as $key => $string) {
					//echo $key . '<br/>';
					if ($key_plural) {
						$t_tab[$key_plural] .= $string . '");' . "\n";
						$key_plural = '';
						unset($t_tab[$key]);
						continue;
					}
					if (substr(strrev($key), 0, 3) == '1__') {
						//echo $key . '<br/>';
						$key_plural = strrev(substr(strrev($key), 3));
						//echo $key_plural . '<br/>';
						$t_tab[$key_plural] = '_n("' . $string . '", "';
						unset($t_tab[$key]);
					} else {
						$t_tab[$key] = '_("' . $string . '");' . "\n";
					}
				}
				array_unshift($t_tab, "<?php\n\n");
				array_push($t_tab, "\n?>\n\n"); 
				file_put_contents($i18n_filename, $t_tab);
				file_put_contents($i18n_filename, file_get_contents($form_file), FILE_APPEND);
				file_put_contents($md5_filename, $md5);
			}
		}
		
		//
		//	Get translates strings
		//
		$t_tab = (array)$t;
		$singular = 0;
		foreach ($t_tab as $key => $string) {
			if ($singular) {
				$t_tab[$key] = _n($singular, $string, 2, self::$PLUGIN_NAME_LC);
				//echo $t_tab[$key] . '<br/>';
				$t_tab[$key . '_'] = $string;
				$singular = 0;
				continue;
			}
			if (substr(strrev($key), 0, 3) == '1__') {
				$singular = $string;
				$t_tab[$key] = _n($string, 'dummy', 1, self::$PLUGIN_NAME_LC);
				//echo $t_tab[$key] . '<br/>';
				$t_tab[$key . '_'] = $string;
			} else {
				$t_tab[$key] = __($string, self::$PLUGIN_NAME_LC);
				$t_tab[$key . '_'] = $string;
			}
		}
	
		$t = (object)$t_tab;
		return $t;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Plural text string management
	//
	private function n($key, $n){
		$key_sing = $key . '__1';
		$key_plur = $key . '__2';
		return ($n > 1 ? self::$t->$key_plur : self::$t->$key_sing);
	}

	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Localize & message management
	//
	private function __($text) {return __($text, self::$PLUGIN_NAME_LC);}
	private function _e($text) {_e($text, self::$PLUGIN_NAME_LC);}
	
	
//	_n('media associated to tag', 'medias associated to tag', $tax_item->count, 'mediatagger') .
	
	
	private function print_ro($obj){echo "<pre>";print_r($obj);echo "</pre>";}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Warning / Error message to user 
	//
	private function user_message(){
		$arg_list = func_get_args();
		$msg = sprintf($arg_list[0], $arg_list[1], $arg_list[2], $arg_list[3], $arg_list[4], $arg_list[5]);
		echo '<div class="updated"><p>' . $msg . '</p></div>';
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Settings link in the extensions admin panel 
	//
	function action_links($links, $file) {
		static $this_plugin;
	
		if (!$this_plugin) {
			$this_plugin = plugin_basename(__FILE__);
		}
	
		if ($file == $this_plugin) {
			$links[] = '<a href="admin.php?page=' . self::$PLUGIN_NAME_LC . '_options">' . self::__('Options') . '</a>';
		}
	
		return $links;
	}
	
	////////////////////////////////////////////////////////////////
	//
	// 	Color conversion routines
	//
	private function html2rgb($color) {
		if ($color[0] == '#')
			$color = substr($color, 1);
	
		if (strlen($color) == 6)
			list($r, $g, $b) = array($color[0].$color[1],
									 $color[2].$color[3],
									 $color[4].$color[5]);
		elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
			return false;
	
		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
	
		return array($r, $g, $b);
	}
	
	private function rgb2html($r, $g=-1, $b=-1) {
		if (is_array($r) && sizeof($r) == 3)
			list($r, $g, $b) = $r;
	
		$r = intval($r); $g = intval($g);
		$b = intval($b);
	
		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));
	
		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return '#'.$color;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Remove accents
	//
	private function strip_accents($string, $is_utf8=0){
		return remove_accents($string);		// WordPress function
		//return strtr(($is_utf8 ? utf8_decode($string) : $string),'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//	Init MediaTagger widget
	//
	function mdtg_widget($args) {
		$widget_options_holder = self::$PLUGIN_NAME_LC . '_widget';
		$options = get_option($widget_options_holder);
		extract($args);    //on extrait les variables natives d'affichage telles que $before_widget
	
		echo $before_widget;
		echo $before_title . $options['title'] . $after_title;
		if (trim($options['text']) != '')
			echo '<div class="textwidget">' . $options['text'] . '</div>';
		// Insert the MediaTagger tag cloud
		echo self::multisort_insert($options['result_url'], $options['num_tags'], $options['font_min'], $options['font_max'],
								$options['color_min'], $options['color_max'], 1);
		
		echo $after_widget; 
	}
	
	//////////////////////
	//
	function mdtg_widget_control() {
		$widget_options_holder = self::$PLUGIN_NAME_LC . '_widget';
		$options = get_option($widget_options_holder);
		
		if (!$options) {	// first : try to read back legacy variable
			$widget_options_holder_legacy = 'wpit_widget';
			$options = get_option($widget_options_holder_legacy);
			
			if (!$options) { // else : init to default
				$options = array('title'=>'My photo library', 'text'=>'Descriptive text here', 'num_tags'=>0, 'font_min'=>8,
					'font_max'=>18, 'color_min'=>'aaaaaa', 'color_max'=>'333333', 'result_url'=>'http://my-result-page');
			}
			update_option($widget_options_holder, $options);
		}
		
		if ($_POST["mdtg_widget_submit"]) {
			$options['title'] = strip_tags(stripslashes($_POST["mdtg_widget_title"]));
			$options['text'] = stripslashes($_POST["mdtg_widget_text"]);
			$options['num_tags'] = $_POST["mdtg_widget_num_tags"];
			$options['font_min'] = $_POST["mdtg_widget_font_min"];
			$options['font_max'] = $_POST["mdtg_widget_font_max"];
			$options['color_min'] = $_POST["mdtg_widget_color_min"];
			$options['color_max'] = $_POST["mdtg_widget_color_max"];
			$options['result_url'] = strip_tags(stripslashes($_POST["mdtg_widget_url"]));
			update_option($widget_options_holder, $options);
		}
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$text = htmlspecialchars($options['text'], ENT_QUOTES);
		$num_tags = $options['num_tags'];
		$font_min = $options['font_min'];
		$font_max = $options['font_max'];
		$color_min = $options['color_min'];
		$color_max = $options['color_max'];
		$url = $options['result_url'];
		?>
	  
		<p><label for="mdtg_widget_title"><?php _e('Title', 'mediatagger'); ?> : </label><br/>
		<input id="mdtg_widget_title" name="mdtg_widget_title" size="30" value="<?php echo $title; ?>" type="text"></p>
		<p><label for="mdtg_widget_text"><?php _e('Text', 'mediatagger'); ?> : </label><br/>
		<textarea name="mdtg_widget_text" cols="28" rows="6"><?php echo $text ?></textarea></p>
		<p><label for="mdtg_widget_num_tags"><?php _e('Number of displayed tags (all = 0)', 'mediatagger'); ?> </label><br/>
		<input id="mdtg_widget_num_tags" name="mdtg_widget_num_tags" size="4" value="<?php echo $num_tags; ?>" type="text"></p>
		<p><label for="mdtg_widget_font_min"><?php _e('Minimum font size', 'mediatagger'); ?> </label><br/>
		<input id="mdtg_widget_font_min" name="mdtg_widget_font_min" size="4" value="<?php echo $font_min; ?>" type="text"></p>
		<p><label for="mdtg_widget_font_max"><?php _e('Maximum font size', 'mediatagger'); ?> </label><br/>
		<input id="mdtg_widget_font_max" name="mdtg_widget_font_max" size="4" value="<?php echo $font_max; ?>" type="text"></p>
		
		<p><label for="mdtg_widget_color_min"><?php _e('Minimum font color (-1 to disable)', 'mediatagger'); ?> </label><br/>
		<input id="mdtg_widget_color_min" name="mdtg_widget_color_min" size="8" value="<?php echo $color_min; ?>" type="text"></p>
		<p><label for="mdtg_widget_color_max"><?php _e('Maximum font color (-1 to disable)', 'mediatagger'); ?> </label><br/>
		<input id="mdtg_widget_color_max" name="mdtg_widget_color_max" size="8" value="<?php echo $color_max; ?>" type="text"></p>
		
		<p><label for="mdtg_widget_url"><?php _e('Result page address', 'mediatagger'); ?> : </label><br/>
		<input id="mdtg_widget_url" name="mdtg_widget_url" size="30" value="<?php echo $url; ?>" type="text"></p>
		
		<input type="hidden" id="mdtg_widget_submit" name="mdtg_widget_submit" value="1" /></p>
	<?php
	}
	
	//////////////////////
	//
	function mdtg_widget_init(){
		$id = self::$PLUGIN_NAME;
		wp_register_sidebar_widget($id, $id, array($this, 'mdtg_widget'), array('description'=>self::$t->plugin_description));     
		wp_register_widget_control($id, $id, array($this, 'mdtg_widget_control'));
	}
	 


	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


};

$wp_mediatagger = new wp_mediatagger();


class Ckeyword_filter {
    function Ckeyword_filter($MDTG_INSTANCE, $keyword){
        $this->mdtg = $MDTG_INSTANCE;
		$this->keyword = $keyword;
    }
    function keyword_filter($a){
		$media_info = $this->mdtg->get_media_info($a);	
		return (stristr($media_info->post_title, $this->keyword) !== false || stristr($media_info->title, $this->keyword) !== false);
    }
}


?>