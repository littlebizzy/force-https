=== Force HTTPS ===

Contributors: littlebizzy
Donate link: https://www.patreon.com/littlebizzy
Tags: force, https, ssl, insecure, content
Requires at least: 4.4
Tested up to: 5.1
Requires PHP: 7.2
Multisite support: No
Stable tag: 1.4.1
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: FHTTPS

Redirects all HTTP requests to the HTTPS version and fixes insecure links and resources without altering the database (also works with CloudFlare).

== Description ==

*WARNING: You must have an SSL certificate installed on your server before activating this plugin. If you website becomes inaccessible after activation, simply login via SFTP and delete this plugin from `/wp-content/plugins/` and clear your browser cache, then refresh the page.*

* the only Force SSL (HTTPS) plugin that correctly avoids protocol-relative hyperlinks and resources as recommended by the Google Chrome team and top internet security experts!
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

== Frequently Asked Questions ==

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

== Changelog ==

= 1.4.1 =
* tested with WP 5.1
* updated plugin meta
* tweaked Composer meta

= 1.4.0 =
* PBP v1.2.0
* removed FORCE_SSL constant references
* added support to force HTTPS on `source` elements (previously unsupported) ... this fixes GitHub Issue #7
* late support for new FORCE_HTTPS defined constant
* define('FORCE_HTTPS', true);
* define('FORCE_HTTPS_EXTERNAL_LINKS', false);
* define('FORCE_HTTPS_EXTERNAL_RESOURCES', true);
* define('FORCE_HTTPS_INTERNAL_LINKS', true);
* define('FORCE_HTTPS_INTERNAL_RESOURCES', true);

= 1.3.0 =
* PBP v1.1.0
* tested with PHP 7.0
* tested with PHP 7.1
* tested with PHP 7.2
* tested with PHP 5.6 (no fatal errors only, tweaked code style and several corrections)
* better support for WP-CLI (fixes GitHub Issue #6/#2)
* simplified plugin class organization
* late support for FORCE_SSL constant aborting the plugin functionality in the last minute if false

= 1.2.0 =
* tested with WP 5.0

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
* changed filters to force HTTPS for external resources (but not hyperlinks) including `src`, `srcset`, `embed`, and `object`
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
