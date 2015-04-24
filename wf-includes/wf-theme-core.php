<?php

//// The Wonderflux core theme functionality

/**
*
* @since 0.891
* @updated 2.0
*
* Core template functions
*
*/
class wflux_theme_core {

	public $wfx_count_bg_divs;
	public $wfx_count_bg_divs_hook;

	function __construct() {
		$this->wfx_count_bg_divs = 0;
		$this->wfx_count_bg_divs_hook = 0;
	}

	/**
	*
	* Sets up WordPress widgets and optionally inserts into template using Wonderflux hook system, plus a couple of other tricks!
	*
	* @param $name - The name of the widget (shows in admin widget editor area) [Incremental number]
	* @param $description - Description of widget (shows in admin widget editor area) [Drag widgets into here to include them in your site]
	* @param $location - Inserts the widget using Wonderflux display hooks - supply value "my_theme_code" to turn this off [wfsidebar_after_all]
	* @param $container - What do you want the Widget to be contained in - eg "div", "li" [div]
	* @param $containerclass - Container CSS class [widget-box]
	* @param $containerid - ADVANCED USE ONLY - Sets CSS ID (Only use this if your widget area has one widget - otherwise the ID's are repeated, which is not good and breaks validation for obvious reasons!) [NONE]
	* @param $titlestyle - Title CSS [h3]
	* @param $titleclass - Title CSS class [widget-title]
	* @param $titleid - ADVANCED USE ONLY - Sets CSS ID (Only use this if your widget area has one widget - otherwise the ID's are repeated, which is not good and breaks validation for obvious reasons!) [NONE]
	* @param $before - Anything you want before the widget [NONE]
	* @param $after - Anything you want after the widget [NONE]
	* @param $priority - Insertion hook priority - NOTE default CSS containers insert at priority 2 and 9 [3]
	*
	* NOTE:
	* Easiest way to insert a widget into your theme code rather than use a Wonderflux hook is:
	* (where widget name was set as 'Front Page Sidebar')
	* if ( !dynamic_sidebar('front-page-sidebar') ) : echo 'no widget content';
	*
	* Also of use is:
	* if ( is_active_sidebar('front-page-sidebar') ) :
	*   echo 'has active widgets in widget area';
	* else :
	*   echo 'no active widgets';
	* endif;
	*
	* @since 0.891
	* @updated 2.0
	*
	*
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
			"container" => "div",
			"containerclass" => "widget-box",
			"containerid" => "",
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
			if ($containerid !="") { $containerid = ' id="' . esc_attr($containerid) . '"'; }

			if ($titleid !="") { $titleid = ' id="' . esc_attr($titleid) . '"'; }

			$clean_name = esc_attr( strtolower( str_replace(' ', '-', $name) ) );

			// Setup this widget using our options WordPress stylee
			register_sidebar(array(
				'name'=> $name,
				'id'=> $clean_name,
				'description' => $description,
				'before_widget' => esc_attr($before) . '<' . esc_attr($container) . ' class="'. esc_attr($containerclass) . ' widget-' . esc_attr($clean_name) .'"' . esc_attr($containerid) . '>',
				'after_widget' => '</' . esc_attr($container) . '>' . esc_attr($after),
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
			unset($container);
			unset($containerclass);
			unset($containerid);
			unset($titlestyle);
			unset($titleclass);
			unset($titleid);
			unset($before);
			unset($after);
			unset($priority);

		}

	}


	/**
	* Sets up WordPress for language packs
	* EXPERIMENTAL FIRST PASS - needs testing and filters!
	* @since 0.913
	* @updated 1.1
	*/
	function wf_language_pack($args) {
		load_theme_textdomain( 'wonderflux', WF_MAIN_DIR . '/wf-content/languages' );
		$locale = get_locale();
		$locale_file = WF_MAIN_DIR . "/wf-content/languages/$locale.php";
		if ( is_readable( $locale_file ) )
		require_once( $locale_file );
	}


	/**
	* @since 0.92
	* @updated 0.92
	* Sets up required background divs and inserts them using Wonderflux hooks
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
	* Inserts JQuery into your theme
	*
	* @param $host Where your JQuery is hosted - Default = 'wonderflux' ['wordpress','wonderflux','google','microsoft','jquery']
	* @param $version Which version of JQuery to use (No effect if $host = 'wordpress') Default = '1.8.1' [ Various versions, use exact for CDN or '1.6','1.7','1.8' to get latest versions of each from Wonderflux core ]
	* @param $location Where you want your JQuery inserted in the code - Default = 'header' ['header,'footer']
	* @param $host Do you need https? - Default = false [true,false] NOTE: JQuery CDN doesnt support https
	*
	* @since 0.92
	* @updated 1.1
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
	* Sets up Cycle Jquery plugin
	*
	* @param To document
	*
	* @since 0.92
	* @updated 0.931
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
	 * Core WordPress threaded comment reply Javascript
	 * THIS IS REQUIRED for WordPress theme repo compliance
	 *
	 * @since 1.1
	 * @lastupdate 2.0
	 */
	function wf_core_comment_js(){
		if ( is_singular() && ( comments_open() && get_option('thread_comments')) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}


}


/**
*
* @since 0.913
* @updated 0.913
*
* Extra display support elements for Internet Explorer, in particular IE6 - the party pooper at the web designers party!
*
*/
class wflux_theme_ie {

	function wf_ie6_png($args) {

		$defaults = array (
			'type' => 'simple',
			'location' => 'footer'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		//TODO: Setup for advanced PNG fix display too - need to create new function!
		if ($type == 'simple') { $type_out = 'wf_ie6_png_simple_print'; } else { $type_out = 'wf_ie6_png_simple_print'; }
		if ($location == 'footer') { $location_out = 'wf_footer'; } else { $location_out = 'wf_head_meta'; }

		add_action($location_out, array($this, $type_out));

	}

	/**
	* Inserts the Javascript PNG transparency fix
	*
	* @param type - 'Simple' or 'advanced' - Simple is faster rendering, advanced does repeating backgrounds properly [simple]
	*
	* Notes on PNG fixes available:
	* Basic is faster rendering, doesn't require a blank image, but DOESNT DO BACKGROUNDS
	* Advanced renders repeating background pngs, but bit slower rendering if loads of PNGs to deal with (already in wf-includes/js) - so watch it if you are using lots and lots of images. It does function correctly though!
	*
	*
	* @since 0.86
	* @updated 0.913
	*/
	function wf_ie6_png_simple_print() {

		//Empty argument 1 - No script dependincies
		wp_register_script('wfx_script_simple_png', esc_url(WF_CONTENT_URL.'/js/ie-png-fix/png-fix-basic.js'), '', WF_VERSION, true);
		// Insert the conditional code for IE
		echo '<!--[if IE 6]>';
		wp_print_scripts('wfx_script_simple_png');
		echo '<![endif]-->'."\n";

	}

}
?>