<?php
/*
 * Core Wonderflux footer template include
 * This will be over-ridden if you create a file of the same name in your child theme
 * NOTE: Most people can use simple location aware template parts in your child theme to control whats actually being displayed
 * EG footer-content.php and/or footer-content-LOCATIONHERE.php
 *
 * @package Wonderflux
 */

wffooter_before_wrapper(); //WF display hook
wffooter_before_container(); //WF display hook
?>

<div class="container" id="footer-bg-content">

	<?php
	wffooter_before_content(); //WF display hook
	wfx_get_template_part('part=footer-content'); // Setup all location aware template parts
	wffooter_after_content(); //WF display hook
	?>

</div>

<?php
wffooter_after_container(); //WF display hook
wffooter_after_wrapper(); //WF display hook
wfbody_after_wrapper(); //WF display hook
wf_footer();  //WF display hook
wp_footer();  //Standard WordPress display hook
?>
</body>
</html>