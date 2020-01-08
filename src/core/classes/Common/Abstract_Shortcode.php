<?php

namespace src_namespace__\Common;

use src_namespace__\functions as h;

abstract class Abstract_Shortcode {
	use Hooker_Trait;

	abstract public function get_shortcode_name ();
	abstract public function get_output ( $atts, $content );

	public function __init () {
		\add_shortcode( $this->get_shortcode_name() , [ $this, 'callback' ] );
	}

	public function callback ( $atts, $content = '', $tag = '' ) {
		$atts = empty( $atts ) ? [] : $atts;
		$atts = \array_merge( $this->get_default_attributes(), $atts );
		$validate = $this->validate_attributes( $atts );
		if ( true === $validate ) {
			return $this->get_output( $atts, $content );
		}
		return h\user_is_admin() ? "<div class='shortcode-$tag-error'>$validate</div>" : '';
	}

	public function get_default_attributes () {
		return [];
	}

	public function validate_attributes ( $atts ) {
		// should returns true on success
		// or an error string on failure 
		return true;
	}
}
