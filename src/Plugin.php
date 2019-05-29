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
		$this->factory = new Factory($composer, $this->io);

		//$this->setupConfig();
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
	 */
	public function setupConfig() {

		$configManager = $this->factory->getWpConfigManager();
		$wpConfig = $configManager->parseWpConfig();
		if (!is_null($wpConfig)){
			$this->io->write('<info>WordPress config file (wp-config.php) already exists. Skipping configuration...</info>');
			return;
		}
		$generatedFiles = $configManager->generateWpConfig();
		$this->io->write(sprintf("<info>WordPress config file(s) created successfully. You should commit this files:%s</info>", "\n" .implode("\n", $generatedFiles)));
	}

}