<?php while (have_posts()) : the_post(); ?>
<div class="post-entry clearfix"> <!-- Main wrapper -->
    <div class="post-entry-content"> <!-- Post-entry-content -->
            <h2><a href="<?php the_permalink(' ') ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="post-entry-date">Posted on <?php the_time('F Y') ?> with <?php comments_popup_link('0 Comments', '1 Comment', '% Comments'); ?></div>
                    <?php the_excerpt(); ?>
                    <a href="<?php the_permalink(' ') ?>" class="post-entry-read-more" title="<?php the_title(); ?>">Read More ?</a>
    </div><!-- END of post-entry-content -->
</div><!--End of main wrapper -->
<?php endwhile; ?>