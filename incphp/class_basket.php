<?php
/**
 * Klasa obsługi koszyka
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

class basket extends layout {
    /**
     * Tryb wyświetlania listy produktów
     * @var string l-large, m-medium, s-small
     */
    var $widok_listy = "l";

    /**
     * Konstruktor klasy
     * @param array $ARG Parametry wejściowe
     * @return shop
     */
    function basket() {
        if (isset($_POST["submit_dokoszyka"]) or isset($_POST["submit_dokoszyka_2_x"])) {
            $this->dodaj((int)$_POST["id"], (int)$_POST["rozmiar"], 1, "produkt");
        }
    }

    function baza_dodaj($id, $rozmiar, $ile, $typ, $cena, $tryb = "update") {
        global $db, $_GLOBAL;

        $T   = $db->onerow("select ilosc from " . dn("sklep_koszyk") . " where session_id='" . session_id() . "' and pr_id=" . $id . " and at_id=" . $rozmiar . " and typ='" . $typ . "'");
        $ret = true;
        if ($_GLOBAL["shop_stan_kontrola"] == "tak") {
            $product   = $db->onerow('SELECT pr_indeks FROM mm_produkt WHERE pr_id = ' . $id);
            $index     = $product['pr_indeks'];
            $index[10] = '%';
            $query     = '  SELECT SUM(ms_ilosc) ms_ilosc
                  FROM ' . dn("magazyn_stan") . '
                  LEFT JOIN ' . dn('magazyn') . ' on ms_ma_id = ma_id
                  LEFT JOIN ' . dn('produkt') . ' on ms_pr_id = pr_id
                  WHERE ms_ilosc > 0
                    AND ms_ma_id NOT IN (' . $_GLOBAL["omitted_stores"] . ')
                    AND pr_indeks LIKE \'' . $index . '\' and ms_at_id = ' . $rozmiar . '
                  ORDER BY pr_indeks, ma_nazwa';
            $P         = $db->onerow($query);

            // $P = $db->onerow("select sum(ms_ilosc) from ".dn("magazyn_stan")." where ms_pr_id=".(int)$id." and ms_at_id=".$rozmiar);
            if (($tryb == "update" and $P[0] < $ile + $T[0]) or ($tryb != "update" and $P[0] < $ile)) {
                if ($tryb == "update") {
                    onload("alert('Niewystarczająca ilość produktów na magazynie do zrealizowania tego zamówienia.\\nZmniejsz ilość produktu:" . $T["pr_nazwa"] . ", roz." . $T["at_nazwa"] . "')");
                }
                $ret = false;
            }
            debug('result: ' . $ret);
        }

        if (is_array($T)) {
            $q = "update " . dn("sklep_koszyk") . " set ilosc=" . ($tryb == "update" ? "ilosc+" : "") . $ile . " where session_id='" . session_id() . "' and pr_id=" . $id . " and at_id=" . $rozmiar . " and typ='" . $typ . "'";
        } else {
            $q = "insert " . dn("sklep_koszyk") . " set cena='" . $cena . "', data_in=" . time() . ", ilosc=" . $ile . ",session_id='" . session_id() . "',pr_id=" . $id . ",at_id=" . $rozmiar . ", typ='" . $typ . "'";
        }

        debug($q);
        $db->query($q);
        if ($tryb == "update") onload("if(confirm('{T_PRODUKT_WKOSZU}')) {window.location='" . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/cart/'};");

        return $ret;
    }

    /**
     * Dodanie kolejnej pozycji do korzyka
     * @param integer $id  id produktu
     * @param string $rozmiar
     * @param integer $ile Ilość
     * @param string $typ
     * @param string $tryb
     * @return bool
     */
    function dodaj($id, $rozmiar, $ile, $typ, $tryb = "update") {
        global $db;

        $q = "select pr_cena_w_brutto from " . dn("produkt") . " left join " . dn("produkt_atrybuty") . " on pr_id=pa_pr_id where pr_id=" . $id . " and pa_at_id=" . $rozmiar;
        debug($q);
        $T = $db->onerow($q);
        if (is_array($T)) {
            return $this->baza_dodaj($id, $rozmiar, $ile, $typ, $T[0], $tryb);
        }
    }

    /**
     * Usuwanie pozycji z koszyka
     *
     * @param integer $id     identyfikator produktu
     * @param string $rozmiar Atrybut
     * @param string $typ
     */
    function usun($id, $rozmiar, $typ) {
        global $db;

        $q = "delete from " . dn("sklep_koszyk") . " where session_id='" . session_id() . "' and pr_id=" . $id . " and at_id=" . $rozmiar . " and typ='" . $typ . "'";
        $db->query($q);
    }

    /**
     * Wyczyszczenie zawartości koszyka
     *
     */
    function wyczysc() {
        global $db;

        $q = "delete from " . dn("sklep_koszyk") . " where session_id='" . session_id() . "'";
        $db->query($q);
    }

    /**
     * Wartość koszyka
     *
     */
    function get_koszyk_wartosc() {
        global $db;

        $query = "select sum(ilosc), sum(ilosc*pr_cena_w_brutto) from " . dn("sklep_koszyk") . " k left join " . dn("produkt") . " v on k.pr_id=v.pr_id where typ='produkt' and session_id='" . session_id() . "';";

        return $db->onerow($query);
    }

    /**
     * Koszyk mini
     *
     */
    function get_koszyk_mini() {
        global $_GLOBAL;
        $T = $this->get_koszyk_wartosc();
        if (is_array($T) and $T[0]) {
            $DD[]         = "IT_PUSTY";
            $A["{ILE}"]   = $T[0];
            $A["{KWOTA}"] = number_format($T[1], 2, ",", "");
        } else {
            $DD[] = "IT_TOWAR";
            $A    = array();
        }
        $A['{URL}']  = $_GLOBAL['page_url'];
        $A['{LANG}'] = $_GLOBAL['lang'];

        return get_template("shop_basket_mini", $A, $DD);
    }
}

$Cbasket = new basket();
?>