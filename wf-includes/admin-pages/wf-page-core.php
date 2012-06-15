<div class="wrap">

	<?php
	// Backpat - depreciated function current_theme_info() in WordPress 3.4
	$ct = ( WF_WORDPRESS_VERSION < 3.4 ) ? current_theme_info() : wp_get_theme();
	?>

	<h3><?php esc_attr_e('Current Wonderflux Child Theme', 'wonderflux'); ?></h3>
	<div id="current-wf-theme">

		<?php if ( $screenshot = $ct->get_screenshot() ) : ?>
			<img src="<?php echo esc_url( $screenshot ); ?>" style="float:left; margin: 0 20px 20px 0;" />
		<?php endif; ?>

		<h4><?php
			/* translators: 1: theme title, 2: theme version, 3: theme author */
			printf(__('%1$s %2$s by %3$s'), $ct->title, $ct->version, $ct->author) ; ?></h4>
		<p class="theme-description"><?php echo $ct->description; ?></p>
		<?php echo '<p>' . esc_attr__('The theme files are located in', 'wonderflux') . ' <code>'. WF_THEME_URL .'</code></p>'; ?>

		<div class="clear"></div>

		<div class="icon32" id="icon-options-general"><br></div>

		<?php
		// Backpat - depreciated function get_current_theme() in WordPress 3.4
		$this_current_theme = ( WF_WORDPRESS_VERSION < 3.4 ) ? get_current_theme() : wp_get_theme()->Name;

		echo '<h2>' . apply_filters( 'wflux_options_theme_name_header', $this_current_theme ) . ' ' . esc_attr__('display functions', 'wonderflux') . '</h2>';
		echo '<p>' . esc_attr__('COMING SOON - Theme designers will be able to enable options to control theme scripts functionality.', 'wonderflux') . '</p>';
		?>
	</div>

</div>