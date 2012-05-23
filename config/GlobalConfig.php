<?php
/** Global Site-Wide Defaults */
define('WEB_ROOT', '/srv/http/ryanthegreat/application/');
define('MIRANDA_ROOT', '/srv/http/ryanthegreat/miranda/');
define('SITE_BASE', 'http://localhost/ryanthegreat/application/public/');
define('SITE_CHARSET', 'UTF-8');
define('BASE_NAMESPACE', 'ryanthegreat\\');
define('VIEWS_BASE', WEB_ROOT . 'views/');
define('VIEWS_NAMESPACE', BASE_NAMESPACE . 'views\\');
define('CONTROLLERS_BASE', WEB_ROOT . 'controllers/');
define('CONTROLLERS_NAMESPACE', BASE_NAMESPACE . 'controllers\\');
define('MODELS_BASE', WEB_ROOT . 'models/');
define('MODELS_NAMESPACE', BASE_NAMESPACE . 'models\\');
define('CACHE_BASE', MIRANDA_ROOT . 'cache/');
define('CACHE_NAMESPACE',  'miranda\\cache\\');
?>