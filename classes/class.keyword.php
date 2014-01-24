<?php

class Keyword {

  public static function addKeyword($id_user, $keyword) {

    Connexion::getInstance()->query("INSERT INTO keyword (id_user, keyword)"
            . " VALUES ('" . $id_user . "', '" . addslashes(strtolower($keyword)) . "') ");
  }

  public static function getKeywords($id_user) {
    Connexion::getInstance()->query("SELECT * FROM keyword WHERE id_user = '" . $id_user . "' ");

    return Connexion::getInstance()->fetchAll();
  }

  public static function exist($id_user, $id_keyword) {
    Connexion::getInstance()->query("SELECT id_keyword FROM keyword WHERE id_user = '" . $id_user . "' AND id_keyword = '" . $id_keyword . "' ");
    $result = Connexion::getInstance()->fetch();
    if ($result > 0) {
      return true;
    } else {
      return false;
    }
  }

  public static function delete($id_user, $id_keyword) {
    Connexion::getInstance()->query("DELETE FROM keyword WHERE id_user = '" . $id_user . "' AND id_keyword = '" . $id_keyword . "' ");
  }

  public static function getKeyword($id_keyword, $id_user) {
    Connexion::getInstance()->query("SELECT * FROM keyword WHERE id_user = '" . $id_user . "' AND id_keyword = '" . $id_keyword . "' ");
    return Connexion::getInstance()->fetch();
  }

  public static function getIndice($keyword) {
    $large = 0;
    if ($keyword['request_large'] <= 10000):
      $large = 0;
    elseif ($keyword['request_large'] > 10000 && $keyword['request_large'] <= 50000):
      $large = 1;
    elseif ($keyword['request_large'] > 50000 && $keyword['request_large'] <= 100000):
      $large = 2;
    elseif ($keyword['request_large'] > 100000 && $keyword['request_large'] <= 500000):
      $large = 3;
    elseif ($keyword['request_large'] > 500000 && $keyword['request_large'] <= 1000000):
      $large = 4;
    elseif ($keyword['request_large'] > 1000000 && $keyword['request_large'] <= 2500000):
      $large = 5;
    elseif ($keyword['request_large'] > 2500000 && $keyword['request_large'] <= 5000000):
      $large = 6;
    elseif ($keyword['request_large'] > 5000000 && $keyword['request_large'] <= 10000000):
      $large = 7;
    elseif ($keyword['request_large'] > 10000000 && $keyword['request_large'] <= 50000000):
      $large = 8;
    elseif ($keyword['request_large'] > 50000000 && $keyword['request_large'] <= 100000000):
      $large = 9;
    elseif ($keyword['request_large'] > 100000000):
      $large = 10;
    endif;

    //exact
    $exact = 0;
    if ($keyword['request_exact'] <= 100):
      $exact = 0;
    elseif ($keyword['request_exact'] > 100 && $keyword['request_exact'] <= 500):
      $exact = 1;
    elseif ($keyword['request_exact'] > 500 && $keyword['request_exact'] <= 1000):
      $exact = 2;
    elseif ($keyword['request_exact'] > 1000 && $keyword['request_exact'] <= 5000):
      $exact = 3;
    elseif ($keyword['request_exact'] > 5000 && $keyword['request_exact'] <= 10000):
      $exact = 4;
    elseif ($keyword['request_exact'] > 10000 && $keyword['request_exact'] <= 50000):
      $exact = 5;
    elseif ($keyword['request_exact'] > 50000 && $keyword['request_exact'] <= 100000):
      $exact = 6;
    elseif ($keyword['request_exact'] > 100000 && $keyword['request_exact'] <= 500000):
      $exact = 7;
    elseif ($keyword['request_exact'] > 500000 && $keyword['request_exact'] <= 1000000):
      $exact = 8;
    elseif ($keyword['request_exact'] > 1000000 && $keyword['request_exact'] <= 25000000):
      $exact = 9;
    elseif ($keyword['request_exact'] > 25000000):
      $exact = 10;
    endif;

    //intitle
    $title = 0;
    if ($keyword['request_title'] <= 50):
      $title = 0;
    elseif ($keyword['request_title'] > 50 && $keyword['request_title'] <= 100):
      $title = 1;
    elseif ($keyword['request_title'] > 100 && $keyword['request_title'] <= 250):
      $title = 2;
    elseif ($keyword['request_title'] > 250 && $keyword['request_title'] <= 500):
      $title = 3;
    elseif ($keyword['request_title'] > 500 && $keyword['request_title'] <= 1000):
      $title = 4;
    elseif ($keyword['request_title'] > 1000 && $keyword['request_title'] <= 2500):
      $title = 5;
    elseif ($keyword['request_title'] > 2500 && $keyword['request_title'] <= 5000):
      $title = 6;
    elseif ($keyword['request_title'] > 5000 && $keyword['request_title'] <= 10000):
      $title = 7;
    elseif ($keyword['request_title'] > 10000 && $keyword['request_title'] <= 100000):
      $title = 8;
    elseif ($keyword['request_title'] > 100000 && $keyword['request_title'] <= 1000000):
      $title = 9;
    elseif ($keyword['request_title'] > 1000000):
      $title = 10;
    endif;

    $concu = round(($keyword['competition'] * 10), 0);

    return round((($large * 4) + ($exact * 3) + ($title * 2) + $concu) / 10, 2);
  }

  public static function getPosition($keyword, $id_user, $url) {

    $date = new DateTime();
    $date->modify('-30 days');

    Connexion::getInstance()->query("SELECT p.position, p.date
        FROM position p
        LEFT JOIN url u ON u.id_url = p.id_url
        WHERE p.id_keyword =  '" . $keyword['id_keyword'] . "'
        AND u.url LIKE '%" . $url . "%' AND p.date BETWEEN '" . $date->format('Y-m-d') . "' AND '" . date('Y-m-d') . "' ");

    $positions = array();
    while ($r = Connexion::getInstance()->fetch())
      $positions[$r['date']] = $r['position'];

    $datearray = new DateTime();
    $timestamp = time();
    $return = array();

    for ($i = 0; $i < 30; $i++):
      $return[$datearray->format('Y-m-d')] = (isset($positions[$datearray->format('Y-m-d')])) ? $positions[$datearray->format('Y-m-d')] : 100;
      $datearray->modify('-1 day');
      $timestamp -= 86400;
    endfor;

    ksort($return);

    return $return;
  }

  public function getConcurrents($keyword) {
    Connexion::getInstance()->query("SELECT max(date) FROM position WHERE id_keyword = '" . $keyword['id_keyword'] . "' ");
    $date = Connexion::getInstance()->result();

    Connexion::getInstance()->query("SELECT u.*, p.position FROM position p LEFT JOIN url u ON p.id_url = u.id_url WHERE p.id_keyword = '" . $keyword['id_keyword'] . "' AND date = '" . $date . "' ");

    return Connexion::getInstance()->fetchAll();
  }

}
