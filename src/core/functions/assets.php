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

function get_asset_url ( $file_path ) {
	return \plugins_url( config_get( 'ASSETS_DIR' ) . '/' . $file_path, config_get('MAIN_FILE') );
}

function get_assets_dir () {
	$dir = config_get( 'ROOT_DIR' ) . '/' . config_get( 'ASSETS_DIR' );
	return \apply_filters( prefix( 'assets_dir' ), $dir );
}
