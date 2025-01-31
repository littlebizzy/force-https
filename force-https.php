<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com/plugins/force-https
Description: HTTPS enforcement for WordPress
Version: 3.0.0
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

// enforce https dynamically via filters (does not modify database)
function force_https_filter_home( $value ) {
    return set_url_scheme( $value, 'https' );
}
add_filter( 'pre_option_home', 'force_https_filter_home' );
add_filter( 'pre_option_siteurl', 'force_https_filter_home' );

// enforce https by redirecting non-ssl requests on frontend, admin, and login pages
function force_https_redirect() {
    
    // exit if already using https, headers are sent, or running via cli
    if ( is_ssl() || headers_sent() || defined( 'WP_CLI' ) ) {
        return;
    }

    // redirect to https version of the requested url with a permanent redirect
    wp_redirect( set_url_scheme( home_url( $_SERVER['REQUEST_URI'] ), 'https' ), 301 );
    exit;
}

// apply https redirect during initialization, admin, and login
foreach ( [ 'init', 'admin_init', 'login_init' ] as $hook ) {
    add_action( $hook, 'force_https_redirect', 10 );
}

// enforce https on valid urls and replace http in text content
function force_https_securize_url( $value ) {

    // return original if not a string
    if ( ! is_string( $value ) ) {
        return $value;
    }

    // enforce https for valid urls
    $secure_value = set_url_scheme( $value, 'https' );

    // return if url scheme was changed (valid URL handled)
    if ( $secure_value !== $value ) {
        return $secure_value;
    }

    // replace http with https in text or html content only if needed
    return ( strpos( $value, 'http://' ) !== false ) ? str_replace( 'http://', 'https://', $value ) : $value;
}

// apply https to all relevant wordpress filters  
add_filter( 'admin_url', 'force_https_securize_url', 999 );
add_filter( 'do_shortcode_tag', 'force_https_securize_url', 999 );
add_filter( 'embed_oembed_html', 'force_https_securize_url', 999 );
add_filter( 'get_avatar_url', 'force_https_securize_url', 999 );
add_filter( 'get_custom_logo', 'force_https_securize_url', 999 );
add_filter( 'home_url', 'force_https_securize_url', 999 );
add_filter( 'includes_url', 'force_https_securize_url', 999 );
add_filter( 'login_redirect', 'force_https_securize_url', 999 );
add_filter( 'logout_redirect', 'force_https_securize_url', 999 );
add_filter( 'network_home_url', 'force_https_securize_url', 999 );
add_filter( 'network_site_url', 'force_https_securize_url', 999 );
add_filter( 'page_link', 'force_https_securize_url', 999 );
add_filter( 'post_link', 'force_https_securize_url', 999 );
add_filter( 'rest_url', 'force_https_securize_url', 999 );
add_filter( 'script_loader_src', 'force_https_securize_url', 999 );
add_filter( 'site_url', 'force_https_securize_url', 999 );
add_filter( 'stylesheet_directory_uri', 'force_https_securize_url', 999 );
add_filter( 'style_loader_src', 'force_https_securize_url', 999 );
add_filter( 'template_directory_uri', 'force_https_securize_url', 999 );
add_filter( 'term_link', 'force_https_securize_url', 999 );
add_filter( 'widget_text', 'force_https_securize_url', 999 );
add_filter( 'widget_text_content', 'force_https_securize_url', 999 );
add_filter( 'wp_get_attachment_url', 'force_https_securize_url', 999 );
add_filter( 'wp_redirect', 'force_https_securize_url', 999 );

// force https on all elements and attributes with urls
add_filter( 'the_content', 'force_https_process_content', 20 );
function force_https_process_content( $content ) {
    return preg_replace_callback(
        '#(<(?:a|area|audio|blockquote|button|canvas|del|embed|form|iframe|img|input|ins|link|meta|object|picture|q|script|source|style|svg|track|video)[^>]+\s(?:action|background|cite|classid|codebase|content|data-[^\s=]+|formaction|href|longdesc|manifest|ping|poster|src|srcdoc|srcset|style|usemap|xlink:href)=["\'])(http://|//)([^"\']+)#i',
        function( $matches ) {
            return $matches[1] . 'https://' . $matches[3];
        },
        $content
    );
}

// enforce https for custom menus
add_filter( 'nav_menu_link_attributes', 'force_https_fix_menu_links', 999 );
function force_https_fix_menu_links( $atts ) {
    if ( isset( $atts['href'] ) ) {
        $atts['href'] = set_url_scheme( $atts['href'], 'https' );
    }
    return $atts;
}

// enforce https for wp resource hints
add_filter( 'wp_resource_hints', 'force_https_fix_resource_hints', 999 );
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
add_filter( 'wp_calculate_image_srcset', 'force_https_fix_image_srcsets', 999 );
function force_https_fix_image_srcsets( $sources ) {

    // exit if sources is not an array
    if ( ! is_array( $sources ) ) {
        return $sources;
    }

    // loop through each image source and enforce https
    foreach ( $sources as &$source ) {
        if ( isset( $source['url'] ) ) {
            $source['url'] = set_url_scheme( $source['url'], 'https' );
        }
    }

    return $sources;
}

// ensure all urls in the upload directory use https
add_filter( 'upload_dir', 'force_https_fix_upload_dir', 999 );
function force_https_fix_upload_dir( $uploads ) {
    if ( isset( $uploads['url'] ) ) {
        $uploads['url'] = set_url_scheme( $uploads['url'], 'https' );
    }
    if ( isset( $uploads['baseurl'] ) ) {
        $uploads['baseurl'] = set_url_scheme( $uploads['baseurl'], 'https' );
    }
    return $uploads;
}

// Ref: ChatGPT
