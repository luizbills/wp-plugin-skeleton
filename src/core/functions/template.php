<?php

namespace src_namespace__\functions;

function include_php_template ( $template_path, $data = [] ) {
	// ensure php extension
	$template_path .= ! str_ends_with( $path, '.php' ) ? '.php' : '';

	// complete the path
	$base_path = config_get( 'ROOT_DIR' ) . '/' . config_get( 'TEMPLATES_DIR' );
	$path = "$base_path/$template_path";

	// get template variables
	$var = \apply_filters( prefix( 'template_data' ), $data, $template_path );

	// render
	v_set_context( get_v_context() );
	require $path;
	v_reset_context();
}

function get_php_template ( $template_path, $data = [] ) {
	ob_start();
	include_php_template( $template_path, $data );
	return ob_get_clean();
}

function register_custom_v_filters () {
	if ( config_get( 'custom_v_filters_registered', false ) ) return;

	$context = get_v_context();

	\v_register_filter(
		'with_prefix',
		function ( $value, $args ) {
			return prefix( $value );
		},
		$context
	);

	\v_register_filter(
		'with_slug',
		function ( $value, $args ) {
			return config_get( 'SLUG' ) . "-$value";
		},
		$context
	);

	\do_action( prefix( 'register_v_filters' ) );

	config_set( 'custom_v_filters_registered', true );
}

function get_v_context () {
	return \apply_filters( prefix( 'v_context' ), config_get( 'SLUG' ) );
}
