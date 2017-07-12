<?php

/**
 * Force HTTPS - Filters class
 *
 * @package Force HTTPS
 * @subpackage Force HTTPS Core
 */
final class FHTTPS_Core_Filters {


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
	private function __construct() {}



	// Methods
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Filters the content
	 */
	public function content($content) {

		// Prepare patterns
		static $searches = array(
			'#<(?:img|iframe) .*?src=[\'"]\Khttp://[^\'"]+#i',	// image and iframe elements
			'#<a\s+[^>]+href=[\'"]\Khttp://[^\'"]+#i',			// anchor elements
			'#<link\s+[^>]+href=[\'"]\Khttp://[^\'"]+#i',		// link elements
			'#<script\s+[^>]*?src=[\'"]\Khttp://[^\'"]+#i',		// script elements
			'#url\([\'"]?\Khttp://[^)]+#i',						// inline CSS e.g. background images
		);

		// Perform the searches
		$content = preg_replace_callback($searches, array(&$this, 'contentURL'), $content);

		// Done
		return $content;
	}



	/**
	 * Callback for URLs
	 */
	public function contentURL($matches) {
		return substr($matches[0], 5);
	}



	/**
	 * Callback for object/embed elements
	 */
	public function embedURL($matches) {
		return preg_replace_callback('#http://[^\'"&\? ]+#i', array(&$this, 'contentURL'), $matches[0]);
	}



}