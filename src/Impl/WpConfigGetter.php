<?php
namespace Gio\WordPress\Setup\Impl;

use Composer\IO\IOInterface;

use Gio\WordPress\Setup\ComposerFile;
use Gio\WordPress\Setup\WpConfigGetter as WpConfigGetterInterface;
use Gio\WordPress\Setup\WpConfig;

/**
 * Class WpConfigGetterImpl
 * @package Gio
 */
class WpConfigGetter implements WpConfigGetterInterface {


    /**
     * @var IOInterface
     */
    private $io;

	/**
	 *
	 * @var ComposerFile
	 */
	private $composeFile;

	/**
	 *
	 * @var WpConfig
	 */
	private $wpConfig;

    /**
     *
     *
     * @param IOInterface $io
     * @var ComposerFile $plugin
     * @var WpConfig $wpConfig
     */
	public function __construct(IOInterface $io, ComposerFile $composeFile, WpConfig $wpConfig){
	    $this->io = $io;
		$this->composeFile = $composeFile;
		$this->wpConfig = $wpConfig;
	}

	/**
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param string $databaseName
	 * @throws \Exception
	 * @return true;
	 */
	private function checkDatabaseConnection($host, $username, $password, $databaseName){
		$mysqliConnection = @mysqli_connect($host, $username, $password, $databaseName);
		if(!$mysqliConnection)
			throw new \Exception(mysqli_connect_error(), mysqli_connect_errno());

		mysqli_close($mysqliConnection);
		return true;
	}

	/**
	 *
	 * @return array
	 */
	private function getDatabaseDetails(){
		// ask for database details.
		$dbDetails = array(
			'DB_NAME'		=> $this->io->ask("In order to setup your WordPress environment, we need to create the config file now. So, let's start by entering the database connection details.\nDatabase Name [wordpress]: ", "wordpress"),
			'DB_USER'		=> $this->io->ask("Username [root]: ", "root"),
			'DB_PASSWORD'	=> $this->io->ask("Password: "),
			'DB_HOST' 		=> $this->io->ask("Database Host [localhost]: ", "localhost"),
			'TABLE_PREFIX'	=> $this->io->ask("Table prefix [wp_]: ", "wp_")
		);
		// check connection with the inserted details.
		try{
			$this->checkDatabaseConnection($dbDetails['DB_HOST'], $dbDetails['DB_USER'], $dbDetails['DB_PASSWORD'], $dbDetails['DB_NAME']);
		}
		// if connection fails print a warning with error message
		catch(\Exception $exception){
			$this->io->writeError("<warning>Warning: Could not connect to the database '{$exception->getMessage()}'.</warning>");
		}
		return $dbDetails;
	}

	/**
	 *
	 * @return array
	 */
	private function getSecretKeys(){

		$secretKeys = array_fill_keys(['AUTH_KEY', 'AUTH_SALT', 'LOGGED_IN_KEY', 'LOGGED_IN_SALT', 'NONCE_KEY', 'NONCE_SALT', 'SECURE_AUTH_KEY', 'SECURE_AUTH_SALT'], '');
		// fetch secret keys from WordPress service
		$secretKeysContent = file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/');

		$matches = array();
		if($secretKeysContent && preg_match_all("/define\('(\w+)',\s+\'([^']+)\'\);/", $secretKeysContent, $matches)){
			array_walk($secretKeys, function(&$secret, $key) use($matches){
				$index = array_search($key, $matches[1]);
				$secret = $matches[2][$index];
			});
		}
		// If we were not able to fetch secret keys, lets build them
		else {
			$md5 = time();
			foreach(array_keys($secretKeys) as $key)
				$secretKeys[$key] = ($md5 = md5($md5)) . md5($md5);
		}
		return $secretKeys;
	}

	/**
	 *
	 * @return WpConfig
	 * @throws \Exception
	 */
	public function getWpConfig(){

		$wpCoreDir = $this->composeFile->getWpCoreDir();
		$wpContentDir = $this->composeFile->getWpContentDir();
		$pluginDir = $this->composeFile->getPluginsDir();
		$muPluginsDir = $this->composeFile->getMuPluginsDir();
		$vendorDir = $this->composeFile->getVendorDir();
		// make sure content files does not live inside forbidden folder
		if ($wpContentDir === $wpCoreDir)
			throw new \Exception("Invalid content dir '$wpContentDir'. It can not be the same directory as the Wordpress core");
		if (strpos($wpContentDir, $wpCoreDir . '/') === 0)
			throw new \Exception("Invalid content dir '$wpContentDir'. It can not be inside of WordPress core directory '$wpCoreDir'.");
		if ($wpContentDir === $vendorDir)
			throw new \Exception("Invalid content dir '$wpContentDir'. It can not be the same as the vendor directory");
		if (strpos($wpContentDir, $vendorDir . '/') === 0)
			throw new \Exception("Invalid content dir '$wpContentDir'. It can not be inside of vendor directory '$vendorDir'.");

		$wpConfig = clone $this->wpConfig;
		$wpConfig->setWpInstallDir($wpCoreDir);
		$wpConfig->setContentDir($wpContentDir);
		$wpConfig->setPluginDir($pluginDir);
		$wpConfig->setMuPluginDir($muPluginsDir);
		// set home url if not empty
		if($homeUrl = $this->composeFile->getSiteUrl())
			$wpConfig->setHomeUrl($homeUrl);
		// set Database details
		$dbDetails = $this->getDatabaseDetails();
		$wpConfig->setDatabaseName($dbDetails['DB_NAME']);
		$wpConfig->setDatabaseUsername($dbDetails['DB_USER']);
		$wpConfig->setDatabasePassword($dbDetails['DB_PASSWORD']);
		$wpConfig->setDatabaseHost($dbDetails['DB_HOST']);
		$wpConfig->setTablePrefix($dbDetails['TABLE_PREFIX']);
		// Set secret keys
		$secretKeys = $this->getSecretKeys();
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
}