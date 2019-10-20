<?php

namespace src_namespace__;

use src_namespace__\functions as h;

$init_classes = [
	// classes with `init` method
	// init() method is called in 'plugins_loaded' hook
	// just put the class name in this array
	// to set a priority use an array
	// e.g.: [ Namespace\MyClass::class, 20 ] // priority = 20
	//Some_Namespace\Foo::class,
];

$boot_classes = [
	// classes with `boot` method
	// boot() method is called in 'init' hook
	//Some_Namespace\Foo::class,
	Plugin_Dependencies::class
];

$pre_boot_classes = [
	// classes with `pre_boot` method
	//Some_Namespace\Foo::class,
];

$activation_classes = [
	// classes with 'activation' static method
	//Some_Namespace\Foo::class,
];

$deactivation_classes = [
	// classes with 'deactivation' static method
	//Some_Namespace\Foo::class,
];

// DON'T EDIT HERE
$classes = [
	'pre_boot' => $pre_boot_classes,
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

\register_activation_hook( h\config_get( 'MAIN_FILE' ), function () use ( $activation_classes ) {
	foreach ( $activation_classes as $class_name ) {
		echo call_user_func( [ $class_name, 'activation' ] );
	}
} );

\register_deactivation_hook( h\config_get( 'MAIN_FILE' ), function () use ( $deactivation_classes ) {
	foreach ( $deactivation_classes as $class_name ) {
		echo call_user_func( [ $class_name, 'deactivation' ] );
	}
} );
