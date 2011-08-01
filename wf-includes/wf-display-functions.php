<?php
//TODO: Setup for translation
/**
* @since 0.913
* @updated 0.931
* Core display functions that output code
*/
class wflux_display_code extends wflux_data {

	/**
	* Builds the start of the head with doc type declaration
	*
	* @since 0.931
	* @updated 0.931
	*
	* @param $doctype (limited variable string) : Document type : 'transitional' (default), 'strict', 'frameset', '1.1', '1.1basic', 'html5'
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
		$lang_length = strlen( trim($lang) );
		if ($lang_length < 6) {
			$lang_output = strtolower(wp_kses($lang, ''));
		} else {
			//Invalid - set sensible default
			$lang_output = 'en';
		}

		//TODO: SET FILTERS HERE READY FOR CLEANSING USING FUNCTIONS BELOW

		// Document type
		switch ($doctype) {
			case 'transitional':
				$doctype_output = '1.0 Transitional';
				$doctype_link_output = '1/DTD/xhtml1-transitional';
			break;

			case 'strict':
				$doctype_output = '1.0 Strict';
				$doctype_link_output = '1/DTD/xhtml1-strict';
			break;

			case 'frameset':
				$doctype_output = '1.0 Frameset';
				$doctype_link_output = '1/DTD/xhtml1-frameset';
			break;

			case '1.1':
				$doctype_output = '1.1';
				$doctype_link_output = '11/DTD/xhtml11';
			break;

			case '1.1basic':
				$doctype_output = 'Basic 1.1';
				$doctype_link_output = '-basic/xhtml-basic11';
			break;

			//Invalid user input - set a sensible default
			default:
				$doctype_output = '1.0 Transitional';
				$doctype_link_output = '1/DTD/xhtml1-tansitional';
			break;
		}

		// Character set
		$charset_output = wp_kses($charset, '');

		// If its HTML 5 it's simple output
		if ($doctype == 'html5') {

			$output = '<!DOCTYPE html>' . "\n";
			$output .= '<html lang="'.$lang_output.'">' . "\n";

		} else {

			// Content type
			$content_length = strlen($content);
			if ($content_length <= 4) {
				$content_output = strtolower(wp_kses($content, ''));
			} else {
				//Invalid input - set a sensible default
				$content_output = 'html';
			}

			// Setup default strings not used in HTML 5 spec
			$common_1 = 'html PUBLIC "-//W3C//DTD XHTML ';
			$common_2 = '" "http://www.w3.org/TR/xhtml';
			$output = '<!DOCTYPE ' . $common_1 . $doctype_output . '//' . strtoupper($lang_output) . $common_2 . $doctype_link_output . '.dtd">';
			$output .="\n";
			$output .='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang_output.'" lang="'.$lang_output.'">';
			$output .="\n";
		}

		echo $output;
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
		if ($doctype == 'html5') {
			$output = "\n".'<meta charset="'.$charset_output.'" />' . "\n";
		} else {
			// Content type
			$content_length = strlen($content);
			if ($content_length <= 4) {
				$content_output = strtolower(wp_kses($content, ''));
			} else {
				//Invalid input - set a sensible default
				$content_output = 'html';
			}
			$output ="\n";
			$output .= '<meta http-equiv="Content-Type" content="text/'.$content_output.'; charset='.$charset_output.'" />';
			$output .="\n";
		}

		echo $output;
	}


	/**
	* Builds the title in the head of the template
	*
	* @since 0.1
	* @updated 0.881
	*/
	function wf_head_title($args) {

		if (is_home() || is_front_page()) {
			echo '<title>';
			bloginfo('name');

			$this_desc = esc_attr( get_bloginfo( 'description', 'display' ) );

			if ($this_desc == 'Just another WordPress site') {
				//Silence is golden - site has default description which we dont want to show
			} else {
				//They have set a site description in options
				echo ' - ';
				echo esc_attr( get_bloginfo( 'description', 'display' ) );
			}
			echo '</title>';
		}

		// If it's a feed, lets add that into the title
		elseif ( is_feed() ) {
			echo '<title>' . get_bloginfo( 'name', 'display' ) . ' feed</title>';
		}

		elseif ( is_search() ) {
			echo '<title>Search results for ' . get_search_query() . ' from ' . get_bloginfo( 'name', 'display' ) . '</title>';
		}

		//DEFAULT FALLBACK
		else {
			echo '<title>';
			wp_title(' - ',true,'right');
			bloginfo('name');
			echo '</title>';
		}

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
	* @updated 0.93
	*/
	function wf_head_css_theme() {
		$path = WF_THEME.'/style.css';
		$version = $this->wfx_mytheme_version;
		$id = 'main-theme';
		$media = 'screen, projection';

		// Allow filtering
		$path = apply_filters( 'wflux_css_theme_path', $path );
		$id = apply_filters( 'wflux_css_theme_id', $id );
		$media = apply_filters( 'wflux_css_theme_media', $media );

		wp_register_style( $id, $path,'', $version, $media );
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
		$path = WF_THEME.'/style-framework.css';
		$path_ie = WF_THEME.'/style-framework-ie.css';
		$version = $this->wfx_mytheme_version;
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
	* @since 0.3
	* @updated 0.881
	*
	* Footer credit
	* TODO: Switch for normal display or code comment
	*
	*/
	function wf_performance($args) {
		echo '<!-- ';
		$debug_text = get_num_queries();
		echo $debug_text.' queries in ';
		$debug_text = timer_stop(1);
		echo ' seconds';
		echo ' -->'."\n";
	}


	/**
	*
	* @since 0.71
	* @updated 0.913
	*
	* Footer code comment credit
	*
	*/
	function wf_code_credit($args) {

		// Default
		$footer_credit = 'Powered by WordPress and the Wonderflux theme framework';
		// Allow filtering
		$footer_credit_output = apply_filters( 'wflux_comment_code_credit', $footer_credit );

		$output = '<!-- '.$footer_credit_output.' -->'."\n";
		echo $output;
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

}


/**
* @since 0.913
* @updated 0.93
* Core display functions that output CSS
*/
class wflux_display_css extends wflux_display_code {

	/**
	*
	* @since 0.2
	* @updated 0.93
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
		echo $divout_open_clean . 'class="' . $css_core_def . $size_def . $move_def . $last . $class_clean . '"' . $id_clean . $divout_close_clean;

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
	* @updated 0.913
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

		$css_info =$start_format . 'WONDERFLUX LAYOUT DESIGN HELPER' . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . 'Full width: ' . $wf_fullwidth . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . 'Columns: ' . $wf_columns . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . 'Single column: ' . $wf_single . ' pixels wide' . $end_format;
		$css_info .="\n";
		$css_info .=$start_format . 'Half: ' . $wf_half . $end_format;
		$css_info .="\n";

		//TODO: More tests for fifths, sixths, sevenths and eighths?!?

		// Test to see if we can use thirds
		if ( !is_float(($wf_columns/3)) ) {

			$css_info .=$start_format . 'Third: ' . $wf_third . $end_format;
			$css_info .="\n";
			$css_info .=$start_format . 'Two Third: ' . $wf_two_third . $end_format;
			$css_info .="\n";

		} else {

			$css_info .=$start_format . 'Your column configuration is not compatible with thirds. Avoid using these in your template designs, or reconfigure options.' . $end_format;
			$css_info .="\n";

		}

		// Test to see if we can use quarters
		if ( !is_float(($wf_columns/4)) ) {

			$css_info .=$start_format . 'Quarter: ' . $wf_quarter . $end_format;
			$css_info .="\n";
			$css_info .=$start_format . 'Two Quarters: ' . $wf_half . $end_format;
			$css_info .="\n";
			$css_info .=$start_format . 'Three Quarters: ' . 'span-'.(($wf_columns/4)*3) . $end_format;
			$css_info .="\n";

		} else {

			$css_info .=$start_format . 'Your column configuration is not compatible with quarters. Avoid using these in your template designs, or reconfigure options.' . $end_format;
			$css_info .="\n";

		}


		$css_info .=$start_format . 'Small: ' . $wf_small . $end_format;
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
	function wf_layout_build_sb1($args) { $this->wf_css('columns='.$this->wfx_sidebar_1_size_columns.'&size='.$this->wfx_sidebar_1_size.'&id='.$this->wfx_sidebar_1_id.'&last=y&divoutput=Y&class=sidebar-1-with-content-1'); }


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for sidebar1 WITHOUT content1
	*/
	function wf_layout_build_sb1_no_content($args) { $this->wf_css('id='.$this->wfx_sidebar_1_id.'&last=y&divoutput=Y&class=sidebar-1-no-content-1'); }



	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for main content1 WITH sidebar1
	*/
	function wf_layout_build_content_sb1($args) { $this->wf_css('columns='.$this->wfx_content_1_size_columns.'&size='.$this->wfx_content_1_size.'&id='.$this->wfx_content_1_id.'&last=y&divoutput=Y&class=content-1-with-sidebar-1'); }


	/**
	* @since 0.93
	* @updated 0.93
	* Sets up required layout divs for main content1 WITHOUT sidebar1
	*/
	function wf_layout_build_content_no_sb1($args) { $this->wf_css('id='.$this->wfx_content_1_id.'&last=y&divoutput=Y&class=content-1-no-sidebar-1'); }

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
	* @updated 0.913
	*
	* Footer credit
	*
	*/
	function wf_credit($args) {

		// Defaults
		$footer_credit_format = 'p';
		$footer_credit_wp = 'Powered by <a href="http://wordpress.org" title="WordPress">WordPress</a>';
		$footer_credit_divider = ' | ';
		$footer_credit_wf = 'Built with <a href="http://wonderflux.com" title="Wonderflux WordPress theme framework">Wonderflux Framework</a>';
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
* @updated 0.92
* Extra core display functions that are for theme designers
*/
class wflux_display_extras {


	/**
	 * Function for displaying the excerpt with just a certain number of words
	 * Can be used inside loop or custom wp_query
	 * @return sanitised text string
	 *
	 * ARGUMENTS
	 * $limit = Number of words. Default = '20'
	 * $excerpt_end = String of characters after the end of the excerpt. Default '...'
	 *
	 * @since 0.85
	 * @updated 0.92
	 */
	function wf_excerpt($args) {

		$defaults = array (
			'limit' => 20,
			'excerpt_end' => '...'
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
	 * @updated 0.92
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
			'fail' => 'Sorry, no tweets available.'
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
				//echo .esc_url($avatar_img).
				echo '<img src="'.esc_url($avatar_img).'" alt="Tweet from '.esc_attr($author_detail).' Twitter user" title="Tweet from @'.esc_attr($author_detail).' Twitter user" width="'.$avatar_size.'" height="'.$avatar_size.'" class="'.esc_attr($avatar_img_class).'" />';
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
						echo esc_attr($seperator) . '<span class="' . esc_attr($date_class) . '">' . esc_attr__('Recently', 'Wonderflux') . '</span>';
					} else {
						// Sort out formatting
						if ($fulldays == '1') {
						// Just one day, so its day not days
							$dayappend = '';
						} else {
							$dayappend = 's';
						}
						//TODO: Internationalisation for day/days (single/plural)
						echo esc_attr($seperator) . '<span class="' . esc_attr($date_class) . '">' . $fulldays . ' ' . esc_attr__('day', 'Wonderflux') . $dayappend . ' ' . esc_attr__('ago', 'Wonderflux') . '</span>';
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
	 * @updated 0.913
	 */
	function wf_perma_img($args) {

		$defaults = array (
			'intro' => 'Read about',
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

		$output = '<a href="' . get_permalink() . '" title="' . $intro . '">';
		$output .= '<img class="';
		$output .= esc_attr($class);
		$output .= '" src="';
		$output .= dirname( get_bloginfo('stylesheet_url') );
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
	 * If user logged in, inserts relevant editor links into template
	 * TODO: Convert this to core WF core widget
	 * TODO: Extend further to accomodate display functionality when a user is NOT logged in (like WordPress Meta widget stylee!)
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
	 * @updated 0.92
	 */
	function wf_edit_meta($args) {

		$defaults = array (
			'userintro' => "Welcome",
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
			$liclass = wp_kses_data($liclass);
			$divclass = wp_kses_data($divclass);
			$this_admin = admin_url();

			$output = '';

			if ( $div == 'Y') { $output .= '<div class="'.esc_attr($divclass).'">'; }

			$output .= '<ul class="' . esc_attr($ulclass) . '">';

			if ( $username == 'Y' && $intro == 'Y' ) {

				$output .= '<li class="' . esc_attr($liclass) . '">';
				$output .= esc_attr($userintro) . ' ';
				$output .= ucwords( $current_user->display_name ) . '</li>';

			} elseif ( $username == 'N') {

				if ( $intro == 'Y') {

					$output .= '<li class="' . esc_attr($liclass) . '">';
					$output .= esc_attr($userintro) . ' ';
					$output .= '</li>';

				}
			}

			wp_reset_query();
			global $wp_query;

			switch (TRUE) {

				case (is_search()) || (is_404()) :
					// Silence is golden
				break;

				case (is_single() || is_page()) :
					$this_post_id = $wp_query->post->ID;
					if ( current_user_can('edit_post', $this_post_id) ) {
						$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . 'post.php?action=edit&amp;post=' . $this_post_id . '" title="' . esc_attr__('Edit this', 'Wonderflux') . '">' . esc_attr__('Edit this content', 'Wonderflux') . '</a></li>';
					}
				break;

				default :
					// Silence is golden
				break;

			}

			if ( current_user_can('edit_posts') && $postcontrols == 'Y' ) {
				$output .= '<li class="' . esc_attr($liclass) . '">' . esc_attr__('POSTS:', 'Wonderflux') . ' <a href="' . wp_sanitize_redirect($this_admin) . 'post-new.php" title="' . esc_attr__('Create a new post', 'Wonderflux') . '">' . esc_attr__('New', 'Wonderflux') . '</a> | <a href="' . wp_sanitize_redirect($this_admin) . 'edit.php" title="' . esc_attr__('Edit existing posts', 'Wonderflux') . '">' . esc_attr__('Edit', 'Wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_pages') && $pagecontrols == 'Y' ) {
				$output .= '<li class="' . esc_attr($liclass) . '">' . esc_attr__('PAGES:', 'Wonderflux') . ' <a href="' . wp_sanitize_redirect($this_admin) . 'post-new.php?post_type=page" title="' . esc_attr__('Create new page', 'Wonderflux') . '">' . esc_attr__('New', 'Wonderflux') . '</a> | <a href="' . wp_sanitize_redirect($this_admin) . 'edit.php?post_type=page" title="' . esc_attr__('Edit existing pages', 'Wonderflux') . '">' . esc_attr__('Edit', 'Wonderflux') . '</a></li>';
			}

			if ( current_user_can('publish_posts') && $adminlink == 'Y' ) {
				$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . '" title="' . esc_attr__('Admin area', 'Wonderflux') . '">' . esc_attr__('Admin area', 'Wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_theme_options') && $widgetslink == 'Y' ) {
				$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . 'widgets.php" title="' . esc_attr__('Edit widgets', 'Wonderflux') . '">' . esc_attr__('Edit widgets', 'Wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_theme_options') && $wfcontrols == 'Y' ) {
				$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . 'admin.php?page=wonderflux" title="Wonderflux ' . esc_attr__('design options', 'Wonderflux') . '">Wonderflux ' . esc_attr__('options', 'Wonderflux') . '</a></li>';
			}

			if ( $logoutlink == 'Y' ) {
			$output .= '<li class="' . esc_attr($liclass) . '">' . '<a href="' . wp_logout_url( get_permalink() ) . '" title="' . esc_attr__('Log out of website CMS', 'Wonderflux') . '">' . esc_attr__('Log out of CMS', 'Wonderflux') . '</a></li>';
			}

			/*$output .= '<p class="wp-meta"><a href="' . wp_sanitize_redirect($this_admin) . 'edit-comments.php" title="Edit comments">Edit comments</a></p>';*/

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
	 * @updated 0.92
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
			'morelinktext' => 'Read',
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
	 * @updated 0.92
	 */
	function wf_login_logout($args) {

		$defaults = array (
			'login' => 'Login',
			'logintip' => 'Login to site',
			'logout' => 'Logout',
			'logouttip' => 'Logout of site',
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
	 * A soon to be 'swiss army knife' of attachment getters!
	 *
	 * TODO: Extend and build properly before next beta!!
	 * TODO: Build different output types
	 * TODO: Document!
	 *
	 * @since 0.901
	 * @updated 0.901
	 */
	function wf_get_attachments($args) {

		$defaults = array (
			'type' => "image",
			'number' => 1,
			'order' => "ASC",
			'output' => "file_url",
			'echo' => "Y"
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Prepare user input for output
		$type = wp_kses_data($type);
		if (!is_numeric($number)) { $number=1; }
		$order = wp_kses_data($order);
		$output = wp_kses_data($output);

		global $post;

		$files = get_children(array(
			'post_parent'    => $post->ID,
			'post_type' => 'attachment',
			'order' => $order,
			'post_mime_type' => $type,
			'numberposts' => $number,
			'post_status' => null
		));

		foreach($files as $file) {

			switch ($output) {
				case 'file_url' : $this_output = wp_get_attachment_url($file->ID); break;
				case 'parent_url' : $this_output = get_permalink($file->post_parent); break;
				case 'page_url' : $this_output = get_attachment_link($file->ID); break;
				default : $this_output = wp_get_attachment_url($file->ID); break;
			}

			if ($echo == "Y") { echo $this_output; }
			else { return $this_output; }

		}

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
	 * @updated 0.93
	 */
	function wf_page_counter($args) {

		$defaults = array (
			'element' => 'p',
			'start' => 'Page ',
			'seperator' => ' of ',
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

}

?>