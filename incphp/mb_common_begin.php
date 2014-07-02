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

/**
 * Tablica z TAGami szablonu głównego, podstawienie 2
 */
$MAIN = array();

/**
 * Tablica z TAGami szablonu głównego, podstawienie 1
 */
$TPL = array();

/**
 * Wartość dodawana do TAGu <body>, onload, onunload, id
 */
$MAIN["{BODY}"]="";

/**
 * Tablica ze słownikiem, podstawienie 0
 */
$L = array();

/**
 * Tablica z TAGami szablonu głównego do usunięcia
 */
$DD=array();


if($Cuzytkownik->test_right("cms","*")) {
  include_once("incphp/mb_admin.php");
} 
?>