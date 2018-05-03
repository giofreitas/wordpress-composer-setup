<?php
namespace Gio\WordPress\Setup;

/**
 *
 */
interface WpConfigManager {

	/**
	 *
	 * @return WpConfig|null
	 */
	public function parseWpConfig();

	/**
	 *
	 * @return array
	 */
	public function generateWpConfig();

}