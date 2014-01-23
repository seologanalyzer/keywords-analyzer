<?php

class KeywordController extends FrontController {

  protected function DeleteAction() {
    if(Keyword::exist($this->user->user['id'], $this->post_data['id'])){
      Keyword::delete($this->user->user['id'], $this->post_data['id']);
    }
    exit;
  }
  
  protected function ShowAction($id_keyword){
    
  }
  
}

?>