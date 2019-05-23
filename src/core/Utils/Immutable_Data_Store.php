<?php

namespace src_namespace__\Utils;

use Webmozart\Assert\Assert;
use src_namespace__\functions as h;

class Immutable_Data_Store extends Data_Store {

	public function set ( $key, $value ) {
		Assert::false( $this->has( $key ), \Exception::class, __CLASS__ . ": key \"$key\" already assigned." );
		return parent::set( $key, $value );
	}

	public function clear ( $key ) {
		Assert::null( '', __CLASS__ . ": It's not possible clear an immutable data store." );
	}
}

