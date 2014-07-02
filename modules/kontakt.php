<?php if(!defined('SMDESIGN')) die();

/**
 * @author Michał Bzowy
 * @since 2006-11-19 
 */

class kontakt extends mb {
  function get_local() {
  global $Ccms;
    $R[] = $Ccms->get_tytul();
    return $R;
  }
  function get_strona() {
  global $Ccms,$_GLOBAL;
    $A["{TOPID}"] = $Ccms->WYNIK["fo"]["st_st_id"];
    $A["{STID}"] = $Ccms->st_id;
    $AX["{STID}"] = $Ccms->st_id;
    
    $A["{TYTUL}"] = $Ccms->get_tytul();
    
    if(isset($_POST["submit_kontakt"])){
      $M["m_to"] = $_GLOBAL["mail_kontakt"];      
      //$M["m_to"] = "mike@imt-host.pl";      
      $M["m_subject"] = "Zapytanie z formularz kontaktowego";      
      $B[] = "Wiadomość ze strony (Formularz kontatkowy)";
      $B[] = "";
      $B[] = "Od: ".$_POST["imie"];
      $B[] = "E-mail: ".$_POST["email"];
      $B[] = "Tresc: ".nl2br(htmlspecialchars($_POST["tresc"]));

      $B[] = "";
      $B[] = "-------------------------------------------------------------------------";
      $B[] = "Wiadomość wysłana automatycznie, proszę na nią nie odpowiadać.";
      $M["m_message"] = join("<br/>\n",$B);
      my_mail_smtp($M);
      $DC[] = "IT_FORM";
      $A["{TRESC}"] = get_template("contact",$AX,$DC);
    } else {
      $DC[] = "IT_INFO";
      $A["{TRESC}"] = s($Ccms->WYNIK["fo"]["st_tresc"]);
      $A["{TRESC}"] .= get_template("contact",$AX,$DC);
      $A["{TRESC}"].= s($Ccms->WYNIK["fo"]["st_html"]);  
    }
    $A["{TRESC}"].= s($Ccms->WYNIK["fo"]["st_html"]);  
    
    return get_template("module",$A);
  }
}
?>