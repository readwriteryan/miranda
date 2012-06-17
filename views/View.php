<?php
namespace miranda\views;

class View
{

    protected $visible = array();
    
    private static function escape($value)
    {
	if(is_string($value))
	    return htmlentities($value, ENT_QUOTES, SITE_CHARSET);
	else
	    return $value;
    }
    
    public function setVisible($set, $value = '')
    {
	if(is_array($set))
	{
	    foreach($set as $key => $value)
	    {
		if(is_array($value))
		{
		    $this -> visible[$key] = array_map('self::escape', $value);
		}
		else
		{
		    $this -> visible[$key] = self::escape($value);
		}
	    }
	}
    }
    
    public function clearVisible()
    {
	$this -> visible = array();
    }
    
    public function render($view = '')
    {
	extract($this -> visible);
		
	if(defined('GLOBAL_TEMPLATE')) require_once(GLOBAL_TEMPLATE);
		
	require_once(VIEWS_BASE . $view . '.html.php');
		
	if(defined('GLOBAL_FOOTER')) require_once(GLOBAL_FOOTER); 
    }
}