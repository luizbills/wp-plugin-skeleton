<?php

namespace src_namespace__\functions;

function throw_if ( $condition, $message, $error_code = 500 ) {
	if ( $condition ) {
		if ( \is_callable( $message ) ) {
			$message = $message();
		}
		$slug = config_get( 'SLUG' );
		throw new \RuntimeException( "[$slug-error] $message", (int) $error_code );
	}
	return $condition;
}

function handle_exception ( \Throwable $exception, $callback = null ) {
	$message = $exception->getMessage();
	$slug = config_get( 'SLUG' );
	$error_prefix = "[$slug-error]";

	if ( str_starts_with( $message, $error_prefix ) ) {
		$error_message = trim( str_after( $message, $error_prefix ) );
		return [
			'error_message' => $error_message,
			'error_code' => $exception->getCode(),
		];
	}

	return false;
}

function logf ( ...$args ) {
	$is_enabled = \apply_filters( prefix( 'debug_log_enabled' ), get_defined( 'WP_DEBUG_LOG', false ) );
	if ( ! $is_enabled ) return;
	\error_log( config_get( 'SLUG' ) . ': ' . format( ...$args ) );
}

function log ( ...$args ) {
	\error_log( '[WARNING] ' . __FUNCTION__ . ' is deprecated! Use "h\logf()" (in core/functions/debug.php) instead.' );
	return logf( ...$args );
}

function dd ( $value ) {
	\function_exists( 'dump' ) ? \dump( $value ) : \var_dump( $value);
	die(1);
}
