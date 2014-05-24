<div style="clear:both"></div>
</div><!--content-->

<div style="clear:both"></div>

<div class="top_wrapper"><a href="#" class="top">Go to top</a></div>

</div><!--container-->

<?php wp_footer(); ?>

<div style="clear:both"></div>

<div id="footer">

<div class="container">
<div class="content body_content">

	<div id="footer-info-wrapper">
		<div id="footer-info">
	    <div class="footer-row">
	       	<a href="<?php bloginfo('home'); ?>" class="logo-footer"><?php bloginfo('name'); ?></a>
	        <p> | </p>
	        <p>Australian Made</p>
	        <p> | </p>
            <p>&copy; <?php echo date('Y'); ?></p>
            <p> | </p>
            <a href="mailto:hello@theagsc.com" title="Email The AGSC">hello@theagsc.com</a>
            <p> | </p>
            <p><div class="fb-like" data-href="http://facebook.com/theagsc" data-width="300" data-height="50" data-colorscheme="light" data-layout="button" data-action="like" data-show-faces="false" data-send="false"></div></p>
            <p><a href="https://twitter.com/theagsc" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @TheAGSC</a></p>
        <div style="clear:both;"></div>
        </div><!--footer-row-->
        <div id="footer-mission" class="footer-row">
            <?php query_posts( 'p=90&post_type=site_content' ); if(have_posts()) : while(have_posts()) : the_post(); 
            the_field('blurb_title');  
            endwhile; endif; wp_reset_query(); ?>
	        <div style="clear:both;"></div>
        </div><!--footer-row-->
      </div><!--footer-info-->
    </div><!--footer-info-wrapper-->
    
    <div id="footer-sub">
    <h2 class="">Subscribe:</h2>
    	    <!-- Begin MailChimp Signup Form -->
        <div id="mc_embed_signup">
        <form action="http://dvclmn.us6.list-manage2.com/subscribe/post?u=9920b985fc240cea2ad71e390&amp;id=2321c542f4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <div class="mc-field-group">
            <input type="email" value="" name="EMAIL" class="required email agsc-field" id="mce-EMAIL" placeholder="name@url.com and press enter">
        </div>
            <div id="mce-responses" class="clear">
                <div class="response" id="mce-error-response" style="display:none"></div>
                <div class="response" id="mce-success-response" style="display:none"></div>
            </div>
            <div style="position: absolute; left: -5000px;"><input type="text" name="b_9920b985fc240cea2ad71e390_2321c542f4" value=""></div>
            <input type="submit" value="m" name="subscribe" id="mc-embedded-subscribe" class="button icon-mail">
        </form>
        </div>
    <!--End mc_embed_signup-->

    </div><!--footer-sub-->
</div><!--content-->
</div><!--container-->
<div style="clear:both"></div>
</div><!--footer-->

<script src="<?php bloginfo('template_directory'); ?>/scripts/stickyfloat.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/scripts/waypoints.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/scripts/waypoints-sticky.js"></script>

<script>

jQuery(document).ready(function() {
	
	jQuery('section.woo-account-section').each(function() {
         if (jQuery(this).text() == "") {
              jQuery(this).addClass('empty');
         }
	});

	jQuery('.button').removeClass('button').addClass('agsc_button');

	//adding classes to Community menu for icons
	jQuery('.menu-item-32 a').addClass('icon-articles icon-item');
	jQuery('.menu-item-33 a').addClass('icon-interviews icon-item');
	jQuery('.menu-item-35 a').addClass('icon-resources icon-item');
	jQuery('.menu-item-37 a').addClass('icon-tutorials icon-item');
	jQuery('.menu-item-36 a').addClass('icon-spotlight icon-item');
	jQuery('.menu-item-34 a').addClass('icon-news icon-item');
	jQuery('.menu-item-1205 a').addClass('icon-image icon-item');

});

</script>

<?php if (is_home()) { ?>

	<script>
	
	jQuery(document).ready(function() {
	
		// make home header fixed
		jQuery(window).scroll(function() {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('body').addClass('fixed_header');
			}
			else if (jQuery(this).scrollTop() < 100) {
				jQuery('body').removeClass('fixed_header');
			}
			
		});
	});
	</script>
	
<?php } else { ?>

	<script>
	
	jQuery(document).ready(function() {
	
		// make page header fixed
		jQuery(window).scroll(function() {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('body').addClass('fixed_header');
			}
			else if (jQuery(this).scrollTop() < 100) {
				jQuery('body').removeClass('fixed_header');
			}
			
		});
	});
	
	</script>
	<?php } ?>
	
	<?php if (is_category('community')) { ?>
	
		<script>DisableKeyboardShake = true;</script>
				
	<?php } ?>
		
<script>


// Preview Pattern Button
	
jQuery(document).ready(function() {
	
	
	jQuery(".preview_pattern_button").click(function() {
		
		if (jQuery(this).hasClass("previewButtonSelected")) {
			jQuery("body").css("background-image", "").removeClass("pattern-on");
			jQuery(this).removeClass("previewButtonSelected");
		} else {
			jQuery("body").css("background-image", "url('" + jQuery(this).attr("img") + "')").addClass("pattern-on");
			jQuery(this).addClass("previewButtonSelected");		
		}
	});
	

	
	// Show or hide the back to top button
	jQuery(window).scroll(function() {
		if (jQuery(this).scrollTop() > 400) {
			jQuery('.top').fadeIn(300);
		} else {
			jQuery('.top').fadeOut(400);
		}
		
	});
	// Animate the scroll to top
	jQuery('.top').click(function(event) {
		event.preventDefault();
		
		jQuery('html, body').animate({scrollTop: 0}, 300);
	})
	
		jQuery('.top_wrapper').stickyfloat( {
		duration : 0,
		stickToBottom: true,
		lockBottom: true
	});

	
	//RoyalSlider inner shadow
	jQuery('.royalSlider').append('<span class="inner-shadow"></span>');
	
	//Nice little numbers instead of dots for Royal Slider
	jQuery('.royalSlider .rsNavItem').each(function(i) {
	    jQuery(this).html(i + 1)
    }); 
	
	
	//Function for clicking on the little search icon (as opposed to pressing 's')
    jQuery('.search-button').click(function(){
	    jQuery(this).toggleClass("icon-cancel");
	    jQuery("#search").toggleClass("active");
	});
	
	//Function for clicking on the menu button
	    jQuery('.menu-button').click(function(){
	    jQuery(this).toggleClass("menu-button-on");
	    jQuery('header').toggleClass("menu-on");
	    jQuery('.body_content').toggleClass('menu-fade');
	});
	
/*
	//Function for clicking on the preview pattern button
	    jQuery('.preview_pattern_button').click(function(){
	    jQuery(this).toggleClass("pattern_on");
	    jQuery('body').toggleClass("preview_pattern");
	});
*/


	
	//Keeps hovered main menu items hovered even when mouse is over the submenu
	jQuery("#menus ul.sub-menu").hover(function() {
		jQuery(this).prev("a").css("color", "#f75231");
	}, function() {
		jQuery(this).prev("a").css("color", "#333030");
	});
	

	//Google Analytics
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	ga('create', 'UA-38725540-3', 'theagsc.com');
	ga('send', 'pageview');


});

	//Keyboard shortcuts
	jQuery(document).keydown(function(e){

	if 			( e.keyCode === 27 ) { // esc
		jQuery(".search-button").removeClass("icon-cancel");
		jQuery("#search").removeClass("active");
		// jQuery(".popup").addClass("hide-popup");
		jQuery(".popup").fadeOut('fast');
		jQuery(".added_to_cart").fadeOut('fast');
	}
	else if 	( e.keyCode === 84 && !jQuery("input, textarea").is(":focus") )  { // t
		jQuery(".tag_wrapper").toggleClass("on");
	}
	else if 	( e.keyCode === 72 && !jQuery("input, textarea").is(":focus") ) { // h
		jQuery(".content").toggleClass("hide-sidebar");
	}
	else if 	( e.keyCode === 73 && !jQuery("input, textarea").is(":focus") ) { // i
		jQuery("html").toggleClass("inverted");
	}
	else if 	( e.keyCode === 83 && !jQuery("input, textarea").is(":focus") ) { // s
		window.scrollTo(0, 0);
		jQuery(".search-button").toggleClass("icon-cancel");
		jQuery("#search").toggleClass("active");
		jQuery("#search").focus();
	return false;
	}
	

});

	//Left and Right shortcuts for navigating through content
	jQuery(document).keydown(function(e){
	
	if (jQuery("input, textarea").is(":focus")) {
		return true;	
	}
		
    var url = false;
    
    if (e.which == 37 ) {  // Left arrow 
        url = jQuery('.keyboardleft a').attr('href'); 
    }
    else if (e.which == 39 ) {  // Right arrow 
        url = jQuery('.keyboardright a').attr('href');
    } else {
		return true;	
	}
	
	
	url = typeof url !== 'string' ? false : url;
	
    if (url) {
        window.location = url;
    } else if (!DisableKeyboardShake) {
		jQuery(".post").effect("shake", {times:1, distance:9, direction:"right"}, 200);	
	}
	
});


jQuery(window).load(function() {

//Isotope

jQuery.Isotope.prototype._getCenteredMasonryColumns = function() {
    this.width = this.element.width();
    
    var parentWidth = this.element.parent().width();
    
                  // i.e. options.masonry && options.masonry.columnWidth
    var colW = this.options.masonry && this.options.masonry.columnWidth ||
                  // or use the size of the first item
                  this.jQueryfilteredAtoms.outerWidth(true) ||
                  // if there's no items, use size of container
                  parentWidth;
    
    var cols = Math.floor( parentWidth / colW );
    cols = Math.max( cols, 1 );

    // i.e. this.masonry.cols = ....
    this.masonry.cols = cols;
    // i.e. this.masonry.columnWidth = ...
    this.masonry.columnWidth = colW;
  };
  
  jQuery.Isotope.prototype._masonryReset = function() {
    // layout-specific props
    this.masonry = {};
    // FIXME shouldn't have to call this again
    this._getCenteredMasonryColumns();
    var i = this.masonry.cols;
    this.masonry.colYs = [];
    while (i--) {
      this.masonry.colYs.push( 0 );
    }
  };

  jQuery.Isotope.prototype._masonryResizeChanged = function() {
    var prevColCount = this.masonry.cols;
    // get updated colCount
    this._getCenteredMasonryColumns();
    return ( this.masonry.cols !== prevColCount );
  };
  
  jQuery.Isotope.prototype._masonryGetContainerSize = function() {
    var unusedCols = 0,
        i = this.masonry.cols;
    // count unused columns
    while ( --i ) {
      if ( this.masonry.colYs[i] !== 0 ) {
        break;
      }
      unusedCols++;
    }
    
    return {
          height : Math.max.apply( Math, this.masonry.colYs ),
          // fit container to columns that have been used;
          width : (this.masonry.cols - unusedCols) * this.masonry.columnWidth
        };
  };


jQuery(function(){
      
      var jQuerycontainer = jQuery('#work');

      jQuerycontainer.isotope({
        itemSelector : '.item',
		masonry : {
			columnWidth: 190
		}
		});
      

		jQuery(".FilterLink").click(function(){
      //jQueryoptionLinks.click(function(){
        var jQuerythis = jQuery(this);
        // don't proceed if already selected
        /*
		if ( jQuerythis.hasClass('selected') ) {
          return false;
        }
		*/
		
		 // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var jQueryoptionSet = jQuerythis.parents('.option-set');
		var options = {},
            key = jQueryoptionSet.attr('data-option-key'),
            value = jQuerythis.attr('data-option-value');
			
		
		
		if (jQuerythis.hasClass("selected")) {
			jQueryoptionSet.find('.selected').removeClass('selected');
			value = "*";
		} else {
			jQueryoptionSet.find('.selected').removeClass('selected');
			jQuerythis.addClass('selected');
		}
		
		
       
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( jQuerythis, options )
        } else {
          // otherwise, apply new options
          jQuerycontainer.isotope( options );
        }
        
        return false;
      });

      
    });
});

jQuery(document).ready(function() {

/*
	jQuery('.tag_wrapper').waypoint('sticky', {
		wrapper: '<div class="sticky-tag_wrapper" />',		
	});
*/
	jQuery('.options_wrapper').waypoint('sticky', {
		wrapper: '<div class="sticky-options_wrapper" />',		
	});
	jQuery('.categories').waypoint('sticky', {
		wrapper: '<div class="sticky-categories" />',		
	});
	
	<?PHP if (defined("HideKeyboardShotcutNotification") && HideKeyboardShotcutNotification == false) { ?>
		jQuery(".popup").show();
	<?PHP } ?>
	
});


</script>

</body>
</html>