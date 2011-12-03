<?php
/**
 * Wonderflux is a free open source, GPLv2 licensed theme framework for professional WordPress theme design.
 *
 * Information and license:    README.txt
 *
 * Guide and documentation:    http://wonderflux.com/guide
 * Introduction to Wonderflux: http://wonderflux.com/guide/doc/introduction/
 * Wonderflux child themes:    http://wonderflux.com/guide/doc/child-theme-files/
 *
 * Bugs/improvements/feedback: http://code.google.com/p/wonderflux-framework/issues/list
 * Official release downloads: http://code.google.com/p/wonderflux-framework/downloads/
 * Development code:           http://code.google.com/p/wonderflux-framework/source/checkout
 *
 * 1 - Helper functions
 * 2 - Display functions
 * 3 - Theme display functions
 * 4 - Social functions
 * 5 - Theme configuration functions
 * 6 - Script support functions
 * 7 - Admin functions
 * 8 - Wonderflux Core
 * 9 - Add actions to hooks
 *
 * DON'T HACK ME!! You should NOT modify the Wonderflux theme framework to avoid issues with updates in the future.
 * It's designed to offer cutting edge flexibility - with lots of ways to manipulate output from your child theme!
 *
 * 1) Create a function with the same name as a Wonderflux display function below in your child theme.
 *    This will override the ones in this file and be used instead.
 *
 * 2) Remove a core Wonderflux action in your child theme functions file with the code:
 *    remove_action('wf_hook_name','wf_function_name', $priority);
 *
 * 3) Add a filter - http://wonderflux.com/guide/filter/
 *
 * 4) Use over 100 location-aware hooks http://wonderflux.com/guide/hook/
 *
 * If you still feel the need to hack the Wonderflux core code, why not submit a patch or suggestion?
 * http://code.google.com/p/wonderflux-framework/issues/list
 *
 * DEVELOPERS AND TESTERS
 * Developers have full SVN checkout access to trunk where the latest non-released development code is held.
 * Visit http://code.google.com/p/wonderflux-framework/source/checkout for access information.
 * All development is tracked within the Google Code SVN system.
 * The trunk version is for contributions, testing and development - NOT for live sites!!
 *
 * Thanks for using Wonderflux - follow us on Twitter @Wonderflux for updates and news.
 *
 * @package Wonderflux
 *
 */

// Start your engine
load_template(TEMPLATEPATH . '/wf-includes/wf-engine.php');


////  1  //////////// HELPER FUNCTIONS


/**
* @since 0.913
* @updated 1.0
* IMPORTANT - Gets type of view
*/
if ( !function_exists( 'wfx_info_location' ) ) : function wfx_info_location() { global $wfx_helper; return $wfx_helper->info_location(); } endif;

/**
* @since 1.0
* @updated 1.0
* IMPORTANT - Detects if you are viewing single content - post, page, attachment, author
*/
if ( !function_exists( 'wfx_info_single' ) ) : function wfx_info_single() { global $wfx_helper; return $wfx_helper->info_single(); } endif;

/**
* @since 0.913
* @updated 0.913
* IMPORTANT - Wonderflux get_template_part
*/
if ( !function_exists( 'wfx_get_template_part' ) ) : function wfx_get_template_part($args) { global $wfx_helper; $wfx_helper->gt_part($args); } endif;

/**
* @since 0.913
* @updated 0.92
* Gets user role (return or echo)
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
* @since 0.913
* @updated 0.92
* Returns current page depth
*/
if ( !function_exists( 'wfx_page_depth' ) ) : function wfx_page_depth($args) { global $wfx_helper; return $wfx_helper->page_depth($args); } endif;

/**
* @since 0.92
* @updated 0.92
* Returns custom field value
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
* @since 0.93
* @updated 0.93
* Returns 'Y' - nothing more, nothing less
* Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__Y' ) in your child theme
*/
if ( !function_exists( 'wfx__Y' ) ) : function wfx__Y() { global $wfx_helper; return $wfx_helper->__Y(); } endif;

/**
* @since 0.93
* @updated 0.93
* Returns 'N' - nothing more, nothing less
* Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__N' ) in your child theme
*/
if ( !function_exists( 'wfx__N' ) ) : function wfx__N() { global $wfx_helper; return $wfx_helper->__N(); } endif;


////  2  //////////// DISPLAY FUNCTIONS


// Only need functions if have child theme overrides
if (WF_THEME_FRAMEWORK_REPLACE == false) {
	/**
	* @since 0.72
	* @updated 0.913
	* Inserts the core framework structure CSS
	*/
	if ( !function_exists( 'wfx_display_head_css_structure' ) ) : function wfx_display_head_css_structure($args) { global $wfx; $wfx->head_css_structure($args); } endif;


	/**
	* @since 0.72
	* @updated 0.913
	* Inserts the dynamic column CSS builder
	*/
	if ( !function_exists( 'wfx_display_head_css_columns' ) ) : function wfx_display_head_css_columns($args) { global $wfx; $wfx->head_css_columns($args); } endif;

	/**
	* @since 0.72
	* @updated 0.913
	* Inserts the core IE CSS
	*/
	if ( !function_exists( 'wfx_display_head_css_ie' ) ) : function wfx_display_head_css_ie($args) { global $wfx; $wfx->head_css_ie($args); } endif;

} elseif (WF_THEME_FRAMEWORK_REPLACE == true) {

	/**
	* @since 0.93
	* @updated 0.93
	* Inserts the core IE CSS
	*/
	if ( !function_exists( 'wfx_head_css_replace' ) ) : function wfx_head_css_replace($args) { global $wfx; $wfx->head_css_replace($args); } endif;

}

/**
* @since 0.913
* @updated 0.913
* IMPORTANT Sets up Wonderflux for translation
*/
if ( !function_exists( 'wfx_config_language' ) ) : function wfx_config_language($args) { global $wfx_theme; $wfx_theme->language_pack($args); } endif;

/**
* @since 0.931
* @updated 0.931
* IMPORTANT Builds the head of the document
*/
if ( !function_exists( 'wfx_display_head_open' ) ) : function wfx_display_head_open($args) { global $wfx; $wfx->head_open($args); } endif;


/**
* @since 0.931
* @updated 0.931
* IMPORTANT Builds the head of the document
*/
if ( !function_exists( 'wfx_display_head_char_set' ) ) : function wfx_display_head_char_set($args) { global $wfx; $wfx->head_char_set($args); } endif;

/**
* @since 0.71
* @updated 0.913
* IMPORTANT Builds the title of the document
*/
if ( !function_exists( 'wfx_display_head_title' ) ) : function wfx_display_head_title($args) { global $wfx; $wfx->head_title($args); } endif;

/**
* @since 0.72
* @updated 0.913
* Inserts the core theme CSS
*/
if ( !function_exists( 'wfx_display_head_css_theme' ) ) : function wfx_display_head_css_theme($args) { global $wfx; $wfx->head_css_theme($args); } endif;

/**
* @since 0.931
* @updated 0.931
* Inserts opening dynamic body tag in output - adds Extra Wonderflux CSS classes
*/
if ( !function_exists( 'wfx_display_body_tag' ) ) : function wfx_display_body_tag($args) { global $wfx; $wfx->body_tag($args); } endif;

/**
* @since 0.72
* @updated 0.913
* Displays debug in code comment
*/
if ( !function_exists( 'wfx_debug_performance' ) ) : function wfx_debug_performance($args) { global $wfx; $wfx->debug_performance($args); } endif;

/**
* @since 0.71
* @updated 0.913
* Displays Wonderflux credit in source code of site
*/
if ( !function_exists( 'wfx_display_code_credit' ) ) : function wfx_display_code_credit($args) { global $wfx; $wfx->code_credit($args); } endif;

/**
* @since 0.71
* @updated 0.913
* Displays a credit, show your support for Wonderflux!
*/
if ( !function_exists( 'wfx_display_credit' ) ) : function wfx_display_credit($args) { global $wfx; $wfx->display_credit($args); } endif;

/**
* @since 0.51
* @updated 0.913
* Displays CSS info for designers as a comment in the head source code
*/
if ( !function_exists( 'wfx_display_css_info' ) ) : function wfx_display_css_info($args) { global $wfx; $wfx->css_info($args); } endif;

/**
* @since 0.93
* @updated 0.93
* Returns saved site dimensions
*/
if ( !function_exists( 'wfx_get_dimensions' ) ) : function wfx_get_dimensions($args) { global $wfx; return $wfx->get_dimensions($args); } endif;


////  3  //////////// THEME DISPLAY


/**
* @since 0.93
* @updated 0.93
* Gets the sidebar
*/
if ( !function_exists( 'wfx_get_sidebar' ) ) : function wfx_get_sidebar($args) { global $wfx; $wfx->get_sidebar($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates dynamic CSS grid class definition
*/
if ( !function_exists( 'wfx_css' ) ) : function wfx_css($args) { global $wfx; $wfx->css($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Just echos </div> - nothing more nothing less!
*/
if ( !function_exists( 'wfx_css_close' ) ) : function wfx_css_close($args) { global $wfx; $wfx->css_close($args); } endif;

/**
* @since 0.93
* @updated 0.93
* IMPORTANT - Creates and controls layout CSS
*/
if ( !function_exists( 'wfx_layout_build' ) ) : function wfx_layout_build($args) { global $wfx; $wfx->layout_build($args); } endif;

/**
* @since 0.913
* @updated 0.92
* Creates a clean excerpt
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
* @since 0.913
* @updated 0.913
* Creates a very configurable twitter feed
*/
if ( !function_exists( 'wfx_twitter_feed' ) ) : function wfx_twitter_feed($args) { global $wfx; $wfx->twitter_feed($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Gets a single post/page/whatever
*/
if ( !function_exists( 'wfx_get_single_content' ) ) : function wfx_get_single_content($args) { global $wfx; $wfx->get_single_content($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates an image link to the post displayed
*/
if ( !function_exists( 'wfx_perma_img' ) ) : function wfx_perma_img($args) { global $wfx; $wfx->perma_img($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates a configurable admin menu with edit link etc
*/
if ( !function_exists( 'wfx_edit_meta' ) ) : function wfx_edit_meta($args) { global $wfx; $wfx->edit_meta($args); } endif;

/**
* @since 0.913
* @updated 0.913
* A simple login/logout link
*/
if ( !function_exists( 'wfx_login_logout' ) ) : function wfx_login_logout($args) { global $wfx; $wfx->login_logout($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Used when you really, really have to create a static coded navigation - CSS class added if active page
*/
if ( !function_exists( 'wfx_static_highlight' ) ) : function wfx_static_highlight($args) { global $wfx; $wfx->static_highlight($args); } endif;

/**
* @since 0.913
* @updated 1.0
* Gets attachment(s) or featured images attached to posts in various formats
*/
if ( !function_exists( 'wfx_get_attachments' ) ) : function wfx_get_attachments($args) {

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
* @since 0.93
* @updated 0.93
* Creates 'page x of x' output for lists of results like category view and others
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



////  4  //////////// SOCIAL FUNCTIONS


/**
* @since 0.931
* @updated 0.931
* Displays Google Plus 1 button
*/
if ( !function_exists( 'wfx_social_google_plus_1' ) ) : function wfx_social_google_plus_1($args='') { global $wfx; $wfx->g_plus_1($args); } endif;


/**
* @since 0.931
* @updated 0.931
* Displays Facebook Like button
*/
if ( !function_exists( 'wfx_social_facebook_like' ) ) : function wfx_social_facebook_like($args='') { global $wfx; $wfx->fb_like($args); } endif;


/**
* @since 0.931
* @updated 0.931
* Displays Twitter share button
*/
if ( !function_exists( 'wfx_social_twitter_share' ) ) : function wfx_social_twitter_share($args='') { global $wfx; $wfx->twit_share($args); } endif;


/**
* @since 0.931
* @updated 0.931
* Builds social associated meta tags (Facebook ect)
*/
if ( !function_exists( 'wfx_social_meta' ) ) : function wfx_social_meta($args='') { global $wfx; $wfx->social_meta($args); } endif;


//  5  //////////// THEME CONFIGURATION


/**
* @since 0.913
* @updated 0.913
* IMPORTANT Sets up widgets
*/
if ( !function_exists( 'wfx_widgets' ) ) : function wfx_widgets($args) { global $wfx_theme; $wfx_theme->widgets($args); } endif;


/**
* @since 0.92
* @updated 0.92
* IMPORTANT Sets up background divs
*/
if ( !function_exists( 'wfx_background_divs' ) ) : function wfx_background_divs($args) { global $wfx_theme; $wfx_theme->background_divs($args); } endif;


/**
* @since 0.913
* @updated 0.913
* Setup IE6 PNG fix - yea, sometimes you still need it (pesky budgets!)
*/
if ( !function_exists( 'wfx_ie6_png' ) ) : function wfx_ie6_png($args) { global $wfx_theme; $wfx_theme->ie6_png($args); } endif;


//  6  //////////// SCRIPT SUPPORT


/**
* @since 0.92
* @updated 0.931
* Setup and insert JQuery how you want it
*/
if ( !function_exists( 'wfx_jquery' ) ) : function wfx_jquery($args='') { global $wfx_theme; $wfx_theme->jquery($args); } endif;


/**
* @since 0.92
* @updated 0.931
* Setup JQuery Cycle how you want it
*/
if ( !function_exists( 'wfx_js_cycle' ) ) : function wfx_js_cycle($args='') { global $wfx_theme; $wfx_theme->cycle($args); } endif;


//  7  //////////// ADMIN FUNCTIONS


/**
* @since 0.93
* @updated 0.93
* Control the display of Wonderflux admin menus
*/
if ( !function_exists( 'wfx_admin_menus' ) ) : function wfx_admin_menus() { global $wfx_admin; $wfx_admin->admin_menus(); } endif;


//  8  //////////// WONDERFLUX CORE


/**
* @since 0.93
* @updated 0.93
* Adds Wonderflux options to appearance menu (respcts WF_ADMIN_ACCESS)
*/
if ( !function_exists( 'wfx_admin_bar_links' ) ) : function wfx_admin_bar_links() { global $wfx_wp_helper; $wfx_wp_helper->admin_bar_links(); } endif;


// For when Wonderflux gets activated directly

/**
* @since 0.902
* @updated 0.913
* Configures basic layout
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


//  9  //////////// Add actions to hooks

// Special child theme function
// Create the function my_wfx_layout() in your child theme functions file
// Use this to configure all your Wonderflux child theme layout functions like wfx_background_divs()
if ( function_exists( 'my_wfx_layout' ) ) { add_action('get_header', 'my_wfx_layout', 1); }
// Use this to configure all your Wonderflux child theme script functions like wfx_jquery()
if ( function_exists( 'my_wfx_scripts' ) ) { if ( !is_admin() ) : add_action('init', 'my_wfx_scripts', 1); endif; }

// Core Wonderflux functionality

// Allow full removal of the core CSS in one swoop
if (WF_THEME_FRAMEWORK_REPLACE == false) {
	add_action('wf_head_meta', 'wfx_display_head_css_structure', 2);
	add_action('wf_head_meta', 'wfx_display_head_css_columns', 2);
	add_action('wf_head_meta', 'wfx_display_head_css_ie', 2);
} elseif (WF_THEME_FRAMEWORK_REPLACE == true) {
	add_action('wf_head_meta', 'wfx_head_css_replace', 2);
}

// Core Wonderflux theme activation
if (get_current_theme() == 'Wonderflux Framework') { add_action('wp_loaded', 'wfx_core_default_widgets'); }

add_action('init', 'wfx_config_language');
add_action('get_header', 'wfx_layout_build', 1); // IMPORTANT - Inserts layout divs
add_action('wf_output_start', 'wfx_display_head_open', 1);
add_action('get_header', 'wfx_social_meta');
add_action('wf_head_meta', 'wfx_display_head_char_set', 1);
add_action('wf_head_meta', 'wfx_display_head_title', 3);
add_action('wf_head_meta', 'wfx_display_head_css_theme', 3);
add_action('wf_head_meta', 'wfx_display_css_info');
add_action('admin_bar_menu', 'wfx_admin_bar_links', 100);
add_action('wffooter_after_content', 'wfx_display_credit', 1);
add_action('wf_footer', 'wfx_debug_performance', 12);
add_action('wf_footer', 'wfx_display_code_credit', 3);
add_action('auth_redirect', 'wfx_admin_menus');
?>