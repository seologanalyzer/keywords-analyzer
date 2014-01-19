<?php

function defaultAutoload($class_name) {

  if ((file_exists(ROOT_PATH.'controllers/' . $class_name . '.php'))):
    require_once ROOT_PATH.'controllers/' . $class_name . '.php';
  elseif ((file_exists(ROOT_PATH.'classes/class.' . strtolower($class_name) . '.php'))):
    require_once ROOT_PATH.'classes/class.' . strtolower($class_name) . '.php';
  else:
    throw new Exception($class_name . ' does not exist.', 404);
  endif;
}

function add($fileName) {

  global $controller, $utilisateur;

  try {

    if (!is_file(ROOT_PATH . 'views/extends/' . $fileName . '.php'))
      return 'File views/extends/' . $fileName . '.php does not exist in ' . realpath($controller->getView());
    else
      include_once ROOT_PATH . 'views/extends/' . $fileName . '.php';
  } catch (Exception $e) {

    include_once ROOT_PATH . 'views/extends/error.php';
  }
}

function show($value){
	echo '<pre>';
	print_r($value);
	exit;
}