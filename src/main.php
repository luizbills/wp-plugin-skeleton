<?php
/*
Plugin Name: {{plugin_name}}
Version: {{plugin_version}}
Description: {{plugin_description}}
Author: {{plugin_author}}
Author URI: {{plugin_author_uri}}

Text Domain: {{plugin_text_domain}}
Domain Path: /languages

License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'WPINC' ) ) die();

use src_namespace__\functions as h;

require_once __DIR__ . '/vendor/autoload.php';

\src_namespace__\Core\Plugin::run( __FILE__ );
