<?php
namespace Gio\WordPress\Setup\Impl;

use Gio\WordPress\Setup\WpConfig as WpConfigInterface;

/**
 *
 */
class WpConfig implements WpConfigInterface {

	/**
	 *
	 * @var array
	 */
	private $secretKeys = array();

	/**
	 *
	 * @var array
	 */
	private $databaseDetails = array();

	/**
	 *
	 * @var string
	 */
	private $contentDir = '';

	/**
	 *
	 * @var string
	 */
	private $homeUrl = '';

	/**
	 * @var string
	 */
	private $muPluginDir = '';

	/**
	 *
	 * @var string
	 */
	private $pluginDir = '';

	/**
	 *
	 * @var string
	 */
	private $wpInstallDir = '';

	/**
	 *
	 * @return string
	 */
	public function getAuthKey() {
		return $this->secretKeys['AUTH_KEY'];
	}

	/**
	 *
	 * @return string
	 */
	public function getAuthSalt() {
		return $this->secretKeys['AUTH_SALT'];
	}

	/**
	 *
	 * @return string
	 */
	public function getContentDir() {
		return $this->contentDir;
	}

	/**
	 *
	 * @return string
	 */
	public function getDatabaseHost() {
		return $this->databaseDetails['DB_HOST'];
	}

	/**
	 *
	 * @return string
	 */
	public function getDatabaseName() {
		return $this->databaseDetails['DB_NAME'];
	}

	/**
	 *
	 * @return string
	 */
	public function getDatabasePassword() {
		return $this->databaseDetails['DB_PASSWORD'];
	}

	/**
	 *
	 * @return string
	 */
	public function getDatabaseUsername() {
		return $this->databaseDetails['DB_USER'];
	}

	/**
	 *
	 * @return string
	 */
	public function getHomeUrl() {
		return $this->homeUrl;
	}

	/**
	 *
	 * @return string
	 */
	public function getLoggedInKey() {
		return $this->secretKeys['LOGGED_IN_KEY'];
	}

	/**
	 *
	 * @return string
	 */
	public function getLoggedInSalt() {
		return $this->secretKeys['LOGGED_IN_SALT'];
	}

	/**
	 *
	 * @return string
	 */
	public function getNonceKey() {
		return $this->secretKeys['NONCE_KEY'];
	}

	/**
	 *
	 * @return string
	 */
	public function getNonceSalt() {
		return $this->secretKeys['NONCE_SALT'];
	}

	/**
	 *
	 * @return string
	 */
	public function getMuPluginDir(){
		return $this->muPluginDir;
	}

	/**
	 *
	 * @return string
	 */
	public function getPluginDir(){
		return $this->pluginDir;
	}

	/**
	 *
	 * @return string
	 */
	public function getSecureAuthKey() {
		return $this->secretKeys['SECURE_AUTH_KEY'];
	}

	/**
	 *
	 * @return string
	 */
	public function getSecureAuthSalt() {
		return $this->secretKeys['SECURE_AUTH_SALT'];
	}

	/**
	 *
	 * @return string
	 */
	public function getTablePrefix() {
		return $this->databaseDetails['TABLE_PREFIX'];
	}

	/**
	 *
	 * @return string
	 */
	public function getWpInstallDir() {
		return $this->wpInstallDir;
	}

	/**
	 *
	 * @param string $prefix
	 */
	public function setTablePrefix($prefix){
		$this->databaseDetails['TABLE_PREFIX'] = $prefix;
	}

	/**
	 *
	 * @param string $key
	 */
	public function setAuthKey($key){
		$this->secretKeys['AUTH_KEY'] = $key;
	}

	/**
	 *
	 * @param string $salt
	 */
	public function setAuthSalt($salt){
		$this->secretKeys['AUTH_SALT'] = $salt;
	}

	/**
	 *
	 * @param $contentDir
	 */
	public function setContentDir($contentDir){
		$this->contentDir = $contentDir;
	}

	/**
	 *
	 * @param string $host
	 */
	public function setDatabaseHost($host){
		$this->databaseDetails['DB_HOST'] = $host;
	}

	/**
	 *
	 * @param string $name
	 */
	public function setDatabaseName($name){
		$this->databaseDetails['DB_NAME'] = $name;
	}

	/**
	 *
	 * @param string $password
	 */
	public function setDatabasePassword($password){
		$this->databaseDetails['DB_PASSWORD'] = $password;
	}

	/**
	 *
	 * @param string $username
	 */
	public function setDatabaseUsername($username){
		$this->databaseDetails['DB_USER'] = $username;
	}

	/**
	 *
	 * @param $homeUrl
	 */
	public function setHomeUrl($homeUrl){
		// append schema if not already there
		if(preg_match('/^https?:\/\//', $homeUrl) === 0)
			$homeUrl = "http//$homeUrl";
		$this->homeUrl = $homeUrl;
	}

	/**
	 *
	 * @param string $dir
	 */
	public function setMuPluginDir($dir){
		$this->muPluginDir = $dir;
	}

	/**
	 *
	 * @param string $dir
	 */
	public function setPluginDir( $dir ) {
		$this->pluginDir = $dir;
	}

	/**
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setSecretKey($key, $value){
		$this->secretKeys[$key] = $value;
	}


	/**
	 *
	 * @param string $key
	 */
	public function setLoggedInKey($key){
		$this->secretKeys['LOGGED_IN_KEY'] = $key;
	}

	/**
	 *
	 * @param string $salt
	 */
	public function setLoggedInSalt($salt){
		$this->secretKeys['LOGGED_IN_SALT'] = $salt;
	}

	/**
	 *
	 * @param string $key
	 */
	public function setNonceKey($key){
		$this->secretKeys['NONCE_KEY'] = $key;
	}

	/**
	 *
	 * @param string $salt
	 */
	public function setNonceSalt($salt){
		$this->secretKeys['NONCE_SALT'] = $salt;
	}

	/**
	 *
	 * @param string $key
	 */
	public function setSecureAuthKey($key){
		$this->secretKeys['SECURE_AUTH_KEY'] = $key;
	}

	/**
	 *
	 * @param string $salt
	 */
	public function setSecureAuthSalt($salt){
		$this->secretKeys['SECURE_AUTH_SALT'] = $salt;
	}

	/**
	 *
	 * @param string $wpInstallDir
	 */
	public function setWpInstallDir($wpInstallDir){
		$this->wpInstallDir = $wpInstallDir;
	}

}