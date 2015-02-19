<?php
/**
 * Wonderflux loop content template part
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Using the 'loop-content' template part 'loop-content-404.php' or 'loop-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */
?>
<div id="post-<?php the_ID(); ?>" <?php wfx_post_class(''); ?>>

	<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Read %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h1>

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wonderflux' ), 'after' => '</div>' ) ); ?>
	</div>

	<?php
	if ( is_single() && has_tag() ) {
		echo '<div class="tag-content">';
			the_tags( '<p>Tags: ', ', ', '</p>' );
		echo '</div>';
	}
	?>

</div>

<?php comments_template( '', true ); ?>