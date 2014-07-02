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
$_GLOBAL['langid'] = 1;
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

function get_produkt_img($id,$nr) {
global $_GLOBAL;
  if(file_exists("files/product/images/".ceil($id/500)."/x/".$id."_".$nr.".jpg")) {
    $A["{IMG}"] = "files/product/images/".ceil($id/500)."/x/".$id."_".$nr.".jpg";
  } else {
    redirect("index.php");
  }
  $nxt=$pre='';
  for($i=0;$i<$_GLOBAL["shop_galeria"];$i++) {
    if(file_exists("files/product/images/".ceil($id/500)."/m/".$id."_".$i.".jpg")) {
      if($i<$nr) $pre = '<a href="?i='.$id.'&amp;n='.$i.'" class="prev">{T_POPRZEDNIE}</a> ';
      if($i>$nr and $nxt=='') $nxt = ' <a href="?i='.$id.'&amp;n='.$i.'" class="next">{T_NASTEPNE}</a>';
      $Q[] = '<a href="?i='.$id.'&amp;n='.$i.'"'.($i==$nr?' class="sel"':'').'>'.($i+1).'</a>';
    }
  }  
  $A["{STRONY}"] = $pre.join(" ",$Q).$nxt;
  return get_template("shop_product_img",$A);
  
}
onload("m_resize();");

$main = get_template("page_print",array("{STRONA}"=>get_produkt_img((int)$_GET["i"],(int)$_GET["n"])));

/**
 * Zakończenie przetwarzania i wyświetlenie wyniku
 */
include_once("incphp/mb_common_end.php");

?>