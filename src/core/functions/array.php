<?php

namespace src_namespace__\functions;

function wrap ( $value ) {
	return \is_array( $value ) ? $value : [ $value ];
}

function array_get ( $arr, $key, $default = null ) {
	return isset( $arr[ $key ] ) ? $arr[ $key ] : $default;
}

function array_head ( $arr ) {
	return \reset( $arr );
}

function array_tail ( $arr ) {
	return \array_slice( $arr, 1 );
}

function array_divide ( $arr ) {
	return [ \array_keys( $arr ), \array_values( $arr ) ];
}

function array_pull ( &$arr, $key, $default = null ) {
	$value = array_get( $arr, $key, $default );
	unset( $arr[ $key ] );
	return $value;
}

function array_forget ( &$arr, $keys ) {
	foreach ( (array) $keys as $key ) {
		if ( isset( $arr[ $key ] ) ) {
			unset( $arr[ $key ] );
		}
	}
	return $arr;
}

function array_only ( $arr, $keys ) {
	return \array_intersect_key( $arr, \array_flip( (array) $keys ) );
}

function array_group_by_prefix ( $arr, $prefix ) {
	$group = [];
	foreach ( $arr as $key => $value ) {
		if ( str_starts_with( $key, $prefix ) ) {
			$new_key = \substr( $key, strlen( $prefix ) );
			$group[ $new_key ] = $value;
		}
	}
	return $group;
}

// array_assign function was deleted, use \array_merge
// and ensure only allowed keys with array_only function

function array_ensure_keys ( &$arr, $keys, $value = '' ) {
	foreach ( $keys as $key ) {
		$arr[ $key ] = array_get( $arr, $key, $value );
	}
}
