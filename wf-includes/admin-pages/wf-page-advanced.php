<div class="wrap">
	<form action="options.php" method="post">
	<?php settings_fields('wf_settings_display'); ?>
	<?php do_settings_sections('wonderflux_stylelab_fb'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save all settings','wonderflux') ?>" />
	</p>
	<?php do_settings_sections('wonderflux_stylelab_doc'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save all settings','wonderflux'); ?>" />
	</p>
	</form>
</div>