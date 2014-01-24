<?php

class IndexController extends FrontController {

  protected function IndexAction() {
    $this->data->keywords = Keyword::getKeywords($this->user->user['id']);
    Connexion::getInstance()->query("SELECT gg, delay, url, frequency FROM user WHERE id = '" . $this->user->user['id'] . "' ");
    $this->data->parameters = Connexion::getInstance()->fetch();

    $numbers = array('0' => 0, '1' => 0, '2' => 0, '3' => 0);

    foreach ($this->data->keywords as $keyword):
      if ($keyword['position'] > 0 && $keyword['position'] < 11):
        $numbers[0] ++;
      elseif ($keyword['position'] >= 11 && $keyword['position'] < 31):
        $numbers[] ++;
      elseif ($keyword['position'] >= 31 && $keyword['position'] < 51):
        $numbers[2] ++;
      else:
        $numbers[3] ++;
      endif;
    endforeach;

    $this->data->numbers = $numbers;
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