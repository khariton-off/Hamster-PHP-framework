<?php
class Hamster {
	protected static $configs = array();
	/**
	 * Start application
	 * @param type $configs
	 * @return type
	 */
	public static function run($configs) {

		# Include autoloader
		require_once 'core/autoloader.php';

		# Set all configs for global var
		self::$configs = $configs;

		# Dirs for autoload
		$autoload_dirs = self::$configs['autoloader']['directories'];

		# File formats for autoload
		$autoload_fileFormats = self::$configs['autoloader']['fileFormats'];

		# Autoloader initialization
		Autoloader::init('./core/', $autoload_dirs, $autoload_fileFormats);
		function __autoload($class) {
			return Autoloader::load($class);
		}

		# Start router
		$Router = new Router;
		$Router->start(self::$configs['path']['application']['controllers']);

		# Save pathes to autoload files
		Autoloader::save();
		return;
	}
}