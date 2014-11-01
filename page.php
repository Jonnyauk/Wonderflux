<?php
/**
 * Wonderflux page template
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'loop' template part 'loop-page.php' (location specific) or 'loop.php' (fallback if location specific file not available)
 * - Using the 'loop-content' template part 'loop-content-page.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */

get_header();
wfmain_before_wrapper(); //WF display hook

wfmain_before_all_container(); //WF display hook
// This template gets used when a static page is set to home
if (is_home() || is_front_page()) : wfmain_before_home_container(); //WF display hook
	else: wfmain_before_page_container(); //WF display hook
endif;

echo apply_filters( 'wflux_layout_content_container_open', '<div class="container" id="main-content">' );

	// Main content
	wfmain_before_all_content(); //WF display hook
	if (is_home() || is_front_page()) : wfmain_before_home_content(); //WF display hook
		else: wfmain_before_page_content(); //WF display hook
	endif;

	get_template_part( 'loop', 'page' );

	if (is_home() || is_front_page()) : wfmain_after_home_content(); //WF display hook
		else: wfmain_after_page_content(); //WF display hook
	endif;

	wfmain_after_all_content(); //WF display hook

	wfx_get_sidebar(''); //WF WordPress get_sidebar function replacement

	// Display hooks for after main content and sidebar
	if (is_home() || is_front_page()) : wfmain_after_home_main_content(); //WF display hook
		else: wfmain_after_page_main_content(); //WF display hook
	endif;
	wfmain_after_all_main_content(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_close', '</div>' );

if (is_home() || is_front_page()) : wfmain_after_home_container(); //WF display hook
	else: wfmain_after_page_container(); //WF display hook
endif;

wfmain_after_all_container(); //WF display hook

wfmain_after_wrapper(); //WF display hook

get_footer();
?>