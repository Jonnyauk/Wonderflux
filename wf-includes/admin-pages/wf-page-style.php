<div class="wrap">
	<form action="options.php" method="post">
	<div class="icon32" id="icon-options-general"><br></div><h2><?php esc_attr_e('Column size configuration', 'wonderflux'); ?></h2>

	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>

	<div id="wfx_fields_display">
		<?php settings_fields('wf_settings_display'); ?>
		<?php do_settings_sections('wonderflux_stylelab'); ?>
	</div>

	<div id="wfx_fields_display_info">
		<p><strong><?php esc_attr_e('DEBUG: VALID COMBINATIONS', 'wonderflux'); ?></strong></p>
		<p><?php esc_attr_e('Currently there is no validation on combinations of numbers, so it is possible to get fractional widths.', 'wonderflux'); ?></p>
		<p><?php esc_attr_e('For testing purposes, try out the following valid combinations - these all add up correctly:', 'wonderflux'); ?></p>
		<ul>
			<li><?php esc_attr_e('width=960 x cols=16 x colwidth=45', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=960 x cols=36 x colwidth=15', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=950 x cols=20 x colwidth=38', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=950 x cols=24 x colwidth=30', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=950 x cols=48 x colwidth=10', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=760 x cols=24 x colwidth=24', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=760 x cols=20 x colwidth=19', 'wonderflux'); ?></li>
		</ul>
	</div>

	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>
	</form>
</div>