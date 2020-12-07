<?php

namespace src_namespace__\functions;

use src_namespace__\Utils\Asset_Manager;

function assets () {
	$assets = config_get_instance( Asset_Manager::class, false );
	if ( false === $assets ) {
		$assets = config_set_instance( Asset_Manager::class );
	}
	return $assets;
}

function get_assets_dir () {
	$dir = config_get( 'ROOT_DIR' ) . '/' . config_get( 'ASSETS_DIR' );
	return \apply_filters( prefix( 'assets_dir' ), $dir );
}

// Return the URL of a asset located in plugin or in the active theme.
// Also, can load an alternative version of any asset (with higher priority). The alternative version will store a text content, must be in same location and ends with `.txt`.
// Example:
//   - real asset: 'images/logo.png'
//   - alternative asset: 'images/logo.png.txt' (will override if exists)
function get_asset_url ( $file_path ) {
	$theme_folder = '/plugins/' . config_get( 'SLUG' ) . '/' . config_get( 'ASSETS_DIR' );
	$paths = [
		// search in theme templates
		'theme' => \get_stylesheet_directory() . $theme_folder,
		// search in plugin templates
		'plugin' => get_assets_dir(),
	];
	$urls = [
		'theme' => \get_stylesheet_directory_uri() . $theme_folder,
		'plugin' => \plugins_url( config_get( 'ASSETS_DIR' ), config_get('MAIN_FILE') ),
	];

	foreach ( $paths as $type => $base_path ) {
		$alt = $file_path . '.txt';
		if ( \file_exists( "$base_path/$alt" ) ) {
			return $urls[ $type ] . "/$alt";
		}
		elseif ( \file_exists( "$base_path/$file_path" ) ) {
			return $urls[ $type ] . "/$file_path";
		}
	}

	logf( "Can't asset template file: $file_path" );
	return false;
}
