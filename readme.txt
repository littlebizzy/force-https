=== Force HTTPS ===

Contributors: littlebizzy
Tags: https, ssl, http, force, redirect, lock, certificate, easy, quick, tls
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources by implementing relative URLs without altering the database.

== Description ==

Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources by implementing relative URLs without altering the database.

Compatibility:

* Meant for Linux servers
* Minimum PHP version: 5.5
* Designed for: PHP 7+ and MySQL 5.7+
* Can be used as a "Must Use" plugin (mu-plugins)

Future plugin goals:

* Localization (translation support)
* Transient experimentation
* More features (based on user suggestions)
* Code maintenance/improvements

Code inspiration:

* n/a

*NOTE: We released this plugin in response to our managed hosting clients asking for better access to their server environment, and our primary goal will likely remain supporting that purpose. Although we are 100% open to fielding requests from the WordPress community, we kindly ask that you consider all of the above mentioned goals before leaving reviews of this plugin, thanks!*


== Installation ==

1. Upload the plugin files to `/wp-content/plugins/`
2. Activate the plugin within the WP Admin
3. Visit http version of any page, and it should 301 redirect to https version


== Frequently Asked Questions ==

= What HTTP header codes does this plugin send? =

It generates 301 codes for any http version of any page and redirects to https version of that page.

= Does this plugin work with CloudFlare SSL? =

Yes, it can be used with either CloudFlare's "flexible" or "full" SSL setting to avoid any spinning or errors.

= How can I change this plugin's settings? =

Currenly no settings page exists, but we may add one in future versions.

= I have a suggestion, how can I let you know? =

Please avoid leaving negative reviews in order to get a feature implemented. Instead, we kindly ask that you post your feedback on the wordpress.org support forums by tagging this plugin in your post. If needed, you may also contact our homepage.


== Changelog ==

= 1.0.0 =
* initial release
