<?php if ( ! defined('BASEURL')) exit('No direct script access allowed');

class Cache
{
	private $cach_file_name = null;
	private $cache_time     = null;
	private $temp_cach_file = null;
	private $file           = null;

	function __construct($cach_file_name , $cache_time = false) 
	{
		$this->cach_file_name = $cach_file_name;
		$this->temp_cach_file = $cach_file_name . '.' . getmypid();
		$this->cache_time     = $cache_time;
	}

	function remove()
	{
		if (file_exists($this->cach_file_name)) 
		{
			unlink($this->cach_file_name);
		}
	}

	function begin()
	{
		if (file_exists($this->cach_file_name) && filesize($this->cach_file_name) && (fileatime($this->cach_file_name) + $this->cache_time > time() || !$this->cache_time))
		{
			include $this->cach_file_name;
			echo "Cached Version";
			exit();
		}
		else
		{
			$this->file = fopen($this->temp_cach_file , 'w');
			ob_start();
		}
	}

	function end()
	{
		if ($this->file) 
		{
			fwrite($this->file, ob_get_contents());
			fclose($this->file);
			rename($this->temp_cach_file, $this->cach_file_name);
			ob_end_flush();
		}
	}
}
/* End of file cache.php */