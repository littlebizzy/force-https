<?php

/**
 * DO NOT MODIFY THE CLASS CODE!
 * Just change the [PluginNamespace] value below
 * Define the settings in the ../config.php file
 */

// Subpackage namespace
namespace LittleBizzy\PluginNamespace\Notices;

/**
 * Admin Notices PHP class
 *
 * @package WordPress Plugin
 * @subpackage Admin Notices
 */
final class Admin_Notices_PHP {



	/**
	 * Caller plugin file
	 */
	private $pluginFile;



	/**
	 * Config variables
	 */
	private $currentVersion;
	private $versionRequired;
	private $versionMessage;



	/**
	 * Single class instance
	 */
	private static $instance;



	/**
	 * Create or retrieve instance
	 */
	public static function instance($pluginFile = null) {

		// Avoid direct calls
		if (!function_exists('add_action')) {
			die;
		}

		// Check instance
		if (!isset(self::$instance)) {
			self::$instance = new self($pluginFile);
		}

		// Done
		return self::$instance;
	}



	/**
	 * Constructor
	 */
	private function __construct($pluginFile = null) {

		// Check admin and PHP version
		if (!is_admin() || !$this->outdated()) {
			return;
		}

		// Main plugin file
		$this->pluginFile = isset($pluginFile)? $pluginFile : __FILE__;

		// WP Loaded hook
		add_action('wp_loaded', [$this, 'loaded'], PHP_INT_MAX);
	}



	/**
	 * WordPress and plugins loaded
	 */
	public function loaded() {

		// Check the disable nag constant
		if ((defined('DISABLE_NAG_NOTICES') && DISABLE_NAG_NOTICES)) {
			return;
		}

		// Check previous action loaded
		if (did_action('ltbpbp_admin_notices_php')) {
			return;
		}

		// Set PBP admin notices PHP action
		do_action('ltbpbp_admin_notices_php');

		// Show the notices
		add_action('admin_notices', [$this, 'notices']);
	}



	/**
	 * Notices display
	 */
	public function notices() {

		// Current message
		$message = $this->versionMessage;

		// Replace plugin name
		$pluginData = get_plugin_data($this->pluginFile);
		$pluginName = (!empty($pluginData) && is_array($pluginData) && !empty($pluginData['Name']))? $pluginData['Name'] : basename(dirname($this->pluginFile));
		$message = str_replace('%plugin%', $pluginName, $message);

		// Replace PHP versions
		$message = str_replace('%php_current_version%', $this->currentVersion, $message);
		$message = str_replace('%php_version_required%', $this->versionRequired, $message);

		// Error display
		?><div class="notice notice-error"><p><?php echo $message; ?></p></div><?php
	}



	/**
	 * Load configuration array and check PHP version
	 */
	private function outdated() {

		// Load configuration configuration file
		$config = @include dirname(dirname(__FILE__)).'/config.php';
		if (empty($config) || !is_array($config) ||	empty($config['boot-check-php']) || !is_array($config['boot-check-php']) ||
			empty($config['boot-check-php']['enabled']) || empty($config['boot-check-php']['version-required']) || empty($config['boot-check-php']['version-message'])) {
			return false;
		}

		// Check outdated version
		if (version_compare(PHP_VERSION, $config['boot-check-php']['version-required'], '>=')) {
			return false;
		}

		// Copy variables
		$this->currentVersion 	= PHP_VERSION;
		$this->versionRequired 	= $config['boot-check-php']['version-required'];
		$this->versionMessage 	= $config['boot-check-php']['version-message'];

		// Outdated
		return true;
	}



}