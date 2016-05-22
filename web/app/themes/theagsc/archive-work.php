<nav class="sub-nav">
	<ul>
		<li><a href="<?= esc_url(home_url('/')); ?>work/type-design" id="articles">Type Design</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>work/lettering" id="tips">Lettering</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>work/branding" id="interviews">Branding</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>work/illustration" id="news">Illustration</a></li>
	</ul>
</nav>
<div style="clear: both;"></div>

<div id="work" class="masonry" data-columns>

		<?php
			$the_query = new WP_Query(array(
			'post_type' => 'work',
			'posts_per_page' => -1,
			));
			while ( $the_query->have_posts() ) :
			$the_query->the_post();
		?>
		<div class="item">
			<a href="<?php the_permalink();?>" class="blog-post">
			<?php if( get_field('thumbnail') ): ?><img src="<?php the_field('thumbnail'); ?>" alt="" class="work_image" /><?php endif; ?>
			</a>
		</div>

		<?php
			endwhile;
			wp_reset_postdata();
		?>

</div>
