<?php

// Subpackage namespace
namespace LittleBizzy\ForceHTTPS\Core;

// Aliased namespaces
use \LittleBizzy\ForceHTTPS\Helpers;
use \LittleBizzy\ForceHTTPS\Force;

/**
 * Object Factory class
 *
 * @package Force HTTPS
 * @subpackage Core
 */
class Factory extends Helpers\Factory {



	/**
	 * Redirect object
	 */
	protected function createRedirect() {
		return Force\Redirect::instance();
	}



	/**
	 * Filters object
	 */
	protected function createFilters() {
		return Force\Filters::instance();
	}



}