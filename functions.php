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

add_action( 'init', 'wfx_config_language' );
add_action( 'wp_enqueue_scripts', 'wfx_core_comment_js', 2 );
add_action( 'get_header', 'wfx_display_head_open', 1 );
add_action( 'get_header', 'wfx_display_body_tag', 1 );
add_action( 'the_post', 'wfx_filter_post_class', 2 );
add_action( 'get_header', 'wfx_layout_build', 1 );
add_action( 'get_header', 'wfx_content_width_embed', 2 );
add_action( 'get_header', 'wfx_social_meta' );
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

} elseif (WF_THEME_FRAMEWORK_REPLACE == true) {

	/**
	 * Replaces framework CSS files (core and dynamic layout system).
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to use.
	 * Create the following files in your child theme folder (see Wonderflux Advanced tab to generate output).
	 * For pixel based system - 'style-framework.css' and optionally 'style-framework-ie.css'.
	 * For % based system (Flux Layout) - 'flux-layout-merged.css'.
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
 * @param	[string] $echo			Echo or return output
 * @param	[string] $extra 		Comma seperated, extra CSS classes you wish to add
 * @param	[string] $position		Position of your additional $extra CSS classes. after/before [after]
 * @param	[string] $just_string 	Just string of classes or wrap the output in 'class=""' like normal WordPress? Y/N [N]
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
if ( !function_exists( 'wfx_display_code_credit' ) ) : function wfx_display_code_credit() { global $wfx; $wfx->code_credit(); } endif;


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
	$echo = (isset($echo_do['echo']) && $echo_do['echo'] == 'N') ? 'N' : 'Y';
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
 *
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


/**
 * Setup menu
 *
 * Only for when Wonderflux is activated directly
 *
 */
function wfx_core_register_nav(){
	register_nav_menus( array(
		'primary' => __( 'Primary navigation', 'wonderflux' )
	)
	);
}


/**
 * Insert primary navigation in a fancy way by hooking into layout
 * outside of main container (for full screen width)
 * NOTE: Won't render a menu if not set (or menu is empty)
 * admin > Appearance > Menus / Manage locations
 *
 * Only for when Wonderflux is activated directly
 *
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