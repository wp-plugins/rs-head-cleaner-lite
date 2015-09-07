<?php
/*
Plugin Name: RS Head Cleaner Lite
Plugin URI: http://www.redsandmarketing.com/plugins/rs-head-cleaner/
Description: This plugin cleans up a number of issues, doing the work of multiple plugins, improving speed, efficiency, security, SEO, and user experience. It removes junk code from the document HEAD & HTTP headers, hides the WP Version, Combines/Minifies/Caches CSS and JavaScript files, removes HTML comments, removes version numbers from CSS and JS links, and fixes the "Read more" link so it displays the entire post.
Author: Scott Allen
Version: 1.4.2
Author URI: http://www.redsandmarketing.com/
Text Domain: rs-head-cleaner-lite
License: GPLv2
*/

/*  Copyright 2014-2015 Scott Allen (email : plugins [at] redsandmarketing [dot] com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* PLUGIN - BEGIN */

/* Make sure plugin remains secure if called directly */
if( !defined( 'ABSPATH' ) ) {
	if( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die( 'ERROR: This plugin requires WordPress and will not function if called directly.' );
}

/* BENCHMARK - BEGIN */
/*$start_time = rshcp_microtime();*/

define( 'RSHCP_VERSION', '1.4.2' );
define( 'RSHCP_REQUIRED_WP_VERSION', '3.8' );
define( 'RSHCP_REQUIRED_PHP_VERSION', '5.3' );

if( !defined( 'RSHCP_DEBUG' ) ) 				{ define( 'RSHCP_DEBUG', FALSE ); } /* Do not change value unless developer asks you to - for debugging only. Change in wp-config.php. */
if( !defined( 'RSHCP_EOL' ) ) 					{ $rshcp_eol = defined( 'PHP_EOL' ) ? PHP_EOL : rshcp_eol(); define( 'RSHCP_EOL', $rshcp_eol ); }
if( !defined( 'RSHCP_DS' ) ) 					{ $rshcp_ds = defined( 'DIRECTORY_SEPARATOR' ) ? DIRECTORY_SEPARATOR : rshcp_ds(); define( 'RSHCP_DS', $rshcp_ds ); }
if( !defined( 'RSHCP_SITE_URL' ) ) 				{ define( 'RSHCP_SITE_URL', untrailingslashit( home_url() ) ); }
if( !defined( 'RSHCP_SITE_PATH' ) ) 			{ define( 'RSHCP_SITE_PATH', untrailingslashit( ABSPATH ) ); }
if( !defined( 'RSHCP_SITE_DOMAIN' ) ) 			{ define( 'RSHCP_SITE_DOMAIN', rshcp_get_domain( RSHCP_SITE_URL ) ); }
if( !defined( 'RSHCP_PLUGIN_BASENAME' ) ) 		{ define( 'RSHCP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }
if( !defined( 'RSHCP_PLUGIN_FILE_BASENAME' ) )	{ define( 'RSHCP_PLUGIN_FILE_BASENAME', trim( basename( __FILE__ ), '/' ) ); }
if( !defined( 'RSHCP_PLUGIN_NAME' ) ) 			{ define( 'RSHCP_PLUGIN_NAME', trim( dirname( RSHCP_PLUGIN_BASENAME ), '/' ) ); }
if( !defined( 'RSHCP_PLUGIN_PATH' ) ) 			{ define( 'RSHCP_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/' ); }

if( !defined( 'RSHCP_REMOVE_OPEN_SANS' ) ) 		{ define( 'RSHCP_REMOVE_OPEN_SANS', FALSE ); } /* Change in wp-config.php */
/***
* By default this feature is off, but if you don't need Open Sans and you want a faster site, add a line in your wp-config.php that says: "define( 'RSHCP_REMOVE_OPEN_SANS', TRUE );"
***/
if( !defined( 'RSHCP_CACHE_DIR_NAME' ) ) 		{ define( 'RSHCP_CACHE_DIR_NAME', 'rshcp' ); }
if( !defined( 'RSHCP_CACHE_PATH' ) ) 			{ define( 'RSHCP_CACHE_PATH', WP_CONTENT_DIR.'/cache/'.RSHCP_CACHE_DIR_NAME.'/' ); }
if( !defined( 'RSHCP_JS_PATH' ) ) 				{ define( 'RSHCP_JS_PATH', RSHCP_CACHE_PATH.'js/' ); }
if( !defined( 'RSHCP_CSS_PATH' ) ) 				{ define( 'RSHCP_CSS_PATH', RSHCP_CACHE_PATH.'css/' ); }
if( !defined( 'RSHCP_CACHE_URL' ) ) 			{ define( 'RSHCP_CACHE_URL', WP_CONTENT_URL.'/cache/'.RSHCP_CACHE_DIR_NAME.'/' ); }
if( !defined( 'RSHCP_JS_URL' ) ) 				{ define( 'RSHCP_JS_URL', RSHCP_CACHE_URL.'js/' ); }
if( !defined( 'RSHCP_CSS_URL' ) ) 				{ define( 'RSHCP_CSS_URL', RSHCP_CACHE_URL.'css/' ); }
if( !defined( 'RSHCP_CONTENT_DIR_URL' ) ) 		{ define( 'RSHCP_CONTENT_DIR_URL', WP_CONTENT_URL ); }
if( !defined( 'RSHCP_CONTENT_DIR_PATH' ) ) 		{ define( 'RSHCP_CONTENT_DIR_PATH', WP_CONTENT_DIR ); }
if( !defined( 'RSHCP_PLUGINS_DIR_URL' ) ) 		{ define( 'RSHCP_PLUGINS_DIR_URL', WP_PLUGIN_URL ); }
if( !defined( 'RSHCP_PLUGINS_DIR_PATH' ) )		{ define( 'RSHCP_PLUGINS_DIR_PATH', WP_PLUGIN_DIR ); }
if( !defined( 'RSHCP_INCL_DIR_URL' ) ) 			{ define( 'RSHCP_INCL_DIR_URL', RSHCP_SITE_URL.'/'.WPINC.'/' ); }
if( !defined( 'RSHCP_INCL_DIR_PATH' ) )			{ define( 'RSHCP_INCL_DIR_PATH', RSHCP_SITE_PATH.'/'.WPINC.'/' ); }
if( !defined( 'RSHCP_SERVER_ADDR' ) ) 			{ define( 'RSHCP_SERVER_ADDR', rshcp_get_server_addr() ); }
if( !defined( 'RSHCP_SERVER_NAME' ) ) 			{ define( 'RSHCP_SERVER_NAME', rshcp_get_server_name() ); }
if( !defined( 'RSHCP_SERVER_NAME_REV' ) ) 		{ define( 'RSHCP_SERVER_NAME_REV', strrev( RSHCP_SERVER_NAME ) ); }
if( !defined( 'RSHCP_DEBUG_SERVER_NAME' ) ) 	{ define( 'RSHCP_DEBUG_SERVER_NAME', '.redsandmarketing.com' ); }
if( !defined( 'RSHCP_DEBUG_SERVER_NAME_REV' ) )	{ define( 'RSHCP_DEBUG_SERVER_NAME_REV', strrev( RSHCP_DEBUG_SERVER_NAME ) ); }
if( !defined( 'RSHCP_RSM_URL' ) ) 				{ define( 'RSHCP_RSM_URL', 'http://www.redsandmarketing.com/' ); }
if( !defined( 'RSHCP_HOME_URL' ) ) 				{ define( 'RSHCP_HOME_URL', RSHCP_RSM_URL.'plugins/'.RSHCP_PLUGIN_NAME.'/' ); }
if( !defined( 'RSHCP_SUPPORT_URL' ) ) 			{ define( 'RSHCP_SUPPORT_URL', RSHCP_RSM_URL.'plugins/wordpress-plugin-support/?plugin='.RSHCP_PLUGIN_NAME.'/' ); }
if( !defined( 'RSHCP_WP_URL' ) ) 				{ define( 'RSHCP_WP_URL', 'https://wordpress.org/extend/plugins/'.RSHCP_PLUGIN_NAME.'/' ); }
if( !defined( 'RSHCP_WP_RATING_URL' ) ) 		{ define( 'RSHCP_WP_RATING_URL', 'https://wordpress.org/support/view/plugin-reviews/'.RSHCP_PLUGIN_NAME ); }
if( !defined( 'RSHCP_DONATE_URL' ) ) 			{ define( 'RSHCP_DONATE_URL', 'http://bit.ly/'.RSHCP_PLUGIN_NAME.'-donate' ); }
if( !defined( 'RSHCP_PHP_VERSION' ) ) 			{ define( 'RSHCP_PHP_VERSION', PHP_VERSION ); }
if( !defined( 'RSHCP_WP_VERSION' ) ) 			{ global $wp_version; define( 'RSHCP_WP_VERSION', $wp_version ); }

if( strpos( RSHCP_SERVER_NAME_REV, RSHCP_DEBUG_SERVER_NAME_REV ) !== 0 && RSHCP_SERVER_ADDR != '127.0.0.1' && TRUE !== RSHCP_DEBUG && TRUE !== WP_DEBUG ) {
	error_reporting(0); /* Prevents error display on production sites, but testing on 127.0.0.1 will display errors, or if debug mode turned on */
}

unset( $rshcp_eol, $rshcp_ds );

/* SET ADVANCED OPTIONS - Change be overridden in wp-config.php. Advanced users only. */
if( !defined( 'RSHCP_CKF_IMG_DOM' ) ) 			{ define( 'RSHCP_CKF_IMG_DOM', FALSE ); }
if( !defined( 'RSHCP_NO_MINIFY_ALL' ) ) 		{ define( 'RSHCP_NO_MINIFY_ALL', FALSE ); }
if( !defined( 'RSHCP_NO_MINIFY_CSS' ) ) 		{ define( 'RSHCP_NO_MINIFY_CSS', FALSE ); }
if( !defined( 'RSHCP_NO_MINIFY_JS' ) ) 			{ define( 'RSHCP_NO_MINIFY_JS', FALSE ); }
if( !defined( 'RSHCP_NO_MINIFY_HTML' ) ) 		{ define( 'RSHCP_NO_MINIFY_HTML', TRUE ); }		/* LITE */
if( !defined( 'RSHCP_NO_MINLINE_CSS' ) ) 		{ define( 'RSHCP_NO_MINLINE_CSS', TRUE ); }		/* LITE */
if( !defined( 'RSHCP_NO_MINLINE_JS' ) ) 		{ define( 'RSHCP_NO_MINLINE_JS', TRUE ); }		/* LITE */
if( !defined( 'RSHCP_JS_DEFER' ) ) 				{ define( 'RSHCP_JS_DEFER', FALSE ); }			/* LITE */
if( !defined( 'RSHCP_JS_TO_FTR' ) ) 			{ define( 'RSHCP_JS_TO_FTR', FALSE ); }			/* LITE */


/*** 
* Adds features, cleans up WP code, and eliminates need for multiple plugins:
*	- Hide WP Generator 				- Security
*	- Removes CSS/JS Versions 			- Security, Speed, Code Validation - Speed: Allows browser to cache JS and CSS files when they don't have arguments appended to URL
*	- Fixes "More" link					- Fixes "More" link so you see the whole post when you click, not just the part after the "more"
*	- Removes Open Sans					- (Optional) Removes the Open Sans from WordPress to speed up your site by removing the call to Google Fonts Library
*	- Remove CF7 JS/CSS					- Removes Contact Form 7 JS/CSS on pages/post where shortcode isn't used (it only needs to be on pages that actually use it)
*	- Combine, Minify & Cache JS/CSS	- Combine all properly registered/queued JS & CSS into one file, minify, and cache these new single files. Fixes CSS image URL locations too. CSS stays in Header, JS will be moved to footer.
*	- HTML								- Removes comments from HTML
*	- Head Cleaner						- Removes the following from the head section for SEO, security, and speed: RSD Link, Windows Live Writer Manifest Link, WordPress Shortlinks, and Adjacent Posts links (Prev/Next)
***/

add_action( 'login_init', 'rshcp_login_init' );

/* CLEANUP HEADER CODE - BEGIN */
remove_action ('wp_head', 'rsd_link');
	/* Remove RSD Link - If you edit blog through browser, then it is not needed. */
	
remove_action( 'wp_head', 'wlwmanifest_link');
	/* Remove Windows Live Writer Manifest Link...similar deal */
	
remove_action( 'wp_head', 'wp_shortlink_wp_head');
	/* Remove WordPress Shortlinks from WP HEAD - WP implements it incorrectly, Bad for SEO, and it adds ugly code */
	
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
	/* Remove WordPress Shortlinks from HTTP Headers */
	
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	/***
	* Remove REL = PREV/NEXT
	* WP incorrectly implements this - supposed to fix pagination issues but it messes up
	* Use All in One SEO Pack - it handles proper implementation of this well, on paginated pages/posts
	***/

remove_action('wp_head', 'wp_generator');
	/* Remove WP Generator/Version - for security reasons */

/* Remove version numbers from CSS and JS in HEAD */
function rshcp_remove_wp_ver_css_js( $src ) {
	if( strpos( $src, 'ver=' ) ) { $src = remove_query_arg( 'ver', $src ); }
	return $src;
}
add_filter( 'style_loader_src', 'rshcp_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'rshcp_remove_wp_ver_css_js', 9999 );

/* Remove HTML Comments and Minify HTML */
function rshcp_remove_html_comments( $buffer ) {
	$rgx	= '~<!--(.|s)*?-->~';
	$func	= 'rshcp_replace_comments';
    $buffer = preg_replace_callback( $rgx, $func, $buffer );
	$buffer = rshcp_simple_minifier_html( $buffer );
    return $buffer;
}
function rshcp_replace_comments( $s ) {
	list( $l ) = $s;
	if( preg_match( "~\!?\[(end)?if~iu", $l ) ) { return $l; }
	return '';
}
function rshcp_buffer_start() {
    ob_start('rshcp_remove_html_comments');
}
function rshcp_buffer_end() {
	ob_end_flush();
}
function rshcp_simple_minifier_html( $html_to_minify, $filter = TRUE ) {
	global $rshcp_slider_active;
	if( is_array( $html_to_minify ) ) { list( $html_to_minify ) = $html_to_minify; }
	if( empty( $filter ) ) { return $html_to_minify; }
	if( TRUE === RSHCP_NO_MINIFY_ALL || TRUE === RSHCP_NO_MINIFY_HTML ) { return $html_to_minify; }
	$html_buffer = $html_to_minify;
	/* Set cookie-free domain for images */
	if( FALSE !== RSHCP_CKF_IMG_DOM ) {
		$rgx_i	= "~['\"]https?\://[a-z0-9\-_\/\.\?\&\=\~\@\%\+\#\:]+\.(gif|jpe?g|png|svg)['\"]~i";
		$func_i	= 'rshcp_replace_ckf_img_dom_urls';
		$html_buffer = preg_replace_callback( $rgx_i, $func_i, $html_buffer );
	}
    $html_buffer = preg_replace( "~\>".RSHCP_EOL."\<~", "><", $html_buffer );
	/* Inline CSS and JS */
    $html_buffer = preg_replace( "~//\ +\<\!\[CDATA\[~", "/* <![CDATA[ */", $html_buffer );
    $html_buffer = preg_replace( "~// +\]\]\>~", "/* ]]> */", $html_buffer );
	$html_buffer = preg_replace( "~[\ \t]+~", ' ', $html_buffer );
	/* Minify Inline CSS */
	if( FALSE === RSHCP_NO_MINLINE_CSS && empty( $rshcp_slider_active ) ) {
		$rgx_c	= "~\<style(?:\s+type\=(?:'|\")text/css(?:'|\")\s*)?\>([^>]+)\</style\>~";
		$func_c	= 'rshcp_html_css_min';
		$html_buffer = preg_replace_callback( $rgx_c, $func_c, $html_buffer );
	}
	/* Minify Inline JS */
	if( FALSE === RSHCP_NO_MINLINE_JS && empty( $rshcp_slider_active ) ) {
		$rgx_j	= "~<script(?:\s+type=(?:'|\")[a-z]+/[a-z]+(?:'|\")\s*)?>\s*(?:/\*\s+\<\!\[CDATA\[\s\*/|//\s+\<\!\[CDATA\[)?([^>]+)(?:/\*\s+\]\]\>\s+\*/|//\s+\]\]\>)?\s*\</script\>~";
		$func_j	= 'rshcp_html_js_min';
		$html_buffer = preg_replace_callback( $rgx_j, $func_j, $html_buffer );
	}
	/* Minify HTML */
	$html_buffer = str_replace( array( '/* <![CDATA[*/', '/*]]> */' ), array( '/* <![CDATA[ */ ', ' /* ]]> */' ), $html_buffer );
    $html_buffer = preg_replace( "~".RSHCP_EOL."\s+".RSHCP_EOL."~", RSHCP_EOL, $html_buffer );
    $html_buffer = preg_replace( "~".RSHCP_EOL."(\t|\ )+~", RSHCP_EOL, $html_buffer );
    $html_buffer = preg_replace( "~(\t|\ )+".RSHCP_EOL."~", RSHCP_EOL, $html_buffer );
    $html_buffer = preg_replace( "~\t+~", "", $html_buffer );
    $html_buffer = preg_replace( "~\ +~", " ", $html_buffer );
    $html_buffer = preg_replace( "~".RSHCP_EOL."{2,}~", RSHCP_EOL, $html_buffer );
    $html_buffer = preg_replace( "~".RSHCP_EOL."+~", "", $html_buffer );
	/* Add more rules - BEGIN */


	/* Add more rules - END */
	$html_minified	= trim( $html_buffer );
	return $html_minified;
    }
add_action('get_header', 'rshcp_buffer_start', 9999);
add_action('wp_footer', 'rshcp_buffer_end', 9999);
/* CLEANUP HEADER CODE - END */

/* IMPROVE USER EXPERIENCE - BEGIN */
/* Change the "Read more" link so it displays the entire post, not just the part after the "#more" */
function rshcp_remove_more($content) {
	global $id;
	return str_replace('#more-'.$id.'"', '"', $content);
}
add_filter('the_content', 'rshcp_remove_more');
/* IMPROVE USER EXPERIENCE - END */

/* SPEED UP WORDPRESS - BEGIN */
/* Add Defer & Async to Scripts */
function rshcp_defer_async_js( $url ) {
	if( is_admin() || is_user_logged_in() || is_404() ) { return $url; } /* Skip if in WP Admin, logged in, or on 404 */
	$slug			= rshcp_get_slug();
	$min_slug		= 'rsm-min-js-'.$slug;
	$min_file_slug	= $min_slug.'.js';
	$js_url			= RSHCP_JS_URL.$min_file_slug;
    if( FALSE === strpos( $url, '.js' ) ) { return $url; } /* Skip non-JS */
    if( FALSE !== strpos( $url, RSHCP_JS_URL ) ) { return $url; }
    if( strpos( $url, 'jquery.js' ) || strpos( $url, '/jquery' ) || strpos( $url, '/masonry' ) || strpos( $url, '/themes/' ) ) { return $url; } /* Skip jquery and theme related JS */
    if( strpos( $url, 'slider' ) ) { global $rshcp_slider_active; $rshcp_slider_active = TRUE; return $url; } /* Skip sliders */
	$new_url = "$url' async='async' defer='defer";
    return $new_url;
}
if( TRUE === RSHCP_JS_DEFER ) {
	add_filter( 'clean_url', 'rshcp_defer_async_js', 9999, 1 );
}
/* Remove Open Sans to Speed Page Loading - Only for Admin area, must change wp-config.php setting */
function rshcp_remove_opensans() {
	if( is_admin() && FALSE !== RSHCP_REMOVE_OPEN_SANS ) {
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', FALSE );
		wp_enqueue_style( 'open-sans', '' );
	}
}
add_action( 'admin_init', 'rshcp_remove_opensans', 9999 );
/* Remove Contact Form 7 JS/CSS on pages/posts where shortcode isn't used */
function rshcp_remove_cf7_css_js() {
	global $post;
	if( is_object( $post ) ) {
		if( ! has_shortcode( $post->post_content, 'contact-form-7' ) ) {
			remove_action('wp_enqueue_scripts', 'wpcf7_enqueue_styles');
			remove_action('wp_enqueue_scripts', 'wpcf7_enqueue_scripts');
		}
	}
}
if( defined( 'WPCF7_VERSION' ) ) {
	add_action( 'wp', 'rshcp_remove_cf7_css_js');
}
/* Combine all JS and CSS, Minify, Cache and Serve one file. CSS stays in Header, JS will be moved to footer. */
if( ! has_action( 'login_enqueue_scripts', 'wp_print_styles' ) ) {
	add_action( 'login_enqueue_scripts', 'wp_print_styles', 11 );
}
function rshcp_html_css_min( $css_str ) {
	/* For inline CSS */
	if( is_array( $css_str ) ) { list( $css_str ) = $css_str; }
	$min_css = rshcp_simple_minifier_css( $css_str, TRUE, TRUE, TRUE );
	return $min_css;
}
function rshcp_html_js_min( $js_str ) {
	/* For inline JS */
	if( is_array( $js_str ) ) { list( $js_str ) = $js_str; }
	$min_js = rshcp_simple_minifier_js( $js_str, TRUE, TRUE, TRUE );
	return $min_js;
}
function rshcp_simple_minifier_css( $css_to_minify, $filter = TRUE, $remcom = TRUE, $inline = FALSE ) {
	if( empty( $filter ) ) { return $css_to_minify; }
	if( TRUE === RSHCP_NO_MINIFY_ALL || TRUE === RSHCP_NO_MINIFY_CSS ) { return $css_to_minify; }
	$css_buffer 	= $css_to_minify;
	/* Replace all newlines with \n ( RSHCP_EOL ) */
	$css_buffer 	= str_replace( array( "\r\n","\r","\n","\f","\v" ), array( RSHCP_EOL,RSHCP_EOL,RSHCP_EOL,RSHCP_EOL,RSHCP_EOL ), $css_buffer );
	/* Remove comments */
	$css_buffer 	= preg_replace( "~(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)~", '', $css_buffer );
	$css_buffer 	= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)[".RSHCP_EOL."\t\ ]*\/\/.*".RSHCP_EOL.")~", RSHCP_EOL, $css_buffer );
	$css_buffer 	= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)([;\{\}]*)\/\/.*".RSHCP_EOL.")~", "$1", $css_buffer );
	/* Trim lines */
	$css_buffer 	= preg_replace( "~(?:\ *".RSHCP_EOL."\ *)~", RSHCP_EOL, $css_buffer );
	/* Remove tabs, spaces, etc. */
	$css_buffer 	= str_replace( array( "\t",'  ','   ','    ','     ' ), '', $css_buffer );
	/* Remove spaces after {},;: */
	$css_buffer 	= str_replace( array( '{ ',' }',' {','} ',', ','; ',' : ',': ' ), array( '{','}','{','}',',',';',':',':' ), $css_buffer );
	/* Remove tabs, spaces, newlines, etc. */
	$css_buffer 	= preg_replace( "~".RSHCP_EOL."{2,}~", RSHCP_EOL, $css_buffer );
	$css_buffer 	= str_replace( array( "{".RSHCP_EOL,RSHCP_EOL."}",RSHCP_EOL."{","}".RSHCP_EOL,",".RSHCP_EOL,";".RSHCP_EOL ), array( '{','}','{','}',',',';' ), $css_buffer );
	/* Add more rules - BEGIN */
	$css_buffer 	= preg_replace( "~\s+,~", ",", $css_buffer );

	/* Add more rules - END */
	$css_minified	= trim( $css_buffer );
	return $css_minified;
    }
function rshcp_simple_minifier_js( $js_to_minify, $filter = TRUE, $remcom = TRUE, $inline = FALSE ) {
	/* $remcom is Remove Comments */
	if( empty( $filter ) ) { return $js_to_minify; }
	if( TRUE === RSHCP_NO_MINIFY_ALL || TRUE === RSHCP_NO_MINIFY_JS ) { return $js_to_minify; }
	$js_buffer		= $js_to_minify;
	/***
	* These aren't all done at once because order of steps is important
	* Replace all newlines with \n ( RSHCP_EOL )
	***/
	$js_buffer 		= str_replace( array( "\r\n","\r","\n","\f","\v" ), array( RSHCP_EOL,RSHCP_EOL,RSHCP_EOL,RSHCP_EOL,RSHCP_EOL ), $js_buffer );
	if( TRUE === $remcom ) {
		/* Remove comments */
		$js_buffer 	= preg_replace( "~(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)~", '', $js_buffer );
		$js_buffer 	= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)[".RSHCP_EOL."\t\ ]*\/\/.*".RSHCP_EOL.")~", RSHCP_EOL, $js_buffer );
		$js_buffer 	= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)([;\{\}]*)\/\/.*".RSHCP_EOL.")~", "$1", $js_buffer );
	}
	/* Trim lines */
	$js_buffer 		= preg_replace( "~(?:\ *".RSHCP_EOL."\ *)~", RSHCP_EOL, $js_buffer );
	$js_buffer 		= str_replace( array( " \\\n", " \\ \n" ), array( '', '' ), $js_buffer );
	/* Remove spaces around JS operators: - + * ? % || && = == != < > <= >= */
	$js_buffer 		= str_replace( array(' - ',' + ',' * ',' / ',' ? ',' % ',' || ',' && ',' = ',' != ',' == ',' === ',' < ',' > ',' <= ',' >= ' ), array( '-','+','*','/','?','%','||','&&','=','!=','==','===','<','>','<=','>=' ), $js_buffer );
	/* Remove tabs, spaces, etc. */
	$js_buffer 		= str_replace( array( "\t",'  ','   ','    ','     ' ), '', $js_buffer );
	/* Remove spaces after {}[](),;: */
	$js_buffer 		= str_replace( array( '{ ',' }',' {','} ','[ ',' ]','( ',' )',' (',') ',', ','; ',' : ',': ' ), array( '{','}','{','}','[',']','(',')','(',')',',',';',':',':' ), $js_buffer );
	/* Remove tabs, spaces, newlines, etc. */
	$js_buffer 		= str_replace( array( " \\\n", " \\ \n" ), array( '', '' ), $js_buffer );
	$js_buffer 		= preg_replace( "~".RSHCP_EOL."{2,}~", RSHCP_EOL, $js_buffer );
	$js_buffer 		= str_replace( array( "{".RSHCP_EOL,RSHCP_EOL."}","[".RSHCP_EOL,RSHCP_EOL."]" ), array( '{','}','[',']' ), $js_buffer );
	$js_buffer 		= str_replace( array( ",".RSHCP_EOL,":".RSHCP_EOL,";".RSHCP_EOL,"&".RSHCP_EOL,"=".RSHCP_EOL,"+".RSHCP_EOL,"-".RSHCP_EOL,"?".RSHCP_EOL,"}\\".RSHCP_EOL," } \\ ".RSHCP_EOL ), array( ',',':',';','&','=','+','-','?','}','}' ), $js_buffer );
	/* Add more rules - BEGIN */


	/* Add more rules - END */
	$js_minified	= trim( $js_buffer );
	return $js_minified;
    }
function rshcp_get_slug() {
	$url = rshcp_get_url();
	return rshcp_md5( $url );
}
add_action('init', 'rshcp_cache_combine_js_css');
function rshcp_cache_combine_js_css() {
	if( !is_admin() && !is_user_logged_in() && !is_404() ) {
		foreach ( array( 'wp_enqueue_scripts', 'login_enqueue_scripts' ) as $a ) { foreach ( array( -999 => 'styles', 9999 => 'scripts' ) as $p => $b ) { add_action( $a, 'rshcp_enqueue_'.$b, $p ); } }
		add_action( 'wp_print_styles', 'rshcp_inspect_styles', 9999 );
		foreach ( array( 'wp_print_scripts', 'wp_print_head_scripts' ) as $a ) { add_action( $a, 'rshcp_inspect_scripts', 9999 ); }
		foreach ( array( 'wp_head', 'login_head' ) as $a ) { add_action( $a, 'rshcp_insert_head_js', 10 ); }
	}
}
function rshcp_enqueue_styles() {
	global $rshcp_css_null,$wp_styles;
	if( !empty( $rshcp_css_null ) ) { return; }
	$slug			= rshcp_get_slug();
	$min_slug		= 'rsm-min-css-'.$slug;
	$min_file_slug	= $min_slug.'.css';
	$css_url		= RSHCP_CSS_URL.$min_file_slug;
	$deps			= array();
	if( is_object( $wp_styles ) ) {
		foreach( $wp_styles->queue as $handle ) {
			$style_deps = (array)$wp_styles->registered[$handle]->deps;
			/* Keep an eye out for potential issues */
			if( !empty( $style_deps ) ) { $deps = array_merge( $deps, $style_deps ); }
		}
		$deps = rshcp_sort_unique( $deps );
		foreach( $wp_styles->queue as $handle ) {
			if( in_array( $handle, $deps, TRUE ) ) {
				$key = array_search( $handle, $deps );
				unset( $deps[$key] );
			}
		}
	}
	if( TRUE === RSHCP_NO_MINIFY_ALL || TRUE === RSHCP_NO_MINIFY_CSS ) { $css_url = str_replace( '-min-', '-raw-', $css_url ); }
	wp_register_style( $min_slug, $css_url, $deps, RSHCP_VERSION );
	wp_enqueue_style( $min_slug );
}
function rshcp_enqueue_scripts() {
	global $rshcp_js_null,$wp_scripts;
	if( TRUE === $rshcp_js_null ) { return; }
	$slug			= rshcp_get_slug();
	$min_slug		= 'rsm-min-js-'.$slug;
	$min_file_slug	= $min_slug.'.js';
	$js_url			= RSHCP_JS_URL.$min_file_slug;
	$deps			= array();
	if( is_object( $wp_scripts ) ) {
		foreach( $wp_scripts->queue as $handle ) {
			$script_deps = (array)$wp_scripts->registered[$handle]->deps;
			/* Keep an eye out for potential issues */
			if( !empty( $script_deps ) ) { $deps = array_merge( $deps, $script_deps ); }
		}
		$deps = rshcp_sort_unique( $deps );
		foreach( $wp_scripts->queue as $handle ) {
			if( in_array( $handle, $deps, TRUE ) ) {
				$key = array_search( $handle, $deps );
				unset( $deps[$key] );
			}
		}
	}
	if( TRUE === RSHCP_NO_MINIFY_ALL || TRUE === RSHCP_NO_MINIFY_JS ) { $js_url = str_replace( '-min-', '-raw-', $js_url ); }
	wp_register_script( $min_slug, $js_url, $deps, RSHCP_VERSION, RSHCP_JS_TO_FTR );
	wp_enqueue_script( $min_slug );
}
function rshcp_insert_head_js() {
	global $rshcp_js_h_null;
	if( TRUE === $rshcp_js_h_null || FALSE === RSHCP_JS_TO_FTR ) { return; }
	if( !is_admin() && !is_user_logged_in() && !is_404() ) {
		$slug			= rshcp_get_slug();
		$min_slug		= 'rsm-min-js-h'.$slug;
		$min_file_slug	= $min_slug.'.js';
		$js_h_url		= RSHCP_JS_URL.$min_file_slug;
		if( TRUE === RSHCP_NO_MINIFY_ALL || TRUE === RSHCP_NO_MINIFY_JS ) { $js_h_url = str_replace( '-min-', '-raw-', $js_h_url ); }
		echo RSHCP_EOL."<script type='text/javascript' src='".$js_h_url."'></script>".RSHCP_EOL;
	}
}
function rshcp_inspect_scripts( $head_scripts = NULL ) {
	global $rshcp_js_run,$wp_scripts,$rshcp_js_h_null,$rshcp_js_null,$rshcp_vfile,$vfile_set;
	if( !empty( $rshcp_js_run ) ) { return; }
	$slug 	= rshcp_get_slug();
	$url 	= rshcp_get_url();
	$domain	= rshcp_get_domain( $url );
	$h		= !empty( $head_scripts ) ? 'h' : '';
	$raw_slug = 'rsm-raw-js-'.$h.$slug;
	$min_slug = 'rsm-min-js-'.$h.$slug;
	$raw_file_slug = $raw_slug.'.js';
	$min_file_slug = $min_slug.'.js';
	$raw_js_file = RSHCP_JS_PATH.$raw_file_slug;
	$min_js_file = RSHCP_JS_PATH.$min_file_slug;
	$script_handles = array();
	$script_srcs 	= array();
	/*$deps 		= array(); // TO DO*/
	$combined_js 	= array();
	$http_pref		= rshcp_is_ssl() ? 'https://' : 'http://';
	if( empty( $head_scripts ) && defined( 'WPCF7_VERSION' ) && wp_script_is( 'jquery-form', 'enqueued' ) ) {
		$handle				= 'jquery-form';
		$script_src			= $script_src_path = WPCF7_PLUGIN_URL.'/includes/js/jquery.form.min.js';
		$script_domain		= $domain;
		$script_src_rev		= rshcp_fix_url( $script_src, TRUE, TRUE, TRUE );
		if( strpos( $script_src_path, '//' ) === 0 ) { $script_src_path = str_replace( '//', $http_pref, $script_src_path ); }
		$script_src_path	= str_replace( array( RSHCP_CONTENT_DIR_URL, RSHCP_INCL_DIR_URL, RSHCP_SITE_URL ), array( RSHCP_CONTENT_DIR_PATH, RSHCP_INCL_DIR_PATH, RSHCP_SITE_PATH ), $script_src_path );
		$js_buffer			= file_get_contents( $script_src_path );
		if( !empty( $js_buffer ) ) {
			$c = '';
			$_wpcf7 = array( 'loaderUrl' => wpcf7_ajax_loader(), 'sending' => __( 'Sending ...', 'contact-form-7' ) ); 
			if( defined( 'WP_CACHE' ) && WP_CACHE ) { $_wpcf7['cached'] = 1; $c = ',"cached":"1"'; }
			if( wpcf7_support_html5_fallback() ) { $_wpcf7['jqueryUi'] = 1; }
			$js_buffer			.= RSHCP_EOL.'var _wpcf7 = {"loaderUrl":"'.str_replace( '/', '\/', WPCF7_PLUGIN_URL ).'\/images\/ajax-loader.gif","sending":"'.__( 'Sending ...', 'contact-form-7' ).'"'.$c.'};'.RSHCP_EOL;
			$script_handles[]	 = $handle;
			$script_srcs[]		 = $script_src;
			$combined_js[] 		 = $js_buffer;
		}
		unset ( $js_buffer );
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
		wp_register_script( $handle, NULL, FALSE, NULL, TRUE);
		wp_enqueue_script( $handle );
	}
	if( !empty( $head_scripts ) ) {
		foreach( $head_scripts as $handle => $a ) {
			$script_src			= $script_src_path = $a['src'];
			$script_domain		= rshcp_get_domain( $script_src );
			if( empty( $script_src ) || $handle == $min_slug || $script_domain != $domain ) { continue; }
			$script_src_rev		= rshcp_fix_url( $script_src, TRUE, TRUE, TRUE );
			if( strpos( $script_src_rev, 'sj.' ) !== 0 ) { continue; } /* Not JS */
			if( strpos( $script_src_path, '//' ) === 0 ) { $script_src_path = str_replace( '//', $http_pref, $script_src_path ); }
			$script_src_path	= str_replace( array( RSHCP_CONTENT_DIR_URL, RSHCP_INCL_DIR_URL, RSHCP_SITE_URL ), array( RSHCP_CONTENT_DIR_PATH, RSHCP_INCL_DIR_PATH, RSHCP_SITE_PATH ), $script_src_path );
			$script_src_path_ex	= str_replace( RSHCP_CONTENT_DIR_PATH, '', $script_src_path );
			if( FALSE !== strpos( $script_src_path_ex, 'slider' ) ) { global $rshcp_slider_active; $rshcp_slider_active = TRUE; continue; } /* Slider scripts/styles usually cannot be minified */
			$js_buffer			= file_get_contents( $script_src_path );
			if( empty( $js_buffer ) ) { continue; }
			$script_handles[] 	= $handle;
			$script_srcs[] 		= $script_src;
			$combined_js[] 		= $js_buffer;
			unset ( $js_buffer );
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}
		if( !empty( $combined_js ) ) {
			$rshcp_js_h_null = FALSE;
			unset( $head_scripts );
			remove_action( 'rshcp_inspect_scripts_head', 'rshcp_inspect_scripts' );
		}
	}
	elseif( !empty( $wp_scripts ) && is_object( $wp_scripts ) ) {
		$rshcp_js_h_null = TRUE;
		$head_scripts = array();
		foreach( $wp_scripts->queue as $handle ) {
			$script_src			= $script_src_path = $wp_scripts->registered[$handle]->src;
			/*$script_deps 		= (array)$wp_scripts->registered[$handle]->deps; // TO DO*/
			$script_domain		= rshcp_get_domain( $script_src );
			if( empty( $script_src ) || $handle == $min_slug || $script_domain != $domain ) { continue; }
			$script_src_rev		= rshcp_fix_url( $script_src, TRUE, TRUE, TRUE );
			if( strpos( $script_src_rev, 'sj.' ) !== 0 ) { continue; } /* Not JS */
			if( strpos( $script_src_path, '//' ) === 0 ) { $script_src_path = str_replace( '//', $http_pref, $script_src_path ); }
			$script_src_path	= str_replace( array( RSHCP_CONTENT_DIR_URL, RSHCP_INCL_DIR_URL, RSHCP_SITE_URL ), array( RSHCP_CONTENT_DIR_PATH, RSHCP_INCL_DIR_PATH, RSHCP_SITE_PATH ), $script_src_path );
			$script_src_path_ex	= str_replace( RSHCP_CONTENT_DIR_PATH, '', $script_src_path );
			if( FALSE !== strpos( $script_src_path_ex, 'slider' ) ) { global $rshcp_slider_active; $rshcp_slider_active = TRUE; continue; } /* Slider scripts/styles usually cannot be minified */
			$js_buffer			= file_get_contents( $script_src_path );
			if( empty( $js_buffer ) ) { continue; }
			/***
			* Helps with compatibility when async and defer are being used
			* $js_buffer			= str_replace( array( '(function($)', '( function( $ )', 'jQuery(document).readyjQuery(document).ready(function($)' ), 'jQuery(document).ready(function($)', $js_buffer );
			* $js_buffer			= str_replace( array( '(jQuery)', '( jQuery )', '(jquery)', '( jquery )' ), '', $js_buffer );
			***/
			$script_handles[] 	= $handle;
			$script_srcs[] 		= $script_src;
			$combined_js[] 		= $js_buffer;
			unset ( $js_buffer );
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}
		if( !rshcp_is_login_page() && wp_script_is( 'jquery', 'enqueued' ) ) {
			$hs_rev = array_reverse( $head_scripts );
			$hs_rev['jquery-migrate'] = array( 'handle' => 'jquery-migrate', 'src' => RSHCP_SITE_URL.'/wp-includes/js/jquery/jquery-migrate.min.js' );
			$hs_rev['jquery-core'] = array( 'handle' => 'jquery-core', 'src' => RSHCP_SITE_URL.'/wp-includes/js/jquery/jquery.js' );
			$head_scripts = array_reverse( $hs_rev );
			wp_dequeue_script( 'jquery' );
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', NULL, FALSE, NULL, TRUE);
			wp_enqueue_script( 'jquery' );
		}
		if( !empty( $head_scripts ) ) { add_action( 'rshcp_inspect_scripts_head', 'rshcp_inspect_scripts', 1 ); }
	} else { $rshcp_js_null = TRUE; return; }
	$combined_js_contents_raw	= implode( RSHCP_EOL, $combined_js );
	if( FALSE === RSHCP_JS_TO_FTR && !empty( $rshcp_vfile ) ) {
		$combined_js_contents_raw = $combined_js_contents_raw.RSHCP_EOL.$rshcp_vfile;
		unset( $rshcp_vfile ); $vfile_set = TRUE;
		if( 47 == rshcp_strlen( basename( $raw_js_file ) ) ) {
			$raw_js_file = str_replace( '/rsm-raw-js-h', '/rsm-raw-js-', $raw_js_file );
			$min_js_file = str_replace( '/rsm-min-js-h', '/rsm-min-js-', $min_js_file );
		}
	}
	$combined_js_contents_len	= rshcp_strlen( $combined_js_contents_raw );
	$combined_js_contents		= rshcp_simple_minifier_js( $combined_js_contents_raw );
	$plugin_file_mod_time		= filemtime( __FILE__ );
	if( file_exists( $raw_js_file ) ) {
		$raw_js_file_mod_time	= filemtime( $raw_js_file );
		$raw_js_file_filesize	= filesize( $raw_js_file );
	}
	else {
		$raw_js_file_mod_time	= FALSE;
		$raw_js_file_filesize	= FALSE;
	}
	$js_cache_time = time() - 86400; /* 60 * 60 * 1 - Sec * Min * Hour; 3600 = 1 Hour; 86400 = 24 Hours; */
	if( !empty( $combined_js_contents_len ) ) {
		if( $raw_js_file_filesize != $combined_js_contents_len || $raw_js_file_mod_time < $plugin_file_mod_time || $raw_js_file_mod_time < $js_cache_time || FALSE === $raw_js_file_mod_time ) {
			rshcp_write_cache_file( $raw_js_file, $combined_js_contents_raw );
			rshcp_write_cache_file( $min_js_file, $combined_js_contents );
		}
	}
	else { $rshcp_js_null = TRUE; }
	if( !empty( $head_scripts ) ) { do_action( 'rshcp_inspect_scripts_head', $head_scripts ); }
	$rshcp_js_run = TRUE;
}
function rshcp_inspect_styles() {
	global $rshcp_css_run,$domain,$wp_styles,$new_url_base,$url_buffer,$rshcp_css_null;
	if( !empty( $rshcp_css_run ) ) { return; }
	$slug			= rshcp_get_slug();
	$url			= rshcp_get_url();
	$domain			= rshcp_get_domain( $url );
	$raw_slug		= 'rsm-raw-css-'.$slug;
	$min_slug		= 'rsm-min-css-'.$slug;
	$raw_file_slug	= $raw_slug.'.css';
	$min_file_slug	= $min_slug.'.css';
	$raw_css_file	= RSHCP_CSS_PATH.$raw_file_slug;
	$min_css_file	= RSHCP_CSS_PATH.$min_file_slug;
	$style_handles 	= array();
	$style_srcs 	= array();
	/*$deps 		= array(); // TO DO*/
	$combined_css 	= array();
	$http_pref		= rshcp_is_ssl() ? 'https://' : 'http://';
	if( is_object( $wp_styles ) ) {
		foreach( $wp_styles->queue as $handle ) {
			$style_src			= $style_src_path = $wp_styles->registered[$handle]->src;
			/*$style_deps 		= (array)$wp_styles->registered[$handle]->deps; // TO DO*/
			$style_domain		= rshcp_get_domain( $style_src );
			if( empty( $style_src ) || $handle == $min_slug || $style_domain != $domain ) { continue; }
			$style_src_rev		= rshcp_fix_url( $style_src, TRUE, TRUE, TRUE );
			if( strpos( $style_src_rev, 'ssc.' ) !== 0 ) { continue; } /* Not CSS */
			$handle_rgx			= preg_quote( $handle );
			if( strpos( $style_src_path, '//' ) === 0 ) { $style_src_path = str_replace( '//', $http_pref, $style_src_path ); }
			$style_src_path	= str_replace( array( RSHCP_CONTENT_DIR_URL, RSHCP_INCL_DIR_URL, RSHCP_SITE_URL ), array( RSHCP_CONTENT_DIR_PATH, RSHCP_INCL_DIR_PATH, RSHCP_SITE_PATH ), $style_src_path );
			$css_buffer 		= file_get_contents( $style_src_path );
			if( empty( $css_buffer ) ) { continue; }
			$style_handles[] 	= $handle;
			$style_srcs[] 		= $style_src;
			$style_src_no_http	= str_replace( array( 'https://', 'http://' ), '', $style_src );
			$url_buffer 		= explode( '/', $style_src_no_http );
			$url_elements		= count( $url_buffer ) - 1;
			unset( $url_buffer[$url_elements] );
			--$url_elements;
			if( preg_match_all( "~(url\('?(?:\.?/)?([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\))~i", $css_buffer, $matches ) ) {
				$new_url_base = implode( '/', $url_buffer );
				$css_buffer = preg_replace_callback( "~url\('?\.?/?([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\)~i", 'rshcp_replace_css_urls', $css_buffer );
			}
			if( preg_match_all( "~(url\('?(?:\.\./)+(?:[a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\))~i", $css_buffer, $matches ) ) {
				foreach( $matches[1] as $m => $match ) {
					$url_buffer_m = $url_buffer;
					/* Number of directories down */
					$num_dirs_down	= substr_count( $match, '../' );
					/* URL Elements Reduced */
					$url_elements_red = $url_elements - $num_dirs_down;
					/* Removing last element(s) of array is how we go down one or more directories */
					$i = $url_elements;
					while( $i > $url_elements_red ) { unset( $url_buffer_m[$i] ); $i--; }
					$new_url_base 	= implode( '/', $url_buffer_m );
					if( FALSE !== RSHCP_CKF_IMG_DOM && preg_match( "~\.(gif|jpe?g|png|svg)(#[a-z0-9]+)?~", $match ) ) {
						$new_url_base = str_replace( $domain, RSHCP_CKF_IMG_DOM, $new_url_base );
					}
					$m_buffer 		= $match;
					$m_buffer 		= preg_replace( "~url\('?(?:\.\./)+([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\)~i", "url('".'//'.$new_url_base."/$1')", $m_buffer );
					$match_rgx 		= preg_quote( $match );
					$css_buffer 	= preg_replace( "~$match_rgx~i", $m_buffer, $css_buffer, -1, $count );
				}
			}
			$combined_css[] = $css_buffer;
			unset ( $css_buffer );
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}
	} else { return; }
	$combined_css_contents_raw	= implode( RSHCP_EOL, $combined_css );
	$combined_css_contents_len	= rshcp_strlen( $combined_css_contents_raw );
	$combined_css_contents		= rshcp_simple_minifier_css( $combined_css_contents_raw );
	$plugin_file_mod_time		= filemtime( __FILE__ );
	if( file_exists( $raw_css_file ) ) {
		$raw_css_file_mod_time	= filemtime( $raw_css_file );
		$raw_css_file_filesize	= filesize( $raw_css_file );
	}
	else {
		$raw_css_file_mod_time	= FALSE;
		$raw_css_file_filesize	= FALSE;
	}
	$css_cache_time = time() - 86400; /* 60 * 60 * 1 - Sec * Min * Hour; 3600 = 1 Hour; 86400 = 24 Hours; */
	if( !empty( $combined_css_contents_len ) ) {
		if( $raw_css_file_filesize != $combined_css_contents_len || $raw_css_file_mod_time < $plugin_file_mod_time || $raw_css_file_mod_time < $css_cache_time || FALSE === $raw_css_file_mod_time ) {
			rshcp_write_cache_file( $raw_css_file, $combined_css_contents_raw );
			rshcp_write_cache_file( $min_css_file, $combined_css_contents );
		}
	}
	else { $rshcp_css_null = TRUE; }
	$rshcp_css_run = TRUE;
}
function rshcp_replace_css_urls( $s ) {
	global $domain,$new_url_base;
	list( $m ) = $s;
	if( FALSE !== RSHCP_CKF_IMG_DOM && preg_match( "~\.(gif|jpe?g|png|svg)(#[a-z0-9]+)?~", $m ) ) {
		$new_url_base = str_replace( $domain, RSHCP_CKF_IMG_DOM, $new_url_base );
	}
	return preg_replace( "~url\('?\.?/?([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\)~i", "url('".'//'.$new_url_base."/$1')", $m );
}
function rshcp_replace_ckf_img_dom_urls( $s ) {
	$url		= rshcp_get_url();
	$domain		= rshcp_get_domain( $url );
	list( $m )	= $s;
	return str_replace( $domain, RSHCP_CKF_IMG_DOM, $m );
}
/* SPEED UP WORDPRESS - END */

/* Standard Functions - BEGIN */
function rshcp_eol() {
	global $is_IIS;
	return !empty( $is_IIS ) ? "\r\n" : "\n";
}
function rshcp_ds() {
	global $is_IIS;
	return !empty( $is_IIS ) ? '\\' : '/';
}
function rshcp_strlen( $string ) {
	/***
	* Use this function instead of mb_strlen because some servers (often IIS) have mb_ functions disabled by default
	* BUT mb_strlen is superior to strlen, so use it whenever possible
	***/
	return function_exists( 'mb_strlen' ) ? mb_strlen( $string, 'UTF-8' ) : strlen( $string );
}
function rshcp_casetrans( $type, $string ) {
	/***
	* Convert case using multibyte version if available, if not, use defaults
	* Added 1.8.4
	***/
	switch($type) {
		case 'upper':
			return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $string, 'UTF-8' ) : strtoupper( $string );
		case 'lower':
			return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string, 'UTF-8' ) : strtolower( $string );
		case 'ucfirst':
			if( function_exists( 'mb_strtoupper' ) && function_exists( 'mb_substr' ) ) {
				$strtmp = mb_strtoupper( mb_substr( $string, 0, 1, 'UTF-8' ), 'UTF-8' ) . mb_substr( $string, 1, NULL, 'UTF-8' );
				return rshcp_strlen( $string ) === rshcp_strlen( $strtmp ) ? $strtmp : ucfirst( $string );
			}
			else { return ucfirst( $string ); }
		case 'ucwords':
			return function_exists( 'mb_convert_case' ) ? mb_convert_case( $string, MB_CASE_TITLE, 'UTF-8' ) : ucwords( $string );
			/***
			* Note differences in results between ucwords() and this. 
			* ucwords() will capitalize first characters without altering other characters, whereas this will lowercase everything, but capitalize the first character of each word.
			* This works better for our purposes, but be aware of differences.
			***/
		default:
			return $string;
	}
}
function rshcp_get_domain( $url ) {
	/* Get domain from URL */
	/* Filter URLs with nothing after http */
	if( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	/* Fix poorly formed URLs so as not to throw errors when parsing */
	$url = rshcp_fix_url( $url );
	/* NOW start parsing */
	$parsed = parse_url($url);
	/* Filter URLs with no domain */
	if( empty( $parsed['host'] ) ) { return ''; }
	return rshcp_casetrans( 'lower', $parsed['host'] );
}
function rshcp_get_url() {
	$url  = rshcp_is_ssl() ? 'https://' : 'http://';
	$url .= RSHCP_SERVER_NAME.$_SERVER['REQUEST_URI'];
	return $url;
}
function rshcp_fix_url( $url, $rem_frag = FALSE, $rem_query = FALSE, $rev = FALSE ) {
	/* Fix poorly formed URLs so as not to throw errors or cause problems */
	/* Too many forward slashes or colons after http */
	$url = preg_replace( "~^(https?)\:+/+~i", "$1://", $url );
	/* Too many dots */
	$url = preg_replace( "~\.+~i", ".", $url );
	/* Too many slashes after the domain */
	$url = preg_replace( "~([a-z0-9]+)/+([a-z0-9]+)~i", "$1/$2", $url );
	/* Remove fragments */
	if( !empty( $rem_frag ) && strpos( $url, '#' ) !== FALSE ) { $url_arr = explode( '#', $url ); $url = $url_arr[0]; }
	/* Remove query string completely */
	if( !empty( $rem_query ) && strpos( $url, '?' ) !== FALSE ) { $url_arr = explode( '?', $url ); $url = $url_arr[0]; }
	/* Reverse */
	if( !empty( $rev ) ) { $url = strrev($url); }
	return $url;
}
function rshcp_get_query_string( $url ) {
	/***
	* Get query string from URL
	* Filter URLs with nothing after http
	***/
	if( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	/* Fix poorly formed URLs so as not to throw errors when parsing */
	$url = rshcp_fix_url( $url );
	/* NOW start parsing */
	$parsed = @parse_url($url);
	/* Filter URLs with no query string */
	if( empty( $parsed['query'] ) ) { return ''; }
	$query_str = $parsed['query'];
	return $query_str;
}
function rshcp_get_query_args( $url ) {
	/***
	* Get query string array from URL
	***/
	if( empty( $url ) ) { return array(); }
	$query_str = rshcp_get_query_string( $url );
	parse_str( $query_str, $args );
	return $args;
}
function rshcp_is_ssl() {
	if( !empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] ) { return TRUE; }
	if( !empty( $_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) { return TRUE; }
	if( !empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) { return TRUE; }
	if( !empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && 'off' !== $_SERVER['HTTP_X_FORWARDED_SSL'] ) { return TRUE; }
	return FALSE;
}
function rshcp_get_server_addr() {
	$server_addr = !empty( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : $_ENV['SERVER_ADDR'];
	if( empty( $server_addr ) ) { $server_addr = ''; }
	return $server_addr;
}
function rshcp_get_server_name() {
	$server_name = '';
	if(		!empty( $_SERVER['HTTP_HOST'] ) )	{ $server_name = $_SERVER['HTTP_HOST']; }
	elseif(	!empty( $_ENV['HTTP_HOST'] ) )		{ $server_name = $_ENV['HTTP_HOST']; }
	elseif(	!empty( $_SERVER['SERVER_NAME'] ) )	{ $server_name = $_SERVER['SERVER_NAME']; }
	elseif(	!empty( $_ENV['SERVER_NAME'] ) )	{ $server_name = $_ENV['SERVER_NAME']; }
	return rshcp_casetrans( 'lower', $server_name );
}
function rshcp_doc_txt() {
	return __( 'Documentation', RSHCP_PLUGIN_NAME );
}
function rshcp_scandir( $dir ) {
	clearstatcache();
	$dot_files = array( '..', '.' );
	$dir_contents_raw = scandir( $dir );
	$dir_contents = array_values( array_diff( $dir_contents_raw, $dot_files ) );
	return $dir_contents;
}
function rshcp_append_log_data( $str = NULL, $rsds_only = FALSE ) {
	/***
	* Adds data to the log for debugging - only use when Debugging - Use with WP_DEBUG & RSHCP_DEBUG
	* Example:
	* rshcp_append_log_data( RSHCP_EOL.'$rshcp_example_variable: "'.$rshcp_example_variable.'" Line: '.__LINE__.' | '.__FUNCTION__.' | MEM USED: ' . rshcp_wp_memory_used(), TRUE );
	* rshcp_append_log_data( RSHCP_EOL.'$rshcp_example_variable: "'.$rshcp_example_variable.'" Line: '.__LINE__.' | '.__FUNCTION__.' | '.rshcp_get_url().' | MEM USED: ' . rshcp_wp_memory_used(), TRUE );
	* rshcp_append_log_data( RSHCP_EOL.'[A]$rshcp_example_array_var: "'.serialize($rshcp_example_array_var).'" Line: '.__LINE__.' | '.__FUNCTION__.' | MEM USED: ' . rshcp_wp_memory_used(), TRUE );
	***/
	if( TRUE === WP_DEBUG && TRUE === RSHCP_DEBUG ) {
		if( !empty( $rsds_only ) && strpos( RSHCP_SERVER_NAME_REV, RSHCP_DEBUG_SERVER_NAME_REV ) !== 0 ) { return; }
		$rshcp_log_str = 'RSHCP DEBUG: ['.$_SERVER['REMOTE_ADDR'].'] '.str_replace(RSHCP_EOL, "", $str);
		error_log( $rshcp_log_str, 0 ); /* Logs to debug.log */
	}
}
function rshcp_microtime() {
	return microtime( TRUE );
}
function rshcp_timer( $start = NULL, $end = NULL, $show_seconds = FALSE, $precision = 8, $no_format = FALSE, $raw = FALSE ) {
	/***
	* $precision will default to 8 but can be set to anything - 1,2,3,4,5,6,etc.
	* Use $no_format when clean numbers are needed for calculations. International formatting throws a wrench into things.
	***/
	if( empty( $start ) || empty( $end ) ) { $start = $end = 0; }
	$total_time = $end - $start;
	if( empty( $no_format ) ) {
		$total_time_for = rshcp_number_format( $total_time, $precision );
		if( !empty( $show_seconds ) ) { $total_time_for .= ' seconds'; }
	}
	elseif( empty( $raw ) ) {
		$total_time_for = number_format( $total_time, $precision );
	} 
	else { $total_time_for = $total_time; }
	return $total_time_for;
}
function rshcp_number_format( $number, $precision = NULL ) {
	/* $precision will default to NULL but can be set to anything - 1,2,3,4,5,6,etc. */
	if( function_exists( 'number_format_i18n' ) ) { $number_for = number_format_i18n( $number, $precision ); }
	else { $number_for = number_format( $number, $precision ); }
	return $number_for;
}
function rshcp_format_bytes( $size, $precision = 2 ) {
	if( !is_numeric($size) ) { return $size; }
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');
	$formatted_num = round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    return $formatted_num;
}
function rshcp_wp_memory_used() {
    return function_exists( 'memory_get_usage' ) ? rshcp_format_bytes( memory_get_usage() ) : 0;
}
function rshcp_date_diff( $start, $end ) {
	$start_ts = strtotime($start);
	$end_ts = strtotime($end);
	$diff = ($end_ts-$start_ts);
	$start_array = explode('-', $start);
	$start_year = $start_array[0];
	$end_array = explode('-', $end);
	$end_year = $end_array[0];
	$years = $end_year-$start_year;
	if(($years%4) == 0) { $extra_days = ((($end_year-$start_year)/4)-1); } else { $extra_days = ((($end_year-$start_year)/4)); }
	$extra_days = round($extra_days);
	return round($diff/86400)+$extra_days;
}
function rshcp_sort_unique($arr) {
	$arr_tmp = array_unique($arr); natcasesort($arr_tmp); $new_arr = array_values($arr_tmp);
	return $new_arr;
}
function rshcp_md5( $string ) {
	/***
	* Use this function instead of hash for compatibility
	* BUT hash is faster than md5, so use it whenever possible
	***/
	return function_exists( 'hash' ) ? hash( 'md5', $string ) : md5( $string );
}
function rshcp_login_init() {
	global $rshcp_is_login_page; $rshcp_is_login_page = TRUE;
}
function rshcp_is_login_page() {
	global $pagenow, $rshcp_is_login_page;
    if( $pagenow === 'wp-login.php' || $pagenow === 'wp-register.php' || !empty( $rshcp_is_login_page ) ) { return TRUE; }
    if( strpos( $_SERVER['PHP_SELF'], '/wp-login.php' ) !== FALSE || strpos( $_SERVER['PHP_SELF'], '/wp-register.php' ) !== FALSE ) { return TRUE; }
    return FALSE;
}
function rshcp_is_user_admin() {
	global $rshcp_user_can_manage_options;
	if( empty( $rshcp_user_can_manage_options ) ) { $rshcp_user_can_manage_options = current_user_can( 'manage_options' ) ? 'YES' : 'NO'; }
	if( $rshcp_user_can_manage_options === 'YES' ) { return TRUE; }
	return FALSE;
}
/* Standard Functions - END */

/* Admin Functions - BEGIN */
register_activation_hook( __FILE__, 'rshcp_activation' );
function rshcp_activation() {
	global $rshcp_options;
	$rshcp_options = get_option( 'rshcp_options' );
	$installed_ver = !empty( $rshcp_options['version'] ) ? $rshcp_options['version'] : '';
	rshcp_upgrade_check( $installed_ver, TRUE );
	rshcp_mk_cache_dir();
}
function rshcp_upgrade_check( $installed_ver = NULL ) {
	global $rshcp_options;
	$rshcp_options = !empty( $rshcp_options ) ? $rshcp_options : get_option( 'rshcp_options' );
	$installed_ver = !empty( $rshcp_options['version'] ) ? $rshcp_options['version'] : '';
	if( $installed_ver !== RSHCP_VERSION ) {
		if( empty( $rshcp_options ) || !is_array( $rshcp_options ) ) { $rshcp_options = array(); }
		$rshcp_options['version'] = RSHCP_VERSION;
		if( empty( $rshcp_options['install_date'] ) ) { $rshcp_options['install_date'] = date('Y-m-d'); }
		update_option('rshcp_options', $rshcp_options);
		rshcp_mk_cache_dir();
	}
}
function rshcp_mk_cache_dir( $i = 1 ) {
	$k						= $i-1;
	$d_perm					= array( 0755, 0775, 0775, 0775, );
	$f_perm					= array( 0644, 0664, 0664, 0664, );
	$rshcp_js_dir			= RSHCP_JS_PATH;
	$rshcp_css_dir			= RSHCP_CSS_PATH;
	$rshcp_index_file		= RSHCP_PLUGIN_PATH.'index.php';
	$rshcp_htaccess_file	= RSHCP_PLUGIN_PATH.'lib/.htaccess';
	if( !file_exists( $rshcp_js_dir ) ) {
		wp_mkdir_p( $rshcp_js_dir );
		@chmod( $rshcp_js_dir, $d_perm[$k] );
		@copy ( $rshcp_index_file, $rshcp_js_dir.'index.php' );
	}
	if( !file_exists( $rshcp_css_dir ) ) {
		wp_mkdir_p( $rshcp_css_dir );
		@chmod( $rshcp_css_dir, $d_perm[$k] );
		@copy ( $rshcp_index_file, $rshcp_css_dir.'index.php' );
	}
	@copy ( $rshcp_index_file, RSHCP_CACHE_PATH.'index.php' );
	@copy ( $rshcp_htaccess_file, RSHCP_CACHE_PATH.'.htaccess' );
}
function rshcp_write_cache_file( $file, $contents, $i = 0 ) {
	/***
	* This function ensures that the cache file is written.
	* file_put_contents() just throws a PHP Warning if a folder is missing, but this will attempt to create the folder and write the file up to 3 times.
	***/
	global $vfile_set;
	$filename = basename( $file );
	if( empty( $vfile_set ) && FALSE === RSHCP_JS_TO_FTR && 46 == rshcp_strlen( $filename ) ) {
		if( strpos( $filename, 'rsm-raw-js-' ) === 0 ) {
			rshcp_vfile_put_contents( $file, $contents );
			return;
		}
		elseif( strpos( $filename, 'rsm-min-js-' ) === 0 ) {
			return;
		}
	}
	$i++; $m = 4;
	if( $i === $m ) {
		clearstatcache();
		if( file_exists( $file ) ) {
			@chmod( $file, 0775 );
			@unlink( $file );
		}
	}
	if( $i <= $m && FALSE === file_put_contents( $file, $contents ) ) {
		rshcp_mk_cache_dir($i);
		rshcp_write_cache_file( $file, $contents, $i );
	}
	@chmod( $file, 0644 );
}
function rshcp_vfile_put_contents( $file, $contents ) {
	global $rshcp_vfile;
	$rshcp_vfile = $contents;
}
add_action( 'admin_init', 'rshcp_check_version' );
function rshcp_check_version() {
	if( current_user_can( 'manage_network' ) ) {
		/* Check for pending admin notices */
		$admin_notices = get_option('rshcp_admin_notices');
		if( !empty( $admin_notices ) ) { add_action( 'network_admin_notices', 'rshcp_admin_notices' ); }
		/* Make sure not network activated */
		if( is_plugin_active_for_network( RSHCP_PLUGIN_BASENAME ) ) {
			deactivate_plugins( RSHCP_PLUGIN_BASENAME, TRUE, TRUE );
			$notice_text = __( 'Plugin deactivated. RS Head Cleaner Lite is not available for network activation.', RSHCP_PLUGIN_NAME );
			$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
			update_option( 'rshcp_admin_notices', $new_admin_notice );
			add_action( 'network_admin_notices', 'rshcp_admin_notices' );
			return FALSE;
		}
	}
	if( current_user_can('manage_options') ) {
		/* Check if plugin has been upgraded */
		rshcp_upgrade_check();
		/* Check for pending admin notices */
		$admin_notices = get_option('rshcp_admin_notices');
		if( !empty( $admin_notices ) ) { add_action( 'admin_notices', 'rshcp_admin_notices' ); }
		/* Make sure user has minimum required WordPress version, in order to prevent issues */
		$rshcp_wp_version = RSHCP_WP_VERSION;
		if( version_compare( $rshcp_wp_version, RSHCP_REQUIRED_WP_VERSION, '<' ) ) {
			deactivate_plugins( RSHCP_PLUGIN_BASENAME );
			$notice_text = sprintf( __( 'Plugin deactivated. WordPress Version %s required. Please upgrade WordPress to the latest version.', RSHCP_PLUGIN_NAME ), RSHCP_REQUIRED_WP_VERSION );
			$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
			update_option( 'rshcp_admin_notices', $new_admin_notice );
			add_action( 'admin_notices', 'rshcp_admin_notices' );
			return FALSE;
		}
		/* Make sure user has minimum required PHP version, in order to prevent issues */
		$rshcp_php_version = RSHCP_PHP_VERSION;
		if( !empty( $rshcp_php_version ) && version_compare( RSHCP_PHP_VERSION, RSHCP_REQUIRED_PHP_VERSION, '<' ) ) {
			deactivate_plugins( RSHCP_PLUGIN_BASENAME );
			$notice_text = sprintf( __( '<p>Plugin deactivated. <strong>Your server is running PHP version %3$s, but RS Head Cleaner Lite requires at least PHP %1$s.</strong> We are no longer supporting PHP 5.2, as it has not been supported by the PHP team <a href=%2$s>since 2011</a>, and there are known security, performance, and compatibility issues.</p><p>The version of PHP running on your server is <em>extremely out of date</em>. You should upgrade your PHP version as soon as possible.</p><p>If you need help with this, please contact your web hosting company and ask them to switch your PHP version to 5.4 or 5.5. Please see the <a href=%4$s>plugin documentation</a> if you have further questions.</p>', RSHCP_PLUGIN_NAME ), RSHCP_REQUIRED_PHP_VERSION, '"http://php.net/archive/2011.php#id2011-08-23-1" target="_blank" rel="external" ', $rshcp_php_version, '"'.RSHCP_HOME_URL.'?src='.RSHCP_VERSION.'-php-notice#rshc_requirements" target="_blank" rel="external" ' ); /* NEEDS TRANSLATION - Added 1.4.0 */
			$new_admin_notice = array( 'style' => 'error', 'notice' => $notice_text );
			update_option( 'rshcp_admin_notices', $new_admin_notice );
			add_action( 'admin_notices', 'rshcp_admin_notices' );
			return FALSE;
		}
		rshcp_check_nag_notices();
	}
}
function rshcp_admin_notices() {
	$admin_notices = get_option('rshcp_admin_notices');
	if( !empty( $admin_notices ) ) {
		$style 	= $admin_notices['style']; /* 'error' or 'updated' */
		$notice	= $admin_notices['notice'];
		echo '<div class="'.$style.'"><p>'.$notice.'</p></div>';
	}
	delete_option('rshcp_admin_notices');
}
function rshcp_admin_nag_notices() {
	global $current_user;
	$nag_notices = get_user_meta( $current_user->ID, 'rshcp_nag_notices', TRUE );
	if( !empty( $nag_notices ) ) {
		$nid			= $nag_notices['nid'];
		$style			= $nag_notices['style']; /* 'error' or 'updated' */
		$timenow		= time();
		$url			= rshcp_get_url();
		$query_args		= rshcp_get_query_args( $url );
		$query_str		= '?' . http_build_query( array_merge( $query_args, array( 'rshcp_hide_nag' => '1', 'nid' => $nid ) ) );
		$query_str_con	= 'QUERYSTRING';
		$notice			= str_replace( array( $query_str_con ), array( $query_str ), $nag_notices['notice'] );
		echo '<div class="'.$style.'"><p>'.$notice.'</p></div>';
	}
}
function rshcp_check_nag_notices() {
	global $current_user;
	$status			= get_user_meta( $current_user->ID, 'rshcp_nag_status', TRUE );
	if( !empty( $status['currentnag'] ) ) { add_action( 'admin_notices', 'rshcp_admin_nag_notices' ); return; }
	if( !is_array( $status ) ) { $status = array(); update_user_meta( $current_user->ID, 'rshcp_nag_status', $status ); }
	$timenow		= time();
	$num_days_inst	= rshcp_num_days_inst();
	$query_str_con	= 'QUERYSTRING';
	/* Notices (Positive Nags) */
	if( empty( $status['currentnag'] ) && ( empty( $status['lastnag'] ) || $status['lastnag'] <= $timenow - 1209600 ) ) {
		if( empty( $status['vote'] ) && $num_days_inst >= 14 ) { /* TO DO: TRANSLATE */
			$nid = 'n01'; $style = 'updated';
			$notice_text = __( 'It looks like you\'ve been using RS Head Cleaner Lite for a while now. That\'s great! :)', RSHCP_PLUGIN_NAME ) .'</p><p>'. __( 'If you find this plugin useful, would you take a moment to give it a rating on WordPress.org?', RSHCP_PLUGIN_NAME ) .'</p><p>'. sprintf( __( '<strong><a href=%1$s>%2$s</a></strong>', RSHCP_PLUGIN_NAME ), '"'.RSHCP_WP_RATING_URL.'" target="_blank" rel="external" ', __( 'Yes, I\'d like to rate it!', RSHCP_PLUGIN_NAME ) ) .' &mdash; '.  sprintf( __( '<strong><a href=%1$s>%2$s</a></strong>', RSHCP_PLUGIN_NAME ), '"'.$query_str_con.'" ', __( 'I already did!', RSHCP_PLUGIN_NAME ) );
			$status['currentnag'] = TRUE; $status['vote'] = FALSE;
		}
		elseif( empty( $status['donate'] ) && $num_days_inst >= 90 ) { /* TO DO: TRANSLATE */
			$nid = 'n02'; $style = 'updated';
			$notice_text = __( 'You\'ve been using RS Head Cleaner Lite for several months now. We hope that means you like it and are finding it helpful. :)', RSHCP_PLUGIN_NAME ) .'</p><p>'. __( 'RS Head Cleaner Lite is provided for free.', RSHCP_PLUGIN_NAME ) . ' ' . __( 'If you like the plugin, consider a donation to help further its development.', RSHCP_PLUGIN_NAME ) .'</p><p>'. sprintf( __( '<strong><a href=%1$s>%2$s</a></strong>', RSHCP_PLUGIN_NAME ), '"'.RSHCP_DONATE_URL.'" target="_blank" rel="external" ', __( 'Yes, I\'d like to donate!', RSHCP_PLUGIN_NAME ) ) .' &mdash; '. sprintf( __( '<strong><a href=%1$s>%2$s</a></strong>', RSHCP_PLUGIN_NAME ), '"'.$query_str_con.'" ', __( 'I already did!', RSHCP_PLUGIN_NAME ) );
			$status['currentnag'] = TRUE; $status['donate'] = FALSE;
		}
	}
	/* Warnings (Negative Nags) */
	/* TO DO: Add Negative Nags - warnings about plugin conflicts and missing PHP functions */
	if( !empty( $status['currentnag'] ) ) {
		add_action( 'admin_notices', 'rshcp_admin_nag_notices' );
		$new_nag_notice = array( 'nid' => $nid, 'style' => $style, 'notice' => $notice_text );
		update_user_meta( $current_user->ID, 'rshcp_nag_notices', $new_nag_notice );
		update_user_meta( $current_user->ID, 'rshcp_nag_status', $status );
	}
}
add_action( 'admin_init', 'rshcp_hide_nag_notices', -10 );
function rshcp_hide_nag_notices() {
	if( !rshcp_is_user_admin() ) { return; }
	$ns_codes		= array( 'n01' => 'vote', 'n02' => 'donate', ); /* Nag Status Codes */
	if( !isset( $_GET['rshcp_hide_nag'], $_GET['nid'], $ns_codes[$_GET['nid']] ) || $_GET['rshcp_hide_nag'] != '1' ) { return; }
	global $current_user;
	$status			= get_user_meta( $current_user->ID, 'rshcp_nag_status', TRUE );
	$timenow		= time();
	$url			= rshcp_get_url();
	$query_args		= rshcp_get_query_args( $url ); unset( $query_args['rshcp_hide_nag'],$query_args['nid'] );
	$query_str		= http_build_query( $query_args ); if( $query_str != '' ) { $query_str = '?'.$query_str; }
	$redirect_url	= rshcp_fix_url( $url, TRUE, TRUE ) . $query_str;
	$status['currentnag'] = FALSE; $status['lastnag'] = $timenow; $status[$ns_codes[$_GET['nid']]] = TRUE;
	update_user_meta( $current_user->ID, 'rshcp_nag_status', $status );
	update_user_meta( $current_user->ID, 'rshcp_nag_notices', array() );
	wp_redirect( $redirect_url );
	exit;
}
add_filter( 'plugin_row_meta', 'rshcp_filter_plugin_meta', 10, 2 ); /* Added 1.3.5 */
function rshcp_filter_plugin_meta( $links, $file ) {
	/* Add Links on Dashboard Plugins page, in plugin meta */
	if( $file == RSHCP_PLUGIN_BASENAME ){
		$links[] = '<a href="'.RSHCP_HOME_URL.'" target="_blank" rel="external" >' . rshcp_doc_txt() . '</a>';
		$links[] = '<a href="'.RSHCP_SUPPORT_URL.'" target="_blank" rel="external" >' . __( 'Support', RSHCP_PLUGIN_NAME ) . '</a>';
		$links[] = '<a href="'.RSHCP_WP_RATING_URL.'" target="_blank" rel="external" >' . __( 'Rate the Plugin', RSHCP_PLUGIN_NAME ) . '</a>';
		$links[] = '<a href="'.RSHCP_DONATE_URL.'" target="_blank" rel="external" >' . __( 'Donate', RSHCP_PLUGIN_NAME ) . '</a>';
	}
	return $links;
}
function rshcp_num_days_inst() {
	global $rshcp_options;
	$current_date	= date('Y-m-d');
	$install_date	= empty( $rshcp_options['install_date'] ) ? $current_date : $rshcp_options['install_date'];
	$num_days_inst	= rshcp_date_diff($install_date, $current_date); if( $num_days_inst < 1 ) { $num_days_inst = 1; }
	return $num_days_inst;
}
register_deactivation_hook( __FILE__, 'rshcp_deactivation' );
function rshcp_deactivation() {
	$rshcp_css_path_old = str_replace( '/cache/'.RSHCP_CACHE_DIR_NAME.'/', '/rshcp-cache/', RSHCP_CSS_PATH );
	$rshcp_js_path_old = str_replace( '/cache/'.RSHCP_CACHE_DIR_NAME.'/', '/rshcp-cache/', RSHCP_JS_PATH );
	$rshcp_dirs_all = array(
		array( 'css' => RSHCP_CSS_PATH, 'js' => RSHCP_JS_PATH ),
		array( 'css' => $rshcp_css_path_old, 'js' => $rshcp_js_path_old ),
		);
	foreach( $rshcp_dirs_all as $i => $rshcp_dirs ) {
		foreach( $rshcp_dirs as $d => $dir ) {
			if( is_dir( $rshcp_dirs[$d] ) ) {
				$filelist = rshcp_scandir( $rshcp_dirs[$d] );
				foreach( $filelist as $f => $filename ) {
					$file = $rshcp_dirs[$d].$filename; $filerev = strrev($file); $drev = strrev($d);
					if( is_file( $file ) ){
						if( strpos( $filerev, $drev.'.' ) !== 0 ) { continue; }
						@chmod( $file, 0775 );
						@unlink( $file );
						if( file_exists( $file ) ) { @chmod( $file, 0644 ); }
					}
				}
			}
		}
	}
}
/* Admin Functions - END */

/* BENCHMARK - END */
/*
$end_time = rshcp_microtime();
$total_time = rshcp_timer( $start_time, $end_time, FALSE, 6, TRUE );
rshcp_append_log_data( RSHCP_EOL.'$start_time: "'.$start_time.'" Line: '.__LINE__.' | '.__FUNCTION__.' | '.rshcp_get_url().' | MEM USED: ' . rshcp_wp_memory_used(), TRUE );
rshcp_append_log_data( RSHCP_EOL.'$end_time: "'.$end_time.'" Line: '.__LINE__.' | '.__FUNCTION__.' | '.rshcp_get_url().' | MEM USED: ' . rshcp_wp_memory_used(), TRUE );
rshcp_append_log_data( RSHCP_EOL.'$total_time: "'.$total_time.'" Line: '.__LINE__.' | '.__FUNCTION__.' | '.rshcp_get_url().' | MEM USED: ' . rshcp_wp_memory_used(), TRUE );
*/

/* PLUGIN - END */
