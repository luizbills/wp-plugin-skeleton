<?php

// all values here can be acessed with `config_get` helper
return [
	'NAME' => '{{plugin_name}}',
	'VERSION' => '{{plugin_version}}',
	'ASSETS_DIR' => 'assets',
	'TEMPLATES_DIR' => 'templates',
	'LANGUAGES_DIR' => 'languages'
];

// important: don't use reserved keys (they're used internally)
// reserved keys = SLUG, PREFIX, MAIN_FILE, ROOT_DIR
