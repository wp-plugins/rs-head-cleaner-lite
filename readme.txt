=== RS Head Cleaner Lite ===
Contributors: RedSand
Donate link: http://www.redsandmarketing.com/rs-head-cleaner-lite-donate/
Tags: cache, clean, cleaner, css, generator, head, head-cleaner, javascript, more, minify, performance, security, seo
Requires at least: 3.6
Tested up to: 4.0
Stable tag: trunk

This plugin cleans up a number of issues, doing the work of multiple plugins, improving speed, efficiency, security, SEO, and user experience.

== Description == 

This plugin cleans up a number of issues, doing the work of multiple plugins, improving efficiency, security, SEO, and user experience. It removes junk code from the HEAD & HTTP headers, combines/minifies/caches CSS & JavaScript files, hides the Generator/WordPress Version number, removes version numbers from CSS and JS links, and fixes the "Read more" link so it displays the entire post.

= Features =

* **Removes the Generator/WordPress Version number** from the HEAD section for security reasons. You don't want your WordPress version being visible because hackers can use it to attack your site. Even if you keep your site up to date, it still could be vulnerable to zero-day exploits.
* **Removes Version numbers from CSS and JS** in the HEAD for the same security reasons you hide WordPress version. Will also improve site loading speed because removing version numbers from the CSS & JS links will allow browsers to properly cache these files, as well as helping your site code to validate.
* **Removes junk WordPress code** from the HEAD of your site: **RSD link**, **Windows Live Writer Manifest link**, **WordPress Shortlinks** (also removed from HTTP Headers), **Adjacent Posts links (REL = PREV/NEXT)** as all are unnecessary, hurt your SEO and clutter your site code.
* **Combines, minifies, and caches CSS and JavaScript files** for even better speed improvements in page loading.
* **Fixes the "Read more"** link so it displays the entire post when you click, not just the part after the "#more".

Several of these features work together to improve page loading speed and will improve your Google PageSpeed score and Yahoo YSlow score. Do a before & after test with GTMetrix to see what I mean.

Use this plugin instead of [RS Head Cleaner Plus](http://wordpress.org/plugins/rs-head-cleaner/ "RS Head Cleaner Plus") if you don't want to move JavaScript from the HEAD to your page footer. (Some themes, especially responsive themes that use JS libraries, need the JS to stay in the HEAD.) 

For a more thorough explanation of what the plugin does and why you need it, visit the [RS Head Cleaner Lite homepage](http://www.redsandmarketing.com/plugins/rs-head-cleaner/ "RS Head Cleaner Lite Plugin").

== Installation ==

= Installation Instructions =
1. After downloading, unzip file and upload the enclosed `rs-head-cleaner-lite` directory to your WordPress plugins directory: `/wp-content/plugins/`.
2. As always, **activate** the plugin on your WordPress plugins page.
3. You are good to go...it's that easy.

= More Info / Documentation =
For more info and full documentation, visit the [RS Head Cleaner Lite plugin homepage](http://www.redsandmarketing.com/plugins/rs-head-cleaner/ "RS Head Cleaner Lite Plugin").

== Changelog ==

Version 1.3.1, *released 07/12/14*

* Added additional security checks.
* Fixed a minor bug.

Version 1.3, *released 07/03/14*

* Added the Combine/Minify/Cache CSS & JavaScript feature.

Version 1.1.0.3, *released 04/28/14* 

* Fixed a bug that caused an error message on certain server configurations.

Version 1.1.0.2, *released 04/13/14* 

* Added additional security checks.

Version 1.1.0.1, *released 04/04/14* 

* Minor code improvements / bug fixes.

Version 1.1.0.0, *released 03/26/14* 

* Added feature to remove version numbers from CSS and JS links in the HEAD.

== Frequently Asked Questions ==

= Where are the options? =

This plugin is fast, and lean...there are no options needed. You install it and it just works.

= But I already have a caching Plugin installed...Why do I need CSS and JS caching? =

Caching plugins are awesome...in fact I recommend everyone use caching plugins. But it doesn't help speed up the CSS and JavaScript files downloading. It speeds up the actual PHP and database calls by creating static HTML files. If you have a lot of plugins and have 10 JS and 10 CSS files that have to download on every page, that can still bottleneck and slow your site down. This plugin will reduce those down to 1 JS and 1 CSS that have to be downloaded. Combined with a caching plugin, your site will be even faster.

= What if I Don't Want to Have My Page's CSS Files Cached? =

This plugin is built for speed. That's why it doesn't have an options page, because I didn't want it to have any database calls, which would slow it down. After a certain amount of database calls, you lose any speed improvements. Certain industry leading websites have found that for every 100 milliseconds (1/10th of a second) their site slowed down, they lost 1% in sales. Ouch.

This new feature is integral to the plugin, so the caching isn't a feature you want, then this plugin won't be the right match for you. It's designed for people that are very speed conscious, and want hardcore solutions.

= You do great work...can I hire you? =

Absolutely...go to my [WordPress Consulting](http://www.redsandmarketing.com/web-design/wordpress-consulting/ "WordPress Consulting") page for more information.
