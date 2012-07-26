<?php
namespace miranda\views;

use miranda\config\Config;

class View
{

    protected $visible	= [];
    public $css		= [];
    public $js		= [];
    
    private static function escape($value)
    {
	if(is_string($value))
	    return htmlentities($value, ENT_QUOTES, Config::get('site', 'charset'));
	else if(is_array($value))
	    return array_map('self::escape', $value);
	else
	    return $value;
    }
    
    public function css($stylesheet)
    {
	$this -> css[] = format(Config::get('locations', 'css') . self::escape($stylesheet) . '.css');
	
	return $this;
    }
    
    public function js($source)
    {
	$this -> js[] = format(Config::get('locations', 'js') . self::escape($source) . '.js');
	
	return $this;
    }
    
    public function visible($set, $value = '')
    {
	if(is_array($set))
	{
	    foreach($set as $key => $value)
	    {
		$this -> visible[$key] = self::escape($value);
	    }
	}
	else
	{
	    $this -> visible[$set] = self::escape($value);
	}
	
	return $this;
    }
    
    public function raw($set, $value = '')
    {
	if(is_array($set))
	{
	    foreach($set as $key => $value)
	    {
		$this -> visible[$key] = $value;
	    }
	}
	else
	{
	    $this -> visible[$set] = $value;
	}
	
	return $this;
    }
    
    public function clearVisible()
    {
	$this -> visible = array();
	
	return $this;
    }
    
    public function render($view)
    {
	extract($this -> visible);
		
	if($template = Config::get('views', 'template')) require_once(Config::get('site', 'webroot') . Config::get('locations', 'global') . $template);
		
	require_once(Config::get('site', 'webroot') . Config::get('locations', 'views') . $view . '.html.php');
		
	if($footer = Config::get('views', 'footer')) require_once(Config::get('site', 'webroot') . Config::get('locations', 'global') . $footer); 
    }
    
    public function render_partial($partial, $visible = NULL)
    {
	if(is_array($visible)) extract($visible);
	require(Config::get('site', 'webroot') . Config::get('locations', 'partials') . $partial .'.html.php');
    }
}