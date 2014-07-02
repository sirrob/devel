<?php
/**
 * Prototyp dla klas zapewniający obsługę wyświetlania układu strony
 *
 * @author    Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since     2008-08-12 12:36:48
 * @link      www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if (!defined('SMDESIGN')) {
    die("Hacking attempt");
}
class layout extends mb {
    /**
     * Typ klasy podrzędnej (shop,cms)
     *
     * @var string;
     */
    var $element_typ;
    /**
     * Rodzaj wyświetlanej informacji (kategoria,produkt,strona)
     *
     * @var string
     */
    var $element;
    /**
     * Elementy wspólne strony
     *
     * @var array
     */
    var $STRONA = array();
    /**
     * Nazwa głównej tabeli z danymi
     *
     * @var string
     */
    var $db_name = "";
    /**
     * Prefiks kolumn w bazie danych
     *
     * @var string
     */
    var $db_pre = "";
    var $id;
    var $menu_id = 1;
    var $top_st_id = 0;
    var $modul = false;


    function layout($ARG) {
        $this->element = $ARG[1];
        $this->id      = $ARG[2];
        $this->id2     = $ARG[3];
        $this->modul   = (($ARG[3] == 'modul') ? true : false);
        $this->generuj();
    }


    function generuj() {
        //  global $db;    
    }


    /**
     * Przygotowanie szablonu strony uzupełnionego o wspólne dynamiczne elementy
     *
     * Funckja przeszukuje kod w poszukiwaniu dynamicznych boksów w postaci {BOX_[NAZWABOKSU]_[ID]}
     *
     * @return string
     */
    function get_strona_tpl() {
        global $Cuzytkownik, $_GET, $_GLOBAL, $L, $db;
        $DD = array();
        $TT = $this->get_baner($this->WYNIK["fo"][$this->db_pre . "_baner"], $this->WYNIK["fo"][$this->db_pre . "_baner_url"]);
        if (!isset($TT["{BANER_TOP}"])) $DD[] = "IT_BANER_TOP";
        $powiel = $this->WYNIK["fo"][$this->db_pre . "_boks_powiel"];
        $id     = $this->id;
        while ($powiel) {
            $id     = $this->WYNIK["strona"][$id]["id_id"];
            $powiel = $this->WYNIK["strona"][$id]["boks_powiel"];
        }

        if ($this->element == 'produkt') $tid = 3;
        else
            if ((isset($_GET['url'])) && ($_GET['url'] == 'selection')) $tid = 3;
            else
                if (($this->element == 1) && ($id == 1) && ($this->element_typ == 'cms')) {
                    $tid = 1;
                } //na pewno tutaj
                else
                    if (($this->element_typ == 'cms') && (!$this->modul) && ($this->id != 71)) {
                        $tid   = 2;
                        $query = "select * from " . dn("cms") . " where st_id=" . (int)$this->id . ";";
                        $res   = $db->query($query);
                        $dane  = $db->fetch($res);
                        if ((!empty($dane['st_tpl'])) && ((int)$dane['st_tpl'] > 0)) {
                            $this->element_typ = 'extra';
                            $tid               = $dane['st_tpl'];
                        }
                    } else
                        if (($this->element_typ == 'cms') && ($this->id == 71)) {
                            $tid               = 5;
                            $this->element     = 'kategoria';
                            $this->element_typ = 'shop';
                        } else
                            if ($this->element == 'shop' && $this->element_typ == 'shop') $tid = 4;
                            else
                                if ($this->id == '122') $tid = 1;
                                else
                                    if ($this->id == '121') $tid = 1;
                                    else
                                        if ($this->id == '219') $tid = 1;
                                        else
                                            if ($this->id == '220') $tid = 1;
                                            else
                                                if ($this->id == '277') $tid = 1;
                                                else
                                                    if ($this->id == '308') $tid = 1;
                                                    else
                                                if ($this->element == 'kategoria') $tid = 4;
                                                else
                                                    if ($this->element == 'produkt') $tid = 3;
                                                    else
                                                        if ($this->element == '44') {
                                                            $tid               = 6;
                                                            $this->element_typ = 'shop';
                                                        } else
                                                            if ($this->element == '42') $tid = 2;
                                                            else
                                                                if ($this->element == '43') $tid = 2;
                                                                else
                                                                    if ($this->element == '59') $tid = 3;
                                                                    else
                                                                        if ($this->element == '57') $tid = 3;
                                                                        else
                                                                            if ($this->element == '58') $tid = 3;
                                                                            else
                                                                                if ($this->element == '48') $tid = 3;
                                                                                else
                                                                                    if ($this->element == '46') $tid = 2;
                                                                                    else $tid = $id;

        //print 'element:'. $this->element . '---id:' . $id . '---tid:' . $tid . '---element_typ:' . $this->element_typ . '<hr>';

        $TT['{URL}']  = $_GLOBAL["page_url"];
        $TT['{LANG}'] = $_GLOBAL["lang"];

//echo "<br><br>files/prototyp/".$this->element_typ."_".$tid.".tpl<br><br>";
        $tpl = my_fread("files/prototyp/" . $this->element_typ . "_" . $tid . ".tpl");
        $tmp = $tpl;
        while (strpos($tmp, "{BOX_") !== false) {
            $tmp                      = substr($tmp, strpos($tmp, "{BOX_") + 5);
            $tag                      = substr($tmp, 0, strpos($tmp, "}"));
            $T                        = explode("_", $tag);
            $TT["{BOX_" . $tag . "}"] = $this->get_boks($T[0], $T[1]);
        }
        if ($this->element_typ == "cms" and $this->id == 1 and file_exists("template/" . TEMPLATE . "/home.css")) {
            get_head("home", "css");
        }

        if ((isset($_GET['sna'])) && (!empty($_GET['sna']))) $TT['{T_SZUKAJ_Value}'] = $_GET['sna'];
        else $TT['{T_SZUKAJ_Value}'] = $L['{T_SZUKAJ_}'];

        return get_template($tpl, $TT, $DD, 0);
    }


    /**
     * Funkcja zwraca pasek lokalizacji
     *
     * @return string
     */
    function get_local() {
        global $_GLOBAL;

        $R[] = '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/">Home</a>';

        if (count($_GLOBAL['PATH']) > 0) {
            foreach ($_GLOBAL['PATH'] as $key => $value) {
                $R[] = '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $value['link'] . '">' . $value['name'] . '</a>';
            }
        }

        return join(" / ", $R);
    }


    /**
     * Funkcja uzupełnia metatagi o dane bieżącej strony
     *
     * @param string $str Oryginalna wartość TAGu
     * @param string $typ Typ TAGu
     * @return string Nowa wartość TAGu
     */
    function get_meta($str, $typ) {
        return $str;
    }


    /**
     * Funkcja podstawia banery do szablonu głównego
     *
     * @param string $baner
     * @return array Tablica do podmiany w szablonie głównym
     */
    function get_baner($baner = "") {
        global $db;
        $TPL = array();
        if ($this->WYNIK["fo"][$this->db_pre . "_baner_powiel"]) {
            $id = $this->WYNIK["fo"][$this->db_pre . "_" . $this->db_pre . "_id"];
            while ($id != 0) {
                if ($this->WYNIK["strona"][$id][$this->db_pre . "_baner_powiel"] == "0") {
                    $baner = $this->WYNIK["strona"][$id][$this->db_pre . "_baner"];
                }
                $id = $this->WYNIK["strona"][$id][$this->db_pre . "_" . $this->db_pre . "_id"];
            }
        }
        $BANER["top"] = $baner;

        if (isset($BANER["top"]) and $BANER["top"] != "") {
            $plik = $BANER["top"];
            switch (substr($plik, -3)) {
                case "swf":
                    $size               = getimagesize($plik);
                    $TPL['{BANER_TOP}'] = '<script type="text/javascript">CreateControl(\'' . $plik . '\',\'' . $size[0] . '\',\'' . $size[1] . '\');</script>';
                    break;
                case "flv":
                case "wmv":
                    $TPL['{BANER_TOP}'] = get_file(substr($plik, 0, -4), substr($plik, -3));
                    break;
                default:
                    $TPL['{BANER_TOP}'] = '<a href="' . s($DZ["bo_link"]) . '"' . ($DZ["bo_link_target"] != "_self" ? '  rel="external"' : '') . '><img src="' . $plik . '" alt=""/></a>';
            }
        }

        if (isset($BANER["sky"]) and $BANER["sky"] != "") {
            $baner              = $BANER["sky_url"] != "" ? '<a href="' . $BANER["top_url"] . ($BANER["sky_url_tryb"] == "_blank" ? ' rel="external"' : '') . '">' . $BANER["sky"] . '</a>' : $BANER["sky"];
            $TPL["{BANER_SKY}"] = $BANER["sky"] != "" ? '<div class="baner_topp">' . $baner . '</div>' : '';
        } else {
            $TPL["{BANER_SKY}"] = '';
        }

        if (isset($BANER["tlo"]) and $BANER["tlo"] != "") {
            get_head('<style type="text/css">body {background: url(' . $BANER["tlo"] . ');}</style>');
        }

        return $TPL;
    }


    function gen_menu_ext($IDS) {
        global $db, $Cuzytkownik;
        if (!count($IDS)) return;
        $RET = array();
        $q   = "select st_id, st_st_id, st_tytul, st_url, st_url_tryb, st_typ,st_modul,st_w_menu_0 from " . dn("cms") . " ";
        $q .= "where st_id in (" . join(",", $IDS) . ") " . (!$Cuzytkownik->test_right("admin") ? " and st_widoczna='1'" : "") . " order by st_pozycja, st_id";
        $nr = $db->query($q);
        while ($T = $db->fetch($nr)) {
            $RET[$T[1]][$T[0]] = $T;
        }

        return $RET;
    }


    function get_menu_ext(&$ST, $id, $poz = 0, $rozwin = 0) {
        global $_GLOBAL;
        if (!is_array($ST[$id])) return '';
        foreach ($ST[$id] as $key => $T) {
            ++$i;
            $url    = m_url("cms", $T["st_id"], s($T["st_tytul"]), $T["st_url"], $T["st_typ"], $T["st_modul"]);
            $target = $T["st_url_tryb"] == "_blank" ? ' rel="external"' : '';
            $tytul  = strip_tags(s($T["st_tytul"]));

            if (is_array($ST[$key]) and $key == $this->st_id) {
                $class                             = ' class="linkextsel"';
                $this->WYNIK["boks_rozwin_onload"] = true;
            } elseif ($key == $this->st_id) {
                $class                             = ' class="linksel"';
                $this->WYNIK["boks_rozwin_onload"] = true;
            } elseif (in_array($key, $this->WYNIK["boks_rozwin"])) $class = ' class="linkextnosel"'; elseif (is_array($ST[$key])) $class = ' class="linkext"'; else $class = '';

            if ($poz < 2 and in_array($key, $this->WYNIK["boks_rozwin"]) and $rozwin and $T["st_w_menu_0"] == "1") $ext = $this->get_menu_ext($ST, $key, $poz + 1);
            else $ext = "";
            $Q[] = '<li class="menuext' . $poz . (($i == count($ST[$id]) and $ext == '') ? 'end' : '') . '"><a href="' . $url . '"' . $class . $target . ' onclick="">' . $tytul . '</a></li>' . $ext;
        }

        return join("\n", $Q);
    }


    function get_menu_main_select($ST, $sid, $pre = "") {
        if (!isset($ST[$sid])) return '';
        foreach ($ST[$sid] as $key => $nazwa) {
            $ret .= '<option value="' . $key . '"{SEL' . $key . '}>' . $pre . hs($nazwa) . '</option>';
            $ret .= $this->get_menu_main_select($ST, $key, $pre . " "); //&nbsp;&nbsp;&nbsp;&nbsp;
        }

        return $ret;
    }


    function get_menu_main_select2($ST, $sid, $pre, $CAT, $level = 0) {

        $ret = '';
        $level++;
        $tmp = '';
        if (!isset($ST[$sid])) return '';
        foreach ($ST[$sid] as $key => $nazwa) {
            $tmp = '';
            if (($level < 3)) {
                $tmp = $this->get_menu_main_select2($ST, $key, $pre . "", $CAT, $level); //&nbsp;&nbsp;&nbsp;&nbsp;
                if (!empty($tmp))
                    $ret .= '<option value="' . $key . '"{SEL' . $key . '}>' . $pre . hs($nazwa) . '</option>';
                $ret .= $tmp;
            } else {
                if (isset($CAT[$key])) {
                    $tmp = $this->get_menu_main_select2($ST, $key, $pre . "", $CAT, $level); //&nbsp;&nbsp;&nbsp;
                    $ret .= '<option value="' . $key . '"{SEL' . $key . '}>' . $pre . hs($nazwa) . '</option>';
                    $ret .= $tmp;
                }
            }
        }

        return $ret;
    }

    /**
     * Funkcja generująca menu na stronie WOMEN, MEN, KIDS
     * @return string Menu
     */
    function get_menu_main($top, $mode) {
        global $Cspeed, $db, $Cuzytkownik, $_GLOBAL, $turl, $sURL, $L;

        $out = '<div class="menu-news-label"><a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/nowosci/">' . $L["{T_B_NOWOSCI}"] . '</a></div>
               <div class="accordions">';

        $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . $this->id . " order by ka_pozycja;";
        $res   = $db->query($query);
        $lp    = 0;
        while ($item = $db->fetch($res)) {
            $T[0] = 'sklep,' . $item['ka_id'] . ',' . $item['ka_nazwa'] . '.htm';
            $T[1] = '';
            $T[2] = $item['ka_nazwa'];
            $out .= "\n" . '<h3>
                        <a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $turl[1] . '/' . $item['ka_alias'] . '/" class="' . $class . '" style="width: 172px;">' . $T[2] . '</a>
                        <div style="float: right;width: 20px;position: relative;top: 5px"><a href="javascript:othermenu(' . $lp . ')"><img src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow.jpg" alt=""></a></div>
                     </h3>' . "\n";
            $out .= '<div id="otm' . $lp . '">';

            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna>0 order by ka_pozycja;";
            $res2  = $db->query($query);
            while ($item2 = $db->fetch($res2)) {
                //$T[0] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $turl[1] . '/' . conv2file(s($item2['ka_nazwa'])) . '/';
                $T[0] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $turl[1] . '/' . $item2['ka_alias'] . '/';
                $T[1] = '';
                $T[2] = $item2['ka_nazwa'];
                $out .= "\n" . '<a href="' . $T[0] . '" class="' . $class . '">' . $T[2] . '</a><br>' . "\n";
            }
            $out .= '<div class="separator" style="padding-bottom:0px;"></div>';
            $out .= '</div>';
            if ($lp++ == 0) $out .= '</div><div class="accordions">';
        }
        $out .= '</div>';

        return $out;
    }


    /**
     * Czy uzytkownik ma jakies zamowienie z statusem zrealizowane
     * @param $id User ID
     * @return bool True if user has such a order, false otherwise
     */
    private function checkUserFinalizedOrders($id) {
        global $db;

        $q   = "SELECT COUNT(za_id) AS count FROM mm_sklep_zamowienie WHERE (za_status = 3 OR za_status = 2) AND za_ko_id = $id";
        $row = $db->onerow($q);
        if ($row['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Funkcja generuje menu na stronie SALE
     * @return string Menu
     */
    function get_menu_sale($top, $mode) {
        global $Cspeed, $db, $Cuzytkownik, $_GLOBAL, $sURL, $_POST, $L;

        $userID         = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
        $userWithOrders = $this->checkUserFinalizedOrders($userID);
        // jesli przez internet nie kupili, to moze lokalnie...
        if (!$userWithOrders) {
            $userWithOrders = ($Cuzytkownik->is_gomezclub() && ($Cuzytkownik->get_gcsuma() > 0));
        }
        $wh             = ($userID > 0) ? (($userWithOrders) ? "(pr_widoczny='1' OR pr_widoczny='2' OR pr_widoczny='3')" : "(pr_widoczny='1' OR pr_widoczny='2')") : "pr_widoczny='1'";
        $bwh            = ($userID > 0) ? (($userWithOrders) ? "(pd_szukaj='1' OR pd_szukaj='2' OR pd_szukaj='3')" : "(pd_szukaj='1' OR pd_szukaj='2')") : "pd_szukaj='1'";

        $out = '';

        if ($top == 'brands') {
            $brands = '';
            if (count($sURL) > 2) {
                $query = "select * from " . dn('sklep_kategoria') . "
                      where ka_widoczna='1' and ka_ka_id=" . (($sURL[2] == 'women') ? (($_GLOBAL['langid'] != 1) ? '219' : '121') : (($_GLOBAL['langid'] != 1) ? '220' : '122')) . ";";
                $res   = $db->query($query);
                while ($item = $db->fetch($res)) {
                    $query = "select distinct pd_id,pd_nazwa, pd_alias 
                    from " . dn('sklep_produkt') . " sp," . dn('sklep_producent') . "," . dn('magazyn_stan') . "," . dn('produkt') . " p,
                         " . dn('sklep_kategoria_produkt') . ", " . dn('sklep_kategoria') . "
                    where sp.pr_pd_id=pd_id
                          and ms_pr_id=sp.pr_id
                          and p.pr_id=sp.pr_id
                          and kp_pr_id=sp.pr_id
                          and ka_id=kp_ka_id
                          and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ")
                          and " . $wh . " 
                          and " . $bwh . " 
                          and (pr_cena_a_brutto*0.7)>=pr_cena_w_brutto 
                          and ms_ilosc>0 
                          and ka_id = " . $item['ka_id'] . "
                          and pr_stan>0
                  order by pd_nazwa;";


                    $resp = $db->query($query);
                    while ($itemp = $db->fetch($resp)) {
                        if (!empty($itemp['pd_id'])) $brands[] = $itemp['pd_id'];
                    }

                    $query = "select * from " . dn('sklep_kategoria') . "
                      where ka_widoczna='1' and ka_ka_id=" . $item['ka_id'] . ";";
                    $res2  = $db->query($query);
                    while ($item2 = $db->fetch($res2)) {
                        $query = "select distinct pd_id,pd_nazwa, pd_alias 
                              from " . dn('sklep_produkt') . " sp, " . dn('sklep_producent') . ", " . dn('magazyn_stan') . ", 
                                   " . dn('produkt') . " p, " . dn('sklep_kategoria_produkt') . ", " . dn('sklep_kategoria') . "
                              where sp.pr_pd_id=pd_id
                                    and ms_pr_id=sp.pr_id
                                    and p.pr_id=sp.pr_id
                                    and kp_pr_id=sp.pr_id
                                    and ka_id=kp_ka_id
                                    and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ")
                                    and " . $wh . " 
                                    and " . $bwh . " 
                                    and ka_widoczna='1'
                                    and (pr_cena_a_brutto*0.7)>=pr_cena_w_brutto 
                                    and ms_ilosc>0 
                                    and ka_id = " . $item2['ka_id'] . "
                                    and pr_stan>0
                          order by pd_nazwa;";

                        $resp = $db->query($query);
                        while ($itemp = $db->fetch($resp)) {
                            if (!empty($itemp['pd_id'])) $brands[] = $itemp['pd_id'];
                        }

                        $query = "select * from " . dn('sklep_kategoria') . "
                            where ka_widoczna='1' and ka_ka_id=" . $item2['ka_id'] . ";";
                        $res3  = $db->query($query);
                        while ($item3 = $db->fetch($res3)) {
                            $query = "select distinct pd_id,pd_nazwa, pd_alias 
                                  from " . dn('sklep_produkt') . " sp, " . dn('sklep_producent') . ", " . dn('magazyn_stan') . ",
                                       " . dn('produkt') . " p, " . dn('sklep_kategoria_produkt') . ", 
                                       " . dn('sklep_kategoria') . "
                                  where sp.pr_pd_id=pd_id
                                        and ms_pr_id=sp.pr_id
                                        and p.pr_id=sp.pr_id
                                        and kp_pr_id=sp.pr_id
                                        and ka_id=kp_ka_id
                                        and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ")
                                        and " . $wh . " 
                                        and " . $bwh . " 
                                        and ka_widoczna='1'
                                        and (pr_cena_a_brutto*0.7)>=pr_cena_w_brutto 
                                        and ms_ilosc>0 
                                        and ka_id = " . $item3['ka_id'] . "
                                        and pr_stan>0
                          order by pd_nazwa;";

                            $resp = $db->query($query);
                            while ($itemp = $db->fetch($resp)) {
                                if (!empty($itemp['pd_id'])) $brands[] = $itemp['pd_id'];
                            }
                        }
                    }
                }

                if (count($brands) > 0) {
                    $query  = "select * from " . dn('sklep_producent') . " where pd_id in (" . join(',', $brands) . ") and " . $bwh . ";";
                    $res    = $db->query($query);
                    $brands = '';
                    while ($item = $db->fetch($res)) {
                        $brands .= '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/' . $sURL[1] . '/' . ((($sURL[2] == 'men') || ($sURL[2] == 'women')) ? $sURL[2] . '/' : '') . $item['pd_alias'] . '/">' . s($item['pd_nazwa']) . '</a><br>';
                    }
                }

            } else {
                $query = "select distinct pd_id,pd_nazwa, pd_alias 
                      from " . dn('sklep_produkt') . " sp," . dn('sklep_producent') . ", 
                           " . dn('magazyn_stan') . ", " . dn('produkt') . " p,
                           " . dn('sklep_kategoria_produkt') . "
                      where sp.pr_pd_id=pd_id
                      and ms_pr_id=sp.pr_id
                      and p.pr_id=sp.pr_id
                      and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ")
                      and " . $wh . " 
                      and " . $bwh . " 
                      and (pr_cena_a_brutto*0.7)>=pr_cena_w_brutto 
                      and  ms_ilosc>0
                      and kp_pr_id = p.pr_id
                      and kp_widoczna='1'
                  order by pd_nazwa;";
                $res   = $db->query($query);
                while ($item = $db->fetch($res)) {
                    $brands .= '<a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/' . $sURL[1] . '/' . $item['pd_alias'] . '/">' . s($item['pd_nazwa']) . '</a><br>';
                }
            }

            $out .= '<form action="" method="post" id="salekmform" class="saleform jqTransform" style="height: 50px;">
            
            <div class="form-element" style="line-height: 24px;height: 20px;width: 114px;">
                <input type="checkbox" value="women" ' . (($sURL[2] == 'women') ? ' checked ' : '') . ' name="women" onclick="location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/sale/brands/\'+this.value+\'/\'">' . $L['{T_KOBIETA}'] . '<br>
            </div>
            <div class="form-element" style="line-height: 24px;height: 20px;width: 114px;">
                <input type="checkbox" value="men" ' . (($sURL[2] == 'men') ? ' checked ' : '') . ' name="men" onclick="location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/sale/brands/\'+this.value+\'/\'">' . $L['{T_MEZCZYZNA}'] . '
            </div>
            </form>
            <div class="separator"></div>
            <div class="sale-brands-menu">' . $brands . '</div>';
//$L['{T_KOBIETA}'] = 'Women';
//$L['{T_MEZCZYZNA}']

        } else {
            if ($top == 'women') $top = (($_GLOBAL['langid'] != 1) ? 219 : 121);
            if ($top == 'men') $top = (($_GLOBAL['langid']) ? 220 : 122);
            $out = '
            <form action="" method="" class="niceform" style="padding-top: 0px; margin-top: 0px;">
            <select name="wm" id="salewm" size="1" style="width: 155px;" onchange="location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/sale/\'+this.value+\'/\'">
            <option value="women" ' . (($top == 121) || ($top == 219) ? ' selected ' : '') . '>Women</option>
            <option value="men" ' . (($top == 122) || ($top == 220) ? ' selected ' : '') . '>Men</option>
            <option value="brands" ' . (($top == 'brands') ? ' selected ' : '') . '>Brands</option>
            </select>
            </form><br><br>
            <!-- <div class="separator"></div> -->
            <div class="accordions">';

            $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna='1' and ka_ka_id=" . (int)$top . " order by ka_pozycja;";
            $res   = $db->query($query);
            $lp    = 0;
            while ($item = $db->fetch($res)) {
                $T[0] = 'sklep,' . $item['ka_id'] . ',' . $item['ka_nazwa'] . '.htm';
                $T[1] = '';
                $T[2] = $item['ka_nazwa'];
                $out .= "\n" . '<h3>
                        <a href="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/' . $sURL[1] . '/' . $item['ka_alias'] . '/" class="' . $class . '">' . s($T[2]) . '</a>
                        <div style="float: right;width: 20px;position: relative;top: 5px"><a href="javascript:othermenu(' . $lp . ')"><img src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow.jpg" alt=""></a></div>
                      </h3>' . "\n";
                $out .= '<div id="otm' . $lp . '">';

                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna='1' order by ka_pozycja;";
                $res2  = $db->query($query);
                while ($item2 = $db->fetch($res2)) {
                    $q = "select ms_pr_id,ms_at_id,at_nazwa 
                  from " . dn('magazyn_stan') . "," . dn('produkt_atrybut') . "," . dn('produkt') . " sp,
                       " . dn('sklep_kategoria_produkt') . " ";
                    $q .= " where ms_at_id=at_id ";
                    $q .= " and sp.pr_id=ms_pr_id ";
                    $q .= " and kp_pr_id=sp.pr_id ";
                    $q .= " and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") and kp_ka_id = " . $item2['ka_id'] . " ";
                    $q .= " and (pr_cena_a_brutto*0.7)>=pr_cena_w_brutto ";
                    $q .= " and ms_ilosc>0 ";
                    $q .= " and at_nazwa != 'Pusty' ";
                    //$q .= " group by at_nazwa order by replace(at_nazwa,'Pusty','_____');";
                    $q .= " group by at_nazwa order by at_nazwa;";

                    $res3 = $db->query($q);
                    if ($db->num_rows($res3) > 0) {
                        $T[0] = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/' . $sURL[0] . '/' . $sURL[1] . '/' . $item2['ka_alias'] . '/';
                        $T[1] = '';
                        $T[2] = $item2['ka_nazwa'];
                        $out .= "\n" . '<a href="' . $T[0] . '" class="' . $class . '">' . s($T[2]) . '</a><br>' . "\n";
                    }
                }
                $out .= '<div class="separator" style="padding-bottom:0px;"></div>';
                $out .= '</div>';
                if ($lp++ == 0) $out .= '</div><div class="accordions">';
            }
            $out .= '</div>';
        }

        return $out;
    }

    function get_boks_shop($DANE, $img = 0, $link = "", $app = "") {
        if (!is_array($DANE)) return '';
        $DD[] = $img ? "IT_TEXT" : "IT_FOTO";
        $tag  = $img ? "IT_FOTO" : "IT_TEXT";
        if ($link == "") $DD[] = "IT_WIECEJ";
        $tpl     = get_template("box_product" . $app, '', $DD);
        $tpl     = get_tag($tpl, $tag, $item);
        $tpl     = get_tag($tpl, "ROZMIAR", $rozmiar);
        $rozmiar = "" ? "m" : $rozmiar;
        $i       = 0;
        foreach ($DANE as $T) {
            $A                = array();
            $A["{NR}"]        = ++$i;
            $A["{CENA}"]      = number_format($T["pr_cena"], 2, "", ",");
            $A["{NAZWA}"]     = s($T["pr_nazwa"]);
            $A["{ALT}"]       = h($T["pr_nazwa"]);
            $A["{PRODUCENT}"] = s($T["pd_nazwa"]);
            $A["{IMG}"]       = ($T["pr_plik"] != "" and file_exists($T["pr_plik"])) ? str_replace("/m/", "/" . $rozmiar . "/", $T["pr_plik"]) : 'images/nofoto_' . $rozmiar . '.gif';
            $A["{URL}"]       = "sklep," . $T["kp_ka_id"] . "," . $T["pr_id"] . "," . conv2file(s($T["pr_nazwa"])) . ".htm";
            $DD               = $T["pd_nazwa"] != "" ? '' : array("IT_PRODUCENT");
            $TT["{" . $tag . "}"] .= get_template($item, $A, $DD, 0);
        }
        $TT["{WIECEJ}"] = $link;

        return get_template($tpl, $TT, '', 0);
    }


    /**
     * Funkcja generująca rożnego rodzaju moduły, menu oraz filter
     * @param string $typ
     * @param string $id
     * @return string Dany box
     */
    function get_boks($typ, $id = "") {
        global $db, $_GLOBAL, $_SESSION, $_GET, $sURL, $L, $Cuzytkownik;

        $userID         = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
        $userWithOrders = $this->checkUserFinalizedOrders($userID);
        $wh             = ($userID > 0) ? (($userWithOrders) ? "(pr_widoczny='1' OR pr_widoczny='2' OR pr_widoczny='3')" : "(pr_widoczny='1' OR pr_widoczny='2')") : "pr_widoczny='1'";
        $bwh            = ($userID > 0) ? (($userWithOrders) ? "(pd_szukaj='1' OR pd_szukaj='2' OR pd_szukaj='3')" : "(pd_szukaj='1' OR pd_szukaj='2')") : "pd_szukaj='1'";

        $ret = "";
        switch ($typ) {
            case "MAINMENU": //mainmeu
                $id                           = $this->menu_id;
                $this->WYNIK["boks_rozwin"]   = array();
                $this->WYNIK["boks_rozwin"][] = $id;
                $top                          = $id;
                while ($id != $this->top_st_id) {
                    if ($this->WYNIK["strona"][$id]["id_id"] == $this->top_st_id) $top = $id;
                    $id                           = $this->WYNIK["strona"][$id]["id_id"];
                    $this->WYNIK["boks_rozwin"][] = $id;
                }
                $ret = '<div class="menuwew">' . $this->get_menu_main($top, "menu_cms") . '</div>';
                break;
            case "SHOPMENU": //mainmeu
                $id                           = $this->id;
                $this->WYNIK["boks_rozwin"]   = array();
                $this->WYNIK["boks_rozwin"][] = $id;
                //shop menu np: women, men, kids
                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $id . ";";
                $res   = $db->query($query);
                while ($item = $db->fetch($res)) {
                    $this->WYNIK['boks_rozwin'][] = $item['ka_id'];
                }

                $top = $id;
                while ($id != 0) {
                    if ($this->WYNIK["strona"][$id]["id_id"] == 0) $top = $id;
                    $id                           = $this->WYNIK["strona"][$id]["id_id"];
                    $this->WYNIK["boks_rozwin"][] = $id;
                } //610px
                $ret = '<div class="menuwew jspPanes scroll-panes" style="height: auto;width: 200px;position:relative;">' . $this->get_menu_main(1, "menu_sklep") . '</div>';
                break;
            case "SHOPFILTER":
                $ret                         = '';
                $id                          = $this->id;
                $pricemin                    = 999999;
                $pricemax                    = 0;
                $url                         = "sklep," . $id;
                $TT["{URL}"]                 = $url . ".htm?";
                $FILTER                      = array();
                $FILTER_list                 = array();
                $FILTER_list['price']['min'] = 999999;
                $kids                        = array();

                $Q  = array();
                $lj = '';

                if ($id != 2) {
                    if ($this->id != 121 and $this->id != 122 and $this->id != 219 and $this->id != 220 and $this->id != 277 and $this->id != 308) {
                        $Q[] = " (kp_ka_id=" . $this->id . " or ka_ka_id=" . $this->id . ") ";
                    } else {
                        $Q[] = " (kp_ka_id=" . $this->id . ") ";
                    }
                } else {
                    if (isset($_GET['ska'])) $Q[] = " (ka_id=" . $_GET['ska'] . " or ka_ka_id=" . $_GET['ska'] . ") ";
                }

                if ($_GLOBAL['shop']['category'] == -1) unset($_GLOBAL['shop']['category']);

                $co = array('ą', 'ę', 'ś', 'ć', 'ł', 'ń', 'ó', 'ż', 'ź');
                $na = array('%', '%', '%', '%', '%', '%', '%', '%', '%');

                if ((isset($_GET['sna'])) && ($_GET['sna'] != '')) {
                    $Q['sna'] = " (pr.pr_nazwa like '%" . str_replace($co, $na, a($_GET['sna'])) . "%' or 
                               pd_nazwa like '%" . str_replace($co, $na, a($_GET['sna'])) . "%' or 
                               pr_indeks like '" . str_replace($co, $na, a($_GET['sna'])) . "%' or 
                               kolekcja like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' or
                               tkanina like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' or
                               fason like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' or
                               faktura like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%' or
                               model like '%" . trim(str_replace($co, $na, a($this->WYNIK["sz"]["sna"]))) . "%') ";
                }

                if ($this->WYNIK["sz"]["sop"] != "") {
                    $Q[] = "(pr_opis like '%" . a($this->WYNIK["sz"]["sop"]) . "%')";
                }

                if ($this->WYNIK["sz"]["spd"] != "") {
                    $_GLOBAL['shop']['brands'][$this->WYNIK["sz"]["spd"]] = $this->WYNIK["sz"]["spd"];
                }

                if ($this->WYNIK["sz"]["sco"] != "") {
                    $Q[] = "(" . get_cena_w() . " >= '" . a($this->WYNIK["sz"]["sco"]) . "')";
                }

                if ($this->WYNIK["sz"]["scd"] != "") {
                    $Q[] = "(" . get_cena_w() . " <= '" . a($this->WYNIK["sz"]["scd"]) . "')";
                }

                if ($this->WYNIK["sz"]["ska"] != "" and test_int($this->WYNIK["sz"]["ska"])) {
                    if ((!isset($_GLOBAL['shop']['subkat'])) || (count($_GLOBAL['shop']['subkat']) == 0))
                        $Q[] = " (kp_ka_id=" . (int)$this->WYNIK["sz"]["ska"] . " or kp_ka_id=" . (int)$this->WYNIK["sz"]["ska"] . " ) ";
                }

                if ($this->WYNIK["sz"]["sro"] != "" and test_int($this->WYNIK["sz"]["sro"])) {
                    $_GLOBAL['shop']['size'][$this->WYNIK["sz"]["sro"]] = $this->WYNIK["sz"]["sro"];
                }


                if ((isset($_GLOBAL['shop']['category'])) &&
                    (!empty($_GLOBAL['shop']['category'])) &&
                    ((!isset($_GLOBAL['shop']['subkat'])) || (count($_GLOBAL['shop']['subkat']) == 0))
                ) {
                    $Q['category'] = " (ka_id=" . $_GLOBAL['shop']['category'] . " or ka_ka_id=" . $_GLOBAL['shop']['category'] . ") ";
                }

                if ((isset($this->WYNIK["sz"]["sale"])) && ($this->WYNIK["sz"]["sale"] != "")) {
                    $Q['sale'] = "pr_cena_w_brutto<=(pr_cena_b_brutto*70/100)";
                }

                if ((isset($_GET["sale2"])) && ($_GET["sale2"] != "")) {
                    $Q['sale2'] = "pr_cena_w_brutto<=(pr_cena_b_brutto*55/100)";
                }

                if ((isset($_GLOBAL['shop']['kolor'])) && (!empty($_GLOBAL['shop']['kolor'])) && (count($_GLOBAL['shop']['kolor']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['kolor'] as $k => $v) {
                        $tmp2[] = "color=" . $v . " ";
                    }
                    $Q['kolor'] = " (" . join(' or ', $tmp2) . ") ";
                }

                if ((isset($_GLOBAL['shop']['size'])) && (!empty($_GLOBAL['shop']['size'])) && (count($_GLOBAL['shop']['size']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['size'] as $k => $v) $tmp[$v] = $v;
                    $Q['size'] = ' ms_at_id in (' . join(',', $tmp) . ')';
                }

                if ((isset($_GLOBAL['shop']['faktura'])) && (!empty($_GLOBAL['shop']['faktura'])) && (count($_GLOBAL['shop']['faktura']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['faktura'] as $k => $v) $tmp[$v] = $v;
                    $Q['faktura'] = ' faktura in (' . join(',', $tmp) . ') ';
                }

                if ((isset($_GLOBAL['shop']['fason'])) && (!empty($_GLOBAL['shop']['fason'])) && (count($_GLOBAL['shop']['fason']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['fason'] as $k => $v) $tmp[$v] = $v;
                    if (count($tmp) > 0) $Q['fason'] = ' fason in (' . join(',', $tmp) . ') ';
                }

                if ((isset($_GLOBAL['shop']['price'])) && (!empty($_GLOBAL['shop']['price'])) && ($_GLOBAL['shop']['price'] != '0;0')) {
                    $tmp        = array();
                    $tmp        = explode(';', $_GLOBAL['shop']['price']);
                    $Q['price'] = ' (pr_cena_w_brutto>= ' . $tmp[0] . ' and pr_cena_w_brutto<=' . $tmp[1] . ') ';
                }

                if ((isset($_GLOBAL['shop']['subkat'])) && (!empty($_GLOBAL['shop']['subkat'])) && (count($_GLOBAL['shop']['subkat']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['subkat'] as $k => $v) $tmp[$v] = $v;
                    if (count($tmp) > 0) {
                        $Q['subkat'] = ' kp_ka_id=ka_id and (ka_id = ' . join(' or ka_id=', $tmp) . ') ';
                    }
                }

                if ((isset($_GLOBAL['shop']['brands'])) && (!empty($_GLOBAL['shop']['brands'])) && (count($_GLOBAL['shop']['brands']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['brands'] as $k => $v) if (!empty($v)) $tmp[$v] = $v;
                    if (count($tmp) > 0) $Q['brands'] = ' pd_id in (' . join(',', $tmp) . ')';
                }

                if ((isset($_GET['groups'])) && (!empty($_GET['groups'])) && (count($_GET['groups']) > 0)) {
                    $tmp = array();

                    // getting products from group
                    $query = "select product_id from " . dn('group_product') . " where group_id = " . $_GET['groups'];
                    $r     = $db->query($query);
                    while ($row = $db->fetch($r)) {
                        $tmp[$row['product_id']] = $row['product_id'];
                    }

                    if (count($tmp) > 0)
                        $whereGroups = ' and pr.pr_id in (' . join(',', $tmp) . ')';
                }

                if ((isset($_GLOBAL['shop']['brn']['subkat'])) && (!empty($_GLOBAL['shop']['brn']['subkat'])) && (count($_GLOBAL['shop']['brn']['subkat']) > 0)) {
                    $tmp = array();
                    foreach ($_GLOBAL['shop']['brn']['subkat'] as $k => $v) $tmp[$v] = $v;
                    if (count($tmp) > 0) {
                        $Q['brn_subkat'] = ' (ka_id = ' . join(' or ka_id=', $tmp) . ') ';
                    }
                }

                //------------- szukaj ----------------/
                if ($_GET['brand']) {
                    //marki
                    /*pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
                $q.= get_cena_w()." pr_cena_w_brutto, pd_nazwa,pd_id,pr_kolor,pd_plik,kp_ka_id, p1.pr_gt_id,
                faktura,fason,color,`primary`, pr_widoczny, kp_widoczna, ka_id, ka_ka_id*/
                    $q = "select pd_id, pd_nazwa
                    from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                         " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                    $q .= "where ms.ms_pr_id = p1.pr_id
                          and pr.pr_id=p1.pr_id ";
                    $q .= "and pr.pr_id=kp_pr_id ";
                    $q .= "and id=pr.pr_id ";
                    $q .= "and ka_id=kp_ka_id ";
                    $q .= "and ms_ilosc>0 ";
                    $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                    $q .= "and pr_stan = '1' ";
                    $q .= "and pr_pd_id=pd_id ";
                    $q .= "and product=p1.pr_id ";
                    $q .= "and " . $wh . " ";
                    $q .= "and kp_widoczna='1' ";
                    $q .= "and " . $bwh . " ";

                    if (count($Q) > 0) {
                        foreach ($Q as $k => $v) if ($k != 'brands') $q .= ' and ' . $v;
                    }

                    if (isset($_GET['mono']) AND $Q['brands'] != '') {
                        $q .= 'and ' . $Q['brands'];
                    }

                    //if(isset($Q['subkat'])) $q .= ' and ' . $Q['subkat'];        
                    if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                        // $q.="and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                    }

                    $q .= ' group by pr.pr_id ';
                    $q .= ' order by pd_nazwa, pr_kolor';

                    $nr = $db->query($q);
                    while ($T = $db->fetch($nr)) {
                        $FILTER['blend'][$T['pd_id']] = trim($T['pd_nazwa']);
                    }

                    $q = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
                    $q .= get_cena_w() . " pr_cena_w_brutto, pd_nazwa,pd_id,pr_kolor,pd_plik,kp_ka_id, p1.pr_gt_id,
                    faktura,fason,color,`primary`, pr_widoczny, kp_widoczna, ka_id, ka_ka_id, IF ((p1.pr_cena_a_brutto - p1.pr_cena_w_brutto)>0,1,0) AS upust
                    from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                       " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                    $q .= "where ms.ms_pr_id = p1.pr_id
                    and pr.pr_id=p1.pr_id ";
                    $q .= "and pr.pr_id=kp_pr_id ";
                    $q .= "and id=pr.pr_id ";
                    $q .= "and ka_id=kp_ka_id ";
                    $q .= "and ms_ilosc>0 ";
                    $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                    $q .= "and pr_stan = '1' ";
                    $q .= "and " . $wh . " ";
                    $q .= "and kp_widoczna='1' ";
                    $q .= "and pr_pd_id=pd_id ";
                    $q .= "and " . $bwh . " ";
                    $q .= "and product=p1.pr_id ";

                    if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                        // $q.="and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                    }

                    if (isset($Q['subkat'])) $q .= ' and ' . $Q['subkat'];
                    //if($Q['brands'] != '')$q .= "and " . $Q['brands'];

                    if (isset($_GET['mono']) AND $Q['brands'] != '') {
                        $q .= ' and ' . $Q['brands'];
                    }

                    if (isset($whereGroups) AND ($whereGroups != '')) {
                        $q .= $whereGroups;
                    }

                    $q .= ' group by pr.pr_id ';
                    $q .= ' order by upust asc, pr.pr_image_upload_timestamp desc, pd_nazwa, color';
                } elseif ($_GET['sale']) {
                    //sale
                    /*pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
              $q.= get_cena_w()." pr_cena_w_brutto, pd_nazwa,pd_id,pr_kolor,pd_plik,kp_ka_id, p1.pr_gt_id,
              faktura,fason,color,`primary`, pr_widoczny, kp_widoczna, ka_id, ka_ka_id*/
                    $q = "select pd_id, pd_nazwa
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                       " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                    $q .= "where ms.ms_pr_id = p1.pr_id
                    and pr.pr_id=p1.pr_id ";
                    $q .= "and pr.pr_id=kp_pr_id ";
                    $q .= "and id=pr.pr_id ";
                    $q .= "and ka_id=kp_ka_id ";
                    $q .= "and ms_ilosc>0 ";
                    $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                    $q .= "and pr_stan>0 ";
                    $q .= "and pr_pd_id=pd_id ";
                    $q .= "and product=p1.pr_id ";
                    $q .= "and " . $wh . " ";
                    $q .= "and kp_widoczna='1' ";
                    $q .= "and " . $bwh . " ";

                    if (count($Q) > 0) {
                        foreach ($Q as $k => $v) if ($k != 'brands') $q .= ' and ' . $v;
                    }

                    // if(isset($Q['sale'])) $q .= ' and ' . $Q['sale'];            
                    if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                        $q .= "and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                    }

                    if (isset($whereGroups) AND ($whereGroups != '')) {
                        $q .= $whereGroups;
                    }

                    $q .= ' group by pr.pr_id ';
                    $q .= ' order by pd_nazwa, pr_kolor';

                    $nr = $db->query($q);
                    while ($T = $db->fetch($nr)) {
                        $FILTER['blend'][$T['pd_id']] = trim($T['pd_nazwa']);
                    }

                    $q = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
                    $q .= get_cena_w() . " pr_cena_w_brutto, pd_nazwa,pd_id,pr_kolor,pd_plik,kp_ka_id, p1.pr_gt_id,
                    faktura,fason,color,`primary`, pr_widoczny, kp_widoczna, ka_id, ka_ka_id
                    from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                       " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                    $q .= "where ms.ms_pr_id = p1.pr_id
                    and pr.pr_id=p1.pr_id ";
                    $q .= "and pr.pr_id=kp_pr_id ";
                    $q .= "and id=pr.pr_id ";
                    $q .= "and ka_id=kp_ka_id ";
                    $q .= "and ms_ilosc>0 ";
                    $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                    $q .= "and pr_stan>0 ";
                    $q .= "and pr_pd_id=pd_id ";
                    $q .= "and " . $bwh . " ";
                    $q .= "and product=p1.pr_id ";
                    $q .= "and " . $wh . " and kp_widoczna='1' ";

                    if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                        $q .= "and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                    }

                    if (isset($Q['sale'])) $q .= ' and ' . $Q['sale'];

                    if (isset($_GET['mono']) AND $Q['brands'] != '') {
                        $q .= ' and ' . $Q['brands'];
                    }

                    // if(($sURL[0]=='sale') && ($sURL[1]=='brands')) {
                    // $q .= ' and ' . $Q['brands'];
                    // }
                    //if(count($Q['brands'])>0) $q .= ' and ' . $Q['brands'];

                    $q .= ' and pr_cena_w_brutto<=(pr_cena_b_brutto*70/100)';
                    $q .= ' group by pr.pr_id ';
                    $q .= ' order by pd_nazwa, color';
                } else {
                    $q = "select pd_id, pd_nazwa
                      from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                           " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                    $q .= "where ms.ms_pr_id = p1.pr_id
                    and pr.pr_id=p1.pr_id ";
                    $q .= "and pr.pr_id=kp_pr_id ";
                    $q .= "and id=pr.pr_id ";
                    $q .= "and ka_id=kp_ka_id ";
                    $q .= "and ms_ilosc>0 ";
                    $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                    $q .= "and pr_stan>0 ";
                    $q .= "and pr_pd_id=pd_id ";
                    $q .= "and product=p1.pr_id ";
                    $q .= "and " . $wh . " ";
                    $q .= "and kp_widoczna='1' ";
                    $q .= "and " . $bwh . " ";

                    if (count($Q) > 0) {
                        foreach ($Q as $k => $v) if ($k != 'brands') $q .= ' and ' . $v;
                    }

                    if (isset($_GET['mono']) AND $Q['brands'] != '') {
                        $q .= ' and ' . $Q['brands'];
                    }

                    if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                        $q .= "and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                    }

                    if (isset($whereGroups) AND ($whereGroups != '')) {
                        $q .= $whereGroups;
                    }

                    $q .= ' group by pr.pr_id ';
                    $q .= ' order by pd_nazwa, pr_kolor';

                    $nr = $db->query($q);
                    while ($T = $db->fetch($nr)) {
                        $FILTER['blend'][$T['pd_id']] = trim($T['pd_nazwa']);
                    }
                    $q = "select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,";
                    $q .= get_cena_w() . " pr_cena_w_brutto, pd_nazwa,pd_id,pr_kolor,pd_plik,kp_ka_id, p1.pr_gt_id,
                  faktura,fason,color,`primary`, pr_widoczny, kp_widoczna, ka_id, ka_ka_id
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . " poa,
                       " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                    $q .= "where ms.ms_pr_id = p1.pr_id
                    and pr.pr_id=p1.pr_id ";
                    $q .= "and pr.pr_id=kp_pr_id ";
                    $q .= "and poa.id=pr.pr_id ";
                    $q .= "and ka_id=kp_ka_id ";
                    $q .= "and ms_ilosc>0 ";
                    $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                    $q .= "and pr_stan>0 ";
                    $q .= "and pr_pd_id=pd_id ";
                    $q .= "and " . $bwh . " ";
                    $q .= "and product=p1.pr_id ";
                    $q .= "and " . $wh . " and kp_widoczna='1' ";

                    if (isset($_GET['sale2']))
                        if (isset($Q['sale2'])) $q .= ' and ' . $Q['sale2'] . ' ';

                    if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                        $q .= "and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                    }

                    if ((isset($_GET['sna'])) && ($_GET['sna'] != '')) $q .= " and " . $Q['sna'];

                    if (isset($_GET['mono']) AND $Q['brands'] != '') {
                        $q .= ' and ' . $Q['brands'];
                    }

                    if (isset($whereGroups) AND ($whereGroups != '')) {
                        $q .= $whereGroups;
                    }

                    $q .= ' group by pr.pr_id ';
                    $q .= ' order by pd_nazwa, color';
                }


                /**
                 * Zebranie dostępnych atrybutów
                 */
                $nr = $db->query($q);
                while ($T = $db->fetch($nr)) {
                    $FILTER_list['blend'][$T['pd_id']] = trim($T['pd_nazwa']);

                    if ($_GET['brand'] == 'y') {
                        if (in_array($T['pd_id'], $_GLOBAL['shop']['brands'])) {
                            $_GLOBAL['shop']['brn']['subkat'][$T['ka_id']]    = $T['ka_id'];
                            $_GLOBAL['shop']['brn']['subkat'][$T['ka_ka_id']] = $T['ka_ka_id'];

                            if ($FILTER_list['price']['min'] > $T['pr_cena_w_brutto']) $FILTER_list['price']['min'] = $T['pr_cena_w_brutto'];
                            if ($FILTER_list['price']['max'] < $T['pr_cena_w_brutto']) $FILTER_list['price']['max'] = $T['pr_cena_w_brutto'];

                            $q = "select ms_at_id,at_nazwa from " . dn("magazyn_stan") . ", " . dn("produkt_atrybut") . " 
                       where ms_at_id=at_id and ms_pr_id=" . $T["pr_id"] . " and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") and
                       ms_ilosc >0 and at_nazwa != 'Pusty'
                       order by at_nazwa";

                            $nrr = $db->query($q);
                            while ($item = $db->fetch($nrr)) {
                                if (!empty($item[1])) {
                                    if (is_numeric(substr($item['at_nazwa'], 0, 1))) $FILTER_list['size'][0][$item['ms_at_id']] = trim($item['at_nazwa']);
                                    else $FILTER_list['size'][1][$item['ms_at_id']] = trim($item['at_nazwa']);
                                }
                            }

                            //kolor
                            if ((!empty($T['color'])) && ($T['primary'] == 0)) $FILTER_list['kolor'][$T['color']] = $T['color'];
                            //faktura
                            if (!empty($T['faktura'])) $FILTER_list['faktura'][$T['faktura']] = $T['faktura'];
                            //fason
                            if (!empty($T['fason'])) $FILTER_list['fason'][$T['fason']] = $T['fason'];
                        }
                    } else
                        if (($_GET['sale'] == 1) && ($sURL[1] == 'brands')) {
                            //if(($sURL[0]=='sale') && ($sURL[1]=='brands'))
                            if (in_array($T['pd_id'], $_GLOBAL['shop']['brands'])) {
                                $_GLOBAL['shop']['brn']['subkat'][$T['ka_id']]    = $T['ka_id'];
                                $_GLOBAL['shop']['brn']['subkat'][$T['ka_ka_id']] = $T['ka_ka_id'];

                                if ($FILTER_list['price']['min'] > $T['pr_cena_w_brutto']) $FILTER_list['price']['min'] = $T['pr_cena_w_brutto'];
                                if ($FILTER_list['price']['max'] < $T['pr_cena_w_brutto']) $FILTER_list['price']['max'] = $T['pr_cena_w_brutto'];

                                $q = "select ms_at_id,at_nazwa from " . dn("magazyn_stan") . ", " . dn("produkt_atrybut") . " 
                         where ms_at_id=at_id and ms_pr_id=" . $T["pr_id"] . " and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") and
                         ms_ilosc >0 and at_nazwa != 'Pusty'
                         order by at_nazwa";

                                $nrr = $db->query($q);
                                while ($item = $db->fetch($nrr)) {
                                    if (!empty($item[1])) {
                                        if (is_numeric(substr($item['at_nazwa'], 0, 1))) $FILTER_list['size'][0][$item['ms_at_id']] = trim($item['at_nazwa']);
                                        else $FILTER_list['size'][1][$item['ms_at_id']] = trim($item['at_nazwa']);
                                    }
                                }

                                //kolor
                                if ((!empty($T['color'])) && ($T['primary'] == 0)) $FILTER_list['kolor'][$T['color']] = $T['color'];
                                //faktura
                                if (!empty($T['faktura'])) $FILTER_list['faktura'][$T['faktura']] = $T['faktura'];
                                //fason
                                if (!empty($T['fason'])) $FILTER_list['fason'][$T['fason']] = $T['fason'];
                            }
                        } else {
                            if ($FILTER_list['price']['min'] > $T['pr_cena_w_brutto']) $FILTER_list['price']['min'] = $T['pr_cena_w_brutto'];
                            if ($FILTER_list['price']['max'] < $T['pr_cena_w_brutto']) $FILTER_list['price']['max'] = $T['pr_cena_w_brutto'];

                            $q = "select ms_at_id,at_nazwa from " . dn("magazyn_stan") . ", " . dn("produkt_atrybut") . " 
                                    where ms_at_id=at_id and ms_pr_id=" . $T["pr_id"] . " and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") and
                                    ms_ilosc >0 and at_nazwa != 'Pusty'
                                    order by at_nazwa";

                            $nrr = $db->query($q);
                            while ($item = $db->fetch($nrr)) {
                                if (!empty($item[1])) {
                                    if (is_numeric(substr($item['at_nazwa'], 0, 1))) $FILTER_list['size'][0][$item['ms_at_id']] = trim($item['at_nazwa']);
                                    else $FILTER_list['size'][1][$item['ms_at_id']] = trim($item['at_nazwa']);
                                }
                            }

                            // $FILTER_list['blend'][$T['pd_id']] = trim($T['pd_nazwa']);
                            //kolor
                            //if((!empty($T['color'])) && ($T['primary']==0)) $FILTER_list['kolor'][$T['color']] = $T['color'];
                            if (!empty($T['color'])) $FILTER_list['kolor'][$T['color']] = $T['color'];
                            //faktura
                            if (!empty($T['faktura']) AND ($T['faktura'] > 0)) $FILTER_list['faktura'][$T['faktura']] = $T['faktura'];
                            //fason
                            if (!empty($T['fason'])) $FILTER_list['fason'][$T['fason']] = $T['fason'];
                        }
                }
                /**
                 * Koniec zbierania dostępnych atrybutów
                 */


                //$q1 = "select min(pr_cena_w_brutto) as min, max(pr_cena_w_brutto) as max
                $q1 = "select pr_cena_w_brutto, pd_id, ka_id, ka_ka_id
                    from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                         " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                $q1 .= "where ms.ms_pr_id = p1.pr_id
                      and pr.pr_id=p1.pr_id ";
                $q1 .= "and pr.pr_id=kp_pr_id ";
                $q1 .= "and id=pr.pr_id ";
                $q1 .= "and ka_id=kp_ka_id ";
                $q1 .= "and ms_ilosc>0 ";
                $q1 .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                $q1 .= "and pr_pd_id=pd_id ";
                $q1 .= "and product=p1.pr_id ";
                $q1 .= "and pr_stan>0 ";
                $q1 .= "and " . $wh . " ";
                $q1 .= "and kp_widoczna='1' ";

                if (count($Q) > 0) {
                    $q1 .= ' and ';
                    $q1 .= join(' and ', $Q);
                }

                if ((isset($_GLOBAL['shop']['category'])) && (!empty($_GLOBAL['shop']['category'])) && ((int)$_GLOBAL['shop']['category'] != 0)) {
                    $q1 .= "and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                }

                if (isset($whereGroups) AND ($whereGroups != '')) {
                    $q1 .= $whereGroups;
                }

                //$q1 .= ' group by pr.pr_id ';
                $q1 .= ' group by kp_pr_id ';
                $q1 .= ' order by pd_nazwa, pr_kolor';


                $nr = $db->query($q1);
                while ($T = $db->fetch($nr)) {
                    if ($pricemin > $T['pr_cena_w_brutto']) $pricemin = $T['pr_cena_w_brutto'];
                    if ($pricemax < $T['pr_cena_w_brutto']) $pricemax = $T['pr_cena_w_brutto'];
                }


                //kategorie        
                $q1 = "select pr_cena_w_brutto, pd_id, ka_id, ka_ka_id
                    from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                         " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                $q1 .= "where ms.ms_pr_id = p1.pr_id
                      and pr.pr_id=p1.pr_id ";
                $q1 .= "and pr.pr_id=kp_pr_id ";
                $q1 .= "and id=pr.pr_id ";
                $q1 .= "and ka_id=kp_ka_id ";
                $q1 .= "and ms_ilosc>0 ";
                $q1 .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                $q1 .= "and pr_stan>0 ";
                $q1 .= "and pr_pd_id=pd_id ";
                $q1 .= "and product=p1.pr_id ";
                $q1 .= "and " . $wh . " ";
                $q1 .= "and kp_widoczna='1' ";

                if (count($Q) > 0) {
                    foreach ($Q as $k => $v) if (($k != 'subkat') && ($k != 'brn_subkat')) $q1 .= ' and ' . $v;
                }

                if ((isset($_GLOBAL['shop']['category'])) &&
                    (!empty($_GLOBAL['shop']['category'])) &&
                    ((int)$_GLOBAL['shop']['category'] != 0) &&
                    ($_GLOBAL['shop']['category'] != 122) &&
                    ($_GLOBAL['shop']['category'] != 121) &&
                    ($_GLOBAL['shop']['category'] != 219) &&
                    ($_GLOBAL['shop']['category'] != 220) &&
                    ($_GLOBAL['shop']['category'] != 277) &&
                        ($_GLOBAL['shop']['category'] != 308)
                ) {
                    $q1 .= "and (ka_id=" . (int)$_GLOBAL['shop']['category'] . " or ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " ) ";
                }

                if (isset($whereGroups) AND ($whereGroups != '')) {
                    $q1 .= $whereGroups;
                }

                $q1 .= ' group by ka_id ';

                $nr = $db->query($q1);
                while ($T = $db->fetch($nr)) {
                    $kids[$T['ka_id']]    = $T['ka_id'];
                    $kids[$T['ka_ka_id']] = $T['ka_ka_id'];
                }
                // if((isset($_GET['brand'])) && (!isset($_GET['subkat']))) $kids = $_SESSION[SID]['shop']['subkat'];


                //rozmiary
                $q = "select pr.pr_id
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                     " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms " . $lj;
                $q .= "where ms.ms_pr_id = p1.pr_id
                      and pr.pr_id=p1.pr_id ";
                $q .= "and pr.pr_id=kp_pr_id ";
                $q .= "and id=pr.pr_id ";
                $q .= "and ka_id=kp_ka_id ";
                $q .= "and ms_ilosc>0 ";
                $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                $q .= "and pr_stan>0 ";
                $q .= "and pr_pd_id=pd_id ";
                $q .= "and product=p1.pr_id ";
                $q .= "and " . $bwh . " ";
                $q .= "and " . $wh . " ";
                $q .= "and kp_widoczna='1' ";
                if (count($Q) > 0) {
                    foreach ($Q as $k => $v) if ($k != 'size') $q .= ' and ' . $v;
                }

                if (isset($whereGroups) AND ($whereGroups != '')) {
                    $q .= $whereGroups;
                }

                $q .= ' group by pr.pr_id ';
                $q .= ' order by pd_nazwa, pr_kolor';

                /*
              select pr.pr_id,pr.pr_nazwa,pr_plik,pr_punkt,pr_cena_a_brutto,pr_etykieta,pr_cena_w_brutto pr_cena_w_brutto, pd_nazwa,pd_id,pr_kolor,pd_plik,kp_ka_id, p1.pr_gt_id, faktura,fason,color,`primary`, pr_widoczny, kp_widoczna, ka_id, ka_ka_id from mm_sklep_produkt pr,mm_produkt p1,mm_sklep_kategoria_produkt,mm_product_other_atrybut, mm_sklep_kategoria,mm_sklep_producent,mm_color_product, mm_magazyn_stan ms 
              where ms.ms_pr_id = p1.pr_id and pr.pr_id=p1.pr_id and pr.pr_id=kp_pr_id and id=pr.pr_id and ka_id=kp_ka_id and ms_ilosc>0 and ms_ma_id<>4 and 
              pr_stan>0 and pr_pd_id=pd_id and pd_szukaj='1' and product=p1.pr_id and pr_widoczny='1' and kp_widoczna='1' and pd_id in (2) group by pr.pr_id order by pd_nazwa, color

              select pr.pr_id from mm_sklep_produkt pr,mm_produkt p1,mm_sklep_kategoria_produkt,mm_product_other_atrybut, mm_sklep_kategoria,mm_sklep_producent,mm_color_product, mm_magazyn_stan ms 
              where ms.ms_pr_id = p1.pr_id and pr.pr_id=p1.pr_id and pr.pr_id=kp_pr_id and id=pr.pr_id and ka_id=kp_ka_id and ms_ilosc>0 and ms_ma_id<>4 and 
              pr_stan>0 and pr_pd_id=pd_id and pd_szukaj='1' and product=p1.pr_id and pr_widoczny='1' and kp_widoczna='1' and kp_ka_id=ka_id and (ka_id = 123 or ka_id=81 or ka_id=82 or ka_id=83 or ka_id=85 or ka_id=86 or ka_id=87 or ka_id=88 or ka_id=89 or ka_id=91 or ka_id=97 or ka_id=114 or ka_id=118 or ka_id=124 or ka_id=79 or ka_id=80 or ka_id=92 or ka_id=93 or ka_id=96 or ka_id=103 or ka_id=107 or ka_id=115 or ka_id=165 or ka_id=166 or ka_id=167 or ka_id=125 or ka_id=95 or ka_id=104 or ka_id=108 or ka_id=113 or ka_id=119 or ka_id=171 or ka_id=172 or ka_id=173 or ka_id=174) and pd_id in (2) group by pr.pr_id order by pd_nazwa, pr_kolor 
             */

                $nr = $db->query($q);
                while ($T = $db->fetch($nr)) {
                    $q = "select ms_at_id,at_nazwa from " . dn("magazyn_stan") . ", " . dn("produkt_atrybut") . " 
                   where ms_at_id=at_id and ms_pr_id=" . $T["pr_id"] . " and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") and
                   ms_ilosc >0 and at_nazwa != 'Pusty'
                   order by at_nazwa";

                    $nrr = $db->query($q);
                    while ($item = $db->fetch($nrr)) {
                        if (!empty($item[1])) {
                            if (is_numeric(substr($item['at_nazwa'], 0, 1))) $FILTER['size'][0][$item['ms_at_id']] = trim($item['at_nazwa']);
                            else $FILTER['size'][1][$item['ms_at_id']] = trim($item['at_nazwa']);
                        }
                    }
                }


                //kolor
                $q = "select color, `primary`
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                         " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms, " . dn('color') . " " . $lj;
                $q .= "where ms.ms_pr_id = p1.pr_id
                      and pr.pr_id=p1.pr_id 
                      and " . dn('color') . ".id=" . dn('color_product') . ".color ";
                $q .= "and pr.pr_id=kp_pr_id ";
                $q .= "and " . dn('product_other_atrybut') . ".id=pr.pr_id ";
                $q .= "and ka_id=kp_ka_id ";
                $q .= "and ms_ilosc>0 ";
                $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                $q .= "and pr_stan>0 ";
                $q .= "and pr_pd_id=pd_id ";
                $q .= "and product=p1.pr_id ";
                $q .= "and `primary`=0 ";
                $q .= "and " . $wh . " ";
                $q .= "and kp_widoczna='1' ";
                $q .= "and langid=" . $_GLOBAL['langid'] . " ";
                if (count($Q) > 0) {
                    foreach ($Q as $k => $v) if ($k != 'kolor') $q .= ' and ' . $v;
                }

                if (isset($whereGroups) AND ($whereGroups != '')) {
                    $q .= $whereGroups;
                }

                $q .= ' group by color ';
                $q .= ' order by ' . dn('color') . '.sort';

                $nr = $db->query($q);
                while ($T = $db->fetch($nr)) {
                    //if((!empty($T['color'])) && ($T['primary']==0)) $FILTER['kolor'][$T['color']] = $T['color'];
                    if (!empty($T['color'])) $FILTER['kolor'][$T['color']] = $T['color'];
                }


                //faktura
                $q = "select faktura
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                         " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms, " . dn('color') . " " . $lj;
                $q .= "where ms.ms_pr_id = p1.pr_id
                      and pr.pr_id=p1.pr_id 
                      and " . dn('color') . ".id=" . dn('color_product') . ".color ";
                $q .= "and pr.pr_id=kp_pr_id ";
                $q .= "and " . dn('product_other_atrybut') . ".id=pr.pr_id ";
                $q .= "and ka_id=kp_ka_id ";
                // $q.= "and ".dn('product_other_atrybut').'.faktura <> NULL ';
                $q .= "and ms_ilosc>0 ";
                $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                $q .= "and pr_pd_id=pd_id ";
                $q .= "and product=p1.pr_id ";
                $q .= "and `primary`=0 ";
                $q .= "and pr_stan>0 ";
                $q .= "and " . $wh . " ";
                $q .= "and kp_widoczna='1' ";
                if (count($Q) > 0) {
                    foreach ($Q as $k => $v) if ($k != 'faktura') $q .= ' and ' . $v;
                }

                if (isset($whereGroups) AND ($whereGroups != '')) {
                    $q .= $whereGroups;
                }

                $q .= ' group by pr.pr_id ';
                $q .= ' order by ' . dn('color') . '.sort';

                $nr = $db->query($q);
                while ($T = $db->fetch($nr)) {
                    if (!empty($T['faktura'])) $FILTER['faktura'][$T['faktura']] = $T['faktura'];
                }


                //fason
                $q = "select fason
                  from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn('product_other_atrybut') . ",
                         " . dn('sklep_kategoria') . "," . dn("sklep_producent") . "," . dn('color_product') . ", " . dn('magazyn_stan') . " ms, 
                         " . dn('color') . " " . $lj;
                $q .= "where ms.ms_pr_id = p1.pr_id
                      and pr.pr_id=p1.pr_id 
                      and " . dn('color') . ".id=" . dn('color_product') . ".color ";
                $q .= "and pr.pr_id=kp_pr_id ";
                $q .= "and " . dn('product_other_atrybut') . ".id=pr.pr_id ";
                $q .= "and ka_id=kp_ka_id ";
                $q .= "and ms_ilosc>0 ";
                $q .= "and ms_ma_id NOT IN (" . $_GLOBAL["omitted_stores"] . ") ";
                $q .= "and pr_stan>0 ";
                $q .= "and pr_pd_id=pd_id ";
                $q .= "and product=p1.pr_id ";
                $q .= "and `primary`=0 ";
                $q .= "and " . $wh . " ";
                $q .= "and kp_widoczna='1' ";
                $q .= "and langid=" . $_GLOBAL['langid'] . ' ';
                if (count($Q) > 0) {
                    foreach ($Q as $k => $v) if ($k != 'fason') $q .= ' and ' . $v;
                }

                if (isset($whereGroups) AND ($whereGroups != '')) {
                    $q .= $whereGroups;
                }

                $q .= ' group by pr.pr_id ';
                $q .= ' order by fason';

                $nr = $db->query($q);
                while ($T = $db->fetch($nr)) {
                    if (!empty($T['fason'])) $FILTER['fason'][$T['fason']] = $T['fason'];
                }

                /*
             * Koniec zmierania informacji do filtra
             */

                /*
             * Generowanie filtra na podstawie zebranych informacji
             */
                $ret = '<form action="' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/" method="get" class="niceform" id="filtered">
                      <fieldset style="overflow: auto">';

				$ret .= '<script>$(document).ready(function(){
							$(".collapsable-toggle").click(function(){
								
								$(this).parents("legend").parent("div").nextAll(".collapsable").first().toggle("slow", function(){
									if($(this).is(":visible")){
										$(this).prevAll("div.legend").first().find("legend").find(".collapsable-toggle > img").attr("src", "' . $_GLOBAL['page_url'] . 'template/3/images/arrow_up.png");
										$(this).prevAll("div.legend").first().find("legend").find(".collapsable-toggle > img").attr("title", "'. ($_GLOBAL['langid'] == 1 ? 'zwiń' : 'collapse') .'");
										$.post("http://gomez.pl/template/3/ajax_php/filter/saveCollapse.php?filter_id=" + 					  $(this).prevAll("div.legend").first().find("legend").attr("id") + "&filter_value=false");
									} else {
										$(this).prevAll("div.legend").first().find("legend").find(".collapsable-toggle > img").attr("src", "' . $_GLOBAL['page_url'] . 'template/3/images/arrow_down.png");
										$(this).prevAll("div.legend").first().find("legend").find(".collapsable-toggle > img").attr("title", "'. ($_GLOBAL['langid'] == 1 ? 'rozwiń' : 'expand') .'");
										$.post("http://gomez.pl/template/3/ajax_php/filter/saveCollapse.php?filter_id=" + 					  $(this).prevAll("div.legend").first().find("legend").attr("id") + "&filter_value=true");										
									}
								});
							});
						});</script>';

                if (isset($_GET['brand'])) {
                    $ret .= '<input type="hidden" name="brand" value="' . $_GET['brand'] . '">';
                }

                if (isset($_GET['nowosci'])) {
                    $ret .= '<input type="hidden" name="nowosci" value="' . $_GET['nowosci'] . '">';
                }

                if (isset($_GET['sale'])) {
                    $ret .= '<input type="hidden" name="sale" value="' . $_GET['sale'] . '">';
                }

                if (isset($_GET['sale2'])) {
                    $ret .= '<input type="hidden" name="sale2" value="' . $_GET['sale2'] . '">';
                }

                if ((isset($_GET['sna'])) && ($_GET['sna'] != '')) {
                    $ret .= '<input type="hidden" name="sna" value="' . $_GET['sna'] . '">';
                }

                if ((isset($_GET['a'])) && ($_GET['a'] == 's')) {
                    $ret .= '<input type="hidden" name="a" value="s">';
                }

                if ((isset($_GET['mono'])) && ($_GET['mono'] == 'y')) {
                    $ret .= '<input type="hidden" name="mono" value="y">';
                }

                if ((isset($_GET['groups']))) {
                    $ret .= '<input type="hidden" name="groups" value="' . $_GET['groups'] . '">';
                }

                //wyswietlanie filtra BRANDS
                // if(((count($sURL)>1) && ($sURL[0]=='brands')) || ((isset($_GET['brand'])) && ($_GET['brand']=='y'))) {
                if (((count($sURL) > 1) && ($sURL[0] == 'brands')) || ((isset($_GET['brand'])) && ($_GET['brand'] == 'y')) || ((isset($_GET['groups'])) && ($_GET['groups'] > 0))) {

                    if ($sURL[0] != 'wyszukiwarka') {
                        $linkb1 = 'location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/';
                        $linkb2 = 'location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/';
                        $linkb3 = 'location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/brands/';

                        if (($sURL[1] == 'men') || ($sURL[1] == 'women') || ($sURL[1] == 'kids')) {
                            $linkb1 .= 'women/';
                            $linkb1 .= $sURL[2] . '/\'';

                            $linkb2 .= 'men/';
                            $linkb2 .= $sURL[2] . '/\'';

                            $linkb3 .= 'kids/';
                            $linkb3 .= $sURL[2] . '/\'';
                        } else {
                            $linkb1 .= 'women/';
                            $linkb1 .= $sURL[1] . '/\'';

                            $linkb2 .= 'men/';
                            $linkb2 .= $sURL[1] . '/\'';

                            $linkb3 .= 'kids/';
                            $linkb3 .= $sURL[1] . '/\'';
                        }
                        //$linkb = $linkb;
                    } else {
                        $linkb1 = 'gofilter();';
                        $linkb2 = 'gofilter();';

                        $linkb1 = 'location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/';
                        $linkb2 = 'location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/';
                        $linkb3 = 'location=\'' . $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/';

                        $linkb1 .= '?plec=women' . ((isset($_GET['brand'])) ? '&brand=y' : '');
                        if (isset($_GET['brands'])) {
                            if (count($_GET['brands']) > 0) {
                                foreach ($_GET['brands'] as $k => $v) $linkb1 .= '&brands[' . $v . ']=' . $v;
                            }
                        }

                        $linkb2 .= '?plec=men' . ((isset($_GET['brand'])) ? '&brand=y' : '');
                        if (isset($_GET['brands'])) {
                            if (count($_GET['brands']) > 0) {
                                foreach ($_GET['brands'] as $k => $v) $linkb2 .= '&brands[' . $v . ']=' . $v;
                            }
                        }

                        $linkb3 .= '?plec=kids' . ((isset($_GET['brand'])) ? '&brand=y' : '');
                        if (isset($_GET['brands'])) {
                            if (count($_GET['brands']) > 0) {
                                foreach ($_GET['brands'] as $k => $v) $linkb2 .= '&brands[' . $v . ']=' . $v;
                            }
                        }
                        $linkb1 .= "'";
                        $linkb2 .= "'";
                        $linkb3 .= "'";
                    }

                    if ((isset($_GET['groups'])) && ($_GET['groups'] > 0)) {
                    } else {
                        $ret .= '<div class="legend"><legend id="plec">' . $L['{PLEC}'] . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['plec']) && $_SESSION['filter_collapse']['plec'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['plec']) && $_SESSION['filter_collapse']['plec'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['plec']) && $_SESSION['filter_collapse']['plec'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>';

						$ret .= '<div class="collapsable" style="' . (isset($_SESSION['filter_collapse']['plec']) && $_SESSION['filter_collapse']['plec'] == 1 ? 'display:none;' : '') . '">';

                        $ret .= '<div class="form-element" style="line-height: 26px;width: 114px;">
                          <input type="radio" name="plec" value="women" onclick="' . $linkb1 . '" ' . (((isset($_GET['plec'])) && ($_GET['plec'] == 'women')) ? ' checked ' : '') . '> ' . $L['{T_KOBIETA}'] . '<br>
                         </div>';
                        $ret .= '<div class="form-element" style="line-height: 26px;">
                          <input type="radio" name="plec" value="men" onclick="' . $linkb2 . '" ' . (((isset($_GET['plec'])) && ($_GET['plec'] == 'men')) ? ' checked ' : '') . '> ' . $L['{T_MEZCZYZNA}'] . '<br>
                         </div>';
                        $ret .= '<div class="form-element" style="line-height: 26px;">
                          <input type="radio" name="plec" value="kids" onclick="' . $linkb3 . '" ' . (((isset($_GET['plec'])) && ($_GET['plec'] == 'kids')) ? ' checked ' : '') . '> ' . $L['{T_DZIECKO}'] . '<br>
                         </div>';
						$ret .= '</div>';
                        $ret .= '<div class="separator"></div>';
                        $clear = '';
                    }

                    //if((isset($_SESSION[SID]['brn']['shop']['subkat'])) && (count($_SESSION[SID]['brn']['shop']['subkat'])>0))
                    if ((isset($_GET['subkat'])) && (count($_GET['subkat']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'subkat') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }

                    $ret .= '<div class="legend"><legend id="kategoria">' . $L["{T_KATEGORIA}"] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>
                       <div class="collapsable jspPane scroll-pane" style="height: 180px;position: relative;' . (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'display:none;' : '') . '">';

                    //tutaj kategorie         
                    $ids = array();
                    if (count($_GLOBAL['shop']['brands']) > 0) {
                        foreach ($_GLOBAL['shop']['brands'] as $k => $v) {
                            if (!empty($v)) $ids[] = $v;
                        }
                    }

                    $query = "select * from " . dn('sklep_kategoria') . "
                        where ka_widoczna='1'
                        and ka_ka_id in (" . ((isset($_GET['plec'])) ? (($_GET['plec'] == 'men') ? (($_GLOBAL['langid'] != 1) ? '220' : '122') : (($_GLOBAL['langid'] != 1) ? '219' : '121')) : (($_GLOBAL['langid'] != 1) ? ' 219, 220 ' : ' 121, 122 ')) . ")
                        group by ka_id order by ka_ka_id;";
                    //pr_pd_id in (" . join(',' , $ids) . ") and

                    $res = $db->query($query);
                    while ($item = $db->fetch($res)) {
                        $query = "select * from " . dn('sklep_kategoria_produkt') . "," . dn('sklep_kategoria') . "," . dn('sklep_produkt') . "
                          where kp_ka_id=ka_id
                          and pr_id=kp_pr_id
                          and ka_widoczna='1'
                          and " . $wh . " 
                          and pr_stan>0
                          " . ((count($ids) > 0) ? " and pr_pd_id in (" . join(',', $_GLOBAL['shop']['brands']) . ") " : "") . " and ka_ka_id in (" . $item['ka_id'] . ")
                          " . ((count($_GLOBAL['shop']['subkat']) > 0) ? " and (ka_id in (" . join(',', $_GLOBAL['shop']['subkat']) . ") or ka_ka_id in (" . join(',', $_GLOBAL['shop']['subkat']) . ")) " : '') . "
                          group by ka_id order by ka_pozycja;";

                        $res2 = $db->query($query);
                        if ($db->num_rows($res2) > 0) {
							$gender = '';
							if($item['ka_id'] == 123){
								$gender = ' damska';
							}
							if($item['ka_id'] == 124 || $item['ka_id'] == 125){
								$gender = ' damskie';
							}
							if($item['ka_id'] == 299){
								$gender = ' dla niej';
							}
							if($item['ka_id'] == 126){
								$gender = ' męska';
							}
							if($item['ka_id'] == 127 || $item['ka_id'] == 128){
								$gender = ' męskie';
							}
							if($item['ka_id'] == 302){
								$gender = ' dla niego';
							}

							$genderEng = '';
							if($item['ka_id'] == 221 || $item['ka_id'] == 222 || $item['ka_id'] == 223){
								$genderEng = 'Women\'s ';
							}
							if($item['ka_id'] == 224 || $item['ka_id'] == 225 || $item['ka_id'] == 226){
								$genderEng = 'Men\'s ';
							}


                            $ret .= '<div class="form-element' . ((!isset($kids[$item['ka_id']])) ? ' fedisabled' : '') . '"> 
                            <input type="checkbox" name="subkat[' . $item['ka_id'] . ']" onclick="gofilter()" value="' . $item['ka_id'] . '" ' . (((isset($_GET['subkat'][$item['ka_id']])) && ($_GET['subkat'][$item['ka_id']] == $item['ka_id'])) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt"><strong>' . $genderEng . $item['ka_nazwa'] . $gender . ' </strong></label>
                           </div>';
                            while ($item2 = $db->fetch($res2)) {
                                $ret .= '<div class="form-element' . ((!isset($kids[$item2['ka_id']])) ? ' fedisabled' : '') . '">
                              <input type="checkbox" name="subkat[' . $item2['ka_id'] . ']" onclick="gofilter()" value="' . $item2['ka_id'] . '" ' . (((isset($_GET['subkat'][$item2['ka_id']])) && ($_GET['subkat'][$item2['ka_id']] == $item2['ka_id'])) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $item2['ka_nazwa'] . '</label>
                             </div>';
                            }
                        }
                    }

                    $ret .= '</div>';
                } else {

                    $clear = '';
                    if ((isset($_GLOBAL['shop']['subkat'])) && (count($_GLOBAL['shop']['subkat']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'subkat') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }
                    $ret .= '<div class="legend"><legend id="kategoria">' . $L["{T_KATEGORIA}"] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>';

                    $ret .= '<div class="collapsable" style=" float: left;padding-bottom: 12px; ' . (isset($_SESSION['filter_collapse']['kategoria']) && $_SESSION['filter_collapse']['kategoria'] == 1 ? 'display:none;' : '') . '">';
                    $ret .= '<div class="form-element">';

                    $linkPARAM = '';
                    if (isset($_GET['sale'])) $linkPARAM = '&sale=1';
                    if (isset($_GET['sale2'])) $linkPARAM = '&sale2=1';
                    $linkURL = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/';
                    $ret .= '<select name="kategoria" id="fkategoria" size="1" onchange="fkategoriana(this.value,\'' . $linkURL . '\',\'' . $linkPARAM . '\')">';
                    $ret .= '<option value=""> ' . $L['{WYBIERZ}'] . ' </option>';

                    //dodać rozróżnienie na wersje językowe
                    if ($_GLOBAL['langid'] != 1) $query = "select * from " . dn('sklep_kategoria') . " where (ka_id=219 or ka_id=220 or ka_id=308) and ka_widoczna='1' order by ka_pozycja;";
                    else $query = "select * from " . dn('sklep_kategoria') . " where (ka_id=121 or ka_id=122 or ka_id=277) and ka_widoczna='1' order by ka_pozycja;";
                    $res = $db->query($query);
                    while ($item = $db->fetch($res)) {
                        $ret .= '<option value="' . $item['ka_id'] . '" ' . (((isset($_GLOBAL['shop']['category'])) && ($_GLOBAL['shop']['category'] == $item['ka_id'])) ? ' selected ' : '') . '>' . $item['ka_nazwa'] . '</option>';
                        $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna='1' order by ka_pozycja;";
                        $res2  = $db->query($query);

                        while ($item2 = $db->fetch($res2)) {
                            $ret .= '<option value="' . $item2['ka_id'] . '" ' . (((isset($_GLOBAL['shop']['category'])) && ($_GLOBAL['shop']['category'] == $item2['ka_id'])) ? ' selected ' : '') . ' style="padding-left: 12px">' . $item2['ka_nazwa'] . '</option>';
                            $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item2['ka_id'] . " and ka_widoczna='1' order by ka_pozycja;";
                            $res3  = $db->query($query);

                            while ($item3 = $db->fetch($res3)) {
                                $ret .= '<option value="' . $item3['ka_id'] . '" ' . (((isset($_GLOBAL['shop']['category'])) && ($_GLOBAL['shop']['category'] == $item3['ka_id'])) ? ' selected ' : '') . ' style="padding-left: 24px;">' . $item3['ka_nazwa'] . '</option>';
                            };
                        };
                    };

                    $ret .= '</select></div>';
                    if ((int)$_GLOBAL['shop']['category'] == 0) {
                        $query = "select * from " . dn('sklep_kategoria') . " 
                          where ka_ka_id=" . (((int)$_GLOBAL['langid'] != 1) ? '2' : '1') . " and 
                                ka_widoczna='1' and `key` like '" . (($_GLOBAL['langid'] != 1) ? '0002' : '0001') . "%'
                          order by ka_pozycja;";
                    } else {
                        $query = "select * from " . dn('sklep_kategoria') . " 
                          where ka_ka_id=" . (int)$_GLOBAL['shop']['category'] . " and 
                                ka_widoczna='1' and `key` like '" . (($_GLOBAL['langid'] != 1) ? '0002' : '0001') . "%'                                
                          order by ka_pozycja;";
                    }

                    $res = $db->query($query);
                    if ($db->num_rows($res) > 0) {
                        $ret .= '<div style="float: none; clear:both;">';
                        while ($item = $db->fetch($res)) {
                            if (strlen($item['key']) == 8) {
                                $ret .= '<div class="form-element' . ((!isset($kids[$item['ka_id']])) ? ' fedisabled' : '') . '"> <!-- id="subkat" -->
                                <input type="checkbox" name="kategoria" ' . ((!isset($kids[$item['ka_id']])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $item['ka_id'] . '" ' . (((isset($_GET['subkat'][$item['ka_id']])) && ($_GET['subkat'][$item['ka_id']] == $item['ka_id'])) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $item['ka_nazwa'] . '</label>
                             </div>';
                            } else {
                                $ret .= '<div class="form-element' . ((!isset($kids[$item['ka_id']])) ? ' fedisabled' : '') . '"> <!-- id="subkat" -->
                                <input type="checkbox" name="subkat[' . $item['ka_id'] . ']" ' . ((!isset($kids[$item['ka_id']])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $item['ka_id'] . '" ' . (((isset($_GET['subkat'][$item['ka_id']])) && ($_GET['subkat'][$item['ka_id']] == $item['ka_id'])) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $item['ka_nazwa'] . '</label>
                             </div>';
                            }

                            if (isset($_GET['subkat'][$item['ka_id']])) {
                                $query = "select * from " . dn('sklep_kategoria') . " where ka_ka_id=" . $item['ka_id'] . " and ka_widoczna='1' order by ka_pozycja;";
                                $res2  = $db->query($query);
                                if ($db->num_rows($res2) > 0) {
                                    while ($item2 = $db->fetch($res2)) {
                                    }
                                }
                            }
                        }
                        $ret .= '</div>';
                    }
                    $ret .= '</div>';
                }
                $ret .= '<div class="separator"></div>';

                if (count($FILTER_list['blend']) > 0) {
                    $clear = '';
                    if ((isset($_GLOBAL['shop']['brands'])) && (count($_GLOBAL['shop']['brands']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'brands') continue;
                                    if ($key == 'brand') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }

                    asort($FILTER_list['blend']);
                    $ret .= '<div class="legend"><legend id="marka">' . $L['{T_MARKA}'] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['marka']) && $_SESSION['filter_collapse']['marka'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['marka']) && $_SESSION['filter_collapse']['marka'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['marka']) && $_SESSION['filter_collapse']['marka'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>
                       <div class="collapsable jspPane scroll-pane" style="height: 180px;position: relative;' . (isset($_SESSION['filter_collapse']['marka']) && $_SESSION['filter_collapse']['marka'] == 1 ? 'display:none;' : '') . '">';
                    foreach ($FILTER_list['blend'] as $k => $v) {
                        $tmp = str_replace(array("\\'"), array("'"), $v);
                        if (!empty($tmp)) {
                            $ret .= '<div class="form-element' . ((!in_array($v, (array)$FILTER['blend'])) ? ' fedisabled' : '') . '"> <!-- id="brands" -->
                              <input type="checkbox" name="brands[' . $k . ']" class="brands" ' . ((!in_array($v, (array)$FILTER['blend'])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $k . '" ' . (((isset($_GET['brands'][$k])) && ($_GET['brands'][$k] == $k)) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $tmp . '</label>
                           </div>';
                        }
                    }
                    $ret .= '</div>';
                    $ret .= '<div class="separator"></div>';
                }


                if ((count($FILTER_list['size'][0]) > 0) || (count($FILTER_list['size'][1]) > 0)) {
                    $clear = '';
                    if ((isset($_GLOBAL['shop']['size'])) && (count($_GLOBAL['shop']['size']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'size') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }

                    if ((count($FILTER_list['size'][0]) > 0)) asort($FILTER_list['size'][0], SORT_REGULAR);
                    if ((count($FILTER_list['size'][1]) > 0)) asort($FILTER_list['size'][1], SORT_REGULAR);
                    $ret .= '<div class="legend"><legend id="rozmiar" style="position:relative">' . $L['{T_ROZMIAR}'] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['rozmiar']) && $_SESSION['filter_collapse']['rozmiar'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['rozmiar']) && $_SESSION['filter_collapse']['rozmiar'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['rozmiar']) && $_SESSION['filter_collapse']['rozmiar'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>
                       <div class="collapsable jspPane scroll-pane" style="height: 180px;position: relative;' . (isset($_SESSION['filter_collapse']['rozmiar']) && $_SESSION['filter_collapse']['rozmiar'] == 1 ? 'display:none;' : '') . '">';

                    $ROZ = array("OS", "XXS", "XS", "S", "S/M", "M", "M/L", "L", "XL", "XXL", "XXXL", "XXXXL");
                    foreach ($ROZ as $key => $value) {
                        if (in_array($value, (array)$FILTER_list['size'][1])) {
                            $klucz = '';
                            $klucz = array_search($value, (array)$FILTER_list['size'][1]);
                            $ret .= '<div class="form-element' . ((!in_array($value, (array)$FILTER['size'][1])) ? ' fedisabled' : '') . '"><!-- id="size" -->
                            <input type="checkbox" name="size[' . $klucz . ']" ' . ((!in_array($value, (array)$FILTER['size'][1])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $klucz . '" ' . (((isset($_GET['size'][$klucz])) && ($_GET['size'][$klucz] == $klucz)) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $value . '</label>
                           </div>';
                        }
                    }

                    if (count($FILTER_list['size'][0]) > 0)
                        foreach ($FILTER_list['size'][0] as $k => $v) {
                            $ret .= '<div class="form-element' . ((!in_array($v, (array)$FILTER['size'][0])) ? ' fedisabled' : '') . '"><!-- id="size" -->
                          <input type="checkbox" name="size[' . $k . ']" ' . ((!in_array($v, (array)$FILTER['size'][0])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $k . '" ' . (((isset($_GET['size'][$k])) && ($_GET['size'][$k] == $k)) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $v . '</label>
                         </div>';
                        }
                    $ret .= '</div>';
                    $ret .= '<div class="separator"></div>';
                }


                if (count($FILTER_list['kolor']) > 0) {
                    $clear = '';
                    if ((isset($_GLOBAL['shop']['kolor'])) && (count($_GLOBAL['shop']['kolor']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'kolor') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }


                    //asort($FILTER['kolor']);
                    $ret .= '<div class="legend"><legend id="kolor">' . $L["{T_KOLOR}"] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['kolor']) && $_SESSION['filter_collapse']['kolor'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['kolor']) && $_SESSION['filter_collapse']['kolor'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['kolor']) && $_SESSION['filter_collapse']['kolor'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>
                        <div class="collapsable jspPane scroll-pane" style="height: 180px;position: relative;' . (isset($_SESSION['filter_collapse']['kolor']) && $_SESSION['filter_collapse']['kolor'] == 1 ? 'display:none;' : '') . '">';
                    foreach ($FILTER_list['kolor'] as $k => $v) {
                        if ((empty($k)) || (empty($v))) continue;
                        $tmpname = '';
                        if (is_numeric($v)) {
                            $query   = "select * from " . dn('color') . " where id=" . (int)$k . " and langid=" . $_GLOBAL['langid'] . ";";
                            $res     = $db->query($query);
                            $item    = $db->fetch($res);
                            $tmpname = $item['name'];
                        } else
                            $tmpname = $v;

                        $ret .= '<div class="form-element' . ((!in_array($v, (array)$FILTER['kolor'])) ? ' fedisabled' : '') . '"><!-- id="kolor" -->
                                <input type="checkbox" name="kolor[' . $k . ']" ' . ((!in_array($v, (array)$FILTER['kolor'])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $k . '" ' . (((isset($_GLOBAL['shop']['kolor'][$k])) && ($_GLOBAL['shop']['kolor'][$k] == $k)) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $tmpname . '</label>
                             </div>';
                    }
                    $ret .= '</div>';
                    $ret .= '<div class="separator"></div>';
                }

                if (count($FILTER_list['faktura']) > 0) {
                    $clear = '';
                    if ((isset($_GLOBAL['shop']['faktura'])) && (count($_GLOBAL['shop']['faktura']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'faktura') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }
                    asort($FILTER_list['faktura']);

                    debug($FILTER_list['faktura']);
                    $ret .= '<div class="legend"><legend id="faktura">' . $L['{T_FAKTURA}'] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['faktura']) && $_SESSION['filter_collapse']['faktura'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['faktura']) && $_SESSION['filter_collapse']['faktura'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['faktura']) && $_SESSION['filter_collapse']['faktura'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>
                        <div class="collapsable" style="position: relative; float: left;padding-bottom: 12px;' . (isset($_SESSION['filter_collapse']['faktura']) && $_SESSION['filter_collapse']['faktura'] == 1 ? 'display:none;' : '') . '">';
                    //height: 180px; class="jspPane scroll-pane"
                    foreach ($FILTER_list['faktura'] as $k => $v) {
						if($k > 0){
							$query   = "select * from " . dn('faktura') . " where id=" . (int)$k . " and langid=" . $_GLOBAL['langid'] . ";";
							$res     = $db->query($query);
							$item    = $db->fetch($res);
							$tmpname = $item['name'];

							$ret .= '<div class="test form-element' . ((!isset($FILTER['faktura'][$v])) ? ' fedisabled' : '') . '"><!-- id="faktura" -->
									<input type="checkbox" name="faktura[' . $k . ']" ' . ((!isset($FILTER['faktura'][$v])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $k . '" ' . (((isset($_GET['faktura'][$k])) && ($_GET['faktura'][$k] == $k)) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $tmpname . '</label>
								 </div>';
						}
                    }
                    $ret .= '</div>';
                    $ret .= '<div class="separator"></div>';
                }

                if (count($FILTER_list['fason']) > 0) {
                    $clear = '';
                    if ((isset($_GLOBAL['shop']['fason'])) && (count($_GLOBAL['shop']['fason']) > 0)) {
                        $tmp = array();
                        if ($sURL[0] == 'wyszukiwarka') {
                            if (count($_GET) > 0) {
                                foreach ($_GET as $key => $value) {
                                    if ($key == 'fason') continue;
                                    if (is_array($value)) {
                                        if (count($value) > 0)
                                            foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                    } else $tmp[] = $key . '=' . $value;
                                }
                            }
                            $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                            $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                        }
                    }
                    asort($FILTER_list['fason']);
                    $ret .= '<div class="legend"><legend id="fason">' . $L['{T_FASON}'] . ' ' . $clear . '<span style="float:right" class="collapsable-toggle">
								<img title="'.($_GLOBAL['langid'] == 1 ? (isset($_SESSION['filter_collapse']['fason']) && $_SESSION['filter_collapse']['fason'] == 1 ? 'rozwiń' : 'zwiń') : (isset($_SESSION['filter_collapse']['fason']) && $_SESSION['filter_collapse']['fason'] == 1 ? 'expand' : 'collapse')).'"
									style="cursor: pointer;" 
									src="' . $_GLOBAL['page_url'] . 'template/3/images/arrow_' . (isset($_SESSION['filter_collapse']['fason']) && $_SESSION['filter_collapse']['fason'] == 1 ? 'down' : 'up') .'.png">
							</span></legend></div>
                        <div class="collapsable" style="position: relative;  float: left;padding-bottom: 12px;' . (isset($_SESSION['filter_collapse']['fason']) && $_SESSION['filter_collapse']['fason'] == 1 ? 'display:none;' : '') . '">';
                    // class="jspPane scroll-pane"height: 180px;
                    foreach ($FILTER_list['fason'] as $k => $v) {
						if($k > 0){
							$query = "select * from " . dn('fason') . " where id=" . (int)$k . " and langid=" . (int)$_GLOBAL['langid'] . ";";
							$res   = $db->query($query);
							if ($db->num_rows($res) > 0) {
								$item    = $db->fetch($res);
								$tmpname = $item['name'];
								$ret .= '<div class="form-element' . ((!isset($FILTER['fason'][$v])) ? ' fedisabled' : '') . '"><!-- id="fason" -->
									<input type="checkbox" name="fason[' . $k . ']" ' . ((!isset($FILTER['fason'][$v])) ? ' disabled="true" ' : ' onclick="gofilter()"') . ' value="' . $k . '" ' . (((isset($_GET['fason'][$k])) && ($_GET['fason'][$k] == $k)) ? ' checked ' : '') . ' /><label for="colorBlue" class="opt">' . $tmpname . '</label>
								 </div>';
							}
						}
                    }
                    $ret .= '</div>';
                    $ret .= '<div class="separator"></div>';
                }


                $ret .= '</fieldset>
                ';
                $scale    = array();
                $priceval = (((isset($_GLOBAL['shop']['price'])) && (!empty($_GLOBAL['shop']['price']))) ? $_GLOBAL['shop']['price'] : floor($pricemin) . ';' . ceil($pricemax));


                $clear = '';
                if ((isset($_GLOBAL['shop']['price'])) && (!empty($_GLOBAL['shop']['price']))) {
                    $tmp = array();
                    if ($sURL[0] == 'wyszukiwarka') {
                        if (count($_GET) > 0) {
                            foreach ($_GET as $key => $value) {
                                if ($key == 'price') continue;
                                if (is_array($value)) {
                                    if (count($value) > 0)
                                        foreach ($value as $key2 => $value2) $tmp[] = $key . '[]=' . $value2;
                                } else $tmp[] = $key . '=' . $value;
                            }
                        }
                        $curl  = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . '/wyszukiwarka/?' . join('&', $tmp) . '';
                        $clear = '<a href="' . $curl . '" class="clear">[ ' . $L["{T_WYCZYSC}"] . ' ]</a>';
                    }
                }

                $ret .= '
                <legend>' . $L["{T_CENA}"] . ' ' . $clear . '</legend>
                <input type="hidden" name="price" id="price" value="' . ((((isset($_GLOBAL['shop']['price']))) && (!empty($_GLOBAL['shop']['price']))) ? $_GLOBAL['shop']['price'] : '') . '">
                <div class="layout-slider" style="width: 100%;padding-top:22px">
                    <span style="display: inline-block; width: 160px; padding: 0 5px;">
                    <input id="price-range" type="slider" name="sprice" value="' . $priceval . '" />
                    </span>
                </div>
                </form>
                <script type="text/javascript" charset="utf-8">
				jQuery(document).ready(function(){
					jQuery("#price-range").slider({ from: ' . floor($pricemin) . ', to: ' . ceil($pricemax) . ', step: 1, limits: false, smooth: true, round: 1,  format: { format: \'##\', locale: \'pl\' }, dimension: " zł", skin: "round" });
					//jQuery("#price-range").slider({ from: ' . floor($FILTER_list['price']['min']) . ', to: ' . ceil($FILTER_list['price']['max']) . ', step: 1, limits: false, smooth: true, round: 1,  format: { format: \'##\', locale: \'pl\' }, dimension: " zł", skin: "round" });
				});
                </script>';
                //}

                break;

            case "SALEMENU":

                $url = str_replace('/gomez/', '', $_SERVER['REQUEST_URI']);
                $url = str_replace('.htm', '', $url);
                $url = explode('/', $url);
                for ($i = 0; $i < count($url); $i++) $url[$i] = $url[$i + 1];
                for ($i = 0; $i <= count($url); $i++) if (empty($url[$i])) unset($url[$i]);

                if (count($url) == 2) {
                    $ret = '<legend>Sale</legend>
                    <div class="form-element" style="width: 200px;"><label for="colorBlue" class="opt"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/women/">Women</a></label></div>
                    <div class="form-element" style="width: 200px;"><label for="colorBlue" class="opt"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/men/">Men</a></label></div>
                    <div class="form-element" style="width: 200px;"><label for="colorBlue" class="opt"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/brands/">Brands</a></label></div>
                    <div class="form-element" style="width: 200px;"><label for="colorBlue" class="opt"><a href="' . $_GLOBAL['page_url'] . '' . $_GLOBAL['lang'] . '/sale/70/">' . $L['{T_OD_70'] . '</a></label></div>'; // - 46 % przeceny lub więcej
                } else {
                    $ret = '<legend>Sale</legend>';
                    //$id = $url[1];
                    if ($url[2] == 'women') $id = (($_GLOBAL['langid'] != 1) ? 219 : 121);
                    else
                        if ($url[2] == 'men') $id = (($_GLOBAL['langid'] != 1) ? 220 : 122);
                        else $id = $url[2];

                    $this->WYNIK["boks_rozwin"]   = array();
                    $this->WYNIK["boks_rozwin"][] = $id;
                    $top                          = $id;
                    while ($id != $this->top_st_id) {
                        if ($this->WYNIK["strona"][$id]["id_id"] == $this->top_st_id) $top = $id;
                        $id                           = $this->WYNIK["strona"][$id]["id_id"];
                        $this->WYNIK["boks_rozwin"][] = $id;
                    }

                    $ret .= '<div class="menuwew">' . $this->get_menu_sale($top, "menu_sklep") . '</div>';
                }
                break;

            case "SHOPNOWOSCI":
            case "SHOPTOPSELL":
            case "SHOPPROMOCJE":
            case "SHOPPOLECAMY":
            case "SHOPOSTATNIE":
                $P = explode(",", $id); // array(ile,img,manual)

                $q = "select pr.pr_id, pr.pr_nazwa, pr_cena_w_brutto pr_cena,pr_plik, pd_nazwa, kp_ka_id 
              from " . dn("sklep_produkt") . " pr," . dn("produkt") . " p1," . dn("sklep_kategoria_produkt") . "," . dn("sklep_producent") . " ";
                $q .= "where pr.pr_id=p1.pr_id ";
                $q .= "and pr.pr_id=kp_pr_id 
              and pr.pr_pd_id=pd_id 
              and ";
                $q .= "pr_stan>0 and " . $wh . " and kp_widoczna='1' ";


                $app = "";
                switch ($typ) {
                    case "SHOPNOWOSCI":
                        if ($P[2]) {
                            $q .= "and pr_nowosc='1' ";
                        }
                        $q .= "group by kp_pr_id order by pr.pr_id desc ";
                        $link = $_GLOBAL['page_url'] . $_GLOBAL['lang'] . "/sklep/nowosci/";
                        break;
                    case "SHOPTOPSELL":
                        if ($P[2]) {
                            $q .= "and pr_topsell='1' ";
                            $q .= "group by kp_pr_id order by pr.pr_id desc ";
                        } else {
                            $q .= "group by kp_pr_id order by pr_sprzedano desc, pr.pr_id desc ";
                        }
                        $link = "sklep,topsell.htm";
                        $app  = "_topsell";
                        break;
                    case "SHOPPOLECAMY":
                        $q .= "and pr_polecany='1' ";
                        $q .= "group by kp_pr_id order by pr.pr_id desc ";
                        $link = "sklep,polecamy.htm";
                        break;
                    case "SHOPPROMOCJE":
                        $q .= "and pr_promocja='1' ";
                        $q .= "group by kp_pr_id order by pr.pr_id desc ";
                        $link = "sklep,promocje.htm";
                        break;
                    case "SHOPOSTATNIE":
                        if (!isset($_SESSION[SID]["shopogladane"])) return '{T_OSTATNIE_BRAK}';
                        $IDS = array_reverse($_SESSION[SID]["shopogladane"]);
                        $q .= "and pr.pr_id in (" . join(", ", $IDS) . ") ";
                        $q .= "group by kp_pr_id ";
                        break;
                }
                $q .= "limit " . (test_int($P[0]) ? $P[0] : 5);
                $nr = $db->query($q);
                while ($T = $db->fetch($nr)) $DANE[] = $T;

                $ret = $this->get_boks_shop($DANE, $P[1], $link, $app);

                break;
            case "SHOPSZUKAJ":
                global $Cspeed;
                $MENU = explode("|^|", $Cspeed->get("menu_sklep_1"));
                foreach ($MENU as $val) {
                    $T                = explode("|", $val); //st_st_id, st_id, url, target, tytul
                    $ST[$T[0]][$T[1]] = $T[4];
                }

//kategorie        
                $TT["{KATEGORIA}"] = '';
                $query             = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$this->WYNIK['fo']['ka_id'] . ";";
                $res               = $db->query($query);
                while ($item = $db->fetch($res)) {
                    $TT["{KATEGORIA}"] .= '<option value="' . $item['ka_id'] . '">' . $item['ka_nazwa'] . '</option>' . "\n";
                    $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . ";";
                    $res2  = $db->query($query);
                    while ($item2 = $db->fetch($res2)) {
                        $TT["{KATEGORIA}"] .= '<option value="' . $item2['ka_id'] . '" style="padding-left: 12px;">' . $item2['ka_nazwa'] . '</option>' . "\n";
                    }
                }

//producenci
                $katids = array();
                $query  = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$this->WYNIK['fo']['ka_id'] . ";";
                $res    = $db->query($query);
                while ($item = $db->fetch($res)) {
                    $katids[] = $item['ka_id'];
                    $query    = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . ";";
                    $res2     = $db->query($query);
                    while ($item2 = $db->fetch($res2)) {
                        $katids[] = $item2['ka_id'];

                        $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . ";";
                        $res3  = $db->query($query);
                        while ($item3 = $db->fetch($res3)) {
                            $katids[] = $item3['ka_id'];
                        }
                    }
                }

//        $query = "select pd_id, pd_nazwa from " . dn('sklep_produkt_v') . "
                $query = "select pd_id, pd_nazwa 
                  from " . dn('sklep_produkt') . ", " . dn('sklep_producent') . ", " . dn('sklep_kategoria_produkt') . "
                 where pr_pd_id=pd_id
                 and kp_pr_id=pr_id
                 and kp_ka_id in (" . join(', ', $katids) . ") 
                 and pd_nazwa != ''
                 and " . $bwh . " 
                 group by pd_id
                 order by pd_nazwa;";

                $res = $db->query($query);
                while ($item = $db->fetch($res)) {
                    $TT["{PRODUCENT}"] .= '<option value="' . $item['pd_id'] . '"' . ($this->WYNIK["sz"]["spd"] == $item[0] ? ' selected' : '') . '>' . hs($item['pd_nazwa']) . '</option>' . chr(10);
                }

                $TT["{SNA}"]    = hs($this->WYNIK["sz"]["sna"]);
                $TT['{CKATID}'] = (int)$this->WYNIK['fo']['ka_id'];

                //rozmiary
                //dorobić
                $TT['{ROZMIAR}'] = $this->get_sizer_search($this->WYNIK['fo']['ka_id']);
                $TT['{URL}']     = $_GLOBAL['page_url'];
                $TT['{LANG}']    = $_GLOBAL['lang'];
                $tpl             = get_template("box_shop_search", $TT);

                return $tpl;
                break;
            default:
                debug("Brak obsługi boksu: " . $typ);
        }

        return $ret;
    }

    /**
     * Funkcja Wyszukuje rozmiary produktów
     * @param integer $kat
     * @return string Lista rozmiarów w postaci option dla select
     */
    function get_sizer_search($kat) {
        global $db, $Cuzytkownik;

        $userID         = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
        $userWithOrders = $this->checkUserFinalizedOrders($userID);
        $wh             = ($userID > 0) ? (($userWithOrders) ? "(pr_widoczny='1' OR pr_widoczny='2' OR pr_widoczny='3')" : "(pr_widoczny='1' OR pr_widoczny='2')") : "pr_widoczny='1'";
        $bwh            = ($userID > 0) ? (($userWithOrders) ? "(pd_szukaj='1' OR pd_szukaj='2' OR pd_szukaj='3')" : "(pd_szukaj='1' OR pd_szukaj='2')") : "pd_szukaj='1'";

        $KIDS = array();
        $AIDS = array();

        $query = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$kat . ";";
        $res   = $db->query($query);
        while ($item = $db->fetch($res)) {
            $KIDS[] = $item['ka_id'];
            $query  = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item['ka_id'] . ";";
            $res2   = $db->query($query);
            while ($item2 = $db->fetch($res2)) {
                $KIDS[] = $item2['ka_id'];
                $query  = "select * from " . dn('sklep_kategoria') . " where ka_widoczna>0 and ka_ka_id=" . (int)$item2['ka_id'] . ";";
                $res3   = $db->query($query);
                while ($item3 = $db->fetch($res3)) {
                    $KIDS[] = $item3['ka_id'];
                }
            }
        }

        $query = "select pr_id from " . dn('sklep_produkt') . ", " . dn('sklep_kategoria_produkt') . " 
                where " . $wh . " and 
                      kp_pr_id=pr_id and
                      kp_ka_id in (" . join(',', $KIDS) . ");";
        $res   = $db->query($query);
        while ($item = $db->fetch($res)) {
            $query = "select * from " . dn('produkt_atrybuty') . ", " . dn('produkt_atrybut') . "
                    where pa_at_id=at_id and pa_pr_id=" . $item['pr_id'] . "
                    group by at_id
                    order by at_nazwa
                    ;";
            $res2  = $db->query($query);
            while ($item2 = $db->fetch($res2)) {
                if (is_numeric(substr($item2['at_nazwa'], 0, 1))) $AIDS[0][$item2['at_id']] = '<option value="' . $item2['at_id'] . '">' . trim($item2['at_nazwa']) . '</option>';
                else $AIDS[1][$item2['at_id']] = trim($item2['at_nazwa']);
            }
        }


        if ((count($AIDS[0]) > 0)) asort($AIDS[0], SORT_REGULAR);

        $out = '';

        $ROZ = array("XXS", "XS", "S", "M", "L", "XL", "XXL", "XXXL", "XXXXL");
        foreach ($ROZ as $key => $value) {
            if (in_array($value, $AIDS[1])) {
                $klucz = '';
                $klucz = array_search($value, $AIDS[1]);
                $out .= '<option value="' . $klucz . '">' . $value . '</option>' . "\n";
            }
        }

        $out .= join('', $AIDS[0]);

        return $out;
    }


    function error($msg) {
        global $_GLOBAL;
        if ($_GLOBAL["debug"]) die($msg);
        else redirect("index.php");
    }
}


?>