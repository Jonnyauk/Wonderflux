<?php
header("Content-type: text/css");
$desc = '/**
 * Wonderflux theme framework dynamic column core (legacy IE support)
 * http://wonderflux.com
 *
 * @package Wonderflux
 * @since Wonderflux 0.2
 */
 ';
echo $desc;

// Columns - max 80
$wf_grid_columns = $_GET['c'];
settype( $wf_grid_columns, "integer" );
$wf_grid_columns_accept = range(4,80,1);

if (in_array($wf_grid_columns,$wf_grid_columns_accept)) {
	$wf_grid_columns_out = $wf_grid_columns;
} else {
	// No cheatin thanks, set sensible value
	$wf_grid_columns_out = 24;
}
?>
<?php
switch ($container_p) {

	case 'left' :
		$wf_site_position .= "left";
	break;

	case 'right' :
		$wf_site_position .= "right";
	break;

	default :
		// If in doubt make it centered
		$wf_site_position .= "center";
	break;

}
?>

body { text-align: <?php echo $wf_site_position; ?>; }
.container { text-align: left; }
<?php

// Setup the main columns

echo '* html .column, ';
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns; $wf_grid_columnlimit++)
	{
		$wf_grid_maincols = "* html div.span-".$wf_grid_columnlimit;


	if ($wf_grid_columnlimit==$wf_grid_columns) {
		//Last one
		$wf_grid_maincols .= ' { display:inline; overflow-x: hidden; }';
	} else {
		$wf_grid_maincols .= ", ";
	}

	echo $wf_grid_maincols;

	}
?>


/** Elements **/

/* Fixes incorrect styling of legend in IE6. */
* html legend { margin:0px -8px 16px 0; padding:0; }

/* Fixes incorrect placement of ol numbers in IE6/7. */
ol { margin-left:2em; }

/* Fixes wrong line-height on sup/sub in IE. */
sup { vertical-align:text-top; }
sub { vertical-align:text-bottom; }

/* Fixes IE7 missing wrapping of code elements. */
html>body p code { *white-space: normal; }

/* IE 6&7 has problems with setting proper <hr> margins. */
hr  { margin:-8px auto 11px; }

/* Explicitly set interpolation, allowing dynamically resized images to not look horrible */
img { -ms-interpolation-mode:bicubic; }

/** Clearing **/

/* Makes clearfix actually work in IE */
.clearfix, .container { display:inline-block; }
* html .clearfix,
* html .container { height:1%; }

/** Forms **/

/* Fixes padding on fieldset */
fieldset { padding-top:0; }

/* Makes classic textareas in IE 6 resemble other browsers */
textarea { overflow:auto; }

/* Fixes rule that IE 6 ignores */
input.text, input.title, textarea { background-color:#fff; border:1px solid #bbb; }
input.text:focus, input.title:focus { border-color:#666; }
input.text, input.title, textarea, select { margin:0.5em 0; }
input.checkbox, input.radio { position:relative; top:.25em; }

/* Fixes alignment of inline form elements */
form.inline div, form.inline p { vertical-align:middle; }
form.inline label { position:relative;top:-0.25em; }
form.inline input.checkbox, form.inline input.radio,
form.inline input.button, form.inline button {
  margin:0.5em 0;
}
button, input.button { position:relative;top:0.25em; }