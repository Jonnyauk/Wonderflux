<?php
/**
 * The core sidebar include
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 * @since Wonderflux 0.6
 */
// Setup Core Wonderflux helper class
$wf_core = new wflux_core;
?>
<?php $wf_core->wf_get_template_part('part=sidebar-content'); // Setup location aware template parts ?>