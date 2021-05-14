<?php

namespace src_namespace__\functions;

function is_rest_api () {
	return get_defined( 'REST_REQUEST' ) && REST_REQUEST;
}
