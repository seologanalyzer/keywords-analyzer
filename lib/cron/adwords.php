<?php

$out = array();
$x = exec('ps -ef | grep -v grep | grep ' . $_SERVER['PHP_SELF'] . ' | awk \'{print $2}\' ', $out);
if (sizeof($out) > 2):
  echo 'Already launched';
  exit;
endif;

require_once dirname(__FILE__) . '/bootstrap.php';

Connexion::getInstance()->query("SELECT keyword, id_keyword
																FROM keyword
																WHERE search = 0 ");

$keywords = Connexion::getInstance()->fetchAll();

foreach ($keywords as $keyword)
  new Adwords($keyword);
