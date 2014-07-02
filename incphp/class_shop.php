<?php
  /**
   *serwer
   * Klasa obsługi prezentacji oferty sklepu
   * @author    Michał Bzowy (niestety)
   * @copyright Copyright (c) 2008, Michał Bzowy
   * @since     2008-08-04 12:36:48
   * @link      www.imt-host.pl
   */

  /*
  Do poprawnego działania konieczne jest utworzenie poniższej perspektywy

  create view mm_sklep_produkt_v as
  select s1.pr_id, s2.pr_nazwa, pr_pd_id, pr_cena_a_brutto, pr_cena_w_brutto,pr_indeks,pr_kolor, pr_etykieta,pr_tabela,
  pr_topsell,pr_nowosc,pr_promocja,pr_polecany,
  pr_data_in, pr_plik, pr_opis, if(isnull(sum(ms_ilosc)),0,sum(ms_ilosc)) pr_stan,pr_sprzedano,pr_odslon,pr_podobne,pr_punkt
  from mm_produkt s1
  left join mm_magazyn_stan on pr_id=ms_pr_id left join mm_sklep_produkt s2 on s1.pr_id=s2.pr_id
  left join mm_sklep_kategoria_produkt on kp_pr_id=s1.pr_id
  where
  pr_widoczny='1' and kp_widoczna='1' and not isnull(ms_ilosc) and ms_ilosc>=0 group by s1.pr_id  order by pr_nazwa,s1.pr_id
  */

  /**
   * Autoryzacja
   */
  if (!defined('SMDESIGN')) {
    die("Hacking attempt");
  }


  class shop extends layout {
    /**
     * Tryb wyświetlania listy produktów
     * @var char l-large,m-medium,s-small
     */
    var $widok_listy = "l";

    /**
     * Produktów na liście na jednej stronie
     * @var integer
     */
    var $ppp = 20;

    /**
     * Kolejność sortowania na liście produktów
     * @var char d-domyśnie, data, cena, nazwa
     */
    var $order = "d";

    var $sex = '';

    var $badIndexProductIDs = array(15616, 8808, 7637, 7638, 7531, 10031, 8895, 12488, 10688, 9531, 8812, 10068, 9105, 10096, 10095, 14146, 10094, 14118, 8432, 986, 1386, 1387, 10689, 10745, 8395, 5281, 14215, 8396, 8418, 8419, 1383, 1385, 1302, 1382, 1381, 1396, 1395, 1391, 1394, 1392, 1393, 1397, 1399, 1398, 1388, 1384, 8401, 8406, 1390, 1389, 1099, 14119, 11847, 13249, 8425, 1176, 8426, 15585, 15587, 15586, 15090, 1191, 1190, 243, 240, 1189, 242, 234, 8434, 2121, 2091, 1931, 2314, 2315, 2120, 8435, 10687, 1178, 1177, 8402, 8398, 13248, 14804, 13992, 13993, 13994, 6027, 13996, 14136, 16222, 1316, 1317, 1318, 16155, 16158, 16164, 1192, 16167, 16165, 16171, 16173, 16174, 16175, 16160, 16161, 16177, 16183, 16184, 2369, 2319, 16187, 16188, 14811, 14801, 14352, 111, 90, 855, 38, 92, 536, 83, 133, 119, 831, 121, 82, 131, 132, 1100, 2321, 2322, 2323, 2320, 2092, 2094, 2093, 2095, 2368, 86, 2375, 146, 148, 150, 153, 573, 310, 561, 13251, 12494, 12495, 14800, 10859, 14810, 9133, 12523, 7783, 8807, 10804, 7779, 10746, 14805, 10084, 10085, 14807, 8140, 11160, 16016, 16018, 16020, 16022, 11868, 2376, 191, 180, 447, 189, 446, 179, 158, 157, 453, 1188, 165, 872, 825, 827, 828, 826, 2367, 2365, 2366, 2364, 2362, 2363, 2361, 12528, 12525, 12526, 14166, 11363, 10087, 14129, 10075, 10076, 10082, 9053, 8796, 12491, 14140, 9135, 15150, 15151, 15152, 14134, 14144, 14145, 10090, 10104, 10099, 10079, 10111, 9049, 9050, 1639, 7261, 1301, 1299, 1638, 1298, 1300, 1297, 1296, 11159, 11371, 11373, 11775, 11777, 8806, 10098, 10109, 8352, 14806, 11376, 9062, 13246, 1755, 8056, 9055, 3347, 8811, 7776, 7777, 14114, 14163, 14351, 11870, 10083, 14124, 14126, 10077, 10078, 13453, 13507, 5964, 5967, 9069, 11781, 14701, 14702, 15374, 12484, 8193, 15158, 15157, 7529, 9068, 13712, 7781, 14538, 115, 14537, 14540, 14542, 16192, 16193, 16194, 14268, 14267, 14270, 14269, 14266, 13716, 16764, 14541, 14821, 14536, 14535, 14539, 10214, 10088, 9104, 5963, 15483, 5966, 8412, 14803, 7532, 7831, 8431, 2096, 231, 230, 228, 216, 232, 286, 12634, 12635, 15769, 10066, 12485, 14802, 1729, 303, 95, 134, 96, 2318, 2317, 2316, 190, 8409, 5969, 12273, 12270, 12858, 12269, 12268, 12263, 12262, 12272, 12266, 12267, 12264, 12271, 12857, 13618, 15307, 13608, 13615, 13616, 15309, 16269, 15807, 13614, 15312, 12862, 12863, 12861, 12461, 12459, 14885, 15591, 13606, 12023, 660, 13613, 8423, 8422, 5978, 10074, 14116, 5977, 7252, 5982, 14115, 9137, 10072, 8397, 7826, 7526);

    /**
     * Konstruktor klasy
     *
     * @param array $ARG Parametry wejściowe
     *
     * @return shop
     */
    function shop($ARG) {
      // debug($ARG);
      $this->db_name     = "sklep_kategoria";
      $this->db_pre      = "ka";
      $this->element_typ = "shop";
      $this->mode        = "prn_kategoria";
      if ($ARG[1] == "produkt") {
        if ($ARG[2] == "szukaj") {
          $this->mode = "prn_szukaj_item";
          $ARG[2]     = 2;
        }

        if ($ARG[2] == "boks") {
          $this->mode          = "prn_boks_item";
          $this->WYNIK["boks"] = $ARG[4];
          $ARG[2]              = 2;
        } else {
          $this->mode = "prn_produkt";
        }
      } elseif ($ARG[1] == "boks") {
        $this->mode          = "prn_boks";
        $this->WYNIK["boks"] = $ARG[2];

        if ($this->WYNIK['boks'] == 'nowosci') {
          $this->sex = $ARG[3];
        }

        $ARG[1] = "shop";
        $ARG[2] = 2;
      }

      if (isset($_GET["o"]) and in_array($_GET["o"], array("d", "cena", "nazwa", "data"))) $_SESSION[SID]["shop"]["o"] = $_GET["o"];
      if (isset($_GET["w"]) and in_array($_GET["w"], array("l", "m", "s"))) $_SESSION[SID]["shop"]["w"] = $_GET["w"];

      if (isset($_GET["ppp"]) and test_int($_GET["ppp"]) and $_GET["ppp"] > -1) $_SESSION[SID]["shop"]["ppp"] = (($_GET["ppp"] == '0') ? 0 : $_GET["ppp"]);

      if ($ARG[2] == $_SESSION[SID]['shop']['category']) {
        if (isset($_GET["brands"])) {
          if (isset($_SESSION[SID]['shop']['brands'][$_GET['brands']])) unset($_SESSION[SID]["shop"]["brands"][$_GET["brands"]]);
          else $_SESSION[SID]["shop"]["brands"][$_GET["brands"]] = $_GET["brands"];
          if ((isset($_SESSION[SID]["shopsz"]['spd'])) && ($_SESSION[SID]["shopsz"]['spd'] == $_GET['brands'])) unset($_SESSION[SID]["shopsz"]['spd']);
        }

        if (isset($_GET["groups"])) {
          if (isset($_SESSION[SID]['shop']['groups'][$_GET['groups']])) unset($_SESSION[SID]["shop"]["groups"][$_GET["groups"]]);
          else $_SESSION[SID]["shop"]["groups"][$_GET["groups"]] = $_GET["groups"];
          if ((isset($_SESSION[SID]["shopsz"]['gp'])) && ($_SESSION[SID]["shopsz"]['gp'] == $_GET['groups'])) unset($_SESSION[SID]["shopsz"]['gp']);
        }

        if (isset($_GET["kolor"])) {
          if (isset($_SESSION[SID]['shop']['kolor'][$_GET['kolor']])) unset($_SESSION[SID]["shop"]["kolor"][$_GET["kolor"]]);
          else $_SESSION[SID]["shop"]["kolor"][$_GET["kolor"]] = $_GET["kolor"];
        }

        if (isset($_GET["size"])) {
          if (isset($_SESSION[SID]['shop']['size'][$_GET['size']])) unset($_SESSION[SID]["shop"]["size"][$_GET["size"]]);
          else $_SESSION[SID]["shop"]["size"][$_GET["size"]] = $_GET["size"];
        }

        if (isset($_GET["faktura"])) {
          if (isset($_SESSION[SID]['shop']['faktura'][$_GET['faktura']])) unset($_SESSION[SID]["shop"]["faktura"][$_GET["faktura"]]);
          else $_SESSION[SID]["shop"]["faktura"][$_GET["faktura"]] = $_GET["faktura"];
        }

        if (isset($_GET["fason"])) {
          if (isset($_SESSION[SID]['shop']['fason'][$_GET['fason']])) unset($_SESSION[SID]["shop"]["fason"][$_GET["fason"]]);
          else $_SESSION[SID]["shop"]["fason"][$_GET["fason"]] = $_GET["fason"];
        }

        if (isset($_GET["cat"])) {
          if (isset($_SESSION[SID]['shop']['cat'])) unset($_SESSION[SID]['shop']['cat']);
          else $_SESSION[SID]['shop']['cat'] = $_GET["cat"];
        }

        if (isset($_GET["subkat"])) {
          if (isset($_SESSION[SID]['shop']['subkat'][$_GET['subkat']])) unset($_SESSION[SID]['shop']['subkat'][$_GET['subkat']]);
          else $_SESSION[SID]['shop']['subkat'][$_GET['subkat']] = $_GET["subkat"];
        }

        if (isset($_GET["price"])) {
          $_SESSION[SID]['shop']['price'] = $_GET["price"];
        }
      }

      $this->order = isset($_SESSION[SID]["shop"]["o"]) ? $_SESSION[SID]["shop"]["o"] : "data";
      $this->ppp   = isset($_SESSION[SID]["shop"]["ppp"]) ? $_SESSION[SID]["shop"]["ppp"] : 20;
      if (isset($_SESSION[SID]["shop"]["w"])) $this->widok_listy = $_SESSION[SID]["shop"]["w"];

      if (isset($_GET["a"]) and $_GET["a"] == "s") {
        unset($_SESSION[SID]["shopsz"]);
        foreach ($_GET as $key => $val)
          if (in_array($key, array("gp", "sna", "sco", "scd", "spd", "sop", "sts", "spo", "sne", "spl", "sro", "ske", "ska", "sale")) and $val != "" and $val != "-1") $_SESSION[SID]["shopsz"][$key] = h(trim($val));
      }

      if ($ARG[1] == "szukaj") {
        if (is_array($_SESSION[SID]["shopsz"]))
          foreach ($_SESSION[SID]["shopsz"] as $key => $val) $this->WYNIK["sz"][$key] = $val;
        $ARG[1]     = "shop";
        $ARG[2]     = 2;
        $this->mode = "prn_szukaj";
      }

      if (isset($_POST['kategoria'])) {
        $this->WYNIK["sz"]["ska"]          = $_POST['kategoria'];
        $_SESSION[SID]['shop']['category'] = $_POST['kategoria'];
      }

      $this->layout($ARG);
    }


    /**
     * Przygotowanie danych wspólnych dla całego obiektu
     */
    function generuj() {
      global $db;

      if ($this->id) {
        $this->WYNIK["fo"] = $db->onerow("select * from " . dn($this->db_name) . " where ka_id='" . $this->id . "'");
        if (!is_array($this->WYNIK["fo"])) $this->error("Brak takiej strony");
        if (!$this->WYNIK["fo"]["ka_widoczna"]) $this->error("Strona niewidoczna");
      } else {
        redirect("/");
      }

      $this->WYNIK["strona"][$this->id] = array(
        "id"           => $this->WYNIK["fo"]["ka_id"],
        "id_id"        => $this->WYNIK["fo"]["ka_ka_id"],
        "widoczna"     => $this->WYNIK["fo"]["ka_widoczna"],
        "tytul"        => $this->WYNIK["fo"]["ka_nazwa"],
        "url"          => "",
        "url_tryb"     => "",
        "baner_powiel" => $this->WYNIK["fo"]["ka_baner_powiel"],
        "baner"        => $this->WYNIK["fo"]["ka_baner"]
      );
      $id                               = $this->id;

      while ($this->WYNIK["strona"][$id]["id_id"] != 0) {
        $this->WYNIK["strona"][$this->WYNIK["strona"][$id]["id_id"]] = $db->onerow("select ka_id id, ka_ka_id id_id,ka_widoczna widoczna,ka_nazwa tytul,'' url,'' url_tryb,ka_boks_powiel boks_powiel, ka_baner_powiel baner_powiel,ka_baner baner from " . dn($this->db_name) . " where ka_id=" . $this->WYNIK["strona"][$id]["id_id"]);
        $id                                                          = $this->WYNIK["strona"][$id]["id_id"];
      }
    }


    /**
     * Podstawienie odpowiedniej treści do wcześniej przygotowanego szablonu
     * @return string
     */
    function get_strona() {
      $TT = $DD = array();
      switch (true) {
        case $this->mode == "prn_kategoria":
        case $this->mode == "prn_szukaj":
        case $this->mode == "prn_boks":
          $TT["{STRONA}"] = $this->get_kategoria();
          break;
        case $this->mode == "prn_produkt":
        case $this->mode == "prn_szukaj_item":
        case $this->mode == "prn_boks_item":
          $TT["{STRONA}"] = $this->get_produkt();
          break;
        case $this->mode == "prn_szukaj_form":
          break;
      }

      return get_template($this->get_strona_tpl(), $TT, $DD, 0);
    }


    /**
     * Czy uzytkownik ma jakies zamowienie z statusem zrealizowane
     *
     * @param $id User ID
     *
     * @return bool True if user has such a order, false otherwise
     */
    private function checkUserFinalizedOrders($id) {
      global $db;

      // wszystkie zamowienia zrealizowane (3) oraz realizowane (2)
      $q   = "SELECT COUNT(za_id) AS count FROM mm_sklep_zamowienie WHERE (za_status = 3 OR za_status = 2) AND za_ko_id = $id";
      $row = $db->onerow($q);
      if ($row['count'] > 0) {
        return true;
      } else {
        return false;
      }
    }


    /**
     * Wyświetla kategorie produktów lub listy wyników wyszukiwania
     * @return string
     */
    function get_kategoria() {
      global $db, $_GLOBAL, $_SESSION, $_GET, $sURL, $Cuzytkownik;

//      debug($_GLOBAL);

      $lj             = '';
      $userID         = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
      $userWithOrders = $this->checkUserFinalizedOrders($userID);
      // jesli przez internet nie kupili, to moze lokalnie...
      if (!$userWithOrders) {
        $userWithOrders = ($Cuzytkownik->is_gomezclub() && ($Cuzytkownik->get_gcsuma() > 0));
      }
      $wh = ($userID > 0) ? (($userWithOrders) ? "(pr_widoczny='1' OR pr_widoczny='2' OR pr_widoczny='3')" : "(pr_widoczny='1' OR pr_widoczny='2')") : "pr_widoczny='1'";

      $leftJoinArray = array();
      $whereArray    = array();

      $TT  = $DD = array();
      $tpl = get_template("shop_category");

      $tmpSizes = "";

      if ($this->mode == "prn_szukaj") {
        debug('SZUKAJ');
        $DD[]         = 'IT_TRESC';
        $DD[]         = 'IT_ELEMENT';
        $whereArray[] = 'sp.pr_stan > 0';
        $whereArray[] = ($userID > 0) ? (($userWithOrders) ? "(sp.pr_widoczny = '1' OR sp.pr_widoczny = '2' OR sp.pr_widoczny = '3')" : "(sp.pr_widoczny = '1' OR sp.pr_widoczny = '2')") : "sp.pr_widoczny = '1'";

        if ($this->WYNIK["sz"]["sna"] != "") {
          $co = array('ą', 'ę', 'ś', 'ć', 'ł', 'ń', 'ó', 'ż', 'ź');
          $na = array('%', '%', '%', '%', '%', '%', '%', '%', '%');

          if (!array_key_exists('mm_sklep_producent', $leftJoinArray))
            $leftJoinArray['mm_sklep_producent'] = 'LEFT JOIN mm_sklep_producent spd ON spd.pd_id = sp.pr_pd_id';

          if (!array_key_exists('mm_product_other_atrybut', $leftJoinArray))
            $leftJoinArray['mm_product_other_atrybut'] = 'LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id';

          $indexPattern = "/(\\d)(\\d)(\\d)(\\d)(-)(\\d)(\\d)(\\d)(\\d)(-)(\\d)(\\d)(\\d)(\\d)/is";
          if (preg_match($indexPattern, trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))))) {
            $indexMatch     = trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"])));
            $indexMatch[10] = '_';
          } else {
            $indexMatch = trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"])));
          }

          $whereArray[] = "   (sp.pr_nazwa like '%" . str_replace($co, $na, a($this->WYNIK["sz"]["sna"])) . "%' OR
                                    spd.pd_nazwa like '%" . str_replace($co, $na, a($this->WYNIK["sz"]["sna"])) . "%' OR
                                    p.pr_indeks like '" . $indexMatch . "%' OR
                                    poa.kolekcja like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' OR
                                    poa.tkanina like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' OR
                                    poa.fason like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' OR
                                    poa.faktura like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' OR
                                    p.pr_polozenie like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' OR
                                    poa.model like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%')";

          $URL[] = "sna=" . $this->WYNIK["sz"]["sna"];
        }

        if ($this->WYNIK["sz"]["sop"] != "") {
          $whereArray[] = "(sp.pr_opis like '%" . a($this->WYNIK["sz"]["sop"]) . "%')";
          $URL[]        = "sop=" . $this->WYNIK["sz"]["sop"];
        }

        if ($this->WYNIK["sz"]["spd"] != "") {
          $_GLOBAL['shop']['brands'][$this->WYNIK["sz"]["spd"]] = $this->WYNIK["sz"]["spd"];
          $URL[]                                                = "spd=" . $this->WYNIK["sz"]["spd"];
        }

        if (($this->WYNIK["sz"]["gp"] != "") OR (isset($_GET['groups']) AND ($_GET['groups'] > 0))) {
          $_GLOBAL['shop']['groups'][$this->WYNIK["sz"]["gp"]] = ($this->WYNIK["sz"]["gp"] != "") ? $this->WYNIK["sz"]["gp"] : $_GET['groups'];
          $URL[]                                               = "gp=" . $this->WYNIK["sz"]["gp"];
        }

        if ($this->WYNIK["sz"]["sco"] != "") {
          $whereArray[] = "(p." . get_cena_w() . " >= '" . a($this->WYNIK["sz"]["sco"]) . "')";
          $URL[]        = "sco=" . $this->WYNIK["sz"]["sco"];
        }

        if ($this->WYNIK["sz"]["scd"] != "") {
          $whereArray[] = "(p." . get_cena_w() . " <= '" . a($this->WYNIK["sz"]["scd"]) . "')";
          $URL[]        = "scd=" . $this->WYNIK["sz"]["scd"];
        }

        if ($this->WYNIK["sz"]["ska"] != "" and test_int($this->WYNIK["sz"]["ska"])) {
          if ((!isset($_GLOBAL['shop']['subkat'])) || (count($_GLOBAL['shop']['subkat']) == 0)) {
            if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
            if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

            $whereArray[] = " (skp.kp_ka_id=" . (int)$this->WYNIK["sz"]["ska"] . " OR skp.kp_ka_id=" . (int)$this->WYNIK["sz"]["ska"] . " ) ";
          }
          $URL[] = "ska=" . $this->WYNIK["sz"]["ska"];
        }

        if ($this->WYNIK["sz"]["sro"] != "" and test_int($this->WYNIK["sz"]["sro"])) {
          $_GLOBAL['shop']['size'][$this->WYNIK["sz"]["sro"]] = $this->WYNIK["sz"]["sro"];
        }

        if (($this->WYNIK["sz"]["sale"] != "") || (isset($_GET['sale']))) {
          $whereArray[] = " p.pr_cena_w_brutto < p.pr_cena_b_brutto ";
          $URL[]        = "sale=1";
        }

        if (($this->WYNIK["sz"]["sale2"] != "") || (isset($_GET['sale2']))) {
          $whereArray[] = " p.pr_cena_w_brutto < (p.pr_cena_b_brutto*55/100) ";
          $URL[]        = "sale=1";
        }


        if ((isset($_GLOBAL['shop']['brands'])) && (!empty($_GLOBAL['shop']['brands'])) && (count($_GLOBAL['shop']['brands']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['brands'] as $k => $v) {
            if ((!empty($v)) && ($v != -1)) {
              $tmp[$v] = $v;

              // getting brand relatives
              $query = "select rpd_id from " . dn('sklep_producent_relatives') . " where pd_id = " . $v;
              $r     = $db->query($query);
              while ($row = $db->fetch($r)) {
                $tmp[$row['rpd_id']] = $row['rpd_id'];
              }
            }
          }
          if (count($tmp) > 0)
            $whereArray[] = ' sp.pr_pd_id in (' . join(',', $tmp) . ')';
        }

        if ((isset($_GLOBAL['shop']['groups'])) && (!empty($_GLOBAL['shop']['groups'])) && (count($_GLOBAL['shop']['groups']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['groups'] as $k => $v) {
            if ((!empty($v)) && ($v != -1)) {
              $tmp[$v] = $v;

              // getting products from group
              $query = "select product_id from " . dn('group_product') . " where group_id = " . $v;
              $r     = $db->query($query);
              while ($row = $db->fetch($r)) {
                $tmp[$row['product_id']] = $row['product_id'];
              }
            }
          }
          if (count($tmp) > 0)
            $whereArray[] = ' sp.pr_id in (' . join(',', $tmp) . ')';
        }

        if ((isset($_GLOBAL['shop']['kolor'])) && (!empty($_GLOBAL['shop']['kolor'])) && (count($_GLOBAL['shop']['kolor']) > 0)) {
          $tmp = array();

          if (!array_key_exists('mm_color_product', $leftJoinArray))
            $leftJoinArray['mm_color_product'] = 'LEFT JOIN mm_color_product cp ON cp.product = sp.pr_id';

          foreach ($_GLOBAL['shop']['kolor'] as $k => $v) {
            $tmp2[] = "cp.color = '" . $v . "' ";
          }

          $whereArray[] = " (" . join(' OR ', $tmp2) . ") ";
        }

        if ((isset($_GLOBAL['shop']['size'])) && (!empty($_GLOBAL['shop']['size'])) && (count($_GLOBAL['shop']['size']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['size'] as $k => $v) $tmp[$v] = $v;
          if (count($tmp) > 0) {
            $tmpSizes = ' ms_at_id IN (' . join(',', $tmp) . ') AND ';
          }
        }

        if ((isset($_GLOBAL['shop']['faktura'])) && (!empty($_GLOBAL['shop']['faktura'])) && (count($_GLOBAL['shop']['faktura']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['faktura'] as $k => $v) $tmp[$v] = $v;
          if (count($tmp) > 0) {
            if (!array_key_exists('mm_product_other_atrybut', $leftJoinArray))
              $leftJoinArray['mm_product_other_atrybut'] = 'LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id';

            $whereArray[] = ' poa.faktura IN (' . join(',', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['fason'])) && (!empty($_GLOBAL['shop']['fason'])) && (count($_GLOBAL['shop']['fason']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['fason'] as $k => $v) $tmp[$v] = $v;
          if (count($tmp) > 0) {
            if (!array_key_exists('mm_product_other_atrybut', $leftJoinArray))
              $leftJoinArray['mm_product_other_atrybut'] = 'LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id';

            $whereArray[] = ' fason IN (' . join(',', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['price'])) && (!empty($_GLOBAL['shop']['price']))) {
          $tmp          = array();
          $tmp          = explode(';', $_GLOBAL['shop']['price']);
          $whereArray[] = ' (p.pr_cena_w_brutto>= ' . $tmp[0] . ' AND p.pr_cena_w_brutto<=' . $tmp[1] . ') ';
        }

        if ((isset($_GLOBAL['shop']['subkat'])) && (!empty($_GLOBAL['shop']['subkat'])) && (count($_GLOBAL['shop']['subkat']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['subkat'] as $k => $v) $tmp[$v] = $v;
          if (count($tmp) > 0) {
            if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
            if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

            $whereArray[] = ' (sk.ka_id = ' . join(' OR sk.ka_id=', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['brn']['subkat'])) && (!empty($_GLOBAL['shop']['brn']['subkat'])) && (count($_GLOBAL['shop']['brn']['subkat']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['brn']['subkat'] as $k => $v) $tmp[$v] = $v;
          if (count($tmp) > 0) {
            if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
            if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

            $whereArray[] = ' (sk.ka_id = ' . join(' OR sk.ka_id=', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['category'])) &&
          (!empty($_GLOBAL['shop']['category'])) &&
          ((!isset($_GLOBAL['shop']['subkat'])) || (count($_GLOBAL['shop']['subkat']) == 0))
        ) {
          if ($_GLOBAL['shop']['category'] != -1)
            if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
          if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
            $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

          $whereArray[] = " (skp.kp_ka_id=" . $_GLOBAL['shop']['category'] . " OR sk.ka_ka_id=" . $_GLOBAL['shop']['category'] . ") ";
        }

        $prodIDs = array();
        if ($_GLOBAL['langid'] != 1) {
          $q = "  SELECT DISTINCT
                            sp.pr_id, p.pr_indeks, sp.pr_stan, sp.pr_widoczny
                        FROM
                            mm_sklep_produkt sp
                        LEFT JOIN mm_produkt p ON sp.pr_id = p.pr_id " . implode(' ', $leftJoinArray) . "
                        LEFT JOIN mm_sklep_product_translation spt ON sp.pr_id = spt.pr_id
                        WHERE
                        " . implode(' AND ', $whereArray) . ' AND (spt.langid=' . $_GLOBAL['langid'] . ' AND spt.name = \'nazwa\' AND spt.description <> \'\')';
        } else {
          $q = "  SELECT DISTINCT
                            sp.pr_id, p.pr_indeks, sp.pr_stan, sp.pr_widoczny
                        FROM
                            mm_sklep_produkt sp
                        LEFT JOIN mm_produkt p ON sp.pr_id = p.pr_id " . implode(' ', $leftJoinArray) . "
                        WHERE
                        " . implode(' AND ', $whereArray);
        }

//        debug('wyszukiwnie:');
//        debug($q);
        $r = $db->get_all($q);
        foreach ($r as $value) {
          // sprawdzenie dla metaproduktow
          $tmpID        = $value['pr_id'];
          $tmpIndex     = $value['pr_indeks'];
          $tmpIndex[10] = '_';
          $query        = '  SELECT ms_at_id, at_nazwa
                            FROM ' . dn("magazyn_stan") . '
                            LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                            LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                            WHERE
                                ' . $tmpSizes . '
                                ms_ilosc > 0 AND
                                pr_indeks LIKE \'' . $tmpIndex . '\' AND
                                ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                                at_nazwa != \'Pusty\'
                            ORDER BY at_nazwa';
          $cr           = $db->get_all($query);
          if (count($cr) > 0) {
            $prodIDs[] = $tmpID;
          }
        }
        // ---

        $tmp = array();
        if ($sURL[0] == 'wyszukiwarka') {
          if (count($_GET) > 0) {
            foreach ($_GET as $key => $value) {
              if ($key == 'p')
                continue;

              if (is_array($value)) {
                if (count($value) > 0) {
                  foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                }
              } else {
                $tmp[] = $key . '=' . $value;
              }
            }
          }
          $url = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '&p=';
        } else {
          for ($i = 0; $i < count($sURL); $i++) {
            if (!is_numeric($sURL[$i])) $tmp[] = $sURL[$i];
          }

          $url = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . join('/', $tmp) . '/';
          if (($sURL[0] == 'sale') && ($sURL[1] == '70')) {
            $url .= '70/';
          }
        }

        $Cnawi = new nawigacja(count($prodIDs), $this->ppp, $url);

        $whereCond = (count($prodIDs) > 0) ? 'WHERE sp.pr_id IN (' . implode(' , ', $prodIDs) . ')' : 'WHERE sp.pr_id IN (-1)';
        $q         = '  SELECT
                        sp.pr_id,
                        sp.pr_nazwa,
                        p.pr_plik,
                        sp.pr_punkt,
                        p.pr_cena_a_brutto,
                        p.pr_cena_w_brutto,
                        sp.pr_etykieta,
                        spd.pd_nazwa,
                        sk.ka_nazwa,
                        IF ((p.pr_cena_a_brutto - p.pr_cena_w_brutto)>0,1,0) AS upust
                    FROM mm_sklep_produkt sp
                    LEFT JOIN mm_produkt p ON p.pr_id = sp.pr_id
                    LEFT JOIN mm_sklep_producent spd ON spd.pd_id = sp.pr_pd_id
                    LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = sp.pr_id
                    LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id
                    ' . $whereCond . '
                    GROUP BY sp.pr_id
                    ORDER BY ';

        switch ($this->order) {
          case "data":
            $q .= "upust asc, sp.pr_image_upload_timestamp desc, sp.pr_id desc ";
//                    $q .= "sp.pr_etykieta asc, sp.pr_image_upload_timestamp desc, sp.pr_id desc ";
//                    $q .= "upust asc, sp.pr_id desc ";
            break;
          case "cena":
            $q .= "p.pr_cena_w_brutto ";
            break;
          case "nazwa":
            $q .= pl_order("sp.pr_nazwa") . " ";
            break;
          default:
            $q .= "upust asc, sp.pr_image_upload_timestamp desc, sp.pr_id desc ";
//                    $q .= "upust asc, sp.pr_id desc ";
            break;
        }
        if ($this->ppp != 0) {
          $q .= "LIMIT " . $Cnawi->get_min() . "," . $Cnawi->get_ppp();
        }
//        debug($q);

        $url                  = "sklep,szukaj";
        $TT["{URL}"]          = $url . ".htm?";
        $this->WYNIK["tytul"] = $TT["{NAZWA}"] = $this->WYNIK["sz"]["sale"] != "" ? "sale" : "{T_SZUKAJ_WYNIK}";

      } elseif ($this->mode == "prn_boks") {
        $DD[] = 'IT_TRESC';
        $DD[] = 'IT_ELEMENT';
        $DD[] = 'IT_NAWI_D';
        $DD[] = 'IT_NAWI_G';

        if ((isset($_GLOBAL['shop']['brands'])) && (!empty($_GLOBAL['shop']['brands'])) && (count($_GLOBAL['shop']['brands']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['brands'] as $k => $v) {
            if ((!empty($v)) && ($v != -1)) {
              $tmp[$v] = $v;

              // getting brand relatives
              $query = "select rpd_id from " . dn('sklep_producent_relatives') . " where pd_id = " . $v;
              $r     = $db->query($query);
              while ($row = $db->fetch($r)) {
                $tmp[$row['rpd_id']] = $row['rpd_id'];
              }
            }
          }
          if (count($tmp) > 0) $Q[] = ' sp.pr_pd_id in (' . join(',', $tmp) . ')';
        }

        if ((isset($_GLOBAL['shop']['groups'])) && (!empty($_GLOBAL['shop']['groups'])) && (count($_GLOBAL['shop']['groups']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['groups'] as $k => $v) {
            if ((!empty($v)) && ($v != -1)) {
              $tmp[$v] = $v;

              // getting products from group
              $query = "select product_id from " . dn('group_product') . " where group_id = " . $v;
              $r     = $db->query($query);
              while ($row = $db->fetch($r)) {
                $tmp[$row['product_id']] = $row['product_id'];
              }
            }
          }
          if (count($tmp) > 0)
            $Q[] = ' sp.pr_id in (' . join(',', $tmp) . ')';
        }

        if ((isset($_GLOBAL['shop']['kolor'])) && (!empty($_GLOBAL['shop']['kolor'])) && (count($_GLOBAL['shop']['kolor']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['kolor'] as $k => $v) {
            $tmp[]  = "pr_kolor='" . $v . "' ";
            $tmp2[] = "color='" . $v . "' ";
          }
          $Q[] = " (" . join(' or ', $tmp) . " or " . join(' or ', $tmp2) . ")";
        }

        if ((isset($_GLOBAL['shop']['size'])) && (!empty($_GLOBAL['shop']['size'])) && (count($_GLOBAL['shop']['size']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['size'] as $k => $v) {
            $tmp[$v] = $v;
          }

          if (count($tmp) > 0) {
            $Q[] = ' ms_at_id in (' . join(',', $tmp) . ')';
          }
        }

        if ((isset($_GLOBAL['shop']['faktura'])) && (!empty($_GLOBAL['shop']['faktura'])) && (count($_GLOBAL['shop']['faktura']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['faktura'] as $k => $v) {
            $tmp[$v] = $v;
          }

          if (count($tmp) > 0) {
            $Q[] = ' faktura in (' . join(',', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['fason'])) && (!empty($_GLOBAL['shop']['fason'])) && (count($_GLOBAL['shop']['fason']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['fason'] as $k => $v) {
            $tmp[$v] = $v;
          }
          if (count($tmp) > 0) {
            $Q[] = ' fason in (' . join(',', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['subkat'])) && (!empty($_GLOBAL['shop']['subkat'])) && (count($_GLOBAL['shop']['subkat']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['subkat'] as $k => $v) {
            $tmp[$v] = $v;
          }

          if (count($tmp) > 0) {
            $Q[] = ' (ka_ka_id in (' . join(',', $tmp) . ') or ka_id in (' . join(',', $tmp) . ')) ';
          }
        }

        if ((isset($_GLOBAL['shop']['price'])) && (!empty($_GLOBAL['shop']['price']))) {
          $tmp = array();
          $tmp = explode(';', $_GLOBAL['shop']['price']);
          $Q[] = ' (pr_cena_w_brutto>= ' . $tmp[0] . ' and pr_cena_w_brutto<=' . $tmp[1] . ') ';
        }


        $q = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,"; //,pd_id,pr_kolor,pd_plik,kp_ka_id,faktura,fason
        $q .= get_cena_w() . " pr_cena_w_brutto,pd_nazwa, ka_nazwa
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                    " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('magazyn_stan') . " " . $lj;
        $q .= "where pr.pr_id=p1.pr_id ";
        $q .= "and pr.pr_id=kp_pr_id ";
        $q .= "and pr.pr_id=" . dn('magazyn_stan') . ".ms_pr_id ";
        $q .= "and " . dn('magazyn_stan') . ".ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
        $q .= "and ms_ilosc>0 ";
        $q .= "and id=pr.pr_id ";
        $q .= "and ka_id=kp_ka_id ";
        $q .= "and pr_pd_id=pd_id "; // . $lj;
        $q .= " and pr_stan>0 and " . $wh . " and kp_widoczna='1' ";
        if (count($Q) > 0) $q .= ' and ' . join(' and ', $Q) . ' ';

        switch ($this->WYNIK["boks"]) {
          case "nowosci":
            $q .= " and pr.pr_etykieta ='1' group by kp_pr_id order by pr.pr_id desc "; //pr_nowosc desc,
            break;
          case "topsell":
            $q .= "group by kp_pr_id order by pr_topsell desc, pr_sprzedano desc, pr.pr_id desc ";
            break;
          case "polecamy":
            $q .= "and pr_polecany='1' ";
            $q .= "group by kp_pr_id order by pr.pr_id desc ";
            break;
          case "promocje":
            $q .= "and pr_promocja='1' ";
            $q .= "group by kp_pr_id order by pr.pr_id desc ";
            break;
          case "upominek":
            $q .= "and pr_punkt>0 ";
            $q .= "group by kp_pr_id order by pr.pr_id desc ";
            break;
          case "sale": //$q .= "and (pr_cena_b_brutto*0.7)>=pr_cena_w_brutto ";
            $q .= "and pr_cena_b_brutto > pr_cena_w_brutto ";
            $q .= "group by kp_pr_id order by pr.pr_id desc ";
            break;
        }

        $q .= "limit 20";

        $this->widok_listy = "l";
        $Cnawi             = new nawigacja(1, 1);

        $url                  = "sklep," . $this->WYNIK["boks"];
        $TT["{URL}"]          = $url . ".htm?";
        $this->WYNIK["tytul"] = $TT["{NAZWA}"] = '{T_B_' . strtoupper($this->WYNIK["boks"]) . '}';
      } else {
        $url         = "sklep," . $this->id;
        $TT["{URL}"] = $url . ".htm?";

        $whereArray[] = 'sp.pr_stan > 0';
        $whereArray[] = ($userID > 0) ? (($userWithOrders) ? "(sp.pr_widoczny = '1' OR sp.pr_widoczny = '2' OR sp.pr_widoczny = '3')" : "(sp.pr_widoczny = '1' OR sp.pr_widoczny = '2')") : "sp.pr_widoczny = '1'";

        if ((isset($_GLOBAL['shop']['cat'])) && (!empty($_GLOBAL['shop']['cat']))) {
          if ((isset($_GLOBAL['shop']['subkat'])) && (!empty($_GLOBAL['shop']['subkat'])) && (count($_GLOBAL['shop']['subkat']) > 0)) {
            $tmp = array();
            foreach ($_GLOBAL['shop']['subkat'] as $k => $v) $tmp[$v] = $v;
            if (count($tmp) > 0) {
              if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
              if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

              $whereArray[] = ' skp.kp_widoczna = \'1\'';
              $whereArray[] = ' sk.ka_ka_id IN (' . join(',', $tmp) . ') ';
            }
          } else {
            if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
            if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
              $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

            $whereArray[] = ' skp.kp_widoczna = \'1\'';
            $whereArray[] = " (sk.ka_id = " . $_GLOBAL['shop']['cat'] . " or sk.ka_ka_id = " . $_GLOBAL['shop']['cat'] . ")";

            $this->id = $_GLOBAL['shop']['cat'];
          }
        } else {
          if ((isset($_GLOBAL['shop']['subkat'])) && (!empty($_GLOBAL['shop']['subkat'])) && (count($_GLOBAL['shop']['subkat']) > 0)) {
            $tmp = array();
            foreach ($_GLOBAL['shop']['subkat'] as $k => $v) {
              $tmp[$v] = $v;
            }

            if (count(array_filter($tmp)) > 0) {
              if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
              if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

              $whereArray[] = ' skp.kp_widoczna = \'1\'';
              debug(join(',', $tmp));
              if (count(array_filter($tmp)) > 0) {
                  $whereArray[] = ' sk.ka_id IN (' . join(',', $tmp) . ') ';
              }
            }
          } else {
            if ($this->id != 121 and $this->id != 122 and $this->id != 219 and $this->id != 220) {
              if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
              if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

              $whereArray[] = ' skp.kp_widoczna = \'1\'';
              $whereArray[] = " (skp.kp_ka_id = " . $this->id . " OR sk.ka_ka_id = " . $this->id . ") ";
            } else {
              if (!array_key_exists('mm_sklep_kategoria_produkt', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria_produkt'] = 'LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = p.pr_id';
              if (!array_key_exists('mm_sklep_kategoria', $leftJoinArray))
                $leftJoinArray['mm_sklep_kategoria'] = 'LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id';

              $whereArray[] = ' skp.kp_widoczna = \'1\'';
              $whereArray[] = " (skp.kp_ka_id = " . $this->id . ") ";
            }
          }
        }

        if ((isset($_GLOBAL['shop']['brands'])) && (!empty($_GLOBAL['shop']['brands'])) && (count($_GLOBAL['shop']['brands']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['brands'] as $k => $v) {
            if ((!empty($v)) && ($v != -1)) {
              $tmp[$v] = $v;

              // getting brand relatives
              $query = "select rpd_id from " . dn('sklep_producent_relatives') . " where pd_id = " . $v;
              $r     = $db->query($query);
              while ($row = $db->fetch($r)) {
                $tmp[$row['rpd_id']] = $row['rpd_id'];
              }
            }
          }

          if (count($tmp) > 0) {
            $whereArray[] = ' sp.pr_pd_id in (' . join(',', $tmp) . ')';
          }
        }

        if ((isset($_GLOBAL['shop']['groups'])) && (!empty($_GLOBAL['shop']['groups'])) && (count($_GLOBAL['shop']['groups']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['groups'] as $k => $v) {
            if ((!empty($v)) && ($v != -1)) {
              $tmp[$v] = $v;

              // getting products from group
              $query = "select product_id from " . dn('group_product') . " where group_id = " . $v;
              $r     = $db->query($query);
              while ($row = $db->fetch($r)) {
                $tmp[$row['product_id']] = $row['product_id'];
              }
            }
          }
          if (count($tmp) > 0) {
            $whereArray[] = ' sp.pr_id in (' . join(',', $tmp) . ')';
          }
        }

        if ((isset($_GLOBAL['shop']['kolor'])) && (!empty($_GLOBAL['shop']['kolor'])) && (count($_GLOBAL['shop']['kolor']) > 0)) {
          $tmp = array();

          if (!array_key_exists('mm_color_product', $leftJoinArray))
            $leftJoinArray['mm_color_product'] = 'LEFT JOIN mm_color_product cp ON cp.product = sp.pr_id';

          foreach ($_GLOBAL['shop']['kolor'] as $k => $v) {
            $tmp[]  = "p.pr_kolor = '" . $v . "' ";
            $tmp2[] = "cp.color = '" . $v . "' ";
          }

          $whereArray[] = " (" . join(' OR ', $tmp) . " OR " . join(' OR ', $tmp2) . ")";
        }

        $tmpSizes = "";
        if ((isset($_GLOBAL['shop']['size'])) && (!empty($_GLOBAL['shop']['size'])) && (count($_GLOBAL['shop']['size']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['size'] as $k => $v) {
            $tmp[$v] = $v;
          }

          if (count($tmp) > 0) {
            $tmpSizes = ' ms_at_id IN (' . join(',', $tmp) . ') AND ';
          }
        }

        if ((isset($_GLOBAL['shop']['faktura'])) && (!empty($_GLOBAL['shop']['faktura'])) && (count($_GLOBAL['shop']['faktura']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['faktura'] as $k => $v) {
            $tmp[$v] = $v;
          }

          if (count($tmp) > 0) {
            if (!array_key_exists('mm_product_other_atrybut', $leftJoinArray))
              $leftJoinArray['mm_product_other_atrybut'] = 'LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id';

            $whereArray[] = ' poa.faktura IN (' . join(',', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['fason'])) && (!empty($_GLOBAL['shop']['fason'])) && (count($_GLOBAL['shop']['fason']) > 0)) {
          $tmp = array();
          foreach ($_GLOBAL['shop']['fason'] as $k => $v) {
            $tmp[$v] = $v;
          }

          if (count($tmp) > 0) {
            if (!array_key_exists('mm_product_other_atrybut', $leftJoinArray))
              $leftJoinArray['mm_product_other_atrybut'] = 'LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id';

            $whereArray[] = ' fason IN (' . join(',', $tmp) . ') ';
          }
        }

        if ((isset($_GLOBAL['shop']['price'])) && (!empty($_GLOBAL['shop']['price']))) {
          $tmp = array();
          $tmp = explode(';', $_GLOBAL['shop']['price']);

          // new queries
          $whereArray[] = ' (p.pr_cena_w_brutto>= ' . $tmp[0] . ' AND p.pr_cena_w_brutto<=' . $tmp[1] . ') ';
          // ---
        }


        $prodIDs = array();
        if ($_GLOBAL['langid'] != 1) {
          $q = "  SELECT DISTINCT
                            sp.pr_id, p.pr_indeks
                        FROM
                            mm_sklep_produkt sp
                        LEFT JOIN mm_produkt p ON sp.pr_id = p.pr_id " . implode(' ', $leftJoinArray) . "
                        LEFT JOIN mm_sklep_product_translation spt ON sp.pr_id = spt.pr_id
                        WHERE
                        " . implode(' AND ', $whereArray) . ' AND (spt.langid=' . $_GLOBAL['langid'] . ' AND spt.name = \'nazwa\' AND spt.description <> \'\')';
        } else {
          $q = "  SELECT DISTINCT
                            sp.pr_id, p.pr_indeks
                        FROM
                            mm_sklep_produkt sp
                        LEFT JOIN mm_produkt p ON sp.pr_id = p.pr_id " . implode(' ', $leftJoinArray) . "
                        WHERE
                        " . implode(' AND ', $whereArray);
        }

        // sprawdzenie dla metaproduktow
        $r = $db->get_all($q);
        foreach ($r as $value) {
          $tmpID        = $value['pr_id'];
          $tmpIndex     = $value['pr_indeks'];
          $tmpIndex[10] = '_';
          $query        = '  SELECT ms_at_id, at_nazwa
                            FROM ' . dn("magazyn_stan") . '
                            LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                            LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                            WHERE
                                ' . $tmpSizes . '
                                ms_ilosc > 0 AND
                                pr_indeks LIKE \'' . $tmpIndex . '\' AND
                                ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                                at_nazwa != \'Pusty\'
                            ORDER BY at_nazwa';
          $cr           = $db->get_all($query);
          if (count($cr) > 0) {
            $prodIDs[] = $tmpID;
          }
        }
        // ---
        $T[0] = count($prodIDs);

        $tmp = array();

        if ($sURL[0] == 'wyszukiwarka') {
          $url = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . join('/', $_GET);
        } else {
          for ($i = 0; $i < count($sURL); $i++) {
            if (!is_numeric($sURL[$i])) {
              $tmp[] = $sURL[$i];
            }
          }
          $url = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . join('/', $tmp) . '/';
        }

        $Cnawi = new nawigacja(count($prodIDs), $this->ppp, $url);

        $whereCond = (count($prodIDs) > 0) ? 'WHERE sp.pr_id IN (' . implode(' , ', $prodIDs) . ')' : 'WHERE sp.pr_id IN (-1)';
        $q         = '  SELECT
                        sp.pr_id,
                        sp.pr_nazwa,
                        p.pr_plik,
                        sp.pr_punkt,
                        p.pr_cena_a_brutto,
                        p.pr_cena_w_brutto,
                        sp.pr_etykieta,
                        spd.pd_nazwa,
                        sk.ka_nazwa,
                        IF ((p.pr_cena_a_brutto - p.pr_cena_w_brutto)>0,1,0) AS upust
                    FROM mm_sklep_produkt sp
                    LEFT JOIN mm_produkt p ON p.pr_id = sp.pr_id
                    LEFT JOIN mm_sklep_producent spd ON spd.pd_id = sp.pr_pd_id
                    LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = sp.pr_id
                    LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id
                    ' . $whereCond . '
                    GROUP BY sp.pr_id
                    ORDER BY ';

        switch ($this->order) {
          case "data":
            $q .= "upust asc, sp.pr_image_upload_timestamp desc, sp.pr_id desc ";
            break;
          case "cena":
            $q .= "p.pr_cena_w_brutto ";
            break;
          case "nazwa":
            $q .= pl_order("sp.pr_nazwa") . " ";
            break;
          default:
            $q .= "upust asc, sp.pr_image_upload_timestamp desc, sp.pr_id desc ";
            break;
        }


        if ($this->ppp != 0) {
          $q .= "LIMIT " . $Cnawi->get_min() . "," . $Cnawi->get_ppp();
        }

        if ($this->WYNIK["fo"]["ka_opis"] != "") {
          //$TT["{TRESC}"] = s($this->WYNIK["fo"]["ka_opis"]);
          //$TT['{BANERCATEGORYBRANDS}'] = s($this->WYNIK["fo"]["ka_opis"]);
        } else {
          $DD[] = 'IT_TRESC';
        }

        $this->WYNIK["tytul"] = $TT["{NAZWA}"] = hs($this->WYNIK["fo"]["ka_nazwa"]);

        $DD[]   = 'IT_BRAK';
        $button = false;

        if ($this->WYNIK["fo"]["ka_button"] != "") {
          for ($i = 1; $i <= 6; $i++) {
            $EL[$i] = "";
            $tpl    = get_tag($tpl, "IT_EL_" . $i, $EL[$i]);
          }

          $TB = explode("|^|", $this->WYNIK["fo"]["ka_button"]);
          $TP = explode("|=|", $TB[1]);
          foreach ($TP as $v) {
            if ($v != "") {
              $TPA    = explode("|", $v);
              $LINK[] = array($TPA[0], $TPA[1]);
            }
          }

          $TBA = explode("|", $TB[0]);
          $lnk = 0;
          foreach ($TBA as $ile) {
            for ($k = 1; $k <= $ile; $k++) {
              if (isset($LINK[$lnk])) {
                $C["{SRC" . $k . "}"] = '<img src="' . $LINK[$lnk][0] . '" alt="" />';
                $C["{URL" . $k . "}"] = $LINK[$lnk++][1];
              } else {
                $C["{SRC" . $k . "}"] = ' ';
                $C["{URL" . $k . "}"] = "#";
                $lnk++;
              }
            }
            $TT["{ELEMENT}"] .= get_template($EL[$ile], $C, '', 0);
          }
          $button = true;
        }

        if (!$button) {
          $DD[] = "IT_ELEMENT";
        }

        if ($this->WYNIK["fo"]["ka_ka_id"] == 0) {
          $DD[] = "IT_TYTUL";
          $DD[] = "IT_LOCAL";
        }
      }

      //odtąd jest generowana lista produktów
      $this->widok_listy = "l";
      $nr                = $db->query($q);
      $ile_wiersz        = 0;
      $wiersz            = "";
      $A                 = array();

      if ($db->affected_rows()) {
        $DD["s"] = "IT_PRODUKT_S";
        $DD["m"] = "IT_PRODUKT_M";
        $DD["l"] = "IT_PRODUKT_L";

        $tpl = get_tag($tpl, "PRODUKT_" . strtoupper($this->widok_listy), $ile_wiersz);

        $tpl    = get_tag($tpl, "IT_PRODUKT_" . strtoupper($this->widok_listy), $wiersz);
        $tpl_pr = get_template("shop_product_" . $this->widok_listy);

        unset($DD[$this->widok_listy]);
        $DD[]   = 'IT_BRAK';
        $i      = 0;
        $waluta = $this->WYNIK["boks"] == "upominek" ? "pkt." : "zł";
        $waluta = $this->WYNIK["boks"] == "upominek" ? "pkt." : "zł";
        while ($T = $db->fetch($nr)) {
          if ($i and $i % $ile_wiersz == 0) {
            $TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"] .= chr(10) . get_template($wiersz, $A, '', 0);
            $A = array();
          }
          $i++;
          $B = $DB = array();

          //nazwa produktu
          $prdDesc = array();
          if ((int)$_GLOBAL['langid'] != 1) {
            $query = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $T['pr_id'] . " and langid=" . (int)$_GLOBAL['langid'] . ";";
            $res   = $db->query($query);
            while ($item = $db->fetch($res)) {
              $prdDesc[$item['name']] = $item['description'];
            }
          }

          if ((int)$_GLOBAL['langid'] != 1) {
            if (!empty($prdDesc['nazwa'])) {
              $B["{NAZWA}"] = s($prdDesc['nazwa']);
            } else {
              $B['{NAZWA}'] = s($T["pr_nazwa"]);
            }
          } else {
            $B["{NAZWA}"] = s($T["pr_nazwa"]);
          }


          $B["{ALT}"] = h($T["pr_nazwa"]);

          if ($T["pr_plik"] == "") {
            $plik = "files/product/images/" . ceil($T["pr_id"] / 500) . "/m/" . $T["pr_id"] . "_0.jpg";
            if (file_exists($plik)) {
              $T["pr_plik"] = $plik;
            }
          }

          $T['pr_plik'] = str_replace('http:////', '', $T['pr_plik']);
          // $PR['pr_plik'] = str_replace('http://gomez.pl//', '', $PR['pr_plik']);
          if (substr($T["pr_plik"], 0, 8) == "http:///") {
            $T["pr_plik"] = substr($T["pr_plik"], 8);
          }

          if (substr($T["pr_plik"], 0, 4) == "http") {
            $T["pr_plik"] = substr($T["pr_plik"], 8);
            $T["pr_plik"] = substr($T["pr_plik"], strpos($T["pr_plik"], "/") + 1);
          }

          $T["pr_plik"] = str_replace("/x/", "/m/", $T["pr_plik"]);
          if (substr($T['pr_plik'], 0, 1) == '/') {
            $T['pr_plik'] = substr($T['pr_plik'], 1);
          }

          $B["{IMG}"] = ($T["pr_plik"] != "" and file_exists($T["pr_plik"])) ? str_replace("/m/", "/" . $this->widok_listy . "/", $T["pr_plik"]) : 'images/nofoto_m.jpg'; //'images/nofoto_'.$this->widok_listy.'.png';

          $B['{IMG}'] = $_GLOBAL['page_url'] . str_replace("/l/", "/m/", $B['{IMG}']);

          if ($this->WYNIK["boks"] == "upominek") {
            $B["{CENA}"] = $T["pr_punkt"];
          } else {
            $B["{CENA}"] = ($T["pr_cena_w_brutto"] < $T["pr_cena_a_brutto"] ? '<span class="sale">' . number_format($T["pr_cena_a_brutto"], 2, ".", "") . ' ' . $waluta . '</span> ' . number_format($T["pr_cena_w_brutto"], 2, ".", "") : number_format($T["pr_cena_w_brutto"], 2, ".", ""));
          }

          $B["{PRODUCENT}"] = s($T["pd_nazwa"]);

          $B["{WALUTA}"] = $waluta;

          // link do produktu
          $B['{URL}'] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/produkt/' . $T['pr_id'] . '/' . conv2file($T["pd_nazwa"]) . '/' . conv2file($T['pr_nazwa']) . '/';
          if ($T["pr_etykieta"] != "") {
            $X = explode("|", $T["pr_etykieta"]);
            if (test_int($X[0])) {
              $B["{ET_PLIK}"] = "{ET_PLIK_" . $X[0] . "}";
              $B["{ET_TXT}"]  = "{ET_TXT_" . $X[0] . "}";
              $ET[$X[0]]      = $X[0];
            } else {
              $DB[] = "IT_ETYKIETA";
            }
          } else {
            $DB[] = "IT_ETYKIETA";
          }


          // KIDS
          $kids_query  = 'SELECT kp_ka_id FROM mm_sklep_kategoria_produkt WHERE (kp_ka_id = 277 OR kp_ka_id = 308) AND kp_pr_id = ' . $T['pr_id'];
          $kids_result = $db->get_all($kids_query);

          if (($sURL[0] == 'kids') OR (count($kids_result) > 0)) {
            $B["{AGE_CLASS}"] = 'kids';
          } else {
            $B["{AGE_CLASS}"] = 'adults';
          }


          //ROZMIARY
          $ORDER  = array();
          $TABROZ = array();
          $TABT   = array();

          // getting amounts for meta-product
          $product   = $db->onerow('SELECT pr_indeks FROM mm_produkt WHERE pr_id = ' . $T["pr_id"]);
          $index     = $product['pr_indeks'];
          $index[10] = '%';
          $query     = '  SELECT ms_at_id, at_nazwa
                            FROM ' . dn("magazyn_stan") . '
                            LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                            LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                            WHERE
                                ms_ilosc > 0 AND
                                pr_indeks LIKE \'' . $index . '\' AND
                                ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                                at_nazwa != \'Pusty\'
                            ORDER BY at_nazwa';
          // ---

          $nrr = $db->query($query);
          while ($Tr = $db->fetch($nrr)) {
            if (!empty($Tr[1])) {
              if (is_numeric(substr($Tr[1], 0, 1))) {
                $ORDER[0][$Tr[0]] = $Tr[1];
              } else {
                $ORDER[1][$Tr[0]] = $Tr[1];
              }
            }
          }

          if (is_array($ORDER[0])) {
            asort($ORDER[0]);
          }

          $tmp = array();
          $ROZ = array("XXS", "XS", "S", "M", "L", "XL", "XXL", "XXXL", "XXXXL");
          foreach ($ROZ as $key => $value) {
            if (in_array($value, (array)$ORDER[1])) {
              $klucz = '';
              $klucz = array_search($value, $ORDER[1]);
              $tmp[] = $value;
            }
          }

          if (count($ORDER[1]) > 0) {
            foreach ($ORDER[1] as $key => $value) {
              if (!in_array($value, (array)$ROZ)) {
                $tmp[] = $value;
              }
            }
          }

          $B['{ROZMIARY}'] .= join(', ', $tmp);
          if ((count($ORDER[1]) > 0) && (count($ORDER[0]) > 0)) $B['{ROZMIARY}'] .= ', ';
          if (count($ORDER[0]) > 0) $B['{ROZMIARY}'] .= join(', ', $ORDER[0]);
          // rozmiary - koniec

          $A["{PRODUKT_" . strtoupper($this->widok_listy) . "}"] .= chr(10) . get_template($tpl_pr, $B, $DB, 0);
        }

        $TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"] .= chr(10) . get_template($wiersz, $A, '', 0);
        if (isset($ET)) {
          $nr = $db->query("select * from " . dn("sklep_etykieta") . " where et_id in (" . join(",", $ET) . ")");
          $TR = array();
          while ($T = $db->fetch($nr)) {
            $TR["{ET_PLIK_" . $T[0] . "}"] = s($T["et_plik"]);
            $TR["{ET_TXT_" . $T[0] . "}"]  = $T["et_nazwa"];
          }
          $TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"] = strtr($TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"], $TR);
        }
        $TT["{ILOSC}"]                                     = $Cnawi->get_pozycje();
        $TT["{IDZDO}"]                                     = $Cnawi->get_idzdo();
        $TT["{STRONY}"]                                    = $Cnawi->prn_strony();
        $TT["{OP_" . strtoupper($this->order) . "}"]       = "selected";
        $TT['{PPP_' . $_SESSION[SID]['shop']['ppp'] . '}'] = ' selected ';
      } else {
        $DD[] = 'IT_LISTA';
      }

      //BANERCATEGORY
      if ($Cnawi->get_min() == 0) {
        $TT['{BANERCATEGORY}']       = '';
        $TT['{BANERCATEGORYBRANDS}'] = s($this->WYNIK["fo"]["ka_opis"]);
        $query                       = "select * from " . dn($this->db_name) . " where ka_id=" . $this->id . ";";
        $res                         = $db->query($query);
        $item                        = $db->fetch($res);

        if (file_exists($item['ka_baner']) && is_file($item['ka_baner'])) {
          if (!empty($item['ka_baner_url'])) $TT['{BANERCATEGORY}'] = '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $item['ka_baner_url'] . '"><img src="' . $_GLOBAL['page_url'] . $item['ka_baner'] . '" alt="" class="bigbaner" /></a>';
          else $TT['{BANERCATEGORY}'] = '<img src="' . $_GLOBAL['page_url'] . $item['ka_baner'] . '" alt="" class="bigbaner" />';
        }


        // custom description
        $currentURL = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $q          = "select * from " . dn('sklep_custom_description') . " where url = '" . trim($currentURL) . "'";
        $r          = $db->query($q);
        $item       = $db->fetch($r);
        if (is_array($item)) {
          $TT['{BANERCATEGORYBRANDS}'] = '<div class="brands-baner">
                ' . stripslashes($item['header']) . '
                </div>';
        } elseif ((isset($_GET['b'])) && ($_GET['b'] == 'y')) {
          $query = "select * from " . dn('sklep_producent') . " where pd_id=" . (int)$this->WYNIK['sz']['spd'] . ";";
          $res   = $db->query($query);
          $dane  = $db->fetch($res);

          $path = './files/prd/';
          $img  = '';
          $img2 = '';

          if (!empty($dane['pd_plik'])) {
            $img2 = '<img src="' . $_GLOBAL['page_url'] . '' . $dane['pd_plik'] . '" alt="" style="float: right" /><br><Br>';
          }

          if ($dane['pd_baner_type'] == 'jpg') {
            if (file_exists($path . (int)$this->WYNIK['sz']['spd'] . '.jpg') && (is_file($path . (int)$this->WYNIK['sz']['spd'] . '.jpg')))
              $img = '<img src="' . $_GLOBAL['page_url'] . '' . $path . (int)$this->WYNIK['sz']['spd'] . '.jpg' . '" alt="" class="brandsbaner" />';
          } elseif ($dane['pd_baner_type'] == 'swf') {
            $img = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="380" height="273" id="flash_id" align="middle">
                                        <param name="allowScriptAccess" value="sameDomain" />
                                        <param name="movie" value="' . $_GLOBAL['page_url'] . $path . $dane['pd_id'] . '.swf" />
                                        <param name="quality" value="high" />
                                        <param name="wmode" value="transparent">
                                        <param name="scale" value="scale" />
                                        <param name="menu" value="false" />
                                        <embed src="' . $_GLOBAL['page_url'] . $path . $dane['pd_id'] . '.swf" menu="false" scale="scale" wmode="transparent" quality="high" width="380" height="273" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                        </object>';
          } elseif ($dane['pd_baner_type'] == 'mp4') {
            $img = '<object width="380" height="273" type="application/x-shockwave-flash" data="' . $_GLOBAL['page_url'] . 'files/player.swf">
                        <param name="movie" value="' . $_GLOBAL['page_url'] . 'files/player.swf" />
                                        <param name="wmode" value="transparent">
                        <param name="flashvars" value="controlbar=none&amp;image=' . $_GLOBAL['page_url'] . 'files/prd/' . $dane['pd_id'] . '.jpg&amp;file=' . $_GLOBAL['page_url'] . 'files/prd/' . $dane['pd_id'] . '.mp4" />
                                        <embed src="' . $_GLOBAL['page_url'] . 'files/player.swf" FlashVars="controlbar=none&amp;image=' . $_GLOBAL['page_url'] . 'files/prd/' . $dane['pd_id'] . '.jpg&amp;file=' . $_GLOBAL['page_url'] . 'files/prd/' . $dane['pd_id'] . '.mp4"  menu="false" scale="scale" wmode="transparent" quality="high" width="380" height="273" name="flash_id" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
                                    </object>'; //over
          }

          if ($_GLOBAL['langid'] == 2) {
            $pd_opis = $dane['pd_opis_en'];
          } else {
            $pd_opis = $dane['pd_opis'];
          }

          $TT['{BANERCATEGORYBRANDS}'] = '
                    <div class="brands-baner">
                        <div class="brands-baner-img">' . $img . '</div>
                        <div class="brands-baner-text">
                            <h1 style="float:left">' . s($dane['pd_nazwa']) . '</h1>
                            <br><br>
                            <div id="scroll-pane" style="width:100%; height:205px; overflow; auto; padding: 0px;">                    
                                <div id="scroll-pane-container">' . nl2br($pd_opis) . '</div>
                            </div>
                        </div>
                    </div>';
        }
      } else {
        $TT['{BANERCATEGORY}']       = '';
        $TT['{BANERCATEGORYBRANDS}'] = "";
      }


      return get_template($tpl, $TT, $DD, 0);
    }


    function get_kategoria_ajax($ckatid = 0) {
      global $db, $_GLOBAL, $_SESSION, $_GET, $sURL, $Cuzytkownik;

      $A              = '';
      $userID         = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
      $userWithOrders = $this->checkUserFinalizedOrders($userID);

      $TT  = $DD = $PR = array();
      $tpl = get_template("shop_category_ajax");

      $DD[] = 'IT_TRESC';
      $DD[] = 'IT_ELEMENT';
      $Q[]  = "kp_widoczna='1'";
      $Q[]  = ($userID > 0) ? (($userWithOrders) ? "(pr_widoczny='1' OR pr_widoczny='2' OR pr_widoczny='3')" : "(pr_widoczny='1' OR pr_widoczny='2')") : "pr_widoczny='1'";
      $Q[]  = "pr_stan = '1'";
      $lj   = '';

      if ($this->WYNIK["sz"]["sna"] != "") {
        $Q[] = "(pr.pr_nazwa like '%" . a($this->WYNIK["sz"]["sna"]) . "%' or pd_nazwa like '%" . a($this->WYNIK["sz"]["sna"]) . "%' or pr_indeks like '" . a($this->WYNIK["sz"]["sna"]) . "%')";
      }

      if ($this->WYNIK["sz"]["sop"] != "") {
        $Q[] = "(pr_opis like '%" . a($this->WYNIK["sz"]["sop"]) . "%')";
      }

      if ($this->WYNIK["sz"]["spd"] != "") {
        $Q[] = "(pr_pd_id = '" . a($this->WYNIK["sz"]["spd"]) . "')";
      }

      if ($this->WYNIK["sz"]["sco"] != "") {
        $Q[] = "(" . get_cena_w() . " >= '" . a($this->WYNIK["sz"]["sco"]) . "')";
      }

      if ($this->WYNIK["sz"]["scd"] != "") {
        $Q[] = "(" . get_cena_w() . " <= '" . a($this->WYNIK["sz"]["scd"]) . "')";
      }

      if ($this->WYNIK["sz"]["ska"] != "" and test_int($this->WYNIK["sz"]["ska"])) {
        $Q[] = " kp_ka_id=ka_id and (kp_ka_id=" . (int)$this->WYNIK["sz"]["ska"] . " or ka_ka_id=" . (int)$this->WYNIK["sz"]["ska"] . ") ";
        $lj .= " ," . dn("sklep_kategoria") . " ";
      } else {
        $katmp = array();
        $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$_POST['ckatid'] . " order by ka_pozycja;";
        $res   = $db->query($query);
        while ($item = $db->fetch($res)) {
          $katmp[] = $item['ka_id'];
          $query   = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . " order by ka_pozycja;";
          $res2    = $db->query($query);

          while ($item2 = $db->fetch($res2)) {
            $katmp[] = $item2['ka_id'];

            $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . " order by ka_pozycja;";
            $res3  = $db->query($query);

            while ($item3 = $db->fetch($res3)) {
              $katmp[] = $item3['ka_id'];
            }

          }
        }

        $Q[] = " kp_ka_id=ka_id and ka_id in (" . join(', ', $katmp) . ") ";
        $lj .= " ," . dn("sklep_kategoria") . " ";
      }

      if ($this->WYNIK["sz"]["sro"] != "" and test_int($this->WYNIK["sz"]["sro"])) {
        $Q[] = " pr.pr_id=ms_pr_id and ms_at_id=" . (int)$this->WYNIK["sz"]["sro"];
        $lj .= " ," . dn("produkt_rozmiar_v") . " ";
      }

      $q = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
      $q .= get_cena_w() . ",  pd_nazwa,pd_plik,kp_ka_id, pr_gt_id, ka_nazwa, IF ((pr_cena_a_brutto - pr_cena_w_brutto)>0,1,0) AS upust
              from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn($this->db_name . "_produkt") . "," . dn("sklep_producent") . " " . $lj;
      $q .= "where pr.pr_id=p1.pr_id ";
      $q .= "and pr.pr_id=kp_pr_id ";
      $q .= "and pr_pd_id=pd_id "; //.$lj;
      $q .= "and " . join(" and ", $Q) . " group by kp_pr_id order by ";

      switch ($this->order) {
        case "data":
          $q .= "upust asc, pr.pr_image_upload_timestamp desc, pr.pr_id desc";
          break;
        case "cena":
          $q .= "pr_cena_w_brutto ";
          break;
        case "nazwa":
          $q .= pl_order("pr.pr_nazwa") . " ";
          break;
        default:
          $q .= "upust asc, pr.pr_image_upload_timestamp desc, pr.pr_id desc";
      }

      $url                  = "sklep,szukaj";
      $TT["{URL}"]          = $url . ".htm?";
      $this->WYNIK["tytul"] = $TT["{NAZWA}"] = $this->WYNIK["sz"]["sale"] != "" ? "sale" : "{T_SZUKAJ_WYNIK}";

      $nr         = $db->query($q);
      $ile_wiersz = 0;
      $wiersz     = "";
      if ($db->affected_rows()) {
        $DD["s"] = "IT_PRODUKT_S";
        $DD["m"] = "IT_PRODUKT_M";
        $DD["l"] = "IT_PRODUKT_L";

        $tpl    = get_tag($tpl, "PRODUKT_" . strtoupper($this->widok_listy), $ile_wiersz);
        $tpl    = get_tag($tpl, "IT_PRODUKT_" . strtoupper($this->widok_listy), $wiersz);
        $tpl_pr = get_template("shop_product_" . $this->widok_listy);

        unset($DD[$this->widok_listy]);
        $DD[]   = 'IT_BRAK';
        $i      = 0;
        $waluta = $this->WYNIK["boks"] == "upominek" ? "pkt." : "PLN";

        while ($T = $db->fetch($nr)) {
          if ($i and $i % $ile_wiersz == 0) {
            $TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"] .= chr(10) . get_template($wiersz, $A, '', 0);
            $A = array();
          }
          $i++;
          $B            = $DB = array();
          $B["{NAZWA}"] = s($T["pr_nazwa"]);
          $B["{ALT}"]   = h($T["pr_nazwa"]);

          if ($T["pr_plik"] == "") {
            $plik = "files/product/images/" . ceil($T["pr_id"] / 500) . "/m/" . $T["pr_id"] . "_0.jpg";
            if (file_exists($plik)) {
              $T["pr_plik"] = $plik;
            }
          }

          $T['pr_plik']  = str_replace('http:////', '', $T['pr_plik']);
          $PR['pr_plik'] = str_replace('http://gomez.pl//', '', $PR['pr_plik']);
          if (substr($T["pr_plik"], 0, 8) == "http:///") {
            $T["pr_plik"] = substr($T["pr_plik"], 8);
          }

          if (substr($T["pr_plik"], 0, 4) == "http") {
            $T["pr_plik"] = substr($T["pr_plik"], 8);
            $T["pr_plik"] = substr($T["pr_plik"], strpos($T["pr_plik"], "/") + 1);
          }

          $T["pr_plik"] = str_replace("/x/", "/m/", $T["pr_plik"]);
          $B["{IMG}"]   = ($T["pr_plik"] != "" and file_exists($T["pr_plik"])) ? str_replace("/m/", "/" . $this->widok_listy . "/", $T["pr_plik"]) : 'images/nofoto_' . $this->widok_listy . '.png';
          $B['{IMG}']   = $_GLOBAL['page_url'] . str_replace("/l/", "/m/", $B['{IMG}']);
          if ($this->WYNIK["boks"] == "upominek") $B["{CENA}"] = $T["pr_punkt"];
          else $B["{CENA}"] = ($T["pr_cena_w_brutto"] < $T["pr_cena_a_brutto"] ? '<span class="sale">' . number_format($T["pr_cena_a_brutto"], 2, ".", "") . ' ' . $waluta . '</span> ' . number_format($T["pr_cena_w_brutto"], 2, ".", "") : number_format($T["pr_cena_w_brutto"], 2, ".", ""));

          $B["{PRODUCENT}"] = s($T["pd_nazwa"]);

          $B["{WALUTA}"] = $waluta;
          // $B["{URL}"] = $url.",".$T["pr_id"].",".conv2file(s($T["pr_nazwa"])).".htm";
          $B['{URL}'] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/produkt/' . $T['pr_id'] . '/' . conv2file($T["pd_nazwa"]) . '/' . conv2file($T['pr_nazwa']) . '/';

          if ($T["pr_etykieta"] != "") {
            $X = explode("|", $T["pr_etykieta"]);
            if (test_int($X[0])) {
              $B["{ET_PLIK}"] = "{ET_PLIK_" . $X[0] . "}";
              $B["{ET_TXT}"]  = "{ET_TXT_" . $X[0] . "}";
              $ET[$X[0]]      = $X[0];
            } else {
              $DB[] = "IT_ETYKIETA";
            }
          } else {
            $DB[] = "IT_ETYKIETA";
          }


          // KIDS
          $kids_query  = 'SELECT kp_ka_id FROM mm_sklep_kategoria_produkt WHERE (kp_ka_id = 277 OR kp_ka_id = 308) AND kp_pr_id = ' . $T['pr_id'];
          $kids_result = $db->get_all($kids_query);

          if (($sURL[0] == 'kids') OR (count($kids_result) > 0)) {
            $B["{AGE_CLASS}"] = 'kids';
          } else {
            $B["{AGE_CLASS}"] = 'adults';
          }

          //rozmiary
          $ORDER = array();

          // getting amounts for meta-product
          $product   = $db->onerow('SELECT pr_indeks FROM mm_produkt WHERE pr_id = ' . $T["pr_id"]);
          $index     = $product['pr_indeks'];
          $index[10] = '%';
          $query     = '  SELECT ms_at_id, at_nazwa
                            FROM ' . dn("magazyn_stan") . '
                            LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                            LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                            WHERE
                                ms_ilosc > 0 AND
                                pr_indeks LIKE \'' . $index . '\' AND
                                ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                                at_nazwa != \'Pusty\'
                            ORDER BY at_nazwa';
          // ---

          $nrr = $db->query($query);
          while ($Tr = $db->fetch($nrr)) {
            if (!empty($Tr[1]))
              if (is_numeric(substr($Tr[1], 0, 1))) $ORDER[0][$Tr[0]] = $Tr[1];
              else $ORDER[1][$Tr[0]] = $Tr[1];
            //if(!empty($Tr[1])) $ORDER[$Tr[0]] = $Tr[1];
          }

          if (is_array($ORDER[0])) asort($ORDER[0]);
          $tmp = array();
          $ROZ = array("XXS", "XS", "S", "M", "L", "XL", "XXL", "XXXL", "XXXXL");
          foreach ($ROZ as $value) {
            if (in_array($value, (array)$ORDER[1])) {
              $klucz = '';
              $klucz = array_search($value, $ORDER[1]);
              $tmp[] = $value;
            }
          }

          $B['{ROZMIARY}'] .= join(', ', $tmp);

          if ((count($ORDER[1]) > 0) && (count($ORDER[0]) > 0)) $B['{ROZMIARY}'] .= ', ';

          $B['{ROZMIARY}'] .= join(', ', (array)$ORDER[0]);
          $A["{PRODUKT_" . strtoupper($this->widok_listy) . "}"] .= chr(10) . get_template($tpl_pr, $B, $DB, 0);
        }
        $TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"] .= chr(10) . get_template($wiersz, $A, '', 0);

        if (isset($ET)) {
          $nr = $db->query("select * from " . dn("sklep_etykieta") . " where et_id in (" . join(",", $ET) . ")");
          $TR = array();
          while ($T = $db->fetch($nr)) {
            $TR["{ET_PLIK_" . $T[0] . "}"] = s($T["et_plik"]);
            $TR["{ET_TXT_" . $T[0] . "}"]  = $T["et_nazwa"];
          }
          $TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"] = strtr($TT["{IT_PRODUKT_" . strtoupper($this->widok_listy) . "}"], $TR);
        }
      } else {
        $DD[] = 'IT_LISTA';
      }

      //BANERCATEGORY
      $TT['{BANERCATEGORY}'] = '';
      $query                 = "select * from " . dn($this->db_name) . " where ka_id=" . $this->id . ";";
      $res                   = $db->query($query);
      $item                  = $db->fetch($res);


      if (file_exists($item['ka_baner']) && is_file($item['ka_baner'])) {
        $TT['{BANERCATEGORY}'] = '<img src="' . $_GLOBAL['page_url'] . '' . $item['ka_baner'] . '" alt="" />';
      }

      return get_template($tpl, $TT, $DD, 0);
    }


    /**
     * Wyświetlenie formularza wyszukiwania zaawansowanego
     *
     * @param string $msg
     *
     * @return string Formularz wyszukiwania
     */
    function prn_szukaj_form($msg = "") {
      global $_GLOBAL;

      $DD = $TT = array();
      if ($msg != "") {
        $TT["{MSG}"] = $msg;
      } else {
        $DD[] = "IT_MSG";
      }
      $TT['{URL}']  = $_GLOBAL['page_url'];
      $TT['{LANG}'] = $_GLOBAL['lang'];

      return get_template("shop_search", $TT, $DD);
    }


    /**
     * Wyświetlenie karty produktu
     * @return string Karta produktu
     */
    function get_produkt($druk = false) {
      global $db, $_GLOBAL, $Cuzytkownik, $sURL, $L;

      $userID         = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
      $userWithOrders = $this->checkUserFinalizedOrders($userID);
      $wh             = ($userID > 0) ? (($userWithOrders) ? "(pr_widoczny='1' OR pr_widoczny='2' OR pr_widoczny='3')" : "(pr_widoczny='1' OR pr_widoczny='2')") : "pr_widoczny='1'";

      $prdDesc = array();

      $q = "select *," . get_cena_w() . " pr_cena_w_brutto,pd_nazwa,pd_plik,pd_url, pd_alias
              from " . dn("produkt") . " pr, " . dn("sklep_produkt") . " p1, " . dn("sklep_producent") . "
              where pr.pr_id=p1.pr_id ";
      $q .= "and pr_pd_id=pd_id ";
      $q .= "and pr.pr_id=" . (int)$this->id2 . " and " . $wh . " ";
      $PR = $db->onerow($q);

      if ((int)$_GLOBAL['langid'] != 1) {
        $query = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $this->id2 . " and langid=" . (int)$_GLOBAL['langid'] . ";";
        $res   = $db->query($query);
        while ($item = $db->fetch($res)) {
          $prdDesc[$item['name']] = $item['description'];
        }
      }

      if ((count($PR) == 0) || ($PR == '')) {
        header('Location: ' . $_GLOBAL['page_url']);
        exit;
      }

      $TT           = $DD = array();
      $tpl          = get_template($druk == true ? "shop_product_print" : "shop_product");
      $TT["{PRID}"] = $PR["pr_id"];

      if ($_GLOBAL['langid'] != 1) {
        if (empty($prdDesc['nazwa'])) $TT['{ALT}'] = h($PR["pr_nazwa"]);
        else $TT['{ALT}'] = $prdDesc['nazwa'];

        if (empty($prdDesc['opis'])) $TT['{OPIS}'] = s($PR["pr_opis"]);
        else $TT['{OPIS}'] = $prdDesc['opis'];

        if (empty($prdDesc['nazwa'])) $this->WYNIK["tytul"] = $TT['{NAZWA}'] = s($PR["pr_nazwa"]);
        else $this->WYNIK["tytul"] = $TT['{NAZWA}'] = $prdDesc['nazwa'];
      } else {
        $TT['{ALT}']          = h($PR["pr_nazwa"]);
        $TT['{OPIS}']         = s($PR["pr_opis"]);
        $this->WYNIK["tytul"] = $TT["{NAZWA}"] = s($PR["pr_nazwa"]);
      }

      if ($Cuzytkownik->test_right("islogin")) $TT["{OPIS}"] = '<b><a href="javascript:;" onclick="my_panel(\'' . $_GLOBAL['page_url'] . 'admin/shop_product.php?submit_zmien=1&id=' . $this->id2 . '\',\'\',\'\',\'\');" style="background:#f00;color:#fff"> *EDYTUJ PRODUKT* </a></b><br/>' . $TT["{OPIS}"];
      $TT["{INDEKS}"] = s($PR["pr_indeks"]);

      $query         = "select *
                  from " . dn('produkt') . " p, " . dn('sklep_produkt') . " sp, " . dn('sklep_producent') . "
                  where pr_indeks like '" . substr($PR['pr_indeks'], 0, 9) . "%'
                        and sp.pr_id=p.pr_id
                        and sp.pr_pd_id=pd_id
                        and " . $wh . "
                  group by p.pr_id";
      $productColors = $db->get_all($query);

      foreach ($productColors as $key => $value) {
        $product   = $db->onerow('SELECT pr_indeks FROM mm_produkt WHERE pr_id = ' . $value["pr_id"]);
        $index     = $product['pr_indeks'];
        $index[10] = '%';
        $query     = '  SELECT ms_at_id, at_nazwa
                        FROM ' . dn("magazyn_stan") . '
                        LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                        LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                        WHERE
                            ms_ilosc > 0 AND
                            pr_indeks LIKE \'' . $index . '\' AND
                            ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                            at_nazwa != \'Pusty\'
                        ORDER BY at_nazwa';
        $cr        = $db->get_all($query);
        if (count($cr) == 0) {
          unset($productColors[$key]);
        }
      }

      $lp  = 0;
      $lp2 = 0;
      if (strlen($PR['pr_indeks']) > 6) {
        if (count($productColors) > 0) {
          foreach ($productColors as $item) {
            $query = "select * from " . dn('color') . ", " . dn('color_product') . "
                              where " . dn('color_product') . ".product=" . (int)$item['pr_id'] . " and
                                    " . dn('color') . ".id=" . dn('color_product') . ".color and
                                    `primary`=1 and
                                    langid=" . (int)$_GLOBAL['langid'] . ";";
            $res2  = $db->query($query);
            if ($db->num_rows($res2) > 0) {
              $item2 = $db->fetch($res2);

              $TT['{KOLORY}'] .= '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/produkt/' . $item['pr_id'] . '/' . conv2file($item["pd_nazwa"]) . '/' . conv2file($item['pr_nazwa']) . '/"><div style="background-color: ' . $item2['code'] . '" class="color-item with-tooltip" title="' . $item2['name'] . '"></div></a>';
              $lp2++;
            }

            $lp++;
          }

          if ($lp2 > 0) {
            $TT['{KOLORYLABEL}'] = $L['{T_DOSTEPNE_KOLORY}'] . ': '; //'Dostępne kolory:';
          }
        }
      }

      if ($lp == 0) $TT["{KOLOR}"] = $TT['{KOLORY}'] = '   ' . $PR["pr_kolor"] ? s($PR["pr_kolor"]) : "-";
      else $TT["{KOLOR}"] = $PR["pr_kolor"] ? s($PR["pr_kolor"]) : "-";

      $item2 = null;

      $TT['{KOLOR}'] = '';
      $query         = "select * from " . dn('color_product') . " where product=" . (int)$PR['pr_id'] . " and `primary`=1;";

      $res = $db->query($query);
      if ($db->num_rows($res) > 0) {
        $da = $db->fetch($res);

        $query         = "select * from " . dn('color') . " where id=" . (int)$da['color'] . " and langid=" . (int)$_GLOBAL['langid'] . ";";
        $res           = $db->query($query);
        $da2           = $db->fetch($res);
        $TT['{KOLOR}'] = $da2['name'];
      }

      $tpl = get_tag($tpl, "IT_ATRYBUT", $artrybut);
      $XX  = explode("|^|", $PR["pr_dodatkowe_atrybuty"]);
      foreach ($XX as $v) {
        if ($v != "") {
          $XV              = explode("|", $v);
          $TX["{NAZWA}"]   = $XV[0];
          $TX["{WARTOSC}"] = $XV[1];
          $TT["{IT_ATRYBUT}"] .= get_template($artrybut, $TX, '', 0);
        }
      }

      if ($PR["pd_plik"] != "" and file_exists($PR["pd_plik"])) $TT["{PRODUCENT}"] = '<img src="' . $_GLOBAL['page_url'] . s($PR["pd_plik"]) . '" alt="' . $PR["pd_nazwa"] . '" />';
      else $TT["{PRODUCENT}"] = s($PR["pd_nazwa"]);

      if ($druk == true) $TT["{PRODUCENT}"] = s($PR["pd_nazwa"]);

      $TT['{PRODUCENTNAME}'] = s($PR['pd_nazwa']);

      $query = "select
                    " . dn('product_other_atrybut') . ".id,
                    " . dn('product_other_atrybut') . ".table,
                    " . dn('product_other_atrybut') . ".tkanina,
                    " . dn('product_other_atrybut') . ".fason,
                    " . dn('product_other_atrybut') . ".faktura,
                    " . dn('product_other_atrybut') . ".model,
                    " . dn('sklep_collection') . ".name as kolekcja_pl,
                    " . dn('sklep_collection') . ".name_en as kolekcja_en
                  from " . dn('product_other_atrybut') . "
                  LEFT JOIN " . dn('sklep_collection') . " ON " . dn('product_other_atrybut') . ".collection_id = " . dn('sklep_collection') . ".id
                  where " . dn('product_other_atrybut') . ".id=" . (int)$PR['pr_id'] . ";";
      $res   = $db->query($query);
      $item  = $db->fetch($res);

      if ($_GLOBAL['langid'] != 1) {
        $TT['{KOLEKCJA}'] = $item['kolekcja_en'];

        if (empty($prdDesc['sklad'])) $TT['{TKANINA}'] = $item['tkanina'];
        else $TT['{TKANINA}'] = $prdDesc['sklad'];
      } else {
        $TT['{KOLEKCJA}'] = $item['kolekcja_pl'];
        $TT['{TKANINA}']  = $item['tkanina'];
      }
      $TT['{MODEL}'] = s($PR['pr_polozenie']);

      $TT["{URL}"]           = "sklep," . $this->id . "," . $PR["pr_id"] . "," . conv2file(s($PR["pr_nazwa"])) . ".htm";
      $TT["{URL_PRODUCENT}"] = ($PR["pd_url"] != "" ? s($PR["pd_url"]) : $_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/brands/" . $PR['pd_alias'] . '/');
      $TT["{TYTUL}"]         = s($this->WYNIK["fo"]["ka_nazwa"]);

      $PR['pr_plik'] = str_replace('http:////', '', $PR['pr_plik']);
      $PR['pr_plik'] = str_replace('http://gomez.pl//', '', $PR['pr_plik']);

      if (substr($PR["pr_plik"], 0, 8) == "http:///") {
        $PR["pr_plik"] = substr($PR["pr_plik"], 8);
      }

      if (substr($PR["pr_plik"], 0, 4) == "http") {
        $PR["pr_plik"] = substr($PR["pr_plik"], 8);
        $PR["pr_plik"] = substr($PR["pr_plik"], strpos($PR["pr_plik"], "/") + 1);
      }

      $PR["pr_plik"] = str_replace("/x/", "/m/", str_replace($_GLOBAL["page_url"] . "/", "", $PR["pr_plik"]));
      $TT["{IMG_M}"] = ($PR["pr_plik"] != "" and file_exists($PR["pr_plik"])) ? str_replace("/m/", "/m/", $PR["pr_plik"]) : 'images/nofoto_l.png';
      $TT["{IMG_X}"] = ($PR["pr_plik"] != "" and file_exists($PR["pr_plik"])) ? str_replace("/m/", "/x/", $PR["pr_plik"]) : 'images/nofoto_l.png';

      $TT["{IMG_S}"] = ($PR["pr_plik"] != "" and file_exists($PR["pr_plik"])) ? str_replace("/m/", "/s/", $PR["pr_plik"]) : 'images/nofoto_l.png';
      $TT["{IMG_L}"] = ($PR["pr_plik"] != "" and file_exists($PR["pr_plik"])) ? str_replace("/m/", "/l/", $PR["pr_plik"]) : 'images/nofoto_l.png';

      $TT["{IMG_M}"] = $_GLOBAL['page_url'] . $TT["{IMG_M}"];
      $TT["{IMG_X}"] = $_GLOBAL['page_url'] . $TT["{IMG_X}"];

      $TT["{IMG_S}"] = $_GLOBAL['page_url'] . $TT["{IMG_S}"];
      $TT["{IMG_L}"] = $_GLOBAL['page_url'] . $TT["{IMG_L}"];


      if ($this->WYNIK["boks"] == "upominek") {
        //$TT['{T_KOSZTY_DOSTAWY}'] = sprintf('Koszt dostawy: <strong>%s zł</strong>/wysyłka 24h',$Q);
        $TT["{CENA}"]   = $PR["pr_punkt"];
        $TT["{WALUTA}"] = "pkt.";
      } else {
        //przecena
        $query = "select * from " . dn('sklep_dostawa') . " where do_id=4;";
        $rd    = $db->query($query);
        $dos   = $db->fetch($rd);

        if ($PR["pr_cena_w_brutto"] < $PR["pr_cena_a_brutto"]) {
          //przecena
          $TT['{T_KOSZTY_DOSTAWY}'] = sprintf($L['{T_KOSZT_DOSTAWY_WYSYLKA_24H}'], (($PR["pr_cena_w_brutto"] >= 299.99) ? 0 : $dos['do_koszt']));
          $TT["{CENA}"]             = '<strike>' . number_format($PR["pr_cena_a_brutto"], 2, ".", "") . '</strike> ' . number_format($PR["pr_cena_w_brutto"], 2, ".", "");
        } else {
          $TT['{T_KOSZTY_DOSTAWY}'] = sprintf($L['{T_KOSZT_DOSTAWY_WYSYLKA_24H}'], (($PR["pr_cena_w_brutto"] > 200) ? 0 : $dos['do_koszt']));
          $TT["{CENA}"]             = number_format($PR["pr_cena_w_brutto"], 2, ".", "");
        }
        //$TT["{CENA}"] = ($PR["pr_cena_w_brutto"]<$PR["pr_cena_a_brutto"]?'<strike>'.number_format($PR["pr_cena_a_brutto"],2,".","").'</strike> '.number_format($PR["pr_cena_w_brutto"],2,".",""):number_format($PR["pr_cena_w_brutto"],2,".",""));
        $TT["{WALUTA}"] = "zł";
      }

      $etykieta = $img = $tabela = "";
      $tpl      = get_tag($tpl, "IT_ETYKIETA", $etykieta);
      $tpl      = get_tag($tpl, "IT_IMG", $img);

      $tpl = get_tag($tpl, "IT_TABELA", $tabela);

      if ($PR["pr_etykieta"] != "") {
        $nr = $db->query("select * from " . dn("sklep_etykieta") . " where et_id in (" . str_replace("|", ",", $PR["pr_etykieta"]) . ")");
        while ($T = $db->fetch($nr)) {
          $TB["{IMG}"]    = s($T["et_plik"]);
          $TB["{ET_TXT}"] = s($T["et_nazwa"]);
          $TT["{IT_ETYKIETA}"] .= get_template($etykieta, $TB, '', 0);
        }
      }

      if ($PR["pr_tabela"] != "") {
        $nr = $db->query("select * from " . dn("sklep_tabela") . " where ta_id in (" . str_replace("|", ",", $PR["pr_tabela"]) . ")");
        while ($T = $db->fetch($nr)) {
          $TB["{IMG}"] = s($T["ta_plik"]);
          $TT["{IT_TABELA}"] .= get_template($tabela, $TB, '', 0);
        }
      }

      $SA = $db->onerow("select sa_nazwa,sa_plik,sa_url
                           from " . dn("sklep_promocja_produkt") . ", " . dn("sklep_promocja") . "
                           where sp_sa_id=sa_id and
                                 sp_pr_id=" . (int)$this->id2 . " and
                                 sa_widoczna='1' and (sa_data_od='' or sa_data_od <'" . date("Y-m-d H:i") . "') and
                                 (sa_data_do='' or sa_data_do >'" . date("Y-m-d H:i") . "')");

      if (is_array($SA)) {
        $TT["{SA_URL}"]  = $SA["sa_url"] != "" ? $SA["sa_url"] : "javascript:;";
        $TT["{SA_PLIK}"] = $SA["sa_plik"];
      } else {
        $DD[] = "IT_RABAT";
      }

      $imgm     = false;
      $Fimg     = '';
      $FimgFlag = true;
      for ($i = 0; $i < $_GLOBAL["shop_galeria"]; $i++) {
        if (file_exists("./files/product/images/" . ceil($PR["pr_id"] / 500) . "/m/" . $PR["pr_id"] . "_" . $i . ".jpg")) {
          $TB            = array();
          $TB["{PRID}"]  = $PR["pr_id"];
          $TB["{IMNR}"]  = $i;
          $TB["{IMG_M}"] = $_GLOBAL['page_url'] . "files/product/images/" . ceil($PR["pr_id"] / 500) . "/m/" . $PR["pr_id"] . "_" . $i . ".jpg";
          $TB["{IMG_L}"] = $_GLOBAL['page_url'] . "files/product/images/" . ceil($PR["pr_id"] / 500) . "/l/" . $PR["pr_id"] . "_" . $i . ".jpg";
          $TB["{IMG_S}"] = $_GLOBAL['page_url'] . "files/product/images/" . ceil($PR["pr_id"] / 500) . "/s/" . $PR["pr_id"] . "_" . $i . ".jpg";
          $TB["{IMG_X}"] = $_GLOBAL['page_url'] . "files/product/images/" . ceil($PR["pr_id"] / 500) . "/x/" . $PR["pr_id"] . "_" . $i . ".jpg";
          $TB["{LP}"]    = $i;

          $TT["{IT_IMG}"] .= get_template($img, $TB, '', 0);
          $imgm         = true;
          $TT["{IMNR}"] = $i;

          if ($FimgFlag == true) {
            $Fimg     = $TB["{IMG_X}"];
            $FimgFlag = false;
          }
        }
      }

      if (!$imgm) $DD[] = 'IT_IMG_WIECEJ';

      if ($PR["pr_stan"] < 1) {
        $TT["{STAN}"] = "{T_STAN_0}";
        $DD[]         = "IT_DOKOSZYKA1";
        $DD[]         = "IT_DOKOSZYKA2";
        $DD[]         = "IT_ROZMIAR";
      } else {
        if ($PR["pr_stan"] < 4) {
          $TT["{STAN}"] = "{T_STAN_3}";
        } else {
          $TT["{STAN}"] = "{T_STAN_N}";
        }
      }

      //ROZMIARY - start
      $TT["{HIDDEN}"] = '<input type="hidden" name="rozmiar" id="rozmiar" value="" />';
      $ORDER          = array();
      $TABROZ         = array();
      $TABT           = array();

      // getting amounts for meta-product
      $product   = $db->onerow('SELECT pr_indeks FROM mm_produkt WHERE pr_id = ' . $PR["pr_id"]);
      $index     = $product['pr_indeks'];
      $index[10] = '%';
      $query     = '  SELECT ms_at_id, at_nazwa
                    FROM ' . dn("magazyn_stan") . '
                    LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                    LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                    WHERE
                        ms_ilosc > 0 AND
                        pr_indeks LIKE \'' . $index . '\' AND
                        ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                        at_nazwa != \'Pusty\'
                    ORDER BY at_nazwa';
      // ---

      $nrr = $db->query($query);
      while ($Tr = $db->fetch($nrr)) {
        if (!empty($Tr[1]))
          if (is_numeric(substr($Tr[1], 0, 1))) $ORDER[0][$Tr[0]] = $Tr[1];
          else $ORDER[1][$Tr[0]] = $Tr[1];
      }

      if (is_array($ORDER[0])) asort($ORDER[0]);
      $tmp = array();
      $ROZ = array("OS", "XXS", "XS", "XS/S", "S", "S/M", "M", "M/L", "L", "XL", "XXL", "XXXL", "XXXXL", 'XXXXXL');
      foreach ($ROZ as $key => $value) {
        if (in_array($value, (array)$ORDER[1])) {
          $klucz       = '';
          $klucz       = array_search($value, $ORDER[1]);
          $tmp[$klucz] = $value;
        }
      }

      if (count($ORDER[1]) > 0) {
        foreach ($ORDER[1] as $key => $value) {
          if (!in_array($value, (array)$ROZ)) {
            $tmp[] = $value;
          }
        }
      }

      if (count($ORDER[0]) > 0) {
        foreach ($ORDER[0] as $key => $value) {
          $tmp[$key] = $value;
        }
      }

      if (count($tmp) == 1) {
        foreach ($tmp as $roz => $T) {
          $TT["{HIDDEN}"] = '<input type="hidden" name="rozmiar" id="rozmiar" value="' . $roz . '" />';
          if (strtolower($T) != "pusty") {
            $TT['{ROZMIAR}'] = '<ul id="size-list">
                            <li><div class="tabroz' . ($T ? '' : 's') . '" id="roz' . $roz . '"><a href="javascript:;"' . ($T ? ' onclick="roz_wybierz(' . $roz . ',\'' . ($T) . '\');"' : '') . '>' . $T . '</a></div></li>
                            <li><div class="tabroz" id="roz"><a href="javascript:;" onclick="tabsizer()">' . $L['{T_TABELA_ROZMIAROW}'] . '</a></div></li>    
                        </ul>
                        <div class="clear"></div>
                        <span id="rozwyb" style="display: none"> </span>';
          } else {
            $DD[] = "IT_ROZMIAR";
          }
        }
      } else {
        if ($druk) {
          $TT["{ROZMIAR}"] = join(", ", $tmp);
        } else {
          $DD[]            = "IT_JS_VAL";
          $TT["{HIDDEN}"]  = '<input type="hidden" name="rozmiar" id="rozmiar" value="" />';
          $TT["{ROZMIAR}"] = '<ul id="size-list">';

          if ((count($tmp) + (count($tmp))) > 7) $TT['{RS}'] = '1';
          else $TT['{RS}'] = '';

          foreach ($tmp as $roz => $v) {
            $T = $v;
            if ($v == "" or strtolower($v == "pusty")) continue;
            $TT["{ROZMIAR}"] .= '<li><div class="tabroz' . ($v ? '' : 's') . '" id="roz' . $roz . '"><a href="javascript:;" onclick="roz_wybierz(' . $roz . ',\'' . ($v) . '\');">' . $v . '</a></div></li>';
          }

          $TT["{ROZMIAR}"] .= '<li><div class="tabroz" id="roz"><a href="javascript:;" onclick="tabsizer()">' . $L['{T_TABELA_ROZMIAROW}'] . '</a></div></li>';
          // $TT['{ROZMIAR}'] .= '<li><div class="tabroz"><a href="javascript:;" onclick="roz_powiadom(\'\',\'\')">' . $L['{T_ZAPYTAJ_O_ROZMIAR}'] . '</a></div></li>';
          $TT["{ROZMIAR}"] .= '</ul>';

          $TT['{ROZMIAR}'] .= '<div class="clear"></div>';
          $TT["{ROZMIAR}"] .= '<span id="rozwyb" style="display: none"> </span>';
        }
      }

      //ROZMIARY - koniec
      if ($druk == true) $TT['{LINK}'] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/produkt/' . $PR['pr_id'] . '/' . conv2file($PR["pd_nazwa"]) . '/' . conv2file($PR['pr_nazwa']) . '/';

      $jakmierzyc = '';
      //jak mierzyc
      $query = "select ka_jakmierzyc from " . dn('sklep_kategoria_produkt') . ", " . dn('sklep_kategoria') . "
                  where ka_id=kp_ka_id and
                        kp_pr_id=" . (int)$PR['pr_id'] . " and 
                        kp_widoczna='1' and
                        ka_widoczna='1'
                  order by kp_pozycja;";
      $res   = $db->query($query);
      while ($jm = $db->fetch($res)) {
        $jakmierzyc .= $jm['ka_jakmierzyc'] . '<br>';
      };


      $query = "select * from " . dn('product_other_atrybut') . " where id=" . (int)$PR['pr_id'] . ";";
      $res   = $db->query($query);
      $item  = $db->fetch($res);

      //$TT['{TABLESIZERWOMAN}'] = str_replace('\"','"',$item['st_tresc']);
      if ($_GLOBAL['langid'] != 1) {
        if (!empty($prdDesc['table'])) $TT['{TABLESIZERWOMAN}'] = $prdDesc['table'];
        else $TT['{TABLESIZERWOMAN}'] = str_replace('\"', '"', $item['table']);
      } else $TT['{TABLESIZERWOMAN}'] = str_replace('\"', '"', $item['table']);
      //$TT['{TABLESIZERMEN}'] = str_replace('\"','"',$item1['st_tresc']);
      $TT['{JAKMIERZYC}'] = str_replace('\"', '"', $jakmierzyc);

     $tpl     = get_tag($tpl, "PODOBNE", $podobne_typ);
     $podobne = $this->get_podobne($PR["pr_id"], $podobne_typ);

     if ($podobne != "") $TT["{PODOBNE}"] = $podobne;
     else $DD[] = "IT_PODOBNE";

      $this->ogladane_dodaj($PR["pr_id"]);
      $db->query("update " . dn("sklep_produkt") . " set pr_odslon=pr_odslon+1 where pr_id=" . (int)$PR["pr_id"]);

      //$TB["{IMG_X}"]
      get_head('<meta property="og:title" content="' . $PR['pr_nazwa'] . '" />
          <meta property="og:type" content="article" />
          <meta property="og:url" content="' . $_GLOBAL["page_url"] . $_GLOBAL['lang'] . '/produkt/' . $PR['pr_id'] . '/' . conv2file($PR["pd_nazwa"]) . '/' . conv2file($PR['pr_nazwa']) . '/" />
          <meta property="og:image" content="' . $Fimg . '" />
          <meta property="og:site_name" content="Gomez.pl" />
          <meta property="fb:admins" content="100000482816449" />');
      // -- link do strony producenta
      $TT['{PRDURL}'] = $_GLOBAL["page_url"] . $_GLOBAL['lang'] . '/produkt/' . $PR['pr_id'] . '/' . conv2file($PR["pd_nazwa"]) . '/' . conv2file($PR['pr_nazwa']) . '/';

      $TT['{UID}']    = $Cuzytkownik->get_id();
      $TT['{URL}']    = $_GLOBAL['page_url'];
      $TT['{LANG}']   = $_GLOBAL['lang'];
      $TT['{LANGID}'] = $_GLOBAL['langid'];

      // Do pinteresta
      $TT['{PINIT_URL}']     = rawurlencode($TT['{PRDURL}']);
      $TT['{PINIT_IMG_URL}'] = rawurlencode($TT["{IMG_L}"]);
      $TT['{PINIT_DESC}']    = rawurlencode($TT['{PRODUCENTNAME}'] . " - " . $TT['{NAZWA}']);

      return get_template($tpl, $TT, $DD, 0);
    }


    /**
     * Dodanie produktu do listy ostatnio oglądanych prezentowanej w boksie bocznym
     *
     * @param integer $pr
     */
    function ogladane_dodaj($pr) {
      if (isset($_SESSION[SID]["shopogladane"][$pr])) unset($_SESSION[SID]["shopogladane"][$pr]);
      if (!isset($_SESSION[SID]["shopogladane"]) or count($_SESSION[SID]["shopogladane"]) <= 20) {
        $_SESSION[SID]["shopogladane"][$pr] = $pr;
      } elseif (count($_SESSION[SID]["shopogladane"]) > 20) {
        foreach ($_SESSION[SID]["shopogladane"] as $k => $v) {
          unset($_SESSION[SID]["shopogladane"][$k]);
          break;
        }
      }
    }


    /**
     * Wyświetlenie listy produktów podobnych, po filtrze lub przypisaniu ręcznym do produktu
     *
     * @param integer $id
     * @param char    $id
     *
     * @return string
     */
    function get_podobne($id, $type = 's') {
      global $db, $_GLOBAL, $sURL, $Cuzytkownik, $T;

      $userID          = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
      $userWithOrders  = $this->checkUserFinalizedOrders($userID);
      $visibilityWhere = ($userID > 0) ? (($userWithOrders) ? "(sp.pr_widoczny = '1' OR sp.pr_widoczny = '2' OR sp.pr_widoczny = '3')" : "(sp.pr_widoczny = '1' OR sp.pr_widoczny = '2')") : "sp.pr_widoczny = '1'";

      // identyfikatory wszystkich produktow polecanych do aktualnego ($id)
      $ids = array();
      // pobranie wszystkich aktywnych polecanych typu product do ktorych nalezy wskazany produkt
      $productRecomendations = array();
      $query                 = "  SELECT r.id
                    FROM recomendation r
                    LEFT JOIN recomendation_product rp ON r.id = rp.recomendation_id
                    WHERE rp.product_id = $id AND
                        rp.type = 0 AND
                        r.active = 1 AND
                        r.type = 1";
      $result                = $db->query($query);
      while ($item = $db->fetch($result)) {
        $productRecomendations[] = $item['id'];
      }
      // pobranie wszystkich aktywnych polecanych produktow na podstawie powyzszych polecanych
      if (!empty($productRecomendations)) {
        $query  = "  SELECT rp.product_id
                        FROM recomendation_product rp
                        LEFT JOIN recomendation r ON r.id = rp.recomendation_id
                        WHERE
                            rp.type = 1 AND
                            r.type = 1 AND
                            r.active = 1 AND
                            r.id IN (" . implode(',', $productRecomendations) . ")
                        ORDER BY
                            rp.product_id
                        ";
        $result = $db->query($query);
        while ($item = $db->fetch($result)) {
          if (!in_array($item['product_id'], $ids)) {
            $ids[] = $item['product_id'];
          }
        }
      }


      // polecane filtrowe tylko jak nie ma stylizancji

      if (count($ids) == 0) {
        // pobranie wszystkich kategorii do ktorych nalezy wskazany produkt

        $productBrand      = null;
        $productLabel      = null;
        $productCollection = null;
        $query             = "  SELECT sp.pr_pd_id, sp.pr_etykieta, poa.collection_id
                        FROM mm_sklep_produkt sp
                        LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id
                        WHERE sp.pr_id = $id
                        LIMIT 1";
        $result            = $db->query($query);
        while ($item = $db->fetch($result)) {
          $productBrand      = $item['pr_pd_id'];
          $productLabel = $item['pr_etykieta'];
          $productCollection = $item['collection_id'];
        }

        if(empty($productBrand)  || empty($productLabel)  || empty($productCollection)) {
            $db->sendMsgToNina($id);
        }

        // pobranie wszystkich ridow aktywnych typu filtr
        $query = 'SELECT DISTINCT r.id
					FROM recomendation r
					LEFT JOIN recomendation_category rc ON r.id = rc.recomendation_id
					LEFT JOIN recomendation_brand rb ON r.id = rb.recomendation_id
					LEFT JOIN recomendation_collection rcol ON r.id = rcol.recomendation_id
					LEFT JOIN recomendation_label rl ON r.id = rl.recomendation_id
					WHERE (rc.type =0 OR rc.type IS NULL)
					AND (rb.type =0 OR rb.type IS NULL)
					AND (rcol.type =0 OR rcol.type  IS NULL)
					AND (rl.type =0 OR rl.type IS NULL)
					AND (rc.type IS NOT NULL OR rb.type IS NOT NULL OR rcol.type IS NOT NULL OR rl.type IS NOT NULL)
					AND r.active =1
					AND r.type =0
					AND (
						(
							category_id
							IN (
								SELECT DISTINCT mm_sklep_kategoria_produkt.kp_ka_id
								FROM mm_sklep_kategoria_produkt
								WHERE mm_sklep_kategoria_produkt.kp_pr_id = ' . $id . '
							)
							OR category_id IS NULL
						)
						AND (';
                               $query .= (!empty($productBrand) ? 'rb.brand_id = ' . $productBrand . ' OR rb.brand_id IS NULL'
                                                                  : 'rb.brand_id IS NULL ');
                        $query.='
						)
						AND (';
                                $query .= (!empty($productCollection) ? 'rcol.collection_id = ' . $productCollection . ' OR rcol.collection_id IS NULL'
                                                                        : 'rcol.collection_id IS NULL ');
                        $query .='
						)
						AND (';
                                $query .=  (!empty($productLabel) ? 'label_id = ' . $productLabel . ' OR label_id IS NULL'
                                                                   : 'label_id IS NULL ');
                        $query .='
						)
					)';

        $result = $db->query($query);

        while ($item = $db->fetch($result)) {
          // kategorie targetowe dla danego rid (dla konkretnych polecanych)
          $targetCategories = array();
          $query            = 'SELECT category_id FROM recomendation_category WHERE type = 1 AND recomendation_id = ' . $item['id'];
          $ro               = $db->query($query);
          while ($o = $db->fetch($ro)) {
            $targetCategories[] = $o['category_id'];
          }

          // marki targetowe dla danego rid (dla konkretnych polecanych)
          $targetBrands = array();
          $query        = 'SELECT brand_id FROM recomendation_brand WHERE type = 1 AND recomendation_id = ' . $item['id'];
          $ro           = $db->query($query);
          while ($o = $db->fetch($ro)) {
            $targetBrands[] = $o['brand_id'];
          }

          // kolekcje targetowe dla danego rid (dla konkretnych polecanych)
          $targetCollections = array();
          $query             = 'SELECT collection_id FROM recomendation_collection WHERE type = 1 AND recomendation_id = ' . $item['id'];
          $ro                = $db->query($query);
          while ($o = $db->fetch($ro)) {
            $targetCollections[] = $o['collection_id'];
          }

          // etykiety targetowe dla danego rid (dla konkretnych polecanych)
          $targetLabels = array();
          $query        = 'SELECT label_id FROM recomendation_label WHERE type = 1 AND recomendation_id = ' . $item['id'];
          $ro           = $db->query($query);
          while ($o = $db->fetch($ro)) {
            $targetLabels[] = $o['label_id'];
          }

          // pobieranie produktow dla danych warunkow targetowych i dopisywanie do ids[]
          $q = 'SELECT sp.pr_id
                                FROM mm_sklep_produkt sp
                                LEFT JOIN mm_product_other_atrybut poa ON poa.id = sp.pr_id
                                LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = sp.pr_id
                                WHERE sp.pr_stan > 0 AND ' . $visibilityWhere;
          $q .= (!empty($targetCategories)) ? ' AND skp.kp_ka_id IN (' . implode(',', $targetCategories) . ')' : '';
          $q .= (!empty($targetBrands)) ? ' AND sp.pr_pd_id IN (' . implode(',', $targetBrands) . ')' : '';
          $q .= (!empty($targetCollections)) ? ' AND poa.collection_id IN (' . implode(',', $targetCollections) . ')' : '';
          $q .= (!empty($targetLabels)) ? ' AND sp.pr_etykieta IN (' . implode(',', $targetLabels) . ')' : '';

          $ro = $db->query($q);
          while ($o = $db->fetch($ro)) {
            if (!in_array($o['pr_id'], $ids)) {
              array_push($ids, $o['pr_id']);
            }
          }
        }
      }

      // jesli puste, to koniec
      if (count($ids) == 0) {
        return '';
      } else {
        // randomize array
        shuffle($ids);
        // reduce array to X elements
        $ids = array_slice($ids, 0, 100);
      }

      // sprawdzenie dla metaproduktów
      $prodIDs = array();
      if ($_GLOBAL['langid'] != 1) {
        $q = "  SELECT
                        sp.pr_id, p.pr_indeks
                    FROM
                        mm_sklep_produkt sp
                    LEFT JOIN mm_produkt p ON sp.pr_id = p.pr_id
                    LEFT JOIN mm_sklep_product_translation spt ON sp.pr_id = spt.pr_id
                    WHERE
                        sp.pr_id <> {$id} AND
                        p.pr_id IN (" . implode(',', $ids) . ")
                        AND (spt.langid=" . $_GLOBAL['langid'] . " AND spt.name = 'nazwa' AND spt.description <> '')";
      } else {
        $q = "  SELECT
                        sp.pr_id, p.pr_indeks
                    FROM
                        mm_sklep_produkt sp
                    LEFT JOIN mm_produkt p ON sp.pr_id = p.pr_id
                    WHERE
                        sp.pr_id <> {$id} AND ";
                        $q .= ((isset($ids) && !empty($ids)) ? " sp.pr_id IN (" . implode(',', $ids) . ")" :  "");
      }

      $r = $db->get_all($q);
      foreach ($r as $value) {
        $tmpID        = $value['pr_id'];
        $tmpIndex     = $value['pr_indeks'];
        $tmpIndex[10] = '_';
        $query        = '  SELECT ms_at_id, at_nazwa
                        FROM ' . dn("magazyn_stan") . '
                        LEFT JOIN ' . dn('produkt_atrybut') . ' on ms_at_id = at_id
                        LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                        WHERE
                            ms_ilosc > 0 AND
                            pr_indeks LIKE \'' . $tmpIndex . '\' AND
                            ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ') AND
                            at_nazwa != \'Pusty\'
                        ORDER BY at_nazwa';
        $cr           = $db->get_all($query);
        if (count($cr) > 0) {
          $prodIDs[] = $tmpID;
        }
      }
      // ---

      if (count($prodIDs) == 0) return '';

      $whereCond = (count($prodIDs) > 0) ? 'WHERE sp.pr_id IN (' . implode(' , ', $prodIDs) . ')' : 'WHERE sp.pr_id IN (-1)';
      $q         = '  SELECT
					spt.description, 
                    sp.pr_id,
                    sp.pr_nazwa,
                    p.pr_plik,
                    sp.pr_punkt,
                    p.pr_cena_a_brutto,
                    p.pr_cena_w_brutto,
                    sp.pr_etykieta,
                    spd.pd_nazwa,
                    sk.ka_nazwa,
                    IF ((p.pr_cena_a_brutto - p.pr_cena_w_brutto)>0,1,0) AS upust
                FROM mm_sklep_produkt sp
                LEFT JOIN mm_produkt p ON p.pr_id = sp.pr_id
                LEFT JOIN mm_sklep_producent spd ON spd.pd_id = sp.pr_pd_id
                LEFT JOIN mm_sklep_kategoria_produkt skp ON skp.kp_pr_id = sp.pr_id
                LEFT JOIN mm_sklep_kategoria sk ON sk.ka_id = skp.kp_ka_id
				LEFT JOIN mm_sklep_product_translation spt ON sp.pr_id = spt.pr_id AND name LIKE \'nazwa\' AND langid = 2
                ' . $whereCond . '
                GROUP BY sp.pr_id
                ORDER BY RAND()
                LIMIT 100';
      $nr        = $db->query($q);
      $T         = explode(",", $type);

      $tpl = get_template("shop_product_" . $T[0]);

      $wiersz_ile = ((test_int($T[1])) ? $T[1] : 3);
      $i          = 0;
      $ret        = '';
      while ($T = $db->fetch($nr)) {
        $B = $DB = array();
        if ($_GLOBAL['langid'] == 1) {
          $B["{NAZWA}"] = s($T["pr_nazwa"]);
          $B["{ALT}"]   = h($T["pr_nazwa"]);
        } else if ($_GLOBAL['langid'] == 2) {
          $B["{NAZWA}"] = s($T["description"]);
          $B["{ALT}"]   = h($T["description"]);
        }


        $T['pr_plik'] = str_replace('http:////', '', $T['pr_plik']);
        // $PR['pr_plik'] = str_replace('http://gomez.pl//', '', $PR['pr_plik']);
        if (substr($T["pr_plik"], 0, 8) == "http:///") {
          $T["pr_plik"] = substr($T["pr_plik"], 8);
        }

        if (substr($T["pr_plik"], 0, 4) == "http") {
          $T["pr_plik"] = substr($T["pr_plik"], 8);
          $T["pr_plik"] = substr($T["pr_plik"], strpos($T["pr_plik"], "/") + 1);
        }

        $T["pr_plik"] = str_replace(array("/x/", "/m/"), array("/s/", "/s/"), str_replace($_GLOBAL["page_url"] . "/", "", $T["pr_plik"]));

        $B["{IMG}"]       = ($T["pr_plik"] != "" and file_exists($T["pr_plik"])) ? str_replace("/s/", "/" . $type . "/", $T["pr_plik"]) : 'images/nofoto_' . $type . '.png';
        $B['{IMG}']       = $_GLOBAL['page_url'] . $B['{IMG}'];
        $B["{CENA}"]      = ($T["pr_cena_w_brutto"] < $T["pr_cena_a_brutto"] ? '<strike>' . number_format($T["pr_cena_a_brutto"], 2, ".", "") . '</strike> ' . number_format($T["pr_cena_w_brutto"], 2, ".", "") : number_format($T["pr_cena_w_brutto"], 2, ".", ""));
        $B["{PRODUCENT}"] = s($T["pd_nazwa"]);
        $B['{URL}']       = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/produkt/' . $T['pr_id'] . '/' . conv2file($T["pd_nazwa"]) . '/' . conv2file($T['pr_nazwa']) . '/';

        $B['{PRID}'] = $T['pr_id'];

        $ret .= chr(10) . get_template($tpl, $B, $DB, 0);
      }

      return $ret;
    }


    function get_tytul() {
      return $this->WYNIK["tytul"];
    }


    /**
     * Funkcja uzupełnia metatagi o dane bieżącej strony
     *
     * @param string $str Oryginalna wartość TAGu
     * @param string $typ Typ TAGu
     *
     * @return string Nowa wartość TAGu
     */
    function get_meta($str, $typ) {
      global $_GLOBAL;

      switch ($typ) {
        case "title":
          if ($this->id != 1) return $_GLOBAL["title"] . " - " . $this->get_tytul(); else return $str;
        case "description":
          if ($this->id != 1) return $_GLOBAL["description"] . " - " . $this->get_tytul(); else return $str;
      }

      return $str;
    }
  }

?>