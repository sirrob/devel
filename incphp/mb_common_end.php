<?php
/**
 * Początek przetwarzania każdej strony
 * 
 * Analiza parametrów wejściowych i konwerja URLa
 * 
 * @author Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since 2008-08-04 12:36:48
 * @link www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if ( !defined('SMDESIGN') ) {die("Hacking attempt");}

$MAIN["{BODY}"] = onload(); 

if(is_object($Cbasket)) {
  $TPL["{KOSZYK_MINI}"] = $Cbasket->get_koszyk_mini();
  $TPL["{USER_MINI}"] = $Cuzytkownik->get_user_mini();
}

//include_once("template/languages/" . $_GLOBAL['lang'] . "/language.php");

$L["{HEAD}"] = get_head();
$L["{EXTDIV}"] = ''; 
$L["{BASEURL}"] = $_GLOBAL["page_url"];
$L["{DEBUG}"] = $db->get_sumary();

//tutaj dodaj wartości globalne dla szablonu
$lang = 'pl';
$TPL['{$mainmenu}'] = get_main_menu($_GLOBAL['lang']);
$TPL['{menubottomtitle1}'] = get_menu_bottom_title(2, $_GLOBAL['langid']);
$TPL['{menubottomlist1}']  = get_menu_bottom_list(2, $_GLOBAL['langid']);
$TPL['{menubottomtitle2}'] = get_menu_bottom_title(3, $_GLOBAL['langid']);
$TPL['{menubottomlist2}']  = get_menu_bottom_list(3, $_GLOBAL['langid']);
$TPL['{menubottomtitle3}'] = get_menu_bottom_title(4, $_GLOBAL['langid']);
$TPL['{menubottomlist3}']  = get_menu_bottom_list(4, $_GLOBAL['langid']);
$TPL['{menubottomtitle4}'] = get_menu_bottom_title(5, $_GLOBAL['langid']);
$TPL['{menubottomlist4}']  = get_menu_bottom_list(5, $_GLOBAL['langid']);
$TPL['{menubottomtitle5}'] = get_menu_bottom_title(6, $_GLOBAL['langid']);
$TPL['{menubottomlist5}']  = get_menu_bottom_list(6, $_GLOBAL['langid']);
$TPL['{MAINPAGEDESCRIPTION}'] = get_maindescription();

$main = get_template(strtr(strtr($main,$MAIN),$TPL),$L,$DD,0); 
$main = str_replace("{TEMPLATE}",$_GLOBAL["template"],$main); 

/**
 * Usunięcie zbędnych TAGów szablonu
 */
$main = str_replace("<!---->","",str_replace("<!--/-->","",ereg_replace("\{([0-9A-Z_]{1,20})\}","",$main)));

header("Content-type: text/html; charset=utf-8");
print($main); 
?>