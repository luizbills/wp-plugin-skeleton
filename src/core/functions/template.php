<?php

namespace src_namespace__\functions;

function include_php_template ( $template_path, $data = [] ) {
	// ensure php extension
	$template_path .= ! str_ends_with( $template_path, '.php' ) ? '.php' : '';

	// get all templates locations
	$paths = \apply_filters( prefix( 'php_template_paths' ), [] );

	// add the default templates location
	$paths[] = config_get( 'ROOT_DIR' ) . '/' . config_get( 'TEMPLATES_DIR' );

	// search for an existing template
	foreach ( $paths as $base_path ) {
		if ( \file_exists( "$base_path/$template_path" ) ) {
			// get template variables
			$var = \apply_filters( prefix( 'php_template_data' ), $data, $template_path );

			// render
			v_set_context( get_v_context() );
			include "$base_path/$template_path";
			v_reset_context();

			// render one template only
			break;
		}
	}

	// throws an error if none template are found
	throw_if( true, "Template file do not exists: $template_path" );
}

function get_php_template ( $template_path, $data = [] ) {
	ob_start();
	include_php_template( $template_path, $data );
	return ob_get_clean();
}

function register_custom_v_filters () {
	if ( config_get( 'custom_v_filters_registered', false ) ) {
		return;
	}
	config_set( 'custom_v_filters_registered', true );

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
	
	// overwrites the escape filter
	\v_register_filter(
		'escape',
		function ( $value, $args ) {
			$type = (string) $args->get( 0 );
			$function = $type ? "esc_$type" : false;
			if ( $function ) {
				throw_if( ! \function_exists( $function ), 'unexpected argument 1 for v filter espape: ' . $type );
				return $function( $value );
			}
			return \esc_html( $value );
		},
		$context
	);
}

function get_v_context () {
	return \apply_filters( prefix( 'v_context' ), config_get( 'SLUG' ) );
}
