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
			'#<(?:img|iframe)[\s|\t].*?src=[\'"]\K(http|https)://[^\'"]+#i',	// image and iframe elements
			'#<a[\s|\t][^>]*href=[\'"]\K(http|https)://[^\'"]+#i',				// anchor elements
			'#<link[\s|\t][^>]*href=[\'"]\K(http|https)://[^\'"]+#i',			// link elements
			'#<script[\s|\t][^>]*?src=[\'"]\K(http|https)://[^\'"]+#i',			// script elements
			'#url\([\'"]?\K(http|https)://[^)]+#i',								// inline CSS e.g. background images
		);

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
	 * Replace HTTP or HTTPS for protocol relative
	 */
	public function contentURL($matches) {
		return substr($matches[0], (empty($matches[1]) || 'http' == $matches[1])? 5 : 6);
	}



	/**
	 * Callback for object/embed elements
	 * Do the replacements for multiple URLs
	 */
	public function embedURL($matches) {
		return preg_replace_callback('#(http|https)://[^\'"&\? ]+#i', array(&$this, 'contentURL'), $matches[0]);
	}



	/**
	 * Filter for the uploads dir array
	 */
	public function uploadDir($uploads) {
		$uploads['url']	= $this->securizeURL($uploads['url']);
		$uploads['baseurl']	= $this->securizeURL($uploads['baseurl']);
		return $uploads;
	}



	/**
	 * Check and securize an URL
	 */
	public function securizeURL($url) {
		return (0 === stripos($url, 'https://'))? substr($url, 6) : ((0 === stripos($url, 'http://'))? substr($url, 5) : $url);
	}



}