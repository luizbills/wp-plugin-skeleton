<?php
/*
Plugin Name: {{plugin_name}}
Version: {{plugin_version}}
Description: {{plugin_description}}
Author: {{plugin_author}}
Author URI: {{plugin_author_uri}}
Update URI: false

Text Domain: {{plugin_text_domain}}
Domain Path: /languages

License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'WPINC' ) ) die();

require_once __DIR__ . '/vendor/autoload.php';

\src_namespace__\Core\Plugin::run( __FILE__ );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	\WC_Konduto\Core\Plugin::run( __FILE__ );
} else {
	\add_action( 'admin_notices', function () {
		echo "<div class='notice notice-error'><p><strong>{{plugin_name}}</strong> can't be initialized because the Composer dependencies were not installed.</p></div>";
	} );
}
