<?php

namespace src_namespace__\Core;

use src_namespace__\functions as h;
use src_namespace__\Core\Config;
use src_namespace__\Common\Hooker_Trait;

final class Plugin {
	use Hooker_Trait;

	protected static $instance = null;

	public static function get_instance ( $main_file = null ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $main_file );
		}
		return self::$instance;
	}

	public static function run ( $main_file ) {
		return self::get_instance( $main_file );
	}

	protected function __construct ( $main_file ) {
		Config::setup( $main_file );
		$this->includes();
		$this->add_hooks();
		$this->register_template_filters();
	}

	protected function includes () {
		require_once h\config_get( 'ROOT_DIR' ) . '/load.php';
	}

	protected function add_hooks () {
		// try to boot
		$this->pre_boot();
		$should_boot = \apply_filters( h\prefix( 'should_boot' ), true );
		if ( ! $should_boot ) return;
		$this->add_action( 'plugins_loaded', 'boot' );

		// try to init
		\do_action( h\prefix( 'pre_init' ) );
		$should_init = \apply_filters( h\prefix( 'should_init' ), true );
		if ( ! $should_init ) return;
		$this->add_action( 'init', 'init' );
	}

	public function pre_boot () {
		$this->load_classes( 'pre_boot' );
	}

	public function boot () {
		$this->load_classes( 'boot' );
	}

	public function init () {
		$this->add_action( 'init', 'load_plugin_textdomain', 0 );
		$this->load_classes( 'init' );
		\do_action( h\prefix( 'after_init' ) );
	}

	public function load_classes ( $method ) {
		$wp_hook = h\get_load_class_hook( $method );
		$classes = \apply_filters( $wp_hook, [] );

		foreach ( $classes as $class_name ) {
			$instance = h\config_get_instance( $class_name, false );

			if ( false === $instance ) {
				$instance = h\config_set_instance( $class_name );
			}

			h\throw_if(
				! \method_exists( $instance, $method ),
				"The $class_name class don't has ${method}() method."
			);

			$instance->$method();
		}
	}

	public function load_plugin_textdomain () {
		\load_plugin_textdomain(
			'{{plugin_text_domain}}',
			false,
			h\config_get( 'ROOT_DIR' ) . '/languages/'
		);
	}

	protected function register_template_filters () {
		// register default template filters
		h\register_template_filter( 'esc_html', 'esc_html' );
		h\register_template_filter( 'esc_attr', 'esc_attr' );
		h\register_template_filter( 'esc_js', 'esc_js' );
		h\register_template_filter( 'esc_url', 'esc_url' );
		h\register_template_filter( 'capitalize', 'ucfirst' );
		h\register_template_filter( 'int', 'intval' );
		h\register_template_filter( 'float', 'floatval' );
		h\register_template_filter( 'abs', 'abs' );
		h\register_template_filter( 'round', 'round' );
		h\register_template_filter( 'lowercase', function ( $value ) {
			return h\str_lower( $value );
		} );
		h\register_template_filter( 'uppercase', function ( $value ) {
			return h\str_upper( $value );
		} );
	}
}
