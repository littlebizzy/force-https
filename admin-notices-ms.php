<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS;

/**
 * Admin Notices MultiSite class
 *
 * @package WordPress
 * @subpackage Admin Notices MultiSite
 */
final class Admin_Notices_MS {



	// Configuration
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Custom message
	 * Mark %plugin% reflects the plugin name
	 */
	private $message = 'Sorry! For performance reasons, WordPress Multisite is not supported by <strong>%plugin%</strong>. Achieve top speed and security with a <a href="https://www.littlebizzy.com/hosting?utm_source=multisite" target="_blank">dedicated Nginx VPS</a> for every site.';



	// Internal properties (do not touch from here)
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Caller plugin file
	 */
	private $plugin_file;



	/**
	 * Single class instance
	 */
	private static $instance;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Create or retrieve instance
	 */
	public static function instance($plugin_file = null) {

		// Avoid direct calls
		if (!function_exists('add_action'))
			die;

		// Single install
		if (!is_multisite())
			return false;

		// Check instance
		if (!isset(self::$instance))
			self::$instance = new self($plugin_file);

		// Done
		return self::$instance;
	}



	/**
	 * Constructor
	 */
	private function __construct($plugin_file = null) {

		// Main plugin file
		$this->plugin_file = isset($plugin_file)? $plugin_file : __FILE__;

		// Admin notices both in admin and network admin
		add_action('admin_notices', [&$this, 'adminNoticesMS']);
		add_action('network_admin_notices', [&$this, 'adminNoticesMS']);
	}



	// WP Hooks
	// ---------------------------------------------------------------------------------------------------



	/**
	 * The admin notice message
	 */
	public function adminNoticesMS() {

		$plugin_data = get_plugin_data($this->plugin_file);

		?><div class="notice notice-error">

			<p><?php echo str_replace('%plugin%', $plugin_data['Name'], $this->message); ?></p>

		</div><?php
	}



}
