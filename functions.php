<?php
/**
 * Core Wonderflux theme framework functions
 * For more information, including license please view README.txt file or visit http://www.wonderflux.com
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
 * 4) Use over 100 location-aware hooks (documentation to come)
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
 */

// ADMIN FUNCTIONS //

// Start your engine
load_template(TEMPLATEPATH . '/wf-includes/wf-engine.php');
$wflux_core = new wflux_core;

// Only build admin stuff if the user is an administrator level user
if ($wflux_core->wf_userrole('var') == 'administrator') {

	// Include admin functions
	load_template(WF_INCLUDES_DIR . '/wf-admin-functions.php');

	// Build admin menus
	$wflux_admin_doit = new wflux_admin;
	add_action('admin_menu', array($wflux_admin_doit, 'wf_add_pages'));
	//Setup options
	add_action( 'admin_init', array($wflux_admin_doit, 'wf_register_settings'));
}


// Core framework functions that you can over-ride //
add_action('get_header', 'wf_display_functions', 1); // !!IMPORTANT!! Dont remove this one - it includes stuff we need for templates
add_action('wf_head_meta', 'wf_display_head_top', 1);
add_action('wf_head_meta', 'wf_display_head_title', 3);
add_action('wf_head_meta', 'wf_display_head_css_structure', 3);
add_action('wf_head_meta', 'wf_display_head_css_typography', 3);
add_action('wf_head_meta', 'wf_display_head_css_columns', 3);
add_action('wf_head_meta', 'wf_display_head_css_ie', 3);
add_action('wf_head_meta', 'wf_display_head_css_theme', 3);

add_action('wf_head_meta', 'wf_display_css_info');
add_action('wf_head_meta', 'wf_display_head_close', 12); //IMPORTANT - Set priority to 12 on this action to ensure it runs after any other functions added to wf_head_meta

add_action('wffooter_after_content', 'wf_display_credit_footer', 1);
add_action('wf_footer', 'wf_display_debug_footer', 1);
add_action('wf_footer', 'wf_display_credit_footer_code', 1);

// Detect if Wonderflux activated directly and add basic functionality
add_action('get_header', 'wf_display_default_layout', 1); // !!IMPORTANT!! Dont remove this one - does stuff if Wonderflux activated directly
add_action('init', 'wf_display_default_widgets'); // !!IMPORTANT!! Dont remove this one - does stuff if Wonderflux activated directly


/**
*
* @since 0.71
* @updated 0.71
*
* IMPORTANT! Hooks into get_header on template display and includes the Wonderflux display functions.
*
*/
if ( ! function_exists( 'wf_display_functions' ) ) :
	function wf_display_functions() {
		load_template(WF_INCLUDES_DIR . '/wf-display-functions.php');
	}
endif;


/**
*
* @since 0.71
* @updated 0.71
*
* IMPORTANT Builds the head of the document, including setting up wp_head after wf_head stuff has executed
*
*/
if ( ! function_exists( 'wf_display_head_top' ) ) :
function wf_display_head_top() {
	wflux_display::wf_head_top('doctype=strict');
}
endif;


/**
*
* @since 0.71
* @updated 0.71
*
* IMPORTANT Builds the title of the document
*
*/
if ( ! function_exists( 'wf_display_head_title' ) ) :
function wf_display_head_title() {
    wflux_display::wf_head_title();
}
endif;


/**
*
* @since 0.72
* @updated 0.72
*
* Inserts the core framework structure CSS
*
*/
if ( ! function_exists( 'wf_display_head_css_structure' ) ) :
function wf_display_head_css_structure() {
    wflux_display::wf_head_css_structure();
}
endif;


/**
*
* @since 0.72
* @updated 0.72
*
* Inserts the core typography CSS
*
*/
if ( ! function_exists( 'wf_display_head_css_typography' ) ) :
function wf_display_head_css_typography() {
    wflux_display::wf_head_css_typography();
}
endif;


/**
*
* @since 0.72
* @updated 0.72
*
* Inserts the core theme CSS
*
*/
if ( ! function_exists( 'wf_display_head_css_theme' ) ) :
function wf_display_head_css_theme() {
    wflux_display::wf_head_css_theme();
}
endif;


/**
*
* @since 0.72
* @updated 0.72
*
* Inserts the dynamic column CSS builder
*
*/
if ( ! function_exists( 'wf_display_head_css_columns' ) ) :
function wf_display_head_css_columns() {
    wflux_display::wf_head_css_columns();
}
endif;


/**
*
* @since 0.72
* @updated 0.72
*
* Inserts the core IE CSS
*
*/
if ( ! function_exists( 'wf_display_head_css_ie' ) ) :
function wf_display_head_css_ie() {
    wflux_display::wf_head_css_ie();
}
endif;


/**
*
* @since 0.51
* @updated 0.881
*
* Displays CSS info for designers as a comment in the head source code
*
*/
if ( ! function_exists( 'wf_display_css_info' ) ) :
function wf_display_css_info() {
	wflux_display::wf_css_info('');
}
endif;


/**
*
* @since 0.71
* @updated 0.71
*
* VERY IMPORTANT - Runs last on wf_head_meta to close head section
* IMPORTANT - Inserts standard WordPress wp_head()
*
*/
if ( ! function_exists( 'wf_display_head_close' ) ) :
function wf_display_head_close() {
	wp_head();
	wflux_display::wf_head_close();
}
endif;


//// FOOTER FUNCTIONS ////


/**
*
* @since 0.71
* @updated 0.72
*
* Displays Wonderflux credit in footer of site
*
*/
if ( ! function_exists( 'wf_display_credit_footer' ) ) :
function wf_display_credit_footer() {
	wflux_display::wf_credit_footer();
}
endif;


/**
*
* @since 0.72
* @updated 0.72
*
* Displays Wonderflux debug in footer of site
*
*/
if ( ! function_exists( 'wf_display_debug_footer' ) ) :
function wf_display_debug_footer() {
	wflux_display::wf_debug_footer();
}
endif;


/**
*
* @since 0.71
* @updated 0.71
*
* Displays Wonderflux credit in source code of site
*
*/
if ( ! function_exists( 'wf_display_credit_footer_code' ) ) :
function wf_display_credit_footer_code() {
	wflux_display::wf_credit_footer_code();
}
endif;


//// WONDERFLUX CORE DISPLAY ////
// Wonderflux is designed to be used with child themes, but people are still gonna activate it directly.
// If Wonderflux is activated, lets make sure that we get some Wonderflux action!


/**
*
* @since 0.902
* @updated 0.902
*
* If Wonderflux activated, hooks into get_header on template display and includes the core Wonderflux display functions
* TODO: This generates some interesting errors with the theme checker plugin, TEST
*
*/
function wf_display_default_layout() {
	if (get_current_theme() == 'Wonderflux Framework') {

		//Insert some basic layout CSS styles using hooks
		wflux_display::wf_display_default_layout();

	}
}


/**
*
* @since 0.902
* @updated 0.912
*
* Runs wf_widgets just like any good Wonderflux theme should - define AND insert on any hook quickly and easily.
*
*/
function wf_display_default_widgets() {
	if (get_current_theme() == 'Wonderflux Framework') {

		wflux_theme_core::wf_widgets(
			array (

				/* All parameters - example NOT ACTIVE
				array (
						"name" => "Above Sidebar",
						"description" => "Drag widgets into here to include them above the sidebar on all pages.",
						"location" => "wfsidebar_before_all",
						"container" => "div",
						"containerclass" => "custom-widget-block",
						"containerid" => "widget1",
						"titlestyle" => "h4",
						"titleclass" => "custom-widget-title",
						"titleid" => "custom-widget-title",
						"before" => "",
						"after" => ""
				),
				*/

				/* Simple example */
				array (
						"name" => "Above sidebar",
						"description" => "Drag widgets into here to include them above the sidebar on your site.",
						"location" => "wfsidebar_before_all",
						"titleclass" => "primary-widget-title"
				),

				/* Even simpler example! */
				array (
						"location" => "wfsidebar_after_all",
						"name" => "Below sidebar"
				)

			)
		);

	}
}
?>