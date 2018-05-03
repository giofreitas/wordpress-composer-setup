<?php
namespace Gio\WordPress\Setup;

/**
 *
 * Interface WpConfigFileGenerator
 * @package Gio
 */
interface WpConfigFileGenerator {

	/**
	 *
	 * @param WpConfig $wpConfig
	 *
	 * @return array
	 */
	public function generateWpConfigFile(WpConfig $wpConfig);
};