<?php
/**
 * Wonderflux header template
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

?>

<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 9]>
<html class="ie ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[ if !(IE 7) | !(IE 8) | !(IE 9) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

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