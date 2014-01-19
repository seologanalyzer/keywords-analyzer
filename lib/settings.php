<?php

session_start();
set_time_limit(300);

/**
 * Defines
 */
define('HOST', $_SERVER['HTTP_HOST']);

define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_BASE', '');

define('ROOT_PATH', dirname(__FILE__) . '/../');
define('URL_VIEWS', ROOT_PATH . 'views/');
define('BACK_PATH', 'http://' . HOST);
define('APP_PATH', 'http://keywords.dulol.fr');
define('SITE', 'Keywords Analyzer');


/**
 * Functions
 */
require_once ROOT_PATH . 'lib/functions.php';
spl_autoload_register('defaultAutoload');

ini_set('display_errors', 1);
error_reporting(E_ALL);
