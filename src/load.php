<?php

namespace src_namespace__;

use src_namespace__\functions as h;

// check the plugin dependecies
if ( class_exists( Plugin_Dependencies::class ) ) {
	h\config_set_instance( Plugin_Dependencies::class );
}

$init_classes = [
	// classes with `init` method
	// init() method is called in 'plugins_loaded' hook
	// just put the class name in this array
	// to set a priority use an array
	// e.g.: [ Namespace\MyClass::class, 20 ] // priority = 20

	//Some_Namespace\Demo::class,
];

$boot_classes = [
	// classes with `boot` method
	// boot() method is called in 'init' hook

	//Some_Namespace\Demo::class,
];

// DON'T EDIT HERE
$classes = [
	'boot' => $boot_classes,
	'init' => $init_classes,
];

foreach ( $classes as $context => $class_names ) {
	foreach ( $class_names as $class_name ) {
		$priority = 10;
		if ( is_array( $class_name ) ) {
			$priority = $class_name[1];
			$class_name = $class_name[0];
		}
		h\load_class( $class_name, $context, $priority );
	}
}
