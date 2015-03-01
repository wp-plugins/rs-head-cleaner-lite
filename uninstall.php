<?php
/*
RS Head Cleaner Lite - uninstall.php
Version: 1.3.4

This script uninstalls RS Head Cleaner Lite and removes all cache files, options, data, and traces of its existence.
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

if ( !defined( 'RSHCL_PLUGIN_PATH_SL' ) ) 	{ define( 'RSHCL_PLUGIN_PATH_SL', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/' ); }
if ( !defined( 'RSHCL_CACHE_DIR_NAME' ) ) 	{ define( 'RSHCL_CACHE_DIR_NAME', 'rshcl-cache' ); }
if ( !defined( 'RSHCL_CACHE_PATH' ) ) 		{ define( 'RSHCL_CACHE_PATH', WP_CONTENT_DIR.'/'.RSHCL_CACHE_DIR_NAME.'/' ); }
if ( !defined( 'RSHCL_JS_PATH' ) ) 			{ define( 'RSHCL_JS_PATH', RSHCL_CACHE_PATH.'/js/' ); }
if ( !defined( 'RSHCL_CSS_PATH' ) ) 		{ define( 'RSHCL_CSS_PATH', RSHCL_CACHE_PATH.'/css/' ); }

function rshcl_uninstall_plugin() {
	// Options to Delete
	$rshcl_option_names = array( 'rs_head_cleaner_lite_version', 'rshcl_admin_notices' );
	foreach( $rshcl_option_names as $i => $rshcl_option ) {
		delete_option( $rshcl_option );
		}

	$rshcl_dirs = array( 'css' => RSHCL_CSS_PATH, 'js' => RSHCL_JS_PATH, 'cache' => RSHCL_CACHE_PATH );
	foreach( $rshcl_dirs as $d => $dir ) {
		if ( is_dir( $rshcl_dirs[$d] ) ) {
			$filelist = rshcl_scandir( $rshcl_dirs[$d] );
			foreach( $filelist as $f => $filename ) {
				$file = $rshcl_dirs[$d].$filename;
				if ( is_file( $file ) ){
					chmod( $file, 0777 );
					@unlink( $file );
					if ( file_exists( $file ) ) { chmod( $file, 0644 ); }
					}
				}
			chmod( $rshcl_dirs[$d], 0777 );
			@rmdir( $rshcl_dirs[$d] );
			if ( file_exists( $file ) ) { chmod( $rshcl_dirs[$d], 0755 ); }
			}
		}
	
	}

function rshcl_scandir( $dir ) {
	clearstatcache();
	$dot_files = array( '..', '.' );
	$dir_contents_raw = scandir( $dir );
	$dir_contents = array_values( array_diff( $dir_contents_raw, $dot_files ) );
	return $dir_contents;
	}

rshcl_uninstall_plugin();

?>