<?php get_header(); ?>
<?php if (is_page('work')) { ?>

<style>
	
	.content {padding-bottom:65px;}
	
</style>


					<div id="work">
			
			<?php query_posts( 'post_type=work' ); if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		
			<div class="item <?php  
				$terms = get_the_terms( $post->id, 'type' ); // get an array of all the terms as objects.
				$terms_slugs = array();
				foreach( $terms as $term ) {
				    $terms_slugs[] = $term->slug; echo $term->slug; // save the slugs in an array
				} ?>" data-category="<?php  
				$terms = get_the_terms( $post->id, 'type' ); // get an array of all the terms as objects.
				$terms_slugs = array();
				foreach( $terms as $term ) {
				    $terms_slugs[] = $term->slug; echo $term->slug; // save the slugs in an array
				} ?>">
				<div class="work_link"><a href="<?php the_permalink(); ?>" class="agsc_button"><?php the_title(); ?></a></div>
				<?php the_post_thumbnail(); ?>
			</div>

			
        <?php endwhile; // end while ?>
        
        <?php else : // end while ?>
        
				<?php get_template_part( 'no-post' ); ?>
        
        <?php endif; wp_reset_query(); // end if ?>
        
			</div><!--work-->

    
<!-- /WORK -->
    
<?php } else { ?>



<div id="community-wrapper">
<div id="community">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	<div class="post">
	
        <div class="title_details">
	        <h1 class="page_title"><?php the_title(); ?></h1>
        </div>
			
        <?php the_content(); ?>
	</div>
    <?php endwhile; // end while ?>
    
    <?php else : // end while ?>
    
		<?php get_template_part( 'no-post' ); ?>
    
    <?php endif; // end if ?>
    
</div>
</div>

<?php } ?>

<?php get_footer(); ?>