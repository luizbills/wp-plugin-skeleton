<?php

namespace src_namespace__\Utils;

use src_namespace__\Common\Hooker_Trait;
use src_namespace__\Utils\Data_Store;
use src_namespace__\functions as h;

class Asset_Manager {

	use Hooker_Trait;

	protected $store;
	protected $script_data = [];

	public function __construct () {
		$this->store = new Data_Store( [
			'js' => [],
			'css' => [],
		] );

		$this->add_action( 'wp_enqueue_scripts', 'enqueue_assets' );
		$this->add_action( 'admin_enqueue_scripts', 'enqueue_assets' );
		$this->add_action( 'wp_footer', 'print_script_data' );
		$this->add_action( 'admin_footer', 'print_script_data' );
	}

	public function add ( $src, $args = [] ) {
		$type = isset( $args['type'] ) ? $args['type'] : h\get_file_extension( $src );
		$scripts = $this->store->get( $type, [] );

		if ( h\str_starts_with( $src, 'http://' ) || h\str_starts_with( $src, 'https://' ) ) {
			$url = $src;
		} else {
			$url = h\get_asset_url( $src );
		}

		$args = h\array_assign(
			$args,
			\array_merge(
				$this->get_defaults( $type ),
				[ 'src' => $url ]
			)
		);

		if ( empty( $args['handle'] ) ) {
			$args['handle'] = h\str_slug( h\prefix( $src ), '_' );
		}

		$scripts[ $src ] = \apply_filters( h\prefix( 'asset_args' ), $args, $src );
		$this->store->set( $type, $scripts );
	}

	public function enqueue_assets () {
		$types = $this->get_types();

		$function = [
			'css' => 'wp_enqueue_style',
			'js' => 'wp_enqueue_script',
		];

		foreach ( $types as $type ) {
			$scripts = $this->store->get( $type, [] );

			foreach ( $scripts as $key => $args ) {
				extract( $args );

				if ( ! call_user_func( $condition ) ) continue;

				$function[ $type ](
					$handle,
					$src,
					$deps,
					$version,
					$in_footer
				);

				h\log_debug(
					sprintf(
						'Enqueued %s: handle=%s src=%s',
						\strtoupper( $type ),
						$handle,
						$src
					)
				);

				if ( ! empty( $script_data ) ) {
					$this->script_data[ $key ] = $script_data;
				}
			}
		}
	}

	public function print_script_data () {
		if ( ! empty( $this->script_data ) ) {
			$data = $this->script_data;

			$data['ajax_url'] = \admin_url( 'admin-ajax.php' );

			\printf(
				"<script>window.%s = %s</script>",
				h\prefix( 'script_data' ),
				\wp_json_encode( $data )
			);
		}
	}

	protected function get_types () {
		return [ 'css', 'js' ];
	}

	protected function is_valid_extension ( $extension ) {
		$extensions = $this->get_types();
		return \in_array( $extension, $extensions );
	}

	protected function get_defaults ( $type ) {
		$defaults = [
			'src' => '',
			'handle' => '',
			'version' => h\config_get( 'VERSION', false ),
			'deps' => false,
			'condition' => '__return_true',
			'type' => $type,
		];

		if ( 'js' == $type ) {
			$defaults['in_footer'] = true;
			$defaults['script_data'] = null;
		}

		if ( 'css' == $type ) {
			$defaults['media'] = 'all';
		}

		return \apply_filters( h\prefix( 'assets_default_args' ), $defaults, $type );
	}
}
