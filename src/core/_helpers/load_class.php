<?php

namespace src_namespace__\functions;

function load_class ( $class, $context = 'init', $hook_priority = 10 ) {
	$context = \strtoupper( $context );
	$contexts = \apply_filters(
		prefix( 'load_class_contexts' ),
		[ 'INIT', 'BOOT' ]
	);

	throw_if(
		! isset( $contexts[ $context ] ),
		'Invalid context argument.'
	);

	\add_filter( config_get( "HOOK_$context" ), return_push_value( $class ), $hook_priority );
}
