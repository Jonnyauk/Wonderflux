<?php

$this->wf_latest_version_notice();

$output_tech = '<h3>Wonderflux technical information</h3>';
$output_tech .= '<p>Your running Wonderflux version: ' . WF_VERSION . ', requires at-least WordPress v' . WF_WORDPRESS_MIN . ', optimised for WordPress v' . WF_WORDPRESS_OPTI . '</p>';
$output_tech .= '<p>Your current Wonderflux server path: ' . WF_MAIN_DIR . '</p>';
$output_tech .= '<p>Your current Wonderflux URL path : ' . WF_MAIN_URL . '</p>';

echo $output_tech;

?>