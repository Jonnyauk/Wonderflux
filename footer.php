<?php
/**
 * Wonderflux footer template
 *
 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Using the 'footer-content' template part 'footer-content-404.php' or 'footer-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 * - IMPORTANT - if you do this, ensure you keep all Wonderflux hooks intact!
 *
 * @package Wonderflux
 */

wffooter_before_wrapper(); //WF display hook
wffooter_before_container(); //WF display hook

echo apply_filters( 'wflux_layout_footer_container_open', '<div class="container" id="footer">' );

	wffooter_before_content(); //WF display hook
	wfx_get_template_part('part=footer-content'); // Setup all location aware template parts
	wffooter_after_content(); //WF display hook

echo apply_filters( 'wflux_layout_footer_container_close', '</div>' );

wffooter_after_container(); //WF display hook
wffooter_after_wrapper(); //WF display hook
wfbody_after_wrapper(); //WF display hook
wf_footer();  //WF display hook
wp_footer();  //Standard WordPress display hook
?>
</body>
</html>