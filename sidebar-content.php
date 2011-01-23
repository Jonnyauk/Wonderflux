<?php
/**
 * The core get_template_part sidebar content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.6
 */
// If you are using any of the Wonderflux extra display functions (ie wf_get_page) you currently need to setup a new instance at the top of each file you are using them in
$wfx = new wflux_display_ex;
?>
<div class="sidebar-box">

	<h4 class="sidebar-title">Pages</h4>
	<ul><?php wp_list_pages('title_li=' ); ?></ul>

	<h4 class="sidebar-title">Categories</h4>
	<ul><?php wp_list_categories('show_count=1&title_li='); ?></ul>

	<h4 class="sidebar-title">Archives</h4>
	<ul><?php wp_get_archives('type=monthly'); ?></ul>

	<?php $wfx->wf_edit_meta('userintro=Hello&wfcontrols=Y'); ?>

</div>