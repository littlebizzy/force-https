<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com/plugins/force-https
Description: HTTPS enforcement for WordPress
Version: 2.0.4
Requires PHP: 7.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
GitHub Plugin URI: littlebizzy/force-https
Primary Branch: master
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

// force https redirection for all non-https requests
add_action( 'init', function() {
    if ( ! is_ssl() && ! is_admin() && php_sapi_name() !== 'cli' ) {
        if ( ! headers_sent() ) {
            $redirect_url = home_url( add_query_arg( null, null ) );
            wp_safe_redirect( set_url_scheme( $redirect_url, 'https' ), 301 );
            exit;
        }
    }
}, 10 );

// force https on all urls by replacing http:// with https://
function fhttps_securize_url( $url ) {
    if ( strpos( $url, 'http://' ) === 0 ) {
        return set_url_scheme( $url, 'https' );
    }
    return $url;
}

// apply https to all relevant wordpress filters
add_filter( 'script_loader_src', 'fhttps_securize_url', 20 );
add_filter( 'style_loader_src', 'fhttps_securize_url', 20 );
add_filter( 'wp_get_attachment_url', 'fhttps_securize_url', 20 );
add_filter( 'the_permalink', 'fhttps_securize_url', 20 );
add_filter( 'post_link', 'fhttps_securize_url', 20 );
add_filter( 'page_link', 'fhttps_securize_url', 20 );
add_filter( 'term_link', 'fhttps_securize_url', 20 );
add_filter( 'home_url', 'fhttps_securize_url', 20 );
add_filter( 'site_url', 'fhttps_securize_url', 20 );
add_filter( 'network_site_url', 'fhttps_securize_url', 20 );
add_filter( 'network_home_url', 'fhttps_securize_url', 20 );
add_filter( 'template_directory_uri', 'fhttps_securize_url', 20 );
add_filter( 'stylesheet_directory_uri', 'fhttps_securize_url', 20 );
add_filter( 'get_avatar_url', 'fhttps_securize_url', 20 );
add_filter( 'rest_url', 'fhttps_securize_url', 20 );

// ensure all urls in the upload directory use https
add_filter( 'upload_dir', function( $uploads ) {
    $uploads['url'] = set_url_scheme( $uploads['url'], 'https' );
    $uploads['baseurl'] = set_url_scheme( $uploads['baseurl'], 'https' );
    return $uploads;
} );

// apply https to all elements and attributes that can contain urls
add_filter( 'the_content', function( $content ) {
    // convert all resources and hyperlinks to https
    $content = preg_replace_callback(
        '#(<(?:img|iframe|embed|source|script|link|meta|video|audio|track|object|form|area|input|button|a)[^>]+(?:src|srcset|data-src|data-href|action|poster|content|style|href|manifest)=["\'])(http://)([^"\']+)#',
        function( $matches ) {
            return $matches[1] . 'https://' . $matches[3];
        },
        $content
    );

    // convert inline styles with url() to https
    $content = preg_replace_callback(
        '#(<[^>]+(?:style)=["\'][^>]*?url\((http://)([^"\']+)\))#',
        function( $matches ) {
            return str_replace( 'http://', 'https://', $matches[0] );
        },
        $content
    );

    return $content;
}, 20 );

// enforce https for text widget content
add_filter( 'widget_text', function( $content ) {
    return str_replace( 'http://', 'https://', $content );
}, 20 );

// enforce https for widget text content in newer wordpress versions
add_filter( 'widget_text_content', function( $content ) {
    return str_replace( 'http://', 'https://', $content );
}, 20 );

// apply https to all urls in custom menus
add_filter( 'nav_menu_link_attributes', function( $atts ) {
    if ( isset( $atts['href'] ) && strpos( $atts['href'], 'http://' ) === 0 ) {
        $atts['href'] = set_url_scheme( $atts['href'], 'https' );
    }
    return $atts;
}, 20 );

// enforce https for oembed urls
add_filter( 'embed_oembed_html', function( $html ) {
    return str_replace( 'http://', 'https://', $html );
}, 20 );

// enforce https for any urls used in shortcodes
add_filter( 'do_shortcode_tag', function( $output ) {
    return str_replace( 'http://', 'https://', $output );
}, 20 );

// enforce https on wp_resource_hints
add_filter( 'wp_resource_hints', function( $urls ) {
    if ( is_array( $urls ) ) {
        foreach ( $urls as &$url ) {
            if ( is_array( $url ) && isset( $url['href'] ) ) {
                $url['href'] = str_replace( 'http://', 'https://', $url['href'] );
            } elseif ( is_string( $url ) ) {
                $url = str_replace( 'http://', 'https://', $url );
            }
        }
    }
    return $urls;
}, 20 );

// enforce https on attachment metadata
add_filter( 'wp_get_attachment_metadata', function( $data ) {
    if ( isset( $data['file'] ) ) {
        $data['file'] = str_replace( 'http://', 'https://', $data['file'] );
    }
    if ( isset( $data['sizes'] ) ) {
        foreach ( $data['sizes'] as &$size ) {
            if ( isset( $size['file'] ) ) {
                $size['file'] = str_replace( 'http://', 'https://', $size['file'] );
            }
        }
    }
    return $data;
}, 20 );

// enforce https on image srcsets
add_filter( 'wp_calculate_image_srcset', function( $sources ) {
    foreach ( $sources as &$source ) {
        if ( isset( $source['url'] ) ) {
            $source['url'] = str_replace( 'http://', 'https://', $source['url'] );
        }
    }
    return $sources;
}, 20 );

// enforce https on custom logo html
add_filter( 'get_custom_logo', function( $html ) {
    return str_replace( 'http://', 'https://', $html );
}, 20 );

// enforce https for login/logout redirect urls
add_filter( 'login_redirect', 'fhttps_securize_url', 20 );
add_filter( 'logout_redirect', 'fhttps_securize_url', 20 );

// ensure redirects are https
add_filter( 'wp_redirect', 'fhttps_securize_url', 20 );

// Ref: ChatGPT
