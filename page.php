<?php
/*
 * Core Wonderflux page template
 * NOTE: Most people can use simple template parts (see reference below) in their child theme instead of replacing this whole file
 * By using the Wonderflux hook system, you can insert any CSS styling/code before or after any content both globally or depending on view type
 *
 * @template-part loop-page.php
 * @fallback-template-part loop.php
 * @what-page is_page()
 * @package Wonderflux
 */

get_header();
wfmain_before_wrapper(); //WF display hook

wfmain_before_all_container(); //WF display hook
// This template gets used when a static page is set to home
if (is_home() || is_front_page()) : wfmain_before_home_container(); //WF display hook
	else: wfmain_before_page_container(); //WF display hook
endif;
?>

<div class="container" id="main-content">

	<?php // Main content
	wfmain_before_all_content(); //WF display hook
	if (is_home() || is_front_page()) : wfmain_before_home_content(); //WF display hook
		else: wfmain_before_page_content(); //WF display hook
	endif;

	get_template_part( 'loop', 'page' );

	if (is_home() || is_front_page()) : wfmain_after_home_content(); //WF display hook
		else: wfmain_after_page_content(); //WF display hook
	endif;

	wfmain_after_all_content(); //WF display hook

	// Sidebar
	wfsidebar_before_all(); //WF display hook

	if (is_home() || is_front_page()) : wfsidebar_before_home(); //WF display hook
		else: wfsidebar_before_page(); //WF display hook
	endif;

	get_sidebar();

	if (is_home() || is_front_page()) : wfsidebar_after_home(); //WF display hook
		else: wfsidebar_after_page(); //WF display hook
	endif;
	wfsidebar_after_all(); //WF display hook

	// Display hooks for after main content and sidebar
	if (is_home() || is_front_page()) : wfmain_after_home_main_content(); //WF display hook
		else: wfmain_after_page_main_content(); //WF display hook
	endif;

	wfmain_after_all_main_content(); //WF display hook
	?>

</div>

<?php
wfmain_after_page_container(); //WF display hook
wfmain_after_all_container(); //WF display hook

wfmain_after_wrapper(); //WF display hook

get_footer();
?>