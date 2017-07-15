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

		// Content filters
		add_filter('the_content', array(&$this, 'filterContent'), 999999);
		add_filter('widget_text', array(&$this, 'filterContent'), 999999);

		// Gravity Forms confirmation content
		add_filter('gform_confirmation', array(&$this, 'filterContent'), 999999);
	}



	// WP Hooks
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Filter content URLs
	 */
	public function filterContent($content) {

		// Load filters object
		require_once(FHTTPS_PATH.'/core/filters.php');
		$filters = FHTTPS_Core_Filters::instance();

		// Filter content
		return $filters->content($content);
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



}