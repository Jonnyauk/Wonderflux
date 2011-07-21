<?php
/*
 * Core Wonderflux single template
 * NOTE: Most people can use simple template parts (see reference below) in their child theme instead of replacing this whole file
 * By using the Wonderflux hook system, you can insert any CSS styling/code before or after any content both globally or depending on view type
 *
 * @template-part loop-single.php
 * @fallback-template-part loop.php
 * @what-page is_single()
 * @package Wonderflux
 */

get_header();
wfmain_before_wrapper(); //WF display hook

wfmain_before_all_container(); //WF display hook
wfmain_before_single_container(); //WF display hook
?>

<div class="container" id="main-content">

	<?php // Main content
	wfmain_before_all_content(); //WF display hook
	wfmain_before_single_content(); //WF display hook

	get_template_part( 'loop', 'single' );

	wfmain_after_single_content(); //WF display hook
	wfmain_after_all_content(); //WF display hook

	// Sidebar
	wfsidebar_before_all(); //WF display hook
	wfsidebar_before_single(); //WF display hook

	get_sidebar();

	wfsidebar_after_single(); //WF display hook
	wfsidebar_after_all(); //WF display hook

	// Display hooks for after main content and sidebar
	wfmain_after_single_main_content(); //WF display hook
	wfmain_after_all_main_content(); //WF display hook
	?>

</div>

<?php
wfmain_after_single_container(); //WF display hook
wfmain_after_all_container(); //WF display hook
wfmain_after_wrapper(); //WF display hook

get_footer();
?>