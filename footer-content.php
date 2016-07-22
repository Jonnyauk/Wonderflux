<?php
/**
 * Wonderflux footer content template part
 *
 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Using the 'footer-content' template part 'footer-content-404.php' or 'footer-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
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