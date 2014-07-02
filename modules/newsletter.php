<?php if(!defined('SMDESIGN')) die();

/**
 * @author Michał Bzowy
 * @since 2006-11-19 
 */


class newsletter extends mb {
  
  function newsletter() {
    if(isset($_GET["a"]) and $_GET["a"]=="nlout" and test_email($_GET["email"])) {
      $this->usun(trim($_GET["email"]));
      $this->mode = "prn_wypis";
    }
  }

  function usun($email) {
    global $db;

    $db->query("delete from mm_newsletter_email where ne_email='".a($email)."'");
    $T = $db->onerow("select ko_id from ".dn("kontrahent")." where ko_email='".a($email)."'");

    // if(is_array($T)) $db->query("update ".dn("sklep_kontrahent")." set ko_newsletter='' where ko_id=".$T[0]);
    if(is_array($T)) {
      $ids = array();
      $query = "SELECT ko_id FROM mm_sklep_kontrahent WHERE ko_email_org = '".trim($email)."'";
      $r1 = $db->get_all($query);
      foreach ($r1 as $value) {
        array_push($ids, $value['ko_id']);
      }
      $query = "SELECT ko_id FROM mm_kontrahent WHERE ko_email = '".trim($email)."'";
      $r2 = $db->get_all($query);
      foreach ($r2 as $value) {
        if (!in_array($value['ko_id'], $ids)) {
          array_push($ids, $value['ko_id']);
        }
      }

      $query = "update ".dn("sklep_kontrahent")." set ko_newsletter = NULL where ko_id IN (".implode(',', $ids).")";
      $db->query($query);
    }
  }

  function get_local() {
  global $Ccms;
    $R[] = $Ccms->get_tytul();
    return $R;
  }
  
  function wyslij($ARG,$nd) {
  global $L,$Ccms,$_GLOBAL;
    //$B["{LINK}"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&a=nl2&nd=".$nd."&imie=".$ARG["ne_imie"]."&email=".$ARG["ne_email"]."&info=".urlencode($ARG["ne_info"]);
    $B["{LINK}"] = $_GLOBAL["page_url"].$_GLOBAL['lang']."/newsletter/?a=nl2&nd=".$nd."&email=".$ARG["ne_email"];
    $B["{IMIE}"] = $ARG["ne_imie"];
    $TP = get_komunikat(array("T_EMAIL","T_EMAIL_NEWSLETTER_SIGN_".strtoupper($_GLOBAL['lang'])));
    $A["{TRESC}"] = get_template($TP["T_EMAIL_NEWSLETTER_SIGN_".strtoupper($_GLOBAL['lang'])],$B,$DB,0);    

    $M["m_to"] = s($ARG["ne_email"]);
    $M["m_message"] = get_template($TP["T_EMAIL"],$A,'',0);    
    $M["m_subject"] = $L['{T_REGISTRATION_EMAIL_SUBJECT}'];
    if(!my_mail_smtp($M)) return false;
    return true;
  }

  function newsletter_dodaj($email,$imie,$info,$nd){
  global $db;
    $A = explode("_",$nd);
    if(!count($A)) return false;
    
    foreach($A as $val){
      $Q[] = "('".a($email)."','".a($val)."','".a($imie)."')";
    }
    $q = "insert ignore into mm_newsletter_email values ".join(", ",$Q);
    $db->query($q);
    return true;
  }
  
  function get_strona() {
  global $Ccms,$db;
    $mode = "prn_form";
    if(isset($_POST["a"]) and ($_POST["a"]=="nl1" or $_POST["a"]=="newsletter")) { //Zatwierdzenie newslettera
      $ARG = get_post("notag");
      $pole = "ne_email";
      if($ARG[$pole]=="" or !test_email($ARG[$pole])) {
        $ERR[] = "- {T_ERR_EMAIL}";
      }
      $pole = "nd_";
      $jest=false;
      foreach($ARG as $key=>$val) {
        if(substr($key,0,3)=="nd_" and $val=="on") {
          $jest=true;
          $ND[] = substr($key,3);
        }
      }
      if(!$jest){
        $ERR[] = "- {T_ERR_ND}";
      }
            
      
      if(isset($ERR)) {
        onload("alert('{T_ERR_FORM} \\n".join("\\n",$ERR)."');");
      } else {
        if($this->wyslij($ARG,join("_",$ND)))$mode = "prn_wyslij";
        else $mode = "prn_mail_error";
      }
      
    } elseif(isset($_GET["a"]) and $_GET["a"]=="nl2") { //klikniecie linku w mailu
      if($this->newsletter_dodaj($_GET["email"],$_GET["imie"],urldecode($_GET["info"]),$_GET["nd"])){
        $mode = "prn_potwierdz";
      }
    } elseif($this->mode=="prn_wypis") {
      $mode = "prn_wypis";
    }
  
    switch($mode) {
      case "prn_form":
        if(isset($_POST["nd"])) {
          $ARG["nd_".$_POST["nd"]]="on";
          $ARG["ne_email"]=s($_POST["email"]);
        }
        $DD[] = "WSTEP_ITEM";
        $DA[] = "WYSLIJ";
        $DA[] = "POTWIERDZ";
        $DA[] = "WYPIS";
        $tpl = get_template("newsletter",array(),$DA);
        $tpl = get_tag($tpl, "ITEM",$item);
        $nr = $db->query("select nd_id, nd_nazwa from ".dn("newsletter_dzial")." where nd_lang='".TEMPLATE."' order by nd_id");
        $A["{ITEM}"] ="";
        while($T = $db->fetch($nr)) {
          $TT["{ID}"] = $T["nd_id"];
          $TT["{NAZWA}"] = $T["nd_nazwa"];
          $TT["{CHECKED}"] = isset($ARG["nd_".$T[0]])=="on"?'checked':'';
          $A["{ITEM}"] .= get_template($item,$TT,'',0);
        }
        $A["{EMAIL}"] = (isset($ARG["ne_email"])?s($ARG["ne_email"]):"");
        $A["{IMIE}"] = (isset($ARG["ne_imie"])?s($ARG["ne_imie"]):"");
        $A["{INFO}"] = (isset($ARG["ne_info"])?s($ARG["ne_info"]):"");

        $A["{URL}"] = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        $A["{WSTEP}"] = s($Ccms->WYNIK["fo"]["st_tresc"]);
        $AT["{TRESC}"] = get_template($tpl,$A,'',0);
      break;
      case "prn_wyslij":
        $DA[] = "FORM";
        $DA[] = "POTWIERDZ";
        $DA[] = "WYPIS";
        $AT["{TRESC}"] = get_template("newsletter",array(),$DA);
      break;  
      case "prn_potwierdz":
        $DA[] = "FORM";
        $DA[] = "WYSLIJ";
        $DA[] = "WYPIS";
        $AT["{TRESC}"] = get_template("newsletter",array(),$DA);
      break;  
      case "prn_mail_error":
        $DA[] = "WYPIS";
        $AT["{TRESC}"] = "{T_ERR_MAIL_SEND}";
      break;  
      case "prn_wypis":
        $DA[] = "FORM";
        $DA[] = "WYSLIJ";
        $DA[] = "POTWIERDZ";
        $AT["{TRESC}"] = get_template("newsletter",array(),$DA);
      break;  
    }
  
    $AT["{TYTUL}"] = $Ccms->get_tytul();
    $DD[] = "ITEM_AUTOR";
    $DD[] = "ITEM_DATA";
    $DD[] = "IT_OPT";
    return get_template("module",$AT,$DD);
  }
}
?>