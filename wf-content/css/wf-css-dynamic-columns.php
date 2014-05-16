<?php header("Content-type: text/css"); ?>
/**
 *
 * Wonderflux theme framework dynamic column core
 * http://wonderflux.com
 *
 * @package Wonderflux
 * @since Wonderflux 0.1
 *
 */

<?php

//TODO: This needs building into functions

$wf_grid_columnwidth_outeach = 0;

// Site width - min 400 max 2000
$wf_grid_sitewidth = $_GET['w'];
settype( $wf_grid_sitewidth, "integer" );
$wf_grid_sitewidth_accept = range(400,2000,10);

if (in_array($wf_grid_sitewidth,$wf_grid_sitewidth_accept)) {
	$wf_grid_sitewidth_out = $wf_grid_sitewidth;
} else {
	// No cheatin thanks, set sensible value
	$wf_grid_sitewidth_out = 950;
}

// Container position
$container_p = $_GET['p'];
settype( $container_p, "string" );
$container_p_accept = array('left', 'middle', 'right');

if (in_array($container_p,$container_p_accept)) {
	$container_p_out = $container_p;
} else {
	// No cheatin thanks, set sensible value
	$container_p_out = 'middle';
}

// Sidebar position
$sidebar_p = $_GET['sbp'];
settype( $sidebar_p, "string" );
$sidebar_p_accept = array('left', 'right');

if (in_array($sidebar_p,$sidebar_p_accept)) {
	$sidebar_p_out = $sidebar_p;
} else {
	// No cheatin thanks, set sensible value
	$sidebar_p_out = 'right';
}

// Columns - max 100
$wf_grid_columns = $_GET['c'];
settype( $wf_grid_columns, "integer" );
$wf_grid_columns_accept = range(2,100,1);

if (in_array($wf_grid_columns,$wf_grid_columns_accept)) {
	$wf_grid_columns_out = $wf_grid_columns;
} else {
	// No cheatin thanks, set sensible value
	$wf_grid_columns_out = 24;
}

// Column width - max 300
$wf_grid_columnwidth = $_GET['cw'];
settype( $wf_grid_columnwidth, "integer" );
$wf_grid_columnwidth_accept = range(1,300,1);

if (in_array($wf_grid_columnwidth,$wf_grid_columnwidth_accept)) {
	$wf_grid_columnwidth_out = $wf_grid_columnwidth;
} else {
	// No cheatin thanks, set sensible value
	$wf_grid_columnwidth_out = 30;
}


//Now work out gutter
$wf_grid_gutter = ($wf_grid_sitewidth_out - ($wf_grid_columns_out * $wf_grid_columnwidth_out)) / ($wf_grid_columns_out - 1);

// Sets up main container
$wf_grid_container = ".container { ";
$wf_grid_container .= "width: " . $wf_grid_sitewidth_out;
$wf_grid_container .= "px; ";

switch ($container_p_out) {

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
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns_out; $wf_grid_columnlimit++)
	{
	echo "div.span-" . $wf_grid_columnlimit;

	if ($wf_grid_columnlimit == $wf_grid_columns_out) { } else { echo ", "; }

	$wf_grid_columnwidth_outeach = $wf_grid_columnwidth_outeach+$wf_grid_columnwidth_out;
	}

echo " { float: left; margin-right: " . $wf_grid_gutter . "px; }";
echo "\n";
echo "\n";

/* The last column in a row needs this class. */
echo '.last, div.last { margin-right: 0; }';


// Setup the main columns
$wf_grid_columnwidth_outeach = $wf_grid_columnwidth_out;
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns_out; $wf_grid_columnlimit++)
	{
	$wf_grid_maincols = ".span-" . $wf_grid_columnlimit . " { width: ";
	$wf_grid_maincols .= $wf_grid_columnwidth_outeach;
	$wf_grid_maincols .= "px; ";

	// If it's the last one, we need to add this last bit of CSS
	if ($wf_grid_columnlimit == $wf_grid_columns_out) { $wf_grid_maincols .= "margin-right: 0;"; }

	$wf_grid_maincols .= "}";
	$wf_grid_maincols .= "\n";

	echo $wf_grid_maincols;

	$wf_grid_columnwidth_outeach = $wf_grid_columnwidth_outeach+$wf_grid_columnwidth_out + $wf_grid_gutter;
	}
echo "\n";


// Add these to a column to append empty cols
$wf_grid_appendwidtheach = $wf_grid_columnwidth_out + $wf_grid_gutter;
for ($wf_grid_applimit=1; $wf_grid_applimit <= ($wf_grid_columns_out - 1); $wf_grid_applimit++)
	{
	$wf_grid_mainapp = ".append-" . $wf_grid_applimit . " { padding-right: ";
	$wf_grid_mainapp .= $wf_grid_appendwidtheach;
	$wf_grid_mainapp .= "px; }";
	$wf_grid_mainapp .= "\n";

	echo $wf_grid_mainapp;

	$wf_grid_appendwidtheach = ($wf_grid_appendwidtheach+$wf_grid_columnwidth_out) + $wf_grid_gutter;
	}
echo "\n";


// Add these to a column to append empty cols
$wf_grid_prependwidtheach = $wf_grid_columnwidth_out + $wf_grid_gutter;
for ($wf_grid_preplimit=1; $wf_grid_preplimit <= ($wf_grid_columns_out - 1); $wf_grid_preplimit++)
	{
	$wf_grid_mainprep = ".prepend-" . $wf_grid_preplimit . " { padding-left: ";
	$wf_grid_mainprep .= $wf_grid_prependwidtheach;
	$wf_grid_mainprep .= "px; }";
	$wf_grid_mainprep .= "\n";

	echo $wf_grid_mainprep;

	$wf_grid_prependwidtheach = ($wf_grid_prependwidtheach+$wf_grid_columnwidth_out) + $wf_grid_gutter;
	}
echo "\n";


// Use these classes on an element to pull it into a previous column
$wf_grid_pullwidtheach = $wf_grid_columnwidth_out + $wf_grid_gutter;
for ($wf_grid_pullimit=1; $wf_grid_pullimit <= $wf_grid_columns_out; $wf_grid_pullimit++)
	{
	$wf_grid_mainpull = ".pull-" . $wf_grid_pullimit . " { margin-left: -";
	$wf_grid_mainpull .= $wf_grid_pullwidtheach - $wf_grid_gutter;
	$wf_grid_mainpull .= "px; }";
	$wf_grid_mainpull .= "\n";

	echo $wf_grid_mainpull;

	$wf_grid_pullwidtheach = ($wf_grid_pullwidtheach+$wf_grid_columnwidth_out) + $wf_grid_gutter;
	}
echo "\n";


// More CSS for pull class
for ($wf_grid_columnlimit=1; $wf_grid_columnlimit<=$wf_grid_columns_out; $wf_grid_columnlimit++)
	{
	echo ".pull-" . $wf_grid_columnlimit;

	if ($wf_grid_columnlimit == $wf_grid_columns_out) { } else { echo ", "; }

	$wf_grid_columnwidth_outeach = $wf_grid_columnwidth_outeach+$wf_grid_columnwidth_out;
	}
echo " { float: left; position: relative; }";
echo "\n";
echo "\n";


// Use these classes on an element to push it into the next column
$wf_grid_pushwidtheach = $wf_grid_columnwidth_out + $wf_grid_gutter;
for ($wf_grid_pushlimit=1; $wf_grid_pushlimit <= $wf_grid_columns_out; $wf_grid_pushlimit++)
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

	$wf_grid_pushwidtheach = ($wf_grid_pushwidtheach+$wf_grid_columnwidth_out) + $wf_grid_gutter;
	}

// By default the content will be on the left and sidebar right
// By floating the content right it puts the content on the right and sidebar left
// Only need to check against left - which needs the content float right!
if ($sidebar_p_out =='left') {
	$wf_grid_layout = "\n";
	$wf_grid_layout .= '#content { float: right; margin-right: 0; }';
	$wf_grid_layout .= "\n";
}else {
	$wf_grid_layout = '';
	// Silence is golden... until we have a second sidebar option
}
echo $wf_grid_layout;





///////////////////////////////// NEW RESPONSIVE GRID





// 1 - Basic rules

$rwd_class = 'rwd-block';
$rwd_width = '80';

$rwd_columns = ( is_numeric($_GET['c']) ) ? $_GET['c'] : 24;
$rwd_columns = ( $wf_grid_columns < 101 ) ? $wf_grid_columns : 24;
$rwd_column_width = 100 / $rwd_columns;

$rwd_class_append = 'rwd-space-left';
$rwd_class_prepend = 'rwd-space-right';
$rwd_class_push_left = 'rwd-push-left';
$rwd_class_push_right = 'rwd-push-right';

echo "\n";
echo '/*New responsive grid whoot*/';
echo "\n" . "\n";


// 1 - Basic rules

echo '.container { width: ' . $rwd_width . '%; margin: 0 auto; }';
echo "\n" . "\n";


// 2 - Float all columns

for ($column_limit=1; $column_limit<=$rwd_columns; $column_limit++) {
	echo 'div.' . $rwd_class . '-' . $column_limit;
	echo ($column_limit == $rwd_columns) ? '' : ', ';
}
echo " { float: left; margin: 0; }";
echo "\n" . "\n";


echo "\n" . "\n" . 'BETA' .  "\n" . "\n";


// 3 - Main column width

for ($column_limit=1; $column_limit<=$rwd_columns; $column_limit++) {
	echo '.' . $rwd_class . '-' . $column_limit;
	echo ' { width: ' . $rwd_column_width*$column_limit . '%; ' . "} " . "\n";
}
echo "\n" . "\n";

// 4 - append

for ($column_limit=1; $column_limit<=$rwd_columns; $column_limit++) {
	echo '.' . $rwd_class_append . '-' . $column_limit;
	echo ' { padding: 0 0 0 ' . $rwd_column_width*$column_limit . '%; ' . "} " . "\n";
}
echo "\n" . "\n";

// 5 - prepend

for ($column_limit=1; $column_limit<=$rwd_columns; $column_limit++) {
	echo '.' . $rwd_class_prepend . '-' . $column_limit;
	echo ' { padding: 0 ' . $rwd_column_width*$column_limit . '% 0 0; ' . "} " . "\n";
}
echo "\n" . "\n";

// 6 - push left (pull)

for ($column_limit=1; $column_limit<=$rwd_columns; $column_limit++) {
	echo '.' . $rwd_class_push_left . '-' . $column_limit;
	echo ' { margin: 0 -' . $rwd_column_width*$column_limit . '% 0 0; ' . "} " . "\n";
}
echo "\n" . "\n";

// 7 - push right (push)

for ($column_limit=1; $column_limit<=$rwd_columns; $column_limit++) {
	echo '.' . $rwd_class_push_right . '-' . $column_limit;
	echo ' { margin: 0 0 0 -' . $rwd_column_width*$column_limit . '%; ' . "} " . "\n";
}
echo "\n" . "\n";

?>