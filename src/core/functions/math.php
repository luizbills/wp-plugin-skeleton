<?php

namespace src_namespace__\functions;

function math_mean ( $numbers ) {
	return array_sum( $numbers ) / count( $numbers );
}

function math_negate ( $number ) {
	$number = \floatval( $number );
	return $number > 0 ? -$number : $number;
}

function math_clamp ( $number, $min, $max ) {
	return \max( $min, \min( $max, $number ) );
}
