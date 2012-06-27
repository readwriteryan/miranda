<?php
namespace miranda\database;

use PDO;
use PDOStatement;
use PDOException;
use miranda\logging\SystemLogger;

Class PDOStmt extends PDOStatement
{   
    public function execute($args = NULL)
    {
	$start 	= microtime(true);
	$result = parent::execute($args);
	$end 	= microtime(true);	
		
	if(!$result)
	{
	    $error_information = $this -> errorInfo();
	    SystemLogger::log_event(QUERY_ERROR, 'Query execution failed with message: ' . $error_information[2]);
	}
		
	$total_time = $end - $start;
	if($total_time > SLOW_QUERY_TIME)
	{
	    SystemLogger::log_event(SLOW_QUERY, 'Prepared statement containing: ' . $this -> queryString . ' took ' . $total_time . ' seconds to execute.');
	}
    }
}

Class PDOEngine extends PDO
{
    private static $db_instance;
    
    public function __construct($strDSN = DEFAULT_DSN, $strUsername = DEFAULT_USERNAME, $strPassword = DEFAULT_PASSWORD)
    {
	/** Declared private to prevent instantiation via constructor. Use Database::getInstance() for access to the singleton database object or Database::getNewInstance for a fresh connection */
	try
	{
	    parent::__construct($strDSN, $strUsername, $strPassword);
	}
	catch(PDOException $exception)
	{
	    echo $exception -> getMessage(); die;
	}
    }
    
    public static function getInstance()
    {
	if(!self::$db_instance)
	{	    
	    self::$db_instance = new PDOEngine();
	    self::$db_instance -> setAttribute(PDO::ATTR_STATEMENT_CLASS, array('\miranda\database\PDOStmt'));
	    self::$db_instance -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	return self::$db_instance;
    }
    
    public static function getNewInstance($strDSN = DEFAULT_DSN, $strUsername = DEFAULT_USERNAME, $strPassword = DEFAULT_PASSWORD)
    {
	return new PDOEngine($strDSN, $strUsername, $strPassword);
    }
    
    public function prepare($statement, $options = NULL)
    {
	if(!$options) $options = [];
	$start		= microtime(true);
	$result		= parent::prepare($statement, $options);
	$end		= microtime(true);
		
	if(LOG_ALL_QUERIES)
	{
	    SystemLogger::log_to_file('query.log', $statement);
	}
		
	return $result;
    }
    
    public function query()
    {	
	
	$arguments	= func_get_args();
	$start		= microtime(true);
	$result		= call_user_func_array(array($this, 'parent::query'), $arguments);
	$end		= microtime(true);
		
	if(LOG_ALL_QUERIES)
	{
	    SystemLogger::log_to_file('query.log', $arguments[0]);
	}
		
	if(!$result)
	{
	    $error_information = $this -> errorInfo();
	    SystemLogger::log_event(QUERY_ERROR, 'Query execution failed with message: ' . $error_information[2]);
	}
		
	$total_time = $end - $start;
	if($total_time > SLOW_QUERY_TIME)
	{
	    SystemLogger::log_event(SLOW_QUERY, 'Query containing: ' . $arguments[0] . ' took ' . $total_time . ' seconds to execute.');
	}
    }
}
?>