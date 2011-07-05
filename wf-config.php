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

if (!defined('WF_ADMIN_ACCESS')) { define( 'WF_ADMIN_ACCESS', 'administrator' ); }
/*
NOTE: To define your own user access IN YOUR CHILD THEME to Wonderflux admin menus use either of the  (they will still need the capability 'manage_options'):
- For multiple user ID's (1 and 4)
define('WF_ADMIN_ACCESS', serialize(array('1','4')));
- For single user ID (1)
define('WF_ADMIN_ACCESS', serialize(array('1')));
- For a single WordPress role (can use custom defined roles too)
define('WF_ADMIN_ACCESS', 'administrator');
- To remove the menus for all users
define('WF_ADMIN_ACCESS', 'none');
*/
?>