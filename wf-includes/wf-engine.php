<?php
load_template(get_template_directory() . '/wf-config.php');
load_template(WF_INCLUDES_DIR . '/wf-version.php');
load_template(WF_INCLUDES_DIR . '/wf-helper-functions.php');
load_template(WF_INCLUDES_DIR . '/wf-data-management.php');
load_template(WF_INCLUDES_DIR . '/wf-theme-support.php');


//// FUNCTIONALITY REQUIRED BEFORE INIT HOOK


/**
 * @since 1.1
 * @updated 1.1
 * Theme support and early call functionality required before init hook
*/
function wflux_capacitor_early() {
	global $wfx_theme_support; $wfx_theme_support = new wflux_early_all;
}
add_action('after_setup_theme','wflux_capacitor_early', 2);


/**
 * @since 1.1
 * @updated 2.0
 * Theme support and early call functionality required before init hook
*/
class wflux_early_all {
	public $wflux_theme_support_do;
	function __construct(){ $this->wflux_theme_support_do = new wflux_theme_support; }
	function core_feed_links(){ return $this->wflux_theme_support_do->wf_core_feed_links(); }
	function core_title_tag(){ return $this->wflux_theme_support_do->wf_core_title_tag(); }
	function core_support_html5(){ return $this->wflux_theme_support_do->wf_core_support_html5(); }
}


//// ADMIN FUNCTIONS


if (is_admin()) {
	load_template(WF_INCLUDES_DIR . '/wf-admin-functions.php');

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

		public $wflux_admin_do;

		function __construct(){
			$this->wflux_admin_do = new wflux_admin;
		}

		function admin_menus(){ $this->wflux_admin_do->wf_admin_menus(); }

	}
}


//// ADMIN POST FUNCTIONS


if ( is_admin() ) {

	load_template( WF_INCLUDES_DIR . '/wf-admin-functions.php', true );

	/**
	* @since 2.0
	* @updated 2.0
	* Sets up all Wonderflux post admin functions
	*/
	function wflux_capacitor_admin_post() {

		global $wfx_admin_post;
		$wfx_admin_post = new wflux_admin_post_all;

	}
	add_action('admin_init','wflux_capacitor_admin_post', 1);

	/**
	* @since 2.0
	* @updated 2.6
	* Creates relevant Wonderflux post admin functions
	*/
	class wflux_admin_post_all {

		public $wflux_admin_post_do;

		function __construct(){ $this->wflux_admin_post_do = new wflux_admin_post; }
		function remove_page_templates($input){ return $this->wflux_admin_post_do->wf_remove_page_templates($input); }

	}

}


//// HELPER FUNCTIONS


/**
* @since 1.1
* @updated 1.1
* Sets up all Wonderflux core data handling/cleaning functions
*/
function wflux_capacitor_data_manage() {
	global $wfx_data_manage; $wfx_data_manage = new wflux_data_manage_all;
}
add_action('init','wflux_capacitor_data_manage', 1);


/**
* @since 1.1
* @updated 2.3
* Creates all Wonderflux core data handling/cleaning functions
*/
class wflux_data_manage_all {
	public $wflux_data_manage_do;
	function __construct(){ $this->wflux_data_manage_do = new wflux_data_manage; }
	function allowed_tags(){ return $this->wflux_data_manage_do->wf_allowed_tags(); }
	function allowed_simple_tags($input){ return $this->wflux_data_manage_do->wf_allowed_simple_tags($input); }
	function strip_whitespace($input){ return $this->wflux_data_manage_do->wf_strip_whitespace($input); }
	function valid_url($input){ return $this->wflux_data_manage_do->wf_valid_url($input); }
	function valid_hex_colour($input){ return $this->wflux_data_manage_do->wf_valid_hex_colour($input); }
	function starts_with($needle, $haystack){ return $this->wflux_data_manage_do->wf_starts_with($needle, $haystack); }
	function ends_with($needle, $haystack){ return $this->wflux_data_manage_do->wf_ends_with($needle, $haystack); }
}


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
* @updated 2.0
* Creates all Wonderflux helper functions
*/
class wflux_helper_all {

	public $wflux_helper_do;

	function __construct(){
		$this->wflux_helper_do = new wflux_helper;
	}

	function info_file($filetype){ return $this->wflux_helper_do->wf_info_file($filetype); }
	function info_location(){ return $this->wflux_helper_do->wf_info_location(); }
	function info_single(){ return $this->wflux_helper_do->wf_info_single(); }
	function gt_part($args){ $this->wflux_helper_do->wf_get_template_part($args); }
	function user_role($args){ return $this->wflux_helper_do->wf_user_role($args); }
	function page_depth($args){ return $this->wflux_helper_do->wf_page_depth($args); }
	function custom_field($args){ return $this->wflux_helper_do->wf_custom_field($args); }
	function __Y(){ return $this->wflux_helper_do->wf__Y(); }
	function __N(){ return $this->wflux_helper_do->wf__N(); }
	function debug($input,$label,$admin_only,$role,$id) { $this->wflux_helper_do->wf_debug($input,$label,$admin_only,$role,$id); }
	function show_hooks() { $this->wflux_helper_do->wf_show_hooks(); }

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

	public $wflux_helper_do;

	function __construct(){
		$this->wflux_helper_do = new wflux_wp_core;
	}

	function admin_bar_links(){ $this->wflux_helper_do->wf_admin_bar_links(); }
	function admin_bar_files_info(){ $this->wflux_helper_do->wf_admin_bar_files_info(); }

}


//// CHILD THEME SETUP


/**
* @since 0.913
* @updated 2.1
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
add_action( 'after_setup_theme', 'wflux_capacitor_theme', 1 );


/**
* @since 0.913
* @updated 0.92
* Creates all Wonderflux theme building functions
*/
class wflux_theme_all {

	public $wflux_theme_core_do;

	function __construct(){
		$this->wflux_theme_core_do = new wflux_theme_core;
	}

	// Main theme config
	function widgets($args){ $this->wflux_theme_core_do->wf_widgets($args); }
	function language_pack(){ $this->wflux_theme_core_do->wf_language_pack(); }
	function background_divs($args){ $this->wflux_theme_core_do->wf_background_divs($args); }

	// Javascript goodness
	function jquery($args){ $this->wflux_theme_core_do->wf_js_jquery($args); }
	function cycle($args){ $this->wflux_theme_core_do->wf_js_cycle($args); }

	// Core WordPress stuff
	function core_comment_js(){ $this->wflux_theme_core_do->wf_core_comment_js(); }

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
add_action('rest_api_init','wflux_capacitor_display', 1);

/**
* @since 0.913
* @updated 2.6
* Creates all Wonderflux display functions
*/
class wflux_display_all {

	public $wflux_display_code_do;
	public $wflux_display_css_do;
	public $wflux_display_do;
	public $wflux_display_ex_do;

	function __construct(){
		$this->wflux_display_code_do = new wflux_display_code;
		$this->wflux_display_css_do = new wflux_display_css;
		$this->wflux_display_do = new wflux_display;
		$this->wflux_display_ex_do = new wflux_display_extras;
	}

	// Code functions
	function head_open(){ $this->wflux_display_code_do->wf_head_open(); }
	function head_char_set($args){ $this->wflux_display_code_do->wf_head_char_set($args); }
	function head_viewport(){ $this->wflux_display_code_do->wf_head_viewport(); }
	function head_title($args){ $this->wflux_display_code_do->wf_head_title($args); }
	function head_css_structure($args){ $this->wflux_display_code_do->wf_head_css_structure($args); }
	function head_css_theme($args){ $this->wflux_display_code_do->wf_head_css_theme($args); }
	function head_css_columns(){ $this->wflux_display_code_do->wf_head_css_columns(); }
	function head_css_ie(){ $this->wflux_display_code_do->wf_head_css_ie(); }
	function head_css_replace($args){ $this->wflux_display_code_do->wf_head_css_replace($args); }
	function body_tag(){ $this->wflux_display_code_do->wf_body_tag(); }
	function body_js_detect(){ $this->wflux_display_code_do->wf_body_js_detect(); }
	function post_class($args){ return $this->wflux_display_code_do->wf_post_class($args); }
	function filter_post_class(){ $this->wflux_display_code_do->wf_filter_post_class(); }

	function debug_performance(){ $this->wflux_display_code_do->wf_performance(); }
	function code_credit(){ $this->wflux_display_code_do->wf_code_credit(); }
	function get_dimensions($args){ return $this->wflux_display_code_do->wf_get_dimensions($args); }
	function content_width_embed(){ $this->wflux_display_code_do->wf_content_width(); }

	// CSS layout functions
	function css($args){ return $this->wflux_display_css_do->wf_css($args); }
	function css_info($args){ $this->wflux_display_css_do->wf_css_info($args); }
	function test_pattern($args){ $this->wflux_display_css_do->wf_css_test_pattern($args); }
	function css_close(){ $this->wflux_display_css_do->wf_css_close(); }
	function layout_build(){ $this->wflux_display_css_do->wf_layout_build(); }
	function rwd_full_width(){ $this->wflux_display_css_do->wf_rwd_full_width(); }

	// Display functions
	function get_sidebar($args){ $this->wflux_display_do->wf_get_sidebar($args); }
	function display_credit(){ $this->wflux_display_do->wf_credit(); }

	// Wonderflux direct activation functions
	function default_layout($args){ $this->wflux_display_code_do->wf_default_layout($args); }

	// EX Display functions - these are useful to theme designers
	function excerpt($args){ return $this->wflux_display_ex_do->wf_excerpt($args); } // TO TEST
	function edit_meta($args){ $this->wflux_display_ex_do->wf_edit_meta($args); } // NEEDS MORE TESTING?
	function get_single_content($args){ $this->wflux_display_ex_do->wf_get_single_content($args); }
	function login_logout($args){ $this->wflux_display_ex_do->wf_login_logout($args); }
	function static_highlight($args){ $this->wflux_display_ex_do->wf_static_highlight($args); }
	function get_attachments($args){ return $this->wflux_display_ex_do->wf_get_attachments($args); }
	function get_image($args){ return $this->wflux_display_ex_do->wf_get_image($args); }
	function page_counter($args){ return $this->wflux_display_ex_do->wf_page_counter($args); }
	function get_cached_part($args){ return $this->wflux_display_ex_do->wf_get_cached_part($args); }
	function build_hyperlink($args){ return $this->wflux_display_ex_do->wf_build_hyperlink($args); }
	function array_to_delimited_string($args){ return $this->wflux_display_ex_do->wf_array_to_delimited_string($args); }
	function auto_text($args){ return $this->wflux_display_ex_do->wf_auto_text($args); }

}


/**
* @since 2.6
* @updated 2.6
* Sets up all Wonderflux WP REST API functions
*/
function wflux_capacitor_restapi() {

	load_template( WF_INCLUDES_DIR . '/wf-rest-api-display.php' );
	global $wflux_restapi_do;
	$wflux_restapi_do = new wflux_restapi_all;

}
add_action( 'rest_api_init','wflux_capacitor_restapi', 1 );


/**
* @since 2.6
* @updated 2.6
* Creates all Wonderflux WP REST API functions
*/
class wflux_restapi_all {

	public $wflux_restapi_do;

	function __construct(){
		$this->wflux_restapi_do = new wflux_display_restapi;
	}

	function rest_add_post_classes(){ $this->wflux_restapi_do->wf_rest_add_post_classes(); }

}
?>