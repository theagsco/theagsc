<?php if ( ! is_woocommerce()) { ?>

<div id="sidebar">

	<section id="about">
	<div class="sidebar_item">
		<?php query_posts( 'p=87&post_type=sidebar_content' ); if(have_posts()) : while(have_posts()) : the_post(); ?>
			<a href="<?php the_field('about_link'); ?>"><?php the_field('about_link_text');?></a>
			<?php the_post_thumbnail(); ?>
	    <?php endwhile; endif; wp_reset_query(); ?>
	</div>
	</section>
	
	<section id="instagram">
	<div class="sidebar_item">
		<h2>Instagram</h2>
	    <div id="instafeed" class="square"><a href="http://instagram.com/theagsc"><img src="<?php bloginfo('template_directory'); ?>/images/insta.png" /></a></div>
	</div>
	</section>
	
<!--
	<section id="instagram">
	<div class="sidebar_item">
		<h2>Instagram</h2>
	    <div id="instafeed" class="square"></div>
	</div>
	</section>
	
-->
<!--
	<section id="categories">
	
		<ul class="categories sub-menu">
				<?php		
		//list terms in a given taxonomy
		$taxonomy = 'category';
		$term_args=array(
		  'hide_empty' => false,
		  'orderby' => 'name',
		  'order' => 'ASC',
		  'parent' => 1
		);
		$tax_terms = get_terms($taxonomy,$term_args);
		foreach ($tax_terms as $tax_term) {
		echo '<li class="menu-item"><a href="' .get_bloginfo('home'). '/community/' . $tax_term->slug . '" " class="icon-' . $tax_term->slug . '">' . $tax_term->name . '</a></li>';
		}
		?>	
		</ul>		

	</section>
-->

<!-- Post details section! make it sticky! -->
<section>
</section>

<div style="clear:both"></div>

</div>

<?php } ?>






