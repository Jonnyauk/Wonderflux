<?php
/**
 * Wonderflux sidebar content template part
 *
 * Customise this in your child theme by:
 * - Creating a file with the same name in your child theme - it will over-ride this file
 * - Creating a 'sidebar-content' location aware template part file - 'sidebar-content-page.php' or 'sidebar-content-category.php' to be used only in those locations
 *
 * @package Wonderflux
 */
?>
<div class="sidebar-box">

	<h4 class="sidebar-title"><?php esc_html_e( 'Pages', 'wonderflux' ); ?></h4>
	<ul><?php wp_list_pages('title_li=' ); ?></ul>

	<h4 class="sidebar-title"><?php esc_html_e( 'Categories', 'wonderflux' ); ?></h4>
	<ul><?php wp_list_categories('show_count=1&title_li='); ?></ul>

	<h4 class="sidebar-title"><?php esc_html_e( 'Archives', 'wonderflux' ); ?></h4>
	<ul><?php wp_get_archives('type=monthly'); ?></ul>

</div>

<?php wfx_edit_meta('wfcontrols=Y&div=Y&divclass=sidebar-box');?>