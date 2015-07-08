<?php
/**
 * Wonderflux search results loop content template part
 *
 * Customise this in your child theme by:
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */
?>

<div id="post-<?php the_ID(); ?>" <?php wfx_post_class(''); ?>>

	<div class="entry-content">

		<?php
		$this_pt = get_post_type();
		$this_pt = (!empty($this_pt)) ? ucfirst($this_pt) : false;
		?>

		<h2 class="archive-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Read %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php echo esc_html( $this_pt ) . ': '; the_title(); ?></a></h2>

		<p><?php wfx_excerpt('limit=20'); ?></p>

		<p><a class="button-sml" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'View %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php esc_html_e( 'View', 'wonderflux' ) ?></a></p>

	</div>

</div>