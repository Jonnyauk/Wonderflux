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
		<p><strong><?php esc_attr_e('IMPORTANT: VALID COMBINATIONS', 'wonderflux'); ?></strong></p>
		<p><?php esc_attr_e('It is currently possible to get fractional widths, or layout sizes that do not work correctly.', 'wonderflux'); ?></p>
		<p><?php esc_attr_e('Try out the following common valid combinations:', 'wonderflux'); ?></p>
		<ul>
			<li><?php esc_attr_e('width=960 x columns=16 x column width=45 (valid suggested relative sizes: full, half, quarter, eigth)', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=960 x columns=36 x column width=15 (valid suggested relative sizes: full, half, third, quarter, sixth, ninth, twelveth)', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=950 x columns=20 x column width=38 (valid suggested relative sizes: full, half, third, quarter, fifth, tenth)', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=950 x columns=24 x column width=30 (valid suggested relative sizes: full, half, third, quarter, sixth, eighth, twelveth)', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=950 x columns=48 x column width=10 (valid suggested relative sizes: full, half, third, quarter, sixth, eighth, twelveth)', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=760 x columns=24 x column width=24 (valid suggested relative sizes: full, half, third, quarter, sixth, eighth, twelveth)', 'wonderflux'); ?></li>
			<li><?php esc_attr_e('width=760 x columns=20 x column width=19 (valid suggested relative sizes: full, half, third, quarter, fifth, tenth)', 'wonderflux'); ?></li>
		</ul>
	</div>

	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>
	</form>
</div>