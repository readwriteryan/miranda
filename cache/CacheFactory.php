<?php
namespace miranda\cache;

class CacheFactory
{
    private static $instances = array();
    
    protected function __construct() {}
    
    public static function getInstance($cacheDriver)
    {
		$driver = CACHE_NAMESPACE . 'drivers\\' . ucfirst($cacheDriver) . 'Driver';
		
		if(!isset($instances[$cacheDriver]))
			$instances[$cacheDriver] = new $driver;
			
		return $instances[$cacheDriver];
    }
}
?>