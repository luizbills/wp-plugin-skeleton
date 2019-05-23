<?php

use src_namespace__\functions as h;

$dir = __DIR__ . '/_helpers';
$file_helper = "$dir/file.php";

// load the 'rscandir' function first
include_once $file_helper;

$files = h\rscandir( $dir );

// don't load file.php again
unset( $files[ array_search( $file_helper, $files, true ) ] );

foreach ( $files as $file ) {
	include $file;
}
