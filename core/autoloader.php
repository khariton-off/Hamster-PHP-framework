<?php
class Autoloader extends Hamster {
	protected static $list = array();
	protected static $modified = false;
	protected static $directories = array();
	protected static $fileFormats = array();
	protected static $path = '';
	/**
	 * Initialization
	 * @param type $path
	 * @param type $directories
	 * @param type $fileFormats
	 * @return type
	 */
	public static function init($path, $directories, $fileFormats) {
		if (!self::$list && file_exists($path . '.autoloader.inc.php')) {
			self::$list = require_once $path . '.autoloader.inc.php';
			if (!is_array(self::$list)) {
				self::$list = array();
			}
		}
		self::$path = $path;
		self::$fileFormats = $fileFormats;
		self::$directories = $directories;
		return;
	}
	/**
	 * Load classes
	 * @param type $className
	 * @return type
	 */
	public static function load($className) {
		if (isset(self::$list[$className])) {
			if (file_exists(self::$list[$className])) {
				require_once self::$list[$className];
				return;
			} else {
				self::$list[$className] = '';
				self::$modified = true;
			}
		} else {
			self::$list[$className] = '';
			self::$modified = true;
		}
		$findPath = null;
		if (self::$fileFormats) {
			$fileFormats = self::$fileFormats;
		} else {
			$fileFormats = array('%s.php', '%s.class.php', 'class.%s.php', '%s.inc.php',);
		}
		$pearStyle = str_ireplace('_', DIRECTORY_SEPARATOR, $className);
		foreach (self::$directories as & $dir) {
			$pearPath = $dir . $pearStyle . '.php';
			if (file_exists($pearPath)) {
				$findPath = $pearPath;
				break;
			}
			foreach ($fileFormats as & $format) {
				$path = $dir . sprintf($format, $className);
				if (file_exists($path)) {
					$findPath = $path;
					break 2;
				} else if (file_exists(strtolower($path))) {
					$findPath = strtolower($path);
					break 2;
				}
			}
		}
		if (is_null($findPath)) {
			throw new Exception('Класс ' . $className . ' не найден.');
		} else {
			self::$list[$className] = $findPath;
			self::$modified = true;
			require_once $findPath;
			return;
		}
	}
	/**
	 * Save pathes to autoload files
	 * @return type
	 */
	public static function save() {
		if (self::$modified) {
			$data = "<?php return array(\n";
			if (self::$list) {
				foreach (self::$list as $class => & $path) $data.= "\t'$class' => '$path',\n";
			}
			$data.= ");";
			file_put_contents(self::$path . '.autoloader.inc.php', $data);
		}
	}
}