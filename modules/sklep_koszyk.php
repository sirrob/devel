<?php
/**
 * Klasa obsługi prezentacji koszyka
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
 * Klasa obsługi wyświetlania koszyka
 */
class sklep_koszyk extends mb {

    /**
     * Konstruktor klasy sklep_koszyk
     */
    function sklep_koszyk() {
        global $_GLOBAL;

        $this->mode = "prn_koszyk";
        if (isset($_POST["submit_usun"]) or isset($_POST["submit_przelicz"])) {
            global $Cbasket;
            foreach ($_POST as $key => $val) {
                if (substr($key, 0, 8) == "ko_usun_" and $val == "on") {
                    $A = explode("_", substr($key, 8));
                    $Cbasket->usun($A[0], $A[1], "produkt");
                } elseif (substr($key, 0, 7) == "ko_ile_") {
                    $A = explode("_", substr($key, 7));
                    $Cbasket->dodaj($A[0], $A[1], $val, 'produkt', "replace");
                }
            }
        }

        if (isset($_SESSION[SID]["zakup"]["do_id"])) {
            $this->WYNIK["do_id"] = $_SESSION[SID]["zakup"]["do_id"];
        }

        if (isset($_GET["stan"])) $this->WYNIK["stan"] = "error";


        if (isset($_SESSION[SID]['discount_code_fault']) AND ($_SESSION[SID]['discount_code_fault'] == true)) {
            if ($_GLOBAL['langid'] == 2) {
                onload("alert('Invalid discount code or the code has expired!')");
            } else {
                onload("alert('Niepoprawny kod rabatowy lub okres ważności kodu upłynął!')");
            }
            unset($_SESSION[SID]['discount_code_fault']);
        }

        if (isset($_SESSION[SID]['discount_code_used']) AND ($_SESSION[SID]['discount_code_used'] == true)) {
            if ($_GLOBAL['langid'] == 2) {
                onload("alert('Discount code has been already used!')");
            } else {
                onload("alert('Kod rabatowy został już wykorzystany!')");
            }
            unset($_SESSION[SID]['discount_code_used']);
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
     * Wyświetlenie zawartości koszyka z możliwością przeliczenia
     * @return string Widok koszyka
     */
    function prn_koszyk() {
        global $db, $Ccms, $_GLOBAL, $Cuzytkownik;

        $gift=0;

        debug($_GLOBAL['lang']);

        $showCheckRules = 0;

        $rab    = -1;
        $rabPrd = array();

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

        $q = "select ko.*,pr.pr_nazwa,pr2.pr_cena_w_brutto,pr2.pr_cena_a_brutto,at_nazwa,pr2.pr_plik, pd_id, pd_nazwa,kp_ka_id from " . dn("sklep_koszyk") . " ko ";
        $q .= "left join " . dn("sklep_produkt") . " pr on ko.pr_id=pr.pr_id ";
        $q .= "left join " . dn("produkt") . " pr2 on ko.pr_id=pr2.pr_id ";
        $q .= "left join " . dn("produkt_atrybut") . " at on ko.at_id=at.at_id ";
        $q .= "left join " . dn("sklep_producent") . " pd on pr_pd_id=pd_id ";
        $q .= "left join " . dn("sklep_kategoria_produkt") . " ka on ko.pr_id=kp_pr_id ";
        $q .= "where typ='produkt' and session_id='" . session_id() . "' group by pr_id, at_id";
        $nr = $db->query($q);

        $tpl = get_template("shop_basket");

        if ($db->affected_rows()) {
            $item    = "";
            $tpl     = get_tag($tpl, "IT_ITEM", $item);
            $i       = 1;
            $suma    = 0;
            $sumaorg = 0;

            while ($T = $db->fetch($nr)) {
                $LST[]              = $T;
                $CENA[$T["pr_id"]]  = $T["pr_cena_w_brutto"];
                $CENAO[$T["pr_id"]] = $T["pr_cena_w_brutto"];
                $CENA2[$T["pr_id"]] = $T["pr_cena_a_brutto"];
                $CENA3[$T["pr_id"]] = $T["ilosc"];
                $IDS[$T["pr_id"]]   = $T["pr_id"];
                $BRANDS[$T["pr_id"]]   = $T["pd_id"];
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
            if ($_GLOBAL["debug"]) {
                debug('debug mode');

                if ($Cuzytkownik->is_gomezclub()) {
                    debug($Cuzytkownik->getLJPercent());
                    debug($BRANDS);
                }
            }

            $sumadc = 0;
            $CENADC = array();
            if (isset($_SESSION[SID]['discount_code'])) {

                //--START
                //czy jest kod i kod jest z bonow a nie z rabatow?
                $_GLOBAL["db_pre"] = "";
                $r = $db->onerow("SELECT * FROM gift_ticket WHERE code = '".$_SESSION[SID]['discount_code']."'");
//                debug("uwaga");
//                debug($r);
//                debug("po uwaga");

                //jesli jest to czy kod istnieje?
                if (is_array($r) AND (count($r) > 0)) {
                    //czy jest nieuzyty?
                    if($r['status']==1){
                        $gift = $r['value'];
                    }
                    //jeszcze nie zdejmuj, zdejmij przy wyslaniu zamowienia
                }

//                debug($gift);

                $_GLOBAL["db_pre"] = "mm";
                //--KONIEC




                debug("kod rabatowy: " . $_SESSION[SID]['discount_code']);
                $appliedProducts = array();

                $q   = "select * from " . dn('rabat_code') . " where code='" . $_SESSION[SID]['discount_code'] . "' and active=1 and (active_start < NOW() or active_start is null) and (active_stop > NOW() or active_stop is null)";
                $nr  = $db->query($q);
                $row = $db->fetch($nr);

                if (is_array($row)) {
                    $type                                = $row['type'];
                    $amount                              = $row['amount'];
                    $_SESSION[SID]['discount_code_gc']   = $row['gc'];
                    $_SESSION[SID]['discount_code_name'] = $row['name'];

                    // sprawdzenie czy kod tylko dla uprawnionych i jesli tak, czy uzytkownik jest uprawniony
                    $onlyForAllowedUsers = false;
                    $q                   = "SELECT * FROM " . dn('rabat_code_allowed_user') . " WHERE code_id = " . $row['id'];
                    $allowed             = $db->get_all($q);
                    if (count($allowed) > 0) {
                        $onlyForAllowedUsers = true;

                        // sprawdzenie czy uzytkownik jest uprawniony
                        $userAllowedForCode = false;
                        $q                  = "SELECT * FROM " . dn('rabat_code_allowed_user') . " WHERE code_id = " . $row['id'] . " AND user_id = " . $Cuzytkownik->get_id();
                        $allowedUser        = $db->get_all($q);
                        if (count($allowedUser) > 0) {
                            $userAllowedForCode = true;
                        }
                    }
                    // ---


                    // sprawdzenie czy wykorzystal juz kod
                    $userCodeUsed = 0;
                    if ($amount > 0) {
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
							 //  $rab = $Cuzytkownik->getPercent2($pkt);
                            $rab = $Cuzytkownik->getPercent();
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
                            // jesli sie nie laczy z GC to sprobowac naliczyc GC dla produktow nie wchodzacych
                            // DC

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

                           // $rab = $Cuzytkownik->getPercent2($pkt);
							  $rab = $Cuzytkownik->getPercent();
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
                        // ---


                        // naliczanie rabatow
                        switch ($type) {
                            case 1: // %
                                if ($row['all_products'] != 0) {
                                    foreach ($LST as $T) {
                                        if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                                            $sumadc += $CENA[$T['pr_id']] * $T["ilosc"] * ($row['rabat'] / 100);
                                            $CENADC[$T['pr_id']] = $CENA[$T['pr_id']] * $T["ilosc"] - $CENA[$T['pr_id']] * $T["ilosc"] * ($row['rabat'] / 100);
                                            // $CENA[$T["pr_id"]] = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]]*$row['rabat']/100;
                                            $appliedProducts[$T["pr_id"]] = true;
                                        }
                                    }
                                } else {
                                    foreach ($LST as $T) {
                                        if (in_array($T['pr_id'], $dcpIDS)) {
                                            if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                                                $sumadc += $CENA[$T["pr_id"]] * $T["ilosc"] * ($row['rabat'] / 100);
                                                $CENADC[$T['pr_id']] = $CENA[$T["pr_id"]] * $T["ilosc"] - $CENA[$T["pr_id"]] * $T["ilosc"] * ($row['rabat'] / 100);
                                                // $CENA[$T["pr_id"]] = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]]*$row['rabat']/100;
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
                                            $sumadc += $T["ilosc"] * $row['rabat'];
                                            $CENADC[$T['pr_id']] = $CENA[$T["pr_id"]] * $T["ilosc"] - $T["ilosc"] * $row['rabat'];
                                            // $CENA[$T["pr_id"]] = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]]*$row['rabat']/100;
                                            $appliedProducts[$T["pr_id"]] = true;
                                        }
                                    }
                                } else {
                                    foreach ($LST as $T) {
                                        if (in_array($T['pr_id'], $dcpIDS)) {
                                            if (!in_array($T["pr_id"], array_keys($appliedProducts))) {
                                                $sumadc += $T["ilosc"] * $row['rabat'];
                                                $CENADC[$T['pr_id']] = $CENA[$T["pr_id"]] * $T["ilosc"] - $T["ilosc"] * $row['rabat'];
                                                // $CENA[$T["pr_id"]] = $CENA[$T["pr_id"]] - $CENA[$T["pr_id"]]*$row['rabat']/100;
                                                $appliedProducts[$T["pr_id"]] = true;
                                            }
                                        }
                                    }
                                }
                                break;
                        }
                    }
                    else { // nie lapie sie na kod rabatowy, wiec jedziemy po GC
                        //tutaj obliczanie ceny dla gomezclub
                        //pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości koszyka
                        if ($Cuzytkownik->is_gomezclub()) {
                            $pkt = 0;
                            foreach ($CENA as $k => $v) $pkt += $v * $CENA3[$k];
                            //$rab = $Cuzytkownik->getPercent2($pkt); //Gomez club
							  $rab = $Cuzytkownik->getPercent();
                            foreach ($CENA2 as $k => $v) {
                                // efektywny rabat: albo GC albo rabat LJ (108)
                                $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                                debug('rabat: ' . $effectiveRab);
                                if ($CENA[$k] < $v) {
                                    //Przecena - naliczyc połowę rabatu Gomez Club
                                    $rabPrd[$k] = ($effectiveRab) / 2;
                                    $CENA[$k]   = ($CENA[$k] * ((100 - (($effectiveRab) / 2)) / 100));
                                } else {
                                    //Brak Przeceny - naliczyć rabat Gomez Club
                                    $rabPrd[$k] = ($effectiveRab);
                                    $CENA[$k]   = ($CENA[$k] * ((100 - ($effectiveRab)) / 100));
                                }
                            }
                        }
                    }
                }
            }
            else {
                //tutaj obliczanie ceny dla gomezclub
                //pobrać rabat i obliczyć czy nadal taki będzie na podstawie zawartości koszyka
                if ($Cuzytkownik->is_gomezclub()) {
                    $pkt = 0;
                    foreach ($CENA as $k => $v) $pkt += $v * $CENA3[$k];
                   // $rab = $Cuzytkownik->getPercent2($pkt); //Gomez club
					  $rab = $Cuzytkownik->getPercent();
                    foreach ($CENA2 as $k => $v) {
                        // efektywny rabat: albo GC albo rabat LJ (108)
                        $effectiveRab = ($BRANDS[$k] == 108) ? $Cuzytkownik->getLJPercent() : $rab;
                        debug('rabat: ' . $effectiveRab);
                        if ($CENA[$k] < $v) {
                            //Przecena - naliczyc połowę rabatu Gomez Club
                            $rabPrd[$k] = ($effectiveRab) / 2;
                            $CENA[$k]   = ($CENA[$k] * ((100 - (($effectiveRab) / 2)) / 100));
                        } else {
                            //Brak Przeceny - naliczyć rabat Gomez Club
                            $rabPrd[$k] = ($effectiveRab);
                            $CENA[$k]   = ($CENA[$k] * ((100 - ($effectiveRab)) / 100));
                        }
                    }
                }
            }
            // --- to be continued


            $prdMsg = array();

            $rppCountTmp = $rppCount;
            $rppValueTmp = $rppValue;
            $B           = array();
            foreach ($LST as $T) {
                $T["pr_cena_w_brutto"] = $CENA[$T["pr_id"]]; //cena po rabacie
                $C["{LP}"]             = $i;
                $C["{MOD}"]            = $i++ % 2;
                $C["{ID}"]             = $T["pr_id"];
                $C["{ILE}"]            = $T["ilosc"];
                $C["{ATRYBUT}"]        = ($T["at_nazwa"]);
                $C["{AT_ID}"]          = ($T["at_id"]);

                if ($T["ilosc"] > $ST[$T["pr_id"]][$T["at_id"]] and $_GLOBAL["shop_stan_kontrola"] == "tak") {
                    $prdMsg[]     = $T['pr_nazwa'];
                    $C["{ERROR}"] = 'error';
                    $ilosc_error  = true;
                } else {
                    $C["{ERROR}"] = '';
                }

                if (($T["pr_cena_w_brutto"] < $CENAO[$T['pr_id']]) && ($Cuzytkownik->is_login()) && ($Cuzytkownik->is_gomezclub())) {
                    $C["{CENA1}"]    = number_format($CENAO[$T["pr_id"]], 2, ".", ""); //cena indywidualnego towaru
                    $C["{CENA2}"]    = number_format($T["pr_cena_w_brutto"] * $T["ilosc"], 2, ".", ""); //cena indywidualnego towaru
                    $C['{CENAFORM}'] = number_format($T["pr_cena_w_brutto"], 2, ".", "");
                } else {
                    $C["{CENA1}"]    = number_format($T["pr_cena_w_brutto"], 2, ".", ""); //cena indywidualnego towaru
                    $C["{CENAFORM}"] = number_format($T["pr_cena_w_brutto"], 2, ".", ""); //cena indywidualnego towaru
                }

                $C["{WARTOSC}"] = number_format($CENAO[$T["pr_id"]] * $T["ilosc"], 2, ".", ""); //wartosc indywidualnego towaru


                if ($_GLOBAL['langid'] != 1) {
                    $q           = "select * from " . dn('sklep_product_translation') . " where pr_id=" . $T['pr_id'] . " and langid=" . $_GLOBAL['langid'] . " and name='nazwa';";
                    $r           = $db->query($q);
                    $translation = $db->fetch($r);
					$C["{ROZMIAR_LABEL}"] = 'size';
                    $product_name = $translation['description'];
                } else {
					$C["{ROZMIAR_LABEL}"] = 'roz.';
                    $product_name = $T["pr_nazwa"];
                }

                $C["{NAZWA}"]     = hs($product_name);
                $C["{INFO}"]      = hs($T["pd_nazwa"]);
                $C["{URL}"]       = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/produkt/" . $T["pr_id"] . "/" . conv2file(s($T["pd_nazwa"])) . "/" . conv2file(s($T["pr_nazwa"])) . "/";
                $C['{CLUBRABAT}'] = (($rabPrd[$T['pr_id']] == -1) ? 'brak' : (($rabPrd[$T['pr_id']] != "") ? $rabPrd[$T['pr_id']] . '%' : "0%"));

                if (array_key_exists($T["pr_id"], $CENADC)) {
                    $tmpWorth = $CENADC[$T["pr_id"]];
                } else {
                    $tmpWorth = $T["ilosc"] * (($rppGC == 0) ? $CENAO[$T["pr_id"]] : $T["pr_cena_w_brutto"]);
                }

                // related product promotions - applying part 1
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
                    $tmp = round($tmp * 100) / 100;
                    $rppSum += $tmp;
                    $tmpWorth -= $tmp;
                }

                // check if show rules acceptance form
                if ($rppSum > 0) {
                    $showCheckRules = 1;
                }
                // --- to be continued

                $C['{WARTOSPORABACIE}'] = number_format($tmpWorth, 2, ".", ""); //wartosc indywidualnego towaru po rabatach


                $T['pr_plik'] = str_replace('http:////', '', $T['pr_plik']);
                if (substr($T["pr_plik"], 0, 8) == "http:///") {
                    $T["pr_plik"] = substr($T["pr_plik"], 8);
                }

                if (substr($T["pr_plik"], 0, 4) == "http") {
                    $T["pr_plik"] = substr($T["pr_plik"], 8);
                    $T["pr_plik"] = substr($T["pr_plik"], strpos($T["pr_plik"], "/") + 1);
                }

                $T["pr_plik"] = str_replace("/x/", "/m/", get_dirfile($T["pr_plik"]));
                $C["{IMG}"]   = ($T["pr_plik"] != "" and file_exists($T["pr_plik"])) ? str_replace("/m/", "/s/", $T["pr_plik"]) : '/images/nofoto_s.gif';
                $C['{IMG}']   = $_GLOBAL['page_url'] . $C['{IMG}'];
                $B["{IT_ITEM}"] .= get_template($item, $C, '', 0);

                $suma += number_format($T["pr_cena_w_brutto"] * $T["ilosc"], 2, ".", "");
                $sumaorg += number_format($CENAO[$T["pr_id"]] * $T["ilosc"], 2, ".", "");
            }

            if ($ilosc_error) $this->MESS_ERR[] = "Brak wystarczającej ilości \"" . join('", "', $prdMsg) . "\" na magazynie do realizacji Twojego zamówienia.<br><br>";
            $B["{ERROR}"]      = $this->get_komunikat();
            $B["{WARTOSC_DO}"] = '0.00';
            $B["{SUMAORG}"]    = number_format($sumaorg, 2, ".", "");
            $B["{SUMARGC}"]    = ($rppGC == 0) ? number_format(0, 2, ".", "") : number_format(($sumaorg - $suma), 2, ".", "");


            if ($rppGC == 1) {
                $suma = $suma - $sumadc - $rppSum;
            } elseif ($rppGC == 0) {
                $suma = $sumaorg - $sumadc - $rppSum;
            } else {
                $suma = $suma - $sumadc - $rppSum;
            }


            //TUTAJ WAR O ZNIZKE I OBLICZENIE
            $suma = $suma - $gift;
            if($suma<0) $suma =0;

            $B["{SUMA}"] = number_format($suma, 2, ".", "");

            $B['{DISCOUNT_SET}'] = "";

            if ($sumadc > 0) {
                $B['{DISCOUNT_SET}'] .= '<div class="row"><div class="label"><strong>{T_RABAT_DZIEKI_PROMOCJI} ' . $_SESSION[SID]['discount_code_name'] . ':</strong></div><div class="price"><strong>' . number_format($sumadc, 2, ".", "") . ' PLN</strong></div></div>';
            }

            // related product promotions - applaying part 3
            if ($rppSum > 0) {
                $B['{DISCOUNT_SET}'] .= '<div class="row"><div class="label"><strong>{T_RABAT_DZIEKI_PROMOCJI} ' . $rppName . ':</strong></div><div class="price"><strong>' . number_format($rppSum, 2, ".", "") . ' PLN</strong></div></div>';
            }
            // ---


            $DD[] = "IT_EMPTY";
        }
        else {
            $DD[] = "IT_FORM";
        }

        $B['{CODESHOW}']  = 'block';
        $B['{CODE_NAME}'] = (isset($_SESSION[SID]['discount_code'])) ? $_SESSION[SID]['discount_code'] : '{NO_CODE}';

        $B['{CHECK_RULES}']      = ($showCheckRules) ? 1 : 0;
        $B['{SHOW_CHECK_RULES}'] = ($showCheckRules) ? "block" : "none";

        $B['{URL}']   = $_GLOBAL['page_url'];
        $B['{LANG}']  = $_GLOBAL['lang'];
        $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
        $A["{STID}"]  = $Ccms->st_id;
        $A["{TYTUL}"] = $Ccms->get_tytul();
        $A["{TRESC}"] = get_template($tpl, $B, $DD, 0);

        return get_template("module", $A, '');
    }
}

?>