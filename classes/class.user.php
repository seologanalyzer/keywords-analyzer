<?php

class User {

  public $user;

  public function __construct($mail) {

    Connexion::getInstance()->query("SELECT * FROM user WHERE mail = '" . $mail . "' ");
    $this->user = Connexion::getInstance()->fetch();
    
    return $this->user;
  }
  
  public function getID(){
    return $this->user->id;
  }

}
