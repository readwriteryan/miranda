<?php
namespace miranda\controllers;

use miranda\views\View;

class ApplicationController
{
    private static $instances	= array();
    protected $params		= array();
    protected $view		= NULL;
    
    protected function __construct($params)
    {
	$this -> params 	= $params;
	$this -> view 		= new View;
		
	$this -> view -> setVisible('params', $this -> params);
    }
    
    public static function getInstance($params)
    {
	$class = get_called_class();
		
	if(!isset(self::$instances[$class]))
	    self::$instances[$class] = new $class($params);  
		
	return self::$instances[$class];
    }    
}
?>