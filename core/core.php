<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS\Core;

// Aliased namespaces
use \LittleBizzy\ForceHTTPS\Helpers;

/**
 * Core class
 *
 * @package Force HTTPS
 * @subpackage Core
 */
final class Core extends Helpers\Singleton {



	/**
	 * Factory object
	 */
	private $factory;



	/**
	 * Pseudo-constructor
	 */
	protected function onConstruct() {

		// Exit on WP-CLI context
		if (defined('WP_CLI') && WP_CLI) {
			return;
		}

		// Create factory object
		$this->factory = new Factory($this->plugin);

		// HTTPS check
		if (!$this->isHTTPS()) {

			// Launch redirect object
			$this->factory->redirect();

		// Continue
		} else {

			// Add hooks
			$this->hooks();
		}
	}



	/**
	 * Declare the WP hooks
	 */
	private function hooks() {

		// Create filters object
		$filters = $this->factory->filters();

		// Scripts and stylesheet links
		add_filter('script_loader_src', [$filters, 'securizeURL'], PHP_INT_MAX);
		add_filter('style_loader_src',  [$filters, 'securizeURL'], PHP_INT_MAX);

		// Attachments URL in frontend or AJAX context
		if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
			add_filter('wp_get_attachment_url', [$filters, 'securizeURL'], PHP_INT_MAX);
		}

		// Content filters
		add_filter('the_content', [$filters, 'content'], PHP_INT_MAX);
		add_filter('widget_text', [$filters, 'content'], PHP_INT_MAX);

		// Gravity Forms confirmation content
		add_filter('gform_confirmation', [$filters, 'content'], PHP_INT_MAX);

		// Upload URLs
		add_filter('upload_dir', [$filters, 'uploadDir'], PHP_INT_MAX);

		// Image Widget plugin
		add_filter('image_widget_image_url', [$filters, 'securizeURL'], PHP_INT_MAX);
	}



	/**
	 * Custom HTTPS check
	 */
	private function isHTTPS() {

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