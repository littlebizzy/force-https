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
		if (!$this->factory->checker->isHTTPS()) {

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
		if ($this->plugin->context()->front() || $this->plugin->context()->ajax()) {
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



}
