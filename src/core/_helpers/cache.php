<?php
/**
 * Based on https://github.com/stevegrunwell/wp-cache-remember/tree/v1.1.1
 */

namespace src_namespace__\functions;

function remember_cache ( $key, $callback, $expire = 0 ) {
	if ( \apply_filters( prefix( 'remember_cache_disabled' ), false, $key ) ) {
		log_debug( "function remember_cache disabled for $key" );
		return $callback();
	}

	$key_prefix = \apply_filters( prefix( 'remember_cache_key_prefix' ), prefix(), $key );
	$key_suffix = \apply_filters( prefix( 'remember_cache_key_suffix' ), '_' . config_get( 'VERSION', '' ), $key );

	$transient_key = $key_prefix . $key . $key_suffix;
	$cached = \get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}

	$value = $callback();

	if ( false !== $value && null !== $value && ! \is_wp_error( $value ) ) {
		$expire = \apply_filters( prefix( 'remember_cache_expiration' ), $expire, $key );
		\set_transient( $transient_key, $value, $expire );
		log_debug( "function remember_cache store key $key with value:", $value );
	}

	return $value;
}

function forget_cache ( $key, $default = null ) {
	$key_prefix = \apply_filters( prefix( 'remember_cache_key_prefix' ), prefix(), $key );
	$key_suffix = \apply_filters( prefix( 'remember_cache_key_suffix' ), '_' . config_get( 'VERSION', '' ), $key );

	$transient_key = $key_prefix . $key . $key_suffix;
	$cached = \get_transient( $key );

	if ( false !== $cached ) {
		\delete_transient( $key );
		log_debug( "function remember_cache deleted key $key with value:", $cached );
		return $cached;
	}
	return $default;
}
