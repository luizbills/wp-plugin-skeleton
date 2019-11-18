<?php

namespace src_namespace__\functions;

function register_ajax_nonce ( $key, $arg, $nonce ) {
	\add_filter(
		prefix( 'ajax_nonces' ),
		return_push_key_value(
			$key,
			[
				'arg' => $arg,
				'value' => $nonce
			]
		)
	);
}

function get_ajax_nonces () {
	return \apply_filters( prefix( 'ajax_nonces' ), [] );
}

function get_ajax_url () {
	return \admin_url( 'admin-ajax.php' );
}
