<div id="community-home" class="carousel slide">
	
	<h4>Community Posts</h4>

	<ul class="carousel-inner">

		<?php 
			$the_query = new WP_Query(array(
			'category_name' => 'community', 
			'posts_per_page' => 1 
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<li class="item active"><a href="<?php the_permalink();?>" class="community-post">
			<?php the_post_thumbnail('large');?>
			<div class="title-excerpt">
				<h2><?php the_title();?></h2>
				<p><?php the_excerpt();?></p>
			</div>
		</a></li><!-- item active -->
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
		
		<?php 
			$the_query = new WP_Query(array(
			'category_name' => 'community', 
			'posts_per_page' => 2, 
			'offset' => 1 
			)); 
			while ( $the_query->have_posts() ) : 
			$the_query->the_post();
		?>
		<li class="item"><a href="<?php the_permalink();?>" class="community-post">
			<?php the_post_thumbnail('large');?>
			<div class="title-excerpt">
				<h2><?php the_title();?></h2>
				<p><?php the_excerpt();?></p>
			</div>
		</a></li><!-- item -->
		<?php 
			endwhile; 
			wp_reset_postdata();
		?>
	</ul><!-- carousel-inner -->
	
	<a href="<?= esc_url(home_url('/')); ?>" class="btn btn-green"><span>Visit the Community</span><img class="svg" src="<?php bloginfo('template_directory'); ?>/assets/images/icons_community.svg"/></a>

</div><!-- community -->

Lorem ipsum dolor sit amet, sed ei ubique appareat, cum in mazim qualisque inciderint. Ius in delenit nostrum assueverit. An mea postea platonem, id vix phaedrum similique. Vim cu sint mollis docendi, ea sale doming discere est.

Populo habemus sed te. In instructior conclusionemque his. Munere aliquid percipit ut duo. Populo partiendo ex qui. Nam in accusam tractatos dignissim, ius ut quas scripserit.

Cu sed unum ullum adversarium, feugiat indoctum interpretaris quo ad, his ut liber mollis. Postea deleniti per in. Et invidunt gloriatur nec, labitur fabulas no usu. Ex mel iriure recusabo senserit, in eos velit eruditi sensibus. Pri cu posse omnium prompta, in quidam pericula cum, equidem nominavi pro cu. Per et purto aperiam, nam hinc velit omittantur et.

Ei dicit intellegebat vel, ex probo audiam sed. Ad has sumo semper eripuit, vel atqui scriptorem contentiones ex. Cum duis sententiae an, elit sonet euismod ius te. Ea facete utroque vis, modus dolores omittantur pri ex. An persius fabulas convenire sea. Nam unum vocent rationibus te, in duo autem ridens incorrupte, mea purto minim prompta cu.

Ne usu porro nostro doctus, fastidii hendrerit at usu. Ceteros epicuri corrumpit ad vim, has causae debitis interpretaris an. Iudico persius ornatus quo ad, novum dissentiunt ad quo. Est ex bonorum fastidii.

Lorem ipsum dolor sit amet, sed ei ubique appareat, cum in mazim qualisque inciderint. Ius in delenit nostrum assueverit. An mea postea platonem, id vix phaedrum similique. Vim cu sint mollis docendi, ea sale doming discere est.

Populo habemus sed te. In instructior conclusionemque his. Munere aliquid percipit ut duo. Populo partiendo ex qui. Nam in accusam tractatos dignissim, ius ut quas scripserit.

Cu sed unum ullum adversarium, feugiat indoctum interpretaris quo ad, his ut liber mollis. Postea deleniti per in. Et invidunt gloriatur nec, labitur fabulas no usu. Ex mel iriure recusabo senserit, in eos velit eruditi sensibus. Pri cu posse omnium prompta, in quidam pericula cum, equidem nominavi pro cu. Per et purto aperiam, nam hinc velit omittantur et.

Ei dicit intellegebat vel, ex probo audiam sed. Ad has sumo semper eripuit, vel atqui scriptorem contentiones ex. Cum duis sententiae an, elit sonet euismod ius te. Ea facete utroque vis, modus dolores omittantur pri ex. An persius fabulas convenire sea. Nam unum vocent rationibus te, in duo autem ridens incorrupte, mea purto minim prompta cu.

Ne usu porro nostro doctus, fastidii hendrerit at usu. Ceteros epicuri corrumpit ad vim, has causae debitis interpretaris an. Iudico persius ornatus quo ad, novum dissentiunt ad quo. Est ex bonorum fastidii.

Lorem ipsum dolor sit amet, sed ei ubique appareat, cum in mazim qualisque inciderint. Ius in delenit nostrum assueverit. An mea postea platonem, id vix phaedrum similique. Vim cu sint mollis docendi, ea sale doming discere est.

Populo habemus sed te. In instructior conclusionemque his. Munere aliquid percipit ut duo. Populo partiendo ex qui. Nam in accusam tractatos dignissim, ius ut quas scripserit.

Cu sed unum ullum adversarium, feugiat indoctum interpretaris quo ad, his ut liber mollis. Postea deleniti per in. Et invidunt gloriatur nec, labitur fabulas no usu. Ex mel iriure recusabo senserit, in eos velit eruditi sensibus. Pri cu posse omnium prompta, in quidam pericula cum, equidem nominavi pro cu. Per et purto aperiam, nam hinc velit omittantur et.

Ei dicit intellegebat vel, ex probo audiam sed. Ad has sumo semper eripuit, vel atqui scriptorem contentiones ex. Cum duis sententiae an, elit sonet euismod ius te. Ea facete utroque vis, modus dolores omittantur pri ex. An persius fabulas convenire sea. Nam unum vocent rationibus te, in duo autem ridens incorrupte, mea purto minim prompta cu.

Ne usu porro nostro doctus, fastidii hendrerit at usu. Ceteros epicuri corrumpit ad vim, has causae debitis interpretaris an. Iudico persius ornatus quo ad, novum dissentiunt ad quo. Est ex bonorum fastidii.