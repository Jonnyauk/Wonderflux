<?php

//// The Wonderflux core theme functionality that need to be loaded right at the start ////

/**
*
* @since 0.891
* @updated 0.891
*
* Core template functions
*
*/

class wflux_theme_core {

	/**
	*
	* Sets up WordPress widgets and optionally inserts into template using Wonderflux hook system
	*
	* @param $name The name of the widget
	* @param $description The description of the widget area (used in admin area
	* @param $location Inserts the widget using Wonderflux display hooks - supply value "my_custom_theme_code" to turn this off
	* @param $container What do you want the Widget to be contained in - eg "div", "li"
	* @param $containerclass Container CSS class
	* @param $titlestyle The title styling, eg "h2"
	* @param $titleclass Title CSS class
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
	* @updated 0.891
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
			"location" => "wfsidebar_after_all_content",
			"container" => "div",
			"containerclass" => "wf-widget-area",
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
				$containerid = ' id="' . $containerid . '"';
			}

			if ($titleid !="") {
				$titleid = ' id="' . $titleid . '"';
			}

			// Setup this widget using our options WordPress stylee
			register_sidebar(array(
				'name'=> __($name),
				'description' => __($description),
				'before_widget' => $before . '<' . $container . ' class="'. $containerclass .'"' . $containerid . '>',
				'after_widget' => '</' . $container . '>' . $after,
				'before_title' => '<' . $titlestyle . ' class="'. $titleclass .'"' . $titleid . '>',
				'after_title' => '</' . $titlestyle . '>',
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

}
?>