<?php get_header(); ?>

<div id="community-wrapper">

    <div id="community">
    
    
    		
		    <h2 class="search_results">Displaying search results for "<?php the_search_query(); ?>"</h2>
    
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
  <div>
    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="Search" />
  </div>
</form>

<div id="results">
    
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>	
		
			
			<div class="post">
		
				<?php if (has_post_thumbnail()) {?><div class="post_thumb"><?php the_post_thumbnail(); ?></div><? } ?>
				
                	<h1 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

                <?php the_excerpt(); ?>
                
                <a href="<?php the_permalink(); ?>" class="agsc_button keep_reading">Keep Reading...</a>
        <div style="clear:both"></div>
                
                <div class="border_box"><?php the_tags(); ?></div>

				<span class="sep"></span>


        <div style="clear:both"></div>

			</div><!--post-->
			
        <?php endwhile; // end while ?>
        
        <?php else : // end while ?>
        
				<?php get_template_part( 'no-post-search' ); ?>
        
        <?php endif; // end if ?>
        
        
</div><!--results-->
    </div><!--community-wrapper-->
    
</div><!--community-->


<?php get_footer(); ?>