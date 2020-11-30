<?php

namespace {ns}\Common;

use {ns}\functions as h;

abstract class Abstract_Post_Type {

	protected $post = null;

	// Post type key. Must not exceed 20 characters
	// and should only contain lowercase alphanumeric characters, dashes, and underscores.
	abstract public function get_slug ();

	// see https://developer.wordpress.org/reference/functions/register_post_type/#parameters
	abstract public function get_args ();

	// see https://developer.wordpress.org/reference/functions/get_post_type_labels/#description
	abstract public function get_labels ();

	public function __construct ( $post_id = 0 ) {
		if ( $post_id > 0 ) {
			$this->post = h\get_post_by_type( $post_id, $this->get_slug() );
		}
	}

	public function register_post_type () {
		// call this method on __init
		\register_post_type( $this->get_slug(), $this->get_args() );
	}

	public function get_id () {
		return $this->post ? $this->post->ID : 0;
	}

	public function set_post ( $data = [] ) {
		$post_id = $this->get_id();
		$new = 0 === $post_id;

		$data['ID'] = $post_id;
		$data['post_type'] = $this->get_slug();
		$post_id = $new ? \wp_insert_post( $data ) : wp_update_post( $data );

		if ( $new ) {
			$this->post = h\get_post_by_type( $post_id, $this->get_slug() );
		}

		return $this->post;
	}

	public function get_post () {
		if ( ! $this->post ) $this->set_post();
		return $this->post;
	}

	public function get_meta ( $key, $single = true ) {
		return \get_post_meta( $this->get_id(), $key, $single );
	}

	public function update_meta ( $key, $value ) {
		return \update_post_meta( $this->get_id(), $key, $value );
	}

	public function add_meta ( $key, $value ) {
		return \add_post_meta( $this->get_id(), $key, $value );
	}
}
