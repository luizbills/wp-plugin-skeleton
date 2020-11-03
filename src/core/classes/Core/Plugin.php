<?php

namespace src_namespace__\Core;

use src_namespace__\functions as h;
use src_namespace__\Core\Config;
use src_namespace__\Core\Dependencies;
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
		$root = \dirname( $main_file );
		require_once $root . '/core/load_helpers.php';
		
		Config::setup( $main_file );
		
		$this->whoops();
		$this->pre_boot();
		$this->add_hooks();

		require_once $root . '/load.php';
		require_once $root . '/core/load_classes.php';
	}

	public function pre_boot () {
		h\load_classes( 'pre_boot' );
	}

	protected function add_hooks () {
		$this->add_action( 'plugins_loaded', 'boot' );
		$this->add_action( 'init', 'load_plugin_textdomain', 0 );
		$this->add_action( 'init', 'init' );
	}

	public function boot () {
		h\load_classes( 'boot' );
	}

	public function init () {
		if ( Dependencies::validate() ) {
			h\load_classes( 'init' );
			\do_action( h\prefix( 'after_init' ) );
		}
	}

	public function load_plugin_textdomain () {
		\load_plugin_textdomain(
			'{{plugin_text_domain}}',
			false,
			h\config_get( 'ROOT_DIR' ) . '/languages/'
		);
	}

	protected function whoops () {
		$use_whoops = h\all_equal(
			[
				\class_exists( 'Whoops\\Run' ),
				! \wp_doing_ajax(),
				h\get_defined( 'WP_DEBUG' ),
				h\get_defined( 'WP_DEBUG_DISPLAY' )
			],
			true,
			true
		);

		if ( $use_whoops ) {
			@error_reporting( E_ERROR | E_WARNING | E_PARSE );
			$whoops = h\config_set( 'whoops_instance', new \Whoops\Run() );
			$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
			$whoops->register();
		}
	}
}
