<?php
namespace Gio\WordPress\Setup;

/**
 *
 */
interface WpConfigSample {

	/**
	 *
	 * @param string $comment
	 * @return WpConfigSample
	 */
	public function addComment($comment);

	/**
	 *
	 * @param string $name
	 * @param string $value
	 * @return WpConfigSample
	 */
	public function addDefinition($name, $value);

	/**
	 * @return WpConfigSample
	 */
	public function addEmptyLine();

	/**
	 * @param $path
	 *
	 * @return WpConfigSample
	 */
	public function addRequire($path);


	/**
	 *
	 * @return string
	 */
	public function getContents();

	/**
	 *
	 * @param string $contents
	 */
	public function setContents($contents);

	/**
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return WpConfigSample
	 */
	public function setDefinition($name, $value);

	/**
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return WpConfigSample
	 */
	public function setVariable($name, $value);

}