<?php
/** Global Site-Wide Defaults */

/** Basic folder locations */
define('WEB_ROOT', '/srv/http/ryanthegreat/application/');
define('MIRANDA_ROOT', '/srv/http/ryanthegreat/miranda/');
define('SITE_BASE', 'http://localhost/ryanthegreat/application/public/');
define('SITE_CHARSET', 'UTF-8');

/** Namespace and directory base definitions */
define('BASE_NAMESPACE', 'ryanthegreat\\');
define('VIEWS_BASE', WEB_ROOT . 'views/');
define('VIEWS_NAMESPACE', BASE_NAMESPACE . 'views\\');
define('CONTROLLERS_BASE', WEB_ROOT . 'controllers/');
define('CONTROLLERS_NAMESPACE', BASE_NAMESPACE . 'controllers\\');
define('MODELS_BASE', WEB_ROOT . 'models/');
define('MODELS_NAMESPACE', BASE_NAMESPACE . 'models\\');

/** Views definitions */
define('GLOBAL_TEMPLATE', WEB_ROOT . 'views/global/template.html.php');
define('GLOBAL_FOOTER', WEB_ROOT . 'views/global/footer.html.php');

/** Caching Definitions */
define('CACHE_BASE', MIRANDA_ROOT . 'cache/');
define('CACHE_NAMESPACE',  'miranda\\cache\\');
define('CACHE_DEFAULT', 'memcache');

/** Memcached Definitions */
define('MEMCACHED_SERVER', 'localhost');
define('MEMCACHED_PORT', '11211');

/** Redis Definitions */
define('REDIS_SERVER', '127.0.0.1');
define('REDIS_PORT', '6379');

/** Request Definitions */
define('GET_REQUEST', 1);
define('POST_REQUEST', 2);
define('PUT_REQUEST', 3);
define('DELETE_REQUEST', 4);

/** Database Definitions */
define('DEFAULT_DSN', 'mysql:dbname=avergrid;host=localhost');	/** Defines the default DSN associated with the engine */
define('DEFAULT_USERNAME', 'testuser');							/** Defines the default username associated with the engine */
define('DEFAULT_PASSWORD', 'testpassword');						/** Defines the default password associated with the engine */
define('LOG_ALL_QUERIES', 1);									/** 1 to log all queries executed, 0 to turn logging off */
define('LOG_QUERY_ERRORS', 1); 									/** 1 to log database query errors, 0 to turn logging off */
define('LOG_SLOW_QUERIES', 1); 									/** 1 to log slow database queries, 0 to turn logging off */
define('SLOW_QUERY_TIME', 2.0);									/** Sets the minimum length in seconds for a query to be considered slow */

/** Error Types */
define('GENERAL_EXCEPTION', 1);
define('SECURITY_EXCEPTION', 2);
define('SYSTEM_EXCEPTION', 3);
define('QUERY_ERROR', 4);
define('SLOW_QUERY', 5);

/** Logging Definitions */
define('LOG_GENERAL_EXCEPTIONS', 1); 						/** 1 to log general exceptions, 0 to turn logging off */
define('LOG_SECURITY_EXCEPTIONS', 1); 						/** 1 to log security exceptions, 0 to turn logging off */
define('LOG_SYSTEM_EXCEPTIONS', 1); 						/** 1 to log system exceptions, 0 to turn logging off */
define('LOG_TO_DATABASE', 1);								/** 1 to turn logging to a database table on, 0 to disable */
define('LOG_TABLE_NAME', 'system_logs'); 					/** If database logging is enabled, system logs will be saved to this table */
define('LOG_TO_FILE', 1); 									/** 1 to turn logging to the filesystem on, 0 to disable */
define('LOG_FILE', 'system.log'); 							/** If filesystem logging is enabled, system logs will be saved to this file name */
define('LOG_FILE_LOCATION', '/srv/http/backend/logs/'); 	/** If filesystem logging is enabled, system logs will be saved in this location */
?>