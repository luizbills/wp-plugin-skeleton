<?php

namespace src_namespace__\functions;

function is_rest_api () {
	return ( defined( 'REST_REQUEST' ) && REST_REQUEST );
}
