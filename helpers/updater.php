<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS\Helpers;

/**
 * Updater class
 *
 * @package WordPress Plugin
 * @subpackage Helpers
 */
class Updater {



	/**
	 * Interval between update checks
	 */
	const INTERVAL_UPDATE_CHECK = 6 * 3600; // 6 hours



	/**
	 * Random time added to the cron hook to avoid multiple requests
	 */
	const INTERVAL_UPDATE_RAND = 300; // 5 minutes



	/**
	 * Plugin constants
	 */
	private $file;
	private $prefix;
	private $version;
	private $repo;



	/**
	 * Plugin directory/file key
	 */
	private $key;



	/**
	 * Namespace primary directory
	 */
	private $namespace;



	/**
	 * Constructor
	 */
	public function __construct($file, $prefix, $version, $repo) {

		// Set plugin data
		$this->file 	= $file;
		$this->prefix 	= $prefix;
		$this->version 	= $version;
		$this->repo 	= $repo;

		// Check plugin file based key
		if (false === ($this->key = $this->fileKey())) {
			return;
		}

		// Set namespace primary name
		$namespace = explode('\\', __NAMESPACE__);
		$this->namespace = strtolower($namespace[0]);

		// Set filter options for upgrades
		add_filter('upgrader_package_options', [$this, 'upgraderOptions']);

		// HTTP Request Args short-circuit
		add_filter('http_request_args', [$this, 'httpRequestArgs'], PHP_INT_MAX, 2);

		// Filters the plugin api information to display a basic readme
		add_filter('plugins_api', [$this, 'pluginsAPI'], PHP_INT_MAX, 3);

		// Check repo for scheduling
		if (!empty($this->repo)) {
			$this->scheduling();
		}
	}



	/**
	 * Handles HTTP requests looking for plugin updates
	 * and removes any reference of the current plugin
	 */
	public function httpRequestArgs($args, $url) {

		// Check args
		if (empty($args) || !is_array($args)) {
			return $args;
		}

		// Check endpoint
		if (false === strpos($url, '://api.wordpress.org/plugins/update-check/')) {
			return $args;
		}

		// Check method
		if (empty($args['method']) || 'POST' != $args['method']) {
			return $args;
		}

		// Check plugins argument
		if (empty($args['body']) || !is_array($args['body']) || empty($args['body']['plugins'])) {
			return $args;
		}

		// Check plugins list
		$data = @json_decode($args['body']['plugins'], true);
		if (empty($data) || !is_array($data)) {
			return $args;
		}

		// Plugins list
		if (!empty($data['plugins']) && is_array($data['plugins']) && isset($data['plugins'][$this->key])) {
			$modified = true;
			unset($data['plugins'][$this->key]);
		}

		// Check active plugins
		if (!empty($data['active']) && is_array($data['active']) && in_array($this->key, $data['active'])) {
			$modified = true;
			$data['active'] = array_diff($data['active'], [$this->key]);
		}

		// Modifications
		if ($modified) {

			// Set new plugins body data
			$args['body']['plugins'] = wp_json_encode($data);

			// Filter the response
			$upgrade = $this->upgrade();
			if (!empty($upgrade)) {
				add_filter('http_response', [$this, 'httpResponse'], PHP_INT_MAX, 3);
			}
		}

		// Done
		return $args;
	}



	/**
	 * Check filter response
	 */
	public function httpResponse($response, $r, $url) {

		// First remove this filter
		remove_filter('http_response', [$this, 'httpResponse'], PHP_INT_MAX);

		// Check endpoint
		if (false === strpos($url, '://api.wordpress.org/plugins/update-check/')) {
			return $response;
		}

		// Check response
		if (is_wp_error($response) || !isset($response['body'])) {
			return $response;
		}

		// Check plugin data
		$upgrade = $this->upgrade();
		if (empty($upgrade)) {
			return $response;
		}

		// Cast to array
		$payload = @json_decode($response['body'], true);
		if (empty($payload) || !is_array($payload)) {
			$payload = [];
		}

		// Check plugins
		if (empty($payload['plugins']) || !is_array($payload['plugins'])) {
			$payload['plugins'] = [];
		}

		// Set this plugin info
		$payload['plugins'][$this->key] = [
			'slug' 				=> dirname($this->key),
			'plugin' 			=> $this->key,
			'new_version' 		=> $upgrade['version'],
			'package' 			=> $upgrade['package'],
			'upgrade_notice' 	=> $upgrade['notice'],
			'icons'				=> $upgrade['icon'],
			'banners'			=> $upgrade['banner'],
			'tested'			=> $upgrade['tested'],
			'requires_php'		=> $upgrade['requires_php'],
		];

		// Back to JSON
		$response['body'] = @json_encode($payload);

		// Done
		return $response;
	}



	/**
	 * Filters the plugin API
	 */
	public function pluginsAPI($default, $action, $args) {

// No Plugins API info at the moment
return $default;

		// Check info
		if ('plugin_information' != $action) {
			return $default;
		}

		// Check arguments
		if (empty($args) || !is_object($args)) {
			return $default;
		}

		// Check slug argument
		if (empty($args->slug) || $args->slug != dirname($this->key)) {
			return $default;
		}

		// Check local data
		$upgrade = $this->upgrade();
		if (empty($upgrade) || !is_array($upgrade)) {
			return $default;
		}

		// Check readme info
		if (empty($upgrade['readme'])) {
			return $default;
		}

		// Reads and parse the readme (TODO)
		$json = false;

		// Check results
		if (empty($json) || !is_array($json)) {
			return $default;
		}

		// Prepare contributors
		$json['contributors'] = ['littlebizzy' => 'https://www.littlebizzy.com'];

		// Prepare object
		$api = (object) $json;
		$api->slug = dirname($this->key);
		$api->banner = $upgrade['banner'];
		$api->version = $upgrade['version'];
		$api->download_link = $upgrade['package'];

		// Done
		return $api;
	}



	/**
	 * Schedule update checks
	 */
	private function scheduling() {

		// Set cron hook
		$hook = $this->namespace.'_'.$this->prefix.'_update_plugin_check';
		add_action($hook, [$this, 'checkUpdates']);

		// Global timestamp option
		global $lbpbp_update_plugin_timestamps;
		if (empty($lbpbp_update_plugin_timestamps)) {
			$lbpbp_update_plugin_timestamps = [];
		}

		// Initialize
		$firstOne = false;

		// Check global update data
		if (!isset($lbpbp_update_plugin_timestamps[$this->namespace])) {

			// Retrieve global plugin timestamps data
			$lbpbp_update_plugin_timestamps[$this->namespace] = @json_decode(get_option($this->namespace.'_update_plugin_timestamps'), true);
			if (empty($lbpbp_update_plugin_timestamps[$this->namespace]) || !is_array($lbpbp_update_plugin_timestamps[$this->namespace])) {
				$lbpbp_update_plugin_timestamps[$this->namespace] = [];
			}

			// Namespace started
			$firstOne = true;
		}

		// Check last update check
		$timestamp = empty($lbpbp_update_plugin_timestamps[$this->namespace][$this->key])? 0 : (int) $lbpbp_update_plugin_timestamps[$this->namespace][$this->key];
		if (!empty($timestamp) && time() < $timestamp + self::INTERVAL_UPDATE_CHECK) {
			return;
		}

		// Update timestamp to avoid more checks
		$lbpbp_update_plugin_timestamps[$this->namespace][$this->key] = time();

		// Save only for the first one
		if ($firstOne) {
			add_action('init', [$this, 'timestamps']);
		}

		// Set scheduling
		if (!wp_next_scheduled($hook)) {
			$extra = empty($timestamp)? 15 : rand(0, self::INTERVAL_UPDATE_RAND);
			wp_schedule_single_event(time() + $extra, $hook);
		}
	}



	/**
	 * Save common timestamps option
	 */
	public function timestamps() {

		// Globals
		global $lbpbp_update_plugin_timestamps;

		// Current timestamp
		$time = time();

		// Clean outdated
		foreach ($lbpbp_update_plugin_timestamps[$this->namespace] as $key => $timestamp) {
			if ($key != $this->key && $time >= $timestamp + self::INTERVAL_UPDATE_CHECK) {
				unset($lbpbp_update_plugin_timestamps[$this->namespace][$key]);
			}
		}

		// Update once for al PBP plugins
		update_option($this->namespace.'_update_plugin_timestamps', @json_encode($lbpbp_update_plugin_timestamps[$this->namespace]), true);
	}



	/**
	 * Check for private repo plugin updates
	 */
	public function checkUpdates() {

		// Compose URL
		$url = str_replace('%repo%', trim($this->repo, '/'), 'https://raw.githubusercontent.com/%repo%/master/releases.json');

		// Request attempt
		$request = wp_remote_get($url.'?'.rand(0, 99999));
		if (empty($request) || !is_array($request) || empty($request['body'])) {
			return;
		}

		// Check response
		if (empty($request['response']) || !is_array($request['response']) ||
			empty($request['response']['code']) || '200' != $request['response']['code']) {
			return;
		}

		// Check json
		$versions = @json_decode($request['body'], true);
		if (empty($versions) || !is_array($versions)) {
			return;
		}

		// Enum json version
		foreach ($versions as $version => $info) {

			// Check basic package data
			if (empty($info['package'])) {
				continue;
			}

			// Compare first with current version
			if (empty($info) || version_compare($version, $this->version, '<=')) {
				continue;
			}

			// Add if there is a new version, or compare with registered new version (this avoid order issues)
			if (empty($greater) || version_compare($version, $greater['version'], '>')) {
				$greater = $info;
				$greater['version'] = $version;
			}
		}

		// Check update data
		if (!empty($greater)) {

			// Safe data
			$upgrade = [
				'version' 		=> $greater['version'],
				'package' 		=> $greater['package'],
				'readme' 		=> empty($greater['readme'])? 		'' : $greater['readme'],
				'notice'		=> empty($greater['notice'])? 		'' : $greater['notice'],
				'icon'			=> empty($greater['icon'])? 		'' : $greater['icon'],
				'banner'		=> empty($greater['banner'])? 		'' : $greater['banner'],
				'tested'		=> empty($greater['tested'])? 		'' : $greater['tested'],
				'requires_php' 	=> empty($greater['requires_php'])? '' : $greater['requires_php'],
			];

			// Save data
			$this->upgrade($upgrade);

			// Check current plugin info
			$current = get_site_transient('update_plugins');

			// Set this plugin data
			$current->response[$this->key] = (object) [
				'slug' 				=> dirname($this->key),
				'plugin' 			=> $this->key,
				'new_version' 		=> $upgrade['version'],
				'package' 			=> $upgrade['package'],
				'upgrade_notice' 	=> $upgrade['notice'],
				'icons'				=> $upgrade['icon'],
				'banners'			=> $upgrade['banner'],
				'tested'			=> $upgrade['tested'],
				'requires_php'		=> $upgrade['requires_php'],
			];

			// And update
			set_site_transient('update_plugins', $current);

			// Check automatic update
			if (defined('AUTOMATIC_UPDATE_PLUGINS') && AUTOMATIC_UPDATE_PLUGINS) {

				// Install attempt
				if ($this->install($upgrade)) {

					// Clean upgrade data
					$this->upgrade([]);
				}
			}

		// No plugin info
		} else {

			// Remove update if not empty
			$upgrade = $this->upgrade();
			if (!empty($upgrade)) {
				$this->upgrade([]);
			}
		}
	}



	/**
	 * Read or save plugins data
	 */
	private function upgrade($upgrade = null) {

		// Option name
		$name = $this->namespace.'_'.$this->prefix.'_update_plugin_info';

		// Update
		if (isset($upgrade)) {

			// Save plugins data
			update_option($name, @json_encode($upgrade), false);

		// Retrieve
		} else {

			// Local cache
			static $value;
			if (isset($value)) {
				return $value;
			}

			// Retrieve plugins list
			$value = @json_decode(get_option($name), true);
			if (empty($value) || !is_array($value)) {
				$value = [];
			}

			// Done
			return $value;
		}
	}



	/**
	 * Compose plugin key based on main plugin file
	 */
	private function fileKey() {

		// This plugin main file
		if (empty($this->file)) {
			return false;
		}

		// Split in slugs
		$parts = explode('/', $this->file);
		if (count($parts) < 2) {
			return false;
		}

		// Check dir and file
		$dir  = $parts[count($parts) - 2];
		$file = $parts[count($parts) - 1];
		if ('' === $dir || '' === $file) {
			return false;
		}

		// Compose key
		$key = $dir.'/'.$file;

		// Done
		return $key;
	}



	/**
	 * Install plugin
	 */
	public function install($upgrade) {

		// Prepare input data
		$plugin = $this->key;
		$slug = dirname($this->key);

		// Check mu plugin
		$is_mu = (false !== strpos(__FILE__, WPMU_PLUGIN_DIR));


		/* WP Core wp_update_plugins function (modified) */

		$plugin = plugin_basename( sanitize_text_field( wp_unslash( $plugin ) ) );

		$status = array(
			'update'     => 'plugin',
			'slug'       => sanitize_key( wp_unslash( $slug ) ),
			'oldVersion' => '',
			'newVersion' => '',
		);

		// Debug mode
		$debug = defined('WP_DEBUG') && WP_DEBUG;

// Debug point
//$debug = true;

		if ( /* ! current_user_can( 'update_plugins' ) || */ // No user permissions here
			 0 !== validate_file( $plugin ) ) {

			// Set message
			$status['errorMessage'] = __( 'Sorry, you are not allowed to update plugins for this site.');

			// Debug point
			if ($debug) {
				error_log(print_r($status, true));
			}

			// Unallowed
			return false;
		}

		// Check plugin data function
		if (!function_exists('get_plugin_data')) {
			require_once ABSPATH.'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( ( $is_mu ? WPMU_PLUGIN_DIR : WP_PLUGIN_DIR ) . '/' . $plugin );
		$status['plugin'] = $plugin;
		$status['pluginName'] = $plugin_data['Name'];

		if ( $plugin_data['Version'] ) {
			$status['oldVersion'] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
		}

		// Remove previous filters
		remove_filter('plugins_api', [$this, 'pluginsAPI'], PHP_INT_MAX);
		remove_filter('http_request_args', [$this, 'httpRequestArgs'], PHP_INT_MAX);
		remove_filter('http_response', [$this, 'httpResponse'], PHP_INT_MAX);

		// Dependencies
		require_once ABSPATH.'wp-admin/includes/file.php';
		require_once ABSPATH.'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH.'wp-admin/includes/class-wp-filesystem-base.php';

		// Regular plugins
		if (!$is_mu) {

			// Update plugins data
			wp_update_plugins();

			// Single plugin upgrade
			$skin = new \WP_Ajax_Upgrader_Skin();
			$upgrader = new \Plugin_Upgrader( $skin );
			$result = $upgrader->upgrade($plugin);

		// mu-plugins
		} else {

			$skin = new \WP_Ajax_Upgrader_Skin();
			$upgrader = new \Plugin_Upgrader( $skin );
			$result = $upgrader->run([
				'package' => $upgrade['package'],
				'destination' => WPMU_PLUGIN_DIR.'/'.dirname($this->key),
				'clear_destination' => true,
				'clear_working' => true,
				'is_multi' => false
			]);
		}

		// Debug info
		if ($debug) {
			$status['debug'] = $skin->get_upgrade_messages();
		}


		/* Results */

		// Check error result
		if ( is_wp_error( $skin->result ) ) {

			// Set error codes
			$status['errorCode']    = $skin->result->get_error_code();
			$status['errorMessage'] = $skin->result->get_error_message();

			// Debug point
			if ($debug) {
				error_log(print_r($status, true));
			}

			// Error
			return false;


		// Check process errors
		} elseif ( $skin->get_errors()->get_error_code() ) {

			// Set message
			$status['errorMessage'] = $skin->get_error_messages();

			// Debug point
			if ($debug) {
				error_log(print_r($status, true));
			}

			// Error
			return false;


		// Check result data
		} elseif ( true === $result ) {

			if (!$is_mu) {

				$plugin_data = get_plugins( '/' . $result[ $plugin ]['destination_name'] );
				$plugin_data = reset( $plugin_data );

				if ( $plugin_data['Version'] ) {
					$status['newVersion'] = sprintf( __( 'Version %s' ), $plugin_data['Version'] );
				}

				// Remove WP upgrade data
				$current = get_site_transient('update_plugins');
				if (isset($current->response[$this->key])) {
					unset($current->response[$this->key]);
					set_site_transient('update_plugins', $current);
				}
			}

			// Debug point
			if ($debug) {
				error_log(print_r($status, true));
			}

			// Remove upgrade data
			$this->upgrade([]);

			// Done
			return true;


		// No result
		} elseif ( false === $result ) {

			// Globals
			global $wp_filesystem;

			// Set messages
			$status['errorCode']    = 'unable_to_connect_to_filesystem';
			$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.' );

			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}

			// Debug point
			if ($debug) {
				error_log(print_r($status, true));
			}

			// Error
			return false;
		}

		// An unhandled error occurred.
		if ($debug) {
			error_log(__('Plugin update failed.'));
		}

		// Error
		return false;
	}



	/**
	 * Set the options for plugin upgrade
	 */
	public function upgraderOptions($options) {

		// Check this plugin update
		if (empty($options['hook_extra']['plugin']) || $this->key != $options['hook_extra']['plugin']) {
			return $options;
		}

		// Set same folder destination
		$options['destination'] = rtrim($options['destination'], '/').'/'.dirname($this->key);

		// Done
		return $options;
	}



}