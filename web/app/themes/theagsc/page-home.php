<h4>Recent Work</h4>

<div id="work-home" class="home-tiles masonry" data-columns>

		<?php
			$the_query = new WP_Query(array(
			'post_type' => 'work',
			'posts_per_page' => 6,
			'tag' => 'featured'
			));
			while ( $the_query->have_posts() ) :
			$the_query->the_post();
		?>
		<div class="item">
			<a href="<?php the_permalink();?>" class="blog-post">
			<?php if( get_field('thumbnail') ): ?><img src="<?php the_field('thumbnail'); ?>" alt="" class="work_image" /><?php endif; ?>
			</a>
		</div><!-- item active -->
		<?php
			endwhile;
			wp_reset_postdata();
		?>

</div><!-- work -->
<a href="<?= esc_url(home_url('/')); ?>work" class="btn btn-green"><span>View more Work</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_work.svg"/></a>


<div id="blog-home" class="home-tiles">

	<h4>Blog Posts</h4>

	<ul>

		<?php
			$the_query = new WP_Query(array(
			'category_name' => 'blog',
			'posts_per_page' => 3
			));
			while ( $the_query->have_posts() ) :
			$the_query->the_post();
		?>
		<li class="item"><a href="<?php the_permalink();?>" class="blog-post">
			<div><?php the_post_thumbnail('large');?></div>
			<div class="title-excerpt">
				<h2><?php the_title();?></h2>
				<p><?php the_excerpt();?></p>
			</div>
		</a></li><!-- item -->
		<?php
			endwhile;
			wp_reset_postdata();
		?>

	</ul>

	<a href="<?= esc_url(home_url('/')); ?>blog" class="btn btn-green"><span>Visit the Blog</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_community.svg"/></a>

</div><!-- blog -->
