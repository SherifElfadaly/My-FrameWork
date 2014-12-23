<?php 
error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) . DS);
$doc_root  = preg_replace("!{$_SERVER['SCRIPT_NAME']}$!", '', $_SERVER['SCRIPT_FILENAME']);
define('BASEURL', preg_replace("!^{$doc_root}!", '', ROOT));

require ROOT . 'system' . DS. 'core' . DS . 'bootstrap.php';
/* End of file index.php */