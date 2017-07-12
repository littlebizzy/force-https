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
	 * Filters the content URLs
	 * Strongly inspired in: https://es.wordpress.org/plugins/ssl-insecure-content-fixer/
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
// fix https also!!!!
		// Test the searches
		$content = preg_replace_callback($searches, array(&$this, 'contentURL'), $content);

		// Object embeds
		static $embeds = array(
			'#<object\s+.*?</object>#is',				// object elements, including contained embed elements
			'#<embed\s+.*?(?:/>|</embed>)#is',			// embed elements, not contained in object elements
			'#<img\s+[^>]+srcset=["\']\K[^"\']+#is',	// responsive image srcset links (both internal and external images)
		);

		// Test the embeds
		$content = preg_replace_callback($embeds, array(&$this, 'embedURL'), $content);

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

		// Fix WP HTTPS behaviour handling local images
		$urls = str_ireplace('https://', 'http://', $matches[0]);

		// Do the replacements for multiple URLs
		return preg_replace_callback('#http://[^\'"&\? ]+#i', array(&$this, 'contentURL'), $urls);
	}



}