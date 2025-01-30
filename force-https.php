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

// override wordpress.org with git updater
add_filter( 'gu_override_dot_org', function( $overrides ) {
    $overrides[] = 'force-https/force-https.php';
    return $overrides;
}, 999 );

// ensure siteurl and home options are always https
function force_https_fix_options() {
    // only update database if constants are not set
    if ( ! defined( 'WP_HOME' ) ) {
        update_option( 'home', set_url_scheme( get_option( 'home' ), 'https' ) );
    }

    if ( ! defined( 'WP_SITEURL' ) ) {
        update_option( 'siteurl', set_url_scheme( get_option( 'siteurl' ), 'https' ) );
    }
}
add_action( 'init', 'force_https_fix_options', 1 );

// enforce https dynamically via filters (does not modify database)
function force_https_filter_home( $value ) {
    return set_url_scheme( $value, 'https' );
}
add_filter( 'pre_option_home', 'force_https_filter_home' );
add_filter( 'pre_option_siteurl', 'force_https_filter_home' );

// force https redirect on frontend, admin, and login  
function force_https_redirect() {
    if ( ! is_ssl() && empty( $_SERVER['HTTPS'] ) && ! defined( 'WP_CLI' ) && ! headers_sent() ) {
        wp_safe_redirect( set_url_scheme( home_url( $_SERVER['REQUEST_URI'] ), 'https' ), 301 );
        exit;
    }
}

// apply https redirect to all key areas  
foreach ( array( 'init', 'admin_init', 'login_init' ) as $hook ) {  
    add_action( $hook, 'force_https_redirect', 10 );  
}

// enforce https on all urls by replacing http with https
function force_https_securize_url( $url ) {
    return set_url_scheme( $url, 'https' );
}

// apply https to all relevant wordpress filters  
add_filter( 'home_url', 'force_https_securize_url', 20 );
add_filter( 'site_url', 'force_https_securize_url', 20 );
add_filter( 'network_site_url', 'force_https_securize_url', 20 );
add_filter( 'network_home_url', 'force_https_securize_url', 20 );
add_filter( 'post_link', 'force_https_securize_url', 20 );
add_filter( 'page_link', 'force_https_securize_url', 20 );
add_filter( 'term_link', 'force_https_securize_url', 20 );
add_filter( 'template_directory_uri', 'force_https_securize_url', 20 );
add_filter( 'stylesheet_directory_uri', 'force_https_securize_url', 20 );
add_filter( 'script_loader_src', 'force_https_securize_url', 20 );
add_filter( 'style_loader_src', 'force_https_securize_url', 20 );
add_filter( 'wp_get_attachment_url', 'force_https_securize_url', 20 );
add_filter( 'get_avatar_url', 'force_https_securize_url', 20 );
add_filter( 'rest_url', 'force_https_securize_url', 20 );
add_filter( 'wp_redirect', 'force_https_securize_url', 20 );
add_filter( 'login_redirect', 'force_https_securize_url', 20 );
add_filter( 'logout_redirect', 'force_https_securize_url', 20 );
add_filter( 'embed_oembed_html', 'force_https_securize_url', 20 );
add_filter( 'do_shortcode_tag', 'force_https_securize_url', 20 );
add_filter( 'widget_text', 'force_https_securize_url', 20 );
add_filter( 'widget_text_content', 'force_https_securize_url', 20 );
add_filter( 'get_custom_logo', 'force_https_securize_url', 20 );

// force https on all elements and attributes with urls
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

// enforce https for custom menus
add_filter( 'nav_menu_link_attributes', 'force_https_fix_menu_links', 20 );
function force_https_fix_menu_links( $atts ) {
    if ( isset( $atts['href'] ) ) {
        $atts['href'] = set_url_scheme( $atts['href'], 'https' );
    }
    return $atts;
}

// enforce https for wp resource hints
add_filter( 'wp_resource_hints', 'force_https_fix_resource_hints', 20 );
function force_https_fix_resource_hints( $urls ) {
    if ( ! is_array( $urls ) ) {
        return $urls;
    }
    foreach ( $urls as &$url ) {
        if ( is_string( $url ) ) {
            $url = set_url_scheme( $url, 'https' );
        } elseif ( is_array( $url ) && isset( $url['href'] ) ) {
            $url['href'] = set_url_scheme( $url['href'], 'https' );
        }
    }
    return $urls;
}

// enforce https on image srcsets
add_filter( 'wp_calculate_image_srcset', 'force_https_fix_image_srcsets', 20 );
function force_https_fix_image_srcsets( $sources ) {
    foreach ( $sources as &$source ) {
        $source['url'] = set_url_scheme( $source['url'], 'https' );
    }
    return $sources;
}

// ensure all urls in the upload directory use https
add_filter( 'upload_dir', 'force_https_fix_upload_dir', 20 );
function force_https_fix_upload_dir( $uploads ) {
    $uploads['url'] = set_url_scheme( $uploads['url'], 'https' );
    $uploads['baseurl'] = set_url_scheme( $uploads['baseurl'], 'https' );
    return $uploads;
}

// Ref: ChatGPT
