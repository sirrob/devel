<?php
/**
 * Wygenerowanie danych o dostępnych modułach
 * 
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-01-18 12:36:48
 * @link www.imt-host.pl
 */
if ( !defined('SMDESIGN') ) {die("Hacking attempt");}
/**
 * Tablica dostępnych modułów
 * @global array $_GLOBAL['modul']
 * @name modul
 */
$_GLOBAL["modul"] = array();

/**
 * Moduły wczytane z liku mb_modul.php
 */
$MODUL = array();

//$M_X = explode("|^|",$Cspeed->get("modul"));

require_once(dirname(__FILE__)."/../files/prototyp/mb_modul.php");


$i = 0;
foreach($MODUL as $id=>$M) {
  $m = $i+$id;
  $_GLOBAL["modul"][$m]["nazwa"] = $M[0];
  $_GLOBAL["modul"][$m]["plik"] = $M[1];
  $_GLOBAL["modul"][$m]["url"] = $M[2];
}

/**
 * Sprawdzenie do jakiej strony przypisany jest dany moduł
 *
 * @param integer $modul ID modułu
 * @return integer ID strony
 */
function get_modul_cms($modul) {
    global $db;
    $q = "select st_id from ".dn("cms")." where st_typ='modul' and st_modul='".$modul."'";
    $T = $db->onerow($q);  
    return $T[0];
}
/**
 * Definicja typu boksu - Lista linków
 */
define('B_ZWYKLY',0);
/**
 * Definicja typu boksu - Menu główne
 */
define('B_MENU',1);
/**
 * Definicja typu boksu - Baner
 */
define('B_BANER',2);
/**
 * Definicja typu boksu - Kod HTML
 */
define('B_HTML',3);
/**
 * Definicja typu boksu - Wyszukiwarka
 */
define('B_SZUKAJ',4);
/**
 * Definicja typu boksu - Menu użytkownika
 */
define('B_USER',5);
/**
 * Definicja typu boksu - Lista podstron wskazanej strony
 * @todo do realizacji
 */
define('B_PODSTRONY',6);
/**
 * Definicja typu boksu - Menu archiwum
 * @todo do realizacji
 */
define('B_ARCHIWUM',7);
/**
 * Definicja typu boksu - Newsletter
 */
define('B_NEWSLETTER',8);
/**
 * Definicja typu boksu - Sklep - menu (kategorie)
 */
define('B_SHOP_MENU',20);
/**
 * Definicja typu boksu - Sklep - TopSell
 */
define('B_SHOP_TOPSELL',21);
/**
 * Definicja typu boksu - Sklep - Nowości
 */
define('B_SHOP_NOWOSCI',22);
/**
 * Definicja typu boksu - Sklep - Polecamy
 */
define('B_SHOP_POLECAMY',23);
/**
 * Definicja typu boksu - Sklep - Promocje
 */
define('B_SHOP_PROMOCJE',24);
/**
 * Definicja typu boksu - Sklep - Ostatnio oglądane
 */
define('B_SHOP_OSTATNIE',25);
/**
 * Definicja typu boksu - Sklep - Wyszukiwarka
 */
define('B_SHOP_SZUKAJ',26);
/**
 * Definicja typu boksu - Sklep - Podsumowanie koszyka
 */
define('B_SHOP_KOSZYK',27);
?>