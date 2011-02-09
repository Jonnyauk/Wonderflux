<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>Main template dimensions</h2>
	<form action="options.php" method="post">
	<?php settings_fields('wf_settings_display'); ?>
	<?php do_settings_sections('wonderflux_stylelab'); ?>
	<p class="submit">
		<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Template settings'); ?>" />
	</p>
	</form>
</div>


<h3>DEBUG: VALID COMBINATIONS</h3>
<p>Currently there is no validation on combinations of numbers, so it is possible to get fractional widths.</p>
<p>For testing purposes, try out the following valid combinations - these all add up correctly:</p>
<ul>
	<li>width=960 x cols=16 x colwidth=45</li>
	<li>width=960 x cols=36 x colwidth=15</li>
	<li>width=950 x cols=20 x colwidth=38</li>
	<li>width=950 x cols=24 x colwidth=30</li>
	<li>width=950 x cols=48 x colwidth=10</li>
	<li>width=760 x cols=24 x colwidth=24</li>
	<li>width=760 x cols=20 x colwidth=19</li>
</ul>