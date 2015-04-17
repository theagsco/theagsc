<div id="community-home" class="carousel slide home-tiles">
	
	<h4>Community Posts</h4>

	<ul class="carousel-inner">

		<?php 
			$the_query = new WP_Query(array(
			'category_name' => 'community', 
			'posts_per_page' => 1 
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<li class="item active"><a href="<?php the_permalink();?>" class="community-post">
			<?php the_post_thumbnail('large');?>
			<div class="title-excerpt">
				<h2><?php the_title();?></h2>
				<p><?php the_excerpt();?></p>
			</div>
		</a></li><!-- item active -->
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
		
		<?php 
			$the_query = new WP_Query(array(
			'category_name' => 'community', 
			'posts_per_page' => 2, 
			'offset' => 1 
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<li class="item"><a href="<?php the_permalink();?>" class="community-post">
			<?php the_post_thumbnail('large');?>
			<div class="title-excerpt">
				<h2><?php the_title();?></h2>
				<p><?php the_excerpt();?></p>
			</div>
		</a></li><!-- item -->
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
	</ul><!-- carousel-inner -->
	
	<a href="<?= esc_url(home_url('/')); ?>/community" class="btn btn-green"><span>Visit the Community</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_community.svg"/></a>

</div><!-- community -->

<div id="work-home" class="home-tiles">
	
	<h4>Recent Work</h4>

		<?php 
			$the_query = new WP_Query(array(
			'post_type' => 'work', 
			'posts_per_page' => 6,
			'tag' => 'featured'
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<div class="item"><a href="<?php the_permalink();?>" class="community-post">
			<?php if( get_field('thumbnail') ): ?><img src="<?php the_field('thumbnail'); ?>" alt="" class="work_image" /><?php endif; ?>
		</a></div><!-- item active -->
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
		
	
	<a href="<?= esc_url(home_url('/')); ?>/work" class="btn btn-green"><span>View more Work</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_work.svg"/></a>

</div><!-- community -->
