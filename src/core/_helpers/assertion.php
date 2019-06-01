<?php

namespace src_namespace__\functions;

use src_namespace__\Throwable\AssertionError;

function throw_if ( $condition, $message = '', $meta = null ) {
	if ( $condition ) {
		$message = empty( $message ) ? __( 'Assertion Error', '{{plugin_text_domain}}' ) : $message;
		throw new AssertionError( $message, $meta );
	}
	return $condition;
}
