<?php
/*
RS Head Cleaner Lite - uninstall.php
Version: 1.4

This script uninstalls RS Head Cleaner Lite and removes all cache files, options, data, and traces of its existence.
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 	{ die(); }

if ( !defined( 'RSHCL_CACHE_DIR_NAME' ) ) 	{ define( 'RSHCL_CACHE_DIR_NAME', 'rshcl' ); }
if ( !defined( 'RSHCL_CACHE_PATH' ) ) 		{ define( 'RSHCL_CACHE_PATH', WP_CONTENT_DIR.'/cache/'.RSHCL_CACHE_DIR_NAME.'/' ); }
if ( !defined( 'RSHCL_JS_PATH' ) ) 			{ define( 'RSHCL_JS_PATH', RSHCL_CACHE_PATH.'/js/' ); }
if ( !defined( 'RSHCL_CSS_PATH' ) ) 		{ define( 'RSHCL_CSS_PATH', RSHCL_CACHE_PATH.'/css/' ); }

function rshcl_uninstall_plugin() {
	// Options to Delete
	$rshcl_options = array( 'rs_head_cleaner_lite_version', 'rshcl_admin_notices' );
	foreach( $rshcl_options as $i => $rshcl_option ) { delete_option( $rshcl_option ); }
	$rshcl_cache_path_old	= str_replace( '/cache/'.RSHCL_CACHE_DIR_NAME.'/', '/rshcl-cache/', RSHCL_CACHE_PATH );
	$rshcl_css_path_old		= str_replace( '/cache/'.RSHCL_CACHE_DIR_NAME.'/', '/rshcl-cache/', RSHCL_CSS_PATH );
	$rshcl_js_path_old		= str_replace( '/cache/'.RSHCL_CACHE_DIR_NAME.'/', '/rshcl-cache/', RSHCL_JS_PATH );
	$rshcl_dirs_all = array( 
		array( 'css' => RSHCL_CSS_PATH, 'js' => RSHCL_JS_PATH, 'cache' => RSHCL_CACHE_PATH ),
		array( 'css' => $rshcl_css_path_old, 'js' => $rshcl_js_path_old, 'cache' => $rshcl_cache_path_old ),
		);
	foreach( $rshcl_dirs_all as $i => $rshcl_dirs ) {
		foreach( $rshcl_dirs as $d => $dir ) {
			if ( is_dir( $rshcl_dirs[$d] ) ) {
				$filelist = rshcl_scandir( $rshcl_dirs[$d] );
				foreach( $filelist as $f => $filename ) {
					$file = $rshcl_dirs[$d].$filename;
					if ( is_file( $file ) ){
						@chmod( $file, 0775 );
						@unlink( $file );
						if ( file_exists( $file ) ) { @chmod( $file, 0644 ); }
						}
					}
				@chmod( $rshcl_dirs[$d], 0775 );
				@rmdir( $rshcl_dirs[$d] );
				if ( file_exists( $rshcl_dirs[$d] ) ) { @chmod( $rshcl_dirs[$d], 0755 ); }
				}
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