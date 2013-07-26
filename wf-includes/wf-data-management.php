<?php
/**
 * Wonderflux data management functions
 * @since 1.1
 */
class wflux_data_manage {

	/**
	 * Returns array of common layout tags to be used with kses or similar
	 *
	 * @params none
	 * @filter 'wflux_allowed_tags' to mainpulate allowed tags
	 *
	 * @lastupdate 1.1
	 * @since 1.1
	 * @return array
	 *
	 * TODO: Expand $type to allow for finer grain control over allowed tags
	 * TODO: TEST! Are there more tags that should be included?
	 */
	function wf_allowed_tags($args){

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
	 * Strips white space in html type output
	 * DOES NOT sanitise $input!
	 * Thanks to http://stackoverflow.com/questions/6225351/how-to-minify-php-page-html-output
	 *
	 * @params input (string)
	 *
	 * @lastupdate 1.1
	 * @since 1.1
	 * @return array
	 *
	 * TODO: TEST MORE!
	 */
	function wf_strip_whitespace($input){
		return preg_replace( array( '/ {2,}/', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'), array(' ',''), $input);
	}


}
?>