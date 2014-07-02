<?php if(!defined('SMDESIGN')) die();

class login extends mb {

  function login() {
    // if(isset($_GET["a"]) and $_GET["a"]=="nlout" and test_email($_GET["email"])) {
    //   $this->usun(trim($_GET["email"]));
    //   $this->mode = "prn_wypis";
    // }
  }
  
  
  function get_local() {
    global $Ccms;

    $R[] = $Ccms->get_tytul();
    return $R;
  }
  
  function get_strona() {
    global $Ccms, $db, $_GLOBAL, $L, $Cuzytkownik;

    $userID = (isset($Cuzytkownik)) ? $Cuzytkownik->get_id() : 0;
    $redirect = (isset($_GET['redirect'])) ? $_GET['redirect'] : '';
    $url = 'http://'.$_SERVER["SERVER_NAME"] . '/' . $_GLOBAL['lang'] . '/' . $redirect . '/';

    if ($userID > 0) {      
      header('Location: '.$url);
    } else {
      $DD[] = "WSTEP_ITEM";
      $DA[] = "WYSLIJ";
      $DA[] = "POTWIERDZ";
      $DA[] = "WYPIS";
      $tpl = get_template("login",array(),$DA);

      $A["{EMAIL}"] = (isset($ARG["ne_email"])?s($ARG["ne_email"]):"");
      $A["{IMIE}"] = (isset($ARG["ne_imie"])?s($ARG["ne_imie"]):"");
      $A["{INFO}"] = (isset($ARG["ne_info"])?s($ARG["ne_info"]):"");

      $A["{URL}"] = $url;
      $A['{T_CONTINUE_WITHOUT_LOGIN_TEXT}'] = $L['{T_CONTINUE_WITHOUT_LOGIN_TEXT}'];
      $A['{T_CONTINUE_WITHOUT_LOGIN}'] = $L['{T_CONTINUE_WITHOUT_LOGIN}'];
      $A['{T_LOGIN_SEND}'] = $L['{T_LOGIN_SEND}'];
      $A['{T_USERNAME}'] = $L['{T_USERNAME}'];
      $A['{T_PASSWORD}'] = $L['{T_PASSWORD}'];
      $A["{WSTEP}"] = s($Ccms->WYNIK["fo"]["st_tresc"]);
      $AT["{TRESC}"] = get_template($tpl,$A,'',0);

      $AT["{TYTUL}"] = $Ccms->get_tytul();
      $DD[] = "ITEM_AUTOR";
      $DD[] = "ITEM_DATA";
      $DD[] = "IT_OPT";
      return get_template("module",$AT,$DD);
    }  
  }
}

?>