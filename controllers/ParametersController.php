<?php

class ParametersController extends FrontController {

  protected function IndexAction() {
    Connexion::getInstance()->query("SELECT gg, delay, url, frequency, name FROM user WHERE id = '" . $this->user->user['id'] . "' ");
    $this->data->parameters = Connexion::getInstance()->fetch();
  }

  protected function RecordAction() {
    
    Connexion::getInstance()->query("UPDATE user SET gg = '".addslashes($this->post_data['gg'])."', delay = '".addslashes($this->post_data['delay'])."', url = '".addslashes($this->post_data['url'])."', frequency = '".addslashes($this->post_data['frequency'])."' WHERE id = '" . $this->user->user['id'] . "' ");
   
    header('location:/parameters');
    exit;
    
  }

}

?>