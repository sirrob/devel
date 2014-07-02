<?php if ( !defined('SMDESIGN') ) {die("Hacking attempt");}

error_reporting(0);
$_GLOBAL["page_url"] = "http://".$_SERVER['HTTP_HOST'] . '/';
$_GLOBAL["db_pre"] = "mm";

$_GLOBAL["db_host"] = "localhost";
$_GLOBAL["db_user"] = "admin_www-devel";
$_GLOBAL["db_password"] = "home2008";
$_GLOBAL["db_database"] = "admin_www";

$_GLOBAL["mk_server"] = "http://www.multikupiec.gomez.pl/external/devel/server_gz.php";

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