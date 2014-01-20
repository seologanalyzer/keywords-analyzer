<?php

class Api {

  public $post;
  public $id_user;

  public function __construct($call, $_POST) {
    if (isset($_POST['token']) && $this->checkToken($_POST['token'])) {
      $this->id_user = $this->checkToken($_POST['token']);
      $this->post = $_POST;
      $this->$call();
      exit;
    } else {
      echo 'ERROR';
    }
  }

  public function checkToken($token) {
    Connexion::getInstance()->query("SELECT id FROM user WHERE token = '" . $token . "' ");
    $result = Connexion::getInstance()->result();
    if ($result != ''):
      return $result;
    else:
      return false;
    endif;
  }

  public function set() {
    if (isset($this->post['id_keyword']) && isset($this->post['type']) && isset($this->post['value'])):
      $q = ($this->post['type'] == 'request_title') ? " , job_done = '1' " : "";
      $this->post['value'] = ($this->post['value'] > 100 && $this->post['type'] == 'position') ? '100' : $this->post['value'];
      Connexion::getInstance()->query("UPDATE keyword SET " . $this->post['type'] . " = '" . $this->post['value'] . "' "
              . $q . " WHERE id_keyword = '" . $this->post['id_keyword'] . "' ");
    endif;
  }

  public function get() {
    Connexion::getInstance()->query("SELECT k.keyword, k.id_keyword, u.gg, u.delay FROM keyword k "
            . " LEFT JOIN user u ON u.id = k.id_user WHERE k.id_user = '" . $this->id_user . "' AND k.job_done = 0 LIMIT 0,1 ");
    $keyword = Connexion::getInstance()->fetch();
    echo json_encode($keyword);
    exit;
  }
  
  public function getposition() {
    //
    $date = new DateTime();
    echo json_encode($keyword);
    exit;
  }

  public function update() {
    $handle = file_get_contents(ROOT_PATH . 'lib/launcher/update.php');
    echo $handle;
    exit;
  }

}
