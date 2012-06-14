<?php
namespace miranda\controllers;
use miranda\cache\CacheFactory;

Class Request
{
    protected static $instance = NULL;
    
    protected $path;
    protected $method;
    protected $mapping = array(
			 'get'		=> GET_REQUEST,
			 'post' 	=> POST_REQUEST,
			 'put' 		=> PUT_REQUEST,
			 'delete' 	=> DELETE_REQUEST,
			 'default'	=> GET_REQUEST);
    
    protected function __construct()
    {
	$this -> getPath();
	$this -> getMethod();
    }
    
    public static function getInstance()
    {
	if(self::$instance == NULL) self::$instance = new Request();
	
	return self::$instance;
    }
    
    public function __get($member)
    {
	return $this -> $member;
    }
    
    private function getMethod()
    {
	$method = strtolower($_SERVER['REQUEST_METHOD']);
	
	if(isset($_POST['http_method_override']))
	    $method = strtolower($_POST['http_method_override']);
		
	if(isset($this -> mapping[$method]))	$this -> method = $this -> mapping[$method];
	else					$this -> method = $this -> mapping['default'];
		
    }
    
    private function getPath()
    {
	if(isset($_SERVER['PATH_INFO']))		$this -> path = $_SERVER['PATH_INFO'];
	else if(isset($_SERVER['ORIG_PATH_INFO']))	$this -> path = $_SERVER['ORIG_PATH_INFO'];
	else						$this -> path = false;
    }
}


Class Route
{
    private static $routes		= array();
    private static $default_route	= NULL;
    private static $root		= NULL;
    
    public static function get($route, $action, $restrictions = array())
    {
	self::$routes[GET_REQUEST][self::processRoute($route, $restrictions)] = $action; 
    }
    
    public static function post($route, $action, $restrictions = array())
    {
	self::$routes[POST_REQUEST][self::processRoute($route, $restrictions)] = $action; 
    }
    
    public static function put($route, $action, $restrictions = array())
    {
	self::$routes[PUT_REQUEST][self::processRoute($route, $restrictions)] = $action; 
    }
    
    public static function delete($route, $action, $restrictions = array())
    {
	self::$routes[DELETE_REQUEST][self::processRoute($route, $restrictions)] = $action; 
    }
    
    public static function custom($route, $action, $method)
    {
	$method 	= strtolower($method);
	$request 	= Request::getInstance();
		
		
	if(isset($request -> mapping[$method]))	$method = $request -> mapping[$method];
	else					$method = $request -> mapping['default'];
		
	self::$routes[$method][self::processRoute($route)] = $action;
    }
    
    public static function other($action)
    {
	self::$default_route = $action;
    }
    
    public static function root($action)
    {
	self::$root				= $action;
	self::$routes[GET_REQUEST]['/']		= $action;
    }
    
    public static function start()
    {
	$request	= Request::getInstance();
	$params		= array();
		
	if($request -> path != false)
	{
	    /** Find route matching supplied path */
	    if(isset(self::$routes[$request -> method]))
	    {
		$cache = CacheFactory::getInstance(CACHE_DEFAULT);
				
		/** Load route actions from cache if possible */
		if($result = $cache -> get('routes_' . md5($request -> path)))
		{
		    $action 				= $result[0];
		    $params				= array_slice($result, 1);
		    list($controller, $function)	= explode('#', $action);
		    $controller 			= CONTROLLERS_NAMESPACE . $controller;
		    $controller				= $controller::getInstance($params);
		    	
		    $controller -> $function();
		    exit;
		}
		else
		{
		    foreach(self::$routes[$request -> method] as $route => $action)
		    {   
			if(preg_match('`^' . $route . '$`', $request -> path, $params))
			{
			    $params 				= array_slice($params, 1);
			    list($controller, $function)	= explode('#', $action);
			    $controller				= CONTROLLERS_NAMESPACE . $controller;
			    $controller 			= $controller::getInstance($params);
			    
			    /** Cache the route, action and params for faster processing */
			    array_unshift($params, $action);
			    $cache -> set('routes_' . md5($request -> path), $params, 0);
								
			    $controller -> $function();
			    exit;
			}
		    }
		}
	    }
			
	    /** Trigger default route */
	    list($controller, $function)	= explode('#', self::$default_route);
	    $controller				= CONTROLLERS_NAMESPACE . $controller;
	    $controller 			= $controller::getInstance($params);
	
	    $controller -> $function();
	}
	else
	{
	    /** Trigger root route */
	    list($controller, $function)	= explode('#', self::$root);
	    $controller				= CONTROLLERS_NAMESPACE . $controller;
	    $controller 			= $controller::getInstance($params);
			
	    $controller -> $function();
	}
    }
    
    protected static function processRoute($route, $restrict)
    {
	$original_route	= $route;
	$cache		= CacheFactory::getInstance(CACHE_DEFAULT);
		
	/** Read route information from cache if possible */
	if($cacheRoute = $cache -> get('route_rewrite_' . md5($route))) return $cacheRoute;
		   
	$restrictions = array(	'int' 		=> '(?P<$1>[0-9]+)',
				'float' 	=> '(?P<$1>[0-9\.]+)',
				'alpha' 	=> '(?P<$1>[a-zA-Z]+)',
				'alphanum'	=> '(?P<$1>[a-zA-Z0-9]+)');
			  
	$route = str_replace(')', ')?', str_replace('(', '(?:',$route));
		
	foreach($restrict as $variable => $restriction)
	{
	    $route = preg_replace("`:($variable)`", $restrictions[$restriction], $route);
	}
		
	$route = preg_replace('`:([a-zA-Z][a-zA-Z0-9]*)`', '(?P<$1>[^/]+)', $route);
	$cache -> set('route_rewrite_' . md5($original_route), $route, 0);
		
	return $route;
    }
}
?>
