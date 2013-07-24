<div class="wrap">
	<form action="options.php" method="post">
	<div class="icon32" id="icon-options-general"><br></div><h2><?php esc_attr_e('Theme layout configuration', 'wonderflux'); ?></h2>
	<p>
	<?php esc_attr_e('It is currently possible to set layout sizes that do not work correctly in the layout cloumn/grid settings. For suggested size configurations, click on the help tab top right.', 'wonderflux'); ?>
	</p>
	<p><strong>
	<?php esc_attr_e('NOTE: For suggested size configurations, click on the help tab top right.', 'wonderflux'); ?>
	</p></strong>
	<p>
	<?php esc_attr_e('You can filter most of these values in your child theme, allowing finer grain control (for instance, conditional on page/type of view). Filters will over-ride any values set here.', 'wonderflux'); ?>
	</p>

	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>

	<div id="wfx_fields_display">
		<?php settings_fields('wf_settings_display'); ?>
		<?php do_settings_sections('wonderflux_stylelab_grid'); ?>
		<?php do_settings_sections('wonderflux_stylelab'); ?>
	</div>

	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>
	</form>
</div>