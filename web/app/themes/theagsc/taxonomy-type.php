<nav class="sub-nav">
	<ul>
		<li><a href="<?= esc_url(home_url('/')); ?>type/type-design" id="articles">Type Design</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/lettering" id="tips">Lettering</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/branding" id="interviews">Branding</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/illustration" id="news">Illustration</a></li>
	</ul>
</nav>
<div style="clear: both;"></div>

<div id="work" class="masonry" data-columns>

	<?php while (have_posts()) : the_post(); ?>
		<div class="item">
			<a href="<?php the_permalink();?>" class="blog-post">
			<?php if( get_field('thumbnail') ): ?><img src="<?php the_field('thumbnail'); ?>" alt="" class="work_image" /><?php endif; ?>
			</a>
		</div>
	<?php endwhile; ?>

</div>
