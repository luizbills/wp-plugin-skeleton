<?php

function slugify ( $text ) {
	$sanitized_text = remove_accents( $text ); // Convert to ASCII
	$invalid = [
		' ' => '-',
		'_' => '-',
	];
	$sanitized_text = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_text );
	$sanitized_text = preg_replace( '/[^A-Za-z0-9- ]/', '', $sanitized_text ); // Remove all non-alphanumeric except .
	$sanitized_text = preg_replace( '/-+/', '-', $sanitized_text ); // Replace any more than one - in a row
	$sanitized_text = preg_replace( '/-$/', '', $sanitized_text ); // Remove last - if at the end
	$sanitized_text = preg_replace( '/^-/', '', $sanitized_text ); // Remove first - if at the begin
	$sanitized_text = strtolower( $sanitized_text ); // Lowercase

	return $sanitized_text;
}

function prefixify ( $text ) {
	return str_replace( '-', '_', slugify( $text ) ) . '_';
}

// clear terminal screen
function cls () {
	print( "\033[2J\033[;H" );
}

// recursive rmdir
function rrmdir ( $src ) {
	$dir = opendir( $src );
	while(false !== ( $file = readdir($dir) ) ) {
		if ( ( $file != '.' ) && ( $file != '..' ) ) {
			$full = $src . '/' . $file;
			if ( is_dir( $full ) ) {
				rrmdir( $full );
			}
			else {
				unlink( $full );
			}
		}
	}
	closedir( $dir );
	rmdir( $src );
}

function remove_accents ( $str ) {
	$search = explode( "," , "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ" );
	$replace = explode( "," , "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE" );
	return str_replace( $search, $replace, $str );
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

function mv ( $origin, $destination ) {
	$origin = escapeshellarg( $origin );
	$destination = escapeshellarg( $destination );
	return shell_exec( "mv $origin $destination" );
}
