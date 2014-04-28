<?php
/*
Plugin Name: RS Head Cleaner Lite
Plugin URI: http://www.redsandmarketing.com/plugins/rs-head-cleaner/
Description: This plugin cleans up a number of issues, doing the work of multiple plugins, improving efficiency, security, SEO, and user experience. It removes junk code from the HEAD & HTTP headers, hides the WP Version, removes version numbers from CSS and JS links, and fixes the "Read more" link so it displays the entire post.
Author: Scott Allen
Version: 1.1.0.3
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

define( 'RSHCP_VERSION', '1.1.0.3' );
define( 'RSHCP_REQUIRED_WP_VERSION', '3.0' );

// Adds features, cleans up WP code, and eliminates need for multiple plugins:
	// - Hide WP Generator 			- Security
	// - Removes CSS/JS Versions 	- Security, Speed, Code Validation
	// - Fixes "More" link			- Fixes "More" link so you see the whole post when you click, not just the part after the "more"
	// - Head Cleaner				- Removes the following from the head section for SEO and speed: RSD Link, Windows Live Writer Manifest Link, WordPress Shortlinks, and Adjacent Posts links (Prev/Next)

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
function rs_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
		}
	return $src;
	}

add_filter( 'style_loader_src', 'rs_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'rs_remove_wp_ver_css_js', 9999 );
// CLEANUP HEADER CODE - END

// IMPROVE USER EXPERIENCE - BEGIN
// Change the "Read more" link so it displays the entire post, not just the part after the "#more"
function rs_remove_more($content) {
	global $id;
	return str_replace('#more-'.$id.'"', '"', $content);
	}

add_filter('the_content', 'rs_remove_more');
// IMPROVE USER EXPERIENCE - END

// PLUGIN - END
?>
