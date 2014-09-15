<?php header("Content-type: text/css"); ?>
/**
 *
 * Flux Layout - A dynamic percentage based layout engine
 * Built for the Wonderflux theme framework
 *
 * NO DATA IS NOT ESCAPED THIS IS AN EXPERIMENTAL - NOT FOR PRODUCTION JUST YET!!
 * http://wonderflux.com
 *
 * @package Wonderflux
 * @since Wonderflux 2.0
 *
 */

<?php

//TODO: Check and clean input values

?>

/* General rules */

* {
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}
*:before,
*:after {
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
}


<?php


/* DO IT! */
$wf_grid = new wflux_wondergrid;
$wf_grid->grid_container();
$wf_grid->grid_float_blocks();
$wf_grid->grid_blocks();
$wf_grid->grid_space_loops();
$wf_grid->grid_push_loops();
$wf_grid->grid_relative_loops(array(1,2,3,4,5,6,7,8,9,10,11,12));
$wf_grid->grid_media_queries();


/**
 * Grid building functionality for wf-css-dynamic-columns file
 * @since 2.0
 */
class wflux_wondergrid {

	protected $rwd_width;				// Width of main container (% or pixels)
	protected $rwd_columns;				// Number of columns in layout
	protected $rwd_column_width;		// Width of columns (%)
	protected $rwd_class_prepend;		// Prepend all CSS selectors (or not!)
	protected $rwd_class_proportional;	// Prepend all CSS selectors (or not!)
	protected $rwd_class_block;			// CSS selector - column width blocks
	protected $rwd_class_space_left;	// CSS selector - padding left
	protected $rwd_class_space_right;	// CSS selector - padding right
	protected $rwd_class_move_left;		// CSS selector - margin left
	protected $rwd_class_move_right;	// CSS selector - margin right

	function __construct() {

		//TODO - ADD IN FILTERS HERE!

		$this->rwd_width = ( is_numeric( $_GET['w'] ) && $_GET['w'] <= 101 ) ? $_GET['w'] : 80;
		$this->rwd_columns = ( is_numeric( $_GET['c'] ) && $_GET['c'] <= 101 ) ? $_GET['c'] : 20;
		$this->rwd_column_width = 100 / $this->rwd_columns;

		$this->rwd_class_prepend = '';
		$this->rwd_class_proportional = 'size-';
		$this->rwd_class_block = $this->rwd_class_prepend . 'span';
		$this->rwd_class_space_left = $this->rwd_class_prepend . 'pad-left';
		$this->rwd_class_space_right = $this->rwd_class_prepend . 'pad-right';
		$this->rwd_class_move_left = $this->rwd_class_prepend . 'move-left';
		$this->rwd_class_move_right = $this->rwd_class_prepend . 'move-right';

	}

	/*
	 * Outputs main site container
	 */
	function grid_container(){

		//echo '.container { width: ' . $this->rwd_width . '%; margin: 0 auto; }';
		echo '.container { width: 950px; margin: 0 auto; }';
		echo "\n" . "\n";

	}

	/*
	 * Outputs float rules for all blocks
	 */
	function grid_float_blocks(){

		for ($limit=1; $limit <= $this->rwd_columns; $limit++) {
			echo 'div.' . $this->rwd_class_block . '-' . $limit;
			echo ($limit == $this->rwd_columns) ? '' : ', ';
		}
		echo " { float: left; margin: 0; }";
		echo "\n" . "\n";

	}

	/*
	 * Outputs percent widths for blocks
	 */
	function grid_blocks(){

		for ($limit=1; $limit <= $this->rwd_columns; $limit++) {
			echo '.' . $this->rwd_class_block . '-' . $limit;
			echo ' { width: ' . $this->rwd_column_width * $limit . '%; ' . "} " . "\n";
		}
		echo "\n";

	}

	/*
	 * Outputs marin + padding rules
	 */
	function grid_mover( $type, $definition, $direction ){

		$negpos = ( $type == 'push' ) ? '-' : '';
		$css_type = ( $type == 'push' ) ? 'margin' : 'padding';

		$css_1 = ( $direction == 'l' ) ? '{ ' . $css_type . ': 0 ' . $negpos : '{ ' . $css_type . ': 0 0 0 ' . $negpos;
		$css_2 = ( $direction == 'l' ) ? ' 0 0; ' : '; ';

		for ( $limit=1; $limit <= $this->rwd_columns; $limit++ ) {
			echo '.' . $definition . '-' . $limit . ' ' . $css_1;
			echo $this->rwd_column_width * $limit . '%' . $css_2 . '} ' . "\n";
		}

	}

	function grid_space_loops(){

		$this->grid_mover( 'space', $this->rwd_class_space_left, 'l' );
		$this->grid_mover( 'space', $this->rwd_class_space_right, 'r' );
		echo "\n";

	}

	function grid_push_loops(){

		$this->grid_mover( 'push', $this->rwd_class_move_left, 'l' );
		$this->grid_mover( 'push', $this->rwd_class_move_right, 'r' );
		echo "\n";

	}

	/*
	 * Outputs main site container
	 * $sizes = array of integers representing what dividions to output
	 */
	function grid_relative_loops($sizes) {

		if ( !is_array($sizes) ) return;

		foreach( $sizes as $size ) {

			//if ( intval($size) < 16 ) return;

			switch( $size ){
				case 1: $def = array( 1, 'full' ); break;
				case 2: $def = array( 2, 'half' ); break;
				case 3: $def = array( 3, 'third' ); break;
				case 4: $def = array( 4, 'forth' ); break;
				case 5: $def = array( 5, 'fifth' ); break;
				case 6: $def = array( 6, 'sixth' ); break;
				case 7: $def = array( 7, 'seventh' ); break;
				case 8: $def = array( 8, 'eighth' ); break;
				case 9: $def = array( 9, 'ninth' ); break;
				case 10: $def = array( 10, 'tenth' ); break;
				case 11: $def = array( 11, 'eleventh' ); break;
				case 12: $def = array( 12, 'twelfth' ); break;
				case 13: $def = array( 13, 'thirteenth' ); break;
				case 14: $def = array( 14, 'fourteenth' ); break;
				case 15: $def = array( 15, 'fifteenth' ); break;
				case 16: $def = array( 16, 'sixteenth' ); break;
				case 17: $def = array( 17, 'seventeenth' ); break;
				case 18: $def = array( 18, 'eightteenth' ); break;
				case 19: $def = array( 19, 'nineteenth' ); break;
				case 20: $def = array( 20, 'twentieth' ); break;
				default: $def = array( 0, '' ); break;
			}

			if ( $def[0] > 0 ) {

				echo '/**** ' . $def[0] . ' - ' . $def[1] . ' ****/' . "\n";

				if ( $size == 1 ){
					echo 'rwd-size-1 .' . $this->rwd_class_prepend . $def[1]
					.' { width: 100%; ' . "} " . "\n";

				} else {

					for ( $limit=1; $limit < $size; $limit++ ) {

						echo '.' . $this->rwd_class_prepend . $this->rwd_class_proportional . $limit . '-' . $def[0]
						. ', .' . $this->rwd_class_prepend . $this->rwd_class_proportional . $limit . '-' . $def[1]
						. ' { width: ' . $limit * ( 100 / $size ) . '%; ' .
						'float:left;' . "} " . "\n";
					}

				}

				echo "\n";

			}

		}

	}

}	/*
	 * Media queries output for different sized screens
	 * 4 definitions:
	 * rwd-large Large screen (Should hit very high resolution desktops)
	 * rwd-medium Medium screen (Should hit most portrait tablets)
	 * rwd-small Small screen (should hit most landscape phones)
	 * rwd-tiny Tiny screen (should hit most portrait phones)
	 */
	function grid_media_queries() {

		// TODO: Build this is a more dynamic way!
		// TODO: Options and filters on min or max width
		// TODO: Options and filters on breakpoint integers
		$sizes = array(
			'large'		=> array(
							'def'	=> 'rwd-large',
							'type'	=> 'min',
							'break'	=> 1441,
							'note'	=> 'Large screens - rwd-large (Should hit very high resolution desktops)'
						),
			'medium'	=> array(
							'def'	=> 'rwd-medium',
							'type'	=> 'max',
							'break'	=> 1023,
							'note'	=> 'Medium screens - rwd-medium (Should hit most portrait tablets)'
						),
			'small'		=> array(
							'def'	=> 'rwd-small',
							'type'	=>  'max',
							'break'	=> 759,
							'note'	=> 'Small screens - rwd-small (should hit most landscape phones)'
						),
			'tiny'		=> array(
							'def'	=> 'rwd-tiny',
							'type'	=>  'max',
							'break'	=> 479,
							'note'	=> 'Tiny screens - rwd-tiny (should hit most portrait phones)'
						)
		);

		// Array of just definitions - used for -hide-except rules
		$all_defs = array();
		foreach ($sizes as $size) {
			$all_defs[] = $size['def'];
		}

		foreach ( $sizes as $size ) {

			echo '/* ' . $size['note'] . ' */' . "\n"
			. '@media screen and (' . $size['type']
			. '-width: ' .  $size['break'] . 'px) {' . "\n"
			. "  ." . $size['def'] . '-hide { display: none; }' . "\n";

			// Use $all_defs to build -hide-except rules
			foreach ($all_defs as $a_def) {
				echo ( $a_def != $size['def'] ) ? "  ." . $a_def . '-only { display: none; }' . "\n" : '';
			}

			echo '}'. "\n\n";

		}

	}

}

?>