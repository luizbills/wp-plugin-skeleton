<?php

use src_namespace__\functions as h;

$dir = __DIR__ . '/functions';

// load the 'rscandir' function first
include_once "$dir/file.php";

foreach ( h\rscandir( $dir ) as $file ) {
	include $file;
}
