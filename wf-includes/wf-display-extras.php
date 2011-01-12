<?php

//// The main Wonderflux extra display functions  ////

//TODO: Build filters like core display functions? Would filters would enable a blanket override if no arguments passed to function in child theme - possibly cool?


/**
*
* @since 0.85
* @updated 0.892
*
* Extra template display functions
*
*/
class wflux_display_ex {


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
	 * @updated 0.892
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
						echo esc_attr($seperator) . '<span class="' . esc_attr($dateclass) . '">' . esc_attr__('Recently', 'Wonderflux') . '</span></' . esc_attr($contentstyle) . '>';
					} else {
						// Sort out formatting
						if ($fulldays == '1') {
						// Just one day, so its day not days
							$dayappend = '';
						} else {
							$dayappend = 's';
						}
						//TODO: Internationalisation for day/days (single/plural)
						echo esc_attr($seperator) . '<span class="' . esc_attr($dateclass) . '">' . $fulldays . esc_attr__('day', 'Wonderflux') . $dayappend . ' ' . esc_attr__('ago', 'Wonderflux') . '</span></' . esc_attr($contentstyle) . '>';
					}

					} else {
						echo esc_attr($seperator) . '<span class="' . esc_attr($dateclass) . '">' . $item->get_date('') . '</span></' . esc_attr($contentstyle) . '>';
					}
				}

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
	 * @updated 0.892
	 */
	function wf_perma_img($args) {

		$defaults = array (
			'intro' => 'Read about',
			'showtitle' => 'Y',
			'seperator' => ' - ',
			'imgclass' => 'button-more',
			'imgpath' => 'images',
			'imgname' => 'button-read-more.png',
			'imgwidth' => 150,
			'imgheight' => 30
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		// Tidy up ready for use
		$intro = wp_kses_data($intro, '');
		if ( $showtitle == 'Y' ) { $intro .= wp_kses_data($seperator, '') . get_the_title(); }
		$imgclass = wp_kses_data($imgclass, '');
		$imgpath = wp_kses_data($imgpath, '');
		$imgname = wp_kses_data($imgname, '');
		if (!is_numeric($imgwidth)) { $imgwidth = 150; } // Checking if a number is light weight
		if (!is_numeric($imgheight)) { $imgheight = 30; } // Checking if a number is light weight

		$output = '<a href="' . get_permalink() . '" title="' . $intro . '">';
		$output .= '<img class="';
		$output .= esc_attr($imgclass);
		$output .= '" src="';
		$output .= dirname( get_bloginfo('stylesheet_url') );
		$output .= '/' . esc_attr($imgpath) . '/';
		$output .= esc_attr($imgname);
		$output .= '" alt="';
		$output .= esc_attr($intro);
		$output .= '" width="';
		$output .= $imgwidth; //Already checked
		$output .= '" height="'; //Already checked
		$output .= $imgheight;
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
	 * @output <ul><li>list items of various admin links
	 *
	 * @since 0.85
	 * @updated 0.901
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
			'liclass' => 'wf-edit-meta-links'
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
			$this_admin = admin_url();

			$output = '<ul class="' . esc_attr($ulclass) . '">';

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
	function wf_loginlogout($args) {

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


/**
*
* @since 0.86
* @updated 0.892
*
* Extra display support elements for Internet Explorer, in particular IE6 - the party pooper at the web designers party!
*
*/
class wflux_display_ex_ie {

	/**
	* Inserts the Javascript PNG transparecy fix
	* TODO: Finish advanced implementation with correct path config
	* TODO: Convert this to core WF display function?
	*
	* @param type - 'Simple' or 'advanced' - Simple is faster rendering, advanced does repeating backgrounds properly [simple]
	* @param path - Custom path to your own png fix file. Defaults to root child theme dir (including '/'). If starts with 'http' it removes this path [WF_INCLUDES_URL.'/js/png-fix-basic.js]
	*
	* Notes on PNG fixes available:
	* Basic is faster rendering, doesn't require a blank image, but DOESNT DO BACKGROUNDS
	* Advanced renders repeating background pngs, but bit slower rendering if loads of PNGs to deal with (already in wf-includes/js) - so watch it if you are using lots and lots of images. It does function correctly though!
	*
	*
	* @since 0.86
	* @updated 0.892
	*/
	function wf_png_ie6($args) {

		$defaults = array (
			'type' => 'simple',
			'path' => WF_INCLUDES_URL.'/js/png-fix-basic.js'
		);

		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		//Use default Wonderflux PNG fix includes
		if ($type == 'advanced') {
			$path_output = WF_INCLUDES_URL.'/js/png-fix-advanced/iepngfix.htc';
		} elseif ($path == WF_INCLUDES_URL.'/js/png-fix-basic.js') {
			$path_output = $path;
		} else {
			//Ignore type and use custom path from user
			$first = substr($path, 0, 4);
			if ($first == 'http') {
				$path_output = $path;
			} else {
				$path_output = dirname( get_bloginfo('stylesheet_url') ) . '/' . $path;
			}
		}

		$png_simple_path_output = '<!--[if IE 6]><script type="text/javascript" src="';
		$png_simple_path_output .= esc_url($path_output);
		$png_simple_path_output .= '"></script><![endif]-->';
		$png_simple_path_output .= "\n";
		echo $png_simple_path_output;

	}

}
?>