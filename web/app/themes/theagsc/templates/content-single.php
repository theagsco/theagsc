<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
      <?php the_post_thumbnail();?>
    </header>
    
    <div class="entry-content">
	      <?php the_content(); ?>
    </div>
    <footer>
	    
<?php $prev_post = get_previous_post();
if($prev_post) {
$prev_title = strip_tags(str_replace('"', '', $prev_post->post_title));
echo "\t" . '<a class="prev" rel="prev" href="' . get_permalink($prev_post->ID) . '" title="' . $prev_title. '"><span>Previous post</span></a>' . "\n";
}

$next_post = get_next_post();
if($next_post) {
$next_title = strip_tags(str_replace('"', '', $next_post->post_title));
echo "\t" . '<a class="next" rel="next" href="' . get_permalink($next_post->ID) . '" title="' . $next_title. '"><span>Next post</span></a>' . "\n";
} ?>

    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
