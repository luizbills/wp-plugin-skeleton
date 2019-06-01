<?php

namespace src_namespace__\Utils;

class Data_Store {
	protected $data = [];

	public function __construct ( $values = [] ) {
		foreach ( $values as $key => $value) {
			$this->set( $key, $value );
		}
	}

	public function set ( $key, $value ) {
		h\throw_if(
			is_null( $value ),
			__CLASS__ . sprintf( ": can't store 'null'." )
		);
		$this->data[ $key ] = $value;
		return $this->data[ $key ];
	}

	public function get ( $key, $default = null ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : $default;
	}

	public function has ( $key ) {
		return isset( $this->data[ $key ] );
	}

	public function clear ( $key ) {
		if ( isset( $this->data[ $key ] ) ) {
			unset( $this->data[ $key ] );
		}
	}
}
