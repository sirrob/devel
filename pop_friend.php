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

$lang = 'pl';
if ((isset($_GET['langid'])) AND ($_GET['langid'] == 2)) {
  $lang = 'en';
}

include_once("template/languages/" . $lang . "/language.php");


/**
 * Klasa powiadom
 */

class powiadom extends mb { 

  function powiadom() {
    global $Cuzytkownik;

    $this->MESS_ERR = array();
    if(isset($_POST["a"]) and $_POST["a"]=="po"){
      foreach($_POST as $key=>$val) $this->WYNIK["fo"][$key] = $val;
      if($this->test_arg()) {
          $this->WYNIK["fo"]["po_url"] = $_GET["i"];
          $this->WYNIK["fo"]["po_tytul"] = $_GET["t"];
        $this->wyslij();
        $this->mode="prn_potwierdz";
      } else $this->mode = "prn_form";
    }elseif(isset($_GET["i"])) {
      $this->WYNIK["fo"]["po_url"] = $_GET["i"];
      $this->WYNIK["fo"]["po_tytul"] = $_GET["t"];
      switch($_GET["k"]) {
        //case "produkt": $this->WYNIK["fo"]["po_info"] = "Cześć,\nPolecam ciekawy produkt - ".$this->WYNIK["fo"]["po_tytul"]; break;
        case 'produkt': break;
        default: $this->WYNIK["fo"]["po_info"] = $L['{T_TELL_FRIEND_TITLE}'].$this->WYNIK["fo"]["po_tytul"];
      }
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
   * Sprawdzanie argumentów
   */
  function test_arg() {
    $ARG = array("po_od","po_do","po_od_email","po_do_email");
    $this->ERROR = test_is_empty($ARG, $this->WYNIK["fo"]);
    $pole = "po_od_email";
    if($this->WYNIK["fo"][$pole]!="" and !test_email($this->WYNIK["fo"][$pole])){
      $this->ERROR[$pole] = 1;
    }
    $pole = "po_do_email";
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
   * Wysyłanie wiadomości
   */
  function wyslij() {
  global $_GLOBAL,$_GET;
    $src=my_fread("template/".TEMPLATE."/powiadom_mail.tpl");

    
    $B["{OD}"] = s($this->WYNIK["fo"]["po_od"].' ['.$this->WYNIK["fo"]["po_od_email"].']');
    $B["{OOD}"] = s($this->WYNIK["fo"]["po_od"]);
    $B["{NAZWA}"] = s($this->WYNIK["fo"]["po_tytul"]);
    $B["{DO}"] = s($this->WYNIK["fo"]["po_od"]);
    $B["{MESS}"] = nl2br(s($this->WYNIK["fo"]["po_info"]));
    $B["{LINK}"] = $_GLOBAL["page_url"]."/".urldecode($this->WYNIK["fo"]["po_url"]);
    
    $TP = get_komunikat(array("T_EMAIL","T_MAIL_TELLAFRIEND"));
    $A["{TRESC}"] = get_template($TP["T_MAIL_TELLAFRIEND"],$B,'',0);
    
    $M["m_to"] = s($this->WYNIK["fo"]["po_do_email"]);
   // $M["m_subject"] = "Ciekawa strona!";
    $M["m_subject"] = $this->WYNIK["fo"]["po_od"] .$L['{T_TELL_FRIEND_RECOMMEND_YOU}'] . $this->WYNIK["fo"]["po_tytul"] . $L['{T_TELL_FRIEND_RECOMMEND_FROM}'];
    
    //$M["m_message"] = get_template($TP["T_EMAIL"],$A,'',0);
    $M["m_message"] = $A["{TRESC}"];
    //my_mail_smtp($M);
    
    $to      = $this->WYNIK["fo"]["po_do_email"];
    $subject = $M["m_subject"];
    $message = $M["m_message"];
    $headers = 'From: ' . $this->WYNIK["fo"]["po_od"] . "\r\n";
    //. 'Reply-To: ' . $this->WYNIK["fo"]["po_od"] . "\r\n";

    mail($to, $subject, $message, $headers);

    
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
      $A["{PO_OD}"] = hs($this->WYNIK["fo"]["po_od"]);
      $A["{PO_OD_EMAIL}"] = hs($this->WYNIK["fo"]["po_od_email"]);
      $A["{PO_DO}"] = hs($this->WYNIK["fo"]["po_do"]);
      $A["{PO_DO_EMAIL}"] = hs($this->WYNIK["fo"]["po_do_email"]);
      $A["{PO_URL}"] = h($this->WYNIK["fo"]["po_url"]);
      $A["{PO_INFO}"] = hs($this->WYNIK["fo"]["po_info"]);
    } else {
      $DD[] = "IT_FORM";
    }
    return get_template("tell_friend",$A,$DD);
  }
}

$Ccms = new powiadom();

$main = get_template("page_print",array("{STRONA}"=>$Ccms->get_strona()));

/**
 * Zakończenie przetwarzania i wyświetlenie wyniku
 */
include_once("incphp/mb_common_end.php");

?>