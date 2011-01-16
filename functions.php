<?php
/**
 * Core Wonderflux functions
 *
 * DON'T HACK ME!! You have lots of ways to manipulate this from your child theme
 * 1) Use remove_action('wf_hook_name','wf_function_name',postitionnumber); in your child theme functions file
 * 2) Create a function with the same name as a core display function in your child theme - that will override the one below
 * 3) Add a filter to a display function - there are lots you can use in your child themes!
 *
 * @package Wonderflux
 */

//////// ADMIN FUNCTIONS ////////

// Start your engine
require(TEMPLATEPATH.'/wf-includes/wf-engine.php');
$wflux_core = new wflux_core;

// Only build admin stuff if the user is an administrator level user

if ($wflux_core->wf_userrole('var') == 'administrator') {

	// Include admin functions
	require("wf-includes/wf-admin-functions.php");

	// Build admin menus
	$wflux_admin_doit = new wflux_admin;
	add_action('admin_menu', array($wflux_admin_doit, 'wf_add_pages'));
	//Setup options
	add_action( 'admin_init', array($wflux_admin_doit, 'wf_register_settings'));
}

//////// DISPLAY FUNCTIONS ////////

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
	include(WF_INCLUDES_DIR . '/wf-display-functions.php');
}
endif;


/**
*
* @since 0.902
* @updated 0.902
*
* IMPORTANT! if Wonderflux activated directly this hooks into get_header on template display and includes the Wonderflux display functions
* Wonderflux is designed to be used with child themes, but people are still gonna activate it directly
* So lets make sure it works ok!
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
* @updated 0.902
*
* IMPORTANT! if Wonderflux activated directly this hooks into get_header on template display and includes the Wonderflux display functions
* Wonderflux is designed to be used with child themes, but people are still gonna activate it directly
* So lets make sure it works ok!
*
*/
function wf_display_default_widgets() {
	if (get_current_theme() == 'Wonderflux Framework') {

		//Include theme core so we can setup some widget areas
		include ''.WF_INCLUDES_DIR.'/wf-theme-core.php';

		//Setup widgets using Wonderflux - define AND insert on any hook quickly and easily, cool!
		wflux_theme_core::wf_widgets(
			array (

				/* All parameters - example NOT ACTIVE
				array (
						"name" => "Above Sidebar",
						"description" => "Drag widgets into here to include them above the sidebar on all pages.",
						"location" => "wfsidebar_before_all_content",
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
* IMPORTANT Builds the head of the document, including setting up wp_head after wf_head stuff has executed
*
*/
if ( ! function_exists( 'wf_display_head_title' ) ) :
function wf_display_head_title() {
    wflux_display::wf_head_title();
}
endif;


/**
*
* @since 0.71
* @updated 0.71
*
* IMPORTANT Builds the head of the document, including setting up wp_head after wf_head stuff has executed
* TODO: Split this up fully for all core Wonderflux css
*
*/
if ( ! function_exists( 'wf_display_head_css' ) ) :
function wf_display_head_css() {
    wflux_display::wf_head_css();
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
* IMPORTANT - Runs last on wf_head_meta to close head section
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
?>