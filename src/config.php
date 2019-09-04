<?php

// all values here can be acessed with `config_get` helper
return [
	'ASSETS_DIR' => 'assets',
	'TEMPLATES_DIR' => 'templates',
	'LANGUAGES_DIR' => 'languages',
	
	// disable the plugin cache while in development or debugging
	'DISABLE_CACHE' => WP_DEBUG,
];

// important: don't use reserved keys (they're used internally)
// reserved keys = NAME, VERSION, MAIN_FILE, ROOT_DIR
//
// also important: SLUG and PREFIX is auto generated if you do not set them here
