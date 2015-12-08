<?php
/**
 * BuddyPress - Members Feedback No Notifications
 *
 * @package Wonderflux
 * @subpackage BuddyPress template files
 */

?>
<div id="message" class="info">

	<?php if ( bp_is_current_action( 'unread' ) ) : ?>

		<?php if ( bp_is_my_profile() ) : ?>

			<p><?php _e( 'You have no unread notifications.', 'wonderflux' ); ?></p>

		<?php else : ?>

			<p><?php _e( 'This member has no unread notifications.', 'wonderflux' ); ?></p>

		<?php endif; ?>

	<?php else : ?>

		<?php if ( bp_is_my_profile() ) : ?>

			<p><?php _e( 'You have no notifications.', 'wonderflux' ); ?></p>

		<?php else : ?>

			<p><?php _e( 'This member has no notifications.', 'wonderflux' ); ?></p>

		<?php endif; ?>

	<?php endif; ?>

</div>
