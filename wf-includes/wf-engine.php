<?php
//Include things in one place to include core stuff when we need it
load_template(TEMPLATEPATH . '/wf-config.php');
load_template(WF_INCLUDES_DIR . '/wf-version.php');
load_template(WF_INCLUDES_DIR . '/wf-helper-functions.php');
load_template(WF_INCLUDES_DIR . '/wf-display-hooks.php');

// Advanced theme functionality
load_template(WF_INCLUDES_DIR . '/wf-display-extras.php');
load_template(WF_INCLUDES_DIR . '/wf-theme-core.php');
?>