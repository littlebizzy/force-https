<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com/plugins/force-https
Description: HTTPS enforcement for WordPress
Version: 2.1.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
Requires PHP: 7.0
Tested up to: 6.7
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Update URI: false
GitHub Plugin URI: littlebizzy/force-https
Primary Branch: master
Text Domain: force-https
*/

// prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// disable wordpress.org updates for this plugin
add_filter( 'gu_override_dot_org', function( $overrides ) {
    $overrides[] = 'force-https/force-https.php';
    return $overrides;
}, 999 );

// force https on frontend requests
add_action( 'init', 'force_https_redirect_non_https', 10 );
function force_https_redirect_non_https() {
    if ( ! is_ssl() && ! is_admin() && PHP_SAPI !== 'cli' ) {
        if ( ! headers_sent() ) {
            wp_safe_redirect( set_url_scheme( home_url( $_SERVER['REQUEST_URI'] ), 'https' ), 301 );
            exit;
        }
    }
}

// enforce https on all urls by replacing http with https
function force_https_securize_url( $url ) {
    return set_url_scheme( $url, 'https' );
}

// apply https to all relevant wordpress filters
add_filter( 'script_loader_src', 'force_https_securize_url', 20 );
add_filter( 'style_loader_src', 'force_https_securize_url', 20 );
add_filter( 'wp_get_attachment_url', 'force_https_securize_url', 20 );
add_filter( 'the_permalink', 'force_https_securize_url', 20 );
add_filter( 'post_link', 'force_https_securize_url', 20 );
add_filter( 'page_link', 'force_https_securize_url', 20 );
add_filter( 'term_link', 'force_https_securize_url', 20 );
add_filter( 'home_url', 'force_https_securize_url', 20 );
add_filter( 'site_url', 'force_https_securize_url', 20 );
add_filter( 'network_site_url', 'force_https_securize_url', 20 );
add_filter( 'network_home_url', 'force_https_securize_url', 20 );
add_filter( 'template_directory_uri', 'force_https_securize_url', 20 );
add_filter( 'stylesheet_directory_uri', 'force_https_securize_url', 20 );
add_filter( 'get_avatar_url', 'force_https_securize_url', 20 );
add_filter( 'rest_url', 'force_https_securize_url', 20 );

// ensure all urls in the upload directory use https
add_filter( 'upload_dir', 'force_https_fix_upload_dir', 20 );
function force_https_fix_upload_dir( $uploads ) {
    $uploads['url'] = set_url_scheme( $uploads['url'], 'https' );
    $uploads['baseurl'] = set_url_scheme( $uploads['baseurl'], 'https' );
    return $uploads;
}

// Force HTTPS on all elements and attributes with URLs.
add_filter( 'the_content', 'force_https_process_content', 20 );
function force_https_process_content( $content ) {
    return preg_replace_callback(
        '#(<(?:a|area|audio|blockquote|button|canvas|del|embed|form|iframe|img|input|ins|link|meta|object|picture|q|script|source|style|svg|track|video)[^>]+(?:@font-face|action|background|background-image|cite|classid|codebase|content|data-[^\s=]+|fetch|font-face|formaction|href|longdesc|manifest|ping|poster|src|srcdoc|srcset|style|url|usemap|video|xlink:href)=["\'])(http://|//)([^"\']+)#i',
        function( $matches ) {
            // Convert protocol-relative URLs like //example.com to https.
            if ( strpos( $matches[2], '//' ) === 0 ) {
                return $matches[1] . 'https://' . $matches[3];
            }

            // Convert all http URLs to https.
            return $matches[1] . 'https://' . $matches[3];
        },
        $content
    );
}

// enforce https for text widget content (for older wordpress versions)
add_filter( 'widget_text', 'force_https_fix_widget_text', 20 );
function force_https_fix_widget_text( $content ) {
    return set_url_scheme( $content, 'https' );
}

// enforce https for text widget content (for newer wordpress versions)
add_filter( 'widget_text_content', 'force_https_fix_widget_text_content', 20 );
function force_https_fix_widget_text_content( $content ) {
    return set_url_scheme( $content, 'https' );
}

// apply https to all urls in custom menus
add_filter( 'nav_menu_link_attributes', 'force_https_fix_menu_links', 20 );
function force_https_fix_menu_links( $atts ) {
    if ( isset( $atts['href'] ) ) {
        $atts['href'] = set_url_scheme( $atts['href'], 'https' );
    }
    return $atts;
}

// enforce https for oembed urls
add_filter( 'embed_oembed_html', 'force_https_fix_oembed_html', 20 );
function force_https_fix_oembed_html( $html ) {
    return set_url_scheme( $html, 'https' );
}

// enforce https for any urls used in shortcodes
add_filter( 'do_shortcode_tag', 'force_https_fix_shortcode_urls', 20 );
function force_https_fix_shortcode_urls( $output ) {
    return set_url_scheme( $output, 'https' );
}

// enforce https on wp_resource_hints
add_filter( 'wp_resource_hints', 'force_https_fix_resource_hints', 20 );
function force_https_fix_resource_hints( $urls ) {
    if ( is_array( $urls ) ) {
        foreach ( $urls as $key => $url ) {
            if ( is_array( $url ) && isset( $url['href'] ) ) {
                $urls[ $key ]['href'] = set_url_scheme( $url['href'], 'https' );
            } elseif ( is_string( $url ) ) {
                $urls[ $key ] = set_url_scheme( $url, 'https' );
            }
        }
    }
    return $urls;
}

// enforce https on attachment metadata
add_filter( 'wp_get_attachment_metadata', 'force_https_fix_attachment_metadata', 20 );
function force_https_fix_attachment_metadata( $data ) {
    if ( isset( $data['file'] ) ) {
        $data['file'] = set_url_scheme( $data['file'], 'https' );
    }
    if ( isset( $data['sizes'] ) && is_array( $data['sizes'] ) ) {
        foreach ( $data['sizes'] as &$size ) {
            if ( isset( $size['file'] ) ) {
                $size['file'] = set_url_scheme( $size['file'], 'https' );
            }
        }
    }
    return $data;
}

// enforce https on image srcsets
add_filter( 'wp_calculate_image_srcset', 'force_https_fix_image_srcsets', 20 );
function force_https_fix_image_srcsets( $sources ) {
    foreach ( $sources as &$source ) {
        if ( isset( $source['url'] ) ) {
            $source['url'] = set_url_scheme( $source['url'], 'https' );
        }
    }
    return $sources;
}

// enforce https on custom logo html
add_filter( 'get_custom_logo', 'force_https_fix_custom_logo', 20 );
function force_https_fix_custom_logo( $html ) {
    return set_url_scheme( $html, 'https' );
}

// enforce https for login/logout redirect urls
add_filter( 'login_redirect', 'force_https_securize_url', 20 );
add_filter( 'logout_redirect', 'force_https_securize_url', 20 );

// ensure redirects are https
add_filter( 'wp_redirect', 'force_https_securize_url', 20 );

// Ref: ChatGPT
