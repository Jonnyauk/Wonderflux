<?php header("Content-type: text/css"); ?>
/**
 *
 * Flux Layout
 *
 * A dynamic percentage based layout engine - https://github.com/Jonnyauk/flux-layout
 * Built for the Wonderflux theme framework - https://github.com/Jonnyauk/Wonderflux
 *
 * Free to use on any project and released under the GPLv2 license
 * Created by Jonny Allbut (copyright 2014). Exceptions include, but are not limited to:
 * Normalize - https://git.io/normalize - MIT License - project by Nicolas Gallagher, co-created with Jonathan Neal
 *
 * THIS IS AN EXPERIMENTAL - NOT FOR PRODUCTION JUST YET!!
 *
 * @package Wonderflux
 * @since Wonderflux 2.0
 *
 */

<?php

/* DO IT! Just for testing and development */
$wf_grid = new wflux_layout;
$wf_grid->grid_containers();
$wf_grid->grid_blocks();
//$wf_grid->grid_space_loops();
//$wf_grid->grid_push_loops();
$wf_grid->grid_relative_loops();
$wf_grid->grid_columns();
$wf_grid->grid_media_queries();

/**
 * Percent based CSS and media query layout generator
 * @since 2.0
 */
class wflux_layout {

	protected $rwd_width;				// INPUT - Width of main container
	protected $rwd_width_units;			// INPUT - Units for main container width (% or pixels)
	protected $rwd_columns_basic;		// INPUT - Number of basic (no gutter) columns in layout
	protected $rwd_class_prepend;		// INPUT - Prepend all CSS main selectors
	protected $rwd_columns_prepend;		// INPUT - Prepend all CSS column selectors
	protected $rwd_columns;				// ARRAY - Advanced columns with gutters
	protected $rwd_columns_gutter;		// INPUT - Target gutter (%)
	protected $rwd_relative;			// ARRAY - General relative sizes
	protected $mq_config;				// ARRAY - Media queries cofig
	protected $mq_specific;				// ARRAY - Media query relative sizes
	protected $rwd_class_space_left;	// INTERNAL - CSS selector - padding left
	protected $rwd_class_space_right;	// INTERNAL - CSS selector - padding right
	protected $rwd_class_move_left;		// INTERNAL - CSS selector - margin left
	protected $rwd_class_move_right;	// INTERNAL - CSS selector - margin right
	protected $rwd_column_width;		// INTERNAL - Width of columns (%)
	protected $rwd_minify;				// INTERNAL - CSS selector - column width blocks

	function __construct() {

		// Cleanup all data ready to be used
		$this->rwd_width = ( is_numeric( $_GET['w'] ) && $_GET['w'] <= 101 ) ? $_GET['w'] : 80;
		$this->rwd_width_units = ( $_GET['wu'] == 'percent' ) ? '%' : 'px';
		$this->rwd_columns_basic = ( is_numeric( $_GET['c'] ) && $_GET['c'] <= 101 ) ? $_GET['c'] : 16;
		$this->rwd_class_prepend = ( !isset($this->rwd_class_prepend) ) ? 'box-' : strtolower( preg_replace('/[^a-z0-9_\-]/', '', $this->rwd_class_prepend) );
		$this->rwd_columns_prepend = ( !isset($this->rwd_columns_prepend) ) ? 'column-' : strtolower( preg_replace('/[^a-z0-9_\-]/', '', $this->rwd_columns_prepend) );

		// Loops of output
		$this->rwd_relative = array(1,2,3,4,8);
		// Add core column option to box array for output
		if ( !in_array($this->rwd_columns_basic, $this->rwd_relative) ){
			sort($this->rwd_relative);
			array_unshift( $this->rwd_relative, $this->rwd_columns_basic );
		}

		$this->rwd_columns = array(1,2,3,4,8);
		// Add core column option to columns array for output
		if ( !in_array($this->rwd_columns_basic, $this->rwd_columns) ){
			sort($this->rwd_columns);
			array_unshift( $this->rwd_columns, $this->rwd_columns_basic );
		}

		$this->rwd_columns_gutter = 2;

		$this->mq_specific = array(1,2,4,8);

		$this->mq_config = array(
			'tiny'	=> array(
							'def'	=> 'mq-tiny',
							'min'	=> 0,
							'max'	=> 480,
							'units'	=> 'px',
							'note'	=> 'Tiny screens - small portrait phones'
						),
			'small'		=> array(
							'def'	=> 'mq-small',
							'min'	=> 481,
							'max'	=> 768,
							'units'	=> 'px',
							'note'	=> 'Small screens - Lower spec landscape phones and some portrait tablets'
						),
			'medium'		=> array(
							'def'	=> 'mq-medium',
							'min'	=> 769,
							'max'	=> 1409,
							'units'	=> 'px',
							'note'	=> 'Medium screens - Standard computers and landscape tablets'
						),
			'large'		=> array(
							'def'	=> 'mq-large',
							'min'	=> 1410,
							'units'	=> 'px',
							'note'	=> 'Large screens - Swanky hi-res screens'
						),

		);

		// Internal values
		$this->rwd_column_width = 100 / $this->rwd_columns_basic;
		$this->rwd_class_space_left = $this->rwd_class_prepend . 'pad-left';
		$this->rwd_class_space_right = $this->rwd_class_prepend . 'pad-right';
		$this->rwd_class_move_left = $this->rwd_class_prepend . 'move-left';
		$this->rwd_class_move_right = $this->rwd_class_prepend . 'move-right';
		$this->rwd_minify = "\n";
		$this->rwd_minify_2 = $this->rwd_minify . $this->rwd_minify;

	}

	/**
	 * Outputs main site .container and .row classes
	 */
	function grid_containers() {

		echo '.container { ' . 'width:' . $this->rwd_width . $this->rwd_width_units . '; margin:0 auto; }'
		. $this->rwd_minify . '.row { ' . 'width:100%; margin:0 auto; }' . $this->rwd_minify_2;

	}

	/**
	 * Outputs percent widths for blocks
	 */
	function grid_blocks() {

		echo '/******** Grid boxes ********/' . $this->rwd_minify_2;

		// CSS attribute wildcard selectors
		echo 'div[class*="' . $this->rwd_class_prepend . '"] { '
		. 'float:left; margin: 0; }'
		 . $this->rwd_minify_2;

		// Main output
		for ( $limit=1; $limit <= $this->rwd_columns_basic; $limit++ ) {
			echo '.' . $this->rwd_class_prepend . $limit . ' { width: '
			. $this->rwd_column_width * $limit . '%; }' . $this->rwd_minify;
		}
		echo $this->rwd_minify;

	}

	/**
	 * Outputs columns rules
	 */
	function grid_columns() {

		echo '/******** Traditional columns ********/' . $this->rwd_minify_2;

		// CSS attribute wildcard selectors
		echo 'div[class*="' . $this->rwd_columns_prepend . '"] { '
		. 'float:left; margin-left: ' . $this->rwd_columns_gutter . '%; }'
		 . $this->rwd_minify;

		echo '.row.' . rtrim($this->rwd_columns_prepend, '-') . ' div:first-child { margin-left: 0; }' . $this->rwd_minify;

		foreach ( $this->rwd_columns as $size_r ) {
			if ( intval($size_r) < 101 ) {
				for ( $limit=1; $limit < $size_r || $limit == 1; $limit++ ) {
					if ( $size_r!=1 ){

						echo '.' . $this->rwd_columns_prepend . $limit . '-' . $size_r
						. ' { width:'
						. ((100 - ($size_r - 1) * $this->rwd_columns_gutter) / $size_r ) * $limit
						. '%; }'
						. $this->rwd_minify;

					}
				}
			}
		}

		echo $this->rwd_minify;

	}

	/**
	 * Outputs margin + padding rules
	 */
	function grid_mover( $type, $definition, $direction ) {

		$negpos = ( $type == 'push' ) ? '-' : '';
		$css_type = ( $type == 'push' ) ? 'margin' : 'padding';

		$css_1 = ( $direction == 'l' ) ? '{ ' . $css_type . ': 0 ' . $negpos : '{ ' . $css_type . ': 0 0 0 ' . $negpos;
		$css_2 = ( $direction == 'l' ) ? ' 0 0; ' : '; ';

		for ( $limit=1; $limit <= $this->rwd_columns_basic; $limit++ ) {
			echo '.' . $definition . '-' . $limit . ' ' . $css_1
			. $this->rwd_column_width * $limit . '%' . $css_2 . '}' . $this->rwd_minify;
		}

	}

	function grid_space_loops() {

		$this->grid_mover( 'space', $this->rwd_class_space_left, 'l' );
		$this->grid_mover( 'space', $this->rwd_class_space_right, 'r' );
		echo $this->rwd_minify;

	}

	function grid_push_loops() {

		$this->grid_mover( 'push', $this->rwd_class_move_left, 'l' );
		$this->grid_mover( 'push', $this->rwd_class_move_right, 'r' );
		echo $this->rwd_minify;

	}

	/**
	 * Outputs relative sized CSS
	 * $sizes = array of integers representing what sizes to output
	 */
	function grid_relative_loops() {

		if ( !is_array($this->rwd_relative) ) return;

		foreach ( $this->rwd_relative as $size ) {

			if ( intval($size) >= 1 && intval($size) < 101 ) {

				// Only get secondary named classes
				switch ( intval($size) ) {
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
					default: $def = array( intval($size), '' ); break;
				}

			}

			if ( isset($def) ) {

				echo '/* ' . $def[0];
				echo ( $def[0] > 1 ) ? ' columns' : ' column';
				echo ( !empty($def[1]) ) ? ' - ' . $def[1] : '';
				echo ' */' . $this->rwd_minify;

				if ( $size == 1 ){

					echo '.' . $this->rwd_class_prepend . '1-1'
					//. ', .' . $this->rwd_class_prepend . $def[1]
					.' { width:100%; }' . $this->rwd_minify;

				} else {

					for ( $limit=1; $limit < $size; $limit++ ) {

						echo '.' . $this->rwd_class_prepend . $limit . '-' . $def[0];
						//echo ( !empty($def[1]) ) ? ', .' . $this->rwd_class_prepend . $limit . '-' . $def[1] : '';
						echo ' { width:' . $limit * ( 100 / $size ) . '%; }' . $this->rwd_minify;

					}

				}

				echo $this->rwd_minify;

			}

		}

	}

	/**
	 * Media queries output for different sized screens
	 * 4 definitions:
	 * rwd-tiny Tiny screens - small portrait phones
	 * rwd-small Small screens - Lower spec landscape phones and some portrait tablets
	 * rwd-medium Medium screens - Standard computers and landscape tablets
	 * rwd-large Large screens - Swanky hi-res screens
	 */
	function grid_media_queries() {

		// Array of just definitions - used for -hide-except rules
		$all_defs = array();

		foreach ( $this->mq_config as $size ) {
			$all_defs[] = $size['def']; // Used to exclude in hider media queries
			$sizes_min[] = $size['min']; // Used to exclude in hider media queries
			$sizes_max[] = $size['max']; // Used to exclude in hider media queries
		}

		$all_defs_count = count( $all_defs );

		echo '/******** Media Queries ********/' . $this->rwd_minify_2;

		// CSS attribute wildcard selectors
		$w_count = 2;
		foreach ( $all_defs as $def ) {
			$seperator = ( ($all_defs_count) == $w_count-1 ) ? ' ' : ', ';
			//echo '.' . $def . '-' . $prepend;
			echo 'div[class*="' . $def . '-' . $prepend . '"]' . $seperator;
			$w_count = ( $def != $size['def'] ) ? $w_count+1 : $w_count;
		}
		echo '{ float:left; }' . $this->rwd_minify_2;

		foreach ( $this->mq_config as $size ) {

			$units = ( !$size[units] && $size[units] == 'px' ) ? 'px' : substr( $size[units], 0, 2 );
			$min = ( !$size[min] && !is_numeric($size[min]) ) ? '' : 'and ( min-width:' . $size[min] . $units . ' )';
			$max = ( !$size[max] && !is_numeric($size[max]) ) ? '' : 'and ( max-width:' . $size[max] . $units . ' )';
			$size_queries = ( !empty($min) && !empty($max) ) ? $min . ' ' . $max : $min . $max;

			// Open media query
			echo '/* ' . $size['def'] . ': ' . $size['note'] . ' */' . $this->rwd_minify
			. '@media screen ' . $size_queries . ' {' . $this->rwd_minify;

			// Keep span
			echo ' span.' . $size['def'] . '-keep { display:block; }' . $this->rwd_minify;

			// Specific breakpoint hider
			echo ' .' . $size['def'] . '-hide { display: none; }' . $this->rwd_minify;

			// Other breakpoint hiders
			$o_count = 2;
			foreach ( $all_defs as $def ) {
				$prepend = ( ($all_defs_count) == $o_count ) ? ' ' : ',';
				echo ( $def != $size['def'] ) ? ' .' . $def . '-only' . $prepend : '';
				$o_count = ( $def != $size['def'] ) ? $o_count+1 : $o_count;
			}
			echo '{ display: none; }' . $this->rwd_minify;

			// Specific proportional breakpoint sizers
			foreach ( $this->mq_specific as $size_r ) {
				if ( intval($size_r) < 101 ) {
					for ( $limit=1; $limit < $size_r || $limit == 1; $limit++ ) {

						echo ' .' . $size['def'] . '-' . $limit . '-' . $size_r;

						for ( $limit_def=0; $limit_def < ($all_defs_count); $limit_def++ ) {
							echo ( $all_defs[$limit_def] <= $size['def'] ) ? ', .' . $all_defs[$limit_def] . '-min-' . $limit . '-' . $size_r : '';
						}

						echo ' { width:' . ( 100/$size_r ) * $limit . '%; ';
						//echo ( $size_r == 1 ) ? '' : 'float:left; ';
						echo '}' . $this->rwd_minify;

					}
				}
			}

			// Close media query
			echo '}' . $this->rwd_minify_2;

		}

	}

}
?>