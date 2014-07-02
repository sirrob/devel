<?php 
/**
 * Strona startowa
 * 
 * Tu można podstawić treść zastępczą dla strony głównej
 * 
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-04 12:36:48
 * @link www.imt-host.pl
 */

/**
 * Autoryzacja
 */
define('SMDESIGN', true);
$_GLOBAL['langid'] = isset($_GET['langid']) ? $_GET['langid'] : 1;
/**
 * Załączenie bibliotek
 * 
 */
include_once("incphp/mb_pliki.php");
/**
 * Początek przetwarzania
 * 
 */
include_once("incphp/mb_common_begin.php");
include_once("incphp/class_shop.php");

if ($_GLOBAL['langid'] == 2) {
	$_GLOBAL['lang'] = 'en';
} else {
	$_GLOBAL['lang'] = 'pl';
}
include_once("template/languages/" . $_GLOBAL['lang'] . "/language.php");


$A[1] = "produkt";
$A[2] = 1;
$A[3] = (int) $_GET["i"];
$Ccms = new shop($A); 

$main = get_template("page_print",array("{STRONA}"=>$Ccms->get_produkt(true)));
include "admin/language/utf-8.php";
if(count($LANG)>0)
    {
        foreach ($LANG as $key => $value) {
            //echo $key . '--' . $value . '<br>';
            $main = str_replace('{'.$key.'}', $value, $main);
        }
    }
//echo $main;
/**
 * Zakończenie przetwarzania i wyświetlenie wyniku
 */
include_once("incphp/mb_common_end.php");

?>