<?php
/**
 * Wonderflux sidebar content template
 *
 * This will be over-ridden if you create a file of the same name in your child theme
 *
 * @package Wonderflux
 */

wfsidebar_before_all(); //WF display hook

$hook_where = wfx_info_location();

//WF location aware display hook
$wfx_sb_hook_before = 'wfsidebar_before_'.$hook_where;
$wfx_sb_hook_before();

wfx_get_template_part('part=sidebar-content'); // Setup all location aware template parts

//WF location aware display hook
$wfx_sb_hook_after = 'wfsidebar_after_'.$hook_where;
$wfx_sb_hook_after();

wfsidebar_after_all(); //WF display hook
?>