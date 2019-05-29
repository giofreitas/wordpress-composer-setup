<?php

namespace Gio\WordPress\Setup;

use Composer\Composer;
use Composer\IO\IOInterface;
use Gio\WordPress\Setup\Impl;

/**
 *
 */
class Factory {

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * Plugin constructor.
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function __construct(Composer $composer, IOInterface $io){
        $this->io = $io;
        $this->composer = $composer;
    }

    /**
     * @return Composer
     */
    public function getComposer(){
        return $this->composer;
    }

    /**
     * @return IOInterface
     */
    public function getIO(){
        return $this->io;
    }

	/**
	 *
	 * @return ComposerFile
	 */
	public function getComposerFile(){
		return new Impl\ComposerFile($this->getComposer());
	}

	/**
	 *
	 * @return WordPressInstaller

	public function getWordPressInstaller(){
		return new WordPressInstaller($this->getIO(), $this->getComposer(), $this->getComposerFile());
	} */

	/**
	 *
	 * @return WpConfigManager
	 */
	public function getWpConfigManager(){
		return new Impl\WpConfigManager($this->getComposerFile(), $this->getWpConfigParser(), $this->getConfigFileGenerator(), $this->getWpConfigGetter());
	}

	/**
	 *
	 * @return WpConfigGetter
	 */
	public function getWpConfigGetter(){
		return new Impl\WpConfigGetter($this->getIO(), $this->getComposerFile(), $this->getWpConfig());
	}

	/**
	 *
	 * @return WpConfigFileGenerator
	 */
	public function getConfigFileGenerator(){
		return new Impl\WpConfigFileGenerator($this->getComposerFile(), $this->getWpConfigSample());
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