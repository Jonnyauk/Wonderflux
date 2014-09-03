<div class="wrap">

	<?php
	// Backpat - depreciated function current_theme_info() in WordPress 3.4
	$ct = ( WF_WORDPRESS_VERSION < 3.4 ) ? current_theme_info() : wp_get_theme();
	?>

	<h2><?php esc_attr_e('Welcome to Wonderflux', 'wonderflux'); ?></h2>
	<div class="clear"></div>
	<p>
	<?php esc_attr_e('Wonderflux is an advanced, open source theme framework. ', 'wonderflux'); ?>
	<?php esc_attr_e('It is built to provide a solid, reliable and easily updateable base for creating bespoke WordPress themes.', 'wonderflux'); ?>
	</p>
	<p>
	<?php esc_attr_e('The best way to use Wonderflux is to create a child theme. ', 'wonderflux'); ?>
	<?php esc_attr_e('Explore the links in the help and support section at the bottom of this page to find out more.', 'wonderflux'); ?>
	</p>

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

</div>