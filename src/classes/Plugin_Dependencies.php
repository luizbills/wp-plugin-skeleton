<?php

namespace src_namespace__;

use src_namespace__\functions as h;
use src_namespace__\Common\Hooker_Trait;

final class Plugin_Dependencies {
	use Hooker_Trait;

	public function boot () {
		$this->add_filter( h\prefix( 'plugin_dependencies' ), 'set_dependencies' );
		$this->add_action( h\prefix( 'handle_missing_dependencies' ), 'print_errors' );
	}

	public function set_dependencies ( $deps ) {
		$deps['php'] = function () {
			$php_version = $this->get_required_php_version();

			if ( $php_version && ! $this->compare_version( $php_version, PHP_VERSION ) ) {
				return esc_html__(
					"Upgrade your PHP version to $required_php_version or later.",
					'{{plugin_text_domain}}'
				);
			}
		};

		return $deps;
	}

	public function print_errors ( $errors ) {
		$name = h\config_get( 'NAME' );
		$message = '<strong>' . __( "Missing requirements for $name. Please follow this instructions:" ) . '</strong>';
		foreach ( $errors as $error ) {
			$message .= sprintf( '<br>%s%s', \str_repeat( '&nbsp;', 4 ), $error );
		}
		h\include_php_template( 'admin-notice.php', [
			'class' => 'error',
			'message' => $message,
		] );
	}

	protected function get_required_php_version () {
		// get required PHP version from composer.json
		$composer_file = h\config_get( 'ROOT_DIR' ) . '/composer.json';

		if ( \file_exists( $composer_file ) ) {
			$content = \file_get_contents( $composer_file );
			$json = h\safe_json_decode( $content, false );

			if ( isset( $json->require->php ) ) {
				$version = preg_replace( '/[^0-9.]/', '', $json->require->php );
				return $version;
			}
		}

		return false;
	}

	protected function is_plugin_active ( $plugin_main_file ) {
		include_once( \ABSPATH . 'wp-admin/includes/plugin.php' );
		return \is_plugin_active( $plugin_main_file );
	}

	protected function compare_version ( $version1, $version2, $operator = '>=' ) {
		return version_compare( strtolower( $version1 ), strtolower( $version2 ), $operator );
	}
}
