	<?php get_header(); ?>

<style>
	
	.post img {margin:0 auto 50px auto;}
	
</style>


		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
			<div class="post">
			
                <?php if( get_field('work_main_image') ): ?><img src="<?php the_field('work_main_image'); ?>" alt="" class="work_image" style="padding-top:<?php the_field('work_image_padding'); ?>px" /><?php endif; ?>

			<?php 
				if( ! get_field('no_description') )
				{ ?>
				
                                
				<div class="work">
                <h1 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
					<?php get_template_part( 'post-details-work' ); ?>
		                
					<?php the_content(); ?>
				</div>

				<?php }	else { ?>
				
				<?php echo isset($WPPostNavigation)?$WPPostNavigation->WP_Custom_Post_Navigation():''; ?>
				
				<?php }?>
				
			
                
			</div><!--post-->
			
        <?php endwhile; // end while ?>
        
        <?php else : // end while ?>
        
				<?php get_template_part( 'no-post' ); ?>
        
        <?php endif; // end if ?>

<?php get_footer(); ?>