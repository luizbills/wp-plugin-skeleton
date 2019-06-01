<?php

namespace src_namespace__\Throwable;

class AssertionError extends \Exception {
	protected $meta;

	public function __construct ( $message, $meta = null ) {
		parent::__construct( $message );
		$this->setMeta( $meta );
	}

	public function setMeta( $meta ) {
		$this->meta = $meta;
	}

	public function getMeta( $toString = false ) {
		return $toString ? \json_encode( $this->meta ) : $this->meta;
	}

	public function __toString () {
		$meta = $this->getMeta( true );
		return $this->getMessage() . ( $meta ?? ' — ' . $meta );
	}
}
