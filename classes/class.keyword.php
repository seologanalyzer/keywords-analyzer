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

}
