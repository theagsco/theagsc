<?php
defined("ABSPATH") or exit;

// prevent caching, declare constants
if(!defined("DONOTCACHEPAGE")) { 
	define('DONOTCACHEPAGE', true);
}

if(!defined("DONOTMINIFY")) { 
	define('DONOTMINIFY', true); 
}

if(!defined("DONOTCDN")) { 
	define('DONOTCDN', true); 
}

// render simple page with form in it.
?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link type="text/css" rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
	<?php wp_head(); ?>
	
	<style type="text/css">
		body{ padding:20px; background:white; }
		p.mc4wp-edit-link{ display:none; }
		#blackbox-web-debug, #wpadminbar{ display:none !important; }
	</style>

	<style type="text/css" id="custom-css"></style>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body style="min-width: auto !important; width:auto !important; max-width:1000px;">
	<?php mc4wp_form( array('id' => absint($_GET['form_id']) ) ); ?>
	<?php wp_footer(); ?>
</body>
</html>