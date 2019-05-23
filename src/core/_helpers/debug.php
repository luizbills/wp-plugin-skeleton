<?php

namespace src_namespace__\functions;

function log_debug () {
	$is_enabled = \apply_filters( prefix( 'log_debug_enabled' ), get_defined( 'WP_DEBUG_LOG', false ) );

	if ( ! $is_enabled ) return;

	$args = \func_get_args();
	$plugin_slug = config_get( 'SLUG' );
	$message = '';

	foreach( $args as $arg ) {
		if ( null === $arg ) {
			$message .= 'Null';
		}
		elseif ( \is_bool( $arg ) ) {
			$message .= $arg ? 'True' : 'False';
		}
		elseif ( ! \is_string( $arg ) ) {
			$message .= print_r( $arg, true );
		} else {
			$message .= $arg;
		}
		$message .= ' ';
	}

	\error_log( "$plugin_slug: $message" );
}
