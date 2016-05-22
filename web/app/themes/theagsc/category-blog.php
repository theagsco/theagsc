<nav class="sub-nav">
	<ul>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/tutorials" id="tutorials">Tutorials</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/articles" id="articles">Articles</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/quick-tips" id="tips">Quick Tips</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/interviews" id="interviews">Interviews</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/news" id="news">News</a></li>
	</ul>
</nav>
<div style="clear: both;"></div>

<div id="blog" class="masonry" data-columns>

<?php
		$the_query = new WP_Query(array(
		'category_name' => 'blog',
		'posts_per_page' => 15,
		'paged' => $paged
		));
		while ( $the_query->have_posts() ) :
		$the_query->the_post();
	?>
	<div class="item"><a href="<?php the_permalink();?>" class="blog-post">
		<?php the_post_thumbnail('large');?>
		<div class="title-excerpt">
			<h2><?php the_title();?></h2>
			<p><?php the_excerpt();?></p>
		</div>
	</a></div>


	<?php
		endwhile;
		wp_reset_postdata();
	?>
	</div>
</div>

<span class="next"><?php next_posts_link( 'Next Page', $the_query->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Previous Page' ); ?></span>



</div><!--  -->
