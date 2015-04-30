<?php

/**
* @since 0.3
* @updated 2.0
* Admin area functions for options pages and menus
*/
class wflux_admin extends wflux_data {


    // wf_page_build
	var $title;
	var $include;
	var $paths;
	var $this_help;
	var $admin_forms;

	function __construct(){

		parent::__construct();

		$this->admin_forms = new wflux_admin_forms;
		$this->admin_backup = new wflux_admin_backup;
		add_action("load-admin_page_wonderflux_backup", array($this->admin_backup, 'wf_import_export'));
	}


	/**
	* Build the admin menus
	* @since 0.3
	* @updated 1.2
	*/
	function wf_add_pages(){
		$wflux_core_admin_page_main = add_theme_page( esc_attr__('Wonderflux main options','wonderflux'), esc_attr__('Wonderflux','wonderflux'), 'administrator', 'wonderflux', array($this, 'wf_page_core'));
		$wflux_core_admin_page_style = add_submenu_page( NULL, esc_attr__('Wonderflux Style Lab','wonderflux'), esc_attr__('Style Lab','wonderflux'), 'administrator', 'wonderflux_stylelab', array($this, 'wf_page_stylelab'));
		$wflux_core_admin_page_advanced = add_submenu_page( NULL, esc_attr__('Wonderflux Advanced','wonderflux'), esc_attr__('Advanced','wonderflux'), 'administrator', 'wonderflux_advanced', array($this, 'wf_page_advanced'));
		$wflux_core_admin_page_cms = add_submenu_page( NULL, esc_attr__('Wonderflux System Information','wonderflux'), esc_attr__('System Information','wonderflux'), 'administrator', 'wonderflux_system', array($this, 'wf_page_system'));
		$wflux_core_admin_page_backup = add_submenu_page( NULL, esc_attr__('Wonderflux Options Backup','wonderflux'), esc_attr__('Options Backup','wonderflux'), 'administrator', 'wonderflux_backup', array($this, 'wf_page_backup'));
		//TODO: If user has wonderflux_edit capability, reveal advanced config menu
	}


	// Add content to admin areas
	function wf_page_core() { $this->wf_page_build(esc_attr__('Wonderflux Home','wonderflux'), 'core'); }
	function wf_page_stylelab() { $this->wf_page_build(esc_attr__('Wonderflux Stylelab','wonderflux'), 'style'); }
	function wf_page_advanced() { $this->wf_page_build(esc_attr__('Wonderflux Advanced','wonderflux'), 'advanced'); }
	function wf_page_system() { $this->wf_page_build(esc_attr__('Wonderflux System Information','wonderflux'), 'system'); }
	function wf_page_backup() { $this->wf_page_build(esc_attr__('Wonderflux Options Backup','wonderflux'), 'backup'); }


	/**
	* Builds Wonderflux admin pages
	* @since 0.1
	* @updated 1.2
	*
	*	@params
	*
	*	'title' = Title at top of page
	*
	*	'include' = Which admin content/form to include
	*
	*/
	function wf_page_build($title, $include) {

		echo '<div class="themes-php wrap">';

		switch ($include) {
			case('core'): $tab1=TRUE; break;
			case('style'): $tab2=TRUE; break;
			case('advanced'): $tab3=TRUE; break;
			case('system'): $tab4=TRUE; break;
			case('backup'): $tab5=TRUE; break;
			default: $tab1=TRUE; break;
		}

		$thistab_highlight = ' nav-tab-active';
		echo '<div class="nav-tab-wrapper">';

		echo '<h2 class="nav-tab-wrapper">';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux" class="nav-tab';
		if (isset($tab1)) { echo $thistab_highlight; };
		echo'">' . esc_attr__('Wonderflux Home', 'wonderflux') . '</a>';

		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_stylelab" class="nav-tab';
		if (isset($tab2)) { echo $thistab_highlight; };
		echo'">' . esc_attr__('Stylelab', 'wonderflux') . '</a>';

		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_advanced" class="nav-tab';
		if (isset($tab3)) { echo $thistab_highlight; };
		echo'">' . esc_attr__('Advanced', 'wonderflux') . '</a>';

		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_system" class="nav-tab';
		if (isset($tab4)) { echo $thistab_highlight; };
		echo'">' . esc_attr__('System', 'wonderflux') . '</a>';

		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_backup" class="nav-tab';
		if (isset($tab5)) { echo $thistab_highlight; };
		echo'">' . esc_attr__('Backup/Restore', 'wonderflux') . '</a>';
		echo '</h2>';

		echo '</div>';

		if ( isset( $_GET['settings-updated'] ) ): echo '<div class="updated settings-error" id="setting-error-settings_updated"><p><strong>' . $title . ' ' . esc_attr__('Settings updated successfully.', 'wonderflux') . '</strong></p></div>'; endif;
		if ( isset( $_GET['backuperror'] ) ): echo '<div class="updated error" id="setting-error-settings_updated"><p><strong>' . esc_attr__('Import aborted - no settings changed. Sorry - looks like thats the wrong file you tried to import.', 'wonderflux') . '</strong></p></div>'; endif;
		if ( isset( $_GET['backupsuccess'] ) ): echo '<div class="updated" id="settings_updated"><p><strong>' . esc_attr__('Import complete - Wonderflux theme settings restored.', 'wonderflux') . '</strong></p></div>'; endif;

		require('admin-pages/wf-page-'.$include.'.php');

		if ($include == 'backup'): $this->admin_backup->wf_backup_form(); endif;

		// Include relevant output depending on grid system
		if ($include == 'advanced'){
			if ( $this->wfx_grid_type == 'pixels' ) {
				$this->admin_forms->wf_form_helper_file_css_combine('css/wf-css-core-structure.css','Y');
			} else {
				$this->admin_forms->wf_form_helper_file_css_combine_2('css/wf-css-flux-layout-core.css','Y');
			}
		}

		// Backpat - depreciated function get_current_theme() in WordPress 3.4
		$wf_current_theme = wp_get_theme()->Name;

		if ($wf_current_theme == 'Wonderflux Framework') {
			$output = '<div id="message2" class="updated">';
			$output .= '<h3>' . esc_attr__('Ooops, you could be doing so much more with Wonderflux!','wonderflux') . '</h3>';
			$output .= '<p>' . esc_attr__('Wonderflux is a theme framework. It can be directly activated like this and will work perfectly - but you are really missing out on all the cool stuff Wonderflux can do!','wonderflux') . '</p>';
			$output .= '<p>';
			$output .= esc_attr__('To get the most out of Wonderflux, you should take a quick read over','wonderflux');
			$output .= ' <a href="http://wonderflux.com/guide/doc/introduction/" title="';
			$output .= esc_attr__('Read the Wonderflux overview guide','wonderflux');
			$output .= '">';
			$output .= esc_attr__('the Wonderflux introduction','wonderflux');
			$output .= '</a> ';
			$output .= esc_attr__('- also be sure to check out the documentation links below to learn about how to use the various aspects of Wonderflux.','wonderflux');
			$output .= '</p>';
			$output .= $this->wf_common_help();
			$output .= '<p>';
			$output .= esc_attr__('You can also','wonderflux');
			$output .= ' <a href="https://github.com/Jonnyauk/wonderflux-girder/" title="';
			$output .= esc_attr__('Download the free Girder Wonderflux child theme','wonderflux');
			$output .= '">';
			$output .= esc_attr__('download the free example Wonderflux child theme call Girder','wonderflux');
			$output .= '</a>';
			$output .= esc_attr__(' - take a look at the layout code and especially the theme functions.php file for references on using Wonderflux.','wonderflux');
			$output .= '</strong></p>';
			$output .= '</div>';
			echo $output;
		} else {
			// Silence is golden
			//echo '<p>' . esc_attr__('You are using the','wonderflux') . ' ' . esc_attr($wf_current_theme) . ' ' . esc_attr__('Wonderflux child theme','wonderflux') . '</p>';
		}



		$output = '<h2>' . esc_attr__('Help and support','wonderflux') . '</h2>';
		$output .= '<p>';
		$output .= ' <a href="http://wonderflux.com/guide/" title="';
		$output .= esc_attr__('Visit the Wonderflux guide','wonderflux');
		$output .= '">';
		$output .= esc_attr__('The Wonderflux guide','wonderflux');
		$output .= '</a> ';
		$output .= esc_attr__('is the official (work in progress!) documentation site for Wonderflux. Click on the direct links below to find relevant content.','wonderflux');
		echo $output;
		echo $this->wf_common_help();
		echo '</div>'; // close themes-php wrap div
	}


	/**
	* Sets up and configures options and form fields
	* @since 0.81
	* @updated 2.0
	*/
	function wf_register_settings(){

		register_setting('wf_settings_display', 'wonderflux_display', array($this->admin_forms, 'validate_opts_layout') );

		add_settings_section('style_lab', '', array($this->admin_forms, 'wf_form_intro_main'), 'wonderflux_stylelab');
		add_settings_section('style_lab_grid_core', '', array($this->admin_forms, 'wf_form_intro_grid_core'), 'wonderflux_stylelab_grid_core');
		add_settings_section('style_lab_grid', '', array($this->admin_forms, 'wf_form_intro_grid'), 'wonderflux_stylelab_grid');
		add_settings_section('style_lab_p_templates', '', array($this->admin_forms, 'wf_form_intro_p_templates'), 'wonderflux_page_templates');
		add_settings_section('style_lab_doc', '', array($this->admin_forms, 'wf_form_intro_doc'), 'wonderflux_stylelab_doc');
		add_settings_section('style_lab_fb', '', array($this->admin_forms, 'wf_form_intro_fb'), 'wonderflux_stylelab_fb');

		//1) Key 2) form label 3) Builder function 4)Page 5)Section
		add_settings_field('grid_type', esc_attr__('Select CSS layout system','wonderflux'), array($this->admin_forms, 'wf_form_grid_type'), 'wonderflux_stylelab_grid_core', 'style_lab_grid_core');
		add_settings_field('columns_num', esc_attr__('Vertical columns (number - inside site container)','wonderflux'), array($this->admin_forms, 'wf_form_columns_num'), 'wonderflux_stylelab_grid', 'style_lab_grid');

		/**
		 * Backpat - add column width if using Wonderflux v1 pixel grid
		 */
		if ( $this->wfx_grid_type == 'pixels' ) {
			// Pixel column width
			add_settings_field('columns_w', esc_attr__('Width of column (pixels)','wonderflux'), array($this->admin_forms, 'wf_form_columns_w'), 'wonderflux_stylelab_grid', 'style_lab_grid');
		}

		/**
		 * Backpat - add container units if using Wonderflux v2 percent grid
		 */
		if ( $this->wfx_grid_type == 'percent' ) {
			// Pixel column width
			add_settings_field('container_u', esc_attr__('Site container width unit','wonderflux'), array($this->admin_forms, 'wf_form_container_u'), 'wonderflux_stylelab_grid_core', 'style_lab_grid_core');
		}

		/**
		 * Backpat - add conditional container width field (unit option)
		 */
		if ( $this->wfx_grid_type == 'pixels' ||  $this->wfx_width_unit == 'pixels' ) {
			// Pixel column width
			add_settings_field('container_w', esc_attr__('Site container width (pixels)','wonderflux'), array($this->admin_forms, 'wf_form_container_w'), 'wonderflux_stylelab_grid', 'style_lab_grid');
		} else {
			add_settings_field('container_w', esc_attr__('Site container width (percent)','wonderflux'), array($this->admin_forms, 'wf_form_container_w'), 'wonderflux_stylelab_grid', 'style_lab_grid');
		}

		add_settings_field('container_p', esc_attr__('Site container position','wonderflux'), array($this->admin_forms, 'wf_form_container_p'), 'wonderflux_stylelab_grid', 'style_lab_grid');
		add_settings_field('content_1_s_px', esc_attr__('Media width (pixels - used as WordPress $content_width for auto-embedding YouTube etc)','wonderflux'), array($this->admin_forms, 'wf_form_content_s_px'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('content_s', esc_attr__('Content width (relative size)','wonderflux'), array($this->admin_forms, 'wf_form_content_s'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('sidebar_s', esc_attr__('Sidebar width (relative size)','wonderflux'), array($this->admin_forms, 'wf_form_sidebar_s'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('sidebar_d', esc_attr__('Sidebar display','wonderflux'), array($this->admin_forms, 'wf_form_sidebar_d'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('sidebar_p', esc_attr__('Sidebar position','wonderflux'), array($this->admin_forms, 'wf_form_sidebar_p'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('page_t', esc_attr__('Page templates','wonderflux'), array($this->admin_forms, 'wf_form_p_template'), 'wonderflux_page_templates', 'style_lab_p_templates');
		add_settings_field('doc_type', esc_attr__('Document type','wonderflux'), array($this->admin_forms, 'wf_form_doc_type'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('doc_lang', esc_attr__('Document language','wonderflux'), array($this->admin_forms, 'wf_form_doc_lang'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('doc_charset', esc_attr__('Document character set','wonderflux'), array($this->admin_forms, 'wf_form_doc_charset'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('fb_admins', esc_attr__('Facebook ID(s)','wonderflux'), array($this->admin_forms, 'wf_form_fb_admins'), 'wonderflux_stylelab_fb', 'style_lab_fb');
		add_settings_field('fb_app', esc_attr__('Facebook Application ID','wonderflux'), array($this->admin_forms, 'wf_form_fb_app'), 'wonderflux_stylelab_fb', 'style_lab_fb');

	}


	/**
	* Checks in the nicest way possible what the latest version of Wonderflux is against installed version
	* No nasty business here or anywhere in Wonderflux, move on with a warm glow in your heart!
	* @since 0.911
	* @updated 0.931
	*/
	function wf_latest_version_fetch() {

		// Get WP feed functionality
		include_once(ABSPATH . WPINC . '/feed.php');

		// Every 2 hours
		$update = 7200;

		add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$update', 'return '.$update.';' ) );

		// Fetch feed
		$rss = fetch_feed( esc_url('http://feeds.feedburner.com/WonderfluxVersion') );

		if ( is_wp_error($rss) ) {

			echo '<p>' . esc_attr__('Sorry, update check not currently available.', 'wonderflux') . '</p>';

		} else {

			// How many?
			$items = 1;
			$rss_items = $rss->get_items( 0, $rss->get_item_quantity($items) );

			foreach ( $rss_items as $item ) {
				$this_update = $item->get_title();
				$this_update_out = esc_html( $this_update );
				return esc_attr($this_update_out);
			}// End foreach

		}

	}


	/**
	* Compares installed Wonderflux version with latest release available
	* @since 0.911
	* @updated 0.931
	*/
	function wf_latest_version_compare() {

		$this_version = WF_VERSION;
		$latest_version = $this->wf_latest_version_fetch();

		if ($latest_version > $this_version) {
			return esc_attr__('update', 'wonderflux');
		} elseif ($latest_version < $this_version) {
			return esc_attr__('development', 'wonderflux');
		} else {
			// Silence is golden
		}

	}


	/**
	* Creates update notice if required
	* @since 0.911
	* @updated 0.931
	*/
	function wf_latest_version_notice() {

		$check = wflux_admin::wf_latest_version_compare();

		if ($check == 'update') {

			$output = '<div id="message1" class="error">';
			$output .= '<h3>' . esc_attr__('Wonderflux framework update available!', 'wonderflux') . '</h3>';
			$output .= '<p>' . sprintf( __( 'You are running v%1$s, the current latest release is v%2$s', 'wonderflux' ), WF_VERSION, $this->wf_latest_version_fetch() ) . '</p>';

			$output .= '<p>' . esc_attr__('There is an update available to Wonderflux. Please read the update notes to check-up on how this may effect your theme BEFORE updating!', 'wonderflux') . '</p>';
			$output .= '<p>You can <a href="https://github.com/Jonnyauk/Wonderflux/releases/" title="'. esc_attr__('Download the latest Wonderflux update here', 'wonderflux') . '">' . esc_attr__('download the latest Wonderflux update here', 'wonderflux') . '</a>.</p>';
			$output .= '</div>';

			echo $output;

		} elseif ($check == 'development') {

			$output = '<div id="message1" class="error">';
			$output .= '<h3>' . esc_attr__('Development version installed', 'wonderflux') . '</h3>';
			$output .= '<p>' . esc_attr__('You are running a development version of Wonderflux, cool!', 'wonderflux') . ' <strong>' . esc_attr__('You should probably NOT be using this on live sites.', 'wonderflux') . '</strong></p>';
			$output .= '<p>' . esc_attr__('It may contain code thats not finished just yet, or new functionality that may conflict with your current Wonderflux child theme.', 'wonderflux') . '</p>';
			$output .= '<p><strong>' . esc_attr__('If you are not a developer, advanced designer or tester', 'wonderflux') . '</strong> ';
			$output .= sprintf( __( 'you will probably be better off using <a href="%1$s" title="%2$s">%3$s</a>', 'wonderflux' ), 'https://github.com/Jonnyauk/Wonderflux/releases/' , __('Download the latest stable Wonderflux release here', 'wonderflux'), esc_attr__('the latest stable version of Wonderflux.', 'wonderflux') );
			$output .= '</div>';

			echo $output;

		} else {
			// Silence is golden - user running current version!
		}

	}


 	/**
	* Contextual help
	* @since 0.92
	* @updated 1.2
	*/
	function wf_contextual_help() {

		$adv_help = '<p>';
		$adv_help .= '<a href="http://wonderflux.com/guide/constant/wf_theme_framework_replace/" title="'. esc_attr__('WF_THEME_FRAMEWORK_REPLACE documentation', 'wonderflux') . '" target="_blank">' . esc_attr__('WF_THEME_FRAMEWORK_REPLACE', 'wonderflux') . '</a>';
		$adv_help .= esc_attr__(' - A Wonderflux theme constant that removes the core stylesheet CSS files.', 'wonderflux');
		$adv_help .= '</p>';

		$style_help = '<p>' . esc_attr('It is currently possible to set layout sizes that do not work correctly in the layout cloumn/grid settings.', 'wonderflux') . '</p>';
		$style_help .= esc_attr('Try out the following common valid combinations:', 'wonderflux') . '</p>';
		$style_help .= '<p>'.esc_attr('width=960 x columns=16 x column width=45', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, quarter, eigth)', 'wonderflux') . '</p>';

		$style_help .= '<p>'.esc_attr('width=960 x columns=36 x column width=15', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, third, quarter, sixth, ninth, twelveth)', 'wonderflux') . '</li>';
		$style_help .= '<p>'.esc_attr('width=950 x columns=20 x column width=38', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, third, quarter, fifth, tenth)', 'wonderflux') . '</li>';
		$style_help .= '<p>'.esc_attr('width=950 x columns=24 x column width=30', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, third, quarter, sixth, eighth, twelveth)', 'wonderflux') . '</li>';
		$style_help .= '<p>'.esc_attr('width=950 x columns=48 x column width=10', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, third, quarter, sixth, eighth, twelveth)', 'wonderflux') . '</li>';
		$style_help .= '<p>'.esc_attr('width=760 x columns=24 x column width=24', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, third, quarter, sixth, eighth, twelveth)', 'wonderflux') . '</li>';
		$style_help .= '<p>'.esc_attr('width=760 x columns=20 x column width=19', 'wonderflux');
		$style_help .= '<br/>'.esc_attr('(valid suggested relative sizes: full, half, third, quarter, fifth, tenth)', 'wonderflux') . '</li>';

		$backup_help = '<p>' . esc_attr('Use this page to backup or restore your Wonderflux theme options.', 'wonderflux') . '</p>';

		$generic_help = '<p>';
		$generic_help .= '<a href="http://wonderflux.com/guide/" title="'. esc_attr__('Wonderflux documentation', 'wonderflux') . '" target="_blank">' . esc_attr__('http://wonderflux.com/guide', 'wonderflux') . '</a>';
		$generic_help .= esc_attr__(' - All Wonderflux documentation and user guides.', 'wonderflux');
		$generic_help .= '</p>';
		$generic_help .= '<h3>';
		$generic_help .= esc_attr__('Get involved!', 'wonderflux' );
		$generic_help .= '</h3>';
		$generic_help .= '<p>';
		$generic_help .= esc_attr__( 'A huge amount of resource has been poured into this project since it began in January 2010. If you have ideas on how Wonderflux could improve, why not think about contributing to the development of Wonderflux?', 'wonderflux' );
		$generic_help .= '</p>';
		$generic_help .= '<p><strong>';
		$generic_help .= esc_attr__( 'Remember, Wonderflux is free, open source code just like WordPress', 'wonderflux' );
		$generic_help .= '</strong>';
		$generic_help .= esc_attr__( ' - so your ideas can help make a great theme framework for the whole community to use. Some ideas on how you could help include bug hunting, documentation, javascript, optimisation - really any ideas you have would be more than welcome!', 'wonderflux' );
		$generic_help .= '</p>';
		$generic_help .= '<p>';
		$generic_help .= '<a href="https://github.com/Jonnyauk/Wonderflux" title="'. esc_attr__('GitHub code repository', 'wonderflux') . '" target="_blank">' . esc_attr__('GitHub code repository', 'wonderflux') . '</a>';
		$generic_help .= esc_attr__( '- Git development code repository (for developers and testers - NOT to be used on live sites!)', 'wonderflux' );
		$generic_help .= '<br />';
		$generic_help .= '<a href="https://github.com/Jonnyauk/Wonderflux/issues" title="'. esc_attr__('GitHub issue tracking list', 'wonderflux') . '" target="_blank">' . esc_attr__('GitHub issue tracking list', 'wonderflux') . '</a>';
		$generic_help .= esc_attr__( '- Development list (for contributors, bug reports and feature requests)', 'wonderflux' );
		$generic_help .= '<br />';
		$generic_help .= '<a href="http://wonderflux.com/guide" title="'. esc_attr__('Wonderflux documentation', 'wonderflux') . '" target="_blank">' . esc_attr__('Wonderflux documentation', 'wonderflux') . '</a>';
		$generic_help .= __( ' - Official documentation site (API documentation and quick start guides)', 'wonderflux' );

		$generic_help .= '</p>';

		$this_wfx_screen = get_current_screen();

		switch ($this_wfx_screen->id) {
			case 'appearance_page_wonderflux' : $this_help = '<h3>' . esc_attr__( 'Wonderflux Help - Main Options', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case 'admin_page_wonderflux_stylelab' : $this_help = '<h3>' . esc_attr__( 'Wonderflux Help - Stylelab', 'wonderflux' ) . '</h3>' . $style_help . $generic_help; break;
			case 'admin_page_wonderflux_advanced' : $this_help = '<h3>' . esc_attr__( 'Wonderflux Help - Advanced', 'wonderflux' ) . '</h3>' . $adv_help . $generic_help; break;
			case 'admin_page_wonderflux_system' : $this_help = '<h3>' . esc_attr__( 'Wonderflux Help - System', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case 'admin_page_wonderflux_backup' : $this_help = '<h3>' . esc_attr__( 'Wonderflux Help - Backup', 'wonderflux' ) . '</h3>' . $backup_help . $generic_help; break;
			default : return false;
		}

		return $this_help;
	}


 	/**
	* Controls access to the Wonderflux admin menus
	* @since 0.93
	* @updated 0.93
	*/
	function wf_admin_menus() {

		if ( WF_ADMIN_ACCESS == 'none' ) {
			// Silence is golden
		} else {

			$input = @unserialize(WF_ADMIN_ACCESS);
				// Check against serialised data
			if ($input === false) {

				// Must be single user role supplied
				if ( WF_ADMIN_ACCESS == wfx_user_role('') && current_user_can('manage_options') ) {
					// Build admin menus
					add_action('admin_menu', array($this, 'wf_add_pages'));
					// Setup options
					add_action( 'admin_init', array($this, 'wf_register_settings'));
					// Setup help
					add_filter('contextual_help', array($this, 'wf_contextual_help'), 10, 3);
				}

			} else {

				// Must be array of user ID's supplied
				global $current_user;
				get_currentuserinfo();
				foreach ($input as $key=>$user_id) {
					if ( $user_id == $current_user->ID && current_user_can('manage_options') ) {
						// Build admin menus
						add_action('admin_menu', array($this, 'wf_add_pages'));
						// Setup options
						add_action( 'admin_init', array($this, 'wf_register_settings'));
						// Setup help
						add_filter('contextual_help', array($this, 'wf_contextual_help'), 10, 3);
					}
				}

			}
		}

	}


	/**
	* Builds common help links for Wonderflux
	* @since 0.931
	* @updated 0.931
	*/
	function wf_common_help() {
		$items = array(
			'doc'=>esc_attr__('Quick start guides','wonderflux'),
			'hook'=>esc_attr__('Hook guides','wonderflux'),
			'function'=>esc_attr__('Function guides','wonderflux'),
			'filter'=>esc_attr__('Filter guides','wonderflux'),
			'constant'=>esc_attr__('Constant guides','wonderflux'),
			'file'=>esc_attr__('File guides','wonderflux')
		);

		$output = '<p><strong>';
		$output .= esc_attr__('Wonderflux help and documentation','wonderflux');
		foreach ($items as $key=>$value) {
			$output .= esc_attr__(' | ','wonderflux');
			$output .= '<a href="http://wonderflux.com/guide/' . $key . '/" title="' . esc_attr__('View all Wonderflux ','wonderflux');
			$output .= $value;
			$output .= esc_attr__(' (documented so far!)','wonderflux');
			$output .= '"> ';
			$output .= $value;
			$output .= '</a> ';
		}
		$output .= '</p>';
		return $output;
	}


}


/**
* @since 0.81
* @updated 2.0
* Admin form functions
*/
class wflux_admin_forms extends wflux_data {

	private $common_size;	// Common size definitions for dropdown
	private $valid;			// Hold array of options with whitelists and default values
	private $size_accept;	// Whitelist of size options

	function __construct() {

		parent::__construct();

		$this->common_size = array(
			array(esc_attr__('Full','wonderflux')=>'full'),
			array(esc_attr__('Half','wonderflux')=>'half'),
			array(esc_attr__('1 Third','wonderflux')=>'third'),
			array(esc_attr__('- 2 Thirds','wonderflux')=>'two_third'),
			array(esc_attr__('1 Quarter','wonderflux')=>'quarter'),
			array(esc_attr__('- 2 Quarters','wonderflux')=>'two_quarter'),
			array(esc_attr__('- 3 Quarters','wonderflux')=>'three_quarter'),
			array(esc_attr__('1 Fifth','wonderflux')=>'fifth'),
			array(esc_attr__('- 2 Fifths','wonderflux')=>'two_fifth'),
			array(esc_attr__('- 3 Fifths','wonderflux')=>'three_fifth'),
			array(esc_attr__('- 4 Fifths','wonderflux')=>'four_fifth'),
			array(esc_attr__('1 Sixth','wonderflux')=>'sixth'),
			array(esc_attr__('- 2 Sixths','wonderflux')=>'two_sixth'),
			array(esc_attr__('- 3 Sixths','wonderflux')=>'three_sixth'),
			array(esc_attr__('- 4 Sixths','wonderflux')=>'four_sixth'),
			array(esc_attr__('- 5 Sixths','wonderflux')=>'five_sixth'),
			array(esc_attr__('1 Seventh','wonderflux')=>'seventh'),
			array(esc_attr__('- 2 Sevenths','wonderflux')=>'two_seventh'),
			array(esc_attr__('- 3 Sevenths','wonderflux')=>'three_seventh'),
			array(esc_attr__('- 4 Sevenths','wonderflux')=>'four_seventh'),
			array(esc_attr__('- 5 Sevenths','wonderflux')=>'five_seventh'),
			array(esc_attr__('- 6 Sevenths','wonderflux')=>'six_seventh'),
			array(esc_attr__('1 Eigth','wonderflux')=>'eighth'),
			array(esc_attr__('- 2 Eigths','wonderflux')=>'two_eighth'),
			array(esc_attr__('- 3 Eigths','wonderflux')=>'three_eighth'),
			array(esc_attr__('- 4 Eigths','wonderflux')=>'four_eighth'),
			array(esc_attr__('- 5 Eigths','wonderflux')=>'five_eighth'),
			array(esc_attr__('- 6 Eigths','wonderflux')=>'six_eighth'),
			array(esc_attr__('- 7 Eigths','wonderflux')=>'seven_eighth'),
			array(esc_attr__('1 Ninth','wonderflux')=>'ninth'),
			array(esc_attr__('- 2 Ninths','wonderflux')=>'two_ninth'),
			array(esc_attr__('- 3 Ninths','wonderflux')=>'three_ninth'),
			array(esc_attr__('- 4 Ninths','wonderflux')=>'four_ninth'),
			array(esc_attr__('- 5 Ninths','wonderflux')=>'five_ninth'),
			array(esc_attr__('- 6 Ninths','wonderflux')=>'six_ninth'),
			array(esc_attr__('- 7 Ninths','wonderflux')=>'seven_ninth'),
			array(esc_attr__('- 8 Ninths','wonderflux')=>'eight_ninth'),
			array(esc_attr__('1 Tenth','wonderflux')=>'tenth'),
			array(esc_attr__('- 2 Tenths','wonderflux')=>'two_tenth'),
			array(esc_attr__('- 3 Tenths','wonderflux')=>'three_tenth'),
			array(esc_attr__('- 4 Tenths','wonderflux')=>'four_tenth'),
			array(esc_attr__('- 5 Tenths','wonderflux')=>'five_tenth'),
			array(esc_attr__('- 6 Tenths','wonderflux')=>'six_tenth'),
			array(esc_attr__('- 7 Tenths','wonderflux')=>'seven_tenth'),
			array(esc_attr__('- 8 Tenths','wonderflux')=>'eight_tenth'),
			array(esc_attr__('- 9 Tenths','wonderflux')=>'nine_tenth'),
			array(esc_attr__('1 Eleventh','wonderflux')=>'eleventh'),
			array(esc_attr__('- 2 Elevenths','wonderflux')=>'two_eleventh'),
			array(esc_attr__('- 3 Elevenths','wonderflux')=>'three_eleventh'),
			array(esc_attr__('- 4 Elevenths','wonderflux')=>'four_eleventh'),
			array(esc_attr__('- 5 Elevenths','wonderflux')=>'five_eleventh'),
			array(esc_attr__('- 6 Elevenths','wonderflux')=>'six_eleventh'),
			array(esc_attr__('- 7 Elevenths','wonderflux')=>'seven_eleventh'),
			array(esc_attr__('- 8 Elevenths','wonderflux')=>'eight_eleventh'),
			array(esc_attr__('- 9 Elevenths','wonderflux')=>'nine_eleventh'),
			array(esc_attr__('- 10 Elevenths','wonderflux')=>'ten_eleventh'),
			array(esc_attr__('1 Twelveth','wonderflux')=>'twelveth'),
			array(esc_attr__('- 2 Twelveths','wonderflux')=>'two_twelveth'),
			array(esc_attr__('- 3 Twelveths','wonderflux')=>'three_twelveth'),
			array(esc_attr__('- 4 Twelveths','wonderflux')=>'four_twelveth'),
			array(esc_attr__('- 5 Twelveths','wonderflux')=>'five_twelveth'),
			array(esc_attr__('- 6 Twelveths','wonderflux')=>'six_twelveth'),
			array(esc_attr__('- 7 Twelveths','wonderflux')=>'seven_twelveth'),
			array(esc_attr__('- 8 Twelveths','wonderflux')=>'eight_twelveth'),
			array(esc_attr__('- 9 Twelveths','wonderflux')=>'nine_twelveth'),
			array(esc_attr__('- 10 Twelveths','wonderflux')=>'ten_twelveth'),
			array(esc_attr__('- 11 Twelveths','wonderflux')=>'eleven_twelveth')
		);

		$this->size_accept = array('full', 'half', 'third', 'two_third', 'quarter', 'two_quarter', 'three_quarter', 'fifth', 'two_fifth', 'three_fifth', 'four_fifth', 'sixth', 'two_sixth', 'three_sixth', 'four_sixth', 'five_sixth', 'seventh', 'two_seventh', 'three_seventh', 'four_seventh', 'five_seventh', 'six_seventh', 'eighth', 'two_eighth', 'three_eighth', 'four_eighth', 'five_eighth', 'six_eighth', 'seven_eighth', 'ninth', 'two_ninth', 'three_ninth', 'four_ninth', 'five_ninth', 'six_ninth', 'seven_ninth', 'eight_ninth', 'tenth', 'two_tenth', 'three_tenth', 'four_tenth', 'five_tenth', 'six_tenth', 'seven_tenth', 'eight_tenth', 'nine_tenth', 'eleventh', 'two_eleventh', 'three_eleventh', 'four_eleventh', 'five_eleventh', 'six_eleventh', 'seven_eleventh', 'eight_eleventh', 'nine_eleventh', 'ten_eleventh', 'twelveth', 'two_twelveth', 'three_twelveth', 'four_twelveth', 'five_twelveth', 'six_twelveth', 'seven_twelveth', 'eight_twelveth', 'nine_twelveth', 'ten_twelveth', 'eleven_twelveth');

		// First value of each array is used as default value if no whitelisted value supplied
		// If no value supplied, input is free text value and is deep cleaned instead!
		$this->valid = array(
			'doc_type'		=> array ('transitional','strict','frameset','1.1','1.1basic','html5','XHTML/RDFa'),
			'doc_lang'		=> array ('aa','ab','ae','af','ak','am','an','ar','as','av','ay','az','ba','be','bg','bh','bi','bm','bn','bo','bo','br','bs','ca','ce','ch','co','cr','cs','cs','cu','cv','cy','cy','da','de','de','dv','dz','ee','el','el','en','eo','es','et','eu','eu','fa','fa','ff','fi','fj','fo','fr','fr','fy','ga','gd','gl','gn','gu','gv','ha','he','hi','ho','hr','ht','hu','hy','hy','hz','ia','id','ie','ig','ii','ik','io','is','is','it','iu','ja','jv','ka','ka','kg','ki','kj','kk','kl','km','kn','ko','kr','ks','ku','kv','kw','ky','la','lb','lg','li','ln','lo','lt','lu','lv','mg','mh','mi','mi','mk','mk','ml','mn','mr','ms','ms','mt','my','my','na','nb','nd','ne','ng','nl','nl','nn','no','nr','nv','ny','oc','oj','om','or','os','pa','pi','pl','ps','pt','qu','rm','rn','ro','ro','ru','rw','sa','sc','sd','se','sg','si','sk','sk','sl','sm','sn','so','sq','sq','sr','ss','st','su','sv','sw','ta','te','tg','th','ti','tk','tl','tn','to','tr','ts','tt','tw','ty','ug','uk','ur','uz','ve','vi','vo','wa','wo','xh','yi','yo','za','zh','zh','zu'),
			'doc_charset'	=> array ('UTF-8','UTF-16','ISO-2022-JP','ISO-2022-JP-2','ISO-2022-KR','ISO-8859-1','ISO-8859-10','ISO-8859-15','ISO-8859-2','ISO-8859-3','ISO-8859-4','ISO-8859-5','ISO-8859-6','ISO-8859-7','ISO-8859-8','ISO-8859-9'),
			'container_p'	=> array ('left','middle','right'),
			'content_s'		=> $this->size_accept,
			'content_s_px'=> array ( 600, range(200,1200,2) ),
			'sidebar_s'		=> $this->size_accept,
			'sidebar_p'		=> array ('left','right'),
			'grid_type'		=> array ('percent','pixels'),
			'sidebar_d'		=> array ('Y','N'),
			'container_u'	=> array ('percent','pixels'),
			'container_w'	=> ( $this->wfx_width_unit == 'pixels' ) ? array ( 950, range(400,2000,10) ) : array ( 80, range(5,100,5) ),
			'columns_num'	=> array ( 24, range(2,100,1) ),
			'columns_w'		=> array ( 30, range(10,200,1) ),
			'page_t'		=> array ( '','no-sidebar' ),
			'fb_admins'		=> '',
			'fb_app'		=> '',
		);

	}


	/**
	* Callback function for all option validation.
	* @param $input - Array of options.
	* @return - Array of safe options.
	* @since 0.912
	* @updated 0.931
	*/
	function validate_opts_layout($input) {

		// One callback function for all - so load data if exists to merge, not over-write
		$db_ops = (is_array(get_option('wonderflux_display')) ) ? get_option('wonderflux_display') : array(false);
		$new_ops = array();

		foreach ( $this->valid as $op_type=>$values ) {
			if ( isset($input[$op_type]) ):
				if ( empty($values) ): $new_ops[$op_type] = wp_kses( $input[$op_type], '' );
				else:
					$whitelist = ( is_array($values[1]) ) ? $values[1] : $this->valid[$op_type];
					if ( in_array($input[$op_type], $whitelist) ): $new_ops[$op_type] = $input[$op_type];
					else: $new_ops[$op_type] = $values[0]; // Cheatin huh - not this time buddy!
					endif;
				endif;
			endif;
		}

		return array_merge( (array)$db_ops, (array)$new_ops );

	}

	//////// STYLE LAB FORM ITEMS

	function wf_form_intro_grid_core() {
		echo '<h2>' . esc_attr__('CSS grid/column core configuration','wonderflux') . '</h2>';
		echo '<div class="clear"></div>';
		echo '<p><strong>' . esc_attr__('IMPORTANT: Core configuration options must be saved first before configuring other options.', 'wonderflux') . '</strong></p>';
		echo '<p>' . esc_attr__('Use percent for responsive (Wonderflux v2) system or pixels for legacy non-responsive (Wonderflux v1) system.', 'wonderflux') . '</p>';
	}

	function wf_form_intro_grid() {
		echo '<h2>' . esc_attr__('CSS grid/column settings','wonderflux') . '</h2>';
		echo '<div class="clear"></div>';
		echo '<p>' . esc_attr__('Setup the dimensions of the CSS layout columns (grid system).', 'wonderflux') . '</p>';
	}

	function wf_form_intro_main() {
		echo '<h2>' . esc_attr__('Main content and sidebar settings','wonderflux') . '</h2>';
		echo '<div class="clear"></div>';
		echo '<p>' . esc_attr__('Setup the dimensions of your main content area and sidebar.','wonderflux') . '</p>';
	}

	function wf_form_intro_p_templates() {
		echo '<h2>' . esc_attr__('Wonderflux core page templates','wonderflux') . '</h2>';
		echo '<div class="clear"></div>';
		echo '<p>' . esc_attr__('Tick to hide the specific page template if it does not suit your child theme (it will be removed from page template dropdown option.)','wonderflux') . '</p>';
	}

	function wf_form_grid_type() { $this->wf_form_helper_ddown_std($this->wfx_grid_type,'grid_type',$this->valid['grid_type'],''); }

	function wf_form_container_p() { $this->wf_form_helper_ddown_std($this->wfx_position,'container_p', $this->valid['container_p'],''); }
	function wf_form_content_s() { $this->wf_form_helper_ddown_std($this->wfx_content_1_size,'content_s', $this->common_size,''); }
	function wf_form_content_s_px() { $this->wf_form_helper_ddown_range($this->wfx_content_size_px,'content_s_px',200,1200,2,''); }
	function wf_form_sidebar_s() { $this->wf_form_helper_ddown_std($this->wfx_sidebar_1_size,'sidebar_s', $this->common_size,''); }
	function wf_form_sidebar_d() { $this->wf_form_helper_ddown_std($this->wfx_sidebar_1_display,'sidebar_d',array(array('yes'=>'Y'), array('no'=>'N')),''); }
	function wf_form_sidebar_p() { $this->wf_form_helper_ddown_std($this->wfx_sidebar_primary_position,'sidebar_p',$this->valid['sidebar_p'],''); }
	function wf_form_container_u() { $this->wf_form_helper_ddown_std($this->wfx_width_unit,'container_u',array('percent','pixels'),''); }
	function wf_form_columns_num() { $this->wf_form_helper_ddown_range($this->wfx_columns,'columns_num',2,100,1,''); }
	function wf_form_columns_w() { $this->wf_form_helper_ddown_range($this->wfx_columns_width,'columns_w',10,200,1,''); }
	function wf_form_p_template() { $this->wf_form_helper_cbox($this->wfx_page_templates,'page_t', $this->valid['page_t'],''); }
	function wf_form_doc_type() { $this->wf_form_helper_ddown_std($this->wfx_doc_type,'doc_type',$this->valid['doc_type'],''); }
	function wf_form_doc_lang() { $this->wf_form_helper_ddown_std($this->wfx_doc_lang,'doc_lang',$this->valid['doc_lang'],''); }
	function wf_form_doc_charset() { $this->wf_form_helper_ddown_std($this->wfx_doc_charset,'doc_charset',$this->valid['doc_charset'],''); }

	// Facebook
	function wf_form_intro_doc() {
		$output = '<h2>' . esc_attr__( "Document output", "wonderflux" ) . '</h2>';
		$output .= '<div class="clear"></div>';
		$output .= '<p>';
		$output .= esc_attr__("Use these controls to set avanced document type and language attributes.","wonderflux");
		$output .= '<br /><strong>' . esc_attr__("WARNING - ","wonderflux") . '</strong>';
		$output .= esc_attr__("These settings should generally be left as default (transitional, en, UTF-8).","wonderflux") . '</p>';
		$output .= '<p><strong>' . esc_attr__("If you are using the Facebook features, you must use document type XHTML/RDFa.","wonderflux") . '</strong></p>';
		echo $output;
	}

	function wf_form_intro_fb() {
		$output = '<h2>' . esc_attr__( "Facebook connect", "wonderflux" ) . '</h2>';
		$output .= '<div class="clear"></div>';
		$output .= '<p>' . esc_attr__("Connect your site with Facebook to allow advanced interaction and sharing. Required if you are using the Facebook share display function. ","wonderflux");
		$output .= esc_attr__("You can assign multiple Facebook ID&rsquo;s by comma seperation (name1,name2) and a single (optional) application ID. ","wonderflux");
		$output .= '<strong>' . esc_attr__("NOTE - You are required to fill-in at-least your Facebook ID to use the Facebook sharing features. ","wonderflux") . '</p></strong>';
		$output .= '<p>' . esc_attr__("Get your Facebook ID number by logging into Facebook, and clicking on the 'profile' tab. If you look at the URL in the address bar, you will see something like: ","wonderflux");
		$output .= '<br />' . esc_attr__("http://www.facebook.com/profile.php?id=123456789012345","wonderflux");
		$output .= '<br />' . esc_attr__("The string of numbers after 'id=' is your Facebook ID number - copy and paste this into the filed below. ","wonderflux");
		$output .= esc_attr__("Application ID is optional (advanced users). ","wonderflux");
		$output .= '<a href="http://developers.facebook.com/setup/" title="' . esc_attr__("Login to Facebook to get your application ID if required.","wonderflux") . '">' . esc_attr__("Get your application ID if required","wonderflux") . '</a>. ';
		$output .= '<p><strong>' . esc_attr__("IMPORTANT - When entering and using any of these details, your site document type is automatically switched to XHTML/RDFa. ","wonderflux") . '</strong>';
		$output .= esc_attr__("Relevant Facebook and Open Graph meta tags are generated and XML namespace attributes are added. Whilst active, it is manditory to keep the document type as XHTML/RDFa. This ensures that your site will still produce valid code, enable XFBML and advanced loading, along with improved compatibility with Internet Explorer.","wonderflux") . '</strong></p>';
		echo $output;
	}

	function wf_form_fb_admins() { $this->wf_form_helper_text($this->wfx_fb_admins,'fb_admins'); }
	function wf_form_fb_app() { $this->wf_form_helper_text($this->wfx_fb_app,'fb_app'); }

	function wf_form_container_w() {

		if ( $this->wfx_width_unit == 'pixels' ) {
			$this->wf_form_helper_ddown_range($this->wfx_width,'container_w',400,2000,10,'');
		} else {
			$this->wf_form_helper_ddown_range($this->wfx_width,'container_w',5,100,5,'');
		}

	}

	/**
	* Creates a dropdown for options page
	* @since 0.931
	* @updated 0.931
	*/
	function wf_form_helper_text( $data,$definition ){
		echo "<input id='wonderflux_display[".esc_attr($definition)."]' name='wonderflux_display[".esc_attr($definition)."]' size='40' type='text' value='".esc_textarea($data)."' />";
	}


	/**
	* Creates a dropdown for options page
	* IMPORTANT: Defaults for content1 and sidebar1 set here if no database options saved
	* @since 0.81
	* @updated 1.2
	*/
	function wf_form_helper_ddown_std( $data, $definition, $items, $label_note ) {
		echo "<select id='wonderflux_display_".esc_attr($definition)."' name='wonderflux_display[".$definition."]'>";
		foreach( $items as $key=>$value ) {
			if ( is_array( $value ) ) {
				foreach( $value as $key=>$value ) {
					$selected = ( $value == $data ) ? 'selected="selected"' : '';
					// Check if no value saved and set appropriate option for sidebar1 size
					if ( !$data && $definition == 'sidebar_s' && $value == 'quarter' ) {
						echo "<option value='$value' selected='selected'>$key</option>";
					// Check if no value saved and set appropriate option for content1 size
					} elseif ( !$data && $definition == 'content_s' && $value == 'three_quarter' ) {
						echo "<option value='$value' selected='selected'>$key</option>";
					} else {
						echo "<option value='$value' $selected>$key</option>";
					}
				}
			} else {
				$selected = ( $value==$data ) ? 'selected="selected"' : '';
				echo "<option value='$value' $selected>$value</option>";
			}
		}
		echo "</select>";
		echo ( !empty($label_note) ) ? ' ' . esc_html( $label_note ) : '';
		echo "\n";
	}


	/**
	* Creates a dropdown for options page populated with range of numbers
	* @since 0.81
	* @updated 1.2
	*/
	function wf_form_helper_ddown_range($data, $definition, $low, $high, $step, $label_note) {
		$items = range($low,$high,$step);
		echo "<select id='wonderflux_display_".esc_attr($definition)."' name='wonderflux_display[".$definition."]'>";
		foreach($items as $item) {
			$selected = ($item==$data) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select>";
		echo ( !empty($label_note) ) ? ' ' . esc_html( $label_note ) : '';
		echo "\n";
	}


	/**
	* Creates checkboxes
	* @since 2.0
	* @updated 2.0
	*/
	function wf_form_helper_cbox($data, $definition, $valid, $label_note) {

		$output = '';
		foreach ( $valid as $key => $val ) {

			if ( !empty($val) ){
				// Use empty field to detect no value saved
				$output .= '<input type="hidden" name="wonderflux_display['.$definition.']" value="">';
				$output .= '<label for="' . esc_attr( 'multiple_checkboxes' . '_' . $key )
				. '" class="checkbox_multi"><input type="checkbox" '
				. checked( ( $data == $val ), true, false ) . ' name="' . esc_attr( 'wonderflux_display[page_t]' )
				. '" value="' . esc_attr( $val ) . '" id="' . esc_attr( $definition . '_' . $key ) . '" /> ' . $val . '</label> ';
			}

		}
		echo $output;

	}


	/**
	* Creates a text area populated with CSS grid output
	* For Wonderflux v1.x pixel grid system
	* @since 0.93
	* @updated 1.1
	*/
	function wf_form_helper_file_css_combine($file,$cleanup) {

		$cleanup = ($cleanup == 'Y') ? 'Y' : 'N';
		$file_accept = array ('css/wf-css-core-structure.css');
		if (in_array ($file,$file_accept)) { $file = WF_CONTENT_DIR . '/' . $file; } else { $file = WF_CONTENT_DIR . '/css/wf-css-core-structure.css'; }

		$content = '';
		$f = fopen($file, 'r');
		$content = fread($f, filesize($file));

		// Remove white space
		$content = ( $cleanup == 'Y' ) ? preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content) : $content;

		//$content_grid = esc_textarea( $this->wf_css_framework_build() );

		$content_grid = ( $cleanup == 'Y' ) ? preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $this->wf_css_framework_build()) : $this->wf_css_framework_build();

		$output = '<h2>' . esc_attr__( "Override Wonderflux CSS files", "wonderflux" ) . '</h2>';
		$output .= '<p>' . esc_attr__( "Advanced users may wish to remove the default stylesheets that are usually inserted for you when using Wonderflux. The code below is all of the Wonderflux framework CSS file content that is normally inserted into the <head> of your site output, using your current saved configuration.", "wonderflux" ) . '</p>';
		$output .= '<p>' . esc_attr__( "By setting the constant in your child theme functions.php file with the single line:", "wonderflux" );
		$output .= ' <strong>' . 'define( \'WF_THEME_FRAMEWORK_REPLACE\', true);' . '</strong> ';
		$output .= esc_attr__( "you will remove the Wonderflux css files: 'wf-css-core-structure', 'wf-css-dynamic-columns' and the conditional 'wf-css-dynamic-core-ie'. ", "wonderflux" );
		$output .= esc_attr__( "When you set ", "wonderflux" );
		$output .= '<strong>WF_THEME_FRAMEWORK_REPLACE</strong> ';
		$output .= esc_attr__("to ","wonderflux");
		$output .= '<strong>true</strong> ';
		$output .= esc_attr__( "Wonderflux automatically inserts style-framework.css and style-framework-ie.css for you, with the IE file wrapped in a conditional comment, before loading your main theme CSS file.", "wonderflux" ) . '</p>';
		$output .= '<p><strong>' . esc_attr__( "IMPORTANT - you will need to create 2 new files in your theme directory to use this functionality.", "wonderflux" ) . '</strong><br />';
		$output .= esc_attr__( "1 - Copy and paste the code from BOX 1 into a file called 'style-framework.css' and save it to your child theme directory.", "wonderflux" ) . '<br />';
		$output .= esc_attr__( "2 - Copy and paste the code from BOX 2 into a file called 'style-framework-ie.css' and save it to your child theme directory.", "wonderflux" ) . '</p>';
		$output .= '<p><strong>' . esc_attr__( "IMPORTANT - If you are using the override files as described and change your grid configuration or layout options, please revisit this page and update your files accordingly.", "wonderflux" ) . '</strong></p>';

		$output .= '<h3>' . esc_attr__( "1 - Code for your style-framework.css file", "wonderflux" ) . '</h3>';
		$output .= '<form name="form1" method="post" action="" >';
		$output .= '<textarea cols="100" rows="20" name="newcontent" id="css-wfx-framework" tabindex="1" onclick="this.select()">'.esc_textarea($content.$content_grid).'</textarea>';

		$content_ie = $this->wf_css_framework_build_ie();

		$output .= '<h3>' . esc_attr__( "2 - Code for your style-framework-ie.css file", "wonderflux" ) . '</h3>';
		$output .= '<textarea cols="100" rows="20" name="newcontent2" id="css-wfx-framework-ie" tabindex="2" onclick="this.select()">'.esc_textarea($content_ie).'</textarea>';
		$output .= '</form>';
		echo $output;
	}


	/**
	* Creates a text area populated with CSS grid output
	* For Wonderflux v2.x responsive grid system
	* @since 2.0
	* @updated 2.0
	*/
	function wf_form_helper_file_css_combine_2($file,$cleanup) {

		$cleanup = ($cleanup == 'Y') ? 'Y' : 'N';
		$file_accept = array ('css/wf-css-flux-layout-core.css');
		if (in_array ($file,$file_accept)) { $file = WF_CONTENT_DIR . '/' . $file; } else { $file = WF_CONTENT_DIR . '/css/wf-css-flux-layout-core.css'; }

		// Core
		$content = '';
		$content = fread( fopen($file, 'r'), filesize($file) );
		// Remove comments
		$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
		// Remove white space
		$content = ( $cleanup == 'Y' ) ? preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content) : $content;

		// Grid framework
		$content_grid = '';

		// Get grid system with user params
		ob_start();
		$_GET['w'] = $this->wfx_width;
		$_GET['wu'] = $this->wfx_width_unit;
		$_GET['p'] = $this->wfx_position;
		$_GET['sbp'] = $this->wfx_sidebar_primary_position;
		$_GET['c'] = $this->wfx_columns;
		// IMPORTANT - Only used here - need to remove header CSS file info to import correctly
		$_GET['export_raw']=true;

    	include ( WF_CONTENT_DIR . '/css/wf-css-flux-layout.php' );

    	$content_grid = ob_get_clean();
		// Remove comments
		$content_grid = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content_grid);
		// Remove white space
		$content_grid = ( $cleanup == 'Y' ) ? preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $content_grid) : $content_grid;

		$output = '<h2>' . esc_attr__( "Override Wonderflux CSS files", "wonderflux" ) . '</h2>';
		$output .= '<p>' . esc_attr__( "Advanced users may wish to remove the default stylesheets that are usually inserted for you when using Wonderflux. The code below is all of the Wonderflux framework CSS file content that is normally inserted into the <head> of your site output, using your current saved configuration.", "wonderflux" ) . '</p>';
		$output .= '<p>' . esc_attr__( "By setting the constant in your child theme functions.php file with the single line:", "wonderflux" );
		$output .= ' <strong>' . 'define( \'WF_THEME_FRAMEWORK_REPLACE\', true);' . '</strong> ';
		$output .= esc_attr__( "you will remove the Wonderflux css files: 'wf-css-flux-layout-core' and 'wf-css-flux-layout'. ", "wonderflux" );
		$output .= esc_attr__( "Wonderflux then automatically inserts the file 'flux-layout-merged.css' for you (if it exists in your child theme), before loading your main child theme style.css file.", "wonderflux" ) . '</p>';
		$output .= '<p><strong>' . esc_attr__( "IMPORTANT - you will need to create 1 new file in your theme directory to use this functionality.", "wonderflux" ) . '</strong><br />';
		$output .= esc_attr__( "1 - Copy and paste the code from the text box below into a new file called 'flux-layout-merged.css' and save it to your child theme directory.", "wonderflux" ) . '<br />';
		$output .= '<p><strong>' . esc_attr__( "IMPORTANT - If you are using the override files as described and change your grid configuration or layout options, please revisit this page and update your files accordingly with the new generated code.", "wonderflux" ) . '</strong></p>';

		$output .= '<h3>' . esc_attr__( "Code for your flux-layout-merged.css file", "wonderflux" ) . '</h3>';
		$output .= '<form name="form1" method="post" action="" >';
		$output .= '<textarea cols="100" rows="20" name="newcontent" id="css-wfx-framework" tabindex="1" onclick="this.select()">'.esc_textarea(trim($content.$content_grid)).'</textarea>';

		echo $output;
	}


	/**
	* Creates the grid output
	* @since 0.93
	* @updated 0.931
	*/
	function wf_css_framework_build() {

		$css_output = "\n/*" . esc_attr__( "GRID CONFIGURATION", "wonderflux" ) . "*/\n";

		//Work out gutter
		$wf_grid_gutter = ($this->wfx_width - ($this->wfx_columns * $this->wfx_columns_width)) / ($this->wfx_columns - 1);

		// 1 Sets up main container

		$wf_grid_container = ".container { ";
		$wf_grid_container .= "width: " . $this->wfx_width;
		$wf_grid_container .= "px; ";

		switch ($this->wfx_position) {
			case 'left' : $wf_grid_container .= "margin: 0 auto 0 0; } "; break;
			case 'right' : $wf_grid_container .= "margin: 0 0 0 auto; } "; break;
			default : $wf_grid_container .= "margin: 0 auto; } "; break;
		}

		$css_output .=  $wf_grid_container . "\n";

		// 2 Sets up basic grid floating and margin

		for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$this->wfx_columns; $wf_grid_columnlimit++)
			{
			$css_output .= "div.span-" . $wf_grid_columnlimit;
				if ($wf_grid_columnlimit == $this->wfx_columns) { } else { $css_output .= ", "; }
			}

		$css_output .= " { float: left; margin-right: " . $wf_grid_gutter . "px; } ";

		// The last column in a row needs this class
		$css_output .= ' '.'.last, div.last { margin-right: 0; }' . "\n";

		// 3 Main column setup

		$grid_out = '';
		$wf_grid_columnwidth_outeach = $this->wfx_columns_width;
		for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$this->wfx_columns; $wf_grid_columnlimit++)
			{
			$wf_grid_maincols = ".span-" . $wf_grid_columnlimit . " { width: ";
			$wf_grid_maincols .= $wf_grid_columnwidth_outeach;
			$wf_grid_maincols .= "px; ";

			// If it's the last one, we need to add this last bit of CSS
			if ($wf_grid_columnlimit == $this->wfx_columns) { $wf_grid_maincols .= "margin-right: 0; "; }

			$wf_grid_maincols .= "}";
			$wf_grid_maincols .= "\n";

			$css_output .= $wf_grid_maincols;

			$wf_grid_columnwidth_outeach = $wf_grid_columnwidth_outeach+$this->wfx_columns_width + $wf_grid_gutter;
			}

		// 4 Appends

		$css_output .= $this->css_append_prepend('append');

		// 5 Prepends

		$css_output .= $this->css_append_prepend('');


		// 6 Pull

		// Use these classes on an element to pull it into a previous column
		$wf_grid_pullwidtheach = $this->wfx_columns_width + $wf_grid_gutter;
		for ($wf_grid_pullimit=1; $wf_grid_pullimit <= $this->wfx_columns; $wf_grid_pullimit++)
			{
			$wf_grid_mainpull = ".pull-" . $wf_grid_pullimit . " { margin-left: -";
			$wf_grid_mainpull .= $wf_grid_pullwidtheach - $wf_grid_gutter;
			$wf_grid_mainpull .= "px; }";
			$wf_grid_mainpull .= "\n";

			$css_output .= $wf_grid_mainpull;

			$wf_grid_pullwidtheach = ($wf_grid_pullwidtheach+$this->wfx_columns_width) + $wf_grid_gutter;
			}

		// 7 more pull class

		// More CSS for pull class
		for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$this->wfx_columns; $wf_grid_columnlimit++)
			{
			$css_output .= ".pull-" . $wf_grid_columnlimit;

			if ($wf_grid_columnlimit == $this->wfx_columns) { } else { $css_output .= ", "; }

			$wf_grid_columnwidth_outeach = $wf_grid_columnwidth_outeach+$this->wfx_columns_width;
			}
		$css_output .= " { float: left; position: relative; } " . "\n";

		// 8 Push

		$wf_grid_pushwidtheach = $this->wfx_columns_width + $wf_grid_gutter;
		for ($wf_grid_pushlimit=1; $wf_grid_pushlimit <= $this->wfx_columns; $wf_grid_pushlimit++)
			{

			$wf_grid_mainpush = ".push-" . $wf_grid_pushlimit . " { margin: 0 -";
			$wf_grid_mainpush .= $wf_grid_pushwidtheach;
			$wf_grid_mainpush .= "px ";
			$wf_grid_mainpush .= "1.5em ";
			$wf_grid_mainpush .= $wf_grid_pushwidtheach;
			$wf_grid_mainpush .= "px ";
			$wf_grid_mainpush .= "}";
			$wf_grid_mainpush .= "\n";

			$css_output .= $wf_grid_mainpush;

			$wf_grid_pushwidtheach = ($wf_grid_pushwidtheach+$this->wfx_columns_width) + $wf_grid_gutter;
			}

		// 9 Content
		// By default the content will be on the left and sidebar right
		// By floating the content right it puts the content on the right and sidebar left
		// Only need to check against left - which needs the content float right!
		if ($this->wfx_sidebar_primary_position =='left') {
			$wf_grid_layout = '#content { float: right; margin-right: 0; }';
		} else {
			$wf_grid_layout = '';
			// Silence is golden... until we have a second sidebar option
		}

		$css_output .= $wf_grid_layout;
		return esc_textarea($css_output);

	}

	/**
	* Helper to create some of the CSS output (append and prepend CSS rules)
	* @since 0.93
	* @updated 0.93
	*/
	function css_append_prepend($definition) {

		$wf_grid_gutter = ($this->wfx_width - ($this->wfx_columns * $this->wfx_columns_width)) / ($this->wfx_columns - 1);

		$definition = ($definition == 'append') ? 'append' : 'prepend';

		$output = '';

		$wf_grid_appendwidtheach = $this->wfx_columns_width + $wf_grid_gutter;
		for ($wf_grid_applimit=1; $wf_grid_applimit <= ($this->wfx_columns - 1); $wf_grid_applimit++)
			{
			$wf_grid_mainapp = ".".$definition."-" . $wf_grid_applimit . " { padding-right: ";
			$wf_grid_mainapp .= $wf_grid_appendwidtheach;
			$wf_grid_mainapp .= "px; }";
			$wf_grid_mainapp .= "\n";

			$output .= $wf_grid_mainapp;

			$wf_grid_appendwidtheach = ($wf_grid_appendwidtheach+$this->wfx_columns_width) + $wf_grid_gutter;
			}

		return $output;
	}


	/**
	* Creates the grid output for IE conditional support
	* @since 0.93
	* @updated 0.931
	*/
	function wf_css_framework_build_ie() {

		$output = '/** * ' . esc_attr__( " Wonderflux theme framework dynamic column core (legacy IE support) * http://wonderflux.com * @package Wonderflux * @since Wonderflux 0.2", "wonderflux" ) . ' */ ';
		$output .= 'body { text-align: ' . $this->wfx_position . '; } ';
		$output .= '.container { text-align: left; } ';

		$output .= '* html .column, ';
		for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$this->wfx_columns; $wf_grid_columnlimit++)
		{
			$wf_grid_maincols = "* html .span-".$wf_grid_columnlimit;

		if ($wf_grid_columnlimit==$this->wfx_columns) {
			//Last one
			$wf_grid_maincols .= ' { display:inline; overflow-x: hidden; }';
		} else {
			$wf_grid_maincols .= ", ";
		}
		$output .= $wf_grid_maincols;
		}

		$output .= '/** Elements **/ ';
		$output .= ' /* Fixes incorrect styling of legend in IE6. */ ';
		$output .= '* html legend { margin:0px -8px 16px 0; padding:0; } ';
		$output .= ' /* Fixes incorrect placement of ol numbers in IE6/7. */ ';
		$output .= 'ol { margin-left:2em; } ';
		$output .= ' /* Fixes wrong line-height on sup/sub in IE. */ ';
		$output .= 'sup { vertical-align:text-top; } ';
		$output .= 'sub { vertical-align:text-bottom; } ';
		$output .= ' /* Fixes IE7 missing wrapping of code elements. */ ';
		$output .= 'html>body p code { *white-space: normal; } ';
		$output .= ' /* IE 6&7 has problems with setting proper <hr> margins. */' ;
		$output .= 'hr { margin:-8px auto 11px; } ';
		$output .= ' /* Explicitly set interpolation, allowing dynamically resized images to not look horrible */ ';
		$output .= 'img { -ms-interpolation-mode:bicubic; } ';
		$output .= ' /** Clearing **/ ';
		$output .= ' /* Makes clearfix actually work in IE */ ';
		$output .= '.clearfix, .container { display:inline-block; } ';
		$output .= '* html .clearfix, * html .container { height:1%; } ';
		$output .= ' /** Forms **/';
		$output .= ' /* Fixes padding on fieldset */ ';
		$output .= 'fieldset { padding-top:0; } ';
		$output .= ' /* Makes classic textareas in IE 6 resemble other browsers */';
		$output .= 'textarea { overflow:auto; } ';
		$output .= ' /* Fixes rule that IE 6 ignores */ ';
		$output .= 'input.text, input.title, textarea { background-color:#fff; border:1px solid #bbb; } ';
		$output .= 'input.text:focus, input.title:focus { border-color:#666; } ';
		$output .= 'input.text, input.title, textarea, select { margin:0.5em 0; } ';
		$output .= 'input.checkbox, input.radio { position:relative; top:.25em; } ';
		$output .= ' /* Fixes alignment of inline form elements */ ';
		$output .= 'form.inline div, form.inline p { vertical-align:middle; } ';
		$output .= 'form.inline label { position:relative;top:-0.25em; } ';
		$output .= 'form.inline input.checkbox, form.inline input.radio, form.inline input.button, form.inline button { margin:0.5em 0; } ';
		$output .= 'button, input.button { position:relative;top:0.25em; }';
		return $output;
	}


//END wflux_admin_forms class
}


/**
 * @since 1.2
 * @updated 1.2
 * Admin area theme backup functions
 */
class wflux_admin_backup {


	function wf_import_export() {
		//$reporting = '&backuperror=true';
		if (isset($_GET['action']) && ($_GET['action'] == 'download')) {
			header("Cache-Control: public, must-revalidate");
			header("Pragma: hack");
			header("Content-Type: text/plain");
			header('Content-Disposition: attachment; filename="wonderflux-theme-options-'.date("dSMY").'.dat"');
			echo serialize($this->_get_options());
			die();
		}
		if (isset($_POST['upload']) && check_admin_referer('wfx_options_backuprestore', 'wfx_options_backuprestore')) {
			if ($_FILES["file"]["error"] > 0) {
				$reporting = '&backuperror=true';
			} else {
				$options = unserialize(file_get_contents($_FILES["file"]["tmp_name"]));
				if ($options) {
					foreach ($options as $option) {
						if ($option->option_name == 'wonderflux_display') {
							update_option($option->option_name, unserialize($option->option_value));
							$reporting = '&backupsuccess=true';
						} else {
							$reporting = '&backuperror=true';
						}

					}
				}
			}
			wp_redirect(admin_url('admin.php?page=wonderflux_backup'.$reporting));
			exit;
		}
	}


	function wf_backup_form() {


		echo '<form action="" method="POST" enctype="multipart/form-data">';
			echo '<style>#backup-options td { display: block; margin-bottom: 20px; }</style>';
			echo '<table id="backup-options">';
				echo '<tr><td>';
						echo '<h3>Backup/Export</h3>';
						echo '<p>Current saved settings for the Wonderflux theme framework:</p>';
						echo '<p><textarea class="widefat code" rows="20" cols="100" onclick="this.select()">'. serialize($this->_get_options()) . '</textarea></p>';
						echo '<p><a href="?page=wonderflux_backup&action=download" class="button-secondary">Download as file</a></p>';
					echo '</td><td>';
						echo '<h3>Restore/Import</h3>';
						echo '<p><label class="description" for="upload">Restore a previous backup</label></p>';
						echo '<p><input type="file" name="file" /> <input type="submit" name="upload" id="upload" class="button-primary" value="Upload file" /></p>';
						wp_nonce_field('wfx_options_backuprestore', 'wfx_options_backuprestore');
					echo '</td></tr>';
			echo '</table>';
		echo '</form>';

	}


	function _display_options() {
		$options = unserialize($this->_get_options());
	}


	function _get_options() {
		global $wpdb;
		return $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = 'wonderflux_display'");
	}


//END wflux_admin_backup class
}


/**
* @since 2.0
* @updated 2.0
* Admin post control
*/
class wflux_admin_post extends wflux_data {

	/**
	 *
	 * Used to filter out unwanted page templates from page attributes dropdown
	 *
	 * @since 2.0
	 * @updated 2.0
	 *
	 */
	function wf_remove_page_templates($input) {

		// Sadly can't load this on load-(page) hook as the filter doesn't work
		$this_screen = get_current_screen();
		if ( isset($this_screen->parent_base) && $this_screen->parent_base != 'edit') return;

		if ( is_array($this->wfx_page_templates) ){

			foreach ( $this->wfx_page_templates as $val ) {
				foreach ($input as $key => $value) {
					if ( $key == 'page-templates/page-template-' . $this->wfx_page_templates . '.php' ) {
						unset( $input['page-templates/page-template-' . $this->wfx_page_templates . '.php'] );
					}
				}
			}

		} else {

			foreach ( $input as $key => $value ) {
				if ( $key == 'page-templates/page-template-' . $this->wfx_page_templates . '.php' ) {
					unset( $input['page-templates/page-template-' . $this->wfx_page_templates . '.php'] );
				}
			}

		}

		return $input;

	}

//END wflux_admin_post class
}
?>