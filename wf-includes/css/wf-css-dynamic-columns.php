<?php
header("Content-type: text/css");

$wf_grid_sitewidth = $_GET['w'];
$container_p = $_GET['p'];
$sidebar_p = $_GET['sbp'];
$wf_grid_columns = $_GET['c'];
$wf_grid_columnwidth = $_GET['cw'];

//Now work out gutter
$wf_grid_gutter = ($wf_grid_sitewidth - ($wf_grid_columns * $wf_grid_columnwidth)) / ($wf_grid_columns - 1);

//Now add on px for neatness - more trouble than it's worth, need these numbers for special things!
//$wf_grid_gutter = $wf_grid_gutter . 'px';
//$wf_grid_sitewidth = $wf_grid_sitewidth . 'px';
//$wf_grid_columnwidth = $wf_grid_columnwidth . 'px';


// Sets up main container
$wf_grid_container = ".container { ";
$wf_grid_container .= "width: " . $wf_grid_sitewidth;
$wf_grid_container .= "px; ";

switch ($container_p) {

	case 'left' :
		$wf_grid_container .= "margin: 0 auto 0 0; }";
	break;

	case 'right' :
		$wf_grid_container .= "margin: 0 0 0 auto; }";
	break;

	default :
		// If in doubt make it centered
		$wf_grid_container .= "margin: 0 auto; }";
	break;

}

echo  $wf_grid_container;
echo "\n";
echo "\n";


// Sets up basic grid floating and margin
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns; $wf_grid_columnlimit++)
	{
	echo "div.span-" . $wf_grid_columnlimit;

	if ($wf_grid_columnlimit == $wf_grid_columns) { } else { echo ", "; }

	$wf_grid_columnwidtheach = $wf_grid_columnwidtheach+$wf_grid_columnwidth;
	}

echo " { float: left; margin-right: " . $wf_grid_gutter . "px; }";
echo "\n";
echo "\n";

/* The last column in a row needs this class. */
echo '.last, div.last { margin-right: 0; }';


// Setup the main columns
$wf_grid_columnwidtheach = $wf_grid_columnwidth;
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns; $wf_grid_columnlimit++)
	{
	$wf_grid_maincols = ".span-" . $wf_grid_columnlimit . " { width: ";
	$wf_grid_maincols .= $wf_grid_columnwidtheach;
	$wf_grid_maincols .= "px; ";

	// If it's the last one, we need to add this last bit of CSS
	if ($wf_grid_columnlimit == $wf_grid_columns) { $wf_grid_maincols .= "margin-right: 0;"; }

	$wf_grid_maincols .= "}";
	$wf_grid_maincols .= "\n";

	echo $wf_grid_maincols;

	$wf_grid_columnwidtheach = $wf_grid_columnwidtheach+$wf_grid_columnwidth + $wf_grid_gutter;
	}
echo "\n";


// Add these to a column to append empty cols
$wf_grid_appendwidtheach = $wf_grid_columnwidth + $wf_grid_gutter;
for ($wf_grid_applimit=1; $wf_grid_applimit <= ($wf_grid_columns - 1); $wf_grid_applimit++)
	{
	$wf_grid_mainapp = ".append-" . $wf_grid_applimit . " { padding-right: ";
	$wf_grid_mainapp .= $wf_grid_appendwidtheach;
	$wf_grid_mainapp .= "px; }";
	$wf_grid_mainapp .= "\n";

	echo $wf_grid_mainapp;

	$wf_grid_appendwidtheach = ($wf_grid_appendwidtheach+$wf_grid_columnwidth) + $wf_grid_gutter;
	}
echo "\n";


// Add these to a column to append empty cols
$wf_grid_prependwidtheach = $wf_grid_columnwidth + $wf_grid_gutter;
for ($wf_grid_preplimit=1; $wf_grid_preplimit <= ($wf_grid_columns - 1); $wf_grid_preplimit++)
	{
	$wf_grid_mainprep = ".prepend-" . $wf_grid_preplimit . " { padding-left: ";
	$wf_grid_mainprep .= $wf_grid_prependwidtheach;
	$wf_grid_mainprep .= "px; }";
	$wf_grid_mainprep .= "\n";

	echo $wf_grid_mainprep;

	$wf_grid_prependwidtheach = ($wf_grid_prependwidtheach+$wf_grid_columnwidth) + $wf_grid_gutter;
	}
echo "\n";


// Use these classes on an element to pull it into a previous column
$wf_grid_pullwidtheach = $wf_grid_columnwidth + $wf_grid_gutter;
for ($wf_grid_pullimit=1; $wf_grid_pullimit <= $wf_grid_columns; $wf_grid_pullimit++)
	{
	$wf_grid_mainpull = ".pull-" . $wf_grid_pullimit . " { margin-left: -";
	$wf_grid_mainpull .= $wf_grid_pullwidtheach - $wf_grid_gutter;
	$wf_grid_mainpull .= "px; }";
	$wf_grid_mainpull .= "\n";

	echo $wf_grid_mainpull;

	$wf_grid_pullwidtheach = ($wf_grid_pullwidtheach+$wf_grid_columnwidth) + $wf_grid_gutter;
	}
echo "\n";


// More CSS for pull class
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns; $wf_grid_columnlimit++)
	{
	echo ".pull-" . $wf_grid_columnlimit;

	if ($wf_grid_columnlimit == $wf_grid_columns) { } else { echo ", "; }

	$wf_grid_columnwidtheach = $wf_grid_columnwidtheach+$wf_grid_columnwidth;
	}
echo " { float: left; position: relative; }";
echo "\n";
echo "\n";


// Use these classes on an element to push it into the next column
$wf_grid_pushwidtheach = $wf_grid_columnwidth + $wf_grid_gutter;
for ($wf_grid_pushlimit=1; $wf_grid_pushlimit <= $wf_grid_columns; $wf_grid_pushlimit++)
	{

	$wf_grid_mainpush = ".push-" . $wf_grid_pushlimit . " { margin: 0 -";
	$wf_grid_mainpush .= $wf_grid_pushwidtheach;
	$wf_grid_mainpush .= "px ";
	$wf_grid_mainpush .= "1.5em ";
	$wf_grid_mainpush .= $wf_grid_pushwidtheach;
	$wf_grid_mainpush .= "px ";
	$wf_grid_mainpush .= "}";
	$wf_grid_mainpush .= "\n";

	echo $wf_grid_mainpush;

	$wf_grid_pushwidtheach = ($wf_grid_pushwidtheach+$wf_grid_columnwidth) + $wf_grid_gutter;
	}

// By default the content will be on the left and sidebar right
// By floating the content right it puts the content on the right and sidebar left
$sidebar_p_out = $sidebar_p;
$sidebar_p_out = 'right' ? 'right' : 'left'; //Yes - looks wrong way round but its what we need for CSS!

if ($sidebar_p_out !='left') {
	$wf_grid_layout = "\n";
	$wf_grid_layout .= '#content { float: right; margin-right: 0; }';
	$wf_grid_layout .= "\n";
}
echo $wf_grid_layout;
?>