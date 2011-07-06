<?php
/**
* Load the options
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

	function __construct() {

		// Main Wonderflux display array
		$this->wfx_db_display = get_option('wonderflux_display');

		// DOCTYPE - 'transitional','strict','frameset','1.1','1.1basic'
		$this->wfx_doc_type = $this->wfx_db_display['doc_type'];
		// Validate
		$wfx_doc_type_out = 'transitional';
		$wfx_doc_type_accept = array('transitional','strict','frameset','1.1','1.1basic','html5');
		if ( in_array($this->wfx_doc_type,$wfx_doc_type_accept) ) { $wfx_doc_type_out = $this->wfx_doc_type; }
		$this->wfx_doc_type = $wfx_doc_type_out;

		// LANGUAGE CODE
		$this->wfx_doc_lang = $this->wfx_db_display['doc_lang'];
		// Validate
		$wfx_doc_lang_out = 'en';
		// Too many language codes to validate against - lets just check for length
		if ( $this->wfx_doc_lang != '' ) {
			if (strlen(trim($this->wfx_doc_lang)) == 2 ) { $wfx_doc_lang_out = $this->wfx_doc_lang; }
		}
		$this->wfx_doc_lang = $wfx_doc_lang_out;

		// CHARACTER SET
		$this->wfx_doc_charset = $this->wfx_db_display['doc_charset'];
		// Validate
		$wfx_doc_charset_out = 'UTF-8';
		if ($this->wfx_doc_charset !='') {

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

		// CONTAINER SIZE - 400 to 2000
		$this->wfx_width = $this->wfx_db_display['container_w'];
		// Validate
		$wfx_width_out = 950;
		if (is_numeric ($this->wfx_width) ) { if ($this->wfx_width >= 400 && $this->wfx_width <= 2000) {$wfx_width_out = $this->wfx_width;} }
		$this->wfx_width = $wfx_width_out;

		// SITE CONTAINER POSITION - left, middle, right
		$this->wfx_position = $this->wfx_db_display['container_p'];
		// Validate
		$wfx_container_p_out = 'middle';
		$wfx_container_p_accept = array('left','middle','right');
		if ( in_array($this->wfx_position,$wfx_container_p_accept) ) { $wfx_container_p_out = $this->wfx_position; }
		$this->wfx_position = $wfx_container_p_out;

		// NUMBER OF COLUMNS - min 1, max 80
		$this->wfx_columns = $this->wfx_db_display['columns_num'];
		// Validate
		$wfx_columns_out = 24;
		if (is_numeric ($this->wfx_columns) ) { if ($this->wfx_columns >= 1 && $this->wfx_columns <= 80) {$wfx_columns_out = $this->wfx_columns;} }
		$this->wfx_columns = $wfx_columns_out;

		// COLUMN WIDTH - min 10, max 1000
		$this->wfx_columns_width = $this->wfx_db_display['columns_w']; // Width of columns
		// Validate
		$wfx_columns_width_out = 30;
		if (is_numeric ($this->wfx_columns_width) ) { if ($this->wfx_columns_width >= 10 && $this->wfx_columns_width <= 1000) {$wfx_columns_width_out = $this->wfx_columns_width;} }
		$this->wfx_columns_width = $wfx_columns_width_out;

		// SIDEBAR PRIMARY POSITION - left, right
		$this->wfx_sidebar_primary_position = $this->wfx_db_display['sidebar_p']; // Primary sidebar position
		// Validate
		$wfx_sidebar_pp_out = 'left';
		$wfx_sidebar_pp_accept = array('left','right');
		if ( in_array($this->wfx_sidebar_primary_position,$wfx_sidebar_pp_accept) ) { $wfx_sidebar_pp_out = $this->wfx_sidebar_primary_position; }
		$this->wfx_sidebar_primary_position = $wfx_sidebar_pp_out;

		// CHILD FUNCTIONALITY
		$this->wfx_wp_info = get_theme( get_current_theme() );
		$this->wfx_mytheme_version = $this->wfx_wp_info['Version'];

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
	* @lastupdate 0.901
	* @params $echo (not manditory) = TRUE (default) | FALSE = echos role, 'var'=>returns value to be used in PHP
	* @return text string of location: 'administrator', 'editor', 'author', 'contributor', subscriber'
	*/
	function wf_info_location($args) {

		$defaults = array (
			'echo' => FALSE
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		switch (TRUE) {

			case is_home() || is_front_page() : $output = 'home'; break;
			case is_category() : $output = 'category'; break;
			case is_tag() : $output = 'tag'; break;
			case is_search() : $output = 'search'; break;
			case is_date() : $output = 'date'; break;
			case is_author() : $output = 'author'; break;
			case is_tax() : $output = 'tax'; break;
			case is_archive() : $output = 'archive'; break;
			case is_attachment() : $output = 'attachment'; break;
			case is_single() : $output = 'single'; break;
			case is_page() : $output = 'page'; break;
			case is_404() : $output = '404'; break;
			default : $output = 'unknown-type'; break;

		}

		// How would you like that?
		if ($echo == FALSE) {
			return $output;
		} else {
			echo $output;
		}

	}


	/**
	* Template part location aware builder
	* IMPORTANT: Used in core template files to setup specific template_parts
	* Could also be used by theme developers anywhere where they want a location specific additional template part of their own naming and design.
	*
	* @since 0.881
	* @lastupdate 0.913
	* @params part_name (MANDITORY TO WORK AS EXPECTED!) = string. Name of bottom level template part
	*/
	function wf_get_template_part($args) {

		$defaults = array (
			'part' => 'loop-content'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$this_location = $this->wf_info_location('');
		$this_location_condition = 'is_'.$this_location.'()';

		// wf_info_location function wont work for multiple test required here, so check now
		if (is_home() || is_front_page()) :
		get_template_part($part, $this_location);

		// Now break down to conditional get_template parts like 'loop-content-page.php and loop-content-category.php
		elseif ($this_location_condition) :
		get_template_part($part, $this_location);

		// Just get the default template part as a fall-back
		else :
		get_template_part($part);
		endif;

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
	* @params return_error - (Y/N) - Do you want something returned on search (is_wp_search) and 404 (is_wp_404) ? - [N]
	* @since 0.92
	* @lastupdate 0.92
	* @return custom field value, can be used inside and outside loop
	*/
	function wf_custom_field($args) {

		$defaults = array (
			'name' => '',
			'empty' => '',
			'escape' => 'N',
			'return_error' => 'N'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		$return_error_valid = array('N','Y');
		$return_error = in_array($return_error,$return_error_valid) ? 'N' : 'Y';

		// Detect and optionally return useful value if no chance of custom field being here
		if (is_search() && $return_error == 'N' ) {
			return 'is_wp_search';
		} elseif (is_404() && $return_error == 'N' ) {
			return 'is_wp_404';
		} else {
			// We have something to query!
			wp_reset_query();
			global $wp_query;
			$this_postid = $wp_query->post->ID;
			$name_clean = wp_kses_data($name, '');
			$empty_clean = wp_kses_post($empty, '');
			$value = get_post_meta($this_postid, $name_clean, true);
			$output = ($value != '') ? $value : $empty_clean;

			if ($escape == 'Y') { return esc_attr($output); } else { return $output; }
		}

	}


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
}
?>