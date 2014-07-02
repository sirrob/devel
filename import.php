<?php 
print($url);
/**
 * Skrypt odpowiedzalny za organizację wyświetlania strony
 * 
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
 * 
 */
include_once("incphp/mb_pliki.php");

$nr = $db->query("select * from fa_oferta_klient where mid(kl_login_data,1,4)>='2008' and kl_id > 11 order by kl_id");
$WJ[1]="wielkopolskie";
$WJ[2]="śląskie";
$WJ[3]="zachodnio-pomorskie";
$WJ[4]="dolnośląskie";
$WJ[5]="kujawsko-pomorskie";
$WJ[6]="lubelskie";
$WJ[7]="lubuskie";
$WJ[8]="łódzkie";
$WJ[9]="małopolskie";
$WJ[10]="mazowieckie";
$WJ[11]="opolskie";
$WJ[12]="podkarpackie";
$WJ[13]="podlaskie";
$WJ[14]="pomorskie";
$WJ[15]="świętokrzyskie";
$WJ[16]="warmińsko-mazurskie";


while($T = $db->fetch($nr)) {
  $Q=array();
  $POLA = array("email","miasto","kraj","ulica","nip","kod");
    foreach($POLA as $p) {
      $Q[] = "ko_".$p."='".iso2utf(a($T["kl_".$p]))."'";
    }
    if($T["kl_firma"]!="") {
      $Q[] = "ko_nazwa='".iso2utf(a($T["kl_firma"]))."'";
    } else {
      $Q[] = "ko_nazwa='".iso2utf(a($T["kl_imie"]." ".$T["kl_nazwisko"]))."'";
    }

    $Q[] = "ko_wojewodztwo='".$WJ[$T["kl_wj_id"]]."'";
    $Q[] = "ko_telefony=';;".a($T["kl_telefon"]).";|;;;|;;;|'";
    $Q[] = "ko_pracownicy='".iso2utf(a($T["kl_imie"]." ".$T["kl_nazwisko"])).";;|;;|;;|'";
    $Q[] = "ko_ma_id=0";
    $Q[] = "ko_odbiorca='1'";
    $Q[] = "ko_data_in=".mktime(substr($T["kl_data_in"],8,2),substr($T["kl_data_in"],10,2),0,substr($T["kl_data_in"],4,2),substr($T["kl_data_in"],6,2),substr($T["kl_data_in"],0,4));
    $q = "insert ".dn("kontrahent")." set ".join(", ",$Q);
    //print($q.'<Br/><br/>');
    $db->query($q);      
    $Q = array();
    $id = $db->insert_id();
    $Q[] = "ko_id=".$id;
    $Q[] = "ko_status='1'";
    $Q[] = "ko_email_org='".a($T["kl_email"])."'";
    $Q[] = "ko_skad='".$T["kl_skad"]."'";
    $Q[] = "ko_data_login=".mktime(substr($T["kl_login_data"],8,2),substr($T["kl_login_data"],10,2),0,substr($T["kl_login_data"],4,2),substr($T["kl_login_data"],6,2),substr($T["kl_login_data"],0,4));
    $Q[] = "ko_data_wizyta=".mktime(substr($T["kl_login_data"],8,2),substr($T["kl_login_data"],10,2),0,substr($T["kl_login_data"],4,2),substr($T["kl_login_data"],6,2),substr($T["kl_login_data"],0,4));
    $Q[] = "ko_login_ilosc=".$T["kl_login_ilosc"];
    $Q[] = "ko_haslo='".pass2code($T["kl_haslo"])."'";
    $q = "insert ".dn("sklep_kontrahent")." set ".join(", ",$Q);  
    //print($q.'<Br/><br/>');
        print($T["kl_id"].'->'.$id.'-----------<Br/><br/>');

    $db->query($q);      
}


?>