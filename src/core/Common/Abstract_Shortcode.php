<?php

namespace src_namespace__\Common;

use src_namespace__\functions as h;

abstract class Abstract_Shortcode {
	use Hooker_Trait;

	abstract public function get_shortcode_name ();
	abstract public function get_output ( $atts, $content );

	public function init () {
		\add_shortcode( $this->get_shortcode_name() , [ $this, 'callback' ] );
	}

	public function callback ( $atts, $content = '', $tag = '' ) {
		$atts = empty( $atts ) ? [] : $atts;
		$atts = \array_merge( $atts, $this->get_default_attributes() );
		if ( $this->validate_attributes( $atts ) ) {
			return $this->get_output( $atts, $content );
		}
		return \apply_filters( h\prefix( "shortcode_${tag}_error_output" ), '', $atts, $content );
	}

	public function get_default_attributes () {
		return [];
	}

	public function validate_attributes ( $atts ) {
		return true;
	}
}
