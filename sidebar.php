<?php
/**
 * Wonderflux primary sidebar template
 *
 * Customise this in your child theme by:
 * - Using the Wonderflux hooks in this file - there are file specific and general ones
 * - Using the 'sidebar-content' template part 'sidebar-content-404.php' or 'sidebar-content.php' (fallback if location specific file not available)
 * - Copying this file to your child theme and amending - it will automatically be used instead of this file
 * - IMPORTANT - if you do this, ensure you keep all Wonderflux hooks intact!
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