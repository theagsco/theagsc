<nav class="sub-nav">
	<ul>
		<li><p><em>Filter:</em></p></li>
		<li><a href="<?= esc_url(home_url('/')); ?>work" id="all">All</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/type-design" id="type-design">Type Design</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/lettering" id="lettering">Lettering</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/branding" id="branding">Branding</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>type/illustration" id="illustration">Illustration</a></li>
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
