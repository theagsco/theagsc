$(document).ready(function() {

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
			jQuery("article").effect("shake", {times:1, distance:9, direction:"right"}, 200);	
		}
		
	});

});
