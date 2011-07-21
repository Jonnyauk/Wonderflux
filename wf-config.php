<?php
/**
 * Advanced configuration options - normal users need not change these.
 *
 * @package Wonderflux
 * @since Wonderflux 0.1
 */

define( 'WF_MAIN_DIR', TEMPLATEPATH );
define( 'WF_MAIN_URL', get_template_directory_uri());

define( 'WF_CONTENT_DIR', WF_MAIN_DIR . '/wf-content');
define( 'WF_CONTENT_URL', WF_MAIN_URL . '/wf-content');

define( 'WF_INCLUDES_DIR', WF_MAIN_DIR . '/wf-includes');
define( 'WF_INCLUDES_URL', WF_MAIN_URL . '/wf-includes');

define( 'WF_THEME', get_bloginfo('stylesheet_directory') );

/*
// ALPHA TESTING
define( 'WF_CDN1', '');
define( 'WF_CDN2', '');
define( 'WF_CDN3', '');
*/
?>