<?php

namespace src_namespace__\functions;

function include_php_template ( $template_path, $data = [] ) {
	$template_base_path = config_get( 'ROOT_DIR' ) . '/' . config_get( 'TEMPLATES_DIR' );
	$template_path = "$template_base_path/$template_path";
	$data = apply_filters( prefix( 'template_default_data' ), $data );

	extract( $data, \EXTR_OVERWRITE );
	require $template_path;
}

function get_php_template ( $template_path, $data = [] ) {
	ob_start();
	include_php_template( $template_path, $data );
	return ob_get_clean();
}

function register_template_filter( $name, $callback ) {
	$filters = config_get( '_template_filers', false );
	if ( false === $filters ) {
		$filters = new \stdClass();
		$filters->list = [];
		config_set( '_template_filers', $filters );
	}
	$filters->list[ $name ] = $callback;
}

function v ( $value, ...$filters ) {
	$escaped = \preg_grep( '/^esc_/', $filters );
	if ( ! in_array( 'raw' , $filters ) && ! $escaped ) {
		$filters[] = 'esc_html';
	}

	$callbacks = config_get( '_template_filers', false );

	if ( false !== $callbacks ) {
		$callbacks = $callbacks->list;
		foreach ( $filters as $name ) {
			if ( 'raw' == $name ) continue;
			if ( isset( $callbacks[ $name ] ) ) {
				$value = \call_user_func( $callbacks[ $name ], $value );
			} else {
				throw_if( true, "unexpected '$name' template filter." );
			}
		}
	}

	return $value;
}

function register_default_template_filters () {
	if ( config_get( 'template_filters_registered', false ) ) return;  

	register_template_filter( 'esc_html', 'esc_html' );

	register_template_filter( 'esc_attr', 'esc_attr' );

	register_template_filter( 'esc_js', 'esc_js' );

	register_template_filter( 'esc_url', 'esc_url' );

	register_template_filter( 'capitalize', 'ucfirst' );

	register_template_filter( 'int', 'intval' );

	register_template_filter( 'float', 'floatval' );

	register_template_filter( 'abs', 'abs' );

	register_template_filter( 'round', 'round' );

	register_template_filter( 'lowercase', function ( $value ) {
		return h\str_lower( $value );
	} );

	register_template_filter( 'uppercase', function ( $value ) {
		return h\str_upper( $value );
	} );

	config_set( 'template_filters_registered', true );
}
