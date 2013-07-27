<?php
/*
 * Core Wonderflux attachment template
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'loop' template part 'loop-attachment.php' (location specific) or 'loop.php' (fallback if location specific file not available)
 * - Using the 'loop-content' template part 'loop-content-attachment.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 * @package Wonderflux
 */
get_header();
wfmain_before_wrapper(); //WF display hook

wfmain_before_all_container(); //WF display hook
wfmain_before_attachment_container(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_open', '<div class="container" id="main-content">' );

	// Main content
	wfmain_before_all_content(); //WF display hook
	wfmain_before_attachment_content(); //WF display hook

	get_template_part( 'loop', 'attachment' );

	wfmain_after_attachment_content(); //WF display hook
	wfmain_after_all_content(); //WF display hook

	wfx_get_sidebar(''); //WF WordPress get_sidebar function replacement

	// Display hooks for after main content and sidebar
	wfmain_after_attachment_main_content(); //WF display hook
	wfmain_after_all_main_content(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_close', '</div>' );

wfmain_after_attachment_container(); //WF display hook
wfmain_after_all_container(); //WF display hook

wfmain_after_wrapper(); //WF display hook

get_footer();
?>