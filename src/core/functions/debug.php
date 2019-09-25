<?php

namespace src_namespace__\functions;

function throw_if ( $condition, $message, $code = 0 ) {
	if ( $condition ) {
		if ( \is_callable( $message ) ) {
			$message = $message();
		}
		throw new \ErrorException( $message, $code = 0 );
	}
	return $condition;
}

function log ( ...$args ) {
	$is_enabled = \apply_filters( prefix( 'debug_log_enabled' ), get_defined( 'WP_DEBUG_LOG', false ) );
	if ( ! $is_enabled ) return;
	\error_log( config_get( 'SLUG' ) . ': ' . format( ...$args ) );
}

function dd ( $value ) {
	if ( \function_exists( 'xdebug_enable' ) ) {
		var_dump( $value );
	} else {
		echo '<pre>';
		var_dump( $value );
		echo '</pre>';
	}
	die(1);
}
