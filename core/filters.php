<?php

/**
 * Force HTTPS - Filters class
 *
 * @package Force HTTPS
 * @subpackage Force HTTPS Core
 */
final class FHTTPS_Core_Redirect {


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
		
	}



}