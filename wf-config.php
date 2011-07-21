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
THE FOLLOWING ARE FOR USE IN YOUR CHILD THEME FUNCTIONS FILE

Define your own user access to Wonderflux admin menus
(user will still need the capability 'manage_options'):
- For multiple user ID's (1 and 4)
define('WF_ADMIN_ACCESS', serialize(array('1','4')));
- For single user ID (1)
define('WF_ADMIN_ACCESS', serialize(array('1')));
- For a single WordPress role (can use custom defined roles too)
define('WF_ADMIN_ACCESS', 'administrator');
- To remove the menus for all users
define('WF_ADMIN_ACCESS', 'none');
*/
if (!defined('WF_ADMIN_ACCESS')) { define( 'WF_ADMIN_ACCESS', 'administrator' ); }

// Override the inclusion of 'wf-css-core-structure.css', 'wf-css-dynamic-columns.php' and 'wf-css-dynamic-core-ie.php'
// Use the files 'style-framework.css' and 'styleframework-ie.css' in your child themes directory instead
if (!defined('WF_THEME_FRAMEWORK_REPLACE')) { define( 'WF_THEME_FRAMEWORK_REPLACE', false ); }
?>