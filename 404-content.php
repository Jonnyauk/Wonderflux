<?php
/**
 * The core get_template_part 404 content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.5
 */
?>
<div id="post-0" class="post error404 not-found">

	<h2><?php _e( 'Sorry', 'wonderflux' ); ?></h2>

	<div class="entry-content">

		<p><?php _e( 'It seems the content you are looking for has either changed or moved.', 'wonderflux' ); ?></p>
		<p><?php _e( 'Try using our site search to locate related content.', 'wonderflux' ); ?></p>
		<?php get_search_form(); ?>

	</div>

</div>