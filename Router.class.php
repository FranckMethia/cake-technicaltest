<?php

class Router
{
	public static $routes = array();

	public static function addRoute($httpMethod, $path, $controller, $action)
	{
		static::$routes[] = array(
			'httpMethod'		=> $httpMethod,
			'path'			=> $path,
			'controller'		=> $controller,
			'action'		=> $action
		);
	}

	public static function doRoute($httpMethod, $path)
	{
		echo $httpMethod . " " . $path;
	}
}
