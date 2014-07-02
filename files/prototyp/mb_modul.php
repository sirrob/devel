<?php if ( !defined('SMDESIGN') ) { die('Hacking attempt');}

$MODUL[1] = array("Strona główna","home","","MO_HOME",1);
define("MO_HOME",1);
$MODUL[2] = array("Kontakt","kontakt","","MO_KONTAKT",2);
define("MO_KONTAKT",2);
$MODUL[3] = array("Newseltter","newsletter","","MO_NEWSLETTER",3);
define("MO_NEWSLETTER",3);
$MODUL[4] = array("Konto użytkownika","sklep_uzytkownik","","MO_MOJEKONTO",4);
define("MO_MOJEKONTO",4);
$MODUL[5] = array("Mapa serwisu","mapa","","MO_MAPA",5);
define("MO_MAPA",5);
$MODUL[6] = array("Sklep - koszyk","sklep_koszyk","","MO_KOSZYK",6);
define("MO_KOSZYK",6);
$MODUL[7] = array("Sklep - zamowienia","sklep_zamowienie","","MO_ZAMOWIENIE",7);
define("MO_ZAMOWIENIE",7);
$MODUL[8] = array("Sklep - zakup","sklep_zakup","","MO_ZAKUP",8);
define("MO_ZAKUP",8);
$MODUL[9] = array("Brands","brands","","MO_BRANDS",9);
define("MO_BRANDS",9);
$MODUL[10] = array("Fasion Store","fasion_store","","MO_FASION_STORE",10);
define("MO_FASION_STORE",10);
$MODUL[11] = array("News & Events","news_events","","MO_NEWS_EVENTS",11);
define("MO_NEWS_EVENTS",11);
$MODUL[12] = array("Gomez Club","gomez_club","","MO_GOMEZ_CLUB",12);
define("MO_GOMEZ_CLUB",12);
$MODUL[13] = array("Sale","sale","","MO_SALE",13);
define("MO_SALE",13);
    

/* Struktura menu administracyjnego */

$AMENU[] = array("1","admin","Sklep","");
  $AMENU[] = array("2","islogin","Zamówienia","/admin/shop_order.php");
  $AMENU[] = array("2","admin","Kategorie","/admin/shop_category.php");
  $AMENU[] = array("2","admin","Import danych","/admin/shop_import.php");
  $AMENU[] = array("2","admin","Promocje","/admin/shop_sale.php");
  
$AMENU[] = array("1","admin","Sklep - kartoteki","");
  $AMENU[] = array("2","islogin","Produkty","/admin/shop_product.php");
  $AMENU[] = array("2","islogin","Grupy produktowe","/admin/shop_product_group.php");
  $AMENU[] = array("2","islogin","Przeceny","/admin/shop_discount.php");
  $AMENU[] = array("2","islogin","Kontrahenci","/admin/shop_client.php");
  $AMENU[] = array("2","admin","Formy płatności","/admin/shop_payment.php");
  $AMENU[] = array("2","admin","Dostawa","/admin/shop_delivery.php");
  $AMENU[] = array("2","admin","Producenci","/admin/shop_brand.php");
  $AMENU[] = array("2","admin","Etykiety","/admin/shop_label.php");
  $AMENU[] = array("2","admin","Tabele rozmiarów","/admin/shop_table.php");
  $AMENU[] = array("2","admin","Polecane","/admin/shop_recomended.php");
  $AMENU[] = array("2","admin","Specjalnie dla Ciebie","/admin/shop_foryou.php");
  $AMENU[] = array("2","admin","Kody rabatowe","/admin/rabat_code.php");
  $AMENU[] = array("2","admin","Kolekcje","/admin/shop_collection.php");
  $AMENU[] = array("2","admin","Zmiana widoczności","/admin/shop_visibility.php");


$AMENU[] = array("1","cms","Strony informacyjne","/admin/cms.php");

$AMENU[] = array("1","admin","Brands","");
$AMENU[] = array("2","admin","Lista","/admin/brands_list.php");
$AMENU[] = array("2","admin","Prezentacja","/admin/brands_view.php");

$AMENU[] = array("1","admin","Sale","");
$AMENU[] = array("2","admin","Baner główny","/admin/sale_main_baner.php");
$AMENU[] = array("2","admin","Baner Women","/admin/sale_women_baner.php");
$AMENU[] = array("2","admin","Baner Men","/admin/sale_men_baner.php");

$AMENU[] = array("1","cms","News & Events","/admin/news_events_news.php");
// $AMENU[] = array("2","cms","Baner","/admin/news_events_baner.php");
// $AMENU[] = array("2","cms","Treść","/admin/news_events_news.php");

$AMENU[] = array("1","admin","Fasion Store","/admin/fasion_store_news.php");
// $AMENU[] = array("2","admin","Baner","/admin/fasion_store_baner.php");
// $AMENU[] = array("2","admin","Treść","/admin/fasion_store_news.php");

$AMENU[] = array("1","admin","Strona główna","");
  $AMENU[] = array("2","admin","Baner górny","/admin/homepage_top.php");
  $AMENU[] = array("2","admin","Baner dolny","/admin/homepage_bottom.php");

$AMENU[] = array("1","admin","Gomez Club","/admin/gomez_club.php");

$AMENU[] = array("1","admin","Układ strony","");
//  $AMENU[] = array("2","admin","Menu top","/admin/link_set.php?i=menutop");
//  $AMENU[] = array("2","admin","Strona główna","/admin/homepage.php");
//  $AMENU[] = array("2","admin","Menu główne","/admin/link_set.php?i=menumain");
//  $AMENU[] = array("2","admin","Menu dolne","/admin/link_set.php?i=menufoot");
    $AMENU[] = array("2","admin","Menu główne","/admin/menumain.php");
  $AMENU[] = array("2","admin","Menu dolne","/admin/menubottom.php");
  $AMENU[] = array("2","admin","Custom description","/admin/custom_description.php");


$AMENU[] = array("2","admin","Boksy boczne","/admin/cms_boks.php");
  $AMENU[] = array("2","admin","Generuj","/admin/index.php?prototyp=1");
  $AMENU[] = array("2","cms","Komunikaty/maile","/admin/message.php");

//  $AMENU[] = array("2","admin","Języki","/admin/cms_boks.php");
//  $AMENU[] = array("2","admin","Waluty","/admin/cms_boks.php");
$AMENU[] = array("1","admin","Moduły","");
  $AMENU[] = array("2","admin","Newsletter","/admin/newsletter.php");
$AMENU[] = array("1","admin","Konfiguracja","/admin/config.php");
  $AMENU[] = array("2","admin","Szablony","/admin/template.php");
  $AMENU[] = array("2","admin","Użytkownicy","/admin/users.php");
  $AMENU[] = array("2","islogin","Menadżer plików","javascript:my_plik('','','','');");

$AMENU[] = array("1","islogin","Wygloguj się","/index.php?a=lo");

?>