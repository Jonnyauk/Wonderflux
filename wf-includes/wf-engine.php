<?php

//// CORE FUNCTIONALITY

// Load just what we need - not everything!
load_template(TEMPLATEPATH . '/wf-config.php');
load_template(WF_INCLUDES_DIR . '/wf-version.php');
load_template(WF_INCLUDES_DIR . '/wf-helper-functions.php');


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
	$wfx_theme = new wflux_theme();
	//EXPERIMENTAL
	//wfcode_after_theme_config(); //WF code hook
}
// Because this interacts with WordPress functionality, load on init to make sure everything functions correctly!
add_action('init','wflux_capacitor_theme', 1);


//// CORE


/**
* @since 0.913
* @updated 0.913
* Sets up all Wonderflux display functions
*/
class wflux_theme {

	static $wflux_theme_core;

	function __construct(){
		$this->wflux_theme_core = new wflux_theme_core;
	}

	function widgets($args){ $this->wflux_theme_core->wf_widgets($args); }
	function language_pack($args){ $this->wflux_theme_core->wf_language_pack($args); } //ALPHA DEVELOPMENT - NEEDS TESTING!

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
	// This is much better than creating instances of the core display class all over the place
	// It also means that DB options can be read once and used throughout class, which is MUCH more efficient!
	// TODO: Debug - Look at alternative options and test
	global $wfx;
	$wfx = new wflux_functions();

}

// Just do the display stuff when we need it
add_action('get_header','wflux_capacitor_display', 1);

/**
* @since 0.913
* @updated 0.913
* Sets up all Wonderflux display functions
*/
class wflux_functions {

	static $wflux_display_code;
	static $wflux_display_css;
	static $wflux_display;
	static $wflux_display_ex;

	function __construct(){
		$this->wflux_display_code = new wflux_display_code;
		$this->wflux_display_css = new wflux_display_css;
		$this->wflux_display = new wflux_display;
		$this->wflux_display_ex = new wflux_display_ex;
	}

	// Code functions
	function head_top($args){ $this->wflux_display_code->wf_head_top($args); }
	function head_title($args){ $this->wflux_display_code->wf_head_title($args); }
	function head_css_structure($args){ $this->wflux_display_code->wf_head_css_structure($args); }
	function head_css_typography($args){ $this->wflux_display_code->wf_head_css_typography($args); }
	function head_css_theme($args){ $this->wflux_display_code->wf_head_css_theme($args); }
	function head_css_columns($args){ $this->wflux_display_code->wf_head_css_columns($args); }
	function head_css_ie($args){ $this->wflux_display_code->wf_head_css_ie($args); }
	function head_close($args){ $this->wflux_display_code->wf_head_close($args); }
	function debug_performance($args){ $this->wflux_display_code->wf_performance($args); }
	function code_credit($args){ $this->wflux_display_code->wf_code_credit($args); }

	// Display functions
	function display_credit($args){ $this->wflux_display->wf_credit($args); }

	// CSS layout functions
	function css($args){ $this->wflux_display_css->wf_css($args); }
	function css_info($args){ $this->wflux_display_css->wf_css_info($args); }
	function css_close($args){ $this->wflux_display_css->wf_css_close($args); }

	// Wonderflux direct activation functions
	function default_layout($args){ $this->wflux_display_code->wf_default_layout($args); }

	// EX Display functions - these are useful to theme designers
	function excerpt($args){ $this->wflux_display_ex->wf_excerpt($args); } // TO TEST
	function twitter_feed($args){ $this->wflux_display_ex->wf_twitter_feed($args); } // TEST PASSED
	function perma_img($args){ $this->wflux_display_ex->wf_perma_img($args); } // TO TEST
	function edit_meta($args){ $this->wflux_display_ex->wf_edit_meta($args); } // NEEDS MORE TESTING?
	function get_single_content($args){ $this->wflux_display_ex->wf_get_single_content($args); } // TEST PASSED
	function login_logout($args){ $this->wflux_display_ex->wf_login_logout($args); } // TEST PASSED
	function static_highlight($args){ $this->wflux_display_ex->wf_static_highlight($args); }
	function get_attachments($args){ $this->wflux_display_ex->wf_get_attachments($args); }

	//NEED TO DO IE DISPLAY EXTRAS CLASS TOO!!

}
?>