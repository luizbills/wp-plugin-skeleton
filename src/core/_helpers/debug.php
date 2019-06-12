<?php

namespace src_namespace__\functions;

function throw_if ( $condition, $message, $code = 0 ) {
	$is_enabled = \apply_filters( 'debug_throw_if_enabled', true, $message, $code );
	if ( $is_enabled && $condition ) {
		throw new \ErrorException( $message, $code = 0 );
	}
	return $condition;
}

function log ( ...$args ) {
	$is_enabled = \apply_filters( prefix( 'debug_log_enabled' ), get_defined( 'WP_DEBUG_LOG', false ) );
	if ( ! $is_enabled ) return;
	\error_log( config_get( 'SLUG' ) . ': ' . format( ...$args ) );
}
