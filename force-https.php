<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com/plugins/force-https
Description: HTTPS enforcement for WordPress
Version: 3.0.10
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
Requires PHP: 7.0
Tested up to: 7.0
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

// enforce https at the database level only if wordpress is incorrectly detecting http
// home_url and site_url should not be in force_https_securize_url because it would run on every call unnecessarily
function force_https_filter_home( $value ) {

    // bypass if running via cli or cron to prevent issues with commands or internal loopback requests
    if ( defined( 'WP_CLI' ) || defined( 'DOING_CRON' ) ) {
        return $value;
    }

    // bypass if already using ssl
    if ( is_ssl() ) {
        return $value;
    }

    // force https scheme
    return set_url_scheme( $value, 'https' );
}

// no priority needed since pre_option filters override values immediately
add_filter( 'pre_option_home', 'force_https_filter_home' );
add_filter( 'pre_option_siteurl', 'force_https_filter_home' );

// enforce https by redirecting non-ssl requests on frontend, admin, and login pages
function force_https_redirect() {
    // exit if already using https
    if ( is_ssl() ) {
        return;
    }

    // exit if headers are sent, running via cli, cron, ajax, or if no request uri exists
    if ( headers_sent() || defined( 'WP_CLI' ) || defined( 'DOING_CRON' ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ! isset( $_SERVER['REQUEST_URI'] ) ) {
        return;
    }

    // sanitize request uri before building redirect url
    $request_uri = sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ) );

    // redirect to https version of the requested url
    wp_safe_redirect( set_url_scheme( home_url( $request_uri ), 'https' ), 301 );
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

// enforce https on nav menu links
function force_https_fix_nav_menu_link_attributes( $atts ) {
    // return unchanged if attributes are not an array
    if ( ! is_array( $atts ) ) {
        return $atts;
    }

    // enforce https on href attribute only
    if ( isset( $atts['href'] ) ) {
        $atts['href'] = force_https_securize_url( $atts['href'] );
    }

    return $atts;
}

// apply https to urls used across wordpress
add_filter( 'admin_url', 'force_https_securize_url', 10 );
add_filter( 'attachment_link', 'force_https_securize_url', 10 );
add_filter( 'author_feed_link', 'force_https_securize_url', 10 );
add_filter( 'author_link', 'force_https_securize_url', 10 );
add_filter( 'category_feed_link', 'force_https_securize_url', 10 );
add_filter( 'category_link', 'force_https_securize_url', 10 );
add_filter( 'content_url', 'force_https_securize_url', 10 );
add_filter( 'day_link', 'force_https_securize_url', 10 );
add_filter( 'embed_oembed_html', 'force_https_filter_output', 10 );
add_filter( 'feed_link', 'force_https_securize_url', 10 );
add_filter( 'get_avatar_url', 'force_https_securize_url', 10 );
add_filter( 'get_custom_logo', 'force_https_filter_output', 10 );
add_filter( 'get_shortlink', 'force_https_securize_url', 10 );
add_filter( 'includes_url', 'force_https_securize_url', 10 );
add_filter( 'login_redirect', 'force_https_securize_url', 10 );
add_filter( 'logout_redirect', 'force_https_securize_url', 10 );
add_filter( 'logout_url', 'force_https_securize_url', 10 );
add_filter( 'month_link', 'force_https_securize_url', 10 );
add_filter( 'nav_menu_link_attributes', 'force_https_fix_nav_menu_link_attributes', 10 );
add_filter( 'network_home_url', 'force_https_securize_url', 10 );
add_filter( 'network_site_url', 'force_https_securize_url', 10 );
add_filter( 'page_link', 'force_https_securize_url', 10 );
add_filter( 'plugins_url', 'force_https_securize_url', 10 );
add_filter( 'post_comments_feed_link', 'force_https_securize_url', 10 );
add_filter( 'post_link', 'force_https_securize_url', 10 );
add_filter( 'post_type_archive_link', 'force_https_securize_url', 10 );
add_filter( 'post_type_link', 'force_https_securize_url', 10 );
add_filter( 'rest_url', 'force_https_securize_url', 10 );
add_filter( 'script_loader_src', 'force_https_securize_url', 10 );
add_filter( 'search_link', 'force_https_securize_url', 10 );
add_filter( 'style_loader_src', 'force_https_securize_url', 10 );
add_filter( 'stylesheet_directory_uri', 'force_https_securize_url', 10 );
add_filter( 'tag_link', 'force_https_securize_url', 10 );
add_filter( 'template_directory_uri', 'force_https_securize_url', 10 );
add_filter( 'term_link', 'force_https_securize_url', 10 );
add_filter( 'theme_file_uri', 'force_https_securize_url', 10 );
add_filter( 'theme_root_uri', 'force_https_securize_url', 10 );
add_filter( 'wp_get_attachment_url', 'force_https_securize_url', 10 );
add_filter( 'year_link', 'force_https_securize_url', 10 );

// register woocommerce filters after plugins are loaded
add_action( 'plugins_loaded', 'force_https_register_woocommerce_filters' );
function force_https_register_woocommerce_filters() {
    // return if woocommerce is not active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // apply https to woocommerce urls and content
    add_filter( 'woocommerce_get_endpoint_url', 'force_https_securize_url', 10 );
    add_filter( 'woocommerce_email_footer_text', 'force_https_filter_output', 999 );
}

// enforce https on strings and nested arrays
function force_https_filter_value( $value ) {
    // replace http with https in strings
    if ( is_string( $value ) ) {
        return str_replace( 'http://', 'https://', $value );
    }

    // recursively process arrays
    if ( is_array( $value ) ) {
        foreach ( $value as $key => $item ) {
            $value[$key] = force_https_filter_value( $item );
        }
    }

    return $value;
}

// enforce https on html content that may contain urls
function force_https_filter_output( $content ) {
    // return unchanged if not a string
    if ( ! is_string( $content ) ) {
        return $content;
    }

    // replace http with https in text or html output
    return force_https_filter_value( $content );
}

// enforce https on rest response object data before final output
function force_https_filter_rest_response_object( $response, $server, $request ) {
    // return unchanged if response is not a wordpress http response object
    if ( ! $response instanceof WP_HTTP_Response ) {
        return $response;
    }

    // enforce https on response data only
    $response->set_data( force_https_filter_value( $response->get_data() ) );

    return $response;
}

// enforce https on rest response values
function force_https_filter_rest_response( $response ) {
    return force_https_filter_value( $response );
}

// apply https enforcement to html content
add_filter( 'comment_text', 'force_https_filter_output', 20 );
add_filter( 'post_thumbnail_html', 'force_https_filter_output', 10 );
add_filter( 'render_block', 'force_https_filter_output', 20 );
add_filter( 'rest_post_dispatch', 'force_https_filter_rest_response_object', 999, 3 );
add_filter( 'rest_pre_echo_response', 'force_https_filter_rest_response', 999 );
add_filter( 'walker_nav_menu_start_el', 'force_https_filter_output', 10 );
add_filter( 'widget_text', 'force_https_filter_output', 20 );
add_filter( 'widget_text_content', 'force_https_filter_output', 20 );

// enforce https on elements and inline content
add_filter( 'the_content', 'force_https_process_content', 20 );

function force_https_process_content( $content ) {
    // match elements with src, href, action, content, or formaction attributes
    static $element_pattern = '#(?i)(<(?:a|img|iframe|video|audio|source|form|link|embed|object|track|script|meta|input|button)\b[^>]*\s*(?:href|src|action|content|formaction)=["\'])http://([^"\']+)#';

    // match script and style content
    static $script_style_pattern = '#(<(?i:script|style)\b[^>]*>)(.*?)</(?i:script|style)>#s';

    // replace http with https in elements
    $content = preg_replace_callback(
        $element_pattern,
        function ( $matches ) {
            return $matches[1] . 'https://' . $matches[2];
        },
        $content
    );

    // replace http and escaped http in script and style blocks
    return preg_replace_callback(
        $script_style_pattern,
        function ( $matches ) {
            preg_match('/<\s*(script|style)/i', $matches[1], $tag_match);
            return $matches[1] . str_replace(
                ['http://', 'http:\/\/'],
                ['https://', 'https:\/\/'],
                $matches[2]
            ) . '</' . $tag_match[1] . '>';
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
            $urls[$key] = force_https_securize_url( $url );
        } elseif ( is_array( $url ) && isset( $url['href'] ) && is_string( $url['href'] ) ) {
            $urls[$key]['href'] = force_https_securize_url( $url['href'] );
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
        if ( isset( $source['url'] ) && is_string( $source['url'] ) ) {
            $sources[$key]['url'] = force_https_securize_url( $source['url'] );
        }
    }

    return $sources;
}

// enforce https on urls in the upload directory to avoid insecure media links
add_filter( 'upload_dir', 'force_https_fix_upload_dir', 999 );
function force_https_fix_upload_dir( $uploads ) {

    // enforce https on the main upload url
    if ( isset( $uploads['url'] ) && is_string( $uploads['url'] ) ) {
        $uploads['url'] = force_https_securize_url( $uploads['url'] );
    }

    // enforce https on the base upload url
    if ( isset( $uploads['baseurl'] ) && is_string( $uploads['baseurl'] ) ) {
        $uploads['baseurl'] = force_https_securize_url( $uploads['baseurl'] );
    }

    return $uploads;
}