<nav class="sub-nav">
	<ul>
		<li><p><em>Filter:</em></p></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog" id="all">All</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/tutorials" id="tutorials">Tutorials</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/articles" id="articles">Articles</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/quick-tips" id="tips">Quick Tips</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/interviews" id="interviews">Interviews</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>blog/news" id="news">News</a></li>
	</ul>
</nav>
<div style="clear: both;"></div>

<!-- <h1 class="entry-title"><?php echo single_cat_title('',false) ?></h1> -->

<div id="blog" class="masonry" data-columns>

<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>

	<div class="item"><a href="<?php the_permalink();?>" class="blog-post">
		<?php the_post_thumbnail('large');?>
		<div class="title-excerpt">
			<h2><?php the_title();?></h2>
			<p><?php the_excerpt();?></p>
		</div>
	</a></div>

	<?php endwhile;
else :
	get_template_part('templates/no-posts');

endif;
?>


	</div><!-- div one -->
</div><!-- div two -->

</div><!-- blog -->
