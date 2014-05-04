<?php get_header(); ?>

<div id="community-wrapper">

    <div id="community">
    
	    	<?php if ( !is_category( 'community' )) { ?>
	    		<h2 class="viewing">Viewing posts from <?php single_cat_title(''); ?> | <a href="<?php bloginfo('home'); ?>/community">View all</a></h2>
			<?php } ?>
			
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
			<div class="post">
		
                <h1 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                
				<?php get_template_part( 'post-details-community' ); ?>
                
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