<?php
/**
 * Wonderflux header content template part
 *
 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Using the 'header-content' template part 'header-content-404.php' or 'header-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 *
 * @package Wonderflux
 */
?>
<div <?php wfx_css('size=full&id=header-content'); ?>>

	<div class="box-header">
		<h1><a href="<?php echo home_url(); ?>/" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a></h1>
		<h2><?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?></h2>
	</div>

</div>