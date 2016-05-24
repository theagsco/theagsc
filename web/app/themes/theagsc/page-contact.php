<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>

	<article>
	    <div class="entry-content">
			<p>It's our dream one day to open up a brick &amp; mortar studio so that you can pop in and visit. Until then, email is a wonderful way to get in touch with us.</p>

			<p>Do you have a project you'd like to work on with us? Ideas for tutorials or other content for the Community? Even if it's a simple hello or a funny gif, hearing from you makes our day. Go ahead and click the button below to email us.</p>

			<div class="contact-email articlewidth center"><a href="mailto:hello@theagsc.com" class="btn"><span>hello@theagsc.com</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_sub.svg"/></a></div>
	    </div>
	</article>

<?php endwhile; ?>
