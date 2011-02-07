<?php
//TODO: Setup for translation
/**
* @since 0.913
* @updated 0.913
* Core display functions that output code
*/
class wflux_display_code extends wflux_data {

	/**
	* Builds the start of the head with doc type declaration
	*
	* @since 0.63
	* @updated 0.884
	*
	* @param $doctype (limited variable string) : Document type : 'transitional' (default), 'strict', 'frameset', '1.1', '1.1basic', 'html5'
	* @param $lang (user variable string) : Alphabetic International language code : 'en' (default), USER INPUT
	* @param $content (user variable string) : Document content : 'html' (default), USER INPUT
	* @param $charset (user variable string) : Character encoding : 'utf8' (default), USER INPUT
	*
	* FILTERING
	* Can override/replace parameters in your child theme functions.php file by using add_filter
	* - TODO: SHOW PARAMETERS HERE ONCE FILTERS SETUP!
	*
	* OVERRIDE
	* Can override/replace entire function in your child theme functions.php file
	* - Create a function called 'wf_display_head_top', with your desired functionality
	* - NOTE: Replacing the function DOES NOT REQUIRE YOU TO remove_action - by creating a function with the name wf_display_head_top
	*
	* ADVANCED
	* Can be removed completely in your child theme functions.php file
	* NOTE: NOT ADVISED AS THIS IS AN ESSENTIAL DISPLAY FUNCTION FOR VALID WEBPAGE CODE OUTPUT
	* - Remove action wf_display_head_top (core Wonderflux display function that facilitates this function)
	*
	* USING DIRECTLY
	* You can then use this function directly with your own parameters in your child theme functions file
	*   - TODO: SHOW PARAMETERS HERE!
	*
	*/
	function wf_head_top($args) {

		$defaults = array (
			'doctype' => 'transitional',
			'lang' => 'en',
			'content' => 'html',
			'charset' => 'UTF-8'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

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

		// If its HTML 5 it's a simple output
		if ($doctype == 'html5') {

			echo '<!DOCTYPE HTML>' . "\n";

		} else {

			// Language code
			$lang_length = strlen($lang);
			if ($lang_length == 2) {
				$lang_output = strtolower(wp_kses($lang, ''));
			} else {
				//Invalid user input - set a sensible default
				$lang_output = 'en';
			}

			// Content type
			$content_length = strlen($content);
			//TODO: R&D all different content types
			if ($content_length <= 4) {
				$content_output = strtolower(wp_kses($content, ''));
			} else {
				//Invalid user input - set a sensible default
				$content_output = 'html';
			}

			// Character set
			$charset_output = wp_kses($charset, '');

			// Setup default strings not used in HTML 5 spec
			$common_1 = 'html PUBLIC "-//W3C//DTD XHTML ';
			$common_2 = '" "http://www.w3.org/TR/xhtml';
			$output = '<!DOCTYPE ' . $common_1 . $doctype_output . '//' . strtoupper($lang_output) . $common_2 . $doctype_link_output . '.dtd">';
			$output .="\n";
			$output .='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$lang_output.'" lang="'.$lang_output.'">';
			$output .="\n";
			$output .= '<head>';
			$output .="\n";
			$output .= '<meta http-equiv="Content-Type" content="text/'.$content_output.'; charset='.$charset_output.'" />';
			$output .="\n";
			echo $output;
		}
	}


	/**
	* Builds the title in the head of the template
	* @since 0.1
	* @updated 0.881
	* OVERRIDE
	* Can override/replace entire function in your child theme functions.php file
	* - Create a function called 'wf_display_head_title', with your desired functionality
	* - NOTE: Replacing the function DOES NOT REQUIRE YOU TO remove_action - by creating a function with the name wf_display_head_top
	* ADVANCED
	* Can be removed completely in your child theme functions.php file
	* NOTE: NOT ADVISED AS THIS IS AN ESSENTIAL DISPLAY FUNCTION FOR VALID WEBPAGE CODE OUTPUT
	* - Remove action wf_display_head_title (core Wonderflux display function that facilitates this function)
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

	}


	/**
	* @since 0.72
	* @updated 0.913
	* Core structure CSS
	*/
	function wf_head_css_structure($args) {
		// Default
		$structure_path = WF_CONTENT_URL . '/css/wf-css-core-structure.css';
		// Allow filtering
		$structure_path = apply_filters( 'wflux_head_css_structure_path', $structure_path );

		$structure_output = '<link rel="stylesheet" href="'. $structure_path .'" type="text/css" media="screen, projection"/>';

		$structure_output = apply_filters( 'wflux_head_css_structure', $structure_output );
		$structure_output .= "\n";

		echo $structure_output;
	}


	/**
	* @since 0.72
	* @updated 0.913
	* Core typography CSS
	*/
	function wf_head_css_typography($args) {
		// Default
		$typography_path = WF_CONTENT_URL . '/css/wf-css-core-typography.css';
		// Allow filtering
		$typography_path = apply_filters( 'wflux_head_css_typography_path', $typography_path );

		$typography_output = '<link rel="stylesheet" href="'. $typography_path .'" type="text/css" media="screen, projection"/>';

		$typography_output = apply_filters( 'wflux_head_css_typography', $typography_output );
		$typography_output .= "\n";

		echo $typography_output;
	}


	/**
	* @since 0.72
	* @updated 0.72
	* Core theme CSS
	*/
	function wf_head_css_theme($args) {
		// Default
		$theme_path = get_bloginfo('stylesheet_url');
		// Allow filtering
		$theme_path = apply_filters( 'wflux_head_css_theme_path', $theme_path );

		$theme_output = '<link rel="stylesheet" href="'. $theme_path .'" type="text/css" media="screen, projection"/>';

		$theme_output = apply_filters( 'wflux_head_css_theme', $theme_output );
		$theme_output .= "\n";

		echo $theme_output;
	}


	/**
	* @since 0.72
	* @updated 0.913
	* Dynamic grid builder
	* TODO: Allow filtering on figures
	*/
	function wf_head_css_columns() {

		$container_w = $this->wfx_width; //Overall container width
		$container_p = $this->wfx_position; //Site container position
		$sidebar_p = $this->wfx_sidebar_primary_position; //Site container position
		//$padding_l = $options['padding_l']; // Container padding left
		//$padding_r = $options['padding_r']; // Container padding right
		$columns_num = $this->wfx_columns; // Number of columns
		$columns_w = $this->wfx_columns_width;	// Width of column

		// Default
		$columns_path = WF_CONTENT_URL . '/css/wf-css-dynamic-columns.php?w='.$container_w;
		$columns_path .= '&amp;p='.$container_p;
		$columns_path .= '&amp;sbp='.$sidebar_p;
		$columns_path .= '&amp;cw='.$columns_w;
		$columns_path .= '&amp;c='.$columns_num;
		$columns_path .= '';

		// Allow filtering
		$columns_path = apply_filters( 'wflux_head_css_columns_path', $columns_path );

		$columns_output = '<link rel="stylesheet" href="'. $columns_path .'" type="text/css" media="screen, projection"/>';

		$columns_output = apply_filters( 'wflux_head_css_columns', $columns_output );
		$columns_output .= "\n";

		echo $columns_output;
	}


	/**
	* @since 0.80
	* @updated 0.913
	* Core layout grid CSS
	*/
	function wf_head_css_ie() {

		$container_w = $this->wfx_width; //Overall container width
		$container_p = $this->wfx_position; //Site container position
		$sidebar_p = $this->wfx_sidebar_primary_position; //Site container position
		$columns_num = $this->wfx_columns; // Number of columns
		$columns_w = $this->wfx_columns_width;	// Width of column

		// Default
		$ie_path = WF_CONTENT_URL . '/css/wf-css-dynamic-core-ie.php?w='.$container_w.
		'&amp;p='.$container_p.
		'&amp;sbp='.$sidebar_p.
		'&amp;cw='.$columns_w.
		'&amp;c='.$columns_num.'';

		// Default
		$ie_version = 'lt IE 8';

		// Allow filtering
		$ie_path = apply_filters( 'wflux_head_css_ie_path', $ie_path );
		$ie_version = apply_filters( 'wflux_head_css_ie_version', $ie_version );

		$ie_output = '<!--[if '. $ie_version .']><link rel="stylesheet" href="'. $ie_path .'" type="text/css" media="screen, projection"/><![endif]-->';

		$ie_output = apply_filters( 'wflux_head_css_ie', $ie_output );
		$ie_output .= "\n";

		echo $ie_output;

	}


	/**
	* @since 0.71
	* @updated 0.902
	* IMPORTANT
	* Close the head of the document after everything has run
	* Opens body tag using dynamic WordPress body class
	*/
	function wf_head_close() {

		$this_body_class = get_body_class();
		$output = '</head>' . "\n";
		$output .= '<body class="';
		$output .= join( ' ', $this_body_class );
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
	function wf_performance() {
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
	function wf_code_credit() {

		// Default
		$footer_credit = 'Powered by WordPress and the Wonderflux theme framework';
		// Allow filtering
		$footer_credit_output = apply_filters( 'wflux_comment_code_credit', $footer_credit );

		$output = '<!-- '.$footer_credit_output.' -->'."\n";
		echo $output;
	}


	/**
	* Creates the default layout for if Wonderflux is activated directly
	* @since 0.902
	* @updated 0.913
	*/
	function wf_default_layout() {
		add_action( 'wfmain_before_all_content', array ($this, 'wf_default_content') );
		add_action( 'wfmain_after_all_content', array ($this, 'wf_default_close_div') );

		add_action( 'wfsidebar_before_all', array ($this, 'wf_default_sidebar') );
		add_action( 'wfsidebar_after_all', array ($this, 'wf_default_close_div') );
	}


	/**
	* Creates the default layout if Wonderflux is activated directly
	* @since 0.913
	* @updated 0.913
	*/
	function wf_default_content() { global $wfx; $wfx->css('size=three_quarter&id=content&last=y&divoutput=TRUE'); }


	/**
	* Creates the default layout if Wonderflux is activated directly
	* @since 0.913
	* @updated 0.913
	*/
	function wf_default_sidebar() { global $wfx; $wfx->css('size=quarter&id=sidebar&last=y&divoutput=TRUE'); }


	/**
	* Used in default layout if Wonderflux is activated directly
	* @since 0.913
	* @updated 0.913
	*/
	function wf_default_close_div() { global $wfx; $wfx->css_close(''); }

}


/**
* @since 0.913
* @updated 0.913
* Core display functions that output CSS
*/
class wflux_display_css extends wflux_data {

	/**
	*
	* @since 0.2
	* @updated 0.913
	* @params
	* size = MANDITORY - Relative definition - eg 'half', 'quarter', 'twothird'
	* extra = OPTIONAL - Extra CSS classes you want to include in definition ie 'content-feature'
	* last = OPTIONAL - Put on last container inside row, eg half, half LAST
	* move = OPTIONAL - NOT ACTIVE - push and pull a div, not using at moment because we are not using this for sidebar(s) placement
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
	*/
	function wf_css($args) {

		$defaults = array (
			'size' => 'full',
			'class' => '',
			'id' => '',
			'last' => '',
			'move' => '',
			'divoutput' => FALSE
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
		if ($divoutput == TRUE) {
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
		switch ($size) {
			//Define $last - this must always go on the last container in a row
			case 'full' : $size_def = $css_core_def . $wf_columns; $last = ' last'; break;
			case 'half' : $size_def = $css_core_def . ($wf_columns/2); break;
			case 'single' : $size_def = $css_core_def . $wf_single; break;
			//Suggest use one_third for consistency, but keep this just in-case
			case 'third' : $size_def = $css_core_def . ($wf_columns/3); break;
			case 'one_third' : $size_def = $css_core_def . ($wf_columns/3); break;
			case 'two_third' : $size_def = $css_core_def . ($wf_columns/3*2); break;
			//Suggest use one_quarter for consistency, but keep this just in-case
			case 'quarter' : $size_def = $css_core_def . ($wf_columns/4); break;
			case 'one_quarter' : $size_def = $css_core_def . ($wf_columns/4); break;
			case 'two_quarter' : $size_def = $css_core_def . ($wf_columns/2); break;
			case 'three_quarter' : $size_def = $css_core_def . ($wf_columns/4*3); break;
			//Suggest use one_fifth for consistency, but keep this just in-case
			case 'fifth' : $size_def = $css_core_def . ($wf_columns/5); break;
			case 'one_fifth' : $size_def = $css_core_def . ($wf_columns/5); break;
			case 'two_fifth' : $size_def = $css_core_def . ($wf_columns/5*2); break;
			case 'three_fifth' : $size_def = $css_core_def . ($wf_columns/5*3); break;
			case 'four_fifth' : $size_def = $css_core_def . ($wf_columns/5*4); break;
			//Suggest use one_sixth for consistency, but keep this just in-case
			case 'sixth' : $size_def = $css_core_def . ($wf_columns/6); break;
			case 'one_sixth' : $size_def = $css_core_def . ($wf_columns/6); break;
			case 'two_sixth' : $size_def = $css_core_def . ($wf_columns/6*2); break;
			case 'three_sixth' : $size_def = $css_core_def . ($wf_columns/6*3); break;
			case 'four_sixth' : $size_def = $css_core_def . ($wf_columns/6*4); break;
			case 'five_sixth' : $size_def = $css_core_def . ($wf_columns/6*5); break;
			//Suggest use one_seventh for consistency, but keep this just in-case
			case 'seventh' : $size_def = $css_core_def . ($wf_columns/7); break;
			case 'one_seventh' : $size_def = $css_core_def . ($wf_columns/7); break;
			case 'two_seventh' : $size_def = $css_core_def . ($wf_columns/7*2); break;
			case 'three_seventh' : $size_def = $css_core_def . ($wf_columns/7*3); break;
			case 'four_seventh' : $size_def = $css_core_def . ($wf_columns/7*4); break;
			case 'five_seventh' : $size_def = $css_core_def . ($wf_columns/7*5); break;
			case 'six_seventh' : $size_def = $css_core_def . ($wf_columns/7*6); break;
			//Suggest use one_eigth for consistency, but keep this just in-case
			case 'eigth' : $size_def = $css_core_def . ($wf_columns/8); break;
			case 'one_eigth' : $size_def = $css_core_def . ($wf_columns/8); break;
			case 'two_eigth' : $size_def = $css_core_def . ($wf_columns/8*2); break;
			case 'three_eigth' : $size_def = $css_core_def . ($wf_columns/8*3); break;
			case 'four_eigth' : $size_def = $css_core_def . ($wf_columns/8*4); break;
			case 'five_eigth' : $size_def = $css_core_def . ($wf_columns/8*5); break;
			case 'six_eigth' : $size_def = $css_core_def . ($wf_columns/8*6); break;
			case 'seven_eigth' : $size_def = $css_core_def . ($wf_columns/8*7); break;
			//Suggest use one_ninth for consistency, but keep this just in-case
			case 'ninth' : $size_def = $css_core_def . ($wf_columns/9); break;
			case 'one_ninth' : $size_def = $css_core_def . ($wf_columns/9); break;
			case 'two_ninth' : $size_def = $css_core_def . ($wf_columns/9*2); break;
			case 'three_ninth' : $size_def = $css_core_def . ($wf_columns/9*3); break;
			case 'four_ninth' : $size_def = $css_core_def . ($wf_columns/9*4); break;
			case 'five_ninth' : $size_def = $css_core_def . ($wf_columns/9*5); break;
			case 'six_ninth' : $size_def = $css_core_def . ($wf_columns/9*6); break;
			case 'seven_ninth' : $size_def = $css_core_def . ($wf_columns/9*7); break;
			case 'eight_ninth' : $size_def = $css_core_def . ($wf_columns/9*8); break;
			//Suggest use one_tenth for consistency, but keep this just in-case
			case 'tenth' : $size_def = $css_core_def . ($wf_columns/10); break;
			case 'one_tenth' : $size_def = $css_core_def . ($wf_columns/10); break;
			case 'two_tenth' : $size_def = $css_core_def . ($wf_columns/10*2); break;
			case 'three_tenth' : $size_def = $css_core_def . ($wf_columns/10*3); break;
			case 'four_tenth' : $size_def = $css_core_def . ($wf_columns/10*4); break;
			case 'five_tenth' : $size_def = $css_core_def . ($wf_columns/10*5); break;
			case 'six_tenth' : $size_def = $css_core_def . ($wf_columns/10*6); break;
			case 'seven_tenth' : $size_def = $css_core_def . ($wf_columns/10*7); break;
			case 'eight_tenth' : $size_def = $css_core_def . ($wf_columns/10*8); break;
			case 'nine_tenth' : $size_def = $css_core_def . ($wf_columns/10*9); break;
			//Suggest use one_eleventh for consistency, but keep this just in-case
			case 'eleventh' : $size_def = $css_core_def . ($wf_columns/11); break;
			case 'one_eleventh' : $size_def = $css_core_def . ($wf_columns/11); break;
			case 'two_eleventh' : $size_def = $css_core_def . ($wf_columns/11*2); break;
			case 'three_eleventh' : $size_def = $css_core_def . ($wf_columns/11*3); break;
			case 'four_eleventh' : $size_def = $css_core_def . ($wf_columns/11*4); break;
			case 'five_eleventh' : $size_def = $css_core_def . ($wf_columns/11*5); break;
			case 'six_eleventh' : $size_def = $css_core_def . ($wf_columns/11*6); break;
			case 'seven_eleventh' : $size_def = $css_core_def . ($wf_columns/11*7); break;
			case 'eight_eleventh' : $size_def = $css_core_def . ($wf_columns/11*8); break;
			case 'nine_eleventh' : $size_def = $css_core_def . ($wf_columns/11*9); break;
			case 'ten_eleventh' : $size_def = $css_core_def . ($wf_columns/11*10); break;
			//Suggest use one_twelveth for consistency, but keep this just in-case
			case 'twelveth' : $size_def = $css_core_def . ($wf_columns/12); break;
			case 'one_twelveth' : $size_def = $css_core_def . ($wf_columns/12); break;
			case 'two_twelveth' : $size_def = $css_core_def . ($wf_columns/12*2); break;
			case 'three_twelveth' : $size_def = $css_core_def . ($wf_columns/12*3); break;
			case 'four_twelveth' : $size_def = $css_core_def . ($wf_columns/12*4); break;
			case 'five_twelveth' : $size_def = $css_core_def . ($wf_columns/12*5); break;
			case 'six_twelveth' : $size_def = $css_core_def . ($wf_columns/12*6); break;
			case 'seven_twelveth' : $size_def = $css_core_def . ($wf_columns/12*7); break;
			case 'eight_twelveth' : $size_def = $css_core_def . ($wf_columns/12*8); break;
			case 'nine_twelveth' : $size_def = $css_core_def . ($wf_columns/12*9); break;
			case 'ten_twelveth' : $size_def = $css_core_def . ($wf_columns/12*10); break;
			case 'eleven_twelveth' : $size_def = $css_core_def . ($wf_columns/12*11); break;
			//Phyew! And finally the default as a fallback
			default: $size_def = $size_def = $css_core_def . $wf_columns; $last = ' last'; break;
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
		echo $divout_open_clean . 'class="' . $size_def . $move_def . $last . $class_clean . '"' . $id_clean . $divout_close_clean;

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

}


/**
* @since 0.1
* @updated 0.913
* Core display functions that output visible items rendered to page
*/
class wflux_display {


	/**
	*
	* @since 0.3
	* @updated 0.913
	*
	* Footer credit
	*
	*/
	function wf_credit() {

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
			wfx_css('size=full&id=footer-wfx-credit&divoutput=TRUE');
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
* @updated 0.913
* Extra core display functions that are for theme designers
*/
class wflux_display_extras {


	/**
	 * Function for displaying the excerpt with just a certain number of words
	 * Can be used inside loop or custom wp_query
	 *
	 * ARGUMENTS
	 * $limit = Number of words. Default = '20'
	 * $excerpt_end = String of characters after the end of the excerpt. Default '...'
	 *
	 * @since 0.85
	 * @updated 0.893
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

		// NOTE: No need for fail-safe if no excerpt, it pulls post content instead if there is no excerpt set.
		$content = get_the_excerpt();

		$excerpt = explode(' ', $content, ($limit+1));

		if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'';
		} else {
		$excerpt = implode(" ",$excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);

		//Finally, check for any rogue spaces
		$excerpt = trim($excerpt);

		echo esc_attr($excerpt) . esc_attr($excerpt_end);

	}


	/**
	 * Displays a configurable Twitter stream
	 *
	 * @param user - The Twitter username [Wonderflux]
	 * @param update - How often you want the Twitter stream cache to refresh [60]
	 * @param items - Number of Tweets you wish to show [5]
	 * @param showname - Show the username at the start of each Tweet [N]
	 * @param tweetlinks - Turn links in Tweets into clickable links [Y]
	 * @param showdate - Show the date at after the Tweet [Y]
	 * @param dateformat - If 'relative' displays 'recently' if less than 24 hours or shows complete days if over 24 hours [relative]
	 * @param smileys - If Tweet has typographic smiley faces in, replace with graphics [Y]
	 * @param contentstyle - Tweet (and date) CSS style [p]
	 * @param tweetclass - Tweet (and date) CSS class [twitter-stream]
	 * @param seperator - Seperator between Tweet and date (not shown if date is hidden) [ - ]
	 * @param dateclass - CSS span class on date [twitter-stream-date]
	 *
	 * @since 0.85
	 * @updated 0.912
	 */
	function wf_twitter_feed($args) {

		$defaults = array (
			'user' => 'Wonderflux',
			'update' => '60',
			'items' => '5',
			'showname' => 'N',
			'tweetlinks' => 'Y',
			'showdate' => 'Y',
			'dateformat' => 'relative',
			'smileys' => 'Y',
			'contentstyle' => 'p',
			'tweetclass' => 'twitter-stream',
			'seperator' => ' - ',
			'dateclass' => 'twitter-stream-date'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Prepare user input for output
		$user = wp_kses_data($user);
		if (!is_numeric($update)) { $update = 60; } // Checking if a number is light weight
		if (!is_numeric($items)) { $items = 5; } // Checking if a number is light weight
		$contentstyle = wp_kses_data($contentstyle);

		// Get WP feed functionality
		include_once(ABSPATH . WPINC . '/feed.php');

		// Cache refresh as seconds instead of minutes
		$update = $update*60;

		add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$update', 'return '.$update.';' ) );

		// Fetch feed
		$rss = fetch_feed( esc_url('http://twitter.com/statuses/user_timeline/'.$user.'.rss') );

		if ( is_wp_error($rss) ) {

			// Oops, we don't have any data
			echo '<' . esc_attr($contentstyle) . ' class="' . esc_attr($tweetclass) . '">' . esc_attr__('Sorry, no Tweets available', 'Wonderflux') . '</' . esc_attr($contentstyle) . '>';

		} else {

		// How many?
		$rss_items = $rss->get_items( 0, $rss->get_item_quantity($items) );

		foreach ( $rss_items as $item ) {

			$this_update = $item->get_title();

			if ($showname == 'N') {
				// Remove username from start
				$this_update = preg_replace('/'.$user.':/', '', $this_update, 1);
			}

			if ($tweetlinks == 'Y') {
				// Parse and setup link URLs
				$this_update = preg_replace( "/(([[:alnum:]]+:\/\/)|www\.)([^[:space:]]*)"."([[:alnum:]#?\/&=])/i", "<a href=\"\\1\\3\\4\">"."\\1\\3\\4</a>", $this_update);
			}

			// OUTPUT START OF TWEET
			echo '<' . esc_attr($contentstyle) . ' class="' . esc_attr($tweetclass) . '">';

			// OUTPUT TWEET
			if ($smileys == 'Y') {
				echo convert_smilies(ltrim($this_update));
			} else {
				echo ltrim($this_update);
			}

			if ($showdate == 'Y') {

				if ($dateformat == 'relative') {

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
						echo esc_attr($seperator) . '<span class="' . esc_attr($dateclass) . '">' . esc_attr__('Recently', 'Wonderflux') . '</span>';
					} else {
						// Sort out formatting
						if ($fulldays == '1') {
						// Just one day, so its day not days
							$dayappend = '';
						} else {
							$dayappend = 's';
						}
						//TODO: Internationalisation for day/days (single/plural)
						echo esc_attr($seperator) . '<span class="' . esc_attr($dateclass) . '">' . $fulldays . ' ' . esc_attr__('day', 'Wonderflux') . $dayappend . ' ' . esc_attr__('ago', 'Wonderflux') . '</span>';
					}

					} else {
						echo esc_attr($seperator) . '<span class="' . esc_attr($dateclass) . '">' . $item->get_date('') . '</span>';
					}
				}

				//Close off tweet
				echo '</' . esc_attr($contentstyle) . '>';

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
			'showtitle' => 'Y',
			'seperator' => ' - ',
			'imgclass' => 'button-more',
			'imgpath' => 'images',
			'img' => 'button-read-more.png',
			'width' => 150,
			'height' => 30
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Tidy up ready for use
		$intro = wp_kses_data($intro, '');
		if ( $showtitle == 'Y' ) { $intro .= wp_kses_data($seperator, '') . get_the_title(); }
		$imgclass = wp_kses_data($imgclass, '');
		$imgpath = wp_kses_data($imgpath, '');
		$img = wp_kses_data($img, '');
		if (!is_numeric($width)) { $width = 150; } // Checking if a number is light weight
		if (!is_numeric($height)) { $height = 30; } // Checking if a number is light weight

		$output = '<a href="' . get_permalink() . '" title="' . $intro . '">';
		$output .= '<img class="';
		$output .= esc_attr($imgclass);
		$output .= '" src="';
		$output .= dirname( get_bloginfo('stylesheet_url') );
		$output .= '/' . esc_attr($imgpath) . '/';
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
	 * @output divclass - CSS class of div [wf-edit-meta-box]
	 * @output <ul><li>list items of various admin links inside optional CSS DIV
	 *
	 * @since 0.85
	 * @updated 0.913
	 */
	function wf_edit_meta($args) {

		$defaults = array (
			'userintro' => "Welcome",
			'username' => "Y",
			'intro' => "Y",
			'postcontrols' => "Y",
			'pagecontrols' => "Y",
			'adminlink' => "Y",
			'widgetslink' => "Y",
			'wfcontrols' => "N",
			'logoutlink' => "N",
			'ulclass' => 'wf-edit-meta',
			'liclass' => 'wf-edit-meta-links',
			'div' => "N",
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
			$liclass = wp_kses_data($liclass);
			$divclass = wp_kses_data($divclass);
			$this_admin = admin_url();

			$output = '';

			if ( $div == "Y") { $output .= '<div class="'.esc_attr($divclass).'">'; }

			$output .= '<ul class="' . esc_attr($ulclass) . '">';

			if ( $username == "Y" && $intro == "Y" ) {

				$output .= '<li class="' . esc_attr($liclass) . '">';
				$output .= esc_attr($userintro) . ' ';
				$output .= $current_user->display_name . '</li>';

			} elseif ( $username == "N") {

				if ( $intro == "Y") {

					$output .= '<li class="' . esc_attr($liclass) . '">';
					$output .= esc_attr($userintro) . ' ';
					$output .= '</li>';

				}
			}

			wp_reset_query();
			global $wp_query;

			//Check for 404
			if (!is_404()){
				$this_post_id = $wp_query->post->ID;
			}

			//TODO: Build in extra checks for neatness to remove stuff

			if ( is_single() || is_page() && current_user_can('edit_post', $this_post_id) && !is_404() ) {
				$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . 'post.php?action=edit&amp;post=' . $this_post_id . '" title="' . esc_attr__('Edit this', 'Wonderflux') . '">' . esc_attr__('Edit this content', 'Wonderflux') . '</a></li>';
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

			if ( current_user_can('edit_theme_options') && $widgetslink == "Y" ) {
				$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . 'widgets.php" title="' . esc_attr__('Edit widgets', 'Wonderflux') . '">' . esc_attr__('Edit widgets', 'Wonderflux') . '</a></li>';
			}

			if ( current_user_can('edit_theme_options') && $wfcontrols == "Y" ) {
				$output .= '<li class="' . esc_attr($liclass) . '"><a href="' . wp_sanitize_redirect($this_admin) . 'admin.php?page=wonderflux" title="Wonderflux ' . esc_attr__('design options', 'Wonderflux') . '">Wonderflux ' . esc_attr__('options', 'Wonderflux') . '</a></li>';
			}

			if ( $logoutlink == "Y" ) {
			$output .= '<li class="' . esc_attr($liclass) . '">' . '<a href="' . wp_logout_url( get_permalink() ) . '" title="' . esc_attr__('Log out of website CMS', 'Wonderflux') . '">' . esc_attr__('Log out of CMS', 'Wonderflux') . '</a></li>';
			}

			/*$output .= '<p class="wp-meta"><a href="' . wp_sanitize_redirect($this_admin) . 'edit-comments.php" title="Edit comments">Edit comments</a></p>';*/

			$output .= '</ul>';

			if ( $div == 'Y') { $output .= '</div>'; }

			echo $output;

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
	 * @updated 0.892
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

		// Containing div open
		echo '<div class="' . esc_attr($boxclass) . '" id="post-' . get_the_ID() . '">';

			// Title
			if ($title == 'Y') {
				$titleoutput = '<div class="wf-mini-display-title">';
				$titleoutput .= '<' . esc_attr($titlestyle) . '>';
				if ($titlelink == 'Y') { $titleoutput .= '<a href="' . get_permalink() . '" title="'. get_the_title() .'">'; }
				$titleoutput .= get_the_title();
				if ($titlelink == 'Y') { $titleoutput .= '</a>'; }
				$titleoutput .= '</' . esc_attr($titlestyle) . '>';
				$titleoutput .= "\n";
				$titleoutput .= '</div>';
				echo $titleoutput;
			}

			// Content
			echo '<div class="' . esc_attr($contentclass) . '">';
				echo "\n";
				echo '<' . esc_attr($contentstyle) . '>';
				$this->wf_excerpt('limit=' . $exerptlimit . '&excerpt_end=' . esc_attr($exerptend) . '');
				echo '</' . esc_attr($contentstyle) . '>';
				echo "\n";
			echo '</div>';

			// More link
			if ($morelink == 'Y') {
				$morelinkoutput = '<p><a href="' . get_permalink() . '" title="' . esc_attr($morelinktext) . ' ' . get_the_title() . '" class="' . esc_attr($morelinkclass) . '">';
				$morelinkoutput .= esc_attr($morelinktext);
				$morelinkoutput .= '</a></p>';
				echo $morelinkoutput;
			}

		// Containing div close
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
	 *
	 * @since 0.901
	 * @updated 0.901
	 */
	function wf_login_logout($args) {

		$defaults = array (
			'login' => "Login",
			'logintip' => "Login to site",
			'logout' => "Logout",
			'logouttip' => "Logout of site",
			'loginredirect' => "dashboard",
			'logoutredirect' => "current"
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( is_user_logged_in() ) {
			echo '<a href="'.wp_logout_url( home_url() ).'" title="'.esc_attr($logouttip).'">'.esc_attr($logout).'</a>';
		} else {
			echo '<a href="'.wp_login_url().'" title="'.esc_attr($logintip).'">'.esc_attr($login).'</a>';
		}

	}


	/**
	 * If you really need to hard-code your site page or category navigation,
	 * this function adds 'current_page_item' CSS class when page/category is viewed
	 *
	 * You really should use wp_list_pages() and wp_list_categories() wherever possible!
	 *
	 * TODO: Tidy up!
	 *
	 * @param pageid - Page ID [NONE]
	 * @param catid - Category ID [NONE]
	 *
	 * @since 0.901
	 * @updated 0.901
	 */
	function wf_static_highlight($thisid) {

		global $post;

		if ($thisid !=''):

			//Style if on actual page on top level navigation
			if ($post->ID == $thisid):
				echo ' current_page_item';
			endif;

		endif;

		//echo $post->ID;

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
			'output' => "file_url"
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

				case 'file_url' :
					$this_output = wp_get_attachment_url($file->ID);
				break;

				case 'parent_url' :
					$this_output = get_permalink($file->post_parent);
				break;

				case 'page_url' :
					$this_output = get_attachment_link($file->ID);
				break;

				default :
					$this_output = wp_get_attachment_url($file->ID);
				break;

			}

			return $this_output;

			//DEBUG TESTING

			//$attlink  = get_attachment_link($audio_file->ID);
			//$postlink = get_permalink($audio_file->post_parent);
			//$atttitle = apply_filters('the_title',$audio_file->post_title);

			//echo '<p><strong>wp_get_attachment_image()</strong><br />'.$attimg.'</p>';
			//echo 'Ians path:'.$atturl.'</p>';
			//echo '<p><strong>get_attachment_link()</strong><br />'.$attlink.'</p>';
			//echo '<p><strong>get_permalink()</strong><br />'.$postlink.'</p>';
			//echo '<p><strong>Title of attachment</strong><br />'.$atttitle.'</p>';
			//echo '<p><strong>Image link to attachment page</strong><br /><a href="'.$attlink.'">'.$attimg.'</a></p>';
			//echo '<p><strong>Image link to attachment post</strong><br /><a href="'.$postlink.'">'.$attimg.'</a></p>';
			//echo '<p><strong>Image link to attachment file</strong><br /><a href="'.$atturl.'">'.$attimg.'</a></p>';
		}

	}


}
?>