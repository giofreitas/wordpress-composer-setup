<?php
namespace Gio\WordPress\Setup\Impl;

use Gio\WordPress\Setup\Plugin;
use Gio\WordPress\Setup\WpConfig;
use Gio\WordPress\Setup\WpConfigManager as WpConfigManagerInterface;
use Gio\WordPress\Setup\WpConfigParser;
use Gio\WordPress\Setup\WpConfigFileGenerator;
use Gio\WordPress\Setup\WpConfigGetter;

/**
 *
 */
class WpConfigManager implements WpConfigManagerInterface {

	/**
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 *
	 * @var WpConfigParser
	 */
	private $parser;

	/**
	 *
	 * @var WpConfigFileGenerator
	 */
	private $generator;

	/**
	 *
	 * @var WpConfigGetter
	 */
	private $getter;

	/**
	 *
	 * @param Plugin $plugin
	 * @param WpConfigParser $parser
	 * @param WpConfigFileGenerator $generator
	 * @param WpConfigGetter $getter
	 */
	public function __construct(Plugin $plugin, WpConfigParser $parser, WpConfigFileGenerator $generator, WpConfigGetter $getter) {
		$this->plugin = $plugin;
		$this->parser = $parser;
		$this->generator = $generator;
		$this->getter = $getter;
	}

	/**
	 *
	 * @return string|false
	 */
	private function getWpConfigPath(){
		$wpCoreDir = $this->plugin->getWpCoreDir();
		// Check if wp-config.php file lives in core folder. It is not supposed to be here but if it is, it will be
		// the one loaded by WordPress
		if(file_exists($wpConfigPath = $wpCoreDir. '/wp-config.php'))
			return $wpConfigPath;
		// check if wp-config.php file lives one directory above the core folder. WordPress will look for wp-config.php
		// here too, so its fine.
		if(file_exists($wpConfigPath = dirname($wpCoreDir).'/wp-config.php'))
			return $wpConfigPath;
		return false;
	}


	/**
	 *
	 * @return WpConfig|null
	 */
	public function parseWpConfig() {

		if (!$wpConfigPath = $this->getWpConfigPath())
			return null;

		$wpConfigContents = file_get_contents($wpConfigPath);
		return $this->parser->parse($wpConfigContents);
	}

	/**
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function generateWpConfig() {
		$wpConfig = $this->getter->getWpConfig();
		return $this->generator->generateWpConfigFile($wpConfig);
	}
}