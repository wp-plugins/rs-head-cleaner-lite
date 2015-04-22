=== RS Head Cleaner Lite ===
Contributors: RedSand
Donate link: http://www.redsandmarketing.com/rs-head-cleaner-lite-donate/
Tags: cache, clean, cleaner, css, generator, head, head-cleaner, javascript, more, minify, performance, security, seo
Requires at least: 3.8
Tested up to: 4.2
Stable tag: trunk

This plugin cleans up a number of issues, doing the work of multiple plugins, improving speed, efficiency, security, SEO, and user experience.

== Description == 

This plugin cleans up a number of issues, doing the work of multiple plugins, improving efficiency, security, SEO, and user experience. It removes junk code from the document HEAD & HTTP headers, combines/minifies/caches CSS & JavaScript files, hides the Generator/WordPress Version number, removes version numbers from CSS and JS links, and fixes the "Read more" link so it displays the entire post.

= Documentation / Tech Support =
* Documentation: [Plugin Homepage](http://www.redsandmarketing.com/plugins/rs-head-cleaner/)
* Tech Support: [WordPress Plugin Support](http://www.redsandmarketing.com/plugins/wordpress-plugin-support/)

= Features =

* **Removes the Generator/WordPress Version number** from the document HEAD section for security reasons. You don't want your WordPress version being visible because hackers can use it to attack your site. Even if you keep your site up to date, it still could be vulnerable to zero-day exploits.
* **Removes Version numbers from CSS and JS** in the HEAD for the same security reasons you hide WordPress version. Will also improve site loading speed because removing version numbers from the CSS & JS links will allow browsers to properly cache these files, as well as helping your site code to validate.
* **Removes junk WordPress code** from the HEAD of your site: **RSD link**, **Windows Live Writer Manifest link**, **WordPress Shortlinks** (also removed from HTTP Headers), **Adjacent Posts links (REL = PREV/NEXT)** as all are unnecessary, hurt your SEO and clutter your site code.
* **Combines, minifies, and caches CSS and JavaScript files** for even better speed improvements in page loading.
* **Fixes the "Read more"** link so it displays the entire post when you click, not just the part after the "#more".

Several of these features work together to improve page loading speed and will improve your Google PageSpeed score and Yahoo YSlow score. Do a before & after test with GTMetrix to see what I mean.

The JavaScript & CSS cache files are cleared each time the plugin is deactivated manually through the dashboard (but not on automatic or bulk plugin upgrades). If you would like to manually clear the cache, simply deactivate and reactivate the plugin. Important: If you're using a caching plugin, when you do this, *be sure to clear your caching plugin's file cache as well to prevent issues from a cached page calling on now-missing JS & CSS files*. (For example, with WP Super Cache, go to the settings, and on the Content page, choose "Delete Cache". It will be similar with other caching plugins.)

Use this plugin instead of [RS Head Cleaner Plus](http://wordpress.org/plugins/rs-head-cleaner/ "RS Head Cleaner Plus") if you don't want to move JavaScript from the page HEAD to your page footer. (Some themes, especially responsive themes that use JS libraries, need the JS to stay in the HEAD.)

*As with any JS/CSS minification & caching plugin, it's important to test, test, test.* Because of the type of plugin it is, this plugin may not work for every site out there, but that does not mean the plugin is broken. **If you have any issues, please submit a [support request](http://www.redsandmarketing.com/plugins/wordpress-plugin-support/) so we can look into it and make it as compatible as possible for everyone.**

For a more thorough explanation of what the plugin does and why you need it, visit the [RS Head Cleaner Lite homepage](http://www.redsandmarketing.com/plugins/rs-head-cleaner/ "RS Head Cleaner Lite Plugin").

== Installation ==

= Installation Instructions =

**Option 1:** Install the plugin directly through the WordPress Admin Dashboard (Recommended)

1. Go to *Plugins* -> *Add New*.

2. Type *RS Head Cleaner Lite* into the Search box, and click *Search Plugins*.

3. When the results are displayed, click *Install Now*.

4. When it says the plugin has successfully installed, click **Activate Plugin** to activate the plugin (or you can do this on the Plugins page).

**Option 2:** Install .zip file through WordPress Admin Dashboard

1. Go to *Plugins* -> *Add New* -> *Upload*.

2. Click *Choose File* and find `rs-head-cleaner-lite.zip` on your computer's hard drive.

3. Click *Install Now*.

4. Click **Activate Plugin** to activate the plugin (or you can do this on the Plugins page).

**Option 3:** Install .zip file through an FTP Client (Recommended for Advanced Users Only)

1. After downloading, unzip file and use an FTP client to upload the enclosed `rs-head-cleaner-lite` directory to your WordPress plugins directory (usually `/wp-content/plugins/`) on your web server.

2. Go to your Plugins page in the WordPress Admin Dashboard, and find this plugin in the list.

3. Click **Activate** to activate the plugin.

= Other Notes =

This plugin has not been designed specifically for use with Multisite. It can be used in Multisite if activated *per site*, but *should not* be Network Activated. As with any plugin, test and make sure it works with your particular setup before using on a production site.

= More Info / Documentation =
For more info and full documentation, visit the [RS Head Cleaner Lite plugin homepage](http://www.redsandmarketing.com/plugins/rs-head-cleaner/ "RS Head Cleaner Lite Plugin").

== Frequently Asked Questions ==

= Where are the options? =

This plugin is fast, and lean...there are no options needed. You install it and it just works.

= Does this plugin have any known issues? = 

We are aware of an issue with some themes using sliders, ans some responsive themes. We are currently working on resolving this and creating a fix. Please test this plugin out thoroughly with your theme prior to using it on a live site. We will provide an update as soon as we have this resolved. Most themes are able to use this with no issues whatsoever.

= But I already have a caching Plugin installed...Why do I need CSS and JS caching? =

Caching plugins are awesome...in fact I recommend everyone use caching plugins. But it doesn't help speed up the CSS and JavaScript files downloading. It speeds up the actual PHP and database calls by creating static HTML files. If you have a lot of plugins and have 10 JS and 10 CSS files that have to download on every page, that can still bottleneck and slow your site down. This plugin will reduce those down to 1 JS and 1 CSS that have to be downloaded. Combined with a caching plugin, your site will be even faster.

= What if I Don't Want to Have My Page's CSS Files Cached? =

This plugin is built for speed. That's why it doesn't have an options page, because I didn't want it to have any database calls, which would slow it down. After a certain amount of database calls, you lose any speed improvements. Certain industry leading websites have found that for every 100 milliseconds (1/10th of a second) their site slowed down, they lost 1% in sales. Ouch.

This new feature is integral to the plugin, so the caching isn't a feature you want, then this plugin won't be the right match for you. It's designed for people that are very speed conscious, and want hardcore solutions.

= You do great work...can I hire you? =

Absolutely...go to my [WordPress Consulting](http://www.redsandmarketing.com/web-design/wordpress-consulting/ "WordPress Consulting") page for more information.

== Changelog ==

= 1.3.7 =
*released 04/22/15*

* Fixed some bugs with the JavaScript and CSS compression.
* Added an `.htaccess` file to the `rs-head-cleaner-lite` directory to control browser access to certain files.
* Increased minimum required WordPress version to 3.8.
* Made various code improvements.

= 1.3.6 =
*released 03/06/15*

* Made various minor code improvements.

= 1.3.5 =
*released 03/03/15*

* Added a function that will clear the JavaScript & CSS cache files each time the plugin is deactivated manually through the dashboard (but not on automatic or bulk plugin upgrades). If you would like to manually clear the cache, simply deactivate and reactivate the plugin. (If you're using a page caching plugin, just be sure to also clear that plugin's cache as well at the same time to prevent related issues.)
* Fixed a minor bug in the uninstall function.

= 1.3.4 =
*released 03/01/15*

* Added an uninstall function that completely uninstalls the plugin and removes all cache files, options, data, and traces of its existence when it is deleted through the dashboard.
* Added a minor JavaScript compatibility fix for Twenty Fourteen and similar themes.

= 1.3.3 =
*released 01/19/15*

* Fixed a minor bug.
* Increased minimum required WordPress version to 3.7.

= 1.3.2 =
*released 12/18/14*

* Added additional security checks.
* Various code improvements.
* Fixed a bug that caused the plugin to attempt to cache web font files.

= 1.3.1 =
*released 07/12/14*

* Added additional security checks.
* Fixed a minor bug.

= 1.3 =
*released 07/03/14*

* Added the Combine/Minify/Cache CSS & JavaScript feature.

= 1.1.1 =
*released 06/17/14*

* Updated some features.

= 1.1.0.3 =
*released 04/28/14*

* Fixed a bug that caused an error message on certain server configurations.

= 1.1.0.2 =
*released 04/13/14*

* Added additional security checks.

= 1.1.0.1 =
*released 04/04/14*

* Minor code improvements / bug fixes.

= 1.1 =
*released 03/26/14*

* Added feature to remove version numbers from CSS and JS links in the HEAD.

= 1.0 =
*released 03/17/14*

* Initial release.

== Upgrade Notice ==
= 1.3.7 =
Fixed some bugs, added an .htaccess file, increased the minimum required WordPress version to 3.8, and made various code improvements. Please see Changelog for details.
