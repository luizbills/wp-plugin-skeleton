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

function handle_exception ( \Throwable $exception, $callback = null ) {
	$message = $exception->getMessage();
	$slug = config_get( 'SLUG' );
	$error_prefix = "[$slug-error";

	if ( str_starts_with( $message, $error_prefix ) ) {
		$parts = \explode( ']', $message );

		// get error id
		$pattern = '/^' . \preg_quote( $error_prefix, '/' ) . '-?/';
		$error_id = \preg_replace( $pattern, '', \array_shift( $parts ) );

		// get message
		$error_message = \implode( ']', $parts );

		// handle
		$http_code = is_callable( $callback)
			? $callback( trim( $error_message ), trim( $error_id ) )
			: false;

		return [
			'error_message' => $error_message,
			'error_id'      => $error_id,
			'code'          => $http_code ? intval( $http_code ) : 400,
		];
	}

	return [
		'error_message' => $exception->getMessage(),
		'error_id'      => 'unexpected-error',
		'code'          => 500,
	];
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
	if ( \function_exists( 'xdebug_enable' ) ) {
		var_dump( $value );
	} else {
		echo '<pre>';
		var_dump( $value );
		echo '</pre>';
	}
	die(1);
}
