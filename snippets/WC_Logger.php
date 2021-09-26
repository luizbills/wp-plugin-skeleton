<?php

namespace {ns};

use {ns}\functions as h;

final class WC_Logger {
	protected static $log;

	public static function get_instance () {
		if ( ! self::$log ) {
			self::$log = \wc_get_logger();
		}
		return self::$log;
	}

	public static function log ( $logger_source, $message, $level = 'info' ) {
		self::get_instance()->log( $level, $message, [ 'source' => $logger_source ] );
	}
}
