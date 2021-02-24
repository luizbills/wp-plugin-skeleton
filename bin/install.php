<?php

include_once __DIR__ . '/helpers.php';

$is_test = in_array( '--test', $argv );
$defaults = [
	'Version' => '1.0.0',
];
$values = null;
$ready = false;
$prompts = [
	'Plugin Name' => '(e.g. Awesome Plugin)',
	'Plugin Description' => '(e.g. Just another plugin)',
	'Plugin Author' => '(e.g. Luiz Bills)',
	'Plugin Author URL' => '(e.g. luizpb.com)',
	'Plugin Text Domain' => '(e.g. awesome-plugin)',
	'PHP Namespace' => '(e.g. luizbills\AwesomePlugin)',
	'Version' => '[1.0.0]',
];

// auto fill if has `--test` option
if ( $is_test ) {
	$ready = true;
	$values = [
		'Plugin Name' => '_Skeleton Dev Plugin',
		'Plugin Description' => 'Use this plugin to test the wp-plugin-skeleton boilerplate',
		'Plugin Author' => 'An Awesome Contribuitor',
		'Plugin Author URL' => 'https://github.com/luizbills/wp-plugin-skeleton',
		'Plugin Text Domain' => 'skeleton-dev-plugin',
		'PHP Namespace' => 'Skeleton\DevPlugin',
		'Version' => date( 'Y-m-d @ H:m:s' ),
	];
}

// get some plugin informations
while ( ! $ready ) {
	$values = [];

	foreach ( $prompts as $var => $desc ) {
		$value = '';
		while ( empty( $value ) ) {
			cls();
			$value = readline( "$var $desc: " );
			if ( empty( $value ) && isset( $defaults[ $var ] ) ) {
				$value = $defaults[ $var ];
			}
		}
		$values[ $var ] = $value;
	}

	if ( empty( $values['Version'] ) ) {
		$values['Version'] = '1.0.0';
	}

	cls();

	foreach ( $values as $key => $value ) {
		echo "$key: $value\n\r";
	}

	// give the user a chance to fix the informations
	$confirm = strtolower( readline( 'Is it OK? [Y/n] ' ) );

	$ready = '' === $confirm || 'y' === $confirm;
}

cls();

// placeholders to find and to replace
$find_replace = [
	'src_namespace__' => $values['PHP Namespace'],
	'{{composer_namespace}}' => str_replace('\\', '\\\\', $values['PHP Namespace'] ) . '\\\\',
	'{{plugin_name}}' => $values['Plugin Name'],
	'{{plugin_description}}' => $values['Plugin Description'],
	'{{plugin_author}}' => $values['Plugin Author'],
	'{{plugin_author_uri}}' => substr( $values['Plugin Author URL'], 0, 4 ) === 'http' ? $values['Plugin Author URL'] : 'https://' . $values['Plugin Author URL'],
	'{{plugin_text_domain}}' => $values['Plugin Text Domain'],
	'{{plugin_version}}' => $values['Version'],
	'{{plugin_slug}}' => slugify( $values['Plugin Name'] ),
	'{{plugin_prefix}}' => prefixify( $values['Plugin Name'] ),
];

// useful informations
$root = dirname( __DIR__ );
$slug = slugify( $values['Plugin Name'] );
$dest_dir = dirname( $root ) . '/' . ( $is_test ? "_$slug" : $slug );
$src_dir = $root . '/src';
$tmp_vendor = '';

// remove old plugin
if ( file_exists( $dest_dir ) ) {
	if ( $is_test ) {
		if ( file_exists( "$dest_dir/vendor" ) ) {
			$tmp_vendor = '/tmp/tmp-skeleton-vendor-' . time();
			mv( "$dest_dir/vendor", $tmp_vendor );
		}
		rrmdir( $dest_dir );
	} else {
		echo "error: $dest_dir already exists." . PHP_EOL;
		exit( 1 );
	}
}

mkdir( $dest_dir, 0755 );

if ( $tmp_vendor && file_exists( $tmp_vendor ) ) {
	echo mv( $tmp_vendor, "$dest_dir/vendor" );
}

// list of files and folders
$files = rscandir( $src_dir );

// copy src files and replace
foreach ( $files as $file ) {
	// don't copy dev-sample.php
	if ( basename( $file ) === 'dev-sample.php' ) continue;

	$target = str_replace( $src_dir, $dest_dir, $file );
	$target_dir = dirname( $target );

	if ( ! file_exists( $target_dir ) ) {
		mkdir( $target_dir, 0755, true );
	}

	$content = file_get_contents( $file );
	$content = str_replace(
		array_keys( $find_replace ),
		array_values( $find_replace ),
		$content
	);
	file_put_contents( $target, $content );
}

// include dev.php
if ( $is_test && file_exists( "$dest_dir/dev.php" ) ) {
	file_put_contents(
		"$dest_dir/load.php",
		PHP_EOL . "include __DIR__ . '/dev.php';" . PHP_EOL,
		FILE_APPEND
	);
}

// install dependencies via composer
echo 'Running composer...' . PHP_EOL;
if ( ! file_exists( "$dest_dir/vendor" ) ) {
	chdir( $dest_dir );
	echo shell_exec( 'composer update' );
} else {
	echo 'Composer packages already installed' . PHP_EOL;
}

// save the plugin folder name in a temporary file
if ( ! $is_test ) {
	file_put_contents( dirname( $root ) . '/.tmp_wp_plugin_dir', basename( $dest_dir ) );
}

echo "The plugin was successfully created in $dest_dir" . PHP_EOL . PHP_EOL;

