<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

function error_404()
{
	header("HTTP/1.0 404 Not Found");
	require ROOT . 'system' . DS . 'errors' . DS . '404.php';
	exit();
}

function user_error_handler($severity, $msg, $filename, $linenum)
{
	error_log('error '. $msg . ' at ' . $filename . ' line ' .$linenum .' '.date('Y/m/d h:m:s')." \r\n" , 3 ,ROOT . 'system/errors/error.log');
	require ROOT . 'system/errors/error.php';
	exit();
}
set_error_handler('user_error_handler');


function user_exception_handler($e)
{
	error_log('error '. $e .' '.date('Y/m/d h:m:s')." \r\n" , 3 ,ROOT . 'system/errors/error.log');
	require ROOT . 'system/errors/error.php';
	exit();
}
set_exception_handler('user_exception_handler');
/* End of file errorhandler.php */