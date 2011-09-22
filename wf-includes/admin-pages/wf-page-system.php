<?php
$this->wf_latest_version_notice();
global $wpdb;

echo '<h3>'. esc_attr__('Server technical information', 'wonderflux') . '</h3>';
echo '<p>' . sprintf( __( 'PHP version installed: %s', 'wonderflux' ), phpversion() ) . '</p>';
echo '<p>' . sprintf( __( 'MySQL version installed: %s', 'wonderflux' ), $wpdb->db_version() ) . '</p>';

echo '<h3>' . esc_attr__('Wonderflux technical information', 'wonderflux') . '</h3>';
echo '<p>' . sprintf( __( 'Wonderflux version: %1$s, requires at-least WordPress v%2$s, optimised for WordPress v%3$s', 'wonderflux' ), WF_VERSION, WF_WORDPRESS_MIN, WF_WORDPRESS_OPTI ) . '</p>';
echo '<p>' . sprintf( __( 'Wonderflux server path: %s', 'wonderflux' ), WF_MAIN_DIR ) . '</p>';
echo '<p>' . sprintf( __( 'Wonderflux URL path: %s', 'wonderflux' ), WF_MAIN_URL ) . '</p>';
?>