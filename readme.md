# Force HTTPS

HTTPS enforcement for WordPress

## What it does

Force HTTPS redirects normal non-SSL web requests to the HTTPS version of the requested WordPress URL while avoiding WP-CLI, WP-Cron, AJAX requests, already-sent headers, and missing request URI edge cases.

Force HTTPS also makes WordPress-generated URLs use HTTPS across core URL helpers, permalinks, feeds, admin/login/logout URLs, media URLs, enqueued scripts and styles, theme URLs, upload directory URLs, image srcsets, resource hints, rendered content, widgets, comments, navigation output, oEmbed/custom logo HTML, REST response data, and supported WooCommerce URL/content filters.

For core `home` and `siteurl` values, Force HTTPS supports both SlickStack and non-SlickStack servers. When `WP_HOME` or `WP_SITEURL` are hardcoded in `wp-config.php`, the plugin uses early `pre_option_home` and `pre_option_siteurl` filtering only when those values resolve to `https://`, including `http://` values that can be safely upgraded. When those constants are not defined or do not resolve to HTTPS, the plugin lets WordPress retrieve the database-backed values normally, then upgrades them through `option_home` and `option_siteurl` filters at runtime.

This approach does not write to the database. It only normalizes the runtime values WordPress uses during normal web requests, while continuing to bypass WP-CLI and WP-Cron to avoid interfering with command-line tasks or internal scheduled jobs.

## What it doesn't do

Force HTTPS does not try to detect HTTPS by trusting proxy, CDN, or load balancer request headers such as `X-Forwarded-Proto`, `X-Forwarded-SSL`, or Cloudflare-specific headers.

Those headers can be useful on correctly configured reverse-proxy setups, but they are only safe when the server or proxy layer strips untrusted incoming values and overwrites them from a trusted source. A WordPress plugin cannot reliably know whether those headers came from a trusted proxy or were spoofed by a direct request to the origin server, so this plugin intentionally avoids using them as a source of truth.

If a site runs behind Cloudflare, another CDN, a load balancer, or a reverse proxy, HTTPS detection should be handled in the server, hosting, CDN, or `wp-config.php` configuration where the trust boundary can be controlled. This plugin focuses on enforcing HTTPS across WordPress-generated URLs, redirects, content, assets, REST output, and supported plugin URL filters after WordPress and the server environment report the request state.

Force HTTPS also does not add HSTS headers. HSTS is best managed at the server or CDN level because it can affect the entire domain and subdomains beyond WordPress.

## Changelog

### 4.0.0
- improved core `home` and `siteurl` HTTPS handling for both SlickStack and non-SlickStack servers
- added safe `WP_HOME` and `WP_SITEURL` support in early `pre_option_home` and `pre_option_siteurl` filters without assuming those constants exist
- tightened early core URL option validation so constants and existing pre-option values only short-circuit when they resolve to `https://` URLs
- added `option_home` and `option_siteurl` fallback filters for database-backed installs without hardcoded constants
- kept WP-CLI and WP-Cron bypasses for core URL option filtering

### 3.0.10
- added REST response object HTTPS filtering via `rest_post_dispatch` while keeping final REST output filtering as a safety net

### 3.0.9
- hardened resource hint, image srcset, and upload directory HTTPS filtering with stricter URL checks

### 3.0.8
- added HTTPS filtering for author, archive, attachment, custom post type, feed, search, shortlink, and logout URLs
- fixed stale WordPress and WooCommerce filter registrations by using current hook names

### 3.0.7
- added HTTPS filtering for enqueued script and stylesheet asset URLs
- added HTTPS filtering for theme root, theme directory, and theme file URLs

### 3.0.6
- removed redundant WooCommerce REST prepare filters now covered by global REST response HTTPS filtering

### 3.0.5
- fixed oEmbed and custom logo HTTPS filtering by using the HTML output callback for HTML-returning hooks
- improved REST response HTTPS filtering for nested string values inside arrays

### 3.0.4
- registered WooCommerce filters on `plugins_loaded` so HTTPS support is not missed due to plugin load order

### 3.0.3
- fixed nav menu link HTTPS filtering by using a dedicated callback for the attributes array passed by `nav_menu_link_attributes`

### 3.0.2
- hardened HTTPS redirects by replacing `wp_redirect` with `wp_safe_redirect`
- sanitized the request URI before building the HTTPS redirect URL
- updated `Tested up to` header for WordPress 7.0

### 3.0.1
- improved WP-CLI and WP-Cron compatibility by bypassing home/siteurl HTTPS filtering during command-line and scheduled tasks

### 3.0.0
- added `Tested up to`, `Update URI`, and `Text Domain` plugin headers
- improved HTTPS redirects to skip WP-CLI, WP-Cron, and AJAX requests
- added early home/siteurl HTTPS filtering with `pre_option_home` and `pre_option_siteurl`
- expanded HTTPS enforcement across WordPress URL, content, media, resource hint, and upload directory filters
- improved content parsing for HTML elements, including `<script>` and `<style>` blocks
- added WooCommerce URL/content filter support when WooCommerce is active
- cleaned up plugin structure, syntax, and compatibility

### 2.0.3
- added `Requires PHP` plugin header

### 2.0.2
- improved `gu_override_dot_org` snippet

### 2.0.1
- fixed `gu_override_dot_org` snippet

### 2.0.0
- completely refactored code to WordPress standards
- no more defined constants or options (hardcoded to enforce HTTPS on all internal/external links and resources)
- much more extensive `add_filter` rules and HTML enforcement of HTTPS
- supports PHP 7.0 to 8.3
- supports Multisite

### 1.4.3
* fixed undefined variable error (new default $modified = false)

### 1.4.2
* improved composer.json
* updated metadata

### 1.4.1
* tested with WP 5.1
* updated metadata
* tweaked `composer.json`

### 1.4.0
* PBP v1.2.0
* removed `FORCE_SSL` constant references
* added support to force HTTPS on `source` elements (previously unsupported) ... this fixes GitHub Issue #7
* late support for new FORCE_HTTPS defined constant
* define('FORCE_HTTPS', true);
* define('FORCE_HTTPS_EXTERNAL_LINKS', false);
* define('FORCE_HTTPS_EXTERNAL_RESOURCES', true);
* define('FORCE_HTTPS_INTERNAL_LINKS', true);
* define('FORCE_HTTPS_INTERNAL_RESOURCES', true);

### 1.3.0
* PBP v1.1.0
* tested with PHP 7.0, 7.1, 7.2
* tested with PHP 5.6 (no fatal errors only, tweaked code style and several corrections)
* better support for WP-CLI (fixes GitHub Issue #6/#2)
* simplified plugin class organization
* late support for FORCE_SSL constant aborting the plugin functionality in the last minute if false

### 1.2.0
* tested with WP 5.0

### 1.1.4
* updated metadata

### 1.1.3
* updated recommended plugins

### 1.1.2
* updated metadata

### 1.1.1
* updated metadata
* updated recommended plugins

### 1.1.0
* versioning correction (major changes in 1.0.6)
* (no code changes)

### 1.0.6
* changed filters to force HTTPS for external resources (but not hyperlinks) including `src`, `srcset`, `embed`, and `object`
* (if an external resource does not exist in HTTPS version, it may generate a 404 error)
* (philosophy = "green padlock" more important than a resource 404 error)
* added warning for Multisite installations
* updated recommended plugins

### 1.0.5
* better support for `DISABLE_NAG_NOTICES`

### 1.0.4
* partial support for `DISABLE_NAG_NOTICES`
* updated metadata

### 1.0.3
* tested with WP 4.9
* updated recommended plugins
* updated metadata

### 1.0.2
* filter to "skip" external hyperlinks
* better HTTPS filters for internal links, internal sources, and image `srcset`
* optimized plugin code
* added rating request notice
* updated recommended plugins

### 1.0.1
* added recommended plugins notice

### 1.0.0
* initial release
