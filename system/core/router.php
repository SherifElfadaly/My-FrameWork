<?php  if ( ! defined('BASEURL')) exit('No direct script access allowed');

abstract class Router
{
	private static $obj_list = array();
	public static function route($url)
	{
		$url_array = explode('/', $url);

		$controller = isset($url_array[0]) && $url_array[0] !== '' ? ucwords($url_array[0]) : ucwords(MAINPAGE);

		array_shift($url_array);

		$method = isset($url_array[0]) && $url_array[0] !== '' ? $url_array[0] : 'index';

		array_shift($url_array);

		$params = $url_array;

		if (class_exists($controller))
		{
			if (!array_key_exists($controller, self::$obj_list))
			{
				$obj = new $controller();
				self::$obj_list[$controller] = $obj;
			}

			if (method_exists(self::$obj_list[$controller] , $method)) 
			{
				call_user_func_array(array(self::$obj_list[$controller] , $method) , $params);
			}
			else 
			{
				error_404();
			}
		}
		else
		{
			error_404();
		}
	}
}
/* End of file routing.php */