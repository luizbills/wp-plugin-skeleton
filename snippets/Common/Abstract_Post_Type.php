<?php

namespace {ns}\Common;

abstract class Abstract_Post_Type {
	public function register_post_type () {
		$args = (array) $this->get_args();
		$args['labels'] = $this->get_labels();
		\register_post_type( $this->get_slug(), $args );
	}

	// Post type key. Must not exceed 20 characters
	// and may only contain lowercase alphanumeric characters, dashes, and underscores.
	abstract public function get_slug ();

	// see https://developer.wordpress.org/reference/functions/register_post_type/#parameters
	abstract public function get_args ();

	// see https://developer.wordpress.org/reference/functions/get_post_type_labels/#description
	abstract public function get_labels ();
}
