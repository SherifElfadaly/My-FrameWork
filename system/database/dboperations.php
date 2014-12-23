<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

abstract class DbOperations
{
	private static $db_obj = null;
	public static function getDbObj()
	{
		global $config;
		if (self::$db_obj === null) 
		{
			switch ($config['db']['dbdriver']) 
			{
				
				case 'sqllite':
				self::$db_obj = new DbDriverOperations("{$config['db']['dbdriver']}:{$config['db']['dbpath']};");
				break;

				case 'oracle':
				self::$db_obj = new DbDriverOperations("oci:dbname={$config['db']['database']};charset=UTF-8"
					, $config['db']['username'] 
					, $config['db']['password']);
				break;

				case 'mysql':
				self::$db_obj = new DbDriverOperations("mysql:host={$config['db']['hostname']};dbname={$config['db']['database']}"
					, $config['db']['username'] 
					, $config['db']['password']);
				break;

				default:
				break;
			}
		}
		self::$db_obj->connect();
		return self::$db_obj;
	}
}

class DbDriverOperations
{
	private $CONN_STRING;
	private $USERNAME;
	private $PASSWORD;

	protected static $CONN = null;

	function __construct($conn_string , $user_name = false , $password = false)
	{
		$this->CONN_STRING = $conn_string;
		$this->USERNAME = $user_name;
		$this->PASSWORD = $password;
	}

	function connect()
	{
		if (self::$CONN === null) 
		{
			self::$CONN = new PDO($this->CONN_STRING , $this->USERNAME  , $this->PASSWORD);
		}	
	}

	function startTransaction()
	{
		PDO::beginTransaction();
	}

	function commit()
	{
		PDO::commit();
	}

	function rollback()
	{
		PDO::rollback();
	}

	function executeQuery($query , $data = array() , &$affected_rows = false)
	{
		$result = self::$CONN->prepare($query);
		$result->execute($data);

		$rows = array();
		while ( $row = $result->fetch(PDO::FETCH_OBJ)) 
		{
			$rows[] = $row;
		}

		$affected_rows = $result->rowCount();
		return $rows;
	}
}
/* End of file dboperations.php */