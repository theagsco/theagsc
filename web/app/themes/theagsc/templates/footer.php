<footer class="content-info" role="contentinfo">
  <div class="container">
    <?php dynamic_sidebar('sidebar-footer'); ?>
  </div>
  
  <a class="footer-brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
  
  <a class="footer-email" href="mailto:hello@theagsc.com">hello@theagsc.com</a>
  
	<?php get_template_part('templates/social'); ?>
  
</footer>
