<?php
/*
 * Core Wonderflux header template
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'header-content' template part 'header-content-category.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */

wf_head_meta();
//NOTE: wf_head calls wp_head (after executing wf_head functions) - no need to call them both in a template!
//This builds the whole head section, no need to even put it in header.php - just concentrate on the design friends!
// It's all taken care of (and filterable or even overidden in your functions file!)

wfbody_before_wrapper(); //WF display hook
wfheader_before_wrapper(); //WF display hook

wfheader_before_container(); //WF display hook
?>

<div class="container" id="header-bg-content">

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