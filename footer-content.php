<?php
/**
 * The core get_template_part footer content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.6
 */
?>
<div <?php wfx_css('size=full&id=footer-content'); ?>>

	<div class="box-footer">
		<h3><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></h3>
		<p><?php /*$wfx-> wf_loginlogout('');*/ ?></p>
	</div>

</div>