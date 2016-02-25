<?php
/**
 * Template Name: Buffon
 */
?>

<p class="islaScript">Introducing</p>
<h1 class="buffonTitle">Buffon</h1>
<div id="burst">
  <img class="svg" id="red" src="<?php echo get_template_directory_uri(); ?>/dist/images/drop.svg">
  <img class="svg" id="yellow" src="<?php echo get_template_directory_uri(); ?>/dist/images/drop.svg">
  <img class="svg" id="blue" src="<?php echo get_template_directory_uri(); ?>/dist/images/drop.svg">
  <img class="svg" id="pink" src="<?php echo get_template_directory_uri(); ?>/dist/images/drop.svg">
</div>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>
