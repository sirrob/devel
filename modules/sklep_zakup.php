<?php
  /**
   * Klasa obsługi zakupu
   *
   * @author    Michał Bzowy
   * @copyright Copyright (c) 2008, Michał Bzowy
   * @since     2008-08-04 12:36:48
   * @link      www.imt-host.pl
   */

  /**
   * Autoryzacja
   */
//   include_once '../inpost-api/inpost/inpost.php';
//   include_once '../inpost-api/inpost/functions.php';
   require_once ('../inpost-api/inpost/inpost.php');
  if (!defined('SMDESIGN')) {
    die("Hacking attempt");
  }

  /**
   * Klasa obsługi zakupu
   *
   */
  class sklep_zakup extends mb {

    const FREE_DHL_DELIVERY = 1364515200; // 29.03.2013

    function sklep_zakup() {
      global $Cuzytkownik, $Cbasket, $_GLOBAL, $L, $db;

        debug("JEDEN");

      $this->mode = "prn_adres";

      if (isset($_POST['do_platnosc'])) $this->WYNIK['fo']['do_platnosc'] = $_POST['do_platnosc'];

      // przejście z koszyka, trzeba go zakutalizować i zapisać sposób
      if (isset($_POST['do_id'])) $_SESSION[SID]["zakup"]["do_id"] = $_POST["do_id"];


      if (isset($_POST["a"]) and $_POST["a"] == "koszyk") {
        $ret = false;
        foreach ($_POST as $key => $val) {
          if (substr($key, 0, 7) == "ko_ile_") {
            $A = explode("_", substr($key, 7));

            if ($_POST["ko_usun_" . substr($key, 7)] == "on" or !$val) {
              $Cbasket->usun($A[0], $A[1], "produkt");
            } else {
              if (!$Cbasket->dodaj($A[0], $A[1], $val, 'produkt', "replace")) {
                $ret = true;
              }
            }
          }
        }

        // apply discount code
        if ((isset($_POST['submit_validate_code'])) AND (isset($_POST['discount_code'])) AND (trim($_POST['discount_code']) != '')) {

          $dcv = $this->checkIfDiscountCodeIsValid($_POST['discount_code']);
          if ($dcv) {
            $_SESSION[SID]['discount_code'] = $_POST['discount_code'];
            $dcu                            = $this->checkIfDiscountCodeIsUsed($_POST['discount_code'], $Cuzytkownik->id);
            if ($dcu) {
              $_SESSION[SID]['discount_code_used'] = true;
            } else {
              $_SESSION[SID]['discount_code_used'] = false;
            }
          } else {
            $_SESSION[SID]['discount_code_fault'] = true;
          }
        }
        // reset discount code
        if (isset($_POST['submit_reset_code'])) {
          $_SESSION[SID]['discount_code'] = "";
          unset($_SESSION[SID]['discount_code']);
          unset($_SESSION[SID]['discount_code_gc']);
        }


        if (isset($_POST['s_w'])) $_POST['a'] = 'koszyk';
        if (isset($_POST['s_'])) $_POST['a'] = 'zakup';

        // Przekierowanie do koszyka
        if ((isset($_POST['a'])) && ($_POST['a'] == 'koszyk')) {
          redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/");
        }

        if ($ret) redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/error/");
        if (is_array($Cbasket->get_koszyk_wartosc()) and $_POST["do_id"] != "-1") {
          $_SESSION[SID]["zakup"]["do_id"] = $_POST["do_id"];
          //jeśli klient nie zalogowany to przekierowanie do logowania
          //if(!$Cuzytkownik->is_klient()) redirect("uzytkownik.htm?url=zakup");
          if (!$Cuzytkownik->is_klient()) redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/uzytkownik/selection/");

        } else
          if ((isset($_GET['a'])) && ($_GET['a'] == 'bez')) {
          } else {
            redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/");
          }

        if (is_array($_SESSION[SID]["zakup"]))
          foreach ($_SESSION[SID]["zakup"] as $k => $v) $this->WYNIK["fo"][$k] = $v;

        /**
         * Przejście z danych odbiorcy i formy płatności
         */
      } elseif (isset($_POST["a"]) and $_POST["a"] == "adres" and ($Cuzytkownik->is_klient() or ($_GET['a'] == 'bez'))) {
        if (isset($_POST['s_w'])) {
          redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/");
        }
        $this->WYNIK["fo"] = get_post("notag");

        if ($this->WYNIK["fo"]["do_odb"] == 2) {

          if ($this->WYNIK["fo"]["do_nazwa"] == "") $ERR[] = $L['{T_WPROWADZ_IMIE_ODBIORCY}'];
          if ($this->WYNIK["fo"]["do_nazwa2"] == "") $ERR[] = $L['{T_WPROWADZ_NAZWISKO_ODBIORCY}'];
          if ($this->WYNIK["fo"]["do_miasto"] == "") $ERR[] = $L['{T_WPROWADZ_MIASTO}'];
          if ($this->WYNIK["fo"]["do_adres"] == "") $ERR[] = $L['{T_WPROWADZ_ULICE_I_NUMER}'];
          if ($this->WYNIK["fo"]["do_kod"] == "") $ERR[] = $L['{T_WPROWADZ_KOD_POCZTOWY}'];
          if ($this->WYNIK["fo"]["dot_email"] == "") $ERR[] = $L['{T_WPROWADZ_ADRES_EMAIL}'];
          if ($this->WYNIK["fo"]["dot_tel"] == "") $ERR[] = $L['{T_WPROWADZ_NUMER_TELEFONU}'];
          if (!isset($this->WYNIK["fo"]["dot_reg"])) $ERR[] = "Musisz zaakceptować warunki Regulaminu !";//$L['{T_WPROWADZ_NUMER_TELEFONU}'];

            debug("START");
            //debug($ERR[]);
            debug("STOP");
        }

        if (empty($this->WYNIK['fo']['do_platnosc'])) {
          $ERR[] = '  ';
        }

        if (empty($this->WYNIK['fo']['do_id'])) {
          $ERR[] = " &nbsp; ";
        }

        if (!isset($this->WYNIK["fo"]["do_platnosc"])) {
          $ERR[] = $L['{T_WYBIERZ_SPOSOB_PLATNOSCI}'];
        }


        if (!isset($ERR)) {
          $_SESSION[SID]["zakup"]["do_odb"]      = $this->WYNIK["fo"]["do_odb"];
          $_SESSION[SID]["zakup"]["do_nazwa"]    = $this->WYNIK["fo"]["do_nazwa"];
          $_SESSION[SID]["zakup"]["do_nazwa2"]   = $this->WYNIK["fo"]["do_nazwa2"];
          $_SESSION[SID]["zakup"]["do_miasto"]   = $this->WYNIK["fo"]["do_miasto"];
          $_SESSION[SID]["zakup"]["do_kraj"]     = $this->WYNIK["fo"]["do_kraj"];
          $_SESSION[SID]["zakup"]["do_kod"]      = $this->WYNIK["fo"]["do_kod"];
          $_SESSION[SID]["zakup"]["do_adres"]    = $this->WYNIK["fo"]["do_adres"];
          $_SESSION[SID]["zakup"]["do_uwagi"]    = $this->WYNIK["fo"]["do_uwagi"];
          $_SESSION[SID]["zakup"]["do_platnosc"] = $this->WYNIK["fo"]["do_platnosc"];
          $_SESSION[SID]["zakup"]["p24_metoda"]  = $this->WYNIK["fo"]["p24_metoda"];
          $_SESSION[SID]["zakup"]["dot_email"]   = $this->WYNIK["fo"]["dot_email"];
          $_SESSION[SID]["zakup"]["dot_tel"]     = $this->WYNIK["fo"]["dot_tel"];
//          $_SESSION[SID]["zakup"]["dot_reg"]     = $this->WYNIK["fo"]["dot_reg"];

          $this->WYNIK["fo"]["do_id"] = $_SESSION[SID]["zakup"]["do_id"];
          $this->mode                 = "prn_podsumowanie";

        } elseif (isset($_GET["a"]) and $_GET["a"] == "mess") {
          $this->WYNIK["mess"] = (int)$_GET["v"];
        } else {
          $this->MESS_ERR[]           = $L['{T_WYSTAPILY_BLEDY_PRZY_WYPELNIANIU__}'] . ":<br/>" . join("<br/>", $ERR);
          $this->WYNIK["fo"]["do_id"] = $_SESSION[SID]["zakup"]["do_id"];
        }

        /**
         * Zatwierdzenie zamówienia - zapisanie do bazy i płatność
         */
      } elseif (isset($_POST["a"]) and $_POST["a"] == "podsumowanie" and ($Cuzytkownik->is_klient() or ($_GET['a'] == 'bez'))) {

        if (isset($_POST['s1'])) {
          redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/");
        } else {
          if (is_array($_SESSION[SID]["zakup"]))
            foreach ($_SESSION[SID]["zakup"] as $k => $v) $this->WYNIK["fo"][$k] = $v;

          $this->zapisz();
          $this->mode = "prn_platnosc";
        }
      } elseif (isset($_GET["a"]) and $_GET["a"] == "mess") {
        $this->WYNIK["mess"] = (int)$_GET["v"];
        $this->mode          = "prn_message";

        /**
         * Wynik otrzymany z Przelewy24
         */
      } elseif (isset($_GET["a"]) and $_GET["a"] == "p24") {
        $this->mode = "prn_message";
        if ($_GET["v"] == "error") {
          $this->WYNIK["mess"] = 2;
        } else {
          if ($this->przelewy24_wynik($_POST)) $this->WYNIK["mess"] = 3;
          else $this->WYNIK["mess"] = 4;
        }
      } elseif (!is_array($Cbasket->get_koszyk_wartosc()) or !$_SESSION[SID]["zakup"]["do_id"]) {
        if ((isset($_GET['a'])) && ($_GET['a'] == 'bez')) {
        } else
          redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/");
      } else {
        if (is_array($_SESSION[SID]["zakup"]))
          foreach ($_SESSION[SID]["zakup"] as $k => $v) $this->WYNIK["fo"][$k] = $v;

        //echo '<pre>fo: ' . print_r($this->WYNIK['fo'],true) . '</pre>';
      }

      //echo '<pre>$_SESSION[SID]["zakup"]: ' . print_r($_SESSION[SID]["zakup"],true) . '</pre>';
       debug("DWA");
    }


    /**
     * Sprawdzenie poprawnosci/istnienia kodu rabatowego
     *
     * @param string kod rabatowy
     *
     * @return boolen Wynik weryfikacji
     */


    function checkIfDiscountCodeIsValid($code) {
      global $db;

        debug("TUTAJ2!!!!");

      $r = $db->onerow("SELECT * FROM mm_rabat_code WHERE code = '$code' AND active = 1 AND (active_start IS NULL OR active_start < NOW()) AND (active_stop IS NULL OR active_stop > NOW())");
      $r2 = $db->onerow("SELECT * FROM gift_ticket WHERE code = '$code'");
      if (is_array($r) AND (count($r) > 0)) {
        return true;
      }elseif(is_array($r2) AND (count($r2) > 0)) {
          //$_SESSION[SID]['gift_code'] = $_POST['discount_code'];
          return true;
      }
      else {
        return false;
      }
    }

    function checkIfDiscountCodeIsUsed($code, $uid) {
      global $db;

        debug("TUTAJ3!!!!");

      if (empty($uid)) {
        return false;
      }

      $r = $db->onerow("SELECT * FROM mm_rabat_code WHERE code = '$code' AND amount > 0 AND active = 1 AND (active_start IS NULL OR active_start < NOW()) AND (active_stop IS NULL OR active_stop > NOW())");
      if (is_array($r) AND (count($r) > 0)) {
        $r2 = $db->onerow("SELECT * FROM mm_rabat_code_user WHERE code = " . $r['id'] . " AND user = " . $uid);
        if (is_array($r2) AND ($r2['amount'] >= $r['amount'])) {
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }

    /**
     * Sprawdzenie poprawnosci/istnienia kodu rabatowego oraz czy kod juz zostal wykorzystany
     * (w przypadku gdy kod jest jednokrotnego uzytku)
     *
     * @param string kod rabatowy
     *
     * @return boolen Wynik weryfikacji
     */
    function checkIfDiscountCodeIsUser($code, $uid) {
      global $db;

        debug("TUTAJ4!!!!");

      $r = $db->onerow("SELECT * FROM mm_rabat_code WHERE code = '$code' AND amount != 0 AND active = 1 AND (active_start IS NULL OR active_start < NOW()) AND (active_stop IS NULL OR active_stop > NOW())");
      if (is_array($r) AND (count($r) > 0)) {
        return true;
      } else {
        return false;
      }
    }


    /**
     * Weryfikacja wyniku z Przelewy24
     *
     * @param array $ARG Informacje otrzymane z Przelewy24
     *
     * @return boolen Wynik weryfikacji
     */
    function przelewy24_wynik($ARG) {
      global $db;

        debug("TUTAJ5!!!!");

      $X  = explode(",", $ARG["p24_session_id"]);
      $za = (int)$X[0];
      $T  = $db->onerow("select za_do_koszt+za_wartosc_brutto za_wartosc, za_status from " . dn("sklep_zamowienie") . " where za_id=" . $za);

      csv_log("files/log/przelewy24_wynik-all.log", 'Argumenty przelewy24_wynik: ' . join('; ', $ARG));

      $PL = $db->onerow("select pl_parametr from " . dn("sklep_platnosc") . " where pl_id=3");
      if (is_array($T) and $ARG["p24_kwota"] == number_format($T["za_wartosc"] * 100, 0, "", "")) {
        $P[] = "p24_id_sprzedawcy=" . $PL["pl_parametr"];
        $P[] = "p24_session_id=" . $ARG["p24_session_id"];
        $P[] = "p24_order_id=" . $ARG["p24_order_id"];
        $P[] = "p24_kwota=" . $ARG["p24_kwota"];
        $RET = explode(chr(13) . chr(10), call_post("https://secure.przelewy24.pl/transakcja.php", $P));

        //csv_log("files/log/przelewy24_wynik-all.log",$ARG);
//            csv_log("files/log/przelewy24_wynik-all.log", 'Wysłane do p24: ' . join('; ', $P));
//            csv_log("files/log/przelewy24_wynik-all.log", 'Odp z p24: ' . join('; ' . $RET));


        if ($RET[1] == "TRUE") {
          if ($T["za_status"] == 0) {
            $q = "update " . dn("sklep_zamowienie") . " set za_data_update=" . time() . ",za_pl_id=3,za_status='1',za_parametr=concat(if(isnull(za_parametr),'',za_parametr),'|p24=" . $ARG["p24_order_id_full"] . "|p24_data=" . time() . "') where za_id=" . $za;

            csv_log("files/log/przelewy24_wynik-all.log", 'Zapytanie konczace: ' . $q);

            $db->query($q);
//potencjalne miejsce aby w przyszłości w tym miejscu przenieść naliczanie punktów GC
            zamowienie_mail($za);
          }

          return true;
        } else {
          csv_log("files/log/przelewy24_wynik.log", "Błąd weryfikacji zamówienia nr " . $za . ": " . $RET[2]);
        }
      } else {
        csv_log("files/log/przelewy24_wynik.log", "Błąd weryfikacji zamówienia nr " . $za . ": brak takiego zamówienia lub niezgodna kwota (" . number_format($ARG["p24_kwota"] / 100, 2, ".", ""));
      }

      return false;
    }


    function checkDHLFreeDelivery($doID) {
      if (($doID == 3 OR $doID == 4) AND (sklep_zakup::FREE_DHL_DELIVERY > 0) AND (time() < sklep_zakup::FREE_DHL_DELIVERY)) {
        return true;
      } else {
        return false;
      }
    }

    /**
     * Zachowanie zamówienia w bazie danych
     * TODO: zapisać zamówienie, zapisać pozycje zamówienia, przygotować dokumenty rezerwacji.
     */
    function zapisz() {
      global $db, $Cuzytkownik, $_GLOBAL, $Cbasket;

        debug("TUTAJ6!!!!");

      $punkt = 0;

      if ($this->WYNIK["fo"]["dot_tel"])
          echo "zaakceptowano regulamin";
      $UZ = $db->onerow("select * from " . dn("kontrahent") . " where ko_id=" . (($Cuzytkownik->is_klient()) ? (int)$Cuzytkownik->get_id() : 0));
      $PL = $db->onerow("select * from " . dn("sklep_platnosc") . " where pl_id=" . (int)$this->WYNIK["fo"]["do_platnosc"]);
      $DO = $db->onerow("select * from " . dn("sklep_dostawa") . " where do_id=" . (int)$this->WYNIK["fo"]["do_id"]);
        debug("Zamówienie zapis jestem tu : nr1");
      $q = "insert into " . dn("sklep_zamowienie") . " set ";
      $q .= "za_data_in=" . time() . ",";
      $q .= "za_data_update=" . time() . ",";
      $q .= "za_ko_id=" . (($Cuzytkownik->is_klient()) ? (int)$Cuzytkownik->get_id() : 0) . ",";
      $q .= "za_ko_nazwa='" . $UZ["ko_nazwa"] . "',";
      $q .= "za_ko_nip='" . $UZ["ko_nip"] . "',";
      $q .= "za_ko_miasto='" . $UZ["ko_miasto"] . "',";
      $q .= "za_ko_kod='" . $UZ["ko_kod"] . "',";
      $q .= "za_ko_kraj='" . $UZ["ko_kraj"] . "',";
      $q .= "za_ko_ulica='" . $UZ["ko_ulica"] . "',";
      $q .= "za_ko_ulica_dom='" . $UZ["ko_ulica_dom"] . "',";
      $q .= "za_ko_ulica_lok='" . $UZ["ko_ulica_lok"] . "',";
      $q .= "za_lang='" . $_GLOBAL["lang"] . "',";


      // dodatkowa notka przy zamowieniu w przypadku zakupu z kodem rabatowym
      $purchaseWithDiscount = "";
      if (isset($_SESSION[SID]['discount_code'])) {
        $q2   = "select * from " . dn('rabat_code') . " where code='" . $_SESSION[SID]['discount_code'] . "' and active=1 and (active_start < NOW() or active_start is null) and (active_stop > NOW() or active_stop is null)";
        $nr2  = $db->query($q2);
        $row2 = $db->fetch($nr2);

        if (is_array($row2)) {
          $purchaseWithDiscount = "\nZakup z kodem rabatowym: " . $row2['code']." ";
        }
      }
      // ---


      $q .= "za_uwagi='" . a($this->WYNIK["fo"]["do_uwagi"]) . $purchaseWithDiscount . "',"; // . $this->WYNIK['fo']['dot_email'] . ' ' . $this->WYNIK['fo']['dot_tel']
      if ($this->WYNIK["fo"]["do_odb"] == 2) {
        $q .= "za_do_nazwa='" . a($this->WYNIK["fo"]["do_nazwa"] . ' ' . $this->WYNIK["fo"]["do_nazwa2"]) . "',";
        $q .= "za_do_kraj='" . a($this->WYNIK["fo"]["do_kraj"]) . "',";
        $q .= "za_do_miasto='" . a($this->WYNIK["fo"]["do_miasto"]) . "',";
        $q .= "za_do_kod='" . a($this->WYNIK["fo"]["do_kod"]) . "',";
        $q .= "za_do_adres='" . a($this->WYNIK["fo"]["do_adres"]) . "',";
      } else {
        $q .= "za_do_nazwa='" . $UZ["ko_nazwa"] . "',";
        $q .= "za_do_kraj='" . $UZ["ko_kraj"] . "',";
        $q .= "za_do_miasto='" . $UZ["ko_miasto"] . "',";
        $q .= "za_do_kod='" . $UZ["ko_kod"] . "',";
        $q .= "za_do_adres='" . $UZ["ko_ulica"] . " " . $UZ["ko_ulica_dom"] . (($UZ["ko_ulica_dom"] != "" and $UZ["ko_ulica_lok"] != "") ? "/" : "") . $UZ["ko_ulica_lok"] . "',";
      }

      $q .= "za_pl_id=" . (int)$this->WYNIK["fo"]["do_platnosc"] . ",";
      $q .= "za_do_id=" . (int)$this->WYNIK["fo"]["do_id"] . ","; //$B["{WARTOSC_DO}"] = number_format($DO["do_koszt"],2,".","").'';
      //sprawdzić znaczenie zmiennej $DO["do_platnosc_odbior"] czy trzeba w to miejsce przenosić naliczanie punktów GC
      if ($DO["do_platnosc_odbior"]) $q .= "za_status=1,";
      else $q .= "za_status=0,";

      $za_q = $q;

      if ($_GLOBAL["shop_stan_kontrola"] == "tak") {
        // get basket products - meta products changes
        $q              = 'select * from ' . dn("sklep_koszyk") . ' where typ=\'produkt\' and session_id=\'' . session_id() . '\'';
        $basketProducts = $db->get_all($q);
        $ST             = array();
        foreach ($basketProducts as $item) {
          $product                            = $db->onerow('SELECT pr_indeks FROM mm_produkt WHERE pr_id = ' . $item['pr_id']);
          $index                              = $product['pr_indeks'];
          $index[10]                          = '%';
          $query                              = '  SELECT SUM(ms_ilosc) ms_ilosc
                          FROM ' . dn("magazyn_stan") . '
                          LEFT JOIN ' . dn('magazyn') . ' on ms_ma_id = ma_id
                          LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                          WHERE ms_ilosc > 0
                            AND ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ')
                            AND pr_indeks LIKE \'' . $index . '\' and ms_at_id = ' . $item['at_id'] . '
                          ORDER BY pr_indeks, ma_nazwa';
          $ms                                 = $db->onerow($query);
          $ST[$item['pr_id']][$item['at_id']] = $ms['ms_ilosc'];
        }
        // ---
        // $q  = "select ms_pr_id, ms_at_id, sum(ms_ilosc) ms_ilosc from "  . dn("magazyn_stan") . " ";
        // $q .= "where (ms_pr_id, ms_at_id) in (select pr_id, at_id from " . dn("sklep_koszyk") . " ";
        // $q .= "where typ='produkt' and session_id='".session_id()."') group by ms_pr_id, ms_at_id";
        // $nr = $db->query($q);
        // while($T = $db->fetch($nr)) $ST[$T[0]][$T[1]] = $T[2];
      }

      $q = "select ko.*,spr.pr_nazwa,pr_cena_w_netto,pr_cena_w_brutto,pr_cena_a_brutto,pr_jm,pr_kod_kreskowy,pr_pd_id,pr_indeks,pr_kolor,pr_punkt,va_nazwa,va_stawka,at_nazwa from " . dn("sklep_koszyk") . " ko ";
      $q .= "left join " . dn("produkt") . " pr on ko.pr_id=pr.pr_id ";
      $q .= "left join " . dn("sklep_produkt") . " spr on ko.pr_id=spr.pr_id ";
      $q .= "left join " . dn("produkt_atrybut") . " at on ko.at_id=at.at_id ";
      $q .= "left join " . dn("produkt_vat") . " va on pr.pr_va_id=va.va_id ";
      $q .= "where typ='produkt' and session_id='" . session_id() . "' group by pr_id, at_id";
      $nr = $db->query($q);

      $CENAO = array();
      while ($T = $db->fetch($nr)) {
        $LST[]              = $T;
        $CENAB[$T["pr_id"]] = $T["pr_cena_w_brutto"];
        $CENAN[$T["pr_id"]] = $T["pr_cena_w_netto"];

        $CENAOB[$T["pr_id"]] = $T["pr_cena_w_brutto"];
        $CENAON[$T["pr_id"]] = $T["pr_cena_w_netto"];

        $CENA2[$T["pr_id"]] = $T["pr_cena_a_brutto"];
        $CENA3[$T["pr_id"]] = $T["ilosc"];

        $IDS[$T["pr_id"]]    = $T["pr_id"];
        $BRANDS[$T["pr_id"]] = $T["pr_pd_id"];
        //tutaj dodać oblicznie punktów
      }

      // related product promotions
      $tmpFreeProductsPaid = array();
      $tmpFreeProductsFree = array();
      $rppName             = "";
      $rppCount            = 0;
      $rppValue            = 0;
      $rppType             = 0;
      $rppSum              = 0;
      $rppGC               = -1;
      $q                   = "SELECT * FROM mm_related_product_promotion WHERE active = 1 LIMIT 1";
      $row                 = $db->onerow($q);
      if (is_array($row)) {
        $rppName  = $row['name'];
        $rppValue = $row['value'];
        $rppType  = $row['type'];
        $rppGC    = $row['with_gc'];
        // paid products
        $paidProducts = array();
        $q            = "SELECT product_id FROM mm_related_product_promotion_products WHERE related_product_promotion_id =" . $row['id'] . " AND type = 0";
        $nr           = $db->query($q);
        while ($r = $db->fetch($nr)) {
          $paidProducts[] = $r['product_id'];
        }
        // debug($paidProducts);

        // related product promotions
        $freeProducts = array();
        $q            = "SELECT product_id FROM mm_related_product_promotion_products WHERE related_product_promotion_id =" . $row['id'] . " AND type = 1";
        $nr           = $db->query($q);
        while ($r = $db->fetch($nr)) {
          $freeProducts[] = $r['product_id'];
        }
        // debug($freeProducts);

        foreach ($LST as $T) {
          if (in_array($T['pr_id'], $paidProducts)) {
            $tmpFreeProductsPaid[$T['pr_id']] = (array_key_exists($T['pr_id'], $tmpFreeProductsPaid)) ? ($tmpFreeProductsPaid[$T['pr_id']] + 1) : 1;
          }
          if (in_array($T['pr_id'], $freeProducts)) {
            $tmpFreeProductsFree[$T['pr_id']] = (array_key_exists($T['pr_id'], $tmpFreeProductsFree)) ? ($tmpFreeProductsFree[$T['pr_id']] + 1) : 1;
          }
        }

        $paidSum = array_sum($tmpFreeProductsPaid);
        $freeSum = array_sum($tmpFreeProductsFree);
        if ($paidSum == 0) {
          $rppCount = 0;
        } elseif ($paidSum < $freeSum) {
          $rppCount = $paidSum;
        } else {
          $rppCount = $freeSum;
        }

        // debug($tmpFreeProductsPaid);
        // debug($tmpFreeProductsFree);
      }
      // --- to be continued


      // applying discount codes
      $CENADC = array();
      if (isset($_SESSION[SID]['discount_code'])) {
        $appliedProducts = array();
        $q               = "select * from " . dn('rabat_code') . " where code='" . $_SESSION[SID]['discount_code'] . "' and active=1 and (active_start < NOW() or active_start is null) and (active_stop > NOW() or active_stop is null)";
        $nr              = $db->query($q);
        $row             = $db->fetch($nr);

        if (is_array($row)) {
          $type                                = $row['type'];
          $amount                              = $row['amount'];
          $_SESSION[SID]['discount_code_gc']   = $row['gc'];
          $_SESSION[SID]['discount_code_name'] = $row['name'];

          // sprawdzenie czy kod tylko dla uprawnionych i jesli tak, czy uzytkownik jest uprawniony
          $onlyForAllowedUsers = false;
          $userAllowedForCode  = false;
          $q                   = "SELECT * FROM " . dn('rabat_code_allowed_user') . " WHERE code_id = " . $row['id'];
          $allowed             = $db->get_all($q);
          if (count($allowed) > 0) {
            $onlyForAllowedUsers = true;

            // sprawdzenie czy uzytkownik jest uprawniony
            $q           = "SELECT * FROM " . dn('rabat_code_allowed_user') . " WHERE code_id = " . $row['id'] . " AND user_id = " . $Cuzytkownik->get_id();
            $allowedUser = $db->get_all($q);
            if (count($allowedUser) > 0) {
              $userAllowedForCode = true;
            }
          }
          // ---

          // sprawdzenie czy wykorzystal juz kod
          $userCodeUsed = 0;
          if ($amount) {
            $q            = "SELECT * FROM " . dn('rabat_code_user') . " WHERE code='" . $row['id'] . "' AND user = " . $Cuzytkownik->get_id();
            $userRow      = $db->onerow($q);
            $userCodeUsed = isset($userRow['amount']) ? $userRow['amount'] : 0;
          }
          // ---


          if (($onlyForAllowedUsers AND $userAllowedForCode) OR (!$onlyForAllowedUsers AND (($amount > $userCodeUsed) OR ($amount == 0)))) {
            // get products with code discount
            $bprods = array();
            foreach ($LST as $T) $bprods[] = $T["pr_id"];
            $q     = "select DISTINCT * from " . dn('rabat_code_product') . " where cid=" . $row['id'] . " AND pid IN (" . join(",", $bprods) . ")";
            $nr    = $db->query($q);
            $prods = array();
            while ($p = $db->fetch($nr)) {
              $prods[] = $p;
            }


            // produkty zwiazane z DC
            $dcpIDS = array();
            foreach ($prods as $value) $dcpIDS[] = $value['pid'];


            // tutaj obliczanie ceny dla gomezclub jesli laczy sie z kodem rabatowym
            // pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości koszyka
            if (($Cuzytkownik->is_gomezclub()) AND ($row['gc'] == 1)) {
              $pkt = 0;
              foreach ($CENA2 as $k => $v) $pkt += $v * $CENA3[$k];
              //$rab = $Cuzytkownik->getPercent2($pkt);
              $rab = $Cuzytkownik->getPercent();
              foreach ($CENA2 as $k => $v) {
                // efektywny rabat: albo GC albo rabat LJ (108)
                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                debug('rabat: ' . $effectiveRab);
                if ($CENAB[$k] < $v) {
                  //Przecena - naliczyc połowę rabatu Gomez Club
                  $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab / 2) / 100;
                  $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab / 2) / 100;
                } else {
                  //Brak Przeceny - naliczyć rabat klienta
                  $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab) / 100;
                  $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab) / 100;
                }
              }
            } elseif (($Cuzytkownik->is_gomezclub()) AND ($row['gc'] == 0)) {
              // jesli sie nie laczy z GC to sprobowac naliczyc GC dla produktow nie wchodzacych DC

              // produkty nie zwiazane z DC w zaleznosci od tego czy wszysktie czy jakies wybrane
              if ($row['all_products'] == 1) {
                $ndcpIDS = array();
              } else {
                $ndcpIDS = array_diff($bprods, $dcpIDS);
              }

              $pkt = 0;
              foreach ($CENA as $k => $v) {
                if (in_array($k, $ndcpIDS)) {
                  $pkt += $v * $CENA3[$k];
                }
              }

              $rab = $Cuzytkownik->getPercent();
              foreach ($CENA2 as $k => $v) {
                // efektywny rabat: albo GC albo rabat LJ (108)
                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                debug('rabat: ' . $effectiveRab);
                if (in_array($k, $ndcpIDS)) {
                  if ($CENAB[$k] < $v) {
                    //Przecena - naliczyc połowę rabatu Gomez Club
                    $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab / 2) / 100;
                    $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab / 2) / 100;
                  } else {
                    //Brak Przeceny - naliczyć rabat klienta
                    $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab) / 100;
                    $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab) / 100;
                  }
                }
              }
            }
            // ---


            // get products with code discount
            $bprods = array();
            foreach ($LST as $T) $bprods[] = $T["pr_id"];
            $q     = "select DISTINCT * from " . dn('rabat_code_product') . " where cid=" . $row['id'] . " AND pid IN (" . join(",", $bprods) . ")";
            $nr    = $db->query($q);
            $prods = array();
            while ($p = $db->fetch($nr)) $prods[] = $p;

            switch ($type) {
              case 1: // %
                if ($row['all_products'] != 0) {
                  foreach ($LST as $T) {
                    if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                      $CENAB[$T["pr_id"]] = $CENAB[$T["pr_id"]] - round($CENAB[$T["pr_id"]] * $row['rabat']) / 100;
                      // $CENAN[$T["pr_id"]] = $CENAN[$T["pr_id"]] - round($CENAN[$T["pr_id"]]*$row['rabat'])/100;
                      $CENAN[$T["pr_id"]]           = round($CENAB[$T["pr_id"]] / (1 + $T['va_stawka'] / 100) * 100) / 100;
                      $appliedProducts[$T["pr_id"]] = true;
                    }
                  }
                } else {
                  foreach ($LST as $T) {
                    if (in_array($T['pr_id'], $dcpIDS)) {
                      if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                        $CENAB[$T["pr_id"]] = $CENAB[$T["pr_id"]] - round($CENAB[$T["pr_id"]] * $row['rabat']) / 100;
                        // $CENAN[$T["pr_id"]] = $CENAN[$T["pr_id"]] - round($CENAN[$T["pr_id"]]*$row['rabat'])/100;
                        $CENAN[$T["pr_id"]]           = round($CENAB[$T["pr_id"]] / (1 + $T['va_stawka'] / 100) * 100) / 100;
                        $appliedProducts[$T["pr_id"]] = true;
                      }
                    }
                  }
                }
                break;
              case 2: // pln
                if ($row['all_products'] != 0) {
                  foreach ($LST as $T) {
                    if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                      $CENAB[$T["pr_id"]] = $CENAB[$T["pr_id"]] - $row['rabat'];
                      $CENAN[$T["pr_id"]] = round($CENAB[$T["pr_id"]] / (1 + $T['va_stawka'] / 100) * 100) / 100;
                      // $CENAN[$T["pr_id"]] = $CENAN[$T["pr_id"]] - $row['rabat'];
                      $appliedProducts[$T["pr_id"]] = true;
                    }
                  }
                } else {
                  foreach ($LST as $T) {
                    if (in_array($T['pr_id'], $dcpIDS)) {
                      if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                        $CENAB[$T["pr_id"]] = $CENAB[$T["pr_id"]] - $row['rabat'];
                        $CENAN[$T["pr_id"]] = round($CENAB[$T["pr_id"]] / (1 + $T['va_stawka'] / 100) * 100) / 100;
                        // $CENAN[$T["pr_id"]] = $CENAN[$T["pr_id"]] - $row['rabat'];
                        $appliedProducts[$T["pr_id"]] = true;
                      }
                    }
                  }
                }
                break;
            }
          } else { // nie lapie sie na kod rabatowy, wiec jedziemy po GC
            //tutaj obliczanie ceny dla gomezclub
            //pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości korzyka
            if ($Cuzytkownik->is_gomezclub()) {
              $pkt = 0;
              foreach ($CENA2 as $k => $v) $pkt += $v * $CENA3[$k];
              //$rab = $Cuzytkownik->getPercent2($pkt);
              $rab = $Cuzytkownik->getPercent();
              foreach ($CENA2 as $k => $v) {
                // efektywny rabat: albo GC albo rabat LJ (108)
                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                debug('rabat: ' . $effectiveRab);
                if ($CENAB[$k] < $v) {
                  //Przecena - naliczyc połowę rabatu Gomez Club
                  $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab / 2) / 100;
                  $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab / 2) / 100;
                } else {
                  //Brak Przeceny - naliczyć rabat klienta
                  $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab) / 100;
                  $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab) / 100;
                }
              }
            }
          }
        }
      } else {
        //tutaj obliczanie ceny dla gomezclub
        //pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości korzyka
        if ($Cuzytkownik->is_gomezclub()) {
          $pkt = 0;
          foreach ($CENA2 as $k => $v) $pkt += $v * $CENA3[$k];
          //$rab = $Cuzytkownik->getPercent2($pkt);
          $rab = $Cuzytkownik->getPercent();
          foreach ($CENA2 as $k => $v) {
            // efektywny rabat: albo GC albo rabat LJ (108)
            $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
            debug('rabat: ' . $effectiveRab);
            if ($CENAB[$k] < $v) {
              //Przecena - naliczyc połowę rabatu Gomez Club
              $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab / 2) / 100;
              $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab / 2) / 100;
            } else {
              //Brak Przeceny - naliczyć rabat klienta
              $CENAB[$k] = $CENAB[$k] - round($CENAB[$k] * $effectiveRab) / 100;
              $CENAN[$k] = $CENAN[$k] - round($CENAN[$k] * $effectiveRab) / 100;
            }
          }
        }
      }
      // ---


      $rppCountTmp = $rppCount;
      $rppValueTmp = $rppValue;
      $ZP_Q        = array();
      $i           = 1;
      foreach ($LST as $T) {
        // zamiana metaproduktu na zwykly produkt
        $query     = "select pr_indeks from " . dn("produkt") . " where pr_id = " . $T['pr_id'];
        $product   = $db->onerow($query);
        $index     = $product['pr_indeks'];
        $index[10] = '%';
        $query     = 'SELECT DISTINCT pr_id, pr_indeks, pr_kod_kreskowy, SUM(ms_ilosc) as ms_ilosc
                      FROM ' . dn('produkt') . '
                      LEFT JOIN ' . dn("magazyn_stan") . ' ON ms_pr_id = pr_id
                      WHERE
                        ms_ilosc > 0 AND
                        pr_indeks LIKE \'' . $index . '\' AND
                        ms_at_id = ' . $T["at_id"] . ' AND
                        ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ')
                      GROUP BY pr_id
                      ORDER BY ms_ma_id';
        $products  = $db->get_all($query);
        $products  = array_reverse($products);

        $productIDS = array();
        foreach ($products as $value) {
          array_push($productIDS, $value['pr_id']);
        }

        $newT  = array();
        $count = $T['ilosc'];
        foreach ($products as $product) {
          $newTI = array();
          if ($product['ms_ilosc'] >= $count) {
            $newTI['pr_id']           = $product['pr_id'];
            $newTI['pr_indeks']       = $product['pr_indeks'];
            $newTI['pr_kod_kreskowy'] = $product['pr_kod_kreskowy'];
            $newTI['ilosc']           = $count;

            $newT[] = $newTI;
            break;
          } else {
            $newTI['pr_id']           = $product['pr_id'];
            $newTI['pr_indeks']       = $product['pr_indeks'];
            $newTI['pr_kod_kreskowy'] = $product['pr_kod_kreskowy'];
            $newTI['ilosc']           = $product['ms_ilosc'];
            $count -= $product['ms_ilosc'];
            $newT[] = $newTI;
          }
        }
        // ---


        $T["pr_cena_w_brutto"] = $CENAB[$T["pr_id"]];
        $T["pr_cena_w_netto"]  = $CENAN[$T["pr_id"]];

        if ($_GLOBAL["shop_stan_kontrola"] == "tak" and $T["ilosc"] > $ST[$T["pr_id"]][$T["at_id"]]) {
          redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/");
        }


        foreach ($newT as $V) {
          $q = "insert into " . dn("sklep_zamowienie_pozycja") . " set ";
          $q .= "zp_pr_id=" . $V["pr_id"] . ",";
          $q .= "zp_pr_nazwa='" . $T["pr_nazwa"] . "',";
          $q .= "zp_pr_kod_kreskowy='" . $V["pr_kod_kreskowy"] . "',";
          $q .= "zp_pr_indeks='" . $V["pr_indeks"] . "',";
          $q .= "zp_pr_jm='" . $T["pr_jm"] . "',";
          $q .= "zp_pr_vat_stawka='" . $T["va_nazwa"] . "',";
          $q .= "zp_pr_kolor='" . $T["pr_kolor"] . "',";
          $q .= "zp_at_id='" . $T["at_id"] . "',";
          $q .= "zp_at_nazwa='" . $T["at_nazwa"] . "',";
          $q .= "zp_cena_netto='" . $T["pr_cena_w_netto"] . "',";
          $q .= "zp_cena_brutto='" . $T["pr_cena_w_brutto"] . "',";
          $q .= "zp_ilosc='" . $V["ilosc"] . "',";
          $q .= "zp_kolejnosc=" . $i++ . ",";
          $q .= "zp_typ = 'produkt',";
          $ZP_Q[] = $q;
        }


        $tmpWorth_brutto = $T["ilosc"] * (($rppGC == 0) ? $CENAOB[$T["pr_id"]] : $T["pr_cena_w_brutto"]);
        $tmpWorth_netto  = $T["ilosc"] * (($rppGC == 0) ? $CENAON[$T["pr_id"]] : $T["pr_cena_w_netto"]);
        // related product promotions - applying part 1
        if (array_key_exists($T["pr_id"], $tmpFreeProductsFree)) {
          if ($rppType == 0) { // procentowo
            if ($T['ilosc'] > $rppCountTmp) {
              $tmp_brutto = $rppCountTmp * (($rppGC == 0) ? $CENAOB[$T["pr_id"]] : $T["pr_cena_w_brutto"]) * $rppValue / 100;
              $tmp_netto  = $rppCountTmp * (($rppGC == 0) ? $CENAON[$T["pr_id"]] : $T["pr_cena_w_netto"]) * $rppValue / 100;
            } else {
              $tmp_brutto = $T['ilosc'] * (($rppGC == 0) ? $CENAOB[$T["pr_id"]] : $T["pr_cena_w_brutto"]) * $rppValue / 100;
              $tmp_netto  = $T['ilosc'] * (($rppGC == 0) ? $CENAON[$T["pr_id"]] : $T["pr_cena_w_netto"]) * $rppValue / 100;
            }
            $rppCountTmp -= $T["ilosc"];
            $rppCountTmp = ($rppCountTmp < 0) ? 0 : $rppCountTmp;
          } elseif ($rppType = 1) { // kwotowo
            if (($T['ilosc'] * $T["pr_cena_w_brutto"]) > $rppValueTmp) {
              $tmp_brutto = $rppValueTmp;
              $tmp_netto  = $rppValueTmp / 1.23; // tu podmienic na stawke
            } else {
              $tmp_brutto = $T['ilosc'] * $T["pr_cena_w_brutto"];
              $tmp_netto  = $T['ilosc'] * $T["pr_cena_w_netto"];
            }
            $rppValueTmp -= $tmp_brutto;
          }
          $tmp_brutto = round($tmp_brutto * 100) / 100;
          $tmp_netto  = round($tmp_netto * 100) / 100;
          $rppSum_brutto += $tmp_brutto;
          $rppSum_netto += $tmp_netto;
          $tmpWorth_brutto = (($tmpWorth_brutto - $tmp_brutto) < 0) ? 0 : ($tmpWorth_brutto - $tmp_brutto);
          $tmpWorth_netto  = (($tmpWorth_netto - $tmp_netto) < 0) ? 0 : ($tmpWorth_netto - $tmp_netto);
        }
        // ---
        $suma_netto += $tmpWorth_netto; //$T["pr_cena_w_netto"] * $T["ilosc"];
        $suma_brutto += $tmpWorth_brutto; //$T["pr_cena_w_brutto"] * $T["ilosc"];
        $punkt += floor($T["pr_cena_w_brutto"] * $T["ilosc"]);
      }




        //tutaj sprawdz kod rabatowy i skoryguj sume na samym zamowieniu plus dodaj uwagi
        if(isset($_SESSION[SID]['discount_code'])){

            $_GLOBAL["db_pre"] = "";
            $r = $db->onerow("SELECT * FROM gift_ticket WHERE code = '".$_SESSION[SID]['discount_code']."'");
            if (is_array($r) AND (count($r) > 0)) {
                if($r['status']==1){
                    $gift = $r['value'];
                    $suma_brutto = $suma_brutto - $gift;
                    $punkt = floor($suma_brutto);
                    if($suma_brutto<0) $suma_brutto=0;
                    $position = strpos($za_q,"za_uwagi='");
                    $za_q=substr_replace($za_q,"RABAT na kod podarunkowy nr ".$r['code']." o wartosci ".$gift,$position+10,0);
                    //zaznacz, ze kod zostal wykorzystany
                    $codeQuery = "update gift_ticket set status = 2 WHERE id = ".$r['id'];
                    $db->query($codeQuery);
                }
            }
            $_GLOBAL["db_pre"] = "mm";
        }

        // mozna jeszcze wersje druga pt. oblicz procent kwoty od zamowienia i odpowiednio wstaw do kazdego produktu

      $za_q .= "za_wartosc_netto=" . $suma_netto . ",";
      //CZY MAM EDYTOWAC NETTO CZY BON NA TO WPLYWA???

      $za_q .= "za_wartosc_brutto=" . $suma_brutto . ", ";

      // dostawa DHL lub inna
      if ((int)$this->WYNIK["fo"]["do_id"] == 3 || (int)$this->WYNIK["fo"]["do_id"] == 4) {
        $zsuma  = 0;
        $znew   = 0;
        $zlevel = 0;
        //$zlevelvalue = array(0 => 200, 1 => 299.99);
        $zlevelvalue = array(0 => 200, 1 => 200);

        $query = "select ilosc, cena, pr_cena_w_brutto, pr_cena_b_brutto
                      from " . dn('sklep_koszyk') . "," . dn('produkt') . "
                      where " . dn('sklep_koszyk') . ".session_id='" . session_id() . "' and
                            " . dn('sklep_koszyk') . ".pr_id=" . dn('produkt') . ".pr_id;";

        $res = $db->query($query);
        while ($dane = $db->fetch($res)) {
          $zsuma += $dane['ilosc'] * $dane['cena'];
          if ($dane['pr_cena_w_brutto'] < ($dane['pr_cena_b_brutto'])) {
            if ($znew == 0) {
              $zlevel = 1;
              $znew++;
            }
          }
        };


        if ($zsuma < $zlevelvalue[$zlevel]) {
          if ($this->checkDHLFreeDelivery($DO["do_id"])) {
            $za_q .= "za_do_koszt=0 ";
          } else {
            $za_q .= "za_do_koszt=" . $DO["do_koszt"] . " ";
          }
        } else {
          $za_q .= "za_do_koszt=0 ";
        }
      } else {
        $za_q .= "za_do_koszt=" . $DO["do_koszt"] . " ";
      }



      // zapisywanie sklep_zamowienie
      $db->query($za_q);
      $za                = $db->insert_id();
      $this->WYNIK["za"] = $za;
        debug("Zamówienie zapis jestem tu : nr2");
      // zapisywanie pozycji zamowienia
      foreach ($ZP_Q as $q) {
        $db->query($q . " zp_za_id=" . $za);
      }

      // jesli sa naliczone punktu oraz sposob dostawy jest inny niz odbior wlasny
        debug("przed!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
      if (($punkt) AND ((int)$this->WYNIK["fo"]["do_id"] != 1)) {
          debug('IFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF');
        $T = $db->onerow("select ko_punkt from " . dn("sklep_kontrahent") . " where ko_id= " . (int)$Cuzytkownik->get_id());
        if (!is_array($T)) {
          $bylo = "-";
          if (((int)$Cuzytkownik->get_id() > 0) && ($Cuzytkownik->is_klient())) {
            if (($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub()) && ($Cuzytkownik->is_klient()) && ($Cuzytkownik->get_id() != 15988)) {
              $query = "select * from " . dn('sklep_kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
              $r     = $db->onerow($query);
              $query = "update " . dn('kontrahent') . " set
                                      ko_CLUB_SUMA=" . (int)$punkt . "
                                          where ko_id=" . $Cuzytkownik->get_id() . ";";
              //$db->query($query);

              if ($Cuzytkownik->getPercent() < $Cuzytkownik->getPercent2($punkt)) $poziom = $Cuzytkownik->getPercent2($punkt);
              else $poziom = $Cuzytkownik->getPercent($punkt);

              $query = "update " . dn('kontrahent') . " set
                                        ko_CLUB_POZIOM=" . $poziom . ",
                                        ko_CLUB_DATA=" . time() . ",
                                        ko_CLUB_SUMA=ko_CLUB_SUMA+" . (int)$punkt . "
                                    where ko_id=" . $Cuzytkownik->get_id() . ";";
              $db->query($query);

                // GSS-WWW GC
                $query = "update gomezclub_card set points = points + " . $punkt . ", discount = " . $poziom . " where customer_id = " . $Cuzytkownik->get_id();
                $db->query($query);
                // ---


              $Cuzytkownik->setCZ();
            }
          } else {
              debug("ELLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLSEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE");
            $query = "replace into " . dn('sklep_zamowienie_dane') . " (za_id, email, tel)
                        values (" . $this->WYNIK['za'] . ",'" . $this->WYNIK["fo"]["dot_email"] . "','" . $this->WYNIK["fo"]["dot_tel"] . "');";
            $db->query($query);
          }
        } else {
          $bylo = $T[0];
          if ((int)$Cuzytkownik->get_id() > 0)
            //$db->query("update ".dn("sklep_kontrahent")." set ko_punkt=ko_punkt+".$punkt." where ko_id=".(int)$Cuzytkownik->get_id());

            if (($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub()) && ($Cuzytkownik->is_klient()) && ($Cuzytkownik->get_id() != 15988)) {
              $query = "select * from " . dn('sklep_kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
              $r     = $db->onerow($query);
              $query = "update " . dn('kontrahent') . " set
                                      ko_CLUB_SUMA=" . (int)$punkt . "
                                          where ko_id=" . $Cuzytkownik->get_id() . ";";
              //$db->query($query);

              if ($Cuzytkownik->getPercent() < $Cuzytkownik->getPercent2($punkt)) $poziom = $Cuzytkownik->getPercent2($punkt);
              else $poziom = $Cuzytkownik->getPercent($punkt);

              $query = "update " . dn('kontrahent') . " set
                                        ko_CLUB_POZIOM=" . $poziom . ",
                                        ko_CLUB_DATA=" . time() . ",
                                        ko_CLUB_SUMA=ko_CLUB_SUMA+" . (int)$punkt . "
                                    where ko_id=" . $Cuzytkownik->get_id() . ";";
              $db->query($query);

              // GSS-WWW GC
              $query = "update gomezclub_card set points = points + " . $punkt . ", discount = " . $poziom . " where customer_id = " . $Cuzytkownik->get_id();
              $db->query($query);
              // ---


              $Cuzytkownik->setCZ();
            }
        }

        //tutaj przenieść zapisywanie punktów GC
        $str = "ZA:" . $za . ";KL:" . (int)$Cuzytkownik->get_id() . ";BYLO:" . $bylo . ";DODANO:" . $punkt;
        csv_log("files/log/zakup_punkty.log", $str);
      }
      else {
          $T = $db->onerow("select ko_punkt from " . dn("sklep_kontrahent") . " where ko_id= " . (int)$Cuzytkownik->get_id());
          if (!is_array($T)) {
              $bylo = "-";
              if (((int)$Cuzytkownik->get_id() > 0) && ($Cuzytkownik->is_klient())) {
                  if (($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub()) && ($Cuzytkownik->is_klient()) && ($Cuzytkownik->get_id() != 15988)) {
                      $query = "select * from " . dn('sklep_kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
                      $r     = $db->onerow($query);
                      $query = "update " . dn('kontrahent') . " set
                                      ko_CLUB_SUMA=" . (int)$punkt . "
                                          where ko_id=" . $Cuzytkownik->get_id() . ";";
                      //$db->query($query);

                      if ($Cuzytkownik->getPercent() < $Cuzytkownik->getPercent2($punkt)) $poziom = $Cuzytkownik->getPercent2($punkt);
                      else $poziom = $Cuzytkownik->getPercent($punkt);

                      $query = "update " . dn('kontrahent') . " set
                                        ko_CLUB_POZIOM=" . $poziom . ",
                                        ko_CLUB_DATA=" . time() . ",
                                        ko_CLUB_SUMA=ko_CLUB_SUMA+" . (int)$punkt . "
                                    where ko_id=" . $Cuzytkownik->get_id() . ";";
                      $db->query($query);

                      // GSS-WWW GC
                      $query = "update gomezclub_card set points = points + " . $punkt . ", discount = " . $poziom . " where customer_id = " . $Cuzytkownik->get_id();
                      $db->query($query);
                      // ---


                      $Cuzytkownik->setCZ();
                  }
              } else {
                  debug("ELLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLSEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE");
                  $query = "replace into " . dn('sklep_zamowienie_dane') . " (za_id, email, tel)
                        values (" . $this->WYNIK['za'] . ",'" . $this->WYNIK["fo"]["dot_email"] . "','" . $this->WYNIK["fo"]["dot_tel"] . "');";
                  $db->query($query);
              }
          } else {
              $bylo = $T[0];
              if ((int)$Cuzytkownik->get_id() > 0)
                  //$db->query("update ".dn("sklep_kontrahent")." set ko_punkt=ko_punkt+".$punkt." where ko_id=".(int)$Cuzytkownik->get_id());

                  if (($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub()) && ($Cuzytkownik->is_klient()) && ($Cuzytkownik->get_id() != 15988)) {
                      $query = "select * from " . dn('sklep_kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
                      $r     = $db->onerow($query);
                      $query = "update " . dn('kontrahent') . " set
                                      ko_CLUB_SUMA=" . (int)$punkt . "
                                          where ko_id=" . $Cuzytkownik->get_id() . ";";
                      //$db->query($query);

                      if ($Cuzytkownik->getPercent() < $Cuzytkownik->getPercent2($punkt)) $poziom = $Cuzytkownik->getPercent2($punkt);
                      else $poziom = $Cuzytkownik->getPercent($punkt);

                      $query = "update " . dn('kontrahent') . " set
                                        ko_CLUB_POZIOM=" . $poziom . ",
                                        ko_CLUB_DATA=" . time() . ",
                                        ko_CLUB_SUMA=ko_CLUB_SUMA+" . (int)$punkt . "
                                    where ko_id=" . $Cuzytkownik->get_id() . ";";
                      $db->query($query);

                      // GSS-WWW GC
                      $query = "update gomezclub_card set points = points + " . $punkt . ", discount = " . $poziom . " where customer_id = " . $Cuzytkownik->get_id();
                      $db->query($query);
                      // ---


                      $Cuzytkownik->setCZ();
                  }
          }

          //tutaj przenieść zapisywanie punktów GC
          $str = "ZA:" . $za . ";KL:" . (int)$Cuzytkownik->get_id() . ";BYLO:" . $bylo . ";DODANO:" . $punkt;
          csv_log("files/log/zakup_punkty.log", $str);
        $query = "replace into " . dn('sklep_zamowienie_dane') . " (za_id, email, tel)
                        values (" . $this->WYNIK['za'] . ",'" . $this->WYNIK["fo"]["dot_email"] . "','" . $this->WYNIK["fo"]["dot_tel"] . "');";
        $db->query($query);
      }
        debug("po???????????????????????????????????????????????????????????????????????????????????");

      // promocja Hilfiger DENIM + Hamak (tylko odzież)
      // $ts = time();
      // if (($ts > 1366833600) AND ($ts < 1367193599)) {
      //     $extraQuery = ' SELECT SUM(mm_sklep_zamowienie_pozycja.zp_cena_brutto * mm_sklep_zamowienie_pozycja.zp_ilosc) AS worth
      //                     FROM mm_sklep_zamowienie_pozycja
      //                     LEFT JOIN mm_sklep_produkt ON mm_sklep_produkt.pr_id = mm_sklep_zamowienie_pozycja.zp_pr_id
      //                     LEFT JOIN mm_sklep_kategoria_produkt ON mm_sklep_kategoria_produkt.kp_pr_id = mm_sklep_zamowienie_pozycja.zp_pr_id
      //                     WHERE mm_sklep_zamowienie_pozycja.zp_za_id = '.$this->WYNIK["za"].' AND mm_sklep_produkt.pr_pd_id = 86 AND mm_sklep_kategoria_produkt.kp_ka_id IN (123, 126)';
      //     $extraResult = $db->onerow($extraQuery);
      //     if ($extraResult['worth'] > 500) {
      //         $extraComment = 'PROMOCJA - HAMAK<br>';
      //         $extraNotes = $db->onerow('SELECT za_uwagi FROM mm_sklep_zamowienie WHERE za_id = '.$this->WYNIK["za"]);
      //         $extraComment .= $extraNotes['za_uwagi'];
      //         $db->query("UPDATE mm_sklep_zamowienie SET za_uwagi = '".$extraComment."' WHERE za_id = ".$this->WYNIK["za"]);
      //     }
      // }
      // ---

      debug($za);


      $Cbasket->wyczysc();
      zamowienie_dodaj($za);
        debug("Zamówienie zapis jestem tu : nr3");
      zamowienie_mail($za);

      // reset discount code
      if (isset($_SESSION[SID]['discount_code'])) {
        // oznacz kod rabatowy jako wykorzystany
        $q2   = "select * from " . dn('rabat_code') . " where code='" . $_SESSION[SID]['discount_code'] . "' and active=1 and (active_start < NOW() or active_start is null) and (active_stop > NOW() or active_stop is null)";
        $nr2  = $db->query($q2);
        $row2 = $db->fetch($nr2);

        if ($row2['amount'] == 1) {
          $q3 = "REPLACE INTO " . dn('rabat_code_user') . " (user, code, amount) VALUES (" . $Cuzytkownik->get_id() . ", " . $row2['id'] . ", 1)";
          $db->query($q3);
        }
        // ---


        $_SESSION[SID]['discount_code'] = "";
        unset($_SESSION[SID]['discount_code']);
        unset($_SESSION[SID]['discount_code_gc']);
      }
    }


    /**
     * Interaktywna ścieżka
     * @return string W tym przypadku pusty
     */
    function get_local() {
      return '';
    }


    /**
     * Wyświetlenie formularza do wprowadzenia danych odbiorcy
     *
     * @return string Widok formularza
     */
    function prn_adres() {
      global $db, $Ccms, $_GET, $_GLOBAL, $L;

        debug("TUTAJ7!!!!");

      $tpl = get_template("shop_order_form");
      if (!isset($this->WYNIK["fo"]["do_odb"])) {
        $this->WYNIK["fo"]["do_kraj"] = "PL";
      }

      $DD = array();

      $B["{DO_UWAGI}"]  = hs($this->WYNIK["fo"]["do_uwagi"]);
      $B["{DO_NAZWA}"]  = hs($this->WYNIK["fo"]["do_nazwa"]);
      $B["{DO_NAZWA2}"] = hs($this->WYNIK["fo"]["do_nazwa2"]);
      $B["{DO_MIASTO}"] = hs($this->WYNIK["fo"]["do_miasto"]);
      $B["{DO_ADRES}"]  = hs($this->WYNIK["fo"]["do_adres"]);
      $B["{DO_KOD}"]    = hs($this->WYNIK["fo"]["do_kod"]);
      $B["{DO_EMAIL}"]  = hs($this->WYNIK["fo"]["dot_email"]);
      $B["{DO_TEL}"]    = hs($this->WYNIK["fo"]["dot_tel"]);
//      $B["{DO_REG}"]    = hs($this->WYNIK["fo"]["dot_reg"]);



      //<input type="radio" name="do_odb" id="do1" value="1" {DO1} onclick="document.getElementById('dadres').style.display='none';"><label for="do1"> Tak, jak w moich danych</label><br><br/>
      //<input type="radio" name="do_odb" id="do2" value="2" {DO2} onclick="document.getElementById('dadres').style.display='block';"><label for="do2"> Użyj innych danych</label><br/>
      if ($this->WYNIK["fo"]["do_odb"] == 2) {
        //$B['{JAKIEDANE}'] = '<input type="radio" name="do_odb" id="do1" value="1" onclick="document.getElementById(\'dadres\').style.height=\'0px\';"><label for="do1"> Tak, jak w moich danych</label><br><br/>
        //<input type="radio" name="do_odb" id="do2" value="2" checked onclick="document.getElementById(\'dadres\').style.height=\'140px\';"><label for="do2"> Użyj innych danych</label><br/>';

        $B['{JAKIEDANE}'] = '
                <div class="form-element" style="width: auto;height: auto;clear: both;">
                    <input type="radio" name="do_odb" id="do1" value="1" /><label for="do1"> ' . $L['{T_TAK_JAK_W_MOICH_DANYCH}'] . '</label><br><br/>
                </div>
                <div class="form-element" style="width: auto;height: auto;clear: both;">
                    <input type="radio" name="do_odb" id="do2" checked="true" value="2" /><label for="do2"> ' . $L['{T_UZYJ_INNYCH_DANYCH}'] . '</label><br/>
                </div>';

        $B["{DO2}"] = ' checked';
        $B["{DOH}"] = '';
      } else {
        //$B['{JAKIEDANE}'] = '<input type="radio" name="do_odb" id="do1" value="1" checked onclick="document.getElementById(\'dadres\').style.height=\'0px\';"><label for="do1"> Tak, jak w moich danych</label><br><br/>
        //<input type="radio" name="do_odb" id="do2" value="2" onclick="document.getElementById(\'dadres\').style.height=\'140px\';"><label for="do2"> Użyj innych danych</label><br/>
        //
        //';

        $B['{JAKIEDANE}'] = '
                <div class="form-element" style="width: auto;height: auto;clear: both;">
                    <input type="radio" name="do_odb" id="do1" value="1" checked ><label for="do1"> ' . $L['{T_TAK_JAK_W_MOICH_DANYCH}'] . '</label>
                </div>
                <div class="form-element" style="width: auto;height: auto;clear: both;">
                    <input type="radio" name="do_odb" id="do2" value="2"><label for="do2"> ' . $L['{T_UZYJ_INNYCH_DANYCH}'] . '</label><br/>
                </div>';

        $B["{DO1}"] = ' checked';
        $B["{DOH}"] = ' style="height:0px;overflow:hidden"';
      }

      if ((isset($_GET['a'])) && ($_GET['a'] == 'bez')) {
        $B['{JAKIEDANE}'] = '<input type="hidden" name="do_odb" id="do2" value="2">';
        $B["{DO1}"]       = ' disabled="true" ';
        $B["{DO2}"]       = ' checked';
        $B["{DOH}"]       = '';
      }


      $LST = get_lista("country", 1);
      foreach ($LST as $k => $v) $B["{KRAJ}"] .= '<option value="' . $k . '"' . ($this->WYNIK["fo"]["do_kraj"] == $k ? ' selected' : '') . '>' . $v . '</option>';
      $DO = $db->onerow("select do_platnosc_odbior from " . dn("sklep_dostawa") . " where do_id=" . (int)$this->WYNIK["fo"]["do_id"]);
      //$nr = $db->query("select * from ".dn("sklep_platnosc")." where pl_widoczna='1' and pl_platnosc_odbior='".(int)$DO[0]."' order by pl_pozycja, pl_id");
      $nr  = $db->query("select * from " . dn("sklep_platnosc") . " where pl_widoczna='1' order by pl_pozycja, pl_id");
      $tpl = get_tag($tpl, "IT_FORMA", $forma);

      while ($T = $db->fetch($nr)) {
        $C["{ID}"]    = $T["pl_id"];
        $C["{NAZWA}"] = s($T["pl_nazwa"]);
        if (!isset($this->WYNIK["fo"]["do_platnosc"])) $this->WYNIK["fo"]["do_platnosc"] = $T["pl_id"];
        if (strpos($T["pl_opis"], '[P24_LISTA_METOD]') !== false) { //Przelewy24
          get_head('<script src="https://secure.przelewy24.pl/external/formy.php?id=' . $T["pl_parametr"] . '&amp;wersja=2&amp;encoding=utf-8&sort=2" type="text/javascript"></script>');
          $T["pl_opis"] = str_replace("[P24_LISTA_METOD]", '<script type="text/javascript">m_formy2();</script>', $T["pl_opis"]);
        }
        //$C["{OPIS}"] = s($T["pl_opis"]);
        if ($T["pl_id"] == $this->WYNIK["fo"]["do_platnosc"]) $C["{CHK}"] = ' checked'; else $C["{CHK}"] = '';
        $B["{IT_FORMA}"] .= get_template($forma, $C, '', 0);
      }
      $B["{ERROR}"]   = $this->get_komunikat();
      $B['{BRAKHDL}'] = '';


      $zsuma  = 0;
      $znew   = 0;
      $zlevel = 0;
//        $zlevelvalue = array(0 => 200, 1 => 299.99);
      $zlevelvalue = array(0 => 200, 1 => 200);

      $query = "select ilosc, cena, pr_cena_w_brutto, pr_cena_b_brutto
                  from " . dn('sklep_koszyk') . "," . dn('produkt') . "
                  where " . dn('sklep_koszyk') . ".session_id='" . session_id() . "' and
                        " . dn('sklep_koszyk') . ".pr_id=" . dn('produkt') . ".pr_id;";
      $res   = $db->query($query);
      while ($dane = $db->fetch($res)) {
        $zsuma += $dane['ilosc'] * $dane['cena'];
        //if($dane['pr_cena_w_brutto']>($dane['pr_cena_b_brutto']*70/100))
        if ($dane['pr_cena_w_brutto'] < ($dane['pr_cena_b_brutto'])) {
          if ($znew == 0) {
            $zlevel = 1;
            $znew++;
          }
        }
      };

      if ($zsuma < $zlevelvalue[$zlevel]) {
        $B['{BRAKHDL}']   = ($zlevelvalue[$zlevel] - $zsuma);
        $B['{DHLPRICE}']  = 14;
        $B['{DHLPRICE2}'] = 16;
      } else {
        $B['{BRAKHDL}']   = 0;
        $B['{DHLPRICE}']  = 0;
        $B['{DHLPRICE2}'] = 0;
      }


      if ((isset($_GET['a'])) && ($_GET['a'] == 'bez')) $B['{ADDURL}'] = 'bez/';
      else $B['{ADDURL}'] = '';

      $B['{URL}']  = $_GLOBAL['page_url'];
      $B['{LANG}'] = $_GLOBAL['lang'];

      $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
      $A["{STID}"]  = $Ccms->st_id;
      $A["{TYTUL}"] = $Ccms->get_tytul();

      //echo '$this->WYNIK[\'fo\'][\'do_platnosc\']: ' . $this->WYNIK['fo']['do_platnosc'] . '<br>';

      if ($this->WYNIK['fo']['do_platnosc'] == '1') {
        switch ($this->WYNIK['fo']['do_id']) {
          case '1':
            $B['{DOID1}'] = 'checked';
            break;
          case '2':
            $B['{DOID2}'] = 'checked';
            break;
          case '3':
            $B['{DOID3}'] = 'checked';
            break;
          default:
            $B['{DOSMSG}'] = $L['{T_WYBIERZ_FORME_DOSTAWY}'] . '<br><br>';
        }
      } else
        if ($this->WYNIK['fo']['do_platnosc'] == '2') {
          switch ($this->WYNIK['fo']['do_id']) {
            case '4':
              $B['{DOID7}'] = 'checked';
              break;
            case '5':
              $B['{DOID6}'] = 'checked';
              break;
            default:
              $B['{DOSMSG}'] = $L['{T_WYBIERZ_FORME_DOSTAWY}'] . '<br><br>';
          }
        } else
          if ($this->WYNIK['fo']['do_platnosc'] == '3') {
            switch ($this->WYNIK['fo']['do_id']) {
              case '4':
                $B['{DOID4}'] = 'checked';
                break;
              case '5':
                $B['{DOID5}'] = 'checked';
                break;
              default:
                $B['{DOSMSG}'] = $L['{T_WYBIERZ_FORME_DOSTAWY}'] . '<br><br>';
            }
          } else {
            $B['{DOSMSG}'] = $L['{T_WYBIERZ_FORME_DOSTAWY}'] . '<br><br>';
          }

      $A["{TRESC}"] = get_template($tpl, $B, $DD, 0);

      debug("tutaj7 end");

      return get_template("module", $A, '');
    }

    /**
     * Ostatni krok przed zapisaniem zamówienia - wyświetlenie podsumowania
     *
     * @return string Podsumowanie zamówienia
     */
    function prn_podsumowanie() {
      global $Cuzytkownik, $db, $Ccms, $_GET, $_GLOBAL, $L;


        debug("TUTAJ8!!!!");
      debug($this->WYNIK["fo"]);

      $tpl          = get_template("shop_order_sumary");
      $UZ           = $db->onerow("select * from " . dn("kontrahent") . " where ko_id=" . $Cuzytkownik->get_id());
      $B["{NAZWA}"] = s($UZ["ko_nazwa"]);
      if ($UZ["ko_nip"] != "") $B["{NIP}"] = s($UZ["ko_nip"]);
      else $DD[] = "IT_NIP";

      if (empty($UZ['ko_kod'])) $B["{ADRES}"] = '';
      else $B["{ADRES}"] = s($UZ["ko_kod"] . " " . $UZ["ko_miasto"] . ", " . $UZ["ko_ulica"] . " " . $UZ["ko_ulica_dom"] . " " . $UZ["ko_ulica_lok"]);

      if ((isset($_GET['a'])) && ($_GET['a'] == 'bez')) $B['{ADDURL}'] = 'bez/';
      else $B['{ADDURL}'] = '';

      $B['{STEPS}'] = '<div class="steps">
                            <div class="step1">' . $L['{T_KROK_1}'] . '</div>
                            <div class="step1">' . $L['{T_KROK_2}'] . '</div>
                            <div class="step2">' . $L['{T_KROK_3}'] . '</div>
                            <div class="step1">' . $L['{T_KROK_4}'] . '</div>
                        </div>';

      if ($this->WYNIK["fo"]["do_odb"] == 2) {
        $B["{DO_NAZWA}"] = s($this->WYNIK["fo"]["do_nazwa"] . ' ' . $this->WYNIK["fo"]["do_nazwa2"]);
        $B["{DO_ADRES}"] = s($this->WYNIK["fo"]["do_kod"] . " " . $this->WYNIK["fo"]["do_miasto"] . ", " . $this->WYNIK["fo"]["do_adres"]) . " (" . $this->WYNIK["fo"]["do_kraj"] . ")";
      } else {
        $B["{DO_NAZWA}"] = $B["{NAZWA}"];
        $B["{DO_ADRES}"] = $B["{ADRES}"] . " (" . $UZ["ko_kraj"] . ")";
      }

      $q = "select ko.*,pr.pr_nazwa,pr2.pr_cena_w_brutto,pr2.pr_cena_a_brutto,at_nazwa,pr2.pr_plik,pd_id,pd_nazwa,kp_ka_id from " . dn("sklep_koszyk") . " ko ";
      $q .= "left join " . dn("sklep_produkt") . " pr on ko.pr_id=pr.pr_id ";
      $q .= "left join " . dn("produkt") . " pr2 on ko.pr_id=pr2.pr_id ";
      $q .= "left join " . dn("produkt_atrybut") . " at on ko.at_id=at.at_id ";
      $q .= "left join " . dn("sklep_producent") . " pd on pr_pd_id=pd_id ";
      $q .= "left join " . dn("sklep_kategoria_produkt") . " ka on ko.pr_id=kp_pr_id ";
      $q .= "where typ='produkt' and session_id='" . session_id() . "' group by pr.pr_id, at_id";
      $nr = $db->query($q);

      $item = "";
      $tpl  = get_tag($tpl, "IT_ITEM", $item);
      $i    = 1;
      $suma = 0;
      while ($T = $db->fetch($nr)) {
        $LST[] = $T;
        //$CENA[$T["pr_id"]] = $T["pr_cena_w_brutto"];
        $CENA[$T["pr_id"]]   = $T["pr_cena_w_brutto"];
        $CENAO[$T["pr_id"]]  = $T["pr_cena_w_brutto"];
        $CENA2[$T["pr_id"]]  = $T["pr_cena_a_brutto"];
        $CENA3[$T["pr_id"]]  = $T["ilosc"];
        $IDS[$T["pr_id"]]    = $T["pr_id"];
        $BRANDS[$T["pr_id"]] = $T["pd_id"];
      }

      if ($Cuzytkownik->is_login()) {
        $q = "select sp_pr_id,sa_rabat from " . dn("sklep_promocja_produkt") . " left join " . dn("sklep_promocja") . " ";
        $q .= "on sp_sa_id=sa_id where sp_pr_id in (" . join(",", $IDS) . ") and ";
        $q .= "sa_widoczna='1' and (sa_data_od='' or sa_data_od <'" . date("Y-m-d H:i") . "') and ";
        $q .= "(sa_data_do='' or sa_data_do > '" . date("Y-m-d H:i") . "')";
        $nr = $db->query($q);
        //while($T = $db->fetch($nr)) $CENA[$T[0]] = number_format(($CENA[$T[0]]*(100-$T[1])/100),2,".","");

        if ($db->affected_rows() > 0) {
          while ($T = $db->fetch($nr)) {
            $CENA[$T[0]]  = number_format(($CENA[$T[0]] * (100 - $T[1]) / 100), 2, ".", "");
            $CENAO[$T[0]] = number_format(($CENAO[$T[0]] * (100 - $T[1]) / 100), 2, ".", "");
            // debug($T);
          }
        }
      }


      // related product promotions
      $tmpFreeProductsPaid = array();
      $tmpFreeProductsFree = array();
      $rppName             = "";
      $rppCount            = 0;
      $rppValue            = 0;
      $rppType             = 0;
      $rppSum              = 0;
      $rppGC               = -1;
      $q                   = "SELECT * FROM mm_related_product_promotion WHERE active = 1 LIMIT 1";
      $row                 = $db->onerow($q);
      if (is_array($row)) {
        $rppName  = $row['name'];
        $rppValue = $row['value'];
        $rppType  = $row['type'];
        $rppGC    = $row['with_gc'];
        // paid products
        $paidProducts = array();
        $q            = "SELECT product_id FROM mm_related_product_promotion_products WHERE related_product_promotion_id =" . $row['id'] . " AND type = 0";
        $nr           = $db->query($q);
        while ($r = $db->fetch($nr)) {
          $paidProducts[] = $r['product_id'];
        }
        // debug($paidProducts);

        // related product promotions
        $freeProducts = array();
        $q            = "SELECT product_id FROM mm_related_product_promotion_products WHERE related_product_promotion_id =" . $row['id'] . " AND type = 1";
        $nr           = $db->query($q);
        while ($r = $db->fetch($nr)) {
          $freeProducts[] = $r['product_id'];
        }
        // debug($freeProducts);

        foreach ($LST as $T) {
          if (in_array($T['pr_id'], $paidProducts)) {
            $tmpFreeProductsPaid[$T['pr_id']] = (array_key_exists($T['pr_id'], $tmpFreeProductsPaid)) ? ($tmpFreeProductsPaid[$T['pr_id']] + 1) : 1;
          }
          if (in_array($T['pr_id'], $freeProducts)) {
            $tmpFreeProductsFree[$T['pr_id']] = (array_key_exists($T['pr_id'], $tmpFreeProductsFree)) ? ($tmpFreeProductsFree[$T['pr_id']] + 1) : 1;
          }
        }

        $paidSum = array_sum($tmpFreeProductsPaid);
        $freeSum = array_sum($tmpFreeProductsFree);
        if ($paidSum == 0) {
          $rppCount = 0;
        } elseif ($paidSum < $freeSum) {
          $rppCount = $paidSum;
        } else {
          $rppCount = $freeSum;
        }

        // debug($tmpFreeProductsPaid);
        // debug($tmpFreeProductsFree);
      }
      // --- to be continued


      // applying discount codes
      if (isset($_SESSION[SID]['discount_code'])) {
        debug($_SESSION[SID]['discount_code']);
        $appliedProducts = array();
        $q               = "select * from " . dn('rabat_code') . " where code='" . $_SESSION[SID]['discount_code'] . "' and active=1 and (active_start < NOW() or active_start is null) and (active_stop > NOW() or active_stop is null)";
        $nr              = $db->query($q);
        $row             = $db->fetch($nr);

        if (is_array($row)) {
          $type                                = $row['type'];
          $amount                              = $row['amount'];
          $_SESSION[SID]['discount_code_gc']   = $row['gc'];
          $_SESSION[SID]['discount_code_name'] = $row['name'];

          // sprawdzenie czy kod tylko dla uprawnionych i jesli tak, czy uzytkownik jest uprawniony
          $onlyForAllowedUsers = false;
          $userAllowedForCode  = false;
          $q                   = "SELECT * FROM " . dn('rabat_code_allowed_user') . " WHERE code_id = " . $row['id'];
          $allowed             = $db->get_all($q);
          if (count($allowed) > 0) {
            $onlyForAllowedUsers = true;

            // sprawdzenie czy uzytkownik jest uprawniony
            $q           = "SELECT * FROM " . dn('rabat_code_allowed_user') . " WHERE code_id = " . $row['id'] . " AND user_id = " . $Cuzytkownik->get_id();
            $allowedUser = $db->get_all($q);
            if (count($allowedUser) > 0) {
              $userAllowedForCode = true;
            }
          }
          // ---

          // sprawdzenie czy wykorzystal juz kod
          $userCodeUsed = 0;
          if ($amount) {
            $q            = "SELECT * FROM " . dn('rabat_code_user') . " WHERE code='" . $row['id'] . "' AND user = " . $Cuzytkownik->get_id();
            $userRow      = $db->onerow($q);
            $userCodeUsed = isset($userRow['amount']) ? $userRow['amount'] : 0;
          }
          // ---

          if (($onlyForAllowedUsers AND $userAllowedForCode) OR (!$onlyForAllowedUsers AND (($amount > $userCodeUsed) OR ($amount == 0)))) {

            // get products with code discount
            $bprods = array();
            foreach ($LST as $T) $bprods[] = $T["pr_id"];
            $q     = "select DISTINCT * from " . dn('rabat_code_product') . " where cid=" . $row['id'] . " AND pid IN (" . join(",", $bprods) . ")";
            $nr    = $db->query($q);
            $prods = array();
            while ($p = $db->fetch($nr)) {
              $prods[] = $p;
            }


            // produkty zwiazane z DC
            $dcpIDS = array();
            foreach ($prods as $value) $dcpIDS[] = $value['pid'];

            // tutaj obliczanie ceny dla gomezclub jesli laczy sie z kodem rabatowym
            // GC naliczany dla wszystkich produktow
            if (($Cuzytkownik->is_gomezclub()) AND ($row['gc'] == 1)) {
              $pkt = 0;
              foreach ($CENA as $k => $v) $pkt += $v * $CENA3[$k];
              $rab = $Cuzytkownik->getPercent2($pkt);
              foreach ($CENA2 as $k => $v) {
                // efektywny rabat: albo GC albo rabat LJ (108)
                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                debug('rabat: ' . $effectiveRab);
                if ($CENA[$k] < $v) {
                  //Przecena - naliczyc połowę rabatu Gomez Club
                  $rabPrd[$k] = ($effectiveRab) / 2;
                  $CENA[$k]   = ($CENA[$k] * ((100 - (($effectiveRab) / 2)) / 100));
                } else {
                  //Brak Przeceny - naliczyć rabat klienta
                  $rabPrd[$k] = ($effectiveRab);
                  $CENA[$k]   = ($CENA[$k] * ((100 - ($effectiveRab)) / 100));
                }
              }
            } elseif (($Cuzytkownik->is_gomezclub()) AND ($row['gc'] == 0)) {
              // jesli sie nie laczy z GC to sprobowac naliczyc GC dla produktow nie wchodzacych DC

              // produkty nie zwiazane z DC w zaleznosci od tego czy wszysktie czy jakies wybrane
              if ($row['all_products'] == 1) {
                $ndcpIDS = array();
              } else {
                $ndcpIDS = array_diff($bprods, $dcpIDS);
              }

              $pkt = 0;
              foreach ($CENA as $k => $v) {
                if (in_array($k, $ndcpIDS)) {
                  $pkt += $v * $CENA3[$k];
                }
              }

              $rab = $Cuzytkownik->getPercent2($pkt);
              foreach ($CENA2 as $k => $v) {
                // efektywny rabat: albo GC albo rabat LJ (108)
                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                debug('rabat: ' . $effectiveRab);
                if (in_array($k, $ndcpIDS)) {
                  if ($CENA[$k] < $v) {
                    //Przecena - naliczyc połowę rabatu Gomez Club
                    $rabPrd[$k] = ($effectiveRab) / 2;
                    $CENA[$k]   = ($CENA[$k] * ((100 - (($effectiveRab) / 2)) / 100));
                  } else {
                    //Brak Przeceny - naliczyć rabat klienta
                    $rabPrd[$k] = ($effectiveRab);
                    $CENA[$k]   = ($CENA[$k] * ((100 - ($effectiveRab)) / 100));
                  }
                }
              }
            }

            switch ($type) {
              case 1: // %
                if ($row['all_products'] != 0) {
                  foreach ($LST as $T) {
                    if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                      $CENA[$T["pr_id"]]            = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]] * $row['rabat'] / 100;
                      $appliedProducts[$T["pr_id"]] = true;
                    }
                  }
                } else {
                  foreach ($LST as $T) {
                    if (in_array($T['pr_id'], $dcpIDS)) {
                      if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                        $CENA[$T["pr_id"]]            = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]] * $row['rabat'] / 100;
                        $appliedProducts[$T["pr_id"]] = true;
                      }
                    }
                  }
                }
                break;
              case 2: // pln
                if ($row['all_products'] != 0) {
                  foreach ($LST as $T) {
                    if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                      $CENA[$T["pr_id"]]            = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]] * $row['rabat'] / 100;
                      $appliedProducts[$T["pr_id"]] = true;
                    }
                  }
                } else {
                  foreach ($LST as $T) {
                    if (in_array($T['pr_id'], $dcpIDS)) {
                      if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                        $CENA[$T["pr_id"]]            = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]] * $row['rabat'] / 100;
                        $appliedProducts[$T["pr_id"]] = true;
                      }
                    }
                  }
                }
                $sumadc = $row['rabat'];
                break;
            }
          } else { // nie lapie sie na kod rabatowy, wiec jedziemy po GC
            //tutaj obliczanie ceny dla gomezclub
            //pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości korzyka
            if ($Cuzytkownik->is_gomezclub()) {
              $pkt = 0;
              foreach ($CENA2 as $k => $v) $pkt += $v * $CENA3[$k];
              // $rab = $Cuzytkownik->getPercent2($pkt);
              $rab = $Cuzytkownik->getPercent();
              foreach ($CENA2 as $k => $v) {
                // efektywny rabat: albo GC albo rabat LJ (108)
                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                debug('rabat: ' . $effectiveRab);
                if ($CENA[$k] < $v) {
                  //Przecena - naliczyc połowę rabatu Gomez Club
                  $CENA[$k] = ($CENA[$k] * ((100 - (($effectiveRab) / 2)) / 100));
                } else {
                  //Brak Przeceny - naliczyć rabat klienta
                  $CENA[$k] = ($CENA[$k] * ((100 - ($effectiveRab)) / 100));
                }
              }
            }
          }
        }
      } else {
        //tutaj obliczanie ceny dla gomezclub
        //pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości korzyka
        if ($Cuzytkownik->is_gomezclub()) {
          $pkt = 0;
          foreach ($CENA2 as $k => $v) $pkt += $v * $CENA3[$k];
          // $rab = $Cuzytkownik->getPercent2($pkt);
          $rab = $Cuzytkownik->getPercent();
          foreach ($CENA2 as $k => $v) {
            // efektywny rabat: albo GC albo rabat LJ (108)
            $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
            debug('rabat: ' . $effectiveRab);
            if ($CENA[$k] < $v) {
              //Przecena - naliczyc połowę rabatu Gomez Club
              $CENA[$k] = ($CENA[$k] * ((100 - (($effectiveRab) / 2)) / 100));
            } else {
              //Brak Przeceny - naliczyć rabat klienta
              $CENA[$k] = ($CENA[$k] * ((100 - ($effectiveRab)) / 100));
            }
          }
        }
      }
      // ---


      $rppCountTmp = $rppCount;
      $rppValueTmp = $rppValue;
      foreach ($LST as $T) {

        $T["pr_cena_w_brutto"] = $CENA[$T["pr_id"]];
        $C["{LP}"]             = $i;
        $C["{MOD}"]            = $i++ % 2;
        $C["{ID}"]             = $T["pr_id"];
        $C["{ILE}"]            = $T["ilosc"];
        $C["{ATRYBUT}"]        = ($T["at_nazwa"]);
        $C["{AT_ID}"]          = ($T["at_id"]);
        $C["{ROZMIAR}"]        = $T["ilosc"];
        //$C["{CENA}"] = number_format($T["pr_cena_w_brutto"],2,".","");

        if (($T["pr_cena_w_brutto"] < $CENAO[$T['pr_id']]) && ($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub())) {
          //$C["{CENA1}"] = number_format($CENAO[$T["pr_id"]],2,".","") . ''; //cena indywidualnego towaru
          $C["{CENA}"] = number_format($T["pr_cena_w_brutto"], 2, ".", ""); //cena indywidualnego towaru
          //$C['{CENAFORM}'] = number_format($T["pr_cena_w_brutto"],2,".","");
        } else {
          $C["{CENA}"] = number_format($T["pr_cena_w_brutto"], 2, ".", ""); //cena indywidualnego towaru
          //$C["{CENAFORM}"] = number_format($T["pr_cena_w_brutto"],2,".",""); //cena indywidualnego towaru
        }


        $tmpWorth = $T["ilosc"] * (($rppGC == 0) ? $CENAO[$T["pr_id"]] : $T["pr_cena_w_brutto"]);

        // related product promotions - applying part 1
        // tu nie wiadomo jaki ma byc algorytm tego odejmowania, czy np. najtansze czy po jednym z kazdego
        // zrobione jest ze po kolei do wyczerpania
        if (array_key_exists($T["pr_id"], $tmpFreeProductsFree)) {
          if ($rppType == 0) { // procentowo
            if ($T['ilosc'] > $rppCountTmp) {
              $tmp = $rppCountTmp * (($rppGC == 0) ? $CENAO[$T["pr_id"]] : $T["pr_cena_w_brutto"]) * $rppValue / 100;
            } else {
              $tmp = $T['ilosc'] * (($rppGC == 0) ? $CENAO[$T["pr_id"]] : $T["pr_cena_w_brutto"]) * $rppValue / 100;
            }
            $rppCountTmp -= $T["ilosc"];
            $rppCountTmp = ($rppCountTmp < 0) ? 0 : $rppCountTmp;
          } elseif ($rppType = 1) { // kwotowo
            if (($T['ilosc'] * $T["pr_cena_w_brutto"]) > $rppValueTmp) {
              $tmp = $rppValueTmp;
            } else {
              $tmp = $T['ilosc'] * $T["pr_cena_w_brutto"];
            }
            $rppValueTmp -= $tmp;
          }
          $rppSum += $tmp;
          $tmpWorth -= $tmp;
        }
        // --- to be continued

        $C["{WARTOSC}"] = number_format($tmpWorth, 2, ".", ""); //wartosc indywidualnego towaru

        if ($_GLOBAL['langid'] != 1) {
          $q           = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $T['pr_id'] . " and langid=" . $_GLOBAL['langid'] . " and name='nazwa';";
          $r           = $db->query($q);
          $translation = $db->fetch($r);

          $C["{ROZMIAR_LABEL}"] = 'size';
          $product_name         = $translation['description'];
        } else {
          $product_name         = $T["pr_nazwa"];
          $C["{ROZMIAR_LABEL}"] = 'roz.';
        }

        $C["{NAZWA}"] = hs($product_name);
        $C["{INFO}"]  = hs($T["pd_nazwa"]);
        $C["{URL}"]   = "sklep," . $T["kp_ka_id"] . "," . $T["pr_id"] . "," . conv2file(s($T["pr_nazwa"])) . ".htm";
        //$C["{INFO}"] = s($T[""])
        $C["{IMG}"] = ($T["pr_plik"] != "" and file_exists($T["pr_plik"])) ? str_replace("/m/", "/s/", $T["pr_plik"]) : '/images/nofoto_s.gif';
        $B["{IT_ITEM}"] .= get_template($item, $C, '', 0);

        $suma += number_format($tmpWorth, 2, ".", "");
      }

      /*
      if(($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub()) && ($Cuzytkownik->is_klient())) {
          $query = "select * from " . dn('sklep_kontrahent') . " where ko_id=" . $Cuzytkownik->get_id() . ";";
          $r = $db->onerow($query);
          $query = "update " . dn('kontrahent') . " set
                    ko_CLUB_SUMA=" . (int)$r['ko_punkt'] . "
                    where ko_id=" . $Cuzytkownik->get_id() . ";";
          $db->query($query);

          if($Cuzytkownik->getPercent()<$Cuzytkownik->getPercent2($suma)) $poziom = $Cuzytkownik->getPercent2($suma);
          else $poziom = $Cuzytkownik->getPercent($suma);

          $query = "update " . dn('kontrahent') . " set
                    ko_CLUB_POZIOM=" . $poziom . ",
                    ko_CLUB_DATA=" . time() . ",
                    ko_CLUB_SUMA=ko_CLUB_SUMA+" . $suma . "
                    where ko_id=" . $Cuzytkownik->get_id() . ";";
          $db->query($query);

          $Cuzytkownik->setCZ();
      }
      */
        $param =
            array (
                'email' => 'paczkomaty@paczkomaty.pl',
                'postcode' => '30-019',
                'selected' => 'KRA139',
                'class' => 'class_inpost_machines_dropdown',
                'name'  => 'paczkomat',
            );

        $result=inpost_machines_dropdown($param);
        if ($result==-1) $result = "Klient nie istnieje"; else
        if (empty($result))
                $result = "dupa";

        $B["{DROPDOWN}"] =  $result;
      $DO = $db->onerow("select * from " . dn("sklep_dostawa") . " where do_id=" . (int)$this->WYNIK["fo"]["do_id"]);
      if ($_GLOBAL['lang'] == "pl") $B["{DOSTAWA}"] = s($DO["do_nazwa"]);
      else  $B["{DOSTAWA}"] = s($DO["do_nazwa_en"]);

      if ((int)$this->WYNIK["fo"]["do_id"] == 3 || (int)$this->WYNIK["fo"]["do_id"] == 4) {
        $zsuma  = 0;
        $znew   = 0;
        $zlevel = 0;
//            $zlevelvalue = array(0 => 200, 1 => 299.99);
        $zlevelvalue = array(0 => 200, 1 => 200);

        $query = "select ilosc, cena, pr_cena_w_brutto, pr_cena_b_brutto
                      from " . dn('sklep_koszyk') . "," . dn('produkt') . "
                      where " . dn('sklep_koszyk') . ".session_id='" . session_id() . "' and " . dn('sklep_koszyk') . ".pr_id=" . dn('produkt') . ".pr_id;";
        $res   = $db->query($query);
        while ($dane = $db->fetch($res)) {
          $zsuma += $dane['ilosc'] * $dane['cena'];
          //if($dane['pr_cena_w_brutto']>($dane['pr_cena_b_brutto']*70/100))
          if ($dane['pr_cena_w_brutto'] < ($dane['pr_cena_b_brutto'])) {
            if ($znew == 0) {
              $zlevel = 1;
              $znew++;
            }
          }
        };

        if ($zsuma < $zlevelvalue[$zlevel]) {
          if ($this->checkDHLFreeDelivery($this->WYNIK["fo"]["do_id"])) {
            $B["{WARTOSC_DO}"] = "0.00";
            $B["{DOSTAWA}"]    = str_replace(array(14, 20), array('0', '0'), $B["{DOSTAWA}"]);
          } else {
            $B["{WARTOSC_DO}"] = number_format($DO["do_koszt"], 2, ".", "") . '';
            $suma += $DO["do_koszt"];
          }
          //$B['{DHLPRICE}'] = 14;
          //$B['{DHLPRICE2}'] = 20;
        } else {
          //$B['{DHLPRICE}'] = 0;
          //$B['{DHLPRICE2}'] = 0;
          $B["{WARTOSC_DO}"] = "0.00";
          $B["{DOSTAWA}"]    = str_replace(array(14, 20), array('0', '0'), $B["{DOSTAWA}"]);
        }
      } else {
        $B["{WARTOSC_DO}"] = number_format($DO["do_koszt"], 2, ".", "") . '';
        $suma += $DO["do_koszt"];
      }

      /*
      if($DO["do_darmowa"]<=$suma) {
          $B["{WARTOSC_DO}"] = "0.00";
      } else  {
          $B["{WARTOSC_DO}"] = number_format($DO["do_koszt"],2,".","").'';
          $suma+=$DO["do_koszt"];
      }
      */

      $DD[] = "IT_STATUS";
      $PL   = $db->onerow("select * from " . dn("sklep_platnosc") . " where pl_id=" . (int)$this->WYNIK["fo"]["do_platnosc"]);
      if ($_GLOBAL['lang'] == "pl") $B["{PLATNOSC}"] = s($PL["pl_nazwa"]);
      else $B["{PLATNOSC}"] = s($PL["pl_nazwa_en"]);


      // wywswietlenie ceny pomniejszonej o wartosc bonu rabatowego
      debug('--------------------------------');
      debug($suma);
      debug($_SESSION[SID]['discount_code']);
      debug('----------------------------------');
      if(isset($_SESSION[SID]['discount_code'])){
          $_GLOBAL["db_pre"] = "";
          $r = $db->onerow("SELECT * FROM gift_ticket WHERE code = '".$_SESSION[SID]['discount_code']."'");
          //jesli jest to czy kod istnieje?
          if (is_array($r) AND (count($r) > 0)) {
              //czy jest nieuzyty?
              if($r['status']==1){
                  $gift = $r['value'];
                  $suma = $suma - $gift;
                  if($suma<0) $suma=0;
              }
          }
          $_GLOBAL["db_pre"] = "mm";
      }
      // end


      $B["{SUMA}"]  = number_format($suma, 2, ".", "");
      $B['{URL}']   = $_GLOBAL['page_url'];
      $B['{LANG}']  = $_GLOBAL['lang'];
      $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
      $A["{STID}"]  = $Ccms->st_id;
      $A["{TYTUL}"] = $L['{T_PODSUMOWANIE_ZAMOWIENIA}']; //"Podsumowanie zamówienia";
      $A["{TRESC}"] = get_template($tpl, $B, $DD, 0);

      return get_template("module", $A, '');
    }


    /**
     * Obsługa płatności
     */
    function prn_platnosc() {
      global $Cuzytkownik, $db, $Ccms, $_GLOBAL, $L;

        debug("TUTAJ9!!!!");

      $DD = array();

      $q = "select za_data_in,za_ko_nazwa, za_ko_kraj, za_ko_miasto, za_ko_kod, za_ko_ulica, za_ko_ulica_dom, za_ko_ulica_lok, ";
      $q .= "za_do_koszt+za_wartosc_brutto za_wartosc, za_status, ko_email, ";
      $q .= "za_do_nazwa, za_do_kraj, za_do_miasto, za_do_kod, za_do_adres, za_ko_id ";
      $q .= "from " . dn("sklep_zamowienie") . " left join " . dn("kontrahent") . " on za_ko_id=ko_id where za_id=" . $this->WYNIK["za"];
      $ZA = $db->onerow($q);
      /**
       * Zamówienie zrealizowano, odrzucono
       */

      if ((empty($ZA['ko_email'])) || ($ZA['ko_email'] == '')) $ZA['ko_email'] = $_SESSION[SID]["zakup"]["dot_email"];
//        if ($T["za_status"] != 0) {
//            redirect($_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/zakup/mess/1/");
//        }
      $ZA["za_ko_adres"] = $ZA["ko_ulica"] . " " . $ZA["ko_ulica_dom"] . (($ZA["ko_ulica_dom"] != "" and $ZA["ko_ulica_lok"] != "") ? "/" : "") . $ZA["ko_ulica_lok"];

      if ($ZA['za_ko_id'] == 0) {
        $query              = "select * from " . dn('sklep_zamowienie_dane') . " where za_id=" . (int)$this->WYNIK["za"] . ";";
        $dane               = $db->onerow($query);
        $ZA['ko_email']     = $dane['email'];
        $ZA['za_ko_nazwa']  = $ZA['za_do_nazwa'];
        $ZA['za_ko_kraj']   = $ZA['za_do_kraj'];
        $ZA['za_ko_miasto'] = $ZA['za_do_miasto'];
        $ZA['za_ko_kod']    = $ZA['za_do_kod'];
        $ZA['za_ko_adres']  = $ZA['za_do_adres'];
      }

      $PL           = $db->onerow("select * from " . dn("sklep_platnosc") . " where pl_id=" . $this->WYNIK["fo"]["do_platnosc"]);
      $tpl          = $_GLOBAL['langid'] == 1 ? s($PL["pl_potwierdzenie"]) : s($PL["pl_potwierdzenie_en"]);
      $B["[ID]"]    = "ZM/" . $this->WYNIK["za"];
      $B["[KWOTA]"] = number_format($ZA["za_wartosc"], 2, ".", "");

      if (strpos($tpl, "[P24_ZAPLAC]") !== false) {
        $B["[P24_ZAPLAC]"] = '
            <form action="https://secure.przelewy24.pl/index.php" method="post" id="p24">
            <input type="hidden" name="p24_id_sprzedawcy" value="' . $PL["pl_parametr"] . '"/>
            <input type="hidden" name="p24_kwota" value="' . number_format($ZA["za_wartosc"] * 100, 0, "", "") . '"/>
            <input type="hidden" name="p24_email" value="' . s($ZA["ko_email"]) . '"/>
            <input type="hidden" name="p24_session_id" value="' . $this->WYNIK["za"] . "," . time() . '"/>
            <input type="hidden" name="p24_klient" value="' . s($ZA["za_ko_nazwa"]) . '"/>
            <input type="hidden" name="p24_metoda" value="' . $this->WYNIK["fo"]["p24_metoda"] . '"/>
            <input type="hidden" name="p24_adres" value="' . s($ZA["za_ko_adres"]) . '"/>
            <input type="hidden" name="p24_kod" value="' . s($ZA["za_ko_kod"]) . '"/>
            <input type="hidden" name="p24_miasto" value="' . s($ZA["za_ko_miasto"]) . '"/>
            <input type="hidden" name="p24_kraj" value="' . s($ZA["za_ko_kraj"]) . '"/>
            <input type="hidden" name="p24_opis" value="Zamówienie nr ZM/' . $this->WYNIK["za"] . '"/>
            <input type="hidden" name="p24_return_url_ok" value="' . $_GLOBAL["page_url"] . $_GLOBAL['lang'] . '/zakup/message/ok/"/>
            <input type="hidden" name="p24_return_url_error" value="' . $_GLOBAL["page_url"] . $_GLOBAL['lang'] . '/zakup/message/error/"/>
            <input type="image" src="' . $_GLOBAL['page_url'] . 'files/cms/6/przelewy24.gif" name="s_" />
            </form>
            <script type="text/javascript">document.getElementById("p24").submit();</script>
            ';
        csv_log("files/log/przelewy24_wynik-all-start.log", 'Początek płatności: ' . join('; ', $B));
      }
      /*
      <input type="hidden" name="p24_return_url_ok" value="'.$_GLOBAL["page_url"].'/zakup.htm?a=p24&v=ok"/>
      <input type="hidden" name="p24_return_url_error" value="'.$_GLOBAL["page_url"].'/zakup.htm?a=p24&v=error"/>

       */
      $A["{TOPID}"]    = $Ccms->WYNIK["fo"]["st_st_id"];
      $A["{STID}"]     = $Ccms->st_id;
      $A["{TYTUL}"]    = $L['{T_ZAMOWIENIE_PRZYJETO}'];
      $A["{TRESC}"]    = get_template($tpl, $B, $DD, 0);
      $A['{PRETRESC}'] = '<div class="steps">
<div class="step1">' . $L['{T_KROK_1}'] . '</div>
<div class="step1">' . $L['{T_KROK_2}'] . '</div>
<div class="step1">' . $L['{T_KROK_3}'] . '</div>
<div class="step2">' . $L['{T_KROK_4}'] . '</div>
</div>
<div class="separator"></div>
';

      return get_template("module", $A, '');
    }

    /**
     * Obsługa komunikatów
     */
    function prn_message() {
      global $L, $Ccms;

        debug("TUTAJ9!!!!");


      $M[1] = $L['{T_NIE_MA_JUZ_MOZLIWOSCI_OPLACENIA_TEGO_ZAMOWIENIA}'];
      $M[2] = $L['{T_DO_CHWILI_OBECNEJ_NIE_OTRZYMALISMY__}'];
      $M[3] = $L['{T_DZIEKUJEMY_ZA_ZLOZENIE_ZAMOWIENIA__}'];
      $M[4] = $L['{T_DO_CHWILI_OBECNEJ_NIE_OTRZYMALISMY__}'];

      $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
      $A["{STID}"]  = $Ccms->st_id;
      $A["{TYTUL}"] = $L['{T_ZAMOWIENIE}'];
      $B["{TRESC}"] = $M[$this->WYNIK["mess"]];
      $A["{TRESC}"] = get_template("message", $B);

      return get_template("module", $A, $A["{STID}"] = $Ccms->st_id);
      $A["{TYTUL}"]    = $L['{T_ZAMOWIENIE_PRZYJETO}'];
      $A["{TRESC}"]    = get_template($tpl, $B, $DD, 0);
      $A['{PRETRESC}'] = '<div class="steps">
                            <div class="step1">' . $L['{T_KROK_1}'] . '</div>
                            <div class="step1">' . $L['{T_KROK_2}'] . '</div>
                            <div class="step1">' . $L['{T_KROK_3}'] . '</div>
                            <div class="step2">' . $L['{T_KROK_4}'] . '</div>
                            </div>
                            <div class="separator"></div>
                            ';

      return get_template("module", $A, '');
    }

    /**
     * Obsługa komunikatów
     */
    function prn_message1() {
      global $L, $Ccms;

        debug("TUTAJ10!!!!");


      $M[1] = $L['{T_NIE_MA_JUZ_MOZLIWOSCI_OPLACENIA_TEGO_ZAMOWIENIA}'];
      $M[2] = $L['{T_DO_CHWILI_OBECNEJ_NIE_OTRZYMALISMY__}'];
      $M[3] = $L['{T_DZIEKUJEMY_ZA_ZLOZENIE_ZAMOWIENIA__}'];
      $M[4] = $L['{T_DO_CHWILI_OBECNEJ_NIE_OTRZYMALISMY__}'];

      $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
      $A["{STID}"]  = $Ccms->st_id;
      $A["{TYTUL}"] = $L['{T_ZAMOWIENIE}'];
      $B["{TRESC}"] = $M[$this->WYNIK["mess"]];
      $A["{TRESC}"] = get_template("message", $B);

      return get_template("module", $A, '');
    }
  }

?>