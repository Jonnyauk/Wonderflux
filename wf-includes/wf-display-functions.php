<?php
//TODO: Setup for translation
/**
* @since 0.913
* @updated 1.0RC4
* Core display functions that output code
*/
class wflux_display_code extends wflux_data {

	protected $xml_namespaces;

	function __construct() {
		parent::__construct();
		$this->xml_namespaces = array(); // Holds all XML namespaces to build into head
	}

	/**
	* Builds the start of the head with doc type declaration
	*
	* @since 0.931
	* @updated 0.931
	*
	* @param $doctype (limited variable string) : Document type : 'transitional' (default), 'strict', 'frameset', '1.1', '1.1basic', 'html5', 'XHTML/RDFa'
	* @param $lang (user variable string) : Alphabetic International language code : 'en' (default), USER INPUT
	* @param $content : Document content : 'html' (default)
	* @param $charset (user variable string) : Character encoding : 'utf8' (default), USER INPUT
	*/
	function wf_head_open($args) {

		$defaults = array (
			'doctype' => $this->wfx_doc_type,
			'lang' => $this->wfx_doc_lang,
			'content' => 'html',
			'charset' => $this->wfx_doc_charset
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Language code
		if ($lang !='en' || strlen( trim($lang) ) < 6): $lang_output = sanitize_key($lang);
		else: $lang_output = 'en';
		endif;

		// Document type
		switch ($doctype) {
			case 'transitional': $doctype_output = 'XHTML 1.0 Transitional'; $doctype_link_output = 'TR/xhtml1/DTD/xhtml1-transitional'; break;
			case 'strict': $doctype_output = 'XHTML 1.0 Strict'; $doctype_link_output = 'TR/xhtml1/DTD/xhtml1-strict'; break;
			case 'frameset': $doctype_output = 'XHTML 1.0 Frameset'; $doctype_link_output = 'TR/xhtml1/DTD/xhtml1-frameset'; break;
			case '1.1': $doctype_output = 'XHTML 1.1'; $doctype_link_output = 'TR/xhtml11/DTD/xhtml11'; break;
			case '1.1basic': $doctype_output = 'XHTML Basic 1.1'; $doctype_link_output = 'TR/xhtml-basic/xhtml-basic11'; break;
			case 'XHTML/RDFa': $doctype_output = 'XHTML+RDFa 1.0'; $doctype_link_output = 'MarkUp/DTD/xhtml-rdfa-1'; break;
			default: $doctype_output = '1.0 Transitional'; $doctype_link_output = 'TR/xhtml1/DTD/xhtml1-tansitional'; break;
		}

		// Character set
		$charset_output = wp_kses($charset, '');

		// Output
		if ($doctype == 'html5'): $output = '<!DOCTYPE html>' . "\n" . '<html lang="'.$lang_output.'">' . "\n";
		else:
			// Content type
			if ($content !='html' || strlen($content) <= 4) :
				$content_output = sanitize_key($content, '');
			else: $content_output = 'html'; endif;

			$this->xml_namespace_build();
			$namespaces = '';
			foreach ( $this->xml_namespaces as $key=>$value ) { $namespaces .= $value . ' '; }

			$lang_extra = ( $doctype == 'XHTML/RDFa' ) ? '' : 'lang="'.$lang_output.'" ';
			$output = '<!DOCTYPE ' . 'html PUBLIC "-//W3C//DTD ' . $doctype_output . '//' . strtoupper($lang_output) . '" "http://www.w3.org/' . $doctype_link_output . '.dtd">';
			$output .="\n";
			$output .='<html ' . 'xml:lang="'.$lang_output.'" ' . $lang_extra . $namespaces . '>';
			$output .="\n";
		endif;
		echo $output;
	}


	/**
	* Builds array of XML namespace attributes to pass to wf_head_meta_xml_namespace
	* @filter wf_head_meta_xml_namespace_main - Full output of default XML namespace definition.
	*
	* @since 0.931
	* @updated 0.931
	*/
	function xml_namespace_build() {
		//$this->xml_namespaces[] = wp_strip_all_tags($name);
		// Default namespace
		$this->xml_namespaces[] = apply_filters( 'wf_head_meta_xml_namespace_main', 'xmlns="http://www.w3.org/1999/xhtml"' );
		// If using Facebook need Open Graph AND Facebook namespace definitions
		if ( $this->wfx_doc_type == 'XHTML/RDFa' ):
			$this->xml_namespaces[] = 'xmlns:og="http://ogp.me/ns#"';
			$this->xml_namespaces[] = 'xmlns:fb="http://www.facebook.com/2008/fbml"';
		endif;
	}


	/**
	* Inserts the Content-Type/charset meta tag
	*
	* @since 0.931
	* @updated 0.931
	*
	* @param $doctype (limited variable string) : Document type : 'transitional' (default), 'strict', 'frameset', '1.1', '1.1basic', 'html5'
	* @param $content : Document content : 'html' (default)
	* @param $charset (user variable string) : Character encoding : 'utf8' (default), USER INPUT
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
	* Builds the title in the head of the template
	*
	* @since 0.1
	* @updated 1.0
	*/
	function wf_head_title($args) {

		echo '<title>';

		// If YOAST WordPress SEO plugin active let it manage titles as it does a great job!
		if ( defined('WPSEO_BASENAME') ) {
			wp_title('', true, 'right');

		} else {

			if (is_home() || is_front_page()) {

				echo get_bloginfo( 'name', 'display' );

				$this_desc = esc_attr( get_bloginfo( 'description', 'display' ) );

				if ($this_desc == 'Just another WordPress site') {
					//Silence is golden - site has default description which we dont want to show
				} else {
					//Proper site description in options
					echo ' - ';
					echo esc_attr( get_bloginfo( 'description', 'display' ) );
				}
			}

			// If it's a feed, lets add that into the title
			elseif ( is_feed() ) {
				echo get_bloginfo( 'name', 'display' ) . ' feed';
			}

			elseif ( is_search() ) {
				printf( __( 'Search results for  %1$s from %2$s', 'wonderflux' ), get_search_query(), get_bloginfo( 'name', 'display' ) );
			}

			//DEFAULT FALLBACK
			else {
				wp_title(' - ', true, 'right');
				bloginfo( 'name' );
			}
		}

		echo '</title>';
		echo "\n";

	}


	/**
	* Inserts structure CSS
	*
	* @since 0.72
	* @updated 0.93
	*/
	function wf_head_css_structure() {
		if (WF_THEME_FRAMEWORK_REPLACE == false) {
			$path = WF_CONTENT_URL . '/css/wf-css-core-structure.css';
			$version = WF_VERSION;
			$id = 'wfx-structure';

			// Allow filtering
			$path = apply_filters( 'wflux_css_structure_path', $path );
			$version = apply_filters( 'wflux_css_structure_version', $version );
			$id = apply_filters( 'wflux_css_structure_id', $id );

			wp_register_style( $id , $path,'',$version,'screen, projection' );
			wp_enqueue_style( $id );
		}
	}


	/**
	* @since 0.72
	* @updated 0.93
	* Dynamic grid builder
	*/
	function wf_head_css_columns($args) {
		if (WF_THEME_FRAMEWORK_REPLACE == false) {
			$path = WF_CONTENT_URL . '/css/wf-css-dynamic-columns.php';
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
			add_filter( 'style_loader_tag', array($this,'wf_head_css_add_args'));
		}
	}


	/**
	* @since 0.80
	* @updated 0.93
	* Core layout grid CSS
	*/
	function wf_head_css_ie($args) {
		if (WF_THEME_FRAMEWORK_REPLACE == false) {
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
	* Inserts main theme CSS
	*
	* @since 0.72
	* @updated 1.0RC4
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
	* Inserts theme CSS sizing parameters - used in filter if required
	* Picks up on version set as 'ver=wfx-dynamic':
	* wp_register_style( '', '','','wfx-dynamic','');
	* Appends Wonderflux size URL params
	* TODO: Investigate a cleaner way to do this!
	*
	* @since 0.93
	* @updated 0.93
	*/
	function wf_head_css_add_args($input) {
		$vars = '&amp;w='.$this->wfx_width.
		'&amp;p='.$this->wfx_position.
		'&amp;sbp='.$this->wfx_sidebar_primary_position.
		'&amp;cw='.$this->wfx_columns_width.
		'&amp;c='.$columns_num = $this->wfx_columns.'';
		return str_replace(array('ver=wfx-dynamic'), array("$vars"), $input);
	}


	/**
	* @since 0.93
	* @updated 0.93
	* VERY IMPORTANT!
	* Removes functionality if child theme override file in place
	* Requires at least 'style-framework.css' and optionally 'style-framework-ie,css' in your child theme directory
	*/
	function wf_head_css_replace() {
		$path = WF_THEME_URL.'/style-framework.css';
		$path_ie = WF_THEME_URL.'/style-framework-ie.css';
		$version = $this->wfx_mytheme_version_clean;
		$id = 'framework';
		$id_ie = 'framework-ie';
		$media = 'screen, projection';

		// Allow filtering
		$media = apply_filters( 'wflux_css_theme_framework_media', $media );

		wp_register_style( $id, $path,'', $version, $media );
		wp_enqueue_style( $id );

		wp_register_style( $id_ie, $path_ie,'', $version, $media );
		wp_enqueue_style( $id_ie );

		// IMPORTANT - Add conditional IE wrapper
		$GLOBALS['wp_styles']->add_data( $id_ie, 'conditional', 'lt IE 8' );
	}


	/**
	* @since 0.931
	* @updated 0.931
	* VERY IMPORTANT!
	* Opens <body> tag using dynamic WordPress body and sidebar/content CSS definition classes
	*/
	function wf_body_tag($args) {

		$output = "\n";
		$output .= '<body class="';
		$output .= join( ' ', get_body_class() );
		$output .= ( $this->wfx_sidebar_1_display == 'Y' ) ? ' content-with-sidebar-1' : ' content-no-sidebar-1';
		$output .= ( $this->wfx_sidebar_1_display == 'Y' && $this->wfx_sidebar_primary_position == 'left' ) ? ' sidebar-1-left' : '';
		$output .= ( $this->wfx_sidebar_1_display == 'Y' && $this->wfx_sidebar_primary_position == 'right' ) ? ' sidebar-1-right' : '';
		$output .= ' width-'.$this->wfx_width;
		$output .= '">' . "\n";
		echo $output;
	}


	/**
	 *
	 * A more flexible post class function
	 *
	 * @since 1.0RC3
	 * @updated 1.0RC3
	 *
	 * @param $extra (string) : Comma seperated, extra CSS classes you wish to add
	 * @param $extra_position (string) : 'after' or 'before' - position of your additional $extra CSS classes
	 * @param $just_string (Y/N) : Wrap the output in 'class=""' like normal WordPress
	 * @filter wflux_post_class : Filter the core WordPress post_class values
	 * @filter wflux_post_class_first : Filter the extra CSS class added to start of last post in loop (default 'first-in-loop')
	 * @filter wflux_post_class_last : Filter the extra CSS class added to end of last post in loop (default 'last-in-loop')
	 * @filter wflux_post_class_single : Filter the extra CSS class added to single post views (default 'single-post')
	 * @filter wflux_post_class_multiple : Filter the extra CSS class added to multiple post views (default 'multiple-posts')
	 * NOTES on 'wflux_post_class' filter:
	 * Use $post_class var in your filter function if you want access to core WP post classes
	 * You can then do things like:
	 * unset($post_class[0]) Remove an item from the array (where [0] is the index/key in the array of WP class values)
	 * $post_class[] = 'my-new-class' Add an item to the array (Can also be done with the $extra param in function if required - which is simpler!)
	 *
	*/
	function wf_post_class($args){

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
	*
	* @since 0.3
	* @updated 0.931
	*
	* Footer credit
	*
	*/
	function wf_performance() {
		echo '<!-- ' . sprintf( __( '%1$s queries in %2$s seconds', 'wonderflux' ), get_num_queries(), timer_stop($display = 0, $precision = 4) ) . ' -->'."\n";
	}


	/**
	*
	* @since 0.71
	* @updated 0.931
	*
	* Footer code comment credit
	*
	*/
	function wf_code_credit() {
		echo '<!-- ' . apply_filters( 'wflux_comment_code_credit', __('Powered by WordPress and the Wonderflux theme framework', 'wonderflux') ) . ' -->' . "\n";
	}


	/**
	* Returns values as set
	* Useful when doing conditional layout functionality to test against conditions
	* @since 0.93
	* @lastupdate 0.93
	* TODO: Build rest of elements for output
	* @return string
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
	* @since 1.0RC4
	* @updated 1.0RC4
	* IMPORTANT - Configures WordPress $content_width global for embeded media such as YouTube video
	*/
	function wf_content_width() {
		global $content_width;
		if ( !isset( $content_width ) ) $content_width = $this->wfx_content_size_px;
	}

}


/**
* @since 0.913
* @updated 1.0RC4
* Core display functions that output CSS
*/
class wflux_display_css extends wflux_display_code {

	/**
	*
	* @since 0.2
	* @updated 1.0RC4
	*
	* Defines size conventions to use in template grid systems to avoid putting actual numbers into templates
	* By using this function to define containers, you can dynamically resize the whole layout
	* The only thing to watch out for is using definitions like:
	* - 'quarter' when your columns arent divisible by 4
	* - 'third' when your columns arent divisible by 3
	* - By checking the output of the wf_css_info function in the head of your document, you can find out lots of useful info!
	*
	* You dont have to use these and can just use the normal blueprint CSS style definitions if you like which is generated from your Stylelab options
	* NOTE: If you don't use this function and revert back to normal BlueprintCSS div sizing, you will loose ability to 'resize' your layout
	*
	* @param size = Relative size definition to full width of site - eg 'half', 'quarter', 'twothird'
	* @param class = Extra CSS classes you want to include in definition, uses spaces if more than one CSS class
	* @param id = CSS div ID if required
	* @param last = Put on last container inside row, eg half, half LAST
	* @param move = Push and pull a div, not using at moment
	* @param divoutput = Wraps output in opening and closing div tags - useful for blocks of code
	* @param columns = Size of div in columns, if set over-rides $size
	*
	*/
	function wf_css($args) {

		$defaults = array (
			'size' => 'full',
			'class' => '',
			'id' => '',
			'last' => '',
			'move' => '',
			'divoutput' => 'N',
			'columns' => 0
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$wf_fullwidth = $this->wfx_width;
		$wf_columns = $this->wfx_columns;
		$wf_single = $this->wfx_columns_width;

		//Blank or invalid param passed so clear it
		if (!$last || !$last == 'N') {
			$last = '';
		} else {
			//Put in space
			$last = ' last';
		}

		// Prepare extra CSS classes for display if supplied
		if ($class !='') {
			$class_clean = ' ' . wp_kses($class, '');
		} else {
			$class_clean = '';
		}

		// Prepare extra CSS id for display if supplied
		if ($id !='') {
			$id_clean = ' id="' . wp_kses($id, '') . '"';
		} else {
			$id_clean = '';
		}

		// IMPORTANT divoutput parameter is for use in theme functions file
		// It encloses the dynamic size class in '<div' and '>' for you
		// Sugest you dont use this at get_template_part xhtml code level as you want to see clearly where the divs open and close
		// Could be used in get_template_part code blocks
		if ($divoutput == 'Y') {
			$divout_open_clean = '<div ';
			$divout_close_clean = '>';
		} else {
			$divout_open_clean = '';
			$divout_close_clean = '';
		}

		$css_core_def = 'span-';
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
	*
	* @since 0.913
	* @updated 0.913
	*/
	function wf_css_close($args) {
		echo '</div>';
	}


	/**
	* @since 0.4
	* @updated 0.931
	* Builds comment for head of document displaying current Wonderflux layout config
	* TODO: Build this function properly!
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
	* @since 0.93
	* @updated 0.93
	* Encloses the main site areas in specified layout divs, only when required
	* NOTE: Inserted at hooks priority 2 or 9, to allow you to hook in with your own functions at:
	* priority 1 for before everything
	* priority 4+ if using Wonderflux widgets auto-inserted on a hook
	*
	*/
	function wf_layout_build($args) {

		// Main content
		if ( $this->wfx_content_1_display == 'Y' && $this->wfx_sidebar_1_display == 'Y' ) {
				add_action( 'wfmain_before_all_content', array ($this, 'wf_layout_build_content_sb1'), 2 );
				add_action( 'wfsidebar_before_all', array ($this, 'wf_layout_build_sb1'), 2 );
				add_action( 'wfmain_after_all_content', array ($this, 'wf_css_close'), 9 );
				add_action( 'wfsidebar_after_all', array ($this, 'wf_css_close'), 9 );
		} elseif ( $this->wfx_content_1_display == 'Y' && $this->wfx_sidebar_1_display == 'N' ) {
				add_action( 'wfmain_before_all_content', array ($this, 'wf_layout_build_content_no_sb1'), 2 );
				add_action( 'wfmain_after_all_content', array ($this, 'wf_css_close'), 9 );
		// Experimental - needs more work to remove content display, but this removes the CSS!
		} elseif ( $this->wfx_content_1_display == 'N' && $this->wfx_sidebar_1_display == 'Y' ) {
				add_action( 'wfsidebar_before_all', array ($this, 'wf_layout_build_sb1_no_content'), 2 );
				add_action( 'wfsidebar_after_all', array ($this, 'wf_css_close'), 9 );
		}
	}


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for sidebar1 WITH content1
	*/
	function wf_layout_build_sb1($args) { echo $this->wf_css('columns='.$this->wfx_sidebar_1_size_columns.'&size='.$this->wfx_sidebar_1_size.'&id='.$this->wfx_sidebar_1_id.'&last=y&divoutput=Y&class=sidebar-1-with-content-1'); }


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for sidebar1 WITHOUT content1
	*/
	function wf_layout_build_sb1_no_content($args) { echo $this->wf_css('id='.$this->wfx_sidebar_1_id.'&last=y&divoutput=Y&class=sidebar-1-no-content-1'); }


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for main content1 WITH sidebar1
	*/
	function wf_layout_build_content_sb1($args) { echo $this->wf_css('columns='.$this->wfx_content_1_size_columns.'&size='.$this->wfx_content_1_size.'&id='.$this->wfx_content_1_id.'&last=y&divoutput=Y&class=content-1-with-sidebar-1'); }


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for main content1 WITHOUT sidebar1
	*/
	function wf_layout_build_content_no_sb1($args) { echo $this->wf_css('id='.$this->wfx_content_1_id.'&last=y&divoutput=Y&class=content-1-no-sidebar-1'); }


}


/**
* @since 0.1
* @updated 0.93
* Core display functions that output visible items rendered to page
*/
class wflux_display extends wflux_display_css {


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up Sidebar content
	*/
	function wf_get_sidebar($args) {
		if ( $this->wfx_sidebar_1_display == 'Y' ) {
			get_sidebar($args);
		} else {
			// Silence is golden - more sidebars to come!
		}
	}


	/**
	*
	* @since 0.3
	* @updated 0.931
	*
	* Footer credit
	*
	*/
	function wf_credit($args) {

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
			echo apply_filters( 'wflux_footer_credit_div', $footer_credit_div );
			echo '" id="wf-footer-credit">';
		} else {
			wfx_css('size=full&id=footer-wfx-credit&divoutput=Y');
		}

		$footer_credit = '<' . $footer_credit_format . '>';

		//Filter to overide all of wflux_footer_credit_content - but keep inside CSS formatting
		$footer_credit .= apply_filters( 'wflux_footer_credit_content', $footer_credit_content );
		$footer_credit .= '</' . $footer_credit_format . '>';

		//Filter to overide all of wflux_footer_credit
		echo apply_filters( 'wflux_footer_credit', $footer_credit );
		echo "\n";

		//Now close the div we opened
		echo '</div>';

	}

}


/**
* @since 0.85
* @updated 1.0RC4
* Extra core display functions for theme designers
*/
class wflux_display_extras {

	private $clean_theme_name = null;

	/**
	 * Returns santisied version of current theme name, replacing spaces and other characters (apart from a-z A-Z and 0-9) with _
	 * Used in cache function (and maybe others in the future!)
	 * @return string
	 *
	 * @since 1.0RC4
	 * @updated 1.0RC4
	 * TODO: Check core WP version_compare() - guessing < evaluation on constant quicker/easier?
	 */
	private function get_clean_theme_name() {
		// Backpat - depreciated function get_current_theme() in WordPress 3.4
		if ( !$this->clean_theme_name )
			$this->clean_theme_name = preg_replace('/[^a-zA-Z0-9]/','_', ( WF_WORDPRESS_VERSION < 3.4 ) ? get_current_theme() : wp_get_theme()->Name );
			return $this->clean_theme_name;
	}


	/**
	 * Function for displaying the excerpt with just a certain number of words
	 * Can be used inside loop or custom wp_query
	 * @return sanitised text string
	 *
	 * ARGUMENTS
	 * $limit = Number of words. Default = '20'
	 * $excerpt_end = String of characters after the end of the excerpt. Default '...'
	 * $trim = Trim off punctuation from end of excerpt - good when you don't want it to bump into your excerpt end. Default 'Y'
	 *
	 * @since 0.85
	 * @updated 0.931
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
			$punctuation = array('.',',','-','&minus;','&ndash;','&mdash;');
			if (in_array($end_check,$punctuation)): $excerpt = substr($excerpt, 0, -1); endif;
		}

		return esc_attr($excerpt) . esc_attr($excerpt_end);

	}


	/**
	 * Displays a configurable user Twitter stream or Twitter @reply stream
	 *
	 * @param user - The Twitter username [Wonderflux]
	 * @param update - How often you want the Twitter stream cache to refresh in minutes [60]
	 * @param items - Number of Tweets you wish to show [5]
	 * @param show_name - Show the username at the start of each Tweet [N]
	 * @param active_urls - Turn links mentioned in Tweets into hyperlinks [Y]
	 * @param show_date - Show the date at after the Tweet [Y]
	 * @param date_format - If 'relative' displays 'recently' if less than 24 hours or shows complete days if over 24 hours [relative]
	 * @param smileys - If Tweet has typographic smiley faces in, replace with graphics [Y]
	 * @param content_style - Tweet (and date) CSS style [p]
	 * @param tweet_class - Tweet (and date) CSS class [twitter-stream]
	 * @param seperator - Seperator between Tweet and date (not shown if date is hidden) [ - ]
	 * @param date_class - CSS span class on date [twitter-stream-date]
	 * @param replies - If set to Y ONLY shows replies (@username tweets) sent by any user on Twitter to defined 'user' [N]
	 * @param avatar - EXPERIMENTAL ALPHA Currently only works for replies (not public stream) [N]
	 * @param avatar_div_class - CSS class used on avatar containing div to allow alightment to tweet (so it can be floated left/right in theme CSS) [twitter-stream-avatar]
	 * @param avatar_img_class - CSS class used on avatar image (so it can have extra CSS styling in theme CSS) [twitter-stream-avatar-img]
	 * @param avatar_size - Width and height in px of avatar - always shown as square image. Don't go bigger - this is the original size available! [48]
	 * @param tweet_div - Contain each tweet in it's own CSS div [Y]
	 * @param tweet_div_class - CSS class used on individual tweet div [twitter-stream-single]
	 * @param tweet_count_class - CSS class added to individual tweet div - appends '-INTEGER' automatically to allow more sophisticated CSS styling [tweet-num]
	 * @param fail - Message shown when there is no tweets available [Sorry, no tweets available.]
	 *
	 * TODO: Probably need to convert this to using Twitter API to get users avatar as not present in old school RSS feed and it's the right thing to do!
	 *
	 * @since 0.85
	 * @updated 1.0RC4
	 */
	function wf_twitter_feed($args) {

		$defaults = array (
			'user' => 'Wonderflux',
			'update' => '60',
			'items' => '5',
			'show_name' => 'N',
			'active_urls' => 'Y',
			'show_date' => 'Y',
			'date_format' => 'relative',
			'smileys' => 'Y',
			'content_style' => 'p',
			'tweet_class' => 'twitter-stream',
			'seperator' => ' - ',
			'date_class' => 'twitter-stream-date',
			'replies' => 'N',
			'avatar' => 'N',
			'avatar_div_class' => 'twitter-stream-avatar',
			'avatar_img_class' => 'twitter-stream-avatar-img',
			'avatar_size' => '48',
			'tweet_div' => 'Y',
			'tweet_div_class' => 'twitter-stream-single',
			'tweet_count_class' => 'tweet-num',
			'fail' => __('Sorry, no tweets available.', 'wonderflux')
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Prepare user input for output
		$user = wp_kses_data($user);
		if (!is_numeric($update)) { $update = 60; }
		if (!is_numeric($items) || ($items > 25) ) { $items = 5; }
		$content_style = wp_kses_data($content_style);
		$avatar_div_class = wp_kses_data($avatar_div_class);
		$avatar_img_class = wp_kses_data($avatar_div_class);
		if (!is_numeric($avatar_size)) { $avatar_size = 48; }
		$tweet_div_class = wp_kses_data($tweet_div_class);
		$tweet_count_class = wp_kses_data($tweet_count_class);
		$fail = wp_kses_data($fail);


		// Get WP feed functionality
		include_once(ABSPATH . WPINC . '/feed.php');

		add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$update', 'return ' . ($update*60) . ';' ) );

		// Fetch feed
		if ($replies == 'Y') {
			// Get @ replies query feed
			$rss = fetch_feed( esc_url('http://search.twitter.com/search.rss?q=@'.$user) );
		} else {
			// Get normal user timeline feed
			$rss = fetch_feed( esc_url('http://twitter.com/statuses/user_timeline/'.$user.'.rss') );
		}

		// Catch for if the Twitter Fail Whale is around (or something else went wrong)
		// NOTE: Extra 'fail' css class added
		if ( is_wp_error($rss) ) {

			// Oops, we don't have any data
			echo '<' . esc_attr($content_style) . ' class="' . esc_attr($tweet_class) . ' fail">' . esc_attr($fail) . '</' . esc_attr($content_style) . '>';

		} else {

		// How many?
		$rss_items = $rss->get_items( 0, $rss->get_item_quantity($items) );

		$div_count = 0;

		foreach ( $rss_items as $item ) {

			if ($tweet_div == 'Y') {
				// Yup, start at 1 because only devs start at 0!
				$div_count++;
				echo '<div class="' . esc_attr($tweet_div_class) . ' ' . $tweet_count_class . '-' . $div_count . '">';
			}

			$this_update = $item->get_title();

			// AVATAR ALPHA CODE
			// TODO: Build this into normal tweet stream
			// Normal stream doesn't have avatars in (great) so probably need to convert all of this to Twitter API to get at avatar?
			if ($avatar == 'Y' && $replies == 'Y') {

				// Get avatar URL
				$enclosures = $item->get_enclosures('');
				foreach ($enclosures as $enc) {
					$avatar_img = $enc->link;
				}

				// Get original author username
				$authors = $item->get_authors();
				foreach ($authors as $author) {
					$author_detail = $author->email;
					$start = strpos($author_detail,"(") ;
					$end = strpos($author_detail,")") ;
					$author_detail = substr($author_detail,$start+1,$end-$start-1) ; // without brackets
				}

				// Show Avatar
				echo '<div class="'.esc_attr($avatar_div_class).'">';
				echo '<img src="'.esc_url($avatar_img).'" alt="'.esc_attr__('Tweet from ', 'wonderflux').esc_attr($author_detail).esc_attr__(' Twitter user', 'wonderflux').'" title="'.esc_attr__('Tweet from @', 'wonderflux').esc_attr($author_detail).esc_attr__(' Twitter user', 'wonderflux').'" width="'.$avatar_size.'" height="'.$avatar_size.'" class="'.esc_attr($avatar_img_class).'" />';
				echo '</div>';
			}

			if ($show_name == 'N') {
				// Remove username from start
				$this_update = preg_replace('/'.$user.':/', '', $this_update, 1);
			}

			if ($active_urls == 'Y') {
				// Parse and setup link URLs
				$this_update = preg_replace( "/(([[:alnum:]]+:\/\/)|www\.)([^[:space:]]*)"."([[:alnum:]#?\/&=])/i", "<a href=\"\\1\\3\\4\">"."\\1\\3\\4</a>", $this_update);
			}

			// OUTPUT START OF TWEET
			echo '<' . esc_attr($content_style) . ' class="' . esc_attr($tweet_class) . '">';

			// OUTPUT TWEET
			if ($smileys == 'Y') {
				echo convert_smilies(ltrim($this_update));
			} else {
				echo ltrim($this_update);
			}

			if ($show_date == 'Y') {

				if ($date_format == 'relative') {

					//CURRENT TIME IN UNIX TIMESTAMP
					$current_time = time();
					//echo $current_time;

					// Get date how it comes through
					$rawdate = $item->get_date('');

					// Format to unix timestamp
					$timestampdate = SimplePie_Misc::parse_date($rawdate);
					// find difference
					$timediff = $current_time - $timestampdate;
					// Round to days
					$fulldays = floor($timediff/(60*60*24));

					if ($fulldays == '0') {
						echo esc_attr($seperator) . '<span class="' . esc_attr($date_class) . '">' . esc_attr__('Recently', 'wonderflux') . '</span>';
					} else {
						// Sort out formatting
						if ($fulldays == '1') {
						// Just one day, so its day not days
							$dayappend = '';
						} else {
							$dayappend = 's';
						}
						//TODO: Internationalisation for day/days (single/plural)
						echo esc_attr($seperator) . '<span class="' . esc_attr($date_class) . '">' . $fulldays . ' ' . esc_attr__('day', 'wonderflux') . $dayappend . ' ' . esc_attr__('ago', 'wonderflux') . '</span>';
					}

					} else {
						echo esc_attr($seperator) . '<span class="' . esc_attr($date_class) . '">' . $item->get_date('') . '</span>';
					}
				}

				//Close off tweet
				echo '</' . esc_attr($content_style) . '>';

				if ($tweet_div == 'Y') {
					echo '</div>';
				}

			}

		}// End foreach

	}


	/**
	 * Displays an image that leads to the individual post/page/content
	 * Can be used inside loop or custom wp_query
	 *
	 * @param intro - Text string used in image title and description [Read about]
	 * @param showtitle - Do you want to show the title of the content in image title and description? If set to 'N' also doesn't display seperator (even if set) [Y]
	 * @param seperator - Text string that seperates 'intro' and title of content [ - ]
	 * @param imgclass - CSS class used on button image [button-more]
	 * @param imgpath - Path in child theme dir to where image is, DONT need full path - already puts you inside your child theme dir! [images]
	 * @param imgname - Filename of image to be used [button-read-more.png]
	 * @param imgwidth - Width of image in pixels [150]
	 * @param imgheight - Height of image in pixels [30]
	 * @output HTML formatted content
	 *
	 * @since 0.85
	 * @updated 1.0RC4
	 */
	function wf_perma_img($args) {

		$defaults = array (
			'intro' =>  __('Read about', 'wonderflux'),
			'title' => 'Y',
			'seperator' => ' - ',
			'class' => 'button-more',
			'path' => 'images',
			'img' => 'button-read-more.png',
			'width' => 150,
			'height' => 30
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Tidy up ready for use
		$intro = wp_kses_data($intro, '');
		if ( $title == 'Y' ) { $intro .= wp_kses_data($seperator, '') . get_the_title(); }
		$class = wp_kses_data($class, '');
		$path = wp_kses_data($path, '');
		$img = wp_kses_data($img, '');
		if (!is_numeric($width)) { $width = 150; } // Checking if a number is light weight
		if (!is_numeric($height)) { $height = 30; } // Checking if a number is light weight

		$output = '<a href="' . get_permalink() . '" title="' . esc_attr($intro) . '">';
		$output .= '<img class="';
		$output .= esc_attr($class);
		$output .= '" src="';
		$output .= WF_THEME_URL;
		$output .= '/' . esc_attr($path) . '/';
		$output .= esc_attr($img);
		$output .= '" alt="';
		$output .= esc_attr($intro);
		$output .= '" width="';
		$output .= $width;
		$output .= '" height="';
		$output .= $height;
		$output .= '"/></a>';

		echo $output;

	}


	/**
	 * Inserts (Mostly) WordPress admin editor links
	 *
	 * TODO: Convert this to core WF core widget
	 * TODO: Extend further to accomodate display functionality when a user is NOT logged in (like WordPress Meta widget stylee!)
	 * TODO: Extend for user profiles, editing taxonomies etc
	 *
	 * @param userintro - Text string in first list item [Welcome]
	 * @param username - Display username after intro (Within same list item) [Y]
	 * @param intro - Display intro [Y]
	 * @param postcontrols - Show post controls [Y]
	 * @param pagecontrols - Show page controls [Y]
	 * @param adminlink - Show admin link [Y]
	 * @param widgetslink - Show edit widgets link [Y]
	 * @param wfcontrols - Show Wonderflux admin link [N]
	 * @param logoutlink - Show WordPress logout link [N]
	 * @param ulclass - Containing ul CSS class [wf-edit-meta-main]
	 * @param liclass - Individual li CSS class [wf-edit-meta-links]
	 * @param div - Insert div around output [N]
	 * @param divclass - CSS class of div [wf-edit-meta-box]
	 * @param wpadminbar - Show WordPress 3.1 admin bar [N]
	 * @output <ul><li>list items of various admin links inside optional CSS DIV
	 *
	 * @since 0.85
	 * @updated 0.931
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
			'divclass' => 'wf-edit-meta-box',
			'wpadminbar' => 'Y'
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

			// Configure WordPress 3.1+ admin bar
			if ( $wpadminbar == 'N' ) {
				global $wfx_wp_helper; $wfx_wp_helper->admin_bar_remove('');
			} else {
				// Still include the WordPress admin bar... how many admin menus do you need you crazy fool?!
			}

		endif;

	}


	/**
	 * Gets a single wordpress post/page and displays the content
	 * TODO: Convert this to core WF core widget
	 *
	 * @param id - ID of the content you want [2]
	 * @param titlestyle - Title CSS style [h4]
	 * @param contentstyle - Content CSS style [p]
	 * @param title - Display title [Y]
	 * @param titlelink - Link title to page [N]
	 * @param exerptlimit - Limit number of words in content [25]
	 * @param exerptend - Optional characters at the end of the content [...]
	 * @param morelink - Display read more link [N]
	 * @param morelinktext - Text used for read more link [Read]
	 * @param morelinkclass - CSS class of more link [wfx-get-page-loop-more]
	 * @param boxclass - Main containing div CSS class [wfx-get-page-loop]
	 * @param contentclass - Content containing div CSS class [wfx-get-page-loop-content]
	 * @output HTML formatted content
	 *
	 * @since 0.85
	 * @updated 0.93
	 */
	function wf_get_single_content($args) {

		$defaults = array (
			'id' => 2,
			'titlestyle' => 'h4',
			'contentstyle' => 'p',
			'title' => 'Y',
			'titlelink' => 'N',
			'exerptlimit' => '25',
			'exerptend' => '...',
			'morelink' => 'N',
			'morelinktext' => __('Read', 'wonderflux'),
			'morelinkclass' => 'wfx-get-page-loop-more',
			'boxclass' => 'wfx-get-page-loop',
			'contentclass' => 'wfx-get-page-loop-content'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Prepare user input for output
		$titlestyle = wp_kses_data($titlestyle);
		$boxclass = wp_kses_data($boxclass);
		$contentstyle = wp_kses_data($contentstyle);
		$contentclass = wp_kses_data($contentclass);
		$exerptend = wp_kses_data($exerptend);
		$morelinktext = wp_kses_data($morelinktext);
		$morelinkclass = wp_kses_data($morelinkclass);
		if (!is_numeric($id)) { $id = 2; }

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
				$title_out .= '<' . esc_attr($titlestyle) . '>';
				if ($titlelink == 'Y') { $title_out .= '<a href="' . get_permalink() . '" title="'. get_the_title() .'">'; }
				$title_out .= get_the_title();
				if ($titlelink == 'Y') { $title_out .= '</a>'; }
				$title_out .= '</' . esc_attr($titlestyle) . '>';
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
	 * Creates a standalone link (unstyled) that does login/logout with redirect on each
	 * TODO: Hook up redirect parameters!
	 * TODO: Allow role redirection
	 * TODO: Allow containing XHTML element
	 *
	 * @param login - The Login text displayed [Login]
	 * @param logintip - The Login tooltip [Login to site]
	 * @param logout - The Logout text displayed [Logout]
	 * @param logouttip - The Logout tooltip [Logout of site]
	 * @param loginredirect - Redirect to where on login [dashboard]
	 * @param logoutredirect - Redirect to where on logout [current]
	 * @param wpadminbar - Configure display of WordPress admin bar [N]
	 *
	 * @since 0.901
	 * @updated 0.931
	 */
	function wf_login_logout($args) {

		$defaults = array (
			'login' => __('Login', 'wonderflux'),
			'logintip' => __('Login to site', 'wonderflux'),
			'logout' => __('Logout', 'wonderflux'),
			'logouttip' => __('Logout of site', 'wonderflux'),
			'loginredirect' => 'dashboard',
			'logoutredirect' => 'current',
			'wpadminbar' => 'Y'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( is_user_logged_in() ) {
			echo '<a href="'.wp_logout_url( home_url() ).'" title="'.esc_attr($logouttip).'">'.esc_attr($logout).'</a>';
		} else {
			echo '<a href="'.wp_login_url().'" title="'.esc_attr($logintip).'">'.esc_attr($login).'</a>';
		}

		// Configure WordPress 3.1+ admin bar
		if ( $wpadminbar == 'N' ) {
			global $wfx_wp_helper; $wfx_wp_helper->admin_bar_remove('');
		} else {
			// Still include the WordPress admin bar... how many admin menus do you need you crazy fool?!
		}

	}


	/**
	 * If you really need to hard-code your site page or category navigation,
	 * this function adds 'current_page_item' CSS class when page/category is viewed
	 *
	 * You really should use wp_list_pages() and wp_list_categories() wherever possible
	 * Check the WordPress Codex pages on these
	 *
	 * TODO: Tidy up!
	 *
	 * @param pageid - Page ID [NONE]
	 * @param catid - Category ID [NONE]
	 *
	 * @since 0.901
	 * @updated 0.913
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
	 * Gets attachment(s) or featured images of posts in various formats
	 * TODO: Soon to be 'swiss army knife' of attachment getters!
	 *
	 * @since 0.901
	 * @updated 1.0
	 */
	function wf_get_attachments($args) {

		$defaults = array (
			'type'		=> 'image',
			'amount'	=> 1,
			'order'		=> 'ASC',
			'output'	=> 'file_url',
			'id'		=> 0,
			'name'		=> 'thumbnail'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$type = ( $type == 'attachment' ) ? $type : wp_kses_data($type);
		$amount = ( is_numeric($amount) ) ? $amount : 1;
		$order = ( $order == 'ASC' ) ? $order : wp_kses_data($order);
		$output = ( $output == 'file_url' ) ? $output : ( in_array($output, array('file_url','parent_url','page_url')) ) ? $output : 'file_url';
		$id = ( is_numeric($id) ) ? $id : 0;
		$name = ( $name == 'thumbnail' ) ? $name : wp_kses_data($name);

		$out = false;
		global $post;

		if ( $type == 'featured' ):
			// Requested ID/this post
			$this_img = ( $id > 0 ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $name) : wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $name);
			$out = ( is_array($this_img) ) ? $this_img[0] : false;
		else:

			$files = get_children(array(
				'post_parent'	=> $post->ID,
				'post_type'		=> 'attachment',
				'order'			=> $order,
				'post_mime_type'=> $type,
				'numberposts'	=> $amount,
				'post_status'	=> 'inherit'
			));

			foreach( $files as $file ) {
				switch ( $output ) {
					case 'file_url' : $out = wp_get_attachment_url($file->ID); break;
					case 'parent_url' : $out = get_permalink($file->post_parent); break;
					case 'page_url' : $out = get_attachment_link($file->ID); break;
					default : $out = wp_get_attachment_url($file->ID); break;
				}
			}

		endif;
		return $out;

	}


	/**
	 * Creates 'page x of x' output for lists of results like category view and others
	 * TODO: Add in wp_link_pages type functionality so it can function with paged single pages, not just query lists
	 *
	 * @param element - The containing overall XHTML element, if blank, no containing element (setup your own in your theme) [p]
	 * @param start - The opening text string [Page ]
	 * @param seperator - The string that seperates the two numbers [ of ]
	 * @param current_span - CSS span class around current page number (set to blank to remove span) [page_num_current]
	 * @param total_span - CSS span class around total page number (set to blank to remove span) [page_num_total]
	 * @param always_show - No output is shown if there is only 1 page of results, setting this to 'Y' will make the counter always show (ie page 1 of 1) [N]
	 * @param navigation - Display next and previous navigation either side of the page display [N]
	 * @param navigation_span - CSS span class around current page number (set to blank to remove span) [page_num_nav]
	 * @param previous - The string that represents the previous link (and space) [&lt; ]
	 * @param next - The string that represents the next link (and space) [ &gt;]
	 * @param container - Puts the output inside a div [Y]
	 * @param container_class - Container CSS class [page-counter-navigation]
	 *
	 * @since 0.93
	 * @updated 0.931
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
			'navigation_span' => 'page-counter-navigation',
			'previous' => '&lt; ',
			'next' => ' &gt;',
			'container' => 'Y',
			'container_class' => 'page-counter'
		);

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
		$navigation_span = ($total_span == 'page_num_nav') ? $navigation_span : wp_kses_data($navigation_span, '');
		$previous = ($previous == '&lt; ') ? $previous : wp_kses_data($previous, '');
		$next = ($next == ' &gt;') ? $next : wp_kses_data($next, '');
		// If someone has removed the span CSS classes definition, dont render to screen
		$current_span = (!$current_span == '') ? '<span class="'.$current_span.'">' : '';
		$current_span_close = (!$current_span == '') ? '</span>' : '';
		$navigation_span = ($navigation_span == '') ? '<span class="'.$navigation_span.'">' : '';
		$navigation_span = (!$navigation_span == '') ? '</span>' : '';
		$navigation_span_close = (!$navigation_span == '') ? '</span>' : '';
		$total_span = (!$total_span == '') ? '<span class="'.$total_span.'">' : '';
		$total_span_close = (!$current_span == '') ? '</span>' : '';
		$container = ($container == 'Y') ? 'Y' : 'N';
		$container_class = ($container_class == 'page-counter-navigation') ? $container_class : wp_kses_data($container_class, '');


		// get total number of pages
		global $wp_query;
		$total = $wp_query->max_num_pages;

		// Setup current page
		$current = 1;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

		$output = ($container == 'Y') ? '<div class="' . $container_class . '">' : '';
		$output .= ($element == '') ? '' : '<'.$element.'>';
		$output .= ($navigation == 'N') ? '' : $navigation_span . get_previous_posts_link('&lt; ',0) . $navigation_span_close;
		$output .= esc_html( $start );
		$output .= $current_span . $current.$current_span_close;
		$output .= esc_html( $seperator );
		$output .= $total_span . $total.$total_span_close;
		$output .= ($navigation == 'N') ? '' : get_next_posts_link(' &gt;',0);
		$output .= ($element == '') ? '' : '</'. $element .'>';
		$output .= ($container == 'Y') ? '</div>' : '';

		// Always show results, even if just one page
		if ( $always_show == 'Y' ) {
			return $output;
		// only render if we have more than one page of results
		} elseif ( $total > 1 ) {
			return $output;
		}
	}


	/**
	 * Include a theme template file and cache generated output for desired time
	 * Works with most things, but may not work with more advanced functions/plugins that inject CSS or JS into header/footer or do other funky stuff!
	 *
	 * Transient option saved as Child_Theme_Name_c_$part
	 *
	 * If you wish to flush the cached files, append your site url as follows using either of these examples (for logged-in users who can edit theme options):
	 * Example 1 - flush all files www.mydomain.com/?flushcache_all=1
	 * Example 2 - flush individual cached element www.mydomain.com/?flushcache_NAME_OF_INCLUDE=1
	 *
	 * WARNING: This is kinda experimental and might change - dont use in production just yet!
	 *
	 * @param part - (string) REQUIRED - Name of the file in active theme directory you want to include and cache, without file extension.
	 * @param file_ext - (string) DEFAULT: 'php' - File extension of the file you want to cache (WITHOUT the '.'!)
	 * @param expire - (integer) DEFAULT: 360 - Length of time (in minutes) that the cache persists.
	 * @param sanitise_in - ('html'/'none') DEFAULT: 'html' - Sanitises before saving to cache
	 * @param sanitise_out - ('html'/'none') DEFAULT: 'none' - Optional sanitisation before outputting to screen
	 * @param mimify - (Y/N) DEFAULT: 'Y' - Remove whitespace before saving as transient
	 * @param transient_key - (string) OPTIONAL Set different transient option name (NOTE: will be trimmed to 32 characters max)
	 * @param flushable - (Y/N) DEFAULT: 'Y' - Can the cached item be force flushed/refreshed via url, user must have edit_theme_options capability
	 * @param output_start - DEFAULT: '<!--cached-part-start-->' - Added to start of output (not saved in transient, runs through output sanitisation if set)
	 * @param output_end - DEFAULT: '<!--cached-part-end-->' - Added to end of output (not saved in transient, runs through output sanitisation if set)
	 *
	 * @filter wflux_allowed_cached_tags - array of allowed output tags used with kses
	 *
	 * @since 1.0RC4
	 * @lastupdate 1.0RC4
	 *
	 * TODO: Extend $sanitise_in and sanitise_out with more options
	 * TODO: Should this be made location aware?
	 * NOTES: Deeper transient key length - only 64 chars allowed in options table name? Use 45 characters or 32 max?? - Watch _transient_timeout_{$transient_key}
	 */
	function wf_get_cached_part($args) {

		$defaults = array (
			'part' => '',
			'file_ext' => 'php',
			'expire' => 360,
			'sanitise_in' => 'html',
			'sanitise_out' => '',
			'mimify' => 'Y',
			'transient_key' => '',
			'flushable' => 'Y',
			'output_start' => '<!--' . __('cached-part-start', 'wonderflux') . '-->',
			'output_end' => '<!--' . __('cached-part-end', 'wonderflux') . '-->'
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( !$part ) return;

		// Setup WFX Data management class
		global $wfx_data_manage;

		$cached_data = false;
		$expire = ( (is_numeric($expire)) ? $expire : 1 )*60; // Set to minutes
		$sanitise_in = ( $sanitise_in == 'html' ) ? 'html' : 'none';
		$sanitise_out = ( $sanitise_out == 'html' ) ? 'html' : 'none';

		// TODO: Transient key reported upto 32 characters max - have tested upto 45, 46 was stormy waters!
		// Best to play on safe side until further teting done (32)
		$transient_key = mb_substr( empty($transient_key) ? $this->get_clean_theme_name() . '_c_' . $part : $transient_key , 0, 32);

		// Cache flush control/load data
		$flush_this = false;
		if( $flushable == 'Y' ) {
			if ( current_user_can('edit_theme_options') )
				$flush_this = ( isset($_GET['flushcache_all']) && $_GET['flushcache_all'] == 1 ) ? true : false;
				$flush_this = ( isset($_GET['flushcache_'.$part.'']) && $_GET['flushcache_'.$part.''] == 1 ) ? true : $flush_this;
		}
		$cached_data = ( !$flush_this ) ? get_transient($transient_key) : false;
		$allowed_tags = ( $sanitise_in == 'html' || $sanitise_out == 'html' ) ? $wfx_data_manage->allowed_tags('') : '';

		// Refresh cache
		if( empty( $cached_data ) ){
			ob_start();
				locate_template( $part.'.'.$file_ext, true, false );
				switch ($sanitise_in){
					case 'html': $cached_data = wp_kses( ob_get_contents(), apply_filters( 'wflux_allowed_cached_tags', $allowed_tags ) );
					default: $cached_data = ob_get_contents();
				}
			ob_end_clean();
			set_transient( $transient_key, ($mimify == 'Y') ? $wfx_data_manage->strip_whitespace($cached_data) : $cached_data, $expire );
		}

		// Output
		if ( !empty( $cached_data ) ){
			switch ( $sanitise_out ){
				case 'html':
					return "\n" . wp_kses( $output_start . $cached_data . $output_end, apply_filters( 'wflux_allowed_cached_tags', $allowed_tags ) ) . "\n";
					break;
				default:
					return "\n" . $output_start . $cached_data . $output_end . "\n";
				break;
			}
		} else {
			return false;
		};

	}


}


/**
* @since 0.931
* @updated 0.931
* Social networking functionality
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
		$this->og_image = apply_filters( 'wfx_social_og_meta_image', WF_THEME_URL.'/images/supporting/social_thumbnail.jpg' );
	}


	/**
	 * Inserts associated social related meta tags in <head> if required.
	 *
	 * @since 0.931
	 * @updated 0.931
	 */
	function wf_social_meta() {
		if ( $this->wfx_doc_type == 'XHTML/RDFa' ):
			add_action( 'wp_head', array( $this, 'wf_og_meta' ), 4 );
		endif;
	}


	/**
	 * Builds Open Graph Meta data (used by Facebook and others)
	 *
	 * @since 0.931
	 * @updated 0.931
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
	 * Displays a Facebook like button.
	 * @param size - Size of share button. 'small', 'tall' [small]
	 * @param send - Show additional send button. 'true', 'false' [false]
	 * @param url - URL to like/share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'.
	 *
	 * @since 0.931
	 * @updated 0.931
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
	 * Displays a Google Plus 1 button with fully valid XHTML rendering.
	 * Supports multiple inserts and asynchronously loads so that it does not block your webpage rendering.
	 * @param size - Size of plus 1 button. 'small', 'medium', 'standard', 'tall' [medium]
	 * @param count - Show count or not. NOTE: Tall size always shows count. 'no_count', 'show_count' [show_count]
	 * @param url - URL to plus 1 - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'.
	 * @filter wfx_social_gplus_target - Sets the target name that's added to the start of the unique ID. Used in div ID and Javascript output. [social-link-gplus]
	 *
	 * @since 0.931
	 * @updated 0.931
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

		$target = apply_filters( 'wfx_social_gplus_target', 'social-link-gplus' );

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
	 * Displays a Twitter button with counter.
	 * @param size - Size of share button. 'small', 'tall' [small]
	 * @param count - Show count or not. NOTE: Defaults to small - no count only available on small. 'no_count' [show_count]
	 * @param url - URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'.
	 *
	 * @since 0.931
	 * @updated 0.931
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
	 * Displays a LinkedIn button with counter.
	 * @param size - Size of share button. 'small', 'tall' [small]
	 * @param count - Show count or not. NOTE: Defaults to small 'no_count' [show_count]
	 * @param url - URL to share - defaults to current page URL if no value supplied. Value 'home' sets url to website homepage. Supply full url for alternative eg 'http://mysite.com/cool/'.
	 *
	 * @since 1.0rc2
	 * @updated 1.0rc2
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