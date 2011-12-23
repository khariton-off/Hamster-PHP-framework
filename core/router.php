<?php
class Router extends Hamster {
	protected static $controller_path;
	protected static $controller_file;
	protected static $controller;
	protected static $action;
	protected static $parts = array();
	/**
	 * Router
	 * @param type $controller_path
	 * @return type
	 */
	protected static function start($controller_path) {

		# If url not correct - return 404 error
		if (preg_match("~(index|\/{2,})~", $_SERVER['REQUEST_URI'])) {
			self::$controller = 'error404';
			self::$action = 'index';
		} else {

			# Set default controller and action
			self::$controller = 'index';
			self::$action = 'index';

			# Get url
			$route = (empty($_GET['rt'])) ? '' : $_GET['rt'];

			# Explode url
			$url = preg_split('[\\/]', $route, -1, PREG_SPLIT_NO_EMPTY);

			# Set controller
			if (isset($url[0])) {
				self::$controller = $url[0];
			}

			# Set action
			if (isset($url[1])) {
				self::$action = $url[1];
			}

			# Put other parts of url in array
			if (isset($url[2])) {
				$parts = array();
				for ($i = 2;$i < count($url);$i++) {
					$parts[] = $url[$i];
				}
				self::$parts = $parts;
			}
		}

		# Path to controllers folder
		self::$controller_path = $controller_path;
		if (!is_dir(self::$controller_path)) {
			throw new Exception('Неверный путь до контроллеров: ' . self::$controller_path);
		}

		# Controller file
		self::$controller_file = self::$controller_path . self::$controller . '.php';

		# If file not exists - set error 404
		if (!file_exists(self::$controller_file)) {
			self::$controller_file = self::$controller_path . 'error404.php';
			self::$controller = 'error404';
			self::$action = 'index';
		}

		# Include controller
		require_once self::$controller_file;

		# Controller class
		$class = self::$controller . 'Controller';
		$controller = new $class;

		# Call controller. No? Set error 404
		if (!is_callable(array($controller, self::$action)) or (self::$controller == 'error404' or self::$action == 'error404')) {
			unset($controller);
			unset($class);
			require_once self::$controller_path . 'error404.php';
			$controller = new error404Controller;
			self::$action = 'index';
		}
		$action = self::$action;
		$controller->$action(self::$parts);
		return;
	}
}