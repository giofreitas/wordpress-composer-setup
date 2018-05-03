<?php
namespace Gio\WordPress\Setup;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class WordPressInstaller extends  LibraryInstaller{

	/**
	 *
	 * @var Plugin
	 */
	private $plugin;

	/**
	 *
	 * @param Plugin $plugin
	 */
	public function __construct(Plugin $plugin) {
		parent::__construct($plugin->getIO(), $plugin->getComposer());

		$this->plugin = $plugin;
		// make sure wordpress is disabled on composer installer. we will handle that
		$installerDisable = (array)$this->plugin->getExtra('installer-disable', array());
		if (!in_array('wordpress', $installerDisable)){
			$installerDisable[] = 'wordpress';
			$this->plugin->setExtra('installer-disable', $installerDisable);
		}
	}

	/**
	 *
	 * @param PackageInterface $package
	 * @return string
	 */
	public function getInstallPath( PackageInterface $package ) {

		$wpContentDir = $this->plugin->getWpContentDir();
		switch ($package->getType()){
			case 'wordpress-plugin':
				return "$wpContentDir/plugins/{$this->getName($package)}/";
			case 'wordpress-theme':
				return "$wpContentDir/themes/{$this->getName($package)}/";
			case 'wordpress-muplugin':
				return "$wpContentDir/mu-plugins/{$this->getName($package)}/";
			case 'wordpress-dropin':
				return "$wpContentDir/{$this->getName($package)}/";
		}
		return "$wpContentDir/";
	}

	/**
	 *
	 * @param PackageInterface $package
	 * @return string
	 */
	private function getName(PackageInterface $package){
		// check first if installer-name is defined in extra
		$packageExtra = $package->getExtra();
		if (isset($packageExtra['installer-name']))
			return $packageExtra['installer-name'];

		$names = explode('/', $package->getPrettyName());
		return end($names);
	}

	/**
	 * @param $packageType
	 *
	 * @return bool
	 */
	public function supports( $packageType ) {
		return in_array($packageType, ['wordpress-plugin', 'wordpress-theme']);
	}
}