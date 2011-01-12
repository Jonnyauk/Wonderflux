<?php

//Include things in one place to include core stuff when we need it

// require_once?

require(TEMPLATEPATH.'/wf-config.php');
require( WF_INCLUDES_DIR .'/wf-version.php');
require( WF_INCLUDES_DIR .'/wf-helper-functions.php');
require( WF_INCLUDES_DIR .'/wf-display-hooks.php');

require( WF_INCLUDES_DIR .'/wf-display-extras.php');


// Just include all the xtra display functions in the front-end, not everywhere!
/*
function wf_engine_template() {
require( WF_INCLUDES_DIR .'/wf-display-extras.php');
}

add_action('get_header', 'wf_engine_template');
*/

?>