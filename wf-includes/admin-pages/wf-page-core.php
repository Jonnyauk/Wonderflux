<div class="wrap">

	<?php
	$themes = get_allowed_themes();
	$ct = current_theme_info();
	unset($themes[$ct->name]);
	//$themes = array_slice( $themes, $start, $per_page );
	?>

	<h3><?php esc_attr_e('Current Wonderflux Child Theme', 'wonderflux'); ?></h3>
	<div id="current-theme">
	<?php if ( $ct->screenshot ) : ?>
	<img src="<?php echo $ct->theme_root_uri . '/' . $ct->stylesheet . '/' . $ct->screenshot; ?>" alt="<?php  _e('Current theme preview', 'wonderflux'); ?>" />
	<?php endif; ?>
	<h4><?php
		/* translators: 1: theme title, 2: theme version, 3: theme author */
		printf(__('%1$s %2$s by %3$s'), $ct->title, $ct->version, $ct->author) ; ?></h4>
	<p class="theme-description"><?php echo $ct->description; ?></p>
	<?php echo '<p>' . esc_attr__('The theme files are located in', 'wonderflux') . ' <code>'. WF_THEME .'</code></p>'; ?>

<div class="clear"></div>

<div class="icon32" id="icon-options-general"><br></div>
<?php echo '<h2>' . get_current_theme(). ' ' . esc_attr__('display functions', 'wonderflux') . '</h2>'; ?>
<?php echo '<p>' . esc_attr__('COMING SOON - Theme designers will be able to enable options to control theme scripts functionality.', 'wonderflux') . '</p>'; ?>

</div>