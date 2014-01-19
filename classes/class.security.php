<?php

class Security {

  public static function isLogged() {
    if (!isset($_SESSION['login'])):
      Security::login();
    endif;
  }

  private static function login() {
        
    $client = new Login(addslashes($_POST['login']), addslashes($_POST['password']));

    if ($client->identification()) {
      //for agency
      $_SESSION['account'] = $_POST['login'];
      $_SESSION['login'] = $client->getIdentifiant();

      return true;
    }
    return false;
  }

  public static function logout() {
    session_unset();
    session_destroy();

    header('location:' . BACK_PATH);
    exit;
  }

  public static function isAdmin() {
    //for crons
    if (php_sapi_name() == 'cli')
      return true;

    $ips_ok = array('127.0.0.1', '83.114.94.99');

    if (!isset($_SERVER['REMOTE_ADDR']) || !in_array($_SERVER['REMOTE_ADDR'], $ips_ok))
      return false;

    return true;
  }

}
