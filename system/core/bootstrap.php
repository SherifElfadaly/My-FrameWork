<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

require ROOT . 'system' . DS . 'utilities' . DS . 'config.php';
require ROOT . 'system' . DS . 'utilities' . DS . 'errorhandler.php';

if(!isset($_SESSION))
{
	session_start();
}

function __autoload($class)
{
	$class = strtolower($class);
	if (file_exists(ROOT . 'application' . DS . 'models' . DS . $class . '.php')) 
	{
		require ROOT . 'application' . DS . 'models' . DS . $class . '.php';
	}
	else if (file_exists(ROOT . 'application' . DS . 'controllers' . DS . $class . '.php')) 
	{
		require ROOT . 'application' . DS . 'controllers' . DS . $class . '.php';
	}
	else if (file_exists(ROOT . 'system' . DS . 'database' . DS . $class . '.php')) 
	{
		require ROOT . 'system' . DS . 'database' . DS . $class . '.php';
	}
	else if (file_exists(ROOT . 'system' . DS . 'utilities' . DS . $class . '.php')) 
	{
		require ROOT . 'system' . DS . 'utilities' . DS . $class . '.php';
	}
	else if (file_exists(ROOT . 'system' . DS . 'core' . DS . $class . '.php')) 
	{
		require ROOT . 'system' . DS . 'core' . DS . $class . '.php';
	}
}

$url = !empty($_GET['url']) ? $_GET['url'] : '';
Router::route($url);
/* End of file bootstrap.php */