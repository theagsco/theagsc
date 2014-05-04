<?php get_header(); ?>

<div id="community-wrapper">

    <div id="community">
    
    <?php // echo get_new_royalslider(1); ?>
    
	    	<?php if ( !is_category( 'community' )) { ?>
	    		<h2 class="viewing">Viewing posts from <?php single_cat_title(''); ?> | <a href="<?php bloginfo('home'); ?>/community">View all</a></h2>
			<?php } ?>
			
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
			<div class="post">
		
                <?php if (has_post_thumbnail()) { ?>
                	<a href="<?php the_permalink(); ?>" class="post_thumb"><?php the_post_thumbnail(); ?></a>
                <?php } else { ?>
	                
	                <style></style>
	                
                <?php } ?>

                <div class="title_details">
                
                	<h1 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php get_template_part( 'post-details-community' ); ?>

                </div><!--title_details-->
                
                	<div class="date_wrapper">
				    	<span class="date-day"><?php the_time('d'); ?></span>
				    	<span class="date-month"><?php the_time('M'); ?></span>
					</div>

                
                <?php the_excerpt(); ?>
                
                <a href="<?php the_permalink(); ?>" class="agsc_button keep_reading">Keep Reading...</a>
                
				<span class="sep"></span>

			</div><!--post-->
			
        <?php endwhile; // end while ?>
        
        <?php else : // end while ?>
        
				<?php get_template_part( 'no-post' ); ?>
        
        <?php endif; // end if ?>
        
        <?php wp_pagenavi(); ?>
        
    </div><!--community-wrapper-->
    
</div><!--community-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>