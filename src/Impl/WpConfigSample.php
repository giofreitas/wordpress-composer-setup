<?php
namespace Gio\WordPress\Setup\Impl;

use Gio\WordPress\Setup\WpConfigSample as WpConfigSampleInterface;

/**
 *
 */
class WpConfigSample implements WpConfigSampleInterface {

	/**
	 * @var string
	 */
	private $contents;

	/**
	 *
	 * @param array ...$lines
	 * @return WpConfigSampleInterface
	 */
	public function addLines(...$lines){
		$this->contents = preg_replace_callback("/\/\*\*.*\*\/\s*\nrequire_once\(ABSPATH\s.\s'wp-settings\.php'\);/", function($matches) use ($lines){
			return implode(PHP_EOL, $lines) . PHP_EOL . $matches[0];
		}, $this->contents);
		return $this;
	}


	/**
	 *
	 * @param string $comment
	 * @return WpConfigSampleInterface
	 */
	public function addComment($comment){

		if (strpos($comment, PHP_EOL) !== false)
			$comment = str_replace(PHP_EOL, PHP_EOL . ' * ', PHP_EOL . $comment) . PHP_EOL;

		$this->addLines("/**  $comment */");
		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return WpConfigSampleInterface
	 */
	public function addDefinition($name, $value){
		$this->addLines("define('$name', $value);");
		return $this;
	}

	/**
	 *
	 * @return WpConfigSampleInterface
	 */
	public function addEmptyLine() {
		$this->addLines("");
		return $this;
	}

	/**
	 *
	 * @param $path
	 * @return WpConfigSampleInterface
	 */
	public function addRequire( $path ) {
		$this->addLines("require $path;");
		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function getContents(){
		return $this->contents;
	}


	/**
	 * @param string $name
	 * @param string $value
	 * @return WpConfigSampleInterface
	 */
	public function setDefinition($name, $value){
		$this->contents = preg_replace_callback("/define\('$name',(\s+)'[^']+'\);/", function($matches) use ($name, $value){
			return "define('{$name}',{$matches[1]}'$value');";
		}, $this->contents);
		return $this;
	}

	/**
	 *
	 * @param string $variable
	 * @param string $value
	 * @return WpConfigSampleInterface
	 */
	public function setVariable($variable, $value){
		$this->contents = preg_replace_callback("/\\\$$variable(\s+)= '[^']+';/", function($matches) use ($variable, $value){
			return "$$variable{$matches[1]}= '$value';";
		}, $this->contents);
		return $this;
	}

	/**
	 *
	 * @param $contents
	 */
	public function setContents($contents){
		$this->contents = $contents;
	}
}