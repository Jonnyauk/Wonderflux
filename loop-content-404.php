<?php
/**
 * Wonderflux 404 content template part
 *
 * Customise this in your child theme by:
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */
?>
<div id="post-0" class="post-0 type-error format-standard hentry single-post paged-return-0 first-in-loop last-in-loop">

	<h1 class="entry-title"><?php _e( 'Sorry', 'wonderflux' ); ?></h1>

	<div class="entry-content">

		<p><?php esc_html_e( 'It seems the content you are looking for has either changed or moved.', 'wonderflux' ); ?></p>
		<p><?php esc_html_e( 'Try using our site search to locate related content.', 'wonderflux' ); ?></p>
		<?php get_search_form(); ?>

	</div>

</div>