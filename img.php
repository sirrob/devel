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
/**
 * Początek przetwarzania
 * 
 */

$nr  = $db->query("select id, pr_plik from sklep_gomez.fa_oferta_produkt where id<>0 order by pr_id");

while($T = $db->fetch($nr)) {
  $i=1;
  $X = explode("|",$T[1]);
  foreach($X as $k) {
    if($k=="") continue;
    print($k." => ".$i.'<br/>');
    copy("pliki/oferta/1/".$k.".jpg","_out/".$T[0]."_".$i++.".jpg");
  }
//  break;
}

?>