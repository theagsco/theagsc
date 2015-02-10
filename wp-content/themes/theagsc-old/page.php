<?php get_header(); ?>
<?php if (is_page('work')) { ?>

<style>
	
	.content {padding-bottom:65px;}
	
</style>


	<div id="work">
						
		<?php
		
		// WP_Query arguments
		$args = array (
			'post_type'              => 'work',
			'pagination'             => false,
			'posts_per_page'         => '-1',
		);
		
		// The Query
		$work = new WP_Query( $args );
		
		// The Loop
		if ( $work->have_posts() ) {
			while ( $work->have_posts() ) {
				$work->the_post(); ?>
		
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
		
		
			<?php }
			
		} else {
		
			get_template_part( 'no-post' ); 
			
		}
		
		// Restore original Post Data
		wp_reset_postdata();
		
		?>
						
						
	</div><!--work-->

    
<!-- /WORK -->
    
<?php } else { ?>



<div id="community-wrapper">
<div id="community">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
	<div class="
	
	<?php if (is_page('contact')) { ?>
			page-block
		<?php } else { ?>
			post
		<?php } ?>
	">
	
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