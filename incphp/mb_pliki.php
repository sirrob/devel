<?php
/**
 * Zarządzanie etykietami przypisywanymi do produtków
 *
 * @author    Michał Bzowy
 * @copyright Copyright (c) 2008, Michał Bzowy
 * @since     2008-08-04 12:36:48
 * @link      www.imt-host.pl
 */

/**
 * Autoryzacja
 */
if (!defined('SMDESIGN')) {
    die("Hacking attempt");
}

define("COMMON_PATH", "common/");

include_once("incphp/mb_ini.php");

include_once(COMMON_PATH . "incphp/mb_func.php");
include_once(COMMON_PATH . "incphp/class_mysql.php");

$nr = $db->query("select co_klucz,co_wartosc from " . dn("config") . " order by co_nr");
while ($T = $db->fetch($nr)) $_GLOBAL[$T[0]] = $T[1];

if ($_GLOBAL["shop_rabat"] != "" and $_GLOBAL["shop_rabat"] != "0" and test_int($_GLOBAL["shop_rabat"])) {
    if (($_GLOBAL["shop_rabat_od"] == "" or $_GLOBAL["shop_rabat_od"] <= date("Y-m-d H:i"))
        and
        ($_GLOBAL["shop_rabat_do"] == "" or $_GLOBAL["shop_rabat_do"] >= date("Y-m-d H:i"))
    ) {
        define('SHOP_RABAT', $_GLOBAL["shop_rabat"]);
    }
}

define('TEMPLATE', $_GLOBAL["template"]);

include_once("incphp/mb_modul.php");
include_once(COMMON_PATH . "incphp/class_html_tag.php");
include_once(COMMON_PATH . "incphp/class_mail.php");
include_once(COMMON_PATH . "incphp/class_mb.php");
include_once(COMMON_PATH . "incphp/class_uzytkownik.php");
include_once(COMMON_PATH . "incphp/class_nawigacja.php");
include_once(COMMON_PATH . "incphp/class_layout.php");
include_once(COMMON_PATH . "incphp/class.cleanurl.php");
?>