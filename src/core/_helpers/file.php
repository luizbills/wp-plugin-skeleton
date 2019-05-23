<?php

namespace src_namespace__\functions;

function get_file_extension ( $path ) {
	return \strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
}

function rscandir ( $dir ) {
	$files = scandir( $dir );
	$result = [];

	unset( $files[ array_search( '.', $files, true ) ] );
	unset( $files[ array_search( '..', $files, true ) ] );

	if ( count( $files ) == 0) return;

	foreach( $files as $entry ) {
		$entry = "$dir/$entry";

		if ( ! is_dir( $entry ) ) {
			$result[] = $entry;
		} else {
			$result = array_merge( $result, rscandir( $entry ) );
		}
	}

	return $result;
}
