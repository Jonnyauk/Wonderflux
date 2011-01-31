<?php
/**
 * The core get_template_part header content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.6
 */
?>
<div <?php wfx_css('size=full&id=header-content'); ?>>

	<div class="box-header">
		<h1><a href="<?php echo home_url(); ?>/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></h1>
		<h2><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></h2>
	</div>

</div>