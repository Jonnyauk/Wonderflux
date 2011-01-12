<?php
/**
 * The core get_template_part for the main loop content content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.4
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wonderflux' ), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h2>

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wonderflux' ), 'after' => '</div>' ) ); ?>
	</div>

</div>

<?php comments_template( '', true ); ?>