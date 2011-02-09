<?php
/**
 * @package Wonderflux Theme Framework
 * @subpackage Superloop
 *
 * This begins the loop and breaks into wf_get_template_part function which builds specific template parts depending on what is being viewed for use in child theme.
 * You don't have to build full loops in your child themes, just code what you would put INSIDE the loop and use the 'loop-content' template part functionality.
 * It looks for loop-content-LOCATIONHERE.php first - ie loop-content-page.php in your child theme directory
 * If that is not present it looks for loop-content.php in your child theme directory, which is used if you dont have a specific location loop-content-LOCATIONHERE.php
 * If you dont have loop-content.php in your child theme, the standard Wonderflux ones gets used instead, cool!
 *
 */

wfloop_before(); //WF display hook

if ($wp_query->max_num_pages > 1) : ?>
	<div class="pagination">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wonderflux' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?></div>
	</div>
<?php endif;

if (!have_posts() ) :
	get_template_part('404-content', 'general');
endif;

while (have_posts()) : the_post();

	wfx_get_template_part('part=loop-content'); // Setup all location aware template parts

endwhile;

if ($wp_query->max_num_pages > 1 ) : ?>
	<div class="pagination">
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wonderflux' ) ); ?></div>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?></div>
	</div>
<?php endif;

wfloop_after(); //WF display hook

?>