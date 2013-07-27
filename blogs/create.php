<?php

/**
 * BuddyPress - Create Blog
 * Modified for Wonderflux theme framework
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php wfx_theme_wrapper('top'); ?>

<?php do_action( 'bp_before_directory_blogs_content' ); ?>

	<?php do_action( 'template_notices' ); ?>

		<h3><?php _e( 'Create a Site', 'buddypress' ); ?> &nbsp;<a class="button" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_blogs_root_slug() ) ?>"><?php _e( 'Site Directory', 'buddypress' ); ?></a></h3>

	<?php do_action( 'bp_before_create_blog_content' ); ?>

	<?php if ( bp_blog_signup_enabled() ) : ?>

		<?php bp_show_blog_signup_form(); ?>

	<?php else: ?>

		<div id="message" class="info">
			<p><?php _e( 'Site registration is currently disabled', 'buddypress' ); ?></p>
		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_create_blog_content' ); ?>

<?php do_action( 'bp_after_directory_blogs_content' ); ?>

<?php wfx_theme_wrapper('bottom'); ?>