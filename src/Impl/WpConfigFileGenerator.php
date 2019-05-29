<?php
namespace Gio\WordPress\Setup\Impl;

use Gio\WordPress\Setup\WpConfigFileGenerator as WpConfigFileGeneratorInterface;
use Gio\WordPress\Setup\WpConfig;
use Gio\WordPress\Setup\WpConfigSample;
use Gio\WordPress\Setup\ComposerFile;

/**
 *
 * Class WpConfigFileGeneratorImpl
 * @package Gio\Composer\Plugins\SetupWpConfig
 */
class WpConfigFileGenerator implements WpConfigFileGeneratorInterface{

	/**
	 *
	 * @var ComposerFile
	 */
	private $composeFile;

	/**
	 * @var WpConfigSample
	 */
	private $sample;

	/**
	 *
	 * @param ComposerFile $composeFile
	 * @param WpConfigSample $sample
	 */
	public function __construct(ComposerFile $composeFile, WpConfigSample $sample) {
		$this->composeFile = $composeFile;
		$this->sample = $sample;
	}

	/**
	 *
	 * @param WpConfig $wpConfig
	 * @return array
	 * @throws \Exception
	 */
	public function generateWpConfigFile( WpConfig $wpConfig){

		$wpCoreDir = $wpConfig->getWpInstallDir();

		$contents = file_get_contents("$wpCoreDir/wp-config-sample.php");
		if (!$contents)
			throw new \Exception("File 'wp-config-sample.php' not fount on '$wpCoreDir' directory");

		$sample = clone $this->sample;
		$sample->setContents($contents);

		$wpContentDir = $wpConfig->getContentDir();
		$pluginDir = $wpConfig->getPluginDir();
		$muPluginDir = $wpConfig->getMuPluginDir();
		$homeUrl = $this->getHomeUrl($wpConfig);
		$rootDir = $this->getRootDir($wpCoreDir);
		$vendorDir = $this->composeFile->getVendorDir();

		$sample
			// Database definitions
			->setDefinition('DB_NAME',			$wpConfig->getDatabaseName())
			->setDefinition('DB_USER',			$wpConfig->getDatabaseUsername())
			->setDefinition('DB_PASSWORD',		$wpConfig->getDatabasePassword())
			->setDefinition('DB_HOST',			$wpConfig->getDatabaseHost())
			->setDefinition('table_prefix',		$wpConfig->getTablePrefix())
			// secret keys definitions
			->setDefinition('AUTH_KEY',			$wpConfig->getAuthKey())
			->setDefinition('SECURE_AUTH_KEY',	$wpConfig->getSecureAuthKey())
			->setDefinition('LOGGED_IN_KEY',	$wpConfig->getLoggedInKey())
			->setDefinition('NONCE_KEY',		$wpConfig->getNonceKey())
			->setDefinition('AUTH_SALT',		$wpConfig->getAuthSalt())
			->setDefinition('SECURE_AUTH_SALT',	$wpConfig->getSecureAuthSalt())
			->setDefinition('LOGGED_IN_SALT',	$wpConfig->getLoggedInSalt())
			->setDefinition('NONCE_SALT',		$wpConfig->getNonceSalt())
			// add new defines
			->addComment("Definitions injected by WordPressSetupConfig composer plugin")
			->addDefinition('WP_HOME', $homeUrl)
			->addDefinition('WP_SITEURL', "WP_HOME . '/$wpCoreDir'")
			->addDefinition('WP_CONTENT_DIR', "$rootDir . '/$wpContentDir'")
			->addDefinition('WP_CONTENT_URL', "WP_HOME . '/$wpContentDir'")
			->addDefinition('WP_PLUGIN_DIR', "$rootDir . '/$pluginDir'")
			->addDefinition('WP_PLUGIN_URL', "WP_HOME . '/$pluginDir'")
			->addDefinition('WPMU_PLUGIN_DIR', "$rootDir . '/$muPluginDir'")
			->addDefinition('WPMU_PLUGIN_URL', "WP_HOME . '/$muPluginDir'")
			->addEmptyLine()
			->addComment("Include Composer autoload file")
			->addRequire("$rootDir . '/$vendorDir/autoload.php'")
			->addEmptyLine();

		// index file to be created and placed in root folder to load WordPress frontend
		$indexSample = "<?php return require(__DIR__ . '/{$wpCoreDir}/index.php');";

		// Keep all paths of the generated files in this array to be returned later
		$generatedFiles = array();
		// generate wp-config.php file
		$wpConfigFilePath = $generatedFiles[] =  dirname($wpCoreDir) . '/wp-config.php';
		if(!file_put_contents($wpConfigFilePath, $sample->getContents()))
			throw new \Exception("Failed to create '$wpConfigFilePath' file.");
		// Generate index file to include WordPress index.php
		$indexFilePath = $generatedFiles[] = './index.php';
		if(!file_put_contents($indexFilePath, $indexSample))
			throw new \Exception("Failed to create './index.php' file.");
		return $generatedFiles;
	}

	/**
	 *
	 * @param WpConfig $wpConfig
	 * @return string
	 */
	private function getHomeUrl(WpConfig $wpConfig){
		if ($homeUrl = $wpConfig->getHomeUrl())
			return "'$homeUrl'";
		return '(!empty($_SERVER["HTTPS"]) || (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] == 443 ) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"]';
	}

	/**
	 * @param $wpInstallDir
	 *
	 * @return mixed
	 */
	private function getRootDir($wpInstallDir){
		return array_reduce(explode('/', $wpInstallDir), function($path){
			return "dirname($path)";
		}, 'ABSPATH');
	}

}