<?php

namespace {ns};

use {ns}\functions as h;
use {ns}\Common\Hooker_Trait;

final class REST_Endpoint {
	use Hooker_Trait;

	public function __init () {
		$this->add_action( 'rest_api_init', 'register_endpoint' );
	}

	public function register_endpoint ( $data ) {
		// DOC: https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
		\register_rest_route( 'prefix/v1', '/action', [
			'methods' => 'POST',
			'callback' => [ $this, 'handle_post_request' ],
			'permission_callback' => '__return_true',
		] );
	}

	public function handle_post_request ( \WP_REST_Request $req ) {
		$return = [ 'success' => true ];

		$response = new \WP_REST_Response( $return );
		$response->set_status( 200 );

		return $response;
	}
}
