<?php
/*
 * Core Wonderflux BuddyPress template
 * Acts as a 'wrapper' around BuddyPress content

 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 * - IMPORTANT - if you do this, ensure you keep all Wonderflux hooks intact!
 * - IMPORTANT - duplicate directory structure in your child theme - /buddypress/buddypress.php
 *
 * - Want to do more with BuddyPress?
 * - Duplicate the following directories to amend core CSS and JS:
 * - buddypress/css
 * - buddypress/js
 *
 * - Want to do even more with BuddyPress template files?
 * - Duplicate the following directories into your child theme and have fun!
 * - buddypress/activity
 * - buddypress/blogs
 * - buddypress/forums
 * - buddypress/groups
 * - buddypress/members
 *
 * @package Wonderflux
 */

get_header();
wfmain_before_wrapper(); //WF display hook

wfmain_before_all_container(); //WF display hook
wfmain_before_bp_container(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_open', '<div class="container" id="main-content">' );

	// Main content
	wfmain_before_all_content(); //WF display hook
	wfmain_before_bp_content(); //WF display hook

	get_template_part( 'loop', 'wonderflux' );

	wfmain_after_bp_content(); //WF display hook
	wfmain_after_all_content(); //WF display hook

	wfx_get_sidebar(''); //WF WordPress get_sidebar function replacement

	// Display hooks for after main content and sidebar
	wfmain_after_bp_main_content(); //WF display hook
	wfmain_after_all_main_content(); //WF display hook

echo apply_filters( 'wflux_layout_content_container_close', '</div>' );

wfmain_after_bp_container(); //WF display hook
wfmain_after_all_container(); //WF display hook

wfmain_after_wrapper(); //WF display hook

get_footer();
?>