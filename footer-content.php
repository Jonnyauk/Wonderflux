<?php
/**
 * The core get_template_part footer content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.6
 */
$wfx = new wflux_display_ex;
?>
<div <?php wflux_display::wf_css('size=full&id=footer-content'); ?> >

	<div class="box-footer">
		<h3><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></h3>
		<p><?php $wfx-> wf_loginlogout(''); ?></p>
	</div>

</div>