<?php

namespace src_namespace__\Core;

use src_namespace__\Utils\Immutable_Data_Store;
use src_namespace__\functions as h;

class Config {
	protected static $options = null;

	public static function get_options () {
		if ( null === self::$options ) {
			self::$options = new Immutable_Data_Store();
		}
		return self::$options;
	}

	public static function setup ( $main_file ) {
		if ( ! null === self::$options ) return;

		$root = \dirname( $main_file );
		$plugin_config = include_once $root . '/config.php';
		$plugin_slug = h\str_slug( $plugin_config['NAME'] );
		$plugin_prefix = h\str_slug( $plugin_config['NAME'], '_' ) . '_';
		$options = self::get_options();

		$options->set( 'SLUG', $plugin_slug );
		$options->set( 'PREFIX', $plugin_prefix );
		$options->set( 'MAIN_FILE', $main_file );
		$options->set( 'ROOT_DIR', $root );
		$options->set( 'HOOK_BOOT', $plugin_prefix . 'boot_plugin' );
		$options->set( 'HOOK_INIT', $plugin_prefix . 'init_plugin' );

		foreach ( $plugin_config as $key => $value ) {
			$options->set( $key, $value );
		}
	}

	public static function set ( $key, $value ) {
		h\throw_if( null === $value, "Can't store 'null'." );
		return self::get_options()->set( $key, $value );
	}

	public static function get ( $key, $default = null ) {
		if ( self::get_options()->has( $key ) ) {
			return self::get_options()->get( $key );
		}
		h\throw_if( null === $default, "Not found '$key' key." );
		return $default;
	}
}
