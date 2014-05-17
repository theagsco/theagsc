<?php get_header(); ?>

<div id="community-wrapper">

    <div id="community">
    
    <style>
	    .sep {display: none;}
	    .post_thumb img {margin-top: 0 !important;}
    </style>
    
		<?php while ( have_posts() ) : the_post(); ?>
		
			<div id="single-post" class="post
			
			<?php 
				if( get_field('remove_drop_cap') )
				{ ?>
				
                      no_drop_cap
                                
				<?php }	else { ?>
				
				
				<?php }?>
			
			
			
			">
		
				<div class="post_thumb"><?php the_post_thumbnail(); ?></div>
				
				<div id="post-top"></div>
				
                <div class="title_details">
                
                	<h1 class="title"><?php the_title(); ?></h1>
                	
					<?php get_template_part('post-details-community'); ?>

                </div><!--title_details-->
                
            	<div class="date_wrapper">
			    	<span class="date-day"><?php the_time('d'); ?></span>
			    	<span class="date-month"><?php the_time('M'); ?></span>
				</div>
				
				<?php get_template_part('share-button'); ?>
                
                <?php the_content(); ?>
                
                <?php get_template_part('follow-buttons'); ?>

                
			<?php comments_template(); ?>


			</div><!--post-->
			
        <?php endwhile; // end while ?>
        
                
    </div><!--community-wrapper-->
    
</div><!--community-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>