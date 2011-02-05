<?php
/**
* Load the options
*/
class wflux_data {

	//Size vars
	protected $wfx_db_display; // Array of core Wonderflux display options
	protected $wfx_width; // Width of main site container
	protected $wfx_position; // Position of main site container
	protected $wfx_columns; // Number of columns
	protected $wfx_columns_width; // Width of columns
	protected $wfx_sidebar_primary_position; // Primary sidebar position

	function __construct() {

		$this->wfx_db_display = get_option('wonderflux_display');
		// Setup and clean variables ready to use

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
	* @since 0.62
	* @lastupdate 0.913
	* @params $echo = 'echo'=>echos role, 'var'=>returns value to be used in PHP
	* @return text string of user role: 'administrator', 'editor', 'author', 'contributor', subscriber'
	*/


	function wf_user_role($args) {

	$defaults = array (
		'echo' => 'N'
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	global $current_user;
	get_currentuserinfo();
	$theuser = new WP_User( $current_user->ID );

	if ( !empty( $theuser->roles ) && is_array( $theuser->roles ) ) {
		foreach ( $theuser->roles as $role )
		$theuserrole = $role;

		if ($echo == 'Y') {
			echo $theuserrole;
		} elseif ($echo == 'var') {
			return $theuserrole;
		} else {
			// Fallback - just return, same as 'var', so no parameters supplied RETURNS the value as default as this is the way it will be used most of the time
			return $theuserrole;
		}

		}

	}


	/**
	* Gets current page depth
	*
	* @since 0.86
	* @lastupdate 0.913
	* @params echo (Y/N)
	* @return integer representing page depth (NOTE: begins at '1' for top level, not 0 - because its logical)
	*/
	function wf_page_depth($args) {

		$defaults = array (
			'echo' => 'N'
		);

		// Stops errors when this is run in invalid location
		if (is_page() && (!is_home() || !is_front_page()) ) {

			$args = wp_parse_args( $args, $defaults );
			extract( $args, EXTR_SKIP );

			global $wp_query;
			$object = $wp_query->get_queried_object();
			$parent_id  = $object->post_parent;
			$depth = 0;
			while ($parent_id > 0) {
				$page = get_page($parent_id);
				$parent_id = $page->post_parent;
				$depth++;
			}
			if ($echo =='Y') { echo $depth+1; }
			else { return $depth+1; }

		}

	}


}
?>