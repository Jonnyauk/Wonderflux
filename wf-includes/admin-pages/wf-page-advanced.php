<div class="wrap">
	<form action="options.php" method="post">
	<div class="icon32" id="icon-options-general"><br></div><h2>Advanced configuration</h2>

	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>

	<?php settings_fields('wf_settings_display'); ?>
	<?php do_settings_sections('wonderflux_stylelab_doc'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>
	</form>
</div>