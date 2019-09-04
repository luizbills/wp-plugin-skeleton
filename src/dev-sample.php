<?php

namespace src_namespace__;

use src_namespace__\functions as h;

// test and simulate things here
class Test {
	public function pre_boot () {
		// some code here
	}
	
	public function boot () {
		// some code here
	}

	public function init () {
		// or here
	}
}

h\load_class( Test::class, 'pre_boot' );
h\load_class( Test::class, 'boot' );
h\load_class( Test::class, 'init' );
