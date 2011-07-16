<?php

load_template(TEMPLATEPATH . '/wf-config.php');
load_template(WF_INCLUDES_DIR . '/wf-version.php');
load_template(WF_INCLUDES_DIR . '/wf-helper-functions.php');

if (is_admin()) {
	load_template(WF_INCLUDES_DIR . '/wf-admin-functions.php');
}


//// ADMIN FUNCTIONS


/**
* @since 0.93
* @updated 0.93
* Sets up all Wonderflux admin functions
*/
function wflux_capacitor_admin() {
	global $wfx_admin;
	$wfx_admin = new wflux_admin_all;
}
add_action('auth_redirect','wflux_capacitor_admin', 1);


/**
* @since 0.93
* @updated 0.93
* Creates relevant Wonderflux admin functions
*/
class wflux_admin_all {

	static $wflux_admin_do;

	function __construct(){
		$this->wflux_admin_do = new wflux_admin;
	}

	function admin_menus(){ $this->wflux_admin_do->wf_admin_menus(); }

}


//// HELPER FUNCTIONS


/**
* @since 0.913
* @updated 0.92
* Sets up all Wonderflux helper functions
*/
function wflux_capacitor_helper() {
	global $wfx_helper;
	$wfx_helper = new wflux_helper_all;
}
add_action('init','wflux_capacitor_helper', 1);


/**
* @since 0.913
* @updated 0.93
* Creates all Wonderflux helper functions
*/
class wflux_helper_all {

	static $wflux_helper_do;

	function __construct(){
		$this->wflux_helper_do = new wflux_helper;
	}

	function info_location(){ return $this->wflux_helper_do->wf_info_location(); }
	function gt_part($args){ $this->wflux_helper_do->wf_get_template_part($args); }
	function user_role($args){ return $this->wflux_helper_do->wf_user_role($args); }
	function page_depth($args){ return $this->wflux_helper_do->wf_page_depth($args); }
	function custom_field($args){ return $this->wflux_helper_do->wf_custom_field($args); }
	function __Y(){ return $this->wflux_helper_do->wf__Y(); }
	function __N(){ return $this->wflux_helper_do->wf__N(); }

}


/**
* @since 0.92
* @updated 0.92
* Sets up all Wonderflux WordPress core helper functions
*/
function wflux_capacitor_wp_helper() {
	global $wfx_wp_helper;
	$wfx_wp_helper = new wflux_wp_helper_all;
}
add_action('init','wflux_capacitor_wp_helper', 1);


/**
* @since 0.92
* @updated 0.93
* Creates all Wonderflux WordPress core helper functions
*/
class wflux_wp_helper_all {

	static $wflux_helper_do;

	function __construct(){
		$this->wflux_helper_do = new wflux_wp_core;
	}

	function admin_bar_remove($args){ $this->wflux_helper_do->wf_admin_bar_remove($args); }
	function admin_bar_links(){ $this->wflux_helper_do->wf_admin_bar_links(); }

}


//// CHILD THEME SETUP


/**
* @since 0.913
* @updated 0.913
* Sets up all Wonderflux theme building functions
*/
function wflux_capacitor_theme() {

	//EXPERIMENTAL
	//wfcode_before_theme_config(); //WF code hook
	load_template(WF_INCLUDES_DIR . '/wf-theme-core.php');
	global $wfx_theme;
	$wfx_theme = new wflux_theme_all;
	//EXPERIMENTAL
	//wfcode_after_theme_config(); //WF code hook
}
add_action('init','wflux_capacitor_theme', 1);


/**
* @since 0.913
* @updated 0.92
* Creates all Wonderflux theme building functions
*/
class wflux_theme_all {

	static $wflux_theme_core_do;
	static $wflux_theme_ie_do;

	function __construct(){
		$this->wflux_theme_core_do = new wflux_theme_core;
		$this->wflux_theme_ie_do = new wflux_theme_ie;
	}

	// Main theme config
	function widgets($args){ $this->wflux_theme_core_do->wf_widgets($args); }
	function language_pack($args){ $this->wflux_theme_core_do->wf_language_pack($args); } //ALPHA DEVELOPMENT - NEEDS TESTING!
	function background_divs($args){ $this->wflux_theme_core_do->wf_background_divs($args); } //ALPHA DEVELOPMENT - NEEDS TESTING!

	// Javascript goodness
	function jquery($args){ $this->wflux_theme_core_do->wf_js_jquery($args); } //ALPHA DEVELOPMENT - NEEDS TESTING!
	function cycle($args){ $this->wflux_theme_core_do->wf_js_cycle($args); } //ALPHA DEVELOPMENT - NEEDS TESTING!

	// Internet Explorer (Pesky IE!)
	function ie6_png($args){ $this->wflux_theme_ie_do->wf_ie6_png($args); }

}


//// DISPLAY FUNCTIONS


/**
* @since 0.913
* @updated 0.913
* Sets up all Wonderflux display functions
*/
function wflux_capacitor_display() {

	load_template(WF_INCLUDES_DIR . '/wf-display-functions.php');
	load_template(WF_INCLUDES_DIR . '/wf-display-hooks.php');
	global $wfx;
	$wfx = new wflux_display_all;

}
add_action('get_header','wflux_capacitor_display', 1);


/**
* @since 0.913
* @updated 0.93
* Creates all Wonderflux display functions
*/
class wflux_display_all {

	static $wflux_display_code_do;
	static $wflux_display_css_do;
	static $wflux_display_do;
	static $wflux_display_ex_do;

	function __construct(){
		$this->wflux_display_code_do = new wflux_display_code;
		$this->wflux_display_css_do = new wflux_display_css;
		$this->wflux_display_do = new wflux_display;
		$this->wflux_display_ex_do = new wflux_display_extras;
	}

	// Code functions
	function head_top($args){ $this->wflux_display_code_do->wf_head_top($args); }
	function head_title($args){ $this->wflux_display_code_do->wf_head_title($args); }
	function head_css_structure($args){ $this->wflux_display_code_do->wf_head_css_structure($args); }
	function head_css_theme($args){ $this->wflux_display_code_do->wf_head_css_theme($args); }
	function head_css_columns($args){ $this->wflux_display_code_do->wf_head_css_columns($args); }
	function head_css_ie($args){ $this->wflux_display_code_do->wf_head_css_ie($args); }
	function head_css_replace($args){ $this->wflux_display_code_do->wf_head_css_replace($args); }
	function head_close($args){ $this->wflux_display_code_do->wf_head_close($args); }
	function debug_performance($args){ $this->wflux_display_code_do->wf_performance($args); }
	function code_credit($args){ $this->wflux_display_code_do->wf_code_credit($args); }
	function get_dimensions($args){ return $this->wflux_display_code_do->wf_get_dimensions($args); }

	// CSS layout functions
	function css($args){ $this->wflux_display_css_do->wf_css($args); }
	function css_info($args){ $this->wflux_display_css_do->wf_css_info($args); }
	function css_close($args){ $this->wflux_display_css_do->wf_css_close($args); }
	function layout_build($args){ $this->wflux_display_css_do->wf_layout_build($args); }


	// Display functions
	function get_sidebar($args){ $this->wflux_display_do->wf_get_sidebar($args); }
	function display_credit($args){ $this->wflux_display_do->wf_credit($args); }

	// Wonderflux direct activation functions
	function default_layout($args){ $this->wflux_display_code_do->wf_default_layout($args); }

	// EX Display functions - these are useful to theme designers
	function excerpt($args){ return $this->wflux_display_ex_do->wf_excerpt($args); } // TO TEST
	function twitter_feed($args){ $this->wflux_display_ex_do->wf_twitter_feed($args); } // TEST PASSED
	function perma_img($args){ $this->wflux_display_ex_do->wf_perma_img($args); } // TO TEST
	function edit_meta($args){ $this->wflux_display_ex_do->wf_edit_meta($args); } // NEEDS MORE TESTING?
	function get_single_content($args){ $this->wflux_display_ex_do->wf_get_single_content($args); } // TEST PASSED
	function login_logout($args){ $this->wflux_display_ex_do->wf_login_logout($args); } // TEST PASSED
	function static_highlight($args){ $this->wflux_display_ex_do->wf_static_highlight($args); }
	function get_attachments($args){ $this->wflux_display_ex_do->wf_get_attachments($args); }
	function page_counter($args){ return $this->wflux_display_ex_do->wf_page_counter($args); }

}
?>