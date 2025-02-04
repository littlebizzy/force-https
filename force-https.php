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

// enforce https at the database level before wordpress processes these values
// home_url and site_url should not be in force_https_securize_url because it would run on every call unnecessarily
function force_https_filter_home( $value ) {
    return set_url_scheme( $value, 'https' );
}
// no priority needed since pre_option filters override values immediately
add_filter( 'pre_option_home', 'force_https_filter_home' );
add_filter( 'pre_option_siteurl', 'force_https_filter_home' );

// enforce https by redirecting non-ssl requests on frontend, admin, and login pages
function force_https_redirect() {
    
    // exit if already using https, headers are sent, running via cli, cron, or ajax, or no request uri exists
    if ( is_ssl() || headers_sent() || defined( 'WP_CLI' ) || defined( 'DOING_CRON' ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ! isset( $_SERVER['REQUEST_URI'] ) ) {
        return;
    }

    // fallback check for https in server if is_ssl fails
    if ( ! empty( $_SERVER['HTTPS'] ) ) {
        return;
    }

    // redirect to https version of the requested url with a permanent redirect
    wp_redirect( set_url_scheme( home_url( $_SERVER['REQUEST_URI'] ), 'https' ), 301 );
    exit;
}

// apply https redirect during initialization, admin, and login
add_action( 'init', 'force_https_redirect', 10 );
add_action( 'admin_init', 'force_https_redirect', 10 );
add_action( 'login_init', 'force_https_redirect', 10 );

// enforce https for valid urls only
function force_https_securize_url( $value ) {
    // return unchanged if not a string or does not start with http
    if ( ! is_string( $value ) || stripos( $value, 'http://' ) !== 0 ) {
        return $value;
    }

    // convert to https
    return set_url_scheme( $value, 'https' );
}

// apply https to urls used across wordpress
add_filter( 'admin_url', 'force_https_securize_url', 10 );
add_filter( 'author_feed_link', 'force_https_securize_url', 10 );
add_filter( 'category_feed_link', 'force_https_securize_url', 10 );
add_filter( 'category_link', 'force_https_securize_url', 10 );
add_filter( 'content_url', 'force_https_securize_url', 10 );
add_filter( 'embed_oembed_html', 'force_https_securize_url', 10 );
add_filter( 'get_avatar_url', 'force_https_securize_url', 10 );
add_filter( 'get_custom_logo', 'force_https_securize_url', 10 );
add_filter( 'get_the_permalink', 'force_https_securize_url', 10 );
add_filter( 'includes_url', 'force_https_securize_url', 10 );
add_filter( 'login_redirect', 'force_https_securize_url', 10 );
add_filter( 'logout_redirect', 'force_https_securize_url', 10 );
add_filter( 'nav_menu_link_attributes', 'force_https_securize_url', 10 );
add_filter( 'network_home_url', 'force_https_securize_url', 10 );
add_filter( 'network_site_url', 'force_https_securize_url', 10 );
add_filter( 'page_link', 'force_https_securize_url', 10 );
add_filter( 'plugins_url', 'force_https_securize_url', 10 );
add_filter( 'post_link', 'force_https_securize_url', 10 );
add_filter( 'rest_url', 'force_https_securize_url', 10 );
add_filter( 'tag_link', 'force_https_securize_url', 10 );
add_filter( 'term_link', 'force_https_securize_url', 10 );
add_filter( 'wp_get_attachment_url', 'force_https_securize_url', 10 );
add_filter( 'wp_logout_url', 'force_https_securize_url', 10 );

// apply https to woocommerce urls if woocommerce is active
if ( class_exists( 'WooCommerce' ) ) {
    add_filter( 'wc_get_endpoint_url', 'force_https_securize_url', 10 );
    add_filter( 'woocommerce_account_endpoint_url', 'force_https_securize_url', 10 );
    add_filter( 'woocommerce_email_footer_text', 'force_https_filter_output', 999 );
    add_filter( 'woocommerce_rest_prepare_coupon', 'force_https_filter_output', 999 );
    add_filter( 'woocommerce_rest_prepare_customer', 'force_https_filter_output', 999 );
    add_filter( 'woocommerce_rest_prepare_order', 'force_https_filter_output', 999 );
    add_filter( 'woocommerce_rest_prepare_product', 'force_https_filter_output', 999 );
}

// enforce https on html content that may contain urls
function force_https_filter_output( $content ) {
    // return unchanged if not a string
    if ( ! is_string( $content ) ) {
        return $content;
    }

    // replace http with https in text or html output
    return str_replace( 'http://', 'https://', $content );
}

// apply https enforcement to html content
add_filter( 'comment_text', 'force_https_filter_output', 20 );
add_filter( 'post_thumbnail_html', 'force_https_filter_output', 10 );
add_filter( 'render_block', 'force_https_filter_output', 20 );
add_filter( 'rest_pre_echo_response', 'force_https_filter_output', 999 );
add_filter( 'walker_nav_menu_start_el', 'force_https_filter_output', 10 );
add_filter( 'widget_text', 'force_https_filter_output', 20 );
add_filter( 'widget_text_content', 'force_https_filter_output', 20 );

// force https on essential elements with urls
add_filter( 'the_content', 'force_https_process_content', 20 );
function force_https_process_content( $content ) {
    return preg_replace_callback(
        '#(<(?:a|img|iframe|video|audio|source|form|link)\b[^>]*\s*(?:href|src|action)=["\'])http://([^"\']+)#i',
        function( $matches ) {
            return $matches[1] . 'https://' . $matches[2];
        },
        $content
    );
}

// force https inside inline script and style content
add_filter( 'the_content', 'force_https_fix_scripts_styles', 20 );
function force_https_fix_scripts_styles( $content ) {
    return preg_replace_callback(
        '#(<script\b[^>]*>|<style\b[^>]*>)(.*?)</(script|style)>#is',
        function( $matches ) {
            return $matches[1] . str_replace(['http://', 'http:\\/\\/'], ['https://', 'https:\\/\\/'], $matches[2]) . '</' . $matches[3] . '>';
        },
        $content
    );
}

// enforce https on wp resource hints to prevent mixed content issues
add_filter( 'wp_resource_hints', 'force_https_fix_resource_hints', 20 );
function force_https_fix_resource_hints( $urls ) {
    // return unchanged if not an array
    if ( ! is_array( $urls ) ) {
        return $urls;
    }

    // loop through each url and enforce https where needed
    foreach ( $urls as $key => $url ) {
        if ( is_string( $url ) ) {
            $urls[$key] = set_url_scheme( $url, 'https' );
        } elseif ( is_array( $url ) && isset( $url['href'] ) ) {
            $urls[$key]['href'] = set_url_scheme( $url['href'], 'https' );
        }
    }

    return $urls;
}

// enforce https on image srcsets to prevent mixed content issues
add_filter( 'wp_calculate_image_srcset', 'force_https_fix_image_srcsets', 999 );
function force_https_fix_image_srcsets( $sources ) {
    // return unchanged if sources is not an array
    if ( ! is_array( $sources ) ) {
        return $sources;
    }

    // loop through each source and enforce https on urls
    foreach ( $sources as $key => $source ) {
        // check if url is set and enforce https
        if ( isset( $source['url'] ) ) {
            $sources[$key]['url'] = set_url_scheme( $source['url'], 'https' );
        }
    }

    return $sources;
}

// enforce https on urls in the upload directory to avoid insecure media links
add_filter( 'upload_dir', 'force_https_fix_upload_dir', 999 );
function force_https_fix_upload_dir( $uploads ) {

    // enforce https on the main upload url
    if ( isset( $uploads['url'] ) ) {
        $uploads['url'] = set_url_scheme( $uploads['url'], 'https' );
    }

    // enforce https on the base upload url
    if ( isset( $uploads['baseurl'] ) ) {
        $uploads['baseurl'] = set_url_scheme( $uploads['baseurl'], 'https' );
    }

    return $uploads;
}

// Ref: ChatGPT
