<div class="post-details">

	<div class="date">
    	<span class="date-day"><?php the_time('d'); ?></span>
    	<span class="date-month"><?php the_time('M'); ?></span>
	</div>
	
	<div class="other">
    	
    	<span class="author-cat">
        	By <a href="<?php bloginfo('home'); ?>/<?php the_author(); ?>"><?php the_author(); ?></a>, filed under 
        	<?php echo get_the_category_list(); ?>, tagged 
        	<?php echo get_the_tag_list(); ?>
        </span>
    	
	</div>

</div><!--post-details-->
                
<div style="clear:both"></div>
