<?php header("Content-type: text/css"); ?>
/**
 *
 * Flux Layout - A dynamic percentage based layout engine
 * Built for the Wonderflux theme framework - http://wonderflux.com
 * Free to use on any project and released under the GPLv2 license
 * Created by Jonny Allbut (copyright 2014). Exceptions include, but are not limited to excerpts of:
 * normalize - https://git.io/normalize - MIT License - project by Nicolas Gallagher, co-created with Jonathan Neal
 * inuitcss - https://github.com/inuitcss - Apache 2 license -  - project by Harry Roberts
 *
 * NO DATA IS NOT ESCAPED THIS IS AN EXPERIMENTAL - NOT FOR PRODUCTION JUST YET!!
 * http://wonderflux.com
 *
 * @package Wonderflux
 * @since Wonderflux 2.0
 *
 */

/*! normalize.css v3.0.1 */

/**
 * 1. Set default font family to sans-serif.
 * 2. Prevent iOS text size adjust after orientation change, without disabling
 *    user zoom.
 */

html {
  font-family: sans-serif; /* 1 */
  -ms-text-size-adjust: 100%; /* 2 */
  -webkit-text-size-adjust: 100%; /* 2 */
}

/**
 * Remove default margin.
 */

body {
  margin: 0;
}

/* HTML5 display definitions
   ========================================================================== */

/**
 * Correct `block` display not defined for any HTML5 element in IE 8/9.
 * Correct `block` display not defined for `details` or `summary` in IE 10/11 and Firefox.
 * Correct `block` display not defined for `main` in IE 11.
 */

article,
aside,
details,
figcaption,
figure,
footer,
header,
hgroup,
main,
nav,
section,
summary {
  display: block;
}

/**
 * 1. Correct `inline-block` display not defined in IE 8/9.
 * 2. Normalize vertical alignment of `progress` in Chrome, Firefox, and Opera.
 */

audio,
canvas,
progress,
video {
  display: inline-block; /* 1 */
  vertical-align: baseline; /* 2 */
}

/**
 * Prevent modern browsers from displaying `audio` without controls.
 * Remove excess height in iOS 5 devices.
 */

audio:not([controls]) {
  display: none;
  height: 0;
}

/**
 * Address `[hidden]` styling not present in IE 8/9/10.
 * Hide the `template` element in IE 8/9/11, Safari, and Firefox < 22.
 */

[hidden],
template {
  display: none;
}

/* Links
   ========================================================================== */

/**
 * Remove the gray background color from active links in IE 10.
 */

a {
  background: transparent;
}

/**
 * Improve readability when focused and also mouse hovered in all browsers.
 */

a:active,
a:hover {
  outline: 0;
}

/* Text-level semantics
   ========================================================================== */

/**
 * Address styling not present in IE 8/9/10/11, Safari, and Chrome.
 */

abbr[title] {
  border-bottom: 1px dotted;
}

/**
 * Address style set to `bolder` in Firefox 4+, Safari, and Chrome.
 */

b,
strong {
  font-weight: bold;
}

/**
 * Address styling not present in Safari and Chrome.
 */

dfn {
  font-style: italic;
}

/**
 * Address variable `h1` font-size and margin within `section` and `article`
 * contexts in Firefox 4+, Safari, and Chrome.
 */

h1 {
  font-size: 2em;
  margin: 0.67em 0;
}

/**
 * Address styling not present in IE 8/9.
 */

mark {
  background: #ff0;
  color: #000;
}

/**
 * Address inconsistent and variable font size in all browsers.
 */

small {
  font-size: 80%;
}

/**
 * Prevent `sub` and `sup` affecting `line-height` in all browsers.
 */

sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline;
}

sup {
  top: -0.5em;
}

sub {
  bottom: -0.25em;
}

/* Embedded content
   ========================================================================== */

/**
 * Remove border when inside `a` element in IE 8/9/10.
 */

img {
  border: 0;
}

/**
 * Correct overflow not hidden in IE 9/10/11.
 */

svg:not(:root) {
  overflow: hidden;
}

/* Grouping content
   ========================================================================== */

/**
 * Address margin not present in IE 8/9 and Safari.
 */

figure {
  margin: 1em 40px;
}

/**
 * Address differences between Firefox and other browsers.
 */

hr {
  -moz-box-sizing: content-box;
  box-sizing: content-box;
  height: 0;
}

/**
 * Contain overflow in all browsers.
 */

pre {
  overflow: auto;
}

/**
 * Address odd `em`-unit font size rendering in all browsers.
 */

code,
kbd,
pre,
samp {
  font-family: monospace, monospace;
  font-size: 1em;
}

/* Forms
   ========================================================================== */

/**
 * Known limitation: by default, Chrome and Safari on OS X allow very limited
 * styling of `select`, unless a `border` property is set.
 */

/**
 * 1. Correct color not being inherited.
 *    Known issue: affects color of disabled elements.
 * 2. Correct font properties not being inherited.
 * 3. Address margins set differently in Firefox 4+, Safari, and Chrome.
 */

button,
input,
optgroup,
select,
textarea {
  color: inherit; /* 1 */
  font: inherit; /* 2 */
  margin: 0; /* 3 */
}

/**
 * Address `overflow` set to `hidden` in IE 8/9/10/11.
 */

button {
  overflow: visible;
}

/**
 * Address inconsistent `text-transform` inheritance for `button` and `select`.
 * All other form control elements do not inherit `text-transform` values.
 * Correct `button` style inheritance in Firefox, IE 8/9/10/11, and Opera.
 * Correct `select` style inheritance in Firefox.
 */

button,
select {
  text-transform: none;
}

/**
 * 1. Avoid the WebKit bug in Android 4.0.* where (2) destroys native `audio`
 *    and `video` controls.
 * 2. Correct inability to style clickable `input` types in iOS.
 * 3. Improve usability and consistency of cursor style between image-type
 *    `input` and others.
 */

button,
html input[type="button"], /* 1 */
input[type="reset"],
input[type="submit"] {
  -webkit-appearance: button; /* 2 */
  cursor: pointer; /* 3 */
}

/**
 * Re-set default cursor for disabled elements.
 */

button[disabled],
html input[disabled] {
  cursor: default;
}

/**
 * Remove inner padding and border in Firefox 4+.
 */

button::-moz-focus-inner,
input::-moz-focus-inner {
  border: 0;
  padding: 0;
}

/**
 * Address Firefox 4+ setting `line-height` on `input` using `!important` in
 * the UA stylesheet.
 */

input {
  line-height: normal;
}

/**
 * It's recommended that you don't attempt to style these elements.
 * Firefox's implementation doesn't respect box-sizing, padding, or width.
 *
 * 1. Address box sizing set to `content-box` in IE 8/9/10.
 * 2. Remove excess padding in IE 8/9/10.
 */

input[type="checkbox"],
input[type="radio"] {
  box-sizing: border-box; /* 1 */
  padding: 0; /* 2 */
}

/**
 * Fix the cursor style for Chrome's increment/decrement buttons. For certain
 * `font-size` values of the `input`, it causes the cursor style of the
 * decrement button to change from `default` to `text`.
 */

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

/**
 * 1. Address `appearance` set to `searchfield` in Safari and Chrome.
 * 2. Address `box-sizing` set to `border-box` in Safari and Chrome
 *    (include `-moz` to future-proof).
 */

input[type="search"] {
  -webkit-appearance: textfield; /* 1 */
  -moz-box-sizing: content-box;
  -webkit-box-sizing: content-box; /* 2 */
  box-sizing: content-box;
}

/**
 * Remove inner padding and search cancel button in Safari and Chrome on OS X.
 * Safari (but not Chrome) clips the cancel button when the search input has
 * padding (and `textfield` appearance).
 */

input[type="search"]::-webkit-search-cancel-button,
input[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

/**
 * Define consistent border, margin, and padding.
 */

fieldset {
  border: 1px solid #c0c0c0;
  margin: 0 2px;
  padding: 0.35em 0.625em 0.75em;
}

/**
 * 1. Correct `color` not being inherited in IE 8/9/10/11.
 * 2. Remove padding so people aren't caught out if they zero out fieldsets.
 */

legend {
  border: 0; /* 1 */
  padding: 0; /* 2 */
}

/**
 * Remove default vertical scrollbar in IE 8/9/10/11.
 */

textarea {
  overflow: auto;
}

/**
 * Don't inherit the `font-weight` (applied by a rule above).
 * NOTE: the default cannot safely be changed in Chrome and Safari on OS X.
 */

optgroup {
  font-weight: bold;
}

/* Tables
   ========================================================================== */

/**
 * Remove most spacing between table cells.
 */

table {
  border-collapse: collapse;
  border-spacing: 0;
}

td,
th {
  padding: 0;
}

<?php


/* DO IT! */
$wf_grid = new wflux_layout;
$wf_grid->grid_container();
$wf_grid->grid_float_blocks();
$wf_grid->grid_blocks();
$wf_grid->grid_space_loops();
$wf_grid->grid_push_loops();
$wf_grid->grid_relative_loops(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20));
$wf_grid->grid_media_queries();


/**
 * Grid building functionality for wf-css-dynamic-columns file
 * @since 2.0
 */
class wflux_layout {

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

		// TODO: - Add in filters
		// TODO: Build admin option controls

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

		echo '.container { width: ' . $this->rwd_width . '%; margin: 0 auto; }';
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
	 * Outputs margin + padding rules
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

	/*
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
			'tiny'	=> array(
							'def'	=> 'rwd-tiny',
							'max'	=> 479,
							'units'	=> 'px',
							'note'	=> 'Tiny screens - rwd-tiny (should hit most portrait phones)'
						),
			'small'		=> array(
							'def'	=> 'rwd-small',
							'min'	=> 480,
							'max'	=> 760,
							'units'	=> 'px',
							'note'	=> 'Small screens - rwd-small (should hit most landscape phones)'
						),
			'medium'		=> array(
							'def'	=> 'rwd-medium',
							'min'	=> 761,
							'max'	=> 1024,
							'units'	=> 'px',
							'note'	=> 'Medium screens - rwd-medium (Should hit most portrait tablets)'
						),
			'large'		=> array(
							'def'	=> 'rwd-large',
							'min'	=> 1025,
							'max'	=> 1440,
							'units'	=> 'px',
							'note'	=> 'Large screens - rwd-large (Should hit most standard desktops and landscape tablets)'
						),
			'xlarge'		=> array(
							'def'	=> 'rwd-xlarge',
							'min'	=> 1441,
							'units'	=> 'px',
							'note'	=> 'Extra large screens - rwd-xlarge (Should hit high-res larger devices)'
						)
		);

		// Array of just definitions - used for -hide-except rules
		$all_defs = array();
		foreach ($sizes as $size) {
			$all_defs[] = $size['def'];
		}

		foreach ( $sizes as $size ) {

			// Units are only ever 2 characters...right?
			$units = (!$size[units] && $size[units] == 'px') ? 'px' : substr($size[units], 0, 2);
			$min = (!$size[min] && !is_numeric($size[min])) ? '' : 'and ( min-width:' . $size[min] . $units . ' )';
			$max = (!$size[max] && !is_numeric($size[max])) ? '' : 'and ( max-width:' . $size[max] . $units . ' )';
			$size_queries = ( !empty($min) && !empty($max) ) ? $min . ' ' . $max : $min . $max;
			$hider_val = max($size[min], $size[max]) + 1;

			echo '/* ' . $size['note'] . ' */' . "\n"
			. '@media screen ' . $size_queries . ' {' . "\n"
			. "  ." . $size['def'] . '-hide { display: none; }' . "\n";

			echo '}'. "\n\n";

			echo '/* Hiders */' . "\n"
			. '@media screen and ( min-width:' . $hider_val . $units . ' ) {' . "\n";

			//Use $all_defs to build -hide-except rules
			foreach ($all_defs as $a_def) {
				echo ( $a_def != $size['def'] ) ? "  ." . $a_def . '-only { display: none; }' . "\n" : '';
			}

			echo '}'. "\n\n";

		}

	}

}

?>