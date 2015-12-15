<?php
/**
 * Wonderflux is a free open source, theme framework for professional WordPress theme design.
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * I'M NOT MEANT TO BE ACTIVATED DIRECTLY THANKS - IM A THEME FRAMEWORK!
 * Put me in your themes folder along with a Wonderflux child theme, then activate child theme, not me!
 *
 * Free example child theme:		https://github.com/Jonnyauk/wonderflux-girder
 *
 * GETTING STARTED GUIDES
 * Information and license:			README.md
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
load_template( get_template_directory() . '/wf-includes/wf-engine.php' );


//// 1.1 // Early setup (before init)


add_action( 'after_setup_theme', 'wfx_core_feed_links', 2 );
add_action( 'after_setup_theme', 'wfx_core_title_tag', 2 );


//// 1.2 // Special child theme functions


// Use this to configure all your Wonderflux child theme layout functions like wfx_background_divs()
// Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
if ( function_exists('my_wfx_layout') ) { add_action( 'get_header', 'my_wfx_layout', 1 ); }
// Use this to configure all your Wonderflux child theme script functions like wfx_jquery()
// Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future
if ( function_exists('my_wfx_scripts') ) { if ( !is_admin() ) : add_action( 'init', 'my_wfx_scripts', 1 ); endif; }


//// 1.3 // Columns functionality


// Allow full removal of the framework CSS system or minified version
if ( WF_THEME_FRAMEWORK_NONE == true ) {
	// Silence is golden
} elseif ( WF_THEME_FRAMEWORK_REPLACE == true ) {
	add_action( 'wf_head_meta', 'wfx_head_css_replace', 2 );
} else {
	add_action( 'wf_head_meta', 'wfx_display_head_css_structure', 2 );
	add_action( 'wf_head_meta', 'wfx_display_head_css_columns', 2 );
	add_action( 'wf_head_meta', 'wfx_display_head_css_ie', 2 );
}


//// 1.4 // If Wonderflux activated directly with no child theme


if ( wp_get_theme()->Name == 'Wonderflux' ) {

	add_action( 'after_setup_theme', 'wfx_core_default_setup', 2 );
	add_action( 'widgets_init', 'wfx_core_default_widgets', 1 );
	add_action( 'get_header', 'wfx_core_default_wrappers', 1 );

	// Advised for theme compatibility
	add_editor_style( 'style.css' );

	$background_defaults = array(
		'default-color'          => 'ffffff',
		'wp-head-callback'       => '_custom_background_cb',
	);
	add_theme_support( 'custom-background', $background_defaults );

	add_action( 'after_setup_theme', 'wfx_core_register_nav' );
	add_action('wfmain_before_all_container','wfx_core_insert_primary_nav', 2);

}


//// 1.5 // Wonderflux core functionality


add_action( 'after_setup_theme', 'wfx_config_language' );
add_action( 'wp_enqueue_scripts', 'wfx_core_comment_js', 2 );
add_action( 'get_header', 'wfx_display_head_open', 1 );
add_action( 'get_header', 'wfx_display_body_tag', 1 );
add_action( 'the_post', 'wfx_filter_post_class', 2 );
add_action( 'get_header', 'wfx_layout_build', 1 );
add_action( 'get_header', 'wfx_content_width_embed', 2 );
add_action( 'get_header', 'wfx_social_meta' );
add_action( 'get_header', 'wfx_rwd_full_width', 2 );
add_action( 'wf_head_meta', 'wfx_display_head_char_set', 1 );
add_action( 'wf_head_meta', 'wfx_display_head_viewport', 2 );
add_action( 'wf_head_meta', 'wfx_display_head_title', 3 );
add_action( 'wf_head_meta', 'wfx_display_head_css_theme', 3 );
add_action( 'wf_head_meta', 'wfx_display_css_info' );
add_action( 'admin_bar_menu', 'wfx_admin_bar_links', 100 );
add_action( 'wffooter_after_content', 'wfx_display_credit', 1 );
add_action( 'wf_footer', 'wfx_display_code_credit', 3 );
add_action( 'auth_redirect', 'wfx_admin_menus' );
add_filter( 'theme_page_templates','wfx_remove_page_templates' );


//// 1.6 // Wonderflux debug functionality


if ( WF_DEBUG == true ){
	add_action( 'init','wfx_show_hooks' );
	add_action( 'admin_bar_menu', 'wfx_admin_bar_files_info', 100 );
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
 * Creates array of information about file based on filename.
 * IMPORTANT - Used internally by Wonderflux.
 *
 * Filters available:
 * wflux_ext_img - array of image file extensions
 *
 * @since	1.1
 * @version	1.1
 *
 * @param	[string] $filename		REQUIRED File name with extension (no path)
 * @return	[array]					ext,type,nicetype,playable
 */
if ( !function_exists( 'wfx_info_file' ) ) : function wfx_info_file($filename='') { global $wfx_helper; return $wfx_helper->info_file($filename); } endif;


/**
 * Detects what type of content you are currently viewing.
 * IMPORTANT - Used internally by Wonderflux.
 *
 * @since	0.881
 * @version	1.0RC3
 *
 * @param	none
 * @return	[string]				Current view - eg 'category'
 */
if ( !function_exists( 'wfx_info_location' ) ) : function wfx_info_location() { global $wfx_helper; return $wfx_helper->info_location(); } endif;


/**
 * Detects if you are viewing single content - post, page, attachment, author
 * as opposed to archive or any other type of views, kinda like is_singular() and is_single()
 *
 * @since	1.0
 * @version	1.1
 *
 * @param	none
 * @return	[bool]					true/false
 */
if ( !function_exists( 'wfx_info_single' ) ) : function wfx_info_single() { global $wfx_helper; return $wfx_helper->info_single(); } endif;


/**
 * Turbo-charged get_template_part file include - loads a template part into a template.
 * IMPORTANT: Used by Wonderflux internally to setup smarter specific template parts.
 *
 * Appends various location information and uses those files if available in your theme folder.
 *
 * Can also use mobile optimised screen alternative template parts for non-desktop devices (like phones or tablets)
 * by creating an additional file with '-mobile' appended, like: loop-content-single-mobile.php
 *
 * EXAMPLES
 * All examples are with $part='loop-content' and shows the order of priority of files
 *
 * SINGLE POST (INCLUDING CUSTOM POST TYPES)
 * NOTE: Normal 'post' post type uses loop-content-single.php NOT loop-content-single-post.php
 * 1 loop-content-single-{POST-TYPE-SLUG}.php
 * 2 loop-content-single.php
 * 3 loop-content.php
 *
 * CATEGORY ARCHIVE
 * 1 loop-content-category-{CATEGORY-SLUG}.php
 * 2 loop-content-category.php
 * 3 loop-content-archive.php (common archive template)
 * 4 loop-content.php
 *
 * TAXONOMY ARCHIVE
 * 1 loop-content-taxonomy-{taxonomy-name}-{taxonomy-term}.php
 * 2 loop-content-taxonomy-{taxonomy-name}.php
 * 3 loop-content-taxonomy.php
 * 4 loop-content-archive.php (common archive template)
 * 5 loop-content.php
 *
 * TAG ARCHIVE
 * 1 loop-content-tag-{tag-slug}.php
 * 2 loop-content-tag.php
 * 3 loop-content-archive.php (common archive template)
 * 4 loop-content.php
 *
 * DATE ARCHIVE
 * 1 loop-content-date-{YEAR}-{MONTH}.php (4 digit year, 2 digit month with leading zero if less than 10)
 * 2 loop-content-date-{YEAR}.php (4 digit year)
 * 3 loop-content-date.php
 * 4 loop-content-archive.php (common archive template)
 * 5 loop-content.php
 *
 * POST ARCHIVE (especially useful for custom post type archives!)
 * 1 loop-content-archive-{post-type-slug}.php
 * 2 loop-content-archive.php (common archive template)
 * 3 loop-content.php
 *
 * AUTHOR TODO: Do username template drill
 * 1 loop-content-author.php
 * 2 loop-content.php
 *
 * HOMEPAGE
 * 1 loop-content-home.php
 * 2 loop-content.php
 *
 * SEARCH
 * 1 loop-content-search.php
 * 2 loop-content.php
 *
 *
 * ATTACHMENT TODO: Basic range of filetypes support
 * 1 loop-content-attachment.php
 * 2 loop-content.php
 *
 * PAGE
 * 1 loop-content-page.php
 * 2 loop-content.php
 *
 * 404 ERROR PAGE
 * 1 loop-content-404.php
 * 2 loop-content.php
 *
 * @since	0.881
 * @version	2.1
 *
 * @param	[string] $part 			REQUIRED The slug name for the generic template
 *
 * @todo 							Extend the simple WP core $is_mobile detection
 */
if ( !function_exists( 'wfx_get_template_part' ) ) : function wfx_get_template_part($args) { global $wfx_helper; $wfx_helper->gt_part($args); } endif;


/**
 * Gets user role of logged-in user.
 * IMPORTANT - Used internally by Wonderflux.
 *
 * @since	0.62
 * @version	2.1
 *
 * @param	[string] $echo 			Do you want to echo instead of return? Y/N [N]
 * @return	[string]				Current user role, eg 'administrator' or false
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
 * Gets current page 'depth' when using parent/child/grandchild etc page structure.
 *
 * @since	0.86
 * @version	0.92
 *
 * @param	[int] $start 			Where you would like to start the depth countr from [0]
 * @param	[string] $show_all 		Return root level on homepage and search. Y/N [N]
 * @return	[int]					Integer representing depth of page
 */
if ( !function_exists( 'wfx_page_depth' ) ) : function wfx_page_depth($args) { global $wfx_helper; return $wfx_helper->page_depth($args); } endif;


/**
 * Get a custom field value for the main queried post.
 * Can be used inside or outside the loop.
 *
 * @since	0.92
 * @version	2.1
 *
 * @param	[string] $name 			REQUIRED The key name of the custom field
 * @param	[string] $empty 		If there is no value in custom field, do you want an alternative value? [false]
 * @param	[string] $escape 		Do you want the output HTML escaped? - Y/N [N]
 * @param	[string] $format 		What type of data is it, do you want to change this date format? - string/date [string]
 * @param	[string] $date_style 	PHP date format style [l F j, Y]
 * @param	[string] $error 		Do you want something returned on search and 404? - Y/N [N]
 * @param	[string] $trim 			Trim white space characters from start and end of custom field value - Y/N [Y]
 * @param	[int] $id 				Function usually returns main loop custom field, setting $id forces function to get custom field from specific post ID [false]
 * @param	[string] $echo 			Do you want to echo instead of return? - Y/N [N]
 * @return	[mixed]					Custom field value
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
 * Returns 'Y' - nothing more, nothing less!
 * Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__Y' ) in your child theme, saves creating a function
 *
 * @since	0.93
 * @version	0.93
 *
 * @param	none
 * @return	[string]				Y
 */
if ( !function_exists( 'wfx__Y' ) ) : function wfx__Y() { global $wfx_helper; return $wfx_helper->__Y(); } endif;


/**
 * Returns 'N' - nothing more, nothing less!
 * Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__N' ) in your child theme, saves creating a function
 *
 * @since	0.93
 * @version	0.93
 *
 * @param	none
 * @return	[string]				N
 */
if ( !function_exists( 'wfx__N' ) ) : function wfx__N() { global $wfx_helper; return $wfx_helper->__N(); } endif;


/**
 * Displays input (or WordPress query information) in a nicer, more useful way for debugging.
 * Only displays for logged-in administrator level users by default.
 * Contains other powerful query debugging functions.
 *
 * $input can be set as the following for powerful WordPress debugging:
 * wp_query - WordPress $wp_query
 * wp_posts - WordPress $posts
 * wp_queried - Current queried object
 * wp_all_taxonomies - All Taxonomies
 *
 * @since	1.1
 * @version	2.0
 *
 * @param	[mixed] $input 			REQUIRED What you want to debug!
 * @param	[string] $label 		Add a title to top of output to help identify it if using multiple debugs.
 * @param	[bool] $admin_only 		Only display to logged-in administrator level users, not other users. true/false [true]
 * @param	[string] $show_all 		Only display to supplied WordPress role. true/false [false]
 * @param	[int] $id 				Only display to supplied user ID. [false]
 */
if ( !function_exists( 'wfx_debug' ) ) : function wfx_debug($input='',$label='',$admin_only=true,$role=false,$id=false) { global $wfx_helper; $wfx_helper->debug($input,$label,$admin_only,$role,$id); } endif;


/**
 * Reveals all Wonderflux hooks available in current view.
 *
 * When logged in as a user has capability of manage_options (can be override with wflux_debug_show_hooks filter) 
 * and WF_DEBUG constant defined as true, this plugin reveals the location of all relevant Wonderflux display hooks within your theme.
 *
 * Filters available:
 * wflux_debug_show_hooks - display hooks information to all levels of users instead of those with manage_options capability.
 *
 * @since	1.2
 * @version	1.2
 *
 * @param	none
 */
if ( !function_exists( 'wfx_show_hooks' ) ) : function wfx_show_hooks() { global $wfx_helper; $wfx_helper->show_hooks(); } endif;


/**
 * Returns array of common HTML tags to be used with kses or similar.
 * Use filter 'wflux_allowed_tags' to mainpulate allowed tags
 *
 * @since	1.1
 * @version	1.1
 *
 * @param	none
 * @return	[array]					Allowed tags array
 */
if ( !function_exists( 'wfx_allowed_tags' ) ) : function wfx_allowed_tags() {
	 global $wfx_data_manage; return $wfx_data_manage->allowed_tags();
} endif;


/**
 * Strips white space and other cruft in html type output
 *
 * DOES NOT sanitise $input!
 *
 * @since	1.1
 * @version	1.1
 *
 * @param	[int] $input 			HTML imput
 * @return	[string]				Cleaned-up HTML output
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
 * Enables post and site/post comment RSS feed links in head of output.
 * THIS IS REQUIRED for WordPress theme repo compliance.
 * Easy to remove by remove_theme_support, remove_action, overload function etc!)
 *
 * @since	1.1
 * @version	2.0
 *
 * @param	none
 */
if ( !function_exists( 'wfx_core_feed_links' ) ) : function wfx_core_feed_links() { global $wfx_theme_support; $wfx_theme_support->core_feed_links(); } endif;


/**
 * Enables title-tag support (available in WordPress 4.1+)
 * THIS IS REQUIRED for WordPress theme repo compliance.
 * Easy to remove by remove_theme_support, remove_action, overload function etc!)
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	none
 */
if ( !function_exists( 'wfx_core_title_tag' ) ) : function wfx_core_title_tag() { global $wfx_theme_support; $wfx_theme_support->core_title_tag(); } endif;


/**
 * Core WordPress threaded comment reply Javascript.
 * THIS IS REQUIRED for WordPress theme repo compliance.
 *
 * @since	1.1
 * @version	2.0
 *
 * @param	none
 */
if ( !function_exists( 'wfx_core_comment_js' ) ) : function wfx_core_comment_js() { global $wfx_theme; $wfx_theme->core_comment_js(); } endif;


// Only need functions if have child theme overrides
if ( WF_THEME_FRAMEWORK_REPLACE == false ) {


	/**
	 * Inserts the core structure static CSS.
	 * Switches automatically between old pixel based and new Flux Layout systems.
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to remove.
	 *
	 * Filters available:
	 * wflux_css_structure_path - full path to file
	 * wflux_css_structure_version - version number appended to file
	 * wflux_css_structure_id - ID of file
	 *
	 * @since	0.72
	 * @version	2.0
	 *
	 * @param	none
	 */
	if ( !function_exists( 'wfx_display_head_css_structure' ) ) : function wfx_display_head_css_structure($args) { global $wfx; $wfx->head_css_structure($args); } endif;

	/**
	 * Inserts the core structure dynamic layout CSS.
	 * Switches automatically between old pixel based and new Flux Layout systems.
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to remove.
	 *
	 * Filters available:
	 * wflux_css_columns_path - full path to file
	 * wflux_css_columns_version - version number appended to file
	 * wflux_css_columns_id - ID of file
	 * wflux_css_columns_media - Media type
	 *
	 * @since	0.72
	 * @version	2.0
	 *
	 * @param	none
	 */
	if ( !function_exists( 'wfx_display_head_css_columns' ) ) : function wfx_display_head_css_columns() { global $wfx; $wfx->head_css_columns(); } endif;

	/**
	 * Inserts pixel based legacy Internet Explorer CSS (<IE8).
	 * Only deployed if you are using old pixel based layout system.
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to remove.
	 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future.
	 *
	 * Filters available:
	 * wflux_css_ie_path - full path to file
	 * wflux_css_ie_id - ID of file
	 * wflux_css_ie_media - Media type
	 *
	 * @since	0.72
	 * @version	2.0
	 *
	 * @param	none
	 */
	if ( !function_exists( 'wfx_display_head_css_ie' ) ) : function wfx_display_head_css_ie() { global $wfx; $wfx->head_css_ie(); } endif;


} elseif ( WF_THEME_FRAMEWORK_REPLACE == true ) {


	/**
	 * Replaces framework CSS files (core and dynamic layout system).
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to use.
	 * Use this to optimise your site - once you have your layout generated you are unlikely to need to change it usually!
	 *
	 * Create the following files in your child theme folder (see Wonderflux Advanced tab to generate output):
	 * - For pixel based system - 'style-framework.css' and optionally 'style-framework-ie.css'.
	 * - For % based system (Flux Layout) - 'flux-layout-merged.css'.
	 * If the file exists in your child theme, it will then be added (registered and enqueued) automatically - cool!
	 *
	 * Filters available:
	 * wflux_css_theme_framework_media - Media type
	 *
	 * @since	0.93
	 * @version	2.0
	 *
	 * @param	none
	 */
	if ( !function_exists( 'wfx_head_css_replace' ) ) : function wfx_head_css_replace($args) { global $wfx; $wfx->head_css_replace($args); } endif;


}


/**
 * Setup for WordPress language packs for translators.
 * THIS IS REQUIRED for WordPress theme repo compliance.
 *
 * @since	0.913
 * @version	1.1
 *
 * @param	none
 *
 * @todo EXPERIMENTAL FIRST PASS - needs testing!
 */
if ( !function_exists( 'wfx_config_language' ) ) : function wfx_config_language() { global $wfx_theme; $wfx_theme->language_pack(); } endif;


/**
 * Builds the start of the head with doc type declaration.
 *
 * @since	0.931
 * @version	2.0
 *
 * @param	none
 */
if ( !function_exists( 'wfx_display_head_open' ) ) : function wfx_display_head_open() { global $wfx; $wfx->head_open(); } endif;


/**
 * Inserts the Content-Type/charset meta tag.
 *
 * @since	0.931
 * @version	0.931
 *
 * @param	[string] $doctype 		Document type transitional/strict/frameset/1.1/1.1basic/html5 [transitional]
 * @param	[string] $content 		Document content [html]
 * @param	[string] $charset		Character encoding [utf8]
 */
if ( !function_exists( 'wfx_display_head_char_set' ) ) : function wfx_display_head_char_set($args) { global $wfx; $wfx->head_char_set($args); } endif;


/**
 * Inserts viewport meta tag.
 * Only deployed if you are using new % based layout system (Flux Layout).
 *
 * Filters available:
 * wflux_head_viewport - Viewport meta content.
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	none
 */
if ( !function_exists( 'wfx_display_head_viewport' ) ) : function wfx_display_head_viewport() { global $wfx; $wfx->head_viewport(); } endif;


/**
 * Builds the title in the head of the document.
 * BACKPAT: When using WordPress 4.1 or above add_theme_support( 'title-tag' ) is automatically used instead.
 *
 * @since	0.1
 * @version	2.0
 *
 * @param	none
 */
if ( !function_exists( 'wfx_display_head_title' ) ) : function wfx_display_head_title($args) { global $wfx; $wfx->head_title($args); } endif;


/**
 * Inserts (enqueue) child theme CSS - style.css
 * BACKPAT: When using WordPress 4.1 or above add_theme_support( 'title-tag' ) is automatically used instead.
 *
 * Filters available:
 * wflux_css_theme_id - ID of file (main-theme)
 * wflux_css_theme_path - full path to file
 * wflux_css_theme_media - Media type
 *
 * @since	0.72
 * @version	1.1
 *
 * @param	none
 */
if ( !function_exists( 'wfx_display_head_css_theme' ) ) : function wfx_display_head_css_theme($args) { global $wfx; $wfx->head_css_theme($args); } endif;


/**
 * Adds extra CSS classes to the body tag via WordPress filter.
 * Classes added describe your Wonderflux layout config, basic mobile and browser detection.
 * Add more classes by using core WordPress filter 'body_class' or override whole function.
 *
 * Filters available:
 * wflux_body_class_browser - Browser detection CSS class output
 * wflux_body_class_layout - Wonderflux layout description classes
 *
 * @since	0.931
 * @version	2.1
 *
 * @param	none
 */
if ( !function_exists( 'wfx_display_body_tag' ) ) : function wfx_display_body_tag() { global $wfx; $wfx->body_tag(); } endif;


/**
 * A more flexible post_class() function.
 * DEPRECIATED - to be removed - use standard WordPress post_class() instead in your template files!
 * See wfx_filter_post_class - which filters WP post_class() instead!
 *
 * NOTES on 'wflux_post_class' filter:
 * Use $post_class var in your filter function if you want access to core WP post classes.
 * You can then do things like:
 * unset($post_class[0]) Remove an item from the array (where [0] is the index/key in the array of WP class values)
 * $post_class[] = 'my-new-class' Add an item to the array (Can also be done with the $extra param in function if required - which is simpler!)
 *
 * Filters available:
 * wflux_body_class_browser - Browser detection CSS class output
 * wflux_body_class_layout - Wonderflux layout description classes
 *
 * @since	1.0RC3
 * @version	2.1
 *
 * @param	[string] $extra 		Comma seperated, extra CSS classes you wish to add
 * @param	[string] $position		Position of your additional $extra CSS classes. after/before [after]
 * @param	[string] $just_string 	Just string of classes or wrap the output in 'class=""' like normal WordPress? Y/N [N]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
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
 * Adds extra CSS classes to post class via WordPress filter.
 * IMPORTANT - Stop using wfx_post_class() it in your child themes!!
 *
 * Filters available:
 * wflux_post_class_single : Extra CSS class added to a single post view
 * wflux_post_class_multiple : Extra CSS class added to multiple post/archive views
 * wflux_post_class_first : Extra CSS class added to first post in loop
 * wflux_post_class_last : Extra CSS class added to first post in loop
 *
 * @since	2.1
 * @version	2.1
 *
 * @param	none
 */
if ( !function_exists( 'wfx_filter_post_class' ) ) : function wfx_filter_post_class() {
	if ( !is_admin() ) {
		global $wfx; $wfx->filter_post_class();
	}
} endif;


/**
 * Displays performance information as a HTML code comment: xx queries in xx seconds.
 *
 * @since	0.3
 * @version	0.931
 *
 * @param	none
 *
 * @todo Extend with other debug information? wfx_debug() is more useful I guess for this?
 */
if ( !function_exists( 'wfx_debug_performance' ) ) : function wfx_debug_performance() { global $wfx; $wfx->debug_performance(); } endif;


/**
 * Output footer HTML code credit comment.
 *
 * Filters available:
 * wflux_comment_code_credit : Text inside code credit
 *
 * @since	0.71
 * @version	2.1
 *
 * @param	none
 */
if ( !function_exists( 'wfx_display_code_credit' ) ) : function wfx_display_code_credit() { global $wfx; $wfx->code_credit(); } endif;


/**
 * Output credit in footer of site - show your support and love for WordPress and Wonderflux!
 *
 * Filters available:
 * wflux_footer_credit_format : HTML tag to surround output with.
 * wflux_footer_credit_wp : WordPress credit text.
 * wflux_footer_divider : Divider between credt text.
 * wflux_footer_credit_wf : Wonderflux credit text.
 * wflux_footer_credit_content : Entire credit text.
 * wflux_footer_credit_div : Surround content with a div?
 *
 * @since	0.3
 * @version	2.1
 *
 * @param	none
 * @todo Review code and santization
 */
if ( !function_exists( 'wfx_display_credit' ) ) : function wfx_display_credit($args) { global $wfx; $wfx->display_credit($args); } endif;


/**
 * Displays CSS info for designers as a HTML code comment in the <head>.
 *
 * @since	0.4
 * @version	0.931
 *
 * @param	none
 *
 * @todo 	Review code and update for Flux Layout - currently only works with old % based layout system.
 */
if ( !function_exists( 'wfx_display_css_info' ) ) : function wfx_display_css_info($args) { global $wfx; $wfx->css_info($args); } endif;


/**
 * EXPERIMENTAL - generates a repeating pattern of columns for testing the grid layout system.
 *
 * @since	1.1
 * @version	1.2
 *
 * @param	[int] $rows				Maximum number of rows of divs you wish to output.
 * @param	[string] $type			Type of column definitions to use to build output - raw column classes 'columns', or nice definitions 'relative'.
 * @param	[string] $split			Undocumented (sorry - needs testing and code review!)
 * @param	[string] $compatibility	Undocumented (sorry - needs testing and code review!)
 *
 * @todo 	Review code and build a bester testing pattern!
 */
if ( !function_exists( 'wfx_display_test_pattern' ) ) : function wfx_display_test_pattern($args) { global $wfx; $wfx->test_pattern($args); } endif;


/**
 * Returns saved Wonderflux option value from main options array.
 *
 * @since	0.93
 * @version	0.93
 *
 * @param	[string] $size			Option you wish to return. site-width/columns/column-width/sidebar-1-position [site-width]
 *
 * @todo 	Build rest of output options.
 */
if ( !function_exists( 'wfx_get_dimensions' ) ) : function wfx_get_dimensions($args) { global $wfx; return $wfx->get_dimensions($args); } endif;


/**
 * IMPORTANT - Configures WordPress $content_width.
 * Sets the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
 *
 * @since	1.1
 * @version	1.1
 *
 * @param	none
 *
 * @todo 	Check over this functionality, Flux Layout deals with responsive content well already - it's been a while!
 */
if ( !function_exists( 'wfx_content_width_embed' ) ) : function wfx_content_width_embed() { global $wfx; $wfx->content_width_embed(); } endif;


/**
 * Includes sidebar template file.
 * Uses get_sidebar() and checks for Wonderflux option/filter.
 *
 * @since	0.93
 * @version	0.93
 *
 * @param	none
 *
 * @todo 	Check over this functionality, should we be extending get_sidebar() rather than replacing?
 */
if ( !function_exists( 'wfx_get_sidebar' ) ) : function wfx_get_sidebar($args) { global $wfx; $wfx->get_sidebar($args); } endif;


/**
 * Creates dynamic CSS grid class definition - used in Wonderflux pixel layout system v1.
 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
 * Just use the normal CSS classes created by Flux Layout - this is over-engineered!
 *
 * By using this function to define containers, you can dynamically resize the whole layout.
 * The only thing to watch out for is using definitions like:
 * - 'quarter' when your columns arent divisible by 4.
 * - 'third' when your columns arent divisible by 3.
 * - By checking the output of the wf_css_info() function in the head of your document, you can find out lots of useful info!
 *
 * @since	0.2
 * @version	2.0
 *
 * @param	[string] $size			Relative size definition to full width of site - eg 'half', 'quarter'. Various values [full]
 * @param	[string] $class			Additional CSS classes. [none]
 * @param	[string] $id			Optional ID. [none]
 * @param	[string] $last			Put on last container inside a row, adds .last CSS class. Y/N [N]
 * @param	[string] $move			Push and pull a div - not used at moment.
 * @param	[string] $divoutput		Wraps output in opening and closing div tags - useful for blocks of code. Y/N [N]
 * @param	[int] $columns			Size of div in columns, overrides $size. [0]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
 *
 * @todo 	Remove and move to legacy support plugin.
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
 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
 *
 * @since	0.913
 * @version	0.913
 *
 * @param	none
 *
 * @todo 	Remove and move to legacy support plugin.
 */
if ( !function_exists( 'wfx_css_close' ) ) : function wfx_css_close() { global $wfx; $wfx->css_close(); } endif;


/**
 * IMPORTANT - Creates layout wrappers around content and sidebar if begin used.
 *
 * NOTE: Inserted at hooks priority 2 or 9, to allow you to hook in with your own functions at:
 * - priority 1 for before wrappers
 * - priority 3+ inside wrappers
 *
 * @since	0.93
 * @version	0.93
 *
 * @param	none
 */
if ( !function_exists( 'wfx_layout_build' ) ) : function wfx_layout_build() { global $wfx; $wfx->layout_build(); } endif;


/**
 * IMPORTANT - Adds additional CSS classes to sidebar and content for media query breakpoints.
 * Designed to work with Flux Layout - configured in Wonderflux layout options.
 *
 * Filters available:
 * wflux_rwd_full_width : Additional generated CSS classes added to sidebar and main content (in helper function).
 *
 * @since	2.1
 * @version	2.1
 *
 * @param	none
 */
if ( !function_exists( 'wfx_rwd_full_width' ) ) : function wfx_rwd_full_width() { global $wfx; $wfx->rwd_full_width(); } endif;


/**
 * Display excerpt of post content inside the loop or custom query.
 *
 * @since	0.85
 * @version	1.1
 *
 * @param	[int] $limit			Number of words. [20]
 * @param	[string] $excerpt_end 	Characters to add to end of the excerpt. [...]
 * @param	[string] $trim			Trim off punctuation from end of excerpt - good when you don't want it to bump into your excerpt end. Y/N [Y]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
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
 * Displays a single post/page/whatever.
 *
 * @since	0.85
 * @version	1.2
 *
 * @param	[int] $id				REQUIRED, ID of the content you want. [false]
 * @param	[string] $titlestyle	Title element definition. [h4]
 * @param	[string] $contentstyle	Content element definition. [p]
 * @param	[string] $title			Display title. Y/N [Y]
 * @param	[string] $titlelink		Link title to content? Y/N [N]
 * @param	[int] $exerptlimit		Limit number of words in post content. [20]
 * @param	[string] $exerptend		Characters to add to end of the excerpt. [...]
 * @param	[string] $morelink		Display read more link. Y/N [N]
 * @param	[string] $morelinktext	Text used for read more link. [More]
 * @param	[string] $morelinkclass	CSS class added to read more link. [wfx-get-page-loop-more]
 * @param	[string] $boxclass		CSS class added to container div. [wfx-get-page-loop]
 * @param	[string] $contentclass	CSS class added to content div. [wfx-get-page-loop-content]
 *
 * @todo 	Review code, make smarter and deploy into Wonderflux widget.
 */
if ( !function_exists( 'wfx_get_single_content' ) ) : function wfx_get_single_content($args) { global $wfx; $wfx->get_single_content($args); } endif;


/**
 * Adds admin/editing links.
 * Creates un-ordered list inside an optional div.
 *
 * @since	0.85
 * @version	2.1
 *
 * @param	[string] $userintro		Text string in first list item. [Welcome]
 * @param	[string] $username		Display username after intro (within same list item). Y/N [Y]
 * @param	[string] $intro			Display intro. Y/N [Y]
 * @param	[string] $postcontrols	Show post controls. Y/N [Y]
 * @param	[string] $pagecontrols	Show page controls. Y/N [Y]
 * @param	[string] $adminlink		Show admin area link. Y/N [Y]
 * @param	[string] $widgetslink	Show edit widgets link. Y/N [N]
 * @param	[string] $logoutlink	Show WordPress logout link. Y/N [N]
 * @param	[string] $ulclass		<ul> class. [wf-edit-meta-main]
 * @param	[string] $liclass		<li> class. Y/N [wf-edit-meta-links]
 * @param	[string] $div			Wrap output in containing <div>. Y/N [N]
 * @param	[string] $divclass		Containing <div> class if used. [wf-edit-meta-box]
 *
 * @todo 	Review code, make smarter and deploy into Wonderflux widget.
 * @todo	Extend further to accomodate when a user is NOT logged in (like WordPress Meta widget stylee!)
 * @todo	Extend for user profiles, editing taxonomies etc.
 */
if ( !function_exists( 'wfx_edit_meta' ) ) : function wfx_edit_meta($args) { global $wfx; $wfx->edit_meta($args); } endif;


/**
 * Creates a login/logout link with redirect options.
 *
 * @since	0.901
 * @version	2.1
 *
 * @param	[string] $login			Logout link text. [Login]
 * @param	[string] $logintip		Login link title/tooltip. [Login to site]
 * @param	[string] $logout		Logout link text. [Logout]
 * @param	[string] $logouttip		Logout link title/tooltip. [Logout of site]
 * @param	[string] $loginurl		Re-direct on login either to current view or home. current/home [current]
 * @param	[string] $logouturl		Re-direct on logout either to current view or home. current/home [home]
 *
 * @todo 	Review code, make smarter and deploy into Wonderflux widget.
 * @todo	Extend further to accomodate other re-directs.
 */
if ( !function_exists( 'wfx_login_logout' ) ) : function wfx_login_logout($args) { global $wfx; $wfx->login_logout($args); } endif;


/**
 * Adds 'current_page_item' CSS class when post ID is current post/page/whatever being viewed.
 * For when you really need to code a static navigation.
 *
 * @since	0.901
 * @version	0.913
 *
 * @param	[int] $id				ID of post. []
 *
 * @todo 	Review code, make smarter!
 */
if ( !function_exists( 'wfx_static_highlight' ) ) : function wfx_static_highlight($args) { global $wfx; $wfx->static_highlight($args); } endif;


/**
 * Gets attachment(s) or featured images of main post query (inside or outside the loop).
 * Will try and playback files or create nice links.
 * IMPORTANT - used in loop-content-attachment.php
 *
 * @since	0.901
 * @version	0.913
 *
 * @param	[string] $type			Type of output. all/featured_image/attachment [all]
 * @param	[string] $mime_type		Limit to certain mime type (only used if $type=all). []
 * @param	[int] $amount			Limit number of attachments (only used if $type=all). [-1]
 * @param	[string] $order			Order attachments (only used if $type=all). ASC/DESC [ASC]
 * @param	[string] $output		What tag to use to wrap attachment output. p/ul/ol [ul]
 * @param	[string] $img_size		Registered image size definition (only used if $type=featured_image). [large]
 * @param	[bool] $div_wrap		Wrap all output in optional div. true/false [false]
 * @param	[string] $div_class		CSS classes used on wrapper div (only used if $div_wrap=true). [box-get-attachment]
 * @param	[string] $element_class	CSS classes used on media output. [get-attachment-file]
 * @param	[string] $link_class	CSS classes used on link output. [get-attachment-link]
 * @param	[string] $img_class		CSS classes used on image output. [attachment-single responsive-full-width]
 * @param	[string] $output_start	Text show before text links [Download]
 * @param	[string] $output_end	Text shown after text links. [false]
 * @param	[string] $meta_key		Used for more advanced attachment queries - see WP core get_children() [false]
 * @param	[string] $meta_value	Used for more advanced attachment queries - see WP core get_children() [false]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
 *
 * @todo	Extend with ID control for any post ID.
 * @todo	Extend media playback control.
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
 * Creates page navigation for lists of results like archive or query views.
 *
 * @since	0.93
 * @version	2.0
 *
 * @param	[string] $element		What tag to use to wrap output (can be empty to setup at template level). [p]
 * @param	[string] $start			Opening text string. [Page ]
 * @param	[string] $seperator		Seperator between pages. [ of ]
 * @param	[string] $current_span	CSS span class around current page number (set to blank to remove span). [page-counter-current]
 * @param	[string] $total_span	CSS span class around total page number (set to blank to remove span). [page-counter-total]
 * @param	[string] $always_show	No output is shown if there is only 1 page of results, setting to 'Y' to always show (ie page 1 of 1). Y/N [N]
 * @param	[string] $navigation	Display next and previous navigation either side of the page display. Y/N [N]
 * @param	[string] $nav_span		CSS span class around totalnavigation links (set to blank to remove span). Y/N [page-counter-navigation]
 * @param	[string] $previous		Text for previous link. [&lt; ]
 * @param	[string] $next			Text for next link. Y/N [ &gt;]
 * @param	[string] $div			Wrap output in containing <div>. Y/N [Y]
 * @param	[string] $div_class		Containing <div> class if used. [page-counter]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
 *
 * @todo	Review code, make smarter!
 * @todo	Extend with wp_link_pages() type functionality so it can function with paged single pages, not just query lists.
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
 * Powerful template part fragment cache for output optimisation.
 * WARNING: This is kinda experimental and might change - keep an eye on development if using in production or why not patch?!
 * Include a theme template file and cache include output as transient option for desired time in minutes.
 * Works with most things, but may not work with more advanced functions/plugins that inject CSS or JS into header/footer or do other funky stuff!
 *
 * NOTE: Transient option saved as 'Child_Theme_Name_c_$part'.
 *
 * To flush cache append the following onto URL:
 * Example 1 - flush all files www.mydomain.com/?flushcache_all=1
 * Example 2 - flush individual cached element www.mydomain.com/?flushcache_NAME_OF_INCLUDE=1
 *
 * Filters available:
 * wflux_allowed_cached_tags : array of allowed output tags used with kses.
 *
 * @since	1.1
 * @version	2.1
 *
 * @param	[string] $part			REQUIRED - name of the file in active theme directory you want to include and cache, without file extension. []
 * @param	[string] $file_ext		File extension of the file you want to cache (without '.'!). [php]
 * @param	[int] $expire			Length of time (in minutes) that the cache persists. [360]
 * @param	[string] $sanitise_in	Sanitises before saving to cache. html/none [html]
 * @param	[string] $sanitise_out	Sanitises on output. html/none [html]
 * @param	[string] $mimify		Remove whitespace before saving as transient. Y/N [Y]
 * @param	[string] $transient_key	Define optional custom transient option name (NOTE: will be trimmed to 32 characters max). []
 * @param	[string] $flushable		Can the cached item be force flushed/refreshed via url, user must have edit_theme_options capability. Y/N [Y]
 * @param	[string] $output_start	Added to start of output (not saved in transient, runs through output sanitisation if set). [<!--cached-part-start-->]
 * @param	[string] $output_end	Added to end of output (not saved in transient, runs through output sanitisation if set). [<!--cached-part-end-->]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
 *
 * @todo	Extend $sanitise_in and sanitise_out with more options.
 * @todo	Should this be made location aware?
 * @todo	Think about multisite integration - set_site_transient() - test!
 * @todo	Extend $sanitise_in and sanitise_out with more options.
 * @todo	Deeper transient key length - only 64 chars allowed in options table name? Use 45 characters or 32 max?? - Watch _transient_timeout_{$transient_key}
 */
if ( !function_exists( 'wfx_get_cached_part' ) ) : function wfx_get_cached_part($args) {
	wp_parse_str($args, $echo_do);
	$echo = (isset($echo_do['echo']) && $echo_do['echo'] == 'N') ? 'N' : 'Y';
	global $wfx;
	if ($echo == 'Y') {
		 echo $wfx->get_cached_part($args);
	} else {
		 return $wfx->get_cached_part($args);
	}
} endif;


/**
 * Builds a clickable link from supplied data.
 *
 * @since	1.1
 * @version	1.1
 *
 * @param	[string] $url			REQUIRED - URL for link. []
 * @param	[string] $title			Link title/tooltip. []
 * @param	[string] $target		Link target attribute. []
 * @param	[string] $class			CSS class for link. []
 * @param	[string] $id			CSS ID for link. []
 * @param	[string] $text			Text to show in link (defaults to $url if none supplied). []
 * @param	[bool] $span			Wrap $text in optional <span>. true/false [false]
 * @param	[string] $span_class	Adds CSS class to optional <span>. []
 * @param	[string] $type			Type of link resource. eg: application/pdf. []
 * @param	[string] $rel			Relationship of link to current view/context. []
 * @param	[string] $echo			Echo or return output. Y/N [Y]
 *
 * @todo	Review code, make smarter!
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
 * Outputs an array as a string with a seperator in-between each.
 * Doesn't add to last item - useful for comma sperated output.
 *
 * @since	1.1
 * @version	1.2
 *
 * @param	[array] $values			REQUIRED - Input array (single dimenional).
 * @param	[string] $seperator		Seperator between items output from array. [, ]
 * @param	[string] $start			Text string at start of each item output. []
 * @param	[string] $end			Text string at end of each item output. []
 * @param	[bool] $esc				Use esc_html() on $values and $seperator output. [true]
 * @param	[string] $echo			Echo or return output. Y/N [Y]
 *
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
 * Outputs a Google Plus 1 button.
 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
 *
 * @since	1.1
 * @version	1.2
 *
 * @param	[string] $size			Size of social button. small/medium/standard/tall [medium]
 * @param	[string] $count			Show count of shares or not ($size=tall always shows count). no_count/show_count [show_count]
 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
 *
 * @todo	Review code!
 */
if ( !function_exists( 'wfx_social_google_plus_1' ) ) : function wfx_social_google_plus_1($args='') { global $wfx; $wfx->g_plus_1($args); } endif;


/**
 * Outputs a Facebook like button with counter.
 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
 *
 * @since	 0.931
 * @version  0.931
 *
 * @param	[string] $size			Size of social button. small/tall [small]
 * @param	[bool] $send			Show additional send button. true/false [false]
 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
 *
 * @todo	Review code!
 */
if ( !function_exists( 'wfx_social_facebook_like' ) ) : function wfx_social_facebook_like($args='') { global $wfx; $wfx->fb_like($args); } endif;


/**
 * Outputs a Twitter share button with counter.
 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
 *
 * @since	0.931
 * @version	0.931
 *
 * @param	[string] $size			Size of social button. small/tall [small]
 * @param	[string] $count			Show count of shares or not (no count only available on small). no_count/show_count [show_count]
 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
 *
 * @todo	Review code!
 */
if ( !function_exists( 'wfx_social_twitter_share' ) ) : function wfx_social_twitter_share($args='') { global $wfx; $wfx->twit_share($args); } endif;


/**
 * Outputs a LinkedIn share button with counter.
 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
 *
 * @since	1.0rc2
 * @version	1.0rc2
 *
 * @param	[string] $size			Size of social button. small/tall [small]
 * @param	[string] $count			Show count of shares or not (no count only available on small). no_count/show_count [show_count]
 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
 *
 * @todo	Review code!
 */
if ( !function_exists( 'wfx_social_linkedin_share' ) ) : function wfx_social_linkedin_share($args='') { global $wfx; $wfx->linkedin_share($args); } endif;


/**
 * Inserts associated social sharing related (Open Graph) meta tags in <head> if required.
 * Can just remove_action or change function too of-course - it's a framework don't you know!
 *
 * @since	0.931
 * @version	0.931
 *
 * @param	none
 *
 * @todo	Test and dont use if using Yoast SEO to control this.
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
 * Creates WordPress widget areas and optionally inserts using Wonderflux hook system, plus a couple of other tricks!
 *
 * @since	0.891
 * @version	2.1
 *
 * @param	[string] $name			The name of the widget area (shows in admin widget editor). [Widget area x]
 * @param	[string] $description	Description of widget (shows in admin widget editor). [Drag widgets into here to include them in your site]
 * @param	[string] $location		Wonderflux display hook to add widget area to - supply value 'my_theme_code' to turn this off. [wfsidebar_after_all]
 * @param	[string] $contain		Widget container - eg div, li. [div]
 * @param	[string] $containclass	Widget Container CSS class. [widget-box]
 * @param	[string] $containid		ADVANCED - Sets CSS ID for container (Only use this if your widget area has one widget - otherwise the IDs are repeated, which is not good and breaks validation for obvious reasons!) []
 * @param	[string] $titlestyle	What tag to use to wrap the title in. [h3]
 * @param	[string] $titleclass	CSS class for title. [widget-title]
 * @param	[string] $titleid		ADVANCED - Sets CSS ID for title (Only use this if your widget area has one widget - otherwise the IDs are repeated, which is not good and breaks validation for obvious reasons!) []
 * @param	[string] $before		Output before the widget. []
 * @param	[string] $after			Output after the widget. []
 * @param	[int] $priority			Wonderflux hook priority - NOTE default CSS containers insert at priority 2 and 9. [3]
 *
 * @todo Review code!
 * @todo Deal with multiple widgets with ID's by appending number?
 */
if ( !function_exists( 'wfx_widgets' ) ) : function wfx_widgets($args) { global $wfx_theme; $wfx_theme->widgets($args); } endif;


/**
 * Creates background wrapper <div>s around different areas of the layout.
 * Saves having to amend template files and great for setting up backgrounds or multiple backgrounds.
 *
 * Outputput formatted like:
 * <div class="wrapper" id="header-bg-1">
 * <div class="wrapper" id="header-bg-2">
 * <div class="wrapper" id="header-bg-3">
 *
 * Closes <div> automatically for you of-course!
 *
 * @since	0.92
 * @version	0.92
 *
 * @param	[int] $depth			How many wrappers to create. [1]
 * @param	[string] $location		Location of wrapper. site/main/header/footer/container-header/container-content/container-footer [site]
 *
 */
if ( !function_exists( 'wfx_background_divs' ) ) : function wfx_background_divs($args) { global $wfx_theme; $wfx_theme->background_divs($args); } endif;


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
 * Add Jquery to your theme in various ways.
 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
 * Just wp_register_script() and wp_enqueue_script() your own!
 *
 * @since	0.92
 * @version	1.1
 *
 * @param	[string] $host			Where your JQuery is hosted - select from CDN if required. wordpress/wonderflux/google/microsoft/jquery [wordpress]
 * @param	[string] $version		Which version of JQuery to use (No effect if $host = 'wordpress', use exact version string for CDN version). [1]
 * @param	[string] $location		Where to insert JQuery. header/footer [header]
 * @param	[bool] $https			Do you want https? true/false [false]
 *
 * @todo 	Remove and move to legacy support plugin.
 * @todo 	Check through and update any Wonderflux functions that may be using this.
 */
if ( !function_exists( 'wfx_jquery' ) ) : function wfx_jquery($args='') { global $wfx_theme; $wfx_theme->jquery($args); } endif;


/**
 * Add Jquery Cycle to your theme in various ways.
 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
 * Just wp_register_script() and wp_enqueue_script() your own!
 * I rather like http://kenwheeler.github.io/slick/ instead these days for a carousel ;)
 *
 * @since	0.92
 * @version	0.931
 *
 * @param	[string] $host			Where your Cycle script is hosted - select from CDN if required. wonderflux/theme/microsoft [wonderflux]
 * @param	[string] $type			Which type of Cycle script to use. lite/normal/all [normal]
 * @param	[string] $theme_dir		URL to your themes cycle config file. (with slash at the start). [/js/cycle]
 * @param	[string] $location		Where do you want to insert Cycle? header/footer [header]
 * @param	[string] $config		Do you want to use the standard Wonderflux cycle config or your themes? wonderflux/theme [wonderflux]
 *
 * @todo 	Remove and move to legacy support plugin.
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
 * Adds Wonderflux admin menus, registers settings and setsup contextual help.
 *
 * @since	0.93
 * @version	0.93
 *
 * @param	none
 *
 * @todo	Will need to review all admin options when they are moved to Customizer!
 */
if ( !function_exists( 'wfx_admin_menus' ) ) : function wfx_admin_menus() { global $wfx_admin; $wfx_admin->admin_menus(); } endif;


/**
 * Adds Wonderflux links to WordPress admin bar.
 *
 * @since	0.93
 * @version	2.0
 *
 * @param	none
 *
 * @todo	Will need to review all admin options when they are moved to Customizer!
 */
if ( !function_exists( 'wfx_admin_bar_links' ) ) : function wfx_admin_bar_links() { global $wfx_wp_helper; $wfx_wp_helper->admin_bar_links(); } endif;


/**
 * Adds files currently used to render view to the Wonderflux admin bar menu.
 * Set constant WF_DEBUG to true to enable.
 * Incredibly useful for debugging - shows which files are your child themes and which are Wonderflux core.
 *
 * @since	0.93
 * @version	2.0
 *
 * @param	none
 *
 * @todo	Will need to review all admin options when they are moved to Customizer!
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
 * Remove unwanted page templates from page attributes dropdown as set in Wonderflux options.
 * Filters theme_page_templates.
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	[array] $input			Pass through WordPress page template array for manipulation via filter.
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
 * Add basic theme support.
 * ONLY USED WHEN WONDERFLUX ACTIVATED DIRECTLY.
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	none
 */
function wfx_core_default_setup() {

	add_theme_support( 'post-thumbnails' );

}


/**
 * Add basic widgets.
 * ONLY USED WHEN WONDERFLUX ACTIVATED DIRECTLY.
 *
 * @since	0.902
 * @version	2.1
 *
 * @param	none
 */
function wfx_core_default_widgets() {

	// Runs wf_widgets() just like any good Wonderflux theme should.
	// Define AND insert on any hook quickly and easily.
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
 * Use for background styling without messing with template files.
 * ONLY USED WHEN WONDERFLUX ACTIVATED DIRECTLY.
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	none
 */
function wfx_core_default_wrappers() {

	// Add 'wrapper' divs around content
	//wfx_background_divs('depth=1&location=site');
	wfx_background_divs('depth=1&location=header');
	wfx_background_divs('depth=1&location=main');
	wfx_background_divs('depth=1&location=footer');

}


/**
 * Add basic editable menu support.
 * ONLY USED WHEN WONDERFLUX ACTIVATED DIRECTLY.
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	none
 */
function wfx_core_register_nav(){

	register_nav_menus( array(
		'primary' => __( 'Primary navigation', 'wonderflux' )
		)
	);

}


/**
 * Insert primary navigation.
 * Creates full-width container by hooking into layout outside main container.
 * ONLY USED WHEN WONDERFLUX ACTIVATED DIRECTLY.
 *
 * @since	2.0
 * @version	2.0
 *
 * @param	none
 */
function wfx_core_insert_primary_nav() {

	// Setup menu data
	// Check if it has been set or is empty - no-one likes showing all links!
	$this_menu = wp_nav_menu(
		array(
			'container_class'	=> 'header-navigation clearfix',
			'menu_id'			=> 'primary-header-nav', /*Need to add ID to target for slicknav.js*/
			'theme_location'	=> 'primary',
			'echo'				=> false,
			'fallback_cb'		=> '__return_false'
		)
	);

	if ( !empty($this_menu) ){
		echo '<div class="wrapper header-navigation-container">';
		echo '<div class="container">';
		echo $this_menu;
		echo '</div>';
		echo '</div>';
	}

}
?>