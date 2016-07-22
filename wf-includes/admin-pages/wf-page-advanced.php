<div class="wrap">
	<form action="options.php" method="post">
	<?php settings_fields('wf_settings_display'); ?>
	<?php
	// Use Yoast SEO to configure if installed and active
	if ( !defined('WPSEO_VERSION') ) {
		do_settings_sections('wonderflux_stylelab_fb');
		echo '<p class="submit">';
		echo '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr('Save all settings','wonderflux') . '" />';
		echo '</p>';
	}
	?>
	<?php do_settings_sections('wonderflux_stylelab_doc'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save all settings','wonderflux'); ?>" />
	</p>
	</form>
</div>