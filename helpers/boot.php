<?php

// Subpackage namespace
namespace LittleBizzy\PluginNamespace\Helpers;

// Aliased namespaces
use LittleBizzy\PluginNamespace\Notices;

// Block direct calls
if (!function_exists('add_action')) {
	die;
}

/**
 * Boot class
 *
 * @package WordPress Plugin
 * @subpackage Helpers
 */
final class Boot {



	/**
	 * Single class instance
	 */
	private static $instance;



	/**
	 * Create or retrieve instance
	 */
	final public static function instance($file, $class = 'Core\Core', $method = 'instance') {

		// Check instance
		if (!isset(self::$instance)) {
			self::$instance = new self($file, $class, $method);
		}

		// Done
		return self::$instance;
	}



	/**
	 * Disallow clone use and overwriting
	 */
	final private function __clone() {}



	/**
	 * Constructor
	 */
	final private function __construct($file, $class, $method) {

		// Plugin directory
		$directory = dirname($file);

		// Load config file
		$config = @include $directory.'/config.php';
		if (!empty($config) && is_array($config)) {

			// Boot check
			require_once $directory.'/notices/boot-check-php.php';

			// Loader
			require_once $directory.'/helpers/loader.php';

			// Admin notices for PHP
			Notices\Admin_Notices_PHP::instance($file);

			// Admin Notices
			if (!empty($config['admin-notices']) && !empty($config['admin-notices']['enabled'])) {
				Notices\Admin_Notices::instance($file);
			}

			// Admin Notices Multisite check
			if (!empty($config['admin-notices-ms']) && !empty($config['admin-notices-ms']['enabled'])) {

				// Check multisite detected
				if (false !== Notices\Admin_Notices_MS::instance($file)) {

					// Check if abort in case of multisite installs
					if (!empty($config['admin-notices-ms']['abort-on-multisite'])) {
						return;
					}
				}
			}

		// No config
		} else {

			// Loader
			require_once $directory.'/helpers/loader.php';
		}

		// Run the main class
		Runner::start($class, $method);
	}



}