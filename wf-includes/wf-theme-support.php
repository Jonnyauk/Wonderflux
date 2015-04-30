<?php
/**
 * Theme support and early call functionality required before init hook
 */


/**
 * Wonderflux theme_support setup
 * TODO: Populate with extended Wonderflux add_theme_support functionality
 * @since 1.1
 */
class wflux_theme_support {

	/**
	 * Enables post and site/post comment RSS feed links in head of output
	 * THIS IS REQUIRED for WordPress theme repo compliance (easy to remove by remove_action, overload function etc!)
	 *
	 * @since 1.1
	 * @lastupdate 2.0
	 */
	function wf_core_feed_links(){
		add_theme_support( 'automatic-feed-links' );
	}

	/**
	 * Enables post and site/post comment RSS feed links in head of output
	 * THIS IS REQUIRED for WordPress theme repo compliance (easy to remove by remove_action, overload function etc!)
	 * Backpat - only available in WordPress 4.1+
	 *
	 * @since 2.0
	 * @lastupdate 2.0
	 */
	function wf_core_title_tag(){
		if ( function_exists( '_wp_render_title_tag' ) ) {
			add_theme_support( 'title-tag' );
		}
	}

}