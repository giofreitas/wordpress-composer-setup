<?php
namespace Gio\WordPress\Setup;

/**
 *
 * Interface WordPressConfig
 * @package Gio
 */
interface WpConfig {

	/**
	 *
	 * @return string
	 */
	public function getAuthKey();

	/**
	 *
	 * @return string
	 */
	public function getAuthSalt();

	/**
	 *
	 * @return string
	 */
	public function getContentDir();

	/**
	 *
	 * @return string
	 */
	public function getDatabaseHost();

	/**
	 *
	 * @return string
	 */
	public function getDatabaseName();

	/**
	 *
	 * @return string
	 */
	public function getDatabasePassword();

	/**
	 *
	 * @return string
	 */
	public function getDatabaseUsername();

	/**
	 *
	 * @return string
	 */
	public function getHomeUrl();

	/**
	 * @return mixed
	 */
	public function getMuPluginDir();

	/**
	 *
	 * @return string
	 */
	public function getPluginDir();

	/**
	 *
	 * @return string
	 */
	public function getLoggedInKey();

	/**
	 *
	 * @return string
	 */
	public function getLoggedInSalt();

	/**
	 *
	 * @return string
	 */
	public function getNonceKey();

	/**
	 *
	 * @return string
	 */
	public function getNonceSalt();

	/**
	 *
	 * @return string
	 */
	public function getSecureAuthKey();

	/**
	 *
	 * @return string
	 */
	public function getSecureAuthSalt();

	/**
	 *
	 * @return string
	 */
	public function getTablePrefix();

	/**
	 *
	 * @return string
	 */
	public function getWpInstallDir();

	/**
	 *
	 * @param string $authKey
	 */
	public function setAuthKey($authKey);

	/**
	 *
	 * @param string $authSalt
	 */
	public function setAuthSalt($authSalt);

	/**
	 *
	 * @param string $dir
	 */
	public function setContentDir($dir);

	/**
	 *
	 * @param string $host
	 */
	public function setDatabaseHost($host);

	/**
	 *
	 * @param string $name
	 */
	public function setDatabaseName($name);

	/**
	 *
	 * @param string $password
	 */
	public function setDatabasePassword($password);

	/**
	 *
	 * @param string $username
	 */
	public function setDatabaseUsername($username);

	/**
	 *
	 * @param string $url
	 */
	public function setHomeUrl($url);

	/**
	 *
	 * @param string $key
	 */
	public function setLoggedInKey($key);

	/**
	 *
	 * @param string $salt
	 */
	public function setLoggedInSalt($salt);

	/**
	 *
	 * @param string $key
	 */
	public function setNonceKey($key);

	/**
	 *
	 * @param string $salt
	 */
	public function setNonceSalt($salt);

	/**
	 *
	 * @param string $dir
	 */
	public function setMuPluginDir($dir);

	/**
	 *
	 * @param $dir
	 */
	public function setPluginDir($dir);

	/**
	 *
	 * @param string $key
	 */
	public function setSecureAuthKey($key);

	/**
	 *
	 * @param string $salt
	 */
	public function setSecureAuthSalt($salt);

	/**
	 *
	 * @param string $prefix
	 */
	public function setTablePrefix($prefix);

	/**
	 *
	 * @param string $dir
	 */
	public function setWpInstallDir($dir);
}