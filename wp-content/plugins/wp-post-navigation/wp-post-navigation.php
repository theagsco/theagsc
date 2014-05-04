<?php
/*
Plugin Name: WP Post Navigation
Version: 1.2.2
Description: Show Previous and Next Post Links at Posts.
Author: Anas Mir
Author URI: http://sharp-coders.com/author/anasmir
Plugin URI: http://sharp-coders.com/plugins/wp-plugins/wp-post-navigation
*/

/*Check Version*/

global $wp_version;
$exit_msg="WP Requires Latest version, Your version is old";
if(version_compare($wp_version, "3.0", "<"))
{
	exit($exit_msg);
}

if(!class_exists(WPPostNavigation)):
	class WPPostNavigation{
		var $pre_navigation = '';
		var $next_navigation = '';
		var $img = '';
		var $options = array();

		function loadMainValues()
		{
			if (is_single()){
				$this->options = $this->get_wp_post_navigation_options();
				$navi = $this->options['nav_within_cat'] == "1"? true: false;
				$pre_post = get_next_post($navi);
				$next_post     = get_previous_post($navi);

				if($this->options['navi_img'] != "1")
				{
					if($this->options['is_custom'] != "1"){
						$this->pre_navigation = $pre_post->ID!=""?'<a href="'. get_permalink($pre_post->ID).'">'.$pre_post->post_title.'</a>':'';
						$this->next_navigation = $next_post->ID!=""?'<a href="'. get_permalink($next_post->ID).'">'.$next_post->post_title.'</a>':'';
					}else{
						$this->pre_navigation = $pre_post->ID!=""?'<a href="'. get_permalink($pre_post->ID).'">'.$this->options['custom_pre'].'</a>':'';
						
						$this->next_navigation = $next_post->ID!=""?'<a href="'. get_permalink($next_post->ID).'">'.$this->options['custom_next'].'</a>':'';
					}
						
				}else{
					$this->pre_navigation = $pre_post->ID!=""?'<a href="'. get_permalink($pre_post->ID).'"><img src="'.$this->options['pre_img_link'].'" /></a>':'';
					$this->next_navigation = $next_post->ID!=""?'<a href="'. get_permalink($next_post->ID).'"><img src="'.$this->options['next_img_link'].'" /></a>':'';
				}
				$this->img = $this->options['navi_img'] == "1"? "-1": '';
			}
		}
		function WP_Custom_Post_Navigation()
		{
			if(is_single())
			{
				return '<div class="wp-post-navigation">
						   <div class="keyboardleft wp-post-navigation-pre'.$this->img.'">
						   '.$this->pre_navigation.'
						   </div>
						   <div class="keyboardright wp-post-navigation-next'.$this->img.'">
						   '.$this->next_navigation.'
						   </div>
						</div>';
			}
		}
		function WP_Pre_Next_Navigation_Bottom($content)
		{
			if(is_single())
			{
				return $content.'<div class="wp-post-navigation">
						   <div class="keyboardleft wp-post-navigation-pre'.$this->img.'">
						   '.$this->pre_navigation.'
						   </div>
						   <div class="keyboardright wp-post-navigation-next'.$this->img.'">
						   '.$this->next_navigation.'
						   </div>
						</div>';
			}
			return $content;
		}
		
		function handle_wp_post_navigation_options()
		{
			$settings = $this->get_wp_post_navigation_options();
			if (isset($_POST['submitted']))
			{
				//check security
				check_admin_referer('wp-post-navigation-by-sharp-coders');
				$settings['nav_within_cat'] = isset($_POST['nav_within_cat'])? "1" : "0" ;
				$settings['style'] = isset($_POST['style_css'])? $_POST['style_css'] : "" ;
				$settings['is_custom'] = isset($_POST['is_custom'])? "1" : "0" ;
				$settings['custom_pre'] = isset($_POST['custom_pre'])? $_POST['custom_pre'] : "" ;
				$settings['custom_next'] = isset($_POST['custom_next'])? $_POST['custom_next'] : "" ;
				$settings['navi_img'] = isset($_POST['navi_img'])? "1" : "0" ;
				$settings['pre_img_link'] = isset($_POST['pre_img_link'])? $_POST['pre_img_link'] : "" ;
				$settings['next_img_link'] = isset($_POST['next_img_link'])? $_POST['next_img_link'] : "" ;
				
				update_option("wp_post_navigation_options", serialize($settings));
				echo '<div class="updated fade"><p>Setting Updated!</p></div>';
			}
			$action_url = $_SERVER['REQUEST_URI'];
			include 'wp-post-navigation-admin-options.php';
		}

		function get_wp_post_navigation_options()
		{
			$options = unserialize(get_option("wp_post_navigation_options"));
			return $options;
		}
		function WP_Post_Navigation_install()
		{
			$options = array(
				'nav_within_cat' => '1',
				'style' => 'text-decoration: none;
font:bold 16px sans-serif, arial;
color: #666;',
				'is_custom' => '0',
				'custom_pre' => 'Previous Post',
				'custom_next' => 'Next Post',
				'navi_img' => '0',
				'pre_img_link' => '',
				'next_img_link' => ''
			);
			add_option("wp_post_navigation_options", serialize($options));
		}
		function wp_admin_menu()
		{
			add_options_page('WP Post Navigation', 'WP Post Navigation', 10, basename(__FILE__), array(&$this, 'handle_wp_post_navigation_options'));
		}

		function wp_post_navigation_stylesheet() {
			if(is_single()){
				wp_register_style( 'wp-post-navigation-style', plugins_url('style.css', __FILE__) );
				wp_enqueue_style( 'wp-post-navigation-style' );
			}
		}
		function wp_post_navigation_HeadAction()
		{
			if(is_single()){
				$this->loadMainValues();
				echo '<style type="text/css">
						.wp-post-navigation a{
						'.$this->options['style'].'
						}
					 </style>';
			}
		}
	}
else:
	exit('WPPostNavigation Already Exists');
endif;

$WPPostNavigation = new WPPostNavigation();
if(isset($WPPostNavigation)){
	register_activation_hook(__FILE__, array(&$WPPostNavigation, 'WP_Post_Navigation_install'));
	add_filter('wp_head', array(&$WPPostNavigation, 'wp_post_navigation_HeadAction'));
	add_filter('the_content', array(&$WPPostNavigation, 'WP_Pre_Next_Navigation_Bottom'));
	add_action('admin_menu', array(&$WPPostNavigation, 'wp_admin_menu'));
	add_action( 'wp_enqueue_scripts', array(&$WPPostNavigation, 'wp_post_navigation_stylesheet'));
}
?>