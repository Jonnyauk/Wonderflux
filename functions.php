<?php
/**
 *
 * Wonderflux is a free open source, theme framework for professional WordPress theme design.
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * I'M NOT MEANT TO BE ACTIVATED DIRECTLY THANKS - IM A THEME FRAMEWORK!
 * Put me in your themes folder along with a Wonderflux child theme, then activate child theme, not me!
 * Free example child theme:		https://github.com/Jonnyauk/wonderflux-girder
 *
 * GETTING STARTED GUIDES
 * Information and license:			README.txt
 * Guide and documentation:			http://wonderflux.com/guide
 * Introduction to Wonderflux:		http://wonderflux.com/guide/doc/introduction
 * Wonderflux child themes:			http://wonderflux.com/guide/doc/child-theme-files
 *
 * GETTING INVOLVED
 * Bugs/improvements/feedback:		https://github.com/Jonnyauk/Wonderflux/issues
 * Official release downloads:		https://github.com/Jonnyauk/Wonderflux/releases
 * Development code:				https://github.com/Jonnyauk/Wonderflux
 *
 * INDEX OF THIS FILE
 * 1  -  Core framework setup & deployment
 * 2  -  Helper functions
 * 3  -  Display functions
 * 4  -  Social functions
 * 5  -  Theme configuration functions
 * 6  -  Javascript support functions
 * 7  -  Admin functions
 * 8  -  Admin post/content functions
 * 9  -  Direct activation fallbacks
 *
 * DON'T HACK ME! You should NOT modify the Wonderflux theme framework to avoid issues with updates in the future.
 * View the readme for more information.
 *
 * If you still feel the need to hack the Wonderflux core code, why not submit a patch or suggestion?
 * https://github.com/Jonnyauk/Wonderflux
 *
 * DEVELOPERS AND TESTERS
 * All development is tracked within the GitHub project.
 * Anyone has full checkout access to the latest non-released development code (master branch)
 * Note - the master branch on GitHub is for testing and development - NOT for live sites (unless you are brave!)
 *
 * THANKS FOR USING WONDERFLUX - YOU NOW ROCK!
 * Created something cool? Let us know - we can't wait to see what you create!
 * Follow @Wonderflux on Twitter for all code updates and news.
 *
 * @package Wonderflux
 *
 */

/*
	  #
	 ##
	# #
	  #
	  #
	  #
	#####

	Core framework setup & deployment
*/

// Wonderflux, start your engine
load_template(get_template_directory() . '/wf-includes/wf-engine.php');

//// 1.1 // Early setup (before init)

add_action('after_setup_theme', 'wfx_core_feed_links', 2);
add_action('after_setup_theme', 'wfx_core_title_tag', 2);

//// 1.2 // Special child theme functions

// Use this to configure all your Wonderflux child theme layout functions like wfx_background_divs()
// Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
if ( function_exists( 'my_wfx_layout' ) ) { add_action('get_header', 'my_wfx_layout', 1); }
// Use this to configure all your Wonderflux child theme script functions like wfx_jquery()
// Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
if ( function_exists( 'my_wfx_scripts' ) ) { if ( !is_admin() ) : add_action('init', 'my_wfx_scripts', 1); endif; }

//// 1.3 // Columns functionality

// Allow full removal of the framework CSS system or minified version
if ( WF_THEME_FRAMEWORK_NONE == true ) {
	// Silence is golden
} elseif ( WF_THEME_FRAMEWORK_REPLACE == true ) {
	add_action('wf_head_meta', 'wfx_head_css_replace', 2);
} else {
	add_action('wf_head_meta', 'wfx_display_head_css_structure', 2);
	add_action('wf_head_meta', 'wfx_display_head_css_columns', 2);
	add_action('wf_head_meta', 'wfx_display_head_css_ie', 2);
}

//// 1.4 // If Wonderflux activated directly with no child theme

if ( wp_get_theme()->Name == 'Wonderflux Framework' ) {
	add_action( 'after_setup_theme', 'wfx_core_default_setup', 2);
	add_action('wp_loaded', 'wfx_core_default_widgets', 1);
	add_action('get_header', 'wfx_core_default_wrappers', 1);
}

//// 1.5 // Wonderflux core functionality

add_action('init', 'wfx_config_language');
add_action('wp_enqueue_scripts', 'wfx_core_comment_js', 2);
add_action('get_header', 'wfx_display_head_open', 1);
add_action('get_header', 'wfx_display_body_tag', 1);
add_action('get_header', 'wfx_layout_build', 1);
add_action('get_header', 'wfx_content_width_embed', 2);
add_action('get_header', 'wfx_social_meta');
add_action('wf_head_meta', 'wfx_display_head_char_set', 1);
add_action('wf_head_meta', 'wfx_display_head_viewport', 2);
add_action('wf_head_meta', 'wfx_display_head_title', 3);
add_action('wf_head_meta', 'wfx_display_head_css_theme', 3);
add_action('wf_head_meta', 'wfx_display_css_info');
add_action('admin_bar_menu', 'wfx_admin_bar_links', 100);
add_action('wffooter_after_content', 'wfx_display_credit', 1);
add_action('wf_footer', 'wfx_display_code_credit', 3);
add_action('auth_redirect', 'wfx_admin_menus');
add_filter('theme_page_templates','wfx_remove_page_templates');

//// 1.6 // Wonderflux debug functionality

if ( WF_DEBUG == true ){
	add_action('init','wfx_show_hooks');
	add_action('admin_bar_menu', 'wfx_admin_bar_files_info', 100);
}

/*
	 #####
	#     #
	      #
	 #####
	#
	#
	#######

	Helper functions
*/

/**
 * Returns array of information about a file based on filename
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_info_file' ) ) : function wfx_info_file($filename='') { global $wfx_helper; return $wfx_helper->info_file($filename); } endif;

/**
 * IMPORTANT - Gets type of view
 *
 * @since 0.913
 * @updated 1.0
 */
if ( !function_exists( 'wfx_info_location' ) ) : function wfx_info_location() { global $wfx_helper; return $wfx_helper->info_location(); } endif;

/**
 * Detects if you are viewing single content - post, page, attachment, author - returns true or false
 *
 * @since 1.0
 * @updated 1.1
 */
if ( !function_exists( 'wfx_info_single' ) ) : function wfx_info_single() { global $wfx_helper; return $wfx_helper->info_single(); } endif;

/**
 * IMPORTANT - Wonderflux get_template_part - like get_template_part but appends location and '-small' for mobile
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_get_template_part' ) ) : function wfx_get_template_part($args) { global $wfx_helper; $wfx_helper->gt_part($args); } endif;

/**
 * Gets user role (return or echo)
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.913
 * @updated 0.92
 */
if ( !function_exists( 'wfx_user_role' ) ) : function wfx_user_role($args) {

	$defaults = array (
		'echo' => 'N'
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $wfx_helper;

	if ($echo == 'N') {
		return $wfx_helper->user_role($args);
	} else {
		echo $wfx_helper->user_role($args);
	}

} endif;

/**
 * Returns current page depth
 *
 * @since 0.913
 * @updated 0.92
 */
if ( !function_exists( 'wfx_page_depth' ) ) : function wfx_page_depth($args) { global $wfx_helper; return $wfx_helper->page_depth($args); } endif;

/**
 * Echo (default) or return custom field value
 *
 * @since 0.92
 * @updated 1.1
 */
if ( !function_exists( 'wfx_custom_field' ) ) : function wfx_custom_field($args) {

	$defaults = array (
		'echo' => 'Y'
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $wfx_helper;

	if ($echo == 'N') {
		return $wfx_helper->custom_field($args);
	} else {
		echo $wfx_helper->custom_field($args);
	}

} endif;

/**
 * Returns 'Y' - nothing more, nothing less
 * Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__Y' ) in your child theme
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx__Y' ) ) : function wfx__Y() { global $wfx_helper; return $wfx_helper->__Y(); } endif;

/**
 * Returns 'N' - nothing more, nothing less
 * Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__N' ) in your child theme
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx__N' ) ) : function wfx__N() { global $wfx_helper; return $wfx_helper->__N(); } endif;

/**
 * Displays input in a nicer way for debugging
 *
 * @since 1.1
 * @updated 2.0
 */
if ( !function_exists( 'wfx_debug' ) ) : function wfx_debug($input='',$label='',$admin_only=true,$role=false,$id=false) { global $wfx_helper; $wfx_helper->debug($input,$label,$admin_only,$role,$id); } endif;

/**
 * Adds message to error reporting if WP_DEBUG is true.
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_debug_report' ) ) : function wfx_debug_report($function='', $message='', $version='') { global $wfx_helper; $wfx_helper->debug_report($function, $message, $version); } endif;

/**
 * Reveals the location of all relevant Wonderflux display hooks within your theme.
 *
 * @since 1.2
 * @updated 1.2
 */
if ( !function_exists( 'wfx_show_hooks' ) ) : function wfx_show_hooks() { global $wfx_helper; $wfx_helper->show_hooks(); } endif;

/**
 * Returns array of common layout tags to be used with kses or similar
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_allowed_tags' ) ) : function wfx_allowed_tags($args) {
	 global $wfx_data_manage; return $wfx_data_manage->allowed_tags($args);
} endif;

/**
 * Returns $input with whitespace stripped out
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_strip_whitespace' ) ) : function wfx_strip_whitespace($input,$echo='N') {
	global $wfx_data_manage; return $wfx_data_manage->strip_whitespace($input);
} endif;

/*
	 #####
	#     #
	      #
	 #####
	      #
	#     #
	 #####

	Display functions
*/

/**
 * Adds 'automatic-feed-links' WordPress theme support
 *
 * @since 1.1
 * @updated 2.0
 */
if ( !function_exists( 'wfx_core_feed_links' ) ) : function wfx_core_feed_links() { global $wfx_theme_support; $wfx_theme_support->core_feed_links(); } endif;

/**
 * Adds 'title-tag' WordPress theme support - Requires WordPress 4.1+
 * Backpat - Original fallback function still works if title-tag not supported
 *
 * @since 2.0
 * @updated 2.0
 */
if ( !function_exists( 'wfx_core_title_tag' ) ) : function wfx_core_title_tag() { global $wfx_theme_support; $wfx_theme_support->core_title_tag(); } endif;

/**
 * Core WordPress threaded comment reply Javascript
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_core_comment_js' ) ) : function wfx_core_comment_js() { global $wfx_theme; $wfx_theme->core_comment_js(); } endif;

// Only need functions if have child theme overrides
if (WF_THEME_FRAMEWORK_REPLACE == false) {

	/**
	 * Inserts the core structure CSS
	 *
	 * @since 0.72
	 * @updated 0.913
	 */
	if ( !function_exists( 'wfx_display_head_css_structure' ) ) : function wfx_display_head_css_structure($args) { global $wfx; $wfx->head_css_structure($args); } endif;

	/**
	 * Inserts the dynamic layout system CSS
	 *
	 * @since 0.72
	 * @updated 0.913
	 */
	if ( !function_exists( 'wfx_display_head_css_columns' ) ) : function wfx_display_head_css_columns($args) { global $wfx; $wfx->head_css_columns($args); } endif;

	/**
	 * Inserts the core IE CSS
 	 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 	 *
	 * @since 0.72
	 * @updated 0.913
	 */
	if ( !function_exists( 'wfx_display_head_css_ie' ) ) : function wfx_display_head_css_ie($args) { global $wfx; $wfx->head_css_ie($args); } endif;

} elseif (WF_THEME_FRAMEWORK_REPLACE == true) {

	/**
	 * Replace core CSS with static versions
	 *
	 * @since 0.93
	 * @updated 0.93
	 */
	if ( !function_exists( 'wfx_head_css_replace' ) ) : function wfx_head_css_replace($args) { global $wfx; $wfx->head_css_replace($args); } endif;

}

/**
 * IMPORTANT Sets up Wonderflux for translation
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_config_language' ) ) : function wfx_config_language($args) { global $wfx_theme; $wfx_theme->language_pack($args); } endif;

/**
 * IMPORTANT Builds the head of the document
 *
 * @since 0.931
 * @updated 0.931
 */
if ( !function_exists( 'wfx_display_head_open' ) ) : function wfx_display_head_open() { global $wfx; $wfx->head_open(); } endif;

/**
 * IMPORTANT Builds the character set of the document
 *
 * @since 0.931
 * @updated 0.931
 */
if ( !function_exists( 'wfx_display_head_char_set' ) ) : function wfx_display_head_char_set($args) { global $wfx; $wfx->head_char_set($args); } endif;

/**
 * Adds meta viewport meta tag (if not using legacy Wonderflux v1 grid system)
 *
 * @since 2.0
 * @updated 2.0
 */
if ( !function_exists( 'wfx_display_head_viewport' ) ) : function wfx_display_head_viewport() { global $wfx; $wfx->head_viewport(); } endif;

/**
 * IMPORTANT Builds the title of the document
 *
 * @since 0.71
 * @updated 1.0
 */
if ( !function_exists( 'wfx_display_head_title' ) ) : function wfx_display_head_title($args) { global $wfx; $wfx->head_title($args); } endif;

/**
 * Inserts the core theme CSS
 *
 * @since 0.72
 * @updated 1.1
 */
if ( !function_exists( 'wfx_display_head_css_theme' ) ) : function wfx_display_head_css_theme($args) { global $wfx; $wfx->head_css_theme($args); } endif;

/**
 * Inserts opening dynamic body tag in output - adds Extra Wonderflux CSS classes and basic browser detection
 *
 * @since 0.931
 * @updated 1.1
 */
if ( !function_exists( 'wfx_display_body_tag' ) ) : function wfx_display_body_tag() { global $wfx; $wfx->body_tag(); } endif;

/**
 * A more flexible post class function - especially compared to get_post_class()
 *
 * @since 1.0RC3
 * @updated 1.0RC3
 */
if ( !function_exists( 'wfx_post_class' ) ) : function wfx_post_class($args) {

	$defaults = array ( 'echo' => 'Y' );
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $wfx;
	if ( $echo == 'Y' ) { echo $wfx->post_class($args); }
	else { return $wfx->post_class($args); }

} endif;

/**
 * Displays debug in code comment
 *
 * @since 0.72
 * @updated 0.913
 */
if ( !function_exists( 'wfx_debug_performance' ) ) : function wfx_debug_performance($args) { global $wfx; $wfx->debug_performance($args); } endif;

/**
 * Displays Wonderflux credit in source code of site
 *
 * @since 0.71
 * @updated 0.913
 */
if ( !function_exists( 'wfx_display_code_credit' ) ) : function wfx_display_code_credit($args) { global $wfx; $wfx->code_credit($args); } endif;

/**
 * Displays a credit, show your support for Wonderflux!
 *
 * @since 0.71
 * @updated 0.913
 */
if ( !function_exists( 'wfx_display_credit' ) ) : function wfx_display_credit($args) { global $wfx; $wfx->display_credit($args); } endif;

/**
 * Displays CSS info for designers as a comment in the head source code
 *
 * @since 0.51
 * @updated 0.913
 */
if ( !function_exists( 'wfx_display_css_info' ) ) : function wfx_display_css_info($args) { global $wfx; $wfx->css_info($args); } endif;

/**
 * Generates a repeating pattern of columns for testing the grid layout system
 *
 * @since 1.1
 * @updated 1.2
 */
if ( !function_exists( 'wfx_display_test_pattern' ) ) : function wfx_display_test_pattern($args) { global $wfx; $wfx->test_pattern($args); } endif;

/**
 * Returns saved site dimensions
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx_get_dimensions' ) ) : function wfx_get_dimensions($args) { global $wfx; return $wfx->get_dimensions($args); } endif;

/**
 * IMPORTANT Sets WordPress $content_width global for oEmbed media
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_content_width_embed' ) ) : function wfx_content_width_embed() { global $wfx; $wfx->content_width_embed(); } endif;

/**
 * Gets the sidebar
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx_get_sidebar' ) ) : function wfx_get_sidebar($args) { global $wfx; $wfx->get_sidebar($args); } endif;

/**
 * Creates dynamic CSS grid class definition
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.913
 * @updated 1.1
 */
if ( !function_exists( 'wfx_css' ) ) : function wfx_css($args) {
	wp_parse_str($args, $echo_do);
	$echo = (isset($echo_do['echo']) && $echo_do['echo'] == 'N') ? 'N' : 'Y';
	global $wfx;
	if ($echo == 'Y') {
		 echo $wfx->css($args);
	} else {
		 return $wfx->css($args);
	}
} endif;

/**
 * Just echos </div> - nothing more nothing less, kinda lazy huh?
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_css_close' ) ) : function wfx_css_close($args) { global $wfx; $wfx->css_close($args); } endif;

/**
 * IMPORTANT - Creates layout wrappers around content and sidebar
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx_layout_build' ) ) : function wfx_layout_build($args) { global $wfx; $wfx->layout_build($args); } endif;

/**
 * Creates a clean excerpt
 *
 * @since 0.913
 * @updated 1.1
 */
if ( !function_exists( 'wfx_excerpt' ) ) : function wfx_excerpt($args) {

	$defaults = array (
		'echo' => 'Y'
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $wfx;

	if ($echo == 'Y') {
		echo $wfx->excerpt($args);
	} else {
		return $wfx->excerpt($args);
	}

} endif;

/**
 * Gets a single post/page/whatever
 *
 * @since 0.913
 * @updated 1.2
 */
if ( !function_exists( 'wfx_get_single_content' ) ) : function wfx_get_single_content($args) { global $wfx; $wfx->get_single_content($args); } endif;

/**
 * Creates an image link to the post displayed
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.913
 * @updated 1.1
 */
if ( !function_exists( 'wfx_perma_img' ) ) : function wfx_perma_img($args) { global $wfx; $wfx->perma_img($args); } endif;

/**
 * Creates a configurable list of admin links etc
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_edit_meta' ) ) : function wfx_edit_meta($args) { global $wfx; $wfx->edit_meta($args); } endif;

/**
 * A simple login/logout link
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_login_logout' ) ) : function wfx_login_logout($args) { global $wfx; $wfx->login_logout($args); } endif;

/**
 * Used when you really, really have to create a static coded navigation - CSS class added if active page
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_static_highlight' ) ) : function wfx_static_highlight($args) { global $wfx; $wfx->static_highlight($args); } endif;

/**
 * Gets attachment(s) or featured images of main post query
 * Used in loop-content-attachment.php - will try and playback files or create nice links
 * Can be used inside or outside the loop
 *
 * @since 0.913
 * @updated 1.1
 */
if ( !function_exists( 'wfx_get_attachments' ) ) : function wfx_get_attachments( $args='' ) {

	$defaults = array ( 'echo' => 'Y' );

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $wfx;
	if ($echo == 'Y') {
		echo $wfx->get_attachments($args);
	} else {
		return $wfx->get_attachments($args);
	}

} endif;

/**
 * Creates 'page x of x' output for lists of posts like archive or query views
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx_page_counter' ) ) : function wfx_page_counter($args) {

	$defaults = array ( 'echo' => 'Y' );

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $wfx;

	if ($echo == 'Y') {
		echo $wfx->page_counter($args);
	} else {
		return $wfx->page_counter($args);
	}

} endif;

/**
 * Include a file and cache generated output for desired time
 *
 * @since 1.0RC3
 * @updated 1.1
 */
if ( !function_exists( 'wfx_get_cached_part' ) ) : function wfx_get_cached_part($args) {
	wp_parse_str($args, $echo_do);
	$echo = (isset($echo_do['echo']) && $echo_do['echo'] == 'Y') ? 'Y' : 'N';
	global $wfx;
	if ($echo == 'Y') {
		 echo $wfx->get_cached_part($args);
	} else {
		 return $wfx->get_cached_part($args);
	}
} endif;

/**
 * Builds a clickable link from supplied data
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_build_hyperlink' ) ) : function wfx_build_hyperlink($args) {

	$echo = (isset($args['echo']) && $args['echo'] == 'Y') ? 'Y' : 'N';

	global $wfx;
	if ($echo == 'Y') {
		echo $wfx->build_hyperlink($args);
	} else {
		return $wfx->build_hyperlink($args);
	}

} endif;

/**
 * Builds a text string from a basic array
 * Separates each value with an optional delimiter (but not the last one!)
 *
 * @since 1.1
 * @updated 1.1
 */
if ( !function_exists( 'wfx_array_to_delimited_string' ) ) : function wfx_array_to_delimited_string($args) {

	$echo = (isset($args['echo']) && $args['echo'] == 'Y') ? 'Y' : 'N';

	global $wfx;
	if ($echo == 'Y') {
		echo $wfx->array_to_delimited_string($args);
	} else {
		return $wfx->array_to_delimited_string($args);
	}

} endif;

/*
	#
	#    #
	#    #
	#    #
	#######
	     #
	     #

	Social functions
*/

/**
 * Displays Google Plus 1 button
 *
 * @since 0.931
 * @updated 0.931
 */
if ( !function_exists( 'wfx_social_google_plus_1' ) ) : function wfx_social_google_plus_1($args='') { global $wfx; $wfx->g_plus_1($args); } endif;

/**
 * Displays Facebook Like button
 *
 * @since 0.931
 * @updated 0.931
 */
if ( !function_exists( 'wfx_social_facebook_like' ) ) : function wfx_social_facebook_like($args='') { global $wfx; $wfx->fb_like($args); } endif;

/**
 * Displays Twitter share button
 *
 * @since 0.931
 * @updated 0.931
 */
if ( !function_exists( 'wfx_social_twitter_share' ) ) : function wfx_social_twitter_share($args='') { global $wfx; $wfx->twit_share($args); } endif;

/**
 * Displays LinkedIn share button
 *
 * @since 1.0rc2
 * @updated 1.0rc2
 */
if ( !function_exists( 'wfx_social_linkedin_share' ) ) : function wfx_social_linkedin_share($args='') { global $wfx; $wfx->linkedin_share($args); } endif;

/**
 * Builds social associated meta tags (Facebook ect)
 *
 * @since 0.931
 * @updated 0.931
 */
if ( !function_exists( 'wfx_social_meta' ) ) : function wfx_social_meta($args='') { global $wfx; $wfx->social_meta($args); } endif;

/*
	#######
	#
	#
	######
	      #
	#     #
	 #####

	Theme configuration functions
*/


/**
 * IMPORTANT Sets up widgets
 *
 * @since 0.913
 * @updated 0.931
 */
if ( !function_exists( 'wfx_widgets' ) ) : function wfx_widgets($args) { global $wfx_theme; $wfx_theme->widgets($args); } endif;

/**
 * IMPORTANT Sets up background divs
 *
 * @since 0.92
 * @updated 0.92
 */
if ( !function_exists( 'wfx_background_divs' ) ) : function wfx_background_divs($args) { global $wfx_theme; $wfx_theme->background_divs($args); } endif;

/**
 * Setup IE6 PNG fix - yea, sometimes you still need it (pesky budgets!)
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.913
 * @updated 0.913
 */
if ( !function_exists( 'wfx_ie6_png' ) ) : function wfx_ie6_png($args) { global $wfx_theme; $wfx_theme->ie6_png($args); } endif;

/*
	 #####
	#     #
	#
	######
	#     #
	#     #
	 #####

	Javascript support functions
*/

/**
 * Setup and insert JQuery
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.92
 * @updated 1.1
 */
if ( !function_exists( 'wfx_jquery' ) ) : function wfx_jquery($args='') { global $wfx_theme; $wfx_theme->jquery($args); } endif;

/**
 * Setup JQuery Cycle how you want it
 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
 *
 * @since 0.92
 * @updated 0.931
 */
if ( !function_exists( 'wfx_js_cycle' ) ) : function wfx_js_cycle($args='') { global $wfx_theme; $wfx_theme->cycle($args); } endif;

/*
	#######
	#    #
	    #
	   #
	  #
	  #
	  #

	Admin functions
*/

/**
 * Control the display of Wonderflux admin menus
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx_admin_menus' ) ) : function wfx_admin_menus() { global $wfx_admin; $wfx_admin->admin_menus(); } endif;

/**
 * Adds Wonderflux options to appearance menu (respects WF_ADMIN_ACCESS)
 *
 * @since 0.93
 * @updated 0.93
 */
if ( !function_exists( 'wfx_admin_bar_links' ) ) : function wfx_admin_bar_links() { global $wfx_wp_helper; $wfx_wp_helper->admin_bar_links(); } endif;

/**
 * Adds files currently in use to the Wonderflux admin bar menu
 *
 * @since 1.2
 * @updated 1.2
 */
if ( !function_exists( 'wfx_admin_bar_files_info' ) ) : function wfx_admin_bar_files_info() { global $wfx_wp_helper; $wfx_wp_helper->admin_bar_files_info(); } endif;

/*
	 #####
	#     #
	#     #
	 #####
	#     #
	#     #
	 #####

	Admin post/content functions
*/

/**
 * Remove page templates if required
 * Sadly can't load this on load-(page) hook as the filter doesn't work - too early I think
 *
 * @since 2.0
 * @updated 2.0
 */
if ( !function_exists( 'wfx_remove_page_templates' ) ) : function wfx_remove_page_templates($input) { global $wfx_admin_post; return $wfx_admin_post->remove_page_templates($input); } endif;

/*
	 #####
	#     #
	#     #
	 ######
	      #
	#     #
	 #####

	Direct activation fallbacks
*/

/**
 * Defines basic theme functionality
 * Only for when Wonderflux is activated directly
 *
 * @since 2.0
 * @updated 2.0
 */
function wfx_core_default_setup() {

	add_theme_support( 'post-thumbnails' );

}

/**
 * Configures basic layout
 * Only for when Wonderflux is activated directly
 *
 * @since 0.902
 * @updated 2.0
 */
function wfx_core_default_widgets() {

	// Runs wf_widgets just like any good Wonderflux theme should - define AND insert on any hook quickly and easily.
	wfx_widgets(
		array (
			array (
					"name" => __( 'Above sidebar', 'wonderflux' ),
					"description" => __( 'Drag widgets into here to include them above the sidebar on your site.', 'wonderflux' ),
					"location" => "wfsidebar_before_all",
					"titleclass" => "primary-widget-title"
			),
			array (
					"name" => __( 'Below sidebar', 'wonderflux' ),
					"description" => __( 'Drag widgets into here to include them below the sidebar on your site.', 'wonderflux' ),
					"location" => "wfsidebar_after_all",
					"titleclass" => "primary-widget-title"
			)
		)
	);

}

/**
 * Adds '.wrapper' div around content blocks
 * Only for when Wonderflux is activated directly
 *
 * @since 2.0
 * @updated 2.0
 */
function wfx_core_default_wrappers() {

	// Add 'wrapper' divs around content
	//wfx_background_divs('depth=1&location=site');
	wfx_background_divs('depth=1&location=header');
	wfx_background_divs('depth=1&location=main');
	wfx_background_divs('depth=1&location=footer');

}
?>