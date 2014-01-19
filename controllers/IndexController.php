<?php

class IndexController extends FrontController {

  protected function IndexAction() {
    $this->data->keywords = Keyword::getKeywords($this->user->user['id']);
  }
  
  protected function AddkeywordAction(){
    
    Keyword::addKeyword($this->user->user['id'], $this->post_data['keyword']);
    header('Location:/');
    exit;
  }

}

?>