<div class="wrap">

	<form action="options.php" method="post">

		<h2><?php esc_attr_e('Theme layout configuration', 'wonderflux'); ?></h2>

		<div class="clear"></div>

		<p>
		<?php esc_attr_e('Configure dynamic CSS layout rules (note that filters override these options.)', 'wonderflux'); ?>
		</p>

		<div id="wfx_fields_display">
			<?php
			settings_fields('wf_settings_display');
			do_settings_sections('wonderflux_stylelab_grid_core');
			?>

			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save core configuration', 'wonderflux'); ?>" />
			</p>

			<?php
			do_settings_sections('wonderflux_stylelab_grid');
			do_settings_sections('wonderflux_stylelab');
			do_settings_sections('wonderflux_page_templates');
			?>
		</div>

		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save settings', 'wonderflux'); ?>" />
		</p>

	</form>

</div>