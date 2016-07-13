<?php
/**
 * Wonderflux home template
 *
 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Using the 'loop' template part 'loop-home.php' (location specific) or 'loop.php' (fallback if location specific file not available)
 * - Using the 'loop-content' template part 'loop-content-home.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 * - IMPORTANT - if you do this, ensure you keep all Wonderflux hooks intact!
 *
 * @package Wonderflux
 */

get_header();
wfmain_before_wrapper(); //WF display hook

wfmain_before_all_container(); //WF display hook
wfmain_before_home_container(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_open', '<div class="container" id="main-content">' );

	// Main content
	wfmain_before_all_content(); //WF display hook
	wfmain_before_home_content(); //WF display hook

	get_template_part( 'loop', 'home' );

	wfmain_after_home_content(); //WF display hook
	wfmain_after_all_content(); //WF display hook

	wfx_get_sidebar(''); //WF WordPress get_sidebar function replacement

	// Display hooks for after main content and sidebar
	wfmain_after_home_main_content(); //WF display hook
	wfmain_after_all_main_content(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_close', '</div>' );

wfmain_after_home_container(); //WF display hook
wfmain_after_all_container(); //WF display hook

wfmain_after_wrapper(); //WF display hook

get_footer();
?>