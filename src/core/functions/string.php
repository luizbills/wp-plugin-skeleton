<?php

namespace src_namespace__\functions;

function str_after ( $string, $search ) {
	return '' === $search ? $string : \array_reverse( \explode( $search, $string, 2 ) )[0];
}

function str_before ( $string, $search ) {
	return '' === $search ? $string : \explode( $search, $string )[0];
}

function str_length ( $string, $encoding = null ) {
	if ( null !== $encoding ) {
		return \mb_strlen( $string, $encoding );
	}
	return \mb_strlen( $string );
}

function str_lower ( $string ) {
	return \mb_strtolower( $string, 'UTF-8' );
}

function str_upper ( $string ) {
	return \mb_strtoupper( $string, 'UTF-8' );
}

function str_slug ( $text, $separator = '-' ) {
	$slug = \remove_accents( $text ); // Convert to ASCII

	$find = [
		' ',
		'_',
		'-',
	];
	$replace = $separator;

	// Standard replacements
	$slug = \str_replace( $find, $replace, $slug );
	// Replace all non-alphanumeric by $separator
	$slug = \preg_replace( "/[^A-Za-z0-9\\$separator]/", $separator, $slug );
	// Replace any more than one $separator in a row
	$slug = \preg_replace( "/\\$separator+/", $separator, $slug );
	// Remove last $separator if at the end
	$slug = \preg_replace( "/\\$separator\$/", '', $slug );
	// Remove first $separator if at the beginning
	$slug = \preg_replace( "/^\\$separator/", '', $slug );
	// Lowercase
	$slug = \strtolower( $slug );

	return $slug;
}

function str_starts_with ( $string, $search ) {
	return \substr( $string, 0, \strlen( $search ) ) === $search;
}

function str_ends_with ( $string, $search ) {
	return \substr( $string, -\strlen( $search ) ) === $search;
}

function str_add_quotes ( $string, $double = true ) {
	$q = $double ? "\"" : '\'';
	return $q . $string . $q;
}