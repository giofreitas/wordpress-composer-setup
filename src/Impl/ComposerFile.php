<?php
namespace Gio\WordPress\Setup\Impl;

use Composer\Composer;
use Gio\WordPress\Setup\ComposerFile as ComposerFileInterface;

/**
 *
 */
class ComposerFile implements ComposerFileInterface {

    /**
     * @var Composer
     */
    private $composer;

    /**
     * Plugin constructor.
     * @param Composer $composer
     */
    public function __construct(Composer $composer){
        $this->composer = $composer;
    }

    /**
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getConfig($name, $default = null){
        $config = $this->composer->getPackage()->getConfig();
        return isset($config[$name]) ? $config[$name] : $default;
    }

    /**
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getExtra($name, $default = null){
        $extras = $this->composer->getPackage()->getExtra();
        return isset($extras[$name]) ? $extras[$name] : $default;
    }

    /**
     *
     * @param string $type
     * @param string $default
     *
     * @return string
     */
    private function getInstallerDir($type, $default){
        $installerPaths = $this->getExtra('installer-paths', array());
        foreach($installerPaths as $path => $types)
            if(in_array("type:$type", $types))
                return dirname($path);
        // if not, return the default folder
        return $default;
    }

    /**
     *
     * @return string
     */
    public function getMuPluginsDir(){
        return $this->getInstallerDir('wordpress-muplugin', 'wp-content/mu-plugins');
    }

    /**
     *
     * @return string
     */
    public function getPluginsDir(){
        return $this->getInstallerDir('wordpress-plugin', 'wp-content/plugins');
    }

    /**
     *
     * @return mixed
     */
    public function getSiteUrl(){
        return $this->getExtra('wordpress-site-url', '');
    }

    /**
     *
     * Get the directory that will contain the themes
     *
     * @return mixed
     */
    public function getThemeDir(){
        return $this->getInstallerDir('wordpress-theme', 'wp-content/themes');
    }

    /**
     *
     * @return string
     */
    public function getVendorDir(){
        return $this->getConfig('vendor-dir', 'vendor');
    }

    /**
     * Get wp-content directory
     *
     * @return mixed
     */
    public function getWpContentDir(){
        // we will get the content dir through themes dir by get its parent folder
        return dirname($this->getThemeDir());
    }

    /**
     * Get from the extra the directory to install wordpress core files.
     *
     * @see https://github.com/johnpbloch/wordpress-core-installer
     * @return string
     */
    public function getWpCoreDir(){
        return $this->getExtra('wordpress-install-dir', 'wordpress');
    }

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function setExtra($name, $value){
        $extra = $this->composer->getPackage()->getExtra();
        $extra[$name] = $value;
        $this->composer->getPackage()->setExtra($extra);
    }
}