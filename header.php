<?php
/**
 * Wonderflux header template
 *
 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Using the 'header-content' template part
 * - For example 'header-content-category.php' for category view or 'header-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 * - IMPORTANT - if you do this, ensure you keep all Wonderflux hooks intact!
 *
 * @package Wonderflux
 */

wf_output_start(); //WF display hook

?>

<html <?php language_attributes(); ?>>

<head>
<?php
wf_head_meta();
wp_head();
?>

</head>
<?php

wf_after_head(); //WF display hook

// NOTE: Wonderflux adds additional classes using the WordPress core WordPress 'body_class' filter
// Use filter 'wflux_body_class_browser' : browser detection CSS class output
// Use filter 'wflux_body_class_layout' : layout description classes
// NOTE: We have to break out of PHP because theme testers don't see body_class() properly otherwise - boo ;(
?>

<body <?php body_class(); ?>>

<?php

wfbody_before_wrapper(); //WF display hook
wfheader_before_wrapper(); //WF display hook

wfheader_before_container(); //WF display hook

echo apply_filters( 'wflux_layout_header_container_open', '<div class="container" id="header">' );

	wfheader_before_content(); //WF display hook
	wfx_get_template_part('part=header-content'); // Setup all location aware template parts
	wfheader_after_content(); //WF display hook

echo apply_filters( 'wflux_layout_header_container_close', '</div>' );

wfheader_after_container(); //WF display hook
wfheader_after_wrapper(); //WF display hook
?>