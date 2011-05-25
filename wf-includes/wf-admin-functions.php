<?php

/**
* Wonderflux admin functions
*/
class wflux_admin {


    // wf_page_build
	var $icon;
	var $title;
	var $include;
	var $paths;
	var $this_help;


	/**
	* Build the admin menus
	* @since 0.3
	* @updated 0.92
	*/
	function wf_add_pages(){
		global $wflux_core_admin_page_main;
		global $wflux_core_admin_page_style;
		//global $wflux_core_admin_page_seo;
		//global $wflux_core_admin_page_cms;
		global $wflux_core_admin_page_config;
		$wflux_core_admin_page_main = add_menu_page('Wonderflux main options', 'Wonderflux', 'administrator', 'wonderflux', array($this, 'wf_page_core'));
		$wflux_core_admin_page_style = add_submenu_page( 'wonderflux', 'Wonderflux Style Lab', 'Style Lab', 'administrator', 'wonderflux_stylelab', array($this, 'wf_page_stylelab'));
		//$wflux_core_admin_page_seo = add_submenu_page( 'wonderflux', 'Wonderflux SEO Optimisation', 'SEO optimise', 'administrator', 'wonderflux_seo', array($this, 'wf_page_seo'));
		//$wflux_core_admin_page_cms = add_submenu_page( 'wonderflux', 'Wonderflux CMS', 'CMS control', 'administrator', 'wonderflux_cms', array($this, 'wf_page_cms'));
		$wflux_core_admin_page_cms = add_submenu_page( 'wonderflux', 'Wonderflux System Information', 'System Information', 'administrator', 'wonderflux_system', array($this, 'wf_page_system'));
		//TODO: If user is superadmin ID, reveal advanced config menu
	}


	// Add content to admin areas
	function wf_page_core() { $this->wf_page_build('index', 'Wonderflux Home', 'core'); }
	function wf_page_stylelab() { $this->wf_page_build('themes', 'Wonderflux Stylelab', 'style'); }
	//function wf_page_seo() { $this->wf_page_build('plugins', 'Wonderflux Search Engine Optimiser', 'seo'); }
	//function wf_page_cms() { $this->wf_page_build('options-general', 'Wonderflux Content Management Options', 'cms'); }
	function wf_page_system() { $this->wf_page_build('options-general', 'Wonderflux System Information', 'system'); }


	/**
	* Builds Wonderflux admin pages
	* @since 0.1
	* @updated 0.913
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
			case('Wonderflux System Information'): $tab5=TRUE; break;
			default: $tab1=TRUE; break;

		}

		$thistab_highlight = ' nav-tab-active';

		echo '<div class="nav-tab-wrapper">';

		echo '<h2 class="nav-tab-wrapper">';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux" class="nav-tab';
		if (isset($tab1)) { echo $thistab_highlight; };
		echo'">' . esc_attr('Wonderflux Home', 'wonderflux') . '</a>';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_stylelab" class="nav-tab';
		if (isset($tab2)) { echo $thistab_highlight; };
		echo'">' . esc_attr('Stylelab', 'wonderflux') . '</a>';
		/*echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_seo" class="nav-tab';
		if (isset($tab3)) { echo $thistab_highlight; };
		echo'">' . esc_attr('SEO', 'wonderflux') . '</a>';
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_cms" class="nav-tab';
		if (isset($tab4)) { echo $thistab_highlight; };
		echo'">' . esc_attr('CMS', 'wonderflux') . '</a>';*/
		echo '<a href="'.wp_sanitize_redirect(admin_url()).'admin.php?page=wonderflux_system" class="nav-tab';
		if (isset($tab5)) { echo $thistab_highlight; };
		echo'">' . esc_attr('System', 'wonderflux') . '</a>';
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

		} else { echo '<p>You are currently using '.esc_attr(get_current_theme()).' Wonderflux child theme</p>'; }

		echo '</div>';

	}


	/**
	* Sets up and configures options and form fields
	* @since 0.81
	* @updated 0.92
	*/
	function wf_register_settings(){

		$myadminforms = new wflux_admin_forms;

		register_setting('wf_settings_display', 'wonderflux_display', array($myadminforms, 'validate_opts_layout') );

		add_settings_section('style_lab', '', array($myadminforms, 'wf_form_intro_main'), 'wonderflux_stylelab');
		add_settings_section('style_lab_doc', '', array($myadminforms, 'wf_form_intro_doc'), 'wonderflux_stylelab_doc');

		//1) Key 2) form label 3) Builder function 4)Page? 5)Section
		add_settings_field('container_p', 'Site container position', array($myadminforms, 'wf_form_container_p'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('sidebar_p', 'Sidebar position', array($myadminforms, 'wf_form_sidebar_p'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('container_w', 'Site container width', array($myadminforms, 'wf_form_container_w'), 'wonderflux_stylelab', 'style_lab');
		//add_settings_field('padding_l', 'Left site container padding', array($myadminforms, 'wf_form_padding_l'), 'wonderflux_stylelab', 'style_lab');
		//add_settings_field('padding_r', 'Right site container padding', array($myadminforms, 'wf_form_padding_r'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('columns_num', 'Number of vertical columns (inside container+padding)', array($myadminforms, 'wf_form_columns_num'), 'wonderflux_stylelab', 'style_lab');
		add_settings_field('columns_w', 'Desired width of column', array($myadminforms, 'wf_form_columns_w'), 'wonderflux_stylelab', 'style_lab');

		add_settings_field('doc_type', 'Document type', array($myadminforms, 'wf_form_doc_type'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('doc_lang', 'Document language', array($myadminforms, 'wf_form_doc_lang'), 'wonderflux_stylelab_doc', 'style_lab_doc');
		add_settings_field('doc_charset', 'Document character set', array($myadminforms, 'wf_form_doc_charset'), 'wonderflux_stylelab_doc', 'style_lab_doc');


	}


	/**
	* Checks in the nicest way possible what the latest version of Wonderflux is against installed version
	* No nasty business here or anywhere in Wonderflux, move on with a warm glow in your heart!
	* @since 0.911
	* @updated 0.911
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

			echo '<p>' . esc_attr__('Sorry, update check not currently not available', 'Wonderflux') . '</p>';

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
	* @updated 0.911
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
			$output .= '<p>It probably contains stuff thats not finished just yet, or new functionality that may conflict with your current Wonderflux child theme.</p>';
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
	* @updated 0.92
	*/
	function wf_contextual_help($contextual_help, $screen_id, $screen) {

		global $wflux_core_admin_page_main;
		global $wflux_core_admin_page_style;
		global $wflux_core_admin_page_seo;
		global $wflux_core_admin_page_cms;

		$generic_help = '<p>';
		$generic_help .= __( 'The Wonderflux Codex - the development reference to help you build Wonderflux child themes smarter and faster will be launching shortly!', 'wonderflux' );
		$generic_help .= '</p>';
		$generic_help .= '<h3>';
		$generic_help .= __( 'In the meantime...', 'wonderflux' );
		$generic_help .= '</h3>';
		$generic_help .= '<p>';
		$generic_help .= __( 'Get involved in the Google code project where you can suggest improvements, report bugs and help us make a really great theme framework for everyone to use!', 'wonderflux' );
		$generic_help .= '</p>';

		switch ($screen_id) {
			case $wflux_core_admin_page_main : $this_help = '<h3>' . __( 'Wonderflux help - Main settings', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case $wflux_core_admin_page_style : $this_help = '<h3>' . __( 'Wonderflux help - Stylelab', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case $wflux_core_admin_page_seo : $this_help = '<h3>' . __( 'Wonderflux help - Search Engine Optimisation', 'wonderflux' ) . '</h3>' . $generic_help; break;
			case $wflux_core_admin_page_cms : $this_help = '<h3>' . __( 'Wonderflux help - Content Management System', 'wonderflux' ) . '</h3>' . $generic_help; break;
			default : $this_help = $generic_help; break;
		}

		return $this_help;
	}


//END wflux_admin class
}

/**
* Wonderflux admin form functions
*/
class wflux_admin_forms extends wflux_data {

	/**
	* IMPORTANT - Validates and cleans any data saved from layout options before saving to database
	* Accepts array, return cleaned items in new array.
	* @since 0.912
	* @updated 0.92
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

		settype( $input['container_w'], "string" );
		$sidebar_p_whitelist = array('left','right');
		if (in_array($input['sidebar_p'],$sidebar_p_whitelist)) { $cleaninput['sidebar_p'] = $input['sidebar_p'];
		} else {
			$cleaninput['sidebar_p'] = 'left'; // No cheatin thanks, set sensible value
		}

		settype( $input['container_w'], "integer" );
		$container_w_whitelist = range(400,2000,10);
		if (in_array($input['container_w'],$container_w_whitelist)) { $cleaninput['container_w'] = $input['container_w'];
		} else {
			$cleaninput['container_w'] = 950; // No cheatin thanks, set sensible value
		}

		settype( $input['columns_num'], "integer" );
		$columns_num_whitelist = range(4,80,1);
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
	function wf_form_intro_main() { echo '<p>Use these controls to setup the main dimensions used across all your Wonderflux template designs.</p>'; }

	function wf_form_container_p() { $this->wf_form_helper_ddown_std($this->wfx_position,'container_p',array('left', 'middle', 'right')); }
	function wf_form_sidebar_p() { $this->wf_form_helper_ddown_std($this->wfx_sidebar_primary_position,'sidebar_p',array('left', 'right')); }
	function wf_form_container_w() { $this->wf_form_helper_ddown_range($this->wfx_width,'container_w',400,2000,10); }
	/* NOT ACTIVE AT MOMENT
	function wf_form_padding_l() { $this->wf_form_helper_ddown_range('padding_l',0,200,1); }
	function wf_form_padding_r() { $this->wf_form_helper_ddown_range('padding_r',0,200,1); }
	*/
	function wf_form_columns_num() { $this->wf_form_helper_ddown_range($this->wfx_columns,'columns_num',4,80,4); }
	function wf_form_columns_w() { $this->wf_form_helper_ddown_range($this->wfx_columns_width,'columns_w',10,200,1); }

	// Doc
	function wf_form_intro_doc() { echo '<p>Use these controls to set advanced document type and language attributes. WARNING - These settings should generally be left as default!</p>'; }
	function wf_form_doc_type() { $this->wf_form_helper_ddown_std($this->wfx_doc_type,'doc_type',array('transitional','strict','frameset','1.1','1.1basic','html5')); }
	function wf_form_doc_lang() { $this->wf_form_helper_ddown_std($this->wfx_doc_lang,'doc_lang',array('aa','ab','ae','af','ak','am','an','ar','as','av','ay','az','ba','be','bg','bh','bi','bm','bn','bo','bo','br','bs','ca','ce','ch','co','cr','cs','cs','cu','cv','cy','cy','da','de','de','dv','dz','ee','el','el','en','eo','es','et','eu','eu','fa','fa','ff','fi','fj','fo','fr','fr','fy','ga','gd','gl','gn','gu','gv','ha','he','hi','ho','hr','ht','hu','hy','hy','hz','ia','id','ie','ig','ii','ik','io','is','is','it','iu','ja','jv','ka','ka','kg','ki','kj','kk','kl','km','kn','ko','kr','ks','ku','kv','kw','ky','la','lb','lg','li','ln','lo','lt','lu','lv','mg','mh','mi','mi','mk','mk','ml','mn','mr','ms','ms','mt','my','my','na','nb','nd','ne','ng','nl','nl','nn','no','nr','nv','ny','oc','oj','om','or','os','pa','pi','pl','ps','pt','qu','rm','rn','ro','ro','ru','rw','sa','sc','sd','se','sg','si','sk','sk','sl','sm','sn','so','sq','sq','sr','ss','st','su','sv','sw','ta','te','tg','th','ti','tk','tl','tn','to','tr','ts','tt','tw','ty','ug','uk','ur','uz','ve','vi','vo','wa','wo','xh','yi','yo','za','zh','zh','zu')); }
	function wf_form_doc_charset() { $this->wf_form_helper_ddown_std($this->wfx_doc_charset,'doc_charset',array('UTF-8','UTF-16','ISO-2022-JP','ISO-2022-JP-2','ISO-2022-KR','ISO-8859-1','ISO-8859-10','ISO-8859-15','ISO-8859-2','ISO-8859-3','ISO-8859-4','ISO-8859-5','ISO-8859-6','ISO-8859-7','ISO-8859-8','ISO-8859-9')); }

	/**
	* Creates a dropdown for options page
	* @since 0.81
	* @updated 0.92
	*/
	function wf_form_helper_ddown_std($data,$definition,$items) {
		echo "<select id='columns_num' name='wonderflux_display[".$definition."]'>";
		foreach($items as $key=>$value) {
			$selected = ($value==$data) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$value</option>";
		}
		echo "</select>";
		echo "\n";
	}


	/**
	* Creates a dropdown for options page populated with range of numbers
	* @since 0.81
	* @updated 0.92
	*/
	function wf_form_helper_ddown_range($data,$definition,$low,$high,$step) {
		$items = range($low,$high,$step);
		echo "<select id='columns_num' name='wonderflux_display[".$definition."]'>";
		foreach($items as $item) {
			$selected = ($item==$data) ? 'selected="selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
		echo "</select>";
		echo "\n";
	}

//END wflux_admin_forms class
}

?>