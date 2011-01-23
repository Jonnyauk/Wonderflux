<?php

//// The main Wonderflux CSS display functions ////
//TODO: Setup for translation

/**
*
* @since 0.1
* @updated 0.89
*
* Core Wonderflux display functions
*
*/
class wflux_display {

	/**
	*
	* Creates the default layout for if Wonderflux is activated directly
	*
	* @since 0.902
	* @updated 0.902
	*
	*/
	function wf_display_default_layout() {
		function my_wf_css_content() { wflux_display::wf_css('size=three_quarter&id=content&last=y&divoutput=TRUE'); }
		function my_wf_css_sidebar() { wflux_display::wf_css('size=quarter&id=sidebar&last=y&divoutput=TRUE'); }
		function my_wf_css_close_div() { echo '</div>'; }
		add_action( 'wfmain_before_all_content', 'my_wf_css_content' );
		add_action( 'wfsidebar_before_all', 'my_wf_css_sidebar');
		add_action( 'wfsidebar_after_all', 'my_wf_css_close_div' );
		add_action( 'wfmain_after_all_content', 'my_wf_css_close_div' );
	}


	/**
	*
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
	*
	* Builds the title in the head of the template
	*
	* @since 0.1
	* @updated 0.881
	*
	* OVERRIDE
	* Can override/replace entire function in your child theme functions.php file
	* - Create a function called 'wf_display_head_title', with your desired functionality
	* - NOTE: Replacing the function DOES NOT REQUIRE YOU TO remove_action - by creating a function with the name wf_display_head_top
	*
	* ADVANCED
	* Can be removed completely in your child theme functions.php file
	* NOTE: NOT ADVISED AS THIS IS AN ESSENTIAL DISPLAY FUNCTION FOR VALID WEBPAGE CODE OUTPUT
	* - Remove action wf_display_head_title (core Wonderflux display function that facilitates this function)
	*
	*
	*/
	function wf_head_title() {

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
	*
	* @since 0.72
	* @updated 0.72
	*
	* Core structure CSS
	*
	*/
	function wf_head_css_structure() {
		// Default
		$structure_path = WF_INCLUDES_URL . '/css/wf-css-core-structure.css';
		// Allow filtering
		$structure_path = apply_filters( 'wflux_head_css_structure_path', $structure_path );

		$structure_output = '<link rel="stylesheet" href="'. $structure_path .'" type="text/css" media="screen, projection"/>';

		$structure_output = apply_filters( 'wflux_head_css_structure', $structure_output );
		$structure_output .= "\n";

		echo $structure_output;
	}


	/**
	*
	* @since 0.72
	* @updated 0.72
	*
	* Core typography CSS
	*
	*/
	function wf_head_css_typography() {
		// Default
		$typography_path = WF_INCLUDES_URL . '/css/wf-css-core-typography.css';
		// Allow filtering
		$typography_path = apply_filters( 'wflux_head_css_typography_path', $typography_path );

		$typography_output = '<link rel="stylesheet" href="'. $typography_path .'" type="text/css" media="screen, projection"/>';

		$typography_output = apply_filters( 'wflux_head_css_typography', $typography_output );
		$typography_output .= "\n";

		echo $typography_output;
	}


	/**
	*
	* @since 0.72
	* @updated 0.72
	*
	* Core theme CSS
	*
	*/
	function wf_head_css_theme() {
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
	*
	* @since 0.80
	* @updated 0.902
	*
	* Core layout grid CSS
	*
	*/
	function wf_head_css_ie() {

		$options = get_option('wonderflux_display');

		$container_w = $options['container_w']; //Overall container width
		$container_p = $options['container_p']; //Site container position
		$sidebar_p = $options['sidebar_p']; //Site container position
		//$padding_l = $options['padding_l']; // Container padding left
		//$padding_r = $options['padding_r']; // Container padding right
		$columns_num = $options['columns_num']; // Number of columns
		$columns_w = $options['columns_w'];	// Width of column

		// Default
		$ie_path = WF_INCLUDES_URL . '/css/wf-css-dynamic-core-ie.php?w='.$container_w.
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
	*
	* @since 0.72
	* @updated 0.81
	*
	* Dynamic grid
	*
	* TODO: Allow filtering on figures
	*
	*/
	function wf_head_css_columns() {

		$options = get_option('wonderflux_display');

		$container_w = $options['container_w']; //Overall container width
		$container_p = $options['container_p']; //Site container position
		$sidebar_p = $options['sidebar_p']; //Site container position
		//$padding_l = $options['padding_l']; // Container padding left
		//$padding_r = $options['padding_r']; // Container padding right
		$columns_num = $options['columns_num']; // Number of columns
		$columns_w = $options['columns_w'];	// Width of column


		// Default
		$columns_path = WF_INCLUDES_URL . '/css/wf-css-dynamic-columns.php?w='.$container_w.
		'&amp;p='.$container_p.
		'&amp;sbp='.$sidebar_p.
		'&amp;cw='.$columns_w.
		'&amp;c='.$columns_num.'';
		// Allow filtering
		$columns_path = apply_filters( 'wflux_head_css_columns_path', $columns_path );

		$columns_output = '<link rel="stylesheet" href="'. $columns_path .'" type="text/css" media="screen, projection"/>';

		$columns_output = apply_filters( 'wflux_head_css_columns', $columns_output );
		$columns_output .= "\n";

		echo $columns_output;
	}



	/**
	*
	* @since 0.2
	* @updated 0.884
	* @params
	* size = MANDITORY - Relative definition - eg 'half', 'quarter', 'twothird
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
			'addclass' => '',
			'id' => '',
			'last' => '',
			'move' => '',
			'divoutput' => FALSE
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		//Blank or invalid param passed so clear it
		if (!$last || !$last == 'N') {
			$last = '';
		} else {
			//Put in space
			$last = ' last';
		}

		// Prepare extra CSS classes for display if supplied
		if ($addclass !='') {
		$class_clean = ' ' . wp_kses($addclass, '');
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

		$options = get_option('wonderflux_display');

		$wf_fullwidth = $options['container_w'];
		$wf_columns = $options['columns_num'];
		$wf_single = $options['columns_w'];

		// From simple numbers (already validated in options panel before saved to DB) we build our grid
		$wf_full = $css_core_def . $wf_columns;
		$wf_half = $css_core_def . ($wf_columns/2);
		$wf_third = $css_core_def . ($wf_columns/3);
		$wf_two_third = $css_core_def . ($wf_columns/3*2);
		$wf_quarter = $css_core_def . ($wf_columns/4);
		$wf_two_quarter = $wf_half;
		$wf_three_quarter = $css_core_def . ($wf_columns/4*3);
		$wf_fifth = $css_core_def . ($wf_columns/5);
		$wf_sixth = $css_core_def . ($wf_columns/6);
		$wf_eigth = $css_core_def . ($wf_columns/8);
		$wf_small = $css_core_def . ($wf_columns/8);

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

		/*
		// INACTIVE FOR MOMENT WHILE TESTING NEW CONFIG IN 0.881
		// NEED TO MAKE MORE DYNAMIC!
		$wf_full_space_l = 'span-'. ($wf_columns-1) . ' prepend-1';
		$wf_half_space_l = 'span-'. (($wf_columns/2)-1) . ' prepend-1';
		$wf_third_space_l = 'span-'. (($wf_columns/3)-1) . ' prepend-1';
		$wf_quarter_space_l = 'span-'. (($wf_columns/4)-1) . ' prepend-1';
		$wf_small_space_l = 'span-'. (($wf_columns/8)-1) . ' prepend-1';
		$wf_single_space_l = 'span-1 prepend-1 ';

		$wf_full_space_r = 'span-'. ($wf_columns-1) . ' append-1';
		$wf_half_space_r = 'span-'. (($wf_columns/2)-1) . ' append-1';
		$wf_third_space_r = 'span-'. (($wf_columns/3)-1) . ' append-1';
		$wf_quarter_space_r = 'span-'. (($wf_columns/4)-1) . ' append-1';
		$wf_small_space_r = 'span-'. (($wf_columns/8)-1) . ' append-1';
		$wf_single_space_r = 'span-1 append-1 ';

		$wf_full_space_lr = 'span-'. ($wf_columns-2) . ' prepend-1 append-1';
		$wf_half_space_lr = 'span-'. (($wf_columns/2)-2) . ' prepend-1 append-1';
		$wf_third_space_lr = 'span-'. (($wf_columns/3)-2) . ' prepend-1 append-1';
		$wf_quarter_space_lr = 'span-'. (($wf_columns/4)-2) . ' prepend-1 append-1';
		$wf_small_space_lr = 'span-'. (($wf_columns/8)-2) . ' prepend-1 append-1';
		//TOO SMALL! $wf_single_space_lr = ($wf_small-2) . ' prepend-1 append-1';
		 */

		switch ($size) {

			/*
			// INACTIVE FOR MOMENT WHILE TESTING NEW CONFIG IN 0.881
			case 'full-space-l' :
				$size_def = $wf_full_space_l;
			break;

			case 'half-space-l' :
				$size_def = $wf_half_space_l;
			break;

			case 'third-space-l' :
				$size_def = $wf_third_space_l;
			break;

			case 'quarter-space-l' :
				$size_def = $wf_quarter_space_l;
			break;

			case 'small-space-l' :
				$size_def = $wf_small_space_l;
			break;

			case 'single-space-l' :
				$size_def = $wf_single_space_l;
			break;

			case 'full-space-r' :
				$size_def = $wf_full_space_r;
			break;

			case 'half-space-r' :
				$size_def = $wf_half_space_r;
			break;

			case 'third-space-r' :
				$size_def = $wf_third_space_r;
			break;

			case 'quarter-space-r' :
				$size_def = $wf_quarter_space_r;
			break;

			case 'small-space-r' :
				$size_def = $wf_small_space_r;
			break;

			case 'single-space-r' :
				$size_def = $wf_single_space_r;
			break;

			case 'full-space-lr' :
				$size_def = $wf_full_space_lr;
			break;

			case 'half-space-lr' :
				$size_def = $wf_half_space_lr;
			break;

			case 'third-space-lr' :
				$size_def = $wf_third_space_lr;
			break;

			case 'quarter-space-lr' :
				$size_def = $wf_quarter_space_lr;
			break;

			case 'small-space-lr' :
				$size_def = $wf_small_space_lr;
			break;
			*/

			// Setup the core sizing CSS class
			case 'full' :
				$size_def = $wf_full;
				//Define $last param so it gets used automatically
				$last = ' last';
			break;

			case 'half' :
				$size_def = $wf_half;
			break;

			case 'third' :
				$size_def = $wf_third;
			break;

			case 'two_third' :
				$size_def = $wf_two_third;
			break;

			case 'quarter' :
				$size_def = $wf_quarter;
			break;

			case 'two_quarter' :
				$size_def = $wf_two_quarter;
			break;

			case 'three_quarter' :
				$size_def = $wf_three_quarter;
			break;

			case 'fifth' :
				$size_def = $wf_fifth;
			break;

			case 'sixth' :
				$size_def = $wf_sixth;
			break;

			case 'eigth' :
				$size_def = $wf_eigth;
			break;

			case 'single' :
				$size_def = $wf_small;
			break;


			default:
				$size_def = $wf_full;
			break;

		}

		// Setup the optional additional CSS class to move div around
		switch ($move) {

			case 'pull_half' :
				$move_def = $wf_pull_half;
			break;

			case 'pull_third' :
				$move_def = $wf_pull_third;
			break;

			case 'pull_quarter' :
				$move_def = $wf_pull_quarter;
			break;

			case 'pull_eighth' :
				$move_def = $wf_pull_eighth;
			break;

			case 'push_half' :
				$move_def = $wf_push_half;
			break;

			case 'push_third' :
				$move_def = $wf_push_third;
			break;

			case 'push_quarter' :
				$move_def = $wf_push_quarter;
			break;

			case 'push_eighth' :
				$move_def = $wf_push_eighth;
			break;

			default :
				$move_def = '';
			break;
		}

		//OK Lets build this CSS class!
		echo $divout_open_clean . 'class="' . $size_def . $move_def . $last . $class_clean . '"' . $id_clean . $divout_close_clean;

	}


	/**
	*
	* @since 0.4
	* @updated 0.881
	*
	* Builds HTML comment displaying current Wonderflux layout config
	*
	*/
	function wf_css_info($args) {

		$defaults = array (
			'display' => FALSE
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ($display == FALSE) {
			$start_format = '<!-- ';
			$end_format = ' -->';
		} else {
			$start_format = '<p>';
			$end_format = '</p>';
		}

		$options = get_option('wonderflux_display');

		$wf_fullwidth = $options['container_w'];
		$wf_columns = $options['columns_num'];
		$wf_single = $options['columns_w'];

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
	*
	* @since 0.71
	* @updated 0.902
	*
	* IMPORTANT - Close the head of the document after everything has run
	* IMPORTANT - Setup body class
	*
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
	*
	*/
	function wf_credit_footer() {

		// Defaults
		$footer_credit_format = 'p';
		$footer_credit_wp = 'Powered by <a href="http://wordpress.org" title="WordPress">WordPress</a>';
		$footer_credit_divider = ' | ';
		$footer_credit_wf = 'Tuned by <a href="http://wonderflux.com" title="Wonderflux WordPress theme framework">Wonderflux Framework</a>';
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
			echo '<div ';
			wflux_display::wf_css('full', 'last');
			echo ' id="wf-footer-credit">';
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


	/**
	*
	* @since 0.3
	* @updated 0.881
	*
	* Footer credit
	*
	*/
	function wf_debug_footer() {
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
	* @updated 0.881
	*
	* Footer code comment credit
	*
	*/
	function wf_credit_footer_code() {

		// Default
		$footer_credit = 'Powered by WordPress and the Wonderflux theme framework';
		// Allow filtering
		$footer_credit = apply_filters( 'wflux_comment_footer_credit', $footer_credit );

		$footer_credit_output = apply_filters( 'wflux_comment_footer_credit', $footer_credit );

		$output = '<!-- '.$footer_credit_output.' -->'."\n";
		echo $output;
	}

}
?>