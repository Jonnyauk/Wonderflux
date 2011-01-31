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

add_action('init', 'wfx_config_language'); //Need to test if this is ok to load on init
add_action('wf_head_meta', 'wfx_display_head_top', 1);
add_action('wf_head_meta', 'wfx_display_head_title', 3);
add_action('wf_head_meta', 'wfx_display_head_css_structure', 3);
add_action('wf_head_meta', 'wfx_display_head_css_typography', 3);
add_action('wf_head_meta', 'wfx_display_head_css_columns', 3);
add_action('wf_head_meta', 'wfx_display_head_css_ie', 3);
add_action('wf_head_meta', 'wfx_display_head_css_theme', 3);
add_action('wf_head_meta', 'wfx_display_css_info');
add_action('wf_head_meta', 'wfx_display_head_close', 12); //IMPORTANT - Set priority to 12 on this action to ensure it runs after any other functions added to wf_head_meta

add_action('wffooter_after_content', 'wf_display_credit', 1);
add_action('wf_footer', 'wfx_debug_performance', 12);
add_action('wf_footer', 'wfx_display_code_credit', 3);

// Detect if Wonderflux activated directly and add basic theme functionality
if (get_current_theme() == 'Wonderflux Framework') {
	add_action('get_header', 'wfx_core_default_layout', 1); // !!IMPORTANT!! Dont remove this one - does stuff if Wonderflux activated directly
	add_action('wp_loaded', 'wfx_core_default_widgets'); // !!IMPORTANT!! Dont remove this one - does stuff if Wonderflux activated directly
}


// These are functions you can over-ride by creating your own function with the same name in your child theme
// Many of these also have filters to manipulate the output rather than recreating a whole function


/**
* @since 0.913
* @updated 0.913
* IMPORTANT Sets up Wonderflux for translation
*/
if ( ! function_exists( 'wfx_config_language' ) ) : function wfx_config_language() { global $wfx_theme; $wfx_theme->language_pack(''); } endif;

/**
* @since 0.71
* @updated 0.913
* IMPORTANT Builds the head of the document, including setting up wp_head after wf_head stuff has executed
*/
if ( ! function_exists( 'wfx_display_head_top' ) ) : function wfx_display_head_top() { global $wfx; $wfx->head_top('doctype=strict'); } endif;

/**
* @since 0.71
* @updated 0.913
* IMPORTANT Builds the title of the document
*/
if ( ! function_exists( 'wfx_display_head_title' ) ) : function wfx_display_head_title() { global $wfx; $wfx->head_title(''); } endif;

/**
* @since 0.72
* @updated 0.913
* Inserts the core framework structure CSS
*/
if ( ! function_exists( 'wfx_display_head_css_structure' ) ) : function wfx_display_head_css_structure() { global $wfx; $wfx->head_css_structure(''); } endif;

/**
* @since 0.72
* @updated 0.913
* Inserts the core typography CSS
*/
if ( ! function_exists( 'wfx_display_head_css_typography' ) ) : function wfx_display_head_css_typography() { global $wfx; $wfx->head_css_typography(''); } endif;

/**
* @since 0.72
* @updated 0.913
* Inserts the core theme CSS
*/
if ( ! function_exists( 'wfx_display_head_css_theme' ) ) : function wfx_display_head_css_theme() { global $wfx; $wfx->head_css_theme(''); } endif;

/**
* @since 0.72
* @updated 0.913
* Inserts the dynamic column CSS builder
*/
if ( ! function_exists( 'wfx_display_head_css_columns' ) ) : function wfx_display_head_css_columns() { global $wfx; $wfx->head_css_columns(''); } endif;

/**
* @since 0.72
* @updated 0.913
* Inserts the core IE CSS
*/
if ( ! function_exists( 'wfx_display_head_css_ie' ) ) : function wfx_display_head_css_ie() { global $wfx; $wfx->head_css_ie(''); } endif;

/**
* @since 0.71
* @updated 0.913
* VERY IMPORTANT - Runs last on wf_head_meta to close head section
* - Inserts standard WordPress wp_head()
*/
if ( ! function_exists( 'wfx_display_head_close' ) ) : function wfx_display_head_close() { global $wfx; $wfx->head_close(''); } endif;

/**
* @since 0.72
* @updated 0.913
* Displays debug in code comment
*/
if ( ! function_exists( 'wfx_debug_performance' ) ) : function wfx_debug_performance() { global $wfx; $wfx->debug_performance(''); } endif;

/**
* @since 0.71
* @updated 0.913
* Displays Wonderflux credit in source code of site
*/
if ( ! function_exists( 'wfx_display_code_credit' ) ) : function wfx_display_code_credit() { global $wfx; $wfx->code_credit(''); } endif;

/**
* @since 0.71
* @updated 0.913
* Displays a credit, show your support for Wonderflux!
*/
if ( ! function_exists( 'wf_display_credit' ) ) : function wf_display_credit() { global $wfx; $wfx->display_credit(''); } endif;


////////////// CSS FUNCTIONS


/**
* @since 0.51
* @updated 0.913
* Displays CSS info for designers as a comment in the head source code
*/
if ( ! function_exists( 'wfx_display_css_info' ) ) : function wfx_display_css_info() { global $wfx; $wfx->css_info(''); } endif;



////////////// EXTRA THEME FUNCTIONS


/**
* @since 0.913
* @updated 0.913
* Creates dynamic CSS grid class definition
*/
if ( ! function_exists( 'wfx_css' ) ) : function wfx_css($args) { global $wfx; $wfx->css($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Just echos </div> - nothing more nothing less!
*/
if ( ! function_exists( 'wfx_css_close' ) ) : function wfx_css_close($args) { global $wfx; $wfx->css_close($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates an excerpt
*/
if ( ! function_exists( 'wfx_excerpt' ) ) : function wfx_excerpt($args) { global $wfx; $wfx->excerpt($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates a very configurable twitter feed
*/
if ( ! function_exists( 'wfx_twitter_feed' ) ) : function wfx_twitter_feed($args) { global $wfx; $wfx->twitter_feed($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates a very configurable twitter feed
*/
if ( ! function_exists( 'wfx_get_single_content' ) ) : function wfx_get_single_content($args) { global $wfx; $wfx->get_single_content($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates an image link to the post displayed
*/
if ( ! function_exists( 'wfx_perma_img' ) ) : function wfx_perma_img($args) { global $wfx; $wfx->perma_img($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Creates a configurable admin menu with edit link etc
*/
if ( ! function_exists( 'wfx_edit_meta' ) ) : function wfx_edit_meta($args) { global $wfx; $wfx->edit_meta($args); } endif;

/**
* @since 0.913
* @updated 0.913
* A simple login/logout link
*/
if ( ! function_exists( 'wfx_login_logout' ) ) : function wfx_login_logout($args) { global $wfx; $wfx->login_logout($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Used when you really, really have to create a static coded navigation - CSS class added if active page
*/
if ( ! function_exists( 'wfx_static_highlight' ) ) : function wfx_static_highlight($args) { global $wfx; $wfx->static_highlight($args); } endif;

/**
* @since 0.913
* @updated 0.913
* Gets attachments of current post - use within loop
*/
if ( ! function_exists( 'wfx_get_attachments' ) ) : function wfx_get_attachments($args) { global $wfx; $wfx->get_attachments($args); } endif;


////////////// THEME CREATION FUNCTIONS


/**
* @since 0.913
* @updated 0.913
* IMPORTANT Sets up Wonderflux for translation
*/
if ( ! function_exists( 'wfx_widgets' ) ) : function wfx_widgets($args) { global $wfx_theme; $wfx_theme->widgets($args); } endif;


////////////// FUNCTIONS THAT DONT NEED TO BE OVERRIDDEN - FOR WHEN WONDERFLUX GETS ACTIVATED DIRECTLY


/**
* @since 0.902
* @updated 0.913
* Insert some basic layout divs
*/
function wfx_core_default_layout() { global $wfx; $wfx->default_layout(''); }


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