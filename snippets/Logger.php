<?php

namespace {ns};

use {ns}\functions as h;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;

// requirements: composer require monolog/monolog
// usage: https://github.com/Seldaek/monolog/blob/2.2.0/doc/01-usage.md#adding-extra-data-in-the-records
final class Logger {

	public static function open ( $filename = 'debug' ) {
		try {
			$logger = new MonologLogger( $filename );
			$logger->pushHandler(
				new RotatingFileHandler(
					h\config_get( 'ROOT_DIR' ) . "/logs/$filename.log",
					\WP_DEBUG ? MonologLogger::DEBUG : MonologLogger::WARNING
				)
			);
			return $logger;
		} catch ( \Throwable $e ) {
			h\logf( 'Error when trying to use Logger class: ' . $e->getMessage() );
		}
	}
}

