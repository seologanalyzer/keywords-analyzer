<?php

class Connexion {

  private $connexion;
  private $result;
  public $query;
  private static $_instance = null;

  public function __construct($utf8 = true) {
    $this->connexion = mysql_connect(DB_HOST, DB_USER, DB_PASS);
    mysql_select_db(DB_BASE, $this->connexion);
    if ($utf8)
      $this->query("SET NAMES 'UTF8'");
  }

  public static function getInstance() {

    if (is_null(self::$_instance)) {
      self::$_instance = new Connexion();
    }
    return self::$_instance;
  }

  public function query($query) {

    $this->query = $query;

    $start = microtime(true);
    $this->result = mysql_query($query, $this->connexion);
    $end = microtime(true);

    if ($this->err())
      echo '<br/>' . $query . '<br/>' . mysql_error($this->connexion);

    return $this->result;
  }

  public function err() {

    if (mysql_errno($this->connexion))
      return true;

    return false;
  }

  public function echoQuery() {
    return $this->query;
  }

  public function fetch() {
    return mysql_fetch_assoc($this->result);
  }

  public function fetchAll() {

    $array = array();
    while ($r = mysql_fetch_assoc($this->result))
      $array[] = $r;
    return $array;
  }

  public function fetchAll2() {
    $array = array();
    while ($r = mysql_fetch_assoc($this->result))
      $array = $r;
    return $array;
  }

  public function fetchAll3() {
    $array = array();
    while ($r = mysql_fetch_row($this->result))
      $array[] = $r[0];
    return $array;
  }

  public function fetchAllObject() {

    $array = array();
    while ($r = mysql_fetch_object($this->result))
      $array[] = $r;
    return $array;
  }

  public function fetchObject() {

    return mysql_fetch_object($this->result);
  }

  public function rows() {

    return @mysql_num_rows($this->result);
  }

  public function result() {
    if ($this->rows() >= 1)
      return mysql_result($this->result, 0);
    return false;
  }

  public function close() {
    mysql_close($this->connexion);
  }

  public function lastID() {
    return (int) mysql_insert_id($this->connexion);
  }

  public function fetchKeyValue() {

    $array = array();

    while ($r = mysql_fetch_row($this->result))
      $array[$r[0]] = mysql_real_escape_string($r[1]);
    return $array;
  }

}

?>
