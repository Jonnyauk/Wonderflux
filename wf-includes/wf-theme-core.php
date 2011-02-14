<?php

//// The Wonderflux core theme functionality that need to be loaded right at the start

/**
*
* @since 0.891
* @updated 0.92
*
* Core template functions
*
*/

class wflux_theme_core {

	static $wfx_count_bg_divs;
	static $wfx_count_bg_divs_hook;

	/**
	*
	* Sets up WordPress widgets and optionally inserts into template using Wonderflux hook system
	*
	* @param $name - The name of the widget (shows in admin widget editor area) [Incremental number]
	* @param $description - Description of widget (shows in admin widget editor area) [Drag widgets into here to include them in your site]
	* @param $location - Inserts the widget using Wonderflux display hooks - supply value "my_custom_theme_code" to turn this off [wfsidebar_after_all]
	* @param $container - What do you want the Widget to be contained in - eg "div", "li" [div]
	* @param $containerclass - Container CSS class [widget-box]
	* @param $containerid - ADVANCED USE ONLY - Sets CSS ID (Only use this if your widget area has one widget - otherwise the ID's are repeated, which is not good and breaks validation for obvious reasons!) [NONE]
	* @param $titlestyle - Title CSS [h3]
	* @param $titleclass - Title CSS class [widget-title]
	* @param $titleid - ADVANCED USE ONLY - Sets CSS ID (Only use this if your widget area has one widget - otherwise the ID's are repeated, which is not good and breaks validation for obvious reasons!) [NONE]
	* @param $before - Anything you want before the widget [NONE]
	* @param $after - Anything you want after the widget [NONE]
	*
	* NOTE:
	* Easiest way to hardcode a widget into your theme rather than use a Wonderflux hook is:
	*
	* Example where widget name was set as 'Front Page Sidebar':
	* //if ( !dynamic_sidebar('front-page-sidebar') ) : echo 'no widget content';
	*
	* Also of use is:
	* //if ( is_active_sidebar('front-page-sidebar') ) :
	* // echo 'has active widgets in widget area';
	* //else :
	* // echo 'no active widgets';
	* //endif;
	*
	* @since 0.891
	* @updated 0.912
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

			// Defaults parameters
			$defaults = array (
			"name" => "Widget area " . $wf_widget_num,
			"description" => "Drag widgets into here to include them in your site.",
			"location" => "wfsidebar_after_all",
			"container" => "div",
			"containerclass" => "widget-box",
			"containerid" => "",
			"titlestyle" => "h3",
			"titleclass" => "widget-title",
			"titleid" => "",
			"before" => "",
			"after" => ""
			);

			$values = wp_parse_args( $values, $defaults );
			extract( $values, EXTR_SKIP );

			// If a specific container or title ID has been supplied, set it up ready to show
			//If none supplied, it doesnt put an ID in at all
			if ($containerid !="") {
				$containerid = ' id="' . esc_attr($containerid) . '"';
			}

			if ($titleid !="") {
				$titleid = ' id="' . esc_attr($titleid) . '"';
			}

			// Setup this widget using our options WordPress stylee
			register_sidebar(array(
				'name'=> __($name),
				'description' => __($description),
				'before_widget' => esc_attr($before) . '<' . esc_attr($container) . ' class="'. esc_attr($containerclass) .'"' . esc_attr($containerid) . '>',
				'after_widget' => '</' . esc_attr($container) . '>' . esc_attr($after),
				'before_title' => '<' . esc_attr($titlestyle) . ' class="'. esc_attr($titleclass) .'"' . esc_attr($titleid) . '>',
				'after_title' => '</' . esc_attr($titlestyle) . '>',
			));

			// Insert the widget area using Wonderflux display hooks
			// IMPORTANT: If you wish to insert the widget area manually into your theme supply 'my_custom_theme_code' as the 'location' parameter.
			// You will then need to insert your widget area using the name parameter into your theme manually using standard WordPress theme code.
			if ($location != 'my_custom_theme_code') {
				add_action( $location, create_function( '$name', "dynamic_sidebar( '$name' );" ) );
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

		}

	}


	/**
	* Sets up WordPress for language packs
	* EXPERIMENTAL FIRST PASS - needs testing and filters!
	* @since 0.913
	* @updated 0.913
	*/
	function wf_language_pack($args) {
		load_theme_textdomain( 'wonderflux', TEMPLATEPATH . '/wf-content/languages' );
		$locale = get_locale();
		$locale_file = TEMPLATEPATH . "/wf-content/languages/$locale.php";
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
		$location_accept = array('site','main','header','footer');
		if ( in_array($location,$location_accept) ) {
			switch ($location) {
				case 'site' : $location_out = $location; $open_hook = 'wfbody_before_wrapper'; $close_hook = 'wfbody_after_wrapper'; break;
				case 'main' : $location_out = $location; $open_hook = 'wfmain_before_wrapper'; $close_hook = 'wfmain_after_wrapper'; break;
				case 'header' : $location_out = $location; $open_hook = 'wfheader_before_wrapper'; $close_hook = 'wfheader_after_wrapper'; break;
				case 'footer' : $location_out = $location; $open_hook = 'wffooter_before_wrapper'; $close_hook = 'wffooter_after_wrapper'; break;
				default : $location_out = 'site'; $open_hook = 'wfbody_before_wrapper'; $close_hook = 'wfbody_after_wrapper'; break;
			}
		} else {
			$location_out = 'site'; $open_hook = 'wfbody_before_wrapper'; $close_hook = 'wfbody_after_wrapper';
		}

		// Do it
		for ($this->wfx_count_bg_divs=1; $this->wfx_count_bg_divs<=$depth; $this->wfx_count_bg_divs++) {
			$this->wfx_count_bg_divs_hook = $location_out;
			add_action( $open_hook, create_function( '', "echo '<div class=\"wrapper\" id=\"' . '$this->wfx_count_bg_divs_hook' . '-bg-' . '$this->wfx_count_bg_divs' . '\">' . \"\n\";" ), 2);
			$wf_bgdiv_close = create_function('', 'echo "</div>";');
			add_action($close_hook, $wf_bgdiv_close, 11);
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