<div class="wrap">

	<?php
	$ct = wp_get_theme();
	?>

	<h2><?php esc_attr_e('Welcome to Wonderflux', 'wonderflux'); ?></h2>
	<div class="clear"></div>
	<p>
	<?php esc_attr_e('Wonderflux is an advanced, open source theme framework which you use as a parent theme. ', 'wonderflux'); ?>
	</p>
	<p>
	<?php esc_attr_e('It gives you a reliable and easy to update platform for creating your own bespoke Wonderflux child themes for WordPress.', 'wonderflux'); ?>
	<p><strong>
	<?php esc_attr_e('Explore the links in the help and support section below to find out more.', 'wonderflux'); ?>
	</p></strong>

	<?php

	// Only show theme info if we are using a child theme
	if ( $ct->title != 'Wonderflux Framework' ){

		?>

		<h2><?php esc_attr_e('Current Wonderflux Child Theme', 'wonderflux'); ?></h2>
		<div class="clear"></div>
		<div id="current-wf-theme">

			<?php if ( $screenshot = $ct->get_screenshot() ) : ?>
				<img src="<?php echo esc_url( $screenshot ); ?>" style="float:left; margin: 0 20px 20px 0; max-height:130px;" />
			<?php endif; ?>

			<h4><?php
				printf(__('%1$s %2$s by %3$s', 'wonderflux'), $ct->title, $ct->version, $ct->author) ; ?></h4>
			<p class="theme-description"><?php echo $ct->description; ?></p>
			<?php echo '<p>' . esc_attr__('The theme files are located in', 'wonderflux') . ' <code>'. WF_THEME_URL .'/</code></p>'; ?>

			<div class="clear"></div>

		</div>

		<?php

	}

	?>

</div>