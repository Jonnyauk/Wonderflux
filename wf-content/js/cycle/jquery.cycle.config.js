// Publishers reference at http://jquery.malsup.com/cycle/options.html
// or view wonderflux/wf-content/js/cycle/jquery-cycle-readme.txt

jQuery.noConflict();
jQuery(document).ready(function($) {

	$('.wfx-cycle.slideshow-1').cycle({
		speed: 2000,  // speed of the transition (any valid fx speed value)
		timeout: 6000,  // milliseconds between slide transitions (0 to disable auto advance)
		next: 'wfx-cycle.slideshow-1',  // selector for element to use as event trigger for next slide
		pause: 0  // true to enable "pause on hover"
	});

	$('.wfx-cycle.slideshow-2').cycle({
		speed: 2000,  // speed of the transition (any valid fx speed value)
		timeout: 6000,  // milliseconds between slide transitions (0 to disable auto advance)
		next: 'wfx-cycle.slideshow-2',  // selector for element to use as event trigger for next slide
		pause: 0  // true to enable "pause on hover"
	});

	$('.wfx-cycle.slideshow-3').cycle({
		speed: 2000,  // speed of the transition (any valid fx speed value)
		timeout: 6000,  // milliseconds between slide transitions (0 to disable auto advance)
		next: 'wfx-cycle.slideshow-3',  // selector for element to use as event trigger for next slide
		pause: 0  // true to enable "pause on hover"
	});

});