<?php
namespace miranda\cache\drivers;
use miranda\cache\CacheInterface;


class RedisDriver implements CacheInterface
{
	private $engine;
	
	public function __construct($redisServer = REDIS_SERVER, $redisPort = REDIS_PORT)
	{
		$redis = new \Redis();
		$redis -> connect($redisServer, $redisPort);
		
		$this -> engine = $redis;
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
		return $this -> engine -> setex($key, $expire, $value);
	}
	
	public function add($key, $value, $expire)
	{
		return $this -> engine -> setnx($key, $value) && $this -> engine -> setTimeout($key, $expire);
	}
	
	public function delete($key)
	{
		return $this -> engine -> delete($key);
	}
	
	public function flush()
	{
		return $this -> engine -> flushAll();
	}
	
	public function inc($key, $amount)
	{
		return $this -> engine -> incr($key, $amount = 1);
	}
	
	public function dec($key, $amount)
	{
		return $this -> engine -> decr($key, $amount = 1);
	}
}
?>