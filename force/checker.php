<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS\Force;

/**
 * Checker class
 *
 * @package Force HTTPS
 * @subpackage Force
 */
class Checker {



	/**
	 * Custom HTTPS check
	 */
	public function isHTTPS() {

		// WP Check
		if (is_ssl()) {
			return true;
		}

		// Check X-Forwarded-Proto header
		if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			return true;
		}

		// Check X-Forwarded-SSL header
		if (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && ('on' == strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) || '1' == $_SERVER['HTTP_X_FORWARDED_SSL'])) {
			return true;
		}

		// Check CloudFront-Forwarded-Proto header
		if (isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) && 'https' == strtolower($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'])) {
			return true;
		}

		// Check the Cloudflare CF-Vistor header
		if (isset($_SERVER['HTTP_CF_VISITOR']) && false !== strpos($_SERVER['HTTP_CF_VISITOR'], 'https')) {
			return true;
		}

		// Check X-ARR-SSL header
		if (!empty($_SERVER['HTTP_X_ARR_SSL'])) {
			return true;
		}

		// No SSL
		return false;
	}



}
