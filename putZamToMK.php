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

zamowienie_dodaj(intval($_POST["z_id"]), 4); 

// Zakończenie przetwarzania i wyświetlenie wyniku
include_once("incphp/mb_common_end.php");

?>
