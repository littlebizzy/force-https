<?php

return [



	/**
	 * Boot check PHP configuration
	 */
	'boot-check-php' => [

		/**
		 * Enables the PHP validation
		 */
		'enabled' => true,

		/**
		 * PHP minimum version
		 * Uses version_compare function: http://php.net/manual/en/function.version-compare.php
		 */
		'version-required' => '5.6.0',

		/**
		 * Aborts the plugin activation on WP sandbox generating an error output message
		 */
		'prevent-activation' => false,

		/**
		 * PHP error message
		 *
		 * Used to trigger a user error: It is limited to 1024 bytes in length. Any additional characters beyond 1024 bytes will be truncated
		 * (from PHP documentation: http://php.net/manual/en/function.trigger-error.php)
		 *
		 * Supported variables: %php_current_version% and %php_version_required%
		 */
		'version-message' => '<strong>%plugin%</strong> does not support your outdated PHP version (%php_current_version%). Please update your PHP to at least version 7.0 or consider <a href="https://www.littlebizzy.com/hosting?utm_source=phpcheck" target="_blank">better web hosting</a>.',

	], // End of boot check PHP



	/**
	 * Admin notices configuration
	 */
	'admin-notices' => [

		/**
		 * Enables the Admin Notices execution
		 */
		'enabled' => false,

		/**
		 * Rate Us
		 * The %plugin% mark reflects the plugin name
		 */
		'days_before_display_rate_us' 	=> 2, // 2 days delay
		'days_dismissing_rate_us' 	=> 180, // 6 months reappear
		'rate_us_url'			=> 'https://wordpress.org/support/plugin/[plugin-slug]-littlebizzy/reviews/?rate=5#new-post',
		'rate_us_url2'			=> 'https://www.facebook.com/groups/littlebizzy/',
		'rate_us_url3'			=> 'https://www.littlebizzy.com/services/speed-boost?utm_source=wpnotice',
		'rate_us_url4'			=> 'https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices#Disable_Nag_Notices',
		'rate_us_message' 		=> 'Thanks for using <strong>%plugin%</strong>. Please support our free work by rating this plugin with 5 stars on <a href="%url%" target="_blank">WordPress.org</a>.<br><br>You may also join our free <a href="%url2%" target="_blank">Facebook group</a> to post any questions or comments!<br><br>P.S. Use coupon code <code>SPEED20</code> to get $20 off our popular <a href="%url3%" target="_blank">Speed Boost</a> service at LittleBizzy.com.<br><br><small><em><a href="%url4%" target="_blank">Hide these notices</a></em></small>',

		/**
		 * Plugin suggestions
		 * The %plugin% mark reflects the plugin name
		 */
		'days_dismissing_suggestions' 	=> 180, // 6 months reappear
		'suggestions_message' 			=> '%plugin% recommends the following free plugins:',
		'suggestions'					=> [

			'disable-wc-status-littlebizzy' => [
				'name' => 'Disable WooCommerce Status',
				'desc' => 'Completely disables the WooCommerce Status widget in the WP Admin dashboard to greatly improve backend performance on high traffic WooCommerce shops.',
				'filename' => 'disable-woocommerce-status.php',
			],

			'disable-wc-styles-littlebizzy' => [
				'name' => 'Disable WooCommerce Styles',
				'desc' => 'Completely disables all of the CSS stylesheets that are loaded by WooCommerce in order that styling can be better managed by a single style.css file.',
				'filename' => 'disable-woocommerce-styles.php',
			],

			'delete-expired-transients-littlebizzy' => [
				'name' => 'Delete Expired Transients',
				'desc' => 'Deletes all expired transients upon activation and on a daily basis thereafter via WP Cron to maintain a cleaner database and improve performance.',
				'filename' => 'delete-expired-transients.php',
			],

			'disable-jq-migrate-littlebizzy' => [
				'name' => 'Disable jQuery Migrate',
				'desc' => 'Easily prevent the jQuery migrate script that is included with WordPress core from being loaded to slim down source code (for advanced users only).',
				'filename' => 'disable-jquery-migrate.php',
			],

			'remove-query-strings-littlebizzy' => [
				'name' => 'Remove Query Strings',
				'desc' => 'Removes all query strings from static resources meaning that proxy servers and beyond can better cache your site content (plus, better SEO scores).',
				'filename' => 'remove-query-strings.php',
			],

		], // End of suggestions

	], // End of admin-notices



	/**
	 * Admin Notices configuration for WordPress MultiSite
	 */
	'admin-notices-ms' => [

		/**
		 * Enables the Admin Notices execution
		 */
		'enabled' => true,

		/**
		 * Disables plugin execution on detected multisited installs
		 */
		'abort-on-multisite' => false,

		/**
		 * Custom message
		 * The %plugin% mark reflects the plugin name
		 */
		'message' => 'For performance reasons, <strong>%plugin%</strong> does not support Multisite. For best results, always place your WordPress website on a <a href="https://www.littlebizzy.com/hosting?utm_source=multisite" target="_blank">dedicated Nginx server</a>.',

	], // End of admin-notices-ms



]; // End of main array
