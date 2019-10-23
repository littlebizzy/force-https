<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS\Force;

// Aliased namespaces
use \LittleBizzy\ForceHTTPS\Helpers;

/**
 * Filters class
 *
 * @package Force HTTPS
 * @subpackage Force
 */
final class Filters extends Helpers\Singleton {



	/**
	 * Current site host
	 */
	private $host;



	/**
	 * Pseudo-constructor
	 */
	protected function onConstruct() {
		$this->host = $this->getHostFromURL(home_url());
	}



	/**
	 * Filters the content URLs
	 * Strongly inspired in: https://es.wordpress.org/plugins/ssl-insecure-content-fixer/
	 */
	public function content($content) {

		// Checks for intentional disabling
		if (!$this->plugin->enabled('FORCE_HTTPS')) {
			return $content;
		}

		// Prepare patterns
		static $searches = [
			'#<((?:img|iframe))[\s|\t].*?src=[\'"]\K(http://|//)[^\'"]+#i',	// image and iframe elements
			'#<(a)[\s|\t][^>]*href=[\'"]\K(http://|//)[^\'"]+#i',			// anchor elements
			'#<(link)[\s|\t][^>]*href=[\'"]\K(http://|//)[^\'"]+#i',		// link elements
			'#<(script)[\s|\t][^>]*?src=[\'"]\K(http://|//)[^\'"]+#i',		// script elements
			'#<(source)[\s|\t][^>]*?src=[\'"]\K(http://|//)[^\'"]+#i',		// source elements
			'#(url)\([\'"]?\K(http://|//)[^)]+#i',							// inline CSS e.g. background images
		];

		// Test the searches
		$content = preg_replace_callback($searches, [$this, 'contentURL'], $content);

		// Object embeds
		static $embeds = [
			'#<object\s+.*?</object>#is',				// object elements, including contained embed elements
			'#<embed\s+.*?(?:/>|</embed>)#is',			// embed elements, not contained in object elements
			'#<img\s+[^>]+srcset=["\']\K[^"\']+#is',	// responsive image srcset links (both internal and external images)
		];

		// Test the embeds
		$content = preg_replace_callback($embeds, [$this, 'embedURL'], $content);

		// Done
		return $content;
	}



	/**
	 * Replace URL HTTP protocol by HTTPS
	 */
	public function contentURL($matches) {

		// Extract tag from matches
		$tag = (3 == count($matches))? $matches[1] : null;

		// Check internal link
		$internal = $this->isInternalLink($matches[0]);

		// Exceptions by tag and internal/external URL's
		if ((isset($tag) && 'a' == $tag && $internal && !$this->plugin->enabled('FORCE_HTTPS_INTERNAL_LINKS')) || 			// prevent internal links to be altered
			((!isset($tag) || 'a' != $tag) && $internal && !$this->plugin->enabled('FORCE_HTTPS_INTERNAL_RESOURCES')) || 	// prevent internal resources to be altered
			(isset($tag) && 'a' == $tag && !$internal && !$this->plugin->enabled('FORCE_HTTPS_EXTERNAL_LINKS', false)) || 	// prevent external links to be altered
			((!isset($tag) || 'a' != $tag) && !$internal && !$this->plugin->enabled('FORCE_HTTPS_EXTERNAL_RESOURCES'))) { 	// prevent external resources to be altered

			// Original URL
			return $matches[0];
		}

		// Prepare protocol
		$protocol = isset($matches[2])? $matches[2] : $matches[1];

		// Compose new URL
		$url = 'https://'.substr($matches[0], strlen($protocol));

		// Done
		return $url;
	}



	/**
	 * Callback for object/embed elements
	 * Do the replacements for multiple URLs
	 */
	public function embedURL($matches) {

		// Initialize
		$result = [];

		// Split URLs
		$srcset = array_map('trim', explode(',', str_replace(', ', ',', $matches[0])));

		// Process URLs
		foreach ($srcset as $url) {

			// Check URL
			if ('' === $url) {
				continue;
			}

			// Replace protocol
			$result[] = preg_replace_callback('#^(http://|//)[^\'"&\? ]+#i', [$this, 'contentURL'], $url);
		}

		// Join replaced URLs
		$srcset = implode(', ', $result);

		// Done
		return $srcset;
	}



	/**
	 * Filter the uploads dir array
	 */
	public function uploadDir($uploads) {

		// Checks for intentional disabling
		if (!$this->plugin->enabled('FORCE_HTTPS')) {
			return $uploads;
		}

		// Securize uploads URLs
		$uploads['url'] = $this->securizeURL($uploads['url']);
		$uploads['baseurl'] = $this->securizeURL($uploads['baseurl']);

		// Done
		return $uploads;
	}



	/**
	 * Check and securize an URL
	 */
	public function securizeURL($url) {

		// Checks for intentional disabling
		if (!$this->plugin->enabled('FORCE_HTTPS')) {
			return $url;
		}

		// Replace HTTP or Protocol relative by HTTPs
		return (0 === stripos($url, 'http://'))? 'https'.substr($url, 4) : ((0 === strpos($url, '//'))? 'https:'.$url : $url);
	}



	/**
	 * Determines if an URL is an internal link
	 */
	private function isInternalLink($url) {

		// URL host
		if (false === ($host = $this->getHostFromURL($url))) {
			return false;
		}

		// Compare hosts
		return ($this->host == $host);
	}



	/**
	 * Extract host or domain name from URL
	 */
	private function getHostFromURL($url, $wwwRemove = true) {

		// Extract the host part
		if (false === ($host = @parse_url($url, PHP_URL_HOST))) {
			return false;
		}

		// Check if remove the www prefix
		if ($wwwRemove && 0 === stripos($host, 'www.')) {
			$host = substr($host, 4);
		}

		// Done
		return $host;
	}



}
