<?php

namespace src_namespace__\functions;

function load_class ( $class, $context = 'init', $hook_priority = 10 ) {
	$context = \strtoupper( $context );
	$contexts = \apply_filters(
		prefix( 'load_class_contexts' ),
		[ 'INIT', 'BOOT' ]
	);

	throw_if( ! in_array( $context, $contexts ), 'Invalid context argument.' );

	$wp_hook = config_get( "HOOK_$context" );
	\add_filter( $wp_hook, return_push_value( $class ), $hook_priority );
}
