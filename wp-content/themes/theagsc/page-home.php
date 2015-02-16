<div id="community">
	<h4>Community Posts</h4>
	<ul>
		
		<?php $home = new WP_Query( 'cat=1&showposts=3' );
		while ($home -> have_posts()) : $home -> the_post(); ?>
		
		<li><a href="<?php the_permalink() ?>">
			
			<?php the_post_thumbnail(); ?>
			
			<div class="title-excerpt">
				<h2><?php the_title(); ?></h2>
				<p><?php the_excerpt(); ?></p>

			</div>

		</a>
		
		</li>
		
		<?php endwhile;?>
		
	</ul>
</div>