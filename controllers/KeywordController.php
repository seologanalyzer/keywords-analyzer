<?php

class KeywordController extends FrontController {

  protected function DeleteAction() {
    if (Keyword::exist($this->user->user['id'], $this->post_data['id'])) {
      Keyword::delete($this->user->user['id'], $this->post_data['id']);
    }
    exit;
  }

  protected function ShowAction($id_keyword) {
    $keyword = Keyword::getKeyword($id_keyword, $this->user->user['id']);

    if (isset($keyword['keyword'])):
      $this->data->url = $this->user->user['url'];
      $this->data->keyword = $keyword;
      $this->data->indice = Keyword::getIndice($keyword);
      $this->data->positions = Keyword::getPosition($keyword, $this->user->user['id'], $this->user->user['url']);
      $this->data->concurrents = Keyword::getConcurrents($keyword);
    else:
      header('Location:/');
    endif;
  }

}

?>