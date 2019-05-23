<?php

namespace src_namespace__\functions;

function return_value ( $value ) {
	return function () use ( $value ) {
		return $value;
	};
}

function return_value_if ( $condition, $value ) {
	return function ( $v ) use ( $condition, $value ) {
		if ( $condition ) return $value;
		return $v;
	};
}

function return_push_value ( $value ) {
	return function ( $arr ) use ( $value ) {
		$arr[] = $value;
		return $arr;
	};
}

function return_push_value_if ( $condition, $value ) {
	return function ( $arr ) use ( $condition, $value ) {
		if ( $condition ) return $arr[] = $value;
		return $arr;
	};
}

function return_print_value ( $value ) {
	return function () use ( $value ) {
		echo $value;
	};
}
