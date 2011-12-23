<?php
class Hamster {
	protected static $configs = array();
	/**
	 * Start application
	 * @param type $configs
	 * @return type
	 */
	public static function run($configs) {
		require_once 'core/autoloader.php';
		self::$configs = $configs;
		$autoload_dirs = self::$configs['autoloader']['directories'];
		$autoload_fileFormats = self::$configs['autoloader']['fileFormats'];
		Autoloader::init('./core/', $autoload_dirs, $autoload_fileFormats);
		function __autoload($class) {
			return Autoloader::load($class);
		}
		$Router = new Router;
		$Router->start(self::$configs['path']['application']['controllers']);
		Autoloader::save();
		return;
	}
}