<?php

set_time_limit(0);
ini_set('memory_limit', -1);
ini_set('display_errors',1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

define('ROOT_PATH', dirname(__FILE__).'/../../');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_BASE', '');
define('PHP_ENV', php_sapi_name());
//Running cron by CLI ? Server IP
define('DB_HOST','localhost');

require_once ROOT_PATH.'lib/functions.php';
spl_autoload_register('defaultAutoload');

//Adwords API
require_once ROOT_PATH.'lib/adwords/init.php';