<?php
/*
 * Core Wonderflux loop template part
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'loop' template part 'loop-404.php' (location specific) or 'loop.php' (fallback if location specific file not available)
 * - Using the 'loop-content' template part 'loop-content-404.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */

wfloop_before(); //WF display hook

if (!have_posts())
	if (is_search())
		get_template_part('loop-content', 'no-search-results');
	else
		get_template_part('loop-content', '404');
else
	while (have_posts()) : the_post();
		wfx_get_template_part('part=loop-content'); // Setup all location aware template parts
	endwhile;

wfx_page_counter('navigation=Y');

wfloop_after(); //WF display hook
?>