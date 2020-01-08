<?php

namespace src_namespace__\Common;

use src_namespace__\functions as h;

abstract class Abstract_Ajax_Action {
	use Hooker_Trait;

	abstract public function get_action_key ();

	public function __init () {
		$this->add_action( 'wp_ajax_' . $this->get_action_name(), 'handle_request' );
		if ( $this->is_public() ) {
			$this->add_action( 'wp_ajax_nopriv_' . $this->get_action_name(), 'handle_request' );
		}

		if ( $this->get_nonce_action() ) {
			h\register_ajax_nonce(
				$this->get_action_key(),
				$this->get_nonce_query_arg(),
				\wp_create_nonce( $this->get_nonce_action() )
			);
		}
	}

	public function get_action_name () {
		return h\prefix( $this->get_action_key() );
	}

	public function is_public () {
		return false;
	}

	public function handle_get () {
		$this->send_default_response();
	}

	public function handle_post () {
		$this->send_default_response();
	}

	public function handle_request () {
		$this->validate_request();
		$method = $_SERVER['REQUEST_METHOD'];
		$callback = \strtolower( "handle_$method" );
		if ( \method_exists( $this,  $callback ) ) {
			$this->$callback();
		} else {
			$this->send_default_response();
		}
	}

	public function get_nonce_action () {
		return 'ajax_nonce_' . $this->get_action_name();
	}

	public function get_nonce_query_arg () {
		return '_ajax_nonce';
	}

	protected function validate_request () {
		// validate with wp nonce
		$nonce_action = $this->get_nonce_action();

		if ( $nonce_action ) {
			$query_arg = $this->get_nonce_query_arg();
			if ( ! \check_ajax_referer( $nonce_action, $query_arg, false ) ) {
				$this->send_json_error(
					__( 'Forbidden Access.', 'reunidas-user-updater' ),
					403
				);
			}
		}
	}

	protected function send_default_response () {
		$this->send_json_error(
			__( 'Invalid request method.', 'reunidas-user-updater' ),
			405
		);
	}

	protected function send_json_error ( $error_message, $status_code = 400 ) {
		$this->send_json( null, $error_message, $status_code );
	}

	protected function send_json_success ( $data, $status_code = 200 ) {
		$this->send_json( $data, null, $status_code );
	}

	protected function send_json ( $data, $error_message = '', $status_code = null ) {
		$response = [];
		if ( empty( $error_message ) ) {
			$status_code = $status_code ? $status_code : 200;
		} else {
			$status_code = $status_code ? $status_code : 400;
			$response['error_message'] = $error_message;
		}
		$response['success'] = $status_code >= 200 && $status_code < 300;
		$response['data'] = $data;
		$response['meta'] = [
			'status_code' => $status_code
		];
		\wp_send_json( $response, $status_code );
	}
}
