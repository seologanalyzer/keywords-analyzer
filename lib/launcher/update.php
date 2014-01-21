<?php

//keywords informations
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
}

//keywords position
$position = getposition($token);

if (isset($position->keyword)) {
  while ((isset($position->keyword))):
    $positions = array();

    for ($start = 0; $start < 10; $start++):

      echo @date('Y-m-d H:i:s') . ' - Position : ' . $position->keyword . ' (Page ' . ($start + 1) . ')' . "\n";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_URL, 'https://www.google.' . $position->gg . '/search?hl=fr&gl=fr&q=' . urlencode($position->keyword) . '&ie=utf-8&oe=utf-8&client=firefox-a&gws_rd=cr&ei=KJrGUvrsNeL30gXn6IHQDg&pws=0&start=' . ($start * 10 ));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; fr-FR; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
      $response = curl_exec($ch);
      curl_close($ch);

      $page = utf8_decode($response);
      $page = str_replace(array('<em>', '</em>', '<b>', '</b>'), '', $page);

      preg_match_all('@<h3\s*class=[^<>]*>\s*<a[^<>]*href="([^<>]*)"[^<>]*>(.*)</a>\s*</h3>@siU', $page, $results);

      $titles = $results[2];
      $urls = $results[1];
      //clean images
      foreach ($urls as $k => $url):
        if (strpos($url, '/url?q') !== false):
          $positions[] = array('url' => str_replace('/url?q=', '', $url), 'title' => $titles[$k]);
        endif;
      endforeach;

      for ($i = rand($position->delay, ($position->delay + 3)); $i > 0; $i--):
        echo '.';
        sleep(1);
      endfor;

    endfor;

    //call post to the api
    //curl to webservice
    $ch = curl_init();
    $fields = array(
        'token' => $token,
        'id_keyword' => urlencode($position->id_keyword),
        'values' => base64_encode(urlencode(serialize($positions)))
    );
    $fields_string = '';
    foreach ($fields as $key => $value):
      $fields_string .= $key . '=' . $value . '&';
    endforeach;

    rtrim($fields_string, '&');

    curl_setopt($ch, CURLOPT_URL, 'http://keywords.dulol.fr/api/setposition');
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    $result = curl_exec($ch);

    curl_close($ch);

    $position = getposition($token);

  endwhile;
}

//if no queue
if (!isset($position->keyword) && !isset($keyword->keyword)) {
  echo @date('Y-m-d H:i:s') . ' : Pas de mots cles en attente.' . "\n";
  echo @date('Y-m-d H:i:s') . ' : Vous pouvez fermer cette fenetre.' . "\n";
  sleep(1000);
}

function getposition($token) {
  $ch = curl_init();
  $fields = array('token' => $token);
  $fields_string = '';
  foreach ($fields as $key => $value):
    $fields_string .= $key . '=' . $value . '&';
  endforeach;

  rtrim($fields_string, '&');

  curl_setopt($ch, CURLOPT_URL, 'http://keywords.dulol.fr/api/getposition');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POST, count($fields));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

  $result = curl_exec($ch);
  curl_close($ch);

  $keyword = json_decode($result);
  return $keyword;
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
