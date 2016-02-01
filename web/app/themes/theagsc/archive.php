<nav class="sub-nav">
	<ul>
		<li><a href="<?= esc_url(home_url('/')); ?>community/tutorials" id="tutorials">Tutorials</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>community/articles" id="articles">Articles</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>community/quick-tips" id="tips">Quick Tips</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>community/interviews" id="interviews">Interviews</a></li>
		<li><a href="<?= esc_url(home_url('/')); ?>community/news" id="news">News</a></li>
	</ul>
</nav>
<div style="clear: both;"></div>

<!-- <h1 class="entry-title"><?php echo single_cat_title('',false) ?></h1> -->

<div id="community" data-columns>

<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>

	<div class="item"><a href="<?php the_permalink();?>" class="community-post">
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
	
</div><!-- community -->
