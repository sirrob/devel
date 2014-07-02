<?php

  /**
   * Skrypt odpowiedzalny za organizację wyświetlania strony
   * @author    Michał Bzowy
   * @copyright Copyright (c) 2008, Michał Bzowy
   * @since     2008-08-12 12:36:48
   * @link      www.imt-host.pl
   */

  /**
   * Autoryzacja
   */
  define('SMDESIGN', true);
  define('SID', "fpp324qr49d30hlrfwehe45fd");

  session_start();

  /**
   * Ustawienie wartości domyślnych
   */
  $lgconf = array('pl', 'en');
  $_GLOBAL['lang'] = 'pl'; //wersja językowa strony
  $_GLOBAL['langid'] = 1;
  $_GLOBAL['ntitle'] = '';
  $_GLOBAL['PATH'] = array();
  $page_desc = '';
  //tutaj dodać sprawdzanie czy wlinku jest wersja językowa

  // SEO Tags
  $seo_language_id = null;
  $seo_brand_id = null;
  $seo_category_id = null;
  $seo_group_id = null;
  $seo_product_id = null;
  $seo_sex = null;
  $seo_sale = 0;
  // --- to be continued

  define("COMMON_PATH", "common/");
  include_once("incphp/mb_ini.php");
  include_once(COMMON_PATH . "incphp/mb_func.php");
  include_once(COMMON_PATH . "incphp/class_mysql.php");
  include_once(COMMON_PATH . "incphp/class.cleanurl.php");

  // SunRise SEO
  @define('SURIS', $_SERVER['REQUEST_URI']);
  @include_once('/sunrise/opt.php');


  // Pobieranie wartości z URL
  $clean = new CleanURL;
  $clean->parseURL();
  $clean->setRelative('relativeslash');
  $clean->setParts('lang', 'page', 'alias');

  $turl = array();
  $turl = $clean->getParts();

  if (count($turl) == 0) {
    $turl[0] = 'pl';
    $turl[1] = 'strona_glowna';
    if (isset($_SESSION[SID]['lang'])) $turl[0] = $_SESSION[SID]['lang'];
  } else $_SESSION[SID]['lang'] = $turl[0];


  if (in_array($turl[0], $lgconf)) {
    $query = "select * from " . dn('language') . " where sign='" . $_SESSION[SID]['lang'] . "';";
    $res   = $db->query($query);
    $dane  = $db->fetch($res);

    $_SESSION[SID]['langid'] = $dane['id'];
    $_GLOBAL['langid']       = $dane['id'];
  } else {
    $turl[0]                 = 'pl';
    $_SESSION[SID]['lang']   = 'pl';
    $_SESSION[SID]['langid'] = 1;
    $_GLOBAL['langid']       = 1;
  }

  if ((in_array($turl[0], $lgconf)) AND (empty($turl[1]))) {
    header('Location: ' . $_GLOBAL['page_url']);
  }

  if ((int)$_GLOBAL['langid'] == 0) $_GLOBAL['langid'] = 1;

  // SEO Tags
  $seo_language_id = $_GLOBAL['langid'];
  // --- to be continued


  //1. sprawdzanie w CMS
  //2. kategorie
  //3. produkty
  //4. panel klienta - uzytkownik
  //5. zamawianie - zakup
  $URL = array();
  $sURL = array();
  $_GLOBAL['lang'] = $turl[0];
  $_GLOBAL['pl'] = 'active';
  $L['{LANG}'] = $_GLOBAL['LANG'];


  /**
   * Załączenie bibliotek
   */
  include_once("incphp/mb_pliki.php");


  /**
   * SunRise script
   */
  @define('SURIS', $_SERVER['REQUEST_URI']);
  @include_once('sunrise/opt.php');


  /**
   * Początek przetwarzania
   */
  include_once("incphp/mb_common_begin.php");
  /**
   * Wysyłanie e-mail - Poleć znajomemu
   */
  if (isset($_POST['recomentfriend'])) {
    $query = "select * from " . dn('cms') . " where st_id=76;";
    $res   = $db->query($query);
    $item  = $db->fetch($res);

    $M["m_to"]      = s($_POST['recomentfriend']);
    $M["m_subject"] = "Ciekawa strona!";
    $M["m_message"] = '<div class="width: 300px;">' . str_replace('/files/', $_GLOBAL['page_url'] . 'files/', s($item['st_tresc'])) . '</div>';
    my_mail_smtp($M);
    onload("alert('E-mail polecający wysłany')");
  }


  /**
   * Dołącznie obsługi koszyka
   */
  include_once("incphp/class_basket.php");
  include_once("template/languages/" . $_GLOBAL['lang'] . "/language.php");

  /**
   * Czyszczenie z pustych wartości
   */
  unset($turl[0]);
  if (count($turl) > 0) {
    $lp = 0;
    foreach ($turl as $value) {
      if ((!empty($value)) && ($value != '')) $sURL[$lp++] = $value;
    }
  }

  if (count($sURL) == 2) {
    if (($sURL[0] == 'uzytkownik') && ($sURL[1] == 'wyloguj')) {
      $Cuzytkownik->logout();
    }
  }

  if (empty($_GLOBAL['lang'])) $_GLOBAL['lang'] = 'pl';

  if (isset($_POST['spd'])) $_GET['spd'] = $_POST['spd'];
  if (isset($_POST['gp'])) $_GET['gp'] = $_POST['gp'];
  if (isset($_POST['ska'])) $_GET['ska'] = $_POST['ska'];
  if (isset($_POST['sro'])) $_GET['sro'] = $_POST['sro'];
  if (isset($_POST['sna'])) $_GET['sna'] = $_POST['sna'];
  if (isset($_POST['a'])) $_GET['a'] = $_POST['a'];
  if (isset($_POST['o'])) $_SESSION[SID]['shop']['o'] = $_POST['o'];
  if (isset($_POST['ppp'])) {
    $_SESSION[SID]['shop']['ppp'] = $_POST['ppp'];
    $_GET['ppp']                  = $_POST['ppp'];
  }


  /**
   * Interpretacja URL-a oraz benerowanie ścieżki oraz tytułu strony
   */
  switch ($sURL[0]) {
    case 'cart':
      $_GLOBAL['ntitle'] = ucfirst($L['{T_KOSZYK}']); //'koszyk';
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
      $URL[0]            = $sURL[0] = 'koszyk';
      break;
    case 'paymant':
      $_GLOBAL['ntitle'] = ucfirst($L['{T_ZAKUP}']); //'zakup';
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
      $URL[0]            = $sURL[0] = 'zakup';

      switch ($sURL[1]) {
        case 'mess':
          if (isset($sURL[2])) $_GET['v'] = 1;
        case 'bez':
        case 'haslo':
        case 'dlaciebie':
        case 'obserwowane':
          if (isset($sURL[2])) $_GET['drop'] = $sURL[2];
          unset($sURL[2]);
        case 'dane':
        case 'rabat':
          $_GET['a'] = $sURL[1];
          break;
        case 'zakup':
        case 'selection':
          $_GET['url'] = $sURL[1];
          break;
        case 'message':
          $_GET['v'] = $sURL[2];
          $_GET['a'] = 'p24';
          $URL[0]    = 'zakup';
          break;
      }
      break;
    case 'summary':
      $_GLOBAL['ntitle'] = ucfirst($L['{T_ZAKUP}']); //'zakup';
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
      $URL[0]            = $sURL[0] = 'zakup';
      if (isset($sURL[1])) $_GET['a'] = 'bez';
      //print_r($sURL);
      break;
    case 'finalizing':
      $_GLOBAL['ntitle'] = ucfirst($L['{T_ZAKUP}']); //'zakup';
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
      $URL[0]            = $sURL[0] = 'zakup';
      $_POST["a"] == "podsumowanie";
      if (isset($sURL[1])) $_GET['a'] = 'bez';
      break;
    case "zamowienie":
      if ($sURL[1] == 'id') $_GET['id'] = $sURL[2];
      $URL[0] = 'zamowienie';
      break;
    case "koszyk":
      switch ($sURL[1]) {
        case 'error':
          $_GET['stan'] = $sURL[1];
          break;
      }
    case "zakup":
    case "uzytkownik":
      // koszyk -> cart
      // zakup -> krok2 (ustalić nazwę)
      // zakup (krok3) -> summary
      // zakup (krok4) -> ustalić nazwę
      switch ($sURL[1]) {
        case 'mess':
          if (isset($sURL[2])) $_GET['v'] = 1;
        case 'bez':
        case 'haslo':
        case 'dlaciebie':
        case 'obserwowane':
          if (isset($sURL[2])) $_GET['drop'] = $sURL[2];
          unset($sURL[2]);
        case 'dane':
        case 'rabat':
          $_GET['a'] = $sURL[1];
          break;
        case 'zakup':
        case 'selection':
          $_GET['url'] = $sURL[1];
          break;
        case 'message':
          $_GET['v'] = $sURL[2];
          $_GET['a'] = 'p24';
          $URL[0]    = 'zakup';
          break;
      }
    case "fasion_store":
    case "news_events":
    case "gomez_club":
    case "sale":
      $URL[0] = $sURL[0];

      switch ($sURL[0]) {
        case 'koszyk':
          $_GLOBAL['ntitle'] = ucfirst($L['{T_KOSZYK}']); //'koszyk';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          break;
        case 'zakup':
          $_GLOBAL['ntitle'] = ucfirst($L['{T_ZAKUP}']); //'zakup';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          break;
        case 'uzytkownik':
          $_GLOBAL['ntitle'] = ucfirst($L['{T_UZYTKOWNIK}']); //'użytkownik';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          break;
        case 'zamowinie':
          $_GLOBAL['ntitle'] = ucfirst($L['{T_ZAMOWIENIE}']); //'zamówienie';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          break;
        case 'news_events':
          $_GLOBAL['ntitle'] = 'News & Events';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          if (isset($sURL[1])) {
            $query = "select * from " . dn('news') . " where page=2 and alias='" . $sURL[1] . "';";
            $res   = $db->query($query);
            $dane  = $db->fetch($res);
            $_GLOBAL['ntitle'] .= ' :: ' . $dane['nazwa'];
            $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => $dane['nazwa']);
          }
          break;
        case 'fasion_store':
          $_GLOBAL['ntitle'] = 'Fashion Store';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          if (isset($sURL[1])) {
            $query = "select * from " . dn('news') . " where page=1 and alias='" . $sURL[1] . "';";
            $res   = $db->query($query);
            $dane  = $db->fetch($res);
            $_GLOBAL['ntitle'] .= ' :: ' . $dane['nazwa'];
            $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => $dane['nazwa']);
          }
          break;
        case 'gomez_club':
          $_GLOBAL['ntitle'] = 'Gomez Club';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          break;
        case 'sale':
          $_GLOBAL['ntitle'] = 'Sale';

          // SEO Tags
          $seo_sale = '1';
          // --- to be continued

          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $_GLOBAL['ntitle']);
          if ($sURL[1] == 'women') {
            $_GLOBAL['ntitle'] .= ' :: Women';
            $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => 'Women');

            // SEO Tags
            $seo_sex = "'w'";
            // --- to be continued
          }

          if ($sURL[1] == 'men') {
            $_GLOBAL['ntitle'] .= ' :: Men';
            $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => 'Men');

            // SEO Tags
            $seo_sex = "'m'";
            // --- to be continued
          }

          if ($sURL[1] == 'brands') {
            $_GLOBAL['ntitle'] .= ' :: Brands';
            $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => 'Brands');
          }
          break;
      }

      if ((isset($sURL[2])) && ($sURL[1] != 'brands') && ($sURL[1] != '70')) {
        if ($sURL[1] == 'men') $idstart = (($_GLOBAL['langid'] != 1) ? 220 : 122);
        else $idstart = (($_GLOBAL['langid'] != 1) ? 219 : 121);

        $query        = "select * from " . dn('sklep_kategoria') . " where ka_alias='" . $sURL[2] . "'";
        $res          = $db->query($query);
        $item         = $db->fetch($res);
        $_GET['a']    = 's';
        $_GET['sale'] = 1;
        $URL[0]       = 'sklep';
        $URL[1]       = 'szukaj';
        if (count($sURL) == 4) {
          $_GET['p'] = $sURL[3];
        }

        $_GLOBAL['ntitle'] .= ' :: ' . $item['ka_nazwa'] . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
        $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/' . $sURL[2] . '/', 'name' => $item['ka_nazwa']);

        $_GLOBAL['shop']['category'] = $idstart;

        $query = "select ka_id, ka_alias from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$idstart . ";";
        $res   = $db->query($query);
        while ($item = $db->fetch($res)) {
          if ($sURL[2] == $item['ka_alias']) {
            $ids[$item['ka_id']]        = $item['ka_id'];
            $_GLOBAL['shop']['category'] = $item['ka_id'];

            // SEO Tags
            $seo_category_id = $item['ka_id'];
            // --- to be continued

            break;
          }

          $query = "select ka_id, ka_alias from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . ";";
          $res2  = $db->query($query);
          while ($item2 = $db->fetch($res2)) {
            if ($sURL[2] == $item2['ka_alias']) {
              $ids[$item2['ka_id']]        = $item2['ka_id'];
              $_GLOBAL['shop']['category'] = $item2['ka_id'];

              // SEO Tags
              $seo_category_id = $item2['ka_id'];
              // --- to be continued

              break;
            }

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item2['ka_id'] . ";";
            $res3  = $db->query($query);
            while ($item3 = $db->fetch($res3)) {
              if ($sURL[2] == $item3['ka_alias']) {
                $ids[$item3['ka_id']]        = $item3['ka_id'];
                $_GLOBAL['shop']['category'] = $item3['ka_id'];

                // SEO Tags
                $seo_category_id = $item3['ka_id'];
                // --- to be continued
                break;
              }
            }
          }
        }
      } else
        if (($sURL[0] == 'sale') && ($sURL[1] == '70')) {
          //przecena 70% - wyświetlać przecenę -46%
          //$_GLOBAL['shop']['sale2'] = 'y';
          $_GET['a']     = 's';
          $_GET['sale2'] = 1;
          $URL[0]        = 'sklep';
          $URL[1]        = 'szukaj';
          if (isset($sURL[2])) $_GET['p'] = $sURL[2];
          $_GLOBAL['ntitle'] .= ' :: 70' . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
        } else
          if (($sURL[0] == 'sale') && ($sURL[1] == 'brands'))
            if (isset($sURL[2])) {
              if (($sURL[2] == 'men') || ($sURL[2] == 'women')) {
                if (count($sURL) == 4 || count($sURL) == 5) {
                  $_GET['a']    = 's';
                  $_GET['sale'] = 1;
                  $URL[0]       = 'sklep';
                  $URL[1]       = 'szukaj';
                  if ($sURL[2] == 'men') $_GLOBAL['shop']['category'] = (($_GLOBAL['langid'] != 1) ? 220 : 122);
                  else $_GLOBAL['shop']['category'] = (($_GLOBAL['langid'] != 1) ? 219 : 121);

                  $query = "select * from " . dn('sklep_producent') . " where pd_alias='" . $sURL[3] . "';";
                  $res   = $db->query($query);
                  $dane  = $db->fetch($res);

                  $_GLOBAL['shop']['brands'][$dane['pd_id']] = $dane['pd_id'];
                  $_GET['brands'][$dane['pd_id']]            = $dane['pd_id'];
                  $query                                     = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . ";";
                  $res                                       = $db->query($query);
                  while ($item = $db->fetch($res)) {
                    $_GLOBAL['shop']['subkat'][$item['ka_id']] = $item['ka_id'];

                    // SEO Tags
                    // $seo_category_id = $item['ka_id'];
                    // --- to be continued

                    $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . ";";
                    $res2  = $db->query($query);
                    while ($item2 = $db->fetch($res2)) {
                      $_GLOBAL['shop']['subkat'][$item2['ka_id']] = $item2['ka_id'];

                      // SEO Tags
                      $seo_category_id = $item2['ka_id'];
                      // --- to be continued

                      $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item2['ka_id'] . ";";
                      $res3  = $db->query($query);
                      while ($item3 = $db->fetch($res3)) {
                        $_GLOBAL['shop']['subkat'][$item3['ka_id']] = $item3['ka_id'];

                        // SEO Tags
                        $seo_category_id = $item3['ka_id'];
                        // --- to be continued
                      }
                    }
                  }

                  if (isset($sURL[4]) and is_numeric($sURL[4])) {
                    $_GET['p'] = $sURL[4];
                  }

                  $_GLOBAL['ntitle'] .= ' :: ' . strip_tags($dane['pd_nazwa']) . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
                }
              } else {
                $query        = "select * from " . dn('sklep_producent') . " where pd_alias='" . str_replace("'", "\\'", $sURL[2]) . "';";
                $res          = $db->query($query);
                $item         = $db->fetch($res);
                $_GET['a']    = 's';
                $_GET['spd']  = $item['pd_id'];
                $_GET['sale'] = 1;
                if (isset($sURL[3]) and is_numeric($sURL[3])) {
                  $_GET['p'] = $sURL[3];
                }
                $URL[0]                                    = 'sklep';
                $URL[1]                                    = 'szukaj';
                $_GLOBAL['shop']['brands'][$item['pd_id']] = $item['pd_id'];
                $_GET['brands'][$item['pd_id']]            = $item['pd_id'];
                $_GLOBAL['ntitle'] .= ' :: ' . strip_tags($item['pd_nazwa']) . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
                $_GLOBAL['PATH'][] = array('link' => $sURL[2], 'name' => strip_tags($item['pd_nazwa']));
              }
            } else
              if (($sURL[0] == 'sale') && (($sURL[1] == 'women') || ($sURL[1] == 'men'))) {
                $ids          = array();
                $query        = "select * from " . dn('sklep_kategoria') . " where pd_alias='" . $sURL[2] . "';";
                $res          = $db->query($query);
                $item         = $db->fetch($res);
                $_GET['a']    = 's';
                $URL[1]       = 'szukaj';
                $_GET['ska']  = $item['ka_id'];
                $_GET['sale'] = 1;
                $URL[0]       = 'sklep';
                $_GLOBAL['ntitle'] .= ' :: ' . $item['ka_nazwa'];
                $_GLOBAL['PATH'][] = array('link' => $sURL[2], 'name' => $item['ka_nazwa']);

                $_GLOBAL['shop']['subkat'][$item['ka_id']] = $item['ka_id'];
              }

      if (($sURL[0] == 'zakup') && ($sURL[1] == 'message')) {
        $_GET['v'] = $sURL[2];
        $_GET['a'] = 'p24';
        $URL[0]    = 'zakup';
      }
      break;
    case "groups":
      $URL[0]            = $sURL[0];
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $sURL[0]);
      $_GET['p']         = $sURL[2];

      // get and check group of products
      $query = "select * from " . dn('group') . " where alias='" . $alias . "' AND active = 1 AND (active_start < CURRENT_TIMESTAMP OR active_start IS NULL) AND (active_end > CURRENT_TIMESTAMP OR active_end IS NULL);";

      $item = $db->onerow($query);

      if (!is_array($item)) {
        header('Location: ' . $_GLOBAL['page_url']);
        exit;
      }

      $URL[0]    = 'sklep';
      $URL[1]    = 'szukaj';
      $_GET['a'] = 's';

      $_GET['gp']                             = $item['id'];
      $_GLOBAL['shop']['groups'][$item['id']] = $item['id'];
      $_GET['groups']                         = $item['id'];

      // debug('pushing: '.$item['id']);

      $_GLOBAL['ntitle'] .= $item['name'] . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');

      // SEO Tags
      $seo_group_id = $item['id'];
      // --- to be continued
      break;
    case "brands":
      $URL[0]            = $sURL[0];
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $sURL[0]);

      if (count($sURL) > 0) {
        foreach ($sURL as $key => $value) {
          if ($value == 'mono') {
            $_GLOBAL['shop']['mono'] = 'y';
            $_GET['mono']            = 'y';
          }
        }
      }

      $_GET['brand'] = 'y';
      if (count($sURL) > 1) {
        if ((isset($_GET['plec'])) && ($_GET['plec'] != $_GLOBAL['shop']['brn']['plec'])) {
          $_GLOBAL['shop']['brn']['subkat'] = array();
          unset($_GLOBAL['shop']['price']);
        }
        if (count($sURL) == 2) {
          if (isset($_GET['plec'])) {
            $tmmp                           = $sURL[1];
            $sURL[1]                        = $_GET['plec'];
            $sURL[2]                        = $tmmp;
            $_GLOBAL['shop']['brn']['plec'] = $_GET['plec'];
          }
        } else {
          if (isset($_GET['plec'])) {
            $sURL[1]                        = $_GET['plec'];
            $_GLOBAL['shop']['brn']['plec'] = $_GET['plec'];
          }
        }

        $notin = '';

        if (isset($_GET['subkat'])) {
          $_GLOBAL['shop']['brn']['subkat'] = $_GET['subkat'];
        } else $_GLOBAL['shop']['brn']['subkat'] = array();

        if ($sURL[1] == 'men') {
          $alias             = $sURL[2];
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => 'Men');
          $_GET['plec']      = 'men';

          // SEO Tags
          $seo_sex = "'m'";
          // --- to be continued

          if ((isset($sURL[3])) && (!empty($sURL[3]))) {
            if ($sURL[3] == 'mono') $_GET['p'] = $sURL[4];
            else $_GET['p'] = $sURL[3];
          }

          if ($sURL[1] == 'men') $idstart = (($_GLOBAL['langid'] != 1) ? 220 : 122);
          else $idstart = (($_GLOBAL['langid']) ? 219 : 121);

          $ids = array();

          $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$idstart . " " . $notin . ";";
          $res   = $db->query($query);
          while ($item = $db->fetch($res)) {
            $ids[$item['ka_id']] = $item['ka_id'];

            // SEO Tags
            // $seo_category_id = $item['ka_id'];
            // --- to be continued

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . " " . $notin . ";";
            $res2  = $db->query($query);
            while ($item2 = $db->fetch($res2)) {
              $ids[$item2['ka_id']] = $item2['ka_id'];

              // SEO Tags
              // $seo_category_id = $item2['ka_id'];
              // --- to be continued

              $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (int)$item2['ka_id'] . " " . $notin . ";";
              $res3  = $db->query($query);
              while ($item3 = $db->fetch($res3)) {
                $ids[$item3['ka_id']] = $item3['ka_id'];

                // SEO Tags
                // $seo_category_id = $item3['ka_id'];
                // --- to be continued
              }
            }
          }
          $_GLOBAL['shop']['subkat'] = $ids;
        } else
          if ($sURL[1] == 'women') {
            $alias             = $sURL[2];
            $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => 'Women');
            $_GET['plec']      = 'women';

            // SEO Tags
            $seo_sex = "'w'";
            // --- to be continued

            if ((isset($sURL[3])) && (!empty($sURL[3]))) {
              if ($sURL[3] == 'mono') $_GET['p'] = $sURL[4];
              else $_GET['p'] = $sURL[3];
            }
            unset($_SESSION[SID]['shop']['p']);

            $idstart = (($_GLOBAL['langid'] != 1) ? 219 : 121);

            $ids = array();

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$idstart . " " . $notin . ";";
            $res   = $db->query($query);
            while ($item = $db->fetch($res)) {
              $ids[$item['ka_id']] = $item['ka_id'];

              // SEO Tags
              // $seo_category_id = $item['ka_id'];
              // --- to be continued

              $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . " " . $notin . ";";
              $res2  = $db->query($query);
              while ($item2 = $db->fetch($res2)) {
                $ids[$item2['ka_id']] = $item2['ka_id'];

                // SEO Tags
                // $seo_category_id = $item2['ka_id'];
                // --- to be continued

                $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (int)$item2['ka_id'] . " " . $notin . ";";
                $res3  = $db->query($query);
                while ($item3 = $db->fetch($res3)) {
                  $ids[$item3['ka_id']] = $item3['ka_id'];

                  // SEO Tags
                  // $seo_category_id = $item3['ka_id'];
                  // --- to be continued
                }
              }
            }

            $_GLOBAL['shop']['subkat'] = $ids;
          } else {
            $alias = $sURL[1];
            if (isset($sURL[2])) {
              if ($sURL[2] == 'mono') $_GET['p'] = $sURL[3];
              else $_GET['p'] = $sURL[2];
            }
          }

        $query     = "select * from " . dn('sklep_producent') . " where pd_alias = '" . $alias . "' order by pd_id;";
        $res       = $db->query($query);
        $item      = $db->fetch($res);
        $URL[0]    = 'sklep';
        $URL[1]    = 'szukaj';
        $_GET['a'] = 's';

        $_GET['b']   = 'y';
        $_GET['spd'] = $item['pd_id'];

        // SEO Tags
        $seo_brand_id = $item['pd_id'];
        // --- to be continued

        $_GLOBAL['shop']['brands'][$item['pd_id']] = $item['pd_id'];
        $_GET['brands'][$item['pd_id']]            = $item['pd_id'];
        $page_desc                                 = str_replace('"', '', strip_tags(s($item['pd_opis'])));

        // wg wytycznych Doroty jesli TH to również inne hilfigery
        if ($item['pd_id'] == 2) {
          $_GET['brands'][101]            = 101; // tailored
          $_GLOBAL['shop']['brands'][101] = 101;

          $_GET['brands'][86]            = 86; // denim
          $_GLOBAL['shop']['brands'][86] = 86;
        }
        // wg wytycznych Doroty jesli guess to również guess jeans i guess by marciano
        if ($item['pd_id'] == 74) {
          $_GET['brands'][71]            = 71; // by marciano
          $_GLOBAL['shop']['brands'][71] = 71;

          $_GET['brands'][62]            = 62; // jeans
          $_GLOBAL['shop']['brands'][62] = 62;
        }

        // wg wytycznych Doroty jesli hugo boss (orange) to również hugo boss green i boss hugo boss
        // if (($item['pd_id'] == 96) && ($_GET['mono'] == 'y')) {
        //              $_GET['brands'][97]            = 97; // hugo boss green
        //              $_GLOBAL['shop']['brands'][97] = 97;

        //              $_GET['brands'][110]            = 110; // boss hugo boss
        //              $_GLOBAL['shop']['brands'][110] = 110;
        //          }

        if ((isset($_GLOBAL['shop']['brn']['subkat'])) && (count($_GLOBAL['shop']['brn']['subkat']) > 0)) {
          $_GLOBAL['shop']['subkat'] = $_GLOBAL['shop']['brn']['subkat'];
        }

        $mono = "";
        if ($_GLOBAL['shop']['mono'] == 'y') $mono = " :: Shop-in-Shop";

        $_GLOBAL['ntitle'] .= strip_tags($item['pd_nazwa']) . $mono;
        $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1] . '/' . ((isset($sURL[2])) ? $item['pd_alias'] : ''), 'name' => strip_tags($item['pd_nazwa']));
      }


      $tmpTitle = '';
      if (isset($sURL[1])) {
        if ($sURL[1] == 'men') {
          $tmpTitle = ($_GLOBAL['langid'] == 1) ? ' dla mężczyzn' : ' for men';
        } elseif ($sURL[1] == 'women') {
          $tmpTitle = ($_GLOBAL['langid'] == 1) ? ' dla kobiet' : ' for women';
        } else {
          $tmpTitle = ':: Brands';
        }
      } else {
        $tmpTitle = 'Brands';
      }
      $_GLOBAL['ntitle'] .= $tmpTitle . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');

      // $_GLOBAL['ntitle'] .= ((isset($sURL[1]))?' :: ':'') . 'Brands';
      break;
    case 'women':
    case 'men':
    case 'kids':
      if (!isset($_POST['brands'])) unset($_GLOBAL['shop']['brands']);

      if (isset($sURL[0])) $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/', 'name' => $sURL[0]);
      $query  = "select * from " . dn('sklep_kategoria') . " where ka_alias='" . $sURL[0] . "' order by ka_id limit 1;";
      $res    = $db->query($query);
      $item   = $db->fetch($res);
      $URL[0] = 'sklep';
      $URL[1] = $item['ka_id'];

      if ($sURL[0] == 'kids') {
        $URL[1] = (($_GLOBAL['langid'] != 1) ? 308 : 277);

        // SEO Tags
        $seo_sex = "'k'";
        // --- to be continued
      } elseif ($sURL[0] == 'men') {
        $URL[1] = (($_GLOBAL['langid'] != 1) ? 220 : 122);

        // SEO Tags
        $seo_sex = "'m'";
        // --- to be continued
      } else {
        $URL[1] = (($_GLOBAL['langid'] != 1) ? 219 : 121);

        // SEO Tags
        $seo_sex = "'w'";
        // --- to be continued
      }

      if (count($sURL) > 1 and !is_numeric($sURL[1])) {
        if ($sURL[1] == 'nowosci') {
          $URL[0] = 'sklep';
          $URL[1] = 'nowosci';
          $URL[2] = $sURL[0];

          if ($sURL[0] == 'kids') {
            $idstart = (($_GLOBAL['langid'] != 1) ? 308 : 277);
          } elseif ($sURL[0] == 'men') {
            $idstart = (($_GLOBAL['langid'] != 1) ? 220 : 122);
          } else {
            $idstart = (($_GLOBAL['langid'] != 1) ? 219 : 121);
          }

          $_GET['kategoria']           = $idstart;
          $_GLOBAL['shop']['category'] = $idstart;

          if ((isset($_GET['kategoria'])) &&
            (!empty($_GET['kategoria'])) &&
            (($_GET['kategiria'] == 121) ||
              ($_GET['kategoria'] == 122) ||
              ($_GET['kategoria'] == 219) ||
              ($_GET['kategoria'] == 220) ||
              ($_GET['kategoria'] == 277) ||
              ($_GET['kategoria'] == 308))
          ) $idstart = $_GET['kategoria'];

          if (isset($_GET['kategoria'])) {
            $idstart                     = $_GET['kategoria'];
            $_GLOBAL['shop']['subkat'][] = $idstart;
          }
          $ids = array();

          $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$idstart . ";";
          $res   = $db->query($query);
          while ($item = $db->fetch($res)) {
            $ids[] = $item['ka_id'];
            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . ";";
            $res2  = $db->query($query);
            while ($item2 = $db->fetch($res2)) {
              $ids[] = $item2['ka_id'];
              $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item2['ka_id'] . ";";
              $res3  = $db->query($query);
              while ($item3 = $db->fetch($res3)) {
                $ids[] = $item3['ka_id'];
              }
            }
          }
          $_GLOBAL['shop']['subkat'] = $ids;

          if ((isset($_GET['subkat'])) && (count($_GET['subkat']) > 0)) $_GLOBAL['shop']['subkat'] = $_GET['subkat'];

          if (isset($_GET['kategoria'])) {
            $idstart                             = $_GET['kategoria'];
            $_GLOBAL['shop']['subkat'][$idstart] = $idstart;
          }

          $_GLOBAL['ntitle'] .= ($_GLOBAL['langid'] == 1) ? 'NOWOŚCI' : 'News';
          $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1], 'name' => $L["{T_B_NOWOSCI}"]); //'Nowości');
        } else {
          if (isset($_GET['kategoria'])) {
            $query = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (int)$_GET['kategoria'] . ";";
            $res   = $db->query($query);
            if ($db->num_rows($res) > 0) {
              $dane = $db->fetch($res);
              if ($dane['ka_alias'] != $sURL[1]) {
                header('Location: ' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/' . $dane['ka_alias'] . '/');
                exit;
              }
            }
          }

          $ka_id    = 0;
          $ka_nazwa = '';

          $query = "  select * from " . dn('sklep_kategoria') . "
                            where ka_alias='" . $sURL[0] . "' and `key` like '" . (($_GLOBAL['langid'] != 1) ? '0002' : '0001') . "%'
                            order by ka_id limit 1;";

          $res = $db->query($query);
          while ($item = $db->fetch($res)) {
            $ka_id    = $item['ka_id'];
            $ka_nazwa = $item['ka_nazwa'];

            // SEO Tags
            // $seo_category_id = $ka_id;
            // --- to be continued

            if ($item['ka_alias'] == $sURL[1]) break;
            //ka_alias='" . $sURL[1] . "' and
            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . " order by ka_id;";
            $res2  = $db->query($query);
            while ($item2 = $db->fetch($res2)) {
              $ka_id    = $item2['ka_id'];
              $ka_nazwa = $item2['ka_nazwa'];

              // SEO Tags
              $seo_category_id = $ka_id;
              // --- to be continued

              if ($item2['ka_alias'] == $sURL[1]) break;

              $query = "select * from " . dn('sklep_kategoria') . "
                                  where ka_alias='" . $sURL[1] . "' and ka_ka_id=" . (int)$item2['ka_id'] . "
                                  order by ka_id limit 1;";
              $res3  = $db->query($query);
              $lp3   = 0;
              while ($item3 = $db->fetch($res3)) {
                $lp3++;
                $ka_id    = $item3['ka_id'];
                $ka_nazwa = $item3['ka_nazwa'];

                // SEO Tags
                $seo_category_id = $ka_id;
                // --- to be continued

                if ((isset($sURL[2])) && (!is_numeric($sURL[2]))) {
                  $query                                      = "select * from " . dn('sklep_kategoria') . "
                                      where ka_alias='" . $sURL[2] . "' and ka_ka_id=" . (int)$item3['ka_id'] . "
                                      order by ka_id limit 1;";
                  $res4                                       = $db->query($query);
                  $item4                                      = $db->fetch($res4);
                  $_GET['subkat'][$item4['ka_id']]            = $item4['ka_id'];
                  $_GLOBAL['shop']['subkat'][$item4['ka_id']] = $item4['ka_id'];
                }

                if ($item3['ka_alias'] == $sURL[1]) break;
              };
              if ($lp3 > 0) break;
            };
          }

          $URL[1] = $ka_id;
          if ($ka_nazwa != '') $_GLOBAL['ntitle'] .= ucfirst($ka_nazwa);
          $_GLOBAL['PATH'][]           = array('link' => $sURL[0] . '/' . $sURL[1] . '/', 'name' => $ka_nazwa);
          $_GLOBAL['shop']['category'] = $ka_id;
        }
      } elseif (is_numeric($sURL[1])) {
        $_GET['p'] = $sURL[1];
      }

      if (count($sURL) == 3) {
        $_GET['p'] = $sURL[2];
      }

      switch ($sURL[0]) {
        case 'women':
          $_GLOBAL['ntitle'] .= ((isset($sURL[1])) ? ' :: ' : '') . 'Women' . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
          break;
        case 'men':
          $_GLOBAL['ntitle'] .= ((isset($sURL[1])) ? ' :: ' : '') . 'Men' . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
          break;
        case 'kids':
          $_GLOBAL['ntitle'] .= ((isset($sURL[1])) ? ' :: ' : '') . 'Kids' . (($_GLOBAL['langid'] == 1) ? ' :: strona ' : ' :: page ') . ((isset($_GET['p'])) ? ($_GET['p'] + 1) : '1');
          break;
      }

      break;
    case 'sklep':
      $URL[0] = $sURL[0];
      if ($sURL[1] == 'szukaj') {
        if (isset($_POST['spd'])) $_GET['spd'] = $_POST['spd'];
        if (isset($_POST['gp'])) $_GET['gp'] = $_POST['gp'];
        if (isset($_POST['ska'])) $_GET['ska'] = $_POST['ska'];
        if (isset($_POST['sro'])) $_GET['sro'] = $_POST['sro'];
        if (isset($_POST['sna'])) $_GET['sna'] = $_POST['sna'];
        if (isset($_POST['a'])) $_GET['a'] = $_POST['a'];
        $_GET['s_.x']  = 16;
        $_GET['&s_.y'] = 10;
        $URL[1]        = 'szukaj';
        $_GET['a']     = 's';
      }
      $_GLOBAL['ntitle'] = ucfirst($L['{T_SKLEP}']);
      break;
    case 'produkt':
      $URL[0]            = 'sklep';
      $URL[1]            = 'szukaj';
      $URL[2]            = $sURL[1];
      $_GLOBAL['ntitle'] = $L['{T_PRODUKT}'];

      // SEO Tags
      $seo_product_id = $sURL[1];
      // --- to be continued

      $query = "select * from " . dn('sklep_produkt') . " where pr_id=" . (int)$sURL[1] . ";";
      $res   = $db->query($query);
      $dane  = $db->fetch($res);

      $query = "select * from " . dn('produkt') . " where pr_id=" . (int)$sURL[1] . ";";
      $res   = $db->query($query);
      $pdane = $db->fetch($res);

      $query  = "select * from " . dn('sklep_producent') . " where pd_id=" . (int)$dane['pr_pd_id'] . ";";
      $res    = $db->query($query);
      $prDane = $db->fetch($res);

      if ($_GLOBAL['langid'] != 1) {
        $q           = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $dane['pr_id'] . " and langid=" . $_GLOBAL['langid'] . " and name='nazwa';";
        $r           = $db->query($q);
        $translation = $db->fetch($r);

        $product_name = $translation['description'];
      } else {
        $product_name = $dane['pr_nazwa'];
      }

      $_GLOBAL['ntitle'] = $product_name . ' :: ' . strip_tags($prDane['pd_nazwa']) . ' :: ' . $pdane['pr_indeks'];
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $dane['pr_id'] . '/' . $prDane['pd_alias'] . '/' . conv2file($dane['pr_nazwa']) . '/', 'name' => $product_name);

      $opis = strip_tags(str_replace("\n", ", ", $dane['pr_opis']));
      $opis = trim($opis);

      if (substr($opis, -1) == ',') {
        $opis = substr($opis, 0, strlen($opis) - 2);
      }

      if (substr($opis, 0, 1) == ',') {
        $opis = substr($opis, 1, strlen($opis) - 1);
      }

      $opis = str_replace(array("\r", '&oacute;'), array('', 'ó'), $opis);

      $page_desc = strip_tags($prDane['pd_nazwa']) . ', ' . $product_name . ' - ' . $opis;
      break;
    case 'wyszukiwarka':
      $URL[0] = 'sklep';
      $URL[1] = 'szukaj';
      //$_GLOBAL['ntitle'] = $L['{T_WYSZUKIWARKA}'];//'Wyszukiwarka';
      $_GLOBAL['ntitle'] = '';
      $_GLOBAL['PATH'][] = array('link' => $sURL[0] . '/' . $sURL[1], 'name' => $L['{T_WYSZUKIWARKA}']); //'Wyszukiwarka');
      unset($_SESSION[SID]['shopsz']);

      if (isset($_GET['price'])) {
        $_GLOBAL['shop']['price'] = $_GET['price'];
      } else unset($_GLOBAL['shop']['price']);


      if (isset($_GET['brands'])) {

        $_GLOBAL['shop']['brands'] = array();

        // wg wytycznych Doroty jesli TH to również inne hilfigery
        if (isset($_GET['brands'][2]) AND ($_GET['brands'][2] == 2) AND (!isset($_SESSION['ALL_HILFIGER']))) {
          $_GET['brands'][101] = 101; // tailored
          $_GET['brands'][86]  = 86; // denim

          $_SESSION['ALL_HILFIGER'] = true;
        }

        if (count($_GET['brands']) > 0) {
          $tmp  = array();
          $tmp2 = array();
          foreach ($_GET['brands'] as $k => $v) $_GLOBAL['shop']['brands'][$v] = $v;
          foreach ($_GET['brands'] as $key => $value) $tmp[$value] = $value;
          foreach ($tmp as $key => $value) {
            $_GET['brands'][$value] = $value;
            //$_GLOBAL['ntitle'] .=
            $query  = "select * from " . dn('sklep_producent') . " where pd_id=" . (int)$value . ";";
            $res    = $db->query($query);
            $prDane = $db->fetch($res);
            $tmp2[] = strip_tags($prDane['pd_nazwa']);
          }

          $_GLOBAL['ntitle'] .= join(' ', $tmp2);
          $page_desc .= join(' ', $tmp2);
        }
      } else $_GLOBAL['shop']['brands'] = array();


      if (isset($_GET['groups'])) {
        $_GLOBAL['shop']['groups'] = array();
        if (count($_GET['groups']) > 0) {
          $tmp  = array();
          $tmp2 = array();
          foreach ($_GET['groups'] as $k => $v) $_GLOBAL['shop']['groups'][$v] = $v;
          foreach ($_GET['groups'] as $key => $value) $tmp[$value] = $value;
          foreach ($tmp as $key => $value) {
            $_GET['groups'][$value] = $value;
            //$_GLOBAL['ntitle'] .=
            $query  = "select * from " . dn('group') . " where id=" . (int)$value . ";";
            $res    = $db->query($query);
            $prDane = $db->fetch($res);
            $tmp2[] = $prDane['name'];
          }

          $_GLOBAL['ntitle'] .= join(' ', $tmp2);
          $page_desc .= join(' ', $tmp2);
        }
      } else $_GLOBAL['shop']['groups'] = array();


      if (isset($_GET['kategoria'])) {
        if (($_GLOBAL['shop']['category'] != $_GET['kategoria']) && (!isset($_GLOBAL['shop']['category']))) {

          $_GLOBAL['shop']['category'] = $_GET['kategoria'];

          $query   = "select * from " . dn('sklep_kategoria') . " where ka_id=" . $_GLOBAL['shop']['category'] . ";";
          $res     = $db->query($query);
          $katDane = $db->fetch($res);

          $_GLOBAL['ntitle'] .= (($_GLOBAL['ntitle'] != '') ? ' :: ' : '') . ucfirst($katDane['ka_nazwa']);
          $page_desc .= (($_GLOBAL['ntitle'] != '') ? ' ' : '') . ucfirst($katDane['ka_nazwa']);
        }
        $_GLOBAL['shop']['category'] = $_GET['kategoria'];
      } else $_GLOBAL['shop']['category'] = '';

      if (isset($_GET['size'])) {
        $_GLOBAL['shop']['size'] = array();
        if (count($_GET['size']) > 0) {
          foreach ($_GET['size'] as $k => $v) $_GLOBAL['shop']['size'][$v] = $v;
          $tmp = array();
          foreach ($_GET['size'] as $key => $value) {
            $tmp[$value] = $value;
          }

          foreach ($tmp as $key => $value) {
            $_GET['size'][$value] = $value;
          }
        }
      } else $_GLOBAL['shop']['size'] = array();

      if (isset($_GET['kolor'])) {
        $_GLOBAL['shop']['kolor'] = array();
        if (count($_GET['kolor']) > 0) {
          foreach ($_GET['kolor'] as $k => $v) $_GLOBAL['shop']['kolor'][$v] = $v;
        }
      } else $_GLOBAL['shop']['kolor'] = array();

      if (isset($_GET['faktura'])) {
        $_GLOBAL['shop']['faktura'] = array();
        if (count($_GET['faktura']) > 0) {
          foreach ($_GET['faktura'] as $k => $v) $_GLOBAL['shop']['faktura'][$v] = $v;
        }
      } else $_GLOBAL['shop']['faktura'] = array();

      if (isset($_GET['fason'])) {
        $_GLOBAL['shop']['fason'] = array();
        if (count($_GET['fason']) > 0) {
          foreach ($_GET['fason'] as $k => $v) $_GLOBAL['shop']['fason'][$v] = $v;
        }
      } else $_GLOBAL['shop']['fason'] = array();


      if ((isset($_GET['brand'])) && ($_GET['brand'] == 'y')) {
        if (isset($_GET['plec'])) {
          if ($_GET['plec'] == 'men') {
            $idstart = (($_GLOBAL['langid'] != 1) ? 220 : 122);
            $ids     = array();

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$idstart . " " . $notin . ";";
            $res   = $db->query($query);
            while ($item = $db->fetch($res)) {
              $ids[$item['ka_id']] = $item['ka_id'];
              $query               = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . " " . $notin . ";";
              $res2                = $db->query($query);
              while ($item2 = $db->fetch($res2)) {
                $ids[$item2['ka_id']] = $item2['ka_id'];
                $query                = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (int)$item2['ka_id'] . " " . $notin . ";";
                $res3                 = $db->query($query);
                while ($item3 = $db->fetch($res3)) {
                  $ids[$item3['ka_id']] = $item3['ka_id'];
                }
              }
            }
            $_GLOBAL['shop']['subkat'] = $ids;
          } else
            if ($_GET['plec'] == 'women') {
              $idstart = (($_GLOBAL['langid'] != 1) ? 219 : 121);
              $ids     = array();

              $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$idstart . " " . $notin . ";";
              $res   = $db->query($query);
              while ($item = $db->fetch($res)) {
                $ids[$item['ka_id']] = $item['ka_id'];
                $query               = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . (int)$item['ka_id'] . " " . $notin . ";";
                $res2                = $db->query($query);
                while ($item2 = $db->fetch($res2)) {
                  $ids[$item2['ka_id']] = $item2['ka_id'];
                  $query                = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (int)$item2['ka_id'] . " " . $notin . ";";
                  $res3                 = $db->query($query);
                  while ($item3 = $db->fetch($res3)) {
                    $ids[$item3['ka_id']] = $item3['ka_id'];
                  }
                }
              }
              $_GLOBAL['shop']['subkat'] = $ids;
            } else unset($_GLOBAL['shop']['brn']['subkat']);
        }


        if (isset($_GET['subkat'])) {
          $_GLOBAL['shop']['brn']['subkat'] = array();
          if (count($_GET['subkat']) > 0) {
            $tmp = array();
            foreach ($_GET['subkat'] as $k => $v) {
              $_GLOBAL['shop']['brn']['subkat'][$v] = $v;

              $query   = "select * from " . dn('sklep_kategoria') . " where ka_id=" . (int)$v . ";";
              $res     = $db->query($query);
              $katDane = $db->fetch($res);
              $tmp[]   = $katDane['ka_nazwa'];
            }

            $_GLOBAL['ntitle'] .= (($_GLOBAL['ntitle'] != '') ? ' :: ' : '') . join(', ', $tmp);
            $page_desc .= (($_GLOBAL['ntitle'] != '') ? ' ' : '') . join(' ', $tmp);
          }
        } else $_GLOBAL['shop']['brn']['subkat'] = array();
      } else {
        if (isset($_GET['subkat'])) {
          $_GLOBAL['shop']['subkat'] = array();
          if (count($_GET['subkat']) > 0) {
            foreach ($_GET['subkat'] as $k => $v) $_GLOBAL['shop']['subkat'][$v] = $v;
          }
        } else $_GLOBAL['shop']['subkat'] = array();
      }
      $_GLOBAL['ntitle'] .= ' - ' . $L['{T_WYSZUKIWARKA}']; //'Wyszukiwarka';

      break;
    default:
      $query = "select * from " . dn('cms') . " where st_alias='" . $sURL[0] . "' order by st_id limit 1;";
      $res   = $db->query($query);
      $lp    = 0;
      debug($sURL[0]);
      if ($db->num_rows($res)) {
        $item   = $db->fetch($res);
        $URL[0] = 'cms';
        $URL[1] = $item['st_id'];
        if ($_GLOBAL['langid'] == 2) {
          $_GLOBAL['ntitle'] = $item['st_tytul_en'];
        } else {
          $_GLOBAL['ntitle'] = $item['st_tytul'];
        }


        $_GLOBAL['PATH'][] = array('link' => $sURL[0], 'name' => $_GLOBAL['ntitle']);
      }elseif($sURL[0] == 'bla')
      {
          $URL[0] = 'cms';
          $URL[1] = 999;
      }
      break;
  }


  /**
   * Sprawdzenie parametru sterującego
   * W zależności od pierwszego argumentu przekazanie kontroli do odpowiedniego modułu
   */
  switch ($URL[0]) {
    case "sklep":
      $A[2] = $URL[1];
      if ($URL[2] != "" and test_int($URL[2])) {
        $A[1] = "produkt";
        $A[3] = $URL[2];
        if ($URL[1] == "szukaj") $A[2] = "szukaj";
        elseif (in_array($URL[1], array("promocje", "topsell", "polecamy", "nowosci", "upominek"))) {
          $A[2] = "boks";
          $A[4] = $URL[1];
        }
      } elseif (in_array($URL[1], array("promocje", "topsell", "polecamy", "nowosci", "upominek"))) {
        $A[1] = "boks";
        $A[2] = $URL[1];
        if ($URL[2] == 'men' || $URL[2] == 'women') $A[3] = $URL[2];
      } elseif ($URL[1] == "szukaj") {
        $A[1] = "szukaj";
      } else {
        $A[1] = "kategoria";
      }
      include_once("incphp/class_shop.php");
      $Ccms            = new shop($A);
      $TPL["{BODYID}"] = "shop" . $Ccms->id;
      break;
    case "koszyk":
    case "zakup":
    case "uzytkownik":
    case "zamowienie":
    case "brands":
    case "groups":
    case "fasion_store":
    case "news_events":
    case "gomez_club":
    case "sale":
      $MM["koszyk"]       = MO_KOSZYK;
      $MM["uzytkownik"]   = MO_MOJEKONTO;
      $MM["zakup"]        = MO_ZAKUP;
      $MM["zamowienie"]   = MO_ZAMOWIENIE;
      $MM['brands']       = MO_BRANDS;
      $MM['fasion_store'] = MO_FASION_STORE;
      $MM['news_events']  = MO_NEWS_EVENTS;
      $MM['gomez_club']   = MO_GOMEZ_CLUB;
      $MM['sale']         = MO_SALE;

      $URL[1] = get_modul_cms($MM[$URL[0]]);
      $URL[0] = "cms";
      $URL[3] = 'modul';
    case "cms":
      include_once("incphp/class_cms.php");
      $URL[2] = $URL[1];
      debug('dfsdf');
      debug($URL);
      $Ccms            = new cms($URL);
      $TPL["{BODYID}"] = "cms" . $Ccms->id;
      break;
    default:
      include_once("incphp/class_default.php");
      $Ccms = new def();
  }
  /* *** */

  // debug('Current GROUP ID: '.$_GET['groups']);


  $main = $Ccms->get_strona();

  $TPL["{LOCAL}"] = $Ccms->get_local();
  $TPL["{TITLE}"] = $Ccms->get_meta($_GLOBAL["page_title"], "title");
  if ($page_desc != '') $_GLOBAL['page_description'] = $page_desc;

  if ($page_desc != '') {
    $page_desc            = str_replace(array("\n", "\r", '&ndash;', '&nbsp;', '&amp;'), array('', '', '-', '', '&'), $page_desc);
    $page_desc            = trim($page_desc);
    $TPL["{DESCRIPTION}"] = $Ccms->get_meta($page_desc, "desconly");
  } else {
    $TPL["{DESCRIPTION}"] = $Ccms->get_meta($_GLOBAL["page_description"], "description");
  }

  $TPL["{KEYWORDS}"] .= $Ccms->get_meta($_GLOBAL["page_keywords"], "keywords");
  $TPL["{TITLE}"] = str_replace('"', '', $Ccms->get_meta($_GLOBAL["page_title"], "title"));

  $co = array('ż', 'ą', 'ę', 'ó', 'ń', 'ś', 'ć', 'ł', 'ź', "\\");
  $na = array('Ż', 'Ą', 'Ę', 'Ó', 'Ń', '&#x015A;', 'Ć', 'Ł', 'Ź', '');

  $_GLOBAL["ntitle"] = str_replace("ANDY WARHOL", "ANDY WARHOL BY PEPE JEANS LONDON", $_GLOBAL["ntitle"]);


  // SEO Tags
  $stqw = array();
  $stqw[] = 'active = 1';
  $stqw[] = 'language_id ' . ((is_null($seo_language_id)) ? 'IS NULL' : '= ' . $seo_language_id);
  $stqw[] = 'brand_id ' . ((is_null($seo_brand_id)) ? 'IS NULL' : '= ' . $seo_brand_id);
  $stqw[] = 'category_id ' . ((is_null($seo_category_id)) ? 'IS NULL' : '= ' . $seo_category_id);
  $stqw[] = 'group_id ' . ((is_null($seo_group_id)) ? 'IS NULL' : '= ' . $seo_group_id);
  $stqw[] = 'product_id ' . ((is_null($seo_product_id)) ? 'IS NULL' : '= ' . $seo_product_id);
  $stqw[] = 'sex ' . ((is_null($seo_sex)) ? 'IS NULL' : '= ' . $seo_sex);
  $stqw[] = 'sale = ' . $seo_sale;

  // title
  $q = 'SELECT * FROM mm_seo_tags WHERE tag = \'title\' AND ' . implode(' AND ', $stqw);
  // debug($q);
  $tag = $db->onerow($q);
  if (is_array($tag)) {
    $_GLOBAL['ntitle'] = mb_convert_encoding(stripslashes($tag['value']), "UTF-8", "ISO-8859-2");
  }

  // keywords
  $q = 'SELECT * FROM mm_seo_tags WHERE tag = \'keywords\' AND ' . implode(' AND ', $stqw);
  // debug($q);
  $tag = $db->onerow($q);
  if (is_array($tag)) {
    $TPL["{KEYWORDS}"] = mb_convert_encoding(stripslashes($tag['value']), "UTF-8", "ISO-8859-2");
  }

  // description
  $q = 'SELECT * FROM mm_seo_tags WHERE tag = \'description\' AND ' . implode(' AND ', $stqw);
  // debug($q);
  $tag = $db->onerow($q);
  if (is_array($tag)) {
    $TPL["{DESCRIPTION}"] = mb_convert_encoding(stripslashes($tag['value']), "UTF-8", "ISO-8859-2");
  }
  // ---

  // if(!empty($_GLOBAL['ntitle'])) $TPL['{TITLE}'] = str_replace($co,$na,$_GLOBAL['ntitle']) . ' :: ' .  $_GLOBAL['title'];
  if (!empty($_GLOBAL['ntitle'])) $TPL['{TITLE}'] = $_GLOBAL['ntitle'] . ' :: ' . $_GLOBAL['title'];

  $TPL['{TITLE}'] = stripslashes($TPL['{TITLE}']);
  $TPL['{DESCRIPTION}'] = stripslashes($TPL['{DESCRIPTION}']);


  // Zakończenie przetwarzania i wyświetlenie wyniku
  include_once("incphp/mb_common_end.php");


?>
