<?php

/**
*
* Wonderflux admin functions
*
*/
class wflux_admin {

    // wf_page_build
	var $icon;
    var $title;
    var $include;
    var $paths;

    //var $superadmin;

	/**
	*
	* @since 0.3
	* @updated 0.3
	*
	* Build the admin menus
	* TODO: Make this more dynamic and feed 1 array of options to build as many options pages as we need quickly!
	*
	*/
	function wf_add_pages(){

		add_menu_page('Wonderflux main options', 'Wonderflux', 'administrator', 'wonderflux', array($this, 'wf_page_core'));
		add_submenu_page( 'wonderflux', 'Wonderflux Style Lab', 'Style Lab', 'administrator', 'wonderflux_stylelab', array($this, 'wf_page_stylelab'));
		add_submenu_page( 'wonderflux', 'Wonderflux SEO Optimisation', 'SEO optimise', 'administrator', 'wonderflux_seo', array($this, 'wf_page_seo'));
		add_submenu_page( 'wonderflux', 'Wonderflux CMS', 'CMS control', 'administrator', 'wonderflux_cms', array($this, 'wf_page_cms'));
		//TODO: If user is superadmin ID, reveal advanced config menu
	}


	/**
	*
	* @since 0.1
	* @updated 0.1
	*
	* Adds core page content to admin area
	*
	*/
	function wf_page_core() {

		$this->wf_page_build('index', 'Wonderflux Home', 'core');

	}

	/**
	*
	* @since 0.1
	* @updated 0.1
	*
	* Adds style page content to admin area
	*
	*/
	function wf_page_stylelab() {

		$this->wf_page_build('themes', 'Wonderflux Stylelab', 'style');

	}

	/**
	*
	* @since 0.1
	* @updated 0.1
	*
	* Adds SEO page content to admin area
	*
	*/
	function wf_page_seo() {

		$this->wf_page_build('plugins', 'Wonderflux Search Engine Optimiser', 'seo');

	}

	/**
	*
	* @since 0.1
	* @updated 0.1
	*
	* Adds CMS page content to admin area
	*
	*/
	function wf_page_cms() {

		$this->wf_page_build('options-general', 'Wonderflux Content Management Options', 'cms');

	}


	/**
	*
	* @since 0.1
	* @updated 0.884
	*
	* Builds Wonderflux admin pages
	*
	* TODO: Extend with settings fields and form builder functions
	*
	*	@params
	*
	*	'icon' =
	*	edit - pin
	*	index - house
	*	upload - media
	*	link - chain link
	*	page - page
	*	edit-comments - speech bubble
	*	themes - window with blocks
	*	plugins - plug
	*	profile - people
	*	tools - hammer/screwdriver
	*	options-general - slider panel
	*
	*	'title' = Title at top of page
	*
	*	'include' = Which admin content/form to include
	*
	*/
	function wf_page_build($icon, $title, $include) {

		echo '<div class="themes-php wrap">';

		echo '<div id="icon-'.$icon.'" class="icon32"><br /></div>';

		switch ($title) {

			case('Wonderflux Home'): $tab1=TRUE; break;
			case('Wonderflux Stylelab'): $tab2=TRUE; break;
			case('Wonderflux Search Engine Optimiser'): $tab3=TRUE; break;
			case('Wonderflux Content Management Options'): $tab4=TRUE; break;
			default: $tab1=TRUE; break;

		}

		$thistab_highlight = ' nav-tab-active';

		echo '<div class="themes-php wrap">';

		echo '<h2>';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux" class="nav-tab';
		if (isset($tab1)) { echo $thistab_highlight; };
		echo'">' . esc_html_x('Wonderflux Home', 'wonderflux') . '</a>';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_stylelab" class="nav-tab';
		if (isset($tab2)) { echo $thistab_highlight; };
		echo'">' . esc_html_x('Stylelab', 'wonderflux') . '</a>';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_seo" class="nav-tab';
		if (isset($tab3)) { echo $thistab_highlight; };
		echo'">' . esc_html_x('SEO', 'wonderflux') . '</a>';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_cms" class="nav-tab';
		if (isset($tab4)) { echo $thistab_highlight; };
		echo'">' . esc_html_x('CMS', 'wonderflux') . '</a>';
		echo '</h2>';

		echo '</div>';

		//echo '<h2>'.esc_attr($title).'</h2>';

		require('admin-pages/wf-page-'.$include.'.php');

		$wf_current_theme = get_current_theme();

		if ($wf_current_theme == 'Wonderflux Framework') {
			$output = '<div id="message2" class="updated">';
			$output .= '<h3>Ooops, you could be doing so much more with Wonderflux!</h3>';
			$output .= '<p>Wonderflux is designed to be used with Wonderflux child themes, not activated directly.</p>';
			$output .= '<p>You can <a href="http://code.google.com/p/wonderflux-girder-theme/" title="Download the free Girder Wonderflux child theme">download an example child theme call Girder</a> - please activate this instead and enjoy!</p>';
			$output .= '</div>';

			echo $output;

		} else {
			echo '<p>You are currently using '.get_current_theme().' Wonderflux child theme</p>';
		}

		echo '</div>';

	}


	/**
	*
	* @since 0.81
	* @updated 0.902
	*
	* IMPORTANT - Sets up and configures options and form fields the neat way
	*
	*/
	function wf_register_settings(){
		register_setting('wf_settings_display', 'wonderflux_display' );
		//register_setting('wf-settings-display', 'wonderflux_display', 'plugin_options_validate' );

		$myadminforms = new wflux_admin_forms;

		add_settings_section('style_lab', '', array($myadminforms, 'wf_form_intro_main'), 'wonderflux_stylelab');

		//1) Key 2) form label 3) Builder function 4)Page? 5)Section
		add_settings_field('container_p', 'Site container position', array($myadminforms, 'wf_form_container_p'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('sidebar_p', 'Sidebar position', array($myadminforms, 'wf_form_sidebar_p'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('container_w', 'Site container width', array($myadminforms, 'wf_form_container_w'), 'wonderflux_stylelab', 'style_lab');
		//add_settings_field('padding_l', 'Left site container padding', array($myadminforms, 'wf_form_padding_l'), 'wonderflux_stylelab', 'style_lab');
		//add_settings_field('padding_r', 'Right site container padding', array($myadminforms, 'wf_form_padding_r'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('columns_num', 'Number of vertical columns (inside container+padding)', array($myadminforms, 'wf_form_columns_num'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('columns_w', 'Desired width of column', array($myadminforms, 'wf_form_columns_w'), 'wonderflux_stylelab', 'style_lab');
	}



//END wflux_admin class
}

/**
*
* Wonderflux admin form functions
*
*/
class wflux_admin_forms {

	//////// STYLE LAB FORM ITEMS START

	// Section HTML, displayed before the first option
	function  wf_form_intro_main() {
		echo '<p>Use these controls to setup the main dimensions used across all your Wonderflux template designs.</p>';
	}

	function wf_form_container_p() {
		$this->wf_form_helper_ddown_std('container_p',array('left', 'middle', 'right'));
	}

	function wf_form_sidebar_p() {
		$this->wf_form_helper_ddown_std('sidebar_p',array('left', 'right'));
	}

	function wf_form_container_w() {
		$this->wf_form_helper_ddown_range('container_w',400,2000,10);
	}

	/*
	// NOT ACTIVE AT MOMENT
	function wf_form_padding_l() {
		echo 'SAVES, BUT NOT ACTIVE ';
		$this->wf_form_helper_ddown_range('padding_l',0,200,1);
	}

	function wf_form_padding_r() {
		echo 'SAVES, BUT NOT ACTIVE ';
		$this->wf_form_helper_ddown_range('padding_r',0,200,1);
	}
	*/

	function wf_form_columns_num() {
		$this->wf_form_helper_ddown_range('columns_num',4,80,4);
	}

	function wf_form_columns_w() {
		$this->wf_form_helper_ddown_range('columns_w',10,200,1);
	}

	//////// STYLE LAB FORM ITEMS END

	/**
	*
	* @since 0.81
	* @updated 0.81
	*
	* Creates a dropdown for options page
	*
	*/
	function wf_form_helper_ddown_std($definition,$items) {

		$options = get_option('wonderflux_display');
		echo "<select id='columns_num' name='wonderflux_display[".$definition."]'>";
		foreach($items as $key=>$value) {
			$selected = ($options[$definition]==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$value</option>";
		}
		echo "</select>";
		echo "\n";
	}


	/**
	*
	* @since 0.81
	* @updated 0.81
	*
	* Creates a dropdown for options page populated with range of numbers
	*
	*/
	function wf_form_helper_ddown_range($definition,$low,$high,$step) {

		$options = get_option('wonderflux_display');
		$items = range($low,$high,$step);
		echo "<select id='columns_num' name='wonderflux_display[".$definition."]'>";
		foreach($items as $item) {
			$selected = ($options[$definition]==$item) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select>";
		echo "\n";
	}

//END wflux_admin_forms class
}

?>