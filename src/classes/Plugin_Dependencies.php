<?php

namespace src_namespace__;

use src_namespace__\functions as h;
use src_namespace__\Common\Hooker_Trait;

final class Plugin_Dependencies {
	use Hooker_Trait;

	protected $errors = null;

	public function __construct () {
		$this->add_action( h\prefix( 'pre_boot' ), 'check_dependencies' );
	}

	public function check_dependencies () {
		$deps = $this->get_dependencies();

		$this->errors = [];

		foreach ( $deps as $args ) {
			if ( ! \call_user_func( $args['check'] ) ) {
				$this->errors[] = $args['error'];
			}
		}

		if ( ! empty( $this->errors ) ) {
			$this->disable_plugin();
			$this->add_action( 'admin_notices', 'print_error_notice' );
		}
	}

	public function print_error_notice () {
		$name = h\config_get( 'NAME' );
		$message = '<strong>' . __( "Missing requirements for $name. Please follow this instructions:" ) . '</strong>';

		foreach ( $this->errors as $error ) {
			$message .= sprintf( '<br>%s%s', \str_repeat( '&nbsp;', 4 ), $error );
		}

		h\include_php_template( 'admin-notice.php', [
			'class' => 'error',
			'message' => $message,
		] );
	}

	protected function get_dependencies () {
		$deps = [];
		$required_php_version = $this->get_required_php_version();

		if ( $required_php_version ) {
			// check PHP version
			$deps[] = [
				'check' => function () use ( $required_php_version ) {
					return $this->compare_version( \PHP_VERSION, $required_php_version );
				},
				'error' => __(
					"Upgrade your PHP version to $required_php_version or later.",
					'{{plugin_text_domain}}'
				),
			];
		}

		return $deps;
	}

	protected function disable_plugin () {
		add_filter( h\prefix( 'should_boot' ), '__return_false', 999 );
	}

	protected function get_required_php_version () {
		// get required PHP version from composer.json
		$composer_file = h\config_get( 'ROOT_DIR' ) . '/composer.json';

		if ( \file_exists( $composer_file ) ) {
			$content = \file_get_contents( $composer_file );
			$json = \json_decode( $content );

			if ( isset( $json->require->php ) ) {
				$version = preg_replace( '/[^0-9.]/', '', $json->require->php );
				return $version;
			}
		}

		return false;
	}

	protected function compare_version ( $version1, $version2, $operator = '>=' ) {
		return version_compare( strtolower( $version1 ), strtolower( $version2 ), $operator );
	}
}
