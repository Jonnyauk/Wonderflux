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

// Start your engine
load_template(TEMPLATEPATH . '/wf-includes/wf-engine.php');

add_action('init', 'wf_config_language'); //Need to test if this is ok to load on init
add_action('wf_head_meta', 'wf_display_head_top', 1);
add_action('wf_head_meta', 'wf_display_head_title', 3);
add_action('wf_head_meta', 'wf_display_head_css_structure', 3);
add_action('wf_head_meta', 'wf_display_head_css_typography', 3);
add_action('wf_head_meta', 'wf_display_head_css_columns', 3);
add_action('wf_head_meta', 'wf_display_head_css_ie', 3);
add_action('wf_head_meta', 'wf_display_head_css_theme', 3);
add_action('wf_head_meta', 'wf_display_css_info');
add_action('wf_head_meta', 'wf_display_head_close', 12); //IMPORTANT - Set priority to 12 on this action to ensure it runs after any other functions added to wf_head_meta

add_action('wffooter_after_content', 'wf_display_credit', 1);
add_action('wf_footer', 'wf_debug_performance', 1);
add_action('wf_footer', 'wf_display_code_credit', 1);

// Detect if Wonderflux activated directly and add basic theme functionality
if (get_current_theme() == 'Wonderflux Framework') {
	add_action('get_header', 'wf_default_layout', 1); // !!IMPORTANT!! Dont remove this one - does stuff if Wonderflux activated directly
	add_action('wp_loaded', 'wf_default_widgets'); // !!IMPORTANT!! Dont remove this one - does stuff if Wonderflux activated directly
}


// These are functions you can over-ride by creating your own function with the same name in your child theme
// Many of these also have filters to manipulate the output rather than recreating a whole function


/**
* @since 0.913
* @updated 0.913
* IMPORTANT Sets up Wonderflux for translation
*/
if ( ! function_exists( 'wf_config_language' ) ) :
	function wf_config_language() { global $wfx_theme; $wfx_theme->language_pack(''); }
endif;


/**
* @since 0.71
* @updated 0.913
* IMPORTANT Builds the head of the document, including setting up wp_head after wf_head stuff has executed
*/
if ( ! function_exists( 'wf_display_head_top' ) ) :
	function wf_display_head_top() { global $wfx; $wfx->head_top('doctype=strict'); }
endif;


/**
* @since 0.71
* @updated 0.913
* IMPORTANT Builds the title of the document
*/
if ( ! function_exists( 'wf_display_head_title' ) ) :
	function wf_display_head_title() { global $wfx; $wfx->head_title(''); }
endif;


/**
* @since 0.72
* @updated 0.913
* Inserts the core framework structure CSS
*/
if ( ! function_exists( 'wf_display_head_css_structure' ) ) :
	function wf_display_head_css_structure() { global $wfx; $wfx->head_css_structure(''); }
endif;


/**
* @since 0.72
* @updated 0.913
* Inserts the core typography CSS
*/
if ( ! function_exists( 'wf_display_head_css_typography' ) ) :
	function wf_display_head_css_typography() { global $wfx; $wfx->head_css_typography(''); }
endif;


/**
* @since 0.72
* @updated 0.913
* Inserts the core theme CSS
*/
if ( ! function_exists( 'wf_display_head_css_theme' ) ) :
	function wf_display_head_css_theme() { global $wfx; $wfx->head_css_theme(''); }
endif;


/**
* @since 0.72
* @updated 0.913
* Inserts the dynamic column CSS builder
*/
if ( ! function_exists( 'wf_display_head_css_columns' ) ) :
	function wf_display_head_css_columns() { global $wfx; $wfx->head_css_columns(''); }
endif;


/**
* @since 0.72
* @updated 0.913
* Inserts the core IE CSS
*/
if ( ! function_exists( 'wf_display_head_css_ie' ) ) :
	function wf_display_head_css_ie() { global $wfx; $wfx->head_css_ie(''); }
endif;


/**
* @since 0.71
* @updated 0.913
* VERY IMPORTANT - Runs last on wf_head_meta to close head section
* IMPORTANT - Inserts standard WordPress wp_head()
*/
if ( ! function_exists( 'wf_display_head_close' ) ) :
	function wf_display_head_close() { global $wfx; $wfx->head_close(''); }
endif;


/**
* @since 0.72
* @updated 0.913
* Displays debug in code comment
*/
if ( ! function_exists( 'wf_debug_performance' ) ) :
	function wf_debug_performance() { global $wfx; $wfx->debug_performance(''); }
endif;


/**
* @since 0.71
* @updated 0.913
* Displays Wonderflux credit in source code of site
*/
if ( ! function_exists( 'wf_display_code_credit' ) ) :
	function wf_display_code_credit() { global $wfx; $wfx->code_credit(''); }
endif;


/**
* @since 0.71
* @updated 0.913
* Displays a credit, useful in the footer
*/
if ( ! function_exists( 'wf_display_credit' ) ) :
	function wf_display_credit() { global $wfx; $wfx->display_credit(''); }
endif;


////////////// CSS FUNCTIONS


/**
* @since 0.51
* @updated 0.913
* Displays CSS info for designers as a comment in the head source code
*/
if ( ! function_exists( 'wf_display_css_info' ) ) :
	function wf_display_css_info() { global $wfx; $wfx->css_info(''); }
endif;


////////////// FUNCTIONS THAT DONT NEED TO BE OVERRIDDEN - FOR WHEN WONDERFLUX GETS ACTIVATED DIRECTLY


/**
*
* @since 0.902
* @updated 0.913
*
* Insert some basic layout divs
*
*/

function wf_default_layout() {
	global $wfx; $wfx->default_layout('');
}


/**
* @since 0.902
* @updated 0.913
* Configures basic layout
*/
function wf_default_widgets() {

	global $wfx_theme;
	// Runs wf_widgets just like any good Wonderflux theme should - define AND insert on any hook quickly and easily.
	$wfx_theme->widgets(
		array (
			array (
					"name" => "Above sidebar",
					"description" => "Drag widgets into here to include them above the sidebar on your site.",
					"location" => "wfsidebar_before_all",
					"titleclass" => "primary-widget-title"
			),
			array (
					"name" => "Below sidebar",
					"description" => "Drag widgets into here to include them below the sidebar on your site.",
					"location" => "wfsidebar_after_all",
					"titleclass" => "primary-widget-title"
			)

		)
	);

}


////////////// ADMIN FUNCTIONS

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
?>