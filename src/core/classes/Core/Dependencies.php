<?php

namespace src_namespace__\Core;

use src_namespace__\functions as h;

class Dependencies {
	public static function validate () {
		$deps = \apply_filters( h\prefix( 'plugin_dependencies' ), [] );
		$errors = [];

		foreach ( $deps as $id => $check_dependency ) {
			$error = \call_user_func( $check_dependency );

			if ( $error ) {
				$errors[ $id ] = $error;
			}
		}

		if ( count( $errors ) > 0 ) {
			\do_action( h\prefix( 'handle_missing_dependencies' ), $errors );
		}
	}
}
