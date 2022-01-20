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

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	\src_namespace__\Core\Plugin::run( __FILE__ );
} else {
	\add_action( 'admin_notices', function () {
		list( $plugin_name ) = \get_file_data( __FILE__, [ 'plugin name' ] );
		$message = sprintf( 
			__( '%s can\'t be initialized because the Composer dependencies were not installed. Reinstall the plugin or run <code>composer install</code>.', '{{plugin_text_domain}}' ),
			"<strong>$plugin_name</strong>"
		);
		echo "<div class='notice notice-error'><p>$message</p></div>";
	} );
}
