<?php

namespace src_namespace__\functions;

/*
```
// Usage
$values = sanitize( $_POST, [
	'name' => [ 'alpha_spaces' ],
	'message' => [ 'escape' ]
] );
```
*/
function sanitize ( $fields, $filters, $defaults = [] ) {
	$result = [];
	$fields = array_merge( $defaults, $fields );

	foreach ( $filters as $key => $filter ) {
		$value = array_get( $fields, $key, '' );
		$filter = wrap( $filter );

		if ( 'array' == gettype( $value ) ) {
			$result[ $key ] = [];

			foreach ( $value as $item ) {
				$result[ $key ][] = sanitize( $item, $filter, $defaults[ $key ] );
			}
		}
		elseif ( 'string' == gettype( $value ) ) {
			$result[ $key ] = sanitize_value( $value, $filter );
		}
		else {
			$result[ $key ] = $value;
		}
	}

	return $result;
}

function sanitize_value ( $value, $filter = [] ) {
	return v( $value, ...$filter );
}
