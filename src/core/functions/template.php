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