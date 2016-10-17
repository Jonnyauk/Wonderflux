<?php
/**
 * Wonderflux secondary sidebar content template part
 *
 * Customise this in your child theme by:
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 * - Creating a 'sidebar-secondary-content' location aware template part file - 'sidebar-secondary-content-page.php' or 'sidebar-secondary-content-category.php' to be used only in those locations
 *
 * @package Wonderflux
 */
?>
<div class="sidebar-box">

	<h4 class="sidebar-title"><?php esc_html_e( 'Archives', 'wonderflux' ); ?></h4>
	<ul><?php wp_get_archives('type=monthly'); ?></ul>

</div>