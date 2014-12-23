<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

class DbObject
{	
	protected $variables = array();
	protected $table_name = null;
	protected $id_name = null;
	protected $id = null;
	protected $select_commande = null;
	protected static $db = null;

	public function __set ($name,$value)
	{
		$this->variables[$name] =  htmlspecialchars($value);
	}

	public function __get ($name)
	{
		if (!array_key_exists($name, $this->variables))
		{
			throw new Exception("Variable dosen't exist", 1);
		} 
		return $this->variables[$name];
	}

	public function __construct($table_name , $id_name = false , $id = false)
	{
		self::$db = DbOperations::getDbObj();
		$this->table_name = $table_name;
		$this->id_name = $id_name;
		$this->id = htmlspecialchars($id);

		if ($id && $id_name) 
		{
			$query = "select * from {$this->table_name} where {$this->id_name} = :id";

			$data  = self::$db->executeQuery($query , array('id' =>  $this->id));

			if (!$data) 
			{
				throw new Exception("DBObject dosen't exists", 1);
			}

			foreach ($data[0] as $key => $value) 
			{
				$this->$key = $value;
			}
		}
	}

	public function getAllData($start = false , $limit = false)
	{
		$query = $start && $limit ? "select * from {$this->table_name} limit :start , :limit" : "select * from {$this->table_name}";
		$data = self::$db->executeQuery($query , array('start' => $start , 'limit' => $limit));
		$allData = array();

		foreach ($data as $element) 
		{
			$allData[] = $element;
		}
		return $allData;
	}

	public function select($columns = '*')
	{
		$this->select_commande = "select $columns from {$this->table_name}";
		return $this;
	}

	public function where($condition)
	{
		$this->select_commande .= " where $condition";
		return $this;
	}

	public function andWhere($condition)
	{
		$this->select_commande .= " and $condition";
		return $this;
	}

	public function orWhere($condition)
	{
		$this->select_commande .= " or $condition";
		return $this;
	}

	public function limit($start , $count)
	{
		$this->select_commande .= " limit $start , $count";
		return $this;
	}

	public function execute($data = array())
	{
		$data = self::$db->executeQuery($this->select_commande , $data);
		$allData = array();

		foreach ($data as $element) 
		{
			$allData[] = $element;
		}
		return $allData;
	}

	public function insert(&$affected_rows)
	{
		if ($this->id) 
		{
			throw new Exception("DBObject hase id ,Can't insert", 1);
		}

		$keys = null;
		$values = null;
		foreach ($this->variables as $key => $value) 
		{
			$keys .= "$key , ";
			$values .= ":$key , ";
		}
		
		$keys = rtrim($keys , ', ');
		$values = rtrim($values , ', ');
		$query = "insert into {$this->table_name} ($keys) values($values)";

		return self::$db->executeQuery($query , $this->variables , $affected_rows);
	}

	public function update(&$affected_rows)
	{
		if (!$this->id) 
		{
			throw new Exception("DBObject must have id ,Can't Update", 1);
		}

		$keys = null;
		foreach ($this->variables as $key => $value) 
		{
			$keys .= "$key = :$key , ";
		}
		$keys = rtrim($keys , ', ');
		$query = "update {$this->table_name} set $keys where {$this->id_name} = {$this->id}";

		return self::$db->executeQuery($query , $this->variables , $affected_rows);
	}

	public function delete(&$affected_rows)
	{
		if (!$this->id) 
		{
			throw new Exception("DBObject must have id ,Can't Delete", 1);
		}

		$query = "delete from {$this->table_name} where {$this->id_name} = {$this->id}";
		return self::$db->executeQuery($query , $affected_rows);
	}
}
/* End of file dvopject.php */