<?php
/**
 * Wonderflux loop template part
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'loop' template part 'loop-404.php' (location specific) or 'loop.php' (fallback if location specific file not available)
 * - Using the 'loop-content' template part 'loop-content-404.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */

$hook_where = wfx_info_location();

wfloop_before(); //WF display hook

if ( !have_posts() ): // No posts found
	if ( is_search() )
		get_template_part('loop-content', 'no-search-results');
	else
		get_template_part('loop-content', '404');
	elseif ( isset($_GET['s']) && trim($_GET['s']) == '' ): // If no query supplied, show no results - dont like this, just override in child theme!
		get_template_part('loop-content', 'no-search-results');
		query_posts('showposts=0'); // Reset post data so page counters show incorrectly - no results = no paged results thanks!
else:

	wfloop_before_found_posts_all();
	$wfloop_before_found = 'wfloop_before_found_posts_'.$hook_where;
	$wfloop_before_found();

	while ( have_posts() ) : the_post();
		wfx_get_template_part('part=loop-content'); // Setup all location aware template parts
	endwhile;

	$wfloop_after_found = 'wfloop_after_found_posts_'.$hook_where;
	$wfloop_after_found();
	wfloop_after_found_posts_all();

endif;

wfx_page_counter('navigation=Y');

wfloop_after(); //WF display hook
?>