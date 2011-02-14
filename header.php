<?php
/*
**IMPORTANT**
** THIS FILE IT IS NOT DESIGNED TO BE EDITED **

- This is one of the core template files of the Wonderflux theme framework
- You use Wonderflux by creating child themes and activating them
- You child theme then uses the power of Wonderflux to make your site amazing!
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