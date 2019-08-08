<?php
/**
 * Custom menu walker Classes to manipluate menu output
 *
 * @since	2.6
 *
 */

/**
 *
 * Custom menu walker
 * Adds div around sub-menu list items.
 *
 * @since	2.6
 * @version	2.6
 *
 */
class wfx_walker_submenu_div extends Walker_Nav_Menu {

	function start_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat( "\t", $depth );

		$output .= "\n"
		. $indent
		. '<div class="sub-menu-wrap">'
		. '<ul class="sub-menu">'
		. "\n";


	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {

		$indent = str_repeat( "\t", $depth );

		$output .= $indent
		. '</ul>'
		. '</div>'
		. "\n";

	}

}