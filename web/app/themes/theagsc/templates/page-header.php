<?php use Roots\Sage\Titles; ?>

<div id="page-header">
  <h1 class="entry-title"><?= Titles\title(); ?></h1>
  <?php // get_template_part('templates/entry-meta'); ?>
  <?php the_post_thumbnail();?>
</div>

