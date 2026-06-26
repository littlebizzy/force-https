# Force HTTPS

HTTPS enforcement for WordPress

## Changelog

## 3.0.6
- removed redundant WooCommerce REST prepare filters now covered by global REST response HTTPS filtering

## 3.0.5
- fixed oEmbed and custom logo HTTPS filtering by using the HTML output callback for HTML-returning hooks
- improved REST response HTTPS filtering for nested string values inside arrays

## 3.0.4
- registered WooCommerce filters on `plugins_loaded` so HTTPS support is not missed due to plugin load order

## 3.0.3
- fixed nav menu link HTTPS filtering by using a dedicated callback for the attributes array passed by `nav_menu_link_attributes`

## 3.0.2
- hardened HTTPS redirects by replacing `wp_redirect` with `wp_safe_redirect`
- sanitized the request URI before building the HTTPS redirect URL
- updated `Tested up to` header for WordPress 7.0

## 3.0.1
- improved WP-CLI and WP-Cron compatibility by bypassing home/siteurl HTTPS filtering during command-line and scheduled tasks

## 3.0.0
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