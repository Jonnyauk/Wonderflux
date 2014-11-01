<?php
/**
 * Wonderflux footer content template part
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'footer-content' template part 'footer-content-404.php' or 'footer-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */
?>
<div <?php wfx_css('size=full&id=footer-content'); ?>>

	<div class="box-footer">
		<h4><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></h4>
		<p><?php wfx_login_logout(''); ?></p>
	</div>

</div>