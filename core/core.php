<?php

/**
 * Force HTTPS - Core class
 *
 * @package Force HTTPS
 * @subpackage Force HTTPS Core
 */
final class FHTTPS_Core {



	// Properties
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Single class instance
	 */
	private static $instance;



	/**
	 * Filters object
	 */
	private $filters;



	// Initialization
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Create or retrieve instance
	 */
	public static function instance() {

		// Check instance
		if (!isset(self::$instance))
			self::$instance = new self;

		// Done
		return self::$instance;
	}



	/**
	 * Constructor
	 */
	private function __construct() {

		// Check SSL status
		$this->checkSSLRedirect();

		// Scripts and stylesheet links
		add_filter('script_loader_src', array(&$this, 'filterURL'), 999999);
		add_filter('style_loader_src', array(&$this, 'filterURL'), 999999);

		// Attachments URL in frontend or AJAX context
		if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX))
			add_filter('wp_get_attachment_url', array(&$this, 'filterURL'), 999999);

		// Content filters
		add_filter('the_content', array(&$this, 'filterContent'), 999999);
		add_filter('widget_text', array(&$this, 'filterContent'), 999999);

		// Gravity Forms confirmation content
		add_filter('gform_confirmation', array(&$this, 'filterContent'), 999999);

		// Upload URLs
		add_filter('upload_dir', array(&$this, 'uploadDir'), 999999);

		// Image Widget plugin
		add_filter('image_widget_image_url', array(&$this, 'filterURL'), 999999);
	}



	// WP Hooks
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Filter content URLs
	 */
	public function filterContent($content) {
		$this->loadFilters();
		return $this->filters->content($content);
	}



	/**
	 * Filter Upload directory array
	 */
	public function uploadDir($uploads) {
		$this->loadFilters();
		return $this->filters->uploadDir($uploads);
	}



	/**
	 * Filter a single URL
	 */
	public function filterURL($url) {
		$this->loadFilters();
		return $this->filters->securizeURL($url);
	}



	// Internal checks
	// ---------------------------------------------------------------------------------------------------



	/**
	 * And is_ssl custom wrapper
	 */
	private function checkSSLRedirect() {

		// Custom check
		if (!$this->isHTTPS()) {

			// Load redirects class
			require_once(FHTTPS_PATH.'/core/redirect.php');
			FHTTPS_Core_Redirect::instance();
		}
	}



	/**
	 * Custom HTTPS check
	 */
	private function isHTTPS() {

		// WP Check
		if (is_ssl())
			return true;

		// Check X-Forwarded-Proto header
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']))
			return true;

		// Check X-Forwarded-SSL header
		if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && ('on' == strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) || '1' == $_SERVER['HTTP_X_FORWARDED_SSL']))
			return true;

		// Check CloudFront-Forwarded-Proto header
		if (isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) && 'https' == strtolower($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']))
			return true;

		// Check the Cloudflare CF-Vistor header
		if (isset($_SERVER['HTTP_CF_VISITOR']) && false !== strpos($_SERVER['HTTP_CF_VISITOR'], 'https'))
			return true;

		// Check X-ARR-SSL header
		if (!empty($_SERVER['HTTP_X_ARR_SSL']))
			return true;

		// No SSL
		return false;
	}



	/**
	 * Load filters object
	 */
	private function loadFilters() {
		if (!isset($this->filters)) {
			require_once(FHTTPS_PATH.'/core/filters.php');
			$this->filters = FHTTPS_Core_Filters::instance();
		}
	}



}