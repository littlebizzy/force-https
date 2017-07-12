<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com
Description: Redirects all HTTP requests to the HTTPS version and fixes all insecure static resources by implementing relative URLs without altering the database.
Version: 1.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


/* Initialization */

// Avoid script calls via plugin URL
if (!function_exists('add_action'))
	die;

// This plugin constants
define('FHTTPS_FILE', __FILE__);
define('FHTTPS_PATH', dirname(FHTTPS_FILE));
define('FHTTPS_VERSION', '1.0.0');

if (defined('FORCE_SSL') && !FORCE_SSL)
	return;

// Load main class
require_once(FHTTPS_PATH.'/core/core.php');
FHTTPS_Core::instance();