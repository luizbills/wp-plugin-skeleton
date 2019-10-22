<?php

namespace src_namespace__\Core;

use src_namespace__\functions as h;
use src_namespace__\Core\Config;
use src_namespace__\Common\Hooker_Trait;

final class Plugin {
	use Hooker_Trait;

	protected static $instance = null;
	protected static $running = false;

	public static function run ( $main_file ) {
		if ( ! self::$running ) {
			new self( $main_file );
			self::$running = true;
		}
	}

	protected function __construct ( $main_file ) {
		Config::setup( $main_file );
		$this->includes();
		$this->pre_boot();
		$this->add_hooks();
	}

	protected function includes () {
		require_once h\config_get( 'ROOT_DIR' ) . '/load.php';
	}

	protected function add_hooks () {
		$this->add_action( 'plugins_loaded', 'boot' );
		$this->add_action( 'init', 'load_plugin_textdomain', 0 );
		$this->add_action( 'init', 'init' );
	}

	public function pre_boot () {
		h\load_classes( 'pre_boot' );
	}

	public function boot () {
		h\load_classes( 'boot' );
	}

	public function init () {
		$should_init = \apply_filters( h\prefix( 'should_init' ), true );
		if ( ! $should_init ) return;

		h\load_classes( 'init' );
		\do_action( h\prefix( 'after_init' ) );
	}

	public function load_plugin_textdomain () {
		\load_plugin_textdomain(
			'{{plugin_text_domain}}',
			false,
			h\config_get( 'ROOT_DIR' ) . '/languages/'
		);
	}
}
