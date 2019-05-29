<?php
namespace Gio\WordPress\Setup;

/**
 *
 */
interface ComposerFile {

    /**
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getExtra($name, $default = null);

    /**
     *
     * @return string
     */
    public function getMuPluginsDir();

    /**
     *
     * @return string
     */
    public function getPluginsDir();

    /**
     *
     * @return mixed
     */
    public function getSiteUrl();

    /**
     *
     * @return string
     */
    public function getVendorDir();

    /**
     * Get wp-content directory
     *
     * @return mixed
     */
    public function getWpContentDir();

    /**
     * Get from the extra the directory to install wordpress core files.
     *
     * @see https://github.com/johnpbloch/wordpress-core-installer
     * @return string
     */
    public function getWpCoreDir();

    /**
     *
     * @param string $name
     * @param mixed $value
     */
    public function setExtra($name, $value);

}