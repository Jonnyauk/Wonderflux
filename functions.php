<?php


//add_action('admin_bar_menu', 'theme_options_link', 1000);\\\



/**
 * Core Wonderflux theme framework functions
 * For more information, including license please view README.txt file or visit http://www.wonderflux.com
 *
 * 1 - Helper functions
 * 2 - Display functions
 * 3 - Theme display functions
 * 4 - Theme configuration functions
 * 5 - Script support functions
 * 6 - Admin functions
 * 7 - Wonderflux Core
 * 8 - Add actions to hooks
 *
 * DON'T HACK ME!! You should not modify the Wonderflux theme framework to avoid issues with updates in the future
 * You have lots of ways to manipulate this from your child theme! http://codex.wordpress.org/Child_Themes
 *
 * 1) Create a function with the same name as a core display function in your child theme. This will override the ones in this file
 *
 * 2) Remove a core Wonderflux action in your child theme functions file with the code:
 *    remove_action('wf_hook_name','wf_function_name',postitionnumber);
 *
 * 3) Add a filter a display function (documentation to come)
 *
 * 4) Use over 120 location-aware hooks (documentation to come)
 *
 * If you still feel the need to hack the Wonderflux core code, why not submit a patch or suggestion?
 *
 * Get involved at http://wonderflux-framework.googlecode.com - full open source SVN project
 * THIS IS THE ONLY PLACE to download the official distribution code (during beta) and is where all issues and updates are tracked
 * Developers also have full SVN access to trunk where the latest non-released development version is held (NOT advised for live sites!)
 *
 * If thats too techie (SVN is not much fun!), submit a suggestion at http://wonderflux.com
 *
 * Thanks for trying Wonderflux, WordPress is a world-class publishing platform,
 * lets make a world-class theme framework for everyone to use, for free, for ever!
 *
 * Follow us on Twitter @Wonderflux for updates and news
 *
 * @package Wonderflux
 *
 */

// Start your engine
load_template(TEMPLATEPATH . '/wf-includes/wf-engine.php');


////  1  //////////// HELPER FUNCTIONS


/**
* @since 0.913
* @updated 0.913
* IMPORTANT - Gets type of view
*/
if ( !function_exists( 'wfx_info_location' ) ) : function wfx_info_location() { global $wfx_helper; return $wfx_helper->info_location(); } endif;

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
* @since 0.71
* @updated 0.913
* IMPORTANT Builds the head of the document
*/
if ( !function_exists( 'wfx_display_head_top' ) ) : function wfx_display_head_top($args) { global $wfx; $wfx->head_top($args); } endif;

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
* @since 0.71
* @updated 0.913
* VERY IMPORTANT - Runs last on wf_head_meta to close head section, inserts core WordPress wp_head() and WordPress dynamic body class (phyew!)
*/
if ( !function_exists( 'wfx_display_head_close' ) ) : function wfx_display_head_close($args) { global $wfx; $wfx->head_close($args); } endif;

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
* @updated 0.913
* Gets attachments of current post - use within loop
*/
if ( !function_exists( 'wfx_get_attachments' ) ) : function wfx_get_attachments($args) { global $wfx; $wfx->get_attachments($args); } endif;

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


//  4  //////////// THEME CONFIGURATION


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


//  5  //////////// SCRIPT SUPPORT


/**
* @since 0.92
* @updated 0.92
* Setup JQuery how you want it
*/
if ( !function_exists( 'wfx_jquery' ) ) : function wfx_jquery($args) { global $wfx_theme; $wfx_theme->jquery($args); } endif;


/**
* @since 0.92
* @updated 0.92
* Setup JQuery Cycle how you want it
*/
if ( !function_exists( 'wfx_js_cycle' ) ) : function wfx_js_cycle($args) { global $wfx_theme; $wfx_theme->cycle($args); } endif;


//  6  //////////// ADMIN FUNCTIONS


/**
* @since 0.93
* @updated 0.93
* Control the display of Wonderflux admin menus
*/
if ( !function_exists( 'wfx_admin_menus' ) ) : function wfx_admin_menus() { global $wfx_admin; $wfx_admin->admin_menus(); } endif;


//  7  //////////// WONDERFLUX CORE


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


//  8  //////////// Add actions to hooks


// Special child theme function
// Create the function my_wfx_layout() in your child theme functions file
// Use this to use and configure Wonderflux layout functions like wfx_background_divs()
if ( function_exists( 'my_wfx_layout' ) ) { add_action('get_header', 'my_wfx_layout', 1); }

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

add_action('init', 'wfx_config_language'); //Need to test if this is ok to load on init
add_action('get_header', 'wfx_layout_build', 1); // IMPORTANT - Inserts layout divs
add_action('wf_head_meta', 'wfx_display_head_top', 1);
add_action('wf_head_meta', 'wfx_display_head_title', 3);
add_action('wf_head_meta', 'wfx_display_head_css_theme', 3);
add_action('wf_head_meta', 'wfx_display_css_info');
add_action('wf_head_meta', 'wfx_display_head_close', 12); //IMPORTANT - Set priority to 12 on this action to ensure it runs after any other functions added to wf_head_meta
add_action('admin_bar_menu', 'wfx_admin_bar_links', 100);
add_action('wffooter_after_content', 'wfx_display_credit', 1);
add_action('wf_footer', 'wfx_debug_performance', 12);
add_action('wf_footer', 'wfx_display_code_credit', 3);
add_action('auth_redirect', 'wfx_admin_menus'); //Need to test if this is ok to load on this hook - looking for an early enough admin only hook
?>