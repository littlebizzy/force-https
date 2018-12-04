<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com/plugins/force-https
Description: Redirects all HTTP requests to the HTTPS version and fixes insecure links and resources without altering the database (also works with CloudFlare).
Version: 1.2.1
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Prefix: FHTTPS
*/

// Block direct calls
if (!function_exists('add_action')) {
	die;
}

// Admin Notices module
require_once dirname(__FILE__).'/admin-notices.php';
FHTTPS_Admin_Notices::instance(__FILE__);

/**
 * Admin Notices Multisite check
 * Uncomment //return to disable this plugin on Multisite installs
 */
require_once dirname(__FILE__).'/admin-notices-ms.php';
if (false !== \LittleBizzy\ForceHTTPS\Admin_Notices_MS::instance(__FILE__)) {
	//return;
}

// Plugin constants
define('FHTTPS_FILE', __FILE__);
define('FHTTPS_PATH', dirname(FHTTPS_FILE));
define('FHTTPS_VERSION', '1.2.0');

// Check if intentionally disabled
if (defined('FORCE_SSL') && !FORCE_SSL) {
	return;
}

// Exit on WP-CLI context
if (defined('WP_CLI') && WP_CLI) {
	return;
}

// Load main class
require_once(FHTTPS_PATH.'/core/core.php');
FHTTPS_Core::instance();
