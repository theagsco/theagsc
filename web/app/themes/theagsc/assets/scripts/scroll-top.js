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
