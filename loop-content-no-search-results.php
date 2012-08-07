<?php
/*
 * Core Wonderflux no search results returned content template part
 *
 * Customise this in your child theme by:
 * - Copying this file to your child theme and customising - it will over-ride this file
 *
 * @package Wonderflux
 */

$search_val = trim(get_search_query());
?>
<div id="post-0" class="post-0 type-error format-standard hentry single-post paged-return-0 first-in-loop last-in-loop">

	<h2 class="entry-title">
	<?php
	if (!empty($search_val))
		printf( __( 'No search results found for: %s', 'wondeflux' ), '<span>' . $search_val . '</span>' );
	else
		_e( 'No search value entered', 'wonderflux' );
	?>
	</h2>

	<div class="entry-content">
		<p>
		<?php
		if (!empty($search_val))
			_e( 'Sorry, nothing matched your search criteria. Please try again with some different keywords.', 'wonderflux' );
		else
			_e( 'Sorry, you did not enter a search keyword. Please try again with some different keywords.', 'wonderflux' );
		?>
		</p>
		<?php get_search_form(); ?>
	</div>

</div>