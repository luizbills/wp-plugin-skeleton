<?php

namespace src_namespace__\functions;

function get_post ( $id, $post_type = 'post' ) {
	$post = \get_post( \intval( $id ) );

	throw_if( null === $post, "Invalid Post ID: $id" );
	throw_if( $post_type !== $post->post_type, "Post #$id isn't a '$post_type'" );

	return $post;
}

