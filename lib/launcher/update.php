<?php

$keyword = get($token);

if (isset($keyword->keyword)) {

  while (isset($keyword->keyword)):

    echo @date('Y-m-d H:i:s') . ' - Analyse de ' . $keyword->keyword . "\n";

    $array = array('request_exact' => '"' . urlencode($keyword->keyword) . '"',
        'request_large' => urlencode($keyword->keyword),
        'request_title' => 'intitle:"' . urlencode($keyword->keyword) . '"');

    foreach ($array as $type => $search):

      echo @date('Y-m-d H:i:s') . ' - Type : ' . $type . ' : ';

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_URL, 'https://www.google.' . $keyword->gg . '/search?hl=fr&gl=fr&q=' . $search . '&ie=utf-8&oe=utf-8&client=firefox-a&gws_rd=cr&ei=KJrGUvrsNeL30gXn6IHQDg&pws=0');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr-FR; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
      $response = curl_exec($ch);
      curl_close($ch);

      $page = utf8_decode($response);
      preg_match_all('#resultStats">(.*)</div>#siU', $page, $results);

      //error
      $nbresult = str_replace(array('Environ', '?r?sultats', ' ', 'Â ', '&#160;', 'árÚsultats'), '', @$results[1][0]);

      echo (int) $nbresult . "\n";

      //curl to webservice
      $ch = curl_init();
      $fields = array(
          'token' => $token,
          'id_keyword' => urlencode($keyword->id_keyword),
          'type' => urlencode($type),
          'value' => urlencode((int) $nbresult)
      );
      $fields_string = '';
      foreach ($fields as $key => $value):
        $fields_string .= $key . '=' . $value . '&';
      endforeach;

      rtrim($fields_string, '&');

      curl_setopt($ch, CURLOPT_URL, 'http://keywords.dulol.fr/api/set');
      curl_setopt($ch, CURLOPT_POST, count($fields));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

      $result = curl_exec($ch);

      curl_close($ch);

      for ($i = rand($keyword->delay, ($keyword->delay + 3)); $i > 0; $i--):
        echo '.';
        sleep(1);
      endfor;

      echo "\n";

    endforeach;

    $keyword = get($token);

  endwhile;
}else {

  echo @date('Y-m-d H:i:s') . ' : Pas de mots cles en attente.' . "\n";
  echo @date('Y-m-d H:i:s') . ' : Vous pouvez fermer cette fenetre.' . "\n";
  sleep(1000);
}

function get($token) {
  $ch = curl_init();
  $fields = array('token' => $token);
  $fields_string = '';
  foreach ($fields as $key => $value):
    $fields_string .= $key . '=' . $value . '&';
  endforeach;

  rtrim($fields_string, '&');

  curl_setopt($ch, CURLOPT_URL, 'http://keywords.dulol.fr/api/get');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, count($fields));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

  $result = curl_exec($ch);
  curl_close($ch);

  $keyword = json_decode($result);
  return $keyword;
}
