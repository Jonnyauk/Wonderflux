<?php

/**
 * Wonderflux core theme functionality
 *
 * @since	0.891
 * @version	2.0
 */
class wflux_theme_core {

	public $wfx_count_bg_divs;
	public $wfx_count_bg_divs_hook;

	function __construct() {
		$this->wfx_count_bg_divs = 0;
		$this->wfx_count_bg_divs_hook = 0;
	}

	/**
	 * Creates WordPress widget areas and optionally inserts using Wonderflux hook system, plus a couple of other tricks!
	 *
	 * @since	0.891
	 * @version	2.1
	 *
	 * @param	[string] $name			The name of the widget area (shows in admin widget editor). [Widget area x]
	 * @param	[string] $description	Description of widget (shows in admin widget editor). [Drag widgets into here to include them in your site]
	 * @param	[string] $location		Wonderflux display hook to add widget area to - supply value 'my_theme_code' to turn this off. [wfsidebar_after_all]
	 * @param	[string] $contain		Widget container - eg div, li. [div]
	 * @param	[string] $containclass	Widget Container CSS class. [widget-box]
	 * @param	[string] $containid		ADVANCED - Sets CSS ID for container (Only use this if your widget area has one widget - otherwise the IDs are repeated, which is not good and breaks validation for obvious reasons!) []
	 * @param	[string] $titlestyle	What tag to use to wrap the title in. [h3]
	 * @param	[string] $titleclass	CSS class for title. [widget-title]
	 * @param	[string] $titleid		ADVANCED - Sets CSS ID for title (Only use this if your widget area has one widget - otherwise the IDs are repeated, which is not good and breaks validation for obvious reasons!) []
	 * @param	[string] $before		Output before the widget. []
	 * @param	[string] $after			Output after the widget. []
	 * @param	[int] $priority			Wonderflux hook priority - NOTE default CSS containers insert at priority 2 and 9. [3]
	 *
	 * @todo Review code!
	 * @todo Deal with multiple widgets with ID's by appending number?
	 */
	function wf_widgets($args) {

		// Need a unique id number to use in name if not set, otherwise its stormy waters!
		$wf_widget_num = 0;

		foreach ( $args as $values ) {

			// Check for name in array, if doesnt exist create a unique ID number to use for default fallback name
			// Widgets have to have unique names otherwise WordPress borks!
			if (!array_key_exists("name",$values)) { $wf_widget_num++; }
			// Default parameters
			$defaults = array (
			"name" => esc_attr__('Widget area ','wonderflux') . $wf_widget_num,
			"description" => esc_attr__('Drag widgets into here to include them in your site.','wonderflux'),
			"location" => "",
			"contain" => "div",
			"containclass" => "widget-box",
			"containid" => "",
			"titlestyle" => "h3",
			"titleclass" => "widget-title",
			"titleid" => "",
			"before" => "",
			"after" => "",
			"priority" => 3
			);

			$values = wp_parse_args( $values, $defaults );
			extract( $values, EXTR_SKIP );

			// If a specific container or title ID has been supplied, set it up ready to show
			//If none supplied, it doesnt put an ID in at all
			if ($containid !="") { $containid = ' id="' . esc_attr($containid) . '"'; }

			if ($titleid !="") { $titleid = ' id="' . esc_attr($titleid) . '"'; }

			$clean_name = esc_attr( strtolower( str_replace(' ', '-', $name) ) );

			// Setup this widget using our options WordPress stylee
			register_sidebar(array(
				'name'=> $name,
				'id'=> $clean_name,
				'description' => $description,
				'before_widget' => esc_attr($before) . '<' . esc_attr($contain) . ' class="'. esc_attr($containclass) . ' widget-' . esc_attr($clean_name) .'"' . esc_attr($containid) . '>',
				'after_widget' => '</' . esc_attr($contain) . '>' . esc_attr($after),
				'before_title' => '<' . esc_attr($titlestyle) . ' class="'. esc_attr($titleclass) .'"' . esc_attr($titleid) . '>',
				'after_title' => '</' . esc_attr($titlestyle) . '>',
			));

			// Insert the widget area using Wonderflux display hooks
			// IMPORTANT: If you wish to insert the widget area manually into your theme supply 'my_theme_code' as the 'location' parameter.
			// You will then need to insert your widget area using the name parameter into your theme manually using standard WordPress theme code.

			if ( $location != 'my_theme_code' || empty($location) ) {
				$priority = (is_numeric($priority)) ? $priority : 3;
				add_action( $location, create_function( '$name', "dynamic_sidebar( '$name' );" ), $priority );
			}

			// Unset ready for next
			unset($name);
			unset($description);
			unset($location);
			unset($contain);
			unset($containclass);
			unset($containid);
			unset($titlestyle);
			unset($titleclass);
			unset($titleid);
			unset($before);
			unset($after);
			unset($priority);

		}

	}


	/**
	 * Setup for WordPress language packs for translators.
	 * THIS IS REQUIRED for WordPress theme repo compliance.
	 *
	 * @since	0.913
	 * @version	1.1
	 *
	 * @param	none
	 *
	 * @todo EXPERIMENTAL FIRST PASS - needs testing!
	 */
	function wf_language_pack() {
		load_theme_textdomain( 'wonderflux', WF_MAIN_DIR . '/wf-content/languages' );
		$locale = get_locale();
		$locale_file = WF_MAIN_DIR . "/wf-content/languages/$locale.php";
		if ( is_readable( $locale_file ) )
		require_once( $locale_file );
	}


	/**
	 * Creates background wrapper <div>s around different areas of the layout.
	 * Saves having to amend template files and great for setting up backgrounds or multiple backgrounds.
	 *
	 * Outputput formatted like:
	 * <div class="wrapper" id="header-bg-1">
	 * <div class="wrapper" id="header-bg-2">
	 * <div class="wrapper" id="header-bg-3">
	 *
	 * Closes <div> automatically for you of-course!
	 *
	 * @since	0.92
	 * @version	0.92
	 *
	 * @param	[int] $depth			How many wrappers to create. [1]
	 * @param	[string] $location		Location of wrapper. site/main/header/footer/container-header/container-content/container-footer [site]
	 *
	 */
	function wf_background_divs($args) {

		$defaults = array (
			'depth' => 1,
			'location' => 'site'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Tidy up ready for use
		if (is_numeric($depth) && $depth >= 0 && $depth <= 10) { $depth_out = $depth; } else { $depth_out = 0; }
			$location_accept = array('site','main','header','footer','container-header','container-content','container-footer');
		if ( in_array($location,$location_accept) ) {
			switch ($location) {
				case 'site' : $location_out = $location; $open_hook = 'wfbody_before_wrapper'; $close_hook = 'wfbody_after_wrapper'; break;
				case 'main' : $location_out = $location; $open_hook = 'wfmain_before_wrapper'; $close_hook = 'wfmain_after_wrapper'; break;
				case 'header' : $location_out = $location; $open_hook = 'wfheader_before_wrapper'; $close_hook = 'wfheader_after_wrapper'; break;
				case 'footer' : $location_out = $location; $open_hook = 'wffooter_before_wrapper'; $close_hook = 'wffooter_after_wrapper'; break;
				case 'container-header' : $location_out = $location; $open_hook = 'wfheader_before_content'; $close_hook = 'wfheader_after_content'; break;
				case 'container-content' : $location_out = $location; $open_hook = 'wfmain_before_all_content'; $close_hook = 'wfmain_after_all_main_content'; break;
				case 'container-footer' : $location_out = $location; $open_hook = 'wffooter_before_content'; $close_hook = 'wffooter_after_content'; break;
				default : $location_out = 'site'; $open_hook = 'wfbody_before_wrapper'; $close_hook = 'wfbody_after_wrapper'; break;
			}
		} else {
			// Fallback in all other cases
			$location_out = 'site'; $open_hook = 'wfbody_before_wrapper'; $close_hook = 'wfbody_after_wrapper';
		}

		for ($this->wfx_count_bg_divs=1; $this->wfx_count_bg_divs<=$depth; $this->wfx_count_bg_divs++) {

			$this->wfx_count_bg_divs_hook = $location_out;

			$container_special = array('container-header','container-content','container-footer');
			$container_type = in_array($location,$container_special) ? 'container' : 'wrapper';

			add_action( $open_hook, create_function( '', "echo '<div class=\"$container_type\" id=\"' . '$this->wfx_count_bg_divs_hook' . '-bg-' . '$this->wfx_count_bg_divs' . '\">' . \"\n\";" ), 1);

			$wf_bgdiv_close = create_function('', 'echo "</div>";');
			add_action($close_hook, $wf_bgdiv_close, 12);
		}

	}


	/**
	 * Add Jquery to your theme in various ways.
	 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
	 *
	 * @since	0.92
	 * @version	1.1
	 *
	 * @param	[string] $host			Where your JQuery is hosted - select from CDN if required. wordpress/wonderflux/google/microsoft/jquery [wordpress]
	 * @param	[string] $version		Which version of JQuery to use (No effect if $host = 'wordpress', use exact version string for CDN version). [1]
	 * @param	[string] $location		Where to insert JQuery. header/footer [header]
	 * @param	[bool] $https			Do you want https? true/false [false]
	 *
	 * @todo 	Remove and move to legacy support plugin.
	 */
	function wf_js_jquery($args) {

		$latest_jquery = '1.8.1'; // Latest version bundled in Wonderflux
		// Backpat - WordPress 3.4 - v1.7.2, WordPress 3.3 = v1.7.1, WordPress 3.2 = v1.6.1
		$wp_jquery = ( WF_WORDPRESS_VERSION < 3.4 ) ? ( WF_WORDPRESS_VERSION < 3.3 ) ? '1.6.1' : '1.7.1' : '1.7.2'; // WordPress bundled JQuery (just used in version string, not too important)
		$wp_jquery_url = includes_url().'js/jquery/jquery.js'; // Core WordPress bundled JQuery URL

		$defaults = array (
			'host'		=> 'wordpress',
			'version'	=> $latest_jquery,
			'location'	=> 'header',
			'https'		=> false,
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$location = ( $location == 'footer' ) ? true : false;

		if ( $host == 'wordpress' ):
			if ( $location == 'footer' )
				wp_deregister_script('jquery'); // Core WordPress JQuery always wants to be in the header;(
				wp_register_script('jquery', $wp_jquery_url, false, $wp_jquery, true);
			wp_enqueue_script( 'jquery' );
		else:
			switch ( $host ) {
				case 'wonderflux':
					switch ($version) {
						case '1.6'	: $version = '1.6.4'; break;
						case '1.7' : $version = '1.7.2'; break;
						case '1.8'	: $version = '1.8.1'; break;
						default		: $version = $latest_jquery; break;
					}
					$host = ($version == $wp_jquery) ? $wp_jquery_url : WF_CONTENT_URL.'/js/jquery/' . $version . '/jquery.min.js';
				break;
				case 'google': $host = ( $https == true ) ? 'https://' : 'http://'; $host .= 'ajax.googleapis.com/ajax/libs/jquery/'. $version .'/jquery.min.js'; break;
				case 'microsoft': $host = ( $https == true ) ? 'https://' : 'http://'; $host .= 'ajax.aspnetcdn.com/ajax/jQuery/jquery-' . $version . '.min.js'; break;
				case 'jquery': $host = 'http://code.jquery.com/jquery-' . $version . '.min.js'; break;
				default: $host = WF_CONTENT_URL.'/js/jquery/' . $latest_jquery . '/jquery.min.js'; break;
			}
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', esc_url($host), false, $version, true );
			wp_enqueue_script( 'jquery' );
		endif;
	}


	/**
	 * Add Jquery Cycle to your theme in various ways.
	 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
	 * Just wp_register_script() and wp_enqueue_script() your own!
	 * I rather like http://kenwheeler.github.io/slick/ instead these days for a carousel ;)
	 *
	 * @since	0.92
	 * @version	0.931
	 *
	 * @param	[string] $host			Where your Cycle script is hosted - select from CDN if required. wonderflux/theme/microsoft [wonderflux]
	 * @param	[string] $type			Which type of Cycle script to use. lite/normal/all [normal]
	 * @param	[string] $theme_dir		URL to your themes cycle config file. (with slash at the start). [/js/cycle]
	 * @param	[string] $location		Where do you want to insert Cycle? header/footer [header]
	 * @param	[string] $config		Do you want to use the standard Wonderflux cycle config or your themes? wonderflux/theme [wonderflux]
	 *
	 * @todo 	Remove and move to legacy support plugin.
	 */
	function wf_js_cycle($args) {

		if ( !is_admin() ) {

			$defaults = array (
				'host' => 'wonderflux',
				'type' => 'normal',
				'theme_dir' => '/js/cycle',
				'location' => 'header',
				'config' => 'wonderflux',
			);

			$args = wp_parse_args( $args, $defaults );
			extract( $args, EXTR_SKIP );

			$type_out = 'jquery.cycle.min'; /* Default to normal version */
			if ( $type != 'normal' ):
				switch ( $type ) {
					case 'lite' : $type_out = 'jquery.cycle.lite.min'; break;
					case 'all' : $type_out = 'jquery.cycle.all.min'; break;
				}
			endif;

			$host_out = WF_CONTENT_URL.'/js/cycle/'; /* Default Wonderflux version */
			if ( $host != 'wonderflux' ):
				switch ( $host ) {
					case 'theme':
						$theme_dir = ( $theme_dir == '/js/cycle' ) ? $theme_dir : wp_kses_data( $theme_dir );
						$host_out = WF_THEME_URL . $theme_dir . '/';
					break;
					case 'microsoft':
						$host_out = 'http://ajax.aspnetcdn.com/ajax/jquery.cycle/2.99/';
					break;
				}
			endif;

			$config_out = ( $config == 'wonderflux' ) ? WF_CONTENT_URL.'/js/cycle/jquery.cycle.config.js' : WF_THEME_URL . '/js/cycle/jquery.cycle.config.js';
			$location = ( $location == 'footer' ) ? true : false;
			wp_register_script( 'jquery_cycle', esc_url( $host_out . $type_out . '.js' ), array('jquery'), '2.99' , $location);
			wp_enqueue_script( 'jquery_cycle' );
			wp_register_script( 'jquery_cycle_config', esc_url( $config_out ), array('jquery'), '2.99' , $location);
			wp_enqueue_script( 'jquery_cycle_config' );
		}

	}


	/**
	 * Core WordPress threaded comment reply Javascript.
	 * THIS IS REQUIRED for WordPress theme repo compliance.
	 *
	 * @since	1.1
	 * @version	2.0
	 */
	function wf_core_comment_js() {
		if ( is_singular() && ( comments_open() && get_option('thread_comments')) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}


}
?>