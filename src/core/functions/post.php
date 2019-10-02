<?php

namespace src_namespace__\functions;

function get_post ( $id, $post_type = 'post' ) {
	$post = \get_post( \intval( $id ) );

	throw_if( null === $post, "Invalid post ID: $id", 'invalid-post-id' );
	throw_if( $post_type !== $post->post_type, "Invalid type for post #$id: $post_type", 'invalid-post-type' );

	return $post;
}

