<?php
namespace miranda\controllers;

use miranda\views\View;
use miranda\config\Config;
use miranda\plugins\SecureHash;

class Controller
{
    private static $instances	= array();
    protected $params		= array();
    protected $view		= NULL;
    protected $before		= [];
    protected $after		= [];
    
    public static function getInstance()
    {
	$class = get_called_class();
		
	if(!isset(self::$instances[$class]))
	    self::$instances[$class] = new $class;  
		
	return self::$instances[$class];
    }
    
    public function controller_setup($params)
    {
	$this -> params = $params;
	$this -> view	= new View;
    }
    
    public function action($filters, $actions = NULL, $when = 'before')
    {
	if(!$actions)
	    $this -> {$when . '_on'}($filters, '*');
	else if(!isset($actions['except']))
	{
	    if(isset($actions['on'])) $actions = $actions['on'];
	    
	    $this -> {$when . '_on'}($filters, $actions);
	}
	else
	{
	    $this -> {$when . '_except'}($filters, $actions['except']);
	}
    }
    
    public function action_on($filters, $actions, $when)
    {
	if(!$actions) $actions = ['*']; else if(!is_array($actions)) $actions = [$actions];
	if(!is_array($filters)) $filters = [$filters];
	
	foreach($filters as $filter)
	    foreach($actions as $action)
		$this -> {$when}[$action][] = $filter;
    }
    
    public function action_except($filters, $actions, $when)
    {
	if(!is_array($actions)) $actions = [$actions];
	if(!is_array($filters)) $filters = [$filters];
	
	foreach($filters as $filter)
	    foreach($actions as $action)
		$this -> {$when}['miranda_execute_except'][$filter][] = $action;
	
    }
    
    public function execute_action($action, $trigger)
    {
	if(isset($this -> {$trigger}['*']))
	    foreach($this -> {$trigger}['*'] as $filter) { $this -> $filter(); }
	    
	if(isset($this -> {$trigger}[$action]))
	    foreach($this -> {$trigger}[$action] as $filter) { $this -> $filter(); }
	    
	if(isset($this -> {$trigger}['miranda_execute_except']))
	    foreach($this -> {$trigger}['miranda_execute_except'] as $filter => $actions) { if(!in_array($action, $actions)) $this -> $filter(); }
    }
    
    public function before($filters, $actions = NULL)
    {
	$this -> action($filters, $actions, 'before');
    }
    
    
    public function before_on($filters, $actions = NULL)
    {
	$this -> action_on($filters, $actions, 'before');
    }
    
    public function before_except($filters, $actions)
    {
	$this -> action_except($filters, $actions, 'before');
    }
    
    public function execute_before($action)
    {
	$this -> execute_action($action, 'before');
    }
    
    public function after($filters, $actions = NULL)
    {
	$this -> action($filters, $actions, 'after');
    }
    
    public function after_on($filters, $actions = NULL)
    {
	$this -> action_on($filters, $actions, 'after');
    }
    
    public function after_except($filters, $actions)
    {
	$this -> action_on($filters, $actions, 'after');
    }
    
    public function execute_after($action)
    {
	$this -> execute_action($action, 'after');
    }
    
    public function redirect($location)
    {
	header('Location: ' . Config::get('site', 'base') . $location, true, 302);
	exit;
    }
    
    public function permanent_redirect($location)
    {
	header('Location: ' . Config::get('site', 'base') . $location, true, 301);
	exit;
    }
}
?>