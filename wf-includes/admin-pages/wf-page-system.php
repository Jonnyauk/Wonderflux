<?php

$this->wf_latest_version_notice();

global $wpdb;
$php_version_check = phpversion();
$mysql_version = $wpdb->db_version();

$output_server = '<h3>Server technical information</h3>';
$output_server .= '<p>PHP version installed: ' . phpversion() . '<p>';
$output_server .= '<p>MySQL version installed: ' . $mysql_version . '<p>';

$output_tech = '<h3>Wonderflux technical information</h3>';
$output_tech .= '<p>Wonderflux version: ' . WF_VERSION . ', requires at-least WordPress v' . WF_WORDPRESS_MIN . ', optimised for WordPress v' . WF_WORDPRESS_OPTI . '</p>';
$output_tech .= '<p>Wonderflux server path: ' . WF_MAIN_DIR . '</p>';
$output_tech .= '<p>Wonderflux URL path: ' . WF_MAIN_URL . '</p>';

echo $output_server;
echo $output_tech;

?>