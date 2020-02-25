<?php
/**
 * Setup and prepare Wonderflux options with fallbacks.
 *
 * @since	0.62
 * @version	2.6
 */
class wflux_data {

	//Size vars
	protected $wfx_db_display; // Array of core Wonderflux display options
	protected $wfx_grid_type; // Grid type
	protected $wfx_doc_type; // Document type
	protected $wfx_doc_lang; // Document language
	protected $wfx_doc_charset; // Document type
	protected $wfx_width_type; // Width unit of main site container (px or %)
	protected $wfx_width; // Width of main site container
	protected $wfx_position; // Position of main site container (works in conjunction with sidebar 1)
	protected $wfx_range_core; // Range of different size definitions for Flux Layout
	protected $wfx_columns; // Number of columns
	protected $wfx_gutter; // Number of columns
	protected $wfx_mquery_m; // Media query optimisation
	protected $wfx_columns_width; // Width of columns
	protected $wfx_sidebar_primary_position; // Primary sidebar position
	protected $wfx_page_templates; // Page templates to hide
	protected $wfx_rwd_full; // At what responsive breakpoint to collapse sidebar(s)/content to full width.
	protected $wfx_content_1_display; // Display of main content - EXPERIMENTAL, needs extra coding in core
	protected $wfx_content_1_size; // Relative 'size' of main content area eg 'three_quarter'
	protected $wfx_content_1_id; // CSS ID of main content container div
	protected $wfx_content_1_size_columns; // Size in columns - NOTE overrides _size variable if set

	protected $wfx_sidebar_1_display; // Display sidebar 1
	protected $wfx_sidebar_1_size; // Sidebar 1 size
	protected $wfx_sidebar_1_id; // CSS ID of sidebar 1 div

	protected $wfx_sidebar_2_display; // Display sidebar 2
	protected $wfx_sidebar_2_size; // Sidebar 2 size
	protected $wfx_sidebar_2_position; // Sidebar 2 position
	protected $wfx_content_2_id; // CSS ID of sidebar 2 div

	protected $wfx_content_size_px; // For core WordPress $content_width global

	protected $wfx_fb_admins; // Facebook admin owners, for meta data
	protected $wfx_fb_app; // Facebook app ID, for meta data

	function __construct() {

		// Main Wonderflux display array
		$this->wfx_db_display = get_option('wonderflux_display');

		//// DOCUMENT CONFIGURATION ////

		// GRID TYPE - 'percent' (Responsive - Flux Layout), 'pixels' (old Wonderflux v1 grid)
		$this->wfx_grid_type = ( isset($this->wfx_db_display['grid_type']) ) ? $this->wfx_db_display['grid_type'] : false;
		// Validate - first value is default
		$grid_type_accept = array('percent','pixels');
		$this->wfx_grid_type = ( !$this->wfx_grid_type || !in_array($this->wfx_grid_type,$grid_type_accept) ) ? $grid_type_accept[0] : $this->wfx_grid_type;

		// DOCTYPE - 'transitional','strict','frameset','1.1','1.1basic'
		$this->wfx_doc_type = (isset($this->wfx_db_display['doc_type']) ) ? $this->wfx_db_display['doc_type'] : false;
		// Validate
		$wfx_doc_type_out = 'transitional';
		$wfx_doc_type_accept = array('transitional','strict','frameset','1.1','1.1basic','html5','XHTML/RDFa');
		// Allow filtering
		$this->wfx_doc_type = apply_filters( 'wflux_doc_type', $this->wfx_doc_type );
		if ( in_array($this->wfx_doc_type,$wfx_doc_type_accept) ) { $wfx_doc_type_out = $this->wfx_doc_type; }
		$this->wfx_doc_type = $wfx_doc_type_out;

		// LANGUAGE CODE
		$this->wfx_doc_lang = (isset($this->wfx_db_display['doc_lang']) ) ? $this->wfx_db_display['doc_lang'] : false;
		$this->wfx_doc_lang = apply_filters( 'wflux_doc_lang', $this->wfx_doc_lang );
		// Validate
		$wfx_doc_lang_out = 'en';
		// Too many language codes to validate against - lets just check for length
		if ( $this->wfx_doc_lang != false ) {
			if (strlen(trim($this->wfx_doc_lang)) == 2 ) { $wfx_doc_lang_out = $this->wfx_doc_lang; }
		}
		$this->wfx_doc_lang = $wfx_doc_lang_out;

		// CHARACTER SET
		$this->wfx_doc_charset = (isset($this->wfx_db_display['doc_charset']) ) ? $this->wfx_db_display['doc_charset'] : false;
		// Validate
		$wfx_doc_charset_out = 'UTF-8';
		if ($this->wfx_doc_charset != false) {

			// Simple check - The W3C recommends the use of UTF-8 wherever possible
			// UTF-8 can be used for all languages and is the recommended charset on the Internet.
			if ($this->wfx_doc_charset == 'UTF-8') {
				$wfx_doc_charset_out = $this->wfx_doc_charset;
			} else {
				// Deeper check
				$pos_utf = strpos($this->wfx_doc_charset, 'UTF');
				if ($pos_utf === false) {
				    // Even deeper check
				    $pos_iso = strpos($this->wfx_doc_charset, 'ISO');
			    	if ($pos_iso === false) {
			    		// Silence is golden
			    	} else {
			    		$wfx_doc_charset_out = $this->wfx_doc_charset;
			    	}
				} else {
					$wfx_doc_charset_out = $this->wfx_doc_charset;
				}
			}
		}
		$this->wfx_doc_charset = $wfx_doc_charset_out;

		//// THIRD PARTY META

		//Facebook

		$this->wfx_fb_admins = ( isset($this->wfx_db_display['fb_admins'] ) ) ? wp_kses_data( $this->wfx_db_display['fb_admins'] ) : '';
		$this->wfx_fb_app = ( isset($this->wfx_db_display['fb_app'] ) ) ? wp_kses_data( $this->wfx_db_display['fb_app'] ) : '';

		//// COLUMNS CONFIGURATION ////

		// CONTAINER UNITS - 'pixels','percent'
		$this->wfx_width_unit = ( isset($this->wfx_db_display['container_u']) ) ? $this->wfx_db_display['container_u'] : false;
		// Validate - first value is default
		$grid_unit_accept = array('percent','pixels');
		$this->wfx_width_unit = ( !$this->wfx_width_unit || !in_array($this->wfx_width_unit,$grid_type_accept) ) ? $grid_unit_accept[0] : $this->wfx_width_unit;

		// CONTAINER SIZE - 400 to 2000
		$this->wfx_width = (isset($this->wfx_db_display['container_w']) ) ? $this->wfx_db_display['container_w'] : false;
		// Validate

		/**
		 * Backpat - define ranges for pixels/percent
		 */
		if ( $this->wfx_width_unit == 'pixels' ) {
			$wfx_width_out = 950;
			if (is_numeric ($this->wfx_width) ) { if ($this->wfx_width >= 400 && $this->wfx_width <= 2000) {$wfx_width_out = $this->wfx_width;} }
		} else {
			$wfx_width_out = 80;
			if (is_numeric ($this->wfx_width) ) { if ($this->wfx_width >= 5 && $this->wfx_width <= 100) {$wfx_width_out = $this->wfx_width;} }
		}
		$this->wfx_width = $wfx_width_out;

		// SITE CONTAINER POSITION - left, middle, right
		$this->wfx_position = (isset($this->wfx_db_display['container_p']) ) ? $this->wfx_db_display['container_p'] : false;
		// Validate
		$wfx_container_p_out = 'middle';
		$wfx_container_p_accept = array('left','middle','right');
		if ( in_array($this->wfx_position,$wfx_container_p_accept) ) { $wfx_container_p_out = $this->wfx_position; }
		$this->wfx_position = $wfx_container_p_out;

		// SIZE RANGE - Range of different size definitions
		$wfx_range_core_out = '1-2-4-5-8-10';
		$this->wfx_range_core = ( isset( $this->wfx_db_display['range_core'] ) ) ? $this->wfx_db_display['range_core'] : $wfx_range_core_out;
		// Allow filtering
		$this->wfx_range_core = apply_filters( 'wflux_range_core', $this->wfx_range_core );
		// Validate
		if ( !empty( $this->wfx_range_core ) && is_string( $this->wfx_range_core ) && strlen( $this->wfx_range_core ) < 20 &&( $this->wfx_range_core != $wfx_range_core_out ) ) {
			$wfx_range_core_out = preg_replace( '/\s+/', '', esc_attr( $this->wfx_range_core ) );
			$wfx_range_core_out = preg_replace( '/-[a-z]/i', '', $wfx_range_core_out );
		}
		$this->wfx_range_core = $wfx_range_core_out;

		// NUMBER OF COLUMNS - min 2, max 100
		$this->wfx_columns = 16;
		$this->wfx_columns = ( isset($this->wfx_db_display['columns_num']) ) ? $this->wfx_db_display['columns_num'] : false;
		$this->wfx_columns = apply_filters( 'wflux_columns_num', $this->wfx_columns );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() && has_filter('wflux_columns_num') ) {
			$this->wfx_columns = $this->wfx_db_display['columns_num'];
		}
		// Validate
		$this->wfx_columns = ( is_numeric ($this->wfx_columns) && ($this->wfx_columns >= 2 && $this->wfx_columns <= 100) ) ? $this->wfx_columns : 16;

		// GUTTER - min 1, max 25
		// TODO: Allow filtering!
		$wfx_gutter_out = 2;
		if ( isset($this->wfx_db_display['gutter']) && is_numeric ($this->wfx_db_display['gutter']) ) {
			if ($this->wfx_db_display['gutter'] >= 1 && $this->wfx_db_display['gutter'] <= 25) {$wfx_gutter_out = $this->wfx_db_display['gutter'];}
		}
		$this->wfx_gutter = $wfx_gutter_out;

		// MEDIA QUERY OPTIMISATION - y, n
		$this->wfx_mquery_m = (isset($this->wfx_db_display['mquery_m']) ) ? $this->wfx_db_display['mquery_m'] : false;
		// Validate
		$wfx_mquery_m_out = 'y';
		$wfx_mquery_m_accept = array('y','n');
		if ( in_array($this->wfx_mquery_m,$wfx_mquery_m_accept) ) { $wfx_mquery_m_out = $this->wfx_mquery_m; }
		$this->wfx_mquery_m = $wfx_mquery_m_out;

		// COLUMN WIDTH - min 10, max 1000
		$this->wfx_columns_width = (isset($this->wfx_db_display['columns_w']) ) ? $this->wfx_db_display['columns_w'] : false;
		// Validate
		$wfx_columns_width_out = 30;
		if (is_numeric ($this->wfx_columns_width) ) { if ($this->wfx_columns_width >= 10 && $this->wfx_columns_width <= 1000) {$wfx_columns_width_out = $this->wfx_columns_width;} }
		$this->wfx_columns_width = $wfx_columns_width_out;

		// SIDEBAR PRIMARY POSITION - left, right
		$this->wfx_sidebar_primary_position = (isset($this->wfx_db_display['sidebar_p']) ) ? $this->wfx_db_display['sidebar_p'] : false;
		// Validate
		$wfx_sidebar_pp_out = 'left';
		$wfx_sidebar_pp_accept = array('left','right');
		if ( in_array($this->wfx_sidebar_primary_position,$wfx_sidebar_pp_accept) ) { $wfx_sidebar_pp_out = $this->wfx_sidebar_primary_position; }
		$this->wfx_sidebar_primary_position = $wfx_sidebar_pp_out;

		// RESPONSIVE WRAPPER CLASSES - Set media query definition to add extra Flux Layout CSS classes to make sidebar(s)/content full width - tiny/small/medium/large
		$this->wfx_rwd_full = ( isset($this->wfx_db_display['rwd_full']) ) ? $this->wfx_db_display['rwd_full'] : false;
		// Validate - second value is default
		$rwd_full_width_accept = array( 'tiny', 'small', 'medium', 'large' );
		$this->wfx_rwd_full = ( !$this->wfx_rwd_full || !in_array($this->wfx_rwd_full, $rwd_full_width_accept) ) ? $rwd_full_width_accept[1] : $this->wfx_rwd_full;

		// PAGE TEMPLATES - saved options hide templates
		$this->wfx_page_templates = (isset($this->wfx_db_display['page_t']) ) ? $this->wfx_db_display['page_t'] : false;
		// Validate - first value is default
		$page_t_accept = array('', 'no-sidebar');
		$this->wfx_page_templates = ( !$this->wfx_page_templates || !in_array($this->wfx_page_templates,$page_t_accept) ) ? $page_t_accept[0] : $this->wfx_page_templates;

		//// CONTAINERS CONFIGURATION ////

		// CONTENT 1 DISPLAY - Experimental! Need extra functionality to remove content
		$this->wfx_content_1_display = 'Y';
		$this->wfx_content_1_display = apply_filters( 'wflux_content_1_display', $this->wfx_content_1_display );
		//if ( !has_filter('wflux_content_1_display') ) { $this->wfx_content_1_display = $this->wfx_db_display['content_d']; // DB ACTION!! }
		$this->wfx_content_1_display = ( $this->wfx_content_1_display == 'Y' ) ? 'Y' : 'N';

		// CONTENT 1 SIZE
		$this->wfx_content_1_size = (isset($this->wfx_db_display['content_s']) ) ? $this->wfx_db_display['content_s'] : false;
		$this->wfx_content_1_size = apply_filters( 'wflux_content_1_size', $this->wfx_content_1_size );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() ) {
			if (has_filter('wflux_content_1_size') ) {
				$this->wfx_content_1_size = $this->wfx_db_display['content_s'];
			}
		} elseif ( $this->wfx_content_1_size == false ) {
			$this->wfx_content_1_size = 'three_quarter';
		}

		// CONTENT SIZE (PIXELS) - 200 to 2000 IMPORTANT USED BY WORDPRESS $content_width GLOBAL
		$this->wfx_content_size_px = (isset($this->wfx_db_display['content_s_px']) ) ? $this->wfx_db_display['content_s_px'] : false;
		$this->wfx_content_size_px = apply_filters( 'wflux_content_embed_width', $this->wfx_content_size_px );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() ) {
			if (has_filter('wflux_content_embed_width') ) {
				$this->wfx_content_size_px = $this->wfx_db_display['content_s_px'];
			}
		} elseif ( $this->wfx_content_size_px == false ) {
			$this->wfx_content_size_px = 600;
		}

		// CONTENT 1 CSS ID
		$this->wfx_content_1_id = 'content';
		$this->wfx_content_1_id = apply_filters( 'wflux_content_1_id', $this->wfx_content_1_id );
		//if ( !has_filter('wflux_content_1_id') ) { $this->wfx_content_1_id = $this->wfx_db_display['content_i']; // DB ACTION!! }
		$this->wfx_content_1_id = wp_kses_data($this->wfx_content_1_id, '');

		// CONTENT 1 COLUMNS
		$this->wfx_content_1_size_columns = 0;
		$this->wfx_content_1_size_columns = apply_filters( 'wflux_content_1_size_columns', $this->wfx_content_1_size_columns );
		//if ( !has_filter('wflux_content_1_size_columns') ) { $this->wfx_content_1_size_columns = $this->wfx_db_display['sidebar_i']; // DB ACTION!! }
		$this->wfx_content_1_size_columns = ( is_numeric($this->wfx_content_1_size_columns) ) ? $this->wfx_content_1_size_columns : 0;

		// SIDEBAR 1 DISPLAY
		$this->wfx_sidebar_1_display = (isset($this->wfx_db_display['sidebar_d']) ) ? $this->wfx_db_display['sidebar_d'] : false;
		$this->wfx_sidebar_1_display = apply_filters( 'wflux_sidebar_1_display', $this->wfx_sidebar_1_display );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() ) {
			if ( has_filter('wflux_sidebar_1_display') ) {
				$this->wfx_sidebar_1_display = $this->wfx_db_display['sidebar_d'];
			}
		} elseif ( $this->wfx_sidebar_1_display == false ) {
			$this->wfx_sidebar_1_display = 'Y';
		}

		// SIDEBAR 1 SIZE
		$this->wfx_sidebar_1_size = (isset($this->wfx_db_display['sidebar_s']) ) ? $this->wfx_db_display['sidebar_s'] : false;
		$this->wfx_sidebar_1_size = apply_filters( 'wflux_sidebar_1_size', $this->wfx_sidebar_1_size );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() ) {
			if (has_filter('wflux_sidebar_1_size') ) {
				$this->wfx_sidebar_1_size = $this->wfx_db_display['sidebar_s'];
			}
		} elseif ( $this->wfx_sidebar_1_size == false ) {
			$this->wfx_sidebar_1_size = 'quarter';
		}

		// SIDEBAR 1 CSS ID
		$this->wfx_sidebar_1_id = 'sidebar';
		$this->wfx_sidebar_1_id = apply_filters( 'wflux_sidebar_1_id', $this->wfx_sidebar_1_id );
		//if ( !has_filter('wflux_sidebar_1_id') ) { $this->wfx_sidebar_1_id = $this->wfx_db_display['sidebar_i']; // DB ACTION!! }
		$this->wfx_sidebar_1_id = ( $this->wfx_sidebar_1_id !='sidebar' ) ? esc_attr( $this->wfx_sidebar_1_id ) : $this->wfx_sidebar_1_id;

		// SIDEBAR 1 COLUMNS
		$this->wfx_sidebar_1_size_columns = 0;
		$this->wfx_sidebar_1_size_columns = apply_filters( 'wflux_sidebar_1_size_columns', $this->wfx_sidebar_1_size_columns );
		//if ( !has_filter('wflux_sidebar_1_size_columns') ) { $this->wfx_sidebar_1_size_columns = $this->wfx_db_display['sidebar_c']; // DB ACTION!! }
		$this->wfx_sidebar_1_size_columns = ( is_numeric($this->wfx_sidebar_1_size_columns) ) ? $this->wfx_sidebar_1_size_columns : 0;

		// SIDEBAR 2 DISPLAY
		// TODO: Check defaults etc!
		$this->wfx_sidebar_2_display = (isset($this->wfx_db_display['sidebar_2_d']) ) ? $this->wfx_db_display['sidebar_2_d'] : false;
		$this->wfx_sidebar_2_display = apply_filters( 'wflux_sidebar_2_display', $this->wfx_sidebar_2_display );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() ) {
			if ( has_filter('wflux_sidebar_2_display') ) {
				$this->wfx_sidebar_2_display = $this->wfx_db_display['sidebar_2_d'];
			}
		} elseif ( $this->wfx_sidebar_2_display == false ) {
			$this->wfx_sidebar_2_display = 'N';
		}

		// SIDEBAR 2 SIZE
		$this->wfx_sidebar_2_size = (isset($this->wfx_db_display['sidebar_2_s']) ) ? $this->wfx_db_display['sidebar_2_s'] : false;
		$this->wfx_sidebar_2_size = apply_filters( 'wflux_sidebar_2_size', $this->wfx_sidebar_2_size );
		// If filtered and in admin, just show original value saved to DB, not filtered values
		if ( is_admin() ) {
			if (has_filter('wflux_sidebar_2_size') ) {
				$this->wfx_sidebar_2_size = $this->wfx_db_display['sidebar_2_s'];
			}
		} elseif ( $this->wfx_sidebar_2_size == false ) {
			$this->wfx_sidebar_2_size = 'quarter';
		}

		// SIDEBAR SECONDARY POSITION - left, right
		$this->wfx_sidebar_2_position = (isset($this->wfx_db_display['sidebar_2_p']) ) ? $this->wfx_db_display['sidebar_2_p'] : false;
		// Validate
		$wfx_sidebar_pp_2_out = 'left';
		$wwfx_sidebar_pp_2_accept = array('left','right');
		if ( in_array($this->wfx_sidebar_2_position,$wwfx_sidebar_pp_2_accept) ) { $wfx_sidebar_pp_2_out = $this->wfx_sidebar_2_position; }
		$this->wfx_sidebar_2_position = $wfx_sidebar_pp_2_out;

		// SIDEBAR 2 CSS ID
		$this->wfx_sidebar_2_id = 'sidebar-secondary';
		$this->wfx_sidebar_2_id = apply_filters( 'wflux_sidebar_2_id', $this->wfx_sidebar_2_id );
		$this->wfx_sidebar_2_id = ( $this->wfx_sidebar_2_id !='sidebar-secondary' ) ? esc_attr( $this->wfx_sidebar_2_id ) : $this->wfx_sidebar_2_id;

		//// THEME INFORMATION ////

		$this->wfx_mytheme_version = wp_get_theme()->version;
		$this->wfx_mytheme_version_clean = ( is_numeric($this->wfx_mytheme_version) ) ? $this->wfx_mytheme_version : urlencode( sanitize_title_with_dashes($this->wfx_mytheme_version) );

	}

}


/**
 * Core Wonderflux helper functions.
 * Used internally by Wonderflux and can be used by theme developers in child themes.
 *
 * @since	0.62
 * @version	2.1
 */
class wflux_helper {

	protected $wfx_is_small_screen; // Basic non-desktop detection

	function __construct() {

		$this->wfx_is_small_screen = wp_is_mobile();

	}

	/**
	 * Creates array of information about file based on filename.
	 * IMPORTANT - Used internally by Wonderflux.
	 *
	 * Filters available:
	 * wflux_ext_img - array of image file extensions
	 *
	 * @since	1.1
	 * @version	1.1
	 *
	 * @param	[string] $filename		REQUIRED File name with extension (no path)
	 * @return	[array]					ext,type,nicetype,playable
	 */
	function wf_info_file( $filename='' ) {

		if ( empty($filename) ) return false;

		$info = wp_check_filetype($filename);
		$file_ext = ( !empty($info) ) ? explode( '.', $filename ) : '';
		$file_nice = wp_ext2type( array_pop( $file_ext ) );
		// wp_ext2type doesn't have functionality to detect image type file (WordPress 3.6)
		// See http://core.trac.wordpress.org/ticket/25261 for patch submitted to address this - may get fixed?!
		// TODO: Check if patch accepted and incorporated in future release of WordPress
		$img_types = apply_filters( 'wflux_ext_img', array('jpg','jpeg','jpe','gif','png','bmp','tif','tiff','ico') );
		$file_nice = ( empty($file_nice) && in_array($info['ext'], $img_types ) ) ? 'image' : $file_nice;

		$info['nicetype'] = $file_nice;
		$info['playable'] = ( in_array( $file_nice,array('video','audio') ) ) ? 'Y' : 'N';
		return $info;
	}

	/**
	 * Detects what type of content you are currently viewing.
	 * IMPORTANT - Used internally by Wonderflux.
	 *
	 * @since	0.881
	 * @version	1.0RC3
	 *
 	 * @param	none
	 * @return	[string]				Current view - eg 'category'
	 */
	function wf_info_location() {

		switch (TRUE) {
			case is_home() || is_front_page() : $out = 'home'; break;
			case is_category() : $out = 'category'; break;
			case is_tag() : $out = 'tag'; break;
			case is_search() : $out = 'search'; break;
			case is_date() : $out = 'date'; break;
			case is_author() : $out = 'author'; break;
			case is_tax() : $out = 'taxonomy'; break;
			case is_archive() : $out = 'archive'; break;
			case is_attachment() : $out = 'attachment'; break;
			case is_single() : $out = 'single'; break;
			case is_page() : $out = 'page'; break;
			case is_404() : $out = '404'; break;
			default : $out = 'index'; break;
		}

		return $out;
	}


	/**
	 * Detects if you are viewing single content - post, page, attachment, author
	 * as opposed to archive or any other type of views, kinda like is_singular() and is_single()
	 *
	 * @since	1.0
	 * @version	1.1
	 *
 	 * @param	none
	 * @return	[bool]					true/false
	 */
	function wf_info_single() {
		switch ( $this->wf_info_location() ) {
			case 'single': $out = true; break;
			case 'page': $out = true; break;
			case 'attachment': $out = true; break;
			case 'author': $out = true; break;
			default : $out = false; break;
		}
		return $out;
	}


	/**
	 * Turbo-charged get_template_part file include - loads a template part into a template.
	 * IMPORTANT: Used by Wonderflux internally to setup smarter specific template parts.
	 *
	 * Appends various location information and uses those files if available in your theme folder.
	 *
	 * Can also use mobile optimised screen alternative template parts for non-desktop devices (like phones or tablets)
	 * by creating an additional file with '-mobile' appended, like: loop-content-single-mobile.php
	 *
	 * EXAMPLES
	 * All examples are with $part='loop-content' and shows the order of priority of files
	 *
	 * SINGLE POST (INCLUDING CUSTOM POST TYPES)
	 * NOTE: Normal 'post' post type uses loop-content-single.php NOT loop-content-single-post.php
	 * 1 loop-content-single-{POST-TYPE-SLUG}.php
	 * 2 loop-content-single.php
	 * 3 loop-content.php
	 *
	 * CATEGORY ARCHIVE
	 * 1 loop-content-category-{CATEGORY-SLUG}.php
	 * 2 loop-content-category.php
	 * 3 loop-content-archive.php (common archive template)
	 * 4 loop-content.php
	 *
	 * TAXONOMY ARCHIVE
	 * 1 loop-content-taxonomy-{taxonomy-name}-{taxonomy-term}.php
	 * 2 loop-content-taxonomy-{taxonomy-name}.php
	 * 3 loop-content-taxonomy.php
	 * 4 loop-content-archive.php (common archive template)
	 * 5 loop-content.php
	 *
	 * TAG ARCHIVE
	 * 1 loop-content-tag-{tag-slug}.php
	 * 2 loop-content-tag.php
	 * 3 loop-content-archive.php (common archive template)
	 * 4 loop-content.php
	 *
	 * DATE ARCHIVE
	 * 1 loop-content-date-{YEAR}-{MONTH}.php (4 digit year, 2 digit month with leading zero if less than 10)
	 * 2 loop-content-date-{YEAR}.php (4 digit year)
	 * 3 loop-content-date.php
	 * 4 loop-content-archive.php (common archive template)
	 * 5 loop-content.php
	 *
	 * POST ARCHIVE (especially useful for custom post type archives!)
	 * 1 loop-content-archive-{post-type-slug}.php
	 * 2 loop-content-archive.php (common archive template)
	 * 3 loop-content.php
	 *
	 * AUTHOR TODO: Do username template drill
	 * 1 loop-content-author.php
	 * 2 loop-content.php
	 *
	 * HOMEPAGE
	 * 1 loop-content-home.php
	 * 2 loop-content.php
	 *
	 * SEARCH
	 * 1 loop-content-search.php
	 * 2 loop-content-archive.php (common archive template)
	 * 3 loop-content.php
	 *
	 * ATTACHMENT TODO: Basic range of filetypes support
	 * 1 loop-content-attachment.php
	 * 2 loop-content.php
	 *
	 * PAGE
	 * 1 loop-content-page.php
	 * 2 loop-content.php
	 *
	 * 404 ERROR PAGE
	 * 1 loop-content-404.php
	 * 2 loop-content.php
	 *
 	 * @filter wflux_template_part_main - string containing first part of filename to get (slug),
 	 * eg 'loop-content' (filename example loop-content-archive.php)
 	 * @filter wflux_template_part_fragment - string containing second part of filename to get (name)
 	 * eg 'archive' (filename example loop-content-archive.php)
 	 * @filter wflux_template_part_array - array of values used to build filename to get (tip - most useful for advanced filtering!),
 	 * eg array(0 => 'loop-content', 1 => 'archive') (filename example loop-content-archive.php)
	 *
	 * @since	0.881
	 * @version	2.6
	 *
	 * @param	[string] $part 			REQUIRED The slug name for the generic template
	 *
	 * @todo 							Extend the simple WP core $is_mobile detection
	 */
	function wf_get_template_part( $args ) {

		$defaults = array (
			'part' => false
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if ( !$part ) return;
		$this_location = $this->wf_info_location('');
		$part_get = false;

		switch( $this_location ) {

			// Single post/custom post type
			case ( 'single' ):

				$slug = get_query_var( 'post_type' );
				$slug_depth_1 = ( isset($slug) ) ? $this_location . '-' . $slug : false;

				if ( $this->wfx_is_small_screen == true ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '-mobile.php', false ) !='' ):
						$part_get = $slug_depth_1 . '-mobile';
					endif;
				}

				if ( empty( $part_get ) ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '.php', false ) !='' ):
						$part_get = $slug_depth_1;
					endif;
				}

			break;

			// Category archive
			case ( 'category' ):

				$slug = get_category( get_query_var( 'cat' ) )->slug;
				$slug_depth_1 = ( isset( $slug ) ) ? $this_location . '-' . $slug : false;

				if ( $this->wfx_is_small_screen == true ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '-mobile.php', false ) !='' ):
						$part_get = $slug_depth_1 . '-mobile';
					endif;
				}

				if ( empty( $part_get ) ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '.php', false ) !='' ):
						$part_get = $slug_depth_1;
					endif;
				}

			break;

			// Tag archive
			case ( 'tag' ):

				$slug = get_query_var( 'tag' );
				$slug_depth_1 = ( isset( $slug ) ) ? $this_location . '-' . $slug : false;

				if ( $this->wfx_is_small_screen == true ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '-mobile.php', false ) !='' ):
						$part_get = $slug_depth_1 . '-mobile';
					endif;
				}

				if ( empty($part_get) ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '.php', false ) !='' ):
						$part_get = $slug_depth_1;
					endif;
				}

			break;

			// Taxonomy archive
			case ('taxonomy'):

				//NOTE: No get_query_var / $wp_query in taxonomy archive view - not populated
				$this_q = get_queried_object();
				$slug_depth_1 = ( isset( $this_q->taxonomy ) ) ? $this_location . '-' . $this_q->taxonomy : false;
				$slug_depth_2 = ( isset( $this_q->slug ) ) ? $this_location . '-' . $this_q->taxonomy . '-' . $this_q->slug : false;

				if ( $this->wfx_is_small_screen == true ) {
					if ( locate_template( $part . '-' . $slug_depth_2 . '-mobile.php', false ) !='' ):
						$part_get = $slug_depth_2 . '-mobile';
					elseif ( locate_template( $part . '-' . $slug_depth_1 . '-mobile.php', false ) !='' ):
						$part_get = $slug_depth_1 . '-mobile';
					endif;
				}

				if ( empty($part_get) ) {
					if ( locate_template( $part . '-' . $slug_depth_2 . '.php', false ) !='' ):
						$part_get = $slug_depth_2;
					elseif ( locate_template( $part . '-' . $slug_depth_1 . '.php', false ) !='' ):
						$part_get = $slug_depth_1;
					endif;
				}

			break;

			// Date archive
			case ('date'):

				$month = get_query_var( 'monthnum' );
				$year = get_query_var( 'year' );
				$slug_1 = ( !empty( $year ) ) ? '-' . $year : false;
				$slug_2 = ( !empty( $month ) ) ? ( $month < 10 ) ? sprintf( '-%02d', $month ) : '-' . $month : false;

				if ( $this->wfx_is_small_screen == true ) {
					if ( locate_template( $part . '-' . $this_location . $slug_1 . $slug_2 . '-mobile.php', false ) !='' ):
						$part_get = $this_location . $slug_1 . $slug_2 . '-mobile';
					elseif ( locate_template( $part . '-' . $this_location . $slug_1 . '-mobile.php', false ) !='' ):
						$part_get = $this_location . $slug_1 . '-mobile';
					endif;
				}

				if ( empty($part_get) ) {
					if ( locate_template( $part . '-' . $this_location . $slug_1 . $slug_2 . '.php', false) !='' ):
						$part_get = $this_location . $slug_1 . $slug_2;
					elseif ( locate_template($part . '-' . $this_location . $slug_1 . '.php', false) !='' ):
						$part_get = $this_location . $slug_1;
					endif;
				}

			break;

			// Archive/custom post type archive
			case ( 'archive' ):

				$slug = get_query_var( 'post_type' );
				$slug_depth_1 = ( isset( $slug ) ) ? $this_location . '-' . $slug : false;

				if ( $this->wfx_is_small_screen == true ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '-mobile.php', false ) !='' ):
						$part_get = $slug_depth_1 . '-mobile';
					endif;
				}

				if ( empty( $part_get ) ) {
					if ( locate_template( $part . '-' . $slug_depth_1 . '.php', false ) !='' ):
						$part_get = $slug_depth_1;
					endif;
				}

			break;

			default:

				$part_get = $this_location;

			break;

		}

		// Use general [PART]-archive.php template part if we need to
		$archive_views = array(
			'category',
			'tag',
			'taxonomy',
			'date',
			'archive',
			'search'
		);

		if ( in_array( $this_location, $archive_views ) ) {

			if ( $this->wfx_is_small_screen == true ) {

				$part_get = ( locate_template( $part . '-' . $this_location . '-mobile.php', false ) !='' ) ? $this_location . '-mobile' : $part_get;

			}

			if ( empty( $part_get ) || $part_get == 'search' ) {

				$part_get = ( locate_template( $part . '-' . $this_location . '.php', false ) !='' ) ? $this_location : 'archive';

			}

		}

		// Covers all other eventualities
		$part_get = ( empty( $part_get ) ) ? $this_location : $part_get;

		// Allow filtering so theme files can be re-purposed
		$part_get_main = apply_filters( 'wflux_template_part_main', $part );
		$part_get_fragment = apply_filters( 'wflux_template_part_fragment', $part_get );
		$part_get_array = apply_filters( 'wflux_template_part_array', array( $part_get_main, $part_get_fragment ) );

		// Now scoot off and get out template part!
		get_template_part( sanitize_html_class( $part_get_array[0] ), sanitize_html_class( $part_get_array[1] ) );

	}


	/**
	 * Gets user role of logged-in user.
	 * IMPORTANT - Used internally by Wonderflux.
	 *
	 * BACKPAT: When using WordPress 4.5 or above wp_get_current_user()
	 * is used instead of get_currentuserinfo() (function deprecated)
	 *
	 * @since	0.62
	 * @version	2.2
	 *
	 * @param	[string] $echo 			Do you want to echo instead of return? Y/N [N]
	 * @return	[string]				Current user role, eg 'administrator' or false
	 */
	function wf_user_role() {

		if ( is_user_logged_in() ) {

			global $current_user;

			// BACKPAT: get_currentuserinfo() is deprecated in version 4.5
			if ( WF_WORDPRESS_VERSION < 4.5 ) {
				get_currentuserinfo();
			} else {
				wp_get_current_user();
			}

			$theuser = new WP_User( $current_user->ID );

			if ( !empty( $theuser->roles ) && is_array( $theuser->roles ) ) {
				foreach ( $theuser->roles as $role )
				$theuserrole = $role;
				return $theuserrole;
			}

		} else {

			return false;

		}

	}


	/**
	 * Gets current page 'depth' when using parent/child/grandchild etc page structure.
	 *
	 * @since	0.86
	 * @version	0.92
	 *
	 * @param	[int] $start 			Where you would like to start the depth countr from [0]
	 * @param	[string] $show_all 		Return root level on homepage and search - Y/N [N]
	 * @return	[int]					Integer representing depth of page
	 */
	function wf_page_depth($args) {

		$defaults = array (
			'start' => 0,
			'show_all' => 'N'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$depth = ($start == 0) ? 0 : 1;
		$show_all = ($show_all == 'N') ? 'N' : 'Y';

		// Stops errors when this is run in invalid location
		if (is_page() && (!is_home() || !is_front_page()) ) {

			global $wp_query;
			$object = $wp_query->get_queried_object();
			$parent_id  = $object->post_parent;

			while ($parent_id > 0) {
				$page = get_page($parent_id);
				$parent_id = $page->post_parent;
				$depth++;
			}

			return $depth;

		} elseif ( $show_all == 'Y' ) {
			if ( is_home() || is_front_page() || is_search() ) { return $depth; }
		}

	}


	/**
	 * Get a custom field value for the main queried post.
	 * Can be used inside or outside the loop.
	 *
	 * @since	0.92
	 * @version	2.1
	 *
	 * @param	[string] $name 			REQUIRED The key name of the custom field
	 * @param	[string] $empty 		If there is no value in custom field, do you want an alternative value? [false]
	 * @param	[string] $escape 		Do you want the output HTML escaped? - Y/N [N]
	 * @param	[string] $format 		What type of data is it, do you want to change this date format? - string/date [string]
	 * @param	[string] $date_style 	PHP date format style [l F j, Y]
	 * @param	[string] $error 		Do you want something returned on search and 404? - Y/N [N]
	 * @param	[string] $trim 			Trim white space characters from start and end of custom field value - Y/N [Y]
	 * @param	[int] $id 				Function usually returns main loop custom field, setting $id forces function to get custom field from specific post ID [false]
	 * @return	[mixed]					Custom field value
	 */
	function wf_custom_field($args) {

		$defaults = array (
			'name' => '',
			'empty' => '',
			'escape' => 'N',
			'format' => 'string',
			'date_style' => 'l F j, Y',
			'error' => 'N',
			'trim' => 'Y',
			'id' => false
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$empty_clean = wp_kses_post( $empty, '' );

		// Detect and optionally return useful value if no chance of custom field being here
		if ( is_admin() ) {
			// Silence is golden
		} elseif ( is_404() && $error == 'N' ) {
			return $empty_clean;
		} elseif ( is_search() && $error == 'N' ) {
			return $empty_clean;
		} else {

			// We have something to query!
			wp_reset_query();

			if ( empty($id) ) {
				global $wp_query;
				$this_id = isset( $wp_query->post->ID ) ? $wp_query->post->ID : false;
			} else {
				$this_id = intval( $id );
			}

			$name_clean = wp_kses_data( $name, '' );

			$value = get_post_meta($this_id, $name_clean, true);
			$output = ( $value != '' ) ? $value : $empty_clean;

			if ( $format == 'date' ) { $output = date( $date_style, $output ); };
			if ( $trim == 'Y' ) { $output = trim( $output ); }
			if ( $escape == 'Y' ) { return esc_attr( $output ); } else { return $output; }
		}

	}


	/**
	 * Returns 'Y' - nothing more, nothing less!
	 * Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__Y' ) in your child theme, saves creating a function
	 *
	 * @since	0.93
	 * @version	0.93
	 *
	 * @param	none
	 * @return	[string]				Y
	 */
	function wf__Y() { return 'Y'; }


	/**
	 * Returns 'N' - nothing more, nothing less!
	 * Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__N' ) in your child theme, saves creating a function
	 *
	 * @since	0.93
	 * @version	0.93
 	 *
	 * @param	none
	 * @return	[string]				N
	 */
	function wf__N() { return 'N'; }


	/**
	 * Displays input (or WordPress query information) in a nicer, more useful way for debugging.
	 * Only displays for logged-in administrator level users by default.
	 * Contains other powerful query debugging functions.
	 *
	 * $input can be set as the following for powerful WordPress debugging:
	 * wp_query - WordPress $wp_query
	 * wp_posts - WordPress $posts
	 * wp_queried - Current queried object
	 * wp_all_taxonomies - All Taxonomies
	 *
	 * @since	1.1
	 * @version	2.6
	 *
	 * @param	[mixed] $input 			REQUIRED What you want to debug!
	 * @param	[string] $label 		Add a title to top of output to help identify it if using multiple debugs.
	 * @param	[bool] $admin_only 		Only display to logged-in administrator level users, not other users. true/false [true]
	 * @param	[string] $show_all 		Only display to supplied WordPress role. true/false [false]
	 * @param	[int] $id 				Only display to supplied user ID. [false]
	 */
	function wf_debug( $input='', $label='', $admin_only=true, $role=false, $id=false ) {

		// Check against top level admin
		if ( $admin_only && !is_super_admin() ) return;
		// Check against user role
		if ( $role && !current_user_can( $role ) ) return;
		// Check against user ID
		if ( is_integer( $id ) && get_current_user_id() != $id ) return;

		if ( WF_PRO_MODE === false ){

			$empty_msg = array(
				'Hope that was deliberate',
				'You type I\'ll drive',
				'It may look like nothing; but...',
				'Oh really, it\'s nothing',
				'Oooops!',
				'Meh',
				';()',
				'=(',
				':(',
				'It\'s only code',
				'Think of the kittens',
				'Sorry captain',
				'I\'m good, but not that good',
				'I\'ll do better next time',
				'Debug time',
				'Maybe next time',
				'Trouble at mill',
				'Drink more water',
				'Meh, could be worse',
				'Have a snack!',
				'You\'ll get it, I didn\'t!',
				'Gremlins Tip: Don\'t feed after midnight',
				'Gremlins Tip: Don\'t get wet',
				'Gremlins Tip: Don\'t expose to sunlight'
			);

		} else {

			$empty_msg = 'NONE';

		}

		$empty_msg = ( is_array( $empty_msg ) ) ? 'NONE (' . $empty_msg[ array_rand( $empty_msg ) ] . ')' : $empty_msg;

		$pre_class = ' class="flush-top flush-bottom"';

		$file = false;
	    $backtrace =  debug_backtrace();
	    $include_functions = array( 'include', 'include_once', 'require', 'require_once' );

		// File
		for ( $index = 0; $index < count( $backtrace ); $index++ ) {

	        $function = $backtrace[ $index ][ 'function' ];

	        if ( in_array( $function, $include_functions ) ) {
	            $file = $backtrace[ $index ][ 'file' ];
	            break;
	        }

	    }

		// Line number
		foreach ( $backtrace as $k => $v ) {

			if ( empty( $caller ) && isset( $v[ 'function' ] ) && $v[ 'function' ] == 'wfx_debug' ) {
				$caller = $v[ 'file' ];
				$caller_line = $v[ 'line' ];
			}

		}

		$o = '<div style="color:#000; padding:0.3em 0.25% 0.4% 0.25%; overflow:auto; border: 5px inset #EA4444; background-color: #f7caca; box-shadow: inset 0px 0px 5px 0px rgba(255,0,0,0.8);" class="wfx_debug_output meta">';

		$o .= '<p style="color:#C73A3A; font-weight: bold; margin-bottom:0; font-size:0.9em;">&#10094; ' . esc_html( $caller_line ) . ' &#10095; ' . esc_html( basename($caller) ) . '</p>';

		$o .= ( !empty( $label ) ) ? '<pre style="margin-top:0; padding-bottom:0.5em; margin-bottom:0.4em; border-bottom: 1px solid #C73A3A;"><span style="color:#C73A3A; font-weight: bold;">' . esc_html( $label ) . ' </span>'/*'</pre>'*/ : '<pre style="margin-bottom:0.5em;">';

		if ( empty( $input ) ) {

			$input_type = '<span style="color:#C73A3A; font-weight: bold;">' . esc_html( $empty_msg ) . '</span>';

		} else {

			if ( $input === 'wp_query' ) {

				$input_type = 'WordPress core $wp_query';
				global $wp_query;
				$input = $wp_query;

			} elseif ( $input === 'wp_posts' ) {

				$input_type = 'WordPress core $posts';
				global $posts;
				$input = $posts;

			} elseif ( $input === 'wp_queried' ) {

				$input_type = 'Currently queried object';
				$input = get_queried_object();

			} elseif ( $input === 'wp_all_taxonomies' ) {

				$input_type = 'All taxonomies';
				global $wp_taxonomies;
				$input = $wp_taxonomies;

			} else {

				$input_type = gettype( $input );

			}

		}

		$o .= /*'<pre' . $pre_class . '>'<strong>' . */esc_html__( 'Data type:', 'wonderflux' ) . '<strong> ' . $input_type . '</strong> '. '</pre>';

		if ( is_bool( $input ) ){

			$o .= '<pre' . $pre_class . '>';
			$o .= ( $input === true ) ? 'true' : 'false';
			$o .= '</pre>';

		} elseif ( is_numeric( $input ) && $input === 0 ) {

			$o .= '<pre' . $pre_class . '>';
			$o .= '0 ' . esc_html__('(zero)', 'wonderflux');
			$o .= '</pre>';

		} elseif ( !empty( $input ) ){

			if ( is_array( $input ) || is_object( $input ) ) {

	   			$o .= '<pre' . $pre_class . '>' . esc_html( print_r( $input, true ) ) . '</pre>';

			} else {

				$o .= '<p style="color:#770000; margin-bottom:0;">' . $input . '</p>';

			}

		} else {

			$o .= '<pre' . $pre_class . '>' . esc_html__( 'No data returned or null', 'wonderflux' ) . '</pre>';

		}

		$o .= '</div>';

		echo "\n";
		echo '<!-- ------------ -->';
		echo "\n";
		echo '<!-- ---------------- -->';
		echo "\n";
		echo '<!-- -------------------- -->';
		echo "\n";
		echo '<!-- -- --- ---- ' . esc_html( $label ) . ' DEBUG START ---- --- -- -->';
		echo "\n\n$o\n\n";
		echo '<!-- -- --- ---- ' . esc_html( $label ) . ' DEBUG END ---- --- -- -->';
		echo "\n";
		echo '<!-- -------------------- -->';
		echo "\n";
		echo '<!-- ---------------- -->';
		echo "\n";
		echo '<!-- ------------ -->';
		echo "\n\n";
	}


	/**
	 * Adds message to error reporting if WP_DEBUG is true.
	 *
	 * Hook wf_debug_report_run used to get the backtrace up to what file and function called the specific function reported.
	 *
	 * @since	1.1
	 * @version	1.2
	 *
 	 * @param	[string] $function 			REQUIRED What you want to debug!
 	 * @param	[string] $message 			REQUIRED What you want to debug!
 	 * @param	[string] $version 			REQUIRED What you want to debug!
 	 *
 	 * @todo	Check this out - has been removed from main functions in v2.1. Need to hookup into main functions for better debug.
	 */
	function wf_debug_report( $function, $message, $version ) {

		do_action( 'wf_debug_report_run', $function, $message, $version );

		if ( WP_DEBUG )
			$version = !is_null( $version ) ? '' : sprintf( __( '(This message was added in version %s.)', 'wonderflux' ), $version );
			$message .= ' ' . __( 'Please see <a href="http://wonderflux.com/guide/">The Wonderflux Guide</a> for more information.', 'wonderflux' );
			trigger_error( sprintf( __( '%1$s was called <strong>incorrectly</strong>. %2$s %3$s', 'wonderflux' ), $function, $message, $version ) );

	}


	/**
	 * Reveals all Wonderflux hooks available in current view.
	 *
	 * When logged in as a user has capability of manage_options (can be override with wflux_debug_show_hooks filter)
	 * and WF_DEBUG constant defined as true, this plugin reveals the location of all relevant Wonderflux display hooks within your theme.
	 *
	 * Filters available:
	 * wflux_debug_show_hooks - display hooks information to all levels of users instead of those with manage_options capability.
	 *
	 * @since	1.2
	 * @version	1.2
	 *
 	 * @param	none
	 */
	function wf_show_hooks(){

		$user_priv = ( has_filter('wflux_debug_show_hooks') ) ? apply_filters( 'wflux_debug_show_hooks', false ) : current_user_can( 'manage_options' );

		if ( !is_admin() && $user_priv ){

			add_action( 'wf_output_start', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wf_head_meta', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wf_after_head', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wf_footer', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfbody_before_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfbody_after_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfheader_before_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfheader_before_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfheader_before_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfheader_after_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfheader_after_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfheader_after_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wffooter_before_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wffooter_before_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wffooter_before_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wffooter_after_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wffooter_after_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wffooter_after_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_all', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_all', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_all', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_all', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_wrapper', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_all_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_all_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_all_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_all_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_all_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_index', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_index', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_index_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_index_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_index_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_index_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_index_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_index', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_index', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_home', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_home', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_home_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_home_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_home_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_home_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_home_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_home', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_home', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_page', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_page', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_page_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_page_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_page_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_page_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_page_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_page', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_page', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_single', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_single', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_single_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_single_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_single_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_single_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_single_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_single', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_single', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_category', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_category', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_category_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_category_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_category_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_category_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_category_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_category', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_category', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_date', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_date', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_date_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_date_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_date_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_date_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_date_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_date', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_date', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_author', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_author', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_author_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_author_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_author_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_author_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_author_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_author', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_author', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_tag', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_tag', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_tag_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_tag_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_tag_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_tag_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_tag_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_tag', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_tag', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_taxonomy', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_taxonomy', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_taxonomy_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_taxonomy_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_taxonomy_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_taxonomy_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_taxonomy_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_taxonomy', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_taxonomy', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_archive', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_archive', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_archive_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_archive_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_archive_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_archive_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_archive_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_archive', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_archive', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_search', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_search', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_search_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_search_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_search_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_search_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_search_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_search', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_search', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_attachment', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_attachment', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_attachment_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_attachment_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_attachment_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_attachment_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_attachment_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_attachment', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_attachment', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_before_404', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfsidebar_after_404', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_404_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_404_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_404_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_404_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_404_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_before_found_posts_404', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfloop_after_found_posts_404', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_bp_container', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_before_bp_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_bp_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_bp_main_content', array($this, 'wf_show_hooks_do'), 1 );
			add_action( 'wfmain_after_bp_container', array($this, 'wf_show_hooks_do'), 1 );
		}
	}


	/**
	 * Internal function to display hooks.
	 * Used by wf_show_hooks()
	 *
	 * @since	1.2
	 * @version	2.2
	 *
 	 * @param	none
	 */
	function wf_show_hooks_do(){
		$debug_style = apply_filters( 'wflux_debug_show_hooks_css', 'display: block; background-color: rgba(127, 127, 127, 0.7); border: 1px solid #212121; margin: 0; padding: 2px; font-size: 0.8em; color: #fff; clear:both;' );
		echo '<p style="'.esc_attr($debug_style).'">Wonderflux hook: &#x27;' . current_filter() . '&#x27;</p>';
	}


}


/**
 * Core Wonderflux WordPress internal admin helper functions.
 */
class wflux_wp_core {


	/**
	 * Adds Wonderflux links to WordPress admin bar.
	 *
	 * @since	0.93
	 * @version	2.4
	 *
	 * @param	none
	 *
	 * @todo	Will need to review all admin options when they are moved to Customizer!
	 */
	function wf_admin_bar_links() {

		if ( !is_admin_bar_showing() || !current_user_can('manage_options') ) {
			return;
		} elseif ( WF_ADMIN_ACCESS == 'none') {
			return;
		} elseif ( WF_ADMIN_ACCESS !='' ) {

			global $wp_admin_bar;

			if ( WF_ADMIN_ACCESS == wfx_user_role('') ) {
				//Backpat < WordPress 3.3
				if ( WF_WORDPRESS_VERSION < 3.3 ) {
					$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'title' => __( 'Wonderflux Stylelab', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_stylelab') ) );
				} else {
					$wp_admin_bar->add_menu( array( 'id' => 'wonderflux-admin-bar-menu', 'title' => __( 'Wonderflux', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux') ) );
					$wp_admin_bar->add_menu( array( 'parent' => 'wonderflux-admin-bar-menu', 'id' => 'wonderflux-admin-bar-menu-2', 'title' => __( 'Wonderflux home', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux') ) );
					$wp_admin_bar->add_menu( array( 'parent' => 'wonderflux-admin-bar-menu', 'id' => 'wonderflux-stylelab', 'title' => __( 'Stylelab', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_stylelab') ) );
					$wp_admin_bar->add_menu( array( 'parent' => 'wonderflux-admin-bar-menu', 'id' => 'wonderflux-advanced', 'title' => __( 'Advanced options', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_advanced') ) );
					$wp_admin_bar->add_menu( array( 'parent' => 'wonderflux-admin-bar-menu', 'id' => 'wonderflux-system', 'title' => __( 'System information', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_system') ) );
					$wp_admin_bar->add_menu( array( 'parent' => 'wonderflux-admin-bar-menu', 'id' => 'wonderflux-backup', 'title' => __( 'Backup/restore', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_backup') ) );
					$wp_admin_bar->add_menu( array( 'parent' => 'wonderflux-admin-bar-menu', 'id' => 'wonderflux-ext-guide', 'title' => __( 'API/documentation', 'wonderflux' ), 'href' => wp_sanitize_redirect('wonderflux.com/guide') ) );
				}
			}

		} else {
			// Silence is golden
		}

	}


	/**
	 * Adds files currently used to render view to the Wonderflux admin bar menu.
	 * Incredibly useful for debugging - shows which files are your child themes and which are Wonderflux core.
	 *
	 * @since	0.93
	 * @version	2.4
	 *
	 * @param	none
	 *
	 * @todo	Will need to review all admin options when they are moved to Customizer!
	 */
	function wf_admin_bar_files_info(){

		if ( !is_admin_bar_showing() || !current_user_can('manage_options') || is_admin() ) {
			return;
		} elseif ( WF_ADMIN_ACCESS == 'none') {
			return;
		} elseif ( WF_ADMIN_ACCESS !='' ) {

			if ( WF_ADMIN_ACCESS == wfx_user_role('') ) {

				global $wp_admin_bar;

				// Setup first menu item
				$wp_admin_bar->add_menu( array(
					'id'    => 'wfx-file-menu',
					'parent'=> 'wonderflux-admin-bar-menu',
					'title' => 'Files in use',
					'meta'  => array(
						'title' => __('Wonderflux template parts in use', 'wonderflux'),
					),
				));

				// Get file data
				$included_files = get_included_files();
				$stylesheet_dir = str_replace( '\\', '/', get_stylesheet_directory() );
				$template_dir = str_replace( '\\', '/', get_template_directory() );

				foreach ( $included_files as $key => $path ) {

				    $path = str_replace( '\\', '/', $path );
					// Strip out non-template includes and other bits
				    if ( strpos( $path, $stylesheet_dir ) === false && strpos( $path, $template_dir ) === false ){
				        unset( $included_files[$key] );
				    } elseif ( strpos($path, 'wf-includes') || strpos($path, 'functions.php') || strpos($path, 'wf-config.php') ){
				        unset( $included_files[$key] );
				    }

				}

				// Reformat for display output
				$nice_includes = array();
				$includes_count = 0;

				foreach ( $included_files as $file ) {
					$urlParts = explode( '/', $file );
					$parts_count = count( $urlParts );
					$file_1 = ( $includes_count == 0 ) ? 'Main file > ' . $urlParts[$parts_count-1] : $urlParts[$parts_count-1];
					$nice_includes[$includes_count] = $file_1 . ' (' . $urlParts[$parts_count-2] . ')';
					$includes_count ++;
				}

				foreach ( $nice_includes as $menu_item ) {
					//TODO: Add in links to Wonderflux guide for relevant template parts
					$wp_admin_bar->add_menu( array(
						'id'    => sanitize_title( $menu_item ),
						'parent' => 'wfx-file-menu',
						'title' => esc_html( $menu_item ),
						/*'href'  => '#',*/
						/*'meta'  => array(
							'title' => __('My Sub Menu Item'),
							'target' => '_blank',
							'class' => 'my_menu_item_class'
						),*/
					));
				}

			}

		}

	}


}
?>