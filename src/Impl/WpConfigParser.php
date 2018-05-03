<?php
namespace Gio\WordPress\Setup\Impl;

use Gio\WordPress\Setup\WpConfigParser as WpConfigParserInterface;
use Gio\WordPress\Setup\WpConfig;

/**
 *
 */
class WpConfigParser implements WpConfigParserInterface{

	/**
	 *
	 * @var WpConfig
	 */
	private $wpConfig;

	/**
	 *
	 * @param WpConfig $wpConfig
	 */
	public function __construct(WpConfig $wpConfig) {
		$this->wpConfig = $wpConfig;
	}


	/**
	 *
	 * @param string $wpConfigContents
	 * @return WpConfig
	 */
	public function parse($wpConfigContents){

		$coreDir = $this->parseCoreDir($wpConfigContents);
		$contentDir = $this->parseContentDir($wpConfigContents);
		$homeUrl = $this->parseHomeUrl($wpConfigContents);
		$dbDetails = $this->parseDatabaseDetails($wpConfigContents);
		$secretKeys = $this->parseSecretKeys($wpConfigContents);

		$wpConfig = clone $this->wpConfig;
		$wpConfig->setWpInstallDir($coreDir);
		$wpConfig->setContentDir($contentDir);
		$wpConfig->setHomeUrl($homeUrl);
		$wpConfig->setDatabaseName($dbDetails['DB_NAME']);
		$wpConfig->setDatabaseUsername($dbDetails['DB_USER']);
		$wpConfig->setDatabasePassword($dbDetails['DB_PASSWORD']);
		$wpConfig->setDatabaseHost($dbDetails['DB_HOST']);
		$wpConfig->setTablePrefix($dbDetails['TABLE_PREFIX']);
		$wpConfig->setAuthKey($secretKeys['AUTH_KEY']);
		$wpConfig->setAuthSalt($secretKeys['AUTH_SALT']);
		$wpConfig->setLoggedInKey($secretKeys['LOGGED_IN_KEY']);
		$wpConfig->setLoggedInSalt($secretKeys['LOGGED_IN_SALT']);
		$wpConfig->setNonceKey($secretKeys['NONCE_KEY']);
		$wpConfig->setNonceSalt($secretKeys['NONCE_SALT']);
		$wpConfig->setSecureAuthKey($secretKeys['SECURE_AUTH_KEY']);
		$wpConfig->setSecureAuthSalt($secretKeys['SECURE_AUTH_SALT']);
		return $wpConfig;
	}

	/**
	 * @param $contents
	 *
	 * @return bool|string
	 */
	private function parseContentDir($contents){
		// Get content directory through WP_CONTENT definition
		return $this->getDefinition($contents, 'WP_CONTENT_URL', "WP_HOME \. '\/(.*)'");

	}

	/**
	 * @param $contents
	 *
	 * @return bool|string
	 */
	private function parseCoreDir($contents){
		// Get wordpress core directory through SITE_URL definition
		return $this->getDefinition($contents, 'WP_SITEURL', "WP_HOME \. '\/(.*)'");
	}

	/**
	 *
	 * @param string $contents
	 * @return array
	 */
	private function parseDatabaseDetails($contents){
		return array(
			'DB_NAME'		=> $this->getDefinition($contents, 'DB_NAME', "'(.*)'"),
			'DB_USER'		=> $this->getDefinition($contents, 'DB_USER', "'(.*)'"),
			'DB_PASSWORD'	=> $this->getDefinition($contents, 'DB_PASSWORD', "'(.*)'"),
			'DB_HOST'		=> $this->getDefinition($contents, 'DB_HOST', "'(.*)'"),
			'TABLE_PREFIX'	=> $this->getVariable($contents, 'table_prefix', "'(.*)'"),
		);
	}

	/**
	 *
	 * @param string $contents
	 * @return bool|string
	 */
	private function parseHomeUrl($contents){
		return $this->getDefinition($contents, 'WP_HOME', "'(.*)'");
	}


	/**
	 * @param string $contents
	 * @return array
	 */
	private function parseSecretKeys($contents){
		return array(
			'AUTH_KEY'			=> $this->getDefinition($contents, 'AUTH_KEY', "'([^']*)'"),
			'SECURE_AUTH_KEY'	=> $this->getDefinition($contents, 'SECURE_AUTH_KEY', "'([^']*)'"),
			'LOGGED_IN_KEY'		=> $this->getDefinition($contents, 'LOGGED_IN_KEY', "'([^']*)'"),
			'NONCE_KEY'			=> $this->getDefinition($contents, 'NONCE_KEY', "'([^']*)'"),
			'AUTH_SALT'			=> $this->getDefinition($contents, 'AUTH_SALT', "'([^']*)'"),
			'SECURE_AUTH_SALT'	=> $this->getDefinition($contents, 'SECURE_AUTH_SALT', "'([^']*)'"),
			'LOGGED_IN_SALT'	=> $this->getDefinition($contents, 'LOGGED_IN_SALT', "'([^']*)'"),
			'NONCE_SALT'		=> $this->getDefinition($contents, 'NONCE_SALT', "'([^']*)'")
		);
	}

	/**
	 *
	 * @param string contents
	 * @param string $name
	 * @param string $capture
	 * @return bool|string
	 */
	public function getDefinition($contents, $name, $capture = '(.*)'){
		$matches = array();
		if (!preg_match("/define\('$name',\s*$capture\);/", $contents, $matches))
			return false;
		return $matches[1];
	}

	/**
	 *
	 * @param string $contents
	 * @param string $name
	 * @param string $capture
	 * @return mixed
	 */
	public function getVariable($contents, $name, $capture = '(.*)'){
		$matches = array();
		if (!preg_match("/\\\$$name\s*=\s*$capture;/", $contents, $matches))
			return false;
		return $matches[1];
	}
}