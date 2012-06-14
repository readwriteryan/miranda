<?php
namespace Miranda\exceptions;
use Exception;

class GeneralException extends Exception
{
    public static function handleException(Exception $exception)
    {
	echo "Caught this exception sneaking around:<br />";
	echo $exception -> getMessage();
	die;
    }
}
?>