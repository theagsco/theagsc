<a href="#" class="top" title="Go to Top"><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_go.svg"/></a>

<footer class="content-info" role="contentinfo">
  <div class="container">
    <?php dynamic_sidebar('sidebar-footer'); ?>
  </div>

  <a class="footer-brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>

  <a class="footer-email" href="mailto:hello@theagsc.com">hello@theagsc.com</a>

	<?php get_template_part('templates/social'); ?>

	<div style="clear: both;"></div>

  <p>To constantly create at the highest standard, whilst aiming to inspire & equip others to do the same.
<!--
	  <br />
	  <br />
	  &#9988 with &#9829 and <a href="https://roots.io/sage/">Sage</a>.
--></p>

</footer>
