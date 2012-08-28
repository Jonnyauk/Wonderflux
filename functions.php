<?php
/**
 * Wonderflux is a free open source, GPLv2 licensed theme framework for professional WordPress theme design.
 *
 * Information and license:    README.txt
 *
 * Guide and documentation:    http://wonderflux.com/guide
 * Introduction to Wonderflux: http://wonderflux.com/guide/doc/introduction
 * Wonderflux child themes:    http://wonderflux.com/guide/doc/child-theme-files
 *
 * Bugs/improvements/feedback: http://code.google.com/p/wonderflux-framework/issues/list
 * Official release downloads: http://code.google.com/p/wonderflux-framework/downloads
 * Development code:           http://code.google.com/p/wonderflux-framework/source/checkout
 *
 * INDEX OF THIS FILE
 *
 * 1 - Add actions to hooks
 * 2 - Helper functions
 * 3 - Display functions
 * 4 - Theme display functions
 * 5 - Social functions
 * 6 - Theme configuration functions
 * 7 - Script support functions
 * 8 - Admin functions
 * 9 - Wonderflux Core
 *
 * DON'T HACK ME!! You should NOT modify the Wonderflux theme framework to avoid issues with updates in the future.
 * It's designed to offer lots of flexibility to manipulate from your child theme, like:
 *
 * 1) Create a function with the same name as a Wonderflux display function below in your child theme.
 *    This will override the ones in this file and be used instead.
 *
 * 2) Remove a core Wonderflux action in your child theme functions file with the code:
 *    remove_action('wf_hook_name','wf_function_name', $priority);
 *
 * 3) Add a filter - http://wonderflux.com/guide/filter/
 *
 * 4) Use over 120 location-aware hooks http://wonderflux.com/guide/hook/
 *
 * If you still feel the need to hack the Wonderflux core code, why not submit a patch or suggestion?
 * http://code.google.com/p/wonderflux-framework/issues/list
 *
 * DEVELOPERS AND TESTERS
 * All development is tracked within the Google Code SVN system.
 * Anyone has full SVN checkout access to the latest non-released development code.
 * Visit http://code.google.com/p/wonderflux-framework/source/checkout for access information.
 * The trunk version is for contributions, testing and development - NOT for live sites (unless you are brave!)
 *
 * THANKS FOR USING WONDERFLUX!
 * Created something cool? Let us know - we can't wait to see what you create!
 * Follow us on Twitter @Wonderflux for updates and news.
 *
 * @package Wonderflux
 *
 */

// Start your engine
load_template(get_template_directory() . '/wf-includes/wf-engine.php');

//  1  //////////// Add actions to hooks and create Wonderflux

// IMPORTANT - Use remove_action in your child theme to control any of this!

//// 1.1 // Early setup (before init)

add_action('after_setup_theme', 'wfx_core_feeds', 3);

//// 1.2 // Special child theme functions

// Create the functions my_wfx_layout() and my_wfx_scripts() in your child theme functions file

// Use this to configure all your Wonderflux child theme layout functions like wfx_background_divs()
if ( function_exists( 'my_wfx_layout' ) ) { add_action('get_header', 'my_wfx_layout', 1); }
// Use this to configure all your Wonderflux child theme script functions like wfx_jquery()
if ( function_exists( 'my_wfx_scripts' ) ) { if ( !is_admin() ) : add_action('init', 'my_wfx_scripts', 1); endif; }

//// 1.3 // Columns functionality

// Allow full removal of the core CSS in one swoop
if (WF_THEME_FRAMEWORK_REPLACE == false) {
	add_action('wf_head_meta', 'wfx_display_head_css_structure', 2);
	add_action('wf_head_meta', 'wfx_display_head_css_columns', 2);
	add_action('wf_head_meta', 'wfx_display_head_css_ie', 2);
} elseif (WF_THEME_FRAMEWORK_REPLACE == true) {
	add_action('wf_head_meta', 'wfx_head_css_replace', 2);
}

//// 1.4 // If Wonderflux activated directly with no child theme

// Backpat - depreciated function get_current_theme() in WordPress 3.4
if ( WF_WORDPRESS_VERSION < 3.4 ) {
	if (get_current_theme() == 'Wonderflux Framework') add_action('wp_loaded', 'wfx_core_default_widgets');
} else {
	if (wp_get_theme()->Name == 'Wonderflux Framework') add_action('wp_loaded', 'wfx_core_default_widgets');
}

//// 1.5 // Wonderflux core functionality

add_action('init', 'wfx_config_language');
add_action( 'wp_enqueue_scripts', 'wfx_core_comment_js', 2 );
add_action('get_header', 'wfx_content_width_embed', 2); // IMPORTANT Sets WordPress $content_width global for oEmbed media
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


////  2  //////////// HELPER FUNCTIONS


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
* @updated 1.0RC3
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


/**
* @since 1.0RC4
* @updated 1.0RC4
* Returns array of common layout tags to be used with kses or similar
*/
if ( !function_exists( 'wfx_allowed_tags' ) ) : function wfx_allowed_tags($args) {
	 global $wfx_data_manage; return $wfx_data_manage->allowed_tags($args);
} endif;


/**
* @since 1.0RC4
* @updated 1.0RC4
* Returns $input with whitespace stripped out
*/
if ( !function_exists( 'wfx_strip_whitespace' ) ) : function wfx_strip_whitespace($input,$echo='N') {
	global $wfx_data_manage; return $wfx_data_manage->strip_whitespace($input);
} endif;


////  3  //////////// DISPLAY FUNCTIONS


/**
* @since 1.0RC4
* @updated 1.0RC4
* Inserts WordPress 'automatic-feed-links' )
*/
if ( !function_exists( 'wfx_core_feeds' ) ) : function wfx_core_feeds() { global $wfx_theme_support; $wfx_theme_support->core_feeds(); } endif;


/**
* @since 1.0RC4
* @updated 1.0RC4
* Core WordPress threaded comment reply Javascript
*/
if ( !function_exists( 'wfx_core_comment_js' ) ) : function wfx_core_comment_js() { global $wfx_theme; $wfx_theme->core_comment_js(); } endif;



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
* @updated 1.0
* IMPORTANT Builds the title of the document
*/
if ( !function_exists( 'wfx_display_head_title' ) ) : function wfx_display_head_title($args) { global $wfx; $wfx->head_title($args); } endif;

/**
* @since 0.72
* @updated 1.0RC4
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
* @since 1.0RC3
* @updated 1.0RC3
* A more flexible post class function - especially compared to get_post_class()
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

/**
* @since 1.0RC4
* @updated 1.0RC4
* IMPORTANT Sets WordPress $content_width global for oEmbed media
*/
if ( !function_exists( 'wfx_content_width_embed' ) ) : function wfx_content_width_embed() { global $wfx; $wfx->content_width_embed(); } endif;



////  4  //////////// THEME DISPLAY


/**
* @since 0.93
* @updated 0.93
* Gets the sidebar
*/
if ( !function_exists( 'wfx_get_sidebar' ) ) : function wfx_get_sidebar($args) { global $wfx; $wfx->get_sidebar($args); } endif;

/**
* @since 0.913
* @updated 1.0RC4
* Creates dynamic CSS grid class definition
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
* @updated 1.0RC4
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
* Creates 'page x of x' output for lists of posts like archive or query views
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
* @since 1.0RC3
* @updated 1.0RC4
* Include a file and cache generated output for desired time
* EXPERIMENTAL - Dont use on live sites!
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


////  5  //////////// SOCIAL FUNCTIONS


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
* @since 1.0rc2
* @updated 1.0rc2
* Displays LinkedIn share button
*/
if ( !function_exists( 'wfx_social_linkedin_share' ) ) : function wfx_social_linkedin_share($args='') { global $wfx; $wfx->linkedin_share($args); } endif;


/**
* @since 0.931
* @updated 0.931
* Builds social associated meta tags (Facebook ect)
*/
if ( !function_exists( 'wfx_social_meta' ) ) : function wfx_social_meta($args='') { global $wfx; $wfx->social_meta($args); } endif;


//  6  //////////// THEME CONFIGURATION


/**
* @since 0.913
* @updated 0.931
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


//  7  //////////// SCRIPT SUPPORT


/**
* @since 0.92
* @updated 1.0RC2
* Setup and insert JQuery how you want it
*/
if ( !function_exists( 'wfx_jquery' ) ) : function wfx_jquery($args='') { global $wfx_theme; $wfx_theme->jquery($args); } endif;


/**
* @since 0.92
* @updated 0.931
* Setup JQuery Cycle how you want it
*/
if ( !function_exists( 'wfx_js_cycle' ) ) : function wfx_js_cycle($args='') { global $wfx_theme; $wfx_theme->cycle($args); } endif;


//  8  //////////// ADMIN FUNCTIONS


/**
* @since 0.93
* @updated 0.93
* Control the display of Wonderflux admin menus
*/
if ( !function_exists( 'wfx_admin_menus' ) ) : function wfx_admin_menus() { global $wfx_admin; $wfx_admin->admin_menus(); } endif;


/**
* @since 0.93
* @updated 0.93
* Adds Wonderflux options to appearance menu (respcts WF_ADMIN_ACCESS)
*/
if ( !function_exists( 'wfx_admin_bar_links' ) ) : function wfx_admin_bar_links() { global $wfx_wp_helper; $wfx_wp_helper->admin_bar_links(); } endif;


//  9  //////////// WONDERFLUX CORE


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
?>