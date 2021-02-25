<?php

namespace {ns}\Common;

use {ns}\Logger;

trait Logger_Trait {

	// overwrites this method if necessary
	public function get_logger_channel () {
		return 'debug';
	}

	public function log_debug ( $message, $extra = [] ) {
		return $this->get_logger()->debug( $message, $extra );
	}

	public function log_info ( $message, $extra = [] ) {
		return $this->get_logger()->info( $message, $extra );
	}

	public function log_notice ( $message, $extra = [] ) {
		return $this->get_logger()->notice( $message, $extra );
	}

	public function log_error ( $message, $extra = [] ) {
		return $this->get_logger()->error( $message, $extra );
	}

	public function log_alert ( $message, $extra = [] ) {
		return $this->get_logger()->alert( $message, $extra );
	}
  
  protected function get_logger () {
		return Logger::open( $this->get_logger_channel() );
	}
}
