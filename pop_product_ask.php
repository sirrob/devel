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


/**
 * Definiowanie klasy powiadom
 */
class powiadom extends mb { 
  function powiadom() {
  global $Cuzytkownik;
    $this->MESS_ERR = array();
    if(isset($_POST["a"]) and $_POST["a"]=="po"){
      foreach($_POST as $key=>$val) $this->WYNIK["fo"][$key] = $val;
      if($this->test_arg()) {
        $this->wyslij();
        $this->mode="prn_potwierdz";
      } else $this->mode = "prn_form";
    }elseif(isset($_GET["i"])) {
      $this->WYNIK["fo"]["po_url"] = $_GET["i"];
      $this->WYNIK["fo"]["po_tytul"] = $_GET["t"];
      $this->WYNIK["fo"]["po_info"] = "Witam,\nChciałem zapytać o produkt ".$this->WYNIK["fo"]["po_tytul"];
      $this->mode = "prn_form";
      if($Cuzytkownik->is_login()) {
        $this->WYNIK["fo"]["po_od"] = $Cuzytkownik->get_login();
        $this->WYNIK["fo"]["po_od_email"] = $Cuzytkownik->get_email();
        
      }
    }else{
      
      onload("window.close();");
    }
  }

  /**
   * testowanie argumentów
   */
  
  function test_arg() {
    $ARG = array("po_nadawca","po_email");
    $this->ERROR = test_is_empty($ARG, $this->WYNIK["fo"]);
    $pole = "po_email";
    if($this->WYNIK["fo"][$pole]!="" and !test_email($this->WYNIK["fo"][$pole])){
      $this->ERROR[$pole] = 1;
    }
    
    if(is_array($this->ERROR)){
      $this->MESS_ERR[] = '{T_ERR_FORM}';
      $this->MESS_ERR[] = '- {T_ERR_ALL}';
      return false;
    }
    return true;
  }
  
  /**
   * wysyłanie wiadomości
   */
  
  function wyslij() {
  global $_GLOBAL;
    $B["{NADAWCA}"] = s($this->WYNIK["fo"]["po_nadawca"]);
    $B["{EMAIL}"] = s($this->WYNIK["fo"]["po_email"]);
    $B["{TELEFON}"] = s($this->WYNIK["fo"]["po_telefon"]);
    $B["{MESS}"] = nl2br(s($this->WYNIK["fo"]["po_info"]));
    $B["{LINK}"] = $_GLOBAL["page_url"]."pl/produkt/".urldecode($this->WYNIK["fo"]["po_url"]).'/';
//echo  $B["{LINK}"] . '<br>';
//echo $this->WYNIK["fo"]["po_url"];
    $TP = get_komunikat(array("T_EMAIL","T_MAIL_ASKFORPRODUCT"));
    $A["{TRESC}"] = get_template($TP["T_MAIL_ASKFORPRODUCT"],$B,'',0);
    
    $M["m_subject"] = "Zapytanie w sprawie produktu";
    $M["m_to"] = $_GLOBAL["mail_kontakt"];    
    
    $M["m_message"] = get_template($TP["T_EMAIL"],$A,'',0);
    my_mail_smtp($M);

  }
  
  /**
   * Generowanie strony
   */
  function get_strona() {
  global $_GLOBAL;

    if(count($this->MESS_ERR)) {
      $A["{MESS}"] = join("<br/>",$this->MESS_ERR);
    } else {
      $DD[] = "IT_ERROR";
    }
    
    if($this->mode == "prn_form") {
      $DD[] = "IT_POTWIERDZ";
      $A["{PO_NADAWCA}"] = hs($this->WYNIK["fo"]["po_nadawca"]);
      $A["{PO_EMAIL}"] = hs($this->WYNIK["fo"]["po_email"]);
      $A["{PO_TELEFON}"] = hs($this->WYNIK["fo"]["po_telefon"]);
      $A["{PO_URL}"] = h($this->WYNIK["fo"]["po_url"]);
      $A["{PO_INFO}"] = hs($this->WYNIK["fo"]["po_info"]);
    } else {
      $DD[] = "IT_FORM";
    }
    return get_template("shop_product_ask",$A,$DD);
  }
}

$Ccms = new powiadom();

$main = get_template("page_print",array("{STRONA}"=>$Ccms->get_strona()));

/**
 * Zakończenie przetwarzania i wyświetlenie wyniku
 */
include_once("incphp/mb_common_end.php");

?>