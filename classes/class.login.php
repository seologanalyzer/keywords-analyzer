<?php

class Login {

  private $login;
  private $pass;

  public function __construct($login, $pass) {
    $this->login = $login;
    $this->pass = $pass;
  }

  public function identification() {

    Connexion::getInstance()->query("SELECT name FROM user
                    WHERE mail = '" . $this->login . "'
                    AND password = '" . md5($this->pass) . "' ");

    $nom = Connexion::getInstance()->result();

    if ($nom):
        return true;

    endif;

    return false;
  }

  public function getIdentifiant() {
    return $this->login;
  }

  public function getMotDePasse() {
    return $this->motDePasse;
  }

}

?>
