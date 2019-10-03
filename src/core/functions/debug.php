<?php

namespace src_namespace__\functions;

function throw_if ( $condition, $message, $error_id = '' ) {
	if ( $condition ) {
		if ( \is_callable( $message ) ) {
			$message = $message();
		}
		$slug = config_get( 'SLUG' );
		$error_slug = $error_id ? "[$slug-error-$error_id]" : "[$slug-error]";
		throw new \ErrorException( "$error_slug $message" );
	}
	return $condition;
}

function handle_exception ( \Throwable $exception, Callable $callback ) {
	$message = $exception->getMessage();
	$slug = config_get( 'SLUG' );
	if ( str_starts_with( $message, "[$slug-" ) ) {
		$parts = \explode( ']', $message );
		if ( count( $parts ) < 2 ) {
			return false;
		}
		// get error id
		$error_id = \array_shift( $parts );
		$error_id = preg_replace( "/^\[$slug-/", '', $error_id );
		// get message
		$error_message = \implode( ']', $parts );
		// handle
		return $callback( trim( $error_message ), trim( $error_id ) );
	}
	return false;
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
