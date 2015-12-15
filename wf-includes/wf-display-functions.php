<?php
/**
 * Core display functions that output code.
 *
 * @since	0.913
 * @version	2.0
 *
 * @todo Check translation setup.
 */
class wflux_display_code extends wflux_data {

	protected $xml_namespaces;
	protected $head_classes;
	protected $post_classes;
	protected $lang_attributes;

	function __construct() {
		parent::__construct();
		$this->xml_namespaces = array(); // Holds all XML namespaces to build into head
		$this->head_classes = array(); // Holds all additional classes that will be added to head
		$this->post_classes = array(); // Holds all additional classes that will be added to post class
		$this->lang_attributes = ''; // Holds all additional attributes added to <html>

	}

	/**
	 * Builds the start of the head with doc type declaration.
	 *
	 * @since	0.931
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_open() {

		$doctype = $this->wfx_doc_type;
		$lang = $this->wfx_doc_lang;
		$content = 'html';

		// Language code
		if ( $lang !='en' || strlen( trim($lang) ) < 6 ): $lang_output = sanitize_key($lang);
		else: $lang_output = 'en';
		endif;

		$lang_extra = ( $doctype == 'XHTML/RDFa' ) ? '' : 'lang="'.$lang_output.'" ';

		$this->xml_namespace_build();
		$namespaces = '';
		foreach ( $this->xml_namespaces as $key=>$value ) {
			$namespaces .= $value . ' ';
		}

		// Document type
		switch ($doctype) {

			case 'transitional':
				$doctype_output = 'XHTML 1.0 Transitional';
				$doctype_link_output = 'TR/xhtml1/DTD/xhtml1-transitional'; break;
			case 'strict':
				$doctype_output = 'XHTML 1.0 Strict';
				$doctype_link_output = 'TR/xhtml1/DTD/xhtml1-strict';
			break;
			case 'frameset':
				$doctype_output = 'XHTML 1.0 Frameset';
				$doctype_link_output = 'TR/xhtml1/DTD/xhtml1-frameset';
			break;
				case '1.1':
				$doctype_output = 'XHTML 1.1';
				$doctype_link_output = 'TR/xhtml11/DTD/xhtml11';
			break;
			case '1.1basic':
				$doctype_output = 'XHTML Basic 1.1';
				$doctype_link_output = 'TR/xhtml-basic/xhtml-basic11';
			break;
			case 'XHTML/RDFa':
				$doctype_output = 'XHTML+RDFa 1.0';
				$doctype_link_output = 'MarkUp/DTD/xhtml-rdfa-1';
			break;
			default:
				$doctype_output = '1.0 Transitional';
				$doctype_link_output = 'TR/xhtml1/DTD/xhtml1-tansitional';
			break;

		}

		if ($doctype == 'html5'):

			$output = '<!DOCTYPE html>' . "\n" . '<html lang="'.$lang_output.'">' . "\n";

		else:

			// Content type
			if ($content !='html' || strlen($content) <= 4) :
				$content_output = sanitize_key($content, '');
			else: $content_output = 'html'; endif;

			$output = '<!DOCTYPE ' . 'html PUBLIC "-//W3C//DTD ' . $doctype_output . '//'
			. strtoupper($lang_output) . '" "http://www.w3.org/' . $doctype_link_output . '.dtd">';

		endif;

		// Add data for $output already built & cleaned
		echo $output;

		$this->lang_attributes = wp_kses( apply_filters('wflux_language_attributes', 'xml:lang="'.$lang_output.'" ' . $lang_extra . $namespaces), '' );

		// Filter core WordPress language attributes
		add_filter( 'language_attributes', array($this, 'wf_lang_attributes_filter') );

	}


	/**
	 * Internal function used in wf_head_open() to filter language_attributes.
	 * Note - data already checked/filtered on output $this->lang_attributes set in wf_head_open()
	 *
	 * @since	0.931
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_lang_attributes_filter($attributes){
		return $this->lang_attributes;
	}


	/**
	* Builds array of XML namespace attributes to pass to wf_head_meta_xml_namespace
	* @filter wflux_head_meta_xml_namespace_main - Full output of default XML namespace definition.
	*
	* @since 0.931
	* @updated 0.931
	*/
	function xml_namespace_build() {
		//$this->xml_namespaces[] = wp_strip_all_tags($name);
		// Default namespace
		$this->xml_namespaces[] = apply_filters( 'wflux_head_meta_xml_namespace_main', 'xmlns="http://www.w3.org/1999/xhtml"' );
		// If using Facebook need Open Graph AND Facebook namespace definitions
		if ( $this->wfx_doc_type == 'XHTML/RDFa' ):
			$this->xml_namespaces[] = 'xmlns:og="http://ogp.me/ns#"';
			$this->xml_namespaces[] = 'xmlns:fb="http://www.facebook.com/2008/fbml"';
		endif;
	}


	/**
	 * Inserts the Content-Type/charset meta tag.
	 *
	 * @since	0.931
	 * @version	0.931
	 *
	 * @param	[string] $doctype 		Document type transitional/strict/frameset/1.1/1.1basic/html5 [transitional]
	 * @param	[string] $content 		Document content [html]
	 * @param	[string] $charset		Character encoding [utf8]
	 */
	function wf_head_char_set($args) {

		$defaults = array (
			'doctype' => $this->wfx_doc_type,
			'content' => 'html',
			'charset' => $this->wfx_doc_charset
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Character set
		$charset_output = wp_kses($charset, '');

		// If its HTML 5 it's simple output
		if ($doctype == 'html5'):
			$output = "\n".'<meta charset="'.$charset_output.'" />' . "\n";
		else:
			$content_output = ( strlen($content) <= 4 ) ? strtolower(wp_kses($content, '')) : 'html';
			$output ="\n";
			$output .= '<meta http-equiv="Content-Type" content="text/'.$content_output.'; charset='.$charset_output.'" />';
			$output .="\n";
		endif;

		echo $output;
	}


	/**
	 * Inserts viewport meta tag.
	 * Only deployed if you are using new % based layout system (Flux Layout).
	 *
	 * Filters available:
	 * wflux_head_viewport - Viewport meta content.
	 *
	 * @since	2.0
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_viewport() {

		// Backpat - dont use for Wonderflux 1 pixel grid
		// Viewport meta is no bueno if its not responsive
		if ( $this->wfx_grid_type == 'percent' ) {
			echo '<meta name="viewport" content="'
			. esc_attr( apply_filters( 'wflux_head_viewport', 'width=device-width, height=device-height, initial-scale=1' ) )
			. '" />' . "\n";
		}
	}


	/**
	 * Builds the title in the head of the document.
	 * BACKPAT: When using WordPress 4.1 or above add_theme_support( 'title-tag' ) is automatically used instead.
	 *
	 * @since	0.1
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_title($args) {

		// Backpat < WordPress 4.1
		// This is replaced with add_theme_support( 'title-tag' )
		// Code is untidy but requried to pass WordPress core theme check - reported but wont be resolved sadly.
		// See report and response: https://wordpress.org/support/topic/too-aggressive-check-for-wp_title
		if ( !function_exists( '_wp_render_title_tag' ) ) {
			?>
			<title><?php wp_title( '|', true, 'right' ); ?></title>
			<?php
		}

	}


	/**
	 * Inserts the core structure static CSS.
	 * Switches between old pixel based and new Flux Layout systems.
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to remove.
	 *
	 * Filters available:
	 * wflux_css_structure_path - full path to file
	 * wflux_css_structure_version - version number appended to file
	 * wflux_css_structure_id - ID of file
	 *
	 * @since	0.72
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_css_structure() {

		if ( WF_THEME_FRAMEWORK_REPLACE == false ) {

			// Backpat - switch source file for core CSS framework
			$file = ( $this->wfx_grid_type == 'percent' ) ? 'wf-css-flux-layout-core.css' : 'wf-css-core-structure.css';

			$path = WF_CONTENT_URL . '/css/' . $file;
			$version = null;
			$id = 'wfx-structure';

			// Allow filtering
			$path = apply_filters( 'wflux_css_structure_path', $path );
			$version = apply_filters( 'wflux_css_structure_version', $version );
			$id = apply_filters( 'wflux_css_structure_id', $id );

			wp_register_style( $id, $path, '', $version, 'screen, projection' );
			wp_enqueue_style( $id );

		}

	}


	/**
	 * Inserts the core structure dynamic layout CSS.
	 * Switches between old pixel based and new Flux Layout systems.
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to remove.
	 *
	 * Filters available:
	 * wflux_css_columns_path - full path to file
	 * wflux_css_columns_version - version number appended to file
	 * wflux_css_columns_id - ID of file
	 * wflux_css_columns_media - Media type
	 *
	 * @since	0.72
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_css_columns() {
		if (WF_THEME_FRAMEWORK_REPLACE == false) {

			/* Backpat - switch grid generator source file */
			$file = ($this->wfx_grid_type == 'percent') ? 'flux-layout' : 'dynamic-columns';

			$path = WF_CONTENT_URL . '/css/wf-css-' . $file . '.php';
			$version = 'wfx-dynamic';
			$id = 'wfx-columns';
			$media = 'screen, projection';

			// Allow filtering
			$path = apply_filters( 'wflux_css_columns_path', $path );
			$version = apply_filters( 'wflux_css_columns_version', $version );
			$id = apply_filters( 'wflux_css_columns_id', $id );
			$media = apply_filters( 'wflux_css_columns_media', $media );
			wp_register_style( $id, $path,'', $version, $media );
			wp_enqueue_style( $id );

			// IMPORTANT - Append layout arguments to url
			add_filter( 'style_loader_tag', array($this,'wf_head_css_add_args') );
		}
	}


	/**
	 * Inserts pixel based legacy Internet Explorer CSS (<IE8).
	 * Only deployed if you are using old pixel based layout system.
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to remove.
	 * Legacy function - deprecated in Wonderflux 2.0, will likely be removed in the future.
	 *
	 * Filters available:
	 * wflux_css_ie_path - full path to file
	 * wflux_css_ie_id - ID of file
	 * wflux_css_ie_media - Media type
	 *
	 * @since	0.72
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_css_ie() {

		// Backpat - legacy Wonderflux v1 IE additional fallback dynamic grid
		if ( WF_THEME_FRAMEWORK_REPLACE == false && $this->wfx_grid_type == 'pixels' ) {

			$path = WF_CONTENT_URL . '/css/wf-css-dynamic-core-ie.php';
			$version = 'wfx-dynamic';
			$id = 'wfx-ie';
			$media = 'screen, projection';

			// Allow filtering
			$path = apply_filters( 'wflux_css_ie_path', $path );
			$id = apply_filters( 'wflux_css_ie_id', $id );
			$media = apply_filters( 'wflux_css_ie_media', $media );

			wp_register_style( $id, $path,'', $version, $media );
			wp_enqueue_style( $id );

			// IMPORTANT - Add conditional IE wrapper
			$GLOBALS['wp_styles']->add_data( 'wfx-ie', 'conditional', 'lt IE 8' );

		}

	}


	/**
	 * Inserts (enqueue) child theme CSS - style.css
	 * BACKPAT: When using WordPress 4.1 or above add_theme_support( 'title-tag' ) is automatically used instead.
	 *
	 * Filters available:
	 * wflux_css_theme_id - ID of file (main-theme)
	 * wflux_css_theme_path - full path to file
	 * wflux_css_theme_media - Media type
	 *
	 * @since	0.72
	 * @version	1.1
	 *
	 * @param	none
	 */
	function wf_head_css_theme() {
		// Allow filtering
		$id = apply_filters( 'wflux_css_theme_id', 'main-theme' );
		$path = apply_filters( 'wflux_css_theme_path', WF_THEME_URL.'/style.css' );
		$media = apply_filters( 'wflux_css_theme_media', 'screen, projection' );

		wp_register_style( $id, $path, '', $this->wfx_mytheme_version_clean, $media );
		wp_enqueue_style( $id );
	}


	/**
	* Inserts theme CSS sizing parameters
	* Settings can be filtered - see wflux_data class
	* Picks up on version set as 'ver=wfx-dynamic':
	* wp_register_style( '', '','','wfx-dynamic','');
	* Appends Wonderflux size URL params
	*
	* @since 0.93
	* @updated 2.0
	*/
	function wf_head_css_add_args($input) {

		// Backpat - config for grid types
		if ( $this->wfx_grid_type == 'percent' ){

			$vars = '&amp;w=' . $this->wfx_width
			. '&amp;wu=' . $this->wfx_width_unit
			. '&amp;p=' . $this->wfx_position
			. '&amp;sbp=' . $this->wfx_sidebar_primary_position
			. '&amp;c=' . $this->wfx_columns;

		} else {

			$vars = '&amp;w=' . $this->wfx_width
			. '&amp;p=' . $this->wfx_position
			. '&amp;sbp=' . $this->wfx_sidebar_primary_position
			. '&amp;cw=' . $this->wfx_columns_width
			. '&amp;c=' . $this->wfx_columns;

		}

		return str_replace(array('ver=wfx-dynamic'), array("$vars"), $input);

	}


	/**
	 * Replaces framework CSS files (core and dynamic layout system).
	 * Set constant WF_THEME_FRAMEWORK_REPLACE to true to use.
	 * Use this to optimise your site - once you have your layout generated you are unlikely to need to change it usually!
	 *
	 * Create the following files in your child theme folder (see Wonderflux Advanced tab to generate output):
	 * - For pixel based system - 'style-framework.css' and optionally 'style-framework-ie.css'.
	 * - For % based system (Flux Layout) - 'flux-layout-merged.css'.
	 * If the file exists in your child theme, it will then be added (registered and enqueued) automatically - cool!
	 *
	 * Filters available:
	 * wflux_css_theme_framework_media - Media type
	 *
	 * @since	0.93
	 * @version	2.0
	 *
	 * @param	none
	 */
	function wf_head_css_replace() {
		$path = ( $this->wfx_grid_type == 'pixels' ) ? WF_THEME_URL.'/style-framework.css' : WF_THEME_URL.'/flux-layout-merged.css';
		$path_ie = WF_THEME_URL.'/style-framework-ie.css';
		$version = $this->wfx_mytheme_version_clean;
		$id = 'framework';
		$id_ie = 'framework-ie';
		$media = 'screen, projection';

		// Allow filtering
		$media = apply_filters( 'wflux_css_theme_framework_media', $media );

		wp_register_style( $id, $path,'', $version, $media );
		wp_enqueue_style( $id );

		// Only add IE specific CSS if using Wonderflux v1.0 pixel grid system
		if ( $this->wfx_grid_type == 'pixels' ) {
			wp_register_style( $id_ie, $path_ie,'', $version, $media );
			wp_enqueue_style( $id_ie );
			// IMPORTANT - Add conditional IE wrapper
			$GLOBALS['wp_styles']->add_data( $id_ie, 'conditional', 'lt IE 8' );
		}
	}


	/**
	 * Adds extra CSS classes to the body tag via WordPress filter.
	 * Classes added describe your Wonderflux layout config, basic mobile and browser detection.
	 * Add more classes by using core WordPress filter 'body_class' or override whole function.
	 *
	 * Filters available:
	 * wflux_body_class_browser - Browser detection CSS class output
	 * wflux_body_class_layout - Wonderflux layout description classes
	 *
	 * @since	0.931
	 * @version	2.1
	 *
	 * @param	none
	 */
	function wf_body_tag() {

		// Setup using WordPress standard browser detection globals - fairly basic
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		$browser = 'browser-';

		$is_mobile = ( wp_is_mobile() ) ? ' is-mobile' : ' is-not-mobile';

		switch (TRUE){
			case $is_lynx: $browser .= 'lynx'; break;
			case $is_gecko: $browser .= 'gecko'; break;
			case $is_IE: $browser .= 'ie'; break;
			case $is_opera: $browser .= 'opera'; break;
			case $is_NS4: $browser .= 'netscape-4'; break;
			case $is_iphone: $browser .= 'iphone'; break;
			case $is_safari: $browser .= 'safari'; break;
			case $is_chrome: $browser .= 'chrome'; break;
			default: $browser .= 'browser-not-defined'; break;
		}

		$this->head_classes[] = apply_filters( 'wflux_body_class_browser', esc_attr($browser . $is_mobile) );

		// Setup additional layout classes
		$layout_classes = array();
		$layout_classes[] = ( $this->wfx_sidebar_1_display == 'Y' ) ? 'content-with-sidebar-1' : 'content-no-sidebar-1';
		$layout_classes[] = ( $this->wfx_sidebar_1_display == 'Y' && $this->wfx_sidebar_primary_position == 'left' ) ? 'sidebar-1-left' : '';
		$layout_classes[] = ( $this->wfx_sidebar_1_display == 'Y' && $this->wfx_sidebar_primary_position == 'right' ) ? 'sidebar-1-right' : '';
		$layout_classes[] = ' width-'.$this->wfx_width;

		// Split back to string for use in wf_body_tag_filter
		$layout_classes_str = '';
		foreach ( apply_filters( 'wflux_body_class_layout', $layout_classes ) as $class ) {
			$layout_classes_str .= $class;
		}

		$this->head_classes[] = $layout_classes_str;

		// Put it all together and filter body_class
		add_filter( 'body_class', array($this, esc_attr('wf_body_tag_filter')) );

	}


	/**
	 * @since 0.931
	 * @updated 1.2
	 * Used in wf_body_tag() to filter body_class
	 */
	function wf_body_tag_filter( $classes ) {

		// Add browser info to start of body class
		array_unshift( $classes, $this->head_classes[0] );
		// Add additional layout classes
		$classes[] = $this->head_classes[1];

		return $classes;

	}


	/**
	 * A more flexible post_class() function.
	 * DEPRECIATED - to be removed - use standard WordPress post_class() instead in your template files!
	 * See wfx_filter_post_class - which filters WP post_class() instead!
	 *
	 * NOTES on 'wflux_post_class' filter:
	 * Use $post_class var in your filter function if you want access to core WP post classes.
	 * You can then do things like:
	 * unset($post_class[0]) Remove an item from the array (where [0] is the index/key in the array of WP class values)
	 * $post_class[] = 'my-new-class' Add an item to the array (Can also be done with the $extra param in function if required - which is simpler!)
	 *
	 * Filters available:
	 * wflux_body_class_browser - Browser detection CSS class output
	 * wflux_body_class_layout - Wonderflux layout description classes
	 *
	 * @since	1.0RC3
	 * @version	2.1
	 *
	 * @param	[string] $extra 		Comma seperated, extra CSS classes you wish to add
	 * @param	[string] $position		Position of your additional $extra CSS classes. after/before [after]
	 * @param	[string] $just_string 	Just string of classes or wrap the output in 'class=""' like normal WordPress? Y/N [N]
	 */
	function wf_post_class( $args ) {

		$defaults = array (
			'extra' => '',
			'position' => 'after',
			'just_string' => 'N'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		$extra = ( !empty($extra) ) ? wp_kses_data($extra, '' ) : '';

		global $post;
		global $wp_query;

		$post_class = apply_filters( 'wflux_post_class', get_post_class( '', $post->ID) );

		$post_class_out = '';
		$pc_count = 1;
		$pc_total = count( $post_class );

		if ( is_array( $post_class ) ){
			foreach ( $post_class as $value ) {
				$post_class_out .= $value;
				if ( $pc_count < $pc_total ) $post_class_out .= ' ';
				$pc_count++;
			}
		} else {
			$post_class_out = $post_class;
		}

		if ( !is_singular() ){
			$post_class_out = $post_class_out . ' ' . apply_filters( 'wflux_post_class_multiple', 'multiple-posts' );
			$post_class_out = ( $wp_query->current_post % 2 == 0 ) ? $post_class_out . ' archive-row-even' : $post_class_out . ' archive-row-odd';
			$post_class_out = $post_class_out . ' ' . 'paged-return-' . $wp_query->current_post;
			$post_class_out = ( $wp_query->current_post != 0 ) ? $post_class_out : $post_class_out . ' ' . apply_filters( 'wflux_post_class_first', 'first-in-loop' );
			$post_class_out = ( ($wp_query->current_post+1) != $wp_query->post_count ) ? $post_class_out : $post_class_out . ' ' . apply_filters( 'wflux_post_class_last', 'last-in-loop' );
		} else {
			$post_class_out = $post_class_out . ' ' . apply_filters( 'wflux_post_class_single', 'single-post' );
		}

		if (!empty($extra)){
			$post_class_out = ( $position == 'before' ) ? $extra . ' ' . $post_class_out : $post_class_out . ' ' . $extra;
		}

		return ( $just_string == 'N' ) ? 'class="' . $post_class_out . '"': $post_class_out;

	}


	/**
	 * Adds extra CSS classes to post class via WordPress filter.
	 * IMPORTANT - Stop using wfx_post_class() it in your child themes!!
	 *
	 * Filters available:
	 * wflux_post_class_single : Extra CSS class added to a single post view
	 * wflux_post_class_multiple : Extra CSS class added to multiple post/archive views
	 * wflux_post_class_first : Extra CSS class added to first post in loop
	 * wflux_post_class_last : Extra CSS class added to first post in loop
	 *
	 * @since	2.1
	 * @version	2.1
	 *
	 * @param	none
	 */
	function wf_filter_post_class() {

		global $wp_query;

		unset( $this->post_classes );

		if ( !is_singular() ){
			$this->post_classes[] = apply_filters( 'wflux_post_class_multiple', 'multiple-posts' );
			$this->post_classes[] = ( $wp_query->current_post % 2 == 0 ) ? 'archive-row-odd' : 'archive-row-even';
			$this->post_classes[] = 'paged-return-' . ( intval($wp_query->current_post) + 1 );
			$this->post_classes[] = ( $wp_query->current_post != 0 ) ? '' : apply_filters( 'wflux_post_class_first', 'first-in-loop' );
			$this->post_classes[] = ( ($wp_query->current_post +1 ) != $wp_query->post_count ) ? '' : apply_filters( 'wflux_post_class_last', 'last-in-loop' );
		} else {
			$this->post_classes[] = apply_filters( 'wflux_post_class_single', 'single-post' );
		}

		// Put it all together and filter body_class
		add_filter( 'post_class', array($this, esc_attr('wf_filter_post_class_do')) );

	}


	/**
	 * @since 0.931
	 * @updated 2.2
	 * Used in wf_body_tag() to filter body_class
	 */
	function wf_filter_post_class_do( $classes ) {

		// Clean up and merge our extra CSS post classes
		$classes = ( !empty($this->post_classes) ) ? array_merge( $classes, array_filter($this->post_classes) ) : $classes;
		return $classes;

	}


	/**
	 * Displays performance information as a HTML code comment: xx queries in xx seconds
	 *
	 * @since	0.3
	 * @version	0.931
	 *
	 * @param	none
	 *
	 * @todo Extend with other debug information? wfx_debug() is more useful I guess for this?
	 */
	function wf_performance() {
		echo '<!-- ' . sprintf( __( '%1$s queries in %2$s seconds', 'wonderflux' ), get_num_queries(), timer_stop($display = 0, $precision = 4) ) . ' -->'."\n";
	}


	/**
	 * Output footer HTML code credit comment.
	 *
	 * Filters available:
	 * wflux_comment_code_credit : Text inside code credit
	 *
	 * @since	0.71
	 * @version	2.1
	 *
	 * @param	none
	 */
	function wf_code_credit() {
		echo "\n" . '<!-- ' . apply_filters( 'wflux_comment_code_credit', esc_html__('Powered by WordPress and the Wonderflux theme framework', 'wonderflux') ) . ' -->' . "\n";
	}


	/**
	 * Returns saved Wonderflux option value from main options array.
	 *
	 * @since	0.93
	 * @version	0.93
	 *
	 * @param	[string] $size			Option you wish to return. site-width/columns/column-width/sidebar-1-position [site-width]
	 *
	 * @todo 	Build rest of output options.
	 */
	function wf_get_dimensions($size) {
		switch ($size) {
			case 'site-width': return $this->wfx_width;
			case 'columns': return $this->wfx_columns;
			case 'column-width': return $this->wfx_columns_width;
			case 'sidebar-1-position': return $this->wfx_sidebar_primary_position;
			default: return $this->wfx_width;
		}
	}


	/**
	 * IMPORTANT - Configures WordPress $content_width.
	 * Sets the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
	 *
	 * @since	1.1
	 * @version	1.1
	 *
	 * @param	none
	 *
	 * @todo 	Check over this functionality, Flux Layout deals with responsive content well already - it's been a while!
	 */
	function wf_content_width() {
		global $content_width;
		if ( !isset( $content_width ) ) $content_width = $this->wfx_content_size_px;
	}

}


/**
 * CSS display functions.
 *
 * @since	0.2
 * @version	2.0
 *
 */
class wflux_display_css extends wflux_display_code {

	/**
	 * Creates dynamic CSS grid class definition - used in Wonderflux pixel layout system v1.
	 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
	 * Just use the normal CSS classes created by Flux Layout - this is over-engineered!
	 *
	 * By using this function to define containers, you can dynamically resize the whole layout.
	 * The only thing to watch out for is using definitions like:
	 * - 'quarter' when your columns arent divisible by 4.
	 * - 'third' when your columns arent divisible by 3.
	 * - By checking the output of the wf_css_info() function in the head of your document, you can find out lots of useful info!
	 *
	 * @since	0.2
	 * @version	2.0
	 *
	 * @param	[string] $size			Relative size definition to full width of site - eg 'half', 'quarter'. Various values [full]
	 * @param	[string] $class			Additional CSS classes. [none]
	 * @param	[string] $id			Optional ID. [none]
	 * @param	[string] $last			Put on last container inside a row, adds .last CSS class. Y/N [N]
	 * @param	[string] $move			Push and pull a div - not used at moment.
	 * @param	[string] $divoutput		Wraps output in opening and closing div tags - useful for blocks of code. Y/N [N]
	 * @param	[int] $columns			Size of div in columns, overrides $size. [0]
	 *
	 * @todo 	Remove and move to legacy support plugin.
	 */
	function wf_css($args) {

		$defaults = array (
			'size' => 'full',
			'class' => '',
			'id' => '',
			'last' => 'N',
			'move' => '',
			'divoutput' => 'N',
			'columns' => 0
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$wf_fullwidth = $this->wfx_width;
		$wf_columns = $this->wfx_columns;
		$wf_single = $this->wfx_columns_width;

		//Last class added only if == Y
		$last = ( 0 === strcasecmp( $last, 'Y' ) ) ? ' last' : '';

		// Prepare extra CSS classes string for display if supplied
		$class_clean = ( $class !='' ) ? ' ' . wp_kses( $class, '' ) : '';

		// Prepare extra CSS id for display if supplied
		$id_clean = ( $id !='' ) ? ' id="' . wp_kses( $id, '' ) . '"' : '';

		// IMPORTANT divoutput parameter is for use in theme functions file
		// It encloses the dynamic size class in '<div' and '>' for you
		// Suggest you dont use this at get_template_part xhtml code level as you want to see clearly where the divs open and close
		// Could be used in get_template_part code blocks
		if ( 0 === strcasecmp( $divoutput, 'Y') ) {
			$divout_open_clean = '<div ';
			$divout_close_clean = '>';
		} else {
			$divout_open_clean = '';
			$divout_close_clean = '';
		}

		// BACKPAT Wonderflux 2.0 uses 'box-' Wonderflux 1.x confusing 'span-'
		$css_core_def = ( $this->wfx_grid_type == 'percent' ) ? 'box-' : 'span-';
		$css_core_def = apply_filters( 'wflux_css_definition', $css_core_def );
		$css_push_def = 'push-';
		$css_pull_def = 'pull-';

		// Pull classes - Move container blocks to the left
		//TODO: Build more!
		$wf_pull_half = ' ' . $css_pull_def . ($wf_columns/2);
		$wf_pull_third = ' ' . $css_pull_def . ($wf_columns/3);
		$wf_pull_quarter = ' ' . $css_pull_def . ($wf_columns/4);
		$wf_pull_eighth = ' ' . $css_pull_def . ($wf_columns/8);

		// Push classes - Move container blocks to the right
		//TODO: Build more!
		$wf_push_half = ' ' . $css_push_def . ($wf_columns/2);
		$wf_push_third = ' ' . $css_push_def . ($wf_columns/3);
		$wf_push_quarter = ' ' . $css_push_def . ($wf_columns/4);
		$wf_push_eighth = ' ' . $css_push_def . ($wf_columns/8);

		// Setup the core sizing CSS class
		if ( $columns == 0 || $columns == '' ) {

			switch ($size) {
				//Define $last - this must always go on the last container in a row
				case 'full' : $size_def = $wf_columns; $last = ' last'; break;
				case 'half' : $size_def = ($wf_columns/2); break;
				case 'single' : $size_def = $wf_single; break;
				//Suggest use one_third for consistency, but keep this just in-case
				case 'third' : $size_def = ($wf_columns/3); break;
				case 'one_third' : $size_def = ($wf_columns/3); break;
				case 'two_third' : $size_def = ($wf_columns/3*2); break;
				//Suggest use one_quarter for consistency, but keep this just in-case
				case 'quarter' : $size_def = ($wf_columns/4); break;
				case 'one_quarter' : $size_def = ($wf_columns/4); break;
				case 'two_quarter' : $size_def = ($wf_columns/2); break;
				case 'three_quarter' : $size_def = ($wf_columns/4*3); break;
				//Suggest use one_fifth for consistency, but keep this just in-case
				case 'fifth' : $size_def = ($wf_columns/5); break;
				case 'one_fifth' : $size_def = ($wf_columns/5); break;
				case 'two_fifth' : $size_def = ($wf_columns/5*2); break;
				case 'three_fifth' : $size_def = ($wf_columns/5*3); break;
				case 'four_fifth' : $size_def = ($wf_columns/5*4); break;
				//Suggest use one_sixth for consistency, but keep this just in-case
				case 'sixth' : $size_def = ($wf_columns/6); break;
				case 'one_sixth' : $size_def = ($wf_columns/6); break;
				case 'two_sixth' : $size_def = ($wf_columns/6*2); break;
				case 'three_sixth' : $size_def = ($wf_columns/6*3); break;
				case 'four_sixth' : $size_def = ($wf_columns/6*4); break;
				case 'five_sixth' : $size_def = ($wf_columns/6*5); break;
				//Suggest use one_seventh for consistency, but keep this just in-case
				case 'seventh' : $size_def = ($wf_columns/7); break;
				case 'one_seventh' : $size_def = ($wf_columns/7); break;
				case 'two_seventh' : $size_def = ($wf_columns/7*2); break;
				case 'three_seventh' : $size_def = ($wf_columns/7*3); break;
				case 'four_seventh' : $size_def = ($wf_columns/7*4); break;
				case 'five_seventh' : $size_def = ($wf_columns/7*5); break;
				case 'six_seventh' : $size_def = ($wf_columns/7*6); break;
				//Suggest use one_eigth for consistency, but keep this just in-case
				case 'eigth' : $size_def = ($wf_columns/8); break;
				case 'one_eigth' : $size_def = ($wf_columns/8); break;
				case 'two_eigth' : $size_def = ($wf_columns/8*2); break;
				case 'three_eigth' : $size_def = ($wf_columns/8*3); break;
				case 'four_eigth' : $size_def = ($wf_columns/8*4); break;
				case 'five_eigth' : $size_def = ($wf_columns/8*5); break;
				case 'six_eigth' : $size_def = ($wf_columns/8*6); break;
				case 'seven_eigth' : $size_def = ($wf_columns/8*7); break;
				//Suggest use one_ninth for consistency, but keep this just in-case
				case 'ninth' : $size_def = ($wf_columns/9); break;
				case 'one_ninth' : $size_def = ($wf_columns/9); break;
				case 'two_ninth' : $size_def = ($wf_columns/9*2); break;
				case 'three_ninth' : $size_def = ($wf_columns/9*3); break;
				case 'four_ninth' : $size_def = ($wf_columns/9*4); break;
				case 'five_ninth' : $size_def = ($wf_columns/9*5); break;
				case 'six_ninth' : $size_def = ($wf_columns/9*6); break;
				case 'seven_ninth' : $size_def = ($wf_columns/9*7); break;
				case 'eight_ninth' : $size_def = ($wf_columns/9*8); break;
				//Suggest use one_tenth for consistency, but keep this just in-case
				case 'tenth' : $size_def = ($wf_columns/10); break;
				case 'one_tenth' : $size_def = ($wf_columns/10); break;
				case 'two_tenth' : $size_def = ($wf_columns/10*2); break;
				case 'three_tenth' : $size_def = ($wf_columns/10*3); break;
				case 'four_tenth' : $size_def = ($wf_columns/10*4); break;
				case 'five_tenth' : $size_def = ($wf_columns/10*5); break;
				case 'six_tenth' : $size_def = ($wf_columns/10*6); break;
				case 'seven_tenth' : $size_def = ($wf_columns/10*7); break;
				case 'eight_tenth' : $size_def = ($wf_columns/10*8); break;
				case 'nine_tenth' : $size_def = ($wf_columns/10*9); break;
				//Suggest use one_eleventh for consistency, but keep this just in-case
				case 'eleventh' : $size_def = ($wf_columns/11); break;
				case 'one_eleventh' : $size_def = ($wf_columns/11); break;
				case 'two_eleventh' : $size_def = ($wf_columns/11*2); break;
				case 'three_eleventh' : $size_def = ($wf_columns/11*3); break;
				case 'four_eleventh' : $size_def = ($wf_columns/11*4); break;
				case 'five_eleventh' : $size_def = ($wf_columns/11*5); break;
				case 'six_eleventh' : $size_def = ($wf_columns/11*6); break;
				case 'seven_eleventh' : $size_def = ($wf_columns/11*7); break;
				case 'eight_eleventh' : $size_def = ($wf_columns/11*8); break;
				case 'nine_eleventh' : $size_def = ($wf_columns/11*9); break;
				case 'ten_eleventh' : $size_def = ($wf_columns/11*10); break;
				//Suggest use one_twelveth for consistency, but keep this just in-case
				case 'twelveth' : $size_def = ($wf_columns/12); break;
				case 'one_twelveth' : $size_def = ($wf_columns/12); break;
				case 'two_twelveth' : $size_def = ($wf_columns/12*2); break;
				case 'three_twelveth' : $size_def = ($wf_columns/12*3); break;
				case 'four_twelveth' : $size_def = ($wf_columns/12*4); break;
				case 'five_twelveth' : $size_def = ($wf_columns/12*5); break;
				case 'six_twelveth' : $size_def = ($wf_columns/12*6); break;
				case 'seven_twelveth' : $size_def = ($wf_columns/12*7); break;
				case 'eight_twelveth' : $size_def = ($wf_columns/12*8); break;
				case 'nine_twelveth' : $size_def = ($wf_columns/12*9); break;
				case 'ten_twelveth' : $size_def = ($wf_columns/12*10); break;
				case 'eleven_twelveth' : $size_def = ($wf_columns/12*11); break;
				//Phyew! And finally the default as a fallback
				default: $size_def = $size_def = $wf_columns; $last = ' last'; break;
			}
		} else {
			// Have columns figure so use it instead
			if (is_numeric($columns)) { $size_def = $columns; }
		}

		// Setup the optional additional CSS class to move div around
		switch ($move) {
			case 'pull_half' : $move_def = $wf_pull_half; break;
			case 'pull_third' : $move_def = $wf_pull_third; break;
			case 'pull_quarter' : $move_def = $wf_pull_quarter; break;
			case 'pull_eighth' : $move_def = $wf_pull_eighth; break;
			case 'push_half' : $move_def = $wf_push_half; break;
			case 'push_third' : $move_def = $wf_push_third; break;
			case 'push_quarter' : $move_def = $wf_push_quarter; break;
			case 'push_eighth' : $move_def = $wf_push_eighth; break;
			default : $move_def = ''; break;
		}

		//OK Lets build this CSS!
		return $divout_open_clean . 'class="' . $css_core_def . $size_def . $move_def . $last . $class_clean . '"' . $id_clean . $divout_close_clean;

	}


	/**
	 * Just echos </div> - nothing more nothing less, kinda lazy huh?
	 * DEPRECIATED in Wonderflux v2.0, will likely be removed in the future!
	 *
	 * @since	0.913
	 * @version	0.913
	 *
	 * @param	none
	 *
	 * @todo 	Remove and move to legacy support plugin.
	 */
	function wf_css_close() {
		echo '</div>';
	}


	/**
	 * Displays CSS info for designers as a HTML code comment in the <head>.
	 *
	 * @since	0.4
	 * @version	0.931
	 *
	 * @param	none
	 *
	 * @todo 	Review code and update for Flux Layout - currently only works with old % based layout system.
	 */
	function wf_css_info($args) {

		$defaults = array (
			'display' => FALSE
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$wf_fullwidth = $this->wfx_width;
		$wf_columns = $this->wfx_columns;
		$wf_single = $this->wfx_columns_width;

		if ($display == FALSE) {
			$start_format = '<!-- ';
			$end_format = ' -->';
		} else {
			$start_format = '<p>';
			$end_format = '</p>';
		}

		// From simple numbers (already validated in options panel before saved to DB) we build our grid
		$wf_full = 'span-'.$wf_columns.' last';
		$wf_half = 'span-'.($wf_columns/2);
		$wf_third = 'span-'.($wf_columns/3);
		$wf_two_third = 'span-'.($wf_columns/3*2);
		$wf_quarter = 'span-'.($wf_columns/4);
		$wf_two_quarter = $wf_half;
		$wf_three_quarter = 'span-'.($wf_columns/4*3);
		$wf_small = 'span-'.($wf_columns/8);

		$css_info =$start_format . __('WONDERFLUX LAYOUT DESIGN HELPER', 'wonderflux') . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . __('Full width: ', 'wonderflux') . $wf_fullwidth . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . __('Columns: ', 'wonderflux') . $wf_columns . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . __('Single column: ', 'wonderflux') . $wf_single . ' pixels wide' . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . __('Half: ', 'wonderflux') . $wf_half . $end_format;
		$css_info .="\n";

		//TODO: More tests for fifths, sixths, sevenths and eighths?!?

		// Test to see if we can use thirds
		if ( !is_float(($wf_columns/3)) ) {

			$css_info .=$start_format . __('Third: ', 'wonderflux') . $wf_third . $end_format;
			$css_info .="\n";
			$css_info .=$start_format . __('Two Third: ', 'wonderflux') . $wf_two_third . $end_format;
			$css_info .="\n";

		} else {

			$css_info .=$start_format . __('Your column configuration is not compatible with thirds. Avoid using these in your template designs, or reconfigure options.', 'wonderflux') . $end_format;
			$css_info .="\n";

		}

		// Test to see if we can use quarters
		if ( !is_float(($wf_columns/4)) ) {

			$css_info .=$start_format . __('Quarter: ', 'wonderflux') . $wf_quarter . $end_format;
			$css_info .="\n";
			$css_info .=$start_format . __('Two Quarters: ', 'wonderflux') . $wf_half . $end_format;
			$css_info .="\n";
			$css_info .=$start_format . __('Three Quarters: ', 'wonderflux') . 'span-'.(($wf_columns/4)*3) . $end_format;
			$css_info .="\n";

		} else {

			$css_info .=$start_format . __('Your column configuration is not compatible with quarters. Avoid using these in your template designs, or reconfigure options.', 'wonderflux') . $end_format;
			$css_info .="\n";

		}


		$css_info .=$start_format . __('Small: ', 'wonderflux') . $wf_small . $end_format;
		$css_info .="\n";

		echo $css_info;
	}


	/**
	 * EXPERIMENTAL - generates a repeating pattern of columns for testing the grid layout system.
	 *
	 * @since	1.1
	 * @version	1.2
	 *
	 * @param	[int] $rows				Maximum number of rows of divs you wish to output.
	 * @param	[string] $type			Type of column definitions to use to build output - raw column classes 'columns', or nice definitions 'relative'.
	 * @param	[string] $split			Undocumented (sorry - needs testing and code review!)
	 * @param	[string] $compatibility	Undocumented (sorry - needs testing and code review!)
	 *
	 * @todo 	Review code and build a bester testing pattern!
	 */
	function wf_css_test_pattern( $args ){

		$defaults = array (
			'rows' => $this->wfx_columns,
			'type' => 'columns',
			'split' => 'single',
			'compatibility' => 'Y',
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		$type = ($type !='relative') ? 'columns' : $type;
		$split = ($split !='single') ? 'relative' : $split;
		$errors = array();

		if ( intval($rows) > 12 && $type == 'relative' ): $rows = 12;
		elseif
			( intval($rows) > 101 && $type == 'columns' ): $rows = 10;
		endif;

		for( $i=0; $i<$rows; $i++ ){
			if ( $type == 'relative')
				$this->wf_css_test_pattern_rel('divs='.($i+1).'&compatibility='.$compatibility.'');
			else
				$this->wf_css_test_pattern_col('divs='.($i+1).'&split='.$split.'');
		}

	}


	/**
	 * @since 1.1
	 * @updated 1.1
	 * Generates a repeating pattern of columns for testing the grid layout system
	 * Internal function used by wf_css_test_pattern
	 *
	 * @param divs (integer) Maximum number of rows of divs you wish to output. [1]
	 */
	function wf_css_test_pattern_col( $args ){

		$defaults = array (
			'divs' => $this->wfx_columns,
			'css' => array( 'p','small','strong' ),
			'size' => $this->wfx_columns,
			'split' => 'single'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		$size = ($split == 'single') ? 1 : floor($this->wfx_columns/$divs);

		wfx_css( 'size=full&class=blocksample-row' . ' blocksample-row&divoutput=Y' );
		echo '<h3 class="flush-bottom">Columns: ' . $divs . '</h3>';

		for( $i=0; $i<$divs; $i++ ) {
			$last = ( $i < ($divs-1) ) ? '' : 'Y';
			wfx_css( 'columns=' . $size . '&class=blocksample-size-'.$divs . ' blocksample' . '&last=' . $last . '&divoutput=Y' );
			echo '<' . $css[0] . ' class="flush-bottom">' . ($i+1) . '</'.$css[0].'>';
			echo '<' . $css[0] . ' class="flush-bottom">' . '<' . $css[1] . '>' . 'Size: ' . $size . '</'.$css[1].'>' . '</'.$css[0].'>';
			echo '</div>';
		}

		wfx_css_close('');

	}


	/**
	 * @since 1.1
	 * @updated 1.1
	 * Generates a repeating pattern of columns using relative sizes like 'half','quarter' for testing the grid layout system.
	 * Internal function used by wf_css_test_pattern
	 *
	 * @param divs (integer) Maximum number of rows of divs you wish to output. NOTE: Maximum 12 [1]
	 * @param compatibility (string) Set to N to show incompatible relative sizes, ie 24 columns will not divide into 9. 'Y','N' [Y]
	 */
	function wf_css_test_pattern_rel( $args ){

		$defaults = array (
			'divs' => $this->wfx_columns,
			'css' => array( 'p','small','strong' ),
			'compatibility' => 'Y'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		$divs = ( intval($divs) < 13 ) ? $divs : 12;

		switch ( $divs ){
			case '1': $def = 'full'; break;
			case '2': $def = 'half'; break;
			case '3': $def = 'third'; break;
			case '4': $def = 'quarter'; break;
			case '5': $def = 'fifth'; break;
			case '6': $def = 'sixth'; break;
			case '7': $def = 'seventh'; break;
			case '8': $def = 'eigth'; break;
			case '9': $def = 'ninth'; break;
			case '10': $def = 'tenth'; break;
			case '11': $def = 'eleventh'; break;
			case '12': $def = 'twelveth'; break;
			default: $def = 'full'; break;
		}



		wfx_css( 'size=full&class=blocksample-row' . ' blocksample-row&divoutput=Y' );
		echo '<h3 class="flush-bottom">Columns: ' . $divs . '</h3>';

		for( $i=0; $i<$divs; $i++ ){
			$last = ( $i < ($divs-1) ) ? '' : 'Y';

			//wfx_css( 'size=' . $def . '&class=blocksample-size-'.$def . ' blocksample' . '&last=' . $last . '&divoutput=Y' );
			echo '<div class="blocksample ' . apply_filters( 'wflux_css_definition', 'span-' ) . '1-' . $divs . '">';


			echo '<' . $css[0] . ' class="flush-bottom">' . ($i+1) . '</'.$css[0].'>';
			echo '<' . $css[0] . ' class="flush-bottom">' . '<' . $css[1] . '>' . 'Size: ' . $def . '</'.$css[1].'>' . '</'.$css[0].'>';
			echo '</div>';
		}

		wfx_css_close('');

	}


	/**
	 * IMPORTANT - Creates layout wrappers around content and sidebar if begin used.
	 *
	 * NOTE: Inserted at hooks priority 2 or 9, to allow you to hook in with your own functions at:
	 * - priority 1 for before wrappers
	 * - priority 3+ inside wrappers
	 *
	 * @since	0.93
	 * @version	0.93
	 *
	 * @param	none
	 */
	function wf_layout_build() {

		// Main content
		if ( $this->wfx_content_1_display == 'Y' && $this->wfx_sidebar_1_display == 'Y' ) {
				add_action( 'wfmain_before_all_content', array ($this, 'wf_layout_build_content_sb1'), 2 );
				add_action( 'wfsidebar_before_all', array ($this, 'wf_layout_build_sb1'), 2 );
				add_action( 'wfmain_after_all_content', array ($this, 'wf_css_close'), 9 );
				add_action( 'wfsidebar_after_all', array ($this, 'wf_css_close'), 9 );
		} elseif ( $this->wfx_content_1_display == 'Y' && $this->wfx_sidebar_1_display == 'N' ) {
				add_action( 'wfmain_before_all_content', array ($this, 'wf_layout_build_content_no_sb1'), 2 );
				add_action( 'wfmain_after_all_content', array ($this, 'wf_css_close'), 9 );
		// Experimental edge case - needs more work to remove content display, but this removes the CSS!
		} elseif ( $this->wfx_content_1_display == 'N' && $this->wfx_sidebar_1_display == 'Y' ) {
				add_action( 'wfsidebar_before_all', array ($this, 'wf_layout_build_sb1_no_content'), 2 );
				add_action( 'wfsidebar_after_all', array ($this, 'wf_css_close'), 9 );
		}
	}


	/**
	* @since 0.93
	* @updated 2.0
	* Sets up required layout divs for sidebar1 WITH content1
	*/
	function wf_layout_build_sb1($args) {

		$args = array(
			'columns'   => $this->wfx_sidebar_1_size_columns,
			'size'      => $this->wfx_sidebar_1_size,
			'id'        => $this->wfx_sidebar_1_id,
			'last'      => 'Y',
			'divoutput' => 'Y',
			'class'     => esc_attr( apply_filters( 'wflux_sidebar_1_with_content_1', 'sidebar-1-with-content-1' ) )

		);
		echo $this->wf_css( $args );

	}


	/**
	* @since 0.93
	* @updated 2.0
	* Sets up required layout divs for sidebar1 WITHOUT content1
	*/
	function wf_layout_build_sb1_no_content($args) {

		$args = array(
			'id'        => $this->wfx_sidebar_1_id,
			'last'      => 'Y',
			'divoutput' => 'Y',
			'class'     => esc_attr( apply_filters( 'wflux_sidebar_1_no_content_1', 'sidebar-1-no-content-1' ) )

		);
		echo $this->wf_css( $args );

	}


	/**
	* @since 0.93
	* @updated 2.0
	* Sets up required layout divs for main content1 WITH sidebar1
	*/
	function wf_layout_build_content_sb1($args) {

		$args = array(
			'columns'   => $this->wfx_content_1_size_columns,
			'size'      => $this->wfx_content_1_size,
			'id'        => $this->wfx_content_1_id,
			'last'      => 'Y',
			'divoutput' => 'Y',
			'class'     => esc_attr( apply_filters( 'wflux_content_1_with_sidebar_1', 'content-1-with-sidebar-1' ) )

		);
		echo $this->wf_css( $args );

	}


	/**
	* @since 0.93
	* @updated 2.0
	* Sets up required layout divs for main content1 WITHOUT sidebar1
	*/
	function wf_layout_build_content_no_sb1($args) {

		$args = array(
			'id'        => $this->wfx_content_1_id,
			'last'      => 'Y',
			'divoutput' => 'Y',
			'class'     => esc_attr( apply_filters( 'wflux_content_1_no_sidebar_1', 'content-1-no-sidebar-1' ) )

		);
		echo $this->wf_css( $args );

	}


	/**
	 * IMPORTANT - Adds additional CSS classes to sidebar and content for media query breakpoints.
	 * Designed to work with Flux Layout - configured in Wonderflux layout options.
	 *
	 * Filters available:
	 * wflux_rwd_full_width : Additional generated CSS classes added to sidebar and main content (in helper function).
	 *
	 * @since	2.1
	 * @version	2.1
	 *
	 * @param	none
	 */
	function wf_rwd_full_width() {

		add_filter( 'wflux_sidebar_1_with_content_1', array($this, 'wf_rwd_full_width_do') );
		add_filter( 'wflux_content_1_with_sidebar_1', array($this, 'wf_rwd_full_width_do') );

	}


	/**
	 * Adds additional CSS classes to array for additional media query breakpoints.
	 * Designed to work with Flux Layout.
	 * Used by wf_rwd_full_width_do() to filter values.
	 *
	 * Filters available:
	 * wflux_rwd_full_width : Additional generated CSS classes added to sidebar and main content.
	 *
	 * @since	2.1
	 * @version	2.1
	 *
	 * @param	$input 					Array of existing CSS classes
	 */
	function wf_rwd_full_width_do( $input ) {

		return esc_attr( $input . apply_filters('wflux_rwd_full_width', ' mq-' . $this->wfx_rwd_full . '-min-box-1-1') );

	}


}


/**
 * Wonderflux display functions.
 *
 * @since	0.1
 * @version	2.1
 */
class wflux_display extends wflux_display_css {


	/**
	 * Includes sidebar template file.
	 * Uses get_sidebar() and checks for Wonderflux option/filter.
	 *
	 * @since	0.93
	 * @version	0.93
	 *
	 * @param	none
	 *
	 * @todo 	Check over this functionality, should we be extending get_sidebar() rather than replacing?
	 */
	function wf_get_sidebar($args) {

		if ( $this->wfx_sidebar_1_display == 'Y' ) {
			get_sidebar($args);
		} else {
			// Silence is golden - more sidebars to come!
		}

	}


	/**
	 * Output credit in footer of site - show your support and love for WordPress and Wonderflux!
	 *
	 * Filters available:
	 * wflux_footer_credit_format : HTML tag to surround output with.
	 * wflux_footer_credit_wp : WordPress credit text.
	 * wflux_footer_divider : Divider between credt text.
	 * wflux_footer_credit_wf : Wonderflux credit text.
	 * wflux_footer_credit_content : Entire credit text.
	 * wflux_footer_credit_div : Surround content with a div?
	 *
	 * @since	0.3
	 * @version	2.1
	 *
	 * @param	none
	 * @todo Review code and santization
	 */
	function wf_credit() {

		// Defaults
		$footer_credit_format = 'p';
		$footer_credit_wp = esc_attr__('Powered by ', 'wonderflux') . '<a href="http://wordpress.org" title="' . esc_attr__('WordPress', 'wonderflux') . '" rel="nofollow">' . esc_attr__('WordPress', 'wonderflux') . '</a>';
		$footer_credit_divider = ' | ';
		$footer_credit_wf = esc_attr__('Built with ', 'wonderflux') . '<a href="http://wonderflux.com" title="' . esc_attr__('Wonderflux WordPress theme framework', 'wonderflux') . '" rel="nofollow">' . esc_attr__('Wonderflux Framework', 'wonderflux') . '</a>';
		$footer_credit_div = '';

		// Setup for individual filtering
		$footer_credit_format = apply_filters( 'wflux_footer_credit_format', $footer_credit_format );
		$footer_credit_wp = apply_filters( 'wflux_footer_credit_wp', $footer_credit_wp );
		$footer_credit_divider = apply_filters( 'wflux_footer_divider', $footer_credit_divider );
		$footer_credit_wf = apply_filters( 'wflux_footer_credit_wf', $footer_credit_wf );
		$footer_credit_div = apply_filters( 'wflux_footer_credit_div', $footer_credit_div );


		$footer_credit_content = $footer_credit_wp . $footer_credit_divider . $footer_credit_wf;


		//We are setup for a custom div
		if ($footer_credit_div !='') {
			echo '<div class="';
			echo apply_filters( 'wflux_footer_credit_div', esc_attr($footer_credit_div) );
			echo '" id="wf-footer-credit">';
		} else {
			wfx_css('size=full&id=footer-wfx-credit&divoutput=Y');
		}

		$footer_credit = '<' . $footer_credit_format . '>';

		//Filter to overide all of wflux_footer_credit_content - but keep inside CSS formatting
		$footer_credit .= apply_filters( 'wflux_footer_credit_content', $footer_credit_content );
		$footer_credit .= '</' .  esc_attr( $footer_credit_format ) . '>';

		//Filter to overide all of wflux_footer_credit
		echo apply_filters( 'wflux_footer_credit', wp_kses_post($footer_credit) );
		echo "\n";

		//Now close the div we opened
		echo '</div>';

	}

}


/**
 * Extra core display functions for theme developers.
 *
 * @since	0.85
 * @version	2.1
 *
 * @todo Check translation setup.
 */
class wflux_display_extras {

	private $clean_theme_name = null;

	/**
	 * Returns santisied version of current theme name, replacing spaces and other characters (apart from a-z A-Z and 0-9) with _
	 * Used in cache function (and maybe others in the future!)
	 * @return string
	 *
	 * @since 1.1
	 * @updated 2.0
	 */
	private function get_clean_theme_name() {
		if ( !$this->clean_theme_name )
			$this->clean_theme_name = wp_get_theme()->Name;
			return $this->clean_theme_name;
	}


	/**
	 * Display excerpt of post content inside the loop or custom query.
	 *
	 * @since	0.85
	 * @version	1.1
	 *
	 * @param	[int] $limit			Number of words. [20]
	 * @param	[string] $excerpt_end 	Characters to add to end of the excerpt. [...]
	 * @param	[string] $trim			Trim off punctuation from end of excerpt - good when you don't want it to bump into your excerpt end. Y/N [Y]
	 */
	function wf_excerpt($args) {

		$defaults = array (
			'limit' => 20,
			'excerpt_end' => '...',
			'trim' => 'Y'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Tidy up ready for use
		$excerpt_end = wp_kses_data($excerpt_end, '');
		$content = get_the_excerpt();

		$excerpt = explode(' ', $content, ($limit+1));

		if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'';
		} else {
		$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		$excerpt = trim(wp_kses_data($excerpt, ''));

		// Remove punctuation
		if ($trim == 'Y') {
			$end_check = substr($excerpt, -1);
			$punctuation = array('.',',','-','&minus;','&ndash;','&mdash;','!','?');
			if (in_array($end_check,$punctuation)): $excerpt = substr($excerpt, 0, -1); endif;
		}

		// Dont include excerpt end if there is no excerpt!
		$excerpt_end = ( !empty($excerpt) ) ? $excerpt_end : '';

		return esc_attr($excerpt) . esc_attr($excerpt_end);

	}


	/**
	 * Adds admin/editing links.
	 * Creates un-ordered list inside an optional div.
	 *
	 * @since	0.85
	 * @version	2.1
	 *
	 * @param	[string] $userintro		Text string in first list item. [Welcome]
	 * @param	[string] $username		Display username after intro (within same list item). Y/N [Y]
	 * @param	[string] $intro			Display intro. Y/N [Y]
	 * @param	[string] $postcontrols	Show post controls. Y/N [Y]
	 * @param	[string] $pagecontrols	Show page controls. Y/N [Y]
	 * @param	[string] $adminlink		Show admin area link. Y/N [Y]
	 * @param	[string] $widgetslink	Show edit widgets link. Y/N [N]
	 * @param	[string] $logoutlink	Show WordPress logout link. Y/N [N]
	 * @param	[string] $ulclass		<ul> class. [wf-edit-meta-main]
	 * @param	[string] $liclass		<li> class. Y/N [wf-edit-meta-links]
	 * @param	[string] $div			Wrap output in containing <div>. Y/N [N]
	 * @param	[string] $divclass		Containing <div> class if used. [wf-edit-meta-box]
	 *
	 * @todo 	Review code, make smarter and deploy into Wonderflux widget.
	 * @todo	Extend further to accomodate when a user is NOT logged in (like WordPress Meta widget stylee!)
	 * @todo	Extend for user profiles, editing taxonomies etc.
	 */
	function wf_edit_meta($args) {

		$defaults = array (
			'userintro' => __('Welcome', 'wonderflux'),
			'username' => 'Y',
			'intro' => 'Y',
			'postcontrols' => 'Y',
			'pagecontrols' => 'Y',
			'adminlink' => 'Y',
			'widgetslink' => 'Y',
			'wfcontrols' => 'N',
			'logoutlink' => 'N',
			'ulclass' => 'wf-edit-meta',
			'liclass' => 'wf-edit-meta-links',
			'div' => 'N',
			'divclass' => 'wf-edit-meta-box'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( is_user_logged_in() ):
			global $current_user;
			get_currentuserinfo();

			// Prepare user input for output
			$userintro = wp_kses_data($userintro);
			$ulclass = wp_kses_data($ulclass);
			$liclass = '<li class="' . esc_attr(wp_kses_data($liclass)) . '">';
			$divclass = wp_kses_data($divclass);
			$this_admin = admin_url();

			$output = '';

			if ( $div == 'Y') { $output .= '<div class="'.esc_attr($divclass).'">'; }

			$output .= '<ul class="' . esc_attr($ulclass) . '">';

			if ( $username == 'Y' && $intro == 'Y' ) {
				$output .= $liclass;
				$output .= esc_attr($userintro) . ' ';
				$output .= ucwords( $current_user->display_name ) . '</li>';
			} elseif ( $username == 'N') {
				if ( $intro == 'Y') {
					$output .= $liclass;
					$output .= esc_attr($userintro) . ' ';
					$output .= '</li>';
				}
			}

			wp_reset_query();
			global $wp_query;

			switch (TRUE) {
				case ( in_the_loop() ) : $edit_do = 'Y'; break;
				case ( is_page() || is_single() ) : $edit_do = 'Y'; break;
				case ( is_home() && get_option('show_on_front') == 'page' ) : $edit_do = 'Y'; break;
				default :
					$edit_do = false;
				break;

			}

			if ( $edit_do == 'Y' ) {
				$this_post_id = ( isset($wp_query->post->ID) ) ? $wp_query->post->ID : '';
				if ( $this_post_id !='' && current_user_can('edit_post', $this_post_id) ) {
					$output .= $liclass. '<a href="' . wp_sanitize_redirect($this_admin) . 'post.php?action=edit&amp;post=' . $this_post_id . '" title="' . esc_attr__('Edit this content', 'wonderflux') . '">' . esc_attr__('Edit this content', 'wonderflux') . '</a></li>';
				}
			}

			if ( current_user_can('edit_posts') && $postcontrols == 'Y' ) {
				$output .= $liclass . esc_attr__('POSTS:', 'wonderflux') . ' <a href="' . wp_sanitize_redirect($this_admin) . 'post-new.php" title="' . esc_attr__('Create a new post', 'wonderflux') . '">' . esc_attr__('New', 'wonderflux') . '</a> | <a href="' . wp_sanitize_redirect($this_admin) . 'edit.php" title="' . esc_attr__('Edit existing posts', 'wonderflux') . '">' . esc_attr__('Edit', 'wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_pages') && $pagecontrols == 'Y' ) {
				$output .= $liclass . esc_attr__('PAGES:', 'wonderflux') . ' <a href="' . wp_sanitize_redirect($this_admin) . 'post-new.php?post_type=page" title="' . esc_attr__('Create new page', 'wonderflux') . '">' . esc_attr__('New', 'wonderflux') . '</a> | <a href="' . wp_sanitize_redirect($this_admin) . 'edit.php?post_type=page" title="' . esc_attr__('Edit existing pages', 'wonderflux') . '">' . esc_attr__('Edit', 'wonderflux') . '</a></li>';
			}

			if ( current_user_can('publish_posts') && $adminlink == 'Y' ) {
				$output .= $liclass . '<a href="' . wp_sanitize_redirect($this_admin) . '" title="' . esc_attr__('Website admin area', 'wonderflux') . '">' . esc_attr__('Admin area', 'wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_theme_options') && $widgetslink == 'Y' ) {
				$output .= $liclass . '<a href="' . wp_sanitize_redirect($this_admin) . 'widgets.php" title="' . esc_attr__('Edit website widget areas', 'wonderflux') . '">' . esc_attr__('Edit widgets', 'wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_theme_options') && $wfcontrols == 'Y' ) {
				$output .= $liclass . '<a href="' . wp_sanitize_redirect($this_admin) . 'admin.php?page=wonderflux" title="' . esc_attr__('Wonderflux theme framework options', 'wonderflux') . '">' . esc_attr__('Theme options', 'wonderflux') . '</a></li>';
			}

			if ( $logoutlink == 'Y' ) {
				$output .= $liclass . '<a href="' . wp_logout_url( get_permalink() ) . '" title="' . esc_attr__('Log out of website', 'wonderflux') . '">' . esc_attr__('Log out', 'wonderflux') . '</a></li>';
			}

			$output .= '</ul>';

			if ( $div == 'Y') { $output .= '</div>'; }

			echo $output;

		endif;

	}


	/**
	 * Displays a single post/page/whatever.
	 *
	 * @since	0.85
	 * @version	1.2
	 *
	 * @param	[int] $id				REQUIRED, ID of the content you want. [false]
	 * @param	[string] $titlestyle	Title element definition. [h4]
	 * @param	[string] $contentstyle	Content element definition. [p]
	 * @param	[string] $title			Display title. Y/N [Y]
	 * @param	[string] $titlelink		Link title to content? Y/N [N]
	 * @param	[int] $exerptlimit		Limit number of words in post content. [20]
	 * @param	[string] $exerptend		Characters to add to end of the excerpt. [...]
	 * @param	[string] $morelink		Display read more link. Y/N [N]
	 * @param	[string] $morelinktext	Text used for read more link. [More]
	 * @param	[string] $morelinkclass	CSS class added to read more link. [wfx-get-page-loop-more]
	 * @param	[string] $boxclass		CSS class added to container div. [wfx-get-page-loop]
	 * @param	[string] $contentclass	CSS class added to content div. [wfx-get-page-loop-content]
	 *
	 * @todo 	Review code, make smarter and deploy into Wonderflux widget.
	 */
	function wf_get_single_content($args) {

		$defaults = array (
			'id' => false,
			'titlestyle' => 'h4',
			'titleclass' => '',
			'contentstyle' => 'p',
			'title' => 'Y',
			'titlelink' => 'N',
			'exerptlimit' => '20',
			'exerptend' => '...',
			'morelink' => 'N',
			'morelinktext' => __('More', 'wonderflux'),
			'morelinkclass' => 'wfx-get-page-loop-more',
			'boxclass' => 'wfx-get-page-loop',
			'contentclass' => 'wfx-get-page-loop-content'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( !is_numeric($id) ) return;

		// Prepare user input for output
		$titlestyle = wp_kses_data($titlestyle);
		$titleclass = wp_kses_data($titleclass);
		$boxclass = wp_kses_data($boxclass);
		$contentstyle = wp_kses_data($contentstyle);
		$contentclass = wp_kses_data($contentclass);
		$exerptend = wp_kses_data($exerptend);
		$morelinktext = wp_kses_data($morelinktext);
		$morelinkclass = wp_kses_data($morelinkclass);
		if (!is_numeric($id)) { $id = 2; }

		$titleclass = ( !empty($titleclass) ) ? ' class="' . sanitize_html_class($titleclass) . '"' : '';

		// LOOP BEGIN
		$my_q_posts = new WP_Query(array(
		'page_id' => ''.$id.'',
		'post_status' => 'publish'
		));
		//$my_q_posts = new WP_Query(array('showposts' => '10'));
		if ($my_q_posts->have_posts()) : while ($my_q_posts->have_posts()) : $my_q_posts->the_post();

			// Title
			if ($title == 'Y') {
				$title_out = '<div class="wf-mini-display-title">';
				$title_out .= '<' . sanitize_html_class( $titlestyle ) . $titleclass . '>';
				if ($titlelink == 'Y') { $title_out .= '<a href="' . get_permalink() . '" title="'. get_the_title() .'">'; }
				$title_out .= get_the_title();
				if ($titlelink == 'Y') { $title_out .= '</a>'; }
				$title_out .= '</' . sanitize_html_class($titlestyle) . '>';
				$title_out .= "\n";
				$title_out .= '</div>';
			} else {
				$title_out = '';
			}

			// Content
			$ex_out = '<div class="' . esc_attr($contentclass) . '">';
			$ex_out .= "\n";
			$ex_out .= '<' . esc_attr($contentstyle) . '>';
			$ex_out .= $this->wf_excerpt('limit=' . $exerptlimit . '&excerpt_end=' . esc_attr($exerptend) . '');
			$ex_out .= '</' . esc_attr($contentstyle) . '>';
			$ex_out .= "\n";
			$ex_out .= '</div>';

			// More link
			if ($morelink == 'Y') {
				$morelink_out = '<p><a href="' . get_permalink() . '" title="' . esc_attr($morelinktext) . ' ' . get_the_title() . '" class="' . esc_attr($morelinkclass) . '">';
				$morelink_out .= esc_attr($morelinktext);
				$morelink_out .= '</a></p>';
			} else {
				$morelink_out = '';
			}

			echo '<div class="' . esc_attr($boxclass) . ' get-post-'.get_the_ID().'">';
			echo $title_out . $ex_out . $morelink_out;
			echo '</div>';

		// LOOP END
		endwhile; endif;

		// And now cleanup
		wp_reset_query();

	}


	/**
	 * Creates a login/logout link with redirect options.
	 *
	 * @since	0.901
	 * @version	2.1
	 *
	 * @param	[string] $login			Logout link text. [Login]
	 * @param	[string] $logintip		Login link title/tooltip. [Login to site]
	 * @param	[string] $logout		Logout link text. [Logout]
	 * @param	[string] $logouttip		Logout link title/tooltip. [Logout of site]
	 * @param	[string] $loginurl		Re-direct on login either to current view or home. current/home [current]
	 * @param	[string] $logouturl		Re-direct on logout either to current view or home. current/home [home]
	 *
	 * @todo 	Review code, make smarter and deploy into Wonderflux widget.
	 * @todo	Extend further to accomodate other re-directs.
	 */
	function wf_login_logout($args) {

		$defaults = array (
			'login' => __( 'Login', 'wonderflux' ),
			'logintip' => __( 'Login to site', 'wonderflux' ),
			'logout' => __( 'Logout', 'wonderflux' ),
			'logouttip' => __( 'Logout of site', 'wonderflux' ),
			'loginurl' => 'current',
			'logouturl' => 'home'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		switch ( $loginurl ) {

			case 'current': $loginurl = get_permalink(); break;
			case 'home': $loginurl = home_url(); break;
			default: $loginurl = false; break;

		}

		switch ( $logouturl ) {

			case 'current': $logouturl = get_permalink(); break;
			case 'home': $logouturl = home_url(); break;
			default: $logouturl = false; break;

		}

		if ( is_user_logged_in() ) {
			echo '<a class="wfx-login-logout wfx-login-logout-logged-in" href="' . esc_url( wp_logout_url($logouturl) ) . '" title="'.esc_attr( $logouttip ) . '">'.esc_attr( $logout ) . '</a>';
		} else {
			echo '<a class="wfx-login-logout" href="' . esc_url( wp_login_url($loginurl) ) . '" title="'.esc_attr( $logintip ) . '">'.esc_html( $login ) . '</a>';
		}

	}


	/**
	 * Adds 'current_page_item' CSS class when post ID is current post/page/whatever being viewed.
	 * For when you really need to code a static navigation.
	 *
	 * @since	0.901
	 * @version	0.913
	 *
	 * @param	[int] $id				ID of post. []
	 *
	 * @todo 	Review code, make smarter!
	 */
	function wf_static_highlight($args) {

		$defaults = array (
			'id' => "2"
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		global $post;

		if ($id !=''):

			//Style if on actual page on top level navigation
			if ($post->ID == $id):
				echo 'current_page_item';
			endif;

		endif;

		/*
		// TODO: Debug this - pretty much works!
		// Highlights if in category view or single post view of given category
		if ($thiscat !='') {

			$categories = get_the_category($post->ID);
			$this_cat_id_single = $categories[0]->cat_ID;
			$this_cat_parent = $categories[0]->category_parent;

			if ($thiscat == $this_cat_id_single) {
				$thishighlight = TRUE;
			} elseif ($this_cat_parent == $thiscat) {
				$thishighlight = TRUE;

			} elseif ($thishighlight != TRUE) {

				//Check deeper
				$this_cat_ids = array();
				foreach ($categories as $cat) {

					$this_cat_ids[]= $cat->cat_ID;

					if (in_array($thiscat,$this_cat_ids, TRUE))
						{
							$thishighlight = TRUE;
						}
				}

			}

			if ($thishighlight == TRUE) { echo ' current_page_item'; }

		}

		// Highlights if in specific page or child page
		if ($thisid !='') {
			//Style if on actual page on top level navigation
			if ($post->ID == $thisid):
				echo ' current_page_item';
			endif;

			//Style if viewing a child page (level 2)
			$ancestors=get_post_ancestors($post->$thisid);
			if (in_array($thisid,$ancestors)):
				echo ' current_page_item';
			endif;

		}
		*/

	}


	/**
	 * Gets attachment(s) or featured images of main post query (inside or outside the loop).
	 * Will try and playback files or create nice links.
	 * IMPORTANT - used in loop-content-attachment.php
	 *
	 * @since	0.901
	 * @version	0.913
	 *
	 * @param	[string] $type			Type of output. all/featured_image/attachment [all]
	 * @param	[string] $mime_type		Limit to certain mime type (only used if $type=all). []
	 * @param	[int] $amount			Limit number of attachments (only used if $type=all). [-1]
	 * @param	[string] $order			Order attachments (only used if $type=all). ASC/DESC [ASC]
	 * @param	[string] $output		What tag to use to wrap attachment output. p/ul/ol [ul]
	 * @param	[string] $img_size		Registered image size definition (only used if $type=featured_image). [large]
	 * @param	[bool] $div_wrap		Wrap all output in optional div. true/false [false]
	 * @param	[string] $div_class		CSS classes used on wrapper div (only used if $div_wrap=true). [box-get-attachment]
	 * @param	[string] $element_class	CSS classes used on media output. [get-attachment-file]
	 * @param	[string] $link_class	CSS classes used on link output. [get-attachment-link]
	 * @param	[string] $img_class		CSS classes used on image output. [attachment-single responsive-full-width]
	 * @param	[string] $output_start	Text show before text links [Download]
	 * @param	[string] $output_end	Text shown after text links. [false]
	 * @param	[string] $meta_key		Used for more advanced attachment queries - see WP core get_children() [false]
	 * @param	[string] $meta_value	Used for more advanced attachment queries - see WP core get_children() [false]
	 *
	 * @todo	Extend with ID control for any post ID.
	 * @todo	Extend media playback control.
	 */
	function wf_get_attachments($args) {

		// Acceptable values ($var_accept) - first item in array is used as default
		$type_accept = array( 'all','featured_image','attachment' ); /* TODO: Extend in the future */
		$output_accept = array( 'p','ul','ol' );
		$mime_type_accept = array( false,'image','video','text','audio','application','' );

		$defaults = array (
			'type' => $type_accept[0],
			'mime_type' => false,
			'amount' => -1,
			'order' => 'ASC',
			'output' => 'ul',
			'img_size' => 'large',
			'div_wrap' => false,
			'div_class' => 'box-get-attachment',
			'element_class' => 'get-attachment-file',
			/*'link_title_start' => 'Download ',*/
			'link_title_end' => false,
			'link_class' => 'get-attachment-link',
			'img_class' => 'attachment-single responsive-full-width',
			'output_start' => 'Download ',
			'output_end' => false,
			'meta_key' => false,
			'meta_value' => false
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Cleanup variables, final escaping etc done later on output

		$type = ( $type == $type_accept[0] ) ? $type : ( in_array( $type, $type_accept ) ) ? $type : $type_accept[0];
		$output = ( $output == $output_accept[0] ) ? $output : ( in_array( $output, $output_accept ) ) ? $output : $output_accept[0];
		$output_i = ( $output == 'ul' || $output == 'ol' ) ?  'li': 'p';

		$mime_type = ( !empty($mime_type) ) ? $mime_type : false;

		$amount = ( is_numeric($amount) ) ? $amount : -1;
		$order = ( $order == 'ASC' ) ? $order : 'DESC';
		$img_size = ( $img_size == 'large' ) ? $img_size : ( in_array($img_size, get_intermediate_image_sizes()) ) ? $img_size : 'large';
		$link_class = ( !empty($link_class) ) ? $link_class . ' ' : '';

		// Ready for output
		$out = '';
		global $post;

		// $type controls the output
		if ( $type == 'featured_image' && has_post_thumbnail($post->ID) ):

			$out .= ( $div_wrap ) ? '<div class="'. esc_attr($div_class) .'">' : '';
			//TODO: Setup get_the_post_thumbnail $attr array for parameters
			$out .= get_the_post_thumbnail($post->ID, $img_size);
			$out .= ( $div_wrap ) ? '</div>' : '';

		// Used in loop-content-attachment.php
		elseif ( $type == 'attachment' ):

			$attachment_url = wp_get_attachment_url($post->ID);
			$file_info = wfx_info_file( $attachment_url );

			$out .= ( $div_wrap && !empty($attachment_url) ) ? '<div class="'. esc_attr($div_class) .'">' : '';

			// Display the attachment the best way we can
			switch ( $file_info['nicetype'] ) {
				case 'image':
					// already checked its an image dont need wp_attachment_is_image( $post->ID ):
					$img_scr = wp_get_attachment_image_src( $post->ID, $img_size );
					// Setup correct alt and title for image if possible
					$alt_atr = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
					$alt_atr = ( !empty($alt_atr) ) ? $alt_atr : get_the_title();
					$title_atr = $post->post_excerpt;
					$title_atr = ( !empty($post->post_excerpt) ) ? $title_atr : get_the_title();

					$out .= ( !empty( $img_scr ) ) ? '<p class="attachment-image"><img src="' . esc_url( $img_scr[0] ) . '" width="' . $img_scr[1] . '" height="' . $img_scr[2] . '" class="'.esc_attr( $img_class ) . '" alt="' . esc_attr( $alt_atr ) . '" title="' . esc_attr( $title_atr ) . '" /></p>' :  '';
				break;

				//Try and play it WP3.6 oembed style
				case 'audio':
					// Things I tried but didn't work as expected with internal URLS
					// Works, but not for internal urls
					//echo wp_oembed_get($mp3_url);
					// global $wp_embed;
					// echo $wp_embed->shortcode(array(), $mp3_url);

					// Backpat - WordPress 3.6 oembeds just with URL (if on new line), cool!
					$backp_start = ( WF_WORDPRESS_VERSION < 3.6 ) ? '[audio src="' : '';
					$backp_end = ( WF_WORDPRESS_VERSION < 3.6 ) ? '"]' : '';
					$out .= apply_filters( 'the_content', $backp_start . esc_url( $attachment_url ) . $backp_end );
				break;

				//Try and play it
				case 'video':
					// Backpat - WordPress 3.6 oembeds just with URL (if on new line), cool!
					$backp_start = ( WF_WORDPRESS_VERSION < 3.6 ) ? '[video src="' : '';
					$backp_end = ( WF_WORDPRESS_VERSION < 3.6 ) ? '"]' : '';
					$out .= apply_filters( 'the_content', $backp_start . esc_url( $attachment_url ) . $backp_end );
				break;

				// A slightly more useful general file link
				default:
					$tool_tip = sprintf( __( 'Download %1$s (.%2$s file)', 'wonderflux' ), $file_info['nicetype'], $file_info['ext'] );
					$title_info = sprintf( __( ' (%1$s .%2$s file)', 'wonderflux' ), $file_info['nicetype'], $file_info['ext'] );

					$out .= ($output == 'ul' || $output == 'ol') ? ($output == 'ul') ? '<ul class="' . $element_class . '">' : '<ol class="' . $element_class . '">' : '';
					$out .= '<' . esc_attr( $output_i ) . ' class="attachment-' . $file_info['ext'] . '">';
					$out .= '<a class="' . esc_attr( $element_class ) . ' ' . esc_attr( $link_class ) . 'attachment-'.$file_info['ext'] . ' attachment-'.$post->ID . '" ';
					$out .= 'title="' . esc_attr( $tool_tip . $link_title_end ) . '" ';
					$out .= 'href="' . esc_url( $attachment_url ) . '">';
					$out .= esc_attr( $output_start.get_the_title( $post->ID ).$output_end.$title_info );
					$out .= '</a></' . esc_attr($output_i) . '>';
					$out .= ($output == 'ul' || $output == 'ol') ? ($output == 'ul') ? '</ul>' : '</ol>' : '';
				break;
				$out .= "\n";

			}

			$out .= ( $div_wrap && !empty($attachment_url) ) ? '</div>' : '';

		// Used to fetch all attachments of current post
		elseif ( $type == 'all' ):

			$files = get_children(array(
				'post_parent'	=> $post->ID,
				'post_type'		=> 'attachment',
				'order'			=> $order,
				'post_mime_type'=> $mime_type,
				'numberposts'	=> $amount,
				'post_status'	=> 'inherit',
				'meta_key'		=> ( is_array($meta_key) ) ? $meta_key : null, // NOTE: false busts the query - has to be null not false
				'meta_value'	=> ( is_array($meta_value) ) ? $meta_value : null // NOTE: false busts the query - has to be null not false
			));

			if ($files){

				$out .= ( $div_wrap ) ? '<div class="'. esc_attr($div_class) .'">' : '';
				$out .= ($output == 'ul' || $output == 'ol') ? ($output == 'ul') ? '<ul class="' . $element_class . '">' : '<ol class="' . $element_class . '">' : '';

				foreach( $files as $file ) {

					$file_url = wp_get_attachment_url($file->ID);
					$file_info = wfx_info_file( $file_url );
					$tool_tip = sprintf( __( 'Download %1$s (.%2$s file)', 'wonderflux' ), $file_info['nicetype'], $file_info['ext'] );
					$title_info = sprintf( __( ' (%1$s .%2$s file)', 'wonderflux' ), $file_info['nicetype'], $file_info['ext'] );


					$out .= '<' . esc_attr( $output_i ) . ' ' . esc_attr( $element_class ) . ' attachment-'.$file_info['ext'] . ' attachment-'.$file->ID . '">';
					$out .= '<a class="' . esc_attr( $link_class ) . 'get-attachment-'.$file_info['ext'] . ' get-attachment-'.$file->ID . '" ';
					$out .= 'title="' . esc_attr( $tool_tip . $link_title_end ) . '" ';
					$out .= 'href="' . esc_url( $file_url ) . '">';
					$out .= esc_attr( $output_start . get_the_title( $file->ID ) . $output_end.$title_info );
					$out .= '</a></' . esc_attr( $output_i ) . '>' . "\n";
					$out .= "\n";

				}

				$out .= ($output == 'ul' || $output == 'ol') ? ($output == 'ul') ? '</ul>' : '</ol>' : '';
				$out .= ( $div_wrap ) ? '</div>' : '';
			}

		endif;

		$out .= "\n";
		return $out;

	}


	/**
	 * Creates page navigation for lists of results like archive or query views.
	 *
	 * @since	0.93
	 * @version	2.0
	 *
	 * @param	[string] $element		What tag to use to wrap output (can be empty to setup at template level). [p]
	 * @param	[string] $start			Opening text string. [Page ]
	 * @param	[string] $seperator		Seperator between pages. [ of ]
	 * @param	[string] $current_span	CSS span class around current page number (set to blank to remove span). [page-counter-current]
	 * @param	[string] $total_span	CSS span class around total page number (set to blank to remove span). [page-counter-total]
	 * @param	[string] $always_show	No output is shown if there is only 1 page of results, setting to 'Y' to always show (ie page 1 of 1). Y/N [N]
	 * @param	[string] $navigation	Display next and previous navigation either side of the page display. Y/N [N]
	 * @param	[string] $nav_span		CSS span class around totalnavigation links (set to blank to remove span). Y/N [page-counter-navigation]
	 * @param	[string] $previous		Text for previous link. [&lt; ]
	 * @param	[string] $next			Text for next link. Y/N [ &gt;]
	 * @param	[string] $div			Wrap output in containing <div>. Y/N [Y]
	 * @param	[string] $div_class		Containing <div> class if used. [page-counter]
	 *
	 * @todo	Review code, make smarter!
	 * @todo	Extend with wp_link_pages() type functionality so it can function with paged single pages, not just query lists.
	 */
	function wf_page_counter($args) {

		$defaults = array (
			'element' => 'p',
			'start' => esc_attr__('Page ', 'wonderflux'),
			'seperator' => esc_attr__(' of ', 'wonderflux'),
			'current_span' => 'page-counter-current',
			'total_span' => 'page-counter-total',
			'always_show' => 'N',
			'navigation' => 'N',
			'nav_span' => 'page-counter-navigation',
			'previous' => '&lt; ',
			'next' => ' &gt;',
			'div' => 'Y',
			'div_class' => 'page-counter'
		);

		// Dont show navigation if this is a single post
		if (is_single()) {
			// Silence is golden
		} else {

			$args = wp_parse_args( $args, $defaults );
			extract( $args, EXTR_SKIP );

			// Clean up ready to use
			$element = ($element == 'p') ? $element : wp_kses_data($element, '');
			$start = ($start == 'Page ') ? $start : wp_kses_data($start, '');
			$seperator = ($seperator == ' of ') ? $seperator : wp_kses_data($seperator, '');
			$current_span = ($current_span == ' of ') ? $current_span : wp_kses_data($current_span, '');
			$total_span = ($total_span == ' of ') ? $total_span : wp_kses_data($total_span, '');
			$always_show = ($always_show == 'N') ? $always_show : 'Y';
			$navigation = ($total_span == 'N') ? $navigation : wp_kses_data($navigation, '');
			$nav_span = ($total_span == 'page_num_nav') ? $nav_span : wp_kses_data($nav_span, '');
			$previous = ($previous == '&lt; ') ? $previous : wp_kses_data($previous, '');
			$next = ($next == ' &gt;') ? $next : wp_kses_data($next, '');
			// If someone has removed the span CSS classes definition, dont render to screen
			$current_span = (!$current_span == '') ? '<span class="'.$current_span.'">' : '';
			$current_span_close = (!$current_span == '') ? '</span>' : '';
			$nav_span = ($nav_span == '') ? '<span class="'.$nav_span.'">' : '';
			$nav_span = (!$nav_span == '') ? '</span>' : '';
			$nav_span_close = (!$nav_span == '') ? '</span>' : '';
			$total_span = (!$total_span == '') ? '<span class="'.$total_span.'">' : '';
			$total_span_close = (!$current_span == '') ? '</span>' : '';
			$div = ($div == 'Y') ? 'Y' : 'N';
			$div_class = ($div_class == 'page-counter-navigation') ? $div_class : wp_kses_data($div_class, '');



			// get total number of pages
			global $wp_query;
			$total = $wp_query->max_num_pages;

			// Setup current page
			$current = 1;
			$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

			$output = ($div == 'Y') ? '<div class="' . $div_class . '">' : '';
			$output .= ($element == '') ? '' : '<'.$element.'>';
			$output .= ($navigation == 'N') ? '' : $nav_span . $this->wf_previous_posts_link($previous) . $nav_span_close;
			$output .= esc_html( $start );
			$output .= $current_span . $current.$current_span_close;
			$output .= esc_html( $seperator );
			$output .= $total_span . $total.$total_span_close;
			$output .= ($navigation == 'N') ? '' : $nav_span . $this->wf_next_posts_link($next) . $nav_span_close;
			$output .= ($element == '') ? '' : '</'. $element .'>';
			$output .= ($div == 'Y') ? '</div>' : '';

			// is_search() will not trigger on empty search string - WordPress wants all the posts
			// We want to use loop-content-no-search-results.php and do something smarter
			if ( isset($_GET['s']) && trim($_GET['s']) == '' ){
				$output = false;
			}

			// Always show results, even if just one page
			if ( $always_show == 'Y' ) {
				return $output;
			// only render if we have more than one page of results
			} elseif ( $total > 1 ) {
				return $output;
			}

		}

	}


	/**
	 * Return the previous posts page link.
	 * Somewhat similar to core WP get_previous_posts_link() function
	 * Just for internal use at the moment in wf_page_counter()
	 *
	 * TODO: Extend in the future and enable as a basic core function in functions.php
	 * TODO: Allow control of CSS class applied
	 *
	 * @param label - Previous page link text string
	 * @return string|null
	 *
	 * @since 1.1
	 * @updated 1.1
	 */
	function wf_previous_posts_link( $label = null ) {
		global $paged;
		if ( null === $label )
			$label = __( '&laquo; Previous Page', 'wonderflux' );
		if ( $paged > 1 ) {
			return '<span class="page-counter-nav-prev"><a href="' . previous_posts( false ) . '">' . esc_attr( wp_kses($label,'') ) .'</a></span>';
		}
	}


	/**
	 * Return the next posts page link.
	 * Somewhat similar to core WP get_next_posts_link() function
	 * Just for internal use at the moment in wf_page_counter()
	 *
	 * TODO: Extend in the future and enable as a basic core function in functions.php
	 * TODO: Allow control of CSS class applied
	 *
	 * @param label - Previous page link text string
	 * @return string|null
	 *
	 * @since 1.1
	 * @updated 1.1
	 */
	function wf_next_posts_link( $label = null, $max_page = 0 ) {
		global $paged, $wp_query;

		if ( !$max_page )
			$max_page = $wp_query->max_num_pages;
		if ( !$paged )
			$paged = 1;
		$nextpage = intval($paged) + 1;

		if ( null === $label )
			$label = __( '&raquo; Next Page', 'wonderflux' );

		if ( $nextpage <= $max_page ) {
			return '<span class="page-counter-nav-next"><a href="' . next_posts( $max_page, false ) . '">' . esc_attr( wp_kses($label,'') ) .'</a></span>';
		}
	}


	/**
	 * Powerful template part fragment cache for output optimisation.
	 * WARNING: This is kinda experimental and might change - keep an eye on development if using in production or why not patch?!
	 * Include a theme template file and cache include output as transient option for desired time in minutes.
	 * Works with most things, but may not work with more advanced functions/plugins that inject CSS or JS into header/footer or do other funky stuff!
	 *
	 * NOTE: Transient option saved as 'Child_Theme_Name_c_$part'.
	 *
	 * To flush cache append the following onto URL:
	 * Example 1 - flush all files www.mydomain.com/?flushcache_all=1
	 * Example 2 - flush individual cached element www.mydomain.com/?flushcache_NAME_OF_INCLUDE=1
	 *
	 * Filters available:
	 * wflux_allowed_cached_tags : array of allowed output tags used with kses.
	 *
	 * @since	1.1
	 * @version	2.1
	 *
	 * @param	[string] $part			REQUIRED - name of the file in active theme directory you want to include and cache, without file extension. []
	 * @param	[string] $file_ext		File extension of the file you want to cache (without '.'!). [php]
	 * @param	[int] $expire			Length of time (in minutes) that the cache persists. [360]
	 * @param	[string] $sanitise_in	Sanitises before saving to cache. html/none [html]
	 * @param	[string] $sanitise_out	Sanitises on output. html/none [html]
	 * @param	[string] $mimify		Remove whitespace before saving as transient. Y/N [Y]
	 * @param	[string] $transient_key	Define optional custom transient option name (NOTE: will be trimmed to 32 characters max). []
	 * @param	[string] $flushable		Can the cached item be force flushed/refreshed via url, user must have edit_theme_options capability. Y/N [Y]
	 * @param	[string] $output_start	Added to start of output (not saved in transient, runs through output sanitisation if set). [<!--cached-part-start-->]
	 * @param	[string] $output_end	Added to end of output (not saved in transient, runs through output sanitisation if set). [<!--cached-part-end-->]
	 * @param	[string] $echo			Echo or return output. Y/N [Y]
	 *
	 * @todo	Extend $sanitise_in and sanitise_out with more options.
	 * @todo	Should this be made location aware?
	 * @todo	Think about multisite integration - set_site_transient() - test!
	 * @todo	Extend $sanitise_in and sanitise_out with more options.
	 * @todo	Deeper transient key length - only 64 chars allowed in options table name? Use 45 characters or 32 max?? - Watch _transient_timeout_{$transient_key}
	 */
	function wf_get_cached_part($args) {

		$defaults = array (
			'part' => '',
			'file_ext' => 'php',
			'expire' => 360,
			'sanitise_in' => 'html',
			'sanitise_out' => 'html',
			'mimify' => 'Y',
			'transient_key' => '',
			'flushable' => 'Y',
			'output_start' => __('CACHED PART START', 'wonderflux'),
			'output_end' => __('CACHED PART END', 'wonderflux')
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( !$part ) return;

		// Setup WFX Data management class
		global $wfx_data_manage;

		$cached_data = false;
		$expire = ( (is_numeric($expire) ) ? $expire : 1 )*60; // Set to minutes
		$sanitise_in = ( $sanitise_in == 'html' ) ? 'html' : 'none';
		$sanitise_out = ( $sanitise_out == 'html' ) ? 'html' : 'none';

		// TODO: Transient key reported upto 32 characters max - have tested upto 45, 46 was stormy waters!
		// Best to play on safe side until further teting done (32)
		$transient_key = mb_substr( empty($transient_key) ? $this->get_clean_theme_name() . '_c_' . $part : $transient_key , 0, 32 );

		// Cache flush control/load data
		$flush_this = false;
		if( $flushable == 'Y' ) {
			if ( current_user_can('edit_theme_options') )
				$flush_this = ( isset($_GET['flushcache_all']) && $_GET['flushcache_all'] == 1 ) ? true : false;
				$flush_this = ( isset($_GET['flushcache_'.$part.'']) && $_GET['flushcache_'.$part.''] == 1 ) ? true : $flush_this;
		}

		$cached_data = ( !$flush_this ) ? get_transient( $transient_key ) : false;

		$allowed_tags = ( $sanitise_in == 'html' || $sanitise_out == 'html' ) ? $wfx_data_manage->allowed_tags('') : '';

		// Refresh cache
		if( empty( $cached_data ) ) {

			// Have to include this if outside of admin for file operations
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			WP_Filesystem();
			global $wp_filesystem;

			$cached_data = $wp_filesystem->get_contents( WF_THEME_URL . '/' . $part . '.' . $file_ext );
			$cached_data = ( $sanitise_in == 'html' ) ? wp_kses( $cached_data, apply_filters('wflux_allowed_cached_tags', $allowed_tags) ) : $cached_data;
			set_transient( $transient_key, ($mimify == 'Y') ? $wfx_data_manage->strip_whitespace( $cached_data ) : $cached_data, $expire );

		}

		// Output
		if ( !empty( $cached_data ) ) {

			$transient_timeout = get_option ( '_transient_timeout_' . $transient_key );

			$transient_timeout = ( !empty($transient_timeout) ) ? ' - Seconds to cache refresh: ' . ( $transient_timeout - time() ) : false;

			$output_start = '<!-- - - - ' . $output_start . ' - ' . $part . '.' . $file_ext . $transient_timeout . ' - - - -->';
			$output_end = '<!-- - - - ' . $output_end . ' - ' . $part . '.' . $file_ext . ' - - - -->';

			// By default we deep clean output with wp_kses just to be sure
			switch ( $sanitise_out ) {
				case 'html':
					return "\n" . wp_kses( $output_start . $cached_data . $output_end, apply_filters( 'wflux_allowed_cached_tags', $allowed_tags ) ) . "\n";
					break;
				default:
					return "\n" . esc_attr($output_start) . $cached_data . esc_attr($output_end) . "\n";
				break;
			}

		} else {

			return false;

		};

	}


	/**
	 * Builds a clickable link from supplied data.
	 *
	 * @since	1.1
	 * @version	1.1
	 *
	 * @param	[string] $url			REQUIRED - URL for link. []
	 * @param	[string] $title			Link title/tooltip. []
	 * @param	[string] $target		Link target attribute. []
	 * @param	[string] $class			CSS class for link. []
	 * @param	[string] $id			CSS ID for link. []
	 * @param	[string] $text			Text to show in link (defaults to $url if none supplied). []
	 * @param	[bool] $span			Wrap $text in optional <span>. true/false [false]
	 * @param	[string] $span_class	Adds CSS class to optional <span>. []
	 * @param	[string] $type			Type of link resource. eg: application/pdf. []
	 * @param	[string] $rel			Relationship of link to current view/context. []
	 *
	 * @todo	Review code, make smarter!
	 */
	function wf_build_hyperlink($args){

		$defaults = array (
		'url'		=> '',
		'title'		=> '',
		'target'	=> '',
		'id'		=> '',
		'class'		=> '',
		'text'		=> '',
		'span'		=> false,
		'span_class'=> '',
		'type'		=> '',
		'rel'		=> '',
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( empty($url) ) return;

		$o = '<a ';
		$o .= 'href="' . esc_url_raw( $url ) . '"';
		$o .= ( !empty($title) ) ? ' title="'. esc_attr( $title ) . '"': '';
		$o .= ( !empty($target) ) ? ' target="'. esc_attr( $target ) . '"': '';
		$o .= ( !empty($id) ) ? ' id="'. esc_attr( $id ) . '"' : '';
		$o .= ( !empty($class) ) ? ' class="'. esc_attr( strtolower($class) ) . '"' : '';
		$o .= ( !empty($type) ) ? ' type="'. esc_attr( $type ) . '"' : '';
		$o .= ( !empty($rel) ) ? ' type="'. esc_attr( $rel ) . '"' : '';
		$o .= '>';
		$o .= ( !empty($span) ) ? '<span' : '';
		$o .= ( !empty($span) && !empty($span_class) ) ? ' class="' . esc_attr( strtolower($span_class) ) . '"' : '';
		$o .= ( !empty($span) ) ? '>' : '';
		$o .= (!empty($text)) ? esc_html( $text ) : esc_html( $url );
		$o .= ( !empty($span) ) ? '</span>' : '';
		$o .= '</a>';

		return $o;

	}


	/**
	 * Outputs an array as a string with a seperator in-between each.
	 * Doesn't add to last item - useful for comma sperated output.
	 *
	 * @since	1.1
	 * @version	1.2
	 *
	 * @param	[array] $values			REQUIRED - Input array (single dimenional).
	 * @param	[string] $seperator		Seperator between items output from array. [, ]
	 * @param	[string] $start			Text string at start of each item output. []
	 * @param	[string] $end			Text string at end of each item output. []
	 * @param	[bool] $esc				Use esc_html() on $values and $seperator output. [true]
	 *
	 */
	function wf_array_to_delimited_string( $args ) {

		$defaults = array (
			'values'	=> '',
			'seperator'	=> ', ',
			'start'		=> '',
			'end'		=> '',
			'esc'		=> true
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( !is_array($values) || empty($values) ) return;

		$count_all = count( $values );
		$counter = 1;
		$output = '';

		foreach( $values as $key => $value ) {

			if ( !empty($value) ){
				$output .= $value;
				$output .= ( $counter != $count_all ) ? $seperator : '';
				$counter++;
			}

		}

		return ( $esc == true ) ? $start . esc_html( $output ) . $end : $start . $output . $end;

	}


}


/**
 * Social networking functionality.
 *
 * @since	0.931
 * @version	1.2
 *
 * @todo Check over more recent sharing code.
 */
class wflux_display_social extends wflux_data {

	protected $share_url; // Common sharing URL
	protected $gplus_id; // Google Plus 1 ID - Increments as more plus one buttons called
	protected $fb_like_id; // Facebook like ID - Increments as more like buttons called
	protected $twit_like_id; // Facebook like ID - Increments as more tweet buttons called
	protected $share_site_name; // Common site name
	protected $share_title; // Common title
	protected $share_description; // Common description

	protected $og_image;

	function __construct() {

		parent::__construct();

		global $post;
		if ( isset($post->ID) ):
			switch ( wfx_info_location('') ) {
				case 'home':
					$this->share_url = home_url();
				break;
				case 'single' || 'page':
					$this->share_url = ( isset($post->ID) ) ? get_permalink($post->ID) : home_url();
				break;
				default:
					$this->share_url = home_url();
				break;
		}
		endif;
		$this->gplus_id = 0;
		$this->fb_like_id = 0;
		$this->twit_like_id = 0;
		$this->share_site_name = wp_strip_all_tags( get_bloginfo( 'name', 'raw' ) );
		$this->share_title = ( isset($post->ID) ) ? wp_strip_all_tags( get_the_title($post->ID) ) : wp_strip_all_tags( get_bloginfo( 'name', 'raw' ) );
		$this->share_description = wp_strip_all_tags( get_bloginfo( 'description', 'raw' ) );
		$this->og_image = apply_filters( 'wflux_social_og_meta_image', WF_THEME_URL.'/images/supporting/social_thumbnail.jpg' );
	}


	/**
	 * Inserts associated social sharing related (Open Graph) meta tags in <head> if required.
	 *
	 * @since	0.931
	 * @version	0.931
	 *
	 * @param	none
	 *
	 * @todo	Test and dont use if using Yoast SEO to control this.
	 */
	function wf_social_meta() {
		if ( $this->wfx_doc_type == 'XHTML/RDFa' ):
			add_action( 'wp_head', array( $this, 'wf_og_meta' ), 4 );
		endif;
	}


	/**
	 * Builds Open Graph meta tags in <head> if required.
	 * Used by wf_social_meta()
	 *
	 * @since	0.931
	 * @version	0.931
	 *
	 * @param	none
	 *
	 * @todo	Test and dont use if using Yoast SEO to control this.
	 */
	function wf_og_meta() {
		//TODO: build extra og:type support

		$type = ( is_home() || is_front_page() ) ? 'website' : 'article';

		echo '<meta property="og:title" content="' . esc_attr( $this->share_title ) . '"/>'."\n";
		echo '<meta property="og:type" content="' . $type . '"/>'."\n";
		echo '<meta property="og:image" content="' . esc_url( $this->og_image ). '"/>'."\n";
		echo '<meta property="og:url" content="' . esc_url( $this->share_url ) . '"/>'."\n";
		echo '<meta property="og:site_name" content="' . esc_attr( $this->share_site_name ) . '"/>'."\n";
		echo '<meta property="og:description" content="' . esc_attr( $this->share_description ) . '"/>'."\n";
		if ( trim( $this->wfx_fb_admins ) != '' ):
			echo '<meta property="fb:admins" content="' . wp_kses( $this->wfx_fb_admins, '' ) . '"/>'."\n";
		endif;
		if ( trim( $this->wfx_fb_app ) != '' ):
			echo '<meta property="fb:app_id" content="' . wp_kses( $this->wfx_fb_app, '' ) . '"/>'."\n";
		endif;
	}


	/**
	 * Outputs a Facebook like button with counter.
	 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
	 *
	 * @since	 0.931
	 * @version  0.931
	 *
	 * @param	[string] $size			Size of social button. small/tall [small]
	 * @param	[bool] $send			Show additional send button. true/false [false]
	 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
	 *
	 * @todo	Review code!
	 */
	function wf_fb_like( $args ) {

		$defaults = array (
			'size' => 'small',
			'send' => 'false',
			'url' => false,
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$this->fb_like_id++;

		// Validate
		$size = ( $size == 'small' ) ? 'button_count' : 'box_count';
		$send = ( $send == 'true' ) ? 'true' : 'false';

		if ( trim($url) == '' || !isset($url) ):
			$url = $this->share_url;
		elseif ( $url == 'home'):
			$url = home_url();
		endif;

		// Build output
		$this->wf_fb_like_render( $size, $send, $url );
		// Add JS once
		if ( $this->fb_like_id > 1 ) return;
		add_action( 'wf_footer', array( $this, 'wf_fb_like_js' ) );
	}


	/**
	 * Builds the Facebook like button
	 * TODO: Extend functionality
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_fb_like_render( $size, $send, $url ){
		$width = ( $size == 'button_count' ) ? 82 : 50;
		echo '<fb:like href="' . esc_url( $url ) . '" send="' . $send . '" layout="' . $size . '" width="' . $width . '" show_faces="false" action="like"></fb:like>';

	}


	/**
	 * Builds the Javascript that is used to connect to Facebook
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_fb_like_js() {
		// REF: status > check login status, cookie > enable cookies to enable server to access session, xfbml > parse xfbml
		?>

		<!-- Facebook Connect -->
		<div id="fb-root"></div>
		<script type='text/javascript'>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId  : '<?php echo wp_kses( $this->wfx_fb_app, '' );?>',
		      status : true,
		      cookie : true,
		      xfbml  : true
		    });
		  };

		  (function() {
		    var e = document.createElement('script');
		    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
		    e.async = true;
		    document.getElementById('fb-root').appendChild(e);
		  }());
		</script>
		<?php
	}


	/**
	 * Outputs a Google Plus 1 button with fully valid XHTML rendering.
	 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
	 *
	 * @since	1.1
	 * @version	1.2
	 *
	 * @param	[string] $size			Size of social button. small/medium/standard/tall [medium]
	 * @param	[string] $count			Show count of shares or not ($size=tall always shows count). no_count/show_count [show_count]
	 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
	 *
	 * @todo	Review code!
	 */
	function wf_g_plus_1( $args ) {

		$defaults = array (
			'size' => 'medium',
			'count' => 'show_count',
			'url' => false,
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$this->gplus_id++;

		// Validate
		$size_accept = array('small', 'medium', 'standard', 'tall');
		$size = ( $size == 'medium' || in_array($size, $size_accept) ) ? $size : 'medium';

		$count = ( $size == 'tall' || $count == 'no_count' ) ? 'false' : 'true';

		if ( $url == '' || !isset($url) ):
			$url = $this->share_url;
		elseif ( $url == 'home'):
			$url = home_url();
		else: // Custom URL supplied
		endif;
		$url = ', \'href\': \'' . $url . '\' ';

		$target = apply_filters( 'wflux_social_gplus_target', 'social-link-gplus' );

		// Build output
		// TODO: Switch for controlling different types of output here
		$this->wf_gplus_div( $this->gplus_id, $target );
		add_action('wf_social_gplus_js_params_1', create_function('','echo " gapi.plusone.render( \'' . sanitize_key($target) . '-' . $this->gplus_id . '\', { \'size\': \'' . $size . '\', \'count\': \'' . $count . '\' ' . $url . '} );";') );
		add_action('wf_social_gplus_js_params_2', create_function('','echo "var div = getElementByIdUniversal( \'' . sanitize_key($target) . '-' . $this->gplus_id . '\' ); ";') );
		// Add JS once
		if ( $this->gplus_id > 1 ) return;
		add_action( 'wf_footer', array( $this, 'wf_gplus_js' ) );

	}


	/**
	 * Builds the Javascript that is used to render the Plus 1 button.
	 * Used internally by wf_gplus.
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_gplus_js() {
		?>
		<!-- Google Plus-1 Buttons -->
		<script type='text/javascript'>
			function plusoneready() {<?php do_action( 'wf_social_gplus_js_params_1' ); ?>}

			function getElementByIdUniversal( id ) { return ( document.getElementById ) ? document.getElementById( id ) : document.all[ id ]; }

			(function() {
				var gp = document.createElement( 'script' );
				gp.type = 'text/javascript';
				gp.async = true;
				gp.src = 'https://apis.google.com/js/plusone.js';
				gp.onload = plusoneready;
				<?php do_action( 'wf_social_gplus_js_params_2' ); ?>

				div.parentNode.insertBefore( gp, div );
			})();

		</script>
		<?php
	}


	/**
	 * Displays the DIV container that is used to render the Plus 1 button.
	 * Used internally by wf_gplus to display an empty div - a lesser sin than invalid markup - naughty Google!
	 * @param id - Unique ID, passed from wf_gplus
	 * @param target - Passed from wf_gplus
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_gplus_div( $id, $target ){ echo '<div id="' . $target . '-' . $id . '"></div>' . "\n"; }


	/**
	 * Outputs a Twitter share button with counter.
	 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
	 *
	 * @since	0.931
	 * @version	0.931
	 *
	 * @param	[string] $size			Size of social button. small/tall [small]
	 * @param	[string] $count			Show count of shares or not (no count only available on small). no_count/show_count [show_count]
	 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
	 *
	 * @todo	Review code!
	 */
	function wf_twit_share( $args ) {

		$defaults = array (
			'size' => 'small',
			'count' => 'show_count',
			'url' => false,
			'tweet' => false,
			'id' => false,
			'recommend' => false,
			'recommend_text' => false,
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$this->twit_like_id++;

		// Validate

		if ( $size == 'small'):
			$size = ($count != 'no_count') ? 'horizontal' : 'none';
		elseif ( $size == 'tall'):
			$size = ($count != 'no_count') ? 'vertical' : 'none';
		else:
			$size = 'none';
		endif;

		// Twitter button pulls URL dynamically if not set
		if ( $url == '' || !isset($url) ):
			$url = false;
		elseif ( $url == 'home'):
			$url = home_url();
		endif;

		// Build output
		$this->wf_twit_share_render($url,$tweet,$size,$id,$recommend,$recommend_text);
		// Add JS once
		if ( $this->twit_like_id > 1 ) return;
		add_action( 'wf_footer', array( $this, 'wf_twit_share_js' ) );
	}


	/**
	 * Builds the Javascript that is used to render the Twitter button.
	 * Cant use wp_enqueue_script as it gets dynamically inserted in button(s) inserted
	 *
	 * @param size - Size of share button. 'small', 'tall' [small]
	 * @param send - Show additional send button. 'true', 'false' [false]
	 * @param url - URL to like/share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'.
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_twit_share_render($url,$tweet,$size,$id,$recommend,$recommend_text) {
		echo '<a href="http://twitter.com/share?';
		echo ( isset($url) ) ? 'url='. urlencode( esc_url($url) ) . '&amp;' : '';
		echo ( isset($tweet) ) ? 'text='. $this->wf_urle( $tweet ) . '&amp;' : '';
		echo 'count='. $size . '&amp;';
		echo ( isset($id) ) ? 'via='. $this->wf_urle( $id ) . '&amp;' : '';
		echo ( isset($recommend) && !isset($recommend_text) ) ? 'related='. $this->wf_urle( $recommend ) . '&amp;' : '';
		echo ( !isset($recommend) && isset($recommend_text) ) ? 'related='. $this->wf_urle( $recommend_text ) . '&amp;' : '';
		echo ( isset($recommend) && isset($recommend_text) ) ? 'related='. $this->wf_urle( $recommend ) . ':' . $this->wf_urle( $recommend_text ) . '&amp;' : '';
		echo '" class="twitter-share-button" rel="nofollow">Tweet</a>';
	}


	/**
	 * Builds the Javascript that is used to render the Twitter button.
	 * Cant use wp_enqueue_script as it gets dynamically inserted if button(s) inserted in content and cant hook into WordPress early enough
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_twit_share_js() {
		echo '<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>';
	}


	/**
	 * Outputs a LinkedIn share button with counter.
	 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
	 *
	 * @since	1.0rc2
	 * @version	1.0rc2
	 *
	 * @param	[string] $size			Size of social button. small/tall [small]
	 * @param	[string] $count			Show count of shares or not (no count only available on small). no_count/show_count [show_count]
	 * @param	[string] $url			URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'. []
	 *
	 * @todo	Review code!
	 */
	function wf_linkedin_share( $args ) {

		$defaults = array (
			'size'	=> 'small',
			'count'	=> 'show_count',
			'url'	=> false,
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$size = ( $size == $defaults['size'] ) ? $defaults['size'] : 'tall';
		$count = ($count == $defaults['count'] ) ? $defaults['count'] : 'no_count';
		// url - gets cleaned later if required

		//echo 'BUG CATCH:'.$size.$count;

		// LinkedIn button pulls URL dynamically if not set
		if ( $url == '' || !isset($url) ):
			$url = false;
		elseif ( $url == 'home'):
			$url = home_url();
		endif;

		// Build output
		$this->wf_linkedin_render($url,$size,$count);
	}


	/**
	 * Internal function to render LinkedIn button Javascript output.
	 * REF: https://developer.linkedin.com/share-plugin-reference
	 *
	 * @since 1.0rc2
	 * @updated 1.0rc2
	 */
	function wf_linkedin_render($url,$size,$count) {
		// No size attribute, just position of counter
		$position = ($size == 'small') ? 'right' : 'top';
		$count = ( $count == 'show_count' ) ? ' data-counter="' . $position . '"' : false;
		echo '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>' . '<script type="IN/Share"' . $count . ' data-url="' . esc_url($url) . '" ></script>';
	}


	/**
	 * Just like urlencode, but replaces + with %20 for URL encoded params
	 *
	 * @param $string - String of text
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_urle($string) {
		return str_replace('+', '%20', urlencode($string));
	}
}
?>