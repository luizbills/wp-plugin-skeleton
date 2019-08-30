<?php

// all values here can be acessed with `config_get` helper
return [
	'ASSETS_DIR' => 'assets',
	'TEMPLATES_DIR' => 'templates',
	'LANGUAGES_DIR' => 'languages'
];

// important: don't use reserved keys (they're used internally)
// reserved keys = NAME, VERSION, MAIN_FILE, ROOT_DIR
//
// also important: SLUG and PREFIX is auto generated if you do not set them here
