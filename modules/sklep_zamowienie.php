<?php
/**
 * Klasa prezentacji historii zamówień klienta
 *
 *
 * @author    Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since     2008-08-04 12:36:48
 * @link      www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if (!defined('SMDESIGN')) {
    die("Hacking attempt");
}


/**
 * Klasa prezentacji historii zamówień klienta
 *
 */
class sklep_zamowienie extends mb {
    /**
     * Lista adresów powrotnych, gdzie może być przekierowany klient po zalgowaniu
     *
     * @var array
     */
    function sklep_zamowienie() {
        global $Cuzytkownik, $_GLOBAL;
        if (!$Cuzytkownik->is_klient()) redirect($_GLOBAL['page_url'] . "uzytkownik.htm?url=zamowienie");

        $this->mode = "prn_lista";
        if (isset($_GET["id"])) {
            $this->id   = (int)$_GET["id"];
            $this->mode = "prn_detal";
        }
    }

    /**
     * Interaktywna ścieżka
     *
     * @return string W tym przypadku pusty
     */
    function get_local() {
        return '';
    }

    /**
     * Lista zamówień dla użytkownika
     *
     * @return string Strona z zamówieniami
     */

    function prn_lista() {
        global $db, $Ccms, $Cuzytkownik, $_GLOBAL, $L;

        $tpl = get_template("shop_order_list");
        $tpl = get_tag($tpl, "IT_ITEM", $item);

        $qw = "where za_ko_id=" . $Cuzytkownik->get_id();

        $q = "select count(*) from " . dn("sklep_zamowienie") . " " . $qw . " order by za_id desc";
        $T = $db->onerow($q);
        if ($T[0]) {
            $Cnawi = new nawigacja($T[0], 20, $_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/zamowienie/");

            $q = "select za_id, za_data_in, za_status,(za_do_koszt+za_wartosc_brutto) za_wartosc from ";
            $q .= dn("sklep_zamowienie") . " ";
            $q .= $qw . " order by za_id desc ";
            $q .= "limit " . $Cnawi->get_min() . "," . $Cnawi->get_ppp();
            $nr  = $db->query($q);
            $LST = get_lista("order_status_" . $_GLOBAL['lang']);
            $i   = 0;
            while ($T = $db->fetch($nr)) {
                $C              = array();
                $C["{MOD}"]     = $i++ % 2;
                $C["{LP}"]      = $i;
                $C["{ID}"]      = $T["za_id"];
                $C["{STATUS}"]  = $LST[$T["za_status"]];
                $C["{NUMER}"]   = date("y", $T["za_data_in"]) . "/" . date("m", $T["za_data_in"]) . "/" . long_id($T["za_id"], 6);
                $C["{DATA}"]    = date("Y-m-d H:i", $T["za_data_in"]);
                $C["{WARTOSC}"] = number_format($T["za_wartosc"], 2, ",", "");
                $C['{URL}']     = $_GLOBAL['page_url'];
                $C['{LANG}']    = $_GLOBAL['lang'];
                $B["{IT_ITEM}"] .= get_template($item, $C, '', 0);
            }
            $DD[]          = "IT_BRAK";
            $B["{ILOSC}"]  = $Cnawi->get_pozycje();
            $B["{IDZDO}"]  = $Cnawi->get_idzdo();
            $B["{STRONY}"] = $Cnawi->prn_strony();
        } else {
            $DD[] = "IT_LISTA";
        }

        if ($Cuzytkownik->is_klient()) {
            $B['{PANELMENU}'] = '
                <div class="steps">
                    <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/rabat/"><div>' . $L['{T_TWOJ_RABAT}'] . '</div></a></div>
                    <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/obserwowane/"><div>' . $L['{T_OBSERWOWANE}'] . '</div></a></div>
                    <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/uzytkownik/dane/"><div>' . $L['{T_MOJE_DANE}'] . '</div></a></div>
                    <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/zamowienie/"><div class="choose">' . $L['{T_HISTORIA_ZAMOWIEN}'] . '</div></a></div>
                    <div class="panelmenu"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/koszyk/"><div>' . $L['{T_PRZEJDZ_DO_KOSZYKA}'] . '</div></a></div>
                </div>';
        } else $B['{PANELMENU}'] = '';

        $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
        $A["{STID}"]  = $Ccms->st_id;
        $A["{TYTUL}"] = $Ccms->get_tytul();
        $A["{TRESC}"] = get_template($tpl, $B, $DD, 0);

        return get_template("module", $A, '');
    }

    /**
     * Detale zamówienia
     *
     * @return string Strona z detalami zamówienia
     */

    function prn_detal() {
        global $db, $Ccms, $Cuzytkownik, $_GLOBAL;
        $tpl  = get_template("shop_order_sumary");
        $tpl  = get_tag($tpl, "IT_ITEM", $item);
        $DD[] = "IT_ACTION";
        $B["{DUPA}"]    = date("Y-m-d H:i", $ZA["za_data_in"]);
        $q = "select za.*,do_nazwa,ko_email from " . dn("sklep_zamowienie") . " za left join " . dn("sklep_dostawa") . " on za_do_id=do_id ";
        $q .= "left join " . dn("kontrahent") . " on za_ko_id=ko_id ";
        $q .= "where za_id='" . $this->id . "' and za_ko_id=" . $Cuzytkownik->get_id();
        $ZA = $db->onerow($q);
        if (!is_array($ZA)) redirect("zamowienie.htm");
        $B["{DATA}"]    = date("Y-m-d H:i", $ZA["za_data_in"]);
        $B["{NUMER}"]   = date("y", $ZA["za_data_in"]) . "/" . date("m", $ZA["za_data_in"]) . "/" . long_id($ZA["za_id"], 6);
        $LST            = get_lista("order_status");
        $B["{STATUS}"]  = $LST[$ZA["za_status"]];
        $T              = $db->onerow("select pl_nazwa from " . dn("sklep_platnosc") . " where pl_id=" . $ZA["za_pl_id"]);
        $ZA["pl_nazwa"] = $T[0];

        $B["{NAZWA}"] = s($ZA["za_ko_nazwa"]);
        if ($UZ["ko_nip"] != "") $B["{NIP}"] = s($ZA["za_ko_nip"]);
        else $DD[] = "IT_NIP";
        $B["{ADRES}"] = s($ZA["za_ko_kod"] . " " . $ZA["za_ko_miasto"] . ", " . $ZA["za_ko_ulica"] . " " . $ZA["za_ko_ulica_dom"] . " " . $ZA["za_ko_ulica_lok"]);

        $B["{DO_NAZWA}"]   = s($ZA["za_do_nazwa"]);
        $B["{DO_ADRES}"]   = s($ZA["za_do_kod"] . " " . $ZA["za_do_miasto"] . ", " . $ZA["za_do_adres"]) . " (" . $ZA["za_do_kraj"] . ")";
        $B["{DOSTAWA}"]    = s($ZA["do_nazwa"]);
        $B["{WARTOSC_DO}"] = number_format($ZA["za_do_koszt"], 2, ".", "");
        $B["{SUMA}"]       = number_format($ZA["za_wartosc_brutto"] + $ZA["za_do_koszt"], 2, ".", "");
        $B["{PLATNOSC}"]   = s($ZA["pl_nazwa"]);

        $i  = 1;
        $nr = $db->query("select * from " . dn("sklep_zamowienie_pozycja") . " where zp_za_id=" . $this->id . " order by zp_id");
        while ($T = $db->fetch($nr)) {
            $C              = array();
            $C["{LP}"]      = $i;
            $C["{MOD}"]     = $i++ % 2;
            $C["{ILE}"]     = $T["zp_ilosc"];
            $C["{ID}"]      = $T["zp_pr_id"];
            $C["{ATRYBUT}"] = ($T["zp_at_nazwa"]);
            $C["{CENA}"]    = number_format($T["zp_cena_brutto"], 2, ".", "");
            $C["{WARTOSC}"] = number_format($T["zp_cena_brutto"] * $T["zp_ilosc"], 2, ".", "");
			if ($_GLOBAL['langid'] != 1) {
                $q           = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $T['zp_pr_id'] . " and langid=" . $_GLOBAL['langid'] . " and name='nazwa';";
                $r           = $db->query($q);
                $translation = $db->fetch($r);

                $product_name = $translation['description'];
            } else {
                $product_name = $T["zp_pr_nazwa"];
            }
            $C["{NAZWA}"]   = hs($product_name);
            $C["{INFO}"]    = hs($T["zp_pr_indeks"]);
            $B["{IT_ITEM}"] .= get_template($item, $C, '', 0);
        }
        if ($ZA["za_status"] == 0) {
            $nr = $db->query("select pl_id,pl_widoczna,pl_parametr from " . dn("sklep_platnosc") . " where pl_id in (2,3)");
            while ($T = $db->fetch($nr)) $PL[$T[0]] = $T;

            $D["{ID}"]    = "ZM/" . $ZA["za_id"];
            $D["{KWOTA}"] = number_format($ZA["za_wartosc_brutto"] + $ZA["za_do_koszt"], 2, ".", "");
            get_head('<script src="https://secure.przelewy24.pl/external/formy.php?id=' . $PL[3]["pl_parametr"] . '&amp;wersja=2&amp;encoding=utf-8&sort=2" type="text/javascript"></script>');
            $D["{P24_ZAPLAC}"] = '
      <form action="https://secure.przelewy24.pl/index.php" method="post" id="p24" class="jqTransform">
      <input type="hidden" name="p24_id_sprzedawcy" value="' . $PL[3]["pl_parametr"] . '"/>
      <input type="hidden" name="p24_kwota" value="' . number_format(($ZA["za_wartosc_brutto"] + $ZA["za_do_koszt"]) * 100, 0, "", "") . '"/>
      <input type="hidden" name="p24_email" value="' . s($ZA["ko_email"]) . '"/>
      <input type="hidden" name="p24_session_id" value="' . $ZA["za_id"] . "," . time() . '"/>
      <input type="hidden" name="p24_klient" value="' . s($ZA["za_ko_nazwa"]) . '"/>
      <input type="hidden" name="p24_adres" value="' . s($ZA["za_ko_ulica"] . " " . $ZA["za_ko_ulica_dom"] . " " . $ZA["za_ko_ulica_lok"]) . '"/>
      <input type="hidden" name="p24_kod" value="' . s($ZA["za_ko_kod"]) . '"/>
      <input type="hidden" name="p24_miasto" value="' . s($ZA["za_ko_miasto"]) . '"/>
      <input type="hidden" name="p24_kraj" value="' . s($ZA["za_ko_kraj"]) . '"/>
      <input type="hidden" name="p24_opis" value="' . s('Zamówienie nr ' . $ZA['za_id'] . ' z dnia ' . date("Y-m-d H:i", $ZA["za_data_in"])) . '"/>
      <input type="hidden" name="p24_return_url_ok" value="' . $_GLOBAL["page_url"] . $_GLOBAL['lang'] . '/zakup/message/ok/"/>
      <input type="hidden" name="p24_return_url_error" value="' . $_GLOBAL["page_url"] . $_GLOBAL['lang'] . '/zakup/message/error/"/>
      
      <input type="image" src="' . $_GLOBAL['page_url'] . 'files/cms/6/przelewy24.gif" name="s_" /><br/><br/>
      <div id="zamdet"> 
      <script type="text/javascript">m_formy2();</script>
      </div><br/><br/>
      <input type="submit" value="zapłać teraz" name="s_2" class="push" />
      </form>    
      ';


            file_put_contents('__przelewy24.log', $D["{P24_ZAPLAC}"] . '\n\n\n\n', FILE_APPEND);

            /*
            <input type="hidden" name="p24_return_url_ok" value="'.$_GLOBAL["page_url"].'/zakup.htm?a=p24&v=ok"/>
                  <input type="hidden" name="p24_return_url_error" value="'.$_GLOBAL["page_url"].'/zakup.htm?a=p24&v=error"/>
             */
            $B["{IT_POST_PAY}"] = get_template("shop_order_post_payment", $D);
        }

        $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
        $A["{STID}"]  = $Ccms->st_id;
        $A["{TYTUL}"] = $Ccms->get_tytul();
        $A["{TRESC}"] = get_template($tpl, $B, $DD, 0);

        return get_template("module", $A, '');

    }
}

?>