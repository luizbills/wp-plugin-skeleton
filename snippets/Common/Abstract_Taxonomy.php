<?php

namespace {ns}\Common;

abstract class Abstract_Taxonomy {
	public function register_taxonomy () {
		$args = (array) $this->get_args();
		$args['labels'] = $this->get_labels();
		\register_taxonomy( $this->get_slug(), $this->get_post_types(), $args );
	}

	// taxonomy key, must not exceed 32 characters.
	// and may only contain lowercase alphanumeric characters, dashes, and underscores.
	abstract public function get_slug ();

	// array of object types with which the taxonomy should be associated.
	abstract public function get_post_types ();

	// see https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
	abstract public function get_args ();

	// see https://developer.wordpress.org/reference/functions/get_taxonomy_labels/#return
	abstract public function get_labels ();
}
