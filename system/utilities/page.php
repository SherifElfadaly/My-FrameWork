<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

class Page
{
	/**
	*Template properties
	*/
	protected $pages         = array();
	protected $page          = null;
	protected $variables     = array();
	private static $page_obj = null;

	public function __construct()
	{
	}

	public function __set ($name,$value)
	{
		$this->variables[$name] = $value;
	}

	public function __get ($name)
	{
		if (!array_key_exists($name, $this->variables))
		{
			throw new Exception("Variable dosen't exist", 1);
		}
		return $this->variables[$name];
	}

	/**
	*Function used to initialize
	*only one instance of the class
	*/
	public static function getPageObj()
	{
		if (self::$page_obj === null)
		{
			self::$page_obj = new Page();
		}
		return self::$page_obj;
	}

	/**
	*Function loads the page
	*and cach it in pages array
	*/
	public function load($page)
	{
		if (array_key_exists($page, $this->pages))
		{
			$this->page = $this->pages[$page];
		}
		else
		{
			if (!file_exists($page))
			{
				throw new Exception("Page not found", 1);
			}
			$this->page         = file_get_contents($page);
			$this->pages[$page] = $this->page;
		}
	}
	/**
	*Function gets the content of the current page
	*/
	public function getPageContent()
	{
		return $this->page;
	}
	/**
	*Function that bind the variables in the
	*page with it's values
	*/
	public function bindParams()
	{
		foreach ($this->variables as $key => $value)
		{
			$this->page = str_replace('{'.$key.'}', htmlspecialchars($value), $this->page);
		}
	}
	/**
	*Function that parse the loop pased on
	*a given array .Array elements names must match
	*the elements in the loop
	*/
	public function startLoop($name , $array)
	{
		while (	 preg_match('#.*{loop:'.$name.'}.*#is' , $this->page) )
		{
			$match = array();
			preg_match_all('#.*{loop:'.$name.'}(.*?){end loop:'.$name.'}.*#is', $this->page , $match);
			$temp = $match[1][0];
			$result = null;

			foreach ($array as $element)
			{
				foreach ($element as $key => $value)
				{
					$temp = str_replace('{'.$key.'}', htmlspecialchars($value), $temp);
				}
				$result .= $temp;
				$temp    = $match[1][0];
			}
			$this->page = str_replace('{loop:'.$name.'}'.$match[1][0].'{end loop:'.$name.'}', $result, $this->page);
		}
	}
	/**
	*Function that parse the if condition pased on
	*a given true or false condition
	*/
	public function bindIf($name , $condition)
	{
		while ( preg_match('#.*{if:'.$name.'}.*#is' , $this->page) )
		{
			$match = array();
			preg_match_all('#.*{if:'.$name.'}(.*?){endif:'.$name.'}.*#is', $this->page , $match);

			$this->page = $condition ? str_replace('{if:'.$name.'}'.$match[1][0].'{endif:'.$name.'}', $match[1][0], $this->page) : str_replace('{if:'.$name.'}'.$match[1][0].'{endif:'.$name.'}', '', $this->page);
		}
	}
	/**
	*Function that pind the variables in the
	*page with it's values then loads the page
	*/
	public function execute()
	{
		while ( preg_match('#.*{*}.*#is' , $this->page)) 
		{
			foreach ($this->variables as $key => $value)
			{
				$this->page = str_replace('{'.$key.'}', $value, $this->page);
			}
		}
		echo $this->page;
	}
}
/* End of file page.php */