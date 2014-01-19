<?php

/* Keywords Analyzer */
/* @author sla */
/* @git https://github.com/seologanalyzer/keywords-analyzer */

require_once '../lib/settings.php';

if (isset($_POST['login']) && isset($_POST['password'])):

  Security::isLogged();
  header('Location: ' . APP_PATH);

endif;

$xpl = explode('/', $_SERVER['REQUEST_URI']);
if (isset($xpl[2]) && $xpl[2] == 'api'):
  $xpl = explode('/', $_SERVER['REQUEST_URI']);
  new Api($xpl[2], $_POST);
  exit;
endif;

if (!isset($_SESSION['login'])):
  if ($_SERVER['REQUEST_URI'] != '/')
    header('Location: ' . APP_PATH);
    add('login');
else:

  $frontController = new FrontController();
  $controller = $frontController->getChildController();
  include_once $controller->getView();
  
endif;
