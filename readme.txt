=== Force HTTPS (Enable SSL Redirect & Fix Insecure Mixed Content) ===

Contributors: littlebizzy
Tags: force, https, ssl, tls, 301, redirect
Requires at least: 4.4
Tested up to: 4.8
Requires PHP: 7.0
Stable tag: 1.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: FHTTPS

Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources by implementing secure URLs without altering the database.

== Description ==

Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources by implementing secure URLs without altering the database.

* redirects all HTTP request to HTTPS (301 redirects)
* filters all non-secure internal static resource to become secure (e.g. src="https://...")
* filters all internal hyperlinks to be become secure (e.g. href="https://...")
* skips any external links
* works with image srcsets too (Version 1.0.2+)
* no need for additional plugins to fix insecure resources
* zero database queries or settings pages
* huge SEO benefits

#### Compatibility ####

This plugin has been designed for use on LEMP (Nginx) web servers with PHP 7.0 and MySQL 5.7 to achieve best performance. All of our plugins are meant for single site WordPress installations only; for both performance and security reasons, we highly recommend against using WordPress Multisite for the vast majority of projects.

#### Plugin Features ####

* Settings Page: No
* PRO Version Available: No
* Includes Media: No
* Includes CSS: No
* Database Storage: Yes
  * Transients: No
  * Options: Yes
* Database Queries: Backend only
* Must-Use Support: Yes
* Multisite Support: No
* Uninstalls Data: Yes

#### Code Inspiration ####

This plugin was partially inspired either in "code or concept" by the open-source software and discussions mentioned below:

* (n/a)

#### Recommended Plugins ####

We invite you to check out a few other related free plugins that our team has also produced that you may find especially useful:

* [Force Strong Hashing](https://wordpress.org/plugins/force-strong-hashing-littlebizzy/)
* [Disable XML-RPC](https://wordpress.org/plugins/diable-xml-rpc-littlebizzy/)
* [Server Status](https://wordpress.org/plugins/server-status-littlebizzy/)
* [Remove Category Base](https://wordpress.org/plugins/remove-category-base-littlebizzy/)
* [404 To Homepage](https://wordpress.org/plugins/404-to-homepage-littlebizzy/)

#### Special Thanks ####

We thank the following groups for their generous contributions to the WordPress community which have particularly benefited us in developing our own free plugins and paid services:

* [Automattic](https://automattic.com)
* [Delicious Brains](https://deliciousbrains.com)
* [Roots](https://roots.io)
* [rtCamp](https://rtcamp.com)
* [WP Tavern](https://wptavern.com)

#### Disclaimer ####

We released this plugin in response to our managed hosting clients asking for better access to their server, and our primary goal will remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you keep the above mentioned goals in mind, thanks!

### Keywords ####

* Terms: ssl, https, hsts, enable, generate, force, setup, configure, enforce, 301, redirect, headers, secure, insecure, incoming, requests, browser, htaccess, apache, nginx, server, replace, filter, scan, auto, automatic, dynamic, dynamically, images, files, resources, css, js, files, static, always, encrypt, free, seo, remove, relative, internal, external, sources, sitewide, site-wide

* Plugins: really simple ssl, easy https redirection, ssl insecure content fixer, one click ssl, cloudflare ssl, cloudflare flexible ssl, wp force ssl, wordpress force https, wp ssl redirect, wp encrypt, wp ssl https enforcer, force ssl, https domain alias, remove http, http https remover, force ssl everywhere

* Phrases: 301 redirect, strict transport security, force https, force ssl, enable ssl, enable tls, http to https, fix ssl, fix https, ssl certificate, ssl redirect, http redirect, https redirect, redirect http, redirect https, automatic redirect, auto redirect, fix mixed content, fix insecure content, secure resources, mixed content errors, mixed content warnings, insecure content warnings, mixed content fixer, ssl on all pages, https on all pages, ssl htaccess, https htaccess, media library https, redirect loop, infinite loop, infinite redirect loops, static files, static resources, flexible ssl, one click, single click, http headers, browser warnings, browser errors, htaccess rules, htaccess redirect, site url, home url, lets encrypt, free ssl, duplicate content, relative urls, relative protocol, protocol relative, remove protocol, sitewide ssl, site-wide ssl

== Installation ==

1. Upload to `/wp-content/plugins/force-https-littlebizzy`
2. Activate via WP Admin > Plugins
3. Test plugin is working by loading a non-HTTPS version of any page

== FAQ ==

= What HTTP header codes does this plugin send? =

It generates 301 codes for any http version of any page and redirects to https version of that page.

= Does this plugin work with CloudFlare SSL? =

Yes, it can be used with either CloudFlare's "flexible" or "full" SSL setting to avoid any spinning or errors.

= How can I change this plugin's settings? =

Currenly no settings page exists, but we may add one in future versions.

= I have a suggestion, how can I let you know? =

Please avoid leaving negative reviews in order to get a feature implemented. Instead, we kindly ask that you post your feedback on the wordpress.org support forums by tagging this plugin in your post. If needed, you may also contact our homepage.

== Changelog ==

= 1.0.2 =
* filter to "skip" external hyperlinks
* improved HTTPS filters for internal links, internal sources, and image srcsets
* optimized plugin code
* updated recommended plugins
* added rating request

= 1.0.1 =
* added recommended plugins

= 1.0.0 =
* initial release
