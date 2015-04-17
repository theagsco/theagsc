<?php use Roots\Sage\Nav; ?>

<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
    <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
        <span class="nav-hamburger"><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_hamburger.svg"/></span>
      </button>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'walker' => new Nav\SageNavWalker(), 'menu_class' => 'nav navbar-nav']);
      endif;
      ?>
      
      <span class="nav-close"><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_hamburger.svg"/></span>
      
    </nav>
    
    <?php if (is_front_page()) : ?>
    
		<div id="blurb">
			<h1>A proudly Australian graphic design studio & creative community.</h1>
			<h3>We are a husband and wife duo, passionate about lettering, illustration, web design and creating strong brands for businesses we believe in.</h3>
			<a class="btn" href="<?= esc_url(home_url('/')); ?>about"><span>Read More</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_go.svg"/></a>	
		</div><!--blurb-->
		
		<span id="fade"></span>
	
	 <?php endif; ?>
    
  </div>
</header>
