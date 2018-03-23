=== Force HTTPS (SSL Redirect & Fix Insecure Content) ===

Contributors: littlebizzy
Donate link: https://www.patreon.com/littlebizzy
Tags: force, https, ssl, insecure, mixed, content
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 7.0
Multisite support: No
Stable tag: 1.0.6
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: FHTTPS

Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources without altering the database (also works with CloudFlare).

== Description ==

Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources without altering the database (also works with CloudFlare).

* [**Join our FREE Facebook group for support!**](https://www.facebook.com/groups/littlebizzy/)
* [Plugin Homepage](https://www.littlebizzy.com/plugins/force-https)
* [Plugin GitHub](https://github.com/littlebizzy/force-https)
* [SlickStack](https://slickstack.io)
* [Starter Theme](https://starter.littlebizzy.com)

#### The Long Version ####

The only Force SSL (HTTPS) plugin that correctly follows Google Chrome team's advice to avoid protocol-relative hyperlinks and resources. Here are more of the current features:

* redirects all HTTP requests to HTTPS (domain/protocol 301 redirects)
* filters all internal resources to become secure (e.g. src="https://...")
* filters all internal hyperlinks to be become secure (e.g. href="https://...")
* filters all external resources to become secure (src, srcset, embeds, and objects)
* skips any external hyperlinks
* works with image srcsets too (Version 1.0.2+)
* no need for additional plugins to fix insecure resources
* avoids "protocol relative" URLs as recommended by top security experts
* zero database queries or settings pages
* huge SEO and security benefits

WARNING: You must have an SSL certificate installed on your server before activating this plugin. If you website becomes inaccessible after activation, login via SFTP and delete this plugin from `/wp-content/plugins/` and clear your browser cache.

#### Compatibility ####

This plugin has been designed for use on LEMP (Nginx) web servers with PHP 7.0 and MySQL 5.7 to achieve best performance. All of our plugins are meant for single site WordPress installations only; for both performance and security reasons, we highly recommend against using WordPress Multisite for the vast majority of projects.

#### Plugin Features ####

* Settings Page: No
* Premium Version Available: Yes ([SEO Genius](https://www.littlebizzy.com/plugins/seo-genius))
* Includes Media (Images, Icons, Etc): No
* Includes CSS: No
* Database Storage: Yes
  * Transients: No
  * Options: Yes
  * Creates New Tables: No
* Database Queries: Backend Only (Options API)
* Must-Use Support: Yes (Use With [Autoloader](https://github.com/littlebizzy/autoloader))
* Multisite Support: No
* Uninstalls Data: Yes

#### Code Inspiration ####

This plugin was partially inspired either in "code or concept" by the open-source software and discussions mentioned below:

* [Paul Irish](https://www.paulirish.com/2010/the-protocol-relative-url/)
* [Jeremy Wagner](https://jeremywagner.me/blog/stop-using-the-protocol-relative-url)
* [SSL Insecure Content Fixer](https://wordpress.org/plugins/ssl-insecure-content-fixer/)
* [WP Force SSL](https://wordpress.org/plugins/wp-force-ssl/)
* [Really Simple SSL](https://wordpress.org/plugins/really-simple-ssl/)

#### Admin Notices ####

This plugin generates multiple [Admin "Nag" Notices](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices#Disable_Nag_Notices) in the WP Admin dashboard. The first one fires during plugin activation which recommends several free plugins that we believe will enhance this plugin's features; this notice will re-appear once every 6 months as our code and recommendations evolve. The second is a notice that fires a few days after plugin activation which asks for a 5-star rating of this plugin on its WordPress.org profile page. This notice will re-appear once every 9 months. These notices can be dismissed by clicking the **(x)** symbol in the upper right of the notice box. These notices may annoy or confuse certain users, but are appreciated by the majority of our userbase, who understand that these notices support our free contributions to the WordPress community while providing valuable (free) recommendations for optimizing their website.

If you feel these notices are too annoying, we encourage you to consider one or more of our upcoming premium plugins that combine several free plugin features into a single control panel, or even consider developing your own plugins for WordPress, if supporting free plugin authors is too frustrating for you. A final alternative would be to place the following defined constant in your `wp-config.php` or `functions.php` file to manually hide this plugin's nag notices:

    define('DISABLE_NAG_NOTICES', true);

Note: This will only affect the nag notices mentioned above, and will not affect any other notices generated by this plugin or other plugins, such as one-time notices for admin-level users.

#### Recommended Plugins ####

We invite you to check out some of our other free plugins hosted on WP.org that you may find particularly valuable:

* [404 To Homepage](https://wordpress.org/plugins/404-to-homepage-littlebizzy/)
* [CloudFlare](https://wordpress.org/plugins/cf-littlebizzy/)
* [Delete Expired Transients](https://wordpress.org/plugins/delete-expired-transients-littlebizzy/)
* [Disable Admin-AJAX](https://wordpress.org/plugins/disable-admin-ajax-littlebizzy/)
* [Disable Author Pages](https://wordpress.org/plugins/disable-author-pages-littlebizzy/)
* [Disable Cart Fragments](https://wordpress.org/plugins/disable-cart-fragments-littlebizzy/)
* [Disable Embeds](https://wordpress.org/plugins/disable-embeds-littlebizzy/)
* [Disable Emojis](https://wordpress.org/plugins/disable-emojis-littlebizzy/)
* [Disable Empty Trash](https://wordpress.org/plugins/disable-empty-trash-littlebizzy/)
* [Disable Image Compression](https://wordpress.org/plugins/disable-image-compression-littlebizzy/)
* [Disable jQuery Migrate](https://wordpress.org/plugins/disable-jq-migrate-littlebizzy/)
* [Disable Search](https://wordpress.org/plugins/disable-search-littlebizzy/)
* [Disable WooCommerce Status](https://wordpress.org/plugins/disable-wc-status-littlebizzy/)
* [Disable WooCommerce Styles](https://wordpress.org/plugins/disable-wc-styles-littlebizzy/)
* [Disable XML-RPC](https://wordpress.org/plugins/disable-xml-rpc-littlebizzy/)
* [Download Media](https://wordpress.org/plugins/download-media-littlebizzy/)
* [Download Plugin](https://wordpress.org/plugins/download-plugin-littlebizzy/)
* [Download Theme](https://wordpress.org/plugins/download-theme-littlebizzy/)
* [Duplicate Post](https://wordpress.org/plugins/duplicate-post-littlebizzy/)
* [Export Database](https://wordpress.org/plugins/export-database-littlebizzy/)
* [Force HTTPS](https://wordpress.org/plugins/force-https-littlebizzy/)
* [Force Strong Hashing](https://wordpress.org/plugins/force-strong-hashing-littlebizzy/)
* [Google Analytics](https://wordpress.org/plugins/ga-littlebizzy/)
* [Header Cleanup](https://wordpress.org/plugins/header-cleanup-littlebizzy/)
* [Index Autoload](https://wordpress.org/plugins/index-autoload-littlebizzy/)
* [Maintenance Mode](https://wordpress.org/plugins/maintenance-mode-littlebizzy/)
* [Profile Change Alerts](https://wordpress.org/plugins/profile-change-alerts-littlebizzy/)
* [Remove Category Base](https://wordpress.org/plugins/remove-category-base-littlebizzy/)
* [Remove Query Strings](https://wordpress.org/plugins/remove-query-strings-littlebizzy/)
* [Server Status](https://wordpress.org/plugins/server-status-littlebizzy/)
* [StatCounter](https://wordpress.org/plugins/sc-littlebizzy/)
* [View Defined Constants](https://wordpress.org/plugins/view-defined-constants-littlebizzy/)
* [Virtual Robots.txt](https://wordpress.org/plugins/virtual-robotstxt-littlebizzy/)

#### Premium Plugins ####

We invite you to check out a few premium plugins that our team has also produced that you may find particularly valuable:

* [Speed Demon](https://www.littlebizzy.com/plugins/speed-demon)
* [SEO Genius](https://www.littlebizzy.com/plugins/seo-genius)
* [Great Migration](https://www.littlebizzy.com/plugins/great-migration)
* [Security Guard](https://www.littlebizzy.com/plugins/security-guard)
* [Genghis Khan](https://www.littlebizzy.com/plugins/genghis-khan)

#### Special Thanks ####

We thank the following groups for their generous contributions to the WordPress community which have particularly benefited us in developing our own plugins and services:

* [Automattic](https://automattic.com)
* [Brad Touesnard](https://bradt.ca)
* [Daniel Auener](http://www.danielauener.com)
* [Delicious Brains](https://deliciousbrains.com)
* [Greg Rickaby](https://gregrickaby.com)
* [Matt Mullenweg](https://ma.tt)
* [Mika Epstein](https://halfelf.org)
* [Mike Garrett](https://mikengarrett.com)
* [Samuel Wood](http://ottopress.com)
* [Scott Reilly](http://coffee2code.com)
* [Jan Dembowski](https://profiles.wordpress.org/jdembowski)
* [Jeff Starr](https://perishablepress.com)
* [Jeff Chandler](https://jeffc.me)
* [Jeff Matson](https://jeffmatson.net)
* [John James Jacoby](https://jjj.blog)
* [Leland Fiegel](https://leland.me)
* [Rahul Bansal](https://profiles.wordpress.org/rahul286)
* [Roots](https://roots.io)
* [rtCamp](https://rtcamp.com)
* [Ryan Hellyer](https://geek.hellyer.kiwi)
* [WP Chat](https://wpchat.com)
* [WP Tavern](https://wptavern.com)

#### Disclaimer ####

We released this plugin in response to our managed hosting clients asking for better access to their server, and our primary goal will remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you keep the above mentioned goals in mind. Thanks!

#### Keywords ####

* Terms: ssl, https, hsts, enable, generate, force, setup, configure, enforce, 301, redirect, headers, secure, insecure, incoming, requests, browser, htaccess, apache, nginx, server, replace, filter, scan, auto, automatic, dynamic, dynamically, images, files, resources, css, js, files, static, always, encrypt, free, seo, remove, relative, internal, external, sources, sitewide, site-wide

* Phrases: 301 redirect, strict transport security, force https, force ssl, enable ssl, enable tls, http to https, fix ssl, fix https, ssl certificate, ssl redirect, http redirect, https redirect, redirect http, redirect https, automatic redirect, auto redirect, fix mixed content, fix insecure content, secure resources, mixed content errors, mixed content warnings, insecure content warnings, mixed content fixer, ssl on all pages, https on all pages, ssl htaccess, https htaccess, media library https, redirect loop, infinite loop, infinite redirect loops, static files, static resources, flexible ssl, one click, single click, http headers, browser warnings, browser errors, htaccess rules, htaccess redirect, site url, home url, lets encrypt, free ssl, duplicate content, relative urls, relative protocol, protocol relative, remove protocol, sitewide ssl, site-wide ssl

* Plugins: really simple ssl, easy https redirection, ssl insecure content fixer, one click ssl, cloudflare ssl, cloudflare flexible ssl, wp force ssl, wordpress force https, wp force https, wp ssl redirect, wp encrypt, wp ssl https enforcer, force ssl, https domain alias, remove http, http https remover, force ssl everywhere

== Installation ==

1. Upload to `/wp-content/plugins/force-https-littlebizzy`
2. Activate via WP Admin > Plugins
3. Test plugin is working by loading a non-HTTPS version of any page

== FAQ ==

= Does this plugin install SSL for my site? =

No. You will first need to order/setup SSL on your server (web host) before activating this plugin.

= After installing this plugin, my site is inaccessible? =

You probably do not have SSL installed yet on your server (web host) which is a prerequisite.

= Are there any potential drawbacks/errors with this plugin? =

The only potential error is a 404 error for external resources that do not already support HTTPS.

= Does this plugin affect my website's speed or performance? =

No, it should not. It's very lightweight and should be cached in PHP Opcache and DNS/browser (301s).

= My developer installed this for me, is he taking shortcuts? =

Mostly likely your developer wants you to be extra protected from insecure resources. This plugin can be (should be) installed as an additional layer of protection/stability even if you already redirect to HTTPS elsewhere (server, CloudFlare, etc). It does not hurt anything to force SSL in multiple places, and in fact provides better redundancy for your security. That said, installing this plugin is not a cure-all and your server (etc) should still be re-configured for SSL too when possible.

= What HTTP header codes does this plugin send to browsers? =

It generates 301 codes for any http version of any page and redirects to https version of that page.

= Does this plugin work with CloudFlare SSL? =

Yes, it can be used with CloudFlare's "flexible" or "full" SSL to avoid "too many redirects" spinning errors.

= How can I change this plugin's settings? =

Currenly no settings page exists, but we may add one in future versions.

= I have a question or comment, how can I let you know? =

Please avoid leaving negative reviews in order to get a feature implemented. Stalking or harassing our team members is also not okay; we will expose those who attempt to extort or threaten us. Instead, you may post on the public WordPress.org forums if you like and other members may be able to help you. Since this is a free plugin, we do not offer support for it; we are also no longer involved at the WordPress.org forums. We recommend joining our Facebook group instead:

[https://www.facebook.com/groups/littlebizzy/](https://www.facebook.com/groups/littlebizzy/)

== Changelog ==

= 1.0.6 =
* changed filters to force HTTPS for external resources (but not external hyperlinks) including `src`, `srcset`, `embed`, and `object`
* (if an external resource does not exist in HTTPS version, it may generate a 404 error)
* (philosophy = "green padlock" more important than a resource 404 error)
* added warning for Multisite installations
* updated recommended plugins
* updated plugin meta

= 1.0.5 =
* better support for `define('DISABLE_NAG_NOTICES', true);`

= 1.0.4 =
* updated plugin meta
* partial support for `define('DISABLE_NAG_NOTICES', true);`

= 1.0.3 =
* tested with WP 4.9
* updated plugin meta
* updated recommended plugins

= 1.0.2 =
* filter to "skip" external hyperlinks
* better HTTPS filters for internal links, internal sources, and image srcsets
* optimized plugin code
* updated recommended plugins
* added rating request

= 1.0.1 =
* added recommended plugins

= 1.0.0 =
* initial release
