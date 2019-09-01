<?php

namespace src_namespace__\functions;

function value ( $value, $default = '' ) {
	$result = is_callable( $value ) ? call_user_func( $value ) : $value;
	return empty( $result ) ? $default : $result;
}

// returns a value of request body
// e.g.: request_value( 'foo' ) returns $_GET['foo'] in GET requests
function request_value ( $key, $default = '' ) {
	$method = $_SERVER['REQUEST_METHOD'];
	return array_get( $GLOBALS["_$method"], $key, $default );
}

function maybe_define ( $key, $value = true, $force_upper_case = true ) {
	$key = $force_upper_case ? \strtoupper( $key ) : $key;
	if ( ! defined( $key ) ) {
		define( $key, $value );
	}
}

function get_defined ( $key, $default = false ) {
	if ( defined( $key ) ) {
		return constant( $key );
	}
	return $default;
}

function format ( ...$args ) {
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

	return $message;
}

function add_plugin_action_link ( $label, $url, $css_class = '', $priority = 10 ) {
	$label = esc_html( $label );
	$css_class = esc_attr( $css_class );
	$url = esc_attr( $url );
	$link = "<a class="$css_class" href="$url">$label</url>";
	\add_filter(
		'plugin_action_links_' . config_get( 'MAIN_FILE' ),
		return_push_value( $link ),
		$priority
	);
}
