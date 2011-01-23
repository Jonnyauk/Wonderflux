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

// Setup Core Wonderflux helper class
$wf_core = new wflux_core;
?>

<?php wfbody_before_wrapper(); //WF display hook ?>
<div class="wrapper" id="site-bg-main">
	<div class="wrapper" id="site-bg-primary">
		<div class="wrapper" id="site-bg-secondary">



			<?php wfheader_before_wrapper(); //WF display hook ?>

			<div class="wrapper" id="header-bg-main">
				<div class="wrapper" id="header-bg-primary">
					<div class="wrapper" id="header-bg-secondary">



						<?php wfheader_before_container(); //WF display hook ?>

						<div class="container" id="header-bg-content">

							<?php wfheader_before_content(); //WF display hook ?>

							<?php $wf_core->wf_get_template_part('part=header-content'); // Setup all location aware template parts ?>

							<?php wfheader_after_content(); //WF display hook ?>

						</div> <?php /*** Close header-bg-content container ***/ ?>

						<?php wfheader_after_container(); //WF display hook ?>



					</div> <?php /*** Close header-bg-secondary wrapper ***/ ?>
				</div> <?php /*** Close header-bg-primary wrapper ***/ ?>
			</div> <?php /*** Close header-bg-main wrapper ***/ ?>
			<?php wfheader_after_wrapper(); //WF display hook ?>