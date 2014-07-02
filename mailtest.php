<?php 

/**
 * Skrypt odpowiedzalny za organizację wyświetlania strony
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-12 12:36:48
 * @link www.imt-host.pl
 */

/**
 * Autoryzacja
 */
define('SMDESIGN', true);

/**
 * Załączenie bibliotek
 */

/**
 * Początek przetwarzania
 */
 
require_once("incphp/mb_pliki.php");
require_once("incphp/mb_common_begin.php");
require_once("common/incphp/mb_func.php");

include_once("incphp/mb_pliki.php");
include_once("incphp/mb_common_begin.php");
include_once("common/incphp/mb_func.php");

function zamowienie_mail2($id,$email="",$pre="") {
    global $_GLOBAL,$db;
          
    //$q  = "select za.*, ko_email,pl_nazwa, do_nazwa ";
    $q  = "select za.*, pl_nazwa, do_nazwa ";
    //$q .= "from ".dn("sklep_zamowienie")." za, ".dn("kontrahent").", ".dn("sklep_platnosc").",".dn("sklep_dostawa")." ";
    $q .= "from ".dn("sklep_zamowienie")." za, ".dn("sklep_platnosc").",".dn("sklep_dostawa")." ";
    //$q .= "where ko_id=za_ko_id and za_do_id=do_id and za_pl_id=pl_id and za_id=".$id;
    $q .= "where za_do_id=do_id and za_pl_id=pl_id and za_id=".$id;
    $T = $db->onerow($q);

    //za_ko_id
    if($T['za_ko_id']==0) {
        $query = "select * from " . dn('sklep_zamowienie_dane') . " where za_id=" . (int)$id . ";";
        $res = $db->query($query);
        $dane = $db->fetch($res);
        $T['ko_email'] = $dane['email'];
    } else {
        $query = "select * from " . dn('kontrahent') . " where ko_id=" . $T['za_ko_id'] . ";";
        $dane = $db->onerow($query);
        $T['ko_email'] = $dane['ko_email'];
    }
    
    // wygenerowanie listu przewozowego
    if ((($T['za_do_id'] == 3) || ($T['za_do_id'] == 4) || ($T['za_do_id'] == 6)) AND ($T['za_ko_id'] != 2391)) {
        zamowienie_listprzewozowy($id);
    } elseif ($T['za_do_id'] == 1) {
        zamowienie_odbiorwlasny($id);
    }
    // ---
    
    if($email!="")$T["ko_email"] = $email;
    $TP = get_komunikat(array("T_EMAIL","T_EMAIL_ORDER"));

    $B["{URL}"] = $_GLOBAL["page_url"]. $_GLOBAL['lang'] ."/zamowienie/";
    $B["{NUMER}"] = "ZM/".date("y",$T["za_data_in"])."/".date("m",$T["za_data_in"])."/".long_id($id,6);
    $B["{DATA}"] = date("Y-m-d, H:i",$T["za_data_in"]);
    $B["{NAZWA}"] = s($T["za_ko_nazwa"]);
    if($T["za_ko_nip"]!="") $B["{NIP}"] = s($T["za_ko_nip"]);
    else $DB[] = "IT_NIP";
    $B["{ADRES}"] = s($T["za_ko_kod"]." ".$T["za_ko_miasto"].", ".$T["za_ko_ulica"]." ".$T["za_ko_ulica_dom"]." ".$T["za_ko_ulica_lok"]);
    $B["{DO_NAZWA}"] = s($T["za_do_nazwa"]);
    $B["{DO_ADRES}"] = s($T["za_do_kod"]." ".$T["za_do_miasto"]." (".$T["za_do_kraj"]."), ".$T["za_do_adres"]);
    $LST = get_lista("order_status",0,$pre);
    $B["{STATUS}"] = $LST[$T["za_status"]];
    $B["{DOSTAWA}"] = s($T["do_nazwa"]);
    $B["{WARTOSC_DO}"] = number_format($T["za_do_koszt"],2,".","");
    $B["{PLATNOSC}"] = s($T["pl_nazwa"]);
    $B["{SUMA}"] = number_format(($T["za_wartosc_brutto"]+$T["za_do_koszt"]),2,".","");

    $item = "";
    $TP["T_EMAIL_ORDER"] = get_tag($TP["T_EMAIL_ORDER"],"IT_ITEM",$item);

    $M["m_to"] = $T["ko_email"];

    // convert product into meta products
    $query = "select * from ".dn("sklep_zamowienie_pozycja")." where zp_za_id=".$id." order by zp_id";  
    $szp = $db->get_all($query);
    $i=1;

    $pTMP = array();
    foreach ($szp as $p) {
        $pattern_prefix = substr($p['zp_pr_indeks'], 0, -5);
        $pattern_sufix = substr($p['zp_pr_indeks'], -3);
        $index = $pattern_prefix.'-0'.$pattern_sufix.'-'.$p['zp_at_id'];
        $mpindex = $pattern_prefix.'-0'.$pattern_sufix;

        if (array_key_exists($index, $pTMP)){
            $pTMP[$index]['zp_ilosc'] += $p['zp_ilosc'];
        } else {            
            $query = 'SELECT p.*, sp.pr_nazwa AS name FROM '.dn("produkt").' p LEFT JOIN '.dn('sklep_produkt').' sp ON sp.pr_id = p.pr_id WHERE pr_indeks = \''.$mpindex.'\'';
            $mp = $db->onerow($query);

            $pTMP[$index]['zp_id'] = $p['zp_id'];
            $pTMP[$index]['zp_cena_brutto'] = $p['zp_cena_brutto'];
            $pTMP[$index]['zp_ilosc'] = $p['zp_ilosc'];
            // $pTMP[$index]['zp_pr_nazwa'] = $mp['name'];
            $pTMP[$index]['zp_pr_nazwa'] = ($mp['name'] != '') ? $mp['name'] : ucfirst($mp['pr_nazwa']);
            $pTMP[$index]['zp_pr_indeks'] = $mpindex;
            $pTMP[$index]['zp_at_nazwa'] = $p['zp_at_nazwa'];
        }        
    }
    // ---

    
    foreach ($pTMP as $T) {
        $C = array();
        $C["{LP}"] = $i;
        $C["{MOD}"] = $i++%2;
        $C["{ILE}"] = $T["zp_ilosc"];
        $C["{ATRYBUT}"] = ($T["zp_at_nazwa"]);
        $C["{CENA}"] = number_format($T["zp_cena_brutto"],2,".","");
        $C["{WARTOSC}"] = number_format($T["zp_cena_brutto"]*$T["zp_ilosc"],2,".","");
        $C["{NAZWA}"] = hs($T["zp_pr_nazwa"]);
        $C["{INFO}"] = hs($T["zp_pr_indeks"]);
        $B["{IT_ITEM}"] .= get_template($item,$C,'',0);
    }
  
    $A["{TRESC}"] = get_template($TP["T_EMAIL_ORDER"],$B,$DB,0);
    $M["m_subject"] = "Gomez.pl-Zamówienie nr ".$B["{NUMER}"];
    $M["m_message"] = get_template($TP["T_EMAIL"],$A,'',0);
//  debug($M);
    my_mail_smtp($M);
}

zamowienie_mail2(72433, "wojciech.panek@gomez.pl", "");

echo "ok";

// Zakończenie przetwarzania i wyświetlenie wyniku
include_once("incphp/mb_common_end.php");

?>
