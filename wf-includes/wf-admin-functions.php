<?php

/**
* @since 0.3
* @updated 0.93
* Admin form functions
*/
class wflux_admin extends wflux_data {


    // wf_page_build
	var $icon;
	var $title;
	var $include;
	var $paths;
	var $this_help;
	var $admin_forms;

	function __construct(){
		$this->admin_forms = new wflux_admin_forms;
	}


	/**
	* Build the admin menus
	* @since 0.3
	* @updated 0.92
	*/
	function wf_add_pages(){
		global $wflux_core_admin_page_main;
		global $wflux_core_admin_page_style;
		global $wflux_core_admin_page_config;
		global $wflux_core_admin_page_advanced;
		$wflux_core_admin_page_main = add_menu_page('Wonderflux main options', 'Wonderflux', 'administrator', 'wonderflux', array($this, 'wf_page_core'));
		$wflux_core_admin_page_style = add_submenu_page( 'wonderflux', 'Wonderflux Style Lab', 'Style Lab', 'administrator', 'wonderflux_stylelab', array($this, 'wf_page_stylelab'));
		$wflux_core_admin_page_advanced = add_submenu_page( 'wonderflux', 'Wonderflux Advanced', 'Advanced', 'administrator', 'wonderflux_advanced', array($this, 'wf_page_advanced'));
		$wflux_core_admin_page_cms = add_submenu_page( 'wonderflux', 'Wonderflux System Information', 'System Information', 'administrator', 'wonderflux_system', array($this, 'wf_page_system'));
		//TODO: If user is superadmin ID, reveal advanced config menu
	}


	// Add content to admin areas
	function wf_page_core() { $this->wf_page_build('index', 'Wonderflux Home', 'core'); }
	function wf_page_stylelab() { $this->wf_page_build('themes', 'Wonderflux Stylelab', 'style'); }
	function wf_page_advanced() { $this->wf_page_build('themes', 'Wonderflux Advanced', 'advanced'); }
	function wf_page_system() { $this->wf_page_build('tools', 'Wonderflux System Information', 'system'); }


	/**
	* Builds Wonderflux admin pages
	* @since 0.1
	* @updated 0.931
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
			case('Wonderflux Advanced'): $tab3=TRUE; break;
			case('Wonderflux System Information'): $tab4=TRUE; break;
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
		echo '</h2>';

		echo '</div>';

		require('admin-pages/wf-page-'.$include.'.php');

		if ($title == 'Wonderflux Advanced'): $this->admin_forms->wf_form_helper_file_css_combine('css/wf-css-core-structure.css','Y'); endif;

		$wf_current_theme = get_current_theme();
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
			$output .= ' <a href="http://code.google.com/p/wonderflux-girder-theme/" title="';
			$output .= esc_attr__('Download the free Girder Wonderflux child theme','wonderflux');
			$output .= '">';
			$output .= esc_attr__('download the free example Wonderflux child theme call Girder','wonderflux');
			$output .= '</a>';
			$output .= esc_attr__(' - take a look at the layout code and especially the theme functions.php file for references on using Wonderflux.','wonderflux');
			$output .= '</strong></p>';
			$output .= '</div>';
			echo $output;
		} else {
			echo '<p>' . esc_attr__('You are using the','wonderflux') . ' ' . esc_attr(get_current_theme()) . ' ' . esc_attr__('Wonderflux child theme','wonderflux') . '</p>';
		}


		$output = '<div id="icon-options-general" class="icon32">';
		$output .= '<br />';
		$output .= '</div>';
		$output .= '<h2>' . esc_attr__('Help and support','wonderflux') . '</h2>';
		$output .= '<p>';
		$output .= ' <a href="http://wonderflux.com/guide/" title="';
		$output .= esc_attr__('visit the Wonderflux guide','wonderflux');
		$output .= '">';
		$output .= esc_attr__('The Wonderflux guide','wonderflux');
		$output .= '</a> ';
		$output .= esc_attr__('is the official documentation site for Wonderflux. Click on the direct links below to find relevant content.','wonderflux');
		echo $output;
		echo $this->wf_common_help();
		echo '</div>'; // close themes-php wrap div
	}


	/**
	* Sets up and configures options and form fields
	* @since 0.81
	* @updated 0.93
	*/
	function wf_register_settings(){

		register_setting('wf_settings_display', 'wonderflux_display', array($this->admin_forms, 'validate_opts_layout') );

		add_settings_section('style_lab', '', array($this->admin_forms, 'wf_form_intro_main'), 'wonderflux_stylelab');
		add_settings_section('style_lab_doc', '', array($this->admin_forms, 'wf_form_intro_doc'), 'wonderflux_stylelab_doc');

		//1) Key 2) form label 3) Builder function 4)Page? 5)Section
		add_settings_field('container_p', 'Site container position', array($this->admin_forms, 'wf_form_container_p'), 'wonderflux_stylelab', 'style_lab');

		add_settings_field('sidebar_d', 'Sidebar display', array($this->admin_forms, 'wf_form_sidebar_d'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('sidebar_p', 'Sidebar position', array($this->admin_forms, 'wf_form_sidebar_p'), 'wonderflux_stylelab', 'style_lab');

		add_settings_field('container_w', 'Site container width', array($this->admin_forms, 'wf_form_container_w'), 'wonderflux_stylelab', 'style_lab');
		//add_settings_field('padding_l', 'Left site container padding', array($this->admin_forms, 'wf_form_padding_l'), 'wonderflux_stylelab', 'style_lab');
		//add_settings_field('padding_r', 'Right site container padding', array($this->admin_forms, 'wf_form_padding_r'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('columns_num', 'Number of vertical columns (inside container+padding)', array($this->admin_forms, 'wf_form_columns_num'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('columns_w', 'Desired width of column', array($this->admin_forms, 'wf_form_columns_w'), 'wonderflux_stylelab', 'style_lab');

		add_settings_field('doc_type', 'Document type', array($this->admin_forms, 'wf_form_doc_type'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('doc_lang', 'Document language', array($this->admin_forms, 'wf_form_doc_lang'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('doc_charset', 'Document character set', array($this->admin_forms, 'wf_form_doc_charset'), 'wonderflux_stylelab_doc', 'style_lab_doc');


	}


	/**
	* Checks in the nicest way possible what the latest version of Wonderflux is against installed version
	* No nasty business here or anywhere in Wonderflux, move on with a warm glow in your heart!
	* @since 0.911
	* @updated 0.93
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

			echo '<p>' . esc_attr__('Sorry, update check not currently available', 'wonderflux') . '</p>';

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
	* @updated 0.911
	*/
	function wf_latest_version_compare() {

		$this_version = WF_VERSION;
		$latest_version = $this->wf_latest_version_fetch();

		if ($latest_version > $this_version) {
			return 'update';
		} elseif ($latest_version < $this_version) {
			return 'development';
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
			$output .= '<h3>Wonderflux framework update available!</h3>';
			$output .= '<p>You are running v'.esc_attr(WF_VERSION).', the current latest release is v'.esc_attr($this->wf_latest_version_fetch()).'</p>';
			$output .= '<p>There is an update available to Wonderflux. Please read the update notes to check-up on how this may effect your theme BEFORE updating!</p>';
			$output .= '<p>You can <a href="http://code.google.com/p/wonderflux-framework/downloads/" title="Download the latest Wonderflux update here">download the latest Wonderflux update here</a>.</p>';
			$output .= '</div>';

			echo $output;

		} elseif ($check == 'development') {

			$output = '<div id="message1" class="error">';
			$output .= '<h3>Development version installed</h3>';
			$output .= '<p>You are running a development version of Wonderflux, cool! <strong>You should probably NOT be using this on live sites.</strong></p>';
			$output .= '<p>It may contain code thats not finished just yet, or new functionality that may conflict with your current Wonderflux child theme.</p>';
			$output .= '<p><strong>If you are not a developer, advanced designer or tester</strong> you will probably be better off using <a href="http://code.google.com/p/wonderflux-framework/downloads/" title="Download the latest stable Wonderflux release here">the latest stable version of Wonderflux</a>.</p>';
			$output .= '</div>';

			echo $output;

		} else {
			// Silence is golden - user running current version!
		}

	}


 	/**
	* Contextual help
	* @since 0.92
	* @updated 0.931
	*/
	function wf_contextual_help($contextual_help, $screen_id, $screen) {

		$link_dev = __( 'Google code project', 'wonderflux' );
		$link_dl = __( 'Latest release', 'wonderflux' );
		$link_svn = __( 'Google code SVN', 'wonderflux' );
		$link_lis = __( 'Google code issue tracking list', 'wonderflux' );
		$link_doc = __( 'Wonderflux documentation', 'wonderflux' );
		$doc = __( 'documentation', 'wonderflux' );

		$adv_help = '<p>';
		$adv_help .= '<a href="http://wonderflux.com/guide/constant/wf_theme_framework_replace/" title="WF_THEME_FRAMEWORK_REPLACE '. $doc . '" target="_blank">WF_THEME_FRAMEWORK_REPLACE</a> - ';
		$adv_help .= 'WF_THEME_FRAMEWORK_REPLACE '. $doc;
		$adv_help .= '</p>';

		$generic_help = '<p>';
		$generic_help .= '<a href="http://wonderflux.com/guide/" title="'. $link_doc . '"target="_blank">http://wonderflux.com/guide</a> - ';
		$generic_help .= __( 'All Wonderflux documentation and user guides', 'wonderflux' );
		$generic_help .= '</p>';
		$generic_help .= '<h3>';
		$generic_help .= __( 'Get involved!', 'wonderflux' );
		$generic_help .= '</h3>';
		$generic_help .= '<p>';
		$generic_help .= __( 'A huge amount of resource has been poured into this project since it began in January 2010. If you have ideas on how Wonderflux could improve, <strong>why not contribute to the development of Wonderflux?</strong>', 'wonderflux' );
		$generic_help .= '</p>';
		$generic_help .= '<p>';
		$generic_help .= __( '<strong>Remember, Wonderflux is free, open source code just like WordPress</strong> - so your ideas can help make a great theme framework for the whole community to use. Some ideas on how you could help include bug hunting, documentation, javascript, optimisation - really any ideas you have would be more than welcome!', 'wonderflux' );
		$generic_help .= '</p>';
		$generic_help .= '<p>';
		$generic_help .= '<a href="http://code.google.com/p/wonderflux-framework/source/checkout/" title="' . $link_svn . '" target="_blank">' . $link_svn . '</a> ';
		$generic_help .= __( '- SVN code repository - latest development version available for checkout (for developers and testers - NOT to be used on live sites!)', 'wonderflux' );
		$generic_help .= '<br />';
		$generic_help .= '<a href="http://code.google.com/p/wonderflux-framework/issues/list/" title="' . $link_lis . '" target="_blank">' . $link_lis . '</a> ';
		$generic_help .= __( '- Development list (for contributors, bug reports and feature requests)', 'wonderflux' );
		$generic_help .= '<br />';
		$generic_help .= '<a href="http://wonderflux.com/guide/" title="' . $link_doc . '" target="_blank">' . $link_doc . '</a> ';
		$generic_help .= __( '- Documentation site', 'wonderflux' );

		$generic_help .= '</p>';

		switch ($screen_id) {
			case 'toplevel_page_wonderflux' : $this_help = '<h3>' . __( 'Wonderflux Help - Main Options', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case 'wonderflux_page_wonderflux_stylelab' : $this_help = '<h3>' . __( 'Wonderflux Help - Stylelab', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case 'wonderflux_page_wonderflux_system' : $this_help = '<h3>' . __( 'Wonderflux Help - System', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case 'wonderflux_page_wonderflux_advanced' : $this_help = '<h3>' . __( 'Wonderflux Help - Advanced', 'wonderflux' ) . '</h3>' . $adv_help . $generic_help; break;
			default : return $contextual_help;
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
			//$output .= esc_attr__('functions','wonderflux');
			$output .= $value;
			$output .= esc_attr__(' (documented so far)','wonderflux');
			$output .= '"> ';
			//$output .= esc_attr__('Function reference','wonderflux');
			$output .= $value;
			$output .= '</a> ';
		}
		$output .= '</p>';
		return $output;
	}


}


/**
* @since 0.81
* @updated 0.93
* Admin form functions
*/
class wflux_admin_forms extends wflux_data {

	/**
	* IMPORTANT - Validates and cleans any data saved from layout options before saving to database
	* Accepts array, return cleaned items in new array.
	* @since 0.912
	* @updated 0.93
	*/
	function validate_opts_layout($input) {

		// This will hold just the data we want and no nasty stuff
		$cleaninput = array();

		settype( $input['doc_type'], "string" );
		$doc_type_whitelist = array('transitional','strict','frameset','1.1','1.1basic','html5');
		if (in_array($input['doc_type'],$doc_type_whitelist)) { $cleaninput['doc_type'] = $input['doc_type'];
		} else {
			$cleaninput['doc_type'] = 'transitional'; // No cheatin thanks, set sensible value
		}

		settype( $input['doc_lang'], "string" );
		$doc_lang_whitelist = array('aa','ab','ae','af','ak','am','an','ar','as','av','ay','az','ba','be','bg','bh','bi','bm','bn','bo','bo','br','bs','ca','ce','ch','co','cr','cs','cs','cu','cv','cy','cy','da','de','de','dv','dz','ee','el','el','en','eo','es','et','eu','eu','fa','fa','ff','fi','fj','fo','fr','fr','fy','ga','gd','gl','gn','gu','gv','ha','he','hi','ho','hr','ht','hu','hy','hy','hz','ia','id','ie','ig','ii','ik','io','is','is','it','iu','ja','jv','ka','ka','kg','ki','kj','kk','kl','km','kn','ko','kr','ks','ku','kv','kw','ky','la','lb','lg','li','ln','lo','lt','lu','lv','mg','mh','mi','mi','mk','mk','ml','mn','mr','ms','ms','mt','my','my','na','nb','nd','ne','ng','nl','nl','nn','no','nr','nv','ny','oc','oj','om','or','os','pa','pi','pl','ps','pt','qu','rm','rn','ro','ro','ru','rw','sa','sc','sd','se','sg','si','sk','sk','sl','sm','sn','so','sq','sq','sr','ss','st','su','sv','sw','ta','te','tg','th','ti','tk','tl','tn','to','tr','ts','tt','tw','ty','ug','uk','ur','uz','ve','vi','vo','wa','wo','xh','yi','yo','za','zh','zh','zu');
		if (in_array($input['doc_lang'],$doc_lang_whitelist)) { $cleaninput['doc_lang'] = $input['doc_lang'];
		} else {
			$cleaninput['doc_lang'] = 'en'; // No cheatin thanks, set sensible value
		}

		settype( $input['doc_charset'], "string" );
		$doc_charset_whitelist = array('UTF-8','UTF-16','ISO-2022-JP','ISO-2022-JP-2','ISO-2022-KR','ISO-8859-1','ISO-8859-10','ISO-8859-15','ISO-8859-2','ISO-8859-3','ISO-8859-4','ISO-8859-5','ISO-8859-6','ISO-8859-7','ISO-8859-8','ISO-8859-9');
		if (in_array($input['doc_charset'],$doc_charset_whitelist)) { $cleaninput['doc_charset'] = $input['doc_charset'];
		} else {
			$cleaninput['doc_charset'] = 'UTF-8'; // No cheatin thanks, set sensible value
		}

		settype( $input['container_p'], "string" );
		$container_p_whitelist = array('left','middle','right');
		if (in_array($input['container_p'],$container_p_whitelist)) { $cleaninput['container_p'] = $input['container_p'];
		} else {
			$cleaninput['container_p'] = 'middle'; // No cheatin thanks, set sensible value
		}

		settype( $input['sidebar_p'], "string" );
		$sidebar_p_whitelist = array('left','right');
		if (in_array($input['sidebar_p'],$sidebar_p_whitelist)) { $cleaninput['sidebar_p'] = $input['sidebar_p'];
		} else {
			$cleaninput['sidebar_p'] = 'left'; // No cheatin thanks, set sensible value
		}

		settype( $input['sidebar_d'], "string" );
		$sidebar_d_whitelist = array('Y','N');
		if (in_array($input['sidebar_d'],$sidebar_d_whitelist)) { $cleaninput['sidebar_d'] = $input['sidebar_d'];
		} else {
			$cleaninput['sidebar_d'] = 'Y'; // No cheatin thanks, set sensible value
		}


		settype( $input['container_w'], "integer" );
		$container_w_whitelist = range(400,2000,10);
		if (in_array($input['container_w'],$container_w_whitelist)) { $cleaninput['container_w'] = $input['container_w'];
		} else {
			$cleaninput['container_w'] = 950; // No cheatin thanks, set sensible value
		}

		settype( $input['columns_num'], "integer" );
		$columns_num_whitelist = range(2,100,1);
		if (in_array($input['columns_num'],$columns_num_whitelist)) { $cleaninput['columns_num'] = $input['columns_num'];
		} else {
			$cleaninput['columns_num'] = 24; // No cheatin thanks, set sensible value
		}

		settype( $input['columns_w'], "integer" );
		$columns_w_whitelist = range(10,300,1);
		if (in_array($input['columns_w'],$columns_w_whitelist)) { $cleaninput['columns_w'] = $input['columns_w'];
		} else {
			$cleaninput['columns_w'] = 30; // No cheatin thanks, set sensible value
		}

		// Return safe array of values to write to database
		return $cleaninput;
	}


	//////// STYLE LAB FORM ITEMS
	// Section HTML, displayed before the first option
	function wf_form_intro_main() { echo '<p>' . esc_attr__("Use these controls to setup the main dimensions used across all your Wonderflux template designs.","wonderflux") . '</p>'; }

	function wf_form_container_p() { $this->wf_form_helper_ddown_std($this->wfx_position,'container_p',array('left', 'middle', 'right')); }
	function wf_form_sidebar_d() { $this->wf_form_helper_ddown_std($this->wfx_sidebar_1_display,'sidebar_d',array(array('yes'=>'Y'), array('no'=>'N'))); }
	function wf_form_sidebar_p() { $this->wf_form_helper_ddown_std($this->wfx_sidebar_primary_position,'sidebar_p',array('left', 'right')); }
	function wf_form_container_w() { $this->wf_form_helper_ddown_range($this->wfx_width,'container_w',400,2000,10); }
	/* NOT ACTIVE AT MOMENT
	function wf_form_padding_l() { $this->wf_form_helper_ddown_range('padding_l',0,200,1); }
	function wf_form_padding_r() { $this->wf_form_helper_ddown_range('padding_r',0,200,1); }
	*/
	function wf_form_columns_num() { $this->wf_form_helper_ddown_range($this->wfx_columns,'columns_num',2,100,1); }
	function wf_form_columns_w() { $this->wf_form_helper_ddown_range($this->wfx_columns_width,'columns_w',10,200,1); }

	// Doc
	function wf_form_intro_doc() { echo '<p>' . esc_attr__("Use these controls to set advanced document type and language attributes. ","wonderflux") . '<strong>' . esc_attr__("WARNING - ","wonderflux") . '</strong>' . esc_attr__("These settings should generally be left as default!","wonderflux") . '</p>'; }
	function wf_form_doc_type() { $this->wf_form_helper_ddown_std($this->wfx_doc_type,'doc_type',array('transitional','strict','frameset','1.1','1.1basic','html5')); }
	function wf_form_doc_lang() { $this->wf_form_helper_ddown_std($this->wfx_doc_lang,'doc_lang',array('aa','ab','ae','af','ak','am','an','ar','as','av','ay','az','ba','be','bg','bh','bi','bm','bn','bo','bo','br','bs','ca','ce','ch','co','cr','cs','cs','cu','cv','cy','cy','da','de','de','dv','dz','ee','el','el','en','eo','es','et','eu','eu','fa','fa','ff','fi','fj','fo','fr','fr','fy','ga','gd','gl','gn','gu','gv','ha','he','hi','ho','hr','ht','hu','hy','hy','hz','ia','id','ie','ig','ii','ik','io','is','is','it','iu','ja','jv','ka','ka','kg','ki','kj','kk','kl','km','kn','ko','kr','ks','ku','kv','kw','ky','la','lb','lg','li','ln','lo','lt','lu','lv','mg','mh','mi','mi','mk','mk','ml','mn','mr','ms','ms','mt','my','my','na','nb','nd','ne','ng','nl','nl','nn','no','nr','nv','ny','oc','oj','om','or','os','pa','pi','pl','ps','pt','qu','rm','rn','ro','ro','ru','rw','sa','sc','sd','se','sg','si','sk','sk','sl','sm','sn','so','sq','sq','sr','ss','st','su','sv','sw','ta','te','tg','th','ti','tk','tl','tn','to','tr','ts','tt','tw','ty','ug','uk','ur','uz','ve','vi','vo','wa','wo','xh','yi','yo','za','zh','zh','zu')); }
	function wf_form_doc_charset() { $this->wf_form_helper_ddown_std($this->wfx_doc_charset,'doc_charset',array('UTF-8','UTF-16','ISO-2022-JP','ISO-2022-JP-2','ISO-2022-KR','ISO-8859-1','ISO-8859-10','ISO-8859-15','ISO-8859-2','ISO-8859-3','ISO-8859-4','ISO-8859-5','ISO-8859-6','ISO-8859-7','ISO-8859-8','ISO-8859-9')); }


	/**
	* Creates a dropdown for options page
	* @since 0.81
	* @updated 0.93
	*/
	function wf_form_helper_ddown_std($data,$definition,$items) {
		echo "<select id='wonderflux_display[".$definition."]' name='wonderflux_display[".$definition."]'>";
		foreach($items as $key=>$value) {
			if (is_array($value)) {
				foreach($value as $key=>$value) {
					$selected = ($value==$data) ? 'selected="selected"' : '';
					echo "<option value='$value' $selected>$key</option>";
				}
			} else {
				$selected = ($value==$data) ? 'selected="selected"' : '';
				echo "<option value='$value' $selected>$value</option>";
			}
		}
		echo "</select>";
		echo "\n";
	}


	/**
	* Creates a dropdown for options page populated with range of numbers
	* @since 0.81
	* @updated 0.93
	*/
	function wf_form_helper_ddown_range($data,$definition,$low,$high,$step) {
		$items = range($low,$high,$step);
		echo "<select id='wonderflux_display[".$definition."]' name='wonderflux_display[".$definition."]'>";
		foreach($items as $item) {
			$selected = ($item==$data) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select>";
		echo "\n";
	}


	/**
	* Creates a text area populated with a file
	* @since 0.93
	* @updated 0.93
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
		$content = esc_textarea( $content );

		//$content_grid = esc_textarea( $this->wf_css_framework_build() );

		$content_grid = ( $cleanup == 'Y' ) ? preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $this->wf_css_framework_build()) : $this->wf_css_framework_build();
		$content_grid = esc_textarea( $content_grid );

		$output = '<div class="icon32" id="icon-options-general"><br></div><h2>' . esc_attr__( "CSS override files", "wonderflux" ) . '</h2>';
		$output .= '<p>' . esc_attr__( "Advanced users may wish to remove the default stylesheets that are usually inserted for you when using Wonderflux. The code below is all of the Wonderflux framework CSS file content that is normally inserted into the <head> of your site output, using your current saved configuration.", "wonderflux" ) . '</p>';
		$output .= '<p>' . esc_attr__( "By setting the constant in your child theme functions.php file with the single line:", "wonderflux" );
		$output .= ' <strong>' . 'define( \'WF_THEME_FRAMEWORK_REPLACE\', true);' . '</strong> ';
		$output .= esc_attr__( "you will remove the Wonderflux css files: 'wf-css-core-structure', 'wf-css-dynamic-columns' and the conditional 'wf-css-dynamic-core-ie', leaving only your theme.css file in the document <head>. ", "wonderflux" );
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
		$output .= '<script type="text/javascript">';
		$output .= 'function select_all() { var text_val=eval("document.form1.newcontent"); text_val.focus(); text_val.select(); }';
		$output .= 'function select_all2() { var text_val=eval("document.form1.newcontent2"); text_val.focus(); text_val.select(); }';
		$output .= '</script>';
		$output .= '<form name="form1" method="post" action="" >';
		$output .= '<textarea cols="100" rows="20" name="newcontent" id="newcontent" tabindex="1" onClick="select_all();">'.$content.$content_grid.'</textarea>';

		$content_ie = $this->wf_css_framework_build_ie();

		$output .= '<h3>' . esc_attr__( "2 - Code for your style-framework-ie.css file", "wonderflux" ) . '</h3>';
		$output .= '<textarea cols="100" rows="20" name="newcontent2" id="newcontent2" tabindex="2" onClick="select_all2();">'.$content_ie.'</textarea>';
		$output .= '</form>';
		echo $output;
	}


	/**
	* Creates the grid output
	* @since 0.93
	* @updated 0.93
	*/
	function wf_css_framework_build() {

		$css_output = "\n" . ' /*GRID CONFIGURATION*/ ' . "\n";

		//Work out gutter
		$wf_grid_gutter = ($this->wfx_width - ($this->wfx_columns * $this->wfx_columns_width)) / ($this->wfx_columns - 1);

		// 1 Sets up main container

		$wf_grid_container = ".container { ";
		$wf_grid_container .= "width: " . $this->wfx_width;
		$wf_grid_container .= "px; ";

		switch ($this->wfx_sidebar_primary_position) {
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
	* Creates the grid output
	* @since 0.93
	* @updated 0.93
	*/
	function wf_css_framework_build_ie() {
		$output = '/** * Wonderflux theme framework dynamic column core (legacy IE support) * http://wonderflux.com * * @package Wonderflux * @since Wonderflux 0.2 */ ';
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

?>