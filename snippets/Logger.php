<?php

namespace {ns};

use {ns}\functions as h;
use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

// requirements: composer require monolog/monolog:2.2
// usage: https://github.com/Seldaek/monolog/blob/2.2.0/doc/01-usage.md
final class Logger {
	protected static $loggers = [];

	public static function open ( $name = 'debug', $level = null ) {
		if ( ! isset( self::$loggers[ $name ] ) ) {
			try {
				$format = "[%datetime%] %level_name% %message% %context% %extra%\n";
				$formatter = new LineFormatter( $format );
				$logger = new MonologLogger( $name );
				$maxFiles = \apply_filters( h\prefix( 'rotating_logs_max_files' ), 7, $name );
				$debug_log = \apply_filters( h\prefix( 'debug_log' ), h\get_defined( 'WP_DEBUG' ), $name );
				$filename = self::get_dir() . "/$name.log";
				$level = $level ? $level : ( \WP_DEBUG ? MonologLogger::DEBUG : MonologLogger::ERROR );
				$handler = new RotatingFileHandler( $filename, $maxFiles, $level );

				$handler->setFormatter( $formatter );
				$logger->pushHandler( $handler );

				self::$loggers[ $name ] = \apply_filters( h\prefix( 'get_logger' ), $logger, $name, $level );
			} catch ( \Throwable $e ) {
				h\logf( 'Can\'t initialize Monolog\\Logger class: ' . $e->getMessage() );
			}
		}
		return self::$loggers[ $name ];
	}

	public static function get_dir () {
		return h\config_get( 'ROOT_DIR' ) . '/logs';
	}
}
