<?php
namespace Gio\WordPress\Setup;

/**
 *
 * Interface WpConfigParser
 * @package Gio\Composer\Plugins\SetupWpConfig
 */
interface WpConfigParser {

	/**
	 *
	 * @param string $wpConfigFile
	 * @return WpConfig|false
	 */
	public function parse($wpConfigFile);
}