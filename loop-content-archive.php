<?php
/**
 * Wonderflux loop content archive template part
 * Used when you dont have a category/taxonomy/tag/date/post type template part
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Read %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>

	<div class="entry-content">

		<p><?php wfx_excerpt( 'limit=40&excerpt_end=...' ); ?></p>

		<p><a class="button-sml" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'View %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php esc_html_e( 'View', 'wonderflux' ) ?></a></p>

	</div>

</div>