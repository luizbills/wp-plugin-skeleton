<?php

namespace {ns}\Common;

use {ns}\Logger;

trait Logger_Trait {

	// overwrites this method if necessary
	public function get_logger_channel () {
		return 'debug';
	}

	public function log_debug ( $message, $extra = [] ) {
		return $this->get_logger()->debug( $message, (array) $extra );
	}

	public function log_info ( $message, $extra = [] ) {
		return $this->get_logger()->info( $message, (array) $extra );
	}

	public function log_notice ( $message, $extra = [] ) {
		return $this->get_logger()->notice( $message, (array) $extra );
	}

	public function log_error ( $message, $extra = [] ) {
		return $this->get_logger()->error( $message, (array) $extra );
	}

	public function log_alert ( $message, $extra = [] ) {
		return $this->get_logger()->alert( $message, (array) $extra );
	}

  protected function get_logger () {
		return Logger::open( $this->get_logger_channel() );
	}
}
