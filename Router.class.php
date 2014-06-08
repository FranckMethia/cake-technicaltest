<?php

class Router
{
	public static $routes = array();

	public static function addRoute($httpMethod, $path, $controller, $action)
	{
		static::$routes[] = array(
			'httpMethod'	=> $httpMethod,
			'path'			=> BASEURL . $path,
			'controller'	=> $controller,
			'action'		=> $action
		);
	}

	public static function doRoute($httpMethod, $path)
	{
		$messages = array();
		$find = false;
		$numberRoute = count(self::$routes);
		for($i = 0; $i < $numberRoute; $i++) {
			if ($httpMethod == self::$routes[$i]['httpMethod']) {
				$pattern = '~^' . str_replace('{id}', '(\d+)', self::$routes[$i]['path']) . '/?$~';
				$matches = null;

				if (preg_match($pattern, $path, $matches)) {
					require(__DIR__ . DIRECTORY_SEPARATOR . self::$routes[$i]['controller'] . '.class.php');
					$className = self::$routes[$i]['controller'];
					$action = self::$routes[$i]['action'];
					$find = true;

					if(count($matches) > 1) {
						$id = $matches[1];
						return $className::$action($id);
					}
					else {
						return $className::$action();
					}
				}
			}
		}
		if(!$find) {
			$messages['errors'][] = 'Aucune route valide !';
		}
		echo json_encode($messages);
	}
}
