<?php get_header(); ?>

<?php echo get_new_royalslider(1); ?>

<section class="blurb">

    <?php query_posts( 'p=67&post_type=site_content' ); if(have_posts()) : while(have_posts()) : the_post(); ?>

			<?php 
            the_field('blurb_title');
            the_content(); ?>
			<a href="<?php the_field('blurb_link'); ?>" class="agsc_button"><?php the_field('blurb_link_text');?></a>
            
    <?php endwhile; endif; wp_reset_query(); ?>
    

</section>

		<div id="work-home">
		<h2>Recent Work</h2>
			<div id="work">
			
			<style>
			#work {margin:30px auto 0 auto;}
			</style>
			
			<?php query_posts( 'post_type=work&tag=featured' ); if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		
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
			<a href="<?php bloginfo('home'); ?>/work" class="agsc_button">Browse all projects</a>
		</div>


<?php get_footer(); ?>