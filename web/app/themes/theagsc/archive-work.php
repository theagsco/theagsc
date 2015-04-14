<div id="work">

	<div id="lettering" class="work-type">
		<h2>Lettering</h2>
		<?php 
			$the_query = new WP_Query(array(
			'post_type' => 'work', 
			'posts_per_page' => -1, 
			'tax_query' => array(
				array(
					'taxonomy' => 'type',
					'field'    => 'slug',
					'terms'    => array ('lettering', 'branding'),
				),
			),		)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<div class="item">
			<a href="<?php the_permalink();?>" class="community-post">
			<?php if( get_field('thumbnail') ): ?><img src="<?php the_field('thumbnail'); ?>" alt="" class="work_image" /><?php endif; ?>
			</a>
		</div>
	
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
		<div style="clear: both;"></div>
	</div>
	
	<div id="websites" class="work-type">
		<h2>Websites</h2>
		
		<?php 
			$the_query = new WP_Query(array(
			'post_type' => 'work', 
			'posts_per_page' => -1, 
			'tax_query' => array(
				array(
					'taxonomy' => 'type',
					'field'    => 'slug',
					'terms'    => 'web',
				),
			),
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<div class="item">
			<a href="<?php the_permalink();?>" class="community-post">
				<span class="circle-red"></span>
				<span class="circle-yellow"></span>
				<span class="circle-green"></span>
			<?php if( get_field('work_main_image') ): ?><img src="<?php the_field('work_main_image'); ?>" alt="" class="work_image" /><?php endif; ?>
			</a>
		</div>
	
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
		<div style="clear: both;"></div>
	</div>

	<div id="illustration" class="work-type">
		<h2>Illustration</h2>
		
		<?php 
			$the_query = new WP_Query(array(
			'post_type' => 'work', 
			'posts_per_page' => -1, 
			'tax_query' => array(
				array(
					'taxonomy' => 'type',
					'field'    => 'slug',
					'terms'    => 'illustration',
				),
			),
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<div class="item">
			<a href="<?php the_permalink();?>" class="community-post">
			<?php if( get_field('work_main_image') ): ?><img src="<?php the_field('work_main_image'); ?>" alt="" class="work_image" /><?php endif; ?>
			</a>
		</div>
	
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
		<div style="clear: both;"></div>
	</div>
	
</div>