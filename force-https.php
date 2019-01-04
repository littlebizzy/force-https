<?php
/*
Plugin Name: Force HTTPS
Plugin URI: https://www.littlebizzy.com/plugins/force-https
Description: Redirects all HTTP requests to the HTTPS version and fixes insecure links and resources without altering the database (also works with CloudFlare).
Version: 1.3.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
PBP Version: 1.1.0
WC requires at least: 3.3
WC tested up to: 3.5
Prefix: FHTTPS
*/

// Plugin namespace
namespace LittleBizzy\ForceHTTPS;

// Plugin constants
const FILE = __FILE__;
const PREFIX = 'fhttps';
const VERSION = '1.3.0';

// Boot
require_once dirname(FILE).'/helpers/boot.php';
Helpers\Boot::instance(FILE);