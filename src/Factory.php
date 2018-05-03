<?php

namespace Gio\WordPress\Setup;

use Gio\WordPress\Setup\Impl;

/**
 *
 */
class Factory {

	/**
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 *
	 * @param Plugin $plugin
	 */
	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
	}

	/**
	 *
	 * @return Plugin
	 */
	public function getPlugin(){
		return $this->plugin;
	}

	/**
	 *
	 * @return WordPressInstaller
	 */
	public function getWordPressInstaller(){
		return new WordPressInstaller($this->plugin);
	}

	/**
	 *
	 * @return WpConfigManager
	 */
	public function getWpConfigManager(){
		return new Impl\WpConfigManager($this->plugin, $this->getWpConfigParser(), $this->getConfigFileGenerator(), $this->getWpConfigGetter());
	}

	/**
	 *
	 * @return WpConfigGetter
	 */
	public function getWpConfigGetter(){
		return new Impl\WpConfigGetter($this->plugin, $this->getWpConfig());
	}

	/**
	 *
	 * @return WpConfigFileGenerator
	 */
	public function getConfigFileGenerator(){
		return new Impl\WpConfigFileGenerator($this->plugin, $this->getWpConfigSample());
	}

	/**
	 *
	 * @return WpConfigParser
	 */
	public function getWpConfigParser(){
		return new Impl\WpConfigParser($this->getWpConfig());
	}

	/**
	 *
	 * @return WpConfig
	 */
	public function getWpConfig(){
		return new Impl\WpConfig();
	}

	/**
	 * @return WpConfigSample
	 */
	public function getWpConfigSample(){
		return new Impl\WpConfigSample();
	}
}