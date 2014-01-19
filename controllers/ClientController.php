<?php

class ClientController extends FrontController {

  protected function AddAction() {

    if (isset($this->post_data) && sizeof($this->post_data) > 0) {

      $position = (isset($this->post_data['position']) && $this->post_data['position'] == 'on') ? '1' : '0';
      $search = (isset($this->post_data['search']) && $this->post_data['search'] == 'on') ? '1' : '0';

      Connexion::getInstance()->query("INSERT INTO client (name, url, google, date, author, todo_position, todo_search) VALUES ('" . addslashes($this->post_data['client']) . "', '" . addslashes($this->post_data['url']) . "', '" . addslashes($this->post_data['google']) . "', '" . date('Y-m-d H:i:s') . "', '" . $_SESSION['login'] . "', '" . $position . "', '" . $search . "') ");

      Connexion::getInstance()->query("SELECT id_client FROM client WHERE name = '" . addslashes($this->post_data['client']) . "' AND url = '" . addslashes($this->post_data['url']) . "' ");

      $id_client = Connexion::getInstance()->result();

      foreach ($this->post_data['keywords'] as $k => $keys):

        $keywords = explode("\n", $keys);
        foreach ($keywords as $keyword):

          if (trim($keyword) != '')
            Connexion::getInstance()->query("INSERT INTO keyword (id_client, keyword, theme, date, done_adwords) VALUES ('" . $id_client . "', '" . addslashes(trim($keyword)) . "', '" . addslashes(trim($this->post_data['categorie'][$k])) . "', '" . date('Y-m-d H:i:s') . "', '0')");

        endforeach;

      endforeach;
    }

    header('Location: /client/processing');

    exit;
  }

  protected function ProcessingAction() {

    Connexion::getInstance()->query("SELECT name, url, id_client as id, date, author FROM client WHERE done = 0 ORDER BY date DESC");
    $this->data->clients = Connexion::getInstance()->fetchAll();
  }

  protected function FinishedAction() {

    Connexion::getInstance()->query("SELECT name, url, id_client as id, date, author FROM client WHERE done = 1 ORDER BY date DESC");
    $this->data->clients = Connexion::getInstance()->fetchAll();
  }

  protected function ShowAction($client) {

    Connexion::getInstance()->query("SELECT k.*, c.name, c.url FROM keyword k LEFT JOIN client c ON k.id_client = c.id_client WHERE k.id_client = '" . $client . "' ");
    $this->data->keywords = Connexion::getInstance()->fetchAll();

    foreach ($this->data->keywords as $k => $kw):

      $large = 0;
      if ($kw['request_large'] <= 10000):
        $large = 0;
      elseif ($kw['request_large'] > 10000 && $kw['request_large'] <= 50000):
        $large = 1;
      elseif ($kw['request_large'] > 50000 && $kw['request_large'] <= 100000):
        $large = 2;
      elseif ($kw['request_large'] > 100000 && $kw['request_large'] <= 500000):
        $large = 3;
      elseif ($kw['request_large'] > 500000 && $kw['request_large'] <= 1000000):
        $large = 4;
      elseif ($kw['request_large'] > 1000000 && $kw['request_large'] <= 2500000):
        $large = 5;
      elseif ($kw['request_large'] > 2500000 && $kw['request_large'] <= 5000000):
        $large = 6;
      elseif ($kw['request_large'] > 5000000 && $kw['request_large'] <= 10000000):
        $large = 7;
      elseif ($kw['request_large'] > 10000000 && $kw['request_large'] <= 50000000):
        $large = 8;
      elseif ($kw['request_large'] > 50000000 && $kw['request_large'] <= 100000000):
        $large = 9;
      elseif ($kw['request_large'] > 100000000):
        $large = 10;
      endif;

      //exact
      $exact = 0;
      if ($kw['request_exact'] <= 100):
        $exact = 0;
      elseif ($kw['request_exact'] > 100 && $kw['request_exact'] <= 500):
        $exact = 1;
      elseif ($kw['request_exact'] > 500 && $kw['request_exact'] <= 1000):
        $exact = 2;
      elseif ($kw['request_exact'] > 1000 && $kw['request_exact'] <= 5000):
        $exact = 3;
      elseif ($kw['request_exact'] > 5000 && $kw['request_exact'] <= 10000):
        $exact = 4;
      elseif ($kw['request_exact'] > 10000 && $kw['request_exact'] <= 50000):
        $exact = 5;
      elseif ($kw['request_exact'] > 50000 && $kw['request_exact'] <= 100000):
        $exact = 6;
      elseif ($kw['request_exact'] > 100000 && $kw['request_exact'] <= 500000):
        $exact = 7;
      elseif ($kw['request_exact'] > 500000 && $kw['request_exact'] <= 1000000):
        $exact = 8;
      elseif ($kw['request_exact'] > 1000000 && $kw['request_exact'] <= 25000000):
        $exact = 9;
      elseif ($kw['request_exact'] > 25000000):
        $exact = 10;
      endif;

      //intitle
      $title = 0;
      if ($kw['request_title'] <= 50):
        $title = 0;
      elseif ($kw['request_title'] > 50 && $kw['request_title'] <= 100):
        $title = 1;
      elseif ($kw['request_title'] > 100 && $kw['request_title'] <= 250):
        $title = 2;
      elseif ($kw['request_title'] > 250 && $kw['request_title'] <= 500):
        $title = 3;
      elseif ($kw['request_title'] > 500 && $kw['request_title'] <= 1000):
        $title = 4;
      elseif ($kw['request_title'] > 1000 && $kw['request_title'] <= 2500):
        $title = 5;
      elseif ($kw['request_title'] > 2500 && $kw['request_title'] <= 5000):
        $title = 6;
      elseif ($kw['request_title'] > 5000 && $kw['request_title'] <= 10000):
        $title = 7;
      elseif ($kw['request_title'] > 10000 && $kw['request_title'] <= 100000):
        $title = 8;
      elseif ($kw['request_title'] > 100000 && $kw['request_title'] <= 1000000):
        $title = 9;
      elseif ($kw['request_title'] > 1000000):
        $title = 10;
      endif;

      $concu = round(($kw['competition'] * 10), 0);

      $this->data->keywords[$k]['mark'] = round((($large * 4) + ($exact * 3) + ($title * 2) + $concu) / 10, 2);

    endforeach;
  }

  protected function AjaxRelaunchAction() {
    Connexion::getInstance()->query("UPDATE keyword SET done_calc = '0', done_adwords = '0', error = '0', process_calc = '0' WHERE id_keyword = '" . $this->post_data['id'] . "' ");
    exit;
  }

}

?>