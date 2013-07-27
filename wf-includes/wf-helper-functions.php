<?php
/**
* Load and prepare the options
*/
class wflux_data {

	//Size vars
	protected $wfx_db_display; // Array of core Wonderflux display options
	protected $wfx_doc_type; // Document type
	protected $wfx_doc_lang; // Document type
	protected $wfx_doc_charset; // Document type
	protected $wfx_width; // Width of main site container
	protected $wfx_position; // Position of main site container
	protected $wfx_columns; // Number of columns
	protected $wfx_columns_width; // Width of columns
	protected $wfx_sidebar_primary_position; // Primary sidebar position

	protected $wfx_content_1_display; // Display of main content - EXPERIMENTAL, needs extra coding in core
	protected $wfx_content_1_size; // Relative 'size' of main content area eg 'three_quarter'
	protected $wfx_content_1_id; // CSS ID of main content container div
	protected $wfx_content_1_size_columns; // Size in columns - NOTE overrides _size variable if set

	protected $wfx_content_size_px; // For core WordPress $content_width global

	protected $wfx_fb_admins; // Facebook admin owners, for meta data
	protected $wfx_fb_app; // Facebook app ID, for meta data

	function __construct() {

		// Main Wonderflux display array
		$this->wfx_db_display = get_option('wonderflux_display');

		//// DOCUMENT CONFIGURATION ////

		// DOCTYPE - 'transitional','strict','frameset','1.1','1.1basic'
		$this->wfx_doc_type = (isset($this->wfx_db_display['doc_type']) ) ? $this->wfx_db_display['doc_type'] : false;
		// Validate
		$wfx_doc_type_out = 'transitional';
		$wfx_doc_type_accept = array('transitional','strict','frameset','1.1','1.1basic','html5','XHTML/RDFa');
		if ( in_array($this->wfx_doc_type,$wfx_doc_type_accept) ) { $wfx_doc_type_out = $this->wfx_doc_type; }
		$this->wfx_doc_type = $wfx_doc_type_out;

		// LANGUAGE CODE
		$this->wfx_doc_lang = (isset($this->wfx_db_display['doc_lang']) ) ? $this->wfx_db_display['doc_lang'] : false;
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

		// CONTAINER SIZE - 400 to 2000
		$this->wfx_width = (isset($this->wfx_db_display['container_w']) ) ? $this->wfx_db_display['container_w'] : false;
		// Validate
		$wfx_width_out = 950;
		if (is_numeric ($this->wfx_width) ) { if ($this->wfx_width >= 400 && $this->wfx_width <= 2000) {$wfx_width_out = $this->wfx_width;} }
		$this->wfx_width = $wfx_width_out;

		// SITE CONTAINER POSITION - left, middle, right
		$this->wfx_position = (isset($this->wfx_db_display['container_p']) ) ? $this->wfx_db_display['container_p'] : false;
		// Validate
		$wfx_container_p_out = 'middle';
		$wfx_container_p_accept = array('left','middle','right');
		if ( in_array($this->wfx_position,$wfx_container_p_accept) ) { $wfx_container_p_out = $this->wfx_position; }
		$this->wfx_position = $wfx_container_p_out;

		// NUMBER OF COLUMNS - min 2, max 100
		$this->wfx_columns = (isset($this->wfx_db_display['columns_num']) ) ? $this->wfx_db_display['columns_num'] : false;
		// Validate
		$wfx_columns_out = 24;
		if (is_numeric ($this->wfx_columns) ) { if ($this->wfx_columns >= 2 && $this->wfx_columns <= 100) {$wfx_columns_out = $this->wfx_columns;} }
		$this->wfx_columns = $wfx_columns_out;

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
			if (has_filter('wflux_sidebar_1_display') ) {
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
		$this->wfx_sidebar_1_id = wp_kses_data($this->wfx_sidebar_1_id, '');

		// SIDEBAR 1 COLUMNS
		$this->wfx_sidebar_1_size_columns = 0;
		$this->wfx_sidebar_1_size_columns = apply_filters( 'wflux_sidebar_1_size_columns', $this->wfx_sidebar_1_size_columns );
		//if ( !has_filter('wflux_sidebar_1_size_columns') ) { $this->wfx_sidebar_1_size_columns = $this->wfx_db_display['sidebar_c']; // DB ACTION!! }
		$this->wfx_sidebar_1_size_columns = ( is_numeric($this->wfx_sidebar_1_size_columns) ) ? $this->wfx_sidebar_1_size_columns : 0;

		//// THEME INFORMATION ////

		// Backpat - depreciated function get_theme() and get_current_theme() in WordPress 3.4
		$this->wfx_mytheme_version = ( WF_WORDPRESS_VERSION < 3.4 ) ? current_theme_info()->version : wp_get_theme()->version;
		$this->wfx_mytheme_version_clean = ( is_numeric($this->wfx_mytheme_version) ) ? $this->wfx_mytheme_version : urlencode( sanitize_title_with_dashes($this->wfx_mytheme_version) );

	}

}


/**
* Core Wonderflux helper functions
* Used internally by Wonderflux and can be used by advanced theme developers to cut down code in their advanced child themes!
* IMPORTANT - If any info is being grabbed in a function, it probably wants go go in here!
*/
class wflux_helper {

	/**
	* Detects what type of content you are currently viewing
	*
	* @since 0.881
	* @lastupdate 1.0RC3
	* @return text string of location: 'home', 'category', 'tag', 'search', date', 'author', 'taxonomy', 'archive', 'attachment', 'single', 'page', '404', 'index' (default)
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
	* as opposed to archive type views
	*
	* @since 1.0
	* @lastupdate 1.0RC3
	* @return text string: 'single' or 'index'
	*/
	function wf_info_single() {
		switch ( $this->wf_info_location() ) {
			case 'post'||'page'||'attachment'||'author': $out = 'single'; break;
			default : $out = false; break;

		}
		return $out;
	}


	/**
	* Turbo-charged get template part file include
	* Appends various location information and uses those files if available in your theme folder
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
	* 3 loop-content.php
	*
	* TAXONOMY ARCHIVE
	* 1 loop-content-taxonomy-{taxonomy-name}-{taxonomy-term}.php
	* 2 loop-content-taxonomy-{taxonomy-name}.php
	* 3 loop-content-taxonomy.php
	* 4 loop-content.php
	*
	* TAG ARCHIVE
	* 1 loop-content-tag-{tag-slug}.php
	* 2 loop-content-tag.php
	* 3 loop-content.php
	*
	* DATE ARCHIVE
	* 1 loop-content-date-{YEAR}-{MONTH}.php (4 digit year, 2 digit month with leading zero if less than 10)
	* 2 loop-content-date-{YEAR}.php (4 digit year)
	* 3 loop-content-date.php
	* 4 loop-content.php
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
	* 2 loop-content.php
	*
	* ARCHIVE
	* 1 loop-content-archive.php
	* 2 loop-content.php
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
	* TODO: Support core WordPress post formats?
	*
	* IMPORTANT: Used in core template files to setup specific template_parts
	*
	* @since 0.881
	* @lastupdate 1.0RC3
	*
	* @param string $part REQUIRED The slug name for the generic template
	* @param string $tag OPTIONAL Extends taxonomy views for additional tag specific template parts
	*/
	function wf_get_template_part($args) {

		$defaults = array (
			'part' => false
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		if (!$part) return;

		$this_location = $this->wf_info_location('');

		switch($this_location) {

			// Single post/custom post type
			case ('single'):
				$slug = get_query_var('post_type');
				$slug_depth_1 = (isset($slug)) ? $this_location . '-' . $slug : false;
				if ( locate_template($part.'-'.$slug_depth_1.'.php', false) !='' ): get_template_part($part, $slug_depth_1);
				else: get_template_part($part, $this_location); endif;
			break;

			// Category archive
			case ('category'):
				$slug = get_category(get_query_var('cat'))->slug;
				$slug_depth_1 = (isset($slug)) ? $this_location . '-' . $slug : false;
				if ( locate_template($part.'-'.$slug_depth_1.'.php', false) !='' ): get_template_part($part, $slug_depth_1);
				else: get_template_part($part, $this_location); endif;
			break;

			// Tag archive
			case ('tag'):
				$slug = get_query_var('tag');
				$slug_depth_1 = (isset($slug)) ? $this_location . '-' . $slug : false;
				if ( locate_template($part.'-'.$slug_depth_1.'.php', false) !='' ): get_template_part($part, $slug_depth_1);
				else: get_template_part($part, $this_location); endif;
			break;

			// Taxonomy archive
			case ('taxonomy'):
				//NOTE: No get_query_var / $wp_query in taxonomy archive view - not populated
				$this_q = get_queried_object();
				$slug_depth_1 = (isset($this_q->taxonomy)) ? $this_location . '-' . $this_q->taxonomy : false;
				$slug_depth_2 = (isset($this_q->slug)) ? $this_location . '-' . $this_q->taxonomy . '-' . $this_q->slug : false;
				if ( locate_template($part.'-'.$slug_depth_2.'.php', false) !='' ): get_template_part($part, $slug_depth_2);
				elseif (locate_template($part.'-'.$slug_depth_1.'.php', false) !='' ): get_template_part($part, $slug_depth_1);
				else: get_template_part($part, $this_location); endif;
			break;

			// Date archive
			case ('date'):
				$month = get_query_var('monthnum');
				$year = get_query_var('year');
				$slug_1 = (!empty($year)) ? '-' . $year : false;
				$slug_2 = (!empty($month)) ? ($month < 10) ? sprintf('-%02d', $month) : '-' . $month : false;
				if ( locate_template($part.'-'.$this_location.$slug_1.$slug_2.'.php', false) !='' ): get_template_part($part, $this_location.$slug_1.$slug_2);
				elseif ( locate_template($part.'-'.$this_location.$slug_1.'.php', false) !='' ): get_template_part($part, $this_location.$slug_1);
				else: get_template_part($part, $this_location); endif;
			break;

			default:
				get_template_part($part, $this_location);
			break;

		}

	}


	/**
	* Returns user role of logged in user
	*
	* NOTE: Parameters, echo or return now controlled in core Wonderflux functions.php
	*
	* @since 0.62
	* @lastupdate 0.92
	* @return text string of user role: 'administrator', 'editor', 'author', 'contributor', subscriber'
	*/
	function wf_user_role() {

		global $current_user;
		get_currentuserinfo();
		$theuser = new WP_User( $current_user->ID );

		if ( !empty( $theuser->roles ) && is_array( $theuser->roles ) ) {
			foreach ( $theuser->roles as $role )
			$theuserrole = $role;
			return $theuserrole;
		}

	}


	/**
	* Gets current page depth
	*
	* @since 0.86
	* @lastupdate 0.92
	* @params start - (integer 0/1) - The starting figure (root level) - [0]
	* @params show_all - (Y/N) - Return root level on homepage and search - [N]
	* @return integer representing page depth
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
	* Get a custom field value for the main post being shown
	*
	* @params name - (string) - The name of the custom field - [NONE]
	* @params empty - (string) - If there is no value in custom field, do you want an alternative value? - [NONE]
	* @params escape - (Y/N) - Do you want the characters HTML escaped (so '<p>' becomes '&lt;p&gt;' - [N]
	* @params $format - ('string'/'date') - What type of data is it, do you want to change this date format? - ['string']
	* @params date_style - (string) - PHP date format - [l F j, Y]
	* @params return_error - (Y/N) - Do you want something returned on search (is_search) and 404 (is_404) ? - [N]
	* @params trim - (Y/N) - Trim white space characters from start and end of custom field value [N]
	* @params id - (integer) - function usually returns main loop custom field, setting $id forces function to get custom field from specific post ID [false]
	* 
	* @since 0.92
	* @lastupdate 1.1
	* @return custom field value, can be used inside and outside loop
	*/
	function wf_custom_field($args) {

		$defaults = array (
			'name' => '',
			'empty' => '',
			'escape' => 'N',
			'format' => 'string',
			'date_style' => 'l F j, Y',
			'return_error' => 'N',
			'trim' => 'N',
			'id' => false
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$empty_clean = wp_kses_post($empty, '');

		// Detect and optionally return useful value if no chance of custom field being here
		if (is_admin() ) {
			// Silence is golden
		} elseif (is_404() && $return_error == 'N' ) {
			return $empty_clean;
		} elseif (is_search() && $return_error == 'N' ) {
			return $empty_clean;
		} else {

			// We have something to query!
			wp_reset_query();

			if (empty($id)) {
				global $wp_query;
				$this_id = $wp_query->post->ID;
			}else{
				$this_id = intval($id);
			}

			$name_clean = wp_kses_data($name, '');

			$value = get_post_meta($this_id, $name_clean, true);
			$output = ($value != '') ? $value : $empty_clean;

			if ( $format == 'date' ) { $output = date($date_style, $output); };
			if ( $trim == 'Y' ) { $output = trim($output); }
			if ( $escape == 'Y' ) { return esc_attr($output); } else { return $output; }
		}

	}


	/**
	* Returns 'Y' - nothing more, nothing less
	* Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__Y' ) in your child theme, saves creating a function
	* @since 0.93
	* @lastupdate 0.93
	*
	* @return Y
	*/
	function wf__Y() { return 'Y'; }


	/**
	* Returns 'N' - nothing more, nothing less
	* Useful for setting values ie add_filter( 'wflux_sidebar_1_display', 'wfx__Y' ) in your child theme, saves creating a function
	* @since 0.93
	* @lastupdate 0.93
	*
	* @return Y
	*/
	function wf__N() { return 'N'; }


}


/**
* Core Wonderflux WordPress manipulation functions
* Used internally by Wonderflux
*/
class wflux_wp_core {

	/**
	* Removes core WordPress admin bar
	* @since 0.92
	* @lastupdate 0.92
	*/
	function wf_admin_bar_remove() {
		wp_deregister_script('admin-bar');
		wp_deregister_style('admin-bar');
		remove_action('wp_footer','wp_admin_bar_render',1000);
	}


	/**
	* Adds Wonderflux links under Appearance on the WordPress admin bar
	* @since 0.93
	* @lastupdate 1.0
	*/
	function wf_admin_bar_links() {
		global $wp_admin_bar, $wpdb;
		if ( !is_admin_bar_showing() || WF_ADMIN_ACCESS == 'none' ) {
			return;
		} elseif ( WF_ADMIN_ACCESS !='' ) {

			$input = @unserialize(WF_ADMIN_ACCESS);
			if ($input === false) {
				// Single user role supplied
				if ( WF_ADMIN_ACCESS == wfx_user_role('') && current_user_can('manage_options') ) {
					//Backpat < WordPress 3.3
					if ( WF_WORDPRESS_VERSION < 3.3 ) {
						$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'title' => __( 'Style Lab', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_stylelab') ) );
					} else {
						$wp_admin_bar->add_menu( array( 'parent' => 'site-name', 'id' => 'wf-stylelab', 'title' => __( 'Style Lab', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_stylelab') ) );
					}
				}
			} else {
				// Array of user ID's supplied
				global $current_user;
				get_currentuserinfo();
				foreach ($input as $key=>$user_id) {
					if ( $user_id == $current_user->ID && current_user_can('manage_options') ) {
						//Backpat < WordPress 3.3
						if ( WF_WORDPRESS_VERSION < 3.3 ) {
							$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 'title' => __( 'Style Lab', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_stylelab') ) );
						} else {
							$wp_admin_bar->add_menu( array( 'parent' => 'site-name', 'id' => 'wf-stylelab', 'title' => __( 'Style Lab', 'wonderflux' ), 'href' => wp_sanitize_redirect(admin_url().'admin.php?page=wonderflux_stylelab') ) );
						}
					}
				}

			}

		} else {
			// Silence is golden
		}
	}

}
?>