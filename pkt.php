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

$q = "SELECT za_ko_id, round( sum(za_wartosc_brutto) )FROM `mm_sklep_zamowienie`WHERE za_status = '3' group by za_ko_id";

$nr = $db->query($q);
echo '<pre>';
while($T = $db->fetch($nr)) {
//  $db->query("update mm_sklep_kontrahent set ko_punkt=ko_punkt+".$T[1]." where ko_id=".$T[0]);

	print_r($T);
}
echo '</pre>';

print $db->get_sumary();
?>