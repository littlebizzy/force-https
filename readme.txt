=== Force HTTPS (SSL Redirect & Fix Insecure Content) ===

Contributors: littlebizzy
Donate link: https://www.patreon.com/littlebizzy
Tags: force, https, ssl, insecure, content
Requires at least: 4.4
Tested up to: 4.9
Requires PHP: 7.0
Multisite support: No
Stable tag: 1.1.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: FHTTPS

Redirects all HTTP requests to the HTTPS version and fixes insecure links and resources without altering the database (also works with CloudFlare).

== Description ==

Redirects all HTTP requests to the HTTPS version and fixes insecure links and resources without altering the database (also works with CloudFlare).

* [**Join our FREE Facebook group for support**](https://www.facebook.com/groups/littlebizzy/)
* [**Worth a 5-star review? Thank you!**](https://wordpress.org/support/plugin/force-https-littlebizzy/reviews/?rate=5#new-post)
* [Plugin Homepage](https://www.littlebizzy.com/plugins/force-https)
* [Plugin GitHub](https://github.com/littlebizzy/force-https)
* [SlickStack](https://slickstack.io)

#### The Long Version ####

*WARNING: You must have an SSL certificate installed on your server before activating this plugin. If you website becomes inaccessible after activation, simply login via SFTP and delete this plugin from `/wp-content/plugins/` and clear your browser cache, then refresh the page.*

The only Force SSL (HTTPS) plugin that correctly avoids protocol-relative hyperlinks and resources as recommended by Google Chrome and top internet security experts!

Current features:

* 301 redirects all HTTP requests to the HTTPS version
* filters all internal resources to become secure (e.g. src="https://...")
* filters all internal hyperlinks to be become secure (e.g. href="https://...")
* filters all external resources to become secure (src, srcset, embeds, and objects)
* skips any external hyperlinks
* works with image srcsets too (Version 1.0.2+)
* no need for additional plugins to fix insecure resources
* avoids "protocol relative" URLs as recommended by top security experts [1](https://jeremywagner.me/blog/stop-using-the-protocol-relative-url), [2](https://www.paulirish.com/2010/the-protocol-relative-url/)
* zero database queries or settings pages
* huge SEO and security benefits

#### Compatibility ####

This plugin has been designed for use on LEMP (Nginx) web servers with PHP 7.2 and MySQL 5.7 to achieve best performance. All of our plugins are meant for single site WordPress installations only; for both performance and usability reasons, we highly recommend avoiding WordPress Multisite for the vast majority of projects.

Any of our WordPress plugins may also be loaded as "Must-Use" plugins by using our free [Autoloader](https://github.com/littlebizzy/autoloader) script in the `mu-plugins` directory.

#### Defined Constants ####

    `define('DISABLE_NAG_NOTICES', true);`

#### Plugin Features ####

* Parent Plugin: N/A
* Disable Nag Notices: [Yes](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices#Disable_Nag_Notices)
* Settings Page: No
* PHP Namespaces: No
* Object-Oriented Code: No
* Includes Media (images, icons, etc): No
* Includes CSS: No
* Database Storage: Yes
  * Transients: No
  * WP Options Table: Yes
  * Other Tables: No
  * Creates New Tables: No
* Database Queries: Backend Only (Options API)
* Must-Use Support: [Yes](https://github.com/littlebizzy/autoloader)
* Multisite Support: No
* Uninstalls Data: Yes

#### Special Thanks ####

[Alex Georgiou](https://www.alexgeorgiou.gr), [Automattic](https://automattic.com), [Brad Touesnard](https://bradt.ca), [Daniel Auener](http://www.danielauener.com), [Delicious Brains](https://deliciousbrains.com), [Greg Rickaby](https://gregrickaby.com), [Matt Mullenweg](https://ma.tt), [Mika Epstein](https://halfelf.org), [Mike Garrett](https://mikengarrett.com), [Samuel Wood](http://ottopress.com), [Scott Reilly](http://coffee2code.com), [Jan Dembowski](https://profiles.wordpress.org/jdembowski), [Jeff Starr](https://perishablepress.com), [Jeff Chandler](https://jeffc.me), [Jeff Matson](https://jeffmatson.net), [Jeremy Wagner](https://jeremywagner.me), [John James Jacoby](https://jjj.blog), [Leland Fiegel](https://leland.me), [Luke Cavanagh](https://github.com/lukecav), [Mike Jolley](https://mikejolley.com), [Pau Iglesias](https://pauiglesias.com), [Paul Irish](https://www.paulirish.com), [Rahul Bansal](https://profiles.wordpress.org/rahul286), [Roots](https://roots.io), [rtCamp](https://rtcamp.com), [Ryan Hellyer](https://geek.hellyer.kiwi), [WP Chat](https://wpchat.com), [WP Tavern](https://wptavern.com)

#### Disclaimer ####

We released this plugin in response to our managed hosting clients asking for better access to their server, and our primary goal will remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you keep the above-mentioned goals in mind, and refrain from slandering, threatening, or harassing our team members... thank you!

#### Search Keywords ####

ssl, https, hsts, enable, generate, force, setup, configure, enforce, 301, redirect, headers, secure, insecure, incoming, requests, browser, htaccess, apache, nginx, server, replace, filter, scan, auto, automatic, dynamic, dynamically, images, files, resources, css, js, files, static, always, encrypt, free, seo, remove, relative, internal, external, sources, sitewide, site-wide, 301 redirect, strict transport security, force https, force ssl, enable ssl, enable tls, http to https, fix ssl, fix https, ssl certificate, ssl redirect, http redirect, https redirect, redirect http, redirect https, automatic redirect, auto redirect, fix mixed content, fix insecure content, secure resources, mixed content errors, mixed content warnings, insecure content warnings, mixed content fixer, ssl on all pages, https on all pages, ssl htaccess, https htaccess, media library https, redirect loop, infinite loop, infinite redirect loops, static files, static resources, flexible ssl, one click, single click, http headers, browser warnings, browser errors, htaccess rules, htaccess redirect, site url, home url, lets encrypt, free ssl, duplicate content, relative urls, relative protocol, protocol relative, remove protocol, sitewide ssl, site-wide ssl, really simple ssl, easy https redirection, ssl insecure content fixer, one click ssl, cloudflare ssl, cloudflare flexible ssl, wp force ssl, wordpress force https, wp force https, wp ssl redirect, wp encrypt, wp ssl https enforcer, force ssl, https domain alias, remove http, http https remover, force ssl everywhere

== Installation ==

1. Upload to `/wp-content/plugins/force-https-littlebizzy`
2. Activate via WP Admin > Plugins
3. Test plugin is working:

Load a non-HTTPS version of any page, and it should be automatically redirected to the HTTPS version. In addition, most if not all insecure links and resources should now be loaded over HTTPS, regardless of original code.

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

= 1.1.4 =
* updated plugin meta

= 1.1.3 =
* updated recommended plugins

= 1.1.2 =
* updated plugin meta

= 1.1.1 =
* updated plugin meta
* updated recommended plugins

= 1.1.0 =
* versioning correction (major changes in 1.0.6)
* (no code changes)

= 1.0.6 =
* changed filters to force HTTPS for external resources (but not external hyperlinks) including `src`, `srcset`, `embed`, and `object`
* (if an external resource does not exist in HTTPS version, it may generate a 404 error)
* (philosophy = "green padlock" more important than a resource 404 error)
* added warning for Multisite installations
* updated recommended plugins
* updated plugin meta

= 1.0.5 =
* better support for `DISABLE_NAG_NOTICES`

= 1.0.4 =
* partial support for `DISABLE_NAG_NOTICES`
* updated plugin meta

= 1.0.3 =
* tested with WP 4.9
* updated recommended plugins
* updated plugin meta

= 1.0.2 =
* filter to "skip" external hyperlinks
* better HTTPS filters for internal links, internal sources, and image srcsets
* optimized plugin code
* added rating request notice
* updated recommended plugins

= 1.0.1 =
* added recommended plugins notice

= 1.0.0 =
* initial release
* tested with PHP 7.0
