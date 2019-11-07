<?php

namespace src_namespace__;

// all classes below are loaded with hooks
// to set the hook priority use a array.
// eg:
// ```
// [ MyClass::class, 20 ] // priority = 20
// ```
$classes_to_load = [
	Plugin_Dependencies::class,
];
