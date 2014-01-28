<?php

class Api {

  public $post;
  public $user;

  public function __construct($call, $_POST) {
    if (isset($_POST['token']) && $this->checkToken($_POST['token'])) {
      $this->user = $this->checkToken($_POST['token']);
      $this->post = $_POST;
      $this->$call();
      exit;
    } else {
      echo 'ERROR';
    }
  }

  public function checkToken($token) {
    Connexion::getInstance()->query("SELECT * FROM user WHERE token = '" . $token . "' ");
    $result = Connexion::getInstance()->fetch();
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
            . " LEFT JOIN user u ON u.id = k.id_user WHERE k.id_user = '" . $this->user['id'] . "' AND k.job_done = 0 LIMIT 0,1 ");
    $keyword = Connexion::getInstance()->fetch();
    echo json_encode($keyword);
    exit;
  }

  public function setposition() {
    if (isset($this->post['id_keyword']) && isset($this->post['values'])):
      $found = false;
      $positions = unserialize(urldecode(base64_decode($this->post['values'])));
      foreach ($positions as $k => $position):
        $xpl = explode('&amp;', $position['url']);
        //if it's user's url

        if (strpos($xpl[0], $this->user['url']) !== false && $found == false):
          $found = true;
          Connexion::getInstance()->query("UPDATE keyword SET position = '" . ($k + 1) . "' WHERE id_keyword = '" . $this->post['id_keyword'] . "' ");
        endif;
        Connexion::getInstance()->query("SELECT id_url FROM url WHERE url = '" . addslashes($xpl[0]) . "' ");
        $id_url = Connexion::getInstance()->result();
        if (!$id_url):
          Connexion::getInstance()->query("INSERT INTO url (url, title) VALUES ('" . addslashes($xpl[0]) . "', '" . addslashes($position['title']) . "') ");
          Connexion::getInstance()->query("SELECT id_url FROM url WHERE url = '" . addslashes($xpl[0]) . "' ");
          $id_url = Connexion::getInstance()->result();
        else:
          Connexion::getInstance()->query("UPDATE url SET title = '" . addslashes($position['title']) . "' WHERE id_url = '" . $id_url . "' ");
        endif;
        Connexion::getInstance()->query("INSERT IGNORE INTO position (id_url, position, date, id_keyword) VALUES ('" . $id_url . "', '" . ($k + 1) . "', '" . date('Y-m-d') . "', '" . $this->post['id_keyword'] . "') ");
      endforeach;
    endif;
  }

  public function getposition() {
    Connexion::getInstance()->query("SELECT id_keyword, keyword FROM keyword WHERE id_keyword NOT IN (SELECT id_keyword FROM position) AND id_user = '" . $this->user['id'] . "' LIMIT 0,1 ");
    $keyword = Connexion::getInstance()->fetch();
    if (isset($keyword['id_keyword'])):
      $keyword['url'] = $this->user['url'];
      $keyword['gg'] = $this->user['gg'];
      $keyword['delay'] = $this->user['delay'];
      echo json_encode($keyword);
    else:
      $date = new DateTime();
      $date->modify('- ' . ($this->user['frequency'] - 1) . ' days');
      Connexion::getInstance()->query("SELECT id_keyword, keyword FROM keyword WHERE id_keyword NOT IN (SELECT id_keyword FROM position WHERE date BETWEEN  '" . $date->format('Y-m-d') . "' AND '" . date('Y-m-d') . "') AND id_user = '" . $this->user['id'] . "' LIMIT 0,1 ");
      $keyword = Connexion::getInstance()->fetch();
      if (isset($keyword['id_keyword'])):
        $keyword['url'] = $this->user['url'];
        $keyword['gg'] = $this->user['gg'];
        $keyword['delay'] = $this->user['delay'];
        echo json_encode($keyword);
      endif;
    endif;
    exit;
  }

  public function update() {
    $handle = file_get_contents(ROOT_PATH . 'lib/launcher/update.php');
    echo $handle;
    exit;
  }

}
