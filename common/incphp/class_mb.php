<?php if ( !defined('SMDESIGN') ) {die("Hacking attempt");}
/**
 * @author Michal Bzowy
 * @since 2008-01-18 12:36:48
 *                                        
*/
class mb {
  var $MESS_ERR;
  var $MESS_INF;
  var $ERROR;
  var $WYNIK = array();
  var $mode;
  
  function mb() {
  }
  
  function test_arg() {
  }
  
  function get_strona() {
    if(method_exists($this,$this->mode)){
      $func = $this->mode;
      return $this->$func();
    }
  }
  
  function get_komunikat() {
    global $h;
    $Q[] = get_error($this->MESS_ERR);
    $Q[] = get_error($this->MESS_INF,"mess");
    return join("",$Q);
  }

  function prn_onload() {
    if(!is_array($this->WYNIK["onload"])) return;
    print(' onload="'.join(" ",$this->WYNIK["onload"]).'"');
  }
  
  function prn_login(){
  global $Cuzytkownik;
    $AX["{URL}"] = "cms.php?i=".$_GET["i"];
    return get_template("logowanie",$AX);
  }
  
  function prn_wiadomosc($str,$mode=0) {
    return get_template("wiadomosc",array("{TRESC}"=>$str));
  }
}

class speedup {
  
  var $SPEED = array();

  function set($klucz,$wartosc) {
  global $db;
    if($wartosc=='') $db->query("delete from ".dn("speedup")." where sp_klucz='".$klucz."'");
    else $db->query("replace ".dn("speedup")." values ('".$klucz."','".a($wartosc)."')");
    $this->SPEED[$klucz] = $wartosc;
  }
  
  function get($klucz) {
  global $db;
    if(isset($this->SPEED[$klucz])) return $this->SPEED[$klucz];
    
    $T = $db->onerow("select sp_wartosc from ".dn("speedup")." where sp_klucz='".$klucz."'");
    
    if(is_array($T)) $this->SPEED[$klucz] = s($T[0]);
    else $this->SPEED[$klucz] = false;

    return $this->SPEED[$klucz];
  }
}

$Cspeed = new speedup();
?>