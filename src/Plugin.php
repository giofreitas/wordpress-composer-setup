<?php
namespace Gio\WordPress\Setup;

use Composer\Installer\PackageEvent;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvents;
use Composer\Package\PackageInterface;

/**
 *
 */
class Plugin implements PluginInterface, EventSubscriberInterface{

	/**
	 * @var IOInterface
	 */
	private $io;

	/**
	 * @var Composer
	 */
	private $composer;

	/**
	 *
	 * @var Factory
	 */
	private $factory;

	/**
	 * @param Composer $composer
	 * @param IOInterface $io
	 */
	public function activate(Composer $composer, IOInterface $io){

		$this->io = $io;

		$this->composer  = $composer;

		$this->factory = new Factory($this);

		//$wordpressInstaller = $this->factory->getWordPressInstaller();
		//$this->composer->getInstallationManager()->addInstaller($wordpressInstaller);

		///$this->setupConfig();
	}

	/**
	 *
	 * @return Composer
	 */
	public function getComposer(){
		return $this->composer;
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
	 * @return IOInterface
	 */
	public function getIO(){
		return $this->io;
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
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return array(
			PackageEvents::POST_PACKAGE_INSTALL => ['onPostWordPressCoreInstall', -99]
		);
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
	 * This function will be called right after any package is installed. For the johnpbloch/wordpress-core package,
	 * runs the setup to generate the config file
	 * @param PackageEvent $event
	 */
	public function onPostWordPressCoreInstall(PackageEvent $event){
		/* @var $package PackageInterface */
		$package = $event->getOperation()->getPackage();
		// return if composer did not installed johnpbloch/wordpress-core package
		if ($package->getName() !== 'johnpbloch/wordpress-core')
			return;

		$this->setupConfig();
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

	/**
	 *
	 */
	public function setupConfig() {

		$configManager = $this->factory->getWpConfigManager();
		$wpConfig = $configManager->parseWpConfig();
		if (!is_null($wpConfig)){
			$this->io->write('<info>WordPress config file (wp-config.php) already exists. Skipping configuration...</info>');
			return;
		}
//
		$generatedFiles = $configManager->generateWpConfig();
		$this->io->write(sprintf("<info>WordPress config file(s) created successfully. You should commit this files:%s</info>", "\n" .implode("\n", $generatedFiles)));
	}

}