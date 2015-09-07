<?php
/*
RS Head Cleaner Lite - uninstall.php
Version: 1.4.2

This script uninstalls RS Head Cleaner Lite and removes all cache files, options, data, and traces of its existence.
*/

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 	{ die(); }

if ( !defined( 'RSHCP_CACHE_DIR_NAME' ) ) 	{ define( 'RSHCP_CACHE_DIR_NAME', 'rshcl' ); }
if ( !defined( 'RSHCP_CACHE_PATH' ) ) 		{ define( 'RSHCP_CACHE_PATH', WP_CONTENT_DIR.'/cache/'.RSHCP_CACHE_DIR_NAME.'/' ); }
if ( !defined( 'RSHCP_JS_PATH' ) ) 			{ define( 'RSHCP_JS_PATH', RSHCP_CACHE_PATH.'/js/' ); }
if ( !defined( 'RSHCP_CSS_PATH' ) ) 		{ define( 'RSHCP_CSS_PATH', RSHCP_CACHE_PATH.'/css/' ); }

function rshcp_uninstall_plugin() {
	// Options to Delete
	$rshcp_options = array( 'rshcp_options', 'rshcp_admin_notices', 'rshcp_admin_notices', 'rs_head_cleaner_lite_version', 'rshcl_admin_notices' );
	foreach( $rshcp_options as $i => $rshcp_option ) { delete_option( $rshcp_option ); }
	$rshcp_cache_path_old	= str_replace( '/cache/'.RSHCP_CACHE_DIR_NAME.'/', '/rshcl-cache/', RSHCP_CACHE_PATH );
	$rshcp_css_path_old		= str_replace( '/cache/'.RSHCP_CACHE_DIR_NAME.'/', '/rshcl-cache/', RSHCP_CSS_PATH );
	$rshcp_js_path_old		= str_replace( '/cache/'.RSHCP_CACHE_DIR_NAME.'/', '/rshcl-cache/', RSHCP_JS_PATH );
	$rshcp_dirs_all = array( 
		array( 'css' => RSHCP_CSS_PATH, 'js' => RSHCP_JS_PATH, 'cache' => RSHCP_CACHE_PATH ),
		array( 'css' => $rshcp_css_path_old, 'js' => $rshcp_js_path_old, 'cache' => $rshcp_cache_path_old ),
		);
	foreach( $rshcp_dirs_all as $i => $rshcp_dirs ) {
		foreach( $rshcp_dirs as $d => $dir ) {
			if ( is_dir( $rshcp_dirs[$d] ) ) {
				$filelist = rshcp_scandir( $rshcp_dirs[$d] );
				foreach( $filelist as $f => $filename ) {
					$file = $rshcp_dirs[$d].$filename;
					if ( is_file( $file ) ){
						@chmod( $file, 0775 );
						@unlink( $file );
						if ( file_exists( $file ) ) { @chmod( $file, 0644 ); }
						}
					}
				@chmod( $rshcp_dirs[$d], 0775 );
				@rmdir( $rshcp_dirs[$d] );
				if ( file_exists( $rshcp_dirs[$d] ) ) { @chmod( $rshcp_dirs[$d], 0755 ); }
				}
			}
		}
	}

function rshcp_scandir( $dir ) {
	clearstatcache();
	$dot_files = array( '..', '.' );
	$dir_contents_raw = scandir( $dir );
	$dir_contents = array_values( array_diff( $dir_contents_raw, $dot_files ) );
	return $dir_contents;
	}

rshcp_uninstall_plugin();

?>