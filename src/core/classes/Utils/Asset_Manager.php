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

		$args = \array_merge(
			$this->get_defaults( $type ),
			[
				'path' => $src,
				'src' => $url,
				'handle' => h\str_slug( h\prefix( $src ), '_' ),
			],
			$args
		);

		$scripts[ $src ] = \apply_filters( h\prefix( 'asset_args' ), $args, $src );
		$this->store->set( $type, $scripts );
	}

	public function enqueue_assets () {
		$types = $this->get_types();
		$is_admin = \is_admin();

		$function = [
			'js' => 'wp_enqueue_script',
			'css' => 'wp_enqueue_style',
		];

		foreach ( $types as $type ) {
			$scripts = $this->store->get( $type, [] );

			foreach ( $scripts as $key => $args ) {
				$allowed_args = \array_keys( $this->get_defaults( $type ) );
				\extract( h\array_only( $args, $allowed_args ) );

				if ( $is_admin !== $in_admin ) continue;
				if ( ! call_user_func( $condition ) ) continue;

				$function[ $type ](
					$handle,
					$src,
					$deps,
					$version,
					'js' == $type ? $in_footer : $media
				);

				h\log(
					sprintf(
						'Enqueued %s: path=%s',
						\strtoupper( $type ),
						$path
					)
				);

				if ( 'js' == $type && ! is_null( $script_data ) ) {
					$key = ! is_null( $script_data_key ) ? $script_data_key : $path;
					$this->script_data[ $key ] = $script_data;
				}
			}
		}
	}

	public function print_script_data () {
		$data = [
			'$ajax_url' => \admin_url( 'admin-ajax.php' ),
			'$prefix' => h\config_get( 'PREFIX' ),
			'$slug' => h\config_get( 'SLUG' ),
			'$debug' => h\get_defined( 'WP_DEBUG' ),
		];

		\printf(
			"<script>window['%s'] = JSON.parse('%s');</script>",
			\esc_js( h\prefix( 'script_data' ) ),
			\wp_json_encode( array_merge( [], $this->script_data, $data ) )
		);
	}

	protected function get_types () {
		return [
			'js',
			'css',
		];
	}

	protected function is_valid_extension ( $extension ) {
		$extensions = $this->get_types();
		return \in_array( $extension, $extensions );
	}

	protected function get_defaults ( $type ) {
		$defaults = [
			'src' => null,
			'path' => null,
			'handle' => null,
			'version' => h\config_get( 'VERSION' ),
			'deps' => false,
			'condition' => '__return_true',
			'type' => $type,
			'in_admin' => false,
		];

		if ( 'js' == $type ) {
			$defaults['in_footer'] = true;
			$defaults['script_data'] = null;
			$defaults['script_data_key'] = null;
		}
		elseif ( 'css' == $type ) {
			$defaults['media'] = 'all';
		}

		return \apply_filters( h\prefix( 'assets_default_args' ), $defaults, $type );
	}
}
