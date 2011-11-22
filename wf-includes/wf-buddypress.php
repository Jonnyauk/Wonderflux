<?php
/**
* Everything Wonderflux needs for BuddyPress (v1.5 minimum)
* Mostly based on the default BuddyPress 1.5 theme, but with a bit of Wonderflux magic
* TODO: Testing!!
* @since 0.94
* @updated 0.94
*
*/
class wflux_buddypress {

	/**
	 * General BuddyPress functionality
	 * @since 0.94
	 * @updated 0.94
	 */
	function setup_bp() {
		global $bp;

		// Load the AJAX functions for the theme
		require( WF_CONTENT_DIR . '/buddypress/ajax.php' );

		if ( !is_admin() ) {
			// Register buttons for the relevant component templates
			// Friends button
			if ( bp_is_active( 'friends' ) )
				add_action( 'bp_member_header_actions',    'bp_add_friend_button' );

			// Activity button
			if ( bp_is_active( 'activity' ) )
				add_action( 'bp_member_header_actions',    'bp_send_public_message_button' );

			// Messages button
			if ( bp_is_active( 'messages' ) )
				add_action( 'bp_member_header_actions',    'bp_send_private_message_button' );

			// Group buttons
			if ( bp_is_active( 'groups' ) ) {
				add_action( 'bp_group_header_actions',     'bp_group_join_button' );
				add_action( 'bp_group_header_actions',     'bp_group_new_topic_button' );
				add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
			}

			// Blog button
			if ( bp_is_active( 'blogs' ) )
				add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );
		}
	}


	/**
	 * Enqueue BuddyPress javascript
	 * @since 0.94
	 * @updated 0.94
	 */
	function enqueue_scripts() {
		// Bump this when changes are made to bust cache
		$version = '20110921';

		// Enqueue the global JS - Ajax will not work without it
		wp_enqueue_script( 'bp-ajax-js', WF_CONTENT_URL . '/buddypress/global.js', array( 'jquery' ), $version );

		// Add words that we need to use in JS to the end of the page so they can be translated and still used.
		$params = array(
			'my_favs'           => __( 'My Favorites', 'wonderflux' ),
			'accepted'          => __( 'Accepted', 'wonderflux' ),
			'rejected'          => __( 'Rejected', 'wonderflux' ),
			'show_all_comments' => __( 'Show all comments for this thread', 'wonderflux' ),
			'show_all'          => __( 'Show all', 'wonderflux' ),
			'comments'          => __( 'comments', 'wonderflux' ),
			'close'             => __( 'Close', 'wonderflux' ),
			'view'              => __( 'View', 'wonderflux' )
		);

		wp_localize_script( 'dtheme-ajax-js', 'BP_DTheme', $params );
	}


	/**
	 * Enqueue BuddyPress CSS
	 * TODO: Incorporate RTL and responsive CSS (thanks Karmatosed for the work and heads-up!)
	 * @since 0.94
	 * @updated 0.94
	 */
	function enqueue_styles() {
		// Bump this when changes are made to BuddyPress to bust cache
		$version = WF_VERSION;
		// Default CSS
		wp_enqueue_style( 'bp-default-main', WF_CONTENT_URL . '/buddypress/css/bp-default.css', array(), $version );
	}


	/**
	 * Template for comments and pingbacks.
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 * @param mixed $comment Comment record from database
	 * @param array $args Arguments from wp_list_comments() call
	 * @param int $depth Comment nesting level
	 * @see wp_list_comments()
	 * @since 0.94
	 * @updated 0.94
	 */
	function blog_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		if ( 'pingback' == $comment->comment_type )
			return false;

		if ( 1 == $depth )
			$avatar_size = 50;
		else
			$avatar_size = 25;
		?>

		<li <?php comment_class() ?> id="comment-<?php comment_ID() ?>">
			<div class="comment-avatar-box">
				<div class="avb">
					<a href="<?php echo get_comment_author_url() ?>" rel="nofollow">
						<?php if ( $comment->user_id ) : ?>
							<?php echo bp_core_fetch_avatar( array( 'item_id' => $comment->user_id, 'width' => $avatar_size, 'height' => $avatar_size, 'email' => $comment->comment_author_email ) ) ?>
						<?php else : ?>
							<?php echo get_avatar( $comment, $avatar_size ) ?>
						<?php endif; ?>
					</a>
				</div>
			</div>

			<div class="comment-content">
				<div class="comment-meta">
					<p>
						<?php
							/* translators: 1: comment author url, 2: comment author name, 3: comment permalink, 4: comment date/timestamp*/
							printf( __( '<a href="%1$s" rel="nofollow">%2$s</a> said on <a href="%3$s"><span class="time-since">%4$s</span></a>', 'wonderflux' ), get_comment_author_url(), get_comment_author(), get_comment_link(), get_comment_date() );
						?>
					</p>
				</div>

				<div class="comment-entry">
					<?php if ( $comment->comment_approved == '0' ) : ?>
					 	<em class="moderate"><?php _e( 'Your comment is awaiting moderation.', 'wonderflux' ); ?></em>
					<?php endif; ?>

					<?php comment_text() ?>
				</div>

				<div class="comment-options">
						<?php if ( comments_open() ) : ?>
							<?php comment_reply_link( array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ); ?>
						<?php endif; ?>

						<?php if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) : ?>
							<?php printf( '<a class="button comment-edit-link bp-secondary-action" href="%1$s" title="%2$s">%3$s</a> ', get_edit_comment_link( $comment->comment_ID ), esc_attr__( 'Edit comment', 'wonderflux' ), __( 'Edit', 'wonderflux' ) ) ?>
						<?php endif; ?>

				</div>

			</div>

	<?php
	}


	/**
	 * Add secondary avatar image to this activity stream's record, if supported.
	 *
	 * @param string $action The text of this activity
	 * @param BP_Activity_Activity $activity Activity object
	 * @return string
	 * @since 0.94
	 * @updated 0.94
	 */
	function activity_secondary_avatars( $action, $activity ) {
		switch ( $activity->component ) {
			case 'groups' :
			case 'friends' :
				// Only insert avatar if one exists
				if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
					$reverse_content = strrev( $action );
					$position        = strpos( $reverse_content, 'a<' );
					$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
				}
				break;
		}
		return $action;
	}


	/**
	 * Applies BuddyPress customisations to the post comment form.
	 *
	 * @global string $user_identity The display name of the user
	 * @param array $default_labels The default options for strings, fields etc in the form
	 * @see comment_form()
	 * @since 0.94
	 * @updated 0.94
	 */
	function comment_form( $default_labels ) {
		global $user_identity;
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields =  array(
			'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'wonderflux' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'wonderflux' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'wonderflux' ) . '</label>' .
			            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
		);

		$new_labels = array(
			'comment_field'  => '<p class="form-textarea"><textarea name="comment" id="comment" cols="60" rows="10" aria-required="true"></textarea></p>',
			'fields'         => apply_filters( 'comment_form_default_fields', $fields ),
			'logged_in_as'   => '',
			'must_log_in'    => '<p class="alert">' . sprintf( __( 'You must be <a href="%1$s">logged in</a> to post a comment.', 'wonderflux' ), wp_login_url( get_permalink() ) )	. '</p>',
			'title_reply'    => __( 'Leave a reply', 'wonderflux' )
		);

		return apply_filters( 'bp_dtheme_comment_form', array_merge( $default_labels, $new_labels ) );
	}

	/**
	 * Adds the user's avatar before the comment form box.
	 *
	 * The 'comment_form_top' action is used to insert our HTML within <div id="reply">
	 * so that the nested comments comment-reply javascript moves the entirety of the comment reply area.
	 *
	 * @see comment_form()
	 * @since 0.94
	 * @updated 0.94
	 */
	function before_comment_form() {
		?>
		<div class="comment-avatar-box">
			<div class="avb">
				<?php if ( bp_loggedin_user_id() ) : ?>
					<a href="<?php echo bp_loggedin_user_domain() ?>">
						<?php echo get_avatar( bp_loggedin_user_id(), 50 ) ?>
					</a>
				<?php else : ?>
					<?php echo get_avatar( 0, 50 ) ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="comment-content standard-form">
		<?php
	}


	/**
	 * Closes tags opened in before_comment_form().
	 *
	 * @see bp_dtheme_before_comment_form()
	 * @see comment_form()
	 * @since 0.94
	 * @updated 0.94
	 */
	function after_comment_form() {
		echo '</div><!-- .comment-content standard-form -->';
	}


	/**
	 * Adds a hidden "redirect_to" input field to the sidebar login form.
	 *
	 * @since 1.5
	 */
	function bp_dtheme_sidebar_login_redirect_to() {
		$redirect_to = apply_filters( 'bp_no_access_redirect', !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '' );
		?>
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
		<?php
	}


}


//TODO: Need to test all BuddyPress hooks and check position/priority is correct
class wflux_buddypress_hooks {

	//SPECIAL CASES
	//index.php template_notices - wfmain_before_index_content
	//header.php - bp_search_login_bar

	//searchform.php
	//bp_before_blog_search_form
	//bp_blog_search_form
	//bp_after_blog_search_form

	// Sidebar
	//DONE//bp_before_sidebar - wfsidebar_before_all
	//DONE//bp_inside_before_sidebar - wfsidebar_before_all
	// - Inside BP functionality
	//bp_before_sidebar_me
	//bp_sidebar_me
	//bp_after_sidebar_me
	// - Inside further BP functionality
	//bp_before_sidebar_login_form
	//bp_sidebar_login_form
	//bp_after_sidebar_login_form
	//DONE//bp_inside_after_sidebar - wfsidebar_after_all
	//DONE//bp_after_sidebar - wfsidebar_after_all

	// Header
	function bp_head() { do_action('bp_head', 12); }
	function bp_before_header() { do_action('bp_before_header', 2); }
	function bp_header() { do_action('bp_header', 9); }
	function bp_after_header() { do_action('bp_after_header', 9); }
	function bp_before_container() { do_action('bp_before_container', 9); }
	// Sidebar
	function bp_before_sidebar() { do_action('bp_before_sidebar', 2); }
	function bp_inside_before_sidebar() { do_action('bp_inside_before_sidebar', 12); }
	function bp_inside_after_sidebar() { do_action('bp_inside_after_sidebar', 9); }
	function bp_after_sidebar() { do_action('bp_after_sidebar', 9); }
	// Footer
	function bp_after_container() { do_action('bp_after_container', 9); }
	function bp_before_footer() { do_action('bp_before_footer', 2); }
	function bp_footer() { do_action('bp_footer', 2); }
	function bp_after_footer() { do_action('bp_after_footer', 9); }
	// General
	function bp_before_blog_post() { do_action('bp_before_blog_post', 2); }
	function bp_after_blog_post() { do_action('bp_after_blog_post', 9); }
	// Locations
	function bp_before_blog_page() { do_action('bp_before_blog_page', 2); }
	function bp_after_blog_page() { do_action('bp_after_blog_page', 9); }
	function bp_before_blog_home() { do_action('bp_before_blog_home', 2); }
	function bp_after_blog_home() { do_action('bp_after_blog_home', 9); }
	function bp_before_404() { do_action('bp_before_404', 2); }
	function bp_404() { do_action('bp_404' * 8); }
	function bp_after_404() { do_action('bp_after_404', 9); }
	function bp_before_archive() { do_action('bp_before_archive', 2); }
	function bp_after_archive() { do_action('bp_after_archive', 9); }
	function bp_before_attachment() { do_action('bp_before_attachment', 2); }
	function bp_after_attachment() { do_action('bp_after_attachment', 9); }
	function bp_before_blog_search() { do_action('bp_before_blog_search', 2); }
	function bp_after_blog_search() { do_action('bp_after_blog_search', 9); }
}


/**
 * Public function to insert Wonderflux content around a file, loop, whatever!
 * @since 0.94
 * @updated 0.94
 */
if ( !function_exists( 'wfx_theme_wrapper' ) ) : function wfx_theme_wrapper($args) {
	switch ($args) {

		case 'top':
			get_header();
			wfmain_before_wrapper(); //WF display hook
			wfmain_before_all_container(); //WF display hook
			echo '<div class="container" id="main-content">';

			wfmain_before_all_content(); //WF display hook

			do_action( 'wfmain_before_buddypress' ); //ALPHA WF display hook
		break;

		case 'bottom':
			do_action( 'wfmain_after_buddypress' ); //ALPHA WF display hook

			wfmain_after_all_content(); //WF display hook
			wfx_get_sidebar(''); //WF WordPress get_sidebar function replacement
			wfmain_after_all_main_content(); //WF display hook
			echo '</div>';

			wfmain_after_all_container(); //WF display hook
			wfmain_after_wrapper(); //WF display hook
			get_footer();
		break;

		default:
			// Silence is golden
		break;

	}
}
endif;


/**
 * Add link to BuddyPress profile in WP admin
 * @since 0.94
 * @updated 0.94
 */
function wfx_bp_admin_users_link($actions, $user_object) {
	global $bp;
	$actions['view'] = '<a href="' . bp_core_get_user_domain($user_object->ID) . '">' . __('Social Profile') . '</a>';
	return $actions;
}
add_filter('user_row_actions', 'wfx_bp_admin_users_link', 10, 2);


/**
 * Insert a div around BP content to allow for common CSS styling and targeting
 * @since 0.94
 * @updated 0.94
 */
if ( !function_exists( 'wfx_bp_box_open' ) ) : function wfx_bp_box_open($args) {
	echo '<div class="' . apply_filters( 'wflux_bp_container_div', 'box-bp-social-content' ) .'">';
}
endif;


/**
 * Insert a div around BP content
 * @since 0.94
 * @updated 0.94
 */
if ( !function_exists( 'wfx_bp_box_close' ) ) : function wfx_bp_box_close($args) {
	echo '</div>';
}
endif;

// Do layout box
add_action( 'wfmain_before_buddypress', 'wfx_bp_box_open' );
add_action( 'wfmain_after_buddypress', 'wfx_bp_box_close' );

// Do BuddyPress
$wfx_bp = new wflux_buddypress;
$wfx_bp_hooks = new wflux_buddypress_hooks;

add_action( 'after_setup_theme', array($wfx_bp, 'setup_bp' ) );
add_action( 'wp_enqueue_scripts', array($wfx_bp, 'enqueue_scripts' ) );
//add_action( 'wp_print_styles', array($wfx_bp, 'enqueue_styles' ), 10 );
add_action( 'wf_head_meta', array($wfx_bp, 'enqueue_styles' ), 3 );
//add_filter( 'bp_get_activity_action_pre_meta', array($wfx_bp, 'activity_secondary_avatars', 10, 2 ) );
//add_filter( 'comment_form_defaults', array($wfx_bp, 'comment_form', 10 ) );
//add_action( 'comment_form_top', array($wfx_bp, 'before_comment_form' ) );
//add_action( 'comment_form', array($wfx_bp, 'after_comment_form' ) );
//add_action( 'bp_sidebar_login_form', array($wfx_bp, 'sidebar_login_redirect_to' ) );

// Header
add_action( 'wf_head_meta', array($wfx_bp_hooks, 'bp_head'), 12);
add_action( 'wfbody_before_wrapper', array($wfx_bp_hooks, 'bp_before_header'), 2);
add_action( 'wfheader_after_content', array($wfx_bp_hooks, 'bp_header'), 9);
add_action( 'wfheader_after_container', array($wfx_bp_hooks, 'bp_after_header'), 9);
add_action( 'wfheader_after_wrapper', array($wfx_bp_hooks, 'bp_before_container'), 9);

// Sidebar
add_action( 'wfsidebar_before_all', array($wfx_bp_hooks, 'bp_before_sidebar'), 2);
add_action( 'wfsidebar_before_all', array($wfx_bp_hooks, 'bp_inside_before_sidebar'), 12);
add_action( 'wfsidebar_after_all', array($wfx_bp_hooks, 'bp_inside_after_sidebar'), 9);
add_action( 'wfsidebar_after_all', array($wfx_bp_hooks, 'bp_after_sidebar'), 9);

// Footer
add_action( 'wfmain_after_wrapper', array($wfx_bp_hooks, 'bp_after_container'), 9);
add_action( 'wffooter_before_wrapper', array($wfx_bp_hooks, 'bp_before_footer'), 2);
add_action( 'wffooter_after_content', array($wfx_bp_hooks, 'bp_footer'), 2);
add_action( 'wf_footer', array($wfx_bp_hooks, 'bp_after_footer'), 9);

// General
add_action( 'wfmain_before_all_content', array($wfx_bp_hooks, 'bp_before_blog_post'), 2);
add_action( 'wfmain_after_all_content', array($wfx_bp_hooks, 'bp_after_blog_post'), 9);

// Locations
add_action( 'wfmain_before_page_content', array($wfx_bp_hooks, 'bp_before_blog_page'), 2);
add_action( 'wfmain_after_page_content', array($wfx_bp_hooks, 'bp_after_blog_page'), 9);
add_action( 'wfmain_before_home_content', array($wfx_bp_hooks, 'bp_before_blog_home'), 2);
add_action( 'wfmain_after_home_content', array($wfx_bp_hooks, 'bp_after_blog_home'), 9);
add_action( 'wfmain_before_404_content', array($wfx_bp_hooks, 'bp_before_404'), 2);
add_action( 'wfmain_after_404_content', array($wfx_bp_hooks, 'bp_404'), 8);
add_action( 'wfmain_after_404_content', array($wfx_bp_hooks, 'bp_after_404'), 9);
add_action( 'wfmain_before_archive_content', array($wfx_bp_hooks, 'bp_before_archive'), 2);
add_action( 'wfmain_after_archive_content', array($wfx_bp_hooks, 'bp_after_archive'), 9);
add_action( 'wfmain_before_attachment_content', array($wfx_bp_hooks, 'bp_before_attachment'), 2);
add_action( 'wfmain_after_attachment_content', array($wfx_bp_hooks, 'bp_after_attachment'), 9);
add_action( 'wfmain_before_search_content', array($wfx_bp_hooks, 'bp_before_blog_search'), 2);
add_action( 'wfmain_after_search_content', array($wfx_bp_hooks, 'bp_after_blog_search'), 9);
?>