<?php
/**
 * Wonderflux comments template
 *
 * Customise this in your child theme by:
 * - Using hooks and your own functions
 * - Copying this file to your child theme and customising - it will over-ride this file
 * - This is pretty similar to the default theme comments file, this is an area for future development!
 *
 * @package Wonderflux
 */
?>

<?php
if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) :
	die('Sorry, no direct access to this content.');
endif;
?>

<div id="comments">

	<?php if ( post_password_required() ) : ?>
					<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'wonderflux' ); ?></p>
				</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php
		// You can start editing here -- including this comment!
	?>

	<?php if ( have_comments() ) : ?>
				<h3 id="comments-title"><?php
				printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), 'wonderflux' ),
				number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
				?></h3>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<div class="navigation">
					<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'wonderflux' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?></div>
				</div> <!-- .navigation -->
	<?php endif; // check for comment navigation ?>

				<ol class="commentlist">
					<?php
					// TODO: Build this function!
					/* Loop through and list the comments. Tell wp_list_comments()
					 * to use wonderflux_comment() to format the comments.
					 * If you want to overload this in a child theme then you can
					 * define wonderflux_comment() and that will be used instead.
					 * See wflux_comment() in wf-includes/wf-display-functions.php for more.
					 */
					//wp_list_comments( array( 'callback' => 'wflux_comment' ) );
					wp_list_comments();
					?>
				</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
				<div class="navigation">
					<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'wonderflux' ) ); ?></div>
					<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'wonderflux' ) ); ?></div>
				</div><!-- .navigation -->
	<?php endif; // check for comment navigation

	else : // or, if we don't have comments:
		if ( ! comments_open() ) :
		// Silence is golden

	endif; // end ! comments_open()
	endif; // end have_comments()


	// Wonderflux comments extra functionality
	// Logical to keep in comments.php to keep everything together?


	/**
	 * Replaces core comments fields with valid XHTML
	 *
	 * @since 1.0RC3
	 * @return comment fields for filter
	 */
	function my_wfx_comment_fields($fields) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );

		$fields['author'] =	'<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'wonderflux' ) . '</label> ' .
			            	'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . ' />' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>';
		$fields['email'] =	'<p class="comment-form-email"><label for="email">' . __( 'Email', 'wonderflux' ) . '</label> ' .
							'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . ' />' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>';
		$fields['url'] =	'<p class="comment-form-url"><label for="url">' . __( 'Website', 'wonderflux' ) . '</label>' .
							'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';
		return $fields;

	}
	add_filter('comment_form_default_fields', 'my_wfx_comment_fields', 9);

	// Silly WordPress - the actual comment field is not in the filterable $fields array (as of WordPress 3.4x)
	$args = array(
			'comment_field' => '<p class="comment-form-comment"><label for="comment">' . __( 'Your comment', 'wonderflux' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" ></textarea></p>',
	);

	comment_form($args);

	?>

</div><!-- #comments -->