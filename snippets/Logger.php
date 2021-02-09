<?php

namespace {ns};

use {ns}\functions as h;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;

// Requires: composer require monolog/monolog
final class Logger {

	public static function open ( $filename = 'debug' ) {
		try {
			$logger = new MonologLogger( $filename );
			$logger->pushHandler(
				new RotatingFileHandler(
					h\config_get( 'ROOT_DIR' ) . "/logs/$filename.log",
					MonologLogger::DEBUG
				)
			);
			return $logger;
		} catch ( \Throwable $e ) {
			h\logf( 'Error when trying to use Logger class: ' . $e->getMessage() );
		}
	}
}
