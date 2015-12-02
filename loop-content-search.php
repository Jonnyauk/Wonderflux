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

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">

		<?php
		// Setup post name to prepend to title
		$this_pt_detail = get_post_type_object( get_post_type(get_the_ID()) );
		$this_pt_detail = ( isset($this_pt_detail->labels->singular_name) && !empty($this_pt_detail->labels->singular_name) ) ? $this_pt_detail->labels->singular_name : '';
		$pt_title_prepend = ( !empty($this_pt_detail) ) ? ucfirst( $this_pt_detail ) . ': ' : '';
		?>

		<h2 class="archive-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Read %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php echo esc_html( $pt_title_prepend ); the_title(); ?></a></h2>

		<p><?php wfx_excerpt('limit=20'); ?></p>

		<p><a class="button-sml" href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'View %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php esc_html_e( 'View', 'wonderflux' ) ?></a></p>

	</div>

</div>