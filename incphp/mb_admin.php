<?php
/**
 * Gdy użytkownik jest administratorem wyświetla menu admina
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

get_head('<link rel="stylesheet" type="text/css" href="' . $_GLOBAL['page_url'] . 'template/admin.css"/>');
$js = '<script type="text/javascript" src="' . $_GLOBAL['page_url'] . 'inchtml/ssm.js">
//Dynamic-FX slide in menu v6.5 (By maXimus, http://maximus.ravecore.com/)
//Updated July 8th, 03\' for doctype bug
//For full source, and 100\'s more DHTML scripts, visit http://www.dynamicdrive.com
</script>
<script type="text/javascript" src="' . $_GLOBAL['page_url'] . 'inchtml/ssmItems.js"></script>

<script type="text/javascript">

function my_panel(mode, par1, par2) {
  var pre = "";
  switch(mode) {
    case "strona":
      var adres = pre+"admin/cms.php";
      adres += "?cms="+par1;
      popup(adres,"panel2");
    break;
    case "sklep":
      var adres = pre+"admin/shop_category.php?sz_ka_id="+par1;
      popup(adres,"panel2");
    break;
    case "boks":
      var adres = pre+"admin/cms_boks.php?i="+par1+"&baza="+par2;
      popup(adres,"panel2");
    break;
    case "boks_zmien":
      var adres = pre+"admin/cms_boks.php?submit_zmien_bo=1&id="+par1+"&i="+par2+"&baza=cms";
      //alert(adres);
      popup(adres,"panel2");
    break;
    case "link_set":
      var adres = pre+"admin/link_set.php?i="+par1;
      popup(adres,"panel2");
    break;
    default:
      var adres = mode;
      popup(adres,"panel2");
    
  }
}

function my_adminopt(obj) {
  my_panel(obj.options[obj.selectedIndex].value);
  obj.selectedIndex = 0;
}

var menupos=0;';
$PO[1] = "";
$PO[2] = "&nbsp;&nbsp;";
$PO[3] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

foreach($AMENU as $k=>$T) {
  if(!$Cuzytkownik->test_right($T[1])) continue;
  if($T[3]!="")
    $js .= 'ssmItems[menupos++]=["'.$PO[$T[0]].$T[2].'","'.(substr($T[3],0,11)=="javascript:"?$T[3]:(strpos($T[3],"?a=lo")!==false?'index.php?a=lo':'javascript:my_panel(\''.$_GLOBAL["page_url"].'/'.$T[3].'\',\'\',\'\',\'\');')).'",""]
    ';
  else 
    $js .= 'ssmItems[menupos++]=["'.$T[2].'"]
    ';
}


$js .= 'buildMenu();
</script>

';

get_head($js);
?>