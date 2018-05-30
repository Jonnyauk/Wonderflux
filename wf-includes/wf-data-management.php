<?php
/**
 * Wonderflux data management functions
 * @since 1.1
 */
class wflux_data_manage {


	/**
	 * Returns array of common HTML tags to be used with kses or similar.
	 * You shouldn't use wp_kses() much - it can be a-little intensive!
	 * However, sometimes we need it to clean user input to only allow certain tags so there is no funny business!
	 *
	 * Filters available:
	 * wflux_allowed_tags - Array containing allowed tags
	 *
	 * @since	1.1
	 * @version	1.1
	 *
	 * @param	none
	 * @return	[array]					Allowed tags
	 */
	function wf_allowed_tags(){

		$allowed = array(
			'address' => array(),
			'a' => array(
				'class' => true,
				'href' => true,
				'id' => true,
				'title' => true,
				'rel' => true,
				'rev' => true,
				'name' => true,
				'target' => true
			),
			'abbr' => array(
				'class' => true,
				'title' => true
			),
			'acronym' => array(
				'title' => true
			),
			'article' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'aside' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'b' => array(),
			'big' => array(),
			'blockquote' => array(
				'id' => true,
				'cite' => true,
				'class' => true,
				'lang' => true,
				'xml:lang' => true
			),
			'br' => array (
				'class' => true
			),
			'button' => array(
				'disabled' => true,
				'name' => true,
				'type' => true,
				'value' => true
			),
			'caption' => array(
				'align' => true,
				'class' => true
			),
			'cite' => array (
				'class' => true,
				'dir' => true,
				'lang' => true,
				'title' => true
			),
			'code' => array (
				'style' => true
			),
			'col' => array(
				'align' => true,
				'char' => true,
				'charoff' => true,
				'span' => true,
				'dir' => true,
				'style' => true,
				'valign' => true,
				'width' => true
			),
			'del' => array(
				'datetime' => true
			),
			'dd' => array(),
			'details' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'open' => true,
				'style' => true,
				'xml:lang' => true
			),
			'div' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'dl' => array(),
			'dt' => array(),
			'em' => array(),
			'fieldset' => array(),
			'figure' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'figcaption' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'font' => array(
				'color' => true,
				'face' => true,
				'size' => true
			),
			'footer' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'form' => array(
				'action' => true,
				'accept' => true,
				'accept-charset' => true,
				'enctype' => true,
				'method' => true,
				'name' => true,
				'target' => true
			),
			'h1' => array(
				'align' => true,
				'class' => true,
				'id'    => true,
				'style' => true
			),
			'h2' => array (
				'align' => true,
				'class' => true,
				'id'    => true,
				'style' => true
			),
			'h3' => array (
				'align' => true,
				'class' => true,
				'id'    => true,
				'style' => true
			),
			'h4' => array (
				'align' => true,
				'class' => true,
				'id'    => true,
				'style' => true
			),
			'h5' => array (
				'align' => true,
				'class' => true,
				'id'    => true,
				'style' => true
			),
			'h6' => array (
				'align' => true,
				'class' => true,
				'id'    => true,
				'style' => true
			),
			'header' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'hgroup' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'hr' => array (
				'align' => true,
				'class' => true,
				'noshade' => true,
				'size' => true,
				'width' => true
			),
			'i' => array(),
			'img' => array(
				'alt' => true,
				'align' => true,
				'border' => true,
				'class' => true,
				'height' => true,
				'hspace' => true,
				'longdesc' => true,
				'vspace' => true,
				'src' => true,
				'style' => true,
				'width' => true
			),
			'ins' => array(
				'datetime' => true,
				'cite' => true
			),
			'kbd' => array(),
			'label' => array(
				'for' => true
			),
			'legend' => array(
				'align' => true
			),
			'li' => array (
				'align' => true,
				'class' => true
			),
			'menu' => array (
				'class' => true,
				'style' => true,
				'type' => true
			),
			'nav' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'p' => array(
				'class' => true,
				'align' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'pre' => array(
				'style' => true,
				'width' => true
			),
			'q' => array(
				'cite' => true
			),
			's' => array(),
			'span' => array (
				'class' => true,
				'dir' => true,
				'align' => true,
				'lang' => true,
				'style' => true,
				'title' => true,
				'xml:lang' => true
			),
			'section' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'strike' => array(),
			'strong' => array(),
			'sub' => array(),
			'summary' => array(
				'align' => true,
				'class' => true,
				'dir' => true,
				'lang' => true,
				'style' => true,
				'xml:lang' => true
			),
			'sup' => array(),
			'table' => array(
				'align' => true,
				'bgcolor' => true,
				'border' => true,
				'cellpadding' => true,
				'cellspacing' => true,
				'class' => true,
				'dir' => true,
				'id' => true,
				'rules' => true,
				'style' => true,
				'summary' => true,
				'width' => true
			),
			'tbody' => array(
				'align' => true,
				'char' => true,
				'charoff' => true,
				'valign' => true
			),
			'td' => array(
				'abbr' => true,
				'align' => true,
				'axis' => true,
				'bgcolor' => true,
				'char' => true,
				'charoff' => true,
				'class' => true,
				'colspan' => true,
				'dir' => true,
				'headers' => true,
				'height' => true,
				'nowrap' => true,
				'rowspan' => true,
				'scope' => true,
				'style' => true,
				'valign' => true,
				'width' => true
			),
			'textarea' => array(
				'cols' => true,
				'rows' => true,
				'disabled' => true,
				'name' => true,
				'readonly' => true
			),
			'tfoot' => array(
				'align' => true,
				'char' => true,
				'class' => true,
				'charoff' => true,
				'valign' => true
			),
			'th' => array(
				'abbr' => true,
				'align' => true,
				'axis' => true,
				'bgcolor' => true,
				'char' => true,
				'charoff' => true,
				'class' => true,
				'colspan' => true,
				'headers' => true,
				'height' => true,
				'nowrap' => true,
				'rowspan' => true,
				'scope' => true,
				'valign' => true,
				'width' => true
			),
			'thead' => array(
				'align' => true,
				'char' => true,
				'charoff' => true,
				'class' => true,
				'valign' => true
			),
			'title' => array(),
			'tr' => array(
				'align' => true,
				'bgcolor' => true,
				'char' => true,
				'charoff' => true,
				'class' => true,
				'style' => true,
				'valign' => true
			),
			'tt' => array(),
			'u' => array(),
			'ul' => array (
				'class' => true,
				'style' => true,
				'type' => true
			),
			'ol' => array (
				'class' => true,
				'start' => true,
				'style' => true,
				'type' => true
			),
			'var' => array()
		);

		return apply_filters( 'wflux_allowed_tags', $allowed );

	}


	/**
	 * Returns array of limited HTML tags to be used with kses or similar.
	 * You shouldn't use wp_kses() much - it can be a-little intensive!
	 * However, sometimes we need it to clean user input to only allow certain tags so there is no funny business!
	 *
	 * @since	2.3
	 * @version	2.6
	 *
	 * @param	[string] $type 			Required - Type of tags to return text/simple/headings [text]
	 *                          		- text     		=> Sutable for wrapping inside your own block level elements - a, br, span, b, strong, i, sup, sub
	 *                          		- textnolinks	=> Similar to 'text' param, but no links. Sutable for wrapping inside your own block level elements - br, span, b, strong, i, sup, sub
	 *                          		- simple   		=> Similar to 'text' param, much more limited, no links or text styling tags = span (only class and ID allowed), br
	 *                          		- headings 		=> Just headings, nothing else = h1, h2, h3, h4, h5, h6
	 * @return	[array]					Allowed tags
	 */
	function wf_allowed_simple_tags( $input='text' ) {

		// Default is first in array
		$types = array(
			'text',
			'textnolinks',
			'simple',
			'headings'
		);

		$input = ( !isset($input) ) ? $types[0] : $input;

		$type = ( !in_array($input, $types) ) ? $types[0] : $input;

		switch ( $type ) {

			case 'simple':

				$output = array (
					'span' => array(
						'class'=>array(),
						'id'=>array()
					),
					'br' => array()
				);

			break;

			case 'headings':

				$output = array (
					'h1' => array(
						'align' => true,
						'class' => true,
						'id'    => true,
						'style' => true
					),
					'h2' => array (
						'align' => true,
						'class' => true,
						'id'    => true,
						'style' => true
					),
					'h3' => array (
						'align' => true,
						'class' => true,
						'id'    => true,
						'style' => true
					),
					'h4' => array (
						'align' => true,
						'class' => true,
						'id'    => true,
						'style' => true
					),
					'h5' => array (
						'align' => true,
						'class' => true,
						'id'    => true,
						'style' => true
					),
					'h6' => array (
						'align' => true,
						'class' => true,
						'id'    => true,
						'style' => true
					)
				);

			break;

			case 'textnolinks':

				// text
				$output = array(
					'span' => array(
						'class'=>array(),
						'id'=>array(),
						'style'=>array()
					),
					'br' => array(),
					'b' => array(),
					'strong' => array(),
					'i' => array(),
					'sup' => array(),
					'sub' => array()
				);

			break;

			default:

				// text
				$output = array(
					'a' => array(
						'href'=> array(),
						'title'=> array(),
						'class'=>array(),
						'id'=>array(),
						'style'=>array()
					),
					'span' => array(
						'class'=>array(),
						'id'=>array(),
						'style'=>array()
					),
					'br' => array(),
					'b' => array(),
					'strong' => array(),
					'i' => array(),
					'sup' => array(),
					'sub' => array()
				);

			break;

		}

		return $output;

	}


	/**
	 * Strips white space and other cruft in html type output
	 *
	 * DOES NOT sanitise $input!
	 *
	 * @since	1.1
	 * @version	1.1
	 *
	 * @param	[int] $input 		HTML imput
	 * @return	[string]			Cleaned-up HTML output
	 */
	function wf_strip_whitespace($input){
		return preg_replace( array( '/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'), array(' ',''), $input);
	}


	/**
	 * Basic(ish) URL validation
	 *
	 * @since	2.2
	 * @version	2.2
	 *
	 * @param	[string] $input 		URL
	 * @return	[mixed]					Input URL/false
	 */
	function wf_valid_url($input){

		$input = trim( filter_var($input, FILTER_SANITIZE_STRING) );
		return ( substr($input, 0, 4) == 'http' ) ? filter_var( $input, FILTER_VALIDATE_URL ) : false;

	}


	/**
	 * Check if input is valid 6 character hexidecimal colour value (plus additional #)
	 * Output escaping example $bg_colour = strip_tags( stripslashes( $bg_colour ) );
	 *
	 * @since	2.2
	 * @version	2.2
	 *
	 * @param	[string] $input 		Text string including #
	 * @return	[bool]					true/false
	 */
	function wf_valid_hex_colour($input){

		return ( preg_match( '/^#[a-f0-9]{6}$/i', $input ) ) ? true : false;

	}


	/**
	 * Check if input starts with a string
	 *
	 * @since	2.2
	 * @version	2.2
	 *
	 * @param	[string] $needle 		String to search for
	 * @param	[string] $haystack 		Input
	 * @return	[bool]					true/false
	 */
	function wf_starts_with($needle, $haystack){

	    return $needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false;

	}


	/**
	 * Check if input ends with a string
	 *
	 * @since	2.2
	 * @version	2.2
	 *
	 * @param	[string] $needle 		String to search for
	 * @param	[string] $haystack 		Input
	 * @return	[bool]					true/false
	 */
	function wf_ends_with($needle, $haystack){

		return $needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);

	}


}
?>