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



	/**
	 * Current site host
	 */
	public $host;



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
		$this->host = $this->getHostFromURL(home_url());
	}



	// Methods
	// ---------------------------------------------------------------------------------------------------



	/**
	 * Filters the content URLs
	 * Strongly inspired in: https://es.wordpress.org/plugins/ssl-insecure-content-fixer/
	 */
	public function content($content) {

		// Prepare patterns
		static $searches = array(
			'#<((?:img|iframe))[\s|\t].*?src=[\'"]\K(http://|//)[^\'"]+#i',	// image and iframe elements
			'#<(a)[\s|\t][^>]*href=[\'"]\K(http://|//)[^\'"]+#i',			// anchor elements
			'#<(link)[\s|\t][^>]*href=[\'"]\K(http://|//)[^\'"]+#i',		// link elements
			'#<(script)[\s|\t][^>]*?src=[\'"]\K(http://|//)[^\'"]+#i',		// script elements
			'#(url)\([\'"]?\K(http://|//)[^)]+#i',							// inline CSS e.g. background images
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
	 * Replace HTTP by HTTPS for images,
	 * URLs in object and embed tags, and internal links.
	 */
	public function contentURL($matches) {
		$tag = (3 == count($matches))?  $matches[1] : null;
		$protocol = isset($matches[2])? $matches[2] : $matches[1];
		return (!isset($tag) || in_array($tag, ['img', 'url']) || $this->isInternalLink($matches[0]))? 'https://'.substr($matches[0], strlen($protocol)) : $matches[0];
	}



	/**
	 * Callback for object/embed elements
	 * Do the replacements for multiple URLs
	 */
	public function embedURL($matches) {
		$result = array();
		$srcset = explode(',', str_replace(', ', ',', $matches[0]));
		foreach ($srcset as $url)
			$result[] = preg_replace_callback('#^(http://|//)[^\'"&\? ]+#i', array(&$this, 'contentURL'), trim($url));
		return implode(', ', $result);
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
		return (0 === stripos($url, 'http://'))? 'https'.substr($url, 4) : ((0 === strpos($url, '//'))? 'https:'.$url : $url);
	}



	/**
	 * Determines if an URL is an internal link
	 */
	public function isInternalLink($url) {

		// URL host
		if (false === ($host = $this->getHostFromURL($url)))
			return false;

		// Compare hosts
		return ($this->host == $host);
	}



	/**
	 * Extract host or domain name from URL
	 */
	public function getHostFromURL($url, $remove_www = true) {

		// Extract the host part
		if (false === ($host = @parse_url($url, PHP_URL_HOST)))
			return false;

		// Check if remove the www prefix
		if ($remove_www && 0 === stripos($host, 'www.'))
			$host = substr($host, 4);

		// Done
		return $host;
	}



}