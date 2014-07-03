<?php
/*
Plugin Name: RS Head Cleaner Lite
Plugin URI: http://www.redsandmarketing.com/plugins/rs-head-cleaner/
Description: This plugin cleans up a number of issues, doing the work of multiple plugins, improving speed, efficiency, security, SEO, and user experience. It removes junk code from the HEAD & HTTP headers, hides the WP Version, Combines/Minifies/Caches CSS and JavaScript files, removes version numbers from CSS and JS links, and fixes the "Read more" link so it displays the entire post.
Author: Scott Allen
Version: 1.3
Author URI: http://www.redsandmarketing.com/
License: GPLv2
*/

/*  Copyright 2014    Scott Allen  (email : plugins [at] redsandmarketing [dot] com)

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


// PLUGIN - BEGIN

/* Note to any other PHP developers reading this:
My use of the end curly braces "}" is a little funky in that I indent them, I know. IMO it's easier to debug. Just know that it's on purpose even though it's not standard. One of my programming quirks, and just how I roll. :)
*/

// Make sure plugin remains secure if called directly
if ( !function_exists( 'add_action' ) ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 403 Forbidden');
		}
	die('ERROR: This plugin requires WordPress and will not function if called directly.');
	}

define( 'RSHCL_VERSION', '1.3' );
define( 'RSHCL_REQUIRED_WP_VERSION', '3.6' );

if ( !defined( 'RSHCL_REMOVE_OPEN_SANS' ) ) { define( 'RSHCL_REMOVE_OPEN_SANS', false ); } // Change in wp-config.php
// By default this feature is off, but if you don't need Open Sans and you want a faster site, add a line in your wp-config.php that says: "define( 'RSHCL_REMOVE_OPEN_SANS', true );"
// Any of these can be changed in wp-config.php:
if ( !defined( 'RSHCL_DIR_NAME' ) ) { define( 'RSHCL_DIR_NAME', 'rshcl-cache' ); }
if ( !defined( 'RSHCL_JS_PATH' ) ) { define( 'RSHCL_JS_PATH', WP_CONTENT_DIR.'/'.RSHCL_DIR_NAME.'/js/' ); }
if ( !defined( 'RSHCL_CSS_PATH' ) ) { define( 'RSHCL_CSS_PATH', WP_CONTENT_DIR.'/'.RSHCL_DIR_NAME.'/css/' ); }
if ( !defined( 'RSHCL_JS_URL' ) ) { define( 'RSHCL_JS_URL', WP_CONTENT_URL.'/'.RSHCL_DIR_NAME.'/js/' ); }
if ( !defined( 'RSHCL_CSS_URL' ) ) { define( 'RSHCL_CSS_URL', WP_CONTENT_URL.'/'.RSHCL_DIR_NAME.'/css/' ); }

// Adds features, cleans up WP code, and eliminates need for multiple plugins:
	// - Hide WP Generator 				- Security
	// - Removes CSS/JS Versions 		- Security, Speed, Code Validation - Speed: Allows browser to cache JS and CSS files when they don't have arguments appended to URL
	// - Adds Defer & Async to JS		- For Speed in page loading - Adds defer="defer" and async="async" to all JS except Jquery & Theme JS to speed up page loading
	// - Fixes "More" link				- Fixes "More" link so you see the whole post when you click, not just the part after the "more"
	// - Removes Open Sans				- (Optional) Removes the Open Sans from WordPress to speed up your site by removing the call to Google Fonts Library
	// - Remove CF7 JS/CSS				- Remove Contact Form 7 JS/CSS on pages/post where shortcode isn't used (it only needs to be on pages that actually use it)
	// - Combine, Minify & Cache JS/CSS	- Combine all properly registered/queued JS & CSS into one file, minify, and cache these new single files. Fixes CSS image URL locations too. CSS & JS will stay in header.
	// - Head Cleaner					- Removes the following from the head section for SEO and speed: RSD Link, Windows Live Writer Manifest Link, WordPress Shortlinks, and Adjacent Posts links (Prev/Next)

// CLEANUP HEADER CODE - BEGIN
remove_action ('wp_head', 'rsd_link');
	// Remove RSD Link - If you edit blog through browser, then it is not needed.
	
remove_action( 'wp_head', 'wlwmanifest_link');
	// Remove Windows Live Writer Manifest Link...similar deal
	
remove_action( 'wp_head', 'wp_shortlink_wp_head');
	// Remove WordPress Shortlinks from WP HEAD - WP implements it incorrectly, Bad for SEO, and it adds ugly code
	
remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
	// Remove WordPress Shortlinks from HTTP Headers
	
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// Remove REL = PREV/NEXT
	// WP incorrectly implements this - supposed to fix pagination issues but it messes up
	// Use All in One SEO Pack - it handles proper implementation of this well, on paginated pages/posts

remove_action('wp_head', 'wp_generator');
	// Remove WP Generator/Version - for security reasons

// Remove version numbers from CSS and JS in HEAD
function rshcl_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
		}
	return $src;
	}
add_filter( 'style_loader_src', 'rshcl_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'rshcl_remove_wp_ver_css_js', 9999 );
// CLEANUP HEADER CODE - END

// IMPROVE USER EXPERIENCE - BEGIN
// Change the "Read more" link so it displays the entire post, not just the part after the "#more"
function rshcl_remove_more($content) {
	global $id;
	return str_replace('#more-'.$id.'"', '"', $content);
	}
add_filter('the_content', 'rshcl_remove_more');
// IMPROVE USER EXPERIENCE - END

// SPEED UP WORDPRESS - BEGIN
// Add Defer & Async to Scripts
function rshcl_defer_async_js( $url ) {
	if ( is_admin() ) { return $url; } // Skip if in WP Admin
    if ( FALSE === strpos( $url, '.js' ) ) { return $url; } // Skip non-JS
    if ( strpos( $url, 'jquery.js' ) || strpos( $url, '/jquery.' ) || strpos( $url, '/themes/' ) || strpos( $url, '/contact-form-7/' ) ) { return $url; } // Skip jquery and theme related JS
	$new_url = "$url' async='async' defer='defer";
    return $new_url;
	}
//add_filter( 'clean_url', 'rshcl_defer_async_js', 9999, 1 );
// Remove Open Sans to Speed Page Loading - Only for Admin area (default), if you change wp-config.php setting, will also work when logged in on any part of site
function rshcl_remove_opensans() {
	if ( is_admin() || RSHCL_REMOVE_OPEN_SANS != false ) {
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );
		wp_enqueue_style( 'open-sans', '' );
		}
	}
add_action( 'init', 'rshcl_remove_opensans', 9999 );
// Remove Contact Form 7 JS/CSS on pages/post where shortcode isn't used
function rshcl_remove_cf7_css_js() {
	global $post;
	if ( ! has_shortcode( $post->post_content, 'contact-form-7' ) ) {
		remove_action('wp_enqueue_scripts', 'wpcf7_enqueue_styles');
		remove_action('wp_enqueue_scripts', 'wpcf7_enqueue_scripts');
		}
	}
add_action( 'wp', 'rshcl_remove_cf7_css_js');
// Combine all JS and CSS, Minify, Cache and Serve one file. CSS & JS will stay in header.
function rshcl_simple_minifier_css( $css_to_minify, $filter = true ) {
	if( empty( $filter ) ) { return $css_to_minify; }
	$css_buffer 	= $css_to_minify;
	// Replace all newlines with \n
	$css_buffer 	= str_replace( array( "\r\n","\r","\n","\f","\v" ), array( "\n","\n","\n","\n","\n" ), $css_buffer );
	// Remove comments
	$css_buffer 	= preg_replace( "~(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)~", '', $css_buffer );
	$css_buffer 	= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)[\n\t\ ]*\/\/.*\n)~", "\n", $css_buffer );
	$css_buffer 	= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)([;\{\}]*)\/\/.*\n)~", "$1", $css_buffer );
	// Trim lines
	$css_buffer 	= preg_replace( "~(?:\ *\n\ *)~", "\n", $css_buffer );
	// Remove tabs, spaces, etc.
	$css_buffer 	= str_replace( array( "\t",'  ','   ','    ','     ' ), '', $css_buffer );
	// Remove spaces after {},;:
	$css_buffer 	= str_replace( array( '{ ',' }',' {','} ',', ','; ',' : ',': ' ), array( '{','}','{','}',',',';',':',':' ), $css_buffer );
	// Remove tabs, spaces, newlines, etc.
	$css_buffer 	= preg_replace( "~\n{2,}~", "\n", $css_buffer );
	$css_buffer 	= str_replace( array( "{\n","\n}","\n{","}\n",",\n",";\n" ), array( '{','}','{','}',',',';' ), $css_buffer );
	// Add more rules - BEGIN
	
	// Add more rules - END
	$css_minified	= trim( $css_buffer );
	return $css_minified;
    }
function rshcl_simple_minifier_js( $js_to_minify, $filter = true ) {
	if( empty( $filter ) ) { return $js_to_minify; }
	$js_buffer		= $js_to_minify;
	// These aren't all done at once because order of steps is important
	// Replace all newlines with \n
	$js_buffer 		= str_replace( array( "\r\n","\r","\n","\f","\v" ), array( "\n","\n","\n","\n","\n" ), $js_buffer );
	// Remove comments
	$js_buffer 		= preg_replace( "~(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)~", '', $js_buffer );
	$js_buffer 		= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)[\n\t\ ]*\/\/.*\n)~", "\n", $js_buffer );
	$js_buffer 		= preg_replace( "~(?:(?![\/a-zA-Z0-9]+)([;\{\}]*)\/\/.*\n)~", "$1", $js_buffer );
	// Trim lines
	$js_buffer 		= preg_replace( "~(?:\ *\n\ *)~", "\n", $js_buffer );
	$js_buffer 		= str_replace( array( " \\\n", " \\ \n" ), array( '', '' ), $js_buffer );
	// Remove spaces around JS operators: - + * ? % || && = == != < > <= >=
	$js_buffer 		= str_replace( array(' - ',' + ',' * ',' / ',' ? ',' % ',' || ',' && ',' = ',' != ',' == ',' === ',' < ',' > ',' <= ',' >= ' ), array( '-','+','*','/','?','%','||','&&','=','!=','==','===','<','>','<=','>=' ), $js_buffer );
	// Remove tabs, spaces, etc.
	$js_buffer 		= str_replace( array( "\t",'  ','   ','    ','     ' ), '', $js_buffer );
	// Remove spaces after {}[](),;:
	$js_buffer 		= str_replace( array( '{ ',' }',' {','} ','[ ',' ]','( ',' )',' (',') ',', ','; ',' : ',': ' ), array( '{','}','{','}','[',']','(',')','(',')',',',';',':',':' ), $js_buffer );
	// Remove tabs, spaces, newlines, etc.
	$js_buffer 		= str_replace( array( " \\\n", " \\ \n" ), array( '', '' ), $js_buffer );
	$js_buffer 		= preg_replace( "~\n{2,}~", "\n", $js_buffer );
	$js_buffer 		= str_replace( array( "{\n","\n}","[\n","\n]" ), array( '{','}','[',']' ), $js_buffer );
	$js_buffer 		= str_replace( array( ",\n",":\n",";\n","&\n","=\n","+\n","-\n","?\n","}\\\n"," } \\ \n" ), array( ',',':',';','&','=','+','-','?','}','}' ), $js_buffer );
	// Add more rules - BEGIN
	
	// Add more rules - END
	$js_minified	= trim( $js_buffer );
	return $js_minified;
    }
function rshcl_get_domain($url) {
	// Filter URLs with nothing after http
	if ( empty( $url ) || preg_match( "~^https?\:*/*$~i", $url ) ) { return ''; }
	// Fix poorly formed URLs so as not to throw errors when parsing
	$url = rshcl_fix_url($url);
	// NOW start parsing
	$parsed = parse_url($url);
	// Filter URLs with no domain
	if ( empty( $parsed['host'] ) ) { return ''; }
	$hostname = $parsed['host'];
	return $hostname;
	}
function rshcl_fix_url($url) {
	// Fix poorly formed URLs so as not to throw errors or cause problems
	// Too many forward slashes or colons after http
	$url = preg_replace( "~^(https?)\:+/+~i", "$1://", $url);
	// Too many dots
	$url = preg_replace( "~\.+~i", ".", $url);
	// Too many slashes after the domain
	$url = preg_replace( "~([a-z0-9]+)/+([a-z0-9]+)~i", "$1/$2", $url);
	return $url;
	}
function rshcl_get_url() {
	if ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) { $url = 'https://'; } else { $url = 'http://'; }
	$url .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	return $url;
	}
function rshcl_get_slug() {
	$url = rshcl_get_url();
	if ( !empty( $url ) ) {
		$slug = url_to_postid( $url );
		}
	else { 
		$url_parts = explode( '/', $url );
		$last = count( $url_parts ) - 1;
		$slug = $url_parts[$last];
		}
	return $slug;
	}
function rshcl_strlen($string) {
	// Use this function instead of mb_strlen because some IIS servers have mb_strlen disabled by default
	// BUT mb_strlen is superior to strlen, so use it whenever possible
	if (function_exists( 'mb_strlen' ) ) { $num_chars = mb_strlen($string); } else { $num_chars = strlen($string); }
	return $num_chars;
	}
add_action('init', 'rshcl_cache_combine_js_css');
function rshcl_cache_combine_js_css() {
	if ( !is_admin() && !is_user_logged_in() ) {
		add_action( 'wp_enqueue_scripts', 'rshcl_enqueue_styles' );
		add_action( 'wp_enqueue_scripts', 'rshcl_enqueue_scripts' );
		add_action( 'wp_print_styles', 'rshcl_inspect_styles', 9999 );
		add_action( 'wp_print_scripts', 'rshcl_inspect_scripts', 9999 );
		add_action( 'wp_print_head_scripts', 'rshcl_inspect_scripts', 9999 );
		}
	}
function rshcl_enqueue_styles() {
	$slug = rshcl_get_slug();
	$min_slug = 'rsm-min-css-'.$slug;
	$min_file_slug = $min_slug.'.css';
	wp_register_style( $min_slug, RSHCL_CSS_URL.$min_file_slug );
	wp_enqueue_style( $min_slug );
	}
function rshcl_enqueue_scripts() {
	$slug = rshcl_get_slug();
	$min_slug = 'rsm-min-js-'.$slug;
	$min_file_slug = $min_slug.'.js';
	wp_register_script( $min_slug, RSHCL_JS_URL.$min_file_slug, array(), RSHCL_VERSION, false );
	wp_enqueue_script( $min_slug );
	}
function rshcl_inspect_scripts() {
	$slug 	= rshcl_get_slug();
	$url 	= rshcl_get_url();
	$domain	= rshcl_get_domain( $url );
	$raw_slug = 'rsm-raw-js-'.$slug;
	$min_slug = 'rsm-min-js-'.$slug;
	$raw_file_slug = $raw_slug.'.js';
	$min_file_slug = $min_slug.'.js';
	$raw_js_file = RSHCL_JS_PATH.$raw_file_slug;
	$min_js_file = RSHCL_JS_PATH.$min_file_slug;
	global $wp_scripts;
	$script_handles = array();
	$script_srcs 	= array();
	$combined_js 	= array();
	foreach( $wp_scripts->queue as $handle ) {
		$script_src = $wp_scripts->registered[$handle]->src;
		$script_domain = rshcl_get_domain( $script_src );
		if( empty( $script_src ) || $handle == $min_slug || $handle == 'contact-form-7' || $script_domain != $domain ) { continue; }
		$script_handles[] 	= $handle;
		$script_srcs[] 		= $script_src;
		$combined_js[] 		= file_get_contents( $script_src );
		wp_dequeue_script( $handle );
		wp_deregister_script( $handle );
		}
	$combined_js_contents_raw = implode( "\n", $combined_js );
	$combined_js_contents_len = rshcl_strlen( $combined_js_contents_raw );
	$combined_js_contents = rshcl_simple_minifier_js( $combined_js_contents_raw );
	$plugin_file_mod_time = filemtime( __FILE__ );
	if ( file_exists( $raw_js_file ) ) {
		$raw_js_file_mod_time = filemtime( $raw_js_file );
		$raw_js_file_filesize = filesize( $raw_js_file );
		}
	else {
		$raw_js_file_mod_time = false;
		$raw_js_file_filesize = false;
		}
	$js_cache_time = time() - 86400; // 60 * 60 * 1 - Sec * Min * Hour; 3600 = 1 Hour; 86400 = 24 Hours;
	if( $raw_js_file_filesize != $combined_js_contents_len || $raw_js_file_mod_time < $plugin_file_mod_time || $raw_js_file_mod_time < $js_cache_time ) {
		file_put_contents( $raw_js_file, $combined_js_contents_raw );
		file_put_contents( $min_js_file, $combined_js_contents );
		}
	}
function rshcl_inspect_styles() {
	$slug 	= rshcl_get_slug();
	$url 	= rshcl_get_url();
	$domain	= rshcl_get_domain( $url );
	$raw_slug = 'rsm-raw-css-'.$slug;
	$min_slug = 'rsm-min-css-'.$slug;
	$raw_file_slug = $raw_slug.'.css';
	$min_file_slug = $min_slug.'.css';
	$raw_css_file = RSHCL_CSS_PATH.$raw_file_slug;
	$min_css_file = RSHCL_CSS_PATH.$min_file_slug;
	global $wp_styles;
	$style_handles 	= array();
	$style_srcs 	= array();
	$combined_css 	= array();
	foreach( $wp_styles->queue as $handle ) {
		$style_src = $wp_styles->registered[$handle]->src;
		$style_domain = rshcl_get_domain( $style_src );
		if( empty( $style_src ) || $handle == $min_slug || $style_domain != $domain ) { continue; } // || strpos( $style_src, '/themes/' )
		$handle_rgx			= preg_quote( $handle );
		$style_handles[] 	= $handle;
		$style_srcs[] 		= $style_src;
		// Get the absolute URL to replace relative URLs in CSS since we're moving location of CSS file
		$css_buffer 		= file_get_contents( $style_src );
		$style_src_no_http	= preg_replace( "~https?\://~i", '', $style_src );
		$url_buffer 		= explode( '/', $style_src_no_http );
		$url_elements		= count( $url_buffer ) - 1;
		unset( $url_buffer[$url_elements] );
		--$url_elements;
		if( preg_match_all( "~(url\('?(?:\.?/)?([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\))~i", $css_buffer, $matches ) ) {
			$new_url_base = implode( '/', $url_buffer );
			$css_buffer = preg_replace( "~url\('?\.?/?([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\)~i", "url('".'//'.$new_url_base."/$1')", $css_buffer );
			}
		if ( preg_match_all( "~(url\('?(?:\.\./)+(?:[a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\))~i", $css_buffer, $matches ) ) {
			$show_matches = array();
			foreach( $matches[1] as $m => $match ) {
				$url_buffer_m = $url_buffer;
				// Number of directories down
				$num_dirs_down	= substr_count( $match, '../' );
				//URL Elements Reduced
				$url_elements_red = $url_elements - $num_dirs_down;
				// Removing last element(s) of array is how we go down one or more directories
				$i = $url_elements;
				while( $i > $url_elements_red ) { unset( $url_buffer_m[$i] ); $i--; }
				$new_url_base 	= implode( '/', $url_buffer_m );
				$m_buffer 		= $match;
				$m_buffer 		= preg_replace( "~url\('?(?:\.\./)+([a-z0-9/\-_]+\.[a-z]{2,4}(#[a-z0-9]+)?)'?\)~i", "url('".'//'.$new_url_base."/$1')", $m_buffer );
				$match_rgx 		= preg_quote( $match );
				$css_buffer 	= preg_replace( "~$match_rgx~i", $m_buffer, $css_buffer, -1, $count );
				}
			}
		$combined_css[] = $css_buffer;
		wp_dequeue_style( $handle );
		wp_deregister_style( $handle );
		}
	$combined_css_contents_raw = implode( "\n", $combined_css );
	$combined_css_contents_len = rshcl_strlen( $combined_css_contents_raw );
	$combined_css_contents = rshcl_simple_minifier_css( $combined_css_contents_raw );
	$plugin_file_mod_time = filemtime( __FILE__ );
	if ( file_exists( $raw_css_file ) ) {
		$raw_css_file_mod_time = filemtime( $raw_css_file );
		$raw_css_file_filesize = filesize( $raw_css_file );
		}
	else {
		$raw_css_file_mod_time = false;
		$raw_css_file_filesize = false;
		}
	$css_cache_time = time() - 86400; // 60 * 60 * 1 - Sec * Min * Hour; 3600 = 1 Hour; 86400 = 24 Hours;
	if( $raw_css_file_filesize != $combined_css_contents_len || $raw_css_file_mod_time < $plugin_file_mod_time || $raw_css_file_mod_time < $css_cache_time ) {
		file_put_contents( $raw_css_file, $combined_css_contents_raw );
		file_put_contents( $min_css_file, $combined_css_contents );
		}
	}
// SPEED UP WORDPRESS - END

// Admin Functions
register_activation_hook( __FILE__, 'rshcl_install_on_activation' );
function rshcl_install_on_activation() {
	$installed_ver = get_option('rs_head_cleaner_lite_version');
	if ( empty( $installed_ver ) || $installed_ver != RSHCL_VERSION ) {
		update_option('rs_head_cleaner_lite_version', RSHCL_VERSION);
		}
	$rshcl_js_dir = RSHCL_JS_PATH;
	$rshcl_css_dir = RSHCL_CSS_PATH;
	if ( !file_exists( $rshcl_js_dir ) ) {
		wp_mkdir_p( $rshcl_js_dir );
		}
	if ( !file_exists( $rshcl_css_dir ) ) {
		wp_mkdir_p( $rshcl_css_dir );
		}
	}

// PLUGIN - END
?>