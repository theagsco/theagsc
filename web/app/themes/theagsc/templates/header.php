<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
    <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>">
      <img class="svg logo-main" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_logo.svg"/>
      <img class="svg logo-small" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_logo-small.svg"/>
    </a>
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
        <span class="nav-hamburger"><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_hamburger.svg"/></span>
      </button>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>

      <span class="nav-close"><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_hamburger.svg"/></span>

    </nav>

    <?php if (is_front_page()) : ?>

		<div id="blurb">
			<h2>We are an Australian husband and wife, passionate about type design, lettering, illustration and creating strong brands for businesses we believe in.</h2>
			<a class="btn" href="<?= esc_url(home_url('/')); ?>about"><span>Read More</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_go.svg"/></a>
		</div><!--blurb-->

	 <?php endif; ?>

  </div>
</header>
<div style="clear:both;"></div>
