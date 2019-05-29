<?php
namespace Gio\WordPress\Setup\Impl;

use Gio\WordPress\Setup\ComposerFile;
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
	 * @var ComposerFile
	 */
	private $composeFile;

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
	 * @param ComposerFile $composeFile
	 * @param WpConfigParser $parser
	 * @param WpConfigFileGenerator $generator
	 * @param WpConfigGetter $getter
	 */
	public function __construct(ComposerFile $composeFile, WpConfigParser $parser, WpConfigFileGenerator $generator, WpConfigGetter $getter) {
		$this->composeFile = $composeFile;
		$this->parser = $parser;
		$this->generator = $generator;
		$this->getter = $getter;
	}

	/**
	 *
	 * @return string|false
	 */
	private function getWpConfigPath(){
		$wpCoreDir = $this->composeFile->getWpCoreDir();
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