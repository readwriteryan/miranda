<?php
namespace miranda\controllers;

use miranda\views\View;
use miranda\config\Config;

class ApplicationController
{
    private static $instances	= array();
    protected $params		= array();
    protected $view		= NULL;
    
    protected function __construct($params)
    {
	$this -> params 	= $params;
	$this -> view 		= new View;
    }
    
    public static function getInstance($params)
    {
	$class = get_called_class();
		
	if(!isset(self::$instances[$class]))
	    self::$instances[$class] = new $class($params);  
		
	return self::$instances[$class];
    }
    
    public function redirect($location)
    {
	header('Location: ' . Config::get('site', 'base') . $location, true, 302);
	exit;
    }
    
    public function permanentRedirect($location)
    {
	header('Location: ' . Config::get('site', 'base') . $location, true, 301);
	exit;
    }
}
?>