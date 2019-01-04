<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS\Force;

// Aliased namespaces
use \LittleBizzy\ForceHTTPS\Helpers;

/**
 * Redirect class
 *
 * @package Force HTTPS
 * @subpackage Force
 */
final class Redirect extends Helpers\Singleton {



	/**
	 * Initialize the redirection process
	 */
	protected function onConstruct() {
		add_action('init', [$this, 'start'], PHP_INT_MAX);
	}



	/**
	 * After WP init, do the redirect in a header-clean way
	 */
	public function start() {

		// Check if intentionally disabled
		if (defined('FORCE_SSL') && !FORCE_SSL) {
			return;
		}

		// Remove existing headers
		$this->removeHeaders();

		// Do the redirection
		$this->redirect();

		// End
		die;
	}



	/**
	 * Perform the URL redirection
	 */
	private function redirect() {

		// The REQUEST URI var contains the current URL
		if (0 === strpos($_SERVER['REQUEST_URI'], 'http')) {

			// Redirect by changing the URL scheme
			wp_redirect(set_url_scheme($_SERVER['REQUEST_URI'], 'https' ), 301);

		// HOST/URI
		} else {

			// Redirect composing the target URL
			wp_redirect('https://'. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 301);
		}
	}



	/**
	 * Remove any existing header
	 */
	private function removeHeaders() {

		// Check headers list
		$headers = @headers_list();
		if (!empty($headers) && is_array($headers)) {

			// Check header_remove function (PHP 5 >= 5.3.0, PHP 7)
			$byFunction = function_exists('header_remove');

			// Enum and clean
			foreach ($headers as $header) {
				list($k, $v) = array_map('trim', explode(':', $header, 2));
				$byFunction? @header_remove($k) : @header($k.':');
			}
		}
	}



}