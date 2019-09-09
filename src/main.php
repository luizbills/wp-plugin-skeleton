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

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/load_helpers.php';

use src_namespace__\functions as h;

$use_whoops = h\all_equal(
	[
		class_exists( 'Whoops\\Run' ),
		! h\get_defined( 'DOING_AJAX' ),
		h\get_defined( 'WP_DEBUG' ),
		h\get_defined( 'WP_DEBUG_DISPLAY' )
	],
	true,
	true
);
if ( $use_whoops ) {
	$whoops = h\config_set( 'whoops_instance', new \Whoops\Run() );
	$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
	$whoops->register();
}

\src_namespace__\Core\Plugin::run( __FILE__ );
