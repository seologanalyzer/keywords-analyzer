<?php

class IndexController extends FrontController {

  protected function IndexAction() {
    $this->data->keywords = Keyword::getKeywords($this->user->user['id']);
    Connexion::getInstance()->query("SELECT gg, delay, url, frequency FROM user WHERE id = '" . $this->user->user['id'] . "' ");
    $this->data->parameters = Connexion::getInstance()->fetch();
  }

  protected function AddkeywordAction() {
    $xpl = explode(',', $this->post_data['keyword']);
    foreach ($xpl as $keyword):
      Keyword::addKeyword($this->user->user['id'], trim($keyword));
    endforeach;
    header('Location:/');
    exit;
  }

}

?>