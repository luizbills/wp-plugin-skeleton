<?php

namespace {ns};

use {ns}\functions as h;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;

// requirements: composer require monolog/monolog
// usage: https://github.com/Seldaek/monolog/blob/2.2.0/doc/01-usage.md#adding-extra-data-in-the-records
final class Logger {
	protected static $logger = null;

	public static function open ( $name = 'debug' ) {
		if ( null === self::$logger ) {
			try {
				$format = "[%datetime%] %level_name% %message% %context% %extra%\n";
				$formatter = new LineFormatter( $format );
				$logger = new MonologLogger( $name );
				$handler = new RotatingFileHandler(
					h\config_get( 'ROOT_DIR' ) . "/logs/$name.log",
					\WP_DEBUG ? MonologLogger::DEBUG : MonologLogger::WARNING
				);

				$handler->setFormatter( $formatter );
				$logger->pushHandler( $handler );

				self::$logger = \apply_filters( h\prefix( 'get_logger' ), $logger, $name );
			} catch ( \Throwable $e ) {
				h\logf( 'Can\'t initialize Monolog\\Logger class: ' . $e->getMessage() );
			}
		}

		return self::$logger;
	}
}
