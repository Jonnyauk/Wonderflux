<?php
/*
 * Core Wonderflux header template
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'header-content' template part
 * - For example 'header-content-category.php' for category view or 'header-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */

wf_output_start(); //WF display hook
echo '<head>';
wf_head_meta();
wp_head();
echo '</head>';
wf_after_head(); //WF display hook

wfx_display_body_tag(''); // IMPORTANT - Inserts dynamic <body> tag with extra Wonderflux CSS classes

wfbody_before_wrapper(); //WF display hook
wfheader_before_wrapper(); //WF display hook

wfheader_before_container(); //WF display hook
?>

<div class="container" id="header-content">

	<?php
	wfheader_before_content(); //WF display hook
	wfx_get_template_part('part=header-content'); // Setup all location aware template parts
	wfheader_after_content(); //WF display hook
	?>

</div>

<?php
wfheader_after_container(); //WF display hook
wfheader_after_wrapper(); //WF display hook
?>