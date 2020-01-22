<?php
/**
 * Based on https://github.com/stevegrunwell/wp-cache-remember/tree/v1.1.1
 */

namespace src_namespace__\functions;

function fetch_cache ( $key, $default = null ) {
	$transient_key = build_cache_key( $key );
	$cached = \get_transient( $transient_key );

	if ( false !== $cached ) {
		return $cached;
	}
	return $default;
}

function remember_cache ( $key, $value, $expire_in_minutes = 0 ) {
	$cache_disabled = \apply_filters(
		prefix( 'remember_cache_disabled' ),
		config_get( 'DISABLE_CACHE', false ),
		$key
	);
	$result = is_callable( $value ) ? call_user_func( $value ) : $value;

	if ( $cache_disabled ) {
		log( "function remember_cache disabled for $key" );
		return $result;
	}

	$cached = fetch_cache( $key, false );

	if ( false !== $cached ) {
		return $cached;
	}

	if ( false !== $result && null !== $result && ! \is_wp_error( $result ) ) {
		$transient_key = build_cache_key( $key );
		$expire = \apply_filters(
			prefix( 'remember_cache_expiration' ),
			$expire_in_minutes * \MINUTE_IN_SECONDS,
			$key
		);

		\set_transient( $transient_key, $result, $expire );
		log( "function remember_cache store key $key with value:", $result );
	}

	return $result;
}

function forget_cache ( $key, $default = null ) {
	$cached = fetch_cache( $key, false );

	if ( false !== $cached ) {
		$transient_key = build_cache_key( $key );
		\delete_transient( $transient_key );
		log( "function remember_cache deleted key $key with value:", $cached );
		return $cached;
	}
	return $default;
}

function clear_plugin_cache () {
	if ( \wp_using_ext_object_cache() ) {
		log( 'External Object Cache detected. Cache NOT cleared.' );
		return;
	}

	global $wpdb;
	$prefix = get_cache_key_prefix();

	$wpdb->query(
		$wpdb->prepare(
			"DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b
			WHERE a.option_name LIKE %s
			AND a.option_name NOT LIKE %s
			AND b.option_name LIKE %s
			AND b.option_value > %d",
			$wpdb->esc_like( "_transient_$prefix" ) . '%',
			$wpdb->esc_like( "_transient_timeout_$prefix" ) . '%',
			$wpdb->esc_like( "_transient_timeout_$prefix" ) . '%',
			time()
		)
	);

	if ( ! \is_multisite() ) {
		// non-Multisite stores site transients in the options table.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name LIKE %s
				AND b.option_value > %d",
				$wpdb->esc_like( "_site_transient_$prefix" ) . '%',
				$wpdb->esc_like( "_site_transient_timeout_$prefix" ) . '%',
				$wpdb->esc_like( "_site_transient_timeout_$prefix" ) . '%',
				time()
			)
		);
	} elseif ( \is_multisite() && \is_main_site() && \is_main_network() ) {
		// Multisite stores site transients in the sitemeta table.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM {$wpdb->sitemeta} a, {$wpdb->sitemeta} b
				WHERE a.meta_key LIKE %s
				AND a.meta_key NOT LIKE %s
				AND b.option_name LIKE %s
				AND b.meta_value > %d",
				$wpdb->esc_like( "_site_transient_$prefix" ) . '%',
				$wpdb->esc_like( "_site_transient_timeout_$prefix" ) . '%',
				$wpdb->esc_like( "_site_transient_timeout_$prefix" ) . '%',
				time()
			)
		);
	}

	log( 'Cache cleared!' );
}

function build_cache_key ( $key ) {
	return get_cache_key_prefix() . $key . get_cache_key_suffix();
}

function get_cache_key_prefix () {
	return \apply_filters( prefix( 'cache_key_prefix' ), prefix() );
}

function get_cache_key_suffix () {
	return \apply_filters( prefix( 'cache_key_suffix' ),'_' . config_get( 'VERSION', '' ) );
}
