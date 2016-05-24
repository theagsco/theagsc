<article>
    <div class="entry-content">

		<h1>Our Story</h1>

		<section>

			<?php
				$the_query = new WP_Query(array(
					'post_type' => 'site_content',
					'p' => '2491'

				));
				while ( $the_query->have_posts() ) :
				$the_query->the_post();
			?>
					<p><?php the_content();?></p>
			<?php
				endwhile;
				wp_reset_postdata();
			?>
		</section>

		<figcaption><img src="<?php bloginfo("template_directory"); ?>/assets/images/dave-laura.jpg" id="dave-laura"></figcaption>

		<hr>

		<h1>FAQ's</h1>

		<section>

			<?php
				$the_query = new WP_Query(array(
					'post_type' => 'site_content',
					'p' => '2493'

				));
				while ( $the_query->have_posts() ) :
				$the_query->the_post();
			?>
					<p><?php the_content();?></p>
			<?php
				endwhile;
				wp_reset_postdata();
			?>

		</section>

		<hr>

		<h1>Services</h1>

		<section>

			<?php
				$the_query = new WP_Query(array(
					'post_type' => 'site_content',
					'p' => '2495'

				));
				while ( $the_query->have_posts() ) :
				$the_query->the_post();
			?>
					<p><?php the_content();?></p>
			<?php
				endwhile;
				wp_reset_postdata();
			?>

		</section>

		<hr>

		<h1>Notable Clients</h1>

		<section>

			<?php
				$the_query = new WP_Query(array(
					'post_type' => 'site_content',
					'p' => '2539'

				));
				while ( $the_query->have_posts() ) :
				$the_query->the_post();
			?>
					<p><?php the_content();?></p>
			<?php
				endwhile;
				wp_reset_postdata();
			?>

		</section>

	</div>
</article>
</main><!-- /.main -->
</div><!-- /.content -->
</div><!-- /.wrap -->
