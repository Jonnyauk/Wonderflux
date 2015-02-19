<?php
/**
 * Wonderflux loop content attachment template part
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

	<h1 class="entry-title"><?php the_title(); ?></h1>

	<div class="entry-content">

		<?php
		wfx_get_attachments('type=attachment');
		the_content();
		?>

	</div>

</div>