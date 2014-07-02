<?php if ( !defined('SMDESIGN') ) {die("Hacking attempt");}
/******************************************************************

    Created by: Michal Bzowy
    Created: 2008-01-16
    $Date: 2008-01-18 12:36:48 +0100 (Pt, 18 sty 2008) $
                                        
*******************************************************************/
error_reporting(0);
//$_GLOBAL["page_url"] = "http://".$_SERVER['HTTP_HOST'] . '/sklep';
$_GLOBAL["page_url"] = "http://".$_SERVER['HTTP_HOST'] . '/';
//$_GLOBAL["page_url"] = "http://".$_SERVER['HTTP_HOST'] . '';
# Baza danych
$_GLOBAL["db_pre"] = "mm";


/*
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `mm_produkt_rozmiar_v` (
`ms_pr_id` int(10) unsigned
,`ms_at_id` smallint(5) unsigned
);

CREATE TABLE IF NOT EXISTS `mm_sklep_produkt_v` (
`pr_id` int(10) unsigned
,`pr_nazwa` varchar(100)
,`pr_pd_id` int(10) unsigned
,`pr_cena_a_brutto` double unsigned
,`pr_cena_w_brutto` double
,`pr_indeks` varchar(15)
,`pr_kolor` varchar(30)
,`pr_etykieta` varchar(100)
,`pr_tabela` varchar(100)
,`pr_topsell` enum('0','1')
,`pr_nowosc` enum('0','1')
,`pr_promocja` enum('0','1')
,`pr_polecany` enum('0','1')
,`pr_data_in` int(10) unsigned zerofill
,`pr_plik` varchar(100)
,`pr_opis` text
,`pr_stan` decimal(32,0)
,`pr_sprzedano` mediumint(8) unsigned
,`pr_odslon` mediumint(8) unsigned
,`pr_podobne` varchar(250)
,`pr_punkt` mediumint(8) unsigned
);

CREATE TABLE IF NOT EXISTS `mm_szablon_plik_v` (
`sp_id` int(10) unsigned
,`sp_sb_id` int(10) unsigned
,`sp_plik` varchar(50)
,`sp_tresc` text
);

CREATE TABLE IF NOT EXISTS `mm_uzytkownik_v` (
`uid` varbinary(12)
,`id` int(11) unsigned
,`login` varchar(100)
,`status` varchar(1)
,`haslo` varchar(64)
,`nazwa` varchar(100)
,`email` varchar(100)
);

DROP TABLE IF EXISTS `mm_produkt_rozmiar_v`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mm_produkt_rozmiar_v` AS select distinct `mm_magazyn_stan`.`ms_pr_id` AS `ms_pr_id`,`mm_magazyn_stan`.`ms_at_id` AS `ms_at_id` from `mm_magazyn_stan` where ((`mm_magazyn_stan`.`ms_ilosc` > 0) and (`mm_magazyn_stan`.`ms_ma_id` <> 4));

DROP TABLE IF EXISTS `mm_sklep_produkt_v`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mm_sklep_produkt_v` AS select `s1`.`pr_id` AS `pr_id`,`s2`.`pr_nazwa` AS `pr_nazwa`,`s2`.`pr_pd_id` AS `pr_pd_id`,`s1`.`pr_cena_a_brutto` AS `pr_cena_a_brutto`,`s1`.`pr_cena_w_brutto` AS `pr_cena_w_brutto`,`s1`.`pr_indeks` AS `pr_indeks`,`s1`.`pr_kolor` AS `pr_kolor`,`s2`.`pr_etykieta` AS `pr_etykieta`,`s2`.`pr_tabela` AS `pr_tabela`,`s2`.`pr_topsell` AS `pr_topsell`,`s2`.`pr_nowosc` AS `pr_nowosc`,`s2`.`pr_promocja` AS `pr_promocja`,`s2`.`pr_polecany` AS `pr_polecany`,`s1`.`pr_data_in` AS `pr_data_in`,`s1`.`pr_plik` AS `pr_plik`,`s2`.`pr_opis` AS `pr_opis`,if(isnull(sum(`mm_magazyn_stan`.`ms_ilosc`)),0,sum(`mm_magazyn_stan`.`ms_ilosc`)) AS `pr_stan`,`s2`.`pr_sprzedano` AS `pr_sprzedano`,`s2`.`pr_odslon` AS `pr_odslon`,`s2`.`pr_podobne` AS `pr_podobne`,`s2`.`pr_punkt` AS `pr_punkt` from (((`mm_produkt` `s1` left join `mm_magazyn_stan` on((`s1`.`pr_id` = `mm_magazyn_stan`.`ms_pr_id`))) left join `mm_sklep_produkt` `s2` on((`s1`.`pr_id` = `s2`.`pr_id`))) left join `mm_sklep_kategoria_produkt` on((`mm_sklep_kategoria_produkt`.`kp_pr_id` = `s1`.`pr_id`))) where ((`s2`.`pr_widoczny` = _utf8'1') and (`mm_sklep_kategoria_produkt`.`kp_widoczna` = _utf8'1') and (`mm_magazyn_stan`.`ms_ilosc` is not null) and (`mm_magazyn_stan`.`ms_ilosc` >= 0)) group by `s1`.`pr_id` order by `s2`.`pr_nazwa`,`s1`.`pr_id`;

DROP TABLE IF EXISTS `mm_szablon_plik_v`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mm_szablon_plik_v` AS select `m`.`sp_id` AS `sp_id`,`m`.`sp_sb_id` AS `sp_sb_id`,`m`.`sp_plik` AS `sp_plik`,`m`.`sp_tresc` AS `sp_tresc` from `mm_szablon_plik` `m`;

DROP TABLE IF EXISTS `mm_uzytkownik_v`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `mm_uzytkownik_v` AS select concat(_utf8'u',`mm_uzytkownik`.`uz_id`) AS `uid`,`mm_uzytkownik`.`uz_id` AS `id`,`mm_uzytkownik`.`uz_login` AS `login`,`mm_uzytkownik`.`uz_status` AS `status`,`mm_uzytkownik`.`uz_haslo` AS `haslo`,`mm_uzytkownik`.`uz_nazwa` AS `nazwa`,`mm_uzytkownik`.`uz_email` AS `email` from `mm_uzytkownik` union select concat(_utf8'k',`ko`.`ko_id`) AS `uid`,`ko`.`ko_id` AS `id`,`ko`.`ko_email` AS `login`,`sk`.`ko_status` AS `status`,`sk`.`ko_haslo` AS `haslo`,`ko`.`ko_nazwa` AS `nazwa`,`ko`.`ko_email` AS `email` from (`mm_kontrahent` `ko` left join `mm_sklep_kontrahent` `sk` on((`ko`.`ko_id` = `sk`.`ko_id`)));
 
*/
//$_GLOBAL["db_host"] = "localhost";
//$_GLOBAL["db_user"] = "gomezopl_mk";
//$_GLOBAL["db_password"] = "23dw34wrfqaaf3!.";
//$_GLOBAL["db_database"] = "gomezopl_www";


/*
na nowym serwerze sa bazy: admin_www oraz admin_www-devel
uzytkownik admin_www, haslo home2008
admin_mk oraz admin_mk-devel
uzytkownik admin_mk, haslo home2009
 */

$_GLOBAL["db_host"] = "sql.gomez.iq.pl";
$_GLOBAL["db_user"] = "admin_www";
$_GLOBAL["db_password"] = "home2008";
$_GLOBAL["db_database"] = "admin_www";


$_GLOBAL["mk_server"] = "http://www.multikupiec.gomez.pl/external/server_gz.php";

set_time_limit(90);
/**************** nie zmieniac ***************?*/
define('SID',"fpp324qr49d30hlrfwehe45fd");


# Opcje developerskie
if(isset($_GET["debug"]) and $_GET["debug"]=="tester") {
  $_SESSION[SID]["debug"] = true;
} elseif(isset($_GET["debug"]) and $_GET["debug"]=="end") {
  unset($_SESSION[SID]["debug"]);
}

if(isset($_SESSION[SID]["debug"]) and $_SESSION[SID]["debug"] == "true") {
  $_GLOBAL["debug"] = true;
  error_reporting(E_ALL^E_NOTICE);
}else {
  $_GLOBAL["debug"] = false;
  error_reporting(0);
}

//# Logowanie
$_GLOBAL["key"] = "_e0a2449eAif3oe9agh6"; //Klucz kodowania hasła
$_GLOBAL["keylogin"] = "4j2eju8323a3354glnp3"; //ID cookie

$_GLOBAL["przyjazny_url"] = true;

define('T_JOIN',"\n"); //znak nowego wiersza kodu
?>