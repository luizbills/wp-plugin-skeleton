<?php

namespace src_namespace__\functions;

function load_class ( $class, $method = 'init', $priority = 10 ) {
	$methods = get_load_class_methods();

	throw_if( ! class_exists( $class ), "$class class not exists" );
	throw_if( ! in_array( $method, $methods ), "Invalid load_class method argument: $method" );

	$wp_hook = get_load_class_hook( $method );
	\add_filter( $wp_hook, return_push_value( $class ), $priority );
}

function get_load_class_methods () {
	$contexts = \apply_filters(
		prefix( 'load_class_methods' ),
		[ 'pre_boot', 'boot', 'init' ]
	);

	return \array_map( 'strtolower', $contexts );
}

function get_load_class_hook ( $method ) {
	return prefix( "load_classes_on_$method" );
}
