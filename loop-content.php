<?php
/**
 * The core get_template_part for the main loop content
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.4
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h2 class="entry-title"><?php the_title(); ?></h2>

	<div class="entry-content">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wonderflux' ), 'after' => '</div>' ) ); ?>
	</div>

</div>

<?php comments_template( '', true ); ?>