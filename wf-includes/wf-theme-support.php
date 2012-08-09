<?php
/**
 * Theme support and early call functionality required before init hook
 */


/**
 * Wonderflux theme_support setup
 * TODO: Populate with extended Wonderflux add_theme_support functionality
 * @since 1.0RC4
 */
class wflux_theme_support {

	/**
	 * Enables post and site/post comment RSS feed links in head of output
	 * THIS IS REQUIRED for WordPress theme repo compliance (easy to remove by remove_action, overload function etc!)
	 *
	 * @param None
	 *
	 * @since 1.0RC4
	 * @lastupdate 1.0RC4
	 * TODO: Extend functionality!
	 */
	function wf_core_feeds(){
		add_theme_support( 'automatic-feed-links' );
	}

}