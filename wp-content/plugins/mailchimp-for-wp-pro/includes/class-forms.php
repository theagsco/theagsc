<?php

class MC4WP_Forms
{
	private $form_instance_number = 1;
	private $error = null;
	private $success = false;
	private $submitted_form_instance = 0;
	private $loaded_ajax_scripts = false;
	private $posted_data = array();

	private static $instance = null;
	
	public static function init() {
		if(!self::$instance) {
			self::$instance = new self;
		}
	}

	public static function instance() {
		return self::$instance;
	}

	private function __construct() 
	{
		add_action( 'init', array($this, 'initialize' ) );

		$opts = mc4wp_get_options('form');

		// enable shortcodes in text widgets
		add_filter( 'widget_text', 'shortcode_unautop');
		add_filter( 'widget_text', 'do_shortcode');	

		add_shortcode('mc4wp_form', array($this, 'output_form'));

		// deprecated. use mc4wp_form.
		add_shortcode('mc4wp-form', array($this, 'output_form'));
				
		// has a form been submitted, either by ajax or manually?
		if(isset($_POST['_mc4wp_form_submit'])) {
			$this->ensure_backwards_compatibility();

			if(!defined('DOING_AJAX') || !DOING_AJAX) {
				// do not submit the form until later, to make sure all WP functions are available
				add_action('init', array($this, 'submit'));
			} else {
				add_action('wp_ajax_nopriv_mc4wp_submit_form', array($this, 'ajax_submit'));
				add_action('wp_ajax_mc4wp_submit_form', array($this, 'ajax_submit'));
			}
		}

		if(isset($_GET['_mc4wp_css_preview'])) {
			add_action('init', array($this, 'show_form_preview'), 1);
		} elseif($opts['css']) {
			// not a preview request, load css.
			if($opts['css'] == 'custom') {
				add_action( 'wp_enqueue_scripts', array($this, 'load_custom_stylesheet'), 90);
			} else {
				add_filter('mc4wp_stylesheets', array($this, 'add_stylesheets'));
			}
		}
	}

	public function initialize() {

		// register post type
		register_post_type( 'mc4wp-form', array(
			'labels' => array(
				'name' => 'MailChimp Sign-up Forms',
				'singular_name' => 'Sign-up Form',
				'add_new_item' => 'Add New Form',
				'edit_item' => 'Edit Form',
				'new_item' => 'New Form',
				'all_items' => 'All Forms',
				'view_item' => null
				),
			'public' => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'show_in_nav_menus' => false,
			'show_in_menu' => false,
			'rewrite' => false
			)
		);

		// register placeholder script, which will later be enqueued for IE only
		wp_register_script( 'mc4wp-placeholders', plugins_url('mailchimp-for-wp-pro/assets/js/third-party/placeholders.min.js'), array(), MC4WP_VERSION_NUMBER, true );
	
		// register ajax script
		wp_register_script('mc4wp-ajax-forms', plugins_url('mailchimp-for-wp-pro/assets/js/ajax-forms.js'), array('jquery-form'), MC4WP_VERSION_NUMBER, true);
	
		// register non-AJAX script (that handles form submissions)
		wp_register_script( 'mc4wp-forms', plugins_url('mailchimp-for-wp-pro/assets/js/forms.js'), array(), MC4WP_VERSION_NUMBER, true );

	}

	public function show_form_preview()
	{
		require MC4WP_PLUGIN_DIR . 'includes/views/form-preview.php';
		die();
	}

	/**
	* Tells the plugin which shipped stylesheets to load.
	*
	* @return array The various stylesheets to be loaded in the combined file.
	*/
	public function add_stylesheets($stylesheets) {

		$opts = mc4wp_get_options('form');

		$stylesheets['form'] = 1;

		// theme?
		if($opts['css'] != 1 && $opts['css'] != 'default') {
			$stylesheets['form-theme'] = $opts['css'];

			if($opts['css'] == 'custom-color') {
				$stylesheets['custom-color'] = urlencode($opts['custom_theme_color']);
			}
		}

		return $stylesheets;
	}

	public function load_custom_stylesheet()
	{
		if(file_exists(WP_CONTENT_DIR . '/mc4wp-custom-styles.css')) {
			wp_enqueue_style( 'mc4wp-custom-form-css', WP_CONTENT_URL . '/mc4wp-custom-styles.css', array());
		}
	}

	public function output_form($atts = array(), $content = null)
	{

		if(!function_exists('mc4wp_replace_variables')) {
			include_once MC4WP_PLUGIN_DIR . 'includes/template-functions.php';
		}

		if(!isset($atts['id'])) { 

			// try to get default form id
			$atts['id'] = get_option('mc4wp_default_form_id', 0);

			// failure? :(
			if(!$atts['id']) {
				return (current_user_can('manage_options')) ? '<p><strong>MailChimp for WP Pro error:</strong> Please specify a form ID in the shortcode attributes. Example: <code>[mc4wp-form id="31"]</code></p>' : ''; 
			}
		}

		$form = get_post($atts['id']);

		if(!$form || $form->post_type != 'mc4wp-form') { return (current_user_can('manage_options')) ? '<p><strong>MailChimp for WP Pro error:</strong> Sign-up form not found. Please check if you have used the correct form ID.</p>' : ''; }

		// get form, first element in posts array
		$form_ID = $form->ID;
		$form_markup = __($form->post_content);
		$settings = mc4wp_get_form_settings($form_ID, true);

		// add some useful css classes
		$css_classes = "mc4wp-form mc4wp-form-{$form_ID} ";

		// does this form have AJAX enabled?
		if($settings['ajax']) { 
			$css_classes .= 'mc4wp-ajax '; 

			// get ajax scripts to load in the footer
			if(!$this->loaded_ajax_scripts) {

				wp_enqueue_script('mc4wp-ajax-forms');
				
				wp_localize_script( 'mc4wp-ajax-forms', 'mc4wp_vars', array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'ajax_loader_url' => plugins_url('mailchimp-for-wp-pro/assets/img/ajax-loader.gif')
					)
				);

				$this->loaded_ajax_scripts = true;
			}
			
		}

		if($this->error) $css_classes .= 'mc4wp-form-error ';
		if($this->success) $css_classes .= 'mc4wp-form-success ';

		if(!function_exists('mc4wp_get_current_url')) {
			include_once MC4WP_PLUGIN_DIR . 'includes/template-functions.php';
		}

		$content = "<!-- MailChimp for WP Pro v". MC4WP_VERSION_NUMBER ." -->";
		$content .= '<div id="mc4wp-form-'.$this->form_instance_number.'" class="'.$css_classes.'">';

		// show the form fields if not submitted or if submitted with hide_after_success == false
		if(!($this->success && $settings['hide_after_success'])) {
			$content .= '<form method="post" action="'. mc4wp_get_current_url() .'">';

			// replace special values
			$form_markup = str_replace(array('%N%', '{n}'), $this->form_instance_number, $form_markup);
			$form_markup = mc4wp_replace_variables($form_markup, array_values($settings['lists']));

			// allow plugins to alter form content
			$form_markup = apply_filters('mc4wp_form_content', $form_markup, $form_ID);

			// allow plugins to add form fields
			do_action('mc4wp_before_form_fields', $form_ID);

			// add form markup to output
			$content .= $form_markup;

			// allow plugins to add form fields
			do_action('mc4wp_after_form_fields', $form_ID);

			// hidden fields
			$content .= '<textarea name="_mc4wp_required_but_not_really" style="display: none;"></textarea>';
			$content .= '<input type="hidden" name="_mc4wp_form_ID" value="'. $form_ID .'" />';
			$content .= '<input type="hidden" name="_mc4wp_form_instance" value="'. $this->form_instance_number .'" />';
			$content .= '<input type="hidden" name="_mc4wp_form_submit" value="1" />';
			$content .= '<input type="hidden" name="_mc4wp_form_nonce" value="'. wp_create_nonce('_mc4wp_form_nonce') .'" />';
			$content .= "</form>";
		}


		// if ajax, output all error messages (but hidden)
		if($settings['ajax']) {
			$content .= '<span class="mc4wp-ajax-loader" style="display: none;"></span>';

				// output all error messages but hide them
			$messages = array('success', 'already_subscribed', 'invalid_email', 'error');
			foreach($messages as $m) {
				if(isset($settings['text_'. $m]) && !empty($settings['text_'. $m])) {

						// build string with css classes
					$css_classes = "mc4wp-alert mc4wp-{$m}-message ";
					if($m == 'success') { $css_classes .= 'mc4wp-success'; }
					elseif($m == 'already_subscribed') { $css_classes .= 'mc4wp-notice'; }
					else{ $css_classes .= 'mc4wp-error'; }

					$content .= '<div style="display:none;" class="'.$css_classes.'">'. __($settings['text_'. $m]) . ' <span class="mc4wp-mailchimp-error"></span></div>';
				}
			}
		} 

		if((int) $this->form_instance_number === (int) $this->submitted_form_instance) {
			// only show success or error messages if this is the form that was submitted.

			if($this->success) {
				$content .= '<div class="mc4wp-alert mc4wp-success">' . __($settings['text_success']) . '</div>';
			} elseif($this->error) {
				
				$api = mc4wp_get_api();
				$e = $this->error;

				// show error messages
				$error_type = ($e == 'already_subscribed') ? 'notice' : 'error';
				$error_message = __($settings['text_' . $e], 'mailchimp-for-wp');
				$content .= '<div class="mc4wp-alert mc4wp-'. $error_type .'">'. $error_message . '</div>';

				// show the eror returned by MailChimp?
				if ( $api->has_error() && current_user_can( 'manage_options' ) ) {
					$content .= '<div class="mc4wp-alert mc4wp-error"><strong>Admin notice:</strong> '. $api->get_error_message() . '</div>';
				}
	
			}
		}

		/* WordPress Administrators only */
		if(empty($settings['lists']) && current_user_can('manage_options')) {
			$content .= '<div class="mc4wp-alert mc4wp-error"><strong>Admin notice:</strong> you have not yet selected a MailChimp list(s) for this form. <a href="'. get_admin_url(null, "post.php?post={$form_ID}&action=edit") .'">Edit this sign-up form</a> and select at least one list.</div>';
		} 

		$content .= "</div>";
		$content .= "<!-- / MailChimp for WP Pro -->";

		// increase form instance number in case there is more than one form on a page
		$this->form_instance_number++;

		// make sure scripts are enqueued later
		global $is_IE;
		if(isset($is_IE) && $is_IE) {
			wp_enqueue_script('mc4wp-placeholders');
		}

		return $content;
	}


	/**
	* Runs for default form submits (non-AJAX)
	*
	* @return boolean True on success
	*/
	public function submit()
	{
		// check nonce
		if(!isset($_POST['_mc4wp_form_nonce']) || !wp_verify_nonce( $_POST['_mc4wp_form_nonce'], '_mc4wp_form_nonce' )) { 
			$this->error = 'invalid_nonce';
			$success = false;
		} else {
			$success = $this->subscribe();
		}

		$this->submitted_form_instance = absint($_POST['_mc4wp_form_instance']);

		// enqueue scripts (in footer)
		wp_enqueue_script( 'mc4wp-forms' );
		wp_localize_script( 'mc4wp-forms', 'mc4wp', array(
			'success' => $success,
			'submittedFormId' => intval($this->submitted_form_instance),
			'postData' => $this->posted_data
			)
		);

		if($success) { 

			$form_ID = $_POST['_mc4wp_form_ID'];
			$settings = mc4wp_get_form_settings($form_ID, true);

			// check if we want to redirect the visitor
			if ( !empty( $settings['redirect'] ) ) {
				wp_redirect( $settings['redirect'] );
				exit;
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	* Runs on AJAX submitted forms.
	*
	* @return JSON Object containing the various result parameters.
	*/
	public function ajax_submit()
	{
		// check nonce, die if invalid.
		check_ajax_referer('_mc4wp_form_nonce', '_mc4wp_form_nonce');

		if(isset($_POST['action'])) {
			unset($_POST['action']);
		}

		$success = $this->subscribe();
		$response = array();
		$response['success'] = $success;
		
		if($success) {
			$form_ID = $_POST['_mc4wp_form_ID'];
			$settings = mc4wp_get_form_settings($form_ID, true);
			$response['redirect'] = (empty($settings['redirect'])) ? false : $settings['redirect'];
			$response['hide_form'] = ($settings['hide_after_success'] == 1);
		} else {
			$response['error'] = $this->error;
			$response['mailchimp_error'] = $this->mailchimp_error;
			$response['show_error'] = current_user_can('manage_options');
		}

		// clear output, some plugins might have thrown errors by now.
		ob_end_clean();

		header( "Content-Type: application/json" );
		echo json_encode($response);
		exit;
	}

	private function subscribe()
	{	
		// check if honeypot was filled
		if(isset($_POST['_mc4wp_required_but_not_really']) && !empty($_POST['_mc4wp_required_but_not_really'])) {
			// spam bot filled the honeypot field
			$this->error = 'spam';
			return false;
		}

		// get individual form settings
		$form_ID = (isset($_POST['_mc4wp_form_ID'])) ? intval($_POST['_mc4wp_form_ID']) : 0;
		$settings = mc4wp_get_form_settings($form_ID, true);

		// check if a MailChimp list is selected in the form settings
		if(empty($settings['lists'])) {
			$this->error = 'no_lists_selected';
			return false;
		}

		// setup array of data entered by user
		// not manipulating anything yet.
		$data = array();
		foreach($_POST as $name => $value) {
			if($name[0] !== '_') {
				$data[$name] = $value;
			}
		}

		// store data somewhere safe
		$this->posted_data = $data;

		// setup empty values
		$merge_vars = array();
		$email = null;

		// add all submited variables to merge vars array
		foreach($data as $name => $value) {

			// uppercase all variables
			$name = trim(strtoupper($name));
			$value = (is_scalar($value)) ? trim($value) : $value;

			if( $name === 'EMAIL' && is_email($value) ) {
				// set the email address
				$email = $value;
			} else if($name === 'GROUPINGS') {
				// try to properly format groupings
				$groupings = $value;

				// malformed, do nothing..
				if(!is_array($groupings)) { continue; }

				$merge_vars['GROUPINGS'] = array();

				foreach($groupings as $grouping_id_or_name => $groups) {

					$grouping = array();

					// group ID or group name given?
					if(is_numeric($grouping_id_or_name)) {
						$grouping['id'] = $grouping_id_or_name;
					} else {
						$grouping['name'] = $grouping_id_or_name;
					}

					// comma separated list should become an array
					if(!is_array($groups)) {
						$grouping['groups'] = explode(',', $groups);
					} else {
						$grouping['groups'] = $groups;
					}

					// add grouping to array
					$merge_vars['GROUPINGS'][] = $grouping;
				}

				// unset groupings if not used
				if(empty($merge_vars['GROUPINGS'])) { unset($merge_vars['GROUPINGS']); }
				
			} else if($name === 'BIRTHDAY') {
				// format birthdays in the DD/MM format required by MailChimp
				$merge_vars['BIRTHDAY'] = date('d/m', strtotime( $value ) );
			} else if($name === 'ADDRESS') {
				
				if(!isset($value['addr1'])) {
					// addr1, addr2, city, state, zip, country 
					$addr_pieces = explode(',', $value);

					// try to fill it.... this is a long shot
					$merge_vars['ADDRESS'] = array(
						'addr1' => $addr_pieces[0],
						'city' => (isset($addr_pieces[1])) ? $addr_pieces[1] : '',
						'state' => (isset($addr_pieces[2])) ? $addr_pieces[2] : '',
						'zip' => (isset($addr_pieces[3])) ? $addr_pieces[3] : ''
					);

				} else {
					// form contains the necessary fields already: perfection
					$merge_vars['ADDRESS'] = $value;
				}

			} else {
				// just add to merge vars array
				$merge_vars[$name] = $value;
			}	
		}

		// check if an email address has been found
		if( !$email ) {
			$this->error = 'invalid_email';
			return false;
		}

		// Try to guess FNAME and LNAME if they are not given, but NAME is
		if(isset($merge_vars['NAME']) && !isset($merge_vars['FNAME']) && !isset($merge_vars['LNAME'])) {
			$strpos = strpos($merge_vars['NAME'], ' ');
			if($strpos) {
				$merge_vars['FNAME'] = substr($merge_vars['NAME'], 0, $strpos);
				$merge_vars['LNAME'] = substr($merge_vars['NAME'], $strpos);
			} else {
				$merge_vars['FNAME'] = $merge_vars['NAME'];
			}
		}

		// everything is ready and sanitized
		// now make the subscribe request

		$api = mc4wp_get_api();

		do_action('mc4wp_before_subscribe', $email, $merge_vars, $form_ID);

		$result = false;
		$email_type = apply_filters('mc4wp_email_type', 'html');
		$lists = apply_filters('mc4wp_lists', $settings['lists'], $merge_vars);

		// make subscribe request for each selected list
		foreach($lists as $list_id) {
			$list_merge_vars = apply_filters('mc4wp_merge_vars', $merge_vars, $form_ID, $list_id);
			$result = $api->subscribe($list_id, $email, $list_merge_vars, $email_type, $settings['double_optin'], $settings['update_existing'], $settings['replace_interests'], $settings['send_welcome']);
		}

		do_action('mc4wp_after_subscribe', $email, $merge_vars, $form_ID, $result);

		if($result === true) {

			if($settings['send_email_copy']) {
				$this->send_email($email, $merge_vars, $form_ID);
			}

			// deprecated, action will be removed in 2.0
			$from_url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
			do_action('mc4wp_subscribe_form', $email, $settings['lists'], $form_ID, $merge_vars, $from_url); 

			$this->success = true;
		} else {
			$this->success = false;
			$this->error = $result;
			$this->mailchimp_error = $api->get_error_message();
		}

		return $this->success;
	}

	private function send_email($email, $merge_vars, $form_id) {

		$settings = mc4wp_get_form_settings($form_id);

		// email receiver
		$to = $settings['email_copy_receiver'];
	 	
		$form = get_post($form_id);
		$form_name = $form->post_title;

	 	// email subject
	 	$subject = "New MailChimp Sign-Up - ". get_bloginfo('name');
	  
		// build message
		ob_start();

		?>
		<h3>MailChimp for WordPress: New Sign-Up</h3>
		<p><strong><?php echo $email; ?></strong> signed-up at <?php echo date("H:i"); ?> on <?php echo date("d/m/Y"); ?> using the form "<?php echo $form_name; ?>".</p>
		<table cellspacing="0" cellpadding="10" border="0" style="border:1px solid #bbb;">
			<tbody>
				<tr><td><strong>EMAIL</strong></td><td><?php echo $email; ?></td></tr>
				<?php foreach($merge_vars as $field => $value) { 

						if($field == 'GROUPINGS') {			 
							foreach($value as $grouping) {
								$grouping_name = isset($grouping['name']) ? $grouping['name'] : $grouping['id'];
								$groups = implode(', ', $grouping['groups']);
								?><tr><td><strong>GROUPING:</strong> <?php echo $grouping_name; ?></td><td><?php echo $groups; ?></td></tr><?php			
							}
						} else {
							if(is_array($value)) {
								$value = implode(", ", $value);
							}
							?><tr><td><strong><?php echo $field; ?></strong></td><td><?php echo $value; ?></td></tr><?php						
						}
				} ?>
			</tbody>
		</table>
		<p style="color:#666;">This email was auto-sent by the MailChimp for WordPress plugin.</p>				
		<?php		  
		$message = ob_get_contents();
		ob_end_clean();

		// filters
		$message = apply_filters('mc4wp_email_copy', $message, $email, $merge_vars, $form_id);
	 
		// send email
		wp_mail( $to, $subject, $message, "Content-Type: text/html" );
	}

	/*
	* Formats old GROUPINGS field into the proper format for the new style
	*
	* @deprecated 2.0
	*/
	public function ensure_backwards_compatibility()
	{
		// detect old style GROUPINGS, then fix it.
		if(isset($_POST['GROUPINGS']) && is_array($_POST['GROUPINGS']) && isset($_POST['GROUPINGS'][0])) {

			$old_groupings = $_POST['GROUPINGS'];
			unset($_POST['GROUPINGS']);
			$new_groupings = array();

			foreach($old_groupings as $grouping) {

				if(!isset($grouping['id']) && !isset($grouping['name'])) { continue; }
				if(!isset($grouping['groups'])) { continue; }

				$key = (isset($grouping['id'])) ? $grouping['id'] : $grouping['name'];

				$new_groupings[$key] = $grouping['groups'];

			}

			// re-fill $_POST array with new groupings
			if(!empty($new_groupings)) { $_POST['GROUPINGS'] = $new_groupings; }

		}

		return;
	}
}