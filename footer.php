<?php
/*
 * Core Wonderflux footer template include
 * This will be over-ridden if you create a file of the same name in your child theme
 * NOTE: Most people can use simple location aware template parts in your child theme to control whats actually being displayed
 * EG footer-content.php and/or footer-content-LOCATIONHERE.php
 *
 * @package Wonderflux
 */

// Setup Core Wonderflux helper class
$wf_core = new wflux_core;
?>

			<?php wffooter_before_wrapper(); //WF display hook ?>

			<div class="wrapper" id="footer-bg-main">
				<div class="wrapper" id="footer-bg-primary">
					<div class="wrapper" id="footer-bg-secondary">



						<?php wffooter_before_container(); //WF display hook ?>

						<div class="container" id="footer-bg-content">

							<?php wffooter_before_content(); //WF display hook ?>

							<?php $wf_core->wf_get_template_part('part=footer-content'); // Setup all location aware template parts ?>

							<?php wffooter_after_content(); //WF display hook ?>

						</div> <?php /*** Close main-bg-content container ***/ ?>

						<?php wffooter_after_container(); //WF display hook ?>



					</div> <?php /*** Close footer-bg-secondary wrapper ***/ ?>
				</div> <?php /*** Close footer-bg-primary wrapper ***/ ?>
			</div> <?php /*** Close footer-bg-main wrapper ***/ ?>
			<?php wffooter_after_wrapper(); //WF display hook ?>


		</div> <?php /*** Close site-bg-secondary ***/ ?>
	</div> <?php /*** Close site-bg-primary ***/ ?>
</div> <?php /*** Close site-bg-main wrapper ***/ ?>
<?php wfbody_after_wrapper(); //WF display hook ?>

<?php wf_footer();  //WF display hook ?>
<?php wp_footer();  //Standard WordPress display hook ?>
</body>
</html>