<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
<meta name="google-site-verification" content="izJbFvsGM4mZAu2To2C3tuHkuOgDsceM2QqpUumT4yk" />
<meta name="viewport" content="width=device-width, initial-scale=0.5">

<title><?php if (is_front_page()) { bloginfo('name'); } 

elseif (is_single() || is_page() || is_archive()) {wp_title(false); echo ' | '; bloginfo('name');}

else { bloginfo('name'); } ?></title>
	
<!--WP-HEAD-->

<?php wp_head(); ?>
   
<!--STYLES-->

<!--
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/form.css" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/style_text.css" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/fonts.css" />
-->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />

<?php if ( !is_home()) {?>

<style>
header {
	height:65px;
	padding-top:35px;
}
#logo {
	width:131px;
	height:28px;
	background-image:url('<?php bloginfo('template_directory'); ?>/images/logo-page.png');
}
nav.menu-header-menu-container, nav#account {bottom:12px;}

#header_wrapper {height: 101px;}

#menus {margin-right: 172px;}

</style>

<?php } ?>


<!--FAVICON-->

<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.png" />

<!--SCRIPTS-->

<script type="text/javascript" src="//use.typekit.net/xpi3pnm.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<script type="text/javascript"> var DisableKeyboardShake = false; </script>
<script src="<?php bloginfo('template_directory'); ?>/scripts/isotope.js"></script>

</head>
<body <?php body_class($class); ?>>

<script>

var disqus_config = function () {
    // The generated payload which authenticates users with Disqus
    this.page.remote_auth_s3 = '<message> <hmac> <timestamp>';
    this.page.api_key = 'gzp6DtakYwm58AF3kLrZW8GCkKsztvdPCa1qBJXru7isYYBR0nZHY0HXB8xAPBr6';
}

</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=346400515483369";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

<!-- Please call pinit.js only once per page -->
<script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>

<div id="local"></div>

<?php if(function_exists('c2c_reveal_template')) {?>
<div id="reveal_template"><?php c2c_reveal_template(); ?></div>
<?php } ?>

<div id="header_wrapper">
<header id="header">
<a href="javascript:void(0)" class="menu-button icon-menu"></a>
    <div class="container">
    <div class="content">
    
    	
    
        <a href="<?php bloginfo('home'); ?>" id="logo"><?php bloginfo('name'); ?></a>
        
        <div id="menus-wrapper">
        	<div id="menus">
        	
			<?php wp_nav_menu( array(
				'container'		=> 'nav',
				'theme_location' 	=> 'header-menu',
				'link_after'		=> '<span class="icon-down"></span>'
            )); ?>
            
            <nav id="account">
            <ul class="menu account-menu">

		<?php if ( is_user_logged_in() ) { ?>
        
                <li class="menu-item menu-item-has-children" id="account_item">
                    <a href="<?php bloginfo('home'); ?>/my-account" title="Visit Account">
                        
                      <span class="account-image"><?php global $user_email; get_currentuserinfo(); ?><?php echo get_avatar( $user_email, $size = '48' ); ?></span>
                      <span class="account-name"><?php global $user_identity; get_currentuserinfo(); echo $user_identity; ?><span class="icon-down"></span></span>
                      
                    </a>
                    <ul class="sub-menu">
                    	<li><a href="<?php bloginfo('home'); ?>/my-account">Profile</a></li>
                    	<li><a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>">Sign Out</a></li>
                    </ul>
                </li>
                <li class="menu-item menu-item-has-children" id="cart_item">
                <!-- Cart Contents -->
                    <a class="cart-contents"></a>
                    <ul class="sub-menu">
                    	<li><a href="<?php bloginfo('home'); ?>/cart">View Cart</a></li>
                    	<li><a href="<?php global $woocommerce; echo $woocommerce->cart->get_cart_url(); ?>?empty-cart"><?php _e( 'Empty Cart', 'woocommerce' ); ?></a></li>
                    	<li><a href="<?php bloginfo('home'); ?>/checkout">Check Out</a></li>
                    </ul>
                </li>

                
        <?php } else { ?>
        
        <style>
        
/*         Styles here for the menu etc when user is not logged in */
.menu-item.hidden a {display: none !important;}

        
        </style>
			
			
            <li class="menu-item logged_out_item"><a href="<?php bloginfo('home'); ?>/my-account" class="create_account">Create Account</a></li>
            <li class="menu-item logged_out_item"><a href="<?php bloginfo('home'); ?>/my-account" class="log_in">Login</a></li>
			
			<?php } ?>    

                <li class="menu-item" id="search_item">
                <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                    <label for="search" class="search-button icon-search"><span>Search</span></label>
                    <input type="search" name="s" id="search" class="agsc-field" placeholder="Press enter to search">
                    <input type="submit" id="searchsubmit" value="Search" />
                </form>
                </li>
                <li class="menu-item hidden"><a href="<?php bloginfo('home'); ?>/my-account">My Account</a></li>
                <li class="menu-item hidden"><a href="<?php bloginfo('home'); ?>/cart">Cart</a></li>
            </ul>  
            
        </nav> 
        
    </div><!--menus-->         
    </div><!--menus-wrapper-->         
        
    </div><!--content-->
    </div><!--container-->
<div class="popup"><h3>Guess what? This site uses keyboard shortcuts. Here's a <a href="<?php bloginfo('home'); ?>/keyboard-shortcuts">Cheat Sheet</a>. <span>(ESC to dimiss)</span></h3></div>

	<div class="tag_wrapper">
			<?php 
	        
	        $tags = get_tags();
	        if ($tags) {
	        foreach ($tags as $tag) {
	        echo '<a href="' . get_tag_link( $tag->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $tag->name ) . '" ' . '>' . '#' . $tag->name.'</a> ';
	        }
	        }
	        
	        ?>
	</div><!--tags-->
	<div style="clear:both;"></div>
</header>
</div><!--header_wrapper-->
	
		<?php if ( is_page('work') ) { ?>
	
	<section id="options">
	<div id="filters" class="option-set options_wrapper" data-option-key="filter">
		<!-- <a href="#filter" data-option-value="*" class="FilterLink">All</a> -->
		
		<?php		
		//list terms in a given taxonomy
		$taxonomy = 'type';
		$term_args=array(
		  'hide_empty' => false,
		  'orderby' => 'name',
		  'order' => 'ASC'
		);
		$tax_terms = get_terms($taxonomy,$term_args);
		foreach ($tax_terms as $tax_term) {
		echo '<a href="#filter" data-option-value=".' . $tax_term->slug . '" class="FilterLink">' . $tax_term->name . '</a>
		';
		}
		?>			
	    
	</div>
</section><!--options-->

<?php } ?>

<div class="container">
<div class="content body_content">

