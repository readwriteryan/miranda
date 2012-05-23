<?php
namespace miranda\cache\drivers;
use miranda\cache\CacheInterface;

define('MEMCACHED_SERVER', 'localhost');
define('MEMCACHED_PORT', '11211');

class MemcacheDriver implements CacheInterface
{
	private $engine;
	
	public function __construct($memcacheServer = MEMCACHED_SERVER, $memcachePort = MEMCACHED_PORT)
	{
		$memcache = new \Memcache();
		$memcache -> connect($memcacheServer, $memcachePort);
		
		$this -> engine = $memcache;
	}
	
	public function __cal()
	{
		$arguments = function_get_args();
		return call_user_func_array($this -> engine, $arguments);
	}
	
	public function get($key)
	{
		return $this -> engine -> get($key);
	}
	
	public function set($key, $value, $expire)
	{
		return $this -> engine -> set($key, $value, false, $expire);
	}
	
	public function add($key, $value, $expire)
	{
		return $this -> engine -> add($key, $value, false, $expire);
	}
	
	public function delete($key)
	{
		return $this -> engine -> delete($key);
	}
	
	public function flush()
	{
		return $this -> engine -> flush();
	}
	
	public function inc($key, $amount = 1)
	{
		return $this -> engine -> increment($key, $amount);
	}
	
	public function dec($key, $amount = 1)
	{
		return $this -> engine -> decrement($key, $amount);
	}
}
?>